<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cruises extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->helper(array('url', 'form'));
		$this->load->library('session');
	}

	public function index()
	{
		$data['title'] = "Voyogo - Luxury Ocean & River Cruise Packages";
		$data['page_title'] = "Luxury Ocean & River Cruise Packages - Voyogo";
		$data['active_page'] = "cruises";
		$this->load->view('includes/header', $data);
		$this->load->view('cruises', $data);
		$this->load->view('includes/footer', $data);
	}
}
