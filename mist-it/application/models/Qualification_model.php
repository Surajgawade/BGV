<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Qualification_model extends CI_Model
{
	function __construct()
    {
		$this->tableName = 'qualification_master';

		$this->primaryKey = 'id';
	}

	public function select($return_as_strict_row,$select_array, $where_array = array())
	{
		$this->db->select($select_array);

		$this->db->from($this->tableName);

		$this->db->where($where_array);

		$this->db->order_by('qualification', 'asc');

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

	public function check_exits($fields)
	{	
		$this->db->select('id');

		$this->db->from($this->tableName);

		$this->db->where($fields);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());

		$result_array = $result->row();
		
	    if($result_array != "")
	    {
	        return $result_array->id;
	    }
	    else
	    {
	    	$this->db->insert($this->tableName, $fields);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	    }	
	}
}
?>