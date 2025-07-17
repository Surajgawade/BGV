<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_menus_model extends CI_Model
{
	function __construct()
    {
		$this->tableName = 'admin_menus';

		$this->primaryKey = 'id';
	}

	public function select($return_as_strict_row,$select_array, $where_array = array())
	{
		$this->db->select($select_array);

		$this->db->from($this->tableName);

		$this->db->where($where_array);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		$result_array = $result->result_array();

        if($return_as_strict_row)
		{
            if(count($result_array) == 1) // ensure only one record has been previously inserted g00gle@123
            {
                $result_array  = $result_array[0];
            }
        }
        return $result_array;
	}

	public function get_user_permission($where_in)
	{
		$this->db->select('GROUP_CONCAT(DISTINCT tbl_admin_menu_id) as admin_menu_id');

		$this->db->from('groups');

		$this->db->where("groups.id IN (".$where_in.")",NULL, false);
		
		$result  = $this->db->get()->result_array();

		if(!empty($result))
		{
			$this->db->select('*');

			$this->db->from('admin_menus');
			
			$this->db->where("admin_menus.parent_id",0);

			$this->db->where("admin_menus.id IN (".$result[0]['admin_menu_id'].")",NULL, false);
			$this->db->order_by('serial_no','asc');
			$return['menu'] = $this->db->get()->result_array();

			$this->db->select('*');

			$this->db->from('admin_menus');
			
			$this->db->where("admin_menus.parent_id !=",0 And "status =", 1);
			$this->db->where("status =", 1);

			$this->db->where("admin_menus.id IN (".$result[0]['admin_menu_id'].")",NULL, false);

			$return['submenu'] = $this->db->get()->result_array();

			$return['access_page_id'] = $result[0]['admin_menu_id'];
		}
		return $return;
	}

	
}
?>
