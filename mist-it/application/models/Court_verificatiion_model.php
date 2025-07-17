<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Court_verificatiion_model extends CI_Model
{
	function __construct()
    {
		$this->tableName = 'courtver';

		$this->primaryKey = 'id';
	}

	public function select($return_as_strict_row,$select_array, $where_array = array())
	{
		$this->db->select($select_array);

		$this->db->from($this->tableName);

		$this->db->where($where_array);

		$this->db->order_by('id', 'desc');

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		$result_array = $result->result_array();

        if($return_as_strict_row){

            if(count($result_array) == 1) {
                $result_array  = $result_array[0];
            }
        }
        return $result_array;
	}
   

	public function update_auto_increament_value($arrdata,$arrwhere = array())
	{
        
		   $this->db->where($arrwhere);

			$result = $this->db->update('courtver', $arrdata);

			record_db_error($this->db->last_query());

			return $result;

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

	protected function filter_where_cond($where_arry)
	{
		$where = array();
		if(isset($where_arry['status']) &&  $where_arry['status'] != '')	
		{
			
			if($where_arry['status'] != 'All')
			{
			$where['courtver_result.var_filter_status'] = $where_arry['status'];
		    }
		   

		}

		if(isset($where_arry['sub_status']) && $where_arry['sub_status'] != '' && $where_arry['sub_status'] != 0)	
		{
			$where['courtver_result.verfstatus'] = $where_arry['sub_status'];
		}

		if(isset($where_arry['client_id']) &&  $where_arry['client_id'] != 0)	
		{
			$where['courtver.clientid'] = $where_arry['client_id'];
		}
        if(isset($where_arry['filter_by_executive']) &&  $where_arry['filter_by_executive'] != 0)	
		{
		   if($where_arry['filter_by_executive'] != "All")
	      	{
              $where['courtver.has_case_id'] = $where_arry['filter_by_executive'];
	      	}
	    }

		return $where;

	}

	public function get_all_court_record_datatable($empver_aary = array(),$where,$columns)
	{
		$this->db->select("candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = courtver.has_case_id) as executive_name,status.status_value,courtver.id as courtver_id,courtver.court_com_ref,courtver.street_address,courtver.city,courtver.pincode,courtver.state,courtver_result.verfstatus,courtver_result.first_qc_approve,courtver_result.first_qc_updated_on,courtver_result.first_qu_reject_reason,courtver.id,courtver.has_assigned_on,courtver.mode_of_veri,courtver.iniated_date, clients.clientname,(select created_on from court_activity_data where comp_table_id = courtver.id order by id desc limit 1) as last_activity_date,due_date,tat_status,courtver_result.closuredate,(select vendor_name from vendors where vendors.id = courtver.vendor_id) as vendor_name,courtver_insuff.insuff_raised_date");

		$this->db->from('courtver');

		$this->db->join("courtver_result",'courtver_result.courtver_id = courtver.id');
		
		$this->db->join("candidates_info",'candidates_info.id = courtver.candsid');

		$this->db->join("courtver_insuff",'(courtver_insuff.courtver_id = courtver.id AND  courtver_insuff.status = 1 )','left');
		
		$this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("status",'status.id = courtver_result.verfstatus');

		$this->db->where($this->filter_where_cond($where));

		 if(isset($where['start_date']) &&  $where['start_date'] != '' && isset($where['end_date']) &&  $where['end_date'] != '')	
		    { 

		     	$start_date  =  $where['start_date'];
	            $end_date  =  $where['end_date'];

	            if($where['status'] == "Closed")
                {
	         
		     	$where3 = "DATE_FORMAT(`courtver_result`.`closuredate`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}

		     	if($where['status'] == "Insufficiency")
                {
	         
		     	$where3 = "DATE_FORMAT(`courtver_insuff`.`insuff_raised_date`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}
                
                $this->db->where($where3); 

		    } 


		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like('candidates_info.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidatesContactNumber', $where['search']['value']);

		    $this->db->or_like('candidates_info.cmp_ref_no', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);

			$this->db->or_like('courtver.court_com_ref', $where['search']['value']);

			$this->db->or_like('courtver.iniated_date', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where['search']['value']);

			
		}
		
		$this->db->limit($where['length'],$where['start']);
		
		/*if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 

			$this->db->where('courtver.vendor_id !=',0);

			$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		}*/

		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 
            
           // $this->db->where('courtver.vendor_id !=',0);

           /* if(!empty($column_name_index))
			{

			  $this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		    }
		    else
		    {

               $order_clause = "(case verfstatus when 12 THEN 0 else 1 end),(case verfstatus when 13 THEN 0 else 1 end),(case verfstatus when 26 THEN 0 else 1 end),(case verfstatus when 11 THEN 0 else 1 end),(case verfstatus when 14 THEN 0 else 1 end)";

		    	$this->db->order_by($order_clause);
		    }*/
			$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		}
		else
		{
			//$order_clause = "(case verfstatus when 12 THEN 0 else 1 end),(case verfstatus when 13 THEN 0 else 1 end),(case verfstatus when 26 THEN 0 else 1 end),(case verfstatus when 11 THEN 0 else 1 end),(case verfstatus when 14 THEN 0 else 1 end)";

		  $this->db->order_by('courtver.id','desc');
		}    


		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_all_court_record_datatable_count($empver_aary = array(),$where,$columns)
	{
		$this->db->select("candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = courtver.has_case_id) as executive_name,status.status_value,courtver.court_com_ref,courtver.address_type, courtver.street_address,courtver.city,courtver.pincode,courtver.state,courtver_result.verfstatus,courtver_result.first_qc_approve,courtver_result.first_qc_updated_on,courtver_result.first_qu_reject_reason,courtver.id,courtver.has_assigned_on,courtver.iniated_date,clients.clientname,(select created_on from court_activity_data where comp_table_id = courtver.id order by id desc limit 1) as last_activity_date,courtver_insuff.insuff_raised_date");

		$this->db->from('courtver');

		$this->db->join("courtver_result",'courtver_result.courtver_id = courtver.id');
		
		$this->db->join("candidates_info",'candidates_info.id = courtver.candsid');

		$this->db->join("courtver_insuff",'(courtver_insuff.courtver_id = courtver.id AND  courtver_insuff.status = 1 )','left');

		
		$this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("status",'status.id = courtver_result.verfstatus');

		$this->db->where($this->filter_where_cond($where));

		 if(isset($where['start_date']) &&  $where['start_date'] != '' && isset($where['end_date']) &&  $where['end_date'] != '')	
		    { 

		     	$start_date  =  $where['start_date'];
	            $end_date  =  $where['end_date'];

	            if($where['status'] == "Closed")
                {
	         
		     	$where3 = "DATE_FORMAT(`courtver_result`.`closuredate`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}

		     	if($where['status'] == "Insufficiency")
                {
	         
		     	$where3 = "DATE_FORMAT(`courtver_insuff`.`insuff_raised_date`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}
                
                $this->db->where($where3); 

		    } 


		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like('candidates_info.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidatesContactNumber', $where['search']['value']);

			$this->db->or_like('candidates_info.cmp_ref_no', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);

			$this->db->or_like('courtver.court_com_ref', $where['search']['value']);

			$this->db->or_like('courtver.iniated_date', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where['search']['value']);

			
		}
				
		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 
            
            //$this->db->where('courtver.vendor_id !=',0);

           /* if(!empty($column_name_index))
			{

			  $this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		    }
		    else
		    {

               $order_clause = "(case verfstatus when 12 THEN 0 else 1 end),(case verfstatus when 13 THEN 0 else 1 end),(case verfstatus when 26 THEN 0 else 1 end),(case verfstatus when 11 THEN 0 else 1 end),(case verfstatus when 14 THEN 0 else 1 end)";

		    	$this->db->order_by($order_clause);
		    }*/
			$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		}
		else
		{
			///$order_clause = "(case verfstatus when 12 THEN 0 else 1 end),(case verfstatus when 13 THEN 0 else 1 end),(case verfstatus when 26 THEN 0 else 1 end),(case verfstatus when 11 THEN 0 else 1 end),(case verfstatus when 14 THEN 0 else 1 end)";

		    $this->db->order_by('courtver.id','desc');
		}    
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_all_court_record($where_array)
	{
		$this->db->select("candidates_info.id as cands_id,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = courtver.has_case_id) as executive_name,courtver.clientid,status.status_value as verfstatus,courtver.id as courtver_id,courtver.court_com_ref,courtver.courtver_re_open_date,courtver.has_case_id,courtver.address_type, courtver.street_address,courtver.city,courtver.pincode,courtver.build_date,courtver.state as state_id,courtver_result.id as courtver_result_id,courtver_result.var_filter_status,courtver_result.first_qc_approve,courtver_result.first_qc_updated_on,courtver_result.first_qu_reject_reason,courtver.id,courtver.has_assigned_on,courtver.iniated_date,(select clientname from clients where clients.id = candidates_info.clientid) as clientname,(select created_on from court_activity_data where comp_table_id = courtver.id order by id desc limit 1) as last_activity_date,due_date,tat_status,courtver.state,courtver.mode_of_veri,mode_of_verification,verified_date,advocate_name,courtver_result.remarks as remarks,closuredate,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,(select id from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_id,(select id from entity_package where entity_package.id = candidates_info.package limit 1) as package_id");

		$this->db->from('courtver');

		$this->db->join("courtver_result",'courtver_result.courtver_id = courtver.id');
		
		$this->db->join("candidates_info",'candidates_info.id = courtver.candsid');
		
		$this->db->join("status",'status.id = courtver_result.verfstatus');

		$this->db->where($where_array);

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function court_com_ref()
	{
		$result = $this->db->select("SUBSTRING_INDEX(court_com_ref, '-',-1) as A_I")->order_by('id','desc')->limit(1)-> get($this->tableName)->row_array();
		return $result;
	}

	public function uploaded_files($files_arry, $return_insert_ids = FALSE)
	{
		$res =  $this->db->insert_batch('courtver_files', $files_arry);
		
		record_db_error($this->db->last_query());
		
		return $res;
	}

	public function select_file($select_array,$where_array)
	{
		$this->db->select($select_array);

		$this->db->from('courtver_files');

		$this->db->where($where_array);

		$this->db->order_by('id', 'asc');
		
		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function select_file_vendor($select_array,$where_array,$where_array_id)
	{
		$this->db->select($select_array);

		$this->db->from('view_vendor_master_log_file');

		$this->db->join('view_vendor_master_log','view_vendor_master_log.id = view_vendor_master_log_file.view_venor_master_log_id');

        $this->db->join('courtver_vendor_log','courtver_vendor_log.id = view_vendor_master_log.case_id');

        $this->db->join("courtver",'courtver.id = courtver_vendor_log.case_id');

		$this->db->where($where_array);

		$this->db->where('courtver.id',$where_array_id);
		
		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}


	public function delete_uploaded_file($where = array())
	{	
		$this->db->where_in('id',$where);

		$this->db->set('status', STATUS_DELETED);

		$result = $this->db->update('courtver_files', array('status' => STATUS_DELETED));

		record_db_error($this->db->last_query());

		return $result;
	}

	public function add_uploaded_file($where = array())
	{	
		$this->db->where_in('id',$where);

		$this->db->set('status', STATUS_ACTIVE);

		$result = $this->db->update('courtver_files', array('status' => STATUS_ACTIVE));

		record_db_error($this->db->last_query());

		return $result;
	}

	public function vendor_delete_uploaded_file($where = array())
	{	
		$this->db->where_in('id',$where);

		$this->db->set('status', STATUS_DELETED);

		$result = $this->db->update('view_vendor_master_log_file', array('status' => STATUS_DELETED));

		record_db_error($this->db->last_query());

		return $result;
	}

	public function vendor_add_uploaded_file($where = array())
	{	
		$this->db->where_in('id',$where);

		$this->db->set('status', STATUS_ACTIVE);

		$result = $this->db->update('view_vendor_master_log_file', array('status' => STATUS_ACTIVE));

		record_db_error($this->db->last_query());

		return $result;
	}

	public function get_court_uploded_files($where_array)
	{
		$this->db->select('*');

		$this->db->from('courtver_files');

		$this->db->where($where_array);

		$this->db->order_by('serialno','asc');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();	
	}


	public function select_reinitiated_date($where_array)
	{
		
	    $this->db->select('courtver.*');

		$this->db->from('courtver');

		$this->db->where($where_array);
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	    
	}


	public function save_update_initiated_date($arrdata,$where_array)
	{
		if(!empty($where_array))
	    {
			$this->db->where($where_array);

			$result = $this->db->update('courtver', $arrdata);
			
			record_db_error($this->db->last_query());
			
			return $result;
	    }
	     
	}
    

	public function save_update_initiated_date_courtver($arrdata,$where_array)
	{
		if(!empty($where_array))
	    {
			$this->db->where($where_array);

			$result = $this->db->update('courtver_result', $arrdata);
			
			record_db_error($this->db->last_query());
			
			return $result;
	    }
	     
	}


    public function initiated_date_courtver_activity_data($arrdata)
	{
        
		   $this->db->insert('court_activity_data', $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	}


	public function get_date_for_update($where_array)
    {

        $this->db->select("due_date,tat_status");



        $this->db->from('courtver');
   

     
        $this->db->where($where_array);

        $result  = $this->db->get();
      
        return $result->result_array();
    }


     public function update_due_date($files_arry,$arrwhere )
	{

         if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update("courtver", $files_arry);
         
    
			record_db_error($this->db->last_query());
             
			return $result;
	    }
		
	}

	public function upload_file_update($updateArray)
	{
		$this->db->update_batch('courtver_files',$updateArray, 'id');
		return true; 
	}

	public function select_insuff($where_array)
	{
		$this->db->select('*')->from('courtver_insuff');

		$this->db->where($where_array);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function save_update_insuff($arrdata,$arrwhere = array())
	{
		if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update('courtver_insuff', $arrdata);
			
			record_db_error($this->db->last_query());
			
			return $result;
	    }
	    else
	    {
			$this->db->insert('courtver_insuff', $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	    }
	}


	 public function get_court_details_first_qc($where_arry = array())
	{
		$this->db->select("courtver.*,courtver_result.*,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.CandidateName,(SELECT status_value FROM status WHERE status.id = courtver_result.verfstatus) verfstatus_name,(select clientname from clients where clients.id = courtver_result.clientid limit 1) as clientname,(select user_name from user_profile where user_profile.id = courtver.has_case_id) as executive_name,due_date,tat_status,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,courtver.iniated_date,candidates_info.caserecddate,court_com_ref,(SELECT GROUP_CONCAT(concat(courtver.clientid,'/',file_name) ORDER BY serialno ASC SEPARATOR '||') FROM courtver_files where courtver_files.courtver_id = courtver_result.id and courtver_files.status = 1 and courtver_files.type= 1) as add_attachments");

		$this->db->from('courtver');

		$this->db->join("courtver_result",'courtver_result.courtver_id = courtver.id');
		
		$this->db->join("candidates_info",'candidates_info.id = courtver.candsid');

		if($where_arry)
		{
			$this->db->where($where_arry);
		}
		
		$result = $this->db->get();
		record_db_error($this->db->last_query());

		return $result->result_array();
	}


	public function select_insuff_join($where_array)
	{
		$this->db->select('courtver_insuff.*,(select user_name from user_profile where id =  courtver_insuff.created_by limit 1) as insuff_raised_by,(select user_name from user_profile where id = courtver_insuff.insuff_cleared_by limit 1) as insuff_cleared_by');

		$this->db->from('courtver_insuff');

		$this->db->where($where_array);
		
		$this->db->where('courtver_insuff.status !=',3);

		$this->db->order_by('courtver_insuff.id','asc');

		return $this->db->get()->result_array();
	}
	
	public function save_update_result($arrdata,$arrwhere = array())
	{
	    if(!empty($arrwhere)) {
			$this->db->where($arrwhere);
			return $this->db->update('courtver_result', $arrdata);
	    } else {
			$this->db->insert('courtver_result', $arrdata);
			return $this->db->insert_id();
	    }
	}

	public function save_update_result_court($arrdata,$arrwhere = array())
	{
	    if(!empty($arrwhere)) {
			$this->db->where($arrwhere);
			return $this->db->update('courtver_ver_result', $arrdata);
	    } else {
			$this->db->insert('courtver_ver_result', $arrdata);
			return $this->db->insert_id();
	    }
	}

	public function export_sql($filter) { 
	
	$sql = "SELECT (select clientname from clients where clients.id = candidates_info.clientid limit 1) as
		client_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name, ClientRefNumber,cmp_ref_no,CandidateName,DATE_FORMAT(caserecddate,'%d-%m-%Y') as caserecddate, (select status_value from status where status.id = courtver_result.verfstatus limit 1) as verfstatus,court_com_ref,(select user_name from user_profile where user_profile.id = courtver.has_case_id) as executive_name,address_type,street_address,city,pincode,state,vendor_id,mode_of_verification,advocate_name,
			courtver_result.remarks,DATE_FORMAT(iniated_date,'%d-%m-%Y') as iniated_date,DATE_FORMAT(due_date,'%d-%m-%Y') as due_date,tat_status,first_qc_updated_on,DATE_FORMAT(closuredate,'%d-%m-%Y') as closuredate,first_qc_approve,(select created_on from court_activity_data where comp_table_id = courtver.id order by id desc limit 1) as last_activity_date
		FROM courtver 
		INNER JOIN candidates_info ON candidates_info.id = courtver.candsid 
		INNER JOIN courtver_result ON courtver_result.courtver_id = courtver.id ".$filter." ";
		
		$query = $this->db->query($sql);

		return $query->result_array();
	}

	public function dashboard_sql($filter) { 
	
	$sql = "SELECT (select clientname from clients where clients.id = candidates_info.clientid limit 1) as
		client_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name, ClientRefNumber,cmp_ref_no,CandidateName,DATE_FORMAT(caserecddate,'%d-%m-%Y') as caserecddate, (select status_value from status where status.id = courtver_result.verfstatus limit 1) as verfstatus,court_com_ref,(select user_name from user_profile where user_profile.id = courtver.has_case_id) as executive_name,(select vendor_name from vendors where vendors.id = courtver.vendor_id) as vendor_name,street_address,city,pincode,state,mode_of_veri,
			courtver_result.remarks,DATE_FORMAT(iniated_date,'%d-%m-%Y') as iniated_date,DATE_FORMAT(due_date,'%d-%m-%Y') as due_date,tat_status,DATE_FORMAT(closuredate,'%d-%m-%Y') as closuredate,(select created_on from court_activity_data where comp_table_id = courtver.id order by id desc limit 1) as last_activity_date
		FROM courtver 
		INNER JOIN candidates_info ON candidates_info.id = courtver.candsid 
		INNER JOIN courtver_result ON courtver_result.courtver_id = courtver.id ".$filter." ";
		
		$query = $this->db->query($sql);

		return $query->result_array();
	}

	public function upload_vendor_assign($tableName,$updateArray,$where_arry)
	{		
		$this->db->where($where_arry);
	 	$this->db->update($tableName,$updateArray);
		return $this->db->affected_rows();
	}

	public function update_status($tableName,$updateArray,$where_arry)
    {   
       
        $this->db->where($where_arry);
        $this->db->update($tableName,$updateArray);
        return $this->db->affected_rows();
    }

	/*public function vendor_logs($where_array)
    {
        $this->db->select('vendor_master_log.status,vendor_master_log.id,trasaction_id,component,(select user_name from user_profile where user_profile.id = courtver_vendor_log.created_by) as allocated_by,courtver_vendor_log.created_on allocated_on,(select user_name from user_profile where user_profile.id = courtver_vendor_log.approval_by) as approval_by,courtver_vendor_log.modified_on approval_on,(select vendor_name from vendors where vendors.id= courtver_vendor_log.vendor_id) as vendor_name, court_com_ref as component_ref,ClientRefNumber,cmp_ref_no,costing,vendor_master_log.tat_status');

        $this->db->from('vendor_master_log');

        $this->db->join('courtver_vendor_log','courtver_vendor_log.id = vendor_master_log.case_id');

        $this->db->join('courtver','courtver.id = courtver_vendor_log.case_id');

        $this->db->join('candidates_info','candidates_info.id = courtver.candsid');

        $this->db->where($where_array);

        $this->db->order_by('courtver_vendor_log.id', 'desc');

        return $this->db->get()->result_array();
    }*/

   	public function vendor_logs($where_array,$id)
    {
        $this->db->select("view_vendor_master_log.*,(select vendor_name from vendors where vendors.id= courtver_vendor_log.vendor_id) as vendor_name,courtver_vendor_log.case_id,(select user_name from user_profile where id = view_vendor_master_log.allocated_by) as allocated_by,(select user_name from user_profile where id = view_vendor_master_log.approval_by) as approval_by");

        $this->db->from('view_vendor_master_log');
        $this->db->join('courtver_vendor_log','courtver_vendor_log.id = view_vendor_master_log.case_id');

       
        $this->db->where($where_array);

        if(!empty($id))
        {
        	 $this->db->where('courtver_vendor_log.case_id',$id);
        }

        $this->db->where_in('view_vendor_master_log.status',array('0','1','2','3','5'));

        $this->db->order_by("view_vendor_master_log.id", "desc");

        $result  = $this->db->get();
        return $result->result_array();
    }

     public function save_first_qc_result($files_arry,$arrwhere )
	{

         if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update("courtver_result", $files_arry);
         
    
			record_db_error($this->db->last_query());
             
			return $result;
	    }
		
	}

	 public function get_assign_users($tableName, $return_as_strict_row,$select_array, $where_array=array())
	{

		$this->db->select($select_array);

		$this->db->from($tableName);

		$this->db->join('roles','roles.id = user_profile.tbl_roles_id');

		$this->db->join('roles_permissions','roles_permissions.tbl_roles_id = roles.id');

        $this->db->where('roles_permissions.access_court_list_assign = 1' ); 

        $this->db->where($where_array); 
        
        $result_array = $this->db->get()->result_array();	
         
        if($return_as_strict_row)
		{
            if(count($result_array) >0 ) // ensure only one record has been previously inserted
            {
                $result_array = $result_array[0];
            }
        }

    
        return convert_to_single_dimension_array($result_array,'id','fullname');
	}
   
    public function get_assign_users_id($tableName, $return_as_strict_row,$select_array, $where_array=array())
	{

		$this->db->select($select_array);

		$this->db->from($tableName);

		$this->db->join('roles','roles.id = user_profile.tbl_roles_id');

		$this->db->join('roles_permissions','roles_permissions.tbl_roles_id = roles.id');

        $this->db->where('roles_permissions.access_court_list_assign = 1' ); 
        
        $this->db->where($where_array); 

        $result_array = $this->db->get()->result_array();	
         
        if($return_as_strict_row)
		{
            if(count($result_array) >0 ) // ensure only one record has been previously inserted
            {
                $result_array = $result_array[0];
            }
        }

    
        return convert_to_single_dimension_array($result_array,'id','fullname');
	}

    public function get_mode_of_verification($where)
	{
		$this->db->select('mode_of_verification');

		$this->db->from('clients_details');
		
		$this->db->where($where);

        $this->db->limit(1);  

		$result  = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function select_result_log($where_array)
	{
		$this->db->select('courtver_ver_result.*,(select created_by from activity_log where id =  courtver_ver_result.activity_log_id) as created_by1,(select user_name from user_profile where id = created_by1 ) as created_by,(select activity_mode from activity_log where id =  courtver_ver_result.activity_log_id) as activity_mode ,(select activity_status from activity_log where id =  courtver_ver_result.activity_log_id) as activity_status ,(select activity_type from activity_log where id =  courtver_ver_result.activity_log_id) as activity_type,(select action from activity_log where id =  courtver_ver_result.activity_log_id) as activity_action,(select GROUP_CONCAT(courtver_files.file_name) from courtver_files where `courtver_files`.`courtver_id` = `courtver_ver_result`.`courtver_id` AND `status` = 1 AND `type` = 1)  as file_names,(select GROUP_CONCAT(courtver_files.id) from courtver_files where `courtver_files`.`courtver_id` = `courtver_ver_result`.`courtver_id` AND `status` = 1 AND `type` = 1) as file_ids');

		$this->db->from('courtver_ver_result');

		$this->db->where($where_array);
		$this->db->order_by('courtver_ver_result.id','desc');
		//$this->db->where('addrverres_result.status !=',3);

		return $this->db->get()->result_array();

		
	}

	public function select_result_log1($where_array)
	{
		$this->db->select('courtver_ver_result.*,(select activity_mode from activity_log where id =  courtver_ver_result.activity_log_id) as activity_mode ,(select activity_status from activity_log where id =  courtver_ver_result.activity_log_id) as activity_status ,(select activity_type from activity_log where id =  courtver_ver_result.activity_log_id) as activity_type,(select action from activity_log where id =  courtver_ver_result.activity_log_id) as activity_action');

		$this->db->from('courtver_ver_result');

		//$this->db->join("education",'education.id = reference_ver_result.education_id');

		//$this->db->join("addrverres",'addrverres.addrverid = addrver.id');

		$this->db->where($where_array);
		$this->db->order_by('id','desc');
		//$this->db->where('addrverres.status !=',3);

		return $this->db->get()->result_array();

		
	}

	public function select_vendor_result_log($where_array,$id)
    {

        $this->db->select("view_vendor_master_log.*,(select vendor_name from vendors where vendors.id= courtver_vendor_log.vendor_id) as vendor_name,(select user_name from user_profile where id = view_vendor_master_log.allocated_by) as allocated_by,(select user_name from user_profile where id = view_vendor_master_log.approval_by) as approval_by,courtver.*,courtver.id as court_id,candidates_info.id as CandidateID,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.CandidateName,(select clientname from clients where clients.id = courtver.clientid limit 1) as clientname,
			(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,courtver.iniated_date,candidates_info.caserecddate,candidates_info.NameofCandidateFather,candidates_info.DateofBirth,  court_com_ref,candidates_info.entity as entity_id,candidates_info.package as package_id,(select id from courtver_result where courtver_result.courtver_id = courtver.id) as court_result_id,view_vendor_master_log.id as view_vendor_master_log_id,(select adv_name from vendors where vendors.id = courtver.vendor_id) as adv_name");

        $this->db->from('view_vendor_master_log');

        $this->db->join('courtver_vendor_log','courtver_vendor_log.id = view_vendor_master_log.case_id');

        $this->db->join('courtver','courtver.id = courtver_vendor_log.case_id');

		$this->db->join("candidates_info",'candidates_info.id = courtver.candsid');

        $this->db->where($where_array);

        if(!empty($id))
        {
        	 $this->db->where('view_vendor_master_log.id',$id);
        }

        $result  = $this->db->get();
       
        return $result->result_array();
    }

    public function get_all_court_by_client($clientid,$filter_by_status,$from_date,$to_date)
	{	

		$this->db->select("courtver.*,clients.clientname,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.DateofBirth,candidates_info.CandidateName,candidates_info.gender,candidates_info.NameofCandidateFather,status.status_value as verfstatus,status.filter_status as filter_status,(select concat(`user_profile`.`firstname`,' ',`user_profile`.`lastname`) from user_profile where user_profile.id = courtver.has_case_id limit 1) as executive_name,(select vendor_name from vendors where vendors.id = courtver.vendor_id limit 1) as vendor_name,(SELECT v.final_status from view_vendor_master_log v, `courtver_vendor_log` `co` where co.case_id = courtver.id and v.case_id = co.id and component = 'courtver' and component_tbl_id = '5' order by v.id desc limit 1) as vendor_status,(SELECT v.vendor_actual_status from view_vendor_master_log v, `courtver_vendor_log` `co` where co.case_id = courtver.id and v.case_id = co.id and component = 'courtver' and component_tbl_id = '5' order by v.id desc limit 1) as vendor_actual_status,(SELECT v.trasaction_id  from view_vendor_master_log v, `courtver_vendor_log` `co` where co.case_id = courtver.id and v.case_id = co.id and component = 'courtver' and component_tbl_id = '5' order by v.id desc limit 1) as transaction_id, cr1.closuredate,
			(SELECT GROUP_CONCAT(concat(DATE_FORMAT(courtver_insuff.insuff_raised_date,'%d-%m-%Y')) SEPARATOR '||') FROM courtver_insuff where courtver_insuff.courtver_id = courtver.id) as insuff_raised_date,(SELECT GROUP_CONCAT(concat(DATE_FORMAT(courtver_insuff.insuff_clear_date,'%d-%m-%Y')) SEPARATOR '||') FROM courtver_insuff where courtver_insuff.courtver_id = courtver.id) as insuff_clear_date,(SELECT GROUP_CONCAT(concat(courtver_insuff.insuff_raise_remark) SEPARATOR '||') FROM courtver_insuff where courtver_insuff.courtver_id = courtver.id) as insuff_raise_remark");

		$this->db->from('courtver');

		$this->db->join("clients",'clients.id = courtver.clientid');

		$this->db->join("candidates_info",'candidates_info.id = courtver.candsid');

		$this->db->join("courtver_result as cr1",'cr1.courtver_id = courtver.id','left');

		$this->db->join("courtver_result as cr2",'(cr2.courtver_id = courtver.id and cr1.id < cr2.id)','left');

		$this->db->join("status",'status.id = cr1.verfstatus','left');

		$this->db->where('cr2.verfstatus is null');

		if($clientid)
		{
			$this->db->where('courtver.clientid',$clientid);
		}

		if($from_date && $to_date)
		{

			$where3 = "DATE_FORMAT(`cr1`.`closuredate`,'%Y-%m-%d') BETWEEN '$from_date' AND '$to_date'";
                
            $this->db->where($where3); 
			
		}

		if($filter_by_status)
		{
			if($filter_by_status == "WIP")
			{
			$this->db->where('(cr1.var_filter_status = "wip" or  cr1.var_filter_status = "WIP")');
		    }
		    if($filter_by_status == "Insufficiency")
			{
			$this->db->where('(cr1.var_filter_status = "insufficiency" or  cr1.var_filter_status = "Insufficiency")');
		    }
		    if($filter_by_status == "Closed")
			{
			$this->db->where('(cr1.var_filter_status = "closed" or  cr1.var_filter_status = "Closed")');
		    }
		}

		$this->db->order_by('courtver.id', 'ASC');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}
    
	public function select_file_court($select_array,$where_array)
	{
		$this->db->select($select_array);

		$this->db->from('courtver_files');

		$this->db->where($where_array);

		$this->db->order_by('id', 'ASC');
		
		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

    public function select_file_from_vendor($select_array,$where_array)
	{
		$this->db->select($select_array);

		$this->db->from('view_vendor_master_log_file');

		$this->db->where($where_array);

		
		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

    public function select_vendor_result_log_cost($where_array,$id)
    {

        $this->db->select("view_vendor_master_log.*");



        $this->db->from('view_vendor_master_log');
   

        
        $this->db->where($where_array);

        if(!empty($id))
        {
        	 $this->db->where('view_vendor_master_log.id',$id);
        }

        $result  = $this->db->get();
        
        return $result->result_array();
    }

    public function save_vendor_details($tablename,$arrdata,$arrwhere = array())
	{
	    if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update($tablename,$arrdata);
       
			record_db_error($this->db->last_query());
             
			return $result;
	    }
	 
	}

	 public function save_vendor_details_cancel($arrdata,$arrwhere = array())
	{
	    if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update("view_vendor_master_log", $arrdata);
       
			record_db_error($this->db->last_query());
             
			return $result;
	    }
	    
	}
    

     public function update_court_vendor_log($tablename,$arrdata,$arrwhere = array())
	{
	    if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update($tablename, $arrdata);
       
			record_db_error($this->db->last_query());
             
			return $result;
	    }
	    
	}

	public function court_case_list($where_array = array(),$where)
    {
        $where_condition = "(view_vendor_master_log.final_status = 'clear' or view_vendor_master_log.final_status = 'possible match')" ;

        $this->db->select("view_vendor_master_log.*,CandidateName,clients.id as client_id,clients.clientname,courtver.id as court_id,courtver.court_com_ref,courtver.address_type,courtver.street_address,courtver.vendor_list_mode,courtver.city,courtver.state,courtver.pincode,(select vendor_name from vendors where vendors.id = courtver.vendor_id) as vendor_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,candidates_info.ClientRefNumber");

        $this->db->from('view_vendor_master_log');
      
        $this->db->join('courtver_vendor_log','courtver_vendor_log.id = view_vendor_master_log.case_id');
        $this->db->join('courtver','courtver.id = courtver_vendor_log.case_id');


        $this->db->join('candidates_info','candidates_info.id = courtver.candsid');
       
        $this->db->join("clients",'clients.id = courtver.clientid');


        $this->db->where($where_array);

        $this->db->where($where_condition);

        if($where != "All" && $where != "" && $where !=  100000)
        {
          $this->db->where('courtver.clientid', $where);

        }

        $this->db->order_by('view_vendor_master_log.trasaction_id', 'ASC');
    
        $result = $this->db->get();

       return $result->result_array();
   
    }

    public function court_case_list_insuff($where_array = array(),$where)
    {
        $admin_status = "(courtver_result.verfstatus = 1 or courtver_result.verfstatus = 11 or courtver_result.verfstatus = 12 or courtver_result.verfstatus = 13 or courtver_result.verfstatus = 14 or courtver_result.verfstatus = 16 or courtver_result.verfstatus = 23 or courtver_result.verfstatus = 26 )";

        $this->db->select("view_vendor_master_log.*,CandidateName,clients.clientname,courtver.id as court_id,courtver.court_com_ref,courtver.address_type,courtver.street_address,courtver.vendor_list_mode,courtver.city,courtver.state,courtver.pincode,(select vendor_name from vendors where vendors.id = courtver.vendor_id) as vendor_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,candidates_info.ClientRefNumber");
        
        $this->db->from('view_vendor_master_log');
      
        $this->db->join('courtver_vendor_log','courtver_vendor_log.id = view_vendor_master_log.case_id');

        $this->db->join('courtver','courtver.id = courtver_vendor_log.case_id');

        $this->db->join('courtver_result','courtver_result.courtver_id = courtver.id');

        $this->db->join('status','status.id = courtver_result.verfstatus');

        $this->db->join('candidates_info','candidates_info.id = courtver.candsid');
       
        $this->db->join("clients",'clients.id = courtver.clientid');


        $this->db->where($where_array);

        $this->db->where($admin_status);

        /*if($where != "All")
        {

          $this->db->where('courtver.has_case_id', $where);

        }*/

        $this->db->order_by('view_vendor_master_log.trasaction_id', 'ASC');
    
   
        $result = $this->db->get();
    
        return $result->result_array();
    
      
    }


    public function update_closure_approval($table_name,$arrdata )
	{
     
			$this->db->insert($table_name, $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	    
	}
	 public function update_closure_approval_details($table_name,$arrdata,$where_array )
	{
     
	 if(!empty($where_array))
	    {
			$this->db->where($where_array);

			$result = $this->db->update($table_name, $arrdata);
         
    
			record_db_error($this->db->last_query());
             
			return $result;
	    }
	}

	 public function get_client_id($where_array )
	{
     
	  $this->db->select("view_vendor_master_log.id,view_vendor_master_log.case_id as  vendor_master_log_case, courtver_vendor_log.id as courtver_vendor_log_id,courtver_vendor_log.case_id as courtver_vendor_log_case,courtver.id as courtver_id , courtver.clientid as client_id" );
        $this->db->from('view_vendor_master_log');
       // $this->db->join('addrver','addrver.id = view_vendor_master_log.component_tbl_id');
        $this->db->join('courtver_vendor_log','courtver_vendor_log.id = view_vendor_master_log.case_id');
        $this->db->join('courtver','courtver.id = courtver_vendor_log.case_id');
        $this->db->join("clients",'clients.id = courtver.clientid');


        $this->db->where($where_array);
        
        $result = $this->db->get();
  
       return $result->result_array();
	}

    public function check_vendor_status_closed_or_not($where_array)
	{
     
	  $this->db->select("view_vendor_master_log.id,view_vendor_master_log.final_status,view_vendor_master_log.case_id as  vendor_master_log_case, courtver_vendor_log.id as court_vendor_log_id,courtver_vendor_log.case_id as court_vendor_log_case,courtver.id as court_id" );

        $this->db->from('view_vendor_master_log');
      
        $this->db->join('courtver_vendor_log','courtver_vendor_log.id = view_vendor_master_log.case_id');

        $this->db->join('courtver','courtver.id = courtver_vendor_log.case_id');
    
        $this->db->where($where_array);

        $this->db->where("(view_vendor_master_log.final_status = 'wip' or view_vendor_master_log.final_status = 'insufficiency')");
        
        $result = $this->db->get();
 
       return $result->result_array();
	}

	public function check_vendor_status_insufficiency($where_array )
	{
     
	  $this->db->select("view_vendor_master_log.id,view_vendor_master_log.final_status,view_vendor_master_log.case_id as  vendor_master_log_case, courtver_vendor_log.id as court_vendor_log_id,courtver_vendor_log.case_id as court_vendor_log_case,courtver.id as courtver_id" );

        $this->db->from('view_vendor_master_log');
      
        $this->db->join('courtver_vendor_log','courtver_vendor_log.id = view_vendor_master_log.case_id');

        $this->db->join('courtver','courtver.id = courtver_vendor_log.case_id');
    
        $this->db->where($where_array);

        $this->db->where('courtver.vendor_id !=', 0);

        $result = $this->db->get();
 
       return $result->result_array();
	}

     public function reject_cost_vendor($arrdata,$arrwhere = array())
	{
	    if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update("view_vendor_master_log", $arrdata);
       
			record_db_error($this->db->last_query());
             
			return $result;
	    }
	    
	}  

    
    public function get_user_id($where_array)
	{
		$this->db->select('id');

		$this->db->from('user_profile');

		$this->db->where($where_array);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->row('id');
	} 

	public function select_candidate_from_file($id)
	{
		$this->db->select('file_name');

		$this->db->from('courtver_files');

		$this->db->join('courtver','courtver.id = courtver_files.courtver_id');
       
        $this->db->join('candidates_info','candidates_info.id = courtver.candsid');

		$this->db->where('candidates_info.id',$id);

		
		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function update_final_status_of_vendor($court_id)
	{

	    if(!empty($court_id))
	    {

			$sql =  'UPDATE view_vendor_master_log LEFT JOIN courtver_vendor_log ON courtver_vendor_log.case_id = view_vendor_master_log.id LEFT JOIN courtver ON courtver.id = courtver_vendor_log.case_id LEFT JOIN courtver_result ON courtver_result.courtver_id = courtver.id SET view_vendor_master_log.final_status = "closed" WHERE (courtver_result.verfstatus = 9 or courtver_result.verfstatus = 27 or courtver_result.verfstatus = 28) and (view_vendor_master_log.component = "courtver" and view_vendor_master_log.component_tbl_id = 5 and (view_vendor_master_log.final_status = "wip" or view_vendor_master_log.final_status = "insufficiency")) AND courtver.id = '.$court_id; 

            $query = $this->db->query($sql);
            
            return $query;
           
	    }   
	} 

	public function select_client_list_view_court($tableName, $return_as_strict_row,$select_array, $where1=array())
	{
		$this->db->select($select_array);
       
		$this->db->from($tableName);

		$this->db->join("courtver",'courtver.clientid = clients.id');

		$this->db->join("user_profile",'user_profile.id = courtver.has_case_id','left');

		$this->db->join("candidates_info",'candidates_info.id = courtver.candsid');

	
		$this->db->join("courtver_result",'courtver_result.courtver_id = courtver.id','left');


		$this->db->join("courtver_insuff",'(courtver_insuff.courtver_id = courtver.id AND  courtver_insuff.status = 1 )','left');

		$this->db->join("status",'status.id = courtver_result.verfstatus','left');

        $this->db->where($this->filter_where_cond($where1)); 

        $this->db->where('clients.status',1); 


            if(isset($where1['start_date']) &&  $where1['start_date'] != '' && isset($where1['end_date']) &&  $where1['end_date'] != '')	
		    { 

		     	$start_date  =  $where1['start_date'];
	            $end_date  =  $where1['end_date'];

	            if($where1['status'] == "Closed")
                {
	         
		     	$where3 = "DATE_FORMAT(`courtver_result`.`closuredate`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}

		     	if($where1['status'] == "Insufficiency")
                {
	         
		     	$where3 = "DATE_FORMAT(`courtver_insuff`.`insuff_raised_date`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}
                
                $this->db->where($where3); 

		    } 
   
		$result = $this->db->get();

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

	public function save_update_court_files($arrdata,$arrwhere = array())
	{
		if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update('courtver_files', $arrdata);

			record_db_error($this->db->last_query());

			return $result;
	    }
	    else
	    {
			$this->db->insert('courtver_files', $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	    }
	} 

	public function insuff_reason_list($return_as_strict_row,$where)
    {   
        $this->db->select('remarks,reason');

		$this->db->from('raising_insuff_dropdown');

        $this->db->where($where); 
        
        $result_array = $this->db->get()->result_array();	
      
        if($return_as_strict_row)
		{
            if(count($result_array) >0 ) // ensure only one record has been previously inserted
            {
                $result_array = $result_array[0];
            }
        }
       

        return convert_to_single_dimension_array($result_array,'reason','reason');
    }

    public function get_vendor_id($clientid)
	{ 
		$sql = "SELECT `id` FROM `vendors` WHERE FIND_IN_SET(".$clientid.",vendors.court_client)";
		
		$query = $this->db->query($sql);

		$result_array =  $query->result_array();
		
		return $result_array;

	}

	public function save_vendor_log($arrdata,$arrwhere = array())
	{
	    if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update('courtver_vendor_log', $arrdata);
       
			record_db_error($this->db->last_query());
             
			return $result;
	    }
	    else
	    {
			$this->db->insert('courtver_vendor_log', $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	    }
	}

	public  function get_reporting_manager_id($clientid)
    {
    	
        $this->db->select('clientmgr');

		$this->db->from('clients');

		$this->db->where('clients.id',$clientid);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
    
    }	

    public function check_court_exists_in_candidate($where_array)
    { 
    	$this->db->select('id');

		$this->db->from('courtver');

		$this->db->where($where_array);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();

    }

    public function get_court_details_for_approval($case_id)
    {
    	$this->db->select('courtver_vendor_log.id as courtver_vendor_log_id,clients.clientname,courtver.court_com_ref,candidates_info.CandidateName,candidates_info.DateofBirth,candidates_info.NameofCandidateFather,courtver.vendor_id,courtver.street_address,courtver.city,courtver.pincode,courtver.state');

		$this->db->from('courtver_vendor_log');

		$this->db->join('courtver','courtver.id = courtver_vendor_log.case_id ');

        $this->db->join('candidates_info','candidates_info.id = courtver.candsid');

        $this->db->join('clients','clients.id = courtver.clientid');

		$this->db->where('courtver_vendor_log.id',$case_id);

		$result  = $this->db->get();
     
		record_db_error($this->db->last_query());
		
		return $result->result_array();
    }

    public function vendor_email_id($where_array)
    {
    	$this->db->select('vendors.*');

		$this->db->from('vendors');
  
        if($where_array)
        {

		  $this->db->where($where_array);

	    }

		$result  = $this->db->get();
     
		record_db_error($this->db->last_query());
		
		return $result->result_array();
    }

    public function select_court_approve_list($where_array = array())
	{

		$this->db->select('courtver_vendor_log.id');

		$this->db->from('courtver_vendor_log');

		$this->db->join('courtver','courtver.id = courtver_vendor_log.case_id');

	    $this->db->join("courtver_result",'courtver_result.courtver_id = courtver.id','left');

	    $this->db->join("candidates_info",'candidates_info.id = courtver.candsid','left');

		$this->db->where($where_array);

        $this->db->where('(courtver_result.var_filter_status = "wip" or courtver_result.var_filter_status = "WIP")');
      
		$this->db->where("courtver_vendor_log.created_on <= DATE_SUB(NOW(), INTERVAL 30 MINUTE)", NULL, FALSE);

		$this->db->order_by('courtver.id','desc'); 

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}



	 
}
?>