<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Overall_insufficiency_model extends CI_Model
{
	function __construct()
    {
		$this->tableName = '';

		$this->primaryKey = 'id';
	}

	public function select_overall_insuff()
	{
		$this->db->select("empverres_insuff.id as insuff_id, 'Employment' as component_name,empver.iniated_date,empver.id, (select clientname from clients where clients.id = empver.clientid limit 1) as clientname,candidates_info.id as candidate_id, candidates_info.CandidateName, emp_com_ref as component_id,DATE_FORMAT(insuff_raised_date,'%d-%m-%Y') as insuff_raised_date,insff_reason,insuff_raise_remark,(select user_name from user_profile where id = empverres_insuff.created_by) as raised_by, 'employment' as controller,empver.clientid,
			(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name, (select entity_package_name from entity_package  where entity_package.id= candidates_info.package limit 1) as package_name");

		$this->db->from('empver');

		$this->db->join("candidates_info",'candidates_info.id = empver.candsid');

		$this->db->join('empverres_insuff','(empver.id = empverres_insuff.empverres_id and empverres_insuff.status = 1)');
		
		$this->db->order_by('empverres_insuff.insuff_raised_date','desc');

		$empver  = $this->db->get()->result_array();
		

		$this->db->select("addrver_insuff.id as insuff_id, 'Address' as component_name,addrver.iniated_date,addrver.id, (select clientname from clients where clients.id = addrver.clientid limit 1) as clientname,candidates_info.id as candidate_id,candidates_info.CandidateName,add_com_ref as component_id,DATE_FORMAT(insuff_raised_date,'%d-%m-%Y') as insuff_raised_date,insff_reason,insuff_raise_remark,(select user_name from user_profile where id = addrver_insuff.created_by) as raised_by, 'address' as controller,addrver.clientid,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name, (select entity_package_name from entity_package  where entity_package.id= candidates_info.package limit 1) as package_name");
		$this->db->from('addrver');

		$this->db->join("candidates_info",'candidates_info.id = addrver.candsid');
		$this->db->join('addrver_insuff','(addrver.id = addrver_insuff.addrverid and addrver_insuff.status = 1)');
		$this->db->order_by('addrver_insuff.insuff_raised_date','desc');
		$addrver  = $this->db->get()->result_array();


		$this->db->select("education_insuff.id as insuff_id, 'Education' as component_name,education.iniated_date, education.id, (select clientname from clients where clients.id = education.clientid limit 1) as clientname,candidates_info.id as candidate_id,candidates_info.CandidateName, education_com_ref as component_id,DATE_FORMAT(insuff_raised_date,'%d-%m-%Y') as insuff_raised_date,insff_reason,insuff_raise_remark,(select user_name from user_profile where id = education_insuff.created_by) as raised_by, 'education' as controller,education.clientid,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name, (select entity_package_name from entity_package  where entity_package.id= candidates_info.package limit 1) as package_name");

		$this->db->from('education');

		$this->db->join("candidates_info",'candidates_info.id = education.candsid');

		$this->db->join('education_insuff','(education.id = education_insuff.education_id and education_insuff.status = 1)');
		
		$this->db->order_by('education_insuff.insuff_raised_date','desc');

		$education  = $this->db->get()->result_array();
		

		$this->db->select("reference_insuff.id as insuff_id, 'Reference' as component_name, reference.iniated_date,reference.id, (select clientname from clients where clients.id = reference.clientid limit 1) as clientname, candidates_info.id as candidate_id,candidates_info.CandidateName,reference_com_ref as component_id,DATE_FORMAT(insuff_raised_date,'%d-%m-%Y') as insuff_raised_date,insff_reason,insuff_raise_remark,(select user_name from user_profile where id = reference_insuff.created_by) as raised_by, 'reference_verificatiion' as controller,reference.clientid,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name, (select entity_package_name from entity_package  where entity_package.id= candidates_info.package limit 1) as package_name");

		$this->db->from('reference');

		$this->db->join("candidates_info",'candidates_info.id = reference.candsid');

		$this->db->join('reference_insuff','(reference.id = reference_insuff.reference_id and reference_insuff.status = 1)');
		
		$this->db->order_by('reference_insuff.insuff_raised_date','desc');

		$reference  = $this->db->get()->result_array();
		
		$this->db->select("courtver_insuff.id as insuff_id, 'Court' as component_name,courtver.iniated_date,courtver.id, (select clientname from clients where clients.id = courtver.clientid limit 1) as clientname,candidates_info.id as candidate_id,candidates_info.CandidateName, court_com_ref as component_id,DATE_FORMAT(insuff_raised_date,'%d-%m-%Y') as insuff_raised_date,insff_reason,insuff_raise_remark,(select user_name from user_profile where id = courtver_insuff.created_by) as raised_by, 'court_verificatiion' as controller,courtver.clientid,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name, (select entity_package_name from entity_package  where entity_package.id= candidates_info.package limit 1) as package_name");
		$this->db->from('courtver');

		$this->db->join("candidates_info",'candidates_info.id = courtver.candsid');
		$this->db->join('courtver_insuff','(courtver.id = courtver_insuff.courtver_id and courtver_insuff.status = 1)');
		$this->db->order_by('courtver_insuff.insuff_raised_date','desc');
		$court  = $this->db->get()->result_array();


		$this->db->select("glodbver_insuff.id as insuff_id, 'Global DB' as component_name,glodbver.iniated_date,glodbver.id, (select clientname from clients where clients.id = glodbver.clientid limit 1) as clientname, candidates_info.id as candidate_id,candidates_info.CandidateName, global_com_ref as component_id,DATE_FORMAT(insuff_raised_date,'%d-%m-%Y') as insuff_raised_date,insff_reason,insuff_raise_remark,(select user_name from user_profile where id = glodbver_insuff.created_by) as raised_by, 'global_database' as controller,glodbver.clientid,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name, (select entity_package_name from entity_package  where entity_package.id= candidates_info.package limit 1) as package_name");
		$this->db->from('glodbver');
		$this->db->join("candidates_info",'candidates_info.id = glodbver.candsid');
		$this->db->join('glodbver_insuff','(glodbver.id = glodbver_insuff.glodbver_id and glodbver_insuff.status = 1)');
		$this->db->order_by('glodbver_insuff.insuff_raised_date','desc');
		$glodbver  = $this->db->get()->result_array();
		
		$this->db->select("drug_narcotis_insuff.id as insuff_id, 'Drug' as component_name,drug_narcotis.iniated_date, drug_narcotis.id,  (select clientname from clients where clients.id = drug_narcotis.clientid limit 1) as clientname,candidates_info.id as candidate_id,candidates_info.CandidateName, drug_com_ref as component_id,DATE_FORMAT(insuff_raised_date,'%d-%m-%Y') as insuff_raised_date,insff_reason,insuff_raise_remark,(select user_name from user_profile where id = drug_narcotis_insuff.created_by) as raised_by, 'drugs_narcotics' as controller,drug_narcotis.clientid,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name, (select entity_package_name from entity_package  where entity_package.id= candidates_info.package limit 1) as package_name");
		$this->db->from('drug_narcotis');
		$this->db->join("candidates_info",'candidates_info.id = drug_narcotis.candsid');
		$this->db->join('drug_narcotis_insuff','(drug_narcotis.id = drug_narcotis_insuff.drug_narcotis_id and drug_narcotis_insuff.status = 1)');
		$this->db->order_by('drug_narcotis_insuff.insuff_raised_date','desc');
		$drug_narcotis  = $this->db->get()->result_array();

		$this->db->select("pcc_insuff.id as insuff_id, 'PCC' as component_name,pcc.iniated_date,pcc.id, (select clientname from clients where clients.id = pcc.clientid limit 1) as clientname, candidates_info.id as candidate_id,candidates_info.CandidateName,pcc_com_ref as component_id,DATE_FORMAT(insuff_raised_date,'%d-%m-%Y') as insuff_raised_date,insff_reason,insuff_raise_remark,(select user_name from user_profile where id = pcc_insuff.created_by) as raised_by, 'pcc' as controller,pcc.clientid,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name, (select entity_package_name from entity_package  where entity_package.id= candidates_info.package limit 1) as package_name");
		$this->db->from('pcc');
		$this->db->join("candidates_info",'candidates_info.id = pcc.candsid');
		$this->db->join('pcc_insuff','(pcc.id = pcc_insuff.pcc_id and pcc_insuff.status = 1)');
		$this->db->order_by('pcc_insuff.insuff_raised_date','desc');
		$pcc  = $this->db->get()->result_array();

		$this->db->select("identity_insuff.id as insuff_id, 'Identity' as component_name,identity.iniated_date,identity.id, (select clientname from clients where clients.id = identity.clientid limit 1) as clientname, candidates_info.id as candidate_id,candidates_info.CandidateName, identity_com_ref as component_id,DATE_FORMAT(insuff_raised_date,'%d-%m-%Y') as insuff_raised_date,insff_reason,insuff_raise_remark,(select user_name from user_profile where id = identity_insuff.created_by) as raised_by, 'identity' as controller,identity.clientid,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name, (select entity_package_name from entity_package  where entity_package.id= candidates_info.package limit 1) as package_name");
		$this->db->from('identity');
	    $this->db->join("candidates_info",'candidates_info.id = identity.candsid');
		$this->db->join('identity_insuff','(identity.id = identity_insuff.identity_id and identity_insuff.status = 1)');
		$this->db->order_by('identity_insuff.insuff_raised_date','desc');
		$identity  = $this->db->get()->result_array();

		$this->db->select("credit_report_insuff.id as insuff_id, 'credit Report' as component_name,credit_report.id,credit_report.iniated_date, (select clientname from clients where clients.id = credit_report.clientid limit 1) as clientname,candidates_info.id as candidate_id,candidates_info.CandidateName, credit_report_com_ref as component_id,DATE_FORMAT(insuff_raised_date,'%d-%m-%Y') as insuff_raised_date,insff_reason,insuff_raise_remark,(select user_name from user_profile where id = credit_report_insuff.created_by) as raised_by, 'Credit_report' as controller,credit_report.clientid,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name, (select entity_package_name from entity_package  where entity_package.id= candidates_info.package limit 1) as package_name");
		$this->db->from('credit_report');

		$this->db->join("candidates_info",'candidates_info.id = credit_report.candsid');
		$this->db->join('credit_report_insuff','(credit_report.id = credit_report_insuff.credit_report_id and credit_report_insuff.status = 1)');
		$this->db->order_by('credit_report_insuff.insuff_raised_date','desc');
		$credit_report  = $this->db->get()->result_array();

		return array_merge($empver,$addrver,$education,$reference,$court,$glodbver,$drug_narcotis,$pcc,$identity,$credit_report);
	}



	public function get_emp_first_qc($empver_aary = array())
	{
		$this->db->select("empver.*,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,user_profile.user_name,empverres.id as empverres_id,empverres.modified_on,(select user_name from user_profile where id = empverres.created_by) as res_created_by,empverres.created_on as res_created_on,empverres.closuredate,(select coname from company_database where company_database.id = empver.nameofthecompany limit 1) as coname,empverres.*,(select status_value from status where id = empverres.verfstatus) as verfstatus");

		$this->db->from('empver');

		$this->db->join("empverres",'empverres.empverid = empver.id');

		$this->db->join("candidates_info",'candidates_info.id = empver.candsid');

		$this->db->join("user_profile",'user_profile.id = empver.has_case_id');

		if(!empty($empver_aary)){
			$this->db->where($empver_aary);
		}

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_emp_final_qc($empver_aary = array())
	{
		$this->db->select("(select coname from company_database where company_database.id = empver.nameofthecompany limit 1) as coname,(select coname from company_database where company_database.id = empverres.res_nameofthecompany limit 1) as res_coname,(select status_value from status where status.id = empverres.verfstatus) as verfstatus,empid,if(empid = res_empid,'Verified',res_empid) as res_empid, concat(empfrom,' to ', empto) as emp_period, concat(employed_from,' to ', employed_to) as res_emp_period, designation, if(emp_designation = designation ,'Verified', emp_designation) as emp_designation,remuneration, if(res_remuneration = remuneration,'Verified',res_remuneration) as res_remuneration,if(r_manager_name != NULL,r_manager_name,'NA') as r_manager_name,if(reportingmanager = r_manager_name,'Verified' ,reportingmanager) as reportingmanager,reasonforleaving,if(res_reasonforleaving = reasonforleaving,'Verified',res_reasonforleaving) as res_reasonforleaving, if(integrity_disciplinary_issue != NULL,integrity_disciplinary_issue,'NA') as integrity_disciplinary_issue,if(exitformalities != NULL,exitformalities,'NA') as exitformalities ,if(eligforrehire != NULL,eligforrehire , 'NA') as eligforrehire,if(fmlyowned != NULL, fmlyowned,'NA') as fmlyowned ,if(justdialwebcheck != NULL,justdialwebcheck ,'NA') as justdialwebcheck,if(mcaregn != NULL,mcaregn ,'NA') as mcaregn, if(domainname != NULL,domainname,'NA') as domainname,if(empverres.remarks != NULL,empverres.remarks,'NA') as res_remarks,if(verfname != NULL,verfname,'NA') as verfname,if(modeofverification != NULL,modeofverification,'NA') as modeofverification, empver.id as empver_id,empverres.id as empverres_id "); 

		$this->db->from('empver');

		$this->db->join("empverres",'empverres.empverid = empver.id');

		if(!empty($empver_aary)){
			$this->db->where($empver_aary);
		}

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_address_final_qc($where_arry = array())
	{
		$this->db->select("addrver.address, city, pincode,(select state from states where states.id = addrver.state) as address_state,,if(stay_from != '',stay_from,'NA') as stay_from,if(stay_to != '',stay_to,'NA') as stay_to,if(res_stay_from = stay_from,'Verified',res_stay_from) as res_stay_from,if(res_stay_to = stay_to,'Verified',res_stay_to) as res_stay_to,res_address,res_city,res_pincode,(select state from states where states.id = res_state) as res_state,if(addrverres.remarks != '',addrverres.remarks,'NA') as res_remarks,if(verified_by != '',verified_by,'NA') as verified_by,if(mode_of_verification != '',mode_of_verification,'NA') as mode_of_verification,addrver.id as addrver_id,(select status_value from status where status.id = addrverres.verfstatus) as verfstatus,addrverres.id as addrverres_id");

		$this->db->from('addrver');

		$this->db->join("addrverres",'addrverres.addrverid = addrver.id');

		$this->db->join("candidates_info",'candidates_info.id = addrver.candsid');

		if(!empty($where_arry)){
			$this->db->where($where_arry);
		}

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_education_final_qc($where_array)
	{
		$this->db->select("education.id as education_id,education_result.id as education_result_id,qualification,school_college,university_board,year_of_passing,if(res_qualification = qualification,'Verified',res_qualification) as res_qualification,res_school_college,res_university_board,if(res_year_of_passing = year_of_passing,'Verified',res_year_of_passing) as res_year_of_passing,verified_by,verifier_designation,verifier_contact_details,education_result.remarks as res_remarks,res_mode_of_verification,(select status_value from status where status.id = education_result.verfstatus) as verfstatus");

		$this->db->from('education');

		$this->db->join("education_result",'education_result.education_id =education.id');
		

		$this->db->where($where_array);

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_reference_final_qc($where_arry)
	{
		$this->db->select("reference.name_of_reference, if(designation != '',designation,'NA') as designation,if(contact_no != '',contact_no,'NA') as contact_no,if(reference_result.remarks != '',reference_result.remarks,'NA') as res_remarks,(select status_value from status where status.id = reference_result.verfstatus) as verfstatus,reference.id as reference_id,reference_result.id as reference_result_id ");

		$this->db->from('reference');

		$this->db->join("reference_result",'reference_result.reference_id = reference.id');
		
		if(!empty($where_arry)){
			$this->db->where($where_arry);
		}

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_court_final_qc($where_array)
	{
		$this->db->select("street_address,if(city != '',city,'') as city,if(pincode != '',pincode,'') as pincode, (select state from states where states.id = courtver.state) as state, remarks,mode_of_verification,verified_by,advocate_name,(select status_value from status where status.id = courtver_result.verfstatus) as verfstatus,courtver.id as courtver_id,courtver_result.id as courtver_result_id,courtver.candsid,courtver.candsid");

		$this->db->from('courtver');

		$this->db->join("courtver_result",'courtver_result.courtver_id = courtver.id');
		
		
		$this->db->where($where_array);

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_drug_db_final_qc($where_array)
	{
		$this->db->select("if(drug_test_code != '',drug_test_code,'NA') as drug_test_code, if(amphetamine_screen != '',amphetamine_screen,'NA') as amphetamine_screen, if(cannabinoids_screen != '',cannabinoids_screen,'NA') as cannabinoids_screen, if(cocaine_screen != '',cocaine_screen,'NA') as cocaine_screen, if(opiates_screen != '',opiates_screen,'NA') as opiates_screen, if(phencyclidine_screen != '',phencyclidine_screen,'NA') as phencyclidine_screen, if(remarks != '',remarks,'NA') as remarks, (select status_value from status where status.id = drug_narcotis_result.verfstatus) as verfstatus,drug_narcotis_id,drug_narcotis_result.id as drug_narcotis_result_id,drug_narcotis.candsid,drug_narcotis.candsid ");

		$this->db->from('drug_narcotis');

		$this->db->join("drug_narcotis_result",'drug_narcotis_result.drug_narcotis_id = drug_narcotis.id');
		
		$this->db->where($where_array);

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_pcc_final_qc($where_array)
	{
		$this->db->select("street_address,city,pincode, DATE_FORMAT(police_station_visit_date,'%d-%m-%Y') as police_station_visit_date,(select state from states where states.id = pcc.state) as state , if(name_designation_police != '',name_designation_police,'NA') as name_designation_police,if(contact_number_police != '',contact_number_police,'NA') as contact_number_police, if(mode_of_verification != '',mode_of_verification,'NA') as mode_of_verification,if(remarks != '',remarks,'NA') as remarks,if(police_station !='',police_station,'NA') as police_station, (select status_value from status where status.id = pcc_result.verfstatus) as verfstatus,pcc_id,pcc_result.id as pcc_result_id,pcc.clientid,pcc.candsid");

		$this->db->from('pcc');

		$this->db->join("pcc_result",'pcc_result.pcc_id = pcc.id');
		

		$this->db->where($where_array);

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_global_db_final_qc($where_array)
	{
		$this->db->select("if(mode_of_verification !='',mode_of_verification,'NA') as mode_of_verification,remarks,if(verified_by !='',verified_by,'NA') as verified_by,remarks,(select status_value from status where status.id = glodbver_result.verfstatus) as verfstatus,glodbver_id,glodbver_result.id as glodbver_result_id");

		$this->db->from('glodbver');

		$this->db->join("glodbver_result",'glodbver_result.glodbver_id = glodbver.id');
		

		$this->db->where($where_array);

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_address_first_qc($where_arry = array())
	{
		$this->db->select("addrver.*,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate, addrverres.id addrverres_id,(select status_value from status where status.id = addrverres.verfstatus limit 1) as verfstatus,user_profile.user_name,
			addrverres.res_address_type,addrverres.res_address,addrverres.res_stay_from,addrverres.res_stay_from,addrverres.res_stay_to,addrverres.neighbour_1,addrverres.neighbour_details_1,addrverres.neighbour_2,addrverres.neighbour_details_2,addrverres.mode_of_verification,addrverres.resident_status,addrverres.landmark,addrverres.verified_by,addrverres.addr_proof_collected,addrverres.remarks,addrverres.first_qc_approve,due_date,tat_status,closuredate");

		$this->db->from('addrver');

		$this->db->join("addrverres",'addrverres.addrverid = addrver.id');

		$this->db->join("candidates_info",'candidates_info.id = addrver.candsid');

		$this->db->join("user_profile",'user_profile.id = addrver.has_case_id');

		if(!empty($where_arry)){
			$this->db->where($where_arry);
		}

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_referencefirst_qc($where_arry)
	{
		$this->db->select("reference.*, candidates_info.CandidateName,candidates_info.cmp_ref_no,candidates_info.ClientRefNumber,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,(select clientname from clients where clients.id = reference.clientid limit 1) as clientname,reference.iniated_date,candidates_info.caserecddate,reference_result.remarks,handle_pressure,handle_pressure_value,attendance,attendance_value,integrity,integrity_value,leadership_skills,leadership_skills_value,responsibilities,responsibilities_value,achievements,achievements_value,strengths,strengths_value,team_player,team_player_value,weakness,weakness_value,(select status_value from status where id = reference_result.verfstatus) as verfstatus,user_profile.user_name");

		$this->db->from('reference');

		$this->db->join("reference_result",'reference_result.reference_id = reference.id');
		
		$this->db->join("candidates_info",'candidates_info.id = reference.candsid');
		
		$this->db->join("user_profile",'user_profile.id = reference.has_case_id');

		if(!empty($where_arry)){
			$this->db->where($where_arry);
		}

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_court_first_qc($where_array)
	{
		$this->db->select("candidates_info.id as cands_id,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = courtver.has_case_id) as user_name,status.status_value as verfstatus,courtver.id as courtver_id,courtver.court_com_ref,courtver.has_case_id,courtver.address_type, courtver.street_address,courtver.city,courtver.pincode,courtver.state as state_id,courtver_result.id as courtver_result_id,courtver_result.first_qc_approve,courtver_result.first_qc_updated_on,courtver_result.first_qu_reject_reason,courtver.id,courtver.has_assigned_on,courtver.iniated_date,(select clientname from clients where clients.id = candidates_info.clientid) as clientname,(select created_on from activity_log where comp_table_id = courtver.id and component_type = 3 order by id desc limit 1) as last_activity_date,due_date,tat_status,(select state from states where states.id= courtver.state) as state,mode_of_verification,verified_by,advocate_name,courtver_result.remarks as res_remarks,closuredate,,courtver_result.modified_on");

		$this->db->from('courtver');

		$this->db->join("courtver_result",'courtver_result.courtver_id = courtver.id');
		
		$this->db->join("candidates_info",'candidates_info.id = courtver.candsid');
		
		$this->db->join("status",'status.id = courtver_result.verfstatus');
		
		$this->db->where($where_array);

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_global_db_first_qc($where_array)
	{
		$this->db->select("candidates_info.id as cands_id,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = glodbver.has_case_id) as user_name,status.status_value as verfstatus,glodbver.id as glodbver_id, glodbver.global_com_ref,glodbver.address_type, glodbver.state as state_id, glodbver.street_address,glodbver.city,glodbver.pincode,glodbver_result.id as glodbver_result_id,glodbver_result.first_qc_approve,glodbver_result.first_qc_updated_on,glodbver_result.first_qu_reject_reason,glodbver.id,glodbver.has_assigned_on,glodbver.iniated_date,(select created_on from activity_log where comp_table_id = glodbver.id and component_type = 7 order by id desc limit 1) as last_activity_date,due_date,tat_status,(select created_on from activity_log where comp_table_id = glodbver.id and component_type = 7 order by id desc limit 1) as last_activity_date,(select clientname from clients where clients.id = candidates_info.clientid) as clientname,(select state from states where states.id= glodbver.state) as state,mode_of_verification,closuredate,glodbver_result.remarks,glodbver_result.modified_on");

		$this->db->from('glodbver');

		$this->db->join("glodbver_result",'glodbver_result.glodbver_id = glodbver.id');
		
		$this->db->join("candidates_info",'candidates_info.id = glodbver.candsid');
		
		$this->db->join("status",'status.id = glodbver_result.verfstatus');

		$this->db->where($where_array);

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_drug_db_first_qc($where_array)
	{
		$this->db->select("drug_narcotis.id,candidates_info.id as cands_id,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = drug_narcotis.has_case_id) as user_name,status.status_value as verfstatus,drug_narcotis.id as drug_narcotis_id,drug_narcotis.appointment_date,  drug_narcotis.appointment_time, drug_narcotis.spoc_no, drug_narcotis.drug_test_code, drug_narcotis.facility_name,  drug_narcotis.drug_com_ref,drug_narcotis.address_type, drug_narcotis.street_address,drug_narcotis.city,drug_narcotis.pincode,drug_narcotis.state as state_id,drug_narcotis_result.first_qc_approve,drug_narcotis_result.id as drug_narcotis_result_id,drug_narcotis_result.first_qc_updated_on,drug_narcotis_result.first_qu_reject_reason,drug_narcotis.has_assigned_on,drug_narcotis.iniated_date,clients.clientname,(select created_on from activity_log where comp_table_id = drug_narcotis.id and component_type = 8 order by id desc limit 1) as last_activity_date,due_date,tat_status,(select state from states where states.id= drug_narcotis.state) as state,drug_narcotis_result.mode_of_verification,closuredate,drug_narcotis_result.remarks,drug_narcotis_result.modified_on");

		$this->db->from('drug_narcotis');

		$this->db->join("drug_narcotis_result",'drug_narcotis_result.drug_narcotis_id = drug_narcotis.id');
		
		$this->db->join("candidates_info",'candidates_info.id = drug_narcotis.candsid');
		
		$this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("status",'status.id = drug_narcotis_result.verfstatus');

		$this->db->where($where_array);

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_pcc_first_qc($where_array)
	{
		$this->db->select("candidates_info.id as cands_id,pcc.id as pcc_id,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = pcc.has_case_id) as user_name,status.status_value as verfstatus,pcc.pcc_com_ref,pcc.address_type, pcc.street_address,pcc.city,pcc.pincode,pcc.state as state_id,pcc.references,pcc.references_no,pcc_result.id as pcc_result_id,pcc_result.first_qc_approve,pcc_result.first_qc_updated_on,pcc_result.first_qu_reject_reason,pcc.id,pcc.has_assigned_on,pcc.iniated_date,(select created_on from activity_log where comp_table_id = pcc.id and component_type = 4 order by id desc limit 1) as last_activity_date,due_date,tat_status,(select clientname from clients where clients.id = candidates_info.clientid) as clientname,(select state from states where states.id= pcc.state) as state,pcc_result.closuredate,pcc_result.mode_of_verification,pcc_result.application_id_ref,pcc_result.submission_date,pcc_result.police_station,pcc_result.police_station_visit_date,pcc_result.name_designation_police,pcc_result.contact_number_police,pcc_result.remarks,pcc_result.modified_on");

		$this->db->from('pcc');

		$this->db->join("pcc_result",'pcc_result.pcc_id = pcc.id');
		
		$this->db->join("candidates_info",'candidates_info.id = pcc.candsid');
		
		$this->db->join("status",'status.id = pcc_result.verfstatus');

		$this->db->where($where_array);

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_identity_first_qc($where_array)
	{
		$this->db->select("identity.id,candidates_info.id as cands_id,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = identity.has_case_id) as user_name,status.status_value as verfstatus,identity.id as identity_id,identity.has_case_id, identity.identity_com_ref,identity.doc_submited,identity.id_number, identity.street_address,identity.city,identity.pincode,identity.state as state_id,identity_result.id as identity_result_id,identity_result.first_qc_approve,identity_result.first_qc_updated_on,identity_result.first_qu_reject_reason,identity_result.mode_of_verification,identity_result.remarks,identity_result.closuredate,identity.has_assigned_on,identity.iniated_date,clients.clientname,(select created_on from activity_log where comp_table_id = identity.id and component_type = 13 order by id desc limit 1) as last_activity_date,due_date,tat_status,(select state from states where states.id= identity.state) as state,identity_result.modified_on");

		$this->db->from('identity');

		$this->db->join("identity_result",'identity_result.identity_id = identity.id');
		
		$this->db->join("candidates_info",'candidates_info.id = identity.candsid');
		
		$this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("status",'status.id = identity_result.verfstatus');

		$this->db->where($where_array);

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_education_first_qc($where_array)
	{
		$this->db->select("candidates_info.id as cands_id,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = education.has_case_id) as user_name,status.status_value as verfstatus,education.id as education_id,school_college,university_board,grade_class_marks,qualification,major,DATE_FORMAT(course_start_date,'%d-%m-%Y') as course_start_date,DATE_FORMAT(course_end_date,'%d-%m-%Y') as course_end_date,month_of_passing,year_of_passing,roll_no,enrollment_no,PRN_no,documents_provided,genuineness,city,state,education.education_com_ref,education.city,education.state as state_id,education_result.id as education_result_id,education_result.first_qc_approve,education_result.first_qc_updated_on,education_result.first_qu_reject_reason,education.id,education.has_case_id,education.online_URL,education.has_assigned_on,education.iniated_date,(select created_on from activity_log where comp_table_id = education.id and component_type = 5 order by id desc limit 1) as last_activity_date,(select clientname from clients where clients.id = candidates_info.clientid) as clientname,(select state from states where states.id= education.state) as state,due_date,tat_status,res_qualification,res_school_college,res_university_board,res_major,res_month_of_passing,res_year_of_passing,res_grade_class_marks,DATE_FORMAT(res_course_start_date,'%d-%m-%Y') as res_course_start_date,DATE_FORMAT(res_course_end_date,'%d-%m-%Y') as res_course_end_date, res_roll_no,res_enrollment_no,res_PRN_no,res_mode_of_verification,res_online_URL,verifier_designation,verifier_contact_details,res_genuineness,education_result.remarks as res_remarks,verified_by,closuredate,education_result.modified_on");

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