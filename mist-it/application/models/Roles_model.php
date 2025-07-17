<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Roles_model extends CI_Model
{
	function __construct()
    {
		$this->tableName = 'roles';

		$this->primaryKey = 'id';
	}

	public function select($return_as_strict_row,$select_array, $where_array = array())
	{
		$this->db->select($select_array);

		$this->db->from($this->tableName);

		$this->db->where($where_array);

		$this->db->order_by($this->primaryKey, 'desc');

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

	public function select_jion($where_array = array(),$where,$columns)
	{
		$this->db->select('roles.id,roles.role_name,roles.role_description,roles.created_on,roles.groups_id,user_profile.user_name,roles_permissions.*');

		$this->db->from('roles');

		$this->db->join('user_profile','user_profile.id = roles.created_by');

		$this->db->join('roles_permissions','roles_permissions.tbl_roles_id = roles.id');

		$this->db->where($where_array);

		if(!empty($where['order']))
		{
          
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir'];
           // print_r($column_name_index);
           // print_r($order_by);
			$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		}
		else
		{
			$this->db->order_by('roles.id','ASC');
		}

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

	public function roles_permission_insert($data_arr,$where_array)
    {
    	if($where_array) {

    		$this->db->where($where_array);

			return $this->db->update('roles_permissions', $data_arr);

    	} else {

    		return $this->db->insert('roles_permissions', $data_arr);

    	}
    }
     

   public function get_page_name($data_arr,$where_array)
    {
    	$this->db->select('tbl_admin_menu_id');

		$this->db->from('groups');

	

      foreach ($data_arr as $key => $value) {
      
		$this->db->or_where("id=$value");
      	
      }

      	$this->db->where($where_array);

		$result  = $this->db->get();

		

		record_db_error($this->db->last_query());

		
		return json_encode($result->result());
    }

    public function get_page_name_idwise($data_arr,$where_array)
    {
    	$this->db->select('group_name');

		$this->db->from('groups');

	

      foreach ($data_arr as $key => $value) {
      
		$this->db->or_where("id=$value");
      	
      }

      	$this->db->where($where_array);

		$result  = $this->db->get();

		

		record_db_error($this->db->last_query());

		
		return json_encode($result->result());
    }

       public function get_page_name_id($data_arr,$where_array)
    {
    	$this->db->select('name');

		$this->db->from('admin_menus');

	print_r($data_arr);

      foreach ($data_arr as $key => $value) {
      
		$this->db->or_where("id=$value");
      	
      }

      	$this->db->where($where_array);

		$result  = $this->db->get();
		record_db_error($this->db->last_query());
		
		return $result->result_array();
    }
  
  
    
}
?>