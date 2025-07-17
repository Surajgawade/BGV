<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vendors_model extends CI_Model
{
	function __construct()
    {
		$this->tableName = 'vendors';

		$this->primaryKey = 'id';
	}

	public function select($return_as_strict_row,$select_array = array('*'), $where_array = array())
	{
		$this->db->select($select_array);

		$this->db->from($this->tableName);

		$this->db->where($where_array);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		$result_array = $result->result_array();

        if($return_as_strict_row){
            if(count($result_array) == 1) // ensure only one record has been previously inserted
            {
                $result_array  = $result_array[0];
            }
        }
        return $result_array;
	}

	public function select_join($where_array = array())
	{
		$this->db->select('vendors.*,user_profile.user_name');

		$this->db->from('vendors');

		$this->db->join("user_profile","user_profile.id = vendors.created_by");
		
		$this->db->where($where_array);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}


    public function get_vendor_list($where_array = array(),$where,$columns)
	{
        $this->db->select('vendors.*,user_profile.user_name');

		$this->db->from('vendors');

		$this->db->join("user_profile","user_profile.id = vendors.created_by");

		if(isset($where['filter_by_status']) &&  $where['filter_by_status'] != '')	
		{  
		    $filter_by_status  =  $where['filter_by_status'];
	          
		    $where_status = "vendors.status = $filter_by_status";
               
            $this->db->where($where_status); 

		}

		 
        $this->db->where($where_array);

        if(is_array($where) && $where['search']['value'] != "" )
		{
			$this->db->like('vendors.vendor_name', $where['search']['value']);
			
		}
		
        if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			
			$order_by = $where['order'][0]['dir']; 

			$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		}
		else
		{
			$this->db->order_by('vendors.id','DESC');
		}

		$this->db->limit($where['length'],$where['start']);

		$result = $this->db->get();

		record_db_error($this->db->last_query());

	    return $result->result_array();


	
	}

	public function get_vendor_list_count($where_array = array(),$where,$columns)
	{
        $this->db->select('vendors.*,user_profile.user_name');

		$this->db->from('vendors');

		$this->db->join("user_profile","user_profile.id = vendors.created_by");

		if(isset($where['filter_by_status']) &&  $where['filter_by_status'] != '')	
		{  
		    $filter_by_status  =  $where['filter_by_status'];
	          
		    $where_status = "vendors.status = $filter_by_status";
               
             $this->db->where($where_status); 
		}

		$this->db->where($where_array);

		if(is_array($where) && $where['search']['value'] != "" )
		{
			$this->db->like('vendors.vendor_name', $where['search']['value']);
			
		}
		
		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			
			$order_by = $where['order'][0]['dir']; 

			$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		}
		else
		{
			$this->db->order_by('vendors.id','DESC');
		}
		
		$result = $this->db->get();


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

	public function insert_batch_vendors_login($files_arry)
	{
		return $this->db->insert_batch('vendors_login', $files_arry);	
	}
    
    public function insert_batch_vendors_spoc($files_arry)
	{
		return $this->db->insert_batch('vendor_spoc_details', $files_arry);	
	}

	public function vendor_account($where)
	{
		$this->db->select('*')->from('vendors_login')->where($where);
		
		return $this->db->get()->result_array();
	}

	public function select_vendor_spoc_details($where_arry)
    {
        return $this->db->select('*')->from('vendor_spoc_details')->where($where_arry)->get()->result_array();
    }

    public function select_vendor_state($id)
    {
        return $this->db->select('address_state')->from('vendors')->where('vendors.id !=', $id)->get()->result_array();
    }
    
    public function select_vendor_employment_state($id)
    {
        return $this->db->select('employment_states')->from('vendors')->where('vendors.id !=', $id)->get()->result_array();
    }

    public function select_vendor_court_client($id)
    {
        return $this->db->select('court_client')->from('vendors')->where('vendors.id !=', $id)->get()->result_array();
    }

    public function select_vendor_global_client($id)
    {
        return $this->db->select('global_client')->from('vendors')->where('vendors.id !=', $id)->get()->result_array();
    }
   
    public function select_vendor_credit_client($id)
    {
        return $this->db->select('credit_client')->from('vendors')->where('vendors.id !=', $id)->get()->result_array();
    }

     public function select_vendor_drugs_panel($id)
    {
        return $this->db->select('panel_code')->from('vendors')->where('vendors.id !=', $id)->get()->result_array();
    }

    public function select_vendor_state1()
    {
        return $this->db->select('address_state')->from('vendors')->get()->result_array();
    }

    public function select_vendor_employment_state1()
    {
        return $this->db->select('employment_states')->from('vendors')->get()->result_array();
    }

    public function select_vendor_court_client1()
    {
        return $this->db->select('court_client')->from('vendors')->get()->result_array();
    }

    public function select_vendor_global_client1()
    {
        return $this->db->select('global_client')->from('vendors')->get()->result_array();
    }
    
    public function select_vendor_credit_client1()
    {
        return $this->db->select('credit_client')->from('vendors')->get()->result_array();
    } 

     public function select_vendor_drugs_panel1()
    {
        return $this->db->select('panel_code')->from('vendors')->get()->result_array();
    } 
    
	public function save_update($table_name,$arrdata,$arrwhere = array())
	{
	    if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update($table_name, $arrdata);

			record_db_error($this->db->last_query());

			return $result;
	    }
	    else
	    {
			$this->db->insert($table_name, $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	    }
	}

}
?>