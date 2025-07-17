<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Download_education extends CI_Controller {
	
	function __construct() {
        parent::__construct();
       
    }

    public function folderdownload($id){

        try{
                ini_set('memory_limit', '-1');
                set_time_limit(0);

                $this->load->library('zip');    
                $this->load->helper('file');

                $finallink = SITE_BASE_PATH . EDUCATION . 'vendor_mail_file/'. base64_decode($id);
               
                if (is_dir($finallink)){
                    if ($dh = opendir($finallink)){
                      while (($file = readdir($dh)) !== false){
                        $this->zip->read_file(( $finallink.'/'.$file));
                      }
                      closedir($dh);
                    }
                  }
               
                $this->zip->download(''.time().'.zip');

        }catch(Exception $e){
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }

    }
          
}
?>