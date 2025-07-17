<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Crimver_model extends CI_Model
{
	function __construct()
    {
		//parent::__construct();

		$this->tableName = 'crimver';

		$this->primaryKey = 'id';
	}

	public function select($return_as_strict_row,$select_array, $where_array = array())
	{
        if(empty($select_array))
		{
			array_push($select_array, '*');
		}

		$select = implode(",",$select_array);

		$this->db->select($select);

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

	public function get_all_criminal_data_by_client($where_arry = array())
	{
		$this->db->select("crimver.*,clients.clientname,cands.ClientRefNumber,cands.cmp_ref_no,cands.CandidateName,membership_groups.name,crimverres.verfstatus");

		$this->db->from('crimver');

		$this->db->join("clients",'clients.id = crimver.clientid');

		$this->db->join("cands",'cands.id = crimver.candsid');

		$this->db->join("membership_groups",'membership_groups.groupID = crimver.assignedto');

		$this->db->join("crimverres",'crimverres.crimverid = crimver.id','left');

		if($where_arry)
		{
			$this->db->where($where_arry);
		}

		$this->db->order_by('crimver.id', 'desc');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function delete($arrwhere)
	{
	  $result =  $this->db->delete($this->tableName, $arrwhere);

	  record_db_error($this->db->last_query());
	  
	  return $result;
	}
}
?>
