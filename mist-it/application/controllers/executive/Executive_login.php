<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Executive_login extends MY_Executive_Cotroller
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
     
  
        if($this->is_vendor_executive_logged_in())
        {
        
            $data['header_title'] = "Dashboard";

            $this->load->view('executive/header',$data);
            
            $this->load->view('executive/index');

            $this->load->view('executive/footer');

        }
        else
        {
           
            $this->login();
        }
    }

    public function login()
    {
      
        $json_array = array();

        if ($this->is_vendor_executive_logged_in())
        {  
          
            redirect(VENDOR_EXECUTIVE_SITE_URL.'index');
        }

        if($this->input->is_ajax_request())
        {
         
            $this->form_validation->set_error_delimiters('', '');

            $this->form_validation->set_rules('venuser_name', 'Mobile No', 'required');

            $this->form_validation->set_rules('venpassword', 'Password', 'required');

            if ($this->form_validation->run() == FALSE)
            {
                $json_array['status'] =  ERROR_CODE;

                $json_array['message'] = validation_errors('','');
            }
            else
            {
                $this->load->model('executive/vendor_executive_login_model');

                $login_details = $this->input->post();

                $result_array = $this->vendor_executive_login_model->select(TRUE,array('id','password'),array('mobile_no' => $login_details['venuser_name'],'status' => STATUS_ACTIVE));
             
                if(!empty($result_array))
                {
                   
                    if(password_verify($login_details['venpassword'], $result_array['password']) === TRUE)
                    {
                       
                        $session_data = $this->vendor_executive_login_model->vendor_executive_details(array('vendor_executive_login.id' => $result_array['id']));

                        $this->session->set_userdata(
                                    array('vendor_executive'=>array(
                                            'id' => $session_data['id'],
                                            'vendors_id' => $session_data['vendor_login_id'],
                                            'email_id' => $session_data['email_id'],
                                            'first_name' => $session_data['first_name'],
                                            'last_name' => $session_data['last_name'],
                                            'mobile_no' => $session_data['mobile_no'],
                                            'profile_pic' => $session_data['profile_pic'],

                                            'vendor_executive_logged_in' => TRUE,
                                            'vdctkn'        => ''
                                        ))
                                );
                        
                        $this->set_vendor_executive_login_cookie(array('vendsessid'=>$session_data['id']));


                        $json_array['status'] =    SUCCESS_CODE;

                        $json_array['message'] =    "Successfully logged in";

                        $json_array['redirect'] =   VENDOR_EXECUTIVE_SITE_URL;
                        
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
            $this->load->view('executive/login');
        }
    }

    public function logout()
    {
        if ($this->is_vendor_executive_logged_in())
        {
            $this->logout_vendor_executive_user();
        }

        $this->session->set_flashdata('message', array('message' => 'Successfully logged out','class' => 'alert alert-success'));

        redirect('executive/login');
    }
}
?>

    