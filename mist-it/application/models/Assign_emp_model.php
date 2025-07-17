<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Assign_emp_model extends CI_Model
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

	public function emp_com_ref()
	{
		$result = $this->db->select("SUBSTRING_INDEX(emp_com_ref, '-',-1) as A_I")->order_by('id','desc')->limit(1)-> get($this->tableName)->row_array();
		return $result;
	}

	

     public function get_all_emp_by_client_datatable_assign($where_arry = array(),$where,$columns)
	{
           
		$this->db->select("candidates_info.id as candidates_info_id, candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,empver.id,empver.emp_com_ref,clients.clientname,empver.nameofthecompany,empver.deputed_company,empver.employment_type,empver.locationaddr,empver.citylocality,empver.pincode,empver.state,empver.iniated_date,status.status_value,user_profile.user_name,empverres.first_qc_approve,empverres.first_qc_updated_on,empverres.first_qu_reject_reason, (select created_on from empver_activity_data where comp_table_id = empver.id order by id desc limit 1) as last_activity_date,due_date,tat_status,(select vendor_name from vendors where vendors.id = empver.vendor_id) as vendor_name");
       
		$this->db->from('empver');

		$this->db->join("user_profile",'user_profile.id = empver.has_case_id','left');

		$this->db->join("candidates_info",'candidates_info.id = empver.candsid');

		$this->db->join("clients",'clients.id = empver.clientid');
		
		$this->db->join("empverres",'empverres.empverid = empver.id','left');

		$this->db->join("status",'status.id = empverres.verfstatus','left');

		$this->db->where('empver.field_visit_status =','wip');

        $this->db->where('empver.vendor_id =',0);

        $this->db->where('(empverres.var_filter_status = "wip" or empverres.var_filter_status = "WIP")');
             

		if(is_array($where) && $where['search']['value'] != "" )
		{
			$this->db->like('candidates_info.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);

			$this->db->or_like('candidates_info.cmp_ref_no', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where['search']['value']);

			$this->db->or_like('candidates_info.overallstatus', $where['search']['value']);


		}

		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 
          
			$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		}
		else
		{
			$this->db->order_by('empver.id','DESC');
		}
		
		$this->db->limit($where['length'],$where['start']);

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_all_emp_by_client_datatable_count_assign($where_arry = array(),$where,$columns)
	{
		$this->db->select("candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,empver.id,empver.emp_com_ref,clients.clientname,empver.id,empver.emp_com_ref,clients.clientname,empver.nameofthecompany,empver.deputed_company,empver.employment_type,empver.locationaddr,empver.citylocality,empver.pincode,empver.state,empver.iniated_date,status.status_value,user_profile.user_name,empverres.first_qc_approve,empverres.first_qc_updated_on,empverres.first_qu_reject_reason, (select created_on from address_activity_data where comp_table_id = empver.id  order by id desc limit 1) as last_activity_date");

		$this->db->from('empver');

		$this->db->join("user_profile",'user_profile.id = empver.has_case_id','left');

		$this->db->join("candidates_info",'candidates_info.id = empver.candsid');

		$this->db->join("clients",'clients.id = empver.clientid');

		$this->db->join("empverres",'(empverres.empverid = empver.id)','left');

		$this->db->join("status",'status.id = empverres.verfstatus','left');

		$this->db->where('empver.field_visit_status =','wip');

        $this->db->where('empver.vendor_id =',0);

        $this->db->where('(empverres.var_filter_status = "wip" or empverres.var_filter_status = "WIP")');

       
		
		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like('candidates_info.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);

			$this->db->or_like('candidates_info.cmp_ref_no', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where['search']['value']);

			$this->db->or_like('candidates_info.overallstatus', $where['search']['value']);
		}

		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			
			$order_by = $where['order'][0]['dir']; 
      
			$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		}
		else
		{
			$this->db->order_by('empver.id','DESC');
		}
		
		$result = $this->db->get();
       
		record_db_error($this->db->last_query());
  
		return $result->result_array();
	}

     
    
	public function get_address_details($where_arry = array())
	{
		$this->db->select("empver.*,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.CandidateName,(select clientname from clients where clients.id = empver.clientid limit 1) as clientname,(select user_name from user_profile where user_profile.id = empver.has_case_id) as executive_name,empverres.id empverres_id,(select status_value from status where status.id = empverres.verfstatus limit 1) as verfstatus,
			empverres.res_address_type,empverres.res_address,empverres.res_stay_from,empverres.res_stay_from,empverres.res_stay_to,empverres.neighbour_1,empverres.neighbour_details_1,empverres.neighbour_2,empverres.neighbour_details_2,empverres.mode_of_verification,empverres.resident_status,empverres.landmark,empverres.verified_by,empverres.addr_proof_collected,empverres.remarks,empverres.first_qc_approve,res_city,res_pincode,res_state,due_date,tat_status,closuredate,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,empver.iniated_date,candidates_info.caserecddate,emp_com_ref");

		$this->db->from('empver');

		$this->db->join("empverres",'empverres.empverid = empver.id');

		$this->db->join("candidates_info",'candidates_info.id = empver.candsid');

		$this->db->join("empver_files",'(empver_files.empver_id = empver.id AND empver_files.status = 1 AND empver_files.type = 0 )','left');

		if($where_arry)
		{
			$this->db->where($where_arry);
		}
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function select_insuff($where_array)
	{
		$this->db->select('*')->from('empver_insuff');

		$this->db->where($where_array);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function save_update_insuff($arrdata,$arrwhere = array())
	{
		if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update('empver_insuff', $arrdata);
			
			record_db_error($this->db->last_query());
			
			return $result;
	    }
	    else
	    {
			$this->db->insert('empver_insuff', $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	    }
	}

	public function select_insuff_join($where_array)
	{
		$this->db->select('empver_insuff.*,(select user_name from user_profile where id =  empver_insuff.created_by limit 1) as insuff_raised_by,(select user_name from user_profile where id = empver_insuff.insuff_cleared_by limit 1) as insuff_cleared_by');

		$this->db->from('empver_insuff');

		$this->db->where($where_array);
		
		$this->db->where('empver_insuff.status !=',3);

		$this->db->order_by('empver_insuff.id','asc');

		return $this->db->get()->result_array();
	}
	
	public function save_update_result($arrdata,$arrwhere = array())
	{
	    if(!empty($arrwhere)) {
			$this->db->where($arrwhere);
			return $this->db->update('empverres', $arrdata);
	    } else {
			$this->db->insert('empverres', $arrdata);
			return $this->db->insert_id();
	    }
	}

	public function export_sql($where) { 
		
		$sql = "SELECT (select clientname from clients where clients.id = candidates_info.clientid limit 1) as
			client_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name, ClientRefNumber,cmp_ref_no,CandidateName,DATE_FORMAT(caserecddate,'%d-%m-%Y') as caserecddate, (select status_value from status where status.id = empverres.verfstatus limit 1) as verfstatus,emp_com_ref,vendor_id,(select user_name from user_profile where user_profile.id = empver.has_case_id) as executive_name,
			address_type,address,city,pincode,state,stay_from,stay_to,DATE_FORMAT(iniated_date,'%d-%m-%Y') as iniated_date,DATE_FORMAT(due_date,'%d-%m-%Y') as due_date,tat_status,first_qc_updated_on,mode_of_verification,resident_status,landmark,neighbour_1,neighbour_details_1,neighbour_2,neighbour_details_2,verified_by,addr_proof_collected,empverres.remarks,DATE_FORMAT(closuredate,'%d-%m-%Y') as closuredate,first_qc_approve,(select created_on from address_activity_data where comp_table_id = empver.id order by id desc limit 1) as last_activity_date
			FROM empver 
			INNER JOIN candidates_info ON candidates_info.id = empver.candsid 
			INNER JOIN empverres ON empverres.empverid = empver.id $where";
		$query = $this->db->query($sql);

		return $query->result_array();
	}























	public function get_all_addrs_by_client($where_arry = array())
	{	
		$this->db->select("empver.*,clients.clientname,cands.ClientRefNumber,cands.cmp_ref_no,cands.CandidateName,membership_groups.name,a1.verfstatus,cands.caserecddate,a1.created_on as last_modified,a1.closuredate,a1.insuffraiseddate,a1.insuffcleardate,a1.insuffremarks,a1.insuff_raised_date_2,a1.insuff_clear_date_2,a1.insuff_remarks_2,a1.verfstatus as addres_status,a1.remark,a1.insuff_additional_remark_1,a1.insuff_additional_remark_2,cands.DateofBirth,cands.CandidatesContactNumber,cands.NameofCandidateFather,cands.ContactNo1");

		$this->db->from('empver');

		$this->db->join("clients",'clients.id = empver.clientid');

		$this->db->join("cands",'cands.id = empver.candsid');

		$this->db->join("membership_groups",'membership_groups.groupID = empver.assignedto');

		$this->db->join("empverres as a1",'a1.empverid = empver.id','left');

		$this->db->join("empverres as a2",'(a2.empverid = empver.id and a1.id < a2.id)','left');

		$this->db->where('a2.verfstatus is null');

		if($where_arry)
		{
			$this->db->where($where_arry);
		}

		$this->db->order_by('a1.id', 'desc');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	

	public function get_address_ver_result($addsver_id = array())
	{
		$this->db->select("empverres.*,empver.clientid as client_id,GROUP_CONCAT(empverres_files.file_name) as file_names,empver.candsid,GROUP_CONCAT(empverres_files.id) as file_ids");

		$this->db->from('empverres');

		$this->db->join("empver",'empver.id = empverres.empverid');

		$this->db->join("empverres_files",'(empverres_files.empverres_id = empverres.id AND status = 1)','left');

		$this->db->group_by('empverres.id');

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

	public function select_adds_ver_result($id)
	{
		$this->db->select('a1.id');

		$this->db->from('empver');
		
		$this->db->join("empverres as a1",'(a1.empverid = empver.id AND empver.clientid = 11)');

		$this->db->join("empverres as a2",'(a2.empverid = empver.id and a1.id < a2.id)','left');

		$this->db->where('a2.verfstatus is null');

		$this->db->where($id);

		$this->db->order_by('a1.id', 'desc');
	
		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		$result_array = $result->result_array();

        if(!empty($result_array))
		{
            $result_array  = $result_array[0];
        }
        return $result_array;
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

	public function save_update_addver_files($arrdata,$arrwhere = array())
	{
		if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update('empverres_files', $arrdata);

			record_db_error($this->db->last_query());

			return $result;
	    }
	    else
	    {
			$this->db->insert('empverres_files', $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	    }
	}

	public function upload_vendor_assign($tableName,$updateArray,$where_arry)
	{		
		$this->db->where($where_arry);
	 	$this->db->update($tableName,$updateArray);
		return $this->db->affected_rows();
	}

	public function upload_file_update($tableName = 'empverres_files',$updateArray)
	{
		return $this->db->update_batch($tableName,$updateArray, 'id');
	}

	public function get_add_uploded_files($where_array)
	{
		$this->db->select('*');

		$this->db->from('empverres_files');

		$this->db->where($where_array);

		$this->db->order_by('serialno','asc');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();	
	}

	public function address_ver_status_count($where = array())
	{
		$this->db->select('count(empver.id) as total,IF(a1.verfstatus IS NOT NULL, a1.verfstatus ,"WIP") as overallstatus');

		$this->db->from('empver');
		
		$this->db->join("empverres as a1",'a1.empverid = empver.id','left');

		$this->db->join("empverres as a2",'(a2.empverid = empver.id and a1.id < a2.id)','left');

		$this->db->where('a2.verfstatus is null');

		if(!empty($where))
		{
			$this->db->where($where);
		}

		$this->db->group_by('overallstatus');

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		$results = convert_to_single_dimension_array($result->result_array(),'overallstatus','total');

		$return['total'] = array_sum($results);

        if(!empty($results)) {

        	if(array_key_exists('No Record Found',$results)) {
	            $results['Discrepancy'] = $results['Discrepancy']+$results['No Record Found'];
	        }

	        if(array_key_exists('Insufficiency I',$results)) {
	            $results['Insufficiency'] = $results['Insufficiency']+$results['Insufficiency I'];
	        }

	        if(array_key_exists('Insufficiency II',$results)) {
	            $results['Insufficiency'] = $results['Insufficiency']+$results['Insufficiency II'];
	        }

	        if(array_key_exists('WIP-Initiated',$results)) {
	            $results['WIP'] = $results['WIP']+$results['WIP-Initiated'];
	        }

            foreach ($results as $key => $value) {
                $return[str_replace('/','',str_replace(' ','',$key))] = $value;
            }
        }
        return $return;
	}

	public function get_aaddress_cases_by_date($date,$where = FALSE)
	{
		$sql = 'SELECT count(cands.id) as total_count FROM cands 
					inner join empver on empver.candsid  = cands.id
					inner join empverres as a1 on a1.empverid = empver.id
					left join empverres as a2 on (a2.empverid = empver.id and a1.id < a2.id)
					where DATE_FORMAT(a1.created_on ,"%Y-%m-%d") >= "'.$date.'" AND DATE_FORMAT(a1.created_on ,"%Y-%m-%d") <= "'.$date.'" AND a1.verfstatus = "clear" AND a2.verfstatus is null';

		if($where)
		{
			$sql .= ' AND empver.has_case_id = '.$where;
		}

		$query = $this->db->query($sql);

		$results = $query->row_array();
		
		return (!empty($results) ? $results['total_count'] : 0);
	}

	public function get_address_tat($where_array = false)
	{
		$this->db->select("empver.reiniated_date,cands.ClientRefNumber,cands.cmp_ref_no,cands.CandidateName,cands.caserecddate,ev1.verfstatus,ev1.remark,ev1.closuredate,ev1.insuffcleardate,ev1.insuffremarks,cands.remarks as overallremark,cands.updated as overall_lastupdated,cands.overallstatus,clients.clientname,clients.tat_empver as tat_days");

		$this->db->from('cands');

		$this->db->join("empver",'cands.id = empver.candsid');

		$this->db->join("clients",'(clients.id = cands.clientid AND clients.empver = 1)');

		$this->db->join("empverres as ev1",'ev1.empverid = empver.id','left');

		$this->db->join("empverres as ev2",'(ev2.empverid = empver.id and ev1.id < ev2.id)','left');

		if($where_array)
		{
			$this->db->where($where_array);
		}

		$this->db->where('ev2.verfstatus is null');

		$this->db->order_by('empver.id', 'desc');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_assigned_cases($where_array = array())
	{
		$this->db->select("empver.*,cands.ClientRefNumber,cands.cmp_ref_no,cands.CandidateName,cands.caserecddate,cands.updated as closuredate,cands.overallstatus,membership_users.custom1");

		$this->db->from('empver');

		$this->db->join("cands",'cands.id = empver.candsid');

		$this->db->join("membership_users",'membership_users.id = empver.has_case_id');
		
		$this->db->join("empverres",'empverres.empverid = empver.id','left outer');

		$this->db->where("empverres.empverid IS NULL",NULL,false);

		if($where_array)
		{
			$this->db->where($where_array);
		}

		$this->db->order_by('empver.id', 'desc');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function vendor_assigned_cases($where_array)
	{
		$this->db->select("empver.id,empver.verder_address_name, empver.address,empver.city,empver.pincode ,cands.ClientRefNumber,cands.cmp_ref_no,cands.CandidateName,cands.caserecddate,cands.overallstatus,membership_users.custom1,empver.vendor_reject_reason,empver.vendor_rejected_on");

		$this->db->from('empver');

		$this->db->join("cands",'cands.id = empver.candsid');

		$this->db->join("membership_users",'membership_users.id = empver.has_case_id');

		if($where_array)
		{
			$this->db->where($where_array);
		}

		$this->db->order_by('empver.id', 'desc');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function vendor_address_details($where_array = array())
	{
		$this->db->select('vendor_manager.vendor_name,vendor_manager.id');

		$this->db->from('vendor_manager');

		$this->db->join('app_users','(app_users.is_manager = vendor_manager.id and app_users.for_address = 1)');

		if($where_array)
		{
			$this->db->where($where_array);
		}

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$result = $result->result_array();

		if(!empty($result))
		{
			$result = convert_to_single_dimension_array($result,'id','vendor_name');
		}

		return $result;
	}

	public function vendor_cases($where_arry)
	{
		$this->db->select('empver.id,empver.address,empver.city,empver.state,empver.pincode,cands.cmp_ref_no,cands.CandidateName,clients.clientname,clients.id as client_id');

		$this->db->from('empver');

		$this->db->join("cands",'cands.id = empver.candsid');

		$this->db->join("clients",'(clients.id = cands.clientid AND clients.empver = 1)');

		if(!empty($where_arry))
		{
			$this->db->where($where_arry);
		}

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function vendor_assign_cases_to_app($arrwhere,$vendor_id)
	{
		if(!empty($arrwhere))
	    {
	    	$this->db->where($vendor_id);

	    	return $this->db->update_batch('empver', $arrwhere, 'id');
	    }
	}

	public function vendor_assigned_case_to_app_user($arrwhere)
	{
		$this->db->select('empver.id,empver.address,empver.city,empver.state,empver.pincode,cands.cmp_ref_no');

		$this->db->from('empver');

		$this->db->join("cands",'cands.id = empver.candsid');

		$this->db->join('app_users','app_users.id = empver.vendor_appuser_id');

		$this->db->join("empverres",'empverres.empverid = empver.id','left');

		$this->db->where('empverres.id is null');
		
		$this->db->where($arrwhere);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function vendor_completed_case($arrwhere)
	{
		$this->db->select('empver.id,empver.address,empver.city,empver.state,empver.pincode,cands.cmp_ref_no,empverres.created_on');

		$this->db->from('empver');

		$this->db->join("cands",'cands.id = empver.candsid');

		$this->db->join('app_users','app_users.id = empver.vendor_appuser_id');

		$this->db->join("empverres",'empverres.empverid = empver.id');
		
		$this->db->where($arrwhere);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function select_client_list_assign_emp_view($tableName,$return_as_strict_row,$select_array)
	{
		$this->db->select($select_array);

		$this->db->from($tableName);

        $this->db->join("empver",'empver.clientid = clients.id');


		$this->db->join("empverres",'empverres.empverid = empver.id');
		
		$this->db->join("candidates_info",'candidates_info.id = empver.candsid');
		

		$this->db->join("status",'status.id = empverres.verfstatus');

		$this->db->where('(empverres.var_filter_status = "wip" or empverres.var_filter_status = "WIP")');

		$this->db->where('empver.field_visit_status =','wip');
		
        $this->db->where('empver.vendor_id =',0);

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		//print_r($this->db->last_query());
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
}
?>