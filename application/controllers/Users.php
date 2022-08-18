<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends MY_Controller {

	public function __construct() {
		parent::__construct(); 
		$this->load->model('users_model','UM');
		
	}

	function index() {

		$includes = array('multiselect', 'datatable', 'datepicker', 'select');
		$data['inclusions'] = inclusions($includes);
		$data['page_title'] = "User Master";
		load_page('users/index', $data);
	}

	function data(){
		
		$columns = array(
			0 => 'user_id', 
			1 => 'role',
			2 => 'zone',
			3 => 'state',
			4 => 'city',
			5 => 'name',
			6 => 'email_id',
			7 => 'phone',
			8 => 'username',
			9 => 'status'
		
		);
      
		$limit = intval($this->input->post('length'));
	    $start = intval($this->input->post('start'));
	
	
	    if($this->input->post('order')[0]['column']){
	         $order = $columns[$this->input->post('order')[0]['column']];
		    $dir = $this->input->post('order')[0]['dir'];
	    }else{
	         $order = 'id';
		    $dir = 'DESC';
	    }
	   
			$totalData = $this->UM->all_data_count();
			$totalFiltered = $totalData; 
				
			if( empty($this->input->post('search')['value']) ) {
				$data = $this->UM->all_data($limit, $start, $order, $dir);
			} else {
				$search = $this->input->post('search')['value']; 
				$data =  $this->UM->data_search($limit=10, $start=0, $search, $order,$dir);
				$totalFiltered = $this->UM->data_search_count($search);
			}
         
			if( !empty($data) ) {
				
				foreach ($data as $user) {
					$data_arr = array();
					$data_arr[] = $user['user_id'];
					$data_arr[] = $user['role'];
					$data_arr[] = $user['zone'];
					$data_arr[] = $user['state'];
					$data_arr[] = $user['city'];
					$data_arr[] = $user['name'];
					$data_arr[] = $user['email_id'];
					$data_arr[] = $user['phone'];
					$data_arr[] = $user['username'];  

					
					if($user['status']=='Approved'){
						$status = '<a href="'.base_url('users/change_status/').custom_encode($user['id']).'" class="badge badge-success" style="cursor:pointer">Approved</a>';
					}else{
						$status = '<a href="'.base_url('users/change_status/').custom_encode($user['id']).'" class="badge badge-danger" style="cursor:pointer">Inactive</a>';
					}
					 

					$data_arr[] = $status; 

					$data_array[] = $data_arr;
                     
				}
				
			
			}

		$json_data = array(
			"draw" => intval($this->input->post('draw')),
			"recordsTotal" => intval($totalData),
			"recordsFiltered" => intval($totalFiltered),
			"data" => $data_array
		);
		echo json_encode($json_data);
	}


	function change_status($id){
		$id = custom_decode($id);
		
		//create transaction
		$txn = $this->UM->txn_id('UST');
		$this->UM->create_txn($txn['txn_id'],$txn['type'],'pending','Web');
		//create transaction

		$result = $this->UM->get_records('id,status','user',['id'=>$id],'one');
		if($result){
			if($result['status'] =='Approved'){		
				$temp = $this->UM->update_records('user',['status'=>'Inactive'],['id'=>$id]);
			}else{
				$temp = $this->UM->update_records('user',['status'=>'Approved'],['id'=>$id]);
			}
	
			if($temp){
				$this->session->set_flashdata('success','Status Updated Successfully');
				$this->UM->activity_logs('Status Changed','User');
			}else{
				$this->session->set_flashdata('success','Error in Updating Status!');
			}

				//update txn
				$this->UM->update_txn($txn['txn_id'],'complete');
				//update txn
			redirect($_SERVER['HTTP_REFERER']);
		}
	}
	

	function add(){
		$data['role'] = $this->UM->get_records('role_name','role');
		$data['zone'] = $this->UM->fatch_zone();
		$data['brand'] = $this->UM->fatch_brand();
		$includes = array('datatable', 'chosen', 'validate');
		$data['inclusions'] = inclusions($includes);
		$data['page_title'] = "Add User";
		  
	
		if (isset($_POST['submit'])) { 
			$data = $this->UM->add();
			if ($data) {
				set_flashdata('message', 'User added successfully', 'success');
			    redirect('users');
			}else{
				set_flashdata('message', 'Somethig wrong, try again', 'danger');
			    redirect('users/add');
			}
		}else{
			
			load_page('users/add', $data);
		} 
	} 

	function view($id){
		if (isset($_POST['submit'])) { 
			$data = $this->users_model->edit($id);
			if ($data) {
				set_flashdata('message', 'User updated successfully', 'success');
			    redirect('users');
			}else{
				set_flashdata('message', 'Somethig wrong, try again', 'danger');
			    redirect('users/add/'.$id);
			}
		}else{ 
			$data['user'] = $this->users_model->view($id);
			// debug($data['user']); die;
			$data['role'] = get_table('role'); 
			$data['page_title'] = "User View"; 
			loadpage('users/view', $data);
		} 
	}
	
}
