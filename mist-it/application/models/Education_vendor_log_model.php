<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Education_vendor_log_model extends CI_Model
{
	function __construct()
    {
		$this->tableName = 'education_vendor_log';

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

        if($return_as_strict_row)
		{
            if(count($result_array) == 1) // ensure only one record has been previously inserted
            {
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

	public function get_new_list($where_array = array(),$where1)
	{
		$where_condition = '(NOT EXISTS (select id from view_vendor_master_log where education_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component_tbl_id = 3) or EXISTS (select id from view_vendor_master_log where education_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component_tbl_id = 3 and view_vendor_master_log.final_status = "wip" ))';

		$this->db->select("education_vendor_log.id,(select url from education_url_details where education_url_details.university_name = education.university_board and education_url_details.qualification_name = education.qualification and education_url_details.year_of_passing = education.year_of_passing) as url_link,(select vendor_name from vendors where vendors.id= education_vendor_log.vendor_id) as vendor_name,(select user_name from user_profile where user_profile.id = education_vendor_log.created_by) as allocated_by,education_vendor_log.created_on as allocated_on,school_college,(select universityname from university_master where university_master.id = education.university_board) as university_board,grade_class_marks,(select qualification from qualification_master where qualification_master.id = education.qualification) as qualification,education_com_ref,education.id as case_id,(select clientname from clients where clients.id = candidates_info.clientid limit 1) as clientname,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.DateofBirth,candidates_info.NameofCandidateFather,candidates_info.MothersName,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,clients_details.mode_of_verification,education.roll_no,education.enrollment_no,education.course_start_date,education.course_end_date,education.month_of_passing,education.year_of_passing,education.major,education.clientid,(SELECT  GROUP_CONCAT(concat(education_files.file_name) SEPARATOR '||' ) FROM `education_files` where education_files.education_id = education.id and (education_files.type = 0 or education_files.type = 2)and education_files.status = 1)  as education_attachments");

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
		
		  $this->db->order_by('education_vendor_log.id', 'asc');

		} 

		$this->db->limit($where1['length'],$where1['start']);


		$result  = $this->db->get();

		record_db_error($this->db->last_query());
	
		return $result->result_array();
	}

	public function get_new_list_count($where_array = array(),$where1)
	{
		$where_condition = '(NOT EXISTS (select id from view_vendor_master_log where education_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component_tbl_id = 3) or EXISTS (select id from view_vendor_master_log where education_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component_tbl_id = 3 and view_vendor_master_log.final_status = "wip" ))';

		$this->db->select("education_vendor_log.id,(select url from education_url_details where education_url_details.university_name = education.university_board and education_url_details.qualification_name = education.qualification and education_url_details.year_of_passing = education.year_of_passing) as url_link,(select vendor_name from vendors where vendors.id= education_vendor_log.vendor_id) as vendor_name,(select user_name from user_profile where user_profile.id = education_vendor_log.created_by) as allocated_by,education_vendor_log.created_on as allocated_on,school_college,(select universityname from university_master where university_master.id = education.university_board) as university_board,grade_class_marks,(select qualification from qualification_master where qualification_master.id = education.qualification) as qualification,education_com_ref,education.id as case_id,(select clientname from clients where clients.id = candidates_info.clientid limit 1) as clientname,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.DateofBirth,candidates_info.NameofCandidateFather,candidates_info.MothersName,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,clients_details.mode_of_verification,education.roll_no,education.enrollment_no,education.course_start_date,education.course_end_date,education.month_of_passing,education.year_of_passing,education.major,education.clientid,(SELECT  GROUP_CONCAT(concat(education_files.file_name) SEPARATOR '||' ) FROM `education_files` where education_files.education_id = education.id and (education_files.type = 0 or education_files.type = 2)and education_files.status = 1)  as education_attachments");

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
		
		  $this->db->order_by('education_vendor_log.id', 'asc');

		} 



		$result  = $this->db->get();

		record_db_error($this->db->last_query());
	
		return $result->result_array();
	}

	public function vendor_master_log_update_batch($tableName = 'vendor_master_log',$updateArray)
	{		
	 	$this->db->insert_batch($tableName,$updateArray);
		return $this->db->affected_rows();
	}

	public function update_batch_vendor_assign($tableName,$updateArray)
	{		
		$this->db->where('vendor_id !=','0');
	 	$this->db->update_batch($tableName,$updateArray, 'id');
		return $this->db->affected_rows();
	}
}
?>
