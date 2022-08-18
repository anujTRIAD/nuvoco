<?php
//================= Email related functions =========================
function sendmail($subject, $body, $address = array(), $cc = array(), $bcc = array(), $attachment='') {
	require_once(FCPATH.'assets/phpmailer/PHPMailerAutoload.php');
	$mail = new PHPMailer();

	$mail = new PHPMailer;
	$mail->isSMTP();
	$mail->Host = 'smtp.gmail.com';
	$mail->Port = 587;
	$mail->SMTPSecure = 'tls';
	$mail->SMTPAuth = true;
	$mail->Username = GMAIL_USERNAME;
	$mail->Password = GMAIL_PASSWORD;
	$mail->setFrom('salesreport@addes.in', 'Team ADDES');
	$mail->addReplyTo('salesreport@addes.in', 'Team ADDES');

	if( is_array($address) && !empty($address) ){
	   
		foreach ($address as $value) {
			$mail->addAddress($value);
		}
	}
	if( is_array($cc) && !empty($cc) ){
		foreach ($cc as $value) {
			$mail->addCC($value);
		}
	}
	if( is_array($bcc) && !empty($bcc) ){
		foreach ($bcc as $value) {
			$mail->addBCC($value);
		}
	}
	/*if( !empty($attachment) ){
	    $mail->addAttachment($attachment);
	}*/
	
	if( is_array($attachment) && !empty($attachment) ){
		foreach ($attachment as $value) {
			$mail->addAttachment($value);
		}
	} else {
	   if( !empty($attachment) ){
	      $mail->addAttachment($attachment);
	   } 
	}

	$mail->Subject = $subject;
	$mail->msgHTML($body);

    
	if ( ! $mail->Send() ) {
		$errors = $mail->ErrorInfo;
		return false;
		//return $errors;
	} else {
		return true;
	}
}

//================ Session related functions =======================
function get_title($title, $trailing = true) {
	if( $trailing ) $title .= ' - '.SITE_NAME;
	return $title;
}

function user_logged_in( $redirect = '' ) {
	if ( get_session('logged_in') == 1 ) {
		if( !empty($redirect) ) redirect($redirect);
		return true;
	} else {
		return false;
	}
}

// function logged_in_user( $user_type ) {
// 	if( get_session('user_type') == $user_type ) {
// 		return true;
// 	}
// 	return false;
// }

function can_access() {
	$CI =& get_instance();
	$controller = $CI->router->fetch_class();
	$function = $CI->router->fetch_method();
	
	if( user_logged_in() ) {
		if( in_array($controller, get_session('controller')) ) {
			//go ahead
		} else {
			show_error('403');
		}
	} else {
		redirect('login?redirect='.current_url());
	}
}

function controller_access() {
	$CI =& get_instance();
	$controller = $CI->router->fetch_class();
	$function = $CI->router->fetch_method();
	
	$pass_change_date = new DateTime(get_pass_change_date());
    $current_date = new DateTime(date('Y-m-d'));
    $interval = $pass_change_date->diff($current_date);
    $diff = $interval->format('%a');
    
	if(in_array(get_session('role'), get_define_pass_change_role())) {
	    $define_date = get_define_pass_change_date(get_session('role'));
	} else {
	    $define_date = get_define_pass_change_date('all');
	}
	
	//if($diff > $define_date || is_forced_pass_change() == 1) {
	//if(is_forced_pass_change() == 1) {
	if(is_forced_pass_change() == 1 && is_forced_pass_change() != false) {
	    redirect('force-change-password');
	} else {
	    if( user_logged_in() ) {
    		if( in_array($controller, get_session('controller')) ) {
    			//go ahead
    		} else {
    			show_error('Not Authorized to access this section');
    		}
    	} else {
    		redirect('login?redirect='.get_current_url());
    	}
	}
}

function function_access() {
	$CI =& get_instance();
	$controller = $CI->router->fetch_class();
	$function = $controller.'~'.$CI->router->fetch_method();
	
	if( user_logged_in() ) {
		if( in_array($function, get_session('function')) ) {
			//go ahead
		} else {
			show_error('Not Authorized to access this function');
		}
	} else {
		redirect('login?redirect='.current_url());
	}
}

function only_for($controller, $function='') {
	if( user_logged_in() ) {
		if( in_array($controller, get_session('controller')) ) {
			if( !empty($function) ){
			    if( in_array($function, get_session('function')) ) {
        			return true;
        		}
        		return false;
			}
			return true;
		}
		return false;
	} else {
		redirect('login');
	}
}



