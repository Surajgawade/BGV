<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Eduver_model extends CI_Model
{
	function __construct()
    {
		$this->tableName = 'education';

		$this->primaryKey = 'id';
	}
     
    public function update($tableName,$arrdata,$arrwhere = array())
    {
        if(!empty($arrwhere))
        {
            $this->db->where($arrwhere);

            $result = $this->db->update($tableName, $arrdata);
       
            record_db_error($this->db->last_query());
             
            return $result;
        }
       
    }


    public function select_file($select_array,$where_array)
    {
        $this->db->select($select_array);

        $this->db->from('view_vendor_master_log_file');

        $this->db->where($where_array);

        $this->db->order_by('id', 'desc');
        
        $result  = $this->db->get();

        record_db_error($this->db->last_query());
        
        return $result->result_array();
    }

    public function select_file_from_admin($select_array,$where_array)
    {
        $this->db->select($select_array);

        $this->db->from('education_files');

        $this->db->where($where_array);

        $this->db->where('(education_files.type = 0  or education_files.type = 2)');

        $this->db->order_by('id', 'desc');
        
        $result  = $this->db->get();

        record_db_error($this->db->last_query());
        
        return $result->result_array();
    }


    public function select_client_id($select_array,$where_array)
    {
        $this->db->select($select_array);

        $this->db->from('education');

        $this->db->where($where_array);

        $result  = $this->db->get();

        record_db_error($this->db->last_query());
        
        return $result->result_array();
    }

    public function education_case_list($where_array = array(),$where,$columns)
    {
        $this->db->select("`view_vendor_master_log`.trasaction_id,`view_vendor_master_log`.modified_on,`view_vendor_master_log`.`final_status`,`view_vendor_master_log`.`created_on`,`view_vendor_master_log`.`id`,CandidateName,clients.clientname,education.id as education_id,education.education_com_ref,education.school_college,education.due_date,education.vendor_list_mode,education.city,education.state,education.tat_status,(select universityname from university_master where university_master.id = education.university_board limit 1) as university_board,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select user_name from vendor_executive_login where vendor_executive_login.id = view_vendor_master_log.has_case_id limit 1) as vendor_executive_id,candidates_info.ClientRefNumber as client_ref_no,candidates_info.cmp_ref_no as cmp_ref_no");
        $this->db->from('view_vendor_master_log');
       // $this->db->join('addrver','addrver.id = view_vendor_master_log.component_tbl_id');
        $this->db->join('education_vendor_log','(education_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "eduver" and view_vendor_master_log.component_tbl_id = 3)');
        $this->db->join('education','education.id = education_vendor_log.case_id');
        $this->db->join('candidates_info','candidates_info.id = education.candsid');
       
        $this->db->join("clients",'clients.id = education.clientid');


        $this->db->where($where_array);
        
        if(is_array($where) && $where['search']['value'] != "" )
        {
           
            //$this->db->group_start();
            $this->db->like('candidates_info.cmp_ref_no', $where['search']['value']);

            $this->db->or_like('candidates_info.ClientRefNumber', $where['search']['value']);

            $this->db->or_like('education.education_com_ref', $where['search']['value']);

            $this->db->or_like('candidates_info.CandidateName', $where['search']['value']);

            $this->db->or_like('view_vendor_master_log.trasaction_id', $where['search']['value']);

           // $this->db->group_end();
        }



        if(!empty($where['order']))
        {
            $column_name_index = $where['order'][0]['column'];
            $order_by = $where['order'][0]['dir']; 
           
            $this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
        }
        else
        {
            $this->db->order_by('view_vendor_master_log.modified_on','ASC');
        }
   
        $result = $this->db->get();

       return $result->result_array();
    
    }


    public function education_case_list_closed($where_array = array(),$where,$columns)
    {

        $this->db->select("view_vendor_master_log.*,CandidateName,clients.clientname,education.id as education_id,education.education_com_ref,education.school_college,education.vendor_list_mode,education.city,education.state,education.tat_status,education.due_date,(select universityname from university_master where university_master.id = education.university_board limit 1) as university_board,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select user_name from vendor_executive_login where vendor_executive_login.id = view_vendor_master_log.has_case_id limit 1) as vendor_executive_id,candidates_info.ClientRefNumber as client_ref_no,candidates_info.cmp_ref_no as cmp_ref_no");

        $this->db->from('view_vendor_master_log');
       // $this->db->join('addrver','addrver.id = view_vendor_master_log.component_tbl_id');
        $this->db->join('education_vendor_log','(education_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "eduver" and view_vendor_master_log.component_tbl_id = 3)'); 
        
        $this->db->join('education','education.id = education_vendor_log.case_id');

        $this->db->join('candidates_info','candidates_info.id = education.candsid');
       
        $this->db->join("clients",'clients.id = education.clientid');


        $this->db->where($where_array);


        if(isset($where['filter_by_status']) &&  $where['filter_by_status'] != '')
        { 

            $filter_status    = $where['filter_by_status'];
            if($filter_status == "All")
            {
 
            $where_condition = "(view_vendor_master_log.final_status = 'clear' or view_vendor_master_log.final_status = 'major discrepancy' or view_vendor_master_log.final_status = 'minor discrepancy' or view_vendor_master_log.final_status = 'no record found' or 
                view_vendor_master_log.final_status = 'unable to verify' or view_vendor_master_log.final_status = 'approve')" ;
            }
            elseif ($filter_status == "clear") {
                $where_condition = "view_vendor_master_log.final_status = 'clear'" ;
            }
            elseif ($filter_status == "major discrepancy") {
                $where_condition = "view_vendor_master_log.final_status = 'major discrepancy'" ;
            }
            elseif ($filter_status == "minor discrepancy") {
                $where_condition = "view_vendor_master_log.final_status = 'minor discrepancy'" ;
            }
            elseif ($filter_status == "no record found") {
                $where_condition = "view_vendor_master_log.final_status = 'no record found'" ;
            }
            elseif ($filter_status == "unable to verify") {
                $where_condition = "view_vendor_master_log.final_status = 'unable to verify'" ;
            }
            elseif ($filter_status == "approved") {
                $where_condition = "view_vendor_master_log.final_status = 'approve'" ;
            }

             $this->db->where($where_condition); 
        }

        if(isset($where['start_date']) &&  $where['start_date'] != '' && isset($where['end_date']) &&  $where['end_date'] != '')
        { 

            $start_date  =  $where['start_date'];
            $end_date  =  $where['end_date'];
             
            $where3 = "DATE_FORMAT(`view_vendor_master_log`.`modified_on`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

            $this->db->where($where3); 

        }

                 
        if(is_array($where) && $where['search']['value'] != "" )
        {
            $this->db->like('candidates_info.cmp_ref_no', $where['search']['value']);
            
            $this->db->or_like('candidates_info.ClientRefNumber', $where['search']['value']);

            $this->db->or_like('education.education_com_ref', $where['search']['value']);

            $this->db->or_like('candidates_info.CandidateName', $where['search']['value']);

            $this->db->or_like('view_vendor_master_log.trasaction_id', $where['search']['value']);
           
        
        }

        if(!empty($where['order']))
        {
            $column_name_index = $where['order'][0]['column'];
            $order_by = $where['order'][0]['dir']; 
           
            $this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
        }
        else
        {
            $this->db->order_by('view_vendor_master_log.modified_on','ASC');
        }
   
        $result = $this->db->get();
    
        return $result->result_array();
    
    }


     public function get_vendor_assign_users($tableName, $return_as_strict_row,$select_array, $where_array=array())
    {

        $this->db->select($select_array);

        $this->db->from($tableName);



        $result_array = $this->db->get()->result_array();   
         
        if($return_as_strict_row)
        {
            if(count($result_array) >0 ) // ensure only one record has been previously inserted
            {
                $result_array = $result_array[0];
            }
        }

    
        return convert_to_single_dimension_array($result_array,'id','fullname');
    }

   
    public function select_vendor_result_log($where_array,$id)
    {

        $this->db->select("education.id as education_id,education.*,(select universityname from university_master where university_master.id = education.university_board) as actual_university_name,(select qualification from qualification_master where qualification_master.id = education.qualification) as actual_qualification_name,view_vendor_master_log.*,(select vendor_name from vendors where vendors.id= education_vendor_log.vendor_id) as vendor_name,(select user_name from user_profile where id = view_vendor_master_log.allocated_by) as allocated_by,(select user_name from user_profile where id = view_vendor_master_log.approval_by) as approval_by,(select verified_by from education_result where education_result.education_id = education.id) as verified_by,(select verifier_designation from education_result where education_result.education_id = education.id) as verifier_designation,(select verifier_contact_details from education_result where education_result.education_id = education.id) as verifier_contact_details,(select id from education_ver_result where education_ver_result.education_id = education.id order by education_ver_result.id desc limit 1) as education_ver_id,candidates_info.CandidateName,candidates_info.ClientRefNumber,CandidatesContactNumber,candidates_info.cmp_ref_no,candidates_info.CandidateName,(select clientname from clients where clients.id = education.clientid limit 1) as clientname,
            (select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,education.iniated_date,candidates_info.caserecddate,education_com_ref");


        $this->db->from('view_vendor_master_log');

        $this->db->join('education_vendor_log','education_vendor_log.id = view_vendor_master_log.case_id');

        $this->db->join('education','education.id = education_vendor_log.case_id');

   

        $this->db->join("candidates_info",'candidates_info.id = education.candsid');

        //$this->db->join('user_profile','user_profile.id = view_vendor_master_log.allocated_by','left');

         // $this->db->join('user_profile','user_profile.id = view_vendor_master_log.approved_by','left');
        $this->db->where($where_array);

        if(!empty($id))
        {
             $this->db->where('view_vendor_master_log.id',$id);
        }

        $result  = $this->db->get();

        return $result->result_array();
    }

    public function get_all_education_by_vendor($where)
    {   

        $this->db->select("education.*,(select universityname from university_master where university_master.id = education.university_board limit 1) as university_name,candidates_info.cmp_ref_no,candidates_info.CandidateName,candidates_info.NameofCandidateFather,status.status_value as verfstatus,status.filter_status as filter_status,ed1.closuredate,(select vendor_name from vendors where vendors.id = education.vendor_id limit 1) as vendor_name,(SELECT v.final_status from view_vendor_master_log v, `education_vendor_log` `ed` where ed.case_id = education.id and v.case_id = ed.id and component = 'eduver' and component_tbl_id = '3' order by v.id desc limit 1) as vendor_status,(SELECT v.trasaction_id  from view_vendor_master_log v, `education_vendor_log` `ed` where ed.case_id = education.id and v.case_id = ed.id and component = 'eduver' and component_tbl_id = '3' order by v.id desc limit 1) as transaction_id,(SELECT v.vendor_remark  from view_vendor_master_log v, `education_vendor_log` `ed` where ed.case_id = education.id and v.case_id = ed.id and component = 'eduver' and component_tbl_id = '3' order by v.id desc limit 1) as vendor_remark,(SELECT GROUP_CONCAT(concat(DATE_FORMAT(education_insuff.insuff_raised_date,'%d-%m-%Y')) SEPARATOR '||') FROM education_insuff where education_insuff.education_id = education.id) as insuff_raised_date,(SELECT GROUP_CONCAT(concat(DATE_FORMAT(education_insuff.insuff_clear_date,'%d-%m-%Y')) SEPARATOR '||') FROM education_insuff where education_insuff.education_id = education.id) as insuff_clear_date,(SELECT GROUP_CONCAT(concat(education_insuff.insuff_raise_remark) SEPARATOR '||') FROM education_insuff where education_insuff.education_id = education.id) as insuff_raise_remark,(SELECT GROUP_CONCAT(concat(education_files.file_name) SEPARATOR '||') FROM education_files where education_files.education_id = education.id) as attachments");

        $this->db->from('education');

        $this->db->join("clients",'clients.id = education.clientid');

        $this->db->join("candidates_info",'candidates_info.id = education.candsid');

        $this->db->join("education_result as ed1",'ed1.education_id = education.id','left');

        $this->db->join("education_result as ed2",'(ed2.education_id  = education.id and ed1.id < ed2.id)','left');

        $this->db->join("education_vendor_log",'education_vendor_log.case_id = education.id');

        $this->db->join("view_vendor_master_log",'view_vendor_master_log.case_id = education_vendor_log.id');

        $this->db->join("status",'status.id = ed1.verfstatus','left');

        $this->db->where('ed2.verfstatus is null');

        if($where)
        {
            $this->db->where($where);
        }

       
        $this->db->order_by('education.id', 'ASC');
        
        $result = $this->db->get();
       
        record_db_error($this->db->last_query());

        return $result->result_array();
    }

    public function get_all_education_by_vendor_closed($where,$where3,$where_condition)
    {   

        $this->db->select("education.*,(select universityname from university_master where university_master.id = education.university_board limit 1) as university_name,candidates_info.cmp_ref_no,candidates_info.CandidateName,candidates_info.NameofCandidateFather,status.status_value as verfstatus,status.filter_status as filter_status,ed1.closuredate,(select vendor_name from vendors where vendors.id = education.vendor_id limit 1) as vendor_name,(SELECT v.final_status from view_vendor_master_log v, `education_vendor_log` `ed` where ed.case_id = education.id and v.case_id = ed.id and component = 'eduver' and component_tbl_id = '3' order by v.id desc limit 1) as vendor_status,(SELECT v.trasaction_id  from view_vendor_master_log v, `education_vendor_log` `ed` where ed.case_id = education.id and v.case_id = ed.id and component = 'eduver' and component_tbl_id = '3' order by v.id desc limit 1) as transaction_id,(SELECT v.vendor_remark  from view_vendor_master_log v, `education_vendor_log` `ed` where ed.case_id = education.id and v.case_id = ed.id and component = 'eduver' and component_tbl_id = '3' order by v.id desc limit 1) as vendor_remark,(SELECT GROUP_CONCAT(concat(DATE_FORMAT(education_insuff.insuff_raised_date,'%d-%m-%Y')) SEPARATOR '||') FROM education_insuff where education_insuff.education_id = education.id) as insuff_raised_date,(SELECT GROUP_CONCAT(concat(DATE_FORMAT(education_insuff.insuff_clear_date,'%d-%m-%Y')) SEPARATOR '||') FROM education_insuff where education_insuff.education_id = education.id) as insuff_clear_date,(SELECT GROUP_CONCAT(concat(education_insuff.insuff_raise_remark) SEPARATOR '||') FROM education_insuff where education_insuff.education_id = education.id) as insuff_raise_remark,(SELECT GROUP_CONCAT(concat(education_files.file_name) SEPARATOR '||') FROM education_files where education_files.education_id = education.id) as attachments");

        $this->db->from('education');

        $this->db->join("clients",'clients.id = education.clientid');

        $this->db->join("candidates_info",'candidates_info.id = education.candsid');

        $this->db->join("education_result as ed1",'ed1.education_id = education.id','left');

        $this->db->join("education_result as ed2",'(ed2.education_id  = education.id and ed1.id < ed2.id)','left');

        $this->db->join("education_vendor_log",'education_vendor_log.case_id = education.id');

        $this->db->join("view_vendor_master_log",'view_vendor_master_log.case_id = education_vendor_log.id');

        $this->db->join("status",'status.id = ed1.verfstatus','left');

        $this->db->where('ed2.verfstatus is null');

        if($where)
        {
            $this->db->where($where);
        }
        
        if($where3 != "")
        {
            $this->db->where($where3);
        }

        if($where_condition != "")
        {
            $this->db->where($where_condition);
        }
       
        $this->db->order_by('education.id', 'ASC');
        
        $result = $this->db->get();
        record_db_error($this->db->last_query());

        return $result->result_array();
    }


  /*	public function upload_vendor_reject($tableName,$updateArray,$where_arry)
	{		

		$this->db->where($where_arry);
	 	$this->db->update($tableName,$updateArray);
		return $this->db->affected_rows();
	}
  
	public function select_address_vendor_log_id($tableName,$where_arry = array())
	{
		$this->db->select("case_id");

		$this->db->from($tableName);


		if($where_arry)
		{
			$this->db->where($where_arry);
		}
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}
    */
   	
}
?>