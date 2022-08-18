<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Projects_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	
	} 

	function project($id = null, $store_id = null) {
		$this->db->select('p.*, pv.*, count(pv.store_id) as total_store');
		$this->db->from('projects p');
		$this->db->join('projects_vendor_mapping pv', 'p.project_id = pv.project_id');
		if( isset($_GET['start']) && !empty($_GET['start'])  ){
			$this->db->where('p.created_at>=', date('Y-m-d 00:00:00', strtotime($_GET['start'])));
		}

		if( isset($_GET['end']) && !empty($_GET['end'])  ){
			$this->db->where('p.created_at<=', date('Y-m-d 23:59:00', strtotime($_GET['end'])));
		}

		if( $id != ''){
			$this->db->where('p.id', $id);
		}

		if( $store_id != ''){
			$this->db->where('pv.store_id', $store_id);
		}

		if($this->session->userdata('role') != 'admin') {

			if($this->session->userdata('user_type') == 'Fabricator') {

				$this->db->where('pv.vendor_1', $this->session->userdata('user_id'));

			}

			$this->db->where('p.brand', $this->session->userdata('brand'));

		}

		

		$this->db->where('p.status', 'Active');

		$this->db->group_by('pv.vendor_1');

		$this->db->order_by('p.id', 'DESC');

		$query = $this->db->get();

		//debug($this->db->last_query());

		if( $query->num_rows() > 0 ){

			$result = $query->result_array();

			return $result;

		}

		return false;

	}



	function add_project($data) {

		$this->db->insert('projects', $data);

		$insert_id = $this->db->insert_id();

		if($insert_id != '') {

			return $insert_id;

		} else {

			return false;

		}        

	}

	function add_project_exception($data_arr,$associated_project_id,$username,$budgeted_amount,$fix_budget_amount,$new_budgeted_amount) {
	
		$updated_data=$data;
		$data_insert=$data;

		$this->db->select('*');
        $this->db->where('project_id',$associated_project_id);
        $query = $this->db->get('projects');
        
       
		
		
	//	echo $this->db->last_query();		die;
		$result = $query->row();
		
		// echo "<pre>"; print_r($result); die;
		 $total_Amounts = $result->budgeted_amount; 
		
		 $total_amounts22 =  $total_Amounts - $budgeted_amount;
		
		 $result->transfer_amount=$budgeted_amount;

		 $result->balanced_amount=$total_amounts22;
		 
		 
		 
		  
        $this->db->select('SUM(IF(status >= 7 AND status != 24, pre_value, 0)) as budgets_used');				
        $this->db->where('project_id',$associated_project_id);
        $query44 = $this->db->get('projects_vendor_mapping');
	
       $result44 = $query44->row_array();
	   
	     $used_budget44 = $result44['budgets_used'];

		$budgeted_amounts = (int)$used_budget44;

		 $unused_budget =  $total_Amounts - $budgeted_amounts;
		 
		 
		 $result->unused_amount = $unused_budget;
		 $result->used_amount = $used_budget44;
		 
		
		$previous_project_budget = json_encode($result);
	
		$budget_data_log11=array(
    		'project_id'=>$associated_project_id,
    		'username'=>$username,
    		'old_json'=>$previous_project_budget,		
		);
		
		 $this->db->insert('budget_project_log_history',$budget_data_log11);
	//	echo "<br>";
		$log_insert_id = $this->db->insert_id();
		
	$number = (int)$total_amounts22;
	$no = floor($number);
	$point = round($number - $no, 2) * 100;
	$hundred = null;
	$digits_1 = strlen($no);
	$i = 0;
	$str = array();
	$words = array('0' => '', '1' => 'One', '2' => 'Two',
	'3' => 'Three', '4' => 'Four', '5' => 'Five', '6' => 'Six',
	'7' => 'Seven', '8' => 'Eight', '9' => 'Nine',
	'10' => 'Ten', '11' => 'Eleven', '12' => 'Twelve',
	'13' => 'Thirteen', '14' => 'Fourteen',
	'15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen',
	'18' => 'Eighteen', '19' =>'Nineteen', '20' => 'Twenty',
	'30' => 'Thirty', '40' => 'Forty', '50' => 'Fifty',
	'60' => 'Sixty', '70' => 'Seventy',
	'80' => 'Eighty', '90' => 'Ninety');
	$digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
	while ($i < $digits_1) {
	 $divider = ($i == 2) ? 10 : 100;
	 $number = floor($no % $divider);
	 $no = floor($no / $divider);
	 $i += ($divider == 10) ? 1 : 2;
	 if ($number) {
		$plural = (($counter = count($str)) && $number > 9) ? 's' : null;
		$hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
		$str [] = ($number < 21) ? $words[$number] .
			" " . $digits[$counter] . $plural . " " . $hundred
			:
			$words[floor($number / 10) * 10]
			. " " . $words[$number % 10] . " "
			. $digits[$counter] . $plural . " " . $hundred;
	 } else $str[] = null;
	}
	
	$str = array_reverse($str);
	$result = implode('', $str);
	$points = ($point) ?
	"." . $words[$point / 10] . " " . 
		  $words[$point = $point % 10] : '';
		  
	 $amt_in_words =  $result . "Rupees " . $points . " Only";
	
		
		$update_budget_data=array(
			'budgeted_amount'=>$total_amounts22,
			'amt_in_words'=>$amt_in_words		
			);
				
			//	echo "<pre>"; print_r($update_budget_data); die;
				
		$this->db->where('project_id',$associated_project_id);
		$this->db->update('projects',$update_budget_data);
		// echo $this->db->last_query();
		
		// die;
		
		
		 
		$this->db->insert('projects', $data_arr);	
		
		$insert_id = $this->db->insert_id();
		
		$this->db->select('*');
        $this->db->where('id',$insert_id);
        $querys = $this->db->get('projects')->row();
		
		$associated_project_id_json = $querys->project_id;
		$associated_project_idss = $querys->project_id;
		
				
		
		$this->db->select('*');
		$this->db->from('projects');
		$this->db->where('project_id',$associated_project_id);
		$updateResults = $this->db->get()->row();
		
		$total_Amounts = $updateResults->project_id; 
		$total_associated_project_id = $updateResults->associated_project_id; 
		
		if(empty($total_associated_project_id))
		{ 
			$data_attach ="";
		}else  {
		 $data_attach = $total_associated_project_id.',';
		}
		
		
		$query_updatess="update projects set associated_project_id='".$data_attach.''.$associated_project_idss."' where project_id='".$associated_project_id."'";
			//	die;
		$this->db->query($query_updatess);
		
		
		// insert updated log
		
		$this->db->select('*');
        $this->db->where('project_id',$associated_project_id);
        $query = $this->db->get('projects');

		$result22 = $query->row();
				
		$this->db->select('SUM(IF(status >= 7 AND status != 24, pre_value, 0)) as budgets_used');				
        $this->db->where('project_id',$associated_project_id);
        $query = $this->db->get('projects_vendor_mapping');
	
       $result = $query->result_array();
	   
	    $used_budget = $result[0]['budgets_used'];

		$budgeted_amounts = (int)$total_amounts22;

		 $rest_budget =  $budgeted_amounts - $used_budget;
		
		 $output = round($rest_budget);	
		 
		$result22->budgeted_amount=$total_amounts22;
		$result22->associated_project_id=$associated_project_id_json;
		$result22->used_amount=$used_budget;			
		$result22->unused_amount=$output;			
		
			
		 $new_project_budget = json_encode($result22);
	
		$budget_amended = $total_Amounts - $fix_budget_amount;
		
		$budget_data_log11=array(
		'project_id'=>$associated_project_id,
		'project_exception_id' =>$associated_project_idss,
		'username'=>$username,		
		'new_json'=>$new_project_budget,
		'budget_amended'=>$budgeted_amount,
		'created_at'=>date('Y-m-d H:i:s')
		);
	
		 $this->db->where('id',$log_insert_id);
		 $this->db->update('budget_project_log_history',$budget_data_log11);
		 
		 
		 
		 
		$associated_project_id_json = $querys->project_id;
		$associated_project_idss = $querys->project_id;
		 
		 $arr = array(
		    'budget_amount'=> $budgeted_amount,
		    'used_amount'=>0,
		    'unused_amount'=>0,
		    'main_project'=>$associated_project_id
		  );
		 
		 $budget_data_log112=array(
		'project_id'=>$associated_project_idss,
		'project_exception_id' =>'',
		'username'=>$username,	
		'old_json'=>'',	
		'new_json'=>json_encode($arr),
		'budget_amended'=>0,
		'created_at'=>date('Y-m-d H:i:s')
		);
		
		
		 $this->db->insert('budget_project_log_history',$budget_data_log112);
		 
		 
		
		if($insert_id != '') {

			return $insert_id;

		} else {

			return false;

		}        

	}
	

	function get_zone() {

        $this->db->select('zone,region');

        //$this->db->where('zone',$this->session->userdata('zone'));

        $this->db->where('brand', $this->session->userdata('brand')); 

        $this->db->group_by('zone'); 

        $query = $this->db->get('region_master');

		if( $query->num_rows() > 0 ){

			$result = $query->result_array();

			return $result;

		}

		return false;

	}



	function update_project($data, $id) {

		$this->db->where('project_id', $id);

		$this->db->update('projects', $data);

		if( $this->db->affected_rows() > 0 ) {

			return true;

		}

		return false;        

	}



	function delete_project($id) {

		$this->db->where('id',$id);

		$this->db->delete('projects');

		if( $this->db->affected_rows() > 0 ) {

			return true;

		}

		return false;

	}



	function project_json_data($id = null) {

		$this->db->select('p.*, pv.*, count(pv.store_id) as total_store, p.created_at as allocation_date');

		$this->db->from('projects p');

		$this->db->join('projects_vendor_mapping pv', 'p.project_id = pv.project_id');

		if( isset($_GET['start']) && !empty($_GET['start'])  ){

			$this->db->where('p.created_at>=', date('Y-m-d 00:00:00', strtotime($_GET['start'])));

		}

		if( isset($_GET['end']) && !empty($_GET['end'])  ){

			$this->db->where('p.created_at<=', date('Y-m-d 23:59:00', strtotime($_GET['end'])));

		}

		if( $id != ''){

			$this->db->where('p.id', $id);

		}

		if($this->session->userdata('role') != 'admin') {

			if($this->session->userdata('user_type') == 'Fabricator') {

				$this->db->where('pv.vendor_1', $this->session->userdata('user_id'));

			} else if($this->session->userdata('role') =='tmm' || $this->session->userdata('role') =='com'){

			    

			   $this->db->where('p.project_zone', $this->session->userdata('zone')); 

			} else if($this->session->userdata('role') =='rsm'){

			    $this->db->where('p.project_region', $this->session->userdata('region'));  

			}

		}

		

		if($this->session->userdata('role') != 'admin'){

            $this->db->where('p.brand', $this->session->userdata('brand'));

        }

        

		$this->db->group_by('pv.project_id');

		$this->db->order_by('p.id', 'DESC');

		$query = $this->db->get();

		//debug($this->db->last_query());

		if( $query->num_rows() > 0 ){

			$result = $query->result_array();

			return $result;

		}

		return false;

	}



	function project_json_data_search_store($id = null, $store_id = null) {

		$this->db->select('p.*, pv.*, count(pv.store_id) as total_store, p.created_at as allocation_date');

		$this->db->from('projects p');

		$this->db->join('projects_vendor_mapping pv', 'p.project_id = pv.project_id');

		if( isset($_GET['start']) && !empty($_GET['start'])  ){

			$this->db->where('p.created_at>=', date('Y-m-d 00:00:00', strtotime($_GET['start'])));

		}

		if( isset($_GET['end']) && !empty($_GET['end'])  ){

			$this->db->where('p.created_at<=', date('Y-m-d 23:59:00', strtotime($_GET['end'])));

		}

		if( $id != ''){

			$this->db->where('p.id', $id);

		}

		if( $store_id != ''){

			$this->db->where('pv.store_id', $store_id);

		}

		if($this->session->userdata('role') != 'admin') {

			if($this->session->userdata('user_type') == 'Fabricator') {

				$this->db->where('pv.vendor_1', $this->session->userdata('user_id'));

			} elseif($this->session->userdata('user_type') == 'Printer') {

				$this->db->where('pv.vendor_2', $this->session->userdata('user_id'));

			}

		}

		$this->db->group_by('pv.project_id');

		$this->db->order_by('p.id', 'DESC');

		$query = $this->db->get();

		if( $query->num_rows() > 0 ){

			$result = $query->result_array();

			return $result;

		}

		return false;

	}



	function project_json_data_admin($id = null, $store_id = null) {

		$this->db->select('p.*, count(pv.ftr_number) as ftr_number, pv.vendor_1, pv.vendor_2, count(distinct NULLIF(pv.store_id,"")) as total_store, count(distinct NULLIF(pv.vendor_1,"") ) as total_vendors1, count(distinct NULLIF(pv.vendor_2,"") ) as total_vendors2, SUM(IF(pv.status >= 7 AND pv.status!= 24 , pv.pre_value, 0)) as budget_used,sum(IF(pv.status=11,1,0)) as ftr_total,sum(IF(pv.status=18,1,0)) as dep_app_total');

		$this->db->from('projects p');

		$this->db->join('projects_vendor_mapping pv', 'p.project_id = pv.project_id','left');

		if( isset($_GET['start']) && !empty($_GET['start'])  ){

			$this->db->where('p.start_date>=', date('Y-m-d 00:00:00', strtotime($_GET['start'])));

		}

		if( isset($_GET['end']) && !empty($_GET['end'])  ){

			$this->db->where('p.end_date<=', date('Y-m-d 23:59:00', strtotime($_GET['end'])));

		}

		if( $store_id != ''){

			$this->db->where('pv.store_id', $store_id);

		}



        if($this->session->userdata('role') == 'rsm'){

            $this->db->where('p.project_region', $this->session->userdata('region'));

        }  

        

        if($this->session->userdata('role') == 'tmm' || $this->session->userdata('role') == 'com'){

            $this->db->where('p.project_zone', $this->session->userdata('zone'));

        } 

        if($this->session->userdata('role') != 'admin'){

            $this->db->where('p.brand', $this->session->userdata('brand'));

        }

		if(isset($_GET['region']) && !empty($_GET['region']) && $_GET['region']!='All'){

            $this->db->where('p.project_region', $_GET['region']);

        }

        

		$this->db->where('p.status', 'Active');

		$this->db->group_by('p.project_id');

		$this->db->order_by('p.id', 'DESC');

		$query = $this->db->get();

		if( $query->num_rows() > 0 ){

			$result = $query->result_array();

			return $result;

		}

		return false;

	}



	function project_search_list_by_sap_code($sap_code = null) {
    
		$this->db->select('p.*, pv.store_id, pv.vendor_1, pv.vendor_2, count(distinct NULLIF(pv.store_id,"")) as total_store, count(distinct NULLIF(pv.vendor_1,"") ) as total_vendors1, count(distinct NULLIF(pv.vendor_2,"") ) as total_vendors2, SUM(IF(pv.status >= 7 AND pv.status!= 24, pv.pre_value, 0)) as budget_used,count(distinct NULLIF(pv.status,"11")) as ftr_total,sum(IF(pv.status=18,1,0)) as dep_app_total');

		$this->db->from('projects p');

		$this->db->join('projects_vendor_mapping pv', 'p.project_id = pv.project_id','left');

		if( isset($_GET['start']) && !empty($_GET['start'])  ){

			$this->db->where('p.start_date>=', date('Y-m-d 00:00:00', strtotime($_GET['start'])));

		}

		if( isset($_GET['end']) && !empty($_GET['end'])  ){

			$this->db->where('p.end_date<=', date('Y-m-d 23:59:00', strtotime($_GET['end'])));

		}

		if( $sap_code != ''){

			$this->db->where('pv.sap_code', $sap_code);

		}

		$this->db->group_by('p.project_id');

		$this->db->order_by('p.id', 'DESC');

		$query = $this->db->get();
      
		if( $query->num_rows() > 0 ){

			$result = $query->result_array();

			return $result;

		}

		return false;

	}



	function add_project_vendor_store($data) {

		$this->db->insert('projects_vendor_mapping', $data);

			$insert_id = $this->db->insert_id();

			if($insert_id != '') {

				return $insert_id;

			} else {

				return false;

			}        

	}



	function add_project_vendor_element($data) {

		$this->db->insert('project_store_element', $data);

			$insert_id = $this->db->insert_id();

			if($insert_id != '') {

				return $insert_id;

			} else {

				return false;

			}        

	}



	function validID( $column, $value , $table ){

		$this->db->where($column,$value);

		$query = $this->db->get($table);

		if( $query->num_rows() != 0 ){

			return true;

		} else {

			return false;

		}

	}



	function count_prj($id = null) {

		$this->db->select('count(distinct pv.project_id) as total_project, count(pv.store_id) as total_store');

		$this->db->from('projects p');

		$this->db->join('projects_vendor_mapping pv', 'p.project_id = pv.project_id');

		if( isset($_GET['start']) && !empty($_GET['start'])  ){

			$this->db->where('p.created_at>=', date('Y-m-d 00:00:00', strtotime($_GET['start'])));

		}

		if( isset($_GET['end']) && !empty($_GET['end'])  ){

			$this->db->where('p.created_at<=', date('Y-m-d 23:59:00', strtotime($_GET['end'])));

		}

		if($this->session->userdata('role') != 'admin') {

			if($this->session->userdata('user_type') == 'Printer'){

				$this->db->where('pv.vendor_2', $this->session->userdata('user_id'));

				$this->db->group_by('pv.vendor_2'); 

			}else{

				$this->db->where('pv.vendor_1', $this->session->userdata('user_id'));

				$this->db->group_by('pv.vendor_1'); 

			}

			

		}

       
		$query = $this->db->get();

		if( $query->num_rows() > 0 ){

			$result = $query->row_array();

			return $result;

		}

		return false;

	}




	function project_count($id = null){

		$this->db->select('count(project_id) as total_project');

		if($id != null){

			$this->db->where('project_id',$id);

		}

		if($this->session->userdata('role') == 'rsm'){

            $this->db->where('project_region', $this->session->userdata('region'));

        }  

        if($this->session->userdata('role') == 'tmm'||$this->session->userdata('role') == 'com' ){

            $this->db->where('project_zone', $this->session->userdata('zone'));

        } 

        $this->db->where('brand', $this->session->userdata('brand'));

		$query = $this->db->get('projects');

		if( $query->num_rows() > 0 ){

			$result = $query->row_array();

			return $result['total_project'];

		}

		return false;

	}



	function store_count($id = null){

		$this->db->select('count(distinct NULLIF(pv.store_id,"")) as total_store');
	    $this->db->from('projects_vendor_mapping pv');
		$this->db->join('projects p', 'p.project_id = pv.project_id');
		if($id != null){

			$this->db->where('pv.project_id',$id);

		}

        $role = $this->session->userdata('role');
		if($role == 'rsm'){
		    $this->db->where('p.project_region',$this->session->userdata('region'));
		}elseif($role=='tmm'|| $role=='com'){
		    $this->db->where('p.project_zone', $this->session->userdata('zone'));
		}
		$this->db->where('pv.brand', $this->session->userdata('brand'));

		$query = $this->db->get();

		if( $query->num_rows() > 0 ){

			$result = $query->row_array();

			return $result['total_store'];

		}

		return false;

	}



	function printer_count($id = null){

		$this->db->select('count(distinct NULLIF(vendor_2,"")) as total_printer');

		if($id != null){

			$this->db->where('project_id',$id);

		}

		$this->db->where('brand', $this->session->userdata('brand'));

		$query = $this->db->get('projects_vendor_mapping'); 

		if( $query->num_rows() > 0 ){

			$result = $query->row_array();

			return $result['total_printer'];

		}

		return false;

	}



	function fabricator_count($id = null){

		$this->db->select('count(distinct NULLIF(pv.vendor_1,"")) as total_fabricator');
		$this->db->from('projects_vendor_mapping pv');
		$this->db->join('projects p', 'p.project_id = pv.project_id');


		if($id != null){

			$this->db->where('pv.project_id',$id);

		}

		$this->db->where('pv.brand', $this->session->userdata('brand'));
		
		$role = $this->session->userdata('role');
		if($role == 'rsm'){
		    $this->db->where('p.project_region',$this->session->userdata('region'));
		}elseif($role=='tmm' || $role=='com'){
		    $this->db->where('p.project_zone', $this->session->userdata('zone'));
		}
    
        
		$query = $this->db->get(); 

		if( $query->num_rows() > 0 ){

			$result = $query->row_array();

			return $result['total_fabricator'];

		}

		return false;

	}



	function store_list($id = null) {

		$this->db->select('projects_vendor_mapping.*');

		$this->db->from('projects_vendor_mapping');

// 		$this->db->join('project_status','project_status.status_id=projects_vendor_mapping.status');

// 		$this->db->join('user','user.user_id = projects_vendor_mapping.vendor_1');

		$this->db->where('projects_vendor_mapping.project_id', $id);



		if(@$_GET['status']!='' && @$_GET['status']!='All'){

		    $this->db->where('projects_vendor_mapping.status',$_GET['status']);

		}

		/*if($_GET['vendor']!='' && $_GET['vendor']!='All'){

		    $this->db->where('projects_vendor_mapping.vendor_1',$_GET['vendor']);

		}*/

		if($this->session->userdata('role')=='vendor'){

		    if($this->session->userdata('user_type') == 'Printer'){

		        $this->db->where('projects_vendor_mapping.vendor_2',$this->session->userdata('user_id'));

		    } else{

		        $this->db->where('projects_vendor_mapping.vendor_1',$this->session->userdata('user_id'));

		    }

		}

		
		if(@$_GET['channel_partner_type']!='' && @$_GET['channel_partner_type']!='All'){

			$this->db->join('store_master','store_master.store_uniqueID = projects_vendor_mapping.store_id');
		    $this->db->where('store_master.channel_partner_type',$_GET['channel_partner_type']);
			
		}
		
		if(@$_GET['ftr_number']!='' && @$_GET['ftr_number']!='All'){

			$this->db->join('store_master','store_master.store_uniqueID = projects_vendor_mapping.store_id');
		    $this->db->where('projects_vendor_mapping.ftr_number',$_GET['ftr_number']);
			
		}

		/*if($this->session->userdata('role') == 'rsm'){

            $this->db->where('p.project_region', $this->session->userdata('region'));

        }  

        

        if($this->session->userdata('role') == 'tmm'){

            $this->db->where('p.project_zone', $this->session->userdata('zone'));

        } */

		

// 		$this->db->order_by('projects_vendor_mapping.timestamp','ASC');
		
		$this->db->order_by('projects_vendor_mapping.id','DESC');

		$query = $this->db->get();
      
		

		if( $query->num_rows() > 0 ){

			$result = $query->result_array();

			return $result;

		}

		return false;

	}



	function store_info($id) {

		$this->db->where('store_uniqueID', $id);
		$query = $this->db->get('store_master');
	
		if( $query->num_rows() > 0 ){

			$result = $query->row_array();
		
			return $result;

		}

		return false;

	}



	function project_info($id) {

		$this->db->where('project_id', $id);

		$query = $this->db->get('projects');

		if( $query->num_rows() > 0 ){

			$result = $query->row_array();

			return $result;

		}

		return false;

	}



	function project_status($store_id,$vendor_id,$project_id) {

		$this->db->where('store_id', $store_id);

		$this->db->where('vendor_1', $vendor_id);

		$this->db->where('project_id', $project_id);

		$query = $this->db->get('projects_vendor_mapping');

		if( $query->num_rows() > 0 ){

			$result = $query->row_array();

			return $result;

		}

		return false;

	}



	function get_element_cost($project_id, $vendor_id, $element_id = '', $element_type = '') {

		$this->db->select('element_rate, installation_per_inch_rate');

		$this->db->where('element_id', $element_id);

		$this->db->where('vendor_id', $vendor_id);

		$this->db->where('project_id', $project_id);

		$this->db->where('element_type', $element_type);

		$query = $this->db->get('project_store_element');

		if( $query->num_rows() > 0 ){

			$result = $query->row_array();

			return $result['element_rate'].'@'.$result['installation_per_inch_rate'];

		}

		return false;

	}

	

	

	function get_project($id = null) {

		$this->db->select('*');

		$this->db->from('projects');

		if( $id!= null){

			$this->db->where('project_id',$id);

		}

		$this->db->where('brand', $this->session->userdata('brand'));

		$query = $this->db->get();

		if( $query->num_rows() > 0){

			$result = $query->row_array();

			return $result;

		}

		return false;

	}



	function project_detail($id = null) {

		$this->db->select('p.*, pv.*');

		$this->db->from('projects p');

		$this->db->join('projects_vendor_mapping pv', 'p.project_id = pv.project_id');

		if( $id!= null){

			$this->db->where('p.project_id',$id);

		}

		$this->db->where('p.brand', $this->session->userdata('brand'));

		$query = $this->db->get();

		if( $query->num_rows() > 0){

			$result = $query->row_array();

			return $result;

		}

		return false;

	}



	function element_info($id) {

		$this->db->where('element_id', $id);

		$query = $this->db->get('element_master');

		if( $query->num_rows() > 0 ){

			$result = $query->row_array();

			return $result;

		}

		return false;

	}



	function get_previous_status($store_id, $project_id) {

		$this->db->select('status');

		$this->db->where('project_id', $project_id);

		$this->db->where('store_id', $store_id);

		$query = $this->db->get('projects_vendor_mapping');



		if($query->num_rows() > 0) {

			$return =  $query->row_array();

			return $return['status'];

		}

		return false;

	}


