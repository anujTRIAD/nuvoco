<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Element extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('element_model');
		is_first_time_login($this->session->userdata('is_first_login'));
	}

	function index() {
		//can_access('admin', 'manager','cmm','tmm_national');

		$includes = array('datatable', 'datepicker', 'fancybox');
		$data['inclusions'] = inclusions($includes);
		$data['page_title'] = "Element Master";
		$data['elements'] = $this->element_model->element();
		load_page('element/index', $data);
	}

	function add() {
		can_access('admin','cmm','tmm_national');

		if( isset($_POST['submit']) ) {

			$txn = txn_id('ELM');
			$data_arr = array(
					'element_id' => $txn["txn_id"],
					'element_name' => trim($this->input->post('element_name'),','),
					'element_type' => $this->input->post('element_type'),
					//'element_rate' => (empty(@$this->input->post('element_rate')))?'':@$this->input->post('element_rate'),
					'element_description' => trim($this->input->post('element_description'),','),
					'brand' => $this->session->userdata('brand'),
					'status' => 'Active',
					'created_at' => date('Y-m-d H:i:s')
			);
			$insert_id = $this->element_model->add_element($data_arr);
			if($insert_id != '') {
				/* Create Transection */
				update_txn($txn["txn_id"], $txn["type"]);
				set_flashdata('message', 'Element added successfully', 'success');
				redirect('element/thank_you');
			} else {
				exception($this->session->userdata('user_id'),"",json_encode($_POST),"Element not Created try again",$this->session->userdata('role'));
				set_flashdata('message', 'Element not added, try again', 'danger');
				redirect('element/add');
			}
			
		} else {
			$includes = array('validation','datatable', 'datepicker');
			$data['inclusions'] = inclusions($includes);
			$data['page_title'] = "Add Element";			
			load_page('element/add', $data);
		}
	}

	function edit($id) {
		can_access('admin','cmm','tmm_national');
		$id = ($id != '') ? $id : $this->input->post('id');

		if( isset($_POST['submit']) ) {
			$data_arr = array(
								'element_name' => $this->input->post('element_name'),
								'element_type' => $this->input->post('element_type'),
								//'element_rate' => $this->input->post('element_rate'),
								'element_description' => $this->input->post('element_description'),
							);
			$insert_id = $this->element_model->update_element($data_arr, custom_decode($id));
			/* Create Transection */
			create_txn('element_master', 'Recored Updated', custom_decode($id));
			if($insert_id) {
				set_flashdata('message', 'Element updated successfully', 'success');
				redirect('element');
			} else {
				set_flashdata('message', 'Element not updated, try again', 'danger');
				redirect('element/edit');
			}
			
		} else {
			$includes = array('datatable', 'datepicker');
			$data['inclusions'] = inclusions($includes);
			$data['page_title'] = "Update Element";	
			$data['element'] = $this->element_model->element(custom_decode($id));		
			load_page('element/edit', $data);
		}
	}

	function status() {
		$id = custom_decode($this->input->post('id'));
        $value = $this->input->post('value');
        $response = set_value('status', $value, 'element_master', $id);
        /* Create Transection */
		create_txn('element_master', 'Status Changed:'.$value, $id);
        echo $response;
	}
	
	function delete($id) {
		$id = ($id != '') ? $id : $this->input->post('id');
		$deleted = $this->element_model->delete_element(custom_decode($id));

		if($deleted) {
			set_flashdata('message', 'Deleted successfully.', 'success');
			redirect('element');
		} else {
			set_flashdata('message', 'Element not deleted, try again', 'danger');
			redirect('element');
		}
	}

	function element_json() {
		$data = $this->element_model->element_json_data();
		if(is_array($data) && count($data)){
		foreach ($data as $key => $value) {
		    $response[] = [
		        'id'=>custom_encode($value["id"]),
		        'element_id'=>$value["element_id"],
		        'element_name'=>$value["element_name"],
		        'element_type'=>strtoupper($value["element_type"]),
		        'element_rate'=>$value["element_rate"],
		        'element_description'=>substr($value["element_description"],0,25),
		        'status'=>$value["status"],
		        'created_at'=>$value["created_at"]
		        ];
		}
		    echo json_encode(array("data"=>$response));
		}else{
		    echo json_encode(array("data"=>[]));
		}
	}

	function thank_you(){
		$includes = array('datatable', 'datepicker');
		$data['inclusions'] = inclusions($includes);
		$data['page_title'] = "Thank You";	
		load_page('element/thank-you', $data);
	}
	
	function download(){
	    $res = $this->element_model->download();
	    foreach($res as $value){
	        $response[] = [
				'Element Id' => $value['element_id'],
				'Element Name' =>$value['element_name'],
				'Element Type' =>$value['element_type'],
				// 'Element Rate' => $value['element_rate'],
				'Details'=>$value['element_description'],
				'status' => $value['status']
			];
	    }
	    outputCsv('Element_master.csv',$response);
	}
	
	
