<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Booking_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // --- FLIGHT BOOKINGS ---

    public function insert_flight_booking($data) {
        if ($this->db->table_exists('flight_bookings')) {
            $existing_fields = $this->db->list_fields('flight_bookings');
            // Dynamically self-heal order_id column if present in payload
            if (isset($data['order_id']) && !in_array('order_id', $existing_fields)) {
                $this->load->dbforge();
                @$this->dbforge->add_column('flight_bookings', array(
                    'order_id' => array('type' => 'VARCHAR', 'constraint' => 100, 'null' => TRUE, 'after' => 'payment_id')
                ));
                $existing_fields = $this->db->list_fields('flight_bookings');
            }
            // Only insert columns that actually exist in the table to prevent MySQL 1054 error
            $data = array_intersect_key($data, array_flip($existing_fields));
        }
        $this->db->insert('flight_bookings', $data);
        return $this->db->insert_id();
    }

    public function get_flight_booking_by_ref($ref) {
        return $this->db->get_where('flight_bookings', array('booking_ref' => $ref))->row_array();
    }

    public function get_all_flight_bookings($limit = 50, $offset = 0) {
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('flight_bookings', $limit, $offset)->result_array();
    }

    public function update_flight_booking_status($id, $status, $payment_status = null) {
        $data = array('booking_status' => $status);
        if ($payment_status !== null) {
            $data['payment_status'] = $payment_status;
        }
        $this->db->where('id', $id);
        return $this->db->update('flight_bookings', $data);
    }

    // --- HOTEL BOOKINGS ---

    public function insert_hotel_booking($data) {
        if ($this->db->table_exists('hotel_bookings')) {
            $existing_fields = $this->db->list_fields('hotel_bookings');
            // Dynamically self-heal order_id column if present in payload
            if (isset($data['order_id']) && !in_array('order_id', $existing_fields)) {
                $this->load->dbforge();
                @$this->dbforge->add_column('hotel_bookings', array(
                    'order_id' => array('type' => 'VARCHAR', 'constraint' => 100, 'null' => TRUE, 'after' => 'payment_id')
                ));
                $existing_fields = $this->db->list_fields('hotel_bookings');
            }
            // Only insert columns that actually exist in the table to prevent MySQL 1054 error
            $data = array_intersect_key($data, array_flip($existing_fields));
        }
        $this->db->insert('hotel_bookings', $data);
        return $this->db->insert_id();
    }

    public function get_hotel_booking_by_ref($ref) {
        return $this->db->get_where('hotel_bookings', array('booking_ref' => $ref))->row_array();
    }

    public function get_all_hotel_bookings($limit = 50, $offset = 0) {
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('hotel_bookings', $limit, $offset)->result_array();
    }

    public function update_hotel_booking_status($id, $status, $payment_status = null) {
        $data = array('booking_status' => $status);
        if ($payment_status !== null) {
            $data['payment_status'] = $payment_status;
        }
        $this->db->where('id', $id);
        return $this->db->update('hotel_bookings', $data);
    }

    // --- ENQUIRIES ---

    public function get_all_enquiries($limit = 50) {
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('enquiries', $limit)->result_array();
    }

    public function delete_enquiry($id) {
        $this->db->where('id', (int)$id);
        return $this->db->delete('enquiries');
    }

    public function clear_empty_enquiries() {
        // Delete records where phone and email are both empty
        $this->db->group_start();
        $this->db->where('phone IS NULL', null, false);
        $this->db->or_where('phone', '');
        $this->db->group_end();
        $this->db->group_start();
        $this->db->where('email IS NULL', null, false);
        $this->db->or_where('email', '');
        $this->db->group_end();
        return $this->db->delete('enquiries');
    }
}
