<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Court_model extends CI_Model
{
	function __construct()
    {
		//parent::__construct();

		$this->tableName = 'courtver';

		$this->primaryKey = 'id';
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

    public function  save_update($arrdata,$arrwhere = array())
	{
	    if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update('courtver_result', $arrdata);

			record_db_error($this->db->last_query());

			return $result;
	    }
	   
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

	public function get_all_court_by_candidate_datatable($where,$columns)
	{
		$this->db->select("candidates_info.id as candidates_info_id,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = courtver.has_case_id) as executive_name,status.status_value,courtver.id as courtver_id,courtver.court_com_ref,courtver.street_address,courtver.city,courtver.pincode,courtver.state,courtver_result.verfstatus,courtver_result.first_qc_approve,courtver_result.first_qc_updated_on,courtver_result.first_qu_reject_reason,courtver.id,courtver.has_assigned_on,courtver.mode_of_veri,courtver.iniated_date, clients.clientname,(select created_on from court_activity_data where comp_table_id = courtver.id order by id desc limit 1) as last_activity_date,due_date,tat_status,courtver_result.closuredate,(select vendor_name from vendors where vendors.id = courtver.vendor_id) as vendor_name,courtver_insuff.insuff_raised_date");

		$this->db->from('courtver');

		$this->db->join("courtver_result",'courtver_result.courtver_id = courtver.id');
		
		$this->db->join("candidates_info",'candidates_info.id = courtver.candsid');

		$this->db->join("courtver_insuff",'(courtver_insuff.courtver_id = courtver.id AND  courtver_insuff.status = 1 )','left');
		
		$this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("status",'status.id = courtver_result.verfstatus');

		$this->db->where('courtver.fill_by','2'); 
        
        $this->db->where('courtver_result.verfstatus','18'); 

        $this->db->where('courtver.candsid',$this->candidate_info['cands_info_id']); 

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

            if(!empty($column_name_index))
			{

			  $this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		    }
		    else
		    {

               $order_clause = "(case verfstatus when 12 THEN 0 else 1 end),(case verfstatus when 13 THEN 0 else 1 end),(case verfstatus when 26 THEN 0 else 1 end),(case verfstatus when 11 THEN 0 else 1 end),(case verfstatus when 14 THEN 0 else 1 end)";

		    	$this->db->order_by($order_clause);
		    }
			//$this->db->order_by($where1['columns'][$column_name_index]['data'],$order_by);
		}
		else
		{
			$order_clause = "(case verfstatus when 12 THEN 0 else 1 end),(case verfstatus when 13 THEN 0 else 1 end),(case verfstatus when 26 THEN 0 else 1 end),(case verfstatus when 11 THEN 0 else 1 end),(case verfstatus when 14 THEN 0 else 1 end)";

		    	$this->db->order_by($order_clause);
		}    


		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_all_court_by_candidate_datatable_count($where,$columns)
	{
		$this->db->select("candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = courtver.has_case_id) as executive_name,status.status_value,courtver.court_com_ref,courtver.address_type, courtver.street_address,courtver.city,courtver.pincode,courtver.state,courtver_result.verfstatus,courtver_result.first_qc_approve,courtver_result.first_qc_updated_on,courtver_result.first_qu_reject_reason,courtver.id,courtver.has_assigned_on,courtver.iniated_date,clients.clientname,(select created_on from court_activity_data where comp_table_id = courtver.id order by id desc limit 1) as last_activity_date,courtver_insuff.insuff_raised_date");

		$this->db->from('courtver');

		$this->db->join("courtver_result",'courtver_result.courtver_id = courtver.id');
		
		$this->db->join("candidates_info",'candidates_info.id = courtver.candsid');

		$this->db->join("courtver_insuff",'(courtver_insuff.courtver_id = courtver.id AND  courtver_insuff.status = 1 )','left');

		
		$this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("status",'status.id = courtver_result.verfstatus');

		$this->db->where('courtver.fill_by','2'); 
        
        $this->db->where('courtver_result.verfstatus','18'); 

        $this->db->where('courtver.candsid',$this->candidate_info['cands_info_id']); 

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

            if(!empty($column_name_index))
			{

			  $this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		    }
		    else
		    {

               $order_clause = "(case verfstatus when 12 THEN 0 else 1 end),(case verfstatus when 13 THEN 0 else 1 end),(case verfstatus when 26 THEN 0 else 1 end),(case verfstatus when 11 THEN 0 else 1 end),(case verfstatus when 14 THEN 0 else 1 end)";

		    	$this->db->order_by($order_clause);
		    }
			//$this->db->order_by($where1['columns'][$column_name_index]['data'],$order_by);
		}
		else
		{
			$order_clause = "(case verfstatus when 12 THEN 0 else 1 end),(case verfstatus when 13 THEN 0 else 1 end),(case verfstatus when 26 THEN 0 else 1 end),(case verfstatus when 11 THEN 0 else 1 end),(case verfstatus when 14 THEN 0 else 1 end)";

		    	$this->db->order_by($order_clause);
		}    
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_all_court_submit_by_candidate_datatable($where,$columns)
	{
		$this->db->select("candidates_info.id as candidates_info_id,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = courtver.has_case_id) as executive_name,status.status_value,courtver.id as courtver_id,courtver.court_com_ref,courtver.street_address,courtver.city,courtver.pincode,courtver.state,courtver_result.verfstatus,courtver_result.first_qc_approve,courtver_result.first_qc_updated_on,courtver_result.first_qu_reject_reason,courtver.id,courtver.has_assigned_on,courtver.mode_of_veri,courtver.iniated_date, clients.clientname,(select created_on from court_activity_data where comp_table_id = courtver.id order by id desc limit 1) as last_activity_date,due_date,tat_status,courtver_result.closuredate,(select vendor_name from vendors where vendors.id = courtver.vendor_id) as vendor_name,courtver_insuff.insuff_raised_date");

		$this->db->from('courtver');

		$this->db->join("courtver_result",'courtver_result.courtver_id = courtver.id');
		
		$this->db->join("candidates_info",'candidates_info.id = courtver.candsid');

		$this->db->join("courtver_insuff",'(courtver_insuff.courtver_id = courtver.id AND  courtver_insuff.status = 1 )','left');
		
		$this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("status",'status.id = courtver_result.verfstatus');

		$this->db->where('courtver.fill_by','2'); 
        
        $this->db->where('courtver_result.verfstatus !=','18'); 

        $this->db->where('courtver.candsid',$this->candidate_info['cands_info_id']); 


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

            if(!empty($column_name_index))
			{

			  $this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		    }
		    else
		    {

               $order_clause = "(case verfstatus when 12 THEN 0 else 1 end),(case verfstatus when 13 THEN 0 else 1 end),(case verfstatus when 26 THEN 0 else 1 end),(case verfstatus when 11 THEN 0 else 1 end),(case verfstatus when 14 THEN 0 else 1 end)";

		    	$this->db->order_by($order_clause);
		    }
			//$this->db->order_by($where1['columns'][$column_name_index]['data'],$order_by);
		}
		else
		{
			$order_clause = "(case verfstatus when 12 THEN 0 else 1 end),(case verfstatus when 13 THEN 0 else 1 end),(case verfstatus when 26 THEN 0 else 1 end),(case verfstatus when 11 THEN 0 else 1 end),(case verfstatus when 14 THEN 0 else 1 end)";

		    	$this->db->order_by($order_clause);
		}    


		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_all_court_submit_by_candidate_datatable_count($where,$columns)
	{
		$this->db->select("candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = courtver.has_case_id) as executive_name,status.status_value,courtver.court_com_ref,courtver.address_type, courtver.street_address,courtver.city,courtver.pincode,courtver.state,courtver_result.verfstatus,courtver_result.first_qc_approve,courtver_result.first_qc_updated_on,courtver_result.first_qu_reject_reason,courtver.id,courtver.has_assigned_on,courtver.iniated_date,clients.clientname,(select created_on from court_activity_data where comp_table_id = courtver.id order by id desc limit 1) as last_activity_date,courtver_insuff.insuff_raised_date");

		$this->db->from('courtver');

		$this->db->join("courtver_result",'courtver_result.courtver_id = courtver.id');
		
		$this->db->join("candidates_info",'candidates_info.id = courtver.candsid');

		$this->db->join("courtver_insuff",'(courtver_insuff.courtver_id = courtver.id AND  courtver_insuff.status = 1 )','left');

		
		$this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("status",'status.id = courtver_result.verfstatus');

		$this->db->where('courtver.fill_by','2'); 
        
        $this->db->where('courtver_result.verfstatus !=','18'); 

        $this->db->where('courtver.candsid',$this->candidate_info['cands_info_id']); 

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

            if(!empty($column_name_index))
			{

			  $this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		    }
		    else
		    {

               $order_clause = "(case verfstatus when 12 THEN 0 else 1 end),(case verfstatus when 13 THEN 0 else 1 end),(case verfstatus when 26 THEN 0 else 1 end),(case verfstatus when 11 THEN 0 else 1 end),(case verfstatus when 14 THEN 0 else 1 end)";

		    	$this->db->order_by($order_clause);
		    }
			//$this->db->order_by($where1['columns'][$column_name_index]['data'],$order_by);
		}
		else
		{
			$order_clause = "(case verfstatus when 12 THEN 0 else 1 end),(case verfstatus when 13 THEN 0 else 1 end),(case verfstatus when 26 THEN 0 else 1 end),(case verfstatus when 11 THEN 0 else 1 end),(case verfstatus when 14 THEN 0 else 1 end)";

		    	$this->db->order_by($order_clause);
		}    
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}
   
    public function get_mode_of_verification($where)
	{
		$this->db->select('mode_of_verification ');

		$this->db->from('clients_details');
		
		$this->db->where($where);

        $this->db->limit(1);  

		$result  = $this->db->get();

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
	
}
?>