<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Addressverify extends MY_User_Controller {
	
	function __construct() {
        parent::__construct();
        $this->load->model('addressver_model');
    }

    public function mobile_only()
    {
        $data['message'] = 'Please open this URL on a mobile phone.';
        $this->load->view('index',$data);
    }
    public function not_allowd()
    {
        $data['url'] = SITE_URL;
        $this->load->view('err404',$data);
    }

    public function index($address_id = false)
    {

        if($address_id != "")
        {
            $address_id = base64_decode($address_id);
           

            $details = $this->addressver_model->address_history(array('addrver.id' => $address_id));
            
          
            if(!empty($details))  {
                $details = $details[0];
                if($details['verification_status'] == 1) {
                    
                    $this->addressver_model->check_user_clicked('addrver',array('ip_address' => $this->input->ip_address(),'last_user_clicked' => date(DB_DATE_FORMAT)),array('id' => $details['id']));
                    
                    $data['details'] = $details;

                    $this->load->view('login_verify',$data);
                }
                else
                {
                    $message = 'Your details have already been verified.';
                    $this->success($message);
                }
            }
            else {
                $data['url'] = SITE_URL;
                $this->load->view('err404',$data);
            }
        }
        else
        {
            $data['url'] = SITE_URL;
            $this->load->view('err404');
        }
    }
    
    public function add_verification($address_id = false,$mobile_no = false)
    {
       
        if($address_id != "")
        { 
            $address_id = base64_decode($address_id);
            
            $details = $this->addressver_model->address_history(array('addrver.id' => $address_id));
         
            if(!empty($details))  {
                $details = $details[0];

                if($details['verification_status'] == 1) {

                     $data['latitude'] = $this->input->post('latitude');
                     $data['longitude'] = $this->input->post('longitude');
       
                   // $this->addressver_model->check_user_clicked('addrver',array('ip_address' => $this->input->ip_address(),'last_user_clicked' => date(DB_DATE_FORMAT)),array('id' => $details['id']));
                   
                    $data['details'] = $details;

                    $this->load->view('address_verify',$data);
                }
                else
                {
                    $message = 'Your details have already been verified.';
                    $this->success($message);
                }
            }
            else {
                $data['url'] = SITE_URL;
                $this->load->view('err404',$data);
            }
        }
        else
        {
            $data['url'] = SITE_URL;
            $this->load->view('err404');
        }
    }

	public function address_update_details()
    {
        ini_set('max_execution_time', 0);
        ini_set("upload_max_filesize","40M");
        ini_set("post_max_size","40M");
        
        $json_array['status'] = ERROR_CODE;
        $json_array['message'] = 'Direct access not allowed';

        if($this->input->is_ajax_request())
        {


            $this->form_validation->set_rules('address_edit',"Address",'required');
           // $this->form_validation->set_rules('latitude',"Latitude",'required');
           // $this->form_validation->set_rules('longitude',"Longitude",'required');
        
            $this->form_validation->set_rules('nature_of_residence',"Address Type",'required');
           
            $this->form_validation->set_rules('period_stay',"Stay From",'required');
            $this->form_validation->set_rules('period_to',"Stay To",'required');
            

            $this->form_validation->set_rules('verifier_name',"Verifier Name",'required');
            $this->form_validation->set_rules('signature',"Signature",'required');
            
            $this->form_validation->set_rules('latitude',"Latitude",'required');
            $this->form_validation->set_rules('longitude',"Longitude",'required');
         /*   $this->form_validation->set_rules('selfie_lat_long','Selfie Picture','required');
            $this->form_validation->set_rules('address_proof_lat_long',"Address Proof",'required');
            $this->form_validation->set_rules('location_picture_1_lat_long',"Location Picture 1",'required');
            $this->form_validation->set_rules('location_picture_2_lat_long',"Location Picture 2",'required');
            $this->form_validation->set_rules('house_pic_door_lat_long',"House Pictire",'required');*/
            // $this->form_validation->set_rules('signature_lat_long',"Signature",'required');
           
            if ($this->form_validation->run() == FALSE)
            {

                $json_array['status'] = ERROR_CODE;
                $json_array['message'] = validation_errors('','');
            }
            else
            {
                
                $frm_details = $this->input->post();
 
                $period_stay = isset($frm_details['period_stay']) ? $frm_details['period_stay'] : "";
                $period_to = isset($frm_details['period_to']) ? $frm_details['period_to'] : "";
               

                $details_arry = array('candidate_id' => $frm_details['cands_id'],
                                'address_id' => $frm_details['address_id'],
                                'address_edit' => $frm_details['address_edit'],
                                'latitude' => $frm_details['latitude'],
                                'longitude' => $frm_details['longitude'],
                                'nature_of_residence' => $frm_details['nature_of_residence'],
                                'period_stay' => $period_stay,
                                'period_to' => $period_to,
                                'verifier_name' => $frm_details['verifier_name'],
                                'relation_verifier_name' => $frm_details['relation_verifier_name'],
                                'candidate_remarks' => $frm_details['candidate_remarks'],
                                'selfie_lat_long' => $frm_details['selfie_lat_long'],
                                'address_proof_lat_long' => $frm_details['address_proof_lat_long'],
                                'location_picture_lat_long_1' => $frm_details['location_picture_1_lat_long'],
                                'location_picture_lat_long_2' => $frm_details['location_picture_2_lat_long'],
                                'house_pic_door_lat_long' => $frm_details['house_pic_door_lat_long'],
                                'signature_lat_long' => $frm_details['signature_lat_long'],
                                'created_on'    => date(DB_DATE_FORMAT),
                                'created_by'    => $frm_details['cands_id'],
                                'status'    => 1
                            );
                  
                $select_previous_images =  $this->addressver_model->select_address_dt('address_details',array('*'),array('address_id' => $frm_details['address_id'],'status'=> 2));

                if(!empty($select_previous_images))
                {
                    if(isset($select_previous_images[0]['selfie']))
                    {
                        if(!empty($select_previous_images[0]['selfie']))
                        {
                            if(file_exists(SITE_BASE_PATH . ADDRESS . "address_verification/".$select_previous_images[0]['selfie'])){
                              unlink(SITE_BASE_PATH . ADDRESS . "address_verification/".$select_previous_images[0]['selfie']);
                            }
                        }
                    }

                    if(isset($select_previous_images[0]['address_proof']))
                    {
                        if(!empty($select_previous_images[0]['address_proof']))
                        {
                            if(file_exists(SITE_BASE_PATH . ADDRESS . "address_verification/".$select_previous_images[0]['address_proof'])){
                              unlink(SITE_BASE_PATH . ADDRESS . "address_verification/".$select_previous_images[0]['address_proof']);
                            }
                        }
                    }

                    if(isset($select_previous_images[0]['location_picture_1']))
                    {
                        if(!empty($select_previous_images[0]['location_picture_1']))
                        {
                            if(file_exists(SITE_BASE_PATH . ADDRESS . "address_verification/".$select_previous_images[0]['location_picture_1'])){
                              unlink(SITE_BASE_PATH . ADDRESS . "address_verification/".$select_previous_images[0]['location_picture_1']);
                            }
                        }
                    }


                    if(isset($select_previous_images[0]['location_picture_2']))
                    {
                        if(!empty($select_previous_images[0]['location_picture_2']))
                        {
                            if(file_exists(SITE_BASE_PATH . ADDRESS . "address_verification/".$select_previous_images[0]['location_picture_2'])){
                              unlink(SITE_BASE_PATH . ADDRESS . "address_verification/".$select_previous_images[0]['location_picture_2']);
                            }
                        }
                    }


                    if(isset($select_previous_images[0]['house_pic_door']))
                    {
                        if(!empty($select_previous_images[0]['house_pic_door']))
                        {
                            if(file_exists(SITE_BASE_PATH . ADDRESS . "address_verification/".$select_previous_images[0]['house_pic_door'])){
                              unlink(SITE_BASE_PATH . ADDRESS . "address_verification/".$select_previous_images[0]['house_pic_door']);
                            }
                        }
                    }

                    if(isset($select_previous_images[0]['signature']))
                    {
                        if(!empty($select_previous_images[0]['signature']))
                        {
                            if(file_exists(SITE_BASE_PATH . ADDRESS . "address_verification/".$select_previous_images[0]['signature'])){
                              unlink(SITE_BASE_PATH . ADDRESS . "address_verification/".$select_previous_images[0]['signature']);
                            }
                        }
                    }
                }

                 if(!empty($_FILES)) {
                    
                   
                    $file_upload_path = SITE_BASE_PATH . ADDRESS .'address_verification';

                    if (!folder_exist($file_upload_path)) {
                        mkdir($file_upload_path, 0777);
                    } else if (!is_writable($file_upload_path)) {
                        array_push($error_msgs, 'Problem while uploading');
                    }

                
                    if (!empty($_FILES['selfie'])) {
                        $config_array = array('file_upload_path' => $file_upload_path,'file_permission' => 'jpeg|jpg|png','file_size' => BULK_UPLOAD_MAX_SIZE_MB*2000,'file_data' => $_FILES['selfie']);

                        $file_uplod_return = $this->file_upload_library->file_upload_for_address_verification($config_array);
                        if($file_uplod_return['status'] === TRUE) {
                            
                            $details_arry['selfie'] = $file_uplod_return['file_name'];
                           // $this->compress_image($file_upload_path.'/'. $file_uplod_return['file_name'], $file_upload_path.'/'.$file_uplod_return['file_name'], 80);

                        }else {
                            $json_array['file_error'] = $file_uplod_return['message'];
                        }
                    }

                    if (!empty($_FILES['address_proof'])) {
                        $config_array = array('file_upload_path' => $file_upload_path,'file_permission' => 'jpeg|jpg|png','file_size' => BULK_UPLOAD_MAX_SIZE_MB*2000,'file_data' => $_FILES['address_proof']);
                        $file_uplod_return = $this->file_upload_library->file_upload_for_address_verification($config_array);
                        if($file_uplod_return['status'] === TRUE) {

                            $details_arry['address_proof'] = $file_uplod_return['file_name'];
                           // $this->compress_image($file_upload_path.'/'. $file_uplod_return['file_name'], $file_upload_path.'/'.$file_uplod_return['file_name'], 80);


                        }else {
                            $json_array['file_error'] = $file_uplod_return['message'];
                        }
                    }

                    if (!empty($_FILES['location_picture_1'])) {
                        $config_array = array('file_upload_path' => $file_upload_path,'file_permission' => 'jpeg|jpg|png','file_size' => BULK_UPLOAD_MAX_SIZE_MB*2000,'file_data' => $_FILES['location_picture_1']);
                        $file_uplod_return = $this->file_upload_library->file_upload_for_address_verification($config_array);
                        if($file_uplod_return['status'] === TRUE) {

                            $details_arry['location_picture_1'] = $file_uplod_return['file_name'];
                           // $this->compress_image($file_upload_path.'/'. $file_uplod_return['file_name'], $file_upload_path.'/'.$file_uplod_return['file_name'], 80);

                        }else {
                            $json_array['file_error'] = $file_uplod_return['message'];
                        }
                    }

                    if (!empty($_FILES['location_picture_2'])) {
                        $config_array = array('file_upload_path' => $file_upload_path,'file_permission' => 'jpeg|jpg|png','file_size' => BULK_UPLOAD_MAX_SIZE_MB*2000,'file_data' => $_FILES['location_picture_2']);
                        $file_uplod_return = $this->file_upload_library->file_upload_for_address_verification($config_array);
                        if($file_uplod_return['status'] === TRUE) {

                            $details_arry['location_picture_2'] = $file_uplod_return['file_name'];
                           // $this->compress_image($file_upload_path.'/'. $file_uplod_return['file_name'], $file_upload_path.'/'.$file_uplod_return['file_name'], 80);
                        }else {
                            $json_array['file_error'] = $file_uplod_return['message'];
                        }
                    }


                    if (!empty($_FILES['house_pic_door'])) {
                        $config_array = array('file_upload_path' => $file_upload_path,'file_permission' => 'jpeg|jpg|png','file_size' => BULK_UPLOAD_MAX_SIZE_MB*2000,'file_data' => $_FILES['house_pic_door']);
                        $file_uplod_return = $this->file_upload_library->file_upload_for_address_verification($config_array);
                        if($file_uplod_return['status'] === TRUE) {

                           // $this->rotate_up_images($file_upload_path.$file_uplod_return['file_name']);
                            
                            $details_arry['house_pic_door'] = $file_uplod_return['file_name'];
                           // $this->compress_image($file_upload_path.'/'. $file_uplod_return['file_name'], $file_upload_path.'/'.$file_uplod_return['file_name'], 80);
                        }else {
                            $json_array['file_error'] = $file_uplod_return['message'];
                        }
                    }
                    
                    $file_upload_path1 = SITE_BASE_PATH . ADDRESS .'address_verification/';

                    $signature_png = $file_upload_path1.$frm_details['address_id'].'_signature.png';

                    $converted = $this->base64_to_jpeg($frm_details['signature'],$signature_png);
                    $details_arry['signature'] = '';
                    if($converted) {
                        $details_arry['signature'] = $frm_details['address_id'].'_signature.png';

                    }
                    
                    // if (!empty($_FILES['signature'])) {
                    //     $config_array = array('file_upload_path' => $file_upload_path,'file_permission' => 'jpeg|jpg|png','file_size' => BULK_UPLOAD_MAX_SIZE_MB*2000,'file_data' => $_FILES['signature']);
                    //     $file_uplod_return = $this->file_upload->file_uplod($config_array);
                    //     if($file_uplod_return['status'] === TRUE) {

                    //         $this->rotate_up_images($file_upload_path.$file_uplod_return['file_name']);
                            
                    //         $details_arry['signature'] = $file_uplod_return['file_name'];
                    //     }else {
                    //         $json_array['file_error'] = $file_uplod_return['message'];
                    //     }
                    // }
              
                   
                    $insert_id = $this->addressver_model->save_vendor_details_costing('address_details',$details_arry);

                    if($insert_id)
                    {
                        $insert_id = $this->addressver_model->save_vendor_details('addrver',array('verification_status' => 2),array('id' => $frm_details['address_id']));
                        $json_array['status'] = SUCCESS_CODE;
                        $json_array['message'] = 'Record inserted Successfully';
                        //$this->success( $json_array['message']);
                        $json_array['redirect'] = SITE_URL.'addressverify/success';
                    }
                    else
                    {
                        $json_array['status'] = ERROR_CODE;
                        $json_array['message'] = 'Unable to store in Database,Please try again';
                    }
                }
                else
                {
                    $json_array['status'] = ERROR_CODE;
                    $json_array['message'] = 'Please upload file';
                }
            }
        }
        else
        {

        }
        echo_json($json_array);
    }


    

   /* protected function rotate_up_images($filename)
    {
        exec("convert -quality 30% ".$filename." ".$filename." ");
        // $source = imagecreatefromjpeg($filename);
        // $rotate = imagerotate($source, 270, 0);
        // imagejpeg($rotate, $filename);
    }*/

    protected function base64_to_jpeg($base64_string, $output_file) {
      
        
        $base64_string = str_replace('[removed]', '', $base64_string);
        $ifp = fopen( $output_file, 'wb' ); 

        $data = explode( ',', $base64_string );

        fwrite( $ifp, base64_decode( $base64_string ) );

        fclose( $ifp ); 

        return $output_file; 
       
    }

 
    public function success($message = false)
    {   
        $this->logout_cands();
        $data['message'] = ($message != "") ? $message : 'Thank you. Your details have been submitted.';
        $this->load->view('index_massage',$data);
    }

    public  function compress_image($source, $destination, $quality) {
      
        $info = pathinfo($source);
     
        if ($info['extension'] == 'jpeg') 
        {
            $image = imagecreatefromjpeg($source);
        }
        elseif ($info['extension'] == 'jpg') 
        {
            $image = imagecreatefromjpeg($source);
        }
        elseif ($info['extension'] == 'gif') 
        {
            $image = imagecreatefromgif($source);
        }

        elseif ($info['extension'] == 'png') 
        {
            $image = imagecreatefrompng($source);
        }

        imagejpeg($image, $destination, $quality);

        return $destination;
    }
}
?>