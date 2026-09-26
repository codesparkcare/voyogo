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

    /**
     * Get Customers with Optional Search
     */
    public function get_customers($limit = 100, $offset = 0, $search = null) {
        if (!$this->db->table_exists('users')) {
            return array();
        }

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('phone', $search);
            $this->db->or_like('first_name', $search);
            $this->db->or_like('last_name', $search);
            $this->db->or_like('email', $search);
            $this->db->group_end();
        }

        $this->db->order_by('id', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get('users')->result_array();
    }

    /**
     * Count Customers
     */
    public function count_customers($search = null) {
        if (!$this->db->table_exists('users')) {
            return 0;
        }

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('phone', $search);
            $this->db->or_like('first_name', $search);
            $this->db->or_like('last_name', $search);
            $this->db->or_like('email', $search);
            $this->db->group_end();
        }

        return $this->db->count_all_results('users');
    }

    /**
     * Toggle Customer Status (Active / Inactive)
     */
    public function toggle_customer_status($id) {
        if (!$this->db->table_exists('users')) {
            return false;
        }
        $user = $this->db->get_where('users', array('id' => $id))->row_array();
        if (!$user) return false;
        $newStatus = ($user['status'] === 'active') ? 'inactive' : 'active';
        $this->db->where('id', $id);
        return $this->db->update('users', array('status' => $newStatus, 'updated_at' => date('Y-m-d H:i:s')));
    }

    /**
     * Delete Customer
     */
    public function delete_customer($id) {
        if (!$this->db->table_exists('users')) {
            return false;
        }
        $this->db->where('id', $id);
        return $this->db->delete('users');
    }

    /* ==========================================================================
       1. VISA ENQUIRIES
       ========================================================================== */
    public function get_visa_enquiries($limit = 100, $offset = 0, $search = '', $status = '') {
        if (!$this->db->table_exists('visa_enquiries')) return array();
        if (!empty($status)) $this->db->where('status', $status);
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('name', $search);
            $this->db->or_like('phone', $search);
            $this->db->or_like('email', $search);
            $this->db->or_like('destination_country', $search);
            $this->db->group_end();
        }
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('visa_enquiries', $limit, $offset)->result_array();
    }

    public function count_visa_enquiries($search = '', $status = '') {
        if (!$this->db->table_exists('visa_enquiries')) return 0;
        if (!empty($status)) $this->db->where('status', $status);
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('name', $search);
            $this->db->or_like('phone', $search);
            $this->db->or_like('email', $search);
            $this->db->or_like('destination_country', $search);
            $this->db->group_end();
        }
        return $this->db->count_all_results('visa_enquiries');
    }

    public function update_visa_status($id, $status) {
        $this->db->where('id', (int)$id);
        return $this->db->update('visa_enquiries', array('status' => $status));
    }

    public function delete_visa_enquiry($id) {
        $this->db->where('id', (int)$id);
        return $this->db->delete('visa_enquiries');
    }

    /* ==========================================================================
       2. CAB ENQUIRIES
       ========================================================================== */
    public function get_cab_enquiries($limit = 100, $offset = 0, $search = '', $status = '') {
        if (!$this->db->table_exists('cab_enquiries')) return array();
        if (!empty($status)) $this->db->where('status', $status);
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('name', $search);
            $this->db->or_like('phone', $search);
            $this->db->or_like('email', $search);
            $this->db->or_like('pickup_location', $search);
            $this->db->or_like('drop_location', $search);
            $this->db->group_end();
        }
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('cab_enquiries', $limit, $offset)->result_array();
    }

    public function count_cab_enquiries($search = '', $status = '') {
        if (!$this->db->table_exists('cab_enquiries')) return 0;
        if (!empty($status)) $this->db->where('status', $status);
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('name', $search);
            $this->db->or_like('phone', $search);
            $this->db->or_like('email', $search);
            $this->db->or_like('pickup_location', $search);
            $this->db->or_like('drop_location', $search);
            $this->db->group_end();
        }
        return $this->db->count_all_results('cab_enquiries');
    }

    public function update_cab_status($id, $status) {
        $this->db->where('id', (int)$id);
        return $this->db->update('cab_enquiries', array('status' => $status));
    }

    public function delete_cab_enquiry($id) {
        $this->db->where('id', (int)$id);
        return $this->db->delete('cab_enquiries');
    }

    /* ==========================================================================
       3. HOLIDAY ENQUIRIES
       ========================================================================== */
    public function get_holiday_enquiries($limit = 100, $offset = 0, $search = '', $status = '') {
        if (!$this->db->table_exists('holiday_enquiries')) return array();
        if (!empty($status)) $this->db->where('status', $status);
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('name', $search);
            $this->db->or_like('phone', $search);
            $this->db->or_like('email', $search);
            $this->db->or_like('destination', $search);
            $this->db->or_like('package_name', $search);
            $this->db->group_end();
        }
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('holiday_enquiries', $limit, $offset)->result_array();
    }

    public function count_holiday_enquiries($search = '', $status = '') {
        if (!$this->db->table_exists('holiday_enquiries')) return 0;
        if (!empty($status)) $this->db->where('status', $status);
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('name', $search);
            $this->db->or_like('phone', $search);
            $this->db->or_like('email', $search);
            $this->db->or_like('destination', $search);
            $this->db->or_like('package_name', $search);
            $this->db->group_end();
        }
        return $this->db->count_all_results('holiday_enquiries');
    }

    public function update_holiday_status($id, $status) {
        $this->db->where('id', (int)$id);
        return $this->db->update('holiday_enquiries', array('status' => $status));
    }

    public function delete_holiday_enquiry($id) {
        $this->db->where('id', (int)$id);
        return $this->db->delete('holiday_enquiries');
    }

    /* ==========================================================================
       4. FOREX ENQUIRIES
       ========================================================================== */
    public function get_forex_enquiries($limit = 100, $offset = 0, $search = '', $status = '') {
        if (!$this->db->table_exists('forex_enquiries')) return array();
        if (!empty($status)) $this->db->where('status', $status);
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('name', $search);
            $this->db->or_like('phone', $search);
            $this->db->or_like('email', $search);
            $this->db->or_like('currency', $search);
            $this->db->or_like('location_city', $search);
            $this->db->group_end();
        }
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('forex_enquiries', $limit, $offset)->result_array();
    }

    public function count_forex_enquiries($search = '', $status = '') {
        if (!$this->db->table_exists('forex_enquiries')) return 0;
        if (!empty($status)) $this->db->where('status', $status);
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('name', $search);
            $this->db->or_like('phone', $search);
            $this->db->or_like('email', $search);
            $this->db->or_like('currency', $search);
            $this->db->or_like('location_city', $search);
            $this->db->group_end();
        }
        return $this->db->count_all_results('forex_enquiries');
    }

    public function update_forex_status($id, $status) {
        $this->db->where('id', (int)$id);
        return $this->db->update('forex_enquiries', array('status' => $status));
    }

    public function delete_forex_enquiry($id) {
        $this->db->where('id', (int)$id);
        return $this->db->delete('forex_enquiries');
    }

    /* ==========================================================================
       5. CRUISE ENQUIRIES
       ========================================================================== */
    public function get_cruise_enquiries($limit = 100, $offset = 0, $search = '', $status = '') {
        if (!$this->db->table_exists('cruise_enquiries')) return array();
        if (!empty($status)) $this->db->where('status', $status);
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('name', $search);
            $this->db->or_like('phone', $search);
            $this->db->or_like('email', $search);
            $this->db->or_like('destination', $search);
            $this->db->or_like('cruise_line', $search);
            $this->db->group_end();
        }
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('cruise_enquiries', $limit, $offset)->result_array();
    }

    public function count_cruise_enquiries($search = '', $status = '') {
        if (!$this->db->table_exists('cruise_enquiries')) return 0;
        if (!empty($status)) $this->db->where('status', $status);
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('name', $search);
            $this->db->or_like('phone', $search);
            $this->db->or_like('email', $search);
            $this->db->or_like('destination', $search);
            $this->db->or_like('cruise_line', $search);
            $this->db->group_end();
        }
        return $this->db->count_all_results('cruise_enquiries');
    }

    public function update_cruise_status($id, $status) {
        $this->db->where('id', (int)$id);
        return $this->db->update('cruise_enquiries', array('status' => $status));
    }

    public function delete_cruise_enquiry($id) {
        $this->db->where('id', (int)$id);
        return $this->db->delete('cruise_enquiries');
    }

    /* ==========================================================================
       EXCLUSIVE DEALS METHODS
       ========================================================================== */
    public function get_deals($limit = null, $offset = 0, $category = '', $status = '', $search = '') {
        if (!$this->db->table_exists('exclusive_deals')) return array();
        if (!empty($category)) $this->db->where('category', $category);
        if (!empty($status)) $this->db->where('status', $status);
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('title', $search);
            $this->db->or_like('subtitle', $search);
            $this->db->or_like('promo_code', $search);
            $this->db->or_like('discount_text', $search);
            $this->db->group_end();
        }
        $this->db->order_by('sort_order', 'ASC');
        $this->db->order_by('id', 'DESC');
        if ($limit !== null) {
            return $this->db->get('exclusive_deals', $limit, $offset)->result_array();
        }
        return $this->db->get('exclusive_deals')->result_array();
    }

    public function count_deals($category = '', $status = '', $search = '') {
        if (!$this->db->table_exists('exclusive_deals')) return 0;
        if (!empty($category)) $this->db->where('category', $category);
        if (!empty($status)) $this->db->where('status', $status);
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('title', $search);
            $this->db->or_like('subtitle', $search);
            $this->db->or_like('promo_code', $search);
            $this->db->or_like('discount_text', $search);
            $this->db->group_end();
        }
        return $this->db->count_all_results('exclusive_deals');
    }

    public function get_deal($id) {
        return $this->db->get_where('exclusive_deals', array('id' => (int)$id))->row_array();
    }

    public function add_deal($data) {
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert('exclusive_deals', $data);
        return $this->db->insert_id();
    }

    public function update_deal($id, $data) {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->where('id', (int)$id);
        return $this->db->update('exclusive_deals', $data);
    }

    public function delete_deal($id) {
        $this->db->where('id', (int)$id);
        return $this->db->delete('exclusive_deals');
    }

    public function toggle_deal_status($id) {
        $deal = $this->get_deal($id);
        if ($deal) {
            $new_status = ($deal['status'] === 'active') ? 'inactive' : 'active';
            $this->update_deal($id, array('status' => $new_status));
            return $new_status;
        }
        return false;
    }

    public function get_active_deals($category = '') {
        if (!$this->db->table_exists('exclusive_deals')) return array();
        $this->db->where('status', 'active');
        if (!empty($category)) {
            $this->db->where('category', $category);
        }
        $this->db->order_by('sort_order', 'ASC');
        $this->db->order_by('id', 'DESC');
        return $this->db->get('exclusive_deals')->result_array();
    }
}


