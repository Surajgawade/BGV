<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Addressver_model extends CI_Model
{
	function __construct()
    {
		$this->tableName = 'addrver';

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

	public function update_auto_increament_value($arrdata,$arrwhere = array())
	{
        
		   $this->db->where($arrwhere);

			$result = $this->db->update('addrver', $arrdata);

			record_db_error($this->db->last_query());

			return $result;

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


	protected function filter_where_cond($where_arry)
	{
		$where1 = array();
  
		if(isset($where_arry['status']) &&  $where_arry['status'] != '')	
		{ 
            if($where_arry['status'] != 'All')
			{
			  $where1['addrverres.var_filter_status'] = $where_arry['status'];
	      	}
	      	
     	}

		if(isset($where_arry['sub_status']) &&  $where_arry['sub_status'] != '' &&  $where_arry['sub_status'] != 0)	
		{
			$where1['addrverres.verfstatus'] = $where_arry['sub_status'];
		}

		if(isset($where_arry['client_id']) &&  $where_arry['client_id'] != 0)	
		{
			$where1['addrver.clientid'] = $where_arry['client_id'];

		}
		if(isset($where_arry['status']) &&  $where_arry['status'] != '')	
		{ 
            if($where_arry['status'] != 'All')
			{
			  $where1['addrverres.var_filter_status'] = $where_arry['status'];
	      	}
	      	
     	}
     	if(isset($where_arry['filter_by_executive']) &&  $where_arry['filter_by_executive'] != 0)	
		{ 
            if($where_arry['filter_by_executive'] != 'All')
			{
			  $where1['addrver.has_case_id'] = $where_arry['filter_by_executive'];
	      	}
	      	
     	}
		if(isset($where_arry['filter_by_vendor']) &&  $where_arry['filter_by_vendor'] != 0)	
		{ 
			if($where_arry['filter_by_vendor'] != 'All')
			{
			   $where1['addrver.vendor_id'] = $where_arry['filter_by_vendor'];
			}
			   
		}

 
		return $where1;
	}
      public function  save_update($arrdata,$arrwhere = array())
	  {
	    if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update('addrverres', $arrdata);

			record_db_error($this->db->last_query());

			return $result;
	    }
	   
	  } 

	  public function  save_update_ver($arrdata,$arrwhere = array())
	  {
	    if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update('addrverres_result', $arrdata);

			record_db_error($this->db->last_query());

			return $result;
	    }
	   
	  }

	public function get_all_addrs_by_client_datatable($where1,$columns)
	{
         
   		$this->db->select("candidates_info.id as candidates_info_id, candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,addrver.id,addrver.mod_of_veri,addrver.add_com_ref,clients.clientname,addrver.address,addrver.city,addrver.pincode, addrver.state ,addrver.iniated_date,status.status_value,user_profile.user_name,addrverres.first_qc_approve,addrverres.verfstatus,addrverres.first_qc_updated_on,addrverres.first_qu_reject_reason,(select created_on from address_activity_data where comp_table_id = addrver.id order by id desc limit 1) as last_activity_date,due_date,tat_status,(select vendor_name from vendors where vendors.id = addrver.vendor_id) as vendor_name,addrver_insuff.insuff_raised_date");
       
		$this->db->from('addrver');

		$this->db->join("user_profile",'user_profile.id = addrver.has_case_id','left');

		$this->db->join("candidates_info",'candidates_info.id = addrver.candsid');

		$this->db->join("clients",'clients.id = addrver.clientid');
		
		$this->db->join("addrverres",'addrverres.addrverid = addrver.id','left');


		$this->db->join("addrver_insuff",'(addrver_insuff.addrverid = addrver.id AND  addrver_insuff.status = 1 )','left');

		$this->db->join("status",'status.id = addrverres.verfstatus','left');

        $this->db->where($this->filter_where_cond($where1)); 


            if(isset($where1['start_date']) &&  $where1['start_date'] != '' && isset($where1['end_date']) &&  $where1['end_date'] != '')	
		    { 

		     	$start_date  =  $where1['start_date'];
	            $end_date  =  $where1['end_date'];

	            if($where1['status'] == "Closed")
                {
	         
		     	$where3 = "DATE_FORMAT(`addrverres`.`closuredate`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}

		     	if($where1['status'] == "Insufficiency")
                {
	         
		     	$where3 = "DATE_FORMAT(`addrver_insuff`.`insuff_raised_date`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}
                
                $this->db->where($where3); 

		    } 
     
       
         
		if(is_array($where1) && $where1['search']['value'] != "" )
		{
			$this->db->like('candidates_info.cmp_ref_no', $where1['search']['value']);

			$this->db->or_like('candidates_info.CandidatesContactNumber', $where1['search']['value']);
            
			$this->db->or_like('candidates_info.ContactNo1', $where1['search']['value']);

			$this->db->or_like('candidates_info.ContactNo2', $where1['search']['value']);
		
			$this->db->or_like('candidates_info.ClientRefNumber', $where1['search']['value']);

			$this->db->or_like('clients.clientname', $where1['search']['value']);

			$this->db->or_like('addrver.add_com_ref', $where1['search']['value']);

			$this->db->or_like('addrver.iniated_date', $where1['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where1['search']['value']);

			$this->db->or_like('addrver.address', $where1['search']['value']);

			$this->db->or_like('addrver.city', $where1['search']['value']);

            $this->db->or_like('addrver.state', $where1['search']['value']);

		}

		if(!empty($where1['order']))
		{
			$column_name_index = $where1['order'][0]['column'];
			$order_by = $where1['order'][0]['dir']; 
            
            
			$this->db->order_by($where1['columns'][$column_name_index]['data'],$order_by);
		}
		else
		{
		
		    $this->db->order_by('addrver.id','desc');
		}
		
		$this->db->limit($where1['length'],$where1['start']);

		$result = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}
   
	public function get_all_addrs_by_client_datatable_count($where1,$columns)
	{
         
		$this->db->select("candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.caserecddate,addrver.id,addrver.mod_of_veri,addrver.add_com_ref,clients.clientname,'' as vendor_name,addrver.address,addrver.city,addrver.pincode,addrver.state,addrver.iniated_date,status.status_value,user_profile.user_name,addrverres.first_qc_approve,addrverres.verfstatus,addrverres.first_qc_updated_on,addrverres.first_qu_reject_reason,(select created_on from address_activity_data where comp_table_id = addrver.id  order by id desc limit 1) as last_activity_date");

		$this->db->from('addrver');

		$this->db->join("user_profile",'user_profile.id = addrver.has_case_id','left');

		$this->db->join("candidates_info",'candidates_info.id = addrver.candsid');

		$this->db->join("clients",'clients.id = addrver.clientid');

		$this->db->join("addrverres",'(addrverres.addrverid = addrver.id)','left');

	    $this->db->join("addrver_insuff",'(addrver_insuff.addrverid = addrver.id AND  addrver_insuff.status = 1 )','left');


		$this->db->join("status",'status.id = addrverres.verfstatus','left');

        $this->db->where($this->filter_where_cond($where1));

         if(isset($where1['start_date']) &&  $where1['start_date'] != '' && isset($where1['end_date']) &&  $where1['end_date'] != '')	
		    { 

		     	$start_date  =  $where1['start_date'];
	            $end_date  =  $where1['end_date'];

	            if($where1['status'] == "Closed")
                {
	         
		     	$where3 = "DATE_FORMAT(`addrverres`.`closuredate`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}

		     	if($where1['status'] == "Insufficiency")
                {
	         
		     	$where3 = "DATE_FORMAT(`addrver_insuff`.`insuff_raised_date`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}
                
                $this->db->where($where3); 

		    } 
        

		
		if(is_array($where1) && $where1['search']['value'] != "")
		{

			$this->db->like('candidates_info.cmp_ref_no', $where1['search']['value']);

			$this->db->or_like('candidates_info.CandidatesContactNumber', $where1['search']['value']);

			$this->db->or_like('candidates_info.ContactNo1', $where1['search']['value']);

			$this->db->or_like('candidates_info.ContactNo2', $where1['search']['value']);

			$this->db->or_like('candidates_info.ClientRefNumber', $where1['search']['value']);

			$this->db->or_like('clients.clientname', $where1['search']['value']);

			$this->db->or_like('addrver.add_com_ref', $where1['search']['value']);

			$this->db->or_like('addrver.iniated_date', $where1['search']['value']);

			$this->db->or_like('candidates_info.CandidateName', $where1['search']['value']);

			$this->db->or_like('addrver.address', $where1['search']['value']);

			$this->db->or_like('addrver.city', $where1['search']['value']);

            $this->db->or_like('addrver.state', $where1['search']['value']);
		}

		if(!empty($where1['order']))
		{
			$column_name_index = $where1['order'][0]['column'];
			$order_by = $where1['order'][0]['dir']; 
            
            
			$this->db->order_by($where1['columns'][$column_name_index]['data'],$order_by);
		}
		else
		{

		    $this->db->order_by('addrver.id','desc');
		}
		
		
		$result = $this->db->get();
    
		record_db_error($this->db->last_query());
  
		return $result->result_array();
	}

   
   // not fast query that why fetching data get_address_details1 call
	
    
	public function get_address_details($where_arry = array())
	{
		$this->db->select("addrver.*,(select `var_filter_status` from `addrverres` where `addrverres`.`addrverid` = `addrver`.`id` ORDER by `id` desc limit 1) as var_filter_status,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.CandidateName,(select clientname from clients where clients.id = addrver.clientid limit 1) as clientname,(select user_name from user_profile where user_profile.id = addrver.has_case_id) as executive_name, (select `id` from `addrverres` where `addrverres`.`addrverid` = `addrver`.`id` ORDER by `id` desc limit 1) as addrverres_id,(select `verfstatus` from `addrverres` where `addrverres`.`addrverid` = `addrver`.`id` ORDER by `id` desc limit 1) as verfstatus1,(SELECT status_value FROM status WHERE status.id = verfstatus1 ORDER BY id DESC LIMIT 1) verfstatus,(select `res_address_type` from `addrverres` where `addrverres`.`addrverid` = `addrver`.`id` ORDER by `id` desc limit 1) as res_address_type,(select `res_address` from `addrverres` where `addrverres`.`addrverid` = `addrver`.`id` ORDER by `id` desc limit 1) as res_address,(select `res_stay_from` from `addrverres` where `addrverres`.`addrverid` = `addrver`.`id` ORDER by `id` desc limit 1) as res_stay_from,(select `res_stay_to` from `addrverres` where `addrverres`.`addrverid` = `addrver`.`id` ORDER by `id` desc limit 1) as res_stay_to,(select `neighbour_1` from `addrverres` where `addrverres`.`addrverid` = `addrver`.`id` ORDER by `id` desc limit 1) as neighbour_1,(select `neighbour_details_1` from `addrverres` where `addrverres`.`addrverid` = `addrver`.`id` ORDER by `id` desc limit 1) as neighbour_details_1,(select `neighbour_2` from `addrverres` where `addrverres`.`addrverid` = `addrver`.`id` ORDER by `id` desc limit 1) as neighbour_2,(select `neighbour_details_2` from `addrverres` where `addrverres`.`addrverid` = `addrver`.`id` ORDER by `id` desc limit 1) as neighbour_details_2,(select `mode_of_verification` from `addrverres` where `addrverres`.`addrverid` = `addrver`.`id` ORDER by `id` desc limit 1) as mode_of_verification,(select `resident_status` from `addrverres` where `addrverres`.`addrverid` = `addrver`.`id` ORDER by `id` desc limit 1) as resident_status,(select `landmark` from `addrverres` where `addrverres`.`addrverid` = `addrver`.`id` ORDER by `id` desc limit 1) as landmark,(select `verified_by` from `addrverres` where `addrverres`.`addrverid` = `addrver`.`id` ORDER by `id` desc limit 1) as verified_by,(select `addr_proof_collected` from `addrverres` where `addrverres`.`addrverid` = `addrver`.`id` ORDER by `id` desc limit 1) as addr_proof_collected,(select `remarks` from `addrverres` where `addrverres`.`addrverid` = `addrver`.`id` ORDER by `id` desc limit 1) as remarks,(select `first_qc_approve` from `addrverres` where `addrverres`.`addrverid` = `addrver`.`id` ORDER by `id` desc limit 1) as first_qc_approve,(select `res_city` from `addrverres` where `addrverres`.`addrverid` = `addrver`.`id` ORDER by `id` desc limit 1) as res_city,(select `res_pincode` from `addrverres` where `addrverres`.`addrverid` = `addrver`.`id` ORDER by `id` desc limit 1) as res_pincode,(select `res_state` from `addrverres` where `addrverres`.`addrverid` = `addrver`.`id` ORDER by `id` desc limit 1) as res_state,(select `closuredate` from `addrverres` where `addrverres`.`addrverid` = `addrver`.`id` ORDER by `id` desc limit 1) as closuredate,due_date,tat_status,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,(select id from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_id,(select id from entity_package where entity_package.id = candidates_info.package limit 1) as package_id,addrver.iniated_date,candidates_info.caserecddate,add_com_ref");

		$this->db->from('addrver');

		//$this->db->join("addrverres",'addrverres.addrverid = addrver.id');
		
               //  $this->db->order_by('addrverres.id','DESC');

       // $this->db->limit('1');
		$this->db->join("candidates_info",'candidates_info.id = addrver.candsid');

		$this->db->join("addrver_files",'(addrver_files.addrver_id = addrver.id AND addrver_files.status = 1 AND addrver_files.type = 0 )','left');
       

		if($where_arry)
		{
			$this->db->where($where_arry);
		}
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

/*
    public function get_address_details_first_qc($where_arry = array())
	{
		$this->db->select("addrver.*,addrverres_result.*,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.CandidateName,(SELECT status_value FROM status WHERE status.id = addrverres_result.verfstatus) verfstatus_name,(select clientname from clients where clients.id = addrver.clientid limit 1) as clientname,(select user_name from user_profile where user_profile.id = addrver.has_case_id) as executive_name,due_date,tat_status,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,addrver.iniated_date,candidates_info.caserecddate,add_com_ref");

		$this->db->from('addrver');

		$this->db->join("addrverres_result",'addrverres_result.addrverid = addrver.id');
		
               //  $this->db->order_by('addrverres.id','DESC');

       // $this->db->limit('1');
		$this->db->join("candidates_info",'candidates_info.id = addrver.candsid');

		$this->db->join("addrver_files",'(addrver_files.addrver_id = addrver.id AND addrver_files.status = 1 AND addrver_files.type = 0 )','left');
       

		if($where_arry)
		{
			$this->db->where($where_arry);
		}
		
		$result = $this->db->get();
		record_db_error($this->db->last_query());

		return $result->result_array();
	}
*/
	 public function get_address_details_first_qc($where_arry = array())
	{
		$this->db->select("addrver.*,addrverres.*,candidates_info.CandidateName,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.CandidateName,(SELECT status_value FROM status WHERE status.id = addrverres.verfstatus) verfstatus_name,(select clientname from clients where clients.id = addrver.clientid limit 1) as clientname,(select user_name from user_profile where user_profile.id = addrver.has_case_id) as executive_name,due_date,tat_status,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,addrver.iniated_date,candidates_info.caserecddate,add_com_ref,(SELECT GROUP_CONCAT(concat(addrver_files.id,'/',addrverres.clientid,'/',file_name) ORDER BY serialno,id ASC SEPARATOR '||') FROM addrver_files where addrver_files.addrver_id = addrver.id and addrver_files.type= 1  and addrver_files.status = 1) as add_attachments");

		$this->db->from('addrver');

		$this->db->join("addrverres",'addrverres.addrverid = addrver.id');
		
               //  $this->db->order_by('addrverres.id','DESC');

       // $this->db->limit('1');
		$this->db->join("candidates_info",'candidates_info.id = addrver.candsid');

		//$this->db->join("addrver_files",'(addrver_files.addrver_id = addrver.id AND addrver_files.status = 1 AND addrver_files.type = 0 )','left');
       

		if($where_arry)
		{
			$this->db->where($where_arry);
		}
		
		$result = $this->db->get();
		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_address_details1($where_arry)
	{
		

		$sql = 'SELECT `addrver`.*, `candidates_info`.`CandidateName`, `candidates_info`.`ClientRefNumber`, `candidates_info`.`cmp_ref_no`, `candidates_info`.`CandidateName`, (select clientname from clients where clients.id = addrver.clientid limit 1) as clientname, (select user_name from user_profile where user_profile.id = addrver.has_case_id) as executive_name, `addrverres`.`id` as `addrverres_id`, (select status_value from status where status.id = addrverres.verfstatus limit 1) as verfstatus, `addrverres`.`res_address_type`, `addrverres`.`res_address`, `addrverres`.`res_stay_from`,`addrverres`.`res_stay_to`, `addrverres`.`neighbour_1`, `addrverres`.`neighbour_details_1`, `addrverres`.`neighbour_2`, `addrverres`.`neighbour_details_2`, `addrverres`.`mode_of_verification`, `addrverres`.`resident_status`, `addrverres`.`landmark`, `addrverres`.`verified_by`, `addrverres`.`addr_proof_collected`, `addrverres`.`remarks`, `addrverres`.`first_qc_approve`, `res_city`, `res_pincode`, `res_state`, `due_date`, `tat_status`, `closuredate`, (select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name, (select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name, `addrver`.`iniated_date`, `candidates_info`.`caserecddate`, `add_com_ref` FROM `addrver` INNER JOIN (SELECT *
FROM `addrverres`
WHERE `addrverres`.`id` IN (
SELECT MAX(`addrverres`.`id`)
FROM `addrverres`
GROUP BY `addrverres`.`addrverid`
)) AS `addrverres` ON `addrver`.`id` = `addrverres`.`addrverid`  JOIN `candidates_info` ON `candidates_info`.`id` = `addrver`.`candsid` ';

		if($where_arry)
		{
			$sql .= ' AND addrver.candsid = '.$where_arry;
		}
         
       $sql .=  ' Order BY add_com_ref desc';

		$result = $this->db->query($sql);

		return $result->result_array();
	}


	public function select_insuff($where_array)
	{
		$this->db->select('*')->from('addrver_insuff');

		$this->db->where($where_array);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function save_update_insuff($arrdata,$arrwhere = array())
	{
		if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update('addrver_insuff', $arrdata);
			
			record_db_error($this->db->last_query());
			
			return $result;
	    }
	    else
	    {
			$this->db->insert('addrver_insuff', $arrdata);
			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	    }
	}

	public function select_reinitiated_date($where_array)
	{
		
	    $this->db->select('addrver.*');

		$this->db->from('addrver');

		$this->db->where($where_array);
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	    
	}


	public function save_update_initiated_date($arrdata,$where_array)
	{
		if(!empty($where_array))
	    {
			$this->db->where($where_array);

			$result = $this->db->update('addrver', $arrdata);
			
			record_db_error($this->db->last_query());
			
			return $result;
	    }
	     
	}
    

	public function save_update_initiated_date_addrver($arrdata,$where_array)
	{
		if(!empty($where_array))
	    {
			$this->db->where($where_array);

			$result = $this->db->update('addrverres', $arrdata);
			
			record_db_error($this->db->last_query());
			
			return $result;
	    }
	     
	}


    public function initiated_date_addrver_activity_data($arrdata)
	{
        
		   $this->db->insert('address_activity_data', $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	}

	public function select_insuff_join($where_array)
	{
		$this->db->select('addrver_insuff.*,(select user_name from user_profile where id =  addrver_insuff.created_by limit 1) as insuff_raised_by,(select user_name from user_profile where id = addrver_insuff.insuff_cleared_by limit 1) as insuff_cleared_by');

		$this->db->from('addrver_insuff');

		$this->db->where($where_array);
		
		$this->db->where('addrver_insuff.status !=',3);

		$this->db->order_by('addrver_insuff.id','asc');

		return $this->db->get()->result_array();

		
	}

	public function select_result_log($where_array)
	{
		$this->db->select('addrverres_result.*,(select created_by from activity_log where id =  addrverres_result.activity_log_id) as created_by1,(select user_name from user_profile where id = created_by1 ) as created_by,(select activity_mode from activity_log where id =  addrverres_result.activity_log_id) as activity_mode ,(select activity_status from activity_log where id =  addrverres_result.activity_log_id) as activity_status ,(select activity_type from activity_log where id =  addrverres_result.activity_log_id) as activity_type,(select action from activity_log where id =  addrverres_result.activity_log_id) as activity_action,(select GROUP_CONCAT(addrver_files.file_name) from addrver_files where `addrver_files`.`addrver_id` = `addrverres_result`.`addrverid` AND `status` = 1 AND `type` = 1)  as file_names,(select GROUP_CONCAT(addrver_files.id) from addrver_files where `addrver_files`.`addrver_id` = `addrverres_result`.`addrverid` AND `status` = 1 AND `type` = 1) as file_ids');

		$this->db->from('addrverres_result');


		$this->db->where($where_array);
		$this->db->order_by('addrverres_result.id','desc');

		$result = $this->db->get();
		record_db_error($this->db->last_query());

		return $result->result_array();
		
	}

	public function select_result_log1($where_array)
	{
		$this->db->select('addrverres_result.*,addrver.address_type,addrver.address,addrver.city,addrver.pincode,addrver.state,addrver.stay_from, addrver.stay_to,(select activity_mode from activity_log where id =  addrverres_result.activity_log_id) as activity_mode ,(select activity_status from activity_log where id =  addrverres_result.activity_log_id) as activity_status ,(select activity_type from activity_log where id =  addrverres_result.activity_log_id) as activity_type,(select action from activity_log where id =  addrverres_result.activity_log_id) as activity_action,GROUP_CONCAT(addrver_files.file_name) as file_names,GROUP_CONCAT(addrver_files.id) as file_ids');

		$this->db->from('addrverres_result');

		$this->db->join("addrver",'addrver.id = addrverres_result.addrverid');

		//$this->db->join("addrverres",'addrverres.addrverid = addrver.id');

	    $this->db->join("addrver_files",'(addrver_files.addrver_id = addrverres_result.addrverid AND status = 1 AND type = 1)','left');

		$this->db->where($where_array);
		$this->db->order_by('id','desc');
		//$this->db->where('addrverres.status !=',3);

		return $this->db->get()->result_array();

		
	}
	
	public function save_update_result($arrdata,$arrwhere = array())
	{
	    if(!empty($arrwhere)) {
			$this->db->where($arrwhere);
			return $this->db->update('addrverres', $arrdata);
	    } else {
			$this->db->insert('addrverres', $arrdata);
			return $this->db->insert_id();
	    }
	}

	public function save_update_result_addrverres($arrdata,$arrwhere = array())
	{
	    if(!empty($arrwhere)) {
			$this->db->where($arrwhere);
			return $this->db->update('addrverres_result', $arrdata);
	    } else {
			$this->db->insert('addrverres_result', $arrdata);
			return $this->db->insert_id();
	    }
	}

	public function export_sql($where) { 
		
		$sql = "SELECT (select clientname from clients where clients.id = candidates_info.clientid limit 1) as
			client_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name, ClientRefNumber,cmp_ref_no,CandidateName,DATE_FORMAT(caserecddate,'%d-%m-%Y') as caserecddate, (select status_value from status where status.id = addrverres.verfstatus limit 1) as verfstatus,add_com_ref,vendor_id,(select user_name from user_profile where user_profile.id = addrver.has_case_id) as executive_name,
			address_type,address,city,pincode,state,stay_from,stay_to,DATE_FORMAT(iniated_date,'%d-%m-%Y') as iniated_date,DATE_FORMAT(due_date,'%d-%m-%Y') as due_date,tat_status,first_qc_updated_on,mode_of_verification,resident_status,landmark,neighbour_1,neighbour_details_1,neighbour_2,neighbour_details_2,verified_by,addr_proof_collected,addrverres.remarks,DATE_FORMAT(closuredate,'%d-%m-%Y') as closuredate,first_qc_approve,(select created_on from address_activity_data where comp_table_id = addrver.id order by id desc limit 1) as last_activity_date
			FROM addrver 
			INNER JOIN candidates_info ON candidates_info.id = addrver.candsid 
			INNER JOIN addrverres ON addrverres.addrverid = addrver.id $where";
		$query = $this->db->query($sql);

		return $query->result_array();
	}
    
    public function dashboard_sql($where) { 
		
		$sql = "SELECT (select clientname from clients where clients.id = candidates_info.clientid limit 1) as
			client_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name, ClientRefNumber,cmp_ref_no,CandidateName,DATE_FORMAT(caserecddate,'%d-%m-%Y') as caserecddate, (select status_value from status where status.id = addrverres.verfstatus limit 1) as verfstatus,add_com_ref,(select vendor_name from vendors where vendors.id = addrver.vendor_id) as vendor_name,(select user_name from user_profile where user_profile.id = addrver.has_case_id) as executive_name,address,city,pincode,state,DATE_FORMAT(iniated_date,'%d-%m-%Y') as iniated_date,DATE_FORMAT(due_date,'%d-%m-%Y') as due_date,tat_status,mod_of_veri,addrverres.remarks,DATE_FORMAT(closuredate,'%d-%m-%Y') as closuredate,(select created_on from address_activity_data where comp_table_id = addrver.id order by id desc limit 1) as last_activity_date
			FROM addrver 
			INNER JOIN candidates_info ON candidates_info.id = addrver.candsid 
			INNER JOIN addrverres ON addrverres.addrverid = addrver.id $where";
			
		$query = $this->db->query($sql);

		return $query->result_array();
	}


	public function get_all_addrs_by_client($where_arry = array())
	{	
		$this->db->select("addrver.*,clients.clientname,cands.ClientRefNumber,cands.cmp_ref_no,cands.CandidateName,membership_groups.name,a1.verfstatus,cands.caserecddate,a1.created_on as last_modified,a1.closuredate,a1.insuffraiseddate,a1.insuffcleardate,a1.insuffremarks,a1.insuff_raised_date_2,a1.insuff_clear_date_2,a1.insuff_remarks_2,a1.verfstatus as addres_status,a1.remark,a1.insuff_additional_remark_1,a1.insuff_additional_remark_2,cands.DateofBirth,cands.CandidatesContactNumber,cands.NameofCandidateFather,cands.ContactNo1");

		$this->db->from('addrver');

		$this->db->join("clients",'clients.id = addrver.clientid');

		$this->db->join("cands",'cands.id = addrver.candsid');

		$this->db->join("membership_groups",'membership_groups.groupID = addrver.assignedto');

		$this->db->join("addrverres as a1",'a1.addrverid = addrver.id','left');

		$this->db->join("addrverres as a2",'(a2.addrverid = addrver.id and a1.id < a2.id)','left');

		$this->db->where('a2.verfstatus is null');

		if($where_arry)
		{
			$this->db->where($where_arry);
		}

		$this->db->order_by('a1.id', 'desc');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}
    

    public function get_all_address_by_client($clientid,$filter_by_status,$from_date,$to_date,$vendor_id)
	{	


        $this->db->select("addrver.*,clients.clientname,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.CandidateName,candidates_info.NameofCandidateFather,candidates_info.CandidatesContactNumber,candidates_info.ContactNo1,candidates_info.ContactNo2,status.status_value as verfstatus,status.filter_status as filter_status,a1.closuredate,(select concat(`user_profile`.`firstname`,' ',`user_profile`.`lastname`) from user_profile where user_profile.id = addrver.has_case_id limit 1) as executive_name,(select vendor_name from vendors where vendors.id = addrver.vendor_id limit 1) as vendor_name,(SELECT v.final_status from view_vendor_master_log v, `address_vendor_log` `ad` where ad.case_id = addrver.id and v.case_id = ad.id and component = 'addrver' and component_tbl_id = '1' order by v.id desc limit 1) as vendor_status,(SELECT v.trasaction_id  from view_vendor_master_log v, `address_vendor_log` `ad` where ad.case_id = addrver.id and v.case_id = ad.id and component = 'addrver' and component_tbl_id = '1' order by v.id desc limit 1) as transaction_id,(SELECT GROUP_CONCAT(concat(DATE_FORMAT(addrver_insuff.insuff_raised_date,'%d-%m-%Y')) SEPARATOR '||') FROM addrver_insuff where addrver_insuff.addrverid = addrver.id) as insuff_raised_date,(SELECT GROUP_CONCAT(concat(DATE_FORMAT(addrver_insuff.insuff_clear_date,'%d-%m-%Y')) SEPARATOR '||') FROM addrver_insuff where addrver_insuff.addrverid = addrver.id) as insuff_clear_date,(SELECT GROUP_CONCAT(concat(addrver_insuff.insuff_raise_remark) SEPARATOR '||') FROM addrver_insuff where addrver_insuff.addrverid = addrver.id) as insuff_raise_remark");
 
		$this->db->from('addrver');

		$this->db->join("clients",'clients.id = addrver.clientid');

		$this->db->join("candidates_info",'candidates_info.id = addrver.candsid');

		$this->db->join("addrverres as a1",'a1.addrverid = addrver.id','left');

		$this->db->join("addrverres as a2",'(a2.addrverid = addrver.id and a1.id < a2.id)','left');

		$this->db->join("status",'status.id = a1.verfstatus','left');

		$this->db->where('a2.verfstatus is null');

		if($clientid)
		{
			$this->db->where('addrver.clientid',$clientid);
		}

		if($vendor_id)
		{
			$this->db->where('addrver.vendor_id',$vendor_id);
		}

		if($from_date && $to_date)
		{

			$where3 = "DATE_FORMAT(`a1`.`closuredate`,'%Y-%m-%d') BETWEEN '$from_date' AND '$to_date'";
                
            $this->db->where($where3); 
			
		}

		if($filter_by_status)
		{
			if($filter_by_status == "WIP")
			{
			$this->db->where('(a1.var_filter_status = "wip" or  a1.var_filter_status = "WIP")');
		    }
		    if($filter_by_status == "Insufficiency")
			{
			$this->db->where('(a1.var_filter_status = "insufficiency" or  a1.var_filter_status = "Insufficiency")');
		    }
		    if($filter_by_status == "Closed")
			{
			$this->db->where('(a1.var_filter_status = "closed" or  a1.var_filter_status = "Closed")');
		    }
		}

		$this->db->order_by('addrver.id', 'ASC');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	

	public function get_address_ver_result($addsver_id = array())
	{
		$this->db->select("addrverres.*,addrver.clientid as client_id,GROUP_CONCAT(addrverres_files.file_name) as file_names,addrver.candsid,GROUP_CONCAT(addrverres_files.id) as file_ids");

		$this->db->from('addrverres');

		$this->db->join("addrver",'addrver.id = addrverres.addrverid');

		$this->db->join("addrverres_files",'(addrverres_files.addrverres_id = addrverres.id AND status = 1)','left');

		$this->db->group_by('addrverres.id');

		if(!empty($addsver_id))
		{
			$this->db->where($addsver_id);
		}

		$this->db->order_by('id', 'desc');
		
		$this->db->limit(1,0);

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function save_update_adds_ver_result($arrdata,$arrwhere = array())
	{
		if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update('addrverres', $arrdata);

			record_db_error($this->db->last_query());

			return $result;
	    }
	    else
	    {
			$this->db->insert('addrverres', $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	    }
	}

	public function select_adds_ver_result($id)
	{
		$this->db->select('a1.id');

		$this->db->from('addrver');
		
		$this->db->join("addrverres as a1",'(a1.addrverid = addrver.id AND addrver.clientid = 11)');

		$this->db->join("addrverres as a2",'(a2.addrverid = addrver.id and a1.id < a2.id)','left');

		$this->db->where('a2.verfstatus is null');

		$this->db->where($id);

		$this->db->order_by('a1.id', 'desc');
	
		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		$result_array = $result->result_array();

        if(!empty($result_array))
		{
            $result_array  = $result_array[0];
        }
        return $result_array;
	}

	public function uploaded_files($files_arry, $return_insert_ids = FALSE)
	{
		if($return_insert_ids)
		{
			$this->db->insert_batch('addrver_files', $files_arry);

			record_db_error($this->db->last_query());

			$first_id = $this->db->insert_id();

			$affected_rows = $this->db->affected_rows();

			if($affected_rows > 0)
			{
				return range($first_id,($first_id+$affected_rows-1));
			}
			else
			{
				return array();
			}
		}
		else
		{
			$res =  $this->db->insert_batch('addrver_files', $files_arry);
			
			record_db_error($this->db->last_query());
			
			return $res;
		}
	}

	public function save_update_addver_files($arrdata,$arrwhere = array())
	{
		if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update('addrver_files', $arrdata);

			record_db_error($this->db->last_query());

			return $result;
	    }
	    else
	    {
			$this->db->insert('addrver_files', $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	    }
	}

    public function delete_uploaded_file($where = array())
	{	
		$this->db->where_in('id',$where);

		$this->db->set('status', STATUS_DELETED);

		$result = $this->db->update('addrver_files', array('status' => STATUS_DELETED));

		record_db_error($this->db->last_query());

		return $result;
	}

	public function add_uploaded_file($where = array())
	{	
		$this->db->where_in('id',$where);

		$this->db->set('status', STATUS_ACTIVE);

		$result = $this->db->update('addrver_files', array('status' => STATUS_ACTIVE));

		record_db_error($this->db->last_query());

		return $result;
	}

	public function vendor_delete_uploaded_file($where = array())
	{	
		$this->db->where_in('id',$where);

		$this->db->set('status', STATUS_DELETED);

		$result = $this->db->update('view_vendor_master_log_file', array('status' => STATUS_DELETED));

		record_db_error($this->db->last_query());

		return $result;
	}

	public function vendor_add_uploaded_file($where = array())
	{	
		$this->db->where_in('id',$where);

		$this->db->set('status', STATUS_ACTIVE);

		$result = $this->db->update('view_vendor_master_log_file', array('status' => STATUS_ACTIVE));

		record_db_error($this->db->last_query());

		return $result;
	}

    public function update_digital_upload_file($id = array())
	{	
		$where_array = "(addrver_files.file_name = '".$id."_Report-0.png' or addrver_files.file_name ='".$id."_Report-1.png' or addrver_files.file_name = '".$id."_Report-2.png')";
		$this->db->where($where_array);

		$this->db->where('status', 1);

		$this->db->set('status', 2);

		$result = $this->db->update('addrver_files', array('status' => 2));

		record_db_error($this->db->last_query());

		return $result;
	}
	
	
	public function upload_vendor_assign($tableName,$updateArray,$where_arry)
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

	public function upload_file_update($updateArray)
	{
		return $this->db->update_batch('addrver_files',$updateArray, 'id');
	}

	public function upload_file_update_vendor($updateArray)
	{
		return $this->db->update_batch('view_vendor_master_log_file',$updateArray, 'id');
	}

	public function get_add_uploded_files($where_array)
	{
		$this->db->select('*');

		$this->db->from('addrverres_files');

		$this->db->where($where_array);

		$this->db->order_by('serialno','asc');

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();	
	}

	public function address_ver_status_count($where = array())
	{
		$this->db->select('count(addrver.id) as total,IF(a1.verfstatus IS NOT NULL, a1.verfstatus ,"WIP") as overallstatus');

		$this->db->from('addrver');
		
		$this->db->join("addrverres as a1",'a1.addrverid = addrver.id','left');

		$this->db->join("addrverres as a2",'(a2.addrverid = addrver.id and a1.id < a2.id)','left');

		$this->db->where('a2.verfstatus is null');

		if(!empty($where))
		{
			$this->db->where($where);
		}

		$this->db->group_by('overallstatus');

		$result = $this->db->get();
		
		record_db_error($this->db->last_query());

		$results = convert_to_single_dimension_array($result->result_array(),'overallstatus','total');

		$return['total'] = array_sum($results);

        if(!empty($results)) {

        	if(array_key_exists('No Record Found',$results)) {
	            $results['Discrepancy'] = $results['Discrepancy']+$results['No Record Found'];
	        }

	        if(array_key_exists('Insufficiency I',$results)) {
	            $results['Insufficiency'] = $results['Insufficiency']+$results['Insufficiency I'];
	        }

	        if(array_key_exists('Insufficiency II',$results)) {
	            $results['Insufficiency'] = $results['Insufficiency']+$results['Insufficiency II'];
	        }

	        if(array_key_exists('WIP-Initiated',$results)) {
	            $results['WIP'] = $results['WIP']+$results['WIP-Initiated'];
	        }

            foreach ($results as $key => $value) {
                $return[str_replace('/','',str_replace(' ','',$key))] = $value;
            }
        }
        return $return;
	}

	public function get_aaddress_cases_by_date($date,$where = FALSE)
	{
		$sql = 'SELECT count(cands.id) as total_count FROM cands 
					inner join addrver on addrver.candsid  = cands.id
					inner join addrverres as a1 on a1.addrverid = addrver.id
					left join addrverres as a2 on (a2.addrverid = addrver.id and a1.id < a2.id)
					where DATE_FORMAT(a1.created_on ,"%Y-%m-%d") >= "'.$date.'" AND DATE_FORMAT(a1.created_on ,"%Y-%m-%d") <= "'.$date.'" AND a1.verfstatus = "clear" AND a2.verfstatus is null';

		if($where)
		{
			$sql .= ' AND addrver.has_case_id = '.$where;
		}

		$query = $this->db->query($sql);

		$results = $query->row_array();
		
		return (!empty($results) ? $results['total_count'] : 0);
	}

	public function get_address_tat($where_array = false)
	{
		$this->db->select("addrver.reiniated_date,cands.ClientRefNumber,cands.cmp_ref_no,cands.CandidateName,cands.caserecddate,ev1.verfstatus,ev1.remark,ev1.closuredate,ev1.insuffcleardate,ev1.insuffremarks,cands.remarks as overallremark,cands.updated as overall_lastupdated,cands.overallstatus,clients.clientname,clients.tat_addrver as tat_days");

		$this->db->from('cands');

		$this->db->join("addrver",'cands.id = addrver.candsid');

		$this->db->join("clients",'(clients.id = cands.clientid AND clients.addrver = 1)');

		$this->db->join("addrverres as ev1",'ev1.addrverid = addrver.id','left');

		$this->db->join("addrverres as ev2",'(ev2.addrverid = addrver.id and ev1.id < ev2.id)','left');

		if($where_array)
		{
			$this->db->where($where_array);
		}

		$this->db->where('ev2.verfstatus is null');

		$this->db->order_by('addrver.id', 'desc');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_assigned_cases($where_array = array())
	{
		$this->db->select("addrver.*,cands.ClientRefNumber,cands.cmp_ref_no,cands.CandidateName,cands.caserecddate,cands.updated as closuredate,cands.overallstatus,membership_users.custom1");

		$this->db->from('addrver');

		$this->db->join("cands",'cands.id = addrver.candsid');

		$this->db->join("membership_users",'membership_users.id = addrver.has_case_id');
		
		$this->db->join("addrverres",'addrverres.addrverid = addrver.id','left outer');

		$this->db->where("addrverres.addrverid IS NULL",NULL,false);

		if($where_array)
		{
			$this->db->where($where_array);
		}

		$this->db->order_by('addrver.id', 'desc');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function vendor_assigned_cases($where_array)
	{
		$this->db->select("addrver.id,addrver.verder_address_name, addrver.address,addrver.city,addrver.pincode ,cands.ClientRefNumber,cands.cmp_ref_no,cands.CandidateName,cands.caserecddate,cands.overallstatus,membership_users.custom1,addrver.vendor_reject_reason,addrver.vendor_rejected_on");

		$this->db->from('addrver');

		$this->db->join("cands",'cands.id = addrver.candsid');

		$this->db->join("membership_users",'membership_users.id = addrver.has_case_id');

		if($where_array)
		{
			$this->db->where($where_array);
		}

		$this->db->order_by('addrver.id', 'desc');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function vendor_address_details($where_array = array())
	{
		$this->db->select('vendor_manager.vendor_name,vendor_manager.id');

		$this->db->from('vendor_manager');

		$this->db->join('app_users','(app_users.is_manager = vendor_manager.id and app_users.for_address = 1)');

		if($where_array)
		{
			$this->db->where($where_array);
		}

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$result = $result->result_array();

		if(!empty($result))
		{
			$result = convert_to_single_dimension_array($result,'id','vendor_name');
		}

		return $result;
	}

	public function vendor_cases($where_arry)
	{
		$this->db->select('addrver.id,addrver.address,addrver.city,addrver.state,addrver.pincode,cands.cmp_ref_no,cands.CandidateName,clients.clientname,clients.id as client_id');

		$this->db->from('addrver');

		$this->db->join("cands",'cands.id = addrver.candsid');

		$this->db->join("clients",'(clients.id = cands.clientid AND clients.addrver = 1)');

		if(!empty($where_arry))
		{
			$this->db->where($where_arry);
		}

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function vendor_assign_cases_to_app($arrwhere,$vendor_id)
	{
		if(!empty($arrwhere))
	    {
	    	$this->db->where($vendor_id);

	    	return $this->db->update_batch('addrver', $arrwhere, 'id');
	    }
	}

	public function vendor_assigned_case_to_app_user($arrwhere)
	{
		$this->db->select('addrver.id,addrver.address,addrver.city,addrver.state,addrver.pincode,cands.cmp_ref_no');

		$this->db->from('addrver');

		$this->db->join("cands",'cands.id = addrver.candsid');

		$this->db->join('app_users','app_users.id = addrver.vendor_appuser_id');

		$this->db->join("addrverres",'addrverres.addrverid = addrver.id','left');

		$this->db->where('addrverres.id is null');
		
		$this->db->where($arrwhere);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function vendor_completed_case($arrwhere)
	{
		$this->db->select('addrver.id,addrver.address,addrver.city,addrver.state,addrver.pincode,cands.cmp_ref_no,addrverres.created_on');

		$this->db->from('addrver');

		$this->db->join("cands",'cands.id = addrver.candsid');

		$this->db->join('app_users','app_users.id = addrver.vendor_appuser_id');

		$this->db->join("addrverres",'addrverres.addrverid = addrver.id');
		
		$this->db->where($arrwhere);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function get_mode_of_verification($where)
	{
		$this->db->select('mode_of_verification');

		$this->db->from('clients_details');
		
		$this->db->where($where);

        $this->db->limit(1);  

		$result  = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function insuff_reason_list($return_as_strict_row,$where)
    {   
        $this->db->select('remarks,reason');

		$this->db->from('raising_insuff_dropdown');

        $this->db->where($where); 
        
        $result_array = $this->db->get()->result_array();	
      
        if($return_as_strict_row)
		{
            if(count($result_array) >0 ) // ensure only one record has been previously inserted
            {
                $result_array = $result_array[0];
            }
        }
       

        return convert_to_single_dimension_array($result_array,'reason','reason');
    }

    public function save_trigger($arrdata)
    { 

     
			$this->db->insert("address_activity_data", $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	     
        
    }

    public function save_activity_log_trigger($arrdata)
    { 

     
			$this->db->insert("activity_log", $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	     
        
    }


	public function update_count($tablename,$arrdata,$arrwhere = array())
	{
	    if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update($this->tableName, $arrdata);

			record_db_error($this->db->last_query());

			return $result;
	    }
	    
	}

	 public function get_assign_users($tableName, $return_as_strict_row,$select_array, $where_array=array())
	{

		$this->db->select($select_array);

		$this->db->from($tableName);

		$this->db->join('roles','roles.id = user_profile.tbl_roles_id');

		$this->db->join('roles_permissions','roles_permissions.tbl_roles_id = roles.id');

        $this->db->where('roles_permissions.access_address_list_assign_executive = 1' ); 

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
   
    public function get_assign_users_id($tableName, $return_as_strict_row,$select_array, $where_array=array())
	{

		$this->db->select($select_array);

		$this->db->from($tableName);

		$this->db->join('roles','roles.id = user_profile.tbl_roles_id');

		$this->db->join('roles_permissions','roles_permissions.tbl_roles_id = roles.id');

        $this->db->where('roles_permissions.access_address_list_assign_executive = 1' ); 
      
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

	
	public function vendor_logs($where_array,$id)
    {
        $this->db->select("view_vendor_master_log.*,(select vendor_name from vendors where vendors.id= address_vendor_log.vendor_id) as vendor_name,address_vendor_log.case_id,(select user_name from user_profile where id = view_vendor_master_log.allocated_by) as allocated_by,(select user_name from user_profile where id = view_vendor_master_log.approval_by) as approval_by");

        $this->db->from('view_vendor_master_log');
        $this->db->join('address_vendor_log','address_vendor_log.id = view_vendor_master_log.case_id');

        //$this->db->join('user_profile','user_profile.id = view_vendor_master_log.allocated_by','left');

         // $this->db->join('user_profile','user_profile.id = view_vendor_master_log.approved_by','left');
        $this->db->where($where_array);

        if(!empty($id))
        {
        	 $this->db->where('address_vendor_log.case_id',$id);
        }

        $this->db->where_in('view_vendor_master_log.status',array('0','1','2','3','5'));

        $this->db->order_by("view_vendor_master_log.id", "desc");

        $result  = $this->db->get();
       // print_r($this->db->last_query());
        return $result->result_array();
    }

    public function vendor_logs_digital($id)
    {
        $this->db->select("addrver.*,vendors.vendor_name");

        $this->db->from('addrver');

        $this->db->join('vendors','vendors.id = addrver.vendor_digital_id');


        if(!empty($id))
        {
        	 $this->db->where('addrver.id',$id);
        }

        $this->db->where('addrver.vendor_digital_id',25);
       
     
        $result  = $this->db->get();

        return $result->result_array();
    }

    public function select_vendor_result_log($where_array,$id)
    {

        $this->db->select("view_vendor_master_log.*,(select vendor_name from vendors where vendors.id= address_vendor_log.vendor_id) as vendor_name,(select user_name from user_profile where id = view_vendor_master_log.allocated_by) as allocated_by,(select user_name from user_profile where id = view_vendor_master_log.approval_by) as approval_by,addrver.*,addrver.id as address_id,candidates_info.id as CandidateID,candidates_info.ClientRefNumber, candidates_info.cmp_ref_no,candidates_info.CandidateName,(select clientname from clients where clients.id = addrver.clientid limit 1) as clientname,
			(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,addrver.iniated_date,candidates_info.caserecddate,add_com_ref,candidates_info.entity as entity_id,candidates_info.package as package_id,candidates_info.package as package_id,(select id from addrverres where addrverres.addrverid = addrver.id) as addrverres_id,view_vendor_master_log.id as view_vendor_master_log_id");

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


    public function select_vendor_result_log_cost($where_array,$id)
    {

        $this->db->select("view_vendor_master_log.*");



        $this->db->from('view_vendor_master_log');
   

        //$this->db->join('user_profile','user_profile.id = view_vendor_master_log.allocated_by','left');

         // $this->db->join('user_profile','user_profile.id = view_vendor_master_log.approved_by','left');
        $this->db->where($where_array);

        if(!empty($id))
        {
        	 $this->db->where('view_vendor_master_log.id',$id);
        }

        $result  = $this->db->get();
        //print_r($this->db->last_query());
        return $result->result_array();
    }

     public function select_vendor_result_log_cost_details($where_array,$id)
    {

        $this->db->select("vendor_cost_details.*,(select user_name from user_profile where id = vendor_cost_details.created_by) as requested_by,(select user_name from user_profile where id = vendor_cost_details.approved_by) as approved_by");



        $this->db->from('vendor_cost_details');
   

       // $this->db->join('user_profile','user_profile.id = vendor_cost_details.created_by','left');

         // $this->db->join('user_profile','user_profile.id = view_vendor_master_log.approved_by','left');
        $this->db->where($where_array);

        if(!empty($id))
        {
        	 $this->db->where('vendor_cost_details.vendor_master_log_id',$id);
        }

        $result  = $this->db->get();

        return $result->result_array();
    }

    public function save_vendor_details($tablename,$arrdata,$arrwhere = array())
	{
	    if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update($tablename,$arrdata);
       
			record_db_error($this->db->last_query());
             
			return $result;
	    }
	 
	}


	public function save_vendor_details_costing($tablename,$arrdata)
	{
	
			$this->db->insert($tablename, $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	  
	}

     public function save_vendor_details_cancel($arrdata,$arrwhere = array())
	{
	    if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update("view_vendor_master_log", $arrdata);
       
			record_db_error($this->db->last_query());
             
			return $result;
	    }
	    
	}
    

     public function update_address_vendor_log($tablename,$arrdata,$arrwhere = array())
	{
	    if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update($tablename, $arrdata);
       
			record_db_error($this->db->last_query());
             
			return $result;
	    }
	    
	}
    
    /*public function address_vendor_details_cancel($tablename,$arrdata)
	{
	
			$this->db->insert($tablename, $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	  
	}
*/


	public function get_vendor_cost_aprroval_details()
	{
	
	   $query = "select view_vendor_master_log.id as vendor_master_log_id,view_vendor_master_log.case_id as view_vendor_master_case_id,view_vendor_master_log.trasaction_id,view_vendor_master_log.status as view_vendor_master_status,view_vendor_master_log.component as view_vendor_master_components,view_vendor_master_log.component_tbl_id as component_tbl_id,addrver.add_com_ref,addrver.stay_from,addrver.stay_to,addrver.address_type,addrver.city,addrver.pincode,addrver.state,vendor_cost_details.cost,vendor_cost_details.additional_cost,vendor_cost_details.accept_reject_cost,vendor_cost_details.id as vendor_cost_details_id,vendor_cost_details.created_on,(select user_name from user_profile where user_profile.id= address_vendor_log.created_by) as created_by,(select vendor_name from vendors where vendors.id= address_vendor_log.vendor_id) as vendor_name from view_vendor_master_log  JOIN `address_vendor_log` ON `address_vendor_log`.`id` = `view_vendor_master_log`.`case_id` JOIN `addrver` ON `addrver`.`id` = `address_vendor_log`.`case_id` JOIN `candidates_info` ON `candidates_info`.`id` = `addrver`.`candsid` LEFT JOIN vendor_cost_details 
    ON vendor_cost_details.id = (SELECT id  FROM vendor_cost_details WHERE 	vendor_master_log_id = view_vendor_master_log.id  ORDER BY id DESC LIMIT 1) WHERE (view_vendor_master_log.status != 4 and view_vendor_master_log.status != 5 and view_vendor_master_log.status != 6 and view_vendor_master_log.component = 'addrver' and view_vendor_master_log.component_tbl_id = 1)"	;

     $result = $this->db->query($query);


	return $result->result_array();
     
	}

	public function approve_cost($arrdata,$arrwhere = array())
	{
	    if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update("vendor_cost_details", $arrdata);
       
			record_db_error($this->db->last_query());
             
			return $result;
	    }
	    
	}

	public function approve_cost_vendor($arrdata,$arrwhere = array())
	{
	    if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update("view_vendor_master_log", $arrdata);
       
			record_db_error($this->db->last_query());
             
			return $result;
	    }
	    
	}
    
    
    public function reject_cost($arrdata,$arrwhere = array())
	{
	    if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update("vendor_cost_details", $arrdata);
       
			record_db_error($this->db->last_query());
             
			return $result;
	    }
	    
	}


    public function reject_cost_vendor($arrdata,$arrwhere = array())
	{
	    if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update("view_vendor_master_log", $arrdata);
       
			record_db_error($this->db->last_query());
             
			return $result;
	    }
	    
	}

    public function uploaded_venodr_cost_file($files_arry,$arrwhere )
	{


		 if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update("vendor_cost_details", $files_arry);
         
     

			record_db_error($this->db->last_query());
             
			return $result;
	    }
	}
   
  
  

  public function get_date_for_update($where_array)
    {

        $this->db->select("due_date,tat_status");

        $this->db->from('addrver');
   
        $this->db->where($where_array);

        $result  = $this->db->get();
      
        return $result->result_array();
    }


     public function update_due_date($files_arry,$arrwhere )
	{

         if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update("addrver", $files_arry);
         
    
			record_db_error($this->db->last_query());
             
			return $result;
	    }
		
	}

	 public function save_first_qc_result($files_arry,$arrwhere )
	{

         if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update("addrverres", $files_arry);
         
    
			record_db_error($this->db->last_query());
             
			return $result;
	    }
		
	}

	public function address_case_global_list_closed($where_array = array())
    {   

        $this->db->select("addrver.*,address_details.*,candidates_info.CandidateName,(select vendor_name from vendors where vendors.id = addrver.vendor_id) as vendor_name,candidates_info.ClientRefNumber,(select clientname from clients where clients.id = candidates_info.clientid) as clientname,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,addrver.id as address_id,candidates_info.id as CandidateID,candidates_info.entity,candidates_info.package,candidates_info.entity,candidates_info.clientid,candidates_info.CandidatesContactNumber,candidates_info.ContactNo1,candidates_info.ContactNo2,(select id from addrverres where addrverres.addrverid = addrver.id) as addrverres_id,address_details.id as address_details_id,address_details.created_on as credte_on");

        $this->db->from('addrver');

        $this->db->join('addrverres','addrverres.addrverid = addrver.id');


        $this->db->join('candidates_info','candidates_info.id = addrver.candsid');


        $this->db->join('address_details','(address_details.address_id = addrver.id and address_details.status = 1)');

        $this->db->where($where_array);

        $this->db->where('addrverres.var_filter_status !=','closed');


        $result = $this->db->get();

        record_db_error($this->db->last_query());

        return $result->result_array();
        
    }

    public function address_case_global_list($where_array = array())
    {   
        $where = "(address_is_mail_sent = 1 or address_is_sms_sent = 1)";
        $this->db->select("addrver.*,candidates_info.CandidateName,candidates_info.id as CandidateID,(select vendor_name from vendors where vendors.id = addrver.vendor_digital_id) as vendor_name,candidates_info.ClientRefNumber,(select clientname from clients where clients.id = candidates_info.clientid) as clientname,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,addrver.id as address_id,candidates_info.cands_email_id,candidates_info.CandidatesContactNumber,candidates_info.ContactNo1,candidates_info.ContactNo2");

        $this->db->from('addrver');

        $this->db->join("addrverres",'(addrverres.addrverid = addrver.id)','left');

        $this->db->join('candidates_info','candidates_info.id = addrver.candsid');

        $this->db->where($where_array);

        $this->db->where($where); 

        $result = $this->db->get();

        record_db_error($this->db->last_query());

        return $result->result_array();
        
    }

	public function address_case_list($where_array = array(),$where)
    {   

    	$where_condition = "view_vendor_master_log.final_status = 'clear'" ; 

        $this->db->select("view_vendor_master_log.*,CandidateName,clients.clientname,addrver.id as address_id,addrver.add_com_ref,addrver.address,addrver.   vendor_list_mode,addrver.city,addrver.state,addrver.pincode,(select vendor_name from vendors where vendors.id = addrver.vendor_id) as vendor_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select concat(vendor_executive_login.first_name,' ',vendor_executive_login.last_name) from vendor_executive_login where vendor_executive_login.id = view_vendor_master_log.has_case_id limit 1) as vendor_executive_id,candidates_info.ClientRefNumber,status.status_value");

        $this->db->from('view_vendor_master_log');

        $this->db->join('address_vendor_log','address_vendor_log.id = view_vendor_master_log.case_id');

        $this->db->join('addrver','addrver.id = address_vendor_log.case_id');

        $this->db->join('addrverres','addrverres.addrverid = addrver.id');

        $this->db->join('status','status.id = addrverres.verfstatus');

        $this->db->join('candidates_info','candidates_info.id = addrver.candsid');
       
        $this->db->join("clients",'clients.id = addrver.clientid');

        $this->db->where($where_condition);
       
        $this->db->where($where_array);

       /* if($where != "All")
        {

          $this->db->where('addrver.has_case_id', $where);

        }*/

        $this->db->order_by('view_vendor_master_log.trasaction_id', 'ASC');

        $result = $this->db->get();

        return $result->result_array();
        
    }

    public function address_case_list_insuff($where_array = array(),$where)
    {
    	$where_condition = "(view_vendor_master_log.final_status = 'candidate shifted' or view_vendor_master_log.final_status = 'unable to verify' or view_vendor_master_log.final_status = 'denied verification' or view_vendor_master_log.final_status = 'resigned'  or view_vendor_master_log.final_status = 'candidate not responding')" ;

    	$admin_status = "(addrverres.verfstatus = 1 or addrverres.verfstatus = 11 or addrverres.verfstatus = 12 or addrverres.verfstatus = 13 or addrverres.verfstatus = 14 or addrverres.verfstatus = 16 or addrverres.verfstatus = 23 or addrverres.verfstatus = 26 )";
    	
        $this->db->select("view_vendor_master_log.*,CandidateName,clients.clientname,addrver.id as address_id,addrver.add_com_ref,addrver.address,addrver.   vendor_list_mode,addrver.city,addrver.state,addrver.pincode,(select vendor_name from vendors where vendors.id = addrver.vendor_id) as vendor_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select concat(vendor_executive_login.first_name,' ',vendor_executive_login.last_name) from vendor_executive_login where vendor_executive_login.id = view_vendor_master_log.has_case_id limit 1) as vendor_executive_id,candidates_info.ClientRefNumber,status.status_value");

        $this->db->from('view_vendor_master_log');

        $this->db->join('address_vendor_log','address_vendor_log.id = view_vendor_master_log.case_id');

        $this->db->join('addrver','addrver.id = address_vendor_log.case_id');

        $this->db->join('addrverres','addrverres.addrverid = addrver.id');

        $this->db->join('status','status.id = addrverres.verfstatus');

        $this->db->join('candidates_info','candidates_info.id = addrver.candsid');
       
        $this->db->join("clients",'clients.id = addrver.clientid');
       
 
        $this->db->where($where_array);

        $this->db->where($where_condition);

        $this->db->where($admin_status);
       
      /*  if($where != "All")
        {

          $this->db->where('addrver.has_case_id', $where);

        }
*/
        $this->db->order_by('view_vendor_master_log.trasaction_id', 'ASC');
        
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



     public function update_closure_approval($table_name,$arrdata )
	{
     
			$this->db->insert($table_name, $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	    
	}
	 public function update_closure_approval_details($table_name,$arrdata,$where_array )
	{
     
	 if(!empty($where_array))
	    {
			$this->db->where($where_array);

			$result = $this->db->update($table_name, $arrdata);
         
    
			record_db_error($this->db->last_query());
             
			return $result;
	    }
	}

	public function get_client_id($where_array )
	{
     
	  $this->db->select("view_vendor_master_log.id,view_vendor_master_log.case_id as  vendor_master_log_case, address_vendor_log.id as address_vendor_log_id,address_vendor_log.case_id as vaddress_vendor_log_case,addrver.id as address_id , addrver.clientid as client_id" );
        $this->db->from('view_vendor_master_log');
       // $this->db->join('addrver','addrver.id = view_vendor_master_log.component_tbl_id');
        $this->db->join('address_vendor_log','address_vendor_log.id = view_vendor_master_log.case_id');
        $this->db->join('addrver','addrver.id = address_vendor_log.case_id');
        $this->db->join("clients",'clients.id = addrver.clientid');


        $this->db->where($where_array);
        
        $result = $this->db->get();
  
       return $result->result_array();
	}

	public function check_vendor_status_closed_or_not($where_array)
	{
     
	  $this->db->select("view_vendor_master_log.id,view_vendor_master_log.final_status,view_vendor_master_log.case_id as  vendor_master_log_case, address_vendor_log.id as address_vendor_log_id,address_vendor_log.case_id as vaddress_vendor_log_case,addrver.id as address_id" );

        $this->db->from('view_vendor_master_log');
      
        $this->db->join('address_vendor_log','address_vendor_log.id = view_vendor_master_log.case_id');

        $this->db->join('addrver','addrver.id = address_vendor_log.case_id');
    
        $this->db->where($where_array);

        $this->db->where("(view_vendor_master_log.final_status = 'wip' or view_vendor_master_log.final_status = 'insufficiency')");
        
        $result = $this->db->get();
 
       return $result->result_array();
	}

	public function check_vendor_status_insufficiency($where_array )
	{
     
	  $this->db->select("view_vendor_master_log.id,view_vendor_master_log.final_status,view_vendor_master_log.case_id as  vendor_master_log_case, address_vendor_log.id as address_vendor_log_id,address_vendor_log.case_id as vaddress_vendor_log_case,addrver.id as address_id" );

        $this->db->from('view_vendor_master_log');
      
        $this->db->join('address_vendor_log','address_vendor_log.id = view_vendor_master_log.case_id');

        $this->db->join('addrver','addrver.id = address_vendor_log.case_id');
    
        $this->db->where($where_array);

        $this->db->where('addrver.vendor_id !=', 0);

        $result = $this->db->get();
 
       return $result->result_array();
	}
     
     
    public function get_user_id($where_array)
	{
		$this->db->select('id');

		$this->db->from('user_profile');

		$this->db->where($where_array);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->row('id');
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

	public function select_file_address($select_array,$where_array)
	{
		$this->db->select($select_array);

		$this->db->from('addrver_files');

		$this->db->where($where_array);

		$this->db->order_by('serialno', 'ASC');
		
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

	public function select_file_vendor($select_array,$where_array,$where_array_id)
	{
		$this->db->select($select_array);

		$this->db->from('view_vendor_master_log_file');

		$this->db->join('view_vendor_master_log','view_vendor_master_log.id = view_vendor_master_log_file.view_venor_master_log_id');

        $this->db->join('address_vendor_log','address_vendor_log.id = view_vendor_master_log.case_id');

        $this->db->join("addrver",'addrver.id = address_vendor_log.case_id');

		$this->db->where($where_array);

		$this->db->where('addrver.id',$where_array_id);
		
		$this->db->order_by('view_vendor_master_log_file.serialno','asc');

		
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
   
    public function select_file2($select_array,$where_array)
	{
		$this->db->select($select_array);

		$this->db->from('addrver_files');

		$this->db->where($where_array);

		$this->db->order_by('id', 'ASC');
		
		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	 public function select_addrverres_details($select_array,$where_array)
	{
		$this->db->select($select_array);

		$this->db->from('addrverres');

		$this->db->where($where_array);
		
		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function select_candidate_from_file($id)
	{
		$this->db->select('file_name');

		$this->db->from('addrver_files');

		$this->db->join('addrver','addrver.id = addrver_files.addrver_id');
       
        $this->db->join('candidates_info','candidates_info.id = addrver.candsid');

		$this->db->where('candidates_info.id',$id);

		
		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	} 	

	public function update_final_status_of_vendor($address_id)
	{

	    if(!empty($address_id))
	    {

			$sql =  'UPDATE view_vendor_master_log LEFT JOIN address_vendor_log ON address_vendor_log.case_id = view_vendor_master_log.id LEFT JOIN addrver ON addrver.id = address_vendor_log.case_id LEFT JOIN addrverres ON addrverres.addrverid = addrver.id SET view_vendor_master_log.final_status = "closed" WHERE (addrverres.verfstatus = 9 or addrverres.verfstatus = 27 or addrverres.verfstatus = 28) and (view_vendor_master_log.component = "addrver" and view_vendor_master_log.component_tbl_id = 1 and (view_vendor_master_log.final_status = "wip" or view_vendor_master_log.final_status = "insufficiency")) AND addrver.id = '.$address_id; 

            $query = $this->db->query($sql);
            
            return $query;
           
	    }

	    
	} 


	public function select_client_list_view_address($tableName, $return_as_strict_row,$select_array, $where1=array())
	{
		$this->db->select($select_array);
       
		$this->db->from($tableName);

		$this->db->join("addrver",'addrver.clientid = clients.id');

		$this->db->join("user_profile",'user_profile.id = addrver.has_case_id','left');

		$this->db->join("candidates_info",'candidates_info.id = addrver.candsid');

	
		$this->db->join("addrverres",'addrverres.addrverid = addrver.id','left');


		$this->db->join("addrver_insuff",'(addrver_insuff.addrverid = addrver.id AND  addrver_insuff.status = 1 )','left');

		$this->db->join("status",'status.id = addrverres.verfstatus','left');

        $this->db->where($this->filter_where_cond($where1)); 

        $this->db->where('clients.status',1); 


            if(isset($where1['start_date']) &&  $where1['start_date'] != '' && isset($where1['end_date']) &&  $where1['end_date'] != '')	
		    { 

		     	$start_date  =  $where1['start_date'];
	            $end_date  =  $where1['end_date'];

	            if($where1['status'] == "Closed")
                {
	         
		     	$where3 = "DATE_FORMAT(`addrverres`.`closuredate`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}

		     	if($where1['status'] == "Insufficiency")
                {
	         
		     	$where3 = "DATE_FORMAT(`addrver_insuff`.`insuff_raised_date`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

		     	}
                
                $this->db->where($where3); 

		    } 
   
		$result = $this->db->get();

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

	public function get_vendor_id($state)
	{ 
		$this->db->select('id');
       
		$this->db->from('vendors');

		$this->db->like('vendors.address_state',$state); 

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$result_array = $result->result_array();

		return $result_array;

	}
	public function get_vendor_id_find_state($state)
	{ 
		$this->db->select('id');
       
		$this->db->from('vendors');

		if(!empty($state))
		{

		$this->db->where("find_in_set('".$state."', address_state)"); 
		}
	
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$result_array = $result->result_array();

		return $result_array;

	}

	public function get_vendor_id_find_city($city)
	{ 
		$this->db->select('id');
       
		$this->db->from('vendors');

		
		if(!empty($city))
		{

		$this->db->where("find_in_set('".$city."', address_city)"); 
		}

		$result = $this->db->get();

		record_db_error($this->db->last_query());

		$result_array = $result->result_array();

		return $result_array;

	}
    
    public function get_vendor_id_drop_down($state = array())
	{ 
		$this->db->select('id,vendor_name');
       
		$this->db->from('vendors');

		if(!empty($state))
        {

		  $this->db->like('vendors.address_state',$state);

        }

		$result = $this->db->get();

		record_db_error($this->db->last_query());
        
		$result_array = $result->result_array();

		return convert_to_single_dimension_array($result_array,'id','vendor_name');

	}

	
    public function save_vendor_log($arrdata,$arrwhere = array())
	{
	    if(!empty($arrwhere))
	    {
			$this->db->where($arrwhere);

			$result = $this->db->update('address_vendor_log', $arrdata);
       
			record_db_error($this->db->last_query());
             
			return $result;
	    }
	    else
	    {
			$this->db->insert('address_vendor_log', $arrdata);

			record_db_error($this->db->last_query());

			return $this->db->insert_id();
	    }
	}

	public  function get_reporting_manager_id($clientid)
    {
    	
        $this->db->select('clientmgr');

		$this->db->from('clients');

		$this->db->where('clients.id',$clientid);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
    
    }

    public function check_address_exists_in_candidate($where_array)
    { 
    	$this->db->select('id');

		$this->db->from('addrver');

		$this->db->where($where_array);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();

    }

    public function get_address_details_for_approval($case_id)
    {
    	$this->db->select('address_vendor_log.id as address_vendor_log_id,clients.clientname,addrver.add_com_ref,candidates_info.CandidateName,candidates_info.CandidatesContactNumber,candidates_info.ContactNo1,candidates_info.ContactNo2,addrver.vendor_id,addrver.address,addrver.city,addrver.pincode,addrver.state');

		$this->db->from('address_vendor_log');

		$this->db->join('addrver','addrver.id = address_vendor_log.case_id ');

        $this->db->join('candidates_info','candidates_info.id = addrver.candsid');

        $this->db->join('clients','clients.id = addrver.clientid');

		$this->db->where('address_vendor_log.id',$case_id);

		$result  = $this->db->get();
     
		record_db_error($this->db->last_query());
		
		return $result->result_array();
    }

    public function vendor_email_id($where_array)
    {
    	$this->db->select('vendors.*');

		$this->db->from('vendors');
  
        if($where_array)
        {

		  $this->db->where($where_array);

	    }

		$result  = $this->db->get();
     
		record_db_error($this->db->last_query());
		
		return $result->result_array();
    }


    public function get_address_details_for_insuff_mail($where_array)
    {
    	$this->db->select('addrver.*,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,(select clientname from clients where clients.id = candidates_info.clientid limit 1) as clientname');

		$this->db->from('addrver');

		$this->db->join('candidates_info','candidates_info.id = addrver.candsid');

        if($where_array)
        {
		  $this->db->where($where_array);
	    }

		$result  = $this->db->get();
     
		record_db_error($this->db->last_query());
		
		return $result->result_array();
    }

    public function get_user_email_id($where_array)
	{
		$this->db->select('email');

		$this->db->from('user_profile');

		$this->db->where($where_array);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->row('email');
	}

	public function select_vendor()
	{ 

        $this->db->select('addrver.id');

		$this->db->from('addrver');

		$this->db->join('address_vendor_log','address_vendor_log.case_id = addrver.id');

		$this->db->where('(addrver.vendor_id > 1)');

		$this->db->where('address_vendor_log.status',0);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function check_transaction_id_exists($address_id = array())
	{ 
		$this->db->select('view_vendor_master_log.id');
       
		$this->db->from('addrver');

	    $this->db->join('address_vendor_log','address_vendor_log.case_id = addrver.id');
    
        $this->db->join('view_vendor_master_log','(view_vendor_master_log.case_id = address_vendor_log.id  and component = "addrver" and component_tbl_id = "1" and view_vendor_master_log.status = "1")');


		$this->db->like('addrver.id',$address_id);

		$result = $this->db->get();

		record_db_error($this->db->last_query());
  
		$result_array = $result->result_array();

		return $result_array ;

	}

	public function check_transaction_final_status($address_id = array())
	{ 
		$this->db->select('view_vendor_master_log.*');
       
		$this->db->from('addrver');

	    $this->db->join('address_vendor_log','address_vendor_log.case_id = addrver.id');
    
        $this->db->join('view_vendor_master_log','(view_vendor_master_log.case_id = address_vendor_log.id  and component = "addrver" and component_tbl_id = "1" and view_vendor_master_log.status = 1)');


		$this->db->where('addrver.id',$address_id);

		$this->db->where('view_vendor_master_log.final_status','wip');

		$result = $this->db->get();

		record_db_error($this->db->last_query());
  
		$result_array = $result->result_array();

		return $result_array ;

	}

    public function check_address_details_status($address_id = array())
    {

    	$this->db->select('addrver.*');
       
		$this->db->from('addrver');

	    $this->db->join('address_details','(address_details.address_id = addrver.id and address_details.status = 1)');
        

		$this->db->where('addrver.id',$address_id);

		$result = $this->db->get();

		record_db_error($this->db->last_query());
  
		$result_array = $result->result_array();

		return $result_array ;


    }

	public function select_address_dt($table_name,$select_array,$where_array)
	{
		$this->db->select($select_array);

		$this->db->from($table_name);

		$this->db->where($where_array);

		$result  = $this->db->get();

		record_db_error($this->db->last_query());
        
        return $result->result_array();    
	} 

	public function address_history($arrwhere)
	{
		if(!empty($arrwhere)) {
			$this->db->select('addrver.*,(select clientname from clients where clients.id = candidates_info.clientid) as clientname,candidates_info.CandidateName,candidates_info.cands_email_id as candidate_email,candidates_info.CandidatesContactNumber as candidate_contact');
	    	$this->db->from('addrver');
	    	$this->db->join('candidates_info','candidates_info.id = addrver.candsid');
	    	
	    	$this->db->where($arrwhere);
	    	$this->db->order_by('addrver.id','desc');
	    	$result = $this->db->get();
			record_db_error($this->db->last_query());
			
			return $result->result_array();    
		}
    	
	} 

	public function check_user_clicked($tableName, $arrdata,$arrwhere = array())
	{
		$result = false;
		    
	    if($tableName && !empty($arrwhere)) {
			$this->db->where($arrwhere);
			$this->db->set('user_clicked', 'user_clicked+1', FALSE);
			$result = $this->db->update($tableName, $arrdata);
			record_db_error($this->db->last_query());
	    }
	    return $result;
	} 

	public function get_report_details($arrwhere = array())
	{		
    	$this->db->select('addrver.*,address_details.*,candidates_info.CandidateName,candidates_info.CandidatesContactNumber,candidates_info.cmp_ref_no,address_details.created_on as verification_update_on,addrver.id as address_id');
    	$this->db->from('addrver');
    	$this->db->join('candidates_info','candidates_info.id = addrver.candsid');
    	$this->db->join('address_details','address_details.address_id = addrver.id');
    	if(!empty($arrwhere)) {
    		$this->db->where($arrwhere);
    	}
    	
    	$result  = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	} 

	public function user_email_count($arrwhere = array())
	{
		$result = false;
		if(!empty($arrwhere))
	    {
		    $this->db->where($arrwhere);
			$this->db->set('is_mail_sent', 'is_mail_sent+1', FALSE);
			$this->db->set('last_email_on', "'".date(DB_DATE_FORMAT)."'", FALSE);
			$result = $this->db->update('addrver');
		}
		    return $result;
	}


	public function user_sms_count($arrwhere = array())
	{
		$result = false;
		if(!empty($arrwhere))
		{
		    $this->db->where($arrwhere);
			$this->db->set('is_sms_sent', 'is_sms_sent+1', FALSE);
			$result = $this->db->update('addrver');
		}
		return $result;
	}
    

    public function follow_up_activity_log_records($where_array)
	{

		$table = $where_array['component_type'].'_activity_data';
		
		$this->db->select("activity_status,activity_mode,activity_type,action,next_follow_up_date,remarks,created_on,(select user_name from user_profile where id = $table.created_by) as res_created_by");

		$this->db->from($table);

		$this->db->where('comp_table_id',$where_array['comp_table_id']);

		$this->db->where('action',$where_array['action']);

		$this->db->order_by("$table.created_on",'DESC');
		 
		$result  = $this->db->get();
		
		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

	public function get_all_digital_address($where_array = array())
	{	
        $where = "(address_is_mail_sent = 1 or address_is_sms_sent = 1)";


        $this->db->select("addrver.*,clients.clientname,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name,(select entity_package_name from entity_package where entity_package.id = candidates_info.package limit 1) as package_name,candidates_info.ClientRefNumber,candidates_info.cmp_ref_no,candidates_info.CandidateName,candidates_info.NameofCandidateFather,candidates_info.CandidatesContactNumber,candidates_info.ContactNo1,candidates_info.ContactNo2,status.status_value as verfstatus,status.filter_status as filter_status,a1.closuredate,(select concat(`user_profile`.`firstname`,' ',`user_profile`.`lastname`) from user_profile where user_profile.id = addrver.has_case_id limit 1) as executive_name");
 
		$this->db->from('addrver');

		$this->db->join("clients",'clients.id = addrver.clientid');

		$this->db->join("candidates_info",'candidates_info.id = addrver.candsid');

		$this->db->join("addrverres as a1",'a1.addrverid = addrver.id','left');

		$this->db->join("addrverres as a2",'(a2.addrverid = addrver.id and a1.id < a2.id)','left');

		$this->db->join("status",'status.id = a1.verfstatus','left');

		$this->db->where('a2.verfstatus is null');

		$this->db->where($where_array);

		$this->db->where($where);

		$this->db->order_by('addrver.id', 'ASC');
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

		return $result->result_array();
	}

	public function select_address_approve_list($where_array = array())
	{
		
	
		$this->db->select('address_vendor_log.id');

		$this->db->from('address_vendor_log');

		$this->db->join('addrver','addrver.id = address_vendor_log.case_id');

		$this->db->join("addrverres",'addrverres.addrverid = addrver.id','left');

		$this->db->join("clients",'clients.id = addrver.clientid');

		$this->db->join("candidates_info",'candidates_info.id = addrver.candsid','left');

		$this->db->where($where_array);
        
	
        $this->db->where('(addrverres.var_filter_status = "wip" or addrverres.var_filter_status = "WIP")');
        
        $this->db->where("address_vendor_log.created_on <= DATE_SUB(NOW(), INTERVAL 30 MINUTE)", NULL, FALSE);

  
		$this->db->order_by('address_vendor_log.id', 'desc');
		
		$result  = $this->db->get();

		record_db_error($this->db->last_query());
	
		return $result->result_array();
	}

	public function get_approve_massage($where_array = array())
	{
		
	
		$this->db->select('address_vendor_log.id');

		$this->db->from('address_vendor_log');

		$this->db->join('addrver','addrver.id = address_vendor_log.case_id');

		$this->db->join("addrverres",'addrverres.addrverid = addrver.id','left');

		$this->db->where($where_array);
        
        $this->db->where('(addrverres.var_filter_status = "wip" or addrverres.var_filter_status = "WIP")');
		
		$result  = $this->db->get();

		record_db_error($this->db->last_query());
	
		return $result->result_array();
	}


}
?>