<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CG_model extends CI_Model
{
	function __construct()
    {
		$this->tableName = 'cg';

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

	public function get_all_cg_record_datatable($empver_aary = array(),$where,$columns)
	{
		$this->db->select("ClientRefNumber,cg_ref_no,iniated_date,company_customer_name,street_address,city,pincode,state,(select user_name from user_profile where user_profile.id = cg.has_case_id) as user_name,status.status_value,clients.clientname,(select created_on from kyc_activity_data where comp_table_id = cg.id order by id desc limit 1) as last_activity_date,cg_res.remarks,due_date,tat_status,closuredate,cg_id,cg_res.id as cg_res_id,cg.id ");

		$this->db->from('cg');

		$this->db->join("cg_res",'cg_res.cg_id =cg.id');
				
		$this->db->join("clients",'clients.id = cg.clientid');

		$this->db->join("status",'status.id = cg_res.verfstatus');

		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like('ClientRefNumber', $where['search']['value']);

			$this->db->or_like('cg_ref_no', $where['search']['value']);

			$this->db->or_like('company_customer_name', $where['search']['value']);
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

	public function get_all_cg_record_datatable_count($empver_aary = array(),$where,$columns)
	{
		$this->db->select("ClientRefNumber,cg_ref_no,iniated_date,company_customer_name,street_address,city,pincode,state,(select user_name from user_profile where user_profile.id = cg.has_case_id) as user_name,status.status_value,clients.clientname,(select created_on from kyc_activity_data where comp_table_id = cg.id order by id desc limit 1) as last_activity_date,cg_res.remarks,due_date,tat_status,closuredate,cg_id,cg_res.id as cg_res_id,cg.id");

		$this->db->from('cg');

		$this->db->join("cg_res",'cg_res.cg_id =cg.id');
				
		$this->db->join("clients",'clients.id = cg.clientid');

		$this->db->join("status",'status.id = cg_res.verfstatus');

		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like('ClientRefNumber', $where['search']['value']);

			$this->db->or_like('cg_ref_no', $where['search']['value']);

			$this->db->or_like('company_customer_name', $where['search']['value']);
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

	public function select_insuff($where_array)
	{
		$this->db->select('*')->from('cg_insuff');

		$this->db->where($where_array);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function save_update_insuff($arrdata,$arrwhere = array())
	{
		if(!empty($arrwhere)) {

			$this->db->where($arrwhere);
			$result = $this->db->update('cg_insuff', $arrdata);
			record_db_error($this->db->last_query());
			return $result;

	    } else {

			$this->db->insert('cg_insuff', $arrdata);
			record_db_error($this->db->last_query());
			return $this->db->insert_id();
			
	    }
	}

	public function upload_file_update($tableName = 'cg_files',$updateArray)
	{
		$this->db->update_batch($tableName,$updateArray, 'id');
		return true; 
	}



















	public function get_all_identity_record($where_array)
	{
		$this->db->select("candidates_info.id as cands_id,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = identity.has_case_id) as executive_name,status.status_value as verfstatus,identity.id as identity_id,identity.has_case_id, identity.identity_com_ref,identity.doc_submited,identity.id_number, identity.street_address,identity.city,identity.pincode,identity.state as state_id,identity_result.id as identity_result_id,identity_result.first_qc_approve,identity_result.first_qc_updated_on,identity_result.first_qu_reject_reason,identity_result.mode_of_verification,identity_result.remarks,identity_result.closuredate,identity.has_assigned_on,identity.iniated_date,clients.clientname,(select created_on from identity_activity_data where comp_table_id = identity.id  order by id desc limit 1) as last_activity_date,due_date,tat_status,(select state from states where states.id= identity.state) as state,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name");

		$this->db->from('identity');

		$this->db->join("identity_result",'identity_result.identity_id = identity.id');
		
		$this->db->join("candidates_info",'candidates_info.id = identity.candsid');
		
		$this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("status",'status.id = identity_result.verfstatus');

		$this->db->where($where_array);

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function cg_ref_no()
	{
		$result = $this->db->select("SUBSTRING_INDEX(cg_ref_no, '-',-1) as A_I")->order_by('id','desc')->limit(1)-> get($this->tableName)->row_array();
		return $result;
	}

	public function uploaded_files($files_arry, $return_insert_ids = FALSE)
	{
		$res =  $this->db->insert_batch('identity_files', $files_arry);
		
		record_db_error($this->db->last_query());
		
		return $res;
	}

	public function delete_uploaded_file($where = array())
	{	
		$this->db->where_in('id',$where);

		$this->db->set('status', STATUS_DELETED);

		$result = $this->db->update('identity_files', array('status' => STATUS_DELETED));

		record_db_error($this->db->last_query());

		return $result;
	}

	public function get_court_uploded_files($where_array)
	{
		$this->db->select('*');

		$this->db->from('identity_files');

		$this->db->where($where_array);

		$this->db->order_by('serialno','asc');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();	
	}



	public function select_insuff_join($where_array)
	{
		$this->db->select('identity_insuff.*,(select user_name from user_profile where id =  identity_insuff.created_by limit 1) as insuff_raised_by,(select user_name from user_profile where id = identity_insuff.insuff_cleared_by limit 1) as insuff_cleared_by');

		$this->db->from('identity_insuff');

		$this->db->where($where_array);
		
		$this->db->where('identity_insuff.status !=',3);

		$this->db->order_by('identity_insuff.id','asc');

		return $this->db->get()->result_array();
	}
	
	public function save_update_ver_result($arrdata,$arrwhere = array())
	{
		if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update('identity_result', $arrdata);

			record_db_error($this->db->last_query());
			
			return $result;
	    }
	    else
	    {
			$this->db->insert('identity_result', $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	    }
	}
}
?>
