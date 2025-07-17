<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Courtver extends MY_Vendor_Cotroller
{    
    function __construct()
    {
        parent::__construct();
        $this->perm_array = array('page_name' => 'courtver');
        $this->assign_options = array('0' => 'Select','1' => 'Assign to Executive');
        $this->load->model(array('vendor/courtver_model'));
    }

    public function index()
    {   
        $data['header_title'] = "Court Cases View";


        $this->load->view('vendor/header',$data);
        
        $this->load->view('vendor/courtver_list');

        $this->load->view('vendor/footer');


    }
    public function courtver_wip()
    {   
        $data['header_title'] = "Court Cases View";

        
       $data['vendor_executive_list'] = $this->courtver_model->get_vendor_assign_users('vendor_executive_login',FALSE,array("`vendor_executive_login`.`status`,`vendor_executive_login`.`id`,`vendor_executive_login`.`email_id`,`vendor_executive_login`.`user_name`,concat(`vendor_executive_login`.`first_name`,' ',`vendor_executive_login`.`last_name`) as fullname,`vendor_executive_login`.`first_name`,`vendor_executive_login`.`last_name`"),array('status' => STATUS_ACTIVE));
       
       
        $this->load->view('vendor/header',$data);
        
        $this->load->view('vendor/courtver_list_wip');

        $this->load->view('vendor/footer');
    }

    public function courtver_insufficiency()
    {   
        $data['header_title'] = "Court Cases View";

       $data['vendor_executive_list'] = $this->courtver_model->get_vendor_assign_users('vendor_executive_login',FALSE,array("`vendor_executive_login`.`status`,`vendor_executive_login`.`id`,`vendor_executive_login`.`email_id`,`vendor_executive_login`.`user_name`,concat(`vendor_executive_login`.`first_name`,' ',`vendor_executive_login`.`last_name`) as fullname,`vendor_executive_login`.`first_name`,`vendor_executive_login`.`last_name`"),array('status' => STATUS_ACTIVE));
       
       
        $this->load->view('vendor/header',$data);
        
        $this->load->view('vendor/courtver_list_insufficiency');

        $this->load->view('vendor/footer');
    }
    
     public function courtver_closed()
    {   
        $data['header_title'] = "Court Cases View";

       $data['vendor_executive_list'] = $this->courtver_model->get_vendor_assign_users('vendor_executive_login',FALSE,array("`vendor_executive_login`.`status`,`vendor_executive_login`.`id`,`vendor_executive_login`.`email_id`,`vendor_executive_login`.`user_name`,concat(`vendor_executive_login`.`first_name`,' ',`vendor_executive_login`.`last_name`) as fullname,`vendor_executive_login`.`first_name`,`vendor_executive_login`.`last_name`"),array('status' => STATUS_ACTIVE));
       
       
        $this->load->view('vendor/header',$data);
        
        $this->load->view('vendor/courtver_list_closed');

        $this->load->view('vendor/footer');


    }


    public function court_view_datatable_assign_wip()
    {
     
        $params = $add_candidates = $data_arry = $columns = array();
         
        $params = $_REQUEST;
  
        $columns = array('court_id.id','candidates_info.ClientRefNumber','candidates_info.cmp_ref_no','company_database.coname','candidates_info.CandidateName','candidates_info.caserecddate','verfstatus','city','encry_id','address','pincode','state','check_closure_date','court_com_ref','iniated_date');
 
        $where_arry = array();

        $lists = $this->courtver_model->court_case_list(array('courtver_vendor_log.vendor_id' => $this->vendor_info['vendors_id'],'view_vendor_master_log.status =' => 1,'view_vendor_master_log.final_status =' => 'wip','component' => 'courtver'),$params,$columns);

        $totalRecords = count($this->courtver_model->court_case_list_count(array('courtver_vendor_log.vendor_id' => $this->vendor_info['vendors_id'],'view_vendor_master_log.status =' => 1,'view_vendor_master_log.final_status =' => 'wip','component' => 'courtver'),$params,$columns));
        
 
        $x = 0;
        
        foreach ($lists as $list)
        {
           $data_arry[$x]['checkbox'] = $list['id'];
           //$data_arry[$x]['checkbox'] = "<input type='checkbox' id='select-checkbox' name='case_id' class='case_id' value='".$list['id']."'>";
            $data_arry[$x]['created_on'] = convert_db_to_display_date($list['created_on'],DB_DATE_FORMAT,DISPLAY_DATE_FORMAT12);
           
            $data_arry[$x]['CandidateName'] = ucwords($list['CandidateName']);
            $data_arry[$x]['street_address'] = ucwords($list['street_address']);
            $data_arry[$x]['city'] = ucwords($list['city']);
            $data_arry[$x]['pincode'] = $list['pincode'];
            $data_arry[$x]['state'] = ucwords($list['state']);
            $data_arry[$x]['city'] = ucwords($list['city']);
            $data_arry[$x]['status'] = ucwords($list['final_status']);
            $data_arry[$x]['vendor_executive_id'] = $list['vendor_executive_id'];
            $data_arry[$x]['test2'] = "test";
       
            $data_arry[$x]['encry_id'] =encrypt($list['id']);
            $data_arry[$x]['tat_status'] = $list['tat_status'];
            $data_arry[$x]['vendor_list_mode'] = ucwords($list['vendor_list_mode']);
            
            $data_arry[$x]['trasaction_id'] = $list['trasaction_id'];
            $data_arry[$x]['court_com_ref'] = $list['court_com_ref'];
                 
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

    public function court_view_datatable_assign_insufficiency()
    {
     
        $params = $add_candidates = $data_arry = $columns = array();
         
        $params = $_REQUEST;
       
        $columns = array('court_id.id','candidates_info.ClientRefNumber','candidates_info.cmp_ref_no','company_database.coname','candidates_info.CandidateName','candidates_info.caserecddate','verfstatus','city','encry_id','address','pincode','state','check_closure_date','court_com_ref','iniated_date');
 
        $where_arry = array();

        $lists = $this->courtver_model->court_case_list(array('courtver_vendor_log.vendor_id' => $this->vendor_info['vendors_id'],'view_vendor_master_log.status =' => 1,'view_vendor_master_log.final_status =' => 'insufficiency','component' => 'courtver'),$params,$columns);

        $totalRecords = count($this->courtver_model->court_case_list_count(array('courtver_vendor_log.vendor_id' => $this->vendor_info['vendors_id'],'view_vendor_master_log.status =' => 1,'view_vendor_master_log.final_status =' => 'insufficiency','component' => 'courtver'),$params,$columns));
        
       
        $x = 0;
        
        foreach ($lists as $list)
        {
           $data_arry[$x]['checkbox'] = $list['id'];
          
            $data_arry[$x]['created_on'] = convert_db_to_display_date($list['created_on'],DB_DATE_FORMAT,DISPLAY_DATE_FORMAT12);
            $data_arry[$x]['modified_on'] = convert_db_to_display_date($list['modified_on'],DB_DATE_FORMAT,DISPLAY_DATE_FORMAT12);
           
            $data_arry[$x]['CandidateName'] = ucwords($list['CandidateName']);
            $data_arry[$x]['street_address'] = ucwords($list['street_address']);
            $data_arry[$x]['city'] = ucwords($list['city']);
            $data_arry[$x]['pincode'] = $list['pincode'];
            $data_arry[$x]['state'] = ucwords($list['state']);
            $data_arry[$x]['city'] = ucwords($list['city']);
            $data_arry[$x]['status'] = ucwords($list['final_status']);
            $data_arry[$x]['vendor_executive_id'] = $list['vendor_executive_id'];
            $data_arry[$x]['test2'] = "test";
            $data_arry[$x]['encry_id'] =encrypt($list['id']);
            $data_arry[$x]['tat_status'] = $list['tat_status'];
            $data_arry[$x]['vendor_list_mode'] = ucwords($list['vendor_list_mode']);
            $data_arry[$x]['trasaction_id'] = $list['trasaction_id'];
            $data_arry[$x]['court_com_ref'] = $list['court_com_ref'];
                  
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

    public function court_view_datatable_assign_closed()
    {
     
        $params = $add_candidates = $data_arry = $columns = array();
         
        $params = $_REQUEST;
       
        $columns = array('court_id.id','candidates_info.ClientRefNumber','candidates_info.cmp_ref_no','company_database.coname','candidates_info.CandidateName','candidates_info.caserecddate','verfstatus','city','encry_id','address','pincode','state','check_closure_date','court_com_ref','iniated_date');
 
        $where_arry = array();

        $lists = $this->courtver_model->court_case_list_closed(array('courtver_vendor_log.vendor_id' => $this->vendor_info['vendors_id'],'view_vendor_master_log.status =' => 1,'component' => 'courtver'),$params,$columns);

        $totalRecords = count($this->courtver_model->court_case_list_closed_count(array('courtver_vendor_log.vendor_id' => $this->vendor_info['vendors_id'],'view_vendor_master_log.status =' => 1,'component' => 'courtver'),$params,$columns));
        
       
        $x = 0;
        
        foreach ($lists as $list)
        {
           $data_arry[$x]['checkbox'] = $list['id'];
         
            $data_arry[$x]['created_on'] = convert_db_to_display_date($list['created_on'],DB_DATE_FORMAT,DISPLAY_DATE_FORMAT12);
            $data_arry[$x]['modified_on'] = convert_db_to_display_date($list['modified_on'],DB_DATE_FORMAT,DISPLAY_DATE_FORMAT12);
            $data_arry[$x]['CandidateName'] = ucwords($list['CandidateName']);
            $data_arry[$x]['street_address'] = ucwords($list['street_address']);
            $data_arry[$x]['city'] = ucwords($list['city']);
            $data_arry[$x]['pincode'] = $list['pincode'];
            $data_arry[$x]['state'] = ucwords($list['state']);
            $data_arry[$x]['city'] = ucwords($list['city']);
            $data_arry[$x]['status'] = ucwords($list['final_status']);
            $data_arry[$x]['vendor_executive_id'] = $list['vendor_executive_id'];
            $data_arry[$x]['test2'] = "test";
            $data_arry[$x]['encry_id'] =encrypt($list['id']);
            $data_arry[$x]['tat_status'] = $list['tat_status'];
            $data_arry[$x]['vendor_list_mode'] = ucwords($list['vendor_list_mode']);
            $data_arry[$x]['trasaction_id'] = $list['trasaction_id'];
            $data_arry[$x]['court_com_ref'] = $list['court_com_ref'];
                     
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
  

    public function view_details($trans_id1 = '')
    {
            
        $trans_id     =  decrypt($trans_id1);
      //  $addressver_details = $this->addrver_model->get_address_details(array('addrver.id' => decrypt($addsver_id)));
        $details['header_title'] = "Address Edit";

        $details['details'] = $this->courtver_model->select_vendor_result_log(array('component_tbl_id' => "5","component" => "courtver"),$trans_id);
       // $details['states'] = $this->get_states();
  

        if($trans_id1 && !empty($details))
        {
            

            $this->load->view('vendor/header',$details);

            $this->load->view('vendor/courtver_edit');

            $this->load->view('vendor/footer');
        }
        else
        {
            show_404();
        }
    }

    public function update_court_wip()
    {
        if($this->input->is_ajax_request())
        {

            $frm_details = $this->input->post();
       
            $this->form_validation->set_rules('transaction_id', 'ID', 'required');

            if($frm_details['status'] != "wip")
            {

            $this->form_validation->set_rules('vendor_remark', 'Vendor Remark', 'required');

            }

            if($frm_details['status'] == "possible match")
            {
            

                if(empty($_FILES['attchments_file']['name'][0]) && empty($_POST['attchments']))
                {

                   
                   $this->form_validation->set_rules('attchments_file', 'Attachment file', 'required');
                 }
            }

            if(($frm_details['generate'] == "1") && ($frm_details['status'] == "clear"))
            {
               $this->form_validation->set_rules('civil_civil_proceed', 'Court Court', 'required');

               $this->form_validation->set_rules('civil_high_proceed', 'High Court in Civil Proceedings', 'required');

               $this->form_validation->set_rules('criminal_magistrate_proceed', 'Magistrate Court', 'required');

               $this->form_validation->set_rules('criminal_sessions_proceed', 'Sessions Court', 'required');

               $this->form_validation->set_rules('criminal_high_proceed', 'High Court in Criminal Proceedings', 'required'); 
            }

            if ($this->form_validation->run() == FALSE)
            {


                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('','');


            }
            else
            {

        
                $frm_details = $this->input->post();

   
                $field_array = array('vendor_actual_status' => $frm_details['status'],'final_status' => $frm_details['status'],'vendor_remark' => $frm_details['vendor_remark']);

                if($frm_details['status'] == "insufficiency" || $frm_details['status'] == "clear" || $frm_details['status'] == "possible match")
                {
                   $field_array['modified_by']  = $this->vendor_info['vendors_id'];
                   $field_array['modified_on']  = date(DB_DATE_FORMAT);
                }

                $result= $this->courtver_model->update('view_vendor_master_log',array_map('strtolower',$field_array),array('trasaction_id' => $frm_details['transaction_id']));

                $folder_name = "vendor_file";
                $file_upload_path = SITE_BASE_PATH.COURT_VERIFICATION.$folder_name;
                if(!folder_exist($file_upload_path)) {
                    mkdir($file_upload_path,0777,true);
                }else if(!is_writable($file_upload_path)) {
                    array_push($error_msgs,'Problem while uploading');
                }
                
               $config_array = array('file_upload_path' => $file_upload_path,
                                     'file_permission' => 'jpeg|jpg|png|tiff|pdf',
                                     'file_size' => BULK_UPLOAD_MAX_SIZE_MB*2000,
                                     'view_venor_master_log_id' =>  $frm_details['update_id'],
                                     'component_tbl_id' => 5,
                                     'status' => 1 );
                
                if($_FILES['attchments_file']['name'][0] != '')
                {
                    $config_array['files_count'] = count($_FILES['attchments_file']['name']);
                    $config_array['file_data'] = $_FILES['attchments_file'];
                    
                    $retunr_de = $this->file_upload_library->file_upload_multiple_vendor($config_array, true);

                    if(!empty($retunr_de['success'])) {
                        $this->common_model->common_insert_batch('view_vendor_master_log_file',$retunr_de['success']);
                    }   
                }

                if(($frm_details['generate'] == "1") && ($frm_details['status'] == "clear"))
                {
                    $file_array = $error_msgs =array();
                    $folder_name = "vendor_file";
                    $file_upload_path = SITE_BASE_PATH.COURT_VERIFICATION.$folder_name;
                    if(!folder_exist($file_upload_path)) {
                        mkdir($file_upload_path,0777,true);
                    }else if(!is_writable($file_upload_path)) {
                       array_push($error_msgs,'Problem while uploading');
                    }
                            
                    $file_name =  $frm_details['transaction_id']."_".date(UPLOAD_FILE_DATE_FORMAT) . '.png';

                    $savefile = file_put_contents($file_upload_path."/".$file_name, base64_decode(explode(",", $_POST['img'])[1]));
                   
                    $file_array[] = array(
                                        'real_filename'  =>  $file_name,
                                        'file_name'      => $file_name,
                                        'view_venor_master_log_id' => $frm_details['update_id'],
                                        'component_tbl_id' => 5,
                                        'status' => 1
                                    );
                    $this->common_model->common_insert_batch('view_vendor_master_log_file',$file_array);

                    $court_filling_record = array(
                        'view_vendor_master_log_id' => $frm_details['update_id'],
                        'date_of_verification' => convert_display_to_db_date($frm_details['date_of_verification']),
                        'civil_civil_proceed' => $frm_details['civil_civil_proceed'],
                        'civil_high_proceed' => $frm_details['civil_high_proceed'],
                        'criminal_magistrate_proceed' => $frm_details['criminal_magistrate_proceed'],
                        'criminal_sessions_proceed' => $frm_details['criminal_sessions_proceed'],
                        'criminal_high_proceed' => $frm_details['criminal_high_proceed'],
                        'status' => 1,
                        'created_on' => date(DB_DATE_FORMAT),
                    );
                    
                    $result_insert_court =  $this->courtver_model->insert_court_details('court_vendor_details',$court_filling_record);
                }

                
                if($result)
                {

                    $update_activity_log = $this->save_activity_log(array('transaction_id' =>  $frm_details['update_id'],'component' => 'courtver','action' => 'Status change','remark' => 'Status Changed','status' => $frm_details['status'],'created_by'=> $this->vendor_info['id'] , 'created_on' => date(DB_DATE_FORMAT))); 

                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'Record Successfully Updated';

                    $json_array['redirect'] = VENDOR_SITE_URL.'courtver/courtver_wip';

                    //$json_array['active_tab'] = 'addrver';
                }
                else
                {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Something went wrong,please try again';
                }
               
            }
             echo_json($json_array);
        }
     
    }
    
    public function update_court_insufficiency()
    {
        if($this->input->is_ajax_request())
        {

          $frm_details = $this->input->post();


            $this->form_validation->set_rules('transaction_id', 'ID', 'required');

            if($frm_details['status'] != "wip")
            {

            $this->form_validation->set_rules('vendor_remark', 'Vendor Remark', 'required');

            }

            if($frm_details['status'] == "possible match")
            {
            

                if(empty($_FILES['attchments_file']['name'][0]) && empty($_POST['attchments']))
                {

                   
                   $this->form_validation->set_rules('attchments_file', 'Attachment file', 'required');
                 }
            }

            
            if(($frm_details['generate'] == "1") && ($frm_details['status'] == "clear"))
            {
               $this->form_validation->set_rules('civil_civil_proceed', 'Court Court', 'required');

               $this->form_validation->set_rules('civil_high_proceed', 'High Court in Civil Proceedings', 'required');

               $this->form_validation->set_rules('criminal_magistrate_proceed', 'Magistrate Court', 'required');

               $this->form_validation->set_rules('criminal_sessions_proceed', 'Sessions Court', 'required');

               $this->form_validation->set_rules('criminal_high_proceed', 'High Court in Criminal Proceedings', 'required'); 
            }

            if ($this->form_validation->run() == FALSE)
            {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('','');
            }
            else
            {

                $frm_details = $this->input->post();

   
                $field_array = array('vendor_actual_status' => $frm_details['status'],'final_status' => $frm_details['status'],'vendor_remark' => $frm_details['vendor_remark']);

                if($frm_details['status'] == "insufficiency" || $frm_details['status'] == "clear" || $frm_details['status'] == "possible match")
                {
                   $field_array['modified_by']  = $this->vendor_info['vendors_id'];
                   $field_array['modified_on']  = date(DB_DATE_FORMAT);
                }
                
                $result= $this->courtver_model->update('view_vendor_master_log',array_map('strtolower',$field_array),array('trasaction_id' => $frm_details['transaction_id']));
                
                $folder_name = "vendor_file";
                $file_upload_path = SITE_BASE_PATH.COURT_VERIFICATION.$folder_name;
                if(!folder_exist($file_upload_path)) {
                    mkdir($file_upload_path,0777,true);
                }else if(!is_writable($file_upload_path)) {
                    array_push($error_msgs,'Problem while uploading');
                }
                
               $config_array = array('file_upload_path' => $file_upload_path,
                                     'file_permission' => 'jpeg|jpg|png|tiff|pdf',
                                     'file_size' => BULK_UPLOAD_MAX_SIZE_MB*2000,
                                     'view_venor_master_log_id' =>  $frm_details['update_id'],
                                     'component_tbl_id' => 5,
                                     'status' => 1 );
                
                if($_FILES['attchments_file']['name'][0] != '')
                {
                    $config_array['files_count'] = count($_FILES['attchments_file']['name']);
                    $config_array['file_data'] = $_FILES['attchments_file'];
                    
                    $retunr_de = $this->file_upload_library->file_upload_multiple_vendor($config_array, true);
                     
                    if(!empty($retunr_de['success'])) {
                        $this->common_model->common_insert_batch('view_vendor_master_log_file',$retunr_de['success']);
                    }   
                }

                if(($frm_details['generate'] == "1") && ($frm_details['status'] == "clear"))
                {
                    $file_array = $error_msgs =array();
                    $folder_name = "vendor_file";
                    $file_upload_path = SITE_BASE_PATH.COURT_VERIFICATION.$folder_name;
                    if(!folder_exist($file_upload_path)) {
                        mkdir($file_upload_path,0777,true);
                    }else if(!is_writable($file_upload_path)) {
                       array_push($error_msgs,'Problem while uploading');
                    }
                            
                    $file_name =  $frm_details['transaction_id']."_".date(UPLOAD_FILE_DATE_FORMAT) . '.png';

                    $savefile = file_put_contents($file_upload_path."/".$file_name, base64_decode(explode(",", $_POST['img'])[1]));
                   
                    $file_array[] = array(
                                        'real_filename'  =>  $file_name,
                                        'file_name'      => $file_name,
                                        'view_venor_master_log_id' => $frm_details['update_id'],
                                        'component_tbl_id' => 5,
                                        'status' => 1
                                    );
                    $this->common_model->common_insert_batch('view_vendor_master_log_file',$file_array);
               
                    $court_filling_record = array(
                        'view_vendor_master_log_id' => $frm_details['update_id'],
                        'date_of_verification' => convert_display_to_db_date($frm_details['date_of_verification']),
                        'civil_civil_proceed' => $frm_details['civil_civil_proceed'],
                        'civil_high_proceed' => $frm_details['civil_high_proceed'],
                        'criminal_magistrate_proceed' => $frm_details['criminal_magistrate_proceed'],
                        'criminal_sessions_proceed' => $frm_details['criminal_sessions_proceed'],
                        'criminal_high_proceed' => $frm_details['criminal_high_proceed'],
                        'status' => 1,
                        'created_on' => date(DB_DATE_FORMAT),
                    );
                    
                    $result_insert_court =  $this->courtver_model->insert_court_details('court_vendor_details',$court_filling_record);

                }
                
                if($result)
                {
                    $update_activity_log = $this->save_activity_log(array('transaction_id' =>  $frm_details['update_id'],'component' => 'courtver','action' => 'Status change','remark' => 'Status Changed','status' => $frm_details['status'],'created_by'=> $this->vendor_info['id'] , 'created_on' => date(DB_DATE_FORMAT))); 

                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'Record Successfully Updated';

                    $json_array['redirect'] = VENDOR_SITE_URL.'courtver/courtver_insufficiency';

                    //$json_array['active_tab'] = 'addrver';
                }
                else
                {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Something went wrong,please try again';
                }
              
            }
              echo_json($json_array);
        }
     
    }

    public function assign_to_executive()
    {
        $json_array = array();
        if($this->input->is_ajax_request())
        {
            $frm_details = $this->input->post();

            if($frm_details['vendor_executive_list'] != 0 && $frm_details['cases_id'] != "")
            {
                $return =  $this->common_model->update_in('view_vendor_master_log',array('has_case_id' => $frm_details['vendor_executive_list'],'has_assigned_on' => date(DB_DATE_FORMAT)),array('where_id' => $frm_details['cases_id']));
                if($return)
                {
                    $json_array['status'] = SUCCESS_CODE;
                    $json_array['message'] ="Assigned Successfully";

                } else {
                    $json_array['status'] = ERROR_CODE;
                    $json_array['message'] ="Something went wrong,please try again";
                }
                
            } else {
                $json_array['status'] = ERROR_CODE;
                $json_array['message'] ="Select atleast one case";
            }

        } else {

            $json_array['status'] = ERROR_CODE;
            $json_array['message'] ="Access Denied, You don’t have permission to access this page";
        }
        echo_json($json_array);
    }
   
    public function View_vendor_logs()
    {
         $encrypt_id = $this->input->post('id');
         $decrypt_id =  decrypt($encrypt_id);


        $details = $this->courtver_model->select_vendor_result_log(array('component_tbl_id' => "5","component" => "courtver"),$decrypt_id);
     
        $details['attachments'] = $this->courtver_model->select_file(array('id','file_name','real_filename','status'),array('view_venor_master_log_id' => $decrypt_id,'component_tbl_id' => 5));

        $details['attachments_file'] = $this->courtver_model->select_file_from_admin(array('id','file_name','real_filename'),array('courtver_id' =>  $details[0]['court_id'],'status' => 1,'type' => 0));
     
        if($encrypt_id && !empty($details))
        {            
            
            $details['assigned_user_id'] = $this->users_list();
            $details['states'] = $this->get_states();
            $details['details'] = $details[0];
            $details['id'] = $decrypt_id;

            echo $this->load->view('vendor/court_ads_vendor_view',$details,TRUE);            
        }
        else
        {
            echo "<h4>Record Not Found</h4>";
        }

    }

    public function export_court()
    {
        $json_array = array();
        
        if($this->input->is_ajax_request())
        {
            set_time_limit(0);
            
            $component_status = $this->input->post('component_status'); 

            if($component_status == "closed")
            {
               
                $frm_details  =  $this->input->post();

                if(isset($frm_details['filter_by_status']) &&  $frm_details['filter_by_status'] != '')
                { 

                    $filter_status    = $frm_details['filter_by_status'];
                    if($filter_status == "All")
                    {
         
                    $where_condition = "(view_vendor_master_log.final_status = 'closed' or view_vendor_master_log.final_status = 'approve')" ;
                    }
                    elseif ($filter_status == "closed" || $filter_status == "Pending" ) {
                        $where_condition = "view_vendor_master_log.final_status = 'closed'" ;
                    }
                    elseif ($filter_status == "approved"  || $filter_status == "Approved") {
                        $where_condition = "view_vendor_master_log.final_status = 'approve'" ;
                    }
                    
                    
                }
                else
                {
                   $where_condition = "";   
                }

                if(isset($frm_details['start_date']) &&  $frm_details['start_date'] != '' && isset($frm_details['end_date']) &&  $frm_details['end_date'] != '')
                { 

                    $start_date  =  $frm_details['start_date'];
                    $end_date  =  $frm_details['end_date'];
                     
                    $where3 = "DATE_FORMAT(`view_vendor_master_log`.`modified_on`,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";

                    
                }
                else
                {
                  $where3 = "";   
                }


                $all_records = $this->courtver_model->get_all_court_by_vendor_closed(array('view_vendor_master_log.component_tbl_id' => 5,'view_vendor_master_log.component' => 'courtver','courtver.vendor_id' => $this->vendor_info['vendors_id']),$where3,$where_condition);
              
            }
            else
            {
               
              $all_records = $this->courtver_model->get_all_court_by_vendor(array('view_vendor_master_log.final_status' => $component_status,'view_vendor_master_log.component_tbl_id' => 5,'view_vendor_master_log.component' => 'courtver','courtver.vendor_id' => $this->vendor_info['vendors_id'])); 
            }

            require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
            // Create new Spreadsheet object
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

            // Set document properties
            $spreadsheet->getProperties()->setCreator(CRMNAME)
                ->setLastModifiedBy(CRMNAME)
                ->setTitle(CRMNAME)
                ->setSubject('Court records')
                ->setDescription('Court records with their status');

            // add style to the header
            $styleArray = array(
                'font' => array(
                    'bold' => true,
                ),
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ),
                'borders' => array(
                    'top' => array(
                        'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ),
                ),
                'fill' => array(
                    'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => array(
                        'argb' => 'FFA0A0A0',
                    ),
                    'endcolor' => array(
                        'argb' => 'FFFFFFFF',
                    ),
                ),
            );
            $spreadsheet->getActiveSheet()->getStyle('A1:N1')->applyFromArray($styleArray);
            // auto fit column to content
            foreach(range('A','N') as $columnID) {
              $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                  ->setWidth(20);
            }

            // set the names of header cells
            $spreadsheet->setActiveSheetIndex(0)
                
                ->setCellValue("A1",'Vendor Assigned on')
                ->setCellValue("B1",'Component Ref No')
                ->setCellValue("C1",'Transaction No')
                ->setCellValue("D1",'Candidate Name')
                ->setCellValue("E1",'Father Name')
                ->setCellValue("F1",'Date Of Birth')
                ->setCellValue("G1",'Address')
                ->setCellValue("H1",'City')
                ->setCellValue("I1",'Pincode')
                ->setCellValue("J1",'State')
                ->setCellValue("K1",'Vendor') 
                ->setCellValue("L1",'Vendor Remark')
                ->setCellValue("M1",'Vendor Status') 
                ->setCellValue("N1",'Vendor Final Status');                

            // Add some data
            $x= 2;
            foreach($all_records as $all_record){
            

                $spreadsheet->setActiveSheetIndex(0)
                  
                  ->setCellValue("A$x",$all_record['vendor_assign_on'])
                  ->setCellValue("B$x",$all_record['component_ref_no'])
                  ->setCellValue("C$x",$all_record['trasaction_id'])
                  ->setCellValue("D$x",ucwords($all_record['CandidateName']))
                  ->setCellValue("E$x",ucwords($all_record['NameofCandidateFather']))
                  ->setCellValue("F$x",convert_db_to_display_date($all_record['DateofBirth']))
                  ->setCellValue("G$x",$all_record['street_address'])
                  ->setCellValue("H$x",$all_record['city'])
                  ->setCellValue("I$x",$all_record['pincode'])
                  ->setCellValue("J$x",$all_record['state'])
                  ->setCellValue("K$x",$all_record['vendor_name'])
                  ->setCellValue("L$x",$all_record['vendor_remark'])
                  ->setCellValue("M$x",ucwords($all_record['vendor_actual_status']))
                  ->setCellValue("N$x",ucwords($all_record['final_status']));
                 
              $x++;
            }
            // Rename worksheet
            $spreadsheet->getActiveSheet()->setTitle('Court Vendor Records');

            $spreadsheet->setActiveSheetIndex(0);

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment;filename=Court Vendor Records $component_status.xlsx");
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Excel2007');
            
            ob_start();
            $writer->save('php://output');
            $xlsData = ob_get_contents();
            ob_end_clean();

            $json_array['file'] = "data:application/vnd.ms-excel;base64,".base64_encode($xlsData);

            $json_array['file_name'] = "Court Vendor Records $component_status";

            $json_array['message'] = "File downloaded successfully,please check in download folder";

            $json_array['status'] = SUCCESS_CODE;

        }
        else
        {
            $json_array['message'] = "Something went wrong,please try again";
            $json_array['status'] = ERROR_CODE;
        }

        echo_json($json_array);
    }
       
}
?>