<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Education_model extends CI_Model
{
	function __construct()
    {
		//parent::__construct();

		$this->tableName = 'education';

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

    public function save_update($arrdata,$arrwhere = array())
	{
	    if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update('education_result', $arrdata);

			record_db_error($this->db->last_query());

			return $result;
	    }
	   
	} 

	public function select_insuff($where_array)
	{
		$this->db->select('*')->from('education_insuff');

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

			$result = $this->db->update('education_insuff', $arrdata);
			
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
			$where['education_result.var_filter_status'] = $where_arry['status'];
		    }
		}

		if(isset($where_arry['sub_status']) && $where_arry['sub_status'] != '' && $where_arry['sub_status'] != 0)	
		{
			$where['education_result.verfstatus'] = $where_arry['sub_status'];
		}

		if(isset($where_arry['client_id']) &&  $where_arry['client_id'] != 0)	
		{
			$where['education.clientid'] = $where_arry['client_id'];
		}
		if(isset($where_arry['filter_by_executive']) &&  $where_arry['filter_by_executive'] != 0)	
		{ 
		  if($where_arry['filter_by_executive'] != "All")
	       {
             $where['education.has_case_id'] = $where_arry['filter_by_executive'];
	       }
        } 
		return $where;

	}

	
	public function get_all_education_record_datatable($where,$columns)
	{
		$this->db->select("candidates_info.CandidateName,candidates_info.id candidates_info_id,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = education.has_case_id) as executive_name,status.status_value,education.education_com_ref,education.mode_of_veri,school_college,university_board,grade_class_marks,qualification,major,course_start_date,course_end_date,month_of_passing,year_of_passing,roll_no,enrollment_no,PRN_no,documents_provided,genuineness,education_result.first_qc_approve,education_result.verfstatus,education_result.first_qc_updated_on,education_result.first_qu_reject_reason,education.id as education_id,education.has_assigned_on,education.iniated_date,clients.clientname,education.vendor_id,(select created_on from education_activity_data where comp_table_id = education.id order by id desc limit 1) as last_activity_date,due_date,tat_status,closuredate,education_result.remarks,(select vendor_name from vendors where vendors.id = education.vendor_id) as vendor_name,education_insuff.insuff_raised_date");

		$this->db->from('education');

		$this->db->join("education_result",'education_result.education_id =education.id');
		
		$this->db->join("candidates_info",'candidates_info.id = education.candsid');

	    $this->db->join("education_insuff",'(education_insuff.education_id = education.id AND  education_insuff.status = 1 )','left');

		$this->db->join("clients",'clients.id = education.clientid');

		$this->db->join("status",'status.id = education_result.verfstatus');

		$this->db->where('education.fill_by','2'); 
        
        $this->db->where('education_result.verfstatus','18');

        $this->db->where('education.candsid',$this->candidate_info['cands_info_id']); 

		$this->db->where($this->filter_where_cond($where));

		 if(isset($where['start_date']) &&  $where['start_date'] != '' && isset($where['end_date']) &&  $where['end_date'] != '')	
		    { 

		     	$start_date  =  $where['start_date'];
	            $end_date  =  $where['end_date'];

	            if($where['status'] == "Closed")
                {
	         
		     	$where3 = "DATE_FORMAT(`education_result`.`closuredate`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}

		     	if($where['status'] == "Insufficiency")
                {
	         
		     	$where3 = "DATE_FORMAT(`education_insuff`.`insuff_raised_date`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}
                
                $this->db->where($where3); 

		    } 


		if(is_array($where) && $where['search']['value'] != "")
		{

			$this->db->like('candidates_info.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('candidates_info.cmp_ref_no', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);

			$this->db->or_like('education.education_com_ref', $where['search']['value']);

			$this->db->or_like('education.iniated_date', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where['search']['value']);

			
		}
		
		$this->db->limit($where['length'],$where['start']);
		
		
        

        if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 
            
        //   $this->db->where('education.vendor_id !=',0);


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

	public function get_all_education_record_datatable_count($where,$columns)
	{
		$this->db->select("candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = education.has_case_id) as user_name,status.status_value,education.education_com_ref,education.city,education.state,education_result.verfstatus,education_result.first_qc_approve,education_result.first_qc_updated_on,education_result.first_qu_reject_reason,education.id,education.has_assigned_on,education.iniated_date,clients.clientname,education.vendor_id,(select created_on from education_activity_data where comp_table_id = education.id order by id desc limit 1) as last_activity_date,due_date,tat_status,closuredate,education_result.remarks,(select vendor_name from vendors where vendors.id = education.vendor_id) as vendor_name, education_insuff.insuff_raised_date");

		$this->db->from('education');

		$this->db->join("education_result",'education_result.education_id = education.id');
		
		$this->db->join("candidates_info",'candidates_info.id = education.candsid');

	    $this->db->join("education_insuff",'(education_insuff.education_id = education.id AND  education_insuff.status = 1 )','left');

	    $this->db->join("clients",'clients.id = education.clientid');

		$this->db->join("status",'status.id = education_result.verfstatus');

		$this->db->where('education.fill_by','2'); 
        
        $this->db->where('education_result.verfstatus','18');

        $this->db->where('education.candsid',$this->candidate_info['cands_info_id']); 

		$this->db->where($this->filter_where_cond($where));

		if(isset($where['start_date']) &&  $where['start_date'] != '' && isset($where['end_date']) &&  $where['end_date'] != '')	
		    { 

		     	$start_date  =  $where['start_date'];
	            $end_date  =  $where['end_date'];

	            if($where['status'] == "Closed")
                {
	         
		     	$where3 = "DATE_FORMAT(`education_result`.`closuredate`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}

		     	if($where['status'] == "Insufficiency")
                {
	         
		     	$where3 = "DATE_FORMAT(`education_insuff`.`insuff_raised_date`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}
                
                $this->db->where($where3); 

		    } 


		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like('candidates_info.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('candidates_info.cmp_ref_no', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);

			$this->db->or_like('education.education_com_ref', $where['search']['value']);

			$this->db->or_like('education.iniated_date', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where['search']['value']);

			
		}
				
		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 
            
          // $this->db->where('education.vendor_id !=',0);


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


	public function get_all_education_submit_by_candidate_datatable($where,$columns)
	{
		$this->db->select("candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = education.has_case_id) as executive_name,status.status_value,education.education_com_ref,education.mode_of_veri,school_college,university_board,grade_class_marks,qualification,major,course_start_date,course_end_date,month_of_passing,year_of_passing,roll_no,enrollment_no,PRN_no,documents_provided,genuineness,education_result.first_qc_approve,education_result.verfstatus,education_result.first_qc_updated_on,education_result.first_qu_reject_reason,education.id,education.has_assigned_on,education.iniated_date,clients.clientname,education.vendor_id,(select created_on from education_activity_data where comp_table_id = education.id order by id desc limit 1) as last_activity_date,due_date,tat_status,closuredate,education_result.remarks,(select vendor_name from vendors where vendors.id = education.vendor_id) as vendor_name,education_insuff.insuff_raised_date");

		$this->db->from('education');

		$this->db->join("education_result",'education_result.education_id =education.id');
		
		$this->db->join("candidates_info",'candidates_info.id = education.candsid');

	    $this->db->join("education_insuff",'(education_insuff.education_id = education.id AND  education_insuff.status = 1 )','left');

		$this->db->join("clients",'clients.id = education.clientid');

		$this->db->join("status",'status.id = education_result.verfstatus');

		$this->db->where('education.fill_by','2'); 
        
        $this->db->where('education_result.verfstatus !=','18');

        $this->db->where('education.candsid',$this->candidate_info['cands_info_id']); 


		$this->db->where($this->filter_where_cond($where));

		 if(isset($where['start_date']) &&  $where['start_date'] != '' && isset($where['end_date']) &&  $where['end_date'] != '')	
		    { 

		     	$start_date  =  $where['start_date'];
	            $end_date  =  $where['end_date'];

	            if($where['status'] == "Closed")
                {
	         
		     	$where3 = "DATE_FORMAT(`education_result`.`closuredate`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}

		     	if($where['status'] == "Insufficiency")
                {
	         
		     	$where3 = "DATE_FORMAT(`education_insuff`.`insuff_raised_date`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}
                
                $this->db->where($where3); 

		    } 


		if(is_array($where) && $where['search']['value'] != "")
		{

			$this->db->like('candidates_info.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('candidates_info.cmp_ref_no', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);

			$this->db->or_like('education.education_com_ref', $where['search']['value']);

			$this->db->or_like('education.iniated_date', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where['search']['value']);

			
		}
		
		$this->db->limit($where['length'],$where['start']);
		
		
        

        if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 
            
        //   $this->db->where('education.vendor_id !=',0);


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

	public function get_all_education_submit_by_candidate_datatable_count($where,$columns)
	{
		$this->db->select("candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = education.has_case_id) as user_name,status.status_value,education.education_com_ref,education.city,education.state,education_result.verfstatus,education_result.first_qc_approve,education_result.first_qc_updated_on,education_result.first_qu_reject_reason,education.id,education.has_assigned_on,education.iniated_date,clients.clientname,education.vendor_id,(select created_on from education_activity_data where comp_table_id = education.id order by id desc limit 1) as last_activity_date,due_date,tat_status,closuredate,education_result.remarks,(select vendor_name from vendors where vendors.id = education.vendor_id) as vendor_name, education_insuff.insuff_raised_date");

		$this->db->from('education');

		$this->db->join("education_result",'education_result.education_id = education.id');
		
		$this->db->join("candidates_info",'candidates_info.id = education.candsid');

	    $this->db->join("education_insuff",'(education_insuff.education_id = education.id AND  education_insuff.status = 1 )','left');

	    $this->db->join("clients",'clients.id = education.clientid');

		$this->db->join("status",'status.id = education_result.verfstatus');

		$this->db->where('education.fill_by','2'); 
        
        $this->db->where('education_result.verfstatus !=','18');

        $this->db->where('education.candsid',$this->candidate_info['cands_info_id']); 

		$this->db->where($this->filter_where_cond($where));

		if(isset($where['start_date']) &&  $where['start_date'] != '' && isset($where['end_date']) &&  $where['end_date'] != '')	
		    { 

		     	$start_date  =  $where['start_date'];
	            $end_date  =  $where['end_date'];

	            if($where['status'] == "Closed")
                {
	         
		     	$where3 = "DATE_FORMAT(`education_result`.`closuredate`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}

		     	if($where['status'] == "Insufficiency")
                {
	         
		     	$where3 = "DATE_FORMAT(`education_insuff`.`insuff_raised_date`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}
                
                $this->db->where($where3); 

		    } 


		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like('candidates_info.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('candidates_info.cmp_ref_no', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);

			$this->db->or_like('education.education_com_ref', $where['search']['value']);

			$this->db->or_like('education.iniated_date', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where['search']['value']);

			
		}
				
		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 
            
          // $this->db->where('education.vendor_id !=',0);


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

	public function get_all_education_record($where_array)
	{
		$this->db->select("candidates_info.id as cands_id,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = education.has_case_id) as executive_name,status.status_value as verfstatus,education.id as education_id,education.clientid,school_college,(select universityname from university_master where university_master.id = education.university_board limit 1) as actual_university_board,university_board, grade_class_marks,(select qualification from qualification_master where qualification_master.id = education.qualification limit 1) as actual_qualification,qualification,major,DATE_FORMAT(course_start_date,'%d-%m-%Y') as course_start_date,DATE_FORMAT(course_end_date,'%d-%m-%Y') as course_end_date,month_of_passing,year_of_passing,roll_no,enrollment_no,PRN_no,documents_provided,genuineness,city,state,education.education_com_ref,education.mode_of_veri,education.city,education.state as state_id,education_result.id as education_result_id,education_result.var_filter_status,education_result.first_qc_approve,education_result.first_qc_updated_on,education_result.first_qu_reject_reason,education.id,education.edu_re_open_date,education.has_case_id,education.online_URL,education.has_assigned_on,education.iniated_date,education.build_date,(select created_on from education_activity_data where comp_table_id = education.id order by id desc limit 1) as last_activity_date,(select clientname from clients where clients.id = candidates_info.clientid) as clientname,(select state from states where states.id= education.state) as state,due_date,tat_status,res_qualification,res_school_college,res_university_board,res_major,res_month_of_passing,res_year_of_passing,res_grade_class_marks,DATE_FORMAT(res_course_start_date,'%d-%m-%Y') as res_course_start_date,DATE_FORMAT(res_course_end_date,'%d-%m-%Y') as res_course_end_date, res_roll_no,res_enrollment_no,res_PRN_no,res_mode_of_verification,res_online_URL,verifier_designation,verifier_contact_details,res_genuineness,education_result.remarks as remarks,verified_by,closuredate,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,(select id from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_id,(select id from entity_package where entity_package.id = candidates_info.package limit 1) as package_id");

		$this->db->from('education');

		$this->db->join("education_result",'education_result.education_id =education.id');
		
		$this->db->join("candidates_info",'candidates_info.id = education.candsid');
		
		$this->db->join("status",'status.id = education_result.verfstatus');

		$this->db->where($where_array);

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	
}
?>