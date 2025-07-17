<?php defined('BASEPATH') OR exit('No direct script access allowed.');
class File_upload_library {
    protected $CI;
    private $is_crop_hard;
    private $compress;
    public function __construct()
    {
        $this->CI =& get_instance();
        $this->is_crop_hard = isset($arr['is_crop_hard'])?(bool)$arr['is_crop_hard']:false;
        $this->compress     = isset($arr['compress'])? ($arr['compress']>=0 && $arr['compress']<=1)? $arr['compress']:1:0.8;
    }
    public function file_uplod_same($config_array)
    {
        $msgs = array();
        if(!folder_exist($config_array['file_upload_path'])) {
            mkdir($config_array['file_upload_path'],0777,true);
        } else if(!is_writable($config_array['file_upload_path'])) {
            $msgs['status'] = FALSE;
            $msgs['message'] = 'Problem while uploading';
        }
        if($config_array['file_data']['name']) 
        {
            $file_name = $config_array['file_data']['name'];
            $file_info  = pathinfo($file_name);
            $new_file_name = preg_replace('/[[:space:]]+/', '_', $file_info['filename']);
            $new_file_name = str_replace('.','_',$new_file_name);
            $new_file_name = $new_file_name.'.'.strtolower($file_info['extension']);
            $_FILES['attchment']['name'] = $new_file_name;
            $_FILES['attchment']['tmp_name'] = $config_array['file_data']['tmp_name'];
            $_FILES['attchment']['error'] = $config_array['file_data']['error'];
            $_FILES['attchment']['size'] = $config_array['file_data']['size'];
            $config['upload_path'] = $config_array['file_upload_path'];
            $config['file_name'] = $new_file_name;
            $config['allowed_types'] = $config_array['file_permission'];
            $config['file_ext_tolower'] = TRUE;
            $config['remove_spaces'] = TRUE;
            $config['maintain_ratio'] = TRUE;
            if(isset($config_array['resize']) && !empty($config_array['resize']))
            {
                $config['width']    = $config_array['resize']['width'];
                $config['height']   = $config_array['resize']['height'];
            }
            $config['max_size'] = $config_array['file_size'];
            $this->CI->load->library('upload',$config);
            $this->CI->upload->initialize($config);
            if($this->CI->upload->do_upload('attchment')) {
                $msgs['status'] = TRUE;
                $msgs['message'] = 'Successfully Uploaded';
                $msgs['file_name'] = $new_file_name;
            } else {   
                $msgs['status'] = FALSE;
                $msgs['message'] = $this->CI->upload->display_errors('','');
            }
        }
        else 
        {
            $msgs['status'] = FALSE;
            $msgs['message'] = 'Uplaod File';
        }
        return $msgs;
    }
    public function file_uplod($config_array)
    {
        $msgs = array();
        if(!folder_exist($config_array['file_upload_path'])) {
            mkdir($config_array['file_upload_path'],0777,true);
        } else if(!is_writable($config_array['file_upload_path'])) {
            $msgs['status'] = FALSE;
            $msgs['message'] = 'Problem while uploading';
        }
        if($config_array['file_data']['name']) 
        {
            $file_name = $config_array['file_data']['name'];
            $file_info  = pathinfo($file_name);
            $new_file_name = preg_replace('/[[:space:]]+/', '_', $file_info['filename']);
            $new_file_name = str_replace(',', '_', $new_file_name);
            $new_file_name = str_replace('.','_',$new_file_name.'_'.DATE(UPLOAD_FILE_DATE_FORMAT));
            $new_file_name = $new_file_name.'.'.strtolower($file_info['extension']);
            $_FILES['attchment']['name'] = $new_file_name;
            $_FILES['attchment']['tmp_name'] = $config_array['file_data']['tmp_name'];
            $_FILES['attchment']['error'] = $config_array['file_data']['error'];
            $_FILES['attchment']['size'] = $config_array['file_data']['size'];
            $config['upload_path'] = $config_array['file_upload_path'];
            $config['file_name'] = $new_file_name;
            $config['allowed_types'] = $config_array['file_permission'];
            $config['file_ext_tolower'] = TRUE;
            $config['remove_spaces'] = TRUE;
            $config['maintain_ratio'] = TRUE;
            if(isset($config_array['resize']) && !empty($config_array['resize']))
            {
                $config['width']    = $config_array['resize']['width'];
                $config['height']   = $config_array['resize']['height'];
            }
            $config['max_size'] = $config_array['file_size'];
            $this->CI->load->library('upload',$config);
            $this->CI->upload->initialize($config);
            if($this->CI->upload->do_upload('attchment')) {

                $msgs['status'] = TRUE;
                $msgs['messa ge'] = 'Successfully Uploaded';
                $msgs['file_name'] = $new_file_name;

                if($config_array['file_data']['type'] == 'application/pdf') {
                    $jpg = '.png';
                    $file = pathinfo($file_name);
                    $file2 = pathinfo($new_file_name);
                    
                    $source_file = $config_array['file_upload_path'].'/'.$new_file_name;
                    $distination_path= $config_array['file_upload_path'].'/';
                    $pagecount = $this->pdf_page_count($source_file,$distination_path);

                    $this->conver_pdf_to_jpg($source_file,$distination_path);
                    
                    if(isset($config_array['global_db_file_separator'])) {

                        $jpg = '.png';
                        $multiple_file_name = '';

                        if($pagecount == 1) {
                            $msgs['file_name'] = $file2['filename'].$jpg;
                            $msgs['multiple_file_name'] = false;
                        } else {
                            for ($i=0; $i < $pagecount; $i++) { 
                                
                                $leading_filename = $i;
                                $multiple_file_name .= $file2['filename']."-".$leading_filename.$jpg."||";
                            }
                        }
                        $msgs['file_name'] = $multiple_file_name;
                        $msgs['multiple_file_name'] = true;
                    }else {

                        $msgs['file_name'] = $file2['filename'].$jpg;
                    }
                }

            } else {   
                $msgs['status'] = FALSE;
                $msgs['message'] = $this->CI->upload->display_errors('','');
            }
        }
        else 
        {
            $msgs['status'] = FALSE;
            $msgs['message'] = 'Uplaod File';
        }
        return $msgs;
    }

