<?php defined('BASEPATH') or exit('No direct script access allowed');
class Client_login extends MY_Client_Cotroller
{
    public function __construct()
    {
        parent::__construct();
        $this->perm_array = array('direct_access' => true);
        $this->load->helper('captcha');
        $this->load->library('session');
    }

    public function index()
    {
        if ($this->is_client_logged_in()) {

            $this->load->model('client/client_login_model');

            $data['clients'] = array();

            $data['header_title'] = "Dashboard";



            $this->load->view('client/header', $data);

            $this->load->view('client/index');

            $this->load->view('client/footer');
        } else {
            $this->login();
        }
    }

    public function login()
    {
        $json_array = array();

        if ($this->is_client_logged_in()) {
            redirect(CLIENT_SITE_URL . 'index');
        }

        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_error_delimiters('', '');

            $this->form_validation->set_rules('venuser_name', 'Email', 'required');

            $this->form_validation->set_rules('venpassword', 'Password', 'required');

            if ($this->form_validation->run() == false) {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');
            } else {
                $this->load->model('client/client_login_model');

                $login_details = $this->input->post();

                $result_array = $this->client_login_model->select(true, array('id', 'password'), array('email_id' => $login_details['venuser_name'], 'status' => STATUS_ACTIVE));

                if (!empty($result_array)) {

                    if (password_verify($login_details['venpassword'], $result_array['password']) === true) {

                        $session_data = $this->client_login_model->user_components_details(array('client_login.id' => $result_array['id']));

                        $this->session->set_userdata(
                            array('client' => array(
                                'id' => $session_data['client_login_id'],
                                'client_name' => $session_data['clientname'],
                                'client_id' => $session_data['client_id'],
                                'email_id' => $session_data['email_id'],
                                'first_name' => $session_data['first_name'],
                                'last_name' => $session_data['last_name'],
                                'profile_pic' => $session_data['profile_pic'],
                                'comp_logo' => $session_data['comp_logo'],
                                'role' => $session_data['role'],
                                'client_entity_access' => $session_data['client_entity_access'],
                                'client_logged_in' => true,
                                'ctlctkn' => '',
                            ))
                        );

                        $this->set_client_login_cookie(array('cltnsessid' => $session_data['client_login_id']));

                        $json_array['status'] = SUCCESS_CODE;

                        $json_array['message'] = "Successfully logged in";

                        $json_array['redirect'] = CLIENT_SITE_URL . 'index/';
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
            if(basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)) == "happilo")
            {   
                $this->load->model('client/client_login_model');

                $company_logo = $this->client_login_model->select_client('clients',true,array('comp_logo'),array('id'=>'59'));

                $cleit_logo_path = SITE_URL . CLIENT_LOGO .'/'.$company_logo['comp_logo'];
                define('URL_LOGO',$cleit_logo_path);
            }
            else{
                $cleit_logo_path = SITE_URL . CLIENT_LOGO ;
                define('URL_LOGO','');
            }

            $this->load->view('client/login');
        }
    }

    public function logout()
    {
        if($this->client_info['client_id'] == "59"){
               if ($this->is_client_logged_in()) {
                $this->logout_client_user();
            }

            $this->session->set_flashdata('message', array('message' => 'Successfully logged out', 'class' => 'alert alert-success'));

            redirect('client/happilo');  
        }
        else
        {
            if ($this->is_client_logged_in()) {
                $this->logout_client_user();
            }

            $this->session->set_flashdata('message', array('message' => 'Successfully logged out', 'class' => 'alert alert-success'));

            redirect('client/login');
        }
    }

    public function refresh_captcha()
    {
        $config = array(
            'word' => '',
            'word_length' => 8,
            'img_path' => 'captcha_images/',
            'img_url' => SITE_URL . 'captcha_images/',
            'font_path' => 'system/fonts/texb.ttf',
            'img_width' => '150',
            'img_height' => 50,
            'font_size' => 18,
            'expiration' => 3600,
            'img_id' => 'Imageid',
            'pool' => '0123456789',
            'colors' => array(
                'background' => array(255, 255, 255),
                'border' => array(153, 102, 102),
                'text' => array(204, 153, 153),
                'grid' => array(255, 182, 182, 255),

            ),

        );

        $captcha = create_captcha($config);
        // Unset previous captcha and set new captcha word
        $this->session->unset_userdata('captchaCode');
        $this->session->set_userdata(array('captcha' => $captcha['word']));

        // Display captcha image
        echo $captcha['image'];

    }

