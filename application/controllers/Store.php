<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Store extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('store_model');
		is_first_time_login($this->session->userdata('is_first_login'));
		
		ini_set('memory_limit', '-1');
	
		/*error_reporting(E_ALL);
        ini_set('display_errors', '1');*/
	}

	function index() {
		//can_access('admin','cmm','tmm_national','nsh','com','rsm','tmm');
		$includes = array('datatable');
		$data['inclusions'] = inclusions($includes);
		$data['page_title'] = "Store Master";
		//$data['stores'] = $this->store_model->store();
		load_page('store/index', $data);
	}
	
	function store_upload_log() {
		can_access('admin','cmm','tmm_national','nsh','com','rsm','tmm');
		$includes = array('datatable');
		$data['inclusions'] = inclusions($includes);
		$data['page_title'] = "Upload logs";
		$data['upload_log_data'] = $this->store_model->upload_logs_data();
		load_page('store/store_upload_log_view', $data);
	}
	
	function upload_csv_view()
	{							 
		can_access('admin','cmm','tmm_national','nsh','com','rsm','tmm');
		$includes = array('datatable', 'datepicker', 'fancybox', 'chosen');
		$data['inclusions'] = inclusions($includes);
		$data['page_title'] = "Upload CSV";
		
		load_page('store/upload_csv_view', $data);
		 		 
	}
	
		function upload_csv()
	{
		
		 $file_name = $_FILES['csv_file']['name']; 
		 $file_tmp = $_FILES['csv_file']['tmp_name'];
		
		ini_set("memory_limit", "-1");
		set_time_limit(0);
				
		error_reporting(E_ALL);		
		
		$insertdata=array();
		
	    $time_start = microtime(true);
	    $cron_status = 'Not Start';
		
		$row = 1;
		if (($handle = fopen($file_tmp, "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000000000)) !== FALSE) {
				
					$insertdata[] = $data;
				
				$row++;
				
			}
			fclose($handle);
		}
		
	    //  echo "<pre>"; print_r($insertdata); die; echo "<br>";
					
	        $count_csv = count($insertdata); 
								
			$data_arr = array();
			$insert = array();	
					
			$this->db->select('*');
			$this->db->from('store_master');
			$user_data = $this->db->get()->result_array();
			
			// echo "<pre>"; print_r($user_data); die;
			
			$sap_code=array();
			$user_name=array();
			
			foreach($user_data as $val)
			{
				$sap_code[]=$val['sap_code'];
			//	$user_name[]=$val['username'];
			}
						
						
		     
		//	echo $this->db->last_query();
		//	die;
			
			
			for($i = 1; $i < $count_csv; $i++) 
			{				
				
				// echo "<pre>"; print_r($insertdata[$i]); die;
				    $this->db->select('*');
    		    	$this->db->from('store_master');
    		    	$this->db->order_by('id','desc');
    		    	$this->db->limit(1);
    		    	$datas = $this->db->get();			
    				$last_store_uniqueID = $datas->row_array();		
    		    	$unique_ids = $last_store_uniqueID['store_uniqueID'];
    		
    			if( $datas->num_rows() > 0 ){
    	            $get_num = substr($unique_ids, strpos($unique_ids, "_") + 1);   
    
                    $num_inc = (int)$get_num;
                     $new_num = $num_inc+$i;
                    
                    $new_storeId = "STR".$new_num."_".$new_num;
            	} else {
    	        	$new_storeId = "STR1";
            	}
			
				 $data_arr=array(
			     'store_uniqueID' => $new_storeId,
				 'store_name' => $insertdata[$i][0],
				 'sap_code' => $insertdata[$i][1],
				 'channel_partner_type' => $insertdata[$i][2],
				 'channel_partner_name' => $insertdata[$i][3],
				 'dealer_code' => $insertdata[$i][4],
				 'dealer_name' => $insertdata[$i][5],
				 'tmm_zone' => $insertdata[$i][6],
				 'store_description' => $insertdata[$i][7],
				 'store_image' => $insertdata[$i][8],				 
				 'address' => $insertdata[$i][9],
				 'state' => $insertdata[$i][10],
				 'city' => $insertdata[$i][11],
				 'region' => $insertdata[$i][12],
				 'hub' => $insertdata[$i][13],
				 'spoke' => $insertdata[$i][14],
				 'ec_status' => $insertdata[$i][15],
				 'category' => $insertdata[$i][16],
				 'brand' => $insertdata[$i][17],
				 'ase_position_code' => $insertdata[$i][18],
				 'pincode' => $insertdata[$i][19],
				 'contact_person' => $insertdata[$i][20],
				 'contact_no' => $insertdata[$i][21],
				 'created_at' => $insertdata[$i][22],
				 'address2' => $insertdata[$i][23],
				 'status' => $insertdata[$i][24],
				 'store_valuation' => $insertdata[$i][25],
				 'master_txn' => $insertdata[$i][26],
				 'dealer_key' => $insertdata[$i][27],
				 'sales_organization' => $insertdata[$i][28],
				 'distribution_channel' => $insertdata[$i][29],
				 'division' => $insertdata[$i][30],
				 'parent' => $insertdata[$i][31],
				 'central_order_block' => $insertdata[$i][32],
				 'sales_office' => $insertdata[$i][33],
				 'customer_group' => $insertdata[$i][34],
				 'central_billing_block' => $insertdata[$i][35],
				 'industry_code_desc1' => $insertdata[$i][36],	
				 'industry_code_desc2' => $insertdata[$i][37],
				 'dealer_segregation' => $insertdata[$i][38],
				 'dealer_location' => $insertdata[$i][39],
				 'changed_on' => $insertdata[$i][40],
				 'consumption_center' => $insertdata[$i][41],
				 'latitude' => $insertdata[$i][42],
				 'longitude' => $insertdata[$i][43],
				 'customer_group2_description' => $insertdata[$i][44],
				 'account_group' => $insertdata[$i][45],
				 'central_delivery_block' => $insertdata[$i][46],
				 'dealer_tier' => $insertdata[$i][47],
				 'upload_date' => date('Y-m-d'),
				 
				 );
				
			//	$this->db->insert("store_master",$data_arr);
			$error_data='';
				if(in_array($insertdata[$i][1],$sap_code))
				{	
				   
							// $error_data[]= $insertdata[$i][1];
										
				} 
				elseif($insertdata[$i][1] == '') {
					
				}
				else {
				
					    array_push($insert,$data_arr);
				}
							
			
			}
			
		//	  echo "<pre>"; print_r($insert); die;
			/*
				if(is_array($insert) && count($insert) > 0){
					   // insert values in Temp Store Master
						$this->db->trans_start();
						$this->db->insert_batch('user' , $insert);
						$this->db->trans_complete();
					
					
					}
					*/
					$this->session->set_userdata('insert_csv',$insert);
					
					
					if(count($insert)>0)
					{
						$val_num=1;
						
						$inv_csv = '<div class="box">
		
			<div class="box-body">
			<div class="col-md-12 table-responsive">
				<table id="users_table" tbl-data="success_show" class="table table-striped table-bordered" data-page-length="10" style="margin-top:15px;">
					<thead>
						<tr>
							<th>Store name </th>
							<th>Sap code</th>
							<th>Channel partner type</th>
							<th>Channel partner name</th>
							<th>Dealer code</th>
							<th>Dealer name</th>
							<th>Tmm zone</th>
							<th>Store description</th>	
							<th>Store image</th>
							<th>Address</th>					
							<th>State</th>
							<th>State</th>
							<th>Region</th>
							<th>Hub</th>
							<th>Spoke</th>
							<th>Ec status</th>
							<th>Category</th>
							<th>Brand</th>
							<th>Ase position code</th>
							<th>Pincode</th>
							<th>Contact person</th>
							<th>Contact no</th>
							<th>Created at</th>
							<th>Address2</th>
							<th>Status</th>
							<th>Store valuation</th>
							<th>Master txn</th>
							<th>Dealer key</th>
							<th>Sales organization</th>
							<th>Distribution channel</th>
							<th>Division</th>
							<th>Parent</th>
							<th>Central order block</th>
							<th>Sales office</th>
							<th>Customer group</th>
							<th>Central billing block</th>
							<th>Industry code desc1</th>
							<th>Industry code desc2</th>
							<th>Dealer segregation</th>
							<th>Dealer location</th>
							<th>Changed on</th>
							<th>Consumption center</th>
							<th>Latitude</th>
							<th>Longitude</th>
							<th>Customer group2 description</th>
							<th>Account group</th>
							<th>Central delivery block</th>
							<th>Dealer tier</th>
						</tr>
					</thead>';
					$inv_csv .='<tbody>';
					
						foreach($insert as $val){
						
						$inv_csv .='<tr>
							<td>'.$val['store_name'].'</td>
							<td>'.$val['sap_code'].'</td>
							<td>'.$val['channel_partner_type'].'</td>
							<td>'.$val['channel_partner_name'].'</td>
							<td>'.$val['dealer_code'].'</td>
							<td>'.$val['dealer_name'].'</td>
							<td>'.$val['tmm_zone'].'</td>
							<td>'.$val['store_description'].'</td>
							<td>'.$val['store_image'].'</td>
							<td>'.$val['address'].'</td>
							<td>'.$val['state'].'</td>
							<td>'.$val['city'].'</td>
							<td>'.$val['region'].'</td>
							<td>'.$val['hub'].'</td>
							<td>'.$val['spoke'].'</td>
							<td>'.$val['ec_status'].'</td>
							<td>'.$val['category'].'</td>
							<td>'.$val['brand'].'</td>
							<td>'.$val['ase_position_code'].'</td>
							<td>'.$val['pincode'].'</td>
							<td>'.$val['contact_person'].'</td>
							<td>'.$val['contact_no'].'</td>
							<td>'.$val['created_at'].'</td>
							<td>'.$val['address2'].'</td>
							<td>'.$val['status'].'</td>
							<td>'.$val['store_valuation'].'</td>
							<td>'.$val['master_txn'].'</td>
							<td>'.$val['dealer_key'].'</td>
							<td>'.$val['sales_organization'].'</td>
							<td>'.$val['distribution_channel'].'</td>
							<td>'.$val['division'].'</td>
							<td>'.$val['parent'].'</td>
							<td>'.$val['central_order_block'].'</td>
							<td>'.$val['sales_office'].'</td>
							<td>'.$val['customer_group'].'</td>
							<td>'.$val['central_billing_block'].'</td>
							<td>'.$val['industry_code_desc1'].'</td>
							<td>'.$val['industry_code_desc2'].'</td>
							<td>'.$val['dealer_segregation'].'</td>
							<td>'.$val['dealer_location'].'</td>
							<td>'.$val['changed_on'].'</td>
							<td>'.$val['consumption_center'].'</td>
							<td>'.$val['latitude'].'</td>
							<td>'.$val['longitude'].'</td>
							<td>'.$val['customer_group2_description'].'</td>
							<td>'.$val['account_group'].'</td>
							<td>'.$val['central_delivery_block'].'</td>
							<td>'.$val['dealer_tier'].'</td>
						</tr>';
						}
						
					
					$inv_csv .='</tbody>
				</table>
			</div>	
			</div>
		</div>';
				
						
					} else {
						$val_num='';
						$inv_csv = '<div class="box">
		
			<div class="box-body">
			<div class="col-md-12 table-responsive">
				<table id="users_table" tbl-data="error_show" class="table table-striped table-bordered" data-page-length="10" style="margin-top:15px;">
					<thead>
						<tr>
							<th>Store name </th>
							<th>Sap code</th>
							<th>Channel partner type</th>
							<th>Channel partner name</th>
							<th>Dealer code</th>
							<th>Dealer name</th>
							<th>Tmm zone</th>
							<th>Store description</th>	
							<th>Store image</th>
							<th>Address</th>					
							<th>State</th>
							<th>State</th>
							<th>Region</th>
							<th>Hub</th>
							<th>Spoke</th>
							<th>Ec status</th>
							<th>Category</th>
							<th>Brand</th>
							<th>Ase position code</th>
							<th>Pincode</th>
							<th>Contact person</th>
							<th>Contact no</th>
							<th>Created at</th>
							<th>Address2</th>
							<th>Status</th>
							<th>Store valuation</th>
							<th>Master txn</th>
							<th>Dealer key</th>
							<th>Sales organization</th>
							<th>Distribution channel</th>
							<th>Division</th>
							<th>Parent</th>
							<th>Central order block</th>
							<th>Sales office</th>
							<th>Customer group</th>
							<th>Central billing block</th>
							<th>Industry code desc1</th>
							<th>Industry code desc2</th>
							<th>Dealer segregation</th>
							<th>Dealer location</th>
							<th>Changed on</th>
							<th>Consumption center</th>
							<th>Latitude</th>
							<th>Longitude</th>
							<th>Customer group2 description</th>
							<th>Account group</th>
							<th>Central delivery block</th>
							<th>Dealer tier</th>
						</tr>
					</thead>
					<tbody>
						
						<tr>
							<td colspan="48" style="color:red; text-align:center;"> UPLOADING CSV FAILED. PLEASE TRY AGAIN!</td>
										
						</tr>
					
					</tbody>
				</table>
			</div>	
			</div>
		</div>';
		
			}
						
		echo $inv_csv;
								
	}
	
		function upload_data() {		 
	
			if(isset($_POST['submit'])) {
				
			$insert = $this->session->userdata('insert_csv');
			
		//	echo "<pre>"; print_r($insert); die;
					
			 if(is_array($insert) && count($insert) > 0){
			   // insert values in Temp Store Master
				$this->db->trans_start();
				$this->db->insert_batch('store_master' , $insert);
				$this->db->trans_complete();
				$val_num=1;		
			 }			
						
			 if($val_num == 1){
			     
			     
			     $txn = txn_id('SMA');
		
            	$count_csv = count($insert);
            	
            	$file_name = "store_".time().'_'.".csv";
            	upload_logs(base_url('data/store_csv/').$file_name,$count_csv,$txn["txn_id"]);
            	
				$this->session->unset_userdata('insert_csv');				
				  
				set_flashdata('message', 'Store data upload  successfully', 'success');
				redirect('store/upload_csv_view');
			
			} else {
				set_flashdata('message', 'Store data upload failed, please try again !', 'error');
				redirect('store/upload_csv_view');
			}
			
			}
		 
	 }
	 
	 
	 
	 function master_uploader() { 
		//can_access('admin', 'manager');
     //   function_access(); 
        can_access('admin','cmm','tmm_national','nsh','com','rsm','tmm');
		@unlink(FCPATH.'data/temp/master_upload/'.$this->session->userdata('file')); 
		$includes = array('select', 'dropzone');
		$data['inclusions'] = inclusions($includes);
		$data['page_title'] = "Master Uploader";
		// echo "<pre>"; print_r($data); die;
		$data['get_store_master'] = $this->store_model->get_store_master();
		load_page('store/master_uploader', $data);
	}
	
	 function get_temp_url(){

		@unlink(FCPATH.'data/temp/master_upload/'.$this->session->userdata('file'));

		$file_unique_id = 'master_upload_temp_'.random_code().'_'.time();
		if( !empty($_FILES['file']['name']) ) {
				
			$extention = get_extention($_FILES['file']);
			$file_name = $file_unique_id.".csv";
			$config = array(
				'upload_path' => FCPATH.'data/temp/master_upload',
				'allowed_types' => '*',
				'remove_spaces' => TRUE,
				'max_size' => 1024*50,
				'file_name' => $file_name,
			);
			$this->load->library('upload');
			$this->upload->initialize($config);
			$this->upload->do_upload('file');
		}
		echo $file_name;
		$this->session->set_userdata('file', $file_name);
	}
	 
	function upload(){
		$file = base_url('data/temp/master_upload/').$this->input->post('file'); 
		$includes = array('select', 'dropzone');
		$data['inclusions'] = inclusions($includes);
		$data['page_title'] = 'Master Uploader ( Checking )';

		$data['file'] = $file;
	//	$data['role'] = $this->input->post('role');
		$data['column'] = $this->input->post('column');
		
	//	 echo "<pre>"; print_r($data['column']); die;
		load_page('store/master_uploader_check_file', $data); 

	}
	
	function submit(){
		$file = $this->input->post('file');
	//	$role = $this->input->post('role');
		 $num_records = $this->input->post('num_records');
		
		 $column = json_decode($this->input->post('column'), true);

		if( !empty($file) ){ //&& !empty($selling_to)
			$submit = $this->store_model->submit_file($file,$column);
			if($submit === true ){ 
			  //  echo "hellso"; die;
			    //========== Upload file log & s3 =============
			    $filedata = FCPATH.'data/temp/master_upload/'.$this->session->userdata('file');
			    $s3file = "data/store_csv/".$this->session->userdata('file');
			    $this->load->helper('s3');
			    
			    $file_link = s3_upload($filedata, $s3file, 'application/csv');
			    
			    // echo $file_link; die;
			    
			    $txn = txn_id('SMA');
		
            //	$count_csv = count($insert);
            	
            	$file_name = "store_".time().'_'.".csv";
            	upload_logs($file_link,$num_records,$txn["txn_id"]);
            	
			//	$this->session->unset_userdata('insert_csv');
			    
			  //  upload_file_log($file_link, 'Master Uploader', $num_records);
			    //=============================================

				@unlink(FCPATH.'data/temp/master_upload/'.$this->session->userdata('file'));
				set_flashdata('message', 'File Successfully Uploaded', 'success');
				redirect('store/master_uploader');
				
			} 
			else if($submit ==='empty') {
			    
				set_flashdata('message', 'Some column are empty, Error while submitting', 'error');
				redirect('store/master_uploader');
			}
			else {
			    
				set_flashdata('message', 'Some column not matched, Error while submitting', 'error');
				redirect('store/master_uploader');
			}
		} else {
			set_flashdata('message', 'Not uploaded! Resubmit your file.', 'error');
			redirect('store/master_uploader');
		}
	}
	
	

	function add_store() {
		can_access('admin','cmm','tmm_national');
		if( isset($_POST['submit']) ) {
		    
			//debug($_POST);
			
			$store_exist = $this->store_model->check_store_exist($this->input->post('store_uniqueID'));
			if($store_exist){
				exception($this->session->userdata('user_id'),"",json_encode($_POST),"Store already exist !",$this->session->userdata('role'));
				set_flashdata('message', 'Store already exist !', 'danger');
				redirect('store/add_store');
			} else {
				$txn = txn_id('AST');
				$add_store = $this->store_model->add_store();
				
				if( $add_store ) {
					update_txn($txn["txn_id"], $txn["type"]);
					set_flashdata('message', 'Store added successsfully', 'success');
					redirect('store/thank_you');
				} else {
					exception($this->session->userdata('user_id'),"",json_encode($_POST),"Store not added try again !",$this->session->userdata('role'));
					set_flashdata('message', 'Store not added try again !', 'error');
					redirect('store/add_store');
				}
			}
			
		} else {
			$includes = array('validation', 'datepicker', 'fancybox' , 'chosen');
			$data['inclusions'] = inclusions($includes);
			$data['page_title'] = "Add Store";
			$data['zones']  = $this->store_model->get_zone();
			//debug($this->db->last_query());
			
			//$data['regions'] = get_table("regions","Active","status");
			//$data['zone'] = get_table_condition("citylist"," GROUP BY region");
			/*$data['hubs'] = get_table("hub","Active","status");
			$data['spokes'] = get_table("spoke","Active","status");*/
			
			load_page('store/add',$data);
		}
	}

	function edit_store($id) {
		can_access('admin','cmm','tmm_national');
		if( isset($_POST['submit']) ) {	                                                                      
			/*========= Txn History=====*/
			$txn = txn_id('EST');
			update_txn($txn["txn_id"], $txn["type"]);		
			$edit_store = $this->store_model->edit_store($id);
			if( $edit_store ) {
				set_flashdata('message', 'Store edited successsfully', 'success');
				redirect('store/edit_store/'.$id);
			} else {
				exception($this->session->userdata('user_id'),"",json_encode($_POST),"Store not updated try again",$this->session->userdata('role'));
				set_flashdata('message', 'Store not edited try again !', 'error');
				redirect('store/edit_store/'.$id);
			}			
		} else {
			$includes = array('validation', 'datepicker', 'fancybox' , 'chosen');
			$data['inclusions'] = inclusions($includes);
			$data['page_title'] = "Edit Store";
			$data['zone'] = get_table_condition("citylist"," GROUP BY region");
			$data['stores'] = $this->store_model->store(custom_decode($id));
			load_page('store/edit',$data);
		}
	}

	public function loadState(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
        	$zone=$this->input->post('zone');
        	$result = $this->users_model->findState($zone);
        if($result)
        {
           echo json_encode($result);
        } else {
            echo json_encode(TRUE);
        } 
    }
	
	public function loadCity(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
        	$state=$this->input->post('state');
        	$result = $this->users_model->findCity($state);
        if($result)
        {
           echo json_encode($result);
        } else {
            echo json_encode(TRUE);
        } 

    }

    function status() {
		$id = custom_decode($this->input->post('id'));
        $value = $this->input->post('value');
        $response = set_value('status', $value, 'store_master', $id);
        echo $response;
	}

	function thank_you(){
		$includes = array('datatable', 'datepicker');
		$data['inclusions'] = inclusions($includes);
		$data['page_title'] = "Thank You";	
		load_page('store/thank-you', $data);
	}
	
	function check_storeId(){
		$store_id = $this->input->post('store_uniqueID');
		$uniquecode = $this->store_model->check_store_exist($store_id);
		if( $uniquecode ) {
			echo "false";
		} else {
			echo "true";
		}
	}
	
	function check_sapCode(){
		$sap_code = $this->input->post('sap_code');
		$uniquecode = $this->store_model->check_sapcode_exist($sap_code);
		if( $uniquecode ) {
			echo "false";
		} else {
			echo "true";
		}
	}

    /*-----------Search Store by SAP Code-------------*/
    function search() {
		can_access('admin','cmm','tmm_national');
		$includes = array('datatable','multiselect');
		$data['inclusions'] = inclusions($includes);
		$data['page_title'] = "Store Search";
		
		$data['dep_prf'] = $this->store_model->seach_by_sapcode();
		
		//debug($data['dep_prf']);

		$data['project_element'] = $this->store_model->get_element_detail_of_project($data['dep_prf']['store_id'],$data['dep_prf']['project_id']);
		
		//debug($this->db->last_query());
		
		$data['dep_proof_file']  = $this->store_model->get_dep_proof_file($data['dep_prf']['project_id'],$data['dep_prf']['vendor_1'], $data['dep_prf']['store_id']);
		
		
		
		load_page('store/store_search_by_sapcode', $data);
	}
	

	public function upload_store(){
    	can_access('admin','cmm','tmm_national');
		if( isset($_POST['submit']) ) {
		    
		    //debug($_FILES);
			$upload_user = $this->store_model->upload_stores();
			//return $upload_user;
			if( $upload_user === true) {
				set_flashdata('message', 'Store Uploaded successsfully', 'success');
				
				redirect('store/upload_store/');
			} else {
				exception($this->session->userdata('user_id'),"",json_encode($_FILES),"Query Error found while uploading store",$this->session->userdata('role'));
				set_flashdata('message', $upload_user, 'error');
				redirect('store/upload_store/');
			}
		} else {
			$includes = array('validate', 'datepicker', 'fancybox');
			$data['inclusions'] = inclusions($includes);
			$data['page_title'] = "Upload Store";
			load_page('store/upload_store', $data);
		}
    }
    
    public function store_json_admin(){
        $store_info = $this->store_model->store();
        if(is_array($store_info) && count($store_info)){
        foreach($store_info as $store){
            $store_data[] = [
                'id'=>custom_encode($store['id']),
                //'store_id'=>$store['store_uniqueID'],
                'store_name'=>$store['store_name'],
                'sap_code' => $store['sap_code'],
                'category'=>$store['category'],
                'dealer_tier'=>$store['dealer_tier'],
                'ec_status'=>$store['ec_status'],
                'tmm_zone'=>$store['tmm_zone'],
                'channel_partner_type' => $store['channel_partner_type'],
                'contact_no'=>$store['contact_no'],
                'region'=>$store['region'],
                'status'=>$store['status'],
                'brand' => $store['brand'],
        		'signage_type_1st_intallation' => ($store['signage_type_1st_intallation']!='')?"Yes":"No",
        		'1st_installation_date' => $store['1st_installation_date'],
        		'signage_type_2st_intallation' => ($store['signage_type_2st_intallation']!="")?"Yes":"No",
        		'2nd_installation_date' => $store['2nd_installation_date'],
        		'in_shop_branding_status_1st_installation' => ($store['in_shop_branding_status_1st_installation']!="")?"Yes":"No",
        		'in_shop_branding_1st_date' => $store['in_shop_branding_1st_date'],
        		'in_shop_branding_status_2st_installation' => ($store['in_shop_branding_status_2st_installation']!="")?"Yes":"No",
        		'in_shop_branding_2nd_date' => $store['in_shop_branding_2nd_date']
                ];
        }
            echo json_encode(array("data"=>$store_data));
        }else{
           echo json_encode(array("data"=>[])); 
        }
        
    }
    
    
	function download_stores(){
	    $transaction = $this->store_model->download_stores();

	    foreach($transaction as $value){
	        
	        if($this->session->userdata('brand') == 'Exide'){
	   
        	        $response[] = [
        	            'Channel Partner Type' => $value['channel_partner_type'],
        	            'Channel Partner Name' =>$value['channel_partner_name'],
        	            'SAP Code' =>$value['sap_code'],
        	            'Store Name' => $value['store_name'],
        	            'Dealer Code' => $value['dealer_code'],
        				'Dealer Name' => $value['dealer_name'],
        				'Zone'=>$value['tmm_zone'],
        				'Region' => $value['region'],
        				'Hub' => $value['hub'],
        				'Spoke' => $value['spoke'],
        				'EC Status' => $value['ec_status'],
        				'Tier' => $value['dealer_tier'],
        				'Category' => $value['category'],
        				'Contact No' => $value['contact_no'],
        				'Address' => $value['address'],
        				'Pincode' => $value['pincode'],
        				'ASE Position Code' => $value['ase_position_code'],
        				'Signage Type 1st Intallation' => ($value['signage_type_1st_intallation']!="")?"Yes":"No",
        				'1st Installation Date' => $value['1st_installation_date'],
        				'Signage type 2st Intallation' => ($value['signage_type_2st_intallation']!="")?"Yes":"No",
        				'2nd Installation date' => $value['2nd_installation_date'],
        				'In shop Branding Status 1st Installation' => ($value['in_shop_branding_status_1st_installation']!="")?"Yes":"No",
        				'In shop branding 1st date' => $value['in_shop_branding_1st_date'],
        				'In shop Branding Status 2st Installation' => ($value['in_shop_branding_status_2st_installation']!="")?"Yes":"No",
        				'In Shop Branding 2nd Date' => $value['in_shop_branding_2nd_date'],
        			
        			];
        			
	        } else if($this->session->userdata('brand') == 'SF'){
	            
	            $response[] = [
        	            'Channel Partner Type' => $value['channel_partner_type'],
        	            'Channel Partner Name' =>$value['channel_partner_name'],
        	            'SAP Code' =>$value['sap_code'],
        	            'Store Name' => $value['store_name'],
        	            'Dealer Code' => $value['dealer_code'],
        				'Dealer Name' => $value['dealer_name'],
        				'Zone'=>$value['tmm_zone'],
        				'Region' => $value['region'],
        				'Hub' => $value['hub'],
        				'Spoke' => $value['spoke'],
        				'PBO Status' => $value['ec_status'],
        				'Tier' => $value['dealer_tier'],
        				'Category' => $value['category'],
        				'Contact No' => $value['contact_no'],
        				'Address' => $value['address'],
        				'Pincode' => $value['pincode'],
        				'ASE Position Code' => $value['ase_position_code']
        			];
	            
	        }else if($this->session->userdata('brand') == 'Dynex'){
	            
	            $response[] = [
					'Channel Partner Type' => $value['channel_partner_type'],
        	            'Channel Partner Name' =>$value['channel_partner_name'],
        	            'SAP Code' =>$value['sap_code'],
        	            'Store Name' => $value['store_name'],
        	            'Dealer Code' => $value['dealer_code'],
        				'Dealer Name' => $value['dealer_name'],
        				'Zone'=>$value['tmm_zone'],
        				'Region' => $value['region'],
        				'Hub' => $value['hub'],
        				'Spoke' => $value['spoke'],
        				'EC Status' => $value['ec_status'],
        				'Tier' => $value['dealer_tier'],
        				'Category' => $value['category'],
        				'Contact No' => $value['contact_no'],
        				'Address' => $value['address'],
        				'Pincode' => $value['pincode'],
        				'ASE Position Code' => $value['ase_position_code'],
				];
	            
	        }
	    }
	    outputCsv('store_list.csv',$response);
	}
    

}