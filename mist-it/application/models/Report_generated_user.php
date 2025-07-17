<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Report_generated_user extends CI_Model
{
	function __construct()
    {
		$this->tableName = 'report_generated_user';

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



	//scheduler_task
	public function select_schedule_task($return_as_strict_row,$select_array, $where_array = array())
	{
		$this->db->select($select_array);

		$this->db->from('scheduler_task');

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

	public function select_schedule_task_days($where_array = array())
	{
		$this->db->select('*');

		$this->db->from('scheduler_task');

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

	public function scheduler_task_save($arrdata,$arrwhere = array())
	{
	    if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update('scheduler_task', $arrdata);

			record_db_error($this->db->last_query());

			return $result;
	    }
	    else
	    {
			$this->db->insert('scheduler_task', $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	    }
	}

	/*public function scheduler_details($where_array)
	{
		$this->db->select('scheduler_task.*,query,type,filter,(select user_name from user_profile where user_profile.id = scheduler_task.created_by) as executive_name,report_generated_user.id as report_id');

		$this->db->from('scheduler_task');

		$this->db->join('report_generated_user','report_generated_user.id = scheduler_task.report_id');

		$this->db->where($where_array);

		$this->db->order_by('scheduler_task.id', 'desc');

		$result  = $this->db->get();
		record_db_error($this->db->last_query());
		print_r($this->db->last_query());
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

 	 public function report_requested_schedular($arrdata,$arrwhere = array())
  	{
	    if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update('scheduler_list', $arrdata);
//print_r($this->db->last_query());
			record_db_error($this->db->last_query());

			return $result;
	    }
	    else
	    {
			$this->db->insert('scheduler_list', $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	    }
 	}

 	public function report_update_file_download_status($arrdata,$arrwhere = array())
  	{
	    if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update('report_requested', $arrdata);

			record_db_error($this->db->last_query());

			return $result;
	    }
	   
 	}

	public function get_user_activity_details($where)
	{
	 
	    $this->db->select("users_activitity_data.created_by,users_activitity_data.component,CASE  WHEN action = 'Add' THEN 'Add' WHEN action = 'Verification Added - Clear' THEN 'Closed' WHEN action = 'Verification Added - Unable To Verify'  THEN 'Closed' WHEN action = 'Verification Added - NA'  THEN 'Closed' WHEN action = 'Verification Added - Stop Check'  THEN 'Closed' WHEN action = 'Verification Added - Change Of Address'  THEN 'Closed' WHEN action = 'Verification Added - Overseas check'  THEN 'Closed' WHEN action = 'Verification Added - No Record Found'  THEN 'Closed' WHEN action = 'Verification Added - Worked with the same organization'  THEN 'Closed' WHEN action = 'Verification Added - Major Discrepancy'  THEN 'Closed' WHEN action = 'Verification Added - Minor Discrepancy'  THEN 'Closed'  WHEN action = 'Insuff Raised' THEN 'Insufficiency' WHEN action = 'Insuff Cleared' THEN 'Cleared' WHEN action = 'Initiation Email' THEN 'WIP'  WHEN action = 'Generic Email' THEN 'WIP'   WHEN action = 'Verification Added - Follow Up' THEN 'WIP' WHEN action = 'Final QC Approved' THEN 'Approved'   END  as action_result,count(*) as count");

		$this->db->from('users_activitity_data');

		if(isset($where['start_date']) &&  $where['start_date'] != '' && isset($where['end_date']) &&  $where['end_date'] != '')	
		    { 

		     	$start_date  =  convert_display_to_db_date($where['start_date']);
	            $end_date  =  convert_display_to_db_date($where['end_date']);

	         
		     	$where3 = "DATE_FORMAT(`users_activitity_data`.`created_on`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";


                $this->db->where($where3); 

		    } 

	
        $this->db->group_by('created_by,component,action_result');
	
		$results = $this->db->get();
	
		record_db_error($this->db->last_query());
  
        $result   =  $results->result_array();
		
        $return = array();

		foreach ($result as $key => $value) {
			
			$return[strtolower(str_replace(" ","_",$value['component'])).'_'.strtolower($value['action_result']).'_'.$value['created_by']] = $value['count'];

		}

        return $return;

	}

	public function get_hourly_activity_details($where)
	{
	 
	    $this->db->select("users_activitity_data.created_by,CASE  WHEN HOUR(`created_on`) = '1' THEN 'eight' WHEN HOUR(`created_on`) = '2' THEN 'eight' WHEN HOUR(`created_on`) = '3'  THEN 'eight' WHEN HOUR(`created_on`) = '4'  THEN 'eight' WHEN HOUR(`created_on`) = '5'  THEN 'eight' WHEN HOUR(`created_on`) = '6'  THEN 'eight' WHEN HOUR(`created_on`) = '7'  THEN 'eight' WHEN HOUR(`created_on`) = '8'  THEN 'eight' WHEN HOUR(`created_on`) = '9'  THEN 'nine' WHEN HOUR(`created_on`) = '10'  THEN 'ten' WHEN  HOUR(`created_on`) = '11'  THEN 'eleven'  WHEN  HOUR(`created_on`) = '12' THEN 'Twelve' WHEN HOUR(`created_on`) = '13' THEN 'thirteen' WHEN HOUR(`created_on`) = '14' THEN 'fourteen'  WHEN HOUR(`created_on`) = '15' THEN 'fifteen'   WHEN HOUR(`created_on`) = '16' THEN 'sixteen' WHEN HOUR(`created_on`) = '17'  THEN 'seventeen' WHEN HOUR(`created_on`) = '18'  THEN 'eighteen' WHEN HOUR(`created_on`) = '19'  THEN 'nineteen' WHEN HOUR(`created_on`) = '20'  THEN 'eight' WHEN HOUR(`created_on`) = '21'  THEN 'eight' WHEN HOUR(`created_on`) = '22'  THEN 'eight' WHEN HOUR(`created_on`) = '23'  THEN 'eight' WHEN HOUR(`created_on`) = '24'  THEN 'eight'  END as hour, COUNT(*) as count");

		$this->db->from('users_activitity_data');

		$this->db->where('users_activitity_data.action !=',"Edit"); 

		if(isset($where['start_date']) &&  $where['start_date'] != '' && isset($where['end_date']) &&  $where['end_date'] != '')	
		    { 

		     	$start_date  =  convert_display_to_db_date($where['start_date']);
	            $end_date  =  convert_display_to_db_date($where['end_date']);

	         
		     	$where3 = "DATE_FORMAT(`users_activitity_data`.`created_on`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";


                $this->db->where($where3); 

		    } 

	
        $this->db->group_by('created_by,hour');
	
		$results = $this->db->get();
	
		record_db_error($this->db->last_query());
  
        $result   =  $results->result_array();
		
        $return = array();

		foreach ($result as $key => $value) {
			
			$return[strtolower($value['hour']).'_'.$value['created_by']] = $value['count'];

		}

        return $return;

	}


 	public function get_activity_file()
  	{
	    $this->db->select("folder_name,file_name,created_on");
        $this->db->from('report_requested');
        $this->db->where('folder_generated_status !=',0);
        $this->db->where('folder_name !=', '');
        $this->db->where('type', 'Activity Log');
        $this->db->limit(10,0);
        $this->db->order_by('id', 'desc');

        $result = $this->db->get();
        $final_array = $result->result_array();

        return $final_array;
 	}

 	public function get_prepost_file()
  	{
	    $this->db->select("folder_name,file_name,created_on");
        $this->db->from('report_requested');
        $this->db->where('folder_generated_status !=',0);
        $this->db->where('folder_name !=', '');
        $this->db->where('type', 'Pre Post');
        $this->db->limit(5,0);
        $this->db->order_by('id', 'desc');

        $result = $this->db->get();
        $final_array = $result->result_array();

        return $final_array;
 	}

 	public function get_wip_insuff_file()
  	{
	    $this->db->select("folder_name,file_name,created_on");
        $this->db->from('report_requested');
        $this->db->where('folder_generated_status !=',0);
        $this->db->where('folder_name !=', '');
        $this->db->where('type', 'WIP_Insuff_report');
        $this->db->limit(10,0);
        $this->db->order_by('id', 'desc');

        $result = $this->db->get();
        $final_array = $result->result_array();

        return $final_array;
 	}

    public function get_axis_file()
  	{
	    $this->db->select("folder_name,file_name,created_on");
        $this->db->from('report_requested');
        $this->db->where('folder_generated_status !=',0);
        $this->db->where('folder_name !=', '');
        $this->db->where('type', 'Axis Tracker');
        $this->db->limit(10,0);
        $this->db->order_by('id', 'desc');

        $result = $this->db->get();
        $final_array = $result->result_array();

        return $final_array;
 	}

 	public function get_axis_ikya_file()
  	{
	    $this->db->select("folder_name,file_name,created_on");
        $this->db->from('report_requested');
        $this->db->where('folder_generated_status !=',0);
        $this->db->where('folder_name !=', '');
        $this->db->where('type', 'Axis Securities Limited (Ikya)');
        $this->db->limit(10,0);
        $this->db->order_by('id', 'desc');

        $result = $this->db->get();
        $final_array = $result->result_array();

        return $final_array;
 	}

 	public function get_axis_teamlease_file()
  	{
	    $this->db->select("folder_name,file_name,created_on");
        $this->db->from('report_requested');
        $this->db->where('folder_generated_status !=',0);
        $this->db->where('folder_name !=', '');
        $this->db->where('type', 'Axis Securities Limited (Teamlease)');
        $this->db->limit(10,0);
        $this->db->order_by('id', 'desc');

        $result = $this->db->get();
        $final_array = $result->result_array();

        return $final_array;
 	}
	public function scheduler_details($where_array)
	{
		$this->db->select('scheduler_list.*,(select user_name from user_profile where user_profile.id = scheduler_list.last_run_by) as username');

		$this->db->from('scheduler_list');

		//$this->db->join('report_generated_user','report_generated_user.id = scheduler_task.report_id');

		$this->db->where($where_array);

		$this->db->order_by('scheduler_list.id', 'ASC');

		$result  = $this->db->get();
		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}
	public function get_activity_records($where_array)
	{
		$this->db->select('users_activitity_data.*,(select user_name from user_profile where user_profile.id = users_activitity_data.created_by) as username');

		$this->db->from('users_activitity_data');
		
		$this->db->where($where_array);

		$this->db->order_by('users_activitity_data.created_on',"DESC");

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

    public function select_overall_wip_insuff_cases()
	{
	
		$this->db->select("add_com_ref as component_id,'Address' as component_name,candidates_info.cmp_ref_no,candidates_info.CandidateName,candidates_info.ClientRefNumber,status.status_value,addrver.iniated_date,addrver.id,(select clientname from clients where clients.id = addrver.clientid limit 1) as clientname,candidates_info.CandidateName,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name, (select entity_package_name from entity_package  where entity_package.id= candidates_info.package limit 1) as package_name,addrver.due_date,addrver.tat_status,(select concat(`user_profile`.`firstname`,' ',`user_profile`.`lastname`) from user_profile where user_profile.id = addrver.has_case_id limit 1) as executive_name,concat(`addrver`.`address`,',',`addrver`.`city`,',',`addrver`.`pincode`,',',`addrver`.`state`) as Details");

		$this->db->from('addrver');

		$this->db->join("candidates_info",'candidates_info.id = addrver.candsid');

		$this->db->join('addrverres','addrverres.addrverid = addrver.id','left');

		$this->db->join("status",'status.id = addrverres.verfstatus','left');

		$this->db->where('(addrverres.var_filter_status != "Closed"  or  addrverres.var_filter_status != "closed")');

		$this->db->where('(addrverres.var_filter_status != "NA")');

		$addrver  = $this->db->get()->result_array();

		$this->db->select("emp_com_ref as component_id,'Employment' as component_name,candidates_info.CandidateName,candidates_info.cmp_ref_no,candidates_info.ClientRefNumber,status.status_value,empver.iniated_date,empver.id,(select clientname from clients where clients.id = empver.clientid limit 1) as clientname,
			(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name, (select entity_package_name from entity_package  where entity_package.id= candidates_info.package limit 1) as package_name,empver.due_date,empver.tat_status,(select concat(`user_profile`.`firstname`,' ',`user_profile`.`lastname`) from user_profile where user_profile.id = empver.has_case_id limit 1) as executive_name,(select concat(`company_database`.`coname`) from company_database where company_database.id = empver.nameofthecompany limit 1) as Details");

		$this->db->from('empver');

		$this->db->join("candidates_info",'candidates_info.id = empver.candsid');

	    $this->db->join('empverres','empverres.empverid = empver.id','left');

		$this->db->join("status",'status.id = empverres.verfstatus','left');

		$this->db->where('(empverres.var_filter_status != "Closed"  or  empverres.var_filter_status != "closed")');

		$this->db->where('(empverres.var_filter_status != "NA")');

		
		$empver  = $this->db->get()->result_array();


		$this->db->select("education_com_ref as component_id, 'Education' as component_name,candidates_info.CandidateName,candidates_info.cmp_ref_no,candidates_info.ClientRefNumber,status.status_value,education.iniated_date,education.id, (select clientname from clients where clients.id = education.clientid limit 1) as clientname,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name, (select entity_package_name from entity_package  where entity_package.id= candidates_info.package limit 1) as package_name,education.due_date,education.tat_status,(select concat(`user_profile`.`firstname`,' ',`user_profile`.`lastname`) from user_profile where user_profile.id = education.has_case_id limit 1) as executive_name,concat(`university_master`.`universityname`,' ',`qualification_master`.`qualification`)  as Details");

		$this->db->from('education');

		$this->db->join("candidates_info",'candidates_info.id = education.candsid');
 
        $this->db->join('education_result','education_result.education_id = education.id','left');

        $this->db->join("university_master",'university_master.id = education.university_board','left');
 
        $this->db->join('qualification_master','qualification_master.id = education.qualification','left');

		$this->db->join("status",'status.id = education_result.verfstatus','left');

		$this->db->where('(education_result.var_filter_status != "Closed"  or  education_result.var_filter_status != "closed")');

	    $this->db->where('(education_result.var_filter_status != "NA")');		

		$education  = $this->db->get()->result_array();
		

		$this->db->select("reference_com_ref as component_id,'Reference' as component_name,candidates_info.ClientRefNumber,candidates_info.CandidateName,candidates_info.cmp_ref_no,status.status_value,reference.iniated_date,reference.id, (select clientname from clients where clients.id = reference.clientid limit 1) as clientname,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name, (select entity_package_name from entity_package  where entity_package.id= candidates_info.package limit 1) as package_name,reference.due_date,reference.tat_status,(select concat(`user_profile`.`firstname`,' ',`user_profile`.`lastname`) from user_profile where user_profile.id = reference.has_case_id limit 1) as executive_name,reference.name_of_reference  as Details");

		$this->db->from('reference');

		$this->db->join("candidates_info",'candidates_info.id = reference.candsid');

		$this->db->join('reference_result','reference_result.reference_id = reference.id','left');

		$this->db->join("status",'status.id = reference_result.verfstatus','left');

		$this->db->where('(reference_result.var_filter_status != "Closed"  or  reference_result.var_filter_status != "closed")');

		$this->db->where('(reference_result.var_filter_status != "NA")');			


		$reference  = $this->db->get()->result_array();
		
		$this->db->select("court_com_ref as component_id, 'Court' as component_name,candidates_info.CandidateName,candidates_info.cmp_ref_no,candidates_info.ClientRefNumber,status.status_value,courtver.iniated_date,courtver.id, (select clientname from clients where clients.id = courtver.clientid limit 1) as clientname,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name, (select entity_package_name from entity_package  where entity_package.id= candidates_info.package limit 1) as package_name,courtver.due_date,courtver.tat_status,(select concat(`user_profile`.`firstname`,' ',`user_profile`.`lastname`) from user_profile where user_profile.id = courtver.has_case_id limit 1) as executive_name,concat(`courtver`.`street_address`,',',`courtver`.`city`,',',`courtver`.`pincode`,',',`courtver`.`state`) as Details");

		$this->db->from('courtver');

		$this->db->join("candidates_info",'candidates_info.id = courtver.candsid');

		$this->db->join('courtver_result','courtver_result.courtver_id  = courtver.id','left');

		$this->db->join("status",'status.id = courtver_result.verfstatus','left');

		$this->db->where('(courtver_result.var_filter_status != "Closed"  or  courtver_result.var_filter_status != "closed")');	

		$this->db->where('(courtver_result.var_filter_status != "NA")');		

		$court  = $this->db->get()->result_array();


		$this->db->select("global_com_ref as component_id,'Global DB' as component_name,candidates_info.CandidateName,candidates_info.cmp_ref_no,candidates_info.ClientRefNumber,status.status_value,glodbver.iniated_date,glodbver.id, (select clientname from clients where clients.id = glodbver.clientid limit 1) as clientname,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package  where entity_package.id= candidates_info.package limit 1) as package_name,glodbver.due_date,glodbver.tat_status,(select concat(`user_profile`.`firstname`,' ',`user_profile`.`lastname`) from user_profile where user_profile.id = glodbver.has_case_id limit 1) as executive_name,concat(`glodbver`.`street_address`,',',`glodbver`.`city`,',',`glodbver`.`pincode`,',',`glodbver`.`state`) as Details");

		$this->db->from('glodbver');

		$this->db->join("candidates_info",'candidates_info.id = glodbver.candsid');

		$this->db->join('glodbver_result','glodbver_result.glodbver_id  = glodbver.id','left');

		$this->db->join("status",'status.id = glodbver_result.verfstatus','left');

		$this->db->where('(glodbver_result.var_filter_status != "Closed"  or  glodbver_result.var_filter_status != "closed")');

		$this->db->where('(glodbver_result.var_filter_status != "NA")');		

		$glodbver  = $this->db->get()->result_array();

		$this->db->select("pcc_com_ref as component_id,'PCC' as component_name,candidates_info.CandidateName,candidates_info.cmp_ref_no,candidates_info.ClientRefNumber,status.status_value,pcc.iniated_date,pcc.id,(select clientname from clients where clients.id = pcc.clientid limit 1) as clientname,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package  where entity_package.id= candidates_info.package limit 1) as package_name,pcc.due_date, pcc.tat_status,(select concat(`user_profile`.`firstname`,' ',`user_profile`.`lastname`) from user_profile where user_profile.id = pcc.has_case_id limit 1) as executive_name,concat(`pcc`.`street_address`,',',`pcc`.`city`,',',`pcc`.`pincode`,',',`pcc`.`state`) as Details");
		$this->db->from('pcc');

		$this->db->join("candidates_info",'candidates_info.id = pcc.candsid');

		$this->db->join('pcc_result','pcc_result.pcc_id  = pcc.id','left');

		$this->db->join("status",'status.id = pcc_result.verfstatus','left');

		$this->db->where('(pcc_result.var_filter_status != "Closed"  or  pcc_result.var_filter_status != "closed")');

		$this->db->where('(pcc_result.var_filter_status != "NA")');		


		$pcc  = $this->db->get()->result_array();

		$this->db->select("identity_com_ref as component_id,'Identity' as component_name,candidates_info.CandidateName,candidates_info.cmp_ref_no,candidates_info.ClientRefNumber,status.status_value,identity.iniated_date,identity.id, (select clientname from clients where clients.id = identity.clientid limit 1) as clientname,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name, (select entity_package_name from entity_package  where entity_package.id= candidates_info.package limit 1) as package_name,identity.due_date,identity.tat_status,(select concat(`user_profile`.`firstname`,' ',`user_profile`.`lastname`) from user_profile where user_profile.id = identity.has_case_id limit 1) as executive_name,identity.doc_submited as Details");

		$this->db->from('identity');

	    $this->db->join("candidates_info",'candidates_info.id = identity.candsid');

		$this->db->join('identity_result','identity_result.identity_id  = identity.id','left');

		$this->db->join("status",'status.id = identity_result.verfstatus','left');

		$this->db->where('(identity_result.var_filter_status != "Closed"  or  identity_result.var_filter_status != "closed")');

		$this->db->where('(identity_result.var_filter_status != "NA")');		

		$identity  = $this->db->get()->result_array();

		$this->db->select("credit_report_com_ref as component_id,'Credit Report' as component_name,candidates_info.CandidateName,candidates_info.cmp_ref_no,candidates_info.ClientRefNumber,status.status_value,credit_report.id,credit_report.iniated_date, (select clientname from clients where clients.id = credit_report.clientid limit 1) as clientname,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name, (select entity_package_name from entity_package  where entity_package.id= candidates_info.package limit 1) as package_name,credit_report.due_date,credit_report.tat_status,(select concat(`user_profile`.`firstname`,' ',`user_profile`.`lastname`) from user_profile where user_profile.id = credit_report.has_case_id limit 1) as executive_name,credit_report.doc_submited as Details");

		$this->db->from('credit_report');

		$this->db->join("candidates_info",'candidates_info.id = credit_report.candsid');

		$this->db->join('credit_report_result','credit_report_result.credit_report_id  = credit_report.id','left');

		$this->db->join("status",'status.id = credit_report_result.verfstatus','left');

		$this->db->where('(credit_report_result.var_filter_status != "Closed"  or  credit_report_result.var_filter_status != "closed")');

		$this->db->where('(credit_report_result.var_filter_status != "NA")');	

		$credit_report  = $this->db->get()->result_array();

	/*	$this->db->select("drug_com_ref as component_id, 'Drugs' as component_name,candidates_info.CandidateName,status.status_value,drug_narcotis.iniated_date, drug_narcotis.id,  (select clientname from clients where clients.id = drug_narcotis.clientid limit 1) as clientname,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name, (select entity_package_name from entity_package  where entity_package.id= candidates_info.package limit 1) as package_name,drug_narcotis.due_date,drug_narcotis.tat_status,(select concat(`user_profile`.`firstname`,' ',`user_profile`.`lastname`) from user_profile where user_profile.id = drug_narcotis.has_case_id limit 1) as executive_name");

		$this->db->from('drug_narcotis');

		$this->db->join("candidates_info",'candidates_info.id = drug_narcotis.candsid');

		$this->db->join('drug_narcotis_result','drug_narcotis_result.drug_narcotis_id   = drug_narcotis.id','left');

		$this->db->join("status",'status.id = drug_narcotis_result.verfstatus','left');

		$this->db->where('(drug_narcotis_result.var_filter_status != "Closed"  or  drug_narcotis_result.var_filter_status != "closed")');

		$drug_narcotis  = $this->db->get()->result_array();
*/
		
		return array_merge($empver,$addrver,$education,$reference,$court,$glodbver,$pcc,$identity,$credit_report);
	}
  

    public function get_candidate_record_for_axis($where_array)
    {
      
        $this->db->select("candidates_info.id,candidates_info.cmp_ref_no,candidates_info.branch_name,candidates_info.ClientRefNumber,candidates_info.CandidateName,candidates_info.caserecddate,candidates_info.Location,candidates_info.Department,candidates_info.overallclosuredate,candidates_info.due_date_candidate,candidates_info.tat_status_candidate,clients.clientname,status.status_value,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,clients_details.component_id");


        $this->db->from('candidates_info');

        $this->db->join("status",'status.id = candidates_info.overallstatus');

     	$this->db->join("clients",'clients.id = candidates_info.clientid');

     	$this->db->join("clients_details",'clients_details.tbl_clients_id = candidates_info.clientid AND clients_details.entity = candidates_info.entity AND clients_details.package = candidates_info.package');

        $this->db->where($where_array);

        $this->db->where('candidates_info.clientid',16);

        $this->db->where('candidates_info.entity', 31);
         
        $this->db->where('candidates_info.package', 32);


        $this->db->order_by('candidates_info.id',"DESC");

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array(); 
 

    }


    public function get_candidate_record_for_axis_new($where_array)
    {
      
        $this->db->select("candidates_info.id,candidates_info.cmp_ref_no,candidates_info.ClientRefNumber,candidates_info.CandidateName,candidates_info.overallclosuredate,clients.clientname,status.status_value,clients_details.component_id,candidates_info.branch_name");


        $this->db->from('candidates_info');

        $this->db->join("status",'status.id = candidates_info.overallstatus');

     	$this->db->join("clients",'clients.id = candidates_info.clientid');

     	$this->db->join("clients_details",'clients_details.tbl_clients_id = candidates_info.clientid AND clients_details.entity = candidates_info.entity AND clients_details.package = candidates_info.package');

        $this->db->where($where_array);

        $this->db->where('candidates_info.clientid',16);

        $this->db->where('candidates_info.entity', 31);
         
        $this->db->where('candidates_info.package', 32);


        $this->db->order_by('candidates_info.id',"DESC");

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array(); 
 

    }

    public function get_candidate_record_for_axis_ikya($where_array)
    {
      
        $this->db->select("candidates_info.id,candidates_info.cmp_ref_no,candidates_info.branch_name,candidates_info.ClientRefNumber,candidates_info.CandidateName,candidates_info.caserecddate,candidates_info.Location,candidates_info.Department,candidates_info.overallclosuredate,candidates_info.due_date_candidate,candidates_info.DateofJoining,
        	candidates_info.tat_status_candidate,clients.clientname,status.status_value,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,clients_details.component_id");


        $this->db->from('candidates_info');

        $this->db->join("status",'status.id = candidates_info.overallstatus');

     	$this->db->join("clients",'clients.id = candidates_info.clientid');

     	$this->db->join("clients_details",'clients_details.tbl_clients_id = candidates_info.clientid AND clients_details.entity = candidates_info.entity AND clients_details.package = candidates_info.package');

        $this->db->where($where_array);

        $this->db->where('candidates_info.clientid',17);

        $this->db->where('candidates_info.entity', 35);
         
        $this->db->where('candidates_info.package', 36);


        $this->db->order_by('candidates_info.id',"DESC");

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array(); 
 
    }

    public function get_candidate_record_for_axis_ikya_new($where_array)
    {
      
        $this->db->select("candidates_info.id,candidates_info.cmp_ref_no,candidates_info.ClientRefNumber,candidates_info.CandidateName,candidates_info.overallclosuredate,clients.clientname,status.status_value,clients_details.component_id,candidates_info.branch_name");


        $this->db->from('candidates_info');

        $this->db->join("status",'status.id = candidates_info.overallstatus');

     	$this->db->join("clients",'clients.id = candidates_info.clientid');

     	$this->db->join("clients_details",'clients_details.tbl_clients_id = candidates_info.clientid AND clients_details.entity = candidates_info.entity AND clients_details.package = candidates_info.package');

        $this->db->where($where_array);

        $this->db->where('candidates_info.clientid',17);

        $this->db->where('candidates_info.entity', 35);
         
        $this->db->where('candidates_info.package', 36);


        $this->db->order_by('candidates_info.id',"DESC");

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array(); 
 
    }


    public function get_candidate_record_for_axis_teamlease($where_array)
    {
      
        $this->db->select("candidates_info.id,candidates_info.cmp_ref_no,candidates_info.branch_name,candidates_info.ClientRefNumber,candidates_info.CandidateName,candidates_info.caserecddate,candidates_info.Location,candidates_info.Department,candidates_info.overallclosuredate,candidates_info.due_date_candidate, candidates_info.tat_status_candidate,candidates_info.DateofJoining,clients.clientname,status.status_value,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,clients_details.component_id");


        $this->db->from('candidates_info');

        $this->db->join("status",'status.id = candidates_info.overallstatus');

     	$this->db->join("clients",'clients.id = candidates_info.clientid');

     	$this->db->join("clients_details",'clients_details.tbl_clients_id = candidates_info.clientid AND clients_details.entity = candidates_info.entity AND clients_details.package = candidates_info.package');

        $this->db->where($where_array);

        $this->db->where('candidates_info.clientid',18);

        $this->db->where('candidates_info.entity', 37);
         
        $this->db->where('candidates_info.package', 38);


        $this->db->order_by('candidates_info.id',"DESC");

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array(); 
 
    }

    public function get_addres_ver_status($clientid)
	{
		$this->db->select("candidates_info.overallstatus,status.status_value,ev1.closuredate,addrver.address as address,addrver.id");

		$this->db->from('candidates_info');

		$this->db->join("addrver",'candidates_info.id = addrver.candsid');

		$this->db->join("clients",'(clients.id = candidates_info.clientid)');

		$this->db->join("addrverres as ev1",'ev1.addrverid = addrver.id','left');

		$this->db->join("addrverres as ev2",'(ev2.addrverid = addrver.id and ev1.id < ev2.id)','left');

		$this->db->join("addrver_insuff as ai1",'ai1.addrverid = addrver.id','left');


		$this->db->join("status",'(status.id = ev1.verfstatus)');


		$this->db->where('ev2.verfstatus is null');
		
		if($clientid)
		{
			$this->db->where($clientid);
		}
		
		$this->db->order_by('addrver.id', 'desc');

		$this->db->limit(1);

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

    public function get_addres_ver_status_new($clientid)
	{
		$this->db->select("candidates_info.overallstatus,status.status_value,ev1.closuredate,addrver.address as address,addrver.city,addrver.state,addrver.pincode,addrver.id");

		$this->db->from('candidates_info');

		$this->db->join("addrver",'candidates_info.id = addrver.candsid');

		$this->db->join("clients",'(clients.id = candidates_info.clientid)');

		$this->db->join("addrverres as ev1",'ev1.addrverid = addrver.id','left');

		$this->db->join("addrverres as ev2",'(ev2.addrverid = addrver.id and ev1.id < ev2.id)','left');

		$this->db->join("addrver_insuff as ai1",'ai1.addrverid = addrver.id','left');


		$this->db->join("status",'(status.id = ev1.verfstatus)');


		$this->db->where('ev2.verfstatus is null');
		
		if($clientid)
		{
			$this->db->where($clientid);
		}
		
		$this->db->order_by('addrver.id', 'desc');

		$this->db->limit(1);

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function  get_address_insuff_details($where_array)
	{

		$this->db->select("ai1.insuff_raised_date,insuff_raise_remark,ai1.insuff_clear_date");

		$this->db->from('addrver');

		$this->db->join("addrver_insuff as ai1",'ai1.addrverid = addrver.id','left');

		if($where_array)
		{
			$this->db->where($where_array);
		}
		
		$this->db->order_by('ai1.id', 'asc');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();

	}

    public function get_empver_ver_status($clientid)
	{
		$this->db->select("candidates_info.overallstatus,status.status_value,ev1.closuredate,ev1.remarks,ev1.verifiers_email_id,(select coname from company_database where company_database.id = empver.nameofthecompany limit 1) as coname,empver.id,empver.empfrom,empver.empto,empver.designation,empver.empid,ev1.verfname");

		$this->db->from('candidates_info');

		$this->db->join("empver",'candidates_info.id = empver.candsid');

		$this->db->join("clients",'(clients.id = candidates_info.clientid)');

		$this->db->join("empverres as ev1",'ev1.empverid = empver.id','left');

		$this->db->join("empverres as ev2",'(ev2.empverid = empver.id and ev1.id < ev2.id)','left');

		$this->db->join("status",'(status.id = ev1.verfstatus)');


		$this->db->where('ev2.verfstatus is null');
		
		if($clientid)
		{
			$this->db->where($clientid);
		}
		
		$this->db->order_by('empver.id', 'desc');
		
        $this->db->limit(1);


		$result = $this->db->get();

		
		record_db_error($this->db->last_query());
		return $result->result_array();
	}

    public function get_empver_ver_status_new($clientid)
	{
		$this->db->select("candidates_info.overallstatus,status.status_value,ev1.remarks,ev1.verifiers_email_id,company_database.coname,company_database.address as company_address,company_database.city as company_city,empver.designation,empver.year_of_experience,empver.empfrom,empver.empto,empver.remuneration,empver.employment_reference_name,empver.employment_reference_no,empver.reasonforleaving,empver.id,empver.employment_type,empver.empid,ev1.verfname,empver.r_manager_name,empver.r_manager_no,empver.r_manager_designation,empver.r_manager_email,ev1.verfdesgn,ev1.verifiers_contact_no");

		$this->db->from('candidates_info');

		$this->db->join("empver",'candidates_info.id = empver.candsid');

		$this->db->join("clients",'(clients.id = candidates_info.clientid)');

        $this->db->join("company_database",'(company_database.id = empver.nameofthecompany)');

		$this->db->join("empverres as ev1",'ev1.empverid = empver.id','left');

		$this->db->join("empverres as ev2",'(ev2.empverid = empver.id and ev1.id < ev2.id)','left');

		$this->db->join("status",'(status.id = ev1.verfstatus)');


		$this->db->where('ev2.verfstatus is null');
		
		if($clientid)
		{
			$this->db->where($clientid);
		}
		
		$this->db->order_by('empver.id', 'desc');
		
        $this->db->limit(1);


		$result = $this->db->get();

		
		record_db_error($this->db->last_query());
		return $result->result_array();
	}

	public function  get_employment_insuff_details($where_array)
	{
      
		$this->db->select("ei1.insuff_raised_date,ei1.insuff_clear_date,ei1.insuff_raise_remark");

		$this->db->from('empver');

		$this->db->join("empverres_insuff as ei1",'ei1.empverres_id = empver.id','left');

		if($where_array)
		{
			$this->db->where($where_array);
		}
		
		$this->db->order_by('ei1.id', 'asc');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();

	}
	public function  get_employment_discrepancy_details($where_array)
	{
        $this->db->select("el1.remarks");

		$this->db->from('empver');

		$this->db->join("empverres_logs as el1",'el1.empverid = empver.id','left');

		if($where_array)
		{
			$this->db->where($where_array);
		}

		$this->db->where('el1.verfstatus', 19);
		
		$this->db->order_by('el1.closuredate', 'asc');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();

	
    }

    public function  get_address_discrepancy_details($where_array)
	{
        $this->db->select("ar1.remarks");

		$this->db->from('addrver');

		$this->db->join("addrverres_result as ar1",'ar1.addrverid = addrver.id','left');

		if($where_array)
		{
			$this->db->where($where_array);
		}

		$this->db->where('ar1.verfstatus', 19);
		
		$this->db->order_by('ar1.closuredate', 'asc');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();

	
    }

	public function get_candidate_records($where_array)
	{
		$this->db->select("candidates_info.id,candidates_info.cmp_ref_no,candidates_info.caserecddate,candidates_info.overallclosuredate,candidates_info.due_date_candidate,candidates_info.tat_status_candidate,clients.clientname,status.status_value,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name");

		$this->db->from('candidates_info');
		
		$this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("status",'status.id = candidates_info.overallstatus');

	        
        $this->db->where($where_array); 


		$this->db->order_by('candidates_info.id',"DESC");

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}
    
    public function get_address_records($where_array)
	{
		$this->db->select("addrver.id,addrver.add_com_ref,clients.clientname,addrver.iniated_date,addrverres.verfstatus,due_date,tat_status,(select user_name from user_profile where user_profile.id = addrver.has_case_id) as executive_name,(select status_value from status where status.id = addrverres.verfstatus) as status_value,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name");
       
		$this->db->from('addrver');

		$this->db->join("candidates_info",'candidates_info.id = addrver.candsid');

		$this->db->join("clients",'clients.id = addrver.clientid');
		
		$this->db->join("addrverres",'addrverres.addrverid = addrver.id','left');

		$this->db->where($where_array);

		$this->db->order_by('addrver.id',"DESC");

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}
	

	public function get_employment_records($where_array)
	{
		$this->db->select("empver.id,empver.emp_com_ref,clients.clientname,empver.iniated_date,empverres.verfstatus,due_date,tat_status,(select user_name from user_profile where user_profile.id = empver.has_case_id) as executive_name,(select status_value from status where status.id = empverres.verfstatus) as status_value,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name");
       
		$this->db->from('empver');

		$this->db->join("candidates_info",'candidates_info.id = empver.candsid');

		$this->db->join("clients",'clients.id = empver.clientid');
		
		$this->db->join("empverres",'empverres.empverid = empver.id','left');

		$this->db->where($where_array);

		$this->db->order_by('empver.id',"DESC");

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_education_records($where_array)
	{
		$this->db->select("education.id,education.education_com_ref,clients.clientname,education.iniated_date,education_result.verfstatus,due_date,tat_status,(select user_name from user_profile where user_profile.id = education.has_case_id) as executive_name,(select status_value from status where status.id = education_result.verfstatus) as status_value,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name");
       
		$this->db->from('education');

		$this->db->join("candidates_info",'candidates_info.id = education.candsid');

		$this->db->join("clients",'clients.id = education.clientid');
		
		$this->db->join("education_result",'education_result.education_id = education.id','left');

		$this->db->where($where_array);

		$this->db->order_by('education.id',"DESC");

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_reference_records($where_array)
	{
		$this->db->select("reference.id,reference.reference_com_ref,clients.clientname,reference.iniated_date,reference_result.verfstatus,due_date,tat_status,(select user_name from user_profile where user_profile.id = reference.has_case_id) as executive_name,(select status_value from status where status.id = reference_result.verfstatus) as status_value,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name");
       
		$this->db->from('reference');

		$this->db->join("candidates_info",'candidates_info.id = reference.candsid');

		$this->db->join("clients",'clients.id = reference.clientid');
		
		$this->db->join("reference_result",'reference_result.reference_id = reference.id','left');

		$this->db->where($where_array);


		$this->db->order_by('reference.id',"DESC");

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}
	
	public function get_court_records($where_array)
	{
		$this->db->select("courtver.id,courtver.court_com_ref,clients.clientname,courtver.iniated_date,courtver_result.verfstatus,due_date,tat_status,(select user_name from user_profile where user_profile.id = courtver.has_case_id) as executive_name,(select status_value from status where status.id = courtver_result.verfstatus) as status_value,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name");
       
		$this->db->from('courtver');

		$this->db->join("candidates_info",'candidates_info.id = courtver.candsid');

		$this->db->join("clients",'clients.id = courtver.clientid');
		
		$this->db->join("courtver_result",'courtver_result.courtver_id = courtver.id','left');

		$this->db->where($where_array);

		$this->db->order_by('courtver.id',"DESC");

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_global_database_records($where_array)
	{
		$this->db->select("glodbver.id,glodbver.global_com_ref,clients.clientname,glodbver.iniated_date,glodbver_result.verfstatus,due_date,tat_status,(select user_name from user_profile where user_profile.id = glodbver.has_case_id) as executive_name,(select status_value from status where status.id = glodbver_result.verfstatus) as status_value,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name");
       
		$this->db->from('glodbver');

		$this->db->join("candidates_info",'candidates_info.id = glodbver.candsid');

		$this->db->join("clients",'clients.id = glodbver.clientid');
		
		$this->db->join("glodbver_result",'glodbver_result.glodbver_id = glodbver.id','left');

		$this->db->where($where_array);

		$this->db->order_by('glodbver.id',"DESC");

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_pcc_records($where_array)
	{
	    $this->db->select("pcc.id,pcc.pcc_com_ref,clients.clientname,pcc.iniated_date,pcc_result.verfstatus,due_date,tat_status,(select user_name from user_profile where user_profile.id = pcc.has_case_id) as executive_name,(select status_value from status where status.id = pcc_result.verfstatus) as status_value,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name");
       
		$this->db->from('pcc');

		$this->db->join("candidates_info",'candidates_info.id = pcc.candsid');

		$this->db->join("clients",'clients.id = pcc.clientid');
		
		$this->db->join("pcc_result",'pcc_result.pcc_id = pcc.id','left');

		$this->db->where($where_array);

		$this->db->order_by('pcc.id',"DESC");

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_identity_records($where_array)
	{
	    $this->db->select("identity.id,identity.identity_com_ref,clients.clientname,identity.iniated_date,identity_result.verfstatus,due_date,tat_status,(select user_name from user_profile where user_profile.id = identity.has_case_id) as executive_name,(select status_value from status where status.id = identity_result.verfstatus) as status_value,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name");
       
		$this->db->from('identity');

		$this->db->join("candidates_info",'candidates_info.id = identity.candsid');

		$this->db->join("clients",'clients.id = identity.clientid');
		
		$this->db->join("identity_result",'identity_result.identity_id = identity.id','left');

		$this->db->where($where_array);

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_credit_report_records($where_array)
	{
	    $this->db->select("credit_report.id,credit_report.credit_report_com_ref,clients.clientname,credit_report.iniated_date,credit_report_result.verfstatus,due_date,tat_status,(select user_name from user_profile where user_profile.id = credit_report.has_case_id) as executive_name,(select status_value from status where status.id = credit_report_result.verfstatus) as status_value,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name");
       
		$this->db->from('credit_report');

		$this->db->join("candidates_info",'candidates_info.id = credit_report.candsid');

		$this->db->join("clients",'clients.id = credit_report.clientid');
		
		$this->db->join("credit_report_result",'credit_report_result.credit_report_id = credit_report.id','left');

		$this->db->where($where_array);

		$this->db->order_by('credit_report.id',"DESC");

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_drugs_records($where_array)
	{
	    $this->db->select("drug_narcotis.id,drug_narcotis.drug_com_ref,clients.clientname,drug_narcotis.iniated_date,drug_narcotis_result.verfstatus,due_date,tat_status,(select user_name from user_profile where user_profile.id = drug_narcotis.has_case_id) as executive_name,(select status_value from status where status.id = drug_narcotis_result.verfstatus) as status_value,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name");
       
		$this->db->from('drug_narcotis');

		$this->db->join("candidates_info",'candidates_info.id = drug_narcotis.candsid');

		$this->db->join("clients",'clients.id = drug_narcotis.clientid');
		
		$this->db->join("drug_narcotis_result",'drug_narcotis_result.drug_narcotis_id = drug_narcotis.id','left');
        
        $this->db->where($where_array);
         
		$this->db->order_by('drug_narcotis.id',"DESC");

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function select_candidate_deatils($client_id,$entity_id, $package_id)
	{
	    $this->db->select("candidates_info.*");
       
		$this->db->from('candidates_info');

		if(!empty($client_id))
		{
		    $this->db->where('candidates_info.clientid',$client_id);	
		}
        if(!empty($entity_id))
		{
		    $this->db->where('candidates_info.entity',$entity_id);	
		}
        if(!empty($package_id))
		{
		    $this->db->where('candidates_info.package',$package_id);	
		}
		$this->db->where_in('candidates_info.overallstatus',array(3,4,6,7,8));	
        
		$this->db->order_by('candidates_info.id',"DESC");

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function select_candidate_deatils_id($list)
	{
	    $this->db->select("candidates_info.*");
       
		$this->db->from('candidates_info');

		$this->db->where_in('candidates_info.id',$list);	
        
		$this->db->order_by('candidates_info.id',"DESC");

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	
	public function status_count_address($params)
	{     

        $array_condition_wip =  array('11','12','13','14','16','23','26','1');
		$array_condition_insuff =  array('18');
        $array_condition_closed =  array('9','17','19','20','21','22','24','25','27','28');

		$this->db->select("addrver.has_case_id,CASE  WHEN addrverres.verfstatus = 11 THEN 'WIP' WHEN addrverres.verfstatus = 12 THEN 'WIP' WHEN addrverres.verfstatus = 13 THEN 'WIP' WHEN addrverres.verfstatus = 14 THEN 'WIP' WHEN addrverres.verfstatus = 16 THEN 'WIP' WHEN addrverres.verfstatus = 23 THEN 'WIP' WHEN addrverres.verfstatus = 26 THEN 'WIP' WHEN addrverres.verfstatus = 1 THEN 'WIP'  END AS status_value,COUNT(addrverres.verfstatus) as total");

		$this->db->from('addrver');

		$this->db->join("addrverres",'addrverres.addrverid = addrver.id');

	    $this->db->where_in('addrverres.verfstatus',$array_condition_wip);
   
		$this->db->group_by('addrver.has_case_id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$addrver_wip = $result->result_array();


		
		$this->db->select("addrver.has_case_id,CASE  WHEN addrverres.verfstatus = 18 THEN 'Insufficiency' END AS status_value,COUNT(addrverres.verfstatus) as total");

		$this->db->from('addrver');

		$this->db->join("addrverres",'addrverres.addrverid = addrver.id');

	    $this->db->where_in('addrverres.verfstatus',$array_condition_insuff);
   
		$this->db->group_by('addrver.has_case_id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$addrver_insuff = $result->result_array();

		$where_condition = "DATE_FORMAT(`addrverres`.`closuredate`,'%Y-%m-%d') BETWEEN '".$params['year']."-".$params['month']."-01' AND '".$params['year']."-".$params['month']."-31'";

		$this->db->select("addrver.has_case_id,CASE WHEN  addrverres.verfstatus =  9 THEN 'Closed' WHEN addrverres.verfstatus =  17  THEN 'Closed' WHEN addrverres.verfstatus = 19  THEN 'Closed'  WHEN addrverres.verfstatus =  20 THEN 'Closed' WHEN addrverres.verfstatus = 21 THEN 'Closed'  WHEN addrverres.verfstatus = 22 THEN 'Closed' WHEN addrverres.verfstatus = 24 THEN 'Closed' WHEN addrverres.verfstatus = 25 THEN 'Closed' WHEN addrverres.verfstatus = 27 THEN 'Closed' WHEN addrverres.verfstatus = 28 THEN 'Closed'  END AS status_value,COUNT(addrverres.verfstatus) as total");

		$this->db->from('addrver');

		$this->db->join("addrverres",'addrverres.addrverid = addrver.id');

		$this->db->where($where_condition);

        $this->db->where_in('addrverres.verfstatus',$array_condition_closed);

		$this->db->group_by('addrver.has_case_id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$addrver_closed = $result->result_array();

		
		
		$return = array();

		foreach ($addrver_wip as $key => $value) {
			
			$return[strtolower($value['status_value']).'_'.$value['has_case_id']] = $value['total'];

		}

		foreach ($addrver_insuff as $key => $value) {
			
			$return[strtolower($value['status_value']).'_'.$value['has_case_id']] = $value['total'];

		}

		foreach ($addrver_closed as $key => $value) {
			
			$return[strtolower($value['status_value']).'_'.$value['has_case_id']] = $value['total'];

		}
		
        return $return;
	
	}


	public function status_count_employment($params)
	{

	    $array_condition_wip =  array('11','12','13','14','16','23','26','1');
		$array_condition_insuff =  array('18');
        $array_condition_closed =  array('9','17','19','20','21','22','24','25','27','28');

		$this->db->select("empver.has_case_id,CASE  WHEN empverres.verfstatus = 11 THEN 'WIP' WHEN empverres.verfstatus = 12 THEN 'WIP' WHEN empverres.verfstatus = 13 THEN 'WIP' WHEN empverres.verfstatus = 14 THEN 'WIP' WHEN empverres.verfstatus = 16 THEN 'WIP' WHEN empverres.verfstatus = 23 THEN 'WIP' WHEN empverres.verfstatus = 26 THEN 'WIP' WHEN empverres.verfstatus = 1 THEN 'WIP'  END AS status_value,COUNT(empverres.verfstatus) as total");

		$this->db->from('empver');

        $this->db->join("empverres",'empverres.empverid = empver.id');

	    $this->db->where_in('empverres.verfstatus',$array_condition_wip);
 
        $this->db->group_by('empver.has_case_id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$empver_wip = $result->result_array();


		$this->db->select("empver.has_case_id,CASE  WHEN empverres.verfstatus = 18 THEN 'Insufficiency'   END AS status_value,COUNT(empverres.verfstatus) as total");

		$this->db->from('empver');

        $this->db->join("empverres",'empverres.empverid = empver.id');
    
	    $this->db->where_in('empverres.verfstatus',$array_condition_insuff);
 
        $this->db->group_by('empver.has_case_id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$empver_insuff = $result->result_array();

		$where_condition = "DATE_FORMAT(`empverres`.`closuredate`,'%Y-%m-%d') BETWEEN '".$params['year']."-".$params['month']."-01' AND '".$params['year']."-".$params['month']."-31'";


		$this->db->select("empver.has_case_id,CASE WHEN  empverres.verfstatus =  9 THEN 'Closed' WHEN empverres.verfstatus =  17  THEN 'Closed' WHEN empverres.verfstatus = 19  THEN 'Closed'  WHEN empverres.verfstatus =  20 THEN 'Closed' WHEN empverres.verfstatus = 21 THEN 'Closed'  WHEN empverres.verfstatus = 22 THEN 'Closed' WHEN empverres.verfstatus = 24 THEN 'Closed' WHEN empverres.verfstatus = 25 THEN 'Closed' WHEN empverres.verfstatus = 27 THEN 'Closed' WHEN empverres.verfstatus = 28 THEN 'Closed'  END AS status_value,COUNT(empverres.verfstatus) as total");

		$this->db->from('empver');

		$this->db->join("empverres",'empverres.empverid = empver.id');

		$this->db->where($where_condition);
		
        $this->db->where_in('empverres.verfstatus',$array_condition_closed);

		$this->db->group_by('empver.has_case_id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$empver_closed = $result->result_array();

		$return = array();

		foreach ($empver_wip as $key => $value) {
			
			$return[strtolower($value['status_value']).'_'.$value['has_case_id']] = $value['total'];

		}

		foreach ($empver_insuff as $key => $value) {
			
			$return[strtolower($value['status_value']).'_'.$value['has_case_id']] = $value['total'];


		}

		foreach ($empver_closed as $key => $value) {
			
			$return[strtolower($value['status_value']).'_'.$value['has_case_id']] = $value['total'];


		}

        return $return;
	
	}

    public function status_count_education($params)
	{

	    $array_condition_wip =  array('11','12','13','14','16','23','26','1');
		$array_condition_insuff =  array('18');
        $array_condition_closed =  array('9','17','19','20','21','22','24','25','27','28');

		$this->db->select("education.has_case_id,CASE  WHEN education_result.verfstatus = 11 THEN 'WIP' WHEN education_result.verfstatus = 12 THEN 'WIP' WHEN education_result.verfstatus = 13 THEN 'WIP' WHEN education_result.verfstatus = 14 THEN 'WIP' WHEN education_result.verfstatus = 16 THEN 'WIP' WHEN education_result.verfstatus = 23 THEN 'WIP' WHEN education_result.verfstatus = 26 THEN 'WIP' WHEN education_result.verfstatus = 1 THEN 'WIP'  END AS status_value,COUNT(education_result.verfstatus) as total");

		$this->db->from('education');

        $this->db->join("education_result",'education_result.education_id = education.id');

	    $this->db->where_in('education_result.verfstatus',$array_condition_wip);
 
        $this->db->group_by('education.has_case_id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$education_wip = $result->result_array();


		$this->db->select("education.has_case_id,CASE  WHEN education_result.verfstatus = 18 THEN 'Insufficiency'   END AS status_value,COUNT(education_result.verfstatus) as total");

		$this->db->from('education');

        $this->db->join("education_result",'education_result.education_id = education.id');
    
	    $this->db->where_in('education_result.verfstatus',$array_condition_insuff);
 
        $this->db->group_by('education.has_case_id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$education_insuff = $result->result_array();

		$where_condition = "DATE_FORMAT(`education_result`.`closuredate`,'%Y-%m-%d') BETWEEN '".$params['year']."-".$params['month']."-01' AND '".$params['year']."-".$params['month']."-31'";

		$this->db->select("education.has_case_id,CASE WHEN  education_result.verfstatus =  9 THEN 'Closed' WHEN education_result.verfstatus =  17  THEN 'Closed' WHEN education_result.verfstatus = 19  THEN 'Closed'  WHEN education_result.verfstatus =  20 THEN 'Closed' WHEN education_result.verfstatus = 21 THEN 'Closed'  WHEN education_result.verfstatus = 22 THEN 'Closed' WHEN education_result.verfstatus = 24 THEN 'Closed' WHEN education_result.verfstatus = 25 THEN 'Closed' WHEN education_result.verfstatus = 27 THEN 'Closed' WHEN education_result.verfstatus = 28 THEN 'Closed'  END AS status_value,COUNT(education_result.verfstatus) as total");

		$this->db->from('education');

		$this->db->join("education_result",'education_result.education_id = education.id');

		$this->db->where($where_condition);

        $this->db->where_in('education_result.verfstatus',$array_condition_closed);

		$this->db->group_by('education.has_case_id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$education_closed = $result->result_array();

		$return = array();

		foreach ($education_wip as $key => $value) {
			
			$return[strtolower($value['status_value']).'_'.$value['has_case_id']] = $value['total'];

		}

		foreach ($education_insuff as $key => $value) {
			
			$return[strtolower($value['status_value']).'_'.$value['has_case_id']] = $value['total'];


		}

		foreach ($education_closed as $key => $value) {
			
			$return[strtolower($value['status_value']).'_'.$value['has_case_id']] = $value['total'];


		}

        return $return;
	
	}


	public function status_count_reference($params)
	{

	    $array_condition_wip =  array('11','12','13','14','16','23','26','1');
		$array_condition_insuff =  array('18');
        $array_condition_closed =  array('9','17','19','20','21','22','24','25','27','28');

		$this->db->select("reference.has_case_id,CASE  WHEN reference_result.verfstatus = 11 THEN 'WIP' WHEN reference_result.verfstatus = 12 THEN 'WIP' WHEN reference_result.verfstatus = 13 THEN 'WIP' WHEN reference_result.verfstatus = 14 THEN 'WIP' WHEN reference_result.verfstatus = 16 THEN 'WIP' WHEN reference_result.verfstatus = 23 THEN 'WIP' WHEN reference_result.verfstatus = 26 THEN 'WIP' WHEN reference_result.verfstatus = 1 THEN 'WIP'  END AS status_value,COUNT(reference_result.verfstatus) as total");

		$this->db->from('reference');

        $this->db->join("reference_result",'reference_result.reference_id = reference.id');

	    $this->db->where_in('reference_result.verfstatus',$array_condition_wip);
 
        $this->db->group_by('reference.has_case_id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$reference_wip = $result->result_array();


		$this->db->select("reference.has_case_id,CASE  WHEN reference_result.verfstatus = 18 THEN 'Insufficiency'   END AS status_value,COUNT(reference_result.verfstatus) as total");

		$this->db->from('reference');

        $this->db->join("reference_result",'reference_result.reference_id = reference.id');
    
	    $this->db->where_in('reference_result.verfstatus',$array_condition_insuff);
 
        $this->db->group_by('reference.has_case_id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$reference_insuff = $result->result_array();


		$where_condition = "DATE_FORMAT(`reference_result`.`closuredate`,'%Y-%m-%d') BETWEEN '".$params['year']."-".$params['month']."-01' AND '".$params['year']."-".$params['month']."-31'";

		$this->db->select("reference.has_case_id,CASE WHEN  reference_result.verfstatus =  9 THEN 'Closed' WHEN reference_result.verfstatus =  17  THEN 'Closed' WHEN reference_result.verfstatus = 19  THEN 'Closed'  WHEN reference_result.verfstatus =  20 THEN 'Closed' WHEN reference_result.verfstatus = 21 THEN 'Closed'  WHEN reference_result.verfstatus = 22 THEN 'Closed' WHEN reference_result.verfstatus = 24 THEN 'Closed' WHEN reference_result.verfstatus = 25 THEN 'Closed' WHEN reference_result.verfstatus = 27 THEN 'Closed' WHEN reference_result.verfstatus = 28 THEN 'Closed'  END AS status_value,COUNT(reference_result.verfstatus) as total");

		$this->db->from('reference');

		$this->db->join("reference_result",'reference_result.reference_id = reference.id');

		$this->db->where($where_condition);

        $this->db->where_in('reference_result.verfstatus',$array_condition_closed);

		$this->db->group_by('reference.has_case_id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$reference_closed = $result->result_array();

		$return = array();

		foreach ($reference_wip as $key => $value) {
			
			$return[strtolower($value['status_value']).'_'.$value['has_case_id']] = $value['total'];

		}

		foreach ($reference_insuff as $key => $value) {
			
			$return[strtolower($value['status_value']).'_'.$value['has_case_id']] = $value['total'];


		}

		foreach ($reference_closed as $key => $value) {
			
			$return[strtolower($value['status_value']).'_'.$value['has_case_id']] = $value['total'];


		}

        return $return;
	
	}

   
	public function status_count_court($params)
	{

	    $array_condition_wip =  array('11','12','13','14','16','23','26','1');
		$array_condition_insuff =  array('18');
        $array_condition_closed =  array('9','17','19','20','21','22','24','25','27','28');

		$this->db->select("courtver.has_case_id,CASE  WHEN courtver_result.verfstatus = 11 THEN 'WIP' WHEN courtver_result.verfstatus = 12 THEN 'WIP' WHEN courtver_result.verfstatus = 13 THEN 'WIP' WHEN courtver_result.verfstatus = 14 THEN 'WIP' WHEN courtver_result.verfstatus = 16 THEN 'WIP' WHEN courtver_result.verfstatus = 23 THEN 'WIP' WHEN courtver_result.verfstatus = 26 THEN 'WIP' WHEN courtver_result.verfstatus = 1 THEN 'WIP'  END AS status_value,COUNT(courtver_result.verfstatus) as total");

		$this->db->from('courtver');

        $this->db->join("courtver_result",'courtver_result.courtver_id = courtver.id');

	    $this->db->where_in('courtver_result.verfstatus',$array_condition_wip);
 
        $this->db->group_by('courtver.has_case_id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$court_wip = $result->result_array();


		$this->db->select("courtver.has_case_id,CASE  WHEN courtver_result.verfstatus = 18 THEN 'Insufficiency'   END AS status_value,COUNT(courtver_result.verfstatus) as total");

		$this->db->from('courtver');

        $this->db->join("courtver_result",'courtver_result.courtver_id = courtver.id');
    
	    $this->db->where_in('courtver_result.verfstatus',$array_condition_insuff);
 
        $this->db->group_by('courtver.has_case_id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$court_insuff = $result->result_array();


		$where_condition = "DATE_FORMAT(`courtver_result`.`closuredate`,'%Y-%m-%d') BETWEEN '".$params['year']."-".$params['month']."-01' AND '".$params['year']."-".$params['month']."-31'";

		$this->db->select("courtver.has_case_id,CASE WHEN  courtver_result.verfstatus =  9 THEN 'Closed' WHEN courtver_result.verfstatus =  17  THEN 'Closed' WHEN courtver_result.verfstatus = 19  THEN 'Closed'  WHEN courtver_result.verfstatus =  20 THEN 'Closed' WHEN courtver_result.verfstatus = 21 THEN 'Closed'  WHEN courtver_result.verfstatus = 22 THEN 'Closed' WHEN courtver_result.verfstatus = 24 THEN 'Closed' WHEN courtver_result.verfstatus = 25 THEN 'Closed' WHEN courtver_result.verfstatus = 27 THEN 'Closed' WHEN courtver_result.verfstatus = 28 THEN 'Closed'  END AS status_value,COUNT(courtver_result.verfstatus) as total");

		$this->db->from('courtver');

		$this->db->join("courtver_result",'courtver_result.courtver_id = courtver.id');

		$this->db->where($where_condition);

        $this->db->where_in('courtver_result.verfstatus',$array_condition_closed);

		$this->db->group_by('courtver.has_case_id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$court_closed = $result->result_array();

		$return = array();

		foreach ($court_wip as $key => $value) {
			
			$return[strtolower($value['status_value']).'_'.$value['has_case_id']] = $value['total'];

		}

		foreach ($court_insuff as $key => $value) {
			
			$return[strtolower($value['status_value']).'_'.$value['has_case_id']] = $value['total'];


		}

		foreach ($court_closed as $key => $value) {
			
			$return[strtolower($value['status_value']).'_'.$value['has_case_id']] = $value['total'];


		}

        return $return;
	
	}

	public function status_count_global_database($params)
	{

	    $array_condition_wip =  array('11','12','13','14','16','23','26','1');
		$array_condition_insuff =  array('18');
        $array_condition_closed =  array('9','17','19','20','21','22','24','25','27','28');

		$this->db->select("glodbver.has_case_id,CASE  WHEN glodbver_result.verfstatus = 11 THEN 'WIP' WHEN glodbver_result.verfstatus = 12 THEN 'WIP' WHEN glodbver_result.verfstatus = 13 THEN 'WIP' WHEN glodbver_result.verfstatus = 14 THEN 'WIP' WHEN glodbver_result.verfstatus = 16 THEN 'WIP' WHEN glodbver_result.verfstatus = 23 THEN 'WIP' WHEN glodbver_result.verfstatus = 26 THEN 'WIP' WHEN glodbver_result.verfstatus = 1 THEN 'WIP'  END AS status_value,COUNT(glodbver_result.verfstatus) as total");

		$this->db->from('glodbver');

        $this->db->join("glodbver_result",'glodbver_result.glodbver_id = glodbver.id');

	    $this->db->where_in('glodbver_result.verfstatus',$array_condition_wip);
 
        $this->db->group_by('glodbver.has_case_id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$global_wip = $result->result_array();


		$this->db->select("glodbver.has_case_id,CASE  WHEN glodbver_result.verfstatus = 18 THEN 'Insufficiency'   END AS status_value,COUNT(glodbver_result.verfstatus) as total");

		$this->db->from('glodbver');

        $this->db->join("glodbver_result",'glodbver_result.glodbver_id = glodbver.id');
    
	    $this->db->where_in('glodbver_result.verfstatus',$array_condition_insuff);
 
        $this->db->group_by('glodbver.has_case_id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$global_insuff = $result->result_array();

		$where_condition = "DATE_FORMAT(`glodbver_result`.`closuredate`,'%Y-%m-%d') BETWEEN '".$params['year']."-".$params['month']."-01' AND '".$params['year']."-".$params['month']."-31'";


		$this->db->select("glodbver.has_case_id,CASE WHEN  glodbver_result.verfstatus =  9 THEN 'Closed' WHEN glodbver_result.verfstatus =  17  THEN 'Closed' WHEN glodbver_result.verfstatus = 19  THEN 'Closed'  WHEN glodbver_result.verfstatus =  20 THEN 'Closed' WHEN glodbver_result.verfstatus = 21 THEN 'Closed'  WHEN glodbver_result.verfstatus = 22 THEN 'Closed' WHEN glodbver_result.verfstatus = 24 THEN 'Closed' WHEN glodbver_result.verfstatus = 25 THEN 'Closed' WHEN glodbver_result.verfstatus = 27 THEN 'Closed' WHEN glodbver_result.verfstatus = 28 THEN 'Closed'  END AS status_value,COUNT(glodbver_result.verfstatus) as total");

		$this->db->from('glodbver');

		$this->db->join("glodbver_result",'glodbver_result.glodbver_id = glodbver.id');

		$this->db->where($where_condition);

        $this->db->where_in('glodbver_result.verfstatus',$array_condition_closed);

		$this->db->group_by('glodbver.has_case_id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$global_closed = $result->result_array();

		$return = array();

		foreach ($global_wip as $key => $value) {
			
			$return[strtolower($value['status_value']).'_'.$value['has_case_id']] = $value['total'];

		}

		foreach ($global_insuff as $key => $value) {
			
			$return[strtolower($value['status_value']).'_'.$value['has_case_id']] = $value['total'];


		}

		foreach ($global_closed as $key => $value) {
			
			$return[strtolower($value['status_value']).'_'.$value['has_case_id']] = $value['total'];


		}

        return $return;
	
	}

	public function status_count_pcc($params)
	{

	    $array_condition_wip =  array('11','12','13','14','16','23','26','1');
		$array_condition_insuff =  array('18');
        $array_condition_closed =  array('9','17','19','20','21','22','24','25','27','28');

		$this->db->select("pcc.has_case_id,CASE  WHEN pcc_result.verfstatus = 11 THEN 'WIP' WHEN pcc_result.verfstatus = 12 THEN 'WIP' WHEN pcc_result.verfstatus = 13 THEN 'WIP' WHEN pcc_result.verfstatus = 14 THEN 'WIP' WHEN pcc_result.verfstatus = 16 THEN 'WIP' WHEN pcc_result.verfstatus = 23 THEN 'WIP' WHEN pcc_result.verfstatus = 26 THEN 'WIP' WHEN pcc_result.verfstatus = 1 THEN 'WIP'  END AS status_value,COUNT(pcc_result.verfstatus) as total");

		$this->db->from('pcc');

        $this->db->join("pcc_result",'pcc_result.pcc_id = pcc.id');

	    $this->db->where_in('pcc_result.verfstatus',$array_condition_wip);
 
        $this->db->group_by('pcc.has_case_id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$pcc_wip = $result->result_array();


		$this->db->select("pcc.has_case_id,CASE  WHEN pcc_result.verfstatus = 18 THEN 'Insufficiency'   END AS status_value,COUNT(pcc_result.verfstatus) as total");

		$this->db->from('pcc');

        $this->db->join("pcc_result",'pcc_result.pcc_id = pcc.id');
    
	    $this->db->where_in('pcc_result.verfstatus',$array_condition_insuff);
 
        $this->db->group_by('pcc.has_case_id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$pcc_insuff = $result->result_array();

		$where_condition = "DATE_FORMAT(`pcc_result`.`closuredate`,'%Y-%m-%d') BETWEEN '".$params['year']."-".$params['month']."-01' AND '".$params['year']."-".$params['month']."-31'";

		$this->db->select("pcc.has_case_id,CASE WHEN  pcc_result.verfstatus =  9 THEN 'Closed' WHEN pcc_result.verfstatus =  17  THEN 'Closed' WHEN pcc_result.verfstatus = 19  THEN 'Closed'  WHEN pcc_result.verfstatus =  20 THEN 'Closed' WHEN pcc_result.verfstatus = 21 THEN 'Closed'  WHEN pcc_result.verfstatus = 22 THEN 'Closed' WHEN pcc_result.verfstatus = 24 THEN 'Closed' WHEN pcc_result.verfstatus = 25 THEN 'Closed' WHEN pcc_result.verfstatus = 27 THEN 'Closed' WHEN pcc_result.verfstatus = 28 THEN 'Closed'  END AS status_value,COUNT(pcc_result.verfstatus) as total");

		$this->db->from('pcc');

		$this->db->join("pcc_result",'pcc_result.pcc_id = pcc.id');

		$this->db->where($where_condition);

        $this->db->where_in('pcc_result.verfstatus',$array_condition_closed);

		$this->db->group_by('pcc.has_case_id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$pcc_closed = $result->result_array();

		$return = array();

		foreach ($pcc_wip as $key => $value) {
			
			$return[strtolower($value['status_value']).'_'.$value['has_case_id']] = $value['total'];

		}

		foreach ($pcc_insuff as $key => $value) {
			
			$return[strtolower($value['status_value']).'_'.$value['has_case_id']] = $value['total'];


		}

		foreach ($pcc_closed as $key => $value) {
			
			$return[strtolower($value['status_value']).'_'.$value['has_case_id']] = $value['total'];


		}

        return $return;
	
	}
     
	public function status_count_identity($params)
	{

	    $array_condition_wip =  array('11','12','13','14','16','23','26','1');
		$array_condition_insuff =  array('18');
        $array_condition_closed =  array('9','17','19','20','21','22','24','25','27','28');

		$this->db->select("identity.has_case_id,CASE  WHEN identity_result.verfstatus = 11 THEN 'WIP' WHEN identity_result.verfstatus = 12 THEN 'WIP' WHEN identity_result.verfstatus = 13 THEN 'WIP' WHEN identity_result.verfstatus = 14 THEN 'WIP' WHEN identity_result.verfstatus = 16 THEN 'WIP' WHEN identity_result.verfstatus = 23 THEN 'WIP' WHEN identity_result.verfstatus = 26 THEN 'WIP' WHEN identity_result.verfstatus = 1 THEN 'WIP'  END AS status_value,COUNT(identity_result.verfstatus) as total");

		$this->db->from('identity');

        $this->db->join("identity_result",'identity_result.identity_id = identity.id');

	    $this->db->where_in('identity_result.verfstatus',$array_condition_wip);
 
        $this->db->group_by('identity.has_case_id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$identity_wip = $result->result_array();


		$this->db->select("identity.has_case_id,CASE  WHEN identity_result.verfstatus = 18 THEN 'Insufficiency'   END AS status_value,COUNT(identity_result.verfstatus) as total");

		$this->db->from('identity');

        $this->db->join("identity_result",'identity_result.identity_id = identity.id');
    
	    $this->db->where_in('identity_result.verfstatus',$array_condition_insuff);
 
        $this->db->group_by('identity.has_case_id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$identity_insuff = $result->result_array();

		$where_condition = "DATE_FORMAT(`identity_result`.`closuredate`,'%Y-%m-%d') BETWEEN '".$params['year']."-".$params['month']."-01' AND '".$params['year']."-".$params['month']."-31'";

		$this->db->select("identity.has_case_id,CASE WHEN  identity_result.verfstatus =  9 THEN 'Closed' WHEN identity_result.verfstatus =  17  THEN 'Closed' WHEN identity_result.verfstatus = 19  THEN 'Closed'  WHEN identity_result.verfstatus =  20 THEN 'Closed' WHEN identity_result.verfstatus = 21 THEN 'Closed'  WHEN identity_result.verfstatus = 22 THEN 'Closed' WHEN identity_result.verfstatus = 24 THEN 'Closed' WHEN identity_result.verfstatus = 25 THEN 'Closed' WHEN identity_result.verfstatus = 27 THEN 'Closed' WHEN identity_result.verfstatus = 28 THEN 'Closed'  END AS status_value,COUNT(identity_result.verfstatus) as total");

		$this->db->from('identity');

		$this->db->join("identity_result",'identity_result.identity_id = identity.id');

		$this->db->where($where_condition);

        $this->db->where_in('identity_result.verfstatus',$array_condition_closed);

		$this->db->group_by('identity.has_case_id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$identity_closed = $result->result_array();

		$return = array();

		foreach ($identity_wip as $key => $value) {
			
			$return[strtolower($value['status_value']).'_'.$value['has_case_id']] = $value['total'];

		}

		foreach ($identity_insuff as $key => $value) {
			
			$return[strtolower($value['status_value']).'_'.$value['has_case_id']] = $value['total'];


		}

		foreach ($identity_closed as $key => $value) {
			
			$return[strtolower($value['status_value']).'_'.$value['has_case_id']] = $value['total'];


		}

        return $return;
	
	}
	
	public function status_count_credit_report($params)
	{

	    $array_condition_wip =  array('11','12','13','14','16','23','26','1');
		$array_condition_insuff =  array('18');
        $array_condition_closed =  array('9','17','19','20','21','22','24','25','27','28');

		$this->db->select("credit_report.has_case_id,CASE  WHEN credit_report_result.verfstatus = 11 THEN 'WIP' WHEN credit_report_result.verfstatus = 12 THEN 'WIP' WHEN credit_report_result.verfstatus = 13 THEN 'WIP' WHEN credit_report_result.verfstatus = 14 THEN 'WIP' WHEN credit_report_result.verfstatus = 16 THEN 'WIP' WHEN credit_report_result.verfstatus = 23 THEN 'WIP' WHEN credit_report_result.verfstatus = 26 THEN 'WIP' WHEN credit_report_result.verfstatus = 1 THEN 'WIP'  END AS status_value,COUNT(credit_report_result.verfstatus) as total");

		$this->db->from('credit_report');

        $this->db->join("credit_report_result",'credit_report_result.credit_report_id = credit_report.id');

	    $this->db->where_in('credit_report_result.verfstatus',$array_condition_wip);
 
        $this->db->group_by('credit_report.has_case_id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$credit_report_wip = $result->result_array();


		$this->db->select("credit_report.has_case_id,CASE  WHEN credit_report_result.verfstatus = 18 THEN 'Insufficiency'   END AS status_value,COUNT(credit_report_result.verfstatus) as total");

		$this->db->from('credit_report');

        $this->db->join("credit_report_result",'credit_report_result.credit_report_id = credit_report.id');
    
	    $this->db->where_in('credit_report_result.verfstatus',$array_condition_insuff);
 
        $this->db->group_by('credit_report.has_case_id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$credit_report_insuff = $result->result_array();

		$where_condition = "DATE_FORMAT(`credit_report_result`.`closuredate`,'%Y-%m-%d') BETWEEN '".$params['year']."-".$params['month']."-01' AND '".$params['year']."-".$params['month']."-31'";


		$this->db->select("credit_report.has_case_id,CASE WHEN  credit_report_result.verfstatus =  9 THEN 'Closed' WHEN credit_report_result.verfstatus =  17  THEN 'Closed' WHEN credit_report_result.verfstatus = 19  THEN 'Closed'  WHEN credit_report_result.verfstatus =  20 THEN 'Closed' WHEN credit_report_result.verfstatus = 21 THEN 'Closed'  WHEN credit_report_result.verfstatus = 22 THEN 'Closed' WHEN credit_report_result.verfstatus = 24 THEN 'Closed' WHEN credit_report_result.verfstatus = 25 THEN 'Closed' WHEN credit_report_result.verfstatus = 27 THEN 'Closed' WHEN credit_report_result.verfstatus = 28 THEN 'Closed'  END AS status_value,COUNT(credit_report_result.verfstatus) as total");

		$this->db->from('credit_report');

		$this->db->join("credit_report_result",'credit_report_result.credit_report_id = credit_report.id');

		$this->db->where($where_condition);

        $this->db->where_in('credit_report_result.verfstatus',$array_condition_closed);

		$this->db->group_by('credit_report.has_case_id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$credit_report_closed = $result->result_array();

		$return = array();

		foreach ($credit_report_wip as $key => $value) {
			
			$return[strtolower($value['status_value']).'_'.$value['has_case_id']] = $value['total'];

		}

		foreach ($credit_report_insuff as $key => $value) {
			
			$return[strtolower($value['status_value']).'_'.$value['has_case_id']] = $value['total'];


		}

		foreach ($credit_report_closed as $key => $value) {
			
			$return[strtolower($value['status_value']).'_'.$value['has_case_id']] = $value['total'];


		}

        return $return;
	
	}


	public function status_count_drugs($params)
	{

	    $array_condition_wip =  array('11','12','13','14','16','23','26','1');
		$array_condition_insuff =  array('18');
        $array_condition_closed =  array('9','17','19','20','21','22','24','25','27','28');

		$this->db->select("drug_narcotis.has_case_id,CASE  WHEN drug_narcotis_result.verfstatus = 11 THEN 'WIP' WHEN drug_narcotis_result.verfstatus = 12 THEN 'WIP' WHEN drug_narcotis_result.verfstatus = 13 THEN 'WIP' WHEN drug_narcotis_result.verfstatus = 14 THEN 'WIP' WHEN drug_narcotis_result.verfstatus = 16 THEN 'WIP' WHEN drug_narcotis_result.verfstatus = 23 THEN 'WIP' WHEN drug_narcotis_result.verfstatus = 26 THEN 'WIP' WHEN drug_narcotis_result.verfstatus = 1 THEN 'WIP'  END AS status_value,COUNT(drug_narcotis_result.verfstatus) as total");

		$this->db->from('drug_narcotis');

        $this->db->join("drug_narcotis_result",'drug_narcotis_result.drug_narcotis_id = drug_narcotis.id');

	    $this->db->where_in('drug_narcotis_result.verfstatus',$array_condition_wip);
 
        $this->db->group_by('drug_narcotis.has_case_id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$drug_narcotis_wip = $result->result_array();


		$this->db->select("drug_narcotis.has_case_id,CASE  WHEN drug_narcotis_result.verfstatus = 18 THEN 'Insufficiency'   END AS status_value,COUNT(drug_narcotis_result.verfstatus) as total");

		$this->db->from('drug_narcotis');

        $this->db->join("drug_narcotis_result",'drug_narcotis_result.drug_narcotis_id = drug_narcotis.id');
    
	    $this->db->where_in('drug_narcotis_result.verfstatus',$array_condition_insuff);
 
        $this->db->group_by('drug_narcotis.has_case_id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$drug_narcotis_insuff = $result->result_array();

		$where_condition = "DATE_FORMAT(`drug_narcotis_result`.`closuredate`,'%Y-%m-%d') BETWEEN '".$params['year']."-".$params['month']."-01' AND '".$params['year']."-".$params['month']."-31'";


		$this->db->select("drug_narcotis.has_case_id,CASE WHEN  drug_narcotis_result.verfstatus =  9 THEN 'Closed' WHEN drug_narcotis_result.verfstatus =  17  THEN 'Closed' WHEN drug_narcotis_result.verfstatus = 19  THEN 'Closed'  WHEN drug_narcotis_result.verfstatus =  20 THEN 'Closed' WHEN drug_narcotis_result.verfstatus = 21 THEN 'Closed'  WHEN drug_narcotis_result.verfstatus = 22 THEN 'Closed' WHEN drug_narcotis_result.verfstatus = 24 THEN 'Closed' WHEN drug_narcotis_result.verfstatus = 25 THEN 'Closed' WHEN drug_narcotis_result.verfstatus = 27 THEN 'Closed' WHEN drug_narcotis_result.verfstatus = 28 THEN 'Closed'  END AS status_value,COUNT(drug_narcotis_result.verfstatus) as total");

		$this->db->from('drug_narcotis');

		$this->db->join("drug_narcotis_result",'drug_narcotis_result.drug_narcotis_id = drug_narcotis.id');

		$this->db->where($where_condition);

        $this->db->where_in('drug_narcotis_result.verfstatus',$array_condition_closed);

		$this->db->group_by('drug_narcotis.has_case_id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$drug_narcotis_closed = $result->result_array();

		$return = array();

		foreach ($drug_narcotis_wip as $key => $value) {
			
			$return[strtolower($value['status_value']).'_'.$value['has_case_id']] = $value['total'];

		}

		foreach ($drug_narcotis_insuff as $key => $value) {
			
			$return[strtolower($value['status_value']).'_'.$value['has_case_id']] = $value['total'];


		}

		foreach ($drug_narcotis_closed as $key => $value) {
			
			$return[strtolower($value['status_value']).'_'.$value['has_case_id']] = $value['total'];


		}

        return $return;
	
	}
	
	public function  status_count_component($params)
	{
		$array_condition_initiation =  array('1','5','2','3','4','6','7','8');
		$array_condition_wip =  array('1');
		$array_condition_insuff =  array('5');
        $array_condition_closed =  array('2','3','4','6','7','8');

		$where_condition_candidate_initiation = "DATE_FORMAT(`candidates_info`.`caserecddate`,'%Y-%m-%d') BETWEEN '".$params['year']."-".$params['month']."-01' AND '".$params['year']."-".$params['month']."-31'";

		$this->db->select("candidates_info.clientid,CASE  WHEN candidates_info.overallstatus = 1 THEN 'Initiation' WHEN candidates_info.overallstatus = 5 THEN 'Initiation' WHEN candidates_info.overallstatus = 2 THEN 'Initiation' WHEN candidates_info.overallstatus = 3 THEN 'Initiation' WHEN candidates_info.overallstatus = 4 THEN 'Initiation' WHEN candidates_info.overallstatus = 6 THEN 'Initiation' WHEN candidates_info.overallstatus = 7 THEN 'Initiation' WHEN candidates_info.overallstatus = 8 THEN 'Initiation' END AS status_value,COUNT(candidates_info.overallstatus) as total");

		$this->db->from('candidates_info');

		$this->db->where($where_condition_candidate_initiation);

	    $this->db->where_in('candidates_info.overallstatus',$array_condition_initiation);
   
		$this->db->group_by('candidates_info.clientid');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$candidate_initiation = $result->result_array();


	
		$this->db->select("candidates_info.clientid,CASE  WHEN candidates_info.overallstatus = 1 THEN 'WIP'  END AS status_value,COUNT(candidates_info.overallstatus) as total");

		$this->db->from('candidates_info');

	    $this->db->where_in('candidates_info.overallstatus',$array_condition_wip);
   
		$this->db->group_by('candidates_info.clientid');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$candidate_wip = $result->result_array();


		
		$this->db->select("candidates_info.clientid,CASE  WHEN candidates_info.overallstatus = 5 THEN 'Insufficiency' END AS status_value,COUNT(candidates_info.overallstatus) as total");

		$this->db->from('candidates_info');

	    $this->db->where_in('candidates_info.overallstatus',$array_condition_insuff);
   
		$this->db->group_by('candidates_info.clientid');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$candidate_insuff = $result->result_array();

        $where_condition_candidate = "DATE_FORMAT(`candidates_info`.`overallclosuredate`,'%Y-%m-%d') BETWEEN '".$params['year']."-".$params['month']."-01' AND '".$params['year']."-".$params['month']."-31'";


		$this->db->select("candidates_info.clientid,CASE WHEN  candidates_info.overallstatus =  2 THEN 'Closed' WHEN candidates_info.overallstatus =  3  THEN 'Closed' WHEN candidates_info.overallstatus =  4  THEN 'Closed'  WHEN candidates_info.overallstatus =  6 THEN 'Closed' WHEN candidates_info.overallstatus = 7 THEN 'Closed'  WHEN candidates_info.overallstatus = 8 THEN 'Closed'   END AS status_value,COUNT(candidates_info.overallstatus) as total");

		$this->db->from('candidates_info');
         
		$this->db->where($where_condition_candidate);
 
        $this->db->where_in('candidates_info.overallstatus',$array_condition_closed);

		$this->db->group_by('candidates_info.clientid');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$candidate_closed = $result->result_array();

		$return = array();
		

		foreach ($candidate_initiation as $key => $value) {
	
			$return[strtolower($value['status_value']).'_'.$value['clientid']] = $value['total'];

		}
	
		foreach ($candidate_wip as $key => $value) {
		
			$return[strtolower($value['status_value']).'_'.$value['clientid']] = $value['total'];

		}

		foreach ($candidate_insuff as $key => $value) {
		
			$return[strtolower($value['status_value']).'_'.$value['clientid']] = $value['total'];

		}

		foreach ($candidate_closed as $key => $value) {
		
			$return[strtolower($value['status_value']).'_'.$value['clientid']] = $value['total'];

		}
     
		return $return;

	}

	public function  status_count_component_vendor($params)
	{
		$array_address_initiated =  array('wip','candidate shifted','unable to verify','denied verification','resigned','candidate not responding','clear','approve');
		$array_address_wip =  array('wip');
		$array_address_insuff =  array('candidate shifted','unable to verify','denied verification','resigned','candidate not responding');
        $array_address_closed =  array('clear','approve');


        $this->db->select("addrver.vendor_id,year(view_vendor_master_log.created_on) as year, month(view_vendor_master_log.created_on) as month,CASE  WHEN view_vendor_master_log.final_status = 'wip' THEN 'currentwip' END AS status_value,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('addrver');

		$this->db->join("address_vendor_log",'address_vendor_log.case_id = addrver.id');
       
        $this->db->join('view_vendor_master_log','(view_vendor_master_log.case_id = address_vendor_log.id  and component = "addrver" and component_tbl_id = "1")');

		$this->db->where('year(view_vendor_master_log.created_on) <=', $params['year']);

        $this->db->where('month(view_vendor_master_log.created_on) <', $params['month']);

		$this->db->where_in('view_vendor_master_log.final_status',$array_address_wip);
   
		$this->db->group_by('addrver.vendor_id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		$addrver_currentwip = $result->result_array();


		
        $this->db->select("addrver.vendor_id,year(view_vendor_master_log.created_on) as year, month(view_vendor_master_log.created_on) as month,CASE  WHEN view_vendor_master_log.final_status = 'candidate shifted' THEN 'currentinsuff' WHEN view_vendor_master_log.final_status = 'unable to verify' THEN 'currentinsuff' WHEN view_vendor_master_log.final_status = 'denied verification' THEN 'currentinsuff' WHEN view_vendor_master_log.final_status = 'resigned' THEN 'currentinsuff' WHEN view_vendor_master_log.final_status = 'candidate not responding' THEN 'currentinsuff' END AS status_value,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('addrver');

		$this->db->join("address_vendor_log",'address_vendor_log.case_id = addrver.id');
       
        $this->db->join('view_vendor_master_log','(view_vendor_master_log.case_id = address_vendor_log.id  and component = "addrver" and component_tbl_id = "1")');

		$this->db->where('year(view_vendor_master_log.created_on) <=', $params['year']);

        $this->db->where('month(view_vendor_master_log.created_on) <', $params['month']);

		$this->db->where_in('view_vendor_master_log.final_status',$array_address_insuff);
   
		$this->db->group_by('addrver.vendor_id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		$addrver_currentinsuff = $result->result_array();

		

        $this->db->select("addrver.vendor_id,year(view_vendor_master_log.created_on) as year, month(view_vendor_master_log.created_on) as month,CASE  WHEN view_vendor_master_log.final_status = 'wip' THEN 'initiated' WHEN view_vendor_master_log.final_status = 'candidate shifted' THEN 'initiated' WHEN view_vendor_master_log.final_status = 'unable to verify' THEN 'initiated' WHEN view_vendor_master_log.final_status = 'denied verification' THEN 'initiation' WHEN view_vendor_master_log.final_status = 'resigned' THEN 'initiation' WHEN view_vendor_master_log.final_status = 'candidate not responding' THEN 'initiation'  WHEN view_vendor_master_log.final_status = 'clear' THEN 'initiated' WHEN view_vendor_master_log.final_status = 'approve' THEN 'initiated'  END AS status_value,CASE  WHEN day(view_vendor_master_log.created_on) = 1 THEN 'one' WHEN day(view_vendor_master_log.created_on) = 2 THEN 'two' WHEN day(view_vendor_master_log.created_on) = 3 THEN 'three' WHEN day(view_vendor_master_log.created_on) = 4 THEN 'four' WHEN  day(view_vendor_master_log.created_on) = 5 THEN 'five' WHEN day(view_vendor_master_log.created_on) = 6 THEN 'six' WHEN day(view_vendor_master_log.created_on) = 7 THEN 'seven' WHEN day(view_vendor_master_log.created_on) = 8 THEN 'eight' WHEN day(view_vendor_master_log.created_on) = 9 THEN 'nine' WHEN day(view_vendor_master_log.created_on) = 10 THEN 'ten' WHEN day(view_vendor_master_log.created_on) = 11 THEN 'eleven' WHEN day(view_vendor_master_log.created_on) = 12 THEN 'twelve' WHEN day(view_vendor_master_log.created_on) = 13 THEN 'thirteen' WHEN day(view_vendor_master_log.created_on) = 14 THEN 'fourteen' WHEN day(view_vendor_master_log.created_on) = 15 THEN 'fifteen' WHEN day(view_vendor_master_log.created_on) = 16 THEN 'sixteen' WHEN day(view_vendor_master_log.created_on) = 17 THEN 'seventeen' WHEN day(view_vendor_master_log.created_on) = 18 THEN 'eightteen' WHEN day(view_vendor_master_log.created_on) = 19 THEN 'nineteen' WHEN day(view_vendor_master_log.created_on) = 20 THEN 'twenty' WHEN day(view_vendor_master_log.created_on) = 21 THEN 'twentyone'  WHEN day(view_vendor_master_log.created_on) = 22 THEN 'twentytwo' WHEN day(view_vendor_master_log.created_on) = 23 THEN 'twentythree'  WHEN day(view_vendor_master_log.created_on) = 24 THEN 'twentyfour' WHEN day(view_vendor_master_log.created_on) = 25 THEN 'twentyfive' WHEN day(view_vendor_master_log.created_on) = 26 THEN 'twentysix' WHEN day(view_vendor_master_log.created_on) = 27 THEN 'twentyseven' WHEN day(view_vendor_master_log.created_on) = 28 THEN 'twentyeight' WHEN day(view_vendor_master_log.created_on) = 29 THEN 'twentynine' WHEN day(view_vendor_master_log.created_on) = 30 THEN 'thirty' WHEN day(view_vendor_master_log.created_on) = 31 THEN 'thirtyone' END  as day,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('addrver');

		$this->db->join("address_vendor_log",'address_vendor_log.case_id = addrver.id');
       
        $this->db->join('view_vendor_master_log','(view_vendor_master_log.case_id = address_vendor_log.id  and component = "addrver" and component_tbl_id = "1")');

		$this->db->where('year(view_vendor_master_log.created_on)', $params['year']);

        $this->db->where('month(view_vendor_master_log.created_on)', $params['month']);

		$this->db->where_in('view_vendor_master_log.final_status',$array_address_initiated);
   
		$this->db->group_by('addrver.vendor_id,year(view_vendor_master_log.created_on), month(view_vendor_master_log.created_on), day(view_vendor_master_log.created_on)');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$addrver_initiated = $result->result_array();



		$this->db->select("addrver.vendor_id,year(view_vendor_master_log.created_on) as year, month(view_vendor_master_log.created_on) as month,CASE  WHEN view_vendor_master_log.final_status = 'wip' THEN 'WIP'   END AS status_value,CASE  WHEN day(view_vendor_master_log.created_on) = 1 THEN 'one' WHEN day(view_vendor_master_log.created_on) = 2 THEN 'two' WHEN day(view_vendor_master_log.created_on) = 3 THEN 'three' WHEN day(view_vendor_master_log.created_on) = 4 THEN 'four' WHEN  day(view_vendor_master_log.created_on) = 5 THEN 'five' WHEN day(view_vendor_master_log.created_on) = 6 THEN 'six' WHEN day(view_vendor_master_log.created_on) = 7 THEN 'seven' WHEN day(view_vendor_master_log.created_on) = 8 THEN 'eight' WHEN day(view_vendor_master_log.created_on) = 9 THEN 'nine' WHEN day(view_vendor_master_log.created_on) = 10 THEN 'ten' WHEN day(view_vendor_master_log.created_on) = 11 THEN 'eleven' WHEN day(view_vendor_master_log.created_on) = 12 THEN 'twelve' WHEN day(view_vendor_master_log.created_on) = 13 THEN 'thirteen' WHEN day(view_vendor_master_log.created_on) = 14 THEN 'fourteen' WHEN day(view_vendor_master_log.created_on) = 15 THEN 'fifteen' WHEN day(view_vendor_master_log.created_on) = 16 THEN 'sixteen' WHEN day(view_vendor_master_log.created_on) = 17 THEN 'seventeen' WHEN day(view_vendor_master_log.created_on) = 18 THEN 'eightteen' WHEN day(view_vendor_master_log.created_on) = 19 THEN 'nineteen' WHEN day(view_vendor_master_log.created_on) = 20 THEN 'twenty' WHEN day(view_vendor_master_log.created_on) = 21 THEN 'twentyone'  WHEN day(view_vendor_master_log.created_on) = 22 THEN 'twentytwo' WHEN day(view_vendor_master_log.created_on) = 23 THEN 'twentythree'  WHEN day(view_vendor_master_log.created_on) = 24 THEN 'twentyfour' WHEN day(view_vendor_master_log.created_on) = 25 THEN 'twentyfive' WHEN day(view_vendor_master_log.created_on) = 26 THEN 'twentysix' WHEN day(view_vendor_master_log.created_on) = 27 THEN 'twentyseven' WHEN day(view_vendor_master_log.created_on) = 28 THEN 'twentyeight' WHEN day(view_vendor_master_log.created_on) = 29 THEN 'twentynine' WHEN day(view_vendor_master_log.created_on) = 30 THEN 'thirty' WHEN day(view_vendor_master_log.created_on) = 31 THEN 'thirtyone' END  as day,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('addrver');

		$this->db->join("address_vendor_log",'address_vendor_log.case_id = addrver.id');
       
        $this->db->join('view_vendor_master_log','(view_vendor_master_log.case_id = address_vendor_log.id  and component = "addrver" and component_tbl_id = "1")');

		$this->db->where('year(view_vendor_master_log.created_on)', $params['year']);

        $this->db->where('month(view_vendor_master_log.created_on)', $params['month']);

		$this->db->where_in('view_vendor_master_log.final_status',$array_address_wip);
   
		$this->db->group_by('addrver.vendor_id,year(view_vendor_master_log.created_on), month(view_vendor_master_log.created_on), day(view_vendor_master_log.created_on)');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$addrver_wip = $result->result_array();

	
		
		$this->db->select("addrver.vendor_id,year(view_vendor_master_log.created_on) as year, month(view_vendor_master_log.created_on) as month,CASE  WHEN view_vendor_master_log.final_status = 'candidate shifted' THEN 'Insufficiency' WHEN view_vendor_master_log.final_status = 'unable to verify' THEN 'Insufficiency' WHEN view_vendor_master_log.final_status = 'denied verification' THEN 'Insufficiency' WHEN view_vendor_master_log.final_status = 'resigned' THEN 'Insufficiency'  WHEN view_vendor_master_log.final_status = 'candidate not responding' THEN 'Insufficiency'  END AS status_value,CASE  WHEN day(view_vendor_master_log.created_on) = 1 THEN 'one' WHEN day(view_vendor_master_log.created_on) = 2 THEN 'two' WHEN day(view_vendor_master_log.created_on) = 3 THEN 'three' WHEN day(view_vendor_master_log.created_on) = 4 THEN 'four' WHEN  day(view_vendor_master_log.created_on) = 5 THEN 'five' WHEN day(view_vendor_master_log.created_on) = 6 THEN 'six' WHEN day(view_vendor_master_log.created_on) = 7 THEN 'seven' WHEN day(view_vendor_master_log.created_on) = 8 THEN 'eight' WHEN day(view_vendor_master_log.created_on) = 9 THEN 'nine' WHEN day(view_vendor_master_log.created_on) = 10 THEN 'ten' WHEN day(view_vendor_master_log.created_on) = 11 THEN 'eleven' WHEN day(view_vendor_master_log.created_on) = 12 THEN 'twelve' WHEN day(view_vendor_master_log.created_on) = 13 THEN 'thirteen' WHEN day(view_vendor_master_log.created_on) = 14 THEN 'fourteen' WHEN day(view_vendor_master_log.created_on) = 15 THEN 'fifteen' WHEN day(view_vendor_master_log.created_on) = 16 THEN 'sixteen' WHEN day(view_vendor_master_log.created_on) = 17 THEN 'seventeen' WHEN day(view_vendor_master_log.created_on) = 18 THEN 'eightteen' WHEN day(view_vendor_master_log.created_on) = 19 THEN 'nineteen' WHEN day(view_vendor_master_log.created_on) = 20 THEN 'twenty' WHEN day(view_vendor_master_log.created_on) = 21 THEN 'twentyone'  WHEN day(view_vendor_master_log.created_on) = 22 THEN 'twentytwo' WHEN day(view_vendor_master_log.created_on) = 23 THEN 'twentythree'  WHEN day(view_vendor_master_log.created_on) = 24 THEN 'twentyfour' WHEN day(view_vendor_master_log.created_on) = 25 THEN 'twentyfive' WHEN day(view_vendor_master_log.created_on) = 26 THEN 'twentysix' WHEN day(view_vendor_master_log.created_on) = 27 THEN 'twentyseven' WHEN day(view_vendor_master_log.created_on) = 28 THEN 'twentyeight' WHEN day(view_vendor_master_log.created_on) = 29 THEN 'twentynine' WHEN day(view_vendor_master_log.created_on) = 30 THEN 'thirty' WHEN day(view_vendor_master_log.created_on) = 31 THEN 'thirtyone' END  as day,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('addrver');

		$this->db->join("address_vendor_log",'address_vendor_log.case_id = addrver.id');
       
        $this->db->join('view_vendor_master_log','(view_vendor_master_log.case_id = address_vendor_log.id  and component = "addrver" and component_tbl_id = "1")');

		$this->db->where('year(view_vendor_master_log.created_on)', $params['year']);

        $this->db->where('month(view_vendor_master_log.created_on)', $params['month']);


	    $this->db->where_in('view_vendor_master_log.final_status',$array_address_insuff);
   
		$this->db->group_by('addrver.vendor_id,year(view_vendor_master_log.created_on), month(view_vendor_master_log.created_on), day(view_vendor_master_log.created_on)');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$addrver_insuff = $result->result_array();



		$this->db->select("addrver.vendor_id,year(view_vendor_master_log.created_on) as year, month(view_vendor_master_log.created_on) as month,CASE  WHEN view_vendor_master_log.final_status = 'clear' THEN 'Closed' WHEN view_vendor_master_log.final_status = 'approve' THEN 'Closed' END AS status_value,CASE  WHEN day(view_vendor_master_log.created_on) = 1 THEN 'one' WHEN day(view_vendor_master_log.created_on) = 2 THEN 'two' WHEN day(view_vendor_master_log.created_on) = 3 THEN 'three' WHEN day(view_vendor_master_log.created_on) = 4 THEN 'four' WHEN  day(view_vendor_master_log.created_on) = 5 THEN 'five' WHEN day(view_vendor_master_log.created_on) = 6 THEN 'six' WHEN day(view_vendor_master_log.created_on) = 7 THEN 'seven' WHEN day(view_vendor_master_log.created_on) = 8 THEN 'eight' WHEN day(view_vendor_master_log.created_on) = 9 THEN 'nine' WHEN day(view_vendor_master_log.created_on) = 10 THEN 'ten' WHEN day(view_vendor_master_log.created_on) = 11 THEN 'eleven' WHEN day(view_vendor_master_log.created_on) = 12 THEN 'twelve' WHEN day(view_vendor_master_log.created_on) = 13 THEN 'thirteen' WHEN day(view_vendor_master_log.created_on) = 14 THEN 'fourteen' WHEN day(view_vendor_master_log.created_on) = 15 THEN 'fifteen' WHEN day(view_vendor_master_log.created_on) = 16 THEN 'sixteen' WHEN day(view_vendor_master_log.created_on) = 17 THEN 'seventeen' WHEN day(view_vendor_master_log.created_on) = 18 THEN 'eightteen' WHEN day(view_vendor_master_log.created_on) = 19 THEN 'nineteen' WHEN day(view_vendor_master_log.created_on) = 20 THEN 'twenty' WHEN day(view_vendor_master_log.created_on) = 21 THEN 'twentyone'  WHEN day(view_vendor_master_log.created_on) = 22 THEN 'twentytwo' WHEN day(view_vendor_master_log.created_on) = 23 THEN 'twentythree'  WHEN day(view_vendor_master_log.created_on) = 24 THEN 'twentyfour' WHEN day(view_vendor_master_log.created_on) = 25 THEN 'twentyfive' WHEN day(view_vendor_master_log.created_on) = 26 THEN 'twentysix' WHEN day(view_vendor_master_log.created_on) = 27 THEN 'twentyseven' WHEN day(view_vendor_master_log.created_on) = 28 THEN 'twentyeight' WHEN day(view_vendor_master_log.created_on) = 29 THEN 'twentynine' WHEN day(view_vendor_master_log.created_on) = 30 THEN 'thirty' WHEN day(view_vendor_master_log.created_on) = 31 THEN 'thirtyone' END  as day,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('addrver');

		$this->db->join("address_vendor_log",'address_vendor_log.case_id = addrver.id');
       
        $this->db->join('view_vendor_master_log','(view_vendor_master_log.case_id = address_vendor_log.id  and component = "addrver" and component_tbl_id = "1")');

		$this->db->where('year(view_vendor_master_log.created_on)', $params['year']);

        $this->db->where('month(view_vendor_master_log.created_on)', $params['month']); 
 
        $this->db->where_in('view_vendor_master_log.final_status',$array_address_closed);

		$this->db->group_by('addrver.vendor_id,year(view_vendor_master_log.created_on), month(view_vendor_master_log.created_on), day(view_vendor_master_log.created_on)');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$addrver_closed = $result->result_array();

		$array_employment_initiated =  array('wip','insufficiency','closed','approve');
		$array_employment_wip =  array('wip');
		$array_employment_insuff =  array('insufficiency');
        $array_employment_closed =  array('closed','approve');


		$this->db->select("empver.vendor_id,year(view_vendor_master_log.created_on) as year, month(view_vendor_master_log.created_on) as month,CASE  WHEN view_vendor_master_log.final_status = 'wip' THEN 'currentwip'   END AS status_value,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('empver');

		$this->db->join("employment_vendor_log",'employment_vendor_log.case_id = empver.id');
       
        $this->db->join('view_vendor_master_log','(view_vendor_master_log.case_id = employment_vendor_log.id  and component = "empver" and component_tbl_id = "2")');

		
		$this->db->where('year(view_vendor_master_log.created_on) <=', $params['year']);

        $this->db->where('month(view_vendor_master_log.created_on) <', $params['month']);
		
		$this->db->where_in('view_vendor_master_log.final_status',$array_employment_wip);
   
		$this->db->group_by('empver.vendor_id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$empver_currentwip = $result->result_array();



		$this->db->select("empver.vendor_id,year(view_vendor_master_log.created_on) as year, month(view_vendor_master_log.created_on) as month,CASE  WHEN view_vendor_master_log.final_status = 'insufficiency' THEN 'currentinsuff'   END AS status_value,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('empver');

		$this->db->join("employment_vendor_log",'employment_vendor_log.case_id = empver.id');
       
        $this->db->join('view_vendor_master_log','(view_vendor_master_log.case_id = employment_vendor_log.id  and component = "empver" and component_tbl_id = "2")');

		
		$this->db->where('year(view_vendor_master_log.created_on) <=', $params['year']);

        $this->db->where('month(view_vendor_master_log.created_on) <', $params['month']);
		
		$this->db->where_in('view_vendor_master_log.final_status',$array_employment_insuff);
   
		$this->db->group_by('empver.vendor_id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$empver_currentinsuff = $result->result_array();


		$this->db->select("empver.vendor_id,year(view_vendor_master_log.created_on) as year, month(view_vendor_master_log.created_on) as month,CASE  WHEN view_vendor_master_log.final_status = 'wip' THEN 'Initiated'  WHEN view_vendor_master_log.final_status = 'insufficiency' THEN 'Initiated' WHEN view_vendor_master_log.final_status = 'closed' THEN 'Initiated' WHEN view_vendor_master_log.final_status = 'approve' THEN 'Initiated'  END AS status_value,CASE  WHEN day(view_vendor_master_log.created_on) = 1 THEN 'one' WHEN day(view_vendor_master_log.created_on) = 2 THEN 'two' WHEN day(view_vendor_master_log.created_on) = 3 THEN 'three' WHEN day(view_vendor_master_log.created_on) = 4 THEN 'four' WHEN  day(view_vendor_master_log.created_on) = 5 THEN 'five' WHEN day(view_vendor_master_log.created_on) = 6 THEN 'six' WHEN day(view_vendor_master_log.created_on) = 7 THEN 'seven' WHEN day(view_vendor_master_log.created_on) = 8 THEN 'eight' WHEN day(view_vendor_master_log.created_on) = 9 THEN 'nine' WHEN day(view_vendor_master_log.created_on) = 10 THEN 'ten' WHEN day(view_vendor_master_log.created_on) = 11 THEN 'eleven' WHEN day(view_vendor_master_log.created_on) = 12 THEN 'twelve' WHEN day(view_vendor_master_log.created_on) = 13 THEN 'thirteen' WHEN day(view_vendor_master_log.created_on) = 14 THEN 'fourteen' WHEN day(view_vendor_master_log.created_on) = 15 THEN 'fifteen' WHEN day(view_vendor_master_log.created_on) = 16 THEN 'sixteen' WHEN day(view_vendor_master_log.created_on) = 17 THEN 'seventeen' WHEN day(view_vendor_master_log.created_on) = 18 THEN 'eightteen' WHEN day(view_vendor_master_log.created_on) = 19 THEN 'nineteen' WHEN day(view_vendor_master_log.created_on) = 20 THEN 'twenty' WHEN day(view_vendor_master_log.created_on) = 21 THEN 'twentyone'  WHEN day(view_vendor_master_log.created_on) = 22 THEN 'twentytwo' WHEN day(view_vendor_master_log.created_on) = 23 THEN 'twentythree'  WHEN day(view_vendor_master_log.created_on) = 24 THEN 'twentyfour' WHEN day(view_vendor_master_log.created_on) = 25 THEN 'twentyfive' WHEN day(view_vendor_master_log.created_on) = 26 THEN 'twentysix' WHEN day(view_vendor_master_log.created_on) = 27 THEN 'twentyseven' WHEN day(view_vendor_master_log.created_on) = 28 THEN 'twentyeight' WHEN day(view_vendor_master_log.created_on) = 29 THEN 'twentynine' WHEN day(view_vendor_master_log.created_on) = 30 THEN 'thirty' WHEN day(view_vendor_master_log.created_on) = 31 THEN 'thirtyone' END  as day,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('empver');

		$this->db->join("employment_vendor_log",'employment_vendor_log.case_id = empver.id');
       
        $this->db->join('view_vendor_master_log','(view_vendor_master_log.case_id = employment_vendor_log.id  and component = "empver" and component_tbl_id = "2")');

		$this->db->where('year(view_vendor_master_log.created_on)', $params['year']);

        $this->db->where('month(view_vendor_master_log.created_on)', $params['month']);
		
		$this->db->where_in('view_vendor_master_log.final_status',$array_employment_initiated);
   
		$this->db->group_by('empver.vendor_id,year(view_vendor_master_log.created_on), month(view_vendor_master_log.created_on), day(view_vendor_master_log.created_on)');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$empver_initiated = $result->result_array();


		$this->db->select("empver.vendor_id,year(view_vendor_master_log.created_on) as year, month(view_vendor_master_log.created_on) as month,CASE  WHEN view_vendor_master_log.final_status = 'wip' THEN 'WIP'   END AS status_value,CASE  WHEN day(view_vendor_master_log.created_on) = 1 THEN 'one' WHEN day(view_vendor_master_log.created_on) = 2 THEN 'two' WHEN day(view_vendor_master_log.created_on) = 3 THEN 'three' WHEN day(view_vendor_master_log.created_on) = 4 THEN 'four' WHEN  day(view_vendor_master_log.created_on) = 5 THEN 'five' WHEN day(view_vendor_master_log.created_on) = 6 THEN 'six' WHEN day(view_vendor_master_log.created_on) = 7 THEN 'seven' WHEN day(view_vendor_master_log.created_on) = 8 THEN 'eight' WHEN day(view_vendor_master_log.created_on) = 9 THEN 'nine' WHEN day(view_vendor_master_log.created_on) = 10 THEN 'ten' WHEN day(view_vendor_master_log.created_on) = 11 THEN 'eleven' WHEN day(view_vendor_master_log.created_on) = 12 THEN 'twelve' WHEN day(view_vendor_master_log.created_on) = 13 THEN 'thirteen' WHEN day(view_vendor_master_log.created_on) = 14 THEN 'fourteen' WHEN day(view_vendor_master_log.created_on) = 15 THEN 'fifteen' WHEN day(view_vendor_master_log.created_on) = 16 THEN 'sixteen' WHEN day(view_vendor_master_log.created_on) = 17 THEN 'seventeen' WHEN day(view_vendor_master_log.created_on) = 18 THEN 'eightteen' WHEN day(view_vendor_master_log.created_on) = 19 THEN 'nineteen' WHEN day(view_vendor_master_log.created_on) = 20 THEN 'twenty' WHEN day(view_vendor_master_log.created_on) = 21 THEN 'twentyone'  WHEN day(view_vendor_master_log.created_on) = 22 THEN 'twentytwo' WHEN day(view_vendor_master_log.created_on) = 23 THEN 'twentythree'  WHEN day(view_vendor_master_log.created_on) = 24 THEN 'twentyfour' WHEN day(view_vendor_master_log.created_on) = 25 THEN 'twentyfive' WHEN day(view_vendor_master_log.created_on) = 26 THEN 'twentysix' WHEN day(view_vendor_master_log.created_on) = 27 THEN 'twentyseven' WHEN day(view_vendor_master_log.created_on) = 28 THEN 'twentyeight' WHEN day(view_vendor_master_log.created_on) = 29 THEN 'twentynine' WHEN day(view_vendor_master_log.created_on) = 30 THEN 'thirty' WHEN day(view_vendor_master_log.created_on) = 31 THEN 'thirtyone' END  as day,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('empver');

		$this->db->join("employment_vendor_log",'employment_vendor_log.case_id = empver.id');
       
        $this->db->join('view_vendor_master_log','(view_vendor_master_log.case_id = employment_vendor_log.id  and component = "empver" and component_tbl_id = "2")');

		$this->db->where('year(view_vendor_master_log.created_on)', $params['year']);

        $this->db->where('month(view_vendor_master_log.created_on)', $params['month']);
		
		$this->db->where_in('view_vendor_master_log.final_status',$array_employment_wip);
   
		$this->db->group_by('empver.vendor_id,year(view_vendor_master_log.created_on), month(view_vendor_master_log.created_on), day(view_vendor_master_log.created_on)');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$empver_wip = $result->result_array();


		
		$this->db->select("empver.vendor_id,year(view_vendor_master_log.created_on) as year, month(view_vendor_master_log.created_on) as month,CASE  WHEN view_vendor_master_log.final_status = 'insufficiency' THEN 'Insufficiency'  END AS status_value,CASE  WHEN day(view_vendor_master_log.modified_on) = 1 THEN 'one' WHEN day(view_vendor_master_log.modified_on) = 2 THEN 'two' WHEN day(view_vendor_master_log.modified_on) = 3 THEN 'three' WHEN day(view_vendor_master_log.modified_on) = 4 THEN 'four' WHEN  day(view_vendor_master_log.modified_on) = 5 THEN 'five' WHEN day(view_vendor_master_log.modified_on) = 6 THEN 'six' WHEN day(view_vendor_master_log.modified_on) = 7 THEN 'seven' WHEN day(view_vendor_master_log.modified_on) = 8 THEN 'eight' WHEN day(view_vendor_master_log.modified_on) = 9 THEN 'nine' WHEN day(view_vendor_master_log.modified_on) = 10 THEN 'ten' WHEN day(view_vendor_master_log.modified_on) = 11 THEN 'eleven' WHEN day(view_vendor_master_log.modified_on) = 12 THEN 'twelve' WHEN day(view_vendor_master_log.modified_on) = 13 THEN 'thirteen' WHEN day(view_vendor_master_log.modified_on) = 14 THEN 'fourteen' WHEN day(view_vendor_master_log.modified_on) = 15 THEN 'fifteen' WHEN day(view_vendor_master_log.modified_on) = 16 THEN 'sixteen' WHEN day(view_vendor_master_log.modified_on) = 17 THEN 'seventeen' WHEN day(view_vendor_master_log.modified_on) = 18 THEN 'eightteen' WHEN day(view_vendor_master_log.modified_on) = 19 THEN 'nineteen' WHEN day(view_vendor_master_log.modified_on) = 20 THEN 'twenty' WHEN day(view_vendor_master_log.modified_on) = 21 THEN 'twentyone'  WHEN day(view_vendor_master_log.modified_on) = 22 THEN 'twentytwo' WHEN day(view_vendor_master_log.modified_on) = 23 THEN 'twentythree'  WHEN day(view_vendor_master_log.modified_on) = 24 THEN 'twentyfour' WHEN day(view_vendor_master_log.modified_on) = 25 THEN 'twentyfive' WHEN day(view_vendor_master_log.modified_on) = 26 THEN 'twentysix' WHEN day(view_vendor_master_log.modified_on) = 27 THEN 'twentyseven' WHEN day(view_vendor_master_log.modified_on) = 28 THEN 'twentyeight' WHEN day(view_vendor_master_log.modified_on) = 29 THEN 'twentynine' WHEN day(view_vendor_master_log.modified_on) = 30 THEN 'thirty' WHEN day(view_vendor_master_log.modified_on) = 31 THEN 'thirtyone' END  as day,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('empver');

		$this->db->join("employment_vendor_log",'employment_vendor_log.case_id = empver.id');
       
        $this->db->join('view_vendor_master_log','(view_vendor_master_log.case_id = employment_vendor_log.id  and component = "empver" and component_tbl_id = "2")');
       
		$this->db->where('year(view_vendor_master_log.modified_on)', $params['year']);

        $this->db->where('month(view_vendor_master_log.modified_on)', $params['month']); 

	    $this->db->where_in('view_vendor_master_log.final_status',$array_employment_insuff);
   
		$this->db->group_by('empver.vendor_id,year(view_vendor_master_log.modified_on), month(view_vendor_master_log.modified_on), day(view_vendor_master_log.modified_on)');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$empver_insuff = $result->result_array();



		$this->db->select("empver.vendor_id,year(view_vendor_master_log.modified_on) as year, month(view_vendor_master_log.modified_on) as month,CASE  WHEN view_vendor_master_log.final_status = 'closed' THEN 'Closed' WHEN view_vendor_master_log.final_status = 'approve' THEN 'Closed' END AS status_value,CASE  WHEN day(view_vendor_master_log.modified_on) = 1 THEN 'one' WHEN day(view_vendor_master_log.modified_on) = 2 THEN 'two' WHEN day(view_vendor_master_log.modified_on) = 3 THEN 'three' WHEN day(view_vendor_master_log.modified_on) = 4 THEN 'four' WHEN  day(view_vendor_master_log.modified_on) = 5 THEN 'five' WHEN day(view_vendor_master_log.modified_on) = 6 THEN 'six' WHEN day(view_vendor_master_log.modified_on) = 7 THEN 'seven' WHEN day(view_vendor_master_log.modified_on) = 8 THEN 'eight' WHEN day(view_vendor_master_log.modified_on) = 9 THEN 'nine' WHEN day(view_vendor_master_log.modified_on) = 10 THEN 'ten' WHEN day(view_vendor_master_log.modified_on) = 11 THEN 'eleven' WHEN day(view_vendor_master_log.modified_on) = 12 THEN 'twelve' WHEN day(view_vendor_master_log.modified_on) = 13 THEN 'thirteen' WHEN day(view_vendor_master_log.modified_on) = 14 THEN 'fourteen' WHEN day(view_vendor_master_log.modified_on) = 15 THEN 'fifteen' WHEN day(view_vendor_master_log.modified_on) = 16 THEN 'sixteen' WHEN day(view_vendor_master_log.modified_on) = 17 THEN 'seventeen' WHEN day(view_vendor_master_log.modified_on) = 18 THEN 'eightteen' WHEN day(view_vendor_master_log.modified_on) = 19 THEN 'nineteen' WHEN day(view_vendor_master_log.modified_on) = 20 THEN 'twenty' WHEN day(view_vendor_master_log.modified_on) = 21 THEN 'twentyone'  WHEN day(view_vendor_master_log.modified_on) = 22 THEN 'twentytwo' WHEN day(view_vendor_master_log.modified_on) = 23 THEN 'twentythree'  WHEN day(view_vendor_master_log.modified_on) = 24 THEN 'twentyfour' WHEN day(view_vendor_master_log.modified_on) = 25 THEN 'twentyfive' WHEN day(view_vendor_master_log.modified_on) = 26 THEN 'twentysix' WHEN day(view_vendor_master_log.modified_on) = 27 THEN 'twentyseven' WHEN day(view_vendor_master_log.modified_on) = 28 THEN 'twentyeight' WHEN day(view_vendor_master_log.modified_on) = 29 THEN 'twentynine' WHEN day(view_vendor_master_log.modified_on) = 30 THEN 'thirty' WHEN day(view_vendor_master_log.modified_on) = 31 THEN 'thirtyone' END  as day,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('empver');

		$this->db->join("employment_vendor_log",'employment_vendor_log.case_id = empver.id');
       
        $this->db->join('view_vendor_master_log','(view_vendor_master_log.case_id = employment_vendor_log.id  and component = "empver" and component_tbl_id = "2")');
         
		$this->db->where('year(view_vendor_master_log.modified_on)', $params['year']);

        $this->db->where('month(view_vendor_master_log.modified_on)', $params['month']); 
 
        $this->db->where_in('view_vendor_master_log.final_status',$array_employment_closed);

		$this->db->group_by('empver.vendor_id,year(view_vendor_master_log.modified_on), month(view_vendor_master_log.modified_on), day(view_vendor_master_log.modified_on)');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$empver_closed = $result->result_array();


		$array_court_initiated =  array('wip','insufficiency','clear','possible match','approve');
		$array_court_wip =  array('wip');
		$array_court_insuff =  array('insufficiency');
        $array_court_closed =  array('clear','possible match','approve');



		$this->db->select("courtver.vendor_id,year(view_vendor_master_log.created_on) as year, month(view_vendor_master_log.created_on) as month,CASE  WHEN view_vendor_master_log.final_status = 'wip' THEN 'currentwip'  END AS status_value,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('courtver');

		$this->db->join("courtver_vendor_log",'courtver_vendor_log.case_id = courtver.id');
       
        $this->db->join('view_vendor_master_log','(view_vendor_master_log.case_id = courtver_vendor_log.id  and component = "courtver" and component_tbl_id = "5")');
       
		$this->db->where('year(view_vendor_master_log.created_on) <=', $params['year']);

        $this->db->where('month(view_vendor_master_log.created_on) <', $params['month']);
	
		$this->db->where_in('view_vendor_master_log.final_status',$array_court_wip);
   
		$this->db->group_by('courtver.vendor_id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$court_currentwip = $result->result_array();


       
		$this->db->select("courtver.vendor_id,year(view_vendor_master_log.created_on) as year, month(view_vendor_master_log.created_on) as month,CASE  WHEN view_vendor_master_log.final_status = 'insufficiency' THEN 'currentinsuff'  END AS status_value,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('courtver');

		$this->db->join("courtver_vendor_log",'courtver_vendor_log.case_id = courtver.id');
       
        $this->db->join('view_vendor_master_log','(view_vendor_master_log.case_id = courtver_vendor_log.id  and component = "courtver" and component_tbl_id = "5")');
       
		$this->db->where('year(view_vendor_master_log.created_on) <=', $params['year']);

        $this->db->where('month(view_vendor_master_log.created_on) <', $params['month']);
	
		$this->db->where_in('view_vendor_master_log.final_status',$array_court_insuff);
   
		$this->db->group_by('courtver.vendor_id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$court_currentinsuff = $result->result_array();



	    $this->db->select("courtver.vendor_id,year(view_vendor_master_log.created_on) as year, month(view_vendor_master_log.created_on) as month,CASE  WHEN view_vendor_master_log.final_status = 'wip' THEN 'Initiated'  WHEN view_vendor_master_log.final_status = 'insufficiency' THEN 'Initiated' WHEN view_vendor_master_log.final_status = 'clear' THEN 'Initiated' WHEN view_vendor_master_log.final_status = 'possible match' THEN 'Initiated' WHEN view_vendor_master_log.final_status = 'approve' THEN 'Initiated' END AS status_value,CASE  WHEN day(view_vendor_master_log.created_on) = 1 THEN 'one' WHEN day(view_vendor_master_log.created_on) = 2 THEN 'two' WHEN day(view_vendor_master_log.created_on) = 3 THEN 'three' WHEN day(view_vendor_master_log.created_on) = 4 THEN 'four' WHEN  day(view_vendor_master_log.created_on) = 5 THEN 'five' WHEN day(view_vendor_master_log.created_on) = 6 THEN 'six' WHEN day(view_vendor_master_log.created_on) = 7 THEN 'seven' WHEN day(view_vendor_master_log.created_on) = 8 THEN 'eight' WHEN day(view_vendor_master_log.created_on) = 9 THEN 'nine' WHEN day(view_vendor_master_log.created_on) = 10 THEN 'ten' WHEN day(view_vendor_master_log.created_on) = 11 THEN 'eleven' WHEN day(view_vendor_master_log.created_on) = 12 THEN 'twelve' WHEN day(view_vendor_master_log.created_on) = 13 THEN 'thirteen' WHEN day(view_vendor_master_log.created_on) = 14 THEN 'fourteen' WHEN day(view_vendor_master_log.created_on) = 15 THEN 'fifteen' WHEN day(view_vendor_master_log.created_on) = 16 THEN 'sixteen' WHEN day(view_vendor_master_log.created_on) = 17 THEN 'seventeen' WHEN day(view_vendor_master_log.created_on) = 18 THEN 'eightteen' WHEN day(view_vendor_master_log.created_on) = 19 THEN 'nineteen' WHEN day(view_vendor_master_log.created_on) = 20 THEN 'twenty' WHEN day(view_vendor_master_log.created_on) = 21 THEN 'twentyone'  WHEN day(view_vendor_master_log.created_on) = 22 THEN 'twentytwo' WHEN day(view_vendor_master_log.created_on) = 23 THEN 'twentythree'  WHEN day(view_vendor_master_log.created_on) = 24 THEN 'twentyfour' WHEN day(view_vendor_master_log.created_on) = 25 THEN 'twentyfive' WHEN day(view_vendor_master_log.created_on) = 26 THEN 'twentysix' WHEN day(view_vendor_master_log.created_on) = 27 THEN 'twentyseven' WHEN day(view_vendor_master_log.created_on) = 28 THEN 'twentyeight' WHEN day(view_vendor_master_log.created_on) = 29 THEN 'twentynine' WHEN day(view_vendor_master_log.created_on) = 30 THEN 'thirty' WHEN day(view_vendor_master_log.created_on) = 31 THEN 'thirtyone' END  as day,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('courtver');

		$this->db->join("courtver_vendor_log",'courtver_vendor_log.case_id = courtver.id');
       
        $this->db->join('view_vendor_master_log','(view_vendor_master_log.case_id = courtver_vendor_log.id  and component = "courtver" and component_tbl_id = "5")');
       
		$this->db->where('year(view_vendor_master_log.created_on)', $params['year']);

        $this->db->where('month(view_vendor_master_log.created_on)', $params['month']);
	
		$this->db->where_in('view_vendor_master_log.final_status',$array_court_initiated);
   
		$this->db->group_by('courtver.vendor_id,year(view_vendor_master_log.created_on), month(view_vendor_master_log.created_on), day(view_vendor_master_log.created_on)');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$court_initiated = $result->result_array();


		$this->db->select("courtver.vendor_id,year(view_vendor_master_log.created_on) as year, month(view_vendor_master_log.created_on) as month,CASE  WHEN view_vendor_master_log.final_status = 'wip' THEN 'WIP'   END AS status_value,CASE  WHEN day(view_vendor_master_log.created_on) = 1 THEN 'one' WHEN day(view_vendor_master_log.created_on) = 2 THEN 'two' WHEN day(view_vendor_master_log.created_on) = 3 THEN 'three' WHEN day(view_vendor_master_log.created_on) = 4 THEN 'four' WHEN  day(view_vendor_master_log.created_on) = 5 THEN 'five' WHEN day(view_vendor_master_log.created_on) = 6 THEN 'six' WHEN day(view_vendor_master_log.created_on) = 7 THEN 'seven' WHEN day(view_vendor_master_log.created_on) = 8 THEN 'eight' WHEN day(view_vendor_master_log.created_on) = 9 THEN 'nine' WHEN day(view_vendor_master_log.created_on) = 10 THEN 'ten' WHEN day(view_vendor_master_log.created_on) = 11 THEN 'eleven' WHEN day(view_vendor_master_log.created_on) = 12 THEN 'twelve' WHEN day(view_vendor_master_log.created_on) = 13 THEN 'thirteen' WHEN day(view_vendor_master_log.created_on) = 14 THEN 'fourteen' WHEN day(view_vendor_master_log.created_on) = 15 THEN 'fifteen' WHEN day(view_vendor_master_log.created_on) = 16 THEN 'sixteen' WHEN day(view_vendor_master_log.created_on) = 17 THEN 'seventeen' WHEN day(view_vendor_master_log.created_on) = 18 THEN 'eightteen' WHEN day(view_vendor_master_log.created_on) = 19 THEN 'nineteen' WHEN day(view_vendor_master_log.created_on) = 20 THEN 'twenty' WHEN day(view_vendor_master_log.created_on) = 21 THEN 'twentyone'  WHEN day(view_vendor_master_log.created_on) = 22 THEN 'twentytwo' WHEN day(view_vendor_master_log.created_on) = 23 THEN 'twentythree'  WHEN day(view_vendor_master_log.created_on) = 24 THEN 'twentyfour' WHEN day(view_vendor_master_log.created_on) = 25 THEN 'twentyfive' WHEN day(view_vendor_master_log.created_on) = 26 THEN 'twentysix' WHEN day(view_vendor_master_log.created_on) = 27 THEN 'twentyseven' WHEN day(view_vendor_master_log.created_on) = 28 THEN 'twentyeight' WHEN day(view_vendor_master_log.created_on) = 29 THEN 'twentynine' WHEN day(view_vendor_master_log.created_on) = 30 THEN 'thirty' WHEN day(view_vendor_master_log.created_on) = 31 THEN 'thirtyone' END  as day,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('courtver');

		$this->db->join("courtver_vendor_log",'courtver_vendor_log.case_id = courtver.id');
       
        $this->db->join('view_vendor_master_log','(view_vendor_master_log.case_id = courtver_vendor_log.id  and component = "courtver" and component_tbl_id = "5")');
       
		$this->db->where('year(view_vendor_master_log.created_on)', $params['year']);

        $this->db->where('month(view_vendor_master_log.created_on)', $params['month']);
	
		$this->db->where_in('view_vendor_master_log.final_status',$array_court_wip);
   
		$this->db->group_by('courtver.vendor_id,year(view_vendor_master_log.created_on), month(view_vendor_master_log.created_on), day(view_vendor_master_log.created_on)');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$court_wip = $result->result_array();


		
		$this->db->select("courtver.vendor_id,year(view_vendor_master_log.modified_on) as year, month(view_vendor_master_log.modified_on) as month,CASE  WHEN view_vendor_master_log.final_status = 'insufficiency' THEN 'Insufficiency'  END AS status_value,CASE  WHEN day(view_vendor_master_log.modified_on) = 1 THEN 'one' WHEN day(view_vendor_master_log.modified_on) = 2 THEN 'two' WHEN day(view_vendor_master_log.modified_on) = 3 THEN 'three' WHEN day(view_vendor_master_log.modified_on) = 4 THEN 'four' WHEN  day(view_vendor_master_log.modified_on) = 5 THEN 'five' WHEN day(view_vendor_master_log.modified_on) = 6 THEN 'six' WHEN day(view_vendor_master_log.modified_on) = 7 THEN 'seven' WHEN day(view_vendor_master_log.modified_on) = 8 THEN 'eight' WHEN day(view_vendor_master_log.modified_on) = 9 THEN 'nine' WHEN day(view_vendor_master_log.modified_on) = 10 THEN 'ten' WHEN day(view_vendor_master_log.modified_on) = 11 THEN 'eleven' WHEN day(view_vendor_master_log.modified_on) = 12 THEN 'twelve' WHEN day(view_vendor_master_log.modified_on) = 13 THEN 'thirteen' WHEN day(view_vendor_master_log.modified_on) = 14 THEN 'fourteen' WHEN day(view_vendor_master_log.modified_on) = 15 THEN 'fifteen' WHEN day(view_vendor_master_log.modified_on) = 16 THEN 'sixteen' WHEN day(view_vendor_master_log.modified_on) = 17 THEN 'seventeen' WHEN day(view_vendor_master_log.modified_on) = 18 THEN 'eightteen' WHEN day(view_vendor_master_log.modified_on) = 19 THEN 'nineteen' WHEN day(view_vendor_master_log.modified_on) = 20 THEN 'twenty' WHEN day(view_vendor_master_log.modified_on) = 21 THEN 'twentyone'  WHEN day(view_vendor_master_log.modified_on) = 22 THEN 'twentytwo' WHEN day(view_vendor_master_log.modified_on) = 23 THEN 'twentythree'  WHEN day(view_vendor_master_log.modified_on) = 24 THEN 'twentyfour' WHEN day(view_vendor_master_log.modified_on) = 25 THEN 'twentyfive' WHEN day(view_vendor_master_log.modified_on) = 26 THEN 'twentysix' WHEN day(view_vendor_master_log.modified_on) = 27 THEN 'twentyseven' WHEN day(view_vendor_master_log.modified_on) = 28 THEN 'twentyeight' WHEN day(view_vendor_master_log.modified_on) = 29 THEN 'twentynine' WHEN day(view_vendor_master_log.modified_on) = 30 THEN 'thirty' WHEN day(view_vendor_master_log.modified_on) = 31 THEN 'thirtyone' END  as day,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('courtver');

		$this->db->join("courtver_vendor_log",'courtver_vendor_log.case_id = courtver.id');
       
        $this->db->join('view_vendor_master_log','(view_vendor_master_log.case_id = courtver_vendor_log.id  and component = "courtver" and component_tbl_id = "5")');

		$this->db->where('year(view_vendor_master_log.modified_on)', $params['year']);

        $this->db->where('month(view_vendor_master_log.modified_on)', $params['month']); 

	    $this->db->where_in('view_vendor_master_log.final_status',$array_court_insuff);
   
		$this->db->group_by('courtver.vendor_id,year(view_vendor_master_log.modified_on), month(view_vendor_master_log.modified_on), day(view_vendor_master_log.modified_on)');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$court_insuff = $result->result_array();



		$this->db->select("courtver.vendor_id,year(view_vendor_master_log.modified_on) as year, month(view_vendor_master_log.modified_on) as month,CASE  WHEN view_vendor_master_log.final_status = 'clear' THEN 'Closed' WHEN view_vendor_master_log.final_status = 'possible match' THEN 'Closed' WHEN view_vendor_master_log.final_status = 'approve' THEN 'Closed' END AS status_value,CASE  WHEN day(view_vendor_master_log.modified_on) = 1 THEN 'one' WHEN day(view_vendor_master_log.modified_on) = 2 THEN 'two' WHEN day(view_vendor_master_log.modified_on) = 3 THEN 'three' WHEN day(view_vendor_master_log.modified_on) = 4 THEN 'four' WHEN  day(view_vendor_master_log.modified_on) = 5 THEN 'five' WHEN day(view_vendor_master_log.modified_on) = 6 THEN 'six' WHEN day(view_vendor_master_log.modified_on) = 7 THEN 'seven' WHEN day(view_vendor_master_log.modified_on) = 8 THEN 'eight' WHEN day(view_vendor_master_log.modified_on) = 9 THEN 'nine' WHEN day(view_vendor_master_log.modified_on) = 10 THEN 'ten' WHEN day(view_vendor_master_log.modified_on) = 11 THEN 'eleven' WHEN day(view_vendor_master_log.modified_on) = 12 THEN 'twelve' WHEN day(view_vendor_master_log.modified_on) = 13 THEN 'thirteen' WHEN day(view_vendor_master_log.modified_on) = 14 THEN 'fourteen' WHEN day(view_vendor_master_log.modified_on) = 15 THEN 'fifteen' WHEN day(view_vendor_master_log.modified_on) = 16 THEN 'sixteen' WHEN day(view_vendor_master_log.modified_on) = 17 THEN 'seventeen' WHEN day(view_vendor_master_log.modified_on) = 18 THEN 'eightteen' WHEN day(view_vendor_master_log.modified_on) = 19 THEN 'nineteen' WHEN day(view_vendor_master_log.modified_on) = 20 THEN 'twenty' WHEN day(view_vendor_master_log.modified_on) = 21 THEN 'twentyone'  WHEN day(view_vendor_master_log.modified_on) = 22 THEN 'twentytwo' WHEN day(view_vendor_master_log.modified_on) = 23 THEN 'twentythree'  WHEN day(view_vendor_master_log.modified_on) = 24 THEN 'twentyfour' WHEN day(view_vendor_master_log.modified_on) = 25 THEN 'twentyfive' WHEN day(view_vendor_master_log.modified_on) = 26 THEN 'twentysix' WHEN day(view_vendor_master_log.modified_on) = 27 THEN 'twentyseven' WHEN day(view_vendor_master_log.modified_on) = 28 THEN 'twentyeight' WHEN day(view_vendor_master_log.modified_on) = 29 THEN 'twentynine' WHEN day(view_vendor_master_log.modified_on) = 30 THEN 'thirty' WHEN day(view_vendor_master_log.modified_on) = 31 THEN 'thirtyone' END  as day,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('courtver');

		$this->db->join("courtver_vendor_log",'courtver_vendor_log.case_id = courtver.id');
       
        $this->db->join('view_vendor_master_log','(view_vendor_master_log.case_id = courtver_vendor_log.id  and component = "courtver" and component_tbl_id = "5")');
         
		$this->db->where('year(view_vendor_master_log.modified_on)', $params['year']);

        $this->db->where('month(view_vendor_master_log.modified_on)', $params['month']); 

 
        $this->db->where_in('view_vendor_master_log.final_status',$array_court_closed);

		$this->db->group_by('courtver.vendor_id,year(view_vendor_master_log.modified_on), month(view_vendor_master_log.modified_on), day(view_vendor_master_log.modified_on)');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$court_closed = $result->result_array();

		$array_global_initiated =  array('wip','insufficiency','clear','possible match','approve');
		$array_global_wip =  array('wip');
		$array_global_insuff =  array('insufficiency');
        $array_global_closed =  array('clear','possible match','approve');


		$this->db->select("glodbver.vendor_id,year(view_vendor_master_log.created_on) as year, month(view_vendor_master_log.created_on) as month,CASE  WHEN view_vendor_master_log.final_status = 'wip' THEN 'currentwip' END AS status_value,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('glodbver');

		$this->db->join("glodbver_vendor_log",'glodbver_vendor_log.case_id = glodbver.id');
       
        $this->db->join('view_vendor_master_log','(view_vendor_master_log.case_id = glodbver_vendor_log.id  and component = "globdbver" and component_tbl_id = "6")');

		$this->db->where('year(view_vendor_master_log.created_on) <=', $params['year']);

        $this->db->where('month(view_vendor_master_log.created_on) <', $params['month']);
		
		$this->db->where_in('view_vendor_master_log.final_status',$array_global_wip);
   
		$this->db->group_by('glodbver.vendor_id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$global_currentwip = $result->result_array();


		$this->db->select("glodbver.vendor_id,year(view_vendor_master_log.created_on) as year, month(view_vendor_master_log.created_on) as month,CASE WHEN view_vendor_master_log.final_status = 'insufficiency' THEN 'currentinsuff'   END AS status_value,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('glodbver');

		$this->db->join("glodbver_vendor_log",'glodbver_vendor_log.case_id = glodbver.id');
       
        $this->db->join('view_vendor_master_log','(view_vendor_master_log.case_id = glodbver_vendor_log.id  and component = "globdbver" and component_tbl_id = "6")');

		$this->db->where('year(view_vendor_master_log.created_on) <=', $params['year']);

        $this->db->where('month(view_vendor_master_log.created_on) <', $params['month']);
		
		$this->db->where_in('view_vendor_master_log.final_status',$array_global_insuff);
   
		$this->db->group_by('glodbver.vendor_id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$global_currentinsuff = $result->result_array();


		$this->db->select("glodbver.vendor_id,year(view_vendor_master_log.created_on) as year, month(view_vendor_master_log.created_on) as month,CASE  WHEN view_vendor_master_log.final_status = 'wip' THEN 'Initiated' WHEN view_vendor_master_log.final_status = 'insufficiency' THEN 'Initiated' WHEN view_vendor_master_log.final_status = 'clear' THEN 'Initiated' WHEN view_vendor_master_log.final_status = 'possible match' THEN 'Initiated' WHEN view_vendor_master_log.final_status = 'approve' THEN 'Initiated'  END AS status_value,CASE  WHEN day(view_vendor_master_log.created_on) = 1 THEN 'one' WHEN day(view_vendor_master_log.created_on) = 2 THEN 'two' WHEN day(view_vendor_master_log.created_on) = 3 THEN 'three' WHEN day(view_vendor_master_log.created_on) = 4 THEN 'four' WHEN  day(view_vendor_master_log.created_on) = 5 THEN 'five' WHEN day(view_vendor_master_log.created_on) = 6 THEN 'six' WHEN day(view_vendor_master_log.created_on) = 7 THEN 'seven' WHEN day(view_vendor_master_log.created_on) = 8 THEN 'eight' WHEN day(view_vendor_master_log.created_on) = 9 THEN 'nine' WHEN day(view_vendor_master_log.created_on) = 10 THEN 'ten' WHEN day(view_vendor_master_log.created_on) = 11 THEN 'eleven' WHEN day(view_vendor_master_log.created_on) = 12 THEN 'twelve' WHEN day(view_vendor_master_log.created_on) = 13 THEN 'thirteen' WHEN day(view_vendor_master_log.created_on) = 14 THEN 'fourteen' WHEN day(view_vendor_master_log.created_on) = 15 THEN 'fifteen' WHEN day(view_vendor_master_log.created_on) = 16 THEN 'sixteen' WHEN day(view_vendor_master_log.created_on) = 17 THEN 'seventeen' WHEN day(view_vendor_master_log.created_on) = 18 THEN 'eightteen' WHEN day(view_vendor_master_log.created_on) = 19 THEN 'nineteen' WHEN day(view_vendor_master_log.created_on) = 20 THEN 'twenty' WHEN day(view_vendor_master_log.created_on) = 21 THEN 'twentyone'  WHEN day(view_vendor_master_log.created_on) = 22 THEN 'twentytwo' WHEN day(view_vendor_master_log.created_on) = 23 THEN 'twentythree'  WHEN day(view_vendor_master_log.created_on) = 24 THEN 'twentyfour' WHEN day(view_vendor_master_log.created_on) = 25 THEN 'twentyfive' WHEN day(view_vendor_master_log.created_on) = 26 THEN 'twentysix' WHEN day(view_vendor_master_log.created_on) = 27 THEN 'twentyseven' WHEN day(view_vendor_master_log.created_on) = 28 THEN 'twentyeight' WHEN day(view_vendor_master_log.created_on) = 29 THEN 'twentynine' WHEN day(view_vendor_master_log.created_on) = 30 THEN 'thirty' WHEN day(view_vendor_master_log.created_on) = 31 THEN 'thirtyone' END  as day,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('glodbver');

		$this->db->join("glodbver_vendor_log",'glodbver_vendor_log.case_id = glodbver.id');
       
        $this->db->join('view_vendor_master_log','(view_vendor_master_log.case_id = glodbver_vendor_log.id  and component = "globdbver" and component_tbl_id = "6")');

	    $this->db->where('year(view_vendor_master_log.created_on)', $params['year']);

        $this->db->where('month(view_vendor_master_log.created_on)', $params['month']);
		
		$this->db->where_in('view_vendor_master_log.final_status',$array_global_initiated);
   
		$this->db->group_by('glodbver.vendor_id,year(view_vendor_master_log.created_on), month(view_vendor_master_log.created_on), day(view_vendor_master_log.created_on)');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$global_initiated = $result->result_array();



		$this->db->select("glodbver.vendor_id,year(view_vendor_master_log.created_on) as year, month(view_vendor_master_log.created_on) as month,CASE  WHEN view_vendor_master_log.final_status = 'wip' THEN 'WIP'   END AS status_value,CASE  WHEN day(view_vendor_master_log.created_on) = 1 THEN 'one' WHEN day(view_vendor_master_log.created_on) = 2 THEN 'two' WHEN day(view_vendor_master_log.created_on) = 3 THEN 'three' WHEN day(view_vendor_master_log.created_on) = 4 THEN 'four' WHEN  day(view_vendor_master_log.created_on) = 5 THEN 'five' WHEN day(view_vendor_master_log.created_on) = 6 THEN 'six' WHEN day(view_vendor_master_log.created_on) = 7 THEN 'seven' WHEN day(view_vendor_master_log.created_on) = 8 THEN 'eight' WHEN day(view_vendor_master_log.created_on) = 9 THEN 'nine' WHEN day(view_vendor_master_log.created_on) = 10 THEN 'ten' WHEN day(view_vendor_master_log.created_on) = 11 THEN 'eleven' WHEN day(view_vendor_master_log.created_on) = 12 THEN 'twelve' WHEN day(view_vendor_master_log.created_on) = 13 THEN 'thirteen' WHEN day(view_vendor_master_log.created_on) = 14 THEN 'fourteen' WHEN day(view_vendor_master_log.created_on) = 15 THEN 'fifteen' WHEN day(view_vendor_master_log.created_on) = 16 THEN 'sixteen' WHEN day(view_vendor_master_log.created_on) = 17 THEN 'seventeen' WHEN day(view_vendor_master_log.created_on) = 18 THEN 'eightteen' WHEN day(view_vendor_master_log.created_on) = 19 THEN 'nineteen' WHEN day(view_vendor_master_log.created_on) = 20 THEN 'twenty' WHEN day(view_vendor_master_log.created_on) = 21 THEN 'twentyone'  WHEN day(view_vendor_master_log.created_on) = 22 THEN 'twentytwo' WHEN day(view_vendor_master_log.created_on) = 23 THEN 'twentythree'  WHEN day(view_vendor_master_log.created_on) = 24 THEN 'twentyfour' WHEN day(view_vendor_master_log.created_on) = 25 THEN 'twentyfive' WHEN day(view_vendor_master_log.created_on) = 26 THEN 'twentysix' WHEN day(view_vendor_master_log.created_on) = 27 THEN 'twentyseven' WHEN day(view_vendor_master_log.created_on) = 28 THEN 'twentyeight' WHEN day(view_vendor_master_log.created_on) = 29 THEN 'twentynine' WHEN day(view_vendor_master_log.created_on) = 30 THEN 'thirty' WHEN day(view_vendor_master_log.created_on) = 31 THEN 'thirtyone' END  as day,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('glodbver');

		$this->db->join("glodbver_vendor_log",'glodbver_vendor_log.case_id = glodbver.id');
       
        $this->db->join('view_vendor_master_log','(view_vendor_master_log.case_id = glodbver_vendor_log.id  and component = "globdbver" and component_tbl_id = "6")');

	    $this->db->where('year(view_vendor_master_log.created_on)', $params['year']);

        $this->db->where('month(view_vendor_master_log.created_on)', $params['month']);
		
		$this->db->where_in('view_vendor_master_log.final_status',$array_global_wip);
   
		$this->db->group_by('glodbver.vendor_id,year(view_vendor_master_log.created_on), month(view_vendor_master_log.created_on), day(view_vendor_master_log.created_on)');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$global_wip = $result->result_array();


		
		$this->db->select("glodbver.vendor_id,year(view_vendor_master_log.modified_on) as year, month(view_vendor_master_log.modified_on) as month,CASE  WHEN view_vendor_master_log.final_status = 'insufficiency' THEN 'Insufficiency'  END AS status_value,CASE  WHEN day(view_vendor_master_log.modified_on) = 1 THEN 'one' WHEN day(view_vendor_master_log.modified_on) = 2 THEN 'two' WHEN day(view_vendor_master_log.modified_on) = 3 THEN 'three' WHEN day(view_vendor_master_log.modified_on) = 4 THEN 'four' WHEN  day(view_vendor_master_log.modified_on) = 5 THEN 'five' WHEN day(view_vendor_master_log.modified_on) = 6 THEN 'six' WHEN day(view_vendor_master_log.modified_on) = 7 THEN 'seven' WHEN day(view_vendor_master_log.modified_on) = 8 THEN 'eight' WHEN day(view_vendor_master_log.modified_on) = 9 THEN 'nine' WHEN day(view_vendor_master_log.modified_on) = 10 THEN 'ten' WHEN day(view_vendor_master_log.modified_on) = 11 THEN 'eleven' WHEN day(view_vendor_master_log.modified_on) = 12 THEN 'twelve' WHEN day(view_vendor_master_log.modified_on) = 13 THEN 'thirteen' WHEN day(view_vendor_master_log.modified_on) = 14 THEN 'fourteen' WHEN day(view_vendor_master_log.modified_on) = 15 THEN 'fifteen' WHEN day(view_vendor_master_log.modified_on) = 16 THEN 'sixteen' WHEN day(view_vendor_master_log.modified_on) = 17 THEN 'seventeen' WHEN day(view_vendor_master_log.modified_on) = 18 THEN 'eightteen' WHEN day(view_vendor_master_log.modified_on) = 19 THEN 'nineteen' WHEN day(view_vendor_master_log.modified_on) = 20 THEN 'twenty' WHEN day(view_vendor_master_log.modified_on) = 21 THEN 'twentyone'  WHEN day(view_vendor_master_log.modified_on) = 22 THEN 'twentytwo' WHEN day(view_vendor_master_log.modified_on) = 23 THEN 'twentythree'  WHEN day(view_vendor_master_log.modified_on) = 24 THEN 'twentyfour' WHEN day(view_vendor_master_log.modified_on) = 25 THEN 'twentyfive' WHEN day(view_vendor_master_log.modified_on) = 26 THEN 'twentysix' WHEN day(view_vendor_master_log.modified_on) = 27 THEN 'twentyseven' WHEN day(view_vendor_master_log.modified_on) = 28 THEN 'twentyeight' WHEN day(view_vendor_master_log.modified_on) = 29 THEN 'twentynine' WHEN day(view_vendor_master_log.modified_on) = 30 THEN 'thirty' WHEN day(view_vendor_master_log.modified_on) = 31 THEN 'thirtyone' END  as day,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('glodbver');

		$this->db->join("glodbver_vendor_log",'glodbver_vendor_log.case_id = glodbver.id');
       
        $this->db->join('view_vendor_master_log','(view_vendor_master_log.case_id = glodbver_vendor_log.id  and component = "globdbver" and component_tbl_id = "6")');
       
		$this->db->where('year(view_vendor_master_log.modified_on)', $params['year']);

        $this->db->where('month(view_vendor_master_log.modified_on)', $params['month']); 

	    $this->db->where_in('view_vendor_master_log.final_status',$array_global_insuff);
   
		$this->db->group_by('glodbver.vendor_id,year(view_vendor_master_log.modified_on), month(view_vendor_master_log.modified_on), day(view_vendor_master_log.modified_on)');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$global_insuff = $result->result_array();



		$this->db->select("glodbver.vendor_id,year(view_vendor_master_log.modified_on) as year, month(view_vendor_master_log.modified_on) as month,CASE  WHEN view_vendor_master_log.final_status = 'clear' THEN 'Closed' WHEN view_vendor_master_log.final_status = 'possible match' THEN 'Closed' WHEN view_vendor_master_log.final_status = 'approve' THEN 'Closed' END AS status_value,CASE  WHEN day(view_vendor_master_log.modified_on) = 1 THEN 'one' WHEN day(view_vendor_master_log.modified_on) = 2 THEN 'two' WHEN day(view_vendor_master_log.modified_on) = 3 THEN 'three' WHEN day(view_vendor_master_log.modified_on) = 4 THEN 'four' WHEN  day(view_vendor_master_log.modified_on) = 5 THEN 'five' WHEN day(view_vendor_master_log.modified_on) = 6 THEN 'six' WHEN day(view_vendor_master_log.modified_on) = 7 THEN 'seven' WHEN day(view_vendor_master_log.modified_on) = 8 THEN 'eight' WHEN day(view_vendor_master_log.modified_on) = 9 THEN 'nine' WHEN day(view_vendor_master_log.modified_on) = 10 THEN 'ten' WHEN day(view_vendor_master_log.modified_on) = 11 THEN 'eleven' WHEN day(view_vendor_master_log.modified_on) = 12 THEN 'twelve' WHEN day(view_vendor_master_log.modified_on) = 13 THEN 'thirteen' WHEN day(view_vendor_master_log.modified_on) = 14 THEN 'fourteen' WHEN day(view_vendor_master_log.modified_on) = 15 THEN 'fifteen' WHEN day(view_vendor_master_log.modified_on) = 16 THEN 'sixteen' WHEN day(view_vendor_master_log.modified_on) = 17 THEN 'seventeen' WHEN day(view_vendor_master_log.modified_on) = 18 THEN 'eightteen' WHEN day(view_vendor_master_log.modified_on) = 19 THEN 'nineteen' WHEN day(view_vendor_master_log.modified_on) = 20 THEN 'twenty' WHEN day(view_vendor_master_log.modified_on) = 21 THEN 'twentyone'  WHEN day(view_vendor_master_log.modified_on) = 22 THEN 'twentytwo' WHEN day(view_vendor_master_log.modified_on) = 23 THEN 'twentythree'  WHEN day(view_vendor_master_log.modified_on) = 24 THEN 'twentyfour' WHEN day(view_vendor_master_log.modified_on) = 25 THEN 'twentyfive' WHEN day(view_vendor_master_log.modified_on) = 26 THEN 'twentysix' WHEN day(view_vendor_master_log.modified_on) = 27 THEN 'twentyseven' WHEN day(view_vendor_master_log.modified_on) = 28 THEN 'twentyeight' WHEN day(view_vendor_master_log.modified_on) = 29 THEN 'twentynine' WHEN day(view_vendor_master_log.modified_on) = 30 THEN 'thirty' WHEN day(view_vendor_master_log.modified_on) = 31 THEN 'thirtyone' END  as day,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('glodbver');

		$this->db->join("glodbver_vendor_log",'glodbver_vendor_log.case_id = glodbver.id');
       
        $this->db->join('view_vendor_master_log','(view_vendor_master_log.case_id = glodbver_vendor_log.id  and component = "globdbver" and component_tbl_id = "6")');
         
		$this->db->where('year(view_vendor_master_log.modified_on)', $params['year']);

        $this->db->where('month(view_vendor_master_log.modified_on)', $params['month']); 
 
        $this->db->where_in('view_vendor_master_log.final_status',$array_global_closed);

		$this->db->group_by('glodbver.vendor_id,year(view_vendor_master_log.modified_on), month(view_vendor_master_log.modified_on), day(view_vendor_master_log.modified_on)');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$global_closed = $result->result_array();

		$array_pcc_initiated =  array('wip','insufficiency','closed','approve');
		$array_pcc_wip =  array('wip');
		$array_pcc_insuff =  array('insufficiency');
        $array_pcc_closed =  array('closed','approve');


		$this->db->select("pcc.vendor_id,year(view_vendor_master_log.created_on) as year, month(view_vendor_master_log.created_on) as month,CASE  WHEN view_vendor_master_log.final_status = 'wip' THEN 'currentwip'   END AS status_value,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('pcc');

		$this->db->join("pcc_vendor_log",'pcc_vendor_log.case_id = pcc.id');
       
        $this->db->join('view_vendor_master_log','(view_vendor_master_log.case_id = pcc_vendor_log.id  and component = "crimver" and component_tbl_id = "8")');

		$this->db->where('year(view_vendor_master_log.created_on) <=', $params['year']);

        $this->db->where('month(view_vendor_master_log.created_on) <', $params['month']);

		$this->db->where_in('view_vendor_master_log.final_status',$array_pcc_wip);
   
		$this->db->group_by('pcc.vendor_id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$pcc_currentwip = $result->result_array();

		
		$this->db->select("pcc.vendor_id,year(view_vendor_master_log.created_on) as year, month(view_vendor_master_log.created_on) as month,CASE   WHEN view_vendor_master_log.final_status = 'insufficiency' THEN 'currentinsuff'  END AS status_value,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('pcc');

		$this->db->join("pcc_vendor_log",'pcc_vendor_log.case_id = pcc.id');
       
        $this->db->join('view_vendor_master_log','(view_vendor_master_log.case_id = pcc_vendor_log.id  and component = "crimver" and component_tbl_id = "8")');

		$this->db->where('year(view_vendor_master_log.created_on) <=', $params['year']);

        $this->db->where('month(view_vendor_master_log.created_on) <', $params['month']);

		$this->db->where_in('view_vendor_master_log.final_status',$array_pcc_insuff);
   
		$this->db->group_by('pcc.vendor_id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$pcc_currentinsuff = $result->result_array();



		$this->db->select("pcc.vendor_id,year(view_vendor_master_log.created_on) as year, month(view_vendor_master_log.created_on) as month,CASE  WHEN view_vendor_master_log.final_status = 'wip' THEN 'Initiated'  WHEN view_vendor_master_log.final_status = 'insufficiency' THEN 'Initiated' WHEN view_vendor_master_log.final_status = 'closed' THEN 'Initiated'  WHEN view_vendor_master_log.final_status = 'approve' THEN 'Closed'  END AS status_value,CASE  WHEN day(view_vendor_master_log.created_on) = 1 THEN 'one' WHEN day(view_vendor_master_log.created_on) = 2 THEN 'two' WHEN day(view_vendor_master_log.created_on) = 3 THEN 'three' WHEN day(view_vendor_master_log.created_on) = 4 THEN 'four' WHEN  day(view_vendor_master_log.created_on) = 5 THEN 'five' WHEN day(view_vendor_master_log.created_on) = 6 THEN 'six' WHEN day(view_vendor_master_log.created_on) = 7 THEN 'seven' WHEN day(view_vendor_master_log.created_on) = 8 THEN 'eight' WHEN day(view_vendor_master_log.created_on) = 9 THEN 'nine' WHEN day(view_vendor_master_log.created_on) = 10 THEN 'ten' WHEN day(view_vendor_master_log.created_on) = 11 THEN 'eleven' WHEN day(view_vendor_master_log.created_on) = 12 THEN 'twelve' WHEN day(view_vendor_master_log.created_on) = 13 THEN 'thirteen' WHEN day(view_vendor_master_log.created_on) = 14 THEN 'fourteen' WHEN day(view_vendor_master_log.created_on) = 15 THEN 'fifteen' WHEN day(view_vendor_master_log.created_on) = 16 THEN 'sixteen' WHEN day(view_vendor_master_log.created_on) = 17 THEN 'seventeen' WHEN day(view_vendor_master_log.created_on) = 18 THEN 'eightteen' WHEN day(view_vendor_master_log.created_on) = 19 THEN 'nineteen' WHEN day(view_vendor_master_log.created_on) = 20 THEN 'twenty' WHEN day(view_vendor_master_log.created_on) = 21 THEN 'twentyone'  WHEN day(view_vendor_master_log.created_on) = 22 THEN 'twentytwo' WHEN day(view_vendor_master_log.created_on) = 23 THEN 'twentythree'  WHEN day(view_vendor_master_log.created_on) = 24 THEN 'twentyfour' WHEN day(view_vendor_master_log.created_on) = 25 THEN 'twentyfive' WHEN day(view_vendor_master_log.created_on) = 26 THEN 'twentysix' WHEN day(view_vendor_master_log.created_on) = 27 THEN 'twentyseven' WHEN day(view_vendor_master_log.created_on) = 28 THEN 'twentyeight' WHEN day(view_vendor_master_log.created_on) = 29 THEN 'twentynine' WHEN day(view_vendor_master_log.created_on) = 30 THEN 'thirty' WHEN day(view_vendor_master_log.created_on) = 31 THEN 'thirtyone' END  as day,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('pcc');

		$this->db->join("pcc_vendor_log",'pcc_vendor_log.case_id = pcc.id');
       
        $this->db->join('view_vendor_master_log','(view_vendor_master_log.case_id = pcc_vendor_log.id  and component = "crimver" and component_tbl_id = "8")');

	    $this->db->where('year(view_vendor_master_log.created_on)', $params['year']);

        $this->db->where('month(view_vendor_master_log.created_on)', $params['month']);
	
		$this->db->where_in('view_vendor_master_log.final_status',$array_pcc_initiated);
   
		$this->db->group_by('pcc.vendor_id,year(view_vendor_master_log.created_on), month(view_vendor_master_log.created_on), day(view_vendor_master_log.created_on)');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$pcc_initiated = $result->result_array();




		$this->db->select("pcc.vendor_id,year(view_vendor_master_log.created_on) as year, month(view_vendor_master_log.created_on) as month,CASE  WHEN view_vendor_master_log.final_status = 'wip' THEN 'WIP' END AS status_value,CASE  WHEN day(view_vendor_master_log.created_on) = 1 THEN 'one' WHEN day(view_vendor_master_log.created_on) = 2 THEN 'two' WHEN day(view_vendor_master_log.created_on) = 3 THEN 'three' WHEN day(view_vendor_master_log.created_on) = 4 THEN 'four' WHEN  day(view_vendor_master_log.created_on) = 5 THEN 'five' WHEN day(view_vendor_master_log.created_on) = 6 THEN 'six' WHEN day(view_vendor_master_log.created_on) = 7 THEN 'seven' WHEN day(view_vendor_master_log.created_on) = 8 THEN 'eight' WHEN day(view_vendor_master_log.created_on) = 9 THEN 'nine' WHEN day(view_vendor_master_log.created_on) = 10 THEN 'ten' WHEN day(view_vendor_master_log.created_on) = 11 THEN 'eleven' WHEN day(view_vendor_master_log.created_on) = 12 THEN 'twelve' WHEN day(view_vendor_master_log.created_on) = 13 THEN 'thirteen' WHEN day(view_vendor_master_log.created_on) = 14 THEN 'fourteen' WHEN day(view_vendor_master_log.created_on) = 15 THEN 'fifteen' WHEN day(view_vendor_master_log.created_on) = 16 THEN 'sixteen' WHEN day(view_vendor_master_log.created_on) = 17 THEN 'seventeen' WHEN day(view_vendor_master_log.created_on) = 18 THEN 'eightteen' WHEN day(view_vendor_master_log.created_on) = 19 THEN 'nineteen' WHEN day(view_vendor_master_log.created_on) = 20 THEN 'twenty' WHEN day(view_vendor_master_log.created_on) = 21 THEN 'twentyone'  WHEN day(view_vendor_master_log.created_on) = 22 THEN 'twentytwo' WHEN day(view_vendor_master_log.created_on) = 23 THEN 'twentythree'  WHEN day(view_vendor_master_log.created_on) = 24 THEN 'twentyfour' WHEN day(view_vendor_master_log.created_on) = 25 THEN 'twentyfive' WHEN day(view_vendor_master_log.created_on) = 26 THEN 'twentysix' WHEN day(view_vendor_master_log.created_on) = 27 THEN 'twentyseven' WHEN day(view_vendor_master_log.created_on) = 28 THEN 'twentyeight' WHEN day(view_vendor_master_log.created_on) = 29 THEN 'twentynine' WHEN day(view_vendor_master_log.created_on) = 30 THEN 'thirty' WHEN day(view_vendor_master_log.created_on) = 31 THEN 'thirtyone' END  as day,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('pcc');

		$this->db->join("pcc_vendor_log",'pcc_vendor_log.case_id = pcc.id');
       
        $this->db->join('view_vendor_master_log','(view_vendor_master_log.case_id = pcc_vendor_log.id  and component = "crimver" and component_tbl_id = "8")');

	    $this->db->where('year(view_vendor_master_log.created_on)', $params['year']);

        $this->db->where('month(view_vendor_master_log.created_on)', $params['month']);
	
		$this->db->where_in('view_vendor_master_log.final_status',$array_pcc_wip);
   
		$this->db->group_by('pcc.vendor_id,year(view_vendor_master_log.created_on), month(view_vendor_master_log.created_on), day(view_vendor_master_log.created_on)');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$pcc_wip = $result->result_array();


		
		$this->db->select("pcc.vendor_id,year(view_vendor_master_log.modified_on) as year, month(view_vendor_master_log.modified_on) as month,CASE  WHEN view_vendor_master_log.final_status = 'insufficiency' THEN 'Insufficiency'  END AS status_value,CASE  WHEN day(view_vendor_master_log.modified_on) = 1 THEN 'one' WHEN day(view_vendor_master_log.modified_on) = 2 THEN 'two' WHEN day(view_vendor_master_log.modified_on) = 3 THEN 'three' WHEN day(view_vendor_master_log.modified_on) = 4 THEN 'four' WHEN  day(view_vendor_master_log.modified_on) = 5 THEN 'five' WHEN day(view_vendor_master_log.modified_on) = 6 THEN 'six' WHEN day(view_vendor_master_log.modified_on) = 7 THEN 'seven' WHEN day(view_vendor_master_log.modified_on) = 8 THEN 'eight' WHEN day(view_vendor_master_log.modified_on) = 9 THEN 'nine' WHEN day(view_vendor_master_log.modified_on) = 10 THEN 'ten' WHEN day(view_vendor_master_log.modified_on) = 11 THEN 'eleven' WHEN day(view_vendor_master_log.modified_on) = 12 THEN 'twelve' WHEN day(view_vendor_master_log.modified_on) = 13 THEN 'thirteen' WHEN day(view_vendor_master_log.modified_on) = 14 THEN 'fourteen' WHEN day(view_vendor_master_log.modified_on) = 15 THEN 'fifteen' WHEN day(view_vendor_master_log.modified_on) = 16 THEN 'sixteen' WHEN day(view_vendor_master_log.modified_on) = 17 THEN 'seventeen' WHEN day(view_vendor_master_log.modified_on) = 18 THEN 'eightteen' WHEN day(view_vendor_master_log.modified_on) = 19 THEN 'nineteen' WHEN day(view_vendor_master_log.modified_on) = 20 THEN 'twenty' WHEN day(view_vendor_master_log.modified_on) = 21 THEN 'twentyone'  WHEN day(view_vendor_master_log.modified_on) = 22 THEN 'twentytwo' WHEN day(view_vendor_master_log.modified_on) = 23 THEN 'twentythree'  WHEN day(view_vendor_master_log.modified_on) = 24 THEN 'twentyfour' WHEN day(view_vendor_master_log.modified_on) = 25 THEN 'twentyfive' WHEN day(view_vendor_master_log.modified_on) = 26 THEN 'twentysix' WHEN day(view_vendor_master_log.modified_on) = 27 THEN 'twentyseven' WHEN day(view_vendor_master_log.modified_on) = 28 THEN 'twentyeight' WHEN day(view_vendor_master_log.modified_on) = 29 THEN 'twentynine' WHEN day(view_vendor_master_log.modified_on) = 30 THEN 'thirty' WHEN day(view_vendor_master_log.modified_on) = 31 THEN 'thirtyone' END  as day,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('pcc');

		$this->db->join("pcc_vendor_log",'pcc_vendor_log.case_id = pcc.id');
       
        $this->db->join('view_vendor_master_log','(view_vendor_master_log.case_id = pcc_vendor_log.id  and component = "crimver" and component_tbl_id = "8")');
        
		$this->db->where('year(view_vendor_master_log.modified_on)', $params['year']);

        $this->db->where('month(view_vendor_master_log.modified_on)', $params['month']); 

	    $this->db->where_in('view_vendor_master_log.final_status',$array_pcc_insuff);
   
		$this->db->group_by('pcc.vendor_id,year(view_vendor_master_log.modified_on), month(view_vendor_master_log.modified_on), day(view_vendor_master_log.modified_on)');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$pcc_insuff = $result->result_array();



		$this->db->select("pcc.vendor_id,year(view_vendor_master_log.modified_on) as year, month(view_vendor_master_log.modified_on) as month,CASE  WHEN view_vendor_master_log.final_status = 'closed' THEN 'Closed'  WHEN view_vendor_master_log.final_status = 'approve' THEN 'Closed' END AS status_value,CASE  WHEN day(view_vendor_master_log.modified_on) = 1 THEN 'one' WHEN day(view_vendor_master_log.modified_on) = 2 THEN 'two' WHEN day(view_vendor_master_log.modified_on) = 3 THEN 'three' WHEN day(view_vendor_master_log.modified_on) = 4 THEN 'four' WHEN  day(view_vendor_master_log.modified_on) = 5 THEN 'five' WHEN day(view_vendor_master_log.modified_on) = 6 THEN 'six' WHEN day(view_vendor_master_log.modified_on) = 7 THEN 'seven' WHEN day(view_vendor_master_log.modified_on) = 8 THEN 'eight' WHEN day(view_vendor_master_log.modified_on) = 9 THEN 'nine' WHEN day(view_vendor_master_log.modified_on) = 10 THEN 'ten' WHEN day(view_vendor_master_log.modified_on) = 11 THEN 'eleven' WHEN day(view_vendor_master_log.modified_on) = 12 THEN 'twelve' WHEN day(view_vendor_master_log.modified_on) = 13 THEN 'thirteen' WHEN day(view_vendor_master_log.modified_on) = 14 THEN 'fourteen' WHEN day(view_vendor_master_log.modified_on) = 15 THEN 'fifteen' WHEN day(view_vendor_master_log.modified_on) = 16 THEN 'sixteen' WHEN day(view_vendor_master_log.modified_on) = 17 THEN 'seventeen' WHEN day(view_vendor_master_log.modified_on) = 18 THEN 'eightteen' WHEN day(view_vendor_master_log.modified_on) = 19 THEN 'nineteen' WHEN day(view_vendor_master_log.modified_on) = 20 THEN 'twenty' WHEN day(view_vendor_master_log.modified_on) = 21 THEN 'twentyone'  WHEN day(view_vendor_master_log.modified_on) = 22 THEN 'twentytwo' WHEN day(view_vendor_master_log.modified_on) = 23 THEN 'twentythree'  WHEN day(view_vendor_master_log.modified_on) = 24 THEN 'twentyfour' WHEN day(view_vendor_master_log.modified_on) = 25 THEN 'twentyfive' WHEN day(view_vendor_master_log.modified_on) = 26 THEN 'twentysix' WHEN day(view_vendor_master_log.modified_on) = 27 THEN 'twentyseven' WHEN day(view_vendor_master_log.modified_on) = 28 THEN 'twentyeight' WHEN day(view_vendor_master_log.modified_on) = 29 THEN 'twentynine' WHEN day(view_vendor_master_log.modified_on) = 30 THEN 'thirty' WHEN day(view_vendor_master_log.modified_on) = 31 THEN 'thirtyone' END  as day,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('pcc');

		$this->db->join("pcc_vendor_log",'pcc_vendor_log.case_id = pcc.id');
       
        $this->db->join('view_vendor_master_log','(view_vendor_master_log.case_id = pcc_vendor_log.id  and component = "crimver" and component_tbl_id = "8")');
         
		$this->db->where('year(view_vendor_master_log.modified_on)', $params['year']);

        $this->db->where('month(view_vendor_master_log.modified_on)', $params['month']); 
 
        $this->db->where_in('view_vendor_master_log.final_status',$array_pcc_closed);

		$this->db->group_by('pcc.vendor_id,year(view_vendor_master_log.modified_on), month(view_vendor_master_log.modified_on), day(view_vendor_master_log.modified_on)');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$pcc_closed = $result->result_array();



        $array_identity_initiated =  array('wip','insufficiency','closed','approve');
		$array_identity_wip =  array('wip');
		$array_identity_insuff =  array('insufficiency');
        $array_identity_closed =  array('closed','approve');


		$this->db->select("identity.vendor_id,year(view_vendor_master_log.created_on) as year, month(view_vendor_master_log.created_on) as month,year(view_vendor_master_log.created_on) as year, month(view_vendor_master_log.created_on) as month,CASE  WHEN view_vendor_master_log.final_status = 'wip' THEN 'currentwip'   END AS status_value,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('identity');

		$this->db->join("identity_vendor_log",'identity_vendor_log.case_id = identity.id');
       
        $this->db->join('view_vendor_master_log','(view_vendor_master_log.case_id = identity_vendor_log.id  and component = "identity" and component_tbl_id = "9")');

		$this->db->where('year(view_vendor_master_log.created_on) <=', $params['year']);

        $this->db->where('month(view_vendor_master_log.created_on) <', $params['month']);
	
		$this->db->where_in('view_vendor_master_log.final_status',$array_identity_wip);
   
		$this->db->group_by('identity.vendor_id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$identity_currentwip = $result->result_array();


		$this->db->select("identity.vendor_id,year(view_vendor_master_log.created_on) as year, month(view_vendor_master_log.created_on) as month,year(view_vendor_master_log.created_on) as year, month(view_vendor_master_log.created_on) as month,CASE  WHEN view_vendor_master_log.final_status = 'insufficiency' THEN 'currentinsuff'  END AS status_value,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('identity');

		$this->db->join("identity_vendor_log",'identity_vendor_log.case_id = identity.id');
       
        $this->db->join('view_vendor_master_log','(view_vendor_master_log.case_id = identity_vendor_log.id  and component = "identity" and component_tbl_id = "9")');

		$this->db->where('year(view_vendor_master_log.created_on) <=', $params['year']);

        $this->db->where('month(view_vendor_master_log.created_on) <', $params['month']);
	
		$this->db->where_in('view_vendor_master_log.final_status',$array_identity_insuff);
   
		$this->db->group_by('identity.vendor_id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$identity_currentinsuff = $result->result_array();




		$this->db->select("identity.vendor_id,year(view_vendor_master_log.created_on) as year, month(view_vendor_master_log.created_on) as month,year(view_vendor_master_log.created_on) as year, month(view_vendor_master_log.created_on) as month,CASE  WHEN view_vendor_master_log.final_status = 'wip' THEN 'Initiated' WHEN view_vendor_master_log.final_status = 'insufficiency' THEN 'Initiated' WHEN view_vendor_master_log.final_status = 'closed' THEN 'Initiated'  WHEN view_vendor_master_log.final_status = 'approve' THEN 'Initiated'  END AS status_value,CASE  WHEN day(view_vendor_master_log.created_on) = 1 THEN 'one' WHEN day(view_vendor_master_log.created_on) = 2 THEN 'two' WHEN day(view_vendor_master_log.created_on) = 3 THEN 'three' WHEN day(view_vendor_master_log.created_on) = 4 THEN 'four' WHEN  day(view_vendor_master_log.created_on) = 5 THEN 'five' WHEN day(view_vendor_master_log.created_on) = 6 THEN 'six' WHEN day(view_vendor_master_log.created_on) = 7 THEN 'seven' WHEN day(view_vendor_master_log.created_on) = 8 THEN 'eight' WHEN day(view_vendor_master_log.created_on) = 9 THEN 'nine' WHEN day(view_vendor_master_log.created_on) = 10 THEN 'ten' WHEN day(view_vendor_master_log.created_on) = 11 THEN 'eleven' WHEN day(view_vendor_master_log.created_on) = 12 THEN 'twelve' WHEN day(view_vendor_master_log.created_on) = 13 THEN 'thirteen' WHEN day(view_vendor_master_log.created_on) = 14 THEN 'fourteen' WHEN day(view_vendor_master_log.created_on) = 15 THEN 'fifteen' WHEN day(view_vendor_master_log.created_on) = 16 THEN 'sixteen' WHEN day(view_vendor_master_log.created_on) = 17 THEN 'seventeen' WHEN day(view_vendor_master_log.created_on) = 18 THEN 'eightteen' WHEN day(view_vendor_master_log.created_on) = 19 THEN 'nineteen' WHEN day(view_vendor_master_log.created_on) = 20 THEN 'twenty' WHEN day(view_vendor_master_log.created_on) = 21 THEN 'twentyone'  WHEN day(view_vendor_master_log.created_on) = 22 THEN 'twentytwo' WHEN day(view_vendor_master_log.created_on) = 23 THEN 'twentythree'  WHEN day(view_vendor_master_log.created_on) = 24 THEN 'twentyfour' WHEN day(view_vendor_master_log.created_on) = 25 THEN 'twentyfive' WHEN day(view_vendor_master_log.created_on) = 26 THEN 'twentysix' WHEN day(view_vendor_master_log.created_on) = 27 THEN 'twentyseven' WHEN day(view_vendor_master_log.created_on) = 28 THEN 'twentyeight' WHEN day(view_vendor_master_log.created_on) = 29 THEN 'twentynine' WHEN day(view_vendor_master_log.created_on) = 30 THEN 'thirty' WHEN day(view_vendor_master_log.created_on) = 31 THEN 'thirtyone' END  as day,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('identity');

		$this->db->join("identity_vendor_log",'identity_vendor_log.case_id = identity.id');
       
        $this->db->join('view_vendor_master_log','(view_vendor_master_log.case_id = identity_vendor_log.id  and component = "identity" and component_tbl_id = "9")');

	    $this->db->where('year(view_vendor_master_log.created_on)', $params['year']);

        $this->db->where('month(view_vendor_master_log.created_on)', $params['month']);
	
		$this->db->where_in('view_vendor_master_log.final_status',$array_identity_initiated);
   
		$this->db->group_by('identity.vendor_id,year(view_vendor_master_log.created_on), month(view_vendor_master_log.created_on), day(view_vendor_master_log.created_on)');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$identity_initiated = $result->result_array();


		$this->db->select("identity.vendor_id,year(view_vendor_master_log.created_on) as year, month(view_vendor_master_log.created_on) as month,year(view_vendor_master_log.created_on) as year, month(view_vendor_master_log.created_on) as month,CASE  WHEN view_vendor_master_log.final_status = 'wip' THEN 'WIP'   END AS status_value,CASE  WHEN day(view_vendor_master_log.created_on) = 1 THEN 'one' WHEN day(view_vendor_master_log.created_on) = 2 THEN 'two' WHEN day(view_vendor_master_log.created_on) = 3 THEN 'three' WHEN day(view_vendor_master_log.created_on) = 4 THEN 'four' WHEN  day(view_vendor_master_log.created_on) = 5 THEN 'five' WHEN day(view_vendor_master_log.created_on) = 6 THEN 'six' WHEN day(view_vendor_master_log.created_on) = 7 THEN 'seven' WHEN day(view_vendor_master_log.created_on) = 8 THEN 'eight' WHEN day(view_vendor_master_log.created_on) = 9 THEN 'nine' WHEN day(view_vendor_master_log.created_on) = 10 THEN 'ten' WHEN day(view_vendor_master_log.created_on) = 11 THEN 'eleven' WHEN day(view_vendor_master_log.created_on) = 12 THEN 'twelve' WHEN day(view_vendor_master_log.created_on) = 13 THEN 'thirteen' WHEN day(view_vendor_master_log.created_on) = 14 THEN 'fourteen' WHEN day(view_vendor_master_log.created_on) = 15 THEN 'fifteen' WHEN day(view_vendor_master_log.created_on) = 16 THEN 'sixteen' WHEN day(view_vendor_master_log.created_on) = 17 THEN 'seventeen' WHEN day(view_vendor_master_log.created_on) = 18 THEN 'eightteen' WHEN day(view_vendor_master_log.created_on) = 19 THEN 'nineteen' WHEN day(view_vendor_master_log.created_on) = 20 THEN 'twenty' WHEN day(view_vendor_master_log.created_on) = 21 THEN 'twentyone'  WHEN day(view_vendor_master_log.created_on) = 22 THEN 'twentytwo' WHEN day(view_vendor_master_log.created_on) = 23 THEN 'twentythree'  WHEN day(view_vendor_master_log.created_on) = 24 THEN 'twentyfour' WHEN day(view_vendor_master_log.created_on) = 25 THEN 'twentyfive' WHEN day(view_vendor_master_log.created_on) = 26 THEN 'twentysix' WHEN day(view_vendor_master_log.created_on) = 27 THEN 'twentyseven' WHEN day(view_vendor_master_log.created_on) = 28 THEN 'twentyeight' WHEN day(view_vendor_master_log.created_on) = 29 THEN 'twentynine' WHEN day(view_vendor_master_log.created_on) = 30 THEN 'thirty' WHEN day(view_vendor_master_log.created_on) = 31 THEN 'thirtyone' END  as day,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('identity');

		$this->db->join("identity_vendor_log",'identity_vendor_log.case_id = identity.id');
       
        $this->db->join('view_vendor_master_log','(view_vendor_master_log.case_id = identity_vendor_log.id  and component = "identity" and component_tbl_id = "9")');

	    $this->db->where('year(view_vendor_master_log.created_on)', $params['year']);

        $this->db->where('month(view_vendor_master_log.created_on)', $params['month']);
	
		$this->db->where_in('view_vendor_master_log.final_status',$array_identity_wip);
   
		$this->db->group_by('identity.vendor_id,year(view_vendor_master_log.created_on), month(view_vendor_master_log.created_on), day(view_vendor_master_log.created_on)');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$identity_wip = $result->result_array();


		
		$this->db->select("identity.vendor_id,year(view_vendor_master_log.modified_on) as year, month(view_vendor_master_log.modified_on) as month,CASE  WHEN view_vendor_master_log.final_status = 'insufficiency' THEN 'Insufficiency'  END AS status_value,CASE  WHEN day(view_vendor_master_log.modified_on) = 1 THEN 'one' WHEN day(view_vendor_master_log.modified_on) = 2 THEN 'two' WHEN day(view_vendor_master_log.modified_on) = 3 THEN 'three' WHEN day(view_vendor_master_log.modified_on) = 4 THEN 'four' WHEN  day(view_vendor_master_log.modified_on) = 5 THEN 'five' WHEN day(view_vendor_master_log.modified_on) = 6 THEN 'six' WHEN day(view_vendor_master_log.modified_on) = 7 THEN 'seven' WHEN day(view_vendor_master_log.modified_on) = 8 THEN 'eight' WHEN day(view_vendor_master_log.modified_on) = 9 THEN 'nine' WHEN day(view_vendor_master_log.modified_on) = 10 THEN 'ten' WHEN day(view_vendor_master_log.modified_on) = 11 THEN 'eleven' WHEN day(view_vendor_master_log.modified_on) = 12 THEN 'twelve' WHEN day(view_vendor_master_log.modified_on) = 13 THEN 'thirteen' WHEN day(view_vendor_master_log.modified_on) = 14 THEN 'fourteen' WHEN day(view_vendor_master_log.modified_on) = 15 THEN 'fifteen' WHEN day(view_vendor_master_log.modified_on) = 16 THEN 'sixteen' WHEN day(view_vendor_master_log.modified_on) = 17 THEN 'seventeen' WHEN day(view_vendor_master_log.modified_on) = 18 THEN 'eightteen' WHEN day(view_vendor_master_log.modified_on) = 19 THEN 'nineteen' WHEN day(view_vendor_master_log.modified_on) = 20 THEN 'twenty' WHEN day(view_vendor_master_log.modified_on) = 21 THEN 'twentyone'  WHEN day(view_vendor_master_log.modified_on) = 22 THEN 'twentytwo' WHEN day(view_vendor_master_log.modified_on) = 23 THEN 'twentythree'  WHEN day(view_vendor_master_log.modified_on) = 24 THEN 'twentyfour' WHEN day(view_vendor_master_log.modified_on) = 25 THEN 'twentyfive' WHEN day(view_vendor_master_log.modified_on) = 26 THEN 'twentysix' WHEN day(view_vendor_master_log.modified_on) = 27 THEN 'twentyseven' WHEN day(view_vendor_master_log.modified_on) = 28 THEN 'twentyeight' WHEN day(view_vendor_master_log.modified_on) = 29 THEN 'twentynine' WHEN day(view_vendor_master_log.modified_on) = 30 THEN 'thirty' WHEN day(view_vendor_master_log.modified_on) = 31 THEN 'thirtyone' END  as day,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('identity');

		$this->db->join("identity_vendor_log",'identity_vendor_log.case_id = identity.id');
       
        $this->db->join('view_vendor_master_log','(view_vendor_master_log.case_id = identity_vendor_log.id  and component = "identity" and component_tbl_id = "9")');
        

		$this->db->where('year(view_vendor_master_log.modified_on)', $params['year']);

        $this->db->where('month(view_vendor_master_log.modified_on)', $params['month']); 

	    $this->db->where_in('view_vendor_master_log.final_status',$array_identity_insuff);
   
		$this->db->group_by('identity.vendor_id,year(view_vendor_master_log.modified_on), month(view_vendor_master_log.modified_on), day(view_vendor_master_log.modified_on)');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$identity_insuff = $result->result_array();



		$this->db->select("identity.vendor_id,year(view_vendor_master_log.modified_on) as year, month(view_vendor_master_log.modified_on) as month,CASE  WHEN view_vendor_master_log.final_status = 'closed' THEN 'Closed'  WHEN view_vendor_master_log.final_status = 'approve' THEN 'Closed' END AS status_value,CASE  WHEN day(view_vendor_master_log.modified_on) = 1 THEN 'one' WHEN day(view_vendor_master_log.modified_on) = 2 THEN 'two' WHEN day(view_vendor_master_log.modified_on) = 3 THEN 'three' WHEN day(view_vendor_master_log.modified_on) = 4 THEN 'four' WHEN  day(view_vendor_master_log.modified_on) = 5 THEN 'five' WHEN day(view_vendor_master_log.modified_on) = 6 THEN 'six' WHEN day(view_vendor_master_log.modified_on) = 7 THEN 'seven' WHEN day(view_vendor_master_log.modified_on) = 8 THEN 'eight' WHEN day(view_vendor_master_log.modified_on) = 9 THEN 'nine' WHEN day(view_vendor_master_log.modified_on) = 10 THEN 'ten' WHEN day(view_vendor_master_log.modified_on) = 11 THEN 'eleven' WHEN day(view_vendor_master_log.modified_on) = 12 THEN 'twelve' WHEN day(view_vendor_master_log.modified_on) = 13 THEN 'thirteen' WHEN day(view_vendor_master_log.modified_on) = 14 THEN 'fourteen' WHEN day(view_vendor_master_log.modified_on) = 15 THEN 'fifteen' WHEN day(view_vendor_master_log.modified_on) = 16 THEN 'sixteen' WHEN day(view_vendor_master_log.modified_on) = 17 THEN 'seventeen' WHEN day(view_vendor_master_log.modified_on) = 18 THEN 'eightteen' WHEN day(view_vendor_master_log.modified_on) = 19 THEN 'nineteen' WHEN day(view_vendor_master_log.modified_on) = 20 THEN 'twenty' WHEN day(view_vendor_master_log.modified_on) = 21 THEN 'twentyone'  WHEN day(view_vendor_master_log.modified_on) = 22 THEN 'twentytwo' WHEN day(view_vendor_master_log.modified_on) = 23 THEN 'twentythree'  WHEN day(view_vendor_master_log.modified_on) = 24 THEN 'twentyfour' WHEN day(view_vendor_master_log.modified_on) = 25 THEN 'twentyfive' WHEN day(view_vendor_master_log.modified_on) = 26 THEN 'twentysix' WHEN day(view_vendor_master_log.modified_on) = 27 THEN 'twentyseven' WHEN day(view_vendor_master_log.modified_on) = 28 THEN 'twentyeight' WHEN day(view_vendor_master_log.modified_on) = 29 THEN 'twentynine' WHEN day(view_vendor_master_log.modified_on) = 30 THEN 'thirty' WHEN day(view_vendor_master_log.modified_on) = 31 THEN 'thirtyone' END  as day,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('identity');

		$this->db->join("identity_vendor_log",'identity_vendor_log.case_id = identity.id');
       
        $this->db->join('view_vendor_master_log','(view_vendor_master_log.case_id = identity_vendor_log.id  and component = "identity" and component_tbl_id = "9")');
         
		$this->db->where('year(view_vendor_master_log.modified_on)', $params['year']);

        $this->db->where('month(view_vendor_master_log.modified_on)', $params['month']); 

        $this->db->where_in('view_vendor_master_log.final_status',$array_identity_closed);

		$this->db->group_by('identity.vendor_id,year(view_vendor_master_log.modified_on), month(view_vendor_master_log.modified_on), day(view_vendor_master_log.modified_on)');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$identity_closed = $result->result_array();

		
        $array_credit_initiated =  array('wip','insufficiency','closed','approve');
		$array_credit_wip =  array('wip');
		$array_credit_insuff =  array('insufficiency');
        $array_credit_closed =  array('closed','approve');


		$this->db->select("credit_report.vendor_id,year(view_vendor_master_log.created_on) as year, month(view_vendor_master_log.created_on) as month,CASE  WHEN view_vendor_master_log.final_status = 'wip' THEN 'currentwip'   END AS status_value,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('credit_report');

		$this->db->join("credit_report_vendor_log",'credit_report_vendor_log.case_id = credit_report.id');
       
        $this->db->join('view_vendor_master_log','(view_vendor_master_log.case_id = credit_report_vendor_log.id  and component = "cbrver" and component_tbl_id = "10")');

		$this->db->where('year(view_vendor_master_log.created_on) <=', $params['year']);

        $this->db->where('month(view_vendor_master_log.created_on) <', $params['month']);
		
		$this->db->where_in('view_vendor_master_log.final_status',$array_credit_wip);
   
		$this->db->group_by('credit_report.vendor_id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$credit_report_currentwip = $result->result_array();



		$this->db->select("credit_report.vendor_id,year(view_vendor_master_log.created_on) as year, month(view_vendor_master_log.created_on) as month,CASE  WHEN view_vendor_master_log.final_status = 'insufficiency' THEN 'currentinsuff'  END AS status_value,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('credit_report');

		$this->db->join("credit_report_vendor_log",'credit_report_vendor_log.case_id = credit_report.id');
       
        $this->db->join('view_vendor_master_log','(view_vendor_master_log.case_id = credit_report_vendor_log.id  and component = "cbrver" and component_tbl_id = "10")');

		$this->db->where('year(view_vendor_master_log.created_on) <=', $params['year']);

        $this->db->where('month(view_vendor_master_log.created_on) <', $params['month']);
		
		$this->db->where_in('view_vendor_master_log.final_status',$array_credit_insuff);
   
		$this->db->group_by('credit_report.vendor_id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$credit_report_currentinsuff = $result->result_array();

		
		$this->db->select("credit_report.vendor_id,year(view_vendor_master_log.created_on) as year, month(view_vendor_master_log.created_on) as month,CASE  WHEN view_vendor_master_log.final_status = 'wip' THEN 'Initiated' WHEN view_vendor_master_log.final_status = 'insufficiency' THEN 'Initiated' WHEN view_vendor_master_log.final_status = 'closed' THEN 'Initiated'  WHEN view_vendor_master_log.final_status = 'approve' THEN 'Initiated'   END AS status_value,CASE  WHEN day(view_vendor_master_log.created_on) = 1 THEN 'one' WHEN day(view_vendor_master_log.created_on) = 2 THEN 'two' WHEN day(view_vendor_master_log.created_on) = 3 THEN 'three' WHEN day(view_vendor_master_log.created_on) = 4 THEN 'four' WHEN  day(view_vendor_master_log.created_on) = 5 THEN 'five' WHEN day(view_vendor_master_log.created_on) = 6 THEN 'six' WHEN day(view_vendor_master_log.created_on) = 7 THEN 'seven' WHEN day(view_vendor_master_log.created_on) = 8 THEN 'eight' WHEN day(view_vendor_master_log.created_on) = 9 THEN 'nine' WHEN day(view_vendor_master_log.created_on) = 10 THEN 'ten' WHEN day(view_vendor_master_log.created_on) = 11 THEN 'eleven' WHEN day(view_vendor_master_log.created_on) = 12 THEN 'twelve' WHEN day(view_vendor_master_log.created_on) = 13 THEN 'thirteen' WHEN day(view_vendor_master_log.created_on) = 14 THEN 'fourteen' WHEN day(view_vendor_master_log.created_on) = 15 THEN 'fifteen' WHEN day(view_vendor_master_log.created_on) = 16 THEN 'sixteen' WHEN day(view_vendor_master_log.created_on) = 17 THEN 'seventeen' WHEN day(view_vendor_master_log.created_on) = 18 THEN 'eightteen' WHEN day(view_vendor_master_log.created_on) = 19 THEN 'nineteen' WHEN day(view_vendor_master_log.created_on) = 20 THEN 'twenty' WHEN day(view_vendor_master_log.created_on) = 21 THEN 'twentyone'  WHEN day(view_vendor_master_log.created_on) = 22 THEN 'twentytwo' WHEN day(view_vendor_master_log.created_on) = 23 THEN 'twentythree'  WHEN day(view_vendor_master_log.created_on) = 24 THEN 'twentyfour' WHEN day(view_vendor_master_log.created_on) = 25 THEN 'twentyfive' WHEN day(view_vendor_master_log.created_on) = 26 THEN 'twentysix' WHEN day(view_vendor_master_log.created_on) = 27 THEN 'twentyseven' WHEN day(view_vendor_master_log.created_on) = 28 THEN 'twentyeight' WHEN day(view_vendor_master_log.created_on) = 29 THEN 'twentynine' WHEN day(view_vendor_master_log.created_on) = 30 THEN 'thirty' WHEN day(view_vendor_master_log.created_on) = 31 THEN 'thirtyone' END  as day,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('credit_report');

		$this->db->join("credit_report_vendor_log",'credit_report_vendor_log.case_id = credit_report.id');
       
        $this->db->join('view_vendor_master_log','(view_vendor_master_log.case_id = credit_report_vendor_log.id  and component = "cbrver" and component_tbl_id = "10")');

	    $this->db->where('year(view_vendor_master_log.created_on)', $params['year']);

        $this->db->where('month(view_vendor_master_log.created_on)', $params['month']);
		
		$this->db->where_in('view_vendor_master_log.final_status',$array_credit_initiated);
   
		$this->db->group_by('credit_report.vendor_id,year(view_vendor_master_log.created_on), month(view_vendor_master_log.created_on), day(view_vendor_master_log.created_on)');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$credit_report_initiated = $result->result_array();


		$this->db->select("credit_report.vendor_id,year(view_vendor_master_log.created_on) as year, month(view_vendor_master_log.created_on) as month,CASE  WHEN view_vendor_master_log.final_status = 'wip' THEN 'WIP'   END AS status_value,CASE  WHEN day(view_vendor_master_log.created_on) = 1 THEN 'one' WHEN day(view_vendor_master_log.created_on) = 2 THEN 'two' WHEN day(view_vendor_master_log.created_on) = 3 THEN 'three' WHEN day(view_vendor_master_log.created_on) = 4 THEN 'four' WHEN  day(view_vendor_master_log.created_on) = 5 THEN 'five' WHEN day(view_vendor_master_log.created_on) = 6 THEN 'six' WHEN day(view_vendor_master_log.created_on) = 7 THEN 'seven' WHEN day(view_vendor_master_log.created_on) = 8 THEN 'eight' WHEN day(view_vendor_master_log.created_on) = 9 THEN 'nine' WHEN day(view_vendor_master_log.created_on) = 10 THEN 'ten' WHEN day(view_vendor_master_log.created_on) = 11 THEN 'eleven' WHEN day(view_vendor_master_log.created_on) = 12 THEN 'twelve' WHEN day(view_vendor_master_log.created_on) = 13 THEN 'thirteen' WHEN day(view_vendor_master_log.created_on) = 14 THEN 'fourteen' WHEN day(view_vendor_master_log.created_on) = 15 THEN 'fifteen' WHEN day(view_vendor_master_log.created_on) = 16 THEN 'sixteen' WHEN day(view_vendor_master_log.created_on) = 17 THEN 'seventeen' WHEN day(view_vendor_master_log.created_on) = 18 THEN 'eightteen' WHEN day(view_vendor_master_log.created_on) = 19 THEN 'nineteen' WHEN day(view_vendor_master_log.created_on) = 20 THEN 'twenty' WHEN day(view_vendor_master_log.created_on) = 21 THEN 'twentyone'  WHEN day(view_vendor_master_log.created_on) = 22 THEN 'twentytwo' WHEN day(view_vendor_master_log.created_on) = 23 THEN 'twentythree'  WHEN day(view_vendor_master_log.created_on) = 24 THEN 'twentyfour' WHEN day(view_vendor_master_log.created_on) = 25 THEN 'twentyfive' WHEN day(view_vendor_master_log.created_on) = 26 THEN 'twentysix' WHEN day(view_vendor_master_log.created_on) = 27 THEN 'twentyseven' WHEN day(view_vendor_master_log.created_on) = 28 THEN 'twentyeight' WHEN day(view_vendor_master_log.created_on) = 29 THEN 'twentynine' WHEN day(view_vendor_master_log.created_on) = 30 THEN 'thirty' WHEN day(view_vendor_master_log.created_on) = 31 THEN 'thirtyone' END  as day,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('credit_report');

		$this->db->join("credit_report_vendor_log",'credit_report_vendor_log.case_id = credit_report.id');
       
        $this->db->join('view_vendor_master_log','(view_vendor_master_log.case_id = credit_report_vendor_log.id  and component = "cbrver" and component_tbl_id = "10")');

	    $this->db->where('year(view_vendor_master_log.created_on)', $params['year']);

        $this->db->where('month(view_vendor_master_log.created_on)', $params['month']);
		
		$this->db->where_in('view_vendor_master_log.final_status',$array_credit_wip);
   
		$this->db->group_by('credit_report.vendor_id,year(view_vendor_master_log.created_on), month(view_vendor_master_log.created_on), day(view_vendor_master_log.created_on)');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$credit_report_wip = $result->result_array();


		
		$this->db->select("credit_report.vendor_id,year(view_vendor_master_log.modified_on) as year, month(view_vendor_master_log.modified_on) as month,CASE  WHEN view_vendor_master_log.final_status = 'insufficiency' THEN 'Insufficiency'  END AS status_value,CASE  WHEN day(view_vendor_master_log.modified_on) = 1 THEN 'one' WHEN day(view_vendor_master_log.modified_on) = 2 THEN 'two' WHEN day(view_vendor_master_log.modified_on) = 3 THEN 'three' WHEN day(view_vendor_master_log.modified_on) = 4 THEN 'four' WHEN  day(view_vendor_master_log.modified_on) = 5 THEN 'five' WHEN day(view_vendor_master_log.modified_on) = 6 THEN 'six' WHEN day(view_vendor_master_log.modified_on) = 7 THEN 'seven' WHEN day(view_vendor_master_log.modified_on) = 8 THEN 'eight' WHEN day(view_vendor_master_log.modified_on) = 9 THEN 'nine' WHEN day(view_vendor_master_log.modified_on) = 10 THEN 'ten' WHEN day(view_vendor_master_log.modified_on) = 11 THEN 'eleven' WHEN day(view_vendor_master_log.modified_on) = 12 THEN 'twelve' WHEN day(view_vendor_master_log.modified_on) = 13 THEN 'thirteen' WHEN day(view_vendor_master_log.modified_on) = 14 THEN 'fourteen' WHEN day(view_vendor_master_log.modified_on) = 15 THEN 'fifteen' WHEN day(view_vendor_master_log.modified_on) = 16 THEN 'sixteen' WHEN day(view_vendor_master_log.modified_on) = 17 THEN 'seventeen' WHEN day(view_vendor_master_log.modified_on) = 18 THEN 'eightteen' WHEN day(view_vendor_master_log.modified_on) = 19 THEN 'nineteen' WHEN day(view_vendor_master_log.modified_on) = 20 THEN 'twenty' WHEN day(view_vendor_master_log.modified_on) = 21 THEN 'twentyone'  WHEN day(view_vendor_master_log.modified_on) = 22 THEN 'twentytwo' WHEN day(view_vendor_master_log.modified_on) = 23 THEN 'twentythree'  WHEN day(view_vendor_master_log.modified_on) = 24 THEN 'twentyfour' WHEN day(view_vendor_master_log.modified_on) = 25 THEN 'twentyfive' WHEN day(view_vendor_master_log.modified_on) = 26 THEN 'twentysix' WHEN day(view_vendor_master_log.modified_on) = 27 THEN 'twentyseven' WHEN day(view_vendor_master_log.modified_on) = 28 THEN 'twentyeight' WHEN day(view_vendor_master_log.modified_on) = 29 THEN 'twentynine' WHEN day(view_vendor_master_log.modified_on) = 30 THEN 'thirty' WHEN day(view_vendor_master_log.modified_on) = 31 THEN 'thirtyone' END  as day,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('credit_report');

		$this->db->join("credit_report_vendor_log",'credit_report_vendor_log.case_id = credit_report.id');
       
        $this->db->join('view_vendor_master_log','(view_vendor_master_log.case_id = credit_report_vendor_log.id  and component = "cbrver" and component_tbl_id = "10")');


		$this->db->where('year(view_vendor_master_log.modified_on)', $params['year']);

        $this->db->where('month(view_vendor_master_log.modified_on)', $params['month']); 


	    $this->db->where_in('view_vendor_master_log.final_status',$array_credit_insuff);
   
		$this->db->group_by('credit_report.vendor_id,year(view_vendor_master_log.modified_on), month(view_vendor_master_log.modified_on), day(view_vendor_master_log.modified_on)');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$credit_report_insuff = $result->result_array();



		$this->db->select("credit_report.vendor_id,year(view_vendor_master_log.modified_on) as year, month(view_vendor_master_log.modified_on) as month,CASE  WHEN view_vendor_master_log.final_status = 'closed' THEN 'Closed'  WHEN view_vendor_master_log.final_status = 'approve' THEN 'Closed' END AS status_value,CASE  WHEN day(view_vendor_master_log.modified_on) = 1 THEN 'one' WHEN day(view_vendor_master_log.modified_on) = 2 THEN 'two' WHEN day(view_vendor_master_log.modified_on) = 3 THEN 'three' WHEN day(view_vendor_master_log.modified_on) = 4 THEN 'four' WHEN  day(view_vendor_master_log.modified_on) = 5 THEN 'five' WHEN day(view_vendor_master_log.modified_on) = 6 THEN 'six' WHEN day(view_vendor_master_log.modified_on) = 7 THEN 'seven' WHEN day(view_vendor_master_log.modified_on) = 8 THEN 'eight' WHEN day(view_vendor_master_log.modified_on) = 9 THEN 'nine' WHEN day(view_vendor_master_log.modified_on) = 10 THEN 'ten' WHEN day(view_vendor_master_log.modified_on) = 11 THEN 'eleven' WHEN day(view_vendor_master_log.modified_on) = 12 THEN 'twelve' WHEN day(view_vendor_master_log.modified_on) = 13 THEN 'thirteen' WHEN day(view_vendor_master_log.modified_on) = 14 THEN 'fourteen' WHEN day(view_vendor_master_log.modified_on) = 15 THEN 'fifteen' WHEN day(view_vendor_master_log.modified_on) = 16 THEN 'sixteen' WHEN day(view_vendor_master_log.modified_on) = 17 THEN 'seventeen' WHEN day(view_vendor_master_log.modified_on) = 18 THEN 'eightteen' WHEN day(view_vendor_master_log.modified_on) = 19 THEN 'nineteen' WHEN day(view_vendor_master_log.modified_on) = 20 THEN 'twenty' WHEN day(view_vendor_master_log.modified_on) = 21 THEN 'twentyone'  WHEN day(view_vendor_master_log.modified_on) = 22 THEN 'twentytwo' WHEN day(view_vendor_master_log.modified_on) = 23 THEN 'twentythree'  WHEN day(view_vendor_master_log.modified_on) = 24 THEN 'twentyfour' WHEN day(view_vendor_master_log.modified_on) = 25 THEN 'twentyfive' WHEN day(view_vendor_master_log.modified_on) = 26 THEN 'twentysix' WHEN day(view_vendor_master_log.modified_on) = 27 THEN 'twentyseven' WHEN day(view_vendor_master_log.modified_on) = 28 THEN 'twentyeight' WHEN day(view_vendor_master_log.modified_on) = 29 THEN 'twentynine' WHEN day(view_vendor_master_log.modified_on) = 30 THEN 'thirty' WHEN day(view_vendor_master_log.modified_on) = 31 THEN 'thirtyone' END  as day,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('credit_report');

		$this->db->join("credit_report_vendor_log",'credit_report_vendor_log.case_id = credit_report.id');
       
        $this->db->join('view_vendor_master_log','(view_vendor_master_log.case_id = credit_report_vendor_log.id  and component = "cbrver" and component_tbl_id = "10")');
         
		$this->db->where('year(view_vendor_master_log.modified_on)', $params['year']);

        $this->db->where('month(view_vendor_master_log.modified_on)', $params['month']); 
 
        $this->db->where_in('view_vendor_master_log.final_status',$array_credit_closed);

		$this->db->group_by('credit_report.vendor_id,year(view_vendor_master_log.modified_on), month(view_vendor_master_log.modified_on), day(view_vendor_master_log.modified_on)');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$credit_report_closed = $result->result_array();

		$array_drugs_initiated =  array('wip','insufficiency','closed','approve');
		$array_drugs_wip =  array('wip');
		$array_drugs_insuff =  array('insufficiency');
        $array_drugs_closed =  array('closed','approve');


		$this->db->select("drug_narcotis.vendor_id,year(view_vendor_master_log.created_on) as year, month(view_vendor_master_log.created_on) as month,CASE  WHEN view_vendor_master_log.final_status = 'wip' THEN 'currentwip'  END AS status_value,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('drug_narcotis');

		$this->db->join("drug_narcotis_vendor_log",'drug_narcotis_vendor_log.case_id = drug_narcotis.id');
       
        $this->db->join('view_vendor_master_log','(view_vendor_master_log.case_id = drug_narcotis_vendor_log.id  and component = "narcver" and component_tbl_id = "7")');

		$this->db->where('year(view_vendor_master_log.created_on) <=', $params['year']);

        $this->db->where('month(view_vendor_master_log.created_on) <', $params['month']);
		
		$this->db->where_in('view_vendor_master_log.final_status',$array_drugs_wip);
   
		$this->db->group_by('drug_narcotis.vendor_id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$drug_narcotis_currentwip = $result->result_array();



		$this->db->select("drug_narcotis.vendor_id,year(view_vendor_master_log.created_on) as year, month(view_vendor_master_log.created_on) as month,CASE  WHEN view_vendor_master_log.final_status = 'insufficiency' THEN 'currentinsuff'  END AS status_value,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('drug_narcotis');

		$this->db->join("drug_narcotis_vendor_log",'drug_narcotis_vendor_log.case_id = drug_narcotis.id');
       
        $this->db->join('view_vendor_master_log','(view_vendor_master_log.case_id = drug_narcotis_vendor_log.id  and component = "narcver" and component_tbl_id = "7")');

		$this->db->where('year(view_vendor_master_log.created_on) <=', $params['year']);

        $this->db->where('month(view_vendor_master_log.created_on) <', $params['month']);
		
		$this->db->where_in('view_vendor_master_log.final_status',$array_drugs_insuff);
   
		$this->db->group_by('drug_narcotis.vendor_id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$drug_narcotis_currentinsuff = $result->result_array();


		$this->db->select("drug_narcotis.vendor_id,year(view_vendor_master_log.created_on) as year, month(view_vendor_master_log.created_on) as month,CASE  WHEN view_vendor_master_log.final_status = 'wip' THEN 'Initiated' WHEN view_vendor_master_log.final_status = 'insufficiency' THEN 'Initiated' WHEN view_vendor_master_log.final_status = 'closed' THEN 'Initiated'  WHEN view_vendor_master_log.final_status = 'approve' THEN 'Initiated' END AS status_value,CASE  WHEN day(view_vendor_master_log.created_on) = 1 THEN 'one' WHEN day(view_vendor_master_log.created_on) = 2 THEN 'two' WHEN day(view_vendor_master_log.created_on) = 3 THEN 'three' WHEN day(view_vendor_master_log.created_on) = 4 THEN 'four' WHEN  day(view_vendor_master_log.created_on) = 5 THEN 'five' WHEN day(view_vendor_master_log.created_on) = 6 THEN 'six' WHEN day(view_vendor_master_log.created_on) = 7 THEN 'seven' WHEN day(view_vendor_master_log.created_on) = 8 THEN 'eight' WHEN day(view_vendor_master_log.created_on) = 9 THEN 'nine' WHEN day(view_vendor_master_log.created_on) = 10 THEN 'ten' WHEN day(view_vendor_master_log.created_on) = 11 THEN 'eleven' WHEN day(view_vendor_master_log.created_on) = 12 THEN 'twelve' WHEN day(view_vendor_master_log.created_on) = 13 THEN 'thirteen' WHEN day(view_vendor_master_log.created_on) = 14 THEN 'fourteen' WHEN day(view_vendor_master_log.created_on) = 15 THEN 'fifteen' WHEN day(view_vendor_master_log.created_on) = 16 THEN 'sixteen' WHEN day(view_vendor_master_log.created_on) = 17 THEN 'seventeen' WHEN day(view_vendor_master_log.created_on) = 18 THEN 'eightteen' WHEN day(view_vendor_master_log.created_on) = 19 THEN 'nineteen' WHEN day(view_vendor_master_log.created_on) = 20 THEN 'twenty' WHEN day(view_vendor_master_log.created_on) = 21 THEN 'twentyone'  WHEN day(view_vendor_master_log.created_on) = 22 THEN 'twentytwo' WHEN day(view_vendor_master_log.created_on) = 23 THEN 'twentythree'  WHEN day(view_vendor_master_log.created_on) = 24 THEN 'twentyfour' WHEN day(view_vendor_master_log.created_on) = 25 THEN 'twentyfive' WHEN day(view_vendor_master_log.created_on) = 26 THEN 'twentysix' WHEN day(view_vendor_master_log.created_on) = 27 THEN 'twentyseven' WHEN day(view_vendor_master_log.created_on) = 28 THEN 'twentyeight' WHEN day(view_vendor_master_log.created_on) = 29 THEN 'twentynine' WHEN day(view_vendor_master_log.created_on) = 30 THEN 'thirty' WHEN day(view_vendor_master_log.created_on) = 31 THEN 'thirtyone' END  as day,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('drug_narcotis');

		$this->db->join("drug_narcotis_vendor_log",'drug_narcotis_vendor_log.case_id = drug_narcotis.id');
       
        $this->db->join('view_vendor_master_log','(view_vendor_master_log.case_id = drug_narcotis_vendor_log.id  and component = "narcver" and component_tbl_id = "7")');

	    $this->db->where('year(view_vendor_master_log.created_on)', $params['year']);

        $this->db->where('month(view_vendor_master_log.created_on)', $params['month']);
		
		$this->db->where_in('view_vendor_master_log.final_status',$array_drugs_initiated);
   
		$this->db->group_by('drug_narcotis.vendor_id,year(view_vendor_master_log.created_on), month(view_vendor_master_log.created_on), day(view_vendor_master_log.created_on)');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$drug_narcotis_initiated = $result->result_array();



		$this->db->select("drug_narcotis.vendor_id,year(view_vendor_master_log.created_on) as year, month(view_vendor_master_log.created_on) as month,CASE  WHEN view_vendor_master_log.final_status = 'wip' THEN 'WIP'   END AS status_value,CASE  WHEN day(view_vendor_master_log.created_on) = 1 THEN 'one' WHEN day(view_vendor_master_log.created_on) = 2 THEN 'two' WHEN day(view_vendor_master_log.created_on) = 3 THEN 'three' WHEN day(view_vendor_master_log.created_on) = 4 THEN 'four' WHEN  day(view_vendor_master_log.created_on) = 5 THEN 'five' WHEN day(view_vendor_master_log.created_on) = 6 THEN 'six' WHEN day(view_vendor_master_log.created_on) = 7 THEN 'seven' WHEN day(view_vendor_master_log.created_on) = 8 THEN 'eight' WHEN day(view_vendor_master_log.created_on) = 9 THEN 'nine' WHEN day(view_vendor_master_log.created_on) = 10 THEN 'ten' WHEN day(view_vendor_master_log.created_on) = 11 THEN 'eleven' WHEN day(view_vendor_master_log.created_on) = 12 THEN 'twelve' WHEN day(view_vendor_master_log.created_on) = 13 THEN 'thirteen' WHEN day(view_vendor_master_log.created_on) = 14 THEN 'fourteen' WHEN day(view_vendor_master_log.created_on) = 15 THEN 'fifteen' WHEN day(view_vendor_master_log.created_on) = 16 THEN 'sixteen' WHEN day(view_vendor_master_log.created_on) = 17 THEN 'seventeen' WHEN day(view_vendor_master_log.created_on) = 18 THEN 'eightteen' WHEN day(view_vendor_master_log.created_on) = 19 THEN 'nineteen' WHEN day(view_vendor_master_log.created_on) = 20 THEN 'twenty' WHEN day(view_vendor_master_log.created_on) = 21 THEN 'twentyone'  WHEN day(view_vendor_master_log.created_on) = 22 THEN 'twentytwo' WHEN day(view_vendor_master_log.created_on) = 23 THEN 'twentythree'  WHEN day(view_vendor_master_log.created_on) = 24 THEN 'twentyfour' WHEN day(view_vendor_master_log.created_on) = 25 THEN 'twentyfive' WHEN day(view_vendor_master_log.created_on) = 26 THEN 'twentysix' WHEN day(view_vendor_master_log.created_on) = 27 THEN 'twentyseven' WHEN day(view_vendor_master_log.created_on) = 28 THEN 'twentyeight' WHEN day(view_vendor_master_log.created_on) = 29 THEN 'twentynine' WHEN day(view_vendor_master_log.created_on) = 30 THEN 'thirty' WHEN day(view_vendor_master_log.created_on) = 31 THEN 'thirtyone' END  as day,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('drug_narcotis');

		$this->db->join("drug_narcotis_vendor_log",'drug_narcotis_vendor_log.case_id = drug_narcotis.id');
       
        $this->db->join('view_vendor_master_log','(view_vendor_master_log.case_id = drug_narcotis_vendor_log.id  and component = "narcver" and component_tbl_id = "7")');

	    $this->db->where('year(view_vendor_master_log.created_on)', $params['year']);

        $this->db->where('month(view_vendor_master_log.created_on)', $params['month']);
		
		$this->db->where_in('view_vendor_master_log.final_status',$array_drugs_wip);
   
		$this->db->group_by('drug_narcotis.vendor_id,year(view_vendor_master_log.created_on), month(view_vendor_master_log.created_on), day(view_vendor_master_log.created_on)');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$drug_narcotis_wip = $result->result_array();


		
		$this->db->select("drug_narcotis.vendor_id,year(view_vendor_master_log.modified_on) as year, month(view_vendor_master_log.modified_on) as month,CASE  WHEN view_vendor_master_log.final_status = 'insufficiency' THEN 'Insufficiency'  END AS status_value,CASE  WHEN day(view_vendor_master_log.modified_on) = 1 THEN 'one' WHEN day(view_vendor_master_log.modified_on) = 2 THEN 'two' WHEN day(view_vendor_master_log.modified_on) = 3 THEN 'three' WHEN day(view_vendor_master_log.modified_on) = 4 THEN 'four' WHEN  day(view_vendor_master_log.modified_on) = 5 THEN 'five' WHEN day(view_vendor_master_log.modified_on) = 6 THEN 'six' WHEN day(view_vendor_master_log.modified_on) = 7 THEN 'seven' WHEN day(view_vendor_master_log.modified_on) = 8 THEN 'eight' WHEN day(view_vendor_master_log.modified_on) = 9 THEN 'nine' WHEN day(view_vendor_master_log.modified_on) = 10 THEN 'ten' WHEN day(view_vendor_master_log.modified_on) = 11 THEN 'eleven' WHEN day(view_vendor_master_log.modified_on) = 12 THEN 'twelve' WHEN day(view_vendor_master_log.modified_on) = 13 THEN 'thirteen' WHEN day(view_vendor_master_log.modified_on) = 14 THEN 'fourteen' WHEN day(view_vendor_master_log.modified_on) = 15 THEN 'fifteen' WHEN day(view_vendor_master_log.modified_on) = 16 THEN 'sixteen' WHEN day(view_vendor_master_log.modified_on) = 17 THEN 'seventeen' WHEN day(view_vendor_master_log.modified_on) = 18 THEN 'eightteen' WHEN day(view_vendor_master_log.modified_on) = 19 THEN 'nineteen' WHEN day(view_vendor_master_log.modified_on) = 20 THEN 'twenty' WHEN day(view_vendor_master_log.modified_on) = 21 THEN 'twentyone'  WHEN day(view_vendor_master_log.modified_on) = 22 THEN 'twentytwo' WHEN day(view_vendor_master_log.modified_on) = 23 THEN 'twentythree'  WHEN day(view_vendor_master_log.modified_on) = 24 THEN 'twentyfour' WHEN day(view_vendor_master_log.modified_on) = 25 THEN 'twentyfive' WHEN day(view_vendor_master_log.modified_on) = 26 THEN 'twentysix' WHEN day(view_vendor_master_log.modified_on) = 27 THEN 'twentyseven' WHEN day(view_vendor_master_log.modified_on) = 28 THEN 'twentyeight' WHEN day(view_vendor_master_log.modified_on) = 29 THEN 'twentynine' WHEN day(view_vendor_master_log.modified_on) = 30 THEN 'thirty' WHEN day(view_vendor_master_log.modified_on) = 31 THEN 'thirtyone' END  as day,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('drug_narcotis');

		$this->db->join("drug_narcotis_vendor_log",'drug_narcotis_vendor_log.case_id = drug_narcotis.id');
       
        $this->db->join('view_vendor_master_log','(view_vendor_master_log.case_id = drug_narcotis_vendor_log.id  and component = "narcver" and component_tbl_id = "7")');

		$this->db->where('year(view_vendor_master_log.modified_on)', $params['year']);

        $this->db->where('month(view_vendor_master_log.modified_on)', $params['month']); 

	    $this->db->where_in('view_vendor_master_log.final_status',$array_drugs_insuff);
   
		$this->db->group_by('drug_narcotis.vendor_id,year(view_vendor_master_log.modified_on), month(view_vendor_master_log.modified_on), day(view_vendor_master_log.modified_on)');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$drug_narcotis_insuff = $result->result_array();



		$this->db->select("drug_narcotis.vendor_id,year(view_vendor_master_log.modified_on) as year, month(view_vendor_master_log.modified_on) as month,CASE  WHEN view_vendor_master_log.final_status = 'closed' THEN 'Closed'  WHEN view_vendor_master_log.final_status = 'approve' THEN 'Closed' END AS status_value,CASE  WHEN day(view_vendor_master_log.modified_on) = 1 THEN 'one' WHEN day(view_vendor_master_log.modified_on) = 2 THEN 'two' WHEN day(view_vendor_master_log.modified_on) = 3 THEN 'three' WHEN day(view_vendor_master_log.modified_on) = 4 THEN 'four' WHEN  day(view_vendor_master_log.modified_on) = 5 THEN 'five' WHEN day(view_vendor_master_log.modified_on) = 6 THEN 'six' WHEN day(view_vendor_master_log.modified_on) = 7 THEN 'seven' WHEN day(view_vendor_master_log.modified_on) = 8 THEN 'eight' WHEN day(view_vendor_master_log.modified_on) = 9 THEN 'nine' WHEN day(view_vendor_master_log.modified_on) = 10 THEN 'ten' WHEN day(view_vendor_master_log.modified_on) = 11 THEN 'eleven' WHEN day(view_vendor_master_log.modified_on) = 12 THEN 'twelve' WHEN day(view_vendor_master_log.modified_on) = 13 THEN 'thirteen' WHEN day(view_vendor_master_log.modified_on) = 14 THEN 'fourteen' WHEN day(view_vendor_master_log.modified_on) = 15 THEN 'fifteen' WHEN day(view_vendor_master_log.modified_on) = 16 THEN 'sixteen' WHEN day(view_vendor_master_log.modified_on) = 17 THEN 'seventeen' WHEN day(view_vendor_master_log.modified_on) = 18 THEN 'eightteen' WHEN day(view_vendor_master_log.modified_on) = 19 THEN 'nineteen' WHEN day(view_vendor_master_log.modified_on) = 20 THEN 'twenty' WHEN day(view_vendor_master_log.modified_on) = 21 THEN 'twentyone'  WHEN day(view_vendor_master_log.modified_on) = 22 THEN 'twentytwo' WHEN day(view_vendor_master_log.modified_on) = 23 THEN 'twentythree'  WHEN day(view_vendor_master_log.modified_on) = 24 THEN 'twentyfour' WHEN day(view_vendor_master_log.modified_on) = 25 THEN 'twentyfive' WHEN day(view_vendor_master_log.modified_on) = 26 THEN 'twentysix' WHEN day(view_vendor_master_log.modified_on) = 27 THEN 'twentyseven' WHEN day(view_vendor_master_log.modified_on) = 28 THEN 'twentyeight' WHEN day(view_vendor_master_log.modified_on) = 29 THEN 'twentynine' WHEN day(view_vendor_master_log.modified_on) = 30 THEN 'thirty' WHEN day(view_vendor_master_log.modified_on) = 31 THEN 'thirtyone' END  as day,COUNT(view_vendor_master_log.final_status) as total");

		$this->db->from('drug_narcotis');

		$this->db->join("drug_narcotis_vendor_log",'drug_narcotis_vendor_log.case_id = drug_narcotis.id');
       
        $this->db->join('view_vendor_master_log','(view_vendor_master_log.case_id = drug_narcotis_vendor_log.id  and component = "narcver" and component_tbl_id = "7")');
         
		$this->db->where('year(view_vendor_master_log.modified_on)', $params['year']);

        $this->db->where('month(view_vendor_master_log.modified_on)', $params['month']); 
 
        $this->db->where_in('view_vendor_master_log.final_status',$array_drugs_closed);

		$this->db->group_by('drug_narcotis.vendor_id,year(view_vendor_master_log.modified_on), month(view_vendor_master_log.modified_on), day(view_vendor_master_log.modified_on)');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$drug_narcotis_closed = $result->result_array();

		$currentwip = array_merge($addrver_currentwip,$empver_currentwip,$court_currentwip,$global_currentwip,$pcc_currentwip,$identity_currentwip,$credit_report_currentwip,$drug_narcotis_currentwip);

		$currentinsuff = array_merge($addrver_currentinsuff,$empver_currentinsuff,$court_currentinsuff,$global_currentinsuff,$pcc_currentinsuff,$identity_currentinsuff,$credit_report_currentinsuff,$drug_narcotis_currentinsuff);

		$initiated = array_merge($addrver_initiated,$empver_initiated,$court_initiated,$global_initiated,$pcc_initiated,$identity_initiated,$credit_report_initiated,$drug_narcotis_initiated);

		$wip = array_merge($addrver_wip,$empver_wip,$court_wip,$global_wip,$pcc_wip,$identity_wip,$credit_report_wip,$drug_narcotis_wip);

		$insufficiency = array_merge($addrver_insuff,$empver_insuff,$court_insuff,$global_insuff,$pcc_insuff,$identity_insuff,$credit_report_insuff,$drug_narcotis_insuff);

		$closed = array_merge($addrver_closed,$empver_closed,$court_closed,$global_closed,$pcc_closed,$identity_closed,$credit_report_closed,$drug_narcotis_closed);
  
		$return = array();

		foreach ($currentwip as $key => $value) {
		
			$return[strtolower($value['status_value']).'_'.$value['vendor_id']][] = $value['total'];

		}

		foreach ($currentinsuff as $key => $value) {
		
			$return[strtolower($value['status_value']).'_'.$value['vendor_id']][] = $value['total'];

		}

		foreach ($initiated as $key => $value) {
		
			$return[strtolower($value['status_value']).'_'.strtolower($value['day']).'_'.$value['vendor_id']][] = $value['total'];

		}


		foreach ($wip as $key => $value) {
		
			$return[strtolower($value['status_value']).'_'.strtolower($value['day']).'_'.$value['vendor_id']][] = $value['total'];

		}

		foreach ($insufficiency as $key => $value) {
		
			$return[strtolower($value['status_value']).'_'.strtolower($value['day']).'_'.$value['vendor_id']][] = $value['total'];

		}

		foreach ($closed as $key => $value) {
		
			$return[strtolower($value['status_value']).'_'.strtolower($value['day']).'_'.$value['vendor_id']][] = $value['total'];

		}

	
		$result_all = array();
	
	
        foreach($return as $key => $subarray){

	        $sums = array();
			foreach( $subarray as $keys=>$val)
			{
				if(!isset($sums[$key])) $sums[$key]=0;

				$sums[$key] += $val;
			}


			$result_all[$key] = $sums;

       }
	 
	   return $result_all;



	}

/*	public function status_count_component($params)
	{

			
	    $array_condition_wip =  array('11','12','13','14','16','23','26','1');
		$array_condition_insuff =  array('18');
        $array_condition_closed =  array('9','17','19','20','21','22','24','25','27','28');

		$this->db->select("addrver.clientid,CASE  WHEN addrverres.verfstatus = 11 THEN 'WIP' WHEN addrverres.verfstatus = 12 THEN 'WIP' WHEN addrverres.verfstatus = 13 THEN 'WIP' WHEN addrverres.verfstatus = 14 THEN 'WIP' WHEN addrverres.verfstatus = 16 THEN 'WIP' WHEN addrverres.verfstatus = 23 THEN 'WIP' WHEN addrverres.verfstatus = 26 THEN 'WIP' WHEN addrverres.verfstatus = 1 THEN 'WIP'  END AS status_value,COUNT(addrverres.verfstatus) as total");

		$this->db->from('addrver');

		$this->db->join("addrverres",'addrverres.addrverid = addrver.id');


	    $this->db->where_in('addrverres.verfstatus',$array_condition_wip);
   
		$this->db->group_by('addrver.clientid');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$addrver_wip = $result->result_array();


		
		$this->db->select("addrver.clientid,CASE  WHEN addrverres.verfstatus = 18 THEN 'Insufficiency' END AS status_value,COUNT(addrverres.verfstatus) as total");

		$this->db->from('addrver');

		$this->db->join("addrverres",'addrverres.addrverid = addrver.id');

	    $this->db->where_in('addrverres.verfstatus',$array_condition_insuff);
   
		$this->db->group_by('addrver.clientid');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$addrver_insuff = $result->result_array();

        $where_condition_address = "DATE_FORMAT(`addrverres`.`closuredate`,'%Y-%m-%d') BETWEEN '".$params['year']."-".$params['month']."-01' AND '".$params['year']."-".$params['month']."-31'";


		$this->db->select("addrver.clientid,CASE WHEN  addrverres.verfstatus =  9 THEN 'Closed' WHEN addrverres.verfstatus =  17  THEN 'Closed' WHEN addrverres.verfstatus = 19  THEN 'Closed'  WHEN addrverres.verfstatus =  20 THEN 'Closed' WHEN addrverres.verfstatus = 21 THEN 'Closed'  WHEN addrverres.verfstatus = 22 THEN 'Closed' WHEN addrverres.verfstatus = 24 THEN 'Closed' WHEN addrverres.verfstatus = 25 THEN 'Closed' WHEN addrverres.verfstatus = 27 THEN 'Closed' WHEN addrverres.verfstatus = 28 THEN 'Closed'  END AS status_value,COUNT(addrverres.verfstatus) as total");

		$this->db->from('addrver');

		$this->db->join("addrverres",'addrverres.addrverid = addrver.id');
         
		$this->db->where($where_condition_address);
 
        $this->db->where_in('addrverres.verfstatus',$array_condition_closed);

		$this->db->group_by('addrver.clientid');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$addrver_closed = $result->result_array();

	

		$this->db->select("empver.clientid,CASE  WHEN empverres.verfstatus = 11 THEN 'WIP' WHEN empverres.verfstatus = 12 THEN 'WIP' WHEN empverres.verfstatus = 13 THEN 'WIP' WHEN empverres.verfstatus = 14 THEN 'WIP' WHEN empverres.verfstatus = 16 THEN 'WIP' WHEN empverres.verfstatus = 23 THEN 'WIP' WHEN empverres.verfstatus = 26 THEN 'WIP' WHEN empverres.verfstatus = 1 THEN 'WIP'  END AS status_value,COUNT(empverres.verfstatus) as total");

		$this->db->from('empver');

        $this->db->join("empverres",'empverres.empverid = empver.id');

	    $this->db->where_in('empverres.verfstatus',$array_condition_wip);
 
        $this->db->group_by('empver.clientid');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$empver_wip = $result->result_array();


		$this->db->select("empver.clientid,CASE  WHEN empverres.verfstatus = 18 THEN 'Insufficiency'   END AS status_value,COUNT(empverres.verfstatus) as total");

		$this->db->from('empver');

        $this->db->join("empverres",'empverres.empverid = empver.id');
    
	    $this->db->where_in('empverres.verfstatus',$array_condition_insuff);
 
        $this->db->group_by('empver.clientid');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$empver_insuff = $result->result_array();
        
		$where_condition_employment = "DATE_FORMAT(`empverres`.`closuredate`,'%Y-%m-%d') BETWEEN '".$params['year']."-".$params['month']."-01' AND '".$params['year']."-".$params['month']."-31'";


		$this->db->select("empver.clientid,CASE WHEN  empverres.verfstatus =  9 THEN 'Closed' WHEN empverres.verfstatus =  17  THEN 'Closed' WHEN empverres.verfstatus = 19  THEN 'Closed'  WHEN empverres.verfstatus =  20 THEN 'Closed' WHEN empverres.verfstatus = 21 THEN 'Closed'  WHEN empverres.verfstatus = 22 THEN 'Closed' WHEN empverres.verfstatus = 24 THEN 'Closed' WHEN empverres.verfstatus = 25 THEN 'Closed' WHEN empverres.verfstatus = 27 THEN 'Closed' WHEN empverres.verfstatus = 28 THEN 'Closed'  END AS status_value,COUNT(empverres.verfstatus) as total");

		$this->db->from('empver');

		$this->db->join("empverres",'empverres.empverid = empver.id');

		$this->db->where($where_condition_employment);

        $this->db->where_in('empverres.verfstatus',$array_condition_closed);

		$this->db->group_by('empver.clientid');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$empver_closed = $result->result_array();

		

		$this->db->select("education.clientid,CASE  WHEN education_result.verfstatus = 11 THEN 'WIP' WHEN education_result.verfstatus = 12 THEN 'WIP' WHEN education_result.verfstatus = 13 THEN 'WIP' WHEN education_result.verfstatus = 14 THEN 'WIP' WHEN education_result.verfstatus = 16 THEN 'WIP' WHEN education_result.verfstatus = 23 THEN 'WIP' WHEN education_result.verfstatus = 26 THEN 'WIP' WHEN education_result.verfstatus = 1 THEN 'WIP'  END AS status_value,COUNT(education_result.verfstatus) as total");

		$this->db->from('education');

        $this->db->join("education_result",'education_result.education_id = education.id');

	    $this->db->where_in('education_result.verfstatus',$array_condition_wip);
 
        $this->db->group_by('education.clientid');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$education_wip = $result->result_array();


		$this->db->select("education.clientid,CASE  WHEN education_result.verfstatus = 18 THEN 'Insufficiency'   END AS status_value,COUNT(education_result.verfstatus) as total");

		$this->db->from('education');

        $this->db->join("education_result",'education_result.education_id = education.id');
    
	    $this->db->where_in('education_result.verfstatus',$array_condition_insuff);
 
        $this->db->group_by('education.clientid');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$education_insuff = $result->result_array();

		$where_condition_education = "DATE_FORMAT(`education_result`.`closuredate`,'%Y-%m-%d') BETWEEN '".$params['year']."-".$params['month']."-01' AND '".$params['year']."-".$params['month']."-31'";

		$this->db->select("education.clientid,CASE WHEN  education_result.verfstatus =  9 THEN 'Closed' WHEN education_result.verfstatus =  17  THEN 'Closed' WHEN education_result.verfstatus = 19  THEN 'Closed'  WHEN education_result.verfstatus =  20 THEN 'Closed' WHEN education_result.verfstatus = 21 THEN 'Closed'  WHEN education_result.verfstatus = 22 THEN 'Closed' WHEN education_result.verfstatus = 24 THEN 'Closed' WHEN education_result.verfstatus = 25 THEN 'Closed' WHEN education_result.verfstatus = 27 THEN 'Closed' WHEN education_result.verfstatus = 28 THEN 'Closed'  END AS status_value,COUNT(education_result.verfstatus) as total");

		$this->db->from('education');

		$this->db->join("education_result",'education_result.education_id = education.id');

		$this->db->where($where_condition_education);

        $this->db->where_in('education_result.verfstatus',$array_condition_closed);

		$this->db->group_by('education.clientid');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$education_closed = $result->result_array();



		$this->db->select("reference.clientid,CASE  WHEN reference_result.verfstatus = 11 THEN 'WIP' WHEN reference_result.verfstatus = 12 THEN 'WIP' WHEN reference_result.verfstatus = 13 THEN 'WIP' WHEN reference_result.verfstatus = 14 THEN 'WIP' WHEN reference_result.verfstatus = 16 THEN 'WIP' WHEN reference_result.verfstatus = 23 THEN 'WIP' WHEN reference_result.verfstatus = 26 THEN 'WIP' WHEN reference_result.verfstatus = 1 THEN 'WIP'  END AS status_value,COUNT(reference_result.verfstatus) as total");

		$this->db->from('reference');

        $this->db->join("reference_result",'reference_result.reference_id = reference.id');

	    $this->db->where_in('reference_result.verfstatus',$array_condition_wip);
 
        $this->db->group_by('reference.clientid');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$reference_wip = $result->result_array();


		$this->db->select("reference.clientid,CASE  WHEN reference_result.verfstatus = 18 THEN 'Insufficiency'   END AS status_value,COUNT(reference_result.verfstatus) as total");

		$this->db->from('reference');

        $this->db->join("reference_result",'reference_result.reference_id = reference.id');
    
	    $this->db->where_in('reference_result.verfstatus',$array_condition_insuff);
 
        $this->db->group_by('reference.clientid');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$reference_insuff = $result->result_array();

		$where_condition_reference = "DATE_FORMAT(`reference_result`.`closuredate`,'%Y-%m-%d') BETWEEN '".$params['year']."-".$params['month']."-01' AND '".$params['year']."-".$params['month']."-31'";

		 
		$this->db->select("reference.clientid,CASE WHEN  reference_result.verfstatus =  9 THEN 'Closed' WHEN reference_result.verfstatus =  17  THEN 'Closed' WHEN reference_result.verfstatus = 19  THEN 'Closed'  WHEN reference_result.verfstatus =  20 THEN 'Closed' WHEN reference_result.verfstatus = 21 THEN 'Closed'  WHEN reference_result.verfstatus = 22 THEN 'Closed' WHEN reference_result.verfstatus = 24 THEN 'Closed' WHEN reference_result.verfstatus = 25 THEN 'Closed' WHEN reference_result.verfstatus = 27 THEN 'Closed' WHEN reference_result.verfstatus = 28 THEN 'Closed'  END AS status_value,COUNT(reference_result.verfstatus) as total");

		$this->db->from('reference');

		$this->db->join("reference_result",'reference_result.reference_id = reference.id');

		$this->db->where($where_condition_reference);

        $this->db->where_in('reference_result.verfstatus',$array_condition_closed);

		$this->db->group_by('reference.clientid');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$reference_closed = $result->result_array();


		$this->db->select("courtver.clientid,CASE  WHEN courtver_result.verfstatus = 11 THEN 'WIP' WHEN courtver_result.verfstatus = 12 THEN 'WIP' WHEN courtver_result.verfstatus = 13 THEN 'WIP' WHEN courtver_result.verfstatus = 14 THEN 'WIP' WHEN courtver_result.verfstatus = 16 THEN 'WIP' WHEN courtver_result.verfstatus = 23 THEN 'WIP' WHEN courtver_result.verfstatus = 26 THEN 'WIP' WHEN courtver_result.verfstatus = 1 THEN 'WIP'  END AS status_value,COUNT(courtver_result.verfstatus) as total");

		$this->db->from('courtver');

        $this->db->join("courtver_result",'courtver_result.courtver_id = courtver.id');

	    $this->db->where_in('courtver_result.verfstatus',$array_condition_wip);
 
        $this->db->group_by('courtver.clientid');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$court_wip = $result->result_array();


		$this->db->select("courtver.clientid,CASE  WHEN courtver_result.verfstatus = 18 THEN 'Insufficiency'   END AS status_value,COUNT(courtver_result.verfstatus) as total");

		$this->db->from('courtver');

        $this->db->join("courtver_result",'courtver_result.courtver_id = courtver.id');
    
	    $this->db->where_in('courtver_result.verfstatus',$array_condition_insuff);
 
        $this->db->group_by('courtver.clientid');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$court_insuff = $result->result_array();

		$where_condition_court = "DATE_FORMAT(`courtver_result`.`closuredate`,'%Y-%m-%d') BETWEEN '".$params['year']."-".$params['month']."-01' AND '".$params['year']."-".$params['month']."-31'";


		$this->db->select("courtver.clientid,CASE WHEN  courtver_result.verfstatus =  9 THEN 'Closed' WHEN courtver_result.verfstatus =  17  THEN 'Closed' WHEN courtver_result.verfstatus = 19  THEN 'Closed'  WHEN courtver_result.verfstatus =  20 THEN 'Closed' WHEN courtver_result.verfstatus = 21 THEN 'Closed'  WHEN courtver_result.verfstatus = 22 THEN 'Closed' WHEN courtver_result.verfstatus = 24 THEN 'Closed' WHEN courtver_result.verfstatus = 25 THEN 'Closed' WHEN courtver_result.verfstatus = 27 THEN 'Closed' WHEN courtver_result.verfstatus = 28 THEN 'Closed'  END AS status_value,COUNT(courtver_result.verfstatus) as total");

		$this->db->from('courtver');

		$this->db->join("courtver_result",'courtver_result.courtver_id = courtver.id');

		$this->db->where($where_condition_court);

        $this->db->where_in('courtver_result.verfstatus',$array_condition_closed);

		$this->db->group_by('courtver.clientid');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$court_closed = $result->result_array();



		$this->db->select("glodbver.clientid,CASE  WHEN glodbver_result.verfstatus = 11 THEN 'WIP' WHEN glodbver_result.verfstatus = 12 THEN 'WIP' WHEN glodbver_result.verfstatus = 13 THEN 'WIP' WHEN glodbver_result.verfstatus = 14 THEN 'WIP' WHEN glodbver_result.verfstatus = 16 THEN 'WIP' WHEN glodbver_result.verfstatus = 23 THEN 'WIP' WHEN glodbver_result.verfstatus = 26 THEN 'WIP' WHEN glodbver_result.verfstatus = 1 THEN 'WIP'  END AS status_value,COUNT(glodbver_result.verfstatus) as total");

		$this->db->from('glodbver');

        $this->db->join("glodbver_result",'glodbver_result.glodbver_id = glodbver.id');

	    $this->db->where_in('glodbver_result.verfstatus',$array_condition_wip);
 
        $this->db->group_by('glodbver.clientid');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$global_wip = $result->result_array();


		$this->db->select("glodbver.clientid,CASE  WHEN glodbver_result.verfstatus = 18 THEN 'Insufficiency'   END AS status_value,COUNT(glodbver_result.verfstatus) as total");

		$this->db->from('glodbver');

        $this->db->join("glodbver_result",'glodbver_result.glodbver_id = glodbver.id');
    
	    $this->db->where_in('glodbver_result.verfstatus',$array_condition_insuff);
 
        $this->db->group_by('glodbver.clientid');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$global_insuff = $result->result_array();


		$where_condition_global = "DATE_FORMAT(`glodbver_result`.`closuredate`,'%Y-%m-%d') BETWEEN '".$params['year']."-".$params['month']."-01' AND '".$params['year']."-".$params['month']."-31'";


		$this->db->select("glodbver.clientid,CASE WHEN  glodbver_result.verfstatus =  9 THEN 'Closed' WHEN glodbver_result.verfstatus =  17  THEN 'Closed' WHEN glodbver_result.verfstatus = 19  THEN 'Closed'  WHEN glodbver_result.verfstatus =  20 THEN 'Closed' WHEN glodbver_result.verfstatus = 21 THEN 'Closed'  WHEN glodbver_result.verfstatus = 22 THEN 'Closed' WHEN glodbver_result.verfstatus = 24 THEN 'Closed' WHEN glodbver_result.verfstatus = 25 THEN 'Closed' WHEN glodbver_result.verfstatus = 27 THEN 'Closed' WHEN glodbver_result.verfstatus = 28 THEN 'Closed'  END AS status_value,COUNT(glodbver_result.verfstatus) as total");

		$this->db->from('glodbver');

		$this->db->join("glodbver_result",'glodbver_result.glodbver_id = glodbver.id');

		$this->db->where($where_condition_global);


        $this->db->where_in('glodbver_result.verfstatus',$array_condition_closed);

		$this->db->group_by('glodbver.clientid');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$global_closed = $result->result_array();


		$this->db->select("pcc.clientid,CASE  WHEN pcc_result.verfstatus = 11 THEN 'WIP' WHEN pcc_result.verfstatus = 12 THEN 'WIP' WHEN pcc_result.verfstatus = 13 THEN 'WIP' WHEN pcc_result.verfstatus = 14 THEN 'WIP' WHEN pcc_result.verfstatus = 16 THEN 'WIP' WHEN pcc_result.verfstatus = 23 THEN 'WIP' WHEN pcc_result.verfstatus = 26 THEN 'WIP' WHEN pcc_result.verfstatus = 1 THEN 'WIP'  END AS status_value,COUNT(pcc_result.verfstatus) as total");

		$this->db->from('pcc');

        $this->db->join("pcc_result",'pcc_result.pcc_id = pcc.id');

	    $this->db->where_in('pcc_result.verfstatus',$array_condition_wip);
 
        $this->db->group_by('pcc.clientid');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$pcc_wip = $result->result_array();


		$this->db->select("pcc.clientid,CASE  WHEN pcc_result.verfstatus = 18 THEN 'Insufficiency'   END AS status_value,COUNT(pcc_result.verfstatus) as total");

		$this->db->from('pcc');

        $this->db->join("pcc_result",'pcc_result.pcc_id = pcc.id');
    
	    $this->db->where_in('pcc_result.verfstatus',$array_condition_insuff);
 
        $this->db->group_by('pcc.clientid');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$pcc_insuff = $result->result_array();


		$where_condition_pcc = "DATE_FORMAT(`pcc_result`.`closuredate`,'%Y-%m-%d') BETWEEN '".$params['year']."-".$params['month']."-01' AND '".$params['year']."-".$params['month']."-31'";

		$this->db->select("pcc.clientid,CASE WHEN  pcc_result.verfstatus =  9 THEN 'Closed' WHEN pcc_result.verfstatus =  17  THEN 'Closed' WHEN pcc_result.verfstatus = 19  THEN 'Closed'  WHEN pcc_result.verfstatus =  20 THEN 'Closed' WHEN pcc_result.verfstatus = 21 THEN 'Closed'  WHEN pcc_result.verfstatus = 22 THEN 'Closed' WHEN pcc_result.verfstatus = 24 THEN 'Closed' WHEN pcc_result.verfstatus = 25 THEN 'Closed' WHEN pcc_result.verfstatus = 27 THEN 'Closed' WHEN pcc_result.verfstatus = 28 THEN 'Closed'  END AS status_value,COUNT(pcc_result.verfstatus) as total");

		$this->db->from('pcc');

		$this->db->join("pcc_result",'pcc_result.pcc_id = pcc.id');

		$this->db->where($where_condition_pcc);

        $this->db->where_in('pcc_result.verfstatus',$array_condition_closed);

		$this->db->group_by('pcc.clientid');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$pcc_closed = $result->result_array();


		$this->db->select("identity.clientid,CASE  WHEN identity_result.verfstatus = 11 THEN 'WIP' WHEN identity_result.verfstatus = 12 THEN 'WIP' WHEN identity_result.verfstatus = 13 THEN 'WIP' WHEN identity_result.verfstatus = 14 THEN 'WIP' WHEN identity_result.verfstatus = 16 THEN 'WIP' WHEN identity_result.verfstatus = 23 THEN 'WIP' WHEN identity_result.verfstatus = 26 THEN 'WIP' WHEN identity_result.verfstatus = 1 THEN 'WIP'  END AS status_value,COUNT(identity_result.verfstatus) as total");

		$this->db->from('identity');

        $this->db->join("identity_result",'identity_result.identity_id = identity.id');

	    $this->db->where_in('identity_result.verfstatus',$array_condition_wip);
 
        $this->db->group_by('identity.clientid');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$identity_wip = $result->result_array();


		$this->db->select("identity.clientid,CASE  WHEN identity_result.verfstatus = 18 THEN 'Insufficiency'   END AS status_value,COUNT(identity_result.verfstatus) as total");

		$this->db->from('identity');

        $this->db->join("identity_result",'identity_result.identity_id = identity.id');
    
	    $this->db->where_in('identity_result.verfstatus',$array_condition_insuff);
 
        $this->db->group_by('identity.clientid');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$identity_insuff = $result->result_array();


		$where_condition_identity = "DATE_FORMAT(`identity_result`.`closuredate`,'%Y-%m-%d') BETWEEN '".$params['year']."-".$params['month']."-01' AND '".$params['year']."-".$params['month']."-31'";


		$this->db->select("identity.clientid,CASE WHEN  identity_result.verfstatus =  9 THEN 'Closed' WHEN identity_result.verfstatus =  17  THEN 'Closed' WHEN identity_result.verfstatus = 19  THEN 'Closed'  WHEN identity_result.verfstatus =  20 THEN 'Closed' WHEN identity_result.verfstatus = 21 THEN 'Closed'  WHEN identity_result.verfstatus = 22 THEN 'Closed' WHEN identity_result.verfstatus = 24 THEN 'Closed' WHEN identity_result.verfstatus = 25 THEN 'Closed' WHEN identity_result.verfstatus = 27 THEN 'Closed' WHEN identity_result.verfstatus = 28 THEN 'Closed'  END AS status_value,COUNT(identity_result.verfstatus) as total");

		$this->db->from('identity');

		$this->db->join("identity_result",'identity_result.identity_id = identity.id');

		$this->db->where($where_condition_identity);

        $this->db->where_in('identity_result.verfstatus',$array_condition_closed);

		$this->db->group_by('identity.clientid');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$identity_closed = $result->result_array();




		$this->db->select("credit_report.clientid,CASE  WHEN credit_report_result.verfstatus = 11 THEN 'WIP' WHEN credit_report_result.verfstatus = 12 THEN 'WIP' WHEN credit_report_result.verfstatus = 13 THEN 'WIP' WHEN credit_report_result.verfstatus = 14 THEN 'WIP' WHEN credit_report_result.verfstatus = 16 THEN 'WIP' WHEN credit_report_result.verfstatus = 23 THEN 'WIP' WHEN credit_report_result.verfstatus = 26 THEN 'WIP' WHEN credit_report_result.verfstatus = 1 THEN 'WIP'  END AS status_value,COUNT(credit_report_result.verfstatus) as total");

		$this->db->from('credit_report');

        $this->db->join("credit_report_result",'credit_report_result.credit_report_id = credit_report.id');


	    $this->db->where_in('credit_report_result.verfstatus',$array_condition_wip);
 
        $this->db->group_by('credit_report.clientid');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$credit_report_wip = $result->result_array();


		$this->db->select("credit_report.clientid,CASE  WHEN credit_report_result.verfstatus = 18 THEN 'Insufficiency'   END AS status_value,COUNT(credit_report_result.verfstatus) as total");

		$this->db->from('credit_report');

        $this->db->join("credit_report_result",'credit_report_result.credit_report_id = credit_report.id');
    
	    $this->db->where_in('credit_report_result.verfstatus',$array_condition_insuff);
 
        $this->db->group_by('credit_report.clientid');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$credit_report_insuff = $result->result_array();

		$where_condition_credit_report = "DATE_FORMAT(`credit_report_result`.`closuredate`,'%Y-%m-%d') BETWEEN '".$params['year']."-".$params['month']."-01' AND '".$params['year']."-".$params['month']."-31'";

		$this->db->select("credit_report.clientid,CASE WHEN  credit_report_result.verfstatus =  9 THEN 'Closed' WHEN credit_report_result.verfstatus =  17  THEN 'Closed' WHEN credit_report_result.verfstatus = 19  THEN 'Closed'  WHEN credit_report_result.verfstatus =  20 THEN 'Closed' WHEN credit_report_result.verfstatus = 21 THEN 'Closed'  WHEN credit_report_result.verfstatus = 22 THEN 'Closed' WHEN credit_report_result.verfstatus = 24 THEN 'Closed' WHEN credit_report_result.verfstatus = 25 THEN 'Closed' WHEN credit_report_result.verfstatus = 27 THEN 'Closed' WHEN credit_report_result.verfstatus = 28 THEN 'Closed'  END AS status_value,COUNT(credit_report_result.verfstatus) as total");

		$this->db->from('credit_report');

		$this->db->join("credit_report_result",'credit_report_result.credit_report_id = credit_report.id');

		$this->db->where($where_condition_credit_report);

        $this->db->where_in('credit_report_result.verfstatus',$array_condition_closed);

		$this->db->group_by('credit_report.clientid');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$credit_report_closed = $result->result_array();

        
		$this->db->select("drug_narcotis.clientid,CASE  WHEN drug_narcotis_result.verfstatus = 11 THEN 'WIP' WHEN drug_narcotis_result.verfstatus = 12 THEN 'WIP' WHEN drug_narcotis_result.verfstatus = 13 THEN 'WIP' WHEN drug_narcotis_result.verfstatus = 14 THEN 'WIP' WHEN drug_narcotis_result.verfstatus = 16 THEN 'WIP' WHEN drug_narcotis_result.verfstatus = 23 THEN 'WIP' WHEN drug_narcotis_result.verfstatus = 26 THEN 'WIP' WHEN drug_narcotis_result.verfstatus = 1 THEN 'WIP'  END AS status_value,COUNT(drug_narcotis_result.verfstatus) as total");

		$this->db->from('drug_narcotis');

        $this->db->join("drug_narcotis_result",'drug_narcotis_result.drug_narcotis_id = drug_narcotis.id');

	    $this->db->where_in('drug_narcotis_result.verfstatus',$array_condition_wip);
 
        $this->db->group_by('drug_narcotis.clientid');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$drug_narcotis_wip = $result->result_array();


		$this->db->select("drug_narcotis.clientid,CASE  WHEN drug_narcotis_result.verfstatus = 18 THEN 'Insufficiency'   END AS status_value,COUNT(drug_narcotis_result.verfstatus) as total");

		$this->db->from('drug_narcotis');

        $this->db->join("drug_narcotis_result",'drug_narcotis_result.drug_narcotis_id = drug_narcotis.id');
    
	    $this->db->where_in('drug_narcotis_result.verfstatus',$array_condition_insuff);
 
        $this->db->group_by('drug_narcotis.clientid');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$drug_narcotis_insuff = $result->result_array();


		$where_condition_drugs = "DATE_FORMAT(`drug_narcotis_result`.`closuredate`,'%Y-%m-%d') BETWEEN '".$params['year']."-".$params['month']."-01' AND '".$params['year']."-".$params['month']."-31'";

		$this->db->select("drug_narcotis.clientid,CASE WHEN  drug_narcotis_result.verfstatus =  9 THEN 'Closed' WHEN drug_narcotis_result.verfstatus =  17  THEN 'Closed' WHEN drug_narcotis_result.verfstatus = 19  THEN 'Closed'  WHEN drug_narcotis_result.verfstatus =  20 THEN 'Closed' WHEN drug_narcotis_result.verfstatus = 21 THEN 'Closed'  WHEN drug_narcotis_result.verfstatus = 22 THEN 'Closed' WHEN drug_narcotis_result.verfstatus = 24 THEN 'Closed' WHEN drug_narcotis_result.verfstatus = 25 THEN 'Closed' WHEN drug_narcotis_result.verfstatus = 27 THEN 'Closed' WHEN drug_narcotis_result.verfstatus = 28 THEN 'Closed'  END AS status_value,COUNT(drug_narcotis_result.verfstatus) as total");

		$this->db->from('drug_narcotis');

		$this->db->join("drug_narcotis_result",'drug_narcotis_result.drug_narcotis_id = drug_narcotis.id');

		$this->db->where($where_condition_drugs);

        $this->db->where_in('drug_narcotis_result.verfstatus',$array_condition_closed);

		$this->db->group_by('drug_narcotis.clientid');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$drug_narcotis_closed = $result->result_array();

		$wip = array_merge($addrver_wip,$empver_wip,$education_wip,$reference_wip,$court_wip,$global_wip,$pcc_wip,$identity_wip,$credit_report_wip,$drug_narcotis_wip);

		$insufficiency = array_merge($addrver_insuff,$empver_insuff,$education_insuff,$reference_insuff,$court_insuff,$global_insuff,$pcc_insuff,$identity_insuff,$credit_report_insuff,$drug_narcotis_insuff);

		$closed = array_merge($addrver_closed,$empver_closed,$education_closed,$reference_closed,$court_closed,$global_closed,$pcc_closed,$identity_closed,$credit_report_closed,$drug_narcotis_closed);

		foreach ($wip as $key => $value) {
		
			$return[strtolower($value['status_value']).'_'.$value['clientid']][] = $value['total'];

		}

		foreach ($insufficiency as $key => $value) {
		
			$return[strtolower($value['status_value']).'_'.$value['clientid']][] = $value['total'];

		}

		foreach ($closed as $key => $value) {
		
			$return[strtolower($value['status_value']).'_'.$value['clientid']][] = $value['total'];

		}

		
		$result_all = array();
	
	
        foreach($return as $key => $subarray){

	        $sums = array();
			foreach( $subarray as $keys=>$val)
			{
				if(!isset($sums[$key])) $sums[$key]=0;

				$sums[$key] += $val;
			}


			$result_all[$key] = $sums;

       }

	   return $result_all;

	}*/

	public function get_all_cand_with_search($clientid,$entity_id,$package_id)
	{
  
		$this->db->select("candidates_info.id,candidates_info.clientid,candidates_info.CandidateName,candidates_info.caserecddate,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.cands_email_id,candidates_info.CandidatesContactNumber,candidates_info.due_date_candidate,candidates_info.Location,clients.clientname,candidates_info.overallclosuredate,status.status_value,candidates_info.overallstatus,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,clients_details.component_id");

		$this->db->from('candidates_info');

		$this->db->join("clients",'clients.id = candidates_info.clientid');

	    $this->db->join("status",'status.id = candidates_info.overallstatus');

		$this->db->join("clients_details",'clients_details.tbl_clients_id = candidates_info.clientid and `clients_details`.`entity` = `candidates_info`.`entity` AND `clients_details`.`package` = `candidates_info`.`package`');


		if(!empty($clientid))
		{
			$this->db->where("candidates_info.clientid",$clientid);
		}
		if(!empty($entity_id))
		{
			$this->db->where("candidates_info.entity",$entity_id);
		}
		if(!empty($package_id))
		{
			$this->db->where("candidates_info.package",$package_id);
		}

		$this->db->order_by('candidates_info.id', 'asc');
		
		$this->db->group_by('candidates_info.id');

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());
		
		return $result->result_array();
	} 

	public function get_addres_ver_status_for_export($clientid)
	{
		$this->db->select("addrver.id,status.status_value as verfstatus,ev1.closuredate,(SELECT GROUP_CONCAT(concat(DATE_FORMAT(addrver_insuff.insuff_raised_date,'%Y-%m-%d')) SEPARATOR '||') FROM addrver_insuff where addrver_insuff.addrverid = addrver.id) as insuff_raised_date,(SELECT GROUP_CONCAT(concat(DATE_FORMAT(addrver_insuff.insuff_clear_date,'%Y-%m-%d')) SEPARATOR '||') FROM addrver_insuff where addrver_insuff.addrverid = addrver.id) as insuff_clear_date");

		$this->db->from('addrver');

		$this->db->join("addrverres as ev1",'ev1.addrverid = addrver.id','left');

		$this->db->join("addrverres as ev2",'(ev2.addrverid = addrver.id and ev1.id < ev2.id)','left');

		$this->db->join("status",'status.id = ev1.verfstatus','left');

		if($clientid)
		{
			$this->db->where($clientid);
		}

		$this->db->where('ev1.verfstatus !=','27');

		$this->db->where('ev1.verfstatus !=','28');

		$this->db->where('ev2.verfstatus is null');

		$order_clause = "(case ev1.verfstatus when 19 THEN 0 else 1 end),(case ev1.verfstatus when 18 THEN 0 else 1 end),(case ev1.verfstatus when 1 THEN 0 else 1 end),(case ev1.verfstatus when 11 THEN 0 else 1 end),(case ev1.verfstatus when 12 THEN 0 else 1 end),(case ev1.verfstatus when 13 THEN 0 else 1 end),(case ev1.verfstatus when 14 THEN 0 else 1 end),(case ev1.verfstatus when 16 THEN 0 else 1 end),(case ev1.verfstatus when 23 THEN 0 else 1 end),(case ev1.verfstatus when 26 THEN 0 else 1 end),(case ev1.verfstatus when 17 THEN 0 else 1 end),(case ev1.verfstatus when 9 THEN 0 else 1 end),(case ev1.verfstatus when 20 THEN 0 else 1 end),(case ev1.verfstatus when 21 THEN 0 else 1 end),(case ev1.verfstatus when 22 THEN 0 else 1 end),(case ev1.verfstatus when 24 THEN 0 else 1 end),(case ev1.verfstatus when 25 THEN 0 else 1 end)";
	
		$this->db->order_by($order_clause);
		
		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_education_ver_status_for_export($clientid)
	{
		$this->db->select("education.id,status_value as verfstatus,ev1.closuredate,(SELECT GROUP_CONCAT(concat(DATE_FORMAT(education_insuff.insuff_raised_date,'%Y-%m-%d')) SEPARATOR '||') FROM education_insuff where education_insuff.education_id = education.id) as insuff_raised_date,(SELECT GROUP_CONCAT(concat(DATE_FORMAT(education_insuff.insuff_clear_date,'%Y-%m-%d')) SEPARATOR '||') FROM education_insuff where education_insuff.education_id = education.id) as insuff_clear_date");

		$this->db->from('education');

		$this->db->join("education_result as ev1",'(ev1.education_id = education.id)','left');

		$this->db->join("education_result as ev2",'(ev2.education_id = education.id and ev1.id < ev2.id)','left');

        $this->db->join("status",'status.id = ev1.verfstatus','left');

		$this->db->where('ev2.verfstatus is null');
		
		if($clientid)
		{
			$this->db->where($clientid);
		}

		$this->db->where('ev1.verfstatus !=','27');

		$this->db->where('ev1.verfstatus !=','28');

		$order_clause = "(case ev1.verfstatus when 19 THEN 0 else 1 end),(case ev1.verfstatus when 18 THEN 0 else 1 end),(case ev1.verfstatus when 1 THEN 0 else 1 end),(case ev1.verfstatus when 11 THEN 0 else 1 end),(case ev1.verfstatus when 12 THEN 0 else 1 end),(case ev1.verfstatus when 13 THEN 0 else 1 end),(case ev1.verfstatus when 14 THEN 0 else 1 end),(case ev1.verfstatus when 16 THEN 0 else 1 end),(case ev1.verfstatus when 23 THEN 0 else 1 end),(case ev1.verfstatus when 26 THEN 0 else 1 end),(case ev1.verfstatus when 17 THEN 0 else 1 end),(case ev1.verfstatus when 9 THEN 0 else 1 end),(case ev1.verfstatus when 20 THEN 0 else 1 end),(case ev1.verfstatus when 21 THEN 0 else 1 end),(case ev1.verfstatus when 22 THEN 0 else 1 end),(case ev1.verfstatus when 24 THEN 0 else 1 end),(case ev1.verfstatus when 25 THEN 0 else 1 end)";
	
		$this->db->order_by($order_clause);

		
		$result = $this->db->get();
	
		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_employment_ver_status_for_export($clientid)
	{
      
		$this->db->select("empver.id,status.status_value as verfstatus,ev1.closuredate,(SELECT GROUP_CONCAT(concat(DATE_FORMAT(empverres_insuff.insuff_raised_date,'%Y-%m-%d')) SEPARATOR '||') FROM empverres_insuff where empverres_insuff.empverres_id = empver.id) as insuff_raised_date,(SELECT GROUP_CONCAT(concat(DATE_FORMAT(empverres_insuff.insuff_clear_date,'%Y-%m-%d')) SEPARATOR '||') FROM empverres_insuff where empverres_insuff.empverres_id = empver.id) as insuff_clear_date");

		$this->db->from('empver');

		$this->db->join("empverres as ev1",'ev1.empverid = empver.id','left');

		$this->db->join("empverres as ev2",'(ev2.empverid = empver.id and ev1.id < ev2.id)','left');

		$this->db->join("status",'status.id = ev1.verfstatus','left');
        
		$this->db->where('ev2.verfstatus is null');

		if($clientid)
		{
			$this->db->where($clientid);
		}

		$this->db->where('ev1.verfstatus !=','27');

		$this->db->where('ev1.verfstatus !=','28');

		$order_clause = "(case ev1.verfstatus when 19 THEN 0 else 1 end),(case ev1.verfstatus when 18 THEN 0 else 1 end),(case ev1.verfstatus when 1 THEN 0 else 1 end),(case ev1.verfstatus when 11 THEN 0 else 1 end),(case ev1.verfstatus when 12 THEN 0 else 1 end),(case ev1.verfstatus when 13 THEN 0 else 1 end),(case ev1.verfstatus when 14 THEN 0 else 1 end),(case ev1.verfstatus when 16 THEN 0 else 1 end),(case ev1.verfstatus when 23 THEN 0 else 1 end),(case ev1.verfstatus when 26 THEN 0 else 1 end),(case ev1.verfstatus when 17 THEN 0 else 1 end),(case ev1.verfstatus when 9 THEN 0 else 1 end),(case ev1.verfstatus when 20 THEN 0 else 1 end),(case ev1.verfstatus when 21 THEN 0 else 1 end),(case ev1.verfstatus when 22 THEN 0 else 1 end),(case ev1.verfstatus when 24 THEN 0 else 1 end),(case ev1.verfstatus when 25 THEN 0 else 1 end)";
	
		$this->db->order_by($order_clause);

				
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_refver_ver_status_for_export($clientid)
	{
         
		$this->db->select("reference.id,status.status_value as verfstatus,ref1.closuredate,(SELECT GROUP_CONCAT(concat(DATE_FORMAT(reference_insuff.insuff_raised_date,'%Y-%m-%d')) SEPARATOR '||') FROM reference_insuff where reference_insuff.reference_id = reference.id) as insuff_raised_date,(SELECT GROUP_CONCAT(concat(DATE_FORMAT(reference_insuff.insuff_clear_date,'%Y-%m-%d')) SEPARATOR '||') FROM reference_insuff where reference_insuff.reference_id = reference.id) as insuff_clear_date");

		$this->db->from('reference');

        $this->db->join("reference_result as ref1",'ref1.reference_id = reference.id','left');

		$this->db->join("reference_result as ref2",'(ref2.reference_id = reference.id and ref1.id < ref2.id)','left');

	    $this->db->join("status",'status.id = ref1.verfstatus','left');

		$this->db->where('ref2.verfstatus is null');

		if($clientid)
		{
			$this->db->where($clientid);
		}

		$this->db->where('ref1.verfstatus !=','27');

		$this->db->where('ref1.verfstatus !=','28');

		$order_clause = "(case ref1.verfstatus when 19 THEN 0 else 1 end),(case ref1.verfstatus when 18 THEN 0 else 1 end),(case ref1.verfstatus when 1 THEN 0 else 1 end),(case ref1.verfstatus when 11 THEN 0 else 1 end),(case ref1.verfstatus when 12 THEN 0 else 1 end),(case ref1.verfstatus when 13 THEN 0 else 1 end),(case ref1.verfstatus when 14 THEN 0 else 1 end),(case ref1.verfstatus when 16 THEN 0 else 1 end),(case ref1.verfstatus when 23 THEN 0 else 1 end),(case ref1.verfstatus when 26 THEN 0 else 1 end),(case ref1.verfstatus when 17 THEN 0 else 1 end),(case ref1.verfstatus when 9 THEN 0 else 1 end),(case ref1.verfstatus when 20 THEN 0 else 1 end),(case ref1.verfstatus when 21 THEN 0 else 1 end),(case ref1.verfstatus when 22 THEN 0 else 1 end),(case ref1.verfstatus when 24 THEN 0 else 1 end),(case ref1.verfstatus when 25 THEN 0 else 1 end)";
	
		$this->db->order_by($order_clause);

		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}
   
    public function get_court_ver_status_for_export($clientid)
	{


		$this->db->select("courtver.id,status.status_value as verfstatus,ev1.closuredate,(SELECT GROUP_CONCAT(concat(DATE_FORMAT(courtver_insuff.insuff_raised_date,'%Y-%m-%d')) SEPARATOR '||') FROM courtver_insuff where courtver_insuff.courtver_id = courtver.id) as insuff_raised_date,(SELECT GROUP_CONCAT(concat(DATE_FORMAT(courtver_insuff.insuff_clear_date,'%Y-%m-%d')) SEPARATOR '||') FROM courtver_insuff where courtver_insuff.courtver_id = courtver.id) as insuff_clear_date");

		$this->db->from('courtver');

        $this->db->join("courtver_result as ev1",'ev1.courtver_id = courtver.id','left');

		$this->db->join("courtver_result as ev2",'(ev2.courtver_id = courtver.id and ev1.id < ev2.id)','left');

		$this->db->join("status",'status.id = ev1.verfstatus','left');

		$this->db->where('ev2.verfstatus is null');

		if($clientid)
		{
			$this->db->where($clientid);
		}

		$this->db->where('ev1.verfstatus !=','27');

		$this->db->where('ev1.verfstatus !=','28');


		$order_clause = "(case ev1.verfstatus when 19 THEN 0 else 1 end),(case ev1.verfstatus when 18 THEN 0 else 1 end),(case ev1.verfstatus when 1 THEN 0 else 1 end),(case ev1.verfstatus when 11 THEN 0 else 1 end),(case ev1.verfstatus when 12 THEN 0 else 1 end),(case ev1.verfstatus when 13 THEN 0 else 1 end),(case ev1.verfstatus when 14 THEN 0 else 1 end),(case ev1.verfstatus when 16 THEN 0 else 1 end),(case ev1.verfstatus when 23 THEN 0 else 1 end),(case ev1.verfstatus when 26 THEN 0 else 1 end),(case ev1.verfstatus when 17 THEN 0 else 1 end),(case ev1.verfstatus when 9 THEN 0 else 1 end),(case ev1.verfstatus when 20 THEN 0 else 1 end),(case ev1.verfstatus when 21 THEN 0 else 1 end),(case ev1.verfstatus when 22 THEN 0 else 1 end),(case ev1.verfstatus when 24 THEN 0 else 1 end),(case ev1.verfstatus when 25 THEN 0 else 1 end)";
	
		$this->db->order_by($order_clause);

		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_pcc_ver_status_for_export($where_arry = array())
	{   

		$this->db->select("pcc.id,status.status_value as verfstatus,ev1.closuredate,(SELECT GROUP_CONCAT(concat(DATE_FORMAT(pcc_insuff.insuff_raised_date,'%Y-%m-%d')) SEPARATOR '||') FROM pcc_insuff where pcc_insuff.pcc_id = pcc.id) as insuff_raised_date,(SELECT GROUP_CONCAT(concat(DATE_FORMAT(pcc_insuff.insuff_clear_date,'%Y-%m-%d')) SEPARATOR '||') FROM pcc_insuff where pcc_insuff.pcc_id = pcc.id) as insuff_clear_date");

		$this->db->from('pcc');

        $this->db->join("pcc_result as ev1",'ev1.pcc_id = pcc.id','left');

		$this->db->join("pcc_result as ev2",'(ev2.pcc_id = pcc.id and ev1.id < ev2.id)','left');

		$this->db->join("status",'status.id = ev1.verfstatus','left');

		$this->db->where('ev2.verfstatus is null');

	
		if($where_arry)
		{
			$this->db->where($where_arry);
		}

		$this->db->where('ev1.verfstatus !=','27');

		$this->db->where('ev1.verfstatus !=','28');

		$order_clause = "(case ev1.verfstatus when 19 THEN 0 else 1 end),(case ev1.verfstatus when 18 THEN 0 else 1 end),(case ev1.verfstatus when 1 THEN 0 else 1 end),(case ev1.verfstatus when 11 THEN 0 else 1 end),(case ev1.verfstatus when 12 THEN 0 else 1 end),(case ev1.verfstatus when 13 THEN 0 else 1 end),(case ev1.verfstatus when 14 THEN 0 else 1 end),(case ev1.verfstatus when 16 THEN 0 else 1 end),(case ev1.verfstatus when 23 THEN 0 else 1 end),(case ev1.verfstatus when 26 THEN 0 else 1 end),(case ev1.verfstatus when 17 THEN 0 else 1 end),(case ev1.verfstatus when 9 THEN 0 else 1 end),(case ev1.verfstatus when 20 THEN 0 else 1 end),(case ev1.verfstatus when 21 THEN 0 else 1 end),(case ev1.verfstatus when 22 THEN 0 else 1 end),(case ev1.verfstatus when 24 THEN 0 else 1 end),(case ev1.verfstatus when 25 THEN 0 else 1 end)";
	
		$this->db->order_by($order_clause);

		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

   

    public function get_globdbver_ver_status_for_export($clientid)
	{
        
		$this->db->select("glodbver.id,status.status_value as verfstatus,ev1.closuredate,(SELECT GROUP_CONCAT(concat(DATE_FORMAT(glodbver_insuff.insuff_raised_date,'%Y-%m-%d')) SEPARATOR '||') FROM glodbver_insuff where glodbver_insuff.glodbver_id = glodbver.id) as insuff_raised_date,(SELECT GROUP_CONCAT(concat(DATE_FORMAT(glodbver_insuff.insuff_clear_date,'%Y-%m-%d')) SEPARATOR '||') FROM glodbver_insuff where glodbver_insuff.glodbver_id = glodbver.id) as insuff_clear_date");

		$this->db->from('glodbver');


		$this->db->join("glodbver_result as ev1",'ev1.glodbver_id = glodbver.id','left');

		$this->db->join("glodbver_result as ev2",'(ev2.glodbver_id = glodbver.id and ev1.id < ev2.id)','left');

		$this->db->join("status",'status.id = ev1.verfstatus','left');

		$this->db->where('ev2.verfstatus is null');
		
		if($clientid)
		{
			$this->db->where($clientid);
		}

		$this->db->where('ev1.verfstatus !=','27');

		$this->db->where('ev1.verfstatus !=','28');

		$order_clause = "(case ev1.verfstatus when 19 THEN 0 else 1 end),(case ev1.verfstatus when 18 THEN 0 else 1 end),(case ev1.verfstatus when 1 THEN 0 else 1 end),(case ev1.verfstatus when 11 THEN 0 else 1 end),(case ev1.verfstatus when 12 THEN 0 else 1 end),(case ev1.verfstatus when 13 THEN 0 else 1 end),(case ev1.verfstatus when 14 THEN 0 else 1 end),(case ev1.verfstatus when 16 THEN 0 else 1 end),(case ev1.verfstatus when 23 THEN 0 else 1 end),(case ev1.verfstatus when 26 THEN 0 else 1 end),(case ev1.verfstatus when 17 THEN 0 else 1 end),(case ev1.verfstatus when 9 THEN 0 else 1 end),(case ev1.verfstatus when 20 THEN 0 else 1 end),(case ev1.verfstatus when 21 THEN 0 else 1 end),(case ev1.verfstatus when 22 THEN 0 else 1 end),(case ev1.verfstatus when 24 THEN 0 else 1 end),(case ev1.verfstatus when 25 THEN 0 else 1 end)";
	
		$this->db->order_by($order_clause);

		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_identity_ver_status_for_export($where_arry = array())
	{

		$this->db->select("identity.id,status.status_value as verfstatus,ev1.closuredate,(SELECT GROUP_CONCAT(concat(DATE_FORMAT(identity_insuff.insuff_raised_date,'%Y-%m-%d')) SEPARATOR '||') FROM identity_insuff where identity_insuff.identity_id = identity.id) as insuff_raised_date,(SELECT GROUP_CONCAT(concat(DATE_FORMAT(identity_insuff.insuff_clear_date,'%Y-%m-%d')) SEPARATOR '||') FROM identity_insuff where identity_insuff.identity_id = identity.id) as insuff_clear_date");

		$this->db->from('identity');

        $this->db->join("identity_result as ev1",'ev1.identity_id = identity.id','left');

		$this->db->join("identity_result as ev2",'(ev2.identity_id = identity.id and ev1.id < ev2.id)','left');

		$this->db->join("status",'status.id = ev1.verfstatus','left');


		$this->db->where('ev2.verfstatus is null');

		if($where_arry)
		{
			$this->db->where($where_arry);
		}

		$this->db->where('ev1.verfstatus !=','27');

		$this->db->where('ev1.verfstatus !=','28');

		$order_clause = "(case ev1.verfstatus when 19 THEN 0 else 1 end),(case ev1.verfstatus when 18 THEN 0 else 1 end),(case ev1.verfstatus when 1 THEN 0 else 1 end),(case ev1.verfstatus when 11 THEN 0 else 1 end),(case ev1.verfstatus when 12 THEN 0 else 1 end),(case ev1.verfstatus when 13 THEN 0 else 1 end),(case ev1.verfstatus when 14 THEN 0 else 1 end),(case ev1.verfstatus when 16 THEN 0 else 1 end),(case ev1.verfstatus when 23 THEN 0 else 1 end),(case ev1.verfstatus when 26 THEN 0 else 1 end),(case ev1.verfstatus when 17 THEN 0 else 1 end),(case ev1.verfstatus when 9 THEN 0 else 1 end),(case ev1.verfstatus when 20 THEN 0 else 1 end),(case ev1.verfstatus when 21 THEN 0 else 1 end),(case ev1.verfstatus when 22 THEN 0 else 1 end),(case ev1.verfstatus when 24 THEN 0 else 1 end),(case ev1.verfstatus when 25 THEN 0 else 1 end)";
	
		$this->db->order_by($order_clause);

		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_credit_report_ver_status_for_export($where_arry = array())
	{
 
		$this->db->select("credit_report.id,status.status_value as verfstatus,ev1.closuredate,(SELECT GROUP_CONCAT(concat(DATE_FORMAT(credit_report_insuff.insuff_raised_date,'%Y-%m-%d')) SEPARATOR '||') FROM credit_report_insuff where credit_report_insuff.credit_report_id = credit_report.id) as insuff_raised_date,(SELECT GROUP_CONCAT(concat(DATE_FORMAT(credit_report_insuff.insuff_clear_date,'%Y-%m-%d')) SEPARATOR '||') FROM credit_report_insuff where credit_report_insuff.credit_report_id = credit_report.id) as insuff_clear_date");

		$this->db->from('credit_report');

        $this->db->join("credit_report_result as ev1",'ev1.credit_report_id = credit_report.id','left');

		$this->db->join("credit_report_result as ev2",'(ev2.credit_report_id = credit_report.id and ev1.id < ev2.id)','left');

		$this->db->join("status",'status.id = ev1.verfstatus','left');

		$this->db->where('ev2.verfstatus is null');

		if($where_arry)
		{
			$this->db->where($where_arry);
		}

		$this->db->where('ev1.verfstatus !=','27');

		$this->db->where('ev1.verfstatus !=','28');

		$order_clause = "(case ev1.verfstatus when 19 THEN 0 else 1 end),(case ev1.verfstatus when 18 THEN 0 else 1 end),(case ev1.verfstatus when 1 THEN 0 else 1 end),(case ev1.verfstatus when 11 THEN 0 else 1 end),(case ev1.verfstatus when 12 THEN 0 else 1 end),(case ev1.verfstatus when 13 THEN 0 else 1 end),(case ev1.verfstatus when 14 THEN 0 else 1 end),(case ev1.verfstatus when 16 THEN 0 else 1 end),(case ev1.verfstatus when 23 THEN 0 else 1 end),(case ev1.verfstatus when 26 THEN 0 else 1 end),(case ev1.verfstatus when 17 THEN 0 else 1 end),(case ev1.verfstatus when 9 THEN 0 else 1 end),(case ev1.verfstatus when 20 THEN 0 else 1 end),(case ev1.verfstatus when 21 THEN 0 else 1 end),(case ev1.verfstatus when 22 THEN 0 else 1 end),(case ev1.verfstatus when 24 THEN 0 else 1 end),(case ev1.verfstatus when 25 THEN 0 else 1 end)";
	
		$this->db->order_by($order_clause);

		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	
	public function get_drugs_report_ver_status_for_export($where_arry = array())
	{
 
		$this->db->select("drug_narcotis.id,status.status_value as verfstatus,ev1.closuredate,(SELECT GROUP_CONCAT(concat(DATE_FORMAT(drug_narcotis_insuff.insuff_raised_date,'%Y-%m-%d')) SEPARATOR '||') FROM drug_narcotis_insuff where drug_narcotis_insuff.drug_narcotis_id = drug_narcotis.id) as insuff_raised_date,(SELECT GROUP_CONCAT(concat(DATE_FORMAT(drug_narcotis_insuff.insuff_clear_date,'%Y-%m-%d')) SEPARATOR '||') FROM drug_narcotis_insuff where drug_narcotis_insuff.drug_narcotis_id = drug_narcotis.id) as insuff_clear_date");

		$this->db->from('drug_narcotis');

        $this->db->join("drug_narcotis_result as ev1",'ev1.drug_narcotis_id = drug_narcotis.id','left');

		$this->db->join("drug_narcotis_result as ev2",'(ev2.drug_narcotis_id = drug_narcotis.id and ev1.id < ev2.id)','left');

		$this->db->join("status",'status.id = ev1.verfstatus','left');

		$this->db->where('ev2.verfstatus is null');

		if($where_arry)
		{
			$this->db->where($where_arry);
		}

		$this->db->where('ev1.verfstatus !=','27');

		$this->db->where('ev1.verfstatus !=','28');

		$order_clause = "(case ev1.verfstatus when 19 THEN 0 else 1 end),(case ev1.verfstatus when 18 THEN 0 else 1 end),(case ev1.verfstatus when 1 THEN 0 else 1 end),(case ev1.verfstatus when 11 THEN 0 else 1 end),(case ev1.verfstatus when 12 THEN 0 else 1 end),(case ev1.verfstatus when 13 THEN 0 else 1 end),(case ev1.verfstatus when 14 THEN 0 else 1 end),(case ev1.verfstatus when 16 THEN 0 else 1 end),(case ev1.verfstatus when 23 THEN 0 else 1 end),(case ev1.verfstatus when 26 THEN 0 else 1 end),(case ev1.verfstatus when 17 THEN 0 else 1 end),(case ev1.verfstatus when 9 THEN 0 else 1 end),(case ev1.verfstatus when 20 THEN 0 else 1 end),(case ev1.verfstatus when 21 THEN 0 else 1 end),(case ev1.verfstatus when 22 THEN 0 else 1 end),(case ev1.verfstatus when 24 THEN 0 else 1 end),(case ev1.verfstatus when 25 THEN 0 else 1 end)";
	
		$this->db->order_by($order_clause);

		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_aq_component_details()
	{

        $this->db->select("COUNT(*) as total,'new' as case_status,'1' as component_id");
       
		$this->db->from('addrver');

		$this->db->join("addrverres",'addrverres.addrverid = addrver.id','left');

		$this->db->where('addrver.vendor_id =',0);

		$this->db->where('addrver.vendor_digital_id =',NULL);


		$this->db->where('(addrverres.var_filter_status = "wip" or addrverres.var_filter_status = "WIP")');
    
        $result = $this->db->get();

		record_db_error($this->db->last_query());

		$address_new = $result->result_array();

		$conditional_status = "(`empverres`.`verfstatus` = '14' or `empverres`.`verfstatus` = '11' or `empverres`.`verfstatus` = '26')"; 

		$this->db->select("COUNT(*) as total,'new' as case_status,'2' as component_id");
       
		$this->db->from('empver');
		
		$this->db->join("empverres",'empverres.empverid = empver.id','left');

        $this->db->where($conditional_status);

        $result = $this->db->get();

		record_db_error($this->db->last_query());

		$employment_new = $result->result_array();


		$this->db->select("COUNT(*) as total,'new' as case_status,'3' as component_id");

		$this->db->from('education');

		$this->db->join("education_result",'education_result.education_id =education.id');

		$this->db->where('(education_result.var_filter_status = "wip" or education_result.var_filter_status = "WIP")');

        $this->db->where('education.vendor_id =',0);

        $result = $this->db->get();

		record_db_error($this->db->last_query());

		$education_new = $result->result_array();


	    $conditional_status_reference = "(`reference_result`.`verfstatus` = '14' or `reference_result`.`verfstatus` = '11' or `reference_result`.`verfstatus` = '26')"; 


		$this->db->select("COUNT(*) as total,'new' as case_status,'4' as component_id");

		$this->db->from('reference');

		$this->db->join("reference_result",'reference_result.reference_id = reference.id');
		
		
	    $this->db->where($conditional_status_reference);

        $result = $this->db->get();

		record_db_error($this->db->last_query());

		$reference_new = $result->result_array();

		$this->db->select("COUNT(*) as total,'new' as case_status,'5' as component_id");

		$this->db->from('courtver');

	    $this->db->join("courtver_result",'courtver_result.courtver_id = courtver.id');

		$this->db->where('courtver.vendor_id =',0);

		$this->db->where('(courtver_result.var_filter_status = "wip" or courtver_result.var_filter_status = "WIP")');
		
        
        $result = $this->db->get();

		record_db_error($this->db->last_query());

		$court_new = $result->result_array();



		$this->db->select("COUNT(*) as total,'new' as case_status,'6' as component_id");

		$this->db->from('glodbver');

	    $this->db->join("glodbver_result",'glodbver_result.glodbver_id = glodbver.id');

		$this->db->where('glodbver.vendor_id =',0);

		$this->db->where('(glodbver_result.var_filter_status = "wip" or glodbver_result.var_filter_status = "WIP")');
		
        
        $result = $this->db->get();

		record_db_error($this->db->last_query());

		$global_new = $result->result_array();


		$this->db->select("COUNT(*) as total,'new' as case_status,'7' as component_id");

		$this->db->from('pcc');

	    $this->db->join("pcc_result",'pcc_result.pcc_id = pcc.id');

		$this->db->where('pcc.vendor_id =',0);

		$this->db->where('(pcc_result.var_filter_status = "wip" or pcc_result.var_filter_status = "WIP")');
		
        
        $result = $this->db->get();

		record_db_error($this->db->last_query());

		$pcc_new = $result->result_array();

		$this->db->select("COUNT(*) as total,'new' as case_status,'8' as component_id");

		$this->db->from('identity');

	    $this->db->join("identity_result",'identity_result.identity_id = identity.id');

		$this->db->where('identity.vendor_id =',0);

		$this->db->where('(identity_result.var_filter_status = "wip" or identity_result.var_filter_status = "WIP")');
		
        
        $result = $this->db->get();

		record_db_error($this->db->last_query());

		$identity_new = $result->result_array();

		$this->db->select("COUNT(*) as total,'new' as case_status,'9' as component_id");

		$this->db->from('credit_report');

	    $this->db->join("credit_report_result",'credit_report_result.credit_report_id = credit_report.id');

		$this->db->where('credit_report.vendor_id =',0);

		$this->db->where('(credit_report_result.var_filter_status = "wip" or credit_report_result.var_filter_status = "WIP")');
		
        
        $result = $this->db->get();

		record_db_error($this->db->last_query());

		$credit_new = $result->result_array();


	    $this->db->select("COUNT(*) as total,'new' as case_status,'10' as component_id");

		$this->db->from('drug_narcotis');

	    $this->db->join("drug_narcotis_result",'drug_narcotis_result.drug_narcotis_id = drug_narcotis.id');

		$this->db->where('drug_narcotis.vendor_id =',0);

		$this->db->where('(drug_narcotis_result.var_filter_status = "wip" or drug_narcotis_result.var_filter_status = "WIP")');
		
        
        $result = $this->db->get();

		record_db_error($this->db->last_query());

		$drugs_new = $result->result_array();
   
   

		$this->db->select("COUNT(*) as total,'aq' as case_status,'1' as component_id");

		$this->db->from('address_vendor_log');

		$this->db->join('addrver','addrver.id = address_vendor_log.case_id');

		$this->db->join("addrverres",'addrverres.addrverid = addrver.id');

		$this->db->where('address_vendor_log.status', 0);
    
	
        $this->db->where('(addrverres.var_filter_status = "wip" or addrverres.var_filter_status = "WIP")');

        
        $result = $this->db->get();

		record_db_error($this->db->last_query());

		$address_aq = $result->result_array();
     


		$this->db->select("COUNT(*) as total,'aq' as case_status,'2' as component_id");

		$this->db->from('employment_vendor_log');

		$this->db->join('empver','empver.id = employment_vendor_log.case_id');

		$this->db->join("empverres",'empverres.empverid = empver.id');


		$this->db->where('employment_vendor_log.status', 0);

        $this->db->where('(empverres.var_filter_status = "wip" or empverres.var_filter_status = "WIP")');


        $result = $this->db->get();

		record_db_error($this->db->last_query());

		$employment_aq = $result->result_array();
     
        
        $where_condition = '(NOT EXISTS (select id from view_vendor_master_log where education_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component_tbl_id = 3) or EXISTS (select id from view_vendor_master_log where education_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component_tbl_id = 3 and view_vendor_master_log.final_status = "wip" ))';

		$this->db->select("COUNT(*) as total,'aq' as case_status,'3' as component_id");

		$this->db->from('education_vendor_log');

		$this->db->join('education','education.id = education_vendor_log.case_id');

	    $this->db->join('vendors','vendors.id = education.vendor_id');

		$this->db->join("education_result",'education_result.education_id = education.id','left');

        
        $this->db->where($where_condition);
	  

		$this->db->where('education.vendor_stamp_id',NULL);

		$this->db->where('education.verifiers_spoc_status',1);

        $this->db->where('vendors.education_verification_status',1);

        $this->db->where('education_vendor_log.status', 0);


		$this->db->where('(education_result.var_filter_status = "wip" or education_result.var_filter_status = "WIP")');

        $result = $this->db->get();

		record_db_error($this->db->last_query());

		$education_aq = $result->result_array();


		$this->db->select("COUNT(*) as total,'aq' as case_status,'5' as component_id");

		$this->db->from('courtver_vendor_log');

		$this->db->join('courtver','courtver.id = courtver_vendor_log.case_id');

	    $this->db->join("courtver_result",'courtver_result.courtver_id = courtver.id');

        $this->db->where('courtver_vendor_log.status', 0);

        $this->db->where('(courtver_result.var_filter_status = "wip" or courtver_result.var_filter_status = "WIP")');
         
        $result = $this->db->get();

		record_db_error($this->db->last_query());

		$court_aq = $result->result_array(); 


		$this->db->select("COUNT(*) as total,'aq' as case_status,'6' as component_id");

		$this->db->from('glodbver_vendor_log');

		$this->db->join('glodbver','glodbver.id = glodbver_vendor_log.case_id');

	    $this->db->join("glodbver_result",'glodbver_result.glodbver_id = glodbver.id');

        $this->db->where('glodbver_vendor_log.status', 0);

        $this->db->where('(glodbver_result.var_filter_status = "wip" or glodbver_result.var_filter_status = "WIP")');
         
        $result = $this->db->get();

		record_db_error($this->db->last_query());

		$global_aq = $result->result_array(); 




	    $this->db->select("COUNT(*) as total,'aq' as case_status,'7' as component_id");

		$this->db->from('pcc_vendor_log');

		$this->db->join('pcc','pcc.id = pcc_vendor_log.case_id');

	    $this->db->join("pcc_result",'pcc_result.pcc_id = pcc.id');

        $this->db->where('pcc_vendor_log.status', 0);

        $this->db->where('(pcc_result.var_filter_status = "wip" or pcc_result.var_filter_status = "WIP")');
         
        $result = $this->db->get();

		record_db_error($this->db->last_query());

		$pcc_aq = $result->result_array(); 


	    $this->db->select("COUNT(*) as total,'aq' as case_status,'8' as component_id");

		$this->db->from('identity_vendor_log');

		$this->db->join('identity','identity.id = identity_vendor_log.case_id');

	    $this->db->join("identity_result",'identity_result.identity_id = identity.id');

        $this->db->where('identity_vendor_log.status', 0);

        $this->db->where('(identity_result.var_filter_status = "wip" or identity_result.var_filter_status = "WIP")');
         
        $result = $this->db->get();

		record_db_error($this->db->last_query());

		$identity_aq = $result->result_array(); 


	    $this->db->select("COUNT(*) as total,'aq' as case_status,'9' as component_id");

		$this->db->from('credit_report_vendor_log');

		$this->db->join('credit_report','credit_report.id = credit_report_vendor_log.case_id');

	    $this->db->join("credit_report_result",'credit_report_result.credit_report_id = credit_report.id');

        $this->db->where('credit_report_vendor_log.status', 0);

        $this->db->where('(credit_report_result.var_filter_status = "wip" or credit_report_result.var_filter_status = "WIP")');
         
        $result = $this->db->get();

		record_db_error($this->db->last_query());

		$credit_aq = $result->result_array(); 


		$this->db->select("COUNT(*) as total,'aq' as case_status,'10' as component_id");

		$this->db->from('drug_narcotis_vendor_log');

		$this->db->join('drug_narcotis','drug_narcotis.id = drug_narcotis_vendor_log.case_id');

	    $this->db->join("drug_narcotis_result",'drug_narcotis_result.drug_narcotis_id = drug_narcotis.id');

        $this->db->where('drug_narcotis_vendor_log.status', 0);

        $this->db->where('(drug_narcotis_result.var_filter_status = "wip" or drug_narcotis_result.var_filter_status = "WIP")');
         
        $result = $this->db->get();

		record_db_error($this->db->last_query());

		$drugs_aq = $result->result_array(); 


 
        $this->db->select("COUNT(*) as total,'closure' as case_status,'1' as component_id");

        $this->db->from('view_vendor_master_log');

        $this->db->join('address_vendor_log','address_vendor_log.id = view_vendor_master_log.case_id');

        $this->db->join('addrver','addrver.id = address_vendor_log.case_id');

        $this->db->join('addrverres','addrverres.addrverid = addrver.id');

        $this->db->where("view_vendor_master_log.final_status",'clear');
       
        $this->db->where('view_vendor_master_log.status', 1);

        $this->db->where('view_vendor_master_log.component','addrver');

        $result = $this->db->get();

		record_db_error($this->db->last_query());

		$address_closure = $result->result_array();

        
        $this->db->select("COUNT(*) as total,'closure' as case_status,'2' as component_id");

        $this->db->from('view_vendor_master_log');
      
        $this->db->join('employment_vendor_log','employment_vendor_log.id = view_vendor_master_log.case_id');

        $this->db->join('empver','empver.id = employment_vendor_log.case_id');

        $this->db->where("view_vendor_master_log.final_status",'closed');

        $this->db->where('view_vendor_master_log.status', 1);
 
        $this->db->where('view_vendor_master_log.component','empver');

        
        $result = $this->db->get();

		record_db_error($this->db->last_query());

		$employment_closure = $result->result_array();



		$admin_status = "(education_result.verfstatus = 1 or education_result.verfstatus = 11 or education_result.verfstatus = 12 or education_result.verfstatus = 13 or education_result.verfstatus = 14 or education_result.verfstatus = 16 or education_result.verfstatus = 23 or education_result.verfstatus = 26 )";


        $where_condition = "(view_vendor_master_log.final_status = 'clear' or view_vendor_master_log.final_status = 'major discrepancy'  or view_vendor_master_log.final_status = 'minor discrepancy' or view_vendor_master_log.final_status = 'no record found' or view_vendor_master_log.final_status = 'unable to verify')" ; 

        $this->db->select("COUNT(*) as total,'closure' as case_status,'3' as component_id");

        $this->db->from('view_vendor_master_log');
      
        $this->db->join('education_vendor_log','education_vendor_log.id = view_vendor_master_log.case_id');

        $this->db->join('education','education.id = education_vendor_log.case_id');

        $this->db->join('education_result','education_result.education_id = education.id');


        $this->db->join('vendors','vendors.id = education.vendor_id');


        $this->db->where('view_vendor_master_log.status', 1);
 
        $this->db->where('view_vendor_master_log.component','eduver');
        
        $this->db->where($admin_status);

        $this->db->where($where_condition);


        $result = $this->db->get();

		record_db_error($this->db->last_query());

		$education_closure = $result->result_array();


        $where_condition_court = "(view_vendor_master_log.final_status = 'clear' or view_vendor_master_log.final_status = 'possible match')" ;

        $this->db->select("COUNT(*) as total,'closure' as case_status,'5' as component_id");

        $this->db->from('view_vendor_master_log');
      
        $this->db->join('courtver_vendor_log','courtver_vendor_log.id = view_vendor_master_log.case_id');

        $this->db->join('courtver','courtver.id = courtver_vendor_log.case_id');

        $this->db->where('view_vendor_master_log.status', 1);
 
        $this->db->where('view_vendor_master_log.component','courtver');

        $this->db->where($where_condition_court);


        $result = $this->db->get();

		record_db_error($this->db->last_query());

		$court_closure = $result->result_array();


		$where_condition_global = "view_vendor_master_log.final_status = 'clear' or view_vendor_master_log.final_status = 'passible match'" ;

        $this->db->select("COUNT(*) as total,'closure' as case_status,'6' as component_id");

        $this->db->from('view_vendor_master_log');
      
        $this->db->join('glodbver_vendor_log','glodbver_vendor_log.id = view_vendor_master_log.case_id');

        $this->db->join('glodbver','glodbver.id = glodbver_vendor_log.case_id');

    

        $this->db->where('view_vendor_master_log.status', 1);
 
        $this->db->where('view_vendor_master_log.component','globdbver');

        $this->db->where($where_condition_global);

        
        $result = $this->db->get();

		record_db_error($this->db->last_query());

		$global_closure = $result->result_array();


		$this->db->select("COUNT(*) as total,'closure' as case_status,'7' as component_id");

        $this->db->from('view_vendor_master_log');
       
        $this->db->join('pcc_vendor_log','pcc_vendor_log.id = view_vendor_master_log.case_id');

        $this->db->join('pcc','pcc.id = pcc_vendor_log.case_id');

        $this->db->where('view_vendor_master_log.status', 1);
 
        $this->db->where('view_vendor_master_log.component','crimver');

        
        $this->db->where('view_vendor_master_log.final_status','closed');

        $result = $this->db->get();

		record_db_error($this->db->last_query());

		$pcc_closure = $result->result_array();



		$this->db->select("COUNT(*) as total,'closure' as case_status,'8' as component_id");

        $this->db->from('view_vendor_master_log');

        $this->db->join('identity_vendor_log','identity_vendor_log.id = view_vendor_master_log.case_id');

        $this->db->join('identity','identity.id = identity_vendor_log.case_id');

        $this->db->where('view_vendor_master_log.status', 1);
 
        $this->db->where('view_vendor_master_log.component','identity');

        $this->db->where('view_vendor_master_log.final_status','closed');

         
        $result = $this->db->get();

		record_db_error($this->db->last_query());

		$identity_closure = $result->result_array();


		$this->db->select("COUNT(*) as total,'closure' as case_status,'9' as component_id");

        $this->db->from('view_vendor_master_log');
     
        $this->db->join('credit_report_vendor_log','credit_report_vendor_log.id = view_vendor_master_log.case_id');

        $this->db->join('credit_report','credit_report.id = credit_report_vendor_log.case_id');

        $this->db->where('view_vendor_master_log.status', 1);
 
        $this->db->where('view_vendor_master_log.component','cbrver');

        $this->db->where('view_vendor_master_log.final_status','closed');
      

        $result = $this->db->get();

		record_db_error($this->db->last_query());

		$credit_closure = $result->result_array();

        
        $this->db->select("COUNT(*) as total,'closure' as case_status,'10' as component_id");

        $this->db->from('view_vendor_master_log');
      
        $this->db->join('drug_narcotis_vendor_log','drug_narcotis_vendor_log.id = view_vendor_master_log.case_id');

        $this->db->join('drug_narcotis','drug_narcotis.id = drug_narcotis_vendor_log.case_id');


        $this->db->where('view_vendor_master_log.status', 1);
 
        $this->db->where('view_vendor_master_log.component','narcver');

        $this->db->where('view_vendor_master_log.final_status','closed');
      

        $result = $this->db->get();

		record_db_error($this->db->last_query());

		$drugs_closure = $result->result_array();


  
		$new_cases = array_merge($address_new,$employment_new,$education_new,$reference_new,$court_new,$global_new,$pcc_new,$identity_new,$credit_new,$drugs_new);  

        $aq_cases = array_merge($address_aq,$employment_aq,$education_aq,$court_aq,$global_aq,$pcc_aq,$identity_aq,$credit_aq,$drugs_aq);     

        $closure_cases = array_merge($address_closure,$employment_closure,$education_closure,$court_closure,$global_closure,$pcc_closure,$identity_closure,$credit_closure,$drugs_closure);  
   
   
		$return = array();
		

		foreach ($new_cases as $key => $value) {
	
			$return[strtolower($value['case_status']).'_'.$value['component_id']] = $value['total'];

		}

		foreach ($aq_cases as $key => $value) {
	
			$return[strtolower($value['case_status']).'_'.$value['component_id']] = $value['total'];

		}

		foreach ($closure_cases as $key => $value) {
	
			$return[strtolower($value['case_status']).'_'.$value['component_id']] = $value['total'];

		}
	
		
     
		return $return;

	}


}
?>