    public function file_upload_for_address_verification($config_array)
    {
        $msgs = array();
        if(!folder_exist($config_array['file_upload_path'])) {
            mkdir($config_array['file_upload_path'],0777);
        } else if(!is_writable($config_array['file_upload_path'])) {
            $msgs['status'] = FALSE;
            $msgs['message'] = 'Problem while uploading';
        }
        $random = rand(0000,9999);
        if($config_array['file_data']['name']) 
        {
            $file_name = $config_array['file_data']['name'];
            $file_info  = pathinfo($file_name);
            $new_file_name = preg_replace('/[[:space:]]+/', '_', $file_info['filename']);
            $new_file_name = str_replace('.','_',$new_file_name.'_'.$random.'_'.DATE(UPLOAD_FILE_DATE_FORMAT));
            $new_file_name = $new_file_name.'.'.strtolower($file_info['extension']);
            $_FILES['attchment']['name'] = $new_file_name;
            $_FILES['attchment']['tmp_name'] = $config_array['file_data']['tmp_name'];
            $_FILES['attchment']['error'] = $config_array['file_data']['error'];
            $_FILES['attchment']['size'] = $config_array['file_data']['size'];
            $config['upload_path'] = $config_array['file_upload_path'];
            $config['file_name'] = $new_file_name;
            $config['allowed_types'] = $config_array['file_permission'];
            $config['file_ext_tolower'] = TRUE;
            $config['remove_spaces'] = TRUE;
            $config['maintain_ratio'] = TRUE;
            if(isset($config_array['resize']) && !empty($config_array['resize']))
            {
                $config['width']    = $config_array['resize']['width'];
                $config['height']   = $config_array['resize']['height'];
            }
            $config['max_size'] = $config_array['file_size'];
            $this->CI->load->library('upload',$config);
            $this->CI->upload->initialize($config);
            if($this->CI->upload->do_upload('attchment')) {
                $msgs['status'] = TRUE;
                $msgs['message'] = 'Successfully Uploaded';
                $msgs['file_name'] = $new_file_name;
            } else { 
                $msgs['status'] = FALSE;
                $msgs['message'] = $this->CI->upload->display_errors('','');
            }
        }
        else 
        {
            $msgs['status'] = FALSE;
            $msgs['message'] = 'Uplaod File';
        }
        return $msgs;
    }
    
