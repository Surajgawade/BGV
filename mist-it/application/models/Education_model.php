<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Education_model extends CI_Model
{
	function __construct()
    {
		$this->tableName = 'education';

		$this->primaryKey = 'id';
	}

	public function select($return_as_strict_row,$select_array, $where_array = array())
	{
		$this->db->select($select_array);

		$this->db->from($this->tableName);

		$this->db->where($where_array);

		$this->db->order_by('id', 'desc');

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		$result_array = $result->result_array();

        if($return_as_strict_row){

            if(count($result_array) == 1) {
                $result_array  = $result_array[0];
            }
        }
        return $result_array;
	}

	public function update_auto_increament_value($arrdata,$arrwhere = array())
	{
        
		   $this->db->where($arrwhere);

			$result = $this->db->update('education', $arrdata);

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

	public function education_verfstatus_update($arrdata,$arrwhere = array())
	{
		if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update('education_result', $arrdata);

			record_db_error($this->db->last_query());

			return $result;
	    }
	    
	}

	public function save_mail_activity_data($arrdata)
	{
	
		
	    $this->db->insert('education_activity_data', $arrdata);

		record_db_error($this->db->last_query());

		return $this->db->insert_id();
		
	}
	 public function education_mail_details($arrdata)
	{
		
	    $this->db->insert('education_mail_details', $arrdata);

		record_db_error($this->db->last_query());

		return $this->db->insert_id();
		
	}

	public function delete($arrwhere)
	{
	  $result =  $this->db->delete($this->tableName, $arrwhere);

	  record_db_error($this->db->last_query());
	  
	  return $result;
	}

    public function save_education($arrdata,$arrwhere = array())
	{
	    if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update('university_master', $arrdata);

			record_db_error($this->db->last_query());

			return $result;
	    }
	    else
	    {
			$this->db->insert('university_master', $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	    }
	}
     
    public function save_qualification($arrdata,$arrwhere = array())
	{
	    if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update('qualification_master', $arrdata);

			record_db_error($this->db->last_query());

			return $result;
	    }
	    else
	    {
			$this->db->insert('qualification_master', $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	    }
	}

    public function select_university($where_array)
	{
	    $this->db->select('*');

		$this->db->from('university_master');

		$this->db->where($where_array);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array(); 
	}

    public function select_qualification($where_array)
	{
	    $this->db->select('*');

		$this->db->from('qualification_master');

		$this->db->where($where_array);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array(); 
	}


	public function get_all_education_record_datatable($empver_aary = array(),$where,$columns)
	{
		$this->db->select("candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = education.has_case_id) as executive_name,status.status_value,education.education_com_ref,education.mode_of_veri,school_college,(select universityname from university_master where university_master.id = education.university_board) as university_board,grade_class_marks,(select qualification from qualification_master where qualification_master.id = education.qualification) as qualification,major,course_start_date,course_end_date,month_of_passing,year_of_passing,roll_no,enrollment_no,PRN_no,documents_provided,genuineness,education_result.first_qc_approve,education_result.verfstatus,education_result.first_qc_updated_on,education_result.first_qu_reject_reason,education.id,education.has_assigned_on,education.iniated_date,clients.clientname,education.vendor_id,(select created_on from education_activity_data where comp_table_id = education.id order by id desc limit 1) as last_activity_date,due_date,tat_status,closuredate,education_result.remarks,(select vendor_name from vendors where vendors.id = education.vendor_id) as vendor_name,(select vendor_name from vendors where vendors.id = education.vendor_stamp_id) as vendor_stamp_name,education_insuff.insuff_raised_date");

		$this->db->from('education');

		$this->db->join("education_result",'education_result.education_id =education.id');
		
		$this->db->join("candidates_info",'candidates_info.id = education.candsid');

	    $this->db->join("education_insuff",'(education_insuff.education_id = education.id AND  education_insuff.status = 1 )','left');

		$this->db->join("clients",'clients.id = education.clientid');

		$this->db->join("status",'status.id = education_result.verfstatus');

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

			$this->db->or_like('candidates_info.CandidatesContactNumber', $where['search']['value']);

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
            
    
			$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		}
		else
		{
			

		    $this->db->order_by('education.id','desc');
		}    


		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_all_education_record_datatable_count($empver_aary = array(),$where,$columns)
	{
		$this->db->select("candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = education.has_case_id) as user_name,status.status_value,education.education_com_ref,education.city,education.state,education_result.verfstatus,education_result.first_qc_approve,education_result.first_qc_updated_on,education_result.first_qu_reject_reason,education.id,education.has_assigned_on,education.iniated_date,clients.clientname,education.vendor_id,(select created_on from education_activity_data where comp_table_id = education.id order by id desc limit 1) as last_activity_date,due_date,tat_status,closuredate,education_result.remarks,(select vendor_name from vendors where vendors.id = education.vendor_id) as vendor_name,(select vendor_name from vendors where vendors.id = education.vendor_stamp_id) as vendor_stamp_name,education_insuff.insuff_raised_date");

		$this->db->from('education');

		$this->db->join("education_result",'education_result.education_id = education.id');
		
		$this->db->join("candidates_info",'candidates_info.id = education.candsid');

	    $this->db->join("education_insuff",'(education_insuff.education_id = education.id AND  education_insuff.status = 1 )','left');

		
	    $this->db->join("clients",'clients.id = education.clientid');


		$this->db->join("status",'status.id = education_result.verfstatus');

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

			$this->db->or_like('candidates_info.CandidatesContactNumber', $where['search']['value']);

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
            
          
			$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		}
		else
		{
			

		     $this->db->order_by('education.id','desc');
		}    

		$result = $this->db->get();

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

	public function education_com_ref()
	{
		$result = $this->db->select("SUBSTRING_INDEX(education_com_ref, '-',-1) as A_I")->order_by('id','desc')->limit(1)-> get($this->tableName)->row_array();
		return $result;
	}


	 public function get_education_details_first_qc($where_arry = array())
	{
		$this->db->select("education.*,education_result.*,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.CandidateName,(SELECT status_value FROM status WHERE status.id = education_result.verfstatus) verfstatus_name,(select clientname from clients where clients.id = education_result.clientid limit 1) as clientname,(select user_name from user_profile where user_profile.id = education.has_case_id) as executive_name,due_date,tat_status,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,education.iniated_date,candidates_info.caserecddate,education_com_ref,(SELECT GROUP_CONCAT(concat(education.clientid,'/',file_name) ORDER BY serialno ASC SEPARATOR '||') FROM education_files where education_files.education_id = education_result.id and education_files.status = 1 and education_files.type= 1) as add_attachments");

		$this->db->from('education');

		$this->db->join("education_result",'education_result.education_id = education.id');
		
               //  $this->db->order_by('addrverres.id','DESC');

       // $this->db->limit('1');
		$this->db->join("candidates_info",'candidates_info.id = education.candsid');

		//$this->db->join("addrver_files",'(addrver_files.addrver_id = addrver.id AND addrver_files.status = 1 AND addrver_files.type = 0 )','left');
       

		if($where_arry)
		{
			$this->db->where($where_arry);
		}
		
		$result = $this->db->get();
		record_db_error($this->db->last_query());

		return $result->result_array();
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


	public function uploaded_files($files_arry, $return_insert_ids = FALSE)
	{
		$res =  $this->db->insert_batch('education_files', $files_arry);
		
		record_db_error($this->db->last_query());
		
		return $res;
	}

	public  function get_reporting_manager_id()
    {

    	
        $this->db->select('email,reporting_manager,firstname,lastname,designation,department,user_name');

		$this->db->from('user_profile');

		$this->db->where('user_profile.id',$this->user_info['id']);

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

    public function select_file($select_array,$where_array)
	{
		$this->db->select($select_array);

		$this->db->from('education_files');

		$this->db->where($where_array);

		$this->db->order_by('serialno', 'asc');
		
		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function select_file_universtity($table_name,$select_array,$where_array)
	{
		$this->db->select($select_array);

		$this->db->from($table_name);

		$this->db->where($where_array);

		
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

        $this->db->join('education_vendor_log','education_vendor_log.id = view_vendor_master_log.case_id');

        $this->db->join("education",'education.id = education_vendor_log.case_id');

		$this->db->where($where_array);

		$this->db->where('education.id',$where_array_id);
		
		
		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function select_reinitiated_date($where_array)
    {
        
        $this->db->select('education.*');

        $this->db->from('education');

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

            $result = $this->db->update('education', $arrdata);
            
            record_db_error($this->db->last_query());
            
            return $result;
        }
         
    }
    

    public function save_update_initiated_date_education($arrdata,$where_array)
    {
        if(!empty($where_array))
        {
            $this->db->where($where_array);

            $result = $this->db->update('education_result', $arrdata);
            
            record_db_error($this->db->last_query());
            
            return $result;
        }
         
    }


    public function initiated_date_education_activity_data($arrdata)
    {
        
           $this->db->insert('education_activity_data', $arrdata);

            record_db_error($this->db->last_query());

            return $this->db->insert_id();
    }

	public function education_ver_details_for_email($education_aary = array())
	{
		
		$this->db->select("cmp_ref_no,candidates_info.clientid,CandidateName,candidates_info.ClientRefNumber,education.school_college,education.candsid,education.university_board,education.qualification,(select universityname from university_master where university_master.id = education.university_board) as actual_university_board,(select qualification from qualification_master where qualification_master.id = education.qualification limit 1) as actual_qualification,education.roll_no,education.enrollment_no,education.PRN_no,education.month_of_passing,education.year_of_passing,education.grade_class_marks,education.major,ev1.verfstatus,ev1.verified_by,ev1.verifier_designation,ev1.verifier_designation,ev1.remarks,clients.clientname as clientname,GROUP_CONCAT(education_files.file_name) as file_names,GROUP_CONCAT(education_files.id) as education_files_ids");

		$this->db->from('education');


		$this->db->join("education_result as ev1",'ev1.education_id = education.id','left');

		$this->db->join("education_result as ev2",'(ev2.education_id = education.id and ev1.id < ev2.id)','left');

		$this->db->join("candidates_info",'candidates_info.id = education.candsid');
		$this->db->join("clients",'clients.id = education.clientid');


		$this->db->join("education_files",'(education_files.education_id = education.id AND education_files.status = 1) AND education_files.type = 0','left');

		if(!empty($education_aary))
		{
			$this->db->where($education_aary);
		}		
		$this->db->group_by('education.id');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function delete_uploaded_file($where = array())
	{	
		$this->db->where_in('id',$where);

		$this->db->set('status', STATUS_DELETED);

		$result = $this->db->update('education_files', array('status' => STATUS_DELETED));

		record_db_error($this->db->last_query());

		return $result;
	}

	public function add_uploaded_file($where = array())
	{	
		$this->db->where_in('id',$where);

		$this->db->set('status', STATUS_ACTIVE);

		$result = $this->db->update('education_files', array('status' => STATUS_ACTIVE));

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

	public function get_court_uploded_files($where_array)
	{
		$this->db->select('*');

		$this->db->from('education_files');

		$this->db->where($where_array);

		$this->db->order_by('serialno','asc');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();	
	}

	public function upload_file_update($updateArray)
	{
		$this->db->update_batch('education_files',$updateArray, 'id');
		return true; 
	}

	public function save_first_qc_result($files_arry,$arrwhere )
	{

         if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update("education_result", $files_arry);
         
    
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
	    else
	    {
			$this->db->insert('education_insuff', $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	    }
	}

	public function select_insuff_join($where_array)
	{
		$this->db->select('education_insuff.*,(select user_name from user_profile where id =  education_insuff.created_by limit 1) as insuff_raised_by,(select user_name from user_profile where id = education_insuff.insuff_cleared_by limit 1) as insuff_cleared_by');

		$this->db->from('education_insuff');

		$this->db->where($where_array);
		
		$this->db->where('education_insuff.status !=',3);

		$this->db->order_by('education_insuff.id','asc');

		return $this->db->get()->result_array();
	}

	public function save_update_result($arrdata,$arrwhere = array())
	{
	    if(!empty($arrwhere)) {
			$this->db->where($arrwhere);
			return $this->db->update('education_result', $arrdata);
	    } else {
			$this->db->insert('education_result', $arrdata);
			return $this->db->insert_id();
	    }
	}

	public function save_update_result_education($arrdata,$arrwhere = array())
	{
	    if(!empty($arrwhere)) {
			$this->db->where($arrwhere);
			return $this->db->update('education_ver_result', $arrdata);
	    } else {
			$this->db->insert('education_ver_result', $arrdata);
			return $this->db->insert_id();
	    }
	}

	public function export_sql($filter) { 
	
	$sql = "SELECT (select clientname from clients where clients.id = candidates_info.clientid limit 1) as
		client_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name, ClientRefNumber,cmp_ref_no,CandidateName,DATE_FORMAT(caserecddate,'%d-%m-%Y') as caserecddate, (select status_value from status where status.id = education_result.verfstatus limit 1) as verfstatus,education_com_ref,(select user_name from user_profile where user_profile.id = education.has_case_id) as executive_name,school_college,university_board,grade_class_marks,qualification,major,DATE_FORMAT(course_start_date,'%d-%m-%Y') as course_start_date,DATE_FORMAT(course_end_date,'%d-%m-%Y') as course_end_date,month_of_passing,year_of_passing,roll_no,enrollment_no,PRN_no,documents_provided,genuineness,online_URL,city,state,res_mode_of_verification,verified_by,verifier_designation,verifier_contact_details,education_result.remarks,DATE_FORMAT(iniated_date,'%d-%m-%Y') as iniated_date,DATE_FORMAT(due_date,'%d-%m-%Y') as due_date,tat_status,first_qc_updated_on,DATE_FORMAT(closuredate,'%d-%m-%Y') as closuredate,first_qc_approve,(select created_on from education_activity_data where comp_table_id = education.id order by id desc limit 1) as last_activity_date
		FROM education 
		INNER JOIN candidates_info ON candidates_info.id = education.candsid 
		INNER JOIN education_result ON education_result.education_id = education.id ".$filter." ";
		
		$query = $this->db->query($sql);

		return $query->result_array();
	}

	
	public function dashboard_sql($filter) { 
	
	$sql = "SELECT (select clientname from clients where clients.id = candidates_info.clientid limit 1) as
		client_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name, ClientRefNumber,cmp_ref_no,CandidateName,DATE_FORMAT(caserecddate,'%d-%m-%Y') as caserecddate,(select vendor_name from vendors where vendors.id = education.vendor_id) as vendor_name, (select status_value from status where status.id = education_result.verfstatus limit 1) as verfstatus,education_com_ref,(select user_name from user_profile where user_profile.id = education.has_case_id) as executive_name,school_college,(select universityname from university_master where university_master.id = education.university_board limit 1) as university_name,(select qualification from qualification_master where qualification_master.id = education.qualification limit 1) as qualification_name,year_of_passing,DATE_FORMAT(iniated_date,'%d-%m-%Y') as iniated_date,DATE_FORMAT(due_date,'%d-%m-%Y') as due_date,mode_of_veri,tat_status,DATE_FORMAT(closuredate,'%d-%m-%Y') as closuredate,(select created_on from education_activity_data where comp_table_id = education.id order by id desc limit 1) as last_activity_date
		FROM education 
		INNER JOIN candidates_info ON candidates_info.id = education.candsid 
		INNER JOIN education_result ON education_result.education_id = education.id ".$filter." ";
		
		$query = $this->db->query($sql);

		return $query->result_array();
	}
   
	public function upload_vendor_assign($tableName,$updateArray,$where_arry)
	{		
		$this->db->where($where_arry);
	 	$this->db->update($tableName,$updateArray);
		return $this->db->affected_rows();
	}

	public function get_assign_users($tableName, $return_as_strict_row,$select_array, $where_array=array())
	{

		$this->db->select($select_array);

		$this->db->from($tableName);

		$this->db->join('roles','roles.id = user_profile.tbl_roles_id');

		$this->db->join('roles_permissions','roles_permissions.tbl_roles_id = roles.id');

        $this->db->where('roles_permissions.access_education_list_re_assign = 1' ); 

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

        $this->db->where('roles_permissions.access_education_list_assign = 1' ); 

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



        $this->db->from('education');
   

     
        $this->db->where($where_array);

        $result  = $this->db->get();
      
        return $result->result_array();
    }


     public function update_due_date($files_arry,$arrwhere )
	{

         if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update("education", $files_arry);
         
    
			record_db_error($this->db->last_query());
             
			return $result;
	    }
		
	}

	public function select_result_log($where_array)
	{
		$this->db->select('education_ver_result.*,(select created_by from activity_log where id =  education_ver_result.activity_log_id) as created_by1,(select user_name from user_profile where id = created_by1 ) as created_by,(select activity_mode from activity_log where id =  education_ver_result.activity_log_id) as activity_mode ,(select activity_status from activity_log where id =  education_ver_result.activity_log_id) as activity_status ,(select activity_type from activity_log where id =  education_ver_result.activity_log_id) as activity_type,(select action from activity_log where id =  education_ver_result.activity_log_id) as activity_action,(select GROUP_CONCAT(education_files.file_name) from education_files where `education_files`.`education_id` = `education_ver_result`.`education_id` AND `status` = 1 AND `type` = 1)  as file_names,(select GROUP_CONCAT(education_files.file_name) from education_files where `education_files`.`education_id` = `education_ver_result`.`education_id` AND `status` = 1 AND `type` = 4)  as file_university_names,(select GROUP_CONCAT(education_files.id) from education_files where `education_files`.`education_id` = `education_ver_result`.`education_id` AND `status` = 1 AND `type` = 1) as file_ids');

		$this->db->from('education_ver_result');

		$this->db->where($where_array);

		$this->db->order_by('education_ver_result.id','desc');

		return $this->db->get()->result_array();

		
	}

	public function select_result_log1($where_array)
	{
		$this->db->select('education_ver_result.*,education.qualification,education.school_college,education.university_board,education.major,education.month_of_passing,education.	year_of_passing, education.grade_class_marks, education.course_start_date, education.course_end_date,education.roll_no,education.	enrollment_no,education.PRN_no,(select activity_mode from activity_log where id =  education_ver_result.activity_log_id) as activity_mode ,(select activity_status from activity_log where id =  education_ver_result.activity_log_id) as activity_status ,(select activity_type from activity_log where id =  education_ver_result.activity_log_id) as activity_type,(select action from activity_log where id =  education_ver_result.activity_log_id) as activity_action');

		$this->db->from('education_ver_result');

		$this->db->join("education",'education.id = education_ver_result.education_id');

		//$this->db->join("addrverres",'addrverres.addrverid = addrver.id');

		$this->db->where($where_array);
		$this->db->order_by('id','desc');
		//$this->db->where('addrverres.status !=',3);

		return $this->db->get()->result_array();

		
	}

	public function get_all_education_by_client($clientid,$filter_by_status,$from_date,$to_date)
	{	

		$this->db->select("education.*,(select universityname from university_master where university_master.id = education.university_board limit 1) as university_name,(select qualification from qualification_master where qualification_master.id = education.qualification limit 1) as qualification_name,clients.clientname,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.CandidateName,candidates_info.NameofCandidateFather,candidates_info.DateofBirth,status.status_value as verfstatus,status.filter_status as filter_status,ed1.closuredate,(select concat(`user_profile`.`firstname`,' ',`user_profile`.`lastname`) from user_profile where user_profile.id = education.has_case_id limit 1) as executive_name,(select vendor_name from vendors where vendors.id = education.vendor_id limit 1) as vendor_name,(select vendor_name from vendors where vendors.id = education.vendor_stamp_id ) as vendor_stamp_name,(SELECT v.final_status from view_vendor_master_log v, `education_vendor_log` `ed` where ed.case_id = education.id and v.case_id = ed.id and component = 'eduver' and component_tbl_id = '3' order by v.id desc limit 1) as stamp_vendor_status,(SELECT v.vendor_actual_status from view_vendor_master_log v, `education_vendor_log` `ed` where ed.case_id = education.id and v.case_id = ed.id and component = 'eduver' and component_tbl_id = '3' order by v.id desc limit 1) as stamp_vendor_actual_status,(SELECT v.final_status from view_vendor_master_log v, `education_vendor_log` `ed` where ed.case_id = education.id and v.case_id = ed.id and component = 'eduver' and component_tbl_id = '3' order by v.id asc limit 1) as verifier_vendor_status,(SELECT v.vendor_actual_status from view_vendor_master_log v, `education_vendor_log` `ed` where ed.case_id = education.id and v.case_id = ed.id and component = 'eduver' and component_tbl_id = '3' order by v.id asc limit 1) as verifier_vendor_actual_status,(SELECT v.trasaction_id  from view_vendor_master_log v, `education_vendor_log` `ed` where ed.case_id = education.id and v.case_id = ed.id and component = 'eduver' and component_tbl_id = '3' order by v.id desc limit 1) as transaction_id,(SELECT v.created_on  from view_vendor_master_log v, `education_vendor_log` `ed` where ed.case_id = education.id and v.case_id = ed.id and component = 'eduver' and component_tbl_id = '3' order by v.id desc limit 1) as stamp_closure_date,(SELECT v.created_on  from view_vendor_master_log v, `education_vendor_log` `ed` where ed.case_id = education.id and v.case_id = ed.id and component = 'eduver' and component_tbl_id = '3' order by v.id asc limit 1) as verifier_closure_date,(SELECT GROUP_CONCAT(concat(DATE_FORMAT(education_insuff.insuff_raised_date,'%d-%m-%Y')) SEPARATOR '||') FROM education_insuff where education_insuff.education_id = education.id) as insuff_raised_date,(SELECT GROUP_CONCAT(concat(DATE_FORMAT(education_insuff.insuff_clear_date,'%d-%m-%Y')) SEPARATOR '||') FROM education_insuff where education_insuff.education_id = education.id) as insuff_clear_date,(SELECT GROUP_CONCAT(concat(education_insuff.insuff_raise_remark) SEPARATOR '||') FROM education_insuff where education_insuff.education_id = education.id) as insuff_raise_remark,(select created_on from education_vendor_log where education_vendor_log.case_id = education.id order by education_vendor_log.id asc limit 1) as verifiers_spoc_created,(select modified_on from education_vendor_log where education_vendor_log.case_id = education.id  order by education_vendor_log.id asc limit 1) as verifiers_spoc_modified,(select created_on from education_vendor_log where education_vendor_log.case_id = education.id order by education_vendor_log.id desc limit 1) as verifiers_stamp_created");

		$this->db->from('education');

		$this->db->join("clients",'clients.id = education.clientid');

		$this->db->join("candidates_info",'candidates_info.id = education.candsid');

		$this->db->join("education_result as ed1",'ed1.education_id = education.id','left');

		$this->db->join("education_result as ed2",'(ed2.education_id  = education.id and ed1.id < ed2.id)','left');

		$this->db->join("status",'status.id = ed1.verfstatus','left');

		$this->db->where('ed2.verfstatus is null');

		if($clientid)
		{
			$this->db->where('education.clientid',$clientid);
		}
		if($from_date && $to_date)
		{

			$where3 = "DATE_FORMAT(`ed1`.`closuredate`,'%Y-%m-%d') BETWEEN '$from_date' AND '$to_date'";
                
            $this->db->where($where3); 
			
		}


		if($filter_by_status)
		{
			if($filter_by_status == "WIP")
			{
			$this->db->where('(ed1.var_filter_status = "wip" or  ed1.var_filter_status = "WIP")');
		    }
		    if($filter_by_status == "Insufficiency")
			{
			$this->db->where('(ed1.var_filter_status = "insufficiency" or  ed1.var_filter_status = "Insufficiency")');
		    }
		    if($filter_by_status == "Closed")
			{
			$this->db->where('(ed1.var_filter_status = "closed" or  ed1.var_filter_status = "Closed")');
		    }
		}

		$this->db->order_by('education.id', 'ASC');
		
		$result = $this->db->get();
		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function vendor_logs($where_array,$id)
    {
        $this->db->select("view_vendor_master_log.*,(select vendor_name from vendors where vendors.id= education_vendor_log.vendor_id) as vendor_name,(select education_verification_status from vendors where vendors.id= education_vendor_log.vendor_id) as vendor_status,education_vendor_log.case_id,(select user_name from user_profile where id = view_vendor_master_log.allocated_by) as allocated_by,(select user_name from user_profile where id = view_vendor_master_log.approval_by) as approval_by");

        $this->db->from('view_vendor_master_log');
        $this->db->join('education_vendor_log','education_vendor_log.id = view_vendor_master_log.case_id');

        //$this->db->join('user_profile','user_profile.id = view_vendor_master_log.allocated_by','left');

         // $this->db->join('user_profile','user_profile.id = view_vendor_master_log.approved_by','left');
        $this->db->where($where_array);

        if(!empty($id))
        {
        	 $this->db->where('education_vendor_log.case_id',$id);
        }

        $this->db->where_in('view_vendor_master_log.status',array('0','1','2','3','5'));

        $this->db->order_by("view_vendor_master_log.id", "desc");

        $result  = $this->db->get();
       // print_r($this->db->last_query());
        return $result->result_array();
    }

    public function select_vendor_result_log($where_array,$id)
    {

        $this->db->select("view_vendor_master_log.*,(select vendor_name from vendors where vendors.id= education_vendor_log.vendor_id) as vendor_name,(select user_name from user_profile where id = view_vendor_master_log.allocated_by) as allocated_by,(select user_name from user_profile where id = view_vendor_master_log.approval_by) as approval_by,education.*,education.id as education_id,candidates_info.id as CandidateID,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.CandidateName,(select clientname from clients where clients.id = education.clientid limit 1) as clientname,
			(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,candidates_info.entity as entity_id,candidates_info.package as package_id,education.iniated_date, candidates_info.caserecddate,education_com_ref,(select id from education_result where education_result.education_id = education.id) as education_result_id,view_vendor_master_log.id as view_vendor_master_log_id");



        $this->db->from('view_vendor_master_log');
        $this->db->join('education_vendor_log','education_vendor_log.id = view_vendor_master_log.case_id');

        $this->db->join('education','education.id = education_vendor_log.case_id');

   

		$this->db->join("candidates_info",'candidates_info.id = education.candsid');

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
   

        $this->db->where($where_array);

        if(!empty($id))
        {
        	 $this->db->where('vendor_cost_details.vendor_master_log_id',$id);
        }

        $result  = $this->db->get();
        //print_r($this->db->last_query());
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
    
   public function update_education_vendor_log($tablename,$arrdata,$arrwhere = array())
	{
	    if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update($tablename, $arrdata);
       
			record_db_error($this->db->last_query());
             
			return $result;
	    }
	    
	}

	public function insert_education_vendor_log($tablename,$arrdata)
	{
	    $this->db->insert($tablename, $arrdata);

	    record_db_error($this->db->last_query());

	    return $this->db->insert_id();
	    
	}

    public function get_vendor_cost_aprroval_details()
	{
	
	   $query = "select view_vendor_master_log.id as vendor_master_log_id,view_vendor_master_log.case_id as view_vendor_master_case_id,view_vendor_master_log.trasaction_id,view_vendor_master_log.status as view_vendor_master_status,view_vendor_master_log.component as view_vendor_master_components,view_vendor_master_log.component_tbl_id as component_tbl_id,education.education_com_ref,education.school_college,education.university_board,education.grade_class_marks,education.city,education.qualification,education.state,vendor_cost_details.cost,vendor_cost_details.additional_cost,vendor_cost_details.accept_reject_cost,vendor_cost_details.id as vendor_cost_details_id,vendor_cost_details.created_on,(select user_name from user_profile where user_profile.id= education_vendor_log.created_by) as created_by,(select vendor_name from vendors where vendors.id= education_vendor_log.vendor_id) as vendor_name from view_vendor_master_log  JOIN `education_vendor_log` ON `education_vendor_log`.`id` = `view_vendor_master_log`.`case_id` JOIN `education` ON `education`.`id` = `education_vendor_log`.`case_id` JOIN `candidates_info` ON `candidates_info`.`id` = `education`.`candsid` LEFT JOIN vendor_cost_details 
    ON vendor_cost_details.id = (SELECT id  FROM vendor_cost_details WHERE 	vendor_master_log_id = view_vendor_master_log.id  ORDER BY id DESC LIMIT 1) WHERE (view_vendor_master_log.status != 4 and view_vendor_master_log.status != 5 and view_vendor_master_log.status != 6 and view_vendor_master_log.component = 'eduver' and view_vendor_master_log.component_tbl_id = 3)"	;

     $result = $this->db->query($query);


	return $result->result_array();
     
	}

	public function education_case_list($where_array = array(),$where)
    {
        $this->db->select("view_vendor_master_log.*,CandidateName,clients.clientname,education.id as education_id,education.education_com_ref,education.school_college,education.vendor_list_mode,education.city,education.state,education.university_board,(select universityname from university_master where university_master.id = education.university_board) as actual_university_board, (select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select user_name from vendor_executive_login where vendor_executive_login.id = view_vendor_master_log.has_case_id limit 1) as vendor_executive_id,candidates_info.ClientRefNumber");

        $this->db->from('view_vendor_master_log');
   
        $this->db->join('education_vendor_log','education_vendor_log.id = view_vendor_master_log.case_id');

        $this->db->join('education','education.id = education_vendor_log.case_id');
        
        $this->db->join('candidates_info','candidates_info.id = education.candsid');
       
        $this->db->join("clients",'clients.id = education.clientid');
      

        $this->db->where($where_array);

        $this->db->where("(view_vendor_master_log.final_status = 'insufficiency' or view_vendor_master_log.final_status = 'closed')"); 

        if($where != "All")
        {

          $this->db->where('education.has_case_id', $where);

        } 



        $this->db->order_by("view_vendor_master_log.modified_on", "asc");
       
        $result = $this->db->get();
    
       return $result->result_array();
    
    }

    public function education_case_list_insuff($where_array = array())
    {
        $admin_status = "(education_result.verfstatus = 1 or education_result.verfstatus = 11 or education_result.verfstatus = 12 or education_result.verfstatus = 13 or education_result.verfstatus = 14 or education_result.verfstatus = 16 or education_result.verfstatus = 23 or education_result.verfstatus = 26 )";

        $this->db->select("view_vendor_master_log.*,CandidateName,clients.clientname,education.id as education_id,education.education_com_ref,education.school_college,education.vendor_list_mode,education.city,education.state,education.university_board,(select universityname from university_master where university_master.id = education.university_board) as actual_university_board, (select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select user_name from vendor_executive_login where vendor_executive_login.id = view_vendor_master_log.has_case_id limit 1) as vendor_executive_id,candidates_info.ClientRefNumber");

        $this->db->from('view_vendor_master_log');
      
        $this->db->join('education_vendor_log','education_vendor_log.id = view_vendor_master_log.case_id');

        $this->db->join('education','education.id = education_vendor_log.case_id');

        $this->db->join('education_result','education_result.education_id = education.id');

        $this->db->join('candidates_info','candidates_info.id = education.candsid');
       
        $this->db->join("clients",'clients.id = education.clientid');


        $this->db->where($where_array);

        $this->db->where($admin_status);

        $this->db->order_by("view_vendor_master_log.modified_on", "asc");

        $result = $this->db->get();
    
       return $result->result_array();
    
    }

    public function education_stamp_verifiers_queue($where_array = array(),$where1)
    {
        $admin_status = "(education_result.verfstatus = 1 or education_result.verfstatus = 11 or education_result.verfstatus = 12 or education_result.verfstatus = 13 or education_result.verfstatus = 14 or education_result.verfstatus = 16 or education_result.verfstatus = 23 or education_result.verfstatus = 26 )";

        $where_file = ' NOT EXISTS (select id from view_vendor_master_log_file where view_vendor_master_log.id = view_vendor_master_log_file.view_venor_master_log_id)';

        $this->db->select("view_vendor_master_log.*,CandidateName,clients.clientname,education.id as education_id,education.education_com_ref,education.school_college,education.vendor_list_mode,education.city,education.state,education.roll_no,education.enrollment_no,education.course_start_date,education.course_end_date,education.month_of_passing,education.year_of_passing,education.major,education.grade_class_marks,(select universityname from university_master where university_master.id = education.university_board) as university_board,(select qualification from qualification_master where qualification_master.id = education.qualification) as qualification,candidates_info.DateofBirth,candidates_info.NameofCandidateFather,candidates_info.MothersName, (select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,(select user_name from vendor_executive_login where vendor_executive_login.id = view_vendor_master_log.has_case_id limit 1) as vendor_executive_id,candidates_info.ClientRefNumber,education.clientid,(SELECT  GROUP_CONCAT(concat(education_files.file_name) SEPARATOR '||' ) FROM `education_files` where education_files.education_id = education.id and (education_files.type = 0 or education_files.type = 2)and education_files.status = 1)  as education_attachments,(select mode_of_verification from clients_details where clients_details.tbl_clients_id = candidates_info.clientid and clients_details.entity = candidates_info.entity and clients_details.package = candidates_info.package) as mode_of_verification,(select vendor_name from vendors where vendors.id= education.vendor_id) as vendor_name,(select vendor_name from vendors where vendors.id= education.vendor_stamp_id) as vendor_stamp_name");

        $this->db->from('view_vendor_master_log');
      
        $this->db->join('education_vendor_log','education_vendor_log.id = view_vendor_master_log.case_id');

        $this->db->join('education','education.id = education_vendor_log.case_id and education.vendor_stamp_id = education_vendor_log.vendor_id');

        $this->db->join('education_result','education_result.education_id = education.id');

        $this->db->join('candidates_info','candidates_info.id = education.candsid');
       
        $this->db->join("clients",'clients.id = education.clientid');

        $this->db->join('vendors','vendors.id = education.vendor_stamp_id');

        $this->db->where($where_file);

        $this->db->where('vendors.education_verification_status',2);

        $this->db->where($where_array);

        $this->db->where($admin_status);

        if($where1['vendor_stamp'] != "all")
        {
        	 $this->db->where('education.vendor_stamp_id',$where1['vendor_stamp']);
        }

        if(is_array($where1) && $where1['search']['value'] != "")
		{
			$this->db->group_start();
			  
			$this->db->like('candidates_info.CandidateName', $where1['search']['value']);

			$this->db->or_like('education.education_com_ref', $where1['search']['value']); 

            $this->db->group_end();
		}

        if(!empty($where1['order']))
		{

			$column_name_index = $where1['order'][0]['column'];
			$order_by = $where1['order'][0]['dir']; 
          
			$this->db->order_by($where1['columns'][$column_name_index]['data'],$order_by);
		}
		else
		{
		
		  $this->db->order_by('view_vendor_master_log.modified_on', 'asc');

		} 

		$this->db->limit($where1['length'],$where1['start']);


        $result = $this->db->get();

       return $result->result_array();
    
    }

    public function education_stamp_verifiers_queue_count($where_array = array(),$where1)
    {
        $admin_status = "(education_result.verfstatus = 1 or education_result.verfstatus = 11 or education_result.verfstatus = 12 or education_result.verfstatus = 13 or education_result.verfstatus = 14 or education_result.verfstatus = 16 or education_result.verfstatus = 23 or education_result.verfstatus = 26 )";

        $where_file = ' NOT EXISTS (select id from view_vendor_master_log_file where view_vendor_master_log.id = view_vendor_master_log_file.view_venor_master_log_id)';

        $this->db->select("view_vendor_master_log.*,CandidateName,clients.clientname,education.id as education_id,education.education_com_ref,education.school_college,education.vendor_list_mode,education.city,education.state,education.roll_no,education.enrollment_no,education.course_start_date,education.course_end_date,education.month_of_passing,education.year_of_passing,education.major,education.grade_class_marks,education.clientid,(select universityname from university_master where university_master.id = education.university_board) as university_board,(select qualification from qualification_master where qualification_master.id = education.qualification) as qualification, (select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,(select user_name from vendor_executive_login where vendor_executive_login.id = view_vendor_master_log.has_case_id limit 1) as vendor_executive_id,candidates_info.ClientRefNumber,candidates_info.DateofBirth,candidates_info.NameofCandidateFather,candidates_info.MothersName,(SELECT  GROUP_CONCAT(concat(education_files.file_name) SEPARATOR '||' ) FROM `education_files` where education_files.education_id = education.id and (education_files.type = 0 or education_files.type = 2)and education_files.status = 1)  as education_attachments,(select mode_of_verification from clients_details where clients_details.tbl_clients_id = candidates_info.clientid and clients_details.entity = candidates_info.entity and clients_details.package = candidates_info.package) as mode_of_verification");

        $this->db->from('view_vendor_master_log');
      
        $this->db->join('education_vendor_log','education_vendor_log.id = view_vendor_master_log.case_id');

        $this->db->join('education','education.id = education_vendor_log.case_id and education.vendor_stamp_id = education_vendor_log.vendor_id');

        $this->db->join('education_result','education_result.education_id = education.id');

        $this->db->join('candidates_info','candidates_info.id = education.candsid');
       
        $this->db->join("clients",'clients.id = education.clientid');

        $this->db->join('vendors','vendors.id = education.vendor_stamp_id');

        $this->db->where($where_file);

        $this->db->where('vendors.education_verification_status',2);

        $this->db->where($where_array);

        $this->db->where($admin_status);

        if($where1['vendor_stamp'] != "all")
        {
        	$this->db->where('education.vendor_stamp_id',$where1['vendor_stamp']);
        }

		if(is_array($where1) && $where1['search']['value'] != "")
		{
			$this->db->group_start();
			  
			$this->db->like('candidates_info.CandidateName', $where1['search']['value']);

			$this->db->or_like('education.education_com_ref', $where1['search']['value']); 

            $this->db->group_end();
		}


        if(!empty($where1['order']))
		{

			$column_name_index = $where1['order'][0]['column'];
			$order_by = $where1['order'][0]['dir']; 
          
			$this->db->order_by($where1['columns'][$column_name_index]['data'],$order_by);
		}
		else
		{
		
		  $this->db->order_by('view_vendor_master_log.modified_on', 'asc');

		} 

        $result = $this->db->get();

       return $result->result_array();
    
    }

    public function education_spoc_verifiers_queue($where_array = array(),$where1)
    { 
    	$where_condition = ' (NOT EXISTS (select id from view_vendor_master_log where education_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component_tbl_id = 3) or EXISTS (select id from view_vendor_master_log where education_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component_tbl_id = 3 and view_vendor_master_log.final_status = "wip" ))';

        	$this->db->select("education_vendor_log.id,(select vendor_name from vendors where vendors.id= education_vendor_log.vendor_id) as vendor_name,(select user_name from user_profile where user_profile.id = education_vendor_log.created_by) as allocated_by,education_vendor_log.created_on as allocated_on,education_vendor_log.modified_on,school_college,(select universityname from university_master where university_master.id = education.university_board) as university_board_name,grade_class_marks,(select qualification from qualification_master where qualification_master.id = education.qualification) as qualification,education_com_ref,education.id as case_id,(select clientname from clients where clients.id = candidates_info.clientid limit 1) as clientname,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.DateofBirth,candidates_info.NameofCandidateFather,candidates_info.MothersName,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,clients_details.mode_of_verification,education.roll_no,education.university_board,education.enrollment_no,education.course_start_date,education.course_end_date,education.month_of_passing,education.year_of_passing,education.major,education.clientid,(SELECT  GROUP_CONCAT(concat(education_files.file_name) SEPARATOR '||' ) FROM `education_files` where education_files.education_id = education.id and (education_files.type = 0 or education_files.type = 2)and education_files.status = 1)  as education_attachments");

		$this->db->from('education_vendor_log');

		$this->db->join('education','education.id = education_vendor_log.case_id');

	    $this->db->join('vendors','vendors.id = education.vendor_id');

		$this->db->join("education_result",'education_result.education_id = education.id','left');

	    $this->db->join("candidates_info",'candidates_info.id = education.candsid','left');

	    $this->db->join("clients_details", "(clients_details.tbl_clients_id =  candidates_info.clientid and clients_details.entity = candidates_info.entity and clients_details.package = candidates_info.package)");
        
       $this->db->where($where_condition);
	  
		$this->db->where($where_array);

		$this->db->where('education.vendor_stamp_id',NULL);

		$this->db->where('education.verifiers_spoc_status',2);

        $this->db->where('vendors.education_verification_status',1);

		$this->db->where('(education_result.var_filter_status = "wip" or education_result.var_filter_status = "WIP")');


		if(is_array($where1) && $where1['search']['value'] != "")
		{
			$this->db->group_start();
			  
			$this->db->like('candidates_info.CandidateName', $where1['search']['value']);

			$this->db->or_like('education.education_com_ref', $where1['search']['value']); 

            $this->db->group_end();
		}


        if(!empty($where1['order']))
		{

			$column_name_index = $where1['order'][0]['column'];
			$order_by = $where1['order'][0]['dir']; 
          
			$this->db->order_by($where1['columns'][$column_name_index]['data'],$order_by);
		}
		else
		{
		
		  $this->db->order_by('education_vendor_log.id', 'desc');

		} 

		$this->db->limit($where1['length'],$where1['start']);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
	
		return $result->result_array();

        
    }

    public function education_spoc_verifiers_queue_count($where_array = array(),$where1)
    { 
    	$where_condition = ' (NOT EXISTS (select id from view_vendor_master_log where education_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component_tbl_id = 3) or EXISTS (select id from view_vendor_master_log where education_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component_tbl_id = 3 and view_vendor_master_log.final_status = "wip" ))';

        	$this->db->select("education_vendor_log.id,(select vendor_name from vendors where vendors.id= education_vendor_log.vendor_id) as vendor_name,(select user_name from user_profile where user_profile.id = education_vendor_log.created_by) as allocated_by,education_vendor_log.created_on as allocated_on,education_vendor_log.modified_on,school_college,(select universityname from university_master where university_master.id = education.university_board) as university_board,grade_class_marks,(select qualification from qualification_master where qualification_master.id = education.qualification) as qualification,education_com_ref,education.id as case_id,(select clientname from clients where clients.id = candidates_info.clientid limit 1) as clientname,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.DateofBirth,candidates_info.NameofCandidateFather,candidates_info.MothersName,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,clients_details.mode_of_verification,education.roll_no,education.enrollment_no,education.course_start_date,education.course_end_date,education.month_of_passing,education.year_of_passing,education.major,education.clientid,(SELECT  GROUP_CONCAT(concat(education_files.file_name) SEPARATOR '||' ) FROM `education_files` where education_files.education_id = education.id and (education_files.type = 0 or education_files.type = 2)and education_files.status = 1)  as education_attachments");

		$this->db->from('education_vendor_log');

		$this->db->join('education','education.id = education_vendor_log.case_id');

	    $this->db->join('vendors','vendors.id = education.vendor_id');

		$this->db->join("education_result",'education_result.education_id = education.id','left');

	    $this->db->join("candidates_info",'candidates_info.id = education.candsid','left');

	    $this->db->join("clients_details", "(clients_details.tbl_clients_id =  candidates_info.clientid and clients_details.entity = candidates_info.entity and clients_details.package = candidates_info.package)");
        
       $this->db->where($where_condition);
	  
		$this->db->where($where_array);

		$this->db->where('education.vendor_stamp_id',NULL);

		$this->db->where('education.verifiers_spoc_status',2);

        $this->db->where('vendors.education_verification_status',1);

		$this->db->where('(education_result.var_filter_status = "wip" or education_result.var_filter_status = "WIP")');

		if(is_array($where1) && $where1['search']['value'] != "")
		{
			$this->db->group_start();
			
			$this->db->like('candidates_info.CandidateName', $where1['search']['value']);

			$this->db->or_like('education.education_com_ref', $where1['search']['value']);

			$this->db->group_end(); 
		}

        if(!empty($where1['order']))
		{

			$column_name_index = $where1['order'][0]['column'];
			$order_by = $where1['order'][0]['dir']; 
          
			$this->db->order_by($where1['columns'][$column_name_index]['data'],$order_by);
		}
		else
		{
		
		  $this->db->order_by('education_vendor_log.id', 'desc');

		} 
    
		$result  = $this->db->get();

		record_db_error($this->db->last_query());
	
		return $result->result_array();
        
    }

    public function education_stamp_verifiers_closure_queue($where_array)
    {
        $admin_status = "(education_result.verfstatus = 1 or education_result.verfstatus = 11 or education_result.verfstatus = 12 or education_result.verfstatus = 13 or education_result.verfstatus = 14 or education_result.verfstatus = 16 or education_result.verfstatus = 23 or education_result.verfstatus = 26 )";

     //   $where_file = ' EXISTS (select id from view_vendor_master_log_file where view_vendor_master_log.id = view_vendor_master_log_file.view_venor_master_log_id)';

        $where_condition = "(view_vendor_master_log.final_status = 'clear' or view_vendor_master_log.final_status = 'major discrepancy'  or view_vendor_master_log.final_status = 'minor discrepancy' or view_vendor_master_log.final_status = 'no record found' or view_vendor_master_log.final_status = 'unable to verify')" ; 

        $this->db->select("view_vendor_master_log.*,(select user_name from user_profile where user_profile.id = view_vendor_master_log.created_by limit 1) as crated_name,CandidateName,clients.clientname,education.id as education_id,education.education_com_ref,education.school_college,education.vendor_list_mode,education.city,education.state,education.university_board,(select universityname from university_master where university_master.id = education.university_board) as actual_university_board,(select qualification from qualification_master where qualification_master.id = education.qualification) as actual_qualification_name, (select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,candidates_info.ClientRefNumber,(select mode_of_verification from clients_details where clients_details.tbl_clients_id = candidates_info.clientid and clients_details.entity = candidates_info.entity and clients_details.package = candidates_info.package) as mode_of_verification");

        $this->db->from('view_vendor_master_log');
      
        $this->db->join('education_vendor_log','education_vendor_log.id = view_vendor_master_log.case_id');

        $this->db->join('education','education.id = education_vendor_log.case_id');

        $this->db->join('education_result','education_result.education_id = education.id');

        $this->db->join('candidates_info','candidates_info.id = education.candsid');
       
        $this->db->join("clients",'clients.id = education.clientid');

        $this->db->join('vendors','vendors.id = education.vendor_id');

      //  $this->db->where($where_file);

        $this->db->where($where_array);
        
        $this->db->where($admin_status);

        $this->db->where($where_condition);

        $this->db->order_by('view_vendor_master_log.modified_on','asc');

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

	 public function get_client_id($where_array )
	{
     
	  $this->db->select("view_vendor_master_log.id,view_vendor_master_log.case_id as  vendor_master_log_case, education_vendor_log.id as education_vendor_log_id,education_vendor_log.case_id as education_vendor_log_case,education.id as education_id ,education.clientid as client_id" );
        $this->db->from('view_vendor_master_log');
       // $this->db->join('addrver','addrver.id = view_vendor_master_log.component_tbl_id');
        $this->db->join('education_vendor_log','education_vendor_log.id = view_vendor_master_log.case_id');
        $this->db->join('education','education.id = education_vendor_log.case_id');
        $this->db->join("clients",'clients.id = education.clientid');


        $this->db->where($where_array);
        
        $result = $this->db->get();
  
       return $result->result_array();
	}

	public function check_vendor_status_closed_or_not($where_array )
	{
     
	  $this->db->select("view_vendor_master_log.id,view_vendor_master_log.final_status,view_vendor_master_log.case_id as  vendor_master_log_case, education_vendor_log.id as education_vendor_log_id,education_vendor_log.case_id as veducation_vendor_log_case,education.id as education_id" );

        $this->db->from('view_vendor_master_log');
      
        $this->db->join('education_vendor_log','education_vendor_log.id = view_vendor_master_log.case_id');

        $this->db->join('education','education.id = education_vendor_log.case_id');
    
        $this->db->where($where_array);

        $this->db->where("(view_vendor_master_log.final_status = 'wip' or view_vendor_master_log.final_status = 'insufficiency')");
        
        $result = $this->db->get();
 
        return $result->result_array();
	}
     

    public function check_vendor_status_insufficiency($where_array )
	{
     
	  $this->db->select("view_vendor_master_log.id,view_vendor_master_log.final_status,view_vendor_master_log.case_id as  vendor_master_log_case, education_vendor_log.id as education_vendor_log_id,education_vendor_log.case_id as veducation_vendor_log_case,education.id as education_id" );

        $this->db->from('view_vendor_master_log');
      
        $this->db->join('education_vendor_log','education_vendor_log.id = view_vendor_master_log.case_id');

        $this->db->join('education','education.id = education_vendor_log.case_id');
    
        $this->db->where($where_array);

        $this->db->where('education.vendor_id !=', 0);
        
        $result = $this->db->get();
 
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
    
    public function get_user_id($where_array)
	{
		$this->db->select('id');

		$this->db->from('user_profile');

		$this->db->where($where_array);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->row('id');
	}

	public function check_universities_exits($fields = array())
	{
		$result = $this->db->select('id')->from('university_master')->where('universityname', $fields['universityname'])->get()->row();
		
		if($result != "" && $result->id != "")
		{
			return $result->id;
		}
		else
		{
			return $this->add_new_numversities($fields);
		}
	}

	public function check_universitiesname_exits($where_arry)
	{
		$this->db->select('universityname');

		$this->db->from('university_master');

		$this->db->where($where_arry);
		
	    $result = $this->db->get()->row('universityname');
	    
		return $result;

	}

	public function check_qualification_exits($fields = array())
	{
		$result = $this->db->select('id')->from('qualification_master')->where('qualification', $fields['qualification'])->get()->row();
		
		if($result != "" && $result->id != "")
		{
			return $result->id;
		}
		else
		{
			return $this->add_new_qualification($fields);
		}
	}

	public function check_qualificationname_exits($where_arry)
	{
		$this->db->select('qualification');

		$this->db->from('qualification_master');

		$this->db->where($where_arry);
		
	    $result = $this->db->get()->row('qualification');
	    
		return $result;

	}

	public function add_new_numversities($arrdata,$arrwhere = array())
	{
		if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update('university_master', $arrdata);

			record_db_error($this->db->last_query());

			return $result;
	    }
	    else
	    {
			$this->db->insert('university_master', $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	    }
	}

	public function add_new_qualification($arrdata,$arrwhere = array())
	{
		if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update('qualification_master', $arrdata);

			record_db_error($this->db->last_query());

			return $result;
	    }
	    else
	    {
			$this->db->insert('qualification_master', $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	    }
	}

	public function select_candidate_from_file($id)
	{
		$this->db->select('file_name');

		$this->db->from('education_files');

		$this->db->join('education','education.id = education_files.education_id');
       
        $this->db->join('candidates_info','candidates_info.id = education.candsid');

		$this->db->where('candidates_info.id',$id);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	} 	 


	public function update_final_status_of_vendor($education_id)
	{

	    if(!empty($education_id))
	    {

			$sql =  'UPDATE view_vendor_master_log LEFT JOIN education_vendor_log ON education_vendor_log.case_id = view_vendor_master_log.id LEFT JOIN education ON education.id = education_vendor_log.case_id LEFT JOIN education_result ON education_result.education_id = education.id SET view_vendor_master_log.final_status = "closed" WHERE (education_result.verfstatus = 9 or education_result.verfstatus = 27 or education_result.verfstatus = 28) and (view_vendor_master_log.component = "eduver" and view_vendor_master_log.component_tbl_id = 3 and (view_vendor_master_log.final_status = "wip" or view_vendor_master_log.final_status = "insufficiency")) AND education.id = '.$education_id; 

            $query = $this->db->query($sql);
            
            return $query;
           
	    }   
	}



	public function select_client_list_view_education($tableName, $return_as_strict_row,$select_array, $where1=array())
	{
		$this->db->select($select_array);
       
		$this->db->from($tableName);

		$this->db->join("education",'education.clientid = clients.id');

		$this->db->join("user_profile",'user_profile.id = education.has_case_id','left');

		$this->db->join("candidates_info",'candidates_info.id = education.candsid');

	
		$this->db->join("education_result",'education_result.education_id = education.id','left');


		$this->db->join("education_insuff",'(education_insuff.education_id = education.id AND  education_insuff.status = 1 )','left');

		$this->db->join("status",'status.id = education_result.verfstatus','left');

        $this->db->where($this->filter_where_cond($where1)); 

        $this->db->where('clients.status',1); 


            if(isset($where1['start_date']) &&  $where1['start_date'] != '' && isset($where1['end_date']) &&  $where1['end_date'] != '')	
		    { 

		     	$start_date  =  $where1['start_date'];
	            $end_date  =  $where1['end_date'];

	            if($where1['status'] == "Closed")
                {
	         
		     	$where3 = "DATE_FORMAT(`education_result`.`closuredate`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}

		     	if($where1['status'] == "Insufficiency")
                {
	         
		     	$where3 = "DATE_FORMAT(`education_insuff`.`insuff_raised_date`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

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

	public function save_update_education_files($arrdata,$arrwhere = array())
	{
		if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update('education_files', $arrdata);

			record_db_error($this->db->last_query());

			return $result;
	    }
	    else
	    {
			$this->db->insert('education_files', $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	    }
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

	public  function get_reporting_manager_id_client($clientid)
    {
    	
        $this->db->select('clientmgr');

		$this->db->from('clients');

		$this->db->where('clients.id',$clientid);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
    
    }
    public function check_education_exists_in_candidate($where_array)
    {
    	$this->db->select('id');

		$this->db->from('education');

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

	public function select_vendor_list_education($component_name,$verification)
    {
        $this->db->select('id,vendor_name');
        $this->db->from('vendors');
        $this->db->where("vendors_components LIKE '%".$component_name."%' ");
        $this->db->where("education_verification_status",$verification);
        $this->db->where("status",1);
        $result = $this->db->get();
        return $result->result_array();
        
    }

    public function get_new_list_vendor($where_array = array(),$education_id)
	{
		$where_file = ' NOT EXISTS (select file_name from education_files where education_files.education_id = education.id)';
		$this->db->select('education_vendor_log.id,(select vendor_name from vendors where vendors.id= education_vendor_log.vendor_id) as vendor_name,(select user_name from user_profile where user_profile.id = education_vendor_log.created_by) as allocated_by,education_vendor_log.created_on as allocated_on,school_college,(select universityname from university_master where university_master.id = education.university_board) as university_board,grade_class_marks,(select qualification from qualification_master where qualification_master.id = education.qualification) as qualification,education_com_ref,education.id as case_id,(select clientname from clients where clients.id = candidates_info.clientid limit 1) as clientname,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.DateofBirth,candidates_info.NameofCandidateFather,candidates_info.MothersName,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,clients_details.mode_of_verification,education.roll_no,education.enrollment_no,education.course_start_date,education.course_end_date,education.month_of_passing,education.year_of_passing,education.major,education.clientid');

		$this->db->from('education');

		$this->db->join('education_vendor_log','education_vendor_log.case_id = education.id');

	    $this->db->join('vendors','vendors.id = education.vendor_id');

		$this->db->join("education_result",'education_result.education_id = education.id','left');

	    $this->db->join("candidates_info",'candidates_info.id = education.candsid','left');

	    $this->db->join("clients_details", "(clients_details.tbl_clients_id =  candidates_info.clientid and clients_details.entity = candidates_info.entity and clients_details.package = candidates_info.package)");
        
        $this->db->where($where_file);
	  
		$this->db->where($where_array);

        $this->db->where('vendors.education_verification_status',1);
      
		$this->db->where('(education_result.var_filter_status = "wip" or education_result.var_filter_status = "WIP")');

		$this->db->where_in('education_vendor_log.id',$education_id);
       
		$this->db->order_by('education_vendor_log.id', 'desc');

		$result  = $this->db->get();
		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function attachment_education_record($education_id)
	{
		$this->db->select('GROUP_CONCAT(concat(education_files.file_name) SEPARATOR '||') as attachments,education.clientid,education_com_ref');

		$this->db->from('education');

		$this->db->join('education_vendor_log','education_vendor_log.case_id = education.id');

		$this->db->join('education_files','education_files.id = education.id');

	    $this->db->where_in('education_vendor_log.id',$education_id);
       
		$this->db->order_by('education_vendor_log.id', 'desc');

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();


	}

	public function get_education_details_for_insuff_mail($where_array)
    {
    	$this->db->select('education.*,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,(select clientname from clients where clients.id = candidates_info.clientid limit 1) as clientname,(select universityname from university_master where university_master.id = education.university_board limit 1) as university_name,(select qualification from qualification_master where qualification_master.id = education.qualification limit 1) as qualification_name');

		$this->db->from('education');

		$this->db->join('candidates_info','candidates_info.id = education.candsid');

        if($where_array)
        {
		  $this->db->where($where_array);
	    }

		$result  = $this->db->get();
     
		record_db_error($this->db->last_query());
		
		return $result->result_array();
    }
    
    public  function get_vendor_details($where_array)
    {

        $this->db->select('vendor_name');

		$this->db->from('vendors');

		$this->db->where($where_array);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
    
    }

    public function select_education($table_name,$select_array, $where_array = array())
	{
		$this->db->select($select_array);

		$this->db->from($table_name);

		$this->db->where($where_array);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}


    public function check_transaction_exit_or_not($table_name,$select_array, $where_array = array())
	{
		$this->db->select($select_array);

		$this->db->from($table_name);

		$this->db->where($where_array);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_education_detail_for_export($where_array)
	{
		

        $this->db->select("education.clientid,(select clientname from clients where clients.id = education.clientid) as clientname,(select mode_of_verification from clients_details where clients_details.tbl_clients_id = candidates_info.clientid and clients_details.entity = candidates_info.entity and clients_details.package = candidates_info.package) as mode_of_verification,education_com_ref,candidates_info.CandidateName,candidates_info.NameofCandidateFather,candidates_info.MothersName,candidates_info.DateofBirth,(select universityname from university_master where university_master.id = education.university_board) as university_board,(select qualification from qualification_master where qualification_master.id = education.qualification) as qualification_name,(select vendor_name from vendors where vendors.id= education_vendor_log.vendor_id) as vendor_name,vendor_assgined_on,(SELECT  GROUP_CONCAT(concat(education_files.file_name) SEPARATOR '||' ) FROM `education_files` where education_files.education_id = education.id and (education_files.type = 0 or education_files.type = 2)and education_files.status = 1)  as attachments,(SELECT  GROUP_CONCAT(concat(education_insuff.insuff_raised_date) SEPARATOR '||' ) FROM `education_insuff` where education_insuff.education_id = education.id)  as insuff_raised_date,(SELECT  GROUP_CONCAT(concat(education_insuff.insuff_clear_date) SEPARATOR '||' ) FROM `education_insuff` where education_insuff.education_id = education.id)  as insuff_clear_date,(SELECT  GROUP_CONCAT(concat(education_insuff.insuff_raise_remark) SEPARATOR '||' ) FROM `education_insuff` where education_insuff.education_id = education.id)  as insuff_raise_remark,year_of_passing,roll_no,enrollment_no,education_vendor_log.created_on");

		$this->db->from('education_vendor_log');

		$this->db->join('education','education.id = education_vendor_log.case_id');

		$this->db->join("candidates_info",'candidates_info.id = education.candsid');

	    
        $this->db->where_in('education_vendor_log.id',$where_array);
	 	
		$result  = $this->db->get();

		record_db_error($this->db->last_query());
	
		return $result->result_array();

	}

    public function get_education_detail_for_export_spoc($where_array)
	{
		
        $this->db->select("education.clientid,education.vendor_id,education.vendor_id,education_com_ref,candidates_info.CandidateName,candidates_info.NameofCandidateFather,candidates_info.MothersName,candidates_info.DateofBirth,(select universityname from university_master where university_master.id = education.university_board) as university_board,(select qualification from qualification_master where qualification_master.id = education.qualification) as qualification_name,(select vendor_name from vendors where vendors.id= education.vendor_id) as vendor_name,(select email_id from vendors where vendors.id= education.vendor_id) as vendor_email_id,vendor_assgined_on,(SELECT  GROUP_CONCAT(concat(education_files.file_name) SEPARATOR '||' ) FROM `education_files` where education_files.education_id = education.id and (education_files.type = 0 or education_files.type = 2)and education_files.status = 1)  as attachments,(SELECT  GROUP_CONCAT(concat(education_insuff.insuff_raised_date) SEPARATOR '||' ) FROM `education_insuff` where education_insuff.education_id = education.id)  as insuff_raised_date,(SELECT  GROUP_CONCAT(concat(education_insuff.insuff_clear_date) SEPARATOR '||' ) FROM `education_insuff` where education_insuff.education_id = education.id)  as insuff_clear_date,(SELECT  GROUP_CONCAT(concat(education_insuff.insuff_raise_remark) SEPARATOR '||' ) FROM `education_insuff` where education_insuff.education_id = education.id)  as insuff_raise_remark,year_of_passing,roll_no,enrollment_no,education_vendor_log.modified_on");

		$this->db->from('education_vendor_log');

		$this->db->join('education','education.id = education_vendor_log.case_id');

		$this->db->join("candidates_info",'candidates_info.id = education.candsid');

        $this->db->where_in('education.id',$where_array);
	 	
		$result  = $this->db->get();

		record_db_error($this->db->last_query());
	
		return $result->result_array();

	}

	public function get_education_detail_for_export_spoc_count($where_array,$vendor_id)
	{
		

        $this->db->select("DATE_FORMAT(`education_vendor_log`.`modified_on`,'%d-%m-%Y') as `date`, count(`education_vendor_log`.`modified_on`) as count_record");

		$this->db->from('education_vendor_log');

		$this->db->join('education','education.id = education_vendor_log.case_id');

		$this->db->join("candidates_info",'candidates_info.id = education.candsid');

        $this->db->where_in('education.id',$where_array);

        $this->db->where('education.vendor_id',$vendor_id);

        $this->db->group_by('DATE(education_vendor_log.modified_on)');

	 	
		$result  = $this->db->get();

		record_db_error($this->db->last_query());
	
		return $result->result_array();

	}


	public function get_education_detail_for_export_stamp($where_array)
	{
		

        $this->db->select("education.clientid,education.vendor_stamp_id,education_com_ref,candidates_info.CandidateName,candidates_info.NameofCandidateFather,candidates_info.MothersName,(select universityname from university_master where university_master.id = education.university_board) as university_board,(select qualification from qualification_master where qualification_master.id = education.qualification) as qualification_name,(select vendor_name from vendors where vendors.id= education.vendor_stamp_id) as vendor_name,(select email_id from vendors where vendors.id= education.vendor_stamp_id) as vendor_email_id,vendor_assgined_on,(SELECT  GROUP_CONCAT(concat(education_files.file_name) SEPARATOR '||' ) FROM `education_files` where education_files.education_id = education.id and (education_files.type = 0 or education_files.type = 2)and education_files.status = 1)  as attachments,(SELECT  GROUP_CONCAT(concat(education_insuff.insuff_raised_date) SEPARATOR '||' ) FROM `education_insuff` where education_insuff.education_id = education.id)  as insuff_raised_date,(SELECT  GROUP_CONCAT(concat(education_insuff.insuff_clear_date) SEPARATOR '||' ) FROM `education_insuff` where education_insuff.education_id = education.id)  as insuff_clear_date,(SELECT  GROUP_CONCAT(concat(education_insuff.insuff_raise_remark) SEPARATOR '||' ) FROM `education_insuff` where education_insuff.education_id = education.id)  as insuff_raise_remark,year_of_passing,(select remarks from view_vendor_master_log where view_vendor_master_log.case_id = education_vendor_log.id and component_tbl_id = 3) as status,(select vendor_remark from view_vendor_master_log where view_vendor_master_log.case_id = education_vendor_log.id and component_tbl_id = 3) as vendor_remark,(select modified_on from view_vendor_master_log where view_vendor_master_log.case_id = education_vendor_log.id and component_tbl_id = 3) as modified_on");

		$this->db->from('education_vendor_log');

		$this->db->join('education','(education.id = education_vendor_log.case_id and education.vendor_stamp_id =  education_vendor_log.vendor_id)');


		$this->db->join("candidates_info",'candidates_info.id = education.candsid');

		$this->db->join("vendors",'vendors.id = education_vendor_log.vendor_id');

        $this->db->where_in('education.id',$where_array);
       
        $this->db->where('vendors.education_verification_status',2); 

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
	
		return $result->result_array();

	}


	public function get_education_detail_for_export_stamp_count($where_array,$vendor_id)
	{
		

        $this->db->select("DATE_FORMAT(`view_vendor_master_log`.`modified_on`,'%d-%m-%Y') as `date`, count(`view_vendor_master_log`.`modified_on`) as count_record");

		$this->db->from('education_vendor_log');

		$this->db->join('education','(education.id = education_vendor_log.case_id  and education.vendor_stamp_id =  education_vendor_log.vendor_id)');

		$this->db->join('view_vendor_master_log','(view_vendor_master_log.case_id = education_vendor_log.id  and view_vendor_master_log.component_tbl_id = 3)');

		$this->db->join("candidates_info",'candidates_info.id = education.candsid');

        $this->db->where_in('education.id',$where_array);

        $this->db->where('education.vendor_stamp_id',$vendor_id);

        $this->db->group_by('DATE(education_vendor_log.created_on)');

	 	
		$result  = $this->db->get();

		record_db_error($this->db->last_query());
	
		return $result->result_array();

	}

	public function select_all_verifiers_queue($where_array)
	{
		$where_condition = '(NOT EXISTS (select id from view_vendor_master_log where education_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component_tbl_id = 3) or EXISTS (select id from view_vendor_master_log where education_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component_tbl_id = 3 and view_vendor_master_log.final_status = "wip" ))';

		$this->db->select("education.education_com_ref");

		$this->db->from('education_vendor_log');

		$this->db->join('education','education.id = education_vendor_log.case_id');

	    $this->db->join('vendors','vendors.id = education.vendor_id');

		$this->db->join("education_result",'education_result.education_id = education.id','left');

	    $this->db->join("candidates_info",'candidates_info.id = education.candsid','left');

	    $this->db->join("clients_details", "(clients_details.tbl_clients_id =  candidates_info.clientid and clients_details.entity = candidates_info.entity and clients_details.package = candidates_info.package)");
        
       $this->db->where($where_condition);
	  
		$this->db->where($where_array);

		$this->db->where('education.vendor_stamp_id',NULL);

		$this->db->where('education.verifiers_spoc_status',1);

        $this->db->where('vendors.education_verification_status',1);

		$this->db->where('(education_result.var_filter_status = "wip" or education_result.var_filter_status = "WIP")');
     
		$this->db->order_by('education_vendor_log.id', 'asc');

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
	
		return $result->result_array();
	}
    
    public function select_all_spoc_verifiers_queue($where_array)
	{
		$where_condition = '(NOT EXISTS (select id from view_vendor_master_log where education_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component_tbl_id = 3) or EXISTS (select id from view_vendor_master_log where education_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component_tbl_id = 3 and view_vendor_master_log.final_status = "wip" ))';

		$this->db->select("education.education_com_ref");

		$this->db->from('education_vendor_log');

		$this->db->join('education','education.id = education_vendor_log.case_id');

	    $this->db->join('vendors','vendors.id = education.vendor_id');

		$this->db->join("education_result",'education_result.education_id = education.id','left');

	    $this->db->join("candidates_info",'candidates_info.id = education.candsid','left');

	    $this->db->join("clients_details", "(clients_details.tbl_clients_id =  candidates_info.clientid and clients_details.entity = candidates_info.entity and clients_details.package = candidates_info.package)");
        
       $this->db->where($where_condition);
	  
		$this->db->where($where_array);

		$this->db->where('education.vendor_stamp_id',NULL);

		$this->db->where('education.verifiers_spoc_status',2);

        $this->db->where('vendors.education_verification_status',1);

		$this->db->where('(education_result.var_filter_status = "wip" or education_result.var_filter_status = "WIP")');
     
		$this->db->order_by('education_vendor_log.id', 'asc');

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
	
		return $result->result_array();
	}
    
}
?>