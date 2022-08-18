<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Element_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	function element($id = null) {
		if( isset($_GET['start']) && !empty($_GET['start'])  ){
			$this->db->where('created_at>=', date('Y-m-d 00:00:00', strtotime($_GET['start'])));
		}
		if( isset($_GET['end']) && !empty($_GET['end'])  ){
			$this->db->where('created_at<=', date('Y-m-d 23:59:00', strtotime($_GET['end'])));
		}
		if( $id != ''){
			$this->db->where('id', $id);
		}
		$this->db->where('brand', $this->session->userdata('brand')); 
		$this->db->order_by('id', 'DESC');
		$query = $this->db->get('element_master');
		//debug($this->db->last_query());
		if( $query->num_rows() > 0 ){
			$result = $query->result_array();
			return $result;
		}
		return false;
	}

	function add_element($data) {
		$this->db->insert('element_master', $data);
        $insert_id = $this->db->insert_id();
        if($insert_id != '') {
        	return $insert_id;
        } else {
        	return false;
        }        
	}

	function update_element($data, $id) {
		$this->db->where('id', $id);
		$this->db->update('element_master', $data);
        if( $this->db->affected_rows() > 0 ) {
			return true;
		}
		return false;        
	}

	function delete_element($id) {
		$this->db->where('id',$id);
        $this->db->delete('element_master');
        if( $this->db->affected_rows() > 0 ) {
			return true;
		}
		return false;
	}

	function element_json_data($id = null) {
		if( isset($_GET['start']) && !empty($_GET['start'])  ){
			$this->db->where('created_at>=', date('Y-m-d 00:00:00', strtotime($_GET['start'])));
		}
		if( isset($_GET['end']) && !empty($_GET['end'])  ){
			$this->db->where('created_at<=', date('Y-m-d 23:59:00', strtotime($_GET['end'])));
		}
		if( $id != ''){
			$this->db->where('id', $id);
		}
		$this->db->where('brand', $this->session->userdata('brand'));
		$this->db->order_by('id', 'DESC');
		$query = $this->db->get('element_master');
		if( $query->num_rows() > 0 ){
			$result = $query->result_array();
			return $result;
		}
		return false;
	}
	
	function download($role){
	    $this->db->select('*');
	    $this->db->where('brand', $this->session->userdata('brand'));
        $this->db->from('element_master');
        $query = $this->db->get();
        if( $query->num_rows() > 0 ){
            $result = $query->result_array();
            return $result;
        }
        return false;
	}

}