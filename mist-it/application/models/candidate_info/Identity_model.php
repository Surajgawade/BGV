<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Identity_model extends CI_Model
{
	function __construct()
    {
		//parent::__construct();

		$this->tableName = 'identity';

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

			$result = $this->db->update('identity_result', $arrdata);

			record_db_error($this->db->last_query());

			return $result;
	    }
	   
	}

	 public function select_insuff($where_array)
	{
		$this->db->select('*')->from('identity_insuff');

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

			$result = $this->db->update('identity_insuff', $arrdata);
			
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
			$where['identity_result.var_filter_status'] = $where_arry['status'];
		    }
		}

		if(isset($where_arry['sub_status']) && $where_arry['sub_status'] != '' && $where_arry['sub_status'] != 0)	
		{
			$where['identity_result.verfstatus'] = $where_arry['sub_status'];
		}

		if(isset($where_arry['client_id']) &&  $where_arry['client_id'] != 0)	
		{
			$where['identity.clientid'] = $where_arry['client_id'];
		}
        if(isset($where_arry['filter_by_executive']) &&  $where_arry['filter_by_executive'] != 0)	
		{
	        if($where_arry['filter_by_executive'] != "All")
	      	{
              $where['identity.has_case_id'] = $where_arry['filter_by_executive'];
	      	}
	    }

		return $where;

	}

	public function get_all_identity_record_datatable($where,$columns)
	{
		$this->db->select("candidates_info.id as candidates_info_id,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = identity.has_case_id) as user_name,status.status_value,identity.identity_com_ref,identity_result.verfstatus,identity_result.first_qc_approve,identity_result.first_qc_updated_on,identity_result.first_qu_reject_reason,identity.id,identity.mode_of_veri,identity.id as identity_id,identity.has_assigned_on,identity.iniated_date,clients.clientname,(select created_on from identity_activity_data where comp_table_id = identity.id  order by id desc limit 1) as last_activity_date,identity_result.remarks,due_date,tat_status,closuredate,id_number,identity_insuff.insuff_raised_date");

		$this->db->from('identity');

		$this->db->join("identity_result",'identity_result.identity_id = identity.id');
		
		$this->db->join("candidates_info",'candidates_info.id = identity.candsid');

		$this->db->join("identity_insuff",'(identity_insuff.identity_id = identity.id AND  identity_insuff.status = 1 )','left');

		
		$this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("status",'status.id = identity_result.verfstatus');

	    $this->db->where('identity.fill_by','2'); 
        
        $this->db->where('identity_result.verfstatus','18'); 

        $this->db->where('identity.candsid',$this->candidate_info['cands_info_id']); 


		$this->db->where($this->filter_where_cond($where));

		 if(isset($where['start_date']) &&  $where['start_date'] != '' && isset($where['end_date']) &&  $where['end_date'] != '')	
		    { 

		     	$start_date  =  $where['start_date'];
	            $end_date  =  $where['end_date'];

	            if($where['status'] == "Closed")
                {
	         
		     	$where3 = "DATE_FORMAT(`identity_result`.`closuredate`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}

		     	if($where['status'] == "Insufficiency")
                {
	         
		     	$where3 = "DATE_FORMAT(`identity_insuff`.`insuff_raised_date`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}
                
                $this->db->where($where3); 

		    } 


		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like('candidates_info.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('candidates_info.cmp_ref_no', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);

			$this->db->or_like('identity.identity_com_ref', $where['search']['value']);

			$this->db->or_like('identity.iniated_date', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where['search']['value']);
			
		}
		
		$this->db->limit($where['length'],$where['start']);
		
		/*if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 

		   $this->db->where('identity.vendor_id !=',0);

			$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		}*/


		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 
            
             //$this->db->where('identity.vendor_id !=',0);
         
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

	public function get_all_identity_record_datatable_count($where,$columns)
	{
		$this->db->select("candidates_info.id as cands_id,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = identity.has_case_id) as user_name,status.status_value,identity.id as identity_id,identity.identity_com_ref,identity_result.verfstatus,identity_result.first_qc_approve,identity_result.first_qc_updated_on,identity_result.first_qu_reject_reason,identity.id,identity.has_assigned_on,identity.iniated_date,clients.clientname,(select created_on from identity_activity_data where comp_table_id = identity.id  order by id desc limit 1) as last_activity_date,identity_insuff.insuff_raised_date");

		$this->db->from('identity');

		$this->db->join("identity_result",'identity_result.identity_id = identity.id');
		
		$this->db->join("candidates_info",'candidates_info.id = identity.candsid');

		$this->db->join("identity_insuff",'(identity_insuff.identity_id = identity.id AND  identity_insuff.status = 1 )','left');

		
		$this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("status",'status.id = identity_result.verfstatus');

		$this->db->where('identity.fill_by','2'); 
        
        $this->db->where('identity_result.verfstatus','18');

        $this->db->where('identity.candsid',$this->candidate_info['cands_info_id']); 

		$this->db->where($this->filter_where_cond($where));

		 if(isset($where['start_date']) &&  $where['start_date'] != '' && isset($where['end_date']) &&  $where['end_date'] != '')	
		    { 

		     	$start_date  =  $where['start_date'];
	            $end_date  =  $where['end_date'];

	            if($where['status'] == "Closed")
                {
	         
		     	$where3 = "DATE_FORMAT(`identity_result`.`closuredate`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}

		     	if($where['status'] == "Insufficiency")
                {
	         
		     	$where3 = "DATE_FORMAT(`identity_insuff`.`insuff_raised_date`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}
                
                $this->db->where($where3); 

		    } 


		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like('candidates_info.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('candidates_info.cmp_ref_no', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);

			$this->db->or_like('identity.identity_com_ref', $where['search']['value']);

			$this->db->or_like('identity.iniated_date', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where['search']['value']);
			
		}
				
		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 
            
            // $this->db->where('identity.vendor_id !=',0);
         
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

	public function get_all_identity_submit_record_datatable($where,$columns)
	{
		$this->db->select("candidates_info.id as candidates_info_id,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = identity.has_case_id) as user_name,status.status_value,identity.identity_com_ref,identity_result.verfstatus,identity_result.first_qc_approve,identity_result.first_qc_updated_on,identity_result.first_qu_reject_reason,identity.id,identity.mode_of_veri,identity.id as identity_id,identity.has_assigned_on,identity.iniated_date,clients.clientname,(select created_on from identity_activity_data where comp_table_id = identity.id  order by id desc limit 1) as last_activity_date,identity_result.remarks,due_date,tat_status,closuredate,id_number,identity_insuff.insuff_raised_date");

		$this->db->from('identity');

		$this->db->join("identity_result",'identity_result.identity_id = identity.id');
		
		$this->db->join("candidates_info",'candidates_info.id = identity.candsid');

		$this->db->join("identity_insuff",'(identity_insuff.identity_id = identity.id AND  identity_insuff.status = 1 )','left');

		
		$this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("status",'status.id = identity_result.verfstatus');

	    $this->db->where('identity.fill_by','2'); 
        
        $this->db->where('identity_result.verfstatus !=','18'); 

        $this->db->where('identity.candsid',$this->candidate_info['cands_info_id']); 


		$this->db->where($this->filter_where_cond($where));

		 if(isset($where['start_date']) &&  $where['start_date'] != '' && isset($where['end_date']) &&  $where['end_date'] != '')	
		    { 

		     	$start_date  =  $where['start_date'];
	            $end_date  =  $where['end_date'];

	            if($where['status'] == "Closed")
                {
	         
		     	$where3 = "DATE_FORMAT(`identity_result`.`closuredate`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}

		     	if($where['status'] == "Insufficiency")
                {
	         
		     	$where3 = "DATE_FORMAT(`identity_insuff`.`insuff_raised_date`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}
                
                $this->db->where($where3); 

		    } 


		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like('candidates_info.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('candidates_info.cmp_ref_no', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);

			$this->db->or_like('identity.identity_com_ref', $where['search']['value']);

			$this->db->or_like('identity.iniated_date', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where['search']['value']);
			
		}
		
		$this->db->limit($where['length'],$where['start']);
		
		/*if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 

		   $this->db->where('identity.vendor_id !=',0);

			$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		}*/


		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 
            
             //$this->db->where('identity.vendor_id !=',0);
         
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

	public function get_all_identity_submit_record_datatable_count($where,$columns)
	{
		$this->db->select("candidates_info.id as cands_id,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = identity.has_case_id) as user_name,status.status_value,identity.id as identity_id,identity.identity_com_ref,identity_result.verfstatus,identity_result.first_qc_approve,identity_result.first_qc_updated_on,identity_result.first_qu_reject_reason,identity.id,identity.has_assigned_on,identity.iniated_date,clients.clientname,(select created_on from identity_activity_data where comp_table_id = identity.id  order by id desc limit 1) as last_activity_date,identity_insuff.insuff_raised_date");

		$this->db->from('identity');

		$this->db->join("identity_result",'identity_result.identity_id = identity.id');
		
		$this->db->join("candidates_info",'candidates_info.id = identity.candsid');

		$this->db->join("identity_insuff",'(identity_insuff.identity_id = identity.id AND  identity_insuff.status = 1 )','left');

		
		$this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("status",'status.id = identity_result.verfstatus');

		$this->db->where('identity.fill_by','2'); 
        
        $this->db->where('identity_result.verfstatus !=','18');

        $this->db->where('identity.candsid',$this->candidate_info['cands_info_id']); 
 

		$this->db->where($this->filter_where_cond($where));

		 if(isset($where['start_date']) &&  $where['start_date'] != '' && isset($where['end_date']) &&  $where['end_date'] != '')	
		    { 

		     	$start_date  =  $where['start_date'];
	            $end_date  =  $where['end_date'];

	            if($where['status'] == "Closed")
                {
	         
		     	$where3 = "DATE_FORMAT(`identity_result`.`closuredate`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}

		     	if($where['status'] == "Insufficiency")
                {
	         
		     	$where3 = "DATE_FORMAT(`identity_insuff`.`insuff_raised_date`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}
                
                $this->db->where($where3); 

		    } 


		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like('candidates_info.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('candidates_info.cmp_ref_no', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);

			$this->db->or_like('identity.identity_com_ref', $where['search']['value']);

			$this->db->or_like('identity.iniated_date', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where['search']['value']);
			
		}
				
		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 
            
            // $this->db->where('identity.vendor_id !=',0);
         
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

	public function get_all_identity_record($where_array)
	{
		$this->db->select("candidates_info.id as cands_id,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = identity.has_case_id) as executive_name,status.status_value as verfstatus,identity.id as identity_id,identity.has_case_id, identity.identity_com_ref,identity.doc_submited,identity.identity_re_open_date,identity.mode_of_veri,identity.id_number,identity.clientid,identity.street_address,identity.city,identity.pincode,identity.build_date,identity.state as state_id,identity_result.id as identity_result_id,identity_result.first_qc_approve,identity_result.first_qc_updated_on,identity_result.first_qu_reject_reason,identity_result.mode_of_verification,identity_result.remarks,identity_result.closuredate,identity.has_assigned_on,identity.iniated_date,clients.clientname,(select created_on from identity_activity_data where comp_table_id = identity.id  order by id desc limit 1) as last_activity_date,due_date,tat_status,identity_result.var_filter_status,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name");

		$this->db->from('identity');

		$this->db->join("identity_result",'identity_result.identity_id = identity.id');
		
		$this->db->join("candidates_info",'candidates_info.id = identity.candsid');
		
		$this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("status",'status.id = identity_result.verfstatus');

		$this->db->where($where_array);

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	
}
?>