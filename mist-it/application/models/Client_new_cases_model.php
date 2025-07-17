<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Client_new_cases_model extends CI_Model
{
	function __construct()
    {
		$this->tableName = 'client_new_cases';

		$this->primaryKey = 'id';
	}

	public function select_logs($where_array)
	{
		$this->db->select('client_new_cases_log.*,(select user_name from user_profile where  user_profile.id= client_new_cases_log.created_by) as created_by');

		$this->db->from('client_new_cases_log');

		$this->db->where($where_array);

		$this->db->order_by('id', 'desc');

		$result  = $this->db->get();

		return $result->result_array();
	}

	public function select_logs1($where_arry = array())
	{
		

    {   
        $clients = $this->common_model->select('clients',FALSE,array('id','clientname'),$where);

        

        foreach ($clients as $key => $value) 
        {
            $clients_arry[$value['id']] = ucwords(strtolower($value['clientname']));
        }
        return $clients_arry;
    }
		//$this->db->select("case_type");

		//$this->db->from('clients_details');

	    //$this->db->where($where_arry);
		
		//$result = $this->db->get();

		//record_db_error($this->db->last_query());

		//return $result->result_array();
	}

	protected function filter_where_cond($where_arry)
	{
		$where1 = array();
		 //$str_append = "";
       
		if(isset($where_arry['status']) &&  $where_arry['status'] != '')	
		{
			
			$where1['client_new_cases.status'] = $where_arry['status'];

		    //$status     =  $where_arry['status'];
			//$str_append .= "client_new_cases.status = ".$status;
		}

		if(isset($where_arry['sub_status']) &&  $where_arry['sub_status'] != '')	
		{
			$where1['client_new_cases.type'] = $where_arry['sub_status'];
		}

		if(isset($where_arry['client_id']) &&  $where_arry['client_id'] != 0)	
		{
			$where1['client_new_cases.client_id'] = $where_arry['client_id'];
		}

		/*if(isset($where_arry['start_date']) &&  $where_arry['start_date'] != '' && isset($where_arry['end_date']) &&  $where_arry['end_date'] != '')	
		{
            $start_date  =  $where_arry['start_date'];
            $end_date  =  $where_arry['end_date'];
             
            $date1 = str_replace("/", "-", $start_date);
            $date2 = str_replace("/", "-", $end_date);

            $prep_date1 = date('Y-m-d',strtotime($date1));
            $prep_date2 = date('Y-m-d',strtotime($date2));


            //print_r($where1);
          //  $where2 = "client_new_cases.created_on BETWEEN ".$prep_date1." AND ".$prep_date2."";
            //array_push($where1,"AND ".$where1['client_new_cases.client_id']." BETWEEN ".$prep_date1." AND ".$prep_date2."");
           // $where1 = $where1." AND ".$where2; 
          // $where1 = array($where,'client_new_cases.created_on BETWEEN' .$prep_date1. AND ".$prep_date2.");
            $str_append .= "client_new_cases.created_on BETWEEN ".$prep_date1." AND ".$prep_date2."";
			//$where1['clients.id'] = $where_arry['start_end_range'];
			print_r($where1);
		}*/
	
		return $where1;
	}
       

   	public function select_join($where_array = array())
	{
		$this->db->select('client_new_cases.*,(select user_name from user_profile where user_profile.id = client_new_cases.created_by) as created_by,(select user_name from user_profile where user_profile.id = client_new_cases.modified_by) as modified_by,(select clientname from clients where clients.id = client_id) as client_name,(select component from view_vendor_master_log where view_vendor_master_log.id = view_vendor_master_log_id) as component');

		$this->db->from('client_new_cases');

		$this->db->where($where_array);

		//$this ->db->order_by("FIELD ( client_new_cases.status, 'wip', 'hold', 'completed' )");

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	/*public function select_jion($get_employee_id,$where1,$columns)
	{
         
	     $user_id  =  $this->user_info['id'];
	     if(!empty($get_employee_id))
	     {
           $get_employee_id1 =   implode(",", $get_employee_id);
       
           $where = "(`has_case_id` IN (".$get_employee_id1.") or `created_by` IN (".$get_employee_id1.") or `has_case_id` = ".$user_id." or created_by =".$user_id.")"; 
	     }
	     else
	     {
	     	$where = "(`has_case_id` = ".$user_id." or created_by =".$user_id.")";
	     }
          
        
		$this->db->select('client_new_cases.*,(select user_name from user_profile where user_profile.id = client_new_cases.created_by) as created_by,(select user_name from user_profile where user_profile.id = client_new_cases.has_case_id) as executive_name,(select user_name from user_profile where user_profile.id = client_new_cases.modified_by) as modified_by,(select clientname from clients where clients.id = client_id) as client_name');

		$this->db->from('client_new_cases');
        
        if($user_id == "1")
        {
               
        	$this->db->where($this->filter_where_cond($where1));
          	if(isset($where1['start_date']) &&  $where1['start_date'] != '' && isset($where1['end_date']) &&  $where1['end_date'] != '')	
		     {  

		     	$start_date  =  $where1['start_date'];
	            $end_date  =  $where1['end_date'];
	             
	            $date1 = str_replace("/", "-", $start_date);
	            $date2 = str_replace("/", "-", $end_date);

	            $prep_date1 = date('Y-m-d',strtotime($date1));
	            $prep_date2 = date('Y-m-d',strtotime($date2));
	            
             
		     	$where3 = "DATE_FORMAT(`client_new_cases`.`created_on`,'%Y-%m-%d') BETWEEN '$prep_date1' AND '$prep_date2'";
                

                $this->db->where($where3); 

		     } 

        }
        else
        {
        	$this->db->where($where);
        	$this->db->where($this->filter_where_cond($where1));
        	if(isset($where1['start_date']) &&  $where1['start_date'] != '' && isset($where1['end_date']) &&  $where1['end_date'] != '')	
		     {  

		     	$start_date  =  $where1['start_date'];
	            $end_date  =  $where1['end_date'];
	             
	            $date1 = str_replace("/", "-", $start_date);
	            $date2 = str_replace("/", "-", $end_date);

	            $prep_date1 = date('Y-m-d',strtotime($date1));
	            $prep_date2 = date('Y-m-d',strtotime($date2));

		     	$where3 = "DATE_FORMAT(`client_new_cases`.`created_on`,'%Y-%m-%d') BETWEEN '$prep_date1' AND '$prep_date2'";
                 
                $this->db->where($where3); 

		     }

        }

        if(is_array($where1) && $where1['search']['value'] != "")
		{

			$this->db->or_like('client_new_cases.total_cases', $where1['search']['value']);

			$this->db->or_like('client_new_cases.status', $where1['search']['value']);

			$this->db->or_like('client_new_cases.type', $where1['search']['value']);
		}

      
        $this->db->limit($where1['length'],$where1['start']);

		$this ->db->order_by("FIELD ( client_new_cases.status, 'wip', 'hold', 'completed' )");
         

		$result  = $this->db->get();
         // print_r($this->db->last_query());  
		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function select_jion_count($get_employee_id,$where1,$columns)
	{
         
	     $user_id  =  $this->user_info['id'];
	     if(!empty($get_employee_id))
	     {
           $get_employee_id1 =   implode(",", $get_employee_id);
       
           $where = "(`has_case_id` IN (".$get_employee_id1.") or `created_by` IN (".$get_employee_id1.") or `has_case_id` = ".$user_id." or created_by =".$user_id.")"; 
	     }
	     else
	     {
	     	$where = "(`has_case_id` = ".$user_id." or created_by =".$user_id.")";
	     }
          
        
		$this->db->select('client_new_cases.*,(select user_name from user_profile where user_profile.id = client_new_cases.created_by) as created_by,(select user_name from user_profile where user_profile.id = client_new_cases.has_case_id) as executive_name,(select user_name from user_profile where user_profile.id = client_new_cases.modified_by) as modified_by,(select clientname from clients where clients.id = client_id) as client_name');

		$this->db->from('client_new_cases');
        
        if($user_id == "1")
        {
        	 $this->db->where($this->filter_where_cond($where1));
        	if(isset($where1['start_date']) &&  $where1['start_date'] != '' && isset($where1['end_date']) &&  $where1['end_date'] != '')	
		     {  

		     	$start_date  =  $where1['start_date'];
	            $end_date  =  $where1['end_date'];
	             
	            $date1 = str_replace("/", "-", $start_date);
	            $date2 = str_replace("/", "-", $end_date);

	            $prep_date1 = date('Y-m-d',strtotime($date1));
	            $prep_date2 = date('Y-m-d',strtotime($date2));

		     	$where3 = "DATE_FORMAT(`client_new_cases`.`created_on`,'%Y-%m-%d') BETWEEN '$prep_date1' AND '$prep_date2'";
                 
                $this->db->where($where3); 

		     }
        }
        else
        {
        	$this->db->where($where);
        	$this->db->where($this->filter_where_cond($where1));
            if(isset($where1['start_date']) &&  $where1['start_date'] != '' && isset($where1['end_date']) &&  $where1['end_date'] != '')	
		     {  

		     	$start_date  =  $where1['start_date'];
	            $end_date  =  $where1['end_date'];
	             
	            $date1 = str_replace("/", "-", $start_date);
	            $date2 = str_replace("/", "-", $end_date);

	            $prep_date1 = date('Y-m-d',strtotime($date1));
	            $prep_date2 = date('Y-m-d',strtotime($date2));

		     	$where3 = "DATE_FORMAT(`client_new_cases`.`created_on`,'%Y-%m-%d') BETWEEN '$prep_date1' AND '$prep_date2'";
                 
                $this->db->where($where3); 

		     }
        }

         if(is_array($where1) && $where1['search']['value'] != "")
		{
			

			$this->db->or_like('client_new_cases.total_cases', $where1['search']['value']);

			$this->db->or_like('client_new_cases.status', $where1['search']['value']);

			$this->db->or_like('client_new_cases.type', $where1['search']['value']);
		}
      

		$this ->db->order_by("FIELD ( client_new_cases.status, 'wip', 'hold', 'completed' )");

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}*/

	public function select_jion($where,$columns)
	{
	

	    $this->db->select("client_new_cases.*,clients.clientname,(select user_name from user_profile where user_profile.id = client_new_cases.created_by) as created_by");

		$this->db->from('client_new_cases');
		
		$this->db->join("clients",'clients.id = client_new_cases.client_id');
        
        $this->db->where($this->filter_where_cond($where));

		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like('clients.clientname', $where['search']['value']);

			$this->db->or_like('client_new_cases.status', $where['search']['value']);

			$this->db->or_like('client_new_cases.remarks', $where['search']['value']);

		
		}

		if(!empty($where['order']))
		{

			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir'];

       
			$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		}
		else
		{
            $this->db->order_by('client_new_cases.id','DESC');
		   
		}

		$this->db->limit($where['length'],$where['start']);

		$result = $this->db->get();
	
		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function select_jion_count($where,$columns)
	{
		$this->db->select("client_new_cases.*,clients.clientname,(select user_name from user_profile where user_profile.id = client_new_cases.created_by) as created_by");

		$this->db->from('client_new_cases');
		
		$this->db->join("clients",'clients.id = client_new_cases.client_id');
		
        $this->db->where($this->filter_where_cond($where));

		if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->like('clients.clientname', $where['search']['value']);

			$this->db->or_like('client_new_cases.status', $where['search']['value']);

			$this->db->or_like('client_new_cases.remarks', $where['search']['value']);

		}

		if(!empty($where['order']))
		{

			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir'];
            
			$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		}
		else
		{
			$this->db->order_by('client_new_cases.id','DESC');
		}

				
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function select_task_person_id($where_array)
	{
		$where_array = explode(',',$where_array);
		$this->db->select('user_name');

		$this->db->from('user_profile');
     
		$this->db->where_in('id',$where_array);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();

	}
   
	public function select_client_new_cases($where_array)
	{
		
		$this->db->select('client_new_cases.*');

		$this->db->from('client_new_cases');
     
		$this->db->where($where_array);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();

	}

	public function  select_pre_post_details($id)
	{
        $where_array = "pre_post_details.task_manager_id = ".$id." or pre_post_details.task_manager_id_post = ".$id;       
 
		$this->db->select('pre_post_details.*,(select clientname from clients where clients.id = pre_post_details.client_id limit 1) as client_name,(select entity_package_name from entity_package where entity_package.id = pre_post_details.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = pre_post_details.package limit 1) as package_name');

		$this->db->from('pre_post_details');
     
		$this->db->where($where_array);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();

		
	}

	public function check_reference_no($where_array)
	{

		$this->db->select("cmp_ref_no");

		$this->db->from('candidates_info');

		$this->db->where($where_array);


		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();

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

	public function save1($arrdata,$arrwhere = array())
	{   


	    if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update($this->tableName, $arrdata);

			record_db_error($this->db->last_query());

			return $result;
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

	public function delete($arrwhere)
	{
	  $result =  $this->db->delete($this->tableName, $arrwhere);

	  record_db_error($this->db->last_query());
	  
	  return $result;
	}
    

   public function get_assign_users_id($tableName, $return_as_strict_row,$select_array, $where_array=array())
	{

		$this->db->select($select_array);

		$this->db->from($tableName);

	
        $this->db->where($where_array); 

        
        $result_array = $this->db->get()->result_array();	
         
        if($return_as_strict_row)
		{
            if(count($result_array) >0 ) // ensure only one record has been previously inserted
            {
                $result_array = $result_array[0];
            }
        }

    
        return convert_to_single_dimension_array($result_array,'id','fullname');
	}
    
    public function get_employee_under()
	{
         
        $where = "`reporting_manager` IN (select ".$this->user_info['id']." from user_profile)"; 

		$this->db->select("id");

		$this->db->from('user_profile');
        
        $this->db->where($where);
      
      

		$result  = $this->db->get()->result_array();

		record_db_error($this->db->last_query());
		
	
		 return convert_to_single_dimension_array1($result,'id','id');
	}

	public function select_file($select_array,$where_array)
	{
		$this->db->select($select_array);

		$this->db->from('client_new_case_file');

		$this->db->where($where_array);

		$this->db->order_by('id', 'ASC');
		
		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}
	

	public function select_file1($where_array)
	{
		$this->db->select('client_new_cases.id as client_new_case_id,client_new_cases.view_vendor_master_log_id as 	client_vendor_master_log_id,view_vendor_master_log_file.file_name,view_vendor_master_log_file.real_filename');

		$this->db->from('client_new_cases');

		$this->db->join('view_vendor_master_log','view_vendor_master_log.id = client_new_cases.view_vendor_master_log_id');

		$this->db->join('view_vendor_master_log_file','view_vendor_master_log_file.view_venor_master_log_id = view_vendor_master_log.id');


		$this->db->where($where_array);


		
		$result  = $this->db->get();
    
		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}
   
    public function select_file_address($select_array,$where_array)
	{
		$this->db->select($select_array);

		$this->db->from('addrver_files');

		$this->db->where($where_array);

		$this->db->order_by('id', 'ASC');
		
		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function select_file_employment($select_array,$where_array)
	{
		$this->db->select($select_array);

		$this->db->from('empverres_files');

		$this->db->where($where_array);

		$this->db->order_by('id', 'ASC');
		
		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function select_file_from_vendor($select_array,$where_array)
	{
		$this->db->select($select_array);

		$this->db->from('view_vendor_master_log_file');

		$this->db->where($where_array);

		
		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function select_file_education($select_array,$where_array)
	{
		$this->db->select($select_array);

		$this->db->from('education_files');

		$this->db->where($where_array);

		$this->db->order_by('id', 'ASC');
		
		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}


	public function select_file_court($select_array,$where_array)
	{
		$this->db->select($select_array);

		$this->db->from('courtver_files');

		$this->db->where($where_array);

		$this->db->order_by('id', 'ASC');
		
		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function select_file_global($select_array,$where_array)
	{
		$this->db->select($select_array);

		$this->db->from('glodbver_files');

		$this->db->where($where_array);

		$this->db->order_by('id', 'ASC');
		
		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function select_file_pcc($select_array,$where_array)
	{
		$this->db->select($select_array);

		$this->db->from('pcc_files');

		$this->db->where($where_array);

		$this->db->order_by('id', 'ASC');
		
		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}


    public function select_file_identity($select_array,$where_array)
	{
		$this->db->select($select_array);

		$this->db->from('identity_files');

		$this->db->where($where_array);

		$this->db->order_by('id', 'ASC');
		
		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}
    

    public function select_file_credit_report($select_array,$where_array)
	{
		$this->db->select($select_array);

		$this->db->from('credit_report_files');

		$this->db->where($where_array);

		$this->db->order_by('id', 'ASC');
		
		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}


	public function get_address_details($where_array)
	{
		$this->db->select('client_new_cases.id as client_new_case_id,client_new_cases.view_vendor_master_log_id as 	client_vendor_master_log_id,addrver.id as addrver_id,addrver.candsid,candidates_info.ClientRefNumber,(select `id` from `addrverres` where `addrverres`.`addrverid` = `addrver`.`id` ORDER by `id` desc limit 1) as addrverres_id,addrver.clientid,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.CandidateName,(select clientname from clients where clients.id = addrver.clientid limit 1) as clientname,(select user_name from user_profile where user_profile.id = addrver.has_case_id) as executive_name, (select `id` from `addrverres` where `addrverres`.`addrverid` = `addrver`.`id` ORDER by `id` desc limit 1) as addrverres_id,(select `verfstatus` from `addrverres` where `addrverres`.`addrverid` = `addrver`.`id` ORDER by `id` desc limit 1) as verfstatus1,(SELECT status_value FROM status WHERE status.id = verfstatus1 ORDER BY id DESC LIMIT 1) verfstatus,(select `res_stay_from` from `addrverres` where `addrverres`.`addrverid` = `addrver`.`id` ORDER by `id` desc limit 1) as res_stay_from,(select `res_address` from `addrverres` where `addrverres`.`addrverid` = `addrver`.`id` ORDER by `id` desc limit 1) as res_address,(select `res_stay_from` from `addrverres` where `addrverres`.`addrverid` = `addrver`.`id` ORDER by `id` desc limit 1) as res_stay_from,(select `res_stay_to` from `addrverres` where `addrverres`.`addrverid` = `addrver`.`id` ORDER by `id` desc limit 1) as res_stay_to,(select `neighbour_1` from `addrverres` where `addrverres`.`addrverid` = `addrver`.`id` ORDER by `id` desc limit 1) as neighbour_1,(select `neighbour_details_1` from `addrverres` where `addrverres`.`addrverid` = `addrver`.`id` ORDER by `id` desc limit 1) as neighbour_details_1,(select `neighbour_2` from `addrverres` where `addrverres`.`addrverid` = `addrver`.`id` ORDER by `id` desc limit 1) as neighbour_2,(select `neighbour_details_2` from `addrverres` where `addrverres`.`addrverid` = `addrver`.`id` ORDER by `id` desc limit 1) as neighbour_details_2,(select `mode_of_verification` from `addrverres` where `addrverres`.`addrverid` = `addrver`.`id` ORDER by `id` desc limit 1) as mode_of_verification,(select `resident_status` from `addrverres` where `addrverres`.`addrverid` = `addrver`.`id` ORDER by `id` desc limit 1) as resident_status,(select `landmark` from `addrverres` where `addrverres`.`addrverid` = `addrver`.`id` ORDER by `id` desc limit 1) as landmark,(select `verified_by` from `addrverres` where `addrverres`.`addrverid` = `addrver`.`id` ORDER by `id` desc limit 1) as verified_by,(select `addr_proof_collected` from `addrverres` where `addrverres`.`addrverid` = `addrver`.`id` ORDER by `id` desc limit 1) as addr_proof_collected,(select `remarks` from `addrverres` where `addrverres`.`addrverid` = `addrver`.`id` ORDER by `id` desc limit 1) as remarks,(select `first_qc_approve` from `addrverres` where `addrverres`.`addrverid` = `addrver`.`id` ORDER by `id` desc limit 1) as first_qc_approve,(select `res_city` from `addrverres` where `addrverres`.`addrverid` = `addrver`.`id` ORDER by `id` desc limit 1) as res_city,(select `res_pincode` from `addrverres` where `addrverres`.`addrverid` = `addrver`.`id` ORDER by `id` desc limit 1) as res_pincode,(select `res_state` from `addrverres` where `addrverres`.`addrverid` = `addrver`.`id` ORDER by `id` desc limit 1) as res_state,(select `closuredate` from `addrverres` where `addrverres`.`addrverid` = `addrver`.`id` ORDER by `id` desc limit 1) as closuredate,due_date,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,addrver.iniated_date,candidates_info.caserecddate,add_com_ref');

		$this->db->from('client_new_cases');

		$this->db->join('view_vendor_master_log','view_vendor_master_log.id = client_new_cases.view_vendor_master_log_id');
       
        $this->db->join('address_vendor_log','address_vendor_log.id = view_vendor_master_log.case_id');

        $this->db->join('addrver','addrver.id = address_vendor_log.case_id');
       
        $this->db->join("candidates_info",'candidates_info.id = addrver.candsid');
 
		$this->db->where($where_array);


		
		$result  = $this->db->get();
   // print_r($this->db->last_query());
		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}


	public function get_employment_details($where_array)
	{
		$this->db->select('client_new_cases.id as client_new_case_id,client_new_cases.view_vendor_master_log_id as 	client_vendor_master_log_id,empver.id as empver_id,empver.candsid,candidates_info.ClientRefNumber,(select `id` from `empverres` where `empverres`.`empverid` = `empver`.`id` ORDER by `id` desc limit 1) as empverres_id,empver.clientid,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.CandidateName,(select clientname from clients where clients.id = empver.clientid limit 1) as clientname,(select user_name from user_profile where user_profile.id = empver.has_case_id) as executive_name, (select `id` from `empverres` where `empverres`.`empverid` = `empver`.`id` ORDER by `id` desc limit 1) as empverres_id,(select `verfstatus` from `empverres` where `empverres`.`empverid` = `empver`.`id` ORDER by `id` desc limit 1) as verfstatus1,(SELECT status_value FROM status WHERE status.id = verfstatus1 ORDER BY id DESC LIMIT 1) verfstatus,(select `res_deputed_company` from `empverres` where `empverres`.`empverid` = `empver`.`id` ORDER by `id` desc limit 1) as res_deputed_company,(select `res_nameofthecompany` from `empverres` where `empverres`.`empverid` = `empver`.`id` ORDER by `id` desc limit 1) as res_nameofthecompany,(select `res_employment_type` from `empverres` where `empverres`.`empverid` = `empver`.`id` ORDER by `id` desc limit 1) as res_employment_type,(select `res_reasonforleaving` from `empverres` where `empverres`.`empverid` = `empver`.`id` ORDER by `id` desc limit 1) as res_reasonforleaving,(select `verifiers_role` from `empverres` where `empverres`.`empverid` = `empver`.`id` ORDER by `id` desc limit 1) as neighbour_1,(select `verfname` from `empverres` where `empverres`.`empverid` = `empver`.`id` ORDER by `id` desc limit 1) as verfname,(select `verfdesgn` from `empverres` where `empverres`.`empverid` = `empver`.`id` ORDER by `id` desc limit 1) as verfdesgn,(select `verifiers_contact_no` from `empverres` where `empverres`.`empverid` = `empver`.`id` ORDER by `id` desc limit 1) as verifiers_contact_no,(select `modeofverification` from `empverres` where `empverres`.`empverid` = `empver`.`id` ORDER by `id` desc limit 1) as modeofverification,(select `verifiers_email_id` from `empverres` where `empverres`.`empverid` = `empver`.`id` ORDER by `id` desc limit 1) as verifiers_email_id,(select `remarks` from `empverres` where `empverres`.`empverid` = `empver`.`id` ORDER by `id` desc limit 1) as remarks,(select `verfdesgn` from `empverres` where `empverres`.`empverid` = `empver`.`id` ORDER by `id` desc limit 1) as verfdesgn,(select `fmlyowned` from `empverres` where `empverres`.`empverid` = `empver`.`id` ORDER by `id` desc limit 1) as fmlyowned,(select `remarks` from `empverres` where `empverres`.`empverid` = `empver`.`id` ORDER by `id` desc limit 1) as remarks,(select `first_qc_approve` from `empverres` where `empverres`.`empverid` = `empver`.`id` ORDER by `id` desc limit 1) as first_qc_approve,(select `res_remuneration` from `empverres` where `empverres`.`empverid` = `empver`.`id` ORDER by `id` desc limit 1) as res_remuneration,(select `emp_designation` from `empverres` where `empverres`.`empverid` = `empver`.`id` ORDER by `id` desc limit 1) as emp_designation,(select `res_empid` from `empverres` where `empverres`.`empverid` = `empver`.`id` ORDER by `id` desc limit 1) as res_empid,(select `closuredate` from `empverres` where `empverres`.`empverid` = `empver`.`id` ORDER by `id` desc limit 1) as closuredate,due_date,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,empver.iniated_date,candidates_info.caserecddate,emp_com_ref');

		$this->db->from('client_new_cases');

		$this->db->join('view_vendor_master_log','view_vendor_master_log.id = client_new_cases.view_vendor_master_log_id');
       
        $this->db->join('employment_vendor_log','employment_vendor_log.id = view_vendor_master_log.case_id');

        $this->db->join('empver','empver.id = employment_vendor_log.case_id');
       
        $this->db->join("candidates_info",'candidates_info.id = empver.candsid');
 
		$this->db->where($where_array);


		
		$result  = $this->db->get();
   // print_r($this->db->last_query());
		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}
   


	public function get_education_details($where_array)
	{
		$this->db->select('client_new_cases.id as client_new_case_id,client_new_cases.view_vendor_master_log_id as 	client_vendor_master_log_id,education.id as education_id,education.candsid,candidates_info.ClientRefNumber,(select `id` from `education_result` where `education_result`.`education_id` = `education`.`id` ORDER by `id` desc limit 1) as education_result_id,education.clientid,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.CandidateName,(select clientname from clients where clients.id = education.clientid limit 1) as clientname,(select user_name from user_profile where user_profile.id = education.has_case_id) as executive_name, (select `id` from `education_result` where `education_result`.`education_id` = `education`.`id` ORDER by `id` desc limit 1) as education_result_id,(select `verfstatus` from `education_result` where `education_result`.`education_id` = `education`.`id` ORDER by `id` desc limit 1) as verfstatus1,(SELECT status_value FROM status WHERE status.id = verfstatus1 ORDER BY id DESC LIMIT 1) verfstatus,(select `res_qualification` from `education_result` where `education_result`.`education_id` = `education`.`id` ORDER by `id` desc limit 1) as res_qualification,(select `res_school_college` from `education_result` where `education_result`.`education_id` = `education`.`id` ORDER by `id` desc limit 1) as res_school_college,(select `res_university_board` from `education_result` where `education_result`.`education_id` = `education`.`id` ORDER by `id` desc limit 1) as res_university_board,(select `res_major` from `education_result` where `education_result`.`education_id` = `education`.`id` ORDER by `id` desc limit 1) as res_major,(select `res_month_of_passing` from `education_result` where `education_result`.`education_id` = `education`.`id` ORDER by `id` desc limit 1) as res_month_of_passing,(select `res_year_of_passing` from `education_result` where `education_result`.`education_id` = `education`.`id` ORDER by `id` desc limit 1) as res_year_of_passing,(select `res_grade_class_marks` from `education_result` where `education_result`.`education_id` = `education`.`id` ORDER by `id` desc limit 1) as res_grade_class_marks,(select `res_course_start_date` from `education_result` where `education_result`.`education_id` = `education`.`id` ORDER by `id` desc limit 1) as res_course_start_date,(select `res_course_end_date` from `education_result` where `education_result`.`education_id` = `education`.`id` ORDER by `id` desc limit 1) as res_course_end_date,(select `res_mode_of_verification` from `education_result` where `education_result`.`education_id` = `education`.`id` ORDER by `id` desc limit 1) as res_mode_of_verification,(select `res_roll_no` from `education_result` where `education_result`.`education_id` = `education`.`id` ORDER by `id` desc limit 1) as res_roll_no,(select `res_enrollment_no` from `education_result` where `education_result`.`education_id` = `education`.`id` ORDER by `id` desc limit 1) as res_enrollment_no,(select `res_PRN_no` from `education_result` where `education_result`.`education_id` = `education`.`id` ORDER by `id` desc limit 1) as res_PRN_no,(select `res_online_URL` from `education_result` where `education_result`.`education_id` = `education`.`id` ORDER by `id` desc limit 1) as res_online_URL,(select `first_qc_approve` from `education_result` where `education_result`.`education_id` = `education`.`id` ORDER by `id` desc limit 1) as first_qc_approve,(select `closuredate` from `education_result` where `education_result`.`education_id` = `education`.`id` ORDER by `id` desc limit 1) as closuredate,due_date,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,education.iniated_date,candidates_info.caserecddate,education_com_ref');

		$this->db->from('client_new_cases');

		$this->db->join('view_vendor_master_log','view_vendor_master_log.id = client_new_cases.view_vendor_master_log_id');
       
        $this->db->join('education_vendor_log','education_vendor_log.id = view_vendor_master_log.case_id');

        $this->db->join('education','education.id = education_vendor_log.case_id');
       
        $this->db->join("candidates_info",'candidates_info.id = education.candsid');
 
		$this->db->where($where_array);


		
		$result  = $this->db->get();
   // print_r($this->db->last_query());
		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}
   

   public function get_court_details($where_array)
	{

		$this->db->select('client_new_cases.id as client_new_case_id,client_new_cases.view_vendor_master_log_id as 	client_vendor_master_log_id,courtver.id as court_id,courtver.candsid,candidates_info.ClientRefNumber,(select `id` from `courtver_result` where `courtver_result`.`courtver_id` = `courtver`.`id` ORDER by `id` desc limit 1) as courtver_result_id,courtver.clientid,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.CandidateName,(select clientname from clients where clients.id = courtver.clientid limit 1) as clientname,(select user_name from user_profile where user_profile.id = courtver.has_case_id) as executive_name, (select `id` from `courtver_result` where `courtver_result`.`courtver_id` = `courtver`.`id` ORDER by `id` desc limit 1) as courtver_result_id,(select `verfstatus` from `courtver_result` where `courtver_result`.`courtver_id` = `courtver`.`id` ORDER by `id` desc limit 1) as verfstatus1,(SELECT status_value FROM status WHERE status.id = verfstatus1 ORDER BY id DESC LIMIT 1) verfstatus,(select `mode_of_verification` from `courtver_result` where `courtver_result`.`courtver_id` = `courtver`.`id` ORDER by `id` desc limit 1) as mode_of_verification,(select `verified_date` from `courtver_result` where `courtver_result`.`courtver_id` = `courtver`.`id` ORDER by `id` desc limit 1) as verified_date,(select `advocate_name` from `courtver_result` where `courtver_result`.`courtver_id` = `courtver`.`id` ORDER by `id` desc limit 1) as advocate_name,(select `remarks` from `courtver_result` where `courtver_result`.`courtver_id` = `courtver`.`id` ORDER by `id` desc limit 1) as remarks,(select `first_qc_approve` from `courtver_result` where `courtver_result`.`courtver_id` = `courtver`.`id` ORDER by `id` desc limit 1) as first_qc_approve,(select `closuredate` from `courtver_result` where `courtver_result`.`courtver_id` = `courtver`.`id` ORDER by `id` desc limit 1) as closuredate,due_date,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,courtver.iniated_date,candidates_info.caserecddate,court_com_ref');

		$this->db->from('client_new_cases');

		$this->db->join('view_vendor_master_log','view_vendor_master_log.id = client_new_cases.view_vendor_master_log_id');
       
        $this->db->join('courtver_vendor_log','courtver_vendor_log.id = view_vendor_master_log.case_id');

        $this->db->join('courtver','courtver.id = courtver_vendor_log.case_id');
       
        $this->db->join("candidates_info",'candidates_info.id = courtver.candsid');
 
		$this->db->where($where_array);


		
		$result  = $this->db->get();
   // print_r($this->db->last_query());
		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}
  

   public function get_global_database_details($where_array)
	{

		$this->db->select('client_new_cases.id as client_new_case_id,client_new_cases.view_vendor_master_log_id as 	client_vendor_master_log_id,glodbver.id as global_database_id,glodbver.candsid,candidates_info.ClientRefNumber,(select `id` from `glodbver_result` where `glodbver_result`.`glodbver_id` = `glodbver`.`id` ORDER by `id` desc limit 1) as glodbver_result_id,glodbver.clientid,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.CandidateName,(select clientname from clients where clients.id = glodbver.clientid limit 1) as clientname,(select user_name from user_profile where user_profile.id = glodbver.has_case_id) as executive_name, (select `id` from `glodbver_result` where `glodbver_result`.`glodbver_id` = `glodbver`.`id` ORDER by `id` desc limit 1) as glodbver_result_id,(select `verfstatus` from `glodbver_result` where `glodbver_result`.`glodbver_id` = `glodbver`.`id` ORDER by `id` desc limit 1) as verfstatus1,(SELECT status_value FROM status WHERE status.id = verfstatus1 ORDER BY id DESC LIMIT 1) verfstatus,(select `mode_of_verification` from `glodbver_result` where `glodbver_result`.`glodbver_id` = `glodbver`.`id` ORDER by `id` desc limit 1) as mode_of_verification,(select `verified_date` from `glodbver_result` where `glodbver_result`.`glodbver_id` = `glodbver`.`id` ORDER by `id` desc limit 1) as verified_date,(select `remarks` from `glodbver_result` where `glodbver_result`.`glodbver_id` = `glodbver`.`id` ORDER by `id` desc limit 1) as remarks,(select `first_qc_approve` from `glodbver_result` where `glodbver_result`.`glodbver_id` = `glodbver`.`id` ORDER by `id` desc limit 1) as first_qc_approve,(select `closuredate` from `glodbver_result` where `glodbver_result`.`glodbver_id` = `glodbver`.`id` ORDER by `id` desc limit 1) as closuredate,due_date,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,glodbver.iniated_date,candidates_info.caserecddate,global_com_ref');

		$this->db->from('client_new_cases');

		$this->db->join('view_vendor_master_log','view_vendor_master_log.id = client_new_cases.view_vendor_master_log_id');
       
        $this->db->join('glodbver_vendor_log','glodbver_vendor_log.id = view_vendor_master_log.case_id');

        $this->db->join('glodbver','glodbver.id = glodbver_vendor_log.case_id');
       
        $this->db->join("candidates_info",'candidates_info.id = glodbver.candsid');
 
		$this->db->where($where_array);


		
		$result  = $this->db->get();
   // print_r($this->db->last_query());
		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}
    

    public function get_pcc_details($where_array)
	{

		$this->db->select('client_new_cases.id as client_new_case_id,client_new_cases.view_vendor_master_log_id as 	client_vendor_master_log_id,pcc.id as pcc_id,pcc.candsid,candidates_info.ClientRefNumber,(select `id` from `pcc_result` where `pcc_result`.`pcc_id` = `pcc`.`id` ORDER by `id` desc limit 1) as pcc_result_id,pcc.clientid,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.CandidateName,(select clientname from clients where clients.id = pcc.clientid limit 1) as clientname,(select user_name from user_profile where user_profile.id = pcc.has_case_id) as executive_name, (select `id` from `pcc_result` where `pcc_result`.`pcc_id` = `pcc`.`id` ORDER by `id` desc limit 1) as pcc_result_id,(select `verfstatus` from `pcc_result` where `pcc_result`.`pcc_id` = `pcc`.`id` ORDER by `id` desc limit 1) as verfstatus1,(SELECT status_value FROM status WHERE status.id = verfstatus1 ORDER BY id DESC LIMIT 1) verfstatus,(select `mode_of_verification` from `pcc_result` where `pcc_result`.`pcc_id` = `pcc`.`id` ORDER by `id` desc limit 1) as mode_of_verification,(select `application_id_ref` from `pcc_result` where `pcc_result`.`pcc_id` = `pcc`.`id` ORDER by `id` desc limit 1) as application_id_ref,(select `remarks` from `pcc_result` where `pcc_result`.`pcc_id` = `pcc`.`id` ORDER by `id` desc limit 1) as remarks,(select `first_qc_approve` from `pcc_result` where `pcc_result`.`pcc_id` = `pcc`.`id` ORDER by `id` desc limit 1) as first_qc_approve,(select `closuredate` from `pcc_result` where `pcc_result`.`pcc_id` = `pcc`.`id` ORDER by `id` desc limit 1) as closuredate,due_date,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,pcc.iniated_date,candidates_info.caserecddate,pcc_com_ref');

		$this->db->from('client_new_cases');

		$this->db->join('view_vendor_master_log','view_vendor_master_log.id = client_new_cases.view_vendor_master_log_id');
       
        $this->db->join('pcc_vendor_log','pcc_vendor_log.id = view_vendor_master_log.case_id');

        $this->db->join('pcc','pcc.id = pcc_vendor_log.case_id');
       
        $this->db->join("candidates_info",'candidates_info.id = pcc.candsid');
 
		$this->db->where($where_array);


		
		$result  = $this->db->get();
   // print_r($this->db->last_query());
		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_identity_details($where_array)
	{

		$this->db->select('client_new_cases.id as client_new_case_id,client_new_cases.view_vendor_master_log_id as 	client_vendor_master_log_id,identity.id as identity_id,identity.candsid,candidates_info.ClientRefNumber,(select `id` from `identity_result` where `identity_result`.`identity_id` = `identity`.`id` ORDER by `id` desc limit 1) as identity_result_id,identity.clientid,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.CandidateName,(select clientname from clients where clients.id = identity.clientid limit 1) as clientname,(select user_name from user_profile where user_profile.id = identity.has_case_id) as executive_name, (select `id` from `identity_result` where `identity_result`.`identity_id` = `identity`.`id` ORDER by `id` desc limit 1) as identity_result_id,(select `verfstatus` from `identity_result` where `identity_result`.`identity_id` = `identity`.`id` ORDER by `id` desc limit 1) as verfstatus1,(SELECT status_value FROM status WHERE status.id = verfstatus1 ORDER BY id DESC LIMIT 1) verfstatus,(select `mode_of_verification` from `identity_result` where `identity_result`.`identity_id` = `identity`.`id` ORDER by `id` desc limit 1) as mode_of_verification,(select `remarks` from `identity_result` where `identity_result`.`identity_id` = `identity`.`id` ORDER by `id` desc limit 1) as remarks,(select `first_qc_approve` from `identity_result` where `identity_result`.`identity_id` = `identity`.`id` ORDER by `id` desc limit 1) as first_qc_approve,(select `closuredate` from `identity_result` where `identity_result`.`identity_id` = `identity`.`id` ORDER by `id` desc limit 1) as closuredate,due_date,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,identity.iniated_date,candidates_info.caserecddate,identity_com_ref');

		$this->db->from('client_new_cases');

		$this->db->join('view_vendor_master_log','view_vendor_master_log.id = client_new_cases.view_vendor_master_log_id');
       
        $this->db->join('identity_vendor_log','identity_vendor_log.id = view_vendor_master_log.case_id');

        $this->db->join('identity','identity.id = identity_vendor_log.case_id');
       
        $this->db->join("candidates_info",'candidates_info.id = identity.candsid');
 
		$this->db->where($where_array);


		
		$result  = $this->db->get();
   // print_r($this->db->last_query());
		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_credit_report_details($where_array)
	{

		$this->db->select('client_new_cases.id as client_new_case_id,client_new_cases.view_vendor_master_log_id as 	client_vendor_master_log_id,credit_report.id as credit_report_id,credit_report.candsid,candidates_info.ClientRefNumber,(select `id` from `credit_report_result` where `credit_report_result`.`credit_report_id` = `credit_report`.`id` ORDER by `id` desc limit 1) as credit_report_result_id,credit_report.clientid,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.CandidateName,(select clientname from clients where clients.id = credit_report.clientid limit 1) as clientname,(select user_name from user_profile where user_profile.id = credit_report.has_case_id) as executive_name, (select `id` from `credit_report_result` where `credit_report_result`.`credit_report_id` = `credit_report`.`id` ORDER by `id` desc limit 1) as credit_report_result_id,(select `verfstatus` from `credit_report_result` where `credit_report_result`.`credit_report_id` = `credit_report`.`id` ORDER by `id` desc limit 1) as verfstatus1,(SELECT status_value FROM status WHERE status.id = verfstatus1 ORDER BY id DESC LIMIT 1) verfstatus,(select `mode_of_verification` from `credit_report_result` where `credit_report_result`.`credit_report_id` = `credit_report`.`id` ORDER by `id` desc limit 1) as mode_of_verification,(select `remarks` from `credit_report_result` where `credit_report_result`.`credit_report_id` = `credit_report`.`id` ORDER by `id` desc limit 1) as remarks,(select `first_qc_approve` from `credit_report_result` where `credit_report_result`.`credit_report_id` = `credit_report`.`id` ORDER by `id` desc limit 1) as first_qc_approve,(select `closuredate` from `credit_report_result` where `credit_report_result`.`credit_report_id` = `credit_report`.`id` ORDER by `id` desc limit 1) as closuredate,due_date,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,credit_report.iniated_date,candidates_info.caserecddate,credit_report_com_ref');

		$this->db->from('client_new_cases');

		$this->db->join('view_vendor_master_log','view_vendor_master_log.id = client_new_cases.view_vendor_master_log_id');
       
        $this->db->join('credit_report_vendor_log','credit_report_vendor_log.id = view_vendor_master_log.case_id');

        $this->db->join('credit_report','credit_report.id = credit_report_vendor_log.case_id');
       
        $this->db->join("candidates_info",'candidates_info.id = credit_report.candsid');
 
		$this->db->where($where_array);


		
		$result  = $this->db->get();
   // print_r($this->db->last_query());
		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}
  
  
  
   public function upload_file_update_status($arrdata,$arrwhere)
	{
	    if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update('client_new_case_rearranged_file', $arrdata);

			record_db_error($this->db->last_query());

			return $result;
	    }
	   
	}
  
	public function upload_file_update($updateArray)
	{
		//print_r($updateArray);

		$this->db->insert_batch('client_new_case_rearranged_file',$updateArray,'id');
		record_db_error($this->db->last_query());
		return true;
	}

}
?>