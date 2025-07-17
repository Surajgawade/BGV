<?php defined('BASEPATH') OR exit('No direct script access allowed.');

class My_Uploada extends CI_Upload  {

	protected $CI;

	public function __construct()
	{
        parent::__construct();
		$this->CI =& get_instance();
	}

	public function upload_file($config_array)
	{
		$msgs = array();

		if(!folder_exist($config_array['file_upload_path'])) {
            mkdir($config_array['file_upload_path'],0777);
        }
        
        if(!folder_exist($config_array['file_upload_path'])) {
            mkdir($config_array['file_upload_path'],0777);
        } else if(!is_writable($config_array['file_upload_path'])) {
        	$msgs['status'] = FALSE;
            $msgs['message'] = 'Problem while uploading';
        }

		if($config_array['file_data']['name']) 
        {
            $file_name = $config_array['file_data']['name'];

            $file_info  = pathinfo($file_name);

            $new_file_name = preg_replace('/[[:space:]]+/', '_', $file_info['filename']);

            $new_file_name = $new_file_name.'_'.DATE(UPLOAD_FILE_DATE_FORMAT);

            $new_file_name = str_replace('.','_',$new_file_name);

            $file_extension = $file_info['extension'];

            $new_file_name = $new_file_name.'.'.$file_extension;

            $_FILES['attchment']['name'] = $new_file_name;

            $_FILES['attchment']['tmp_name'] = $config_array['file_data']['tmp_name'];

            $_FILES['attchment']['error'] = $config_array['file_data']['error'];

            $_FILES['attchment']['size'] = $config_array['file_data']['size'];

            $config['upload_path'] = $config_array['file_upload_path'];

            $config['file_name'] = $new_file_name;

            $config['allowed_types'] = $config_array['file_permission'];

            $config['file_ext_tolower'] = TRUE;

            $config['remove_spaces'] = TRUE;

            $config['max_size'] = $config_array['file_size'];

            $this->CI->load->library('upload',$config);

            $this->CI->upload->initialize($config);
            
            if($this->CI->upload->do_upload('attchment')) {
            	$msgs['status'] = TRUE;
	            $msgs['message'] = 'Successfully Uploaded';
	            $msgs['file_name'] = $new_file_name;
            } else {   
                $msgs['status'] = FALSE;
            	$msgs['message'] = $this->CI->upload->display_errors();
            }
        }
        else 
        {
        	$msgs['status'] = FALSE;
            $msgs['message'] = 'Uplaod File';
        }
        return $msgs;
	}
}