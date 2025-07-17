<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Global_database_model extends CI_Model
{
	function __construct()
    {
		//parent::__construct();

		$this->tableName = 'glodbver';

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

	public function get_global_data($where_arry)
	{
		$this->db->select("glodbver.*,cands.CandidateName,cands.id as cands_id,cands.clientid,CONCAT(cands.prasent_address,' ',cands.cands_pincode,',',cands.cands_state,',',cands.cands_country) as present_address,cands.ClientRefNumber");

		$this->db->from('cands');

		$this->db->join("glodbver",'cands.id = glodbver.candsid','left');

		if($where_arry)
		{
			$this->db->where($where_arry);
		}
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_all_global_record($where_arry = array())
	{
		$this->db->select("glodbver.*,cands.ClientRefNumber,cands.caserecddate,cands.cmp_ref_no,cands.CandidateName,cands.DateofBirth,cands.NameofCandidateFather,cands.MothersName,cands.CandidatesContactNumber,glodbver.created as last_modified,cands.id as cands_id");

		$this->db->from('cands');

		$this->db->join('clients','clients.id = cands.clientid');

		$this->db->join("glodbver",'cands.id = glodbver.candsid','left');

		$this->db->where('clients.globdbver', 1);

		if($where_arry)
		{
			$this->db->where($where_arry);
		}

		$this->db->order_by('glodbver.id', 'desc');
		
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

	public function global_db_ver_status_count($where)
	{
		$this->db->select('count(cands.id) as count,IF(glodbver.verfstatus IS NOT NULL, glodbver.verfstatus ,"WIP") as verfstatus');

		$this->db->from('cands');

		$this->db->join("glodbver",'cands.id = glodbver.candsid','left');

		$this->db->join('clients','(clients.id = cands.clientid AND clients.globdbver = 1)');
		
		if($where)
		{
			$this->db->where($where);
		}

		$this->db->group_by('verfstatus');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

        return $result->result_array();
	}

	public function get_global_db_cases_by_date($date)
	{
		$query = $this->db->query('SELECT count(glodbver.id) as total_count FROM glodbver 
					where DATE_FORMAT(glodbver.created ,"%Y-%m-%d") >= "'.$date.'" AND DATE_FORMAT(glodbver.created ,"%Y-%m-%d") <= "'.$date.'" AND glodbver.verfstatus = "clear"');

		$results = $query->row_array();
		
		return (!empty($results) ? $results['total_count'] : 0);
	}

	public function get_global_tat($clientid = false)
	{
		$this->db->select("cands.ClientRefNumber,cands.caserecddate,cands.cmp_ref_no,cands.CandidateName,cands.DateofBirth,cands.NameofCandidateFather,cands.MothersName,cands.CandidatesContactNumber,glodbver.created as last_modified,cands.id as cands_id,glodbver.verfstatus,clients.tat_courtver as tat_days,glodbver.reiniated_date,glodbver.closuredate,glodbver.remarks");

		$this->db->from('cands');

		$this->db->join('clients','clients.id = cands.clientid');

		$this->db->join("glodbver",'cands.id = glodbver.candsid','left');

		$this->db->where('clients.globdbver',1);
		
		if($clientid)
		{
			$this->db->where('cands.clientid',$clientid);
		}
		$this->db->order_by('cands.id', 'desc');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function global_db_ver_by_status($component_status,$where)
	{
		$this->db->select("glodbver.*,cands.ClientRefNumber,cands.caserecddate,cands.cmp_ref_no,cands.CandidateName,cands.DateofBirth,cands.NameofCandidateFather,cands.MothersName,cands.CandidatesContactNumber,glodbver.created as last_modified,cands.id as cands_id");

		$this->db->from('cands');

		$this->db->join("glodbver",'cands.id = glodbver.candsid','left');

		$this->db->join('clients','(clients.id = cands.clientid AND clients.globdbver = 1)');

		if($component_status != 'all')
		{
			if($component_status == 'WIP')
			{
				$this->db->where('glodbver.verfstatus','WIP');

				$this->db->or_where('glodbver.verfstatus IS NULL', null, false);
			}
			else if($component_status == 'Unable-to-Verify')
			{
				$this->db->where('glodbver.verfstatus','Unable to Verify');
			}
			else if($component_status == 'Discrepancy')
			{
				$this->db->where('glodbver.verfstatus','Discrepancy');
				
				$this->db->or_where('glodbver.verfstatus','No Record Found');
			}
			else
			{
				$this->db->where('glodbver.verfstatus',$component_status);
			}
		}

		$this->db->order_by('id', 'desc');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_all_global_db_client($where_arry)
	{
		$this->db->select("cands.ClientRefNumber,cands.caserecddate,cands.cmp_ref_no,cands.CandidateName,cands.DateofBirth,cands.NameofCandidateFather,cands.MothersName,cands.CandidatesContactNumber,glodbver.created as last_modified,cands.id as cands_id,glodbver.verfstatus,clients.tat_globdbver as tat_days,glodbver.reiniated_date,glodbver.closuredate,glodbver.remarks,glodbver.remarks as remark,clients.clientname,clients.id as client_id");

		$this->db->from('cands');

		$this->db->join('clients','(clients.id = cands.clientid AND clients.globdbver = 1)');

		$this->db->join("glodbver",'cands.id = glodbver.candsid','left');
		
		if($where_arry)
		{
			$this->db->where($where_arry);
		}
		$this->db->order_by('cands.id', 'desc');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());
	
		return $result->result_array();
	}

	public function cand_global_data($where_arry,$comp)
	{
		$this->db->select("glodbver.*,glodbver_result.remarks,status.status_value,glodbver_result.closuredate,glodbver_result.mode_of_verification, glodbver_result.verified_by");

		$this->db->from('candidates_info');


		$this->db->join("glodbver",'candidates_info.id = glodbver.candsid');
		$this->db->join("glodbver_result",'glodbver_result.glodbver_id = glodbver.id','left');

		$this->db->join("status",'(status.id = glodbver_result.verfstatus)');

		if($where_arry)
		{
			$this->db->where($where_arry);
		}
		
         if($comp)
		{
			$this->db->where('glodbver.global_com_ref',$comp);
		}

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function cand_global_data_view($where_arry)
	{
		$this->db->select("glodbver.*,glodbver_result.remarks,status.status_value,glodbver_result.closuredate,glodbver_result.mode_of_verification, glodbver_result.verified_by");

		$this->db->from('candidates_info');


		$this->db->join("glodbver",'candidates_info.id = glodbver.candsid');
		$this->db->join("glodbver_result",'glodbver_result.glodbver_id = glodbver.id','left');

		$this->db->join("status",'(status.id = glodbver_result.verfstatus)');

		if($where_arry)
		{
			$this->db->where($where_arry);
		}
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}
}
?>