    public function file_upload_multiple($config_array,$pdf_to_jpg = false)
    {
        $file_array = $error_msgs =array();

        if(!folder_exist($config_array['file_upload_path'])) {            
            mkdir($config_array['file_upload_path'],0777,true);
        } 
        for($i = 0; $i < $config_array['files_count']; $i++)
        {
            try {
                if($config_array['file_data']['name'][$i]) 
                {
                    $file_name = $config_array['file_data']['name'][$i];
                    $file_info  = pathinfo($file_name);
                    $new_file_name = preg_replace('/[[:space:]]+/', '_', $file_info['filename']);
                    $new_file_name = preg_replace('/[^A-Za-z0-9\-]/', '_', $new_file_name);
                    $new_file_name = $new_file_name.'_'.DATE(UPLOAD_FILE_DATE_FORMAT);
                    $new_file_name = str_replace('.','_',$new_file_name);
                    $new_file_name = str_replace(',', '_', $new_file_name);
                    $file_extension = strtolower($file_info['extension']);
                    $new_file_name = $new_file_name.'.'.$file_extension;

                    $_FILES['attchment']['name'] = $new_file_name;
                    $_FILES['attchment']['tmp_name'] = $config_array['file_data']['tmp_name'][$i];
                    $_FILES['attchment']['error'] = $config_array['file_data']['error'][$i];
                    $_FILES['attchment']['size'] = $config_array['file_data']['size'][$i];
                    $config['upload_path'] = $config_array['file_upload_path'];
                    $config['file_name'] = $new_file_name;
                    $config['allowed_types'] = $config_array['file_permission'];
                    $config['file_ext_tolower'] = TRUE;
                    $config['remove_spaces'] = TRUE;
                    $config['max_size'] = $config_array['file_size'];

                    $this->CI->load->library('upload',$config);
                    $this->CI->upload->initialize($config);
                    if($this->CI->upload->do_upload('attchment'))
                    {
                       
                        if($pdf_to_jpg && $config_array['file_data']['type'][$i] == 'application/pdf') {

                            $jpg = '.png';
                            $source_file = $config_array['file_upload_path'].'/'.$new_file_name;
                            $distination_path= $config_array['file_upload_path'].'/';
                            $pagecount = $this->pdf_page_count($source_file,$distination_path);
                            
                            $this->conver_pdf_to_jpg($source_file,$distination_path);
                            if($pagecount == 1) {
                                $file = pathinfo($file_name);
                                $file2 = pathinfo($new_file_name);
                                $file_array[] =  array(
                                                    'real_filename'  => $file['filename'].$jpg,
                                                    'file_name'      => $file2['filename'].$jpg,
                                                    'type'           => $config_array['type'],
                                                    $config_array['component_name'] => $config_array['file_id']
                                                );
                            } else  {
                                for ($i=0 ; $i < $pagecount; $i++) { 
                                    $file = pathinfo($file_name);
                                    $file2 = pathinfo($new_file_name);
                                    $file_array[] = array(
                                        'real_filename'=> $file['filename']."-".$i.$jpg,
                                        'file_name'   => $file2['filename']."-".$i.$jpg,
                                        'type' => $config_array['type'],
                                        $config_array['component_name'] => $config_array['file_id']
                                    );
                                }
                            }
                        } 
                        else 
                        {
                            $file_array[] = array(
                                'real_filename'=> $file_name,
                                'file_name'=> $new_file_name,
                                'type' => $config_array['type'],
                                $config_array['component_name'] => $config_array['file_id']
                            ); 
                        }
                    }
                    else
                    {   
                        array_push($error_msgs,$this->CI->upload->display_errors('',''));
                    }
                }
            } catch (Exception $e) {
                log_message('error', 'File_upload_library::file_upload_multiple');
                log_message('error', $e->getMessage());
            }
        }
        log_message('error', 'File Upload status');
        log_message('error', print_r($error_msgs, true));
        log_message('error', print_r($file_array, true));

        return array('success'  =>  $file_array,    'fail'  =>  $error_msgs);
    }

