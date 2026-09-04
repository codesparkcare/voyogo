<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cabs extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->helper(array('url', 'form'));
		$this->load->library('session');
	}

	public function index()
	{
		$data['title'] = "Voyogo - Airport & Outstation Cab Rentals | Safe & Sanitized Taxis";
		$data['page_title'] = "Airport & Outstation Cab Rentals - Voyogo";
		$data['active_page'] = "cabs";
		$this->load->view('includes/header', $data);
		$this->load->view('cabs', $data);
		$this->load->view('includes/footer', $data);
	}
}
