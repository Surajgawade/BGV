<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Holiday_model extends CI_Model
{
	function __construct()
    {
		$this->tableName = 'holiday_dates';

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
			   
			
			$where1['addrverres.var_filter_status'] = $where_arry['status'];

		 
		}

		if(isset($where_arry['sub_status']) &&  $where_arry['sub_status'] != '' &&  $where_arry['sub_status'] != 0)	
		{
			$where1['addrverres.verfstatus'] = $where_arry['sub_status'];
		}

		if(isset($where_arry['client_id']) &&  $where_arry['client_id'] != 0)	
		{
			$where1['addrver.clientid'] = $where_arry['client_id'];

		}


		return $where1;
	}

	/* private function get_holidays_list($where_array = array())
    {
        
            $this->CI =& get_instance();

            $this->CI->db->select('id,holiday_date,remark,created_on,(select user_name from user_profile where user_profile.id = holiday_dates.created_by) as created_user_name');

            $this->CI->db->from('holiday_dates');

            $this->CI->db->where($where_array);

            $result  = $this->CI->db->get();

            record_db_error($this->CI->db->last_query());
            
            $result = $result->result_array();

            if(!empty($where_array) && !empty($result)) {
                $result = $result[0];
            }

            return $result;
      
    }*/

    public function get_holidays_list($where_array = array(),$where,$columns)
	{

		$this->db->select('id,holiday_date,remark,created_on,(select user_name from user_profile where user_profile.id = holiday_dates.created_by) as created_user_name');

		$this->db->from('holiday_dates');

		 if(isset($where['filter_by_month']) &&  $where['filter_by_month'] != '')	
		     {  
		     	$filter_by_month  =  $where['filter_by_month'];
	          
		     	$where_month = "DATE_FORMAT(`holiday_dates`.`holiday_date`,'%m') = $filter_by_month";
               
                $this->db->where($where_month); 

		     }

		 if(isset($where['filter_by_year']) &&  $where['filter_by_year'] != '')	
		     {  
		     	$filter_by_year  =  $where['filter_by_year'];

	            $where_year = "DATE_FORMAT(`holiday_dates`.`holiday_date`,'%Y') = $filter_by_year";

                $this->db->where($where_year); 

		     }    

        $this->db->where($where_array);
		
		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 
          
			$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		}
		else
		{
			$this->db->order_by('holiday_dates.holiday_date','DESC');
		}
		
		$result = $this->db->get();

		record_db_error($this->db->last_query());

	    $result = $result->result_array();


		if(!empty($where_array) && !empty($result)) {
                $result = $result[0];
            }

		
		return $result;
	}

	public function get_holidays_list_count($where_array = array(),$where,$columns)
	{
		$this->db->select('id,holiday_date,remark,created_on,(select user_name from user_profile where user_profile.id = holiday_dates.created_by) as created_user_name');

		$this->db->from('holiday_dates');

		 if(isset($where['filter_by_month']) &&  $where['filter_by_month'] != '')	
		     {  
		     	$filter_by_month  =  $where['filter_by_month'];
	          
		     	$where_month = "DATE_FORMAT(`holiday_dates`.`holiday_date`,'%m') = $filter_by_month";
               
                $this->db->where($where_month); 

		     }

		 if(isset($where['filter_by_year']) &&  $where['filter_by_year'] != '')	
		     {  
		     	$filter_by_year  =  $where['filter_by_year'];

	            $where_year = "DATE_FORMAT(`holiday_dates`.`holiday_date`,'%Y') = $filter_by_year";

                $this->db->where($where_year); 

		     }    


		$this->db->where($where_array);
		
		if(!empty($where['order']))
		{
			$column_name_index = $where['order'][0]['column'];
			$order_by = $where['order'][0]['dir']; 
          
			$this->db->order_by($where['columns'][$column_name_index]['data'],$order_by);
		}
		else
		{
			$this->db->order_by('holiday_dates.id','DESC');
		}
		
		$result = $this->db->get();


		record_db_error($this->db->last_query());
		
		return $result->result_array();
	}

    public function holiday_export($start_from,$end_to)
	{

		$start_from = date('Y-m-d',strtotime($start_from));
	    $end_to = date('Y-m-d',strtotime($end_to));

	    $where = "DATE_FORMAT(`holiday_dates`.`holiday_date`,'%Y-%m-%d') BETWEEN '$start_from' AND '$end_to'";

	
		$this->db->select('id,holiday_date,remark,created_on,(select user_name from user_profile where user_profile.id = holiday_dates.created_by) as created_user_name');

		$this->db->from('holiday_dates');

		$this->db->where($where);
		

		$this->db->order_by('holiday_dates.holiday_date','ASC');


        $result = $this->db->get();
       

		record_db_error($this->db->last_query());
		
		return $result->result_array();

	}
	

	 public function get_holidays_exists_or_not($where_array)
	 {
	    $this->db->select('id');

		$this->db->from('holiday_dates');

		$this->db->where($where_array);


        $result = $this->db->get();
       

		record_db_error($this->db->last_query());
		
		return $result->result_array();	
	 }
	
     
}
?>