<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SLA_default_setting_model extends CI_Model
{
	function __construct()
    {
		$this->tableName = 'sla_default_setting';

		$this->primaryKey = 'id';
	}

	public function select($return_as_strict_row,$select_array = array('*'), $where_array = array())
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

	public function select_scope_of_word($return_as_strict_row,$select_array = array('*'), $where_array = array())
	{
		$this->db->select($select_array);

		$this->db->from('client_scope_of_work');

		$this->db->where($where_array);

		$this->db->order_by('id', 'desc');

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

	public function save_scope_of_work($arrdata,$arrwhere = array())
	{
		if(!empty($arrwhere))
		{
			$this->db->where($arrwhere);

			$result = $this->db->update('client_scope_of_work', $arrdata);

			record_db_error($this->db->last_query());

			return $result;
		}
		else
		{
			$this->db->insert('client_scope_of_work', $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
		}
	}

	public function select_mode_of_verification($return_as_strict_row,$select_array = array('*'), $where_array = array())
	{
		$this->db->select($select_array);

		$this->db->from('client_mode_of_verification');

		$this->db->where($where_array);

		$this->db->order_by('id', 'desc');

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

	public function save_mode_of_verification($arrdata,$arrwhere = array())
	{
		if(!empty($arrwhere))
		{
			$this->db->where($arrwhere);

			$result = $this->db->update('client_mode_of_verification', $arrdata);

			record_db_error($this->db->last_query());

			return $result;
		}
		else
		{
			$this->db->insert('client_mode_of_verification', $arrdata);

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

	public function sla_setting($where_array = array())
	{
		$this->db->select('sla_default_setting.id,sla_default_setting.particulars,sla_default_setting.section,sla_default_setting.selected_selection,sla_default_setting.remarks,components.component_name,components.id as component_id,components.component_name');

		$this->db->from('components');

		$this->db->join('sla_default_setting','components.id = sla_default_setting.component_id');

		$this->db->where($where_array);
		 
		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function scope_of_work_group_by($where_array = array())
	{
		$this->db->select('component_key,component_id,GROUP_CONCAT(client_scope_of_work.id) as id,GROUP_CONCAT(scop_of_word) as scop_of_word');

		$this->db->from('components');

		$this->db->join('client_scope_of_work','components.id = client_scope_of_work.component_id','left');

		$this->db->where($where_array);
		
		$this->db->group_by('components.id');

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function mode_of_veri_group_by($where_array = array())
	{
		$this->db->select('component_key,component_id,GROUP_CONCAT(client_mode_of_verification.id) as id,GROUP_CONCAT(client_mode_of_verification.mode_of_verification) as mode_of_verification');

		$this->db->from('components');

		$this->db->join('client_mode_of_verification','components.id = client_mode_of_verification.component_id','left');

		$this->db->where($where_array);
		
		$this->db->group_by('components.id');

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}	
}