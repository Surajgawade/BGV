<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Credit_report_vendor_log_model extends CI_Model
{
	function __construct()
    {
		$this->tableName = 'courtver_vendor_log';

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

	public function delete($arrwhere)
	{
	  $result =  $this->db->delete($this->tableName, $arrwhere);

	  record_db_error($this->db->last_query());
	  
	  return $result;
	}

	public function get_new_list($where_array = array(),$where1)
	{
		$this->db->select('credit_report_vendor_log.id,(select vendor_name from vendors where vendors.id= credit_report_vendor_log.vendor_id) as vendor_name,(select user_name from user_profile where user_profile.id = credit_report_vendor_log.created_by) as allocated_by,credit_report_vendor_log.created_on as allocated_on,credit_report_com_ref,street_address,city,pincode,state,case_id,(select clientname from clients where clients.id = candidates_info.clientid limit 1) as clientname,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name');

		$this->db->from('credit_report_vendor_log');

		$this->db->join('credit_report','credit_report.id = credit_report_vendor_log.case_id');

		$this->db->join("credit_report_result",'credit_report_result.credit_report_id = credit_report.id','left');

		$this->db->join("candidates_info",'candidates_info.id = credit_report.candsid','left');

		$this->db->where($where_array);

        $this->db->where('(credit_report_result.var_filter_status = "wip" or credit_report_result.var_filter_status = "WIP")');

        if(!empty($where1['order']))
		{

			$column_name_index = $where1['order'][0]['column'];
			$order_by = $where1['order'][0]['dir']; 
          
			$this->db->order_by($where1['columns'][$column_name_index]['data'],$order_by);
		}
		else
		{
		
		  $this->db->order_by('credit_report_vendor_log.id', 'desc');

		} 


		$this->db->order_by('id', 'desc');

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function vendor_master_log_update_batch($tableName = 'vendor_master_log',$updateArray)
	{		
	 	$this->db->insert_batch($tableName,$updateArray);
		return $this->db->affected_rows();
	}

	public function update_batch_vendor_assign($tableName,$updateArray)
	{		
		$this->db->where('vendor_id !=','0');
	 	$this->db->update_batch($tableName,$updateArray, 'id');
		return $this->db->affected_rows();
	}
}
?>
