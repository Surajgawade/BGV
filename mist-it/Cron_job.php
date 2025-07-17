<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Cron_job extends CI_Controller{
    
    function __construct(){

        parent::__construct();
       
    }
    
    function sendMail()
    {
        $config = Array(
      'protocol' => 'smtp',
      'smtp_host' => 'ssl://smtp.googlemail.com',
      'smtp_port' => 465,
      'smtp_user' => 'xxx@gmail.com', // change it to yours
      'smtp_pass' => 'xxx', // change it to yours
      'mailtype' => 'html',
      'charset' => 'iso-8859-1',
      'wordwrap' => TRUE
    );
    
        $message = 'Test Mail';
        $this->load->library('email', $config);
          $this->email->set_newline("\r\n");
          $this->email->from('xxx@gmail.com'); // change it to yours
          $this->email->to('xxx@gmail.com');// change it to yours
          $this->email->subject('Resume from JobsBuddy for your Job posting');
          $this->email->message($message);
          if($this->email->send())
         {
          echo 'Email sent.';
         }
         else
        {
         show_error($this->email->print_debugger());
        }
    
    }
}