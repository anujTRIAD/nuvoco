<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		// if (!$this->session->userdata('id')) {
		// 	loadpage('login');
		// }
	}

	public function index()
	{
		
		loadpage('dashboard');
	}
	public function logout()
	{
		$data = $this->session->all_userdata();
		foreach ($data as $row => $rows_value) {
			$this->session->unset_userdata($row);
		}
		redirect('login');
	}
}
