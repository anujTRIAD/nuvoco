<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}
	
	
	function get_projects(){
	    $this->db->select('DISTINCT(projects.id),projects.project_id,projects.project_name');
	    
    	if($this->session->userdata('role') != 'admin') {
    	    
    	    $this->db->where('projects.brand', $this->session->userdata('brand')); 
    	}
	    
	    
	    if($this->session->userdata('role') == 'vendor') {
	        $this->db->join('projects_vendor_mapping pv','pv.project_id = projects.project_id');
    		$this->db->where('pv.vendor_1', $this->session->userdata('user_id'));
    		
		} else if($this->session->userdata('role') =='tmm' || $this->session->userdata('role') =='com'){
		    
		   $this->db->where('projects.project_zone', $this->session->userdata('zone')); 
		} else if($this->session->userdata('role') =='rsm'){
		    $this->db->where('projects.project_region', $this->session->userdata('region'));  
		}
	    $this->db->where('projects.status', 'Active');
		$query = $this->db->get('projects');
	    
		if( $query->num_rows() > 0 ){
			$result = $query->result_array();
			return $result;
		}
		return false;
	}
	

	function stacked_chart_data($project_id){
		$data = array(
			'total_store' => get_count('projects_vendor_mapping', 'project_id', $project_id),
			'vendors' => array(),
		);

		$this->db->select('vendor_1');
		$this->db->where('project_id', $project_id);
		$this->db->group_by('vendor_1');
		$query = $this->db->get('projects_vendor_mapping');
		if( $query->num_rows() > 0 ){
			$result = $query->result_array();

			foreach ($result as $value) {
				$pending = $recce_approval_pending = $site_cancelled = $po_pending = $deployment_pending = $deployed = 0;

				$this->db->select('status');
				$this->db->where('project_id', $project_id);	
				$this->db->where('vendor_1', $value['vendor_1']);
				//$this->db->group_by('status');	
				$query1 = $this->db->get('projects_vendor_mapping');
				$result1 = $query1->result_array();
				foreach ($result1 as $value1) {
					
					$val = get_value('graph_status', 'project_status', $value1['status'], 'status_id');
					if( $val == 'Recce Pending' ){
						$pending = $pending+1;
					}
					if( $val == 'Recce Approval Pending' ){
						$recce_approval_pending = $recce_approval_pending+1;
					}
					if( $val == 'Cancelled' ){
						$site_cancelled = $site_cancelled+1;
					}
					if( $val == 'PO Pending' ){
						$po_pending = $po_pending+1;
					}
					if( $val == 'Deployment Pending' ){
						$deployment_pending = $deployment_pending+1;
					}
					if( $val == 'Deployed' ){
						$deployed = $deployed+1;
					}
				}

				$data_array['vendor_name'] = get_value('name', 'user', $value['vendor_1'], 'user_id');
				$data_array['total_store'] = get_count('projects_vendor_mapping', 'project_id', $project_id, 'vendor_1', $value['vendor_1']);
				$data_array['pending'] = $pending;
				$data_array['recce_approval_pending'] = $recce_approval_pending;
				$data_array['site_cancelled'] = $site_cancelled;
				$data_array['po_pending'] = $po_pending;
				$data_array['deployment_pending'] = $deployment_pending;
				$data_array['deployed'] = $deployed;

				$main_array[] = $data_array;
			}
			
			//debug($main_array);

			$data['vendors'] = $main_array;
			return $data;
		} else {
			return $data;
		}
	}
	
	function pie_chart_data($project_id=NULL){
	   
	
	    if($project_id!=NULL){
	        $data = array(
			'budgeted' => get_value('budgeted_amount', 'projects', $project_id, 'project_id'),
			'incurred' => 0,
		); 
		
    		$this->db->select('sum(pre_value) as incurred_expenses');
    		if($project_id!=NULL){
    		    	$this->db->where('project_id', $project_id);
    		}
    	
    		$this->db->where('status >=',7);
    		$query = $this->db->get('projects_vendor_mapping');
    	
    		if( $query->num_rows() > 0 ){
    			$result = $query->row_array();
    			$data['incurred'] = (!empty($result['incurred_expenses']))?$result['incurred_expenses']:'0';
    			
    			return $data;
    		} else {
    		    return $data;
    		}
	    }else{
	        
	        $da = $this->total_budget_allocated();
	        
	      $da['total']=0;
	      $da['incurred'] = 0;
	        if(!empty($da)){
	            foreach($da as $i=>$l){
	                $da['total'] = $da['total'] + $l['budget'];
	                $da['incurred'] = $da['incurred'] + $l['budget_used'];
	            }
	        }
	      $data = array(
			'budgeted' => $da['total'],
			'incurred' => $da['incurred']
	    	);  
	    	
	    	return $data;
	    }
	    
		
	}
	
	function line_chart_data($project_id){

		$this->db->select('vendor_1');
		$this->db->where('project_id', $project_id);
		$this->db->group_by('vendor_1');
		$query = $this->db->get('projects_vendor_mapping');
		if( $query->num_rows() > 0 ){
			$result = $query->result_array();

			foreach ($result as $value) {

				$this->db->select('start_date,completion_date');
				$this->db->where('project_id', $project_id);	
				$this->db->where('vendor_1', $value['vendor_1']);
				$query1 = $this->db->get('projects_vendor_mapping');
				$result1 = $query1->result_array();
				
				$total_time = '0'; $k=0; $diff =0;
				foreach ($result1 as $value1) {
				    
					if( $value1['completion_date'] != '0000-00-00 00:00:00' ){
					    $earlier = new DateTime($value1['start_date']);
                        $later = new DateTime($value1['completion_date']);
                        
                        $diff = $diff + $later->diff($earlier)->format("%a");
                        $k = $k+1;
					}
				}
				
				if( $k > 0 ){
				    $total_time = round($diff/$k,0);
				}

				$data_array['vendor_name'] = get_value('name', 'user', $value['vendor_1'], 'user_id');
				$data_array['total_time'] = $total_time;
				
				$main_array[] = $data_array;
			}

			return $main_array;
		} else {
			return array();
		}
	}
	
	function total_completed_site(){
	    $this->db->select('count(pv.id) as completed');
	    $this->db->from('projects_vendor_mapping pv');
	  	$this->db->join('projects p', 'p.project_id = pv.project_id');
	    if( get_session('role') == 'vendor' ){
	        if( get_session('user_type') == 'Printer' ){
    	        $this->db->where('pv.vendor_2', get_session('user_id'));
    	    } else {
    	        $this->db->where('pv.vendor_1', get_session('user_id'));
    	    }
	    }
	    $this->db->group_start();
	    $this->db->where('pv.status', '10');
	    $this->db->or_where('pv.status', '12');
	    $this->db->or_where('pv.status', '13');
	    $this->db->or_where('pv.status', '15');
	    $this->db->group_end();
	    
	    
         $role = $this->session->userdata('role');
		if($role == 'rsm'){
		    $this->db->where('p.project_region',$this->session->userdata('region'));
		}elseif($role=='tmm'|| $role=='com'){
		    $this->db->where('p.project_zone', $this->session->userdata('zone'));
		}
	$this->db->where('p.brand',$this->session->userdata('brand'));
		$query = $this->db->get();
		if( $query->num_rows() > 0 ){
			$result = $query->row_array();
			return $result['completed'];
		}
		return 0;
	}
	
	function total_pending_site(){
	    $this->db->select('count(pv.id) as pending');
	     $this->db->from('projects_vendor_mapping pv');
	     $this->db->join('projects p', 'p.project_id = pv.project_id');
	    if($this->session->userdata('role') == 'vendor' ){
	        $this->db->where('pv.vendor_1', get_session('user_id'));
	    }
	    $this->db->group_start();
	    $this->db->where('pv.status', '0');
	    /*$this->db->where('status!=', '12');
	    $this->db->where('status!=', '13');
	    $this->db->where('status!=', '15');*/
	    $this->db->group_end();
	     $role = $this->session->userdata('role');
		if($role == 'rsm'){
		    $this->db->where('p.project_region',$this->session->userdata('region'));
		}elseif($role=='tmm'|| $role=='com'){
		    $this->db->where('p.project_zone', $this->session->userdata('zone'));
		}
	$this->db->where('p.brand',$this->session->userdata('brand'));
// 	query();
		$query = $this->db->get();
		if( $query->num_rows() > 0 ){
			$result = $query->row_array();
			return $result['pending'];
		}
		return 0;
	}
	
	function total_budget_allocated(){
	   
        $this->db->select('SUM(IF(pv.status >= 7, pv.pre_value, 0)) as budget_used,p.budgeted_amount as budget');
		$this->db->from('projects p');
		$this->db->join('projects_vendor_mapping pv', 'p.project_id = pv.project_id','left');
		
        if($this->session->userdata('role') == 'rsm'){
            $this->db->where('p.project_region', $this->session->userdata('region'));
        }  
        if($this->session->userdata('role') == 'tmm' || $this->session->userdata('role') == 'com'){
            $this->db->where('p.project_zone', $this->session->userdata('zone'));
        } 
        if($this->session->userdata('role') != 'admin'){
            $this->db->where('p.brand', $this->session->userdata('brand'));
        }
	
		$this->db->where('p.status', 'Active');
		$this->db->group_by('p.project_id');
		$query = $this->db->get();
		// echo $this->db->last_query(); die;
		if( $query->num_rows() > 0 ){
			$result = $query->result_array();
			return $result;
		}

		return false;
    
	}
	
	
	function project_count(){
	    $this->db->select('projects.id,count(projects.id) as project_count');
	    $this->db->where('projects.brand', $this->session->userdata('brand')); 
	     if($this->session->userdata('role') == 'vendor') {
	        $this->db->join('projects_vendor_mapping pv','pv.project_id = projects.project_id');
    		$this->db->where('pv.vendor_1', $this->session->userdata('user_id'));
    		
		} 
	   if($this->session->userdata('role') =='tmm' || $this->session->userdata('role') =='com'){
		   $this->db->where('projects.project_zone', $this->session->userdata('zone')); 
		} else if($this->session->userdata('role') =='rsm'){
		    $this->db->where('projects.project_region', $this->session->userdata('region'));  
		}
		$this->db->group_by('projects.project_id');
		$query = $this->db->get('projects');
		if( $query->num_rows() > 0 ){
			return $query->num_rows();
		}
		return 0;
	}

    function vendor_project_count(){
	    $this->db->select('id,count(id) as project_count');
	    $this->db->where('brand', $this->session->userdata('brand')); 
	    if( get_session('user_type') == 'Printer' ){
	        $this->db->where('vendor_2', get_session('user_id'));
	    } else {
	        $this->db->where('vendor_1', get_session('user_id'));
	    }
        $this->db->group_by('project_id');
		$query = $this->db->get('projects_vendor_mapping');
		if( $query->num_rows() > 0 ){
			return $query->num_rows();
		}
		return 0;
	}

	function change_password() {
		$where = array(
			'id' => get_session('id'),
			'password' => md5($this->input->post('old')),
		);
		$this->db->where($where);
		$query = $this->db->get('user');
		if( $query->num_rows() > 0 ){

			$this->db->set('password', md5($this->input->post('newpass')));
			$this->db->set('is_first_login', 1);
			$this->db->where($where);
			$query = $this->db->update('user');
			if( $this->db->affected_rows() > 0 ){
				return true;
			}
			return false;
		}
		return false;
	}

	function total_submitted_site(){
	    $this->db->select('count(pv.id) as recieved');
	     $this->db->from('projects_vendor_mapping pv');
	     $this->db->join('projects p', 'p.project_id = pv.project_id');
	    if( get_session('role') == 'vendor' ){
	        if( get_session('user_type') == 'Printer' ){
    	        $this->db->where('pv.vendor_2', get_session('user_id'));
    	    } else {
    	        $this->db->where('pv.vendor_1', get_session('user_id'));
    	    }
	    }
	    $this->db->where('pv.status=', '1');
	     $role = $this->session->userdata('role');
		if($role == 'rsm'){
		    $this->db->where('p.project_region',$this->session->userdata('region'));
		}elseif($role=='tmm'|| $role=='com'){
		    $this->db->where('p.project_zone', $this->session->userdata('zone'));
		}
	$this->db->where('p.brand',$this->session->userdata('brand'));
		$query = $this->db->get();
		if( $query->num_rows() > 0 ){
			$result = $query->row_array();
			return $result['recieved'];
		}
		return 0;
	}

	function total_po_approved_site(){
	    $this->db->select('count(pv.id) as artwork_app');
	     $this->db->from('projects_vendor_mapping pv');
	     $this->db->join('projects p', 'p.project_id = pv.project_id');
	    if( get_session('role') == 'vendor' ){
    	  $this->db->where('pv.vendor_1', get_session('user_id'));
	    }
	    $this->db->where('pv.status=', '7');
	      $role = $this->session->userdata('role');
		if($role == 'rsm'){
		    $this->db->where('p.project_region',$this->session->userdata('region'));
		}elseif($role=='tmm'|| $role=='com'){
		    $this->db->where('p.project_zone', $this->session->userdata('zone'));
		}
		$this->db->where('p.brand',$this->session->userdata('brand'));
		$query = $this->db->get();
		if( $query->num_rows() > 0 ){
			$result = $query->row_array();
			return $result['artwork_app'];
		}
		return 0;
	}

	function total_po_pending_site(){
	    $this->db->select('count(pv.id) as artwork_pending');
	    $this->db->from('projects_vendor_mapping pv');
	     $this->db->join('projects p', 'p.project_id = pv.project_id');
	    if( get_session('role') == 'vendor' ){
	        $this->db->where('pv.vendor_1', get_session('user_id'));
	    }
	    $this->db->where('pv.status=', '5');
	    //$this->db->or_where('status=', '6');
	    $role = $this->session->userdata('role');
		if($role == 'rsm'){
		    $this->db->where('p.project_region',$this->session->userdata('region'));
		}elseif($role=='tmm'|| $role=='com'){
		    $this->db->where('p.project_zone', $this->session->userdata('zone'));
		}
		$this->db->where('p.brand',$this->session->userdata('brand'));
		$query = $this->db->get();
		if( $query->num_rows() > 0 ){
			$result = $query->row_array();
			return $result['artwork_pending'];
		}
		return 0;
	}
	
	function total_deployment_approved()
	{
		$this->db->select('count(pv.id) as deployment_approved');
		 $this->db->from('projects_vendor_mapping pv');
	     $this->db->join('projects p', 'p.project_id = pv.project_id');
	    $this->db->where('pv.status=', '11');
	     $role = $this->session->userdata('role');
		if($role == 'rsm'){
		    $this->db->where('p.project_region',$this->session->userdata('region'));
		}elseif($role=='tmm'|| $role=='com'){
		    $this->db->where('p.project_zone', $this->session->userdata('zone'));
		}
		$this->db->where('p.brand',$this->session->userdata('brand'));
		$query = $this->db->get();
		if( $query->num_rows() > 0 ){
			$result = $query->row_array();
			return $result['deployment_approved'];
		}
		return 0;
	}


