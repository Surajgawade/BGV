<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Employment_model extends CI_Model
{
	function __construct()
    {
		$this->tableName = 'empver';

		$this->primaryKey = 'id';
	}

	public function select($return_as_strict_row,$select_array, $where_array = array())
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

	public function update_auto_increament_value($arrdata,$arrwhere = array())
	{
        
		   $this->db->where($arrwhere);

			$result = $this->db->update('empver', $arrdata);

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

	public function save_employment($tableName,$arrdata,$arrwhere = array())
	{
		if(!empty($arrwhere))
		{
			$this->db->where($arrwhere);

			$result = $this->db->update($tableName, $arrdata);

			record_db_error($this->db->last_query());

			return $result;
		}
		else
		{
			$this->db->insert($tableName, $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
		}
	}
   
    public function save_mail_activity_data($arrdata)
	{
	
		
	    $this->db->insert('empver_activity_data', $arrdata);

		record_db_error($this->db->last_query());

		return $this->db->insert_id();
		
	}
	 public function emp_mail_details($arrdata)
	{
	
		
	    $this->db->insert('emp_mail_details', $arrdata);

		record_db_error($this->db->last_query());

		return $this->db->insert_id();
		
	}

	public function save_employee_company_verified($arrdata)
	{
		
			$this->db->insert('company_database_verifiers_details', $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
		
	}


	public function save_employee_company_verified_update($arrdata,$arrwhere)
	{
		
	  
			$this->db->where($arrwhere);

			$result = $this->db->update('company_database_verifiers_details', $arrdata);

			record_db_error($this->db->last_query());

			return $result;
		
		
	}


	public function check_company_exists($wheredata)
	{
		$this->db->select("id");

		$this->db->from('company_database_verifiers_details');

		
		$this->db->where($wheredata);


		$result = $this->db->get();


		record_db_error($this->db->last_query());
		
		return $result->result_array(); 
		
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

	public function check_cin_exist($wheredata)
	{
		$this->db->select("id");

		$this->db->from('company_database');


		$this->db->where('company_database.cin_number',$wheredata);

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


	public function emp_com_ref()
	{
		$result = $this->db->select("id,SUBSTRING_INDEX(emp_com_ref, '-',-1) as A_I")->order_by('id','desc')->limit(1)-> get($this->tableName)->row_array();
		return $result;
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
   
     public function get_employment_details_first_qc($empver_aary)
	{
	  $this->db->select("empver.candsid,(select coname from company_database where company_database.id = empver.nameofthecompany limit 1) as coname,(select coname from company_database where company_database.id = empverres.res_nameofthecompany limit 1) as res_coname,(select status_value from status where status.id = empverres.verfstatus) as verfstatus,empid,if(empid = res_empid,'Verified',res_empid) as res_empid, concat(empfrom,' to ', empto) as emp_period, concat(employed_from,' to ', employed_to) as res_emp_period, designation, if(emp_designation = designation ,'Verified', emp_designation) as emp_designation,remuneration, if(res_remuneration = remuneration,'Verified',res_remuneration) as res_remuneration,if(r_manager_name != NULL,r_manager_name,'NA') as r_manager_name,if(reportingmanager = r_manager_name,'Verified' ,reportingmanager) as reportingmanager,reasonforleaving,if(res_reasonforleaving = reasonforleaving,'Verified',res_reasonforleaving) as res_reasonforleaving, if(integrity_disciplinary_issue != NULL,integrity_disciplinary_issue,'NA') as integrity_disciplinary_issue,if(exitformalities != NULL,exitformalities,'NA') as exitformalities ,if(eligforrehire != NULL,eligforrehire , 'NA') as eligforrehire,if(fmlyowned != NULL, fmlyowned,'NA') as fmlyowned ,if(justdialwebcheck != NULL,justdialwebcheck ,'NA') as justdialwebcheck,if(mcaregn != NULL,mcaregn ,'NA') as mcaregn, if(domainname != NULL,domainname,'NA') as domainname,if(empverres.remarks != NULL,empverres.remarks,'NA') as res_remarks,if(verfname != NULL,verfname,'NA') as verfname,if(modeofverification != NULL,modeofverification,'NA') as modeofverification, empver.id as empver_id,empverres.id as empverres_id, (SELECT GROUP_CONCAT(concat(empver.clientid,'/',file_name) ORDER BY serialno ASC SEPARATOR '||') FROM empverres_files where empverres_files.empver_id = empverres.id and empverres_files.type= 1) as attachments"); 

		$this->db->from('empver');

		$this->db->join("empverres",'empverres.empverid = empver.id');

		if(!empty($empver_aary)){
			$this->db->where($empver_aary);
		}

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());
		
		return $result->result_array();

	}

	public function get_all_emp_by_client_datatable($empver_aary = array(),$where,$columns)
	{
		
		$this->db->select("candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,user_profile.user_name,company_database.coname,status.status_value,ev1.verfstatus,ev1.first_qc_approve,ev1.first_qc_updated_on,ev1.first_qu_reject_reason,empver.empid,empver.mode_of_veri,empver.id,empver.has_assigned_on,empver.iniated_date,empver.emp_com_ref,empver.field_visit_status,empver.mail_sent_status,empver.mail_sent_addrs,ev1.res_reasonforleaving,candidates_info.caserecddate,clients.clientname,(select created_on from empver_activity_data where comp_table_id = empver.id order by id desc limit 1) as last_activity_date,due_date,tat_status,empverres_insuff.insuff_raised_date");

		$this->db->from('empver');

		$this->db->join("user_profile",'user_profile.id = empver.has_case_id','left');
		
		$this->db->join("candidates_info",'candidates_info.id = empver.candsid');

		$this->db->join("clients",'clients.id = empver.clientid');

		$this->db->join("company_database",'company_database.id = empver.nameofthecompany');

		$this->db->join("empverres as ev1",'ev1.empverid = empver.id','left');

		$this->db->join("empverres_insuff",'(empverres_insuff.empverres_id = empver.id AND  empverres_insuff.status = 1 )','left');

		$this->db->join("status",'status.id = ev1.verfstatus','left');

		$this->db->where($this->filter_where_cond($where));

	    //$this->db->where('empver.reject_status',1);

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

			$this->db->or_like('candidates_info.CandidatesContactNumber', $where['search']['value']);

			$this->db->or_like('candidates_info.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);

			$this->db->or_like('company_database.coname', $where['search']['value']);

			$this->db->or_like('empver.emp_com_ref', $where['search']['value']);

			$this->db->or_like('empver.iniated_date', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where['search']['value']);

			
		}
		
		$this->db->limit($where['length'],$where['start']);
		
		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 
            
            

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

		    $this->db->order_by('empver.id','desc');
		}

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}




	public function get_all_emp_by_client_datatable_count($empver_aary = array(),$where,$columns)
	{
		$this->db->select("candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,user_profile.user_name,company_database.coname,status.status_value,ev1.verfstatus,ev1.first_qc_approve,ev1.first_qc_updated_on,ev1.first_qu_reject_reason,empver.empid,empver.id,empver.has_assigned_on,empver.iniated_date,empver.emp_com_ref,empver.mail_sent_status,empver.mail_sent_addrs,ev1.res_reasonforleaving,candidates_info.caserecddate,empverres_insuff.insuff_raised_date");

		$this->db->from('empver');

		$this->db->join("user_profile",'user_profile.id = empver.has_case_id');

		$this->db->join("clients",'clients.id = empver.clientid');
		
		$this->db->join("candidates_info",'candidates_info.id = empver.candsid');

		$this->db->join("company_database",'company_database.id = empver.nameofthecompany');

		$this->db->join("empverres as ev1",'ev1.empverid = empver.id','left');

	   $this->db->join("empverres_insuff",'(empverres_insuff.empverres_id = empver.id AND  empverres_insuff.status = 1 )','left');


		$this->db->join("status",'status.id = ev1.verfstatus','left');
		
		$this->db->where($this->filter_where_cond($where));
	//	$this->db->where('empver.reject_status',1);

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

			$this->db->or_like('candidates_info.CandidatesContactNumber', $where['search']['value']);

			$this->db->or_like('candidates_info.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);

			$this->db->or_like('company_database.coname', $where['search']['value']);

			$this->db->or_like('empver.emp_com_ref', $where['search']['value']);

			$this->db->or_like('empver.iniated_date', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where['search']['value']);

		}
		
		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 
            
            

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

		    	$this->db->order_by('empver.id','desc');
		}

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

    
    public function get_all_emp_by_client_datatable_new_cases($where_arry = array(),$where,$columns)
	{  
        $conditional_status = "(`empverres`.`verfstatus` = '14' or `empverres`.`verfstatus` = '11' or `empverres`.`verfstatus` = '26')"; 

		$this->db->select("candidates_info.id as candidates_info_id, candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,empver.id,empver.emp_com_ref,clients.clientname,empver.nameofthecompany,empver.deputed_company,empver.employment_type,empver.locationaddr,empver.citylocality,empver.pincode,empver.state,empver.iniated_date,status.status_value,user_profile.user_name,empverres.first_qc_approve,empverres.first_qc_updated_on,empverres.first_qu_reject_reason, (select created_on from empver_activity_data where comp_table_id = empver.id order by id desc limit 1) as last_activity_date,due_date,tat_status,(select vendor_name from vendors where vendors.id = empver.vendor_id) as vendor_name");
       
		$this->db->from('empver');

		$this->db->join("user_profile",'user_profile.id = empver.has_case_id','left');

		$this->db->join("candidates_info",'candidates_info.id = empver.candsid');

		$this->db->join("clients",'clients.id = empver.clientid');
		
		$this->db->join("empverres",'empverres.empverid = empver.id','left');

		$this->db->join("status",'status.id = empverres.verfstatus','left');

        $this->db->where($conditional_status);


        if(isset($where['filter_by_executive']) &&  $where['filter_by_executive'] != 0)	
		{ 
		    if($where['filter_by_executive'] != "All")
	     	{
	     		$this->db->where('empver.has_case_id',$where['filter_by_executive']);
            
	      	}
        }

		if(is_array($where) && $where['search']['value'] != "" )
		{
			$this->db->like('candidates_info.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);

			$this->db->or_like('candidates_info.cmp_ref_no', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where['search']['value']);

			$this->db->or_like('candidates_info.overallstatus', $where['search']['value']);


		}

		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 
          
			$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		}
		else
		{
			$this->db->order_by('empver.id','DESC');
		}
		
		$this->db->limit($where['length'],$where['start']);

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_all_emp_by_client_datatable_count_new_cases($where_arry = array(),$where,$columns)
	{

		$conditional_status = "(`empverres`.`verfstatus` = '14' or `empverres`.`verfstatus` = '11' or `empverres`.`verfstatus` = '26')"; 

		$this->db->select("candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,empver.id,empver.emp_com_ref,clients.clientname,empver.id,empver.emp_com_ref,clients.clientname,empver.nameofthecompany,empver.deputed_company,empver.employment_type,empver.locationaddr,empver.citylocality,empver.pincode,empver.state,empver.iniated_date,status.status_value,user_profile.user_name,empverres.first_qc_approve,empverres.first_qc_updated_on,empverres.first_qu_reject_reason, (select created_on from address_activity_data where comp_table_id = empver.id  order by id desc limit 1) as last_activity_date");

		$this->db->from('empver');

		$this->db->join("user_profile",'user_profile.id = empver.has_case_id','left');

		$this->db->join("candidates_info",'candidates_info.id = empver.candsid');

		$this->db->join("clients",'clients.id = empver.clientid');

		$this->db->join("empverres",'(empverres.empverid = empver.id)','left');

		$this->db->join("status",'status.id = empverres.verfstatus','left');

		$this->db->where($conditional_status);

		if(isset($where['filter_by_executive']) &&  $where['filter_by_executive'] != 0)	
		{ 
		    if($where['filter_by_executive'] != "All")
	     	{
	     		$this->db->where('empver.has_case_id',$where['filter_by_executive']);
            
	      	}
        }
		
		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like('candidates_info.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);

			$this->db->or_like('candidates_info.cmp_ref_no', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where['search']['value']);

			$this->db->or_like('candidates_info.overallstatus', $where['search']['value']);
		}

		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			
			$order_by = $where['order'][0]['dir']; 
      
			$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		}
		else
		{
			$this->db->order_by('empver.id','DESC');
		}
		
		$result = $this->db->get();
       
		record_db_error($this->db->last_query());
  
		return $result->result_array();
	}


	public function get_all_employment_by_client($clientid,$filter_by_status,$from_date,$to_date)
	{	

		$this->db->select("empver.*,clients.clientname,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.CandidateName,status.status_value as verfstatus,status.filter_status as filter_status,company_database.coname,ev1.closuredate,ev1.remarks,(select concat(`user_profile`.`firstname`,' ',`user_profile`.`lastname`) from user_profile where user_profile.id = empver.has_case_id limit 1) as executive_name,(select vendor_name from vendors where vendors.id = empver.vendor_id limit 1) as vendor_name,(SELECT v.final_status from view_vendor_master_log v, `employment_vendor_log` `ed` where ed.case_id = empver.id and v.case_id = ed.id and component = 'empver' and  component_tbl_id  = '2' order by v.id desc limit 1) as vendor_status,(select v.trasaction_id from view_vendor_master_log v,`employment_vendor_log` `ed` where ed.case_id = empver.id and v.case_id = ed.id  and component = 'empver' and  component_tbl_id  = '2' order by v.id desc limit 1) as transaction_id,(SELECT GROUP_CONCAT(concat(DATE_FORMAT(empverres_insuff.insuff_raised_date,'%d-%m-%Y')) SEPARATOR '||') FROM empverres_insuff where empverres_insuff.empverres_id = empver.id) as insuff_raised_date,(SELECT GROUP_CONCAT(concat(DATE_FORMAT(empverres_insuff.insuff_clear_date,'%d-%m-%Y')) SEPARATOR '||') FROM empverres_insuff where empverres_insuff.empverres_id = empver.id) as insuff_clear_date,(SELECT GROUP_CONCAT(concat(empverres_insuff.insuff_raise_remark) SEPARATOR '||') FROM empverres_insuff where empverres_insuff. 	empverres_id = empver.id) as insuff_raise_remark");

		$this->db->from('empver');

		$this->db->join("clients",'clients.id = empver.clientid');

		$this->db->join("candidates_info",'candidates_info.id = empver.candsid');

		$this->db->join("company_database",'company_database.id = empver.nameofthecompany');

		$this->db->join("empverres as ev1",'ev1.empverid = empver.id','left');

		$this->db->join("empverres as ev2",'(ev2.empverid = empver.id and ev1.id < ev2.id)','left');

		$this->db->join("status",'status.id = ev1.verfstatus','left');

		$this->db->where('ev2.verfstatus is null');

		if($clientid)
		{
			$this->db->where('empver.clientid',$clientid);
		}

		if($from_date && $to_date)
		{

			$where3 = "DATE_FORMAT(`ev1`.`closuredate`,'%Y-%m-%d') BETWEEN '$from_date' AND '$to_date'";
                
            $this->db->where($where3); 
			
		}


		if($filter_by_status)
		{
			if($filter_by_status == "WIP")
			{
			$this->db->where('(ev1.var_filter_status = "wip" or  ev1.var_filter_status = "WIP")');
		    }
		    if($filter_by_status == "Insufficiency")
			{
			$this->db->where('(ev1.var_filter_status = "insufficiency" or  ev1.var_filter_status = "Insufficiency")');
		    }
		    if($filter_by_status == "Closed")
			{
			$this->db->where('(ev1.var_filter_status = "closed" or  ev1.var_filter_status = "Closed")');
		    }
		}

		$this->db->order_by('empver.id', 'ASC');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}


	public function get_emp_list_view($empver_aary = array())
	{
		$this->db->select("candidates_info.CandidateName,candidates_info.gender,DATE_FORMAT(candidates_info.DateofBirth,'%d-%M-%Y') as DateofBirth,DATE_FORMAT(candidates_info.overallclosuredate,'%d-%M-%Y') as overallclosuredate,candidates_info.ClientRefNumber,candidates_info.overallstatus,candidates_info.cmp_ref_no,(select coname from company_database where company_database.id = empver.nameofthecompany) as coname,ev1.first_qc_approve,ev1.first_qc_updated_on,ev1.first_qu_reject_reason,empver.designation,empver.due_date,empver.tat_status,empver.remuneration,r_manager_name,reasonforleaving,empver.empfrom,empver.empto,empver.empid,empver.id,empver.has_assigned_on,DATE_FORMAT(empver.iniated_date,'%d-%M-%Y') as iniated_date,DATE_FORMAT(empver.iniated_date,'%d-%m-%Y') as initiated_date,empver.emp_com_ref,empver.mail_sent_status,empver.employment_type,empver.iniated_date,empver.mail_sent_addrs,ev1.res_reasonforleaving,candidates_info.caserecddate,(select created_on from empver_activity_data where comp_table_id = empver.id order by id desc limit 1) as last_activity_date,(select user_name from user_profile where user_profile.id = empver.has_case_id limit 1) as executive_name,(select clientname from clients where clients.id = empver.clientid limit 1) as clientname,(select status_value from status where status.id = ev1.verfstatus limit 1) as verfstatus,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,ev1.res_empid,ev1.employed_from,ev1.employed_to,ev1.emp_designation,ev1.res_remuneration,ev1.reportingmanager,ev1.res_reasonforleaving,integrity_disciplinary_issue,exitformalities,mcaregn,domainname,justdialwebcheck,fmlyowned,verfname,verfdesgn,modeofverification,ev1.remarks as res_remarks,(select status_value from status where status.id = candidates_info.overallstatus)  as overallstatus_value"); 

		$this->db->from('empver');

		$this->db->join("candidates_info",'candidates_info.id = empver.candsid');

		$this->db->join("empverres as ev1",'ev1.empverid = empver.id');

		$this->db->where($empver_aary);

		return $this->db->get()->result_array();
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
   
    public function save_trigger($arrdata)
    { 

     
			$this->db->insert("empver_activity_data", $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	     
        
    }
    public  function get_reporting_manager_id()
    {

    	
        $this->db->select('email,reporting_manager,firstname,lastname,designation,department');

		$this->db->from('user_profile');

		$this->db->where('user_profile.id',$this->user_info['id']);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
    
    }

    public  function get_reporting_manager_final_id($where_id)
    {

    	
        $this->db->select('email,reporting_manager,firstname,lastname,designation,department');

		$this->db->from('user_profile');

		$this->db->where('user_profile.id',$where_id);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
    
    }

    public  function get_reporting_manager_email_id($reportingmanager_id)
    {

    	
        $this->db->select('email');

		$this->db->from('user_profile');

		$this->db->where('user_profile.id',$reportingmanager_id);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
    
    }

    public function save_first_qc_result($files_arry,$arrwhere )
	{

        if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update("empverres", $files_arry);
         
    
			record_db_error($this->db->last_query());
             
			return $result;
	    }
		
	}


    public function empver_supervisor_save($files_arry)
	{
		
		   $this->db->insert('empver_supervisor_details', $files_arry);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	}
	public function supervison_details($where_array)
	{
		$this->db->select('*');

		$this->db->from('empver_supervisor_details');

		$this->db->where($where_array);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function uploaded_files($files_arry, $return_insert_ids = FALSE)
	{
		return $this->db->insert_batch('empverres_files', $files_arry);
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

	public function empverres_result_log($empver_aary = array())
	{
		$this->db->select("id,first_qc_approve,first_qu_reject_reason,DATE_FORMAT(first_qc_updated_on,'%d-%m-%Y %H-%i-%s') as first_qc_updated_on,final_qc_status,final_qc_reject_resoan,(select user_name from user_profile where first_qu_updated_by = user_profile.id) as first_qu_updated_by,DATE_FORMAT(final_qc_approved_on,'%d-%m-%Y %H-%i-%s') as final_qc_approved_on,(select user_name from user_profile where final_qc_approved_by = user_profile.id) as final_qc_approved_by");

		$this->db->from('empverres_logs');

		if(!empty($empver_aary)){
			$this->db->where($empver_aary);
		}

		$result_log = $this->db->get()->result_array();
		
		$this->db->select("id,first_qc_approve,first_qu_reject_reason,DATE_FORMAT(first_qc_updated_on,'%d-%m-%Y %H-%i-%s') as first_qc_updated_on,(select user_name from user_profile where first_qu_updated_by = user_profile.id) as first_qu_updated_by,final_qc_status,final_qc_reject_resoan,DATE_FORMAT(final_qc_approved_on,'%d-%m-%Y %H-%i-%s') as final_qc_approved_on,(select user_name from user_profile where final_qc_approved_by = user_profile.id) as final_qc_approved_by");

		$this->db->from('empverres');

		if(!empty($empver_aary)){
			$this->db->where($empver_aary);
		}

		$result = $this->db->get()->result_array();

		return array_merge($result_log,$result);
	}

	public function select_result_log($where_array)
	{
		$this->db->select('empverres_logs.*,(select created_by from activity_log where id =  empverres_logs.activity_log_id) as created_by1,(select user_name from user_profile where id = created_by1 ) as created_by,(select activity_mode from activity_log where id =  empverres_logs.activity_log_id) as activity_mode ,(select activity_status from activity_log where id =  empverres_logs.activity_log_id) as activity_status ,(select activity_type from activity_log where id =  empverres_logs.activity_log_id) as activity_type,(select action from activity_log where id =  empverres_logs.activity_log_id) as activity_action,(select GROUP_CONCAT(empverres_files.file_name) from empverres_files where `empverres_files`.`empver_id` = `empverres_logs`.`empverid` AND `status` = 1 AND `type` = 1)  as file_names,(select GROUP_CONCAT(empverres_files.id) from empverres_files where `empverres_files`.`empver_id` = `empverres_logs`.`empverid` AND `status` = 1 AND `type` = 1) as file_ids');

		$this->db->from('empverres_logs');


		$this->db->where($where_array);
		$this->db->order_by('empverres_logs.sr_id','desc');
		//$this->db->where('addrverres_result.status !=',3);

		$result  = $this->db->get();
        record_db_error($this->db->last_query());
		
		return $result->result_array();
		
	}

	public function select_result_log1($where_array)
	{
		
		$this->db->select('empverres_logs.*,empver.nameofthecompany as empver_nameofthecompany,empver.deputed_company  as empver_deputed_company,empver.employment_type as empver_employment_type,empver.empid as empver_empid,empver.r_manager_name,empver.empfrom as empver_empfrom, empver.locationaddr as empver_locationaddr,empver.citylocality as empver_citylocality,empver.pincode as empver_pincode,empver.state,empver.empto as empver_empto,empver.designation as empver_designation,empver.remuneration as empver_remuneration,empver.reasonforleaving as empreasonforleaving,(select activity_mode from activity_log where id =  empverres_logs.activity_log_id) as activity_mode ,(select activity_status from activity_log where id =  empverres_logs.activity_log_id) as activity_status ,(select activity_type from activity_log where id =  empverres_logs.activity_log_id) as activity_type,(select action from activity_log where id =  empverres_logs.activity_log_id) as activity_action,(select coname from company_database where company_database.id =  empver.nameofthecompany) as co_name,(select coname from company_database where company_database.id =  empverres_logs.res_nameofthecompany) as res_co_name');

		$this->db->from('empverres_logs');

		$this->db->join("empver",'empver.id = empverres_logs.empverid');

		//$this->db->join("addrverres",'addrverres.addrverid = addrver.id');

		$this->db->where($where_array);
		$this->db->order_by('id','desc');
		//$this->db->where('addrverres.status !=',3);

		$result  = $this->db->get();

        record_db_error($this->db->last_query());
		
		return $result->result_array();

		
	}

	public function select_file($select_array,$where_array)
	{
		$this->db->select($select_array);

		$this->db->from('empverres_files');

		$this->db->where($where_array);

		$this->db->order_by('id', 'desc');
		
		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function select_reinitiated_date($where_array)
    {
        
        $this->db->select('empver.*');

        $this->db->from('empver');

        $this->db->where($where_array);
        
        $result = $this->db->get();

        record_db_error($this->db->last_query());

        return $result->result_array();
        
    }

    public function select_verification_status($where_array)
    {
        
        $this->db->select('empverres.verfstatus,empverres.var_filter_status,empverres.var_report_status');

        $this->db->from('empverres');

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

            $result = $this->db->update('empver', $arrdata);
            
            record_db_error($this->db->last_query());
            
            return $result;
        }
         
    }
    

    public function save_update_initiated_date_empver($arrdata,$where_array)
    {
        if(!empty($where_array))
        {
            $this->db->where($where_array);

            $result = $this->db->update('empverres', $arrdata);
            
            record_db_error($this->db->last_query());
            
            return $result;
        }
         
    }


    public function initiated_date_empver_activity_data($arrdata)
    {
        
           $this->db->insert('empver_activity_data', $arrdata);

            record_db_error($this->db->last_query());

            return $this->db->insert_id();
    }
    


	public function get_employer_result_details($empver_aary = array())
	{
		$this->db->select("empver.id as empver_id,emp_com_ref, empver.has_case_id as empver_has_case_id,empver.r_manager_name,empver.has_assigned_on as empver_has_assigned_on,empver.candsid as empver_candsid,empver.empid as empver_empid,empver.nameofthecompany as empver_nameofthecompany,empver.deputed_company as empver_deputed_company,empver.employment_type as empver_employment_type,empver.locationaddr as empver_locationaddr,empver.citylocality as empver_citylocality,empver.pincode as empver_pincode,empver.empfrom as empver_empfrom,empver.empto as empver_empto,empver.designation as empver_designation,empver.remuneration as empver_remuneration,empver.clientid as empver_clientid,empver.emp_re_open_date,empver.mail_sent_status as empver_mail_sent_status,empver.mail_sent_addrs as empver_mail_sent_addrs,empver.mail_sent_on as empver_mail_sent_on,empver.reasonforleaving as empver_reasonforleaving,empver.reasonforleaving as empreasonforleaving,ev1.*,candidates_info.CandidateName,candidates_info.cmp_ref_no,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,(select id from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_id,(select id from entity_package where entity_package.id = candidates_info.package limit 1) as package_id,(select clientname from clients where clients.id = empver.clientid limit 1) as clientname,empver.iniated_date,ClientRefNumber,candidates_info.caserecddate,candidates_info.id as cands_id,(select coname from company_database where company_database.id =  empver.nameofthecompany) as co_name,(select coname from company_database where company_database.id =  ev1.res_nameofthecompany) as res_co_name");

		$this->db->from('empver');

		$this->db->join("candidates_info",'candidates_info.id = empver.candsid');

		$this->db->join("empverres as ev1",'ev1.empverid = empver.id','left');

		$this->db->where($empver_aary);
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$result =  $result->result_array();

		return $result;
	}

	public function save_update_empt_ver_result($arrdata,$arrwhere = array())
	{
		if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update('empverres', $arrdata);

			record_db_error($this->db->last_query());
			
			return $result;
	    }
	    else
	    {
			$this->db->insert('empverres', $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	    }
	}

	public function save_update_empt_ver_result_employment($arrdata,$arrwhere = array())
	{
		if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update('empverres_logs', $arrdata);

			record_db_error($this->db->last_query());
			
			return $result;
	    }
	    else
	    {
			$this->db->insert('empverres_logs', $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	    }
	}

	public function get_cands_details($where_array)
	{
		$this->db->select("candidates_info.ClientRefNumber,candidates_info.caserecddate");

		$this->db->from('empver');

		$this->db->join("candidates_info",'candidates_info.id = empver.candsid');

		$this->db->where($where_array);
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$result = $result->result_array();

		if(!empty($result)) {
			return $result[0];
		} else {
			return array('ClientRefNumber' => 'NA');
		}
	}

	public function select_empver_logs($where_array = array())
	{
		$this->db->select('empver_logs.*,empver_logs.created_by,empver_logs.created_on,empver_logs.empid,empver_logs.locationaddr,user_profile.user_name');

		$this->db->from('empver_logs');

		$this->db->join('user_profile','user_profile.id = empver_logs.created_by');

		$this->db->where($where_array);
		
		$this->db->order_by('empver_logs.created_on','desc');

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
	    else
	    {
			$this->db->insert('empverres_insuff', $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	    }
	}

	public function select_insuff($where_array)
	{
		$this->db->select('*')->from('empverres_insuff');

		$this->db->where($where_array);
		
		$this->db->order_by('id','desc');

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function select_insuff_join($where_array)
	{
		$this->db->select('empverres_insuff.*,(select user_name from user_profile where id =  empverres_insuff.created_by limit 1) as insuff_raised_by,(select user_name from user_profile where id = empverres_insuff.insuff_cleared_by limit 1) as insuff_cleared_by');

		$this->db->from('empverres_insuff');

		$this->db->where($where_array);
		
		$this->db->where('empverres_insuff.status !=',3);

		$this->db->order_by('empverres_insuff.id','asc');

		return $this->db->get()->result_array();
	}

	public function check_company_exits($fields = array())
	{
		$result = $this->db->select('id')->from('company_database')->where('coname', $fields['coname'])->get()->row();
		
		if($result != "" && $result->id != "")
		{
			return $result->id;
		}
		else
		{
			return $this->add_new_company($fields);
		}
	}

	public function check_companyname_exits($where_arry)
	{
		$this->db->select('coname');

		$this->db->from('company_database');

		$this->db->where($where_arry);
		
	    $result = $this->db->get()->row('coname');
	    
		return $result;

	}


	public function add_new_company($arrdata,$arrwhere = array())
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

	public function emp_ver_details_for_email($empver_aary = array())
	{
		
		$this->db->select("cmp_ref_no,candidates_info.clientid,candidates_info.id as candsid,candidates_info.ClientRefNumber,CandidateName,(select coname from company_database where company_database.id = empver.nameofthecompany) as coname,(select address from company_database where company_database.id = empver.nameofthecompany) as coaddress,(select city from company_database where company_database.id = empver.nameofthecompany) as colocation,empid,empver.empfrom,empver.candsid,empver.empto,empver.emp_com_ref,designation,remuneration,reasonforleaving,deputed_company,employment_type,clients.clientname as clientname,(select concat(supervisor_name, ' & ', supervisor_designation) from empver_supervisor_details as emsd where  emsd.empver_id= empver.id limit 1) as super_name_des,empver.id,empver.r_manager_name,empver.r_manager_designation,empver.r_manager_no,empver.r_manager_email,GROUP_CONCAT(empverres_files.file_name) as file_names,GROUP_CONCAT(empverres_files.id) as empverres_files_ids,ev1.verfstatus,ev1.verfname,ev1.verfdesgn,ev1.info_integrity_disciplinary_issue,ev1.info_eligforrehire,ev1.info_exitformalities,ev1.employed_from,ev1.res_employment_type,ev1.employed_to,ev1.emp_designation,ev1.res_empid as rsl_empid,ev1.res_remuneration as rsL_remuneration,ev1.res_reasonforleaving as res_reasonforleaving,ev1.reportingmanager as rsl_reportingmanager,ev1.remarks,ev1.verifiers_role,ev1.verifiers_contact_no,ev1.verifiers_email_id");

		$this->db->from('empver');

		$this->db->join("empverres as ev1",'ev1.empverid = empver.id','left');

		$this->db->join("empverres as ev2",'(ev2.empverid = empver.id and ev1.id < ev2.id)','left');

		$this->db->join("candidates_info",'candidates_info.id = empver.candsid');
		$this->db->join("clients",'clients.id = empver.clientid');


		$this->db->join("empverres_files",'(empverres_files.empver_id = empver.id AND empverres_files.status = 1) AND (empverres_files.type = 0 || empverres_files.type = 2 || empverres_files.type = 3)','left');

		if(!empty($empver_aary))
		{
			$this->db->where($empver_aary);
		}		
		$this->db->group_by('empver.id');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function export_sql($filter) { 
	
	$sql = "SELECT (select clientname from clients where clients.id = candidates_info.clientid limit 1) as
		client_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name, ClientRefNumber,cmp_ref_no,CandidateName,DATE_FORMAT(caserecddate,'%d-%m-%Y') as caserecddate, (select status_value from status where status.id = empverres.verfstatus limit 1) as verfstatus,emp_com_ref,(select user_name from user_profile where user_profile.id = empver.has_case_id) as executive_name,(select coname from company_database where company_database.id = empver.nameofthecompany) as coname,deputed_company,empid,employment_type,locationaddr,citylocality,pincode,state,compant_contact,compant_contact_name,compant_contact_designation,empfrom,empto,designation,remuneration,r_manager_name,r_manager_no,r_manager_designation,r_manager_email,reasonforleaving,
		DATE_FORMAT(iniated_date,'%d-%m-%Y') as iniated_date,DATE_FORMAT(due_date,'%d-%m-%Y') as due_date,
		tat_status,
		first_qc_updated_on,integrity_disciplinary_issue,exitformalities,eligforrehire,fmlyowned,justdialwebcheck,mcaregn,domainname,domainpurch,executive_name as res_executive_name,modeofverification,empverres.remarks,verifiers_role,verfname,verfdesgn,verifiers_contact_no,verifiers_email_id,DATE_FORMAT(closuredate,'%d-%m-%Y') as closuredate,first_qc_approve,(select created_on from address_activity_data where comp_table_id = empver.id order by id desc limit 1) as last_activity_date,(select count(file_name) from empverres_files where empverres_files.empver_id = empverres.id and type = 1) as file_name
		FROM empver 
		INNER JOIN candidates_info ON candidates_info.id = empver.candsid 
		INNER JOIN empverres ON empverres.empverid = empver.id  ".$filter." ";
		
		$query = $this->db->query($sql);

		return $query->result_array();
	}

	public function dashboard_sql($filter) { 
	
	$sql = "SELECT (select clientname from clients where clients.id = candidates_info.clientid limit 1) as
		client_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name, ClientRefNumber,cmp_ref_no,CandidateName,DATE_FORMAT(caserecddate,'%d-%m-%Y') as caserecddate, (select status_value from status where status.id = empverres.verfstatus limit 1) as verfstatus,emp_com_ref,(select user_name from user_profile where user_profile.id = empver.has_case_id) as executive_name,(select vendor_name from vendors where vendors.id = empver.vendor_id) as vendor_name,(select coname from company_database where company_database.id = empver.nameofthecompany) as coname,
		DATE_FORMAT(iniated_date,'%d-%m-%Y') as iniated_date,DATE_FORMAT(due_date,'%d-%m-%Y') as due_date,
		tat_status,mode_of_veri,field_visit_status,empverres.remarks,DATE_FORMAT(closuredate,'%d-%m-%Y') as closuredate,first_qc_approve,(select created_on from empver_activity_data where comp_table_id = empver.id order by id desc limit 1) as last_activity_date
		FROM empver 
		INNER JOIN candidates_info ON candidates_info.id = empver.candsid 
		INNER JOIN empverres ON empverres.empverid = empver.id  ".$filter." ";
		
		$query = $this->db->query($sql);

		return $query->result_array();
	}



	public function generic_mail_details($arrdata)
	{
		$this->db->insert('emp_mail_details', $arrdata);

		record_db_error($this->db->last_query());

		return $this->db->insert_id();
	}

	public function delete($arrwhere)
	{
	  $result =  $this->db->delete($this->tableName, $arrwhere);

	  record_db_error($this->db->last_query());
	  
	  return $result;
	}

	


	public function get_empverres_result($emp_ver_id = array())
	{
		$this->db->select("empverres.*,empver.clientid as client_id,GROUP_CONCAT(empverres_files.file_name) as verifier_uploded_attc,GROUP_CONCAT(empverres_files.id) as file_ids,empver.candsid as emp_candsid,empver.nameofthecompany");

		$this->db->from('empverres');

		$this->db->join("empver",'empver.id = empverres.empverid');

		$this->db->join("empverres as ev2",'(ev2.empverid = empver.id and empverres.id < ev2.id)','left');
		
		$this->db->join("empverres_files",'(empverres_files.empver_id = empverres.id AND empverres_files.status = 1) AND empverres_files.type = 1','left');

		if(!empty($emp_ver_id))
		{
			$this->db->where($emp_ver_id);
		}
 		
 		$this->db->where('ev2.verfstatus is null');

 		$this->db->group_by('empverres.id');

		$this->db->order_by('id', 'desc');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	

	

	public function delete_uploaded_file($where = array())
	{	
		$this->db->where_in('id',$where);

		$this->db->set('status', STATUS_DELETED);

		$result = $this->db->update('empverres_files', array('status' => STATUS_DELETED));

		record_db_error($this->db->last_query());

		return $result;
	}

	public function add_uploaded_file($where = array())
	{	
		$this->db->where_in('id',$where);

		$this->db->set('status', STATUS_ACTIVE);

		$result = $this->db->update('empverres_files', array('status' => STATUS_ACTIVE));

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

	public function save_update_empver_files($arrdata,$arrwhere = array())
	{
		if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update('empverres_files', $arrdata);

			record_db_error($this->db->last_query());

			return $result;
	    }
	    else
	    {
			$this->db->insert('empverres_files', $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	    }
	}


	public function emp_verfstatus_update($arrdata,$arrwhere = array())
	{
		if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update('empverres', $arrdata);

			record_db_error($this->db->last_query());

			return $result;
	    }
	    
	}


	
	// public function emp_verification_report($empver_aary = array())
	// {
	// 	$this->db->select("empver.*,clients.clientname,cands.CandidateName,knowncos.coname,GROUP_CONCAT(empverres_files.file_name) as file_names,GROUP_CONCAT(empverres_files.id) as empverres_files_ids,empverres.verfname,empverres.verfdesgn,empverres.reasonforleaving,empverres.natureofseparation,empverres.integrity_disciplinary_issue,empverres.eligforrehire,empverres.exitformalities,empverres.addlhrcomments,empverres.employed_from,empverres.employed_to,empverres.emp_designation");

	// 	$this->db->from('empver');

	// 	$this->db->join("cands",'cands.id = empver.candsid');

	// 	$this->db->join("clients",'clients.id = cands.clientid');

	// 	$this->db->join("knowncos",'knowncos.id = empver.nameofthecompany');

	// 	$this->db->join("empverres",'empverres.empverid = empver.id');

	// 	$this->db->join("empverres_files",'(empverres_files.empver_id = empver.id AND empverres_files.status = 1)','left');

	// 	if(!empty($empver_aary))
	// 	{
	// 		$this->db->where($empver_aary);
	// 	}

	// 	$this->db->group_by('empver.id');

	// 	$this->db->order_by('id', 'desc');
		
	// 	$result = $this->db->get();

	// 	record_db_error($this->db->last_query());

	// 	return $result->result_array();
	// }

	

	

	public function check_company_exits_like($fields = array())
	{
		$this->db->select('id');

		$this->db->from('knowncos');

		$this->db->where('coname', $fields['coname']);

		$result = $this->db->get()->row();
		
		if($result != "")
		{
			return $result->id;
		}
		else
		{
			return $this->add_new_company($fields);
		}
	}

	public function get_ever_id($where_array)
	{
		$this->db->select('addrver.id,cands.id as cands');

		$this->db->from('addrver');

		$this->db->join("cands",'cands.id = addrver.candsid');

		$this->db->where($where_array);

		$result  = $this->db->get();
		
		$result_array = $result->result_array();
		
        if(!empty($result_array)) // ensure only one record has been previously inserted
        {
            $result_array  = $result_array[0];
        }

        return $result_array;
	}

	public function select_emp_ver_result($id)
	{
		$this->db->select('ev1.id');

		$this->db->from('empver');
		
		$this->db->join("empverres as ev1",'(ev1.empverid = empver.id and ev1.clientid = 11)');

		$this->db->join("empverres as ev2",'(ev2.empverid = empver.id and ev1.id < ev2.id)','left');

		$this->db->where('ev2.verfstatus is null');

		$this->db->where($id);

		$this->db->order_by('ev1.id', 'desc');
	
		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		$result_array = $result->result_array();

        if(!empty($result_array))
		{
            $result_array  = $result_array[0];
        }
        return $result_array;
	}

	public function upload_vendor_assign($tableName,$updateArray,$where_arry)
	{		
		$this->db->where($where_arry);
	 	$this->db->update($tableName,$updateArray);
		return $this->db->affected_rows();
	}

	public function employment_ver_status_count($where = array())
	{
		$this->db->select('count(empver.id) as total,IF(ev1.verfstatus IS NOT NULL, ev1.verfstatus ,"WIP") as overallstatus');

		$this->db->from('empver');
		
		$this->db->join("empverres as ev1",'ev1.empverid = empver.id','left');

		$this->db->join("empverres as ev2",'(ev2.empverid = empver.id and ev1.id < ev2.id)','left');

		$this->db->where('ev2.verfstatus is null');

		if(!empty($where))
		{
			$this->db->where($where);
		}

		$this->db->group_by('overallstatus');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$results = convert_to_single_dimension_array($result->result_array(),'overallstatus','total');

		$return = array();

		$return['total'] = array_sum($results);

        if(!empty($results)) {

        	if(array_key_exists('No Record Found',$results)) {
	            $results['Discrepancy'] = $results['Discrepancy']+$results['No Record Found'];
	        }

	        if(array_key_exists('Insufficiency I',$results)) {
	            $results['Insufficiency'] = $results['Insufficiency']+$results['Insufficiency I'];
	        }

	        if(array_key_exists('Insufficiency II',$results)) {
	            $results['Insufficiency'] = $results['Insufficiency']+$results['Insufficiency II'];
	        }

	        if(array_key_exists('Insufficiency-Relieving Letter Required',$results)) {
	            $results['Insufficiency'] = $results['Insufficiency']+$results['Insufficiency-Relieving Letter Required'];
	        }

	        if(array_key_exists('case initiated',$results)) {
	            $results['WIP'] = $results['WIP']+$results['case initiated'];
	        }

	        if(array_key_exists('WIP-Initiated',$results)) {
	            $results['WIP'] = $results['WIP']+$results['WIP-Initiated'];
	        }

	        if(array_key_exists('WIP–pending for clarification',$results)) {
	            $results['WIP'] = $results['WIP']+$results['WIP–pending for clarification'];
	        }

	        if(array_key_exists('Work With the Same Organization',$results)) {
	            $results['Stop/Check'] = $results['Stop/Check']+$results['Work With the Same Organization'];
	        }

	        if(array_key_exists('Inaccessible',$results)) {
	            $results['Clear'] = $results['Clear']+$results['Inaccessible'];
	        }

	        if(array_key_exists('No-Response',$results)) {
	            $results['Clear'] = $results['Clear']+$results['No-Response'];
	        }

            foreach ($results as $key => $value) {
                $return[str_replace('/','',str_replace(' ','',$key))] = $value;
            }
        }

        return $return;
	}

	public function get_employment_cases_by_date($date,$where = '')
	{
		$sql = 'SELECT count(empver.id) as total_count FROM empver 
					inner join empverres as ev1 on ev1.empverid = empver.id
					left join empverres as ev2 on (ev2.empverid = empver.id and ev1.id < ev2.id)
					where DATE_FORMAT(ev1.created ,"%Y-%m-%d") >= "'.$date.'" AND DATE_FORMAT(ev1.created ,"%Y-%m-%d") <= "'.$date.'" AND ev1.verfstatus = "clear" AND ev2.verfstatus is null';
		if($where)
		{
			$sql .= ' AND empver.has_case_id = '.$where;
		}

		$query = $this->db->query($sql);

		$results = $query->row_array();
		
		return (!empty($results) ? $results['total_count'] : 0);
	}


	public function vendor_logs($where_array,$id)
    {
        $this->db->select("view_vendor_master_log.*,(select vendor_name from vendors where vendors.id= employment_vendor_log.vendor_id) as vendor_name,employment_vendor_log.case_id,(select user_name from user_profile where id = view_vendor_master_log.allocated_by) as allocated_by,(select user_name from user_profile where id = view_vendor_master_log.approval_by) as approval_by");

        $this->db->from('view_vendor_master_log');
        $this->db->join('employment_vendor_log','employment_vendor_log.id = view_vendor_master_log.case_id');

        //$this->db->join('user_profile','user_profile.id = view_vendor_master_log.allocated_by','left');

         // $this->db->join('user_profile','user_profile.id = view_vendor_master_log.approved_by','left');
        $this->db->where($where_array);

        if(!empty($id))
        {
        	 $this->db->where('employment_vendor_log.case_id',$id);
        }

        $this->db->where_in('view_vendor_master_log.status',array('0','1','2','3','5'));

        $this->db->order_by("view_vendor_master_log.id", "desc");

        $result  = $this->db->get();
        
        return $result->result_array();
    }


	public function get_employment_tat($where_array = false)
	{
		$this->db->select("empver.*,cands.ClientRefNumber,cands.cmp_ref_no,cands.CandidateName,cands.caserecddate,cands.updated as closuredate,ev1.verfstatus,ev1.InsuffRaisedDate,ev1.InsuffClearDate,ev1.remarks as remark,empver.reiniated_date,cands.overallstatus,clients.tat_empver as tat_days");

		$this->db->from('empver');

		$this->db->join("cands",'cands.id = empver.candsid');

		$this->db->join("clients",'(clients.id = cands.clientid AND clients.empver = 1)');

		$this->db->join("empverres as ev1",'ev1.empverid = empver.id','left');

		$this->db->join("empverres as ev2",'(ev2.empverid = empver.id and ev1.id < ev2.id)','left');

		$this->db->where('ev2.verfstatus is null');

		if($where_array)
		{
			$this->db->where($where_array);
		}
		
		$this->db->order_by('empver.id', 'desc');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_assigned_cases($where_array = array())
	{
		$this->db->select("empver.*,cands.ClientRefNumber,cands.cmp_ref_no,cands.CandidateName,cands.caserecddate,cands.updated as closuredate,cands.overallstatus,membership_users.custom1");

		$this->db->from('empver');

		$this->db->join("cands",'cands.id = empver.candsid');

		$this->db->join("membership_users",'membership_users.id = empver.has_case_id');
		
		$this->db->join("empverres",'empverres.empverid = empver.id','left outer');

		$this->db->where("empverres.empverid IS NULL",NULL,false);

		if($where_array)
		{
			$this->db->where($where_array);
		}

		$this->db->order_by('empver.id', 'desc');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}
	
	public function get_emp_uploded_files($where_array)
	{
		$this->db->select('*');

		$this->db->from('empverres_files');

		$this->db->where($where_array);

		$this->db->order_by('serialno','asc');

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

	public function get_client_disclosure_details($where)
	{
		$this->db->select('client_disclosures');

		$this->db->from('clients_details');
		
		$this->db->where($where);

        $this->db->limit(1);  

		$result  = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_assign_users($tableName, $return_as_strict_row,$select_array, $where_array=array())
	{

		$this->db->select($select_array);

		$this->db->from($tableName);

		$this->db->join('roles','roles.id = user_profile.tbl_roles_id');

		$this->db->join('roles_permissions','roles_permissions.tbl_roles_id = roles.id');

        $this->db->where('roles_permissions.access_employment_list_assign = 1' ); 

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

        $this->db->where('roles_permissions.access_employment_list_assign = 1' ); 

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


	public function upload_file_update($updateArray)
	{
		$this->db->update_batch('empverres_files',$updateArray, 'id');
		return true; 
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

    public function get_date_for_update($where_array)
    {

        $this->db->select("due_date,tat_status");

        $this->db->from('empver');
   
        $this->db->where($where_array);

        $result  = $this->db->get();
      
        return $result->result_array();
    }


     public function update_due_date($files_arry,$arrwhere )
	{

         if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update("empver", $files_arry);
         
    
			record_db_error($this->db->last_query());
             
			return $result;
	    }
		
	}


	public function select_vendor_result_log($where_array,$id)
    {

        $this->db->select("view_vendor_master_log.*,(select vendor_name from vendors where vendors.id= employment_vendor_log.vendor_id) as vendor_name,(select user_name from user_profile where id = view_vendor_master_log.allocated_by) as allocated_by,(select user_name from user_profile where id = view_vendor_master_log.approval_by) as approval_by,empver.*,empver.id as employment_id,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.CandidateName,(select clientname from clients where clients.id = empver.clientid limit 1) as clientname,
			(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,empver.iniated_date,candidates_info.caserecddate,emp_com_ref,candidates_info.entity as entity_id,candidates_info.package as package_id,(select id from empverres where empverres.empverid = empver.id) as empverres_id,view_vendor_master_log.id as view_vendor_master_log_id");



        $this->db->from('view_vendor_master_log');
        $this->db->join('employment_vendor_log','employment_vendor_log.id = view_vendor_master_log.case_id');

        $this->db->join('empver','empver.id = employment_vendor_log.case_id');

   

		$this->db->join("candidates_info",'candidates_info.id = empver.candsid');

        //$this->db->join('user_profile','user_profile.id = view_vendor_master_log.allocated_by','left');

         // $this->db->join('user_profile','user_profile.id = view_vendor_master_log.approved_by','left');
        $this->db->where($where_array);

        if(!empty($id))
        {
        	 $this->db->where('view_vendor_master_log.id',$id);
        }

        $result  = $this->db->get();
        //print_r($this->db->last_query());
        return $result->result_array();
    }


    public function select_vendor_result_log_cost($where_array,$id)
    {

        $this->db->select("view_vendor_master_log.*");



        $this->db->from('view_vendor_master_log');
   

        //$this->db->join('user_profile','user_profile.id = view_vendor_master_log.allocated_by','left');

         // $this->db->join('user_profile','user_profile.id = view_vendor_master_log.approved_by','left');
        $this->db->where($where_array);

        if(!empty($id))
        {
        	 $this->db->where('view_vendor_master_log.id',$id);
        }

        $result  = $this->db->get();
        //print_r($this->db->last_query());
        return $result->result_array();
    }

     public function select_vendor_result_log_cost_details($where_array,$id)
    {

        $this->db->select("vendor_cost_details.*,(select user_name from user_profile where id = vendor_cost_details.created_by) as requested_by,(select user_name from user_profile where id = vendor_cost_details.approved_by) as approved_by");



        $this->db->from('vendor_cost_details');
   

       // $this->db->join('user_profile','user_profile.id = vendor_cost_details.created_by','left');

         // $this->db->join('user_profile','user_profile.id = view_vendor_master_log.approved_by','left');
        $this->db->where($where_array);

        if(!empty($id))
        {
        	 $this->db->where('vendor_cost_details.vendor_master_log_id',$id);
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


	public function save_vendor_details_costing($tablename,$arrdata)
	{
	
			$this->db->insert($tablename, $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	  
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
    

     public function update_address_vendor_log($tablename,$arrdata,$arrwhere = array())
	{
	    if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update($tablename, $arrdata);
       
			record_db_error($this->db->last_query());
             
			return $result;
	    }
	    
	}
    
    /*public function address_vendor_details_cancel($tablename,$arrdata)
	{
	
			$this->db->insert($tablename, $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	  
	}
*/


	public function get_vendor_cost_aprroval_details()
	{
	
	   $query = "select view_vendor_master_log.id as vendor_master_log_id,view_vendor_master_log.case_id as view_vendor_master_case_id,view_vendor_master_log.trasaction_id,view_vendor_master_log.status as view_vendor_master_status,view_vendor_master_log.component as view_vendor_master_components,view_vendor_master_log.component_tbl_id as component_tbl_id,empver.emp_com_ref,empver.deputed_company,empver.employment_type,empver.locationaddr,empver.citylocality,empver.pincode,empver.state,vendor_cost_details.cost,vendor_cost_details.additional_cost,vendor_cost_details.accept_reject_cost,vendor_cost_details.id as vendor_cost_details_id,vendor_cost_details.created_on,(select user_name from user_profile where user_profile.id= employment_vendor_log.created_by) as created_by,(select vendor_name from vendors where vendors.id= employment_vendor_log.vendor_id) as vendor_name from view_vendor_master_log  JOIN `employment_vendor_log` ON `employment_vendor_log`.`id` = `view_vendor_master_log`.`case_id` JOIN `empver` ON `empver`.`id` = `employment_vendor_log`.`case_id` JOIN `candidates_info` ON `candidates_info`.`id` = `empver`.`candsid` LEFT JOIN vendor_cost_details 
    ON vendor_cost_details.id = (SELECT id  FROM vendor_cost_details WHERE 	vendor_master_log_id = view_vendor_master_log.id  ORDER BY id DESC LIMIT 1) WHERE (view_vendor_master_log.status != 4 and view_vendor_master_log.status != 5 and view_vendor_master_log.status != 6 and view_vendor_master_log.component = 'empver' and view_vendor_master_log.component_tbl_id = 2)"	;

     $result = $this->db->query($query);


	return $result->result_array();
     
	}

	public function approve_cost($arrdata,$arrwhere = array())
	{
	    if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update("vendor_cost_details", $arrdata);
       
			record_db_error($this->db->last_query());
             
			return $result;
	    }
	    
	}

	public function approve_cost_vendor($arrdata,$arrwhere = array())
	{
	    if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update("view_vendor_master_log", $arrdata);
       
			record_db_error($this->db->last_query());
             
			return $result;
	    }
	    
	}
    
    
    public function reject_cost($arrdata,$arrwhere = array())
	{
	    if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update("vendor_cost_details", $arrdata);
       
			record_db_error($this->db->last_query());
             
			return $result;
	    }
	    
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

	public function uploaded_venodr_cost_file($files_arry,$arrwhere )
	{


		 if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update("vendor_cost_details", $files_arry);
         
     

			record_db_error($this->db->last_query());
             
			return $result;
	    }
	}

	public function save_activity_log_trigger($arrdata)
    { 

	    $this->db->insert("activity_log", $arrdata);

		record_db_error($this->db->last_query());

		return $this->db->insert_id();
	       
    }
    
    public function employment_case_list($where_array = array(),$where)
    {
        $this->db->select("view_vendor_master_log.*,CandidateName,clients.clientname,empver.emp_com_ref,empver.id as employment_id,empver.nameofthecompany,empver.deputed_company,empver.employment_type,empver.locationaddr,empver.citylocality,empver.state,empver.pincode,empver.vendor_list_mode,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select coname from company_database where company_database.id = empver.nameofthecompany limit 1) as company_name,(select vendor_name from vendors where vendors.id = empver.vendor_id) as vendor_name,(select user_name from vendor_executive_login where vendor_executive_login.id = view_vendor_master_log.has_case_id limit 1) as vendor_executive_id,candidates_info.ClientRefNumber");

        $this->db->from('view_vendor_master_log');
      
        $this->db->join('employment_vendor_log','employment_vendor_log.id = view_vendor_master_log.case_id');
        $this->db->join('empver','empver.id = employment_vendor_log.case_id');
        $this->db->join('candidates_info','candidates_info.id = empver.candsid');
       
        $this->db->join("clients",'clients.id = empver.clientid');


        $this->db->where($where_array);

        if($where != "All")
        {

          $this->db->where('empver.has_case_id', $where);

        }
   
        $result = $this->db->get();
    
       return $result->result_array();
       
    }

    public function employment_case_list_insuff($where_array = array(),$where)
    {
       $admin_status = "(empverres.verfstatus = 1 or empverres.verfstatus = 11 or empverres.verfstatus = 12 or empverres.verfstatus = 13 or empverres.verfstatus = 14 or empverres.verfstatus = 16 or empverres.verfstatus = 23 or empverres.verfstatus = 26 )";
    	

        $this->db->select("view_vendor_master_log.*,CandidateName,clients.clientname,empver.id as employment_id,empver.emp_com_ref,(select coname from company_database where company_database.id = empver.nameofthecompany limit 1) as company_name,empver.vendor_list_mode,(select vendor_name from vendors where vendors.id = empver.vendor_id) as vendor_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,candidates_info.ClientRefNumber");
        
        $this->db->from('view_vendor_master_log');
      
        $this->db->join('employment_vendor_log','employment_vendor_log.id = view_vendor_master_log.case_id');

        $this->db->join('empver','empver.id = employment_vendor_log.case_id');

        $this->db->join('empverres','empverres.empverid = empver.id');

        $this->db->join('status','status.id = empverres.verfstatus');

        $this->db->join('candidates_info','candidates_info.id = empver.candsid');
       
        $this->db->join("clients",'clients.id = empver.clientid');


        $this->db->where($where_array);

        $this->db->where($admin_status);

        if($where != "All")
        {

          $this->db->where('empver.has_case_id', $where);

        }
   
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

	 public function get_user_id($where_array)
	{
		$this->db->select('id');

		$this->db->from('user_profile');

		$this->db->where($where_array);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->row('id');
	}

	 public function get_client_id($where_array )
	{
     
	  $this->db->select("view_vendor_master_log.id,view_vendor_master_log.case_id as  vendor_master_log_case, employment_vendor_log.id as employment_vendor_log_id,employment_vendor_log.case_id as employment_vendor_log_case,empver.id as empver_id , empver.clientid as client_id" );
        $this->db->from('view_vendor_master_log');
       // $this->db->join('addrver','addrver.id = view_vendor_master_log.component_tbl_id');
        $this->db->join('employment_vendor_log','employment_vendor_log.id = view_vendor_master_log.case_id');
        $this->db->join('empver','empver.id = employment_vendor_log.case_id');
        $this->db->join("clients",'clients.id = empver.clientid');


        $this->db->where($where_array);
        
        $result = $this->db->get();
  
       return $result->result_array();
	}
    
    public function check_vendor_status_insufficiency($where_array )
	{
     
	  $this->db->select("view_vendor_master_log.id,view_vendor_master_log.final_status,view_vendor_master_log.case_id as  vendor_master_log_case, employment_vendor_log.id as employment_vendor_log_id,employment_vendor_log.case_id as vemployment_vendor_log_case,empver.id as employment_id" );

        $this->db->from('view_vendor_master_log');
      
        $this->db->join('employment_vendor_log','employment_vendor_log.id = view_vendor_master_log.case_id');

        $this->db->join('empver','empver.id = employment_vendor_log.case_id');
    
        $this->db->where($where_array);

        $this->db->where('empver.vendor_id !=', 0);
        
        $result = $this->db->get();
 
        return $result->result_array();
	}
    
	public function get_hr_database_details_model($where_arry)
	{
       
		
		$this->db->select("company_database_verifiers_details.*,company_database.coname,user_profile.user_name,(select user_name from user_profile where user_profile.id = company_database_verifiers_details.modified_by) as modified_name");

		$this->db->from('company_database_verifiers_details');

	    $this->db->join("company_database",'company_database.id = company_database_verifiers_details.company_database_id');

	    
	    $this->db->join("user_profile",'user_profile.id = company_database_verifiers_details.created_by','left');

		if($where_arry)
		{
			
			$this->db->like('company_database.coname', $where_arry);
		}
		
		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();
	}
	public function select_email_id_verifiers($where_array)
	{
       $this->db->select("company_database_verifiers_details.id,company_database_verifiers_details.verifiers_email_id");

		$this->db->from('company_database_verifiers_details');

	    $this->db->where($where_array);

	    $this->db->order_by('company_database_verifiers_details.id','desc');

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();
	}

    public function select_candidate_from_file($id)
	{
		$this->db->select('file_name');

		$this->db->from('empverres_files');

		$this->db->join('empver','empver.id = empverres_files.empver_id');
       
        $this->db->join('candidates_info','candidates_info.id = empver.candsid');

		$this->db->where('candidates_info.id',$id);
		
		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	} 	 


    public function update_final_status_of_vendor($employment_id)
	{

	    if(!empty($employment_id))
	    {

			$sql =  'UPDATE view_vendor_master_log LEFT JOIN employment_vendor_log ON employment_vendor_log.case_id = view_vendor_master_log.id LEFT JOIN empver ON empver.id = employment_vendor_log.case_id LEFT JOIN empverres ON empverres.empverid = empver.id SET view_vendor_master_log.final_status = "closed" WHERE (empverres.verfstatus = 9 or empverres.verfstatus = 27 or empverres.verfstatus = 28) and (view_vendor_master_log.component = "empver" and view_vendor_master_log.component_tbl_id = 2 and (view_vendor_master_log.final_status = "wip" or view_vendor_master_log.final_status = "insufficiency")) AND empver.id = '.$employment_id; 

            $query = $this->db->query($sql);
            
            return $query;
           
	    }   
	}
    
    public function select_client_list_view_employment($tableName, $return_as_strict_row,$select_array, $where1=array())
	{
		$this->db->select($select_array);
       
		$this->db->from($tableName);

		$this->db->join("empver",'empver.clientid = clients.id');

		$this->db->join("user_profile",'user_profile.id = empver.has_case_id','left');

		$this->db->join("candidates_info",'candidates_info.id = empver.candsid');

	
		$this->db->join("empverres as ev1",'ev1.empverid = empver.id','left');


		$this->db->join("empverres_insuff",'(empverres_insuff.empverres_id = empver.id AND  empverres_insuff.status = 1 )','left');

		$this->db->join("status",'status.id = ev1.verfstatus','left');

        $this->db->where($this->filter_where_cond($where1)); 

        $this->db->where('clients.status',1); 


            if(isset($where1['start_date']) &&  $where1['start_date'] != '' && isset($where1['end_date']) &&  $where1['end_date'] != '')	
		    { 

		     	$start_date  =  $where1['start_date'];
	            $end_date  =  $where1['end_date'];

	            if($where1['status'] == "Closed")
                {
	         
		     	$where3 = "DATE_FORMAT(`ev1`.`closuredate`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}

		     	if($where1['status'] == "Insufficiency")
                {
	         
		     	$where3 = "DATE_FORMAT(`empverres_insuff`.`insuff_raised_date`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

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


	public function get_company_name($wheredata)
	{
		$this->db->select("id,coname as company_name");

		$this->db->from('company_database');

		$this->db->like('company_database.coname', $wheredata,'after');

		$this->db->where('status',1);

		$this->db->where('dropdown_status',1);

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array(); 
		
	}

	public function select_required_field($wheredata)
	{
		$this->db->select("previous_emp_code,branch_location,experience_letter,loa,auto_initiate,follow_up,co_email_id,cc_email_id,client_disclosure");

		$this->db->from('company_database');

		$this->db->where($wheredata);

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array(); 
		
	}

	public function get_candidate_and_ref_no($wheredata)
	{
		$this->db->select("previous_emp_code,branch_location,experience_letter,loa,auto_initiate,follow_up,co_email_id");

		$this->db->from('company_database');

		$this->db->where($wheredata);

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array(); 
		
	}


	public function get_user_email_id($where_array)
	{
		$this->db->select('email');

		$this->db->from('user_profile');

		$this->db->where($where_array);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->row('email');
	}


	public function get_candidate_entity_package($where_array)
	{
		$this->db->select('clientid,entity,package');

		$this->db->from('candidates_info');

		$this->db->where($where_array);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array(); 
	}

	public  function get_reporting_manager_id_client($clientid)
    {
    	
        $this->db->select('clientmgr');

		$this->db->from('clients');

		$this->db->where('clients.id',$clientid);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
    
    }
    public function check_employment_exists_in_candidate($where_array)
    { 

    	$this->db->select('id');

		$this->db->from('empver');

		$this->db->where($where_array);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();

    }

    public function update_status($tableName,$updateArray,$where_arry)
	{	
	   
		$this->db->where($where_arry);
	 	$this->db->update($tableName,$updateArray);
		return $this->db->affected_rows();
	}

	public function emp_initial_mail_details($where_array)
	{
		$this->db->select('emp_mail_details.*,user_profile.firstname,user_profile.lastname,user_profile.designation,user_profile.email,user_profile.department,user_profile.department,user_profile.designation,user_profile.office_phone,user_profile.mobile_phone');

		$this->db->from('emp_mail_details');

		$this->db->join("user_profile",'user_profile.id = emp_mail_details.created_by');

		$this->db->where($where_array);

		$this->db->order_by('emp_mail_details.id', 'desc');

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
		
	}

	public function select_table_value($tableName,$arrdata,$arrwhere)
    {
    	$this->db->select($arrdata);

		$this->db->from($tableName);
  
        if($arrwhere)
        {

		  $this->db->where($arrwhere);

	    }

		$result  = $this->db->get();
     
		record_db_error($this->db->last_query());
		
		return $result->result_array();
    }

    public function get_employment_details_for_insuff_mail($where_array)
    {
    	$this->db->select('empver.*,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,(select clientname from clients where clients.id = candidates_info.clientid limit 1) as clientname,(select coname from company_database where company_database.id = empver.nameofthecompany limit 1) as company_name');

		$this->db->from('empver');

		$this->db->join('candidates_info','candidates_info.id = empver.candsid');

        if($where_array)
        {
		  $this->db->where($where_array);
	    }

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

    public function get_vendor_id_find_state($state = array())
	{ 
		$this->db->select('id,vendor_name');
       
		$this->db->from('vendors');

		if(!empty($state))
		{

		$this->db->where("find_in_set('".$state."', employment_states)"); 
		}
	
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$result_array = $result->result_array();

	    return convert_to_single_dimension_array($result_array,'id','vendor_name');
		
	}

	public function get_vendor_id_find_city($city)
	{ 
		$this->db->select('id');
       
		$this->db->from('vendors');

		
		if(!empty($city))
		{

		$this->db->where("find_in_set('".$city."', employment_city)"); 
		}

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$result_array = $result->result_array();

		return $result_array;

	}
	public function select_employment_dt($table_name,$select_array,$where_array)
	{
		$this->db->select($select_array);

		$this->db->from($table_name);

		$this->db->where($where_array);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
        
        return $result->result_array();    
	} 

	public function get_employment_details_for_approval($case_id)
    {
    	$this->db->select('employment_vendor_log.id as employment_vendor_log_id,clients.clientname,empver.emp_com_ref,candidates_info.CandidateName,candidates_info.CandidatesContactNumber,candidates_info.ContactNo1,candidates_info.ContactNo2,empver.vendor_id,empver.locationaddr,empver.citylocality,empver.pincode,empver.state');

		$this->db->from('employment_vendor_log');

		$this->db->join('empver','empver.id = employment_vendor_log.case_id ');

        $this->db->join('candidates_info','candidates_info.id = empver.candsid');

        $this->db->join('clients','clients.id = empver.clientid');

		$this->db->where('employment_vendor_log.id',$case_id);

		$result  = $this->db->get();
     
		record_db_error($this->db->last_query());
		
		return $result->result_array();
    }

    public function select_file_employment($select_array,$where_array)
	{
		$this->db->select($select_array);

		$this->db->from('empverres_files');

		$this->db->where($where_array);

		$this->db->order_by('serialno', 'ASC');
		
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

	public function select_file_vendor($select_array,$where_array,$where_array_id)
	{
		$this->db->select($select_array);

		$this->db->from('view_vendor_master_log_file');

		$this->db->join('view_vendor_master_log','view_vendor_master_log.id = view_vendor_master_log_file.view_venor_master_log_id');

        $this->db->join('employment_vendor_log','employment_vendor_log.id = view_vendor_master_log.case_id');

        $this->db->join("empver",'empver.id = employment_vendor_log.case_id');

		$this->db->where($where_array);

		$this->db->where('empver.id',$where_array_id);
		
		$this->db->order_by('view_vendor_master_log_file.serialno','asc');
		
		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}
	
    
   
}
?>