    public function forgot()
    {

        $config = array(
            'word' => '',
            'word_length' => 8,
            'img_path' => 'captcha_images/',
            'img_url' => SITE_URL . 'captcha_images/',
            'font_path' => 'system/fonts/texb.ttf',
            'img_width' => '150',
            'img_height' => 50,
            'font_size' => 18,
            'expiration' => 3600,
            'img_id' => 'Imageid',
            'pool' => '0123456789',
            'colors' => array(
                'background' => array(255, 255, 255),
                'border' => array(153, 102, 102),
                'text' => array(204, 153, 153),
                'grid' => array(255, 182, 182, 255),
            ),

        );

        $captcha = create_captcha($config);
        // Unset previous captcha and set new captcha word
        $this->session->unset_userdata('captchaCode');
        $this->session->set_userdata('captchaCode', $captcha['word']);

        // Pass captcha image to view
        $data['captchaImg'] = $captcha['image'];

        $this->load->view('client/forgot_password', $data);
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

                    $this->load->model('client/client_login_model');

                    $result_array = $this->client_login_model->select(true, array('id', 'first_name', 'email_id', 'mobile_no'), array('email_id' => $email, 'status' => STATUS_ACTIVE));

                    if (!empty($result_array)) {
                        $pass_reset_key = generate_random_str('numeric', 10);

                        $fields = array('pass_reset_key' => $pass_reset_key, 'pass_reset_expiry' => date(DB_DATE_FORMAT));

                        $where = array('id' => $result_array['id']);

                        $results = $this->client_login_model->save($fields, $where);

                        $this->load->library('email');

                        $encrypt_insert_id = $result_array['id'];

                        $email_tmpl_data['subject'] = "Forgot Password";

                        $email_tmpl_data['first_name'] = $result_array['first_name'];

                        $email_tmpl_data['mobile_no'] = $result_array['mobile_no'];

                        $email_tmpl_data['email'] = $result_array['email_id'];

                        $email_tmpl_data['pass_url'] = CLIENT_SITE_URL . "Client_login/change_password/$encrypt_insert_id/$pass_reset_key";

                        $this->email->client_forgot_password($email_tmpl_data);

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

        echo_json($json_array);
    }

    public function change_password($admin_id = null, $key = null)
    {
        if (isset($admin_id) && $admin_id != "" && isset($key) && $key != "") {
            $this->load->model('client/client_login_model');

            $where = array("id" => $admin_id, 'pass_reset_key' => $key);

            $results = $this->client_login_model->select(true, array('id'), $where);

            if (!empty($results)) {
                $this->session->set_userdata('session_pass_chang', $results['id']);

                $this->load->view('client/reset_password');
            } else {
                $this->session->set_flashdata('error_message', array('message' => 'Link is not valid Please contact Administrator.', 'heading' => 'Authentication Timeout.', 'admin_url' => true));

                redirect("client/client_login");
            }
        } else {
            redirect('client/client_login');
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

                $json_array['message'] = validation_errors('', '');
            } else {
                $this->load->model('client/client_login_model');

                $password = $this->input->post('password');

                $cnf_password = $this->input->post('cnf_password');

                $enc_pass = create_password($cnf_password);

                $where = array("id" => $this->session->userdata('session_pass_chang'));

                $feilds = array('password' => $enc_pass,
                    'pass_reset_key' => '',
                    'modified_on' => date(DB_DATE_FORMAT));

                $results = $this->client_login_model->save($feilds, $where);

                $this->session->unset_userdata('session_pass_chang');

                if ($results) {
                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'Password Update Successfully';

                } else {
                    $json_array['status'] = ERROR_CODE;
                    $json_array['message'] = 'Something went wrong,please try again';
                }
            }
        } else {
            permission_denied();
        }

        $this->logout_client_user();

        $json_array['redirect'] = CLIENT_SITE_URL . 'Client_login';

        echo_json($json_array);
    }
}

?>