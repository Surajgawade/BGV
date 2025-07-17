<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Candidates_model_insufficiency extends CI_Model
{
	function __construct()
    {
		//parent::__construct();

		$this->tableName = 'candidates_info';

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

	public function delete($arrwhere)
	{
	  $result =  $this->db->delete($this->tableName, $arrwhere);

	  record_db_error($this->db->last_query());
	  
	  return $result;
	}
	
	public function get_all_cand_by($client_id = NULL)
	{
		$this->db->select("candidates_info.*,clients.clientname");

		$this->db->from('candidates_info');

		$this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("eduver",'eduver.candidates_infoid = candidates_info.id','left');

		if($client_id)
		{
			$this->db->where("candidates_info.clientid",$client_id);
		}
		$this->db->order_by('id', 'desc');
		
		$this->db->group_by('candidates_info.id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}


	public function get_all_cand_with_search($package_name,$client_id,$where,$columns= null)
		{
            $package_name1   = explode(",", $package_name);

			$this->db->select("candidates_info.id,candidates_info.clientid,candidates_info.CandidateName,candidates_info.caserecddate,candidates_info.Location,(select entity_package_name  from entity_package where id=candidates_info.entity and is_entity_package = 1) as entity_name,(select entity_package_name  from entity_package where id=candidates_info.package and is_entity_package = 2) as package_name,candidates_info.Department,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.overallstatus,candidates_info.remarks,clients.clientname,clients_details.tat_addrver,clients_details.tat_courtver,clients_details.tat_crimver,clients_details.tat_eduver,clients_details.tat_empver,clients_details.tat_narcver,clients_details.tat_refver,clients_details.tat_globdbver,candidates_info.overallclosuredate,candidates_info.ClientRefNumber AS EmployeeCode,status.status_value");

			

			if(is_array($where) && $where['search']['value'] != "")
			{
				$this->db->like($this->tableName.'.ClientRefNumber', $where['search']['value']);

				$this->db->or_like('clients.clientname', $where['search']['value']);

				$this->db->or_like($this->tableName.'.cmp_ref_no', $where['search']['value']);

				$this->db->or_like($this->tableName.'.CandidateName', $where['search']['value']);

				$this->db->or_like($this->tableName.'.CandidatesContactNumber', $where['search']['value']);

				$this->db->or_like($this->tableName.'.EmployeeCode', $where['search']['value']);

				$this->db->or_like($this->tableName.'.overallstatus', $where['search']['value']);

			}

	        $this->db->limit($where['length'],$where['start']);
	        //$this->db->limit(50,0);


	        $this->db->from('candidates_info');

	        $this->db->join("status",'status.id = candidates_info.overallstatus');
 
	        if($client_id)
	        {
	            $this->db->join("clients","(clients.id = candidates_info.clientid and candidates_info.clientid = $client_id)");
	            $this->db->join("clients_details","(clients_details.tbl_clients_id = candidates_info.clientid and candidates_info.clientid = $client_id)");
	        }
	        else
	        {
	            $this->db->join("clients",'clients.id = candidates_info.clientid');
	            $this->db->join("clients_details",'clients_details.tbl_clients_id = candidates_info.clientid');
	        }

	        if(isset($where['clientid']) &&  $where['clientid'] != 0  &&  $where['clientid'] != "")	
		    {
			
			    $this->db->where("candidates_info.clientid", $where['clientid']);
			}
			
			if(isset($where['entity']) &&  $where['entity'] != "" && $where['entity'] != 0)	
			{
				$this->db->where("candidates_info.clientid", $where['entity']);
			}

			if(isset($where['package']) &&  $where['package'] != 0 && $where['package'] != "")	
			{
				$this->db->where("candidates_info.package",$where['package']);
			}

             


	        if($package_name1)
	        {
	           $this->db->where_in("candidates_info.package",$package_name1); 
	        }
            
            $this->db->where_in("candidates_info.overallstatus",5); 

			$this->db->order_by('candidates_info.id', 'desc');
			
			$this->db->group_by('candidates_info.id');
           
	        $result = $this->db->get();
	        $str = $this->db->last_query();

			record_db_error($this->db->last_query());

			return $result->result_array();
		}





	public function get_all_cand_with_search_count($package_name,$client_id,$where,$columns)
	{
        

         $package_name1   = explode(",", $package_name);

		$this->db->select("candidates_info.id,candidates_info.clientid,candidates_info.CandidateName,candidates_info.caserecddate,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.overallstatus,candidates_info.remarks,clients.clientname,clients_details.tat_addrver,clients_details.tat_courtver,clients_details.tat_crimver,clients_details.tat_eduver,clients_details.tat_empver,clients_details.tat_narcver,clients_details.tat_refver,clients_details.tat_globdbver,candidates_info.overallclosuredate");

		$this->db->from('candidates_info');
  		
  		if($client_id)
		{
			$this->db->join("clients","(clients.id = candidates_info.clientid and candidates_info.clientid = $client_id)");
		    $this->db->join("clients_details","(clients_details.tbl_clients_id = candidates_info.clientid and candidates_info.clientid = $client_id)");

		}   
        else
		{
			$this->db->join("clients",'clients.id = candidates_info.clientid');
            $this->db->join("clients_details",'clients_details.tbl_clients_id = candidates_info.clientid');

		}
		
		// if($client_id)
		// {
		// 	$this->db->where("candidates_info.clientid",$client_id);
		// }

				
		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like($this->tableName.'.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);

			$this->db->or_like($this->tableName.'.cmp_ref_no', $where['search']['value']);

			$this->db->or_like($this->tableName.'.CandidateName', $where['search']['value']);

			$this->db->or_like($this->tableName.'.CandidatesContactNumber', $where['search']['value']);

			$this->db->or_like($this->tableName.'.EmployeeCode', $where['search']['value']);

			$this->db->or_like($this->tableName.'.overallstatus', $where['search']['value']);

		}

		 if($package_name)
	        {
	           $this->db->where("candidates_info.package",$package_name); 
	        }

	       if(isset($where['clientid']) &&  $where['clientid'] != 0  &&  $where['clientid'] != "")	
		    {
			
			    $this->db->where("candidates_info.clientid", $where['clientid']);
			}
			
			if(isset($where['entity']) &&  $where['entity'] != "" && $where['entity'] != 0)	
			{
				$this->db->where("candidates_info.clientid", $where['entity']);
			}

			if(isset($where['package']) &&  $where['package'] != 0 && $where['package'] != "")	
			{
				$this->db->where("candidates_info.package",$where['package']);
			}
    
	    $this->db->where_in("candidates_info.overallstatus",5); 
   
		
		$this->db->order_by('candidates_info.id', 'desc');
		
		$this->db->group_by('candidates_info.id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function select_overall_insuff()
	{

         $package_name = $this->user_package;

		 $package_name1   = explode(",", $package_name);


	$this->db->select("empverres_insuff.id as insuff_id, 'Employment' as component_name,empver.iniated_date,empver.id, (select clientname from clients where clients.id = empver.clientid limit 1) as clientname,candidates_info.id as candidate_id, candidates_info.CandidateName, emp_com_ref as component_id,DATE_FORMAT(insuff_raised_date,'%d-%m-%Y') as insuff_raised_date,insff_reason,insuff_raise_remark,(select user_name from user_profile where id = empverres_insuff.created_by) as raised_by, 'employment' as controller,empver.clientid,
			(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name, (select entity_package_name from entity_package  where entity_package.id= candidates_info.package limit 1) as package_name");

		$this->db->from('empver');

		$this->db->join("candidates_info",'candidates_info.id = empver.candsid');

		$this->db->join('empverres_insuff','(empver.id = empverres_insuff.empverres_id and empverres_insuff.status = 1)');

	    if($package_name1)
	    {
	       $this->db->where_in("candidates_info.package",$package_name1); 
	    }
		
		$this->db->order_by('empverres_insuff.insuff_raised_date','desc');

		$empver  = $this->db->get()->result_array();
		

		$this->db->select("addrver_insuff.id as insuff_id, 'Address' as component_name,addrver.iniated_date,addrver.id, (select clientname from clients where clients.id = addrver.clientid limit 1) as clientname,candidates_info.id as candidate_id,candidates_info.CandidateName,add_com_ref as component_id,DATE_FORMAT(insuff_raised_date,'%d-%m-%Y') as insuff_raised_date,insff_reason,insuff_raise_remark,(select user_name from user_profile where id = addrver_insuff.created_by) as raised_by, 'address' as controller,addrver.clientid,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name, (select entity_package_name from entity_package  where entity_package.id= candidates_info.package limit 1) as package_name");

		$this->db->from('addrver');

		$this->db->join("candidates_info",'candidates_info.id = addrver.candsid');

		$this->db->join('addrver_insuff','(addrver.id = addrver_insuff.addrverid and addrver_insuff.status = 1)');

		if($package_name1)
	    {
	       $this->db->where_in("candidates_info.package",$package_name1); 
	    }
		$this->db->order_by('addrver_insuff.insuff_raised_date','desc');
		$addrver  = $this->db->get()->result_array();


		$this->db->select("education_insuff.id as insuff_id, 'Education' as component_name,education.iniated_date, education.id, (select clientname from clients where clients.id = education.clientid limit 1) as clientname,candidates_info.id as candidate_id,candidates_info.CandidateName, education_com_ref as component_id,DATE_FORMAT(insuff_raised_date,'%d-%m-%Y') as insuff_raised_date,insff_reason,insuff_raise_remark,(select user_name from user_profile where id = education_insuff.created_by) as raised_by, 'education' as controller,education.clientid,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name, (select entity_package_name from entity_package  where entity_package.id= candidates_info.package limit 1) as package_name");

		$this->db->from('education');

		$this->db->join("candidates_info",'candidates_info.id = education.candsid');

		$this->db->join('education_insuff','(education.id = education_insuff.education_id and education_insuff.status = 1)');
		
		$this->db->order_by('education_insuff.insuff_raised_date','desc');

		if($package_name1)
	    {
	       $this->db->where_in("candidates_info.package",$package_name1); 
	    }

		$education  = $this->db->get()->result_array();
		

		$this->db->select("reference_insuff.id as insuff_id, 'Reference' as component_name, reference.iniated_date,reference.id, (select clientname from clients where clients.id = reference.clientid limit 1) as clientname, candidates_info.id as candidate_id,candidates_info.CandidateName,reference_com_ref as component_id,DATE_FORMAT(insuff_raised_date,'%d-%m-%Y') as insuff_raised_date,insff_reason,insuff_raise_remark,(select user_name from user_profile where id = reference_insuff.created_by) as raised_by, 'reference_verificatiion' as controller,reference.clientid,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name, (select entity_package_name from entity_package  where entity_package.id= candidates_info.package limit 1) as package_name");

		$this->db->from('reference');

		$this->db->join("candidates_info",'candidates_info.id = reference.candsid');

		$this->db->join('reference_insuff','(reference.id = reference_insuff.reference_id and reference_insuff.status = 1)');

		if($package_name1)
	    {
	       $this->db->where_in("candidates_info.package",$package_name1); 
	    }
		
		$this->db->order_by('reference_insuff.insuff_raised_date','desc');

		$reference  = $this->db->get()->result_array();
		
		$this->db->select("courtver_insuff.id as insuff_id, 'Court' as component_name,courtver.iniated_date,courtver.id, (select clientname from clients where clients.id = courtver.clientid limit 1) as clientname,candidates_info.id as candidate_id,candidates_info.CandidateName, court_com_ref as component_id,DATE_FORMAT(insuff_raised_date,'%d-%m-%Y') as insuff_raised_date,insff_reason,insuff_raise_remark,(select user_name from user_profile where id = courtver_insuff.created_by) as raised_by, 'court_verificatiion' as controller,courtver.clientid,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name, (select entity_package_name from entity_package  where entity_package.id= candidates_info.package limit 1) as package_name");
		$this->db->from('courtver');

		$this->db->join("candidates_info",'candidates_info.id = courtver.candsid');
		$this->db->join('courtver_insuff','(courtver.id = courtver_insuff.courtver_id and courtver_insuff.status = 1)');

		if($package_name1)
	    {
	       $this->db->where_in("candidates_info.package",$package_name1); 
	    }
		$this->db->order_by('courtver_insuff.insuff_raised_date','desc');
		$court  = $this->db->get()->result_array();


		$this->db->select("glodbver_insuff.id as insuff_id, 'Global DB' as component_name,glodbver.iniated_date,glodbver.id, (select clientname from clients where clients.id = glodbver.clientid limit 1) as clientname, candidates_info.id as candidate_id,candidates_info.CandidateName, global_com_ref as component_id,DATE_FORMAT(insuff_raised_date,'%d-%m-%Y') as insuff_raised_date,insff_reason,insuff_raise_remark,(select user_name from user_profile where id = glodbver_insuff.created_by) as raised_by, 'global_database' as controller,glodbver.clientid,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name, (select entity_package_name from entity_package  where entity_package.id= candidates_info.package limit 1) as package_name");
		$this->db->from('glodbver');
		$this->db->join("candidates_info",'candidates_info.id = glodbver.candsid');
		$this->db->join('glodbver_insuff','(glodbver.id = glodbver_insuff.glodbver_id and glodbver_insuff.status = 1)');
        
	    
		if($package_name1)
	    {
	       $this->db->where_in("candidates_info.package",$package_name1); 
	    }
		$this->db->order_by('glodbver_insuff.insuff_raised_date','desc');
		$glodbver  = $this->db->get()->result_array();
		
		$this->db->select("drug_narcotis_insuff.id as insuff_id, 'Drug' as component_name,drug_narcotis.iniated_date, drug_narcotis.id,  (select clientname from clients where clients.id = drug_narcotis.clientid limit 1) as clientname,candidates_info.id as candidate_id,candidates_info.CandidateName, drug_com_ref as component_id,DATE_FORMAT(insuff_raised_date,'%d-%m-%Y') as insuff_raised_date,insff_reason,insuff_raise_remark,(select user_name from user_profile where id = drug_narcotis_insuff.created_by) as raised_by, 'drugs_narcotics' as controller,drug_narcotis.clientid,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name, (select entity_package_name from entity_package  where entity_package.id= candidates_info.package limit 1) as package_name");
		$this->db->from('drug_narcotis');
		$this->db->join("candidates_info",'candidates_info.id = drug_narcotis.candsid');
		$this->db->join('drug_narcotis_insuff','(drug_narcotis.id = drug_narcotis_insuff.drug_narcotis_id and drug_narcotis_insuff.status = 1)');

		if($package_name1)
	    {
	       $this->db->where_in("candidates_info.package",$package_name1); 
	    }
		$this->db->order_by('drug_narcotis_insuff.insuff_raised_date','desc');
		$drug_narcotis  = $this->db->get()->result_array();

		$this->db->select("pcc_insuff.id as insuff_id, 'PCC' as component_name,pcc.iniated_date,pcc.id, (select clientname from clients where clients.id = pcc.clientid limit 1) as clientname, candidates_info.id as candidate_id,candidates_info.CandidateName,pcc_com_ref as component_id,DATE_FORMAT(insuff_raised_date,'%d-%m-%Y') as insuff_raised_date,insff_reason,insuff_raise_remark,(select user_name from user_profile where id = pcc_insuff.created_by) as raised_by, 'pcc' as controller,pcc.clientid,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name, (select entity_package_name from entity_package  where entity_package.id= candidates_info.package limit 1) as package_name");
		$this->db->from('pcc');
		$this->db->join("candidates_info",'candidates_info.id = pcc.candsid');
		$this->db->join('pcc_insuff','(pcc.id = pcc_insuff.pcc_id and pcc_insuff.status = 1)');
		if($package_name1)
	    {
	       $this->db->where_in("candidates_info.package",$package_name1); 
	    }
		$this->db->order_by('pcc_insuff.insuff_raised_date','desc');
		$pcc  = $this->db->get()->result_array();

		$this->db->select("identity_insuff.id as insuff_id, 'Identity' as component_name,identity.iniated_date,identity.id, (select clientname from clients where clients.id = identity.clientid limit 1) as clientname, candidates_info.id as candidate_id,candidates_info.CandidateName, identity_com_ref as component_id,DATE_FORMAT(insuff_raised_date,'%d-%m-%Y') as insuff_raised_date,insff_reason,insuff_raise_remark,(select user_name from user_profile where id = identity_insuff.created_by) as raised_by, 'identity' as controller,identity.clientid,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name, (select entity_package_name from entity_package  where entity_package.id= candidates_info.package limit 1) as package_name");

		$this->db->from('identity');
	    $this->db->join("candidates_info",'candidates_info.id = identity.candsid');
		$this->db->join('identity_insuff','(identity.id = identity_insuff.identity_id and identity_insuff.status = 1)');
		if($package_name1)
	    {
	       $this->db->where_in("candidates_info.package",$package_name1); 
	    }
		$this->db->order_by('identity_insuff.insuff_raised_date','desc');
		$identity  = $this->db->get()->result_array();

		$this->db->select("credit_report_insuff.id as insuff_id, 'credit Report' as component_name,credit_report.id,credit_report.iniated_date, (select clientname from clients where clients.id = credit_report.clientid limit 1) as clientname,candidates_info.id as candidate_id,candidates_info.CandidateName, credit_report_com_ref as component_id,DATE_FORMAT(insuff_raised_date,'%d-%m-%Y') as insuff_raised_date,insff_reason,insuff_raise_remark,(select user_name from user_profile where id = credit_report_insuff.created_by) as raised_by, 'Credit_report' as controller,credit_report.clientid,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name, (select entity_package_name from entity_package  where entity_package.id= candidates_info.package limit 1) as package_name");
		$this->db->from('credit_report');

		$this->db->join("candidates_info",'candidates_info.id = credit_report.candsid');
		$this->db->join('credit_report_insuff','(credit_report.id = credit_report_insuff.credit_report_id and credit_report_insuff.status = 1)');
		$this->db->order_by('credit_report_insuff.insuff_raised_date','desc');
		if($package_name1)
	    {
	       $this->db->where_in("candidates_info.package",$package_name1); 
	    }
		$credit_report  = $this->db->get()->result_array();

		return array_merge($empver,$addrver,$education,$reference,$court,$glodbver,$drug_narcotis,$pcc,$identity,$credit_report);
	}

	public function get_candidates_info_info_report($where_arry = array())
	{
		$this->db->select("candidates_info.CandidateName,DATE_FORMAT(candidates_info.caserecddate,'%d-%b-%Y') as caserecddate, DATE_FORMAT(candidates_info.DateofBirth,'%d-%b-%Y') as DateofBirth, candidates_info.ClientRefNumber,candidates_info.gender, candidates_info.overallstatus,DATE_FORMAT(candidates_info.created_on,'%d-%b-%Y') as candidates_info_created,candidates_info.cmp_ref_no,candidates_info.ClientRefNumber,candidates_info.DesignationJoinedas as level,candidates_info.ClientRefNumber,clients.clientname,candidates_info.clientid,clients.comp_logo,clients_details.candidate_report_type");

		$this->db->from('candidates_info');

		$this->db->join("clients",'clients.id = candidates_info.clientid');
		$this->db->join("clients_details",'clients_details.tbl_clients_id = candidates_info.clientid');

		if(!empty($where_arry))
		{
			$this->db->where($where_arry);
		}

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$result_array = $result->result_array();

	    if(count($result_array)==1) // ensure only one record has been previously inserted
	    {
	        $result_array  = $result_array[0];
	    }
        
        return $result_array;
	}

	public function education_info_report($empver_aary = array())
	{

		$this->db->select("education.*,GROUP_CONCAT(education_files.file_name) as file_names,university_master.universityname,qualification_master.qualification as qualification_degree,ev1.verfstatus,ev1.res_mode_of_verification,ev1.remarks,ev1.verified_by,ev1.verifier_designation,ev1.	verifier_contact_details,ev2.res_mode_of_verification as em");

		$this->db->from('education');

		$this->db->join("education_result as ev1",'(ev1.education_id = education.id)');

		$this->db->join("education_result as ev2",'(ev2.education_id = education.id and ev1.id < ev2.id)','left');

		$this->db->join("education_files",'(education_files.education_id = ev1.id AND education_files.status = 1)','left');

		$this->db->join("university_master",'university_master.id = education.university_board');

		$this->db->join("qualification_master",'qualification_master.id = education.qualification');

		if(!empty($empver_aary))
		{
			$this->db->where($empver_aary);
		}

		$this->db->where('ev2.res_mode_of_verification is null');

		$this->db->group_by('education.id');

		$this->db->order_by('id', 'desc');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function employment_info_report($empver_aary = array())
	{
		$this->db->select('empver.*, company_database.coname,GROUP_CONCAT(empverres_files.file_name) as file_names,ev1.verfstatus, CONCAT( DATE_FORMAT(ev1.employed_from,"%D-%b-%y") , " to " , DATE_FORMAT(ev1.employed_to,"%D-%b-%y")) as employment_period, ev1.reportingmanager as ver_reportingmanager, ev1.res_reasonforleaving as ver_reasonforleaving,ev1.verfname,ev1.verfdesgn,ev1.mcaregn,ev1.domainname,ev1.justdialwebcheck,ev1.fmlyowned,ev1.emp_designation,ev1.integrity_disciplinary_issue,ev1.exitformalities,ev1.modeofverification,ev1.remarks,ev1.res_remuneration as ver_remuneration,ev1.empverid as ver_empid,CONCAT( DATE_FORMAT(empver.empfrom,"%D-%b-%y") , " to ",DATE_FORMAT(empver.empto,"%D-%b-%y")) as enter_employment_period,ev1.emp_designation as ver_emp_designation');

		$this->db->from('empver');

		$this->db->join("company_database",'company_database.id = empver.nameofthecompany');

		$this->db->join("empverres as ev1",'ev1.empverid = empver.id','left');

		$this->db->join("empverres as ev2",'(ev2.empverid = empver.id and ev1.id < ev2.id)','left');

		$this->db->join("empverres_files",'(empverres_files.empver_id = ev1.id AND empverres_files.status = 1) AND empverres_files.type = 1','left');

		if(!empty($empver_aary))
		{
			$this->db->where($empver_aary);
		}

		$this->db->where('ev2.verfstatus is null');
		
		$this->db->group_by('empver.id');

		$this->db->order_by('id', 'desc');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function address_info_report($address_aary = array())
	{
		$this->db->select("CONCAT(addrver.address,' ',addrver.city, ',', LOWER(addrver.state),', ', addrver.pincode ) as full_aadress ,GROUP_CONCAT(addrver_files.file_name) as file_names, CONCAT( DATE_FORMAT(add1.res_stay_from,'%D-%b-%y') , ' to ' , DATE_FORMAT(add1.res_stay_to,'%D-%b-%y')) as period_of_stay,add1.remarks,add1.verfstatus,add1.verified_by,add1.mode_of_verification,add1.resident_status,addrver.clientid,add1.res_stay_from");

		$this->db->from('addrver');

		$this->db->join("addrverres as add1",'add1.addrverid = addrver.id','left');

		$this->db->join("addrverres as add2",'(add2.addrverid = addrver.id and add1.id < add2.id)','left');

		$this->db->join("addrver_files",'(addrver_files.addrver_id = add1.id AND addrver_files.status = 1)','left');

		if(!empty($address_aary))
		{
			$this->db->where($address_aary);
		}

		$this->db->where('add2.verfstatus is null');

		$this->db->group_by('addrver.id');

		$this->db->order_by('addrver.id', 'desc');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function status_count($clientid)
	{
		$this->db->select('overallstatus,COUNT(overallstatus) as total');

		$this->db->from($this->tableName);

		if($clientid)
		{
			$this->db->where('clientid',$clientid);
		}

		$this->db->group_by('overallstatus');

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		$results = $result->result_array();

		$return = array();

		$return['NoRecordFound'] = '0'; 
		
		if(!empty($results))
		{
			foreach ($results as $key => $value) {
				$return[str_replace('/','',str_replace(' ','',$value['overallstatus']))] = $value['total'];
			}
			$return['total'] = array_sum($return);
		}

		return $return;
	}

	public function get_cases_by_date($date,$client_id)
	{
		$query = $this->db->query('SELECT count(id) as total_count FROM candidates_info where clientid = "'.$client_id.'" AND  DATE_FORMAT(overallclosuredate ,"%Y-%m-%d") >= "'.$date.'" AND DATE_FORMAT(overallclosuredate ,"%Y-%m-%d") <= "'.$date.'" AND overallstatus in ("clear","Stop/Check", "Discrepancy") ');

		$results = $query->row_array();
		
		return (!empty($results) ? $results['total_count'] : 0);
	}

	public function get_all_cases_by_date($date)
	{
		$query = $this->db->query('SELECT count(id) as total_count FROM candidates_info where DATE_FORMAT(overallclosuredate ,"%Y-%m-%d") >= "'.$date.'" AND DATE_FORMAT(overallclosuredate ,"%Y-%m-%d") <= "'.$date.'" AND overallstatus in ("clear","Stop/Check", "Discrepancy") ');

		$results = $query->row_array();
		
		return (!empty($results) ? $results['total_count'] : 0);
	}

	public function get_cases_by_months($client_id)
	{
		$query = $this->db->query('SELECT COUNT(id) as cases_count , MONTH(updated) as months FROM candidates_info where clientid = "'.$client_id.'" AND YEAR(updated) = YEAR(CURDATE()) AND overallstatus = "clear" GROUP BY DATE_FORMAT(updated,"%m") ');

		return  $query->result_array();
	}
	
	public function all_get_cases_by_months()
	{
		$query = $this->db->query('SELECT COUNT(id) as cases_count , MONTH(updated) as months FROM candidates_info where YEAR(updated) = YEAR(CURDATE()) AND overallstatus = "clear" GROUP BY DATE_FORMAT(updated,"%m") ');

		return  $query->result_array();
	}

	public function get_tat_status($where_array)
	{
		$this->db->select('datediff(updated,created) as diff_days');

		$this->db->from('candidates_info');

		$this->db->where($where_array);

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_tat_days($clientid)
	{
		$this->db->select('*')->from('clients');
		
		$this->db->where('id',$clientid);

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$results = $result->result_array();

		if(!empty($results))
		{
			$results = $results[0];
		}
		return $results;
	}

	public function pcc_info_report($address_aary = array())
	{
		$this->db->select("p1.verfstatus,p1.police_station_visit_date,p1.name_designation_police,p1.contact_number_police,p1.mode_of_verification,p1.remarks as remarks,candidates_info.clientid");

		$this->db->from('candidates_info');

		$this->db->join("pcc_result as p1",'p1.candsid = candidates_info.id');

		$this->db->join("pcc_result as p2",'(p2.candsid = candidates_info.id and p1.id < p2.id)','left');

		if(!empty($address_aary))
		{
			$this->db->where($address_aary);
		}

		$this->db->where('p2.verfstatus is null');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function courtinfo_report($where_arry = array())
	{
		$this->db->select("courtver.*,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.CandidateName,candidates_info.DateofBirth,candidates_info.NameofCandidateFather,candidates_info.MothersName,candidates_info.CandidatesContactNumber,GROUP_CONCAT(courtver_files.file_name) as file_names");

		$this->db->from('courtver');

		$this->db->join("candidates_info",'candidates_info.id = courtver.candsid');

		$this->db->join("courtver_files",'(courtver_files.courtver_id = courtver.id AND courtver_files.status = 1)','left');
		
		if($where_arry)
		{
			$this->db->where($where_arry);
		}

		$this->db->group_by('courtver.id');
		
		$this->db->order_by('courtver.id', 'desc');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_addres_ver_status($clientid)
	{
		$this->db->select("candidates_info.overallstatus,ev1.verfstatus,addrver_insuff.insuff_raised_date,addrver_insuff.insuff_raise_remark
			,addrver_insuff.insuff_clear_date,ev1.closuredate,addrver_insuff.insuff_remarks,addrver.address as address,status.filter_status");

		$this->db->from('candidates_info');

		$this->db->join("addrver",'candidates_info.id = addrver.candsid');

		$this->db->join("clients",'(clients.id = candidates_info.clientid)');

		$this->db->join("addrverres as ev1",'ev1.addrverid = addrver.id','left');

		$this->db->join("addrverres as ev2",'(ev2.addrverid = addrver.id and ev1.id < ev2.id)','left');

		$this->db->join("addrver_insuff",'addrver_insuff.addrverid = addrver.id','left');

	    $this->db->join("status",'status.id = ev1.verfstatus');

		$this->db->where('ev2.verfstatus is null');
		
		if($clientid)
		{
			$this->db->where($clientid);
		}
		
		$this->db->order_by('addrver.id', 'desc');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_education_ver_status($clientid)
	{
		$this->db->select("candidates_info.overallstatus,ev1.verfstatus,status.filter_status");

		$this->db->from('education');

		$this->db->join("candidates_info",'candidates_info.id = education.candsid');

		$this->db->join("clients",'(clients.id = candidates_info.clientid)');

		$this->db->join("education_result as ev1",'(ev1.education_id = education.id)','left');

		$this->db->join("education_result as ev2",'(ev2.education_id = education.id and ev1.id < ev2.id)','left');

		 $this->db->join("status",'status.id = ev1.verfstatus');

		$this->db->where('ev2.verfstatus is null');
		
		if($clientid)
		{
			$this->db->where($clientid);
		}

		$this->db->order_by('candidates_info.id', 'desc');
		
		$result = $this->db->get();
	
		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_employment_ver_status($clientid)
	{
		$this->db->select("candidates_info.overallstatus,ev1.verfstatus,empverres_insuff.insuff_raised_date,empverres_insuff.insuff_raise_remark,ev1.closuredate,ev1.integrity_disciplinary_issue,ev1.verifiers_email_id,ev1.remarks as empres_remarks,company_database.coname,status.filter_status");

		$this->db->from('empver');

		$this->db->join("candidates_info",'candidates_info.id = empver.candsid');

		$this->db->join("clients",'(clients.id = candidates_info.clientid)');

		$this->db->join('company_database', 'company_database.id = empver.nameofthecompany');
		
		$this->db->join("empverres as ev1",'ev1.empverid = empver.id','left');

		$this->db->join("empverres as ev2",'(ev2.empverid = empver.id and ev1.id < ev2.id)','left');

		$this->db->join("empverres_insuff",'empverres_insuff.empverres_id = empver.id');

		 $this->db->join("status",'status.id = ev1.verfstatus');

		$this->db->where('ev2.verfstatus is null');

		if($clientid)
		{
			$this->db->where($clientid);
		}
				
		$result = $this->db->get();
		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_court_ver_status($where_arry = array())
	{
		$this->db->select("candidates_info.overallstatus,ev1.verfstatus,status.filter_status");

		$this->db->from('candidates_info');

		$this->db->join('clients','clients.id = candidates_info.clientid');

		$this->db->join("courtver",'candidates_info.id = courtver.candsid','left');

		$this->db->join("courtver_result as ev1",'ev1.courtver_id = courtver.id','left');


		 $this->db->join("status",'status.id = ev1.verfstatus');



		if($where_arry)
		{
			$this->db->where($where_arry);
		}

		$this->db->order_by('courtver.id', 'desc');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_pcc_ver_status($where_arry = array())
	{
		$this->db->select("candidates_info.overallstatus,pcc_result.verfstatus,status.filter_status");

		$this->db->from('candidates_info');

		$this->db->join('clients','clients.id = candidates_info.clientid');

		$this->db->join("pcc_result as pcc_result",'pcc_result.candsid = candidates_info.id','left');

		$this->db->join("pcc_result as p2",'(p2.candsid = candidates_info.id and pcc_result.id < p2.id)','left');

		$this->db->where('p2.verfstatus is null');
        
         $this->db->join("status",'status.id = pcc_result.verfstatus');


		if($where_arry)
		{
			$this->db->where($where_arry);
		}

		$this->db->order_by('pcc_result.id', 'desc');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function references_report($ref_aary = array())
	{
		$this->db->select("candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.CandidateName,candidates_info.caserecddate,reference.	name_of_reference,reference.designation,reference.contact_no,ref1.created_on as last_modified,reference.designation,reference.contact_no,ref1.remarks,ref1.verfstatus as refverfstatus,reference.id,reference.clientid,reference.	has_assigned_on,ref1.id as refveri_id,GROUP_CONCAT(reference_files.file_name) as file_names,ref1.closuredate,candidates_info.id as candidates_infoid");

		$this->db->from('candidates_info');

		$this->db->join("clients",'(clients.id = candidates_info.clientid)');

		$this->db->join('reference','reference.candsid = candidates_info.id');

		$this->db->join("reference_result as ref1",'ref1.reference_id = reference.id','left');

		$this->db->join("reference_result as ref2",'(ref2.reference_id = reference.id and ref1.id < ref2.id)','left');

		$this->db->join("reference_files",'(reference_files.reference_id = ref1.id AND reference_files.status = 1)','left');

		$this->db->where('ref2.verfstatus is null');

		if(!empty($ref_aary))
		{
			$this->db->where($ref_aary);
		}

		$this->db->group_by('reference.id');

		$this->db->order_by('reference.id', 'desc');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_global_db_ver_status($where_arry = array())
	{
		$this->db->select("candidates_info.overallstatus,glob1.verfstatus,status.filter_status");

		$this->db->from('candidates_info');

		$this->db->join('clients','(clients.id = candidates_info.clientid)');

		$this->db->join("glodbver",'candidates_info.id = glodbver.candsid','left');

		$this->db->join("glodbver_result as glob1",'glob1.glodbver_id = glodbver.id','left');

		 $this->db->join("status",'status.id = glob1.verfstatus');

		if($where_arry)
		{
			$this->db->where($where_arry);
		}

		$this->db->order_by('glodbver.id', 'desc');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}
		
	public function get_reference_ver_status($where_arry = array())
	{
		$this->db->select("ref1.*,reference.clientid,reference.clientid as client_id,reference.id,ref1.id as ref_ver_id,ref1.verfstatus,status.filter_status");

		$this->db->from('reference');

		$this->db->join('reference_result as ref1','ref1.reference_id = reference.id');

		$this->db->join("reference_result as ref2",'(ref2.reference_id = reference.id and ref1.id < ref2.id)','left');
		
		$this->db->where('ref2.verfstatus is null');

		 $this->db->join("status",'status.id = ref1.verfstatus');

		if($where_arry)
		{
			$this->db->where($where_arry);
		}
		
		$this->db->group_by('reference.id');

		$this->db->order_by('ref1.id', 'desc');
		
		$result = $this->db->get();
		
		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function sales_all_cand_with_search($client_id,$where,$columns)
	{
		$this->db->select("candidates_info.id,candidates_info.clientid,candidates_info.CandidateName,candidates_info.caserecddate,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.overallstatus,candidates_info.remarks,clients.clientname,clients.addrver,clients.courtver,clients.crimver,clients.eduver,clients.empver,clients.narcver,clients.refver,clients.globdbver,candidates_info.overallclosuredate");

		$this->db->from('candidates_info');
        
        $this->db->join("clients","(clients.id = candidates_info.clientid and clients.sales_manager = $client_id)");
		
		if($where['status'] != "")
		{			
			$status_array = array('stop-check' => 'Stop/Check','unable-to-verified'=>'Unable to Verify','completed' =>'Clear');

			if(array_key_exists($where['status'] ,$status_array))
			{
				$where['status'] = $status_array[$where['status']];
			}

			$this->db->where("candidates_info.overallstatus",$where['status']);

			if($where['status'] == 'discrepancy')
			{
				$this->db->or_where("candidates_info.overallstatus",'No Record Found');
			}
			
		}

		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like($this->tableName.'.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);

			$this->db->or_like($this->tableName.'.cmp_ref_no', $where['search']['value']);

			$this->db->or_like($this->tableName.'.CandidateName', $where['search']['value']);

			$this->db->or_like($this->tableName.'.CandidatesContactNumber', $where['search']['value']);

			$this->db->or_like($this->tableName.'.EmployeeCode', $where['search']['value']);

			$this->db->or_like($this->tableName.'.overallstatus', $where['search']['value']);

		}
		
		$this->db->limit($where['length'],$where['start']);

		$this->db->order_by('candidates_info.id', 'desc');
		
		$this->db->group_by('candidates_info.id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function sales_all_cand_with_search_count($client_id,$where,$columns)
	{
		$this->db->select("candidates_info.id,candidates_info.clientid,candidates_info.CandidateName,candidates_info.caserecddate,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.overallstatus,candidates_info.remarks,clients.clientname,clients.addrver,clients.courtver,clients.crimver,clients.eduver,clients.empver,clients.narcver,clients.refver,candidates_info.overallclosuredate");

		$this->db->from('candidates_info');
  		
  		$this->db->join("clients","(clients.id = candidates_info.clientid and clients.sales_manager = $client_id)");

		if($where['status'] != "")
		{
			$status_array = array('stop-check' => 'Stop/Check','unable-to-verified'=>'Unable to Verify','completed' =>'Clear');

			if(array_key_exists($where['status'] ,$status_array))
			{
				$where['status'] = $status_array[$where['status']];
			}

			$this->db->where("candidates_info.overallstatus",$where['status']);

			if($where['status'] == 'discrepancy')
			{
				$this->db->or_where("candidates_info.overallstatus",'No Record Found');
			}
		}
		
		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like($this->tableName.'.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);

			$this->db->or_like($this->tableName.'.cmp_ref_no', $where['search']['value']);

			$this->db->or_like($this->tableName.'.CandidateName', $where['search']['value']);

			$this->db->or_like($this->tableName.'.CandidatesContactNumber', $where['search']['value']);

			$this->db->or_like($this->tableName.'.EmployeeCode', $where['search']['value']);

			$this->db->or_like($this->tableName.'.overallstatus', $where['search']['value']);

		}
		
		$this->db->order_by('candidates_info.id', 'desc');
		
		$this->db->group_by('candidates_info.id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function candidates_infoid_by_dates($client_id,$where)
	{
		$this->db->select("candidates_info.id,LOWER(candidates_info.CandidateName),candidates_info.caserecddate,candidates_info.EmployeeCode,clients.addrver,clients.empver,clients.clientname");

		$this->db->from('candidates_info');
  		
  		$this->db->join("clients","(clients.id = candidates_info.clientid and clients.id = $client_id)");

  		$this->db->where('candidates_info.overallstatus','Clear');

  		$this->db->where('caserecddate >=',convert_display_to_db_date('01-'.$where['month_of_reprots']));

  		$this->db->where('caserecddate <=',convert_display_to_db_date('30-'.$where['month_of_reprots']));

  		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();

	}

	public function excel_candidates_infoid_by_dates($client_id,$where)
	{
		$this->db->select("candidates_info.id,candidates_info.CandidateName,candidates_info.caserecddate,candidates_info.ClientRefNumber AS EmployeeCode,clients.addrver,clients.empver,candidates_info.build_date,candidates_info.remarks as candidates_info_remarks");

		$this->db->from('candidates_info');
  		
  		$this->db->join("clients","(clients.id = candidates_info.clientid and candidates_info.clientid = $client_id)");
		

  		$this->db->where('caserecddate >=',convert_display_to_db_date($where['from_date']));

  		$this->db->where('caserecddate <=',convert_display_to_db_date($where['to_date']));

  		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	/*public function get_excel($client_id,$where)
	{
	
		$this->db->select("candidates_info.id,candidates_info.CandidateName,candidates_info.caserecddate,candidates_info.ClientRefNumber AS EmployeeCode,clients.addrver,clients.empver,candidates_info.build_date,candidates_info.remarks as candidates_info_remarks");

		$this->db->from('candidates_info');
  		
  		$this->db->join("clients","(clients.id = candidates_info.clientid and candidates_info.clientid = $client_id)");
		//$this->db->join("addrverres","(clients.id = candidates_info.clientid and addrverres.clientid = $client_id)");
		//$this->db->join("empverres","(clients.id = candidates_info.clientid and empverres.clientid = $client_id)");
$this->db->join("addrverres","(clients.id = candidates_info.clientid and addrverres.clientid = $client_id)");
		//$this->db->join("empverres","(clients.id = candidates_info.clientid and empverres.clientid = $client_id)");

  		$this->db->where('caserecddate >=',convert_display_to_db_date($where['from_date']));

  		$this->db->where('caserecddate <=',convert_display_to_db_date($where['to_date']));
		   
         // print_r($this->db->get());
        $result = $this->db->get();
      print_r($this->db->last_query());
		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}
  */
	public function report_requested_save($arrdata,$arrwhere = array())
  	{
	    if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update('report_requested', $arrdata);

			record_db_error($this->db->last_query());

			return $result;
	    }
	    else
	    {
			$this->db->insert('report_requested', $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	    }
 	}

 	public function report_requested_select($arrdata)
  	{
	    $this->db->select('id','folder_name')->from('report_requested')->where($arrdata);

	    $result = $this->db->get();

		record_db_error($this->db->last_query());
		
		$result =  $result->result_array();

		if(!empty($result))
		{
			return $result[0];
		}
		else
		{
			return $result;
		}
 	}

 	public function get_address_result($candidate_id)
 	{
 		$this->db->select("addrver_insuff.id as insuff_id, 'Address' as component_name,addrver.iniated_date,addrver.id, (select clientname from clients where clients.id = addrver.clientid limit 1) as clientname,candidates_info.id as candidate_id,candidates_info.CandidateName,add_com_ref as component_id,DATE_FORMAT(insuff_raised_date,'%d-%m-%Y') as insuff_raised_date,insff_reason,insuff_raise_remark,(select user_name from user_profile where id = addrver_insuff.created_by) as raised_by, 'address' as controller,addrver.clientid,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name, (select entity_package_name from entity_package  where entity_package.id= candidates_info.package limit 1) as package_name");

		$this->db->from('addrver');

		$this->db->join("candidates_info",'candidates_info.id = addrver.candsid');

		$this->db->join('addrver_insuff','(addrver.id = addrver_insuff.addrverid and addrver_insuff.status = 1)');

		
	    $this->db->where("candidates_info.id",$candidate_id); 
	    
		$this->db->order_by('addrver_insuff.insuff_raised_date','desc');
		
		$addrver  = $this->db->get()->result_array();

		return $addrver;
 	}


 	public function get_court_result($candidate_id)
 	{
 		
		$this->db->select("courtver_insuff.id as insuff_id, 'Court' as component_name,courtver.iniated_date,courtver.id, (select clientname from clients where clients.id = courtver.clientid limit 1) as clientname,candidates_info.id as candidate_id,candidates_info.CandidateName, court_com_ref as component_id,DATE_FORMAT(insuff_raised_date,'%d-%m-%Y') as insuff_raised_date,insff_reason,insuff_raise_remark,(select user_name from user_profile where id = courtver_insuff.created_by) as raised_by, 'court_verificatiion' as controller,courtver.clientid,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name, (select entity_package_name from entity_package  where entity_package.id= candidates_info.package limit 1) as package_name");
		$this->db->from('courtver');

		$this->db->join("candidates_info",'candidates_info.id = courtver.candsid');

		$this->db->join('courtver_insuff','(courtver.id = courtver_insuff.courtver_id and courtver_insuff.status = 1)');

		$this->db->where("candidates_info.id",$candidate_id);

		$this->db->order_by('courtver_insuff.insuff_raised_date','desc');

		$court  = $this->db->get()->result_array();
        
        return $court;

 	}

 	public function get_employment_result($candidate_id)
 	{

        $this->db->select("empverres_insuff.id as insuff_id, 'Employment' as component_name,empver.iniated_date,empver.id, (select clientname from clients where clients.id = empver.clientid limit 1) as clientname,candidates_info.id as candidate_id, candidates_info.CandidateName, emp_com_ref as component_id,DATE_FORMAT(insuff_raised_date,'%d-%m-%Y') as insuff_raised_date,insff_reason,insuff_raise_remark,(select user_name from user_profile where id = empverres_insuff.created_by) as raised_by, 'employment' as controller,empver.clientid,
			(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name, (select entity_package_name from entity_package  where entity_package.id= candidates_info.package limit 1) as package_name");

		$this->db->from('empver');

		$this->db->join("candidates_info",'candidates_info.id = empver.candsid');

		$this->db->join('empverres_insuff','(empver.id = empverres_insuff.empverres_id and empverres_insuff.status = 1)');

	    $this->db->where("candidates_info.id",$candidate_id);

		
		$this->db->order_by('empverres_insuff.insuff_raised_date','desc');

		$empver  = $this->db->get()->result_array();
		
        return $empver;
 	}

 	public function get_education_result($candidate_id)
 	{

       $this->db->select("education_insuff.id as insuff_id, 'Education' as component_name,education.iniated_date, education.id, (select clientname from clients where clients.id = education.clientid limit 1) as clientname,candidates_info.id as candidate_id,candidates_info.CandidateName, education_com_ref as component_id,DATE_FORMAT(insuff_raised_date,'%d-%m-%Y') as insuff_raised_date,insff_reason,insuff_raise_remark,(select user_name from user_profile where id = education_insuff.created_by) as raised_by, 'education' as controller,education.clientid,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name, (select entity_package_name from entity_package  where entity_package.id= candidates_info.package limit 1) as package_name");

		$this->db->from('education');

		$this->db->join("candidates_info",'candidates_info.id = education.candsid');

		$this->db->join('education_insuff','(education.id = education_insuff.education_id and education_insuff.status = 1)');
		
		$this->db->order_by('education_insuff.insuff_raised_date','desc');

		$this->db->where("candidates_info.id",$candidate_id);

		$education  = $this->db->get()->result_array();

		return $education;
 	}

 	public function get_reference_result($candidate_id)
 	{
		$this->db->select("reference_insuff.id as insuff_id, 'Reference' as component_name, reference.iniated_date,reference.id, (select clientname from clients where clients.id = reference.clientid limit 1) as clientname, candidates_info.id as candidate_id,candidates_info.CandidateName,reference_com_ref as component_id,DATE_FORMAT(insuff_raised_date,'%d-%m-%Y') as insuff_raised_date,insff_reason,insuff_raise_remark,(select user_name from user_profile where id = reference_insuff.created_by) as raised_by, 'reference_verificatiion' as controller,reference.clientid,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name, (select entity_package_name from entity_package  where entity_package.id= candidates_info.package limit 1) as package_name");

		$this->db->from('reference');

		$this->db->join("candidates_info",'candidates_info.id = reference.candsid');

		$this->db->join('reference_insuff','(reference.id = reference_insuff.reference_id and reference_insuff.status = 1)');

		$this->db->where("candidates_info.id",$candidate_id);

		
		$this->db->order_by('reference_insuff.insuff_raised_date','desc');

		$reference  = $this->db->get()->result_array();

		return $reference;
 	}

    public function get_global_result($candidate_id)
 	{
		$this->db->select("glodbver_insuff.id as insuff_id, 'Global DB' as component_name,glodbver.iniated_date,glodbver.id, (select clientname from clients where clients.id = glodbver.clientid limit 1) as clientname, candidates_info.id as candidate_id,candidates_info.CandidateName, global_com_ref as component_id,DATE_FORMAT(insuff_raised_date,'%d-%m-%Y') as insuff_raised_date,insff_reason,insuff_raise_remark,(select user_name from user_profile where id = glodbver_insuff.created_by) as raised_by, 'global_database' as controller,glodbver.clientid,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name, (select entity_package_name from entity_package  where entity_package.id= candidates_info.package limit 1) as package_name");
		$this->db->from('glodbver');

		$this->db->join("candidates_info",'candidates_info.id = glodbver.candsid');

		$this->db->join('glodbver_insuff','(glodbver.id = glodbver_insuff.glodbver_id and glodbver_insuff.status = 1)');

		$this->db->where("candidates_info.id",$candidate_id);

		$this->db->order_by('glodbver_insuff.insuff_raised_date','desc');

		$glodbver  = $this->db->get()->result_array();

		return $glodbver;
 	}

 	public function get_pcc_result($candidate_id)
 	{

		$this->db->select("pcc_insuff.id as insuff_id, 'PCC' as component_name,pcc.iniated_date,pcc.id, (select clientname from clients where clients.id = pcc.clientid limit 1) as clientname, candidates_info.id as candidate_id,candidates_info.CandidateName,pcc_com_ref as component_id,DATE_FORMAT(insuff_raised_date,'%d-%m-%Y') as insuff_raised_date,insff_reason,insuff_raise_remark,(select user_name from user_profile where id = pcc_insuff.created_by) as raised_by, 'pcc' as controller,pcc.clientid,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name, (select entity_package_name from entity_package  where entity_package.id= candidates_info.package limit 1) as package_name");

		$this->db->from('pcc');

		$this->db->join("candidates_info",'candidates_info.id = pcc.candsid');

		$this->db->join('pcc_insuff','(pcc.id = pcc_insuff.pcc_id and pcc_insuff.status = 1)');

		$this->db->where("candidates_info.id",$candidate_id);

		$this->db->order_by('pcc_insuff.insuff_raised_date','desc');

		$pcc  = $this->db->get()->result_array();

		return $pcc;

 	}

 	public function get_identity_result($candidate_id)
 	{

		$this->db->select("identity_insuff.id as insuff_id, 'Identity' as component_name,identity.iniated_date,identity.id, (select clientname from clients where clients.id = identity.clientid limit 1) as clientname, candidates_info.id as candidate_id,candidates_info.CandidateName, identity_com_ref as component_id,DATE_FORMAT(insuff_raised_date,'%d-%m-%Y') as insuff_raised_date,insff_reason,insuff_raise_remark,(select user_name from user_profile where id = identity_insuff.created_by) as raised_by, 'identity' as controller,identity.clientid,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name, (select entity_package_name from entity_package  where entity_package.id= candidates_info.package limit 1) as package_name");

		$this->db->from('identity');

	    $this->db->join("candidates_info",'candidates_info.id = identity.candsid');

		$this->db->join('identity_insuff','(identity.id = identity_insuff.identity_id and identity_insuff.status = 1)');
		$this->db->where("candidates_info.id",$candidate_id);

		$this->db->order_by('identity_insuff.insuff_raised_date','desc');

		$identity  = $this->db->get()->result_array();

		return $identity;
 	}


 	public function get_credit_report_result($candidate_id)
 	{
		$this->db->select("reference_insuff.id as insuff_id, 'Reference' as component_name, reference.iniated_date,reference.id, (select clientname from clients where clients.id = reference.clientid limit 1) as clientname, candidates_info.id as candidate_id,candidates_info.CandidateName,reference_com_ref as component_id,DATE_FORMAT(insuff_raised_date,'%d-%m-%Y') as insuff_raised_date,insff_reason,insuff_raise_remark,(select user_name from user_profile where id = reference_insuff.created_by) as raised_by, 'reference_verificatiion' as controller,reference.clientid,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name, (select entity_package_name from entity_package  where entity_package.id= candidates_info.package limit 1) as package_name");

		$this->db->from('reference');

		$this->db->join("candidates_info",'candidates_info.id = reference.candsid');

		$this->db->join('reference_insuff','(reference.id = reference_insuff.reference_id and reference_insuff.status = 1)');

		$this->db->where("candidates_info.id",$candidate_id);

		
		$this->db->order_by('reference_insuff.insuff_raised_date','desc');

		$reference  = $this->db->get()->result_array();

		return $reference;

		$this->db->select("credit_report_insuff.id as insuff_id, 'credit Report' as component_name,credit_report.id,credit_report.iniated_date, (select clientname from clients where clients.id = credit_report.clientid limit 1) as clientname,candidates_info.id as candidate_id,candidates_info.CandidateName, credit_report_com_ref as component_id,DATE_FORMAT(insuff_raised_date,'%d-%m-%Y') as insuff_raised_date,insff_reason,insuff_raise_remark,(select user_name from user_profile where id = credit_report_insuff.created_by) as raised_by, 'Credit_report' as controller,credit_report.clientid,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name, (select entity_package_name from entity_package  where entity_package.id= candidates_info.package limit 1) as package_name");

		$this->db->from('credit_report');

		$this->db->join("candidates_info",'candidates_info.id = credit_report.candsid');

		$this->db->join('credit_report_insuff','(credit_report.id = credit_report_insuff.credit_report_id and credit_report_insuff.status = 1)');

		$this->db->order_by('credit_report_insuff.insuff_raised_date','desc');

		$this->db->where("candidates_info.id",$candidate_id);

		$credit_report  = $this->db->get()->result_array();

		return $credit_report;
 	}

 	public function get_drugs_result($candidate_id)
 	{
		$this->db->select("reference_insuff.id as insuff_id, 'Reference' as component_name, reference.iniated_date,reference.id, (select clientname from clients where clients.id = reference.clientid limit 1) as clientname, candidates_info.id as candidate_id,candidates_info.CandidateName,reference_com_ref as component_id,DATE_FORMAT(insuff_raised_date,'%d-%m-%Y') as insuff_raised_date,insff_reason,insuff_raise_remark,(select user_name from user_profile where id = reference_insuff.created_by) as raised_by, 'reference_verificatiion' as controller,reference.clientid,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name, (select entity_package_name from entity_package  where entity_package.id= candidates_info.package limit 1) as package_name");

		$this->db->from('reference');

		$this->db->join("candidates_info",'candidates_info.id = reference.candsid');

		$this->db->join('reference_insuff','(reference.id = reference_insuff.reference_id and reference_insuff.status = 1)');

		$this->db->where("candidates_info.id",$candidate_id);

		
		$this->db->order_by('reference_insuff.insuff_raised_date','desc');

		$reference  = $this->db->get()->result_array();

		return $reference;

		$this->db->select("drug_narcotis_insuff.id as insuff_id, 'Drug' as component_name,drug_narcotis.iniated_date, drug_narcotis.id,  (select clientname from clients where clients.id = drug_narcotis.clientid limit 1) as clientname,candidates_info.id as candidate_id,candidates_info.CandidateName, drug_com_ref as component_id,DATE_FORMAT(insuff_raised_date,'%d-%m-%Y') as insuff_raised_date,insff_reason,insuff_raise_remark,(select user_name from user_profile where id = drug_narcotis_insuff.created_by) as raised_by, 'drugs_narcotics' as controller,drug_narcotis.clientid,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name, (select entity_package_name from entity_package  where entity_package.id= candidates_info.package limit 1) as package_name");

		
		$this->db->from('drug_narcotis');

		$this->db->join("candidates_info",'candidates_info.id = drug_narcotis.candsid');

		$this->db->join('drug_narcotis_insuff','(drug_narcotis.id = drug_narcotis_insuff.drug_narcotis_id and drug_narcotis_insuff.status = 1)');

		$this->db->where("candidates_info.id",$candidate_id);

		$this->db->order_by('drug_narcotis_insuff.insuff_raised_date','desc');

		$drug_narcotis  = $this->db->get()->result_array();

		return $drug_narcotis;

 	}

}
?>