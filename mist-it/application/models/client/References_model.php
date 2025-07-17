<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class References_model extends CI_Model
{
	function __construct()
    {
		$this->tableName = 'refver';

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

	public function cand_reference_details_view($where_arry = array())
	{
		$this->db->select("reference.*,IF(ref1.verfstatus IS NOT NULL, ref1.verfstatus ,'WIP') as verfstatus,candidates_info.caserecddate,candidates_info.CandidateName,candidates_info.ClientRefNumber,status.status_value");

		$this->db->from('candidates_info');

		$this->db->join('reference','reference.candsid = candidates_info.id');


		$this->db->join('reference_result ref1','ref1.reference_id	= reference.id','left');

		$this->db->join("reference_result as ref2",'(ref2.reference_id = reference.id and ref1.id < ref2.id)','left');
		
		$this->db->where('ref2.verfstatus is null');	


		$this->db->join("status",'(status.id = ref1.verfstatus)');

		if($where_arry)
		{
			$this->db->where($where_arry);
		}
			
		$result = $this->db->get();
		
		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function cand_reference_details($where_arry = array(),$comp)
	{
		$this->db->select("reference.*,IF(ref1.verfstatus IS NOT NULL, ref1.verfstatus ,'WIP') as verfstatus,candidates_info.caserecddate,candidates_info.CandidateName,candidates_info.ClientRefNumber,status.status_value");

		$this->db->from('candidates_info');

		$this->db->join('reference','reference.candsid = candidates_info.id');


		$this->db->join('reference_result ref1','ref1.reference_id	= reference.id','left');

		$this->db->join("reference_result as ref2",'(ref2.reference_id = reference.id and ref1.id < ref2.id)','left');
		
		$this->db->where('ref2.verfstatus is null');	


		$this->db->join("status",'(status.id = ref1.verfstatus)');

		if($where_arry)
		{
			$this->db->where($where_arry);
		}

		if($comp)
		{
			$this->db->where('reference.reference_com_ref',$comp);
		}
			
		$result = $this->db->get();
		
		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}
}
?>