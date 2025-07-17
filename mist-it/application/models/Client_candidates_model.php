<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Client_candidates_model extends CI_Model
{
	function __construct()
    {
		$this->tableName = 'client_candidates_info';

		$this->primaryKey = 'id';
	}
   
    public function get_client_cand_by_datatable($where,$columns= null)
	{
             
			
		$this->db->select("client_candidates_info.id,client_candidates_info.clientid,client_candidates_info.CandidateName,client_candidates_info.cands_info_id,client_candidates_info.created_on,client_candidates_info.caserecddate,client_candidates_info.Location,client_candidates_info.Department,client_candidates_info.ClientRefNumber,client_candidates_info.cmp_ref_no,candidates_info.overallstatus,client_candidates_info.remarks,(select entity_package_name from entity_package where entity_package.id = client_candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = client_candidates_info.package limit 1) as package_name,clients.clientname,clients_details.tat_addrver,clients_details.tat_courtver,clients_details.tat_crimver,clients_details.tat_eduver,clients_details.tat_empver,clients_details.tat_narcver,clients_details.tat_refver,clients_details.tat_globdbver,client_candidates_info.overallclosuredate,client_candidates_info.ClientRefNumber AS EmployeeCode,status.status_value");

			if($where['status'] != "")
			{			
				
				$this->db->where("client_candidates_info.overallstatus",$where['status']);
		
			}


	        $this->db->where("client_candidates_info.status",1);
	        $this->db->where("client_candidates_info.check_mail_send",'0');
		


			if(is_array($where) && $where['search']['value'] != "")
			{
				$this->db->like($this->tableName.'.ClientRefNumber', $where['search']['value']);

				$this->db->or_like('clients.clientname', $where['search']['value']);

				$this->db->or_like($this->tableName.'.cmp_ref_no', $where['search']['value']);

				$this->db->or_like($this->tableName.'.CandidateName', $where['search']['value']);

				$this->db->or_like($this->tableName.'.CandidatesContactNumber', $where['search']['value']);

				$this->db->or_like($this->tableName.'.EmployeeCode', $where['search']['value']);

				//$this->db->or_like($this->tableName.'.overallstatus', $where['search']['value']);

			}

	        $this->db->limit($where['length'],$where['start']);
	        //$this->db->limit(50,0);

			//$this->db->order_by('candidates_info.id', 'desc');
			
			$this->db->group_by('client_candidates_info.id');

	        $this->db->from('client_candidates_info');

	        $this->db->join("candidates_info",'candidates_info.id = client_candidates_info.cands_info_id');

	        $this->db->join("status",'status.id = candidates_info.overallstatus');

	      
	         
	        $this->db->join("clients",'clients.id = client_candidates_info.clientid');
	            
	        $this->db->join("clients_details",'clients_details.tbl_clients_id = client_candidates_info.clientid');
	        

	       
	       if(!empty($where['order']))
		   {

			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir'];
           
			$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		   }
		   else
	       {
			$this->db->order_by('client_candidates_info.id','DESC');
		   }

	        $result = $this->db->get();
	       
	        
			record_db_error($this->db->last_query());

			return $result->result_array();
	}

	public function get_client_cand_by_datatable_count($where,$columns)
	{

		$this->db->select("client_candidates_info.id,client_candidates_info.clientid,client_candidates_info.CandidateName,client_candidates_info.caserecddate,client_candidates_info.ClientRefNumber,client_candidates_info.cmp_ref_no,client_candidates_info.overallstatus,client_candidates_info.remarks,clients.clientname,clients_details.tat_addrver,clients_details.tat_courtver,clients_details.tat_crimver,clients_details.tat_eduver,clients_details.tat_empver,clients_details.tat_narcver,clients_details.tat_refver,clients_details.tat_globdbver,client_candidates_info.overallclosuredate");

		$this->db->from('client_candidates_info');
  		
  		
		$this->db->join("clients",'clients.id = client_candidates_info.clientid');
           
        $this->db->join("clients_details",'clients_details.tbl_clients_id = client_candidates_info.clientid');

		$this->db->where("client_candidates_info.status",1);
		$this->db->where("client_candidates_info.check_mail_send",'0');
		

		if($where['status'] != "")
		{
			$status_array = array('stop-check' => 'Stop/Check','unable-to-verified'=>'Unable to Verify','completed' =>'Clear');

			if(array_key_exists($where['status'] ,$status_array))
			{
				$where['status'] = $status_array[$where['status']];
			}

			$this->db->where("client_candidates_info.overallstatus",$where['status']);

			if($where['status'] == 'discrepancy')
			{
				$this->db->or_where("client_candidates_info.overallstatus",'No Record Found');
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


		}

		  
	       
		
		    if(!empty($where['order']))
		    {

			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir'];
			
			$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		   }
		   else
		   {
			$this->db->order_by('client_candidates_info.id','DESC');
		   }
		
		$this->db->group_by('client_candidates_info.id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

		public function get_addres_ver_status($clientid)
	{
		$this->db->select("candidates_info.overallstatus,ev1.verfstatus,ev1.var_filter_status,addrver_insuff.insuff_raised_date,addrver_insuff.insuff_raise_remark,addrver_insuff.insuff_clear_date,ev1.closuredate,addrver_insuff.insuff_remarks,addrver.address as address,status.filter_status");

		$this->db->from('candidates_info');

		$this->db->join("addrver",'candidates_info.id = addrver.candsid');

		$this->db->join("clients",'(clients.id = candidates_info.clientid)');

		$this->db->join("addrverres as ev1",'ev1.addrverid = addrver.id','left');

		$this->db->join("addrverres as ev2",'(ev2.addrverid = addrver.id and ev1.id < ev2.id)','left');

		$this->db->join('addrver_insuff','(addrver_insuff.addrverid = addrver.id and addrver_insuff.status = 1)');

	    $this->db->join("status",'status.id = ev1.verfstatus');

		$this->db->where('ev2.verfstatus is null');
		
		if($clientid)
		{
			$this->db->where($clientid);
		}
		
		$this->db->order_by('addrver.id', 'ASC');
		
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

	    $this->db->join('education_insuff','(education_insuff.education_id = education.id and education_insuff.status = 1)');

		 $this->db->join("status",'status.id = ev1.verfstatus');

		$this->db->where('ev2.verfstatus is null');
		
		if($clientid)
		{
			$this->db->where($clientid);
		}

		$this->db->order_by('education.id', 'ASC');
		
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

    public function get_employment_ver_status1($clientid)
	{
		$this->db->select("candidates_info.overallstatus,status.action as verfstatus,ev1.var_filter_status,ev1.remarks as verification_remarks,ev1.closuredate");

		$this->db->from('empver');

		$this->db->join("candidates_info",'candidates_info.id = empver.candsid');

		$this->db->join("clients",'(clients.id = candidates_info.clientid)');

		$this->db->join("empverres as ev1",'ev1.empverid = empver.id','left');

		$this->db->join("empverres as ev2",'(ev2.empverid = empver.id and ev1.id < ev2.id)','left');

	    $this->db->join('empverres_insuff','(empverres_insuff.empverres_id = empver.id and empverres_insuff.status = 1)');


		$this->db->join("status",'status.id = ev1.verfstatus','left');


		$this->db->where('ev2.verfstatus is null');

		if($clientid)
		{
			$this->db->where($clientid);
		}

		$this->db->order_by('empver.id', 'ASC');
				
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

        $this->db->join('courtver_insuff','(courtver_insuff.courtver_id = courtver.id and courtver_insuff.status = 1)');
 
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
		$this->db->select("candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.CandidateName,candidates_info.caserecddate,reference.name_of_reference,reference.designation,reference.contact_no,ref1.created_on as last_modified,reference.designation,reference.contact_no,ref1.remarks,ref1.verfstatus as refverfstatus,reference.id,reference.clientid,reference.	has_assigned_on,ref1.id as refveri_id,GROUP_CONCAT(reference_files.file_name) as file_names,ref1.closuredate,candidates_info.id as candidates_infoid");

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

	    $this->db->join('glodbver_insuff','(glodbver_insuff.glodbver_id = glodbver.id and glodbver_insuff.status = 1)');

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

	public function get_identity_ver_status($where_arry = array())
	{
		$this->db->select("candidates_info.overallstatus,status.action as verfstatus,ev1.var_filter_status,ev1.remarks as verification_remarks,ev1.closuredate");

		$this->db->from('identity');

		$this->db->join("candidates_info",'candidates_info.id = identity.candsid');

		$this->db->join('clients','clients.id = candidates_info.clientid');

        $this->db->join("identity_result as ev1",'ev1.identity_id = identity.id','left');

		$this->db->join("identity_result as ev2",'(ev2.identity_id = identity.id and ev1.id < ev2.id)','left');

	    $this->db->join('identity_insuff','(identity_insuff.identity_id = identity.id and identity_insuff.status = 1)');


		$this->db->join("status",'status.id = ev1.verfstatus','left');


		$this->db->where('ev2.verfstatus is null');

		if($where_arry)
		{
			$this->db->where($where_arry);
		}

		$this->db->group_by('identity.id');

		$this->db->order_by('identity.id', 'ASC');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_credit_reports_ver_status($where_arry = array())
	{
		$this->db->select("candidates_info.overallstatus,status.action as verfstatus,ev1.var_filter_status,ev1.remarks as verification_remarks,ev1.closuredate");

		$this->db->from('credit_report');

		$this->db->join("candidates_info",'candidates_info.id = credit_report.candsid');

		$this->db->join('clients','clients.id = candidates_info.clientid');

        $this->db->join("credit_report_result as ev1",'ev1.credit_report_id = credit_report.id','left');

		$this->db->join("credit_report_result as ev2",'(ev2.credit_report_id = credit_report.id and ev1.id < ev2.id)','left');
       
       	$this->db->join('credit_report_insuff','(credit_report_insuff.credit_report_id  = credit_report.id and credit_report_insuff.status = 1)');

		$this->db->join("status",'status.id = ev1.verfstatus','left');

		$this->db->where('ev2.verfstatus is null');

		if($where_arry)
		{
			$this->db->where($where_arry);
		}

		$this->db->group_by('credit_report.id');

		$this->db->order_by('credit_report.id', 'ASC');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_narcver_ver_status($where_arry = array())
	{
		$this->db->select("candidates_info.overallstatus,status.action as verfstatus,ev1.var_filter_status,ev1.remarks as verification_remarks,ev1.closuredate");

		$this->db->from('drug_narcotis');

		$this->db->join("candidates_info",'candidates_info.id = drug_narcotis.candsid');

		$this->db->join('clients','clients.id = candidates_info.clientid');

        $this->db->join("drug_narcotis_result as ev1",'ev1.drug_narcotis_id = drug_narcotis.id','left');

		$this->db->join("drug_narcotis_result as ev2",'(ev2.drug_narcotis_id = drug_narcotis.id and ev1.id < ev2.id)','left');

		$this->db->join('drug_narcotis_insuff','(drug_narcotis_insuff.drug_narcotis_id  = drug_narcotis.id and drug_narcotis_insuff.status = 1)');


		$this->db->join("status",'status.id = ev1.verfstatus','left');


		$this->db->where('ev2.verfstatus is null');

		if($where_arry)
		{
			$this->db->where($where_arry);
		}

		$this->db->group_by('drug_narcotis.id');

		$this->db->order_by('drug_narcotis.id', 'ASC');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}


	
}
?>