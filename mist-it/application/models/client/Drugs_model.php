<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Drugs_model extends CI_Model
{
	function __construct()
    {
		//parent::__construct();

		$this->tableName = 'drug_narcotis';

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

	public function get_all_court_record($where_arry = array())
	{
		$this->db->select("courtver.*,cands.ClientRefNumber,cands.caserecddate,cands.cmp_ref_no,cands.CandidateName,cands.DateofBirth,cands.NameofCandidateFather,cands.MothersName,cands.CandidatesContactNumber,courtver.created as last_modified,cands.id as cands_id,cands.ClientRefNumber");

		$this->db->from('cands');

		$this->db->join('clients','clients.id = cands.clientid');

		$this->db->join("courtver",'cands.id = courtver.candsid','left');

		$this->db->where('clients.courtver', 1);

		if($where_arry)
		{
			$this->db->where($where_arry);
		}

		$this->db->order_by('courtver.id', 'desc');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_drugs_data($where_arry,$comp)
	{
		$this->db->select("drug_narcotis.*,candidates_info.CandidateName,candidates_info.id as cands_id,candidates_info.clientid, d1.mode_of_verification,status.status_value");

		$this->db->from('candidates_info');

		$this->db->join("drug_narcotis",'candidates_info.id = drug_narcotis.candsid','left');

		$this->db->join("drug_narcotis_result as d1",'d1.drug_narcotis_id = drug_narcotis.id','left');

		$this->db->join("status",'(status.id = d1.verfstatus)');

		if($where_arry)
		{
			$this->db->where($where_arry);
		}

		if($comp)
		{
			$this->db->where('drug_narcotis.drug_com_ref',$comp);
		}
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}


	public function get_drugs_data_view($where_arry)
	{
		$this->db->select("drug_narcotis.*,candidates_info.CandidateName,candidates_info.id as cands_id,candidates_info.clientid, d1.mode_of_verification,status.status_value");

		$this->db->from('candidates_info');

		$this->db->join("drug_narcotis",'candidates_info.id = drug_narcotis.candsid','left');

		$this->db->join("drug_narcotis_result as d1",'d1.drug_narcotis_id = drug_narcotis.id','left');

		$this->db->join("status",'(status.id = d1.verfstatus)');

		if($where_arry)
		{
			$this->db->where($where_arry);
		}

		
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}


	public function court_ver_status_count($where)
	{
		$this->db->select('count(cands.id) as count,IF(courtver.verfstatus IS NOT NULL, courtver.verfstatus ,"WIP") as verfstatus');

		$this->db->from('cands');

		$this->db->join('clients','(clients.id = cands.clientid AND clients.courtver = 1)');

		$this->db->join("courtver",'cands.id = courtver.candsid','left');

		if($where)
		{
			$this->db->where($where);
		}
		
		$this->db->group_by('verfstatus');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function court_ver_by_status($component_status,$where)
	{
		$this->db->select("courtver.*,cands.ClientRefNumber,cands.caserecddate,cands.cmp_ref_no,cands.CandidateName,cands.DateofBirth,cands.NameofCandidateFather,cands.MothersName,cands.CandidatesContactNumber,courtver.created as last_modified,cands.id as cands_id");

		$this->db->from('cands');

		$this->db->join('clients','(clients.id = cands.clientid AND clients.courtver = 1)');

		$this->db->join("courtver",'cands.id = courtver.candsid','left');

		if($component_status != 'all')
		{
			if($component_status == 'WIP')
			{
				$this->db->where('courtver.verfstatus IS NULL', null, false);
			} 
			else if($component_status == 'Unable-to-Verify')
			{
				$this->db->where('courtver.verfstatus','Unable to Verify');
			}
			else if($component_status == 'Stop-Check')
			{
				$this->db->where('ev1.verfstatus','Stop/Check');
			}
			else if($component_status == 'Discrepancy')
			{
				$this->db->where('courtver.verfstatus','Discrepancy');
				
				$this->db->or_where('courtver.verfstatus','No Record Found');
			}
			else
			{
				$this->db->where('courtver.verfstatus',$component_status);
			}
		}

		if($where)
		{
			$this->db->where($where);
		}
		
		$this->db->order_by('id', 'desc');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_all_court_client($where_arry)
	{
		$this->db->select("cands.ClientRefNumber,cands.caserecddate,cands.cmp_ref_no,cands.CandidateName,cands.DateofBirth,cands.NameofCandidateFather,cands.MothersName,cands.CandidatesContactNumber,courtver.created as last_modified,cands.id as cands_id,courtver.verfstatus,clients.tat_courtver as tat_days,courtver.reiniated_date,courtver.closuredate,courtver.remarks,courtver.remarks as remark,clients.clientname,clients.id as client_id");

		$this->db->from('cands');

		$this->db->join('clients','(clients.id = cands.clientid AND clients.courtver = 1)');

		$this->db->join("courtver",'cands.id = courtver.candsid','left');
		
		if($where_arry)
		{
			$this->db->where($where_arry);
		}
		
		$this->db->order_by('cands.id', 'desc');
		
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
