<?php defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('show'))
{
	function show($data,$exit = FALSE)
	{
		echo "<pre>";
		print_r($data);
		echo "</pre>";
		if($exit) exit();
	}
}

function isWeekend($date) {
    $weekDay = date('w', strtotime($date));

    return ($weekDay == 0) ? date( 'Y-m-d', strtotime($date . ' -2 day' )) : ( ($weekDay == 6) ? date( 'Y-m-d', strtotime($date . ' -1 day' )) : $date);
}


function getNetWorkDays($startDate,$endDate,$holidays = array()) {
    
    $endDate = strtotime($endDate);
    $startDate = strtotime($startDate);

    $days = ($endDate - $startDate) / 86400 + 1;
    $no_full_weeks = floor($days / 7);
    $no_remaining_days = fmod($days, 7);
    //It will return 1 if it's Monday,.. ,7 for Sunday
    $the_first_day_of_week = date("N", $startDate);
    $the_last_day_of_week = date("N", $endDate);

    if ($the_first_day_of_week <= $the_last_day_of_week) {
        if ($the_first_day_of_week <= 6 && 6 <= $the_last_day_of_week) $no_remaining_days--;
        if ($the_first_day_of_week <= 7 && 7 <= $the_last_day_of_week) $no_remaining_days--;
    }
    else {

        if ($the_first_day_of_week == 7) {
            $no_remaining_days--;
            if ($the_last_day_of_week == 6) {
                $no_remaining_days--;
            }
        }
        else {
            $no_remaining_days -= 2;
        }
    }

   	$workingDays = $no_full_weeks * 5;
    if ($no_remaining_days > 0 )
    {
      $workingDays += $no_remaining_days;
    }
    //We subtract the holidays
    foreach($holidays as $holiday){
        $time_stamp=strtotime($holiday);
        //If the holiday doesn't fall in weekend
        if ($startDate <= $time_stamp && $time_stamp <= $endDate && date("N",$time_stamp) != 6 && date("N",$time_stamp) != 7)
            $workingDays--;
    }
    return ($workingDays > 0) ? $workingDays-1 : $workingDays;
   
}

if ( ! function_exists('getWorkingDays_increament')) 
{	
	function getWorkingDays_increament($date_required, $holiday_date_array=array(), $days)
	{
		if(!empty($date_required) && $days){
		    $counter_1=0;
		    $incremented_date = '';
		    for($i=1; $i <= $days; $i++){

		        $date = strtotime("+$i day", strtotime($date_required));
		        $day_name = date("D", $date);
		        
		        $incremented_date = date("Y-m-d", $date);
		        if($day_name=='Sat'||$day_name=='Sun'|| in_array($incremented_date ,$holiday_date_array)==true){
		            $counter_1+=1;
		        }
		    }
		    if($counter_1 > 0){
		        return getWorkingDays_increament($incremented_date, $holiday_date_array, $counter_1);
		    }else{
		        return $incremented_date;
		    }
		}else{
		    return FALSE;
		}
	}
}


if ( ! function_exists('status_color'))
{
	function status_color($status)
	{
		switch ($status) {
			case 'Clear':
				return '#4C9900';
				break;
		    case 'Insufficiency':
				return '#e60730';
				break;	
             case 'Clear':
				return '#0d690f';
				break;	

			case 'Unable to Verify':
				return '#FF9933';
				break;
			case 'Discrepancy':
				return '#FF0000';
				break;
			default:
				return '';
				break;
		}
	}
}

if ( ! function_exists('getWorkingDays')) 
{	
	function getWorkingDays($date_required, $holiday_date_array=array(), $days)
	{
		
		if(!empty($date_required) && $days){
		    $counter_1=0;
		    $incremented_date = '';
          
            $date_required1 = date('Y-m-d', strtotime('-1 day', strtotime($date_required)));

		    for($i=1; $i <= $days; $i++){

		        $date = strtotime("+$i day", strtotime($date_required1));
		        $day_name = date("D", $date);
		        
		        $incremented_date = date("Y-m-d", $date);
		        if($day_name=='Sat'||$day_name=='Sun'|| in_array($incremented_date ,$holiday_date_array)==true){
		            $counter_1+=1;
		        }
		    }
		    if($counter_1 > 0){
		        return getWorkingDays_increament($incremented_date, $holiday_date_array, $counter_1);
		    }else{
		        return $incremented_date;
		    }
		}else{
		    return FALSE;
		}
	}
}

function post_data_array_to_json($data)
{
	$return_arry = array();
	foreach ($data as $key => $value) {
		$return_arry[$key] = $value;
	}

	return json_encode($return_arry);
}

