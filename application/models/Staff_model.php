<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Staff_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function add_staff($data)
    {
        return $this->db->insert('staff_table', $data);
    }

    public function get_all_staff()
    {
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get('staff_table');
        return $query->result_array();
    }

    public function update_staff($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('staff_table', $data);
    }

    public function delete_staff($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('staff_table');
    }
}
