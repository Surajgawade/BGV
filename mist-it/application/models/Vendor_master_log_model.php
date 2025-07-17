<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vendor_master_log_model extends CI_Model
{
	function __construct()
    {
		$this->tableName = 'vendor_master_log';

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

	public function get_list($where_array = array())
	{	
		$this->db->select('view_vendor_master_log.*,(select vendor_name from vendors where vendors.id= address_vendor_log.vendor_id) as vendor_name,(select user_name from user_profile where user_profile.id= address_vendor_log.created_by) as allocated_by,(select user_name from user_profile where user_profile.id= address_vendor_log.approval_by) as approval_by,address_vendor_log.created_on as allocated_on');
		$this->db->from('view_vendor_master_log');

		$this->db->join('address_vendor_log','address_vendor_log.id = view_vendor_master_log.case_id');
 

		$this->db->where($where_array);
		return $this->db->get()->result_array();
	}
	// public function get_list($where_array = array())
	// {
	// 	$this->db->select('vendor_master_log.case_id as component_case_id,vendor_master_log.status,vendor_master_log.id,trasaction_id,component,(select user_name from user_profile where user_profile.id = address_vendor_log.created_by) as allocated_by,address_vendor_log.created_on allocated_on,(select user_name from user_profile where user_profile.id = address_vendor_log.approval_by) as approval_by,address_vendor_log.modified_on approval_on,(select vendor_name from vendors where vendors.id= address_vendor_log.vendor_id) as vendor_name, add_com_ref as component_ref,ClientRefNumber,cmp_ref_no,costing,vendor_master_log.tat_status');

	// 	$this->db->from('vendor_master_log');

	// 	$this->db->join('address_vendor_log','(address_vendor_log.id = vendor_master_log.case_id and address_vendor_log.status = 1)');

	// 	$this->db->join('addrver','addrver.id = address_vendor_log.case_id');

	// 	$this->db->join('candidates_info','candidates_info.id = addrver.candsid');

	// 	$this->db->where('vendor_master_log.component','Address');

	// 	$this->db->where($where_array);

	// 	$this->db->order_by('address_vendor_log.id', 'desc');

	// 	$address  = $this->db->get()->result_array();

	// 	$this->db->select('vendor_master_log.case_id as component_case_id,vendor_master_log.status,vendor_master_log.id,trasaction_id,component,(select user_name from user_profile where user_profile.id = courtver_vendor_log.created_by) as allocated_by,courtver_vendor_log.created_on allocated_on,(select user_name from user_profile where user_profile.id = courtver_vendor_log.approval_by) as approval_by,courtver_vendor_log.modified_on approval_on,(select vendor_name from vendors where vendors.id= courtver_vendor_log.vendor_id) as vendor_name, court_com_ref as component_ref,ClientRefNumber,cmp_ref_no,costing,vendor_master_log.tat_status');

	// 	$this->db->from('vendor_master_log');

	// 	$this->db->join('courtver_vendor_log','(courtver_vendor_log.id = vendor_master_log.case_id and courtver_vendor_log.status = 1)');

	// 	$this->db->join('courtver','courtver.id = courtver_vendor_log.case_id');

	// 	$this->db->join('candidates_info','candidates_info.id = courtver.candsid');
	// 	$this->db->where('vendor_master_log.component','Court');
	// 	$this->db->where($where_array);

	// 	$this->db->order_by('courtver_vendor_log.id', 'desc');

	// 	$courtver  = $this->db->get()->result_array();

	// 	$this->db->select('vendor_master_log.case_id as component_case_id,vendor_master_log.status,vendor_master_log.id,trasaction_id,component,(select user_name from user_profile where user_profile.id = drug_narcotis_vendor_log.created_by) as allocated_by,drug_narcotis_vendor_log.created_on allocated_on,(select user_name from user_profile where user_profile.id = drug_narcotis_vendor_log.approval_by) as approval_by,drug_narcotis_vendor_log.modified_on approval_on,(select vendor_name from vendors where vendors.id= drug_narcotis_vendor_log.vendor_id) as vendor_name, drug_com_ref as component_ref,ClientRefNumber,cmp_ref_no,costing,vendor_master_log.tat_status');

	// 	$this->db->from('vendor_master_log');

	// 	$this->db->join('drug_narcotis_vendor_log','(drug_narcotis_vendor_log.id = vendor_master_log.case_id and drug_narcotis_vendor_log.status = 1)');

	// 	$this->db->join('drug_narcotis','drug_narcotis.id = drug_narcotis_vendor_log.case_id');

	// 	$this->db->join('candidates_info','candidates_info.id = drug_narcotis.candsid');
	// 	$this->db->where('vendor_master_log.component','Drug');
	// 	$this->db->where($where_array);

	// 	$this->db->order_by('drug_narcotis_vendor_log.id', 'desc');

	// 	$drug_narcotis  = $this->db->get()->result_array();

	// 	$this->db->select('vendor_master_log.case_id as component_case_id,vendor_master_log.status,vendor_master_log.id,trasaction_id,component,(select user_name from user_profile where user_profile.id = education_vendor_log.created_by) as allocated_by,education_vendor_log.created_on allocated_on,(select user_name from user_profile where user_profile.id = education_vendor_log.approval_by) as approval_by,education_vendor_log.modified_on approval_on,(select vendor_name from vendors where vendors.id= education_vendor_log.vendor_id) as vendor_name, education_com_ref as component_ref,ClientRefNumber,cmp_ref_no,costing,vendor_master_log.tat_status');

	// 	$this->db->from('vendor_master_log');

	// 	$this->db->join('education_vendor_log','(education_vendor_log.id = vendor_master_log.case_id and education_vendor_log.status = 1)');

	// 	$this->db->join('education','education.id = education_vendor_log.case_id');

	// 	$this->db->join('candidates_info','candidates_info.id = education.candsid');
	// 	$this->db->where('vendor_master_log.component','Education');
	// 	$this->db->where($where_array);

	// 	$this->db->order_by('education_vendor_log.id', 'desc');

	// 	$education  = $this->db->get()->result_array();

	// 	$this->db->select('vendor_master_log.case_id as component_case_id,vendor_master_log.status,vendor_master_log.id,trasaction_id,component,(select user_name from user_profile where user_profile.id = glodbver_vendor_log.created_by) as allocated_by,glodbver_vendor_log.created_on allocated_on,(select user_name from user_profile where user_profile.id = glodbver_vendor_log.approval_by) as approval_by,glodbver_vendor_log.modified_on approval_on,(select vendor_name from vendors where vendors.id= glodbver_vendor_log.vendor_id) as vendor_name, global_com_ref as component_ref,ClientRefNumber,cmp_ref_no,costing,vendor_master_log.tat_status');

	// 	$this->db->from('vendor_master_log');

	// 	$this->db->join('glodbver_vendor_log','(glodbver_vendor_log.id = vendor_master_log.case_id and glodbver_vendor_log.status = 1)');

	// 	$this->db->join('glodbver','glodbver.id = glodbver_vendor_log.case_id');

	// 	$this->db->join('candidates_info','candidates_info.id = glodbver.candsid');
	// 	$this->db->where('vendor_master_log.component','Global');
	// 	$this->db->where($where_array);

	// 	$this->db->order_by('glodbver_vendor_log.id', 'desc');

	// 	$glodbver  = $this->db->get()->result_array();


	// 	$this->db->select('vendor_master_log.case_id as component_case_id,vendor_master_log.status,vendor_master_log.id,trasaction_id,component,(select user_name from user_profile where user_profile.id = pcc_vendor_log.created_by) as allocated_by,pcc_vendor_log.created_on allocated_on,(select user_name from user_profile where user_profile.id = pcc_vendor_log.approval_by) as approval_by,pcc_vendor_log.modified_on approval_on,(select vendor_name from vendors where vendors.id= pcc_vendor_log.vendor_id) as vendor_name, pcc_com_ref as component_ref,ClientRefNumber,cmp_ref_no,costing,vendor_master_log.tat_status');

	// 	$this->db->from('vendor_master_log');

	// 	$this->db->join('pcc_vendor_log','(pcc_vendor_log.id = vendor_master_log.case_id and pcc_vendor_log.status = 1)');

	// 	$this->db->join('pcc','pcc.id = pcc_vendor_log.case_id');

	// 	$this->db->join('candidates_info','candidates_info.id = pcc.candsid');
	// 	$this->db->where('vendor_master_log.component','PCC');
	// 	$this->db->where($where_array);

	// 	$this->db->order_by('pcc_vendor_log.id', 'desc');

	// 	$pcc  = $this->db->get()->result_array();

	// 	return array_merge($address,$courtver,$drug_narcotis,$education,$glodbver,$pcc);
	// }
	 public function get_new_list_vendor_aq()
	 {
	 	$this->db->select('address_vendor_log.id,address_vendor_log.case_id,(select vendor_name from vendors where vendors.id= address_vendor_log.vendor_id) as vendor_name,(select user_name from user_profile where user_profile.id = address_vendor_log.created_by) as allocated_by,address_vendor_log.created_on as allocated_on,city,state,pincode,add_com_ref,addrver.id as case_id');

		$this->db->from('address_vendor_log');

		$this->db->join('addrver','addrver.id = address_vendor_log.case_id');

		$this->db->where('address_vendor_log.status','0');

		$this->db->order_by('address_vendor_log.id', 'desc');

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		$address  =  $result->result_array();


		$this->db->select('employment_vendor_log.id,(select vendor_name from vendors where vendors.id= employment_vendor_log.vendor_id) as vendor_name,(select user_name from user_profile where user_profile.id = employment_vendor_log.created_by) as allocated_by,employment_vendor_log.created_on as allocated_on,locationaddr,citylocality,pincode,state,emp_com_ref,empver.id as case_id');

		$this->db->from('employment_vendor_log');

		$this->db->join('empver','empver.id = employment_vendor_log.case_id');

		$this->db->where('employment_vendor_log.status','0');

		$this->db->order_by('employment_vendor_log.id', 'desc');

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
	    $employment    = $result->result_array();

	    $this->db->select('education_vendor_log.id,(select vendor_name from vendors where vendors.id= education_vendor_log.vendor_id) as vendor_name,(select user_name from user_profile where user_profile.id = education_vendor_log.created_by) as allocated_by,education_vendor_log.created_on as allocated_on,school_college,qualification,university_board,education_com_ref,city,state,education.id as case_id');

		$this->db->from('education_vendor_log');

		$this->db->join('education','education.id = education_vendor_log.case_id');

		$this->db->where('education_vendor_log.status','0');

		$this->db->order_by('education_vendor_log.id', 'desc');

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		$education = $result->result_array();

		$this->db->select('courtver_vendor_log.id,(select vendor_name from vendors where vendors.id= courtver_vendor_log.vendor_id) as vendor_name,(select user_name from user_profile where user_profile.id = courtver_vendor_log.created_by) as allocated_by,courtver_vendor_log.created_on as allocated_on,court_com_ref,street_address,city,pincode,state,case_id');

		$this->db->from('courtver_vendor_log');

		$this->db->join('courtver','courtver.id = courtver_vendor_log.case_id');

		$this->db->where('courtver_vendor_log.status','0');

		$this->db->order_by('id', 'desc');

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		$court =  $result->result_array();

		$this->db->select('glodbver_vendor_log.id,(select vendor_name from vendors where vendors.id= glodbver_vendor_log.vendor_id) as vendor_name,(select user_name from user_profile where user_profile.id = glodbver_vendor_log.created_by) as allocated_by,glodbver_vendor_log.created_on as allocated_on,global_com_ref,street_address,city,pincode,state,case_id');

		$this->db->from('glodbver_vendor_log');

		$this->db->join('glodbver','glodbver.id = glodbver_vendor_log.case_id');

		$this->db->where('glodbver_vendor_log.status','0');

		$this->db->order_by('id', 'desc');

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		$global_database   = $result->result_array();

		$this->db->select('pcc_vendor_log.id,(select vendor_name from vendors where vendors.id= pcc_vendor_log.vendor_id) as vendor_name,(select user_name from user_profile where user_profile.id = pcc_vendor_log.created_by) as allocated_by,pcc_vendor_log.created_on as allocated_on,city,state,pincode,pcc_com_ref,case_id');

		$this->db->from('pcc_vendor_log');

		$this->db->join('pcc','pcc.id = pcc_vendor_log.case_id');

		$this->db->where('pcc_vendor_log.status','0');

		$this->db->order_by('pcc_vendor_log.id', 'desc');

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
	    $pcc	= $result->result_array();


	    $this->db->select('identity_vendor_log.id,identity_vendor_log.case_id,(select vendor_name from vendors where vendors.id= identity_vendor_log.vendor_id) as vendor_name,(select user_name from user_profile where user_profile.id = identity_vendor_log.created_by) as allocated_by,identity_vendor_log.created_on as allocated_on,city,state,pincode,identity_com_ref,identity.id as case_id');

		$this->db->from('identity_vendor_log');

		$this->db->join('identity','identity.id = identity_vendor_log.case_id');

		$this->db->where('identity_vendor_log.status','0');

		$this->db->order_by('identity_vendor_log.id', 'desc');

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		$identity = $result->result_array();

		$this->db->select('credit_report_vendor_log.id,(select vendor_name from vendors where vendors.id= credit_report_vendor_log.vendor_id) as vendor_name,(select user_name from user_profile where user_profile.id = credit_report_vendor_log.created_by) as allocated_by,credit_report_vendor_log.created_on as allocated_on,credit_report_com_ref,street_address,city,pincode,state,case_id');

		$this->db->from('credit_report_vendor_log');

		$this->db->join('credit_report','credit_report.id = credit_report_vendor_log.case_id');

		$this->db->where('credit_report_vendor_log.status','0');

		$this->db->order_by('id', 'desc');

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		$credit_report  = $result->result_array();

		$this->db->select('drug_narcotis_vendor_log.id,(select vendor_name from vendors where vendors.id= drug_narcotis_vendor_log.vendor_id) as vendor_name,(select user_name from user_profile where user_profile.id = drug_narcotis_vendor_log.created_by) as allocated_by,drug_narcotis_vendor_log.created_on as allocated_on,city,state,pincode,drug_com_ref,case_id');

		$this->db->from('drug_narcotis_vendor_log');

		$this->db->join('drug_narcotis','drug_narcotis.id = drug_narcotis_vendor_log.case_id');

		$this->db->where('drug_narcotis_vendor_log.status','0');

		$this->db->order_by('drug_narcotis_vendor_log.id', 'desc');

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		$drug_narcotis  = $result->result_array();

	    return array_merge($address,$employment,$education,$court,$global_database,$pcc,$identity,$credit_report,$drug_narcotis);
	 }

    public function get_new_list_vendor_charges()
	 {
	  $query = "select view_vendor_master_log.id as vendor_master_log_id,view_vendor_master_log.case_id as view_vendor_master_case_id,view_vendor_master_log.trasaction_id,view_vendor_master_log.status as view_vendor_master_status,view_vendor_master_log.component as view_vendor_master_components,view_vendor_master_log.component_tbl_id as component_tbl_id,addrver.add_com_ref,addrver.stay_from,addrver.stay_to,addrver.address_type,addrver.city,addrver.pincode,addrver.state,vendor_cost_details.cost,vendor_cost_details.additional_cost,vendor_cost_details.accept_reject_cost,vendor_cost_details.id as vendor_cost_details_id,vendor_cost_details.created_on,(select user_name from user_profile where user_profile.id= address_vendor_log.created_by) as created_by,(select vendor_name from vendors where vendors.id= address_vendor_log.vendor_id) as vendor_name from view_vendor_master_log  JOIN `address_vendor_log` ON `address_vendor_log`.`id` = `view_vendor_master_log`.`case_id` JOIN `addrver` ON `addrver`.`id` = `address_vendor_log`.`case_id` JOIN `candidates_info` ON `candidates_info`.`id` = `addrver`.`candsid` LEFT JOIN vendor_cost_details 
    ON vendor_cost_details.id = (SELECT id  FROM vendor_cost_details WHERE 	vendor_master_log_id = view_vendor_master_log.id  ORDER BY id DESC LIMIT 1) WHERE (view_vendor_master_log.status != 4 and view_vendor_master_log.status != 5 and view_vendor_master_log.status != 6 and view_vendor_master_log.component = 'addrver' and view_vendor_master_log.component_tbl_id = 1)"	;

     $result = $this->db->query($query);


	   $address  = $result->result_array();

	   $query = "select view_vendor_master_log.id as vendor_master_log_id,view_vendor_master_log.case_id as view_vendor_master_case_id,view_vendor_master_log.trasaction_id,view_vendor_master_log.status as view_vendor_master_status,view_vendor_master_log.component as view_vendor_master_components,view_vendor_master_log.component_tbl_id as component_tbl_id,empver.emp_com_ref,empver.deputed_company,empver.employment_type,empver.locationaddr,empver.citylocality,empver.pincode,empver.state,vendor_cost_details.cost,vendor_cost_details.additional_cost,vendor_cost_details.accept_reject_cost,vendor_cost_details.id as vendor_cost_details_id,vendor_cost_details.created_on,(select user_name from user_profile where user_profile.id= employment_vendor_log.created_by) as created_by,(select vendor_name from vendors where vendors.id= employment_vendor_log.vendor_id) as vendor_name from view_vendor_master_log  JOIN `employment_vendor_log` ON `employment_vendor_log`.`id` = `view_vendor_master_log`.`case_id` JOIN `empver` ON `empver`.`id` = `employment_vendor_log`.`case_id` JOIN `candidates_info` ON `candidates_info`.`id` = `empver`.`candsid` LEFT JOIN vendor_cost_details 
    ON vendor_cost_details.id = (SELECT id  FROM vendor_cost_details WHERE 	vendor_master_log_id = view_vendor_master_log.id  ORDER BY id DESC LIMIT 1) WHERE (view_vendor_master_log.status != 4 and view_vendor_master_log.status != 5 and view_vendor_master_log.status != 6 and view_vendor_master_log.component = 'empver' and view_vendor_master_log.component_tbl_id = 2)"	;

     $result = $this->db->query($query);


	     $employment  = $result->result_array();


	   $query = "select view_vendor_master_log.id as vendor_master_log_id,view_vendor_master_log.case_id as view_vendor_master_case_id,view_vendor_master_log.trasaction_id,view_vendor_master_log.status as view_vendor_master_status,view_vendor_master_log.component as view_vendor_master_components,view_vendor_master_log.component_tbl_id as component_tbl_id,education.education_com_ref,education.school_college,education.university_board,education.grade_class_marks,education.city,education.qualification,education.state,vendor_cost_details.cost,vendor_cost_details.additional_cost,vendor_cost_details.accept_reject_cost,vendor_cost_details.id as vendor_cost_details_id,vendor_cost_details.created_on,(select user_name from user_profile where user_profile.id= education_vendor_log.created_by) as created_by,(select vendor_name from vendors where vendors.id= education_vendor_log.vendor_id) as vendor_name from view_vendor_master_log  JOIN `education_vendor_log` ON `education_vendor_log`.`id` = `view_vendor_master_log`.`case_id` JOIN `education` ON `education`.`id` = `education_vendor_log`.`case_id` JOIN `candidates_info` ON `candidates_info`.`id` = `education`.`candsid` LEFT JOIN vendor_cost_details 
    ON vendor_cost_details.id = (SELECT id  FROM vendor_cost_details WHERE 	vendor_master_log_id = view_vendor_master_log.id  ORDER BY id DESC LIMIT 1) WHERE (view_vendor_master_log.status != 4 and view_vendor_master_log.status != 5 and view_vendor_master_log.status != 6 and view_vendor_master_log.component = 'eduver' and view_vendor_master_log.component_tbl_id = 3)"	;

     $result = $this->db->query($query);


	$education  = $result->result_array();

	  $query = "select view_vendor_master_log.id as vendor_master_log_id,view_vendor_master_log.case_id as view_vendor_master_case_id,view_vendor_master_log.trasaction_id,view_vendor_master_log.status as view_vendor_master_status,view_vendor_master_log.component as view_vendor_master_components,view_vendor_master_log.component_tbl_id as component_tbl_id,identity.identity_com_ref,identity.doc_submited,identity.id_number,identity.street_address,identity.city,identity.pincode,identity.state,vendor_cost_details.cost,vendor_cost_details.additional_cost,vendor_cost_details.accept_reject_cost,vendor_cost_details.id as vendor_cost_details_id,vendor_cost_details.created_on,(select user_name from user_profile where user_profile.id= identity_vendor_log.created_by) as created_by,(select vendor_name from vendors where vendors.id= identity_vendor_log.vendor_id) as vendor_name from view_vendor_master_log  JOIN `identity_vendor_log` ON `identity_vendor_log`.`id` = `view_vendor_master_log`.`case_id` JOIN `identity` ON `identity`.`id` = `identity_vendor_log`.`case_id` JOIN `candidates_info` ON `candidates_info`.`id` = `identity`.`candsid` LEFT JOIN vendor_cost_details 
    ON vendor_cost_details.id = (SELECT id  FROM vendor_cost_details WHERE 	vendor_master_log_id = view_vendor_master_log.id  ORDER BY id DESC LIMIT 1) WHERE (view_vendor_master_log.status != 4 and view_vendor_master_log.status != 5 and view_vendor_master_log.status != 6 and view_vendor_master_log.component = 'identity' and view_vendor_master_log.component_tbl_id = 9)"	;


     $result = $this->db->query($query);


	 $identity  = $result->result_array();

	   	    return array_merge($address,$employment,$education,$identity);


       }
}

?>