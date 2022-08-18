<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model {

	public function __construct() {
		parent::__construct();
       
	}

	function activity_logs($message,$type="") {
		
		$insert_array = array(
			"date" => date("Y-m-d"), 
			"time" => date("H:i:s"),
			'username'=>$this->session->userdata('user_id'),
			"page" => $_SERVER['REQUEST_URI'],
			"role" => $this->session->userdata('role'),
			"browser" => @$_SERVER['HTTP_USER_AGENT'], 
			"ip" => $_SERVER['REMOTE_ADDR'],
			'session_id' =>$_SESSION['__ci_last_regenerate'],
			'page_referer'=>@$_SERVER['HTTP_REFERER'],
			'message'=>$message,
			'type'=>$type
		); 
		$this->db->insert('web_activity_log', $insert_array);
		return true;
	}

	function exception($plateform,$code='', $input='', $msg='', $role='', $device='',$master_txn='') {
		$insert_array = array(
			"url" => current_url(),
			"json_input" => $input,
			"error_msg" => $msg,
			"user_id" => $code,
			"role" => $role,
			"date" => date("Y-m-d"),
			"time" => date("H:i:s"),
			"device_info" => $device,
			"ip" => $_SERVER['REMOTE_ADDR'],
			"master_txn"=>$master_txn,
			'plateform'=>$plateform
		);
		$this->db->insert('exception_log', $insert_array);
		return true;
	}

	function txn_id($code) {
		
		$type = $this->txn_type($code);
		$this->db->select('txn_id');
		$this->db->like('txn_id', $code, 'after');
		$this->db->order_by('id', 'desc');
		$this->db->limit('1');
		$query = $this->db->get('transaction');
		if( $query->num_rows() > 0  ) {
			$result = $query->row_array();
			$arr = array(
				"txn_id" => $code.date("dmy",time()).(substr($result['txn_id'], -7)+1),
				"type" => $type,
			);
			return $arr; 
		} else { 
			$arr = array(
				"txn_id" => $code.date("dmy",time()).'1000001',
				"type" => $type,
			); 
			return $arr;
		}
	}

	function txn_type($code) {
		
		$this->db->select('type');
		$this->db->where('code', $code);
		$query = $this->db->get('transaction_code');
		if( $query->num_rows() > 0 ){
			$result = $query->row_array();
			return $result['type'];
		} else { 
			return $type=""; 
		}
	}

	function create_txn($txn_id, $type, $status='',$platform='Web') {
		
		$insert_array = array(
			"date_created" => date("Y-m-d"), 
			"time_created" => date("H:i:s"), 
			"txn_id" => $txn_id, 
			"txn_type" => $type, 
			"user_id" => $this->session->userdata('user_id'), 
			"role" => $this->session->userdata('role'), 
			"platform" => $platform,
			"device_info" => $_SERVER['HTTP_USER_AGENT'], 
			"ip_add" => $_SERVER['REMOTE_ADDR'],
			"status"=>$status
		); 
		$this->db->insert('transaction', $insert_array);
		return true;
	}

	function update_txn($txn_id,$status){
		$this->db->where('txn_id',$txn_id);
		$this->db->update('transaction',['status'=>$status]);
		return true;
	}
   

	function query_exception($code='',$query='', $input='',$plateform='', $msg='', $role='',$device='', $master_txn='') {

		$insert_array = array(
			"uniquecode" => $code,
			"role" => $role,
			"url" => current_url(),
			"query" => $query,
			"json_input" => $input,
			"message" => $msg,
			"date" => date("Y-m-d"),
			"time" => date("H:i:s"),
			"device_info" => $device,
			"group_txn" => $master_txn,
			"ip" => $_SERVER['REMOTE_ADDR'],
			"plateform"=>$plateform
		);
		$this->db->insert('query_exception', $insert_array);
		return true;
	}

	function return_false(){
		$this->query_exception(get_session('user_id'),query(),'','web','',get_session('role'));
		return false;
	}
	//get records
	function get_records($select='*',$table,$where=array(),$type='all',$groupby=''){
	
		$this->db->select($select);
		if(!empty($where)){
			$this->db->where($where);
		}
		if(!empty($groupby)){
			$this->db->group_by($groupby);
		}
		$result = $this->db->get($table);
		
		if($result){
			
			if($type!='all'){
				return $result->row_array();
			}else{
				return $result->result_array();
			}
		}else{
			$this->return_false();
		}
	}

	

	//update records

	function update_records($table,$data,$where){
		$this->db->where($where);
		$result = $this->db->update($table,$data);
	
		if($result){
			return true;
		}else{
			$this->return_false();
		}
	}

	// get zone

	function fatch_zone(){
		$temp = $this->db->select('region as zone')->from('citylist')->group_by('region')->get();
		if($temp){
			return $temp->result_array();
		}else{
			$this->return_false();
		}
	}

// get state
	function fatch_state($zone=array()){
		$this->db->select('statename as state')->from('citylist');
		if(!empty($zone)){
			$this->db->where_in('region',$zone);
		}
		$temp = $this->db->group_by('statename')->get();
		if($temp){
			return $temp->result_array();
		}else{
			$this->return_false();
		}
	}

	// get_brand
	function fatch_brand(){
		$temp = $this->db->select('name as brand')->from('brands')->group_by('name')->get();
		if($temp){
			return $temp->result_array();
		}else{
			$this->return_false();
		}
	}
}