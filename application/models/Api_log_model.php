<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_log_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->ensure_table_exists();
    }

    /**
     * Self-healing table check
     */
    public function ensure_table_exists() {
        if (!$this->db->table_exists('api_logs')) {
            $this->load->dbforge();
            $fields = array(
                'id' => array(
                    'type'           => 'BIGINT',
                    'auto_increment' => TRUE,
                ),
                'service_type' => array(
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                ),
                'action_name' => array(
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                ),
                'endpoint_url' => array(
                    'type' => 'TEXT',
                ),
                'request_method' => array(
                    'type'       => 'VARCHAR',
                    'constraint' => 10,
                    'default'    => 'POST',
                ),
                'request_payload' => array(
                    'type' => 'LONGTEXT',
                    'null' => TRUE,
                ),
                'response_payload' => array(
                    'type' => 'LONGTEXT',
                    'null' => TRUE,
                ),
                'http_code' => array(
                    'type'       => 'INT',
                    'constraint' => 11,
                    'default'    => 200,
                ),
                'execution_time_ms' => array(
                    'type'       => 'INT',
                    'constraint' => 11,
                    'default'    => 0,
                ),
                'error_message' => array(
                    'type' => 'TEXT',
                    'null' => TRUE,
                ),
                'ip_address' => array(
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                    'null'       => TRUE,
                ),
                'created_at' => array(
                    'type' => 'DATETIME',
                    'null' => TRUE,
                ),
            );
            $this->dbforge->add_field($fields);
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->add_key('service_type');
            $this->dbforge->add_key('action_name');
            $this->dbforge->create_table('api_logs', TRUE);
        }
    }

    /**
     * Record an API Request & Response
     */
    public function log_call($service_type, $action_name, $url, $method = 'POST', $request_payload = null, $response_payload = null, $http_code = 200, $execution_time_ms = 0, $error_msg = null, $ip = null) {
        try {
            // Convert arrays/objects to JSON strings if passed as array
            if (is_array($request_payload) || is_object($request_payload)) {
                $request_payload = json_encode($request_payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            }
            if (is_array($response_payload) || is_object($response_payload)) {
                $response_payload = json_encode($response_payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            }

            if (empty($ip)) {
                $ip = $this->input->ip_address();
            }

            $data = array(
                'service_type'      => strtolower(trim($service_type)),
                'action_name'       => trim($action_name),
                'endpoint_url'      => trim($url),
                'request_method'    => strtoupper(trim($method)),
                'request_payload'   => $request_payload,
                'response_payload'  => $response_payload,
                'http_code'         => (int)$http_code,
                'execution_time_ms' => (int)$execution_time_ms,
                'error_message'     => $error_msg,
                'ip_address'        => $ip,
                'created_at'        => date('Y-m-d H:i:s')
            );

            return $this->db->insert('api_logs', $data);
        } catch (Exception $e) {
            log_message('error', 'Failed to insert API log: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Apply filter conditions to Active Record query
     */
    private function _apply_filters($filters = array()) {
        if (!empty($filters['service_type']) && $filters['service_type'] !== 'all') {
            $this->db->where('service_type', strtolower($filters['service_type']));
        }

        if (!empty($filters['status'])) {
            if ($filters['status'] === 'success') {
                $this->db->where('http_code >=', 200);
                $this->db->where('http_code <', 300);
            } elseif ($filters['status'] === 'error') {
                $this->db->group_start();
                $this->db->where('http_code >=', 400);
                $this->db->or_where('http_code', 0);
                $this->db->or_where('error_message IS NOT NULL', NULL, FALSE);
                $this->db->group_end();
            }
        }

        if (!empty($filters['search'])) {
            $q = trim($filters['search']);
            $this->db->group_start();
            $this->db->like('action_name', $q);
            $this->db->or_like('endpoint_url', $q);
            $this->db->or_like('ip_address', $q);
            $this->db->or_like('error_message', $q);
            $this->db->group_end();
        }

        if (!empty($filters['date_from'])) {
            $this->db->where('created_at >=', $filters['date_from'] . ' 00:00:00');
        }
        if (!empty($filters['date_to'])) {
            $this->db->where('created_at <=', $filters['date_to'] . ' 23:59:59');
        }
    }

    /**
     * Get paginated logs list
     */
    public function get_logs($limit = 50, $offset = 0, $filters = array()) {
        $this->_apply_filters($filters);
        $this->db->order_by('id', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get('api_logs')->result_array();
    }

    /**
     * Count filtered logs
     */
    public function count_logs($filters = array()) {
        $this->_apply_filters($filters);
        return $this->db->count_all_results('api_logs');
    }

    /**
     * Get a single log by ID
     */
    public function get_log_by_id($id) {
        return $this->db->get_where('api_logs', array('id' => (int)$id))->row_array();
    }

    /**
     * Get high-level API logs statistics
     */
    public function get_stats($service_type = null) {
        if ($service_type) {
            $this->db->where('service_type', strtolower($service_type));
        }
        $total = $this->db->count_all_results('api_logs');

        $flight_calls = $this->db->where('service_type', 'flight')->count_all_results('api_logs');
        $hotel_calls  = $this->db->where('service_type', 'hotel')->count_all_results('api_logs');
        $razorpay_calls = $this->db->where('service_type', 'razorpay')->count_all_results('api_logs');

        if ($service_type) {
            $this->db->where('service_type', strtolower($service_type));
        }
        $this->db->where('http_code >=', 200)->where('http_code <', 300);
        $success_count = $this->db->count_all_results('api_logs');
        $error_count   = $total - $success_count;

        if ($service_type) {
            $this->db->where('service_type', strtolower($service_type));
        }
        $this->db->select_avg('execution_time_ms', 'avg_time');
        $avg_row = $this->db->get('api_logs')->row_array();
        $avg_time = round($avg_row['avg_time'] ?? 0);

        $today_date = date('Y-m-d 00:00:00');
        if ($service_type) {
            $this->db->where('service_type', strtolower($service_type));
        }
        $this->db->where('created_at >=', $today_date);
        $today_calls = $this->db->count_all_results('api_logs');

        return array(
            'total_calls'    => $total,
            'flight_calls'   => $flight_calls,
            'hotel_calls'    => $hotel_calls,
            'razorpay_calls' => $razorpay_calls,
            'success_count'  => $success_count,
            'error_count'    => $error_count,
            'success_rate'   => ($total > 0) ? round(($success_count / $total) * 100, 1) : 100,
            'avg_latency_ms' => $avg_time,
            'today_calls'    => $today_calls
        );
    }

    /**
     * Purge logs older than X days
     */
    public function clear_old_logs($days = 30) {
        $cutoff = date('Y-m-d H:i:s', strtotime("-$days days"));
        $this->db->where('created_at <', $cutoff);
        return $this->db->delete('api_logs');
    }

    /**
     * Truncate all logs
     */
    public function clear_all_logs() {
        return $this->db->empty_table('api_logs');
    }
}
