<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Migration: Initial Schema
 * 
 * Creates the base tables for the Voyogo application.
 * This mirrors the existing schema.sql file.
 */
class Migration_Initial_schema extends CI_Migration {

    public function up()
    {
        // admin_users table
        if (!$this->db->table_exists('admin_users')) {
            $this->dbforge->add_field(array(
                'id' => array('type' => 'INT', 'auto_increment' => TRUE),
                'username' => array('type' => 'VARCHAR', 'constraint' => 50),
                'password' => array('type' => 'VARCHAR', 'constraint' => 255),
                'email' => array('type' => 'VARCHAR', 'constraint' => 100),
                'created_at' => array('type' => 'DATETIME', 'default' => NULL),
            ));
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table('admin_users', TRUE, array('ENGINE' => 'InnoDB', 'DEFAULT CHARSET' => 'utf8mb4'));

            // Insert default admin
            $this->db->query("INSERT INTO `admin_users` (`username`, `password`, `email`, `created_at`)
                SELECT 'admin', '\$2y\$10\$cyNdwHuPyQQEtWob3FftDuNfhwqmMkmh5Li4i.bn2CfkgU0nLHEuO', 'admin@voyogo.com', NOW()
                FROM DUAL WHERE NOT EXISTS (SELECT * FROM `admin_users` WHERE `username` = 'admin')");
        }

        // flight_bookings table
        if (!$this->db->table_exists('flight_bookings')) {
            $this->dbforge->add_field(array(
                'id' => array('type' => 'INT', 'auto_increment' => TRUE),
                'booking_ref' => array('type' => 'VARCHAR', 'constraint' => 50),
                'pnr' => array('type' => 'VARCHAR', 'constraint' => 20, 'null' => TRUE),
                'airline_name' => array('type' => 'VARCHAR', 'constraint' => 100, 'null' => TRUE),
                'airline_code' => array('type' => 'VARCHAR', 'constraint' => 10, 'null' => TRUE),
                'flight_number' => array('type' => 'VARCHAR', 'constraint' => 20, 'null' => TRUE),
                'origin' => array('type' => 'VARCHAR', 'constraint' => 100, 'null' => TRUE),
                'destination' => array('type' => 'VARCHAR', 'constraint' => 100, 'null' => TRUE),
                'departure_datetime' => array('type' => 'DATETIME', 'null' => TRUE),
                'arrival_datetime' => array('type' => 'DATETIME', 'null' => TRUE),
                'cabin_class' => array('type' => 'VARCHAR', 'constraint' => 50, 'default' => 'Economy'),
                'passenger_details' => array('type' => 'TEXT', 'null' => TRUE),
                'contact_name' => array('type' => 'VARCHAR', 'constraint' => 100, 'null' => TRUE),
                'contact_email' => array('type' => 'VARCHAR', 'constraint' => 100, 'null' => TRUE),
                'contact_phone' => array('type' => 'VARCHAR', 'constraint' => 20, 'null' => TRUE),
                'total_amount' => array('type' => 'DECIMAL', 'constraint' => '10,2', 'default' => '0.00'),
                'payment_id' => array('type' => 'VARCHAR', 'constraint' => 100, 'null' => TRUE),
                'payment_status' => array('type' => 'VARCHAR', 'constraint' => 20, 'default' => 'Pending'),
                'booking_status' => array('type' => 'VARCHAR', 'constraint' => 20, 'default' => 'Confirmed'),
                'created_at' => array('type' => 'DATETIME', 'default' => NULL),
            ));
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table('flight_bookings', TRUE, array('ENGINE' => 'InnoDB', 'DEFAULT CHARSET' => 'utf8mb4'));
        }

        // hotel_bookings table
        if (!$this->db->table_exists('hotel_bookings')) {
            $this->dbforge->add_field(array(
                'id' => array('type' => 'INT', 'auto_increment' => TRUE),
                'booking_ref' => array('type' => 'VARCHAR', 'constraint' => 50),
                'hotel_id' => array('type' => 'VARCHAR', 'constraint' => 50, 'null' => TRUE),
                'hotel_name' => array('type' => 'VARCHAR', 'constraint' => 255, 'null' => TRUE),
                'hotel_address' => array('type' => 'TEXT', 'null' => TRUE),
                'hotel_image' => array('type' => 'TEXT', 'null' => TRUE),
                'room_type' => array('type' => 'VARCHAR', 'constraint' => 100, 'null' => TRUE),
                'checkin_date' => array('type' => 'DATE', 'null' => TRUE),
                'checkout_date' => array('type' => 'DATE', 'null' => TRUE),
                'guests_count' => array('type' => 'INT', 'default' => 1),
                'rooms_count' => array('type' => 'INT', 'default' => 1),
                'primary_guest_name' => array('type' => 'VARCHAR', 'constraint' => 100, 'null' => TRUE),
                'guest_email' => array('type' => 'VARCHAR', 'constraint' => 100, 'null' => TRUE),
                'guest_phone' => array('type' => 'VARCHAR', 'constraint' => 20, 'null' => TRUE),
                'total_amount' => array('type' => 'DECIMAL', 'constraint' => '10,2', 'default' => '0.00'),
                'payment_id' => array('type' => 'VARCHAR', 'constraint' => 100, 'null' => TRUE),
                'payment_status' => array('type' => 'VARCHAR', 'constraint' => 20, 'default' => 'Pending'),
                'booking_status' => array('type' => 'VARCHAR', 'constraint' => 20, 'default' => 'Confirmed'),
                'created_at' => array('type' => 'DATETIME', 'default' => NULL),
            ));
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table('hotel_bookings', TRUE, array('ENGINE' => 'InnoDB', 'DEFAULT CHARSET' => 'utf8mb4'));
        }

        // enquiries table
        if (!$this->db->table_exists('enquiries')) {
            $this->dbforge->add_field(array(
                'id' => array('type' => 'INT', 'auto_increment' => TRUE),
                'name' => array('type' => 'VARCHAR', 'constraint' => 100, 'null' => TRUE),
                'email' => array('type' => 'VARCHAR', 'constraint' => 100, 'null' => TRUE),
                'phone' => array('type' => 'VARCHAR', 'constraint' => 20, 'null' => TRUE),
                'message' => array('type' => 'TEXT', 'null' => TRUE),
                'created_at' => array('type' => 'DATETIME', 'default' => NULL),
            ));
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table('enquiries', TRUE, array('ENGINE' => 'InnoDB', 'DEFAULT CHARSET' => 'utf8mb4'));
        }

        // email_settings table
        if (!$this->db->table_exists('email_settings')) {
            $this->dbforge->add_field(array(
                'id' => array('type' => 'INT', 'default' => 1),
                'smtp_host' => array('type' => 'VARCHAR', 'constraint' => 100, 'default' => 'smtp.gmail.com'),
                'smtp_port' => array('type' => 'INT', 'default' => 587),
                'smtp_user' => array('type' => 'VARCHAR', 'constraint' => 100, 'default' => ''),
                'smtp_pass' => array('type' => 'VARCHAR', 'constraint' => 255, 'default' => ''),
                'smtp_crypto' => array('type' => 'VARCHAR', 'constraint' => 10, 'default' => 'tls'),
                'from_email' => array('type' => 'VARCHAR', 'constraint' => 100, 'default' => 'noreply@voyogo.com'),
                'from_name' => array('type' => 'VARCHAR', 'constraint' => 100, 'default' => 'Voyogo Travels'),
                'updated_at' => array('type' => 'DATETIME', 'null' => TRUE),
            ));
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table('email_settings', TRUE, array('ENGINE' => 'InnoDB', 'DEFAULT CHARSET' => 'utf8mb4'));

            // Insert default email settings
            $this->db->query("INSERT INTO `email_settings` (`id`, `smtp_host`, `smtp_port`, `smtp_user`, `smtp_pass`, `smtp_crypto`, `from_email`, `from_name`)
                SELECT 1, 'smtp.gmail.com', 587, '', '', 'tls', 'noreply@voyogo.com', 'Voyogo Travels'
                FROM DUAL WHERE NOT EXISTS (SELECT * FROM `email_settings` WHERE `id` = 1)");
        }
    }

    public function down()
    {
        $this->dbforge->drop_table('email_settings', TRUE);
        $this->dbforge->drop_table('enquiries', TRUE);
        $this->dbforge->drop_table('hotel_bookings', TRUE);
        $this->dbforge->drop_table('flight_bookings', TRUE);
        $this->dbforge->drop_table('admin_users', TRUE);
    }
}
