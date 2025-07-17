<?php defined('BASEPATH') or exit('No direct script access allowed');

class Courtver extends MY_Client_Cotroller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->is_client_logged_in()) {
            redirect('client/login');
            exit();
        }

        $this->perm_array = array('direct_access' => true);
        $this->load->model(array('court_verificatiion_model'));
    }

    public function index()
    {
        if ($this->is_vendor_logged_in()) {
            $data['header_title'] = "Court Cases View";

            $data['lists'] = $this->vendor_common_model->court_case_list(array('courtver_vendor_log.vendor_id' => $this->vendor_info['vendors_id'], 'vendor_master_log.status =' => 1));

            $this->load->view('vendor/header', $data);

            $this->load->view('vendor/court_list');

            $this->load->view('vendor/footer');
        } else {
            $this->login();
        }
    }

    public function add($candsid = false)
    {
        if ($this->input->is_ajax_request()) {

            $data['get_cands_details'] = $this->candidate_entity_pack_details($candsid);
            $data['states'] = $this->get_states();

            $data['mode_of_verification'] = $this->court_verificatiion_model->get_mode_of_verification(array('entity' => $data['get_cands_details']['entity_id'], 'package' => $data['get_cands_details']['package_id'], 'tbl_clients_id' => $data['get_cands_details']['clientid']));

            echo $this->load->view('client/courtver_add', $data, true);
        } else {
            echo "<h3>Something went wrong, please try again</h3>";
        }
    }

    protected function court_com_ref($insert_id)
    {

        $name = COMPONENT_REF_NO['COURT'];

        $courtnumber = $name . $insert_id;

        $field_array = array('court_com_ref' => $courtnumber);

        $update_auto_increament_id = $this->court_verificatiion_model->update_auto_increament_value($field_array, array('id' => $insert_id));

        return $courtnumber;
    }

    public function save_court()
    {
        if ($this->input->is_ajax_request()) {

            $frm_details = $this->input->post();

           
            $this->form_validation->set_rules('clientid', 'Client', 'required');

            $this->form_validation->set_rules('candsid', 'Candidate', 'required');

            $this->form_validation->set_rules('street_address', 'Address', 'required');

            $this->form_validation->set_rules('city', 'City', 'required');

            $this->form_validation->set_rules('pincode', 'PIN code', 'required');
             

            $this->form_validation->set_rules('state', 'State', 'required');


            if ($this->form_validation->run() == false) {

                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');

            } else {

        
                $tat_day = $this->get_client_entity_package_tat_day(array('tbl_clients_id' => $frm_details['clientid'], 'entity' => $frm_details['entity_id'], 'package' => $frm_details['package_id']));

                $get_holiday1 = $this->get_holiday();

                $get_holiday = array_map('current', $get_holiday1);

                $closed_date = getWorkingDays(convert_display_to_db_date($frm_details['iniated_date']), $get_holiday, $tat_day[0]['tat_courtver']);

                $has_case_id = $this->court_verificatiion_model->get_reporting_manager_id($frm_details['clientid']);

                $field_array = array('clientid' => $frm_details['clientid'],
                        'candsid' => $frm_details['candsid'],
                        'court_com_ref' => '',
                        'address_type' => $frm_details['address_type'],
                        'street_address' => $frm_details['street_address'],
                        'city' => $frm_details['city'],
                        'pincode' => $frm_details['pincode'],
                        'state' => $frm_details['state'],
                        'mode_of_veri' => $frm_details['mod_of_veri'],
                        'created_on' => date(DB_DATE_FORMAT),
                        'modified_on' => date(DB_DATE_FORMAT),
                        'is_bulk_uploaded' => 0,
                        'iniated_date' => convert_display_to_db_date($frm_details['iniated_date']),
                        'has_case_id' => $has_case_id[0]['clientmgr'],
                        "due_date" => $closed_date,
                        "tat_status" => 'IN TAT',
                        'fill_by' => 1,
                    );
   
                $result = $this->court_verificatiion_model->save(array_map('strtolower', $field_array));

                $court_com_ref = $this->court_com_ref($result);

                $error_msgs = array();
                $file_upload_path = SITE_BASE_PATH . COURT_VERIFICATION . $frm_details['clientid'];
                if (!folder_exist($file_upload_path)) {
                    mkdir($file_upload_path, 0777,true);
                } else if (!is_writable($file_upload_path)) {
                    array_push($error_msgs, 'Problem while uploading');
                }


                $config_array = array('file_upload_path' => $file_upload_path, 'file_permission' => 'jpeg|jpg|png|pdf|docx|xls|xlsx', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 1000, 'file_id' => $result, 'component_name' => 'courtver_id');
                if (empty($error_msgs)) {
                    if ($_FILES['attchments']['name'][0] != '') {
                        $config_array['files_count'] = count($_FILES['attchments']['name']);
                        $config_array['file_data'] = $_FILES['attchments'];
                        $config_array['type'] = 0;
                        $retunr_de = $this->file_upload_multiple($config_array);
                        if (!empty($retunr_de)) {
                            $this->common_model->common_insert_batch('courtver_files', $retunr_de['success']);
                        }
                    }

                }

                if ($result) {


                        $field = array('candsid' => $frm_details['candsid'],
                            'ClientRefNumber' => '',
                            'comp_table_id' => $result,
                            'activity_mode' => '',
                            'activity_status' => 'New check',
                            'activity_type' => 'New check',
                            'action' => 'New check',
                            'next_follow_up_date' => null,
                            'remarks' => 'New Check Added by  Client ' . $frm_details['clientname'],
                            'created_on' => date(DB_DATE_FORMAT),
                            'is_auto_filled' => 0,

                        );
                    //$result = $this->court_verificatiion_model->save_trigger($field);  

                    if ($result) {
                        auto_update_tat_status($frm_details['candsid']);

                        auto_update_overall_status($frm_details['candsid']);

                        $json_array['status'] = SUCCESS_CODE;

                        $json_array['message'] = 'Court Record Successfully Inserted';
                        
                    } else {
                        $json_array['status'] = ERROR_CODE;

                        $json_array['message'] = 'Something went wrong,please try again';
                    }

                    //$json_array['active_tab'] = 'addrver';
                } else {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Something went wrong,please try again';
                }
               
            }
            echo_json($json_array);
        }
    }

    public function login()
    {
        $json_array = array();

        if ($this->is_vendor_logged_in()) {
            redirect(VENDOR_SITE_URL . 'index');
        }

        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_error_delimiters('', '');

            $this->form_validation->set_rules('venuser_name', 'Email', 'required');

            $this->form_validation->set_rules('venpassword', 'Password', 'required');

            if ($this->form_validation->run() == false) {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');
            } else {
                $this->load->model('vendor/vendor_login_model');

                $login_details = $this->input->post();

                $result_array = $this->vendor_login_model->select(true, array('id', 'password'), array('email_id' => $login_details['venuser_name'], 'status' => STATUS_ACTIVE));

                if (!empty($result_array)) {
                    if (password_verify($login_details['venpassword'], $result_array['password']) === true) {
                        $session_data = $this->vendor_login_model->user_components_details(array('vendors_login.id' => $result_array['id']));

                        $this->session->set_userdata(
                            array('vendor' => array(
                                'id' => $session_data['vendors_login_id'],
                                'vendors_id' => $session_data['vendors_id'],
                                'email_id' => $session_data['email_id'],
                                'first_name' => $session_data['first_name'],
                                'last_name' => $session_data['last_name'],
                                'profile_pic' => $session_data['profile_pic'],
                                'vendor_name' => $session_data['vendor_name'],
                                'vendor_managers' => $session_data['vendor_managers'],
                                'vendors_components' => $session_data['vendors_components'],
                                'vendor_logged_in' => true,
                                'vdctkn' => '',
                            ))
                        );

                        $this->set_vendor_login_cookie(array('vendsessid' => $session_data['vendors_login_id']));

                        $json_array['status'] = SUCCESS_CODE;

                        $json_array['message'] = "Successfully logged in";

                        $json_array['redirect'] = VENDOR_SITE_URL . 'index/';
                    } else {
                        $json_array['status'] = ERROR_CODE;

                        $json_array['message'] = "Invalid Credentials";
                    }
                } else {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = "Invalid Credentials";
                }
            }

            echo_json($json_array);
        } else {
            $this->load->view('vendor/login');
        }
    }

    public function logout()
    {
        if ($this->is_vendor_logged_in()) {
            $this->logout_vendor_user();
        }

        $this->session->set_flashdata('message', array('message' => 'Successfully logged out', 'class' => 'alert alert-success'));

        redirect('vendor/login');
    }

    public function forgot_password()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('email_id_frg', 'Email', 'required|valid_email');

            if ($this->form_validation->run() == false) {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = "Email Id required";
            } else {
                $email = $this->input->post('email_id_frg');

                $this->load->model('membership_users_model');

                $result_array = $this->membership_users_model->select(true, array('id,', 'memberID', 'email', 'custom1'), array('email' => $email, 'status' => STATUS_ACTIVE));

                if (!empty($result_array)) {
                    $pass_reset_key = generate_random_str('numeric', 10);

                    $fields = array('pass_reset_key' => $pass_reset_key, 'pass_reset_expiry' => date(DB_DATE_FORMAT));

                    $where = array('id' => $result_array['id']);

                    $results = $this->membership_users_model->save($fields, $where);

                    $this->load->library('email');

                    $encrypt_insert_id = $result_array['id'];

                    $email_tmpl_data['subject'] = "Forgot Password";

                    $email_tmpl_data['memberID'] = $result_array['memberID'];

                    $email_tmpl_data['first_name'] = $result_array['custom1'];

                    $email_tmpl_data['email'] = $result_array['email'];

                    $email_tmpl_data['pass_url'] = ADMIN_SITE_URL . "dashboard/change_password/$encrypt_insert_id/$pass_reset_key";

                    $this->email->admin_forgot_password($email_tmpl_data);

                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = "Forgot password link send to your register email ID";
                } else {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = "Email ID is not exits in our database";
                }
            }
        } else {
            $json_array['status'] = ERROR_CODE;

            $json_array['message'] = "please try again!";
        }

        $this->echo_json($json_array);
    }

    public function change_password($admin_id = null, $key = null)
    {
        if (isset($admin_id) && $admin_id != "" && isset($key) && $key != "") {
            $this->load->model('membership_users_model');

            $where = array("id" => $admin_id, 'pass_reset_key' => $key);

            $results = $this->membership_users_model->select(true, array('id'), $where);

            if (!empty($results)) {
                $this->session->set_userdata('session_pass_chang', $results['id']);

                $this->load->view('admin/reset_password');
            } else {
                $this->session->set_flashdata('error_message', array('message' => 'Link is not valid Please contact Administrator.', 'heading' => 'Authentication Timeout.', 'admin_url' => true));

                redirect("admin/login");
            }
        } else {
            redirect('admin/login');
        }
    }

    public function set_password()
    {
        $json_array = array();

        if ($this->input->is_ajax_request() && $this->session->userdata('session_pass_chang')) {
            $this->form_validation->set_rules('password', 'Email', 'required');

            $this->form_validation->set_rules('cnf_password', 'Email', 'required|matches[password]');

            if ($this->form_validation->run() == false) {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = "All fields required";
            } else {
                $this->load->model('membership_users_model');

                $password = $this->input->post('password');

                $cnf_password = $this->input->post('cnf_password');

                $where = array("id" => $this->session->userdata('session_pass_chang'));

                $feilds = array('passMD5' => create_password($cnf_password),
                    'pass_reset_key' => '',
                    'modified_on' => date(DB_DATE_FORMAT));

                $results = $this->membership_users_model->save($feilds, $where);

                $this->session->unset_userdata('session_pass_chang');

                if ($results) {
                    $json_array['status'] = SUCCESS_CODE;

                    $this->session->set_flashdata('message', array('message' => 'Password changed Successfully', 'class' => 'alert alert-success'));
                } else {
                    $json_array['status'] = ERROR_CODE;
                    $this->session->set_flashdata('message', array('message' => 'Something went wrong, please try again!', 'class' => 'alert alert-danger'));
                }
            }
        } else {
            $this->session->set_flashdata('message', array('message' => 'Permission Denied, please try again!', 'class' => 'alert alert-danger'));
        }

        $this->logout_admin();

        $json_array['redirect'] = ADMIN_SITE_URL . 'login';

        $this->echo_json($json_array);
    }
}
