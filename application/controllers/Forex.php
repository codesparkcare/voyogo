<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Forex extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->helper(array('url', 'form'));
		$this->load->library('session');
	}

	public function index()
	{
		$data['title'] = "Voyogo - Currency Exchange & Multi-Currency Forex Cards";
		$data['page_title'] = "Foreign Exchange & Currency Cards - Voyogo";
		$data['active_page'] = "forex";
		$this->load->view('includes/header', $data);
		$this->load->view('forex', $data);
		$this->load->view('includes/footer', $data);
	}
}