function unset_login_sessions() {
	$data = array(
		'logged_in',
		'username',
		'role',
		'role_type',
		'name',
		'email',
		'uniquecode',
		'mobile',
		'image',
		'zone',
		'state',
		'city',
		'mapping',
		'user_detail',
		'ip',
		'agent',
		'token',
	);
	foreach( $data as $value ) {
		unset_session($value);
	}
}

//======================= Common database functions ========================
function get_value($field, $table, $value, $where='id') {
	$CI =& get_instance();
	$output = false;
	
	$CI->db->select($field);
	$CI->db->from($table);
	$CI->db->where($where, $value);
	$query = $CI->db->get();
	if( $query->num_rows() > 0 ) {
		$result = $query->result_array();
		$output = $result[0][$field];
	}
	return $output;
}


//======================= Get column value with mulitple where Condition ========================
function get_column_value($field, $table, $value1, $where1,$value2, $where2) {
	$CI =& get_instance();
	$output = false;
	
	$CI->db->select($field);
	$CI->db->from($table);
	$CI->db->where($where1, $value1);
	$CI->db->where($where2, $value2);
	$query = $CI->db->get();
	if( $query->num_rows() > 0 ) {
		$result = $query->result_array();
		$output = $result[0][$field];
	}
	return $output;
}

function get_value_column_sum($field, $table, $value, $where='id') {
	$CI =& get_instance();
	$output = 0;
	
	$CI->db->select_sum($field);
	$CI->db->from($table);
	$CI->db->where($where, $value);
	$query = $CI->db->get();
	if( $query->num_rows() > 0 ) {
		$result = $query->row();
		$output = $result->$field;
		if( $output == null ){ $output = "0"; }
	}
	return $output;
}

// function set_value($field, $value, $table, $where_value, $where_cond = 'id') {
// 	$CI =& get_instance();
	
// 	$CI->db->set($field, $value);
// 	$CI->db->where($where_cond, $where_value);
// 	$result = $CI->db->update($table);
// 	return $result;
// }

function get_row($table, $id, $where='id', $where1='', $value1='') {
	$CI =& get_instance();
	$CI->db->from($table);
	$CI->db->where($where, $id);
	if( !empty($where1) ) {
		$CI->db->where($where1, $value1);
	}
	$query = $CI->db->get();
	if( $query->num_rows() > 0 ) {
		$result = $query->row_array();
		return $result;
	}
	return false;
}

/*---------------------Get multiple coumlms-------------------*/
function get_row_with_multi($table, $columns=array(),$id, $where='id', $where1='', $value1='') {
	$CI =& get_instance();

	$colum = implode(', ', $columns); 

	$CI->db->select($colum);
	$CI->db->from($table);
	$CI->db->where($where, $id);
	if( !empty($where1) ) {
		$CI->db->where($where1, $value1);
	}
	$query = $CI->db->get();
	if( $query->num_rows() > 0 ) {
		$result = $query->row_array();
		return $result;
	}
	return false;
}

function get_table($table, $where_value ='', $where ='', $where1='', $value1='') {
	$CI =& get_instance();
	if( !empty($where) ) {
		$CI->db->where($where, $where_value);
	}
	if( !empty($where1) ) {
		$CI->db->where($where1, $value1);
	}
	$query = $CI->db->get($table);
	if( $query->num_rows() > 0 ) {
		$result = $query->result_array();
		return $result;
	}
	return false;
}

function get_count($table, $where='', $value='', $where1='', $value1='') {
	$CI =& get_instance();
	$output = false;
	
	$CI->db->select('count(*) as total');
	if( !empty($where) ) {
		$CI->db->where($where, $value);
	}
	if( !empty($where1) ) {
		$CI->db->where($where1, $value1);
	}
	$query = $CI->db->get($table);
	if( $query->num_rows() > 0 ) {
		$result = $query->row_array();
		$output = $result['total']; 
	}
	return $output;
}

//============== Other common functions ============
function load_view($view, $data = NULL){
	$CI =& get_instance();
	$CI->load->view($view, $data);
}

function compare_datetime($a, $b) {
	$ad = strtotime($a['exact_date']);
	$bd = strtotime($b['exact_date']);

	if ($ad == $bd) {
		return 0;
	}

	return $ad > $bd ? 1 : -1;
}

