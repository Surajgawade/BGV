<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pcc_model extends CI_Model
{
	function __construct()
    {
		//parent::__construct();

		$this->tableName = 'pcc';

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

			$result = $this->db->update('pcc_result', $arrdata);

			record_db_error($this->db->last_query());

			return $result;
	    }
	   
	}

	public function select_insuff($where_array)
	{
		$this->db->select('*')->from('pcc_insuff');

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

			$result = $this->db->update('pcc_insuff', $arrdata);
			
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
			$where['pcc_result.var_filter_status'] = $where_arry['status'];
		    }

		}

		if(isset($where_arry['sub_status']) && $where_arry['sub_status'] != '' && $where_arry['sub_status'] != 0)	
		{
			$where['pcc_result.verfstatus'] = $where_arry['sub_status'];
		}

		if(isset($where_arry['client_id']) &&  $where_arry['client_id'] != 0)	
		{
			$where['pcc.clientid'] = $where_arry['client_id'];
		}
		if(isset($where_arry['filter_by_executive']) &&  $where_arry['filter_by_executive'] != 0)	
		{

		   if($where_arry['filter_by_executive'] != "All")
	      	{
              $where['pcc.has_case_id'] = $where_arry['filter_by_executive'];
	      	}
	    }

		return $where;
	}


	public function get_all_pcc_record_datatable($where,$columns)
	{
		$this->db->select("candidates_info.id candidates_info_id,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = pcc.has_case_id) as user_name,status.status_value,pcc.pcc_com_ref,pcc_result.verfstatus,pcc.address_type, pcc.street_address,pcc.id as pcc_id,pcc.city,pcc.pincode,pcc.state,pcc.mode_of_veri,pcc_result.first_qc_approve,pcc_result.first_qc_updated_on,pcc_result.first_qu_reject_reason,pcc.id,pcc.has_assigned_on,pcc.iniated_date,clients.clientname,(select created_on from pcc_activity_data where comp_table_id = pcc.id  order by id desc limit 1) as last_activity_date,,closuredate,pcc_result.remarks,(select vendor_name from vendors where vendors.id = pcc.vendor_id) as vendor_name,due_date,tat_status,pcc_insuff.insuff_raised_date");

		$this->db->from('pcc');

		$this->db->join("pcc_result",'pcc_result.pcc_id = pcc.id');
		
		$this->db->join("candidates_info",'candidates_info.id = pcc.candsid');
		
		$this->db->join("clients",'clients.id = candidates_info.clientid');

	    $this->db->join("pcc_insuff",'(pcc_insuff.pcc_id = pcc.id AND  pcc_insuff.status = 1 )','left');


		$this->db->join("status",'status.id = pcc_result.verfstatus');

        $this->db->where('pcc.fill_by','2'); 
        
        $this->db->where('pcc_result.verfstatus','18');

        $this->db->where('pcc.candsid',$this->candidate_info['cands_info_id']); 
 
    
		$this->db->where($this->filter_where_cond($where));

		 if(isset($where['start_date']) &&  $where['start_date'] != '' && isset($where['end_date']) &&  $where['end_date'] != '')	
		    { 

		     	$start_date  =  $where['start_date'];
	            $end_date  =  $where['end_date'];

	            if($where['status'] == "Closed")
                {
	         
		     	$where3 = "DATE_FORMAT(`pcc_result`.`closuredate`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}

		     	if($where['status'] == "Insufficiency")
                {
	         
		     	$where3 = "DATE_FORMAT(`pcc_insuff`.`insuff_raised_date`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}
                
                $this->db->where($where3); 

		    } 


		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like('candidates_info.ClientRefNumber', $where['search']['value']);

		    $this->db->or_like('candidates_info.cmp_ref_no', $where['search']['value']);


			$this->db->or_like('clients.clientname', $where['search']['value']);

			$this->db->or_like('pcc.pcc_com_ref', $where['search']['value']);

			$this->db->or_like('pcc.iniated_date', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where['search']['value']);

			
		}
		
		$this->db->limit($where['length'],$where['start']);
		
		/*if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 

			$this->db->where('pcc.vendor_id !=',0);

			$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		}*/


		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 
            
            //$this->db->where('pcc.vendor_id !=',0);
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

	public function get_all_pcc_record_datatable_count($where,$columns)
	{
		$this->db->select("candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = pcc.has_case_id) as user_name,status.status_value,pcc.pcc_com_ref,pcc_result.verfstatus,pcc.address_type, pcc.street_address,pcc.city,pcc.pincode,pcc.state,pcc_result.first_qc_approve,pcc_result.first_qc_updated_on,pcc_result.first_qu_reject_reason,pcc.id,pcc.has_assigned_on,pcc.iniated_date,clients.clientname,(select created_on from pcc_activity_data where comp_table_id = pcc.id  order by id desc limit 1) as last_activity_date,pcc_insuff.insuff_raised_date");

		$this->db->from('pcc');

		$this->db->join("pcc_result",'pcc_result.pcc_id = pcc.id');
		
		$this->db->join("candidates_info",'candidates_info.id = pcc.candsid');
		
		$this->db->join("clients",'clients.id = candidates_info.clientid');

        $this->db->join("pcc_insuff",'(pcc_insuff.pcc_id = pcc.id AND  pcc_insuff.status = 1 )','left');

		$this->db->join("status",'status.id = pcc_result.verfstatus');

        $this->db->where('pcc.fill_by','2'); 
        
        $this->db->where('pcc_result.verfstatus','18');

        $this->db->where('pcc.candsid',$this->candidate_info['cands_info_id']); 
   
		
		$this->db->where($this->filter_where_cond($where));

		 if(isset($where['start_date']) &&  $where['start_date'] != '' && isset($where['end_date']) &&  $where['end_date'] != '')	
		    { 

		     	$start_date  =  $where['start_date'];
	            $end_date  =  $where['end_date'];

	            if($where['status'] == "Closed")
                {
	         
		     	$where3 = "DATE_FORMAT(`pcc_result`.`closuredate`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}

		     	if($where['status'] == "Insufficiency")
                {
	         
		     	$where3 = "DATE_FORMAT(`pcc_insuff`.`insuff_raised_date`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}
                
                $this->db->where($where3); 

		    } 


		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like('candidates_info.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('candidates_info.cmp_ref_no', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);

			$this->db->or_like('pcc.pcc_com_ref', $where['search']['value']);

			$this->db->or_like('pcc.iniated_date', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where['search']['value']);

			
		}
				
		
		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 
            
            //$this->db->where('pcc.vendor_id !=',0);
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

    public function get_all_pcc_submit_by_candidate_datatable($where,$columns)
	{
		$this->db->select("candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = pcc.has_case_id) as user_name,status.status_value,pcc.pcc_com_ref,pcc_result.verfstatus,pcc.address_type, pcc.street_address,pcc.id as pcc_id,pcc.city,pcc.pincode,pcc.state,pcc.mode_of_veri,pcc_result.first_qc_approve,pcc_result.first_qc_updated_on,pcc_result.first_qu_reject_reason,pcc.id,pcc.has_assigned_on,pcc.iniated_date,clients.clientname,(select created_on from pcc_activity_data where comp_table_id = pcc.id  order by id desc limit 1) as last_activity_date,,closuredate,pcc_result.remarks,(select vendor_name from vendors where vendors.id = pcc.vendor_id) as vendor_name,due_date,tat_status,pcc_insuff.insuff_raised_date");

		$this->db->from('pcc');

		$this->db->join("pcc_result",'pcc_result.pcc_id = pcc.id');
		
		$this->db->join("candidates_info",'candidates_info.id = pcc.candsid');
		
		$this->db->join("clients",'clients.id = candidates_info.clientid');

	    $this->db->join("pcc_insuff",'(pcc_insuff.pcc_id = pcc.id AND  pcc_insuff.status = 1 )','left');


		$this->db->join("status",'status.id = pcc_result.verfstatus');

        $this->db->where('pcc.fill_by','2'); 
        
        $this->db->where('pcc_result.verfstatus !=','18'); 

        $this->db->where('pcc.candsid',$this->candidate_info['cands_info_id']); 

    
		$this->db->where($this->filter_where_cond($where));

		 if(isset($where['start_date']) &&  $where['start_date'] != '' && isset($where['end_date']) &&  $where['end_date'] != '')	
		    { 

		     	$start_date  =  $where['start_date'];
	            $end_date  =  $where['end_date'];

	            if($where['status'] == "Closed")
                {
	         
		     	$where3 = "DATE_FORMAT(`pcc_result`.`closuredate`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}

		     	if($where['status'] == "Insufficiency")
                {
	         
		     	$where3 = "DATE_FORMAT(`pcc_insuff`.`insuff_raised_date`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}
                
                $this->db->where($where3); 

		    } 


		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like('candidates_info.ClientRefNumber', $where['search']['value']);

		    $this->db->or_like('candidates_info.cmp_ref_no', $where['search']['value']);


			$this->db->or_like('clients.clientname', $where['search']['value']);

			$this->db->or_like('pcc.pcc_com_ref', $where['search']['value']);

			$this->db->or_like('pcc.iniated_date', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where['search']['value']);

			
		}
		
		$this->db->limit($where['length'],$where['start']);
		
		/*if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 

			$this->db->where('pcc.vendor_id !=',0);

			$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		}*/


		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 
            
            //$this->db->where('pcc.vendor_id !=',0);
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

	public function get_all_pcc_submit_by_candidate_datatable_count($where,$columns)
	{
		$this->db->select("candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = pcc.has_case_id) as user_name,status.status_value,pcc.pcc_com_ref,pcc_result.verfstatus,pcc.address_type, pcc.street_address,pcc.city,pcc.pincode,pcc.state,pcc_result.first_qc_approve,pcc_result.first_qc_updated_on,pcc_result.first_qu_reject_reason,pcc.id,pcc.has_assigned_on,pcc.iniated_date,clients.clientname,(select created_on from pcc_activity_data where comp_table_id = pcc.id  order by id desc limit 1) as last_activity_date,pcc_insuff.insuff_raised_date");

		$this->db->from('pcc');

		$this->db->join("pcc_result",'pcc_result.pcc_id = pcc.id');
		
		$this->db->join("candidates_info",'candidates_info.id = pcc.candsid');
		
		$this->db->join("clients",'clients.id = candidates_info.clientid');

        $this->db->join("pcc_insuff",'(pcc_insuff.pcc_id = pcc.id AND  pcc_insuff.status = 1 )','left');

		$this->db->join("status",'status.id = pcc_result.verfstatus');

        $this->db->where('pcc.fill_by','2'); 
        
        $this->db->where('pcc_result.verfstatus !=','18');

        $this->db->where('pcc.candsid',$this->candidate_info['cands_info_id']); 
   
		
		$this->db->where($this->filter_where_cond($where));

		 if(isset($where['start_date']) &&  $where['start_date'] != '' && isset($where['end_date']) &&  $where['end_date'] != '')	
		    { 

		     	$start_date  =  $where['start_date'];
	            $end_date  =  $where['end_date'];

	            if($where['status'] == "Closed")
                {
	         
		     	$where3 = "DATE_FORMAT(`pcc_result`.`closuredate`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}

		     	if($where['status'] == "Insufficiency")
                {
	         
		     	$where3 = "DATE_FORMAT(`pcc_insuff`.`insuff_raised_date`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}
                
                $this->db->where($where3); 

		    } 


		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like('candidates_info.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('candidates_info.cmp_ref_no', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);

			$this->db->or_like('pcc.pcc_com_ref', $where['search']['value']);

			$this->db->or_like('pcc.iniated_date', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where['search']['value']);

			
		}
				
		
		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 
            
            //$this->db->where('pcc.vendor_id !=',0);
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

	
}
?>