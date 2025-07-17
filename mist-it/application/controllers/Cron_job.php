<?php defined('BASEPATH') or exit('No direct script access allowed');

class Cron_job extends CI_Controller{
    
    function __construct(){

        parent::__construct();
       
    }
    
    function sendMail()
    {
      
        
        $this->load->library('email');
          $this->email->set_newline("\r\n");

          $email_tmpl_data['from_emails'] = 'employment.ver@mistitservices.com';
          $email_tmpl_data['to_emails'] = 'surajgawade192@gmail.com';
          $email_tmpl_data['subject'] = 'Test Mail';
          $email_tmpl_data['message'] = 'Test Mail';



          $result = $this->email->client_add_cases_and_pre_post($email_tmpl_data);

    }
}