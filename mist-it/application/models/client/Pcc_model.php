<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pcc_model extends CI_Model
{
	function __construct()
    {
		//parent::__construct();

		$this->tableName = 'pcc_result';

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

	public function get_all_pcc_records_by_client($where_arry = array())
	{
		$this->db->select("cands.*,pcc_result.applicationid,pcc_result.date_of_visit_police,pcc_result.name_and_des_of_police,pcc_result.verification_address,pcc_result.contact_no_of_police,pcc_result.verification_result_record,pcc_result.modeofverification,pcc_result.attchments_ver,pcc_result.verfstatus as pcc_verfstatus,pcc_result.verification_remarks,pcc_result.created_on as last_modified");

		$this->db->from('cands');

		$this->db->join('clients','clients.id = cands.clientid');

		$this->db->join("pcc_result as pcc_result",'pcc_result.cands_id = cands.id','left');

		$this->db->join("pcc_result as p2",'(p2.cands_id = cands.id and pcc_result.id < p2.id)','left');

		$this->db->where('p2.verfstatus is null');

		$this->db->where('clients.crimver',1);

		if($where_arry)
		{
			$this->db->where($where_arry);
		}

		$this->db->order_by('pcc_result.id', 'desc');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function pcc_verification_result_save($arrdata,$arrwhere = array())
	{
		if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update('pcc_result', $arrdata);

			record_db_error($this->db->last_query());

			return $result;
	    }
	    else
	    {
			$this->db->insert('pcc_result', $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	    }
	}

	public function pcc_ver_status_count($where)
	{
		$this->db->select('count(cands.id) as count,IF(pcc_result.verfstatus IS NOT NULL, pcc_result.verfstatus ,"WIP") as verfstatus');

		$this->db->from('cands');

		$this->db->join('clients','(clients.id = cands.clientid AND clients.crimver = 1)');

		$this->db->join("pcc_result as pcc_result",'pcc_result.cands_id = cands.id','left');

		$this->db->join("pcc_result as p2",'(p2.cands_id = cands.id and pcc_result.id < p2.id)','left');

		$this->db->where('p2.verfstatus is null');

		if($where)
		{
			$this->db->where($where);
		}

		$this->db->group_by('verfstatus');

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

	public function pcc_ver_by_status($component_status,$where)
	{
		$this->db->select("cands.*,pcc_result.applicationid,pcc_result.date_of_visit_police,pcc_result.name_and_des_of_police,pcc_result.verification_address,pcc_result.contact_no_of_police,pcc_result.verification_result_record,pcc_result.modeofverification,pcc_result.attchments_ver,pcc_result.verfstatus as pcc_verfstatus,pcc_result.verification_remarks,pcc_result.created_on as last_modified");

		$this->db->from('cands');

		$this->db->join('clients','(clients.id = cands.clientid AND clients.crimver = 1)');

		$this->db->join("pcc_result as pcc_result",'pcc_result.cands_id = cands.id','left');

		$this->db->join("pcc_result as p2",'(p2.cands_id = cands.id and pcc_result.id < p2.id)','left');

		$this->db->where('p2.verfstatus is null');

		if($component_status != 'all')
		{
			if($component_status == 'WIP')
			{
				$this->db->where('pcc_result.verfstatus','WIP');

				$this->db->or_where('pcc_result.verfstatus IS NULL', null, false);
			}
			else if($component_status == 'Unable-to-Verify')
			{
				$this->db->where('pcc_result.verfstatus','Unable to Verify');
			}
			else if($component_status == 'Stop-Check')
			{
				$this->db->where('pcc_result.verfstatus','Stop/Check');
			}
			else if($component_status == 'Discrepancy')
			{
				$this->db->where('pcc_result.verfstatus','Discrepancy');
				
				$this->db->or_where('pcc_result.verfstatus','No Record Found');
			}
			else
			{
				$this->db->where('pcc_result.verfstatus',$component_status);
			}	
		}

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_all_pcc_client($where_arry)
	{
		$this->db->select("cands.ClientRefNumber,cands.caserecddate,cands.cmp_ref_no,cands.CandidateName,pcc_result.applicationid,pcc_result.date_of_visit_police,pcc_result.name_and_des_of_police,pcc_result.verification_address,pcc_result.contact_no_of_police,pcc_result.verification_result_record,pcc_result.modeofverification,pcc_result.attchments_ver,pcc_result.verfstatus as pcc_verfstatus,pcc_result.verification_remarks,pcc_result.created_on as last_modified,pcc_result.verfstatus,clients.tat_crimver as tat_days,pcc_result.reiniated_date,pcc_result.verification_remarks as remark,clients.clientname,clients.id as client_id");

		$this->db->from('cands');

		$this->db->join('clients','(clients.id = cands.clientid AND clients.crimver = 1)');

		$this->db->join("pcc_result as pcc_result",'pcc_result.cands_id = cands.id','left');

		$this->db->join("pcc_result as p2",'(p2.cands_id = cands.id and pcc_result.id < p2.id)','left');

		$this->db->where('p2.verfstatus is null');
		
		if($where_arry)
		{
			$this->db->where($where_arry);
		}

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function cand_pcc_details($cand_id,$comp)
	{
		$this->db->select("pcc.*,pcc_result.application_id_ref,pcc_result.police_station_visit_date,pcc_result.name_designation_police,pcc_result.contact_number_police,pcc_result.mode_of_verification,pcc_result.verfstatus as pcc_verfstatus,pcc_result.remarks,pcc_result.created_on as last_modified,status.status_value,pcc_result.mode_of_verification");

		$this->db->from('candidates_info');

		$this->db->join("pcc",'pcc.candsid = candidates_info.id');

		$this->db->join("pcc_result as pcc_result",'pcc_result.candsid = candidates_info.id');

		$this->db->join("pcc_result as p2",'(p2.candsid = candidates_info.id and pcc_result.id < p2.id)','left');

		$this->db->where('p2.verfstatus is null');

		$this->db->join("status",'(status.id = pcc_result.verfstatus)');
	
		$this->db->where('candidates_info.id',$cand_id);

		if($comp)
		{
			$this->db->where('pcc.pcc_com_ref',$comp);
		}
	
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function cand_pcc_details_view($cand_id)
	{


		$this->db->select("pcc.*,pcc_result.application_id_ref,pcc_result.police_station_visit_date,pcc_result.name_designation_police,pcc_result.contact_number_police,pcc_result.mode_of_verification,pcc_result.verfstatus as pcc_verfstatus,pcc_result.remarks,pcc_result.created_on as last_modified,status.status_value,pcc_result.mode_of_verification");

		$this->db->from('candidates_info');

		$this->db->join("pcc",'pcc.candsid = candidates_info.id');

		$this->db->join("pcc_result as pcc_result",'pcc_result.candsid = candidates_info.id');

		$this->db->join("pcc_result as p2",'(p2.candsid = candidates_info.id and pcc_result.id < p2.id)','left');

		$this->db->where('p2.verfstatus is null');

		$this->db->join("status",'(status.id = pcc_result.verfstatus)');
	
		$this->db->where('candidates_info.id',$cand_id);
	
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}
}
?>
