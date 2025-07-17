<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reject_cases_model extends CI_Model
{
	function __construct()
    {
		$this->tableName = '';

		$this->primaryKey = 'id';
	}

	public function select_overall_reject_cases()
	{
		
		$this->db->select("empver.*,'Employment' as component_name,(select clientname from clients where clients.id = empver.clientid limit 1) as clientname,emp_com_ref as component_id,(select entity_package_name from entity_package where entity_package.id = candidates_info.entity limit 1) as entity_name, (select entity_package_name from entity_package  where entity_package.id= candidates_info.package limit 1) as package_name,candidates_info.CandidateName");

		$this->db->from('empver');

		$this->db->join("candidates_info",'candidates_info.id = empver.candsid');

		$this->db->join("candidates_info",'candidates_info.id = empver.candsid');

        $this->db->where('empver.reject_status',2);

		$empver  = $this->db->get()->result_array();
		
        return $empver; 
		
		//return array_merge($empver,$addrver,$education,$reference,$court,$glodbver,$drug_narcotis,$pcc,$identity,$credit_report);
	}
}
?>