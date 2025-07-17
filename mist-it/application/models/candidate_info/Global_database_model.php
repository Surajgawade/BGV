<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Global_database_model extends CI_Model
{
	function __construct()
    {
		//parent::__construct();

		$this->tableName = 'glodbver';

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

			$result = $this->db->update('glodbver_result', $arrdata);

			record_db_error($this->db->last_query());

			return $result;
	    }
	   
	} 

	public function select_insuff($where_array)
	{
		$this->db->select('*')->from('glodbver_insuff');

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

			$result = $this->db->update('glodbver_insuff', $arrdata);
			
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
			$where['glodbver_result.var_filter_status'] = $where_arry['status'];
		    }


		}

		if(isset($where_arry['sub_status']) && $where_arry['sub_status'] != '' && $where_arry['sub_status'] != 0)	
		{
			$where['glodbver_result.verfstatus'] = $where_arry['sub_status'];
		}

		if(isset($where_arry['client_id']) &&  $where_arry['client_id'] != 0)	
		{
			$where['glodbver.clientid'] = $where_arry['client_id'];
		}
        
        if(isset($where_arry['filter_by_executive']) &&  $where_arry['filter_by_executive'] != 0)	
		{
			if($where_arry['filter_by_executive'] != "All")
		    {
	          $where['glodbver.has_case_id'] = $where_arry['filter_by_executive'];
		    }
		}

		return $where;

	}

	public function get_all_global_record_datatable($where,$columns)
	{
		$this->db->select("candidates_info.id as candidates_info_id,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = glodbver.has_case_id) as executive_name,status.status_value,glodbver.global_com_ref,glodbver_result.verfstatus,glodbver.mode_of_veri,glodbver.id as glodbver_id,glodbver.street_address,glodbver_result.first_qc_approve,glodbver_result.first_qc_updated_on,glodbver_result.first_qu_reject_reason,glodbver.id,glodbver.has_assigned_on,glodbver.iniated_date,clients.clientname,due_date,tat_status,glodbver_result.closuredate,glodbver_result.remarks,(select created_on from glodbver_activity_data where comp_table_id = glodbver.id order by id desc limit 1) as last_activity_date,(select vendor_name from vendors where vendors.id = glodbver.vendor_id) as vendor_name,glodbver_insuff.insuff_raised_date");

		$this->db->from('glodbver');

		$this->db->join("glodbver_result",'glodbver_result.glodbver_id = glodbver.id');
		
		$this->db->join("candidates_info",'candidates_info.id = glodbver.candsid');

	    $this->db->join("glodbver_insuff",'(glodbver_insuff.glodbver_id = glodbver.id AND  glodbver_insuff.status = 1 )','left');

		$this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("status",'status.id = glodbver_result.verfstatus');

		$this->db->where('glodbver.fill_by','2'); 
        
        $this->db->where('glodbver_result.verfstatus','18'); 
   
        $this->db->where('glodbver.candsid',$this->candidate_info['cands_info_id']); 


		$this->db->where($this->filter_where_cond($where));

		 if(isset($where['start_date']) &&  $where['start_date'] != '' && isset($where['end_date']) &&  $where['end_date'] != '')	
		    { 

		     	$start_date  =  $where['start_date'];
	            $end_date  =  $where['end_date'];

	            if($where['status'] == "Closed")
                {
	         
		     	$where3 = "DATE_FORMAT(`glodbver_result`.`closuredate`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}

		     	if($where['status'] == "Insufficiency")
                {
	         
		     	$where3 = "DATE_FORMAT(`glodbver_insuff`.`insuff_raised_date`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}
                
                $this->db->where($where3); 

		    } 


		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like('candidates_info.ClientRefNumber', $where['search']['value']);

		    $this->db->or_like('candidates_info.cmp_ref_no', $where['search']['value']);


			$this->db->or_like('clients.clientname', $where['search']['value']);

			$this->db->or_like('glodbver.global_com_ref', $where['search']['value']);

			$this->db->or_like('glodbver.iniated_date', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where['search']['value']);
	
		}
		
		$this->db->limit($where['length'],$where['start']);
		
		/*if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 

			$this->db->where('glodbver.vendor_id !=',0);

			$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		}*/

		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 
            
            //$this->db->where('glodbver.vendor_id !=',0);

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

	public function get_all_global_record_datatable_count($where,$columns)
	{
		$this->db->select("candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = glodbver.has_case_id) as executive_name,status.status_value,glodbver.global_com_ref,glodbver.address_type,glodbver_result.verfstatus,glodbver.street_address,glodbver.city,glodbver.pincode,glodbver.state,glodbver_result.first_qc_approve,glodbver_result.first_qc_updated_on,glodbver_result.first_qu_reject_reason,glodbver.id,glodbver.has_assigned_on,glodbver.iniated_date,clients.clientname,tat_status,(select created_on from glodbver_activity_data where comp_table_id = glodbver.id order by id desc limit 1) as last_activity_date,glodbver_insuff.insuff_raised_date");

		$this->db->from('glodbver');

		$this->db->join("glodbver_result",'glodbver_result.glodbver_id = glodbver.id');
		
		$this->db->join("candidates_info",'candidates_info.id = glodbver.candsid');

		$this->db->join("glodbver_insuff",'(glodbver_insuff.glodbver_id = glodbver.id AND  glodbver_insuff.status = 1 )','left');

		
		$this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("status",'status.id = glodbver_result.verfstatus');

	    $this->db->where('glodbver.fill_by','2'); 
        
        $this->db->where('glodbver_result.verfstatus','18'); 

        $this->db->where('glodbver.candsid',$this->candidate_info['cands_info_id']); 


		$this->db->where($this->filter_where_cond($where));

		 if(isset($where['start_date']) &&  $where['start_date'] != '' && isset($where['end_date']) &&  $where['end_date'] != '')	
		    { 

		     	$start_date  =  $where['start_date'];
	            $end_date  =  $where['end_date'];

	            if($where['status'] == "Closed")
                {
	         
		     	$where3 = "DATE_FORMAT(`glodbver_result`.`closuredate`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}

		     	if($where['status'] == "Insufficiency")
                {
	         
		     	$where3 = "DATE_FORMAT(`glodbver_insuff`.`insuff_raised_date`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}
                
                $this->db->where($where3); 

		    } 


		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like('candidates_info.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('candidates_info.cmp_ref_no', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);

			$this->db->or_like('glodbver.global_com_ref', $where['search']['value']);

			$this->db->or_like('glodbver.iniated_date', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where['search']['value']);
	
		}
		
		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 
            
           // $this->db->where('glodbver.vendor_id !=',0);

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
   

	public function get_all_global_record_submit_datatable($where,$columns)
	{
		$this->db->select("candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = glodbver.has_case_id) as executive_name,status.status_value,glodbver.global_com_ref,glodbver_result.verfstatus,glodbver.mode_of_veri,glodbver.id as glodbver_id,glodbver.street_address,glodbver_result.first_qc_approve,glodbver_result.first_qc_updated_on,glodbver_result.first_qu_reject_reason,glodbver.id,glodbver.has_assigned_on,glodbver.iniated_date,clients.clientname,due_date,tat_status,glodbver_result.closuredate,glodbver_result.remarks,(select created_on from glodbver_activity_data where comp_table_id = glodbver.id order by id desc limit 1) as last_activity_date,(select vendor_name from vendors where vendors.id = glodbver.vendor_id) as vendor_name,glodbver_insuff.insuff_raised_date");

		$this->db->from('glodbver');

		$this->db->join("glodbver_result",'glodbver_result.glodbver_id = glodbver.id');
		
		$this->db->join("candidates_info",'candidates_info.id = glodbver.candsid');

	    $this->db->join("glodbver_insuff",'(glodbver_insuff.glodbver_id = glodbver.id AND  glodbver_insuff.status = 1 )','left');

		$this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("status",'status.id = glodbver_result.verfstatus');

		$this->db->where('glodbver.fill_by','2'); 
        
        $this->db->where('glodbver_result.verfstatus !=','18'); 

        $this->db->where('glodbver.candsid',$this->candidate_info['cands_info_id']); 


		$this->db->where($this->filter_where_cond($where));

		 if(isset($where['start_date']) &&  $where['start_date'] != '' && isset($where['end_date']) &&  $where['end_date'] != '')	
		    { 

		     	$start_date  =  $where['start_date'];
	            $end_date  =  $where['end_date'];

	            if($where['status'] == "Closed")
                {
	         
		     	$where3 = "DATE_FORMAT(`glodbver_result`.`closuredate`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}

		     	if($where['status'] == "Insufficiency")
                {
	         
		     	$where3 = "DATE_FORMAT(`glodbver_insuff`.`insuff_raised_date`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}
                
                $this->db->where($where3); 

		    } 


		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like('candidates_info.ClientRefNumber', $where['search']['value']);

		    $this->db->or_like('candidates_info.cmp_ref_no', $where['search']['value']);


			$this->db->or_like('clients.clientname', $where['search']['value']);

			$this->db->or_like('glodbver.global_com_ref', $where['search']['value']);

			$this->db->or_like('glodbver.iniated_date', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where['search']['value']);
	
		}
		
		$this->db->limit($where['length'],$where['start']);
		
		/*if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 

			$this->db->where('glodbver.vendor_id !=',0);

			$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		}*/

		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 
            
            //$this->db->where('glodbver.vendor_id !=',0);

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

	public function get_all_global_record_submit_datatable_count($where,$columns)
	{
		$this->db->select("candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = glodbver.has_case_id) as executive_name,status.status_value,glodbver.global_com_ref,glodbver.address_type,glodbver_result.verfstatus,glodbver.street_address,glodbver.city,glodbver.pincode,glodbver.state,glodbver_result.first_qc_approve,glodbver_result.first_qc_updated_on,glodbver_result.first_qu_reject_reason,glodbver.id,glodbver.has_assigned_on,glodbver.iniated_date,clients.clientname,tat_status,(select created_on from glodbver_activity_data where comp_table_id = glodbver.id order by id desc limit 1) as last_activity_date,glodbver_insuff.insuff_raised_date");

		$this->db->from('glodbver');

		$this->db->join("glodbver_result",'glodbver_result.glodbver_id = glodbver.id');
		
		$this->db->join("candidates_info",'candidates_info.id = glodbver.candsid');

		$this->db->join("glodbver_insuff",'(glodbver_insuff.glodbver_id = glodbver.id AND  glodbver_insuff.status = 1 )','left');

		
		$this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("status",'status.id = glodbver_result.verfstatus');

	    $this->db->where('glodbver.fill_by','2'); 
        
        $this->db->where('glodbver_result.verfstatus !=','18'); 

        $this->db->where('glodbver.candsid',$this->candidate_info['cands_info_id']); 

		$this->db->where($this->filter_where_cond($where));

		 if(isset($where['start_date']) &&  $where['start_date'] != '' && isset($where['end_date']) &&  $where['end_date'] != '')	
		    { 

		     	$start_date  =  $where['start_date'];
	            $end_date  =  $where['end_date'];

	            if($where['status'] == "Closed")
                {
	         
		     	$where3 = "DATE_FORMAT(`glodbver_result`.`closuredate`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}

		     	if($where['status'] == "Insufficiency")
                {
	         
		     	$where3 = "DATE_FORMAT(`glodbver_insuff`.`insuff_raised_date`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}
                
                $this->db->where($where3); 

		    } 


		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like('candidates_info.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('candidates_info.cmp_ref_no', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);

			$this->db->or_like('glodbver.global_com_ref', $where['search']['value']);

			$this->db->or_like('glodbver.iniated_date', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where['search']['value']);
	
		}
		
		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 
            
           // $this->db->where('glodbver.vendor_id !=',0);

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
		$this->db->select('mode_of_verification');

		$this->db->from('clients_details');
		
		$this->db->where($where);

        $this->db->limit(1);  

		$result  = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_all_global_record($where_array)
	{
		$this->db->select("candidates_info.id as cands_id,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = glodbver.has_case_id) as executive_name,glodbver.has_case_id,
			glodbver.clientid,status.status_value as verfstatus,glodbver.id as glodbver_id, glodbver.global_com_ref,glodbver.address_type, glodbver.state as state_id,glodbver.build_date, glodbver.street_address,glodbver.city,glodbver.glodbver_re_open_date,glodbver.mode_of_veri,glodbver.pincode,glodbver_result.id as glodbver_result_id,glodbver_result.first_qc_approve,glodbver_result.var_filter_status,glodbver_result.first_qc_updated_on,glodbver_result.first_qu_reject_reason,glodbver_result.verified_date,glodbver.id,glodbver.has_assigned_on,glodbver.iniated_date,,due_date,tat_status,(select created_on from glodbver_activity_data where comp_table_id = glodbver.id order by id desc limit 1) as last_activity_date,(select clientname from clients where clients.id = candidates_info.clientid) as clientname,glodbver.state,mode_of_verification,closuredate,glodbver_result.remarks,(select id from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_id,(select id from entity_package where entity_package.id = candidates_info.package limit 1) as package_id");

		$this->db->from('glodbver');

		$this->db->join("glodbver_result",'glodbver_result.glodbver_id = glodbver.id');
		
		$this->db->join("candidates_info",'candidates_info.id = glodbver.candsid');
		
		$this->db->join("status",'status.id = glodbver_result.verfstatus');

		$this->db->where($where_array);

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}


	
}
?>