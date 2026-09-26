<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Migration: Add Exclusive Deals Table
 */
class Migration_Add_exclusive_deals extends CI_Migration {

    public function up()
    {
        if (!$this->db->table_exists('exclusive_deals')) {
            $this->dbforge->add_field(array(
                'id' => array(
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'auto_increment' => TRUE,
                ),
                'category' => array(
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                    'default'    => 'HOT DEALS',
                ),
                'title' => array(
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                ),
                'subtitle' => array(
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => TRUE,
                ),
                'promo_code' => array(
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                    'null'       => TRUE,
                ),
                'discount_text' => array(
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => TRUE,
                ),
                'image_url' => array(
                    'type' => 'TEXT',
                ),
                'link_url' => array(
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'default'    => '#',
                ),
                'sort_order' => array(
                    'type'       => 'INT',
                    'constraint' => 11,
                    'default'    => 0,
                ),
                'status' => array(
                    'type'       => 'ENUM("active","inactive")',
                    'default'    => 'active',
                ),
                'created_at' => array(
                    'type' => 'DATETIME',
                    'null' => TRUE,
                ),
                'updated_at' => array(
                    'type' => 'DATETIME',
                    'null' => TRUE,
                ),
            ));
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table('exclusive_deals', TRUE);
        }
    }

    public function down()
    {
        if ($this->db->table_exists('exclusive_deals')) {
            $this->dbforge->drop_table('exclusive_deals');
        }
    }
}
