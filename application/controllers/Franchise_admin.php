<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Franchise_admin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('url', 'form'));
        $this->load->library(array('session', 'form_validation'));
        $this->load->model('Franchise_model');
    }

    private function _check_auth() {
        if (!$this->session->userdata('franchise_admin_logged_in')) {
            redirect('franchise-admin/login');
            exit;
        }
    }

    // =========================================================================
    // 1. AUTHENTICATION
    // =========================================================================
    public function login() {
        if ($this->session->userdata('franchise_admin_logged_in')) {
            redirect('franchise-admin');
            return;
        }

        $data['error'] = '';
        if ($this->input->method() === 'post') {
            $username = trim($this->input->post('username'));
            $password = trim($this->input->post('password'));

            $admin = $this->Franchise_model->admin_login($username, $password);
            if ($admin) {
                $this->session->set_userdata(array(
                    'franchise_admin_logged_in' => true,
                    'franchise_admin_id'        => $admin['id'],
                    'franchise_admin_username'  => $admin['username'],
                    'franchise_admin_name'      => $admin['name']
                ));
                redirect('franchise-admin');
                return;
            } else {
                $data['error'] = 'Invalid username or password.';
            }
        }

        $this->load->view('franchise_admin/login', $data);
    }

    public function logout() {
        $this->session->unset_userdata(array(
            'franchise_admin_logged_in',
            'franchise_admin_id',
            'franchise_admin_username',
            'franchise_admin_name'
        ));
        $this->session->set_flashdata('success', 'Logged out successfully.');
        redirect('franchise-admin/login');
    }

    // =========================================================================
    // 2. DASHBOARD
    // =========================================================================
    public function index() {
        $this->_check_auth();

        $data['title']        = 'Franchise Admin Dashboard';
        $data['active_menu']  = 'dashboard';
        $data['stats']        = $this->Franchise_model->get_admin_dashboard_stats();
        $data['recent_stores']= $this->Franchise_model->get_all_stores();
        $data['recent_tx']    = $this->Franchise_model->get_wallet_ledger(null, 10);
        $data['admin_name']   = $this->session->userdata('franchise_admin_name');

        $this->load->view('franchise_admin/layout/header', $data);
        $this->load->view('franchise_admin/dashboard', $data);
        $this->load->view('franchise_admin/layout/footer');
    }

    // =========================================================================
    // 3. STORE OWNERS MANAGEMENT
    // =========================================================================
    public function stores() {
        $this->_check_auth();

        $search = $this->input->get('q');
        $data['title']       = 'Manage Franchise Stores';
        $data['active_menu'] = 'stores';
        $data['stores']      = $this->Franchise_model->get_all_stores($search);
        $data['search']      = $search;

        $this->load->view('franchise_admin/layout/header', $data);
        $this->load->view('franchise_admin/stores', $data);
        $this->load->view('franchise_admin/layout/footer');
    }

    public function store_create() {
        $this->_check_auth();

        if ($this->input->method() !== 'post') {
            redirect('franchise-admin/stores');
            return;
        }

        $agent_code = trim($this->input->post('agent_code'));
        $username   = trim($this->input->post('username'));
        $password   = trim($this->input->post('password'));
        $store_name = trim($this->input->post('store_name'));
        $phone      = trim($this->input->post('phone'));
        $email      = trim($this->input->post('email'));
        $address    = trim($this->input->post('address'));
        $gst_number = trim($this->input->post('gst_number'));
        $init_wallet= (float)$this->input->post('initial_wallet');

        if (empty($agent_code) || empty($username) || empty($password) || empty($store_name) || empty($phone) || empty($email)) {
            $this->session->set_flashdata('error', 'Please fill all required fields.');
            redirect('franchise-admin/stores');
            return;
        }

        if ($this->Franchise_model->check_agent_code_exists($agent_code)) {
            $this->session->set_flashdata('error', 'Agent Code already exists. Please choose a unique code.');
            redirect('franchise-admin/stores');
            return;
        }

        if ($this->Franchise_model->check_username_exists($username)) {
            $this->session->set_flashdata('error', 'Username already taken. Please choose another username.');
            redirect('franchise-admin/stores');
            return;
        }

        $store_id = $this->Franchise_model->create_store(array(
            'agent_code'     => $agent_code,
            'username'       => $username,
            'password'       => password_hash($password, PASSWORD_DEFAULT),
            'store_name'     => $store_name,
            'phone'          => $phone,
            'email'          => $email,
            'address'        => $address,
            'gst_number'     => $gst_number,
            'wallet_balance' => 0.00,
            'status'         => 'active'
        ));

        if ($store_id && $init_wallet > 0) {
            $this->Franchise_model->update_wallet_balance(
                $store_id,
                'credit',
                $init_wallet,
                'admin_initial_setup',
                null,
                'Initial Wallet Top-up on Store Creation',
                $this->session->userdata('franchise_admin_username')
            );
        }

        $this->session->set_flashdata('success', 'Franchise Store Owner "' . htmlspecialchars($store_name) . '" created successfully!');
        redirect('franchise-admin/stores');
    }

    public function store_edit($id) {
        $this->_check_auth();

        $store = $this->Franchise_model->get_store_by_id($id);
        if (!$store) {
            $this->session->set_flashdata('error', 'Store not found.');
            redirect('franchise-admin/stores');
            return;
        }

        if ($this->input->method() === 'post') {
            $store_name = trim($this->input->post('store_name'));
            $phone      = trim($this->input->post('phone'));
            $email      = trim($this->input->post('email'));
            $address    = trim($this->input->post('address'));
            $gst_number = trim($this->input->post('gst_number'));
            $new_pass   = trim($this->input->post('password'));

            $update_data = array(
                'store_name' => $store_name,
                'phone'      => $phone,
                'email'      => $email,
                'address'    => $address,
                'gst_number' => $gst_number
            );

            if (!empty($new_pass)) {
                $update_data['password'] = password_hash($new_pass, PASSWORD_DEFAULT);
            }

            $this->Franchise_model->update_store($id, $update_data);
            $this->session->set_flashdata('success', 'Store details updated successfully!');
            redirect('franchise-admin/stores');
            return;
        }

        redirect('franchise-admin/stores');
    }

    public function store_toggle($id) {
        $this->_check_auth();
        $new_status = $this->Franchise_model->toggle_store_status($id);

        if ($new_status) {
            $this->session->set_flashdata('success', 'Store status updated to ' . strtoupper($new_status) . ' successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to update store status.');
        }
        redirect('franchise-admin/stores');
    }

    // =========================================================================
    // 4. WALLET MANAGEMENT & MANUAL TOP-UP
    // =========================================================================
    public function wallets() {
        $this->_check_auth();

        $data['title']       = 'Wallet Management';
        $data['active_menu'] = 'wallets';
        $data['stores']      = $this->Franchise_model->get_all_stores();
        $data['ledger']      = $this->Franchise_model->get_wallet_ledger(null, 100);

        $this->load->view('franchise_admin/layout/header', $data);
        $this->load->view('franchise_admin/wallets', $data);
        $this->load->view('franchise_admin/layout/footer');
    }

    public function wallet_update() {
        $this->_check_auth();

        if ($this->input->method() !== 'post') {
            redirect('franchise-admin/wallets');
            return;
        }

        $store_id = (int)$this->input->post('store_id');
        $type     = trim($this->input->post('transaction_type')); // 'credit' or 'debit'
        $amount   = (float)$this->input->post('amount');
        $remarks  = trim($this->input->post('remarks'));

        if ($amount <= 0) {
            $this->session->set_flashdata('error', 'Amount must be greater than zero.');
            redirect('franchise-admin/wallets');
            return;
        }

        $res = $this->Franchise_model->update_wallet_balance(
            $store_id,
            $type,
            $amount,
            'admin_manual',
            null,
            $remarks ?: ('Manual ' . ucfirst($type) . ' by Admin'),
            $this->session->userdata('franchise_admin_username')
        );

        if ($res['status']) {
            $this->session->set_flashdata('success', 'Wallet ' . ucfirst($type) . ' of ₹ ' . number_format($amount, 2) . ' applied successfully! New Balance: ₹ ' . number_format($res['new_balance'], 2));
        } else {
            $this->session->set_flashdata('error', 'Transaction Failed: ' . ($res['error'] ?? 'Unknown error'));
        }

        redirect('franchise-admin/wallets');
    }

    // =========================================================================
    // 5. MASTER BOOKINGS HISTORY
    // =========================================================================
    public function bookings() {
        $this->_check_auth();

        $data['title']           = 'Master Booking History';
        $data['active_menu']     = 'bookings';
        $data['flight_bookings'] = $this->Franchise_model->get_all_flight_bookings(100);
        $data['hotel_bookings']  = $this->Franchise_model->get_all_hotel_bookings(100);

        $this->load->view('franchise_admin/layout/header', $data);
        $this->load->view('franchise_admin/bookings', $data);
        $this->load->view('franchise_admin/layout/footer');
    }
}
