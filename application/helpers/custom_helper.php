<?php
function loadpage($view,$data=array()){
	$CI = & get_instance();
	$CI->load->view('layout/header');
	$CI->load->view('layout/aside');
	$CI->load->view($view,$data);
	$CI->load->view('layout/footer');
}

?>
