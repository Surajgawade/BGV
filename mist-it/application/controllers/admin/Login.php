<?php defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('captcha');
        $this->load->library('session');
    }

    public function index()
    {
        if ($this->is_admin_logged_in()) {
            $data['header_title'] = "BGV Dashboard Login";

            $this->load->view('admin/header', $data);

            $this->load->view('admin/index');

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

                $result_array = $this->users_model->user_role_details(array('user_name' => $login_details['user_name']));

                if (!empty($result_array)) {
                    if (password_verify($login_details['password'], $result_array['password']) === true) {
                        $this->session->set_userdata(array(
                            'admin' => array('id' => $result_array['id'],
                                'email' => $result_array['email'],
                                'user_name' => $result_array['user_name'],
                                'profile_pic' => $result_array['profile_pic'],
                                'firstname' => $result_array['firstname'],
                                'lastname' => $result_array['lastname'],
                                'tbl_groups_id' => $result_array['tbl_groups_id'],
                                'role_id' => $result_array['tbl_role_id'],
                                'bill_date_permission' => $result_array['bill_date_permission'],
                                'admin_logged_in' => true),
                        ));

                        $this->set_admin_login_cookie(array('id' => $result_array['id']));

                        $json_array['status'] = SUCCESS_CODE;

                        $json_array['message'] = "Successfully logged in";

                        $json_array['redirect'] = ADMIN_SITE_URL . 'dashboard/';
                    } else {
                        $json_array['status'] = ERROR_CODE;

                        $json_array['message'] = "Invalid Credentials";
                    }
                } else {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = "Invalid Credentials";
                }
            }

            $this->echo_json($json_array);
        } else {
            $this->load->view('admin/login');
        }
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

        $this->load->view('admin/forgot_password', $data);
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

    private function echo_json($json_array)
    {
        echo_json($json_array);

        exit;
    }
}
