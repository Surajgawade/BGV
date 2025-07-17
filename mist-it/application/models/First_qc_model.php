<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class First_qc_model extends CI_Model
{
	function __construct()
    {
		$this->tableName = 'sla_default_setting';

		$this->primaryKey = 'id';



	}

	public function get_emp_final_qc($empver_aary = array())
	{
		$this->db->select("(select coname from company_database where company_database.id = empver.nameofthecompany limit 1) as coname,(select coname from company_database where company_database.id = empverres.res_nameofthecompany limit 1) as res_coname,(select report_status from status where status.id = empverres.verfstatus) as verfstatus,empid,if(empid = res_empid,'Verified',res_empid) as res_empid,empfrom,empto,employed_from, employed_to,designation, if(emp_designation = designation ,'Verified', emp_designation) as emp_designation,remuneration, if(res_remuneration = remuneration,'Verified',res_remuneration) as res_remuneration,if(r_manager_name is NOT NULL,r_manager_name,'Not Provided') as r_manager_name,if(reportingmanager = r_manager_name,'Verified' ,reportingmanager) as reportingmanager,reasonforleaving,if(res_reasonforleaving = reasonforleaving,'Verified',res_reasonforleaving) as res_reasonforleaving, if(info_integrity_disciplinary_issue IS NOT NULL,info_integrity_disciplinary_issue,'NA') as info_integrity_disciplinary_issue,if(info_exitformalities IS NOT NULL,info_exitformalities,'NA') as info_exitformalities ,if(eligforrehire IS NOT NULL,eligforrehire , 'NA') as eligforrehire,if(fmlyowned IS NOT NULL, fmlyowned,'NA') as fmlyowned ,if(empverres.justdialwebcheck IS NOT NULL,empverres.justdialwebcheck ,'NA') as justdialwebcheck,if(empverres.mcaregn IS NOT NULL,empverres.mcaregn ,'NA') as mcaregn, if(empverres.domainname IS NOT NULL,empverres.domainname,'NA') as domainname,if(empverres.remarks IS NOT NULL ,empverres.remarks,'NA') as res_remarks,if(verfname IS NOT NULL,verfname,'NA') as verfname,if(verifiers_role IS NOT NULL,verifiers_role,'NA') as verifiers_role,if(verfdesgn IS NOT NULL,verfdesgn,'NA') as verfdesgn,if(modeofverification IS NOT NULL,modeofverification,'NA') as modeofverification, empver.id as empver_id,empverres.id as empverres_id,empver.citylocality,(select insuff_raise_remark from empverres_insuff where status = 1 and empverres_insuff.empverres_id = empver.id order by id desc limit 1) as insuff_raise_remark,(SELECT GROUP_CONCAT(concat(empverres_files.id,'/',empver.clientid,'/',file_name) ORDER BY serialno,id ASC SEPARATOR '||') FROM empverres_files where empverres_files.empver_id = empver.id and empverres_files.type= 1  and empverres_files.status = 1) as attachments,(SELECT  GROUP_CONCAT(concat(view_vendor_master_log_file.file_name) SEPARATOR '||' ) FROM `view_vendor_master_log_file`, `view_vendor_master_log`, `employment_vendor_log` where view_vendor_master_log_file.view_venor_master_log_id = view_vendor_master_log.id and view_vendor_master_log_file.component_tbl_id = 2 and view_vendor_master_log_file.status = 1  and view_vendor_master_log.case_id = employment_vendor_log.id and  employment_vendor_log.case_id  = empver.id)  as vendor_attachments"); 

		$this->db->from('empver');

		$this->db->join("empverres",'empverres.empverid = empver.id');

		if(!empty($empver_aary)){
			$this->db->where($empver_aary);
		}

		$this->db->where('verfstatus !=','27');
		$this->db->where('verfstatus !=','28');


		$result = $this->db->get();
		
		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_address_final_qc($where_arry = array())
	{
		$this->db->simple_query('SET SESSION group_concat_max_len=15000');
		$this->db->select("addrver.address, city, pincode,(select state from states where states.id = addrver.state) as address_state,if(stay_from != '',stay_from,'NA') as stay_from,if(stay_to != '',stay_to,'NA') as stay_to,res_stay_from,res_stay_to,res_address,res_city,res_pincode,(select state from states where states.id = res_state) as res_state,if(addrverres.remarks != '',addrverres.remarks,'NA') as res_remarks,if(verified_by != '',verified_by,'NA') as verified_by,if(mode_of_verification != '',mode_of_verification,'NA') as mode_of_verification,addrver.id as addrver_id,(select insuff_raise_remark from addrver_insuff where status = 1 and addrver_insuff.addrverid = addrver.id order by id desc limit 1) as insuff_raise_remark,(select report_status from status where status.id = addrverres.verfstatus) as verfstatus,addrverres.id as addrverres_id,(SELECT GROUP_CONCAT(concat(addrver_files.id,'/',addrver.clientid,'/',file_name) ORDER BY serialno,id ASC SEPARATOR '||') FROM addrver_files where addrver_files.addrver_id = addrver.id and addrver_files.type= 1 and addrver_files.status = 1) as add_attachments,(SELECT  GROUP_CONCAT(concat(view_vendor_master_log_file.file_name) ORDER BY view_vendor_master_log_file.serialno,view_vendor_master_log_file.id ASC SEPARATOR '||' ) FROM `view_vendor_master_log_file`, `view_vendor_master_log`, `address_vendor_log` where view_vendor_master_log_file.view_venor_master_log_id = view_vendor_master_log.id and view_vendor_master_log_file.component_tbl_id = 1 and view_vendor_master_log_file.status = 1  and view_vendor_master_log.case_id = address_vendor_log.id and  address_vendor_log.case_id  = addrver.id)  as vendor_attachments");

		$this->db->from('addrver');

		$this->db->join("addrverres",'addrverres.addrverid = addrver.id');

		if(!empty($where_arry)){
			$this->db->where($where_arry);
		}


        $this->db->where('verfstatus !=','27');
		$this->db->where('verfstatus !=','28');


		$result = $this->db->get();
       
       
		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_education_final_qc($where_array)
	{
		$this->db->select("education.id as education_id,education_result.id as education_result_id,(select qualification from qualification_master where qualification_master.id = education.qualification) as qualification,school_college,(select universityname from university_master where university_master.id = education.university_board) as university_board,year_of_passing,(select qualification from qualification_master where qualification_master.id = education_result.res_qualification) as res_qualification,res_school_college,(select universityname from university_master where university_master.id = education_result.res_university_board) as res_university_board,if(res_year_of_passing = year_of_passing,'Verified',res_year_of_passing) as res_year_of_passing,verified_by,verifier_designation,verifier_contact_details,education_result.remarks as res_remarks,res_mode_of_verification,(select report_status from status where status.id = education_result.verfstatus) as verfstatus,(select insuff_raise_remark from education_insuff where status = 1 and education_insuff.education_id = education.id order by id desc limit 1) as insuff_raise_remark,(SELECT GROUP_CONCAT(concat(education_files.id,'/',education.clientid,'/',file_name) ORDER BY serialno,id ASC SEPARATOR '||') FROM education_files where education_files.education_id = education.id and education_files.type= 1 and education_files.status = 1) as attachments,(SELECT GROUP_CONCAT(concat(education_files.id,'/',file_name) ORDER BY serialno,id ASC SEPARATOR '||') FROM education_files where education_files.education_id = education.id and education_files.type= 4 and education_files.status = 1) as university_attachments,(SELECT  GROUP_CONCAT(concat(view_vendor_master_log_file.file_name) SEPARATOR '||' ) FROM `view_vendor_master_log_file`, `view_vendor_master_log`, `education_vendor_log` where view_vendor_master_log_file.view_venor_master_log_id = view_vendor_master_log.id and view_vendor_master_log_file.component_tbl_id = 3 and view_vendor_master_log_file.status = 1  and view_vendor_master_log.case_id = education_vendor_log.id and  education_vendor_log.case_id  = education.id)  as vendor_attachments");

		$this->db->from('education');

		$this->db->join("education_result",'education_result.education_id =education.id');
		

		$this->db->where($where_array);

		$this->db->where('verfstatus !=','27');
		$this->db->where('verfstatus !=','28');

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_reference_final_qc($where_arry)
	{
		$this->db->select("reference.name_of_reference, if(designation != '',designation,'NA') as designation,if(contact_no != '',contact_no,'NA') as contact_no,if(reference_result.remarks != '',reference_result.remarks,'NA') as res_remarks,(select report_status from status where status.id = reference_result.verfstatus) as verfstatus,reference.id as reference_id,reference_result.handle_pressure_value,reference_result.attendance_value,reference_result.integrity_value,reference_result.leadership_skills_value,reference_result.responsibilities_value,reference_result.achievements_value,reference_result.strengths_value,reference_result.team_player_value,reference_result.weakness_value,reference_result.overall_performance,reference_result.additional_comments,reference_result.mode_of_verification,(select insuff_raise_remark from reference_insuff where status = 1 and reference_insuff.reference_id = reference.id order by id desc limit 1) as insuff_raise_remark,(SELECT GROUP_CONCAT(concat(reference_files.id,'/',reference.clientid,'/',file_name) ORDER BY serialno,id ASC SEPARATOR '||') FROM reference_files where reference_files.reference_id = reference.id and reference_files.type= 1 and reference_files.status= 1 ) as attachments");

		$this->db->from('reference');

		$this->db->join("reference_result",'reference_result.reference_id = reference.id');
		
		if(!empty($where_arry)){
			$this->db->where($where_arry);
		}

        
        $this->db->where('verfstatus !=','27');
		$this->db->where('verfstatus !=','28');

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_court_final_qc($where_array)
	{
		$this->db->select("street_address,if(city != '',city,'') as city,if(pincode != '',pincode,'') as pincode, (select state from states where states.id = courtver.state) as state, remarks,mode_of_verification,advocate_name,(select report_status from status where status.id = courtver_result.verfstatus) as verfstatus,courtver.id as courtver_id,courtver_result.id as courtver_result_id,courtver.candsid,courtver.candsid,(select insuff_raise_remark from courtver_insuff where status = 1 and courtver_insuff.courtver_id = courtver.id order by id desc limit 1) as insuff_raise_remark,(SELECT GROUP_CONCAT(concat(courtver_files.id,'/',courtver.clientid,'/',file_name) ORDER BY serialno,id ASC SEPARATOR '||') FROM courtver_files where courtver_files.courtver_id = courtver.id and courtver_files.type= 1 and courtver_files.status= 1) as attachments,(SELECT  GROUP_CONCAT(concat(view_vendor_master_log_file.file_name) SEPARATOR '||' ) FROM `view_vendor_master_log_file`, `view_vendor_master_log`, `courtver_vendor_log` where view_vendor_master_log_file.view_venor_master_log_id = view_vendor_master_log.id and view_vendor_master_log_file.component_tbl_id = 5 and view_vendor_master_log_file.status = 1  and view_vendor_master_log.case_id = courtver_vendor_log.id and  courtver_vendor_log.case_id  = courtver.id)  as vendor_attachments");

		$this->db->from('courtver');

		$this->db->join("courtver_result",'courtver_result.courtver_id = courtver.id');
		
		
		$this->db->where($where_array);

		$this->db->where('verfstatus !=','27');
		$this->db->where('verfstatus !=','28');

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_drug_db_final_qc($where_array)
	{
		$this->db->select("if(drug_test_code != '',drug_test_code,'NA') as drug_test_code, if(amphetamine_screen != '',amphetamine_screen,'NA') as amphetamine_screen, if(cannabinoids_screen != '',cannabinoids_screen,'NA') as cannabinoids_screen, if(cocaine_screen != '',cocaine_screen,'NA') as cocaine_screen, if(opiates_screen != '',opiates_screen,'NA') as opiates_screen, if(phencyclidine_screen != '',phencyclidine_screen,'NA') as phencyclidine_screen, if(remarks != '',remarks,'NA') as remarks, (select report_status from status where status.id = drug_narcotis_result.verfstatus) as verfstatus,drug_narcotis.id as drug_narcotis_id,drug_narcotis_result.id as drug_narcotis_result_id, drug_narcotis.candsid,drug_narcotis.candsid,(select insuff_raise_remark from drug_narcotis_insuff where status = 1 and drug_narcotis_insuff.drug_narcotis_id = drug_narcotis.id order by id desc limit 1) as insuff_raise_remark,(SELECT GROUP_CONCAT(concat(drug_narcotis_files.id,'/',drug_narcotis.clientid,'/',file_name) ORDER BY serialno,id ASC SEPARATOR '||') FROM drug_narcotis_files where drug_narcotis_files.drug_narcotis_id = drug_narcotis.id and drug_narcotis_files.type= 1 and drug_narcotis_files.status= 1) as attachments,(SELECT  GROUP_CONCAT(concat(view_vendor_master_log_file.file_name) SEPARATOR '||' ) FROM `view_vendor_master_log_file`, `view_vendor_master_log`, `drug_narcotis_vendor_log` where view_vendor_master_log_file.view_venor_master_log_id = view_vendor_master_log.id and view_vendor_master_log_file.component_tbl_id = 7 and view_vendor_master_log_file.status = 1  and view_vendor_master_log.case_id = drug_narcotis_vendor_log.id and  drug_narcotis_vendor_log.case_id  = drug_narcotis.id)  as vendor_attachments");

		$this->db->from('drug_narcotis');

		$this->db->join("drug_narcotis_result",'drug_narcotis_result.drug_narcotis_id = drug_narcotis.id');
		
		$this->db->where($where_array);

		$this->db->where('verfstatus !=','27');
		$this->db->where('verfstatus !=','28');

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_pcc_final_qc($where_array)
	{
		$this->db->select("street_address,if(city != '',city,'') as city,if(pincode != '',pincode,'') as pincode, DATE_FORMAT(police_station_visit_date,'%d-%m-%Y') as police_station_visit_date,(select state from states where states.id = pcc.state) as state , if(name_designation_police != '',name_designation_police,'NA') as name_designation_police,if(contact_number_police != '',contact_number_police,'NA') as contact_number_police, if(mode_of_verification != '',mode_of_verification,'NA') as mode_of_verification,if(remarks != '',remarks,'NA') as remarks,if(police_station !='',police_station,'NA') as police_station, (select report_status from status where status.id = pcc_result.verfstatus) as verfstatus,pcc_result.closuredate,pcc.id as pcc_id,pcc_result.id as pcc_result_id,pcc.clientid,pcc.candsid,(select insuff_raise_remark from pcc_insuff where status = 1 and pcc_insuff.pcc_id = pcc.id order by id desc limit 1) as insuff_raise_remark,(SELECT GROUP_CONCAT(concat(pcc_files.id,'/',pcc.clientid,'/',file_name) ORDER BY serialno,id ASC SEPARATOR '||') FROM pcc_files where pcc_files.pcc_id = pcc.id and pcc_files.type= 1 and pcc_files.status= 1) as attachments,(SELECT  GROUP_CONCAT(concat(view_vendor_master_log_file.file_name) SEPARATOR '||' ) FROM `view_vendor_master_log_file`, `view_vendor_master_log`, `pcc_vendor_log` where view_vendor_master_log_file.view_venor_master_log_id = view_vendor_master_log.id and view_vendor_master_log_file.component_tbl_id = 8 and view_vendor_master_log_file.status = 1  and view_vendor_master_log.case_id = pcc_vendor_log.id and  pcc_vendor_log.case_id  = pcc.id)  as vendor_attachments");

		$this->db->from('pcc');

		$this->db->join("pcc_result",'pcc_result.pcc_id = pcc.id');
		

		$this->db->where($where_array);

		$this->db->where('verfstatus !=','27');
		$this->db->where('verfstatus !=','28');


		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_global_db_final_qc($where_array)
	{
		$this->db->select("street_address,mode_of_veri,if(city != '',city,'') as city,if(pincode != '',pincode,'') as pincode,(select state from states where states.id = glodbver.state) as state,if(mode_of_verification !='',mode_of_verification,'NA') as mode_of_verification,remarks,if(verified_by !='',verified_by,'NA') as verified_by,remarks,(select report_status from status where status.id = glodbver_result.verfstatus) as verfstatus,glodbver.id as glodbver_id,glodbver_result.id as glodbver_result_id,(select insuff_raise_remark from glodbver_insuff where status = 1 and glodbver_insuff.glodbver_id  = glodbver.id order by id desc limit 1) as insuff_raise_remark,(SELECT GROUP_CONCAT(concat(glodbver_files.id,'/',glodbver.clientid,'/',file_name) ORDER BY serialno,id ASC SEPARATOR '||') FROM glodbver_files where glodbver_files.glodbver_id = glodbver.id and glodbver_files.type= 1 and glodbver_files.status= 1) as attachments,(SELECT  GROUP_CONCAT(concat(view_vendor_master_log_file.file_name) SEPARATOR '||' ) FROM `view_vendor_master_log_file`, `view_vendor_master_log`, `glodbver_vendor_log` where view_vendor_master_log_file.view_venor_master_log_id = view_vendor_master_log.id and view_vendor_master_log_file.component_tbl_id = 6 and view_vendor_master_log_file.status = 1  and view_vendor_master_log.case_id = glodbver_vendor_log.id and  glodbver_vendor_log.case_id  = glodbver.id)  as vendor_attachments");

		$this->db->from('glodbver');

		$this->db->join("glodbver_result",'glodbver_result.glodbver_id = glodbver.id');
		

		$this->db->where($where_array);

		$this->db->where('verfstatus !=','27');
		$this->db->where('verfstatus !=','28');

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_identity_final_qc($where_array)
	{
		$this->db->select("doc_submited,id_number,identity_result.mode_of_verification,identity_result.remarks,(select report_status from status where status.id = identity_result.verfstatus) as verfstatus,identity.id as identity_id,identity_result.id as identity_result_id,(select insuff_raise_remark from identity_insuff where status = 1 and identity_insuff.identity_id  = identity.id order by id desc limit 1) as insuff_raise_remark,(SELECT GROUP_CONCAT(concat(identity_files.id,'/',identity.clientid,'/',file_name) ORDER BY serialno,id ASC SEPARATOR '||') FROM identity_files where identity_files.identity_id = identity.id and identity_files.type= 1 and identity_files.status= 1) as attachments,(SELECT  GROUP_CONCAT(concat(view_vendor_master_log_file.file_name) SEPARATOR '||' ) FROM `view_vendor_master_log_file`, `view_vendor_master_log`, `identity_vendor_log` where view_vendor_master_log_file.view_venor_master_log_id = view_vendor_master_log.id and view_vendor_master_log_file.component_tbl_id = 9 and view_vendor_master_log_file.status = 1  and view_vendor_master_log.case_id = identity_vendor_log.id and  identity_vendor_log.case_id  = identity.id)  as vendor_attachments");

		$this->db->from('identity');

		$this->db->join("identity_result",'identity_result.identity_id = identity.id');

		$this->db->where($where_array);

		$this->db->where('verfstatus !=','27');
		$this->db->where('verfstatus !=','28');

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_credit_report_final_qc($where_array)
	{
		$this->db->select("doc_submited,mode_of_veri,credit_report_result.mode_of_verification,(select report_status from status where status.id = credit_report_result.verfstatus) as verfstatus,credit_report.id as credit_report_id,credit_report_result.id as credit_report_result_id,credit_report_result.remarks,(select insuff_raise_remark from credit_report_insuff where status = 1 and credit_report_insuff.credit_report_id  = credit_report.id order by id desc limit 1) as insuff_raise_remark,(SELECT GROUP_CONCAT(concat(credit_report_files.id,'/',credit_report.clientid,'/',file_name) ORDER BY serialno,id ASC SEPARATOR '||') FROM credit_report_files where credit_report_files.credit_report_id = credit_report.id and credit_report_files.type= 1 and credit_report_files.status = 1) as attachments,(SELECT  GROUP_CONCAT(concat(view_vendor_master_log_file.file_name) SEPARATOR '||' ) FROM `view_vendor_master_log_file`, `view_vendor_master_log`, `credit_report_vendor_log` where view_vendor_master_log_file.view_venor_master_log_id = view_vendor_master_log.id and view_vendor_master_log_file.component_tbl_id = 10 and view_vendor_master_log_file.status = 1  and view_vendor_master_log.case_id = credit_report_vendor_log.id and  credit_report_vendor_log.case_id  = credit_report.id)  as vendor_attachments");

		$this->db->from('credit_report');

		$this->db->join("credit_report_result",'credit_report_result.credit_report_id = credit_report.id');

		$this->db->where($where_array);

		$this->db->where('verfstatus !=','27');
		$this->db->where('verfstatus !=','28');

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_social_media_final_qc($where_array)
	{
		$this->db->select("mode_of_veri,social_media_result.mode_of_verification,(select report_status from status where status.id = social_media_result.verfstatus) as verfstatus,social_media.id as social_media_id,social_media_result.id as social_media_result_id,social_media_result.remarks,(select insuff_raise_remark from social_media_insuff where status = 1 and social_media_insuff.social_media_id  = social_media.id order by id desc limit 1) as insuff_raise_remark,(SELECT GROUP_CONCAT(concat(social_media_files.id,'/',social_media.clientid,'/',file_name) ORDER BY serialno,id ASC SEPARATOR '||') FROM social_media_files where social_media_files.social_media_id = social_media.id and social_media_files.type= 1 and social_media_files.status = 1) as attachments");

		$this->db->from('social_media');

		$this->db->join("social_media_result",'social_media_result.social_media_id = social_media.id');

		$this->db->where($where_array);

		$this->db->where('verfstatus !=','27');
		$this->db->where('verfstatus !=','28');

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_address_first_qc($where_arry = array())
	{
		$this->db->select("addrver.*,candidates_info.entity,candidates_info.package,candidates_info.clientid,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate, addrverres.id addrverres_id,(select status_value from status where status.id = addrverres.verfstatus limit 1) as verfstatus,user_profile.user_name,addrverres.var_filter_status,addrverres.res_address_type,addrverres.res_address,addrverres.res_stay_from,addrverres.res_stay_from,addrverres.res_stay_to,addrverres.neighbour_1,addrverres.neighbour_details_1,addrverres.neighbour_2,addrverres.neighbour_details_2,addrverres.mode_of_verification,addrverres.resident_status,addrverres.res_city,addrverres.res_pincode,addrverres.res_state,addrverres.id as addrverid,addrverres.landmark,addrverres.verified_by,addrverres.addr_proof_collected,addrverres.remarks,addrverres.first_qc_approve,due_date,tat_status,closuredate,(SELECT GROUP_CONCAT(concat(addrver_files.id,'/',addrverres.clientid,'/',file_name) ORDER BY serialno,id ASC SEPARATOR '||') FROM addrver_files where addrver_files.addrver_id = addrver.id and addrver_files.type= 1  and addrver_files.status = 1) as add_attachments");



		$this->db->from('addrver');

		$this->db->join("addrverres",'addrverres.addrverid = addrver.id');

		$this->db->join("candidates_info",'candidates_info.id = addrver.candsid');

		$this->db->join("user_profile",'user_profile.id = addrverres.modified_by');

		$this->db->where('verfstatus !=','27');
		$this->db->where('verfstatus !=','28');

       
         
        $this->db->order_by('addrver.id','desc'); 

		if(!empty($where_arry)){
			$this->db->where($where_arry);
		}

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_emp_first_qc($empver_aary = array())
	{
		$this->db->select("empver.*,empver.id as empver_id,(select coname from company_database where company_database.id = empverres.res_nameofthecompany limit 1) as res_coname,empver.empfrom,empver.empto,empverres.employed_from,empverres.employed_to,candidates_info.entity,candidates_info.package,candidates_info.clientid,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,user_profile.user_name,empverres.id as empverres_id,empverres.modified_on,(select user_name from user_profile where id = empverres.created_by) as res_created_by,(select status_value from status where status.id = empverres.verfstatus limit 1) as verfstatus,if(empverres.remarks is NOT NULL,empverres.remarks,'NA') as res_remarks,empverres.var_filter_status,empverres.created_on as res_created_on,empverres.closuredate,(select coname from company_database where company_database.id = empver.nameofthecompany limit 1) as coname,empverres.*,(select status_value from status where id = empverres.verfstatus) as verfstatus,(SELECT GROUP_CONCAT(concat(empverres_files.id,'/',empver.clientid,'/',file_name) ORDER BY serialno,id ASC SEPARATOR '||') FROM empverres_files where empverres_files.empver_id = empver.id and empverres_files.type= 1  and empverres_files.status = 1) as attachments");


		$this->db->from('empver');

		$this->db->join("empverres",'empverres.empverid = empver.id');

		$this->db->join("candidates_info",'candidates_info.id = empver.candsid');

		$this->db->join("user_profile",'user_profile.id = empverres.modified_by');

		$this->db->where('verfstatus !=','27');
		$this->db->where('verfstatus !=','28');

	
     	$this->db->order_by('empver.id','desc');

		if(!empty($empver_aary)){
			$this->db->where($empver_aary);
		}

		$result = $this->db->get();
		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

    public function get_education_first_qc($where_array  = array())
	{
		$this->db->select("candidates_info.id as cands_id,candidates_info.entity,candidates_info.package,candidates_info.clientid,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = education_result.modified_by) as user_name,status.status_value as verfstatus,education.id as education_id,school_college,(select universityname from university_master where university_master.id = education.university_board) as university_board,grade_class_marks,(select qualification from qualification_master where qualification_master.id = education.qualification) as qualification,major,DATE_FORMAT(course_start_date,'%d-%m-%Y') as course_start_date,DATE_FORMAT(course_end_date,'%d-%m-%Y') as course_end_date,month_of_passing,year_of_passing,roll_no,enrollment_no,PRN_no,documents_provided,genuineness,city,state,education.education_com_ref,education.city,education.state as state_id,education_result.id as education_result_id,education_result.var_filter_status,education_result.first_qc_approve,education_result.first_qc_updated_on,education_result.first_qu_reject_reason,education.id,education.has_case_id,education.online_URL,education.has_assigned_on,education.iniated_date,(select created_on from activity_log where comp_table_id = education.id and component_type = 5 order by id desc limit 1) as last_activity_date,(select clientname from clients where clients.id = candidates_info.clientid) as clientname,(select state from states where states.id= education.state) as state,due_date,tat_status,(select qualification from qualification_master where qualification_master.id = education_result.res_qualification) as res_qualification,res_school_college,(select universityname from university_master where university_master.id = education_result.res_university_board) as res_university_board,res_major,res_month_of_passing,res_year_of_passing,res_grade_class_marks,DATE_FORMAT(res_course_start_date,'%d-%m-%Y') as res_course_start_date,DATE_FORMAT(res_course_end_date,'%d-%m-%Y') as res_course_end_date, res_roll_no,res_enrollment_no,res_PRN_no,res_mode_of_verification,res_online_URL,verifier_designation,verifier_contact_details,res_genuineness,education_result.remarks as res_remarks,verified_by,closuredate,education_result.modified_on,(SELECT GROUP_CONCAT(concat(education_files.id,'/',education.clientid,'/',file_name) ORDER BY serialno,id ASC SEPARATOR '||') FROM education_files where education_files.education_id = education.id and education_files.status = 1 and education_files.type= 1) as attachments");

		$this->db->from('education');

		$this->db->join("education_result",'education_result.education_id = education.id');
		
		$this->db->join("candidates_info",'candidates_info.id = education.candsid');
		
		$this->db->join("status",'status.id = education_result.verfstatus');

		$this->db->where('verfstatus !=','27');
		$this->db->where('verfstatus !=','28');

		$this->db->order_by('education.id','desc'); 


		$this->db->where($where_array);

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_reference_first_qc($where_arry  = array())
	{
		$this->db->select("reference.*,candidates_info.entity,candidates_info.package,candidates_info.clientid, candidates_info.CandidateName,candidates_info.cmp_ref_no,candidates_info.ClientRefNumber,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,(select clientname from clients where clients.id = reference.clientid limit 1) as clientname,reference.iniated_date, candidates_info.caserecddate, reference_result.remarks as res_remarks,reference_result.id as reference_ver_id,handle_pressure,handle_pressure_value,attendance,attendance_value,integrity,integrity_value,leadership_skills,overall_performance,mode_of_verification,additional_comments,leadership_skills_value,responsibilities,responsibilities_value,achievements,achievements_value,strengths,strengths_value,team_player,team_player_value,weakness,weakness_value,(select status_value from status where id = reference_result.verfstatus) as verfstatus, reference_result.var_filter_status, user_profile.user_name,(SELECT GROUP_CONCAT(concat(reference_files.id,'/',reference.clientid,'/',file_name) ORDER BY serialno ASC SEPARATOR '||') FROM reference_files where reference_files.reference_id = reference.id and reference_files.status = 1 and reference_files.type= 1) as attachments");

		$this->db->from('reference');

		$this->db->join("reference_result",'reference_result.reference_id = reference.id');
		
		$this->db->join("candidates_info",'candidates_info.id = reference.candsid');
		
		$this->db->join("user_profile",'user_profile.id = reference_result.modified_by');

		$this->db->where('verfstatus !=','27');
		$this->db->where('verfstatus !=','28');

       $this->db->order_by('reference.id','desc'); 


		if(!empty($where_arry)){
			$this->db->where($where_arry);
		}

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_court_first_qc($where_array  = array())
	{
		$this->db->select("candidates_info.id as cands_id,candidates_info.entity,candidates_info.package,candidates_info.clientid,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = courtver_result.modified_by) as user_name,status.status_value as verfstatus,courtver_result.var_filter_status, courtver.id as courtver_id,courtver.court_com_ref,courtver.has_case_id,courtver.address_type, courtver.street_address,courtver.city,courtver.pincode,courtver.state as state_id,courtver_result.id as courtver_result_id,courtver_result.first_qc_approve,courtver_result.first_qc_updated_on,courtver_result.first_qu_reject_reason,courtver.id,courtver.has_assigned_on,courtver.iniated_date,(select clientname from clients where clients.id = candidates_info.clientid) as clientname,(select created_on from activity_log where comp_table_id = courtver.id and component_type = 3 order by id desc limit 1) as last_activity_date,due_date,tat_status,(select state from states where states.id= courtver.state) as state, mode_of_verification, advocate_name,courtver_result.remarks,closuredate,,courtver_result.modified_on,(SELECT GROUP_CONCAT(concat(courtver_files.id,'/',courtver.clientid,'/',file_name) ORDER BY serialno,id ASC SEPARATOR '||') FROM courtver_files where courtver_files.courtver_id = courtver.id and courtver_files.status = 1 and courtver_files.type= 1) as attachments");

		$this->db->from('courtver');

		$this->db->join("courtver_result",'courtver_result.courtver_id = courtver.id');
		
		$this->db->join("candidates_info",'candidates_info.id = courtver.candsid');
		
		$this->db->join("status",'status.id = courtver_result.verfstatus');


		$this->db->where('verfstatus !=','27');
		$this->db->where('verfstatus !=','28');

		
		$this->db->where($where_array);

		$this->db->order_by('courtver.id','desc'); 


		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_global_db_first_qc($where_array  = array())
	{
		$this->db->select("candidates_info.id as cands_id,candidates_info.entity,candidates_info.package,candidates_info.clientid,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = glodbver_result.modified_by) as user_name,status.status_value as verfstatus,glodbver_result.var_filter_status,glodbver.id as glodbver_id, glodbver.global_com_ref,glodbver.address_type, glodbver.state as state_id, glodbver.street_address,glodbver.city,glodbver.pincode,glodbver_result.id as glodbver_result_id,glodbver_result.first_qc_approve,glodbver_result.first_qc_updated_on,glodbver_result.first_qu_reject_reason,glodbver.id,glodbver.has_assigned_on,glodbver.iniated_date,(select created_on from activity_log where comp_table_id = glodbver.id and component_type = 7 order by id desc limit 1) as last_activity_date,due_date,tat_status,(select created_on from activity_log where comp_table_id = glodbver.id and component_type = 7 order by id desc limit 1) as last_activity_date,(select clientname from clients where clients.id = candidates_info.clientid) as clientname,(select state from states where states.id= glodbver.state) as state,mode_of_verification,closuredate,glodbver_result.verified_by,glodbver_result.remarks,glodbver_result.modified_on,(SELECT GROUP_CONCAT(concat(glodbver_files.id,'/',glodbver.clientid,'/',file_name) ORDER BY serialno,id ASC SEPARATOR '||') FROM glodbver_files where glodbver_files.glodbver_id = glodbver.id and glodbver_files.status = 1 and glodbver_files.type= 1) as attachments");

		$this->db->from('glodbver');

		$this->db->join("glodbver_result",'glodbver_result.glodbver_id = glodbver.id');
		
		$this->db->join("candidates_info",'candidates_info.id = glodbver.candsid');
		
		$this->db->join("status",'status.id = glodbver_result.verfstatus');


		$this->db->where('verfstatus !=','27');
		$this->db->where('verfstatus !=','28');

		$this->db->where($where_array);

		$this->db->order_by('glodbver.id','desc'); 


		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_drug_db_first_qc($where_array = array())
	{
		$this->db->select("drug_narcotis.id,candidates_info.id as cands_id,candidates_info.entity,candidates_info.package,candidates_info.clientid,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = drug_narcotis_result.modified_by) as user_name,status.status_value as verfstatus, drug_narcotis_result.var_filter_status,drug_narcotis.id as drug_narcotis_id, drug_narcotis.appointment_date,  drug_narcotis.appointment_time, drug_narcotis.spoc_no, drug_narcotis.drug_test_code, drug_narcotis.facility_name,  drug_narcotis.drug_com_ref,drug_narcotis.street_address,drug_narcotis.city,drug_narcotis.pincode,drug_narcotis.state as state_id,drug_narcotis_result.amphetamine_screen,drug_narcotis_result.cannabinoids_screen,drug_narcotis_result.cocaine_screen,drug_narcotis_result.opiates_screen,drug_narcotis_result.phencyclidine_screen,drug_narcotis_result.first_qc_approve,drug_narcotis_result.id as drug_narcotis_result_id,drug_narcotis_result.first_qc_updated_on,drug_narcotis_result.first_qu_reject_reason,drug_narcotis.has_assigned_on,drug_narcotis.iniated_date,clients.clientname,(select created_on from activity_log where comp_table_id = drug_narcotis.id and component_type = 8 order by id desc limit 1) as last_activity_date,due_date,tat_status,(select state from states where states.id= drug_narcotis.state) as state,drug_narcotis_result.mode_of_verification,closuredate,drug_narcotis_result.remarks,drug_narcotis_result.modified_on,(SELECT GROUP_CONCAT(concat(drug_narcotis_files.id,'/',drug_narcotis.clientid,'/',file_name) ORDER BY serialno ASC SEPARATOR '||') FROM drug_narcotis_files where drug_narcotis_files.drug_narcotis_id = drug_narcotis.id and drug_narcotis_files.status = 1 and drug_narcotis_files.type= 1) as attachments");

		$this->db->from('drug_narcotis');

		$this->db->join("drug_narcotis_result",'drug_narcotis_result.drug_narcotis_id = drug_narcotis.id');
		
		$this->db->join("candidates_info",'candidates_info.id = drug_narcotis.candsid');
		
		$this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("status",'status.id = drug_narcotis_result.verfstatus');


		$this->db->where('verfstatus !=','27');
		$this->db->where('verfstatus !=','28');

		$this->db->where($where_array);

		$this->db->order_by('drug_narcotis.id','desc'); 

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_pcc_first_qc($where_array = array())
	{
		$this->db->select("candidates_info.id as cands_id,candidates_info.entity,candidates_info.package, candidates_info.clientid,pcc.id as pcc_id,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = pcc_result.modified_by) as user_name,status.status_value as verfstatus,pcc_result.var_filter_status,pcc.pcc_com_ref,pcc.address_type, pcc.street_address,pcc.city,pcc.pincode,pcc.state as state_id,pcc.references,pcc.references_no,pcc_result.id as pcc_result_id,pcc_result.first_qc_approve,pcc_result.first_qc_updated_on,pcc_result.first_qu_reject_reason,pcc.id,pcc.has_assigned_on,pcc.iniated_date,(select created_on from activity_log where comp_table_id = pcc.id and component_type = 4 order by id desc limit 1) as last_activity_date,due_date,tat_status,(select clientname from clients where clients.id = candidates_info.clientid) as clientname,(select state from states where states.id= pcc.state) as state,pcc_result.closuredate,pcc_result.mode_of_verification,pcc_result.application_id_ref,pcc_result.submission_date,pcc_result.police_station,pcc_result.police_station_visit_date,pcc_result.name_designation_police,pcc_result.contact_number_police,pcc_result.remarks,pcc_result.modified_on,(SELECT GROUP_CONCAT(concat(pcc_files.id,'/',pcc.clientid,'/',file_name) ORDER BY serialno ASC SEPARATOR '||') FROM pcc_files where pcc_files.pcc_id = pcc.id and pcc_files.status = 1 and pcc_files.type= 1) as attachments");

		$this->db->from('pcc');

		$this->db->join("pcc_result",'pcc_result.pcc_id = pcc.id');
		
		$this->db->join("candidates_info",'candidates_info.id = pcc.candsid');
		
		$this->db->join("status",'status.id = pcc_result.verfstatus');


		$this->db->where('verfstatus !=','27');
		$this->db->where('verfstatus !=','28');


		$this->db->where($where_array);

		$this->db->order_by('pcc.id','desc'); 

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_identity_first_qc($where_array = array())
	{
		$this->db->select("identity.id,candidates_info.id as cands_id,candidates_info.entity,candidates_info.package,candidates_info.clientid,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = identity_result.modified_by) as user_name,status.status_value as verfstatus,identity_result.var_filter_status,identity.id as identity_id,identity.has_case_id, identity.identity_com_ref,identity.doc_submited,identity.id_number, identity.street_address,identity.city,identity.pincode,identity.state as state_id,identity_result.id as identity_result_id,identity_result.first_qc_approve,identity_result.first_qc_updated_on,identity_result.first_qu_reject_reason,identity_result.mode_of_verification,identity_result.remarks,identity_result.closuredate,identity.has_assigned_on,identity.iniated_date,clients.clientname,(select created_on from activity_log where comp_table_id = identity.id and component_type = 13 order by id desc limit 1) as last_activity_date,due_date,tat_status,(select state from states where states.id= identity.state) as state,identity_result.modified_on,(SELECT GROUP_CONCAT(concat(identity_files.id,'/',identity.clientid,'/',file_name) ORDER BY serialno ASC SEPARATOR '||') FROM identity_files where identity_files.identity_id = identity.id and identity_files.status = 1 and identity_files.type= 1) as attachments");

		$this->db->from('identity');

		$this->db->join("identity_result",'identity_result.identity_id = identity.id');
		
		$this->db->join("candidates_info",'candidates_info.id = identity.candsid');
		
		$this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("status",'status.id = identity_result.verfstatus');


		$this->db->where('verfstatus !=','27');
		$this->db->where('verfstatus !=','28');


		$this->db->where($where_array);

		$this->db->order_by('identity.id','desc'); 

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

    public function get_credit_report_first_qc($where_arry = array())
	{
		$this->db->select("credit_report.*,candidates_info.entity,candidates_info.package,candidates_info.clientid, candidates_info.CandidateName,candidates_info.cmp_ref_no,candidates_info.ClientRefNumber,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,(select clientname from clients where clients.id = credit_report.clientid limit 1) as clientname,candidates_info.caserecddate,credit_report_result.id as credit_report_result_id,credit_report_result.remarks,(select status_value from status where id = credit_report_result.verfstatus) as verfstatus, credit_report_result.var_filter_status, credit_report_result.mode_of_verification, user_profile.user_name,(SELECT GROUP_CONCAT(concat(credit_report_files.id,'/',credit_report.clientid,'/',file_name) ORDER BY serialno ASC SEPARATOR '||') FROM credit_report_files where credit_report_files.credit_report_id = credit_report.id and credit_report_files.status = 1 and credit_report_files.type= 1) as attachments");

		$this->db->from('credit_report');

		$this->db->join("credit_report_result",'credit_report_result.credit_report_id = credit_report.id');
		
		$this->db->join("candidates_info",'candidates_info.id = credit_report.candsid');
		
		$this->db->join("user_profile",'user_profile.id = credit_report_result.modified_by');

		$this->db->where('verfstatus !=','27');
		$this->db->where('verfstatus !=','28');


       $this->db->order_by('credit_report.id','desc'); 


		if(!empty($where_arry)){
			$this->db->where($where_arry);
		}

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_address_display_first_qc($where_arry = array())
	{
		$this->db->select("addrver.id,addrver.modified_on,addrver.add_com_ref,addrver.iniated_date,candidates_info.entity,candidates_info.package,candidates_info.clientid,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no, addrverres.id  as addrverres_id,(select status_value from status where status.id = addrverres.verfstatus limit 1) as verfstatus,user_profile.user_name, addrverres.var_filter_status, clients.clientname,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,candidates_info.ClientRefNumber, candidates_info.cmp_ref_no");

		$this->db->from('addrver');

		$this->db->join("addrverres",'addrverres.addrverid = addrver.id');

		$this->db->join("candidates_info",'candidates_info.id = addrver.candsid');

		$this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("user_profile",'user_profile.id = addrverres.modified_by');

		$this->db->where('verfstatus !=','27');
		$this->db->where('verfstatus !=','28');

         
        $this->db->order_by('addrverres.modified_on','desc'); 

		if(!empty($where_arry)){
			$this->db->where($where_arry);
		}

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_emp_display_first_qc($empver_aary = array())
	{
		$this->db->select("empver.id,empver.emp_com_ref,empver.iniated_date,candidates_info.entity,candidates_info.package,candidates_info.clientid,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,user_profile.user_name,empverres.id as empverres_id,empverres.modified_on,(select status_value from status where status.id = empverres.verfstatus limit 1) as verfstatus,empverres.var_filter_status,clients.clientname,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no");

		$this->db->from('empver');

		$this->db->join("empverres",'empverres.empverid = empver.id');

		$this->db->join("candidates_info",'candidates_info.id = empver.candsid');

	    $this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("user_profile",'user_profile.id = empverres.modified_by');

		$this->db->where('verfstatus !=','27');
		$this->db->where('verfstatus !=','28');

	  
		$this->db->order_by('empverres.modified_on','desc');

		if(!empty($empver_aary)){
			$this->db->where($empver_aary);
		}

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_education_display_first_qc($where_array  = array())
	{
		$this->db->select("education.id as education_id,education.education_com_ref,education.iniated_date,candidates_info.entity,candidates_info.package,candidates_info.clientid,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,(select user_name from user_profile where user_profile.id = education_result.modified_by) as user_name,status.status_value as verfstatus,clients.clientname,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,education_result.modified_on,candidates_info.ClientRefNumber, candidates_info.cmp_ref_no");

		$this->db->from('education');

		$this->db->join("education_result",'education_result.education_id = education.id');
		
		$this->db->join("candidates_info",'candidates_info.id = education.candsid');

	    $this->db->join("clients",'clients.id = candidates_info.clientid');

		
		$this->db->join("status",'status.id = education_result.verfstatus');

		$this->db->where('verfstatus !=','27');
		$this->db->where('verfstatus !=','28');


	  
		$this->db->order_by('education_result.modified_on','desc'); 


		$this->db->where($where_array);

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_reference_display_first_qc($where_arry  = array())
	{
		$this->db->select("reference.id,candidates_info.entity,candidates_info.package,candidates_info.clientid, candidates_info.CandidateName,candidates_info.cmp_ref_no,candidates_info.ClientRefNumber,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,(select clientname from clients where clients.id = reference.clientid limit 1) as clientname,reference.iniated_date,reference.reference_com_ref,reference_result.modified_on, candidates_info.caserecddate,(select status_value from status where id = reference_result.verfstatus) as verfstatus, reference_result.var_filter_status, user_profile.user_name,candidates_info.ClientRefNumber, candidates_info.cmp_ref_no");

		$this->db->from('reference');

		$this->db->join("reference_result",'reference_result.reference_id = reference.id');
		
		$this->db->join("candidates_info",'candidates_info.id = reference.candsid');
		
		$this->db->join("user_profile",'user_profile.id = reference_result.modified_by');

		$this->db->where('verfstatus !=','27');
		$this->db->where('verfstatus !=','28');


	   
       $this->db->order_by('reference_result.modified_on','desc'); 


		if(!empty($where_arry)){
			$this->db->where($where_arry);
		}

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

   public function get_global_display_db_first_qc($where_array  = array())
	{
		$this->db->select("glodbver.id,glodbver.global_com_ref,glodbver.iniated_date,candidates_info.entity,candidates_info.package,candidates_info.clientid,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,(select user_name from user_profile where user_profile.id = glodbver_result.modified_by) as user_name,status.status_value as verfstatus,glodbver_result.var_filter_status,(select clientname from clients where clients.id = candidates_info.clientid) as clientname,glodbver_result.modified_on,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,candidates_info.ClientRefNumber, candidates_info.cmp_ref_no");

		$this->db->from('glodbver');

		$this->db->join("glodbver_result",'glodbver_result.glodbver_id = glodbver.id');
		
		$this->db->join("candidates_info",'candidates_info.id = glodbver.candsid');
		
		$this->db->join("status",'status.id = glodbver_result.verfstatus');


		$this->db->where('verfstatus !=','27');
		$this->db->where('verfstatus !=','28');


		$this->db->where($where_array);

		$this->db->order_by('glodbver_result.modified_on','desc'); 


		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_court_display_first_qc($where_array  = array())
	{
		$this->db->select("candidates_info.entity,candidates_info.package,candidates_info.clientid,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,(select user_name from user_profile where user_profile.id = courtver_result.modified_by) as user_name,status.status_value as verfstatus,courtver_result.var_filter_status,courtver_result.modified_on, courtver.id,courtver.court_com_ref, courtver.iniated_date,(select clientname from clients where clients.id = candidates_info.clientid) as clientname,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,candidates_info.ClientRefNumber, candidates_info.cmp_ref_no");

		$this->db->from('courtver');

		$this->db->join("courtver_result",'courtver_result.courtver_id = courtver.id');
		
		$this->db->join("candidates_info",'candidates_info.id = courtver.candsid');
		
		$this->db->join("status",'status.id = courtver_result.verfstatus');


		$this->db->where('verfstatus !=','27');
		$this->db->where('verfstatus !=','28');

		
		$this->db->where($where_array);

		$this->db->order_by('courtver_result.modified_on','desc'); 


		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}


	public function get_identity_display_first_qc($where_array = array())
	{
		$this->db->select("identity.id,candidates_info.entity,candidates_info.package,candidates_info.clientid,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,(select user_name from user_profile where user_profile.id = identity_result.modified_by) as user_name,status.status_value as verfstatus,identity_result.var_filter_status,identity.identity_com_ref,identity.iniated_date,clients.clientname,identity_result.modified_on,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,candidates_info.ClientRefNumber, candidates_info.cmp_ref_no");

		$this->db->from('identity');

		$this->db->join("identity_result",'identity_result.identity_id = identity.id');
		
		$this->db->join("candidates_info",'candidates_info.id = identity.candsid');
		
		$this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("status",'status.id = identity_result.verfstatus');


		$this->db->where('verfstatus !=','27');
		$this->db->where('verfstatus !=','28');

	

		$this->db->where($where_array);

		$this->db->order_by('identity_result.modified_on','desc'); 

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_pcc_display_first_qc($where_array = array())
	{
		$this->db->select("candidates_info.entity,candidates_info.package, candidates_info.clientid,pcc.id,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,(select user_name from user_profile where user_profile.id = pcc_result.modified_by) as user_name,status.status_value as verfstatus,pcc_result.var_filter_status,pcc.pcc_com_ref,pcc.iniated_date,(select clientname from clients where clients.id = candidates_info.clientid) as clientname,pcc_result.modified_on,(select clientname from clients where clients.id = candidates_info.clientid) as clientname,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,candidates_info.ClientRefNumber, candidates_info.cmp_ref_no");

		$this->db->from('pcc');

		$this->db->join("pcc_result",'pcc_result.pcc_id = pcc.id');
		
		$this->db->join("candidates_info",'candidates_info.id = pcc.candsid');
		
		$this->db->join("status",'status.id = pcc_result.verfstatus');


		$this->db->where('verfstatus !=','27');
		$this->db->where('verfstatus !=','28');
	

		$this->db->where($where_array);

		$this->db->order_by('pcc_result.modified_on','desc'); 

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_credit_display_report_first_qc($where_arry = array())
	{
		$this->db->select("credit_report.id,credit_report.credit_report_com_ref,credit_report.iniated_date,candidates_info.entity,candidates_info.package,candidates_info.clientid, candidates_info.CandidateName,candidates_info.cmp_ref_no,candidates_info.ClientRefNumber,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,(select clientname from clients where clients.id = credit_report.clientid limit 1) as clientname,credit_report.id,(select status_value from status where id = credit_report_result.verfstatus) as verfstatus, credit_report_result.var_filter_status,credit_report_result.modified_on,user_profile.user_name,candidates_info.ClientRefNumber, candidates_info.cmp_ref_no");

		$this->db->from('credit_report');

		$this->db->join("credit_report_result",'credit_report_result.credit_report_id = credit_report.id');
		
		$this->db->join("candidates_info",'candidates_info.id = credit_report.candsid');
		
		$this->db->join("user_profile",'user_profile.id = credit_report_result.modified_by');

		$this->db->where('verfstatus !=','27');
		$this->db->where('verfstatus !=','28');



       $this->db->order_by('credit_report_result.modified_on','desc'); 


		if(!empty($where_arry)){
			$this->db->where($where_arry);
		}

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}


  public function get_drug_display_db_first_qc($where_array = array())
	{
		$this->db->select("drug_narcotis.id,candidates_info.entity,candidates_info.package,candidates_info.clientid,candidates_info.CandidateName,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,(select user_name from user_profile where user_profile.id = drug_narcotis_result.modified_by) as user_name,status.status_value as verfstatus, drug_narcotis_result.var_filter_status,drug_narcotis.id,drug_narcotis.drug_com_ref,drug_narcotis.iniated_date,clients.clientname,drug_narcotis_result.modified_on,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no");

		$this->db->from('drug_narcotis');

		$this->db->join("drug_narcotis_result",'drug_narcotis_result.drug_narcotis_id = drug_narcotis.id');
		
		$this->db->join("candidates_info",'candidates_info.id = drug_narcotis.candsid');
		
		$this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("status",'status.id = drug_narcotis_result.verfstatus');


		$this->db->where('verfstatus !=','27');
		$this->db->where('verfstatus !=','28');



		$this->db->where($where_array);

		$this->db->order_by('drug_narcotis_result.modified_on','desc'); 

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}
  

   public function get_client_entity_package_details($where_array)
	{
       $this->db->select("clients.id,clients.clientname,clients.status,clients_details.tbl_clients_id,clients_details.entity,clients_details.package");

		$this->db->from('clients');

		$this->db->join("clients_details",'clients_details.tbl_clients_id =clients.id');
		
		

		$this->db->where($where_array);

		$result = $this->db->get();
 //  print_r($this->db->last_query());
		record_db_error($this->db->last_query());
		
		return $result->result_array(); 
   


	}
	 public function get_client_entity_package_details_component($where_array)
	{
       $this->db->select("clients.id,clients.clientname,clients.status,clients_details.tbl_clients_id,clients_details.entity,clients_details.package");

		$this->db->from('clients');

		$this->db->join("clients_details",'clients_details.tbl_clients_id =clients.id');
		
		

		$this->db->where($where_array);

		$result = $this->db->get();
 //  print_r($this->db->last_query());
		record_db_error($this->db->last_query());
		
		return $result->result_array(); 
   


	}

	 public function check_component_exists($where_array)
	{
       $this->db->select("tbl_clients_id,entity,package,first_qc_component_name");

		$this->db->from('clients_details');
		

		$this->db->where($where_array);

		$result = $this->db->get();
 //  print_r($this->db->last_query());
		record_db_error($this->db->last_query());
		
		return $result->result_array(); 
   


	}
	

}