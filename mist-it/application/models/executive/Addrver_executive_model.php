<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Addrver_executive_model extends CI_Model
{
	function __construct()
    {
		$this->tableName = 'addrver';

		$this->primaryKey = 'id';
	}
     

    public function address_case_list_executive($where_array = array(),$where,$columns)
    {
        $this->db->select("view_vendor_master_log.*,CandidateName,clients.clientname,addrver.add_com_ref,addrver.address,addrver.   vendor_list_mode,addrver.city,addrver.state,addrver.pincode,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,candidates_info.ClientRefNumber as client_ref_no");
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
           $this->db->order_by('view_vendor_master_log.has_assigned_on','ASC');
        }

        $this->db->limit($where['length'],$where['start']);
   
        $result = $this->db->get();
    
       return $result->result_array();
 
       
    }

    public function address_case_list_executive_count($where_array = array(),$where,$columns)
    {
        $this->db->select("view_vendor_master_log.*,CandidateName,clients.clientname,addrver.add_com_ref,addrver.address,addrver.   vendor_list_mode,addrver.city,addrver.state,addrver.pincode,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,candidates_info.ClientRefNumber as client_ref_no");

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
           $this->db->order_by('view_vendor_master_log.has_assigned_on','ASC');
        }

   
        $result = $this->db->get();
    
       return $result->result_array();
 
       
    } 



    public function address_case_list_insufficiency_executive($where_array = array(),$where,$columns)
    {
        $insufficiency_condition = "(view_vendor_master_log.final_status = 'candidate shifted' or view_vendor_master_log.final_status = 'unable to verify' or view_vendor_master_log.final_status = 'denied verification' or view_vendor_master_log.final_status = 'resigned' or view_vendor_master_log.final_status = 'candidate not responding')" ;

        $this->db->select("view_vendor_master_log.*,CandidateName,clients.clientname,addrver.add_com_ref,addrver.address,addrver.   vendor_list_mode,addrver.city,addrver.state,addrver.pincode,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,candidates_info.ClientRefNumber as client_ref_no");
        $this->db->from('view_vendor_master_log');
      
        $this->db->join('address_vendor_log','(address_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "addrver" and view_vendor_master_log.component_tbl_id = 1)');
        $this->db->join('addrver','addrver.id = address_vendor_log.case_id');
        $this->db->join('candidates_info','candidates_info.id = addrver.candsid');
       
        $this->db->join("clients",'clients.id = addrver.clientid');


        $this->db->where($where_array);

        $this->db->where($insufficiency_condition);


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
           $this->db->order_by('view_vendor_master_log.has_assigned_on','ASC');
        }

        $this->db->limit($where['length'],$where['start']);
   
        $result = $this->db->get();
    
       return $result->result_array();
 
       
    }

    public function address_case_list_insufficiency_executive_count($where_array = array(),$where,$columns)
    {
        $insufficiency_condition = "(view_vendor_master_log.final_status = 'candidate shifted' or view_vendor_master_log.final_status = 'unable to verify' or view_vendor_master_log.final_status = 'denied verification' or view_vendor_master_log.final_status = 'resigned' or view_vendor_master_log.final_status = 'candidate not responding')" ;


        $this->db->select("view_vendor_master_log.*,CandidateName,clients.clientname,addrver.add_com_ref,addrver.address,addrver.vendor_list_mode,addrver.city,addrver.state,addrver.pincode,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,candidates_info.ClientRefNumber as client_ref_no");

        $this->db->from('view_vendor_master_log');
      
        $this->db->join('address_vendor_log','(address_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "addrver" and view_vendor_master_log.component_tbl_id = 1)');

        $this->db->join('addrver','addrver.id = address_vendor_log.case_id');

        $this->db->join('candidates_info','candidates_info.id = addrver.candsid');
       
        $this->db->join("clients",'clients.id = addrver.clientid');


        $this->db->where($where_array);

        $this->db->where($insufficiency_condition);


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
           $this->db->order_by('view_vendor_master_log.has_assigned_on','ASC');
        }

   
        $result = $this->db->get();
    
       return $result->result_array();
 
       
    } 
    
    
    public function address_case_list_closed_executive($where_array = array(),$where,$columns)
    {
        $closed_condition = "(view_vendor_master_log.final_status = 'clear' or view_vendor_master_log.final_status = 'approved')" ;

        $this->db->select("view_vendor_master_log.*,CandidateName,clients.clientname,addrver.add_com_ref,addrver.address,addrver.   vendor_list_mode,addrver.city,addrver.state,addrver.pincode,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,candidates_info.ClientRefNumber as client_ref_no");
        $this->db->from('view_vendor_master_log');
      
        $this->db->join('address_vendor_log','(address_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "addrver" and view_vendor_master_log.component_tbl_id = 1)');
        $this->db->join('addrver','addrver.id = address_vendor_log.case_id');
        $this->db->join('candidates_info','candidates_info.id = addrver.candsid');
       
        $this->db->join("clients",'clients.id = addrver.clientid');


        $this->db->where($where_array);

        $this->db->where($closed_condition);


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
           $this->db->order_by('view_vendor_master_log.has_assigned_on','ASC');
        }

        $this->db->limit($where['length'],$where['start']);
   
        $result = $this->db->get();
    
       return $result->result_array();
 
       
    }

    public function address_case_list_closed_executive_count($where_array = array(),$where,$columns)
    {
        $closed_condition = "(view_vendor_master_log.final_status = 'clear' or view_vendor_master_log.final_status = 'approved')" ;


        $this->db->select("view_vendor_master_log.*,CandidateName,clients.clientname,addrver.add_com_ref,addrver.address,addrver.   vendor_list_mode,addrver.city,addrver.state,addrver.pincode,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,candidates_info.ClientRefNumber as client_ref_no");

        $this->db->from('view_vendor_master_log');
      
        $this->db->join('address_vendor_log','(address_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "addrver" and view_vendor_master_log.component_tbl_id = 1)');

        $this->db->join('addrver','addrver.id = address_vendor_log.case_id');

        $this->db->join('candidates_info','candidates_info.id = addrver.candsid');
       
        $this->db->join("clients",'clients.id = addrver.clientid');


        $this->db->where($where_array);

        $this->db->where($closed_condition);


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
           $this->db->order_by('view_vendor_master_log.has_assigned_on','ASC');
        }

   
        $result = $this->db->get();
    
       return $result->result_array();
 
       
    } 

    public function select_transaction_details_for_form_generation($where_array = array())
    {
        $this->db->select("view_vendor_master_log.*,addrver.add_com_ref,addrver.id as address_id,addrver.iniated_date,(select CandidateName from candidates_info where candidates_info.id = addrver.candsid) as candiates_name,(select CandidatesContactNumber from candidates_info where candidates_info.id = addrver.candsid) as CandidatesContactNumber,addrver.address,addrver.city,addrver.pincode,addrver.state,addrver.stay_from,addrver.stay_to");

        $this->db->from('view_vendor_master_log');
      
        $this->db->join('address_vendor_log','(address_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "addrver" and view_vendor_master_log.component_tbl_id = 1)');

        $this->db->join('addrver','addrver.id = address_vendor_log.case_id');

        $this->db->where($where_array);

        $result = $this->db->get();
    
       return $result->result_array();
 
       
    } 

    public function save($tableName,$arrdata,$arrwhere = array())
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

    public function status_count_address_executive($vendor_id,$vendor_executive_id,$params)
    {


        $this->db->select("CASE WHEN view_vendor_master_log.final_status =  'wip'  THEN 'WIP' END AS status_value,COUNT(view_vendor_master_log.final_status) as total");

        $this->db->from('view_vendor_master_log');

        $this->db->join("address_vendor_log",'(address_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "addrver" and view_vendor_master_log.component_tbl_id = 1)');

        $this->db->join("addrver",'addrver.id = address_vendor_log.case_id');


        $this->db->where('addrver.vendor_id',$vendor_id);

        $this->db->where('view_vendor_master_log.has_case_id',$vendor_executive_id);

        $this->db->group_by('view_vendor_master_log.final_status');

        $result = $this->db->get();

        record_db_error($this->db->last_query());

        $addrver_wip = $result->result_array();
        
 
        
        $this->db->select("CASE  WHEN view_vendor_master_log.final_status = 'candidate shifted' THEN 'Insufficiency' WHEN view_vendor_master_log.final_status = 'unable to verify' THEN 'Insufficiency'  WHEN view_vendor_master_log.final_status = 'denied verification' THEN 'Insufficiency' WHEN view_vendor_master_log.final_status = 'resigned' THEN 'Insufficiency' WHEN view_vendor_master_log.final_status = 'candidate not responding' THEN 'Insufficiency' END AS status_value,COUNT(view_vendor_master_log.final_status) as total");

        $this->db->from('view_vendor_master_log');

        $this->db->join("address_vendor_log",'(address_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "addrver" and view_vendor_master_log.component_tbl_id = 1)');

        $this->db->join("addrver",'addrver.id = address_vendor_log.case_id');
   
 
        $this->db->where('addrver.vendor_id',$vendor_id);

        $this->db->where('view_vendor_master_log.has_case_id',$vendor_executive_id);


        $this->db->group_by('view_vendor_master_log.final_status');

        $result = $this->db->get();
      
        record_db_error($this->db->last_query());

        $addrver_insuff = $result->result_array();

        

        $this->db->select("CASE WHEN  view_vendor_master_log.final_status =  'clear' THEN 'Closed' WHEN  view_vendor_master_log.final_status =  'approve' THEN 'Closed'  END AS status_value,COUNT(view_vendor_master_log.final_status) as total");


        $this->db->from('view_vendor_master_log');

        $this->db->join("address_vendor_log",'(address_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "addrver" and view_vendor_master_log.component_tbl_id = 1)');

        $this->db->join("addrver",'addrver.id = address_vendor_log.case_id');
    
        $this->db->where('addrver.vendor_id',$vendor_id);

        $this->db->where('view_vendor_master_log.has_case_id',$vendor_executive_id);

        $this->db->group_by('view_vendor_master_log.final_status');


        $result = $this->db->get();
 
        record_db_error($this->db->last_query());

        $addrver_closed = $result->result_array();


        $this->db->select("CASE WHEN DATE(view_vendor_master_log.has_assigned_on) = CURDATE()  THEN 'Current' END AS status_value,COUNT(view_vendor_master_log.has_assigned_on) as total");

        $this->db->from('view_vendor_master_log');

        $this->db->join("address_vendor_log",'(address_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "addrver" and view_vendor_master_log.component_tbl_id = 1)');

        $this->db->join("addrver",'addrver.id = address_vendor_log.case_id');


        $this->db->where('addrver.vendor_id',$vendor_id);

        $this->db->where('view_vendor_master_log.has_case_id',$vendor_executive_id);

        $this->db->group_by('view_vendor_master_log.has_assigned_on');

        $result = $this->db->get();

        record_db_error($this->db->last_query());
 
        $addrver_current = $result->result_array();


        $results_addrver_wip = array_reduce($addrver_wip, function($result, $item) {
            if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
            $result[$item['status_value']] += $item['total'];
            return $result;
        }, array());

        $results_addrver_insuff = array_reduce($addrver_insuff, function($result, $item) {
            if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
            $result[$item['status_value']] += $item['total'];
            return $result;
        }, array());


        $results_addrver_closed = array_reduce($addrver_closed, function($result, $item) {
            if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
            $result[$item['status_value']] += $item['total'];
            return $result;
        }, array());


        $results_addrver_current = array_reduce($addrver_current, function($result, $item) {
            if (!isset($result[$item['status_value']])) $result[$item['status_value']] = 0;
            $result[$item['status_value']] += $item['total'];
            return $result;
        }, array());


        $return = array();

        foreach ($results_addrver_wip as $key => $value) {
            if($key == "WIP")
            {
                $return['address_'.strtolower($key)] =  $value;
            }
        }

        foreach ($results_addrver_insuff as $key => $value) {
            if($key == "Insufficiency")
            {
                $return['address_'.strtolower($key)] = $value;
            
            }
        }

        foreach ($results_addrver_closed as $key => $value) {
            if($key == "Closed")
            {
                $return['address_'.strtolower($key)] = $value;
            }
        }

        foreach ($results_addrver_current as $key => $value) {
            if($key == "Current")
            {
                $return['address_'.strtolower($key)] = $value;
            }
        }
        
        return $return;

         
    }

    public function select_mobile_no($where_array = array())
    {
        $this->db->select("candidates_info.CandidatesContactNumber,candidates_info.ContactNo1,candidates_info.ContactNo2");

        $this->db->from('view_vendor_master_log');
      
        $this->db->join('address_vendor_log','(address_vendor_log.id = view_vendor_master_log.case_id and view_vendor_master_log.component = "addrver" and view_vendor_master_log.component_tbl_id = 1)');

        $this->db->join('addrver','addrver.id = address_vendor_log.case_id');

        $this->db->join('candidates_info','candidates_info.id = addrver.candsid');

        $this->db->where($where_array);

        $result = $this->db->get();
    
       return $result->result_array();
 
    } 

   
}
?>