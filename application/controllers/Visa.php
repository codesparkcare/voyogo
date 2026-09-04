<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Visa extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->helper(array('url', 'form'));
		$this->load->library('session');
	}

	public function index()
	{
		$data['title'] = "Voyogo - Tourist & Business Visas | Fast & Easy Visa Processing";
		$data['page_title'] = "Tourist & Business Visas - Voyogo";
		$data['active_page'] = "visa";
		$this->load->view('includes/header', $data);
		$this->load->view('visa', $data);
		$this->load->view('includes/footer', $data);
	}
}

