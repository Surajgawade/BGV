<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Employment_model extends CI_Model
{
	function __construct()
    {
		//parent::__construct();

		$this->tableName = 'empver';

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

	public function get_all_emp_client($where_arry)
	{
		$this->db->select("empver.*,cands.ClientRefNumber,cands.cmp_ref_no,lower(cands.CandidateName) as CandidateName,cands.caserecddate,cands.updated as closuredate,ev1.verfstatus,ev1.InsuffRaisedDate,ev1.InsuffClearDate,ev1.remarks as remark,empver.reiniated_date,cands.overallstatus,clients.tat_empver as tat_days,membership_users.user_name,clients.clientname,clients.id as client_id");

		$this->db->from('empver');

		$this->db->join("cands",'cands.id = empver.candsid');

		$this->db->join('membership_users', 'membership_users.id = empver.has_case_id');

		$this->db->join("clients",'(clients.id = empver.clientid  AND clients.empver = 1)');
		//$this->db->join("clients",'(clients.id = cands.clientid AND clients.empver = 1)');

		$this->db->join('knowncos', 'knowncos.id = empver.nameofthecompany');
		
		$this->db->join("empverres as ev1",'ev1.empverid = empver.id','left');

		$this->db->join("empverres as ev2",'(ev2.empverid = empver.id and ev1.id < ev2.id)','left');

		$this->db->where('ev2.verfstatus is null');

		if($where_arry)
		{
			$this->db->where($where_arry);
		}
		
		$this->db->order_by('empver.id', 'desc');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_all_emp_by_client($empver_aary = array())
	{
		$this->db->select("empver.*,cands.ClientRefNumber,cands.cmp_ref_no,cands.CandidateName,cands.caserecddate,cands.updated as closuredate,ev1.verfstatus,ev1.InsuffRaisedDate,ev1.InsuffClearDate,ev1.remarks as remark,empver.reiniated_date,cands.overallstatus");

		$this->db->from('empver');

		$this->db->join("cands",'cands.id = empver.candsid');

		$this->db->join("empverres as ev1",'ev1.empverid = empver.id','left');

		$this->db->join("empverres as ev2",'(ev2.empverid = empver.id and ev1.id < ev2.id)','left');

		$this->db->where('cands.clientid',$this->session->userdata('client_id'));

		$this->db->where('ev2.verfstatus is null');

		$this->db->group_by('empver.id');

		$this->db->order_by('empver.id', 'desc');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}



	public function get_empverres_result($emp_ver_id = array())
	{
		$this->db->select("empverres.*,empver.clientid as client_id,GROUP_CONCAT(empverres_files.file_name) as verifier_uploded_attc");

		$this->db->from('empverres');

		$this->db->join("empver",'empver.id = empverres.empverid');

		$this->db->join("empverres as ev2",'(ev2.empverid = empver.id and empverres.id < ev2.id)','left');
		
		$this->db->join("empverres_files",'(empverres_files.empver_id = empverres.id AND empverres_files.status = 1) AND empverres_files.type = 1','left');

		if(!empty($emp_ver_id))
		{
			$this->db->where($emp_ver_id);
		}
 		
 		$this->db->where('ev2.verfstatus is null');

 		$this->db->group_by('empverres.id');

		$this->db->order_by('id', 'desc');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function save_update_empt_ver_result($arrdata,$arrwhere = array())
	{
		if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update('empverres', $arrdata);

			record_db_error($this->db->last_query());

			return $result;
	    }
	    else
	    {
			$this->db->insert('empverres', $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	    }
	}

	public function uploaded_files($files_arry, $return_insert_ids = FALSE)
	{
		if($return_insert_ids)
		{
			$this->db->insert_batch('empverres_files', $files_arry);

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
			$res =  $this->db->insert_batch('empverres_files', $files_arry);
			
			record_db_error($this->db->last_query());
			
			return $res;
		}
	}

	

	public function delete_uploaded_file($where = array())
	{	
		
		$this->db->where_in('id',$where);

		$this->db->set('status', STATUS_DELETED);

		$result = $this->db->update('empverres_files', array('status' => STATUS_DELETED));

		record_db_error($this->db->last_query());

		return $result;
	}

	public function add_new_company($arrdata,$arrwhere = array())
	{
		if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update('knowncos', $arrdata);

			record_db_error($this->db->last_query());

			return $result;
	    }
	    else
	    {
			$this->db->insert('knowncos', $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	    }
	}

	public function emp_verification_report($empver_aary = array())
	{
		$this->db->select("empver.*,clients.clientname,cands.CandidateName,knowncos.coname,GROUP_CONCAT(empverres_files.file_name) as file_names,GROUP_CONCAT(empverres_files.id) as empverres_files_ids,empverres.verfname,empverres.verfdesgn,empverres.reasonforleaving,empverres.natureofseparation,empverres.integrity_disciplinary_issue,empverres.eligforrehire,empverres.exitformalities,empverres.addlhrcomments,empverres.employed_from,empverres.employed_to,empverres.emp_designation");

		$this->db->from('empver');

		$this->db->join("cands",'cands.id = empver.candsid');

		$this->db->join("clients",'clients.id = cands.clientid');

		$this->db->join("knowncos",'knowncos.id = empver.nameofthecompany');

		$this->db->join("empverres",'empverres.empverid = empver.id');

		$this->db->join("empverres_files",'(empverres_files.empver_id = empver.id AND empverres_files.status = 1)','left');

		if(!empty($empver_aary))
		{
			$this->db->where($empver_aary);
		}

		$this->db->group_by('empver.id');

		$this->db->order_by('id', 'desc');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function emp_ver_details_for_email($empver_aary = array())
	{
		$this->db->select("empver.*,clients.clientname,cands.CandidateName,knowncos.coname,GROUP_CONCAT(empverres_files.file_name) as file_names,GROUP_CONCAT(empverres_files.id) as empverres_files_ids,ev1.verfname,ev1.verfdesgn,ev1.reasonforleaving,ev1.natureofseparation,ev1.integrity_disciplinary_issue,ev1.eligforrehire,ev1.exitformalities,ev1.addlhrcomments,ev1.employed_from,ev1.employed_to,ev1.emp_designation,ev1.reportingmanager");

		$this->db->from('empver');

		$this->db->join("cands",'cands.id = empver.candsid');

		$this->db->join("clients",'clients.id = cands.clientid');

		$this->db->join("knowncos",'knowncos.id = empver.nameofthecompany');

		$this->db->join("empverres as ev1",'ev1.empverid = empver.id','left');

		$this->db->join("empverres as ev2",'(ev2.empverid = empver.id and ev1.id < ev2.id)','left');

		$this->db->join("empverres_files",'(empverres_files.empver_id = empver.id AND empverres_files.status = 1) AND empverres_files.type = 0','left');

		if(!empty($empver_aary))
		{
			$this->db->where($empver_aary);
		}

		$this->db->where('ev2.verfstatus is null');
		
		$this->db->group_by('empver.id');

		$this->db->order_by('id', 'desc');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function check_company_exits($fields = array())
	{
		$result = $this->db->select('id')->from('knowncos')->where('coname', $fields['coname'].'dd')->get()->row();

		if($result!= "")
		{
			return $result->id;
		}
		else
		{
			return $this->add_new_company($fields);
		}
	}

	public function cand_employment_details($id,$comp)
	{
		$this->db->select("empver.*,company_database.coname,empverres_files.file_name,ev1.verfstatus,status.status_value");

		$this->db->from('empver');

		$this->db->join("company_database",'company_database.id = empver.nameofthecompany','left');

		$this->db->join("empverres_files",'(empverres_files.empver_id = empver.id AND empverres_files.status = 1) AND empverres_files.type = 0','left');

		$this->db->join("empverres as ev1",'ev1.empverid = empver.id','left');

		$this->db->join("empverres as ev2",'(ev2.empverid = empver.id and ev1.id < ev2.id)','left');

		$this->db->where('ev2.verfstatus is null');

		$this->db->join("status",'(status.id = ev1.verfstatus)');

		$this->db->where('empver.candsid',$id);

		if($comp)
		{
			$this->db->where('empver.emp_com_ref',$comp);
		}

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}


	public function cand_employment_details_view($id)
	{
		$this->db->select("empver.*,company_database.coname,empverres_files.file_name,ev1.verfstatus,status.status_value");

		$this->db->from('empver');

		$this->db->join("company_database",'company_database.id = empver.nameofthecompany','left');

		$this->db->join("empverres_files",'(empverres_files.empver_id = empver.id AND empverres_files.status = 1) AND empverres_files.type = 0','left');

		$this->db->join("empverres as ev1",'ev1.empverid = empver.id','left');

		$this->db->join("empverres as ev2",'(ev2.empverid = empver.id and ev1.id < ev2.id)','left');

		$this->db->where('ev2.verfstatus is null');

		$this->db->join("status",'(status.id = ev1.verfstatus)');

		$this->db->where('empver.candsid',$id);

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}


	public function employment_ver_status_count($where)
	{
		$this->db->select('count(empver.id) as count,IF(ev1.verfstatus IS NOT NULL, ev1.verfstatus ,"WIP") as verfstatus');

		$this->db->from('empver');
		
		$this->db->join("cands",'cands.id = empver.candsid');

		$this->db->join("clients",'clients.id = empver.clientid');

		$this->db->join("knowncos",'knowncos.id = empver.nameofthecompany');

		$this->db->join("empverres as ev1",'ev1.empverid = empver.id','left');

		$this->db->join("empverres as ev2",'(ev2.empverid = empver.id and ev1.id < ev2.id)','left');

		$this->db->where('ev2.verfstatus is null');

		if($where)
		{
			$this->db->where($where);
		}

		$this->db->group_by('verfstatus');

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function employment_ver_by_status($component_status,$where)
	{
		$this->db->select("empver.*,cands.ClientRefNumber,cands.cmp_ref_no,cands.CandidateName,cands.caserecddate,cands.updated as closuredate,ev1.verfstatus,ev1.InsuffRaisedDate,ev1.InsuffClearDate,ev1.remarks as remark,empver.reiniated_date,cands.overallstatus,knowncos.coname");

		$this->db->from('empver');

		$this->db->join("cands",'cands.id = empver.candsid');

		if($where)
		{
			$clientid = $where['cands.clientid'];

			$this->db->join("clients","(clients.id = empver.clientid AND clients.empver = 1) AND clients.id = $clientid");
		}
		else
		{
			$this->db->join("clients",'clients.id = empver.clientid AND clients.empver = 1');
		}

		$this->db->join("knowncos",'knowncos.id = empver.nameofthecompany');
		
		$this->db->join("empverres as ev1",'ev1.empverid = empver.id','left');

		$this->db->join("empverres as ev2",'(ev2.empverid = empver.id and ev1.id < ev2.id)','left');

		$this->db->where('ev2.verfstatus is null');
		
		if($component_status != 'all')
		{
			if($component_status == 'WIP')
			{
				$this->db->where('ev1.verfstatus','WIP');

				$this->db->or_where("ev1.verfstatus",'WIP-Initiated');

				$this->db->or_where("ev1.verfstatus",'case initiated');
				
				$this->db->or_where("ev1.verfstatus",'WIP – pending for clarification');
				
				$this->db->or_where('ev1.verfstatus IS NULL', null, false);
			}
			else if($component_status == 'Unable-to-Verify')
			{
				$this->db->where('ev1.verfstatus','Unable to Verify');
			}
			else if($component_status == 'Stop-Check')
			{
				$this->db->where('ev1.verfstatus','Stop/Check');

				$this->db->or_where('ev1.verfstatus','Work With the Same Organization');
			}
			else if($component_status == 'Discrepancy')
			{
				$this->db->where('ev1.verfstatus','Discrepancy');

				$this->db->or_where('ev1.verfstatus','No Record Found');
			}
			else if($component_status == 'Insufficiency')
			{
				$this->db->where('ev1.verfstatus','Insufficiency');

				$this->db->or_where("ev1.verfstatus",'Insufficiency-Relieving Letter Required');
			}
			else if($component_status == 'Clear')
			{
				$this->db->where('ev1.verfstatus','Clear');

				$this->db->or_where("ev1.verfstatus",'Inaccessible');

				$this->db->or_where("ev1.verfstatus",'No-Response');
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