if ( ! function_exists('export_to_csv'))
{
	function export_to_csv($data_to_export,$file_name) {
		
		ob_start();

        $output = fopen("php://output", 'w');

        header('Content-Type: application/csv; charset=utf-8');

        header("Content-Type:application/csv");
        
        header('Pragma: no-cache');
        
        $output = fopen('php://output', 'w');

        if($data_to_export)
		{
	        $header = implode(',', array_keys($data_to_export[0]));

	        fputcsv($output, explode(",", $header));

	        foreach ($data_to_export as $value) {
	            fputcsv($output, $value);
	        }
	    }
	    else
	    {
	    	fputcsv($output, array('No Log Found'));
	    }

        header('Content-Disposition: attachment; filename="' . $file_name . '"');
        fclose($output);
        ob_end_flush();
		exit();
	}
}

if ( ! function_exists('status_frm_db'))
{
	function status_frm_db($where = array('components_id' => 0)) {
		
		$CI =& get_instance();

		$CI->db->select('id,status_value')->from('status')->where($where);

		$result  = $CI->db->get();

	 	$result_array = $result->result_array();

	 	$loop = array();
        
        $loop['0'] = 'Select Status';

        foreach ($result_array as $key => $value) {
            $loop[$value['id']] = $value['status_value'];
        }
        return $loop;
	}
}

if ( ! function_exists('status_frm_db1'))
{
	function status_frm_db1($where = array('components_id' => 0 , 'id' => 1)) {
		
		$CI =& get_instance();

		$CI->db->select('id,status_value')->from('status')->where($where);

		$result  = $CI->db->get();

	 	$result_array = $result->result_array();

	 	$loop = array();
        
        $loop['0'] = 'Select Status';

        foreach ($result_array as $key => $value) {
            $loop[$value['id']] = $value['status_value'];
        }
        return $loop;
	}
}



if ( ! function_exists('status_id_frm_db'))
{
	function status_id_frm_db($where = array('components_id' => 0)) {
		$CI =& get_instance();

		$result_array = $CI->db->select('*')->from('status')->where($where)->get()->result_array();
	 	if(!empty($result_array)) {
	 		$result_array = $result_array[0];
	 	}
	 	return $result_array;
	}
}


if ( ! function_exists('get_group_wise_status'))
{
	function get_group_wise_status($where = array()) {

		$CI =& get_instance();

		$result_array = $CI->db->select("GROUP_CONCAT(id SEPARATOR ',') as status_ids")->from('status')->where($where)->where('id !=', '9')->group_by('filter_status')->get()->result_array();
	 	if(!empty($result_array))
	 	{
	 		$result_array = $result_array[0];
	 	}
	 	return $result_array;
	}
}




if ( ! function_exists('permission_denied'))
{
	function permission_denied($json_array = array()) {
		$json_array['status'] = 400;
		$json_array['message'] = "We're sorry, you do not have access to this page.";
		echo_json($json_array);
		exit();
	}
}

if(! function_exists('auto_update_overall_status'))
{

	function auto_update_overall_status($where)
	{

		$cmd = 'php /var/www/html/'.SITE_FOLDER.'index.php cli_request_only overall_status_update '.$where;
		
		if (substr(php_uname(), 0, 7) == "Windows"){ 
			
		   pclose(popen("start /MIN ". $cmd, "r")); 

		}else{ 
		    exec($cmd . " > /dev/null &");   
		}
	}
}


if(! function_exists('auto_update_tat_status'))
{
 
	function auto_update_tat_status($where)
	{

		$cmd = 'php /var/www/html/'.SITE_FOLDER.'index.php cli_request_only update_tat_status_candidate '.$where;
		
		if (substr(php_uname(), 0, 7) == "Windows"){ 
		
		   pclose(popen("start /MIN ". $cmd, "r")); 

		}else{ 
		    exec($cmd . " > /dev/null &");   
		}
	}
}


if(! function_exists('all_component_closed_qc_status'))
{
 
	function all_component_closed_qc_status($where)
	{

		$cmd = 'php /var/www/html/'.SITE_FOLDER.'index.php cli_request_only auto_all_component_closed_qc_status '.$where;
		
		if (substr(php_uname(), 0, 7) == "Windows"){ 
		
		   pclose(popen("start /MIN ". $cmd, "r")); 

		}else{ 
		    exec($cmd . " > /dev/null &");   
		}
	}
}

if ( ! function_exists('convert_to_single_dimension_array'))
{
	function convert_to_single_dimension_array($multi_dimension_array,$key_from_multi,$value_from_multi)
	{ 
    	$single_dimension_array = Array();
    	$single_dimension_array[0] = 'Select';
		foreach ($multi_dimension_array as $multi_dimension)
		{
			$key = $multi_dimension[$key_from_multi];

			$value = ucwords($multi_dimension[$value_from_multi]);

			$single_dimension_array[$key] = $value;
		}
		
		return $single_dimension_array;
	}
}

