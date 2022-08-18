<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Download_csv extends CI_Controller {

	public function __construct() {
		parent::__construct();
		// $this->load->model('download_csv_model');
	}

	function user() {
		// can_access('admin','manager');

		$this->load->helper('csv');
		// $user = custom_decode($_GET['user']);

		$this->db->select('`user_id`, `username`,`role`, `zone`, `state`,`email_id`, `phone`, `status`, `reg_date`, `create_by_name`, `create_by_code`'); 
		if( !empty($_GET['start_date']) ) {
			$this->db->where('date>=', date('Y-m-d', strtotime($_GET['start_date'])));
		}
		if( !empty($_GET['end_date']) ) {
			$this->db->where('date<=', date('Y-m-d', strtotime($_GET['end_date'])));
		}
		// $this->db->where('role', 'user');
		$query = $this->db->get('user');
		// echo $this->db->last_query();
		query_to_csv($query, TRUE, 'User.csv');
	}
	
	function attendance_all() {
		can_access('admin','manager');

		$this->load->helper('csv');

		$this->db->select('`name`, `attendance`.`emp_code`, `company_code`, `attendance`.`role`, `date`, `in_time`, `out_time`, `working_hours`, `halfs` as compliance, `msg` as message, `marked_by`, `attendance`.`manager_name`, `manager_approve`, `manager_remarks`, `attendance`.`admin_name`, `admin_approve`, `admin_remarks`');
		$this->db->join('user', 'user.emp_code = attendance.emp_code', 'left');
		if( get_session('role') == 'manager' ){
			$this->db->where('user.role', 'user');
			$this->db->where('user.manager_code', get_session('uniquecode'));
		} else {
			$this->db->where('user.role!=', 'Office');
			$this->db->where('user.role!=', 'admin');
		}
		if( !empty($_POST['start_date']) ) {
			$this->db->where('date>=', date('Y-m-d', strtotime($_POST['start_date'])));
		}
		if( !empty($_POST['end_date']) ) {
			$this->db->where('date<=', date('Y-m-d', strtotime($_POST['end_date'])));
		}
		$this->db->order_by('name asc', 'date asc');
		$query = $this->db->get('attendance');
		// echo $this->db->last_query();
		query_to_csv($query, TRUE, 'attendance.csv');
	}

}
