<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Assign_add_model extends CI_Model
{
	function __construct()
    {
		$this->tableName = 'addrver';

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

	public function add_com_ref()
	{
		$result = $this->db->select("SUBSTRING_INDEX(add_com_ref, '-',-1) as A_I")->order_by('id','desc')->limit(1)-> get($this->tableName)->row_array();
		return $result;
	}

	

    public function get_all_addrs_by_client_datatable_assign($where_arry = array(),$where,$columns)
	{
           
		$this->db->select("candidates_info.id as candidates_info_id,candidates_info.clientid,candidates_info.entity,candidates_info.package,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,addrver.id,addrver.add_com_ref,clients.clientname,addrver.address,addrver.city,addrver.pincode, addrver.state ,addrver.iniated_date,status.status_value,user_profile.user_name,addrverres.first_qc_approve,addrverres.first_qc_updated_on,addrverres.first_qu_reject_reason, (select created_on from address_activity_data where comp_table_id = addrver.id order by id desc limit 1) as last_activity_date,due_date,tat_status,(select vendor_name from vendors where vendors.id = addrver.vendor_id) as vendor_name");
       
		$this->db->from('addrver');

		$this->db->join("user_profile",'user_profile.id = addrver.has_case_id','left');

		$this->db->join("candidates_info",'candidates_info.id = addrver.candsid');

		$this->db->join("clients",'clients.id = addrver.clientid');
		
		$this->db->join("addrverres",'addrverres.addrverid = addrver.id','left');

		$this->db->join("status",'status.id = addrverres.verfstatus','left');

		$this->db->where('addrver.vendor_id =',0);

		$this->db->where('addrver.vendor_digital_id =',NULL);


		$this->db->where('(addrverres.var_filter_status = "wip" or addrverres.var_filter_status = "WIP")');
         
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
			$this->db->order_by('addrver.id','DESC');
		}
		
		$this->db->limit($where['length'],$where['start']);

		$result = $this->db->get();

	//	print_r($this->db->last_query());
		
		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_all_addrs_by_client_datatable_count_assign($where_arry = array(),$where,$columns)
	{
		$this->db->select("candidates_info.clientid,candidates_info.entity,candidates_info.package,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,addrver.id,addrver.add_com_ref,clients.clientname,'' as vendor_name,addrver.address,addrver.city,addrver.pincode,addrver.state,addrver.iniated_date,status.status_value,user_profile.user_name,addrverres.first_qc_approve,addrverres.first_qc_updated_on,addrverres.first_qu_reject_reason, (select created_on from address_activity_data where comp_table_id = addrver.id  order by id desc limit 1) as last_activity_date");

		$this->db->from('addrver');

		$this->db->join("user_profile",'user_profile.id = addrver.has_case_id','left');

		$this->db->join("candidates_info",'candidates_info.id = addrver.candsid');

		$this->db->join("clients",'clients.id = addrver.clientid');

		$this->db->join("addrverres",'(addrverres.addrverid = addrver.id)','left');

		$this->db->join("status",'status.id = addrverres.verfstatus','left');

		$this->db->where('addrver.vendor_id =',0);

		$this->db->where('addrver.vendor_digital_id =',NULL);


		$this->db->where('(addrverres.var_filter_status = "wip" or addrverres.var_filter_status = "WIP")');

     
		
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
			$this->db->order_by('addrver.id','DESC');
		}
		
		$result = $this->db->get();
       
		record_db_error($this->db->last_query());
  
		return $result->result_array();
	}

     
    
	public function get_address_details($where_arry = array())
	{
		$this->db->select("addrver.*,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.CandidateName,(select clientname from clients where clients.id = addrver.clientid limit 1) as clientname,(select user_name from user_profile where user_profile.id = addrver.has_case_id) as executive_name,addrverres.id addrverres_id,(select status_value from status where status.id = addrverres.verfstatus limit 1) as verfstatus,
			addrverres.res_address_type,addrverres.res_address,addrverres.res_stay_from,addrverres.res_stay_from,addrverres.res_stay_to,addrverres.neighbour_1,addrverres.neighbour_details_1,addrverres.neighbour_2,addrverres.neighbour_details_2,addrverres.mode_of_verification,addrverres.resident_status,addrverres.landmark,addrverres.verified_by,addrverres.addr_proof_collected,addrverres.remarks,addrverres.first_qc_approve,res_city,res_pincode,res_state,due_date,tat_status,closuredate,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,addrver.iniated_date,candidates_info.caserecddate,add_com_ref");

		$this->db->from('addrver');

		$this->db->join("addrverres",'addrverres.addrverid = addrver.id');

		$this->db->join("candidates_info",'candidates_info.id = addrver.candsid');

		$this->db->join("addrver_files",'(addrver_files.addrver_id = addrver.id AND addrver_files.status = 1 AND addrver_files.type = 0 )','left');

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
		$this->db->select('*')->from('addrver_insuff');

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

			$result = $this->db->update('addrver_insuff', $arrdata);
			
			record_db_error($this->db->last_query());
			
			return $result;
	    }
	    else
	    {
			$this->db->insert('addrver_insuff', $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	    }
	}

	public function select_insuff_join($where_array)
	{
		$this->db->select('addrver_insuff.*,(select user_name from user_profile where id =  addrver_insuff.created_by limit 1) as insuff_raised_by,(select user_name from user_profile where id = addrver_insuff.insuff_cleared_by limit 1) as insuff_cleared_by');

		$this->db->from('addrver_insuff');

		$this->db->where($where_array);
		
		$this->db->where('addrver_insuff.status !=',3);

		$this->db->order_by('addrver_insuff.id','asc');

		return $this->db->get()->result_array();
	}
	
	public function save_update_result($arrdata,$arrwhere = array())
	{
	    if(!empty($arrwhere)) {
			$this->db->where($arrwhere);
			return $this->db->update('addrverres', $arrdata);
	    } else {
			$this->db->insert('addrverres', $arrdata);
			return $this->db->insert_id();
	    }
	}

	public function export_sql($where) { 
		
		$sql = "SELECT (select clientname from clients where clients.id = candidates_info.clientid limit 1) as
			client_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name, ClientRefNumber,cmp_ref_no,CandidateName,DATE_FORMAT(caserecddate,'%d-%m-%Y') as caserecddate, (select status_value from status where status.id = addrverres.verfstatus limit 1) as verfstatus,add_com_ref,vendor_id,(select user_name from user_profile where user_profile.id = addrver.has_case_id) as executive_name,
			address_type,address,city,pincode,state,stay_from,stay_to,DATE_FORMAT(iniated_date,'%d-%m-%Y') as iniated_date,DATE_FORMAT(due_date,'%d-%m-%Y') as due_date,tat_status,first_qc_updated_on,mode_of_verification,resident_status,landmark,neighbour_1,neighbour_details_1,neighbour_2,neighbour_details_2,verified_by,addr_proof_collected,addrverres.remarks,DATE_FORMAT(closuredate,'%d-%m-%Y') as closuredate,first_qc_approve,(select created_on from address_activity_data where comp_table_id = addrver.id order by id desc limit 1) as last_activity_date
			FROM addrver 
			INNER JOIN candidates_info ON candidates_info.id = addrver.candsid 
			INNER JOIN addrverres ON addrverres.addrverid = addrver.id $where";
		$query = $this->db->query($sql);

		return $query->result_array();
	}

	public function get_mode_of_verification($where)
	{
		$this->db->select('mode_of_verification');

		$this->db->from('clients_details');
		
		$this->db->where($where);

        $this->db->limit(1);  

		$result  = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_all_addrs_by_client($where_arry = array())
	{	
		$this->db->select("addrver.*,clients.clientname,cands.ClientRefNumber,cands.cmp_ref_no,cands.CandidateName,membership_groups.name,a1.verfstatus,cands.caserecddate,a1.created_on as last_modified,a1.closuredate,a1.insuffraiseddate,a1.insuffcleardate,a1.insuffremarks,a1.insuff_raised_date_2,a1.insuff_clear_date_2,a1.insuff_remarks_2,a1.verfstatus as addres_status,a1.remark,a1.insuff_additional_remark_1,a1.insuff_additional_remark_2,cands.DateofBirth,cands.CandidatesContactNumber,cands.NameofCandidateFather,cands.ContactNo1");

		$this->db->from('addrver');

		$this->db->join("clients",'clients.id = addrver.clientid');

		$this->db->join("cands",'cands.id = addrver.candsid');

		$this->db->join("membership_groups",'membership_groups.groupID = addrver.assignedto');

		$this->db->join("addrverres as a1",'a1.addrverid = addrver.id','left');

		$this->db->join("addrverres as a2",'(a2.addrverid = addrver.id and a1.id < a2.id)','left');

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
		$this->db->select("addrverres.*,addrver.clientid as client_id,GROUP_CONCAT(addrverres_files.file_name) as file_names,addrver.candsid,GROUP_CONCAT(addrverres_files.id) as file_ids");

		$this->db->from('addrverres');

		$this->db->join("addrver",'addrver.id = addrverres.addrverid');

		$this->db->join("addrverres_files",'(addrverres_files.addrverres_id = addrverres.id AND status = 1)','left');

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

	public function select_adds_ver_result($id)
	{
		$this->db->select('a1.id');

		$this->db->from('addrver');
		
		$this->db->join("addrverres as a1",'(a1.addrverid = addrver.id AND addrver.clientid = 11)');

		$this->db->join("addrverres as a2",'(a2.addrverid = addrver.id and a1.id < a2.id)','left');

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

	public function save_update_addver_files($arrdata,$arrwhere = array())
	{
		if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update('addrverres_files', $arrdata);

			record_db_error($this->db->last_query());

			return $result;
	    }
	    else
	    {
			$this->db->insert('addrverres_files', $arrdata);

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

	public function upload_file_update($tableName = 'addrverres_files',$updateArray)
	{
		return $this->db->update_batch($tableName,$updateArray, 'id');
	}

	public function get_add_uploded_files($where_array)
	{
		$this->db->select('*');

		$this->db->from('addrverres_files');

		$this->db->where($where_array);

		$this->db->order_by('serialno','asc');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();	
	}

	public function address_ver_status_count($where = array())
	{
		$this->db->select('count(addrver.id) as total,IF(a1.verfstatus IS NOT NULL, a1.verfstatus ,"WIP") as overallstatus');

		$this->db->from('addrver');
		
		$this->db->join("addrverres as a1",'a1.addrverid = addrver.id','left');

		$this->db->join("addrverres as a2",'(a2.addrverid = addrver.id and a1.id < a2.id)','left');

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
					inner join addrver on addrver.candsid  = cands.id
					inner join addrverres as a1 on a1.addrverid = addrver.id
					left join addrverres as a2 on (a2.addrverid = addrver.id and a1.id < a2.id)
					where DATE_FORMAT(a1.created_on ,"%Y-%m-%d") >= "'.$date.'" AND DATE_FORMAT(a1.created_on ,"%Y-%m-%d") <= "'.$date.'" AND a1.verfstatus = "clear" AND a2.verfstatus is null';

		if($where)
		{
			$sql .= ' AND addrver.has_case_id = '.$where;
		}

		$query = $this->db->query($sql);

		$results = $query->row_array();
		
		return (!empty($results) ? $results['total_count'] : 0);
	}

	public function get_address_tat($where_array = false)
	{
		$this->db->select("addrver.reiniated_date,cands.ClientRefNumber,cands.cmp_ref_no,cands.CandidateName,cands.caserecddate,ev1.verfstatus,ev1.remark,ev1.closuredate,ev1.insuffcleardate,ev1.insuffremarks,cands.remarks as overallremark,cands.updated as overall_lastupdated,cands.overallstatus,clients.clientname,clients.tat_addrver as tat_days");

		$this->db->from('cands');

		$this->db->join("addrver",'cands.id = addrver.candsid');

		$this->db->join("clients",'(clients.id = cands.clientid AND clients.addrver = 1)');

		$this->db->join("addrverres as ev1",'ev1.addrverid = addrver.id','left');

		$this->db->join("addrverres as ev2",'(ev2.addrverid = addrver.id and ev1.id < ev2.id)','left');

		if($where_array)
		{
			$this->db->where($where_array);
		}

		$this->db->where('ev2.verfstatus is null');

		$this->db->order_by('addrver.id', 'desc');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_assigned_cases($where_array = array())
	{
		$this->db->select("addrver.*,cands.ClientRefNumber,cands.cmp_ref_no,cands.CandidateName,cands.caserecddate,cands.updated as closuredate,cands.overallstatus,membership_users.custom1");

		$this->db->from('addrver');

		$this->db->join("cands",'cands.id = addrver.candsid');

		$this->db->join("membership_users",'membership_users.id = addrver.has_case_id');
		
		$this->db->join("addrverres",'addrverres.addrverid = addrver.id','left outer');

		$this->db->where("addrverres.addrverid IS NULL",NULL,false);

		if($where_array)
		{
			$this->db->where($where_array);
		}

		$this->db->order_by('addrver.id', 'desc');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function vendor_assigned_cases($where_array)
	{
		$this->db->select("addrver.id,addrver.verder_address_name, addrver.address,addrver.city,addrver.pincode ,cands.ClientRefNumber,cands.cmp_ref_no,cands.CandidateName,cands.caserecddate,cands.overallstatus,membership_users.custom1,addrver.vendor_reject_reason,addrver.vendor_rejected_on");

		$this->db->from('addrver');

		$this->db->join("cands",'cands.id = addrver.candsid');

		$this->db->join("membership_users",'membership_users.id = addrver.has_case_id');

		if($where_array)
		{
			$this->db->where($where_array);
		}

		$this->db->order_by('addrver.id', 'desc');
		
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
		$this->db->select('addrver.id,addrver.address,addrver.city,addrver.state,addrver.pincode,cands.cmp_ref_no,cands.CandidateName,clients.clientname,clients.id as client_id');

		$this->db->from('addrver');

		$this->db->join("cands",'cands.id = addrver.candsid');

		$this->db->join("clients",'(clients.id = cands.clientid AND clients.addrver = 1)');

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

	    	return $this->db->update_batch('addrver', $arrwhere, 'id');
	    }
	}

	public function vendor_assigned_case_to_app_user($arrwhere)
	{
		$this->db->select('addrver.id,addrver.address,addrver.city,addrver.state,addrver.pincode,cands.cmp_ref_no');

		$this->db->from('addrver');

		$this->db->join("cands",'cands.id = addrver.candsid');

		$this->db->join('app_users','app_users.id = addrver.vendor_appuser_id');

		$this->db->join("addrverres",'addrverres.addrverid = addrver.id','left');

		$this->db->where('addrverres.id is null');
		
		$this->db->where($arrwhere);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function vendor_completed_case($arrwhere)
	{
		$this->db->select('addrver.id,addrver.address,addrver.city,addrver.state,addrver.pincode,cands.cmp_ref_no,addrverres.created_on');

		$this->db->from('addrver');

		$this->db->join("cands",'cands.id = addrver.candsid');

		$this->db->join('app_users','app_users.id = addrver.vendor_appuser_id');

		$this->db->join("addrverres",'addrverres.addrverid = addrver.id');
		
		$this->db->where($arrwhere);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function select_client_list_assign_add_view($tableName,$return_as_strict_row,$select_array)
	{
		$this->db->select($select_array);

		$this->db->from($tableName);

        $this->db->join("addrver",'addrver.clientid = clients.id');


		$this->db->join("addrverres",'addrverres.addrverid =addrver.id');
		
		$this->db->join("candidates_info",'candidates_info.id = addrver.candsid');
		

		$this->db->join("status",'status.id = addrverres.verfstatus');

		$this->db->where('(addrverres.var_filter_status = "wip" or addrverres.var_filter_status = "WIP")');
		
        $this->db->where('addrver.vendor_id =',0);


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