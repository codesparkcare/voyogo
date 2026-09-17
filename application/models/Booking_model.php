<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Booking_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // --- FLIGHT BOOKINGS ---

    public function insert_flight_booking($data) {
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
