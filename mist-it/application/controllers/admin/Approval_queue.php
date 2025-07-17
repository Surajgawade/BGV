<?php defined('BASEPATH') or exit('No direct script access allowed');

class Approval_queue extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!$this->is_admin_logged_in()) {
            redirect('admin/login');
            exit();
        }

        $this->perm_array = array('page_id' => 33);
        $this->load->model(array('address_vendor_log_model'));
    }

    public function index()
    {
        $data['header_title'] = "Vendor Approve List";

        $data['lists'] = $this->address_vendor_log_model->get_new_list(array('address_vendor_log.status' => 0));

        $this->load->view('admin/header', $data);

        $this->load->view('admin/vendor_approve_queue');

        $this->load->view('admin/footer');
    }

}
