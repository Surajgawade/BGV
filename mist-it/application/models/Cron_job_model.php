<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cron_job_model extends CI_Model
{
	function __construct()
    {
		$this->tableName = 'cron_job';

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


    public function save_cron($tableName,$arrdata,$arrwhere = array())
	{
	    if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update($tableName, $arrdata);
       
			record_db_error($this->db->last_query());
             
			return $result;
	    }
	    else
	    {
			$this->db->insert($tableName, $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	    }
	}


	public function Cron_job_details()
	{
		$this->db->select("cron_job.id as cronjob_id,cron_job.activity_name,cron_job.created_on,cron_job.executed_on, cron_job.status,(select concat(user_profile.firstname,' ',user_profile.lastname) from user_profile where user_profile.id = cron_job.created_by) as user_name");

		$this->db->from('cron_job');

		$this->db->order_by('cron_job.id', 'ASC');

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}
    
    public function vendor_details()
	{
		$lists = $this->common_model->select('vendors', false, array("id","vendor_name" ),array());

        $status_arry[0] = 'Select Vendor';

        foreach ($lists as $key => $value) {
            $status_arry[$value['id']] = $value['vendor_name'];
        }
        $status_arry['All'] = 'All';
        return $status_arry;
	}

	

	

	/*public function get_all_cand_for_fino_finance($clientid)
	{
        $date = date("Y-m-d", strtotime("-5 days"));

        $where_array = "candidates_info.caserecddate <= '$date'"; 

        $where_status = "(candidates_info.overallstatus = '5' or candidates_info.overallstatus = '6')";

		$this->db->select("candidates_info.id,candidates_info.clientid,candidates_info.CandidateName,candidates_info.caserecddate,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.due_date_candidate,candidates_info.Location,clients.clientname,candidates_info.overallclosuredate,status.status_value,candidates_info.overallstatus,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,clients_details.component_id");

		$this->db->from('candidates_info');

		$this->db->join("clients",'clients.id = candidates_info.clientid');

	    $this->db->join("status",'status.id = candidates_info.overallstatus');

		$this->db->join("clients_details",'clients_details.tbl_clients_id = candidates_info.clientid and `clients_details`.`entity` = `candidates_info`.`entity` AND `clients_details`.`package` = `candidates_info`.`package`');

		if($clientid)
		{
			
			$this->db->where("candidates_info.clientid",$clientid);

		}

		$this->db->where($where_status);

		$this->db->where($where_array);

		$this->db->order_by('candidates_info.id', 'desc');
		
		$this->db->group_by('candidates_info.id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}*/

	public function get_all_cand_for_fino_finance($clientid)
	{
        $where_status_address = "(ev1.verfstatus = '1' or ev1.verfstatus = '11' or ev1.verfstatus = '12' or ev1.verfstatus = '13' or ev1.verfstatus = '14' or ev1.verfstatus = '16' or ev1.verfstatus = '23' or ev1.verfstatus = '26' or ev1.verfstatus = '18' or ev1.verfstatus = '19')";

        $where_status_employment = "(ev1.verfstatus = '1' or ev1.verfstatus = '11' or ev1.verfstatus = '12' or ev1.verfstatus = '13' or ev1.verfstatus = '14' or ev1.verfstatus = '16' or ev1.verfstatus = '23' or ev1.verfstatus = '26' or ev1.verfstatus = '18' or ev1.verfstatus = '19')";

        $count5WD = 1;
        $temp = strtotime("now"); 
        while($count5WD<5){
            $prev1WD = strtotime('-1 weekday', $temp);
            $prev1WDDate = date('Y-m-d', $prev1WD);
           // if(!in_array($prev1WDDate, $holidayDates)){
            $count5WD++;
           // }
            $temp = $prev1WD;
        }

        $prev5WD = date("Y-m-d", $temp);
     
        $count10WD = 1;
        $temp = strtotime("now"); 
        while($count10WD<10){
            $prev1WD = strtotime('-1 weekday', $temp);
            $prev1WDDate = date('Y-m-d', $prev1WD);
           // if(!in_array($prev1WDDate, $holidayDates)){
            $count10WD++;
           // }
            $temp = $prev1WD;
        }

        $prev10WD = date("Y-m-d", $temp);

        $count15WD = 1;
        $temp = strtotime("now"); 
        while($count15WD<15){
            $prev1WD = strtotime('-1 weekday', $temp);
            $prev1WDDate = date('Y-m-d', $prev1WD);
           // if(!in_array($prev1WDDate, $holidayDates)){
            $count15WD++;
           // }
            $temp = $prev1WD;
        }

        $prev15WD = date("Y-m-d", $temp);
       
        $where_array_address = "IF((ev1.verfstatus = '1' or ev1.verfstatus = '11' or ev1.verfstatus = '12' or ev1.verfstatus = '13' or ev1.verfstatus = '14' or ev1.verfstatus = '16' or ev1.verfstatus = '23' or ev1.verfstatus = '26'),(addrver.iniated_date = '$prev5WD' or addrver.iniated_date = '$prev10WD' or addrver.iniated_date <= '$prev15WD'),addrver.iniated_date <= '$prev5WD')"; 

        $where_array_employment = "IF((ev1.verfstatus = '1' or ev1.verfstatus = '11' or ev1.verfstatus = '12' or ev1.verfstatus = '13' or ev1.verfstatus = '14' or ev1.verfstatus = '16' or ev1.verfstatus = '23' or ev1.verfstatus = '26'),(empver.iniated_date = '$prev5WD' or empver.iniated_date = '$prev10WD' or empver.iniated_date <= '$prev15WD'),empver.iniated_date <= '$prev5WD')"; 

        $this->db->select("candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.overallstatus,'Address' as component_name,candidates_info.entity,candidates_info.package,(select entity_package_name from entity_package  where entity_package.id= candidates_info.package limit 1) as package_name,(select clientname from clients where clients.id = addrver.clientid) as clientname,addrver.iniated_date,addrver.id,concat(addrver.address,' ',addrver.city,' ',addrver.state,' - ',addrver.pincode) as details,candidates_info.CandidateName,addrver_insuff.insuff_raise_remark,ev1.remarks as verification_remarks,ev1.verfstatus,status.action as status,addrver.clientid");

		$this->db->from('addrver');

		$this->db->join("candidates_info",'candidates_info.id = addrver.candsid');

		$this->db->join("addrver_insuff",'addrver_insuff.addrverid = addrver.id and addrver_insuff.status = 1','left');

		$this->db->join("addrverres as ev1",'ev1.addrverid = addrver.id','left');

		$this->db->join("addrverres as ev2",'(ev2.addrverid = addrver.id and ev1.id < ev2.id)','left');

		$this->db->join("status",'status.id = ev1.verfstatus','left');

		$this->db->where('ev2.verfstatus is null');

		$this->db->where("addrver.clientid",$clientid);

	    $this->db->where($where_status_address);

	    $this->db->where($where_array_address);

	    $this->db->where("candidates_info.overallstatus !=",3);

	    $this->db->order_by('candidates_info.package', 'asc');
	
		$addrver  = $this->db->get()->result_array();

        $this->db->select("candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.overallstatus,'Employment' as component_name,candidates_info.entity,candidates_info.package,(select entity_package_name from entity_package  where entity_package.id= candidates_info.package limit 1) as package_name,(select clientname from clients where clients.id = empver.clientid) as clientname,empver.iniated_date,empver.id,candidates_info.CandidateName,empverres_insuff.insuff_raise_remark,(select coname from company_database where company_database.id = empver.nameofthecompany) as details,ev1.remarks as verification_remarks,ev1.verfstatus,status.action as status,empver.clientid");

		$this->db->from('empver');

		$this->db->join("candidates_info",'candidates_info.id = empver.candsid');

		$this->db->join("empverres_insuff",'empverres_insuff.empverres_id = empver.id and empverres_insuff.status = 1','left');

	    $this->db->join("empverres as ev1",'ev1.empverid = empver.id','left');

		$this->db->join("empverres as ev2",'(ev2.empverid = empver.id and ev1.id < ev2.id)','left');

		$this->db->join("status",'status.id = ev1.verfstatus','left');

		$this->db->where('ev2.verfstatus is null');

		$this->db->where("empver.clientid",$clientid);
		
		$this->db->where($where_status_employment);

		$this->db->where($where_array_employment);

		$this->db->where("candidates_info.overallstatus !=",3);

		$this->db->order_by('candidates_info.package', 'asc');

		$empver  = $this->db->get()->result_array();
		

		return array_merge($addrver,$empver);
       
	}

	public function select_client_details($where_array)
	{
		$this->db->select('id');

		$this->db->from('clients_details');

		$this->db->where($where_array);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function select_spoc_mail_id($where_array)
	{

		$this->db->select("client_spoc_details.*");

		$this->db->from('client_spoc_details');

		$this->db->where_in('clients_details_id',$where_array);

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();

	}

	public function select_client_manager_details($where_arry)
    {
        $this->db->select('clientmgr');

		$this->db->from('clients');

		$this->db->where($where_arry);
				
	    $result = $this->db->get();

		record_db_error($this->db->last_query());

	    return $result->result_array();
    }

    public function select_client_manager_email_details($where_arry)
    {
    	$this->db->select('email');

		$this->db->from('user_profile');

		$this->db->where($where_arry);
				
	    $result = $this->db->get();

		record_db_error($this->db->last_query());

	    return $result->result_array();
    }
    

    public function get_all_client_spoc_details_with_count($clientid)
    {

    	$date = date("Y-m-d", strtotime("-5 days"));

        $where_array = "candidates_info.caserecddate <= '$date'"; 

        $where_status = "(candidates_info.overallstatus = '5' or candidates_info.overallstatus = '6')";

		$this->db->select("entity_package.entity_package_name,CASE  WHEN overallstatus = 5 THEN 'Insufficiency' WHEN overallstatus = 6 THEN 'Mojor Discrepency'  END  as status,count(*) as count");

		$this->db->from('candidates_info');

		$this->db->join("clients",'clients.id = candidates_info.clientid');

	    $this->db->join("status",'status.id = candidates_info.overallstatus');

	    $this->db->join("entity_package",'entity_package.id = candidates_info.package');

		$this->db->join("clients_details",'clients_details.tbl_clients_id = candidates_info.clientid and `clients_details`.`entity` = `candidates_info`.`entity` AND `clients_details`.`package` = `candidates_info`.`package`');

		if($clientid)
		{
			
			$this->db->where("candidates_info.clientid",$clientid);

		}


		$this->db->where($where_status);

		$this->db->where($where_array);

		$this->db->group_by('candidates_info.package,candidates_info.overallstatus');  

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		$result = $result->result_array();


        $return = '';

		foreach ($result as $key => $value) {
			
			$return[$value['entity_package_name'].'_'.$value['status']] = $value['count'];
		}
		
       
        return $return;

       
    }
    public function get_all_court_vendor_details()
    {
    	$this->db->select("courtver.court_com_ref,courtver.street_address,courtver.city,courtver.pincode,courtver.state,candidates_info.CandidateName,candidates_info.DateofBirth,candidates_info.NameofCandidateFather,view_vendor_master_log.vendor_actual_status,view_vendor_master_log.final_status");

		$this->db->from('courtver');

		$this->db->join("candidates_info",'candidates_info.id = courtver.candsid');

	    $this->db->join("vendors",'vendors.id = courtver.vendor_id');

	    $this->db->join("courtver_vendor_log",'courtver_vendor_log.case_id = courtver.id and courtver_vendor_log.status = 1');

        $this->db->join("view_vendor_master_log","(view_vendor_master_log.case_id = courtver_vendor_log.id and view_vendor_master_log.component = 'courtver')");
            
        $this->db->where('vendors.generate',1);

        $this->db->where('view_vendor_master_log.final_status',"clear");

        $result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();

    }

    public function selected_aq_component()
    {
    	$this->db->select("cron_job_component.cron_job_component_selection");

		$this->db->from('cron_job_component');

		$this->db->join("cron_job",'cron_job.id = cron_job_component.cron_job_id');
            
        $this->db->where('cron_job_component.status',1);

        $this->db->where('cron_job_component.cron_job_id',5);
        
        $result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
    }

    public function selected_final_qc_aq_component()
    {
    	$this->db->select("cron_job_component.cron_job_component_selection");

		$this->db->from('cron_job_component');

		$this->db->join("cron_job",'cron_job.id = cron_job_component.cron_job_id');
            
        $this->db->where('cron_job_component.status',1);

        $this->db->where('cron_job_component.cron_job_id',6);
        
        $result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
    }
   
}
?>
