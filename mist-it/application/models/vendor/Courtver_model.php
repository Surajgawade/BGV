<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Courtver_model extends CI_Model
{
	function __construct()
    {
		$this->tableName = 'courtver';

		$this->primaryKey = 'id';
	}
     
    public function update($tableName,$arrdata,$arrwhere = array())
    {
        if(!empty($arrwhere))
        {
            $this->db->where($arrwhere);

            $result = $this->db->update($tableName, $arrdata);
       
            record_db_error($this->db->last_query());
             
            return $result;
        }
       
    }

    public function insert_court_details($tableName,$arrdata)
    {
       
        $this->db->insert($tableName, $arrdata);

		record_db_error($this->db->last_query());

		return $this->db->insert_id();
        
    }


    public function select_file($select_array,$where_array)
    {
        $this->db->select($select_array);

        $this->db->from('view_vendor_master_log_file');

        $this->db->where($where_array);

        $this->db->order_by('id', 'desc');
        
        $result  = $this->db->get();

        record_db_error($this->db->last_query());
        
        return $result->result_array();
    }

    public function court_case_list($where_array = array(),$where,$columns)
    {


        $this->db->select("view_vendor_master_log.*,CandidateName,clients.clientname,courtver.court_com_ref,courtver.address_type,courtver.street_address,courtver.vendor_list_mode,courtver.city,courtver.state,courtver.pincode,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select user_name from vendor_executive_login where vendor_executive_login.id = view_vendor_master_log.has_case_id limit 1) as vendor_executive_id,candidates_info.ClientRefNumber as client_ref_no");

        $this->db->from('view_vendor_master_log');

    
        $this->db->join('courtver_vendor_log','(courtver_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "courtver" and view_vendor_master_log.component_tbl_id = 5)');

        $this->db->join('courtver','courtver.id = courtver_vendor_log.case_id');

        $this->db->join('candidates_info','candidates_info.id = courtver.candsid');
       
        $this->db->join("clients",'clients.id = courtver.clientid');

        

        $this->db->where($where_array);


        if(is_array($where) && $where['search']['value'] != "" )
        {
           
            $this->db->group_start();
            $this->db->like('candidates_info.cmp_ref_no', $where['search']['value']);

            $this->db->or_like('candidates_info.ClientRefNumber', $where['search']['value']);

            $this->db->or_like('courtver.court_com_ref', $where['search']['value']);

            $this->db->or_like('candidates_info.CandidateName', $where['search']['value']);

            $this->db->or_like('view_vendor_master_log.trasaction_id', $where['search']['value']);

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
            $this->db->order_by('view_vendor_master_log.trasaction_id','ASC');
        }

        $this->db->limit($where['length'],$where['start']);

        $result = $this->db->get();
     //  print_r($this->db->last_query());

       return $result->result_array();
   
    }

    public function court_case_list_count($where_array = array(),$where,$columns)
    {

        $this->db->select("view_vendor_master_log.*,CandidateName,clients.clientname,courtver.court_com_ref,courtver.address_type,courtver.street_address,courtver.vendor_list_mode,courtver.city,courtver.state,courtver.pincode,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select user_name from vendor_executive_login where vendor_executive_login.id = view_vendor_master_log.has_case_id limit 1) as vendor_executive_id,candidates_info.ClientRefNumber as client_ref_no");

        $this->db->from('view_vendor_master_log');

    
        $this->db->join('courtver_vendor_log','(courtver_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "courtver" and view_vendor_master_log.component_tbl_id = 5)');

        $this->db->join('courtver','courtver.id = courtver_vendor_log.case_id');

        $this->db->join('candidates_info','candidates_info.id = courtver.candsid');
       
        $this->db->join("clients",'clients.id = courtver.clientid');


        $this->db->where($where_array);

//print_r($where);
        if(is_array($where) && $where['search']['value'] != "" )
        {
           
            $this->db->group_start();
            $this->db->like('candidates_info.cmp_ref_no', $where['search']['value']);

            $this->db->or_like('candidates_info.ClientRefNumber', $where['search']['value']);

            $this->db->or_like('courtver.court_com_ref', $where['search']['value']);

            $this->db->or_like('candidates_info.CandidateName', $where['search']['value']);

            $this->db->or_like('view_vendor_master_log.trasaction_id', $where['search']['value']);

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
            $this->db->order_by('view_vendor_master_log.trasaction_id','ASC');
        }
   
        $result = $this->db->get();
     //  print_r($this->db->last_query());

       return $result->result_array();
   
    }

    public function court_case_list_closed($where_array = array(),$where,$columns)
    {

        $this->db->select("view_vendor_master_log.*,CandidateName,clients.clientname,courtver.court_com_ref,courtver.address_type,courtver.street_address,courtver.vendor_list_mode,courtver.city,courtver.state,courtver.pincode,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select user_name from vendor_executive_login where vendor_executive_login.id = view_vendor_master_log.has_case_id limit 1) as vendor_executive_id,candidates_info.ClientRefNumber as client_ref_no");

        $this->db->from('view_vendor_master_log');

        $this->db->join('courtver_vendor_log','(courtver_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "courtver" and view_vendor_master_log.component_tbl_id = 5)');

        $this->db->join('courtver','courtver.id = courtver_vendor_log.case_id');

        $this->db->join('candidates_info','candidates_info.id = courtver.candsid');
       
        $this->db->join("clients",'clients.id = courtver.clientid');


        $this->db->where($where_array);

        if(isset($where['filter_by_status']) &&  $where['filter_by_status'] != '')
        { 

            $filter_status    = $where['filter_by_status'];
            if($filter_status == "All")
            {
 
            $where_condition = "(view_vendor_master_log.final_status = 'clear' or view_vendor_master_log.final_status = 'possible match' or view_vendor_master_log.final_status = 'approve')" ;
            }
            elseif ($filter_status == "clear") {
                $where_condition = "view_vendor_master_log.final_status = 'clear'" ;
            }
            elseif ($filter_status == "possible match") {
                $where_condition = "view_vendor_master_log.final_status = 'possible match'" ;
            }
            elseif ($filter_status == "approved") {
                $where_condition = "view_vendor_master_log.final_status = 'approve'" ;
            }

             $this->db->where($where_condition); 
        }


        if(isset($where['start_date']) &&  $where['start_date'] != '' && isset($where['end_date']) &&  $where['end_date'] != '')
        { 

            $start_date  =  $where['start_date'];
            $end_date  =  $where['end_date'];
             
            $where3 = "DATE_FORMAT(`view_vendor_master_log`.`modified_on`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

            $this->db->where($where3); 

        }

        if(is_array($where) && $where['search']['value'] != "" )
        {
            $this->db->group_start();
            $this->db->like('candidates_info.cmp_ref_no', $where['search']['value']);
            
            $this->db->or_like('candidates_info.ClientRefNumber', $where['search']['value']);

            $this->db->or_like('courtver.court_com_ref', $where['search']['value']);

            $this->db->or_like('candidates_info.CandidateName', $where['search']['value']);

            $this->db->or_like('view_vendor_master_log.trasaction_id', $where['search']['value']);
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
            $this->db->order_by('view_vendor_master_log.trasaction_id','ASC');
        }
   
        $this->db->limit($where['length'],$where['start']);

        $result = $this->db->get();
  //  print_r($this->db->last_query());
       return $result->result_array();
 
      
    }

    public function court_case_list_closed_count($where_array = array(),$where,$columns)
    {

        $this->db->select("view_vendor_master_log.*,CandidateName,clients.clientname,courtver.court_com_ref,courtver.address_type,courtver.street_address,courtver.vendor_list_mode,courtver.city,courtver.state,courtver.pincode,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select user_name from vendor_executive_login where vendor_executive_login.id = view_vendor_master_log.has_case_id limit 1) as vendor_executive_id,candidates_info.ClientRefNumber as client_ref_no");

        $this->db->from('view_vendor_master_log');

        $this->db->join('courtver_vendor_log','(courtver_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "courtver" and view_vendor_master_log.component_tbl_id = 5)');

        $this->db->join('courtver','courtver.id = courtver_vendor_log.case_id');

        $this->db->join('candidates_info','candidates_info.id = courtver.candsid');
       
        $this->db->join("clients",'clients.id = courtver.clientid');


        $this->db->where($where_array);

        if(isset($where['filter_by_status']) &&  $where['filter_by_status'] != '')
        { 

            $filter_status    = $where['filter_by_status'];
            if($filter_status == "All")
            {
 
            $where_condition = "(view_vendor_master_log.final_status = 'clear' or view_vendor_master_log.final_status = 'possible match' or view_vendor_master_log.final_status = 'approve')" ;
            }
            elseif ($filter_status == "clear") {
                $where_condition = "view_vendor_master_log.final_status = 'clear'" ;
            }
            elseif ($filter_status == "possible match") {
                $where_condition = "view_vendor_master_log.final_status = 'possible match'" ;
            }
            elseif ($filter_status == "approved") {
                $where_condition = "view_vendor_master_log.final_status = 'approve'" ;
            }

             $this->db->where($where_condition); 
        }


        if(isset($where['start_date']) &&  $where['start_date'] != '' && isset($where['end_date']) &&  $where['end_date'] != '')
        { 

            $start_date  =  $where['start_date'];
            $end_date  =  $where['end_date'];
             
            $where3 = "DATE_FORMAT(`view_vendor_master_log`.`modified_on`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

            $this->db->where($where3); 

        }

        if(is_array($where) && $where['search']['value'] != "" )
        {
            $this->db->group_start();
            $this->db->like('candidates_info.cmp_ref_no', $where['search']['value']);
            
            $this->db->or_like('candidates_info.ClientRefNumber', $where['search']['value']);

            $this->db->or_like('courtver.court_com_ref', $where['search']['value']);

            $this->db->or_like('candidates_info.CandidateName', $where['search']['value']);

            $this->db->or_like('view_vendor_master_log.trasaction_id', $where['search']['value']);
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
            $this->db->order_by('view_vendor_master_log.trasaction_id','ASC');
        }
   
   
        $result = $this->db->get();
  //  print_r($this->db->last_query());
       return $result->result_array();
 
      
    }
    
    public function get_vendor_assign_users($tableName, $return_as_strict_row,$select_array, $where_array=array())
    {

        $this->db->select($select_array);

        $this->db->from($tableName);



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
   

   
    public function select_vendor_result_log($where_array,$id)
    {

        $this->db->select("courtver.*,view_vendor_master_log.*,courtver.id as court_id,(select vendor_name from vendors where vendors.id= courtver_vendor_log.vendor_id) as vendor_name,(select user_name from user_profile where id = view_vendor_master_log.allocated_by) as allocated_by,(select user_name from user_profile where id = view_vendor_master_log.approval_by) as approval_by,candidates_info.CandidateName,candidates_info.ClientRefNumber,CandidatesContactNumber,candidates_info.cmp_ref_no,candidates_info.CandidateName,(select clientname from clients where clients.id = courtver.clientid limit 1) as clientname,
            (select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,courtver.iniated_date,candidates_info.caserecddate,candidates_info.NameofCandidateFather,candidates_info.DateofBirth,court_com_ref,(select generate from vendors where vendors.id = courtver.vendor_id) as generate");



        $this->db->from('view_vendor_master_log');
        $this->db->join('courtver_vendor_log','courtver_vendor_log.id = view_vendor_master_log.case_id');

        $this->db->join('courtver','courtver.id = courtver_vendor_log.case_id');

        $this->db->join("candidates_info",'candidates_info.id = courtver.candsid');

        $this->db->where($where_array);

        if(!empty($id))
        {
             $this->db->where('view_vendor_master_log.id',$id);
        }

        $result  = $this->db->get();
     
        return $result->result_array();
    }

  /*	public function upload_vendor_reject($tableName,$updateArray,$where_arry)
	{		

		$this->db->where($where_arry);
	 	$this->db->update($tableName,$updateArray);
		return $this->db->affected_rows();
	}
  
	public function select_address_vendor_log_id($tableName,$where_arry = array())
	{
		$this->db->select("case_id");

		$this->db->from($tableName);


		if($where_arry)
		{
			$this->db->where($where_arry);
		}
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}
    */

    public function select_file_from_admin($select_array,$where_array)
    {
        $this->db->select($select_array);

        $this->db->from('courtver_files');

        $this->db->where($where_array);

        $this->db->order_by('id', 'desc');
        
        $result  = $this->db->get();

        record_db_error($this->db->last_query());
        
        return $result->result_array();
    }

    public function get_all_court_by_vendor($where)
    {   

        $this->db->select("courtver_vendor_log.modified_on as vendor_assign_on,courtver.court_com_ref as component_ref_no,view_vendor_master_log.trasaction_id,view_vendor_master_log.vendor_remark,view_vendor_master_log.final_status,view_vendor_master_log.vendor_actual_status,candidates_info.CandidateName,candidates_info.NameofCandidateFather,candidates_info.DateofBirth,courtver.street_address,courtver.city,courtver.pincode,courtver.state,(select vendor_name from vendors where vendors.id = courtver.vendor_id limit 1) as vendor_name,courtver.vendor_id");

        $this->db->from('courtver');

        $this->db->join("candidates_info",'candidates_info.id = courtver.candsid');

        $this->db->join("courtver_vendor_log",'courtver_vendor_log.case_id = courtver.id and courtver_vendor_log.status = 1');

       
        $this->db->join("view_vendor_master_log","(view_vendor_master_log.case_id = courtver_vendor_log.id and view_vendor_master_log.component = 'courtver')");
            

        if($where)
        {
            $this->db->where($where);
        }

       
        $this->db->order_by('courtver.id', 'ASC');
        
        $result = $this->db->get();

        record_db_error($this->db->last_query());

        return $result->result_array();
    }

    public function get_all_court_by_vendor_closed($where,$where3,$where_condition)
    {   

        $this->db->select("courtver_vendor_log.modified_on as vendor_assign_on,courtver.court_com_ref as component_ref_no,view_vendor_master_log.trasaction_id,view_vendor_master_log.vendor_remark,view_vendor_master_log.final_status,view_vendor_master_log.vendor_actual_status,candidates_info.CandidateName,candidates_info.NameofCandidateFather,candidates_info.DateofBirth,courtver.street_address,courtver.city,courtver.pincode,courtver.state,(select vendor_name from vendors where vendors.id = courtver.vendor_id limit 1) as vendor_name,courtver.vendor_id");

        $this->db->from('courtver');

        $this->db->join("candidates_info",'candidates_info.id = courtver.candsid');

        $this->db->join("courtver_vendor_log",'courtver_vendor_log.case_id = courtver.id and courtver_vendor_log.status = 1');

       
        $this->db->join("view_vendor_master_log","(view_vendor_master_log.case_id = courtver_vendor_log.id and view_vendor_master_log.component = 'courtver')");
            
        if($where)
        {
            $this->db->where($where);
        }
        
        if($where3 != "")
        {
            $this->db->where($where3);
        }

        if($where_condition != "")
        {
            $this->db->where($where_condition);
        }
       
        $this->db->order_by('courtver.id', 'ASC');
        
        $result = $this->db->get();
        record_db_error($this->db->last_query());

        return $result->result_array();   
    }  	
}
?>