<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Get user by ID
     */
    public function get_by_id($id)
    {
        return $this->db->get_where('users', ['id' => $id])->row_array();
    }

    /**
     * Get user by Phone
     */
    public function get_by_phone($phone)
    {
        // Normalize phone to search
        $cleanPhone = preg_replace('/[^0-9+]/', '', $phone);
        $this->db->where('phone', $cleanPhone);
        return $this->db->get('users')->row_array();
    }

    /**
     * Create or Login user by verified phone number
     */
    public function create_or_login_by_phone($phone, $firebase_uid = null)
    {
        $cleanPhone = preg_replace('/[^0-9+]/', '', $phone);
        $user = $this->get_by_phone($cleanPhone);

        if ($user) {
            // Update firebase_uid if available
            if ($firebase_uid && empty($user['firebase_uid'])) {
                $this->db->where('id', $user['id']);
                $this->db->update('users', ['firebase_uid' => $firebase_uid]);
            }
            return $user;
        }

        // Insert new user
        $data = [
            'phone'        => $cleanPhone,
            'firebase_uid' => $firebase_uid,
            'status'       => 'active',
            'created_at'   => date('Y-m-d H:i:s'),
            'updated_at'   => date('Y-m-d H:i:s')
        ];
        $this->db->insert('users', $data);
        $insert_id = $this->db->insert_id();

        return $this->get_by_id($insert_id);
    }

    /**
     * Update User Profile (Phone number is strictly protected and cannot be modified)
     */
    public function update_profile($id, $data)
    {
        // Only allow first_name, last_name, email
        $allowed = [
            'first_name' => isset($data['first_name']) ? trim($data['first_name']) : null,
            'last_name'  => isset($data['last_name']) ? trim($data['last_name']) : null,
            'email'      => isset($data['email']) ? trim($data['email']) : null,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $this->db->where('id', $id);
        return $this->db->update('users', $allowed);
    }
}
