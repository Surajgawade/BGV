<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reference_vendor_log_model extends CI_Model
{
	function __construct()
    {
		$this->tableName = 'reference_vendor_log';

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

	public function get_new_list($where_array = array())
	{
		$this->db->select('reference_vendor_log.id,(select vendor_name from vendors where vendors.id= reference_vendor_log.vendor_id) as vendor_name,(select user_name from user_profile where user_profile.id = reference_vendor_log.created_by) as allocated_by,reference_vendor_log.created_on as allocated_on,name_of_reference,contact_no,reference_com_ref,reference.id as case_id');

		$this->db->from('reference_vendor_log');

		$this->db->join('reference','reference.id = reference_vendor_log.case_id');

		$this->db->where($where_array);

		$this->db->order_by('reference_vendor_log.id', 'desc');

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
