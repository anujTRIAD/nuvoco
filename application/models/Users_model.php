<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Users_model extends MY_Model {

	public function __construct() {
		parent::__construct();
	}

	function where(){
		$where_array = array();
		
		
		return $where_array;
	
	}
	function all_data_count(){

		$where_array = $this->where();
		if( !empty($where_array) ) { $this->db->where($where_array); }
		$query = $this->db->get('user');
		return $query->num_rows();
	}
	
	function all_data($limit, $start, $col, $dir){

		$this->db->select('*');
		$where_array = $this->where();
	
		if( !empty($where_array) ) { $this->db->where($where_array); }
		$query = $this
				->db
				->limit($limit, $start)
				->order_by($col, $dir)
				
				->get('user');
				log_message('error', $this->db->last_query());
		if( $query->num_rows()>0 ) {
			return $query->result_array();
		} else {
			return array();
		}
	}
   
	function data_search($limit, $start, $search, $col, $dir){
		$search = trim($search);
		$this->db->select('*');
		$where_array = $this->where();
		if( !empty($where_array) ) { $this->db->where($where_array); }
		$query = $this
				->db
				->group_start()
				->like('user_id', $search)
				->or_like('role', $search)
				->or_like('zone', $search)
				->or_like('state', $search)
				->or_like('city', $search)
				->or_like('name', $search)
				->or_like('email_id', $search)
				->or_like('username', $search)
				->or_like('phone', $search)
				->or_like('status', $search)
				->group_end()
				->limit($limit, $start)
				->order_by($col, $dir)
				->get('user');
	   
		if( $query->num_rows()>0 ) {
			return $query->result_array();  
		} else {
			return array();
		}
	}

	function data_search_count($search) {
		$where_array = $this->where();

		if( !empty($where_array) ) { $this->db->where($where_array); }
		$query = $this
				->db
				->group_start()
				->like('user_id', $search)
				->or_like('role', $search)
				->or_like('zone', $search)
				->or_like('state', $search)
				->or_like('city', $search)
				->or_like('name', $search)
				->or_like('email_id', $search)
				->or_like('username', $search)
				->or_like('phone', $search)
				->or_like('status', $search)
				->group_end()
				->get('user');
	
		return $query->num_rows();
	}




	function add() { 
		$this->db->select('user_id');
		$this->db->order_by('id', 'desc');
		$this->db->limit(1);
		$data = $this->db->get('user')->row_array();
		$data1 = $data['user_id']+1; 
		$txn = txn_id('CUS'); 
		$insert_array = [
			'user_id' => $data1,
			'role' => safe($this->input->post('role')),
			'zone' => safe(implode(',',$this->input->post('zone'))),
			'state' => safe(implode(',',$this->input->post('state'))),
			'name' => safe($this->input->post('name')),
			'email_id' => safe($this->input->post('email')), 
			'contact_person' => safe($this->input->post('contact_no')),
			'phone' => safe($this->input->post('contact_no')),
			'username' => safe($this->input->post('username')), 
			'password' => md5(safe($this->input->post('password'))),
			'status' =>  'Approved',
			'reg_date' =>  date('Y-m-d H:i:s'),
			'create_by_name' =>  get_session('name'),
			'create_by_code' =>  get_session('user_id'),

		];

		$log = [
			'date' => date('Y-m-d'),
			'status' => 'Approved',
			'role' => safe($this->input->post('role')),
			'uniquecode' => $data1,
			'username' => safe($this->input->post('username')),
			'platform' => 'Web',
			'ip_add' => $_SERVER['REMOTE_ADDR'],
			'browser' => $_SERVER['HTTP_USER_AGENT'], 
			'master_txn' => $txn['txn_id'],
			'create_by_name' => get_session('name'),
			'create_by_code' => get_session('user_id'),
		];
		$res = $this->db->insert('user',$insert_array);
		// echo $this->db->last_query(); die;
		if ($res) { 
			$this->db->insert('userlogs',$log);
			update_txn($txn['txn_id'], $txn['type'], $data1, safe($this->input->post('role')), 'Web');
			return true;
		} 
	}
	
	function view($id) { 
		$ids = custom_decode($id);
		$this->db->where('id', $ids);
		$query = $this->db->get('user');
		if( $query->num_rows() > 0 ){
			$result = $query->row_array();
			return $result;
		}
		return false;
	}
	
	
	function get_zone() {
        $this->db->select('zone');
        if($this->session->userdata('role') != 'admin'){
            $this->db->where('brand', $this->session->userdata('brand'));
        }
        $this->db->group_by('zone'); 
        $query = $this->db->get('region_master');
		if( $query->num_rows() > 0 ){
			$result = $query->result_array();
			return $result;
		}
		return false;
	}

	function add_vendor() {
	    
		if($this->input->post('profile') != ""){
			$profile_type = implode(",",$this->input->post('profile'));
		} else{
			$profile_type = "";
		}
		$ins_arry = array(
			'role' =>'vendor',
			'user_type' => $this->input->post('vendor_type'),
			'user_id' => generate_user_id_new(),
			'contact_person' => $this->input->post('contact_person'),
			'username' => trim($this->input->post('username')),
			'password' => md5($this->input->post('password')),
			'email_id' => trim($this->input->post('email_id')),
			'name' =>$this->input->post('name'),
			'vendor_mobile' =>$this->input->post('mobile'),
			'aadhar' =>$this->input->post('adhar'),
			'pan_no' =>$this->input->post('pan'),
			'zone' =>$this->input->post('zone'),
			'region' => $this->input->post('region'),
			'state' => '',
			'city' => '',
			'address' => $this->input->post('address_line_1'),
			'address2' => $this->input->post('address_line_2'),
			'pincode' =>$this->input->post('pincode'),
			'gst_no' =>$this->input->post('gst_no'),
			'sap_code' =>$this->input->post('vendor_sap_code'),
			'profile' => $profile_type,
			'status' =>'Approved',
			'reg_date' =>date('Y-m-d'),
			'brand' =>(empty(@$this->session->userdata('brand')))?'':@$this->session->userdata('brand'),
			'old_vendor_code' => $this->input->post('old_vendor_code'),
		);
		
		$this->db->trans_start();
		$this->db->insert('user', $ins_arry);
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE ) {
			return false;

		} else {
		    
		    if($this->input->post('send_mail') == 'yes'){  
	           
			    $body = '<!DOCTYPE html>
		                <html lang="en">
            			<head>
            			</head>
            			<body>
            			<p>Dear Vendor,</p>
            			<p>Please find Your Login Credintials Details below.</p>
            			<p>Username : '.$this->input->post('username').'</p>
            			<p>Password : '.$this->input->post('password').'</p>
            			<br><br>
            			<p>Regards, <br>Exide</p>
            			</body>
            			</html>';
				
				
				$email_data = array(
                    'to'=> 'hansh@triadweb.in',
                    'subject' => 'Exide Login Credintials',
                    'message' => $body
                    );
                //$email = sendmail($email_data);
	        }
				    
		
    		$insert_id = $this->db->insert_id();
    		
    		if( !empty($_FILES['file']['name']) ) {
    				$extention = get_extention($_FILES['file']);
    				$user_file_name = "User_".time().'_'.$insert_id.".png";
    				$config = array(
    					'upload_path' => IMAGE_PATH,
    					'allowed_types' => '*',
    					'remove_spaces' => TRUE,
    					'max_size' => 1024*50,
    					'file_name' => $user_file_name,
    				);
    				$this->load->library('upload');
    				$this->upload->initialize($config);
    				$this->upload->do_upload('file');
    				set_value('picture', base_url('data/user_image/'.$user_file_name), 'user', $insert_id);
    			}
    		/*========= Txn History=====*/
    			update_txn($this->input->post('vendor_id'),"Add Vendor");
    			return true;
		    
		}
		
		
	}

    //=---------Edit Vendor-------------
	function edit_user($id) {
		$id = custom_decode($id);
		$ins_arry = array(
			'role' =>'vendor',
			'user_type' => $this->input->post('vendor_type'),
			'email_id' => $this->input->post('email_id'),
			'name' =>$this->input->post('name'),
			'aadhar' =>$this->input->post('adhar'),
			'pan_no' =>$this->input->post('pan'),
			'zone' =>$this->input->post('zone'),
			'region' => $this->input->post('region'),
			'address' => $this->input->post('address_line_1'),
			'address2' => $this->input->post('address_line_2'),
			'pincode' =>$this->input->post('pincode'),
			'gst_no' =>$this->input->post('gst_no'),
			//'profile' => implode(",",$this->input->post('profile')),
			//'old_vendor_code' => $this->input->post('old_vendor_code'),
		);
		$this->db->where('id', $id);
		$this->db->update("user",$ins_arry);
		if( !empty($_FILES['file']['name']) ) {
				$extention = get_extention($_FILES['file']);
				$user_file_name = "User_".time().'_'.$id.".png";
				$config = array(
					'upload_path' => IMAGE_PATH,
					'allowed_types' => '*',
					'remove_spaces' => TRUE,
					'max_size' => 1024*50,
					'file_name' => $user_file_name,
				);
				$this->load->library('upload');
				$this->upload->initialize($config);
				$this->upload->do_upload('file');
				set_value('picture', base_url('data/user_image/'.$user_file_name), 'user', $id);
			}
		/*========= Txn History=====*/
		update_txn($id,"Updated Vendor");
		return true;
	}
	
	function create_emp() {
		//debug($_POST);
		$txn = txn_id('AUS');
		$ins_arry = array(
			'role' =>$this->input->post('role'),
			'user_id' => generate_user_id_new(),
			'username' => $this->input->post('username'),
			'password' => md5($this->input->post('password')),
			'email_id' => $this->input->post('email'),
			'name' =>$this->input->post('name'),
			'phone'=>$this->input->post('phone'),
			'zone'=>$this->input->post('zone'),
			'brand'=> $this->session->userdata('brand'),
			'sap_code'=> (empty(@$this->input->post('sap_code')))?'':@$this->input->post('sap_code'),
			'position_code'=> (empty(@$this->input->post('position_code')))?'':@$this->input->post('position_code'),
			'is_first_login'=>1,
			'region' =>$this->input->post('region'),
			'status' =>'Approved',
			'reg_date' =>date('Y-m-d')
			
		);
		
		if($this->session->userdata('role')=='tmm_national'){
		    $ins_arry['type'] = $this->input->post('type');
		}
		if($this->input->post('role') == 'tmm'){
		    $ins_arry['region'] = '';
		} else {
		    $ins_arry['region'] =(empty(@$this->input->post('region')))?'':@$this->input->post('region');
		}
	
		$this->db->trans_start();
		$this->db->insert('user',$ins_arry);
		$this->db->trans_complete();
		
       if ($this->db->trans_status() === FALSE) {
           return false;
       } else {
            update_txn($txn["txn_id"], $txn["type"]);
		    return true;
       }
	}
	
	function emp_edit($id) {
		$id = custom_decode($id);
		$ins_arry = array(
			'email_id' => $this->input->post('email'),
			'name' =>$this->input->post('name'),
			'phone'=>$this->input->post('phone'),
			'zone'=>$this->input->post('zone'),
			'region'=>$this->input->post('region'),
			'position_code'=> (empty(@$this->input->post('position_code')))?'':@$this->input->post('position_code')
		);
		
		$this->db->where('id', $id);
		$this->db->update("user",$ins_arry);
		/*========= Txn History=====*/
		update_txn($id,"Updated User");
		return true;
	}

	function check_username_exist($id) {
		if( !empty($id) ){
			$this->db->where('id !=', custom_decode($id));
		}
		$this->db->where('username', $this->input->post('username'));
		$query = $this->db->get('user');
		if( $query->num_rows()>0 ) {
			return true;
		}
		return false;
	}

	function check_email_exist($id) {
		if( !empty($id) ){
			$this->db->where('id !=', custom_decode($id));
		}
		$this->db->where('email', $this->input->post('email'));
		$query = $this->db->get('user');
		if( $query->num_rows()>0 ) {
			return true;
		}
		return false;
	}

	function check_mobile_exist($id) {
		if( !empty($id) ){
			$this->db->where('id !=', custom_decode($id));
		}
		$this->db->where('phone', $this->input->post('mobile'));
		$query = $this->db->get('user');
		if( $query->num_rows()>0 ) {
			return true;
		}
		return false;
	}

	function check_uniquecode_exist($id) {
		if( !empty($id) ){
			$this->db->where('id !=', custom_decode($id));
		}
		$this->db->where('uniquecode', $this->input->post('uniquecode'));
		$query = $this->db->get('user');
		if( $query->num_rows()>0 ) {
			return true;
		}
		return false;
	}

	function uniquecode($office_code) {

		$this->db->select_max('emp_code');
    	$this->db->where('office_code', $office_code);
		$query = $this->db->get('user');
		if( $query->num_rows() > 0  ) {
			
			$result = $query->row_array();
			$code = ++$result['emp_code'];
			return $code;
		} else {
			return true;
		}
	}

	function check_user_exist($mobile) {
		$this->db->where('vendor_mobile', $mobile);
		$query = $this->db->get('user');
		if( $query->num_rows() > 0 ) {
			return true;
		}
		return false;
	}

	function get_tracker() {
	    if($this->input->get('status')){
	       if($this->input->get('status') == 'Completed'){
	          $this->db->where('status',$this->input->get('status')); 
	       }else{
	           $this->db->where('status',$this->input->get('status'));
	       } 
	    }
		//$this->db->where('role !=','admin');
		$query = $this->db->get('track_record');
		if( $query->num_rows() > 0 ){
			$result = $query->result_array();
			return $result;
		}
		return false;
	}

	// function code($length) {
	// 	$chars = "0123456789131767854267868321553154";
 	// 	$code = substr( str_shuffle( $chars ), 0, $length );
	// 	return $code;
	// }
	
	function tracker_image( $id ){
	    $this->db->where('track_record_id', $id);
		$query = $this->db->get('tracker_pictures');
		if( $query->num_rows() > 0 ) {
			return $query->result_array();
		}
		return false;
	}
	
	function view_tracker( $id ) {
		$this->db->where('id', $id);
		$query = $this->db->get('track_record');
		if( $query->num_rows() > 0 ) {
			return $query->row();
		}
		return false;
	}
	
	
	function findRegion($zone){
        
        if( isset($_POST['zone']) && !empty($_POST['zone']) ){
			if($_POST['zone'] != 'all') {
				if( is_array($_POST['zone']) ){
					$this->db->where_in('zone', $_POST['zone']);
				} else {
					$this->db->where('zone', $_POST['zone']);
				}
			}
		}
        
		$this->db->distinct();
        $this->db->select('region,region_level');
        $this->db->where('zone',$zone);
        $this->db->where('brand', $this->session->userdata('brand'));
        $query = $this->db->get('region_master');
        //debug($this->db->last_query());
        $result = $query->result_array();
        if(!empty($result))
        {
            return $result;
        } else {
            return array();
        }  
    }
    
    
    
	function findProject($zone_name,$project_region_name){
		
        $this->db->select('id,project_id,project_name,budgeted_amount');
        $this->db->where('project_zone',$zone_name);
        $this->db->where('project_region',$project_region_name);
        $this->db->where('brand',$this->session->userdata('brand'));
        $this->db->where('project_type','simple');
        $query = $this->db->get('projects');
	
		$output = '<option value="">Select Project</option>';
		foreach($query->result_array() as $row)
			{
		$output .= '<option value="'.$row['project_id'].'">'.$row['project_name'].'</option>';
			}
		return $output;
		
    }
	
	function findProjectBudget($project_id){

        $this->db->select('id,project_id,project_name,budgeted_amount');
        $this->db->where('project_id',$project_id);
        $query = $this->db->get('projects');
        
       $result = $query->result_array();
		
		$output = $result[0]['budgeted_amount'];		
		return $output;
						
    }
	
	function findProjectBudgetLeft($project_id){
		

	
		$this->db->select('id,project_id,project_name,budgeted_amount');
        $this->db->where('project_id',$project_id);
        $query = $this->db->get('projects');
		
		 $result = $query->result_array();
		
		$total_budget = $result[0]['budgeted_amount'];
		
	
		$this->db->select('SUM(IF(status >= 7 AND status != 24, pre_value, 0)) as budgets_used');				
        $this->db->where('project_id',$project_id);
        $query = $this->db->get('projects_vendor_mapping');

       $result = $query->result_array();
	   
	   $used_budget = $result[0]['budgets_used'];
	   
	   $rest_budget = $total_budget - $used_budget;
		
		$output = round($rest_budget);		
		return $output;
						
    }
		
	function findProjectBudgetLeft_used($project_id){
		

		$this->db->select('SUM(IF(status >= 7 AND status != 24, pre_value, 0)) as budgets_used');				
        $this->db->where('project_id',$project_id);
        $query = $this->db->get('projects_vendor_mapping');

       $result = $query->result_array();
	   
	    $used_budget = $result[0]['budgets_used'];
	   
	
		
		$output = round($used_budget);	
		return $output;
						
    }
	
    

	function findState($zone){
        
        if( isset($_POST['zone']) && !empty($_POST['zone']) ){
			if($_POST['zone'] != 'all') {
				if( is_array($_POST['zone']) ){
					$this->db->where_in('region', $_POST['zone']);
				} else {
					$this->db->where('region', $_POST['zone']);
				}
			}
		}
        
		$this->db->distinct();
        $this->db->select('statename');
        //$this->db->where('region',$zone);
        $query = $this->db->get('citylist');
        $result = $query->result_array();
        if(!empty($result))
        {
            return $result;
        } else {
            return array();
        }  
    }
	
	function findCity($state){
        $this->db->select('id,cityname');
        $this->db->where('statename',$state);
        $query = $this->db->get('citylist');
        $result = $query->result_array();
        if(!empty($result))
        {
            return $result;
        } else {
            return array();
        }  
    }

    function upload_vendor(){
    	if( !empty($_FILES['file']['name']) ) {
			$extention = get_extention($_FILES['file']);
			$file_name = "Vendor_".time().'_'.".csv";
			$config = array(
				'upload_path' => VENDOR_CSV,
				'allowed_types' => '*',
				'remove_spaces' => TRUE,
				'max_size' => 1024*50,
				'file_name' => $file_name,
			);
			$this->load->library('upload');
			$this->upload->initialize($config);
			$this->upload->do_upload('file');
			}
			$file = read_csv(base_url('data/vendor_csv/').$file_name);
			//debug($file);
			$count_csv = count($file);
			$error_data = array();
			for($i = 1; $i <= $count_csv - 1; $i++){
			    
				    /* Validation */
			        $status='';
    			    if(empty($file[$i][0])){
    			        
    			        set_flashdata('message', 'Vendor Type is empty in Row '.$i.'!', 'danger');
    			        $status='false';
    					$error_data[]=array(
			                        'vendor_type'    => $file[$i][0],
    							    'vendor_sapcode' => $file[$i][1],
    							    'email_id'       => $file[$i][2],
    							    'vendor_name'    => $file[$i][3],
    							    'contact_no'     => $file[$i][4],
                                    'error_msg'      => 'Vendor Type is empty',
    			                    );
    			    }
    			    
    			    if(empty($file[$i][1])){
    			        
    			        set_flashdata('message', 'Vendor SAPCODE is empty in Row '.$i.'!', 'danger');
    			        $status='false';
    			        $error_data[]=array(
			                        'vendor_type'    => $file[$i][0],
    							    'vendor_sapcode' => $file[$i][1],
    							    'email_id'       => $file[$i][2],
    							    'vendor_name'    => $file[$i][3],
    							    'contact_no'     => $file[$i][4],
                                    'error_msg'      => 'Venodr SAPCODE is empty',
    			                    );
    			    }
    			    
    			    if(empty($file[$i][2]))
    			    {
    			        set_flashdata('message', 'Email Id is empty in Row '.$i.'!', 'danger');
    			        $status='false';
    			        $error_data[]=array(
			                        'vendor_type'    => $file[$i][0],
    							    'vendor_sapcode' => $file[$i][1],
    							    'email_id'       => $file[$i][2],
    							    'vendor_name'    => $file[$i][3],
    							    'contact_no'     => $file[$i][4],
                                    'error_msg'      => 'Email Id is empty',
    			                    );
    					
    			    }
    			    
    			    if(empty($file[$i][3]))
    			    {
    			        set_flashdata('message', 'Vendor Name is empty in Row '.$i.'!', 'danger');
    			        $status='false';
    			        $error_data[]=array(
			                        'vendor_type'    => $file[$i][0],
    							    'vendor_sapcode' => $file[$i][1],
    							    'email_id'       => $file[$i][2],
    							    'vendor_name'    => $file[$i][3],
    							    'contact_no'     => $file[$i][4],
                                    'error_msg'      => 'Vendor Name is empty',
    			                    );
    			    }
    			    
    			    if(empty($file[$i][4]))
    			    {
    			        set_flashdata('message', 'Contact No is empty in Row '.$i.'!', 'danger');
    			        $status='false';
    			         $error_data[]=array(
			                        'vendor_type'    => $file[$i][0],
    							    'vendor_sapcode' => $file[$i][1],
    							    'email_id'       => $file[$i][2],
    							    'vendor_name'    => $file[$i][3],
    							    'contact_no'     => $file[$i][4],
                                    'error_msg'      => 'Contact No is is empty',
    			                    );
    			    }
    			    
    			    
    			    if(empty($file[$i][5]))
    			    {
    			        set_flashdata('message', 'Contact Person Name is empty in Row '.$i.'!', 'danger');
    			        $status='false';
    			         $error_data[]=array(
			                        'vendor_type'    => $file[$i][0],
    							    'vendor_sapcode' => $file[$i][1],
    							    'email_id'       => $file[$i][2],
    							    'vendor_name'    => $file[$i][3],
    							    'contact_no'     => $file[$i][4],
                                    'error_msg'      => 'Contact Person Name is is empty',
    			                    );
    			    }
    			    
    			    if(empty($file[$i][14])){
    			        set_flashdata('message', 'Username is empty in Row '.$i.'!', 'danger');
    			        $status='false';
    			         $error_data[]=array(
			                        'vendor_type'    => $file[$i][0],
    							    'vendor_sapcode' => $file[$i][1],
    							    'email_id'       => $file[$i][2],
    							    'vendor_name'    => $file[$i][3],
    							    'contact_no'     => $file[$i][4],
                                    'error_msg'      => 'Username is is empty',
    			                    );
    			    } else {
    			        
    			        $check_username = $this->check_vendorUsername($file[$i][14]);
    			    }
    			    
    			    if(empty($file[$i][15]))
    			    {
    			        set_flashdata('message', 'Password is empty in Row '.$i.'!', 'danger');
    			        $status='false';
    			         $error_data[]=array(
			                        'vendor_type'    => $file[$i][0],
    							    'vendor_sapcode' => $file[$i][1],
    							    'email_id'       => $file[$i][2],
    							    'vendor_name'    => $file[$i][3],
    							    'contact_no'     => $file[$i][4],
                                    'error_msg'      => 'Password is is empty',
    			                    );
    			    }
    			    
    			    
    			    
				

					/*$check_vendorID = $this->check_vendorID($file[$i][0]);
					if( $check_vendorID ){
						set_flashdata('message', 'Vendor ID already exist !', 'danger');
						$status='false';
						//redirect('user/upload_vendor');
						$error_data[]=array(
			                        'vendor_type'    => $file[$i][0],
    							    'vendor_sapcode' => $file[$i][1],
    							    'email_id'       => $file[$i][2],
    							    'vendor_name'    => $file[$i][3],
    							    'contact_no'     => $file[$i][4],
                                    'error_msg'      => 'Vendor ID is already exist',
    			                    );
					} */
					
					if($status !='false') {
						$ins_arry = array(
							'role' =>'vendor',
							'user_type' => $file[$i][1],
							'user_id'   => $file[$i][0],
							'username'  => $file[$i][0],
							'password'  => $file[$i][0],
							'email_id'  => $file[$i][2],
							'name'      => $file[$i][3],
							'vendor_mobile' =>$file[$i][4],
							'aadhar'    => $file[$i][7],
							'pan_no'    => $file[$i][8],
							'zone'      => $file[$i][10],
							'state'     => $file[$i][11],
							'city'      => $file[$i][12],
							'address'   => $file[$i][13],
							'address2'  => $file[$i][14],
							'pincode'   => $file[$i][15],
							'gst_no'    => $file[$i][8],
							'profile'   => $file[$i][5],
							'sap_code'   => $file[$i][6],
							'status'    =>'Approved'
						);
						$this->db->insert("user",$ins_arry);
						
						$data = array(
            			    'user_id'  => $file[$i][0],
            			    'username' => $file[$i][0],
            			    'password' => $file[$i][0],
            			    'name'     => $file[$i][3],
            		    );
					
    					$email_data = array(
                            //'to' => $file[$i][2],
                            'to'=>'hansh@triadweb.in',
                            'subject' => 'Vmx registration details',
                            'message' => $this->load->view('mail_templates/confirm_email', $data, true)
                            );
                        sendmail($email_data);
						
						
						/*========= Txn History=====*/
						update_txn($file[$i][0],"Add Vendor Through CSV");
					}
					
			}
			$this->session->set_userdata('error_data',$error_data);
			return true;
    }

    function check_vendorID($vendorID) {
		$this->db->where('user_id', $vendorID);
		$query = $this->db->get('user');
		if( $query->num_rows() > 0 ) {
			return true;
		}
		return false;
	}
	
	function get_profile(){
	    $this->db->select('*');
        $this->db->from('profile_type');
        $this->db->where('status',1);
        $query = $this->db->get();
        if( $query->num_rows() > 0 ){
            $result = $query->result_array();
            return $result;
        }
        return false;
	}
	
	
	function check_vendorUsername($username) {
		$this->db->where('user_id', $username);
		$query = $this->db->get('user');
		if( $query->num_rows() > 0 ) {
			return true;
		}
		return false;
	}
	
    function vendor_exists($sap_code) {
		$this->db->where('sap_code', $sap_code);
		$this->db->where('brand', $this->session->userdata('brand'));
		$this->db->where('role', 'vendor');
		$query = $this->db->get('user');
		if( $query->num_rows() > 0 ) {
			return true;
		}
		return false;
	}
	
	function download_user($role){
	    $this->db->select('*');
        $this->db->from('user');
        $this->db->where('brand', $this->session->userdata('brand'));
        $this->db->where('role',$role);
        $query = $this->db->get();
        //debug($this->db->last_query());
        if( $query->num_rows() > 0 ){
            $result = $query->result_array();
            return $result;
        }
        return false;
	}
	
	function allocation_report_fabricator(){
	    $this->db->select('usr.*, count(distinct NULLIF(pv.store_id,"")) as total_store, count(distinct NULLIF(pv.project_id,"") ) as total_project');
        $this->db->from('user usr');
        $this->db->join('projects_vendor_mapping pv', 'usr.user_id = pv.vendor_1','left');
        if( isset($_GET['start']) && !empty($_GET['start'])  ){
            $this->db->where('usr.created_at>=', date('Y-m-d 00:00:00', strtotime($_GET['start'])));
        }
        if( isset($_GET['end']) && !empty($_GET['end'])  ){
            $this->db->where('usr.created_at<=', date('Y-m-d 23:59:00', strtotime($_GET['end'])));
        }
        $this->db->where('role','vendor');
        //$this->db->where('user_type','Fabricator');
        $this->db->group_by('usr.user_id');
        $this->db->order_by('usr.user_id', 'DESC');
        $query = $this->db->get();
        if( $query->num_rows() > 0 ){
            $result = $query->result_array();
            return $result;
        }
        return false;
	}
	
	function allocation_report_printer(){
	    $this->db->select('usr.*, count(distinct NULLIF(pv.store_id,"")) as total_store, count(distinct NULLIF(pv.project_id,"") ) as total_project');
        $this->db->from('user usr');
        $this->db->join('projects_vendor_mapping pv', 'usr.user_id = pv.vendor_2','left');
        if( isset($_GET['start']) && !empty($_GET['start'])  ){
            $this->db->where('usr.created_at>=', date('Y-m-d 00:00:00', strtotime($_GET['start'])));
        }
        if( isset($_GET['end']) && !empty($_GET['end'])  ){
            $this->db->where('usr.created_at<=', date('Y-m-d 23:59:00', strtotime($_GET['end'])));
        }
        $this->db->where('role','vendor');
        //$this->db->where('user_type','Printer');
        $this->db->group_by('usr.user_id');
        $this->db->order_by('usr.user_id', 'DESC');
        $query = $this->db->get();
        if( $query->num_rows() > 0 ){
            $result = $query->result_array();
            return $result;
        }
        return false;
	}
	
	function transaction_report(){
		$query = $this->db->get('transaction');
		if( $query->num_rows() > 0 ) {
			return $result = $query->result_array();
		}
		return false;
	}
	
	
	
	function email_configuration_list() {
		//$this->db->where('role', 'admin');
		$query = $this->db->get('email_headers');
		if( $query->num_rows() > 0 ) {
			return $result = $query->result_array();
		}
		return false;
	}
	
	function add_email_header(){
	    $ins_array = array(
	        'section'=>$this->input->post('section'),
	        'subject'=>$this->input->post('subject'),
	        'to_mail'=>$this->input->post('to_mail'),
	        'cc_mail'=>$this->input->post('cc_mail'),
	        'bcc_mail'=>$this->input->post('bcc_mail'),
	       );
	       $this->db->insert('email_headers',$ins_array);
	       return true;
	}
	
	function user_list($role){
	    //$this->db->where_in('role',array('rsm','tmm','ASC'));
	    
	    $this->db->where_in('role',array($role));
	    if($this->session->userdata('role') !='admin'){
	        $this->db->where('brand', $this->session->userdata('brand'));
	    }
	    $query = $this->db->get('user');
		if( $query->num_rows() > 0 ) {
			return $result = $query->result_array();
		}
		return false;
	}
	
}