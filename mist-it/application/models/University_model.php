<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class University_model extends CI_Model
{
	function __construct()
    {
		$this->tableName = 'university_master';

		$this->primaryKey = 'id';
	}

	public function select($return_as_strict_row,$select_array, $where_array = array())
	{
		$this->db->select($select_array);

		$this->db->from($this->tableName);

		$this->db->where($where_array);

		$this->db->order_by('universityname', 'asc');

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
     
    public function select_table($tableName,$select_array, $where_array = array())
	{
		$this->db->select($select_array);

		$this->db->from($tableName);

		$this->db->where($where_array);


		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		$result_array = $result->result_array();

      
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

	public function check_university_exits($fields)
	{	
		$this->db->select('id')->from($this->tableName)->where($fields);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());

		$result_array = $result->row();
		
	    if($result_array != "") {
	        return $result_array->id;
	    } else {
	    	$this->db->insert($this->tableName, $fields);
			return $this->db->insert_id();
	    }
	}

	public function select_fake_university($return_as_strict_row,$select_array, $where_array = array())
	{
		$this->db->select($select_array);

		$this->db->from('fake_university');

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

	public function insert_fake_university($arrdata,$arrwhere = array())
	{
		if(!empty($arrwhere))
		{
			$this->db->where($arrwhere);

			$result = $this->db->update('fake_university', $arrdata);

			record_db_error($this->db->last_query());

			return $result;
		}
		else
		{
			$this->db->insert('fake_university', $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
		}
	}

	public function fake_university_insert_batch($details)
	{	
        foreach ($details as $key => $value) 
        {
			$this->db->trans_start();

			$insert_query = $this->db->insert_string('fake_university', $value);

			$insert_query = str_replace('INSERT INTO', 'INSERT IGNORE INTO', $insert_query);

			$this->db->query($insert_query);

			$this->db->trans_complete();
        }
	}


    public function select_vendor_list($component_name)
    {
        $result = $this->db->select('id,vendor_name')->from('vendors')->where("vendors_components LIKE '%".$component_name."%' ")->where('vendors.education_verification_status',1)->get();
        
        return $result->result_array();
        
    }

    
	public function uploaded_files($files_arry, $return_insert_ids = FALSE)
	{
		if($return_insert_ids)
		{
			$this->db->insert_batch('university_master_image', $files_arry);

			record_db_error($this->db->last_query());

			$first_id = $this->db->insert_id();

			$affected_rows = $this->db->affected_rows();

			if($affected_rows > 0)
			{
				return range($first_id,($first_id+$affected_rows-1));
			}
			else
			{
				return array();
			}
		}
		else
		{
			$res =  $this->db->insert_batch('university_master_image', $files_arry);
			
			record_db_error($this->db->last_query());
			
			return $res;
		}
	}
}
?>