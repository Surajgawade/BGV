<?php defined('BASEPATH') or exit('No direct script access allowed');

class Reject_cases extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        if(!$this->input->is_ajax_request())
        {   
            $the_session = array("contoller_name" => $this->router->fetch_class(), "method_name" => $this->router->fetch_method());
            $this->session->set_userdata('controller_mothod', $the_session);
        }

        if (!$this->is_admin_logged_in()) {
            redirect('admin/login');
            exit();
        }

        $this->perm_array = array('page_id' => 50);

    }

    public function index()
    {
        $this->load->model('reject_cases_model');

        $data['header_title'] = "Component Rejet Cases";

        
        $data['component_reject_cases_details'] = $this->reject_cases_model->select_overall_reject_cases();

        $this->load->view('admin/header', $data);

        $this->load->view('admin/component_reject_cases');
        
        $this->load->view('admin/footer');

    }
}
?>