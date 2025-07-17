<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Addrver_model extends CI_Model
{
	function __construct()
    {
		$this->tableName = 'addrver';

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

     public function select_file_from_admin($select_array,$where_array)
    {
        $this->db->select($select_array);

        $this->db->from('addrver_files');

        $this->db->where($where_array);

        $this->db->where('(addrver_files.type = 0  or addrver_files.type = 2)');

        $this->db->order_by('id', 'desc');
        
        $result  = $this->db->get();

        record_db_error($this->db->last_query());
        
        return $result->result_array();
    }

    public function address_case_list($where_array = array(),$where,$columns)
    {
        $this->db->select("view_vendor_master_log.*,CandidateName,clients.clientname,addrver.add_com_ref,addrver.address,addrver.   vendor_list_mode,addrver.city,addrver.state,addrver.pincode,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select concat(vendor_executive_login.first_name,' ',vendor_executive_login.last_name) from vendor_executive_login where vendor_executive_login.id = view_vendor_master_log.has_case_id limit 1) as vendor_executive_name,candidates_info.ClientRefNumber as client_ref_no");
        $this->db->from('view_vendor_master_log');
      
        $this->db->join('address_vendor_log','(address_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "addrver" and view_vendor_master_log.component_tbl_id = 1)');
        $this->db->join('addrver','addrver.id = address_vendor_log.case_id');
        $this->db->join('candidates_info','candidates_info.id = addrver.candsid');
       
        $this->db->join("clients",'clients.id = addrver.clientid');


        $this->db->where($where_array);

        if(is_array($where) && $where['search']['value'] != "" )
        {
            $this->db->group_start();
            
            $this->db->like('candidates_info.cmp_ref_no', $where['search']['value']);

            $this->db->or_like('candidates_info.CandidatesContactNumber', $where['search']['value']);

            $this->db->or_like('candidates_info.ContactNo1', $where['search']['value']);

            $this->db->or_like('candidates_info.ContactNo2', $where['search']['value']);

            $this->db->or_like('candidates_info.ClientRefNumber', $where['search']['value']);

            $this->db->or_like('addrver.add_com_ref', $where['search']['value']);

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
           $this->db->order_by('view_vendor_master_log.modified_on','ASC');
        }

        $this->db->limit($where['length'],$where['start']);
   
        $result = $this->db->get();
    
       return $result->result_array();
 
       
    }

    public function address_case_list_count($where_array = array(),$where,$columns)
    {
        $this->db->select("view_vendor_master_log.*,CandidateName,clients.clientname,addrver.add_com_ref,addrver.address,addrver.vendor_list_mode,addrver.city,addrver.state,addrver.pincode,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select concat(vendor_executive_login.first_name,' ',vendor_executive_login.last_name) from vendor_executive_login where vendor_executive_login.id = view_vendor_master_log.has_case_id limit 1) as vendor_executive_name,candidates_info.ClientRefNumber as client_ref_no");

        $this->db->from('view_vendor_master_log');
      
        $this->db->join('address_vendor_log','(address_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "addrver" and view_vendor_master_log.component_tbl_id = 1)');

        $this->db->join('addrver','addrver.id = address_vendor_log.case_id');

        $this->db->join('candidates_info','candidates_info.id = addrver.candsid');
       
        $this->db->join("clients",'clients.id = addrver.clientid');


        $this->db->where($where_array);

        if(is_array($where) && $where['search']['value'] != "" )
        {
            $this->db->group_start();

            $this->db->like('candidates_info.cmp_ref_no', $where['search']['value']);

            $this->db->or_like('candidates_info.CandidatesContactNumber', $where['search']['value']);

            $this->db->or_like('candidates_info.ContactNo1', $where['search']['value']);

            $this->db->or_like('candidates_info.ContactNo2', $where['search']['value']);

            $this->db->or_like('candidates_info.ClientRefNumber', $where['search']['value']);

            $this->db->or_like('addrver.add_com_ref', $where['search']['value']);

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
           $this->db->order_by('view_vendor_master_log.modified_on','ASC');
        }

   
        $result = $this->db->get();
    
       return $result->result_array();
 
       
    }

    
    public function address_case_list_insufficiency($where_array = array(),$where,$columns)
    {
        $where_condition = "(view_vendor_master_log.final_status = 'candidate shifted' or view_vendor_master_log.final_status = 'unable to verify' or view_vendor_master_log.final_status = 'denied verification' or view_vendor_master_log.final_status = 'resigned' or view_vendor_master_log.final_status = 'candidate not responding')" ;

        $this->db->select("view_vendor_master_log.*,CandidateName,clients.clientname,addrver.add_com_ref,addrver.address,addrver.vendor_list_mode,addrver.city,addrver.state,addrver.pincode,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select user_name from vendor_executive_login where vendor_executive_login.id = view_vendor_master_log.has_case_id limit 1) as vendor_executive_id,candidates_info.ClientRefNumber as client_ref_no");

        $this->db->from('view_vendor_master_log');
    
        $this->db->join('address_vendor_log','(address_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "addrver" and view_vendor_master_log.component_tbl_id = 1)');

        $this->db->join('addrver','addrver.id = address_vendor_log.case_id');

        $this->db->join('candidates_info','candidates_info.id = addrver.candsid');
       
        $this->db->join("clients",'clients.id = addrver.clientid');


        $this->db->where($where_array);
     
         $this->db->where($where_condition);

        if(is_array($where) && $where['search']['value'] != "" )
        {
           
            $this->db->group_start();

            $this->db->like('candidates_info.cmp_ref_no', $where['search']['value']);

            $this->db->or_like('candidates_info.CandidatesContactNumber', $where['search']['value']);

            $this->db->or_like('candidates_info.ContactNo1', $where['search']['value']);

            $this->db->or_like('candidates_info.ContactNo2', $where['search']['value']);

            $this->db->or_like('candidates_info.ClientRefNumber', $where['search']['value']);

            $this->db->or_like('addrver.add_com_ref', $where['search']['value']);

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
           $this->db->order_by('view_vendor_master_log.modified_on','ASC');
        }

        $this->db->limit($where['length'],$where['start']);
   
        $result = $this->db->get();

        return $result->result_array();
 
       
    }

    public function address_case_list_insufficiency_count($where_array = array(),$where,$columns)
    {
        $where_condition = "(view_vendor_master_log.final_status = 'candidate shifted' or view_vendor_master_log.final_status = 'unable to verify' or view_vendor_master_log.final_status = 'denied verification' or view_vendor_master_log.final_status = 'resigned' or view_vendor_master_log.final_status = 'candidate not responding')" ;

        $this->db->select("view_vendor_master_log.*,CandidateName,clients.clientname,addrver.add_com_ref,addrver.address,addrver.vendor_list_mode,addrver.city,addrver.state,addrver.pincode,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select user_name from vendor_executive_login where vendor_executive_login.id = view_vendor_master_log.has_case_id limit 1) as vendor_executive_id,candidates_info.ClientRefNumber as client_ref_no");

        $this->db->from('view_vendor_master_log');
    
        $this->db->join('address_vendor_log','(address_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "addrver" and view_vendor_master_log.component_tbl_id = 1)');

        $this->db->join('addrver','addrver.id = address_vendor_log.case_id');

        $this->db->join('candidates_info','candidates_info.id = addrver.candsid');
       
        $this->db->join("clients",'clients.id = addrver.clientid');


        $this->db->where($where_array);
     
         $this->db->where($where_condition);

        if(is_array($where) && $where['search']['value'] != "" )
        {
           
            $this->db->group_start();

            $this->db->like('candidates_info.cmp_ref_no', $where['search']['value']);

            $this->db->or_like('candidates_info.CandidatesContactNumber', $where['search']['value']);

            $this->db->or_like('candidates_info.ContactNo1', $where['search']['value']);

            $this->db->or_like('candidates_info.ContactNo2', $where['search']['value']);

            $this->db->or_like('candidates_info.ClientRefNumber', $where['search']['value']);

            $this->db->or_like('addrver.add_com_ref', $where['search']['value']);

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
           $this->db->order_by('view_vendor_master_log.modified_on','ASC');
        }
   
        $result = $this->db->get();
    
       return $result->result_array();
 
       
    }

    public function address_case_list_closed($where_array = array(),$where,$columns)
    {

       
        $this->db->select("view_vendor_master_log.*,CandidateName,clients.clientname,addrver.add_com_ref,addrver.address,addrver.vendor_list_mode,addrver.city,addrver.state,addrver.pincode,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select user_name from vendor_executive_login where vendor_executive_login.id = view_vendor_master_log.has_case_id limit 1) as vendor_executive_id,candidates_info.ClientRefNumber as client_ref_no");

        $this->db->from('view_vendor_master_log');

        $this->db->join('address_vendor_log','(address_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "addrver" and view_vendor_master_log.component_tbl_id = 1)');

        $this->db->join('addrver','addrver.id = address_vendor_log.case_id');

        $this->db->join('candidates_info','candidates_info.id = addrver.candsid');
       
        $this->db->join("clients",'clients.id = addrver.clientid');


        $this->db->where($where_array);

        if(isset($where['filter_by_status']) &&  $where['filter_by_status'] != '')
        { 

            $filter_status    = $where['filter_by_status'];
            if($filter_status == "All")
            {
 
            $where_condition = "(view_vendor_master_log.final_status = 'clear' or view_vendor_master_log.final_status = 'approve')" ;
            }
            elseif ($filter_status == "clear") {
                $where_condition = "view_vendor_master_log.final_status = 'clear'" ;
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

            $this->db->or_like('candidates_info.CandidatesContactNumber', $where['search']['value']);

            $this->db->or_like('candidates_info.ContactNo1', $where['search']['value']);

            $this->db->or_like('candidates_info.ContactNo2', $where['search']['value']);
            
            $this->db->or_like('candidates_info.ClientRefNumber', $where['search']['value']);

            $this->db->or_like('addrver.add_com_ref', $where['search']['value']);

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
           $this->db->order_by('view_vendor_master_log.modified_on','ASC');
        }

        $this->db->limit($where['length'],$where['start']);
   
        $result = $this->db->get();

       return $result->result_array();
 
       
    }

    public function address_case_list_closed_count($where_array = array(),$where,$columns)
    {

       
        $this->db->select("view_vendor_master_log.*,CandidateName,clients.clientname,addrver.add_com_ref,addrver.address,addrver.vendor_list_mode,addrver.city,addrver.state,addrver.pincode,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select user_name from vendor_executive_login where vendor_executive_login.id = view_vendor_master_log.has_case_id limit 1) as vendor_executive_id,candidates_info.ClientRefNumber as client_ref_no");

        $this->db->from('view_vendor_master_log');

        $this->db->join('address_vendor_log','(address_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "addrver" and view_vendor_master_log.component_tbl_id = 1)');

        $this->db->join('addrver','addrver.id = address_vendor_log.case_id');

        $this->db->join('candidates_info','candidates_info.id = addrver.candsid');
       
        $this->db->join("clients",'clients.id = addrver.clientid');


        $this->db->where($where_array);

        if(isset($where['filter_by_status']) &&  $where['filter_by_status'] != '')
        { 

            $filter_status    = $where['filter_by_status'];
            if($filter_status == "All")
            {
 
            $where_condition = "(view_vendor_master_log.final_status = 'clear' or view_vendor_master_log.final_status = 'approve')" ;
            }
            elseif ($filter_status == "clear") {
                $where_condition = "view_vendor_master_log.final_status = 'clear'" ;
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

            $this->db->or_like('candidates_info.CandidatesContactNumber', $where['search']['value']);

            $this->db->or_like('candidates_info.ContactNo1', $where['search']['value']);

            $this->db->or_like('candidates_info.ContactNo2', $where['search']['value']);
            
            $this->db->or_like('candidates_info.ClientRefNumber', $where['search']['value']);

            $this->db->or_like('addrver.add_com_ref', $where['search']['value']);

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
           $this->db->order_by('view_vendor_master_log.modified_on','ASC');
        }
   
        $result = $this->db->get();

       return $result->result_array();
 
       
    }
    
    public function get_vendor_assign_users($tableName, $return_as_strict_row,$select_array, $where_array=array())
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

    public function select_vendor_result_log($where_array,$id)
    {

        $this->db->select("addrver.id as address_id,addrver.*,view_vendor_master_log.*,(select vendor_name from vendors where vendors.id= address_vendor_log.vendor_id) as vendor_name,(select user_name from user_profile where id = view_vendor_master_log.allocated_by) as allocated_by,(select user_name from user_profile where id = view_vendor_master_log.approval_by) as approval_by,candidates_info.CandidateName,candidates_info.ClientRefNumber,CandidatesContactNumber,candidates_info.cmp_ref_no,candidates_info.CandidateName,(select clientname from clients where clients.id = addrver.clientid limit 1) as clientname,
            (select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,addrver.iniated_date,candidates_info.caserecddate,add_com_ref,candidates_info.ContactNo1,candidates_info.ContactNo2");



        $this->db->from('view_vendor_master_log');
        $this->db->join('address_vendor_log','address_vendor_log.id = view_vendor_master_log.case_id');

        $this->db->join('addrver','addrver.id = address_vendor_log.case_id');

        $this->db->join("candidates_info",'candidates_info.id = addrver.candsid');

      
        $this->db->where($where_array);

        if(!empty($id))
        {
             $this->db->where('view_vendor_master_log.id',$id);
        }

        $result  = $this->db->get();
     
        return $result->result_array();
    }

  

    public function select_vendor_activity_log($where_array)
    {

        $this->db->select("vendor_activity_log.*");

        $this->db->from('vendor_activity_log');
        
        $this->db->where($where_array);

        $result  = $this->db->get();
     
        return $result->result_array();
    }
   	
}
?>