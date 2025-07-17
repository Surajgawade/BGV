<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Narcver_model extends CI_Model
{
	function __construct()
    {
		$this->tableName = 'drug_narcotis';

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

    public function narcver_case_list($where_array = array(),$where,$columns)
    {
       
        $this->db->select("view_vendor_master_log.*,CandidateName,clients.clientname,drug_narcotis.drug_com_ref,drug_narcotis.street_address,drug_narcotis.vendor_list_mode,drug_narcotis.city,drug_narcotis.state,drug_narcotis.pincode,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,candidates_info.ClientRefNumber as client_ref_no");
        $this->db->from('view_vendor_master_log');
        $this->db->join('drug_narcotis_vendor_log','drug_narcotis_vendor_log.id = view_vendor_master_log.case_id');
        $this->db->join('drug_narcotis','drug_narcotis.id = drug_narcotis_vendor_log.case_id');
        $this->db->join('candidates_info','candidates_info.id = drug_narcotis.candsid');
       
        $this->db->join("clients",'clients.id = drug_narcotis.clientid');


        $this->db->where($where_array);

        if(is_array($where) && $where['search']['value'] != "" )
        {
            $this->db->group_start();
            

            $this->db->like('candidates_info.CandidatesContactNumber', $where['search']['value']);

            $this->db->or_like('candidates_info.ContactNo1', $where['search']['value']);

            $this->db->or_like('candidates_info.ContactNo2', $where['search']['value']);

            $this->db->or_like('candidates_info.ClientRefNumber', $where['search']['value']);

            $this->db->or_like('drug_narcotis.drug_com_ref', $where['search']['value']);

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

    public function narcver_case_list_count($where_array = array(),$where,$columns)
    {
       
        $this->db->select("view_vendor_master_log.*,CandidateName,clients.clientname,drug_narcotis.drug_com_ref,drug_narcotis.street_address,drug_narcotis.vendor_list_mode,drug_narcotis.city,drug_narcotis.state,drug_narcotis.pincode,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity) as entity_name,candidates_info.ClientRefNumber as client_ref_no");
        $this->db->from('view_vendor_master_log');
        $this->db->join('drug_narcotis_vendor_log','drug_narcotis_vendor_log.id = view_vendor_master_log.case_id');
        $this->db->join('drug_narcotis','drug_narcotis.id = drug_narcotis_vendor_log.case_id');
        $this->db->join('candidates_info','candidates_info.id = drug_narcotis.candsid');
       
        $this->db->join("clients",'clients.id = drug_narcotis.clientid');


        $this->db->where($where_array);

        if(is_array($where) && $where['search']['value'] != "" )
        {
            $this->db->group_start();
            

            $this->db->like('candidates_info.CandidatesContactNumber', $where['search']['value']);

            $this->db->or_like('candidates_info.ContactNo1', $where['search']['value']);

            $this->db->or_like('candidates_info.ContactNo2', $where['search']['value']);

            $this->db->or_like('candidates_info.ClientRefNumber', $where['search']['value']);

            $this->db->or_like('drug_narcotis.drug_com_ref', $where['search']['value']);

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

    
    public function narcver_case_list_insufficiency($where_array = array(),$where,$columns)
    {
       
        $this->db->select("view_vendor_master_log.*,CandidateName,clients.clientname,drug_narcotis.drug_com_ref,drug_narcotis.street_address,drug_narcotis.vendor_list_mode,drug_narcotis.city,drug_narcotis.state,drug_narcotis.pincode,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,candidates_info.ClientRefNumber as client_ref_no");
        $this->db->from('view_vendor_master_log');
        $this->db->join('drug_narcotis_vendor_log','drug_narcotis_vendor_log.id = view_vendor_master_log.case_id');
        $this->db->join('drug_narcotis','drug_narcotis.id = drug_narcotis_vendor_log.case_id');
        $this->db->join('candidates_info','candidates_info.id = drug_narcotis.candsid');
       
        $this->db->join("clients",'clients.id = drug_narcotis.clientid');


        $this->db->where($where_array);

        if(is_array($where) && $where['search']['value'] != "" )
        {
            $this->db->group_start();
            

            $this->db->like('candidates_info.CandidatesContactNumber', $where['search']['value']);

            $this->db->or_like('candidates_info.ContactNo1', $where['search']['value']);

            $this->db->or_like('candidates_info.ContactNo2', $where['search']['value']);

            $this->db->or_like('candidates_info.ClientRefNumber', $where['search']['value']);

            $this->db->or_like('drug_narcotis.drug_com_ref', $where['search']['value']);

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

    public function narcver_case_list_insufficiency_count($where_array = array(),$where,$columns)
    {
       
        $this->db->select("view_vendor_master_log.*,CandidateName,clients.clientname,drug_narcotis.drug_com_ref,drug_narcotis.street_address,drug_narcotis.vendor_list_mode,drug_narcotis.city,drug_narcotis.state,drug_narcotis.pincode,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity) as entity_name,candidates_info.ClientRefNumber as client_ref_no");
        $this->db->from('view_vendor_master_log');
        $this->db->join('drug_narcotis_vendor_log','drug_narcotis_vendor_log.id = view_vendor_master_log.case_id');
        $this->db->join('drug_narcotis','drug_narcotis.id = drug_narcotis_vendor_log.case_id');
        $this->db->join('candidates_info','candidates_info.id = drug_narcotis.candsid');
       
        $this->db->join("clients",'clients.id = drug_narcotis.clientid');


        $this->db->where($where_array);

        if(is_array($where) && $where['search']['value'] != "" )
        {
            $this->db->group_start();
            

            $this->db->like('candidates_info.CandidatesContactNumber', $where['search']['value']);

            $this->db->or_like('candidates_info.ContactNo1', $where['search']['value']);

            $this->db->or_like('candidates_info.ContactNo2', $where['search']['value']);

            $this->db->or_like('candidates_info.ClientRefNumber', $where['search']['value']);

            $this->db->or_like('drug_narcotis.drug_com_ref', $where['search']['value']);

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

    public function narcver_case_list_closed($where_array = array(),$where,$columns)
    {
       
        $this->db->select("view_vendor_master_log.*,CandidateName,clients.clientname,drug_narcotis.drug_com_ref,drug_narcotis.street_address,drug_narcotis.vendor_list_mode,drug_narcotis.city,drug_narcotis.state,drug_narcotis.pincode,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,candidates_info.ClientRefNumber as client_ref_no");
        $this->db->from('view_vendor_master_log');
        $this->db->join('drug_narcotis_vendor_log','drug_narcotis_vendor_log.id = view_vendor_master_log.case_id');
        $this->db->join('drug_narcotis','drug_narcotis.id = drug_narcotis_vendor_log.case_id');
        $this->db->join('candidates_info','candidates_info.id = drug_narcotis.candsid');
       
        $this->db->join("clients",'clients.id = drug_narcotis.clientid');


        $this->db->where($where_array);

        if(isset($where['filter_by_status']) &&  $where['filter_by_status'] != '')
        { 

            $filter_status    = $where['filter_by_status'];
            if($filter_status == "All")
            {
 
            $where_condition = "(view_vendor_master_log.final_status = 'closed' or view_vendor_master_log.final_status = 'approve')" ;
            }
            elseif ($filter_status == "closed") {
                $where_condition = "view_vendor_master_log.final_status = 'closed'" ;
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
            

            $this->db->like('candidates_info.CandidatesContactNumber', $where['search']['value']);

            $this->db->or_like('candidates_info.ContactNo1', $where['search']['value']);

            $this->db->or_like('candidates_info.ContactNo2', $where['search']['value']);

            $this->db->or_like('candidates_info.ClientRefNumber', $where['search']['value']);

            $this->db->or_like('drug_narcotis.drug_com_ref', $where['search']['value']);

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

    public function narcver_case_list_closed_count($where_array = array(),$where,$columns)
    {
       
        $this->db->select("view_vendor_master_log.*,CandidateName,clients.clientname,drug_narcotis.drug_com_ref,drug_narcotis.street_address,drug_narcotis.vendor_list_mode,drug_narcotis.city,drug_narcotis.state,drug_narcotis.pincode,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity) as entity_name,candidates_info.ClientRefNumber as client_ref_no");
        $this->db->from('view_vendor_master_log');
        $this->db->join('drug_narcotis_vendor_log','drug_narcotis_vendor_log.id = view_vendor_master_log.case_id');
        $this->db->join('drug_narcotis','drug_narcotis.id = drug_narcotis_vendor_log.case_id');
        $this->db->join('candidates_info','candidates_info.id = drug_narcotis.candsid');
       
        $this->db->join("clients",'clients.id = drug_narcotis.clientid');


        $this->db->where($where_array);

        if(isset($where['filter_by_status']) &&  $where['filter_by_status'] != '')
        { 

            $filter_status    = $where['filter_by_status'];
            if($filter_status == "All")
            {
 
            $where_condition = "(view_vendor_master_log.final_status = 'closed' or view_vendor_master_log.final_status = 'approve')" ;
            }
            elseif ($filter_status == "closed") {
                $where_condition = "view_vendor_master_log.final_status = 'closed'" ;
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
            

            $this->db->like('candidates_info.CandidatesContactNumber', $where['search']['value']);

            $this->db->or_like('candidates_info.ContactNo1', $where['search']['value']);

            $this->db->or_like('candidates_info.ContactNo2', $where['search']['value']);

            $this->db->or_like('candidates_info.ClientRefNumber', $where['search']['value']);

            $this->db->or_like('drug_narcotis.drug_com_ref', $where['search']['value']);

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

        $this->db->select("drug_narcotis.id as drugs_id,drug_narcotis.*,drug_narcotis.has_case_id as executive_name,view_vendor_master_log.*,(select vendor_name from vendors where vendors.id= drug_narcotis_vendor_log.vendor_id) as vendor_name,(select user_name from user_profile where id = view_vendor_master_log.allocated_by) as allocated_by,(select user_name from user_profile where id = view_vendor_master_log.approval_by) as approval_by,candidates_info.CandidateName,candidates_info.ClientRefNumber,CandidatesContactNumber,candidates_info.cmp_ref_no,candidates_info.CandidateName,(select clientname from clients where clients.id = drug_narcotis.clientid limit 1) as clientname,
            (select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,drug_narcotis.iniated_date,candidates_info.caserecddate,candidates_info.ContactNo1,candidates_info.ContactNo2,drug_com_ref");



        $this->db->from('view_vendor_master_log');
        $this->db->join('drug_narcotis_vendor_log','drug_narcotis_vendor_log.id = view_vendor_master_log.case_id');

        $this->db->join('drug_narcotis','drug_narcotis.id = drug_narcotis_vendor_log.case_id');


        $this->db->join("candidates_info",'candidates_info.id = drug_narcotis.candsid');

        $this->db->where($where_array);

        if(!empty($id))
        {
             $this->db->where('view_vendor_master_log.id',$id);
        }

        $result  = $this->db->get();
     
        return $result->result_array();
    }

    public function select_file_from_admin($select_array,$where_array)
    {
        $this->db->select($select_array);

        $this->db->from('drug_narcotis_files');

        $this->db->where($where_array);

        $this->db->where('(drug_narcotis_files.type = 0  or drug_narcotis_files.type = 2)');

        $this->db->order_by('id', 'desc');
        
        $result  = $this->db->get();

        record_db_error($this->db->last_query());
        
        return $result->result_array();
    }


   	
}
?>