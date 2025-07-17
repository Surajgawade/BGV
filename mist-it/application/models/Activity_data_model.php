<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Activity_data_model extends CI_Model
{
	function __construct()
    {
		$this->tableName = 'activity_data';

		$this->primaryKey = 'id';
	}

	public function select($return_as_strict_row,$select_array = array('*'), $where_array = array())
	{
		$this->db->select($select_array);

		$this->db->from($this->tableName);

		$this->db->where($where_array);

		$this->db->order_by('id', 'asc');

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

	public function select_employee($return_as_strict_row,$select_array = array('*'), $where_array = array())
	{

		$where_condition = "(`activity_name` = 'Follow Up' OR `activity_name` = 'Stop Check')";

		$this->db->select($select_array);

		$this->db->from($this->tableName);

		$this->db->where($where_array);

        $this->db->where($where_condition);

		$this->db->order_by('id', 'asc');

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


	public function inseer_multiple($insert_array)
	{
		$res =  $this->db->insert_batch($this->tableName, $insert_array);
			
		record_db_error($this->db->last_query());
		
		return $res;
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

	public function activity_log_save($arrdata,$arrwhere = array())
	{

		//print_r($arrdata);exit();
		if(!empty($arrwhere))
		{
			$this->db->where($arrwhere);

			$result = $this->db->update('activity_log', $arrdata);
     
			record_db_error($this->db->last_query());

			return $result;
		}
		else
		{
			$this->db->insert('activity_log', $arrdata);

			record_db_error($this->db->last_query());
 
			return $this->db->insert_id();
		}
	}

	public function activity_log_select($return_as_strict_row,$select_array, $where_array = array())
	{
		$this->db->select($select_array);

		$this->db->from('activity_log');

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

	public function activity_log_records($where_array)
	{

		$table = $where_array['component_type'].'_activity_data';
		
		$this->db->select("activity_status,activity_mode,activity_type,action,next_follow_up_date,remarks,created_on,(select user_name from user_profile where id = $table.created_by) as res_created_by");

		$this->db->from($table);

		$this->db->where('comp_table_id',$where_array['comp_table_id']);

		$this->db->order_by("$table.created_on",'DESC');
		 
		$result  = $this->db->get();
		//print_r($this->db->last_query());
		//print_r($this->db->last_query());
		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function activity_all_data()
	{
		$this->db->select('component.component_name, activity_data.id as status_id,activity_data.activity_name as status_name,activity_mode.id as mode_id,activity_mode.activity_name as mode_name ,activity_data.created_on,activity_data.add_result ,activity_action.activity_remark,activity_type.id as type_id,activity_type.activity_name as type_name,
activity_action.id as action_id,activity_action.activity_name as action_name,(select user_name from  user_profile where  user_profile.id = activity_data.created_by) as user_name');

		$this->db->from('activity_data');

		$this->db->join('components as component','component.id = activity_data.components_id');

		$this->db->join('activity_data as activity_mode','activity_mode.parent_id = activity_data.id');

		$this->db->join('activity_data as activity_type','activity_type.parent_id = activity_mode.id');

		$this->db->join('activity_data as activity_action','activity_action.parent_id = activity_type.id');

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	

    public function save_trigger_save($arrdata,$where,$user_activity)
    { 


            if($where['component_type'] == "1")
            {
            	if($arrdata['action'] == "Follow Up")
            	{

                   $field_update = array('verfstatus' => 23,
                                'var_filter_status' =>'WIP',
                                'var_report_status' => 'WIP'   
                            );
                   $where_array = array('addrverres.addrverid' => $arrdata['comp_table_id']  
                            );
                   
            		 $this->db->where($where_array );

		             $result = $this->db->update('addrverres', $field_update);
            	}

            	$user_activity_data = $this->common_model->user_actity_data(array('component' => "Address",
                            'ref_no' => $user_activity['component_ref_no'],'candidate_name' => $user_activity['CandidateName'],'created_on' => date(DB_DATE_FORMAT),'created_by' => $this->user_info['id'],'activity_type' => 'Manual','action' => 'Verification Added - '.$arrdata['action']));
            	
			  $this->db->insert("address_activity_data", $arrdata);
		    }
		    if($where['component_type'] == "2")
            {
                if($arrdata['action'] == "Follow Up")
            	{

                   $field_update = array('verfstatus' => 23,
                                'var_filter_status' =>'WIP',
                                'var_report_status' => 'WIP'   
                            );
                   $where_array = array('empverres.empverid' => $arrdata['comp_table_id']  
                            );
                   
            		 $this->db->where($where_array );

		             $result = $this->db->update('empverres', $field_update);
            	}

            	$user_activity_data = $this->common_model->user_actity_data(array('component' => "Employment",
                            'ref_no' => $user_activity['component_ref_no'],'candidate_name' => $user_activity['CandidateName'],'created_on' => date(DB_DATE_FORMAT),'created_by' => $this->user_info['id'],'activity_type' => 'Manual','action' => 'Verification Added - '.$arrdata['action']));

			$this->db->insert("empver_activity_data", $arrdata);
		    }
            if($where['component_type'] == "3")
            {

            	if($arrdata['action'] == "Follow Up")
            	{

                   $field_update = array('verfstatus' => 23,
                                'var_filter_status' =>'WIP',
                                'var_report_status' => 'WIP'   
                            );
                   $where_array = array('education_result.education_id' => $arrdata['comp_table_id']  
                            );
                   
            		 $this->db->where($where_array );

		             $result = $this->db->update('education_result', $field_update);
            	}

            	$user_activity_data = $this->common_model->user_actity_data(array('component' => "Education",
                            'ref_no' => $user_activity['component_ref_no'],'candidate_name' => $user_activity['CandidateName'],'created_on' => date(DB_DATE_FORMAT),'created_by' => $this->user_info['id'],'activity_type' => 'Manual','action' => 'Verification Added - '.$arrdata['action']));

			$this->db->insert("education_activity_data", $arrdata);
		    }
		    if($where['component_type'] == "4")
            {
            	if($arrdata['action'] == "Follow Up")
            	{

                   $field_update = array('verfstatus' => 23,
                                'var_filter_status' =>'WIP',
                                'var_report_status' => 'WIP'   
                            );
                   $where_array = array('reference_result.reference_id' => $arrdata['comp_table_id']  
                            );
                   
            		 $this->db->where($where_array );

		             $result = $this->db->update('reference_result', $field_update);
            	}

            	$user_activity_data = $this->common_model->user_actity_data(array('component' => "Reference",
                            'ref_no' => $user_activity['component_ref_no'],'candidate_name' => $user_activity['CandidateName'],'created_on' => date(DB_DATE_FORMAT),'created_by' => $this->user_info['id'],'activity_type' => 'Manual','action' => 'Verification Added - '.$arrdata['action']));

			$this->db->insert("reference_activity_data", $arrdata);
		    }
		    if($where['component_type'] == "5")
            {

            	if($arrdata['action'] == "Follow Up")
            	{

                   $field_update = array('verfstatus' => 23,
                                'var_filter_status' =>'WIP',
                                'var_report_status' => 'WIP'   
                            );
                   $where_array = array('courtver_result.courtver_id' => $arrdata['comp_table_id']  
                            );
                   
            		 $this->db->where($where_array );

		             $result = $this->db->update('courtver_result', $field_update);
            	}

            	$user_activity_data = $this->common_model->user_actity_data(array('component' => "Court Verification",
                            'ref_no' => $user_activity['component_ref_no'],'candidate_name' => $user_activity['CandidateName'],'created_on' => date(DB_DATE_FORMAT),'created_by' => $this->user_info['id'],'activity_type' => 'Manual','action' => 'Verification Added - '.$arrdata['action']));

			$this->db->insert("court_activity_data", $arrdata);
		    }
		    if($where['component_type'] == "6")
            {
            	if($arrdata['action'] == "Follow Up")
            	{

                   $field_update = array('verfstatus' => 23,
                                'var_filter_status' =>'WIP',
                                'var_report_status' => 'WIP'   
                            );
                   $where_array = array('glodbver_result.glodbver_id' => $arrdata['comp_table_id']  
                            );
                   
            		 $this->db->where($where_array );

		             $result = $this->db->update('glodbver_result', $field_update);
            	}

            	$user_activity_data = $this->common_model->user_actity_data(array('component' => "Global Database",
                            'ref_no' => $user_activity['component_ref_no'],'candidate_name' => $user_activity['CandidateName'],'created_on' => date(DB_DATE_FORMAT),'created_by' => $this->user_info['id'],'activity_type' => 'Manual','action' => 'Verification Added - '.$arrdata['action']));


			$this->db->insert("glodbver_activity_data", $arrdata);
		    }
            if($where['component_type'] == "7")
            {
            	if($arrdata['action'] == "Follow Up")
            	{

                   $field_update = array('verfstatus' => 23,
                                'var_filter_status' =>'WIP',
                                'var_report_status' => 'WIP'   
                            );
                   $where_array = array('drug_narcotis_result.drug_narcotis_id' => $arrdata['comp_table_id']  
                            );
                   
            		 $this->db->where($where_array );

		             $result = $this->db->update('drug_narcotis_result', $field_update);
            	}

            	$user_activity_data = $this->common_model->user_actity_data(array('component' => "Drugs Verification",
                            'ref_no' => $user_activity['component_ref_no'],'candidate_name' => $user_activity['CandidateName'],'created_on' => date(DB_DATE_FORMAT),'created_by' => $this->user_info['id'],'activity_type' => 'Manual','action' => 'Verification Added - '.$arrdata['action']));

			$this->db->insert("drug_narcotis_activity_data", $arrdata);
		    }
		    if($where['component_type'] == "8")
            {
            	if($arrdata['action'] == "Follow Up")
            	{

                   $field_update = array('verfstatus' => 23,
                                'var_filter_status' =>'WIP',
                                'var_report_status' => 'WIP'   
                            );
                   $where_array = array('pcc_result.pcc_id' => $arrdata['comp_table_id']  
                            );
                   
            		 $this->db->where($where_array );

		             $result = $this->db->update('pcc_result', $field_update);
            	}


            	$user_activity_data = $this->common_model->user_actity_data(array('component' => "PCC",
                            'ref_no' => $user_activity['component_ref_no'],'candidate_name' => $user_activity['CandidateName'],'created_on' => date(DB_DATE_FORMAT),'created_by' => $this->user_info['id'],'activity_type' => 'Manual','action' => 'Verification Added - '.$arrdata['action']));

			$this->db->insert("pcc_activity_data", $arrdata);
		    }
		    if($where['component_type'] == "9")
            {

            	if($arrdata['action'] == "Follow Up")
            	{

                   $field_update = array('verfstatus' => 23,
                                'var_filter_status' =>'WIP',
                                'var_report_status' => 'WIP'   
                            );
                   $where_array = array('identity_result.identity_id' => $arrdata['comp_table_id']  
                            );
                   
            		 $this->db->where($where_array );

		             $result = $this->db->update('identity_result', $field_update);
            	}

            	$user_activity_data = $this->common_model->user_actity_data(array('component' => "Identity",
                            'ref_no' => $user_activity['component_ref_no'],'candidate_name' => $user_activity['CandidateName'],'created_on' => date(DB_DATE_FORMAT),'created_by' => $this->user_info['id'],'activity_type' => 'Manual','action' => 'Verification Added - '.$arrdata['action']));

			$this->db->insert("identity_activity_data", $arrdata);
		    }
		    if($where['component_type'] == "10")
            {
            	if($arrdata['action'] == "Follow Up")
            	{

                   $field_update = array('verfstatus' => 23,
                                'var_filter_status' =>'WIP',
                                'var_report_status' => 'WIP'   
                            );
                   $where_array = array('credit_report_result.credit_report_id' => $arrdata['comp_table_id']  
                            );
                   
            		 $this->db->where($where_array );

		             $result = $this->db->update('credit_report_result', $field_update);
            	}

            	$user_activity_data = $this->common_model->user_actity_data(array('component' => "Credit Report",
                            'ref_no' => $user_activity['component_ref_no'],'candidate_name' => $user_activity['CandidateName'],'created_on' => date(DB_DATE_FORMAT),'created_by' => $this->user_info['id'],'activity_type' => 'Manual','action' => 'Verification Added - '.$arrdata['action']));
            	
			  $this->db->insert("credit_report_activity_data", $arrdata);
		    }

		if($where['component_type'] == "11")
              {
	            	if($arrdata['action'] == "Follow Up")
	            	{

	                   $field_update = array('verfstatus' => 23,
	                                'var_filter_status' =>'WIP',
	                                'var_report_status' => 'WIP'   
	                            );
	                   $where_array = array('social_media_result.social_media_id' => $arrdata['comp_table_id']  
	                            );
	                   
	            		 $this->db->where($where_array );

			             $result = $this->db->update('social_media_result', $field_update);
	            	}

            	       $user_activity_data = $this->common_model->user_actity_data(array('component' => "Social Media",
                            'ref_no' => $user_activity['component_ref_no'],'candidate_name' => $user_activity['CandidateName'],'created_on' => date(DB_DATE_FORMAT),'created_by' => $this->user_info['id'],'activity_type' => 'Manual','action' => 'Verification Added - '.$arrdata['action']));
            	
			  $this->db->insert("social_media_activity_data", $arrdata);
		}    

          
			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	     
        
    }
}