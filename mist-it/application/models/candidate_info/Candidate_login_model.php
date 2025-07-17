<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Candidate_login_model extends CI_Model
{
	function __construct()
    {
		$this->tableName = 'client_candidates_info';

		$this->primaryKey = 'id';
	}

	public function select($return_as_strict_row,$select_array, $where_array=array())
	{
		$this->db->select($select_array);

		$this->db->from($this->tableName);

		$this->db->where($where_array);
		
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

	public function user_components_details($where_array = array())
	{
		$this->db->select('client_login.email_id,clients.clientname,client_login.client_id,client_login.first_name,client_login.last_name,client_login.mobile_no,client_login.profile_pic,client_login.id as client_login_id,client_login.role,client_login.client_entity_access');

		$this->db->from('client_login');

		$this->db->join('clients','clients.id = client_login.client_id');

		$this->db->where($where_array);

		$result  = $this->db->get();
		
		record_db_error($this->db->last_query());
		
		$result_array = $result->result_array();

        if(!empty($where_array) && !empty($result))
		{
         	$result_array  = $result_array[0];
        }
        return $result_array;
	}

	public function select_menu()
	{
        
        $this->db->select("empver.id,'Employment' as component_name");

		$this->db->from('empver');

		$this->db->join("empverres as ev1",'ev1.empverid = empver.id','left');

		$this->db->where('empver.fill_by','2'); 
        
        $this->db->where('ev1.verfstatus','18');

        $this->db->where('empver.candsid',$this->candidate_info['cands_info_id']); 

		$empver  = $this->db->get()->result_array();

		$this->db->select("empver.id,'Employment Submit' as component_name");

		$this->db->from('empver');

		$this->db->join("empverres as ev1",'ev1.empverid = empver.id','left');

		$this->db->where('empver.fill_by','2'); 
        
        $this->db->where('ev1.verfstatus !=','18');

        $this->db->where('empver.candsid',$this->candidate_info['cands_info_id']); 

		$empver_submit  = $this->db->get()->result_array();
		
		$this->db->select("addrver.id, 'Address' as component_name");

		$this->db->from('addrver');

		$this->db->join("addrverres as a1",'a1.addrverid = addrver.id','left');

       	$this->db->where('addrver.fill_by','2'); 
        
        $this->db->where('a1.verfstatus','18');

        $this->db->where('addrver.candsid',$this->candidate_info['cands_info_id']); 
	
		$addrver  = $this->db->get()->result_array();

	    $this->db->select("addrver.id, 'Address Submit' as component_name");

		$this->db->from('addrver');

		$this->db->join("addrverres as a1",'a1.addrverid = addrver.id','left');

       	$this->db->where('addrver.fill_by','2'); 
        
        $this->db->where('a1.verfstatus !=','18');

        $this->db->where('addrver.candsid',$this->candidate_info['cands_info_id']); 

		$addrver_submit  = $this->db->get()->result_array();

		$this->db->select("education.id, 'Education' as component_name");

		$this->db->from('education');

		$this->db->join("education_result as e1",'e1.education_id = education.id','left');

       	$this->db->where('education.fill_by','2'); 
        
        $this->db->where('e1.verfstatus','18');

        $this->db->where('education.candsid',$this->candidate_info['cands_info_id']); 

		$education  = $this->db->get()->result_array();

		$this->db->select("education.id, 'Education Submit' as component_name");

		$this->db->from('education');

		$this->db->join("education_result as e1",'e1.education_id = education.id','left');

       	$this->db->where('education.fill_by','2'); 
        
        $this->db->where('e1.verfstatus !=','18');

        $this->db->where('education.candsid',$this->candidate_info['cands_info_id']); 

		$education_submit  = $this->db->get()->result_array();

 
		$this->db->select("reference.id, 'Reference' as component_name");

		$this->db->from('reference');

		$this->db->join('reference_result as ref1','ref1.reference_id = reference.id','left');
		
	    $this->db->where('reference.fill_by','2'); 
        
        $this->db->where('ref1.verfstatus','18');

        $this->db->where('reference.candsid',$this->candidate_info['cands_info_id']); 

		$reference  = $this->db->get()->result_array();

		$this->db->select("reference.id, 'Reference Submit' as component_name");

		$this->db->from('reference');

		$this->db->join('reference_result as ref1','ref1.reference_id = reference.id','left');
		
	    $this->db->where('reference.fill_by','2'); 
        
        $this->db->where('ref1.verfstatus !=','18');

        $this->db->where('reference.candsid',$this->candidate_info['cands_info_id']); 

		$reference_submit  = $this->db->get()->result_array();
		
		$this->db->select("courtver.id,'Court' as component_name");

		$this->db->from('courtver');

	    $this->db->join('courtver_result as c1','c1.courtver_id = courtver.id','left');
		
	    $this->db->where('courtver.fill_by','2'); 
        
        $this->db->where('c1.verfstatus','18');

        $this->db->where('courtver.candsid',$this->candidate_info['cands_info_id']); 

		$court  = $this->db->get()->result_array();

	    $this->db->select("courtver.id,'Court Submit' as component_name");

		$this->db->from('courtver');

	    $this->db->join('courtver_result as c1','c1.courtver_id = courtver.id','left');
		
	    $this->db->where('courtver.fill_by','2'); 
        
        $this->db->where('c1.verfstatus !=','18');

        $this->db->where('courtver.candsid',$this->candidate_info['cands_info_id']); 

		$court_submit  = $this->db->get()->result_array();


		$this->db->select("glodbver.id,'Global DB' as component_name");

		$this->db->from('glodbver');

		$this->db->join('glodbver_result as gl1','gl1.glodbver_id = glodbver.id','left');

		$this->db->where('glodbver.fill_by','2'); 
        
        $this->db->where('gl1.verfstatus','18');

        $this->db->where('glodbver.candsid',$this->candidate_info['cands_info_id']); 

		$glodbver  = $this->db->get()->result_array();


		$this->db->select("glodbver.id,'Global DB Submit' as component_name");

		$this->db->from('glodbver');

		$this->db->join('glodbver_result as gl1','gl1.glodbver_id = glodbver.id','left');

		$this->db->where('glodbver.fill_by','2'); 
        
        $this->db->where('gl1.verfstatus !=','18');

        $this->db->where('glodbver.candsid',$this->candidate_info['cands_info_id']); 

		$glodbver_submit  = $this->db->get()->result_array();
		
		
		$this->db->select("pcc.id, 'PCC' as component_name");

		$this->db->from('pcc');

		$this->db->join('pcc_result as pcc1','pcc1.pcc_id = pcc.id','left');

		$this->db->where('pcc.fill_by','2'); 
        
        $this->db->where('pcc1.verfstatus','18');

        $this->db->where('pcc.candsid',$this->candidate_info['cands_info_id']); 
	
		$pcc  = $this->db->get()->result_array();


		$this->db->select("pcc.id, 'PCC Submit' as component_name");

		$this->db->from('pcc');

		$this->db->join('pcc_result as pcc1','pcc1.pcc_id = pcc.id','left');

		$this->db->where('pcc.fill_by','2'); 
        
        $this->db->where('pcc1.verfstatus !=','18');

        $this->db->where('pcc.candsid',$this->candidate_info['cands_info_id']); 
	
		$pcc_submit  = $this->db->get()->result_array();



		$this->db->select("identity.id, 'Identity' as component_name");

		$this->db->from('identity');

		$this->db->join('identity_result as i1','i1.identity_id = identity.id','left');

		$this->db->where('identity.fill_by','2'); 
        
        $this->db->where('i1.verfstatus','18');

        $this->db->where('identity.candsid',$this->candidate_info['cands_info_id']); 

		$identity  = $this->db->get()->result_array();


		$this->db->select("identity.id, 'Identity Submit' as component_name");

		$this->db->from('identity');

		$this->db->join('identity_result as i1','i1.identity_id = identity.id','left');

		$this->db->where('identity.fill_by','2'); 
        
        $this->db->where('i1.verfstatus !=','18');

        $this->db->where('identity.candsid',$this->candidate_info['cands_info_id']); 

		$identity_submit  = $this->db->get()->result_array();


		$this->db->select("credit_report.id, 'Credit Report' as component_name");

		$this->db->from('credit_report');

		$this->db->join('credit_report_result as cr1','cr1.credit_report_id = credit_report.id','left');

        $this->db->where('credit_report.fill_by','2'); 
        
        $this->db->where('cr1.verfstatus','18');

        $this->db->where('credit_report.candsid',$this->candidate_info['cands_info_id']); 
	
		$credit_report  = $this->db->get()->result_array();

	    $this->db->select("credit_report.id, 'Credit Report Submit' as component_name");

		$this->db->from('credit_report');

		$this->db->join('credit_report_result as cr1','cr1.credit_report_id = credit_report.id','left');

        $this->db->where('credit_report.fill_by','2'); 
        
        $this->db->where('cr1.verfstatus !=','18');

        $this->db->where('credit_report.candsid',$this->candidate_info['cands_info_id']); 

		$credit_report_submit  = $this->db->get()->result_array();

		return array_merge($empver,$addrver,$education,$reference,$court,$glodbver,$pcc,$identity,$credit_report,$empver_submit,$addrver_submit,$education_submit,$reference_submit,$court_submit,$glodbver_submit,$pcc_submit,$identity_submit,$credit_report_submit);
	 
	}

	public function select_client_email_id($where_array)
	{

		$this->db->select('email_id');

		$this->db->from('client_login');


		$this->db->where($where_array);

		$result  = $this->db->get();
		
		record_db_error($this->db->last_query());
		
		$result_array = $result->result_array();

        
        return $result_array;

	}
   
    public function select_client_manager_details($client_id)
    {
        $this->db->select('clientmgr');

		$this->db->from('clients');

		$this->db->where('clients.id',$client_id);
				
	    $result = $this->db->get();

		record_db_error($this->db->last_query());

	    return $result->result_array();
    }

    public function select_user_info($user_id)
    {
        $this->db->select('email');

		$this->db->from('user_profile');

		$this->db->where('user_profile.id',$user_id);

				
	    $result = $this->db->get();

		record_db_error($this->db->last_query());

	    return $result->result_array();
    }
    
    public function select_all_component_mail($candidate_id)
	{
        $this->db->select("addrver.id, 'Address' as component_name,addrver.add_com_ref as component_ref_no");

		$this->db->from('addrver');

        $this->db->where('addrver.candsid',$candidate_id); 
	
		$addrver  = $this->db->get()->result_array();

	    
        $this->db->select("empver.id,'Employment' as component_name,empver.emp_com_ref as component_ref_no");

		$this->db->from('empver');

        $this->db->where('empver.candsid',$candidate_id); 

		$empver  = $this->db->get()->result_array();

		
		$this->db->select("education.id, 'Education' as component_name,education.education_com_ref as component_ref_no");

		$this->db->from('education');

        $this->db->where('education.candsid',$candidate_id); 

		$education  = $this->db->get()->result_array();

		
		$this->db->select("reference.id, 'Reference' as component_name,reference.reference_com_ref as component_ref_no");

		$this->db->from('reference');

        $this->db->where('reference.candsid',$candidate_id); 

		$reference  = $this->db->get()->result_array();

		
		$this->db->select("courtver.id,'Court' as component_name,courtver.court_com_ref as component_ref_no");

		$this->db->from('courtver');

        $this->db->where('courtver.candsid',$candidate_id); 

		$court  = $this->db->get()->result_array();


		$this->db->select("glodbver.id,'Global DB' as component_name,glodbver.global_com_ref as component_ref_no");

		$this->db->from('glodbver');

        $this->db->where('glodbver.candsid',$candidate_id); 

		$glodbver  = $this->db->get()->result_array();

		$this->db->select("pcc.id, 'PCC' as component_name,pcc.pcc_com_ref as component_ref_no");

		$this->db->from('pcc');

        $this->db->where('pcc.candsid',$candidate_id); 
	
		$pcc  = $this->db->get()->result_array();


		$this->db->select("identity.id, 'Identity' as component_name,identity.identity_com_ref as component_ref_no");

		$this->db->from('identity');

        $this->db->where('identity.candsid',$candidate_id); 

		$identity  = $this->db->get()->result_array();


		$this->db->select("credit_report.id, 'Credit Report' as component_name,credit_report.credit_report_com_ref as component_ref_no");

		$this->db->from('credit_report');

        $this->db->where('credit_report.candsid',$candidate_id); 
	
		$credit_report  = $this->db->get()->result_array();

		return array_merge($empver,$addrver,$education,$reference,$court,$glodbver,$pcc,$identity,$credit_report);
	 
	}
   

}
?>