<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reference_verificatiion_model extends CI_Model
{
	function __construct()
    {
		$this->tableName = 'reference';

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

			$result = $this->db->update('reference', $arrdata);

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

	public function reference_verfstatus_update($arrdata,$arrwhere = array())
	{
		if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update('reference_result', $arrdata);

			record_db_error($this->db->last_query());

			return $result;
	    }
	    
	}

	public function save_mail_activity_data($arrdata)
	{
	
		
	    $this->db->insert('reference_activity_data', $arrdata);

		record_db_error($this->db->last_query());

		return $this->db->insert_id();
		
	}
	 public function referece_mail_details($arrdata)
	{
		
	    $this->db->insert('ref_mail_details', $arrdata);

		record_db_error($this->db->last_query());

		return $this->db->insert_id();
		
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
			$where['reference_result.var_filter_status'] = $where_arry['status'];
		    }


		}

		if(isset($where_arry['sub_status']) && $where_arry['sub_status'] != '' && $where_arry['sub_status'] != 0)	
		{
			$where['reference_result.verfstatus'] = $where_arry['sub_status'];
		}

		if(isset($where_arry['client_id']) &&  $where_arry['client_id'] != 0)	
		{
			$where['reference.clientid'] = $where_arry['client_id'];
		}
		if(isset($where_arry['filter_by_executive']) &&  $where_arry['filter_by_executive'] != 0)	
		{

		    if($where_arry['filter_by_executive'] != "All")
	      	{
            $where['reference.has_case_id'] = $where_arry['filter_by_executive'];
	      	}

	    } 	

		return $where;

	}


	public function get_all_reference_record_datatable($empver_aary = array(),$where,$columns)
	{
		$this->db->select("candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = reference.has_case_id) as executive_name,status.status_value,reference.id,reference.reference_com_ref,reference_result.verfstatus,reference_result.first_qc_approve,reference.mode_of_veri,reference_result.first_qc_updated_on,reference_result.first_qu_reject_reason,reference.id,reference.has_assigned_on,reference.iniated_date,clients.clientname,due_date,tat_status,reference_result.closuredate,reference.name_of_reference,(select created_on from reference_activity_data where comp_table_id = reference.id order by id desc limit 1) as last_activity_date,(select vendor_name from vendors where vendors.id = reference.vendor_id) as vendor_name,reference_insuff.insuff_raised_date");

		$this->db->from('reference');

		$this->db->join("reference_result",'reference_result.reference_id = reference.id');
		
		$this->db->join("candidates_info",'candidates_info.id = reference.candsid');
		
		$this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("reference_insuff",'(reference_insuff.reference_id = reference.id AND  reference_insuff.status = 1 )','left');


		$this->db->join("status",'status.id = reference_result.verfstatus');

	    $this->db->where($this->filter_where_cond($where));


		 if(isset($where['start_date']) &&  $where['start_date'] != '' && isset($where['end_date']) &&  $where['end_date'] != '')	
		    { 

		     	$start_date  =  $where['start_date'];
	            $end_date  =  $where['end_date'];

	            if($where['status'] == "Closed")
                {
	         
		     	$where3 = "DATE_FORMAT(`reference_result`.`closuredate`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}

		     	if($where['status'] == "Insufficiency")
                {
	         
		     	$where3 = "DATE_FORMAT(`reference_insuff`.`insuff_raised_date`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}
                
                $this->db->where($where3); 

		    } 


		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like('candidates_info.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidatesContactNumber', $where['search']['value']);

	        $this->db->or_like('candidates_info.cmp_ref_no', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);

			$this->db->or_like('reference.reference_com_ref', $where['search']['value']);

			$this->db->or_like('reference.iniated_date', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where['search']['value']);

		}
		
		$this->db->limit($where['length'],$where['start']);
		

		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 
            
         
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

		     $this->db->order_by('reference.id','desc');
		}    

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	 public function get_reference_details_first_qc($where_arry = array())
	{
		$this->db->select("reference.*,reference_result.*,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.CandidateName,(SELECT status_value FROM status WHERE status.id = reference_result.verfstatus) verfstatus_name,(select clientname from clients where clients.id = reference_result.clientid limit 1) as clientname,(select user_name from user_profile where user_profile.id = reference.has_case_id) as executive_name,due_date,tat_status,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,reference.iniated_date,candidates_info.caserecddate,reference_com_ref,(SELECT GROUP_CONCAT(concat(reference.clientid,'/',file_name) ORDER BY serialno ASC SEPARATOR '||') FROM reference_files where reference_files.reference_id = reference_result.id and reference_files.status = 1 and reference_files.type= 1) as add_attachments");

		$this->db->from('reference');

		$this->db->join("reference_result",'reference_result.reference_id = reference.id');
		
               //  $this->db->order_by('addrverres.id','DESC');

       // $this->db->limit('1');
		$this->db->join("candidates_info",'candidates_info.id = reference.candsid');

		//$this->db->join("addrver_files",'(addrver_files.addrver_id = addrver.id AND addrver_files.status = 1 AND addrver_files.type = 0 )','left');
       

		if($where_arry)
		{
			$this->db->where($where_arry);
		}
		
		$result = $this->db->get();
		record_db_error($this->db->last_query());

		return $result->result_array();
	}


	public function get_all_reference_record_datatable_count($empver_aary = array(),$where,$columns)
	{
		$this->db->select("candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = reference.has_case_id) as executive_name,status.status_value,reference.reference_com_ref,reference_result.verfstatus,reference_result.first_qc_approve,reference_result.first_qc_updated_on,reference_result.first_qu_reject_reason,reference.id,reference.has_assigned_on,reference.iniated_date,clients.clientname,reference_insuff.insuff_raised_date");

		$this->db->from('reference');

		$this->db->join("reference_result",'reference_result.reference_id = reference.id');
		
		$this->db->join("candidates_info",'candidates_info.id = reference.candsid');
		
		$this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("reference_insuff",'(reference_insuff.reference_id = reference.id AND  reference_insuff.status = 1 )','left');


		$this->db->join("status",'status.id = reference_result.verfstatus');

		$this->db->where($this->filter_where_cond($where));

       if(isset($where['start_date']) &&  $where['start_date'] != '' && isset($where['end_date']) &&  $where['end_date'] != '')	
		    { 

		     	$start_date  =  $where['start_date'];
	            $end_date  =  $where['end_date'];

	            if($where['status'] == "Closed")
                {
	         
		     	$where3 = "DATE_FORMAT(`reference_result`.`closuredate`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}

		     	if($where['status'] == "Insufficiency")
                {
	         
		     	$where3 = "DATE_FORMAT(`reference_insuff`.`insuff_raised_date`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}
                
                $this->db->where($where3); 

		    } 


		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like('candidates_info.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidatesContactNumber', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);

			$this->db->or_like('reference.reference_com_ref', $where['search']['value']);

			$this->db->or_like('reference.iniated_date', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where['search']['value']);

		}
				
		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 
            
         
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

		    $this->db->order_by('reference.id','desc');
		}    

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_all_reference_record_datatable_new_case($empver_aary = array(),$where,$columns)
	{
         $conditional_status = "(`reference_result`.`verfstatus` = '14' or `reference_result`.`verfstatus` = '11' or `reference_result`.`verfstatus` = '26')"; 


		$this->db->select("candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = reference.has_case_id) as executive_name,status.status_value,reference.id,reference.reference_com_ref,reference_result.verfstatus,reference_result.first_qc_approve,reference.mode_of_veri,reference_result.first_qc_updated_on,reference_result.first_qu_reject_reason,reference.id,reference.has_assigned_on,reference.iniated_date,clients.clientname,due_date,tat_status,reference_result.closuredate,reference.name_of_reference,(select created_on from reference_activity_data where comp_table_id = reference.id order by id desc limit 1) as last_activity_date,(select vendor_name from vendors where vendors.id = reference.vendor_id) as vendor_name,reference_insuff.insuff_raised_date");

		$this->db->from('reference');

		$this->db->join("reference_result",'reference_result.reference_id = reference.id');
		
		$this->db->join("candidates_info",'candidates_info.id = reference.candsid');
		
		$this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("reference_insuff",'(reference_insuff.reference_id = reference.id AND  reference_insuff.status = 1 )','left');


		$this->db->join("status",'status.id = reference_result.verfstatus');

	    $this->db->where($conditional_status);

	    if(isset($where['filter_by_executive']) &&  $where['filter_by_executive'] != 0)	
		{ 
		    if($where['filter_by_executive'] != "All")
	     	{
	     		$this->db->where('reference.has_case_id',$where['filter_by_executive']);
            
	      	}
        }

		

		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like('candidates_info.ClientRefNumber', $where['search']['value']);

	        $this->db->or_like('candidates_info.cmp_ref_no', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);

			$this->db->or_like('reference.reference_com_ref', $where['search']['value']);

			$this->db->or_like('reference.iniated_date', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where['search']['value']);

		}
		
		$this->db->limit($where['length'],$where['start']);
		

		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 
            
         
          
			$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		}
		else
		{
			

		     $this->db->order_by('reference.id','desc');
		}    

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_all_reference_record_datatable_count_new_case($empver_aary = array(),$where,$columns)
	{
		$conditional_status = "(`reference_result`.`verfstatus` = '14' or `reference_result`.`verfstatus` = '11' or `reference_result`.`verfstatus` = '26')"; 

		$this->db->select("candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = reference.has_case_id) as executive_name,status.status_value,reference.reference_com_ref,reference_result.verfstatus,reference_result.first_qc_approve,reference_result.first_qc_updated_on,reference_result.first_qu_reject_reason,reference.id,reference.has_assigned_on,reference.iniated_date,clients.clientname,reference_insuff.insuff_raised_date");

		$this->db->from('reference');

		$this->db->join("reference_result",'reference_result.reference_id = reference.id');
		
		$this->db->join("candidates_info",'candidates_info.id = reference.candsid');
		
		$this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("reference_insuff",'(reference_insuff.reference_id = reference.id AND  reference_insuff.status = 1 )','left');


		$this->db->join("status",'status.id = reference_result.verfstatus');

		
		$this->db->where($conditional_status);


	    if(isset($where['filter_by_executive']) &&  $where['filter_by_executive'] != 0)	
		{ 
		    if($where['filter_by_executive'] != "All")
	     	{
	     		$this->db->where('reference.has_case_id',$where['filter_by_executive']);
            
	      	}
        }

      

		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like('candidates_info.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);

			$this->db->or_like('reference.reference_com_ref', $where['search']['value']);

			$this->db->or_like('reference.iniated_date', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where['search']['value']);

		}
				
		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 
         
			$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		}
		else
		{
			
		    $this->db->order_by('reference.id','desc');
		}    

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_all_reference_record($where_array)
	{
		$this->db->select("candidates_info.id as cands_id,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = reference.has_case_id) as executive_name,reference.has_case_id,status.status_value as verfstatus,reference.id as reference_id,reference.reference_re_open_date,reference.name_of_reference,reference.reference_com_ref,reference.designation,reference.contact_no_first,reference.contact_no_second, reference.contact_no,reference.email_id,reference.build_date,reference_result.id as reference_result_id,reference.clientid,reference_result.first_qc_approve,reference_result.var_filter_status,reference_result.mode_of_verification,closuredate,reference_result.first_qc_updated_on,reference_result.first_qu_reject_reason,reference.id,reference.has_assigned_on,reference.iniated_date,(select created_on from reference_activity_data where comp_table_id = reference.id order by id desc limit 1) as last_activity_date,due_date,tat_status,(select clientname from clients where clients.id = candidates_info.clientid) as clientname,reference_result.remarks,handle_pressure,handle_pressure_value,attendance,attendance_value,integrity,integrity_value,leadership_skills,leadership_skills_value,responsibilities,responsibilities_value,achievements,achievements_value,strengths,strengths_value,team_player,team_player_value,weakness,weakness_value,overall_performance,additional_comments,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,");

		$this->db->from('reference');

		$this->db->join("reference_result",'reference_result.reference_id = reference.id');
		
		$this->db->join("candidates_info",'candidates_info.id = reference.candsid');
		
		$this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("status",'status.id = reference_result.verfstatus');

		$this->db->where($where_array);

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function reference_com_ref()
	{
		$result = $this->db->select("SUBSTRING_INDEX(reference_com_ref, '-',-1) as A_I")->order_by('id','desc')->limit(1)-> get($this->tableName)->row_array();
		return $result;
	}

	public function uploaded_files($files_arry, $return_insert_ids = FALSE)
	{
		$res =  $this->db->insert_batch('reference_files', $files_arry);
		
		record_db_error($this->db->last_query());
		
		return $res;
	}

	 public function save_first_qc_result($files_arry,$arrwhere )
	{

         if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update("reference_result", $files_arry);
         
    
			record_db_error($this->db->last_query());
             
			return $result;
	    }
		
	}

	public function select_reinitiated_date($where_array)
	{
		
	    $this->db->select('reference.*');

		$this->db->from('reference');

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

			$result = $this->db->update('reference', $arrdata);
			
			record_db_error($this->db->last_query());
			
			return $result;
	    }
	     
	}
    

	public function save_update_initiated_date_reference($arrdata,$where_array)
	{
		if(!empty($where_array))
	    {
			$this->db->where($where_array);

			$result = $this->db->update('reference_result', $arrdata);
			
			record_db_error($this->db->last_query());
			
			return $result;
	    }
	     
	}


    public function initiated_date_reference_activity_data($arrdata)
	{
        
		   $this->db->insert('reference_activity_data', $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	}



	public function delete_uploaded_file($where = array())
	{	
		$this->db->where_in('id',$where);

		$this->db->set('status', STATUS_DELETED);

		$result = $this->db->update('reference_files', array('status' => STATUS_DELETED));

		record_db_error($this->db->last_query());

		return $result;
	}

	public function add_uploaded_file($where = array())
	{	
		$this->db->where_in('id',$where);

		$this->db->set('status', STATUS_ACTIVE);

		$result = $this->db->update('reference_files', array('status' => STATUS_ACTIVE));

		record_db_error($this->db->last_query());

		return $result;
	}

	public function get_court_uploded_files($where_array)
	{
		$this->db->select('*');

		$this->db->from('reference_files');

		$this->db->where($where_array);

		$this->db->order_by('serialno','asc');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();	
	}

	public  function get_reporting_manager_id()
    {

    	
        $this->db->select('email,reporting_manager,firstname,lastname,designation,department');

		$this->db->from('user_profile');

		$this->db->where('user_profile.id',$this->user_info['id']);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
    
    }

    public  function get_reporting_manager_email_id($reportingmanager_id)
    {

    	
        $this->db->select('email');

		$this->db->from('user_profile');

		$this->db->where('user_profile.id',$reportingmanager_id);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
    
    }

    public function select_file($select_array,$where_array)
	{
		$this->db->select($select_array);

		$this->db->from('reference_files');

		$this->db->where($where_array);

		$this->db->order_by('id', 'ascc');
		
		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_all_reference_verification_by_client($clientid,$filter_by_status,$from_date,$to_date)
	{	

		$this->db->select("reference.*,clients.clientname,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.DateofBirth,candidates_info.CandidateName,candidates_info.NameofCandidateFather,status.status_value as verfstatus,status.filter_status as filter_status,(select concat(`user_profile`.`firstname`,' ',`user_profile`.`lastname`) from user_profile where user_profile.id = reference.has_case_id limit 1) as executive_name,rr1.closuredate,(SELECT GROUP_CONCAT(concat(DATE_FORMAT(reference_insuff.insuff_raised_date,'%d-%m-%Y')) SEPARATOR '||') FROM reference_insuff where reference_insuff.reference_id = reference.id) as insuff_raised_date,(SELECT GROUP_CONCAT(concat(DATE_FORMAT(reference_insuff.insuff_clear_date,'%d-%m-%Y')) SEPARATOR '||') FROM reference_insuff where reference_insuff.reference_id = reference.id) as insuff_clear_date,(SELECT GROUP_CONCAT(concat(reference_insuff.insuff_raise_remark) SEPARATOR '||') FROM reference_insuff where reference_insuff.reference_id = reference.id) as insuff_raise_remark");

		$this->db->from('reference');

		$this->db->join("clients",'clients.id = reference.clientid');

		$this->db->join("candidates_info",'candidates_info.id = reference.candsid');

		$this->db->join("reference_result as rr1",'rr1.reference_id = reference.id','left');

		$this->db->join("reference_result as rr2",'(rr2.reference_id = reference.id and rr1.id < rr2.id)','left');

		$this->db->join("status",'status.id = rr1.verfstatus','left');

		$this->db->where('rr2.verfstatus is null');

		if($clientid)
		{
			$this->db->where('reference.clientid',$clientid);
		}

	    if($from_date && $to_date)
		{

			$where3 = "DATE_FORMAT(`rr1`.`closuredate`,'%Y-%m-%d') BETWEEN '$from_date' AND '$to_date'";
                
            $this->db->where($where3); 
			
		}


		if($filter_by_status)
		{
			if($filter_by_status == "WIP")
			{
			$this->db->where('(rr1.var_filter_status = "wip" or  rr1.var_filter_status = "WIP")');
		    }
		    if($filter_by_status == "Insufficiency")
			{
			$this->db->where('(rr1.var_filter_status = "insufficiency" or  rr1.var_filter_status = "Insufficiency")');
		    }
		    if($filter_by_status == "Closed")
			{
			$this->db->where('(rr1.var_filter_status = "closed" or  rr1.var_filter_status = "Closed")');
		    }
		}

		$this->db->order_by('reference.id', 'ASC');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

    public function reference_ver_details_for_email($empver_aary = array())
	{
		
		$this->db->select("cmp_ref_no,candidates_info.clientid,CandidateName,candidates_info.ClientRefNumber,reference.name_of_reference,reference.candsid,ev1.handle_pressure,ev1.handle_pressure_value,ev1.verfstatus,ev1.attendance,ev1.attendance_value,ev1.integrity,ev1.integrity_value,ev1.leadership_skills,ev1.leadership_skills_value,ev1.responsibilities,ev1.responsibilities_value,ev1.achievements,ev1.achievements_value,ev1.strengths,ev1.strengths_value,ev1.team_player,ev1.team_player_value,ev1.weakness,ev1.weakness_value,ev1.overall_performance,ev1.additional_comments,clients.clientname as clientname,GROUP_CONCAT(reference_files.file_name) as file_names,GROUP_CONCAT(reference_files.id) as referenceres_files_ids");

		$this->db->from('reference');


		$this->db->join("reference_result as ev1",'ev1.reference_id = reference.id','left');

		$this->db->join("reference_result as ev2",'(ev2.reference_id = reference.id and ev1.id < ev2.id)','left');

		$this->db->join("candidates_info",'candidates_info.id = reference.candsid');
		$this->db->join("clients",'clients.id = reference.clientid');


		$this->db->join("reference_files",'(reference_files.reference_id = reference.id AND reference_files.status = 1) AND reference_files.type = 0','left');

		if(!empty($empver_aary))
		{
			$this->db->where($empver_aary);
		}		
		$this->db->group_by('reference.id');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function upload_file_update($updateArray)
	{
		$this->db->update_batch('reference_files',$updateArray, 'id');
		return true; 
	}

	public function select_insuff($where_array)
	{
		$this->db->select('*')->from('reference_insuff');

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

			$result = $this->db->update('reference_insuff', $arrdata);
			
			record_db_error($this->db->last_query());
			
			return $result;
	    }
	    else
	    {
			$this->db->insert('reference_insuff', $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	    }
	}

	public function select_insuff_join($where_array)
	{
		$this->db->select('reference_insuff.*,(select user_name from user_profile where id =  reference_insuff.created_by limit 1) as insuff_raised_by,(select user_name from user_profile where id = reference_insuff.insuff_cleared_by limit 1) as insuff_cleared_by');

		$this->db->from('reference_insuff');

		$this->db->where($where_array);
		
		$this->db->where('reference_insuff.status !=',3);

		$this->db->order_by('reference_insuff.id','asc');

		return $this->db->get()->result_array();
	}
	
	public function save_update_ver_result($arrdata,$arrwhere = array())
	{
	    if(!empty($arrwhere)) {
			$this->db->where($arrwhere);
			return $this->db->update('reference_result', $arrdata);
	    } else {
			$this->db->insert('reference_result', $arrdata);
			return $this->db->insert_id();
	    }
	}

	public function save_update_ver_result_refrence($arrdata,$arrwhere = array())
	{
	    if(!empty($arrwhere)) {
			$this->db->where($arrwhere);
			return $this->db->update('reference_ver_result', $arrdata);
	    } else {
			$this->db->insert('reference_ver_result', $arrdata);
			return $this->db->insert_id();
	    }
	}

	 public function get_date_for_update($where_array)
    {

        $this->db->select("due_date,tat_status");



        $this->db->from('reference');
   

     
        $this->db->where($where_array);

        $result  = $this->db->get();
      
        return $result->result_array();
    }


     public function update_due_date($files_arry,$arrwhere )
	{

         if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update("reference", $files_arry);
         
    
			record_db_error($this->db->last_query());
             
			return $result;
	    }
		
	}

	public function export_sql($filter) { 
	
	$sql = "SELECT (select clientname from clients where clients.id = candidates_info.clientid limit 1) as
		client_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name, ClientRefNumber,cmp_ref_no,CandidateName,DATE_FORMAT(caserecddate,'%d-%m-%Y') as caserecddate, (select status_value from status where status.id = reference_result.verfstatus limit 1) as verfstatus,reference_com_ref,(select user_name from user_profile where user_profile.id = reference.has_case_id) as executive_name,name_of_reference,designation,contact_no,email_id,handle_pressure,handle_pressure_value,attendance,attendance_value,integrity,integrity_value,leadership_skills,leadership_skills_value,responsibilities,responsibilities_value,achievements,achievements_value,strengths,strengths_value,team_player,team_player_value,weakness,weakness_value,mode_of_verification,    reference_result.remarks,DATE_FORMAT(iniated_date,'%d-%m-%Y') as iniated_date,DATE_FORMAT(due_date,'%d-%m-%Y') as due_date,tat_status,first_qc_updated_on,DATE_FORMAT(closuredate,'%d-%m-%Y') as closuredate,first_qc_approve,(select created_on from reference_activity_data where comp_table_id = reference.id order by id desc limit 1) as last_activity_date
		FROM reference 
		INNER JOIN candidates_info ON candidates_info.id = reference.candsid 
		INNER JOIN reference_result ON reference_result.reference_id = reference.id ".$filter." ";
		
		$query = $this->db->query($sql);

		return $query->result_array();
	}

	public function dashboard_sql($filter) { 
	
	$sql = "SELECT (select clientname from clients where clients.id = candidates_info.clientid limit 1) as
		client_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name, ClientRefNumber,cmp_ref_no,CandidateName,DATE_FORMAT(caserecddate,'%d-%m-%Y') as caserecddate, (select status_value from status where status.id = reference_result.verfstatus limit 1) as verfstatus,reference_com_ref,(select vendor_name from vendors where vendors.id = reference.vendor_id) as vendor_name,(select user_name from user_profile where user_profile.id = reference.has_case_id) as executive_name,name_of_reference,mode_of_veri,    reference_result.remarks,DATE_FORMAT(iniated_date,'%d-%m-%Y') as iniated_date,DATE_FORMAT(due_date,'%d-%m-%Y') as due_date,tat_status,DATE_FORMAT(closuredate,'%d-%m-%Y') as closuredate,(select created_on from reference_activity_data where comp_table_id = reference.id order by id desc limit 1) as last_activity_date
		FROM reference 
		INNER JOIN candidates_info ON candidates_info.id = reference.candsid 
		INNER JOIN reference_result ON reference_result.reference_id = reference.id ".$filter." ";
		
		$query = $this->db->query($sql);

		return $query->result_array();
	}

	public function upload_vendor_assign($tableName,$updateArray,$where_arry)
	{		
		$this->db->where($where_arry);
	 	$this->db->update($tableName,$updateArray);
		return $this->db->affected_rows();
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

    public function select_result_log($where_array)
	{
		$this->db->select('reference_ver_result.*,(select created_by from activity_log where id =  reference_ver_result.activity_log_id) as created_by1,(select user_name from user_profile where id = created_by1 ) as created_by,(select activity_mode from activity_log where id =  reference_ver_result.activity_log_id) as activity_mode ,(select activity_status from activity_log where id =  reference_ver_result.activity_log_id) as activity_status ,(select activity_type from activity_log where id =  reference_ver_result.activity_log_id) as activity_type,(select action from activity_log where id =  reference_ver_result.activity_log_id) as activity_action,(select GROUP_CONCAT(reference_files.file_name) from reference_files where `reference_files`.`reference_id` = `reference_ver_result`.`reference_id` AND `status` = 1 AND `type` = 1)  as file_names,(select GROUP_CONCAT(reference_files.id) from reference_files where `reference_files`.`reference_id` = `reference_ver_result`.`reference_id` AND `status` = 1 AND `type` = 1) as file_ids');

		$this->db->from('reference_ver_result');

		$this->db->where($where_array);

		$this->db->order_by('reference_ver_result.id','desc');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();

		
	}

	public function select_result_log1($where_array)
	{
		$this->db->select('reference_ver_result.*,(select name_of_reference from reference where reference.id =  reference_ver_result.reference_id) as name_of_reference,(select activity_mode from activity_log where id =  reference_ver_result.activity_log_id) as activity_mode ,(select activity_status from activity_log where id =  reference_ver_result.activity_log_id) as activity_status ,(select activity_type from activity_log where id =  reference_ver_result.activity_log_id) as activity_type,(select action from activity_log where id =  reference_ver_result.activity_log_id) as activity_action');

		$this->db->from('reference_ver_result');

		//$this->db->join("education",'education.id = reference_ver_result.education_id');

		//$this->db->join("addrverres",'addrverres.addrverid = addrver.id');

		$this->db->where($where_array);
		$this->db->order_by('id','desc');
		//$this->db->where('addrverres.status !=',3);

		return $this->db->get()->result_array();

		
	}

	public function get_assign_users($tableName, $return_as_strict_row,$select_array, $where_array=array())
	{

		$this->db->select($select_array);

		$this->db->from($tableName);

		$this->db->join('roles','roles.id = user_profile.tbl_roles_id');

		$this->db->join('roles_permissions','roles_permissions.tbl_roles_id = roles.id');

        $this->db->where('roles_permissions.access_reference_list_assign = 1' ); 

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

        $this->db->where('roles_permissions.access_reference_list_assign = 1' ); 
         
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
	 public function get_user_id($where_array)
	{
		$this->db->select('id');

		$this->db->from('user_profile');

		$this->db->where($where_array);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->row('id');
	}


	public function select_candidate_from_file($id)
	{
		$this->db->select('file_name');

		$this->db->from('reference_files');

		$this->db->join('reference','reference.id = reference_files.reference_id');
       
        $this->db->join('candidates_info','candidates_info.id = reference.candsid');

		$this->db->where('candidates_info.id',$id);

		
		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function save_update_reference_files($arrdata,$arrwhere = array())
	{
		if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update('reference_files', $arrdata);

			record_db_error($this->db->last_query());

			return $result;
	    }
	    else
	    {
			$this->db->insert('reference_files', $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	    }
	}

	public function insuff_reason_list($return_as_strict_row,$where)
    {   
        $this->db->select('remarks,reason');

		$this->db->from('raising_insuff_dropdown');

        $this->db->where($where); 
        
        $result_array = $this->db->get()->result_array();	
      
        if($return_as_strict_row)
		{
            if(count($result_array) >0 ) // ensure only one record has been previously inserted
            {
                $result_array = $result_array[0];
            }
        }
       

        return convert_to_single_dimension_array($result_array,'reason','reason');
    }
    
    public function select_client_list_view_reference($tableName, $return_as_strict_row,$select_array, $where1=array())
	{
		$this->db->select($select_array);
       
		$this->db->from($tableName);

		$this->db->join("reference",'reference.clientid = clients.id');

		$this->db->join("user_profile",'user_profile.id = reference.has_case_id','left');

		$this->db->join("candidates_info",'candidates_info.id = reference.candsid');

  		$this->db->join("reference_result",'reference_result.reference_id = reference.id','left');


		$this->db->join("reference_insuff",'(reference_insuff.reference_id = reference.id AND  reference_insuff.status = 1 )','left');

		$this->db->join("status",'status.id = reference_result.verfstatus','left');

        $this->db->where($this->filter_where_cond($where1)); 

        $this->db->where('clients.status',1); 


            if(isset($where1['start_date']) &&  $where1['start_date'] != '' && isset($where1['end_date']) &&  $where1['end_date'] != '')	
		    { 

		     	$start_date  =  $where1['start_date'];
	            $end_date  =  $where1['end_date'];

	            if($where1['status'] == "Closed")
                {
	         
		     	$where3 = "DATE_FORMAT(`reference_result`.`closuredate`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}

		     	if($where1['status'] == "Insufficiency")
                {
	         
		     	$where3 = "DATE_FORMAT(`reference_insuff`.`insuff_raised_date`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

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

    
    public function  select_client_list_assign_reference_view($tableName,$return_as_strict_row,$select_array)
    {
         $conditional_status = "(`reference_result`.`verfstatus` = '14' or `reference_result`.`verfstatus` = '11' or `reference_result`.`verfstatus` = '26')"; 

        $this->db->select($select_array);

		$this->db->from($tableName);

        $this->db->join("reference",'reference.clientid = clients.id');


		$this->db->join("reference_result",'reference_result.reference_id =reference.id');
		
		$this->db->join("candidates_info",'candidates_info.id = reference.candsid');
		

		$this->db->join("status",'status.id = reference_result.verfstatus');
		
        $this->db->where($conditional_status);

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		//print_r($this->db->last_query());
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

    public function get_user_email_id($where_array)
	{
		$this->db->select('email');

		$this->db->from('user_profile');

		$this->db->where($where_array);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->row('email');
	}

	public  function get_reporting_manager_id_client($clientid)
    {
    	
        $this->db->select('clientmgr');

		$this->db->from('clients');

		$this->db->where('clients.id',$clientid);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
    
    }

    public function check_referenceno_exists_in_candidate($where_arry)
    {
        $this->db->select('id');

		$this->db->from('reference');

		$this->db->where($where_arry);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
     
    }
    public function update_status($tableName,$updateArray,$where_arry)
	{	
	   
		$this->db->where($where_arry);
	 	$this->db->update($tableName,$updateArray);
		return $this->db->affected_rows();
	}
    
    public function get_reference_details_for_insuff_mail($where_array)
    {
    	$this->db->select('reference.*,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,(select clientname from clients where clients.id = candidates_info.clientid limit 1) as clientname,reference.name_of_reference');

		$this->db->from('reference');

		$this->db->join('candidates_info','candidates_info.id = reference.candsid');

        if($where_array)
        {
		  $this->db->where($where_array);
	    }

		$result  = $this->db->get();
     
		record_db_error($this->db->last_query());
		
		return $result->result_array();
    }


 
}
?>
