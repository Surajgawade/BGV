<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Credit_report_model extends CI_Model
{
	function __construct()
    {
		//parent::__construct();

		$this->tableName = 'credit_report';

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

			$result = $this->db->update('credit_report_result', $arrdata);

			record_db_error($this->db->last_query());

			return $result;
	    }
	   
	} 


   public function select_insuff($where_array)
	{
		$this->db->select('*')->from('credit_report_insuff');

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

			$result = $this->db->update('credit_report_insuff', $arrdata);
			
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
			$where['credit_report_result.var_filter_status'] = $where_arry['status'];
		    }
		  
		}

		if(isset($where_arry['sub_status']) && $where_arry['sub_status'] != '' && $where_arry['sub_status'] != 0)	
		{
			$where['credit_report_result.verfstatus'] = $where_arry['sub_status'];
		}

		if(isset($where_arry['client_id']) &&  $where_arry['client_id'] != 0)	
		{
			$where['credit_report.clientid'] = $where_arry['client_id'];
		}
        if(isset($where_arry['filter_by_executive']) &&  $where_arry['filter_by_executive'] != 0)	
		{
		    if($where_arry['filter_by_executive'] != "All")
	      	{
            $where['credit_report.has_case_id'] = $where_arry['filter_by_executive'];
	      	}
        } 
         
		return $where;

	}


	public function get_all_credit_report_by_candidate_datatable($where,$columns)
	{
		$this->db->select("candidates_info.CandidateName,candidates_info.id candidates_info_id,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = credit_report.has_case_id) as user_name,status.status_value,credit_report.credit_report_com_ref,credit_report.mode_of_veri,credit_report_result.verfstatus,credit_report_result.first_qc_approve,credit_report_result.first_qc_updated_on,credit_report_result.first_qu_reject_reason,credit_report.id,credit_report.id as credit_report_id,credit_report.has_assigned_on,credit_report.iniated_date,clients.clientname,(select created_on from credit_report_activity_data where comp_table_id = credit_report.id  order by id desc limit 1) as last_activity_date,credit_report_result.remarks,due_date,tat_status,closuredate,id_number,credit_report_insuff.insuff_raised_date");

		$this->db->from('credit_report');

		$this->db->join("credit_report_result",'credit_report_result.credit_report_id = credit_report.id');
		
		$this->db->join("candidates_info",'candidates_info.id = credit_report.candsid');


	    $this->db->join("credit_report_insuff",'(credit_report_insuff.credit_report_id = credit_report.id AND  credit_report_insuff.status = 1 )','left');
		
		$this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("status",'status.id = credit_report_result.verfstatus');


		$this->db->where('credit_report.fill_by','2'); 
        
        $this->db->where('credit_report_result.verfstatus','18');

        $this->db->where('credit_report.candsid',$this->candidate_info['cands_info_id']); 
 

		$this->db->where($this->filter_where_cond($where));

		 if(isset($where['start_date']) &&  $where['start_date'] != '' && isset($where['end_date']) &&  $where['end_date'] != '')	
		    { 

		     	$start_date  =  $where['start_date'];
	            $end_date  =  $where['end_date'];

	            if($where['status'] == "Closed")
                {
	         
		     	$where3 = "DATE_FORMAT(`credit_report_result`.`closuredate`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}

		     	if($where['status'] == "Insufficiency")
                {
	         
		     	$where3 = "DATE_FORMAT(`credit_report_insuff`.`insuff_raised_date`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}
                
                $this->db->where($where3); 

		    } 


		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like('candidates_info.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('candidates_info.cmp_ref_no', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);

			$this->db->or_like('credit_report.credit_report_com_ref', $where['search']['value']);

			$this->db->or_like('credit_report.iniated_date', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where['search']['value']);

			
		}

		
		$this->db->limit($where['length'],$where['start']);
		
		
		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 
            
             //$this->db->where('credit_report.vendor_id !=',0);

         
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

		//print_r($this->db->last_query());
      
		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_all_credit_report_by_candidate_datatable_count($where,$columns)
	{
		$this->db->select("candidates_info.id as cands_id,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = credit_report.has_case_id) as user_name,status.status_value,credit_report.id as credit_report_id,credit_report.credit_report_com_ref,credit_report_result.verfstatus,credit_report_result.first_qc_approve,credit_report_result.first_qc_updated_on,credit_report_result.first_qu_reject_reason,credit_report.id,credit_report.has_assigned_on,credit_report.iniated_date,clients.clientname,(select created_on from credit_report_activity_data where comp_table_id = credit_report.id  order by id desc limit 1) as last_activity_date,credit_report_insuff.insuff_raised_date");

		$this->db->from('credit_report');

		$this->db->join("credit_report_result",'credit_report_result.credit_report_id = credit_report.id');
		
		$this->db->join("candidates_info",'candidates_info.id = credit_report.candsid');

	    $this->db->join("credit_report_insuff",'(credit_report_insuff.credit_report_id = credit_report.id AND  credit_report_insuff.status = 1 )','left');
		
		$this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("status",'status.id = credit_report_result.verfstatus');


		$this->db->where('credit_report.fill_by','2'); 
        
        $this->db->where('credit_report_result.verfstatus','18'); 

        $this->db->where('credit_report.candsid',$this->candidate_info['cands_info_id']); 


		$this->db->where($this->filter_where_cond($where));

		 if(isset($where['start_date']) &&  $where['start_date'] != '' && isset($where['end_date']) &&  $where['end_date'] != '')	
		    { 

		     	$start_date  =  $where['start_date'];
	            $end_date  =  $where['end_date'];

	            if($where['status'] == "Closed")
                {
	         
		     	$where3 = "DATE_FORMAT(`credit_report_result`.`closuredate`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}

		     	if($where['status'] == "Insufficiency")
                {
	         
		     	$where3 = "DATE_FORMAT(`credit_report_insuff`.`insuff_raised_date`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}
                
                $this->db->where($where3); 

		    } 


		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like('candidates_info.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('candidates_info.cmp_ref_no', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);

			$this->db->or_like('credit_report.credit_report_com_ref', $where['search']['value']);

			$this->db->or_like('credit_report.iniated_date', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where['search']['value']);

			
		}

				
		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 
            
            // $this->db->where('credit_report.vendor_id !=',0);

         
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
  
    public function get_all_credit_report_submit_by_candidate_datatable($where,$columns)
	{
		$this->db->select("candidates_info.CandidateName,candidates_info.id candidates_info_id,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = credit_report.has_case_id) as user_name,status.status_value,credit_report.credit_report_com_ref,credit_report.mode_of_veri,credit_report_result.verfstatus,credit_report_result.first_qc_approve,credit_report_result.first_qc_updated_on,credit_report_result.first_qu_reject_reason,credit_report.id,credit_report.id as credit_report_id,credit_report.has_assigned_on,credit_report.iniated_date,clients.clientname,(select created_on from credit_report_activity_data where comp_table_id = credit_report.id  order by id desc limit 1) as last_activity_date,credit_report_result.remarks,due_date,tat_status,closuredate,id_number,credit_report_insuff.insuff_raised_date");

		$this->db->from('credit_report');

		$this->db->join("credit_report_result",'credit_report_result.credit_report_id = credit_report.id');
		
		$this->db->join("candidates_info",'candidates_info.id = credit_report.candsid');


	    $this->db->join("credit_report_insuff",'(credit_report_insuff.credit_report_id = credit_report.id AND  credit_report_insuff.status = 1 )','left');
		
		$this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("status",'status.id = credit_report_result.verfstatus');


		$this->db->where('credit_report.fill_by','2'); 
        
        $this->db->where('credit_report_result.verfstatus !=','18'); 

        $this->db->where('credit_report.candsid',$this->candidate_info['cands_info_id']); 


		$this->db->where($this->filter_where_cond($where));

		 if(isset($where['start_date']) &&  $where['start_date'] != '' && isset($where['end_date']) &&  $where['end_date'] != '')	
		    { 

		     	$start_date  =  $where['start_date'];
	            $end_date  =  $where['end_date'];

	            if($where['status'] == "Closed")
                {
	         
		     	$where3 = "DATE_FORMAT(`credit_report_result`.`closuredate`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}

		     	if($where['status'] == "Insufficiency")
                {
	         
		     	$where3 = "DATE_FORMAT(`credit_report_insuff`.`insuff_raised_date`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}
                
                $this->db->where($where3); 

		    } 


		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like('candidates_info.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('candidates_info.cmp_ref_no', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);

			$this->db->or_like('credit_report.credit_report_com_ref', $where['search']['value']);

			$this->db->or_like('credit_report.iniated_date', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where['search']['value']);

			
		}

		
		$this->db->limit($where['length'],$where['start']);
		
		
		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 
            
             //$this->db->where('credit_report.vendor_id !=',0);

         
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

		//print_r($this->db->last_query());
      
		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_all_credit_report_submit_by_candidate_datatable_count($where,$columns)
	{
		$this->db->select("candidates_info.id as cands_id,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = credit_report.has_case_id) as user_name,status.status_value,credit_report.id as credit_report_id,credit_report.credit_report_com_ref,credit_report_result.verfstatus,credit_report_result.first_qc_approve,credit_report_result.first_qc_updated_on,credit_report_result.first_qu_reject_reason,credit_report.id,credit_report.has_assigned_on,credit_report.iniated_date,clients.clientname,(select created_on from credit_report_activity_data where comp_table_id = credit_report.id  order by id desc limit 1) as last_activity_date,credit_report_insuff.insuff_raised_date");

		$this->db->from('credit_report');

		$this->db->join("credit_report_result",'credit_report_result.credit_report_id = credit_report.id');
		
		$this->db->join("candidates_info",'candidates_info.id = credit_report.candsid');

	    $this->db->join("credit_report_insuff",'(credit_report_insuff.credit_report_id = credit_report.id AND  credit_report_insuff.status = 1 )','left');
		
		$this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("status",'status.id = credit_report_result.verfstatus');


		$this->db->where('credit_report.fill_by','2'); 
        
        $this->db->where('credit_report_result.verfstatus !=','18'); 
     
        $this->db->where('credit_report.candsid',$this->candidate_info['cands_info_id']); 

		$this->db->where($this->filter_where_cond($where));

		 if(isset($where['start_date']) &&  $where['start_date'] != '' && isset($where['end_date']) &&  $where['end_date'] != '')	
		    { 

		     	$start_date  =  $where['start_date'];
	            $end_date  =  $where['end_date'];

	            if($where['status'] == "Closed")
                {
	         
		     	$where3 = "DATE_FORMAT(`credit_report_result`.`closuredate`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}

		     	if($where['status'] == "Insufficiency")
                {
	         
		     	$where3 = "DATE_FORMAT(`credit_report_insuff`.`insuff_raised_date`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}
                
                $this->db->where($where3); 

		    } 


		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like('candidates_info.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('candidates_info.cmp_ref_no', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);

			$this->db->or_like('credit_report.credit_report_com_ref', $where['search']['value']);

			$this->db->or_like('credit_report.iniated_date', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where['search']['value']);

			
		}

				
		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 
            
            // $this->db->where('credit_report.vendor_id !=',0);

         
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

	public function  get_all_credit_report_record($where_array)
	{
		$this->db->select("candidates_info.id as cands_id,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = credit_report.has_case_id) as executive_name,status.status_value as verfstatus,credit_report.id as credit_report_id,credit_report.clientid,credit_report.has_case_id,credit_report.mode_of_veri,credit_report.build_date, credit_report.credit_report_com_ref,credit_report.doc_submited,credit_report.id_number, credit_report.street_address,credit_report.city,credit_report.pincode,credit_report.state as state_id,credit_report_result.id as credit_report_result_id,credit_report_result.first_qc_approve,credit_report_result.first_qc_updated_on,credit_report_result.first_qu_reject_reason,credit_report_result.mode_of_verification,credit_report_result.remarks,credit_report_result.closuredate,credit_report.credit_report_re_open_date,credit_report.has_assigned_on,credit_report_result.var_filter_status,credit_report.iniated_date,clients.clientname,(select created_on from credit_report_activity_data where comp_table_id = credit_report.id  order by id desc limit 1) as last_activity_date,due_date,tat_status,credit_report.state,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name");

		$this->db->from('credit_report');

		$this->db->join("credit_report_result",'credit_report_result.credit_report_id = credit_report.id');
		
		$this->db->join("candidates_info",'candidates_info.id = credit_report.candsid');
		
		$this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("status",'status.id = credit_report_result.verfstatus');

		$this->db->where($where_array);

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}



	
}
?>