<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		if ($this->session->has_userdata('userdata')) {
			loadpage('dashboard');
		}
		$this->load->model('login_model','LM');
		$this->load->helper('cookie');
	}

	public function index()
	{
		//form validation
		$this->form_validation->set_rules('username','Username','required');
		$this->form_validation->set_rules('password','Password','required');
		$valid = $this->form_validation->run();
		//form validation
		
		if($valid){
			
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			$result = $this->LM->user_login($username,$password);
			if($result['status']){
				//create transaction
				$txn = $this->LM->txn_id('LGN');
				$this->LM->create_txn($txn['txn_id'],$txn['type'],'pending','Web');
				//create transaction
				
				//remember me logic
					$remember_me = $this->input->post('remember_me');
					if($remember_me){
						set_cookie('remember_me',1,86400);
						set_cookie('username',$username,86400);
						set_cookie('password',$password,86400);
					}else{
						unset_cookie('remember_me');
						unset_cookie('username');
						unset_cookie('password');
					}
				//remember me logic
				$this->session->set_flashdata('success',$result['message']);

				//update txn
				$this->LM->update_txn($txn['txn_id'],'complete');
				//update txn
				redirect('dashboard');
			}else{
				$this->session->set_flashdata('error',$result['message']);
				redirect('login');
			}
		}else{
			$this->load->view('login');
		}
		
	}

	
}
