<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Filter_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	function findState($zone)
    {
		$this->db->distinct();
        $this->db->select('statename');
        $this->db->where_in('region',$zone);
        $query = $this->db->get('citylist');
        $result = $query->result_array();
        if(!empty($result))
        {
            return $result;
        } else {
            return array();
        }  
    }
	
	function findCity($state)
    {
        $this->db->select('id,cityname');
        $this->db->where_in('statename',$state);
        $query = $this->db->get('citylist');
        $result = $query->result_array();
        if(!empty($result))
        {
            return $result;
        } else {
            return array();
        }  
    } 
	
}