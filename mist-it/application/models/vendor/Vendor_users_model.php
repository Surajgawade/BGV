<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vendor_users_model extends CI_Model
{
	function __construct()
    {
		$this->tableName = 'vendor_executive_login';

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

	public function get_vendor_user_details($where_array = array(),$where,$columns)
	{
		$this->db->select('vendor_executive_login.*');

		$this->db->from('vendor_executive_login');

		$this->db->where($where_array);


		if(is_array($where) && $where['search']['value'] != "" )
		{
			$this->db->like('vendor_executive_login.first_name', $where['search']['value']);

			$this->db->or_like('vendor_executive_login.last_name', $where['search']['value']);
            
			$this->db->or_like('vendor_executive_login.mobile_no', $where['search']['value']);

			$this->db->or_like('vendor_executive_login.email_id', $where['search']['value']);
		

		}

		
		$this->db->order_by('vendor_executive_login.id','ASC');
	
		
		$this->db->limit($where['length'],$where['start']);

		$result  = $this->db->get();
		 
		record_db_error($this->db->last_query());
		
		$result_array = $result->result_array();
   
      
        return $result_array;
	}

	public function get_vendor_user_details_count($where_array = array(),$where,$columns)
	{
		$this->db->select('vendor_executive_login.*');

		$this->db->from('vendor_executive_login');

		$this->db->where($where_array);


		if(is_array($where) && $where['search']['value'] != "" )
		{
			$this->db->like('vendor_executive_login.first_name', $where['search']['value']);

			$this->db->or_like('vendor_executive_login.last_name', $where['search']['value']);
            
			$this->db->or_like('vendor_executive_login.mobile_no', $where['search']['value']);

			$this->db->or_like('vendor_executive_login.email_id', $where['search']['value']);
		

		}

		
		$this->db->order_by('vendor_executive_login.id','ASC');
	
	
		$result  = $this->db->get();
		 
		record_db_error($this->db->last_query());
		
		$result_array = $result->result_array();
   
      
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

 

    public function status_count_address($vendor_id,$params)
	{


		$this->db->select("CASE WHEN view_vendor_master_log.final_status =  'wip'  THEN 'WIP' END AS status_value,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('view_vendor_master_log');

		$this->db->join("address_vendor_log",'(address_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "addrver" and view_vendor_master_log.component_tbl_id = 1)');

		$this->db->join("addrver",'addrver.id = address_vendor_log.case_id');


		$this->db->where('addrver.vendor_id',$vendor_id);

		$this->db->group_by('view_vendor_master_log.final_status');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$addrver_wip = $result->result_array();
		
 
		
		$this->db->select("CASE  WHEN view_vendor_master_log.final_status = 'candidate shifted' THEN 'Insufficiency' WHEN view_vendor_master_log.final_status = 'unable to verify' THEN 'Insufficiency'  WHEN view_vendor_master_log.final_status = 'denied verification' THEN 'Insufficiency' WHEN view_vendor_master_log.final_status = 'resigmed' THEN 'Insufficiency' WHEN view_vendor_master_log.final_status = 'candidate not responding' THEN 'Insufficiency' END AS status_value,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('view_vendor_master_log');

		$this->db->join("address_vendor_log",'(address_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "addrver" and view_vendor_master_log.component_tbl_id = 1)');

		$this->db->join("addrver",'addrver.id = address_vendor_log.case_id');
   
 
		$this->db->where('addrver.vendor_id',$vendor_id);

		$this->db->group_by('view_vendor_master_log.final_status');

		$result = $this->db->get();
      
		record_db_error($this->db->last_query());

		$addrver_insuff = $result->result_array();

		$where_condition = "DATE_FORMAT(`view_vendor_master_log`.`modified_on`,'%Y-%m-%d') BETWEEN '".$params['year']."-".$params['month']."-01' AND '".$params['year']."-".$params['month']."-31'";

		$this->db->select("CASE WHEN  view_vendor_master_log.final_status =  'clear' THEN 'Closed' WHEN  view_vendor_master_log.final_status =  'approve' THEN 'Closed'  END AS status_value,COUNT(view_vendor_master_log.final_status) as total");


		$this->db->from('view_vendor_master_log');

		$this->db->join("address_vendor_log",'(address_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "addrver" and view_vendor_master_log.component_tbl_id = 1)');

		$this->db->join("addrver",'addrver.id = address_vendor_log.case_id');
        
		$this->db->where($where_condition);

		$this->db->where('addrver.vendor_id',$vendor_id);

		$this->db->group_by('view_vendor_master_log.final_status');


		$result = $this->db->get();
 
		record_db_error($this->db->last_query());

		$addrver_closed = $result->result_array();


		$results_addrver_wip = array_reduce($addrver_wip, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());

		$results_addrver_insuff = array_reduce($addrver_insuff, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());


		$results_addrver_closed = array_reduce($addrver_closed, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());


		$return = array();

		foreach ($results_addrver_wip as $key => $value) {
			if($key == "WIP")
			{
		     	$return['address_'.strtolower($key)] =  $value;
			}
		}

		foreach ($results_addrver_insuff as $key => $value) {
	        if($key == "Insufficiency")
			{
		    	$return['address_'.strtolower($key)] = $value;
			
			}
		}

		foreach ($results_addrver_closed as $key => $value) {
			if($key == "Closed")
			{
		    	$return['address_'.strtolower($key)] = $value;
			}
		}
		
        return $return;

		/*$this->db->select("CASE WHEN  view_vendor_master_log.final_status =  'clear' THEN 'Closed' WHEN  view_vendor_master_log.final_status =  'approve' THEN 'Closed' WHEN view_vendor_master_log.final_status =  'wip'  THEN 'WIP' WHEN view_vendor_master_log.final_status = 'candidate shifted' THEN 'Insufficiency' WHEN view_vendor_master_log.final_status = 'unable to verify' THEN 'Insufficiency' WHEN view_vendor_master_log.final_status = 'candidate shifted' THEN 'Insufficiency' WHEN view_vendor_master_log.final_status = 'denied verification' THEN 'Insufficiency' WHEN view_vendor_master_log.final_status = 'resigmed' THEN 'Insufficiency' WHEN view_vendor_master_log.final_status = 'candidate not responding' THEN 'Insufficiency' END AS status_value,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('view_vendor_master_log');

		$this->db->join("address_vendor_log",'(address_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "addrver" and view_vendor_master_log.component_tbl_id = 1)');

		$this->db->join("addrver",'addrver.id = address_vendor_log.case_id');

 
		$this->db->where('addrver.vendor_id',$vendor_id);

		$this->db->group_by('view_vendor_master_log.final_status');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$addrver = $result->result_array();


		$results = array_reduce($addrver, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());
  

         
        if(!isset($results['WIP']))
		{
		$results['WIP'] = '0';	
		}
		if(!isset($results['Insufficiency']))
		{
        $results['Insufficiency'] = '0';
        }
        if(!isset($results['Closed']))
		{
        $results['Closed'] = '0';
        }
		
		if(!empty($results))
		{

	        if(array_key_exists('WIP',$results)) {
	            $results['WIP'] =  $results['WIP'];
	        }

	        if(array_key_exists('Insufficiency',$results)) {
	            $results['Insufficiency'] = $results['Insufficiency'];
	        }

	        if(array_key_exists('Closed',$results)) {
	            $results['Closed'] =  $results['Closed'];
	        }
	        
	        $return['total'] =  $results['WIP'] + $results['Insufficiency'] + $results['Closed'];

			foreach ($results as $key => $value) {
				$return[str_replace('/','',str_replace(' ','',$key))] = $value;
			}
			
		}
       return $return;*/
         
	}
    
    public function status_count_employment($vendor_id,$params)
	{

		$this->db->select("CASE  WHEN view_vendor_master_log.final_status =  'wip'  THEN 'WIP'  END AS status_value,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('view_vendor_master_log');

	    $this->db->join("employment_vendor_log",'(employment_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "empver" and view_vendor_master_log.component_tbl_id = 2)');

		$this->db->join("empver",'empver.id = employment_vendor_log.case_id');
   
     
		$this->db->where('empver.vendor_id',$vendor_id);

		$this->db->group_by('view_vendor_master_log.final_status');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$empver_wip = $result->result_array();
		

		$this->db->select("CASE  WHEN view_vendor_master_log.final_status = 'insufficiency' THEN 'Insufficiency'  END AS status_value,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('view_vendor_master_log');

	    $this->db->join("employment_vendor_log",'(employment_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "empver" and view_vendor_master_log.component_tbl_id = 2)');

		$this->db->join("empver",'empver.id = employment_vendor_log.case_id');
   
     
		$this->db->where('empver.vendor_id',$vendor_id);

		$this->db->group_by('view_vendor_master_log.final_status');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$empver_insuff = $result->result_array();



		$where_condition = "DATE_FORMAT(`view_vendor_master_log`.`modified_on`,'%Y-%m-%d') BETWEEN '".$params['year']."-".$params['month']."-01' AND '".$params['year']."-".$params['month']."-31'";


		$this->db->select("CASE WHEN  view_vendor_master_log.final_status =  'closed' THEN 'Closed' WHEN  view_vendor_master_log.final_status =  'approve' THEN 'Closed'  END AS status_value,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('view_vendor_master_log');

	    $this->db->join("employment_vendor_log",'(employment_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "empver" and view_vendor_master_log.component_tbl_id = 2)');

		$this->db->join("empver",'empver.id = employment_vendor_log.case_id');
         
		$this->db->where($where_condition);
     
		$this->db->where('empver.vendor_id',$vendor_id);

		$this->db->group_by('view_vendor_master_log.final_status');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$empver_closed = $result->result_array();

		$results_empver_wip = array_reduce($empver_wip, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());

		$results_empver_insuff = array_reduce($empver_insuff, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());


		$results_empver_closed = array_reduce($empver_closed, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());



		$return = array();

		foreach ($results_empver_wip as $key => $value) {
			if($key == "WIP")
			{
			   $return['employment_'.strtolower($key)] = $value;
			}

		}

		foreach ($results_empver_insuff as $key => $value) {
			if($key == "Insufficiency")
			{
		    	$return['employment_'.strtolower($key)] = $value;
			}
		}

		foreach ($results_empver_closed as $key => $value) {
			if($key == "Closed")
			{ 
			   $return['employment_'.strtolower($key)] = $value;
			}

		}
		
        return $return;


 
		/*$this->db->select("CASE WHEN  view_vendor_master_log.final_status =  'closed' THEN 'Closed' WHEN  view_vendor_master_log.final_status =  'approve' THEN 'Closed' WHEN view_vendor_master_log.final_status =  'wip'  THEN 'WIP' WHEN view_vendor_master_log.final_status = 'insufficiency' THEN 'Insufficiency'  END AS status_value,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('view_vendor_master_log');

	    $this->db->join("employment_vendor_log",'(employment_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "empver" and view_vendor_master_log.component_tbl_id = 2)');

		$this->db->join("empver",'empver.id = employment_vendor_log.case_id');

 
		$this->db->where('empver.vendor_id',$vendor_id);

		$this->db->group_by('view_vendor_master_log.final_status');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$empver = $result->result_array();


		$results = array_reduce($empver, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());
  

         
       
		if(!isset($results['WIP']))
		{
		$results['WIP'] = '0';	
		}
		if(!isset($results['Insufficiency']))
		{
        $results['Insufficiency'] = '0';
        }
        if(!isset($results['Closed']))
		{
        $results['Closed'] = '0';
        } 
		
		if(!empty($results))
		{

	        if(array_key_exists('WIP',$results)) {
	            $results['WIP'] =  $results['WIP'];
	        }

	        if(array_key_exists('Insufficiency',$results)) {
	            $results['Insufficiency'] = $results['Insufficiency'];
	        }

	        if(array_key_exists('Closed',$results)) {
	            $results['Closed'] =  $results['Closed'];
	        }

	        $return['total'] =  $results['WIP'] + $results['Insufficiency'] + $results['Closed'];
	        
			foreach ($results as $key => $value) {
				$return[str_replace('/','',str_replace(' ','',$key))] = $value;
			}
			
		}
       return $return;*/
         
	} 

	public function status_count_education($vendor_id,$params)
	{
        
		$this->db->select("CASE WHEN view_vendor_master_log.final_status =  'wip'  THEN 'WIP'   END AS status_value,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('view_vendor_master_log');

		$this->db->join("education_vendor_log",'(education_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "eduver" and view_vendor_master_log.component_tbl_id = 3)');

		$this->db->join("education",'education.id = education_vendor_log.case_id');
 
		$this->db->where('education.vendor_id',$vendor_id);

		$this->db->group_by('view_vendor_master_log.final_status');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$education_wip = $result->result_array();

		 
		$this->db->select("CASE  WHEN view_vendor_master_log.final_status = 'insufficiency' THEN 'Insufficiency'  END AS status_value,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('view_vendor_master_log');

		$this->db->join("education_vendor_log",'(education_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "eduver" and view_vendor_master_log.component_tbl_id = 3)');

		$this->db->join("education",'education.id = education_vendor_log.case_id');
 
		$this->db->where('education.vendor_id',$vendor_id);

		$this->db->group_by('view_vendor_master_log.final_status');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$education_insuff = $result->result_array();


		$where_condition = "DATE_FORMAT(`view_vendor_master_log`.`modified_on`,'%Y-%m-%d') BETWEEN '".$params['year']."-".$params['month']."-01' AND '".$params['year']."-".$params['month']."-31'";

		$this->db->select("CASE WHEN  view_vendor_master_log.final_status =  'closed' THEN 'Closed' WHEN  view_vendor_master_log.final_status =  'approve' THEN 'Closed'  END AS status_value,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('view_vendor_master_log');

		$this->db->join("education_vendor_log",'(education_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "eduver" and view_vendor_master_log.component_tbl_id = 3)');

		$this->db->join("education",'education.id = education_vendor_log.case_id');

		$this->db->where($where_condition);
 
		$this->db->where('education.vendor_id',$vendor_id);

		$this->db->group_by('view_vendor_master_log.final_status');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$education_closed = $result->result_array();

		
		$results_education_wip = array_reduce($education_wip, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());

		$results_education_insuff = array_reduce($education_insuff, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());


		$results_education_closed = array_reduce($education_closed, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());


		$return = array();

		foreach ($results_education_wip as $key => $value) {
			if($key == "WIP")
			{
			   $return['education_'.strtolower($key)] = $value;
			}
		}

		foreach ($results_education_insuff as $key => $value) {
			if($key == "Insufficiency")
			{
			   $return['education_'.strtolower($key)] = $value;
			}

		}

		foreach ($results_education_closed as $key => $value) {
			if($key == "Closed")
			{	
			    $return['education_'.strtolower($key)] = $value;
			}
		}
		
        return $return;

		
		/*$this->db->select("CASE WHEN  view_vendor_master_log.final_status =  'closed' THEN 'Closed' WHEN  view_vendor_master_log.final_status =  'approve' THEN 'Closed' WHEN view_vendor_master_log.final_status =  'wip'  THEN 'WIP' WHEN view_vendor_master_log.final_status = 'insufficiency' THEN 'Insufficiency'  END AS status_value,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('view_vendor_master_log');

		$this->db->join("education_vendor_log",'(education_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "eduver" and view_vendor_master_log.component_tbl_id = 3)');

		$this->db->join("education",'education.id = education_vendor_log.case_id');

		$this->db->where('year(view_vendor_master_log.created_on)', $where['year']);

        $this->db->where('month(view_vendor_master_log.created_on)', $where['month']);
 
		$this->db->where('education.vendor_id',$vendor_id);

		$this->db->group_by('view_vendor_master_log.final_status');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$education = $result->result_array();


		$results = array_reduce($education, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());
  
		
		if(!isset($results['WIP']))
		{
		$results['WIP'] = '0';	
		}
		if(!isset($results['Insufficiency']))
		{
        $results['Insufficiency'] = '0';
        }
        if(!isset($results['Closed']))
		{
        $results['Closed'] = '0';
        }
		
		if(!empty($results))
		{

	        if(array_key_exists('WIP',$results)) {
	            $results['WIP'] =  $results['WIP'];
	        }

	        if(array_key_exists('Insufficiency',$results)) {
	            $results['Insufficiency'] = $results['Insufficiency'];
	        }

	        if(array_key_exists('Closed',$results)) {
	            $results['Closed'] =  $results['Closed'];
	        }
	        
	        $return['total'] = $results['WIP'] + $results['Insufficiency'] + $results['Closed'];

			foreach ($results as $key => $value) {
				$return[str_replace('/','',str_replace(' ','',$key))] = $value;
			}
			
		}
       return $return;*/
         
	} 

	public function status_count_reference($vendor_id,$params)
	{

		$this->db->select("CASE WHEN view_vendor_master_log.final_status =  'wip'  THEN 'WIP'  END AS status_value,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('view_vendor_master_log');

		$this->db->join("reference_vendor_log",'(reference_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "refver" and view_vendor_master_log.component_tbl_id = 4)');

		$this->db->join("reference",'reference.id = reference_vendor_log.case_id');

		$this->db->where('reference.vendor_id',$vendor_id);

		$this->db->group_by('view_vendor_master_log.final_status');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$reference_wip = $result->result_array();


		$this->db->select("CASE  WHEN view_vendor_master_log.final_status = 'insufficiency' THEN 'Insufficiency'  END AS status_value,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('view_vendor_master_log');

		$this->db->join("reference_vendor_log",'(reference_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "refver" and view_vendor_master_log.component_tbl_id = 4)');

		$this->db->join("reference",'reference.id = reference_vendor_log.case_id');

		$this->db->where('reference.vendor_id',$vendor_id);

		$this->db->group_by('view_vendor_master_log.final_status');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$reference_insuff = $result->result_array();
        

		$where_condition = "DATE_FORMAT(`view_vendor_master_log`.`modified_on`,'%Y-%m-%d') BETWEEN '".$params['year']."-".$params['month']."-01' AND '".$params['year']."-".$params['month']."-31'";

		$this->db->select("CASE WHEN  view_vendor_master_log.final_status =  'closed' THEN 'Closed' WHEN  view_vendor_master_log.final_status =  'approve' THEN 'Closed'  END AS status_value,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('view_vendor_master_log');

		$this->db->join("reference_vendor_log",'(reference_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "refver" and view_vendor_master_log.component_tbl_id = 4)');

		$this->db->join("reference",'reference.id = reference_vendor_log.case_id');

		$this->db->where($where_condition);

		$this->db->where('reference.vendor_id',$vendor_id);

		$this->db->group_by('view_vendor_master_log.final_status');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$reference_closed = $result->result_array();

		$results_reference_wip = array_reduce($reference_wip, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());

		$results_reference_insuff = array_reduce($reference_insuff, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());


		$results_reference_closed = array_reduce($reference_closed, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());

		$return = array();

		foreach ($results_reference_wip as $key => $value) {
			if($key == "WIP")
			{	
		    	$return['reference_'.strtolower($key)] = $value;
			}

		}

		foreach ($results_reference_insuff as $key => $value) {
			if($key == "Insufficiency")
			{	
			   $return['reference_'.strtolower($key)] = $value;
			}
 
		}

		foreach ($results_reference_closed as $key => $value) {
			if($key == "Closed")
			{	
			    $return['reference_'.strtolower($key)] = $value;
			}

		}
		
        return $return;


	/*	$this->db->select("CASE WHEN  view_vendor_master_log.final_status =  'closed' THEN 'Closed' WHEN  view_vendor_master_log.final_status =  'approve' THEN 'Closed' WHEN view_vendor_master_log.final_status =  'wip'  THEN 'WIP' WHEN view_vendor_master_log.final_status = 'insufficiency' THEN 'Insufficiency'  END AS status_value,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('view_vendor_master_log');

		$this->db->join("reference_vendor_log",'(reference_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "refver" and view_vendor_master_log.component_tbl_id = 4)');

		$this->db->join("reference",'reference.id = reference_vendor_log.case_id');

		$this->db->where('year(view_vendor_master_log.created_on)', $where['year']);

        $this->db->where('month(view_vendor_master_log.created_on)', $where['month']);
 
		$this->db->where('reference.vendor_id',$vendor_id);

		$this->db->group_by('view_vendor_master_log.final_status');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$reference = $result->result_array();


		$results = array_reduce($reference, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());
  
		
		if(!isset($results['WIP']))
		{
		$results['WIP'] = '0';	
		}
		if(!isset($results['Insufficiency']))
		{
        $results['Insufficiency'] = '0';
        }
        if(!isset($results['Closed']))
		{
        $results['Closed'] = '0';
        } 
		
		if(!empty($results))
		{

	        if(array_key_exists('WIP',$results)) {
	            $results['WIP'] =  $results['WIP'];
	        }

	        if(array_key_exists('Insufficiency',$results)) {
	            $results['Insufficiency'] = $results['Insufficiency'];
	        }

	        if(array_key_exists('Closed',$results)) {
	            $results['Closed'] =  $results['Closed'];
	        }
	        

	        $return['total'] = $results['WIP'] + $results['Insufficiency'] + $results['Closed'];
	        
			foreach ($results as $key => $value) {
				$return[str_replace('/','',str_replace(' ','',$key))] = $value;
			}
			
		}
       return $return;*/
         
	} 

	public function status_count_court($vendor_id,$params)
	{

		$this->db->select("CASE  WHEN view_vendor_master_log.final_status =  'wip'  THEN 'WIP'  END AS status_value,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('view_vendor_master_log');

		$this->db->join("courtver_vendor_log",'(courtver_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "courtver" and view_vendor_master_log.component_tbl_id = 5)');

		$this->db->join("courtver",'courtver.id = courtver_vendor_log.case_id');

	
		$this->db->where('courtver.vendor_id',$vendor_id);

		$this->db->group_by('view_vendor_master_log.final_status');

		$result = $this->db->get();

		record_db_error($this->db->last_query());
 
		$court_wip = $result->result_array();

		$this->db->select("CASE WHEN view_vendor_master_log.final_status = 'insufficiency' THEN 'Insufficiency'  END AS status_value,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('view_vendor_master_log');

		$this->db->join("courtver_vendor_log",'(courtver_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "courtver" and view_vendor_master_log.component_tbl_id = 5)');

		$this->db->join("courtver",'courtver.id = courtver_vendor_log.case_id');

	
		$this->db->where('courtver.vendor_id',$vendor_id);

		$this->db->group_by('view_vendor_master_log.final_status');

		$result = $this->db->get();

		record_db_error($this->db->last_query());
 
		$court_insuff = $result->result_array();

		$where_condition = "DATE_FORMAT(`view_vendor_master_log`.`modified_on`,'%Y-%m-%d') BETWEEN '".$params['year']."-".$params['month']."-01' AND '".$params['year']."-".$params['month']."-31'";


		$this->db->select("CASE WHEN  view_vendor_master_log.final_status =  'clear' THEN 'Closed' WHEN  view_vendor_master_log.final_status =  'possible match' THEN 'Closed' WHEN  view_vendor_master_log.final_status =  'approve' THEN 'Closed'  END AS status_value,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('view_vendor_master_log');

		$this->db->join("courtver_vendor_log",'(courtver_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "courtver" and view_vendor_master_log.component_tbl_id = 5)');

		$this->db->join("courtver",'courtver.id = courtver_vendor_log.case_id');

		$this->db->where($where_condition);

		$this->db->where('courtver.vendor_id',$vendor_id);

		$this->db->group_by('view_vendor_master_log.final_status');

		$result = $this->db->get();

		record_db_error($this->db->last_query());
 
		$court_closed = $result->result_array();

		$results_court_wip = array_reduce($court_wip, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());

		$results_court_insuff = array_reduce($court_insuff, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());


		$results_court_closed = array_reduce($court_closed, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());

		$return = array();

		foreach ($results_court_wip as $key => $value) {

			if($key == "WIP")
			{	
			
			   $return['court_'.strtolower($key)] = $value;
			}

		}

		foreach ($results_court_insuff as $key => $value) {
			
			if($key == "Insufficiency")
			{	
			   $return['court_'.strtolower($key)] = $value;
			}

		}

		foreach ($results_court_closed as $key => $value) {
			
			if($key == "Closed")
			{	
			   $return['court_'.strtolower($key)] = $value;
			}

		}

 
		return $return;
 
	/*	$this->db->select("CASE WHEN  view_vendor_master_log.final_status =  'clear' THEN 'Closed' WHEN  view_vendor_master_log.final_status =  'possible match' THEN 'Closed' WHEN  view_vendor_master_log.final_status =  'approve' THEN 'Closed' WHEN view_vendor_master_log.final_status =  'wip'  THEN 'WIP' WHEN view_vendor_master_log.final_status = 'insufficiency' THEN 'Insufficiency'  END AS status_value,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('view_vendor_master_log');

		$this->db->join("courtver_vendor_log",'(courtver_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "courtver" and view_vendor_master_log.component_tbl_id = 5)');

		$this->db->join("courtver",'courtver.id = courtver_vendor_log.case_id');

		$this->db->where('year(view_vendor_master_log.created_on)', $where['year']);

        $this->db->where('month(view_vendor_master_log.created_on)', $where['month']);
 
		$this->db->where('courtver.vendor_id',$vendor_id);

		$this->db->group_by('view_vendor_master_log.final_status');

		$result = $this->db->get();

		record_db_error($this->db->last_query());
 
		$court = $result->result_array();


		$results = array_reduce($court, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());
  
		
		if(!isset($results['WIP']))
		{
		$results['WIP'] = '0';	
		}
		if(!isset($results['Insufficiency']))
		{
        $results['Insufficiency'] = '0';
        }
        if(!isset($results['Closed']))
		{
        $results['Closed'] = '0';
        } 
		
		if(!empty($results))
		{

	        if(array_key_exists('WIP',$results)) {
	            $results['WIP'] = $results['WIP'];
	        }

	        if(array_key_exists('Insufficiency',$results)) {
	            $results['Insufficiency'] = $results['Insufficiency'];
	        }

	        if(array_key_exists('Closed',$results)) {
	            $results['Closed'] =  $results['Closed'];
	        }

	        $return['total'] = $results['WIP'] + $results['Insufficiency'] + $results['Closed'];
		
	        
			foreach ($results as $key => $value) {
				$return[str_replace('/','',str_replace(' ','',$key))] = $value;
			}
			
		}
       return $return;*/
         
	} 

    public function status_count_global_database($vendor_id,$params)
	{

		$this->db->select("CASE  WHEN view_vendor_master_log.final_status =  'wip'  THEN 'WIP'  END AS status_value,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('view_vendor_master_log');

		$this->db->join("glodbver_vendor_log",'(glodbver_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "globdbver" and view_vendor_master_log.component_tbl_id = 6)');

		$this->db->join("glodbver",'glodbver.id = glodbver_vendor_log.case_id');

 
		$this->db->where('glodbver.vendor_id',$vendor_id);

		$this->db->group_by('view_vendor_master_log.final_status');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$global_database_wip = $result->result_array();


		$this->db->select("CASE WHEN view_vendor_master_log.final_status = 'insufficiency' THEN 'Insufficiency'  END AS status_value,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('view_vendor_master_log');

		$this->db->join("glodbver_vendor_log",'(glodbver_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "globdbver" and view_vendor_master_log.component_tbl_id = 6)');

		$this->db->join("glodbver",'glodbver.id = glodbver_vendor_log.case_id');

 
		$this->db->where('glodbver.vendor_id',$vendor_id);

		$this->db->group_by('view_vendor_master_log.final_status');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$global_database_insuff = $result->result_array();

       
		$where_condition = "DATE_FORMAT(`view_vendor_master_log`.`modified_on`,'%Y-%m-%d') BETWEEN '".$params['year']."-".$params['month']."-01' AND '".$params['year']."-".$params['month']."-31'";

		
		$this->db->select("CASE WHEN  view_vendor_master_log.final_status =  'clear' THEN 'Closed' WHEN  view_vendor_master_log.final_status =  'possible match' THEN 'Closed'  END AS status_value,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('view_vendor_master_log');

		$this->db->join("glodbver_vendor_log",'(glodbver_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "globdbver" and view_vendor_master_log.component_tbl_id = 6)');

		$this->db->join("glodbver",'glodbver.id = glodbver_vendor_log.case_id');

		$this->db->where($where_condition);
 
		$this->db->where('glodbver.vendor_id',$vendor_id);

		$this->db->group_by('view_vendor_master_log.final_status');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$global_database_closed = $result->result_array();

		$results_global_database_wip = array_reduce($global_database_wip, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());

		$results_global_database_insuff = array_reduce($global_database_insuff, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());


		$results_global_database_closed = array_reduce($global_database_closed, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());

		
		$return = array();

		foreach ($results_global_database_wip as $key => $value) {

			if($key == "WIP")
			{
			
			    $return['global_database_'.strtolower($key)] = $value;
			}

		}

		foreach ($results_global_database_insuff as $key => $value) {

			if($key == "Insufficiency")
			{
			
		     	$return['global_database_'.strtolower($key)] = $value;
			}

		}

		foreach ($results_global_database_closed as $key => $value) {

			
			if($key == "Closed")
			{
			
			$return['global_database_'.strtolower($key)] = $value;

			}

		}

 
		return $return;
 

		/*$this->db->select("CASE WHEN  view_vendor_master_log.final_status =  'clear' THEN 'Closed' WHEN  view_vendor_master_log.final_status =  'possible match' THEN 'Closed' WHEN view_vendor_master_log.final_status =  'wip'  THEN 'WIP' WHEN view_vendor_master_log.final_status = 'insufficiency' THEN 'Insufficiency'  END AS status_value,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('view_vendor_master_log');

		$this->db->join("glodbver_vendor_log",'(glodbver_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "globdbver" and view_vendor_master_log.component_tbl_id = 6)');

		$this->db->join("glodbver",'glodbver.id = glodbver_vendor_log.case_id');

		$this->db->where('year(view_vendor_master_log.created_on)', $where['year']);

        $this->db->where('month(view_vendor_master_log.created_on)', $where['month']);
 
		$this->db->where('glodbver.vendor_id',$vendor_id);

		$this->db->group_by('view_vendor_master_log.final_status');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$global_database = $result->result_array();


		$results = array_reduce($global_database, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());
  		
			
		if(!isset($results['WIP']))
		{
		$results['WIP'] = '0';	
		}
		if(!isset($results['Insufficiency']))
		{
        $results['Insufficiency'] = '0';
        }
        if(!isset($results['Closed']))
		{
        $results['Closed'] = '0';
        } 
		
		if(!empty($results))
		{

	        if(array_key_exists('WIP',$results)) {
	            $results['WIP'] =  $results['WIP'];
	        }

	        if(array_key_exists('Insufficiency',$results)) {
	            $results['Insufficiency'] =  $results['Insufficiency'];
	        }

	        if(array_key_exists('Closed',$results)) {
	            $results['Closed'] =  $results['Closed'];
	        }

	        $return['total'] = $results['WIP'] + $results['Insufficiency'] + $results['Closed'];
	        
			foreach ($results as $key => $value) {
				$return[str_replace('/','',str_replace(' ','',$key))] = $value;
			}
			
		}
       return $return;*/
         
	}

    public function status_count_pcc($vendor_id,$params)
	{

		$this->db->select("CASE WHEN view_vendor_master_log.final_status =  'wip'  THEN 'WIP' END AS status_value,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('view_vendor_master_log');


		$this->db->join("pcc_vendor_log",'(pcc_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "crimver" and view_vendor_master_log.component_tbl_id = 8)');

		$this->db->join("pcc",'pcc.id = pcc_vendor_log.case_id');

 
		$this->db->where('pcc.vendor_id',$vendor_id);

		$this->db->group_by('view_vendor_master_log.final_status');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$pcc_wip = $result->result_array();


		$this->db->select("CASE  WHEN view_vendor_master_log.final_status = 'insufficiency' THEN 'Insufficiency'  END AS status_value,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('view_vendor_master_log');


		$this->db->join("pcc_vendor_log",'(pcc_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "crimver" and view_vendor_master_log.component_tbl_id = 8)');

		$this->db->join("pcc",'pcc.id = pcc_vendor_log.case_id');

 
		$this->db->where('pcc.vendor_id',$vendor_id);

		$this->db->group_by('view_vendor_master_log.final_status');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$pcc_insuff = $result->result_array();


		$where_condition = "DATE_FORMAT(`view_vendor_master_log`.`modified_on`,'%Y-%m-%d') BETWEEN '".$params['year']."-".$params['month']."-01' AND '".$params['year']."-".$params['month']."-31'";


		$this->db->select("CASE WHEN  view_vendor_master_log.final_status =  'closed' THEN 'Closed' WHEN  view_vendor_master_log.final_status =  'approve' THEN 'Closed' END AS status_value,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('view_vendor_master_log');


		$this->db->join("pcc_vendor_log",'(pcc_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "crimver" and view_vendor_master_log.component_tbl_id = 8)');

		$this->db->join("pcc",'pcc.id = pcc_vendor_log.case_id');
         
		$this->db->where($where_condition);
 
		$this->db->where('pcc.vendor_id',$vendor_id);

		$this->db->group_by('view_vendor_master_log.final_status');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$pcc_closed = $result->result_array();

		$results_pcc_wip = array_reduce($pcc_wip, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());

		$results_pcc_insuff = array_reduce($pcc_insuff, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());


		$results_pcc_closed = array_reduce($pcc_closed, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());


		$return = array();

		foreach ($results_pcc_wip as $key => $value) {
			if($key == "WIP")
			{
		    	$return['pcc_'.strtolower($key)] = $value;
			}

		}

		foreach ($results_pcc_insuff as $key => $value) {
			if($key == "Insufficiency")
			{
		    	$return['pcc_'.strtolower($key)] = $value;
			}

		}

		foreach ($results_pcc_closed as $key => $value) {
			if($key == "Closed")
			{
			   $return['pcc_'.strtolower($key)] = $value;
			}

		}

 
		return $return;
 		 
/*
	   $this->db->select("CASE WHEN  view_vendor_master_log.final_status =  'closed' THEN 'Closed' WHEN  view_vendor_master_log.final_status =  'approve' THEN 'Closed' WHEN view_vendor_master_log.final_status =  'wip'  THEN 'WIP' WHEN view_vendor_master_log.final_status = 'insufficiency' THEN 'Insufficiency'  END AS status_value,COUNT(view_vendor_master_log.final_status) as total");

	   $this->db->from('view_vendor_master_log');


	   $this->db->join("pcc_vendor_log",'(pcc_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "crimver" and view_vendor_master_log.component_tbl_id = 8)');

	   $this->db->join("pcc",'pcc.id = pcc_vendor_log.case_id');

	   $this->db->where('year(view_vendor_master_log.created_on)', $where['year']);

	   $this->db->where('month(view_vendor_master_log.created_on)', $where['month']);

	   $this->db->where('pcc.vendor_id',$vendor_id);

	   $this->db->group_by('view_vendor_master_log.final_status');

	   $result = $this->db->get();

	   record_db_error($this->db->last_query());

	   $pcc = $result->result_array();


	   $results = array_reduce($pcc, function($result, $item) {
		   if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		   $result[$item['status_value']] += $item['total'];
		   return $result;
	   }, array());
 

	   
	   if(!isset($results['WIP']))
	   {
	   $results['WIP'] = '0';	
	   }
	   if(!isset($results['Insufficiency']))
	   {
	   $results['Insufficiency'] = '0';
	   }
	   if(!isset($results['Closed']))
	   {
	   $results['Closed'] = '0';
	   } 
	   
	   if(!empty($results))
	   {

		   if(array_key_exists('WIP',$results)) {
			   $results['WIP'] =  $results['WIP'];
		   }

		   if(array_key_exists('Insufficiency',$results)) {
			   $results['Insufficiency'] =  $results['Insufficiency'];
		   }

		   if(array_key_exists('Closed',$results)) {
			   $results['Closed'] =  $results['Closed'];
		   }


		   $return['total'] = $results['WIP'] + $results['Insufficiency'] + $results['Closed'];
		   
		   foreach ($results as $key => $value) {
			   $return[str_replace('/','',str_replace(' ','',$key))] = $value;
		   }
		   
	   }
	  return $return;*/
         
	}

	public function status_count_identity($vendor_id,$params)
	{   

		$this->db->select("CASE WHEN view_vendor_master_log.final_status =  'wip'  THEN 'WIP'  END AS status_value,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('view_vendor_master_log');

		$this->db->join("identity_vendor_log",'(identity_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "identity" and view_vendor_master_log.component_tbl_id = 9)');

		$this->db->join("identity",'identity.id = identity_vendor_log.case_id');

		$this->db->where('identity.vendor_id',$vendor_id);

		$this->db->group_by('view_vendor_master_log.final_status');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$identity_wip = $result->result_array();


		$this->db->select("CASE WHEN view_vendor_master_log.final_status = 'insufficiency' THEN 'Insufficiency'  END AS status_value,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('view_vendor_master_log');

		$this->db->join("identity_vendor_log",'(identity_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "identity" and view_vendor_master_log.component_tbl_id = 9)');

		$this->db->join("identity",'identity.id = identity_vendor_log.case_id');

		$this->db->where('identity.vendor_id',$vendor_id);

		$this->db->group_by('view_vendor_master_log.final_status');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$identity_insuff = $result->result_array();


		$where_condition = "DATE_FORMAT(`view_vendor_master_log`.`modified_on`,'%Y-%m-%d') BETWEEN '".$params['year']."-".$params['month']."-01' AND '".$params['year']."-".$params['month']."-31'";


		$this->db->select("CASE WHEN  view_vendor_master_log.final_status =  'closed' THEN 'Closed' WHEN  view_vendor_master_log.final_status =  'approve' THEN 'Closed'  END AS status_value,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('view_vendor_master_log');

		$this->db->join("identity_vendor_log",'(identity_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "identity" and view_vendor_master_log.component_tbl_id = 9)');

		$this->db->join("identity",'identity.id = identity_vendor_log.case_id');

		$this->db->where('identity.vendor_id',$vendor_id);

		$this->db->where($where_condition);		

		$this->db->group_by('view_vendor_master_log.final_status');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$identity_closed = $result->result_array();


		$results_identity_wip = array_reduce($identity_wip, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());

		$results_identity_insuff = array_reduce($identity_insuff, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());


		$results_identity_closed = array_reduce($identity_closed, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());


		
		$return = array();

		foreach ($results_identity_wip as $key => $value) {
			if($key == "WIP")
			{	
		     	$return['identity_'.strtolower($key)] = $value;
			}

		}

		foreach ($results_identity_insuff as $key => $value) {
			if($key == "Insufficiency")
			{
			   $return['identity_'.strtolower($key)] = $value;
			}

		}

		foreach ($results_identity_closed as $key => $value) {

			if($key == "Closed")
			{
			
		    	$return['identity_'.strtolower($key)] = $value;
			}

		}


       return $return;


	/*	$this->db->select("CASE WHEN  view_vendor_master_log.final_status =  'closed' THEN 'Closed' WHEN  view_vendor_master_log.final_status =  'approve' THEN 'Closed' WHEN view_vendor_master_log.final_status =  'wip'  THEN 'WIP' WHEN view_vendor_master_log.final_status = 'insufficiency' THEN 'Insufficiency'  END AS status_value,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('view_vendor_master_log');

		$this->db->join("identity_vendor_log",'(identity_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "identity" and view_vendor_master_log.component_tbl_id = 9)');

		$this->db->join("identity",'identity.id = identity_vendor_log.case_id');

 
		$this->db->where('identity.vendor_id',$vendor_id);

		$this->db->group_by('view_vendor_master_log.final_status');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$identity_wip = $result->result_array();


		$results = array_reduce($identity, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());
  

		
		if(!isset($results['WIP']))
		{
		$results['WIP'] = '0';	
		}
		if(!isset($results['Insufficiency']))
		{
        $results['Insufficiency'] = '0';
        }
        if(!isset($results['Closed']))
		{
        $results['Closed'] = '0';
        } 
		
		if(!empty($results))
		{

	        if(array_key_exists('WIP',$results)) {
	            $results['WIP'] =  $results['WIP'];
	        }

	        if(array_key_exists('Insufficiency',$results)) {
	            $results['Insufficiency'] =  $results['Insufficiency'];
	        }

	        if(array_key_exists('Closed',$results)) {
	            $results['Closed'] =  $results['Closed'];
	        }

            $return['total'] = $results['WIP'] + $results['Insufficiency'] + $results['Closed'];
	        
			foreach ($results as $key => $value) {
				$return[str_replace('/','',str_replace(' ','',$key))] = $value;
			}
			
		}
       return $return;
         */
	}   

	public function status_count_drugs($vendor_id,$params)
	{

		$this->db->select("CASE WHEN view_vendor_master_log.final_status =  'wip'  THEN 'WIP'  END AS status_value,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('view_vendor_master_log');

		$this->db->join("drug_narcotis_vendor_log",'(drug_narcotis_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "narcver" and view_vendor_master_log.component_tbl_id = 7)');


		$this->db->join("drug_narcotis",'drug_narcotis.id = drug_narcotis_vendor_log.case_id');

 
		$this->db->where('drug_narcotis.vendor_id',$vendor_id);

		$this->db->group_by('view_vendor_master_log.final_status');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$drugs_wip = $result->result_array();
    

		$this->db->select("CASE  WHEN view_vendor_master_log.final_status = 'insufficiency' THEN 'Insufficiency'  END AS status_value,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('view_vendor_master_log');

		$this->db->join("drug_narcotis_vendor_log",'(drug_narcotis_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "narcver" and view_vendor_master_log.component_tbl_id = 7)');


		$this->db->join("drug_narcotis",'drug_narcotis.id = drug_narcotis_vendor_log.case_id');

 
		$this->db->where('drug_narcotis.vendor_id',$vendor_id);

		$this->db->group_by('view_vendor_master_log.final_status');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$drugs_insuff = $result->result_array();



		$where_condition = "DATE_FORMAT(`view_vendor_master_log`.`modified_on`,'%Y-%m-%d') BETWEEN '".$params['year']."-".$params['month']."-01' AND '".$params['year']."-".$params['month']."-31'";


		$this->db->select("CASE WHEN view_vendor_master_log.final_status =  'closed' THEN 'Closed' WHEN  view_vendor_master_log.final_status =  'approve' THEN 'Closed'   END AS status_value,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('view_vendor_master_log');

		$this->db->join("drug_narcotis_vendor_log",'(drug_narcotis_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "narcver" and view_vendor_master_log.component_tbl_id = 7)');

		$this->db->join("drug_narcotis",'drug_narcotis.id = drug_narcotis_vendor_log.case_id');

        $this->db->where($where_condition);

		$this->db->where('drug_narcotis.vendor_id',$vendor_id);

		$this->db->group_by('view_vendor_master_log.final_status');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$drugs_closed = $result->result_array();

		$results_drugs_wip = array_reduce($drugs_wip, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());

		$results_drugs_insuff = array_reduce($drugs_insuff, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());


		$results_drugs_closed = array_reduce($drugs_closed, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());

		$return = array();

		foreach ($results_drugs_wip as $key => $value) {
			if($key == "WIP")
			{
			   $return['drugs_'.strtolower($key)] = $value;
			}

		}

		foreach ($results_drugs_insuff as $key => $value) {
			if($key == "Insufficiency")
			{
		    	$return['drugs_'.strtolower($key)] = $value;
			}

		}

		foreach ($results_drugs_closed as $key => $value) {
			if($key == "Closed")
			{	
		    	$return['drugs_'.strtolower($key)] = $value;
			}

		}


       return $return;




	/*	$this->db->select("CASE WHEN  view_vendor_master_log.final_status =  'closed' THEN 'Closed' WHEN  view_vendor_master_log.final_status =  'approve' THEN 'Closed' WHEN view_vendor_master_log.final_status =  'wip'  THEN 'WIP' WHEN view_vendor_master_log.final_status = 'insufficiency' THEN 'Insufficiency'  END AS status_value,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('view_vendor_master_log');

		$this->db->join("drug_narcotis_vendor_log",'(drug_narcotis_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "narcver" and view_vendor_master_log.component_tbl_id = 7)');


		$this->db->join("drug_narcotis",'drug_narcotis.id = drug_narcotis_vendor_log.case_id');

		$this->db->where('year(view_vendor_master_log.created_on)', $where['year']);

        $this->db->where('month(view_vendor_master_log.created_on)', $where['month']);
 
		$this->db->where('drug_narcotis.vendor_id',$vendor_id);

		$this->db->group_by('view_vendor_master_log.final_status');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$drugs = $result->result_array();


		$results = array_reduce($drugs, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());
  

		
		if(!isset($results['WIP']))
		{
		$results['WIP'] = '0';	
		}
		if(!isset($results['Insufficiency']))
		{
        $results['Insufficiency'] = '0';
        }
        if(!isset($results['Closed']))
		{
        $results['Closed'] = '0';
        } 
		
		if(!empty($results))
		{

	        if(array_key_exists('WIP',$results)) {
	            $results['WIP'] = $results['WIP'];
	        }

	        if(array_key_exists('Insufficiency',$results)) {
	            $results['Insufficiency'] =  $results['Insufficiency'];
	        }

	        if(array_key_exists('Closed',$results)) {
	            $results['Closed'] =  $results['Closed'];
	        }

            $return['total'] = $results['WIP'] + $results['Insufficiency'] + $results['Closed'];
	        
			foreach ($results as $key => $value) {
				$return[str_replace('/','',str_replace(' ','',$key))] = $value;
			}
			
		}
       return $return;*/
         
	}

    public function status_count_credit_report($vendor_id,$params)
	{

		$this->db->select("CASE  WHEN view_vendor_master_log.final_status =  'wip'  THEN 'WIP'  END AS status_value,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('view_vendor_master_log');

		$this->db->join("credit_report_vendor_log",'(credit_report_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "cbrver" and view_vendor_master_log.component_tbl_id = 10)');

		$this->db->join("credit_report",'credit_report.id = credit_report_vendor_log.case_id');

 
		$this->db->where('credit_report.vendor_id',$vendor_id);

		$this->db->group_by('view_vendor_master_log.final_status');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$credit_report_wip = $result->result_array();



		$this->db->select("CASE WHEN view_vendor_master_log.final_status = 'insufficiency' THEN 'Insufficiency'  END AS status_value,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('view_vendor_master_log');

		$this->db->join("credit_report_vendor_log",'(credit_report_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "cbrver" and view_vendor_master_log.component_tbl_id = 10)');

		$this->db->join("credit_report",'credit_report.id = credit_report_vendor_log.case_id');

 
		$this->db->where('credit_report.vendor_id',$vendor_id);

		$this->db->group_by('view_vendor_master_log.final_status');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$credit_report_insuff = $result->result_array();


		$where_condition = "DATE_FORMAT(`view_vendor_master_log`.`modified_on`,'%Y-%m-%d') BETWEEN '".$params['year']."-".$params['month']."-01' AND '".$params['year']."-".$params['month']."-31'";


		$this->db->select("CASE WHEN  view_vendor_master_log.final_status =  'closed' THEN 'Closed' WHEN  view_vendor_master_log.final_status =  'approve' THEN 'Closed'  END AS status_value,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('view_vendor_master_log');

		$this->db->join("credit_report_vendor_log",'(credit_report_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "cbrver" and view_vendor_master_log.component_tbl_id = 10)');

		$this->db->join("credit_report",'credit_report.id = credit_report_vendor_log.case_id');

 
		$this->db->where($where_condition);

		$this->db->where('credit_report.vendor_id',$vendor_id);

		$this->db->group_by('view_vendor_master_log.final_status');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$credit_report_closed = $result->result_array();

		
		$results_credit_report_wip = array_reduce($credit_report_wip, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());

		$results_credit_report_insuff = array_reduce($credit_report_insuff, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());


		$results_credit_report_closed = array_reduce($credit_report_closed, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());

		$return = array();

		foreach ($results_credit_report_wip as $key => $value) {
		    if($key == "WIP")
			{	
		    	$return['credit_report_'.strtolower($key)] = $value;
			}

		}

		foreach ($results_credit_report_insuff as $key => $value) {
			if($key == "Insufficiency")
			{
			    $return['credit_report_'.strtolower($key)] = $value;
			}

		}

		foreach ($results_credit_report_closed as $key => $value) {
			if($key == "Closed")
			{
			
		    	$return['credit_report_'.strtolower($key)] = $value;
			}

		}


       return $return;


		
      /*
		$this->db->select("CASE WHEN  view_vendor_master_log.final_status =  'closed' THEN 'Closed' WHEN  view_vendor_master_log.final_status =  'approve' THEN 'Closed' WHEN view_vendor_master_log.final_status =  'wip'  THEN 'WIP' WHEN view_vendor_master_log.final_status = 'insufficiency' THEN 'Insufficiency'  END AS status_value,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('view_vendor_master_log');

		$this->db->join("credit_report_vendor_log",'(credit_report_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "cbrver" and view_vendor_master_log.component_tbl_id = 10)');

		$this->db->join("credit_report",'credit_report.id = credit_report_vendor_log.case_id');

		$this->db->where('year(view_vendor_master_log.created_on)', $where['year']);

        $this->db->where('month(view_vendor_master_log.created_on)', $where['month']);
 
		$this->db->where('credit_report.vendor_id',$vendor_id);

		$this->db->group_by('view_vendor_master_log.final_status');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$credit_report = $result->result_array();


		$results = array_reduce($credit_report, function($result, $item) {
		    if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
		    $result[$item['status_value']] += $item['total'];
		    return $result;
		}, array());
  

         
		if(!isset($results['WIP']))
		{
		$results['WIP'] = '0';	
		}
		if(!isset($results['Insufficiency']))
		{
        $results['Insufficiency'] = '0';
        }
        if(!isset($results['Closed']))
		{
        $results['Closed'] = '0';
        } 
		
		if(!empty($results))
		{

	        if(array_key_exists('WIP',$results)) {
	            $results['WIP'] =  $results['WIP'];
	        }

	        if(array_key_exists('Insufficiency',$results)) {
	            $results['Insufficiency'] =  $results['Insufficiency'];
	        }

	        if(array_key_exists('Closed',$results)) {
	            $results['Closed'] =  $results['Closed'];
	        }

	        $return['total'] = $results['WIP'] + $results['Insufficiency'] + $results['Closed'];
	        
			foreach ($results as $key => $value) {
				$return[str_replace('/','',str_replace(' ','',$key))] = $value;
			}
			
		}
       return $return;*/
         
	}     


  }
?>
