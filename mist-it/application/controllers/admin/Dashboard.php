<?php defined('BASEPATH') or exit('No direct script access allowed');
class Dashboard extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->perm_array = array('page_id' => 26, 'direct_access' => true);
    }

    public function index()
    {
        if ($this->is_admin_logged_in()) {

            $data['header_title'] = "Dashboard";
          //  $data['component_lists'] = $this->components();

          //  $data['status'] = $this->common_model->distinct_report_status_case_status();

            $this->load->model('users_model');
    
            $data['clients'] = $this->users_model->get_all_client_name(array('status' => STATUS_ACTIVE));
    
            $this->load->view('admin/header', $data);
            $this->load->view('admin/index');
            $this->load->view('admin/footer');
        } else {
            redirect(ADMIN_SITE_URL . 'login');
        }
    }

    public function login()
    {
        $json_array = array();

        if ($this->is_admin_logged_in()) {
            redirect(ADMIN_SITE_URL . 'dashboard/');
        }

        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_error_delimiters('', '');

            $this->form_validation->set_rules('user_name', 'Username', 'required');

            $this->form_validation->set_rules('password', 'Password', 'required');

            if ($this->form_validation->run() == false) {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');
            } else {
                $this->load->model('users_model');
              
                $login_details = $this->input->post();
                log_message('error', 'Login Details');
                try {
                    $result_array = $this->users_model->select(true, array('id', 'password'), array('user_name' => $login_details['user_name'], 'status' => STATUS_ACTIVE));

                    if (!empty($result_array)) {
                        log_message('error', 'User details found');

                        $result_array_ip_address = $this->users_model->select(TRUE,array('id','password','ip_address','modified_on'),array('user_name' => $login_details['user_name'],'status' => STATUS_ACTIVE));
                        $result_array_ip_address_array = explode(',',$result_array_ip_address['ip_address']);
                    

                        //if(in_array($_SERVER['HTTP_X_FORWARDED_FOR'],$result_array_ip_address_array) ||  $result_array_ip_address['id'] == '1' || in_array('1.1.1.1',$result_array_ip_address_array))
                       // {    
                            if (password_verify($login_details['password'], $result_array['password']) === true) {

                                $date_format = date('Y-m-d', strtotime($result_array_ip_address['modified_on']));
 
                                $next_date_format = date('Y-m-d', strtotime($date_format. ' +30 days'));
                             
                                if($next_date_format > date('Y-m-d') ||  $result_array_ip_address['id'] == '1')
                                {

                                    log_message('error', 'User password matched');

                                    $permission_array = $this->users_model->user_role_gorup_details(array('user_profile.id' => $result_array['id']));

                                    $this->session->set_userdata(array(
                                        'admin' => array('id' => $permission_array['id'],
                                            'email' => $permission_array['email'],
                                            'tbl_roles_id' => $permission_array['tbl_roles_id'],
                                            'user_name' => $permission_array['user_name'],
                                            'profile_pic' => $permission_array['profile_pic'],
                                            'firstname' => $permission_array['firstname'],
                                            'lastname' => $permission_array['lastname'],
                                            'groups_id' => $permission_array['groups_id'],
                                            'department' => $permission_array['department'],
                                            'mobile_phone' => $permission_array['mobile_phone'],
                                            'office_phone' => $permission_array['office_phone'],
                                            'bill_date_permission' => $permission_array['bill_date_permission'],
                                            'import_permission' => $permission_array['import_permission'],
                                            'permission_array' => $permission_array,
                                            'admin_logged_in' => true,
                                            'menus' => '',
                                            'catkn' => '',
                                        ),
                                    ));

                                    $cookie = array(
                                        'name' => 'lastloggnedin',
                                        'value' => $permission_array['firstname'],
                                        'expire' => time() + 60 * 60 * 24 * 30,
                                        'path' => '/',
                                        'prefix' => '',
                                    );
                                    set_cookie($cookie);

                                    $update_login_or_not = $this->users_model->save(array('is_login_or_not' => 2), array('id' => $result_array['id']));
                            
                                    $this->set_admin_login_cookie(array('id' => $result_array['id']));
                                    $json_array['status'] = SUCCESS_CODE;
                                    $json_array['message'] = "Successfully logged in";

                                    if($this->session->userdata('controller_mothod')){

                                        $controller_mothod = $this->session->userdata('controller_mothod');
                                       
                                        $controller = $controller_mothod['contoller_name'];
                                        $method = $controller_mothod['method_name'];
                                        
                                        $json_array['redirect'] = ADMIN_SITE_URL . $controller .'/'.$method;

                                    }
                                    else{
                                        $json_array['redirect'] = ADMIN_SITE_URL . 'dashboard/';
                                    }
                                    
                                }
                                else{

                                    $json_array['status'] = SUCCESS_CODE;
                                    $json_array['message'] = "Please Reset Password";
                                    $json_array['redirect'] = ADMIN_SITE_URL . 'dashboard/change_password/'.$result_array['id'];
                                 
                                }         
                                    
                            } else {
                                    $json_array['status'] = ERROR_CODE;
                                    $json_array['message'] = "Invalid Credentials";
                            }
                       // }
                       // else{
                        //    $json_array['status'] = ERROR_CODE;
                      //      $json_array['message'] = "You have not access to login"; 
                      //  }   

                    } else {
                        $json_array['status'] = ERROR_CODE;
                        $json_array['message'] = "Invalid Credentials";
                    }
                } catch (Exception $e) {
                    log_message('error', 'Srinivas');
                    log_message('error', 'Dashboard::login');
                    log_message('error', $e->getMessage());
                }
            }
            
            $this->echo_json($json_array);

        } else {
            $lastloggedin = ($this->input->cookie('CKI2Y0SA8hv7lastloggnedin', true)) ? ' ' . ucwords($this->input->cookie('CKI2Y0SA8hv7lastloggnedin', true)) . '!' : '!';
            $data['lastloggnedin'] = $lastloggedin;
            $this->load->view('admin/login', $data);
        }
    }

    public function logout()
    {
        if ($this->is_admin_logged_in()) {
            if ($this->is_admin_id()) {
                $this->logout_admin();
            }
        }
        $this->session->set_flashdata('message', array('message' => 'Successfully logged out', 'class' => 'alert alert-success'));

        redirect('admin/login');
    }

    public function forgot_password()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('user_name', 'Email', 'required|valid_email');

            $this->form_validation->set_rules('code', 'code', 'required');

            if ($this->input->post('code') == $this->session->userdata('captcha')) {

                if ($this->form_validation->run() == false) {

                    $json_array['status'] = ERROR_CODE;
                    $json_array['message'] = validation_errors('', '');

                } else {

                    $email = $this->input->post('user_name');
                    $this->load->model('users_model');
                    $result_array = $this->users_model->select(true, array('id', 'user_name', 'email', 'firstname'), array('email' => $email, 'status' => STATUS_ACTIVE));

                    if (!empty($result_array)) {
                        $pass_reset_key = generate_random_str('numeric', 10);

                        $fields = array('pass_reset_key' => $pass_reset_key, 'pass_reset_expiry' => date(DB_DATE_FORMAT));

                        $where = array('id' => $result_array['id']);

                        $results = $this->users_model->save($fields, $where);

                        $this->load->library('email');

                        $encrypt_insert_id = $result_array['id'];

                        $email_tmpl_data['user_name'] = $result_array['user_name'];

                        $email_tmpl_data['first_name'] = $result_array['firstname'];

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
                $json_array['message'] = "'validate_captcha', 'Wrong captcha code'";
            }
        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['message'] = "please try again!";
        }

        $this->echo_json($json_array);
    }

   /* public function change_password($admin_id = null, $key = null)
    {
        if (isset($admin_id) && $admin_id != "" && isset($key) && $key != "") {
            $this->load->model('users_model');

            $where = array("id" => $admin_id, 'pass_reset_key' => $key);

            $results = $this->users_model->select(true, array('id'), $where);

            if (!empty($results)) {
                $this->session->set_userdata('session_pass_chang', $results['id']);

                $this->load->view('admin/reset_password');
            } else {
                $this->session->set_flashdata('error_message', array('message' => 'Link is not valid Please contact Administrator.', 'heading' => 'Authentication Timeout.', 'admin_url' => true));

                redirect("vendor/login");
            }
        } else {
            redirect('vendor/login');
        }
    }*/

    public function change_password($id)
    {
        $data['id'] = $id;

        echo $this->load->view('admin/reset_password',$data,true); 
    }


    public function set_password()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {
            
            $this->form_validation->set_rules('old_password', 'Old Password', 'required');
            $this->form_validation->set_rules('password', 'Password', 'required');
            $this->form_validation->set_rules('cnf_password', 'Password', 'required|matches[password]|callback_valid_password');
            if ($this->form_validation->run() == false) {
                $json_array['status'] = ERROR_CODE;
                $json_array['message'] = validation_errors('', '');
            } else {
               
                $this->load->model('users_model');
                $id = $this->input->post('user_id');
                $old_password = $this->input->post('old_password');
                $password = $this->input->post('password');
                $cnf_password = $this->input->post('cnf_password');

                $result_array = $this->users_model->select(TRUE,array('id','password','password1','password2'),array('id' =>  $id,'status' => STATUS_ACTIVE));

                if (password_verify($old_password, $result_array['password']) === true) {

                    if ((password_verify($password, $result_array['password']) === true) || (password_verify($password, $result_array['password1']) === true) || (password_verify($password, $result_array['password2']) === true)) 
                    {
                        $json_array['status'] = ERROR_CODE;

                        $json_array['message'] = "Don't need password as same as last three password";
                    }
                    else
                    {
                        $enc_pass = create_password($cnf_password);
                        $where = array("id" => $id);
                        $feilds = array('password' => $enc_pass,'password1' => $result_array['password'],'password2' => $result_array['password1'], 'modified_on' => date(DB_DATE_FORMAT));
                        $results = $this->users_model->save($feilds, $where);  

                        if ($results) {
                            $json_array['status'] = SUCCESS_CODE;

                            $json_array['message'] = 'Password Update Successfully';

                            $json_array['redirect'] = ADMIN_SITE_URL.'login';
                        } else {
                            $json_array['status'] = ERROR_CODE;

                            $json_array['message'] = 'Something went wrong,please try again';
                        }
                    }
                }else {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Old Password Does Not Match';
                }
               
            }
        }
        else 
        {
            $json_array['status'] = ERROR_CODE;

            $json_array['message'] = 'Something went wrong,please try again';
        }
       
        $this->echo_json($json_array);
    }

    private function echo_json($json_array)
    {
        header('Content-Type: application/json');
        echo_json($json_array);

        exit;
    }

    public function valid_password($password = '')
    {
      
        $password = trim($password);

        $regex_lowercase = '/[a-z]/';
        $regex_uppercase = '/[A-Z]/';
        $regex_number = '/[0-9]/';
        $regex_special = '/[!@#$%^&*()\-_=+{};:,<.>ยง~]/';

        if (empty($password))
        {
            $this->form_validation->set_message('valid_password', 'The {field} field is required.');

            return FALSE;
        }

        if (preg_match_all($regex_lowercase, $password) < 1)
        {
            $this->form_validation->set_message('valid_password', 'The {field} field must be at least one lowercase letter.');

            return FALSE;
        }

        if (preg_match_all($regex_uppercase, $password) < 1)
        {
            $this->form_validation->set_message('valid_password', 'The {field} field must be at least one uppercase letter.');

            return FALSE;
        }

        if (preg_match_all($regex_number, $password) < 1)
        {
            $this->form_validation->set_message('valid_password', 'The {field} field must have at least one number.');

            return FALSE;
        }

        if (preg_match_all($regex_special, $password) < 1)
        {
            $this->form_validation->set_message('valid_password', 'The {field} field must have at least one special character.' . ' ' . htmlentities('!@#$%^&*()\-_=+{};:,<.>ยง~'));

            return FALSE;
        }

        if (strlen($password) < 6)
        {
            $this->form_validation->set_message('valid_password', 'The {field} field must be at least 6 characters in length.');

            return FALSE;
        }

        if (strlen($password) > 26)
        {
            $this->form_validation->set_message('valid_password', 'The {field} field cannot exceed 26 characters in length.');

            return FALSE;
        }

        return TRUE;
    }
    

    public function client_details()
    {
        $json_array = array();
        
        $result = '';

        if($this->input->is_ajax_request())
        {

            $this->load->model('users_model');

            $params = $_REQUEST;

            $result = $this->users_model->get_all_client_details($params);

            if($result != '')
            {
        
                $json_array['status'] = SUCCESS_CODE;
                        
                $json_array['message'] = $result;
            }
            else
            {
                $json_array['status'] = ERROR_CODE;

               $json_array['message'] = 'Something went wrong';
            }
        }
        else
        {
            $json_array['status'] = ERROR_CODE;

            $json_array['message'] = 'Something went wrong';
        }
        echo_json($json_array);

    }   

   
}
?>
