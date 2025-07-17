<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends MY_Vendor_Cotroller
{    
    function __construct()
    {
        parent::__construct();
        $this->perm_array = array('page_name' => 'addrver');
    }

    public function index()
    {   
        $data['header_title'] = "Court Cases View";

        //$data['lists'] = $this->vendor_common_model->court_case_list(array('view_vendor_master_log.vendor_id' => $this->vendor_info['vendors_id'],'view_vendor_master_log.status =' => 1,'component' => 'courtver'));
       
        $this->load->view('vendor/header',$data);
        
        $this->load->view('vendor/court_list');

        $this->load->view('vendor/footer');
    }
}