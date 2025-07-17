<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Address_vendor_log_model extends CI_Model
{
	function __construct()
    {
		$this->tableName = 'address_vendor_log';

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

        if($return_as_strict_row)
		{
            if(count($result_array) == 1) // ensure only one record has been previously inserted
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

	public function get_new_list($where_array = array(),$where1)
	{
		
	
		$this->db->select('address_vendor_log.id,address_vendor_log.case_id,(select vendor_name from vendors where vendors.id= address_vendor_log.vendor_id) as vendor_name,(select user_name from user_profile where user_profile.id = address_vendor_log.created_by) as allocated_by,address_vendor_log.created_on as allocated_on,city,state,pincode,add_com_ref,addrver.id as case_id,clients.clientname,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name, (select mode_of_verification from clients_details where clients_details.tbl_clients_id = candidates_info.clientid and clients_details.entity = candidates_info.entity and clients_details.package = candidates_info.package) as mode_of_verification');

		$this->db->from('address_vendor_log');

		$this->db->join('addrver','addrver.id = address_vendor_log.case_id');

		$this->db->join("addrverres",'addrverres.addrverid = addrver.id','left');

		$this->db->join("clients",'clients.id = addrver.clientid');

		$this->db->join("candidates_info",'candidates_info.id = addrver.candsid','left');

		$this->db->where($where_array);

	
        $this->db->where('(addrverres.var_filter_status = "wip" or addrverres.var_filter_status = "WIP")');

        if(is_array($where1) && $where1['search']['value'] != "" )
		{
			$this->db->like('candidates_info.cmp_ref_no', $where1['search']['value']);

			$this->db->or_like('candidates_info.ClientRefNumber', $where1['search']['value']);

			$this->db->or_like('addrver.add_com_ref', $where1['search']['value']);

			$this->db->or_like('addrver.iniated_date', $where1['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where1['search']['value']);

			$this->db->or_like('addrver.address', $where1['search']['value']);

			$this->db->or_like('addrver.city', $where1['search']['value']);

            $this->db->or_like('addrver.state', $where1['search']['value']);

		}

		if(!empty($where1['order']))
		{

			$column_name_index = $where1['order'][0]['column'];
			$order_by = $where1['order'][0]['dir']; 
          
			$this->db->order_by($where1['columns'][$column_name_index]['data'],$order_by);
		}
		else
		{
		
		  $this->db->order_by('address_vendor_log.id', 'desc');

		} 
 
		
		$result  = $this->db->get();

		record_db_error($this->db->last_query());
	
		return $result->result_array();
	}

	public function vendor_master_log_update_batch($tableName = 'vendor_master_log',$updateArray)
	{		
	 	$this->db->insert_batch($tableName,$updateArray);
		return $this->db->affected_rows();
	}

	public function update_batch_vendor_assign($tableName,$updateArray)
	{		
		$this->db->where('vendor_id !=','0');
	 	$this->db->update_batch($tableName,$updateArray, 'id');
		return $this->db->affected_rows();
	}
}
?>
