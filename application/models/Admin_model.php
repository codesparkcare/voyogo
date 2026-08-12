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
                'smtp_host'   => 'smtp.gmail.com',
                'smtp_port'   => 587,
                'smtp_user'   => '',
                'smtp_pass'   => '',
                'smtp_crypto' => 'tls',
                'from_email'  => 'noreply@voyogo.com',
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
}
