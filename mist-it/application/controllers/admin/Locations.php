<?php defined('BASEPATH') or exit('No direct script access allowed');

class Locations extends MY_Controller
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

        $this->perm_array = array('page_id' => 31);

        $this->load->model('location_master_model');
    }

    public function index()
    {
        $data['header_title'] = "Location Master";

        $data['holidays_lists'] = array();

        $this->load->view('admin/header', $data);

        $this->load->view('admin/locations_list');

        $this->load->view('admin/footer');
    }

    public function fetch_location_list()
    {
        if ($this->input->is_ajax_request()) {
            $results = $this->location_master_model->select(false, array('location_master.*,(select user_name from user_profile where user_profile.id = location_master.created_by) as created_user,(select
                state from states where states.id = location_master.state_id) state_name', ));

            //show($results);
            // die;
            foreach ($results as $key => $value) {
                $id = encrypt($value['id']);
                //$status = ($value['status'] == 1) ? 'Active' : 'Inactive';
                $data_arry[] = array('location' => ucwords($value['location']),
                    'pin_code' => ucwords($value['pincode']),
                    'state_name' => ucwords($value['state_name']),
                    'created_on' => convert_db_to_display_date($value['created_on'], DB_DATE_FORMAT),
                    'created_by' => $value['created_user'],
                    'edit' => ADMIN_SITE_URL . 'vendors/view_details/' . $id,
                    //'status' => $status
                );
            }

            $json_array = array("draw" => $data_arry, 'status' => SUCCESS_CODE);

            echo_json($json_array);
        } else {
            permission_denied();
        }
    }
}
