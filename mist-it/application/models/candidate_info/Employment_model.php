<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Employment_model extends CI_Model
{
	function __construct()
    {
		//parent::__construct();

		$this->tableName = 'empver';

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

			$result = $this->db->update('empverres', $arrdata);

			record_db_error($this->db->last_query());

			return $result;
	    }
	   
	} 

	public function select_insuff($where_array)
	{
		$this->db->select('*')->from('empverres_insuff');

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

			$result = $this->db->update('empverres_insuff', $arrdata);
			
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
			$where['ev1.var_filter_status'] = $where_arry['status'];
		    }
		   
		}

		if(isset($where_arry['sub_status']) && $where_arry['sub_status'] != '' && $where_arry['sub_status'] != 0)	
		{
			$where['ev1.verfstatus'] = $where_arry['sub_status'];
		}

		if(isset($where_arry['client_id']) &&  $where_arry['client_id'] != 0)	
		{
			$where['empver.clientid'] = $where_arry['client_id'];
		}
		if(isset($where_arry['filter_by_executive']) &&  $where_arry['filter_by_executive'] != 0)	
		{ 
		    if($where_arry['filter_by_executive'] != "All")
	     	{
              $where['empver.has_case_id'] = $where_arry['filter_by_executive'];
	      	}
        }
		return $where;

	}


	public function get_all_employment_by_candidate_datatable($where,$columns)
	{
		
		$this->db->select("candidates_info.CandidateName,candidates_info.id as candidates_info_id,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,user_profile.user_name,status.status_value,ev1.verfstatus,ev1.first_qc_approve,ev1.first_qc_updated_on,ev1.first_qu_reject_reason,empver.empid,empver.mode_of_veri,empver.id,empver.has_assigned_on,empver.iniated_date,empver.emp_com_ref,empver.field_visit_status,empver.mail_sent_status,empver.mail_sent_addrs,ev1.res_reasonforleaving,candidates_info.caserecddate,clients.clientname,(select created_on from empver_activity_data where comp_table_id = empver.id order by id desc limit 1) as last_activity_date,due_date,tat_status,empverres_insuff.insuff_raised_date,(select coname from company_database where company_database.id = empver.nameofthecompany) as coname");

		$this->db->from('empver');

		$this->db->join("user_profile",'user_profile.id = empver.has_case_id','left');
		
		$this->db->join("candidates_info",'candidates_info.id = empver.candsid');

		$this->db->join("clients",'clients.id = empver.clientid');
		
		$this->db->join("empverres as ev1",'ev1.empverid = empver.id','left');

		$this->db->join("empverres_insuff",'(empverres_insuff.empverres_id = empver.id AND  empverres_insuff.status = 1 )','left');

		$this->db->join("status",'status.id = ev1.verfstatus','left');

		$this->db->where('empver.fill_by','2'); 
        
        $this->db->where('ev1.verfstatus','18');

        $this->db->where('empver.candsid',$this->candidate_info['cands_info_id']); 


		$this->db->where($this->filter_where_cond($where));

		 if(isset($where['start_date']) &&  $where['start_date'] != '' && isset($where['end_date']) &&  $where['end_date'] != '')	
		    { 

		     	$start_date  =  $where['start_date'];
	            $end_date  =  $where['end_date'];

	            if($where['status'] == "Closed")
                {
	         
		     	$where3 = "DATE_FORMAT(`ev1`.`closuredate`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}

		     	if($where['status'] == "Insufficiency")
                {
	         
		     	$where3 = "DATE_FORMAT(`empverres_insuff`.`insuff_raised_date`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}
                
                $this->db->where($where3); 

		    } 


		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like('candidates_info.cmp_ref_no', $where['search']['value']);

			$this->db->or_like('candidates_info.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);

			$this->db->or_like('empver.emp_com_ref', $where['search']['value']);

			$this->db->or_like('empver.iniated_date', $where['search']['value']);

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

	public function get_all_employment_by_candidate_datatable_count($where,$columns)
	{
		$this->db->select("candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,user_profile.user_name,status.status_value,ev1.verfstatus,ev1.first_qc_approve,ev1.first_qc_updated_on,ev1.first_qu_reject_reason,empver.empid,empver.id,empver.has_assigned_on,empver.iniated_date,empver.emp_com_ref,empver.mail_sent_status,empver.mail_sent_addrs,ev1.res_reasonforleaving,candidates_info.caserecddate,empverres_insuff.insuff_raised_date,(select coname from company_database where company_database.id = empver.nameofthecompany) as coname");

		$this->db->from('empver');

		$this->db->join("user_profile",'user_profile.id = empver.has_case_id');

		$this->db->join("clients",'clients.id = empver.clientid');
		
		$this->db->join("candidates_info",'candidates_info.id = empver.candsid');


		$this->db->join("empverres as ev1",'ev1.empverid = empver.id','left');

	   $this->db->join("empverres_insuff",'(empverres_insuff.empverres_id = empver.id AND  empverres_insuff.status = 1 )','left');


		$this->db->join("status",'status.id = ev1.verfstatus','left');

	    $this->db->where('empver.fill_by','2'); 
        
        $this->db->where('ev1.verfstatus','18');

        $this->db->where('empver.candsid',$this->candidate_info['cands_info_id']); 

		
		$this->db->where($this->filter_where_cond($where));

		 if(isset($where['start_date']) &&  $where['start_date'] != '' && isset($where['end_date']) &&  $where['end_date'] != '')	
		    { 

		     	$start_date  =  $where['start_date'];
	            $end_date  =  $where['end_date'];

	            if($where['status'] == "Closed")
                {
	         
		     	$where3 = "DATE_FORMAT(`ev1`.`closuredate`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}

		     	if($where['status'] == "Insufficiency")
                {
	         
		     	$where3 = "DATE_FORMAT(`empverres_insuff`.`insuff_raised_date`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}
                
                $this->db->where($where3); 

		    } 


		if(is_array($where) && $where['search']['value'] != "")
		{

			$this->db->like('candidates_info.cmp_ref_no', $where['search']['value']);

			$this->db->or_like('candidates_info.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);

			$this->db->or_like('empver.emp_com_ref', $where['search']['value']);

			$this->db->or_like('empver.iniated_date', $where['search']['value']);

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


	public function get_all_employment_submit_by_candidate_datatable($where,$columns)
	{
		
		$this->db->select("candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,user_profile.user_name,status.status_value,ev1.verfstatus,ev1.first_qc_approve,ev1.first_qc_updated_on,ev1.first_qu_reject_reason,empver.empid,empver.mode_of_veri,empver.id,empver.has_assigned_on,empver.iniated_date,empver.emp_com_ref,empver.field_visit_status,empver.mail_sent_status,empver.mail_sent_addrs,ev1.res_reasonforleaving,candidates_info.caserecddate,clients.clientname,(select created_on from empver_activity_data where comp_table_id = empver.id order by id desc limit 1) as last_activity_date,due_date,tat_status,empverres_insuff.insuff_raised_date,(select coname from company_database where company_database.id = empver.nameofthecompany) as coname");

		$this->db->from('empver');

		$this->db->join("user_profile",'user_profile.id = empver.has_case_id','left');
		
		$this->db->join("candidates_info",'candidates_info.id = empver.candsid');

		$this->db->join("clients",'clients.id = empver.clientid');


		$this->db->join("empverres as ev1",'ev1.empverid = empver.id','left');

		$this->db->join("empverres_insuff",'(empverres_insuff.empverres_id = empver.id AND  empverres_insuff.status = 1 )','left');

		$this->db->join("status",'status.id = ev1.verfstatus','left');

		$this->db->where('empver.fill_by','2'); 
        
        $this->db->where('ev1.verfstatus !=','18');

        $this->db->where('empver.candsid',$this->candidate_info['cands_info_id']); 


		$this->db->where($this->filter_where_cond($where));

		 if(isset($where['start_date']) &&  $where['start_date'] != '' && isset($where['end_date']) &&  $where['end_date'] != '')	
		    { 

		     	$start_date  =  $where['start_date'];
	            $end_date  =  $where['end_date'];

	            if($where['status'] == "Closed")
                {
	         
		     	$where3 = "DATE_FORMAT(`ev1`.`closuredate`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}

		     	if($where['status'] == "Insufficiency")
                {
	         
		     	$where3 = "DATE_FORMAT(`empverres_insuff`.`insuff_raised_date`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}
                
                $this->db->where($where3); 

		    } 


		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like('candidates_info.cmp_ref_no', $where['search']['value']);

			$this->db->or_like('candidates_info.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);

			$this->db->or_like('empver.emp_com_ref', $where['search']['value']);

			$this->db->or_like('empver.iniated_date', $where['search']['value']);

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

	public function get_all_employment_submit_by_candidate_datatable_count($where,$columns)
	{
		$this->db->select("candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,user_profile.user_name,status.status_value,ev1.verfstatus,ev1.first_qc_approve,ev1.first_qc_updated_on,ev1.first_qu_reject_reason,empver.empid,empver.id,empver.has_assigned_on,empver.iniated_date,empver.emp_com_ref,empver.mail_sent_status,empver.mail_sent_addrs,ev1.res_reasonforleaving,candidates_info.caserecddate,empverres_insuff.insuff_raised_date,(select coname from company_database where company_database.id = empver.nameofthecompany) as coname");

		$this->db->from('empver');

		$this->db->join("user_profile",'user_profile.id = empver.has_case_id');

		$this->db->join("clients",'clients.id = empver.clientid');
		
		$this->db->join("candidates_info",'candidates_info.id = empver.candsid');


		$this->db->join("empverres as ev1",'ev1.empverid = empver.id','left');

	   $this->db->join("empverres_insuff",'(empverres_insuff.empverres_id = empver.id AND  empverres_insuff.status = 1 )','left');


		$this->db->join("status",'status.id = ev1.verfstatus','left');

	    $this->db->where('empver.fill_by','2'); 
        
        $this->db->where('ev1.verfstatus !=','18');

        $this->db->where('empver.candsid',$this->candidate_info['cands_info_id']); 

		
		$this->db->where($this->filter_where_cond($where));

		 if(isset($where['start_date']) &&  $where['start_date'] != '' && isset($where['end_date']) &&  $where['end_date'] != '')	
		    { 

		     	$start_date  =  $where['start_date'];
	            $end_date  =  $where['end_date'];

	            if($where['status'] == "Closed")
                {
	         
		     	$where3 = "DATE_FORMAT(`ev1`.`closuredate`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}

		     	if($where['status'] == "Insufficiency")
                {
	         
		     	$where3 = "DATE_FORMAT(`empverres_insuff`.`insuff_raised_date`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}
                
                $this->db->where($where3); 

		    } 


		if(is_array($where) && $where['search']['value'] != "")
		{

			$this->db->like('candidates_info.cmp_ref_no', $where['search']['value']);

			$this->db->or_like('candidates_info.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);

			$this->db->or_like('empver.emp_com_ref', $where['search']['value']);

			$this->db->or_like('empver.iniated_date', $where['search']['value']);

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
		$this->db->select('mode_of_verification ');

		$this->db->from('clients_details');
		
		$this->db->where($where);

        $this->db->limit(1);  

		$result  = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function empver_supervisor_details($files_arry,$where_array = array())
	{
		if(!empty($where_array))
		{
			$this->db->delete('empver_supervisor_details', $where_array);
		}

		$res =  $this->db->insert_batch('empver_supervisor_details', $files_arry);
			
		record_db_error($this->db->last_query());
		
		return $res;
	}

	public function get_employer_details($empver_aary = array())
	{
		$this->db->select("empver.*,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = empver.has_case_id) as user_name,empverres.id as empverres_id,empverres.modified_on,(select status_value from status where id = empverres.verfstatus) as verfstatus,(select user_name from user_profile where id = empverres.created_by) as res_created_by,(select clientname from clients where clients.id = empver.clientid limit 1) as clientname,empverres.created_on as res_created_on,empverres.closuredate,(select filter_status from status where id=empverres.verfstatus) as emp_filter_status,(select id from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_id,(select id from entity_package where entity_package.id = candidates_info.package limit 1) as package_id,empverres.first_qc_approve,empverres.var_filter_status,(select coname from company_database where company_database.id = empver.nameofthecompany) as actual_company_name");

		$this->db->from('empver');

		$this->db->join("empverres",'empverres.empverid = empver.id');

		$this->db->join("candidates_info",'candidates_info.id = empver.candsid');
		
		if(!empty($empver_aary)){
			$this->db->where($empver_aary);
		}

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		$result =  $result->result_array();

		return $result;
	}

	public function check_company_exist($wheredata)
	{
		$this->db->select("id");

		$this->db->from('company_database');


		$this->db->where('company_database.coname',$wheredata);

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array(); 
		
	}
	public function save_company_details($arrdata,$arrwhere = array())
	{
	    if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update('company_database', $arrdata);

			record_db_error($this->db->last_query());

			return $result;
	    }
	    else
	    {
			$this->db->insert('company_database', $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	    }
	}


    public function select_executive_id()
	{
	    $sql = "select id,rr_id from user_profile where (id = 10 or id = 11)";
		
		$query = $this->db->query($sql);

		return $query->result_array();
    }

    public function update_executive_id($arrdata,$arrwhere = array())
	{
	    if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update('user_profile', $arrdata);

			record_db_error($this->db->last_query());

			return $result;
	    }
    }

}
?>