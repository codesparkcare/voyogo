<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Migration: Add Razorpay Settings Table
 */
class Migration_Add_razorpay_settings extends CI_Migration {

    public function up()
    {
        if (!$this->db->table_exists('razorpay_settings')) {
            $this->dbforge->add_field(array(
                'id' => array(
                    'type'       => 'INT',
                    'constraint' => 11,
                    'default'    => 1,
                ),
                'razorpay_key_id' => array(
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'default'    => 'rzp_test_TTVGSNKy0V1o7B',
                ),
                'razorpay_key_secret' => array(
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'default'    => 'na1MTEQwpH6CFfHOVghZn2GO',
                ),
                'merchant_name' => array(
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'default'    => 'Voyogo Travels',
                ),
                'theme_color' => array(
                    'type'       => 'VARCHAR',
                    'constraint' => 20,
                    'default'    => '#0d3470',
                ),
                'currency' => array(
                    'type'       => 'VARCHAR',
                    'constraint' => 10,
                    'default'    => 'INR',
                ),
                'environment' => array(
                    'type'       => 'VARCHAR',
                    'constraint' => 20,
                    'default'    => 'test',
                ),
                'is_enabled' => array(
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'default'    => 1,
                ),
                'updated_at' => array(
                    'type' => 'DATETIME',
                    'null' => TRUE,
                ),
            ));
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table('razorpay_settings', TRUE, array('ENGINE' => 'InnoDB', 'DEFAULT CHARSET' => 'utf8mb4'));

            // Insert default Razorpay credentials
            $this->db->query("INSERT INTO `razorpay_settings` (`id`, `razorpay_key_id`, `razorpay_key_secret`, `merchant_name`, `theme_color`, `currency`, `environment`, `is_enabled`)
                SELECT 1, 'rzp_test_TTVGSNKy0V1o7B', 'na1MTEQwpH6CFfHOVghZn2GO', 'Voyogo Travels', '#0d3470', 'INR', 'test', 1
                FROM DUAL WHERE NOT EXISTS (SELECT * FROM `razorpay_settings` WHERE `id` = 1)");
        }
    }

    public function down()
    {
        $this->dbforge->drop_table('razorpay_settings', TRUE);
    }
}