function remove_dir($dir) {
	if (is_dir($dir)) {
		$objects = scandir($dir);
		foreach ($objects as $object) {
			if ($object != "." && $object != "..") {
				if (filetype($dir."/".$object) == "dir"){
					remove_dir($dir."/".$object);
				} else { 
					unlink($dir."/".$object);
				}
			}
		}
		reset($objects);
		rmdir($dir);
	}
}

function get_extention($file) {
	return pathinfo($file['name'], PATHINFO_EXTENSION);
}

function i_encode($url) {
	$CI =& get_instance();
	$uri = $CI->encryption->encrypt($url);
	$pattern = '"/"';
	$new_uri = preg_replace($pattern, '_', $uri);
	return $new_uri;
}

function i_decode($url) {
	$CI =& get_instance();
	$pattern = '"_"';
	$uri = preg_replace($pattern, '/', $url);
	$new_uri = $CI->encryption->decrypt($uri);
	return $new_uri;
}

function custom_encode($string) {
	$key = "AmaZoNAdDeS";
	$string = base64_encode($string);
	$string = str_replace('=', '', $string);
	$main_arr = str_split($string);
	$output = array();
	$count = 0;
	for( $i=0; $i<strlen($string); $i++) {
		$output[] = $main_arr[$i];
		if($i%2==1) {
			$output[] = substr($key, $count, 1);
			$count++;
		}
	}
	$string = implode('', $output);
	return $string;
}

function custom_decode($string) {
	$key = "AmaZoNAdDeS";
	$arr = str_split($string);
	$count = 0;
	for( $i=0; $i<strlen($string); $i++) {
		if( $count < strlen($key) ) {
			if($i%3==2) {
				unset($arr[$i]);
				$count++;
			}
		}
	}
	$string = implode('', $arr);
	$string = base64_decode($string);
	return $string;
}

function get_array_key($value, $array) {
	while ($single = current($array)) {
		if ($single == $value) {
			return key($array);
		}
		next($array);
	}
}


//================ include scripts and css ================



//==================  load content with header, left, footer  ====================


function delete_file($file_path) {
	if( is_file($file_path) ) {
		unlink($file_path);
	}
}

function format_datetime($datetime) {
	return date('j M, Y - h:ia', strtotime($datetime));
}

function format_date($date) {
	return date('j M, Y', strtotime($date));
}

function format_time($time) {
	return date('h:i A', strtotime($time));
}

function timezone_datetime($datetime = '') {
	$timezone_datetime = new DateTime($datetime, new DateTimeZone('Asia/Kolkata'));
	return $timezone_datetime;
}

function posted_ago($datetime, $full = false) {
	$now = timezone_datetime();
	$ago = timezone_datetime($datetime);

	$diff = $now->diff($ago);

	$diff->w = floor($diff->d / 7);
	$diff->d -= $diff->w * 7;

	$string = array(
		'y' => 'year',
		'm' => 'month',
		'w' => 'week',
		'd' => 'day',
		'h' => 'hour',
		'i' => 'minute',
		's' => 'second',
	);
	foreach ($string as $k => &$v) {
		if ($diff->$k) {
			$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
		} else {
			unset($string[$k]);
		}
	}

	if (!$full) $string = array_slice($string, 0, 1);
	return $string ? implode(', ', $string) . ' ago' : 'just now';
}


// function debug($item = array(), $die = true, $display = true) {
// 	if( is_array($item) || is_object($item) ) {
// 		echo "<pre ".($display?'':'style="display:none"').">"; print_r($item); echo "</pre>";
// 	} else {
// 		echo $item;
// 	}
	
// 	if( $die ) {
// 		die();
// 	}
// }

// function ci_debug() {
// 	$CI =& get_instance();
// 	$CI->output->enable_profiler(TRUE);
// }

function fieldset( $field = array() ) {
	echo '
	<div class="fieldset">
		<input type="'.(isset($field['type'])?$field['type']:'text').'" id="'.(isset($field['id'])?$field['id']:$field['name']).'" name="'.$field['name'].'" class="field" required />
		<label for="'.(isset($field['id'])?$field['id']:$field['name']).'">'.$field['label'].'</label>
	</div>
	';
}

function random_code($length = 16) {
	$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
	$code = substr( str_shuffle( $chars ), 0, $length );
	return $code;
}

function set_flashdata($name, $message, $class='') {
	$CI =& get_instance();
	
	$data = 'toastr.'.$class.'("'.$message.' !'.'", "'.ucfirst($class).'", {timeOut: 6000,showMethod:"slideDown",hideMethod:"slideUp"});';
	$CI->session->set_flashdata($name, $data);
}

