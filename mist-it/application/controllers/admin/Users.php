<?php defined('BASEPATH') or exit('No direct script access allowed');
class Users extends MY_Controller
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

        $this->perm_array = array('page_id' => 1);

        $this->title = array(0 => 'Select Title', 'mr' => 'Mr', 'mrs' => 'Mrs', 'miss' => 'Miss', 'ms' => 'Ms');
        $this->load->model(array('users_model'));
    }

    public function index()
    {

        $data['header_title'] = "Users";

        $this->load->view('admin/header', $data);

        $this->load->view('admin/users_view');

        $this->load->view('admin/footer');
    }

    public function fetch_user()
    {

        if ($this->input->is_ajax_request()) {

            $params = $add_candidates = $data_arry = $columns = array();

            $params = $_REQUEST;

            $columns = array('department', 'created_on', 'user_name', 'email', 'firstname', 'lastname');

            $where_arry = array();

            if ($this->permission['access_admin_list_view'] == true) {
                $data_arry = array();

                $results = $this->users_model->user_role_gorup_details_user($where_arry, $params, $columns);

                $totalRecords = count($this->users_model->user_role_gorup_details_user($where_arry, $params, $columns));

                $x = 0;

                // $results = $this->users_model->user_role_gorup_details();

                foreach ($results as $result) {

                    $status = ($result['status'] == 1) ? "Active" : "Inactive";

                    if ($result['status'] == 0) {$account = 'Inactive';} elseif ($result['status'] == 1) {
                        $account = 'Active';} elseif ($result['status'] == 2) {$account = 'Deleted';} else {};

                    $status = $account;

                    $data_arry[$x]['id'] = $x + 1;
                    $data_arry[$x]['user_name'] = ucwords($result['user_name']);
                    $data_arry[$x]['firstname'] = ucwords($result['firstname']);
                    $data_arry[$x]['created_by'] = $result['created_by'];
                    $data_arry[$x]['encry_id'] = ADMIN_SITE_URL . 'users/edit/' . encrypt($result['id']);
                    $data_arry[$x]['created_on'] = get_date_from_indian_timestamp($result['created_on']);
                    $data_arry[$x]['lastname'] = $result['lastname'];
                    $data_arry[$x]['email'] = ucwords($result['email']);
                    $data_arry[$x]['role_name'] = $result['role_name'];
                    $data_arry[$x]['status'] = $status;
                    $data_arry[$x]['department'] = $result['department'];

                    $x++;

                    unset($results_client_manager);
                    unset($results_sales_manager);

                }

                $json_data = array(
                    "draw" => intval($params['draw']),
                    "recordsTotal" => intval($totalRecords),
                    "recordsFiltered" => intval($totalRecords),
                    "data" => $data_arry,
                );

                echo_json($json_data);

            } else {
                permission_denied();
            }
        } else {
            permission_denied();
        }
    }

    public function add()
    {
        if ($this->permission['access_admin_list_add'] == true) {
            $data['header_title'] = "Create User";

            $data['title'] = $this->title;

            $data['states'] = $this->get_states();

            $data['clientmgr'] = $this->users_list();

            $this->load->view('admin/header', $data);

            $this->load->view('admin/users_add');

            $this->load->view('admin/footer');
        }
    }

    public function save_users()
    {
        if ($this->input->is_ajax_request() && $this->permission['access_admin_list_add'] == true) {
            $user_name = $this->input->post('user_name');
            $ip_address = $this->input->post('ip_address');

            $this->form_validation->set_rules('reporting_manager', 'Select Reporting Manager', 'required');

            $this->form_validation->set_rules('tbl_roles_id', 'Role', 'required');

            $this->form_validation->set_rules('user_name', 'User Name', 'required|min_length[5]|alpha_number_dot|is_unique[user_profile.user_name]');

            $this->form_validation->set_rules('designation', 'designation', 'required');

            $this->form_validation->set_rules('firstname', 'First Name', 'required|alpha_numeric_spaces');

            $this->form_validation->set_rules('lastname', 'Last Name', 'required|alpha_numeric_spaces');

            $this->form_validation->set_rules('email', 'Email ID', 'required');

            $this->form_validation->set_rules('password', 'Password', 'required|min_length[7]');

            $this->form_validation->set_rules('department', 'Department', 'required');

            $this->form_validation->set_rules('crmpassword', 'Comfirm Password', 'required|min_length[7]|matches[password]');

            $this->form_validation->set_rules('mobile_phone', 'Mobile Phone', 'numeric|min_length[10]|max_length[15]');

            $this->form_validation->set_rules('office_phone', 'Office Phone', 'numeric|min_length[6]|max_length[12]');

            $this->form_validation->set_rules('pincode', 'Pin Code', 'numeric|min_length[6]|max_length[6]');

            $this->form_validation->set_rules('city', 'City', 'alpha_numeric_spaces');

            $this->form_validation->set_message('min_length', 'Password content min 8 charecter long');

            $this->form_validation->set_message('matches', 'Comfirm password same as Password');

            $this->form_validation->set_message('alpha_number_dot', 'Sorry, only letters (a-z), numbers (0-9), and periods (.) are allowed.');

            $this->form_validation->set_message('is_unique', "$user_name user name taken, please try another.");

            $this->form_validation->set_message('ip_address', 'ip_address', 'required|valid_ip[ipv4]');

            $this->form_validation->set_message('ip_address', 'ip_address', 'required|valid_ip[ipv6]');

         /*   if (!$this->input->valid_ip($ip_address)) {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = 'IP address Not valid';
            } else {*/

                if ($this->form_validation->run() == false) {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = validation_errors('', '');
                } else {
                    $frm_details = $this->input->post();

                    $enc_pass = create_password($frm_details['crmpassword']);
                    $field_array = array("user_name" => $frm_details["user_name"],
                        "email" => $frm_details["email"] . $frm_details["email_constant"],
                        "firstname" => $frm_details["firstname"],
                        "lastname" => $frm_details["lastname"],
                        "profile_pic" => 'default.png',
                        "password" => '',
                        "tbl_roles_id" => $frm_details["tbl_roles_id"],
                        "reporting_manager" => $frm_details["reporting_manager"],
                        "title" => $frm_details["title"],
                        "designation" => $frm_details["designation"],
                        "department" => $frm_details["department"],
                        "office_phone" => $frm_details["office_phone"],
                        "mobile_phone" => $frm_details["mobile_phone"],
                        "address" => $frm_details["address"],
                        "city" => $frm_details["city"],
                        "pincode" => $frm_details["pincode"],
                        "state" => $frm_details["state"],
                        'joining_date' => convert_display_to_db_date($frm_details['joining_date']),
                        'relieving_date' => convert_display_to_db_date($frm_details['relieving_date']),
                        'created_on' => date(DB_DATE_FORMAT),
                        'created_by' => $this->user_info['id'],
                        'modified_on' => date(DB_DATE_FORMAT),
                        'modified_by' => $this->user_info['id'],
                        'ip_address' => $frm_details["ip_address"],
                        'bill_date_permission' => 'no',
                    );

                    $field_array = array_map('strtolower', $field_array);

                    $field_array['password'] = $enc_pass;

                    $file_upload_path = SITE_BASE_PATH . PROFILE_PIC_PATH;

                    if (!folder_exist($file_upload_path)) {
                        mkdir($file_upload_path, 0777);
                    }

                    if (!empty($_FILES['user_image']['name'])) {
                        $file_name = str_replace(' ', '_', $_FILES['user_image']['name']);

                        $new_file_name = time() . "_" . $file_name;

                        $_FILES['logo']['name'] = $new_file_name;

                        $_FILES['logo']['tmp_name'] = $_FILES['user_image']['tmp_name'];

                        $_FILES['logo']['error'] = $_FILES['user_image']['error'];

                        $_FILES['logo']['size'] = $_FILES['user_image']['size'];

                        $config['upload_path'] = $file_upload_path;

                        $config['file_ext_tolower'] = true;

                        $config['max_size'] = BULK_UPLOAD_MAX_SIZE_MB * 1000;

                        $config['allowed_types'] = 'jpeg|jpg|png';

                        $config['remove_spaces'] = true;

                        $config['overwrite'] = false;

                        $config['file_name'] = $new_file_name;

                        $this->load->library('upload', $config);

                        $this->upload->initialize($config);

                        if ($this->upload->do_upload('logo')) {
                            $field_array['profile_pic'] = $new_file_name;

                            $uploaded_file_name = $this->upload->data('file_name');

                            $config = array();

                            $config['image_library'] = 'gd2';

                            $config['source_image'] = $file_upload_path . '/' . $uploaded_file_name;

                            $config['maintain_ratio'] = false;

                            $config['width'] = 160;

                            $config['height'] = 180;

                            $this->load->library('image_lib', $config);

                            $this->image_lib->resize();
                        } else {
                            $json_array['upload_error'] = $this->upload->display_errors('', '');
                        }
                    }

                    if ($this->users_model->save($field_array)) {
                        $json_array['status'] = SUCCESS_CODE;

                        $json_array['message'] = 'User Created Successfully';

                        $json_array['redirect'] = ADMIN_SITE_URL . 'users';
                    } else {
                        $json_array['status'] = ERROR_CODE;

                        $json_array['message'] = 'Something went wrong,please try again';
                    }
                }
            //}
            echo_json($json_array);
        } else {
            permission_denied();
        }
    }

    public function check_username()
    {
        $result = $this->users_model->select(true, array('id'), array('user_name' => $this->input->post('user_name')));

        echo (!empty($result) ? 'false' : 'true');
    }

    public function edit($user_id = null)
    {
        $is_exits = $this->users_model->select(true, array('*'), array('user_profile.id' => decrypt($user_id)));
        if ($user_id && !empty($is_exits)) {
            $data['header_title'] = "User Details";

            $data['title'] = $this->title;

            $data['states'] = $this->get_states();

            $data['clientmgr'] = $this->users_list();

            $data['user_details'] = $is_exits;

            $this->load->view('admin/header', $data);

            $this->load->view('admin/users_edit');

            $this->load->view('admin/footer');
        } else {
            show_404();
        }
    }

    public function update_users()
    {

        if ($this->input->is_ajax_request() && $this->permission['access_admin_list_edit'] == true) {
            $this->form_validation->set_rules('hidden_uid', 'User ID', 'required');

            $this->form_validation->set_rules('tbl_roles_id', 'Role', 'required');

            $this->form_validation->set_rules('reporting_manager', 'Reporting Manager', 'required');

            $this->form_validation->set_rules('designation', 'designation', 'required');

            $this->form_validation->set_rules('firstname', 'First Name', 'required|alpha_numeric_spaces');

            $this->form_validation->set_rules('firstname', 'Last Name', 'required|alpha_numeric_spaces');

            $this->form_validation->set_rules('password', 'Password', 'min_length[7]');

            $this->form_validation->set_rules('email', 'Email ID', 'required');

            $this->form_validation->set_rules('crmpassword', 'Comfirm Password', 'min_length[7]|matches[password]');

            $this->form_validation->set_rules('mobile_phone', 'Mobile Phone', 'numeric|min_length[10]|max_length[15]');

            $this->form_validation->set_rules('office_phone', 'Office Phone', 'numeric|min_length[6]|max_length[12]');

            $this->form_validation->set_rules('pincode', 'Pin Code', 'numeric|min_length[6]|max_length[6]');

            $this->form_validation->set_rules('city', 'City', 'alpha_numeric_spaces');

            $this->form_validation->set_message('min_length', 'Password content min 8 charecter long');

            $this->form_validation->set_message('matches', 'Comfirm password same as Password');

            $this->form_validation->set_message('ip_address', 'ip_address', 'valid_ip');

            $this->form_validation->set_message('ip_address', 'ip_address', 'valid_ip');

            $ip_address = $this->input->post('ip_address');

            /*if (!$this->input->valid_ip($ip_address)) {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = 'IP address Not valid';
            } else {*/

                if ($this->form_validation->run() == false) {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = validation_errors('', '');
                } else {
                    $frm_details = $this->input->post();

                    $field_array = array("email" => $frm_details["email"] . $frm_details["email_constant"],
                        "firstname" => $frm_details["firstname"],
                        "lastname" => $frm_details["lastname"],
                        "reporting_manager" => $frm_details["reporting_manager"],
                        "tbl_roles_id" => $frm_details["tbl_roles_id"],
                        "title" => $frm_details["title"],
                        "designation" => $frm_details["designation"],
                        "department" => $frm_details["department"],
                        "office_phone" => $frm_details["office_phone"],
                        "mobile_phone" => $frm_details["mobile_phone"],
                        "address" => $frm_details["address"],
                        "city" => $frm_details["city"],
                        "pincode" => $frm_details["pincode"],
                        "state" => $frm_details["state"],
                        "status" => $frm_details["status"],
                        'joining_date' => convert_display_to_db_date($frm_details['joining_date']),
                        'relieving_date' => convert_display_to_db_date($frm_details['relieving_date']),
                        'modified_on' => date(DB_DATE_FORMAT),
                        'modified_by' => $this->user_info['id'],
                        'ip_address' => $frm_details["ip_address"],
                        'import_permission' => $frm_details["import_check"],
                        'bill_date_permission' => $frm_details["bill_date_permission"],
                    );

                    $field_array = array_map('strtolower', $field_array);

                    ($frm_details["password"] != "") ? $field_array['password'] = create_password($frm_details['crmpassword']) : '';
                    

                    $file_upload_path = SITE_BASE_PATH . PROFILE_PIC_PATH;
                    if (!empty($_FILES['user_image']['name'])) {
                        $file_name = str_replace(' ', '_', $_FILES['user_image']['name']);

                        $new_file_name = time() . "_" . $file_name;

                        $_FILES['logo']['name'] = $new_file_name;

                        $_FILES['logo']['tmp_name'] = $_FILES['user_image']['tmp_name'];

                        $_FILES['logo']['error'] = $_FILES['user_image']['error'];

                        $_FILES['logo']['size'] = $_FILES['user_image']['size'];

                        $config['upload_path'] = $file_upload_path;

                        $config['file_ext_tolower'] = true;

                        $config['max_size'] = BULK_UPLOAD_MAX_SIZE_MB * 1000;

                        $config['allowed_types'] = 'jpeg|jpg|png';

                        $config['remove_spaces'] = true;

                        $config['overwrite'] = false;

                        $config['file_name'] = $new_file_name;

                        $this->load->library('upload', $config);

                        $this->upload->initialize($config);

                        if ($this->upload->do_upload('logo')) {
                            $field_array['profile_pic'] = $new_file_name;

                            $uploaded_file_name = $this->upload->data('file_name');

                            $config = array();

                            $config['image_library'] = 'gd2';

                            $config['source_image'] = $file_upload_path . '/' . $uploaded_file_name;

                            $config['maintain_ratio'] = false;

                            $config['width'] = 160;

                            $config['height'] = 180;

                            $this->load->library('image_lib', $config);

                            $this->image_lib->resize();
                        } else {
                            $json_array['upload_error'] = $this->upload->display_errors('', '');
                        }
                    }

                    if ($this->users_model->save($field_array, array('id' => $frm_details['hidden_uid']))) {
                        $json_array['status'] = SUCCESS_CODE;

                        $json_array['message'] = 'Profile Updated Successfully';

                        $json_array['redirect'] = ADMIN_SITE_URL . 'users';
                    } else {
                        $json_array['status'] = ERROR_CODE;

                        $json_array['message'] = 'Something went wrong,please try again';
                    }
                }
           // }
            echo_json($json_array);
        } else {
            permission_denied();
        }
    }

    public function delete()
    {

        $user_id = $this->input->post('user_id');

        if ($this->input->is_ajax_request() && $this->permission['access_admin_list_delete'] == true) {

            $is_exits = $this->users_model->user_role_gorup_details(array('user_profile.id' => decrypt($user_id)));

            if ($user_id && !empty($is_exits)) {

                $field_array = array('status' => STATUS_DELETED,
                    'modified_on' => date(DB_DATE_FORMAT),
                    'modified_by' => $this->user_info['id'],
                );

                $where_array = array('id' => $is_exits['id']);

                if ($this->users_model->save($field_array, $where_array)) {
                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'Deleted Successfully';
                } else {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Something went wrong,please try again';
                }
            } else {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = 'Something went wrong,please try again';
            }
            echo_json($json_array);
        } else {
            permission_denied();
        }
    }
}
