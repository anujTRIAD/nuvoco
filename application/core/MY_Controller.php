<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->is_user_login();
        $this->load->model('users_model','UM');
       
    }

    function is_user_login()
    {
        if (!$this->session->has_userdata('user_id')) {
            redirect('login');
        }
    }

    function load_selectBox()
    {
        $post = $this->input->post();
        $table = $post['table'];
        $where = json_decode($post['where'],true);
        $select = $post['column'];
        $groupby = $post['groupby'];
        $data = $this->UM->get_records($select,$table,$where,'all',$groupby);
      
        if($data){
            $result = array(
                'status'=>1,
                'data'=>$data
            );
        }else{
            $result = array(
                'status'=>0,
                'data'=>''
            );
        }
        echo json_encode($result);
    }
}
