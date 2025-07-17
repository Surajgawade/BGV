<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Employment_wip_activity_cases_model extends CI_Model
{
	function __construct()
    {
		$this->tableName = 'empver';

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

	
    public function get_all_emp_by_client_datatable_wip_activity($where_arry = array(),$where,$columns)
	{
          
        $conditional_status = "(`ev1`.`verfstatus` = '1' or `ev1`.`verfstatus` = '12' or `ev1`.`verfstatus` = '13' or `ev1`.`verfstatus` = '23')";  

        $date =    date("Y-m-d", strtotime("-2 day"));
      
        $where_cond = "DATE_FORMAT(`empver_activity_data`.`created_on`,'%Y-%m-%d') <= '$date'";

		$this->db->select("candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,user_profile.user_name,company_database.coname,status.status_value,ev1.verfstatus,ev1.first_qc_approve,ev1.first_qc_updated_on,ev1.first_qu_reject_reason,empver.empid,empver.mode_of_veri,empver.id,empver.has_assigned_on,empver.iniated_date,empver.emp_com_ref,empver.field_visit_status,empver.mail_sent_status,empver.mail_sent_addrs,ev1.res_reasonforleaving,candidates_info.caserecddate,clients.clientname,empver_activity_data.created_on,due_date,tat_status,empverres_insuff.insuff_raised_date");

		$this->db->from('empver');

		$this->db->join("user_profile",'user_profile.id = empver.has_case_id','left');
		
		$this->db->join("candidates_info",'candidates_info.id = empver.candsid');

		$this->db->join("clients",'clients.id = empver.clientid');

		$this->db->join("company_database",'company_database.id = empver.nameofthecompany');

		$this->db->join('(SELECT  MAX(id) max_id,comp_table_id FROM empver_activity_data GROUP BY  comp_table_id) c_max ','c_max.comp_table_id = empver.id');

        $this->db->join("empver_activity_data",'empver_activity_data.id = c_max.max_id');

		$this->db->join("empverres as ev1",'ev1.empverid = empver.id','left');

		$this->db->join("empverres_insuff",'(empverres_insuff.empverres_id = empver.id AND  empverres_insuff.status = 1 )','left');

		$this->db->join("status",'status.id = ev1.verfstatus','left');

	    $this->db->where($conditional_status);

	    $this->db->where($where_cond);

	    if(isset($where['filter_by_executive']) &&  $where['filter_by_executive'] != 0)	
		{ 
		    if($where['filter_by_executive'] != "All")
	     	{
	     		$this->db->where('empver.has_case_id',$where['filter_by_executive']);
            
	      	}
        }

		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like('candidates_info.cmp_ref_no', $where['search']['value']);

			$this->db->or_like('candidates_info.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);

			$this->db->or_like('empver.emp_com_ref', $where['search']['value']);

			$this->db->or_like('empver.iniated_date', $where['search']['value']);

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
		    $this->db->order_by('empver.id','desc');
		}

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_all_emp_by_client_datatable_count_wip_activity($where_arry = array(),$where,$columns)
	{

		$conditional_status = "(`ev1`.`verfstatus` = '1' or `ev1`.`verfstatus` = '12' or `ev1`.`verfstatus` = '13' or `ev1`.`verfstatus` = '23')";  
 
        $date =    date("Y-m-d", strtotime("-2 day"));
      
        $where_cond = "DATE_FORMAT(`empver_activity_data`.`created_on`,'%Y-%m-%d') <= '$date'";

		$this->db->select("candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,user_profile.user_name,company_database.coname,status.status_value,ev1.verfstatus,ev1.first_qc_approve,ev1.first_qc_updated_on,ev1.first_qu_reject_reason,empver.empid,empver.id,empver.has_assigned_on,empver.iniated_date,empver.emp_com_ref,empver.mail_sent_status,empver.mail_sent_addrs,ev1.res_reasonforleaving,candidates_info.caserecddate,empverres_insuff.insuff_raised_date,empver_activity_data.created_on");

		$this->db->from('empver');

		$this->db->join("user_profile",'user_profile.id = empver.has_case_id');

		$this->db->join("clients",'clients.id = empver.clientid');
		
		$this->db->join("candidates_info",'candidates_info.id = empver.candsid');

		$this->db->join("company_database",'company_database.id = empver.nameofthecompany');

		$this->db->join('(SELECT  MAX(id) max_id,comp_table_id FROM empver_activity_data GROUP BY  comp_table_id) c_max ','c_max.comp_table_id = empver.id');

        $this->db->join("empver_activity_data",'empver_activity_data.id = c_max.max_id');


		$this->db->join("empverres as ev1",'ev1.empverid = empver.id','left');

	   $this->db->join("empverres_insuff",'(empverres_insuff.empverres_id = empver.id AND  empverres_insuff.status = 1 )','left');


		$this->db->join("status",'status.id = ev1.verfstatus','left');
		
     	$this->db->where($conditional_status);

        $this->db->where($where_cond);

        if(isset($where['filter_by_executive']) &&  $where['filter_by_executive'] != 0)	
		{ 
		    if($where['filter_by_executive'] != "All")
	     	{
	     		$this->db->where('empver.has_case_id',$where['filter_by_executive']);
            
	      	}
        }


		if(is_array($where) && $where['search']['value'] != "")
		{

			$this->db->like('candidates_info.cmp_ref_no', $where['search']['value']);

			$this->db->or_like('candidates_info.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);

			$this->db->or_like('empver.emp_com_ref', $where['search']['value']);

			$this->db->or_like('empver.iniated_date', $where['search']['value']);

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
			

		    $this->db->order_by('empver.id','desc');
		}

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

     
}
?>