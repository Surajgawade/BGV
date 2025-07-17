<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Credit_report_model extends CI_Model
{
	function __construct()
    {
		$this->tableName = 'credit_report';

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


	public function update_auto_increament_value($arrdata,$arrwhere = array())
	{
        
		    $this->db->where($arrwhere);

			$result = $this->db->update('credit_report', $arrdata);

			record_db_error($this->db->last_query());

	
			return $result;

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

		protected function filter_where_cond($where_arry)
	{
		$where = array();
		if(isset($where_arry['status']) &&  $where_arry['status'] != '')	
		{
			
			if($where_arry['status'] != 'All')
			{
			$where['credit_report_result.var_filter_status'] = $where_arry['status'];
		    }
		  
		}

		if(isset($where_arry['sub_status']) && $where_arry['sub_status'] != '' && $where_arry['sub_status'] != 0)	
		{
			$where['credit_report_result.verfstatus'] = $where_arry['sub_status'];
		}

		if(isset($where_arry['client_id']) &&  $where_arry['client_id'] != 0)	
		{
			$where['credit_report.clientid'] = $where_arry['client_id'];
		}
        if(isset($where_arry['filter_by_executive']) &&  $where_arry['filter_by_executive'] != 0)	
		{
		    if($where_arry['filter_by_executive'] != "All")
	      	{
            $where['credit_report.has_case_id'] = $where_arry['filter_by_executive'];
	      	}
        } 
         
		return $where;

	}


	public function get_all_credit_report_record_datatable($empver_aary = array(),$where,$columns)
	{
		$this->db->select("candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = credit_report.has_case_id) as user_name,status.status_value,credit_report.credit_report_com_ref,credit_report.mode_of_veri,credit_report_result.verfstatus,credit_report_result.first_qc_approve,credit_report_result.first_qc_updated_on,credit_report_result.first_qu_reject_reason,credit_report.id,credit_report.id as credit_report_id,credit_report.has_assigned_on,credit_report.iniated_date,clients.clientname,(select created_on from credit_report_activity_data where comp_table_id = credit_report.id  order by id desc limit 1) as last_activity_date,credit_report_result.remarks,due_date,tat_status,closuredate,id_number,credit_report_insuff.insuff_raised_date");

		$this->db->from('credit_report');

		$this->db->join("credit_report_result",'credit_report_result.credit_report_id = credit_report.id');
		
		$this->db->join("candidates_info",'candidates_info.id = credit_report.candsid');


	    $this->db->join("credit_report_insuff",'(credit_report_insuff.credit_report_id = credit_report.id AND  credit_report_insuff.status = 1 )','left');
		
		$this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("status",'status.id = credit_report_result.verfstatus');

		$this->db->where($this->filter_where_cond($where));

		 if(isset($where['start_date']) &&  $where['start_date'] != '' && isset($where['end_date']) &&  $where['end_date'] != '')	
		    { 

		     	$start_date  =  $where['start_date'];
	            $end_date  =  $where['end_date'];

	            if($where['status'] == "Closed")
                {
	         
		     	$where3 = "DATE_FORMAT(`credit_report_result`.`closuredate`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}

		     	if($where['status'] == "Insufficiency")
                {
	         
		     	$where3 = "DATE_FORMAT(`credit_report_insuff`.`insuff_raised_date`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}
                
                $this->db->where($where3); 

		    } 


		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like('candidates_info.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidatesContactNumber', $where['search']['value']);

			$this->db->or_like('candidates_info.cmp_ref_no', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);

			$this->db->or_like('credit_report.credit_report_com_ref', $where['search']['value']);

			$this->db->or_like('credit_report.iniated_date', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where['search']['value']);

			
		}

		
		$this->db->limit($where['length'],$where['start']);
		
		
		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 
            
             //$this->db->where('credit_report.vendor_id !=',0);

         
           /* if(!empty($column_name_index))
			{

			  $this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		    }
		    else
		    {

               $order_clause = "(case verfstatus when 12 THEN 0 else 1 end),(case verfstatus when 13 THEN 0 else 1 end),(case verfstatus when 26 THEN 0 else 1 end),(case verfstatus when 11 THEN 0 else 1 end),(case verfstatus when 14 THEN 0 else 1 end)";

		    	$this->db->order_by($order_clause);
		    }*/
			$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		}
		else
		{
			//$order_clause = "(case verfstatus when 12 THEN 0 else 1 end),(case verfstatus when 13 THEN 0 else 1 end),(case verfstatus when 26 THEN 0 else 1 end),(case verfstatus when 11 THEN 0 else 1 end),(case verfstatus when 14 THEN 0 else 1 end)";

		    $this->db->order_by('credit_report.id','desc');
		}    

		$result = $this->db->get();

		//print_r($this->db->last_query());
      
		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_all_credit_report_record_datatable_count($empver_aary = array(),$where,$columns)
	{
		$this->db->select("candidates_info.id as cands_id,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = credit_report.has_case_id) as user_name,status.status_value,credit_report.id as credit_report_id,credit_report.credit_report_com_ref,credit_report_result.verfstatus,credit_report_result.first_qc_approve,credit_report_result.first_qc_updated_on,credit_report_result.first_qu_reject_reason,credit_report.id,credit_report.has_assigned_on,credit_report.iniated_date,clients.clientname,(select created_on from credit_report_activity_data where comp_table_id = credit_report.id  order by id desc limit 1) as last_activity_date,credit_report_insuff.insuff_raised_date");

		$this->db->from('credit_report');

		$this->db->join("credit_report_result",'credit_report_result.credit_report_id = credit_report.id');
		
		$this->db->join("candidates_info",'candidates_info.id = credit_report.candsid');

	    $this->db->join("credit_report_insuff",'(credit_report_insuff.credit_report_id = credit_report.id AND  credit_report_insuff.status = 1 )','left');
		
		$this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("status",'status.id = credit_report_result.verfstatus');

		$this->db->where($this->filter_where_cond($where));

		 if(isset($where['start_date']) &&  $where['start_date'] != '' && isset($where['end_date']) &&  $where['end_date'] != '')	
		    { 

		     	$start_date  =  $where['start_date'];
	            $end_date  =  $where['end_date'];

	            if($where['status'] == "Closed")
                {
	         
		     	$where3 = "DATE_FORMAT(`credit_report_result`.`closuredate`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}

		     	if($where['status'] == "Insufficiency")
                {
	         
		     	$where3 = "DATE_FORMAT(`credit_report_insuff`.`insuff_raised_date`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}
                
                $this->db->where($where3); 

		    } 


		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like('candidates_info.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidatesContactNumber', $where['search']['value']);

			$this->db->or_like('candidates_info.cmp_ref_no', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);

			$this->db->or_like('credit_report.credit_report_com_ref', $where['search']['value']);

			$this->db->or_like('credit_report.iniated_date', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where['search']['value']);

			
		}

				
		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 
            
            // $this->db->where('credit_report.vendor_id !=',0);

         
            /*if(!empty($column_name_index))
			{

			  $this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		    }
		    else
		    {

               $order_clause = "(case verfstatus when 12 THEN 0 else 1 end),(case verfstatus when 13 THEN 0 else 1 end),(case verfstatus when 26 THEN 0 else 1 end),(case verfstatus when 11 THEN 0 else 1 end),(case verfstatus when 14 THEN 0 else 1 end)";

		    	$this->db->order_by($order_clause);
		    }*/
			$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		}
		else
		{
			//$order_clause = "(case verfstatus when 12 THEN 0 else 1 end),(case verfstatus when 13 THEN 0 else 1 end),(case verfstatus when 26 THEN 0 else 1 end),(case verfstatus when 11 THEN 0 else 1 end),(case verfstatus when 14 THEN 0 else 1 end)";

		     $this->db->order_by('credit_report.id','desc');
		}    

		$result = $this->db->get();
   
		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function  get_all_credit_report_record($where_array)
	{
		$this->db->select("candidates_info.id as cands_id,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = credit_report.has_case_id) as executive_name,status.status_value as verfstatus,credit_report.id as credit_report_id,credit_report.clientid,credit_report.has_case_id,credit_report.mode_of_veri,credit_report.build_date, credit_report.credit_report_com_ref,credit_report.doc_submited,credit_report.id_number, credit_report.street_address,credit_report.city,credit_report.pincode,credit_report.state as state_id,credit_report_result.id as credit_report_result_id,credit_report_result.first_qc_approve,credit_report_result.first_qc_updated_on,credit_report_result.first_qu_reject_reason,credit_report_result.mode_of_verification,credit_report_result.remarks,credit_report_result.closuredate,credit_report.credit_report_re_open_date,credit_report.has_assigned_on,credit_report_result.var_filter_status,credit_report.iniated_date,clients.clientname,(select created_on from credit_report_activity_data where comp_table_id = credit_report.id  order by id desc limit 1) as last_activity_date,due_date,tat_status,credit_report.state,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name");

		$this->db->from('credit_report');

		$this->db->join("credit_report_result",'credit_report_result.credit_report_id = credit_report.id');
		
		$this->db->join("candidates_info",'candidates_info.id = credit_report.candsid');
		
		$this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("status",'status.id = credit_report_result.verfstatus');

		$this->db->where($where_array);

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function credit_report_com_ref()
	{
		$result = $this->db->select("SUBSTRING_INDEX(credit_report_com_ref, '-',-1) as A_I")->order_by('id','desc')->limit(1)-> get($this->tableName)->row_array();
		return $result;
	}

	public function uploaded_files($files_arry, $return_insert_ids = FALSE)
	{
		$res =  $this->db->insert_batch('credit_report_files', $files_arry);
		
		record_db_error($this->db->last_query());
		
		return $res;
	}

	 public function get_credit_report_details_first_qc($where_arry = array())
	{
		$this->db->select("credit_report.*,credit_report_result.*,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.CandidateName,(SELECT status_value FROM status WHERE status.id = credit_report_result.verfstatus) verfstatus_name,(select clientname from clients where clients.id = credit_report_result.clientid limit 1) as clientname,(select user_name from user_profile where user_profile.id = credit_report.has_case_id) as executive_name,due_date,tat_status,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,credit_report.iniated_date,candidates_info.caserecddate,credit_report_com_ref,(SELECT GROUP_CONCAT(concat(credit_report.clientid,'/',file_name) ORDER BY serialno ASC SEPARATOR '||') FROM credit_report_files where credit_report_files.credit_report_id = credit_report_result.id and credit_report_files.status = 1 and credit_report_files.type= 1) as add_attachments");

		$this->db->from('credit_report');

		$this->db->join("credit_report_result",'credit_report_result.credit_report_id = credit_report.id');
		
		$this->db->join("candidates_info",'candidates_info.id = credit_report.candsid');

		if($where_arry)
		{
			$this->db->where($where_arry);
		}
		
		$result = $this->db->get();
		record_db_error($this->db->last_query());

		return $result->result_array();
	}


	public function get_all_credit_report_by_client($clientid,$filter_by_status,$from_date,$to_date)
	{	

		$this->db->select("credit_report.*,clients.clientname,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.DateofBirth,candidates_info.CandidateName,candidates_info.NameofCandidateFather,status.report_status as verfstatus,status.filter_status as filter_status,(select concat(`user_profile`.`firstname`,' ',`user_profile`.`lastname`) from user_profile where user_profile.id = credit_report.has_case_id limit 1) as executive_name,(select vendor_name from vendors where vendors.id = credit_report.vendor_id limit 1) as vendor_name,(SELECT v.final_status from view_vendor_master_log v, `credit_report_vendor_log` `crv` where crv.case_id = credit_report.id and v.case_id = crv.id and component = 'cbrver' and component_tbl_id = '10' order by v.id desc limit 1) as vendor_status,(SELECT v.trasaction_id  from view_vendor_master_log v, `credit_report_vendor_log` `crv` where crv.case_id = credit_report.id and v.case_id = crv.id and component = 'cbrver' and component_tbl_id = '10' order by v.id desc limit 1) as transaction_id,crr1.closuredate,(SELECT GROUP_CONCAT(concat(DATE_FORMAT(credit_report_insuff.insuff_raised_date,'%d-%m-%Y')) SEPARATOR '||') FROM credit_report_insuff where credit_report_insuff.credit_report_id  = credit_report.id) as insuff_raised_date,(SELECT GROUP_CONCAT(concat(DATE_FORMAT(credit_report_insuff.insuff_clear_date,'%d-%m-%Y')) SEPARATOR '||') FROM credit_report_insuff where credit_report_insuff.credit_report_id  = credit_report.id) as insuff_clear_date,(SELECT GROUP_CONCAT(concat(credit_report_insuff.insuff_raise_remark) SEPARATOR '||') FROM credit_report_insuff where credit_report_insuff.credit_report_id  = credit_report.id) as insuff_raise_remark");

		$this->db->from('credit_report');

		$this->db->join("clients",'clients.id = credit_report.clientid');

		$this->db->join("candidates_info",'candidates_info.id = credit_report.candsid');

		$this->db->join("credit_report_result as crr1",'crr1.credit_report_id = credit_report.id','left');

		$this->db->join("credit_report_result as crr2",'(crr2.credit_report_id = credit_report.id and crr1.id < crr2.id)','left');

		$this->db->join("status",'status.id = crr1.verfstatus','left');

		$this->db->where('crr2.verfstatus is null');

		if($clientid)
		{
			$this->db->where('credit_report.clientid',$clientid);
		}
		if($from_date && $to_date)
		{

			$where3 = "DATE_FORMAT(`crr1`.`closuredate`,'%Y-%m-%d') BETWEEN '$from_date' AND '$to_date'";
                
            $this->db->where($where3); 
			
		}

		if($filter_by_status)
		{
			if($filter_by_status == "WIP")
			{
			$this->db->where('(crr1.var_filter_status = "wip" or  crr1.var_filter_status = "WIP")');
		    }
		    if($filter_by_status == "Insufficiency")
			{
			$this->db->where('(crr1.var_filter_status = "insufficiency" or  crr1.var_filter_status = "Insufficiency")');
		    }
		    if($filter_by_status == "Closed")
			{
			$this->db->where('(crr1.var_filter_status = "closed" or  crr1.var_filter_status = "Closed")');
		    }
		}

		$this->db->order_by('credit_report.id', 'ASC');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function save_first_qc_result($files_arry,$arrwhere )
	{

         if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update("credit_report_result", $files_arry);
         
    
			record_db_error($this->db->last_query());
             
			return $result;
	    }
		
	}


	public function delete_uploaded_file($where = array())
	{	
		$this->db->where_in('id',$where);

		$this->db->set('status', STATUS_DELETED);

		$result = $this->db->update('credit_report_files', array('status' => STATUS_DELETED));

		record_db_error($this->db->last_query());

		return $result;
	}

	public function add_uploaded_file($where = array())
	{	
		$this->db->where_in('id',$where);

		$this->db->set('status', STATUS_ACTIVE);

		$result = $this->db->update('credit_report_files', array('status' => STATUS_ACTIVE));

		record_db_error($this->db->last_query());

		return $result;
	}

	public function vendor_delete_uploaded_file($where = array())
	{	
		$this->db->where_in('id',$where);

		$this->db->set('status', STATUS_DELETED);

		$result = $this->db->update('view_vendor_master_log_file', array('status' => STATUS_DELETED));

		record_db_error($this->db->last_query());

		return $result;
	}

	public function vendor_add_uploaded_file($where = array())
	{	
		$this->db->where_in('id',$where);

		$this->db->set('status', STATUS_ACTIVE);

		$result = $this->db->update('view_vendor_master_log_file', array('status' => STATUS_ACTIVE));

		record_db_error($this->db->last_query());

		return $result;
	}

	public function select_file($select_array,$where_array)
	{
		$this->db->select($select_array);

		$this->db->from('credit_report_files');

		$this->db->where($where_array);

		$this->db->order_by('id', 'asc');
		
		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function select_file_vendor($select_array,$where_array,$where_array_id)
	{
		$this->db->select($select_array);

		$this->db->from('view_vendor_master_log_file');

		$this->db->join('view_vendor_master_log','view_vendor_master_log.id = view_vendor_master_log_file.view_venor_master_log_id');

        $this->db->join('credit_report_vendor_log','credit_report_vendor_log.id = view_vendor_master_log.case_id');

        $this->db->join("credit_report",'credit_report.id = credit_report_vendor_log.case_id');

		$this->db->where($where_array);

		$this->db->where('credit_report.id',$where_array_id);
		
		$this->db->order_by('view_vendor_master_log_file.serialno','asc');

		
		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

    public function select_file_from_vendor($select_array,$where_array)
	{
		$this->db->select($select_array);

		$this->db->from('view_vendor_master_log_file');

		$this->db->where($where_array);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

    public function get_credit_report_uploded_files($where_array)
	{
		$this->db->select('*');

		$this->db->from('credit_report_files');

		$this->db->where($where_array);

		$this->db->order_by('serialno','asc');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();	
	}

	public function select_reinitiated_date($where_array)
	{
		
	    $this->db->select('credit_report.*');

		$this->db->from('credit_report');

		$this->db->where($where_array);
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	    
	}


	public function save_update_initiated_date($arrdata,$where_array)
	{
		if(!empty($where_array))
	    {
			$this->db->where($where_array);

			$result = $this->db->update('credit_report', $arrdata);
			
			record_db_error($this->db->last_query());
			
			return $result;
	    }
	     
	}
   
	public function save_update_initiated_date_credit_report($arrdata,$where_array)
	{
		if(!empty($where_array))
	    {
			$this->db->where($where_array);

			$result = $this->db->update('credit_report_result', $arrdata);
			
			record_db_error($this->db->last_query());
			
			return $result;
	    }
	     
	}

    public function initiated_date_credit_report_activity_data($arrdata)
	{
        
		   $this->db->insert('credit_report_activity_data', $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	}



	  public function get_date_for_update($where_array)
    {

        $this->db->select("due_date,tat_status");



        $this->db->from('credit_report');
   

     
        $this->db->where($where_array);

        $result  = $this->db->get();
      
        return $result->result_array();
    }


     public function update_due_date($files_arry,$arrwhere )
	{

         if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update("credit_report", $files_arry);
         
    
			record_db_error($this->db->last_query());
             
			return $result;
	    }
		
	}

	public function upload_file_update($updateArray)
	{
		$this->db->update_batch('credit_report_files',$updateArray, 'id');
		return true; 
	}

	public function select_insuff($where_array)
	{
		$this->db->select('*')->from('credit_report_insuff');

		$this->db->where($where_array);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function save_update_insuff($arrdata,$arrwhere = array())
	{
		if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update('credit_report_insuff', $arrdata);
			
			record_db_error($this->db->last_query());
			
			return $result;
	    }
	    else
	    {
			$this->db->insert('credit_report_insuff', $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	    }
	}

	public function select_insuff_join($where_array)
	{
		$this->db->select('credit_report_insuff.*,(select user_name from user_profile where id =  credit_report_insuff.created_by limit 1) as insuff_raised_by,(select user_name from user_profile where id = credit_report_insuff.insuff_cleared_by limit 1) as insuff_cleared_by');

		$this->db->from('credit_report_insuff');

		$this->db->where($where_array);
		
		$this->db->where('credit_report_insuff.status !=',3);

		$this->db->order_by('credit_report_insuff.id','asc');

		return $this->db->get()->result_array();
	}
	
	public function save_update_ver_result($arrdata,$arrwhere = array())
	{
		if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update('credit_report_result', $arrdata);

			record_db_error($this->db->last_query());
			
			return $result;
	    }
	    else
	    {
			$this->db->insert('credit_report_result', $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	    }
	}


	public function save_update_result_credit($arrdata,$arrwhere = array())
	{	
		if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update('credit_report_ver_result', $arrdata);

			record_db_error($this->db->last_query());
			
			return $result;
	    }
	    else
	    {
			$this->db->insert('credit_report_ver_result', $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
		}	
	}

	public function export_sql($filter) { 
	
	$sql = "SELECT (select clientname from clients where clients.id = candidates_info.clientid limit 1) as
		client_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name, ClientRefNumber,cmp_ref_no,CandidateName,DATE_FORMAT(caserecddate,'%d-%m-%Y') as caserecddate, (select status_value from status where status.id = credit_report_result.verfstatus limit 1) as verfstatus,credit_report_com_ref,(select user_name from user_profile where user_profile.id = credit_report.has_case_id) as executive_name, doc_submited,id_number,street_address,city,pincode,state,mode_of_verification,
			credit_report_result.remarks,DATE_FORMAT(iniated_date,'%d-%m-%Y') as iniated_date,DATE_FORMAT(due_date,'%d-%m-%Y') as due_date,tat_status,first_qc_updated_on,DATE_FORMAT(closuredate,'%d-%m-%Y') as closuredate,first_qc_approve,(select created_on from credit_report_activity_data where comp_table_id = credit_report.id order by id desc limit 1) as last_activity_date,(select count(file_name) from credit_report_files where credit_report_files.credit_report_id = credit_report_result.id and type = 1) as file_name
		FROM credit_report 
		INNER JOIN candidates_info ON candidates_info.id = credit_report.candsid 
		INNER JOIN credit_report_result ON credit_report_result.credit_report_id = credit_report.id";
		
		$query = $this->db->query($sql);

		return $query->result_array();
	}

	public function dashboard_sql($filter) { 
	
	$sql = "SELECT (select clientname from clients where clients.id = candidates_info.clientid limit 1) as
		client_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name, ClientRefNumber,cmp_ref_no,CandidateName,DATE_FORMAT(caserecddate,'%d-%m-%Y') as caserecddate, (select status_value from status where status.id = credit_report_result.verfstatus limit 1) as verfstatus,credit_report_com_ref,(select user_name from user_profile where user_profile.id = credit_report.has_case_id) as executive_name,(select vendor_name from vendors where vendors.id = credit_report.vendor_id) as vendor_name, doc_submited,id_number,mode_of_veri,DATE_FORMAT(iniated_date,'%d-%m-%Y') as iniated_date,DATE_FORMAT(due_date,'%d-%m-%Y') as due_date,tat_status,DATE_FORMAT(closuredate,'%d-%m-%Y') as closuredate,(select created_on from credit_report_activity_data where comp_table_id = credit_report.id order by id desc limit 1) as last_activity_date
		FROM credit_report 
		INNER JOIN candidates_info ON candidates_info.id = credit_report.candsid 
		INNER JOIN credit_report_result ON credit_report_result.credit_report_id = credit_report.id ".$filter." ";
		
		$query = $this->db->query($sql);

		return $query->result_array();
	}

	public function get_assign_users($tableName, $return_as_strict_row,$select_array, $where_array=array())
	{

		$this->db->select($select_array);

		$this->db->from($tableName);

		$this->db->join('roles','roles.id = user_profile.tbl_roles_id');

		$this->db->join('roles_permissions','roles_permissions.tbl_roles_id = roles.id');

        $this->db->where('roles_permissions.access_credit_list_assign = 1' ); 

        $this->db->where($where_array); 
        
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
	
	public function get_assign_users_id($tableName, $return_as_strict_row,$select_array, $where_array=array())
	{

		$this->db->select($select_array);

		$this->db->from($tableName);

		$this->db->join('roles','roles.id = user_profile.tbl_roles_id');

		$this->db->join('roles_permissions','roles_permissions.tbl_roles_id = roles.id');

        $this->db->where('roles_permissions.access_credit_list_assign = 1' ); 
      
        $this->db->where($where_array); 
        
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

	public function get_mode_of_verification($where)
	{
		$this->db->select('mode_of_verification ');

		$this->db->from('clients_details');
		
		$this->db->where($where);

        $this->db->limit(1);  

		$result  = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function upload_vendor_assign($tableName,$updateArray,$where_arry)
	{		

		$this->db->where($where_arry);
	 	$this->db->update($tableName,$updateArray);
		return $this->db->affected_rows();
	}

	public function update_status($tableName,$updateArray,$where_arry)
	{	
	  
		$this->db->where($where_arry);
	 	$this->db->update($tableName,$updateArray);
		return $this->db->affected_rows();
	}


	public function select_result_log($where_array)
	{
		$this->db->select('credit_report_ver_result.*,(select created_by from activity_log where id =  credit_report_ver_result.activity_log_id) as created_by1,(select user_name from user_profile where id = created_by1 ) as created_by,(select activity_mode from activity_log where id =  credit_report_ver_result.activity_log_id) as activity_mode ,(select activity_status from activity_log where id =  credit_report_ver_result.activity_log_id) as activity_status ,(select activity_type from activity_log where id =  credit_report_ver_result.activity_log_id) as activity_type,(select action from activity_log where id =  credit_report_ver_result.activity_log_id) as activity_action,(select GROUP_CONCAT(credit_report_files.file_name) from credit_report_files where `credit_report_files`.`credit_report_id` = `credit_report_ver_result`.`credit_report_id` AND `status` = 1 AND `type` = 1)  as file_names,(select GROUP_CONCAT(credit_report_files.id) from credit_report_files where `credit_report_files`.`credit_report_id` = `credit_report_ver_result`.`credit_report_id` AND `status` = 1 AND `type` = 1) as file_ids');

		$this->db->from('credit_report_ver_result');


		$this->db->where($where_array);
		$this->db->order_by('credit_report_ver_result.id','desc');
		//$this->db->where('addrverres_result.status !=',3);

		return $this->db->get()->result_array();

		
	}

	public function select_result_log1($where_array)
	{
		$this->db->select('credit_report_ver_result.*,(select activity_mode from activity_log where id =  credit_report_ver_result.activity_log_id) as activity_mode ,(select activity_status from activity_log where id =  credit_report_ver_result.activity_log_id) as activity_status ,(select activity_type from activity_log where id =  credit_report_ver_result.activity_log_id) as activity_type,(select action from activity_log where id =  credit_report_ver_result.activity_log_id) as activity_action');

		$this->db->from('credit_report_ver_result');

		//$this->db->join("education",'education.id = drug_narcotis_ver_result.education_id');

		//$this->db->join("addrverres",'addrverres.addrverid = addrver.id');

		$this->db->where($where_array);
		$this->db->order_by('id','desc');
		//$this->db->where('addrverres.status !=',3);

		return $this->db->get()->result_array();

		
	}

    public function vendor_logs($where_array,$id)
    {
        $this->db->select("view_vendor_master_log.*,(select vendor_name from vendors where vendors.id= credit_report_vendor_log.vendor_id) as vendor_name,credit_report_vendor_log.case_id,(select user_name from user_profile where id = view_vendor_master_log.allocated_by) as allocated_by,(select user_name from user_profile where id = view_vendor_master_log.approval_by) as approval_by");

        $this->db->from('view_vendor_master_log');
        $this->db->join('credit_report_vendor_log','credit_report_vendor_log.id = view_vendor_master_log.case_id');

        //$this->db->join('user_profile','user_profile.id = view_vendor_master_log.allocated_by','left');

         // $this->db->join('user_profile','user_profile.id = view_vendor_master_log.approved_by','left');
        $this->db->where($where_array);

        if(!empty($id))
        {
        	 $this->db->where('credit_report_vendor_log.case_id',$id);
        }

        $this->db->where_in('view_vendor_master_log.status',array('0','1','2','3','5'));

        $this->db->order_by("view_vendor_master_log.id", "desc");

        $result  = $this->db->get();
       // print_r($this->db->last_query());
        return $result->result_array();
    }


    public function select_vendor_result_log($where_array,$id)
    {

        $this->db->select("view_vendor_master_log.*,(select vendor_name from vendors where vendors.id= credit_report_vendor_log.vendor_id) as vendor_name,(select user_name from user_profile where id = view_vendor_master_log.allocated_by) as allocated_by,(select user_name from user_profile where id = view_vendor_master_log.approval_by) as approval_by,credit_report.*,credit_report.id as credit_report_id,candidates_info.id as CandidateID,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,(select clientname from clients where clients.id = credit_report.clientid limit 1) as clientname,candidates_info.NameofCandidateFather,candidates_info.DateofBirth,
			(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,credit_report.iniated_date,candidates_info.caserecddate,credit_report_com_ref,candidates_info.entity as entity_id,candidates_info.package as package_id,(select id from credit_report_result where credit_report_result.credit_report_id = credit_report.id) as credit_report_result_id,view_vendor_master_log.id as view_vendor_master_log_id");



        $this->db->from('view_vendor_master_log');
        $this->db->join('credit_report_vendor_log','credit_report_vendor_log.id = view_vendor_master_log.case_id');

        $this->db->join('credit_report','credit_report.id = credit_report_vendor_log.case_id');

		$this->db->join("candidates_info",'candidates_info.id = credit_report.candsid');

        $this->db->where($where_array);

        if(!empty($id))
        {
        	 $this->db->where('view_vendor_master_log.id',$id);
        }

        $result  = $this->db->get();
       // print_r($this->db->last_query());
        return $result->result_array();
    }


    public function select_vendor_result_log_cost($where_array,$id)
    {

        $this->db->select("view_vendor_master_log.*");



        $this->db->from('view_vendor_master_log');
   

        
        $this->db->where($where_array);

        if(!empty($id))
        {
        	 $this->db->where('view_vendor_master_log.id',$id);
        }

        $result  = $this->db->get();
        
        return $result->result_array();
    }

    public function save_vendor_details($tablename,$arrdata,$arrwhere = array())
	{
	    if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update($tablename,$arrdata);
       
			record_db_error($this->db->last_query());
             
			return $result;
	    }
	 
	}

	public function save_vendor_details_cancel($arrdata,$arrwhere = array())
	{
	    if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update("view_vendor_master_log", $arrdata);
       
			record_db_error($this->db->last_query());
             
			return $result;
	    }
	    
	}
    

     public function update_credit_report_vendor_log($tablename,$arrdata,$arrwhere = array())
	{
	    if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update($tablename, $arrdata);
       
			record_db_error($this->db->last_query());
             
			return $result;
	    }
	    
	}

	 public function get_user_id($where_array)
	{
		$this->db->select('id');

		$this->db->from('user_profile');

		$this->db->where($where_array);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->row('id');
	}

	public function credit_report_case_list($where_array = array())
    {
        $this->db->select("view_vendor_master_log.*,CandidateName,clients.clientname,credit_report.id as credit_report_id,credit_report.credit_report_com_ref,credit_report.id_number,credit_report.street_address,credit_report.vendor_list_mode,credit_report.city,credit_report.state,credit_report.pincode,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select vendor_name from vendors where vendors.id = credit_report.vendor_id) as vendor_name,(select user_name from vendor_executive_login where vendor_executive_login.id = view_vendor_master_log.has_case_id limit 1) as vendor_executive_id,candidates_info.ClientRefNumber");

        $this->db->from('view_vendor_master_log');
     
        $this->db->join('credit_report_vendor_log','credit_report_vendor_log.id = view_vendor_master_log.case_id');

        $this->db->join('credit_report','credit_report.id = credit_report_vendor_log.case_id');

        $this->db->join('candidates_info','candidates_info.id = credit_report.candsid');
       
        $this->db->join("clients",'clients.id = credit_report.clientid');


        $this->db->where($where_array);
   
        $result = $this->db->get();
    
       return $result->result_array();

    }

    public function credit_report_case_list_insuff($where_array = array())
    {

    	$admin_status = "(credit_report_result.verfstatus = 1 or credit_report_result.verfstatus = 11 or credit_report_result.verfstatus = 12 or credit_report_result.verfstatus = 13 or credit_report_result.verfstatus = 14 or credit_report_result.verfstatus = 16 or credit_report_result.verfstatus = 23 or credit_report_result.verfstatus = 26 )";

        $this->db->select("view_vendor_master_log.*,CandidateName,clients.clientname,credit_report.id as credit_report_id,credit_report.credit_report_com_ref,credit_report.doc_submited,credit_report.id_number,credit_report.vendor_list_mode,(select vendor_name from vendors where vendors.id = credit_report.vendor_id) as vendor_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,candidates_info.ClientRefNumber");
        
        $this->db->from('view_vendor_master_log');

        $this->db->join('credit_report_vendor_log','credit_report_vendor_log.id = view_vendor_master_log.case_id');

        $this->db->join('credit_report','credit_report.id = credit_report_vendor_log.case_id');

        $this->db->join('credit_report_result','credit_report_result.credit_report_id = credit_report.id');

        $this->db->join('status','status.id = credit_report_result.verfstatus');

        $this->db->join('candidates_info','candidates_info.id = credit_report.candsid');
       
        $this->db->join("clients",'clients.id = credit_report.clientid');


        $this->db->where($where_array);

        $this->db->where($admin_status);
   
        $result = $this->db->get();
    
       return $result->result_array();
    
    }

     public function update_closure_approval($table_name,$arrdata )
	{
			$this->db->insert($table_name, $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	    
	}

	 public function update_closure_approval_details($table_name,$arrdata,$where_array )
	{
     
	 if(!empty($where_array))
	    {
			$this->db->where($where_array);

			$result = $this->db->update($table_name, $arrdata);
         
    
			record_db_error($this->db->last_query());
             
			return $result;
	    }
	}

	 public function get_client_id($where_array )
	{
     
	  $this->db->select("view_vendor_master_log.id,view_vendor_master_log.case_id as  vendor_master_log_case, credit_report_vendor_log.id as credit_report_vendor_log_id,credit_report_vendor_log.case_id as credit_report_vendor_log_case,credit_report.id as credit_report_id , credit_report.clientid as client_id" );
        $this->db->from('view_vendor_master_log');
       // $this->db->join('addrver','addrver.id = view_vendor_master_log.component_tbl_id');
        $this->db->join('credit_report_vendor_log','credit_report_vendor_log.id = view_vendor_master_log.case_id');
        $this->db->join('credit_report','credit_report.id = credit_report_vendor_log.case_id');
        $this->db->join("clients",'clients.id = credit_report.clientid');

        $this->db->where($where_array);
        
        $result = $this->db->get();
  
       return $result->result_array();
	}


	public function check_vendor_status_closed_or_not($where_array)
	{
     
	  $this->db->select("view_vendor_master_log.id,view_vendor_master_log.final_status,view_vendor_master_log.case_id as  vendor_master_log_case, credit_report_vendor_log.id as credit_report_vendor_log_id,credit_report_vendor_log.case_id as credit_report_vendor_log_case,credit_report.id as credit_report_id" );

        $this->db->from('view_vendor_master_log');
      
        $this->db->join('credit_report_vendor_log','credit_report_vendor_log.id = view_vendor_master_log.case_id');

        $this->db->join('credit_report','credit_report.id = credit_report_vendor_log.case_id');
    
        $this->db->where($where_array);

        $this->db->where("(view_vendor_master_log.final_status = 'wip' or view_vendor_master_log.final_status = 'insufficiency')");
        
        $result = $this->db->get();
 
       return $result->result_array();
	}

	public function check_vendor_status_insufficiency($where_array)
	{
     
	  $this->db->select("view_vendor_master_log.id,view_vendor_master_log.final_status,view_vendor_master_log.case_id as  vendor_master_log_case,credit_report_vendor_log.id as credit_report_vendor_log_id,credit_report_vendor_log.case_id as  credit_report_vendor_log_case,credit_report.id as credit_report_id" );

        $this->db->from('view_vendor_master_log');
      
        $this->db->join('credit_report_vendor_log','credit_report_vendor_log.id = view_vendor_master_log.case_id');

        $this->db->join('credit_report','credit_report.id = credit_report_vendor_log.case_id');
    
        $this->db->where($where_array);

        $this->db->where('credit_report.vendor_id !=', 0);

        $result = $this->db->get();
 
       return $result->result_array();
	}

    public function reject_cost_vendor($arrdata,$arrwhere = array())
	{
	    if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update("view_vendor_master_log", $arrdata);
       
			record_db_error($this->db->last_query());
             
			return $result;
	    }
	    
	}  

	public function select_candidate_from_file($id)
	{
		$this->db->select('file_name');

		$this->db->from('credit_report_files');

		$this->db->join('credit_report','credit_report.id = credit_report_files.credit_report_id');
       
        $this->db->join('candidates_info','candidates_info.id = credit_report.candsid');

		$this->db->where('candidates_info.id',$id);

		
		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function select_client_list_view_credit_report($tableName, $return_as_strict_row,$select_array, $where1=array())
	{
		$this->db->select($select_array);
       
		$this->db->from($tableName);

		$this->db->join("credit_report",'credit_report.clientid = clients.id');

		$this->db->join("user_profile",'user_profile.id = credit_report.has_case_id','left');

		$this->db->join("candidates_info",'candidates_info.id = credit_report.candsid');

	
		$this->db->join("credit_report_result",'credit_report_result.credit_report_id = credit_report.id','left');


		$this->db->join("credit_report_insuff",'(credit_report_insuff.credit_report_id = credit_report.id AND  credit_report_insuff.status = 1 )','left');

		$this->db->join("status",'status.id = credit_report_result.verfstatus','left');

        $this->db->where($this->filter_where_cond($where1)); 

        $this->db->where('clients.status',1); 


            if(isset($where1['start_date']) &&  $where1['start_date'] != '' && isset($where1['end_date']) &&  $where1['end_date'] != '')	
		    { 

		     	$start_date  =  $where1['start_date'];
	            $end_date  =  $where1['end_date'];

	            if($where1['status'] == "Closed")
                {
	         
		     	$where3 = "DATE_FORMAT(`credit_report_result`.`closuredate`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}

		     	if($where1['status'] == "Insufficiency")
                {
	         
		     	$where3 = "DATE_FORMAT(`credit_report_insuff`.`insuff_raised_date`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}
                
                $this->db->where($where3); 

		    } 
   
		$result = $this->db->get();

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

	public function save_update_credit_report_files($arrdata,$arrwhere = array())
	{
		if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update('credit_report_files', $arrdata);

			record_db_error($this->db->last_query());

			return $result;
	    }
	    else
	    {
			$this->db->insert('credit_report_files', $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	    }
	}

	public function get_vendor_id($clientid)
	{ 
		$sql = "SELECT `id` FROM `vendors` WHERE FIND_IN_SET(".$clientid.",vendors.credit_client)";
		
		$query = $this->db->query($sql);

		$result_array =  $query->result_array();
		
		return $result_array;
		
	}

	public function save_vendor_log($arrdata,$arrwhere = array())
	{
	    if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update('credit_report_vendor_log', $arrdata);
       
			record_db_error($this->db->last_query());
             
			return $result;
	    }
	    else
	    {
			$this->db->insert('credit_report_vendor_log', $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	    }
	}

	public  function get_reporting_manager_id($clientid)
    {
    	
        $this->db->select('clientmgr');

		$this->db->from('clients');

		$this->db->where('clients.id',$clientid);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
    
    }
    public function check_credit_exists_in_candidate($where_array)	
    {
    	$this->db->select('id');

		$this->db->from('credit_report');

		$this->db->where($where_array);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
    }

    public function get_credit_report_details_for_approval($case_id)
    {
    	$this->db->select('credit_report_vendor_log.id as credit_report_vendor_log_id,clients.clientname,credit_report.credit_report_com_ref,candidates_info.CandidateName,candidates_info.DateofBirth,candidates_info.NameofCandidateFather,credit_report.vendor_id,credit_report.id_number,credit_report.street_address');

		$this->db->from('credit_report_vendor_log');

		$this->db->join('credit_report','credit_report.id = credit_report_vendor_log.case_id ');

        $this->db->join('candidates_info','candidates_info.id = credit_report.candsid');

        $this->db->join('clients','clients.id = credit_report.clientid');

		$this->db->where('credit_report_vendor_log.id',$case_id);

		$result  = $this->db->get();
     
		record_db_error($this->db->last_query());
		
		return $result->result_array();
    }

    public function vendor_email_id($where_array)
    {
    	$this->db->select('vendors.*');

		$this->db->from('vendors');
  
        if($where_array)
        {

		  $this->db->where($where_array);

	    }

		$result  = $this->db->get();
     
		record_db_error($this->db->last_query());
		
		return $result->result_array();
    }

    public function select_credit_approve_list($where_array = array())
	{
		$this->db->select('credit_report_vendor_log.id');

		$this->db->from('credit_report_vendor_log');

		$this->db->join('credit_report','credit_report.id = credit_report_vendor_log.case_id');

		$this->db->join("credit_report_result",'credit_report_result.credit_report_id = credit_report.id','left');

		$this->db->join("candidates_info",'candidates_info.id = credit_report.candsid','left');

		$this->db->where($where_array);

        $this->db->where('(credit_report_result.var_filter_status = "wip" or credit_report_result.var_filter_status = "WIP")');

        $this->db->where("credit_report_vendor_log.created_on <= DATE_SUB(NOW(), INTERVAL 30 MINUTE)", NULL, FALSE);

		$this->db->order_by('credit_report_vendor_log.id', 'desc');

		$this->db->order_by('id', 'desc');

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	  
		
	  	 	
}
?>
