<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users_model extends CI_Model
{
	function __construct()
    {
		$this->tableName = 'user_profile';

		$this->primaryKey = 'id';
	}

	public function select($return_as_strict_row,$select_array, $where_array=array())
	{
		$this->db->select($select_array);

		$this->db->from($this->tableName);

		$this->db->where($where_array);
		
		$result  = $this->db->get();
         
		record_db_error($this->db->last_query());
		
		$result_array = $result->result_array();
   
        if($return_as_strict_row)
		{
            if(count($result_array)==1) // ensure only one record has been previously inserted
            {
                $result_array  = $result_array[0];
            }
        }
        return $result_array;
	}

	public function save($arrdata,$arrwhere = array())
	{

		
	    if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update($this->tableName, $arrdata);
        
			record_db_error($this->db->last_query());

			return $result;
	    }
	    else
	    {
			$this->db->insert($this->tableName, $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	    }
	}

	public function delete($arrwhere)
	{
	  	$result =  $this->db->delete($this->tableName, $arrwhere);

	  	record_db_error($this->db->last_query());
	  
		return $result;
	}

	public function user_role_gorup_details($where_array = array())
	{
		$this->db->select('user_profile.status,user_profile.id,user_profile.email,user_profile.mobile_phone,user_profile.office_phone,user_profile.department,user_profile.reporting_manager,user_profile.import_permission,user_profile.user_name,user_profile.profile_pic,user_profile.firstname,user_profile.lastname,user_profile.bill_date_permission,(select user_name from user_profile where user_profile.id = user_profile.created_by limit 1) as created_by,user_profile.created_on,roles.role_name,roles.groups_id,roles_permissions.*');

		$this->db->from('user_profile');

		$this->db->join('roles','roles.id = user_profile.tbl_roles_id');

		$this->db->join('roles_permissions','roles_permissions.tbl_roles_id = roles.id');

		$this->db->where($where_array);

		$result  = $this->db->get();
		
		record_db_error($this->db->last_query());
		
		$result_array = $result->result_array();
   
        if(!empty($where_array) && !empty($result))
		{
         	$result_array  = $result_array[0];
        }
        return $result_array;
	}

	public function user_role_gorup_details_user($where_array = array(),$where,$columns)
	{
		$this->db->select('user_profile.status,user_profile.id,user_profile.email,user_profile.department,user_profile.reporting_manager,user_profile.user_name,user_profile.profile_pic,user_profile.firstname,user_profile.lastname,(select user_name from user_profile where user_profile.id = user_profile.created_by limit 1) as created_by,user_profile.created_on,roles.role_name,roles.groups_id,roles_permissions.*');

		$this->db->from('user_profile');

		$this->db->join('roles','roles.id = user_profile.tbl_roles_id');

		$this->db->join('roles_permissions','roles_permissions.tbl_roles_id = roles.id');

		if(isset($where['filter_by_status']) &&  $where['filter_by_status'] != '')	
		{  
		    $filter_by_status  =  $where['filter_by_status'];
	          
		    $where_status = "user_profile.status = $filter_by_status";
               
            $this->db->where($where_status); 

		}

		$this->db->where($where_array);

		if(is_array($where) && $where['search']['value'] != "" )
		{
            $this->db->like('user_profile.firstname', $where['search']['value']);

			$this->db->or_like('user_profile.lastname', $where['search']['value']);

			$this->db->or_like('user_profile.user_name', $where['search']['value']);

			$this->db->or_like('user_profile.email', $where['search']['value']);
            
			$this->db->or_like('user_profile.department', $where['search']['value']);

		}

		if(!empty($where['order']))
		{

			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir'];
            
			$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		}
		else
		{
			$this->db->order_by('user_profile.id','ASC');
		}

		$result  = $this->db->get();
		//print_r($this->db->last_query());   
		record_db_error($this->db->last_query());
		
		$result_array = $result->result_array();
   
        if(!empty($where_array) && !empty($result))
		{
         	$result_array  = $result_array[0];
        }
        return $result_array;
	}
    
    public function access_page_ids($where_in)
    {
    	
    	$this->db->select("GROUP_CONCAT(DISTINCT tbl_admin_menu_id ORDER BY tbl_admin_menu_id ASC SEPARATOR ';') as unique_pages_ids");

		$this->db->from('groups');

		$this->db->where_in('id', $where_in);

		$result  = $this->db->get();
		
		record_db_error($this->db->last_query());
		
		$result_array = $result->result_array();

		if(!empty($result_array))
		{
			$result_array = $result_array[0];
		}

		return $result_array;
    }	

    public function multiple_insert($data_arr,$tableName)
	{
		if($tableName)
		{
			return $this->db->insert_batch($tableName, $data_arr);
		}
	}

	public function is_email_exists($email)
	{
		$this->db->select('id');

		$this->db->from($this->tableName);

		$this->db->where(array('user_email'=>$email,'user_status'=>0));

		$result =  $this->db->get();
		record_db_error($this->db->last_query());

		return (bool) $result->num_rows();
	}

	public function user_permission($return_as_strict_row,$select_array, $where_array=array())
	{
		$this->db->select($select_array);

		$this->db->from('groups_permissions');

		$this->db->where($where_array);
		
		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		$result_array = $result->result_array();

        if($return_as_strict_row)
		{
            if(count($result_array)==1) // ensure only one record has been previously inserted
            {
                $result_array  = $result_array[0];
            }
        }
        return $result_array;
	}

	public function status_count_address($clientid,$from,$to)
	{     
		$where_condition = "DATE_FORMAT(`addrverres`.`closuredate`,'%Y-%m-%d') BETWEEN '$from' AND '$to'";
        $array_condition_wip_insuff =  array('18','11','12','13','14','16','23','26','1');
        $array_condition_closed =  array('9','17','19','20','21','22','24','25','27','28');
		$this->db->select("CASE  WHEN addrverres.verfstatus = 18 THEN 'Insufficiency' WHEN addrverres.verfstatus = 11 THEN 'Insufficiency Cleared' WHEN addrverres.verfstatus = 12 THEN 'Final QC Reject' WHEN addrverres.verfstatus = 13 THEN 'First QC Reject' WHEN addrverres.verfstatus = 14 THEN 'New Check' WHEN addrverres.verfstatus = 16 THEN 'YTR' WHEN addrverres.verfstatus = 23 THEN 'Follow Up' WHEN addrverres.verfstatus = 26 THEN 'Re-Initiated' WHEN addrverres.verfstatus = 1 THEN 'WIP'  END AS status_value,COUNT(addrverres.verfstatus) as total");

		$this->db->from('addrver');

		$this->db->join("addrverres",'addrverres.addrverid = addrver.id');

		if(!empty($clientid))
		{

		 $this->db->where("addrver.clientid",$clientid);

	    }

        if($this->user_info['tbl_roles_id']  != "1")
        {
          
            $this->db->where("addrver.has_case_id",$this->user_info['id']);   
        }

	    $this->db->where_in('addrverres.verfstatus',$array_condition_wip_insuff);
   
		$this->db->group_by('addrverres.verfstatus');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$addrver = $result->result_array();

		$this->db->select("CASE WHEN  addrverres.verfstatus =  9 THEN 'Stop Check' WHEN addrverres.verfstatus =  17  THEN 'Clear' WHEN addrverres.verfstatus = 19  THEN 'Major Discrepancy'  WHEN addrverres.verfstatus =  20 THEN 'Minor Discrepancy' WHEN addrverres.verfstatus = 21 THEN 'Unable to verify'  WHEN addrverres.verfstatus = 22 THEN 'Worked with the same organization' WHEN addrverres.verfstatus = 24 THEN 'Overseas check' WHEN addrverres.verfstatus = 25 THEN 'No Record Found' WHEN addrverres.verfstatus = 27 THEN 'Change of Address' WHEN addrverres.verfstatus = 28 THEN 'NA'  END AS status_value,COUNT(addrverres.verfstatus) as total");


		$this->db->from('addrver');

		$this->db->join("addrverres",'addrverres.addrverid = addrver.id');

		if(!empty($clientid))
		{

		 $this->db->where("addrver.clientid",$clientid);

	    }

	    if($this->user_info['tbl_roles_id']  != "1")
        {
          
            $this->db->where("addrver.has_case_id",$this->user_info['id']);   
        }
   
        $this->db->where($where_condition);

        $this->db->where_in('addrverres.verfstatus',$array_condition_closed);

		$this->db->group_by('addrverres.verfstatus');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$addrver_closed = $result->result_array();


		$results_addrver = array_reduce($addrver, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());

		$results_addrver_closed = array_reduce($addrver_closed, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());

    
        $return['total'] = array_sum($results_addrver) + array_sum($results_addrver_closed);
		
		//if(!isset($results['WIP']))
		//{
		$results['WIP'] = '0';	
		//}
        if(!isset($results['Insufficiency']))
		{
        $results['Insufficiency'] = '0';
        }
		
        $results['Closed'] = '0';
		$return['NoRecordFound'] = '0'; 
		

		if(!empty($results))
		{

	        if(array_key_exists('Insufficiency Cleared',$results_addrver)) {
	            $results['WIP'] = $results['WIP'] + $results_addrver['Insufficiency Cleared'];
	        }

	        if(array_key_exists(NULL,$results_addrver)) {
	            $results['WIP'] = $results['WIP'] + $results_addrver[''];
	        }

	        if(array_key_exists('WIP',$results_addrver)) {
	            $results['WIP'] = $results['WIP'] + $results_addrver['WIP'];
	        }

	        if(array_key_exists('Final QC Reject',$results_addrver)) {
	            $results['WIP'] = $results['WIP'] + $results_addrver['Final QC Reject'];
	        }

	        if(array_key_exists('First QC Reject',$results_addrver)) {
	            $results['WIP'] = $results['WIP'] + $results_addrver['First QC Reject'];
	        }

	        if(array_key_exists('New Check',$results_addrver)) {
	            $results['WIP'] = $results['WIP'] + $results_addrver['New Check'];
	        }

	        if(array_key_exists('YTR',$results_addrver)) {
	            $results['WIP'] = $results['WIP'] + $results_addrver['YTR'];
	        }


	        if(array_key_exists('Follow Up',$results_addrver)) {
	            $results['WIP'] = $results['WIP'] + $results_addrver['Follow Up'];
	        }

	        if(array_key_exists('Re-Initiated',$results_addrver)) {
	            $results['WIP'] = $results['WIP'] + $results_addrver['Re-Initiated'];
	        }


	        if(array_key_exists('Insufficiency',$results_addrver)) {
	        
	            $results['Insufficiency'] = $results_addrver['Insufficiency'];
	        }

	        if(array_key_exists('Stop Check',$results_addrver_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_addrver_closed['Stop Check'];
	        }
            
            if(array_key_exists('Clear',$results_addrver_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_addrver_closed['Clear'];
	        }

	        if(array_key_exists('Major Discrepancy',$results_addrver_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_addrver_closed['Major Discrepancy'];
	        }

	         if(array_key_exists('Minor Discrepancy',$results_addrver_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_addrver_closed['Minor Discrepancy'];
	        }

	        if(array_key_exists('Unable to verify',$results_addrver_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_addrver_closed['Unable to verify'];
	        }

	        if(array_key_exists('Worked with the same organization',$results_addrver_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_addrver_closed['Worked with the same organization'];
	        }

	        if(array_key_exists('Overseas check',$results)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_addrver_closed['Overseas check'];
	        }

	        if(array_key_exists('No Record Found',$results)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_addrver_closed['No Record Found'];
	        }

	     
	        if(array_key_exists('Change of Address',$results_addrver_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_addrver_closed['Change of Address'];
	        }

	        if(array_key_exists('NA',$results_addrver_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_addrver_closed['NA'];
	        }
	     
	     
			foreach ($results as $key => $value) {
				$return[str_replace('/','',str_replace(' ','',$key))] = $value;
			}
			
		}
       return $return;
         
	}


	public function status_count_employment($clientid,$from,$to)
	{

		$where_condition = "DATE_FORMAT(`empverres`.`closuredate`,'%Y-%m-%d') BETWEEN '$from' AND '$to'";
        $array_condition_wip_insuff =  array('18','11','12','13','14','16','23','26','1');
        $array_condition_closed =  array('9','17','19','20','21','22','24','25','27','28');

		$this->db->select("CASE  WHEN empverres.verfstatus = 18 THEN 'Insufficiency' WHEN empverres.verfstatus = 11 THEN 'Insufficiency Cleared' WHEN empverres.verfstatus = 12 THEN 'Final QC Reject' WHEN empverres.verfstatus = 13 THEN 'First QC Reject' WHEN empverres.verfstatus = 14 THEN 'New Check' WHEN empverres.verfstatus = 16 THEN 'YTR' WHEN empverres.verfstatus = 23 THEN 'Follow Up' WHEN empverres.verfstatus = 26 THEN 'Re-Initiated' WHEN empverres.verfstatus = 1 THEN 'WIP'  END AS status_value,COUNT(empverres.verfstatus) as total");

		$this->db->from('empver');

        $this->db->join("empverres",'empverres.empverid = empver.id');

        if(!empty($clientid))
		{

		 $this->db->where("empver.clientid",$clientid);

	    }

	    if($this->user_info['tbl_roles_id']  != "1")
        {
          
            $this->db->where("empver.has_case_id",$this->user_info['id']);   
        }

	    $this->db->where_in('empverres.verfstatus',$array_condition_wip_insuff);
 
        $this->db->group_by('empverres.verfstatus');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$empver = $result->result_array();

		$this->db->select("CASE WHEN  empverres.verfstatus =  9 THEN 'Stop Check' WHEN empverres.verfstatus =  17  THEN 'Clear' WHEN empverres.verfstatus = 19  THEN 'Major Discrepancy'  WHEN empverres.verfstatus =  20 THEN 'Minor Discrepancy' WHEN empverres.verfstatus = 21 THEN 'Unable to verify'  WHEN empverres.verfstatus = 22 THEN 'Worked with the same organization' WHEN empverres.verfstatus = 24 THEN 'Overseas check' WHEN empverres.verfstatus = 25 THEN 'No Record Found' WHEN empverres.verfstatus = 27 THEN 'Change of Address' WHEN empverres.verfstatus = 28 THEN 'NA'  END AS status_value,COUNT(empverres.verfstatus) as total");


		$this->db->from('empver');

		$this->db->join("empverres",'empverres.empverid = empver.id');

		if(!empty($clientid))
		{

		 $this->db->where("empver.clientid",$clientid);

	    }

	    if($this->user_info['tbl_roles_id']  != "1")
        {
          
            $this->db->where("empver.has_case_id",$this->user_info['id']);   
        }
   
        $this->db->where($where_condition);

        $this->db->where_in('empverres.verfstatus',$array_condition_closed);

		$this->db->group_by('empverres.verfstatus');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$empver_closed = $result->result_array();
		
		$results_empver = array_reduce($empver, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());

		$results_empver_closed = array_reduce($empver_closed, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());


    
        $return['total'] = array_sum($results_empver)  + array_sum($results_empver_closed);
		
		//if(!isset($results['WIP']))
		//{
		$results['WIP'] = '0';	
		//}
        if(!isset($results['Insufficiency']))
		{
        $results['Insufficiency'] = '0';
        }
		
        $results['Closed'] = '0';
		$return['NoRecordFound'] = '0'; 
		
		if(!empty($results))
		{

	        if(array_key_exists('Insufficiency Cleared',$results_empver)) {
	            $results['WIP'] = $results['WIP'] + $results_empver['Insufficiency Cleared'];
	        }

	        if(array_key_exists(NULL,$results_empver)) {
	            $results['WIP'] = $results['WIP'] + $results_empver[''];
	        }

	         if(array_key_exists('WIP',$results_empver)) {
	            $results['WIP'] = $results['WIP'] + $results_empver['WIP'];
	        }

	        if(array_key_exists('Final QC Reject',$results_empver)) {
	            $results['WIP'] = $results['WIP'] + $results_empver['Final QC Reject'];
	        }

	        if(array_key_exists('First QC Reject',$results_empver)) {
	            $results['WIP'] = $results['WIP'] + $results_empver['First QC Reject'];
	        }

	        if(array_key_exists('New Check',$results_empver)) {
	            $results['WIP'] = $results['WIP'] + $results_empver['New Check'];
	        }

	        if(array_key_exists('YTR',$results_empver)) {
	            $results['WIP'] = $results['WIP'] + $results_empver['YTR'];
	        }


	        if(array_key_exists('Follow Up',$results_empver)) {
	            $results['WIP'] = $results['WIP'] + $results_empver['Follow Up'];
	        }

	        if(array_key_exists('Re-Initiated',$results_empver)) {
	            $results['WIP'] = $results['WIP'] + $results_empver['Re-Initiated'];
	        }


	        if(array_key_exists('Insufficiency',$results_empver)) {
	        
	            $results['Insufficiency'] = $results_empver['Insufficiency'];
	        }

	        if(array_key_exists('Stop Check',$results_empver_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_empver_closed['Stop Check'];
	        }
            
            if(array_key_exists('Clear',$results_empver_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_empver_closed['Clear'];
	        }

	        if(array_key_exists('Major Discrepancy',$results_empver_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_empver_closed['Major Discrepancy'];
	        }

	         if(array_key_exists('Minor Discrepancy',$results_empver_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_empver_closed['Minor Discrepancy'];
	        }

	        if(array_key_exists('Unable to verify',$results_empver_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_empver_closed['Unable to verify'];
	        }

	        if(array_key_exists('Worked with the same organization',$results_empver_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_empver_closed['Worked with the same organization'];
	        }

	        if(array_key_exists('Overseas check',$results_empver_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_empver_closed['Overseas check'];
	        }

	        if(array_key_exists('No Record Found',$results_empver_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_empver_closed['No Record Found'];
	        }

	        if(array_key_exists('Change of Address',$results_empver_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_empver_closed['Change of Address'];
	        }

	        if(array_key_exists('NA',$results_empver_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_empver_closed['NA'];
	        }
	     
	     
			foreach ($results as $key => $value) {
				$return[str_replace('/','',str_replace(' ','',$key))] = $value;
			}
			
		}
       return $return;
         
	}

    public function status_count_education($clientid,$from,$to)
   	{ 

   		$where_condition = "DATE_FORMAT(`education_result`.`closuredate`,'%Y-%m-%d') BETWEEN '$from' AND '$to'";
        $array_condition_wip_insuff =  array('18','11','12','13','14','16','23','26','1');
        $array_condition_closed =  array('9','17','19','20','21','22','24','25','27','28');

		$this->db->select("CASE  WHEN education_result.verfstatus = 18 THEN 'Insufficiency' WHEN education_result.verfstatus = 11 THEN 'Insufficiency Cleared' WHEN education_result.verfstatus = 12 THEN 'Final QC Reject' WHEN education_result.verfstatus = 13 THEN 'First QC Reject' WHEN education_result.verfstatus = 14 THEN 'New Check' WHEN education_result.verfstatus = 16 THEN 'YTR' WHEN education_result.verfstatus = 23 THEN 'Follow Up' WHEN education_result.verfstatus = 26 THEN 'Re-Initiated'  WHEN education_result.verfstatus = 1 THEN 'WIP'  END AS status_value,COUNT(education_result.verfstatus) as total");


		$this->db->from('education');

		$this->db->join("education_result",'education_result.education_id = education.id');


        if(!empty($clientid))
		{

		 $this->db->where("education.clientid",$clientid);

	    }

	    if($this->user_info['tbl_roles_id']  != "1")
        {
          
            $this->db->where("education.has_case_id",$this->user_info['id']);   
        }

	    $this->db->where_in('education_result.verfstatus',$array_condition_wip_insuff);

		$this->db->group_by('education_result.verfstatus');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$education = $result->result_array();

		$this->db->select("CASE WHEN  education_result.verfstatus =  9 THEN 'Stop Check' WHEN education_result.verfstatus =  17  THEN 'Clear' WHEN education_result.verfstatus = 19  THEN 'Major Discrepancy'  WHEN education_result.verfstatus =  20 THEN 'Minor Discrepancy' WHEN education_result.verfstatus = 21 THEN 'Unable to verify'  WHEN education_result.verfstatus = 22 THEN 'Worked with the same organization' WHEN education_result.verfstatus = 24 THEN 'Overseas check' WHEN education_result.verfstatus = 25 THEN 'No Record Found' WHEN education_result.verfstatus = 27 THEN 'Change of Address' WHEN education_result.verfstatus = 28 THEN 'NA'  END AS status_value,COUNT(education_result.verfstatus) as total");


		$this->db->from('education');

		$this->db->join("education_result",'education_result.education_id = education.id');

		if(!empty($clientid))
		{

		 $this->db->where("education.clientid",$clientid);

	    }

	    if($this->user_info['tbl_roles_id']  != "1")
        {
          
            $this->db->where("education.has_case_id",$this->user_info['id']);   
        }
   
        $this->db->where($where_condition);

        $this->db->where_in('education_result.verfstatus',$array_condition_closed);

		$this->db->group_by('education_result.verfstatus');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$education_closed = $result->result_array();
		
		$results_education = array_reduce($education, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());


		$results_education_closed = array_reduce($education_closed, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());


        $return['total'] = array_sum($results_education) + array_sum($results_education_closed);
		
		//if(!isset($results['WIP']))
		//{
		$results['WIP'] = '0';	
		//}
        if(!isset($results['Insufficiency']))
		{
        $results['Insufficiency'] = '0';
        }
		
        $results['Closed'] = '0';
		$return['NoRecordFound'] = '0'; 
		
		if(!empty($results))
		{

	        if(array_key_exists('Insufficiency Cleared',$results_education)) {
	            $results['WIP'] = $results['WIP'] + $results_education['Insufficiency Cleared'];
	        }

	        if(array_key_exists(NULL,$results_education)) {
	            $results['WIP'] = $results['WIP'] + $results_education[''];
	        }

	        if(array_key_exists('WIP',$results_education)) {
	           $results['WIP'] = $results['WIP'] + $results_education['WIP'];
	        }

	        if(array_key_exists('Final QC Reject',$results_education)) {
	            $results['WIP'] = $results['WIP'] + $results_education['Final QC Reject'];
	        }

	        if(array_key_exists('First QC Reject',$results_education)) {
	            $results['WIP'] = $results['WIP'] + $results_education['First QC Reject'];
	        }

	        if(array_key_exists('New Check',$results_education)) {
	            $results['WIP'] = $results['WIP'] + $results_education['New Check'];
	        }

	        if(array_key_exists('YTR',$results_education)) {
	            $results['WIP'] = $results['WIP'] + $results_education['YTR'];
	        }


	        if(array_key_exists('Follow Up',$results_education)) {
	            $results['WIP'] = $results['WIP'] + $results_education['Follow Up'];
	        }

	        if(array_key_exists('Re-Initiated',$results_education)) {
	            $results['WIP'] = $results['WIP'] + $results_education['Re-Initiated'];
	        }


	        if(array_key_exists('Insufficiency',$results_education)) {
	        
	            $results['Insufficiency'] = $results_education['Insufficiency'];
	        }

	        if(array_key_exists('Stop Check',$results_education_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_education_closed['Stop Check'];
	        }
            
            if(array_key_exists('Clear',$results_education_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_education_closed['Clear'];
	        }

	        if(array_key_exists('Major Discrepancy',$results_education_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_education_closed['Major Discrepancy'];
	        }

	         if(array_key_exists('Minor Discrepancy',$results_education_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_education_closed['Minor Discrepancy'];
	        }

	        if(array_key_exists('Unable to verify',$results_education_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_education_closed['Unable to verify'];
	        }

	        if(array_key_exists('Worked with the same organization',$results_education_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_education_closed['Worked with the same organization'];
	        }

	        if(array_key_exists('Overseas check',$results_education_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_education_closed['Overseas check'];
	        }

	        if(array_key_exists('No Record Found',$results_education_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_education_closed['No Record Found'];
	        }

	        if(array_key_exists('Change of Address',$results_education_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_education_closed['Change of Address'];
	        }

	        if(array_key_exists('NA',$results_education_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_education_closed['NA'];
	        }
	     
	     
			foreach ($results as $key => $value) {
				$return[str_replace('/','',str_replace(' ','',$key))] = $value;
			}
			
		}
       return $return;
         
	}


    public function status_count_reference($clientid,$from,$to)
	{
        $where_condition = "DATE_FORMAT(`reference_result`.`closuredate`,'%Y-%m-%d') BETWEEN '$from' AND '$to'";
        $array_condition_wip_insuff =  array('18','11','12','13','14','16','23','26','1');
        $array_condition_closed =  array('9','17','19','20','21','22','24','25','27','28');
		
		$this->db->select("CASE WHEN reference_result.verfstatus = 18 THEN 'Insufficiency' WHEN reference_result.verfstatus = 11 THEN 'Insufficiency Cleared' WHEN reference_result.verfstatus = 12 THEN 'Final QC Reject' WHEN reference_result.verfstatus = 13 THEN 'First QC Reject' WHEN reference_result.verfstatus = 14 THEN 'New Check' WHEN reference_result.verfstatus = 16 THEN 'YTR' WHEN reference_result.verfstatus = 23 THEN 'Follow Up' WHEN reference_result.verfstatus = 26 THEN 'Re-Initiated' WHEN reference_result.verfstatus = 1 THEN 'WIP'  END AS status_value,COUNT(reference_result.verfstatus) as total");

		$this->db->from('reference');

		$this->db->join("reference_result",'reference_result.reference_id = reference.id');

		if(!empty($clientid))
		{

		 $this->db->where("reference.clientid",$clientid);

	    }

	    if($this->user_info['tbl_roles_id']  != "1")
        {
          
            $this->db->where("reference.has_case_id",$this->user_info['id']);   
        }
         
        $this->db->where_in('reference_result.verfstatus',$array_condition_wip_insuff); 
 
	    $this->db->group_by('reference_result.verfstatus');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$reference = $result->result_array();

		$this->db->select("CASE WHEN  reference_result.verfstatus =  9 THEN 'Stop Check' WHEN reference_result.verfstatus =  17  THEN 'Clear' WHEN reference_result.verfstatus = 19  THEN 'Major Discrepancy'  WHEN reference_result.verfstatus =  20 THEN 'Minor Discrepancy' WHEN reference_result.verfstatus = 21 THEN 'Unable to verify'  WHEN reference_result.verfstatus = 22 THEN 'Worked with the same organization' WHEN reference_result.verfstatus = 24 THEN 'Overseas check' WHEN reference_result.verfstatus = 25 THEN 'No Record Found' WHEN reference_result.verfstatus = 27 THEN 'Change of Address' WHEN reference_result.verfstatus = 28 THEN 'NA'  END AS status_value,COUNT(reference_result.verfstatus) as total");


		$this->db->from('reference');

		$this->db->join("reference_result",'reference_result.reference_id = reference.id');

		if(!empty($clientid))
		{

		 $this->db->where("reference.clientid",$clientid);

	    }

	    if($this->user_info['tbl_roles_id']  != "1")
        {
          
            $this->db->where("reference.has_case_id",$this->user_info['id']);   
        }
   
        $this->db->where($where_condition);

        $this->db->where_in('reference_result.verfstatus',$array_condition_closed);

		$this->db->group_by('reference_result.verfstatus');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$reference_closed = $result->result_array();
		
		
		$results_reference = array_reduce($reference, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());

		$results_reference_closed = array_reduce($reference_closed, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());


    
        $return['total'] = array_sum($results_reference) + array_sum($results_reference_closed);
		
		//if(!isset($results['WIP']))
		//{
		$results['WIP'] = '0';	
		//}
        if(!isset($results['Insufficiency']))
		{
        $results['Insufficiency'] = '0';
        }
		
        $results['Closed'] = '0';
		$return['NoRecordFound'] = '0'; 
		
		if(!empty($results))
		{

	        if(array_key_exists('Insufficiency Cleared',$results_reference)) {
	            $results['WIP'] = $results['WIP'] + $results_reference['Insufficiency Cleared'];
	        }

	        if(array_key_exists(NULL,$results_reference)) {
	            $results['WIP'] = $results['WIP'] + $results_reference[''];
	        }

	         if(array_key_exists('WIP',$results_reference)) {
	            $results['WIP'] = $results['WIP'] + $results_reference['WIP'];
	        }

     
	        if(array_key_exists('Final QC Reject',$results_reference)) {
	            $results['WIP'] = $results['WIP'] + $results_reference['Final QC Reject'];
	        }

	        if(array_key_exists('First QC Reject',$results_reference)) {
	            $results['WIP'] = $results['WIP'] + $results_reference['First QC Reject'];
	        }

	        if(array_key_exists('New Check',$results_reference)) {
	            $results['WIP'] = $results['WIP'] + $results_reference['New Check'];
	        }

	        if(array_key_exists('YTR',$results_reference)) {
	            $results['WIP'] = $results['WIP'] + $results_reference['YTR'];
	        }


	        if(array_key_exists('Follow Up',$results_reference)) {
	            $results['WIP'] = $results['WIP'] + $results_reference['Follow Up'];
	        }

	        if(array_key_exists('Re-Initiated',$results_reference)) {
	            $results['WIP'] = $results['WIP'] + $results_reference['Re-Initiated'];
	        }


	        if(array_key_exists('Insufficiency',$results_reference)) {
	        
	            $results['Insufficiency'] = $results_reference['Insufficiency'];
	        }

	        if(array_key_exists('Stop Check',$results_reference_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_reference_closed['Stop Check'];
	        }
            
            if(array_key_exists('Clear',$results_reference_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_reference_closed['Clear'];
	        }

	        if(array_key_exists('Major Discrepancy',$results_reference_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_reference_closed['Major Discrepancy'];
	        }

	         if(array_key_exists('Minor Discrepancy',$results_reference_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_reference_closed['Minor Discrepancy'];
	        }

	        if(array_key_exists('Unable to verify',$results_reference_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_reference_closed['Unable to verify'];
	        }

	        if(array_key_exists('Worked with the same organization',$results_reference_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_reference_closed['Worked with the same organization'];
	        }

	        if(array_key_exists('Overseas check',$results_reference_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_reference_closed['Overseas check'];
	        }

	        if(array_key_exists('No Record Found',$results_reference_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_reference_closed['No Record Found'];
	        }

	       

	        if(array_key_exists('Change of Address',$results_reference_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_reference_closed['Change of Address'];
	        }

	        if(array_key_exists('NA',$results_reference_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_reference_closed['NA'];
	        }
	     
	     
			foreach ($results as $key => $value) {
				$return[str_replace('/','',str_replace(' ','',$key))] = $value;
			}
			
		}
       return $return;
         
	}


    public function status_count_court($clientid,$from,$to)
	{
        $where_condition = "DATE_FORMAT(`courtver_result`.`closuredate`,'%Y-%m-%d') BETWEEN '$from' AND '$to'";
        $array_condition_wip_insuff =  array('18','11','12','13','14','16','23','26','1');
        $array_condition_closed =  array('9','17','19','20','21','22','24','25','27','28');
		
	
		$this->db->select("CASE  WHEN courtver_result.verfstatus = 18 THEN 'Insufficiency' WHEN courtver_result.verfstatus = 11 THEN 'Insufficiency Cleared' WHEN courtver_result.verfstatus = 12 THEN 'Final QC Reject' WHEN courtver_result.verfstatus = 13 THEN 'First QC Reject' WHEN courtver_result.verfstatus = 14 THEN 'New Check' WHEN courtver_result.verfstatus = 16 THEN 'YTR' WHEN courtver_result.verfstatus = 23 THEN 'Follow Up' WHEN courtver_result.verfstatus = 26 THEN 'Re-Initiated' WHEN courtver_result.verfstatus = 1 THEN 'WIP' END AS status_value,COUNT(courtver_result.verfstatus) as total");

		$this->db->from('courtver');

		$this->db->join("courtver_result",'courtver_result.courtver_id = courtver.id');

		if(!empty($clientid))
		{

		 $this->db->where("courtver.clientid",$clientid);

	    }

	    if($this->user_info['tbl_roles_id']  != "1")
        {
            $this->db->where("courtver.has_case_id",$this->user_info['id']);   
        }
         
        $this->db->where_in('courtver_result.verfstatus',$array_condition_wip_insuff); 
 
		$this->db->group_by('courtver_result.verfstatus');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$court = $result->result_array();

		$this->db->select("CASE WHEN  courtver_result.verfstatus =  9 THEN 'Stop Check' WHEN courtver_result.verfstatus =  17  THEN 'Clear' WHEN courtver_result.verfstatus = 19  THEN 'Major Discrepancy'  WHEN courtver_result.verfstatus =  20 THEN 'Minor Discrepancy' WHEN courtver_result.verfstatus = 21 THEN 'Unable to verify'  WHEN courtver_result.verfstatus = 22 THEN 'Worked with the same organization' WHEN courtver_result.verfstatus = 24 THEN 'Overseas check' WHEN courtver_result.verfstatus = 25 THEN 'No Record Found' WHEN courtver_result.verfstatus = 27 THEN 'Change of Address' WHEN courtver_result.verfstatus = 28 THEN 'NA'  END AS status_value,COUNT(courtver_result.verfstatus) as total");


		$this->db->from('courtver');

		$this->db->join("courtver_result",'courtver_result.courtver_id = courtver.id');

		if(!empty($clientid))
		{

		 $this->db->where("courtver.clientid",$clientid);

	    }

	    if($this->user_info['tbl_roles_id']  != "1")
        {
            $this->db->where("courtver.has_case_id",$this->user_info['id']);   
        }
         
   
        $this->db->where($where_condition);

        $this->db->where_in('courtver_result.verfstatus',$array_condition_closed);

		$this->db->group_by('courtver_result.verfstatus');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$court_closed = $result->result_array();
		
		
		$results_court = array_reduce($court, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());

        $results_court_closed = array_reduce($court_closed, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());   
    
        $return['total'] = array_sum($results_court) + array_sum($results_court_closed);
		
		//if(!isset($results['WIP']))
		//{
		$results['WIP'] = '0';	
		//}
        if(!isset($results['Insufficiency']))
		{
        $results['Insufficiency'] = '0';
        }
		
        $results['Closed'] = '0';
		$return['NoRecordFound'] = '0'; 
		
		if(!empty($results))
		{

	        if(array_key_exists('Insufficiency Cleared',$results_court)) {
	            $results['WIP'] = $results['WIP'] + $results_court['Insufficiency Cleared'];
	        }

	        if(array_key_exists(NULL,$results_court)) {
	            $results['WIP'] = $results['WIP'] + $results_court[''];
	        }

	        if(array_key_exists('WIP',$results_court)) {
	            $results['WIP'] = $results['WIP'] + $results_court['WIP'];
	        }

	        if(array_key_exists('Final QC Reject',$results_court)) {
	            $results['WIP'] = $results['WIP'] + $results_court['Final QC Reject'];
	        }

	        if(array_key_exists('First QC Reject',$results_court)) {
	            $results['WIP'] = $results['WIP'] + $results_court['First QC Reject'];
	        }

	        if(array_key_exists('New Check',$results_court)) {
	            $results['WIP'] = $results['WIP'] + $results_court['New Check'];
	        }

	        if(array_key_exists('YTR',$results_court)) {
	            $results['WIP'] = $results['WIP'] + $results_court['YTR'];
	        }


	        if(array_key_exists('Follow Up',$results_court)) {
	            $results['WIP'] = $results['WIP'] + $results_court['Follow Up'];
	        }

	        if(array_key_exists('Re-Initiated',$results_court)) {
	            $results['WIP'] = $results['WIP'] + $results_court['Re-Initiated'];
	        }


	        if(array_key_exists('Insufficiency',$results_court)) {
	        
	            $results['Insufficiency'] = $results_court['Insufficiency'];
	        }

	        if(array_key_exists('Stop Check',$results_court_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_court_closed['Stop Check'];
	        }
            
            if(array_key_exists('Clear',$results_court_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_court_closed['Clear'];
	        }

	        if(array_key_exists('Major Discrepancy',$results_court_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_court_closed['Major Discrepancy'];
	        }

	         if(array_key_exists('Minor Discrepancy',$results_court_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_court_closed['Minor Discrepancy'];
	        }

	        if(array_key_exists('Unable to verify',$results_court_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_court_closed['Unable to verify'];
	        }

	        if(array_key_exists('Worked with the same organization',$results_court_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_court_closed['Worked with the same organization'];
	        }

	        if(array_key_exists('Overseas check',$results_court_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_court_closed['Overseas check'];
	        }

	        if(array_key_exists('No Record Found',$results_court_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_court_closed['No Record Found'];
	        }

	     

	        if(array_key_exists('Change of Address',$results_court_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_court_closed['Change of Address'];
	        }

	        if(array_key_exists('NA',$results_court_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_court_closed['NA'];
	        }
	     
	     
			foreach ($results as $key => $value) {
				$return[str_replace('/','',str_replace(' ','',$key))] = $value;
			}
			
		}
       return $return;
         
	}

	public function status_count_global_database($clientid,$from,$to)
	{
        $where_condition = "DATE_FORMAT(`glodbver_result`.`closuredate`,'%Y-%m-%d') BETWEEN '$from' AND '$to'";
        $array_condition_wip_insuff =  array('18','11','12','13','14','16','23','26','1');
        $array_condition_closed =  array('9','17','19','20','21','22','24','25','27','28');
		
	
		$this->db->select("CASE  WHEN glodbver_result.verfstatus = 18 THEN 'Insufficiency' WHEN glodbver_result.verfstatus = 11 THEN 'Insufficiency Cleared' WHEN glodbver_result.verfstatus = 12 THEN 'Final QC Reject' WHEN glodbver_result.verfstatus = 13 THEN 'First QC Reject' WHEN glodbver_result.verfstatus = 14 THEN 'New Check' WHEN glodbver_result.verfstatus = 16 THEN 'YTR' WHEN glodbver_result.verfstatus = 23 THEN 'Follow Up' WHEN glodbver_result.verfstatus = 26 THEN 'Re-Initiated'  WHEN glodbver_result.verfstatus = 1 THEN 'WIP'  END AS status_value,COUNT(glodbver_result.verfstatus) as total");

		$this->db->from('glodbver');

		$this->db->join("glodbver_result",'glodbver_result.glodbver_id = glodbver.id');

		if(!empty($clientid))
		{

		 $this->db->where("glodbver.clientid",$clientid);

	    }

	    if($this->user_info['tbl_roles_id']  != "1")
        {
            $this->db->where("glodbver.has_case_id",$this->user_info['id']);   
        }
         

		$this->db->where_in('glodbver_result.verfstatus',$array_condition_wip_insuff); 
 
		$this->db->group_by('glodbver_result.verfstatus');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$glodbver = $result->result_array();


		$this->db->select("CASE WHEN  glodbver_result.verfstatus =  9 THEN 'Stop Check' WHEN glodbver_result.verfstatus =  17  THEN 'Clear' WHEN glodbver_result.verfstatus = 19  THEN 'Major Discrepancy'  WHEN glodbver_result.verfstatus =  20 THEN 'Minor Discrepancy' WHEN glodbver_result.verfstatus = 21 THEN 'Unable to verify'  WHEN glodbver_result.verfstatus = 22 THEN 'Worked with the same organization' WHEN glodbver_result.verfstatus = 24 THEN 'Overseas check' WHEN glodbver_result.verfstatus = 25 THEN 'No Record Found' WHEN glodbver_result.verfstatus = 27 THEN 'Change of Address' WHEN glodbver_result.verfstatus = 28 THEN 'NA'  END AS status_value,COUNT(glodbver_result.verfstatus) as total");


		$this->db->from('glodbver');

		$this->db->join("glodbver_result",'glodbver_result.glodbver_id = glodbver.id');

		if(!empty($clientid))
		{

		 $this->db->where("glodbver.clientid",$clientid);

	    }

	    if($this->user_info['tbl_roles_id']  != "1")
        {
            $this->db->where("glodbver.has_case_id",$this->user_info['id']);   
        }
   
        $this->db->where($where_condition);

        $this->db->where_in('glodbver_result.verfstatus',$array_condition_closed);

		$this->db->group_by('glodbver_result.verfstatus');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$glodbver_closed = $result->result_array();

		
		$results_glodbver = array_reduce($glodbver, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());

        $results_glodbver_closed = array_reduce($glodbver_closed, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());
    
        $return['total'] = array_sum($results_glodbver) + array_sum($results_glodbver_closed);
		
		//if(!isset($results['WIP']))
		//{
		$results['WIP'] = '0';	
		//}
        if(!isset($results['Insufficiency']))
		{
        $results['Insufficiency'] = '0';
        }
		
        $results['Closed'] = '0';
		$return['NoRecordFound'] = '0'; 
		
		if(!empty($results))
		{

	        if(array_key_exists('Insufficiency Cleared',$results_glodbver)) {
	            $results['WIP'] = $results['WIP'] + $results_glodbver['Insufficiency Cleared'];
	        }

	        if(array_key_exists(NULL,$results_glodbver)) {
	            $results['WIP'] = $results['WIP'] + $results_glodbver[''];
	        }

	        if(array_key_exists('WIP',$results_glodbver)) {
	            $results['WIP'] = $results['WIP'] + $results_glodbver['WIP'];
	        }

	        if(array_key_exists('Final QC Reject',$results_glodbver)) {
	            $results['WIP'] = $results['WIP'] + $results_glodbver['Final QC Reject'];
	        }

	        if(array_key_exists('First QC Reject',$results_glodbver)) {
	            $results['WIP'] = $results['WIP'] + $results_glodbver['First QC Reject'];
	        }

	        if(array_key_exists('New Check',$results_glodbver)) {
	            $results['WIP'] = $results['WIP'] + $results_glodbver['New Check'];
	        }

	        if(array_key_exists('YTR',$results_glodbver)) {
	            $results['WIP'] = $results['WIP'] + $results_glodbver['YTR'];
	        }


	        if(array_key_exists('Follow Up',$results_glodbver)) {
	            $results['WIP'] = $results['WIP'] + $results_glodbver['Follow Up'];
	        }

	        if(array_key_exists('Re-Initiated',$results_glodbver)) {
	            $results['WIP'] = $results['WIP'] + $results_glodbver['Re-Initiated'];
	        }


	        if(array_key_exists('Insufficiency',$results_glodbver)) {
	        
	            $results['Insufficiency'] = $results_glodbver['Insufficiency'];
	        }

	        if(array_key_exists('Stop Check',$results_glodbver_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_glodbver_closed['Stop Check'];
	        }
            
            if(array_key_exists('Clear',$results_glodbver_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_glodbver_closed['Clear'];
	        }

	        if(array_key_exists('Major Discrepancy',$results_glodbver_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_glodbver_closed['Major Discrepancy'];
	        }

	         if(array_key_exists('Minor Discrepancy',$results_glodbver_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_glodbver_closed['Minor Discrepancy'];
	        }

	        if(array_key_exists('Unable to verify',$results_glodbver_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_glodbver_closed['Unable to verify'];
	        }

	        if(array_key_exists('Worked with the same organization',$results_glodbver_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_glodbver_closed['Worked with the same organization'];
	        }

	        if(array_key_exists('Overseas check',$results_glodbver_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_glodbver_closed['Overseas check'];
	        }

	        if(array_key_exists('No Record Found',$results_glodbver_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_glodbver_closed['No Record Found'];
	        }


	        if(array_key_exists('Change of Address',$results_glodbver_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_glodbver_closed['Change of Address'];
	        }

	        if(array_key_exists('NA',$results_glodbver_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_glodbver_closed['NA'];
	        }
	     
	     
			foreach ($results as $key => $value) {
				$return[str_replace('/','',str_replace(' ','',$key))] = $value;
			}
			
		}
       return $return;
         
	}

	public function status_count_pcc($clientid,$from,$to)
	{
        $where_condition = "DATE_FORMAT(`pcc_result`.`closuredate`,'%Y-%m-%d') BETWEEN '$from' AND '$to'";
        $array_condition_wip_insuff =  array('18','11','12','13','14','16','23','26','1');
        $array_condition_closed =  array('9','17','19','20','21','22','24','25','27','28');
		
	
		 $this->db->select("CASE  WHEN pcc_result.verfstatus = 18 THEN 'Insufficiency' WHEN pcc_result.verfstatus = 11 THEN 'Insufficiency Cleared' WHEN pcc_result.verfstatus = 12 THEN 'Final QC Reject' WHEN pcc_result.verfstatus = 13 THEN 'First QC Reject' WHEN pcc_result.verfstatus = 14 THEN 'New Check' WHEN pcc_result.verfstatus = 16 THEN 'YTR' WHEN pcc_result.verfstatus = 23 THEN 'Follow Up' WHEN pcc_result.verfstatus = 26 THEN 'Re-Initiated' WHEN pcc_result.verfstatus = 1 THEN 'WIP'  END AS status_value,COUNT(pcc_result.verfstatus) as total");

		$this->db->from('pcc');

		$this->db->join("pcc_result",'pcc_result.pcc_id = pcc.id');

		if(!empty($clientid))
		{

		 $this->db->where("pcc.clientid",$clientid);

	    }
	    if($this->user_info['tbl_roles_id']  != "1")
        {
            $this->db->where("pcc.has_case_id",$this->user_info['id']);   
        }
   

	    $this->db->where_in('pcc_result.verfstatus',$array_condition_wip_insuff); 

	    $this->db->group_by('pcc_result.verfstatus');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$pcc = $result->result_array();


		$this->db->select("CASE WHEN  pcc_result.verfstatus =  9 THEN 'Stop Check' WHEN pcc_result.verfstatus =  17  THEN 'Clear' WHEN pcc_result.verfstatus = 19  THEN 'Major Discrepancy'  WHEN pcc_result.verfstatus =  20 THEN 'Minor Discrepancy' WHEN pcc_result.verfstatus = 21 THEN 'Unable to verify'  WHEN pcc_result.verfstatus = 22 THEN 'Worked with the same organization' WHEN pcc_result.verfstatus = 24 THEN 'Overseas check' WHEN pcc_result.verfstatus = 25 THEN 'No Record Found' WHEN pcc_result.verfstatus = 27 THEN 'Change of Address' WHEN pcc_result.verfstatus = 28 THEN 'NA'  END AS status_value,COUNT(pcc_result.verfstatus) as total");


		$this->db->from('pcc');

		$this->db->join("pcc_result",'pcc_result.pcc_id = pcc.id');

		if(!empty($clientid))
		{

		 $this->db->where("pcc.clientid",$clientid);

	    }
	    if($this->user_info['tbl_roles_id']  != "1")
        {
            $this->db->where("pcc.has_case_id",$this->user_info['id']);   
        }

        $this->db->where($where_condition);

        $this->db->where_in('pcc_result.verfstatus',$array_condition_closed);

		$this->db->group_by('pcc_result.verfstatus');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$pcc_closed = $result->result_array();
		
		
		$results_pcc = array_reduce($pcc, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());

        $results_pcc_closed = array_reduce($pcc_closed, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());

    
        $return['total'] = array_sum($results_pcc) + array_sum($results_pcc_closed);
		
		//if(!isset($results['WIP']))
		//{
		$results['WIP'] = '0';	
		//}
        if(!isset($results['Insufficiency']))
		{
        $results['Insufficiency'] = '0';
        }
		
        $results['Closed'] = '0';
		$return['NoRecordFound'] = '0'; 
		
		if(!empty($results))
		{

	        if(array_key_exists('Insufficiency Cleared',$results_pcc)) {
	            $results['WIP'] = $results['WIP'] + $results_pcc['Insufficiency Cleared'];
	        }

	        if(array_key_exists(NULL,$results_pcc)) {
	            $results['WIP'] = $results['WIP'] + $results_pcc[''];
	        }


	        if(array_key_exists('WIP',$results_pcc)) {
	            $results['WIP'] = $results['WIP'] + $results_pcc['WIP'];
	        }

	        if(array_key_exists('Final QC Reject',$results_pcc)) {
	            $results['WIP'] = $results['WIP'] + $results_pcc['Final QC Reject'];
	        }

	        if(array_key_exists('First QC Reject',$results_pcc)) {
	            $results['WIP'] = $results['WIP'] + $results_pcc['First QC Reject'];
	        }

	        if(array_key_exists('New Check',$results_pcc)) {
	            $results['WIP'] = $results['WIP'] + $results_pcc['New Check'];
	        }

	        if(array_key_exists('YTR',$results_pcc)) {
	            $results['WIP'] = $results['WIP'] + $results_pcc['YTR'];
	        }


	        if(array_key_exists('Follow Up',$results_pcc)) {
	            $results['WIP'] = $results['WIP'] + $results_pcc['Follow Up'];
	        }

	        if(array_key_exists('Re-Initiated',$results_pcc)) {
	            $results['WIP'] = $results['WIP'] + $results_pcc['Re-Initiated'];
	        }


	        if(array_key_exists('Insufficiency',$results_pcc)) {
	        
	            $results['Insufficiency'] = $results_pcc['Insufficiency'];
	        }

	        if(array_key_exists('Stop Check',$results_pcc_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_pcc_closed['Stop Check'];
	        }
            
            if(array_key_exists('Clear',$results_pcc_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_pcc_closed['Clear'];
	        }

	        if(array_key_exists('Major Discrepancy',$results_pcc_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_pcc_closed['Major Discrepancy'];
	        }

	         if(array_key_exists('Minor Discrepancy',$results_pcc_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_pcc_closed['Minor Discrepancy'];
	        }

	        if(array_key_exists('Unable to verify',$results_pcc_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_pcc_closed['Unable to verify'];
	        }

	        if(array_key_exists('Worked with the same organization',$results_pcc_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_pcc_closed['Worked with the same organization'];
	        }

	        if(array_key_exists('Overseas check',$results_pcc_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_pcc_closed['Overseas check'];
	        }

	        if(array_key_exists('No Record Found',$results_pcc_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_pcc_closed['No Record Found'];
	        }

	        

	        if(array_key_exists('Change of Address',$results_pcc_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_pcc_closed['Change of Address'];
	        }

	        if(array_key_exists('NA',$results_pcc_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_pcc_closed['NA'];
	        }
	     
	     
			foreach ($results as $key => $value) {
				$return[str_replace('/','',str_replace(' ','',$key))] = $value;
			}
			
		}
       return $return;
         
	}
     
    public function status_count_identity($clientid,$from,$to)
	{
        $where_condition = "DATE_FORMAT(`identity_result`.`closuredate`,'%Y-%m-%d') BETWEEN '$from' AND '$to'";
        $array_condition_wip_insuff =  array('18','11','12','13','14','16','23','26','1');
        $array_condition_closed =  array('9','17','19','20','21','22','24','25','27','28');

		 $this->db->select("CASE WHEN identity_result.verfstatus = 18 THEN 'Insufficiency' WHEN identity_result.verfstatus = 11 THEN 'Insufficiency Cleared' WHEN identity_result.verfstatus = 12 THEN 'Final QC Reject' WHEN identity_result.verfstatus = 13 THEN 'First QC Reject' WHEN identity_result.verfstatus = 14 THEN 'New Check' WHEN identity_result.verfstatus = 16 THEN 'YTR' WHEN identity_result.verfstatus = 23 THEN 'Follow Up' WHEN identity_result.verfstatus = 26 THEN 'Re-Initiated' WHEN identity_result.verfstatus = 1 THEN 'WIP'  END AS status_value,COUNT(identity_result.verfstatus) as total");

		$this->db->from('identity');

	    $this->db->join("identity_result",'identity_result.identity_id = identity.id');

	    if(!empty($clientid))
		{

		 $this->db->where("identity.clientid",$clientid);

	    }
	    if($this->user_info['tbl_roles_id']  != "1")
        {
            $this->db->where("identity.has_case_id",$this->user_info['id']);   
        }

		$this->db->where_in('identity_result.verfstatus',$array_condition_wip_insuff); 
 
        $this->db->group_by('identity_result.verfstatus');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$identity = $result->result_array();

		$this->db->select("CASE WHEN  identity_result.verfstatus =  9 THEN 'Stop Check' WHEN identity_result.verfstatus =  17  THEN 'Clear' WHEN identity_result.verfstatus = 19  THEN 'Major Discrepancy'  WHEN identity_result.verfstatus =  20 THEN 'Minor Discrepancy' WHEN identity_result.verfstatus = 21 THEN 'Unable to verify'  WHEN identity_result.verfstatus = 22 THEN 'Worked with the same organization' WHEN identity_result.verfstatus = 24 THEN 'Overseas check' WHEN identity_result.verfstatus = 25 THEN 'No Record Found' WHEN identity_result.verfstatus = 27 THEN 'Change of Address' WHEN identity_result.verfstatus = 28 THEN 'NA'  END AS status_value,COUNT(identity_result.verfstatus) as total");


		$this->db->from('identity');

	    $this->db->join("identity_result",'identity_result.identity_id = identity.id');

	    if(!empty($clientid))
		{

		 $this->db->where("identity.clientid",$clientid);

	    }

	    if($this->user_info['tbl_roles_id']  != "1")
        {
            $this->db->where("identity.has_case_id",$this->user_info['id']);   
        }


        $this->db->where($where_condition);

        $this->db->where_in('identity_result.verfstatus',$array_condition_closed);

		$this->db->group_by('identity_result.verfstatus');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$identity_closed = $result->result_array();
		
		
		$results_identity = array_reduce($identity, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());

        $results_identity_closed = array_reduce($identity_closed, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());

    
        $return['total'] = array_sum($results_identity) + array_sum($results_identity_closed);
		
		//if(!isset($results['WIP']))
		//{
		$results['WIP'] = '0';	
		//}
        if(!isset($results['Insufficiency']))
		{
        $results['Insufficiency'] = '0';
        }
		
        $results['Closed'] = '0';
		$return['NoRecordFound'] = '0'; 
		
		if(!empty($results))
		{

	        if(array_key_exists('Insufficiency Cleared',$results_identity)) {
	            $results['WIP'] = $results['WIP'] + $results_identity['Insufficiency Cleared'];
	        }

	        if(array_key_exists(NULL,$results_identity)) {
	            $results['WIP'] = $results['WIP'] + $results_identity[''];
	        }

	        if(array_key_exists('WIP',$results_identity)) {
	            $results['WIP'] = $results['WIP'] + $results_identity['WIP'];
	        }

	        if(array_key_exists('Final QC Reject',$results_identity)) {
	            $results['WIP'] = $results['WIP'] + $results_identity['Final QC Reject'];
	        }

	        if(array_key_exists('First QC Reject',$results_identity)) {
	            $results['WIP'] = $results['WIP'] + $results_identity['First QC Reject'];
	        }

	        if(array_key_exists('New Check',$results_identity)) {
	            $results['WIP'] = $results['WIP'] + $results_identity['New Check'];
	        }

	        if(array_key_exists('YTR',$results_identity)) {
	            $results['WIP'] = $results['WIP'] + $results_identity['YTR'];
	        }


	        if(array_key_exists('Follow Up',$results_identity)) {
	            $results['WIP'] = $results['WIP'] + $results_identity['Follow Up'];
	        }

	        if(array_key_exists('Re-Initiated',$results_identity)) {
	            $results['WIP'] = $results['WIP'] + $results_identity['Re-Initiated'];
	        }


	        if(array_key_exists('Insufficiency',$results_identity)) {
	        
	            $results['Insufficiency'] = $results_identity['Insufficiency'];
	        }

	        if(array_key_exists('Stop Check',$results_identity_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_identity_closed['Stop Check'];
	        }
            
            if(array_key_exists('Clear',$results_identity_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_identity_closed['Clear'];
	        }

	        if(array_key_exists('Major Discrepancy',$results_identity_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_identity_closed['Major Discrepancy'];
	        }

	         if(array_key_exists('Minor Discrepancy',$results_identity_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_identity_closed['Minor Discrepancy'];
	        }

	        if(array_key_exists('Unable to verify',$results_identity_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_identity_closed['Unable to verify'];
	        }

	        if(array_key_exists('Worked with the same organization',$results_identity_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_identity_closed['Worked with the same organization'];
	        }

	        if(array_key_exists('Overseas check',$results_identity_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_identity_closed['Overseas check'];
	        }

	        if(array_key_exists('No Record Found',$results_identity_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_identity_closed['No Record Found'];
	        }

	       
	        if(array_key_exists('Change of Address',$results_identity_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_identity_closed['Change of Address'];
	        }

	        if(array_key_exists('NA',$results_identity_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_identity_closed['NA'];
	        }
	     
	     
			foreach ($results as $key => $value) {
				$return[str_replace('/','',str_replace(' ','',$key))] = $value;
			}
			
		}
       return $return;
         
	}
	
	public function status_count_credit_report($clientid,$from,$to)
	{
		$where_condition = "DATE_FORMAT(`credit_report_result`.`closuredate`,'%Y-%m-%d') BETWEEN '$from' AND '$to'";
        $array_condition_wip_insuff =  array('18','11','12','13','14','16','23','26','1');
        $array_condition_closed =  array('9','17','19','20','21','22','24','25','27','28');

		$this->db->select("CASE WHEN credit_report_result.verfstatus = 18 THEN 'Insufficiency' WHEN credit_report_result.verfstatus = 11 THEN 'Insufficiency Cleared' WHEN credit_report_result.verfstatus = 12 THEN 'Final QC Reject' WHEN credit_report_result.verfstatus = 13 THEN 'First QC Reject' WHEN credit_report_result.verfstatus = 14 THEN 'New Check' WHEN credit_report_result.verfstatus = 16 THEN 'YTR' WHEN credit_report_result.verfstatus = 23 THEN 'Follow Up'  WHEN credit_report_result.verfstatus = 1 THEN 'WIP'  END AS status_value,COUNT(credit_report_result.verfstatus) as total");


		$this->db->from('credit_report');

	    $this->db->join("credit_report_result",'credit_report_result.credit_report_id = credit_report.id');

	    if(!empty($clientid))
		{

		 $this->db->where("credit_report.clientid",$clientid);

	    }
	    if($this->user_info['tbl_roles_id']  != "1")
        {
            $this->db->where("credit_report.has_case_id",$this->user_info['id']);   
        }

	    $this->db->where_in('credit_report_result.verfstatus',$array_condition_wip_insuff); 

		$this->db->group_by('credit_report_result.verfstatus');


		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$credit_report = $result->result_array();

		$this->db->select("CASE WHEN  credit_report_result.verfstatus =  9 THEN 'Stop Check' WHEN credit_report_result.verfstatus =  17  THEN 'Clear' WHEN credit_report_result.verfstatus = 19  THEN 'Major Discrepancy'  WHEN credit_report_result.verfstatus =  20 THEN 'Minor Discrepancy' WHEN credit_report_result.verfstatus = 21 THEN 'Unable to verify'  WHEN credit_report_result.verfstatus = 22 THEN 'Worked with the same organization' WHEN credit_report_result.verfstatus = 24 THEN 'Overseas check' WHEN credit_report_result.verfstatus = 25 THEN 'No Record Found' WHEN credit_report_result.verfstatus = 27 THEN 'Change of Address' WHEN credit_report_result.verfstatus = 28 THEN 'NA'  END AS status_value,COUNT(credit_report_result.verfstatus) as total");


		$this->db->from('credit_report');

	    $this->db->join("credit_report_result",'credit_report_result.credit_report_id = credit_report.id');

	    if(!empty($clientid))
		{

		 $this->db->where("credit_report.clientid",$clientid);

	    }
	    if($this->user_info['tbl_roles_id']  != "1")
        {
            $this->db->where("credit_report.has_case_id",$this->user_info['id']);   
        }

        $this->db->where($where_condition);

        $this->db->where_in('credit_report_result.verfstatus',$array_condition_closed);

		$this->db->group_by('credit_report_result.verfstatus');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$credit_report_closed = $result->result_array();
		
		$results_credit_report = array_reduce($credit_report, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());

        $results_credit_report_closed = array_reduce($credit_report_closed, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());

    
        $return['total'] = array_sum($results_credit_report) + array_sum($results_credit_report_closed); 
		
		//if(!isset($results['WIP']))
		//{
		$results['WIP'] = '0';	
		//}
        if(!isset($results['Insufficiency']))
		{
        $results['Insufficiency'] = '0';
        }
		
        $results['Closed'] = '0';
		$return['NoRecordFound'] = '0'; 
		
		if(!empty($results))
		{

	        if(array_key_exists('Insufficiency Cleared',$results_credit_report)) {
	            $results['WIP'] = $results['WIP'] + $results_credit_report['Insufficiency Cleared'];
	        }

	        if(array_key_exists(NULL,$results_credit_report)) {
	            $results['WIP'] = $results['WIP'] + $results_credit_report[''];
	        }

	        if(array_key_exists('WIP',$results_credit_report)) {
	            $results['WIP'] = $results['WIP'] + $results_credit_report['WIP'];
	        }


	        if(array_key_exists('Final QC Reject',$results_credit_report)) {
	            $results['WIP'] = $results['WIP'] + $results_credit_report['Final QC Reject'];
	        }

	        if(array_key_exists('First QC Reject',$results_credit_report)) {
	            $results['WIP'] = $results['WIP'] + $results_credit_report['First QC Reject'];
	        }

	        if(array_key_exists('New Check',$results_credit_report)) {
	            $results['WIP'] = $results['WIP'] + $results_credit_report['New Check'];
	        }

	        if(array_key_exists('YTR',$results_credit_report)) {
	            $results['WIP'] = $results['WIP'] + $results_credit_report['YTR'];
	        }


	        if(array_key_exists('Follow Up',$results_credit_report)) {
	            $results['WIP'] = $results['WIP'] + $results_credit_report['Follow Up'];
	        }

	        if(array_key_exists('Re-Initiated',$results_credit_report)) {
	            $results['WIP'] = $results['WIP'] + $results_credit_report['Re-Initiated'];
	        }


	        if(array_key_exists('Insufficiency',$results_credit_report)) {
	        
	            $results['Insufficiency'] = $results_credit_report['Insufficiency'];
	        }

	        if(array_key_exists('Stop Check',$results_credit_report_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_credit_report_closed['Stop Check'];
	        }
            
            if(array_key_exists('Clear',$results_credit_report_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_credit_report_closed['Clear'];
	        }

	        if(array_key_exists('Major Discrepancy',$results_credit_report_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_credit_report_closed['Major Discrepancy'];
	        }

	         if(array_key_exists('Minor Discrepancy',$results_credit_report_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_credit_report_closed['Minor Discrepancy'];
	        }

	        if(array_key_exists('Unable to verify',$results_credit_report_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_credit_report_closed['Unable to verify'];
	        }

	        if(array_key_exists('Worked with the same organization',$results_credit_report_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_credit_report_closed['Worked with the same organization'];
	        }

	        if(array_key_exists('Overseas check',$results_credit_report_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_credit_report_closed['Overseas check'];
	        }

	        if(array_key_exists('No Record Found',$results_credit_report_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_credit_report_closed['No Record Found'];
	        }

	        
	        if(array_key_exists('Change of Address',$results_credit_report_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_credit_report_closed['Change of Address'];
	        }

	        if(array_key_exists('NA',$results_credit_report_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_credit_report_closed['NA'];
	        }
	     
	     
			foreach ($results as $key => $value) {
				$return[str_replace('/','',str_replace(' ','',$key))] = $value;
			}
			
		}
       return $return;
         
	}

	public function status_count_drugs($clientid,$from,$to)
	{
	     $where_condition = "DATE_FORMAT(`drug_narcotis_result`.`closuredate`,'%Y-%m-%d') BETWEEN '$from' AND '$to'";
        $array_condition_wip_insuff =  array('18','11','12','13','14','16','23','26','1');
        $array_condition_closed =  array('9','17','19','20','21','22','24','25','27','28');

		$this->db->select("CASE  WHEN drug_narcotis_result.verfstatus = 18 THEN 'Insufficiency' WHEN drug_narcotis_result.verfstatus = 11 THEN 'Insufficiency Cleared' WHEN drug_narcotis_result.verfstatus = 12 THEN 'Final QC Reject' WHEN drug_narcotis_result.verfstatus = 13 THEN 'First QC Reject' WHEN drug_narcotis_result.verfstatus = 14 THEN 'New Check' WHEN drug_narcotis_result.verfstatus = 16 THEN 'YTR' WHEN drug_narcotis_result.verfstatus = 23 THEN 'Follow Up' WHEN drug_narcotis_result.verfstatus = 26 THEN 'Re-Initiated'  WHEN drug_narcotis_result.verfstatus = 1 THEN 'WIP'  END AS status_value,COUNT(drug_narcotis_result.verfstatus) as total");

		$this->db->from('drug_narcotis');

		$this->db->join("drug_narcotis_result",'drug_narcotis_result.drug_narcotis_id = drug_narcotis.id');
 
        if(!empty($clientid))
		{

		 $this->db->where("drug_narcotis.clientid",$clientid);

	    }

	    if($this->user_info['tbl_roles_id']  != "1")
        {
            $this->db->where("drug_narcotis.has_case_id",$this->user_info['id']);   
        }

		$this->db->where_in('drug_narcotis_result.verfstatus',$array_condition_wip_insuff); 

	    $this->db->group_by('drug_narcotis_result.verfstatus');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$drug_narcotis = $result->result_array();

		$this->db->select("CASE WHEN  drug_narcotis_result.verfstatus =  9 THEN 'Stop Check' WHEN drug_narcotis_result.verfstatus =  17  THEN 'Clear' WHEN drug_narcotis_result.verfstatus = 19  THEN 'Major Discrepancy'  WHEN drug_narcotis_result.verfstatus =  20 THEN 'Minor Discrepancy' WHEN drug_narcotis_result.verfstatus = 21 THEN 'Unable to verify'  WHEN drug_narcotis_result.verfstatus = 22 THEN 'Worked with the same organization' WHEN drug_narcotis_result.verfstatus = 24 THEN 'Overseas check' WHEN drug_narcotis_result.verfstatus = 25 THEN 'No Record Found' WHEN drug_narcotis_result.verfstatus = 27 THEN 'Change of Address' WHEN drug_narcotis_result.verfstatus = 28 THEN 'NA'  END AS status_value,COUNT(drug_narcotis_result.verfstatus) as total");


		$this->db->from('drug_narcotis');

		$this->db->join("drug_narcotis_result",'drug_narcotis_result.drug_narcotis_id = drug_narcotis.id');
 
        if(!empty($clientid))
		{

		 $this->db->where("drug_narcotis.clientid",$clientid);

	    }

	    if($this->user_info['tbl_roles_id']  != "1")
        {
            $this->db->where("drug_narcotis.has_case_id",$this->user_info['id']);   
        }


        $this->db->where($where_condition);

        $this->db->where_in('drug_narcotis_result.verfstatus',$array_condition_closed); 

	    $this->db->group_by('drug_narcotis_result.verfstatus');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$drug_narcotis_closed = $result->result_array();
		

		$results_drug_narcotis = array_reduce($drug_narcotis, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());

         $results_drug_narcotis_closed = array_reduce($drug_narcotis_closed, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());

    
        $return['total'] = array_sum($results_drug_narcotis) + array_sum($results_drug_narcotis_closed);
		
		//if(!isset($results['WIP']))
		//{
		$results['WIP'] = '0';	
		//}
        if(!isset($results['Insufficiency']))
		{
        $results['Insufficiency'] = '0';
        }
		
        $results['Closed'] = '0';
		$return['NoRecordFound'] = '0'; 
		
		if(!empty($results))
		{

	        if(array_key_exists('Insufficiency Cleared',$results_drug_narcotis)) {
	            $results['WIP'] = $results['WIP'] + $results_drug_narcotis['Insufficiency Cleared'];
	        }

	        if(array_key_exists(NULL,$results_drug_narcotis)) {
	            $results['WIP'] = $results['WIP'] + $results_drug_narcotis[''];
	        }

	        if(array_key_exists('WIP',$results_drug_narcotis)) {
	            $results['WIP'] = $results['WIP'] + $results_drug_narcotis['WIP'];
	        }

	        if(array_key_exists('Final QC Reject',$results_drug_narcotis)) {
	            $results['WIP'] = $results['WIP'] + $results_drug_narcotis['Final QC Reject'];
	        }

	        if(array_key_exists('First QC Reject',$results_drug_narcotis)) {
	            $results['WIP'] = $results['WIP'] + $results_drug_narcotis['First QC Reject'];
	        }

	        if(array_key_exists('New Check',$results_drug_narcotis)) {
	            $results['WIP'] = $results['WIP'] + $results_drug_narcotis['New Check'];
	        }

	        if(array_key_exists('YTR',$results_drug_narcotis)) {
	            $results['WIP'] = $results['WIP'] + $results_drug_narcotis['YTR'];
	        }


	        if(array_key_exists('Follow Up',$results_drug_narcotis)) {
	            $results['WIP'] = $results['WIP'] + $results_drug_narcotis['Follow Up'];
	        }

	        if(array_key_exists('Re-Initiated',$results_drug_narcotis)) {
	            $results['WIP'] = $results['WIP'] + $results_drug_narcotis['Re-Initiated'];
	        }


	        if(array_key_exists('Insufficiency',$results_drug_narcotis)) {
	        
	            $results['Insufficiency'] = $results_drug_narcotis['Insufficiency'];
	        }

	        if(array_key_exists('Stop Check',$results_drug_narcotis_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_drug_narcotis_closed['Stop Check'];
	        }
            
            if(array_key_exists('Clear',$results_drug_narcotis_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_drug_narcotis_closed['Clear'];
	        }

	        if(array_key_exists('Major Discrepancy',$results_drug_narcotis_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_drug_narcotis_closed['Major Discrepancy'];
	        }

	         if(array_key_exists('Minor Discrepancy',$results_drug_narcotis_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_drug_narcotis_closed['Minor Discrepancy'];
	        }

	        if(array_key_exists('Unable to verify',$results_drug_narcotis_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_drug_narcotis_closed['Unable to verify'];
	        }

	        if(array_key_exists('Worked with the same organization',$results_drug_narcotis_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_drug_narcotis_closed['Worked with the same organization'];
	        }

	        if(array_key_exists('Overseas check',$results_drug_narcotis_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_drug_narcotis_closed['Overseas check'];
	        }

	        if(array_key_exists('No Record Found',$results_drug_narcotis_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_drug_narcotis_closed['No Record Found'];
	        }


	        if(array_key_exists('Change of Address',$results_drug_narcotis_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_drug_narcotis_closed['Change of Address'];
	        }

	        if(array_key_exists('NA',$results_drug_narcotis_closed)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results_drug_narcotis_closed['NA'];
	        }
	     
	     
			foreach ($results as $key => $value) {
				$return[str_replace('/','',str_replace(' ','',$key))] = $value;
			}
			
		}
       return $return;

	} 


	public function tat_count_address()
	{
        $group_id = $this->db->query("select group_concat(id) as wip_filter from status where filter_status = 'WIP'");
        $res_filter = $group_id->result_array()[0];

        
        $filter  =  explode(',',$res_filter['wip_filter']);
       
		$this->db->select("addrver.tat_status,COUNT(addrver.tat_status) as total");

		$this->db->from('addrver');

        $this->db->join("addrverres",'addrverres.addrverid = addrver.id');

        if($this->user_info['tbl_roles_id']  != "1")
        {
            $this->db->where("addrver.has_case_id",$this->user_info['id']);   
        }

        $this->db->where_in('addrverres.verfstatus',$filter);

		$this->db->group_by('addrver.tat_status');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$addrver = $result->result_array();

		$results_tat = array_reduce($addrver, function($result, $item) {
		    if (!isset($result[$item['tat_status']])) $result[$item['tat_status']] = 0;
		    $result[$item['tat_status']] += $item['total'];
		    return $result;
		}, array());



        $return['total'] = array_sum($results_tat);
		
		$results['INNER TAT'] = "0";

		$results['OUTER TAT'] = "0";

		$results['APPRACHING TAT'] = "0";

		$results['TODAY TAT'] = "0";
		
		
		if(!empty($results))
		{

	        if(array_key_exists('IN TAT',$results_tat)) {
	            $results['INNER TAT'] = $results['INNER TAT'] + $results_tat['IN TAT'];
	        }

	        if(array_key_exists('in tat',$results_tat)) {
	            $results['INNER TAT'] = $results['INNER TAT'] + $results_tat['in tat'];
	        }

	        if(array_key_exists('OUT TAT',$results_tat)) {
	            $results['OUTER TAT'] = $results['OUTER TAT'] + $results_tat['OUT TAT'];
	        }


	        if(array_key_exists('out tat',$results_tat)) {
	            $results['OUTER TAT'] = $results['OUTER TAT'] + $results_tat['out tat'];
	        }

	        if(array_key_exists('AP TAT',$results_tat)) {
	            $results['APPRACHING TAT'] = $results['APPRACHING TAT'] + $results_tat['AP TAT'];
	        }

	         if(array_key_exists('ap tat',$results_tat)) {
	            $results['APPRACHING TAT'] = $results['APPRACHING TAT'] + $results_tat['ap tat'];
	        }

	        if(array_key_exists('TDY TAT',$results_tat)) {
	            $results['TODAY TAT'] = $results['TODAY TAT'] + $results_tat['TDY TAT'];
	        }

	         if(array_key_exists('tdy tat',$results_tat)) {
	            $results['TODAY TAT'] = $results['TODAY TAT'] + $results_tat['tdy tat'];
	        }
	      
			foreach ($results as $key => $value) {
				$return[str_replace('/','',str_replace(' ','',$key))] = $value;
			}
			
		}
       return $return;
         
	}


	public function tat_count_employment()
	{

		$group_id = $this->db->query("select group_concat(id) as wip_filter from status where filter_status = 'WIP'");
        $res_filter = $group_id->result_array()[0];
        
        $filter  =  explode(',',$res_filter['wip_filter']);
       
		$this->db->select("empver.tat_status,COUNT(empver.tat_status) as total");

		$this->db->from('empver');

        $this->db->join("empverres",'empverres.empverid = empver.id');

        if($this->user_info['tbl_roles_id']  != "1")
        {
            $this->db->where("empver.has_case_id",$this->user_info['id']);   
        }

        $this->db->where_in('empverres.verfstatus',$filter);

		$this->db->group_by('empver.tat_status');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$empver = $result->result_array();

		$results_tat = array_reduce($empver, function($result, $item) {
		    if (!isset($result[$item['tat_status']])) $result[$item['tat_status']] = 0;
		    $result[$item['tat_status']] += $item['total'];
		    return $result;
		}, array());



        $return['total'] = array_sum($results_tat);
		
		$results['INNER TAT'] = "0";

		$results['OUTER TAT'] = "0";

		$results['APPRACHING TAT'] = "0";

		$results['TODAY TAT'] = "0";
		
		
		if(!empty($results))
		{

	        if(array_key_exists('IN TAT',$results_tat)) {
	            $results['INNER TAT'] = $results['INNER TAT'] + $results_tat['IN TAT'];
	        }

	        if(array_key_exists('in tat',$results_tat)) {
	            $results['INNER TAT'] = $results['INNER TAT'] + $results_tat['in tat'];
	        }

	        if(array_key_exists('OUT TAT',$results_tat)) {
	            $results['OUTER TAT'] = $results['OUTER TAT'] + $results_tat['OUT TAT'];
	        }


	        if(array_key_exists('out tat',$results_tat)) {
	            $results['OUTER TAT'] = $results['OUTER TAT'] + $results_tat['out tat'];
	        }

	        if(array_key_exists('AP TAT',$results_tat)) {
	            $results['APPRACHING TAT'] = $results['APPRACHING TAT'] + $results_tat['AP TAT'];
	        }

	         if(array_key_exists('ap tat',$results_tat)) {
	            $results['APPRACHING TAT'] = $results['APPRACHING TAT'] + $results_tat['ap tat'];
	        }

	        if(array_key_exists('TDY TAT',$results_tat)) {
	            $results['TODAY TAT'] = $results['TODAY TAT'] + $results_tat['TDY TAT'];
	        }

	         if(array_key_exists('tdy tat',$results_tat)) {
	            $results['TODAY TAT'] = $results['TODAY TAT'] + $results_tat['tdy tat'];
	        }
	      
			foreach ($results as $key => $value) {
				$return[str_replace('/','',str_replace(' ','',$key))] = $value;
			}
			
		}
       return $return;
         
	}

    public function tat_count_education()
	{

		
		$group_id = $this->db->query("select group_concat(id) as wip_filter from status where filter_status = 'WIP'");
        $res_filter = $group_id->result_array()[0];
        
        $filter  =  explode(',',$res_filter['wip_filter']);
       
		$this->db->select("education.tat_status,COUNT(education.tat_status) as total");

		$this->db->from('education');

        $this->db->join("education_result",'education_result.education_id = education.id');

        if($this->user_info['tbl_roles_id']  != "1")
        {
            $this->db->where("education.has_case_id",$this->user_info['id']);   
        }

        $this->db->where_in('education_result.verfstatus',$filter);

		$this->db->group_by('education.tat_status');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$education = $result->result_array();

		$results_tat = array_reduce($education, function($result, $item) {
		    if (!isset($result[$item['tat_status']])) $result[$item['tat_status']] = 0;
		    $result[$item['tat_status']] += $item['total'];
		    return $result;
		}, array());



        $return['total'] = array_sum($results_tat);
		
		$results['INNER TAT'] = "0";

		$results['OUTER TAT'] = "0";

		$results['APPRACHING TAT'] = "0";

		$results['TODAY TAT'] = "0";
		
		
		if(!empty($results))
		{

	        if(array_key_exists('IN TAT',$results_tat)) {
	            $results['INNER TAT'] = $results['INNER TAT'] + $results_tat['IN TAT'];
	        }

	        if(array_key_exists('in tat',$results_tat)) {
	            $results['INNER TAT'] = $results['INNER TAT'] + $results_tat['in tat'];
	        }

	        if(array_key_exists('OUT TAT',$results_tat)) {
	            $results['OUTER TAT'] = $results['OUTER TAT'] + $results_tat['OUT TAT'];
	        }


	        if(array_key_exists('out tat',$results_tat)) {
	            $results['OUTER TAT'] = $results['OUTER TAT'] + $results_tat['out tat'];
	        }

	        if(array_key_exists('AP TAT',$results_tat)) {
	            $results['APPRACHING TAT'] = $results['APPRACHING TAT'] + $results_tat['AP TAT'];
	        }

	         if(array_key_exists('ap tat',$results_tat)) {
	            $results['APPRACHING TAT'] = $results['APPRACHING TAT'] + $results_tat['ap tat'];
	        }

	        if(array_key_exists('TDY TAT',$results_tat)) {
	            $results['TODAY TAT'] = $results['TODAY TAT'] + $results_tat['TDY TAT'];
	        }

	         if(array_key_exists('tdy tat',$results_tat)) {
	            $results['TODAY TAT'] = $results['TODAY TAT'] + $results_tat['tdy tat'];
	        }
	      
			foreach ($results as $key => $value) {
				$return[str_replace('/','',str_replace(' ','',$key))] = $value;
			}
			
		}
       return $return;
         
	}


    public function tat_count_reference()
	{

		
		$group_id = $this->db->query("select group_concat(id) as wip_filter from status where filter_status = 'WIP'");
        $res_filter = $group_id->result_array()[0];
        
        $filter  =  explode(',',$res_filter['wip_filter']);
       
		$this->db->select("reference.tat_status,COUNT(reference.tat_status) as total");

		$this->db->from('reference');

        $this->db->join("reference_result",'reference_result.reference_id = reference.id');

        if($this->user_info['tbl_roles_id']  != "1")
        {
            $this->db->where("reference.has_case_id",$this->user_info['id']);   
        }

        $this->db->where_in('reference_result.verfstatus',$filter);

		$this->db->group_by('reference.tat_status');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$reference = $result->result_array();

		$results_tat = array_reduce($reference, function($result, $item) {
		    if (!isset($result[$item['tat_status']])) $result[$item['tat_status']] = 0;
		    $result[$item['tat_status']] += $item['total'];
		    return $result;
		}, array());



        $return['total'] = array_sum($results_tat);
		
		$results['INNER TAT'] = "0";

		$results['OUTER TAT'] = "0";

		$results['APPRACHING TAT'] = "0";

		$results['TODAY TAT'] = "0";
		
		
		if(!empty($results))
		{

	        if(array_key_exists('IN TAT',$results_tat)) {
	            $results['INNER TAT'] = $results['INNER TAT'] + $results_tat['IN TAT'];
	        }

	        if(array_key_exists('in tat',$results_tat)) {
	            $results['INNER TAT'] = $results['INNER TAT'] + $results_tat['in tat'];
	        }

	        if(array_key_exists('OUT TAT',$results_tat)) {
	            $results['OUTER TAT'] = $results['OUTER TAT'] + $results_tat['OUT TAT'];
	        }


	        if(array_key_exists('out tat',$results_tat)) {
	            $results['OUTER TAT'] = $results['OUTER TAT'] + $results_tat['out tat'];
	        }

	        if(array_key_exists('AP TAT',$results_tat)) {
	            $results['APPRACHING TAT'] = $results['APPRACHING TAT'] + $results_tat['AP TAT'];
	        }

	         if(array_key_exists('ap tat',$results_tat)) {
	            $results['APPRACHING TAT'] = $results['APPRACHING TAT'] + $results_tat['ap tat'];
	        }

	        if(array_key_exists('TDY TAT',$results_tat)) {
	            $results['TODAY TAT'] = $results['TODAY TAT'] + $results_tat['TDY TAT'];
	        }

	         if(array_key_exists('tdy tat',$results_tat)) {
	            $results['TODAY TAT'] = $results['TODAY TAT'] + $results_tat['tdy tat'];
	        }
	      
			foreach ($results as $key => $value) {
				$return[str_replace('/','',str_replace(' ','',$key))] = $value;
			}
			
		}
       return $return;
         
	}


    public function tat_count_court()
	{

	    $group_id = $this->db->query("select group_concat(id) as wip_filter from status where filter_status = 'WIP'");
        $res_filter = $group_id->result_array()[0];
        
        $filter  =  explode(',',$res_filter['wip_filter']);
       
		$this->db->select("courtver.tat_status,COUNT(courtver.tat_status) as total");

		$this->db->from('courtver');

        $this->db->join("courtver_result",'courtver_result.courtver_id = courtver.id');

        if($this->user_info['tbl_roles_id']  != "1")
        {
            $this->db->where("courtver.has_case_id",$this->user_info['id']);   
        }

        $this->db->where_in('courtver_result.verfstatus',$filter);

		$this->db->group_by('courtver.tat_status');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$court = $result->result_array();

		$results_tat = array_reduce($court, function($result, $item) {
		    if (!isset($result[$item['tat_status']])) $result[$item['tat_status']] = 0;
		    $result[$item['tat_status']] += $item['total'];
		    return $result;
		}, array());



        $return['total'] = array_sum($results_tat);
		
		$results['INNER TAT'] = "0";

		$results['OUTER TAT'] = "0";

		$results['APPRACHING TAT'] = "0";

		$results['TODAY TAT'] = "0";
		
		
		if(!empty($results))
		{

	        if(array_key_exists('IN TAT',$results_tat)) {
	            $results['INNER TAT'] = $results['INNER TAT'] + $results_tat['IN TAT'];
	        }

	        if(array_key_exists('in tat',$results_tat)) {
	            $results['INNER TAT'] = $results['INNER TAT'] + $results_tat['in tat'];
	        }

	        if(array_key_exists('OUT TAT',$results_tat)) {
	            $results['OUTER TAT'] = $results['OUTER TAT'] + $results_tat['OUT TAT'];
	        }


	        if(array_key_exists('out tat',$results_tat)) {
	            $results['OUTER TAT'] = $results['OUTER TAT'] + $results_tat['out tat'];
	        }

	        if(array_key_exists('AP TAT',$results_tat)) {
	            $results['APPRACHING TAT'] = $results['APPRACHING TAT'] + $results_tat['AP TAT'];
	        }

	         if(array_key_exists('ap tat',$results_tat)) {
	            $results['APPRACHING TAT'] = $results['APPRACHING TAT'] + $results_tat['ap tat'];
	        }

	        if(array_key_exists('TDY TAT',$results_tat)) {
	            $results['TODAY TAT'] = $results['TODAY TAT'] + $results_tat['TDY TAT'];
	        }

	         if(array_key_exists('tdy tat',$results_tat)) {
	            $results['TODAY TAT'] = $results['TODAY TAT'] + $results_tat['tdy tat'];
	        }
	      
			foreach ($results as $key => $value) {
				$return[str_replace('/','',str_replace(' ','',$key))] = $value;
			}
			
		}
       return $return;
         
	}

	public function tat_count_global_database()
	{

	
		$group_id = $this->db->query("select group_concat(id) as wip_filter from status where filter_status = 'WIP'");
        $res_filter = $group_id->result_array()[0];
        
        $filter  =  explode(',',$res_filter['wip_filter']);
       
		$this->db->select("glodbver.tat_status,COUNT(glodbver.tat_status) as total");

		$this->db->from('glodbver');

        $this->db->join("glodbver_result",'glodbver_result.glodbver_id = glodbver.id');

        if($this->user_info['tbl_roles_id']  != "1")
        {
            $this->db->where("glodbver.has_case_id",$this->user_info['id']);   
        }

        $this->db->where_in('glodbver_result.verfstatus',$filter);

		$this->db->group_by('glodbver.tat_status');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$global_database = $result->result_array();

		$results_tat = array_reduce($global_database, function($result, $item) {
		    if (!isset($result[$item['tat_status']])) $result[$item['tat_status']] = 0;
		    $result[$item['tat_status']] += $item['total'];
		    return $result;
		}, array());



        $return['total'] = array_sum($results_tat);
		
		$results['INNER TAT'] = "0";

		$results['OUTER TAT'] = "0";

		$results['APPRACHING TAT'] = "0";

		$results['TODAY TAT'] = "0";
		
		
		if(!empty($results))
		{

	        if(array_key_exists('IN TAT',$results_tat)) {
	            $results['INNER TAT'] = $results['INNER TAT'] + $results_tat['IN TAT'];
	        }

	        if(array_key_exists('in tat',$results_tat)) {
	            $results['INNER TAT'] = $results['INNER TAT'] + $results_tat['in tat'];
	        }

	        if(array_key_exists('OUT TAT',$results_tat)) {
	            $results['OUTER TAT'] = $results['OUTER TAT'] + $results_tat['OUT TAT'];
	        }


	        if(array_key_exists('out tat',$results_tat)) {
	            $results['OUTER TAT'] = $results['OUTER TAT'] + $results_tat['out tat'];
	        }

	        if(array_key_exists('AP TAT',$results_tat)) {
	            $results['APPRACHING TAT'] = $results['APPRACHING TAT'] + $results_tat['AP TAT'];
	        }

	         if(array_key_exists('ap tat',$results_tat)) {
	            $results['APPRACHING TAT'] = $results['APPRACHING TAT'] + $results_tat['ap tat'];
	        }

	        if(array_key_exists('TDY TAT',$results_tat)) {
	            $results['TODAY TAT'] = $results['TODAY TAT'] + $results_tat['TDY TAT'];
	        }

	         if(array_key_exists('tdy tat',$results_tat)) {
	            $results['TODAY TAT'] = $results['TODAY TAT'] + $results_tat['tdy tat'];
	        }
	      
			foreach ($results as $key => $value) {
				$return[str_replace('/','',str_replace(' ','',$key))] = $value;
			}
			
		}
       return $return;
         
	}

	public function tat_count_pcc()
	{

	    $group_id = $this->db->query("select group_concat(id) as wip_filter from status where filter_status = 'WIP'");
        $res_filter = $group_id->result_array()[0];
        
        $filter  =  explode(',',$res_filter['wip_filter']);
       
		$this->db->select("pcc.tat_status,COUNT(pcc.tat_status) as total");

		$this->db->from('pcc');

        $this->db->join("pcc_result",'pcc_result.pcc_id = pcc.id');

        if($this->user_info['tbl_roles_id']  != "1")
        {
            $this->db->where("pcc.has_case_id",$this->user_info['id']);   
        }

        $this->db->where_in('pcc_result.verfstatus',$filter);

		$this->db->group_by('pcc.tat_status');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$pcc = $result->result_array();

		$results_tat = array_reduce($pcc, function($result, $item) {
		    if (!isset($result[$item['tat_status']])) $result[$item['tat_status']] = 0;
		    $result[$item['tat_status']] += $item['total'];
		    return $result;
		}, array());



        $return['total'] = array_sum($results_tat);
		
		$results['INNER TAT'] = "0";

		$results['OUTER TAT'] = "0";

		$results['APPRACHING TAT'] = "0";

		$results['TODAY TAT'] = "0";
		
		
		if(!empty($results))
		{

	        if(array_key_exists('IN TAT',$results_tat)) {
	            $results['INNER TAT'] = $results['INNER TAT'] + $results_tat['IN TAT'];
	        }

	        if(array_key_exists('in tat',$results_tat)) {
	            $results['INNER TAT'] = $results['INNER TAT'] + $results_tat['in tat'];
	        }

	        if(array_key_exists('OUT TAT',$results_tat)) {
	            $results['OUTER TAT'] = $results['OUTER TAT'] + $results_tat['OUT TAT'];
	        }


	        if(array_key_exists('out tat',$results_tat)) {
	            $results['OUTER TAT'] = $results['OUTER TAT'] + $results_tat['out tat'];
	        }

	        if(array_key_exists('AP TAT',$results_tat)) {
	            $results['APPRACHING TAT'] = $results['APPRACHING TAT'] + $results_tat['AP TAT'];
	        }

	         if(array_key_exists('ap tat',$results_tat)) {
	            $results['APPRACHING TAT'] = $results['APPRACHING TAT'] + $results_tat['ap tat'];
	        }

	        if(array_key_exists('TDY TAT',$results_tat)) {
	            $results['TODAY TAT'] = $results['TODAY TAT'] + $results_tat['TDY TAT'];
	        }

	         if(array_key_exists('tdy tat',$results_tat)) {
	            $results['TODAY TAT'] = $results['TODAY TAT'] + $results_tat['tdy tat'];
	        }
	      
			foreach ($results as $key => $value) {
				$return[str_replace('/','',str_replace(' ','',$key))] = $value;
			}
			
		}
       return $return;
         
	}
     
    public function tat_count_identity()
	{

		$group_id = $this->db->query("select group_concat(id) as wip_filter from status where filter_status = 'WIP'");
        $res_filter = $group_id->result_array()[0];
        
        $filter  =  explode(',',$res_filter['wip_filter']);
       
		$this->db->select("identity.tat_status,COUNT(identity.tat_status) as total");

		$this->db->from('identity');

        $this->db->join("identity_result",'identity_result.identity_id = identity.id');

        if($this->user_info['tbl_roles_id']  != "1")
        {
            $this->db->where("identity.has_case_id",$this->user_info['id']);   
        }

        $this->db->where_in('identity_result.verfstatus',$filter);

		$this->db->group_by('identity.tat_status');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$identity = $result->result_array();

		$results_tat = array_reduce($identity, function($result, $item) {
		    if (!isset($result[$item['tat_status']])) $result[$item['tat_status']] = 0;
		    $result[$item['tat_status']] += $item['total'];
		    return $result;
		}, array());



        $return['total'] = array_sum($results_tat);
		
		$results['INNER TAT'] = "0";

		$results['OUTER TAT'] = "0";

		$results['APPRACHING TAT'] = "0";

		$results['TODAY TAT'] = "0";
		
		
		if(!empty($results))
		{

	        if(array_key_exists('IN TAT',$results_tat)) {
	            $results['INNER TAT'] = $results['INNER TAT'] + $results_tat['IN TAT'];
	        }

	        if(array_key_exists('in tat',$results_tat)) {
	            $results['INNER TAT'] = $results['INNER TAT'] + $results_tat['in tat'];
	        }

	        if(array_key_exists('OUT TAT',$results_tat)) {
	            $results['OUTER TAT'] = $results['OUTER TAT'] + $results_tat['OUT TAT'];
	        }


	        if(array_key_exists('out tat',$results_tat)) {
	            $results['OUTER TAT'] = $results['OUTER TAT'] + $results_tat['out tat'];
	        }

	        if(array_key_exists('AP TAT',$results_tat)) {
	            $results['APPRACHING TAT'] = $results['APPRACHING TAT'] + $results_tat['AP TAT'];
	        }

	         if(array_key_exists('ap tat',$results_tat)) {
	            $results['APPRACHING TAT'] = $results['APPRACHING TAT'] + $results_tat['ap tat'];
	        }

	        if(array_key_exists('TDY TAT',$results_tat)) {
	            $results['TODAY TAT'] = $results['TODAY TAT'] + $results_tat['TDY TAT'];
	        }

	         if(array_key_exists('tdy tat',$results_tat)) {
	            $results['TODAY TAT'] = $results['TODAY TAT'] + $results_tat['tdy tat'];
	        }
	      
			foreach ($results as $key => $value) {
				$return[str_replace('/','',str_replace(' ','',$key))] = $value;
			}
			
		}
       return $return;
         
	}
	
	public function tat_count_credit_report()
	{

		$group_id = $this->db->query("select group_concat(id) as wip_filter from status where filter_status = 'WIP'");
        $res_filter = $group_id->result_array()[0];
        
        $filter  =  explode(',',$res_filter['wip_filter']);
       
		$this->db->select("credit_report.tat_status,COUNT(credit_report.tat_status) as total");

		$this->db->from('credit_report');

        $this->db->join("credit_report_result",'credit_report_result.credit_report_id = credit_report.id');

        if($this->user_info['tbl_roles_id']  != "1")
        {
            $this->db->where("credit_report.has_case_id",$this->user_info['id']);   
        }

        $this->db->where_in('credit_report_result.verfstatus',$filter);

		$this->db->group_by('credit_report.tat_status');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$credit_report = $result->result_array();

		$results_tat = array_reduce($credit_report, function($result, $item) {
		    if (!isset($result[$item['tat_status']])) $result[$item['tat_status']] = 0;
		    $result[$item['tat_status']] += $item['total'];
		    return $result;
		}, array());



        $return['total'] = array_sum($results_tat);
		
		$results['INNER TAT'] = "0";

		$results['OUTER TAT'] = "0";

		$results['APPRACHING TAT'] = "0";

		$results['TODAY TAT'] = "0";
		
		
		if(!empty($results))
		{

	        if(array_key_exists('IN TAT',$results_tat)) {
	            $results['INNER TAT'] = $results['INNER TAT'] + $results_tat['IN TAT'];
	        }

	        if(array_key_exists('in tat',$results_tat)) {
	            $results['INNER TAT'] = $results['INNER TAT'] + $results_tat['in tat'];
	        }

	        if(array_key_exists('OUT TAT',$results_tat)) {
	            $results['OUTER TAT'] = $results['OUTER TAT'] + $results_tat['OUT TAT'];
	        }


	        if(array_key_exists('out tat',$results_tat)) {
	            $results['OUTER TAT'] = $results['OUTER TAT'] + $results_tat['out tat'];
	        }

	        if(array_key_exists('AP TAT',$results_tat)) {
	            $results['APPRACHING TAT'] = $results['APPRACHING TAT'] + $results_tat['AP TAT'];
	        }

	         if(array_key_exists('ap tat',$results_tat)) {
	            $results['APPRACHING TAT'] = $results['APPRACHING TAT'] + $results_tat['ap tat'];
	        }

	        if(array_key_exists('TDY TAT',$results_tat)) {
	            $results['TODAY TAT'] = $results['TODAY TAT'] + $results_tat['TDY TAT'];
	        }

	         if(array_key_exists('tdy tat',$results_tat)) {
	            $results['TODAY TAT'] = $results['TODAY TAT'] + $results_tat['tdy tat'];
	        }
	      
			foreach ($results as $key => $value) {
				$return[str_replace('/','',str_replace(' ','',$key))] = $value;
			}
			
		}
       return $return;
         
	}

	public function tat_count_drugs()
	{
		$group_id = $this->db->query("select group_concat(id) as wip_filter from status where filter_status = 'WIP'");
        $res_filter = $group_id->result_array()[0];
        
        $filter  =  explode(',',$res_filter['wip_filter']);
       
		$this->db->select("drug_narcotis.tat_status,COUNT(drug_narcotis.tat_status) as total");

		$this->db->from('drug_narcotis');

        $this->db->join("drug_narcotis_result",'drug_narcotis_result.drug_narcotis_id = drug_narcotis.id');

        if($this->user_info['tbl_roles_id']  != "1")
        {
            $this->db->where("drug_narcotis.has_case_id",$this->user_info['id']);   
        }


        $this->db->where_in('drug_narcotis_result.verfstatus',$filter);

		$this->db->group_by('drug_narcotis.tat_status');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$drugs = $result->result_array();

		$results_tat = array_reduce($drugs, function($result, $item) {
		    if (!isset($result[$item['tat_status']])) $result[$item['tat_status']] = 0;
		    $result[$item['tat_status']] += $item['total'];
		    return $result;
		}, array());



        $return['total'] = array_sum($results_tat);
		
		$results['INNER TAT'] = "0";

		$results['OUTER TAT'] = "0";

		$results['APPRACHING TAT'] = "0";

		$results['TODAY TAT'] = "0";
		
		
		if(!empty($results))
		{

	        if(array_key_exists('IN TAT',$results_tat)) {
	            $results['INNER TAT'] = $results['INNER TAT'] + $results_tat['IN TAT'];
	        }

	        if(array_key_exists('in tat',$results_tat)) {
	            $results['INNER TAT'] = $results['INNER TAT'] + $results_tat['in tat'];
	        }

	        if(array_key_exists('OUT TAT',$results_tat)) {
	            $results['OUTER TAT'] = $results['OUTER TAT'] + $results_tat['OUT TAT'];
	        }


	        if(array_key_exists('out tat',$results_tat)) {
	            $results['OUTER TAT'] = $results['OUTER TAT'] + $results_tat['out tat'];
	        }

	        if(array_key_exists('AP TAT',$results_tat)) {
	            $results['APPRACHING TAT'] = $results['APPRACHING TAT'] + $results_tat['AP TAT'];
	        }

	         if(array_key_exists('ap tat',$results_tat)) {
	            $results['APPRACHING TAT'] = $results['APPRACHING TAT'] + $results_tat['ap tat'];
	        }

	        if(array_key_exists('TDY TAT',$results_tat)) {
	            $results['TODAY TAT'] = $results['TODAY TAT'] + $results_tat['TDY TAT'];
	        }

	         if(array_key_exists('tdy tat',$results_tat)) {
	            $results['TODAY TAT'] = $results['TODAY TAT'] + $results_tat['tdy tat'];
	        }
	      
			foreach ($results as $key => $value) {
				$return[str_replace('/','',str_replace(' ','',$key))] = $value;
			}
			
		}
       return $return;

	} 



	public function report_count_address()
	{

		$this->db->select("CASE WHEN  addrverres.verfstatus =  1 THEN 'WIP' WHEN  addrverres.verfstatus =  9 THEN 'Stop Check'  WHEN addrverres.verfstatus = 11 THEN 'WIP'  WHEN addrverres.verfstatus = 12 THEN 'WIP' WHEN addrverres.verfstatus = 13 THEN 'WIP' WHEN addrverres.verfstatus = 14 THEN 'WIP'  WHEN addrverres.verfstatus = 16 THEN 'YTR'  WHEN addrverres.verfstatus =  17  THEN 'Clear'  WHEN addrverres.verfstatus = 18 THEN 'Insufficiency' WHEN addrverres.verfstatus = 19  THEN 'Major Discrepancy'  WHEN addrverres.verfstatus =  20 THEN 'Minor Discrepancy' WHEN addrverres.verfstatus = 21 THEN 'Unable to verify'  WHEN addrverres.verfstatus = 22 THEN 'Worked with the same organization' WHEN addrverres.verfstatus = 23 THEN 'WIP' WHEN addrverres.verfstatus = 24 THEN 'Overseas check' WHEN addrverres.verfstatus = 25 THEN 'Closed' WHEN addrverres.verfstatus = 27 THEN 'Change of Address' WHEN addrverres.verfstatus = 28 THEN 'NA'  WHEN addrverres.verfstatus = 26 THEN 'WIP'  END AS report_value,COUNT(addrverres.verfstatus) as total");

		$this->db->from('addrver');

		$this->db->join("addrverres",'addrverres.addrverid = addrver.id');
 
		$this->db->group_by('addrverres.verfstatus');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$addrver = $result->result_array();

	    return $addrver;
         
	}


	public function report_count_employment()
	{

		$this->db->select("CASE WHEN  empverres.verfstatus =  1 THEN 'WIP' WHEN  empverres.verfstatus =  9 THEN 'Stop Check'  WHEN empverres.verfstatus = 11 THEN 'WIP'  WHEN empverres.verfstatus = 12 THEN 'WIP' WHEN empverres.verfstatus = 13 THEN 'WIP' WHEN empverres.verfstatus = 14 THEN 'WIP'  WHEN empverres.verfstatus = 16 THEN 'YTR'  WHEN empverres.verfstatus =  17  THEN 'Clear'  WHEN empverres.verfstatus = 18 THEN 'Insufficiency' WHEN empverres.verfstatus = 19  THEN 'Major Discrepancy'  WHEN empverres.verfstatus =  20 THEN 'Minor Discrepancy' WHEN empverres.verfstatus = 21 THEN 'Unable to verify'  WHEN empverres.verfstatus = 22 THEN 'Worked with the same organization' WHEN empverres.verfstatus = 23 THEN 'WIP' WHEN empverres.verfstatus = 24 THEN 'Overseas check' WHEN empverres.verfstatus = 25 THEN 'Closed' WHEN empverres.verfstatus = 27 THEN 'Change of Address' WHEN empverres.verfstatus = 28 THEN 'NA'  WHEN empverres.verfstatus = 26 THEN 'WIP'  END AS report_value,COUNT(empverres.verfstatus) as total");

		$this->db->from('empver');

        $this->db->join("empverres",'empverres.empverid = empver.id');
 
        $this->db->group_by('empverres.verfstatus');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$empver = $result->result_array();
		
		return $empver;
         
	}

    public function report_count_education()
	{

		$this->db->select("CASE WHEN  education_result.verfstatus =  1 THEN 'WIP' WHEN  education_result.verfstatus =  9 THEN 'Stop Check'  WHEN education_result.verfstatus = 11 THEN 'WIP'  WHEN education_result.verfstatus = 12 THEN 'WIP' WHEN education_result.verfstatus = 13 THEN 'WIP' WHEN education_result.verfstatus = 14 THEN 'WIP'  WHEN education_result.verfstatus = 16 THEN 'YTR'  WHEN education_result.verfstatus =  17  THEN 'Clear'  WHEN education_result.verfstatus = 18 THEN 'Insufficiency' WHEN education_result.verfstatus = 19  THEN 'Major Discrepancy'  WHEN education_result.verfstatus =  20 THEN 'Minor Discrepancy' WHEN education_result.verfstatus = 21 THEN 'Unable to verify'  WHEN education_result.verfstatus = 22 THEN 'Worked with the same organization' WHEN education_result.verfstatus = 23 THEN 'WIP' WHEN education_result.verfstatus = 24 THEN 'Overseas check' WHEN education_result.verfstatus = 25 THEN 'Closed' WHEN education_result.verfstatus = 27 THEN 'Change of Address' WHEN education_result.verfstatus = 28 THEN 'NA'  WHEN education_result.verfstatus = 26 THEN 'WIP'  END AS report_value,COUNT(education_result.verfstatus) as total");


		$this->db->from('education');

		$this->db->join("education_result",'education_result.education_id = education.id');
 
		$this->db->group_by('education_result.verfstatus');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$education = $result->result_array();

		return $education;	
         
	}


    public function report_count_reference()
	{

		
		$this->db->select("CASE  WHEN  reference_result.verfstatus =  1 THEN 'WIP'  WHEN  reference_result.verfstatus =  9 THEN 'Stop Check'  WHEN reference_result.verfstatus = 11 THEN 'WIP'  WHEN reference_result.verfstatus = 12 THEN 'WIP' WHEN reference_result.verfstatus = 13 THEN 'WIP' WHEN reference_result.verfstatus = 14 THEN 'WIP'  WHEN reference_result.verfstatus = 16 THEN 'YTR'  WHEN reference_result.verfstatus =  17  THEN 'Clear'  WHEN reference_result.verfstatus = 18 THEN 'Insufficiency' WHEN reference_result.verfstatus = 19  THEN 'Major Discrepancy'  WHEN reference_result.verfstatus =  20 THEN 'Minor Discrepancy' WHEN reference_result.verfstatus = 21 THEN 'Unable to verify'  WHEN reference_result.verfstatus = 22 THEN 'Worked with the same organization' WHEN reference_result.verfstatus = 23 THEN 'WIP' WHEN reference_result.verfstatus = 24 THEN 'Overseas check' WHEN reference_result.verfstatus = 25 THEN 'Closed' WHEN reference_result.verfstatus = 27 THEN 'Change of Address' WHEN reference_result.verfstatus = 28 THEN 'NA'  WHEN reference_result.verfstatus = 26 THEN 'WIP'  END AS report_value,COUNT(reference_result.verfstatus) as total");

		$this->db->from('reference');

		$this->db->join("reference_result",'reference_result.reference_id = reference.id');
 
	    $this->db->group_by('reference_result.verfstatus');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$reference = $result->result_array();
		
		
       return $reference;
         
	}


    public function report_count_court()
	{
	   $this->db->select("CASE  WHEN  courtver_result.verfstatus =  1 THEN 'WIP'  WHEN  courtver_result.verfstatus =  9 THEN 'Stop Check'  WHEN courtver_result.verfstatus = 11 THEN 'WIP'  WHEN courtver_result.verfstatus = 12 THEN 'WIP' WHEN courtver_result.verfstatus = 13 THEN 'WIP' WHEN courtver_result.verfstatus = 14 THEN 'WIP'  WHEN courtver_result.verfstatus = 16 THEN 'YTR'  WHEN courtver_result.verfstatus =  17  THEN 'Clear'  WHEN courtver_result.verfstatus = 18 THEN 'Insufficiency' WHEN courtver_result.verfstatus = 19  THEN 'Major Discrepancy'  WHEN courtver_result.verfstatus =  20 THEN 'Minor Discrepancy' WHEN courtver_result.verfstatus = 21 THEN 'Unable to verify'  WHEN courtver_result.verfstatus = 22 THEN 'Worked with the same organization' WHEN courtver_result.verfstatus = 23 THEN 'WIP' WHEN courtver_result.verfstatus = 24 THEN 'Overseas check' WHEN courtver_result.verfstatus = 25 THEN 'Closed' WHEN courtver_result.verfstatus = 27 THEN 'Change of Address' WHEN courtver_result.verfstatus = 28 THEN 'NA'  WHEN courtver_result.verfstatus = 26 THEN 'WIP'  END AS report_value,COUNT(courtver_result.verfstatus) as total");

		$this->db->from('courtver');

		$this->db->join("courtver_result",'courtver_result.courtver_id = courtver.id');
 
		$this->db->group_by('courtver_result.verfstatus');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$court = $result->result_array();
		
        return  $court;
	}

	public function report_count_global_database()
	{

	
		 $this->db->select("CASE  WHEN  glodbver_result.verfstatus =  1 THEN 'WIP' WHEN  glodbver_result.verfstatus =  9 THEN 'Stop Check'  WHEN glodbver_result.verfstatus = 11 THEN 'WIP'  WHEN glodbver_result.verfstatus = 12 THEN 'WIP' WHEN glodbver_result.verfstatus = 13 THEN 'WIP' WHEN glodbver_result.verfstatus = 14 THEN 'WIP'  WHEN glodbver_result.verfstatus = 16 THEN 'YTR'  WHEN glodbver_result.verfstatus =  17  THEN 'Clear'  WHEN glodbver_result.verfstatus = 18 THEN 'Insufficiency' WHEN glodbver_result.verfstatus = 19  THEN 'Major Discrepancy'  WHEN glodbver_result.verfstatus =  20 THEN 'Minor Discrepancy' WHEN glodbver_result.verfstatus = 21 THEN 'Unable to verify'  WHEN glodbver_result.verfstatus = 22 THEN 'Worked with the same organization' WHEN glodbver_result.verfstatus = 23 THEN 'WIP' WHEN glodbver_result.verfstatus = 24 THEN 'Overseas check' WHEN glodbver_result.verfstatus = 25 THEN 'Closed' WHEN glodbver_result.verfstatus = 27 THEN 'Change of Address' WHEN glodbver_result.verfstatus = 28 THEN 'NA'  WHEN glodbver_result.verfstatus = 26 THEN 'WIP'  END AS report_value,COUNT(glodbver_result.verfstatus) as total");

		$this->db->from('glodbver');

		$this->db->join("glodbver_result",'glodbver_result.glodbver_id = glodbver.id');
 
		$this->db->group_by('glodbver_result.verfstatus');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$glodbver = $result->result_array();
	
        return $glodbver;
         
	}

	public function report_count_pcc()
	{

	
	    $this->db->select("CASE WHEN  pcc_result.verfstatus =  1 THEN 'WIP' WHEN  pcc_result.verfstatus =  9 THEN 'Stop Check'  WHEN pcc_result.verfstatus = 11 THEN 'WIP'  WHEN pcc_result.verfstatus = 12 THEN 'WIP' WHEN pcc_result.verfstatus = 13 THEN 'WIP' WHEN pcc_result.verfstatus = 14 THEN 'WIP'  WHEN pcc_result.verfstatus = 16 THEN 'YTR'  WHEN pcc_result.verfstatus =  17  THEN 'Clear'  WHEN pcc_result.verfstatus = 18 THEN 'Insufficiency' WHEN pcc_result.verfstatus = 19  THEN 'Major Discrepancy'  WHEN pcc_result.verfstatus =  20 THEN 'Minor Discrepancy' WHEN pcc_result.verfstatus = 21 THEN 'Unable to verify'  WHEN pcc_result.verfstatus = 22 THEN 'Worked with the same organization' WHEN pcc_result.verfstatus = 23 THEN 'WIP' WHEN pcc_result.verfstatus = 24 THEN 'Overseas check' WHEN pcc_result.verfstatus = 25 THEN 'Closed' WHEN pcc_result.verfstatus = 27 THEN 'Change of Address' WHEN pcc_result.verfstatus = 28 THEN 'NA'  WHEN pcc_result.verfstatus = 26 THEN 'WIP'  END AS report_value,COUNT(pcc_result.verfstatus) as total");

		$this->db->from('pcc');

		$this->db->join("pcc_result",'pcc_result.pcc_id = pcc.id');
 
	    $this->db->group_by('pcc_result.verfstatus');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$pcc = $result->result_array();
		
		return $pcc;
	}
     
    public function report_count_identity()
	{
		 
	   $this->db->select("CASE   WHEN  identity_result.verfstatus =  1 THEN 'WIP' WHEN  identity_result.verfstatus =  9 THEN 'Stop Check'  WHEN identity_result.verfstatus = 11 THEN 'WIP'  WHEN identity_result.verfstatus = 12 THEN 'WIP' WHEN identity_result.verfstatus = 13 THEN 'WIP' WHEN identity_result.verfstatus = 14 THEN 'WIP'  WHEN identity_result.verfstatus = 16 THEN 'YTR'  WHEN identity_result.verfstatus =  17  THEN 'Clear'  WHEN identity_result.verfstatus = 18 THEN 'Insufficiency' WHEN identity_result.verfstatus = 19  THEN 'Major Discrepancy'  WHEN identity_result.verfstatus =  20 THEN 'Minor Discrepancy' WHEN identity_result.verfstatus = 21 THEN 'Unable to verify'  WHEN identity_result.verfstatus = 22 THEN 'Worked with the same organization' WHEN identity_result.verfstatus = 23 THEN 'WIP' WHEN identity_result.verfstatus = 24 THEN 'Overseas check' WHEN identity_result.verfstatus = 25 THEN 'Closed' WHEN identity_result.verfstatus = 27 THEN 'Change of Address' WHEN identity_result.verfstatus = 28 THEN 'NA'  WHEN identity_result.verfstatus = 26 THEN 'WIP'  END AS report_value,COUNT(identity_result.verfstatus) as total");

		$this->db->from('identity');

	    $this->db->join("identity_result",'identity_result.identity_id = identity.id');
 

        $this->db->group_by('identity_result.verfstatus');


		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$identity = $result->result_array();
       
        return $identity;
	     
	}
	
	public function report_count_credit_report()
	{

		$this->db->select("CASE WHEN  credit_report_result.verfstatus =  1 THEN 'WIP'  WHEN  credit_report_result.verfstatus =  9 THEN 'Stop Check'  WHEN credit_report_result.verfstatus = 11 THEN 'WIP'  WHEN credit_report_result.verfstatus = 12 THEN 'WIP' WHEN credit_report_result.verfstatus = 13 THEN 'WIP' WHEN credit_report_result.verfstatus = 14 THEN 'WIP'  WHEN credit_report_result.verfstatus = 16 THEN 'YTR'  WHEN credit_report_result.verfstatus =  17  THEN 'Clear'  WHEN credit_report_result.verfstatus = 18 THEN 'Insufficiency' WHEN credit_report_result.verfstatus = 19  THEN 'Major Discrepancy'  WHEN credit_report_result.verfstatus =  20 THEN 'Minor Discrepancy' WHEN credit_report_result.verfstatus = 21 THEN 'Unable to verify'  WHEN credit_report_result.verfstatus = 22 THEN 'Worked with the same organization' WHEN credit_report_result.verfstatus = 23 THEN 'WIP' WHEN credit_report_result.verfstatus = 24 THEN 'Overseas check' WHEN credit_report_result.verfstatus = 25 THEN 'Closed' WHEN credit_report_result.verfstatus = 27 THEN 'Change of Address' WHEN credit_report_result.verfstatus = 28 THEN 'NA'  WHEN credit_report_result.verfstatus = 26 THEN 'WIP'  END AS report_value,COUNT(credit_report_result.verfstatus) as total");


		$this->db->from('credit_report');

	    $this->db->join("credit_report_result",'credit_report_result.credit_report_id = credit_report.id');
 
		$this->db->group_by('credit_report_result.verfstatus');


		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$credit_report = $result->result_array();

       return $credit_report;
         
	}

	public function report_count_drugs()
	{
		$this->db->select("CASE  WHEN  drug_narcotis_result.verfstatus =  1 THEN 'WIP'  WHEN  drug_narcotis_result.verfstatus =  9 THEN 'Stop Check'  WHEN drug_narcotis_result.verfstatus = 11 THEN 'WIP'  WHEN drug_narcotis_result.verfstatus = 12 THEN 'WIP' WHEN drug_narcotis_result.verfstatus = 13 THEN 'WIP' WHEN drug_narcotis_result.verfstatus = 14 THEN 'WIP'  WHEN drug_narcotis_result.verfstatus = 16 THEN 'YTR'  WHEN drug_narcotis_result.verfstatus =  17  THEN 'Clear'  WHEN drug_narcotis_result.verfstatus = 18 THEN 'Insufficiency' WHEN drug_narcotis_result.verfstatus = 19  THEN 'Major Discrepancy'  WHEN drug_narcotis_result.verfstatus =  20 THEN 'Minor Discrepancy' WHEN drug_narcotis_result.verfstatus = 21 THEN 'Unable to verify'  WHEN drug_narcotis_result.verfstatus = 22 THEN 'Worked with the same organization' WHEN drug_narcotis_result.verfstatus = 23 THEN 'WIP' WHEN drug_narcotis_result.verfstatus = 24 THEN 'Overseas check' WHEN drug_narcotis_result.verfstatus = 25 THEN 'Closed' WHEN drug_narcotis_result.verfstatus = 27 THEN 'Change of Address' WHEN drug_narcotis_result.verfstatus = 28 THEN 'NA'  WHEN drug_narcotis_result.verfstatus = 26 THEN 'WIP'  END AS report_value,COUNT(drug_narcotis_result.verfstatus) as total");

		$this->db->from('drug_narcotis');

		$this->db->join("drug_narcotis_result",'drug_narcotis_result.drug_narcotis_id = drug_narcotis.id');

	    $this->db->group_by('drug_narcotis_result.verfstatus');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$drug_narcotis = $result->result_array();

		
       return $drug_narcotis;

	}

	public function report_count_candidates($clientid,$from,$to)
	{     
		$where_condition = "DATE_FORMAT(`candidates_info`.`overallclosuredate`,'%Y-%m-%d') BETWEEN '$from' AND '$to'";
        $array_condition_wip_insuff =  array('1','5');
        $array_condition_closed =  array('2','3','4','6','7','8');
		$this->db->select("CASE  WHEN candidates_info.overallstatus = 1 THEN 'WIP' WHEN candidates_info.overallstatus = 5 THEN 'Insufficiency' END AS status_value,COUNT(candidates_info.overallstatus) as total");

		$this->db->from('candidates_info');


		if(!empty($clientid))
		{

		 $this->db->where("candidates_info.clientid",$clientid);

	    }

	    $this->db->where_in('candidates_info.overallstatus',$array_condition_wip_insuff);
   
		$this->db->group_by('candidates_info.overallstatus');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$candidate = $result->result_array();

		$this->db->select("CASE  WHEN candidates_info.overallstatus = 2 THEN 'NA'  WHEN  candidates_info.overallstatus =  3 THEN 'Stop Check' WHEN candidates_info.overallstatus =  4  THEN 'Clear' WHEN candidates_info.overallstatus = 6  THEN 'Major Discrepancy'  WHEN candidates_info.overallstatus =  7 THEN 'Minor Discrepancy' WHEN candidates_info.overallstatus = 8 THEN 'Unable to verify'  END AS status_value,COUNT(candidates_info.overallstatus) as total");


		$this->db->from('candidates_info');

		if(!empty($clientid))
		{

		 $this->db->where("candidates_info.clientid",$clientid);

	    }
   
        $this->db->where($where_condition);

        $this->db->where_in('candidates_info.overallstatus',$array_condition_closed);

		$this->db->group_by('candidates_info.overallstatus');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$candidate_closed = $result->result_array();


		$results_candidate = array_reduce($candidate, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());

		$results_candidate_closed = array_reduce($candidate_closed, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());

    
        $return['total'] = array_sum($results_candidate) + array_sum($results_candidate_closed);
		
		//if(!isset($results['WIP']))
		//{
		$results['WIP'] = '0';	
		//}
        if(!isset($results['Insufficiency']))
		{
        $results['Insufficiency'] = '0';
        }
		
        $results['Closed'] = '0';
		$return['NoRecordFound'] = '0'; 
		

		if(!empty($results))
		{
            if(array_key_exists('WIP',$results_candidate)) {
	        
	           $results['WIP'] = $results_candidate['WIP'];
	        }

           

	       /* if(array_key_exists(NULL,$results_candidate)) {
	            $results['WIP'] = $results['WIP'] + $results_candidate[''];
	        }*/

	        if(array_key_exists('Insufficiency',$results_candidate)) {
	        
	            $results['Insufficiency'] = $results_candidate['Insufficiency'];
	        }

	        if(array_key_exists('Stop Check',$results_candidate_closed)) {
	        	
	            $results['Stop Check'] =  $results_candidate_closed['Stop Check'];
	        }
            
            if(array_key_exists('Clear',$results_candidate_closed)) {
	        	
	            $results['Clear'] =  $results_candidate_closed['Clear'];
	        }

	        if(array_key_exists('Major Discrepancy',$results_candidate_closed)) {
	        	
	            $results['Major Discrepancy'] = $results_candidate_closed['Major Discrepancy'];
	        }

	         if(array_key_exists('Minor Discrepancy',$results_candidate_closed)) {
	        	
	            $results['Minor Discrepancy'] =  $results_candidate_closed['Minor Discrepancy'];
	        }

	        if(array_key_exists('Unable to verify',$results_candidate_closed)) {
	        	
	            $results['Unable to verify'] =  $results_candidate_closed['Unable to verify'];
	        }

	        if(array_key_exists('NA',$results_candidate_closed)) {
	        	
	            $results['NA'] = $results_candidate_closed['NA'];
	        }
	     
	     
			foreach ($results as $key => $value) {
				$return[str_replace('/','',str_replace(' ','',$key))] = $value;
			}
			
		}
       return $return;
         
	} 

	public function status_count_address_menu($where_array)
	{

		$this->db->select("COUNT(addrver.id) as total");

		$this->db->from('addrver');

		$this->db->join("addrverres",'addrverres.addrverid = addrver.id');

		$this->db->where($where_array);

		$this->db->where("(addrverres.verfstatus = 11 or addrverres.verfstatus = 12 or addrverres.verfstatus = 13 or addrverres.verfstatus = 14  or addrverres.verfstatus = 26)");
       

		$addrver = $this->db->get()->row();

		record_db_error($this->db->last_query());

        return $addrver;
         
	}

	public function status_count_employment_menu($where_array)
	{

		$this->db->select("COUNT(empver.id) as total");

		$this->db->from('empver');

		$this->db->join("empverres",'empverres.empverid = empver.id');

		$this->db->join("company_database",'company_database.id = empver.nameofthecompany');

		$this->db->where($where_array);

		$this->db->where("(empverres.verfstatus = 11 or empverres.verfstatus = 12 or empverres.verfstatus = 13 or empverres.verfstatus = 14  or empverres.verfstatus = 26)");
 

		$empver = $this->db->get()->row();

		record_db_error($this->db->last_query());

        return $empver;
         
	}

	public function status_count_education_menu($where_array)
	{

		$this->db->select("COUNT(education.id) as total");

		$this->db->from('education');

		$this->db->join("education_result",'education_result.education_id = education.id');

		$this->db->where($where_array);

		$this->db->where("(education_result.verfstatus = 11 or education_result.verfstatus = 12 or education_result.verfstatus = 13 or education_result.verfstatus = 14  or education_result.verfstatus = 26)");
 

		$education = $this->db->get()->row();

		record_db_error($this->db->last_query());

		
        return $education;
         
	}
	public function status_count_reference_menu($where_array)
	{

		$this->db->select("COUNT(reference.id) as total");

		$this->db->from('reference');

		$this->db->join("reference_result",'reference_result.reference_id = reference.id');

		$this->db->where($where_array);

		$this->db->where("(reference_result.verfstatus = 11 or reference_result.verfstatus = 12 or reference_result.verfstatus = 13 or reference_result.verfstatus = 14  or reference_result.verfstatus = 26)");
 

		$reference = $this->db->get()->row();

		record_db_error($this->db->last_query());

		
        return $reference;
         
	}
	public function status_count_court_menu($where_array)
	{

		$this->db->select("COUNT(courtver.id) as total");

		$this->db->from('courtver');

		$this->db->join("courtver_result",'courtver_result.courtver_id = courtver.id');

		$this->db->where($where_array);

		$this->db->where("(courtver_result.verfstatus = 11 or courtver_result.verfstatus = 12 or courtver_result.verfstatus = 13 or courtver_result.verfstatus = 14  or courtver_result.verfstatus = 26)");

		$court = $this->db->get()->row();

		record_db_error($this->db->last_query());


        return $court;
         
	}
	public function status_count_global_database_menu($where_array)
	{

		$this->db->select("COUNT(glodbver.id) as total");

		$this->db->from('glodbver');

		$this->db->join("glodbver_result",'glodbver_result.glodbver_id = glodbver.id');

		$this->db->where($where_array);

		$this->db->where("(glodbver_result.verfstatus = 11 or glodbver_result.verfstatus = 12 or glodbver_result.verfstatus = 13 or glodbver_result.verfstatus = 14  or glodbver_result.verfstatus = 26)");
 

		$glodbver = $this->db->get()->row();

		record_db_error($this->db->last_query());


       return $glodbver;
         
	}
	public function status_count_pcc_menu($where_array)
	{

		$this->db->select("COUNT(pcc.id) as total");

		$this->db->from('pcc');

		$this->db->join("pcc_result",'pcc_result.pcc_id = pcc.id');

		$this->db->where($where_array);

		$this->db->where("(pcc_result.verfstatus = 11 or pcc_result.verfstatus = 12 or pcc_result.verfstatus = 13 or pcc_result.verfstatus = 14  or pcc_result.verfstatus = 26)");
 

		$pcc  = $this->db->get()->row();

		record_db_error($this->db->last_query());

	
       return $pcc;
         
	}
	public function status_count_identity_menu($where_array)
	{

		$this->db->select("COUNT(identity.id) as total");

		$this->db->from('identity');

		$this->db->join("identity_result",'identity_result.identity_id = identity.id');

	    $this->db->where($where_array);

		$this->db->where("(identity_result.verfstatus = 11 or identity_result.verfstatus = 12 or identity_result.verfstatus = 13 or identity_result.verfstatus = 14  or identity_result.verfstatus = 26)");
 
		$identity  = $this->db->get()->row();

		record_db_error($this->db->last_query());


       return $identity;
         
	}
	public function status_count_drugs_menu($where_array)
	{

		$this->db->select("COUNT(drug_narcotis.id) as total");

		$this->db->from('drug_narcotis');

		$this->db->join("drug_narcotis_result",'drug_narcotis_result.drug_narcotis_id = drug_narcotis.id');

		$this->db->where($where_array);

		$this->db->where("(drug_narcotis_result.verfstatus = 11 or drug_narcotis_result.verfstatus = 12 or drug_narcotis_result.verfstatus = 13 or drug_narcotis_result.verfstatus = 14  or drug_narcotis_result.verfstatus = 26)");
 

		$drug_narcotis = $this->db->get()->row();

		record_db_error($this->db->last_query());

       return $drug_narcotis;
         
	}
	public function status_count_credit_report_menu($where_array)
	{

		$this->db->select("COUNT(credit_report.id) as total");

		$this->db->from('credit_report');

		$this->db->join("credit_report_result",'credit_report_result.credit_report_id = credit_report.id');

		$this->db->where($where_array);

		$this->db->where("(credit_report_result.verfstatus = 11 or credit_report_result.verfstatus = 12 or credit_report_result.verfstatus = 13 or credit_report_result.verfstatus = 14  or credit_report_result.verfstatus = 26)");
 

		$credit_report = $this->db->get()->row();

		record_db_error($this->db->last_query());

       return $credit_report;
         
	}

    public function status_count_address_menu_approve()
	{

		$this->db->select("COUNT(addrver.id) as total");

		
		$this->db->from('address_vendor_log');

		$this->db->join('addrver','addrver.id = address_vendor_log.case_id');

		$this->db->join("addrverres",'addrverres.addrverid = addrver.id','left');

		$this->db->where('address_vendor_log.status',0);

        $this->db->where('(addrverres.var_filter_status = "wip" or addrverres.var_filter_status = "WIP")');
 

		$addrver = $this->db->get()->row();

		record_db_error($this->db->last_query());

        return $addrver;
         
	}

	public function status_count_employment_menu_approve()
	{

		$this->db->select("COUNT(empver.id) as total");

		
		$this->db->from('employment_vendor_log');

		$this->db->join('empver','empver.id = employment_vendor_log.case_id');

		$this->db->join("empverres",'empverres.empverid = empver.id','left');

		$this->db->where('employment_vendor_log.status',0);

        $this->db->where('(empverres.var_filter_status = "wip" or empverres.var_filter_status = "WIP")');
 

		$empver = $this->db->get()->row();

		record_db_error($this->db->last_query());

        return $empver;
         
	}

	public function status_count_education_menu_approve()
	{

		$this->db->select("COUNT(education.id) as total");


		$this->db->from('education_vendor_log');

		$this->db->join('education','education.id = education_vendor_log.case_id');

		$this->db->join("education_result",'education_result.education_id = education.id','left');

		$this->db->where('education_vendor_log.status',0);

        $this->db->where('(education_result.var_filter_status = "wip" or education_result.var_filter_status = "WIP")');
 

		$education = $this->db->get()->row();

		record_db_error($this->db->last_query());

		
        return $education;
         
	}

	public function status_count_court_menu_approve()
	{

		$this->db->select("COUNT(courtver.id) as total");

		
		$this->db->from('courtver_vendor_log');

		$this->db->join('courtver','courtver.id = courtver_vendor_log.case_id');

		$this->db->join("courtver_result",'courtver_result.courtver_id = courtver.id','left');

		$this->db->where('courtver_vendor_log.status',0);

        $this->db->where('(courtver_result.var_filter_status = "wip" or courtver_result.var_filter_status = "WIP")');
 
		$court = $this->db->get()->row();

		record_db_error($this->db->last_query());


        return $court;
         
	}
	public function status_count_global_database_menu_approve()
	{

		$this->db->select("COUNT(glodbver.id) as total");

        
        $this->db->from('glodbver_vendor_log');

		$this->db->join('glodbver','glodbver.id = glodbver_vendor_log.case_id');

		$this->db->join("glodbver_result",'glodbver_result.glodbver_id = glodbver.id','left');

		$this->db->where('glodbver_vendor_log.status',0);

        $this->db->where('(glodbver_result.var_filter_status = "wip" or glodbver_result.var_filter_status = "WIP")'); 

		$glodbver = $this->db->get()->row();

		record_db_error($this->db->last_query());


       return $glodbver;
         
	}
	public function status_count_pcc_menu_approve()
	{

		$this->db->select("COUNT(pcc.id) as total");


		$this->db->from('pcc_vendor_log');

		$this->db->join('pcc','pcc.id = pcc_vendor_log.case_id');

		$this->db->join("pcc_result",'pcc_result.pcc_id = pcc.id','left');

		$this->db->where('pcc_vendor_log.status',0);

        $this->db->where('(pcc_result.var_filter_status = "wip" or pcc_result.var_filter_status = "WIP")');
 

		$pcc  = $this->db->get()->row();

		record_db_error($this->db->last_query());

	
       return $pcc;
         
	}
	public function status_count_identity_menu_approve()
	{

		$this->db->select("COUNT(identity.id) as total");


	    $this->db->from('identity_vendor_log');

		$this->db->join('identity','identity.id = identity_vendor_log.case_id');

		$this->db->join("identity_result",'identity_result.identity_id = identity.id','left');

		$this->db->where('identity_vendor_log.status',0);

        $this->db->where('(identity_result.var_filter_status = "wip" or identity_result.var_filter_status = "WIP")');

 
		$identity  = $this->db->get()->row();

		record_db_error($this->db->last_query());


       return $identity;
         
	}
	public function status_count_drugs_menu_approve()
	{

		$this->db->select("COUNT(drug_narcotis.id) as total");


		$this->db->from('drug_narcotis_vendor_log');

		$this->db->join('drug_narcotis','drug_narcotis.id = drug_narcotis_vendor_log.case_id');

		$this->db->join("drug_narcotis_result",'drug_narcotis_result.drug_narcotis_id = drug_narcotis.id','left');

		$this->db->where('drug_narcotis_vendor_log.status',0);

        $this->db->where('(drug_narcotis_result.var_filter_status = "wip" or drug_narcotis_result.var_filter_status = "WIP")');
 

		$drug_narcotis = $this->db->get()->row();

		record_db_error($this->db->last_query());

       return $drug_narcotis;
         
	}
	public function status_count_credit_report_menu_approve()
	{

		$this->db->select("COUNT(credit_report.id) as total");


	    $this->db->from('credit_report_vendor_log');

		$this->db->join('credit_report','credit_report.id = credit_report_vendor_log.case_id');

		$this->db->join("credit_report_result",'credit_report_result.credit_report_id = credit_report.id','left');

		$this->db->where('credit_report_vendor_log.status',0);

        $this->db->where('(credit_report_result.var_filter_status = "wip" or credit_report_result.var_filter_status = "WIP")');
 

		$credit_report = $this->db->get()->row();

		record_db_error($this->db->last_query());

       return $credit_report;
         
	}

	 public function status_count_address_menu_assign()
	{

		$this->db->select("COUNT(addrver.id) as total");

		$this->db->from('addrver');

		$this->db->join("addrverres",'addrverres.addrverid = addrver.id','left');

        $this->db->where('addrver.vendor_id =',0);

		$this->db->where('(addrverres.var_filter_status = "wip" or addrverres.var_filter_status = "WIP")');

		$addrver = $this->db->get()->row();

		record_db_error($this->db->last_query());

        return $addrver;
         
	}

	public function status_count_employment_menu_assign()
	{

		$this->db->select("COUNT(empver.id) as total");

		$this->db->from('empver');

		$this->db->join("empverres",'empverres.empverid = empver.id','left');

		$this->db->where('empver.field_visit_status =','wip');

		$this->db->where('empver.vendor_id =',0);

        $this->db->where('(empverres.var_filter_status = "wip" or empverres.var_filter_status = "WIP")');
 
		$empver = $this->db->get()->row();

		record_db_error($this->db->last_query());

        return $empver;
         
	}

	public function status_count_education_menu_assign()
	{

		$this->db->select("COUNT(education.id) as total");

		$this->db->from('education');

		$this->db->join("education_result",'education_result.education_id = education.id','left');

	    $this->db->where('education.vendor_id =',0);

	 

        $this->db->where('(education_result.var_filter_status = "wip" or education_result.var_filter_status = "WIP")');
 

		$education = $this->db->get()->row();

		record_db_error($this->db->last_query());

		
        return $education;
         
	}

	public function status_count_court_menu_assign()
	{

		$this->db->select("COUNT(courtver.id) as total");
		
		$this->db->from('courtver');

	    $this->db->join("courtver_result",'courtver_result.courtver_id = courtver.id','left');

		$this->db->where('courtver.vendor_id =',0);

        $this->db->where('(courtver_result.var_filter_status = "wip" or courtver_result.var_filter_status = "WIP")');
 
		$court = $this->db->get()->row();

		record_db_error($this->db->last_query());


        return $court;
         
	}
	public function status_count_global_database_menu_assign()
	{

		$this->db->select("COUNT(glodbver.id) as total");

        
        $this->db->from('glodbver');

        $this->db->join("glodbver_result",'glodbver_result.glodbver_id = glodbver.id','left');


		$this->db->where('glodbver.vendor_id =',0);

	  
        $this->db->where('(glodbver_result.var_filter_status = "wip" or glodbver_result.var_filter_status = "WIP")'); 

		$glodbver = $this->db->get()->row();

		record_db_error($this->db->last_query());


       return $glodbver;
         
	}
	public function status_count_pcc_menu_assign()
	{

		$this->db->select("COUNT(pcc.id) as total");


		$this->db->from('pcc');

		$this->db->join("pcc_result",'pcc_result.pcc_id = pcc.id','left');

		$this->db->where('pcc.vendor_id =',0);

	 
        $this->db->where('(pcc_result.var_filter_status = "wip" or pcc_result.var_filter_status = "WIP")');
 

		$pcc  = $this->db->get()->row();

		record_db_error($this->db->last_query());

	
       return $pcc;
         
	}
	public function status_count_identity_menu_assign()
	{

		$this->db->select("COUNT(identity.id) as total");


	    $this->db->from('identity');

	    $this->db->join("identity_result",'identity_result.identity_id = identity.id','left');

		$this->db->where('identity.vendor_id =',0);

        $this->db->where('(identity_result.var_filter_status = "wip" or identity_result.var_filter_status = "WIP")');

 
		$identity  = $this->db->get()->row();

		record_db_error($this->db->last_query());


       return $identity;
         
	}
	public function status_count_drugs_menu_assign()
	{

		$this->db->select("COUNT(drug_narcotis.id) as total");

		$this->db->from('drug_narcotis');

	    $this->db->join("drug_narcotis_result",'drug_narcotis_result.drug_narcotis_id = drug_narcotis.id','left');

		$this->db->where('drug_narcotis.vendor_id =',0);

	    $this->db->where('(drug_narcotis_result.var_filter_status = "wip" or drug_narcotis_result.var_filter_status = "WIP")');
 

		$drug_narcotis = $this->db->get()->row();

		record_db_error($this->db->last_query());

       return $drug_narcotis;
         
	}
	public function status_count_credit_report_menu_assign()
	{

		$this->db->select("COUNT(credit_report.id) as total");

	    $this->db->from('credit_report');

	    $this->db->join("credit_report_result",'credit_report_result.credit_report_id = credit_report.id','left');

		$this->db->where('credit_report.vendor_id =',0);

        $this->db->where('(credit_report_result.var_filter_status = "wip" or credit_report_result.var_filter_status = "WIP")');
 

		$credit_report = $this->db->get()->row();

		record_db_error($this->db->last_query());

       return $credit_report;
         
	}

	public function get_all_client_name($where)
	{
	 
	    $this->db->select("clients.id,clients.clientname,(select user_name from user_profile where user_profile.id = clients.clientmgr) as spocname,sales_manager");

		$this->db->from('clients');
	
        $this->db->where($where);
	
		$result = $this->db->get();
	
		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	
	public function get_all_client_details($where)
	{
	 
	    $this->db->select("candidates_info.clientid,year(caserecddate) as year, month(caserecddate) as month,CASE  WHEN day(caserecddate) = 1 THEN 'one' WHEN day(caserecddate) = 2 THEN 'two' WHEN day(caserecddate) = 3 THEN 'three' WHEN day(caserecddate) = 4 THEN 'four' WHEN  day(caserecddate) = 5 THEN 'five' WHEN day(caserecddate) = 6 THEN 'six' WHEN day(caserecddate) = 7 THEN 'seven' WHEN day(caserecddate) = 8 THEN 'eight' WHEN day(caserecddate) = 9 THEN 'nine' WHEN day(caserecddate) = 10 THEN 'ten' WHEN day(caserecddate) = 11 THEN 'eleven' WHEN day(caserecddate) = 12 THEN 'twelve' WHEN day(caserecddate) = 13 THEN 'thirteen' WHEN day(caserecddate) = 14 THEN 'fourteen' WHEN day(caserecddate) = 15 THEN 'fifteen' WHEN day(caserecddate) = 16 THEN 'sixteen' WHEN day(caserecddate) = 17 THEN 'seventeen' WHEN day(caserecddate) = 18 THEN 'eightteen' WHEN day(caserecddate) = 19 THEN 'nineteen' WHEN day(caserecddate) = 20 THEN 'twenty' WHEN day(caserecddate) = 21 THEN 'twentyone'  WHEN day(caserecddate) = 22 THEN 'twentytwo' WHEN day(caserecddate) = 23 THEN 'twentythree'  WHEN day(caserecddate) = 24 THEN 'twentyfour' WHEN day(caserecddate) = 25 THEN 'twentyfive' WHEN day(caserecddate) = 26 THEN 'twentysix' WHEN day(caserecddate) = 27 THEN 'twentyseven' WHEN day(caserecddate) = 28 THEN 'twentyeight' WHEN day(caserecddate) = 29 THEN 'twentynine' WHEN day(caserecddate) = 30 THEN 'thirty' WHEN day(caserecddate) = 31 THEN 'thirtyone' END  as day,count(*) as count");

		$this->db->from('candidates_info');
	
        $this->db->where('year(caserecddate)', $where['year']);

        $this->db->where('month(caserecddate)', $where['month']);

        $this->db->group_by('clientid, year(caserecddate), month(caserecddate), day(caserecddate)');
	
		$results = $this->db->get();
	
		record_db_error($this->db->last_query());
  
        $result   =  $results->result_array();

        $return = array();

		foreach ($result as $key => $value) {
			
			$return[$value['day'].'_'.$value['clientid']] = $value['count'];

		}
		

        return $return;

	}

    
}
?>