function get_flashdata($name) {
	$CI =& get_instance();
	$data = $CI->session->flashdata($name);
	return $data;
}

function set_notification($message, $class) {
	set_flashdata('notification', $message, $class);
}

function get_notification() {
	$data = get_flashdata('notification');
	return $data;
}

function page_title($page_title) {
	echo '
		<div class="page_title">
			<h1>'.$page_title.'</h1>
		</div>
	';
}

function delete_document($file) {
	$document_path = FCPATH.'data/documents/'.$file;
	if( is_file($document_path) ) unlink($document_path);
}

function truncate($string, $word_count = 10) {
	$string = htmlspecialchars_decode(strip_tags($string));
	$words = explode(' ', $string);

	$output = '';
	foreach( $words as $key=>$word ) {
		if( $key < $word_count ) {
			$output .= $word.' ';
		}
	}

	if( sizeof( $words ) > $word_count ) {
		return $output.'...';
	}
	return $output;
}

function safe($data) {
	$CI =& get_instance();
	$data = $CI->security->xss_clean($data);
	return $data;
}

function month_diff($month1, $month2){
	//$m1 = date_create($month1);
	//$m2 = date_create($month2);
	//$diff = date_diff($m1, $m2);
	//return $diff->m;
	
	//$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
	
	//$diff = abs(strtotime($month1.'-01') - strtotime($month2.'-01'));    
	//$years = floor($diff / (365*60*60*24));
	//return $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
	$m = round(abs(strtotime($month1) - strtotime($month2))/86400);
	if( $m >= 28 ){
		return '1';
	} else {
		return '0';
	}
}

function month_array($month1, $month2){
	$start = new DateTime($month1);
	$end = new DateTime($month2);
	$end->modify('first day of next month');
	$interval = DateInterval::createFromDateString('1 month');
	$period = new DatePeriod($start, $interval, $end);
	
	foreach ($period as $dt) {
		$arr[] = $dt->format("Y-m");
	}
	return $arr;
}

function linear_regression( $x, $y ) {
 
	$n     = count($x);     // number of items in the array
	$x_sum = array_sum($x); // sum of all X values
	$y_sum = array_sum($y); // sum of all Y values
 
	$xx_sum = 0;
	$xy_sum = 0;
 
	for($i = 0; $i < $n; $i++) {
		$xy_sum += ( $x[$i]*$y[$i] );
		$xx_sum += ( $x[$i]*$x[$i] );
	}
 
	// Slope
	$slope = ( ( $n * $xy_sum ) - ( $x_sum * $y_sum ) ) / ( ( $n * $xx_sum ) - ( $x_sum * $x_sum ) );
 
	// calculate intercept
	$intercept = ( $y_sum - ( $slope * $x_sum ) ) / $n;
 
	return array( 
		'slope'     => $slope,
		'intercept' => $intercept,
	);
}

function slope($x, $y) {
 
	$n     = count($x);     // number of items in the array
	$x_sum = array_sum($x); // sum of all X values
	$y_sum = array_sum($y); // sum of all Y values
 
	$xx_sum = 0;
	$xy_sum = 0;
 
	for($i = 0; $i < $n; $i++) {
		$xy_sum += ( $x[$i]*$y[$i] );
		$xx_sum += ( $x[$i]*$x[$i] );
	}
 
	// Slope
	$slope = ( ( $n * $xy_sum ) - ( $x_sum * $y_sum ) ) / ( ( $n * $xx_sum ) - ( $x_sum * $x_sum ) );
 
	return $slope;
}

function median($arr){
	if($arr){
		$count = count($arr);
		sort($arr);
		$mid = floor(($count-1)/2);
		return ($arr[$mid]+$arr[$mid+1-$count%2])/2;
	}
	return 0;
}

function create_date_range_array($strDateFrom, $strDateTo) {
	$aryRange = array();
	$iDateFrom = mktime(1,0,0,substr($strDateFrom,5,2), substr($strDateFrom,8,2), substr($strDateFrom,0,4));
	$iDateTo = mktime(1,0,0,substr($strDateTo,5,2), substr($strDateTo,8,2), substr($strDateTo,0,4));

	if ($iDateTo>=$iDateFrom) {
		array_push($aryRange,date('Y-m-d',$iDateFrom)); // first entry
		while ($iDateFrom<$iDateTo)
		{
			$iDateFrom+=86400; // add 24 hours
			array_push($aryRange,date('Y-m-d',$iDateFrom));
		}
	}
	return $aryRange;
}

