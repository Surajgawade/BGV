<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Clients_model extends CI_Model
{
	function __construct()
    {
		$this->tableName = 'clients';

		$this->primaryKey = 'id';
	}

	public function select($return_as_strict_row,$select_array, $where_array = array())
	{
		$this->db->select($select_array);

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

	public function select_client($return_as_strict_row,$select_array, $where_array = array())
	{
		$this->db->select($select_array);

		$this->db->from('clients_details');

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

	public function delete($arrwhere)
	{
	  $result =  $this->db->delete($this->tableName, $arrwhere);

	  record_db_error($this->db->last_query());
	  
	  return $result;
	}

	public function client_login_save($arrdata,$arrwhere = array())
    {
        if(!empty($arrwhere))
        {
            $this->db->where($arrwhere);

            $result = $this->db->update('client_login', $arrdata);

            record_db_error($this->db->last_query());

            return $result;
        }
        else
        {
            $this->db->insert('client_login', $arrdata);

            record_db_error($this->db->last_query());

            return $this->db->insert_id();
        }
    }

    public function client_select($return_as_strict_row,$select_array, $where_array = array())
    {
        $this->db->select($select_array);

        $this->db->from('client_login');

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

    public function insert_batch_client_login($files_arry)
	{
		return $this->db->insert_batch('client_login', $files_arry);	
	}

    public function update_batch_update_client($arrdata,$arrwhere)
	{
		if(!empty($arrwhere))
        {
            $this->db->where($arrwhere);

            $result = $this->db->update('client_login', $arrdata);

            record_db_error($this->db->last_query());
      
            return $result;
        }
	}

	public function insert_batch_client_details($files_arry)
	{
		return $this->db->insert_batch('clients_details', $files_arry);	
	}

	public function select_clients_spoc_details($where_arry)
    {
        return $this->db->select('*')->from('client_spoc_details')->where($where_arry)->get()->result_array();
    }
    
	public function clients_details_save($arrdata,$arrwhere = array())
    {
        if(!empty($arrwhere))
        {
            $this->db->where($arrwhere);

            $result = $this->db->update('clients_details', $arrdata);
           
            record_db_error($this->db->last_query());

            return $result;
        }
        else
        {
            $this->db->insert('clients_details', $arrdata);

            record_db_error($this->db->last_query());

            return $this->db->insert_id();
        }
    }
    
	public function select_clients_details($return_as_strict_row = FALSE, $where = array())
	{
		$this->db->select('*')->from('clients_details');
		$this->db->where($where);
		$result_array  = $this->db->get()->result_array();

        if($return_as_strict_row)
        {
            if(count($result_array) > 0) // ensure only one record has been previously inserted
            {
                $result_array  = $result_array[0];
            }
        }
        return $result_array;
	}

	public function get_client_list($where_array = array(),$where,$columns)
	{
         $this->db->select("clients.*,user_profile.user_name as create_by,(select aggr_end from client_aggr_details where client_id=clients.id order by  id desc limit 1) as agreement_end_date");

		$this->db->from('clients');

		$this->db->join('user_profile','user_profile.id = clients.created_by');

		 if(isset($where['filter_by_status']) &&  $where['filter_by_status'] != '')	
		     {  
		     	$filter_by_status  =  $where['filter_by_status'];
	          
		     	$where_status = "clients.status = $filter_by_status";
               
                $this->db->where($where_status); 

		     }

		    

        $this->db->where($where_array);
			
        if(is_array($where) && $where['search']['value'] != "" )
		{
			$this->db->like('clients.clientname', $where['search']['value']);
		}

		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			
			$order_by = $where['order'][0]['dir']; 

			$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		}
		else
		{
			$this->db->order_by('clients.id','ASC');
		}
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

	    $result = $result->result_array();


		if(!empty($where_array) && !empty($result)) {
                $result = $result[0];
            }

		
		return $result;


	}

	public function get_client_list_count($where_array = array(),$where,$columns)
	{
         $this->db->select("clients.*,user_profile.user_name as create_by,(select aggr_end from client_aggr_details where client_id=clients.id order by  id desc limit 1) as agreement_end_date");

		$this->db->from('clients');

		$this->db->join('user_profile','user_profile.id = clients.created_by');


		  if(isset($where['filter_by_status']) &&  $where['filter_by_status'] != '')	
		     {  
		     	$filter_by_status  =  $where['filter_by_status'];
	          
		     	$where_status = "clients.status = $filter_by_status";
               
                $this->db->where($where_status); 

		     }


		$this->db->where($where_array);
         
        if(is_array($where) && $where['search']['value'] != "" )
		{
			$this->db->like('clients.clientname', $where['search']['value']);
		} 
	
		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			
			$order_by = $where['order'][0]['dir']; 

			$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		}
		else
		{
			$this->db->order_by('clients.id','ASC');
		}
		
		$result = $this->db->get();


		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function select_logs($where_arry = array())
	{
		$this->db->select("clients_logs.*,user_profile.user_name as created_by");

		$this->db->from('clients_logs');

		$this->db->join("user_profile",'user_profile.id = clients_logs.created_by');

		if(!empty($where_arry))
		{
			$this->db->where($where_arry);
		}
		$this->db->order_by('clients_logs.created_on','desc');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_entitypackages($where_array)
	{
		$this->db->select("id,tbl_clients_id,clientaddress,clientcity,clientpincode,component_id,component_name,(select entity_package_name from entity_package where id = clients_details.entity) as entity,(select entity_package_name from entity_package where id = clients_details.package) as package,created_on,created_by");

		$this->db->from('clients_details');

		$this->db->where($where_array);

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function entity_packageList($where_array)
	{
		$this->db->select('entity_package.id as entity_id,entity_package.tbl_client_id as client_id,entity_package.entity_package_name as entity_name, sub.entity_package_name as package_name,sub.created_on as created_on,(select user_name from user_profile where user_profile.id = sub.created_by) as created_by,sub.id as package_id,(select cd.component_id from clients_details as cd where cd.tbl_clients_id = entity_package.tbl_client_id and cd.entity = entity_package.id and cd.package = sub.id) as component_id');
		
		$this->db->from('entity_package');
		
		$this->db->join('entity_package as sub','sub.is_entity = entity_package.id');

		//$this->db->join('clients_details as cd','cd.tbl_clients_id = entity_package.tbl_client_id and and cd.entity = entity_package.id and cd.package = sub.id');

		//$this->db->join('clients_details as cd','cd.entity = entity_package.id');

	//$this->db->join('clients_details as cd','cd.package = sub.id');

		$this->db->where($where_array);
		// print_r($this->db);
		$result = $this->db->get();
      
		record_db_error($this->db->last_query());
		return $result->result_array();
	}

	public function entityList($where_array)
	{
		$this->db->select('entity_package.id as entity_id,entity_package.entity_package_name as entity_name');
		
		$this->db->from('entity_package');
		
		$this->db->where($where_array);
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());
		return $result->result_array();
	}

	public function copy_package($client,$entity,$insert_status)
	{
		$query = $this->db->query("INSERT clients_details (tbl_clients_id,entity,package,clientaddress,clientcity,clientstate,clientpincode,clientphone1,clientphone2,clientmobile,clientemail1,clientemail2,status,report_type,component_id,component_name,scope_of_work,mode_of_verification,tat_addrver,tat_empver,tat_eduver,interim_report,final_report,price,created_on,modified_on,created_by,modified_by,tat_refver,tat_courtver,tat_globdbver,tat_narcver,tat_crimver,tat_identity,tat_cbrver,tat_claim_investigation,tat_KYC_collection,package_amount) SELECT tbl_clients_id,".$entity.",".$insert_status.",clientaddress,clientcity,clientstate,clientpincode,clientphone1,clientphone2,clientmobile,clientemail1,clientemail2,status,report_type,component_id,component_name,scope_of_work,mode_of_verification,tat_addrver,tat_empver,tat_eduver,interim_report,final_report,price,created_on,modified_on,created_by,modified_by,tat_refver,tat_courtver,tat_globdbver,tat_narcver,tat_crimver,tat_identity,tat_cbrver,tat_claim_investigation,tat_KYC_collection,package_amount
                           FROM clients_details
                           WHERE tbl_clients_id = ".$client." limit 1 ");
	}




	public function generate_cands_ref_no($client_id)
	{
		$this->db->select("clients.clientname,count(cands.id)+1 as total_cands");

		$this->db->from('cands');

		$this->db->join("clients",'clients.id = cands.clientid');

		$this->db->where("clients.id",$client_id);

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$result_array =  $result->result_array();

		if(count($result_array)> 0) // ensure only one record has been previously inserted
	    {
	        $result_array  = $result_array[0];
	    }

	    return $result_array;
	}

	public function client_details($where_arry)
    {
        if(!empty($where_arry))
        {
            $this->db->select('client_login.*,clients.clientname');

            $this->db->from('client_login');

            $this->db->join("clients",'clients.id = client_login.client_id');

            $this->db->where($where_arry);
            
            $result  = $this->db->get();

            record_db_error($this->db->last_query());

            return $result->result_array();
        }
    }

    public function select_aggrement($return_as_strict_row,$select_array, $where_array = array())
	{
		$this->db->select($select_array);

		$this->db->from('client_aggr_details');

		$this->db->where($where_array);

		$this->db->order_by('id','desc');

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		$result_array = $result->result_array();

        if($return_as_strict_row)
		{
            if(count($result_array) > 0) // ensure only one record has been previously inserted
            {
                $result_array  = $result_array[0];
            }
        }
        
        return $result_array;
	}

	public function select_file($select_array,$where_array)
	{
		$this->db->select($select_array);

		$this->db->from('client_aggr_details');

		$this->db->where($where_array);

		$this->db->order_by('id');
		
		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function select_file_logo($select_array,$where_array)
	{
		$this->db->select($select_array);

		$this->db->from('clients');

		$this->db->where($where_array);

		$this->db->order_by('id');
		
		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}
	public function insert_sla_details($arrdata,$arrwhere = array())
    {
        $this->db->select("*");

		$this->db->from('sla_setting');

		$this->db->where($arrwhere);

		$result  = $this->db->get();
        $result_array = $result->result_array();
 
       if(!empty($result_array))
        {
           foreach($arrdata as $arrdata=>$arrdata_value)
        	{
            $this->db->where($arrwhere);

            $this->db->where(array('question' => $arrdata_value['question']));

            $result = $this->db->update('sla_setting', $arrdata_value);

            record_db_error($this->db->last_query());
           }
            return $result;
        }
        else
        {
        	foreach($arrdata as $arrdata=>$arrdata_value)
        	{

        	  $this->db->insert('sla_setting', $arrdata_value);

              record_db_error($this->db->last_query());

             }
            return $this->db->insert_id();
        }
    }

    public function select_sla_details($return_as_strict_row = FALSE, $where = array())
	{   
  
		$this->db->select('*');

		$this->db->from('sla_setting');

		$this->db->where($where);

		$result  = $this->db->get();
    
		record_db_error($this->db->last_query());
		
		$result_array  = $result->result_array();
		
        return $result_array;
	}

	public function select_sla_client_info($return_as_strict_row = FALSE, $where = array())
	{   
     
		$this->db->select('clients_details.*,(select clientname from clients where id = clients_details.tbl_clients_id) as client_name,(select entity_package_name from entity_package where id = clients_details.entity and is_entity_package = 1) as entity_name,(select entity_package_name from entity_package where id = clients_details.package and is_entity_package = 2) as package_name');

		$this->db->from('clients_details');
	
		$this->db->where($where);

		$result  = $this->db->get();
    
		record_db_error($this->db->last_query());
		
		$result_array  = $result->result_array();
		
        return $result_array;
	}
	public function select_sla_pdf($return_as_strict_row = FALSE, $where = array())
	{   
     
		$this->db->select('*');

		$this->db->from('sla_setting');

		$this->db->where($where);

		$result  = $this->db->get();
    
		record_db_error($this->db->last_query());
		
		$result_array  = $result->result_array();
		
        return $result_array;
	}
    
    public function delete_client($arrdata,$arrwhere)
	{   
     
      if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update($this->tableName, $arrdata);

			record_db_error($this->db->last_query());

			return $result;
	    }
	}

	public function select_join_client($where_array)
	{
		$this->db->select("concat(firstname,' ',lastname) as fullname");

		$this->db->from('user_profile');

		$this->db->where($where_array);

		
		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		$result_array = $result->result_array();

        
        return $result_array;
	}

	public function select_client_spoc_email($where_array)
	{
		$this->db->select("id");

		$this->db->from('client_spoc_details');

		$this->db->where($where_array);

		
		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		$result_array = $result->result_array();

        
        return $result_array;
	}
	public function update_spoc_detail($arrdata,$arrwhere)
	{

		if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update('client_spoc_details', $arrdata);

			record_db_error($this->db->last_query());

			return $result;
	    }
	    else
	    {
			$this->db->insert('client_spoc_details', $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	    }
       
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
	
}
?>
