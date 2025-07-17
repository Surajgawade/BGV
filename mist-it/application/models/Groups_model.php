<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Groups_model extends CI_Model
{
	function __construct()
    {
		$this->tableName = 'groups';

		$this->primaryKey = 'id';
	}

	public function select($return_as_strict_row,$select_array = array('*'), $where_array = array())
	{
		$this->db->select($select_array);

		$this->db->from($this->tableName);

		$this->db->where($where_array);

		$this->db->order_by('group_name');

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

	public function select_join($where_array = array(),$where,$columns)
	{
		$this->db->select('groups.*,user_profile.user_name');

		$this->db->from('groups');

		$this->db->join("user_profile","user_profile.id = groups.created_by");
		
		$this->db->where($where_array);


		if(!empty($where['order']))
		{
          
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir'];
           
			$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		}
		else
		{
			$this->db->order_by('groups.id','ASC');
		}

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	
	public function select_join_menu($where_array)

	{

		$this->db->select('name');

		$this->db->from('admin_menus');
		
		$this->db->where($where_array);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
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








	public function delete_permission($where_id)
	{
		$result = $this->db->delete('groups_permissions',$where_id);

		record_db_error($this->db->last_query());
	  
	  	return $result;
	}

	public function multiple_insert($data_arr)
	{
		return $this->db->insert_batch('groups_permissions', $data_arr);
	}

	public function group_permission_insert($data_arr)
	{
		return $this->db->insert('groups_permissions', $data_arr);
	}
}
?>
