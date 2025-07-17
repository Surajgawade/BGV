<?php defined('BASEPATH') or exit('No direct script access allowed');
class Backup extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!$this->is_admin_logged_in()) {
            redirect('admin/login');
            exit();
        }
        
       $this->load->helper('file_helper');
       $this->load->helper('download');
       $this->load->library('zip');
       $this->perm_array = array('direct_access' => true);

       
    }

    public function index()
    {

        $data['header_title'] = "Datatabase Download";

        
        $this->load->view('admin/header', $data);

        $this->load->view('admin/backup');

        $this->load->view('admin/footer');
    }
    public function take_backup()
    {

        /*$this->load->dbutil();
        $config = array('format' => 'zip', 'filename' => 'Database-backup_' . date('Y-m-d_H-i-s').'.sql');
    
        $backup = $this->dbutil->backup($config);
        $file_upload_path = SITE_BASE_PATH . UPLOAD_FOLDER . '/backup';

        if (!folder_exist($file_upload_path)) {
            mkdir($file_upload_path, 0777);
        } else if (!is_writable($file_upload_path)) {
            array_push($error_msgs, 'Problem while uploading');
        }

        $db_name =  'BD-backup_' . date('Y-m-d_H-i-s') . '.zip';
        if (!write_file($file_upload_path.'/'.$db_name, $backup)) {
           echo "Error while creating auto database backup!";
        }
        else {
           force_download($db_name,$backup);
        }*/

        $user = 'root';
        $pass = 'sA1n0XmpmWXBwEQamA49hmiXBX6W4xaHeA7oXKTxb9UbZOa3';
        $host = 'localhost';
        $database = 'viotal_db_v2';
          
        $file_name = 'Database-backup_' . date('Y-m-d_H-i-s').'.sql.gz';  
        $dir_2 =  SITE_BASE_PATH . UPLOAD_FOLDER . 'backup/'.$file_name;

      //  exec("mysqldump --user={$user} --password={$pass} --host={$host} {$database} --result-file={$dir_2} 2>&1", $output_2);
        exec("mysqldump --user={$user} --password={$pass} --host={$host} --single-transaction {$database} | gzip > ".SITE_BASE_PATH . UPLOAD_FOLDER . "backup/". $file_name);

        if(!empty($file_name)){
           
            if(file_exists($dir_2))
            {
              header('Content-Type: application/octet-stream');  
              header('Content-Disposition: attachment; filename=' . $file_name);  
              readfile($dir_2); 
              exit;
            }
            else
            {
              echo 'File does not exists on given path';
            }
        } 
    }

    public function delete_backup(){ 

        if(isset($_POST['delete_file']))
        {
         $filename = $_POST['file_name'];

            if(file_exists(SITE_BASE_PATH . UPLOAD_FOLDER . 'backup/'.$filename))
            {
              unlink(SITE_BASE_PATH . UPLOAD_FOLDER . 'backup/'.$filename);
              
              echo 'File delete successfully';
            }
            else{

                echo 'File does not exists on given path';
            }    
        }

    }  

}
