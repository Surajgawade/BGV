<?php defined('BASEPATH') or exit('No direct script access allowed');

class Candidate_login extends MY_Candidate_Cotroller
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
        if ($this->is_candidate_logged_in()) {

            $data['ccandidate'] = array();

            $data['header_title'] = "Dashboard";

            $this->load->view('candidate_info/header', $data);

            $this->load->view('candidate_info/index');

            $this->load->view('candidate_info/footer');
        } else {

            $this->login();
        }
    }

    public function login()
    {
        $json_array = array();

        if ($this->is_candidate_logged_in()) {
            redirect(CANDIDATE_SITE_URL . 'index');
        }

        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_error_delimiters('', '');

            $this->form_validation->set_rules('venuser_name', 'Email', 'required');

            $this->form_validation->set_rules('venpassword', 'Password', 'required');

            if ($this->form_validation->run() == false) {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');
            } else {
                $this->load->model('candidate_info/candidate_login_model');

                $login_details = $this->input->post();

                $result_array = $this->candidate_login_model->select(true, array('id', 'email_password'), array('cands_email_id' => $login_details['venuser_name'], 'status' => STATUS_ACTIVE));

                if (!empty($result_array)) {

                    if (password_verify($login_details['venpassword'], $result_array['email_password']) === true) {
                        $session_data = $this->candidate_login_model->select(true, array(), array('client_candidates_info.id' => $result_array['id']));

                        $this->session->set_userdata(
                            array('candidate' => array(
                                'id' => $session_data['id'],
                                'cands_info_id' => $session_data['cands_info_id'],
                                'client_id' => $session_data['clientid'],
                                'entity' => $session_data['entity'],
                                'package' => $session_data['package'],
                                'email_id' => $session_data['cands_email_id'],
                                'CandidateName' => $session_data['CandidateName'],
                                'candidate_logged_in' => true,
                                'ctlctkn' => '',
                            ))
                        );

                        $this->set_candidate_login_cookie(array('cltnsessid' => $session_data['id']));

                        $json_array['status'] = SUCCESS_CODE;

                        $json_array['message'] = "Successfully logged in";

                        $json_array['redirect'] = CANDIDATE_SITE_URL . 'Dashboard/';
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
            $this->load->view('candidate_info/login');
        }
    }

    public function logout()
    {
        if ($this->is_candidate_logged_in()) {
            $this->logout_candidate_user();
        }

        $this->session->set_flashdata('message', array('message' => 'Successfully logged out', 'class' => 'alert alert-success'));

        redirect('candidate_info/login');
    }

    public function display_menu()
    {
        $json_array = array();

        $this->load->model('candidate_info/candidate_login_model');

        $result_array = $this->candidate_login_model->select_menu();

        if ($result_array) {

            $json_array['status'] = SUCCESS_CODE;

            $json_array['message'] = $result_array;

        } else {
            $json_array['status'] = ERROR_CODE;

            $json_array['message'] = "Something went wrong,please try again";
        }

        echo_json($json_array);
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

        $this->load->view('candidate_info/forgot_password', $data);
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

                    $this->load->model('candidate_info/candidate_login_model');

                    $result_array = $this->candidate_login_model->select(true, array('id', 'first_name', 'email_id', 'mobile_no'), array('email_id' => $email, 'status' => STATUS_ACTIVE));

                    if (!empty($result_array)) {
                        $pass_reset_key = generate_random_str('numeric', 10);

                        $fields = array('pass_reset_key' => $pass_reset_key, 'pass_reset_expiry' => date(DB_DATE_FORMAT));

                        $where = array('id' => $result_array['id']);

                        $results = $this->candidate_login_model->save($fields, $where);

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
            $this->load->model('candidate_info/candidate_login_model');

            $where = array("id" => $admin_id, 'pass_reset_key' => $key);

            $results = $this->candidate_login_model->select(true, array('id'), $where);

            if (!empty($results)) {
                $this->session->set_userdata('session_pass_chang', $results['id']);

                $this->load->view('candidate_info/reset_password');
            } else {
                $this->session->set_flashdata('error_message', array('message' => 'Link is not valid Please contact Administrator.', 'heading' => 'Authentication Timeout.', 'admin_url' => true));

                redirect("candidate_info/candidate_login");
            }
        } else {
            redirect('candidate_info/candidate_login');
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
                $this->load->model('candidate_info/candidate_login_model');

                $password = $this->input->post('password');

                $cnf_password = $this->input->post('cnf_password');

                $enc_pass = create_password($cnf_password);

                $where = array("id" => $this->session->userdata('session_pass_chang'));

                $feilds = array('password' => $enc_pass,
                    'pass_reset_key' => '',
                    'modified_on' => date(DB_DATE_FORMAT));

                $results = $this->candidate_login_model->save($feilds, $where);

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

        $this->logout_candidate_user();

        $json_array['redirect'] = CANDIDATE_SITE_URL . 'Candidate_login';

        echo_json($json_array);
    }

}
