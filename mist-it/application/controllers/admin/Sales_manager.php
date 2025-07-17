<?php defined('BASEPATH') or exit('No direct script access allowed');
class Sales_manager extends MY_Controller
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

        $this->perm_array = array('page_id' => 28);

        $this->load->model(array('sales_login_model'));
    }

    public function index()
    {
        $data['header_title'] = "Sales Manager List";

        $this->load->view('admin/header', $data);

        $this->load->view('admin/sales_manager_list');

        $this->load->view('admin/footer');
    }

    public function fetch_roles()
    {
        if ($this->input->is_ajax_request()) {
            $results = $this->sales_login_model->sales_manager_list(false, array(), array('status' => STATUS_ACTIVE));

            if ($this->permission['access_view'] == true) {
                foreach ($results as $key => $value) {

                    $id = encrypt($value['id']);

                    $data_arry[] = array('email_id' => $value['email_id'],
                        'first_name' => $value['first_name'],
                        'last_name' => $value['last_name'],
                        'mobile_no' => $value['mobile_no'],
                        'created_by' => $value['created_by'],
                        'created_on' => convert_db_to_display_date($value['created_on'], DB_DATE_FORMAT),
                        'created_by' => $value['created_by'],
                        'edit' => ADMIN_SITE_URL . 'sales_manager/view_details/' . $id,
                    );
                }
                $json_array = array("draw" => $data_arry, 'status' => SUCCESS_CODE);
                echo_json($json_array);
            } else {
                permission_denied();
            }
        } else {
            permission_denied();
        }
    }

    public function add()
    {
        $data['header_title'] = "Sales Manager";

        $this->load->view('admin/header', $data);

        $this->load->view('admin/sales_manager_add_view');

        $this->load->view('admin/footer');
    }

    public function save()
    {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('first_name', 'First Name', 'required');

            $this->form_validation->set_rules('last_name', 'Last Name', 'required');

            $this->form_validation->set_rules('username', 'Username', 'required|is_unique[sales_login.username]');

            $this->form_validation->set_rules('email_id', 'Email ID', 'required');

            $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');

            $this->form_validation->set_rules('crm_password', 'Comfirm Password', 'required|min_length[6]|matches[password]');

            $this->form_validation->set_message('min_length', 'Password content min 6 charecter long');

            $this->form_validation->set_message('matches', 'Comfirm password same as Password');

            if ($this->form_validation->run() == false) {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors();
            } else {
                $frm_details = $this->input->post();

                $fields = array('first_name' => $frm_details['first_name'],
                    'last_name' => $frm_details['last_name'],
                    'username' => $frm_details['username'],
                    'email_id' => $frm_details['email_id'],
                    'city' => $frm_details['city'],
                    'address' => $frm_details['address'],
                    'mobile_no' => $frm_details['mobile_no'],
                    'status' => STATUS_ACTIVE,
                    'created_by' => $this->user_info['id'],
                    'created_on' => date(DB_DATE_FORMAT),
                    'modified_by' => $this->user_info['id'],
                    'modified_on' => date(DB_DATE_FORMAT),
                );
                $fields = array_map('strtolower', $fields);

                $fields['password'] = create_password($frm_details['crm_password']);

                if ($this->sales_login_model->save($fields)) {
                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'Account Created Successfully';

                    $json_array['redirect'] = ADMIN_SITE_URL . 'sales_manager';
                } else {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Something went wrong,please try again';
                }
            }
            echo_json($json_array);
        } else {
            permission_denied();
        }
    }

    public function is_email_id_valid()
    {
        if ($this->input->is_ajax_request()) {
            $username = $this->input->post('username');

            $result = $this->sales_login_model->select(true, array('id'), array('username' => $username));

            if (!empty($result)) {
                echo 'false';
            } else {
                echo "true";
            }
        } else {
            permission_denied();
        }
    }

    public function view_details($id = false)
    {
        $is_exits = $this->sales_login_model->select(true, array('*'), array('id' => decrypt($id), 'status' => STATUS_ACTIVE));

        if ($id && !empty($is_exits)) {
            $data['header_title'] = " " . $is_exits['first_name'] . " 's Details";

            $data['details'] = $is_exits;

            $this->load->view('admin/header', $data);

            $this->load->view('admin/sales_manager_edit_view');

            $this->load->view('admin/footer');
        } else {
            show_404();
        }

    }

    public function update_details()
    {
        if ($this->input->post()) {
            $frm_details = $this->input->post();

            $this->form_validation->set_rules('update_id', 'id', 'required');

            $this->form_validation->set_rules('first_name', 'First Name', 'required');

            $this->form_validation->set_rules('last_name', 'Last Name', 'required');

            $this->form_validation->set_rules('email_id', 'Email ID', 'required');

            $this->form_validation->set_rules('password', 'Password', 'min_length[5]');

            $this->form_validation->set_rules('crm_password', 'Comfirm Password', 'min_length[5]|matches[password]');

            $this->form_validation->set_message('min_length', 'Password content min 8 charecter long');

            $this->form_validation->set_message('matches', 'Comfirm password same as Password');

            if ($this->form_validation->run() == false) {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors();
            } else {
                $member_arry = array('first_name' => $frm_details['first_name'],
                    'last_name' => $frm_details['last_name'],
                    'email_id' => $frm_details['email_id'],
                    'city' => $frm_details['city'],
                    'address' => $frm_details['address'],
                    'mobile_no' => $frm_details['mobile_no'],
                    'status' => STATUS_ACTIVE,
                    'modified_on' => $this->user_info['id'],
                    'modified_by' => date(DB_DATE_FORMAT),
                );

                $member_arry = array_map('strtolower', $member_arry);

                if ($frm_details['crm_password'] != "") {
                    $member_arry['password'] = create_password($frm_details['crm_password']);
                }

                $result = $this->sales_login_model->save($member_arry, array('id' => $frm_details['update_id']));

                if ($result) {
                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'Updated Successfully';

                    $json_array['redirect'] = ADMIN_SITE_URL . 'sales_manager';
                } else {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Something went wrong,please try again';
                }
            }
            echo_json($json_array);
        }
    }

    public function sales_manager_list()
    {
        if ($this->input->is_ajax_request()) {
            $lists = $this->sales_login_model->select(false, array("id,concat(first_name,' ',last_name) as fullname
                ", ), array('status' => STATUS_ACTIVE));

            $json_array['message'] = convert_to_single_dimension_array($lists, 'id', 'fullname');

            echo_json($json_array);
        }
    }
}
