<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Assign_pcc_verificatiion_model extends CI_Model
{
	function __construct()
    {
		$this->tableName = 'pcc';

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

	public function get_all_pcc_record_datatable_assign_verbal($empver_aary = array(),$where,$columns)
	{
		$this->db->select("candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = pcc.has_case_id) as user_name,status.status_value,pcc.pcc_com_ref,,pcc.address_type, pcc.street_address,pcc.id as pcc_id,pcc.city,pcc.pincode,pcc.state,pcc_result.first_qc_approve,pcc_result.first_qc_updated_on,pcc_result.first_qu_reject_reason,pcc.id,pcc.has_assigned_on,pcc.iniated_date,clients.clientname,(select created_on from pcc_activity_data where comp_table_id = pcc.id  order by id desc limit 1) as last_activity_date,,closuredate,pcc_result.remarks,(select vendor_name from vendors where vendors.id = pcc.vendor_id) as vendor_name,due_date,tat_status");

		$this->db->from('pcc');

		$this->db->join("pcc_result",'pcc_result.pcc_id = pcc.id');
		
		$this->db->join("candidates_info",'candidates_info.id = pcc.candsid');
		
		$this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("status",'status.id = pcc_result.verfstatus');

	    $this->db->where('pcc.vendor_id =',0);

	    $this->db->where('pcc.mode_of_veri','verbal');
	    
		$this->db->where('(pcc_result.var_filter_status = "wip" or pcc_result.var_filter_status = "WIP")');
		

		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like('candidates_info.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('candidates_info.cmp_ref_no', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where['search']['value']);
		}


	
		$this->db->limit($where['length'],$where['start']);
		
		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 
           
			$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		}

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_all_pcc_record_datatable_count_assign_verbal($empver_aary = array(),$where,$columns)
	{
		$this->db->select("candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = pcc.has_case_id) as user_name,status.status_value,pcc.pcc_com_ref,pcc.address_type, pcc.street_address,pcc.city,pcc.pincode,pcc.state,pcc_result.first_qc_approve,pcc_result.first_qc_updated_on,pcc_result.first_qu_reject_reason,pcc.id,pcc.has_assigned_on,pcc.iniated_date,clients.clientname,(select created_on from pcc_activity_data where comp_table_id = pcc.id  order by id desc limit 1) as last_activity_date");

		$this->db->from('pcc');

		$this->db->join("pcc_result",'pcc_result.pcc_id = pcc.id');
		
		$this->db->join("candidates_info",'candidates_info.id = pcc.candsid');
		
		$this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("status",'status.id = pcc_result.verfstatus');

		$this->db->where('pcc.vendor_id =',0);
        

		$this->db->where('pcc.mode_of_veri','verbal');
	    
		$this->db->where('(pcc_result.var_filter_status = "wip" or pcc_result.var_filter_status = "WIP")');



		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like('candidates_info.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('candidates_info.cmp_ref_no', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where['search']['value']);
		}

	   				
		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 

			$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		}

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}


	public function get_all_pcc_record_datatable_assign($empver_aary = array(),$where,$columns)
	{
		$this->db->select("candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = pcc.has_case_id) as user_name,status.status_value,pcc.pcc_com_ref,,pcc.address_type, pcc.street_address,pcc.id as pcc_id,pcc.city,pcc.pincode,pcc.state,pcc_result.first_qc_approve,pcc_result.first_qc_updated_on,pcc_result.first_qu_reject_reason,pcc.id,pcc.has_assigned_on,pcc.iniated_date,clients.clientname,(select created_on from pcc_activity_data where comp_table_id = pcc.id  order by id desc limit 1) as last_activity_date,,closuredate,pcc_result.remarks,(select vendor_name from vendors where vendors.id = pcc.vendor_id) as vendor_name,due_date,tat_status");

		$this->db->from('pcc');

		$this->db->join("pcc_result",'pcc_result.pcc_id = pcc.id');
		
		$this->db->join("candidates_info",'candidates_info.id = pcc.candsid');
		
		$this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("status",'status.id = pcc_result.verfstatus');

	    $this->db->where('pcc.vendor_id =',0);

	    $this->db->where('pcc.mode_of_veri !=','verbal');

	    
		$this->db->where('(pcc_result.var_filter_status = "wip" or pcc_result.var_filter_status = "WIP")');
		

		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like('candidates_info.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('candidates_info.cmp_ref_no', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where['search']['value']);
		}


	
		$this->db->limit($where['length'],$where['start']);
		
		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 
           
			$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		}

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_all_pcc_record_datatable_count_assign($empver_aary = array(),$where,$columns)
	{
		$this->db->select("candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = pcc.has_case_id) as user_name,status.status_value,pcc.pcc_com_ref,pcc.address_type, pcc.street_address,pcc.city,pcc.pincode,pcc.state,pcc_result.first_qc_approve,pcc_result.first_qc_updated_on,pcc_result.first_qu_reject_reason,pcc.id,pcc.has_assigned_on,pcc.iniated_date,clients.clientname,(select created_on from pcc_activity_data where comp_table_id = pcc.id  order by id desc limit 1) as last_activity_date");

		$this->db->from('pcc');

		$this->db->join("pcc_result",'pcc_result.pcc_id = pcc.id');
		
		$this->db->join("candidates_info",'candidates_info.id = pcc.candsid');
		
		$this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("status",'status.id = pcc_result.verfstatus');

		$this->db->where('pcc.vendor_id =',0);
        
        $this->db->where('pcc.mode_of_veri !=','verbal');
	    
		$this->db->where('(pcc_result.var_filter_status = "wip" or pcc_result.var_filter_status = "WIP")');



		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like('candidates_info.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('candidates_info.cmp_ref_no', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where['search']['value']);
		}

	   				
		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 

			$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		}

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_all_pcc_record($where_array)
	{
		$this->db->select("candidates_info.id as cands_id,pcc.id as pcc_id,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = pcc.has_case_id) as executive_name,status.status_value as verfstatus,pcc.pcc_com_ref,pcc.address_type, pcc.street_address,pcc.city,pcc.pincode,pcc.state as state_id,pcc.references,pcc.references_no,pcc_result.id as pcc_result_id,pcc_result.first_qc_approve,pcc_result.first_qc_updated_on,pcc_result.first_qu_reject_reason,pcc.id,pcc.has_assigned_on,pcc.iniated_date,(select created_on from pcc_activity_data where comp_table_id = pcc.id  order by id desc limit 1) as last_activity_date,due_date,tat_status,(select clientname from clients where clients.id = candidates_info.clientid) as clientname,pcc.state,pcc_result.closuredate,pcc_result.mode_of_verification,pcc_result.application_id_ref,pcc_result.submission_date,pcc_result.police_station,pcc_result.police_station_visit_date,pcc_result.name_designation_police,pcc_result.contact_number_police,pcc_result.remarks,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,");

		$this->db->from('pcc');

		$this->db->join("pcc_result",'pcc_result.pcc_id = pcc.id');
		
		$this->db->join("candidates_info",'candidates_info.id = pcc.candsid');
		
		$this->db->join("status",'status.id = pcc_result.verfstatus');

		$this->db->where($where_array);

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function pcc_com_ref()
	{
		$result = $this->db->select("SUBSTRING_INDEX(pcc_com_ref, '-',-1) as A_I")->order_by('id','desc')->limit(1)-> get($this->tableName)->row_array();
		return $result;
	}

	public function uploaded_files($files_arry, $return_insert_ids = FALSE)
	{
		$res =  $this->db->insert_batch('pcc_files', $files_arry);
		
		record_db_error($this->db->last_query());
		
		return $res;
	}

	public function delete_uploaded_file($where = array())
	{	
		$this->db->where_in('id',$where);

		$this->db->set('status', STATUS_DELETED);

		$result = $this->db->update('pcc_files', array('status' => STATUS_DELETED));

		record_db_error($this->db->last_query());

		return $result;
	}

	public function get_pcc_uploded_files($where_array)
	{
		$this->db->select('*');

		$this->db->from('pcc_files');

		$this->db->where($where_array);

		$this->db->order_by('serialno','asc');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();	
	}

	public function upload_file_update($tableName = 'pcc_files',$updateArray)
	{
		$this->db->update_batch($tableName,$updateArray, 'id');
		return true; 
	}

	public function select_insuff($where_array)
	{
		$this->db->select('*')->from('pcc_insuff');

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

			$result = $this->db->update('pcc_insuff', $arrdata);
			
			record_db_error($this->db->last_query());
			
			return $result;
	    }
	    else
	    {
			$this->db->insert('pcc_insuff', $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	    }
	}

	public function select_insuff_join($where_array)
	{
		$this->db->select('pcc_insuff.*,(select user_name from user_profile where id =  pcc_insuff.created_by limit 1) as insuff_raised_by,(select user_name from user_profile where id = pcc_insuff.insuff_cleared_by limit 1) as insuff_cleared_by');

		$this->db->from('pcc_insuff');

		$this->db->where($where_array);
		
		$this->db->where('pcc_insuff.status !=',3);

		$this->db->order_by('pcc_insuff.id','asc');

		return $this->db->get()->result_array();
	}
	
	public function save_update_result($arrdata,$arrwhere = array())
	{
	    if(!empty($arrwhere)) {
			$this->db->where($arrwhere);
			return $this->db->update('pcc_result', $arrdata);
	    } else {
			$this->db->insert('pcc_result', $arrdata);
			return $this->db->insert_id();
	    }
	}

	public function export_sql($filter) { 
	
	$sql = "SELECT (select clientname from clients where clients.id = candidates_info.clientid limit 1) as
		client_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name, ClientRefNumber,cmp_ref_no,CandidateName,DATE_FORMAT(caserecddate,'%d-%m-%Y') as caserecddate, (select status_value from status where status.id = pcc_result.verfstatus limit 1) as verfstatus,pcc_com_ref,(select user_name from user_profile where user_profile.id = pcc.has_case_id) as executive_name,address_type,street_address,city,pincode,state,vendor_id,mode_of_verification,application_id_ref,DATE_FORMAT(submission_date,'%d-%m-%Y') as submission_date,police_station,DATE_FORMAT(police_station_visit_date,'%d-%m-%Y') as police_station_visit_date,name_designation_police,contact_number_police,
			pcc_result.remarks,DATE_FORMAT(iniated_date,'%d-%m-%Y') as iniated_date,DATE_FORMAT(due_date,'%d-%m-%Y') as due_date,tat_status,first_qc_updated_on,DATE_FORMAT(closuredate,'%d-%m-%Y') as closuredate,first_qc_approve,(select created_on from pcc_activity_data where comp_table_id = pcc.id order by id desc limit 1) as last_activity_date,(select count(file_name) from pcc_files where pcc_files.pcc_id = pcc_result.id and type = 1) as file_name
		FROM pcc 
		INNER JOIN candidates_info ON candidates_info.id = pcc.candsid 
		INNER JOIN pcc_result ON pcc_result.pcc_id = pcc.id ".$filter." ";
		
		$query = $this->db->query($sql);

		return $query->result_array();
	}
	
	public function upload_vendor_assign($tableName,$updateArray,$where_arry)
	{		
		$this->db->where($where_arry);
	 	$this->db->update($tableName,$updateArray);
		return $this->db->affected_rows();
	}

	public function vendor_logs($where_array)
	{
		$this->db->select('vendor_master_log.status,vendor_master_log.id,trasaction_id,component,(select user_name from user_profile where user_profile.id = pcc_vendor_log.created_by) as allocated_by,pcc_vendor_log.created_on allocated_on,(select user_name from user_profile where user_profile.id = pcc_vendor_log.approval_by) as approval_by,pcc_vendor_log.modified_on approval_on,(select vendor_name from vendors where vendors.id= pcc_vendor_log.vendor_id) as vendor_name, pcc_com_ref as component_ref,ClientRefNumber,cmp_ref_no,costing,vendor_master_log.tat_status');

		$this->db->from('vendor_master_log');

		$this->db->join('pcc_vendor_log','pcc_vendor_log.id = vendor_master_log.case_id');

		$this->db->join('pcc','pcc.id = pcc_vendor_log.case_id');

		$this->db->join('candidates_info','candidates_info.id = pcc.candsid');

		$this->db->where($where_array);

		$this->db->order_by('pcc_vendor_log.id', 'desc');

		$pcc  = $this->db->get()->result_array();

		return $this->db->get()->result_array();
	}

	public function select_client_list_assign_pcc_view($tableName,$return_as_strict_row,$select_array)
	{
		$this->db->select($select_array);

		$this->db->from($tableName);

        $this->db->join("pcc",'pcc.clientid = clients.id');


		$this->db->join("pcc_result",'pcc_result.pcc_id =pcc.id');
		
		$this->db->join("candidates_info",'candidates_info.id = pcc.candsid');
		

		$this->db->join("status",'status.id = pcc_result.verfstatus');

		$this->db->where('(pcc_result.var_filter_status = "wip" or pcc_result.var_filter_status = "WIP")');
		
        $this->db->where('pcc.vendor_id =',0);

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
}
?>