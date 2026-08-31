<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('url', 'form'));
        $this->load->library('session');
        $this->load->model('Admin_model');
        $this->load->model('Booking_model');
    }

    /**
     * Check Session Helper
     */
    private function _check_login() {
        if (!$this->session->userdata('admin_logged_in')) {
            redirect('admin/login');
        }
    }

    /**
     * Admin Dashboard
     */
    public function index()
    {
        $this->_check_login();

        $data['stats'] = $this->Admin_model->get_dashboard_stats();
        $data['recent_flights'] = $this->Booking_model->get_all_flight_bookings(5);
        $data['recent_hotels']  = $this->Booking_model->get_all_hotel_bookings(5);
        $data['active_menu'] = 'dashboard';

        $this->load->view('admin/layout/header', $data);
        $this->load->view('admin/layout/sidebar', $data);
        $this->load->view('admin/dashboard', $data);
        $this->load->view('admin/layout/footer', $data);
    }

    /**
     * Admin Login View & Action
     */
    public function login()
    {
        if ($this->session->userdata('admin_logged_in')) {
            redirect('admin');
        }

        if ($this->input->post()) {
            $username = $this->input->post('username');
            $password = $this->input->post('password');

            $user = $this->Admin_model->verify_login($username, $password);

            if ($user) {
                $this->session->set_userdata(array(
                    'admin_id' => $user['id'],
                    'admin_username' => $user['username'],
                    'admin_email' => $user['email'],
                    'admin_logged_in' => TRUE
                ));
                redirect('admin');
            } else {
                $data['error'] = 'Invalid Username or Password!';
            }
        }

        $this->load->view('admin/login', isset($data) ? $data : NULL);
    }

    /**
     * Admin Logout
     */
    public function logout()
    {
        $this->session->unset_userdata(array('admin_id', 'admin_username', 'admin_email', 'admin_logged_in'));
        $this->session->sess_destroy();
        redirect('admin/login');
    }

    /**
     * Manage Flight Bookings
     */
    public function manage_flight_bookings()
    {
        $this->_check_login();

        $data['bookings'] = $this->Booking_model->get_all_flight_bookings(100);
        $data['active_menu'] = 'flight_bookings';

        $this->load->view('admin/layout/header', $data);
        $this->load->view('admin/layout/sidebar', $data);
        $this->load->view('admin/manage_flight_bookings', $data);
        $this->load->view('admin/layout/footer', $data);
    }

    /**
     * Update Flight Booking Status
     */
    public function update_flight_status()
    {
        $this->_check_login();
        $id = $this->input->post('booking_id');
        $status = $this->input->post('booking_status');
        $payment_status = $this->input->post('payment_status');

        $this->Booking_model->update_flight_booking_status($id, $status, $payment_status);
        $this->session->set_flashdata('success', 'Flight booking status updated successfully!');
        redirect('admin/manage_flight_bookings');
    }

    /**
     * Manage Hotel Bookings
     */
    public function manage_hotel_bookings()
    {
        $this->_check_login();

        $data['bookings'] = $this->Booking_model->get_all_hotel_bookings(100);
        $data['active_menu'] = 'hotel_bookings';

        $this->load->view('admin/layout/header', $data);
        $this->load->view('admin/layout/sidebar', $data);
        $this->load->view('admin/manage_hotel_bookings', $data);
        $this->load->view('admin/layout/footer', $data);
    }

    /**
     * Update Hotel Booking Status
     */
    public function update_hotel_status()
    {
        $this->_check_login();
        $id = $this->input->post('booking_id');
        $status = $this->input->post('booking_status');
        $payment_status = $this->input->post('payment_status');

        $this->Booking_model->update_hotel_booking_status($id, $status, $payment_status);
        $this->session->set_flashdata('success', 'Hotel booking status updated successfully!');
        redirect('admin/manage_hotel_bookings');
    }

    /**
     * View Enquiries
     */
    public function enquiries()
    {
        $this->_check_login();

        $data['enquiries'] = $this->Booking_model->get_all_enquiries(100);
        $data['active_menu'] = 'enquiries';

        $this->load->view('admin/layout/header', $data);
        $this->load->view('admin/layout/sidebar', $data);
        $this->load->view('admin/manage_enquiries', $data);
        $this->load->view('admin/layout/footer', $data);
    }

    /**
     * SMTP Email Settings Management
     */
    public function email_settings()
    {
        $this->_check_login();

        if ($this->input->post('action') === 'save_settings') {
            $save_data = array(
                'smtp_host'   => trim($this->input->post('smtp_host')),
                'smtp_port'   => (int)$this->input->post('smtp_port'),
                'smtp_user'   => trim($this->input->post('smtp_user')),
                'smtp_pass'   => trim($this->input->post('smtp_pass')),
                'smtp_crypto' => trim($this->input->post('smtp_crypto')),
                'from_email'  => trim($this->input->post('from_email')),
                'from_name'   => trim($this->input->post('from_name'))
            );
            $this->Admin_model->save_email_settings($save_data);
            $this->session->set_flashdata('success', 'SMTP Email Settings saved successfully!');
            redirect('admin/email_settings');
        }

        if ($this->input->post('action') === 'test_email') {
            $test_to = trim($this->input->post('test_email_to'));
            $this->load->library('email');
            
            $settings = $this->Admin_model->get_email_settings();
            $smtp_host = $settings['smtp_host'];
            if (!empty($settings['smtp_crypto']) && strtolower($settings['smtp_crypto']) === 'ssl' && strpos($smtp_host, 'ssl://') === false) {
                $smtp_host = 'ssl://' . $smtp_host;
            }

            $config = array(
                'protocol'    => !empty($settings['smtp_host']) ? 'smtp' : 'mail',
                'smtp_host'   => $smtp_host,
                'smtp_port'   => (int)$settings['smtp_port'],
                'smtp_user'   => $settings['smtp_user'],
                'smtp_pass'   => $settings['smtp_pass'],
                'smtp_crypto' => $settings['smtp_crypto'],
                'mailtype'    => 'html',
                'charset'     => 'utf-8',
                'wordwrap'    => TRUE,
                'newline'     => "\r\n",
                'crlf'        => "\r\n"
            );
            $this->email->initialize($config);
            $this->email->from($settings['from_email'], $settings['from_name']);
            $this->email->to($test_to);
            $this->email->subject('Voyogo Test Email Configuration');
            $this->email->message('<h3>Voyogo SMTP Setup Success</h3><p>Your SMTP email configuration is working correctly!</p>');

            if (@$this->email->send()) {
                $this->session->set_flashdata('success', "Test email sent successfully to $test_to!");
            } else {
                $this->session->set_flashdata('error', "Failed to send test email. Check your SMTP host/credentials or firewall settings.");
            }
            redirect('admin/email_settings');
        }

        $data['settings'] = $this->Admin_model->get_email_settings();
        $data['active_menu'] = 'email_settings';

        $this->load->view('admin/layout/header', $data);
        $this->load->view('admin/layout/sidebar', $data);
        $this->load->view('admin/email_settings', $data);
        $this->load->view('admin/layout/footer', $data);
    }

    /**
     * Razorpay Payment Gateway Settings Management
     */
    public function razorpay_settings()
    {
        $this->_check_login();

        if ($this->input->post('action') === 'save_settings') {
            $save_data = array(
                'razorpay_key_id'     => trim($this->input->post('razorpay_key_id')),
                'razorpay_key_secret' => trim($this->input->post('razorpay_key_secret')),
                'merchant_name'       => trim($this->input->post('merchant_name')) ?: 'Voyogo Travels',
                'theme_color'         => trim($this->input->post('theme_color')) ?: '#0d3470',
                'currency'            => strtoupper(trim($this->input->post('currency'))) ?: 'INR',
                'environment'         => trim($this->input->post('environment')) ?: 'test',
                'is_enabled'          => $this->input->post('is_enabled') ? 1 : 0
            );
            $this->Admin_model->save_razorpay_settings($save_data);
            $this->session->set_flashdata('success', 'Razorpay Payment Gateway Settings saved successfully!');
            redirect('admin/razorpay_settings');
        }

        if ($this->input->post('action') === 'test_connection') {
            $key_id     = trim($this->input->post('test_key_id'));
            $key_secret = trim($this->input->post('test_key_secret'));

            if (empty($key_id) || empty($key_secret)) {
                $this->session->set_flashdata('error', 'Please provide both Razorpay Key ID and Key Secret to test connection.');
                redirect('admin/razorpay_settings');
            }

            $startTime = microtime(true);
            $ch = curl_init('https://api.razorpay.com/v1/payments?count=1');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERPWD, $key_id . ':' . $key_secret);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curl_error = curl_error($ch);
            curl_close($ch);
            $durationMs = round((microtime(true) - $startTime) * 1000);

            // Log Razorpay API call
            $this->load->model('Api_log_model');
            $this->Api_log_model->log_call(
                'razorpay',
                'VerifyCredentials',
                'https://api.razorpay.com/v1/payments?count=1',
                'GET',
                array('key_id' => $key_id),
                $response,
                $http_code,
                $durationMs,
                $curl_error
            );

            if ($http_code === 200) {
                $this->session->set_flashdata('success', 'Razorpay Connection Successful! Credentials are verified and active (HTTP 200 OK).');
            } elseif ($http_code === 401) {
                $this->session->set_flashdata('error', 'Authentication Failed (HTTP 401 Unauthorized): Invalid Razorpay Key ID or Key Secret.');
            } elseif ($curl_error) {
                $this->session->set_flashdata('error', 'Network Connection Error: ' . $curl_error);
            } else {
                $this->session->set_flashdata('error', 'Razorpay API returned status code ' . $http_code . '. Response: ' . substr($response, 0, 150));
            }
            redirect('admin/razorpay_settings');
        }

        $data['settings'] = $this->Admin_model->get_razorpay_settings();
        $data['active_menu'] = 'razorpay_settings';

        $this->load->view('admin/layout/header', $data);
        $this->load->view('admin/layout/sidebar', $data);
        $this->load->view('admin/razorpay_settings', $data);
        $this->load->view('admin/layout/footer', $data);
    }

    /**
     * API Request & Response Activity Logs Management
     */
    public function api_logs()
    {
        $this->_check_login();
        $this->load->model('Api_log_model');

        $service = $this->input->get('service') ?: 'all';
        $status  = $this->input->get('status') ?: 'all';
        $search  = trim($this->input->get('search') ?: '');
        $page    = max(1, (int)($this->input->get('page') ?: 1));
        $limit   = 30;
        $offset  = ($page - 1) * $limit;

        $filters = array(
            'service_type' => $service,
            'status'       => $status,
            'search'       => $search
        );

        $total_rows = $this->Api_log_model->count_logs($filters);
        $logs = $this->Api_log_model->get_logs($limit, $offset, $filters);
        $stats = $this->Api_log_model->get_stats();

        $total_pages = ceil($total_rows / $limit);

        $data = array(
            'logs'        => $logs,
            'stats'       => $stats,
            'total_rows'  => $total_rows,
            'total_pages' => $total_pages,
            'current_page'=> $page,
            'service'     => $service,
            'status'      => $status,
            'search'      => $search,
            'active_menu' => 'api_logs'
        );

        $this->load->view('admin/layout/header', $data);
        $this->load->view('admin/layout/sidebar', $data);
        $this->load->view('admin/api_logs', $data);
        $this->load->view('admin/layout/footer', $data);
    }

    /**
     * AJAX endpoint to view single API log payload details
     */
    public function api_log_detail($id)
    {
        $this->_check_login();
        $this->load->model('Api_log_model');

        $log = $this->Api_log_model->get_log_by_id($id);
        if (!$log) {
            $this->output
                ->set_status_header(404)
                ->set_content_type('application/json')
                ->set_output(json_encode(array('status' => 'error', 'message' => 'Log not found')));
            return;
        }

        // Prettify JSON if possible
        $reqJson = json_decode($log['request_payload'], true);
        if ($reqJson !== null) {
            $log['request_formatted'] = json_encode($reqJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        } else {
            $log['request_formatted'] = $log['request_payload'];
        }

        $respJson = json_decode($log['response_payload'], true);
        if ($respJson !== null) {
            $log['response_formatted'] = json_encode($respJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        } else {
            $log['response_formatted'] = $log['response_payload'];
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array('status' => 'success', 'data' => $log)));
    }

    /**
     * Export / Download all API logs in a single structured text/JSON file
     */
    public function api_logs_export()
    {
        $this->_check_login();
        $this->load->model('Api_log_model');

        $service = $this->input->get('service') ?: 'all';
        $status  = $this->input->get('status') ?: 'all';
        $search  = trim($this->input->get('search') ?: '');
        $limit   = max(1, min(500, (int)($this->input->get('limit') ?: 200)));

        $filters = array(
            'service_type' => $service,
            'status'       => $status,
            'search'       => $search
        );

        $logs = $this->Api_log_model->get_logs($limit, 0, $filters);

        // Sort in chronological order (oldest to newest) for readable booking sequence
        $logs = array_reverse($logs);

        $filename = 'Voyogo_API_Logs_' . date('Ymd_His') . '.txt';

        $output = "================================================================================\n";
        $output .= "VOYOGO API ACTIVITY & PAYLOAD LOG REPORT\n";
        $output .= "Generated At: " . date('Y-m-d H:i:s T') . "\n";
        $output .= "Total Entries: " . count($logs) . "\n";
        $output .= "Filter: Service=" . strtoupper($service) . ", Status=" . strtoupper($status) . "\n";
        $output .= "================================================================================\n\n";

        $step = 1;
        foreach ($logs as $log) {
            $reqJson = json_decode($log['request_payload'], true);
            $reqFormatted = ($reqJson !== null) ? json_encode($reqJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : $log['request_payload'];

            $respJson = json_decode($log['response_payload'], true);
            $respFormatted = ($respJson !== null) ? json_encode($respJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : $log['response_payload'];

            $output .= "--------------------------------------------------------------------------------\n";
            $output .= "STEP #{$step} | LOG ID: #{$log['id']} | TIMESTAMP: {$log['created_at']}\n";
            $output .= "API NAME / ACTION: {$log['action_name']}\n";
            $output .= "SERVICE: " . strtoupper($log['service_type']) . " | METHOD: " . strtoupper(isset($log['http_method']) ? $log['http_method'] : 'POST') . "\n";
            $output .= "ENDPOINT URL: {$log['endpoint_url']}\n";
            $output .= "HTTP STATUS: {$log['http_code']} | LATENCY: {$log['execution_time_ms']} ms | CLIENT IP: {$log['ip_address']}\n";
            if (!empty($log['error_message'])) {
                $output .= "ERROR: {$log['error_message']}\n";
            }
            $output .= "--------------------------------------------------------------------------------\n";
            $output .= "REQUEST BODY:\n";
            $output .= (!empty($reqFormatted) ? $reqFormatted : "{}") . "\n\n";
            $output .= "RESPONSE BODY:\n";
            $output .= (!empty($respFormatted) ? $respFormatted : "{}") . "\n";
            $output .= "================================================================================\n\n";

            $step++;
        }

        $this->load->helper('download');
        force_download($filename, $output);
    }

    /**
     * Clear API Logs
     */
    public function api_logs_clear()
    {
        $this->_check_login();
        $this->load->model('Api_log_model');

        $type = $this->input->post('type');
        if ($type === 'all') {
            $this->Api_log_model->clear_all_logs();
            $this->session->set_flashdata('success', 'All API logs cleared successfully!');
        } else {
            $this->Api_log_model->clear_old_logs(30);
            $this->session->set_flashdata('success', 'API logs older than 30 days cleared successfully!');
        }

        redirect('admin/api_logs');
    }

    /**
     * Database Installer Setup Utility
     */
    public function setup_db()
    {
        $this->_check_login();

        $schemaFile = APPPATH . 'config/schema.sql';
        if (file_exists($schemaFile)) {
            $sql = file_get_contents($schemaFile);
            $queries = explode(';', $sql);
            foreach ($queries as $q) {
                $q = trim($q);
                if (!empty($q)) {
                    $this->db->query($q);
                }
            }
            $this->session->set_flashdata('success', 'Database tables and initial admin account synchronized successfully!');
        } else {
            $this->session->set_flashdata('error', 'schema.sql file not found.');
        }

        redirect('admin');
    }
}
