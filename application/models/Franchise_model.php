<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Franchise_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // =========================================================================
    // 1. FRANCHISE ADMIN AUTHENTICATION
    // =========================================================================
    public function admin_login($username, $password) {
        $this->db->where('username', trim($username));
        $this->db->where('status', 'active');
        $admin = $this->db->get('franchise_admins')->row_array();

        if ($admin && password_verify($password, $admin['password'])) {
            return $admin;
        }
        return false;
    }

    public function get_admin_by_id($id) {
        return $this->db->get_where('franchise_admins', array('id' => (int)$id))->row_array();
    }

    // =========================================================================
    // 2. FRANCHISE STORE AUTHENTICATION & MANAGEMENT
    // =========================================================================
    public function store_login($username, $password) {
        $clean_user = trim($username);
        $this->db->group_start();
        $this->db->where('username', $clean_user);
        $this->db->or_where('agent_code', $clean_user);
        $this->db->group_end();
        $store = $this->db->get('franchise_stores')->row_array();

        if (!$store) {
            return array('status' => false, 'error' => 'Invalid username or password.');
        }

        if ($store['status'] !== 'active') {
            return array('status' => false, 'error' => 'Your store account has been deactivated by Franchise Admin. Please contact support.');
        }

        if (password_verify($password, $store['password'])) {
            return array('status' => true, 'store' => $store);
        }

        return array('status' => false, 'error' => 'Invalid username or password.');
    }

    public function get_store_by_id($id) {
        return $this->db->get_where('franchise_stores', array('id' => (int)$id))->row_array();
    }

    public function get_all_stores($search = null) {
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('store_name', $search);
            $this->db->or_like('agent_code', $search);
            $this->db->or_like('username', $search);
            $this->db->or_like('phone', $search);
            $this->db->or_like('email', $search);
            $this->db->group_end();
        }
        $this->db->order_by('id', 'DESC');
        return $this->db->get('franchise_stores')->result_array();
    }

    public function create_store($data) {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->insert('franchise_stores', $data);
        return $this->db->insert_id();
    }

    public function update_store($id, $data) {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->where('id', (int)$id);
        return $this->db->update('franchise_stores', $data);
    }

    public function toggle_store_status($id) {
        $store = $this->get_store_by_id($id);
        if ($store) {
            $new_status = ($store['status'] === 'active') ? 'inactive' : 'active';
            $this->db->where('id', (int)$id);
            $this->db->update('franchise_stores', array(
                'status'     => $new_status,
                'updated_at' => date('Y-m-d H:i:s')
            ));
            return $new_status;
        }
        return false;
    }

    public function check_agent_code_exists($agent_code, $exclude_id = null) {
        $this->db->where('agent_code', trim($agent_code));
        if ($exclude_id) {
            $this->db->where('id !=', (int)$exclude_id);
        }
        return $this->db->count_all_results('franchise_stores') > 0;
    }

    public function check_username_exists($username, $exclude_id = null) {
        $this->db->where('username', trim($username));
        if ($exclude_id) {
            $this->db->where('id !=', (int)$exclude_id);
        }
        return $this->db->count_all_results('franchise_stores') > 0;
    }

    // =========================================================================
    // 3. ATOMIC WALLET MANAGEMENT & TRANSACTIONS
    // =========================================================================
    public function update_wallet_balance($store_id, $type, $amount, $ref_type, $ref_id = null, $remarks = null, $created_by = 'system') {
        $store_id = (int)$store_id;
        $amount   = (float)$amount;

        if ($amount <= 0) {
            return array('status' => false, 'error' => 'Amount must be greater than zero.');
        }

        $this->db->trans_start();

        // Lock row for update
        $query = $this->db->query("SELECT wallet_balance, status, store_name FROM franchise_stores WHERE id = ? FOR UPDATE", array($store_id));
        $store = $query->row_array();

        if (!$store) {
            $this->db->trans_rollback();
            return array('status' => false, 'error' => 'Store not found.');
        }

        if ($store['status'] !== 'active') {
            $this->db->trans_rollback();
            return array('status' => false, 'error' => 'Store account is currently inactive.');
        }

        $prev_balance = (float)$store['wallet_balance'];

        if ($type === 'debit') {
            if ($prev_balance < $amount) {
                $this->db->trans_rollback();
                return array(
                    'status'        => false,
                    'error'         => 'Insufficient wallet balance. Available: ₹ ' . number_format($prev_balance, 2) . ' | Required: ₹ ' . number_format($amount, 2),
                    'available_bal' => $prev_balance
                );
            }
            $new_balance = round($prev_balance - $amount, 2);
        } elseif ($type === 'credit') {
            $new_balance = round($prev_balance + $amount, 2);
        } else {
            $this->db->trans_rollback();
            return array('status' => false, 'error' => 'Invalid transaction type.');
        }

        // Update balance
        $this->db->where('id', $store_id);
        $this->db->update('franchise_stores', array(
            'wallet_balance' => $new_balance,
            'updated_at'     => date('Y-m-d H:i:s')
        ));

        // Insert audit log
        $tx_data = array(
            'store_id'         => $store_id,
            'transaction_type' => $type,
            'amount'           => $amount,
            'previous_balance' => $prev_balance,
            'new_balance'      => $new_balance,
            'reference_type'   => $ref_type,
            'reference_id'     => $ref_id,
            'remarks'          => $remarks,
            'created_by'       => $created_by,
            'created_at'       => date('Y-m-d H:i:s')
        );
        $this->db->insert('franchise_wallet_transactions', $tx_data);
        $tx_id = $this->db->insert_id();

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return array('status' => false, 'error' => 'Database transaction failed.');
        }

        return array(
            'status'         => true,
            'transaction_id' => $tx_id,
            'previous_balance' => $prev_balance,
            'new_balance'    => $new_balance
        );
    }

    public function get_wallet_ledger($store_id = null, $limit = 100) {
        $this->db->select('t.*, s.store_name, s.agent_code');
        $this->db->from('franchise_wallet_transactions t');
        $this->db->join('franchise_stores s', 's.id = t.store_id', 'left');
        if ($store_id) {
            $this->db->where('t.store_id', (int)$store_id);
        }
        $this->db->order_by('t.id', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result_array();
    }

    // =========================================================================
    // 4. BOOKINGS MANAGEMENT (FLIGHTS & HOTELS)
    // =========================================================================
    public function record_flight_booking($data) {
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert('franchise_flight_bookings', $data);
        return $this->db->insert_id();
    }

    public function record_hotel_booking($data) {
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert('franchise_hotel_bookings', $data);
        return $this->db->insert_id();
    }

    public function get_flight_booking_by_ref($ref, $store_id = null) {
        $this->db->where('booking_ref', $ref);
        if ($store_id) {
            $this->db->where('store_id', (int)$store_id);
        }
        return $this->db->get('franchise_flight_bookings')->row_array();
    }

    public function get_hotel_booking_by_ref($ref, $store_id = null) {
        $this->db->where('booking_ref', $ref);
        if ($store_id) {
            $this->db->where('store_id', (int)$store_id);
        }
        return $this->db->get('franchise_hotel_bookings')->row_array();
    }

    public function get_store_flight_bookings($store_id, $limit = 50) {
        $this->db->where('store_id', (int)$store_id);
        $this->db->order_by('id', 'DESC');
        $this->db->limit($limit);
        return $this->db->get('franchise_flight_bookings')->result_array();
    }

    public function get_store_hotel_bookings($store_id, $limit = 50) {
        $this->db->where('store_id', (int)$store_id);
        $this->db->order_by('id', 'DESC');
        $this->db->limit($limit);
        return $this->db->get('franchise_hotel_bookings')->result_array();
    }

    public function get_all_flight_bookings($limit = 100) {
        $this->db->select('b.*, s.store_name, s.agent_code');
        $this->db->from('franchise_flight_bookings b');
        $this->db->join('franchise_stores s', 's.id = b.store_id', 'left');
        $this->db->order_by('b.id', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result_array();
    }

    public function get_all_hotel_bookings($limit = 100) {
        $this->db->select('b.*, s.store_name, s.agent_code');
        $this->db->from('franchise_hotel_bookings b');
        $this->db->join('franchise_stores s', 's.id = b.store_id', 'left');
        $this->db->order_by('b.id', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result_array();
    }

    // =========================================================================
    // 5. DASHBOARD STATS
    // =========================================================================
    public function get_admin_dashboard_stats() {
        $total_stores = $this->db->count_all('franchise_stores');
        $active_stores = $this->db->where('status', 'active')->count_all_results('franchise_stores');
        
        $wallet_sum = $this->db->select_sum('wallet_balance')->get('franchise_stores')->row_array();
        $total_wallet_float = (float)($wallet_sum['wallet_balance'] ?? 0);

        $total_flight_bookings = $this->db->count_all('franchise_flight_bookings');
        $total_hotel_bookings  = $this->db->count_all('franchise_hotel_bookings');

        $today = date('Y-m-d');
        $today_flights = $this->db->like('created_at', $today, 'after')->count_all_results('franchise_flight_bookings');
        $today_hotels  = $this->db->like('created_at', $today, 'after')->count_all_results('franchise_hotel_bookings');

        return array(
            'total_stores'          => $total_stores,
            'active_stores'         => $active_stores,
            'total_wallet_float'    => $total_wallet_float,
            'total_flight_bookings' => $total_flight_bookings,
            'total_hotel_bookings'  => $total_hotel_bookings,
            'today_bookings'        => $today_flights + $today_hotels
        );
    }
}
