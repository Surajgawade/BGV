<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Candidates_model extends CI_Model
{
	function __construct()
    {
		//parent::__construct();

		$this->tableName = 'client_candidates_info';

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

	public function select_candidate_entity_package_list($tableName, $return_as_strict_row,$select_array, $where_array=array(),$where)
	{
		$this->db->select($select_array);

		$this->db->from($tableName);

        $this->db->where($where_array);
         
        $this->db->where_in($where); 
        
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


	public function select_client_admin($return_as_strict_row,$select_array, $where_array = array())
	{
        if(empty($select_array))
		{
			array_push($select_array, '*');
		}

		$select = implode(",",$select_array);

		$this->db->select($select);

		$this->db->from('candidates_info');

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

	public function save_task_manager($tableName,$arrdata,$arrwhere = array())
  	{

  		
	    if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update($tableName, $arrdata);

			record_db_error($this->db->last_query());

			return $result;
	    }
	    else
	    {
			$this->db->insert($tableName, $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	    }
 	}

 	public function save_actual_table($arrdata,$arrwhere = array())
  	{

  		
	    if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update('candidates_info', $arrdata);

			record_db_error($this->db->last_query());

			return $result;
	    }
	    else
	    {
			$this->db->insert('candidates_info', $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	    }
 	}


 	public function save_candidate_log_main($arrdata)
	{
	   
	    $this->db->insert('candidates_info_logs', $arrdata);

	    record_db_error($this->db->last_query());

	    return $this->db->insert_id();
	   
	}

	public function update_auto_increament_value($arrdata,$arrwhere = array())
	{
        
		   $this->db->where($arrwhere);

		   $result = $this->db->update('candidates_info', $arrdata);

		   record_db_error($this->db->last_query());
		
		  return $result;

	}

 	public function save_candidate($arrdata)
	{
	   
	    $this->db->insert('client_candidates_info_logs', $arrdata);

	    record_db_error($this->db->last_query());

	    return $this->db->insert_id();
	   
	}


	public function delete($arrwhere)
	{
	  $result =  $this->db->delete($this->tableName, $arrwhere);

	  record_db_error($this->db->last_query());
	  
	  return $result;
	}
	
	public function get_all_cand_by($client_id = NULL)
	{
		$this->db->select("candidates_info.*,clients.clientname");

		$this->db->from('candidates_info');

		$this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("eduver",'eduver.candidates_infoid = candidates_info.id','left');

		if($client_id)
		{
			$this->db->where("candidates_info.clientid",$client_id);
		}
		$this->db->order_by('id', 'desc');
		
		$this->db->group_by('candidates_info.id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
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

	protected function filter_where_cond_client_case($where_arry)
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
			$where['candidates_info.overallstatus'] = 5;
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
	
	

	protected function filter_where_cond_add_candidate($where_arry)
	{

		
		$where = array('client_candidates_info.status' => STATUS_ACTIVE);
  
		if(isset($where_arry['status']) &&  $where_arry['status'] != '')	
		{
			if($where_arry['status'] != 'All')
			{
			$where['status.filter_status'] = $where_arry['status'];
		    }
		   
		}
		else
		{
			$where['client_candidates_info.overallstatus'] = 1;
		}

		if(isset($where_arry['sub_status']) &&  $where_arry['sub_status'] != 0)	
		{
			$where['status.id'] = $where_arry['sub_status'];
		}

		if(isset($where_arry['client_id']) &&  $where_arry['client_id'] != 0)	
		{
			$where['client_candidates_info.clientid'] = $where_arry['client_id'];
		}

		if(isset($where_arry['entity']) &&  $where_arry['entity'] != 0)	
		{
			$where['client_candidates_info.entity'] = $where_arry['entity'];
		}

		if(isset($where_arry['package']) &&  $where_arry['package'] != 0)	
		{
			$where['client_candidates_info.package'] = $where_arry['package'];
		}
       
		return $where;
	}



	    public function get_all_cand_with_search($package_name,$client_id,$where,$columns= null)
		{
            $package_name1   = explode(",", $package_name);
			
			
			$this->db->select("candidates_info.id,candidates_info.clientid,candidates_info.CandidateName,candidates_info.caserecddate,candidates_info.Location,candidates_info.Department,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.overallstatus,candidates_info.tat_status_candidate, candidates_info.remarks,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,clients.clientname,clients_details.tat_addrver,clients_details.tat_courtver,clients_details.tat_crimver,clients_details.tat_eduver,clients_details.tat_empver,clients_details.tat_narcver,clients_details.tat_refver,clients_details.tat_globdbver,candidates_info.overallclosuredate,candidates_info.ClientRefNumber AS EmployeeCode,status.status_value");

			$this->db->from('candidates_info');
			
		    $this->db->where($this->filter_where_cond($where));


			if(is_array($where) && $where['search']['value'] != "")
			{
				$this->db->like('candidates_info'.'.ClientRefNumber', $where['search']['value']);

				$this->db->or_like('clients.clientname', $where['search']['value']);

				$this->db->or_like('candidates_info'.'.cmp_ref_no', $where['search']['value']);

				$this->db->or_like('candidates_info'.'.CandidateName', $where['search']['value']);

				$this->db->or_like('candidates_info'.'.CandidatesContactNumber', $where['search']['value']);

				$this->db->or_like('candidates_info'.'.EmployeeCode', $where['search']['value']);

				//$this->db->or_like($this->tableName.'.overallstatus', $where['search']['value']);

			}

	        $this->db->limit($where['length'],$where['start']);
	        //$this->db->limit(50,0);

			//$this->db->order_by('candidates_info.id', 'desc');
			
			$this->db->group_by('candidates_info.id');

	    

	        $this->db->join("status",'status.id = candidates_info.overallstatus');
 
	        if($client_id)
	        {
	            $this->db->join("clients","(clients.id = candidates_info.clientid and candidates_info.clientid = $client_id)");
	            $this->db->join("clients_details","(clients_details.tbl_clients_id = candidates_info.clientid and candidates_info.clientid = $client_id)");
	        }
	        else
	        {
	            $this->db->join("clients",'clients.id = candidates_info.clientid');
	            $this->db->join("clients_details",'clients_details.tbl_clients_id = candidates_info.clientid');
	        }

	        $this->db->where("candidates_info.status",1);


	        if($package_name)
	        {
	           $this->db->where_in("candidates_info.package",$package_name1); 
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
	        $str = $this->db->last_query();
			record_db_error($this->db->last_query());

			return $result->result_array();
		}
        

         public function get_all_cand_with_search_count_value($package_name,$client_id,$where,$columns= null)
		{
            $package_name1   = explode(",", $package_name);
			
			
			$this->db->select("candidates_info.id,candidates_info.clientid,candidates_info.CandidateName,candidates_info.caserecddate,candidates_info.Location,candidates_info.Department,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.overallstatus,candidates_info.tat_status_candidate, candidates_info.remarks,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,clients.clientname,clients_details.tat_addrver,clients_details.tat_courtver,clients_details.tat_crimver,clients_details.tat_eduver,clients_details.tat_empver,clients_details.tat_narcver,clients_details.tat_refver,clients_details.tat_globdbver,candidates_info.overallclosuredate,candidates_info.ClientRefNumber AS EmployeeCode,status.status_value");

			$this->db->from('candidates_info');
			
		    $this->db->where($this->filter_where_cond($where));


			if(is_array($where) && $where['search']['value'] != "")
			{
				$this->db->like('candidates_info'.'.ClientRefNumber', $where['search']['value']);

				$this->db->or_like('clients.clientname', $where['search']['value']);

				$this->db->or_like('candidates_info'.'.cmp_ref_no', $where['search']['value']);

				$this->db->or_like('candidates_info'.'.CandidateName', $where['search']['value']);

				$this->db->or_like('candidates_info'.'.CandidatesContactNumber', $where['search']['value']);

				$this->db->or_like('candidates_info'.'.EmployeeCode', $where['search']['value']);

				//$this->db->or_like($this->tableName.'.overallstatus', $where['search']['value']);

			}

	       // $this->db->limit($where['length'],$where['start']);
	        //$this->db->limit(50,0);

			//$this->db->order_by('candidates_info.id', 'desc');
			
			$this->db->group_by('candidates_info.id');

	        $this->db->join("status",'status.id = candidates_info.overallstatus');
 
	        if($client_id) {
	            $this->db->join("clients","(clients.id = candidates_info.clientid and candidates_info.clientid = $client_id)");
	            $this->db->join("clients_details","(clients_details.tbl_clients_id = candidates_info.clientid and candidates_info.clientid = $client_id)");
	        } else {
	            $this->db->join("clients",'clients.id = candidates_info.clientid');
	            $this->db->join("clients_details",'clients_details.tbl_clients_id = candidates_info.clientid');
	        }

	        $this->db->where("candidates_info.status",1);


	        if($package_name) {
	        	$this->db->where_in("candidates_info.package",$package_name1); 
	        }

	        if(!empty($where['order'])) {
				$column_name_index = $where['order'][0]['column'];
				$order_by = $where['order'][0]['dir'];
				$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		   	} else {
				$this->db->order_by('candidates_info.id','DESC');
		   	}
	    
	        $result = $this->db->get();
	        $str = $this->db->last_query();
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

		public function get_all_cand_with_search_add_candidate($package_name,$client_id,$where,$columns= null)
		{

            $package_name1   = explode(",", $package_name);
			
			$this->db->select("client_candidates_info.id,client_candidates_info.clientid,client_candidates_info.CandidateName,client_candidates_info.cands_info_id,client_candidates_info.created_on,client_candidates_info.caserecddate,client_candidates_info.Location,client_candidates_info.Department,client_candidates_info.ClientRefNumber,client_candidates_info.cmp_ref_no,candidates_info.overallstatus,client_candidates_info.remarks,(select entity_package_name from entity_package where entity_package.id = client_candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = client_candidates_info.package limit 1) as package_name,clients.clientname,clients_details.tat_addrver,clients_details.tat_courtver,clients_details.tat_crimver,clients_details.tat_eduver,clients_details.tat_empver,clients_details.tat_narcver,clients_details.tat_refver,clients_details.tat_globdbver,client_candidates_info.overallclosuredate,client_candidates_info.ClientRefNumber AS EmployeeCode,status.status_value");

			
	        $this->db->from('client_candidates_info');

			 
	        $this->db->where("client_candidates_info.status",1);

	        $this->db->where($this->filter_where_cond_add_candidate($where));

			if(is_array($where) && $where['search']['value'] != "")
			{
				$this->db->like($this->tableName.'.ClientRefNumber', $where['search']['value']);

				$this->db->or_like('clients.clientname', $where['search']['value']);

				$this->db->or_like($this->tableName.'.cmp_ref_no', $where['search']['value']);

				$this->db->or_like($this->tableName.'.CandidateName', $where['search']['value']);

				$this->db->or_like($this->tableName.'.CandidatesContactNumber', $where['search']['value']);

				$this->db->or_like($this->tableName.'.EmployeeCode', $where['search']['value']);

				//$this->db->or_like($this->tableName.'.overallstatus', $where['search']['value']);

			}



	        $this->db->join("candidates_info",'candidates_info.id = client_candidates_info.cands_info_id');

	        $this->db->join("status",'status.id = client_candidates_info.overallstatus');

	        if($client_id)
	        {
	            $this->db->join("clients","(clients.id = client_candidates_info.clientid and client_candidates_info.clientid = $client_id)");
	            $this->db->join("clients_details","(clients_details.tbl_clients_id = client_candidates_info.clientid and client_candidates_info.clientid = $client_id)");
	        }
	        else
	        {
	            $this->db->join("clients",'clients.id = client_candidates_info.clientid');
	            $this->db->join("clients_details",'clients_details.tbl_clients_id = client_candidates_info.clientid');
	        }

	        if($package_name)
	        {
	           $this->db->where_in("client_candidates_info.package",$package_name1); 
	        }
	      
	       if(!empty($where['order']))
		  {

			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir'];
           
			$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		  }
		  else
		 {
			$this->db->order_by('client_candidates_info.id','DESC');
		 }


	        $this->db->limit($where['length'],$where['start']);
	        //$this->db->limit(50,0);

			//$this->db->order_by('candidates_info.id', 'desc');
			
			$this->db->group_by('client_candidates_info.id');

	        $result = $this->db->get();
	        $str = $this->db->last_query();
	        
			record_db_error($this->db->last_query());

			return $result->result_array();
		}
		
		public function get_all_cand_with_search_pre_post($package_name,$client_id,$where,$columns= null)
		{

            $package_name1   = explode(",", $package_name);
			
			$this->db->select("pre_post_details.*,(select id from candidates_info where candidates_info.cmp_ref_no = pre_post_details.component_ref_no limit 1) as candidate_id,(select overallstatus from candidates_info where candidates_info.cmp_ref_no = pre_post_details.component_ref_no limit 1) as overallstatus,(select final_qc from candidates_info where candidates_info.cmp_ref_no = pre_post_details.component_ref_no limit 1) as final_qc,(select clientname from clients where clients.id = pre_post_details.client_id limit 1) as clientname,(select clientname from clients where clients.id = pre_post_details.client_id limit 1) as clientname,(select entity_package_name from entity_package where entity_package.id = pre_post_details.entity limit 1) as entity,(select entity_package_name from entity_package where entity_package.id = pre_post_details.package limit 1) as package");

	        $this->db->from('pre_post_details');

			$this->db->where("pre_post_details.type",3); 

			if(is_array($where) && $where['search']['value'] != "")
			{
				$this->db->like('pre_post_details.client_ref_no', $where['search']['value']);

				$this->db->or_like('pre_post_details.candidate_name', $where['search']['value']);

				$this->db->or_like('pre_post_details.primary_contact', $where['search']['value']);

				$this->db->or_like('pre_post_details.contact_two', $where['search']['value']);

	            $this->db->or_like('pre_post_details.contact_three', $where['search']['value']);


			}

	        if($client_id)
	        {
				$this->db->where("pre_post_details.client_id",$client_id); 

	        }
	        
	        if($package_name)
	        {
	           $this->db->where_in("pre_post_details.package",$package_name1); 
	        }
	      
			if(!empty($where['order']))
			{

				$column_name_index = $where['order'][0]['column'];
				$order_by = $where['order'][0]['dir'];
			
				$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
			}
			else
			{
				$this->db->order_by('pre_post_details.id','DESC');
			}


	        $this->db->limit($where['length'],$where['start']);
	       
	        $result = $this->db->get();

			record_db_error($this->db->last_query());

			return $result->result_array();
		}

		public function get_all_cand_with_search_count_pre_post($package_name,$client_id,$where,$columns)
		{
	
			$package_name1   = explode(",", $package_name);
	
			$this->db->select("pre_post_details.*,(select id from candidates_info where candidates_info.cmp_ref_no = pre_post_details.component_ref_no limit 1) as candidate_id,(select overallstatus from candidates_info where candidates_info.cmp_ref_no = pre_post_details.component_ref_no limit 1) as overallstatus,(select final_qc from candidates_info where candidates_info.cmp_ref_no = pre_post_details.component_ref_no limit 1) as final_qc,(select clientname from clients where clients.id = pre_post_details.client_id limit 1) as clientname,(select clientname from clients where clients.id = pre_post_details.client_id limit 1) as clientname,(select entity_package_name from entity_package where entity_package.id = pre_post_details.entity limit 1) as entity,(select entity_package_name from entity_package where entity_package.id = pre_post_details.package limit 1) as package");
	
			$this->db->from('pre_post_details');

			$this->db->where("pre_post_details.type",3); 
			  
			if(is_array($where) && $where['search']['value'] != "")
			{
				$this->db->like('pre_post_details.client_ref_no', $where['search']['value']);

				$this->db->or_like('pre_post_details.candidate_name', $where['search']['value']);

				$this->db->or_like('pre_post_details.primary_contact', $where['search']['value']);

				$this->db->or_like('pre_post_details.contact_two', $where['search']['value']);

	            $this->db->or_like('pre_post_details.contact_three', $where['search']['value']);


			}

	        if($client_id)
	        {
				$this->db->where("pre_post_details.client_id",$client_id); 

	        }
	        
	        if($package_name)
	        {
	           $this->db->where_in("pre_post_details.package",$package_name1); 
	        }
	    
	
			
			if(!empty($where['order']))
			{
	
				$column_name_index = $where['order'][0]['column'];
				$order_by = $where['order'][0]['dir'];
				
				$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
			}
			else
			{
				$this->db->order_by('pre_post_details.id','DESC');
			}
			
	
			$result = $this->db->get();
	
			record_db_error($this->db->last_query());
	
			return $result->result_array();
		}

		public function get_all_cand_with_search_initiation($package_name,$client_id,$where,$columns= null)
		{

            $package_name1   = explode(",", $package_name);
			
			$this->db->select("pre_post_details.*,(select id from candidates_info where candidates_info.cmp_ref_no = pre_post_details.component_ref_no limit 1) as candidate_id,(select overallstatus from candidates_info where candidates_info.cmp_ref_no = pre_post_details.component_ref_no limit 1) as overallstatus,(select final_qc from candidates_info where candidates_info.cmp_ref_no = pre_post_details.component_ref_no limit 1) as final_qc,(select clientname from clients where clients.id = pre_post_details.client_id limit 1) as clientname,(select clientname from clients where clients.id = pre_post_details.client_id limit 1) as clientname,(select entity_package_name from entity_package where entity_package.id = pre_post_details.entity limit 1) as entity,(select entity_package_name from entity_package where entity_package.id = pre_post_details.package limit 1) as package");

	        $this->db->from('pre_post_details');

			$this->db->where("pre_post_details.type",2); 

			if(is_array($where) && $where['search']['value'] != "")
			{
				$this->db->like('pre_post_details.client_ref_no', $where['search']['value']);

				$this->db->or_like('pre_post_details.candidate_name', $where['search']['value']);

				$this->db->or_like('pre_post_details.primary_contact', $where['search']['value']);

				$this->db->or_like('pre_post_details.contact_two', $where['search']['value']);

	            $this->db->or_like('pre_post_details.contact_three', $where['search']['value']);


			}

	        if($client_id)
	        {
				$this->db->where("pre_post_details.client_id",$client_id); 

	        }
	        
	        if($package_name)
	        {
	           $this->db->where_in("pre_post_details.package",$package_name1); 
	        }
	      
	        if(!empty($where['order']))
			{

				$column_name_index = $where['order'][0]['column'];
				$order_by = $where['order'][0]['dir'];
			
				$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
			}
			else
			{
				$this->db->order_by('pre_post_details.id','DESC');
			}


	        $this->db->limit($where['length'],$where['start']);
	       
	        $result = $this->db->get();

			record_db_error($this->db->last_query());

			return $result->result_array();
		}

		public function get_all_cand_with_search_count_initiation($package_name,$client_id,$where,$columns)
		{
	
			$package_name1   = explode(",", $package_name);
	
			$this->db->select("pre_post_details.*,(select id from candidates_info where candidates_info.cmp_ref_no = pre_post_details.component_ref_no limit 1) as candidate_id,(select overallstatus from candidates_info where candidates_info.cmp_ref_no = pre_post_details.component_ref_no limit 1) as overallstatus,(select final_qc from candidates_info where candidates_info.cmp_ref_no = pre_post_details.component_ref_no limit 1) as final_qc,(select clientname from clients where clients.id = pre_post_details.client_id limit 1) as clientname,(select entity_package_name from entity_package where entity_package.id = pre_post_details.entity limit 1) as entity,(select entity_package_name from entity_package where entity_package.id = pre_post_details.package limit 1) as package");
	
			$this->db->from('pre_post_details');

			$this->db->where("pre_post_details.type",2); 

			if(is_array($where) && $where['search']['value'] != "")
			{
				$this->db->like('pre_post_details.client_ref_no', $where['search']['value']);

				$this->db->or_like('pre_post_details.candidate_name', $where['search']['value']);

				$this->db->or_like('pre_post_details.primary_contact', $where['search']['value']);

				$this->db->or_like('pre_post_details.contact_two', $where['search']['value']);

	            $this->db->or_like('pre_post_details.contact_three', $where['search']['value']);


			}

	        if($client_id)
	        {
				$this->db->where("pre_post_details.client_id",$client_id); 

	        }
	        
	        if($package_name)
	        {
	           $this->db->where_in("pre_post_details.package",$package_name1); 
	        }
	    
	
			
			if(!empty($where['order']))
			{
	
				$column_name_index = $where['order'][0]['column'];
				$order_by = $where['order'][0]['dir'];
				
				$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
			}
			else
			{
				$this->db->order_by('pre_post_details.id','DESC');
			}
			
	
			$result = $this->db->get();
	
			record_db_error($this->db->last_query());
	
			return $result->result_array();
		}

	/*public function get_all_cand_with_search($client_id,$where,$columns= null)
		{
			$this->db->select("candidates_info.id,candidates_info.clientid,candidates_info.CandidateName,candidates_info.caserecddate,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.overallstatus,candidates_info.remarks,clients.clientname,clients.addrver,clients.courtver,clients.crimver,clients.eduver,clients.empver,clients.narcver,clients.refver,clients.globdbver,candidates_info.overallclosuredate,candidates_info.ClientRefNumber AS EmployeeCode");

			if($where['status'] != "")
			{			
				$status_array = array('stop-check' => 'Stop/Check','unable-to-verified'=>'Unable to Verify','completed' =>'Clear');

				if(array_key_exists($where['status'] ,$status_array))
				{
					$where['status'] = $status_array[$where['status']];
				}

				$this->db->where("candidates_info.overallstatus",$where['status']);

				if($where['status'] == 'discrepancy')
				{
					$this->db->or_where("candidates_info.overallstatus",'No Record Found');
				}
				
			}

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

			$this->db->order_by('candidates_info.id', 'desc');
			
			$this->db->group_by('candidates_info.id');

	        $this->db->from('candidates_info');

	        if($client_id)
	        {
	            $this->db->join("clients","(clients.id = candidates_info.clientid and candidates_info.clientid = $client_id)");
	        }
	        else
	        {
	            $this->db->join("clients",'clients.id = candidates_info.clientid');
	        }


	        $result = $this->db->get();
	        $str = $this->db->last_query();

			record_db_error($this->db->last_query());

			return $result->result_array();
		}*/

	public function select_pre_post_details($where_array)
	{
		$this->db->select("pre_post_details.*");

		$this->db->from('pre_post_details');

		if(!empty($where_array))
		{
			$this->db->where($where_array);
		}

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$result_array = $result->result_array();
        
        return $result_array;

	}	

	public function select_attachment_details($where_array)
	{
		$this->db->select("client_new_case_file.*");

		$this->db->from('client_new_case_file');

		if(!empty($where_array))
		{
			$this->db->where($where_array);
		}

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$result_array = $result->result_array();
        
        return $result_array;

	}

	public function get_all_cand_with_search_new($client_id,$where,$columns)
    {
        $this->db->select("candidates_info.id,candidates_info.clientid,candidates_info.CandidateName,candidates_info.caserecddate,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.overallstatus,candidates_info.remarks,clients.clientname,clients_details.tat_addrver,clients_details.tat_courtver,clients_details.tat_crimver,clients_details.tat_eduver,clients_details.tat_empver,clients_details.tat_narcver,clients_details.tat_refver,clients_details.tat_globdbver,candidates_info.overallclosuredate,candidates_info.EmployeeCode ");


        if($where['status'] != "")
        {
           /* $status_array = array('stop-check' => 'Stop/Check','unable-to-verified'=>'Unable to Verify','completed' =>'Clear');

            if(array_key_exists($where['status'] ,$status_array))
            {
                $where['status'] = $status_array[$where['status']];
            }*/

            $this->db->where("candidates_info.overallstatus",$where['status']);

           /* if($where['status'] == 'discrepancy')
            {
                $this->db->or_where("candidates_info.overallstatus",'No Record Found');
            }*/

        }

        if(is_array($where) && $where['search']['value'] != "")
        {
            $this->db->like($this->tableName.'.ClientRefNumber', $where['search']['value']);

            $this->db->or_like('clients.clientname', $where['search']['value']);

            $this->db->or_like($this->tableName.'.cmp_ref_no', $where['search']['value']);

            $this->db->or_like($this->tableName.'.CandidateName', $where['search']['value']);

            $this->db->or_like($this->tableName.'.CandidatesContactNumber', $where['search']['value']);

            $this->db->or_like($this->tableName.'.EmployeeCode', $where['search']['value']);

          //  $this->db->or_like($this->tableName.'.overallstatus', $where['search']['value']);

        }

        $this->db->limit($where['length'],$where['start']);

        $this->db->order_by('candidates_info.id', 'desc');

        $this->db->group_by('candidates_info.id');
        $this->db->from('candidates_info');

        if($client_id)
        {
            $this->db->join("clients","(clients.id = candidates_info.clientid and candidates_info.clientid = $client_id)");
            $this->db->join("clients_details","(clients_details.tbl_clients_id = candidates_info.clientid and candidates_info.clientid = $client_id)");
        }
        else
        {
            $this->db->join("clients",'clients.id = candidates_info.clientid');
            $this->db->join("clients_details",'clients_details.tbl_clients_id = candidates_info.clientid');

        }

//		$result = $this->db->get_compiled_select();
        $result = $this->db->get();
//		var_dump($result);exit();
//		echo $result;exit();

        record_db_error($this->db->last_query());

//		return $result;
        return $result->result_array();
    }

	public function get_all_cand_with_search_count($package_name,$client_id,$where,$columns)
	{

        $package_name1   = explode(",", $package_name);

		$this->db->select("candidates_info.id,candidates_info.clientid,candidates_info.CandidateName,candidates_info.caserecddate,candidates_info.ClientRefNumber,candidates_info.tat_status_candidate, candidates_info.cmp_ref_no,candidates_info.overallstatus,candidates_info.remarks,clients.clientname,clients_details.tat_addrver,clients_details.tat_courtver,clients_details.tat_crimver,clients_details.tat_eduver,clients_details.tat_empver,clients_details.tat_narcver,clients_details.tat_refver,clients_details.tat_globdbver,candidates_info.overallclosuredate");

		$this->db->from('candidates_info');
  		
  		if($client_id)
		{
			$this->db->join("clients","(clients.id = candidates_info.clientid and candidates_info.clientid = $client_id)");
		    $this->db->join("clients_details","(clients_details.tbl_clients_id = candidates_info.clientid and candidates_info.clientid = $client_id)");

		}   
        else
		{
			$this->db->join("clients",'clients.id = candidates_info.clientid');
            $this->db->join("clients_details",'clients_details.tbl_clients_id = candidates_info.clientid');

		}
		
	
	 	$this->db->where("candidates_info.status",1);
		

		if($where['status'] != "")
		{
			$status_array = array('stop-check' => 'Stop/Check','unable-to-verified'=>'Unable to Verify','completed' =>'Clear');

			if(array_key_exists($where['status'] ,$status_array))
			{
				$where['status'] = $status_array[$where['status']];
			}

			$this->db->where("candidates_info.overallstatus",$where['status']);

			if($where['status'] == 'discrepancy')
			{
				$this->db->or_where("candidates_info.overallstatus",'No Record Found');
			}
		}
		
		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like('candidates_info'.'.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);

			$this->db->or_like('candidates_info'.'.cmp_ref_no', $where['search']['value']);

			$this->db->or_like('candidates_info'.'.CandidateName', $where['search']['value']);

			$this->db->or_like('candidates_info'.'.CandidatesContactNumber', $where['search']['value']);

			$this->db->or_like('candidates_info'.'.EmployeeCode', $where['search']['value']);

		//	$this->db->or_like($this->tableName.'.overallstatus', $where['search']['value']);

		}

		  if($package_name)
	        {
	           $this->db->where("candidates_info.package",$package_name); 
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
	       
		
		$this->db->group_by('candidates_info.id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	protected function filter_where_condition($where_arry)
	{

		$where = array('client_candidates_info.status' => STATUS_ACTIVE);
  
		if(isset($where_arry['client_id']) &&  $where_arry['client_id'] != 0)	
		{
			$where['client_candidates_info.clientid'] = $where_arry['client_id'];
		}

		if(isset($where_arry['entity']) &&  $where_arry['entity'] != 0)	
		{
			$where['client_candidates_info.entity'] = $where_arry['entity'];
		}

		if(isset($where_arry['package']) &&  $where_arry['package'] != 0)	
		{
			$where['client_candidates_info.package'] = $where_arry['package'];
		}
       
		return $where;
	}


	public function get_all_cand_with_search_count_add_candidate($package_name,$client_id,$where,$columns)
	{

        $package_name1   = explode(",", $package_name);

		$this->db->select("client_candidates_info.id,client_candidates_info.clientid,client_candidates_info.CandidateName,client_candidates_info.caserecddate,client_candidates_info.ClientRefNumber,client_candidates_info.cmp_ref_no,client_candidates_info.overallstatus,client_candidates_info.remarks,clients.clientname,clients_details.tat_addrver,clients_details.tat_courtver,clients_details.tat_crimver,clients_details.tat_eduver,clients_details.tat_empver,clients_details.tat_narcver,clients_details.tat_refver,clients_details.tat_globdbver,client_candidates_info.overallclosuredate");

		$this->db->from('client_candidates_info');
  		
  		if($client_id)
		{
			$this->db->join("clients","(clients.id = client_candidates_info.clientid and client_candidates_info.clientid = $client_id)");
		    $this->db->join("clients_details","(clients_details.tbl_clients_id = client_candidates_info.clientid and client_candidates_info.clientid = $client_id)");

		}   
        else
		{
			$this->db->join("clients",'clients.id = client_candidates_info.clientid');
            $this->db->join("clients_details",'clients_details.tbl_clients_id = client_candidates_info.clientid');

		}

	    $this->db->join("status",'status.id = client_candidates_info.overallstatus');


		$this->db->where("client_candidates_info.status",1);
		
        if(empty($package_name) && empty($client_id)){
		    $this->db->where($this->filter_where_condition($where));
		}else{
	    $this->db->where($this->filter_where_cond_add_candidate($where));
	    }

		
		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like($this->tableName.'.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);

			$this->db->or_like($this->tableName.'.cmp_ref_no', $where['search']['value']);

			$this->db->or_like($this->tableName.'.CandidateName', $where['search']['value']);

			$this->db->or_like($this->tableName.'.CandidatesContactNumber', $where['search']['value']);

			$this->db->or_like($this->tableName.'.EmployeeCode', $where['search']['value']);


		}

		  if($package_name)
	        {
	           $this->db->where("client_candidates_info.package",$package_name); 
	        }
	       
		
		if(!empty($where['order']))
		    {

			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir'];
			
			$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		   }
		   else
		   {
			$this->db->order_by('client_candidates_info.id','DESC');
		   }
		
		$this->db->group_by('client_candidates_info.id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	    public function get_all_cand_with_search_mail_view($package_name,$client_id,$where,$columns= null)
		{
              $package_name1   = explode(",", $package_name);
			
			
			$this->db->select("client_candidates_info.id,client_candidates_info.clientid,client_candidates_info.CandidateName,client_candidates_info.cands_info_id,client_candidates_info.created_on,client_candidates_info.caserecddate,client_candidates_info.Location,client_candidates_info.Department,client_candidates_info.ClientRefNumber,client_candidates_info.cmp_ref_no,client_candidates_info.public_key,client_candidates_info.private_key,candidates_info.overallstatus,client_candidates_info.remarks,(select entity_package_name from entity_package where entity_package.id = client_candidates_info.entity limit 1) as entity_name,client_candidates_info.is_mail_sent,client_candidates_info.is_sms_sent,(select entity_package_name from entity_package where entity_package.id = client_candidates_info.package limit 1) as package_name,clients.clientname,clients_details.tat_addrver,clients_details.tat_courtver,clients_details.tat_crimver,clients_details.tat_eduver,clients_details.tat_empver,clients_details.tat_narcver,clients_details.tat_refver,clients_details.tat_globdbver,client_candidates_info.overallclosuredate,client_candidates_info.ClientRefNumber AS EmployeeCode,status.status_value");

			$this->db->from('client_candidates_info');


	        $this->db->join("candidates_info",'candidates_info.id = client_candidates_info.cands_info_id');

	        $this->db->join("status",'status.id = candidates_info.overallstatus');

	        if($client_id)
	        {
	            $this->db->join("clients","(clients.id = client_candidates_info.clientid and client_candidates_info.clientid = $client_id)");
	            $this->db->join("clients_details","(clients_details.tbl_clients_id = client_candidates_info.clientid and client_candidates_info.clientid = $client_id)");
	        }
	        else
	        {
	            $this->db->join("clients",'clients.id = client_candidates_info.clientid');
	            $this->db->join("clients_details",'clients_details.tbl_clients_id = client_candidates_info.clientid');
	        }
            

	        $this->db->where("client_candidates_info.status",1);
		    $this->db->where("client_candidates_info.check_mail_send",1);

		    if(empty($package_name) && empty($client_id))
		    {
		    	$this->db->where($this->filter_where_condition($where));
		    }
           

			if(is_array($where) && $where['search']['value'] != "")
			{
				$this->db->like($this->tableName.'.ClientRefNumber', $where['search']['value']);

				$this->db->or_like('clients.clientname', $where['search']['value']);

				$this->db->or_like($this->tableName.'.cmp_ref_no', $where['search']['value']);

				$this->db->or_like($this->tableName.'.CandidateName', $where['search']['value']);

				$this->db->or_like($this->tableName.'.CandidatesContactNumber', $where['search']['value']);

				$this->db->or_like($this->tableName.'.EmployeeCode', $where['search']['value']);

				//$this->db->or_like($this->tableName.'.overallstatus', $where['search']['value']);

			}	     

		        if($package_name)
		        {
		           $this->db->where_in("client_candidates_info.package",$package_name1); 
		        }
	      
		       if(!empty($where['order']))
			   {

				$column_name_index = $where['order'][0]['column'];
				$order_by = $where['order'][0]['dir'];
	           
				$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
			  }
			  else
			  {
				$this->db->order_by('client_candidates_info.id','DESC');
			  }

		    $this->db->limit($where['length'],$where['start']);

		    $this->db->group_by('client_candidates_info.id');


	        $result = $this->db->get();
	        $str = $this->db->last_query();
	        
			record_db_error($this->db->last_query());

			return $result->result_array();
		}
    
    public function get_all_cand_with_search_count_mail_view($package_name,$client_id,$where,$columns)
	{

        $package_name1   = explode(",", $package_name);

		$this->db->select("client_candidates_info.id,client_candidates_info.clientid,client_candidates_info.CandidateName,client_candidates_info.caserecddate,client_candidates_info.ClientRefNumber,client_candidates_info.cmp_ref_no,client_candidates_info.public_key,client_candidates_info.private_key,client_candidates_info.is_mail_send,client_candidates_info.is_sms_send,client_candidates_info.overallstatus,client_candidates_info.remarks,clients.clientname,clients_details.tat_addrver,clients_details.tat_courtver,clients_details.tat_crimver,clients_details.tat_eduver,clients_details.tat_empver,clients_details.tat_narcver,clients_details.tat_refver,clients_details.tat_globdbver,client_candidates_info.overallclosuredate");

		$this->db->from('client_candidates_info');


	    $this->db->join("candidates_info",'candidates_info.id = client_candidates_info.cands_info_id');

	    $this->db->join("status",'status.id = candidates_info.overallstatus');
  		
  		if($client_id)
		{
			$this->db->join("clients","(clients.id = client_candidates_info.clientid and client_candidates_info.clientid = $client_id)");
		    $this->db->join("clients_details","(clients_details.tbl_clients_id = client_candidates_info.clientid and client_candidates_info.clientid = $client_id)");

		}   
        else
		{
			$this->db->join("clients",'clients.id = client_candidates_info.clientid');
            $this->db->join("clients_details",'clients_details.tbl_clients_id = client_candidates_info.clientid');

		}

		$this->db->where("client_candidates_info.status",1);
		$this->db->where("client_candidates_info.check_mail_send",1);


		
		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like($this->tableName.'.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);

			$this->db->or_like($this->tableName.'.cmp_ref_no', $where['search']['value']);

			$this->db->or_like($this->tableName.'.CandidateName', $where['search']['value']);

			$this->db->or_like($this->tableName.'.CandidatesContactNumber', $where['search']['value']);

			$this->db->or_like($this->tableName.'.EmployeeCode', $where['search']['value']);


		}

		  if($package_name)
	        {
	           $this->db->where("client_candidates_info.package",$package_name); 
	        }
	       
		
		if(!empty($where['order']))
		    {

			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir'];
			
			$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		   }
		   else
		   {
			$this->db->order_by('client_candidates_info.id','DESC');
		   }
		
		$this->db->group_by('client_candidates_info.id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}
	
	
	public function get_all_cand_with_search_client_case($where,$columns= null)
	{
		  
	
			$this->db->select("client_candidates_info.id,client_candidates_info.clientid,client_candidates_info.CandidateName,client_candidates_info.cands_info_id,client_candidates_info.created_on,client_candidates_info.caserecddate,client_candidates_info.Location,client_candidates_info.Department,client_candidates_info.ClientRefNumber,client_candidates_info.cmp_ref_no,client_candidates_info.public_key,client_candidates_info.private_key,candidates_info.overallstatus,client_candidates_info.remarks,(select entity_package_name from entity_package where entity_package.id = client_candidates_info.entity limit 1) as entity_name,client_candidates_info.is_mail_sent,client_candidates_info.is_sms_sent,(select entity_package_name from entity_package where entity_package.id = client_candidates_info.package limit 1) as package_name,clients.clientname,client_candidates_info.overallclosuredate,client_candidates_info.ClientRefNumber AS EmployeeCode,status.status_value,client_candidates_info.last_sms_on,client_candidates_info.last_email_on,client_candidates_info.candidate_visit,client_candidates_info.last_candidate_visit,(select cancel_on from candidate_activity_record where candidate_activity_record.candidate_id = client_candidates_info.cands_info_id order by candidate_activity_record.id desc limit 1) as last_activity");

			$this->db->from('client_candidates_info');


			$this->db->join("candidates_info",'candidates_info.id = client_candidates_info.cands_info_id');

			$this->db->join("status",'status.id = candidates_info.overallstatus');

			$this->db->join("clients",'clients.id = candidates_info.clientid');


			$this->db->where("client_candidates_info.status",1);

		
			$this->db->where($this->filter_where_cond_client_case($where));
		

			if(is_array($where) && $where['search']['value'] != "")
			{
				$this->db->like($this->tableName.'.ClientRefNumber', $where['search']['value']);

				$this->db->or_like('clients.clientname', $where['search']['value']);

				$this->db->or_like($this->tableName.'.cmp_ref_no', $where['search']['value']);

				$this->db->or_like($this->tableName.'.CandidateName', $where['search']['value']);

				$this->db->or_like($this->tableName.'.CandidatesContactNumber', $where['search']['value']);

				$this->db->or_like($this->tableName.'.EmployeeCode', $where['search']['value']);

				//$this->db->or_like($this->tableName.'.overallstatus', $where['search']['value']);

			}	     

		
		   if(!empty($where['order']))
		   {

			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir'];
		   
			$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
			}
			else
			{
				$this->db->order_by('client_candidates_info.id','DESC');
			}

			$this->db->limit($where['length'],$where['start']);

			$this->db->group_by('client_candidates_info.id');


			$result = $this->db->get();
			$str = $this->db->last_query();
			
			record_db_error($this->db->last_query());

			return $result->result_array();
     	}

	public function get_all_cand_with_search_client_case_count($where,$columns)
	{


		$this->db->select("client_candidates_info.id,client_candidates_info.clientid,client_candidates_info.CandidateName,client_candidates_info.caserecddate,client_candidates_info.ClientRefNumber,client_candidates_info.cmp_ref_no,client_candidates_info.public_key,client_candidates_info.private_key,client_candidates_info.overallstatus,client_candidates_info.remarks,clients.clientname,client_candidates_info.overallclosuredate,client_candidates_info.candidate_visit,client_candidates_info.last_candidate_visit,(select cancel_on from candidate_activity_record where candidate_activity_record.candidate_id = client_candidates_info.cands_info_id order by candidate_activity_record.id desc limit 1) as last_activity");

		$this->db->from('client_candidates_info');


		$this->db->join("candidates_info",'candidates_info.id = client_candidates_info.cands_info_id');

		$this->db->join("status",'status.id = candidates_info.overallstatus');

		$this->db->join("clients",'clients.id = candidates_info.clientid');

		
	
		$this->db->where("client_candidates_info.status",1);

		$this->db->where($this->filter_where_cond_client_case($where));
		
		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like($this->tableName.'.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);

			$this->db->or_like($this->tableName.'.cmp_ref_no', $where['search']['value']);

			$this->db->or_like($this->tableName.'.CandidateName', $where['search']['value']);

			$this->db->or_like($this->tableName.'.CandidatesContactNumber', $where['search']['value']);

			$this->db->or_like($this->tableName.'.EmployeeCode', $where['search']['value']);


		}

			
		if(!empty($where['order']))
			{

			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir'];
			
			$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		}
		else
		{
			$this->db->order_by('client_candidates_info.id','DESC');
		}
		
		$this->db->group_by('client_candidates_info.id');

		$result = $this->db->get();

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

		$this->db->select("education.*,GROUP_CONCAT(education_files.file_name) as file_names,university_master.universityname,qualification_master.qualification as qualification_degree,ev1.verfstatus,ev1.res_mode_of_verification,ev1.remarks,ev1.verified_by,ev1.verifier_designation,ev1.	verifier_contact_details,ev2.res_mode_of_verification as em");

		$this->db->from('education');

		$this->db->join("education_result as ev1",'(ev1.education_id = education.id)');

		$this->db->join("education_result as ev2",'(ev2.education_id = education.id and ev1.id < ev2.id)','left');

		$this->db->join("education_files",'(education_files.education_id = ev1.id AND education_files.status = 1)','left');

		$this->db->join("university_master",'university_master.id = education.university_board');

		$this->db->join("qualification_master",'qualification_master.id = education.qualification');

		if(!empty($empver_aary))
		{
			$this->db->where($empver_aary);
		}

		$this->db->where('ev2.res_mode_of_verification is null');

		$this->db->group_by('education.id');

		$this->db->order_by('id', 'desc');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function employment_info_report($empver_aary = array())
	{
		$this->db->select('empver.*, company_database.coname,GROUP_CONCAT(empverres_files.file_name) as file_names,ev1.verfstatus, CONCAT( DATE_FORMAT(ev1.employed_from,"%D-%b-%y") , " to " , DATE_FORMAT(ev1.employed_to,"%D-%b-%y")) as employment_period, ev1.reportingmanager as ver_reportingmanager, ev1.res_reasonforleaving as ver_reasonforleaving,ev1.verfname,ev1.verfdesgn,ev1.fmlyowned,ev1.emp_designation,ev1.integrity_disciplinary_issue,ev1.exitformalities,ev1.modeofverification,ev1.remarks,ev1.res_remuneration as ver_remuneration,ev1.empverid as ver_empid,CONCAT( DATE_FORMAT(empver.empfrom,"%D-%b-%y") , " to ",DATE_FORMAT(empver.empto,"%D-%b-%y")) as enter_employment_period,ev1.emp_designation as ver_emp_designation');

		$this->db->from('empver');

		$this->db->join("company_database",'company_database.id = empver.nameofthecompany');

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
		$this->db->select("CONCAT(addrver.address,' ',addrver.city, ',', LOWER(addrver.state),', ', addrver.pincode ) as full_aadress ,GROUP_CONCAT(addrver_files.file_name) as file_names, CONCAT( DATE_FORMAT(add1.res_stay_from,'%D-%b-%y') , ' to ' , DATE_FORMAT(add1.res_stay_to,'%D-%b-%y')) as period_of_stay,add1.remarks,add1.verfstatus,add1.verified_by,add1.mode_of_verification,add1.resident_status,addrver.clientid,add1.res_stay_from");

		$this->db->from('addrver');

		$this->db->join("addrverres as add1",'add1.addrverid = addrver.id','left');

		$this->db->join("addrverres as add2",'(add2.addrverid = addrver.id and add1.id < add2.id)','left');

		$this->db->join("addrver_files",'(addrver_files.addrver_id = add1.id AND addrver_files.status = 1)','left');

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

    public function status_count($clientid,$package_id)
	{
		$this->db->select("overallstatus,CASE WHEN  overallstatus =  1 THEN 'WIP' WHEN overallstatus = 5  THEN 'Insufficiency' WHEN overallstatus = 3  THEN 'Stop Check'  WHEN overallstatus =  4 THEN 'Clear' WHEN overallstatus = 6 THEN 'Major Discrepancy'  WHEN overallstatus = 7 THEN 'Minor Discrepancy' WHEN overallstatus = 8 THEN 'Unable to verify'  END AS overallstatusvalue,COUNT(overallstatus) as total");

		$this->db->from('candidates_info');

		if($clientid)
		{
			$this->db->where('clientid',$clientid);
		}

		if($package_id)
		{
			$this->db->where_in('package',$package_id);
		}


		$this->db->group_by('overallstatus');

		$result  = $this->db->get();

		record_db_error($this->db->last_query());

		$results = convert_to_single_dimension_array($result->result_array(),'overallstatusvalue','total');

        $return['total'] = array_sum($results);
		
		if(!isset($results['WIP']))
		{
		  $results['WIP'] = '0';	
		}
        if(!isset($results['Insufficiency']))
		{
        $results['Insufficiency'] = '0';
        }
		
        $results['Closed'] = '0';
		$return['NoRecordFound'] = '0'; 
		
		if(!empty($results))
		{

            if(array_key_exists('',$results)) {
	            $results['WIP'] = $results['WIP'] + $results[''];
	        }

        	if(array_key_exists('WIP',$results)) {
	            $results['WIP'] = $results['WIP'];
	        }

	        if(array_key_exists('Insufficiency',$results)) {
	        
	            $results['Insufficiency'] = $results['Insufficiency'];
	        }

	        if(array_key_exists('Stop Check',$results)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results['Stop Check'];
	        }
            
            if(array_key_exists('Clear',$results)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results['Clear'];
	        }

	        if(array_key_exists('Major Discrepancy',$results)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results['Major Discrepancy'];
	        }

	         if(array_key_exists('Minor Discrepancy',$results)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results['Minor Discrepancy'];
	        }

	        if(array_key_exists('Unable to verify',$results)) {
	        	
	            $results['Closed'] = $results['Closed'] + $results['Unable to verify'];
	        }
	     
			foreach ($results as $key => $value) {
				$return[str_replace('/','',str_replace(' ','',$key))] = $value;
			}
			
		}

		return $return;
	}


	public function get_cases_by_date($date,$client_id)
	{
		$query = $this->db->query('SELECT count(id) as total_count FROM candidates_info where clientid = "'.$client_id.'" AND  DATE_FORMAT(overallclosuredate ,"%Y-%m-%d") >= "'.$date.'" AND DATE_FORMAT(overallclosuredate ,"%Y-%m-%d") <= "'.$date.'" AND overallstatus in ("clear","Stop/Check", "Discrepancy") ');

		$results = $query->row_array();
		
		return (!empty($results) ? $results['total_count'] : 0);
	}

	public function get_all_cases_by_date($date)
	{
		$query = $this->db->query('SELECT count(id) as total_count FROM candidates_info where DATE_FORMAT(overallclosuredate ,"%Y-%m-%d") >= "'.$date.'" AND DATE_FORMAT(overallclosuredate ,"%Y-%m-%d") <= "'.$date.'" AND overallstatus in ("clear","Stop/Check", "Discrepancy") ');

		$results = $query->row_array();
		
		return (!empty($results) ? $results['total_count'] : 0);
	}

	public function get_cases_by_months($client_id)
	{
		$query = $this->db->query('SELECT COUNT(id) as cases_count , MONTH(updated) as months FROM candidates_info where clientid = "'.$client_id.'" AND YEAR(updated) = YEAR(CURDATE()) AND overallstatus = "clear" GROUP BY DATE_FORMAT(updated,"%m") ');

		return  $query->result_array();
	}
	
	public function all_get_cases_by_months()
	{
		$query = $this->db->query('SELECT COUNT(id) as cases_count , MONTH(updated) as months FROM candidates_info where YEAR(updated) = YEAR(CURDATE()) AND overallstatus = "clear" GROUP BY DATE_FORMAT(updated,"%m") ');

		return  $query->result_array();
	}

	public function get_tat_status($where_array)
	{
		$this->db->select('datediff(updated,created) as diff_days');

		$this->db->from('candidates_info');

		$this->db->where($where_array);

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_tat_days($clientid)
	{
		$this->db->select('*')->from('clients');
		
		$this->db->where('id',$clientid);

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$results = $result->result_array();

		if(!empty($results))
		{
			$results = $results[0];
		}
		return $results;
	}

	public function pcc_info_report($address_aary = array())
	{
		$this->db->select("p1.verfstatus,p1.police_station_visit_date,p1.name_designation_police,p1.contact_number_police,p1.mode_of_verification,p1.remarks as remarks,candidates_info.clientid");

		$this->db->from('candidates_info');

		$this->db->join("pcc_result as p1",'p1.candsid = candidates_info.id');

		$this->db->join("pcc_result as p2",'(p2.candsid = candidates_info.id and p1.id < p2.id)','left');

		if(!empty($address_aary))
		{
			$this->db->where($address_aary);
		}

		$this->db->where('p2.verfstatus is null');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function courtinfo_report($where_arry = array())
	{
		$this->db->select("courtver.*,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.CandidateName,candidates_info.DateofBirth,candidates_info.NameofCandidateFather,candidates_info.MothersName,candidates_info.CandidatesContactNumber,GROUP_CONCAT(courtver_files.file_name) as file_names");

		$this->db->from('courtver');

		$this->db->join("candidates_info",'candidates_info.id = courtver.candsid');

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

	public function get_addres_ver_status($clientid)
	{
		$this->db->select("candidates_info.overallstatus,ev1.verfstatus,ev1.var_filter_status,addrver_insuff.insuff_raised_date,addrver_insuff.insuff_raise_remark,addrver_insuff.insuff_clear_date,ev1.closuredate,addrver_insuff.insuff_remarks,addrver.address as address,status.filter_status");

		$this->db->from('candidates_info');

		$this->db->join("addrver",'candidates_info.id = addrver.candsid');

		$this->db->join("clients",'(clients.id = candidates_info.clientid)');

		$this->db->join("addrverres as ev1",'ev1.addrverid = addrver.id','left');

		$this->db->join("addrverres as ev2",'(ev2.addrverid = addrver.id and ev1.id < ev2.id)','left');

		$this->db->join('addrver_insuff','(addrver_insuff.addrverid = addrver.id and addrver_insuff.status = 1)','left');

	    $this->db->join("status",'status.id = ev1.verfstatus');

		$this->db->where('ev2.verfstatus is null');
		
		if($clientid)
		{
			$this->db->where($clientid);
		}
		
		$this->db->order_by('addrver.id', 'ASC');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_education_ver_status($clientid)
	{
		$this->db->select("candidates_info.overallstatus,ev1.verfstatus,status.filter_status");

		$this->db->from('education');

		$this->db->join("candidates_info",'candidates_info.id = education.candsid');

		$this->db->join("clients",'(clients.id = candidates_info.clientid)');

		$this->db->join("education_result as ev1",'(ev1.education_id = education.id)','left');

		$this->db->join("education_result as ev2",'(ev2.education_id = education.id and ev1.id < ev2.id)','left');

	    $this->db->join('education_insuff','(education_insuff.education_id = education.id and education_insuff.status = 1)','left');

		 $this->db->join("status",'status.id = ev1.verfstatus');

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

	public function get_employment_ver_status($clientid)
	{
		$this->db->select("candidates_info.overallstatus,ev1.verfstatus,empverres_insuff.insuff_raised_date,empverres_insuff.insuff_raise_remark,ev1.closuredate,ev1.integrity_disciplinary_issue,ev1.verifiers_email_id,ev1.remarks as empres_remarks,company_database.coname,status.filter_status");

		$this->db->from('empver');

		$this->db->join("candidates_info",'candidates_info.id = empver.candsid');

		$this->db->join("clients",'(clients.id = candidates_info.clientid)');

		$this->db->join('company_database', 'company_database.id = empver.nameofthecompany');
		
		$this->db->join("empverres as ev1",'ev1.empverid = empver.id','left');

		$this->db->join("empverres as ev2",'(ev2.empverid = empver.id and ev1.id < ev2.id)','left');

		$this->db->join("empverres_insuff",'empverres_insuff.empverres_id = empver.id','left');

		 $this->db->join("status",'status.id = ev1.verfstatus');

		$this->db->where('ev2.verfstatus is null');

		if($clientid)
		{
			$this->db->where($clientid);
		}
				
		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

    public function get_employment_ver_status1($clientid)
	{
		$this->db->select("candidates_info.overallstatus,status.action as verfstatus,ev1.var_filter_status,ev1.remarks as verification_remarks,ev1.closuredate");

		$this->db->from('empver');

		$this->db->join("candidates_info",'candidates_info.id = empver.candsid');

		$this->db->join("clients",'(clients.id = candidates_info.clientid)');

		$this->db->join("empverres as ev1",'ev1.empverid = empver.id','left');

		$this->db->join("empverres as ev2",'(ev2.empverid = empver.id and ev1.id < ev2.id)','left');

	    $this->db->join('empverres_insuff','(empverres_insuff.empverres_id = empver.id and empverres_insuff.status = 1)','left');


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
		$this->db->select("candidates_info.overallstatus,ev1.verfstatus,status.filter_status");

		$this->db->from('candidates_info');

		$this->db->join('clients','clients.id = candidates_info.clientid');

		$this->db->join("courtver",'candidates_info.id = courtver.candsid','left');

		$this->db->join("courtver_result as ev1",'ev1.courtver_id = courtver.id','left');

        $this->db->join('courtver_insuff','(courtver_insuff.courtver_id = courtver.id and courtver_insuff.status = 1)','left');
 
		$this->db->join("status",'status.id = ev1.verfstatus');

		if($where_arry)
		{
			$this->db->where($where_arry);
		}

		$this->db->order_by('courtver.id', 'desc');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_pcc_ver_status($where_arry = array())
	{
		$this->db->select("candidates_info.overallstatus,pcc_result.verfstatus,status.filter_status");

		$this->db->from('candidates_info');

		$this->db->join('clients','clients.id = candidates_info.clientid');

		$this->db->join("pcc_result as pcc_result",'pcc_result.candsid = candidates_info.id','left');

		$this->db->join("pcc_result as p2",'(p2.candsid = candidates_info.id and pcc_result.id < p2.id)','left');

		$this->db->where('p2.verfstatus is null');
        
        $this->db->join("status",'status.id = pcc_result.verfstatus');


		if($where_arry)
		{
			$this->db->where($where_arry);
		}

		$this->db->order_by('pcc_result.id', 'desc');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function references_report($ref_aary = array())
	{
		$this->db->select("candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.CandidateName,candidates_info.caserecddate,reference.name_of_reference,reference.designation,reference.contact_no,ref1.created_on as last_modified,reference.designation,reference.contact_no,ref1.remarks,ref1.verfstatus as refverfstatus,reference.id,reference.clientid,reference.	has_assigned_on,ref1.id as refveri_id,GROUP_CONCAT(reference_files.file_name) as file_names,ref1.closuredate,candidates_info.id as candidates_infoid");

		$this->db->from('candidates_info');

		$this->db->join("clients",'(clients.id = candidates_info.clientid)');

		$this->db->join('reference','reference.candsid = candidates_info.id');

		$this->db->join("reference_result as ref1",'ref1.reference_id = reference.id','left');

		$this->db->join("reference_result as ref2",'(ref2.reference_id = reference.id and ref1.id < ref2.id)','left');

		$this->db->join("reference_files",'(reference_files.reference_id = ref1.id AND reference_files.status = 1)','left');

		$this->db->where('ref2.verfstatus is null');

		if(!empty($ref_aary))
		{
			$this->db->where($ref_aary);
		}

		$this->db->group_by('reference.id');

		$this->db->order_by('reference.id', 'desc');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_global_db_ver_status($where_arry = array())
	{
		$this->db->select("candidates_info.overallstatus,glob1.verfstatus,status.filter_status");

		$this->db->from('candidates_info');

		$this->db->join('clients','(clients.id = candidates_info.clientid)');

		$this->db->join("glodbver",'candidates_info.id = glodbver.candsid','left');

		$this->db->join("glodbver_result as glob1",'glob1.glodbver_id = glodbver.id','left');

	    $this->db->join('glodbver_insuff','(glodbver_insuff.glodbver_id = glodbver.id and glodbver_insuff.status = 1)','left');

		$this->db->join("status",'status.id = glob1.verfstatus');
        

		if($where_arry)
		{
			$this->db->where($where_arry);
		}

		$this->db->order_by('glodbver.id', 'desc');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}
		
	public function get_reference_ver_status($where_arry = array())
	{
		$this->db->select("ref1.*,reference.clientid,reference.clientid as client_id,reference.id,ref1.id as ref_ver_id,ref1.verfstatus,status.filter_status");

		$this->db->from('reference');

		$this->db->join('reference_result as ref1','ref1.reference_id = reference.id');

		$this->db->join("reference_result as ref2",'(ref2.reference_id = reference.id and ref1.id < ref2.id)','left');
		
		$this->db->where('ref2.verfstatus is null');

		 $this->db->join("status",'status.id = ref1.verfstatus');

		if($where_arry)
		{
			$this->db->where($where_arry);
		}
		
		$this->db->group_by('reference.id');

		$this->db->order_by('ref1.id', 'desc');
		
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

	    $this->db->join('identity_insuff','(identity_insuff.identity_id = identity.id and identity_insuff.status = 1)','left');


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
       
       	$this->db->join('credit_report_insuff','(credit_report_insuff.credit_report_id  = credit_report.id and credit_report_insuff.status = 1)','left');

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

	public function get_narcver_ver_status($where_arry = array())
	{
		$this->db->select("candidates_info.overallstatus,status.action as verfstatus,ev1.var_filter_status,ev1.remarks as verification_remarks,ev1.closuredate");

		$this->db->from('drug_narcotis');

		$this->db->join("candidates_info",'candidates_info.id = drug_narcotis.candsid');

		$this->db->join('clients','clients.id = candidates_info.clientid');

        $this->db->join("drug_narcotis_result as ev1",'ev1.drug_narcotis_id = drug_narcotis.id','left');

		$this->db->join("drug_narcotis_result as ev2",'(ev2.drug_narcotis_id = drug_narcotis.id and ev1.id < ev2.id)','left');

		$this->db->join('drug_narcotis_insuff','(drug_narcotis_insuff.drug_narcotis_id  = drug_narcotis.id and drug_narcotis_insuff.status = 1)','left');


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


	public function sales_all_cand_with_search($client_id,$where,$columns)
	{
		$this->db->select("candidates_info.id,candidates_info.clientid,candidates_info.CandidateName,candidates_info.caserecddate,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.overallstatus,candidates_info.remarks,clients.clientname,clients.addrver,clients.courtver,clients.crimver,clients.eduver,clients.empver,clients.narcver,clients.refver,clients.globdbver,candidates_info.overallclosuredate");

		$this->db->from('candidates_info');
        
        $this->db->join("clients","(clients.id = candidates_info.clientid and clients.sales_manager = $client_id)");
		
		if($where['status'] != "")
		{			
			$status_array = array('stop-check' => 'Stop/Check','unable-to-verified'=>'Unable to Verify','completed' =>'Clear');

			if(array_key_exists($where['status'] ,$status_array))
			{
				$where['status'] = $status_array[$where['status']];
			}

			$this->db->where("candidates_info.overallstatus",$where['status']);

			if($where['status'] == 'discrepancy')
			{
				$this->db->or_where("candidates_info.overallstatus",'No Record Found');
			}
			
		}

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

		$this->db->order_by('candidates_info.id', 'desc');
		
		$this->db->group_by('candidates_info.id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function sales_all_cand_with_search_count($client_id,$where,$columns)
	{
		$this->db->select("candidates_info.id,candidates_info.clientid,candidates_info.CandidateName,candidates_info.caserecddate,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.overallstatus,candidates_info.remarks,clients.clientname,clients.addrver,clients.courtver,clients.crimver,clients.eduver,clients.empver,clients.narcver,clients.refver,candidates_info.overallclosuredate");

		$this->db->from('candidates_info');
  		
  		$this->db->join("clients","(clients.id = candidates_info.clientid and clients.sales_manager = $client_id)");

		if($where['status'] != "")
		{
			$status_array = array('stop-check' => 'Stop/Check','unable-to-verified'=>'Unable to Verify','completed' =>'Clear');

			if(array_key_exists($where['status'] ,$status_array))
			{
				$where['status'] = $status_array[$where['status']];
			}

			$this->db->where("candidates_info.overallstatus",$where['status']);

			if($where['status'] == 'discrepancy')
			{
				$this->db->or_where("candidates_info.overallstatus",'No Record Found');
			}
		}
		
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
		
		$this->db->order_by('candidates_info.id', 'desc');
		
		$this->db->group_by('candidates_info.id');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function candidates_infoid_by_dates($client_id,$where)
	{
		$this->db->select("candidates_info.id,LOWER(candidates_info.CandidateName),candidates_info.caserecddate,candidates_info.EmployeeCode,clients.addrver,clients.empver,clients.clientname");

		$this->db->from('candidates_info');
  		
  		$this->db->join("clients","(clients.id = candidates_info.clientid and clients.id = $client_id)");

  		$this->db->where('candidates_info.overallstatus','Clear');

  		$this->db->where('caserecddate >=',convert_display_to_db_date('01-'.$where['month_of_reprots']));

  		$this->db->where('caserecddate <=',convert_display_to_db_date('30-'.$where['month_of_reprots']));

  		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();

	}

	public function excel_candidates_infoid_by_dates($client_id,$where)
	{
		$this->db->select("candidates_info.id,candidates_info.CandidateName,candidates_info.caserecddate,candidates_info.ClientRefNumber AS EmployeeCode,clients.addrver,clients.empver,candidates_info.build_date,candidates_info.remarks as candidates_info_remarks");

		$this->db->from('candidates_info');
  		
  		$this->db->join("clients","(clients.id = candidates_info.clientid and candidates_info.clientid = $client_id)");
		

  		$this->db->where('caserecddate >=',convert_display_to_db_date($where['from_date']));

  		$this->db->where('caserecddate <=',convert_display_to_db_date($where['to_date']));

  		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	/*public function get_excel($client_id,$where)
	{
	
		$this->db->select("candidates_info.id,candidates_info.CandidateName,candidates_info.caserecddate,candidates_info.ClientRefNumber AS EmployeeCode,clients.addrver,clients.empver,candidates_info.build_date,candidates_info.remarks as candidates_info_remarks");

		$this->db->from('candidates_info');
  		
  		$this->db->join("clients","(clients.id = candidates_info.clientid and candidates_info.clientid = $client_id)");
		//$this->db->join("addrverres","(clients.id = candidates_info.clientid and addrverres.clientid = $client_id)");
		//$this->db->join("empverres","(clients.id = candidates_info.clientid and empverres.clientid = $client_id)");
$this->db->join("addrverres","(clients.id = candidates_info.clientid and addrverres.clientid = $client_id)");
		//$this->db->join("empverres","(clients.id = candidates_info.clientid and empverres.clientid = $client_id)");

  		$this->db->where('caserecddate >=',convert_display_to_db_date($where['from_date']));

  		$this->db->where('caserecddate <=',convert_display_to_db_date($where['to_date']));
		   
         // print_r($this->db->get());
        $result = $this->db->get();
      print_r($this->db->last_query());
		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}
  */
	public function report_requested_save($arrdata,$arrwhere = array())
  	{
	    if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update('report_requested', $arrdata);

			record_db_error($this->db->last_query());

			return $result;
	    }
	    else
	    {
			$this->db->insert('report_requested', $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	    }
 	}

 	public function report_requested_select($arrdata)
  	{
	    $this->db->select('id','folder_name')->from('report_requested')->where($arrdata);

	    $result = $this->db->get();

		record_db_error($this->db->last_query());
		
		$result =  $result->result_array();

		if(!empty($result))
		{
			return $result[0];
		}
		else
		{
			return $result;
		}
 	}

 	public function get_entity_package_component($where_arry)
	{
      
       $this->db->select("clients_details.*,(select entity_package_name from entity_package where entity_package.id = clients_details.entity limit 1) as entity_name, (select entity_package_name from entity_package  where entity_package.id= clients_details.package limit 1) as package_name");

		$this->db->from('clients_details');

		
		$this->db->where($where_arry);
		
		$result = $this->db->get();
		
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

		$this->db->limit(1);
		
	    $result = $this->db->get();

		record_db_error($this->db->last_query());

		$res = $result->result_array();

		$r =  @$res[0];
		 return $r;
	
   }


   public function select_check_mail_send_or_not($candidate_id)
   {
     
		$this->db->select('check_mail_send');

		$this->db->from($this->tableName);

		$this->db->where('client_candidates_info.cands_info_id',$candidate_id);

				
	    $result = $this->db->get();

		record_db_error($this->db->last_query());

	    return $result->result_array();

	
    }
     
    public function select_mail_details($where_arry)
    {
     
		$this->db->select('client_candidates_info.*,(select clientname from clients where clients.id = client_candidates_info.clientid) as clientname');

		$this->db->from($this->tableName);

		$this->db->where($where_arry);
	
	    $result = $this->db->get();

		record_db_error($this->db->last_query());

	    return $result->result_array();

    }
    public function select_client_manager_details($where_arry)
    {
        $this->db->select('clientmgr');

		$this->db->from('clients');

		$this->db->where($where_arry);

				
	    $result = $this->db->get();

		record_db_error($this->db->last_query());

	    return $result->result_array();
    }

    public function select_user_info($user_id)
    {
        $this->db->select('email');

		$this->db->from('user_profile');

		$this->db->where('user_profile.id',$user_id);

				
	    $result = $this->db->get();

		record_db_error($this->db->last_query());

	    return $result->result_array();
    }
    
    
    public function update_mail_details($arrdata,$arrwhere)
    {
    
	    if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update($this->tableName, $arrdata);

			record_db_error($this->db->last_query());

			return $result;
	    }
	
    }

    public function get_entity_package_details($arrwhere)
    {
	    if(!empty($arrwhere))
	    {
	    	
	    	$this->db->select("is_entity");

            $this->db->from('entity_package');
      
            $this->db->where_in('entity_package.id',$arrwhere);

            $result  = $this->db->get();
             
            return $result->result_array();

	    }
	
    }

    public function check_candidate_comp($clientid,$entity,$package)
    {
	      	
	    $this->db->select("candidate_add_component");

        $this->db->from('clients_details');
      
        $this->db->where('clients_details.tbl_clients_id',$clientid);

		$this->db->where('clients_details.entity',$entity);

		$this->db->where('clients_details.package',$package);

        $result  = $this->db->get();
          
        return $result->result_array();

	
    }

    public function check_primary_no($mobileno,$where_array)
	{
		$where_condition = "(CandidatesContactNumber = ".$mobileno." or ContactNo1 = ".$mobileno." or ContactNo2 = ".$mobileno.")";

		$this->db->select("cmp_ref_no,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name");

		$this->db->from('candidates_info');

		$this->db->where($where_array);

		$this->db->where($where_condition);

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();

	}

	public function get_client_deatils($candsid)
	{
		$this->db->select("(select candidate_component_count from clients_details where tbl_clients_id = candidates_info.clientid and entity = candidates_info.entity and package = candidates_info.package) as candidate_component_count");

		$this->db->from('candidates_info');

		$this->db->where('candidates_info.id',$candsid);

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_addres_ver_status_list($clientid)
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

	public function get_education_ver_status_list($clientid)
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

	public function get_globdbver_ver_status_list($clientid)
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

	public function get_employment_ver_status_list($clientid)
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

	public function get_court_ver_status_list($where_arry = array())
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

	public function get_refver_ver_status_list($where_arry = array())
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

	public function get_narcver_ver_status_list($where_arry = array())
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

	public function get_identity_ver_status_list($where_arry = array())
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

	public function get_credit_reports_ver_status_list($where_arry = array())
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

	public function get_pcc_ver_status_list($where_arry = array())
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

	public function select_component_details($cands_id){
    
        $this->db->select("candidates_info.id as candidate_id,addrver.id as address_id,empver.id as employment_id,education.id as education_id,reference.id as reference_id,courtver.id as court_id,glodbver.id as global_id,pcc.id as pcc_id,identity.id as identity_id,credit_report.id as credit_id,drug_narcotis.id as drugs_id");

		$this->db->from('candidates_info');

		$this->db->join("addrver",'addrver.candsid = candidates_info.id','left');

		$this->db->join('empver','empver.candsid = candidates_info.id','left');

        $this->db->join("education",'education.candsid = candidates_info.id','left');

		$this->db->join("reference",'reference.candsid = candidates_info.id','left');

		$this->db->join("courtver",'courtver.candsid = candidates_info.id','left');

		$this->db->join("glodbver",'glodbver.candsid = candidates_info.id','left');

		$this->db->join("pcc",'pcc.candsid = candidates_info.id','left');
	    
		$this->db->join("identity",'identity.candsid = candidates_info.id','left');

		$this->db->join("credit_report",'credit_report.candsid = candidates_info.id','left');

	    $this->db->join("drug_narcotis",'drug_narcotis.candsid = candidates_info.id','left');

		if($cands_id)
		{
			$this->db->where('candidates_info.id',$cands_id);
		}
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
   

	}

	    public function get_all_cand_with_search_report($package_name,$client_id,$where,$columns= null)
		{
            $package_name1   = explode(",", $package_name);
			
			
			$this->db->select("candidates_info.id,candidates_info.clientid,candidates_info.CandidateName,candidates_info.caserecddate,candidates_info.Location,candidates_info.Department,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.overallstatus,candidates_info.tat_status_candidate, candidates_info.remarks,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,clients.clientname,clients_details.tat_addrver,clients_details.tat_courtver,clients_details.tat_crimver,clients_details.tat_eduver,clients_details.tat_empver,clients_details.tat_narcver,clients_details.tat_refver,clients_details.tat_globdbver,candidates_info.overallclosuredate,candidates_info.ClientRefNumber AS EmployeeCode,status.status_value");

			$this->db->from('candidates_info');

			$this->db->join("status",'status.id = candidates_info.overallstatus');      

	        
 
	        if($client_id)
	        {
	            $this->db->join("clients","(clients.id = candidates_info.clientid and candidates_info.clientid = $client_id)");
	            $this->db->join("clients_details","(clients_details.tbl_clients_id = candidates_info.clientid and candidates_info.clientid = $client_id)");
	        }
	        else
	        {
	            $this->db->join("clients",'clients.id = candidates_info.clientid');
	            $this->db->join("clients_details",'clients_details.tbl_clients_id = candidates_info.clientid');
	        }

	        $this->db->where("candidates_info.status",1);


	        if($package_name)
	        {
	           $this->db->where_in("candidates_info.package",$package_name1); 
	        }

	        if(isset($where['from_date']) &&  $where['from_date'] != '' && isset($where['todate']) &&  $where['todate'] != '')	
		    {  
              
		     	$from_date  =  $where['from_date'];
	            $todate  =  $where['todate'];
	   
             
		     	$where3 = "DATE_FORMAT(`candidates_info`.`overallclosuredate`,'%Y-%m-%d') BETWEEN '$from_date' AND '$todate'";
                
                $this->db->where($where3); 

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
	        $this->db->limit($where['length'],$where['start']);
	      
			
			$this->db->group_by('candidates_info.id');

	        $result = $this->db->get();
	        $str = $this->db->last_query();
			record_db_error($this->db->last_query());

			return $result->result_array();
		}
        

         public function get_all_cand_with_search_count_report($package_name,$client_id,$where,$columns= null)
		{
            $package_name1   = explode(",", $package_name);
			
			
			$this->db->select("candidates_info.id,candidates_info.clientid,candidates_info.CandidateName,candidates_info.caserecddate,candidates_info.Location,candidates_info.Department,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.overallstatus,candidates_info.tat_status_candidate, candidates_info.remarks,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,clients.clientname,clients_details.tat_addrver,clients_details.tat_courtver,clients_details.tat_crimver,clients_details.tat_eduver,clients_details.tat_empver,clients_details.tat_narcver,clients_details.tat_refver,clients_details.tat_globdbver,candidates_info.overallclosuredate,candidates_info.ClientRefNumber AS EmployeeCode,status.status_value");

			$this->db->from('candidates_info');
		
			
	        $this->db->join("status",'status.id = candidates_info.overallstatus');
 
	        if($client_id) {
	            $this->db->join("clients","(clients.id = candidates_info.clientid and candidates_info.clientid = $client_id)");
	            $this->db->join("clients_details","(clients_details.tbl_clients_id = candidates_info.clientid and candidates_info.clientid = $client_id)");
	        } else {
	            $this->db->join("clients",'clients.id = candidates_info.clientid');
	            $this->db->join("clients_details",'clients_details.tbl_clients_id = candidates_info.clientid');
	        }

	        $this->db->where("candidates_info.status",1);

	        if($package_name) {
	        	$this->db->where_in("candidates_info.package",$package_name1); 
	        }

         	if(isset($where['from_date']) &&  $where['from_date'] != '' && isset($where['todate']) &&  $where['todate'] != '')	
		    {  
              
		     	$from_date  =  $where['from_date'];
	            $todate  =  $where['todate'];
	   
             
		     	$where3 = "DATE_FORMAT(`candidates_info`.`overallclosuredate`,'%Y-%m-%d') BETWEEN '$from_date' AND '$todate'";
                
                $this->db->where($where3); 

		    } 
           

	        if(!empty($where['order'])) {
				$column_name_index = $where['order'][0]['column'];
				$order_by = $where['order'][0]['dir'];
				$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		   	} else {
				$this->db->order_by('candidates_info.id','DESC');
		   	}

		   	$this->db->group_by('candidates_info.id');

	    
	        $result = $this->db->get();
	        $str = $this->db->last_query();
			record_db_error($this->db->last_query());

			return $result->result_array();
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

	    public function user_email_count($arrdata,$arrwhere = array())
	    {
			$result = false;
		    if(!empty($arrwhere))
		    {
				$this->db->where($arrwhere);
				$this->db->set('is_mail_sent', 'is_mail_sent+1', FALSE);
				$this->db->set('last_email_on', "'".date(DB_DATE_FORMAT)."'", FALSE);
				$result = $this->db->update('client_candidates_info', $arrdata);
		    }
		    return $result;
	    }


	    public function user_sms_count($arrdata,$arrwhere = array())
	    {
			$result = false;
		    if(!empty($arrwhere))
		    {
				$this->db->where($arrwhere);
				$this->db->set('is_sms_sent', 'is_sms_sent+1', FALSE);
				$result = $this->db->update('client_candidates_info', $arrdata);
		    }
		    return $result;
	    }
   
        public function get_data_for_export_from_client($package_name,$client_id)
	    {
            $package_name1   = explode(",", $package_name);
			
			$this->db->select("client_candidates_info.id,client_candidates_info.clientid,client_candidates_info.CandidateName,client_candidates_info.cands_info_id,client_candidates_info.created_on,client_candidates_info.caserecddate,client_candidates_info.Location,client_candidates_info.Department,client_candidates_info.ClientRefNumber,client_candidates_info.cmp_ref_no,client_candidates_info.public_key,client_candidates_info.private_key,candidates_info.overallstatus,client_candidates_info.remarks,(select entity_package_name from entity_package where entity_package.id = client_candidates_info.entity limit 1) as entity_name,client_candidates_info.is_mail_sent,client_candidates_info.is_sms_sent,(select entity_package_name from entity_package where entity_package.id = client_candidates_info.package limit 1) as package_name,clients.clientname,clients_details.tat_addrver,clients_details.tat_courtver,clients_details.tat_crimver,clients_details.tat_eduver,clients_details.tat_empver,clients_details.tat_narcver,clients_details.tat_refver,clients_details.tat_globdbver,client_candidates_info.overallclosuredate,client_candidates_info.ClientRefNumber AS EmployeeCode,status.status_value");

			$this->db->from('client_candidates_info');


	        $this->db->join("candidates_info",'candidates_info.id = client_candidates_info.cands_info_id');

	        $this->db->join("status",'status.id = candidates_info.overallstatus');

	        if($client_id)
	        {
	            $this->db->join("clients","(clients.id = client_candidates_info.clientid and client_candidates_info.clientid = $client_id)");
	            $this->db->join("clients_details","(clients_details.tbl_clients_id = client_candidates_info.clientid and client_candidates_info.clientid = $client_id)");
	        }
	        else
	        {
	            $this->db->join("clients",'clients.id = client_candidates_info.clientid');
	            $this->db->join("clients_details",'clients_details.tbl_clients_id = client_candidates_info.clientid');
	        }
            

	        $this->db->where("client_candidates_info.status",1);
		    $this->db->where("client_candidates_info.check_mail_send",1);
           

			if($package_name)
		    {
		        $this->db->where_in("client_candidates_info.package",$package_name1); 
		    }
		       
		    $this->db->order_by('client_candidates_info.id','DESC');
			  

	        $result = $this->db->get();
	        $str = $this->db->last_query();
	        
			record_db_error($this->db->last_query());

			return $result->result_array();
		    
	    }

	    public function select_client_login_email_id($where_arry)
	    {
			$this->db->select("client_login.*");

			$this->db->from('client_login');

			if($where_arry)
			{
				$this->db->where($where_arry);
			}
			
			$result = $this->db->get();

			record_db_error($this->db->last_query());

			return $result->result_array();
     	}


	public function get_all_cand_with_all_status($clientid,$entity_id,$package_id,$fil_by_status,$fil_by_sub_status)
	{
  
		$this->db->select("candidates_info.id,candidates_info.clientid,candidates_info.CandidatesContactNumber,candidates_info.cands_email_id,candidates_info.CandidateName,candidates_info.caserecddate,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.due_date_candidate,candidates_info.Location,clients.clientname,candidates_info.overallclosuredate,status.status_value,candidates_info.overallstatus,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,clients_details.component_id");

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

	public function select_detials($tableName,$selected_array,$where_array)
    {
		$this->db->select($selected_array);

		$this->db->from($tableName);

		if($where_array)
		{
			$this->db->where($where_array);
		}

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function select_candidate_activity_record($where_array = array())
	{
		
		$this->db->select("candidate_activity_record.*,(select user_name from user_profile where user_profile.id = candidate_activity_record.created_by) as create_by");

		$this->db->from('candidate_activity_record');


		if($where_array)
		{
			$this->db->where($where_array);
		}

		$this->db->order_by('candidate_activity_record.id', 'DESC');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();

	}
	public function get_candidate_detail_for_export($where_array = array())
	{
		$this->db->select("client_candidates_info.cands_info_id,client_candidates_info.private_key,client_candidates_info.public_key,candidates_info.caserecddate,candidates_info.cmp_ref_no,(select clientname from clients where clients.id = candidates_info.clientid) as clientname,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,(select status_value from status where status.id = candidates_info.overallstatus limit 1) as status_value,(select activity_status from candidate_activity_record where candidate_activity_record.candidate_id = candidates_info.id order by candidate_activity_record.id desc limit 1) as activity_status,(select created_on from candidate_activity_record where candidate_activity_record.candidate_id = candidates_info.id order by candidate_activity_record.id desc limit 1) as latest_activity_created_on,(select remark from candidate_activity_record where candidate_activity_record.candidate_id = candidates_info.id order by candidate_activity_record.id desc limit 1) as activity_remark,candidates_info.CandidateName,client_candidates_info.public_key,client_candidates_info.private_key,client_candidates_info.is_sms_sent,client_candidates_info.last_sms_on,client_candidates_info.is_mail_sent,client_candidates_info.last_email_on,(select user_name from user_profile where user_profile.id = candidates_info.modified_by) as modified_by,candidates_info.modified_on");

		$this->db->from('client_candidates_info');


		$this->db->join("candidates_info",'candidates_info.id = client_candidates_info.cands_info_id');

	    if(!empty($where_array))
		{
			$this->db->where_in('client_candidates_info.cands_info_id',$where_array);
		}
       
	 	
		$result  = $this->db->get();

		record_db_error($this->db->last_query());
	
		return $result->result_array();

	}

	public function select_client_for_client_cases($tableName,$return_as_strict_row,$select_array)
	{
		

		$this->db->select($select_array);

		$this->db->from($tableName);


        $this->db->join("candidates_info",'candidates_info.clientid = clients.id');

		$this->db->join("client_candidates_info",'client_candidates_info.cands_info_id = candidates_info.id');

	    $this->db->join("status",'status.id = candidates_info.overallstatus');



		$this->db->where("client_candidates_info.status",1);

		$this->db->where("candidates_info.overallstatus",5);

		
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



}
?>