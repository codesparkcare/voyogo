<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Migration: Add API Logs Table
 */
class Migration_Add_api_logs extends CI_Migration {

    public function up()
    {
        if (!$this->db->table_exists('api_logs')) {
            $this->dbforge->add_field(array(
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
            ));
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->add_key('service_type');
            $this->dbforge->add_key('action_name');
            $this->dbforge->create_table('api_logs', TRUE, array('ENGINE' => 'InnoDB', 'DEFAULT CHARSET' => 'utf8mb4'));
        }
    }

    public function down()
    {
        $this->dbforge->drop_table('api_logs', TRUE);
    }
}
