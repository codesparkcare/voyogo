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

    // =========================================================================
    // 6. GROUP TOUR FOLLOW UP MANAGEMENT
    // =========================================================================
    public function ensure_group_tours_table() {
        $sql = "CREATE TABLE IF NOT EXISTS `franchise_group_tours` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `s_no` varchar(50) DEFAULT NULL,
            `tour_date` varchar(100) DEFAULT NULL,
            `tour_code` varchar(100) DEFAULT NULL,
            `country_name` varchar(255) DEFAULT NULL,
            `tour_amount` varchar(100) DEFAULT NULL,
            `confirmed` varchar(100) DEFAULT NULL,
            `grp_tkn` varchar(50) DEFAULT NULL,
            `need` varchar(50) DEFAULT NULL,
            `reminder_45` varchar(100) DEFAULT NULL,
            `reminder_30` varchar(100) DEFAULT NULL,
            `ticket_status` varchar(100) DEFAULT NULL,
            `visa_status` varchar(255) DEFAULT NULL,
            `handling_person` varchar(150) DEFAULT NULL,
            `travel_bag` varchar(150) DEFAULT NULL,
            `tour_manager` varchar(150) DEFAULT NULL,
            `month_group` varchar(100) DEFAULT NULL,
            `notes` text DEFAULT NULL,
            `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
            `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `tour_code_idx` (`tour_code`),
            KEY `month_group_idx` (`month_group`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $this->db->query($sql);
    }

    public function get_group_tours($month_group = null, $search = null) {
        $this->ensure_group_tours_table();
        $this->db->from('franchise_group_tours');

        if (!empty($month_group) && $month_group !== 'all') {
            $this->db->where('month_group', trim($month_group));
        }

        if (!empty($search)) {
            $term = trim($search);
            $this->db->group_start();
            $this->db->like('tour_code', $term);
            $this->db->or_like('country_name', $term);
            $this->db->or_like('tour_date', $term);
            $this->db->or_like('handling_person', $term);
            $this->db->or_like('tour_manager', $term);
            $this->db->or_like('visa_status', $term);
            $this->db->or_like('ticket_status', $term);
            $this->db->group_end();
        }

        $this->db->order_by('id', 'ASC');
        return $this->db->get()->result_array();
    }

    public function get_group_tour_months() {
        $this->ensure_group_tours_table();
        $this->db->select('month_group, COUNT(*) as tour_count');
        $this->db->where("month_group IS NOT NULL AND month_group != ''", NULL, FALSE);
        $this->db->group_by('month_group');
        $this->db->order_by('MIN(id)', 'ASC');
        return $this->db->get('franchise_group_tours')->result_array();
    }

    public function get_group_tours_stats() {
        $this->ensure_group_tours_table();
        $tours = $this->db->get('franchise_group_tours')->result_array();

        $total_tours = count($tours);
        $total_needed = 0;
        $total_grp_tkn = 0;
        $visa_pending_count = 0;
        $ticket_pending_count = 0;

        foreach ($tours as $t) {
            // Count needed
            $need_val = (int)preg_replace('/[^0-9]/', '', $t['need'] ?? '');
            $total_needed += $need_val;

            $grp_val = (int)preg_replace('/[^0-9]/', '', $t['grp_tkn'] ?? '');
            $total_grp_tkn += $grp_val;

            if (empty($t['visa_status']) || stripos($t['visa_status'], 'WE APPLY') !== false) {
                $visa_pending_count++;
            }
            if (empty($t['ticket_status']) || stripos($t['ticket_status'], 'TAKEN') === false) {
                $ticket_pending_count++;
            }
        }

        return array(
            'total_tours'          => $total_tours,
            'total_grp_tkn'        => $total_grp_tkn,
            'total_needed'         => $total_needed,
            'visa_pending_count'   => $visa_pending_count,
            'ticket_pending_count' => $ticket_pending_count
        );
    }

    public function resequence_s_no() {
        $rows = $this->db->order_by('id', 'ASC')->get('franchise_group_tours')->result_array();
        if (!empty($rows)) {
            $num = 1;
            foreach ($rows as $r) {
                $this->db->where('id', $r['id'])->update('franchise_group_tours', array('s_no' => (string)$num));
                $num++;
            }
        }
    }

    public function insert_group_tours_batch($rows, $replace = false) {
        $this->ensure_group_tours_table();
        if ($replace) {
            $this->db->truncate('franchise_group_tours');
        }

        if (empty($rows)) {
            return 0;
        }

        $inserted = 0;
        $batch = array();
        $s_no_counter = 1;
        if (!$replace) {
            $s_no_counter = ($this->db->count_all('franchise_group_tours')) + 1;
        }

        foreach ($rows as $r) {
            $batch[] = array(
                's_no'            => (string)($s_no_counter++),
                'tour_date'       => isset($r['tour_date']) ? trim($r['tour_date']) : null,
                'tour_code'       => isset($r['tour_code']) ? trim($r['tour_code']) : null,
                'country_name'    => isset($r['country_name']) ? trim($r['country_name']) : null,
                'tour_amount'     => isset($r['tour_amount']) ? trim($r['tour_amount']) : null,
                'confirmed'       => isset($r['confirmed']) ? trim($r['confirmed']) : null,
                'grp_tkn'         => isset($r['grp_tkn']) ? trim($r['grp_tkn']) : null,
                'need'            => isset($r['need']) ? trim($r['need']) : null,
                'reminder_45'     => isset($r['reminder_45']) ? trim($r['reminder_45']) : null,
                'reminder_30'     => isset($r['reminder_30']) ? trim($r['reminder_30']) : null,
                'ticket_status'   => isset($r['ticket_status']) ? trim($r['ticket_status']) : null,
                'visa_status'     => isset($r['visa_status']) ? trim($r['visa_status']) : null,
                'handling_person' => isset($r['handling_person']) ? trim($r['handling_person']) : null,
                'travel_bag'      => isset($r['travel_bag']) ? trim($r['travel_bag']) : null,
                'tour_manager'    => isset($r['tour_manager']) ? trim($r['tour_manager']) : null,
                'month_group'     => isset($r['month_group']) ? trim($r['month_group']) : null,
                'notes'           => isset($r['notes']) ? trim($r['notes']) : null
            );
            $inserted++;

            if (count($batch) >= 100) {
                $this->db->insert_batch('franchise_group_tours', $batch);
                $batch = array();
            }
        }

        if (!empty($batch)) {
            $this->db->insert_batch('franchise_group_tours', $batch);
        }

        $this->resequence_s_no();
        return $inserted;
    }

    public function save_group_tour($data, $id = null) {
        $this->ensure_group_tours_table();
        $record = array(
            's_no'            => $data['s_no'] ?? null,
            'tour_date'       => $data['tour_date'] ?? null,
            'tour_code'       => $data['tour_code'] ?? null,
            'country_name'    => $data['country_name'] ?? null,
            'tour_amount'     => $data['tour_amount'] ?? null,
            'confirmed'       => $data['confirmed'] ?? null,
            'grp_tkn'         => $data['grp_tkn'] ?? null,
            'need'            => $data['need'] ?? null,
            'reminder_45'     => $data['reminder_45'] ?? null,
            'reminder_30'     => $data['reminder_30'] ?? null,
            'ticket_status'   => $data['ticket_status'] ?? null,
            'visa_status'     => $data['visa_status'] ?? null,
            'handling_person' => $data['handling_person'] ?? null,
            'travel_bag'      => $data['travel_bag'] ?? null,
            'tour_manager'    => $data['tour_manager'] ?? null,
            'month_group'     => $data['month_group'] ?? null,
            'notes'           => $data['notes'] ?? null
        );

        if (!empty($id)) {
            $this->db->where('id', (int)$id)->update('franchise_group_tours', $record);
            return $id;
        } else {
            $this->db->insert('franchise_group_tours', $record);
            $new_id = $this->db->insert_id();
            $this->resequence_s_no();
            return $new_id;
        }
    }

    public function delete_group_tour($id) {
        $this->ensure_group_tours_table();
        $deleted = $this->db->where('id', (int)$id)->delete('franchise_group_tours');
        $this->resequence_s_no();
        return $deleted;
    }

    public function clear_all_group_tours() {
        $this->ensure_group_tours_table();
        return $this->db->truncate('franchise_group_tours');
    }

    public function seed_default_group_tours() {
        $this->ensure_group_tours_table();
        $this->db->truncate('franchise_group_tours');
        $samples = array(
            // SEPTEMBER:2026
            array('s_no'=>'1', 'tour_date'=>'16.09.2026', 'tour_code'=>'NBO160926', 'country_name'=>'KENYA (5N/6D)', 'tour_amount'=>'259,000', 'confirmed'=>'21+1+1+2+2', 'grp_tkn'=>'22', 'need'=>'-', 'reminder_45'=>'02.08.2026', 'reminder_30'=>'17.08.2026', 'ticket_status'=>'GROUP TAKEN', 'visa_status'=>'WE APPLY (INCLUDE)', 'handling_person'=>'KEERTHANA DEVI', 'travel_bag'=>'-', 'tour_manager'=>'YOGESH', 'month_group'=>'SEPTEMBER:2026'),
            array('s_no'=>'2', 'tour_date'=>'17.09.2026', 'tour_code'=>'NRT170926', 'country_name'=>'JAPAN (7N/8D)', 'tour_amount'=>'259,000', 'confirmed'=>'35+1', 'grp_tkn'=>'36', 'need'=>'-', 'reminder_45'=>'03.08.2026', 'reminder_30'=>'18.08.2026', 'ticket_status'=>'GROUP TAKEN', 'visa_status'=>'WE APPLY (INCLUDE)', 'handling_person'=>'NAVEENA', 'travel_bag'=>'-', 'tour_manager'=>'SARATHKUMAR', 'month_group'=>'SEPTEMBER:2026'),
            array('s_no'=>'3', 'tour_date'=>'20.09.2025', 'tour_code'=>'CDG200926', 'country_name'=>'AMAZING EUROPE (9N/10)', 'tour_amount'=>'274,000', 'confirmed'=>'31+1+1+2', 'grp_tkn'=>'32', 'need'=>'-', 'reminder_45'=>'06.08.2026', 'reminder_30'=>'21.08.2026', 'ticket_status'=>'GROUP TAKEN', 'visa_status'=>'WE APPLY (INCLUDE)', 'handling_person'=>'MUKESH', 'travel_bag'=>'-', 'tour_manager'=>'RAJA MITHUN', 'month_group'=>'SEPTEMBER:2026'),
            array('s_no'=>'4', 'tour_date'=>'21.09.2026', 'tour_code'=>'CPH210926', 'country_name'=>'SCANDINAVIA (9N/10D)', 'tour_amount'=>'345,000', 'confirmed'=>'31+1+1+2', 'grp_tkn'=>'32', 'need'=>'-', 'reminder_45'=>'07.08.2026', 'reminder_30'=>'22.08.2026', 'ticket_status'=>'GROUP TAKEN', 'visa_status'=>'WE APPLY (INCLUDE)', 'handling_person'=>'MUKESH', 'travel_bag'=>'-', 'tour_manager'=>'BALA MURUGAN', 'month_group'=>'SEPTEMBER:2026'),
            array('s_no'=>'5', 'tour_date'=>'23.09.2026', 'tour_code'=>'DAD230926', 'country_name'=>'ONLY VIETNAM', 'tour_amount'=>'105,000', 'confirmed'=>'21+1', 'grp_tkn'=>'22', 'need'=>'-', 'reminder_45'=>'09.08.2026', 'reminder_30'=>'24.08.2026', 'ticket_status'=>'GROUP TAKEN', 'visa_status'=>'WE APPLY (INCLUDE)', 'handling_person'=>'NAVEENA', 'travel_bag'=>'-', 'tour_manager'=>'PANDISELVAM', 'month_group'=>'SEPTEMBER:2026'),
            array('s_no'=>'6', 'tour_date'=>'24.09.2026', 'tour_code'=>'SINKUL240926', 'country_name'=>'SINGAPORE & MALAYSIA (5N/6D)', 'tour_amount'=>'99,000', 'confirmed'=>'20+1', 'grp_tkn'=>'21', 'need'=>'-', 'reminder_45'=>'10.08.2026', 'reminder_30'=>'25.08.2026', 'ticket_status'=>'GROUP TAKEN', 'visa_status'=>'SIN-WE APPLY (INCLUDE) / MH-FREE', 'handling_person'=>'KEERTHANA DEVI', 'travel_bag'=>'-', 'tour_manager'=>'YASIN', 'month_group'=>'SEPTEMBER:2026'),
            
            // OCTOBER:2026
            array('s_no'=>'7', 'tour_date'=>'10.10.2026', 'tour_code'=>'SGN101026', 'country_name'=>'VIETNAM CAMBODIA ( 7N/8D)', 'tour_amount'=>'142,000', 'confirmed'=>'21+1', 'grp_tkn'=>'22', 'need'=>'-', 'reminder_45'=>'20.09.2026', 'reminder_30'=>'01.10.2026', 'ticket_status'=>'GROUP TAKEN', 'visa_status'=>'-', 'handling_person'=>'NAVEENA', 'travel_bag'=>'-', 'tour_manager'=>'CHANDRASEKAR', 'month_group'=>'OCTOBER:2026'),
            array('s_no'=>'8', 'tour_date'=>'18.10.2026', 'tour_code'=>'DPS181026', 'country_name'=>'BALI (4N/5D)', 'tour_amount'=>'74,000', 'confirmed'=>'21+1+1+2', 'grp_tkn'=>'22', 'need'=>'-', 'reminder_45'=>'16.10.2026', 'reminder_30'=>'01.12.2026', 'ticket_status'=>'GROUP TAKEN', 'visa_status'=>'-', 'handling_person'=>'YOGESH', 'travel_bag'=>'-', 'tour_manager'=>'HARIHARAN', 'month_group'=>'OCTOBER:2026'),
            array('s_no'=>'9', 'tour_date'=>'20.10.2026', 'tour_code'=>'CDG181026', 'country_name'=>'AMAZING EUROPE (9N/10)', 'tour_amount'=>'274,000', 'confirmed'=>'24+1', 'grp_tkn'=>'32', 'need'=>'7', 'reminder_45'=>'-', 'reminder_30'=>'-', 'ticket_status'=>'GROUP TAKEN', 'visa_status'=>'-', 'handling_person'=>'MUKESH', 'travel_bag'=>'-', 'tour_manager'=>'BALA MURUGAN', 'month_group'=>'OCTOBER:2026'),
            array('s_no'=>'10', 'tour_date'=>'22.10.2026', 'tour_code'=>'KIX221026', 'country_name'=>'JAPAN (7N/8D)', 'tour_amount'=>'259,000', 'confirmed'=>'28+1+4', 'grp_tkn'=>'36', 'need'=>'7', 'reminder_45'=>'20.10.2026', 'reminder_30'=>'28.11.2026', 'ticket_status'=>'GROUP TAKEN', 'visa_status'=>'WE APPLY (INCLUDE)', 'handling_person'=>'NAVEENA', 'travel_bag'=>'-', 'tour_manager'=>'RAJA MITHUN', 'month_group'=>'OCTOBER:2026'),
            array('s_no'=>'11', 'tour_date'=>'23.10.2026', 'tour_code'=>'SINKUL231026', 'country_name'=>'SINGAPORE & MALAYSIA (5N/6D)', 'tour_amount'=>'103,000', 'confirmed'=>'18+1', 'grp_tkn'=>'22', 'need'=>'3', 'reminder_45'=>'21.10.2026', 'reminder_30'=>'29.11.2026', 'ticket_status'=>'GROUP TAKEN', 'visa_status'=>'SIN-WE APPLY (INCLUDE) / MH-FREE', 'handling_person'=>'KEERTHANA DEVI', 'travel_bag'=>'-', 'tour_manager'=>'PANDISELVAM', 'month_group'=>'OCTOBER:2026'),
            array('s_no'=>'12', 'tour_date'=>'24.10.2026', 'tour_code'=>'CAI241026', 'country_name'=>'EGYPT (7N/8D)', 'tour_amount'=>'189,000', 'confirmed'=>'10+1', 'grp_tkn'=>'22', 'need'=>'11', 'reminder_45'=>'22.10.2026', 'reminder_30'=>'30.11.2026', 'ticket_status'=>'GROUP TAKEN', 'visa_status'=>'WE APPLY (INCLUDE)', 'handling_person'=>'JANANI', 'travel_bag'=>'-', 'tour_manager'=>'SARATHKUMAR', 'month_group'=>'OCTOBER:2026'),

            // NOVEMBER:2026
            array('s_no'=>'13', 'tour_date'=>'11.11.2026', 'tour_code'=>'IXB111126', 'country_name'=>'BHUTAN (5N/6D)', 'tour_amount'=>'62,000', 'confirmed'=>'13+1', 'grp_tkn'=>'22', 'need'=>'8', 'reminder_45'=>'-', 'reminder_30'=>'-', 'ticket_status'=>'GROUP TAKEN', 'visa_status'=>'-', 'handling_person'=>'HARINI', 'travel_bag'=>'-', 'tour_manager'=>'BALA MURUGAN', 'month_group'=>'NOVEMBER:2026'),
            array('s_no'=>'14', 'tour_date'=>'16.11.2026', 'tour_code'=>'IXB161126', 'country_name'=>'BHUTAN (5N/6D)', 'tour_amount'=>'57,000', 'confirmed'=>'21+1', 'grp_tkn'=>'22', 'need'=>'-', 'reminder_45'=>'-', 'reminder_30'=>'-', 'ticket_status'=>'GROUP TAKEN', 'visa_status'=>'-', 'handling_person'=>'HARINI', 'travel_bag'=>'-', 'tour_manager'=>'PANDISELVAM', 'month_group'=>'NOVEMBER:2026'),
            array('s_no'=>'15', 'tour_date'=>'18.11.2026', 'tour_code'=>'DAD181126', 'country_name'=>'ONLY VIETNAM (6D/5N)', 'tour_amount'=>'105,000', 'confirmed'=>'7+1', 'grp_tkn'=>'22', 'need'=>'14', 'reminder_45'=>'08.10.2026', 'reminder_30'=>'21.10.2026', 'ticket_status'=>'GROUP TAKEN', 'visa_status'=>'WE APPLY (INCLUDE)', 'handling_person'=>'NAVEENA', 'travel_bag'=>'-', 'tour_manager'=>'RAJA MITHUN', 'month_group'=>'NOVEMBER:2026'),
            array('s_no'=>'16', 'tour_date'=>'18.11.2026', 'tour_code'=>'NRT181126', 'country_name'=>'JAPAN (7N/8D)', 'tour_amount'=>'259,000', 'confirmed'=>'2+1', 'grp_tkn'=>'36', 'need'=>'33', 'reminder_45'=>'03.08.2026', 'reminder_30'=>'18.08.2026', 'ticket_status'=>'GROUP TAKEN', 'visa_status'=>'WE APPLY (INCLUDE)', 'handling_person'=>'NAVEENA', 'travel_bag'=>'-', 'tour_manager'=>'BALA MURUGAN', 'month_group'=>'NOVEMBER:2026'),
            array('s_no'=>'17', 'tour_date'=>'20.11.2026', 'tour_code'=>'SINMH201126', 'country_name'=>'SINGAPORE & MALAYSIA (5N/6D)', 'tour_amount'=>'103,000', 'confirmed'=>'9+1', 'grp_tkn'=>'22', 'need'=>'12', 'reminder_45'=>'10.10.2026', 'reminder_30'=>'23.10.2026', 'ticket_status'=>'GROUP TAKEN', 'visa_status'=>'SIN-WE APPLY (INCLUDE) / MH-FREE', 'handling_person'=>'KEERTHANA DEVI', 'travel_bag'=>'-', 'tour_manager'=>'BALASUBRAMANIAN', 'month_group'=>'NOVEMBER:2026'),
            array('s_no'=>'18', 'tour_date'=>'23.11.2026', 'tour_code'=>'CAI231126', 'country_name'=>'EGYPT (7N/8D)', 'tour_amount'=>'189,000', 'confirmed'=>'1+1', 'grp_tkn'=>'22', 'need'=>'20', 'reminder_45'=>'13.10.2026', 'reminder_30'=>'26.10.2026', 'ticket_status'=>'GROUP TAKEN', 'visa_status'=>'WE APPLY (INCLUDE)', 'handling_person'=>'JANANI', 'travel_bag'=>'-', 'tour_manager'=>'HARIHARAN', 'month_group'=>'NOVEMBER:2026'),

            // DECEMBER:2026
            array('s_no'=>'19', 'tour_date'=>'10.12.2026', 'tour_code'=>'SINMH101226', 'country_name'=>'SINGAPORE & MALAYSIA (5N/6D)', 'tour_amount'=>'103,000', 'confirmed'=>'2+1', 'grp_tkn'=>'22', 'need'=>'19', 'reminder_45'=>'27.10.2026', 'reminder_30'=>'11.11.2026', 'ticket_status'=>'GROUP TAKEN', 'visa_status'=>'SIN-WE APPLY (INCLUDE) / MH-FREE', 'handling_person'=>'KEERTHANA DEVI', 'travel_bag'=>'-', 'tour_manager'=>'MUTHUPANDI', 'month_group'=>'DECEMBER:2026'),
            array('s_no'=>'20', 'tour_date'=>'10.12.2026', 'tour_code'=>'SXR101226', 'country_name'=>'KASHMIR (4N/5D)', 'tour_amount'=>'59,000', 'confirmed'=>'2+1', 'grp_tkn'=>'22', 'need'=>'19', 'reminder_45'=>'-', 'reminder_30'=>'-', 'ticket_status'=>'GROUP TAKEN', 'visa_status'=>'-', 'handling_person'=>'ASHOK KUMAR', 'travel_bag'=>'-', 'tour_manager'=>'PANDISELVAM', 'month_group'=>'DECEMBER:2026'),
            array('s_no'=>'21', 'tour_date'=>'16.12.2026', 'tour_code'=>'CAI161226', 'country_name'=>'EGYPT (6N/7D)', 'tour_amount'=>'189,000', 'confirmed'=>'-', 'grp_tkn'=>'22', 'need'=>'22', 'reminder_45'=>'-', 'reminder_30'=>'-', 'ticket_status'=>'-', 'visa_status'=>'-', 'handling_person'=>'JANANI', 'travel_bag'=>'-', 'tour_manager'=>'BALA MURUGAN', 'month_group'=>'DECEMBER:2026'),

            // JANUARY:2027
            array('s_no'=>'22', 'tour_date'=>'10.01.2027', 'tour_code'=>'JAI100127', 'country_name'=>'RAJASTHAN (5N/6D)', 'tour_amount'=>'58,500', 'confirmed'=>'4+1', 'grp_tkn'=>'22', 'need'=>'17', 'reminder_45'=>'-', 'reminder_30'=>'-', 'ticket_status'=>'GROUP TAKEN', 'visa_status'=>'-', 'handling_person'=>'ASHOK KUMAR', 'travel_bag'=>'-', 'tour_manager'=>'YASIN', 'month_group'=>'JANUARY:2027'),
            array('s_no'=>'23', 'tour_date'=>'11.01.2027', 'tour_code'=>'IXZ110127', 'country_name'=>'ANDAMAN (4N/5D)', 'tour_amount'=>'52,000', 'confirmed'=>'8+1', 'grp_tkn'=>'22', 'need'=>'13', 'reminder_45'=>'-', 'reminder_30'=>'-', 'ticket_status'=>'GROUP TAKEN', 'visa_status'=>'-', 'handling_person'=>'JEBA SURYA', 'travel_bag'=>'-', 'tour_manager'=>'MUTHUPANDI', 'month_group'=>'JANUARY:2027'),
            array('s_no'=>'24', 'tour_date'=>'11.01.2027', 'tour_code'=>'IXC110127', 'country_name'=>'SHIMLA KULLU MANALI (4N/5D)', 'tour_amount'=>'39,000', 'confirmed'=>'-', 'grp_tkn'=>'22', 'need'=>'22', 'reminder_45'=>'-', 'reminder_30'=>'-', 'ticket_status'=>'GROUP TAKEN', 'visa_status'=>'-', 'handling_person'=>'HARINI', 'travel_bag'=>'-', 'tour_manager'=>'-', 'month_group'=>'JANUARY:2027'),
            array('s_no'=>'25', 'tour_date'=>'12.01.2027', 'tour_code'=>'AUH120127', 'country_name'=>'DUBAI (4N/5D)', 'tour_amount'=>'99,000', 'confirmed'=>'7+1', 'grp_tkn'=>'32', 'need'=>'24', 'reminder_45'=>'-', 'reminder_30'=>'-', 'ticket_status'=>'GROUP TAKEN', 'visa_status'=>'-', 'handling_person'=>'KEERTHANA DEVI', 'travel_bag'=>'-', 'tour_manager'=>'HARIHARAN', 'month_group'=>'JANUARY:2027'),
            array('s_no'=>'26', 'tour_date'=>'13.01.2027', 'tour_code'=>'DAD130127', 'country_name'=>'ONLY VIETNAM (6D/5N)', 'tour_amount'=>'118,000', 'confirmed'=>'-', 'grp_tkn'=>'22', 'need'=>'22', 'reminder_45'=>'-', 'reminder_30'=>'-', 'ticket_status'=>'GROUP TAKEN', 'visa_status'=>'-', 'handling_person'=>'NAVEENA', 'travel_bag'=>'-', 'tour_manager'=>'PANDISELVAM', 'month_group'=>'JANUARY:2027'),
            array('s_no'=>'27', 'tour_date'=>'14.01.2027', 'tour_code'=>'SINKUL140127', 'country_name'=>'SINGAPORE & MALAYSIA (5N/6D)', 'tour_amount'=>'107,000', 'confirmed'=>'-', 'grp_tkn'=>'32', 'need'=>'32', 'reminder_45'=>'-', 'reminder_30'=>'-', 'ticket_status'=>'GROUP TAKEN', 'visa_status'=>'-', 'handling_person'=>'KEERTHANA DEVI', 'travel_bag'=>'-', 'tour_manager'=>'KARUKUVEL', 'month_group'=>'JANUARY:2027'),
            array('s_no'=>'28', 'tour_date'=>'17.01.2027', 'tour_code'=>'CAI170127', 'country_name'=>'EGYPT (6N/7D)', 'tour_amount'=>'189,000', 'confirmed'=>'-', 'grp_tkn'=>'22', 'need'=>'22', 'reminder_45'=>'-', 'reminder_30'=>'-', 'ticket_status'=>'GROUP TAKEN', 'visa_status'=>'-', 'handling_person'=>'JANANI', 'travel_bag'=>'-', 'tour_manager'=>'BALA MURUGAN', 'month_group'=>'JANUARY:2027'),
            array('s_no'=>'29', 'tour_date'=>'19.01.2027', 'tour_code'=>'PRODMK190127', 'country_name'=>'PROTHAILAND (3N/4D)', 'tour_amount'=>'Online Fare +14900', 'confirmed'=>'-', 'grp_tkn'=>'22', 'need'=>'-', 'reminder_45'=>'-', 'reminder_30'=>'-', 'ticket_status'=>'GROUP TAKEN', 'visa_status'=>'-', 'handling_person'=>'KEERTHANA DEVI', 'travel_bag'=>'-', 'tour_manager'=>'NO TOUR MANAGER', 'month_group'=>'JANUARY:2027')
        );

        $this->db->insert_batch('franchise_group_tours', $samples);
        $this->resequence_s_no();
        return count($samples);
    }
}
