<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Client_login_model extends CI_Model
{
	function __construct()
    {
		$this->tableName = 'client_login';

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
		$this->db->select('client_login.email_id,clients.clientname,client_login.client_id,client_login.first_name,client_login.last_name,client_login.mobile_no,client_login.profile_pic,client_login.id as client_login_id,client_login.role,client_login.client_entity_access,clients.comp_logo');

		$this->db->from('client_login');

		$this->db->join('clients','clients.id = client_login.client_id');

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
   
    public function select_client($tablename,$return_as_strict_row,$select_array, $where_array=array())
	{
		$this->db->select($select_array);

		$this->db->from($tablename);

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


   
}
?>