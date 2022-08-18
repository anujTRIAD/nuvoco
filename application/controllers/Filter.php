<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Filter extends CI_Controller {
    
    public function __construct() {
		parent::__construct();
		$this->load->model('filter_model');
	}

	function loadState() {
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		$zone = $this->input->post('zone');
		$result = $this->filter_model->findState($zone);
		if ($result) {
			echo json_encode($result);
		} else {
			echo json_encode(TRUE);
		}
	}

	function loadCity() {
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		$state = $this->input->post('state');
		$result = $this->filter_model->findCity($state);
		if ($result) {
			echo json_encode($result);
		} else {
			echo json_encode(TRUE);
		}
	}

	 

}