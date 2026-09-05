<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hotel_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->ensure_tables_exist();
    }

    /**
     * Self-healing table creation for Hotel API Settings & Hotel Bookings
     */
    public function ensure_tables_exist() {
        $this->load->dbforge();

        // 1. hotel_api_settings table
        if (!$this->db->table_exists('hotel_api_settings')) {
            $fields = array(
                'id'                  => array('type' => 'INT', 'constraint' => 11, 'default' => 1),
                'environment'         => array('type' => 'VARCHAR', 'constraint' => 20, 'default' => 'live'),
                'live_client_id'      => array('type' => 'VARCHAR', 'constraint' => 100, 'default' => 'APISKYPLANETN'),
                'live_password'       => array('type' => 'VARCHAR', 'constraint' => 255, 'default' => 'SUB@908#54961'),
                'live_merchant_id'    => array('type' => 'VARCHAR', 'constraint' => 50, 'default' => '200'),
                'live_api_key'        => array('type' => 'VARCHAR', 'constraint' => 255, 'default' => '069ab7973ac12116ccc1802546ad52bf'),
                'live_browser_key'    => array('type' => 'VARCHAR', 'constraint' => 255, 'default' => '069ab7973ac12116ccc1802546ad52bf'),
                'live_agent_code'     => array('type' => 'VARCHAR', 'constraint' => 50, 'default' => ' '),
                'live_utils_url'      => array('type' => 'VARCHAR', 'constraint' => 255, 'default' => 'https://apiutilsagents.akbartravelsonline.com'),
                'live_hotel_url'      => array('type' => 'VARCHAR', 'constraint' => 255, 'default' => 'https://apiagents.akbartravelsonline.com'),
                'sandbox_client_id'   => array('type' => 'VARCHAR', 'constraint' => 100, 'default' => 'bitest'),
                'sandbox_password'    => array('type' => 'VARCHAR', 'constraint' => 255, 'default' => 'staging@1'),
                'sandbox_merchant_id' => array('type' => 'VARCHAR', 'constraint' => 50, 'default' => '300'),
                'sandbox_api_key'     => array('type' => 'VARCHAR', 'constraint' => 255, 'default' => 'kXAY9yHARK'),
                'sandbox_browser_key' => array('type' => 'VARCHAR', 'constraint' => 255, 'default' => 'caecd3cd30225512c1811070dce615c1'),
                'sandbox_agent_code'  => array('type' => 'VARCHAR', 'constraint' => 50, 'default' => ' '),
                'sandbox_utils_url'   => array('type' => 'VARCHAR', 'constraint' => 255, 'default' => 'https://b2bapiutils.benzyinfotech.com'),
                'sandbox_hotel_url'   => array('type' => 'VARCHAR', 'constraint' => 255, 'default' => 'https://travelportalapi.benzyinfotech.com'),
                'channel_id'          => array('type' => 'VARCHAR', 'constraint' => 100, 'default' => 'b2bIndiaDeals'),
                'is_enabled'          => array('type' => 'TINYINT', 'constraint' => 1, 'default' => 1),
                'updated_at'          => array('type' => 'DATETIME', 'null' => TRUE)
            );
            $this->dbforge->add_field($fields);
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table('hotel_api_settings', TRUE);

            $default = array(
                'id'                  => 1,
                'environment'         => 'live',
                'live_client_id'      => 'APISKYPLANETN',
                'live_password'       => 'SUB@908#54961',
                'live_merchant_id'    => '200',
                'live_api_key'        => '069ab7973ac12116ccc1802546ad52bf',
                'live_browser_key'    => '069ab7973ac12116ccc1802546ad52bf',
                'live_agent_code'     => ' ',
                'live_utils_url'      => 'https://apiutilsagents.akbartravelsonline.com',
                'live_hotel_url'      => 'https://apiagents.akbartravelsonline.com',
                'sandbox_client_id'   => 'bitest',
                'sandbox_password'    => 'staging@1',
                'sandbox_merchant_id' => '300',
                'sandbox_api_key'     => 'kXAY9yHARK',
                'sandbox_browser_key' => 'caecd3cd30225512c1811070dce615c1',
                'sandbox_agent_code'  => ' ',
                'sandbox_utils_url'   => 'https://b2bapiutils.benzyinfotech.com',
                'sandbox_hotel_url'   => 'https://travelportalapi.benzyinfotech.com',
                'channel_id'          => 'b2bIndiaDeals',
                'is_enabled'          => 1,
                'updated_at'          => date('Y-m-d H:i:s')
            );
            $this->db->insert('hotel_api_settings', $default);
        }

        // 2. hotel_bookings table (Self-Healing Table & Missing Column Migration)
        $booking_fields = array(
            'id'                  => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => TRUE),
            'booking_reference'   => array('type' => 'VARCHAR', 'constraint' => 100, 'null' => TRUE),
            'booking_ref'         => array('type' => 'VARCHAR', 'constraint' => 100, 'null' => TRUE),
            'supplier_reference'  => array('type' => 'VARCHAR', 'constraint' => 100, 'null' => TRUE),
            'transaction_id'      => array('type' => 'VARCHAR', 'constraint' => 100, 'null' => TRUE),
            'voucher_number'      => array('type' => 'VARCHAR', 'constraint' => 100, 'null' => TRUE),
            'hotel_id'            => array('type' => 'VARCHAR', 'constraint' => 100, 'null' => TRUE),
            'hotel_name'          => array('type' => 'VARCHAR', 'constraint' => 255, 'null' => TRUE),
            'hotel_address'       => array('type' => 'TEXT', 'null' => TRUE),
            'hotel_image'         => array('type' => 'TEXT', 'null' => TRUE),
            'star_rating'         => array('type' => 'INT', 'constraint' => 2, 'default' => 3),
            'room_type'           => array('type' => 'VARCHAR', 'constraint' => 255, 'null' => TRUE),
            'board_type'          => array('type' => 'VARCHAR', 'constraint' => 255, 'null' => TRUE),
            'destination_city'    => array('type' => 'VARCHAR', 'constraint' => 100, 'null' => TRUE),
            'checkin_date'        => array('type' => 'DATE', 'null' => TRUE),
            'checkout_date'       => array('type' => 'DATE', 'null' => TRUE),
            'nights_count'        => array('type' => 'INT', 'constraint' => 4, 'default' => 1),
            'rooms_count'         => array('type' => 'INT', 'constraint' => 4, 'default' => 1),
            'adults_count'        => array('type' => 'INT', 'constraint' => 4, 'default' => 2),
            'children_count'      => array('type' => 'INT', 'constraint' => 4, 'default' => 0),
            'guests_count'        => array('type' => 'INT', 'constraint' => 4, 'default' => 2),
            'lead_guest_title'    => array('type' => 'VARCHAR', 'constraint' => 10, 'default' => 'Mr'),
            'lead_guest_name'     => array('type' => 'VARCHAR', 'constraint' => 150, 'null' => TRUE),
            'primary_guest_name'  => array('type' => 'VARCHAR', 'constraint' => 150, 'null' => TRUE),
            'lead_guest_email'    => array('type' => 'VARCHAR', 'constraint' => 150, 'null' => TRUE),
            'guest_email'         => array('type' => 'VARCHAR', 'constraint' => 150, 'null' => TRUE),
            'lead_guest_phone'    => array('type' => 'VARCHAR', 'constraint' => 30, 'null' => TRUE),
            'guest_phone'         => array('type' => 'VARCHAR', 'constraint' => 30, 'null' => TRUE),
            'guest_details_json'  => array('type' => 'LONGTEXT', 'null' => TRUE),
            'special_requests'    => array('type' => 'TEXT', 'null' => TRUE),
            'total_amount'        => array('type' => 'DECIMAL', 'constraint' => '10,2', 'default' => '0.00'),
            'tax_amount'          => array('type' => 'DECIMAL', 'constraint' => '10,2', 'default' => '0.00'),
            'currency'            => array('type' => 'VARCHAR', 'constraint' => 10, 'default' => 'INR'),
            'payment_id'          => array('type' => 'VARCHAR', 'constraint' => 100, 'null' => TRUE),
            'payment_status'      => array('type' => 'VARCHAR', 'constraint' => 50, 'default' => 'paid'),
            'booking_status'      => array('type' => 'VARCHAR', 'constraint' => 50, 'default' => 'confirmed'),
            'cancellation_policy' => array('type' => 'TEXT', 'null' => TRUE),
            'created_at'          => array('type' => 'DATETIME', 'null' => TRUE),
            'updated_at'          => array('type' => 'DATETIME', 'null' => TRUE)
        );

        if (!$this->db->table_exists('hotel_bookings')) {
            $this->dbforge->add_field($booking_fields);
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table('hotel_bookings', TRUE);
        } else {
            // Check for missing columns and add them automatically
            $existing_columns = $this->db->list_fields('hotel_bookings');
            foreach ($booking_fields as $col_name => $col_def) {
                if ($col_name === 'id') continue;
                if (!in_array($col_name, $existing_columns)) {
                    $this->dbforge->add_column('hotel_bookings', array($col_name => $col_def));
                }
            }
        }
    }

    /**
     * Get Hotel API Settings
     */
    public function get_hotel_api_settings() {
        $row = $this->db->get_where('hotel_api_settings', array('id' => 1))->row_array();
        if (!$row) {
            $this->ensure_tables_exist();
            $row = $this->db->get_where('hotel_api_settings', array('id' => 1))->row_array();
        }
        return $row;
    }

    /**
     * Save Hotel API Settings
     */
    public function save_hotel_api_settings($data) {
        $data['id'] = 1;
        $data['updated_at'] = date('Y-m-d H:i:s');
        $existing = $this->db->get_where('hotel_api_settings', array('id' => 1))->num_rows();
        if ($existing > 0) {
            $this->db->where('id', 1);
            return $this->db->update('hotel_api_settings', $data);
        } else {
            return $this->db->insert('hotel_api_settings', $data);
        }
    }

    /**
     * Get All Hotel Bookings
     */
    public function get_all_hotel_bookings($limit = 50, $offset = 0, $status = null) {
        if (!empty($status)) {
            $this->db->where('booking_status', $status);
        }
        $this->db->order_by('id', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get('hotel_bookings')->result_array();
    }

    /**
     * Get Hotel Booking By Reference
     */
    public function get_hotel_booking_by_ref($ref) {
        $this->ensure_tables_exist();
        $row = $this->db->group_start()
                        ->where('booking_reference', $ref)
                        ->or_where('booking_ref', $ref)
                        ->group_end()
                        ->get('hotel_bookings')
                        ->row_array();
        if ($row) {
            if (empty($row['booking_reference'])) $row['booking_reference'] = $row['booking_ref'] ?? $ref;
            if (empty($row['lead_guest_name'])) $row['lead_guest_name'] = $row['primary_guest_name'] ?? 'Guest';
            if (empty($row['lead_guest_email'])) $row['lead_guest_email'] = $row['guest_email'] ?? '';
            if (empty($row['lead_guest_phone'])) $row['lead_guest_phone'] = $row['guest_phone'] ?? '';
        }
        return $row;
    }

    /**
     * Save New Hotel Booking
     */
    public function save_hotel_booking($data) {
        $this->ensure_tables_exist();
        $data['created_at'] = date('Y-m-d H:i:s');

        // Populate alias fields for backwards and forward schema compatibility
        if (isset($data['booking_reference']) && !isset($data['booking_ref'])) {
            $data['booking_ref'] = $data['booking_reference'];
        } elseif (isset($data['booking_ref']) && !isset($data['booking_reference'])) {
            $data['booking_reference'] = $data['booking_ref'];
        }

        if (isset($data['lead_guest_name']) && !isset($data['primary_guest_name'])) {
            $data['primary_guest_name'] = $data['lead_guest_name'];
        }
        if (isset($data['lead_guest_email']) && !isset($data['guest_email'])) {
            $data['guest_email'] = $data['lead_guest_email'];
        }
        if (isset($data['lead_guest_phone']) && !isset($data['guest_phone'])) {
            $data['guest_phone'] = $data['lead_guest_phone'];
        }
        if (!isset($data['guests_count'])) {
            $data['guests_count'] = ($data['adults_count'] ?? 1) + ($data['children_count'] ?? 0);
        }

        // Only insert columns that actually exist in the table to prevent MySQL 1054 error
        $existingFields = $this->db->list_fields('hotel_bookings');
        $filteredData = array_intersect_key($data, array_flip($existingFields));

        $this->db->insert('hotel_bookings', $filteredData);
        return $this->db->insert_id();
    }

    /**
     * Update Hotel Booking Status
     */
    public function update_hotel_booking_status($id, $status, $supp_ref = null) {
        $data = array('booking_status' => $status, 'updated_at' => date('Y-m-d H:i:s'));
        if ($supp_ref) {
            $data['supplier_reference'] = $supp_ref;
        }
        $this->db->where('id', $id);
        return $this->db->update('hotel_bookings', $data);
    }

    /**
     * Hotel Dashboard Statistics
     */
    public function get_hotel_stats() {
        $total = $this->db->count_all('hotel_bookings');
        $confirmed = $this->db->where('booking_status', 'confirmed')->count_all_results('hotel_bookings');
        $cancelled = $this->db->where('booking_status', 'cancelled')->count_all_results('hotel_bookings');
        
        $this->db->select_sum('total_amount');
        $this->db->where('booking_status', 'confirmed');
        $revenue = $this->db->get('hotel_bookings')->row()->total_amount ?? 0;

        return array(
            'total'     => $total,
            'confirmed' => $confirmed,
            'cancelled' => $cancelled,
            'revenue'   => (float)$revenue
        );
    }
}
