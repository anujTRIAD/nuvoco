<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login_model extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function user_login($username, $password)
	{
		$this->db->select('*');
		$this->db->from('user');
		$this->db->where('username',$username);
		$temp = $this->db->get();
		
		// query exception
		if(!$this->db->affected_rows()){
			$this->query_exception($username,query(), json_encode($this->input->post()), 'web','Error in Query');
		}
		// query exception

		if($temp->num_rows()>0){
			$data = $temp->row_array();
			if($data['status']!='Approved'){
				$result  = array(
					'status'=>0,
					'message'=>'Inactive User!'
				);
				// catch exception
				$this->exception('web',$username,json_encode($this->input->post()),$result['message']);
				// catch exception
			}else{
				if($data['password'] == md5($password)){
					set_login_sessions($data);
					$result  = array(
						'status'=>1,
						'message'=>'Login Successfully!'
					);
					//catch activity log
					$this->activity_logs($result['message'],'Login');
					//catch activity log
				}else{
					$result  = array(
						'status'=>0,
						'message'=>'Incorrect Password!'
					);
					// catch exception
					$this->exception('web',$username,json_encode($this->input->post()),$result['message']);
					// catch exception
				}
			}
		}else{
			$result  = array(
				'status'=>0,
				'message'=>'Incorrect Username!'
			);
			// catch exception
			$this->exception('web',$username,json_encode($this->input->post()),$result['message']);
			// catch exception
		}

		
		return $result;
	}
}