if ( ! function_exists('convert_to_single_dimension_array1'))
{
	function convert_to_single_dimension_array1($multi_dimension_array,$key_from_multi,$value_from_multi)
	{ 
    	$single_dimension_array = Array();
    	
		foreach ($multi_dimension_array as $multi_dimension)
		{
			$key = $multi_dimension[$key_from_multi];

			$value = ucwords($multi_dimension[$value_from_multi]);

			$single_dimension_array[$key] = $value;
		}
		
		return $single_dimension_array;
	}
}

if ( ! function_exists('sanitize_url_param'))
{
	function sanitize_url_param($param)
	{
		$param =  trim($param);

		$param =  urldecode($param);

		return $param =  htmlentities($param);

	}
}

if ( ! function_exists('generate_random_str'))
{
	function generate_random_str($formate = 'numeric' , $len = 4)
	{
	  return random_string($formate, $len);
	}
}

if ( ! function_exists('folder_exist'))
{
	function folder_exist($folder_path)
	{
    	return (file_exists($folder_path) AND is_dir($folder_path));
	}
}

if ( ! function_exists('echo_json'))
{
	function echo_json($json_array,$set_csrf_token = false)
	{
		$CI =& get_instance();

		if($set_csrf_token === TRUE) {
			$json_array['rm457srftkn'] = $CI->security->get_csrf_hash();
		}
		
		header('Content-Type:application/json');
		
		echo json_encode($json_array);

		exit();
	}
}

if ( ! function_exists('convert_display_to_db_date'))
{
	function convert_display_to_db_date($str_display_date,$display_date_format = DISPLAY_DATE_FORMATDATE,$db_date_format = DATE_ONLY)
	{
		$is_valid_date = FALSE;

		$new_display_date  = DateTime::createFromFormat($display_date_format, $str_display_date);

		if($new_display_date && $new_display_date->format($display_date_format) == $str_display_date) {
			$is_valid_date = TRUE;
		}

		if($is_valid_date) {
			return $new_display_date->format($db_date_format);
		} else {
			return NULL;
		}
	}
}

function convert_db_to_display_date($str_db_date,$db_date_format = DATE_ONLY,$display_date_format = DISPLAY_DATE_FORMATDATE)
{
	$is_valid_date = FALSE;

	$new_db_date  = DateTime::createFromFormat($db_date_format, $str_db_date);

	if($new_db_date && $new_db_date->format($db_date_format) == $str_db_date)
	{
		$is_valid_date = TRUE;
	}

    if($is_valid_date){
		return $new_db_date->format($display_date_format);
	} else {
		return NULL;
	}
}

if ( ! function_exists('date_validate'))
{
	function date_validate($date) {
		
		if(!empty($date) || $date === NULL) {
			return date(DATE_ONLY, strtotime($date));
		} else {
			return false;
		}
	}
}

if ( ! function_exists('record_db_error'))
{
	function record_db_error($query)
	{
		/*This function writes database error into logs */

		if(ENVIRONMENT !== 'production'){
			return;
		}

		$CI =& get_instance();

		$db_error_info = $CI->db->call_function('error',$CI->db->conn_id);

		if(!$db_error_info)
		{
			return;
		}

		$mysql_error_no = $CI->db->call_function('errno',$CI->db->conn_id);

		$backtrace_array = debug_backtrace(FALSE);

		$backtrace_array = $backtrace_array[1];

		$file = $backtrace_array['file'];

		$line = $backtrace_array['line'];

		$message = sprintf("DB Error(%s) %s occurred at  %s -> %s with query:%s",$mysql_error_no,$db_error_info,$backtrace_array['class'],$backtrace_array['function'],$query);

		$CI->lib_log->error_handler('db',$message,$file,$line);
	}
}

if ( ! function_exists('create_password'))
{
	function create_password($password) {
		$options = ['cost' => 12];
	    return password_hash($password, PASSWORD_BCRYPT, $options);
	}
}

if ( ! function_exists('encrypt'))
{
	function encrypt($plan_str) {
		return $plan_str;
		// $key  = md5('p8I6o6zC73hS2YQ9sRlb2Eew1yTB15Vx');
		// $id   = base_convert($plan_str, 10, 36); // Save some space
		// $data = mcrypt_encrypt(MCRYPT_BLOWFISH, $key, $id, 'ecb');
		// $data = bin2hex($data);
		// return $data;
	}
}

