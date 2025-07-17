<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Drug_verificatiion_model extends CI_Model
{
	function __construct()
    {
		$this->tableName = 'drug_narcotis';

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

			$result = $this->db->update('drug_narcotis', $arrdata);

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

	public function select_client_list_view_drugs($tableName, $return_as_strict_row,$select_array, $where1=array())
	{
		$this->db->select($select_array);
       
		$this->db->from($tableName);

		$this->db->join("drug_narcotis",'drug_narcotis.clientid = clients.id');

		$this->db->join("user_profile",'user_profile.id = drug_narcotis.has_case_id','left');

		$this->db->join("candidates_info",'candidates_info.id = drug_narcotis.candsid');

	
		$this->db->join("drug_narcotis_result",'drug_narcotis_result.drug_narcotis_id = drug_narcotis.id','left');


		$this->db->join("drug_narcotis_insuff",'(drug_narcotis_insuff.drug_narcotis_id  = drug_narcotis.id AND  drug_narcotis_insuff.status = 1 )','left');

		$this->db->join("status",'status.id = drug_narcotis_result.verfstatus','left');

        $this->db->where($this->filter_where_cond($where1)); 

        $this->db->where('clients.status',1); 


            if(isset($where1['start_date']) &&  $where1['start_date'] != '' && isset($where1['end_date']) &&  $where1['end_date'] != '')	
		    { 

		     	$start_date  =  $where1['start_date'];
	            $end_date  =  $where1['end_date'];

	            if($where1['status'] == "Closed")
                {
	         
		     	$where3 = "DATE_FORMAT(`drug_narcotis_result`.`closuredate`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}

		     	if($where1['status'] == "Insufficiency")
                {
	         
		     	$where3 = "DATE_FORMAT(`drug_narcotis_insuff`.`insuff_raised_date`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

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


	
	protected function filter_where_cond($where_arry)
	{
		$where = array();
		if(isset($where_arry['status']) &&  $where_arry['status'] != '')	
		{
			
			if($where_arry['status'] != 'All')
			{
			$where['drug_narcotis_result.var_filter_status'] = $where_arry['status'];
		    }
		    
		}

		if(isset($where_arry['sub_status']) && $where_arry['sub_status'] != '' && $where_arry['sub_status'] != 0)	
		{
			$where['drug_narcotis_result.verfstatus'] = $where_arry['sub_status'];
		}

		if(isset($where_arry['client_id']) &&  $where_arry['client_id'] != 0)	
		{
			$where['drug_narcotis.clientid'] = $where_arry['client_id'];
		}
        if(isset($where_arry['filter_by_executive']) &&  $where_arry['filter_by_executive'] != 0)	
		{
	        if($where_arry['filter_by_executive'] != "All")
	      	{
            $where['drug_narcotis.has_case_id'] = $where_arry['filter_by_executive'];
	      	}
	    }

		return $where;

	}



	public function get_all_drug_record_datatable($where,$columns)
	{
		$this->db->select("candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = drug_narcotis.has_case_id) as user_name,status.status_value,drug_narcotis.drug_com_ref,drug_narcotis.mode_of_veri,drug_narcotis_result.first_qc_approve,drug_narcotis_result.first_qc_updated_on,drug_narcotis_result.first_qu_reject_reason,drug_narcotis.id,drug_narcotis.id as drug_narcotis_id,drug_narcotis.has_assigned_on,drug_narcotis.iniated_date,clients.clientname,(select created_on from drug_narcotis_activity_data where comp_table_id = drug_narcotis.id order by id desc limit 1) as last_activity_date,due_date,tat_status,drug_narcotis.state,drug_narcotis.street_address,drug_narcotis.city,drug_narcotis.pincode,drug_narcotis_result.closuredate,drug_narcotis_result.remarks,(select vendor_name from vendors where vendors.id = drug_narcotis.vendor_id) as vendor_name,drug_narcotis_insuff.insuff_raised_date,drug_test_code");

		$this->db->from('drug_narcotis');

		$this->db->join("drug_narcotis_result",'drug_narcotis_result.drug_narcotis_id = drug_narcotis.id');
		
		$this->db->join("candidates_info",'candidates_info.id = drug_narcotis.candsid');

		$this->db->join("drug_narcotis_insuff",'(drug_narcotis_insuff.drug_narcotis_id = drug_narcotis.id AND  drug_narcotis_insuff.status = 1 )','left');
		
		$this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("status",'status.id = drug_narcotis_result.verfstatus');

		$this->db->where($this->filter_where_cond($where));

		 if(isset($where['start_date']) &&  $where['start_date'] != '' && isset($where['end_date']) &&  $where['end_date'] != '')	
		    { 

		     	$start_date  =  $where['start_date'];
	            $end_date  =  $where['end_date'];

	            if($where['status'] == "Closed")
                {
	         
		     	$where3 = "DATE_FORMAT(`drug_narcotis_result`.`closuredate`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}

		     	if($where['status'] == "Insufficiency")
                {
	         
		     	$where3 = "DATE_FORMAT(`drug_narcotis_insuff`.`insuff_raised_date`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}
                
                $this->db->where($where3); 

		    } 


		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like('candidates_info.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('candidates_info.cmp_ref_no', $where['search']['value']);


			$this->db->or_like('clients.clientname', $where['search']['value']);

			$this->db->or_like('drug_narcotis.drug_com_ref', $where['search']['value']);

			$this->db->or_like('drug_narcotis.iniated_date', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where['search']['value']);
			
		}
		
		$this->db->limit($where['length'],$where['start']);
		
		/*if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 

			$this->db->where('drug_narcotis.vendor_id !=',0);

			$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		}*/

		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 
            
           // $this->db->where('drug_narcotis.vendor_id !=',0);

            if(!empty($column_name_index))
			{

			  $this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		    }
		    else
		    {

               $order_clause = "(case verfstatus when 12 THEN 0 else 1 end),(case verfstatus when 13 THEN 0 else 1 end),(case verfstatus when 26 THEN 0 else 1 end),(case verfstatus when 11 THEN 0 else 1 end),(case verfstatus when 14 THEN 0 else 1 end)";

		    	$this->db->order_by($order_clause);
		    }
			//$this->db->order_by($where1['columns'][$column_name_index]['data'],$order_by);
		}
		else
		{
			$order_clause = "(case verfstatus when 12 THEN 0 else 1 end),(case verfstatus when 13 THEN 0 else 1 end),(case verfstatus when 26 THEN 0 else 1 end),(case verfstatus when 11 THEN 0 else 1 end),(case verfstatus when 14 THEN 0 else 1 end)";

		    	$this->db->order_by($order_clause);
		}    

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_all_drug_record_datatable_count($where,$columns)
	{
		$this->db->select("candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = drug_narcotis.has_case_id) as user_name,status.status_value,drug_narcotis.drug_com_ref, drug_narcotis.street_address,drug_narcotis.city,drug_narcotis.pincode,drug_narcotis.state,drug_narcotis_result.first_qc_approve,drug_narcotis_result.first_qc_updated_on,drug_narcotis_result.first_qu_reject_reason,drug_narcotis.id,drug_narcotis.has_assigned_on,drug_narcotis.iniated_date,clients.clientname,(select created_on from drug_narcotis_activity_data where comp_table_id = drug_narcotis.id order by id desc limit 1) as last_activity_date,drug_narcotis_insuff.insuff_raised_date,drug_test_code");

		$this->db->from('drug_narcotis');

		$this->db->join("drug_narcotis_result",'drug_narcotis_result.drug_narcotis_id = drug_narcotis.id');
		
		$this->db->join("candidates_info",'candidates_info.id = drug_narcotis.candsid');

		$this->db->join("drug_narcotis_insuff",'(drug_narcotis_insuff.drug_narcotis_id = drug_narcotis.id AND  drug_narcotis_insuff.status = 1 )','left');

	
		$this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("status",'status.id = drug_narcotis_result.verfstatus');

		
		$this->db->where($this->filter_where_cond($where));

		 if(isset($where['start_date']) &&  $where['start_date'] != '' && isset($where['end_date']) &&  $where['end_date'] != '')	
		    { 

		     	$start_date  =  $where['start_date'];
	            $end_date  =  $where['end_date'];

	            if($where['status'] == "Closed")
                {
	         
		     	$where3 = "DATE_FORMAT(`drug_narcotis_result`.`closuredate`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}

		     	if($where['status'] == "Insufficiency")
                {
	         
		     	$where3 = "DATE_FORMAT(`drug_narcotis_insuff`.`insuff_raised_date`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}
                
                $this->db->where($where3); 

		    } 


		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like('candidates_info.ClientRefNumber', $where['search']['value']);

		    $this->db->or_like('candidates_info.cmp_ref_no', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);

			$this->db->or_like('drug_narcotis.drug_com_ref', $where['search']['value']);

			$this->db->or_like('drug_narcotis.iniated_date', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where['search']['value']);
			
		}
				
		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 
            
            //$this->db->where('drug_narcotis.vendor_id !=',0);

            if(!empty($column_name_index))
			{

			  $this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		    }
		    else
		    {

               $order_clause = "(case verfstatus when 12 THEN 0 else 1 end),(case verfstatus when 13 THEN 0 else 1 end),(case verfstatus when 26 THEN 0 else 1 end),(case verfstatus when 11 THEN 0 else 1 end),(case verfstatus when 14 THEN 0 else 1 end)";

		    	$this->db->order_by($order_clause);
		    }
			//$this->db->order_by($where1['columns'][$column_name_index]['data'],$order_by);
		}
		else
		{
			$order_clause = "(case verfstatus when 12 THEN 0 else 1 end),(case verfstatus when 13 THEN 0 else 1 end),(case verfstatus when 26 THEN 0 else 1 end),(case verfstatus when 11 THEN 0 else 1 end),(case verfstatus when 14 THEN 0 else 1 end)";

		    	$this->db->order_by($order_clause);
		}    

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_all_drug_record($where_array)
	{
		$this->db->select("candidates_info.id as cands_id,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,candidates_info.clientid,candidates_info.entity,candidates_info.package,(select user_name from user_profile where user_profile.id = drug_narcotis.has_case_id) as executive_name,status.status_value as verfstatus,drug_narcotis.id as drug_narcotis_id,drug_narcotis.appointment_date,drug_narcotis.drug_re_open_date,drug_narcotis.drug_com_ref,drug_narcotis.clientid,drug_narcotis.appointment_time, drug_narcotis.spoc_no, drug_narcotis.drug_test_code, drug_narcotis.has_case_id,drug_narcotis.facility_name,drug_narcotis.drug_com_ref,drug_narcotis.build_date, drug_narcotis.street_address,drug_narcotis.city,drug_narcotis.mode_of_veri,drug_narcotis.pincode,drug_narcotis.state as state_id,drug_narcotis_result.first_qc_approve,drug_narcotis_result.var_filter_status,drug_narcotis_result.id as drug_narcotis_result_id,drug_narcotis_result.first_qc_updated_on,drug_narcotis_result.first_qu_reject_reason,drug_narcotis.has_assigned_on,drug_narcotis.iniated_date,clients.clientname,(select created_on from drug_narcotis_activity_data where comp_table_id = drug_narcotis.id order by id desc limit 1) as last_activity_date,due_date,tat_status,drug_narcotis.state,drug_narcotis_result.mode_of_verification,closuredate,drug_narcotis_result.remarks,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,amphetamine_screen,cannabinoids_screen,cocaine_screen,opiates_screen,phencyclidine_screen");

		$this->db->from('drug_narcotis');

		$this->db->join("drug_narcotis_result",'drug_narcotis_result.drug_narcotis_id = drug_narcotis.id');
		
		$this->db->join("candidates_info",'candidates_info.id = drug_narcotis.candsid');
		
		$this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("status",'status.id = drug_narcotis_result.verfstatus');

		$this->db->where($where_array);

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function drug_com_ref()
	{
		$result = $this->db->select("SUBSTRING_INDEX(drug_com_ref, '-',-1) as A_I")->order_by('id','desc')->limit(1)-> get($this->tableName)->row_array();
		return $result;
	}

	public function get_drugs_details_first_qc($where_arry = array())
	{
		$this->db->select("drug_narcotis.*,drug_narcotis_result.*,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.CandidateName,(SELECT status_value FROM status WHERE status.id = drug_narcotis_result.verfstatus) verfstatus_name,(select clientname from clients where clients.id = drug_narcotis_result.clientid limit 1) as clientname,(select user_name from user_profile where user_profile.id = drug_narcotis.has_case_id) as executive_name,due_date,tat_status,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,drug_narcotis.iniated_date,candidates_info.caserecddate,drug_com_ref,(SELECT GROUP_CONCAT(concat(drug_narcotis.clientid,'/',file_name) ORDER BY serialno ASC SEPARATOR '||') FROM drug_narcotis_files where drug_narcotis_files.drug_narcotis_id = drug_narcotis_result.id and drug_narcotis_files.status = 1 and drug_narcotis_files.type= 1) as add_attachments");

		$this->db->from('drug_narcotis');

		$this->db->join("drug_narcotis_result",'drug_narcotis_result.drug_narcotis_id = drug_narcotis.id');
		
		$this->db->join("candidates_info",'candidates_info.id = drug_narcotis.candsid');

		if($where_arry)
		{
			$this->db->where($where_arry);
		}
		
		$result = $this->db->get();
		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function save_first_qc_result($files_arry,$arrwhere )
	{

         if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update("drug_narcotis_result", $files_arry);
         
    
			record_db_error($this->db->last_query());
             
			return $result;
	    }
		
	}

	public function delete_uploaded_file($where = array())
	{	
		$this->db->where_in('id',$where);

		$this->db->set('status', STATUS_DELETED);

		$result = $this->db->update('drug_narcotis_files', array('status' => STATUS_DELETED));

		record_db_error($this->db->last_query());

		return $result;
	}

	public function add_uploaded_file($where = array())
	{	
		$this->db->where_in('id',$where);

		$this->db->set('status', STATUS_ACTIVE);

		$result = $this->db->update('drug_narcotis_files', array('status' => STATUS_ACTIVE));

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

		$this->db->from('drug_narcotis_files');

		$this->db->where($where_array);

		$this->db->order_by('id', 'asc');
		
		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function uploaded_files($files_arry, $return_insert_ids = FALSE)
	{
		$res =  $this->db->insert_batch('drug_narcotis_files', $files_arry);
		
		record_db_error($this->db->last_query());
		
		return $res;
	}

	  public function get_date_for_update($where_array)
    {

        $this->db->select("due_date,tat_status");



        $this->db->from('drug_narcotis');
   

     
        $this->db->where($where_array);

        $result  = $this->db->get();
      
        return $result->result_array();
    }

    	public function select_reinitiated_date($where_array)
    {
        
        $this->db->select('drug_narcotis.*');

        $this->db->from('drug_narcotis');

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

            $result = $this->db->update('drug_narcotis', $arrdata);
            
            record_db_error($this->db->last_query());
            
            return $result;
        }
         
    }
   
    public function save_update_initiated_date_drugs($arrdata,$where_array)
    {
        if(!empty($where_array))
        {
            $this->db->where($where_array);

            $result = $this->db->update('drug_narcotis_result', $arrdata);
            
            record_db_error($this->db->last_query());
            
            return $result;
        }
         
    }


    public function initiated_date_drugs_activity_data($arrdata)
    {
        
           $this->db->insert('drug_narcotis_activity_data', $arrdata);

            record_db_error($this->db->last_query());

            return $this->db->insert_id();
    }
    


     public function update_due_date($files_arry,$arrwhere )
	{

         if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update("drug_narcotis", $files_arry);
         
    
			record_db_error($this->db->last_query());
             
			return $result;
	    }
		
	}

	public function get_court_uploded_files($where_array)
	{
		$this->db->select('*');

		$this->db->from('drug_narcotis_files');

		$this->db->where($where_array);

		$this->db->order_by('serialno','asc');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();	
	}

	public function upload_file_update($updateArray)
	{
		$this->db->update_batch('drug_narcotis_files',$updateArray, 'id');
		return true; 
	}

	public function select_insuff($where_array)
	{
		$this->db->select('*')->from('drug_narcotis_insuff');

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

			$result = $this->db->update('drug_narcotis_insuff', $arrdata);
			
			record_db_error($this->db->last_query());
			
			return $result;
	    }
	    else
	    {
			$this->db->insert('drug_narcotis_insuff', $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	    }
	}

	public function select_insuff_join($where_array)
	{
		$this->db->select('drug_narcotis_insuff.*,(select user_name from user_profile where id =  drug_narcotis_insuff.created_by limit 1) as insuff_raised_by,(select user_name from user_profile where id = drug_narcotis_insuff.insuff_cleared_by limit 1) as insuff_cleared_by');

		$this->db->from('drug_narcotis_insuff');

		$this->db->where($where_array);
		
		$this->db->where('drug_narcotis_insuff.status !=',3);

		$this->db->order_by('drug_narcotis_insuff.id','asc');

		return $this->db->get()->result_array();
	}
	
	public function save_update_result($arrdata,$arrwhere = array())
	{
	    if(!empty($arrwhere)) {
			$this->db->where($arrwhere);
			return $this->db->update('drug_narcotis_result', $arrdata);
	    } else {
			$this->db->insert('drug_narcotis_result', $arrdata);
			return $this->db->insert_id();
	    }
	}

	public function save_update_result_drugs($arrdata,$arrwhere = array())
	{
	    if(!empty($arrwhere)) {
			$this->db->where($arrwhere);
			return $this->db->update('drug_narcotis_ver_result', $arrdata);
	    } else {
			$this->db->insert('drug_narcotis_ver_result', $arrdata);
			return $this->db->insert_id();
		}
	   
	}

	public function export_sql($filter) { 
	
	$sql = "SELECT (select clientname from clients where clients.id = candidates_info.clientid limit 1) as
		client_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name, ClientRefNumber,cmp_ref_no,CandidateName,DATE_FORMAT(caserecddate,'%d-%m-%Y') as caserecddate, (select status_value from status where status.id = drug_narcotis_result.verfstatus limit 1) as verfstatus,drug_com_ref,(select user_name from user_profile where user_profile.id = drug_narcotis.has_case_id) as executive_name,DATE_FORMAT(appointment_date,'%d-%m-%Y') as appointment_date,DATE_FORMAT(appointment_time,'%H-%i') as appointment_time,spoc_no,drug_test_code,facility_name,street_address,city,pincode,state,vendor_id,mode_of_verification,amphetamine_screen,cannabinoids_screen,cocaine_screen,opiates_screen,phencyclidine_screen,
			drug_narcotis_result.remarks,DATE_FORMAT(iniated_date,'%d-%m-%Y') as iniated_date,DATE_FORMAT(due_date,'%d-%m-%Y') as due_date,tat_status,first_qc_updated_on,DATE_FORMAT(closuredate,'%d-%m-%Y') as closuredate,first_qc_approve,(select created_on from drug_narcotis_activity_data where comp_table_id = drug_narcotis.id order by id desc limit 1) as last_activity_date,(select count(file_name) from drug_narcotis_files where drug_narcotis_files.drug_narcotis_id = drug_narcotis_result.id and type = 1) as file_name
		FROM drug_narcotis 
		INNER JOIN candidates_info ON candidates_info.id = drug_narcotis.candsid 
		INNER JOIN drug_narcotis_result ON drug_narcotis_result.drug_narcotis_id = drug_narcotis.id ".$filter." ";
		
		$query = $this->db->query($sql);

		return $query->result_array();
	}

	public function dashboard_sql($filter) { 
	
	$sql = "SELECT (select clientname from clients where clients.id = candidates_info.clientid limit 1) as
		client_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name, ClientRefNumber,cmp_ref_no,CandidateName,DATE_FORMAT(caserecddate,'%d-%m-%Y') as caserecddate, (select status_value from status where status.id = drug_narcotis_result.verfstatus limit 1) as verfstatus,drug_com_ref,(select user_name from user_profile where user_profile.id = drug_narcotis.has_case_id) as executive_name,(select vendor_name from vendors where vendors.id = drug_narcotis.vendor_id) as vendor_name,street_address,city,pincode,state,mode_of_veri,DATE_FORMAT(iniated_date,'%d-%m-%Y') as iniated_date,DATE_FORMAT(due_date,'%d-%m-%Y') as due_date,tat_status,DATE_FORMAT(closuredate,'%d-%m-%Y') as closuredate,(select created_on from drug_narcotis_activity_data where comp_table_id = drug_narcotis.id order by id desc limit 1) as last_activity_date
		FROM drug_narcotis 
		INNER JOIN candidates_info ON candidates_info.id = drug_narcotis.candsid 
		INNER JOIN drug_narcotis_result ON drug_narcotis_result.drug_narcotis_id = drug_narcotis.id ".$filter." ";
		
		$query = $this->db->query($sql);

		return $query->result_array();
	}
	
	public function upload_vendor_assign($tableName,$updateArray,$where_arry)
	{		
		$this->db->where($where_arry);
	 	$this->db->update($tableName,$updateArray);
		return $this->db->affected_rows();
	}

	public function select_result_log($where_array)
	{

		$this->db->select('drug_narcotis_ver_result.*,(select created_by from activity_log where id =  drug_narcotis_ver_result.activity_log_id) as created_by1,(select user_name from user_profile where id = created_by1 ) as created_by,(select activity_mode from activity_log where id =  drug_narcotis_ver_result.activity_log_id) as activity_mode ,(select activity_status from activity_log where id =  drug_narcotis_ver_result.activity_log_id) as activity_status ,(select activity_type from activity_log where id =  drug_narcotis_ver_result.activity_log_id) as activity_type,(select action from activity_log where id =  drug_narcotis_ver_result.activity_log_id) as activity_action,(select GROUP_CONCAT(drug_narcotis_files.file_name) from drug_narcotis_files where `drug_narcotis_files`.`drug_narcotis_id` = `drug_narcotis_ver_result`.`drug_narcotis_id` AND `status` = 1 AND `type` = 1)  as file_names,(select GROUP_CONCAT(drug_narcotis_files.id) from drug_narcotis_files where `drug_narcotis_files`.`drug_narcotis_id` = `drug_narcotis_ver_result`.`drug_narcotis_id` AND `status` = 1 AND `type` = 1) as file_ids');

		$this->db->from('drug_narcotis_ver_result');

		$this->db->where($where_array);
		$this->db->order_by('drug_narcotis_ver_result.id','desc');
		
        $result = $this->db->get();
		record_db_error($this->db->last_query());

		return $result->result_array();

		
	}

	public function select_result_log1($where_array)
	{
		$this->db->select('drug_narcotis_ver_result.*,(select activity_mode from activity_log where id =  drug_narcotis_ver_result.activity_log_id) as activity_mode ,(select activity_status from activity_log where id =  drug_narcotis_ver_result.activity_log_id) as activity_status ,(select activity_type from activity_log where id =  drug_narcotis_ver_result.activity_log_id) as activity_type,(select action from activity_log where id =  drug_narcotis_ver_result.activity_log_id) as activity_action,GROUP_CONCAT(drug_narcotis_files.file_name) as file_names,GROUP_CONCAT(drug_narcotis_files.id) as file_ids');

		$this->db->from('drug_narcotis_ver_result');

		$this->db->join("drug_narcotis",'drug_narcotis.id = drug_narcotis_ver_result.drug_narcotis_id');

	    $this->db->join("drug_narcotis_files",'(drug_narcotis_files.drug_narcotis_id = drug_narcotis_ver_result.drug_narcotis_id AND drug_narcotis_files.status = 1 AND type = 1)','left');

		$this->db->where($where_array);
		$this->db->order_by('id','desc');
		

		return $this->db->get()->result_array();

		
	}

	/*public function vendor_logs($where_array)
	{
		$this->db->select('vendor_master_log.status,vendor_master_log.id,trasaction_id,component,(select user_name from user_profile where user_profile.id = drug_narcotis_vendor_log.created_by) as allocated_by,drug_narcotis_vendor_log.created_on allocated_on,(select user_name from user_profile where user_profile.id = drug_narcotis_vendor_log.approval_by) as approval_by,drug_narcotis_vendor_log.modified_on approval_on,(select vendor_name from vendors where vendors.id= drug_narcotis_vendor_log.vendor_id) as vendor_name, drug_com_ref as component_ref,ClientRefNumber,cmp_ref_no,costing,vendor_master_log.tat_status');

		$this->db->from('vendor_master_log');

		$this->db->join('drug_narcotis_vendor_log','drug_narcotis_vendor_log.id = vendor_master_log.case_id');

		$this->db->join('drug_narcotis','drug_narcotis.id = drug_narcotis_vendor_log.case_id');

		$this->db->join('candidates_info','candidates_info.id = drug_narcotis.candsid');

		$this->db->where($where_array);

		$this->db->order_by('drug_narcotis_vendor_log.id', 'desc');

		return $this->db->get()->result_array();
	}*/

	public function vendor_logs($where_array,$id)
    {
        $this->db->select("view_vendor_master_log.*,(select vendor_name from vendors where vendors.id= drug_narcotis_vendor_log.vendor_id) as vendor_name,drug_narcotis_vendor_log.case_id,(select user_name from user_profile where id = view_vendor_master_log.allocated_by) as allocated_by,(select user_name from user_profile where id = view_vendor_master_log.approval_by) as approval_by");

        $this->db->from('view_vendor_master_log');
        $this->db->join('drug_narcotis_vendor_log','drug_narcotis_vendor_log.id = view_vendor_master_log.case_id');

        //$this->db->join('user_profile','user_profile.id = view_vendor_master_log.allocated_by','left');

         // $this->db->join('user_profile','user_profile.id = view_vendor_master_log.approved_by','left');
        $this->db->where($where_array);

        if(!empty($id))
        {
        	 $this->db->where('drug_narcotis_vendor_log.case_id',$id);
        }

        $this->db->where_in('view_vendor_master_log.status',array('0','1','2','3','5'));

        $this->db->order_by("view_vendor_master_log.id", "desc");

        $result  = $this->db->get();
       // print_r($this->db->last_query());
        return $result->result_array();
    }

     public function get_assign_users($tableName, $return_as_strict_row,$select_array, $where_array=array())
	{

		$this->db->select($select_array);

		$this->db->from($tableName);

		$this->db->join('roles','roles.id = user_profile.tbl_roles_id');

		$this->db->join('roles_permissions','roles_permissions.tbl_roles_id = roles.id');

        $this->db->where('roles_permissions.access_drugs_list_assign = 1' ); 

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

        $this->db->where('roles_permissions.access_drugs_list_assign = 1' ); 
        
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
		$this->db->select('mode_of_verification');

		$this->db->from('clients_details');
		
		$this->db->where($where);

        $this->db->limit(1);  

		$result  = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}


	public function get_scope_of_work($where)
	{
		$this->db->select('scope_of_work');

		$this->db->from('clients_details');
		
		$this->db->where($where);

        $this->db->limit(1);  

		$result  = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
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

		$this->db->from('drug_narcotis_files');

		$this->db->join('drug_narcotis','drug_narcotis.id = drug_narcotis_files.drug_narcotis_id');
       
        $this->db->join('candidates_info','candidates_info.id = drug_narcotis.candsid');

		$this->db->where('candidates_info.id',$id);
		
		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
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
  
	public  function get_reporting_manager_id($clientid)
    {
    	
        $this->db->select('clientmgr');

		$this->db->from('clients');

		$this->db->where('clients.id',$clientid);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
    
    }

    public function get_drugs_details_for_approval($case_id)
    {
    	$this->db->select('drug_narcotis_vendor_log.id as drug_narcotis_vendor_log_id,clients.clientname,drug_narcotis.drug_com_ref,drug_narcotis.drug_test_code,candidates_info.CandidateName,candidates_info.DateofBirth,candidates_info.CandidatesContactNumber,candidates_info.ContactNo1,candidates_info.ContactNo2,candidates_info.NameofCandidateFather,drug_narcotis.vendor_id,drug_narcotis.street_address,drug_narcotis.city,drug_narcotis.pincode,drug_narcotis.state');

		$this->db->from('drug_narcotis_vendor_log');

		$this->db->join('drug_narcotis','drug_narcotis.id = drug_narcotis_vendor_log.case_id ');

        $this->db->join('candidates_info','candidates_info.id = drug_narcotis.candsid');

        $this->db->join('clients','clients.id = drug_narcotis.clientid');

		$this->db->where('drug_narcotis_vendor_log.id',$case_id);

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
	  
	public function select_vendor_result_log($where_array,$id)
    {

        $this->db->select("view_vendor_master_log.*,(select vendor_name from vendors where vendors.id= drug_narcotis_vendor_log.vendor_id) as vendor_name,(select user_name from user_profile where id = view_vendor_master_log.allocated_by) as allocated_by,(select user_name from user_profile where id = view_vendor_master_log.approval_by) as approval_by,drug_narcotis.*,candidates_info.id as CandidateID,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.CandidateName,(select clientname from clients where clients.id = drug_narcotis.clientid limit 1) as clientname,candidates_info.entity as entity_id,candidates_info.package as package_id,
			(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,drug_narcotis.iniated_date,candidates_info.caserecddate,drug_com_ref,(select id from drug_narcotis_result where drug_narcotis_result.drug_narcotis_id = drug_narcotis.id) as drugs_id,(select id from drug_narcotis_result where drug_narcotis_result.drug_narcotis_id = drug_narcotis.id) as drugs_result_id,view_vendor_master_log.id as view_vendor_master_log_id");



        $this->db->from('view_vendor_master_log');
        $this->db->join('drug_narcotis_vendor_log','drug_narcotis_vendor_log.id = view_vendor_master_log.case_id');

        $this->db->join('drug_narcotis','drug_narcotis.id = drug_narcotis_vendor_log.case_id');

		$this->db->join("candidates_info",'candidates_info.id = drug_narcotis.candsid');

        $this->db->where($where_array);

        if(!empty($id))
        {
        	 $this->db->where('view_vendor_master_log.id',$id);
        }

        $result  = $this->db->get();
       // print_r($this->db->last_query());
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

	public function select_file_vendor($select_array,$where_array,$where_array_id)
	{
		$this->db->select($select_array);

		$this->db->from('view_vendor_master_log_file');

		$this->db->join('view_vendor_master_log','view_vendor_master_log.id = view_vendor_master_log_file.view_venor_master_log_id');

        $this->db->join('drug_narcotis_vendor_log','drug_narcotis_vendor_log.id = view_vendor_master_log.case_id');

        $this->db->join("drug_narcotis",'drug_narcotis.id = drug_narcotis_vendor_log.case_id');

		$this->db->where($where_array);

		$this->db->where('drug_narcotis.id',$where_array_id);
		
		
		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
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

	public function select_vendor_result_log_cost($where_array,$id)
    {

        $this->db->select("view_vendor_master_log.*");



        $this->db->from('view_vendor_master_log');
   

        //$this->db->join('user_profile','user_profile.id = view_vendor_master_log.allocated_by','left');

         // $this->db->join('user_profile','user_profile.id = view_vendor_master_log.approved_by','left');
        $this->db->where($where_array);

        if(!empty($id))
        {
        	 $this->db->where('view_vendor_master_log.id',$id);
        }

        $result  = $this->db->get();
        //print_r($this->db->last_query());
        return $result->result_array();
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

	public function update_drugs_vendor_log($tablename,$arrdata,$arrwhere = array())
	{
	    if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update($tablename, $arrdata);
       
			record_db_error($this->db->last_query());
             
			return $result;
	    }
	    
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
     
	  $this->db->select("view_vendor_master_log.id,view_vendor_master_log.case_id as  vendor_master_log_case, drug_narcotis_vendor_log.id as drug_narcotis_vendor_log_id,drug_narcotis_vendor_log.case_id as drug_narcotis_vendor_log_case,drug_narcotis.id as drug_narcotis_id , drug_narcotis.clientid as client_id" );
        $this->db->from('view_vendor_master_log');
       // $this->db->join('addrver','addrver.id = view_vendor_master_log.component_tbl_id');
        $this->db->join('drug_narcotis_vendor_log','drug_narcotis_vendor_log.id = view_vendor_master_log.case_id');
        $this->db->join('drug_narcotis','drug_narcotis.id = drug_narcotis_vendor_log.case_id');
        $this->db->join("clients",'clients.id = drug_narcotis.clientid');


        $this->db->where($where_array);
        
        $result = $this->db->get();
  
       return $result->result_array();
	}

    public function drugs_narcotics_case_list($where_array = array(),$where)
    {
        $where_condition = "(view_vendor_master_log.final_status = 'closed')" ;

        $this->db->select("view_vendor_master_log.*,CandidateName,clients.clientname,drug_narcotis.id as drug_narcotis_id,drug_narcotis.drug_com_ref,drug_narcotis.street_address,drug_narcotis.vendor_list_mode,drug_narcotis.city,drug_narcotis.state,drug_narcotis.pincode,(select vendor_name from vendors where vendors.id = drug_narcotis.vendor_id) as vendor_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,candidates_info.ClientRefNumber");

        $this->db->from('view_vendor_master_log');
      
        $this->db->join('drug_narcotis_vendor_log','drug_narcotis_vendor_log.id = view_vendor_master_log.case_id');
        $this->db->join('drug_narcotis','drug_narcotis.id = drug_narcotis_vendor_log.case_id');


        $this->db->join('candidates_info','candidates_info.id = drug_narcotis.candsid');
       
        $this->db->join("clients",'clients.id = drug_narcotis.clientid');


        $this->db->where($where_array);

        $this->db->where($where_condition);

       /* if($where != "All")
        {

          $this->db->where('courtver.has_case_id', $where);

        }*/

        $this->db->order_by('view_vendor_master_log.trasaction_id', 'ASC');
    
        $result = $this->db->get();
  
       return $result->result_array();
   
    }

    public function drugs_case_list_insuff($where_array = array(),$where)
    {
        $admin_status = "(drug_narcotis_result.verfstatus = 1 or drug_narcotis_result.verfstatus = 11 or drug_narcotis_result.verfstatus = 12 or drug_narcotis_result.verfstatus = 13 or drug_narcotis_result.verfstatus = 14 or drug_narcotis_result.verfstatus = 16 or drug_narcotis_result.verfstatus = 23 or drug_narcotis_result.verfstatus = 26 )";

        $this->db->select("view_vendor_master_log.*,CandidateName,clients.clientname,drug_narcotis.id as drug_narcotis_id,drug_narcotis.drug_com_ref,drug_narcotis.street_address,drug_narcotis.vendor_list_mode,drug_narcotis.city,drug_narcotis.state,drug_narcotis.pincode,(select vendor_name from vendors where vendors.id = drug_narcotis.vendor_id) as vendor_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,candidates_info.ClientRefNumber");
        
        $this->db->from('view_vendor_master_log');
      
        $this->db->join('drug_narcotis_vendor_log','drug_narcotis_vendor_log.id = view_vendor_master_log.case_id');

        $this->db->join('drug_narcotis','drug_narcotis.id = drug_narcotis_vendor_log.case_id');

        $this->db->join('drug_narcotis_result','drug_narcotis_result.drug_narcotis_id = drug_narcotis.id');

        $this->db->join('status','status.id = drug_narcotis_result.verfstatus');

        $this->db->join('candidates_info','candidates_info.id = drug_narcotis.candsid');
       
        $this->db->join("clients",'clients.id = drug_narcotis.clientid');


        $this->db->where($where_array);

        $this->db->where($admin_status);

        /*if($where != "All")
        {

          $this->db->where('courtver.has_case_id', $where);

        }*/

        $this->db->order_by('view_vendor_master_log.trasaction_id', 'ASC');
    
   
        $result = $this->db->get();
    
        return $result->result_array();
    
      
    }

    public function get_vendor_id_form_panel($panel)
	{ 
		$this->db->select('id');
       
		$this->db->from('vendors');

		
		if(!empty($panel))
		{
	    	$this->db->where("find_in_set('".$panel."', panel_code)"); 
		}

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$result_array = $result->result_array();

		return $result_array;

	}

	public function save_narcotics($table_name,$arrdata,$arrwhere = array())
	{
	    if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update($table_name, $arrdata);

			record_db_error($this->db->last_query());

			return $result;
	    }
	    else
	    {
			$this->db->insert($table_name, $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	    }
	}
   
    public function select_drugs_dt($table_name,$select_array,$where_array)
	{
		$this->db->select($select_array);

		$this->db->from($table_name);

		$this->db->where($where_array);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
        
        return $result->result_array();    
	} 



	public function get_all_addrs_by_client($where_arry = array())
	{	
		$this->db->select("addrver.*,clients.clientname,cands.ClientRefNumber,cands.cmp_ref_no,cands.CandidateName,membership_groups.name,a1.verfstatus,cands.caserecddate,a1.created_on as last_modified,a1.closuredate,a1.insuffraiseddate,a1.insuffcleardate,a1.insuffremarks,a1.insuff_raised_date_2,a1.insuff_clear_date_2,a1.insuff_remarks_2,a1.verfstatus as addres_status,a1.remark,a1.insuff_additional_remark_1,a1.insuff_additional_remark_2,cands.DateofBirth,cands.CandidatesContactNumber,cands.NameofCandidateFather,cands.ContactNo1");

		$this->db->from('addrver');

		$this->db->join("clients",'clients.id = addrver.clientid');

		$this->db->join("cands",'cands.id = addrver.candsid');

		$this->db->join("membership_groups",'membership_groups.groupID = addrver.assignedto');

		$this->db->join("addrverres as a1",'a1.addrverid = addrver.id','left');

		$this->db->join("addrverres as a2",'(a2.addrverid = addrver.id and a1.id < a2.id)','left');

		$this->db->where('a2.verfstatus is null');

		if($where_arry)
		{
			$this->db->where($where_arry);
		}

		$this->db->order_by('a1.id', 'desc');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}
    

    public function get_all_drugs_by_client($clientid,$filter_by_status)
	{	


        $this->db->select("drug_narcotis.*,clients.clientname,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.CandidateName,candidates_info.NameofCandidateFather,candidates_info.CandidatesContactNumber,candidates_info.ContactNo1,candidates_info.ContactNo2,candidates_info.DateofBirth,candidates_info.gender,status.status_value as verfstatus,status.filter_status as filter_status,a1.closuredate,(select concat(`user_profile`.`firstname`,' ',`user_profile`.`lastname`) from user_profile where user_profile.id = drug_narcotis.has_case_id limit 1) as executive_name,(select vendor_name from vendors where vendors.id = drug_narcotis.vendor_id limit 1) as vendor_name,(SELECT v.vendor_actual_status from view_vendor_master_log v, `drug_narcotis_vendor_log` `ad` where ad.case_id = drug_narcotis.id and v.case_id = ad.id and component = 'narcver' and component_tbl_id = '7' order by v.id desc limit 1) as vendor_status,(SELECT v.final_status from view_vendor_master_log v, `drug_narcotis_vendor_log` `ad` where ad.case_id = drug_narcotis.id and v.case_id = ad.id and component = 'narcver' and component_tbl_id = '7' order by v.id desc limit 1) as final_status,(SELECT v.trasaction_id  from view_vendor_master_log v, `drug_narcotis_vendor_log` `ad` where ad.case_id = drug_narcotis.id and v.case_id = ad.id and component = 'narcver' and component_tbl_id = '7' order by v.id desc limit 1) as transaction_id,(SELECT GROUP_CONCAT(concat(DATE_FORMAT(drug_narcotis_insuff.insuff_raised_date,'%d-%m-%Y')) SEPARATOR '||') FROM drug_narcotis_insuff where drug_narcotis_insuff.drug_narcotis_id = drug_narcotis.id) as insuff_raised_date,(SELECT GROUP_CONCAT(concat(DATE_FORMAT(drug_narcotis_insuff.insuff_clear_date,'%d-%m-%Y')) SEPARATOR '||') FROM drug_narcotis_insuff where drug_narcotis_insuff.drug_narcotis_id = drug_narcotis.id) as insuff_clear_date,(SELECT GROUP_CONCAT(concat(drug_narcotis_insuff.insuff_raise_remark) SEPARATOR '||') FROM drug_narcotis_insuff where drug_narcotis_insuff.drug_narcotis_id = drug_narcotis.id) as insuff_raise_remark");
 
		$this->db->from('drug_narcotis');

		$this->db->join("clients",'clients.id = drug_narcotis.clientid');

		$this->db->join("candidates_info",'candidates_info.id = drug_narcotis.candsid');

		$this->db->join("drug_narcotis_result as a1",'a1.drug_narcotis_id = drug_narcotis.id','left');

		$this->db->join("drug_narcotis_result as a2",'(a2.drug_narcotis_id = drug_narcotis.id and a1.id < a2.id)','left');

		$this->db->join("status",'status.id = a1.verfstatus','left');

		$this->db->where('a2.verfstatus is null');

		if($clientid)
		{
			$this->db->where('drug_narcotis.clientid',$clientid);
		}

	
		if($filter_by_status)
		{
			if($filter_by_status == "WIP")
			{
			$this->db->where('(a1.var_filter_status = "wip" or  a1.var_filter_status = "WIP")');
		    }
		    if($filter_by_status == "Insufficiency")
			{
			$this->db->where('(a1.var_filter_status = "insufficiency" or  a1.var_filter_status = "Insufficiency")');
		    }
		    if($filter_by_status == "Closed")
			{
			$this->db->where('(a1.var_filter_status = "closed" or  a1.var_filter_status = "Closed")');
		    }
		}

		$this->db->order_by('drug_narcotis.id', 'ASC');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}
   
    public function select_drugs_approve_list($where_array = array())
	{
		$this->db->select('drug_narcotis_vendor_log.id');

		$this->db->from('drug_narcotis_vendor_log');

		$this->db->join('drug_narcotis','drug_narcotis.id = drug_narcotis_vendor_log.case_id');

        $this->db->join("drug_narcotis_result",'drug_narcotis_result.drug_narcotis_id = drug_narcotis.id','left');

        $this->db->join("candidates_info",'candidates_info.id = drug_narcotis.candsid','left');

		$this->db->where($where_array);

        $this->db->where('(drug_narcotis_result.var_filter_status = "wip" or drug_narcotis_result.var_filter_status = "WIP")');

        $this->db->where("drug_narcotis_vendor_log.created_on <= DATE_SUB(NOW(), INTERVAL 30 MINUTE)", NULL, FALSE);

		$this->db->order_by('drug_narcotis_vendor_log.id', 'desc');

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}


    
}
?>