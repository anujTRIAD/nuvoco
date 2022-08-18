<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Upload extends CI_Controller {

	public function __construct() {
		parent::__construct();
		
		$this->load->library('Compress');
	}

	function file() {
	    
	    $file = $_FILES['file']['tmp_name']; // file that you wanna compress
        $new_name_image = time().'.'.png; // name of new file compressed
        $quality = 50; // Value that I chose
        $destination = base_url();
        
        $compress = new Compress();
        
        $compress->file_url = $file;
        $compress->new_name_image = $new_name_image;
        $compress->quality = $quality;
        $compress->destination = $destination;
        
        $result = $compress->compress_image();
        debug($result);
	}
	
	function insert_data_csv($csv_file_with_column_name)
    {
        $data =  read_csv($csv_file_with_column_name.'.csv');
       
        $headers = $data[0];
       
        array_shift($data);
        foreach($data as $key=>$value){
            foreach($headers as $h=>$head){
                $data[$key][$head] = $data[$key][$h];
                unset($data[$key][$h]);
            }
        }
        $this->db->trans_start();
        $this->db->insert_batch('store_master',$data);
        $this->db->trans_complete();
        
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
        }
       
    }
}