    public function file_upload_multiple_address($config_array,$pdf_to_jpg = false)
    {
        $file_array = $error_msgs =array();

        if(!folder_exist($config_array['file_upload_path'])) {            
            mkdir($config_array['file_upload_path'],0777,true);
        } 
        for($i = 0; $i < $config_array['files_count']; $i++)
        {
            try {
                if($config_array['file_data']['name'][$i]) 
                {
                    $file_name = $config_array['file_data']['name'][$i];
                    $file_info  = pathinfo($file_name);
                    $new_file_name = preg_replace('/[[:space:]]+/', '_', $file_info['filename']);
                    $new_file_name = preg_replace('/[^A-Za-z0-9\-]/', '_', $new_file_name);
                    $new_file_name = $new_file_name.'_'.DATE(UPLOAD_FILE_DATE_FORMAT);
                    $new_file_name = str_replace('.','_',$new_file_name);
                    $new_file_name = str_replace(',', '_', $new_file_name);
                    $file_extension = strtolower($file_info['extension']);
                    $new_file_name = $new_file_name.'.'.$file_extension;

                    $_FILES['attchment']['name'] = $new_file_name;
                    $_FILES['attchment']['tmp_name'] = $config_array['file_data']['tmp_name'][$i];
                    $_FILES['attchment']['error'] = $config_array['file_data']['error'][$i];
                    $_FILES['attchment']['size'] = $config_array['file_data']['size'][$i];
                    $config['upload_path'] = $config_array['file_upload_path'];
                    $config['file_name'] = $new_file_name;
                    $config['allowed_types'] = $config_array['file_permission'];
                    $config['file_ext_tolower'] = TRUE;
                    $config['remove_spaces'] = TRUE;
                    $config['max_size'] = $config_array['file_size'];

                    $this->CI->load->library('upload',$config);
                    $this->CI->upload->initialize($config);
                    if($this->CI->upload->do_upload('attchment'))
                    {
                      
                        if($pdf_to_jpg && $config_array['file_data']['type'][$i] == 'application/pdf') {

                            $jpg = '.png';
                            $source_file = $config_array['file_upload_path'].'/'.$new_file_name;
                            $distination_path= $config_array['file_upload_path'].'/';
                            $pagecount = $this->pdf_page_count($source_file,$distination_path);
                           
                            $this->conver_pdf_to_jpg($source_file,$distination_path);
                            if($pagecount == 1) {
                                $file = pathinfo($file_name);
                                $file2 = pathinfo($new_file_name);
                                $file_array[] =  array(
                                                    'real_filename'  => $new_file_name,
                                                    'file_name'      => $file2['filename'].$jpg,
                                                    'type'           => $config_array['type'],
                                                    $config_array['component_name'] => $config_array['file_id']
                                                );
                            } else  {
                                for ($i=0 ; $i < $pagecount; $i++) { 
                                    $file = pathinfo($file_name);
                                    $file2 = pathinfo($new_file_name);
                                    $file_array[] = array(
                                        'real_filename'=> $new_file_name,
                                        'file_name'   => $file2['filename']."-".$i.$jpg,
                                        'type' => $config_array['type'],
                                        $config_array['component_name'] => $config_array['file_id']
                                    );
                                }
                            }
                        }
                        elseif($config_array['file_data']['type'][$i] == 'image/jpg' || $config_array['file_data']['type'][$i] == 'image/jpeg'){
                        
                            $jpg = '.png';
                            $source_file = $config_array['file_upload_path'].'/'.$new_file_name;
                            $distination_path= $config_array['file_upload_path'].'/';
                            
                            $this->convert_jpg_to_png($source_file,$distination_path);
                           
                            $file = pathinfo($file_name);
                            $file2 = pathinfo($new_file_name);
                            $file_array[] =  array(
                                    'real_filename'  => $new_file_name,
                                    'file_name'      => $file2['filename'].$jpg,
                                    'type'           => $config_array['type'],
                                    $config_array['component_name'] => $config_array['file_id']
                                );
                            
                        } 
                        else 
                        {
                             
                            $file_array[] = array(
                                'real_filename'=> $file_name,
                                'file_name'=> $new_file_name,
                                'type' => $config_array['type'],
                                $config_array['component_name'] => $config_array['file_id']
                            ); 
                        }
                    }
                    else
                    {   
                        array_push($error_msgs,$this->CI->upload->display_errors('',''));
                    }
                }
            } catch (Exception $e) {
                log_message('error', 'File_upload_library::file_upload_multiple');
                log_message('error', $e->getMessage());
            }
        }
        log_message('error', 'File Upload status');
        log_message('error', print_r($error_msgs, true));
        log_message('error', print_r($file_array, true));

        return array('success'  =>  $file_array,    'fail'  =>  $error_msgs);
    }

