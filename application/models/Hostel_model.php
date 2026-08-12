<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hostel_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function add_hostel($data)
    {
        return $this->db->insert('hostel_table', $data);
    }

    public function get_all_hostels()
    {
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get('hostel_table');
        return $query->result_array();
    }

    public function update_hostel($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('hostel_table', $data);
    }

    public function delete_hostel($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('hostel_table');
    }
}
