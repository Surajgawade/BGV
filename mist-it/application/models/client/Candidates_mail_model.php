<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Candidates_mail_model extends CI_Model
{
	function __construct()
    {
		//parent::__construct();

		$this->tableName = 'client_candidates_info';

		$this->primaryKey = 'id';
	}

	public function select($return_as_strict_row,$select_array, $where_array = array())
	{
        if(empty($select_array))
		{
			array_push($select_array, '*');
		}

		$select = implode(",",$select_array);

		$this->db->select($select);

		$this->db->from($this->tableName);

		$this->db->where($where_array);

		$this->db->order_by('id', 'desc');
		
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

    public function get_entitypackages($where_array)
	{
		$this->db->select("id,tbl_clients_id,clientaddress,clientcity,clientpincode,component_id,component_name,(select entity_package_name from entity_package where id = clients_details.entity) as entity,(select entity_package_name from entity_package where id = clients_details.package) as package,created_on,created_by");

		$this->db->from('clients_details');

		$this->db->where($where_array);

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_client_deatils($candsid)
	{
		$this->db->select("(select candidate_component_count from clients_details where tbl_clients_id = candidates_info.clientid and entity = candidates_info.entity and package = candidates_info.package) as candidate_component_count");

		$this->db->from('candidates_info');

		$this->db->where('candidates_info.id',$candsid);

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_candidate_component_check($candsid)
	{
		$this->db->select("address_component_check,employment_component_check,education_component_check,reference_component_check,court_component_check,global_component_check,identity_component_check,credit_report_component_check,drugs_component_check,pcc_component_check");

		$this->db->from('client_candidates_info');

		$this->db->where('client_candidates_info.cands_info_id',$candsid);

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_address_details_mail($where_arry)
	{
		$this->db->select("addrver.iniated_date,addrver.stay_from,addrver.stay_to,addrver.add_com_ref,addrver.address_type,addrver.address,addrver.city,addrver.pincode,addrver.state");

		$this->db->from('addrver');

		$this->db->where('addrver.candsid',$where_arry);

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();
	
	}

	public function get_employment_details_mail($where_arry)
	{
		$this->db->select("empver.iniated_date,empver.emp_com_ref,empver.deputed_company,empver.employment_type,empver.locationaddr,empver.citylocality,empver.pincode,empver.state,empver.empid,empver.employment_type,empver.empfrom,empver.empto,empver.designation,empver.empto,(select coname from company_database where company_database.id = empver.nameofthecompany) as coname");

		$this->db->from('empver');
		
		$this->db->where('empver.candsid',$where_arry);

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();
	
	}
   
    public function get_education_details_mail($where_arry)
	{
		$this->db->select("education.iniated_date,education.education_com_ref,education.school_college,(select universityname from university_master where university_master.id = education.university_board limit 1) as university_board,education.grade_class_marks,(select qualification from qualification_master where qualification_master.id = education.qualification limit 1) as qualification,education.major,education.course_start_date,education.course_end_date,education.month_of_passing,education.year_of_passing,education.roll_no,education.enrollment_no,education.PRN_no");

		$this->db->from('education');
		
		$this->db->where('education.candsid',$where_arry);

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();
	
	}

	public function save_user_visited_count($arrdata,$arrwhere = array())
	{

		$result = false;
		if(!empty($arrwhere))
		{
			$this->db->where($arrwhere);
			$this->db->set('candidate_visit', 'candidate_visit+1', FALSE);
			$result = $this->db->update('client_candidates_info', $arrdata);

		}
		return $result;
   }


	
}
?>