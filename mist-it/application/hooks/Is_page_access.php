<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Is_page_access extends MY_Controller
{    
    var $CI;
    
    public function __construct() {
        $this->CI =& get_instance(); 
    }

    public function check_permissions() 
    {
        $page_id = isset($this->CI->perm_array['page_id']) ? $this->CI->perm_array['page_id'] : '';
        
        if(isset($this->CI->user_info) && !empty($this->CI->user_info))
        {
            if(!isset($this->CI->perm_array['direct_access']))
            {
                if(!in_array($page_id, explode(',', $this->CI->user_info['access_page_id'])))
                {
                    show_error("You don't have permission to access this page. Please contact the administrator.",403,"Permission Denied");
                }
            }
        }
        else if(isset($this->CI->vendor_info) && !empty($this->CI->vendor_info))
        {
            $page_name = isset($this->CI->perm_array['page_name']) ? $this->CI->perm_array['page_name'] : '';
                
            if(!isset($this->CI->perm_array['direct_access']))
            {

                if(!in_array($page_name, explode(',', $this->CI->vendor_info['vendors_components'])))
                {
                    show_error("You don't have permission to access this page. Please contact the administrator.",403,"Permission Denied");
                }

            }
        }
    }
}