// 	 common filter work by ajit 25/july/2022
    function getColumn(){
        $columnKey = $this->input->get('columnKey');
        $table = 'element_master';
        $data = $this->db->list_fields($table);
        $returnData = array();
        $list = '';
        $make_filter = array();
        $selectedArr = array();
        if($this->session->has_userdata('common_filter_session')){
           $make_filter = $this->session->userdata('common_filter_session');
       }
        if(!empty($make_filter)){
               foreach($make_filter as $unset=>$filter){
                
                if($filter['column']==$column){
                    $selectedArr[] = $column;
                }
            }
           }
        foreach($data as $key=>$value){
            $data_key = $value;
            $active = ''; 
            if(in_array($data_key,$selectedArr)){
               $active = 'active'; 
            }
           if($key!='id'){
                if($columnKey!=""){
                $columnKey = str_replace(" ","_",$columnKey);
                if(trim(strtolower($value))==trim(strtolower($columnKey)) || strpos($value,$columnKey)===0 || strpos($value,$columnKey)!=''){
                    $vlaue = ucfirst(str_replace("_"," ",$value));
                    $list .= '<li class="list-group-item '.$active.'" style="cursor:pointer" data-id="'.$data_key.'" >'.$value.'</li>';
                }
            }else{
                $value = ucfirst(str_replace("_"," ",$value));
                $list .= '<li class="list-group-item '.$active.'" style="cursor:pointer" data-id="'.$data_key.'" >'.$value.'</li>';
            }
           }
        }
       
        
        echo json_encode($list);
    }
    
    function createFilter(){
        $table = $this->input->get('table');
        $columnKey = $this->input->get('column');
        $type = $this->input->get('type');
        $selectedData = json_decode($this->input->get('selectedData'));
        
        $data = $this->db->select($columnKey)->group_by($columnKey)->get($table)->result_array();
        
        $select = '<div class="row parenCommonDiv"><div class="form-group"><label>'.ucfirst(str_replace("_"," ",$columnKey)).'     <span class="close" data-id="'.$columnKey.'">X</span></label><br>';
        if($type=='multiple'){
            $select .='<select class="commonFilter form-control" name="'.$columnKey.'[]" multiple style="width:80%" data-id="'.$columnKey.'">';
        }else{
            $select .='<select class="commonFilter form-control" name="'.$columnKey.'" style="width:80%" data-id="'.$columnKey.'">';
        }
        foreach($data as $key=>$value){
            
            if(in_array($value[$columnKey],$selectedData)){
                $select .='<option value="'.$value[$columnKey].'" selected>'.$value[$columnKey].'</option>';
            }else{
                $select .='<option value="'.$value[$columnKey].'" >'.$value[$columnKey].'</option>';
            }
        }
        $select .='</select></div></div>';
        echo json_encode($select); 
    }
   
   function set_CommonfilterSession(){

       $type = $this->input->get('type');
       $column = $this->input->get('column');
       $selection = $this->input->get('selection');
       $make_filter = array();
       if($this->session->has_userdata('common_filter_session')){
           $make_filter = $this->session->userdata('common_filter_session');
           $make_filter = array_values($make_filter);
       }
       $insertArray = array(
            'column'=>$column,
            'selection' => $selection
        );
      
           if(!empty($make_filter)){
               
               foreach($make_filter as $unset=>$filter){
                
                if($filter['column']==$column){
                    unset($make_filter[$unset]);
                    if($type=='set'){
                        $make_filter[] = $insertArray; 
                    }
                    
                }else{
                    if($type=='set'){
                        $make_filter[] = $insertArray; 
                    }
                }
            }
           }else{
               $make_filter[] = $insertArray; 
           }  
       
       $this->session->set_userdata('common_filter_session',$make_filter);
       
   }
   
  
// 	 common filter work by ajit 25/july/2022
}
