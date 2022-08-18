<?php 
defined('BASEPATH') OR exit('No direct script access allowed');  

class Reports extends CI_Controller { 

	public function __construct() { 
		parent::__construct(); 
		// $this->load->model('reports_model');
		// is_first_time_login($this->session->userdata('is_first_login')); 
	
	}

	function index() {
		//can_access('admin', 'vendor','cmm','tmm','tmm_national','rsm','com','nsh'); 
		$includes = array('datatable', 'datepicker', 'fancybox');
		$data['inclusions'] = inclusions($includes);
		$data['page_title'] = "Reports"; 
		load_page('reports/index', $data); 
	}

     
}