if ( ! function_exists('decrypt'))
{
	function decrypt($encrypted_str) {
		return $encrypted_str;
		// $key  = md5('p8I6o6zC73hS2YQ9sRlb2Eew1yTB15Vx');
		// $data = pack('H*', $encrypted_str); // Translate back to binary
		// $data = mcrypt_decrypt(MCRYPT_BLOWFISH, $key, $data, 'ecb');
		// $data = base_convert($data, 36, 10);
		// return $data;
	}
}

function get_date_from_timestamp($value)
{
	if($value != "" && $value != "NA") {
		return date('Y-m-d',strtotime($value));
	} else {
		return NULL;
	}
}


function get_date_from_indian_timestamp($value)
{
	if($value != "" && $value != "NA") {
		return date('d-m-Y',strtotime($value));
	} else {
		return NULL;
	}
}

function get_date_time_from_timestamp_indian_format($value)
{
	if($value != "" && $value != "NA") {
		return date('d-m-Y h:i:sa',strtotime($value));
	} else {
		return NULL;
	}
}

if ( ! function_exists('fetch_company_details'))
{
	function fetch_company_details($company_name) {
		// zaubacorp api
		try {
			log_message('error', 'Data passing');
			log_message('error', $company_name);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,"https://www.zaubacorp.com/custom-search");
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,"search=".$company_name."&filter=company");

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			$server_output = curl_exec($ch);
			$server_output = trim($server_output);
			curl_close ($ch);
			$companies = [];
			if($server_output) {
				
				$server_output = explode('="', $server_output);
				
				foreach ($server_output as $key => $company) {

					if(strpos($company, 'company') !== false) {

						$name = explode('/', $company);
                      
						if(isset($name[1]) && isset($name[2])) {
							$cin = explode('">', $name[2]);
							if(isset($cin[0])) {
								$companies[]  = ['id' => $cin[0], 'company_name' => strip_tags($name[1])];
							}
						}
					}
				}
			}
			return  $companies;
			//return echo_json($companies);
		} catch (Exception $e) {
            log_message('error','Error on helper::fetch_company_details');
            log_message('error',$e->getMessage());
        }
	}

	function base64_to_jpeg($base64_string, $output_file) {
		try {
		
			$base64_string = str_replace('[removed]', '', $base64_string);
			$ifp = fopen( $output_file, 'wb' ); 

			$data = explode( ',', $base64_string );

			fwrite( $ifp, base64_decode( $base64_string ) );

			fclose( $ifp ); 

			return $output_file; 
		} catch (Exception $e) {
			log_message('error', 'hilper::base64_to_jpeg');
			log_message('error', $e->getMessage());
		}
    }
}

if ( ! function_exists('overall_status_color'))
{
	function overall_status_color($status)
	{	

		$status = strtolower($status);

		if($status == 'clear') {
			return '<td bgcolor="#4c9900" color = "#000000"><b>Green</b></td>';
		} else if($status == 'major discrepancy') {
			return '<td bgcolor="#ff2300" color = "#000000"><b>Red</b></td>';
		}
		 else  {
			return '<td color = "#000000"><b>'.$status.'</b></td>';	
		}
	}
}

if ( ! function_exists('get_attacments'))
{
  function get_attacments($details)
  {
    $attacments = array();
    
      $attacments[] = array('filename' => $details['selfie'],'lat_long' => $details['selfie_lat_long'],'type' => 'Selfie');
      $attacments[] = array('filename' => $details['address_proof'],'lat_long' => $details['address_proof_lat_long'],'type' => 'Address proof (Electricity/ Aadhar/Voters card, etc)');
      $attacments[] = array('filename' => $details['house_pic_door'],'lat_long' => $details['house_pic_door_lat_long'],'type' => 'Door/Gate photo');
      $attacments[] = array('filename' => $details['location_picture_2'],'lat_long' => $details['location_picture_lat_long_2'],'type' => 'Location Image 1 (Locality photo)');
      $attacments[] = array('filename' => $details['location_picture_1'],'lat_long' => $details['location_picture_lat_long_1'],'type' => 'Location Image 2 (Locality photo)');
      $attacments[] = array('filename' => $details['signature'],'lat_long' => $details['signature_lat_long'],'type' => 'Signature');
    
    return $attacments;
  }
}

if(! function_exists('auto_update_client_candidate_status'))
{
 
	function auto_update_client_candidate_status($where)
	{

		$cmd = 'php /var/www/html/'.SITE_FOLDER.'index.php Cli_request_only auto_update_client_candidates_status '.$where;
		
		
		if (substr(php_uname(), 0, 7) == "Windows"){ 
		
		   pclose(popen("start /MIN ". $cmd, "r")); 

		}else{ 
		    exec($cmd . " > /dev/null &");   
		}
	}
}
