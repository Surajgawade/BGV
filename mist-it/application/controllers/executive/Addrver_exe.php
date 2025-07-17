<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Addrver_exe extends MY_Executive_Cotroller
{    
    function __construct()
    {
        parent::__construct();
        if(!$this->is_vendor_executive_logged_in())
        {
            redirect('executive/login');
            exit();
        }
        $this->load->model(array('executive/addrver_executive_model'));
    }

    public function addrver_exe_current()
    {   
        $data['header_title'] = "Address Cases View";

         
        $this->load->view('executive/header',$data);
        
        $this->load->view('executive/addrver_exe_list_current');

        $this->load->view('executive/footer');
    }

    public function addrver_exe_wip()
    {   
        $data['header_title'] = "Address Cases View";

         
        $this->load->view('executive/header',$data);
        
        $this->load->view('executive/addrver_exe_list_wip');

        $this->load->view('executive/footer');
    }

    public function addrver_insufficiency()
    {   
        $data['header_title'] = "Address Cases View";

         
        $this->load->view('executive/header',$data);
        
        $this->load->view('executive/addrver_exe_list_insufficiency');

        $this->load->view('executive/footer');
    }

    public function addrver_closed()
    {   
        $data['header_title'] = "Address Cases View";

         
        $this->load->view('executive/header',$data);
        
        $this->load->view('executive/addrver_exe_list_closed');

        $this->load->view('executive/footer');
    }

    public function address_view_datatable_current_executive()
    {
    
        $params = $add_candidates = $data_arry = $columns = array();
         
        $params = $_REQUEST;


        $columns = array('addrver_id.id','candidates_info.ClientRefNumber','candidates_info.cmp_ref_no','company_database.coname','candidates_info.CandidateName','candidates_info.caserecddate','verfstatus','city','encry_id','address','pincode','state','check_closure_date','add_com_ref','iniated_date');
 
        $where_arry = array();

        $lists = $this->addrver_executive_model->address_case_list_executive(array('address_vendor_log.vendor_id' => $this->vendor_executive_info['vendors_id'],'view_vendor_master_log.has_case_id' => $this->vendor_executive_info['id'],'view_vendor_master_log.status =' => 1, 'DATE(`view_vendor_master_log`.`has_assigned_on`)' => date("Y-m-d"),'component' => 'addrver'),$params,$columns);

        $totalRecords = count($this->addrver_executive_model->address_case_list_executive_count(array('address_vendor_log.vendor_id' => $this->vendor_executive_info['vendors_id'],'view_vendor_master_log.has_case_id' => $this->vendor_executive_info['id'],'view_vendor_master_log.status =' => 1, 'DATE(`view_vendor_master_log`.`has_assigned_on`)' => date("Y-m-d"),'component' => 'addrver'),$params,$columns));
        
    
        $x = 0;
        
        foreach ($lists as $list)
        {
           $data_arry[$x]['sr_no'] = $x + 1;
        
            $data_arry[$x]['created_on'] = convert_db_to_display_date($list['has_assigned_on'],DB_DATE_FORMAT,DISPLAY_DATE_FORMAT12);
           
            $data_arry[$x]['CandidateName'] = ucwords($list['CandidateName']);
            $data_arry[$x]['address'] = ucwords($list['address']);
            $data_arry[$x]['city'] = ucwords($list['city']);
            $data_arry[$x]['ID'] = $list['add_com_ref'];
            $data_arry[$x]['action'] = '<button  data-url ="' . VENDOR_EXECUTIVE_SITE_URL . 'addrver_exe/get_mobile_no/' . base64_encode($list['id']) .'"  class="btn showviewcallModel"> <i class="fas fa-phone fa-text-height fa-2x" aria-hidden="true" style="color:black"></i>  </button>&nbsp;<button  data-url ="' . VENDOR_EXECUTIVE_SITE_URL . 'addrver_exe/address_result_transaction_details/' . base64_encode($list['id']) .'/'. base64_encode('current').'"  class="btn showAddResultModel"> <i class="fas fa-plus fa-text-height fa-2x" aria-hidden="true"  style="color:black"></i> </button>';
         
            $data_arry[$x]['clientname'] = ucwords($list['clientname']);
            $data_arry[$x]['entity_name'] = ucwords($list['entity_name']);
         
                 
          $x++;
        }

        $json_data = array(
            "draw"            => intval( $params['draw'] ),
            "recordsTotal"    => intval( $totalRecords ),
            "recordsFiltered" => intval($totalRecords),
            "data"            => $data_arry
            );

        echo_json($json_data);
    }


    public function address_view_datatable_wip_executive()
    {
    
        $params = $add_candidates = $data_arry = $columns = array();
         
        $params = $_REQUEST;


        $columns = array('addrver_id.id','candidates_info.ClientRefNumber','candidates_info.cmp_ref_no','company_database.coname','candidates_info.CandidateName','candidates_info.caserecddate','verfstatus','city','encry_id','address','pincode','state','check_closure_date','add_com_ref','iniated_date');
 
        $where_arry = array();

        $lists = $this->addrver_executive_model->address_case_list_executive(array('address_vendor_log.vendor_id' => $this->vendor_executive_info['vendors_id'],'view_vendor_master_log.has_case_id' => $this->vendor_executive_info['id'],'view_vendor_master_log.status =' => 1,'view_vendor_master_log.final_status =' => 'wip','component' => 'addrver'),$params,$columns);

        $totalRecords = count($this->addrver_executive_model->address_case_list_executive_count(array('address_vendor_log.vendor_id' => $this->vendor_executive_info['vendors_id'],'view_vendor_master_log.has_case_id' => $this->vendor_executive_info['id'],'view_vendor_master_log.status =' => 1,'view_vendor_master_log.final_status =' => 'wip','component' => 'addrver'),$params,$columns));
        
    
        $x = 0;
        
        foreach ($lists as $list)
        {
           $data_arry[$x]['sr_no'] = $x + 1;
        
            $data_arry[$x]['created_on'] = convert_db_to_display_date($list['has_assigned_on'],DB_DATE_FORMAT,DISPLAY_DATE_FORMAT12);
           
            $data_arry[$x]['CandidateName'] = ucwords($list['CandidateName']);
            $data_arry[$x]['address'] = ucwords($list['address']);
            $data_arry[$x]['city'] = ucwords($list['city']);
            $data_arry[$x]['ID'] = $list['add_com_ref'];
            $data_arry[$x]['action'] = '<button  data-url ="' . VENDOR_EXECUTIVE_SITE_URL . 'addrver_exe/get_mobile_no/' . base64_encode($list['id']) .'"  class="btn showviewcallModel"> <i class="fas fa-phone fa-text-height fa-2x" aria-hidden="true" style="color:black"></i>  </button>&nbsp;<button  data-url ="' . VENDOR_EXECUTIVE_SITE_URL . 'addrver_exe/address_result_transaction_details/' . base64_encode($list['id']) .'/'. base64_encode('wip').'"  class="btn showAddResultModel"> <i class="fas fa-plus fa-text-height fa-2x" aria-hidden="true"  style="color:black"></i> </button>';
         
            $data_arry[$x]['clientname'] = ucwords($list['clientname']);
            $data_arry[$x]['entity_name'] = ucwords($list['entity_name']);
         
                 
          $x++;
        }

        $json_data = array(
            "draw"            => intval( $params['draw'] ),
            "recordsTotal"    => intval( $totalRecords ),
            "recordsFiltered" => intval($totalRecords),
            "data"            => $data_arry
            );

        echo_json($json_data);
    }


    public function address_view_datatable_insufficiency_executive()
    {
    
        $params = $add_candidates = $data_arry = $columns = array();
         
        $params = $_REQUEST;


        $columns = array('addrver_id.id','candidates_info.ClientRefNumber','candidates_info.cmp_ref_no','company_database.coname','candidates_info.CandidateName','candidates_info.caserecddate','verfstatus','city','encry_id','address','pincode','state','check_closure_date','add_com_ref','iniated_date');
 
        $where_arry = array();

        $lists = $this->addrver_executive_model->address_case_list_insufficiency_executive(array('address_vendor_log.vendor_id' => $this->vendor_executive_info['vendors_id'],'view_vendor_master_log.has_case_id' => $this->vendor_executive_info['id'],'view_vendor_master_log.status =' => 1,'component' => 'addrver'),$params,$columns);

        $totalRecords = count($this->addrver_executive_model->address_case_list_insufficiency_executive_count(array('address_vendor_log.vendor_id' => $this->vendor_executive_info['vendors_id'],'view_vendor_master_log.has_case_id' => $this->vendor_executive_info['id'],'view_vendor_master_log.status =' => 1,'component' => 'addrver'),$params,$columns));
        
    
        $x = 0;
        
        foreach ($lists as $list)
        {
           $data_arry[$x]['sr_no'] = $x + 1;
        
            $data_arry[$x]['created_on'] = convert_db_to_display_date($list['has_assigned_on'],DB_DATE_FORMAT,DISPLAY_DATE_FORMAT12);
           
            $data_arry[$x]['CandidateName'] = ucwords($list['CandidateName']);
            $data_arry[$x]['address'] = ucwords($list['address']);
            $data_arry[$x]['city'] = ucwords($list['city']);
            $data_arry[$x]['ID'] = $list['add_com_ref'];
            $data_arry[$x]['action'] = '<button  data-url ="' . VENDOR_EXECUTIVE_SITE_URL . 'addrver_exe/get_mobile_no/' . base64_encode($list['id']) .'"  class="btn showviewcallModel">  <i class="fas fa-phone fa-text-height fa-2x" aria-hidden="true" style="color:black"></i>  </button>&nbsp;<button  data-url ="' . VENDOR_EXECUTIVE_SITE_URL . 'addrver_exe/address_result_transaction_details/' . base64_encode($list['id']) .'/'.base64_encode('insufficiency').'"  class="btn showAddResultModel"> <i class="fas fa-plus fa-text-height fa-2x" aria-hidden="true" style="color:black"></i></button>';
         
            $data_arry[$x]['clientname'] = ucwords($list['clientname']);
            $data_arry[$x]['entity_name'] = ucwords($list['entity_name']);
         
                 
          $x++;
        }

        $json_data = array(
            "draw"            => intval( $params['draw'] ),
            "recordsTotal"    => intval( $totalRecords ),
            "recordsFiltered" => intval($totalRecords),
            "data"            => $data_arry
            );

        echo_json($json_data);
    }
   
    public function address_view_datatable_closed_executive()
    {
    
        $params = $add_candidates = $data_arry = $columns = array();
         
        $params = $_REQUEST;


        $columns = array('addrver_id.id','candidates_info.ClientRefNumber','candidates_info.cmp_ref_no','company_database.coname','candidates_info.CandidateName','candidates_info.caserecddate','verfstatus','city','encry_id','address','pincode','state','check_closure_date','add_com_ref','iniated_date');
 
        $where_arry = array();

        $lists = $this->addrver_executive_model->address_case_list_closed_executive(array('address_vendor_log.vendor_id' => $this->vendor_executive_info['vendors_id'],'view_vendor_master_log.has_case_id' => $this->vendor_executive_info['id'],'view_vendor_master_log.status =' => 1,'component' => 'addrver'),$params,$columns);

        $totalRecords = count($this->addrver_executive_model->address_case_list_closed_executive_count(array('address_vendor_log.vendor_id' => $this->vendor_executive_info['vendors_id'],'view_vendor_master_log.has_case_id' => $this->vendor_executive_info['id'],'view_vendor_master_log.status =' => 1,'component' => 'addrver'),$params,$columns));
        
    
        $x = 0;
        
        foreach ($lists as $list)
        {
           $data_arry[$x]['sr_no'] = $x + 1;
        
            $data_arry[$x]['created_on'] = convert_db_to_display_date($list['has_assigned_on'],DB_DATE_FORMAT,DISPLAY_DATE_FORMAT12);
           
            $data_arry[$x]['CandidateName'] = ucwords($list['CandidateName']);
            $data_arry[$x]['address'] = ucwords($list['address']);
            $data_arry[$x]['city'] = ucwords($list['city']);
            $data_arry[$x]['ID'] = $list['add_com_ref'];
           
            $data_arry[$x]['action'] = '<button  data-url ="' . VENDOR_EXECUTIVE_SITE_URL . 'addrver_exe/get_mobile_no/' . base64_encode($list['id']) .'"  class="btn showviewcallModel"> <i class="fas fa-phone fa-text-height fa-2x" aria-hidden="true" style="color:black"></i> </button>&nbsp;&nbsp;<button  data-url ="' . VENDOR_EXECUTIVE_SITE_URL . 'addrver_exe/address_result_transaction_details/' . base64_encode($list['id']) .'/'.base64_encode('closed').'"  class="btn showAddResultModel">  <i class="fas fa-plus fa-text-height fa-2x" aria-hidden="true" style="color:black"></i> </button>';
         
            $data_arry[$x]['clientname'] = ucwords($list['clientname']);
            $data_arry[$x]['entity_name'] = ucwords($list['entity_name']);
         
                 
          $x++;
        }

        $json_data = array(
            "draw"            => intval( $params['draw'] ),
            "recordsTotal"    => intval( $totalRecords ),
            "recordsFiltered" => intval($totalRecords),
            "data"            => $data_arry
            );

        echo_json($json_data);
    }


    public function address_result_transaction_details($transaction_id,$status)
    {


        $details = $this->addrver_executive_model->select_transaction_details_for_form_generation(array('view_vendor_master_log.id' => base64_decode($transaction_id),'component' => 'addrver','view_vendor_master_log.status' => 1));

        if ($transaction_id && !empty($details)) {
          
           $data['details'] =  $details[0];
           $data['status_redirect'] =  base64_decode($status);

           $this->load->view('executive/header', $data);  
           $this->load->view('executive/address_form_generation_details');
            $this->load->view('executive/footer');

        } else {
            echo "<h4>Record Not Found</h4>";
        }

    }


    public function vendor_submit_details()
    {
        ini_set('max_execution_time', 0);
        ini_set("upload_max_filesize","40M");
        ini_set("post_max_size","40M");
        
        $json_array['status'] = ERROR_CODE;
        $json_array['message'] = 'Direct access not allowed';

        if($this->input->is_ajax_request())
        {
            $frm_details = $this->input->post();

            $this->form_validation->set_rules('transaction_id',"Transaction ID",'required');
  
            $this->form_validation->set_rules('status',"Status",'required');
        
            $this->form_validation->set_rules('remarks',"Remarks",'required');
          
      /*      if($frm_details['status'] ==  "clear")
            {
                $this->form_validation->set_rules('address_action',"Address Action",'required');

                $this->form_validation->set_rules('stay_from_action',"Stay From Action",'required');

                $this->form_validation->set_rules('stay_to_action',"Stay To Action",'required');

                $this->form_validation->set_rules('mode_of_verification',"Address Action",'required');

                $this->form_validation->set_rules('addr_proof_collected',"Address Proof Collection",'required');

                $this->form_validation->set_rules('resident_status',"Resident Status",'required');

                $this->form_validation->set_rules('verified_by',"Verified By",'required');

                $this->form_validation->set_rules('signature',"Signature",'required');

            }
           */
          
           
            if ($this->form_validation->run() == FALSE)
            {

                $json_array['status'] = ERROR_CODE;
                $json_array['message'] = validation_errors('','');
            }
            else
            {
                
                $frm_details = $this->input->post();

                $field_array = array('vendor_actual_status' => $frm_details['status'],'final_status' => $frm_details['status'],'vendor_remark' => $frm_details['remarks']);


                if($frm_details['status'] == "candidate shifted" || $frm_details['status'] == "unable to verify" || $frm_details['status'] == "denied verification"  || $frm_details['status'] == "resigned" || $frm_details['status'] == "candidate not responding" || $frm_details['status'] == "clear")
                {
                   $field_array['modified_on']  = date(DB_DATE_FORMAT);
                }
                
                $result= $this->addrver_executive_model->save('view_vendor_master_log',array_map('strtolower',$field_array),array('trasaction_id' => $frm_details['transaction_id']));

                
                if($frm_details['status'] ==  "clear")
                {
                   /* $details_arry = array('transaction_id' => $frm_details['t_id'],
                                'latitude' => $frm_details['latitude'],
                                'longitude' => $frm_details['longitude'],
                                'address_action' => $frm_details['address_action'],
                                'stay_from_action' => $frm_details['stay_from_action'],
                                'saty_to_action' => $frm_details['stay_to_action'],
                                'street_address' => $frm_details['address'],
                                'stay_from' => $frm_details['stay_from'],
                                'stay_to' => $frm_details['stay_to'],
                                'res_street_address' => $frm_details['res_address'],
                                'res_stay_from' => $frm_details['res_stay_from'],
                                'res_stay_to' => $frm_details['res_stay_to'],
                                'mode_of_verification' => $frm_details['mode_of_verification'],
                                'resident_status' => $frm_details['resident_status'],
                                'landmark' => $frm_details['landmark'],
                                'verified_by' => $frm_details['verified_by'],
                                'neighbour_1' => $frm_details['neighbour_1'],
                                'neighbour_details_1' => $frm_details['neighbour_details_1'],
                                'neighbour_2' => $frm_details['neighbour_2'],
                                'neighbour_details_2' => $frm_details['neighbour_details_2'],
                                'addr_proof_collected' => $frm_details['addr_proof_collected'],
                                'address_proof_front_lat_long' => $frm_details['address_proof_front_lat_long'],
                                'address_proof_back_lat_long' => $frm_details['address_proof_back_lat_long'],
                                'address_proof_lat_long' => $frm_details['address_proof_lat_long'],
                                'house_pic_door_lat_long' => $frm_details['house_pic_door_lat_long'],
                                'location_pictures_1_lat_long' => $frm_details['location_picture_1_lat_long'],
                                'location_pictures_2_lat_long' => $frm_details['location_picture_2_lat_long'],
                                'location_pictures_3_lat_long' => $frm_details['location_picture_3_lat_long'],
                                'signature_lat_long' => $frm_details['signature_lat_long'],
                                'created_on'    => date(DB_DATE_FORMAT),
                                'created_by'    => $this->vendor_executive_info['id'],
                                'status'    => 1
                            );

                    $stay_from_verification =  ($frm_details['stay_from_action'] == 'yes') ? $frm_details['stay_from'] : $frm_details['res_stay_from'];

                    $stay_to_verification =  ($frm_details['stay_to_action'] == 'yes') ? $frm_details['stay_to'] : $frm_details['res_stay_to'];

                    $details_arry_pdf = array('add_com_ref' => $frm_details['add_com_ref'],
                                'iniated_date' => $frm_details['iniated_date'],
                                'date_of_visit' => date('d-m-Y'),
                                'status' => $frm_details['status'],
                                'candidate_name' => $frm_details['CandidateName'],
                                'candidate_contact' => $frm_details['CandidatesContactNumber'],
                                'provided_address' => $frm_details['address'],
                                'address_action' =>($frm_details['address_action'] == 'yes') ? '' : ' - Not Verified',

                                'period_stay_from' => $stay_from_verification.' to '.$stay_to_verification,
                                
                                'resident_status' => $frm_details['resident_status'],
                                'mode_of_verification' => $frm_details['mode_of_verification'],
                                'addr_proof_collected' => $frm_details['addr_proof_collected'],
                                'verified_by' => $frm_details['verified_by'],
                                'executive_name' => $this->vendor_executive_info['first_name'].' '.$this->vendor_executive_info['last_name'],
                                'remarks' => $frm_details['remarks'],
                                'transaction_id' => $frm_details['transaction_id']
                            );

                       */

                    if(!empty($_FILES)) {

                        $folder_name = "vendor_file";
                        $file_upload_path = SITE_BASE_PATH.ADDRESS.$folder_name;
                        if(!folder_exist($file_upload_path)) {
                            mkdir($file_upload_path,0777);
                        }else if(!is_writable($file_upload_path)) {
                            array_push($error_msgs,'Problem while uploading');
                        }
                   
                        $config_array = array('file_upload_path' => $file_upload_path,
                                          'file_permission' => 'jpeg|jpg|png',
                                          'file_size' => BULK_UPLOAD_MAX_SIZE_MB*20000,
                                          'view_venor_master_log_id' =>  $frm_details['t_id'],
                                          'component_tbl_id' => 1,
                                          'status' => 1 );
            
                        
                    
                        if (!empty($_FILES['address_proof_front'])) {
                            $config_array = array('file_upload_path' => $file_upload_path,'file_permission' => 'jpeg|jpg|png','file_size' => BULK_UPLOAD_MAX_SIZE_MB*2000,'file_data' => $_FILES['address_proof_front']);

                            $file_uplod_return = $this->file_upload_library->file_upload_for_address_verification($config_array);
                            if($file_uplod_return['status'] === TRUE) {
                                
                              
                               // $this->watermarkImage($file_uplod_return['file_name'],$frm_details['address_proof_front_lat_long']);
                               
                                $field_array_address_front = array('file_name' => $file_uplod_return['file_name'],
                                          'real_filename' => $file_uplod_return['file_name'],
                                          'view_venor_master_log_id' =>  $frm_details['t_id'],
                                          'component_tbl_id' => 1,
                                          'status' => 1 );
                                
                                $insert_address_front = $this->addrver_executive_model->save('view_vendor_master_log_file',array_map('strtolower',$field_array_address_front));

                                $details_arry['address_proof_front'] = $file_uplod_return['file_name'];

                            }else {
                                $json_array['file_error'] = $file_uplod_return['message'];
                            }
                        }

                        if (!empty($_FILES['address_proof_back'])) {
                            $config_array = array('file_upload_path' => $file_upload_path,'file_permission' => 'jpeg|jpg|png','file_size' => BULK_UPLOAD_MAX_SIZE_MB*2000,'file_data' => $_FILES['address_proof_back']);
                            $file_uplod_return = $this->file_upload_library->file_upload_for_address_verification($config_array);
                            if($file_uplod_return['status'] === TRUE) {


                              //  $this->watermarkImage($file_uplod_return['file_name'],$frm_details['address_proof_back_lat_long']);

                                $field_array_address_back = array('file_name' => $file_uplod_return['file_name'],
                                          'real_filename' => $file_uplod_return['file_name'],
                                          'view_venor_master_log_id' =>  $frm_details['t_id'],
                                          'component_tbl_id' => 1,
                                          'status' => 1 );
                                
                                $insert_address_back = $this->addrver_executive_model->save('view_vendor_master_log_file',array_map('strtolower',$field_array_address_back));


                                $details_arry['address_proof_back'] = $file_uplod_return['file_name'];
                            }else {
                                $json_array['file_error'] = $file_uplod_return['message'];
                            }
                        }

                        if (!empty($_FILES['address_forms'])) {
                            $config_array = array('file_upload_path' => $file_upload_path,'file_permission' => 'jpeg|jpg|png','file_size' => BULK_UPLOAD_MAX_SIZE_MB*2000,'file_data' => $_FILES['address_forms']);
                            $file_uplod_return = $this->file_upload_library->file_upload_for_address_verification($config_array);
                            if($file_uplod_return['status'] === TRUE) {


                                $field_array_address_forms = array('file_name' => $file_uplod_return['file_name'],
                                          'real_filename' => $file_uplod_return['file_name'],
                                          'view_venor_master_log_id' =>  $frm_details['t_id'],
                                          'component_tbl_id' => 1,
                                          'status' => 1 );
                                
                                $insert_address_forms = $this->addrver_executive_model->save('view_vendor_master_log_file',array_map('strtolower',$field_array_address_forms));


                                $details_arry['address_forms'] = $file_uplod_return['file_name'];
                            }else {
                                $json_array['file_error'] = $file_uplod_return['message'];
                            }
                        }


                      /*  if (!empty($_FILES['address_proof'])) {
                            $config_array = array('file_upload_path' => $file_upload_path,'file_permission' => 'jpeg|jpg|png','file_size' => BULK_UPLOAD_MAX_SIZE_MB*2000,'file_data' => $_FILES['address_proof']);
                            $file_uplod_return = $this->file_upload_library->file_upload_for_address_verification($config_array);
                            if($file_uplod_return['status'] === TRUE) {


                                $this->watermarkImage($file_uplod_return['file_name'],$frm_details['address_proof_lat_long']);

                                $field_array_address_proof = array('file_name' => $file_uplod_return['file_name'],
                                          'real_filename' => $file_uplod_return['file_name'],
                                          'view_venor_master_log_id' =>  $frm_details['t_id'],
                                          'component_tbl_id' => 1,
                                          'status' => 1 );

                                $insert_address_back = $this->addrver_executive_model->save('view_vendor_master_log_file',array_map('strtolower', $field_array_address_proof));


                                $details_arry['address_proof'] = $file_uplod_return['file_name'];
                            }else {
                                $json_array['file_error'] = $file_uplod_return['message'];
                            }
                        }*/



                        if (!empty($_FILES['house_pic_door'])) {
                            $config_array = array('file_upload_path' => $file_upload_path,'file_permission' => 'jpeg|jpg|png','file_size' => BULK_UPLOAD_MAX_SIZE_MB*2000,'file_data' => $_FILES['house_pic_door']);
                            $file_uplod_return = $this->file_upload_library->file_upload_for_address_verification($config_array);
                            if($file_uplod_return['status'] === TRUE) {


                                $this->watermarkImage($file_uplod_return['file_name'],$frm_details['house_pic_door_lat_long']);

                                $field_array_house_door = array('file_name' => $file_uplod_return['file_name'],
                                          'real_filename' => $file_uplod_return['file_name'],
                                          'view_venor_master_log_id' =>  $frm_details['t_id'],
                                          'component_tbl_id' => 1,
                                          'status' => 1 );

                                $insert_address_house = $this->addrver_executive_model->save('view_vendor_master_log_file',array_map('strtolower', $field_array_house_door));


                                $details_arry['house_pic_door'] = $file_uplod_return['file_name'];
                            }else {
                                $json_array['file_error'] = $file_uplod_return['message'];
                            }
                        }


                        if (!empty($_FILES['location_picture_1'])) {
                            $config_array = array('file_upload_path' => $file_upload_path,'file_permission' => 'jpeg|jpg|png','file_size' => BULK_UPLOAD_MAX_SIZE_MB*2000,'file_data' => $_FILES['location_picture_1']);
                            $file_uplod_return = $this->file_upload_library->file_upload_for_address_verification($config_array);
                            if($file_uplod_return['status'] === TRUE) {


                                $this->watermarkImage($file_uplod_return['file_name'],$frm_details['location_picture_1_lat_long']);


                                $field_array_location_picture_1 = array('file_name' => $file_uplod_return['file_name'],
                                          'real_filename' => $file_uplod_return['file_name'],
                                          'view_venor_master_log_id' =>  $frm_details['t_id'],
                                          'component_tbl_id' => 1,
                                          'status' => 1 );

                                $insert_address_location_1 = $this->addrver_executive_model->save('view_vendor_master_log_file',array_map('strtolower', $field_array_location_picture_1));


                                $details_arry['location_picture_1'] = $file_uplod_return['file_name'];
                            }else {
                                $json_array['file_error'] = $file_uplod_return['message'];
                            }
                        }

                        if (!empty($_FILES['location_picture_2'])) {
                            $config_array = array('file_upload_path' => $file_upload_path,'file_permission' => 'jpeg|jpg|png','file_size' => BULK_UPLOAD_MAX_SIZE_MB*2000,'file_data' => $_FILES['location_picture_2']);
                            $file_uplod_return = $this->file_upload_library->file_upload_for_address_verification($config_array);
                            if($file_uplod_return['status'] === TRUE) {


                                $this->watermarkImage($file_uplod_return['file_name'],$frm_details['location_picture_2_lat_long']);

                                $field_array_location_picture_2 = array('file_name' => $file_uplod_return['file_name'],
                                          'real_filename' => $file_uplod_return['file_name'],
                                          'view_venor_master_log_id' =>  $frm_details['t_id'],
                                          'component_tbl_id' => 1,
                                          'status' => 1 );

                                $insert_address_location_2 = $this->addrver_executive_model->save('view_vendor_master_log_file',array_map('strtolower', $field_array_location_picture_2));

                                $details_arry['location_picture_2'] = $file_uplod_return['file_name'];
                            }else {
                                $json_array['file_error'] = $file_uplod_return['message'];
                            }
                        }


                        
                        if (!empty($_FILES['location_picture_3'])) {
                            $config_array = array('file_upload_path' => $file_upload_path,'file_permission' => 'jpeg|jpg|png','file_size' => BULK_UPLOAD_MAX_SIZE_MB*2000,'file_data' => $_FILES['location_picture_3'],$frm_details['location_picture_3_lat_long']);
                            $file_uplod_return = $this->file_upload_library->file_upload_for_address_verification($config_array);
                            if($file_uplod_return['status'] === TRUE) {


                                $this->watermarkImage($file_uplod_return['file_name'],$frm_details['location_picture_3_lat_long']);

                                $field_array_location_picture_3 = array('file_name' => $file_uplod_return['file_name'],
                                          'real_filename' => $file_uplod_return['file_name'],
                                          'view_venor_master_log_id' =>  $frm_details['t_id'],
                                          'component_tbl_id' => 1,
                                          'status' => 1 );

                                $insert_address_location_3 = $this->addrver_executive_model->save('view_vendor_master_log_file',array_map('strtolower', $field_array_location_picture_3));


                                $details_arry['location_picture_3'] = $file_uplod_return['file_name'];
                            }else {
                                $json_array['file_error'] = $file_uplod_return['message'];
                            }
                        }

                        if (!empty($_FILES['other'])) {
                            $config_array = array('file_upload_path' => $file_upload_path,'file_permission' => 'jpeg|jpg|png','file_size' => BULK_UPLOAD_MAX_SIZE_MB*2000,'file_data' => $_FILES['other'],$frm_details['other_lat_long']);
                            $file_uplod_return = $this->file_upload_library->file_upload_for_address_verification($config_array);
                            if($file_uplod_return['status'] === TRUE) {


                                $this->watermarkImage($file_uplod_return['file_name'],$frm_details['other_lat_long']);

                                $field_array_other = array('file_name' => $file_uplod_return['file_name'],
                                          'real_filename' => $file_uplod_return['file_name'],
                                          'view_venor_master_log_id' =>  $frm_details['t_id'],
                                          'component_tbl_id' => 1,
                                          'status' => 1 );

                                $insert_other = $this->addrver_executive_model->save('view_vendor_master_log_file',array_map('strtolower', $field_array_other));


                                $details_arry['other'] = $file_uplod_return['file_name'];
                            }else {
                                $json_array['file_error'] = $file_uplod_return['message'];
                            }
                        }
                        

                      /*  $folder_name1 = "vendor_file";
                        $file_upload_path1 = SITE_BASE_PATH.ADDRESS.$folder_name1.'/';

                        $signature_png = $file_upload_path1.$frm_details['transaction_id'].'_signature.png';

                        $converted = $this->base64_to_jpeg($frm_details['signature'],$signature_png);
                        $details_arry['signature'] = '';
                        if($converted) {
                            $details_arry['signature'] = $frm_details['transaction_id'].'_signature.png';

                        }
                        
                    
                        $insert_id= $this->addrver_executive_model->save('view_vendor_master_log_executive',$details_arry);

                        $this->load->library('address_form');

                        $this->address_form->generate_pdf($details_arry_pdf);
                        $jpg = '.png';
                        $path = SITE_BASE_PATH . ADDRESS . 'vendor_file/';
                        $file_name = $frm_details['transaction_id'].'_Report'.'.pdf';
                        $source_file = $path.$file_name;
                        $distination_path = SITE_BASE_PATH . ADDRESS . 'vendor_file/';


                        $pagecount = $this->pdf_page_count($source_file);
                        $this->conver_pdf_to_jpg($source_file,$distination_path);

                        if($pagecount == 1) {
                            $file = pathinfo($file_name);
                            $file2 = pathinfo($file_name);
                                $file_array[] =  array(
                                    'real_filename'  => $file['filename'].$jpg,
                                    'file_name'      => $file2['filename'].$jpg,
                                    'view_venor_master_log_id' => $frm_details['t_id'],
                                    'component_tbl_id' => 1,
                                    'status' => 1
                                );
                        }

                        if (!empty($file_array)) {
                           $this->common_model->common_insert_batch('view_vendor_master_log_file', $file_array);
                        }
                        */
                        
                     
                            
                            $json_array['status'] = SUCCESS_CODE;
                            $json_array['message'] = 'Record inserted Successfully';

                            if($frm_details['status_redirect'] == "wip")
                            {
                                $json_array['redirect'] = VENDOR_EXECUTIVE_SITE_URL.'addrver_exe/addrver_exe_wip';
                            }
                            if($frm_details['status_redirect'] == "insufficiency")
                            {
                                $json_array['redirect'] = VENDOR_EXECUTIVE_SITE_URL.'addrver_exe/addrver_insufficiency';
                            }
                            if($frm_details['status_redirect'] == "closed")
                            {
                                $json_array['redirect'] = VENDOR_EXECUTIVE_SITE_URL.'addrver_exe/addrver_closed';
                            }


                
                    }
                    else
                    {
                        $json_array['status'] = ERROR_CODE;
                        $json_array['message'] = 'Please upload file';
                    }

                }
                if($frm_details['status'] == "candidate shifted" || $frm_details['status'] == "unable to verify" || $frm_details['status'] == "denied verification"  || $frm_details['status'] == "resigned" || $frm_details['status'] == "candidate not responding")
                {

                    $folder_name = "vendor_file";
                    $file_upload_path = SITE_BASE_PATH.ADDRESS.$folder_name;
                    if(!folder_exist($file_upload_path)) {
                        mkdir($file_upload_path,0777);
                    }else if(!is_writable($file_upload_path)) {
                        array_push($error_msgs,'Problem while uploading');
                    }
                   
                    $config_array = array('file_upload_path' => $file_upload_path,
                                    'file_permission' => 'jpeg|jpg|png',
                                    'file_size' => BULK_UPLOAD_MAX_SIZE_MB*20000,
                                    'view_venor_master_log_id' =>  $frm_details['t_id'],
                                    'component_tbl_id' => 1,
                                    'status' => 1 );


                        if (!empty($_FILES['address_forms'])) {
                            $config_array = array('file_upload_path' => $file_upload_path,'file_permission' => 'jpeg|jpg|png','file_size' => BULK_UPLOAD_MAX_SIZE_MB*2000,'file_data' => $_FILES['address_forms']);
                            $file_uplod_return = $this->file_upload_library->file_upload_for_address_verification($config_array);
                            if($file_uplod_return['status'] === TRUE) {


                                $field_array_address_forms = array('file_name' => $file_uplod_return['file_name'],
                                          'real_filename' => $file_uplod_return['file_name'],
                                          'view_venor_master_log_id' =>  $frm_details['t_id'],
                                          'component_tbl_id' => 1,
                                          'status' => 1 );
                                
                                $insert_address_forms = $this->addrver_executive_model->save('view_vendor_master_log_file',array_map('strtolower',$field_array_address_forms));


                                $details_arry['address_forms'] = $file_uplod_return['file_name'];
                            }else {
                                $json_array['file_error'] = $file_uplod_return['message'];
                            }
                        }


                        if (!empty($_FILES['house_pic_door'])) {
                            $config_array = array('file_upload_path' => $file_upload_path,'file_permission' => 'jpeg|jpg|png','file_size' => BULK_UPLOAD_MAX_SIZE_MB*2000,'file_data' => $_FILES['house_pic_door']);
                            $file_uplod_return = $this->file_upload_library->file_upload_for_address_verification($config_array);
                            if($file_uplod_return['status'] === TRUE) {


                                $this->watermarkImage($file_uplod_return['file_name'],$frm_details['house_pic_door_lat_long']);

                                $field_array_house_door = array('file_name' => $file_uplod_return['file_name'],
                                          'real_filename' => $file_uplod_return['file_name'],
                                          'view_venor_master_log_id' =>  $frm_details['t_id'],
                                          'component_tbl_id' => 1,
                                          'status' => 1 );

                                $insert_address_house = $this->addrver_executive_model->save('view_vendor_master_log_file',array_map('strtolower', $field_array_house_door));


                                $details_arry['house_pic_door'] = $file_uplod_return['file_name'];
                            }else {
                                $json_array['file_error'] = $file_uplod_return['message'];
                            }
                        }


                        if (!empty($_FILES['location_picture_1'])) {
                            $config_array = array('file_upload_path' => $file_upload_path,'file_permission' => 'jpeg|jpg|png','file_size' => BULK_UPLOAD_MAX_SIZE_MB*2000,'file_data' => $_FILES['location_picture_1']);
                            $file_uplod_return = $this->file_upload_library->file_upload_for_address_verification($config_array);
                            if($file_uplod_return['status'] === TRUE) {


                                $this->watermarkImage($file_uplod_return['file_name'],$frm_details['location_picture_1_lat_long']);


                                $field_array_location_picture_1 = array('file_name' => $file_uplod_return['file_name'],
                                          'real_filename' => $file_uplod_return['file_name'],
                                          'view_venor_master_log_id' =>  $frm_details['t_id'],
                                          'component_tbl_id' => 1,
                                          'status' => 1 );

                                $insert_address_location_1 = $this->addrver_executive_model->save('view_vendor_master_log_file',array_map('strtolower', $field_array_location_picture_1));


                                $details_arry['location_picture_1'] = $file_uplod_return['file_name'];
                            }else {
                                $json_array['file_error'] = $file_uplod_return['message'];
                            }
                        }

                        if (!empty($_FILES['location_picture_2'])) {
                            $config_array = array('file_upload_path' => $file_upload_path,'file_permission' => 'jpeg|jpg|png','file_size' => BULK_UPLOAD_MAX_SIZE_MB*2000,'file_data' => $_FILES['location_picture_2']);
                            $file_uplod_return = $this->file_upload_library->file_upload_for_address_verification($config_array);
                            if($file_uplod_return['status'] === TRUE) {


                                $this->watermarkImage($file_uplod_return['file_name'],$frm_details['location_picture_2_lat_long']);

                                $field_array_location_picture_2 = array('file_name' => $file_uplod_return['file_name'],
                                          'real_filename' => $file_uplod_return['file_name'],
                                          'view_venor_master_log_id' =>  $frm_details['t_id'],
                                          'component_tbl_id' => 1,
                                          'status' => 1 );

                                $insert_address_location_2 = $this->addrver_executive_model->save('view_vendor_master_log_file',array_map('strtolower', $field_array_location_picture_2));

                                $details_arry['location_picture_2'] = $file_uplod_return['file_name'];
                            }else {
                                $json_array['file_error'] = $file_uplod_return['message'];
                            }
                        }


                        
                        if (!empty($_FILES['location_picture_3'])) {
                            $config_array = array('file_upload_path' => $file_upload_path,'file_permission' => 'jpeg|jpg|png','file_size' => BULK_UPLOAD_MAX_SIZE_MB*2000,'file_data' => $_FILES['location_picture_3'],$frm_details['location_picture_3_lat_long']);
                            $file_uplod_return = $this->file_upload_library->file_upload_for_address_verification($config_array);
                            if($file_uplod_return['status'] === TRUE) {


                                $this->watermarkImage($file_uplod_return['file_name'],$frm_details['location_picture_3_lat_long']);

                                $field_array_location_picture_3 = array('file_name' => $file_uplod_return['file_name'],
                                          'real_filename' => $file_uplod_return['file_name'],
                                          'view_venor_master_log_id' =>  $frm_details['t_id'],
                                          'component_tbl_id' => 1,
                                          'status' => 1 );

                                $insert_address_location_3 = $this->addrver_executive_model->save('view_vendor_master_log_file',array_map('strtolower', $field_array_location_picture_3));


                                $details_arry['location_picture_3'] = $file_uplod_return['file_name'];
                            }else {
                                $json_array['file_error'] = $file_uplod_return['message'];
                            }
                        }

                        if (!empty($_FILES['other'])) {
                            $config_array = array('file_upload_path' => $file_upload_path,'file_permission' => 'jpeg|jpg|png','file_size' => BULK_UPLOAD_MAX_SIZE_MB*2000,'file_data' => $_FILES['other'],$frm_details['other_lat_long']);
                            $file_uplod_return = $this->file_upload_library->file_upload_for_address_verification($config_array);
                            if($file_uplod_return['status'] === TRUE) {


                                $this->watermarkImage($file_uplod_return['file_name'],$frm_details['other_lat_long']);

                                $field_array_other = array('file_name' => $file_uplod_return['file_name'],
                                          'real_filename' => $file_uplod_return['file_name'],
                                          'view_venor_master_log_id' =>  $frm_details['t_id'],
                                          'component_tbl_id' => 1,
                                          'status' => 1 );

                                $insert_other = $this->addrver_executive_model->save('view_vendor_master_log_file',array_map('strtolower', $field_array_other));


                                $details_arry['other'] = $file_uplod_return['file_name'];
                            }else {
                                $json_array['file_error'] = $file_uplod_return['message'];
                            }
                        }

                    $json_array['status'] = SUCCESS_CODE;
                    $json_array['message'] = 'Record inserted Successfully';    
                        if($frm_details['status_redirect'] == "wip")
                        {
                            $json_array['redirect'] = VENDOR_EXECUTIVE_SITE_URL.'addrver_exe/addrver_exe_wip';
                        }
                        if($frm_details['status_redirect'] == "insufficiency")
                        {
                            $json_array['redirect'] = VENDOR_EXECUTIVE_SITE_URL.'addrver_exe/addrver_insufficiency';
                        }
                        if($frm_details['status_redirect'] == "closed")
                        {
                            $json_array['redirect'] = VENDOR_EXECUTIVE_SITE_URL.'addrver_exe/addrver_closed';
                        }
                }   
            }
        }
        else
        {
            $json_array['status'] = ERROR_CODE;
            $json_array['message'] = 'Something went wrong !!';
        }
        echo_json($json_array);
    }


 /*   protected function rotate_up_images($filename)
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
            log_message('error', 'Address::pdf_page_count');
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
            exec("convert -density 300 $pdf -quality 100 $file_name.png > /dev/null &");
            return true;
        } catch (Exception $e) {
            log_message('error', 'Address_exe::conver_pdf_to_jpg');
            log_message('error', $e->getMessage());
        }
        // for windows
        // $cmd = "C:\\xpdf-tools-win-4.02\\bin64\\pdftopng.exe";  // Windows
        // exec("$cmd $source $distination", $output);
        // return true;
    }
    
    protected function watermarkImage($filename,$latlong) {
        
        $folder_name = "vendor_file/";
        $file_upload_path = SITE_BASE_PATH.ADDRESS.$folder_name;

        $fileget = $file_upload_path.$filename; 
    
       
        if (file_exists($fileget)){

            if(!empty($latlong))
            {
                $latitude_longitude =    explode(',',$latlong);

                $latitude  = $latitude_longitude[0];

                $longitude  = $latitude_longitude[1];
            }
            else
            {
                $latitude = '';
                $longitude  = '';
            }
             
            exec("python3 /var/www/html/mist-it/convert.py $latitude $longitude 2>&1", $address, $ret_code);


            list($width, $height) = getimagesize($fileget);
            $extension = pathinfo($fileget, PATHINFO_EXTENSION);
     
            $image_p = imagecreatetruecolor($width, $height);
            $size = getimagesize($fileget);
            $dest_x = $size[0] - $width - 5;  
            $dest_y = $size[1] - $height - 5;
            switch ($extension)
            {
              case 'png':
                $image = imagecreatefrompng($fileget);
              break;
              case 'gif':
                $image = imagecreatefromgif($fileget);
              break;
              case 'jpeg':
                $image = imagecreatefromjpeg($fileget);
              break;
              case 'jpg':
                $image = imagecreatefromjpeg($fileget);
              break;
              default:
                throw new InvalidArgumentException('File "'.$fileget.'" is not valid jpg, png or gif image.');
              break;  
            }

            imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width, $height); 
            $white = imagecolorallocate($image_p, 255, 255, 255);
            $black = imagecolorallocate($image_p, 0, 0, 0 );
            $font = '/var/www/html/mist-it/arial.ttf';
            //$font = 'C:/xampp/htdocs/arial.ttf';
           
            $font_size = (1.5 / 100) * imagesy($image);

            if(!empty($address[0]))
            {
               $address_array = explode(",",$address[0]);
               $address_count =  count($address_array);

               if($address_count % 2 == 0){
                  $division =   $address_count / 2; 
                }
                else{
                  $division =   $address_count / 2;
                  $division =  $division - 0.5;
                }
                $address = '';
                foreach ($address_array as $key => $value) {
                    if($division > 3)
                    {
                      if($key ==  3)
                      {
                        $address .= "\n";
                      }
                    }
                    else{
                        if($key ==  $division)
                        {
                          $address .= "\n";
                        }
                    }
                     
                    $address .= $value;
          
                  if($address_count - 1 != $key)
                  {
                    $address .= ',';
                  }
                }

               $address_bbx = imagettfbbox($font_size, 0, $font, $address);
            }
            $latitude_bbx = imagettfbbox($font_size, 0, $font, "Latitude : ".$latitude);
            $localtime_bbox = imagettfbbox($font_size, 0, $font, "Local ". date('h:i:s A'));
            $gmttime_bbox = imagettfbbox($font_size, 0, $font,"GMT ". gmdate('h:i:s A'));
            
            $longitude_bbx = imagettfbbox($font_size, 0, $font, "Longitude : ". $longitude);
            $mist_bbox = imagettfbbox($font_size, 0, $font, "Mist IT Services Pvt Ltd");
            $bbox = imagettfbbox($font_size, 0, $font, date('l  d-m-Y' ));
           
            if(!empty($address[0]))
            {
               $x_address =  $address_bbx[2];
               $y_address = $address_bbx[1] + (imagesy($image)) - ($address_bbx[5] / 2);
            }

            $x_latitude =  $latitude_bbx[2];
            $y_latitude = $latitude_bbx[1] + (imagesy($image)) - ($latitude_bbx[5] / 2);
            
            $x_localtime =  $localtime_bbox[2];
            $y_localtime = $localtime_bbox[1] + (imagesy($image)) - ($localtime_bbox[5] / 2);

            $x_gmttime =  $gmttime_bbox[2];
            $y_gmttime = $gmttime_bbox[1] + (imagesy($image)) - ($gmttime_bbox[5] / 2);

            $x_longitude =  $longitude_bbx[0] + (imagesx($image)) - ($longitude_bbx[4]);
            $y_longitude = $longitude_bbx[1] + (imagesy($image)) - ($longitude_bbx[5] / 2);

            $x = $bbox[0] + (imagesx($image)) - ($bbox[4]);
            $y = $bbox[1] + (imagesy($image)) - ($bbox[5] / 2);

            $x_mist = $mist_bbox[0] + (imagesx($image)) - ($mist_bbox[4]);
            $y_mist = $mist_bbox[1] + (imagesy($image)) - ($mist_bbox[5] / 2);

            imagefilledrectangle($image_p,0,imagesy($image),imagesx($image),imagesy($image) -  ((15 / 100) * imagesy($image)), $black);

            if(!empty($address[0]))
            {
                imagettftext($image_p, $font_size, 0,(5 / 100) * $x_address,(84 / 100) * $y_address, $white, $font, $address);
            }
            imagettftext($image_p, $font_size, 0,(5 / 100) * $x_latitude,(92 / 100) * $y_latitude, $white, $font, "Latitude : ".$latitude);
            imagettftext($image_p, $font_size, 0,(5 / 100) * $x_localtime,(95 / 100) * $y_localtime, $white, $font, "Local ". date('h:i:s A'));
            imagettftext($image_p, $font_size, 0,(5 / 100) * $x_gmttime,(98 / 100) *  $y_gmttime, $white, $font, "GMT ". gmdate('h:i:s A'));

            imagettftext($image_p, $font_size, 0,$x_longitude - (5 / 100) * $x_longitude,(92 / 100) * $y_longitude, $white, $font, "Longitude : ".$longitude);
            imagettftext($image_p, $font_size, 0,$x - (5 / 100) * $x_longitude,(95 / 100) * $y, $white, $font, date('l  d-m-Y' ));

            imagettftext($image_p, $font_size, 0,$x_mist - (5 / 100) * $x_longitude, (98 / 100) *  $y_mist , $white, $font,"Mist IT Services Pvt Ltd");

            switch ($extension)
            {
              case 'png':
                imagepng ($image_p, $fileget, 9); 
              break;
              case 'gif':
                imagegif($image_p, $fileget, 9);
              break;
              case 'jpeg':
                imagejpeg($image_p, $fileget, 9);
              break;
              case 'jpg':
                imagejpeg($image_p, $fileget, 9);
              break;
              default:
                throw new InvalidArgumentException('File "'.$fileget.'" is not valid jpg, png or gif image.');
              break;  
            }
            
          
            imagedestroy($image); 
            imagedestroy($image_p); 
        }
       
    }

    
    public static function geolocationaddress($lat, $long)
    {
      /*  $geocode = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$long&key=AIzaSyAU_tcKKvL8NP97duFTiS4-Urc6S1JKJ4g&#038;ver=1";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $geocode);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
       // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
       // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        $output = json_decode($response);
       print_r($output);exit();
        $dataarray = get_object_vars($output);
      
        if ($dataarray['status'] != 'ZERO_RESULTS' && $dataarray['status'] != 'INVALID_REQUEST') {
            if (isset($dataarray['results'][0]->formatted_address)) {

                $address = $dataarray['results'][0]->formatted_address;

            } else {
                $address = 'Not Found';

            }
        } else {
            $address = 'Not Found';
        }

        return $address;*/
        /*  $url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($lat).','.trim($long).'&sensor=false';
          $json = file_get_contents($url);
          print_r($json);exit();
          $data = json_decode($json);
          $status = $data->status;
          $address = '';
          if($status == "OK")
          {
            echo $address = $data->results[0]->formatted_address;
          }
          else
          {
            echo "No Data Found Try Again";
          }*/
    }
     

    public function status_count_executive()
    {

        $json_array = array();
        
        $result = array();

        $params = $_REQUEST;
        
        if($this->input->is_ajax_request())
        {
          
          $result = $this->addrver_executive_model->status_count_address_executive($this->vendor_executive_info['vendors_id'],$this->vendor_executive_info['id'],$params);


           // $result = array_merge($result_address,$result_drugs);    

          $json_array['status'] = SUCCESS_CODE;
                    
          $json_array['message'] = $result;
        }
        else
        {
            $json_array['status'] = ERROR_CODE;

            $json_array['message'] = 'Something went wrong';
        }
        echo_json($json_array);
    } 
   

 
    public function get_mobile_no($transaction_id)
    {
       $details = $this->addrver_executive_model->select_mobile_no(array('view_vendor_master_log.id' => base64_decode($transaction_id),'component' => 'addrver','view_vendor_master_log.status' => 1));

        if ($transaction_id && !empty($details)) {
          
           $data['details'] =  $details[0];

            
          echo  $this->load->view('executive/mobile_no_details', $data,true);

        } else {
            echo "<h4>Record Not Found</h4>";
        }
    } 
    

}
?>