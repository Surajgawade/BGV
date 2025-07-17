<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Company_database_model extends CI_Model
{
	function __construct()
    {
		$this->tableName = 'company_database';

		$this->primaryKey = 'id';
	}

	public function select($return_as_strict_row,$select_array, $where_array = array())
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

	public function get_company_details($arrwhere = array())
	{ 
		$this->db->select("company_database.*");

		$this->db->from('company_database');

		$this->db->where($arrwhere);
		
		$result = $this->db->get();
		
		record_db_error($this->db->last_query());
		
		return $result->result_array();
 
	}

	public function save_company($arrdata,$arrwhere = array())
	{
	    if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update('company_database', $arrdata);

			record_db_error($this->db->last_query());

			return $result;
	    }
	    else
	    {
			$this->db->insert('company_database', $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	    }
	}

	public function save($arrdata,$arrwhere = array())
	{
	    if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update('company_database_verifiers_details', $arrdata);

			record_db_error($this->db->last_query());

			return $result;
	    }
	    else
	    {
			$this->db->insert('company_database_verifiers_details', $arrdata);

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

	public function get_all_company_for_datatable($where,$columns)
	{
		$this->db->select("company_database.*");

		$this->db->from('company_database');

	   // $this->db->join("company_database_verifiers_details",'company_database_verifiers_details.company_database_id = company_database.id');


		$this->db->where('company_database.status', 1);

		if(isset($where['filter_by_status']) &&  $where['filter_by_status'] != 'all')	
		{  
		    $filter_by_status  =  $where['filter_by_status'];
	          
		    $where_status = "company_database.dropdown_status = $filter_by_status";
               
            $this->db->where($where_status); 

		}		

		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like('company_database.cin_number', $where['search']['value']);

			$this->db->or_like('company_database.coname', $where['search']['value']);

			$this->db->or_like('company_database.address', $where['search']['value']);

			$this->db->like('company_database.city', $where['search']['value']);

			$this->db->or_like('company_database.state', $where['search']['value']);

			$this->db->or_like('company_database.pincode', $where['search']['value']);
		}

		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			
			$order_by = $where['order'][0]['dir']; 

			$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		}
		else
		{
			$this->db->order_by('company_database.id','DESC');
		}

		$this->db->limit($where['length'],$where['start']);

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_all_company_for_datatable_count($where,$columns)
	{
		$this->db->select("company_database.*");

		$this->db->from('company_database');


	  
		$this->db->where('company_database.status', 1);

		if(isset($where['filter_by_status']) &&  $where['filter_by_status'] != 'all')	
		{  
		    $filter_by_status  =  $where['filter_by_status'];
	          
		    $where_status = "company_database.dropdown_status = $filter_by_status";
               
            $this->db->where($where_status); 

		}			

		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like('company_database.cin_number', $where['search']['value']);

			$this->db->or_like('company_database.coname', $where['search']['value']);

			$this->db->or_like('company_database.address', $where['search']['value']);

			$this->db->like('company_database.city', $where['search']['value']);

			$this->db->or_like('company_database.state', $where['search']['value']);

			$this->db->or_like('company_database.pincode', $where['search']['value']);
		}
		
		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			
			$order_by = $where['order'][0]['dir']; 

			$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		}
		else
		{
			$this->db->order_by('company_database.id','DESC');
		}

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function verifiers_details_save($arrdata,$arrwhere = array())
	{
	    if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update('company_database_verifiers_details', $arrdata);

			record_db_error($this->db->last_query());

			return $result;
	    }
	    else
	    {
			$this->db->insert('company_database_verifiers_details', $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	    }
	}

	public function get_hr_database_details($where_arry = array())
	{
		$this->db->select("company_database_verifiers_details.*");

		$this->db->from('company_database_verifiers_details');

	
		if($where_arry)
		{
			$this->db->where($where_arry);
		}
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_hr_database_details_bk($where_arry = array())
	{
		$this->db->select("company_database_verifiers_details_bk.*");

		$this->db->from('company_database_verifiers_details_bk');

	    $this->db->like('company_database_verifiers_details_bk.company_name', $where_arry);

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	} 

}