<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vendor_login_model extends CI_Model
{
	function __construct()
    {
		$this->tableName = 'vendors_login';

		$this->primaryKey = 'id';
	}

	public function select($return_as_strict_row,$select_array, $where_array=array())
	{
		$this->db->select($select_array);

		$this->db->from($this->tableName);

		$this->db->where($where_array);
		
		$result  = $this->db->get();

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

	public function user_components_details($where_array = array())
	{
		$this->db->select('vendors_login.id as vendors_login_id,vendors.id as vendors_id,vendors_login.email_id,vendors_login.first_name,vendors_login.last_name,vendors_login.mobile_no,vendors_login.address,vendors_login.city,vendors_login.profile_pic,vendor_name,vendor_managers,vendors_components,vendors_components_tat,price_tier_1');

		$this->db->from('vendors_login');

		$this->db->join('vendors','vendors.id = vendors_login.vendors_id');

		$this->db->where($where_array);

		$result  = $this->db->get();
		
		record_db_error($this->db->last_query());
		
		$result_array = $result->result_array();

        if(!empty($where_array) && !empty($result))
		{
         	$result_array  = $result_array[0];
        }
        return $result_array;
	}
   
}
?>