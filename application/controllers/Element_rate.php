<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Element_rate extends CI_Controller {

	public function __construct() {
		parent::__construct();
		// $this->load->model('element_rate_model');
		is_first_time_login($this->session->userdata('is_first_login')); 
	}

	function index() { 
		//can_access('admin','cmm','tmm_national','nsh','com','rsm','tmm');
		$includes = array('datatable', 'datepicker', 'fancybox');
		$data['inclusions'] = inclusions($includes);
		$data['page_title'] = "Element Rate Master";
		//$data['stores'] = $this->vendor_model->store();
		load_page('element_rate/index', $data);
	} 
    

}