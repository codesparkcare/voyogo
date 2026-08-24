<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Migration: Update SMTP Settings with SecureServer Credentials
 */
class Migration_Update_smtp_settings extends CI_Migration {

    public function up()
    {
        if ($this->db->table_exists('email_settings')) {
            $data = array(
                'id'          => 1,
                'smtp_host'   => 'smtpout.secureserver.net',
                'smtp_port'   => 465,
                'smtp_user'   => 'support@voyogos.com',
                'smtp_pass'   => 'Voyo_support@123*#',
                'smtp_crypto' => 'ssl',
                'from_email'  => 'support@voyogos.com',
                'from_name'   => 'Voyogo Travels'
            );

            $existing = $this->db->get_where('email_settings', array('id' => 1))->num_rows();
            if ($existing > 0) {
                $this->db->where('id', 1);
                $this->db->update('email_settings', $data);
            } else {
                $this->db->insert('email_settings', $data);
            }
        }
    }

    public function down()
    {
        // Keep current settings
    }
}
