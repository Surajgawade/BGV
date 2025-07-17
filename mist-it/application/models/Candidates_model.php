<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Candidates_model extends CI_Model
{
	function __construct()
    {
		$this->tableName = 'candidates_info';

		$this->primaryKey = 'id';
	}

	public function select($return_as_strict_row,$select_array, $where_array = array())
	{
		$this->db->select($select_array);

		$this->db->from($this->tableName);

		$this->db->where($where_array);

		$this->db->order_by('id', 'desc');
		
		$result  = $this->db->get();
     //print_r($this->db->last_query());
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

	public function select_log($return_as_strict_row,$select_array, $where_array = array())
	{
		$this->db->select($select_array);

		$this->db->from("candidates_info_logs");

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


	/*public function cmp_ref_no($where)
	{
		$result = $this->db->select("SUBSTRING_INDEX(cmp_ref_no, '-',-1) as A_I")->where($where)->order_by('id','desc')->limit(1)-> get($this->tableName)->row_array();
		return $result;
	}*/

	/*public function cmp_ref_no()
	{
		$result = $this->db->select("SUBSTRING_INDEX(cmp_ref_no, '-',-1) as A_I")->order_by('id','desc')->limit(1)-> get($this->tableName)->row_array();
		return $result;
	}*/





    public function select_candidate( $where_array = array())
	{
		$this->db->select('candidates_info.id,candidates_info.clientid,candidates_info.entity,candidates_info.package,clients.clientname,ClientRefNumber,cmp_ref_no,CandidateName,CandidateName,entity,CandidateName,package,caserecddate,status.status_value,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,candidates_info.final_qc_send_mail_timestamp');

		$this->db->from("candidates_info");

		$this->db->join("status",'status.id = candidates_info.overallstatus');

		$this->db->join("clients",'clients.id = candidates_info.clientid');


		$this->db->where($where_array);
		
		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		$result_array = $result->result_array();
		
        return $result_array;
	}

	public function update_auto_increament_value($arrdata,$arrwhere = array())
	{
        
		   $this->db->where($arrwhere);

		   $result = $this->db->update('candidates_info', $arrdata);

		   record_db_error($this->db->last_query());
		
		  return $result;

	}

	public function get_candidate_last_id()
	{

        $result = $this->db->select('id')->order_by('id','desc')->limit(1)-> get('candidate_generate_unique_id')->row('id');
        
		return $result;

	}

	/*public function cmp_ref_no()
	{
		$result = $this->db->select("SUBSTRING_INDEX(cmp_ref_no, '-',-1) as A_I")->order_by('id','desc')->limit(1)-> get($this->tableName)->row_array();
		return $result;
	}*/

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
       
    public function save_candidate_file($arrdata)
	{
	   
	   
		$this->db->insert('candidate_files', $arrdata);

		record_db_error($this->db->last_query());

		return $this->db->insert_id();
	    
	}  

	public function select_activity_log($where_array)
	{
	   
	   $this->db->select('activity_log.*');

		$this->db->from("activity_log");

		$this->db->where($where_array);
		
		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		$result_array = $result->result_array();
		
        return $result_array;
	   
	}  

	public function save_candidate($arrdata)
	{
	   
	    $this->db->insert('candidates_info_logs', $arrdata);

	    record_db_error($this->db->last_query());

	    return $this->db->insert_id();
	   
	}

	public function save_activity_log($arrdata)
	{
	   
	    $this->db->insert('activity_log', $arrdata);

	    record_db_error($this->db->last_query());

	    return $this->db->insert_id();
	   
	}

    public function update_activity_log($arrdata,$arrwhere = array())
	{

	    if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update('activity_log', $arrdata);

			record_db_error($this->db->last_query());

			return $result;
	    }
	   
	}
    
    public  function get_reporting_manager_id()
    {

    	
        $this->db->select('email,reporting_manager,firstname,lastname,designation,department');

		$this->db->from('user_profile');

		$this->db->where('user_profile.id',$this->user_info['id']);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
    
    }

     public  function get_client_manager_id($where_array)
    {

    	
        $this->db->select('clientname,clientmgr');

		$this->db->from('clients');

		$this->db->where($where_array);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
    
    }

     public  function get_client_manager_email_id($clientmanager_id)
    {

    	
        $this->db->select('email');

		$this->db->from('user_profile');
//print_r(explode('||',$clientmanager_id));
		$this->db->where_in('user_profile.id',explode('||',$clientmanager_id));

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
    
    }

	public  function get_reporting_manager_email_id($reportingmanager_id)
    {

    	
        $this->db->select('email');

		$this->db->from('user_profile');

		$this->db->where('user_profile.id',$reportingmanager_id);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
    
    }


	public function delete($arrwhere)
	{
	  $result =  $this->db->delete($this->tableName, $arrwhere);

	  record_db_error($this->db->last_query());
	  
	  return $result;
	}


	public function save_update_candidate_files($arrdata,$arrwhere = array())
	{
		if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update('candidate_files', $arrdata);

			record_db_error($this->db->last_query());

			return $result;
	    }
	    else
	    {
			$this->db->insert('candidate_files', $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	    }
	}
	
	protected function filter_where_cond($where_arry)
	{

		
		$where = array('candidates_info.status' => STATUS_ACTIVE);
  
		if(isset($where_arry['status']) &&  $where_arry['status'] != '')	
		{
			if($where_arry['status'] != 'All')
			{
			$where['status.filter_status'] = $where_arry['status'];
		    }
		   
		}
		else
		{
			$where['candidates_info.overallstatus'] = 1;
		}

		if(isset($where_arry['sub_status']) &&  $where_arry['sub_status'] != 0)	
		{
			$where['status.id'] = $where_arry['sub_status'];
		}

		if(isset($where_arry['client_id']) &&  $where_arry['client_id'] != 0)	
		{
			$where['candidates_info.clientid'] = $where_arry['client_id'];
		}

		if(isset($where_arry['entity']) &&  $where_arry['entity'] != 0)	
		{
			$where['candidates_info.entity'] = $where_arry['entity'];
		}

		if(isset($where_arry['package']) &&  $where_arry['package'] != 0)	
		{
			$where['candidates_info.package'] = $where_arry['package'];
		}
       
		return $where;
	}

	public function get_all_cand_by_datatable($where,$columns)
	{
	

	    $this->db->select("candidates_info.*,clients.clientname,clients.clientmgr,clients.sales_manager,status.status_value,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name");

		$this->db->from('candidates_info');
		
		$this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("status",'status.id = candidates_info.overallstatus');

		if($where['status'] == "Closed")
         {

         	 if(isset($where['start_date']) &&  $where['start_date'] != '' && isset($where['end_date']) &&  $where['end_date'] != '')	
		     {  
              
		     	$start_date  =  $where['start_date'];
	            $end_date  =  $where['end_date'];
	   
             
		     	$where3 = "DATE_FORMAT(`candidates_info`.`overallclosuredate`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";
                
                $this->db->where($where3); 

		     } 
         }


		$this->db->where($this->filter_where_cond($where));

		
       /* if(($this->user_info['tbl_roles_id'] != "1")) 
        {
          $this->db->like('clients.clientmgr', $this->user_info['id']);	
          $this->db->or_like('clients.sales_manager', $this->user_info['id']);    
        }*/
       
		

		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like('candidates_info.caserecddate', $where['search']['value']);

			$this->db->or_like('candidates_info.cmp_ref_no', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidatesContactNumber', $where['search']['value']);

			$this->db->or_like('candidates_info.ContactNo1', $where['search']['value']);

			$this->db->or_like('candidates_info.ContactNo2', $where['search']['value']);

			$this->db->or_like('candidates_info.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where['search']['value']);

			$this->db->or_like('status.status_value', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);	
		}

		if(!empty($where['order']))
		{

			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir'];

           /*
			if(!empty($column_name_index))
			{

			  $this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		    }
		    else
		    {
               $order_clause = "(case overallstatus when 6 THEN 0 else 1 end),(case overallstatus when 7 THEN 0 else 1 end),(case overallstatus when 8 THEN 0 else 1 end)";

		    	$this->db->order_by($order_clause);
		    }*/
            
			$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		}
		else
		{
			//$order_clause = "(case overallstatus when 6 THEN 0 else 1 end),(case overallstatus when 7 THEN 0 else 1 end),(case overallstatus when 8 THEN 0 else 1 end)";
            $this->db->order_by('candidates_info.id','desc');
		   // $this->db->order_by($order_clause);
		}

		$this->db->limit($where['length'],$where['start']);

		$result = $this->db->get();
	
		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_all_cand_by_datatable_count($where,$columns)
	{
		$this->db->select("candidates_info.*,clients.clientname,clients.clientmgr,clients.sales_manager");

		$this->db->from('candidates_info');

		$this->db->join("clients",'clients.id = candidates_info.clientid');
		
		$this->db->join("status",'status.id = candidates_info.overallstatus');

		if($where['status'] == "Closed")
         {

         	 if(isset($where['start_date']) &&  $where['start_date'] != '' && isset($where['end_date']) &&  $where['end_date'] != '')	
		     {  
              
		     	$start_date  =  $where['start_date'];
	            $end_date  =  $where['end_date'];
	   
             
		     	$where3 = "DATE_FORMAT(`candidates_info`.`overallclosuredate`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";
                
                $this->db->where($where3); 

		     } 
         }


		$this->db->where($this->filter_where_cond($where));

		/* if(($this->user_info['tbl_roles_id'] != "1")) 
        {
          $this->db->like('clients.clientmgr', $this->user_info['id']);	
          $this->db->or_like('clients.sales_manager', $this->user_info['id']);    
        }*/

		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like('candidates_info.caserecddate', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidatesContactNumber', $where['search']['value']);

			$this->db->or_like('candidates_info.ContactNo1', $where['search']['value']);

			$this->db->or_like('candidates_info.ContactNo2', $where['search']['value']);

			$this->db->or_like('candidates_info.cmp_ref_no', $where['search']['value']);

			$this->db->or_like('candidates_info.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where['search']['value']);

			$this->db->or_like('status.status_value', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);	
		}

		if(!empty($where['order']))
		{

			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir'];
            
			$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		}
		else
		{
			$this->db->order_by('candidates_info.id','DESC');
		}

				
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}


	
	public function candidate_entity_pack_details($where_arry)
	{
		$this->db->select("candidates_info.id as candsid,candidates_info.CandidatesContactNumber,candidates_info.ContactNo1,candidates_info.ContactNo2,candidates_info.DateofBirth,candidates_info.gender,candidates_info.NameofCandidateFather,candidates_info.clientid,candidates_info.CandidateName,candidates_info.caserecddate,candidates_info.cmp_ref_no,candidates_info.ClientRefNumber,(select clientname from clients where clients.id = candidates_info.clientid limit 1) as clientname,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,(select id from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_id,(select id from entity_package where entity_package.id = candidates_info.package limit 1) as package_id,candidates_info.prasent_address,candidates_info.cands_state,candidates_info.cands_city,candidates_info.cands_pincode");

		$this->db->from('candidates_info');	
		
		$this->db->where($where_arry);

		return $this->db->get()->result_array();
	}
   

    public function get_all_cand_with_search_insufficiency($where,$columns)
	{

		$this->db->select("candidates_info.id,candidates_info.clientid,clients.clientmgr,clients.sales_manager,candidates_info.CandidateName,candidates_info.caserecddate,candidates_info.Location,(select entity_package_name  from entity_package where id=candidates_info.entity and is_entity_package = 1) as entity_name,(select entity_package_name  from entity_package where id=candidates_info.package and is_entity_package = 2) as package_name,candidates_info.Department,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.overallstatus,candidates_info.remarks,clients.clientname,candidates_info.overallclosuredate,candidates_info.ClientRefNumber AS EmployeeCode,status.status_value");

			

			if(is_array($where) && $where['search']['value'] != "")
			{
				$this->db->like($this->tableName.'.ClientRefNumber', $where['search']['value']);

				$this->db->or_like('clients.clientname', $where['search']['value']);

				$this->db->or_like($this->tableName.'.cmp_ref_no', $where['search']['value']);

				$this->db->or_like($this->tableName.'.CandidateName', $where['search']['value']);

				$this->db->or_like($this->tableName.'.CandidatesContactNumber', $where['search']['value']);

				$this->db->or_like($this->tableName.'.EmployeeCode', $where['search']['value']);

				$this->db->or_like($this->tableName.'.overallstatus', $where['search']['value']);

			}

	        $this->db->limit($where['length'],$where['start']);
	        //$this->db->limit(50,0);


	        $this->db->from('candidates_info');

	        $this->db->join("status",'status.id = candidates_info.overallstatus');
 
	        $this->db->join("clients",'clients.id = candidates_info.clientid');

	      
            $this->db->where("candidates_info.overallstatus",5); 

            $this->db->where("candidates_info.status",STATUS_ACTIVE); 


            if(($this->user_info['department'] == "client services")) 
	        {
	          $this->db->where('clients.clientmgr', $this->user_info['id']);    
	        }
	        if(($this->user_info['department'] == "sales")) 
	        {
	          $this->db->where('clients.sales_manager', $this->user_info['id']);    
	        }
       


            if(isset($where['clientid']) &&  $where['clientid'] != 0  &&  $where['clientid'] != "")	
		    {
			
			    $this->db->where("candidates_info.clientid", $where['clientid']);
			}
			
			if(isset($where['entity']) &&  $where['entity'] != "" && $where['entity'] != 0)	
			{
				$this->db->where("candidates_info.clientid", $where['entity']);
			}

			if(isset($where['package']) &&  $where['package'] != 0 && $where['package'] != "")	
			{
				$this->db->where("candidates_info.package",$where['package']);
			}

        


			$this->db->order_by('candidates_info.id', 'desc');
			
			
           
	        $result = $this->db->get();
	        $str = $this->db->last_query();
//print_r($str);
			record_db_error($this->db->last_query());

			return $result->result_array();
		}

   public function select_cadidate_report_join($where_arry)
   {
     
		$this->db->select('activity_log.*,(select user_name from user_profile where id =  activity_log.created_by limit 1) as username');

		$this->db->from('activity_log');

		$this->db->where($where_arry);

		$this->db->where('activity_log.action !=', "Follow Up");

		$this->db->order_by('activity_log.id', 'desc');
		
	    $result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	
   }

	public function get_all_cand_by($client_id = NULL)
	{
		$this->db->select("candidates_info.*,clients.clientname");

		$this->db->from('candidates_info');

		$this->db->join("clients",'clients.id = candidates_info.clientid');

		if($client_id)
		{
			$this->db->where("candidates_info.clientid",$client_id);
		}
		$this->db->order_by('id', 'desc');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function select_logs($where_arry = array())
	{
		$this->db->select("candidates_info_logs.*,clients.clientname,user_profile.user_name as created_by");

		$this->db->from('candidates_info_logs');

		$this->db->join("clients",'clients.id = candidates_info_logs.clientid');

		$this->db->join("user_profile",'user_profile.id = candidates_info_logs.created_by');

		if(!empty($where_arry))
		{
			$this->db->where($where_arry);
		}
		$this->db->order_by('candidates_info_logs.created_on','desc');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	


	public function select_file($select_array,$where_array)
	{
		$this->db->select($select_array);

		$this->db->from('candidate_files');

		$this->db->where($where_array);

		$this->db->order_by('id', 'desc');
		
		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_component_details($where_arry)
	{
		$this->db->select('candidates_info.*,component_id,component_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name, (select entity_package_name from entity_package  where entity_package.id= candidates_info.package limit 1) as package_name,(select clientname from clients where clients.id =  candidates_info.clientid limit 1) as clientname,(select comp_logo from clients where  clients.id = candidates_info.clientid limit 1) as comp_logo,(select status_value from status where status.id = candidates_info.overallstatus) as overallstatus_value');

		$this->db->from('candidates_info');

		$this->db->join('clients_details','(candidates_info.clientid = clients_details.tbl_clients_id and candidates_info.entity = clients_details.entity and candidates_info.package = clients_details.package)');

		$this->db->where($where_arry);

		$result  = $this->db->get();
		
		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}


	public function get_candidates_info_info_report($where_arry = array())
	{
		$this->db->select("candidates_info.*,component_id,component_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name, (select entity_package_name from entity_package  where entity_package.id= candidates_info.package limit 1) as package_name,(select clientname from clients where clients.id = candidates_info.clientid limit 1) as clientname,(select comp_logo from clients where  clients.id = candidates_info.clientid limit 1) as comp_logo,(select status_value from status where status.id = candidates_info.overallstatus) as overallstatus_value");

		$this->db->from('candidates_info');

	    $this->db->join('clients_details','(candidates_info.clientid = clients_details.tbl_clients_id and candidates_info.entity = clients_details.entity and candidates_info.package = clients_details.package)');

		if(!empty($where_arry))
		{
			$this->db->where($where_arry);
		}

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$result_array = $result->result_array();

	    if(count($result_array)==1) // ensure only one record has been previously inserted
	    {
	        $result_array  = $result_array[0];
	    }
        
        return $result_array;
	}

	public function education_info_report($empver_aary = array())
	{

		$this->db->select("eduver.*,GROUP_CONCAT(eduver_files.file_name order by serialno) as file_names,univers.universityname,qualification_master.qualification as qualification_degree,ev1.verfstatus,ev1.modeofverf,ev1.verfremarks,ev1.verfname,ev1.verfdesgn,ev1.verfcontacts,ev2.modeofverf as em");

		$this->db->from('eduver');

		$this->db->join("eduverres as ev1",'(ev1.eduverid = eduver.id)','left');

		$this->db->join("eduverres as ev2",'(ev2.eduverid = eduver.id and ev1.id < ev2.id)','left');

		$this->db->join("eduver_files",'(eduver_files.eduver_id = ev1.id AND eduver_files.status = 1) and eduver_files.type = 1','left');

		$this->db->join("univers",'univers.id = eduver.university');

		$this->db->join("qualification_master",'qualification_master.id = eduver.qualification');

		if(!empty($empver_aary))
		{
			$this->db->where($empver_aary);
		}

		$this->db->where('ev2.modeofverf is null');

		$this->db->group_by('eduver.id');

		$this->db->order_by('id', 'desc');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function employment_info_report($empver_aary = array())
	{
		$this->db->select('empver.*, knowncos.coname,GROUP_CONCAT(empverres_files.file_name order by serialno) as file_names,ev1.verfstatus, CONCAT( DATE_FORMAT(ev1.employed_from,"%D-%b-%y") , " to " , DATE_FORMAT(ev1.employed_to,"%D-%b-%y")) as employment_period, ev1.reportingmanager as ver_reportingmanager, ev1.reasonforleaving as ver_reasonforleaving,ev1.verfname,ev1.verfdesgn,ev1.mcaregn,ev1.domainname,ev1.justdialwebcheck,ev1.fmlyowned,ev1.emp_designation,ev1.integrity_disciplinary_issue,ev1.exitformalities,ev1.modeofverification,ev1.addlhrcomments,ev1.remuneration as ver_remuneration,ev1.empid as ver_empid,CONCAT( DATE_FORMAT(empver.empfrom,"%D-%b-%y") , " to ",DATE_FORMAT(empver.empto,"%D-%b-%y")) as enter_employment_period,ev1.emp_designation as ver_emp_designation');

		$this->db->from('empver');

		$this->db->join("knowncos",'knowncos.id = empver.nameofthecompany');

		$this->db->join("empverres as ev1",'ev1.empverid = empver.id','left');

		$this->db->join("empverres as ev2",'(ev2.empverid = empver.id and ev1.id < ev2.id)','left');

		$this->db->join("empverres_files",'(empverres_files.empver_id = ev1.id AND empverres_files.status = 1) AND empverres_files.type = 1','left');

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

	public function address_info_report($address_aary = array())
	{
		$this->db->select("CONCAT(addrver.address,' ',addrver.city, ',', LOWER(addrver.state),', ', addrver.pincode ) as full_aadress ,GROUP_CONCAT(addrverres_files.file_name order by serialno) as file_names, CONCAT( DATE_FORMAT(add1.stayfrom,'%D-%b-%y') , ' to ' , DATE_FORMAT(add1.stayto,'%D-%b-%y')) as period_of_stay,add1.remark,add1.verfstatus,add1.verifiername,add1.modeofverification,add1.residentstatus,addrver.clientid,add1.stayfrom,add1.stayto");

		$this->db->from('addrver');

		$this->db->join("addrverres as add1",'add1.addrverid = addrver.id','left');

		$this->db->join("addrverres as add2",'(add2.addrverid = addrver.id and add1.id < add2.id)','left');

		$this->db->join("addrverres_files",'(addrverres_files.addrverres_id = add1.id AND addrverres_files.status = 1)','left');

		if(!empty($address_aary))
		{
			$this->db->where($address_aary);
		}

		$this->db->where('add2.verfstatus is null');

		$this->db->group_by('addrver.id');

		$this->db->order_by('addrver.id', 'desc');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function pcc_info_report($where_aary = array())
	{
		$this->db->select("p1.verification_address,p1.verfstatus,p1.date_of_visit_police,p1.name_and_des_of_police,p1.contact_no_of_police,p1.verification_result_record,p1.modeofverification,p1.attchments_ver,p1.verification_remarks as remarks,candidates_info.clientid");

		$this->db->from('candidates_info');

		$this->db->join("pcc_result as p1",'p1.candidates_info_id = candidates_info.id');

		$this->db->join("pcc_result as p2",'(p2.candidates_info_id = candidates_info.id and p1.id < p2.id)','left');

		if(!empty($where_aary))
		{
			$this->db->where($where_aary);
		}

		$this->db->where('p2.verfstatus is null');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function courtinfo_report($where_arry = array())
	{
	$this->db->select("courtver.*,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.CandidateName,candidates_info.DateofBirth,candidates_info.NameofCandidateFather,candidates_info.MothersName,candidates_info.CandidatesContactNumber,candidates_info.clientid,GROUP_CONCAT(courtver_files.file_name) as file_names");

		$this->db->from('courtver');

		$this->db->join("candidates_info",'candidates_info.id = courtver.candidates_infoid');

		$this->db->join("courtver_files",'(courtver_files.courtver_id = courtver.id AND courtver_files.status = 1)','left');

		if($where_arry)
		{
			$this->db->where($where_arry);
		}

		$this->db->group_by('courtver.id');
		
		$this->db->order_by('courtver.id', 'desc');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function global_db_report($where_array)
	{
		$this->db->select('glodbver.remarks,glodbver.attchments_ver as file_names,glodbver.attchments_ver1 as file_names1,glodbver.verifiername,glodbver.additional_comment,glodbver.verfstatus,glodbver.clientid');

		$this->db->from('glodbver');

		$this->db->where($where_array);

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function references_report($ref_aary = array())
	{
		$this->db->select("candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.CandidateName,candidates_info.caserecddate,refver.referencename,refver.designation,refver.contact_number,ref1.created as last_modified,ref1.ver_reference_name,ref1.ver_designation,ref1.ver_contactnumber,ref1.additional_remarks,ref1.verfstatus as refverfstatus,refver.id,refver.clientid,refver.assignedto,ref1.insuffraiseddate,ref1.insuffcleardate,ref1.insuffremark,ref1.id as refveri_id,GROUP_CONCAT(refverres_files.file_name) as file_names,ref1.closuredate,candidates_info.id as candidates_infoid");

		$this->db->from('candidates_info');

		$this->db->join("clients",'(clients.id = candidates_info.clientid and clients.refver = 1)');

		$this->db->join('refver','refver.candidates_infoid = candidates_info.id');

		$this->db->join("refverres as ref1",'ref1.refverid = refver.id','left');

		$this->db->join("refverres as ref2",'(ref2.refverid = refver.id and ref1.id < ref2.id)','left');

		$this->db->join("refverres_files",'(refverres_files.refverres_id = ref1.id AND refverres_files.status = 1)','left');

		$this->db->where('ref2.verfstatus is null');

		if(!empty($ref_aary))
		{
			$this->db->where($ref_aary);
		}

		$this->db->group_by('refver.id');

		$this->db->order_by('refver.id', 'desc');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_all_cand_with_search($clientid,$entity_id,$package_id,$fil_by_status,$fil_by_sub_status)
	{
  
		$this->db->select("candidates_info.id,candidates_info.clientid,candidates_info.CandidateName,candidates_info.caserecddate,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.cands_email_id,candidates_info.CandidatesContactNumber,candidates_info.due_date_candidate,candidates_info.Location,clients.clientname,candidates_info.overallclosuredate,status.status_value,candidates_info.overallstatus,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,clients_details.component_id");

		$this->db->from('candidates_info');

		$this->db->join("clients",'clients.id = candidates_info.clientid');

	    $this->db->join("status",'status.id = candidates_info.overallstatus');

		$this->db->join("clients_details",'clients_details.tbl_clients_id = candidates_info.clientid and `clients_details`.`entity` = `candidates_info`.`entity` AND `clients_details`.`package` = `candidates_info`.`package`');


		if(!empty($clientid))
		{
			$this->db->where("candidates_info.clientid",$clientid);
		}
		if(!empty($entity_id))
		{
			$this->db->where("candidates_info.entity",$entity_id);
		}
		if(!empty($package_id))
		{
			$this->db->where("candidates_info.package",$package_id);
		}
		if(!empty($fil_by_status))
		{  
			if(empty($fil_by_sub_status))
		    {
				if($fil_by_status == "WIP")
				{
				$this->db->where("candidates_info.overallstatus",1);
			    }
			    if($fil_by_status == "Insufficiency")
				{
				$this->db->where("candidates_info.overallstatus",5);
			    }
			    if($fil_by_status == "Closed")
				{
					$where_condition = "(candidates_info.overallstatus = 3 or candidates_info.overallstatus = 4 or candidates_info.overallstatus = 6 or candidates_info.overallstatus = 7 or candidates_info.overallstatus = 8)";
			    	$this->db->where($where_condition);
			    }
		    }
		}
		if(!empty($fil_by_sub_status))
		{
			$this->db->where("candidates_info.overallstatus",$fil_by_sub_status);
		}

		$this->db->order_by('candidates_info.id', 'asc');
		
		$this->db->group_by('candidates_info.id');

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());
		
		return $result->result_array();
	} 

	public function get_all_cand_with_search_prepost($clientid,$entity_id,$package_id)
	{
		$this->db->select("candidates_info.id,candidates_info.clientid,candidates_info.CandidateName,candidates_info.caserecddate,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.due_date_candidate,candidates_info.Location,clients.clientname,candidates_info.overallclosuredate,status.status_value,candidates_info.overallstatus,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,clients_details.component_id");

		$this->db->from('candidates_info');

		$this->db->join("clients",'clients.id = candidates_info.clientid');

	    $this->db->join("status",'status.id = candidates_info.overallstatus');

		$this->db->join("clients_details",'clients_details.tbl_clients_id = candidates_info.clientid and `clients_details`.`entity` = `candidates_info`.`entity` AND `clients_details`.`package` = `candidates_info`.`package`');


		if($clientid)
		{
			
			$this->db->where("candidates_info.clientid",$clientid);

		}
		if($entity_id)
		{
			
			$this->db->where("candidates_info.entity",$entity_id);
		}
		if($package_id)
		{
			
			$this->db->where("candidates_info.package",$package_id);
		}

		$this->db->order_by('candidates_info.id', 'asc');
		
		$this->db->group_by('candidates_info.id');

		$result = $this->db->get();
		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}


	
	public function get_all_cand_with_search_for_insufficiency($clientid,$entity_id,$package_id,$fil_by_status,$fil_by_sub_status)
	{
		$this->db->select("candidates_info.id,candidates_info.clientid,candidates_info.CandidateName,candidates_info.caserecddate,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.due_date_candidate,candidates_info.Location,clients.clientname,candidates_info.overallclosuredate,status.status_value,candidates_info.overallstatus,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,clients_details.component_id");

		$this->db->from('candidates_info');

		$this->db->join("clients",'clients.id = candidates_info.clientid');

	    $this->db->join("status",'status.id = candidates_info.overallstatus');

		$this->db->join("clients_details",'clients_details.tbl_clients_id = candidates_info.clientid and `clients_details`.`entity` = `candidates_info`.`entity` AND `clients_details`.`package` = `candidates_info`.`package`');


		if($clientid)
		{
			
			$this->db->where("candidates_info.clientid",$clientid);

		}
		if($entity_id)
		{
			
			$this->db->where("candidates_info.entity",$entity_id);
		}
		if($package_id)
		{
			
			$this->db->where("candidates_info.package",$package_id);
		}
		if($fil_by_sub_status)
		{
			$this->db->where("candidates_info.overallstatus",$fil_by_sub_status);
		}

		$this->db->where("(candidates_info.overallstatus = '1' or candidates_info.overallstatus = '5')");

		$this->db->order_by('candidates_info.id', 'desc');
		
		$this->db->group_by('candidates_info.id');

		$result = $this->db->get();
		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_addres_ver_status_for_export($clientid)
	{
		$this->db->select("addrver.id,addrver.iniated_date,addrver.address,addrver.city,addrver.pincode,addrver.state,status.status_value as verfstatus,status.report_status as report_status, addrver_insuff.insuff_raise_remark,ev1.remarks,ev1.closuredate");

		$this->db->from('addrver');

		$this->db->join("addrver_insuff",'addrver_insuff.addrverid = addrver.id and addrver_insuff.status = 1','left');

		$this->db->join("addrverres as ev1",'ev1.addrverid = addrver.id','left');

		$this->db->join("addrverres as ev2",'(ev2.addrverid = addrver.id and ev1.id < ev2.id)','left');

		$this->db->join("status",'status.id = ev1.verfstatus','left');

		if($clientid)
		{
			$this->db->where($clientid);
		}

		$this->db->where('ev2.verfstatus is null');

		$this->db->order_by('addrver.id', 'ASC');
		
		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_education_ver_status_for_export($clientid)
	{
		$this->db->select("education.id,education.iniated_date,status.status_value as verfstatus,status.report_status as report_status,university_master.universityname,education_insuff.insuff_raise_remark,(select qualification from qualification_master where  qualification_master.id = education.qualification) as qualification_name,ev1.remarks");

		$this->db->from('education');

		$this->db->join("education_insuff",'education_insuff.education_id = education.id and education_insuff.status = 1','left');

		$this->db->join("education_result as ev1",'(ev1.education_id = education.id)','left');

		$this->db->join("education_result as ev2",'(ev2.education_id = education.id and ev1.id < ev2.id)','left');

        $this->db->join("status",'status.id = ev1.verfstatus','left');

		$this->db->join("university_master",'university_master.id = education.university_board','left');

		$this->db->where('ev2.verfstatus is null');
		
		if($clientid)
		{
			$this->db->where($clientid);
		}

		$this->db->order_by('education.id', 'ASC');
		
		$result = $this->db->get();
	
		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_employment_ver_status_for_export($clientid)
	{
      
		$this->db->select("empver.id,empver.iniated_date,status.status_value as verfstatus,status.report_status as report_status,company_database.coname,empverres_insuff.insuff_raise_remark,ev1.remarks,ev1.closuredate");

		$this->db->from('empver');

		$this->db->join("empverres_insuff",'empverres_insuff.empverres_id = empver.id and empverres_insuff.status = 1','left');

		$this->db->join("empverres as ev1",'ev1.empverid = empver.id','left');

		$this->db->join("empverres as ev2",'(ev2.empverid = empver.id and ev1.id < ev2.id)','left');

		$this->db->join("status",'status.id = ev1.verfstatus','left');
        
        $this->db->join("company_database",'company_database.id = empver.nameofthecompany','left');

		$this->db->where('ev2.verfstatus is null');

		if($clientid)
		{
			$this->db->where($clientid);
		}

		$this->db->order_by('empver.id', 'ASC');
				
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_refver_ver_status_for_export($clientid)
	{
         
		$this->db->select("reference.id,reference.iniated_date,status.status_value as verfstatus,status.report_status as report_status,reference.name_of_reference, reference_insuff.insuff_raise_remark,ref1.remarks");

		$this->db->from('reference');

		$this->db->join("reference_insuff",'reference_insuff.reference_id  = reference.id and reference_insuff.status = 1','left');

        $this->db->join("reference_result as ref1",'ref1.reference_id = reference.id','left');

		$this->db->join("reference_result as ref2",'(ref2.reference_id = reference.id and ref1.id < ref2.id)','left');

	    $this->db->join("status",'status.id = ref1.verfstatus','left');

		$this->db->where('ref2.verfstatus is null');

		if($clientid)
		{
			$this->db->where($clientid);
		}


		$this->db->order_by('reference.id', 'ASC');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}
   
    public function get_court_ver_status_for_export($clientid)
	{


		$this->db->select("courtver.id,courtver.iniated_date,status.status_value as verfstatus,status.report_status as report_status,courtver.street_address,courtver.city,courtver.pincode,courtver.state,courtver_insuff.insuff_raise_remark,ev1.remarks,ev1.closuredate");

		$this->db->from('courtver');

		$this->db->join("courtver_insuff",'courtver_insuff.courtver_id  = courtver.id and courtver_insuff.status = 1','left');

        $this->db->join("courtver_result as ev1",'ev1.courtver_id = courtver.id','left');

		$this->db->join("courtver_result as ev2",'(ev2.courtver_id = courtver.id and ev1.id < ev2.id)','left');

		$this->db->join("status",'status.id = ev1.verfstatus','left');


		$this->db->where('ev2.verfstatus is null');
		if($clientid)
		{
			$this->db->where($clientid);
		}

		$this->db->order_by('courtver.id', 'ASC');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_pcc_ver_status_for_export($where_arry = array())
	{   

		$this->db->select("pcc.id,pcc.iniated_date,status.status_value as verfstatus,status.report_status as report_status,pcc.street_address,pcc.city,pcc.pincode,pcc.state,pcc_insuff.insuff_raise_remark,ev1.remarks");

		$this->db->from('pcc');

		$this->db->join("pcc_insuff",'pcc_insuff.pcc_id  = pcc.id and pcc_insuff.status = 1','left');

        $this->db->join("pcc_result as ev1",'ev1.pcc_id = pcc.id','left');

		$this->db->join("pcc_result as ev2",'(ev2.pcc_id = pcc.id and ev1.id < ev2.id)','left');

		$this->db->join("status",'status.id = ev1.verfstatus','left');

		$this->db->where('ev2.verfstatus is null');

	
		if($where_arry)
		{
			$this->db->where($where_arry);
		}

		$this->db->order_by('pcc.id', 'ASC');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

   

    public function get_globdbver_ver_status_for_export($clientid)
	{
        
		$this->db->select("glodbver.id,glodbver.iniated_date,status.status_value as verfstatus,status.report_status as report_status,glodbver.street_address,glodbver.city,glodbver.pincode,glodbver.state,glodbver_insuff.insuff_raise_remark,ev1.remarks");

		$this->db->from('glodbver');

		$this->db->join("glodbver_insuff",'glodbver_insuff.glodbver_id = glodbver.id and glodbver_insuff.status = 1','left');      

		$this->db->join("glodbver_result as ev1",'ev1.glodbver_id = glodbver.id','left');

		$this->db->join("glodbver_result as ev2",'(ev2.glodbver_id = glodbver.id and ev1.id < ev2.id)','left');

		$this->db->join("status",'status.id = ev1.verfstatus','left');

		$this->db->where('ev2.verfstatus is null');
		
		if($clientid)
		{
			$this->db->where($clientid);
		}

		$this->db->order_by('glodbver.id', 'ASC');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_identity_ver_status_for_export($where_arry = array())
	{

		$this->db->select("identity.id,identity.iniated_date,status.status_value as verfstatus,status.report_status as report_status,identity.doc_submited,identity_insuff.insuff_raise_remark,ev1.remarks,ev1.closuredate");

		$this->db->from('identity');

		$this->db->join("identity_insuff",'identity_insuff.identity_id  = identity.id and identity_insuff.status = 1','left');

        $this->db->join("identity_result as ev1",'ev1.identity_id = identity.id','left');

		$this->db->join("identity_result as ev2",'(ev2.identity_id = identity.id and ev1.id < ev2.id)','left');

		$this->db->join("status",'status.id = ev1.verfstatus','left');


		$this->db->where('ev2.verfstatus is null');

		if($where_arry)
		{
			$this->db->where($where_arry);
		}

		$this->db->order_by('identity.id', 'ASC');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_credit_report_ver_status_for_export($where_arry = array())
	{
 
		$this->db->select("credit_report.id,credit_report.iniated_date,status.status_value as verfstatus,status.report_status as report_status,credit_report_insuff.insuff_raise_remark,ev1.remarks");

		$this->db->from('credit_report');

		$this->db->join("credit_report_insuff",'credit_report_insuff.credit_report_id  = credit_report.id and credit_report_insuff.status = 1','left');

        $this->db->join("credit_report_result as ev1",'ev1.credit_report_id = credit_report.id','left');

		$this->db->join("credit_report_result as ev2",'(ev2.credit_report_id = credit_report.id and ev1.id < ev2.id)','left');

		$this->db->join("status",'status.id = ev1.verfstatus','left');

		$this->db->where('ev2.verfstatus is null');

		if($where_arry)
		{
			$this->db->where($where_arry);
		}

		$this->db->order_by('credit_report.id', 'ASC');
		
		$result = $this->db->get();
		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	
	public function get_drugs_report_ver_status_for_export($where_arry = array())
	{
 
		$this->db->select("drug_narcotis.iniated_date,drug_narcotis.drug_test_code,status.status_value as verfstatus,status.report_status as report_status,drug_narcotis_insuff.insuff_raise_remark,ev1.remarks");

		$this->db->from('drug_narcotis');

		$this->db->join("drug_narcotis_insuff",'drug_narcotis_insuff.drug_narcotis_id  = drug_narcotis.id and drug_narcotis_insuff.status = 1','left');

        $this->db->join("drug_narcotis_result as ev1",'ev1.drug_narcotis_id = drug_narcotis.id','left');

		$this->db->join("drug_narcotis_result as ev2",'(ev2.drug_narcotis_id = drug_narcotis.id and ev1.id < ev2.id)','left');

		$this->db->join("status",'status.id = ev1.verfstatus','left');

		$this->db->where('ev2.verfstatus is null');

		if($where_arry)
		{
			$this->db->where($where_arry);
		}

		$this->db->order_by('drug_narcotis.id', 'ASC');
		
		$result = $this->db->get();
		record_db_error($this->db->last_query());

		return $result->result_array();
	}



	public function get_all_components_check($where_arry)
	{
		$this->db->select("candidates_info.id,candidates_info.clientid,candidates_info.CandidateName,candidates_info.caserecddate,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.overallstatus,candidates_info.remarks,clients.clientname,clients.addrver,clients.courtver,clients.crimver,clients.eduver,clients.empver,clients.narcver,clients.refver,clients.globdbver");

		$this->db->from('candidates_info');

		$this->db->join("clients",'clients.id = candidates_info.clientid');

		if(!empty($where_arry))
		{
			$this->db->where($where_arry);
		}

		$this->db->order_by('candidates_info.id', 'desc');
		
		$this->db->limit(1);

		$this->db->group_by('candidates_info.id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		$result = $result->result_array();

		if(!empty($result))
		{
			return $result[0];
		}

		return true;
	}

	public function get_addres_ver_status($clientid)
	{
		$this->db->select("candidates_info.overallstatus,status.action as verfstatus,ev1.var_filter_status,ev1.remarks as verification_remarks,ev1.closuredate");

		$this->db->from('candidates_info');

		$this->db->join("addrver",'candidates_info.id = addrver.candsid');

		$this->db->join("clients",'(clients.id = candidates_info.clientid)');

		$this->db->join("addrverres as ev1",'ev1.addrverid = addrver.id','left');

		$this->db->join("addrverres as ev2",'(ev2.addrverid = addrver.id and ev1.id < ev2.id)','left');

		$this->db->join("status",'status.id = ev1.verfstatus','left');

		if($clientid)
		{
			$this->db->where($clientid);
		}

		$this->db->where('ev2.verfstatus is null');

		$this->db->order_by('addrver.id', 'ASC');
		
		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_education_ver_status($clientid)
	{
		$this->db->select("candidates_info.overallstatus,status.action as verfstatus,ev1.var_filter_status,ev1.remarks as verification_remarks,ev1.closuredate");

		$this->db->from('education');

		$this->db->join("candidates_info",'candidates_info.id = education.candsid');

		$this->db->join("clients",'(clients.id = candidates_info.clientid)');

		$this->db->join("education_result as ev1",'(ev1.education_id = education.id)','left');

		$this->db->join("education_result as ev2",'(ev2.education_id = education.id and ev1.id < ev2.id)','left');

		$this->db->join("status",'status.id = ev1.verfstatus','left');

		$this->db->where('ev2.verfstatus is null');
		
		if($clientid)
		{
			$this->db->where($clientid);
		}

		$this->db->order_by('education.id', 'ASC');
		
		$result = $this->db->get();
	
		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_globdbver_ver_status($clientid)
	{
		$this->db->select("candidates_info.overallstatus,status.action as verfstatus,ev1.var_filter_status,ev1.remarks as verification_remarks,ev1.closuredate");

		$this->db->from('glodbver');

		$this->db->join("candidates_info",'candidates_info.id = glodbver.candsid');

		$this->db->join("clients",'(clients.id = candidates_info.clientid)');

		$this->db->join("glodbver_result as ev1",'ev1.glodbver_id = glodbver.id','left');

		$this->db->join("glodbver_result as ev2",'(ev2.glodbver_id = glodbver.id and ev1.id < ev2.id)','left');

		$this->db->join("status",'status.id = ev1.verfstatus','left');

		$this->db->where('ev2.verfstatus is null');
		
		if($clientid)
		{
			$this->db->where($clientid);
		}

		$this->db->order_by('glodbver.id', 'ASC');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_employment_ver_status($clientid)
	{
		$this->db->select("candidates_info.overallstatus,status.action as verfstatus,ev1.var_filter_status,ev1.remarks as verification_remarks,ev1.closuredate");

		$this->db->from('empver');

		$this->db->join("candidates_info",'candidates_info.id = empver.candsid');

		$this->db->join("clients",'(clients.id = candidates_info.clientid)');

		$this->db->join("empverres as ev1",'ev1.empverid = empver.id','left');

		$this->db->join("empverres as ev2",'(ev2.empverid = empver.id and ev1.id < ev2.id)','left');

		$this->db->join("status",'status.id = ev1.verfstatus','left');


		$this->db->where('ev2.verfstatus is null');

		if($clientid)
		{
			$this->db->where($clientid);
		}

		$this->db->order_by('empver.id', 'ASC');
				
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_court_ver_status($where_arry = array())
	{
		$this->db->select("candidates_info.overallstatus,status.action as verfstatus,ev1.var_filter_status,ev1.remarks as verification_remarks,ev1.closuredate");

		$this->db->from('courtver');

		$this->db->join("candidates_info",'candidates_info.id = courtver.candsid');

		$this->db->join('clients','clients.id = candidates_info.clientid');

        $this->db->join("courtver_result as ev1",'ev1.courtver_id = courtver.id','left');

		$this->db->join("courtver_result as ev2",'(ev2.courtver_id = courtver.id and ev1.id < ev2.id)','left');

		$this->db->join("status",'status.id = ev1.verfstatus','left');


		$this->db->where('ev2.verfstatus is null');
		if($where_arry)
		{
			$this->db->where($where_arry);
		}

		$this->db->order_by('courtver.id', 'ASC');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_refver_ver_status($where_arry = array())
	{
		$this->db->select("candidates_info.overallstatus,status.action as verfstatus,ref1.var_filter_status,ref1.remarks as verification_remarks,ref1.closuredate");

		$this->db->from('reference');

		$this->db->join("candidates_info",'candidates_info.id = reference.candsid');

		$this->db->join('clients','clients.id = candidates_info.clientid');

        $this->db->join("reference_result as ref1",'ref1.reference_id = reference.id','left');

		$this->db->join("reference_result as ref2",'(ref2.reference_id = reference.id and ref1.id < ref2.id)','left');

	    $this->db->join("status",'status.id = ref1.verfstatus','left');


		$this->db->where('ref2.verfstatus is null');

		if($where_arry)
		{
			$this->db->where($where_arry);
		}

		$this->db->group_by('reference.id');

		$this->db->order_by('reference.id', 'ASC');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_narcver_ver_status($where_arry = array())
	{
		$this->db->select("candidates_info.overallstatus,status.action as verfstatus,ev1.var_filter_status,ev1.remarks as verification_remarks,ev1.closuredate");

		$this->db->from('drug_narcotis');

		$this->db->join("candidates_info",'candidates_info.id = drug_narcotis.candsid');

		$this->db->join('clients','clients.id = candidates_info.clientid');

        $this->db->join("drug_narcotis_result as ev1",'ev1.drug_narcotis_id = drug_narcotis.id','left');

		$this->db->join("drug_narcotis_result as ev2",'(ev2.drug_narcotis_id = drug_narcotis.id and ev1.id < ev2.id)','left');

		$this->db->join("status",'status.id = ev1.verfstatus','left');


		$this->db->where('ev2.verfstatus is null');

		if($where_arry)
		{
			$this->db->where($where_arry);
		}

		$this->db->group_by('drug_narcotis.id');

		$this->db->order_by('drug_narcotis.id', 'ASC');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_social_media_ver_status($where_arry = array())
	{
		$this->db->select("candidates_info.overallstatus,status.action as verfstatus,ev1.var_filter_status,ev1.remarks as verification_remarks,ev1.closuredate");

		$this->db->from('social_media');

		$this->db->join("candidates_info",'candidates_info.id = social_media.candsid');

		$this->db->join('clients','clients.id = candidates_info.clientid');

        $this->db->join("social_media_result as ev1",'ev1.social_media_id = social_media.id','left');

		$this->db->join("social_media_result as ev2",'(ev2.social_media_id = social_media.id and ev1.id < ev2.id)','left');

		$this->db->join("status",'status.id = ev1.verfstatus','left');


		$this->db->where('ev2.verfstatus is null');

		if($where_arry)
		{
			$this->db->where($where_arry);
		}

		$this->db->group_by('social_media.id');

		$this->db->order_by('social_media.id', 'ASC');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_identity_ver_status($where_arry = array())
	{
		$this->db->select("candidates_info.overallstatus,status.action as verfstatus,ev1.var_filter_status,ev1.remarks as verification_remarks,ev1.closuredate");

		$this->db->from('identity');

		$this->db->join("candidates_info",'candidates_info.id = identity.candsid');

		$this->db->join('clients','clients.id = candidates_info.clientid');

        $this->db->join("identity_result as ev1",'ev1.identity_id = identity.id','left');

		$this->db->join("identity_result as ev2",'(ev2.identity_id = identity.id and ev1.id < ev2.id)','left');

		$this->db->join("status",'status.id = ev1.verfstatus','left');


		$this->db->where('ev2.verfstatus is null');

		if($where_arry)
		{
			$this->db->where($where_arry);
		}

		$this->db->group_by('identity.id');

		$this->db->order_by('identity.id', 'ASC');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_credit_reports_ver_status($where_arry = array())
	{
		$this->db->select("candidates_info.overallstatus,status.action as verfstatus,ev1.var_filter_status,ev1.remarks as verification_remarks,ev1.closuredate");

		$this->db->from('credit_report');

		$this->db->join("candidates_info",'candidates_info.id = credit_report.candsid');

		$this->db->join('clients','clients.id = candidates_info.clientid');

        $this->db->join("credit_report_result as ev1",'ev1.credit_report_id = credit_report.id','left');

		$this->db->join("credit_report_result as ev2",'(ev2.credit_report_id = credit_report.id and ev1.id < ev2.id)','left');

		$this->db->join("status",'status.id = ev1.verfstatus','left');

		$this->db->where('ev2.verfstatus is null');

		if($where_arry)
		{
			$this->db->where($where_arry);
		}

		$this->db->group_by('credit_report.id');

		$this->db->order_by('credit_report.id', 'ASC');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_pcc_ver_status($where_arry = array())
	{
		$this->db->select("candidates_info.overallstatus,status.action as verfstatus,ev1.var_filter_status,ev1.remarks,ev1.closuredate as closuredate");

		$this->db->from('pcc');

		$this->db->join("candidates_info",'candidates_info.id = pcc.candsid');

		$this->db->join('clients','clients.id = candidates_info.clientid');

        $this->db->join("pcc_result as ev1",'ev1.pcc_id = pcc.id','left');

		$this->db->join("pcc_result as ev2",'(ev2.pcc_id = pcc.id and ev1.id < ev2.id)','left');

		$this->db->join("status",'status.id = ev1.verfstatus','left');

		$this->db->where('ev2.verfstatus is null');

	
		if($where_arry)
		{
			$this->db->where($where_arry);
		}

		$this->db->order_by('pcc.id', 'ASC');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}
    

    public function get_addres_ver_status_with_insuff($clientid)
	{
		$this->db->select("addrver.address,addrver.city,addrver.pincode,addrver.state,candidates_info.overallstatus,status.action as verfstatus,ev1.var_filter_status,ev1.remarks as verification_remarks,ev1.closuredate, addrver_insuff.insuff_raise_remark,addrver_insuff.insff_reason");

		$this->db->from('candidates_info');

		$this->db->join("addrver",'candidates_info.id = addrver.candsid');

		$this->db->join("addrver_insuff",'addrver_insuff.addrverid = addrver.id and addrver_insuff.status = 1','left');

		$this->db->join("clients",'(clients.id = candidates_info.clientid)');

		$this->db->join("addrverres as ev1",'ev1.addrverid = addrver.id','left');

		$this->db->join("addrverres as ev2",'(ev2.addrverid = addrver.id and ev1.id < ev2.id)','left');

		$this->db->join("status",'status.id = ev1.verfstatus','left');

		if($clientid)
		{
			$this->db->where($clientid);
		}

		$this->db->where('ev2.verfstatus is null');

		$this->db->order_by('addrver.id', 'ASC');
		
		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_education_ver_status_with_insuff($clientid)
	{
		$this->db->select("candidates_info.overallstatus,status.action as verfstatus,ev1.var_filter_status,ev1.remarks as verification_remarks,ev1.closuredate,education_insuff.insuff_raise_remark,(select universityname from university_master where  university_master.id = education.university_board) as university_name,(select qualification from qualification_master where  qualification_master.id = education.qualification) as qualification_name");

		$this->db->from('education');

		$this->db->join("candidates_info",'candidates_info.id = education.candsid');

		$this->db->join("clients",'(clients.id = candidates_info.clientid)');

		$this->db->join("education_insuff",'education_insuff.education_id = education.id and education_insuff.status = 1','left');

		$this->db->join("education_result as ev1",'(ev1.education_id = education.id)','left');

		$this->db->join("education_result as ev2",'(ev2.education_id = education.id and ev1.id < ev2.id)','left');

		$this->db->join("status",'status.id = ev1.verfstatus','left');

		$this->db->where('ev2.verfstatus is null');
		
		if($clientid)
		{
			$this->db->where($clientid);
		}

		$this->db->order_by('education.id', 'ASC');
		
		$result = $this->db->get();
	
		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_globdbver_ver_status_with_insuff($clientid)
	{
		$this->db->select("glodbver.street_address,glodbver.city,glodbver.pincode,glodbver.state,candidates_info.overallstatus,status.action as verfstatus,ev1.var_filter_status,ev1.remarks as verification_remarks,ev1.closuredate, glodbver_insuff.insuff_raise_remark,glodbver_insuff.insff_reason");

		$this->db->from('glodbver');

		$this->db->join("candidates_info",'candidates_info.id = glodbver.candsid');

		$this->db->join("glodbver_insuff",'glodbver_insuff.glodbver_id = glodbver.id and glodbver_insuff.status = 1','left');

		$this->db->join("clients",'(clients.id = candidates_info.clientid)');

		$this->db->join("glodbver_result as ev1",'ev1.glodbver_id = glodbver.id','left');

		$this->db->join("glodbver_result as ev2",'(ev2.glodbver_id = glodbver.id and ev1.id < ev2.id)','left');

		$this->db->join("status",'status.id = ev1.verfstatus','left');

		$this->db->where('ev2.verfstatus is null');
		
		if($clientid)
		{
			$this->db->where($clientid);
		}

		$this->db->order_by('glodbver.id', 'ASC');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_employment_ver_status_with_insuff($clientid)
	{
		$this->db->select("candidates_info.overallstatus,status.action as verfstatus,ev1.var_filter_status,ev1.remarks as verification_remarks,ev1.closuredate,empverres_insuff.insuff_raise_remark,(select coname from company_database where company_database.id = empver.nameofthecompany) as company_name");

		$this->db->from('empver');

		$this->db->join("candidates_info",'candidates_info.id = empver.candsid');

		$this->db->join("empverres_insuff",'empverres_insuff.empverres_id = empver.id and empverres_insuff.status = 1','left');

		$this->db->join("clients",'(clients.id = candidates_info.clientid)');

		$this->db->join("empverres as ev1",'ev1.empverid = empver.id','left');

		$this->db->join("empverres as ev2",'(ev2.empverid = empver.id and ev1.id < ev2.id)','left');

		$this->db->join("status",'status.id = ev1.verfstatus','left');


		$this->db->where('ev2.verfstatus is null');

		if($clientid)
		{
			$this->db->where($clientid);
		}

		$this->db->order_by('empver.id', 'ASC');
				
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_court_ver_status_with_insuff($where_arry = array())
	{
		$this->db->select("courtver.street_address,courtver.city,courtver.pincode,courtver.state,candidates_info.overallstatus,status.action as verfstatus,ev1.var_filter_status,ev1.remarks as verification_remarks,ev1.closuredate,courtver_insuff.insuff_raise_remark");

		$this->db->from('courtver');

		$this->db->join("candidates_info",'candidates_info.id = courtver.candsid');

		$this->db->join("courtver_insuff",'courtver_insuff.courtver_id  = courtver.id and courtver_insuff.status = 1','left');


		$this->db->join('clients','clients.id = candidates_info.clientid');

        $this->db->join("courtver_result as ev1",'ev1.courtver_id = courtver.id','left');

		$this->db->join("courtver_result as ev2",'(ev2.courtver_id = courtver.id and ev1.id < ev2.id)','left');

		$this->db->join("status",'status.id = ev1.verfstatus','left');


		$this->db->where('ev2.verfstatus is null');
		if($where_arry)
		{
			$this->db->where($where_arry);
		}

		$this->db->order_by('courtver.id', 'ASC');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_refver_ver_status_with_insuff($where_arry = array())
	{
		$this->db->select("reference.name_of_reference,candidates_info.overallstatus,status.action as verfstatus,ref1.var_filter_status,ref1.remarks as verification_remarks,ref1.closuredate,reference_insuff.insuff_raise_remark,reference_insuff.insff_reason");

		$this->db->from('reference');

		$this->db->join("candidates_info",'candidates_info.id = reference.candsid');

	    $this->db->join("reference_insuff",'reference_insuff.reference_id  = reference.id and reference_insuff.status = 1','left');

		$this->db->join('clients','clients.id = candidates_info.clientid');

        $this->db->join("reference_result as ref1",'ref1.reference_id = reference.id','left');

		$this->db->join("reference_result as ref2",'(ref2.reference_id = reference.id and ref1.id < ref2.id)','left');

	    $this->db->join("status",'status.id = ref1.verfstatus','left');


		$this->db->where('ref2.verfstatus is null');

		if($where_arry)
		{
			$this->db->where($where_arry);
		}

		$this->db->group_by('reference.id');

		$this->db->order_by('reference.id', 'ASC');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	
	public function get_identity_ver_status_with_insuff($where_arry = array())
	{
		$this->db->select("identity.doc_submited,candidates_info.overallstatus,status.action as verfstatus,ev1.var_filter_status,ev1.remarks as verification_remarks,ev1.closuredate,identity_insuff.insuff_raise_remark,identity_insuff.insff_reason");

		$this->db->from('identity');

		$this->db->join("candidates_info",'candidates_info.id = identity.candsid');

		$this->db->join("identity_insuff",'identity_insuff.identity_id  = identity.id and identity_insuff.status = 1','left');

		$this->db->join('clients','clients.id = candidates_info.clientid');

        $this->db->join("identity_result as ev1",'ev1.identity_id = identity.id','left');

		$this->db->join("identity_result as ev2",'(ev2.identity_id = identity.id and ev1.id < ev2.id)','left');

		$this->db->join("status",'status.id = ev1.verfstatus','left');


		$this->db->where('ev2.verfstatus is null');

		if($where_arry)
		{
			$this->db->where($where_arry);
		}

		$this->db->group_by('identity.id');

		$this->db->order_by('identity.id', 'ASC');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_credit_reports_ver_status_with_insuff($where_arry = array())
	{
		$this->db->select("credit_report.doc_submited,candidates_info.overallstatus,status.action as verfstatus,ev1.var_filter_status,ev1.remarks as verification_remarks,ev1.closuredate,credit_report_insuff.insuff_raise_remark,credit_report_insuff.insff_reason");

		$this->db->from('credit_report');

		$this->db->join("candidates_info",'candidates_info.id = credit_report.candsid');

	    $this->db->join("credit_report_insuff",'credit_report_insuff.credit_report_id  = credit_report.id and credit_report_insuff.status = 1','left');

		$this->db->join('clients','clients.id = candidates_info.clientid');

        $this->db->join("credit_report_result as ev1",'ev1.credit_report_id = credit_report.id','left');

		$this->db->join("credit_report_result as ev2",'(ev2.credit_report_id = credit_report.id and ev1.id < ev2.id)','left');

		$this->db->join("status",'status.id = ev1.verfstatus','left');

		$this->db->where('ev2.verfstatus is null');

		if($where_arry)
		{
			$this->db->where($where_arry);
		}

		$this->db->group_by('credit_report.id');

		$this->db->order_by('credit_report.id', 'ASC');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_pcc_ver_status_with_insuff($where_arry = array())
	{
		$this->db->select("pcc.street_address,pcc.city,pcc.pincode,pcc.state,candidates_info.overallstatus,status.action as verfstatus,ev1.var_filter_status, ev1.remarks,ev1.closuredate as closuredate,pcc_insuff.insff_reason, pcc_insuff.insuff_raise_remark");

		$this->db->from('pcc');

		$this->db->join("candidates_info",'candidates_info.id = pcc.candsid');

		$this->db->join("pcc_insuff",'pcc_insuff.pcc_id  = pcc.id and pcc_insuff.status = 1','left');

		$this->db->join('clients','clients.id = candidates_info.clientid');

        $this->db->join("pcc_result as ev1",'ev1.pcc_id = pcc.id','left');

		$this->db->join("pcc_result as ev2",'(ev2.pcc_id = pcc.id and ev1.id < ev2.id)','left');

		$this->db->join("status",'status.id = ev1.verfstatus','left');

		$this->db->where('ev2.verfstatus is null');

	
		if($where_arry)
		{
			$this->db->where($where_arry);
		}

		$this->db->order_by('pcc.id', 'ASC');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_drugs_ver_status_with_insuff($where_arry = array())
	{
		$this->db->select("drug_narcotis.drug_test_code,candidates_info.overallstatus,status.action as verfstatus,ev1.var_filter_status,ev1.remarks as verification_remarks,ev1.closuredate,drug_narcotis_insuff.insuff_raise_remark,drug_narcotis_insuff.insff_reason");

		$this->db->from('drug_narcotis');

		$this->db->join("candidates_info",'candidates_info.id = drug_narcotis.candsid');

	    $this->db->join("drug_narcotis_insuff",'drug_narcotis_insuff.drug_narcotis_id  = drug_narcotis.id and drug_narcotis_insuff.status = 1','left');

		$this->db->join('clients','clients.id = candidates_info.clientid');

        $this->db->join("drug_narcotis_result as ev1",'ev1.drug_narcotis_id = drug_narcotis.id','left');

		$this->db->join("drug_narcotis_result as ev2",'(ev2.drug_narcotis_id = drug_narcotis.id and ev1.id < ev2.id)','left');

		$this->db->join("status",'status.id = ev1.verfstatus','left');

		$this->db->where('ev2.verfstatus is null');

		if($where_arry)
		{
			$this->db->where($where_arry);
		}

		$this->db->group_by('drug_narcotis.id');

		$this->db->order_by('drug_narcotis.id', 'ASC');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function status_count($clientid)
    {
        $this->db->select('overallstatus,COUNT(overallstatus) as total');

        $this->db->from($this->tableName);

        if($clientid)
        {
            $this->db->where('clientid',$clientid);
        }

        $this->db->group_by('overallstatus');

        $result  = $this->db->get();

        record_db_error($this->db->last_query());
        
        $results = convert_to_single_dimension_array($result->result_array(),'overallstatus','total');

        $return['total'] = array_sum($results);

        if(!empty($results)) {
        	if(array_key_exists('No Record Found',$results)) {
	            $results['Discrepancy'] = $results['Discrepancy']+$results['No Record Found'];
	        }

            foreach ($results as $key => $value) {
                $return[str_replace('/','',str_replace(' ','',$key))] = $value;
            }
            
        }
        return $return;
    }


    public function get_addres_due_tat_status($where_array)
	{
		$this->db->select_max("due_date");

		$this->db->from('addrver');

		$this->db->join("addrverres as ev1",'ev1.addrverid = addrver.id','left');

		$this->db->join("addrverres as ev2",'(ev2.addrverid = addrver.id and ev1.id < ev2.id)','left');

		
		if($where_array)
		{
			$this->db->where($where_array);
		}


		$this->db->order_by('addrver.id', 'desc');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	

	 public function get_employment_due_tat_status($where_array)
	{
		$this->db->select_max("due_date");

		$this->db->from('empver');

		$this->db->join("empverres as ev1",'ev1.empverid = empver.id','left');

		$this->db->join("empverres as ev2",'(ev2.empverid = empver.id and ev1.id < ev2.id)','left');

		
		if($where_array)
		{
			$this->db->where($where_array);
		}


		$this->db->order_by('empver.id', 'desc');
		
		$result = $this->db->get();
		record_db_error($this->db->last_query());

		return $result->result_array();
	}
    

     public function get_education_due_tat_status($where_array)
	{
		$this->db->select_max("due_date");

		$this->db->from('education');

		$this->db->join("education_result as ev1",'ev1.education_id = education.id','left');

		$this->db->join("education_result as ev2",'(ev2.education_id = education.id and ev1.id < ev2.id)','left');


		if($where_array)
		{
			$this->db->where($where_array);
		}


		$this->db->order_by('education.id', 'desc');
		
		$result = $this->db->get();
		record_db_error($this->db->last_query());

		return $result->result_array();
	}


	 public function get_reference_due_tat_status($where_array)
	{
		$this->db->select_max("due_date");

		$this->db->from('reference');

		$this->db->join("reference_result as ev1",'ev1.reference_id = reference.id','left');

		$this->db->join("reference_result as ev2",'(ev2.reference_id = reference.id and ev1.id < ev2.id)','left');

		if($where_array)
		{
			$this->db->where($where_array);
		}


		$this->db->order_by('reference.id', 'desc');
		
		$result = $this->db->get();
		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	 public function get_court_due_tat_status($where_array)
	{
		$this->db->select_max("due_date");

		$this->db->from('courtver');

		$this->db->join("courtver_result as ev1",'ev1.courtver_id = courtver.id','left');

		$this->db->join("courtver_result as ev2",'(ev2.courtver_id = courtver.id and ev1.id < ev2.id)','left');
		
		if($where_array)
		{
			$this->db->where($where_array);
		}


		$this->db->order_by('courtver.id', 'desc');
		
		$result = $this->db->get();
		record_db_error($this->db->last_query());

		return $result->result_array();
	}


	 public function get_global_due_tat_status($where_array)
	{
		$this->db->select_max("due_date");

		$this->db->from('glodbver');

		$this->db->join("glodbver_result as ev1",'ev1.glodbver_id = glodbver.id','left');

		$this->db->join("glodbver_result as ev2",'(ev2.glodbver_id = glodbver.id and ev1.id < ev2.id)','left');

		if($where_array)
		{
			$this->db->where($where_array);
		}


		$this->db->order_by('glodbver.id', 'desc');
		
		$result = $this->db->get();
		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	 public function get_pcc_due_tat_status($where_array)
	{
		$this->db->select_max("due_date");

		$this->db->from('pcc');


		$this->db->join("pcc_result as ev1",'ev1.pcc_id = pcc.id','left');

		$this->db->join("pcc_result as ev2",'(ev2.pcc_id = pcc.id and ev1.id < ev2.id)','left');

		if($where_array)
		{
			$this->db->where($where_array);
		}


		$this->db->order_by('pcc.id', 'desc');
		
		$result = $this->db->get();
		record_db_error($this->db->last_query());

		return $result->result_array();
	}
    
     public function get_identity_due_tat_status($where_array)
	{
		$this->db->select_max("due_date");

		$this->db->from('identity');
         
        $this->db->join("identity_result as ev1",'ev1.identity_id = identity.id','left');

		$this->db->join("identity_result as ev2",'(ev2.identity_id = identity.id and ev1.id < ev2.id)','left'); 
		
		if($where_array)
		{
			$this->db->where($where_array);
		}


		$this->db->order_by('identity.id', 'desc');
		
		$result = $this->db->get();
		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	 public function get_credit_report_due_tat_status($where_array)
	{
		$this->db->select_max("due_date");

		$this->db->from('credit_report');


        $this->db->join("credit_report_result as ev1",'ev1.credit_report_id = credit_report.id','left');

		$this->db->join("credit_report_result as ev2",'(ev2.credit_report_id = credit_report.id and ev1.id < ev2.id)','left');

		if($where_array)
		{
			$this->db->where($where_array);
		}


		$this->db->order_by('credit_report.id', 'desc');
		
		$result = $this->db->get();
		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_drugs_due_tat_status($where_array)
	{
		$this->db->select_max("due_date");

		$this->db->from('drug_narcotis');

		$this->db->join("drug_narcotis_result as ev1",'ev1.drug_narcotis_id = drug_narcotis.id','left');

		$this->db->join("drug_narcotis_result as ev2",'(ev2.drug_narcotis_id = drug_narcotis.id and ev1.id < ev2.id)','left');

		if($where_array)
		{
			$this->db->where($where_array);
		}


		$this->db->order_by('drug_narcotis.id', 'desc');
		
		$result = $this->db->get();
		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_social_media_due_tat_status($where_array)
	{
		$this->db->select_max("due_date");

		$this->db->from('social_media');

		$this->db->join("social_media_result as ev1",'ev1.social_media_id = social_media.id','left');

		$this->db->join("social_media_result as ev2",'(ev2.social_media_id = social_media.id and ev1.id < ev2.id)','left');

		if($where_array)
		{
			$this->db->where($where_array);
		}


		$this->db->order_by('social_media.id', 'desc');
		
		$result = $this->db->get();
		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function update_candidate_due_date($files_arry,$arrwhere )
	{

         if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update("candidates_info", $files_arry);
         
    
			record_db_error($this->db->last_query());
             
			return $result;
	    }
		
	}

    public function get_all_cases_by_date($date)
	{
		$query = $this->db->query('SELECT count(id) as total_count FROM candidates_info where DATE_FORMAT(overallclosuredate ,"%Y-%m-%d") >= "'.$date.'" AND DATE_FORMAT(overallclosuredate ,"%Y-%m-%d") <= "'.$date.'" AND overallstatus in ("clear","Stop/Check", "Discrepancy") ');

		$results = $query->row_array();
		
		return (!empty($results) ? $results['total_count'] : 0);
	}

	  public function get_candidate_list($where_arry)
	{
		$this->db->select("candidates_info_logs.*,user_profile.user_name as created_name");

		$this->db->from('candidates_info_logs');

	    $this->db->join("user_profile",'user_profile.id = candidates_info_logs.created_by');
		
		$this->db->where($where_arry);

		$this->db->order_by('id','desc');
		
		$result = $this->db->get();
		
		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_entity_package_component($where_arry)
	{
      
       $this->db->select("clients_details.*,(select entity_package_name from entity_package where entity_package.id = clients_details.entity limit 1) as entity_name, (select entity_package_name from entity_package  where entity_package.id= clients_details.package limit 1) as package_name,");

		$this->db->from('clients_details');

		
		$this->db->where($where_arry);
		
		$result = $this->db->get();
		
		record_db_error($this->db->last_query());
		
		return $result->result_array();


	}

	public function get_all_export($where_arry,$count)
	{
		$this->db->select("candidates_info.*,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name, (select entity_package_name from entity_package  where entity_package.id= candidates_info.package limit 1) as package_name,(select user_name from user_profile  where user_profile.id= candidates_info.created_by limit 1) as username, status.status_value,clients.clientname");

		$this->db->from('candidates_info');

		$this->db->join("clients",'clients.id = candidates_info.clientid');
        
        $this->db->join("status",'status.id = candidates_info.overallstatus');


		$this->db->where($where_arry);

		$this->db->order_by('id', 'desc');

		$this->db->limit($count);
		
		$result = $this->db->get();


		
		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	// public function get_client_tat_days($where_arry)
	// {
	// 	$this->db->select("datediff(updated,created) as diff_days,candidates_info.created,cadns.updated,clients.*");

	// 	$this->db->from('candidates_info');

	// 	$this->db->join("clients",'clients.id = candidates_info.clientid');

	// 	if($where_arry)
	// 	{
	// 		$this->db->where($where_arry);
	// 	}
	// 	$this->db->order_by('id', 'desc');
		
	// 	$result = $this->db->get();

	// 	record_db_error($this->db->last_query());

	// 	return $result->result_array();
	// }

	public function get_entitypackages($where_array)
	{
		$this->db->select("id,tbl_clients_id,clientaddress,clientcity,clientpincode,component_id,component_name,final_qc,auto_report,report_type,(select entity_package_name from entity_package where id = clients_details.entity) as entity,(select entity_package_name from entity_package where id = clients_details.package) as package,created_on,created_by");

		$this->db->from('clients_details');

		$this->db->where($where_array);

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();
	}

     public function select_closed_qc_status($where_array)
	{
		$this->db->select('candidates_info.id,clients_details.final_qc,clients_details.first_qc_component_name,clients_details.component_id');

        $this->db->from("candidates_info");

        $this->db->join("clients",'clients.id = candidates_info.clientid');

        $this->db->join("clients_details",'clients_details.tbl_clients_id = candidates_info.clientid and `clients_details`.`entity` = `candidates_info`.`entity` AND `clients_details`.`package` = `candidates_info`.`package`');

		$this->db->where($where_array);

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function select_address_join($where_array)
	{
		$this->db->select("addrver.created_on,addrverres_result.id as addrverres_result_id,status.action,addrver.address,status.status_value,addrverres.var_filter_status,addrverres.first_qc_approve");

		$this->db->from('addrver');

		$this->db->join("addrverres",'addrverres.addrverid = addrver.id');

		$this->db->join("addrverres_result",'addrverres_result.addrverid = addrver.id');

		$this->db->join("status",'status.id = addrverres.verfstatus');

		

		$this->db->where($where_array);

		$this->db->order_by('addrver.id', 'asc');

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();
	}
    
    public function select_address_join_follow_up($where_array)
	{
		$this->db->select("addrver.created_on, status.action,addrver.address,status.status_value,addrverres.var_filter_status,addrverres.first_qc_approve");

		$this->db->from('addrver');

		$this->db->join("addrverres",'addrverres.addrverid = addrver.id');

		$this->db->join("status",'status.id = addrverres.verfstatus');

		

		$this->db->where($where_array);

		$this->db->order_by('addrver.id', 'asc');

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();
	}

   public function select_address_wip_join($where_array)
	{
		$this->db->select("addrver.id,addrver.created_on,addrver.add_com_ref,status.action,status.status_value,addrverres.var_filter_status,addrverres.first_qc_approve");

		$this->db->from('addrver');

		$this->db->join("addrverres",'addrverres.addrverid = addrver.id');

		$this->db->join("status",'status.id = addrverres.verfstatus ');

		$this->db->where($where_array);

		$this->db->where('(addrverres.var_filter_status = "wip" or addrverres.var_filter_status = "WIP" or addrverres.var_filter_status = "Insufficiency" or addrverres.var_filter_status = "insufficiency")');

		$this->db->order_by('addrver.id', 'asc');

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function select_court_wip_join($where_array)
	{
		$this->db->select("courtver.id,courtver.street_address,courtver.court_com_ref,status.status_value,courtver.created_on,courtver_result.var_filter_status,courtver_result.first_qc_approve");

		$this->db->from('courtver');

		$this->db->join("courtver_result",'courtver_result.courtver_id = courtver.id');

		$this->db->join("status",'status.id = courtver_result.verfstatus');

		$this->db->where('(courtver_result.var_filter_status = "wip" or courtver_result.var_filter_status = "WIP" or courtver_result.var_filter_status = "Insufficiency" or courtver_result.var_filter_status = "insufficiency")');


		$this->db->where($where_array);

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function select_employment_wip_join($where_array)
	{
		$this->db->select("empver.id,empver.created_on,empver.emp_com_ref,status.status_value,(select  coname from company_database where  company_database.id = empver.nameofthecompany) as name_of_company, empverres.var_filter_status, empverres.first_qc_approve");

		$this->db->from('empver');

		$this->db->join("empverres",'empverres.empverid = empver.id');

		$this->db->join("status",'status.id = empverres.verfstatus');

		$this->db->where('(empverres.var_filter_status = "wip" or empverres.var_filter_status = "WIP" or empverres.var_filter_status = "Insufficiency" or empverres.var_filter_status = "insufficiency")');

		$this->db->order_by('empver.id', 'asc');


		$this->db->where($where_array);

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function select_education_wip_join($where_array)
	{
		$this->db->select("education.*,status.status_value,education_result.var_filter_status,education_result.first_qc_approve");

		$this->db->from('education');

		$this->db->join("education_result",'education_result.education_id = education.id');

	    $this->db->join("status",'status.id = education_result.verfstatus');

	    $this->db->where('(education_result.var_filter_status = "wip" or education_result.var_filter_status = "WIP" or education_result.var_filter_status = "Insufficiency" or education_result.var_filter_status = "insufficiency")');

		$this->db->where($where_array);

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function select_reference_wip_join($where_array)
	{
		$this->db->select("reference.id,reference.name_of_reference,reference.reference_com_ref,status.status_value,reference.created_on,reference_result.var_filter_status,reference_result.first_qc_approve");

		$this->db->from('reference');

		$this->db->join("reference_result",'reference_result.reference_id = reference.id');

		$this->db->join("status",'status.id = reference_result.verfstatus');

		$this->db->where('(reference_result.var_filter_status = "wip" or reference_result.var_filter_status = "WIP" or reference_result.var_filter_status = "Insufficiency" or reference_result.var_filter_status = "insufficiency")');

		$this->db->where($where_array);

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function select_global_database_wip_join($where_array)
	{
		$this->db->select("glodbver.id,glodbver.street_address,glodbver.global_com_ref,status.status_value,glodbver.created_on,glodbver_result.var_filter_status,glodbver_result.first_qc_approve");

		$this->db->from('glodbver');

		$this->db->join("glodbver_result",'glodbver_result.glodbver_id = glodbver.id');

		$this->db->join("status",'status.id = glodbver_result.verfstatus');

		$this->db->where('(glodbver_result.var_filter_status = "wip" or glodbver_result.var_filter_status = "WIP" or glodbver_result.var_filter_status = "Insufficiency" or glodbver_result.var_filter_status = "insufficiency")');


		$this->db->where($where_array);

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function select_drugs_wip_join($where_array)
	{
		$this->db->select("drug_narcotis.id,drug_narcotis.drug_test_code,drug_narcotis.drug_com_ref,status.status_value,drug_narcotis.created_on,drug_narcotis_result.var_filter_status,drug_narcotis_result.first_qc_approve");

		$this->db->from('drug_narcotis');

		$this->db->join("drug_narcotis_result",'drug_narcotis_result.drug_narcotis_id = drug_narcotis.id');

		$this->db->join("status",'status.id = drug_narcotis_result.verfstatus');

		$this->db->where('(drug_narcotis_result.var_filter_status = "wip" or drug_narcotis_result.var_filter_status = "WIP" or drug_narcotis_result.var_filter_status = "Insufficiency" or drug_narcotis_result.var_filter_status = "insufficiency")');


		$this->db->where($where_array);

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function select_pcc_wip_join($where_array)
	{
		$this->db->select("pcc.id,pcc.street_address,status.status_value,pcc.pcc_com_ref,pcc.created_on,pcc_result.var_filter_status,pcc_result.first_qc_approve");

		$this->db->from('pcc');

		$this->db->join("pcc_result",'pcc_result.pcc_id = pcc.id');

		$this->db->join("status",'status.id = pcc_result.verfstatus');


		$this->db->where('(pcc_result.var_filter_status = "wip" or pcc_result.var_filter_status = "WIP" or pcc_result.var_filter_status = "Insufficiency" or pcc_result.var_filter_status = "insufficiency")');


		$this->db->where($where_array);

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function select_identity_wip_join($where_array)
	{
		$this->db->select("identity.id,identity.doc_submited,identity.identity_com_ref,identity.created_on,status.status_value,identity_result.var_filter_status,identity_result.first_qc_approve");

		$this->db->from('identity');

		$this->db->join("identity_result",'identity_result.identity_id = identity.id');

		$this->db->join("status",'status.id = identity_result.verfstatus');

		$this->db->where('(identity_result.var_filter_status = "wip" or identity_result.var_filter_status = "WIP" or identity_result.var_filter_status = "Insufficiency" or identity_result.var_filter_status = "insufficiency")');



		$this->db->where($where_array);

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function select_credit_report_wip_join($where_array)
	{
		$this->db->select("credit_report.id,credit_report.doc_submited,credit_report.credit_report_com_ref,status.status_value,credit_report.created_on,credit_report_result.var_filter_status,credit_report_result.first_qc_approve");

		$this->db->from('credit_report');

		$this->db->join("credit_report_result",'credit_report_result.credit_report_id = credit_report.id');

		$this->db->join("status",'status.id = credit_report_result.verfstatus');

	    $this->db->where('(credit_report_result.var_filter_status = "wip" or credit_report_result.var_filter_status = "WIP" or credit_report_result.var_filter_status = "Insufficiency" or credit_report_result.var_filter_status = "insufficiency")');



		$this->db->where($where_array);

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();
	}


	public function select_employment_join($where_array)
	{
		$this->db->select("empver.created_on,empverres_logs.sr_id as empver_log_id,status.status_value,(select  coname from company_database where  company_database.id = empver.nameofthecompany) as name_of_company, empverres.var_filter_status, empverres.first_qc_approve");

		$this->db->from('empver');

		$this->db->join("empverres",'empverres.empverid = empver.id');

		$this->db->join("empverres_logs",'empverres_logs.empverid = empver.id');

		$this->db->join("status",'status.id = empverres.verfstatus');

		$this->db->order_by('empver.id', 'asc');


		$this->db->where($where_array);

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function select_employment_join_follow_up($where_array)
	{
		$this->db->select("empver.created_on,status.status_value,(select  coname from company_database where  company_database.id = empver.nameofthecompany) as name_of_company, empverres.var_filter_status, empverres.first_qc_approve");

		$this->db->from('empver');

		$this->db->join("empverres",'empverres.empverid = empver.id');

		$this->db->join("status",'status.id = empverres.verfstatus');

		$this->db->order_by('empver.id', 'asc');


		$this->db->where($where_array);

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function select_education_join($where_array)
	{
		$this->db->select("education.*,education_ver_result.id as education_ver_result_id,status.status_value,education_result.var_filter_status,education_result.first_qc_approve");

		$this->db->from('education');

		$this->db->join("education_result",'education_result.education_id = education.id');

		$this->db->join("education_ver_result",'education_ver_result.education_id = education.id');

	    $this->db->join("status",'status.id = education_result.verfstatus');

		$this->db->where($where_array);

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function select_education_join_follow_up($where_array)
	{
		$this->db->select("education.*,status.status_value,education_result.var_filter_status,education_result.first_qc_approve");

		$this->db->from('education');

		$this->db->join("education_result",'education_result.education_id = education.id');


	    $this->db->join("status",'status.id = education_result.verfstatus');

		$this->db->where($where_array);

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function select_reference_join($where_array)
	{
		$this->db->select("reference.name_of_reference,reference_ver_result.id as reference_ver_result_id,status.status_value,reference.created_on,reference_result.var_filter_status,reference_result.first_qc_approve");

		$this->db->from('reference');

		$this->db->join("reference_result",'reference_result.reference_id = reference.id');

		$this->db->join("reference_ver_result",'reference_ver_result.reference_id = reference.id');

		$this->db->join("status",'status.id = reference_result.verfstatus');

		$this->db->where($where_array);

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();
	}
	public function select_reference_join_follow_up($where_array)
	{
		$this->db->select("reference.name_of_reference,status.status_value,reference.created_on,reference_result.var_filter_status,reference_result.first_qc_approve");

		$this->db->from('reference');

		$this->db->join("reference_result",'reference_result.reference_id = reference.id');

		$this->db->join("status",'status.id = reference_result.verfstatus');

		$this->db->where($where_array);

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();
	}
	public function select_court_join($where_array)
	{
		$this->db->select("courtver.street_address,courtver_ver_result.id as courtver_ver_result_id,status.status_value,courtver.created_on,courtver_result.var_filter_status,courtver_result.first_qc_approve");

		$this->db->from('courtver');

		$this->db->join("courtver_result",'courtver_result.courtver_id = courtver.id');

		$this->db->join("courtver_ver_result",'courtver_ver_result.courtver_id = courtver.id');

		$this->db->join("status",'status.id = courtver_result.verfstatus');

		$this->db->where($where_array);

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();
	}
   
    public function select_court_join_follow_up($where_array)
	{
		$this->db->select("courtver.street_address,tatus.status_value,courtver.created_on,courtver_result.var_filter_status,courtver_result.first_qc_approve");

		$this->db->from('courtver');

		$this->db->join("courtver_result",'courtver_result.courtver_id = courtver.id');

		$this->db->join("status",'status.id = courtver_result.verfstatus');

		$this->db->where($where_array);

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function select_global_database_join($where_array)
	{
		$this->db->select("glodbver.street_address,glodbver_ver_result.id as glodbver_ver_result_id,status.status_value,glodbver.created_on,glodbver_result.var_filter_status,glodbver_result.first_qc_approve");

		$this->db->from('glodbver');

		$this->db->join("glodbver_result",'glodbver_result.glodbver_id = glodbver.id');

	    $this->db->join("glodbver_ver_result",'glodbver_ver_result.glodbver_id = glodbver.id');

		$this->db->join("status",'status.id = glodbver_result.verfstatus');

		$this->db->where($where_array);

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();
	}
    
    public function select_global_database_join_follow_up($where_array)
	{
		$this->db->select("glodbver.street_address,status.status_value,glodbver.created_on,glodbver_result.var_filter_status,glodbver_result.first_qc_approve");

		$this->db->from('glodbver');

		$this->db->join("glodbver_result",'glodbver_result.glodbver_id = glodbver.id');


		$this->db->join("status",'status.id = glodbver_result.verfstatus');

		$this->db->where($where_array);

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function select_drugs_join($where_array)
	{
		$this->db->select("drug_narcotis.drug_test_code,drug_narcotis_ver_result.id as drug_narcotis_ver_result_id,status.status_value,drug_narcotis.created_on,drug_narcotis_result.var_filter_status,drug_narcotis_result.first_qc_approve");

		$this->db->from('drug_narcotis');

		$this->db->join("drug_narcotis_result",'drug_narcotis_result.drug_narcotis_id = drug_narcotis.id');

		$this->db->join("drug_narcotis_ver_result",'drug_narcotis_ver_result.drug_narcotis_id = drug_narcotis.id');

		$this->db->join("status",'status.id = drug_narcotis_result.verfstatus');


		$this->db->where($where_array);

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function select_drugs_join_follow_up($where_array)
	{
		$this->db->select("drug_narcotis.drug_test_code,status.status_value,drug_narcotis.created_on,drug_narcotis_result.var_filter_status,drug_narcotis_result.first_qc_approve");

		$this->db->from('drug_narcotis');

		$this->db->join("drug_narcotis_result",'drug_narcotis_result.drug_narcotis_id = drug_narcotis.id');


		$this->db->join("status",'status.id = drug_narcotis_result.verfstatus');


		$this->db->where($where_array);

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();
	}


	public function select_pcc_join($where_array)
	{
		$this->db->select("pcc.street_address,status.status_value,pcc_ver_result.id as pcc_ver_result_id,pcc.created_on,pcc_result.var_filter_status,pcc_result.first_qc_approve");

		$this->db->from('pcc');

		$this->db->join("pcc_result",'pcc_result.pcc_id = pcc.id');

		$this->db->join("pcc_ver_result",'pcc_ver_result.pcc_id = pcc.id');


		$this->db->join("status",'status.id = pcc_result.verfstatus');


		$this->db->where($where_array);

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();
	}


	public function select_pcc_join_follow_up($where_array)
	{
		$this->db->select("pcc.street_address,status.status_value,pcc.created_on,pcc_result.var_filter_status,pcc_result.first_qc_approve");

		$this->db->from('pcc');

		$this->db->join("pcc_result",'pcc_result.pcc_id = pcc.id');

		$this->db->join("status",'status.id = pcc_result.verfstatus');


		$this->db->where($where_array);

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();
	}


	public function select_identity_join($where_array)
	{
		$this->db->select("identity.doc_submited,identity_ver_result.id as identity_ver_result_id,identity.created_on,status.status_value,identity_result.var_filter_status,identity_result.first_qc_approve");

		$this->db->from('identity');

		$this->db->join("identity_result",'identity_result.identity_id = identity.id');

		$this->db->join("status",'status.id = identity_result.verfstatus');

		$this->db->join("identity_ver_result",'identity_ver_result.identity_id = identity.id');


		$this->db->where($where_array);

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function select_identity_join_follow_up($where_array)
	{
		$this->db->select("identity.doc_submited,identity.created_on,status.status_value,identity_result.var_filter_status,identity_result.first_qc_approve");

		$this->db->from('identity');

		$this->db->join("identity_result",'identity_result.identity_id = identity.id');

		$this->db->join("status",'status.id = identity_result.verfstatus');



		$this->db->where($where_array);

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();
	}


	public function select_credit_report_join($where_array)
	{
		$this->db->select("credit_report.doc_submited,credit_report_ver_result.id as credit_report_ver_result_id,status.status_value,credit_report.created_on,credit_report_result.var_filter_status,credit_report_result.first_qc_approve");

		$this->db->from('credit_report');

		$this->db->join("credit_report_result",'credit_report_result.credit_report_id = credit_report.id');

		$this->db->join("credit_report_ver_result",'credit_report_ver_result.credit_report_id = credit_report.id');

		$this->db->join("status",'status.id = credit_report_result.verfstatus');

		$this->db->where($where_array);

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function select_credit_report_join_follow_up($where_array)
	{
		$this->db->select("credit_report.doc_submited,status.status_value,credit_report.created_on,credit_report_result.var_filter_status,credit_report_result.first_qc_approve");

		$this->db->from('credit_report');

		$this->db->join("credit_report_result",'credit_report_result.credit_report_id = credit_report.id');

		$this->db->join("status",'status.id = credit_report_result.verfstatus');

		$this->db->where($where_array);

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();
	}
    
    public function dashboard_sql($where) { 
		
		$sql = "SELECT (select clientname from clients where clients.id = candidates_info.clientid limit 1) as
			client_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,DATE_FORMAT(caserecddate,'%d-%m-%Y') as caserecddate, ClientRefNumber,cmp_ref_no,CandidateName, (select status_value from status where status.id = candidates_info.overallstatus limit 1) as verfstatus,DATE_FORMAT(overallclosuredate,'%d-%m-%Y') as overallclosuredate,DATE_FORMAT(due_date_candidate,'%d-%m-%Y') as due_date,tat_status_candidate as tat_status FROM candidates_info  $where";
			
		$query = $this->db->query($sql);

		return $query->result_array();
	}

	public function check_primary_no($mobileno,$where_array)
	{
		$where_condition = "(CandidatesContactNumber = ".$mobileno." or ContactNo1 = ".$mobileno." or ContactNo2 = ".$mobileno.")";

		$this->db->select("cmp_ref_no");

		$this->db->from('candidates_info');

		$this->db->where($where_array);

		$this->db->where($where_condition);

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();

	}

	public function get_address_insuff_details($where_array)
	{

		$this->db->select("addrver_insuff.*");

		$this->db->from('addrver');

	    $this->db->join("addrver_insuff",'addrver_insuff.addrverid = addrver.id','left');

		$this->db->where($where_array);

		$this->db->order_by('addrver_insuff.id', 'ASC');

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();

	}

	public function get_employment_insuff_details($where_array)
	{

		$this->db->select("empverres_insuff.*");

		$this->db->from('empver');

	    $this->db->join("empverres_insuff",'empverres_insuff.empverres_id = empver.id','left');

		$this->db->where($where_array);

		$this->db->order_by('empverres_insuff.id', 'ASC');

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();

	}

	public function get_education_insuff_details($where_array)
	{

		$this->db->select("education_insuff.*");

		$this->db->from('education');

	    $this->db->join("education_insuff",'education_insuff.education_id = education.id','left');

		$this->db->where($where_array);

		$this->db->order_by('education_insuff.id', 'ASC');

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();

	}

	public function get_reference_insuff_details($where_array)
	{

		$this->db->select("reference_insuff.*");

		$this->db->from('reference');

	    $this->db->join("reference_insuff",'reference_insuff.reference_id = reference.id','left');

		$this->db->where($where_array);

		$this->db->order_by('reference_insuff.id', 'ASC');

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();

	}

	

    public function get_court_insuff_details($where_array)
	{

		$this->db->select("courtver_insuff.*");

		$this->db->from('courtver');

	    $this->db->join("courtver_insuff",'courtver_insuff.courtver_id = courtver.id','left');

		$this->db->where($where_array);

		$this->db->order_by('courtver_insuff.id', 'ASC');

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();

	}

	public function get_pcc_insuff_details($where_array)
	{

		$this->db->select("pcc_insuff.*");

		$this->db->from('pcc');

	    $this->db->join("pcc_insuff",'pcc_insuff.pcc_id = pcc.id','left');

		$this->db->where($where_array);

		$this->db->order_by('pcc_insuff.id', 'ASC');

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();

	}

	public function get_global_insuff_details($where_array)
	{

		$this->db->select("glodbver_insuff.*");

		$this->db->from('glodbver');

	    $this->db->join("glodbver_insuff",'glodbver_insuff.glodbver_id = glodbver.id','left');

		$this->db->where($where_array);

		$this->db->order_by('glodbver_insuff.id', 'ASC');

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();

	}

	public function get_identity_insuff_details($where_array)
	{

		$this->db->select("identity_insuff.*");

		$this->db->from('identity');

	    $this->db->join("identity_insuff",'identity_insuff.identity_id = identity.id','left');

		$this->db->where($where_array);

		$this->db->order_by('identity_insuff.id', 'ASC');

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();

	}

	public function get_credit_report_insuff_details($where_array)
	{

		$this->db->select("credit_report_insuff.*");

		$this->db->from('credit_report');

	    $this->db->join("credit_report_insuff",'credit_report_insuff.credit_report_id = credit_report.id','left');

		$this->db->where($where_array);

		$this->db->order_by('credit_report_insuff.id', 'ASC');

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();

	}

	public function get_drugs_report_insuff_details($where_array)
	{

		$this->db->select("drug_narcotis_insuff.*");

		$this->db->from('drug_narcotis');

	    $this->db->join("drug_narcotis_insuff",'drug_narcotis_insuff.drug_narcotis_id = drug_narcotis.id','left');

		$this->db->where($where_array);

		$this->db->order_by('drug_narcotis_insuff.id', 'ASC');

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();

	}
	
    public function select_client_details($where_array)
	{
		$this->db->select('id');

		$this->db->from('clients_details');

		$this->db->where($where_array);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function select_spoc_mail_id($where_array)
	{

		$this->db->select("client_spoc_details.*");

		$this->db->from('client_spoc_details');

		$this->db->where_in('clients_details_id',$where_array);

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();

	}
	public function select_pre_post_client($tableName, $return_as_strict_row,$select_array)
	{
		$this->db->select($select_array);

		$this->db->from($tableName);

        $this->db->where_in('id',array('3','4','5','33')); 
        
        $result_array = $this->db->get()->result_array();	
      
        if($return_as_strict_row)
		{
            if(count($result_array) >0 ) // ensure only one record has been previously inserted
            {
                $result_array = $result_array[0];
            }
        }
        return $result_array;
	}

	public function get_address_attachment_details($where_arry = array())
	{
		
		$this->db->select("addrver.add_com_ref,addrver.clientid,(SELECT GROUP_CONCAT(concat(file_name) ORDER BY serialno,id ASC SEPARATOR '||') FROM addrver_files where addrver_files.addrver_id = addrver.id and addrver_files.type= 1 and addrver_files.status = 1) as add_attachments,(SELECT  GROUP_CONCAT(concat(view_vendor_master_log_file.file_name) SEPARATOR '||' ) FROM `view_vendor_master_log_file`, `view_vendor_master_log`, `address_vendor_log` where view_vendor_master_log_file.view_venor_master_log_id = view_vendor_master_log.id and view_vendor_master_log_file.component_tbl_id = 1 and view_vendor_master_log_file.status = 1  and view_vendor_master_log.case_id = address_vendor_log.id and  address_vendor_log.case_id  = addrver.id)  as vendor_attachments");

		$this->db->from('addrver');

		if(!empty($where_arry)){
			$this->db->where($where_arry);
		}

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}
    
    public function get_employment_attachment_details($where_arry = array())
	{
		
		$this->db->select("empver.emp_com_ref,empver.clientid,(SELECT GROUP_CONCAT(concat(file_name) ORDER BY serialno,id ASC SEPARATOR '||') FROM empverres_files where empverres_files.empver_id = empver.id and empverres_files.type= 1 and empverres_files.status = 1) as add_attachments,(SELECT  GROUP_CONCAT(concat(view_vendor_master_log_file.file_name) SEPARATOR '||' ) FROM `view_vendor_master_log_file`, `view_vendor_master_log`, `employment_vendor_log` where view_vendor_master_log_file.view_venor_master_log_id = view_vendor_master_log.id and view_vendor_master_log_file.component_tbl_id = 2 and view_vendor_master_log_file.status = 1  and view_vendor_master_log.case_id = employment_vendor_log.id and  employment_vendor_log.case_id  = empver.id)  as vendor_attachments");

		$this->db->from('empver');

		if(!empty($where_arry)){
			$this->db->where($where_arry);
		}

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_education_attachment_details($where_arry = array())
	{
		
		$this->db->select("education.education_com_ref,education.clientid,(SELECT GROUP_CONCAT(concat(file_name) ORDER BY serialno,id ASC SEPARATOR '||') FROM education_files where education_files.education_id = education.id and education_files.type= 1 and education_files.status = 1) as add_attachments,(SELECT  GROUP_CONCAT(concat(view_vendor_master_log_file.file_name) SEPARATOR '||' ) FROM `view_vendor_master_log_file`, `view_vendor_master_log`, `education_vendor_log` where view_vendor_master_log_file.view_venor_master_log_id = view_vendor_master_log.id and view_vendor_master_log_file.component_tbl_id = 3 and view_vendor_master_log_file.status = 1  and view_vendor_master_log.case_id = education_vendor_log.id and  education_vendor_log.case_id  = education.id)  as vendor_attachments");

		$this->db->from('education');

		if(!empty($where_arry)){
			$this->db->where($where_arry);
		}

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}
    
    
	public function get_reference_attachment_details($where_arry = array())
	{
		
		$this->db->select("reference.reference_com_ref,reference.clientid,(SELECT GROUP_CONCAT(concat(file_name) ORDER BY serialno,id ASC SEPARATOR '||') FROM reference_files where reference_files.reference_id  = reference.id and reference_files.type= 1 and reference_files.status = 1) as add_attachments");

		$this->db->from('reference');

		if(!empty($where_arry)){
			$this->db->where($where_arry);
		}

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}
    
    public function get_court_attachment_details($where_arry = array())
	{
		
		$this->db->select("courtver.court_com_ref,courtver.clientid,(SELECT GROUP_CONCAT(concat(file_name) ORDER BY serialno,id ASC SEPARATOR '||') FROM courtver_files where courtver_files.courtver_id = courtver.id and courtver_files.type= 1 and courtver_files.status = 1) as add_attachments,(SELECT  GROUP_CONCAT(concat(view_vendor_master_log_file.file_name) SEPARATOR '||' ) FROM `view_vendor_master_log_file`, `view_vendor_master_log`, `courtver_vendor_log` where view_vendor_master_log_file.view_venor_master_log_id = view_vendor_master_log.id and view_vendor_master_log_file.component_tbl_id = 5 and view_vendor_master_log_file.status = 1  and view_vendor_master_log.case_id = courtver_vendor_log.id and  courtver_vendor_log.case_id  = courtver.id)  as vendor_attachments,(select ClientRefNumber from candidates_info where candidates_info.id = courtver.candsid) as ClientRefNumber");

		$this->db->from('courtver');

		if(!empty($where_arry)){
			$this->db->where($where_arry);
		}

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_pcc_attachment_details($where_arry = array())
	{
		
		$this->db->select("pcc.pcc_com_ref,pcc.clientid,(SELECT GROUP_CONCAT(concat(file_name) ORDER BY serialno,id ASC SEPARATOR '||') FROM pcc_files where pcc_files.pcc_id = pcc.id and pcc_files.type= 1 and pcc_files.status = 1) as add_attachments,(SELECT  GROUP_CONCAT(concat(view_vendor_master_log_file.file_name) SEPARATOR '||' ) FROM `view_vendor_master_log_file`, `view_vendor_master_log`, `pcc_vendor_log` where view_vendor_master_log_file.view_venor_master_log_id = view_vendor_master_log.id and view_vendor_master_log_file.component_tbl_id = 8 and view_vendor_master_log_file.status = 1  and view_vendor_master_log.case_id = pcc_vendor_log.id and  pcc_vendor_log.case_id  = pcc.id)  as vendor_attachments");

		$this->db->from('pcc');

		if(!empty($where_arry)){
			$this->db->where($where_arry);
		}

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_global_attachment_details($where_arry = array())
	{
		
		$this->db->select("glodbver.global_com_ref,glodbver.clientid,(SELECT GROUP_CONCAT(concat(file_name) ORDER BY serialno,id ASC SEPARATOR '||') FROM glodbver_files where glodbver_files.glodbver_id = glodbver.id and glodbver_files.type= 1 and glodbver_files.status = 1) as add_attachments,(SELECT  GROUP_CONCAT(concat(view_vendor_master_log_file.file_name) SEPARATOR '||' ) FROM `view_vendor_master_log_file`, `view_vendor_master_log`, `glodbver_vendor_log` where view_vendor_master_log_file.view_venor_master_log_id = view_vendor_master_log.id and view_vendor_master_log_file.component_tbl_id = 6 and view_vendor_master_log_file.status = 1  and view_vendor_master_log.case_id = glodbver_vendor_log.id and  glodbver_vendor_log.case_id  = glodbver.id)  as vendor_attachments");

		$this->db->from('glodbver');

		if(!empty($where_arry)){
			$this->db->where($where_arry);
		}

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

    public function get_identity_attachment_details($where_arry = array())
	{
		
		$this->db->select("identity.identity_com_ref,identity.clientid,(SELECT GROUP_CONCAT(concat(file_name) ORDER BY serialno,id ASC SEPARATOR '||') FROM identity_files where identity_files.identity_id = identity.id and identity_files.type= 1 and identity_files.status = 1) as add_attachments,(SELECT  GROUP_CONCAT(concat(view_vendor_master_log_file.file_name) SEPARATOR '||' ) FROM `view_vendor_master_log_file`, `view_vendor_master_log`, `identity_vendor_log` where view_vendor_master_log_file.view_venor_master_log_id = view_vendor_master_log.id and view_vendor_master_log_file.component_tbl_id = 9 and view_vendor_master_log_file.status = 1  and view_vendor_master_log.case_id = identity_vendor_log.id and  identity_vendor_log.case_id  = identity.id)  as vendor_attachments");

		$this->db->from('identity');

		if(!empty($where_arry)){
			$this->db->where($where_arry);
		}

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_credit_report_attachment_details($where_arry = array())
	{
		
		$this->db->select("credit_report.credit_report_com_ref,credit_report.clientid,(SELECT GROUP_CONCAT(concat(file_name) ORDER BY serialno,id ASC SEPARATOR '||') FROM credit_report_files where credit_report_files.credit_report_id = credit_report.id and credit_report_files.type= 1 and credit_report_files.status = 1) as add_attachments,(SELECT  GROUP_CONCAT(concat(view_vendor_master_log_file.file_name) SEPARATOR '||' ) FROM `view_vendor_master_log_file`, `view_vendor_master_log`, `credit_report_vendor_log` where view_vendor_master_log_file.view_venor_master_log_id = view_vendor_master_log.id and view_vendor_master_log_file.component_tbl_id = 10 and view_vendor_master_log_file.status = 1  and view_vendor_master_log.case_id = credit_report_vendor_log.id and  credit_report_vendor_log.case_id  = credit_report.id)  as vendor_attachments");

		$this->db->from('credit_report');

		if(!empty($where_arry)){
			$this->db->where($where_arry);
		}

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}
	

	public function get_drugs_attachment_details($where_arry = array())
	{
		
		$this->db->select("drug_narcotis.drug_com_ref,drug_narcotis.clientid,(SELECT GROUP_CONCAT(concat(file_name) ORDER BY serialno,id ASC SEPARATOR '||') FROM drug_narcotis_files where drug_narcotis_files.drug_narcotis_id = drug_narcotis.id and drug_narcotis_files.type= 1 and drug_narcotis_files.status = 1) as add_attachments,(SELECT  GROUP_CONCAT(concat(view_vendor_master_log_file.file_name) SEPARATOR '||' ) FROM `view_vendor_master_log_file`, `view_vendor_master_log`, `drug_narcotis_vendor_log` where view_vendor_master_log_file.view_venor_master_log_id = view_vendor_master_log.id and view_vendor_master_log_file.component_tbl_id = 7 and view_vendor_master_log_file.status = 1  and view_vendor_master_log.case_id = drug_narcotis_vendor_log.id and  drug_narcotis_vendor_log.case_id  = drug_narcotis.id)  as vendor_attachments");

		$this->db->from('drug_narcotis');

		if(!empty($where_arry)){
			$this->db->where($where_arry);
		}

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function select_candidate_for_export( $where_array = array())
	{
		$this->db->select('candidates_info.CandidateName,candidates_info.ClientRefNumber,courtver.court_com_ref,courtver.iniated_date,view_vendor_master_log.final_status,view_vendor_master_log.created_on,(select closuredate from courtver_result where courtver_result.courtver_id = courtver.id) as closuredate');

		$this->db->from("candidates_info");

	    $this->db->join("courtver",'courtver.candsid = candidates_info.id');

	    $this->db->join("courtver_vendor_log",'courtver_vendor_log.case_id = courtver.id');
        
        $this->db->join("view_vendor_master_log",'(view_vendor_master_log.case_id = courtver_vendor_log.id and view_vendor_master_log.component_tbl_id = 5)');


		$this->db->where_in('candidates_info.id',$where_array);
		
		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		$result_array = $result->result_array();
		
        return $result_array;
	}

	
    public function check_identity_comp($clientid,$entity,$package)
    {
	      	
	    $this->db->select("component_id");

        $this->db->from('clients_details');
      
        $this->db->where('clients_details.tbl_clients_id',$clientid);

		$this->db->where('clients_details.entity',$entity);

		$this->db->where('clients_details.package',$package);

        $result  = $this->db->get();
          
        return $result->result_array();

	
    }

	public function select_record_present($tableName,$select_array,$where_array)
    {
		$this->db->select($select_array);

        $this->db->from($tableName);
    
		$this->db->where($where_array);

        $result  = $this->db->get();
          
        return $result->result_array();
	}

	public function select_record_present_like($tableName,$search_column,$select_array,$where_array)
    {
		$this->db->select($select_array);

        $this->db->from($tableName);
    
		$this->db->like($tableName.".".$search_column, $where_array);
		
        $result  = $this->db->get();
         
        return $result->result_array();
	}

	public function select_final_approve_qc_run()
	{ 

		$conditional_status = "(`candidates_info`.`overallstatus` = '3' or `candidates_info`.`overallstatus` = '4' or `candidates_info`.`overallstatus` = '6' or `candidates_info`.`overallstatus` = '7' or `candidates_info`.`overallstatus` = '8' )";

		$conditional_date = "(`candidates_info`.`overallclosuredate` != '0000-00-00')";


        $this->db->select('candidates_info.id');

		$this->db->from("candidates_info");
        
        $this->db->where("candidates_info.status =", 1);

        $this->db->where("candidates_info.final_qc =", 'Final QC Approve');

        $this->db->where("clients_details.final_qc =", 1);

        $this->db->where("candidates_info.final_qc_send_mail !=", 2);

        $this->db->where("candidates_info.final_qc_send_mail !=", 1);

        $this->db->where("clients_details.report_type =", 1);

        $this->db->where("clients_details.auto_report =", 1);

        $this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("status",'status.id = candidates_info.overallstatus');

		$this->db->join("clients_details",'clients_details.tbl_clients_id = candidates_info.clientid and `clients_details`.`entity` = `candidates_info`.`entity` AND `clients_details`.`package` = `candidates_info`.`package`');
        
     
        $this->db->where($conditional_status);

		$this->db->where($conditional_date);

		$this->db->where($conditional_date);

		$this->db->where("final_qc_approve_reject_timestamp <= DATE_SUB(NOW(), INTERVAL 30 MINUTE)", NULL, FALSE);

		
		$this->db->order_by('candidates_info.modified_on','ASC');
	
        $this->db->limit(15);
      
        $result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();

	} 

	
}
?>