function create_date_range_array_one($strDateTo) {
	$strDateFrom = date('Y-m-01', strtotime($strDateTo));

	$aryRange = array();
	$iDateFrom = mktime(1,0,0,substr($strDateFrom,5,2), substr($strDateFrom,8,2), substr($strDateFrom,0,4));
	$iDateTo = mktime(1,0,0,substr($strDateTo,5,2), substr($strDateTo,8,2), substr($strDateTo,0,4));

	if ($iDateTo>=$iDateFrom) {
		array_push($aryRange,date('Y-m-d',$iDateFrom)); // first entry
		while ($iDateFrom<$iDateTo)
		{
			$iDateFrom+=86400; // add 24 hours
			array_push($aryRange,date('Y-m-d',$iDateFrom));
		}
	}
	return $aryRange;
}

function get_current_url(){
    $CI =& get_instance();
    $url = $CI->config->site_url($CI->uri->uri_string());
    return $_SERVER['QUERY_STRING'] ? $url.'?'.$_SERVER['QUERY_STRING'] : $url;
}


function outputCsv($fileName, $assocDataArray)
{
    //ob_clean();
    header('Pragma: public');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Cache-Control: private', false);
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename=' . $fileName);    
    if(isset($assocDataArray['0'])){
        $fp = fopen('php://output', 'w');
        fputcsv($fp, array_keys($assocDataArray['0']));
        foreach($assocDataArray AS $values){
            fputcsv($fp, $values);
        }
        fclose($fp);
    }
    //ob_flush();
}

function is_forced_pass_change() {
    $CI =& get_instance();
	$output = false;
	$username = (get_session('username') != '') ? get_session('username') : '';
	
	$CI->db->select('is_forced_pass_change');
	$CI->db->where('username', $username);
	$query = $CI->db->get('user');
	if( $query->num_rows() > 0 ) {
		$result = $query->row_array();
		$output = $result['is_forced_pass_change']; 
	}
	return $output;
}

function get_pass_change_date() {
    $CI =& get_instance();
	$output = false;
	
	$CI->db->select('password_change_date');
	$CI->db->where('username', get_session('username'));
	$CI->db->where('password_change_date !=', '0000-00-00');
	$query = $CI->db->get('user');
	if( $query->num_rows() > 0 ) {
		$result = $query->row_array();
		$output = $result['password_change_date']; 
	}
	return $output;
}

function get_define_pass_change_date($role) {
    $CI =& get_instance();
	$output = false;
	
	$CI->db->select('days');
	$CI->db->where('role', $role);
	$query = $CI->db->get('change_password_days');
	if( $query->num_rows() > 0 ) {
		$result = $query->row_array();
		$output = $result['days']; 
	}
	return $output;
}

function get_define_pass_change_role() {
    $CI =& get_instance();
	$output = false;
	
	$CI->db->select('role');
	$CI->db->group_by('role');
	$query = $CI->db->get('change_password_days');
	if( $query->num_rows() > 0 ) {
		$result = $query->result_array();
		$output = call_user_func_array("array_merge", $result);
	}
	return $output;
}

function dateDiffInDays($date1, $date2)  { 
    // Calulating the difference in timestamps 
    $diff = strtotime($date2) - strtotime($date1); 
      
    // 1 day = 24 hours 
    // 24 * 60 * 60 = 86400 seconds 
    return abs(round($diff / 86400)); 
} 


//======== Is Display Unit Yes Function =========
function is_display_unit_yes_web($dsn_no) {
	$CI =& get_instance();
	$CI->db->select('id');
	$CI->db->from('stock');
	$CI->db->where_in('dsn', $dsn_no);
	$CI->db->where('is_display_unit', 'yes');
	$query = $CI->db->get();
	if( $query->num_rows() > 0 ) {
		return false;
	}
	return true;
}
//======== common function by Ajit =========

function get_records($select='*',$table,$where=array(),$type='all'){
    $CI = & get_instance();
    $CI->db->select($select);
    $CI->db->from($table);
    if(!empty($where)){
         $CI->db->where($where);
    }
    $temp =  $CI->db->get();
    if($temp){
       
        if($type=='one'){
           return $temp->row_array();
        }
        
        return $temp->result_array();
        
    }else{
        return false;
    }
}

