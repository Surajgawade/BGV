<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Common_model extends CI_Model {

    public function vendor_logs($where_array)
    {
        $this->db->select("*");
        $this->db->from('view_vendor_master_log');
        $this->db->where($where_array);
        return $this->db->get()->result_array();
    }
    
	public function select($tableName, $return_as_strict_row,$select_array, $where_array=array())
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
        return $result_array;
	}

    public function distinct_status($select_array,$where = array())
    {
        $this->db->select($select_array)->from('status');
        $this->db->where('filter_status !=',''); 
        $this->db->where($where); 
        $this->db->group_by('filter_status');
        return $this->db->get()->result_array();
    }

    public function distinct_report_status()
    {
        $this->db->select('id,report_status')->from('status');
        $this->db->where('report_status !=',''); 
        $this->db->group_by('report_status');
        $this->db->order_by('report_status','asc');
        return $this->db->get()->result_array();
    }

    public function distinct_report_status_case_status()
    {
        $order_clause = "(case id when 1 THEN 0 else 1 end),(case id when 5 THEN 0 else 1 end)";
     
        $this->db->select('id,report_status')->from('status');
        $this->db->where('report_status !=',''); 
        $this->db->where('components_id','0'); 
        $this->db->group_by('report_status');
        $this->db->order_by($order_clause);
        return $this->db->get()->result_array();
    }

    public function component_serial_wise($where_array = array('status' => 1))
    {   
        $this->db->select('*');

        $this->db->from('components_admin');

        $this->db->where($where_array); 

        $this->db->order_by('serial_id','asc');

        return $this->db->get()->result_array();
        
    }

    public function select_vendor_list($component_name)
    {
        $result = $this->db->select('id,vendor_name')->from('vendors')->where("vendors_components LIKE '%".$component_name."%' ")->get();
        
        return $result->result_array();
        
    }

    public function user_actity_data($arrdata)
    {
        $this->db->insert('users_activitity_data', $arrdata);
        record_db_error($this->db->last_query());
        return $this->db->insert_id();
    }

    public function select_candidate_billed_date($tableName, $return_as_strict_row,$select_array, $where_array=array())
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
        return $result_array;
    }
   
    public function update_candidate_billed_date($arrdata,$arrwhere)
    {
        if(!empty($arrwhere))
        {
            $this->db->where($arrwhere);

            $result = $this->db->update('candidates_info', $arrdata);
       
            record_db_error($this->db->last_query());
             
            return $result;
        }
        
    }

    public function get_case_activity_status($where)
    {
        $this->db->select('case_activity');

        $this->db->from('clients_details');
        
        $this->db->where($where);

        $this->db->limit(1);  

        $result  = $this->db->get();

        record_db_error($this->db->last_query());

        return $result->result_array();
    }

    public function select_spoc_mail_id($where_array)
    {

        $this->db->select("client_spoc_details.*");

        $this->db->from('client_spoc_details');

        $this->db->where('clients_details_id',$where_array);

        $result = $this->db->get();

        record_db_error($this->db->last_query());
        
        return $result->result_array();

    }


    public function address_entity_pack_details($where_arry)
    {
        $this->db->select("addrver.*");

        $this->db->from('addrver'); 
        
        $this->db->where($where_arry);

        $this->db->order_by('addrver.id','desc');

        return $this->db->get()->result_array();
    }

	public function save($tableName,$arrdata, $arrwhere = array())
    {
    	if(!empty($arrwhere)){
    		foreach ($arrwhere as $field => $value)  {
    			$this->db->where($field,$value);	
    		}
            return $this->db->update($tableName, $arrdata);

    	} else{
    		$this->db->insert($tableName, $arrdata);    
            return $this->db->insert_id();
    	}
    }

    public function update_in($tableName,$arrdata,$ids_in)
    {
        $this->db->where_in('id', ''.$ids_in['where_id'].'', FALSE);
        return $this->db->update($tableName, $arrdata);
    }

    public function common_insert_batch($tableName,$arrdata)
    {
        $this->db->trans_begin();

        $this->db->insert_batch($tableName, $arrdata);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return TRUE;
        }
    }

    public function common_insert_transaction_no($tableName,$arrdata)
    {
        $this->db->insert($tableName, $arrdata);
        record_db_error($this->db->last_query());
        return $this->db->insert_id();
    }

    public function update_transaction_id($arrdata,$arrwhere)
    {
        if(!empty($arrwhere))
        {
            $this->db->where($arrwhere);

            $result = $this->db->update('view_vendor_master_log', $arrdata);
       
            record_db_error($this->db->last_query());
             
            return $result;
        }
    }

    public function delete($tableName, $arrwhere)
	{
		return $this->db->delete($tableName, $arrwhere);	
	}

    public function groups_permissions_menu_name($where_array)
    {
        $this->db->select('roles_permissions.*,admin_menus.controllers');

        $this->db->from('roles_permissions');

        $this->db->join("admin_menus","admin_menus.id = groups_permissions.admin_menu_id");
        
        $this->db->where($where_array);

        $result  = $this->db->get();

        record_db_error($this->db->last_query());
        
        return $result->result_array();
    }

    public function assign_cases_to_team($tableName,$arrwhere)
    {
        if(!empty($arrwhere))
        {
            return $this->db->update_batch($tableName, $arrwhere, 'id');
        }
    }

    public function update_batch_all($tableName,$data,$where)
    {
        $this->db->trans_begin();

        $this->db->update_batch($tableName, $data,$where);

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return FALSE;
        }
        else
        {
            $this->db->trans_commit();
            return TRUE;
        }
    }

    public function last_insert_id()
    {
        $next = $this->db->query("SHOW TABLE STATUS LIKE 'view_vendor_master_log'");
        $next = $next->row(0);
        $next->Auto_increment;
        return $next->Auto_increment;
    }

    public function update_batch_vendor_assign($tableName,$updateArray,$where_arry)
    {       

        $this->db->where($where_arry);
        $this->db->update($tableName,$updateArray);
        
        return $this->db->affected_rows();
    }

    public function update_status($tableName,$updateArray,$where_arry)
    {   
       
        $this->db->where($where_arry);
        $this->db->update($tableName,$updateArray);
        return $this->db->affected_rows();
    }

   public function users_list_executive1($tableName, $return_as_strict_row,$select_array, $where_array=array())
    {

        $this->db->select($select_array);

        $this->db->from($tableName);

        $this->db->join('roles','roles.id = user_profile.tbl_roles_id');

        $this->db->join('roles_permissions','roles_permissions.tbl_roles_id = roles.id');

        $this->db->where('roles_permissions.access_task_list_re_assign = 1' ); 

        
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

    public function update_log_in($arrdata,$arrwhere = array())
    {

        if(!empty($arrwhere))
        {
            $this->db->where($arrwhere);

            $result = $this->db->update('user_profile', $arrdata);
        
            record_db_error($this->db->last_query());

            return $result;
        }
       
    }
   
    public function get_count($arrwhere)
    {
        
        $this->db->select('count');

        $this->db->from('admin_menus');


        $this->db->where($arrwhere); 
  
       
        return $this->db->get()->row()->count;
       
    }

    public function update_count($arrdata,$arrwhere = array())
    {
        if(!empty($arrwhere))
        {
            $this->db->where($arrwhere);

            $result = $this->db->update('admin_menus', $arrdata);

            record_db_error($this->db->last_query());

            return $result;
        }
        
    }
  
   
     public function candidate_entity_package_tat_day($wherearray = array())
    {

        $this->db->select('tat_addrver,tat_empver,tat_eduver,tat_refver,tat_courtver,tat_globdbver,tat_narcver, tat_crimver,tat_identity,tat_cbrver,tat_social_media');

        $this->db->from('clients_details');

      
        
        $this->db->where($wherearray);

        $result  = $this->db->get();

        record_db_error($this->db->last_query());
        
        return $result->result_array();
   }

    public function get_holiday($wherearray = array())
    {

        $this->db->select('holiday_date');

        $this->db->from('holiday_dates');

      
        
        $this->db->where($wherearray);

        $result  = $this->db->get();

        record_db_error($this->db->last_query());
        
        return $result->result_array();
   }

   public function get_addres_ver_filter_status($wherearray)
    {

        $this->db->select('addrver.*,addrverres.verfstatus,addrverres.var_filter_status,addrverres.var_report_status');

        $this->db->from('addrver');

        $this->db->join('addrverres','addrverres.addrverid = addrver.id');
        
        $this->db->where($wherearray);

        $result  = $this->db->get();

        record_db_error($this->db->last_query());
        
        return $result->result_array();
   }

    public function set_address_verification_status($arrdata,$arrwhere)
    {
        if(!empty($arrwhere))
        {
            $this->db->where($arrwhere);

            $result = $this->db->update('addrver', $arrdata);

            record_db_error($this->db->last_query());

            return $result;
        }
        
    }
    
    public function save_update_vendor_files($arrdata,$arrwhere)
    { 
        if(!empty($arrwhere))
        {
            $this->db->where($arrwhere);

            $result = $this->db->update('view_vendor_master_log_file', $arrdata);

            record_db_error($this->db->last_query());

            return $result;
        }

    }

    public function get_scope_of_work($where)
    {
        $this->db->select('scope_of_work');

        $this->db->from('clients_details');
        
        $this->db->where($where);

        $this->db->limit(1);  

        $result  = $this->db->get();

        record_db_error($this->db->last_query());

        return $result->result_array();
    }

    public function component_stat_update($arrdata,$arrwhere = array())
    {
        if(!empty($arrwhere))
        {
            $this->db->where($arrwhere);

            $result = $this->db->update('client_candidates_info', $arrdata);

            record_db_error($this->db->last_query());

            return $result;
        }
    }

    public function select_address_data($where_arry)
    {
        $this->db->select('addrver.id,addrver.address,candidates_info.CandidateName,candidates_info.cands_email_id,candidates_info.CandidatesContactNumber,clients.clientname,(select email from user_profile where user_profile.id = clients.clientmgr) as client_manager_email_id');

        $this->db->from('addrver');
        $this->db->join('candidates_info','candidates_info.id = addrver.candsid');
        $this->db->join('clients','clients.id = addrver.clientid');
        $this->db->where('addrver.id',$where_arry); 
        
        $result  = $this->db->get();

        record_db_error($this->db->last_query());

        return $result->result_array();
    }

    public function select_client_closure($tableName,$select_array, $where_array=array(),$id)
    {
        $this->db->select($select_array);

        $this->db->from($tableName);

        $this->db->where($where_array); 

        if(!empty($id))
        {
          $this->db->where_in($tableName.'.id',$id); 
        }
        
        $result_array = $this->db->get()->result_array();   
      
        
        return $result_array;
    }



}
?>