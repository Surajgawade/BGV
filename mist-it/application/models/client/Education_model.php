<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Education_model extends CI_Model
{
	function __construct()
    {
		//parent::__construct();

		$this->tableName = 'eduver';

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

	// public function get_all_cands_education_data()
	// {
	// 	$this->db->select("eduver.id,cands.ClientRefNumber,cands.cmp_ref_no,cands.CandidateName,cands.caserecddate,ev1.verfstatus,cands.overallstatus");

	// 	$this->db->from('cands');

	// 	$this->db->join("eduver",'cands.id = eduver.candsid','left');

	// 	$this->db->join("eduverres as ev1",'(ev1.eduverid = eduver.id)','left');

	// 	$this->db->join("eduverres as ev2",'(ev2.eduverid = eduver.id and ev1.id < ev2.id)','left');

	// 	$this->db->where('ev2.verfstatus is null');
		
	// 	$this->db->group_by('eduver.id');

	// 	$this->db->order_by('cands.id', 'desc');
		
	// 	$result = $this->db->get();

	// 	record_db_error($this->db->last_query());

	// 	return $result->result_array();
	// }

	public function get_all_eduver($empver_aary = array())
	{
		$this->db->select("eduver.*,cands.ClientRefNumber,cands.cmp_ref_no,cands.CandidateName,cands.caserecddate,ev1.verfstatus,ev1.InsuffRaisedDate,ev1.InsuffClearDate,ev1.ClosureDate as closuredate,ev1.verfremarks as remark,eduver.reiniated_date,cands.overallstatus");

		$this->db->from('eduver');

		$this->db->join("cands",'cands.id = eduver.candsid','left');

		$this->db->join("eduverres as ev1",'(ev1.eduverid = eduver.id)','left');

		$this->db->join("eduverres as ev2",'(ev2.eduverid = eduver.id and ev1.id < ev2.id)','left');

		$this->db->where('cands.clientid',$this->session->userdata('client_id'));

		$this->db->where('ev2.verfstatus is null');
		
		$this->db->group_by('eduver.id');

		$this->db->order_by('id', 'desc');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function uploaded_files($files_arry, $return_insert_ids = FALSE)
	{
		if($return_insert_ids)
		{
			$this->db->insert_batch('eduver_files', $files_arry);

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
			$res =  $this->db->insert_batch('eduver_files', $files_arry);
			
			record_db_error($this->db->last_query());
			
			return $res;
		}
	}


	public function get_eduverres_result($edu_ver_id = array())
	{
		$this->db->select("eduverres.*,GROUP_CONCAT(eduver_files.file_name) as verifier_uploded_attc");

		$this->db->from('eduverres');

		$this->db->join("eduver",'eduver.id = eduverres.eduverid');

		$this->db->join("eduverres as ev2",'(ev2.eduverid = eduver.id and eduverres.id < ev2.id)','left');

		$this->db->join("eduver_files",'(eduver_files.eduver_id = eduverres.id AND eduver_files.status = 1) AND eduver_files.type = 1','left');

		if(!empty($edu_ver_id))
		{
			$this->db->where($edu_ver_id);
		}

		$this->db->where('ev2.modeofverf is null');

		$this->db->group_by('eduverres.id');

		$this->db->order_by('id', 'desc');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function save_update_edu_ver_result($arrdata,$arrwhere = array())
	{
		if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update('eduverres', $arrdata);

			record_db_error($this->db->last_query());

			return $result;
	    }
	    else
	    {
			$this->db->insert('eduverres', $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	    }
	}

	public function delete_uploaded_file($where = array())
	{	
		
		$this->db->where_in('id',$where);

		$this->db->set('status', STATUS_DELETED);

		$result = $this->db->update('eduver_files', array('status' => STATUS_DELETED));

		record_db_error($this->db->last_query());

		return $result;
	}

	public function add_university($arrdata,$arrwhere = array())
	{
		if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update('univers', $arrdata);

			record_db_error($this->db->last_query());

			return $result;
	    }
	    else
	    {
			$this->db->insert('univers', $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	    }
	}

	public function cand_education_details($id,$comp)
	{
		$this->db->select("education.school_college,education.major,education.year_of_passing,education.grade_class_marks,education.course_start_date,education.course_end_date,education.enrollment_no,education.roll_no,education.documents_provided,education.		university_board,education.qualification,education_files.file_name,education.clientid,ev1.verfstatus,status.status_value");

		$this->db->from('education');

		$this->db->join("education_files",'(education_files.education_id = education.id AND education_files.status = 1) AND education_files.type = 0','left');

		$this->db->join("education_result as ev1",'(ev1.education_id = education.id)','left');

		$this->db->join("education_result as ev2",'(ev2.education_id = education.id and ev1.id < ev2.id)','left');

		$this->db->where('ev2.verfstatus is null');
		
		$this->db->join("status",'(status.id = ev1.verfstatus)');

		$this->db->where('education.candsid',$id);

		if($comp)
		{
			$this->db->where('education.education_com_ref',$comp);
		}

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}
   

    	public function cand_education_details_view($id)
	{
		$this->db->select("education.candsid,education.education_com_ref,education.iniated_date,education.school_college,education.major,education.year_of_passing,education.grade_class_marks,education.course_start_date,education.course_end_date,education.enrollment_no,education.roll_no,education.documents_provided,education.		university_board,education.qualification,education_files.file_name,education.clientid,ev1.verfstatus,status.status_value");

		$this->db->from('education');

		$this->db->join("education_files",'(education_files.education_id = education.id AND education_files.status = 1) AND education_files.type = 0','left');

		$this->db->join("education_result as ev1",'(ev1.education_id = education.id)','left');

		$this->db->join("education_result as ev2",'(ev2.education_id = education.id and ev1.id < ev2.id)','left');

		$this->db->where('ev2.verfstatus is null');
		
		$this->db->join("status",'(status.id = ev1.verfstatus)');

		$this->db->where('education.candsid',$id);

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function education_ver_status_count($where)
	{
		$this->db->select('count(eduver.id) as count,IF(ev1.verfstatus IS NOT NULL, ev1.verfstatus ,"WIP") as verfstatus');

		$this->db->from('cands');
		
		$this->db->join("eduver",'cands.id = eduver.candsid');

		$this->db->join("clients",'(clients.id = cands.clientid AND clients.eduver = 1)');

		$this->db->join("eduverres as ev1",'(ev1.eduverid = eduver.id)','left');

		$this->db->join("eduverres as ev2",'(ev2.eduverid = eduver.id and ev1.id < ev2.id)','left');

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

	public function education_ver_by_status($component_status,$where)
	{
		$this->db->select("eduver.*,cands.ClientRefNumber,cands.cmp_ref_no,cands.CandidateName,cands.caserecddate,ev1.verfstatus,ev1.InsuffRaisedDate,ev1.InsuffClearDate,ev1.ClosureDate as closuredate,ev1.verfremarks as remark,eduver.reiniated_date,cands.overallstatus,ev1.created as last_modified");

		$this->db->from('eduver');

		$this->db->join("cands",'cands.id = eduver.candsid');

		if($where)
		{
			$clientid = $where['cands.clientid'];

			$this->db->join("clients","(clients.id = cands.clientid AND clients.eduver = 1) AND clients.id = $clientid");
		}
		else
		{
			$this->db->join("clients",'clients.id = cands.clientid AND clients.eduver = 1');
		}
		
		$this->db->join("eduverres as ev1",'(ev1.eduverid = eduver.id)','left');

		$this->db->join("eduverres as ev2",'(ev2.eduverid = eduver.id and ev1.id < ev2.id)','left');

		$this->db->where('ev2.verfstatus is null');
		
		if($component_status != 'all')
		{
			if($component_status == 'WIP')
			{
				$this->db->where('ev1.verfstatus','WIP');

				$this->db->or_where('ev1.verfstatus IS NULL', null, false);
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

	public function get_all_eduver_client($where_arry)
	{
		$this->db->select("cands.ClientRefNumber,cands.cmp_ref_no,cands.CandidateName,cands.caserecddate,ev1.verfstatus,ev1.InsuffRaisedDate,ev1.InsuffClearDate,ev1.ClosureDate as closuredate,ev1.verfremarks as remark,eduver.reiniated_date,cands.overallstatus,clients.tat_eduver as tat_days,ev1.verfremarks as remarks,clients.clientname,clients.id as client_id");

		$this->db->from('eduver');

		$this->db->join("cands",'cands.id = eduver.candsid');

		$this->db->join("clients",'(clients.id = cands.clientid AND clients.eduver = 1)');

		$this->db->join("eduverres as ev1",'(ev1.eduverid = eduver.id)','left');

		$this->db->join("eduverres as ev2",'(ev2.eduverid = eduver.id and ev1.id < ev2.id)','left');

		$this->db->where('ev2.verfstatus is null');
		
		if($where_arry)
		{
			$this->db->where($where_arry);
		}

		$this->db->order_by('cands.id', 'desc');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}
}