    public function file_upload_multiple_vendor($config_array,$pdf_to_jpg = false)
    {
        $file_array = $error_msgs =array();

        if(!folder_exist($config_array['file_upload_path'])) {            
            mkdir($config_array['file_upload_path'],0777,true);
        } 
        for($i = 0; $i < $config_array['files_count']; $i++)
        {
            try {
                if($config_array['file_data']['name'][$i]) 
                {
                    $file_name = $config_array['file_data']['name'][$i];
                    $file_info  = pathinfo($file_name);
                    $new_file_name = preg_replace('/[[:space:]]+/', '_', $file_info['filename']);
                    $new_file_name = preg_replace('/[^A-Za-z0-9\-]/', '_', $new_file_name);
                    $new_file_name = $new_file_name.'_'.DATE(UPLOAD_FILE_DATE_FORMAT);
                    $new_file_name = str_replace('.','_',$new_file_name);
                    $new_file_name = str_replace(',', '_', $new_file_name);
                    $file_extension = strtolower($file_info['extension']);
                    $new_file_name = $new_file_name.'.'.$file_extension;

                    $_FILES['attchment']['name'] = $new_file_name;
                    $_FILES['attchment']['tmp_name'] = $config_array['file_data']['tmp_name'][$i];
                    $_FILES['attchment']['error'] = $config_array['file_data']['error'][$i];
                    $_FILES['attchment']['size'] = $config_array['file_data']['size'][$i];
                    $config['upload_path'] = $config_array['file_upload_path'];
                    $config['file_name'] = $new_file_name;
                    $config['allowed_types'] = $config_array['file_permission'];
                    $config['file_ext_tolower'] = TRUE;
                    $config['remove_spaces'] = TRUE;
                    $config['max_size'] = $config_array['file_size'];
                    $this->CI->load->library('upload',$config);
                    $this->CI->upload->initialize($config);
                    if($this->CI->upload->do_upload('attchment'))
                    {
                        if($pdf_to_jpg && $config_array['file_data']['type'][$i] == 'application/pdf') {
                        
                            $jpg = '.png';
                            $source_file = $config_array['file_upload_path'].'/'.$new_file_name;
                            $distination_path= $config_array['file_upload_path'].'/';
                            $pagecount = $this->pdf_page_count($source_file,$distination_path);
                           
                            $this->conver_pdf_to_jpg($source_file,$distination_path);
                            if($pagecount == 1) {
                                $file = pathinfo($file_name);
                                $file2 = pathinfo($new_file_name);
                                $file_array[] =  array(
                                                    'real_filename'  => $file['filename'].$jpg,
                                                    'file_name'      => $file2['filename'].$jpg,
                                                    'view_venor_master_log_id' => $config_array['view_venor_master_log_id'],
                                                    'component_tbl_id' => $config_array['component_tbl_id'],
                                                    'status' => 1
                                                );
                            } else  {
                                for ($i=0 ; $i < $pagecount; $i++) { 
                                    $file = pathinfo($file_name);
                                    $file2 = pathinfo($new_file_name);
                                    $file_array[] = array(
                                        'real_filename'  => $file['filename']."-".$i.$jpg,
                                        'file_name'      => $file2['filename']."-".$i.$jpg,
                                        'view_venor_master_log_id' => $config_array['view_venor_master_log_id'],
                                        'component_tbl_id' => $config_array['component_tbl_id'],
                                        'status' => 1
                                    );
                                }
                            }
                     
                        } 
                        else 
                        {
                            $file_array[] = array(
                                'real_filename'  => $file_name,
                                'file_name'      => $new_file_name,
                                'view_venor_master_log_id' => $config_array['view_venor_master_log_id'],
                                'component_tbl_id' => $config_array['component_tbl_id'],
                                'status' => 1
                            ); 
                        }
                    }
                    else
                    {   
                        array_push($error_msgs,$this->CI->upload->display_errors('',''));
                    }
                }
            } catch (Exception $e) {
                log_message('error', 'File_upload_library::file_upload_multiple');
                log_message('error', $e->getMessage());
            }
        }
        log_message('error', 'File Upload status');
        log_message('error', print_r($error_msgs, true));
        log_message('error', print_r($file_array, true));

        return array('success'  =>  $file_array,    'fail'  =>  $error_msgs);
    }
    protected function chnage_extension($filename, $new_extension = 'jpg')
    {
        $info = pathinfo($filename);
        return $info['filename'] . '.' . $new_extension;
    }

