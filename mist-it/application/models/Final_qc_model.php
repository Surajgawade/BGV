<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Final_qc_model extends CI_Model
{
	function __construct()
    {
		$this->tableName = 'sla_default_setting';

		$this->primaryKey = 'id';
	}

	/*public function select($return_as_strict_row,$select_array, $where_array = array())
	{
		$conditional_status = "(`candidates_info`.`overallstatus` = '3' or `candidates_info`.`overallstatus` = '4' or `candidates_info`.`overallstatus` = '6' or `candidates_info`.`overallstatus` = '7' or `candidates_info`.`overallstatus` = '8' )";

		$conditional_date = "(`candidates_info`.`overallclosuredate` != '0000-00-00')";


		$this->db->select($select_array);

		$this->db->from("candidates_info");

		//$this->db->join("user_profile",'user_profile.id = candidates_info.created_by','left');


		$this->db->where($where_array);

		$this->db->where($conditional_status);

		$this->db->where($conditional_date);

		$this->db->order_by('candidates_info.id', 'desc');
		
		$result  = $this->db->get();
    // print_r($this->db->last_query());
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
	}*/

	public function select_client($tableName,$return_as_strict_row,$select_array)
	{
		$conditional_status = "(`candidates_info`.`overallstatus` = '3' or `candidates_info`.`overallstatus` = '4' or `candidates_info`.`overallstatus` = '6' or `candidates_info`.`overallstatus` = '7' or `candidates_info`.`overallstatus` = '8' )";

		$conditional_date = "(`candidates_info`.`overallclosuredate` != '0000-00-00')";


		$this->db->select($select_array);

		$this->db->from($tableName);

		$this->db->join("candidates_info",'candidates_info.clientid = clients.id');
 

        $this->db->join("clients_details",'clients_details.tbl_clients_id = candidates_info.clientid and `clients_details`.`entity` = `candidates_info`.`entity` AND `clients_details`.`package` = `candidates_info`.`package`');

        $this->db->where("candidates_info.status =", 1);

        $this->db->where("candidates_info.final_qc_send_mail  !=", 1);

         $this->db->where("candidates_info.final_qc_send_mail  !=", 2);

        $this->db->where("candidates_info.final_qc =", 'Final QC Approve');

        $this->db->where("clients_details.report_type =", 1);

       // $this->db->where("clients_details.final_qc =", 1);

      //  $this->db->where("clients_details.auto_report =", 1);

		$this->db->where($conditional_status);

		$this->db->where($conditional_date);

		$this->db->order_by('clients.clientname', 'ASC');

		$result  = $this->db->get();
    // print_r($this->db->last_query());
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

	public function select_client_annexure($tableName,$return_as_strict_row,$select_array)
	{
		$conditional_status = "(`candidates_info`.`overallstatus` = '3' or `candidates_info`.`overallstatus` = '4' or `candidates_info`.`overallstatus` = '6' or `candidates_info`.`overallstatus` = '7' or `candidates_info`.`overallstatus` = '8' )";

		$conditional_date = "(`candidates_info`.`overallclosuredate` != '0000-00-00')";


		$this->db->select($select_array);

		$this->db->from($tableName);


		$this->db->join("candidates_info",'candidates_info.clientid = clients.id');
 

        $this->db->join("clients_details",'clients_details.tbl_clients_id = candidates_info.clientid and `clients_details`.`entity` = `candidates_info`.`entity` AND `clients_details`.`package` = `candidates_info`.`package`');

        $this->db->where("candidates_info.status =", 1);

        $this->db->where("candidates_info.final_qc_send_mail  !=", 1);

         $this->db->where("candidates_info.final_qc_send_mail  !=", 2);

        $this->db->where("candidates_info.final_qc =", 'Final QC Approve');

         $this->db->where("clients_details.report_type =", 2);

       // $this->db->where("clients_details.final_qc =", 1);

      //  $this->db->where("clients_details.auto_report =", 1);

		$this->db->where($conditional_status);

		$this->db->where($conditional_date);

		$this->db->order_by('clients.clientname', 'ASC');

		$result  = $this->db->get();
    // print_r($this->db->last_query());
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

    
    public function select_client_list_view($tableName,$return_as_strict_row,$select_array)
	{
		$conditional_status = "(`candidates_info`.`overallstatus` = '3' or `candidates_info`.`overallstatus` = '4' or `candidates_info`.`overallstatus` = '6' or `candidates_info`.`overallstatus` = '7' or `candidates_info`.`overallstatus` = '8' )";

		$conditional_date = "(`candidates_info`.`overallclosuredate` != '0000-00-00')";


		$this->db->select($select_array);

		$this->db->from($tableName);

		$this->db->join("candidates_info",'candidates_info.clientid = clients.id');
 

        $this->db->join("clients_details",'clients_details.tbl_clients_id = candidates_info.clientid and `clients_details`.`entity` = `candidates_info`.`entity` AND `clients_details`.`package` = `candidates_info`.`package`');

        $this->db->where("candidates_info.status =", 1);

        $this->db->where("candidates_info.final_qc =", 'final qc pending');

        $this->db->where("clients_details.final_qc =", 1);

		$this->db->where($conditional_status);

		$this->db->where($conditional_date);

		$this->db->order_by('clients.clientname', 'ASC');

		$result  = $this->db->get();
    // print_r($this->db->last_query());
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


	protected function filter_where_cond($where_arry)
	{
         $where = array();
     
		if(isset($where_arry['clientid']) &&  $where_arry['clientid'] != 0 )	
		{
			$where['candidates_info.clientid'] = $where_arry['clientid'];
		}
        if(isset($where_arry['entity']) &&  $where_arry['entity'] != '' && $where_arry['entity'] != 0 && $where_arry['entity'] != null)	
		{
			$where['candidates_info.entity'] = $where_arry['entity'];
		} 

		if(isset($where_arry['package']) &&  $where_arry['package'] != 0 && $where_arry['package'] != '')	
		{
			$where['candidates_info.package'] = $where_arry['package']; 
		}
		
		/*if(isset($where_arry['overallstatus']) &&  $where_arry['overallstatus'] != 0 && $where_arry['overallstatus'] != '')	
		{
			$where['candidates_info.overallstatus'] = $where_arry['overallstatus']; 
		}*/

		
		return $where;
	}
     
    public function select_final_qc($where,$columns)
	{ 

		$conditional_status = "(`candidates_info`.`overallstatus` = '3' or `candidates_info`.`overallstatus` = '4' or `candidates_info`.`overallstatus` = '6' or `candidates_info`.`overallstatus` = '7' or `candidates_info`.`overallstatus` = '8' )";

		$conditional_date = "(`candidates_info`.`overallclosuredate` != '0000-00-00')";


        $this->db->select('candidates_info.*,(select user_name from user_profile where user_profile.id = candidates_info.created_by) as username,clients.clientname,status.status_value,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,clients_details.final_qc,clients_details.component_id,candidates_info.final_qc_send_mail_timestamp');

		$this->db->from("candidates_info");
        
        $this->db->where("candidates_info.status =", 1);

        $this->db->where("candidates_info.final_qc =", 'final qc pending');

        $this->db->where("clients_details.final_qc =", 1);

     
        $this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("status",'status.id = candidates_info.overallstatus');

		$this->db->join("clients_details",'clients_details.tbl_clients_id = candidates_info.clientid and `clients_details`.`entity` = `candidates_info`.`entity` AND `clients_details`.`package` = `candidates_info`.`package`');
        
     
        $this->db->where($conditional_status);

		$this->db->where($conditional_date);

		$this->db->where($this->filter_where_cond($where));

       if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->group_start();

			$this->db->like('candidates_info.caserecddate', $where['search']['value']);

			$this->db->or_like('candidates_info.cmp_ref_no', $where['search']['value']);

			$this->db->or_like('candidates_info.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where['search']['value']);

			$this->db->or_like('status.status_value', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);	

			$this->db->group_end();
		}


		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 
            
			$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		}
		else
		{
			$this->db->order_by('candidates_info.modified_on','ASC');
		}
        $this->db->limit($where['length'],$where['start']);
      
        $result  = $this->db->get();
	
		record_db_error($this->db->last_query());
	
		return $result->result_array();

	} 

	public function select_final_count_qc($where,$columns)
	{ 
       
		$conditional_status = "(`candidates_info`.`overallstatus` = '3' or `candidates_info`.`overallstatus` = '4' or `candidates_info`.`overallstatus` = '6' or `candidates_info`.`overallstatus` = '7' or `candidates_info`.`overallstatus` = '8' )";

		$conditional_date = "(`candidates_info`.`overallclosuredate` != '0000-00-00')";


        $this->db->select('candidates_info.*,(select user_name from user_profile where user_profile.id = candidates_info.created_by) as username,clients.clientname,status.status_value,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,clients_details.final_qc,clients_details.component_id,candidates_info.final_qc_send_mail_timestamp');

		$this->db->from("candidates_info");
        
        $this->db->where("candidates_info.status =", 1);

        $this->db->where("candidates_info.final_qc =", 'final qc pending');

        $this->db->where("clients_details.final_qc =", 1);

        $this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("status",'status.id = candidates_info.overallstatus');

		$this->db->join("clients_details",'clients_details.tbl_clients_id = candidates_info.clientid and `clients_details`.`entity` = `candidates_info`.`entity` AND `clients_details`.`package` = `candidates_info`.`package`');
        
     
        $this->db->where($conditional_status);

		$this->db->where($conditional_date);

		$this->db->where($this->filter_where_cond($where));

        if(is_array($where) && $where['search']['value'] != "")
		{

			$this->db->group_start();
			$this->db->like('candidates_info.caserecddate', $where['search']['value']);

			$this->db->or_like('candidates_info.cmp_ref_no', $where['search']['value']);

			$this->db->or_like('candidates_info.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where['search']['value']);

			$this->db->or_like('status.status_value', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);	

		    $this->db->group_end();
		}

		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 
            

			$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		}
		else
		{
			$this->db->order_by('candidates_info.modified_on','ASC');
		}

        $result  = $this->db->get();
	
		record_db_error($this->db->last_query());
		
		return $result->result_array();

	} 


	public function select_final_approve_qc($where,$columns)
	{ 

		$conditional_status = "(`candidates_info`.`overallstatus` = '3' or `candidates_info`.`overallstatus` = '4' or `candidates_info`.`overallstatus` = '6' or `candidates_info`.`overallstatus` = '7' or `candidates_info`.`overallstatus` = '8' )";

		$conditional_date = "(`candidates_info`.`overallclosuredate` != '0000-00-00')";


        $this->db->select('candidates_info.*,(select user_name from user_profile where user_profile.id = candidates_info.final_qc_updated_by) as username,clients.clientname,status.status_value,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,clients_details.final_qc,clients_details.component_id,candidates_info.final_qc_send_mail_timestamp');

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

		$this->db->where($this->filter_where_cond($where));

        if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->group_start();
			$this->db->like('candidates_info.caserecddate', $where['search']['value']);

			$this->db->or_like('candidates_info.cmp_ref_no', $where['search']['value']);

			$this->db->or_like('candidates_info.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where['search']['value']);

			$this->db->or_like('status.status_value', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);	
			 $this->db->group_end();
		}
		
		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 
            

			$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		}
		else
		{
			$this->db->order_by('candidates_info.modified_on','ASC');
		}

        $this->db->limit($where['length'],$where['start']);
      
        $result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();

	} 

	public function select_final_approve_count_qc($where,$columns)
	{ 

		$conditional_status = "(`candidates_info`.`overallstatus` = '3' or `candidates_info`.`overallstatus` = '4' or `candidates_info`.`overallstatus` = '6' or `candidates_info`.`overallstatus` = '7' or `candidates_info`.`overallstatus` = '8' )";

		$conditional_date = "(`candidates_info`.`overallclosuredate` != '0000-00-00')";


        $this->db->select('candidates_info.*,(select user_name from user_profile where user_profile.id = candidates_info.created_by) as username,clients.clientname,status.status_value,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,clients_details.final_qc,clients_details.component_id,candidates_info.final_qc_send_mail_timestamp');

		$this->db->from("candidates_info");
        
        $this->db->where("candidates_info.status =", 1);

        $this->db->where("candidates_info.final_qc =", 'Final QC Approve');

        $this->db->where("candidates_info.final_qc_send_mail !=", 2);

        $this->db->where("candidates_info.final_qc_send_mail !=", 1);

        $this->db->where("clients_details.final_qc =", 1);

        $this->db->where("clients_details.auto_report =", 1);

        $this->db->where("clients_details.report_type =", 1);
  
        $this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("status",'status.id = candidates_info.overallstatus');

		$this->db->join("clients_details",'clients_details.tbl_clients_id = candidates_info.clientid and `clients_details`.`entity` = `candidates_info`.`entity` AND `clients_details`.`package` = `candidates_info`.`package`');
        
     
        $this->db->where($conditional_status);

		$this->db->where($conditional_date);

		$this->db->where($this->filter_where_cond($where));

       if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->group_start();
			$this->db->like('candidates_info.caserecddate', $where['search']['value']);

			$this->db->or_like('candidates_info.cmp_ref_no', $where['search']['value']);

			$this->db->or_like('candidates_info.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where['search']['value']);

			$this->db->or_like('status.status_value', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);	

			$this->db->group_end();
		}
		
		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 
            

			$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		}
		else
		{
			$this->db->order_by('candidates_info.modified_on','ASC');
		}

        $result  = $this->db->get();
	
		record_db_error($this->db->last_query());
		
		return $result->result_array();

	} 



/*	public function select_final_approve_qc_annexture($where,$columns)
	{ 
		$conditional_status = "(`candidates_info`.`overallstatus` = '3' or `candidates_info`.`overallstatus` = '4' or `candidates_info`.`overallstatus` = '6' or `candidates_info`.`overallstatus` = '7' or `candidates_info`.`overallstatus` = '8' )";

		$conditional_date = "(`candidates_info`.`overallclosuredate` != '0000-00-00')";


        $this->db->select('candidates_info.*,(select user_name from user_profile where user_profile.id = candidates_info.final_qc_updated_by) as username,clients.clientname,status.status_value,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,clients_details.final_qc,clients_details.component_id,candidates_info.final_qc_send_mail_timestamp');

		$this->db->from("candidates_info");
        
        $this->db->where("candidates_info.status =", 1);

   //     $this->db->where("candidates_info.final_qc =", 'Final QC Approve');

        $this->db->where("clients_details.report_type =", 2);

      //  $this->db->where("candidates_info.final_qc_send_mail !=", 2);

      //  $this->db->where("candidates_info.final_qc_send_mail !=", 1);

      //  $this->db->where("clients_details.auto_report =", 1);

        $this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("status",'status.id = candidates_info.overallstatus');

		$this->db->join("clients_details",'clients_details.tbl_clients_id = candidates_info.clientid and `clients_details`.`entity` = `candidates_info`.`entity` AND `clients_details`.`package` = `candidates_info`.`package`');
        
     
        $this->db->where($conditional_status);

		$this->db->where($conditional_date);

		$this->db->where($this->filter_where_cond($where));

		if(isset($where['start_dates']) &&  $where['start_dates'] != '' && isset($where['end_dates']) &&  $where['end_dates'] != '')	
		{  
              
		     	$start_date  =  convert_display_to_db_date($where['start_dates']);
	            $end_date  =  convert_display_to_db_date($where['end_dates']);
	   
             
		     	$where3 = "DATE_FORMAT(`candidates_info`.`overallclosuredate`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";
                
                $this->db->where($where3); 

		} 

        if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->group_start();
			$this->db->like('candidates_info.caserecddate', $where['search']['value']);

			$this->db->or_like('candidates_info.cmp_ref_no', $where['search']['value']);

			$this->db->or_like('candidates_info.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where['search']['value']);

			$this->db->or_like('status.status_value', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);	
			 $this->db->group_end();
		}
		
		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 
            

			$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		}
		else
		{
			$this->db->order_by('candidates_info.modified_on','ASC');
		}
        
		if($where['length'] != "-1")
		{

          $this->db->limit($where['length'],$where['start']);

		}

        $result  = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();

	} 

	public function select_final_approve_annexture_count_qc($where,$columns)
	{ 

		$conditional_status = "(`candidates_info`.`overallstatus` = '3' or `candidates_info`.`overallstatus` = '4' or `candidates_info`.`overallstatus` = '6' or `candidates_info`.`overallstatus` = '7' or `candidates_info`.`overallstatus` = '8' )";

		$conditional_date = "(`candidates_info`.`overallclosuredate` != '0000-00-00')";


        $this->db->select('candidates_info.*,(select user_name from user_profile where user_profile.id = candidates_info.created_by) as username,clients.clientname,status.status_value,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,clients_details.final_qc,clients_details.component_id,candidates_info.final_qc_send_mail_timestamp');

		$this->db->from("candidates_info");
        
        $this->db->where("candidates_info.status =", 1);

      //  $this->db->where("candidates_info.final_qc =", 'Final QC Approve');

      //  $this->db->where("candidates_info.final_qc_send_mail !=", 2);

       // $this->db->where("candidates_info.final_qc_send_mail !=", 1);

        $this->db->where("clients_details.report_type =", 2);

     //   $this->db->where("clients_details.auto_report =", 1);
  
        $this->db->join("clients",'clients.id = candidates_info.clientid');

		$this->db->join("status",'status.id = candidates_info.overallstatus');

		$this->db->join("clients_details",'clients_details.tbl_clients_id = candidates_info.clientid and `clients_details`.`entity` = `candidates_info`.`entity` AND `clients_details`.`package` = `candidates_info`.`package`');
        
     
        $this->db->where($conditional_status);

		$this->db->where($conditional_date);

		$this->db->where($this->filter_where_cond($where));

		
		if(isset($where['start_dates']) &&  $where['start_dates'] != '' && isset($where['end_dates']) &&  $where['end_dates'] != '')	
		{  
              
		     	$start_date  =  convert_display_to_db_date($where['start_dates']);
	            $end_date  =  convert_display_to_db_date($where['end_dates']);
	   
             
		     	$where3 = "DATE_FORMAT(`candidates_info`.`overallclosuredate`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";
                
                $this->db->where($where3); 

		} 


       if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->group_start();
			$this->db->like('candidates_info.caserecddate', $where['search']['value']);

			$this->db->or_like('candidates_info.cmp_ref_no', $where['search']['value']);

			$this->db->or_like('candidates_info.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where['search']['value']);

			$this->db->or_like('status.status_value', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);	

			$this->db->group_end();
		}
		
		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 
            

			$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		}
		else
		{
			$this->db->order_by('candidates_info.modified_on','ASC');
		}

        $result  = $this->db->get();
	
		record_db_error($this->db->last_query());
		
		return $result->result_array();

	} */

	public function select_final_approve_qc_annexture($where,$columns)
	{ 
		

        $conditional_status = "(view_vendor_master_log.final_status = 'clear' or view_vendor_master_log.final_status = 'possible match' or view_vendor_master_log.final_status = 'approve' or view_vendor_master_log.final_status ='insufficiency')" ;
 
        $this->db->select('courtver.*,clients.clientname,candidates_info.id as candidate_id,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,status.status_value,view_vendor_master_log.modified_on as mod_time,(select vendor_name from vendors where vendors.id =  view_vendor_master_log.modified_by) as vendor_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity and entity_package.is_entity_package = 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package and entity_package.is_entity_package = 2) as package_name');

		$this->db->from("courtver");

		$this->db->join("candidates_info",'candidates_info.id = courtver.candsid');

		$this->db->join("clients",'clients.id = courtver.clientid');

		$this->db->join("status",'status.id = candidates_info.overallstatus');
        
        $this->db->join("courtver_vendor_log",'(courtver_vendor_log.case_id = courtver.id and courtver_vendor_log.status = 1)');

		$this->db->join("view_vendor_master_log",'(view_vendor_master_log.case_id = courtver_vendor_log.id and view_vendor_master_log.component_tbl_id = 5 and view_vendor_master_log.component = "courtver")');

     
        $this->db->where($conditional_status);


		//$this->db->where($this->filter_where_cond($where));
         
		if($this->user_info['id'] == "24")
		{
			$where2 = "courtver.clientid = 43";
                
			$this->db->where($where2); 
		}	
		else{
	
			if(isset($where['clientid']) &&  $where['clientid'] != 0 )	
			{
				
				$where2 = "courtver.clientid = ".$where['clientid'];
					
				$this->db->where($where2); 
			}
    	}	

	 	if(isset($where['start_dates']) &&  $where['start_dates'] != '' && isset($where['end_dates']) &&  $where['end_dates'] != '')	
		{  
              
		     	$start_date  =  convert_display_to_db_date($where['start_dates']);
	            $end_date  =  convert_display_to_db_date($where['end_dates']);
	   
             
		     	$where3 = "DATE_FORMAT(`view_vendor_master_log`.`modified_on`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";
                
                $this->db->where($where3); 

		} 
    
        if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->group_start();
			
			$this->db->or_like('candidates_info.cmp_ref_no', $where['search']['value']);

			$this->db->or_like('candidates_info.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);	
			 $this->db->group_end();
		}
		
		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 
            

			$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		}
		else
		{
			$this->db->order_by('courtver.id','desc');
		}
        
		if($where['length'] != "-1")
		{

          $this->db->limit($where['length'],$where['start']);

		}

        $result  = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();

	} 

	public function select_final_approve_annexture_count_qc($where,$columns)
	{ 
		

        $conditional_status = "(view_vendor_master_log.final_status = 'clear' or view_vendor_master_log.final_status = 'possible match' or view_vendor_master_log.final_status = 'approve' or view_vendor_master_log.final_status ='insufficiency')" ;
 
        $this->db->select('courtver.*,clients.clientname,candidates_info.id as candidate_id,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,status.status_value,view_vendor_master_log.modified_on as mod_time,(select vendor_name from vendors where vendors.id =  view_vendor_master_log.modified_by) as vendor_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity and entity_package.is_entity_package = 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package and entity_package.is_entity_package = 2) as package_name');

		$this->db->from("courtver");

		$this->db->join("candidates_info",'candidates_info.id = courtver.candsid');

		$this->db->join("clients",'clients.id = courtver.clientid');

		$this->db->join("status",'status.id = candidates_info.overallstatus');
        
        $this->db->join("courtver_vendor_log",'(courtver_vendor_log.case_id = courtver.id and courtver_vendor_log.status = 1)');

		$this->db->join("view_vendor_master_log",'(view_vendor_master_log.case_id = courtver_vendor_log.id and view_vendor_master_log.component_tbl_id = 5 and view_vendor_master_log.component = "courtver")');

     
        $this->db->where($conditional_status);


		//$this->db->where($this->filter_where_cond($where));

		
		if($this->user_info['id'] == "24")
		{
			$where2 = "courtver.clientid = 43";
                
			$this->db->where($where2); 
		}	
		else{
	
			if(isset($where['clientid']) &&  $where['clientid'] != 0 )	
			{
				
				$where2 = "courtver.clientid = ".$where['clientid'];
					
				$this->db->where($where2); 
			}
    	}	
		

		if(isset($where['start_dates']) &&  $where['start_dates'] != '' && isset($where['end_dates']) &&  $where['end_dates'] != '')	
		{  
              
		     	$start_date  =  convert_display_to_db_date($where['start_dates']);
	            $end_date  =  convert_display_to_db_date($where['end_dates']);
	   
             
		     	$where3 = "DATE_FORMAT(`view_vendor_master_log`.`modified_on`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";
                
                $this->db->where($where3); 

		} 

        if(is_array($where) && $where['search']['value'] != "")
		{
			$this->db->group_start();
			
			$this->db->or_like('candidates_info.cmp_ref_no', $where['search']['value']);

			$this->db->or_like('candidates_info.ClientRefNumber', $where['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where['search']['value']);

			$this->db->or_like('clients.clientname', $where['search']['value']);	
			 $this->db->group_end();
		}
		
		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 
            

			$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		}
		else
		{
			$this->db->order_by('courtver.id','DESC');
		}
        
		

        $result  = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();

	} 


	public function get_address_closed_qc_result($addsver_id)
	{
		$this->db->select("addrverres.verfstatus,addrverres.var_filter_status,addrverres.first_qc_approve");

		$this->db->from('addrverres');

		$this->db->join("addrver",'addrver.id = addrverres.addrverid');
	   	
    
		$this->db->group_by('addrverres.id');

		if(!empty($addsver_id))
		{
			$this->db->where('addrverres.candsid =',$addsver_id);
		}

		$this->db->order_by('addrverres.id', 'desc');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_employment_closed_qc_result($empsver_id)
	{
		$this->db->select("empverres.verfstatus,empverres.var_filter_status,empverres.first_qc_approve");

		$this->db->from('empverres');

		$this->db->join("addrver",'addrver.id = empverres.empverid');
	   	
    
		$this->db->group_by('empverres.id');

		if(!empty($empsver_id))
		{
			$this->db->where('empverres.candsid =',$empsver_id);
		}

		$this->db->order_by('empverres.id', 'desc');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_education_closed_qc_result($eduver_id)
	{
		$this->db->select("education_result.verfstatus,education_result.var_filter_status,education_result.first_qc_approve");

		$this->db->from('education_result');

		$this->db->join("education",'education.id = education_result.education_id');
	   	
    
		$this->db->group_by('education_result.id');

		if(!empty($eduver_id))
		{
			$this->db->where('education_result.candsid =',$eduver_id);
		}

		$this->db->order_by('education_result.id', 'desc');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_reference_closed_qc_result($reference_id)
	{
		$this->db->select("reference_result.verfstatus,reference_result.var_filter_status,reference_result.first_qc_approve");

		$this->db->from('reference_result');

		$this->db->join("reference",'reference.id = reference_result.reference_id');
	   	
    
		$this->db->group_by('reference_result.id');

		if(!empty($reference_id))
		{
			$this->db->where('reference_result.candsid =',$reference_id);
		}

		$this->db->order_by('reference_result.id', 'desc');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}


	public function get_court_closed_qc_result($court_id)
	{
		$this->db->select("courtver_result.verfstatus,courtver_result.var_filter_status,courtver_result.first_qc_approve");

		$this->db->from('courtver_result');

		$this->db->join("courtver",'courtver.id = courtver_result.courtver_id');
	   	
    
		$this->db->group_by('courtver_result.id');

		if(!empty($court_id))
		{
			$this->db->where('courtver_result.candsid =',$court_id);
		}

		$this->db->order_by('courtver_result.id', 'desc');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_global_closed_qc_result($global_id)
	{
		$this->db->select("glodbver_result.verfstatus,glodbver_result.var_filter_status,glodbver_result.first_qc_approve");

		$this->db->from('glodbver_result');

		$this->db->join("glodbver",'glodbver.id = glodbver_result.glodbver_id');
	   	
    
		$this->db->group_by('glodbver_result.id');

		if(!empty($global_id))
		{
			$this->db->where('glodbver_result.candsid =',$global_id);
		}

		$this->db->order_by('glodbver_result.id', 'desc');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_pcc_closed_qc_result($pcc_id)
	{
		$this->db->select("pcc_result.verfstatus,pcc_result.var_filter_status,pcc_result.first_qc_approve");

		$this->db->from('pcc_result');

		$this->db->join("pcc",'pcc.id = pcc_result.pcc_id');
	   	
    
		$this->db->group_by('pcc_result.id');

		if(!empty($pcc_id))
		{
			$this->db->where('pcc_result.candsid =',$pcc_id);
		}

		$this->db->order_by('pcc_result.id', 'desc');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}
      
    public function get_identity_closed_qc_result($identity_id)
	{
		$this->db->select("identity_result.verfstatus,identity_result.var_filter_status,identity_result.first_qc_approve");

		$this->db->from('identity_result');

		$this->db->join("identity",'identity.id = identity_result.identity_id');
	   	
    
		$this->db->group_by('identity_result.id');

		if(!empty($identity_id))
		{
			$this->db->where('identity_result.candsid =',$identity_id);
		}

		$this->db->order_by('identity_result.id', 'desc');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}
   
    public function get_credit_report_closed_qc_result($credit_report_id)
	{
		$this->db->select("credit_report_result.verfstatus,credit_report_result.var_filter_status,credit_report_result.first_qc_approve");

		$this->db->from('credit_report_result');

		$this->db->join("credit_report",'credit_report.id = credit_report_result.credit_report_id');
	   	
    
		$this->db->group_by('credit_report_result.id');

		if(!empty($credit_report_id))
		{
			$this->db->where('credit_report_result.candsid =',$credit_report_id);
		}

		$this->db->order_by('credit_report_result.id', 'desc');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}
    
    public function get_drugs_closed_qc_result($drugs_id)
	{
		$this->db->select("drug_narcotis_result.verfstatus,drug_narcotis_result.var_filter_status,drug_narcotis_result.first_qc_approve");

		$this->db->from('drug_narcotis_result');

		$this->db->join("drug_narcotis",'drug_narcotis.id = drug_narcotis_result.drug_narcotis_id');
	   	
    
		$this->db->group_by('drug_narcotis_result.id');

		if(!empty($drugs_id))
		{
			$this->db->where('drug_narcotis_result.candsid =',$drugs_id);
		}

		$this->db->order_by('drug_narcotis_result.id', 'desc');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}
	public function select_last_activity_component_name($where_arry)
	{
        $where_condition = "(action = 'Stop Check' or action = 'Clear' or action = 'Major Discrepancy' or action = 'Minor Discrepancy' or action = 'Unable to verify')";

		$this->db->select("CASE  WHEN activity_log.component_type = 1 THEN 'Address' WHEN activity_log.component_type = 2 THEN 'Employment' WHEN activity_log.component_type = 3 THEN 'Education' WHEN activity_log.component_type = 4 THEN 'Reference' WHEN activity_log.component_type = 5 THEN 'Court Verification'  WHEN activity_log.component_type = 6 THEN 'Global Database' WHEN activity_log.component_type = 7 THEN 'Drugs' WHEN activity_log.component_type = 8 THEN 'PCC' WHEN activity_log.component_type = 9 THEN 'Identity' WHEN activity_log.component_type = 10 THEN 'Credit Report' END AS component_name");

		$this->db->from('activity_log');

	
		if(!empty($where_arry))
		{
			$this->db->where($where_arry);
		}

		$this->db->where($where_condition);

		$this->db->order_by('activity_log.id', 'desc');

		$this->db->limit(1);

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();

	}

	public function select_candidate_for_export( $where_array = array())
	{
		$this->db->select('candidates_info.CandidateName,candidates_info.ClientRefNumber,courtver.court_com_ref,courtver.iniated_date,view_vendor_master_log.vendor_actual_status,view_vendor_master_log.vendor_remark,view_vendor_master_log.created_on,(select closuredate from courtver_result where courtver_result.courtver_id = courtver.id) as closuredate');

		$this->db->from("courtver");

	    $this->db->join("candidates_info",'candidates_info.id = courtver.candsid');

	    $this->db->join("courtver_vendor_log",'courtver_vendor_log.case_id = courtver.id');
        
        $this->db->join("view_vendor_master_log",'(view_vendor_master_log.case_id = courtver_vendor_log.id and view_vendor_master_log.component_tbl_id = 5)');


		$this->db->where_in('courtver.id',$where_array);
		
		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		$result_array = $result->result_array();
		
        return $result_array;
	}


}
?>