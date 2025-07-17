<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Assign_edu_model extends CI_Model
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

	public function delete($arrwhere)
	{
	  $result =  $this->db->delete($this->tableName, $arrwhere);

	  record_db_error($this->db->last_query());
	  
	  return $result;
	}

	public function get_all_education_record_datatable_assign($empver_aary = array(),$where,$columns)
	{
		$this->db->select("candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,candidates_info.clientid,candidates_info.entity,candidates_info.package,(select user_name from user_profile where user_profile.id = education.has_case_id) as executive_name,status.status_value,education.education_com_ref,school_college,(select universityname from university_master where university_master.id = education.university_board) as university_board,grade_class_marks,(select qualification from qualification_master where qualification_master.id = education.qualification) as qualification,major,course_start_date,course_end_date,month_of_passing,year_of_passing,roll_no,enrollment_no,PRN_no,documents_provided,genuineness,education_result.first_qc_approve,education_result.first_qc_updated_on,education_result.first_qu_reject_reason,education.id,education.has_assigned_on,education.iniated_date,clients.clientname,education.vendor_id,(select created_on from education_activity_data where comp_table_id = education.id order by id desc limit 1) as last_activity_date,due_date,tat_status,closuredate,education_result.remarks,(select vendor_name from vendors where vendors.id = education.vendor_id) as vendor_name");

		$this->db->from('education');

		$this->db->join("education_result",'education_result.education_id =education.id');
		
		$this->db->join("candidates_info",'candidates_info.id = education.candsid');
		
		$this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("status",'status.id = education_result.verfstatus');

		$this->db->where('(education_result.var_filter_status = "wip" or education_result.var_filter_status = "WIP")');

        $this->db->where('education.vendor_id =',0);

      
		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like('candidates_info.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('candidates_info.cmp_ref_no', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where['search']['value']);
		}
		
		$this->db->limit($where['length'],$where['start']);

		
		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 
            
			$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		}

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_all_education_record_datatable_count_assign($empver_aary = array(),$where,$columns)
	{
		$this->db->select("candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = education.has_case_id) as user_name,status.status_value,education.education_com_ref,education.city,education.state,education_result.first_qc_approve,education_result.first_qc_updated_on,education_result.first_qu_reject_reason,education.id,education.has_assigned_on,education.iniated_date,clients.clientname,education.vendor_id,(select created_on from education_activity_data where comp_table_id = education.id order by id desc limit 1) as last_activity_date,due_date,tat_status,closuredate,education_result.remarks,(select vendor_name from vendors where vendors.id = education.vendor_id) as vendor_name");

		$this->db->from('education');

		$this->db->join("education_result",'education_result.education_id = education.id');
		
		$this->db->join("candidates_info",'candidates_info.id = education.candsid');
		
		$this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("status",'status.id = education_result.verfstatus');

		$this->db->where('(education_result.var_filter_status = "wip" or education_result.var_filter_status = "WIP")');

        $this->db->where('education.vendor_id =',0);

      
		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like('candidates_info.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('candidates_info.cmp_ref_no', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where['search']['value']);
		}

				
		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 
			$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		}

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_all_education_record($where_array)
	{
		$this->db->select("candidates_info.id as cands_id,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = education.has_case_id) as executive_name,status.status_value as verfstatus,education.id as education_id,school_college,university_board,grade_class_marks,qualification,major,DATE_FORMAT(course_start_date,'%d-%m-%Y') as course_start_date,DATE_FORMAT(course_end_date,'%d-%m-%Y') as course_end_date,month_of_passing,year_of_passing,roll_no,enrollment_no,PRN_no,documents_provided,genuineness,city,state,education.education_com_ref,education.city,education.state as state_id,education_result.id as education_result_id,education_result.first_qc_approve,education_result.first_qc_updated_on,education_result.first_qu_reject_reason,education.id,education.has_case_id,education.online_URL,education.has_assigned_on,education.iniated_date,(select created_on from education_activity_data where comp_table_id = education.id order by id desc limit 1) as last_activity_date,(select clientname from clients where clients.id = candidates_info.clientid) as clientname,(select state from states where states.id= education.state) as state,due_date,tat_status,res_qualification,res_school_college,res_university_board,res_major,res_month_of_passing,res_year_of_passing,res_grade_class_marks,DATE_FORMAT(res_course_start_date,'%d-%m-%Y') as res_course_start_date,DATE_FORMAT(res_course_end_date,'%d-%m-%Y') as res_course_end_date, res_roll_no,res_enrollment_no,res_PRN_no,res_mode_of_verification,res_online_URL,verifier_designation,verifier_contact_details,res_genuineness,education_result.remarks as res_remarks,verified_by,closuredate,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name");

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

	public function uploaded_files($files_arry, $return_insert_ids = FALSE)
	{
		$res =  $this->db->insert_batch('education_files', $files_arry);
		
		record_db_error($this->db->last_query());
		
		return $res;
	}

	public function delete_uploaded_file($where = array())
	{	
		$this->db->where_in('id',$where);

		$this->db->set('status', STATUS_DELETED);

		$result = $this->db->update('education_files', array('status' => STATUS_DELETED));

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

	public function export_sql($filter) { 
	
	$sql = "SELECT (select clientname from clients where clients.id = candidates_info.clientid limit 1) as
		client_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name, ClientRefNumber,cmp_ref_no,CandidateName,DATE_FORMAT(caserecddate,'%d-%m-%Y') as caserecddate, (select status_value from status where status.id = education_result.verfstatus limit 1) as verfstatus,education_com_ref,(select user_name from user_profile where user_profile.id = education.has_case_id) as executive_name,school_college,university_board,grade_class_marks,qualification,major,DATE_FORMAT(course_start_date,'%d-%m-%Y') as course_start_date,DATE_FORMAT(course_end_date,'%d-%m-%Y') as course_end_date,month_of_passing,year_of_passing,roll_no,enrollment_no,PRN_no,documents_provided,genuineness,online_URL,city,state,res_mode_of_verification,verified_by,verifier_designation,verifier_contact_details,education_result.remarks,DATE_FORMAT(iniated_date,'%d-%m-%Y') as iniated_date,DATE_FORMAT(due_date,'%d-%m-%Y') as due_date,tat_status,first_qc_updated_on,DATE_FORMAT(closuredate,'%d-%m-%Y') as closuredate,first_qc_approve,(select created_on from education_activity_data where comp_table_id = education.id order by id desc limit 1) as last_activity_date
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

	public function select_client_list_assign_edu_view($tableName,$return_as_strict_row,$select_array)
	{
		$this->db->select($select_array);

		$this->db->from($tableName);

        $this->db->join("education",'education.clientid = clients.id');


		$this->db->join("education_result",'education_result.education_id =education.id');
		
		$this->db->join("candidates_info",'candidates_info.id = education.candsid');
		

		$this->db->join("status",'status.id = education_result.verfstatus');

		$this->db->where('(education_result.var_filter_status = "wip" or education_result.var_filter_status = "WIP")');

        $this->db->where('education.vendor_id =',0);
        
        
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
}
?>