<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vendor_common_model extends CI_Model {

	public function select($tableName, $return_as_strict_row,$select_array, $where_array=array())
	{
		$this->db->select($select_array);

		$this->db->from($tableName);

        $this->db->where($where_array); 
        
        $result_array = $this->db->get()->result_array();	

        if($return_as_strict_row)
		{
            if(count($result_array) >0 ) // ensure only one record has been previously inserted
            {
                $result_array = $result_array[0];
            }
        }
        return $result_array;
	}

    public function vendor_component($where_array)
    {   
        $this->db->select('component_name,component_key,show_component_name,vendor_icon');

        $this->db->from('components');

        $this->db->where_in('component_key',$where_array); 

        $this->db->where('status',1); 

        $this->db->order_by('serial_id','asc');

        return $this->db->get()->result_array();
    }

   
    public function court_case_list($where_array = array())
    {
        $this->db->select("view_vendor_master_log.*,CandidateName,address_type,street_address,city,pincode,state");
        $this->db->from('view_vendor_master_log');
        $this->db->join('courtver','courtver.id = view_vendor_master_log.component_tbl_id');
        $this->db->join('candidates_info','candidates_info.id = courtver.candsid');
        $this->db->where($where_array);
        return $this->db->get()->result_array();

        // $this->db->select('vendor_master_log.id as vendor_master_log_id,court_com_ref as component_ref,address_type,street_address,city,pincode,state, vendor_master_log.status,trasaction_id,component,courtver_vendor_log.created_on allocated_on,vendor_master_log.tat_status');

        // $this->db->from('vendor_master_log');

        // $this->db->join('courtver_vendor_log','courtver_vendor_log.id = vendor_master_log.case_id');

        // $this->db->join('courtver','courtver.id = courtver_vendor_log.case_id');

        // $this->db->join('candidates_info','candidates_info.id = courtver.candsid');

        // $this->db->where($where_array);

        // $this->db->order_by('courtver_vendor_log.id', 'desc');

        // return $this->db->get()->result_array();
    }


	public function save($tableName,$arrdata, $arrwhere = array())
    {
    	if(!empty($arrwhere)){
    		foreach ($arrwhere as $field => $value)  {
    			$this->db->where($field,$value);	
    		}
            return $this->db->update($tableName, $arrdata);

    	} else{
    		$this->db->insert($tableName, $arrdata);    
            return $this->db->insert_id();
    	}
    }

    public function delete($tableName, $arrwhere)
	{
		return $this->db->delete($tableName, $arrwhere);	
	}
    
    public function get_vendor_details($arrwhere = array())
    {

        $this->db->select("vendors.*");
        $this->db->from('vendors');

        if($arrwhere['vendor_list'] != "All")
        {
           $this->db->where('vendors.id',$arrwhere['vendor_list']);  
        }
      
        $this->db->order_by('vendors.id', 'ASC');
        return $this->db->get()->result_array();

    }


    public function get_vedor_datails_for_mail()
    {
        $date = new DateTime("now");

        $curr_date = $date->format('Y-m-d');

        $this->db->select("address_vendor_log.modified_on as vendor_assign_on,(select clientname from clients where clients.id = addrver.clientid limit 1) as clientname,addrver.add_com_ref as component_ref_no,'Address' as component_name,view_vendor_master_log.trasaction_id,candidates_info.CandidateName,candidates_info.NameofCandidateFather,candidates_info.CandidatesContactNumber,candidates_info.ContactNo1,candidates_info.ContactNo2,addrver.address,addrver.city,addrver.state,addrver.pincode,(select vendor_name from vendors where vendors.id = addrver.vendor_id limit 1) as vendor_name,addrver.vendor_id");

        $this->db->from('addrver');

        $this->db->join("candidates_info",'candidates_info.id = addrver.candsid');

        $this->db->join("address_vendor_log",'address_vendor_log.case_id = addrver.id and address_vendor_log.status = 1');
            
        $this->db->join("view_vendor_master_log",'view_vendor_master_log.case_id = address_vendor_log.id');

        $this->db->join("addrverres as a1",'a1.addrverid = addrver.id','left');

        $this->db->join("addrverres as a2",'(a2.addrverid = addrver.id and a1.id < a2.id)','left');

        $this->db->where('a2.verfstatus is null');

        $this->db->where('addrver.vendor_id !=',' ');

        $this->db->where('address_vendor_log.modified_on <', $curr_date);

        $this->db->where("(a1.verfstatus = 1 or a1.verfstatus = 11 or a1.verfstatus = 12 or a1.verfstatus = 13 or a1.verfstatus = 14 or a1.verfstatus = 16 or a1.verfstatus = 23 or a1.verfstatus = 26)");
   
       

        $result  = $this->db->get();
      
        record_db_error($this->db->last_query());
       
        $addrver = $result->result_array();  


        $this->db->select("employment_vendor_log.modified_on as vendor_assign_on,(select clientname from clients where clients.id = empver.clientid limit 1) as clientname,empver.emp_com_ref as component_ref_no,'Employment' as component_name,view_vendor_master_log.trasaction_id,candidates_info.CandidateName,candidates_info.NameofCandidateFather,candidates_info.CandidatesContactNumber,candidates_info.ContactNo1,candidates_info.ContactNo2,(select coname from company_database where company_database.id = empver.nameofthecompany limit 1) as company_name,(select vendor_name from vendors where vendors.id = empver.vendor_id limit 1) as vendor_name,empver.vendor_id");


        $this->db->from('empver');

        $this->db->join("candidates_info",'candidates_info.id = empver.candsid');

        $this->db->join("employment_vendor_log",'employment_vendor_log.case_id = empver.id and employment_vendor_log.status = 1');
            
        $this->db->join("view_vendor_master_log",'view_vendor_master_log.case_id = employment_vendor_log.id');

        $this->db->join("empverres as a1",'a1.empverid = empver.id','left');

        $this->db->join("empverres as a2",'(a2.empverid = empver.id and a1.id < a2.id)','left');

        $this->db->where('a2.verfstatus is null');

        $this->db->where('empver.vendor_id !=',' ');

        $this->db->where('employment_vendor_log.modified_on <', $curr_date);

        $this->db->where("(a1.verfstatus = 1 or a1.verfstatus = 11 or a1.verfstatus = 12 or a1.verfstatus = 13 or a1.verfstatus = 14 or a1.verfstatus = 16 or  a1.verfstatus = 23 or a1.verfstatus = 26)");

        $result  = $this->db->get();
        
        record_db_error($this->db->last_query());
           
        $empver = $result->result_array();  
    

        $this->db->select("eduaction_vendor_log.modified_on as vendor_assign_on,(select clientname from clients where clients.id = education.clientid limit 1) as clientname,education.education_com_ref as component_ref_no,'Education' as component_name,education.mode_of_veri,view_vendor_master_log.trasaction_id,candidates_info.CandidateName,candidates_info.NameofCandidateFather,candidates_info.CandidatesContactNumber,candidates_info.ContactNo1,candidates_info.ContactNo2,(select qualification from qualification_master where qualification_master.id = education.qualification limit 1) as qualification_name,(select universityname from university_master where university_master.id = education.university_board limit 1) as university_name,education.grade_class_marks,education.month_of_passing,education.year_of_passing,education.roll_no,education.PRN_no,education.course_start_date,education.course_end_date,education.major,(select vendor_name from vendors where vendors.id = education.vendor_id limit 1) as vendor_name,education.vendor_id");

       
        $this->db->from('education');

        $this->db->join("candidates_info",'candidates_info.id = education.candsid');

        $this->db->join("eduaction_vendor_log",'eduaction_vendor_log.case_id = education.id and eduaction_vendor_log.status = 1');
            
        $this->db->join("view_vendor_master_log",'view_vendor_master_log.case_id = eduaction_vendor_log.id');

        $this->db->join("education_result as a1",'a1.education_id = education.id','left');

        $this->db->join("education_result as a2",'(a2.education_id = education.id and a1.id < a2.id)','left');

        $this->db->where('a2.verfstatus is null');

        $this->db->where('education.vendor_id !=',' ');

        $this->db->where('eduaction_vendor_log.modified_on <', $curr_date);

        $this->db->where("(a1.verfstatus = 1 or a1.verfstatus = 11 or a1.verfstatus = 12 or a1.verfstatus = 13 or a1.verfstatus = 14 or a1.verfstatus = 16 or  a1.verfstatus = 23 or a1.verfstatus = 26)");

        $result  = $this->db->get();
        
        record_db_error($this->db->last_query());
           
        $education = $result->result_array();  
    

       $this->db->select("courtver_vendor_log.modified_on as vendor_assign_on,courtver.court_com_ref as component_ref_no,'Court' as component_name,view_vendor_master_log.trasaction_id,candidates_info.CandidateName,candidates_info.NameofCandidateFather,candidates_info.DateofBirth,courtver.street_address,courtver.city,courtver.pincode,courtver.state,(select vendor_name from vendors where vendors.id = courtver.vendor_id limit 1) as vendor_name,courtver.vendor_id");

       $this->db->from('courtver');

        $this->db->join("candidates_info",'candidates_info.id = courtver.candsid');

        $this->db->join("courtver_vendor_log",'courtver_vendor_log.case_id = courtver.id and courtver_vendor_log.status = 1');
            
        $this->db->join("view_vendor_master_log",'view_vendor_master_log.case_id = courtver_vendor_log.id');

        $this->db->join("courtver_result as a1",'a1.courtver_id = courtver.id','left');

        $this->db->join("courtver_result as a2",'(a2.courtver_id = courtver.id and a1.id < a2.id)','left');

        $this->db->where('a2.verfstatus is null');

        $this->db->where('courtver.vendor_id !=',' ');

        $this->db->where('courtver_vendor_log.modified_on <', $curr_date);

        $this->db->where("(a1.verfstatus = 1 or a1.verfstatus = 11 or a1.verfstatus = 12 or a1.verfstatus = 13 or a1.verfstatus = 14 or a1.verfstatus = 16  or a1.verfstatus = 23 or a1.verfstatus = 26)");

        $result  = $this->db->get();
        
        record_db_error($this->db->last_query());
           
        $court = $result->result_array();  
    
        $this->db->select("glodbver_vendor_log.modified_on as vendor_assign_on,glodbver.global_com_ref  as component_ref_no,'Global Database' as component_name,view_vendor_master_log.trasaction_id,candidates_info.CandidateName,candidates_info.NameofCandidateFather,candidates_info.DateofBirth,glodbver.street_address,glodbver.city,glodbver.pincode,glodbver.state,(select vendor_name from vendors where vendors.id = glodbver.vendor_id limit 1) as vendor_name,glodbver.vendor_id");
    
        $this->db->from('glodbver');

        $this->db->join("candidates_info",'candidates_info.id = glodbver.candsid');

        $this->db->join("glodbver_vendor_log",'glodbver_vendor_log.case_id = glodbver.id and glodbver_vendor_log.status = 1');
            
        $this->db->join("view_vendor_master_log",'view_vendor_master_log.case_id = glodbver_vendor_log.id');

        $this->db->join("glodbver_result as a1",'a1.glodbver_id = glodbver.id','left');

        $this->db->join("glodbver_result as a2",'(a2.glodbver_id = glodbver.id and a1.id < a2.id)','left');

        $this->db->where('a2.verfstatus is null');

        $this->db->where('glodbver.vendor_id !=',' ');

        $this->db->where('glodbver_vendor_log.modified_on <', $curr_date);

        $this->db->where("(a1.verfstatus = 1 or a1.verfstatus = 11 or a1.verfstatus = 12 or a1.verfstatus = 13 or a1.verfstatus = 14 or a1.verfstatus = 16  or a1.verfstatus = 23 or a1.verfstatus = 26)");

        $result  = $this->db->get();
        
        record_db_error($this->db->last_query());
           
        $glodbver = $result->result_array();  

    
        $this->db->select("pcc_vendor_log.modified_on as vendor_assign_on,pcc.pcc_com_ref  as component_ref_no,'PCC' as component_name,view_vendor_master_log.trasaction_id,candidates_info.CandidateName,candidates_info.NameofCandidateFather,candidates_info.DateofBirth,pcc.street_address,pcc.city,pcc.pincode,pcc.state,(select vendor_name from vendors where vendors.id = pcc.vendor_id limit 1) as vendor_name,pcc.vendor_id");

        $this->db->from('pcc');

        $this->db->join("candidates_info",'candidates_info.id = pcc.candsid');

        $this->db->join("pcc_vendor_log",'pcc_vendor_log.case_id = pcc.id and pcc_vendor_log.status = 1');
            
        $this->db->join("view_vendor_master_log",'view_vendor_master_log.case_id = pcc_vendor_log.id');

        $this->db->join("pcc_result as a1",'a1.pcc_id = pcc.id','left');

        $this->db->join("pcc_result as a2",'(a2.pcc_id = pcc.id and a1.id < a2.id)','left');

        $this->db->where('a2.verfstatus is null');

        $this->db->where('pcc.vendor_id !=',' ');

        $this->db->where('pcc_vendor_log.modified_on <', $curr_date);

        $this->db->where("(a1.verfstatus = 1 or a1.verfstatus = 11 or a1.verfstatus = 12 or a1.verfstatus = 13 or a1.verfstatus = 14 or a1.verfstatus = 16  or a1.verfstatus = 23 or a1.verfstatus = 26)");

        $result  = $this->db->get();
        
        record_db_error($this->db->last_query());
           
        $pcc = $result->result_array();  


        $this->db->select("credit_report_vendor_log.modified_on as vendor_assign_on,credit_report.credit_report_com_ref  as component_ref_no,'Credit Report' as component_name,view_vendor_master_log.trasaction_id,candidates_info.CandidateName,candidates_info.NameofCandidateFather,candidates_info.DateofBirth,credit_report.doc_submited,(select vendor_name from vendors where vendors.id = credit_report.vendor_id limit 1) as vendor_name,credit_report.vendor_id");

        $this->db->from('credit_report');

        $this->db->join("candidates_info",'candidates_info.id = credit_report.candsid');

        $this->db->join("credit_report_vendor_log",'credit_report_vendor_log.case_id = credit_report.id and credit_report_vendor_log.status = 1');
            
        $this->db->join("view_vendor_master_log",'view_vendor_master_log.case_id = credit_report_vendor_log.id');

        $this->db->join("credit_report_result as a1",'a1.credit_report_id = credit_report.id','left');

        $this->db->join("credit_report_result as a2",'(a2.credit_report_id = credit_report.id and a1.id < a2.id)','left');

        $this->db->where('a2.verfstatus is null');

        $this->db->where('credit_report.vendor_id !=',' ');

        $this->db->where('credit_report_vendor_log.modified_on <', $curr_date);

        $this->db->where("(a1.verfstatus = 1 or a1.verfstatus = 11 or a1.verfstatus = 12 or a1.verfstatus = 13 or a1.verfstatus = 14 or a1.verfstatus = 16  or a1.verfstatus = 23 or a1.verfstatus = 26)");

        $result  = $this->db->get();
        
        record_db_error($this->db->last_query());
           
        $credit_report = $result->result_array();  


       return array_merge($empver,$addrver,$education,$court,$glodbver,$pcc,$credit_report);
    }


    public function get_vedor_datails_for_address($vendor_id)
    {
        //$date = new DateTime("now");

        //$curr_date = $date->format('Y-m-d');

        $this->db->select("address_vendor_log.modified_on as vendor_assign_on,(select clientname from clients where clients.id = addrver.clientid limit 1) as clientname,addrver.add_com_ref as component_ref_no,'Address' as component_name,view_vendor_master_log.trasaction_id,candidates_info.CandidateName,candidates_info.NameofCandidateFather,candidates_info.CandidatesContactNumber,candidates_info.ContactNo1,candidates_info.ContactNo2,addrver.address,addrver.city,addrver.state,addrver.pincode,(select vendor_name from vendors where vendors.id = addrver.vendor_id limit 1) as vendor_name,addrver.vendor_id");

        $this->db->from('addrver');

        $this->db->join("candidates_info",'candidates_info.id = addrver.candsid');

        $this->db->join("address_vendor_log",'address_vendor_log.case_id = addrver.id and address_vendor_log.status = 1');
            
        $this->db->join("view_vendor_master_log","(view_vendor_master_log.case_id = address_vendor_log.id and view_vendor_master_log.component = 'addrver')");

        $this->db->join("addrverres as a1",'a1.addrverid = addrver.id','left');

        $this->db->join("addrverres as a2",'(a2.addrverid = addrver.id and a1.id < a2.id)','left');

        $this->db->where('a2.verfstatus is null');

        $this->db->where("(view_vendor_master_log.final_status = 'wip' or view_vendor_master_log.final_status = 'WIP')");

        $this->db->where('addrver.vendor_id',$vendor_id);

       // $this->db->where('addrver.clientid !=',1);

       // $this->db->where('address_vendor_log.modified_on <', $curr_date);

      //  $this->db->where("(a1.verfstatus = 1 or a1.verfstatus = 11 or a1.verfstatus = 12 or a1.verfstatus = 13 or a1.verfstatus = 14 or a1.verfstatus = 16 or a1.verfstatus = 23 or a1.verfstatus = 26)");
   

        $result  = $this->db->get();
     
        record_db_error($this->db->last_query());
        return  $result->result_array();  
    }

    public function get_address_date_wise_count($vendor_id)
    {
       // $date = new DateTime("now");

       // $curr_date = $date->format('Y-m-d');

        $this->db->select("DATE_FORMAT(`address_vendor_log`.`modified_on`,'%d-%m-%Y') as `date`, count(`address_vendor_log`.`modified_on`) as count_record");

        $this->db->from('addrver');

        $this->db->join("candidates_info",'candidates_info.id = addrver.candsid');

        $this->db->join("address_vendor_log",'address_vendor_log.case_id = addrver.id and address_vendor_log.status = 1');
            
        $this->db->join("view_vendor_master_log","(view_vendor_master_log.case_id = address_vendor_log.id and view_vendor_master_log.component = 'addrver')");

        $this->db->join("addrverres as a1",'a1.addrverid = addrver.id','left');

        $this->db->join("addrverres as a2",'(a2.addrverid = addrver.id and a1.id < a2.id)','left');

        $this->db->where('a2.verfstatus is null');

        $this->db->where("(view_vendor_master_log.final_status = 'wip' or view_vendor_master_log.final_status = 'WIP')");


        $this->db->where('addrver.vendor_id',$vendor_id);

      //  $this->db->where('addrver.clientid !=',1);

       // $this->db->where('address_vendor_log.modified_on <', $curr_date);

       // $this->db->where("(a1.verfstatus = 1 or a1.verfstatus = 11 or a1.verfstatus = 12 or a1.verfstatus = 13 or a1.verfstatus = 14 or a1.verfstatus = 16 or a1.verfstatus = 23 or a1.verfstatus = 26)");
   
       $this->db->group_by('DATE(address_vendor_log.modified_on)');

        $result  = $this->db->get();

        record_db_error($this->db->last_query());
        
        return  $result->result_array();  
    }


    public function get_vedor_datails_for_employment($vendor_id)
    {
       // $date = new DateTime("now");

       // $curr_date = $date->format('Y-m-d');

        $this->db->select("employment_vendor_log.modified_on as vendor_assign_on,(select clientname from clients where clients.id = empver.clientid limit 1) as clientname,empver.emp_com_ref as component_ref_no,'Employment' as component_name,view_vendor_master_log.trasaction_id,candidates_info.CandidateName,candidates_info.NameofCandidateFather,candidates_info.CandidatesContactNumber,candidates_info.ContactNo1,candidates_info.ContactNo2,(select coname from company_database where company_database.id = empver.nameofthecompany limit 1) as company_name,(select vendor_name from vendors where vendors.id = empver.vendor_id limit 1) as vendor_name,empver.vendor_id");


        $this->db->from('empver');

        $this->db->join("candidates_info",'candidates_info.id = empver.candsid');

        $this->db->join("employment_vendor_log",'employment_vendor_log.case_id = empver.id and employment_vendor_log.status = 1');
            
        $this->db->join("view_vendor_master_log","(view_vendor_master_log.case_id = employment_vendor_log.id and view_vendor_master_log.component = 'empver')");

        $this->db->join("empverres as a1",'a1.empverid = empver.id','left');

        $this->db->join("empverres as a2",'(a2.empverid = empver.id and a1.id < a2.id)','left');

        $this->db->where('a2.verfstatus is null');

        $this->db->where("(view_vendor_master_log.final_status = 'wip' or view_vendor_master_log.final_status = 'WIP')");

        $this->db->where('empver.vendor_id',$vendor_id);

        $this->db->where('empver.clientid !=',1);

       // $this->db->where('employment_vendor_log.modified_on <', $curr_date);

       // $this->db->where("(a1.verfstatus = 1 or a1.verfstatus = 11 or a1.verfstatus = 12 or a1.verfstatus = 13 or a1.verfstatus = 14 or a1.verfstatus = 16 or  a1.verfstatus = 23 or a1.verfstatus = 26)");

        $result  = $this->db->get();
        record_db_error($this->db->last_query());
        
        return  $result->result_array();  
    }

    public function get_employment_date_wise_count($vendor_id)
    {
       // $date = new DateTime("now");

       // $curr_date = $date->format('Y-m-d');

        $this->db->select("DATE_FORMAT(`employment_vendor_log`.`modified_on`,'%d-%m-%Y') as `date`, count(`employment_vendor_log`.`modified_on`) as count_record");

        $this->db->from('empver');

        $this->db->join("candidates_info",'candidates_info.id = empver.candsid');

        $this->db->join("employment_vendor_log",'employment_vendor_log.case_id = empver.id and employment_vendor_log.status = 1');
            
        $this->db->join("view_vendor_master_log","(view_vendor_master_log.case_id = employment_vendor_log.id and view_vendor_master_log.component = 'empver')");

        $this->db->join("empverres as a1",'a1.empverid = empver.id','left');

        $this->db->join("empverres as a2",'(a2.empverid = empver.id and a1.id < a2.id)','left');

        $this->db->where('a2.verfstatus is null');

        $this->db->where("(view_vendor_master_log.final_status = 'wip' or view_vendor_master_log.final_status = 'WIP')");

        $this->db->where('empver.vendor_id',$vendor_id);

        $this->db->where('empver.clientid !=',1);

      //  $this->db->where('employment_vendor_log.modified_on <', $curr_date);

      //  $this->db->where("(a1.verfstatus = 1 or a1.verfstatus = 11 or a1.verfstatus = 12 or a1.verfstatus = 13 or a1.verfstatus = 14 or a1.verfstatus = 16 or  a1.verfstatus = 23 or a1.verfstatus = 26)");

        $this->db->group_by('DATE(employment_vendor_log.modified_on)');


        $result  = $this->db->get();
        
        record_db_error($this->db->last_query());
        
        return  $result->result_array();  
    }

    public function get_vedor_datails_for_education($vendor_id)
    {
       // $date = new DateTime("now");

       // $curr_date = $date->format('Y-m-d');

       $this->db->select("education_vendor_log.modified_on as vendor_assign_on,(select clientname from clients where clients.id = education.clientid limit 1) as clientname,education.education_com_ref as component_ref_no,'Education' as component_name,education.mode_of_veri,view_vendor_master_log.trasaction_id,candidates_info.CandidateName,candidates_info.NameofCandidateFather,candidates_info.CandidatesContactNumber,candidates_info.ContactNo1,candidates_info.ContactNo2,(select qualification from qualification_master where qualification_master.id = education.qualification limit 1) as qualification_name,(select universityname from university_master where university_master.id = education.university_board limit 1) as university_name,education.grade_class_marks,education.month_of_passing,education.year_of_passing,education.roll_no,education.PRN_no,education.course_start_date,education.course_end_date,education.major,(select vendor_name from vendors where vendors.id = education.vendor_id limit 1) as vendor_name,education.vendor_id");

       
        $this->db->from('education');

        $this->db->join("candidates_info",'candidates_info.id = education.candsid');

        $this->db->join("education_vendor_log",'education_vendor_log.case_id = education.id and education_vendor_log.status = 1');
     
        $this->db->join("view_vendor_master_log","(view_vendor_master_log.case_id = education_vendor_log.id and view_vendor_master_log.component = 'eduver')");

        $this->db->join("education_result as a1",'a1.education_id = education.id','left');

        $this->db->join("education_result as a2",'(a2.education_id = education.id and a1.id < a2.id)','left');

        $this->db->where('a2.verfstatus is null');

        $this->db->where("(view_vendor_master_log.final_status = 'wip' or view_vendor_master_log.final_status = 'WIP')");

        $this->db->where('education.vendor_id',$vendor_id);

        $this->db->where('education.clientid !=',1);

       // $this->db->where('education_vendor_log.modified_on <', $curr_date);

       // $this->db->where("(a1.verfstatus = 1 or a1.verfstatus = 11 or a1.verfstatus = 12 or a1.verfstatus = 13 or a1.verfstatus = 14 or a1.verfstatus = 16 or  a1.verfstatus = 23 or a1.verfstatus = 26)");

        $result  = $this->db->get();
        
        record_db_error($this->db->last_query());
           
        return $result->result_array();  
      
    }

    public function get_education_date_wise_count($vendor_id)
    {
      //  $date = new DateTime("now");

      //  $curr_date = $date->format('Y-m-d');

        $this->db->select("DATE_FORMAT(`education_vendor_log`.`modified_on`,'%d-%m-%Y') as `date`, count(`education_vendor_log`.`modified_on`) as count_record");

        $this->db->from('education');

        $this->db->join("candidates_info",'candidates_info.id = education.candsid');

        $this->db->join("education_vendor_log",'education_vendor_log.case_id = education.id and education_vendor_log.status = 1');
            
        $this->db->join("view_vendor_master_log","(view_vendor_master_log.case_id = education_vendor_log.id and view_vendor_master_log.component = 'eduver')");

        $this->db->join("education_result as a1",'a1.education_id = education.id','left');

        $this->db->join("education_result as a2",'(a2.education_id = education.id and a1.id < a2.id)','left');

        $this->db->where('a2.verfstatus is null');

        $this->db->where("(view_vendor_master_log.final_status = 'wip' or view_vendor_master_log.final_status = 'WIP')");


        $this->db->where('education.vendor_id',$vendor_id);

        $this->db->where('education.clientid !=',1);

       // $this->db->where('education_vendor_log.modified_on <', $curr_date);

       // $this->db->where("(a1.verfstatus = 1 or a1.verfstatus = 11 or a1.verfstatus = 12 or a1.verfstatus = 13 or a1.verfstatus = 14 or a1.verfstatus = 16 or  a1.verfstatus = 23 or a1.verfstatus = 26)");

        $this->db->group_by('DATE(education_vendor_log.modified_on)');


        $result  = $this->db->get();
        
        record_db_error($this->db->last_query());
           
        return $result->result_array();  
      
    }


    public function get_vedor_datails_for_court($vendor_id)
    {
       // $date = new DateTime("now");

       // $curr_date = $date->format('Y-m-d');

        $this->db->select("courtver_vendor_log.modified_on as vendor_assign_on,courtver.court_com_ref as component_ref_no,'Court' as component_name,view_vendor_master_log.trasaction_id,candidates_info.CandidateName,candidates_info.NameofCandidateFather,candidates_info.DateofBirth,courtver.street_address,courtver.city,courtver.pincode,courtver.state,(select vendor_name from vendors where vendors.id = courtver.vendor_id limit 1) as vendor_name,courtver.vendor_id");

        $this->db->from('courtver');

        $this->db->join("candidates_info",'candidates_info.id = courtver.candsid');

        $this->db->join("courtver_vendor_log",'courtver_vendor_log.case_id = courtver.id and courtver_vendor_log.status = 1');

       
        $this->db->join("view_vendor_master_log","(view_vendor_master_log.case_id = courtver_vendor_log.id and view_vendor_master_log.component = 'courtver')");
            
      
        $this->db->join("courtver_result as a1",'a1.courtver_id = courtver.id','left');

        $this->db->join("courtver_result as a2",'(a2.courtver_id = courtver.id and a1.id < a2.id)','left');

        $this->db->where('a2.verfstatus is null');

        $this->db->where("(view_vendor_master_log.final_status = 'wip' or view_vendor_master_log.final_status = 'WIP')");

        $this->db->where('courtver.vendor_id',$vendor_id);

        $this->db->where('courtver.clientid !=',1);

       // $this->db->where('courtver_vendor_log.modified_on <', $curr_date);

       // $this->db->where("(a1.verfstatus = 1 or a1.verfstatus = 11 or a1.verfstatus = 12 or a1.verfstatus = 13 or a1.verfstatus = 14 or a1.verfstatus = 16  or a1.verfstatus = 23 or a1.verfstatus = 26)");

        $result  = $this->db->get();
        
        record_db_error($this->db->last_query());
           
        return $result->result_array();  
    
      
    }

    public function get_court_date_wise_count($vendor_id)
    {
      //  $date = new DateTime("now");

      //  $curr_date = $date->format('Y-m-d');

        $this->db->select("DATE_FORMAT(`courtver_vendor_log`.`modified_on`,'%d-%m-%Y') as `date`, count(`courtver_vendor_log`.`modified_on`) as count_record");

        $this->db->from('courtver');

        $this->db->join("candidates_info",'candidates_info.id = courtver.candsid');

        $this->db->join("courtver_vendor_log",'courtver_vendor_log.case_id = courtver.id and courtver_vendor_log.status = 1');
            
        $this->db->join("view_vendor_master_log","(view_vendor_master_log.case_id = courtver_vendor_log.id and view_vendor_master_log.component = 'courtver')");

        $this->db->join("courtver_result as a1",'a1.courtver_id = courtver.id','left');

        $this->db->join("courtver_result as a2",'(a2.courtver_id = courtver.id and a1.id < a2.id)','left');

        $this->db->where('a2.verfstatus is null');

        $this->db->where("(view_vendor_master_log.final_status = 'wip' or view_vendor_master_log.final_status = 'WIP')");

        $this->db->where('courtver.vendor_id',$vendor_id);

        $this->db->where('courtver.clientid !=',1);

       // $this->db->where('courtver_vendor_log.modified_on <', $curr_date);

      //  $this->db->where("(a1.verfstatus = 1 or a1.verfstatus = 11 or a1.verfstatus = 12 or a1.verfstatus = 13 or a1.verfstatus = 14 or a1.verfstatus = 16  or a1.verfstatus = 23 or a1.verfstatus = 26)");

        $this->db->group_by('DATE(courtver_vendor_log.modified_on)');

        $result  = $this->db->get();
        
        record_db_error($this->db->last_query());
           
        return $result->result_array();  
      
    }

    public function get_vedor_datails_for_global($vendor_id)
    {
       // $date = new DateTime("now");

       // $curr_date = $date->format('Y-m-d');

        $this->db->select("glodbver_vendor_log.modified_on as vendor_assign_on,glodbver.global_com_ref  as component_ref_no,'Global Database' as component_name,view_vendor_master_log.trasaction_id,candidates_info.CandidateName,candidates_info.NameofCandidateFather,candidates_info.DateofBirth,candidates_info.gender,glodbver.street_address,glodbver.city,glodbver.pincode,glodbver.state,(select vendor_name from vendors where vendors.id = glodbver.vendor_id limit 1) as vendor_name,glodbver.vendor_id");
    
        $this->db->from('glodbver');

        $this->db->join("candidates_info",'candidates_info.id = glodbver.candsid');

        $this->db->join("glodbver_vendor_log",'glodbver_vendor_log.case_id = glodbver.id and glodbver_vendor_log.status = 1');
            

        $this->db->join("view_vendor_master_log","(view_vendor_master_log.case_id = glodbver_vendor_log.id and view_vendor_master_log.component = 'globdbver')");

        $this->db->join("glodbver_result as a1",'a1.glodbver_id = glodbver.id','left');

        $this->db->join("glodbver_result as a2",'(a2.glodbver_id = glodbver.id and a1.id < a2.id)','left');

        $this->db->where('a2.verfstatus is null');

        $this->db->where("(view_vendor_master_log.final_status = 'wip' or view_vendor_master_log.final_status = 'WIP')");

        $this->db->where('glodbver.vendor_id',$vendor_id);

        $this->db->where('glodbver.clientid !=',1);

       // $this->db->where('glodbver_vendor_log.modified_on <', $curr_date);

      //  $this->db->where("(a1.verfstatus = 1 or a1.verfstatus = 11 or a1.verfstatus = 12 or a1.verfstatus = 13 or a1.verfstatus = 14 or a1.verfstatus = 16  or a1.verfstatus = 23 or a1.verfstatus = 26)");

        $result  = $this->db->get();
        
        record_db_error($this->db->last_query());
           
        return $result->result_array();  

      
    }

    public function get_global_date_wise_count($vendor_id)
    {
        //$date = new DateTime("now");

        //$curr_date = $date->format('Y-m-d');

        $this->db->select("DATE_FORMAT(`glodbver_vendor_log`.`modified_on`,'%d-%m-%Y') as `date`, count(`glodbver_vendor_log`.`modified_on`) as count_record");
    
        $this->db->from('glodbver');

        $this->db->join("candidates_info",'candidates_info.id = glodbver.candsid');

        $this->db->join("glodbver_vendor_log",'glodbver_vendor_log.case_id = glodbver.id and glodbver_vendor_log.status = 1');
            
        $this->db->join("view_vendor_master_log","(view_vendor_master_log.case_id = glodbver_vendor_log.id and view_vendor_master_log.component = 'globdbver')");

        $this->db->join("glodbver_result as a1",'a1.glodbver_id = glodbver.id','left');

        $this->db->join("glodbver_result as a2",'(a2.glodbver_id = glodbver.id and a1.id < a2.id)','left');

        $this->db->where('a2.verfstatus is null');

        $this->db->where("(view_vendor_master_log.final_status = 'wip' or view_vendor_master_log.final_status = 'WIP')");


        $this->db->where('glodbver.vendor_id',$vendor_id);

        $this->db->where('glodbver.clientid !=',1);

       // $this->db->where('glodbver_vendor_log.modified_on <', $curr_date);

       // $this->db->where("(a1.verfstatus = 1 or a1.verfstatus = 11 or a1.verfstatus = 12 or a1.verfstatus = 13 or a1.verfstatus = 14 or a1.verfstatus = 16  or a1.verfstatus = 23 or a1.verfstatus = 26)");

        $this->db->group_by('DATE(glodbver_vendor_log.modified_on)');

        $result  = $this->db->get();
        
        record_db_error($this->db->last_query());
           
        return $result->result_array();  
      
    }

    public function get_vedor_datails_for_pcc($vendor_id)
    {
        //$date = new DateTime("now");

        //$curr_date = $date->format('Y-m-d');

       $this->db->select("pcc_vendor_log.modified_on as vendor_assign_on,pcc.pcc_com_ref  as component_ref_no,'PCC' as component_name,view_vendor_master_log.trasaction_id,candidates_info.CandidateName,candidates_info.NameofCandidateFather,candidates_info.DateofBirth,pcc.street_address,pcc.city,pcc.pincode,pcc.state,(select vendor_name from vendors where vendors.id = pcc.vendor_id limit 1) as vendor_name,pcc.vendor_id");

        $this->db->from('pcc');

        $this->db->join("candidates_info",'candidates_info.id = pcc.candsid');

        $this->db->join("pcc_vendor_log",'pcc_vendor_log.case_id = pcc.id and pcc_vendor_log.status = 1');
            
        $this->db->join("view_vendor_master_log","(view_vendor_master_log.case_id = pcc_vendor_log.id and view_vendor_master_log.component = 'crimver')");

        $this->db->join("pcc_result as a1",'a1.pcc_id = pcc.id','left');

        $this->db->join("pcc_result as a2",'(a2.pcc_id = pcc.id and a1.id < a2.id)','left');

        $this->db->where('a2.verfstatus is null');

        $this->db->where("(view_vendor_master_log.final_status = 'wip' or view_vendor_master_log.final_status = 'WIP')");


        $this->db->where('pcc.vendor_id',$vendor_id);

        $this->db->where('pcc.clientid !=',1);

      //  $this->db->where('pcc_vendor_log.modified_on <', $curr_date);

     //   $this->db->where("(a1.verfstatus = 1 or a1.verfstatus = 11 or a1.verfstatus = 12 or a1.verfstatus = 13 or a1.verfstatus = 14 or a1.verfstatus = 16  or a1.verfstatus = 23 or a1.verfstatus = 26)");

        $result  = $this->db->get();
        
        record_db_error($this->db->last_query());
           
        return $result->result_array();  
      
    }

    public function get_pcc_date_wise_count($vendor_id)
    {
        //$date = new DateTime("now");

        //$curr_date = $date->format('Y-m-d');

        $this->db->select("DATE_FORMAT(`pcc_vendor_log`.`modified_on`,'%d-%m-%Y') as `date`, count(`pcc_vendor_log`.`modified_on`) as count_record");
    

        $this->db->from('pcc');

        $this->db->join("candidates_info",'candidates_info.id = pcc.candsid');

        $this->db->join("pcc_vendor_log",'pcc_vendor_log.case_id = pcc.id and pcc_vendor_log.status = 1');
            
        $this->db->join("view_vendor_master_log","(view_vendor_master_log.case_id = pcc_vendor_log.id and view_vendor_master_log.component = 'crimver')");


        $this->db->join("pcc_result as a1",'a1.pcc_id = pcc.id','left');

        $this->db->join("pcc_result as a2",'(a2.pcc_id = pcc.id and a1.id < a2.id)','left');

        $this->db->where('a2.verfstatus is null');

        $this->db->where("(view_vendor_master_log.final_status = 'wip' or view_vendor_master_log.final_status = 'WIP')");

        $this->db->where('pcc.vendor_id',$vendor_id);

        $this->db->where('pcc.clientid !=',1);

       // $this->db->where('pcc_vendor_log.modified_on <', $curr_date);

      //  $this->db->where("(a1.verfstatus = 1 or a1.verfstatus = 11 or a1.verfstatus = 12 or a1.verfstatus = 13 or a1.verfstatus = 14 or a1.verfstatus = 16  or a1.verfstatus = 23 or a1.verfstatus = 26)");

        $this->db->group_by('DATE(pcc_vendor_log.modified_on)');


        $result  = $this->db->get();
        
        record_db_error($this->db->last_query());
           
        return $result->result_array();  
      
    }


    public function get_vedor_datails_for_credit_report($vendor_id)
    {
        //$date = new DateTime("now");

        //$curr_date = $date->format('Y-m-d');

        $this->db->select("credit_report_vendor_log.modified_on as vendor_assign_on,credit_report.credit_report_com_ref  as component_ref_no,'Credit Report' as component_name,view_vendor_master_log.trasaction_id,candidates_info.CandidateName,candidates_info.NameofCandidateFather,candidates_info.DateofBirth,credit_report.doc_submited,(select vendor_name from vendors where vendors.id = credit_report.vendor_id limit 1) as vendor_name,credit_report.vendor_id,credit_report.street_address,credit_report.id_number");

        $this->db->from('credit_report');

        $this->db->join("candidates_info",'candidates_info.id = credit_report.candsid');

        $this->db->join("credit_report_vendor_log",'credit_report_vendor_log.case_id = credit_report.id and credit_report_vendor_log.status = 1');
            
        $this->db->join("view_vendor_master_log","(view_vendor_master_log.case_id = credit_report_vendor_log.id and view_vendor_master_log.component = 'cbrver')");


        $this->db->join("credit_report_result as a1",'a1.credit_report_id = credit_report.id','left');

        $this->db->join("credit_report_result as a2",'(a2.credit_report_id = credit_report.id and a1.id < a2.id)','left');

        $this->db->where('a2.verfstatus is null');

        $this->db->where("(view_vendor_master_log.final_status = 'wip' or view_vendor_master_log.final_status = 'WIP')");

        $this->db->where('credit_report.vendor_id',$vendor_id);

        $this->db->where('credit_report.clientid !=',1);

       // $this->db->where('credit_report_vendor_log.modified_on <', $curr_date);

       // $this->db->where("(a1.verfstatus = 1 or a1.verfstatus = 11 or a1.verfstatus = 12 or a1.verfstatus = 13 or a1.verfstatus = 14 or a1.verfstatus = 16  or a1.verfstatus = 23 or a1.verfstatus = 26)");

        $result  = $this->db->get();
        
        record_db_error($this->db->last_query());
           
        return $result->result_array();  
  

      
    }

    public function get_credit_date_wise_count($vendor_id)
    {
       // $date = new DateTime("now");

       // $curr_date = $date->format('Y-m-d');

        $this->db->select("DATE_FORMAT(`credit_report_vendor_log`.`modified_on`,'%d-%m-%Y') as `date`, count(`credit_report_vendor_log`.`modified_on`) as count_record");

        $this->db->from('credit_report');

        $this->db->join("candidates_info",'candidates_info.id = credit_report.candsid');

        $this->db->join("credit_report_vendor_log",'credit_report_vendor_log.case_id = credit_report.id and credit_report_vendor_log.status = 1');
            
       

        $this->db->join("view_vendor_master_log","(view_vendor_master_log.case_id = credit_report_vendor_log.id and view_vendor_master_log.component = 'cbrver')");


        $this->db->join("credit_report_result as a1",'a1.credit_report_id = credit_report.id','left');

        $this->db->join("credit_report_result as a2",'(a2.credit_report_id = credit_report.id and a1.id < a2.id)','left');

        $this->db->where('a2.verfstatus is null');

        $this->db->where("(view_vendor_master_log.final_status = 'wip' or view_vendor_master_log.final_status = 'WIP')");


        $this->db->where('credit_report.vendor_id',$vendor_id);

        $this->db->where('credit_report.clientid !=',1);

       // $this->db->where('credit_report_vendor_log.modified_on <', $curr_date);

     //   $this->db->where("(a1.verfstatus = 1 or a1.verfstatus = 11 or a1.verfstatus = 12 or a1.verfstatus = 13 or a1.verfstatus = 14 or a1.verfstatus = 16  or a1.verfstatus = 23 or a1.verfstatus = 26)");

       $this->db->group_by('DATE(credit_report_vendor_log.modified_on)');


        $result  = $this->db->get();
        
        record_db_error($this->db->last_query());
           
        return $result->result_array();  
 
    }


    public function get_vedor_datails_for_drugs($vendor_id)
    {
        //$date = new DateTime("now");

        //$curr_date = $date->format('Y-m-d');

        $this->db->select("drug_narcotis_vendor_log.modified_on as vendor_assign_on,drug_narcotis.drug_com_ref  as component_ref_no,'Drugs' as component_name,view_vendor_master_log.trasaction_id,candidates_info.CandidateName,candidates_info.NameofCandidateFather,candidates_info.DateofBirth,drug_narcotis.drug_test_code,(select vendor_name from vendors where vendors.id = drug_narcotis.vendor_id limit 1) as vendor_name,drug_narcotis.vendor_id");

        $this->db->from('drug_narcotis');

        $this->db->join("candidates_info",'candidates_info.id = drug_narcotis.candsid');

        $this->db->join("drug_narcotis_vendor_log",'drug_narcotis_vendor_log.case_id = drug_narcotis.id and drug_narcotis_vendor_log.status = 1');
            
        $this->db->join("view_vendor_master_log","(view_vendor_master_log.case_id = drug_narcotis_vendor_log.id and view_vendor_master_log.component = 'narcver')");


        $this->db->join("drug_narcotis_result as a1",'a1.drug_narcotis_id = drug_narcotis.id','left');

        $this->db->join("drug_narcotis_result as a2",'(a2.drug_narcotis_id = drug_narcotis.id and a1.id < a2.id)','left');

        $this->db->where('a2.verfstatus is null');

        $this->db->where("(view_vendor_master_log.final_status = 'wip' or view_vendor_master_log.final_status = 'WIP')");

        $this->db->where('drug_narcotis.vendor_id',$vendor_id);

        $this->db->where('drug_narcotis.clientid !=',1);

       // $this->db->where('credit_report_vendor_log.modified_on <', $curr_date);

       // $this->db->where("(a1.verfstatus = 1 or a1.verfstatus = 11 or a1.verfstatus = 12 or a1.verfstatus = 13 or a1.verfstatus = 14 or a1.verfstatus = 16  or a1.verfstatus = 23 or a1.verfstatus = 26)");

        $result  = $this->db->get();
        
        record_db_error($this->db->last_query());
           
        return $result->result_array();  
  

      
    }

    public function get_drugs_date_wise_count($vendor_id)
    {
       // $date = new DateTime("now");

       // $curr_date = $date->format('Y-m-d');

        $this->db->select("DATE_FORMAT(`drug_narcotis_vendor_log`.`modified_on`,'%d-%m-%Y') as `date`, count(`drug_narcotis_vendor_log`.`modified_on`) as count_record");

        $this->db->from('drug_narcotis');

        $this->db->join("candidates_info",'candidates_info.id = drug_narcotis.candsid');

        $this->db->join("drug_narcotis_vendor_log",'drug_narcotis_vendor_log.case_id = drug_narcotis.id and drug_narcotis_vendor_log.status = 1');
            
       

        $this->db->join("view_vendor_master_log","(view_vendor_master_log.case_id = drug_narcotis_vendor_log.id and view_vendor_master_log.component = 'narcver')");


        $this->db->join("drug_narcotis_result as a1",'a1.drug_narcotis_id = drug_narcotis.id','left');

        $this->db->join("drug_narcotis_result as a2",'(a2.drug_narcotis_id = drug_narcotis.id and a1.id < a2.id)','left');

        $this->db->where('a2.verfstatus is null');

        $this->db->where("(view_vendor_master_log.final_status = 'wip' or view_vendor_master_log.final_status = 'WIP')");


        $this->db->where('drug_narcotis.vendor_id',$vendor_id);

        $this->db->where('drug_narcotis.clientid !=',1);

       // $this->db->where('credit_report_vendor_log.modified_on <', $curr_date);

     //   $this->db->where("(a1.verfstatus = 1 or a1.verfstatus = 11 or a1.verfstatus = 12 or a1.verfstatus = 13 or a1.verfstatus = 14 or a1.verfstatus = 16  or a1.verfstatus = 23 or a1.verfstatus = 26)");

       $this->db->group_by('DATE(drug_narcotis_vendor_log.modified_on)');


        $result  = $this->db->get();
        
        record_db_error($this->db->last_query());
           
        return $result->result_array();  
 
    }


     public function get_address_user_name_password($where_array)
     {
        $this->db->select("firstname,lastname,designation,department,email,email_password,reporting_manager");

        $this->db->from('user_profile');

        $this->db->where($where_array);

        $result  = $this->db->get();
        
        record_db_error($this->db->last_query());
           
        return $result->result_array();  

     }

     public function get_repoting_manager_email_id($where_array)
     {
       
        $this->db->select("email");

        $this->db->from('user_profile');

        $this->db->where($where_array);

        $result  = $this->db->get();
        
        record_db_error($this->db->last_query());
           
        return $result->result_array();  
     }
}

?>