// dashboard work
    function site_count($where){
        $this->db->select('count(pv.id) as site_count');
		 $this->db->from('projects_vendor_mapping pv');
	     $this->db->join('projects p', 'p.project_id = pv.project_id');
	    $this->db->where($where);
	     $role = $this->session->userdata('role');
		if($role == 'rsm'){
		    $this->db->where('p.project_region',$this->session->userdata('region'));
		}elseif($role=='tmm'|| $role=='com'){
		    $this->db->where('p.project_zone', $this->session->userdata('zone'));
		}
		$this->db->where('p.brand',$this->session->userdata('brand'));
	
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ){
			$result = $query->row_array();
			return $result['site_count'];
		}
		return 0;
    }
    
    function site_allocation_count($where=NULL){
        $this->db->select('count(st.id) as site_count');
		$this->db->from('site_allocation_request st');
	     $role = $this->session->userdata('role');
		if($role == 'rsm'){
		    $this->db->where('st.region',$this->session->userdata('region'));
		}elseif($role=='tmm'|| $role=='com'){
		    $this->db->where('st.tmm_zone', $this->session->userdata('zone'));
		}
		$this->db->where('st.brand',$this->session->userdata('brand'));
		if($where!=NULL){
		$this->db->where($where);
		}
		$this->db->where('st.status!=','Re-Allocated');
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ){
			$result = $query->row_array();
			return $result['site_count'];
		}
		return 0;
    }
    
    function column_chart($id) {

		$this->db->select('project_status.status_name, count(projects_vendor_mapping.id) as stores ');
		$this->db->from('project_status');
		$this->db->join('projects_vendor_mapping', 'project_status.status_id = projects_vendor_mapping.status', 'left');
	
		    $this->db->where('projects_vendor_mapping.project_id',$id);
		
	
		$this->db->group_by('project_status.graph_status');
		$query = $this->db->get();
	
		if( $query->num_rows() > 0 ){
			$result = $query->result_array();
			return $result;
		}
		return false;
	}
// end
}