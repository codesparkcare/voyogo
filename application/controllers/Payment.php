<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Payment Controller
 * 
 * Handles Razorpay Standard Web Checkout:
 * 1. POST /api/create-order  -> Initiates a server-side order with Razorpay
 * 2. POST /api/verify-payment -> Verifies HMAC-SHA256 signature
 */
class Payment extends CI_Controller {

    protected $key_id;
    protected $key_secret;
    protected $currency;
    protected $merchant_name;

    public function __construct() {
        parent::__construct();
        $this->load->model('Admin_model');

        // Load credentials from environment (.env) with DB fallback
        $settings = $this->Admin_model->get_razorpay_settings();
        $this->key_id        = getenv('RAZORPAY_KEY_ID') ?: ($settings['razorpay_key_id'] ?? '');
        $this->key_secret    = getenv('RAZORPAY_KEY_SECRET') ?: ($settings['razorpay_key_secret'] ?? '');
        $this->currency      = !empty($settings['currency']) ? $settings['currency'] : 'INR';
        $this->merchant_name = !empty($settings['merchant_name']) ? $settings['merchant_name'] : 'Voyogo Travels';
    }

    /**
     * STEP 1: Backend - Create Order
     * Endpoint: POST /api/create-order
     * 
     * Request Body: { amount (paise), currency (optional), receipt (optional) }
     * Returns: { order_id, amount, currency, key_id }
     */
    public function create_order() {
        header('Content-Type: application/json; charset=utf-8');

        // Ensure POST request
        if ($this->input->method(TRUE) !== 'POST') {
            http_response_code(405);
            echo json_encode(array('status' => 'error', 'message' => 'Method not allowed. Use POST.'));
            return;
        }

        // Parse input from POST or JSON raw body
        $rawBody = file_get_contents('php://input');
        $jsonInput = json_decode($rawBody, true) ?: array();
        $params = array_merge($this->input->post(), $jsonInput);

        // Determine amount in paise
        $amount = 0;
        if (isset($params['amount'])) {
            $amount = (int)$params['amount'];
        } elseif (isset($params['amount_rupees'])) {
            $amount = (int)round((float)$params['amount_rupees'] * 100);
        }

        // Validation: Minimum 100 paise (₹1.00)
        if ($amount < 100) {
            http_response_code(400);
            echo json_encode(array(
                'status'  => 'error',
                'message' => 'Amount must be at least 100 paise (₹1.00)'
            ));
            return;
        }

        if (empty($this->key_id) || empty($this->key_secret)) {
            http_response_code(500);
            echo json_encode(array(
                'status'  => 'error',
                'message' => 'Razorpay API credentials are not configured.'
            ));
            return;
        }

        $currency = !empty($params['currency']) ? strtoupper(trim($params['currency'])) : $this->currency;
        $receipt  = !empty($params['receipt']) ? trim($params['receipt']) : ('rcpt_' . time() . '_' . rand(1000, 9999));
        $notes    = !empty($params['notes']) && is_array($params['notes']) ? $params['notes'] : array(
            'platform' => 'Voyogo Web Checkout',
            'service'  => $params['service'] ?? 'Travel Booking'
        );

        $orderPayload = array(
            'amount'          => $amount,
            'currency'        => $currency,
            'receipt'         => $receipt,
            'payment_capture' => 1,
            'notes'           => $notes
        );

        // Call Razorpay API: POST https://api.razorpay.com/v1/orders
        $orderResponse = $this->callRazorpayApi('https://api.razorpay.com/v1/orders', $orderPayload);

        if ($orderResponse['http_code'] === 200 || $orderResponse['http_code'] === 201) {
            $data = $orderResponse['data'];
            http_response_code(200);
            echo json_encode(array(
                'status'        => 'success',
                'order_id'      => $data['id'],
                'amount'        => $data['amount'],
                'currency'      => $data['currency'],
                'key_id'        => $this->key_id,
                'merchant_name' => $this->merchant_name,
                'receipt'       => $data['receipt'] ?? $receipt
            ));
        } elseif ($orderResponse['http_code'] === 401) {
            http_response_code(401);
            echo json_encode(array(
                'status'  => 'error',
                'message' => 'Razorpay Authentication Failed: Invalid Key ID or Key Secret.'
            ));
        } else {
            http_response_code(500);
            $errDesc = $orderResponse['data']['error']['description'] ?? 'Failed to create Razorpay order.';
            echo json_encode(array(
                'status'  => 'error',
                'message' => $errDesc,
                'details' => $orderResponse['data'] ?? null
            ));
        }
    }

    /**
     * STEP 3: Backend - Verify Payment Signature
     * Endpoint: POST /api/verify-payment
     * 
     * Request Body: { razorpay_order_id, razorpay_payment_id, razorpay_signature }
     * Algorithm: HMAC-SHA256(order_id + "|" + payment_id, KEY_SECRET)
     */
    public function verify_payment() {
        header('Content-Type: application/json; charset=utf-8');

        if ($this->input->method(TRUE) !== 'POST') {
            http_response_code(405);
            echo json_encode(array('status' => 'error', 'message' => 'Method not allowed. Use POST.'));
            return;
        }

        $rawBody = file_get_contents('php://input');
        $jsonInput = json_decode($rawBody, true) ?: array();
        $params = array_merge($this->input->post(), $jsonInput);

        $orderId   = trim($params['razorpay_order_id'] ?? ($params['order_id'] ?? ''));
        $paymentId = trim($params['razorpay_payment_id'] ?? ($params['payment_id'] ?? ''));
        $signature = trim($params['razorpay_signature'] ?? ($params['signature'] ?? ''));

        // Validate required fields
        if (empty($orderId) || empty($paymentId) || empty($signature)) {
            http_response_code(400);
            echo json_encode(array(
                'status'  => 'error',
                'message' => 'Missing required fields: razorpay_order_id, razorpay_payment_id, and razorpay_signature are required.'
            ));
            return;
        }

        if (empty($this->key_secret)) {
            http_response_code(500);
            echo json_encode(array(
                'status'  => 'error',
                'message' => 'Razorpay Key Secret is not configured.'
            ));
            return;
        }

        // Verification Algorithm: HMAC-SHA256(order_id + "|" + payment_id, KEY_SECRET)
        $expectedSignature = hash_hmac('sha256', $orderId . '|' . $paymentId, $this->key_secret);

        if (hash_equals($expectedSignature, $signature)) {
            http_response_code(200);
            echo json_encode(array(
                'status'     => 'success',
                'verified'   => true,
                'message'    => 'Payment signature verified successfully.',
                'order_id'   => $orderId,
                'payment_id' => $paymentId
            ));
        } else {
            // Signature mismatch - do NOT mark as paid
            http_response_code(400);
            echo json_encode(array(
                'status'   => 'error',
                'verified' => false,
                'message'  => 'Signature mismatch: Payment verification failed.'
            ));
        }
    }

    /**
     * Helper to call Razorpay REST API directly with Basic Authentication
     */
    protected function callRazorpayApi($url, $payload = array()) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $this->key_id . ':' . $this->key_secret);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'User-Agent: Voyogo-Razorpay-Client/1.0'
        ));
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 8);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        $data = null;
        if (!empty($response)) {
            $data = json_decode($response, true);
        }

        return array(
            'http_code' => $httpCode,
            'response'  => $response,
            'data'      => $data,
            'error'     => $curlError
        );
    }
}
