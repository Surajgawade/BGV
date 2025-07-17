<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Addressver_model extends CI_Model
{
	function __construct()
    {
		//parent::__construct();

		$this->tableName = 'addrver';

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
            if(count($result_array)==1) // ensure only one record has been previously inserted
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

	public function get_all_addrs_by_client($where_arry)
	{
		$this->db->select("addrver.id,addrver.address,addrver.city,addrver.state,addrver.pincode,addrver.verder_address_name,addrver.reiniated_date,cands.ClientRefNumber,cands.cmp_ref_no,lower(cands.CandidateName) as CandidateName,cands.caserecddate,ev1.verfstatus,ev1.remark,ev1.closuredate,ev1.insuffcleardate,ev1.insuffremarks,cands.remarks as overallremark,cands.updated as overall_lastupdated,cands.overallstatus,clients.id as client_id,clients.clientname,clients.tat_addrver as tat_days,membership_users.user_name");

		$this->db->from('addrver');

		$this->db->join("cands",'cands.id = addrver.candsid');

		$this->db->join('membership_users', 'membership_users.id = addrver.has_case_id');

		$this->db->join("clients",'(clients.id = cands.clientid AND clients.addrver = 1)');

		$this->db->join("membership_groups",'membership_groups.groupID = addrver.assignedto');
		
		$this->db->join("addrverres as ev1",'ev1.addrverid = addrver.id','left');

		$this->db->join("addrverres as ev2",'(ev2.addrverid = addrver.id and ev1.id < ev2.id)','left');

		$this->db->where('ev2.verfstatus is null');

		if($where_arry)
		{
			$this->db->where($where_arry);
		}

		$this->db->order_by('addrver.id', 'desc');

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}
  

 
    public function  save_update($arrdata,$arrwhere = array())
	  {
	    if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update($this->tableName, $arrdata);

			record_db_error($this->db->last_query());

			return $result;
	    }
	   
	  }

	public function get_all_addrs_by_client_view($id)
	{


		$this->db->select("addrver.*,a1.verfstatus,a1.mode_of_verification,status.status_value");

		$this->db->from('addrver');
		
		$this->db->join("addrverres as a1",'a1.addrverid = addrver.id','left');

		$this->db->join("addrverres as a2",'(a2.addrverid = addrver.id and a1.id < a2.id)','left');

		$this->db->join("status",'(status.id = a1.verfstatus)');

		if($id)
		{
			$this->db->where('addrver.candsid',$id);
		}
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	

	public function get_address_ver_result($addsver_id = array())
	{
		$this->db->select("addrverres.*,addrver.clientid as client_id,GROUP_CONCAT(addrverres_files.file_name) as file_names");

		$this->db->from('addrverres');

		$this->db->join("addrver",'addrver.id = addrverres.addrverid');

		$this->db->join("addrverres_files",'addrverres_files.addrverres_id = addrverres.id','left');

		$this->db->group_by('addrverres.id');

		if(!empty($addsver_id))
		{
			$this->db->where($addsver_id);
		}

		$this->db->order_by('id', 'desc');
		
		$this->db->limit(1,0);

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function save_update_adds_ver_result($arrdata,$arrwhere = array())
	{
		if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update('addrverres', $arrdata);

			record_db_error($this->db->last_query());

			return $result;
	    }
	    else
	    {
			$this->db->insert('addrverres', $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	    }
	}

	public function uploaded_files($files_arry, $return_insert_ids = FALSE)
	{
		if($return_insert_ids)
		{
			$this->db->insert_batch('addrverres_files', $files_arry);

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
			$res =  $this->db->insert_batch('addrverres_files', $files_arry);
			
			record_db_error($this->db->last_query());
			
			return $res;
		}
	}

	public function cand_address_details($id,$comp)
	{
		$this->db->select("addrver.*,a1.verfstatus,a1.mode_of_verification,status.status_value");

		$this->db->from('addrver');
		
		$this->db->join("addrverres as a1",'a1.addrverid = addrver.id','left');

		$this->db->join("addrverres as a2",'(a2.addrverid = addrver.id and a1.id < a2.id)','left');

		$this->db->join("status",'(status.id = a1.verfstatus)');

		$this->db->where('a2.verfstatus is null');

		if($id)
		{
			$this->db->where('addrver.candsid',$id);
		}
		if($comp)
		{
			$this->db->where('addrver.add_com_ref',$comp);
		}
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function address_ver_status_count($where)
	{
		$this->db->select('count(addrver.id) as count,IF(a1.verfstatus IS NOT NULL, a1.verfstatus ,"WIP") as verfstatus');

		$this->db->from('cands');
		
		$this->db->join("addrver",'cands.id = addrver.candsid');

		$this->db->join("clients",'(clients.id = cands.clientid AND clients.addrver = 1)');

		$this->db->join("addrverres as a1",'a1.addrverid = addrver.id','left');

		$this->db->join("addrverres as a2",'(a2.addrverid = addrver.id and a1.id < a2.id)','left');

		$this->db->where('a2.verfstatus is null');

		if($where)
		{
			$this->db->where($where);
		}

		$this->db->group_by('verfstatus');

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function address_ver_by_status($component_status,$where)
	{
		$this->db->select("addrver.*,cands.ClientRefNumber,cands.cmp_ref_no,cands.CandidateName,cands.caserecddate,ev1.verfstatus,ev1.remark,ev1.closuredate,ev1.insuffcleardate,ev1.insuffremarks,cands.remarks as overallremark,cands.updated as overall_lastupdated,cands.overallstatus,ev1.created_on as last_modified");

		$this->db->from('addrver');

		$this->db->join("cands",'cands.id = addrver.candsid');

		if($where)
		{
			$clientid = $where['cands.clientid'];

			$this->db->join("clients","(clients.id = cands.clientid AND clients.addrver = 1) AND clients.id = $clientid");
		}
		else
		{
			$this->db->join("clients",'(clients.id = cands.clientid AND clients.addrver = 1)');
		}
		
		$this->db->join("addrverres as ev1",'ev1.addrverid = addrver.id','left');

		$this->db->join("addrverres as ev2",'(ev2.addrverid = addrver.id and ev1.id < ev2.id)','left');

		$this->db->where('ev2.verfstatus is null');

		if($component_status != 'all')
		{
			if($component_status == 'WIP')
			{
				$this->db->where('ev1.verfstatus','WIP');

				$this->db->or_where("ev1.verfstatus IS NULL",NULL,false);

				$this->db->or_where("ev1.verfstatus",'WIP-Initiated');
			}
			else if($component_status == 'Unable-to-Verify')
			{
				$this->db->where('ev1.verfstatus','Unable to Verify');
			}
			else if($component_status == 'Stop-Check')
			{
				$this->db->where('ev1.verfstatus','Stop/Check');
			}
			else if($component_status == 'Discrepancy')
			{
				$this->db->where('ev1.verfstatus','Discrepancy');
				
				$this->db->or_where('ev1.verfstatus','No Record Found');
			}
			else if($component_status == 'Insufficiency')
			{
				$this->db->where('ev1.verfstatus','Insufficiency');
				
				$this->db->or_where('ev1.verfstatus','Insufficiency I');

				$this->db->or_where('ev1.verfstatus','Insufficiency II');
			}
			else
			{
				$this->db->where('ev1.verfstatus',$component_status);
			}
		}
		
		$this->db->order_by('id', 'desc');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}
}
?>