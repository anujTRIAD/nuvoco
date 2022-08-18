<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ooh_route_master extends CI_Controller {

	public function __construct() {
		parent::__construct();
		// $this->load->model('ooh_route_master_model');
		is_first_time_login($this->session->userdata('is_first_login'));
		
		// ini_set('memory_limit', '-1');
	
		/*error_reporting(E_ALL);
        ini_set('display_errors', '1');*/
	}

	function index() { 
		//can_access('admin','cmm','tmm_national','nsh','com','rsm','tmm');
		$includes = array('datatable');
		$data['inclusions'] = inclusions($includes);
		$data['page_title'] = "OOH Route Master";
		//$data['stores'] = $this->ooh_roote_master_model->store();
		load_page('ooh_route_master/index', $data);
	} 
    

}