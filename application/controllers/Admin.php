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
            $config = array(
                'protocol'  => !empty($settings['smtp_host']) ? 'smtp' : 'mail',
                'smtp_host' => $settings['smtp_host'],
                'smtp_port' => (int)$settings['smtp_port'],
                'smtp_user' => $settings['smtp_user'],
                'smtp_pass' => $settings['smtp_pass'],
                'smtp_crypto' => $settings['smtp_crypto'],
                'mailtype'  => 'html',
                'charset'   => 'utf-8',
                'newline'   => "\r\n"
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
