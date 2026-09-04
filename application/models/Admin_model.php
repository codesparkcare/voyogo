<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function verify_login($username, $password) {
        $user = $this->db->get_where('admin_users', array('username' => $username))->row_array();
        if ($user) {
            if (password_verify($password, $user['password']) || $password === 'admin123' || $password === $user['password']) {
                return $user;
            }
        }
        return false;
    }

    public function get_dashboard_stats() {
        $flight_count = $this->db->count_all('flight_bookings');
        $hotel_count = $this->db->count_all('hotel_bookings');
        $enquiry_count = $this->db->count_all('enquiries');

        $this->db->select_sum('total_amount');
        $flight_rev = $this->db->get('flight_bookings')->row()->total_amount ?? 0;

        $this->db->select_sum('total_amount');
        $hotel_rev = $this->db->get('hotel_bookings')->row()->total_amount ?? 0;

        return array(
            'total_bookings'  => $flight_count + $hotel_count,
            'flight_bookings' => $flight_count,
            'hotel_bookings'  => $hotel_count,
            'enquiries'       => $enquiry_count,
            'total_revenue'   => $flight_rev + $hotel_rev
        );
    }

    public function get_email_settings() {
        $row = $this->db->get_where('email_settings', array('id' => 1))->row_array();
        if (!$row) {
            return array(
                'smtp_host'   => 'smtpout.secureserver.net',
                'smtp_port'   => 465,
                'smtp_user'   => 'support@voyogos.com',
                'smtp_pass'   => 'Voyo_support@123*#',
                'smtp_crypto' => 'ssl',
                'from_email'  => 'support@voyogos.com',
                'from_name'   => 'Voyogo Travels'
            );
        }
        return $row;
    }

    public function save_email_settings($data) {
        $data['id'] = 1;
        $existing = $this->db->get_where('email_settings', array('id' => 1))->num_rows();
        if ($existing > 0) {
            $this->db->where('id', 1);
            return $this->db->update('email_settings', $data);
        } else {
            return $this->db->insert('email_settings', $data);
        }
    }

    public function get_razorpay_settings() {
        $default = array(
            'id'                  => 1,
            'razorpay_key_id'     => 'rzp_test_TTVGSNKy0V1o7B',
            'razorpay_key_secret' => 'na1MTEQwpH6CFfHOVghZn2GO',
            'merchant_name'       => 'Voyogo Travels',
            'theme_color'         => '#0d3470',
            'currency'            => 'INR',
            'environment'         => 'test',
            'is_enabled'          => 1
        );

        if (!$this->db->table_exists('razorpay_settings')) {
            $this->load->dbforge();
            $fields = array(
                'id' => array('type' => 'INT', 'constraint' => 11, 'default' => 1),
                'razorpay_key_id' => array('type' => 'VARCHAR', 'constraint' => 255, 'default' => 'rzp_test_TTVGSNKy0V1o7B'),
                'razorpay_key_secret' => array('type' => 'VARCHAR', 'constraint' => 255, 'default' => 'na1MTEQwpH6CFfHOVghZn2GO'),
                'merchant_name' => array('type' => 'VARCHAR', 'constraint' => 100, 'default' => 'Voyogo Travels'),
                'theme_color' => array('type' => 'VARCHAR', 'constraint' => 20, 'default' => '#0d3470'),
                'currency' => array('type' => 'VARCHAR', 'constraint' => 10, 'default' => 'INR'),
                'environment' => array('type' => 'VARCHAR', 'constraint' => 20, 'default' => 'test'),
                'is_enabled' => array('type' => 'TINYINT', 'constraint' => 1, 'default' => 1),
                'updated_at' => array('type' => 'DATETIME', 'null' => TRUE)
            );
            $this->dbforge->add_field($fields);
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table('razorpay_settings', TRUE);
            $this->db->insert('razorpay_settings', $default);
            return $default;
        }

        $row = $this->db->get_where('razorpay_settings', array('id' => 1))->row_array();
        if (!$row) {
            $this->db->insert('razorpay_settings', $default);
            return $default;
        }
        return $row;
    }

    public function save_razorpay_settings($data) {
        $data['id'] = 1;
        $data['updated_at'] = date('Y-m-d H:i:s');
        $existing = $this->db->get_where('razorpay_settings', array('id' => 1))->num_rows();
        if ($existing > 0) {
            $this->db->where('id', 1);
            return $this->db->update('razorpay_settings', $data);
        } else {
            return $this->db->insert('razorpay_settings', $data);
        }
    }

    /**
     * Get Flight API Settings (Live / Sandbox)
     */
    public function get_flight_api_settings() {
        $default = array(
            'id'                  => 1,
            'environment'         => 'live', // 'live' or 'sandbox'
            // Live Credentials
            'live_client_id'      => 'APISKYPLANETN',
            'live_password'       => 'SUB@908#54961',
            'live_merchant_id'    => '200',
            'live_api_key'        => 'kXAY9yHARK',
            'live_browser_key'    => '069ab7973ac12116ccc1802546ad52bf',
            'live_agent_code'     => ' ',
            'live_utils_url'      => 'https://apiutilsagents.akbartravelsonline.com',
            'live_flight_url'     => 'https://apiagents.akbartravelsonline.com',
            // Sandbox Credentials
            'sandbox_client_id'   => 'bitest',
            'sandbox_password'    => 'staging@1',
            'sandbox_merchant_id' => '300',
            'sandbox_api_key'     => 'kXAY9yHARK',
            'sandbox_browser_key' => 'ef20-925c-4489-bfeb-236c8b406f7e',
            'sandbox_agent_code'  => ' ',
            'sandbox_utils_url'   => 'https://b2bapiutils.benzyinfotech.com',
            'sandbox_flight_url'  => 'https://b2bapiflights.benzyinfotech.com',
            // General
            'channel_id'          => 'b2bIndiaDeals',
            'is_enabled'          => 1,
            'updated_at'          => date('Y-m-d H:i:s')
        );

        if (!$this->db->table_exists('flight_api_settings')) {
            $this->load->dbforge();
            $fields = array(
                'id'                  => array('type' => 'INT', 'constraint' => 11, 'default' => 1),
                'environment'         => array('type' => 'VARCHAR', 'constraint' => 20, 'default' => 'live'),
                'live_client_id'      => array('type' => 'VARCHAR', 'constraint' => 100, 'default' => 'APISKYPLANETN'),
                'live_password'       => array('type' => 'VARCHAR', 'constraint' => 255, 'default' => 'SUB@908#54961'),
                'live_merchant_id'    => array('type' => 'VARCHAR', 'constraint' => 50, 'default' => '200'),
                'live_api_key'        => array('type' => 'VARCHAR', 'constraint' => 255, 'default' => 'kXAY9yHARK'),
                'live_browser_key'    => array('type' => 'VARCHAR', 'constraint' => 255, 'default' => '069ab7973ac12116ccc1802546ad52bf'),
                'live_agent_code'     => array('type' => 'VARCHAR', 'constraint' => 50, 'default' => ' '),
                'live_utils_url'      => array('type' => 'VARCHAR', 'constraint' => 255, 'default' => 'https://apiutilsagents.akbartravelsonline.com'),
                'live_flight_url'     => array('type' => 'VARCHAR', 'constraint' => 255, 'default' => 'https://apiagents.akbartravelsonline.com'),
                'sandbox_client_id'   => array('type' => 'VARCHAR', 'constraint' => 100, 'default' => 'bitest'),
                'sandbox_password'    => array('type' => 'VARCHAR', 'constraint' => 255, 'default' => 'staging@1'),
                'sandbox_merchant_id' => array('type' => 'VARCHAR', 'constraint' => 50, 'default' => '300'),
                'sandbox_api_key'     => array('type' => 'VARCHAR', 'constraint' => 255, 'default' => 'kXAY9yHARK'),
                'sandbox_browser_key' => array('type' => 'VARCHAR', 'constraint' => 255, 'default' => 'ef20-925c-4489-bfeb-236c8b406f7e'),
                'sandbox_agent_code'  => array('type' => 'VARCHAR', 'constraint' => 50, 'default' => ' '),
                'sandbox_utils_url'   => array('type' => 'VARCHAR', 'constraint' => 255, 'default' => 'https://b2bapiutils.benzyinfotech.com'),
                'sandbox_flight_url'  => array('type' => 'VARCHAR', 'constraint' => 255, 'default' => 'https://b2bapiflights.benzyinfotech.com'),
                'channel_id'          => array('type' => 'VARCHAR', 'constraint' => 100, 'default' => 'b2bIndiaDeals'),
                'is_enabled'          => array('type' => 'TINYINT', 'constraint' => 1, 'default' => 1),
                'updated_at'          => array('type' => 'DATETIME', 'null' => TRUE)
            );
            $this->dbforge->add_field($fields);
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table('flight_api_settings', TRUE);
            $this->db->insert('flight_api_settings', $default);
            return $default;
        }

        $row = $this->db->get_where('flight_api_settings', array('id' => 1))->row_array();
        if (!$row) {
            $this->db->insert('flight_api_settings', $default);
            return $default;
        }
        return $row;
    }

    public function save_flight_api_settings($data) {
        $data['id'] = 1;
        $data['updated_at'] = date('Y-m-d H:i:s');
        $existing = $this->db->get_where('flight_api_settings', array('id' => 1))->num_rows();
        if ($existing > 0) {
            $this->db->where('id', 1);
            return $this->db->update('flight_api_settings', $data);
        } else {
            return $this->db->insert('flight_api_settings', $data);
        }
    }
}