    public function compress_image($source, $destination, $quality) {

        $info = getimagesize($source);

        if ($info['mime'] == 'image/jpeg') 
            $image = imagecreatefromjpeg($source);

        elseif ($info['mime'] == 'image/gif') 
            $image = imagecreatefromgif($source);

        elseif ($info['mime'] == 'image/png') 
            $image = imagecreatefrompng($source);

        imagejpeg($image, $destination, $quality);

        return $destination;
    }


    protected function pdf_page_count($file_path)
    {   
        try {
            $count = 0;

            if($file_path != "") {
                $cmd = sprintf("identify %s", $file_path);
      
                exec($cmd,$output);  
                $count = count($output);
            }
        } catch (Exception $e) {
            log_message('error', 'File_upload_library::pdf_page_count');
            log_message('error', $e->getMessage());
        }
        return $count;
        // for windows
        // $pagecount = 0;
        // if($file_path != "") {
        //     $cmd = "C:\\xpdf-tools-win-4.02\\bin64\\pdfinfo.exe";  // Windows
        //     exec("$cmd \"$file_path\"", $output);
        //     foreach($output as $op)
        //     {
        //         if(preg_match("/Pages:\s*(\d+)/i", $op, $matches) === 1)
        //         {
        //             $pagecount = intval($matches[1]);
        //             break;
        //         }
        //     }
        // }
        // return $pagecount;
    }

    protected function conver_pdf_to_jpg($source,$distination)
    {
        try {
            $pdf = $source;
            $info = pathinfo($pdf);
            $file_name =  $distination.basename($pdf,'.'.$info['extension']);
            exec("convert -density 200 $pdf -quality 100 $file_name.png > /dev/null &");
            return true;
        } catch (Exception $e) {
            log_message('error', 'File_upload_library::conver_pdf_to_jpg');
            log_message('error', $e->getMessage());
        }
        // for windows
        // $cmd = "C:\\xpdf-tools-win-4.02\\bin64\\pdftopng.exe";  // Windows
        // exec("$cmd $source $distination", $output);
        // return true;
    }

    protected function convert_jpg_to_png($source,$distination)
    {
        try {
            $jpg = $source;
            $info = pathinfo($jpg);
            $file_name =  $distination.basename($jpg,'.'.$info['extension']);
       //     exec("convert -density 300 $jpg -quality 120 $file_name.png > /dev/null &");
           exec("convert $jpg $file_name.png > /dev/null &");
            return true;
        } catch (Exception $e) {
            log_message('error', 'File_upload_library::convert_jpg_to_png');
            log_message('error', $e->getMessage());
        }
       
    }

    public function bulk_file_upload_multiple($config_array)
    {
        $file_array = $error_msgs =array();
        if(!file_exists($config_array['file_upload_path'])){
            mkdir($config_array['file_upload_path'],0777,true);
        }
        for($i = 0; $i < $config_array['files_count']; $i++)
        {
            try {
                if($config_array['file_data']['name'][$i]) 
                {
                    $file_name = $config_array['file_data']['name'][$i];
                    $file_info  = pathinfo($file_name);
                    $new_file_name = preg_replace('/[[:space:]]+/', '_', $file_info['filename']);
                    $new_file_name = $new_file_name.'_'.DATE(UPLOAD_FILE_DATE_FORMAT);
                    $new_file_name = str_replace('.','_',$new_file_name);
                    $new_file_name = str_replace(',', '_', $new_file_name);
                    $file_extension = strtolower($file_info['extension']);
                    $new_file_name = $new_file_name.'.'.$file_extension;
                    $_FILES['attchment']['name'] = $file_name;
                    $_FILES['attchment']['tmp_name'] = $config_array['file_data']['tmp_name'][$i];
                    $_FILES['attchment']['error'] = $config_array['file_data']['error'][$i];
                    $_FILES['attchment']['size'] = $config_array['file_data']['size'][$i];
                    $config['upload_path'] = $config_array['file_upload_path'];
                    $config['file_name'] = $file_name;
                    $config['allowed_types'] = $config_array['file_permission'];
                    $config['file_ext_tolower'] = TRUE;
                    $config['remove_spaces'] = TRUE;
                    $config['max_size'] = $config_array['file_size'];
                    $this->CI->load->library('upload',$config);
                    $this->CI->upload->initialize($config);
                    if($this->CI->upload->do_upload('attchment'))
                    {
                        array_push($file_array,array(
                                'og_name'=> $file_name,
                                'name'=> $new_file_name,
                                $config_array['component_name'] => $config_array['file_id'])
                        );
                    }
                    else
                    {   
                        array_push($error_msgs,$this->CI->upload->display_errors('',''));
                    }
                }
            } catch (Exception $e) {
                log_message('error', 'File_upload_library::bulk_file_upload_multiple');
                log_message('error', $e->getMessage());
            }
        }
        return array('success'=>$file_array,'fail'=>$error_msgs);
    }

