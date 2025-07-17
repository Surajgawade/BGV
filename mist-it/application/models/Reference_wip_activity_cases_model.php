<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reference_wip_activity_cases_model extends CI_Model
{
	function __construct()
    {
		$this->tableName = 'reference';

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

        if($return_as_strict_row){

            if(count($result_array) == 1) {
                $result_array  = $result_array[0];
            }
        }
        return $result_array;
	}



	public function get_all_reference_record_datatable_wip_cases($empver_aary = array(),$where,$columns)
	{
		$conditional_status = "(`reference_result`.`verfstatus` = '1' or `reference_result`.`verfstatus` = '12' or `reference_result`.`verfstatus` = '13' or `reference_result`.`verfstatus` = '23')";  

		$date =    date("Y-m-d", strtotime("-2 day"));
      
        $where_cond = "DATE_FORMAT(`reference_activity_data`.`created_on`,'%Y-%m-%d') <= '$date'";

		$this->db->select("candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = reference.has_case_id) as executive_name,status.status_value,reference.id,reference.reference_com_ref,reference_result.verfstatus,reference_result.first_qc_approve,reference.mode_of_veri,reference_result.first_qc_updated_on,reference_result.first_qu_reject_reason,reference.id,reference.has_assigned_on,reference.iniated_date,clients.clientname,due_date,tat_status,reference_result.closuredate,reference.name_of_reference,reference_activity_data.created_on,(select vendor_name from vendors where vendors.id = reference.vendor_id) as vendor_name,reference_insuff.insuff_raised_date");

		$this->db->from('reference');

		$this->db->join("reference_result",'reference_result.reference_id = reference.id');
		
		$this->db->join("candidates_info",'candidates_info.id = reference.candsid');

		$this->db->join('(SELECT  MAX(id) max_id,comp_table_id FROM reference_activity_data GROUP BY  comp_table_id) c_max ','c_max.comp_table_id = reference.id');

        $this->db->join("reference_activity_data",'reference_activity_data.id = c_max.max_id');

		
		$this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("reference_insuff",'(reference_insuff.reference_id = reference.id AND  reference_insuff.status = 1 )','left');


		$this->db->join("status",'status.id = reference_result.verfstatus');

        $this->db->where($conditional_status);
		 
		$this->db->where($where_cond); 

		if(isset($where['filter_by_executive']) &&  $where['filter_by_executive'] != 0)	
		{ 
		    if($where['filter_by_executive'] != "All")
	     	{
	     		$this->db->where('reference.has_case_id',$where['filter_by_executive']);
            
	      	}
        } 

		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like('candidates_info.ClientRefNumber', $where['search']['value']);

	        $this->db->or_like('candidates_info.cmp_ref_no', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);

			$this->db->or_like('reference.reference_com_ref', $where['search']['value']);

			$this->db->or_like('reference.iniated_date', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where['search']['value']);

		}
		
		$this->db->limit($where['length'],$where['start']);
		

		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 
            
         
        
			$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		}
		else
		{
			

		     $this->db->order_by('reference.id','desc');
		}    

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}




	public function get_all_reference_record_datatable_wip_cases_count($empver_aary = array(),$where,$columns)
	{
		$conditional_status = "(`reference_result`.`verfstatus` = '1' or `reference_result`.`verfstatus` = '12' or `reference_result`.`verfstatus` = '13' or `reference_result`.`verfstatus` = '23')"; 

		$date =    date("Y-m-d", strtotime("-2 day"));
      
        $where_cond = "DATE_FORMAT(`reference_activity_data`.`created_on`,'%Y-%m-%d') <= '$date'";

		$this->db->select("candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,(select user_name from user_profile where user_profile.id = reference.has_case_id) as executive_name,status.status_value,reference.reference_com_ref,reference_result.verfstatus,reference_result.first_qc_approve,reference_result.first_qc_updated_on,reference_result.first_qu_reject_reason,reference.id,reference.has_assigned_on,reference.iniated_date,clients.clientname,reference_insuff.insuff_raised_date");

		$this->db->from('reference');

		$this->db->join("reference_result",'reference_result.reference_id = reference.id');
		
		$this->db->join("candidates_info",'candidates_info.id = reference.candsid');
		
		$this->db->join("clients",'clients.id = candidates_info.clientid');

	    $this->db->join('(SELECT  MAX(id) max_id,comp_table_id FROM reference_activity_data GROUP BY  comp_table_id) c_max ','c_max.comp_table_id = reference.id');

        $this->db->join("reference_activity_data",'reference_activity_data.id = c_max.max_id');


		$this->db->join("reference_insuff",'(reference_insuff.reference_id = reference.id AND  reference_insuff.status = 1 )','left');


		$this->db->join("status",'status.id = reference_result.verfstatus');

		$this->db->where($conditional_status);

	    $this->db->where($where_cond); 

	    if(isset($where['filter_by_executive']) &&  $where['filter_by_executive'] != 0)	
		{ 
		    if($where['filter_by_executive'] != "All")
	     	{
	     		$this->db->where('reference.has_case_id',$where['filter_by_executive']);
            
	      	}
        } 
  
		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like('candidates_info.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);

			$this->db->or_like('reference.reference_com_ref', $where['search']['value']);

			$this->db->or_like('reference.iniated_date', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where['search']['value']);

		}
				
		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 
            
        
			$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		}
		else
		{
		
		    $this->db->order_by('reference.id','desc');
		}    

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}


}
?>
