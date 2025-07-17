<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Device extends CI_Controller
{
    
    public function detect_browser()
    {   
        
        echo "<title> Tutorial and Example </title>";    
         $this->load->library('user_agent');
         $info = $this->agent->platform();
                 echo "<br/>". "Operating System  ". $info;
                 echo $this->agent->version();exit();
         if ($this->agent->is_browser()) 
         {
            $user = $this->agent->browser();
         }
         if ($this->agent->is_mobile())
         {
             $user_m = $this->agent->is_mobile([$key = NULL] ); 
         }
         if ($this->agent->is_robot())
         {
             $user_r = $this->agent->robot(); 
         }
         echo $user. " ".$user_m. " ".$user_r;
    }
}