    private function image_data($path, $mime){

        if (!strstr($mime, 'image/')) {
            return false;
        }

        if($mime=='image/png'){ $src_img = imagecreatefrompng($path); }
        else if($mime=='image/jpeg' or $mime=='image/jpg' or $mime=='image/pjpeg') {
            $src_img = imagecreatefromjpeg($path);
        }
        else $src_img = false;
        return $src_img;
    }

    private function save($dst_src, $new_thumb_loc, $mime){
        if($mime=='image/png'){ $result = imagepng($dst_src,$new_thumb_loc,$this->compress*10); }
        else if($mime=='image/jpeg' or $mime=='image/jpg' or $mime=='image/pjpeg') {
            $result = imagejpeg($dst_src,$new_thumb_loc,$this->compress*100);
        }
        return $result;
    }

    public function file_upload_multiple_education($config_array,$img_to_pdf = false)
    {
        $file_array = $error_msgs = $image_file_array = array();

        if(!folder_exist($config_array['file_upload_path'])) {            
            mkdir($config_array['file_upload_path'],0777,true);
        } 
        try {
       
            for($i = 0; $i < $config_array['files_count']; $i++)
            {
                if($config_array['file_data']['name'][$i]) 
                {
                    $file_name = $config_array['file_data']['name'][$i];
                    $file_info  = pathinfo($file_name);
                    $new_file_name = preg_replace('/[[:space:]]+/', '_', $file_info['filename']);
                    $new_file_name = preg_replace('/[^A-Za-z0-9\-]/', '_', $new_file_name);
                    $new_file_name = $config_array['education_id'].'_'.$new_file_name.'_'.DATE(UPLOAD_FILE_DATE_FORMAT);
                    $new_file_name = str_replace('.','_',$new_file_name);
                    $new_file_name = str_replace(',', '_', $new_file_name);
                    $file_extension = strtolower($file_info['extension']);
                    $new_file_name = $new_file_name.'.'.$file_extension;

                    $_FILES['attchment']['name'] = $new_file_name;
                    $_FILES['attchment']['tmp_name'] = $config_array['file_data']['tmp_name'][$i];
                    $_FILES['attchment']['error'] = $config_array['file_data']['error'][$i];
                    $_FILES['attchment']['size'] = $config_array['file_data']['size'][$i];
                    $config['upload_path'] = $config_array['file_upload_path'];
                    $config['file_name'] = $new_file_name;
                    $config['allowed_types'] = $config_array['file_permission'];
                    $config['file_ext_tolower'] = TRUE;
                    $config['remove_spaces'] = TRUE;
                    $config['max_size'] = $config_array['file_size'];

                    $this->CI->load->library('upload',$config);
                    $this->CI->upload->initialize($config);
                    if($this->CI->upload->do_upload('attchment'))
                    {
                        
                        if($img_to_pdf && ($config_array['file_data']['type'][$i] == 'image/jpeg' || $config_array['file_data']['type'][$i] == 'image/jpg' || $config_array['file_data']['type'][$i] == 'image/png') ) {
                         
                            array_push($image_file_array,array('folder_path' => $config_array['file_upload_path'],'file_name' => $new_file_name));
                        }
                        elseif($img_to_pdf && $config_array['file_data']['type'][$i] == 'application/pdf')
                        {

                            $CI =& get_instance();

                            $CI->load->library('pdf_text');

                            $CI->pdf_text->watermark_pdf($config_array['file_upload_path'],$new_file_name,$config_array['education_id']);


                            $file_array[] = array(
                                'real_filename'=> $file_name,
                                'file_name'=> $new_file_name,
                                'type' => $config_array['type'],
                                $config_array['component_name'] => $config_array['file_id']
                            ); 

                        } 
                        else 
                        {
                            $file_array[] = array(
                                'real_filename'=> $file_name,
                                'file_name'=> $new_file_name,
                                'type' => $config_array['type'],
                                $config_array['component_name'] => $config_array['file_id']
                            ); 
                        }
                    }
                    else
                    {   
                        array_push($error_msgs,$this->CI->upload->display_errors('',''));
                    }
                }
            }
            if(!empty($image_file_array))
            {

                $CI =& get_instance();

                $CI->load->library('education_pdf');
                

                define('EDUCATION_REF_NO',$config_array['education_id']);

                $file_name_pdf = "education_".$config_array['education_id'].'_'.DATE(UPLOAD_FILE_DATE_FORMAT).'.pdf';

                $CI->education_pdf->generate_education_pdf($image_file_array,$config_array['file_upload_path'],$file_name_pdf);

                $file_array[] = array(
                                'real_filename'=> $file_name_pdf,
                                'file_name'=> $file_name_pdf,
                                'type' => $config_array['type'],
                                $config_array['component_name'] => $config_array['file_id']
                            ); 

                for ($i = 0; $i < count($image_file_array); $i++) {
            
                    if(file_exists($image_file_array[$i]['folder_path'].'/'.$image_file_array[$i]['file_name']))
                    {
                       
                       unlink($image_file_array[$i]['folder_path'].'/'.$image_file_array[$i]['file_name']);
                    }
             
                }   

            } 
        }catch (Exception $e) {
            log_message('error', 'File_upload_library::file_upload_multiple');
            log_message('error', $e->getMessage());
        }
        log_message('error', 'File Upload status');
        log_message('error', print_r($error_msgs, true));
        log_message('error', print_r($file_array, true));

        return array('success'  =>  $file_array,    'fail'  =>  $error_msgs);
    }

