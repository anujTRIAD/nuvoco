<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Site_allocator_model extends CI_Model {

	public function __construct() {
		parent::__construct();
		 error_reporting(E_ALL);
        ini_set('display_errors', '1');
        $this->db->order_by('id','DESC');
	}
	
	function get_data() {
       
		$this->db->select('*');
		$this->db->from('store_master');
		
		if( isset($_POST['region']) && !empty($_POST['region'])  ){
			$this->db->where('region', $_POST['region']);
		}
		
		if( isset($_POST['channel_partner_type']) && !empty($_POST['channel_partner_type'])  ){
			$this->db->where_in('channel_partner_type', $_POST['channel_partner_type']);
		}
		
		if( isset($_POST['ec_status']) && !empty($_POST['ec_status'])  ){
			$this->db->where('ec_status', $_POST['ec_status']);
		}
		
		if( isset($_POST['category']) && count($_POST['category']) >0 ){
			$this->db->where_in('category', $_POST['category']);
		}
		
		if( isset($_POST['tier']) && count($_POST['tier']) > 0 ){
		  
			$this->db->where_in('dealer_tier', @$_POST['tier']);
		}
		
		$this->db->where('status', 'Active'); 
		$this->db->where('brand', $this->session->userdata('brand'));
		
		$this->db->where('signage_type_2st_intallation', '');
		$this->db->where('signage_type_1st_intallation', '');
		
		$this->db->where('in_shop_branding_status_1st_installation ', '');
		$this->db->where('in_shop_branding_status_2st_installation ', '');
		
		$query = $this->db->get();
	    
		
		if( $query->num_rows() > 0 ){
			$result = $query->result_array();
			return $result;
		}
		return false;
	}
	
	
	function single_request_get_data() {

		$this->db->select('*');
		$this->db->from('store_master');
		
		if( isset($_POST['region']) && !empty($_POST['region'])  ){
			$this->db->where('region', $_POST['region']);
		}
		
		if( isset($_POST['channel_partner_type']) && !empty($_POST['channel_partner_type'])  ){
			$this->db->where_in('channel_partner_type', $_POST['channel_partner_type']);
		}
		
		if( isset($_POST['ec_status']) && !empty($_POST['ec_status'])  ){
			$this->db->where('ec_status', $_POST['ec_status']);
		}
		
		if( isset($_POST['category']) && count($_POST['category']) >0 ){
			$this->db->where_in('category', $_POST['category']);
		}
		$this->db->where('brand', $this->session->userdata('brand'));
		$this->db->where('status', 'Active');
		$query = $this->db->get();
		//debug($this->db->last_query(),false);
		if( $query->num_rows() > 0 ){
			$result = $query->result_array();
			return $result;
		}
		return false;
	}
	
	function store_detail($store_id) {

		$this->db->select('*');
		$this->db->from('store_master');
		$this->db->where('store_uniqueID', custom_decode($store_id));
		$query = $this->db->get();
		//debug($this->db->last_query(),false);
		if( $query->num_rows() > 0 ){
			$result = $query->row_array();
			return $result;
		}
		return false;
	}
	
	function load_proposed_singage($type_of_cp,$category,$dealer_tier,$ec_status){
	    $this->db->select('*');
		$this->db->from('branding_rules');  
		$this->db->where('type_of_channel_partner', $type_of_cp);

		if($type_of_cp == 'Dealer'){
		   $this->db->where('ec_status', $ec_status);
		   
		   if($this->session->userdata('brand') == 'SF'){
		       $search  = "FIND_IN_SET('".$dealer_tier."', tier)";
		       $this->db->where($search); 
		   }

		} else if($type_of_cp == 'Garage'){
		    /*$search  = "FIND_IN_SET('".$category."', category)";
		    $this->db->where($search);*/
		} else {
		    $search  = "FIND_IN_SET('".$category."', category)";
		    $this->db->where($search);
		}
		
		$this->db->where('brand', $this->session->userdata('brand'));
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ){
			$result = $query->row_array();
			return $result;
		}
		return false;
	}

	function get_estimated_cost($type_of_cp){
	    $this->db->select('*');
		$this->db->from('estimated_cost_master');  
		$this->db->where('element_id', $type_of_cp);
		$this->db->where('brand', $this->session->userdata('brand'));
		$query = $this->db->get();

		
		if( $query->num_rows() > 0 ){
			$result = $query->row_array();
			return $result;
		}
	}
	
	
    // 	only inshop
    function get_estimated_cost_inshop($type_of_cp){
	    $this->db->select('*');
		$this->db->from('estimated_cost_master');  
		$this->db->where('signage_type', $type_of_cp);
		$this->db->where('type_of_isb','Yes');
		$this->db->where('brand', $this->session->userdata('brand'));
	
		$query = $this->db->get();

		if( $query->num_rows() > 0 ){
			$result = $query->row_array();
			return $result;
		}
	}
	
    function post_single_site_only_inshop() {
    
		$this->load->library('form_validation');   
		$this->form_validation->set_rules('st', 'st', 'trim|required');

		$this->form_validation->set_rules('proposed_isb', 'proposed_isb', 'trim|required');
		$this->form_validation->set_rules('project', 'project', 'trim|required');
		$this->form_validation->set_rules('vendor', 'vendor', 'trim|required');
		
		if ( $this->form_validation->run() == FALSE ) {

			return "Please Fill all Mandatory Fields";

		} else {
		    
            $this->db->select('*');
    		$this->db->from('store_master');
    		$this->db->where('store_uniqueID', custom_decode($this->input->post('st')));
    		$query = $this->db->get();

    		if($query->num_rows() > 0) {
    		    
    			$result =  $query->row_array();

    			    if($this->check_store_aleardy_exist($result['store_uniqueID'],custom_decode($this->input->post('project')))){
    			        return "Sites are already added in Allocation Request.";
    			    }

    			    $vendor = get_row('user', custom_decode($this->input->post('vendor')), 'user_id');
    			   
    		        $project_name = get_value("project_name","projects",custom_decode($this->input->post('project')),"project_id");
    		        
    		        
    		        $this->db->select('id,user_id,role');
                	$this->db->from('user');
                	$this->db->where('region', $result['region']);
                	$this->db->where('role', 'rsm');
                	$this->db->where('brand', $this->session->userdata('brand'));
                	$this->db->where('status', 'Approved');
                	$query1 = $this->db->get();
                	
                	if( $query1->num_rows() > 0 ) {
                		$result1 = $query1->row_array();
                		
                	} else {
                	    $result1['user_id'] = '';
                	}
    		        
    		       
    		        $eestimatd_expence = trim($this->input->post('estimated_cost'));
            		
    			    /*-------------------Check Vendor Rate of Proposed ISB Element -------------------*/
    			    $proposed_isb_element_id ='';
    			    if(trim($this->input->post('proposed_isb')) =='yes'){
    			        $this->db->select('id,element_id');
                		$this->db->from('element_rate_master');
                		$this->db->where('vendor_id',custom_decode(trim($this->input->post('vendor'))));
                		$this->db->where('brand', $this->session->userdata('brand'));
                		$this->db->where('status','Active');
                		$this->db->where('element_type','In Shop Branding');
                		$query = $this->db->get();
                	
                		if( $query->num_rows() == 0 ){
                		    return "Vendor In-shop Branding Element Rate is not found.";
                		}
                		
                		$proposed_isb_element_id = get_value("element_id","element_master",trim($this->input->post('proposed_isb_element')),"element_name");
                		
    			    }
    			    
				     $txn = txn_id("SAR");
	                 $request_id = $txn['txn_id'];
	                 
	                 $element_id = get_value("element_id","element_master",trim($this->input->post('signage_type_1st_intallation')),"element_name");
	                 
	                
	             
	                
    			     $ins_arry = array(
    			        'request_id' => $request_id,
            		    'channel_partner_type' => $result['channel_partner_type'],
            		    'sap_code' => $result['sap_code'],
            		    'channel_partner_name' => $result['channel_partner_name'],
            		    'dealer_code' => $result['dealer_code'],
            		    'dealer_name' => $result['dealer_name'],
            		    'tmm_zone' => $result['tmm_zone'],
            		    'region' => $result['region'],
            		    'hub' => $result['hub'],
            		    'spoke' => $result['spoke'],
            		    'ec_status' => $result['ec_status'],
            		    'category' => $result['category'],
            		    'dealer_tier' => $result['dealer_tier'],
            		    'brand' => $result['brand'],
            		    'signage_type_1st_intallation' => $result['signage_type_1st_intallation'],
            		    '1st_installation_date' => $result['1st_installation_date'],
            		    'signage_type_2st_intallation' => $result['signage_type_2st_intallation'],
            		    '2nd_installation_date' => $result['2nd_installation_date'],
            		    'in_shop_branding_status_1st_installation' => $result['in_shop_branding_status_1st_installation'],
            		    'in_shop_branding_1st_date' => $result['in_shop_branding_1st_date'],
            		    'in_shop_branding_status_2st_installation' => $result['in_shop_branding_status_2st_installation'],
            		    'in_shop_branding_2nd_date' => $result['in_shop_branding_2nd_date'],
            		    
            		    'proposed_signage_id' => '',
            		    'proposed_signage' => '',
            		    'proposed_isb' => $this->input->post('proposed_isb'),
            		    'proposed_isb_element_id' => !empty($proposed_isb_element_id)?$proposed_isb_element_id:'',
            		    'isb_element' => !empty($this->input->post('proposed_isb_element'))?$this->input->post('proposed_isb_element'):'',
            		    'remarks' => $this->input->post('comment'),
            		    //'estimated_expenses' => $estimatd_expence,
            		    
            		    'estimated_expenses' => $this->input->post('estimated_cost'),
            			'store_uniqueID' => $result['store_uniqueID'],
            			'store_name' => $result['store_name'],
            			'address' => $result['address'],
            			'contact_no' =>$result['contact_no'],
            			'address2' => $result['address2'],
            			'pincode' =>$result['pincode'],
            			'allocation_by' => $this->session->userdata('user_id'),
            			'status' =>'Pending',
            			'project' => custom_decode($this->input->post('project')),
            			'project_name' => $project_name,
            			'vendor'  => custom_decode($this->input->post('vendor')),
            			'vendor_name'  => $vendor['name'],
            			'vendor_sapcode' =>$vendor['sap_code'],
            			'rsm_id'  => $result1['user_id'],
            			'date'  => date('Y-m-d'),
            			'master_txn'=>$request_id,
            			'allocation_type' =>'inshop-only'
    		        );
    		        
    		        
    		         

    	        $this->db->trans_start();
    			$this->db->insert('site_allocation_request', $ins_arry);
    			
    			$this->db->trans_complete();
    			
    			
    			if( $this->db->trans_status() === TRUE ){
    			    
    				/*========= Txn History=====*/
        		    update_txn($txn['txn_id'],$txn['type']);
        		    return true;
    			} else {
    			    return "Site Allocated to RSM not added try again";
    			}
    		} else {
    			return "Somthing went wrong,Please try again!!!!!!!!.";
    		}	 
	    }
	}
	
    // end
	function calculate_estimated_cost($type_of_cp,$is_isb,$proposed_isb_element){
	    $this->db->select('*');
		$this->db->from('estimated_cost_master');  
		$this->db->where('signage_type', $proposed_isb_element);
		$this->db->where('type_of_isb', $is_isb);
		$this->db->where('brand', $this->session->userdata('brand'));
		$query = $this->db->get();
		if( $query->num_rows() > 0 ){
			$result = $query->row_array();
			return $result;
		}
	}
	
	function post_single_site() {

		$this->load->library('form_validation');   
		$this->form_validation->set_rules('st', 'st', 'trim|required');
		$this->form_validation->set_rules('proposed_signage', 'proposed_signage', 'trim|required');
		$this->form_validation->set_rules('proposed_isb', 'proposed_isb', 'trim|required');
		$this->form_validation->set_rules('project', 'project', 'trim|required');
		$this->form_validation->set_rules('vendor', 'vendor', 'trim|required');
		
		if ( $this->form_validation->run() == FALSE ) {

			return "Please Fill all Mandatory Fields";

		} else {
		    
            $this->db->select('*');
    		$this->db->from('store_master');
    		$this->db->where('store_uniqueID', custom_decode($this->input->post('st')));
    		$query = $this->db->get();
    		//debug($this->db->last_query());
    	
    		if($query->num_rows() > 0) {
    			$result =  $query->row_array();
    			
    			    /*-------------------Store is already added in site allocation Request-------------------*/
    			    if($this->check_store_aleardy_exist($result['store_uniqueID'],custom_decode($this->input->post('project')))){
    			        return "Site are already added in allocation Request.";
    			    }

    			    $vendor = get_row('user', custom_decode($this->input->post('vendor')), 'user_id');
    		        $project_name = get_value("project_name","projects",custom_decode($this->input->post('project')),"project_id");
    		        
    		        
    		        $this->db->select('id,user_id,role');
                	$this->db->from('user');
                	$this->db->where('region', $result['region']);
                	$this->db->where('role', 'rsm');
                	$this->db->where('brand', $this->session->userdata('brand'));
                	$this->db->where('status', 'Approved');
                	$query1 = $this->db->get();
                	
                	if( $query1->num_rows() > 0 ) {
                		$result1 = $query1->row_array();
                		
                	} else {
                	    $result1['user_id'] = '';
                	}
    		        
    		       
    		        $estimatd_expence=0;
    		        /*if($this->session->userdata('brand') == 'Exide'){
            		    $cost = $this->get_estimated_cost($this->input->post('proposed_signage'));

                		if($this->input->post('proposed_isb') =='Yes'){
                		  $estimatd_expence = $cost['estimated_cost']+$cost['isb_cost'];
                		} else {
                		  $estimatd_expence = $cost['estimated_cost'];
                		}
                		
            		} else {
            		    
            		    $cost = $this->get_estimated_cost($this->input->post('proposed_signage'));
            		    $isb_cost = $this->calculate_estimated_cost($this->input->post('proposed_signage'),$this->input->post('proposed_isb'),$this->input->post('proposed_isb_element'));
            		    $estimatd_expence = $cost['estimated_cost']+$isb_cost['isb_cost'];
            		}*/
            		
            		$cost = $this->get_estimated_cost($this->input->post('proposed_signage'));
                    
                    $estimatd_expence =0;
            		if($this->input->post('proposed_isb') =='Yes'){
            		  $estimatd_expence = $cost['estimated_cost']+$cost['isb_cost'];
            		} else {
            		  $estimatd_expence = $cost['estimated_cost'];
            		}
            		
            		if($estimatd_expence <= 0){
            		    return "Estimated Expenence is not should be 0.";
            		}
            		
            		
            		/*-------------------Check Vendor Rate of Proposed Element (Signage Element)-------------------*/
    			    if($this->check_vendor_element_rate(custom_decode($this->input->post('vendor')),trim($this->input->post('proposed_signage')))){
    			        return "Vendor Element Rate is not found for proposed Signage.";
    			    }
    			    
    			    /*-------------------Check Vendor Rate of Proposed ISB Element -------------------*/
    			    $proposed_isb_element_id ='';
    			    if($this->input->post('proposed_isb') =='Yes'){
    			        $this->db->select('id,element_id');
                		$this->db->from('element_rate_master');
                		$this->db->where('vendor_id',custom_decode($this->input->post('vendor')));
                		$this->db->where('brand', $this->session->userdata('brand'));
                		$this->db->where('status','Active');
                		$this->db->where('element_type','In Shop Branding');
                		$query = $this->db->get();
                		if( $query->num_rows() == 0 ){
                		    return "Vendor In-shop Branding Element Rate is not found.";
                		}
                		
                		$proposed_isb_element_id = get_value("element_id","element_master",$this->input->post('proposed_isb_element'),"element_name");
    			    }
    			    
				     $txn = txn_id("SAR");
	                 $request_id = $txn['txn_id'];
	                 
	                 $element_id = get_value("element_id","element_master",$this->input->post('proposed_signage'),"element_name");
	                 
	               $element_info = element_info($this->input->post('proposed_signage'));
	                $element_id = $element_info[0]['element_id'];
	                $element_name = $element_info[0]['element_name'];
	                 
    			     $ins_arry = array(
    			        'request_id' => $request_id,
            		    'channel_partner_type' => $result['channel_partner_type'],
            		    'sap_code' => $result['sap_code'],
            		    'channel_partner_name' => $result['channel_partner_name'],
            		    'dealer_code' => $result['dealer_code'],
            		    'dealer_name' => $result['dealer_name'],
            		    'tmm_zone' => $result['tmm_zone'],
            		    'region' => $result['region'],
            		    'hub' => $result['hub'],
            		    'spoke' => $result['spoke'],
            		    'ec_status' => $result['ec_status'],
            		    'category' => $result['category'],
            		    'dealer_tier' => $result['dealer_tier'],
            		    'brand' => $result['brand'],
            		    'signage_type_1st_intallation' => $result['signage_type_1st_intallation'],
            		    '1st_installation_date' => $result['1st_installation_date'],
            		    'signage_type_2st_intallation' => $result['signage_type_2st_intallation'],
            		    '2nd_installation_date' => $result['2nd_installation_date'],
            		    'in_shop_branding_status_1st_installation' => $result['in_shop_branding_status_1st_installation'],
            		    'in_shop_branding_1st_date' => $result['in_shop_branding_1st_date'],
            		    'in_shop_branding_status_2st_installation' => $result['in_shop_branding_status_2st_installation'],
            		    'in_shop_branding_2nd_date' => $result['in_shop_branding_2nd_date'],
            		    
            		    'proposed_signage_id' => $element_id,
            		    'proposed_signage' => $element_name,
            		    'proposed_isb' => $this->input->post('proposed_isb'),
            		    'proposed_isb_element_id' => !empty($proposed_isb_element_id)?$proposed_isb_element_id:'',
            		    'isb_element' => !empty($this->input->post('proposed_isb_element'))?$this->input->post('proposed_isb_element'):'',
            		    'remarks' => $this->input->post('comment'),
            		    'estimated_expenses' => $estimatd_expence,
            		    
            			'store_uniqueID' => $result['store_uniqueID'],
            			'store_name' => $result['store_name'],
            			'address' => $result['address'],
            			'contact_no' =>$result['contact_no'],
            			'address2' => $result['address2'],
            			'pincode' =>$result['pincode'],
            			'allocation_by' => $this->session->userdata('user_id'),
            			'status' =>'Pending',
            			'project' => custom_decode($this->input->post('project')),
            			'project_name' => $project_name,
            			'vendor'  => custom_decode($this->input->post('vendor')),
            			'vendor_name'  => $vendor['name'],
            			'vendor_sapcode' =>$vendor['sap_code'],
            			'rsm_id'  => $result1['user_id'],
            			'date'  => date('Y-m-d'),
            			'master_txn'=>$request_id
    		        );
    		        
    		        
    		         

    	        $this->db->trans_start();
    			$this->db->insert('site_allocation_request', $ins_arry);
    			$this->db->trans_complete();
    			
    			//debug($this->db->last_query());
    			if( $this->db->trans_status() === TRUE ){
    			    
    				/*========= Txn History=====*/
        		    update_txn($txn['txn_id'],$txn['type']);
        		    return true;
    			} else {
    			    return "Site Allocated to RSM not added try again";
    			}
    		} else {
    			return "Somthing went wrong,Please try again!!!!!!!!.";
    		}	 
	    }
	}
	
	function check_vendor_element_rate($vendor,$element){
	    $this->db->select('id,element_id');
		$this->db->from('element_rate_master');
		$this->db->where('vendor_id',$vendor);
		$this->db->where('brand', $this->session->userdata('brand'));
		$this->db->where('element_id',$element);
		$this->db->where('status','Active');
		$query = $this->db->get();
		//debug($this->db->last_query());
	
		if( $query->num_rows() > 0 ){
		    return false;
		} else {
		    return true;
		}
	}
	
	function get_dep_proof_file($store_id) {
		/*$this->db->where('project_id',$project_id);
	    $this->db->where('vendor_id',$vendor_id);*/
	    $this->db->where('store_id',custom_decode($store_id));
	    $this->db->where('type', 'dep_proof');
		$query = $this->db->get('project_supporting_images');
		if( $query->num_rows() > 0 ){
			$result = $query->result_array();
			return $result;
		}
		return array();
	}

	function allocated_sites($where=NULL) {
		
        $this->db->select('* , count(id) as total_sites,SUM(estimated_expenses) as total_estimated_expenses, count(IF(status = "pending", id, NULL)) AS pending_sites,count(if(is_delete = "1",id,NULL)) as total_deleted_sites,count(if(allocation_type = "inshop-only",id,NULL)) as total_inshop_sites,count(IF(status = "rejected", id, NULL)) AS rejected_sites');
        if($this->session->userdata('role') == 'tmm'){
            $this->db->where('allocation_by',$this->session->userdata('user_id'));
        }
        $this->db->where('brand', $this->session->userdata('brand'));
        
		if($where!=NULL){
			$this->db->where($where);
		}
        $this->db->group_by('request_id');
        $query = $this->db->get('site_allocation_request');
  
		if( $query->num_rows() > 0 ){
			$result = $query->result_array();
			return $result;
		}
		return false;
	}
	
	function get_regions() {
        $this->db->select('region,region_level');
        $this->db->where('zone',$this->session->userdata('zone'));
        $this->db->where('brand', $this->session->userdata('brand')); 
        
        $query = $this->db->get('region_master');
		if( $query->num_rows() > 0 ){
			$result = $query->result_array();
			return $result;
		}
		return false;
	}
	

	function get_project_details($request_id,$status=NULL){
	    
	   /* $this->db->select('pv.id,pv.store_uniqueID,pv.sap_code,pv.store_name,pv.contact_no,pv.category,pv.brand,pv.channel_partner_type,pv.ec_status,site_allocation_request.status,`site_allocation_request`.`approved_date`');
        $this->db->from('site_allocation_request');
        $this->db->join('store_master pv', 'site_allocation_request.store_uniqueID = pv.store_uniqueID','left');
        $this->db->where('site_allocation_request.project',custom_decode($project_id));
        $query = $this->db->get();
        //debug($this->db->last_query());
        if( $query->num_rows() > 0 ){
            $result = $query->result_array();
            return $result;
        }
        return false;*/
        
        $this->db->select('*');
		$this->db->from('site_allocation_request');
		$this->db->where('request_id',custom_decode($request_id));
		if($status!=NULL){
		    $this->db->where('status',$status);
		}
		
		$query = $this->db->get();
	
		if( $query->num_rows() > 0 ){
			$result = $query->result_array();
			return $result;
		}
	    
	}
	function total_estimated_expense($request_id,$status=NULL){
		$this->db->select('id,SUM(estimated_expenses) as total_expenses');
        $this->db->where('request_id',custom_decode($request_id));
        	if($status!=NULL){
		    $this->db->where('status',$status);
		}
        $query = $this->db->get('site_allocation_request');
        if( $query->num_rows() > 0 ){
			$result = $query->row_array();
			return $result['total_expenses'];
		}
        return 0;
	}
	
	function download_sites($request_id){
	   
        $this->db->select('*');
		$this->db->from('site_allocation_request');
		$this->db->where('brand', $this->session->userdata('brand'));
		$this->db->where('request_id',custom_decode($request_id));
		$query = $this->db->get();
		if( $query->num_rows() > 0 ){
			$result = $query->result_array();
			return $result;
		}
	}
	
	function download_store_master(){

	    $this->db->select('*');
		$this->db->from('store_master');
		
		if( isset($_GET['region']) && !empty($_GET['region'])  ){
			$this->db->where('region', $_GET['region']);
		}
		
		if( isset($_GET['cp_type']) && !empty(@$_GET['cp_type'])  ){
			$this->db->where_in('channel_partner_type', explode(',', @$_GET['cp_type']));
			//$this->db->where_in('channel_partner_type', $_GET['cp_type']);
		}
		
		if( isset($_GET['ec_status']) && !empty($_GET['ec_status'])  ){
			$this->db->where('ec_status', $_GET['ec_status']);
		}
		
		if( isset($_GET['category']) && !empty($_GET['category']) ){
			$this->db->where_in('category', explode(',', $_GET['category']));
		}
		
		if( isset($_GET['tier']) && !empty($_GET['tier'])){
			$this->db->where_in('dealer_tier', explode(',', @$_GET['tier']));
		}
		
		$this->db->where('brand', $this->session->userdata('brand')); 
		$this->db->where('status', 'Active');

		$this->db->where('signage_type_2st_intallation', '');
		$this->db->where('signage_type_1st_intallation', '');
		
		$this->db->where('in_shop_branding_status_1st_installation ', '');
		$this->db->where('in_shop_branding_status_2st_installation ', '');
 
		$query = $this->db->get();

		if( $query->num_rows() > 0 ){
			$result = $query->result_array();
			return $result;
		}
		return false;
	}
	
	function send_to_rsm() {
		$txn = txn_id('AST');
		//debug($_POST);
		
		$select_all = $this->input->post('select_all');
	    $request_id = $this->input->post('request_id');
	    
	    if(count($request_id) ==0){
	        return "Please Select Site First.";
	    }
	    
        $this->db->select('*');
		$this->db->from('store_master');
		$this->db->where_in('id', $request_id);
		$query = $this->db->get();
	
		if($query->num_rows() > 0) {
			$row =  $query->result_array();
			
			foreach($row as $result) {
			    
			    /*---------Store is already added in site allocation Request-------------------*/
			    
			    if($this->check_store_aleardy_exist($result['store_uniqueID'],$this->input->post('project') )){
			        return "Site are already added in allocation Request.";
			    }
			    
			    $vendor = get_row('user', $this->input->post('vendor'), 'user_id');
		        $project_name = get_value("project_name","projects",$this->input->post('project'),"project_id");
		        
		        $this->db->select('id,user_id,role');
            	$this->db->from('user');
            	$this->db->where('region', $result['region']);
            	$this->db->where('role', 'rsm');
            	$this->db->where('status', 'Approved');
            	$query1 = $this->db->get();
            	if( $query1->num_rows() > 0 ) {
            		$result1 = $query1->row_array();
            	} else {
            	    return "System not found any RSM in selected Region.";
            	}
		        
		        //debug($result1['user_id']);
		        
			     $ins_arry[] = array(
        		    'channel_partner_type' => $result['channel_partner_type'],
        		    'sap_code' => $result['sap_code'],
        		    'channel_partner_name' => $result['channel_partner_name'],
        		    'dealer_code' => $result['dealer_code'],
        		    'dealer_name' => $result['dealer_name'],
        		    'tmm_zone' => $result['tmm_zone'],
        		    'region' => $result['region'],
        		    'hub' => $result['hub'],
        		    'spoke' => $result['spoke'],
        		    'ec_status' => $result['ec_status'],
        		    'category' => $result['category'],
        		    'brand' => $result['brand'],
        		    'signage_type_1st_intallation' => $result['signage_type_1st_intallation'],
        		    '1st_installation_date' => $result['1st_installation_date'],
        		    'signage_type_2st_intallation' => $result['signage_type_2st_intallation'],
        		    '2nd_installation_date' => $result['2nd_installation_date'],
        		    'in_shop_branding_status_1st_installation' => $result['in_shop_branding_status_1st_installation'],
        		    'in_shop_branding_1st_date' => $result['in_shop_branding_1st_date'],
        		    'in_shop_branding_status_2st_installation' => $result['in_shop_branding_status_2st_installation'],
        		    'in_shop_branding_2nd_date' => $result['in_shop_branding_2nd_date'],
        			'store_uniqueID' => $result['store_uniqueID'],
        			'store_name' => $result['store_name'],
        			'address' => $result['address'],
        			'contact_no' =>$result['contact_no'],
        			'address2' => $result['address2'],
        			'pincode' =>$result['pincode'],
        			'allocation_by' => $this->session->userdata('user_id'),
        			'status' =>'Pending',
        			'project' => $this->input->post('project'),
        			'project_name' => $project_name,
        			'vendor'  => $this->input->post('vendor'),
        			'vendor_name'  => $vendor['name'],
        			'vendor_sapcode'  => $vendor['sap_code'],
        			'rsm_id'  => $result1['user_id'],
        			'date'  => date('Y-m-d'),
		        );
	        } 
	        
	        $this->db->trans_start();
			$this->db->insert_batch('site_allocation_request', $ins_arry);
			$this->db->trans_complete();
			
			//debug($this->db->last_query());
			if( $this->db->trans_status() === TRUE ){
			    
				/*========= Txn History=====*/
    		    //update_txn($txn['txn_id'],$txn['type']);
    		    return true;
			} else {
			    return "Site Allocated to RSM not added try again";
			}
		} else {
			return "Somthing went wrong,Please try again.";
		}	        
	}
	
	function check_store_aleardy_exist($store_id,$project_id){
	    $this->db->select('id,store_uniqueID');
        $this->db->from('site_allocation_request');
        $this->db->where_in('store_uniqueID', $store_id);
        $this->db->where_in('project', $project_id);
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
			return true;
		} else {
		    return false;
		}
	}
	
	function check_vendor_rate($vendor) {
	    $this->db->select('id');
		$this->db->where('vendor_id', $vendor);
		$this->db->where('status', 'Active');
		$this->db->where('brand', $this->session->userdata('brand'));
		$query = $this->db->get('element_rate_master');
		if( $query->num_rows() > 0 ) {
			return false;
		}
		return true;
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
	
    function check_storeID($storeId) {
		$this->db->where('store_uniqueID', $storeId);
		$query = $this->db->get('store_master');
		if( $query->num_rows() > 0 ) {
			return true;
		}
		return false;
	}
	
	function upload_sites(){
	    //debug($_FILES,false);
	   
	    $this->load->library('form_validation');   

		$this->form_validation->set_rules('project', 'project', 'trim|required');
		$this->form_validation->set_rules('region', 'region', 'trim|required');
		$this->form_validation->set_rules('vendor', 'vendor', 'trim|required');
	
		if ( $this->form_validation->run() == FALSE ) {
        
			return "Please Fill all Mandatory Fields";

		} else {
	      
        	if( !empty($_FILES['file']['name']) ) {
        	    
            		$txn = txn_id('UST');
        			//$extention = get_extention($_FILES['file']);
        
        			$file_name = "site_allocator_".time().'_'.".csv";
        			$config = array(
        				'upload_path' => SITE_ALLOCATOR, 
        				'allowed_types' => '*',
        				'remove_spaces' => TRUE,
        				'max_size' => 1024*50,
        				'file_name' => $file_name,
        			);
        		
        			$this->load->library('upload');
        			$this->upload->initialize($config);
        			if($this->upload->do_upload('file')){
        				$file = read_csv(base_url('data/bulk_site_allocator_csv/').$file_name);
        				$count_csv = count($file);
        				
        				$this->session->set_userdata('file', $file_name);
        				
        				update_txn($txn["txn_id"], $txn["type"]);
                        upload_logs(base_url('data/bulk_site_allocator_csv/').$file_name,$count_csv-1,$txn["txn_id"]);
        			}else{
        				exception($this->session->userdata('user_id'),"",json_encode($_FILES),"File uploading error",$this->session->userdata('role'));
        			}
    			}
    
    			$store_err_tbl = array();
    			$store_empty_err=0;
    			$error_data=array();
    			$ins_arry = array();
    			$status ='';
    			
    			for($i = 1; $i <= $count_csv - 1; $i++){
    			    
    			        $sap_code               = $file[$i][0];
    			        $element_id_cell        = $file[$i][1];
    			        $Proposed_Signage_Type  = $file[$i][2];
    			        $Proposed_isb           = $file[$i][3];
    			        $store_name             = $file[$i][4];
        			    $zone                   = $file[$i][5];
        			    $region                 = $file[$i][6];
    			        $add1                   = $file[$i][7];
    			        $add2                   = $file[$i][8];
    			        $category               = $file[$i][9];
    			        $type_of_chennal        = $file[$i][10];
    			        $status                 = $file[$i][11];
    			        $exist_signage_1        = $file[$i][12];
    			        $exist_signage_2        = $file[$i][13];
    			        
    			    
    			        //debug($file[$i],false);
    
    			        /* Validation */
    			        
    			        $error_array = array(
    	                        'error_msg'  => 'Store SapCode is empty',
    	                        'sap_code'   => $sap_code,
    	                        'store_name'   => $store_name,
    						    'Proposed_Signage_Type' => $Proposed_Signage_Type,
    						    'Proposed_isb'  => $Proposed_isb,
    						    'zone' => $zone,
    						    'region' => $region,
    	                    );
    	                    
    			        $status='';
    			        
    			        if(empty($element_id_cell)){
        			        $status='false';
        			        $error_array['error_msg'] = 'Element Id is empty';
        			        $error_data[]= $error_array;
        			    }
        			    
        			    if(empty($sap_code)){
        			        $status='false';
        			        $error_array['error_msg'] = 'Store SapCode is empty';
        			        $error_data[]= $error_array;
        			    }
        			    
        			    if(empty(trim($Proposed_Signage_Type))){
        			        $status='false';
        			         $error_array['error_msg'] = 'Proposed  Signage Type is empty';
        			        $error_data[]= $error_array;
        			    }
        			    
        			    if(empty($Proposed_isb)){
        			        $status='false';
    	                     $error_array['error_msg'] = 'Proposed In shop Branding is empty';
        			        $error_data[]= $error_array;
        			    }
       			  
       			  
                        //=======================Checks========================
    				
    					
    					/*--------------Store has already installed Signage----------------------*/
    					$store_detail = get_row('store_master', $sap_code, 'sap_code');
    					if(!$store_detail){
    					    $status='false';
    					  
    	                    $error_array['error_msg'] = 'SapCode is already Exist.';
        			        $error_data[]= $error_array;
    					}
    					
    					if($store_detail['brand'] != $this->session->userdata('brand')){
    						$status='false';
    					   
    	                     $error_array['error_msg'] = "Store is not of '".$this->session->userdata('brand')."' Brand";
        			        $error_data[]= $error_array;
    					}
    					
    					if( (!empty($store_detail['signage_type_1st_intallation']) || !empty($store_detail['signage_type_2st_intallation'])) ){
    						$status='false';
    					   
    	                    $error_array['error_msg'] ='Store has already installed Signage.';
        			        $error_data[]= $error_array;
    					}
    					
    					/*--------------Check Store Region or Selected Region should be Same----------------------*/
    					if($store_detail['region'] != $this->input->post('region')){
    					    $status='false';
    					  
    	                     $error_array['error_msg'] ='Store has Different Region as you selected.';
        			        $error_data[]= $error_array;
    					}

    					/*--------------Check Proposed Signage depands on given Branding Rules----------------------*/
    					if(!empty(trim($Proposed_Signage_Type))){
    					    
    					    $load_proposed_list= $this->site_allocator_model->load_proposed_singage($store_detail['channel_partner_type'],$store_detail['category'],trim($store_detail['dealer_tier']),$store_detail['ec_status']);
    					  
                		    $stimated_expence =0;
                	        if(count($load_proposed_list) > 0){
                	            
                			    $signage_list = explode(",",$load_proposed_list['element_id']);
                			    $singnate_type_list = explode(" | ",$load_proposed_list['signage_type']);
                			   
			                    $elementInfo = element_info($signage_list);
			                    
                			    $isb_list    = explode(",",trim($load_proposed_list['type_of_isb']));
                			    
                			   
                			    //debug($signage_list,false);
                			    
                			    if(!in_array(trim($element_id_cell),$signage_list)){
                			        $status='false';
            					   
            	                     $error_array['error_msg'] ='Wrong Element Id.';
        			                $error_data[]= $error_array;
                			    }
                			    
                			    if(!in_array(trim($Proposed_Signage_Type),$singnate_type_list)){
                			        $status='false';
            					   
            	                     $error_array['error_msg'] ='Wrong Proposed Signage Type!';
        			                $error_data[]= $error_array;
                			    } 
                			    
                			    $name2 = $this->db->select('element_name')->from('element_master')->where('element_id',trim($element_id_cell))->get()->row_array();
                			   
                			   if(trim($name2['element_name'])!=trim($Proposed_Signage_Type)){
                			       $status='false';
            					   
            	                     $error_array['error_msg'] ='Wrong Proposed Signage Type!';
        			                $error_data[]= $error_array;
                			   }
                			   

                			    if(!in_array(trim($Proposed_isb),$isb_list)){
                			        $status='false';
            					   
            	                    $error_array['error_msg'] ='Wrong Proposed In shop Branding.';
        			                $error_data[]= $error_array;
                			    }
                			    
                			   
                			}
    					}
    					
    				
    					if($this->check_store_aleardy_exist($store_detail['store_uniqueID'],$this->input->post('project'))){
    
    			            $status='false';
    					   
    	                    $error_array['error_msg'] ='Site is already added in allocation Request.';
        			        $error_data[]= $error_array;
    			        }
    
                        /*--------------Check RSM in Selected Region-----------------*/
    					$this->db->select('id,user_id,role');
                    	$this->db->from('user');
                    	$this->db->where('region', $store_detail['region']);
                    	$this->db->where('role', 'rsm');
                    	$this->db->where('status', 'Approved');
                    	$query1 = $this->db->get();
                    	if( $query1->num_rows() > 0 ) {
                    		$result1 = $query1->row_array();
                    	} else {
                    	    $status='false';
    					   
    	                     $error_array['error_msg'] ='System not found any RSM in selected Region.';
        			        $error_data[]= $error_array;
                    	}
                    
                    	
                    	/*-------------------Check Vendor Rate of Proposed Element (Signage Element)-------------------*/
                    	$vendor = get_row('user', $this->input->post('vendor'), 'user_id');
                    	
        			    if($this->check_vendor_element_rate($vendor['user_id'],trim($element_id_cell))){

        			        $status='false';
    					  
    	                    $error_array['error_msg'] ='Vendor Element Rate is not found.';
        			        $error_data[]= $error_array;
        			    }
                    	
    					
    					$estimated_cost=0;
    					if($status !='false') {  
    					    
    					    $project_name = get_value("project_name","projects",$this->input->post('project'),"project_id");
    					    $vendor = get_row('user', $this->input->post('vendor'), 'user_id');
    					    
    					    $cost = $this->site_allocator_model->get_estimated_cost(trim($element_id_cell));
    					    
    					    if($Proposed_isb =='Yes'){
                    		  $estimated_cost = $cost['estimated_cost']+$cost['isb_cost'];
                    		} else {
                    		  $estimated_cost = $cost['estimated_cost'];
                    		}
    					    
    					   
    					    
    					    if($estimated_cost == 0){
    					        $status='false';
        					  
        	                     $error_array['error_msg'] ='Estimated Expenses cant be 0.';
        			        $error_data[]= $error_array;
    					    }
    					    
    					   // $element_id = get_value("element_id","element_master",trim($Proposed_Signage_Type),"element_name");
    					    
    					    $element_id = $element_id_cell;
    					    
    					    $proposed_isb_element_id ='';
    					    if(trim($Proposed_isb) == 'Yes'){
    					        $proposed_isb_element_id = get_value("element_id","element_master",trim($Proposed_isb),"element_name");
    					    }
    					    
    					    
    					    $txn = txn_id("SAR");
	                        $request_id = $txn['txn_id'];
	                        	
            				$ins_arry[] = array(
            				    'request_id' => $request_id,
                    		    'channel_partner_type' => $store_detail['channel_partner_type'],
                    		    'sap_code' => $store_detail['sap_code'],
                    		    'channel_partner_name' => $store_detail['channel_partner_name'],
                    		    'dealer_code' => $store_detail['dealer_code'],
                    		    'dealer_name' => $store_detail['dealer_name'],
                    		    'tmm_zone' => $store_detail['tmm_zone'],
                    		    'region' => $store_detail['region'],
                    		    'hub' => $store_detail['hub'],
                    		    'spoke' => $store_detail['spoke'],
                    		    'ec_status' => $store_detail['ec_status'],
                    		    'category' => $store_detail['category'],
                    		    'brand' => $store_detail['brand'],
                    		    'signage_type_1st_intallation' => $store_detail['signage_type_1st_intallation'],
                    		    '1st_installation_date' => $store_detail['1st_installation_date'],
                    		    'signage_type_2st_intallation' => $store_detail['signage_type_2st_intallation'],
                    		    '2nd_installation_date' => $store_detail['2nd_installation_date'],
                    		    'in_shop_branding_status_1st_installation' => $store_detail['in_shop_branding_status_1st_installation'],
                    		    'in_shop_branding_1st_date' => $store_detail['in_shop_branding_1st_date'],
                    		    'in_shop_branding_status_2st_installation' => $store_detail['in_shop_branding_status_2st_installation'],
                    		    'in_shop_branding_2nd_date' => $store_detail['in_shop_branding_2nd_date'],
                    		    
                    		    'proposed_signage_id' =>$element_id,
                    		    'proposed_signage' => trim($Proposed_Signage_Type),
                    		    'proposed_isb' => trim($Proposed_isb),
                    		    'proposed_isb_element_id' => $proposed_isb_element_id,
                    		    //'isb_element' => trim($file[$i][3]),
                    		    'remarks' => "",
                    		    'estimated_expenses' => $estimated_cost,
                    		    
                    			'store_uniqueID' => $store_detail['store_uniqueID'],
                    			'store_name' => $store_detail['store_name'],
                    			'address' => $store_detail['address'],
                    			'contact_no' =>$store_detail['contact_no'],
                    			'address2' => $store_detail['address2'],
                    			'pincode' =>$store_detail['pincode'],
                    			'allocation_by' => $this->session->userdata('user_id'),
                    			'status' =>'Pending',
                    			'project' => $this->input->post('project'),
                    			'project_name' => $project_name,
                    			'vendor'  => $vendor['user_id'],
                    			'vendor_name'  => $vendor['name'],
                    			'vendor_sapcode' => $vendor['sap_code'],
                    			'rsm_id'  => $result1['user_id'],
                    			'date'  => date('Y-m-d'),
                    			'master_txn'=> $request_id
        		            );
            			}
    			}
    			
    		
    			/* Exception if any*/
    			if(is_array($error_data) && count($error_data) > 0){
                    exception($this->session->userdata('user_id'),"",json_encode($error_data),"Error when store upload",$this->session->userdata('role'),$txn['txn_id']);
    			    return $error_data;
                } else {
                    
                    /*-----------Insert Batch Query----------------*/
        			$this->db->trans_start();
        			$this->db->insert_batch('site_allocation_request', $ins_arry);
        			$this->db->trans_complete();
        
        			if( $this->db->trans_status() === TRUE ){
        			   update_txn($txn['txn_id'],$txn['type']);
        			   return true; 
        			} else {
        			    return "There are some error Occurred,Please try again";
        			}
                }
	    }
    }
    
    function check_sapcode_exist($sapcode) {
        $this->db->select('id');
		$this->db->where('sap_code', $sapcode);
		$query = $this->db->get('store_master');
		if( $query->num_rows() > 0 ) {
			return false;
		}
		return true;
	}
	
	function check_sapCode($sap_code) {
		$this->db->where('sap_code', $sap_code);
		$query = $this->db->get('store_master');
		if( $query->num_rows() > 0 ) {
			return true;
		}
		return false;
	}
	
	
	function get_category(){
		if( isset($_POST['partner_name']) && !empty($_POST['partner_name']) ){
			if($_POST['partner_name'] != 'all') {
				if( is_array($_POST['partner_name']) ){
					$this->db->where_in('type_of_cp', $_POST['partner_name']);
				} else {
					$this->db->where('type_of_cp', $_POST['partner_name']);
				}
			}
			
			$this->db->distinct();
    		$this->db->select('category,dealer_tier,type');
    		$this->db->where('brand', $this->session->userdata('brand')); 
    		$this->db->order_by('category','asc'); 
    		//$this->db->where('category !=', '');
    		$query = $this->db->get('categories');
    	
    		return $query->result_array();
    		
		} else {
		    return array();
		}
		
	}

    function get_status(){
        $this->db->select('status');
        $this->db->from('site_allocation_request');
        $this->db->group_by('status');
        $result = $this->db->get();
        if( $result->num_rows() > 0 ) {
			return $result->result_array();
		}
		return [];
    }
    
// 	bulk upload only ISB start**********
    function upload_sites_only_ISB(){
    
    $this->load->library('form_validation');   

    $this->form_validation->set_rules('project', 'project', 'trim|required');
    $this->form_validation->set_rules('region', 'region', 'trim|required');
    $this->form_validation->set_rules('vendor', 'vendor', 'trim|required');

        if ( $this->form_validation->run() == FALSE ) {
            
          return "Please Fill all Mandatory Fields";
        
        } else {
            
              if( !empty($_FILES['file']['name']) ) {
                  
                    $txn = txn_id('UST');
                  //$extention = get_extention($_FILES['file']);
            
                  $file_name = "site_allocator_only_isb_".time().'_'.".csv";
                  $config = array(
                    'upload_path' => SITE_ALLOCATOR, 
                    'allowed_types' => '*',
                    'remove_spaces' => TRUE,
                    'max_size' => 1024*50,
                    'file_name' => $file_name,
                  );
                
                  $this->load->library('upload');
                  $this->upload->initialize($config);
                  if($this->upload->do_upload('file')){
                    $file = read_csv(base_url('data/bulk_site_allocator_csv/').$file_name);
                    $count_csv = count($file);
                    
                    $this->session->set_userdata('file', $file_name);
                    
                    update_txn($txn["txn_id"], $txn["type"]);
                            upload_logs(base_url('data/bulk_site_allocator_csv/').$file_name,$count_csv-1,$txn["txn_id"]);
                  }else{
                    exception($this->session->userdata('user_id'),"",json_encode($_FILES),"File uploading error",$this->session->userdata('role'));
                  }
              }
        
              $store_err_tbl = array();
              $store_empty_err=0;
              $error_data=array();
              $ins_arry = array();
              $status ='';
              
              for($i = 1; $i <= $count_csv - 1; $i++){
                  
                      
        
                      $sap_code           = $file[$i][0];
                      $isb                = $file[$i][1];
                      $store_name         = $file[$i][2];
                      $ase_code           = $file[$i][3];
                      $rsm_name           = $file[$i][4];
                      $zone               = $file[$i][5];
                      $region             = $file[$i][6];
                      $add1               = $file[$i][7];
                      $add2               = $file[$i][8];
                      $category           = $file[$i][9];
                      $chennal_partner    = $file[$i][10];
                      $status             = $file[$i][11];
                    
        
                      /* Validation */
                      
                      $err_array = array(
                            'sap_code'   => $sap_code,
                            'store_name'   => $store_name,
                            'Proposed_isb'  => $isb,
                            'zone' => $zone,
                            'region' => $region,
                        );
                       
                      
                      $status='';
                      if(empty($sap_code)){
                          $status='false';
                          $err_array['error_msg'] = 'Store SapCode is empty';
                          $error_data[]= $err_array;
                      }
                       
                      
                      if(empty($isb)){
                          $status='false';
                          $err_array['error_msg'] = 'Proposed In shop Branding is empty';
                          $error_data[]= $err_array;
                         
                      }
                     
                   
                            //=======================Checks========================
                  $check_store = $this->check_sapcode_exist($sap_code);
                  
                  if( $check_store ){
                    $status='false';
                    $err_array['error_msg'] = 'SapCode is already Exist.';
                    $error_data[]= $err_array;
                  }
                  
                  
                  /*--------------Store has already installed Signage----------------------*/
                  $store_detail = get_row('store_master', $sap_code, 'sap_code');
                 
                  if($store_detail['brand'] != $this->session->userdata('brand')){
                    $status='false';
                    $err_array['error_msg'] = "Store is not of '".$this->session->userdata('brand')."' Brand";
                    $error_data[]= $err_array;
                  }
                   
                  
                  if( (!empty($store_detail['signage_type_1st_intallation']) || !empty($store_detail['signage_type_2st_intallation'])) ){
                    $status='false';
                    $err_array['error_msg'] = 'Store has already installed Signage.';
                    $error_data[]= $err_array;
                  }
                  
                  /*--------------Check Store Region or Selected Region should be Same----------------------*/
                  if($store_detail['region'] != $this->input->post('region')){
                      $status='false';
                      $err_array['error_msg'] =  'Store has Different Region as you selected.';
                     $error_data[]= $err_array;
                  }
        
                  /*--------------Check Proposed Signage depands on given Branding Rules----------------------*/
               
                
                  if($this->check_store_aleardy_exist($store_detail['store_uniqueID'],$this->input->post('project'))){
        
                          $status='false';
                          $err_array['error_msg'] =  'Site is already added in allocation Request.';
                         $error_data[]= $err_array;
                      }
        
         
                            /*--------------Check RSM in Selected Region-----------------*/
                  $this->db->select('id,user_id,role');
                          $this->db->from('user');
                          $this->db->where('region', $store_detail['region']);
                          $this->db->where('role', 'rsm');
                          $this->db->where('status', 'Approved');
                          $query1 = $this->db->get();
                          if( $query1->num_rows() > 0 ) {
                            $result1 = $query1->row_array();
                          } else {
                              $status='false';
                               $err_array['error_msg'] =  'System not found any RSM in selected Region.';
                             $error_data[]= $err_array;
                          }
                      
                          /*-------------------Check Vendor Rate of Proposed Element (Signage Element)-------------------*/
                          $vendor = get_row('user', $this->input->post('vendor'), 'user_id');
                          
                  $estimated_cost=0;
                  if($status !='false') {
                      $where =  $rule = array();
                     
                              array_walk($store_detail, create_function('&$val','$val = trim($val);'));
                              if($store_detail['brand']=='Exide' ){
                              if($store_detail['channel_partner_type']=='Dealer'){
                                $where = array(
                                  'type_of_channel_partner'=>$store_detail['channel_partner_type'],
                                  'ec_status'=>$store_detail['ec_status'],
                                  'brand'=>$store_detail['brand']
                                ); 
                              }elseif($store_detail['channel_partner_type']=='Sub Dealer'){
                                $where = array(
                                  'type_of_channel_partner'=>$store_detail['channel_partner_type'],
                                  'brand'=>$store_detail['brand']
                                );
                                  $this->db->like('category',$store_detail['category']);
            
                              }elseif($store_detail['channel_partner_type']=='Garage'){
                              $where = array(
                              'type_of_channel_partner'=>$store_detail['channel_partner_type'],
                              'ec_status'=>$store_detail['ec_status'],
                              'brand'=>$store_detail['brand']
                              );
                                  }
                                }elseif($store_detail['brand']=='Dynex'){
                                  $rule = array();
                                }elseif($store_detail['brand']=='SF'){
             
                               if($store_detail['channel_partner_type']=='Dealer'){
                                 $where = array(
                                  'type_of_channel_partner'=>$store_detail['channel_partner_type'],
                                  'tier'=>$store_detail['dealer_tier'],
                                  'brand'=>$store_detail['brand'],
                                  'ec_status'=>$store_detail['ec_status']
                                ); 
                               }elseif($store_detail['channel_partner_type']=='Registered Retailer'){
               
                            $where = array(
                              'type_of_channel_partner'=>$store_detail['channel_partner_type'],
                              'brand'=>$store_detail['brand'],
                            ); 
                            $this->db->like('category',$store_detail['category']);
                             }
          
                          }
                                $this->db->where($where);
                                $rule = $this->db->get('branding_rules')->row_array();
        
                              
                            
                                   
                                   if($rule['type_of_isb']=='No'){
                                        $status='false';
                                        $err_array['error_msg'] =  'NO IN SHOP BRANDING REQUEST ALLOWED.';
                                        $error_data[]= $err_array;
                                    }elseif($rule['type_of_isb']=='No,Yes'){
                                                 if($rule['brand']=='Exide'){
                                           if($rule['type_of_channel_partner']=='Garage' ){
                                               $estimated_cost = 15000;
                                           }else{
                                               $estimated_cost = 20000;
                                           }
                                        }elseif($rule['brand']=='SF'){
                                            if($rule['type_of_channel_partner']=='Dealer' ){
                                                
                                                if($rule['tier']=='Authorized Distributor'){
                                                    if($rule['ec_status']=="Yes"){
                                                        $estimated_cost = 9100;  
                                                    }else{
                                                        $estimated_cost = 45000;  
                                                    }
                                                }elseif($rule['tier']=='Direct Dealer'){
                                                    if($rule['ec_status']=="No"){
                                                        $estimated_cost = 45000;  
                                                    }else{
                                                        $estimated_cost = 0;  
                                                    }
                                                }
                                            }elseif($rule['type_of_channel_partner']=='Registered Retailer'){
                                                $cat_arr = explode(",",$rule['category']);
                                              
                                                if( in_array("A",$cat_arr) || in_array("B",$cat_arr) || in_array("C",$cat_arr) || in_array("E",$cat_arr) || empty($cat_arr) || in_array("N",$cat_arr) ){
                                                  $estimated_cost = 45000;
                                             }elseif(in_array("D",$cat_arr)){
                                                    $estimated_cost = 33000;
                                             }
                                            }
                                        }
                                    }
                            
                     
                                
                      
                      $project_name = get_value("project_name","projects",$this->input->post('project'),"project_id");
                      $vendor = get_row('user', $this->input->post('vendor'), 'user_id');
                    
                      if($estimated_cost == 0){
                          $status='false';
                                  $err_array['error_msg'] =  'Estimated Expenses cant be 0.';
                                  $error_data[]= $err_array;
                      }
                      
                      
                      $proposed_isb_element_id ='';
                      if(trim($isb) == 'Yes'){
                          $proposed_isb_element_id = get_value("element_id","element_master",trim($isb),"element_name");
                      }else{
                           $status='false';
                            $err_array['error_msg'] =  'NO IN SHOP BRANDING REQUEST ALLOWED.';
                            $error_data[]= $err_array;
                      }
                      
                      
                      $txn = txn_id("SAR");
                              $request_id = $txn['txn_id'];
                                
                        $ins_arry[] = array(
                            'request_id' => $request_id,
                                'channel_partner_type' => $store_detail['channel_partner_type'],
                                'sap_code' => $store_detail['sap_code'],
                                'channel_partner_name' => $store_detail['channel_partner_name'],
                                'dealer_code' => $store_detail['dealer_code'],
                                'dealer_name' => $store_detail['dealer_name'],
                                'tmm_zone' => $store_detail['tmm_zone'],
                                'region' => $store_detail['region'],
                                'hub' => $store_detail['hub'],
                                'spoke' => $store_detail['spoke'],
                                'ec_status' => $store_detail['ec_status'],
                                'category' => $store_detail['category'],
                                'dealer_tier' => $store_detail['dealer_tier'],
                                'brand' => $store_detail['brand'],
                                'signage_type_1st_intallation' => $store_detail['signage_type_1st_intallation'],
                                '1st_installation_date' => $store_detail['1st_installation_date'],
                                'signage_type_2st_intallation' => $store_detail['signage_type_2st_intallation'],
                                '2nd_installation_date' => $store_detail['2nd_installation_date'],
                                'in_shop_branding_status_1st_installation' => $store_detail['in_shop_branding_status_1st_installation'],
                                'in_shop_branding_1st_date' => $store_detail['in_shop_branding_1st_date'],
                                'in_shop_branding_status_2st_installation' => $store_detail['in_shop_branding_status_2st_installation'],
                                'in_shop_branding_2nd_date' => $store_detail['in_shop_branding_2nd_date'],
                                'proposed_signage_id' =>'',
                                'proposed_signage' => '',
                                'proposed_isb' => trim($isb),
                                'proposed_isb_element_id' => $proposed_isb_element_id,
                                'remarks' => "",
                                'estimated_expenses' => $estimated_cost,
                                
                              'store_uniqueID' => $store_detail['store_uniqueID'],
                              'store_name' => $store_detail['store_name'],
                              'address' => $store_detail['address'],
                              'contact_no' =>$store_detail['contact_no'],
                              'address2' => $store_detail['address2'],
                              'pincode' =>$store_detail['pincode'],
                              'allocation_by' => $this->session->userdata('user_id'),
                              'status' =>'Pending',
                              'project' => $this->input->post('project'),
                              'project_name' => $project_name,
                              'vendor'  => $vendor['user_id'],
                              'vendor_name'  => $vendor['name'],
                              'vendor_sapcode' => $vendor['sap_code'],
                              'rsm_id'  => $result1['user_id'],
                              'date'  => date('Y-m-d'),
                              'master_txn'=> $request_id,
                              'allocation_type' =>'inshop-only'
                            );
                      }
              }
              
            
              /* Exception if any*/
              if(is_array($error_data) && count($error_data) > 0){
                        exception($this->session->userdata('user_id'),"",json_encode($error_data),"Error when store upload",$this->session->userdata('role'),$txn['txn_id']);
                  return $error_data;
                    } else {
                        
                        /*-----------Insert Batch Query----------------*/
                  $this->db->trans_start();
                  $this->db->insert_batch('site_allocation_request', $ins_arry);
                  $this->db->trans_complete();
            
                  if( $this->db->trans_status() === TRUE ){
                     update_txn($txn['txn_id'],$txn['type']);
                     return true; 
                  } else {
                      return "There are some error Occurred,Please try again";
                  }
                    }
          }
    }
// 	bulk upload only ISB end***************

function site_allocation_count2($where=NULL){
        $this->db->select('count(id) as site_count');
		$this->db->from('site_allocation_request');
	     $role = $this->session->userdata('role');
		if($role == 'rsm'){
		    $this->db->where('region',$this->session->userdata('region'));
		}elseif($role=='tmm'|| $role=='com'){
		    $this->db->where('tmm_zone', $this->session->userdata('zone'));
		}
		$this->db->where('brand',$this->session->userdata('brand'));
		if($where!=NULL){
		$this->db->where($where);
		}
		$this->db->where('status!=','Re-Allocated');
		$query = $this->db->get();
        // echo $this->db->last_query();die;
		if( $query->num_rows() > 0 ){
			$result = $query->row_array();
			return $result['site_count'];
		}
		return 0;
    }
    
//  exception site allocation work
    function load_proposed_singage_exception($type_of_cp){
	    $this->db->select('*');
		$this->db->from('exception_branding_rules');  
		$this->db->where('type_of_channel_partner', $type_of_cp);

		$this->db->where('brand', $this->session->userdata('brand'));
		$query = $this->db->get();
		//query();
		if( $query->num_rows() > 0 ){
			$result = $query->row_array();
			return $result;
		}
		return false;
	}
//  exceotuion site allocation work end
}