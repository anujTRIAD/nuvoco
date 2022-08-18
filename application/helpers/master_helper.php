<?php
function set_cookie($name,$value,$expire=300){
    $CI = & get_instance();
    $cookie= array(
        'name'   => $name,
        'value'  => $value,                            
        'expire' => $expire,                                                                                   
        'secure' => TRUE
    );
    $CI->input->set_cookie($cookie);
}

function unset_cookie($name){
    set_cookie($name,'',$expire=300);
}
function get_cookie($name){
    $CI = & get_instance();
    return $CI->input->cookie($name);
}

function set_sessions($values) {
	$CI =& get_instance();
	$CI->session->set_userdata($values);
}

function get_session($name='') {
	$CI =& get_instance();
	if( !empty($name) ) {
		return $CI->session->userdata($name);
	}
	return $CI->session->userdata();
}

function unset_session($name) {
	$CI =& get_instance();
	$CI->session->unset_userdata($name);
}

function set_login_sessions($user) {

    $data = array(
        'logged_in' => 1,
        'username' => $user['username'],
        'id' => $user['id'],
        'role' => $user['role'],
        'user_id' => $user['user_id'],
        'name' => $user['name'],
        'user_type' => $user['user_type'],
        'is_first_login' => $user['is_first_login'],
        'brand' => $user['brand'],
        'zone' => $user['zone'],
        'region' => $user['region']
    );
    set_sessions($data);
}

function query(){
    $CI = & get_instance();
    return $CI->db->last_query();
}

function debug($data=array()){
   echo "<pre>";
   print_r($data);
   echo "</pre>";
}

function get_csrf_security(){
	$CI = & get_instance();
	echo  '<input type="hidden" name="'.$CI->security->get_csrf_token_name().'" value="'.$CI->security->get_csrf_hash().'">';
}

function inclusions( $values = array() ) {
	$options = array(
		'datatable' => array(
							array(
								'type' => 'js',
								'value' => 'assets/custom/vendors/js/tables/datatable/datatables.min'
							),
							array(
								'type' => 'css',
								'value' => 'assets/custom/vendors/css/tables/datatable/datatables.min'
							),
						),
		'datepicker' => array(
					array(
						'type' => 'css',
						'value' => 'assets/custom/datepicker/datetimepicker.min'
					),
					array(
						'type' => 'js',
						'value' => 'assets/custom/datepicker/moment.min'
					),
					array(
						'type' => 'js',
						'value' => 'assets/custom/datepicker/datetimepicker.min'
					),
				),	
	
		'boot-datepicker' => array(
					array(
						'type' => 'css',
						'value' => 'assets/custom/bootstrap-datepicker/css/bootstrap-datepicker'
					),
					array(
						'type' => 'js',
						'value' => 'assets/custom/bootstrap-datepicker/js/bootstrap-datepicker'
					)
				),
		'validate' => array(
							array(
								'type' => 'header_js',
								'value' => 'assets/custom/js/validator'
							),
						),
		'select' => array(
							array(
								'type' => 'css',
								'value' => 'assets/custom/vendors/css/forms/selects/select2.min'
							),
							array(
								'type' => 'js',
								'value' => 'assets/custom/vendors/js/forms/select/select2.full.min'
							),
							array(
								'type' => 'js',
								'value' => 'assets/custom/js/scripts/forms/select/form-select2.min'
							),
						),
		'animation' => array(
							array(
								'type' => 'css',
								'value' => 'assets/custom/css/plugins/animate/animate.min'
							),
						),
		'chosen' => array(
						array(
							'type' => 'css',
							'value' => 'assets/custom/css/chosen'
						),
						array(
							'type' => 'js',
							'value' => 'assets/custom/js/chosen'
						),
					),
		'dashboard' => array(
							array(
								'type' => 'js',
								'value' => 'assets/custom/vendors/js/extensions/jquery.knob.min'
							),
							array(
								'type' => 'js',
								'value' => 'assets/custom/js/scripts/cards/card-statistics.min'
							),
						),
		'fancybox' => array(
						array(
							'type' => 'js',
							'value' => 'assets/custom/fancybox/jquery.fancybox'
						),
						array(
							'type' => 'js',
							'value' => 'assets/custom/fancybox/jquery-browser'
						),
						array(
							'type' => 'css',
							'value' => 'assets/custom/fancybox/jquery.fancybox'
						),
					),
		'dropzone' => array(
						array(
							'type' => 'header_js',
							'value' => 'assets/custom/vendors/js/extensions/dropzone.min'
						),
						array(
							'type' => 'header_js',
							'value' => 'assets/custom/js/jquery.redirect'
						),
						array(
							'type' => 'css',
							'value' => 'assets/custom/vendors/css/file-uploaders/dropzone.min'
						),
						array(
							'type' => 'css',
							'value' => 'assets/custom/css/plugins/file-uploaders/dropzone.min'
						),
					),
		'multiselect' => array(
						array(
							'type' => 'js',
							'value' => 'assets/custom/multiselect-master/dist/js/bootstrap-multiselect'
						),
						array(
							'type' => 'css',
							'value' => 'assets/custom/multiselect-master/docs/css/multiselect'
						),
					),
					
		'boot-inputtag' => array(
			array(
				'type' => 'css',
				'value' => 'assets/custom/bootstrap-tagsinput/dist/bootstrap-tagsinput'
			),
			array(
				'type' => 'js',
				'value' => 'assets/custom/bootstrap-tagsinput/dist/bootstrap-tagsinput'
			)
		),
	);
	
	$output['header_js'] = array(
		'assets/vendors/js/vendors.min',
		'assets/js/custom_js'
	);

	foreach( $values as $value ) {
		$inputs = $options[$value];
		foreach( $inputs as $input ) {
			$output[$input['type']][] = $input['value'];
		}
	}

	return $output;
}

function load_page($views, $data = array()) {
	$CI =& get_instance();
	
	if( sizeof($data['inclusions']) == 0 ) {
		$inclusions = inclusions();
	} else {
		$inclusions = $data['inclusions'];
	}
	
	

	$data['inclusions'] = array_merge($data['inclusions'], $inclusions);
   
	$CI->load->view('layout/header', $data);

	if( !is_array($views) ) $views = array($views);
	foreach( $views as $view ) {
        $CI->load->view($view);
	}
	
	$CI->load->view('layout/footer');
}
?>