    public function createThumbnail($imagePathName,$newWidth = 300,$newHeight = 450) {
        $path       =  $imagePathName;
        $mime_info  = getimagesize($path);
        $mime       = $mime_info['mime'];

        $src_img    = $this->image_data($path, $mime);
        if($src_img===false) return false;

        $old_w = imageSX($src_img);
        $old_h = imageSY($src_img);

        $source_aspect_ratio = $old_w / $old_h;
        $desired_aspect_ratio = $newWidth / $newHeight;

        if ($source_aspect_ratio > $desired_aspect_ratio) {
            /*
             * Triggered when source image is wider
             */
            $thumb_h = $newHeight;
            $thumb_w = ( int ) ($newHeight * $source_aspect_ratio);
        } else {
            /*
             * Triggered otherwise (i.e. source image is similar or taller)
             */
            $thumb_w = $newWidth;
            $thumb_h = ( int ) ($newWidth / $source_aspect_ratio);
        }

        $dst_img     =   ImageCreateTrueColor($thumb_w,$thumb_h);

        $color = imagecolorallocatealpha($dst_img, 0, 0, 0, 127);
        imagefill($dst_img,0,0,$color);
        imagesavealpha($dst_img, true);

        imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $thumb_w, $thumb_h, $old_w, $old_h);

        if($this->is_crop_hard) {
            $x = ($thumb_w - $newWidth) / 2;
            $y = ($thumb_h - $newHeight) / 2;

            $tmp_img    = imagecreatetruecolor($newWidth, $newHeight);
            $color      = imagecolorallocatealpha($tmp_img, 0, 0, 0, 127);
            imagefill($tmp_img,0,0,$color);
            imagesavealpha($tmp_img, true);

            imagecopy($tmp_img, $dst_img, 0, 0, $x, $y, $newWidth, $newHeight);
            $dst_img = $tmp_img;
        }

        $new_thumb_loc = $path;
        $result = $this->save($dst_img, $new_thumb_loc, $mime);

        imagedestroy($dst_img);
        imagedestroy($src_img);
        return $result;
    }
}