<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Address_model extends CI_Model
{
	function __construct()
    {
		//parent::__construct();

		$this->tableName = 'addrver';

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

			$result = $this->db->update('addrverres', $arrdata);

			record_db_error($this->db->last_query());

			return $result;
	    }
	   
	} 

	public function select_insuff($where_array)
	{
		$this->db->select('*')->from('addrver_insuff');

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

			$result = $this->db->update('addrver_insuff', $arrdata);
			record_db_error($this->db->last_query());
			
			return $result;
	    }
	  
	} 

    protected function filter_where_cond($where_arry)
	{
		$where1 = array();
  
		if(isset($where_arry['status']) &&  $where_arry['status'] != '')	
		{ 
            if($where_arry['status'] != 'All')
			{
			  $where1['addrverres.var_filter_status'] = $where_arry['status'];
	      	}
	      	
     	}

		if(isset($where_arry['sub_status']) &&  $where_arry['sub_status'] != '' &&  $where_arry['sub_status'] != 0)	
		{
			$where1['addrverres.verfstatus'] = $where_arry['sub_status'];
		}

		if(isset($where_arry['client_id']) &&  $where_arry['client_id'] != 0)	
		{
			$where1['addrver.clientid'] = $where_arry['client_id'];

		}
		if(isset($where_arry['status']) &&  $where_arry['status'] != '')	
		{ 
            if($where_arry['status'] != 'All')
			{
			  $where1['addrverres.var_filter_status'] = $where_arry['status'];
	      	}
	      	
     	}
     	if(isset($where_arry['filter_by_executive']) &&  $where_arry['filter_by_executive'] != 0)	
		{ 
            if($where_arry['filter_by_executive'] != 'All')
			{
			  $where1['addrver.has_case_id'] = $where_arry['filter_by_executive'];
	      	}
	      	
     	}

 
		return $where1;
	}

	public function get_all_addrs_by_candidate_datatable($where1,$columns)
	{
         
   		$this->db->select("candidates_info.id as candidates_info_id, candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,addrver.id,addrver.mod_of_veri,addrver.add_com_ref,clients.clientname,addrver.address,addrver.city,addrver.pincode, addrver.state ,addrver.iniated_date,status.status_value,user_profile.user_name,addrverres.first_qc_approve,addrverres.verfstatus,addrverres.first_qc_updated_on,addrverres.first_qu_reject_reason,(select created_on from address_activity_data where comp_table_id = addrver.id order by id desc limit 1) as last_activity_date,due_date,tat_status,(select vendor_name from vendors where vendors.id = addrver.vendor_id) as vendor_name,addrver_insuff.insuff_raised_date");
       
		$this->db->from('addrver');

		$this->db->join("user_profile",'user_profile.id = addrver.has_case_id','left');

		$this->db->join("candidates_info",'candidates_info.id = addrver.candsid');

		$this->db->join("clients",'clients.id = addrver.clientid');
		
		$this->db->join("addrverres",'addrverres.addrverid = addrver.id','left');


		$this->db->join("addrver_insuff",'(addrver_insuff.addrverid = addrver.id AND  addrver_insuff.status = 1 )','left');

		$this->db->join("status",'status.id = addrverres.verfstatus','left');

        $this->db->where($this->filter_where_cond($where1)); 

        $this->db->where('addrver.fill_by','2'); 
        
        $this->db->where('addrverres.verfstatus','18');

        $this->db->where('addrver.candsid',$this->candidate_info['cands_info_id']); 


            if(isset($where1['start_date']) &&  $where1['start_date'] != '' && isset($where1['end_date']) &&  $where1['end_date'] != '')	
		    { 

		     	$start_date  =  $where1['start_date'];
	            $end_date  =  $where1['end_date'];

	            if($where1['status'] == "Closed")
                {
	         
		     	$where3 = "DATE_FORMAT(`addrverres`.`closuredate`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}

		     	if($where1['status'] == "Insufficiency")
                {
	         
		     	$where3 = "DATE_FORMAT(`addrver_insuff`.`insuff_raised_date`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}
                
                $this->db->where($where3); 

		    } 
     
       
         
		if(is_array($where1) && $where1['search']['value'] != "" )
		{
			$this->db->like('candidates_info.cmp_ref_no', $where1['search']['value']);

			$this->db->or_like('candidates_info.ClientRefNumber', $where1['search']['value']);

			$this->db->or_like('clients.clientname', $where1['search']['value']);

			$this->db->or_like('addrver.add_com_ref', $where1['search']['value']);

			$this->db->or_like('addrver.iniated_date', $where1['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where1['search']['value']);

			$this->db->or_like('addrver.address', $where1['search']['value']);

			$this->db->or_like('addrver.city', $where1['search']['value']);

            $this->db->or_like('addrver.state', $where1['search']['value']);

		}

		if(!empty($where1['order']))
		{
			$column_name_index = $where1['order'][0]['column'];
			$order_by = $where1['order'][0]['dir']; 
            
            //$this->db->where('addrver.vendor_id !=',0);

            if(!empty($column_name_index))
			{

			  $this->db->order_by($where1['columns'][$column_name_index]['data'],$order_by);
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
		
		$this->db->limit($where1['length'],$where1['start']);

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}
   
	public function get_all_addrs_by_candidate_datatable_count($where1,$columns)
	{
         
		$this->db->select("candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,addrver.id,addrver.mod_of_veri,addrver.add_com_ref,clients.clientname,'' as vendor_name,addrver.address,addrver.city,addrver.pincode,addrver.state,addrver.iniated_date,status.status_value,user_profile.user_name,addrverres.first_qc_approve,addrverres.verfstatus,addrverres.first_qc_updated_on,addrverres.first_qu_reject_reason,(select created_on from address_activity_data where comp_table_id = addrver.id  order by id desc limit 1) as last_activity_date");

		$this->db->from('addrver');

		$this->db->join("user_profile",'user_profile.id = addrver.has_case_id','left');

		$this->db->join("candidates_info",'candidates_info.id = addrver.candsid');

		$this->db->join("clients",'clients.id = addrver.clientid');

		$this->db->join("addrverres",'(addrverres.addrverid = addrver.id)','left');

	    $this->db->join("addrver_insuff",'(addrver_insuff.addrverid = addrver.id AND  addrver_insuff.status = 1 )','left');


		$this->db->join("status",'status.id = addrverres.verfstatus','left');

		$this->db->where('addrver.fill_by','2'); 
        
        $this->db->where('addrverres.verfstatus','18'); 

        $this->db->where('addrver.candsid',$this->candidate_info['cands_info_id']); 

        $this->db->where($this->filter_where_cond($where1));

         if(isset($where1['start_date']) &&  $where1['start_date'] != '' && isset($where1['end_date']) &&  $where1['end_date'] != '')	
		    { 

		     	$start_date  =  $where1['start_date'];
	            $end_date  =  $where1['end_date'];

	            if($where1['status'] == "Closed")
                {
	         
		     	$where3 = "DATE_FORMAT(`addrverres`.`closuredate`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}

		     	if($where1['status'] == "Insufficiency")
                {
	         
		     	$where3 = "DATE_FORMAT(`addrver_insuff`.`insuff_raised_date`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}
                
                $this->db->where($where3); 

		    } 
        

		
		if(is_array($where1) && $where1['search']['value'] != "")
		{

			$this->db->like('candidates_info.cmp_ref_no', $where1['search']['value']);

			$this->db->or_like('candidates_info.ClientRefNumber', $where1['search']['value']);

			$this->db->or_like('clients.clientname', $where1['search']['value']);

			$this->db->or_like('addrver.add_com_ref', $where1['search']['value']);

			$this->db->or_like('addrver.iniated_date', $where1['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where1['search']['value']);

			$this->db->or_like('addrver.address', $where1['search']['value']);

			$this->db->or_like('addrver.city', $where1['search']['value']);

            $this->db->or_like('addrver.state', $where1['search']['value']);
		}

		if(!empty($where1['order']))
		{
			$column_name_index = $where1['order'][0]['column'];
			$order_by = $where1['order'][0]['dir']; 
            
            //$this->db->where('addrver.vendor_id !=',0);

            if(!empty($column_name_index))
			{

			  $this->db->order_by($where1['columns'][$column_name_index]['data'],$order_by);
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

    public function get_all_addrs_submit_by_candidate_datatable($where1,$columns)
	{
         
   		$this->db->select("candidates_info.id as candidates_info_id, candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,addrver.id,addrver.mod_of_veri,addrver.add_com_ref,clients.clientname,addrver.address,addrver.city,addrver.pincode, addrver.state ,addrver.iniated_date,status.status_value,user_profile.user_name,addrverres.first_qc_approve,addrverres.verfstatus,addrverres.first_qc_updated_on,addrverres.first_qu_reject_reason,(select created_on from address_activity_data where comp_table_id = addrver.id order by id desc limit 1) as last_activity_date,due_date,tat_status,(select vendor_name from vendors where vendors.id = addrver.vendor_id) as vendor_name,addrver_insuff.insuff_raised_date");
       
		$this->db->from('addrver');

		$this->db->join("user_profile",'user_profile.id = addrver.has_case_id','left');

		$this->db->join("candidates_info",'candidates_info.id = addrver.candsid');

		$this->db->join("clients",'clients.id = addrver.clientid');
		
		$this->db->join("addrverres",'addrverres.addrverid = addrver.id','left');


		$this->db->join("addrver_insuff",'(addrver_insuff.addrverid = addrver.id AND  addrver_insuff.status = 1 )','left');

		$this->db->join("status",'status.id = addrverres.verfstatus','left');

        $this->db->where($this->filter_where_cond($where1)); 

        $this->db->where('addrver.fill_by','2'); 
        
        $this->db->where('addrverres.verfstatus !=','18'); 

        $this->db->where('addrver.candsid',$this->candidate_info['cands_info_id']); 


            if(isset($where1['start_date']) &&  $where1['start_date'] != '' && isset($where1['end_date']) &&  $where1['end_date'] != '')	
		    { 

		     	$start_date  =  $where1['start_date'];
	            $end_date  =  $where1['end_date'];

	            if($where1['status'] == "Closed")
                {
	         
		     	$where3 = "DATE_FORMAT(`addrverres`.`closuredate`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}

		     	if($where1['status'] == "Insufficiency")
                {
	         
		     	$where3 = "DATE_FORMAT(`addrver_insuff`.`insuff_raised_date`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}
                
                $this->db->where($where3); 

		    } 
     
       
         
		if(is_array($where1) && $where1['search']['value'] != "" )
		{
			$this->db->like('candidates_info.cmp_ref_no', $where1['search']['value']);

			$this->db->or_like('candidates_info.ClientRefNumber', $where1['search']['value']);

			$this->db->or_like('clients.clientname', $where1['search']['value']);

			$this->db->or_like('addrver.add_com_ref', $where1['search']['value']);

			$this->db->or_like('addrver.iniated_date', $where1['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where1['search']['value']);

			$this->db->or_like('addrver.address', $where1['search']['value']);

			$this->db->or_like('addrver.city', $where1['search']['value']);

            $this->db->or_like('addrver.state', $where1['search']['value']);

		}

		if(!empty($where1['order']))
		{
			$column_name_index = $where1['order'][0]['column'];
			$order_by = $where1['order'][0]['dir']; 
            
            //$this->db->where('addrver.vendor_id !=',0);

            if(!empty($column_name_index))
			{

			  $this->db->order_by($where1['columns'][$column_name_index]['data'],$order_by);
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
		
		$this->db->limit($where1['length'],$where1['start']);

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}
   
	public function get_all_addrs_submit_by_candidate_datatable_count($where1,$columns)
	{
         
		$this->db->select("candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,addrver.id,addrver.mod_of_veri,addrver.add_com_ref,clients.clientname,'' as vendor_name,addrver.address,addrver.city,addrver.pincode,addrver.state,addrver.iniated_date,status.status_value,user_profile.user_name,addrverres.first_qc_approve,addrverres.verfstatus,addrverres.first_qc_updated_on,addrverres.first_qu_reject_reason,(select created_on from address_activity_data where comp_table_id = addrver.id  order by id desc limit 1) as last_activity_date");

		$this->db->from('addrver');

		$this->db->join("user_profile",'user_profile.id = addrver.has_case_id','left');

		$this->db->join("candidates_info",'candidates_info.id = addrver.candsid');

		$this->db->join("clients",'clients.id = addrver.clientid');

		$this->db->join("addrverres",'(addrverres.addrverid = addrver.id)','left');

	    $this->db->join("addrver_insuff",'(addrver_insuff.addrverid = addrver.id AND  addrver_insuff.status = 1 )','left');


		$this->db->join("status",'status.id = addrverres.verfstatus','left');

		$this->db->where('addrver.fill_by','2'); 
        
        $this->db->where('addrverres.verfstatus !=','18'); 

        $this->db->where('addrver.candsid',$this->candidate_info['cands_info_id']); 

        $this->db->where($this->filter_where_cond($where1));

         if(isset($where1['start_date']) &&  $where1['start_date'] != '' && isset($where1['end_date']) &&  $where1['end_date'] != '')	
		    { 

		     	$start_date  =  $where1['start_date'];
	            $end_date  =  $where1['end_date'];

	            if($where1['status'] == "Closed")
                {
	         
		     	$where3 = "DATE_FORMAT(`addrverres`.`closuredate`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}

		     	if($where1['status'] == "Insufficiency")
                {
	         
		     	$where3 = "DATE_FORMAT(`addrver_insuff`.`insuff_raised_date`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}
                
                $this->db->where($where3); 

		    } 
        

		
		if(is_array($where1) && $where1['search']['value'] != "")
		{

			$this->db->like('candidates_info.cmp_ref_no', $where1['search']['value']);

			$this->db->or_like('candidates_info.ClientRefNumber', $where1['search']['value']);

			$this->db->or_like('clients.clientname', $where1['search']['value']);

			$this->db->or_like('addrver.add_com_ref', $where1['search']['value']);

			$this->db->or_like('addrver.iniated_date', $where1['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where1['search']['value']);

			$this->db->or_like('addrver.address', $where1['search']['value']);

			$this->db->or_like('addrver.city', $where1['search']['value']);

            $this->db->or_like('addrver.state', $where1['search']['value']);
		}

		if(!empty($where1['order']))
		{
			$column_name_index = $where1['order'][0]['column'];
			$order_by = $where1['order'][0]['dir']; 
            
            //$this->db->where('addrver.vendor_id !=',0);

            if(!empty($column_name_index))
			{

			  $this->db->order_by($where1['columns'][$column_name_index]['data'],$order_by);
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