<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Holidays extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->helper(array('url', 'form'));
		$this->load->library('session');
	}

	public function index()
	{
		$data['title'] = "Voyogo - Holiday Packages & Exclusive Deals";
		$data['page_title'] = "Voyogo - Holiday Packages & Exclusive Deals";
		$data['active_page'] = "holidays";
		$this->load->view('includes/header', $data);
		$this->load->view('holidays', $data);
		$this->load->view('includes/footer', $data);
	}
}