// recee revert
 function recee_revert_data($where, $update,$remarks)
    {
        $this->db->trans_start();
        $this->db->select('*');
        $this->db->from('all_recce_posted_data');
        $this->db->where($where);
        $temp = $this->db->get();
        $data = $temp->result_array();
        if($data && count($data)>0){
            $this->db->insert_batch('all_recce_posted_data_revert', $data);
        }
        

        $this->db->where($where);
        $this->db->delete('all_recce_posted_data');
        $this->db->where($where);
        $this->db->delete('element_area');
        $this->db->where($where);
        $this->db->delete('project_store_sub_element');
        $where['vendor_1'] = $where['vendor_id'];
        unset($where['vendor_id']);
        $this->db->where($where);
        $this->db->update('projects_vendor_mapping', $update);
        $this->create_project_logs($where['project_id'], $where['store_id'], $this->session->userdata('user_id'), $this->session->userdata('name'), $this->session->userdata('role'), 0, $remarks);

        $this->db->trans_complete();
        if ($this->db->trans_status() == FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }
// end revert recee

	function all_recce_posted_data($transection_type = null, $previous_status = null, $next_status = null) {

		$project_id = ($this->input->post('project_id') != '') ? $this->input->post('project_id') : $this->input->post('pr');
		$vendor_id = ($this->input->post('vendor_id') != '') ? $this->input->post('vendor_id') : $this->input->post('vr');
		$store_id = ($this->input->post('store_id') != '') ? $this->input->post('store_id') : $this->input->post('st');

        $data = array(
                'project_id' => $project_id,
                'vendor_id' => $vendor_id,
                'store_id' => $store_id,
                'json_data' => json_encode($_POST),
                'created_by' => $this->session->userdata('username'),
                'create_date' => date('Y-m-d H:i:s'),
                'transection_type' => $transection_type,
                'previous_status' => $previous_status,
                'next_status' => $next_status,
                'ip' => $_SERVER['REMOTE_ADDR'],
                'status' => 1
        );
        $this->db->insert('all_recce_posted_data', $data);
        return $this->db->insert_id();
    }
    
	function upload_recce_data() {

		//============ create branch folder ==============
		$vendor_id = $this->input->post('vendor_id');
	
		$folder = 'EXIDE_'.$vendor_id;
		
		create_vendor_folder(VENDOR_IMAGE_PATH,$folder);
		if($this->input->post('permission') == 'yes') {

			/* Add data into element */
			$element_cost_array = array();
		
			for( $x=0;$x<count($this->input->post('width'));$x++ ) {

				if($this->input->post('width')[$x] != '' || $this->input->post('width')[$x] != 0) {

					$exp_element_type = explode('@', $this->input->post('element_type')[$x]);

					//$get_element_cost = $this->projects_model->get_element_cost_new($this->input->post('project_id'),$this->input->post('vendor_id'), $exp_element_type[2]);

					$get_element_cost = $this->projects_model->get_element_type($this->input->post('vendor_id'), $exp_element_type[3]);

					$element = get_row('element_master', $exp_element_type[3], 'element_id');

					

					$element_cost_array[] = array(

						'element_id' => @$exp_element_type[3],

						'project_id' => $this->input->post('project_id'),

						'vendor_id'  => $this->input->post('vendor_id'),

						'store_id'   => $this->input->post('store_id'),

						'element_rate' => $get_element_cost['element_rate'],

						'element_rate_inches' => $get_element_cost['element_rate_inches'],

						'added_by' => $this->session->userdata('role'),

						'element_type' => @$exp_element_type[2],

						'type_element' => $element['element_type'],

						'width'     => $this->input->post('width')[$x],

						'height'    => $this->input->post('height')[$x],

						'area'      => $this->input->post('total_size')[$x],

						'img1'      => $this->input->post('imgl')[$x],

						'img2'      => $this->input->post('imgr')[$x],

						'img3'      => $this->input->post('imgt')[$x],

						'amount'    => $this->input->post('total_size')[$x] * $get_element_cost['element_rate'],

						'status'    => 1,

						'created_at' => date('Y-m-d'),

					);

				}

			}

			$cost = $this->db->insert_batch('element_area', $element_cost_array);
			$cost_id = $this->db->insert_id();
            $get_store_info = $this->get_store_info($this->input->post('store_id'));
            $get_user_info = $this->get_vendor_info($this->input->post('vendor_id'));


			/* Update project vendor mapping */

			$vendor_mapping_array = array(

			    'store_name'=>$get_store_info['store_name'],

			    'add_line1'=>$get_store_info['address'],

			    'city'=>@$get_store_info['city'],

			    'state'=>@$get_store_info['state'],

			    'pincode'=>@$get_store_info['pincode'],

			    'contact_person_name'=>$get_store_info['contact_person'],

			    'contact_mob'=>$get_store_info['contact_no'],

			    'vendor_name'=>$get_user_info['name'],

			    'recce_certificate' => $this->input->post('recce_certificate'),

				'permission' => $this->input->post('permission'),

				'pre_value' => $this->input->post('total_cost'),

				'recce_status' => 'Completed', 

				'vernacular_language' => $this->input->post('vernacular_language'),

				'signage_board_used' => $this->input->post('signage_board_used'),

				'existing_boards' => $this->input->post('existing_boards'),

				'recce_submit_date' => date('Y-m-d H:i:s'),

				'installed_isb_element'=>$this->input->post('isb_element'),

				'recce_submit_comment' => $this->input->post('comment'),

				'status' => 1,

				

			);
			
		

			$this->db->where('element_1', $this->input->post('element_id'));

			$this->db->where('vendor_1', $this->input->post('vendor_id'));

			$this->db->where('project_id', $this->input->post('project_id'));

			$this->db->where('store_id', $this->input->post('store_id'));

			$this->db->update('projects_vendor_mapping', $vendor_mapping_array);





			/* Add data into project store sub element */

			$store_sub_element_cost_array = array(

					'project_id' => $this->input->post('project_id'),

					'vendor_id'  => $this->input->post('vendor_id'),

					'store_id'   => $this->input->post('store_id'),               

					'element_id' => $this->input->post('element_id'),

					/*'element_rate' => $element_cost['element_rate'],

					'element_rate_inches' => $element_cost['element_rate_inches'],*/

					'signoge_cost' => ($this->input->post('signoge_cost'))?$this->input->post('signoge_cost'):0,

					'isb_cost' => !empty($this->input->post('isb_cost')) ? $this->input->post('isb_cost'):0,

					'angle_cost' => 0,

					'scaffolding' => 0,

					'spider' => 0,

					'transport' => 0,

					'installation_cost' => 0,

					'reccee_charge' => !empty($this->input->post('recce_charge')) ? $this->input->post('recce_charge') :0,

					'added_by' => $this->session->userdata('role'),

					'recce_certificate' => $this->input->post('recce_certificate'),

					'status' => 1,

					'created_at' => date('Y-m-d'),

					

			);

			
			

			$element_cost = $this->db->insert('project_store_sub_element', $store_sub_element_cost_array);
          
          
			$element_cost_id = $this->db->insert_id();

			

			$this->create_project_logs($this->input->post('project_id'),$this->input->post('store_id'),$this->session->userdata('user_id'),$this->session->userdata('name'),$this->session->userdata('role'),1,$this->input->post('comment'));



			if($element_cost_id != '') {

				return $element_cost_id;

			}

			return false;



		} else {

            $get_store_info = $this->get_store_info($this->input->post('store_id'));

            $get_user_info = $this->get_vendor_info($this->input->post('vendor_id'));

			/* Update project vendor mapping */

			$vendor_mapping_array = array(

			    'store_name'    => $get_store_info['store_name'],

			    'add_line1'     => $get_store_info['address'],

			    'city'          => $get_store_info['city'],

			    'state'         => $get_store_info['state'],

			    'pincode'       => $get_store_info['pincode'],

			    'contact_person_name'=>$get_store_info['contact_person'],

			    'contact_mob'   => $get_store_info['contact_no'],

			    'vendor_name'   => $get_user_info['name'],

				'permission'    => $this->input->post('permission'),

				'comments'      => $this->input->post('comment'),

				'recce_status'  => 'Completed',

				'recce_submit_comment' => $this->input->post('comment'),

				'recce_submit_date' => date('Y-m-d H:i:s'),

				'status' => 2,

			);

			$this->db->where('element_1', $this->input->post('element_id'));

			$this->db->where('vendor_1', $this->input->post('vendor_id'));

			$this->db->where('project_id', $this->input->post('project_id'));

			$this->db->where('store_id', $this->input->post('store_id'));

			$this->db->update('projects_vendor_mapping', $vendor_mapping_array);



			$this->create_project_logs($this->input->post('project_id'),$this->input->post('store_id'),$this->session->userdata('user_id'),$this->session->userdata('name'),$this->session->userdata('role'),2,$this->input->post('comment'));



			if( $this->db->affected_rows() > 0 ) {

				return true;

			}

			return false;

		}

	}


function update_recce_data_by_vendor() {

		

		//============ create branch folder ==============

		$vendor_id = $this->input->post('vendor_id');
		
		$folder = 'EXIDE_'.$vendor_id;
	
		create_vendor_folder(VENDOR_IMAGE_PATH,$folder);
	

			/* Add data into element */

			$element_cost_array = array();

			for( $x=0;$x<count($this->input->post('width'));$x++ ) {

				if($this->input->post('width')[$x] != '' || $this->input->post('width')[$x] != 0) {

					$exp_element_type = explode('@', $this->input->post('element_type')[$x]);

					$get_element_cost = $this->projects_model->get_element_type($this->input->post('vendor_id'), $exp_element_type[3]);

					$element = get_row('element_master', $exp_element_type[3], 'element_id');

					$this->db->trans_start();
					$WHERE = array(
						'element_id'=>@$exp_element_type[3],
						'project_id'=>$this->input->post('project_id'),
						'vendor_id'=>$this->input->post('vendor_id'),
						'store_id'=>$this->input->post('store_id'),
						'element_type'=>$this->session->userdata('role'),
						'type_element'=>@$exp_element_type[2],
					);
					$this->db->where($WHERE);
					$this->db->delete('element_area');

					$element_cost_array[] = array(

						'element_id' => @$exp_element_type[3],

						'project_id' => $this->input->post('project_id'),

						'vendor_id'  => $this->input->post('vendor_id'),

						'store_id'   => $this->input->post('store_id'),

						'element_rate' => $get_element_cost['element_rate'],

						'element_rate_inches' => $get_element_cost['element_rate_inches'],

						'added_by' => $this->session->userdata('role'),

						'element_type' => @$exp_element_type[2],

						'type_element' => $element['element_type'],

						'width'     => $this->input->post('width')[$x],

						'height'    => $this->input->post('height')[$x],

						'area'      => $this->input->post('total_size')[$x],

						'img1'      => $this->input->post('imgl')[$x],

						'img2'      => $this->input->post('imgr')[$x],

						'img3'      => $this->input->post('imgt')[$x],

						'amount'    => $this->input->post('total_size')[$x] * $get_element_cost['element_rate'],

						'status'    => 1,

						'created_at' => date('Y-m-d'),

					);

				}

			}
		

			$cost = $this->db->insert_batch('element_area', $element_cost_array);

		

            $get_store_info = $this->get_store_info($this->input->post('store_id'));

            $get_user_info = $this->get_vendor_info($this->input->post('vendor_id'));

           

			/* Update project vendor mapping */

			$vendor_mapping_array = array(

			    'store_name'=>$get_store_info['store_name'],

			    'add_line1'=>$get_store_info['address'],

			    'city'=>@$get_store_info['city'],

			    'state'=>@$get_store_info['state'],

			    'pincode'=>@$get_store_info['pincode'],

			    'contact_person_name'=>$get_store_info['contact_person'],

			    'contact_mob'=>$get_store_info['contact_no'],

			    'vendor_name'=>$get_user_info['name'],

			    'recce_certificate' => $this->input->post('recce_certificate'),

				'permission' => $this->input->post('permission'),

				'pre_value' => $this->input->post('total_cost'),

				'recce_status' => 'Completed', 

				'vernacular_language' => $this->input->post('vernacular_language'),

				'signage_board_used' => $this->input->post('signage_board_used'),

				'existing_boards' => $this->input->post('existing_boards'),

				'recce_submit_date' => date('Y-m-d H:i:s'),

				'installed_isb_element'=>$this->input->post('isb_element'),

				'recce_submit_comment' => $this->input->post('comment'),

				'status' => 1,

				

			);
		 
			$this->db->where('element_1', $this->input->post('element_id'));

			$this->db->where('vendor_1', $this->input->post('vendor_id'));

			$this->db->where('project_id', $this->input->post('project_id'));

			$this->db->where('store_id', $this->input->post('store_id'));

			$this->db->update('projects_vendor_mapping', $vendor_mapping_array);





			$WHERE2 = array(
				'element_id'=>$this->input->post('element_id'),
				'project_id'=>$this->input->post('project_id'),
				'vendor_id'=>$this->input->post('vendor_id'),
				'store_id'=>$this->input->post('store_id')
			);
			$this->db->where($WHERE2);
			$this->db->delete('project_store_sub_element');

			/* Add data into project store sub element */

			$store_sub_element_cost_array = array(

					'project_id' => $this->input->post('project_id'),

					'vendor_id'  => $this->input->post('vendor_id'),

					'store_id'   => $this->input->post('store_id'),               

					'element_id' => $this->input->post('element_id'),

					/*'element_rate' => $element_cost['element_rate'],

					'element_rate_inches' => $element_cost['element_rate_inches'],*/

					'signoge_cost' => $this->input->post('signoge_cost'),

					'isb_cost' => !empty($this->input->post('isb_cost')) ? $this->input->post('isb_cost'):0,

					'angle_cost' => 0,

					'scaffolding' => 0,

					'spider' => 0,

					'transport' => 0,

					'installation_cost' => 0,

					'reccee_charge' => !empty($this->input->post('recce_charge')) ? $this->input->post('recce_charge') :0,

					'added_by' => $this->session->userdata('role'),

					'recce_certificate' => $this->input->post('recce_certificate'),

					'status' => 1,

					'created_at' => date('Y-m-d'),

					

			);

			

			

			$element_cost = $this->db->insert('project_store_sub_element', $store_sub_element_cost_array);

			$element_cost_id = $this->db->insert_id();

			

			$this->create_project_logs($this->input->post('project_id'),$this->input->post('store_id'),$this->session->userdata('user_id'),$this->session->userdata('name'),$this->session->userdata('role'),1,$this->input->post('comment'));

			$this->db->trans_complete();
			if ($this->db->trans_status() === TRUE) {

			if($element_cost_id != '') {

				return $element_cost_id;

			}
		}else{
			return false;
		}
		

	}


	function element_area($element_id, $vendor_id, $project_id, $store_id) {

		$this->db->where('element_id', $element_id);

		$this->db->where('vendor_id', $vendor_id);

		$this->db->where('project_id', $project_id);

		$this->db->where('store_id', $store_id);

		$query = $this->db->get('element_area');

		if( $query->num_rows() > 0 ){

			$result = $query->row_array();

			return $result;

		}

		return false;

	}



	function projects_vendor_mapping($vendor_id, $project_id, $store_id) {

		$this->db->where('vendor_1', $vendor_id);

		$this->db->where('project_id', $project_id);

		$this->db->where('store_id', $store_id);

		$query = $this->db->get('projects_vendor_mapping');

		if( $query->num_rows() > 0 ){

			$result = $query->row_array();

			return $result;

		}

		return false;

	}



	function project_store_sub_element($vendor_id, $project_id, $store_id) {

		$this->db->where('vendor_id', $vendor_id);

		$this->db->where('project_id', $project_id);

		$this->db->where('store_id', $store_id);

		$query = $this->db->get('project_store_sub_element');

		if( $query->num_rows() > 0 ){

			$result = $query->row_array();

			return $result;

		}

		return false;

	}





	function update_recce_data_by_tmm() {

	    

		//============ create branch folder ==============

		header('Content-Type: application/json');



		$vendor_id  = $this->input->post('vendor_id');

		$store_id   = $this->input->post('store_id');

		$project_id = $this->input->post('project_id');

		//$element_id = $this->input->post('element_id');

		

		/* Keep update history before update */

		$element_area_json = array();

		for( $x=0;$x<count($this->input->post('width'));$x++ ) {

			$exp_element_type = explode('@', $this->input->post('element_type')[$x]);			

			$element_area_json[] = $this->element_area($exp_element_type[3], $vendor_id, $project_id, $store_id);

		}



		$projects_vendor_mapping_json   = $this->projects_vendor_mapping($vendor_id, $project_id, $store_id);

		$project_store_sub_element_json = $this->project_store_sub_element($vendor_id, $project_id, $store_id);

		

		$update_recce_json = array(

				'vendor_id' => $vendor_id,

				'store_id' => $store_id,

				'project_id' => $project_id,

				'element_id' => '',

				'old_element_area' => json_encode($element_area_json, JSON_UNESCAPED_SLASHES),

				'old_projects_vendor_mapping' => json_encode($projects_vendor_mapping_json, JSON_UNESCAPED_SLASHES),

				'old_project_store_sub_element' => json_encode($project_store_sub_element_json, JSON_UNESCAPED_SLASHES),

				'create_date' => date('Y-m-d'),

				'create_time' => date('H:i:s'));

		/* Keep update history */	

		

		$this->db->insert('update_recce', $update_recce_json);





        /*-----------Update Element Area by Admin-----------*/

        $element_cost_array = array();

		for( $x=0; $x<count($this->input->post('element_id')); $x++ ) {

		    

            	$exp_element_type = explode('@', $this->input->post('element_type')[$x]);



            	$get_element_cost = $this->projects_model->get_element_type($this->input->post('vendor_id'), $exp_element_type[3]);

				$element = get_row('element_master', $exp_element_type[3], 'element_id');

            	

    			$element_cost_array[] = array(

    			    'id' => $this->input->post('element_id')[$x],

    				'element_rate' => $get_element_cost['element_rate'],

    				'element_rate_inches' => $get_element_cost['element_rate_inches'],

    				'width' => $this->input->post('width')[$x],

    				'height' => $this->input->post('height')[$x],

    				'area' => $this->input->post('total_size')[$x],

    				'amount' => $this->input->post('total_size')[$x] * $get_element_cost['element_rate'],

    			);

		}

		$cost = $this->db->update_batch('element_area', $element_cost_array,'id');





		/* Update project vendor mapping */

    		$vendor_mapping_array = array(

    			'pre_value' => $this->input->post('total_cost'),

    			

    		);

    		$this->db->where('vendor_1', $vendor_id);

    		$this->db->where('project_id', $project_id);

    		$this->db->where('store_id', $store_id);

    		$this->db->update('projects_vendor_mapping', $vendor_mapping_array);

    		



		/* Add data into project store sub element */

		$store_sub_element_cost_array = array(

			'signoge_cost' => $this->input->post('signoge_cost'),

			'isb_cost' => !empty($this->input->post('isb_cost')) ? $this->input->post('isb_cost'):0,

			'total_cost' => $this->input->post('total_cost'),

			'reccee_charge' => !empty($this->input->post('recce_charge')) ? $this->input->post('recce_charge') :0

		);



		$this->db->where('vendor_id', $vendor_id);

		$this->db->where('project_id', $project_id);

		$this->db->where('store_id', $store_id);

		$this->db->update('project_store_sub_element', $store_sub_element_cost_array);



        $comment = ($this->input->post('comment') != '') ? $this->input->post('comment') : '';

		$this->create_project_logs($project_id,$store_id,$this->session->userdata('user_id'),$this->session->userdata('name'),$this->session->userdata('role'),64,$comment);

		return true;

	}



	function update_recce_data() {

		//debug($_POST);

		$recce_charge = $this->get_recce_charge();

		//============ create branch folder ==============

		header('Content-Type: application/json');



		$vendor_id = $this->input->post('vendor_id');

		$store_id = $this->input->post('store_id');

		$project_id = $this->input->post('project_id');

		$element_id = $this->input->post('element_id');

		$folder = 'EXIDE_'.$vendor_id;

		create_vendor_folder(VENDOR_IMAGE_PATH,$folder);



		/* Keep update history before update */

		$element_area_json = array();

		for( $x=0;$x<count($this->input->post('width'));$x++ ) {

			$element_area_json[] = $this->element_area($element_id, $vendor_id, $project_id, $store_id);            

		}

		$projects_vendor_mapping_json = $this->projects_vendor_mapping($vendor_id, $project_id, $store_id);

		$project_store_sub_element_json = $this->project_store_sub_element($vendor_id, $project_id, $store_id);

		$update_recce_json = array(

									'vendor_id' => $vendor_id,

									'store_id' => $store_id,

									'project_id' => $project_id,

									'element_id' => $element_id,

									'old_element_area' => json_encode($element_area_json, JSON_UNESCAPED_SLASHES),

									'old_projects_vendor_mapping' => json_encode($projects_vendor_mapping_json, JSON_UNESCAPED_SLASHES),

									'old_project_store_sub_element' => json_encode($project_store_sub_element_json, JSON_UNESCAPED_SLASHES),

									'create_date' => date('Y-m-d'),

									'create_time' => date('H:i:s'));

		$this->db->insert('update_recce', $update_recce_json);

		$last_updated_json_id = $this->db->insert_id();

		/* Keep update history */



		/* Add data into element */

		for( $x=0;$x<count($this->input->post('width'));$x++ ) {



			/* Copy and Paste records from one table to another 

			$this->db->where('id', $this->input->post('projects_vendor_mapping_id')[$x]);

			$query = $this->db->get('element_area');

			$data = $query->row_array();

		    foreach($data as $row) {

		        $this->db->insert('element_area_deleted', $data);

		    }*/



		    /* Delete old records from table 

		    $this->db->where_in('id', $this->input->post('projects_vendor_mapping_id')[$x]);

        	$this->db->delete('element_area');*/



        	$exp_element_type = explode('@', $this->input->post('element_type')[$x]);

        	$get_element_cost = $this->projects_model->get_element_cost_new($project_id,$vendor_id, $exp_element_type[2]);

			$y = 1;

			$element_cost_array = array(

				'project_id' => $project_id,

				'vendor_id' => $vendor_id,

				'store_id' => $store_id,               

				'element_id' => $exp_element_type[3],

				'element_rate' => $get_element_cost['element_rate'],

				'added_by' => $this->session->userdata('role'),

				'element_type' => $exp_element_type[2],

				'width' => $this->input->post('width')[$x],

				'height' => $this->input->post('height')[$x],

				'area' => $this->input->post('total_size')[$x],

				'img1' => $this->input->post('imgl')[$x],

				'img2' => $this->input->post('imgr')[$x],

				'img3' => $this->input->post('imgt')[$x],

				'amount' => $this->input->post('total_size')[$x] * $get_element_cost['element_rate'],

				'status' => 1,

				'created_at' => date('Y-m-d'),

				

			);

			$cost = $this->db->insert('element_area', $element_cost_array);

			$cost_id = $this->db->insert_id();

			$y++;			

		}



		/* Update project vendor mapping */

		$recce_value_vendor = $this->projects_model->get_pre_value($store_id,$vendor_id,$project_id);



		$vendor_mapping_array = array(

			'recce_value_by_vendor' => $recce_value_vendor,

			'pre_value' => $this->input->post('total_cost'),

			'status' => '62',

			'comments' => $this->input->post('approve_with_changes_comment'),

			'additional_branding' => $this->input->post('additional_branding'),

			'recce_approval_comment' => $this->input->post('comment'),

			'recce_approval_date' => date('Y-m-d H:i:s'),

			

		);

		//$this->db->where('element_1', $element_id);

		$this->db->where('vendor_1', $vendor_id);

		$this->db->where('project_id', $project_id);

		$this->db->where('store_id', $store_id);

		$this->db->update('projects_vendor_mapping', $vendor_mapping_array);



		/* Add data into project store sub element */

		$store_sub_element_cost_array = array(

				'project_id' => $project_id,

				'vendor_id' => $vendor_id,

				'store_id' => $store_id,               

				'element_id' => $element_id,

				//'element_rate' => $this->input->post('element_rate'),

				'signoge_cost' => $this->input->post('signoge_cost'),

				'angle_cost' => $this->input->post('angle_cost'),

				'scaffolding' => $this->input->post('scaffolding'),

				'spider' => "",

				'transport' => $this->input->post('transport'),

				'installation_cost' => $this->input->post('installation_cost'),

				'reccee_charge' => $recce_charge,

				'added_by' => $this->session->userdata('role'),

				//'recce_certificate' => $this->input->post('recce_certificate'),

				'status' => 1,

				'created_at' => date('Y-m-d'),

				

			);

		$store_sub_element = $this->db->insert('project_store_sub_element', $store_sub_element_cost_array);

		$store_sub_element_id = $this->db->insert_id();



		/*$this->db->where('element_id', $element_id);

		$this->db->where('vendor_id', $vendor_id);

		$this->db->where('project_id', $project_id);

		$this->db->where('store_id', $store_id);

		$this->db->update('project_store_sub_element', $store_sub_element_cost_array);*/



		/*$element_cost_id = $this->db->insert_id();*/

		$element_cost_id = $this->get_project_store_sub_element_id($element_id, $vendor_id, $project_id, $store_id);



		/* Keep update history after update */

		$element_area_json = array();

		for( $x=0;$x<count($this->input->post('width'));$x++ ) {

			$element_area_json[] = $this->element_area($element_id, $vendor_id, $project_id, $store_id);            

		}

		$projects_vendor_mapping_json = $this->projects_vendor_mapping($vendor_id, $project_id, $store_id);

		$project_store_sub_element_json = $this->project_store_sub_element($vendor_id, $project_id, $store_id);

		$update_recce_json = array(

									'vendor_id' => $vendor_id,

									'store_id' => $store_id,

									'project_id' => $project_id,

									'element_id' => $element_id,

									'new_element_area' => json_encode($element_area_json, JSON_UNESCAPED_SLASHES),

									'new_projects_vendor_mapping' => json_encode($projects_vendor_mapping_json, JSON_UNESCAPED_SLASHES),

									'new_project_store_sub_element' => json_encode($project_store_sub_element_json, JSON_UNESCAPED_SLASHES),

									'create_date' => date('Y-m-d'),

									'create_time' => date('H:i:s'));



		$this->db->where('id', $last_updated_json_id);

		$this->db->update('update_recce', $update_recce_json);

		/* Keep update history */



		$this->create_project_logs($project_id,$store_id,$this->session->userdata('user_id'),$this->session->userdata('name'),$this->session->userdata('role'),62,'');



		if($element_cost_id != '') {

			return $element_cost_id;

		}

		return false;



	}



	function project_data($id){

		$this->db->where('project_id', $id);

		$query = $this->db->get('projects');

		if( $query->num_rows() > 0 ){

			$result = $query->row_array();

			return $result;

		}

		return false;

	}



	function get_element_detail_of_project(){

		$this->db->select('id,element_id,element_type,width,height,area,img1,img2,img3,type_element');

		$this->db->where('store_id',custom_decode($this->uri->segment(3)));

		$this->db->where('vendor_id',custom_decode($this->uri->segment(4)));

		$this->db->where('project_id',custom_decode($this->uri->segment(5)));

		$this->db->where('added_by', 'vendor');

		$query = $this->db->get('element_area');

		

		if( $query->num_rows() > 0 ){

			$result = $query->result_array();

			return $result;

		}

		return false;

	}



	function get_element_detail_of_project_admin(){

		$this->db->select('id,element_type,type_element,width,height,area,img1,img2,img3');

		$this->db->where('store_id',custom_decode($this->uri->segment(3)));

		$this->db->where('vendor_id',custom_decode($this->uri->segment(4)));

		$this->db->where('project_id',custom_decode($this->uri->segment(5)));

		$this->db->where('added_by', 'admin');

		$query = $this->db->get('element_area');

		if( $query->num_rows() > 0 ){

			$result = $query->result_array();

			return $result;

		}

		return false;

	}



	function get_subelement_detail_of_project(){

		$this->db->select('id,signoge_cost,isb_cost,angle_cost,scaffolding,transport,recce_certificate,reccee_charge,installation_cost');

		$this->db->where('store_id',custom_decode($this->uri->segment(3)));

		$this->db->where('vendor_id',custom_decode($this->uri->segment(4)));

		$this->db->where('project_id',custom_decode($this->uri->segment(5)));

		$this->db->where('added_by', 'vendor');

		$query = $this->db->get('project_store_sub_element');

		if( $query->num_rows() > 0 ){

			$result = $query->result_array();

			return $result;

		}

		return false;

	}



	function get_subelement_detail_of_project_for_admin(){

		$this->db->select('id,signoge_cost,angle_cost,scaffolding,transport,recce_certificate,reccee_charge,installation_cost');

		$this->db->where('store_id',custom_decode($this->uri->segment(3)));

		$this->db->where('vendor_id',custom_decode($this->uri->segment(4)));

		$this->db->where('project_id',custom_decode($this->uri->segment(5)));

		$this->db->where('added_by', 'admin');

		$query = $this->db->get('project_store_sub_element');

		if( $query->num_rows() > 0 ){

			$result = $query->result_array();

			return $result;

		}

		return false;

	}



	function get_subelement_detail_of_project_admin(){

		$this->db->select('id,signoge_cost,angle_cost,scaffolding,transport,recce_certificate,reccee_charge,installation_cost');

		$this->db->where('store_id',custom_decode($this->uri->segment(3)));

		$this->db->where('vendor_id',custom_decode($this->uri->segment(4)));

		$this->db->where('project_id',custom_decode($this->uri->segment(5)));

		$this->db->where('added_by', 'admin');

		$query = $this->db->get('project_store_sub_element');

		if( $query->num_rows() > 0 ){

			$result = $query->result_array();

			return $result;

		}

		return false;

	}



	function get_subtotal_detail_of_project(){

		$this->db->select('pre_value,status,vernacular_language,existing_boards,signage_board_used,proposed_signage,proposed_isb,proposed_isb_element,estimated_expenses,proposed_signage_id');

		$this->db->where('store_id',custom_decode($this->uri->segment(3)));

		$this->db->where('project_id',custom_decode($this->uri->segment(5)));

		$query = $this->db->get('projects_vendor_mapping');

		if( $query->num_rows() > 0 ){

			$result = $query->row_array();

			return $result;

		}

		return false;

	}

	



    /*------------Best case ------------------*/

	function change_status(){

		if($this->input->post('action') == 3){
           
			/*=========== Get Old data =====*/
			// store sub element list 
			$json_sub_element = "";
			$this->db->select('*');
			$this->db->where('project_id',$this->input->post('pr'));
			$this->db->where('store_id',$this->input->post('st'));
			$query = $this->db->get('project_store_sub_element');
			if( $query->num_rows() > 0 ){
				$result = $query->result_array();
				$json_sub_element = json_encode($result);
			}

			// store sub element list

			// Element area List

			$json_element_area_list = "";

			$this->db->select('*');

			$this->db->where('project_id',$this->input->post('pr'));

			$this->db->where('store_id',$this->input->post('st'));

			$query = $this->db->get('element_area');

			if( $query->num_rows() > 0 ){

				$result = $query->result_array();

				$json_element_area_list = json_encode($result);

			}

			// Element area List



			// supporting image List

			$json_image_list = "";

			$this->db->select('*');

			$this->db->where('project_id',$this->input->post('pr'));

			$this->db->where('store_id',$this->input->post('st'));

			$this->db->where('vendor_id',$this->input->post('vr'));

			$query = $this->db->get('project_supporting_images');

			if( $query->num_rows() > 0 ){

				$result = $query->result_array();

				$json_image_list = json_encode($result);

			}

			// supporting image List



			// Make reallocated Logs

			$in_array = array(

				'element_area_list'=>$json_element_area_list,

				'sub_element_list'=>$json_sub_element,

				'supporting_images'=>$json_image_list,

				'project_id'=>$this->input->post('pr'),

				'vendor_id'=>$this->input->post('vr'),

				'store_id'=>$this->input->post('st'),

				'created_at'=>date("Y-m-d H:i:s")

			);



			$this->db->insert('reallocated_logs',$in_array);

			// Make reallocated Logs



			// update project_vendor_mapping

			$up_array = array(

				'recce_submit_comment'=>"",

				'recce_submit_date'=>"0000-00-00 00:00:00",

				'recce_approval_comment'=>"",

				'recce_approval_date'=>"0000-00-00 00:00:00",

				'recce_status'=>"",
				'status'=>0,
                'po_number'=>"",
				'recce_submit_comment'=>""

			);

			$this->db->where('project_id',$this->input->post('pr'));

			$this->db->where('vendor_1',$this->input->post('vr'));

			$this->db->where('store_id',$this->input->post('st'));

			$this->db->update('projects_vendor_mapping',$up_array);
           
			// update project_vendor_mapping 





			// Delete data



			$this->db->where('store_id',$this->input->post('st'));

			$this->db->where('project_id',$this->input->post('pr'));

			$this->db->delete('project_store_sub_element');



			$this->db->where('store_id',$this->input->post('st'));

			$this->db->where('project_id',$this->input->post('pr'));

			$this->db->delete('element_area');



			$up_array = array(

				'status'=>0

			);



			$comment = ($this->input->post('comment') != '') ? $this->input->post('comment') : '';



			$this->db->where('project_id',$this->input->post('pr'));

			$this->db->where('store_id',$this->input->post('st'));

			$this->db->update('projects_vendor_mapping',$up_array);

			

			$this->create_project_logs($this->input->post('pr'),$this->input->post('st'),$this->session->userdata('user_id'),$this->session->userdata('name'),$this->session->userdata('role'),0,$comment);

			return true;

		} else {

			for( $x=0;$x<count($this->input->post('art_file'));$x++ ) {

				$up_array = array(

					'project_id' => $this->input->post('pr'),

					'user_id' => $this->session->userdata('user_id'),

					'store_id' => $this->input->post('st'), 

					'vendor_id' => $this->input->post('vr'),

					'picture' => $this->input->post('art_file')[$x],

					'status' => 1,

					'created_at' => date('Y-m-d'),

					'type' => 'art_file'

					

				);

				$art_file = $this->db->insert('project_supporting_images', $up_array);

			}

            /*============Update Artwork to project vendor mapping==========*/

            if( count($this->input->post('art_file')) > 0 ) {

                $up_art = array(

                    'art_work'=>$this->input->post('art_file')[0],

                    'art_work2'=>$this->input->post('art_file')[1],

                    'art_work3'=>$this->input->post('art_file')[2],

                    );

                $this->db->where('project_id',$this->input->post('pr'));

				$this->db->where('vendor_1',$this->input->post('vr'));

				$this->db->where('store_id',$this->input->post('st'));    

                $this->db->update('projects_vendor_mapping',$up_art);

            }


            // delete if proof re-uploaded
                    if($this->input->post('action') == 17){
                        $project_id = $this->input->post('pr');
                        $store_id = $this->input->post('st');
                        $vendor_id = $this->input->post('vr');
                        $this->delete_files($project_id,$store_id,$vendor_id,"fileproof_file");
                        $this->delete_files($project_id,$store_id,$vendor_id,"dep_proof");
                       
                    }
            // end
        
        
			if($this->input->post('fileproof_file') != '') {

				$up_array = array(

					'project_id' => $this->input->post('pr'),

					'user_id' => $this->session->userdata('user_id'),

					'store_id' => $this->input->post('st'), 

					'vendor_id' => $this->input->post('vr'),

					'picture' => $this->input->post('fileproof_file'),

					'status' => 1,

					'created_at' => date('Y-m-d'),

					'type' => 'fileproof_file'

					

				);

				$fileproof_file = $this->db->insert('project_supporting_images', $up_array);



			}

			for( $x=0;$x<count($this->input->post('dep_proof'));$x++ ) {

				$up_array = array(

					'project_id' => $this->input->post('pr'),

					'user_id' => $this->session->userdata('user_id'),

					'store_id' => $this->input->post('st'), 

					'vendor_id' => $this->input->post('vr'),

					'picture' => $this->input->post('dep_proof')[$x],

					'status' => 1,

					'created_at' => date('Y-m-d'),

					'type' => 'dep_proof'

					

				);

				$dep_proof = $this->db->insert('project_supporting_images', $up_array);

			}

			for( $x=0;$x<count($this->input->post('isb_dep_proof'));$x++ ) {

				$up_array = array(

					'project_id' => $this->input->post('pr'),

					'user_id' => $this->session->userdata('user_id'),

					'store_id' => $this->input->post('st'), 

					'vendor_id' => $this->input->post('vr'),

					'picture' => $this->input->post('isb_dep_proof')[$x],

					'status' => 1,

					'created_at' => date('Y-m-d'),

					'type' => 'isb_dep_proof'

					

				);

				$dep_proof = $this->db->insert('project_supporting_images', $up_array);

			}

			/*============Update Deployment to project vendor mapping==========*/

            if( count($this->input->post('dep_proof')) > 0 ) {

                $up_art = array(

                    'deployment_proof'=>$this->input->post('fileproof_file'),

                    'dep_proof1'=>$this->input->post('dep_proof')[0],

                    'dep_proof2'=>$this->input->post('dep_proof')[1],

                    'dep_proof3'=>$this->input->post('dep_proof')[2],

                    );

                $this->db->where('project_id',$this->input->post('pr'));

				$this->db->where('vendor_1',$this->input->post('vr'));

				$this->db->where('store_id',$this->input->post('st'));    

                $this->db->update('projects_vendor_mapping',$up_art);

            }

			/*echo $this->db->last_query();

			exit();*/

			/*---------- GRN Uploaded By TMM-----------------------*/
			if($this->input->post('action') == 10) {

				$up_array = array(
					'status' => $this->input->post('action'),
					'grn_number'=> $this->input->post('grn_number'),
					'comments'=>$this->input->post('comment'),
					'grn_upload_date'=>date('Y-m-d')
				);
              
				$this->db->where('project_id',$this->input->post('pr'));
				$this->db->where('store_id',$this->input->post('st'));
				$this->db->update('projects_vendor_mapping',$up_array);

			/*------FTR Uploaded by TMM-------*/	
			}elseif($this->input->post('action') == 11) {

				$up_array = array(
					'status' => $this->input->post('action'),
					'ftr_number'=> $this->input->post('ftr_number'),
					'comments'=>$this->input->post('comment'),
					'ftr_upload_date'=>date('Y-m-d')
				);

				$this->db->where('project_id',$this->input->post('pr'));
				$this->db->where('store_id',$this->input->post('st'));
				$this->db->update('projects_vendor_mapping',$up_array);

			/*------Deployment Not Approved by cmm support 1 -------*/	
			} elseif($this->input->post('action') == 13) {

				$up_array = array(
    				'status' => $this->input->post('action'),
    				'comments' => $this->input->post('comment'),
    				'dep_not_approved' => $this->input->post('comment'),
    				'dep_approval_date' => date('Y-m-d H:i:s'),
				);

				$store_id = $this->input->post('st');
				$vendor_id = $this->input->post('vr');
				$project_id = $this->input->post('pr');

				$this->db->where('project_id',$this->input->post('pr'));
				$this->db->where('store_id',$this->input->post('st'));
				$this->db->update('projects_vendor_mapping',$up_array);
				$this->delete_files($project_id,$store_id,$vendor_id,"fileproof_file");
				$this->delete_files($project_id,$store_id,$vendor_id,"dep_proof");

			}elseif($this->input->post('action') == 19) {

				$up_array = array(
    				'status' => $this->input->post('action'),
    				'comments' => $this->input->post('comment'),
    				'dep_not_approved' => $this->input->post('comment'),
    				'dep_approval_date_tmm_national' => date('Y-m-d H:i:s'),
				);

				$store_id = $this->input->post('st');
				$vendor_id = $this->input->post('vr');
				$project_id = $this->input->post('pr');

				$this->db->where('project_id',$this->input->post('pr'));
				$this->db->where('store_id',$this->input->post('st'));
				$this->db->update('projects_vendor_mapping',$up_array);
				$this->delete_files($project_id,$store_id,$vendor_id,"fileproof_file");
				$this->delete_files($project_id,$store_id,$vendor_id,"dep_proof");

			}elseif($this->input->post('action') == 23) {

				$up_array = array(
    				'status' => $this->input->post('action'),
    				'comments' => $this->input->post('comment'),
    				'dep_not_approved' => $this->input->post('comment'),
    				'dep_approval_date_cmo_support1' => date('Y-m-d H:i:s'),
				);

				$store_id = $this->input->post('st');
				$vendor_id = $this->input->post('vr');
				$project_id = $this->input->post('pr');

				$this->db->where('project_id',$this->input->post('pr'));
				$this->db->where('store_id',$this->input->post('st'));
				$this->db->update('projects_vendor_mapping',$up_array);
				$this->delete_files($project_id,$store_id,$vendor_id,"fileproof_file");
				$this->delete_files($project_id,$store_id,$vendor_id,"dep_proof");

			}elseif($this->input->post('action') == 24) {

				$up_array = array(
    				'status' => $this->input->post('action'),
    				'comments' => $this->input->post('comment'),
    				'is_denied' => 1
				);

				$store_id = $this->input->post('st');
				$vendor_id = $this->input->post('vr');
				$project_id = $this->input->post('pr');

				$this->db->where('project_id',$this->input->post('pr'));
				$this->db->where('store_id',$this->input->post('st'));
				$this->db->update('projects_vendor_mapping',$up_array);
			

			} elseif($this->input->post('action') == 12) {

				$unq_ref_id = $this->input->post('vr').'_'.date('Ymdhis');

				$up_array = array(
    				'status' => $this->input->post('action'),
    				'comments' => $this->input->post('comment'),
    				'dep_approval_date' => date('Y-m-d H:i:s'),
    				'unique_ref_id' => $unq_ref_id,
    				'deployment_approval_pdf' => base_url("data/vendors/pdf/$unq_ref_id.pdf")
				);

				/*$this->db->where('project_id',$this->input->post('pr'));
				$this->db->where('store_id',$this->input->post('st'));
				$this->db->update('projects_vendor_mapping',$up_array);*/

				/*--------Insert Data in store_audit_branding_details-----------*/
				$store_data = get_row('store_master',$this->input->post('st'),'store_uniqueID');

				//$pro_vndor_mapp_data = get_row('store_master',$this->input->post('st'),'store_uniqueID');
		        /*$this->db->select('id,proposed_signage,proposed_isb');
        		$this->db->where('brand', $this->session->userdata('brand'));
        		$this->db->where('project_id',$this->input->post('pr'));
				$this->db->where('store_id',$this->input->post('st'));
        		$query = $this->db->get('projects_vendor_mapping');
        		$result = $query->row_array();*/

        		$txn = txn_id('DPA');
        		$this->db->select('*');
        		$this->db->where('store_id',$this->input->post('st'));
        		$this->db->where('vendor_id',$this->input->post('vr'));
        		$this->db->where('project_id',$this->input->post('pr'));
        		$this->db->where('added_by', 'vendor');
        		$query = $this->db->get('element_area');

        		if( $query->num_rows() > 0 ){
        			$element = $query->result_array();
            		$insert_array = array();
            		if( is_array($element) && count($element) > 0 ) { 
            		    foreach($element as $elm){ 

            		        

            		        if($elm['type_element'] =='In Shop Branding'){

            		            $type = 'isb_dep_proof';

            		        } else {  $type = 'dep_proof';  }

            		        

            		        $this->db->select('id,picture');

                    		$this->db->where('project_id',$this->input->post('pr'));

            				$this->db->where('store_id',$this->input->post('st'));

            				$this->db->where_in('type',$type);

                    		$query1 = $this->db->get('project_supporting_images');

                    		

                    		$dep_picture = array();

                    		$deployment_images='';

                    		

                    		if( $query1->num_rows() > 0 ){

            			        $res = $query1->result_array();

            			        foreach($res as $data){

            			            $dep_picture[] = $data['picture'];

            			        }

            			        

            			        $deployment_images = json_encode($dep_picture);

            		        }

            		        

    

            				$insert_array[] = array(

                				'store_id' => $this->input->post('st'),

                				'sap_code' => $store_data['sap_code'],

                				'store_name' => $store_data['store_name'],

                				'zone' => $store_data['tmm_zone'],

                				'region'  => $store_data['region'],

                				'address' => $store_data['address'],

                				'pincode' => $store_data['pincode'],

                				'lat' => '',

                				'lon' => '',

                				'position_code' => '',

                				'brand'=> $this->session->userdata('brand'),

                				'txn_id'=> $txn["txn_id"],

                				'branding_type' => $elm['type_element'],

                				'element_name' => $elm['element_type'],

                				'installation_date' => date('Y-m-d'),

                				'deployment_image' => $deployment_images,

                				'data_type'=>'Vmx',

                				'date' => date('Y-m-d'),

            				);

            		    }

    			    }

			    }

			

				$this->db->trans_start();

                $this->db->insert_batch('store_audit_branding_details', $insert_array);

                

                $this->db->where('project_id',$this->input->post('pr'));

				$this->db->where('store_id',$this->input->post('st'));

				$this->db->update('projects_vendor_mapping',$up_array);

				

                $this->db->trans_complete();



                if ($this->db->trans_status() === TRUE) {

                   update_txn($txn["txn_id"], $txn["type"]);

                   $this->create_project_logs($this->input->post('pr'),$this->input->post('st'),$this->session->userdata('user_id'),$this->session->userdata('name'),$this->session->userdata('role'),$this->input->post('action'), @$this->input->post('comment'));

                   return true;

                } else {

                   return false;

                }

           

			}elseif($this->input->post('action') == 18) {

				$unq_ref_id = $this->input->post('vr').'_'.date('Ymdhis');

				$up_array = array(
    				'status' => $this->input->post('action'),
    				'comments' => $this->input->post('comment'),
    				'dep_approval_date_tmm_national' => date('Y-m-d H:i:s'),
    				'unique_ref_id' => $unq_ref_id,
    				'deployment_approval_pdf' => base_url("data/vendors/pdf/$unq_ref_id.pdf")
				);

				/*$this->db->where('project_id',$this->input->post('pr'));
				$this->db->where('store_id',$this->input->post('st'));
				$this->db->update('projects_vendor_mapping',$up_array);*/

				/*--------Insert Data in store_audit_branding_details-----------*/
				$store_data = get_row('store_master',$this->input->post('st'),'store_uniqueID');

				//$pro_vndor_mapp_data = get_row('store_master',$this->input->post('st'),'store_uniqueID');
		        /*$this->db->select('id,proposed_signage,proposed_isb');
        		$this->db->where('brand', $this->session->userdata('brand'));
        		$this->db->where('project_id',$this->input->post('pr'));
				$this->db->where('store_id',$this->input->post('st'));
        		$query = $this->db->get('projects_vendor_mapping');
        		$result = $query->row_array();*/

        		$txn = txn_id('DPA');
        		$this->db->select('*');
        		$this->db->where('store_id',$this->input->post('st'));
        		$this->db->where('vendor_id',$this->input->post('vr'));
        		$this->db->where('project_id',$this->input->post('pr'));
        		$this->db->where('added_by', 'vendor');
        		$query = $this->db->get('element_area');

        		if( $query->num_rows() > 0 ){
        			$element = $query->result_array();
            		$insert_array = array();
            		if( is_array($element) && count($element) > 0 ) { 
            		    foreach($element as $elm){ 

            		        

            		        if($elm['type_element'] =='In Shop Branding'){

            		            $type = 'isb_dep_proof';

            		        } else {  $type = 'dep_proof';  }

            		        

            		        $this->db->select('id,picture');

                    		$this->db->where('project_id',$this->input->post('pr'));

            				$this->db->where('store_id',$this->input->post('st'));

            				$this->db->where_in('type',$type);

                    		$query1 = $this->db->get('project_supporting_images');

                    		

                    		$dep_picture = array();

                    		$deployment_images='';

                    		

                    		if( $query1->num_rows() > 0 ){

            			        $res = $query1->result_array();

            			        foreach($res as $data){

            			            $dep_picture[] = $data['picture'];

            			        }

            			        

            			        $deployment_images = json_encode($dep_picture);

            		        }

            		        

    

            				$insert_array[] = array(

                				'store_id' => $this->input->post('st'),

                				'sap_code' => $store_data['sap_code'],

                				'store_name' => $store_data['store_name'],

                				'zone' => $store_data['tmm_zone'],

                				'region'  => $store_data['region'],

                				'address' => $store_data['address'],

                				'pincode' => $store_data['pincode'],

                				'lat' => '',

                				'lon' => '',

                				'position_code' => '',

                				'brand'=> $this->session->userdata('brand'),

                				'txn_id'=> $txn["txn_id"],

                				'branding_type' => $elm['type_element'],

                				'element_name' => $elm['element_type'],

                				'installation_date' => date('Y-m-d'),

                				'deployment_image' => $deployment_images,

                				'data_type'=>'Vmx',

                				'date' => date('Y-m-d'),

            				);

            		    }

    			    }

			    }

			

				$this->db->trans_start();

                $this->db->insert_batch('store_audit_branding_details', $insert_array);

                

                $this->db->where('project_id',$this->input->post('pr'));

				$this->db->where('store_id',$this->input->post('st'));

				$this->db->update('projects_vendor_mapping',$up_array);

				

                $this->db->trans_complete();



                if ($this->db->trans_status() === TRUE) {

                   update_txn($txn["txn_id"], $txn["type"]);

                   $this->create_project_logs($this->input->post('pr'),$this->input->post('st'),$this->session->userdata('user_id'),$this->session->userdata('name'),$this->session->userdata('role'),$this->input->post('action'), @$this->input->post('comment'));

                   return true;

                } else {

                   return false;

                }

           

			}elseif($this->input->post('action') == 22) {

				$unq_ref_id = $this->input->post('vr').'_'.date('Ymdhis');

				$up_array = array(
    				'status' => $this->input->post('action'),
    				'comments' => $this->input->post('comment'),
    				'dep_approval_date_cmo_support1' => date('Y-m-d H:i:s'),
    				'unique_ref_id' => $unq_ref_id,
    				'deployment_approval_pdf' => base_url("data/vendors/pdf/$unq_ref_id.pdf")
				);

				/*$this->db->where('project_id',$this->input->post('pr'));
				$this->db->where('store_id',$this->input->post('st'));
				$this->db->update('projects_vendor_mapping',$up_array);*/

				/*--------Insert Data in store_audit_branding_details-----------*/
				$store_data = get_row('store_master',$this->input->post('st'),'store_uniqueID');

				//$pro_vndor_mapp_data = get_row('store_master',$this->input->post('st'),'store_uniqueID');
		        /*$this->db->select('id,proposed_signage,proposed_isb');
        		$this->db->where('brand', $this->session->userdata('brand'));
        		$this->db->where('project_id',$this->input->post('pr'));
				$this->db->where('store_id',$this->input->post('st'));
        		$query = $this->db->get('projects_vendor_mapping');
        		$result = $query->row_array();*/

        		$txn = txn_id('DPA');
        		$this->db->select('*');
        		$this->db->where('store_id',$this->input->post('st'));
        		$this->db->where('vendor_id',$this->input->post('vr'));
        		$this->db->where('project_id',$this->input->post('pr'));
        		$this->db->where('added_by', 'vendor');
        		$query = $this->db->get('element_area');

        		if( $query->num_rows() > 0 ){
        			$element = $query->result_array();
            		$insert_array = array();
            		if( is_array($element) && count($element) > 0 ) { 
            		    foreach($element as $elm){ 

            		        

            		        if($elm['type_element'] =='In Shop Branding'){

            		            $type = 'isb_dep_proof';

            		        } else {  $type = 'dep_proof';  }

            		        

            		        $this->db->select('id,picture');

                    		$this->db->where('project_id',$this->input->post('pr'));

            				$this->db->where('store_id',$this->input->post('st'));

            				$this->db->where_in('type',$type);

                    		$query1 = $this->db->get('project_supporting_images');

                    		

                    		$dep_picture = array();

                    		$deployment_images='';

                    		

                    		if( $query1->num_rows() > 0 ){

            			        $res = $query1->result_array();

            			        foreach($res as $data){

            			            $dep_picture[] = $data['picture'];

            			        }

            			        

            			        $deployment_images = json_encode($dep_picture);

            		        }

            		        

    

            				$insert_array[] = array(

                				'store_id' => $this->input->post('st'),

                				'sap_code' => $store_data['sap_code'],

                				'store_name' => $store_data['store_name'],

                				'zone' => $store_data['tmm_zone'],

                				'region'  => $store_data['region'],

                				'address' => $store_data['address'],

                				'pincode' => $store_data['pincode'],

                				'lat' => '',

                				'lon' => '',

                				'position_code' => '',

                				'brand'=> $this->session->userdata('brand'),

                				'txn_id'=> $txn["txn_id"],

                				'branding_type' => $elm['type_element'],

                				'element_name' => $elm['element_type'],

                				'installation_date' => date('Y-m-d'),

                				'deployment_image' => $deployment_images,

                				'data_type'=>'Vmx',

                				'date' => date('Y-m-d'),

            				);

            		    }

    			    }

			    }

			

				$this->db->trans_start();

                $this->db->insert_batch('store_audit_branding_details', $insert_array);

                

                $this->db->where('project_id',$this->input->post('pr'));

				$this->db->where('store_id',$this->input->post('st'));

				$this->db->update('projects_vendor_mapping',$up_array);

				

                $this->db->trans_complete();



                if ($this->db->trans_status() === TRUE) {

                   update_txn($txn["txn_id"], $txn["type"]);

                   $this->create_project_logs($this->input->post('pr'),$this->input->post('st'),$this->session->userdata('user_id'),$this->session->userdata('name'),$this->session->userdata('role'),$this->input->post('action'), @$this->input->post('comment'));

                   return true;

                } else {

                   return false;

                }

           

			} elseif($this->input->post('action') == 6) {

				$up_array = array(

    				'status' => $this->input->post('action'),

    				'comments' => $this->input->post('comment'),

    				'additional_branding' => $this->input->post('additional_branding'),

    				'recce_approval_comment' => $this->input->post('comment'),

    				'recce_approval_date' => date('Y-m-d H:i:s'),

				);

				

				$this->db->where('project_id',$this->input->post('pr'));

				$this->db->where('store_id',$this->input->post('st'));

				$this->db->update('projects_vendor_mapping',$up_array);



            /*------------Recce Approved as provided------------*/

			} elseif($this->input->post('action') == 5) {



			    $recce_value_vendor = $this->projects_model->get_pre_value($this->input->post('st'),$this->input->post('vr'),$this->input->post('pr'));

				$up_array = array(

    				'recce_value_by_vendor' => $recce_value_vendor,

    				'status' => $this->input->post('action'),

    				'comments' => $this->input->post('comment'),

    				'recce_approval_comment' => $this->input->post('comment'),

    				'recce_approval_date' => date('Y-m-d H:i:s'),

				);



				/* Copy and Paste records from one table to same */

				$this->db->where('project_id', $this->input->post('pr'));

				$this->db->where('vendor_id', $this->input->post('vr'));

				$this->db->where('store_id', $this->input->post('st'));

				$query = $this->db->get('element_area');

				$data = $query->result_array();

			    foreach($data as $row) {

			    	$element_arr = array(

    						'project_id' => $row['project_id'],

    						'vendor_id' => $row['vendor_id'],

    						'store_id' => $row['store_id'],

    						'element_id' => $row['element_id'],

    						'added_by' => 'admin',

    						'type_element' => $row['type_element'],

    						'element_type' => $row['element_type'],

    						'width' => $row['width'],

    						'area' => $row['area'],

    						'height' => $row['height'],

    						'element_rate' => $row['element_rate'],

    						'amount' => $row['amount'],

    						'img1' => $row['img1'],

    						'img2' => $row['img2'],

    						'img3' => $row['img3'],

    						'status' => $row['status'],

    						'created_at' => date('Y-m-d H:i:s')

    					);

			        $this->db->insert('element_area', $element_arr);

			    }



			    /* Copy and Paste records from one table to same */

				$this->db->where('project_id', $this->input->post('pr'));

				$this->db->where('vendor_id', $this->input->post('vr'));

				$this->db->where('store_id', $this->input->post('st'));

				$query = $this->db->get('project_store_sub_element');

				$data = $query->result_array();

			    foreach($data as $row) {

			    	$sub_element_arr = array(

    						'project_id' => $row['project_id'],

    						'vendor_id' => $row['vendor_id'],

    						'store_id' => $row['store_id'],

    						'element_id' => $row['element_id'],

    						'added_by' => 'admin',

    						'element_rate' => $row['element_rate'],

    						'signoge_cost' => $row['signoge_cost'],

    						'angle_cost' => $row['angle_cost'],

    						'scaffolding' => $row['scaffolding'],

    						'spider' => $row['spider'],			    						

    						'transport' => $row['transport'],

    						'recce_certificate' => $row['recce_certificate'],

    						'installation_cost' => $row['installation_cost'],

    						'reccee_charge' => $row['reccee_charge'],

    						'status' => $row['status'],

    						'created_at' => date('Y-m-d H:i:s')

    					);



			        $this->db->insert('project_store_sub_element', $sub_element_arr);

			    }

			    

			    $this->db->where('project_id',$this->input->post('pr'));

				$this->db->where('store_id',$this->input->post('st'));

				$this->db->update('projects_vendor_mapping',$up_array);



           
            
            
            /*---------------Deployment done and deployment proof re-uploaded by vendor----------------*/ 

			}elseif($this->input->post('action') == 17){
			    
			        $deduct_installation = $this->does_deduct_installation_exists($this->input->post('pr'), $this->input->post('st'), $this->input->post('vr'));

				if($deduct_installation == false) {
					$up_array = array(
    					'status' => $this->input->post('action'),
    					'comments' => $this->input->post('comment'),
    					'pre_value' => $this->input->post('total_cost'),
    					'deduct_installation' => '',
    					'completion_date' => date('Y-m-d H:i:s'),
    					'deployment_done_comment' => $this->input->post('comment'),
    					'deployment_done_status' => 'Completed',
    					'deployment_date'=>date('Y-m-d')
					);

				} else {
					$up_array = array(
    					'status' => $this->input->post('action'),
    					'comments' => $this->input->post('comment'),
    					'pre_value' => $this->input->post('total_cost') + $deduct_installation,
    					'deduct_installation' => '',
    					'completion_date' => date('Y-m-d H:i:s'),
    					'deployment_done_comment' => $this->input->post('comment'),
    					'deployment_done_status' => 'Completed',
    					'deployment_date'=>date('Y-m-d')
					);

				}

				$this->db->where('project_id',$this->input->post('pr'));
				$this->db->where('store_id',$this->input->post('st'));
				$this->db->update('projects_vendor_mapping',$up_array);
			} 
			/*---------------end proof re-uploaded by vendor----------------*/ 
			
		
		 /*---------------Deployment done and deployment proof uploaded by vendor----------------*/	
			elseif($this->input->post('action') == 8) {

			   

				$deduct_installation = $this->does_deduct_installation_exists($this->input->post('pr'), $this->input->post('st'), $this->input->post('vr'));

				if($deduct_installation == false) {

					$up_array = array(

    					'status' => $this->input->post('action'),

    					'comments' => $this->input->post('comment'),

    					'pre_value' => $this->input->post('total_cost'),

    					'deduct_installation' => '',

    					'completion_date' => date('Y-m-d H:i:s'),

    					'deployment_done_comment' => $this->input->post('comment'),

    					'deployment_done_status' => 'Completed',
    					'deployment_date'=>date('Y-m-d') // 2022-02-09

					);

				} else {

					$up_array = array(

    					'status' => $this->input->post('action'),

    					'comments' => $this->input->post('comment'),

    					'pre_value' => $this->input->post('total_cost') + $deduct_installation,

    					'deduct_installation' => '',

    					'completion_date' => date('Y-m-d H:i:s'),

    					'deployment_done_comment' => $this->input->post('comment'),

    					'deployment_done_status' => 'Completed',
    					'deployment_date'=>date('Y-m-d')

					);

				}

				

				$this->db->where('project_id',$this->input->post('pr'));

				$this->db->where('store_id',$this->input->post('st'));

				$this->db->update('projects_vendor_mapping',$up_array);

				

				

			} elseif($this->input->post('action') == 7 && $this->input->post('changed_action') == 'po_done') {

				$up_array = array(

    				'status' => $this->input->post('action'),

    				'po_number' => $this->input->post('po_number'),
    				'po_upload_date'=>date('Y-m-d')

				);

				

				$this->db->where('project_id',$this->input->post('pr'));

				$this->db->where('store_id',$this->input->post('st'));

				$this->db->update('projects_vendor_mapping',$up_array);

				

			/*--------------Deployment issue by Vendor---------------------*/	

			} elseif($this->input->post('action') == 9) {

				$deduct_installation = $this->does_deduct_installation_exists($this->input->post('pr'), $this->input->post('st'), $this->input->post('vr'));

				if($deduct_installation == false) {

					$up_array = array(

					'status' => $this->input->post('action'),

					'comments' => $this->input->post('comment'),

					'deployment_issue' => $this->input->post('deployment_issue'),

					'deployment_issue_remarks' => $this->input->post('comment'),

					'pre_value' => $this->input->post('new_total_cost'),

					'deduct_installation' => $this->input->post('installation_cost')

					);

				}

				

				

				$this->db->where('project_id',$this->input->post('pr'));

				$this->db->where('store_id',$this->input->post('st'));

				$this->db->update('projects_vendor_mapping',$up_array);

					

			} elseif($this->input->post('action') == 4) {

				$up_array = array(

    				'status' => $this->input->post('action'),

    				'comments' => $this->input->post('comment'),

    				'additional_branding' => $this->input->post('additional_branding'),

				);

				

				$this->db->where('project_id',$this->input->post('pr'));

				$this->db->where('store_id',$this->input->post('st'));

				$this->db->update('projects_vendor_mapping',$up_array);

				

            /*----------Recce approved with changes accept by vendor----*/

			} elseif($this->input->post('action') == 14) {

				$up_array = array(

    				'status' => $this->input->post('action'),

    				'comments' => $this->input->post('comment'),

				);

				

				$this->db->where('project_id',$this->input->post('pr'));

				$this->db->where('store_id',$this->input->post('st'));

				$this->db->update('projects_vendor_mapping',$up_array);



			} 

			

			$this->create_project_logs($this->input->post('pr'),$this->input->post('st'),$this->session->userdata('user_id'),$this->session->userdata('name'),$this->session->userdata('role'),$this->input->post('action'), @$this->input->post('comment'));

			

			$myfile = fopen("logs.txt", "a") or die("Unable to open file!");

            fwrite($myfile, "\n". $_POST);

            fclose($myfile);

			return true;

		}

		

	}
// end change_status
	

	

	function create_project_logs($project_id,$store_id,$user_id,$name,$role,$status,$comment=''){

		$ins_array = array(

    			'status_changed'=>$status,

    			'comments'=>$comment,

    			'created_by'=>$role,

    			'user_id'=>$user_id,

    			'name'=>$name,

    			'project_id'=>$project_id,

    			'store_id'=>$store_id,

    			'created_at'=>date("Y-m-d H:i:s")

		);

		$this->db->insert('project_log_history',$ins_array);
        //echo query();
		return true;

	}

	

	/* Create Project edited log in amdin panel*/

	function project_edit_log($project_id,$old_data,$new_data,$user_id){

		$ins_array = array(

			'project_id'=>$project_id,

			'old_data'=>$old_data,

			'new_data'=>$new_data,

			'updated_by'=>$user_id,

			'updated_at'=>date("Y-m-d H:i:s")

		);

		$this->db->insert('project_edit_log',$ins_array);

		return true;

	}

	

	function incurred_expenses($id){

		$this->db->select('sum(pre_value) as incurred_expenses');

		$this->db->where('project_id',$id);

		$this->db->where('status >=',5);

		$this->db->where('brand', $this->session->userdata('brand'));

		$query = $this->db->get('projects_vendor_mapping');

		if( $query->num_rows() > 0 ){

			$result = $query->row_array();

			return $result['incurred_expenses'];

		}

		return false;

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



	function project_element_list($id){

		$this->db->select('project_store_element.element_id,project_store_element.vendor_id,project_store_element.actual_rate,project_store_element.installation_cost,user.name,element_master.element_name');

		$this->db->from('project_store_element');

		$this->db->join('user','user.user_id = project_store_element.vendor_id');

		$this->db->join('element_master','element_master.element_id = project_store_element.element_id');

		$this->db->where('project_store_element.project_id', $id);

		$query = $this->db->get();

		if( $query->num_rows() > 0 ){

			$result = $query->result_array();

			return $result;

		}

		return false;

	}



	function project_store_list($id){

		$this->db->where('project_id', $id);

		$query = $this->db->get('projects_vendor_mapping');

		if( $query->num_rows() > 0 ){

			$result = $query->result_array();

			return $result;

		}

		return false;

	}



	function get_vendor_info($id){

		$this->db->where('user_id', $id);

		$query = $this->db->get('user');

		if( $query->num_rows() > 0 ){

			$result = $query->row_array();

			return $result;

		}

		return false;

	}



	function get_printer_info($id) {

		$this->db->where('user_id', $id);

		$query = $this->db->get('user');

		if( $query->num_rows() > 0 ){

			$result = $query->row_array();

			return $result;

		}

		return false;

	}



	function get_store_info($id) {

		$this->db->where('store_uniqueID', $id);

		$query = $this->db->get('store_master');

		if( $query->num_rows() > 0 ){

			$result = $query->row_array();

			return $result;

		}

		return false;

	}



	function get_subelement_detail($store_id,$vendor_id,$project_id) {

		$this->db->select('signoge_cost,angle_cost,scaffolding,transport,recce_certificate,reccee_charge,installation_cost');

		$this->db->where('store_id',$store_id);

		$this->db->where('vendor_id',$vendor_id);

		$this->db->where('project_id',$project_id);

		$query = $this->db->get('project_store_sub_element');

		if( $query->num_rows() > 0 ){

			$result = $query->result_array();

			return $result;

		}

		return false;

	}



	function get_element_detail($store_id,$vendor_id,$project_id) {

		$this->db->select('width,height,area,img1,img2,img3');

		$this->db->where('store_id',$store_id);

		$this->db->where('vendor_id',$vendor_id);

		$this->db->where('project_id',$project_id);

		$this->db->where('added_by', 'vendor');

		$query = $this->db->get('element_area');

		if( $query->num_rows() > 0 ){

			$result = $query->result_array();

			return $result;

		}

		return false;

	}



	function get_element_detail_admin($store_id,$vendor_id,$project_id) {

		$this->db->select('width,height,area,img1,img2,img3');

		$this->db->where('store_id',$store_id);

		$this->db->where('vendor_id',$vendor_id);

		$this->db->where('project_id',$project_id);

		$this->db->where('added_by', 'admin');

		$query = $this->db->get('element_area');

		if( $query->num_rows() > 0 ){

			$result = $query->result_array();

			return $result;

		}

		return false;

	}

	

	function project_log_history($store_id,$vendor_id,$project_id) {

		$this->db->where('store_id', $store_id);

		//$this->db->where('user_id', $vendor_id);

		$this->db->where('project_id', $project_id);

		$this->db->order_by('id','DESC');

		$query = $this->db->get('project_log_history');

		if( $query->num_rows() > 0 ){

			$result = $query->result_array();

			return $result;

		}

		return false;

	}

	

	function get_project_detail($id){

		$this->db->where('project_id', $id);

		$query = $this->db->get('projects');

		if( $query->num_rows() > 0 ){

			$result = $query->row_array();

			return $result;

		}

		return false;

	}

	

	function duplicate_store($store_id,$project_id){

		$this->db->where('project_id', $project_id);

		$this->db->where('store_id', $store_id);

		$query = $this->db->get('projects_vendor_mapping');

		if( $query->num_rows() > 0 ){

			return true;

		}

		return false;

	}

	

	function store_view_box($id){

		$this->db->select('project_status.graph_status,project_status.fa_icon, count(projects_vendor_mapping.id) as stores ');

		$this->db->from('project_status');

		$this->db->join('projects_vendor_mapping', 'project_status.status_id = projects_vendor_mapping.status', 'left');

		$this->db->where('projects_vendor_mapping.project_id',$id);

		if($this->session->userdata('role') == 'vendor'){

			if($this->session->userdata('user_type') == 'Printer'){

				$this->db->where('projects_vendor_mapping.vendor_2',$this->session->userdata('user_id'));

			}else{

				$this->db->where('projects_vendor_mapping.vendor_1',$this->session->userdata('user_id'));

			}

		}

		$this->db->group_by('project_status.graph_status');

		$query = $this->db->get();

		if( $query->num_rows() > 0 ){

			$result = $query->result_array();

			return $result;

		}

		return false;

	}



	function get_pre_value_of_project(){

		$this->db->select('pre_value');

		$this->db->where('store_id',custom_decode($this->uri->segment(3)));

		$this->db->where('vendor_1',custom_decode($this->uri->segment(4)));

		$this->db->where('project_id',custom_decode($this->uri->segment(5)));

		$query = $this->db->get('projects_vendor_mapping');

		if( $query->num_rows() > 0 ){

			$result = $query->row_array();

			return $result['pre_value'];

		}

		return false;

	}



	function get_recce_value_by_vendor_of_project(){

		$this->db->select('recce_value_by_vendor');

		$this->db->where('store_id',custom_decode($this->uri->segment(3)));

		$this->db->where('vendor_1',custom_decode($this->uri->segment(4)));

		$this->db->where('project_id',custom_decode($this->uri->segment(5)));

		$query = $this->db->get('projects_vendor_mapping');

		if( $query->num_rows() > 0 ){

			$result = $query->row_array();

			return $result['recce_value_by_vendor'];

		}

		return false;

	}



	function get_pre_value($store_id,$vendor_id,$project_id){

		$this->db->select('pre_value');

		$this->db->where('store_id',$store_id);

		$this->db->where('vendor_1',$vendor_id);

		$this->db->where('project_id',$project_id);

		$query = $this->db->get('projects_vendor_mapping');

		if( $query->num_rows() > 0 ){

			$result = $query->row_array();

			return $result['pre_value'];

		}

		return false;

	}



	function get_element_id($element_id, $vendor_id, $project_id, $store_id) {

		$this->db->select('id');

		$this->db->where('element_id', $element_id);

		$this->db->where('vendor_id', $vendor_id);

		$this->db->where('project_id', $project_id);

		$this->db->where('store_id', $store_id);

		$query = $this->db->get('element_area');

		if( $query->num_rows() > 0 ){

			$result = $query->row_array();

			return $result['id'];

		}

		return false;

	}



	function get_project_store_sub_element_id($element_id, $vendor_id, $project_id, $store_id) {

		$this->db->select('id');

		$this->db->where('element_id', $element_id);

		$this->db->where('vendor_id', $vendor_id);

		$this->db->where('project_id', $project_id);

		$this->db->where('store_id', $store_id);

		$query = $this->db->get('project_store_sub_element');

		if( $query->num_rows() > 0 ){

			$result = $query->row_array();

			return $result['id'];

		}

		return false;

	}

	

	function get_vendor_type($vendor_id){

		$this->db->select('user_type');

		$this->db->where('user_id', $vendor_id);

		$query = $this->db->get('user');

		if( $query->num_rows() > 0 ){

			$result = $query->row_array();

			return $result['user_type'];

		}

		return false;

	}

	

	function delete_project_store($store_id,$project_id){

		/*========  Get Old data =====*/

		$this->db->where('project_id',$project_id);

		$this->db->where('store_id',$store_id);

		$query = $this->db->get('projects_vendor_mapping');

		if( $query->num_rows() > 0 ){

			$result = $query->row_array();

			$get_old_data = json_encode($result);

		}

		/*========  Create Logs =====*/

		$ins_array = array(

			'data'=>$get_old_data,

			'deleted_by'=>$this->session->userdata('username'),

			'ip'=> $_SERVER['REMOTE_ADDR'],

			'created_at'=>date("Y-m-d H:i:s")

			);

			

		$this->db->insert('deleted_project_store_logs',$ins_array);

		

		/*========  Delete Old data =====*/

		$this->db->where('project_id',$project_id);

		$this->db->where('store_id',$store_id);

		$is_deleted = $this->db->delete('projects_vendor_mapping');

		if($is_deleted){

			return true;

		}else{

			return false;

		}

		

	}

	

	function does_deduct_installation_exists($project_id,$store_id,$vendor_id) {

		$this->db->select('deduct_installation');

		$this->db->where('project_id', $project_id);

		$this->db->where('store_id', $store_id);

		$this->db->where('vendor_1', $vendor_id);

		$query = $this->db->get('projects_vendor_mapping');

		if( $query->num_rows() > 0 ){

			$result = $query->row_array();

			return $result['deduct_installation'];

		}

		return false;

	}

	

	function vendor_element_rate_list(){

		$this->db->select('project_store_element.vendor_id,project_store_element.element_name,project_store_element.element_id,element_rate_master.element_rate,user.user_id,user.user_type,user.name, element_master.element_type');

		$this->db->from('project_store_element');

		$this->db->join('user', 'user.user_id = project_store_element.vendor_id');

		$this->db->join('element_master', 'element_master.element_id = project_store_element.element_id');

		$this->db->join('element_rate_master', 'element_rate_master.element_id = project_store_element.element_id AND element_rate_master.vendor_id = project_store_element.vendor_id');

		$this->db->group_by('project_store_element.vendor_id , project_store_element.element_name');

		$query = $this->db->get();

		if( $query->num_rows() > 0 ){

			$result = $query->result_array();

			return $result;

		}

		return false;

	}

	

	function project_statuses_list(){

		$this->db->select('*');

		if($this->session->userdata('role') != 'admin') {

			if($this->session->userdata('user_type') == 'Fabricator') {

				$this->db->where('vendor_1', $this->session->userdata('user_id'));

			} elseif($this->session->userdata('user_type') == 'Printer') {

				$this->db->where('vendor_2', $this->session->userdata('user_id'));

			}

		}

		if($this->input->get('project')!=''){

		    $this->db->where('project_id',$this->input->get('project'));

		}

		//$this->db->group_by('store_id');

		$query = $this->db->get('projects_vendor_mapping');

		log_message('error' , "project_statuses_list => " . $this->db->last_query());

		log_message('error' , "projects => " . $this->input->get('project'));

		if( $query->num_rows() > 0 ){

			$result = $query->result_array();

			return $result;

		}

		return false;

	}

	

	function project_element_data($project_id,$store_id){

	    $this->db->where('project_id',$project_id);

	    $this->db->where('store_id',$store_id);

		$query = $this->db->get('element_area');

		if( $query->num_rows() > 0 ){

			$result = $query->result_array();

			return $result;

		}

		return false;

	}

	

	function vendor_name($user_id){

	    $this->db->select('name');

	    $this->db->where('user_id',$user_id);

		$query = $this->db->get('user');

		if( $query->num_rows() > 0 ){

			$result = $query->row_array();

			return $result['name'];

		}

		return false;

	}

	

	function element_name($element_id){

	    $this->db->where('element_id',$element_id);

		$query = $this->db->get('element_master');

		if( $query->num_rows() > 0 ){

			$result = $query->row_array();

			return $result['element_name'];

		}

		return false;

	}

	

	function store_name($store_id){

	    $this->db->where('store_uniqueID',$store_id);

		$query = $this->db->get('store_master');

		if( $query->num_rows() > 0 ){

			$result = $query->row_array();

			return $result['store_name'];

		}

		return false;

	}

	

	function vendor_by_project($id){

	    $this->db->select('pv.vendor_1,usr.name');

	    $this->db->from('projects_vendor_mapping pv');

	    $this->db->join('user usr','usr.user_id = pv.vendor_1');

	    $this->db->where('pv.project_id',$id);

	    $this->db->group_by('pv.vendor_1');

		$query = $this->db->get();

		if( $query->num_rows() > 0 ){

			$result = $query->result_array();

			return $result;

		}

		return false;

	}

	

	function status_by_project($id){

	    $this->db->select('pv.status,p_s.status_id');

	    $this->db->from('projects_vendor_mapping pv');

	    $this->db->join('project_status p_s','p_s.status_id = pv.status');

	    $this->db->where('pv.project_id',$id);

	    $this->db->group_by('pv.status');

		$query = $this->db->get();

		if( $query->num_rows() > 0 ){

			$result = $query->result_array();

			return $result;

		}

		return false;

	}

	

	public function get_next_store($store_id,$project_id,$vendor_id,$status){

	    $this->db->select('store_id');

	    if($project_id!=''){

	    	$this->db->where('project_id',$project_id);

	    }

	    if($vendor_id!=''){

	    	if($vendor_id!='All'){

	    		if($this->session->userdata('role')=='vendor'){

		    	if($this->session->userdata('user_type') == 'Printer'){

		    			$this->db->where('vendor_2',$this->session->userdata('user_id'));

				    }else{

				    	$this->db->where('vendor_1',$this->session->userdata('user_id'));

				   }

			    }else{

			    	if($vendor_id!='' && $vendor_id!='All'){

			    		if($this->session->userdata('user_type') == 'Printer'){

			    			$this->db->where('vendor_2',$vendor_id);

					    }else{

					    	$this->db->where('vendor_1',$vendor_id);

					   }

			    	}

			    }

	    	}

	    }

		if($status!=''){

			if($status!='All'){

				$this->db->where('status',$status);

			}

		}

		$this->db->where('store_id!=',$store_id);

		$this->db->order_by('timestamp','ASC');

		$this->db->limit('1');

		$query = $this->db->get('projects_vendor_mapping');

		if( $query->num_rows() > 0 ){

			$result = $query->row_array();

        	return $result['store_id'];

		}

		return false;

	}

	

	function reallocated_by_vendor($store_id,$project_id){

	        $this->db->where('store_id',$store_id);

			$this->db->where('project_id',$project_id);

			$this->db->delete('project_store_sub_element');

			

			$this->db->where('store_id',$store_id);

			$this->db->where('project_id',$project_id);

			$this->db->delete('element_area');



			$up_array = array(

				'status'=>0

			);



			$comment = 'Reallocated By Vendor';



			$this->db->where('project_id',$project_id);

			$this->db->where('store_id',$store_id);

			$this->db->update('projects_vendor_mapping',$up_array);

			$this->create_project_logs($project_id,$store_id,$this->session->userdata('user_id'),$this->session->userdata('name'),$this->session->userdata('role'),0,$comment);

			return true;

	}



	function get_additional_branding() {

		$query = $this->db->get('additional_branding');

		if($query->num_rows() > 0) {

			$result = $query->result_array();

			return $result; 

		}

	}



	function get_recce_charge() {

		$query = $this->db->get('recce_charge');

		if($query->num_rows() > 0) {

			$result = $query->row_array();

			return $result['charge']; 

		}

	}



	function get_art_work_file($project_id, $vendor_id, $store_id) {

	    $this->db->where('project_id',$project_id);

	    $this->db->where('vendor_id',$vendor_id);

	    $this->db->where('store_id',$store_id);

	    $this->db->where('type', 'art_file');

		$query = $this->db->get('project_supporting_images');

		if( $query->num_rows() > 0 ){

			$result = $query->result_array();

			return $result;

		}

		return false;

	}



	function get_dep_proof_file($project_id, $vendor_id, $store_id) {

		$this->db->where('project_id',$project_id);

	    $this->db->where('vendor_id',$vendor_id);

	    $this->db->where('store_id',$store_id);

	    $this->db->where_in('type', array('dep_proof','isb_dep_proof'));

		$query = $this->db->get('project_supporting_images');

		if( $query->num_rows() > 0 ){

			$result = $query->result_array();

			return $result;

		}

		return false;

	}



	function get_fileproof_file($project_id, $vendor_id, $store_id) {

		$this->db->where('project_id',$project_id);

	    $this->db->where('vendor_id',$vendor_id);

	    $this->db->where('store_id',$store_id);

	    $this->db->where('type', 'fileproof_file');

		$query = $this->db->get('project_supporting_images');

		if( $query->num_rows() > 0 ){

			$result = $query->result_array();

			return $result;

		}

		return false;

	}





	function delete_files($project_id,$store_id,$vendor_id,$type="",$type2=""){

		$up_array = array(

			'type'=>$type.'_history'

		);

		$this->db->where('project_id',$project_id);

	    $this->db->where('vendor_id',$vendor_id);

	    $this->db->where('store_id',$store_id);

	    if($type!=""){

	    	$this->db->where('type',$type);

	    }

	    if($type2!=""){

	    	$this->db->where('type',$type2);

	    }

	    $this->db->update('project_supporting_images',$up_array);
    
	    return true;

	}



	function element_type($element_id){

	    $this->db->where('element_id',$element_id);

		$query = $this->db->get('element_master');

		if( $query->num_rows() > 0 ){

			$result = $query->row_array();

			return $result['element_type'];

		}

		return false;

	}



	function project_element_data_vendor($project_id,$store_id){

	    $this->db->where('project_id',$project_id);

	    $this->db->where('store_id',$store_id);

	    $this->db->where('added_by!=','admin');

		$query = $this->db->get('element_area');

		if( $query->num_rows() > 0 ){

			$result = $query->result_array();

			return $result;

		}

		return false;

	}



	function project_element_data_admin($project_id,$store_id){

	    $this->db->where('project_id',$project_id);

	    $this->db->where('store_id',$store_id);

	    $this->db->where('added_by =','admin');

		$query = $this->db->get('element_area');

		if( $query->num_rows() > 0 ){

			$result = $query->result_array();

			return $result;

		}

		return false;

	}



	function count_vendor_element($project_id,$store_id){

	    $this->db->select('count(*) as total');

	    $this->db->where('store_id',$store_id);

	    $this->db->where('project_id',$project_id);

	    $this->db->where('added_by','vendor');

		$query = $this->db->get('element_area');

		if( $query->num_rows() > 0 ){

			$result = $query->row_array();

			return $result['total'];

		}

		return false;

	}

	

	function count_admin_element($project_id,$store_id){

	    $this->db->select('count(*) as total');

	    $this->db->where('store_id',$store_id);

	    $this->db->where('project_id',$project_id);

	    $this->db->where('added_by','admin');

		$query = $this->db->get('element_area');

		if( $query->num_rows() > 0 ){

			$result = $query->row_array();

			return $result['total'];

		}

		return false;

	}



	function get_vendor($store_id,$project_id){

		$this->db->select('vendor_1');

		$this->db->where('store_id',$store_id);

	    $this->db->where('project_id',$project_id);

	    $query = $this->db->get('projects_vendor_mapping');

		if( $query->num_rows() > 0 ){

			$result = $query->row_array();

			return $result['vendor_1'];

		}

		return false;

	}



	function show_elements($vendor_id, $element_id=NULL) {

		$this->db->select('element_id,element_name,element_type,element_rate,element_rate_inches');

	    $this->db->where('vendor_id',$vendor_id);

	    

	    if(($this->session->userdata('brand') == 'Exide') && ($element_id == 'ELM2806211000001')){

	        $proposedElement = array('ELM2806211000001','ELM0309211000022');

	        

	        $this->db->where_in('element_id',$proposedElement);

	    } else {

            if($element_id!=NULL){
                $this->db->where('element_id',$element_id);
            }
	        

	    }

	    $this->db->where('brand',$this->session->userdata('brand'));

	    $this->db->where('status','Active');

		$query = $this->db->get('element_rate_master');
        
		if( $query->num_rows() > 0 ){

			return $query->result_array();


		}
        
		return array();

	}

	
	function show_elements_only_inshop($vendor_id, $element_id=NULL) {

		$this->db->select('element_id,element_name,element_type,element_rate,element_rate_inches');

	    $this->db->where('vendor_id',$vendor_id);

	    

	    if(($this->session->userdata('brand') == 'Exide') && ($element_id == 'ELM2806211000001')){

	        $proposedElement = array('ELM2806211000001','ELM0309211000022');

	        

	        $this->db->where_in('element_id',$proposedElement);

	    } else {

            if($element_id!=NULL){
                $this->db->where('element_id',$element_id);
            }
	        

	    }

	    $this->db->where('brand',$this->session->userdata('brand'));

	    $this->db->where('status','Active');
        $this->db->where('element_type','In Shop Branding');
		$query = $this->db->get('element_rate_master');

		if( $query->num_rows() > 0 ){

			$result = $query->result_array();

			return $result;

		}

		return array();

	}
	

	function get_element_type($vendor_id, $element_id) {

		$this->db->select('element_id,element_name,element_type,element_rate,element_rate_inches');

	    $this->db->where('vendor_id',$vendor_id);

	    $this->db->where_in('element_id',$element_id);

	    $this->db->where('brand',$this->session->userdata('brand'));

	    $this->db->where('status','Active');

		$query = $this->db->get('element_rate_master');

		if( $query->num_rows() > 0 ){

			$result = $query->row_array();

			return $result;

		}

		return array();

	}

	

	

	/*-------------ISB  Elements----------------------*/

	function get_isb_element($vendor_id) {

		$this->db->select('element_id,element_name,element_type,element_rate,element_rate_inches');

	    $this->db->where('vendor_id',$vendor_id);

	    //$this->db->where('element_name',$element);  

	    $this->db->where('element_type','In Shop Branding');

	    $this->db->where('brand',$this->session->userdata('brand'));

	    $this->db->where('status','Active');

		$query = $this->db->get('element_rate_master');

		if( $query->num_rows() > 0 ){

			$result = $query->result_array();

			return $result;

		}

		return array();

	}





	function get_element_cost_new($project_id, $vendor_id, $element_type) {

		$this->db->select('element_rate, installation_per_inch_rate');

		$this->db->where('vendor_id', $vendor_id);

		$this->db->where('project_id', $project_id);

		$this->db->where('element_name', $element_type);

		$query = $this->db->get('project_store_element');

		if( $query->num_rows() > 0 ){

			$result = $query->row_array();

			return $result;

		}

		return false;

	}

	

	function get_project_dropdown(){

	    $this->db->group_by('project_id');

	    $this->db->where('brand',$this->session->userdata('brand'));

		$query = $this->db->get('projects');

		if( $query->num_rows() > 0 ){

			$result = $query->result_array();

			return $result;

		}

		return false;

	}

	

	function get_project_vendor_all_detail($project_id,$store_id){

	    $this->db->select('projects_vendor_mapping.*, user.name,user.user_id,store_master.store_name,store_master.store_uniqueID,store_master.city,store_master.state');

		$this->db->from('projects_vendor_mapping');

		$this->db->join('user', 'projects_vendor_mapping.vendor_1 = user.user_id');

		$this->db->join('store_master', 'projects_vendor_mapping.store_id = store_master.store_uniqueID');

		$this->db->where('projects_vendor_mapping.store_id',$store_id);

		$this->db->where('projects_vendor_mapping.project_id',$project_id);

		$query = $this->db->get();

		if( $query->num_rows() > 0 ){

			$result = $query->result_array();

			return $result;

		}

		return false;

	}

	

	function project_xls_report(){

	    $this->db->select('projects_vendor_mapping.project_id,projects_vendor_mapping.status,projects_vendor_mapping.store_id,projects_vendor_mapping.vendor_1,projects_vendor_mapping.recce_submit_date,projects_vendor_mapping.store_name,projects_vendor_mapping.add_line1,projects_vendor_mapping.city,projects_vendor_mapping.state,projects_vendor_mapping.pincode,projects_vendor_mapping.contact_person_name,projects_vendor_mapping.contact_mob,projects_vendor_mapping.vendor_name,projects_vendor_mapping.recce_approval_date,projects_vendor_mapping.artwork_approval_date,projects_vendor_mapping.completion_date,projects_vendor_mapping.vernacular_language,projects_vendor_mapping.existing_boards,projects_vendor_mapping.art_work,projects_vendor_mapping.art_work2,projects_vendor_mapping.deployment_proof,projects_vendor_mapping.dep_proof1,projects_vendor_mapping.recce_certificate,element_area.element_id,element_area.added_by,element_area.element_type,element_area.width,element_area.height,element_area.area,element_area.element_rate,element_area.amount,element_area.img1,element_area.img2,element_area.img3');

		$this->db->from('projects_vendor_mapping');

		$this->db->join('element_area', 'projects_vendor_mapping.store_id = element_area.store_id AND projects_vendor_mapping.project_id = element_area.project_id','left');

		$query = $this->db->get();

		if( $query->num_rows() > 0 ){

			$result = $query->result_array();

			return $result;

		}

		return false;

	}



	function deleted_store() {

		$query = $this->db->get('deleted_project_store_logs');

		if( $query->num_rows() > 0 ){

			$result = $query->result_array();

			return $result;

		}

		return false;

	}



	function revoke_store($id) {

		$this->db->select('data');

		$this->db->where('id', $id);

		$query = $this->db->get('deleted_project_store_logs');

		if( $query->num_rows() > 0 ){

			$result = $query->row_array();

			$decoded_data = json_decode($result['data'], true);

			foreach ($decoded_data as $value) {

				$this->db->insert('projects_vendor_mapping', $decoded_data);

			}



			/* Update status */

			$up_array = array(

								'status' => 1,

								'revoked_by'=>$this->session->userdata('username'),

								'revoked_date'=> date('Y-m-d H:i:s')

							);

			$this->db->where('id', $id);

			$this->db->update('deleted_project_store_logs', $up_array);

			return true;

		}

		return false;

	}



	function downgrade_recce_submit($str_id, $ven_id, $prj_id) {

		$store_id = custom_decode($str_id);

		$vendor_id = custom_decode($ven_id);

		$project_id = custom_decode($prj_id);



		/* Get Element Area Data */

		$this->db->where('store_id',$store_id);

		$this->db->where('vendor_id',$vendor_id);

		$this->db->where('project_id',$project_id);

		$this->db->where('added_by', 'admin');

		$query1 = $this->db->get('element_area');

		$element_area = json_encode($query1->result_array());



		/* Get Project Store Sub Element Data */

		$this->db->where('store_id',$store_id);

		$this->db->where('vendor_id',$vendor_id);

		$this->db->where('project_id',$project_id);

		$this->db->where('added_by', 'admin');

		$query2 = $this->db->get('project_store_sub_element');

		$project_store_sub_element = json_encode($query2->row_array());



		/* Get Project Supporting Image Data */

		$this->db->where('store_id',$store_id);

		$this->db->where('vendor_id',$vendor_id);

		$this->db->where('project_id',$project_id);

		$query3 = $this->db->get('project_supporting_images');

		$project_supporting_images = json_encode($query3->row_array());



		/* Get Project Vendor Mapping Data */

		$this->db->where('store_id',$store_id);

		$this->db->where('vendor_1',$vendor_id);

		$this->db->where('project_id',$project_id);

		$query4 = $this->db->get('projects_vendor_mapping');

		$projects_vendor_mapping = json_encode($query4->row_array());



		$data = array(

						'store_id' => $store_id,

						'vendor_id' => $vendor_id,

						'project_id' => $project_id,

						'element_area' => $element_area,

						'project_store_sub_element' => $project_store_sub_element,

						'project_supporting_images' => $project_supporting_images,

						'projects_vendor_mapping' => $projects_vendor_mapping,

						'deleted_by' => $this->session->userdata('username'),

						'deletion_date' => date('Y-m-d H:s:i'),

						'ip' => $_SERVER['REMOTE_ADDR']

					);

	    $this->db->insert('downgrade_recce_submit_logs', $data);



		/* Delete element area data */

		$this->db->where('store_id',$store_id);

		$this->db->where('vendor_id',$vendor_id);

		$this->db->where('project_id',$project_id);

		$this->db->where('added_by', 'admin');

		$this->db->delete('element_area');



		/* Delete Project Store sub Element data */

		$this->db->where('store_id',$store_id);

		$this->db->where('vendor_id',$vendor_id);

		$this->db->where('project_id',$project_id);

		$this->db->where('added_by', 'admin');

		$this->db->delete('project_store_sub_element');



		/* Delete Project Supprting Image */

		$this->db->where('store_id',$store_id);

		$this->db->where('vendor_id',$vendor_id);

		$this->db->where('project_id',$project_id);

		$this->db->delete('project_supporting_images');



		/* Update Project Vendor Mapping */

		$this->db->select('recce_value_by_vendor, pre_value');

		$this->db->where('store_id',$store_id);

		$this->db->where('vendor_1',$vendor_id);

		$this->db->where('project_id',$project_id);

		$get_query = $this->db->get('projects_vendor_mapping');

		if($get_query->num_rows() > 0) {			

			if($get_query->row_array()['recce_value_by_vendor'] > 0) {

				$recce_val = $get_query->row_array()['recce_value_by_vendor'];

			} else {

				$recce_val = $get_query->row_array()['pre_value'];

			}

		}



		$vendor_mapping_array = array(

			'completion_date' => '0000-00-00 00:00:00',

			'dep_approval_date' => '0000-00-00 00:00:00',

			'deployment_issue' => '',

			'pre_value' => $recce_val,

			'recce_value_by_vendor' => '',

			'status' => 1,

			'deduct_installation' => '',

			'comments' => '',

			'awb_no' => '',

			'courier' => '',

			'dispatch_date' => '0000-00-00 00:00:00',

			'deployment_approval_pdf' => '',

			'recce_submit_comment' => '',

			'recce_approval_comment' => '',

			'recce_approval_date' => '0000-00-00 00:00:00',

			'artwork_approval_user_id' => '',

			'artwork_approval_date' => '0000-00-00 00:00:00',

			'artwork_approval_comment' => '',

			'deployment_done_comment' => '',

			'deployment_done_status' => ''

			

		);



		$this->db->where('store_id',$store_id);

		$this->db->where('vendor_1',$vendor_id);

		$this->db->where('project_id',$project_id);

		$this->db->update('projects_vendor_mapping', $vendor_mapping_array);



		$this->create_project_logs($project_id,$store_id,$this->session->userdata('user_id'),$this->session->userdata('name'),$this->session->userdata('role'),65,'');



		if($this->db->affected_rows() > 0) {

			return true;

		}

		return false;

	}

	

	function element_type_info($id) {

		$this->db->where('element_id', $id);

		$query = $this->db->get('element_master');

		if( $query->num_rows() > 0 ){

			$result = $query->row_array();

			return $result['element_type'];

		}

		return false;

	}

	

	

	function bulk_po_grn_submited() {

	    $this->load->library('form_validation');   

		$this->form_validation->set_rules('request_id[]', 'request_id', 'trim|required');

		$this->form_validation->set_rules('project', 'project', 'trim|required');

		$this->form_validation->set_rules('action', 'action', 'trim|required');

		$this->form_validation->set_rules('filter_type', 'filter_type', 'trim|required');

		

		if(custom_decode($this->input->post('filter_type')) == 5){

	       $this->form_validation->set_rules('po_number', 'po_number', 'trim|required');

	    }

	     
		if ( $this->form_validation->run() == FALSE ) {

			return "Please Fill all Mandatory Fields";



		} else {

		   

		    //$select_all = $this->input->post('select_all');

	        $store_id = $this->input->post('request_id');

	       
	        if(count($store_id) ==0){

	            return "Please Select Site First.";

	        }

	        

	        if(custom_decode($this->input->post('action')) == 'PO_UPLOADED'){

    		    $action = 7;

    		} else if(custom_decode($this->input->post('action')) == 'GRN_UPLOADED'){

    		    $action = 10;

    		} else {

    		    return "Something went wrong,Please try again.!";

    		}

    		

            $this->db->select('*');
    		$this->db->from('projects_vendor_mapping');
    		$this->db->where_in('store_id', $store_id);
    		$this->db->where_in('project_id', custom_decode($this->input->post('project')));
    		$query = $this->db->get();
    		$update_arry = array();

    		$ins_array =array();
            
            $recce_value_by_vendor = 0;
    		if($query->num_rows() > 0) {
    			$row =  $query->result_array();
    			foreach($row as $result) {
    			    $recce_value_by_vendor+=$result['recce_value_by_vendor'];
    			    if(custom_decode($this->input->post('action')) == 'PO_UPLOADED'){
    			        $update_arry[] = array(
    		                'id' => $result['id'],
    				        'status' => $action,
    				        'po_number' => $this->input->post('po_number'),

				        );

    			    } else if(custom_decode($this->input->post('action')) == 'GRN_UPLOADED'){

    			        $update_arry[] = array(

    		                'id' => $result['id'],

    				        'status' => $action,

    				        'grn_number' => $this->input->post('grn_number'),

				        );

    			    }

    			    

                    $ins_array[] = array(

            			'status_changed'=>$action,

            			'comments'=>'PO/GRN Bulk Upload',

            			'created_by'=> $this->session->userdata('role'),

            			'user_id'=>$this->session->userdata('user_id'),

            			'project_id'=>custom_decode($this->input->post('project')),

            			'store_id'=> $result['store_id'],

            			'created_at'=>date("Y-m-d H:i:s")

            		);

    	        } 

    	        $proB = get_project_budget_used(custom_decode($this->input->post('project')));
				$totalB = round($proB['budgeted_amount']);
				$usedB = round($proB['budget_used']);
				$recceB = round($recce_value_by_vendor);
				if($totalB < ($usedB+$recceB) && custom_decode($this->input->post('action')) == 'PO_UPLOADED'){
				     return "Not Enough Budget.";
				}else{
				     $this->db->trans_start();

        			$this->db->insert_batch('project_log_history', $ins_array);
    
        			$this->db->update_batch('projects_vendor_mapping', $update_arry, 'id');
    
        			$this->db->trans_complete();
    
        			
    
        			if( $this->db->trans_status() === TRUE ){
    
        				/*========= Txn History=====*/
    
            		    //update_txn($txn['txn_id'],$txn['type']);
    
            		    return true;
    
        			} else {
    
        			    return "Somthing went wrong,Please try again.";
    
        			}
				}


    	       

    		} else {

    			return "Somthing went wrong,Please try again.";

    		}

	    }

	}

	// code start
	function channel_partners(){
		$temp = $this->db->select('DISTINCT(channel_partner_type) AS channel_partner_type')
		->from('store_master')
		->where('brand', $this->session->userdata('brand'))
		->get();
		if($temp){
			return $temp->result_array();
		}else{
			return false;
		}
	}

	function get_all_region(){
		$temp = $this->db->select('DISTINCT(region_level) AS region_level_name')
		->from('region_master')
		->where('brand', $this->session->userdata('brand'))
		->get();
		if($temp){
			return $temp->result_array();
		}else{
			return false;
		}
	}


// remove site from project

function all_sites($id){
        
		$this->db->where('project_id', $id);
        $this->db->where('status','0');
		$query = $this->db->get('projects_vendor_mapping');

		if( $query->num_rows() > 0 ){

			$result = $query->result_array();

			return $result;

		}

		return false;

	}

function remove_site_from_project($project_id, $temp_arr, $remarks)
{

	if (count($temp_arr) == 0) {
		return "Please Select Site First.";
	}

	$this->db->select('*');
	$this->db->from('site_allocation_request');
	$this->db->where('brand', $this->session->userdata('brand'));
	$this->db->where('project', $project_id);
	$this->db->where_in('store_uniqueID', $temp_arr);
	$query = $this->db->get();

	$this->db->select('*');
	$this->db->from('projects_vendor_mapping');
	$this->db->where('brand', $this->session->userdata('brand'));
	$this->db->where('project_id', $project_id);
	$this->db->where('status','0');
	$this->db->where_in('store_id', $temp_arr);
	$query2 = $this->db->get();
   
	if($query2->num_rows() < 0 ){
	    return "Somthing went wrong,Please try again."; 
	}

	$update_arry = array();

	if ($query->num_rows() > 0) {
		$row =  $query->result_array();

		if ($remarks == '') {
			return "Remarks is Required!";
		}

		foreach ($row as $result) {

			$update_arry[] = array(
				'id' => $result['id'],
				'is_delete'=>'1',
				'status' => 'Deleted',
				'deleted_by' => $this->session->userdata('user_id'),
				'deleted_date' => date('Y-m-d'),
				'delete_remark' => $remarks,
			);
		}


      
		$this->db->trans_start();
	
		$this->db->update_batch('site_allocation_request', $update_arry, 'id');
	
		$this->db->insert_batch('pvm_deleted_site', $query2->result_array());
		
		$this->db->where('brand', $this->session->userdata('brand'));
	    $this->db->where('project_id', $project_id);
	    $this->db->where_in('store_id', $temp_arr);
		$this->db->delete('projects_vendor_mapping');
		$this->db->trans_complete();

		if ($this->db->trans_status() === TRUE) {
			return true;
		} else {
			return "Somthing went wrong,Please try again.....";
		}
	} else {
		return "Somthing went wrong,Please try again.";
	}
}
// end


// bulk ftr uploader

    function upload_ftr_data($id){
         if( !empty($_FILES['bulk_ftr']['name']) ) {
            
                  $txn = txn_id("BFU");
                  $file_name = "bulk_upload_ftr_".time().'_'.".csv";
                  
                  $config = array(
                    'upload_path' => FTR_UPLOAD_PATH, 
                    'allowed_types' => '*',
                    'remove_spaces' => TRUE,
                    'max_size' => 1024*50,
                    'file_name' => $file_name,
                  );
               
                 
                  $this->load->library('upload');
                  $this->upload->initialize($config);
                  if($this->upload->do_upload('bulk_ftr')){
                    $file = read_csv(base_url('data/bulk_ftr_files/').$file_name);
                    $count_csv = count($file);
                    
                    $this->session->set_userdata('bulk_ftr', $file_name);
                    
                    update_txn($txn["txn_id"], $txn["type"]);
                    upload_logs(base_url('data/bulk_ftr_files/').$file_name,$count_csv-1,$txn["txn_id"]);
                  }else{
                    exception($this->session->userdata('user_id'),"",json_encode($_FILES),"File uploading error",$this->session->userdata('role'));
                  }
              }
        
              $store_err_tbl = array();
              $store_empty_err=0;
              $error_data=array();
              $ins_arry = array();
              $status ='';
              $where = array();
              for($i = 1; $i <= $count_csv - 1; $i++){
                  
                        
                      $sap_code         = $file[$i][0];
                      $store_name       = $file[$i][1];
                      $ftr_number       = $file[$i][12];
                      $comment          = $file[$i][13];
                      
                        $where[] = $sap_code;
        
                      /* Validation */
                      
                      $err_array = array(
                            'sap_code'   => $sap_code,
                            'store_name'   => $store_name,
                            'ftr_number' => $ftr_number
                        );
                       
                      
                      $status='';
                      if(empty($sap_code)){
                          $status='false';
                          $err_array['error_msg'] = 'Store SapCode is empty';
                          $error_data[]= $err_array;
                      }
                      
                      if(empty($ftr_number)){
                          $status='false';
                          $err_array['error_msg'] = 'FTR number is empty';
                          $error_data[]= $err_array;
                      }
                      
                       $up_array[] = array(
                           'sap_code'=>$sap_code,
					        'status' => 11,
					        'ftr_number'=> $ftr_number,
					        'comments'=>$comment
				        );
				        
				        
				       $store = $this->db->select('store_id')->from('projects_vendor_mapping')->where('sap_code',$sap_code)->get()->row_array();
				    if($store){
				        $store_id = $store['store_id'];
				    }else{
				        $store_id = '';
				    }
				    
				  
                    $this->create_project_logs(custom_decode($id),$store_id,$this->session->userdata('user_id'),$this->session->userdata('name'),$this->session->userdata('role'),11, $comment); 
                  
              }
              
            
             
              if(is_array($error_data) && count($error_data) > 0){
                        exception($this->session->userdata('user_id'),"",json_encode($error_data),"Error when Bulk FTR Uploaded ",$this->session->userdata('role'),$txn['txn_id']);
                  return $error_data;
                } else {
                       
                        /*-----------Insert Batch Query----------------*/
                          $this->db->trans_start();
                          $this->db->where('project_id',custom_decode($id));
                          $this->db->update_batch('projects_vendor_mapping',$up_array,'sap_code');
                          
                          $this->db->trans_complete();
                    
                          if( $this->db->trans_status() === TRUE ){
                             update_txn($txn['txn_id'],$txn['type']);
                             return true; 
                          } else {
                              return "There are some error Occurred,Please try again";
                          }
                    }
    }
    
    // cmm support 2 work
    
    function store_list_cmm_support2($get){
      
        $this->db->select("projects_vendor_mapping.*,projects.project_zone,projects.project_region,projects.project_name");
        $this->db->from('projects_vendor_mapping');
        $this->db->join("projects","projects_vendor_mapping.project_id = projects.project_id","left");
        $this->db->where('projects_vendor_mapping.status','12');
        $this->db->where('projects_vendor_mapping.brand',$this->session->userdata('brand'));
        if($get['zone']!="" && $get['zone']!="All"){
            $this->db->where('projects.project_zone',$get['zone']);
        }
        if($get['region']!="" && $get['region']!="All"){
            $this->db->where('projects.project_region',$get['region']);
        }
        if($get['vendor']!="" && $get['vendor']!="All"){
            $this->db->where('projects_vendor_mapping.vendor_name',$get['vendor']);
        }
        if($get['chennel']!="" && $get['chennel']!="All"){
            $this->db->where('projects_vendor_mapping.channel_type',$get['chennel']);
        }
        
        if($get['ftr_number']!="" && $get['ftr_number']!="All"){
            $this->db->where('projects_vendor_mapping.ftr_number',$get['ftr_number']);
        }
        $result = $this->db->get();
     
        $_SESSION['d_query'] = $this->db->last_query();
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return [];
        }
        
    }
    
    
    public function get_budget_percentages(){
        $this->db->select('SUM(IF(pv.status >= 7 AND pv.status!= 24, pv.pre_value, 0)) as budget_used,p.budgeted_amount');
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
		if( $query->num_rows() > 0 ){
			$result = $query->result_array();
			return $result;
		}

		return false;
    }
 
 public function project_logs_graph($project_id)
	{

		$this->db->select('*');
		$this->db->from('projects');		
		$this->db->where('project_id', $project_id);
		$data = $this->db->get()->row();
		
		 $projectId = $data->project_id;
		 $associatedProjectId = $data->associated_project_id;
		 $projectType = $data->project_type; 

		
		if($projectType == "simple")
		{
			$this->db->select('*');
			$this->db->from('budget_project_log_history');
			$this->db->where('project_id', $project_id);
			$this->db->order_by('id','desc');
			$query = $this->db->get()->result_array();
			
			$parent=array();
			$i=0;
			foreach($query as $row)
			{
				$parent[$i]['id'] = $row['id'];
				$parent[$i]['project_id'] = $row['project_id'];
				$parent[$i]['username'] = $row['username'];
				$parent[$i]['old_json'] = $row['old_json'];
				$parent[$i]['new_json'] = $row['new_json'];
				$parent[$i]['budget_amended'] = $row['budget_amended'];
				$parent[$i]['created_at'] = $row['created_at'];
				$parent[$i]['project_type'] = "simple";
				
			$i++;			
			}
			
	
		} else {
			
			
				$this->db->select('*');
			$this->db->from('budget_project_log_history');
			$this->db->where('project_id', $project_id);
			$this->db->order_by('id','desc');
			$query = $this->db->get()->result_array();
			
			
			$parent=array();
			$i=0;
			foreach($query as $row)
			{
				$parent[$i]['id'] = $row['id'];
				$parent[$i]['project_id'] = $row['project_id'];
				$parent[$i]['username'] = $row['username'];
				$parent[$i]['old_json'] = $row['old_json'];
				$parent[$i]['new_json'] = $row['new_json'];
				$parent[$i]['budget_amended'] = $row['budget_amended'];
				$parent[$i]['created_at'] = $row['created_at'];
				$parent[$i]['project_type'] = "exception";
				
			$i++;			
			}
			
		}
		
		return $parent;
		
		
	}
	
	public function project_edit_logs($project_id)	
	{
			$this->db->select('*');
			$this->db->from('project_edit_log');
			$this->db->where('project_id', $project_id);
			$this->db->order_by('id','desc');
			$query = $this->db->get()->result_array();
			return $query; 
	}
	
	
	 public function deleted_project_status($store_id,$vendor_id,$project_id,$sap_code)
	{
	    
	    date_default_timezone_set("Asia/Kolkata");
	    
	    // print_r($_SESSION); die;
	    $created_by =  $_SESSION['role'];

		$this->db->select('*');
		$this->db->from('project_supporting_images');		
		$this->db->where('store_id', $store_id);		
		$this->db->where('vendor_id', $vendor_id);		
		$this->db->where('project_id', $project_id);
		$data = $this->db->get()->result_array();
		
		 $projectId = $project_id;
		 $store_id = $store_id;
		 $project_table1 = "project_supporting_images"; 
		 
	//	echo "<pre>";  print_r(json_encode($data)); die;
		$old_json1=json_encode($data);
		 
			
		$datalog1 = array(
		    'table_name' => $project_table1,
		    'old_json' => $old_json1,
		    'new_json' => '',
		    'created_by' => $created_by,
		    'project_id' => $project_id,
		    'store_id' => $store_id
		    );
			    
	    $this->db->insert('downgrade_cases_logs',$datalog1);
			    
	// start delete table project_supporting_images		    
			    
	  $this->db->where('store_id', $store_id);		
	  $this->db->where('vendor_id', $vendor_id);		
	  $this->db->where('project_id', $project_id);
	  $this->db->delete("project_supporting_images");
	 
	// end delete table project_supporting_images
	
	
	
    	$this->db->select('*');
		$this->db->from('project_store_sub_element');		
		$this->db->where('store_id', $store_id);		
		$this->db->where('vendor_id', $vendor_id);		
		$this->db->where('project_id', $project_id);
		$data2 = $this->db->get()->result_array();
		
		$project_table2 = "project_store_sub_element"; 
		 
		 
	//	echo "<pre>";  print_r(json_encode($data)); die;
		$old_json2=json_encode($data2);
				
		$datalog2 = array(
		    'table_name' => $project_table2,
		    'old_json' => $old_json2,
		    'new_json' => '',
		    'created_by' => $created_by,
		    'project_id' => $project_id,
		    'store_id' => $store_id
		    );
			    
	    $this->db->insert('downgrade_cases_logs',$datalog2);
	    
	    // start delete table project_store_sub_element		    
			    
	    $this->db->where('store_id', $store_id);		
		$this->db->where('vendor_id', $vendor_id);		
		$this->db->where('project_id', $project_id);
	    $this->db->delete("project_store_sub_element");
	 
	// end delete table project_store_sub_element
	
    	$this->db->select('*');
		$this->db->from('element_area');		
		$this->db->where('store_id', $store_id);		
		$this->db->where('vendor_id', $vendor_id);		
		$this->db->where('project_id', $project_id);
		$data3 = $this->db->get()->result_array();
		
		$project_table3 = "element_area"; 
		 
		 
	//	echo "<pre>";  print_r(json_encode($data)); die;
		$old_json3=json_encode($data3);
				
		$datalog3 = array(
		    'table_name' => $project_table3,
		    'old_json' => $old_json3,
		    'new_json' => '',
		    'created_by' => $created_by,
		    'project_id' => $project_id,
		    'store_id' => $store_id
		    );
			    
	    $this->db->insert('downgrade_cases_logs',$datalog3);
	    
	// start delete table element_area		    
			    
    	$this->db->where('store_id', $store_id);		    
	    $this->db->where('vendor_id', $vendor_id);		
		$this->db->where('project_id', $project_id);
	    $this->db->delete("element_area");
	 
	// end delete table element_area
	
    	$this->db->select('*');
		$this->db->from('projects_vendor_mapping');		
		$this->db->where('store_id', $store_id);		
		$this->db->where('sap_code', $sap_code);		
		$this->db->where('project_id', $project_id);
		$data4 = $this->db->get()->result_array();
		
		$project_table4 = "projects_vendor_mapping"; 
		 
	//	echo "<pre>";  print_r(json_encode($data)); die;
		$old_json4=json_encode($data4);
				
		$datalog4 = array(
		    'table_name' => $project_table4,
		    'old_json' => $old_json4,
		    'new_json' => '',
		    'created_by' => $created_by,
		    'project_id' => $project_id,
		    'store_id' => $store_id
		    );
			    
	    $this->db->insert('downgrade_cases_logs',$datalog4);
	    $insert_id = $this->db->insert_id();
	    
	    $data_update = array
        (
        'completion_date' => "0000-00-00 00:00:00",
        'dep_approval_date' => "0000-00-00 00:00:00",
        'deployment_issue' => "",
        'deployment_issue_remarks' => "",
        'status'=> 0,
        'additional_branding' => "",
        'delivery_type' => "",
        'permission' => "",
        'pre_value' => "",
        'recce_value_by_vendor' => "",
        'vernacular_language' => "",
        'signage_board_used' => "",
        'po_number' => "",
        'po_approvel_date' => "",
        'art_work' => "",
        'art_work2' => "",
        'art_work3' => "",
        'deployment_proof' => "",
        'dep_proof1' => "",
        'dep_proof2' => "",
        'dep_proof3' => "",
        'deployment_date' => "",
        'ftr_number' => "",
        'grn_upload_date' => "",
        'ftr_upload_date' => "",
        'po_upload_date' => "",
        'grn_number' => "",
        'unique_ref_id' => "",
        'deduct_installation' => "",
        'dep_not_approved' => "",
        'comments' => "",
        'awb_no' => "",
        'courier' => "",
        'dispatch_date' => "",
        'deployment_approval_pdf' => "",
        'recce_submit_comment' => "",
        'recce_status' => "",
        'recce_submit_date' => "0000-00-00 00:00:00",
        'recce_approval_comment' => "",
        'recce_approval_date' => "0000-00-00 00:00:00",
        'artwork_approval_user_id' => "",
        'artwork_approval_date' => "0000-00-00 00:00:00",
        'artwork_approval_comment' => "",
        'deployment_done_comment' => "",
        'deployment_done_status' => "",
        'existing_boards' => "",
        'dep_approval_date_tmm_national' => "0000-00-00 00:00:00",
        'dep_approval_date_cmo_support1' => "",
        'recce_certificate' => "",
        );
	    
	// start delete table element_area		    
			    
	    $this->db->where('store_id', $store_id);		
		$this->db->where('sap_code', $sap_code);		
	    $this->db->where('project_id', $project_id);
	    $this->db->update("projects_vendor_mapping",$data_update);
	 //	echo $this->db->last_query();
	// end delete table element_area
	
		$this->db->select('*');
		$this->db->from('projects_vendor_mapping');		
		$this->db->where('store_id', $store_id);		
		$this->db->where('sap_code', $sap_code);		
		$this->db->where('project_id', $project_id);
		$data6 = $this->db->get()->result_array();
		
		$project_table6 = "projects_vendor_mapping"; 
		 
		 
	//	echo "<pre>";  print_r(json_encode($data6)); die;
		$new_json6=json_encode($data6);
				
		$dataupdate_log = array(
		    'new_json' => $new_json6,
		    );
			    
		$this->db->where('id', $insert_id);	    
		$this->db->where('table_name', $project_table6);
		$this->db->where('store_id', $store_id);		
		$this->db->where('project_id', $project_id);	    
	    $this->db->update('downgrade_cases_logs',$dataupdate_log);
	
	
	// start project_log_history
	    			
		$datalog7 = array(
		    'status_changed' => '65',
		    'comments' => 'Done',
		    'created_by' => $created_by,
		    'user_id' => $_SESSION['user_id'],
		    'name'=>$_SESSION['name'],
		    'project_id' => $project_id,
		    'store_id' => $store_id,
		    'created_at' => date("Y-m-d H:i:s")
		    );
			    
	    $this->db->insert('project_log_history',$datalog7);

    //	echo $this->db->last_query();
	//  die;
		 return true;
		 
	
	}
	
	function ftr_number($id){
	    $this->db->where('projects_vendor_mapping.project_id', $id);
	   $temp =  $this->db->select('ftr_number')->from('projects_vendor_mapping')->group_by('ftr_number')->get();
	   if($temp->num_rows()>0){
	       return $temp->result_array();
	   }else{
	       return false;
	   }
	}
	
	function project_all_status_count($id){
	    $this->db->select('count(pvm.status) as status_count,ps.status_name as status_name');
	    $this->db->from('projects_vendor_mapping pvm');
	    $this->db->join('project_status ps','ps.status_id = pvm.status','left');
	    $this->db->where('pvm.project_id',$id);
	    $this->db->group_by('pvm.status');
	    $temp = $this->db->get();
	   
	    if($temp->num_rows()>0){
	        return $temp->result_array();
	    }else{
	        return false;
	    }
	}
}

?>