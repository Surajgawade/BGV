<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Vendor_login extends MY_Vendor_Cotroller
{    
    function __construct()
    {
        parent::__construct();
        $this->perm_array = array('direct_access' => true);
        $this->load->helper('captcha');
       $this->load->library('session');
    }

    public function index()
    { 
        
  
        if($this->is_vendor_logged_in())
        {
        
            $data['header_title'] = "Dashboard";

            $data['clients'] = array();

            $this->load->view('vendor/header',$data);
            
            $this->load->view('vendor/index');

            $this->load->view('vendor/footer');
        }
        else
        {
           
            $this->login();
        }
    }

    
    public function login()
    {
      
        $json_array = array();

        if ($this->is_vendor_logged_in())
        {  
          
            redirect(VENDOR_SITE_URL.'index');
        }

        if($this->input->is_ajax_request())
        {
           
            $this->form_validation->set_error_delimiters('', '');

            $this->form_validation->set_rules('venuser_name', 'Email', 'required');

            $this->form_validation->set_rules('venpassword', 'Password', 'required');

            if ($this->form_validation->run() == FALSE)
            {
                $json_array['status'] =  ERROR_CODE;

                $json_array['message'] = validation_errors('','');
            }
            else
            {
                $this->load->model('vendor/vendor_login_model');

                $login_details = $this->input->post();

                $result_array = $this->vendor_login_model->select(TRUE,array('id','password'),array('email_id' => $login_details['venuser_name'],'status' => STATUS_ACTIVE));
             
                if(!empty($result_array))
                {
                   
                    if(password_verify($login_details['venpassword'], $result_array['password']) === TRUE)
                    {
                       
                        $session_data = $this->vendor_login_model->user_components_details(array('vendors_login.id' => $result_array['id']));
                      
                        $this->session->set_userdata(
                                    array('vendor'=>array(
                                            'id' => $session_data['vendors_login_id'],
                                            'vendors_id' => $session_data['vendors_id'],
                                            'email_id' => $session_data['email_id'],
                                            'first_name' => $session_data['first_name'],
                                            'last_name' => $session_data['last_name'],
                                            'profile_pic' => $session_data['profile_pic'],
                                            'vendor_name' => $session_data['vendor_name'],
                                            'vendor_managers' => $session_data['vendor_managers'],
                                            'vendors_components' => $session_data['vendors_components'],
                                            'vendor_logged_in' => TRUE,
                                            'vdctkn'        => ''
                                        ))
                                );
                        
                        $this->set_vendor_login_cookie(array('vendsessid'=>$session_data['vendors_login_id']));


                        $json_array['status'] =    SUCCESS_CODE;

                        $json_array['message'] =    "Successfully logged in";

                        $json_array['redirect'] =   VENDOR_SITE_URL.'vendor_login/';
                        
                    }
                    else
                    {
                        $json_array['status'] =    ERROR_CODE;

                        $json_array['message'] =    "Invalid Credential";
                    }
                }
                else
                {
                    $json_array['status'] =    ERROR_CODE;

                    $json_array['message'] =    "Invalid Credentials";
                }
            }
            
            echo_json($json_array);
        }
        else
        {
            $this->load->view('vendor/login');
        }
    }


    public function logout()
    {
        if ($this->is_vendor_logged_in())
        {
            $this->logout_vendor_user();
        }

        $this->session->set_flashdata('message', array('message' => 'Successfully logged out','class' => 'alert alert-success'));

        redirect('vendor/login');
    }

    public function refresh_captcha(){
        $config = array(
        'word' => '',
        'word_length' => 8,
        'img_path'   => 'captcha_images/',
        'img_url'    => SITE_URL.'captcha_images/',
        'font_path' =>  'system/fonts/texb.ttf',
        'img_width' => '150',
        'img_height' => 50,
        'font_size'  => 18,
        'expiration' => 3600,
        'img_id'        => 'Imageid',
        'pool'          => '0123456789',
        'colors'        => array(
                        'background' => array(255, 255, 255),
                        'border' => array(153,102,102),
                        'text' => array(204,153,153),
                        'grid' => array(255,182,182,255)


                )

        );

        $captcha = create_captcha($config);
        // Unset previous captcha and set new captcha word
        $this->session->unset_userdata('captchaCode');
        $this->session->set_userdata(array('captcha'=>$captcha['word']));
        
        // Display captcha image
        echo $captcha['image'];

    }


    public function forgot()
    {

       $config = array(
        'word' => '',
        'word_length' => 8,
        'img_path'   => 'captcha_images/',
        'img_url'    => SITE_URL.'captcha_images/',
        'font_path' =>  'system/fonts/texb.ttf',
        'img_width' => '150',
        'img_height' => 50,
        'font_size'  => 18,
        'expiration' => 3600,
        'img_id'        => 'Imageid',
        'pool'          => '0123456789',
        'colors'        => array(
                        'background' => array(255, 255, 255),
                        'border' => array(153,102,102),
                        'text' => array(204,153,153),
                        'grid' => array(255,182,182,255)
                )

        );

        $captcha = create_captcha($config);
        // Unset previous captcha and set new captcha word
        $this->session->unset_userdata('captchaCode');
        $this->session->set_userdata('captchaCode',$captcha['word']);
        
        
        // Pass captcha image to view
        $data['captchaImg'] = $captcha['image'];
         
      $this->load->view('vendor/forgot_password', $data);
    }


    public function forgot_password()
    {
        $json_array = array();

        if($this->input->is_ajax_request())
        {   
            $this->form_validation->set_rules('user_name', 'Email', 'required|valid_email');

            $this->form_validation->set_rules('code', 'code', 'required');

        if($this->input->post('code') == $this->session->userdata('captcha'))
         {  

            if ($this->form_validation->run() == FALSE)
            {
                 $json_array['status'] = ERROR_CODE;

                 $json_array['message'] = validation_errors('','');
            }
            else
            {
                 $email =  $this->input->post('user_name');

                $this->load->model('vendor/vendor_login_model');

                $result_array = $this->vendor_login_model->select(TRUE,array('id','first_name','email_id','mobile_no'),array('email_id' => $email,'status' => STATUS_ACTIVE));
                
                if(!empty($result_array))
                {
                        $pass_reset_key  = generate_random_str('numeric',10);

                        $fields  = array('pass_reset_key'=> $pass_reset_key, 'pass_reset_expiry' => date(DB_DATE_FORMAT));

                        $where  = array('id'=> $result_array['id']);

                        $results = $this->vendor_login_model->save($fields,$where);

                        $this->load->library('email');

                        $encrypt_insert_id =$result_array['id'];

                        $email_tmpl_data['subject']  = "Forgot Password";

                        $email_tmpl_data['first_name'] = $result_array['first_name'];

                        $email_tmpl_data['mobile_no'] = $result_array['mobile_no'];

                        $email_tmpl_data['email']  = $result_array['email_id'];

                        $email_tmpl_data['pass_url']  = VENDOR_SITE_URL."Vendor_login/change_password/$encrypt_insert_id/$pass_reset_key";

                        $this->email->vendor_forgot_password($email_tmpl_data);

                        $json_array['status'] =    SUCCESS_CODE;

                        $json_array['message'] =    "Forgot password link send to your register email ID";  
                }
                else
                {
                    $json_array['status'] =    ERROR_CODE;

                    $json_array['message'] =    "Email ID is not exits in our database";
                }
            }
          }
         else
          {

            $json_array['status'] =    ERROR_CODE;
     
            $json_array['message'] =    "'validate_captcha', 'Wrong captcha code'";
          }
        }
        else
        {
            $json_array['status'] =    ERROR_CODE;

            $json_array['message'] =    "please try again!";
        }

        echo_json($json_array);
    }

    public function change_password($admin_id = NULL,$key = NULL)
    {
        if(isset($admin_id) && $admin_id != "" && isset($key) && $key != "")
        {   
            $this->load->model('vendor/vendor_login_model');

            $where = array("id"=> $admin_id,'pass_reset_key'=> $key);

            $results = $this->vendor_login_model->select(TRUE, array('id'), $where);

            if(!empty($results))
            {
                $this->session->set_userdata('session_pass_chang', $results['id']);

                $this->load->view('vendor/reset_password');
            }
            else
            {
                $this->session->set_flashdata('error_message', array('message' => 'Link is not valid Please contact Administrator.','heading' => 'Authentication Timeout.','admin_url'=> TRUE));

                redirect("vendor/vendor_login");
            }
        }
        else
        {
            redirect('vendor/vendor_login');
        }
    }

    public function set_password()
    {   
        $json_array = array();

        if($this->input->is_ajax_request() && $this->session->userdata('session_pass_chang'))
        {   
            $this->form_validation->set_rules('password', 'Email', 'required');

            $this->form_validation->set_rules('cnf_password', 'Email', 'required|matches[password]');

            if ($this->form_validation->run() == FALSE)
            {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('','');
            }
            else
            {   
                $this->load->model('vendor/vendor_login_model');

                $password = $this->input->post('password');

                $cnf_password = $this->input->post('cnf_password');

                $enc_pass =  create_password($cnf_password);

                $where = array("id" => $this->session->userdata('session_pass_chang'));                
               
                $feilds = array('password'=> $enc_pass,
                                'pass_reset_key'   => '',
                                'modified_on'   => date(DB_DATE_FORMAT));

                $results = $this->vendor_login_model->save($feilds,$where);

                $this->session->unset_userdata('session_pass_chang');

                if($results)
                {
                    $json_array['status'] =    SUCCESS_CODE;

                    $json_array['message'] = 'Password Update Successfully';
                }
                else
                {
                    $json_array['status'] =    ERROR_CODE;

                    $json_array['message'] = 'Something went wrong,please try again';
                }
            }
        }
        else
        {
           permission_denied();
        }

        $this->logout_vendor_user();

        $json_array['redirect'] =    VENDOR_SITE_URL.'Vendor_login';

        echo_json($json_array);
    }


}