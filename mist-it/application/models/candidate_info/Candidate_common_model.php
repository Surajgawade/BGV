<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Candidate_common_model extends CI_Model {

	public function select($tableName, $return_as_strict_row,$select_array, $where_array=array())
	{
		$this->db->select($select_array);

		$this->db->from($tableName);

        $this->db->where($where_array); 
        
        $result_array = $this->db->get()->result_array();	

        if($return_as_strict_row)
		{
            if(count($result_array) >0 ) // ensure only one record has been previously inserted
            {
                $result_array = $result_array[0];
            }
        }
        return $result_array;
	}

    public function select1($tableName, $return_as_strict_row,$select_array, $where_array=array())
    {
        $this->db->select($select_array);

        $this->db->from($tableName);

        $this->db->where($where_array); 
        
        $result_array = $this->db->get()->result_array();   

        if($return_as_strict_row)
        {
            if(count($result_array) >0 ) // ensure only one record has been previously inserted
            {
                $result_array = $result_array[0];
            }
        }
        return $result_array[0];
    }

    public function client_component($where_array)
    {   
        $this->db->select('component_name,component_key,show_component_name,vendor_icon');

        $this->db->from('components_client');

        $this->db->where_in('component_key',$where_array); 

        $this->db->where('status',1); 

        $this->db->order_by('serial_id','asc');

        $result = $this->db->get();

        return $result->result_array();
    }



    public function delete($tableName, $arrwhere)
	{
		return $this->db->delete($tableName, $arrwhere);	
	}
}
?>