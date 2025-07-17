<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reference_model extends CI_Model
{
	function __construct()
    {
		//parent::__construct();

		$this->tableName = 'reference';

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

			$result = $this->db->update('reference_result', $arrdata);

			record_db_error($this->db->last_query());

			return $result;
	    }
	   
	} 

	public function select_insuff($where_array)
	{
		$this->db->select('*')->from('reference_insuff');

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

			$result = $this->db->update('reference_insuff', $arrdata);
			
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
			$where['reference_result.var_filter_status'] = $where_arry['status'];
		    }


		}

		if(isset($where_arry['sub_status']) && $where_arry['sub_status'] != '' && $where_arry['sub_status'] != 0)	
		{
			$where['reference_result.verfstatus'] = $where_arry['sub_status'];
		}

		if(isset($where_arry['client_id']) &&  $where_arry['client_id'] != 0)	
		{
			$where['reference.clientid'] = $where_arry['client_id'];
		}
		if(isset($where_arry['filter_by_executive']) &&  $where_arry['filter_by_executive'] != 0)	
		{

		    if($where_arry['filter_by_executive'] != "All")
	      	{
            $where['reference.has_case_id'] = $where_arry['filter_by_executive'];
	      	}

	    } 	

		return $where;

	}

    public function get_all_reference_record_datatable($where,$columns)
	{
		$this->db->select("candidates_info.id as candidates_info_id, candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = reference.has_case_id) as executive_name,status.status_value,reference.id,reference.reference_com_ref,reference_result.verfstatus,reference_result.first_qc_approve,reference.mode_of_veri,reference_result.first_qc_updated_on,reference_result.first_qu_reject_reason,reference.id as reference_id,reference.has_assigned_on,reference.iniated_date,clients.clientname,due_date,tat_status,reference_result.closuredate,reference.name_of_reference,(select created_on from reference_activity_data where comp_table_id = reference.id order by id desc limit 1) as last_activity_date,(select vendor_name from vendors where vendors.id = reference.vendor_id) as vendor_name,reference_insuff.insuff_raised_date");

		$this->db->from('reference');

		$this->db->join("reference_result",'reference_result.reference_id = reference.id');
		
		$this->db->join("candidates_info",'candidates_info.id = reference.candsid');
		
		$this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("reference_insuff",'(reference_insuff.reference_id = reference.id AND  reference_insuff.status = 1 )','left');


		$this->db->join("status",'status.id = reference_result.verfstatus');

		$this->db->where('reference.fill_by','2'); 
        
        $this->db->where('reference_result.verfstatus','18'); 

        $this->db->where('reference.candsid',$this->candidate_info['cands_info_id']); 


	    $this->db->where($this->filter_where_cond($where));


		 if(isset($where['start_date']) &&  $where['start_date'] != '' && isset($where['end_date']) &&  $where['end_date'] != '')	
		    { 

		     	$start_date  =  $where['start_date'];
	            $end_date  =  $where['end_date'];

	            if($where['status'] == "Closed")
                {
	         
		     	$where3 = "DATE_FORMAT(`reference_result`.`closuredate`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}

		     	if($where['status'] == "Insufficiency")
                {
	         
		     	$where3 = "DATE_FORMAT(`reference_insuff`.`insuff_raised_date`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}
                
                $this->db->where($where3); 

		    } 


		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like('candidates_info.ClientRefNumber', $where['search']['value']);

	        $this->db->or_like('candidates_info.cmp_ref_no', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);

			$this->db->or_like('reference.reference_com_ref', $where['search']['value']);

			$this->db->or_like('reference.iniated_date', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where['search']['value']);

		}
		
		$this->db->limit($where['length'],$where['start']);
		

		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 
            
         
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



	public function get_all_reference_record_datatable_count($where,$columns)
	{
		$this->db->select("candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = reference.has_case_id) as executive_name,status.status_value,reference.reference_com_ref,reference_result.verfstatus,reference_result.first_qc_approve,reference_result.first_qc_updated_on,reference_result.first_qu_reject_reason,reference.id,reference.has_assigned_on,reference.iniated_date,clients.clientname,reference_insuff.insuff_raised_date");

		$this->db->from('reference');

		$this->db->join("reference_result",'reference_result.reference_id = reference.id');
		
		$this->db->join("candidates_info",'candidates_info.id = reference.candsid');
		
		$this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("reference_insuff",'(reference_insuff.reference_id = reference.id AND  reference_insuff.status = 1 )','left');


		$this->db->join("status",'status.id = reference_result.verfstatus');

		$this->db->where('reference.fill_by','2'); 
        
        $this->db->where('reference_result.verfstatus','18'); 
    
        $this->db->where('reference.candsid',$this->candidate_info['cands_info_id']); 


		$this->db->where($this->filter_where_cond($where));

       if(isset($where['start_date']) &&  $where['start_date'] != '' && isset($where['end_date']) &&  $where['end_date'] != '')	
		    { 

		     	$start_date  =  $where['start_date'];
	            $end_date  =  $where['end_date'];

	            if($where['status'] == "Closed")
                {
	         
		     	$where3 = "DATE_FORMAT(`reference_result`.`closuredate`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}

		     	if($where['status'] == "Insufficiency")
                {
	         
		     	$where3 = "DATE_FORMAT(`reference_insuff`.`insuff_raised_date`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}
                
                $this->db->where($where3); 

		    } 


		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like('candidates_info.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);

			$this->db->or_like('reference.reference_com_ref', $where['search']['value']);

			$this->db->or_like('reference.iniated_date', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where['search']['value']);

		}
				
		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 
            
         
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

	public function get_all_reference_submit_record_datatable($where,$columns)
	{
		$this->db->select("candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = reference.has_case_id) as executive_name,status.status_value,reference.id,reference.reference_com_ref,reference_result.verfstatus,reference_result.first_qc_approve,reference.mode_of_veri,reference_result.first_qc_updated_on,reference_result.first_qu_reject_reason,reference.id,reference.has_assigned_on,reference.iniated_date,clients.clientname,due_date,tat_status,reference_result.closuredate,reference.name_of_reference,(select created_on from reference_activity_data where comp_table_id = reference.id order by id desc limit 1) as last_activity_date,(select vendor_name from vendors where vendors.id = reference.vendor_id) as vendor_name,reference_insuff.insuff_raised_date");

		$this->db->from('reference');

		$this->db->join("reference_result",'reference_result.reference_id = reference.id');
		
		$this->db->join("candidates_info",'candidates_info.id = reference.candsid');
		
		$this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("reference_insuff",'(reference_insuff.reference_id = reference.id AND  reference_insuff.status = 1 )','left');


		$this->db->join("status",'status.id = reference_result.verfstatus');

		$this->db->where('reference.fill_by','2'); 
        
        $this->db->where('reference_result.verfstatus !=','18'); 

        $this->db->where('reference.candsid',$this->candidate_info['cands_info_id']); 

	    $this->db->where($this->filter_where_cond($where));


		 if(isset($where['start_date']) &&  $where['start_date'] != '' && isset($where['end_date']) &&  $where['end_date'] != '')	
		    { 

		     	$start_date  =  $where['start_date'];
	            $end_date  =  $where['end_date'];

	            if($where['status'] == "Closed")
                {
	         
		     	$where3 = "DATE_FORMAT(`reference_result`.`closuredate`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}

		     	if($where['status'] == "Insufficiency")
                {
	         
		     	$where3 = "DATE_FORMAT(`reference_insuff`.`insuff_raised_date`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}
                
                $this->db->where($where3); 

		    } 


		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like('candidates_info.ClientRefNumber', $where['search']['value']);

	        $this->db->or_like('candidates_info.cmp_ref_no', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);

			$this->db->or_like('reference.reference_com_ref', $where['search']['value']);

			$this->db->or_like('reference.iniated_date', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where['search']['value']);

		}
		
		$this->db->limit($where['length'],$where['start']);
		

		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 
            
         
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



	public function get_all_reference_submit_record_datatable_count($where,$columns)
	{
		$this->db->select("candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = reference.has_case_id) as executive_name,status.status_value,reference.reference_com_ref,reference_result.verfstatus,reference_result.first_qc_approve,reference_result.first_qc_updated_on,reference_result.first_qu_reject_reason,reference.id,reference.has_assigned_on,reference.iniated_date,clients.clientname,reference_insuff.insuff_raised_date");

		$this->db->from('reference');

		$this->db->join("reference_result",'reference_result.reference_id = reference.id');
		
		$this->db->join("candidates_info",'candidates_info.id = reference.candsid');
		
		$this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("reference_insuff",'(reference_insuff.reference_id = reference.id AND  reference_insuff.status = 1 )','left');


		$this->db->join("status",'status.id = reference_result.verfstatus');

		$this->db->where('reference.fill_by','2'); 
        
        $this->db->where('reference_result.verfstatus !=','18'); 
         
        $this->db->where('reference.candsid',$this->candidate_info['cands_info_id']);

		$this->db->where($this->filter_where_cond($where));

       if(isset($where['start_date']) &&  $where['start_date'] != '' && isset($where['end_date']) &&  $where['end_date'] != '')	
		    { 

		     	$start_date  =  $where['start_date'];
	            $end_date  =  $where['end_date'];

	            if($where['status'] == "Closed")
                {
	         
		     	$where3 = "DATE_FORMAT(`reference_result`.`closuredate`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}

		     	if($where['status'] == "Insufficiency")
                {
	         
		     	$where3 = "DATE_FORMAT(`reference_insuff`.`insuff_raised_date`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}
                
                $this->db->where($where3); 

		    } 


		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like('candidates_info.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);

			$this->db->or_like('reference.reference_com_ref', $where['search']['value']);

			$this->db->or_like('reference.iniated_date', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where['search']['value']);

		}
				
		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 
            
         
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

	public function get_all_reference_record($where_array)
	{
		$this->db->select("candidates_info.id as cands_id,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = reference.has_case_id) as executive_name,reference.has_case_id,status.status_value as verfstatus,reference.id as reference_id,reference.reference_re_open_date,reference.name_of_reference,reference.reference_com_ref,reference.designation, reference.contact_no,reference.email_id,reference.build_date,reference_result.id as reference_result_id,reference.clientid,reference_result.first_qc_approve,reference_result.var_filter_status,reference_result.mode_of_verification,closuredate,reference_result.first_qc_updated_on,reference_result.first_qu_reject_reason,reference.id,reference.has_assigned_on,reference.iniated_date,(select created_on from reference_activity_data where comp_table_id = reference.id order by id desc limit 1) as last_activity_date,due_date,tat_status,(select clientname from clients where clients.id = candidates_info.clientid) as clientname,reference_result.remarks,handle_pressure,handle_pressure_value,attendance,attendance_value,integrity,integrity_value,leadership_skills,leadership_skills_value,responsibilities,responsibilities_value,achievements,achievements_value,strengths,strengths_value,team_player,team_player_value,weakness,weakness_value,overall_performance,additional_comments,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,");

		$this->db->from('reference');

		$this->db->join("reference_result",'reference_result.reference_id = reference.id');
		
		$this->db->join("candidates_info",'candidates_info.id = reference.candsid');
		
		$this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("status",'status.id = reference_result.verfstatus');

		$this->db->where($where_array);

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	
}
?>