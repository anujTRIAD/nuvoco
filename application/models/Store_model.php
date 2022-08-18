<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Store_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	function store($id='') {
	    
		if( $id != ''){
			$this->db->where('id', $id);
		}
		$this->db->where('brand', $this->session->userdata('brand')); 
		$this->db->order_by('id','DESC');
		$this->store_list_role_wise();
		$query = $this->db->get('store_master');
		if( $query->num_rows() > 0 ){
			$result = $query->result_array();
			return $result;
		}
		return false;
	}
	
	
	function upload_logs_data() {
	    
		
		$this->db->order_by('id','DESC');
		$query = $this->db->get('upload_log');
		if( $query->num_rows() > 0 ){
			$result = $query->result_array();
			return $result;
		}
		return false;
	}
	
	function get_store_master() {
		
		$this->db->order_by('id','DESC');
		$query = $this->db->get('store_master');
		if( $query->num_rows() > 0 ){
			$result = $query->row_array();
		//	echo "<pre>"; print_r($result); die;
			return $result;
		}
		return false;
	}
	
	function check_row($row, $role){ 
		$error_message = array();
		$array_key = array_keys($row);   
	//	echo $row[$array_key[0]];
  //  print_r($array_key); 
  //  die;
		//===========  Uniquecode Check ==========
		if( $row[$array_key[0]] == '' ){
			$error_message[] = 'Missing Sap code';
		} else {
			$res = $this->uniquecode_check($row[$array_key[0]]);
			if( $res != false ){
				$error_message[] = $res;
			} 
		}

		return $error_message;
	}
	
	function uniquecode_check($sap_code){
		$this->db->select('status');
		$this->db->where('sap_code', $sap_code);
	//	$this->db->where('role', $role);
		$query = $this->db->get('store_master');
		// echo  $this->db->last_query(); die;
		if( $query->num_rows() > 0 ){
			// $res = $query->row_array();
			// if( $res['status'] != 'Approved' ){
			// 	return 'Inactive Uniquecode';
			// } else {
				return false;
			// }
		}
		return "<p class='text-danger'>Sap code does not exist</p>";
	}
	
	function submit_file($file,$column){
	   // echo "hello bb"; die;
		$column = (array) $column;
		$data = parse_csv_file($file);
		$key = array_keys($data[1]);  
		
		/*
		echo "<pre>";
		print_r($key);
		echo "<br>";
		print_r($column);
		echo count($key);
		echo "<br>";
		echo count($column)+1;
		*/
		
		$row_count = count($data); 
		if( count($key) != count($column)+1 ){
		     //   echo count($key);
		     //	echo "<br>";
		     //   echo count($column)+1;
		     //	echo "now"; die;
			return false;
			
		}
	//	echo "below"; die;
        
		$i=1; 
		$counter = 0; 
		foreach ($column as $keys => $res) { 
			if (in_array($res, $column)) { 
				if (trim(strtolower(($res))) != trim(strtolower($key[$i]))) {  
					$counter++;
				}
				
			$i++;
			} 

		}  
	//	echo "<br><br>";
	//	echo $counter; 
	//	echo "<br><br>";

	    if($counter==0){
	        
	        // $column
	      //  echo "<pre>";
	      //  print_r($data);
	        
	        foreach($data as  $key =>$rows)
	        {   //echo $rows;
	        
	           foreach($rows as $update_key => $update_row)
	            {
	                if($update_key !="sap_code")
	                {
	                    if($update_row ==''){
	                        return "empty";
	                        break;
	                    }
	                }
	                
	             }
	            
	        }
	        
	       foreach($data as  $key =>$rows)
	         {   //echo $rows;
	        
	           foreach($rows as $update_key => $update_row)
	            {
	                if($update_key !="sap_code")
	                {
	                    if($update_row !=''){
	            $sql_update="update store_master SET $update_key = '".$update_row."' where sap_code ='".$rows['sap_code']."' ";
	            $this->db->query($sql_update);
	           // echo "<br>";
	                    }
	                }
	                
	             }
	            
	        }
	        
	            $txn = txn_id('SMA');
				
				update_txn($txn['txn_id'], $txn['type']); 
				return true;
		} else {
		return false;
		}
			
}
	
	function get_zone() {
        $this->db->select('zone');
        $this->db->where('brand', $this->session->userdata('brand')); 
        $this->db->group_by('zone'); 
        $query = $this->db->get('region_master');
		if( $query->num_rows() > 0 ){
			$result = $query->result_array();
			return $result;
		}
		return false;
	}

	function add_store() {
	    
		$txn = txn_id('AST');

		$ins_arry = array(
		    'channel_partner_type' => $this->input->post('channel_partner_type'),
		    'sap_code' => $this->input->post('sap_code'),
		    'channel_partner_name' => '',
		    'dealer_code' => (empty(@$this->input->post('dealer_code')))?'':@$this->input->post('dealer_code'),
		    'dealer_name' => (empty(@$this->input->post('dealer_name')))?'':@$this->input->post('dealer_name'),
		    'tmm_zone' => $this->input->post('tmm_zone'),
		    'region' => $this->input->post('region'),
		    'hub' => '',
		    'spoke' => '',
		    'ec_status' => $this->input->post('ec_status'),
		    'category' => $this->input->post('category'),
		    'brand' =>(empty(@$this->session->userdata('brand')))?'':@$this->session->userdata('brand'),
		    //'signage_type_1st_intallation' => 
		    //'in_shop_branding_status_1st_installation' => $this->input->post('in_shop_branding_status_1st_installation'),
			'store_uniqueID' => generate_store_code(),
			'store_name' => $this->input->post('store_name'),
			'store_description' => '',
			'address' => $this->input->post('address'),
			'contact_person' =>'',
			'contact_no' =>$this->input->post('mobile'),
			'address2' => $this->input->post('address2'),
			'pincode' =>$this->input->post('pincode'),
			'status' =>'Active',
			'store_valuation' => '',
			'created_at'=>date("Y-m-d H:i:s")   
		); 
		
        if($this->session->userdata('brand') == 'Exide'){
            
            $ins_arry['signage_type_1st_intallation'] = (empty(@$this->input->post('signage_type_1st_intallation')))?'':@$this->input->post('signage_type_1st_intallation');
    		$ins_arry['in_shop_branding_status_1st_installation'] = $this->input->post('in_shop_branding_status_1st_installation');

    		if($this->input->post('signage_type_1st_intallation') == 'No'){
    		    
    		    $ins_arry['1st_installation_date'] = '';
    		    $ins_arry['signage_type_2st_intallation'] = '';
    		    $ins_arry['2nd_installation_date'] = '';
    		} else {
    		    
    		    $ins_arry['1st_installation_date'] = $this->input->post('1st_installation_date');
    		    $ins_arry['signage_type_2st_intallation'] = $this->input->post('signage_type_2st_intallation');
    		    $ins_arry['2nd_installation_date'] = $this->input->post('2nd_installation_date');
    		}
    		
    		if($this->input->post('in_shop_branding_status_1st_installation') == 'No'){
    		    
    		    $ins_arry['in_shop_branding_1st_date'] = '';
    		    $ins_arry['in_shop_branding_status_2st_installation'] = '';
    		    $ins_arry['in_shop_branding_2nd_date'] = '';
    		} else {
    		    $ins_arry['in_shop_branding_1st_date'] = $this->input->post('in_shop_branding_1st_date');
    		    $ins_arry['in_shop_branding_status_2st_installation'] = $this->input->post('in_shop_branding_status_2st_installation');
    		    $ins_arry['in_shop_branding_2nd_date'] = $this->input->post('in_shop_branding_2nd_date');
    		}
		
	    }
		
		$this->db->trans_start();
		$this->db->insert("store_master",$ins_arry);
        $this->db->trans_complete();
        
        //debug($this->db->last_query());
        
        if ($this->db->trans_status() === FALSE) {
           return false;
        } else {
           update_txn($txn['txn_id'],$txn['type']);
		   return true;
        }
		
	}

	function edit_store($id) {
		$id = custom_decode($id);
		$ins_arry = array(
			'store_name' => $this->input->post('store_name'),
			'address' => $this->input->post('address'),
			'contact_person' =>$this->input->post('name'),
			'contact_no' =>$this->input->post('mobile'),
			/*'tmm_zone' =>$this->input->post('zone'),
			'region' =>$this->input->post('region'),*/
			'address2' => $this->input->post('address2'),
			'pincode' =>$this->input->post('pincode'),
			/*'ec_status' => $this->input->post('ec_status'),
		    'category' => $this->input->post('category'),*/
		);
		
		if($this->session->userdata('brand') == 'Exide'){
		    
		    $ins_arry['signage_type_1st_intallation'] = $this->input->post('signage_type_1st_intallation');
		    $ins_arry['1st_installation_date'] = $this->input->post('1st_installation_date');
		    $ins_arry['signage_type_2st_intallation'] = $this->input->post('signage_type_2st_intallation');
		    $ins_arry['2nd_installation_date'] = $this->input->post('2nd_installation_date');
		    $ins_arry['in_shop_branding_status_1st_installation'] = $this->input->post('in_shop_branding_status_1st_installation');
		    $ins_arry['in_shop_branding_1st_date'] = $this->input->post('in_shop_branding_1st_date');
		    $ins_arry['in_shop_branding_status_2st_installation'] = $this->input->post('in_shop_branding_status_2st_installation');
		    $ins_arry['in_shop_branding_2nd_date'] = $this->input->post('in_shop_branding_2nd_date');
		}
		$this->db->where('id', $id);
		$this->db->where('brand', $this->session->userdata('brand')); 
		$this->db->update("store_master",$ins_arry);

		return true;
	}

	function edit_user($id) {
		$id = custom_decode($id);
		$update_array = array(
			'name' => $this->input->post('name'),
			'phone' => $this->input->post('mobile'),
			'username' => $this->input->post('username'),
			'password' => $this->input->post('password'),
			'email' => $this->input->post('email'),
			'address' => $this->input->post('address'),
		);
		$update_array = $this->security->xss_clean($update_array);
		$this->db->where('id', $id);
		$this->db->update('user', $update_array);
		
		if( !empty($_FILES['file']['name']) ) {
			
			$extention = get_extention($_FILES['file']);
			$file_name = "User_".time().'_'.$id.".png";
			$config = array(
				'upload_path' => IMAGE_PATH,
				'allowed_types' => '*',
				'remove_spaces' => TRUE,
				'max_size' => 1024*50,
				'file_name' => $file_name,
			);
			$this->load->library('upload');
			$this->upload->initialize($config);
			$this->upload->do_upload('file');

			set_value('image', base_url('data/user_image/'.$file_name), 'user', $id);
		}
		return true;
		// return false;
	}

	function findState($zone){
		$this->db->distinct();
        $this->db->select('statename');
        $this->db->where('region',$zone);
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

    function check_store_exist($storeID) {
		$this->db->where('store_uniqueID', $storeID);
		$query = $this->db->get('store_master');
		if( $query->num_rows() > 0 ) {
			return true;
		}
		return false;
	}
	
	function check_sapcode_exist($storeID) {
		$this->db->where('sap_code', $storeID);
		$query = $this->db->get('store_master');
		if( $query->num_rows() > 0 ) {
			return true;
		}
		return false;
	}
	
	function seach_by_sapcode() {
	    
	    $this->db->select('sm.store_name,sm.channel_partner_type,sm.channel_partner_name,sm.address,sm.region,sm.contact_no,sm.contact_person, pv.*');
		$this->db->from('store_master sm');
		$this->db->join('projects_vendor_mapping pv', 'sm.store_uniqueID = pv.store_id');
		
		if( isset($_POST['sap_code']) && !empty($_POST['sap_code'])  ){
			$this->db->where('pv.sap_code', $_POST['sap_code']);
		}
		
		/*$this->db->group_by('pv.project_id');
		$this->db->order_by('sm.id', 'DESC');*/
		$query = $this->db->get();
		if( $query->num_rows() > 0 ){
			$result = $query->row_array();
			return $result;
		}
	    return false;
	    
	}
	
	function get_element_detail_of_project($store_id,$project_id){
		$this->db->select('id,element_type,width,height,area,img1,img2,img3');
		$this->db->where('store_id',$store_id);
		$this->db->where('project_id',$project_id);
		
	    /*$this->db->where('vendor_id',custom_decode($this->uri->segment(4)));
		$this->db->where('added_by', 'vendor');*/
		$query = $this->db->get('element_area');
		if( $query->num_rows() > 0 ){
			$result = $query->result_array();
			return $result;
		}
		return array();
	}
	
	function get_dep_proof_file($project_id, $vendor_id, $store_id) {
		$this->db->where('project_id',$project_id);
	    $this->db->where('vendor_id',$vendor_id);
	    $this->db->where('store_id',$store_id);
	    $this->db->where('type', 'dep_proof');
		$query = $this->db->get('project_supporting_images');
		if( $query->num_rows() > 0 ){
			$result = $query->result_array();
			return $result;
		}
		return array();
	}
	
	
	function upload_stores(){
    	if( !empty($_FILES['file']['name']) ) {
    		$txn = txn_id('UST');
			$extention = get_extention($_FILES['file']);
			$file_name = "Store_".time().'_'.".csv";
			$config = array(
				'upload_path' => STORE_CSV, 
				'allowed_types' => '*',
				'remove_spaces' => TRUE,
				'max_size' => 1024*50,
				'file_name' => $file_name,
			);
			$this->load->library('upload');
			$this->upload->initialize($config);
				if($this->upload->do_upload('file')){
					$file = read_csv(base_url('data/store_csv/').$file_name);
					$count_csv = count($file);
					update_txn($txn["txn_id"], $txn["type"]);
	                upload_logs(base_url('data/store_csv/').$file_name,$count_csv-1,$txn["txn_id"]);
				}else{
					exception($this->session->userdata('user_id'),"",json_encode($_FILES),"File uploading error",$this->session->userdata('role'));
				}
			}
			//debug($file);
			$store_err_tbl = array();
			$store_empty_err=0;
			$error_data=array();
			$ins_arry = array();
			$status='';
			
			for($i = 1; $i <= $count_csv - 1; $i++){
			        
			        /* Validation */
			        $status='';
    			    if(empty($file[$i][0])){
    			        $status='false';
    			        $error_data[]=array(
	                        'error_msg'  => 'Type of Channel Partner is empty',
	                        'sap_code'   => $file[$i][1],
						    'store_name' => $file[$i][2],
						    'tmm_zone'   => $file[$i][5],
						    'Contact_No' => $file[$i][14],
						    'region'     => $file[$i][6],
				            'Pincode'     => $file[$i][13]
	                    );
    			    }
    			    
    			    if(empty($file[$i][1])){
    			        $status='false';
    			        $error_data[]=array(
    			            'error_msg'  => 'SAP Code is empty',
	                        'sap_code'   => $file[$i][1],
						    'store_name' => $file[$i][2],
						    'tmm_zone'   => $file[$i][5],
						    'Contact_No' => $file[$i][14],
						    'region'     => $file[$i][6],
				            'Pincode'     => $file[$i][13]
    			        );
    			    }
    			    
    			    if(empty($file[$i][2])){
    			        $status='false';
    			        $error_data[]=array(
	                        'error_msg' => 'Store Name is empty',
	                        'sap_code'   => $file[$i][1],
						    'store_name' => $file[$i][2],
						    'tmm_zone'   => $file[$i][5],
						    'Contact_No' => $file[$i][14],
						    'region'     => $file[$i][6],
				            'Pincode'     => $file[$i][13]
	                    );
    			    }
    			    
    			    if(empty($file[$i][5])){
    			        $status='false';
    			        $error_data[]=array(
	                        'error_msg' => 'TMM Zone is empty',
	                        'sap_code'   => $file[$i][1],
						    'store_name' => $file[$i][2],
						    'tmm_zone'   => $file[$i][5],
						    'Contact_No' => $file[$i][14],
						    'region'     => $file[$i][6],
				            'Pincode'     => $file[$i][13]
	                    );
    			    }
    			    
    			    if(empty($file[$i][6])){
    			        $status='false';
    			        $error_data[]=array(
	                        'error_msg' => 'Region is empty',
	                        'sap_code'   => $file[$i][1],
						    'store_name' => $file[$i][2],
						    'tmm_zone'   => $file[$i][5],
						    'Contact_No' => $file[$i][14],
						    'region'     => $file[$i][6],
				            'Pincode'     => $file[$i][13]
	                    );
    			    }
    			    
    			    if(empty($file[$i][7])){
    			        $status='false';
    			        $error_data[]=array(
	                        'error_msg' => 'Hub is empty',
	                        'sap_code'   => $file[$i][1],
						    'store_name' => $file[$i][2],
						    'tmm_zone'   => $file[$i][5],
						    'Contact_No' => $file[$i][14],
						    'region'     => $file[$i][6],
				            'Pincode'     => $file[$i][13]
	                    );
    			    }
    			    
    			    if(empty($file[$i][8])){
    			        $status='false';
    			        $error_data[]=array(
	                        'error_msg' => 'Spoke is empty',
	                        'sap_code'   => $file[$i][1],
						    'store_name' => $file[$i][2],
						    'tmm_zone'   => $file[$i][5],
						    'Contact_No' => $file[$i][14],
						    'region'     => $file[$i][6],
				            'Pincode'     => $file[$i][13]
	                    );
    			    }
    			    
    			    if(empty($file[$i][9])){
    			        $status='false';
    			        $error_data[]=array(
	                        'error_msg' => 'Address line 1 is empty',
	                        'sap_code'   => $file[$i][1],
						    'store_name' => $file[$i][2],
						    'tmm_zone'   => $file[$i][5],
						    'Contact_No' => $file[$i][14],
						    'region'     => $file[$i][6],
				            'Pincode'     => $file[$i][13]
	                    );
    			    }
    			    
    			    if(empty($file[$i][13])){
    			        $status='false';
    			        $error_data[]=array(
	                        'error_msg' => 'Pincode  is empty',
	                        'sap_code'   => $file[$i][1],
						    'store_name' => $file[$i][2],
						    'tmm_zone'   => $file[$i][5],
						    'Contact_No' => $file[$i][14],
						    'region'     => $file[$i][6],
				            'Pincode'     => $file[$i][13]
	                    );
    			    }
    			    
    			    if(empty($file[$i][14])){
    			        $status='false';
    			        $error_data[]=array(
	                        'error_msg' => 'Contact No / Mobile   is empty',
	                        'sap_code'   => $file[$i][1],
						    'store_name' => $file[$i][2],
						    'tmm_zone'   => $file[$i][5],
						    'Contact_No' => $file[$i][14],
						    'region'     => $file[$i][6],
				            'Pincode'     => $file[$i][13]
	                    );
    			    }
    			    
    			    if(empty($file[$i][15])){
    			        $status='false';
    			        $error_data[]=array(
	                        'error_msg' => 'EC Status is empty',
	                        'sap_code'   => $file[$i][1],
						    'store_name' => $file[$i][2],
						    'tmm_zone'   => $file[$i][5],
						    'Contact_No' => $file[$i][14],
						    'region'     => $file[$i][6],
				            'Pincode'     => $file[$i][13]
	                    );
    			    }
    			    
    			    if(empty($file[$i][16])){
    			        $status='false';
    			        $error_data[]=array(
	                        'error_msg' => 'Category is empty',
	                        'sap_code'   => $file[$i][1],
						    'store_name' => $file[$i][2],
						    'tmm_zone'   => $file[$i][5],
						    'Contact_No' => $file[$i][14],
						    'region'     => $file[$i][6],
				            'Pincode'     => $file[$i][13]
	                    );
    			    }
    			    
    			    /*if(empty($file[$i][17])){
    			        $status='false';
    			        $error_data[]=array(
	                        'error_msg' => 'Signage Type (1st Intallation) is empty',
	                        'sap_code'   => $file[$i][0],
						    'store_name' => $file[$i][1],
						    'tmm_zone'   => $file[$i][2],
						    'Contact_No' => $file[$i][3],
						    'region'     => $file[$i][4],
				            'state'      => $file[$i][5],
				            'city'       => $file[$i][6]
	                    );
    			    }
    			    
    			    if(empty($file[$i][18])){
    			        $status='false';
    			        $error_data[]=array(
	                        'error_msg' => 'Signage Type (2nd Installation) is empty',
	                        'sap_code'   => $file[$i][0],
						    'store_name' => $file[$i][1],
						    'tmm_zone'   => $file[$i][2],
						    'Contact_No' => $file[$i][3],
						    'region'     => $file[$i][4],
				            'state'      => $file[$i][5],
				            'city'       => $file[$i][6]
	                    );
    			    }*/
    			    
                    /*-----------Check SAPCODE-------------------*/
					$check_storeid = $this->check_sapcode_exist($file[$i][1]);
					if( $check_storeid ){
						$status='false';
					    $error_data[]=array(
	                        'error_msg' => 'SAP CODE is already Exist.',
	                        'sap_code'   => $file[$i][1],
						    'store_name' => $file[$i][2],
						    'tmm_zone'   => $file[$i][5],
						    'Contact_No' => $file[$i][14],
						    'region'     => $file[$i][6],
				            'Pincode'     => $file[$i][13]
	                    );
						//redirect('store/upload_store');
					}
					
					
					if($status !='false') {
        				$ins_arry[] = array(
        					'channel_partner_type'  => $file[$i][0],
        					'sap_code'              => $file[$i][1],
        					'store_name'            => $file[$i][2],
        					'dealer_code'           => $file[$i][3],
        					'dealer_name'           => $file[$i][4],
        					'tmm_zone'              => $file[$i][5],
        					'region'                => $file[$i][6],
        					'hub'                   => $file[$i][7],
        					'spoke'                 => $file[$i][8],
        					'address'               => $file[$i][9],
        					'address2'              => $file[$i][10],
        					'state'                 => $file[$i][11],
        					'city'                  => $file[$i][12],
        					'pincode'               => $file[$i][13],
        					'contact_no'            => $file[$i][14],
        					'ec_status'             => $file[$i][15],
        					'category'              => $file[$i][16],
        					'signage_type_1st_intallation' => $file[$i][17],
        					'1st_installation_date'        => $file[$i][18],
        					'signage_type_2st_intallation' =>$file[$i][19],
        					'2nd_installation_date'        => $file[$i][20],
        					'in_shop_branding_status_1st_installation' => $file[$i][21],
        					'in_shop_branding_1st_date' =>$file[$i][22],
        					'in_shop_branding_status_2st_installation'=> $file[$i][23],
        					'in_shop_branding_2nd_date' => $file[$i][24],
        					'store_uniqueID' => generate_store_code(),
        					'status' =>'Active',
        					'brand' =>(empty(@$this->session->userdata('brand')))?'':@$this->session->userdata('brand'),
        					'master_txn'=>$txn["txn_id"]
        				);
        				//$this->db->insert("store_master",$ins_arry);
        			}
			}
			
			
			/* Exception if any*/
			if(is_array($error_data) && count($error_data) > 0){
                exception($this->session->userdata('user_id'),"",json_encode($error_data),"Error when store upload",$this->session->userdata('role'),$txn['txn_id']);
                $this->session->set_userdata('error_data',$error_data);
			    return "There are some errors in your file,Please remove those first and then try again.!";
            } else {
                /*-----------Insert Batch Query----------------*/
                
                //debug($ins_arry);
    			$this->db->trans_start();
    			$this->db->insert_batch('store_master', $ins_arry);
    			$this->db->trans_complete();
    
    			if( $this->db->trans_status() === TRUE ){
    			   return true; 
    			} else {
    			    return "There are some error Occurred,Please try again";
    			}
            }
    }
    
    function check_storeID($storeId) {
		$this->db->where('store_uniqueID', $storeId);
		$query = $this->db->get('store_master');
		if( $query->num_rows() > 0 ) {
			return true;
		}
		return false;
	}
	
	function check_sapCode($sap_code) {
		$this->db->where('sap_code', $sap_code);
		$query = $this->db->get('store_master');
		if( $query->num_rows() > 0 ) {
			return true;
		}
		return false;
	}
	
	function download_stores(){
	    $this->db->select('*');
	    $this->db->where('brand', $this->session->userdata('brand')); 
        $this->db->from('store_master');
        $this->store_list_role_wise();
        $query = $this->db->get();
        if( $query->num_rows() > 0 ){
            $result = $query->result_array();
            return $result;
        }
        return false;
	}


    function store_list_role_wise(){
        $sess = $_SESSION;
        $role = $sess['role'];
        if($role=='com' || $role=='tmm'){
            $this->db->where('tmm_zone',$sess['zone']);
        }elseif($role=='rsm'){
            $this->db->where('region',$sess['region']);
        }
    }
}