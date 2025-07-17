<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Eduver extends MY_Vendor_Cotroller
{    
    function __construct()
    {
        parent::__construct();
        if(!$this->is_vendor_logged_in())
        {
            redirect('vendor/login');
            exit();
        }
        $this->perm_array = array('page_name' => 'eduver');
        $this->assign_options = array('0' => 'Select','1' => 'Assign to Executive');
        $this->load->model(array('vendor/eduver_model'));
        $this->load->library('zip');
    }

    public function eduver_wip()
    {   
        $data['header_title'] = "Education Cases View";

      //  $data['lists'] = $this->eduver_model->education_case_list(array('education_vendor_log.vendor_id' => $this->vendor_info['vendors_id'],'view_vendor_master_log.status =' => 1,'component' => 'eduver'));

       $data['vendor_executive_list'] = $this->eduver_model->get_vendor_assign_users('vendor_executive_login',FALSE,array("`vendor_executive_login`.`status`,`vendor_executive_login`.`id`,`vendor_executive_login`.`email_id`,`vendor_executive_login`.`user_name`,concat(`vendor_executive_login`.`first_name`,' ',`vendor_executive_login`.`last_name`) as fullname,`vendor_executive_login`.`first_name`,`vendor_executive_login`.`last_name`"),array('status' => STATUS_ACTIVE));
       
       
        $this->load->view('vendor/header',$data);
        
        $this->load->view('vendor/eduver_list_wip');

        $this->load->view('vendor/footer');
    }

    public function eduver_insufficiency()
    {   
        $data['header_title'] = "Education Cases View";

      //  $data['lists'] = $this->eduver_model->education_case_list(array('education_vendor_log.vendor_id' => $this->vendor_info['vendors_id'],'view_vendor_master_log.status =' => 1,'component' => 'eduver'));

       $data['vendor_executive_list'] = $this->eduver_model->get_vendor_assign_users('vendor_executive_login',FALSE,array("`vendor_executive_login`.`status`,`vendor_executive_login`.`id`,`vendor_executive_login`.`email_id`,`vendor_executive_login`.`user_name`,concat(`vendor_executive_login`.`first_name`,' ',`vendor_executive_login`.`last_name`) as fullname,`vendor_executive_login`.`first_name`,`vendor_executive_login`.`last_name`"),array('status' => STATUS_ACTIVE));
       
       
        $this->load->view('vendor/header',$data);
        
        $this->load->view('vendor/eduver_list_insufficiency');

        $this->load->view('vendor/footer');
    }
    
    public function eduver_closed()
    {   
        $data['header_title'] = "Education Cases View";

       // $data['lists'] = $this->eduver_model->education_case_list(array('education_vendor_log.vendor_id' => $this->vendor_info['vendors_id'],'view_vendor_master_log.status =' => 1,'component' => 'eduver'));

       $data['vendor_executive_list'] = $this->eduver_model->get_vendor_assign_users('vendor_executive_login',FALSE,array("`vendor_executive_login`.`status`,`vendor_executive_login`.`id`,`vendor_executive_login`.`email_id`,`vendor_executive_login`.`user_name`,concat(`vendor_executive_login`.`first_name`,' ',`vendor_executive_login`.`last_name`) as fullname,`vendor_executive_login`.`first_name`,`vendor_executive_login`.`last_name`"),array('status' => STATUS_ACTIVE));
       
       
        $this->load->view('vendor/header',$data);
        
        $this->load->view('vendor/eduver_list_closed');

        $this->load->view('vendor/footer');


    }

    public function education_view_datatable_assign_wip()
    {
     
        $params = $add_candidates = $data_arry = $columns = array();
         
        $params = $_REQUEST;
       
        $columns = array('eduver_id.id','candidates_info.ClientRefNumber','candidates_info.cmp_ref_no','company_database.coname','candidates_info.CandidateName','candidates_info.caserecddate','verfstatus','city','encry_id','school_college','university_board','state','check_closure_date','education_com_ref','iniated_date');
 
        $where_arry = array();

        $lists = $this->eduver_model->education_case_list(array('education_vendor_log.vendor_id' => $this->vendor_info['vendors_id'],'view_vendor_master_log.status =' => 1,'view_vendor_master_log.final_status =' => 'wip','component' => 'eduver'),$params,$columns);

        $totalRecords = count($this->eduver_model->education_case_list(array('education_vendor_log.vendor_id' => $this->vendor_info['vendors_id'],'view_vendor_master_log.status =' => 1,'view_vendor_master_log.final_status =' => 'wip','component' => 'eduver'),$params,$columns));
    

        $x = 0;
        
        foreach ($lists as $list)
        {
           $data_arry[$x]['checkbox'] = $list['id'];
          // $data_arry[$x]['checkbox'] = "<input type='checkbox' id='select-checkbox' name='case_id' class='case_id' value='".$list['id']."'>";
            $data_arry[$x]['modified_on'] = convert_db_to_display_date($list['modified_on'],DB_DATE_FORMAT,DISPLAY_DATE_FORMAT12);
           
            $data_arry[$x]['CandidateName'] = ucwords($list['CandidateName']);
            $data_arry[$x]['school_college'] = ucwords($list['school_college']);
            $data_arry[$x]['city'] = ucwords($list['city']);
            $data_arry[$x]['university_board'] = ucwords($list['university_board']);
            $data_arry[$x]['state'] = ucwords($list['state']);
            $data_arry[$x]['city'] = ucwords($list['city']);
            $data_arry[$x]['status'] = ucwords($list['final_status']);
            $data_arry[$x]['vendor_executive_id'] = $list['vendor_executive_id'];
            $data_arry[$x]['due_date'] = convert_db_to_display_date($list['due_date']);
            $data_arry[$x]['document'] = '<a href ='.VENDOR_SITE_URL.'eduver/create_zip/'.encrypt($list['education_id']).' class="btn btn-info">Download</a>';
            $data_arry[$x]['encry_id'] =encrypt($list['id']);
            $data_arry[$x]['edu_id'] = array($list['id']);
            $data_arry[$x]['tat_status'] = $list['tat_status'];
            $data_arry[$x]['vendor_list_mode'] = ucwords($list['vendor_list_mode']);
           // $data_arry[$x]['clientname'] = $list['clientname'];
            //$data_arry[$x]['entity_name'] = $list['entity_name'];
            $data_arry[$x]['trasaction_id'] = $list['trasaction_id'];
            $data_arry[$x]['education_com_ref'] = $list['education_com_ref'];
            $data_arry[$x]['client_ref_no'] = $list['client_ref_no'];
            $data_arry[$x]['cmp_ref_no'] = $list['cmp_ref_no'];
                 
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

    public function education_view_datatable_assign_insufficiency()
    {
     
        $params = $add_candidates = $data_arry = $columns = array();
         
        $params = $_REQUEST;
       
        $columns = array('eduver_id.id','candidates_info.ClientRefNumber','candidates_info.cmp_ref_no','company_database.coname','candidates_info.CandidateName','candidates_info.caserecddate','verfstatus','city','encry_id','school_college','university_board','state','check_closure_date','education_com_ref','iniated_date');
 
        $where_arry = array();

        $lists = $this->eduver_model->education_case_list(array('education_vendor_log.vendor_id' => $this->vendor_info['vendors_id'],'view_vendor_master_log.status =' => 1,'view_vendor_master_log.final_status =' => 'insufficiency','component' => 'eduver'),$params,$columns);
        
        $totalRecords = count($this->eduver_model->education_case_list(array('education_vendor_log.vendor_id' => $this->vendor_info['vendors_id'],'view_vendor_master_log.status =' => 1,'view_vendor_master_log.final_status =' => 'insufficiency','component' => 'eduver'),$params,$columns));
        
       
        $x = 0;
        
        foreach ($lists as $list)
        {
           $data_arry[$x]['checkbox'] = $list['id'];
           //$data_arry[$x]['checkbox'] = "<input type='checkbox' id='select-checkbox' name='case_id' class='case_id' value='".$list['id']."'>";
            $data_arry[$x]['modified_on'] = convert_db_to_display_date($list['modified_on'],DB_DATE_FORMAT,DISPLAY_DATE_FORMAT12);
           
            $data_arry[$x]['CandidateName'] = ucwords($list['CandidateName']);
            $data_arry[$x]['school_college'] = ucwords($list['school_college']);
            $data_arry[$x]['city'] = ucwords($list['city']);
            $data_arry[$x]['university_board'] = ucwords($list['university_board']);
            $data_arry[$x]['state'] = ucwords($list['state']);
            $data_arry[$x]['city'] = ucwords($list['city']);
            $data_arry[$x]['status'] = ucwords($list['final_status']);
            $data_arry[$x]['vendor_executive_id'] = $list['vendor_executive_id'];
            $data_arry[$x]['due_date'] = convert_db_to_display_date($list['due_date']);
            $data_arry[$x]['document'] = '<a href ='.VENDOR_SITE_URL.'eduver/create_zip/'.encrypt($list['education_id']).' class="btn btn-info">Download</a>' ;
            $data_arry[$x]['encry_id'] =encrypt($list['id']);
            $data_arry[$x]['tat_status'] = $list['tat_status'];
            $data_arry[$x]['vendor_list_mode'] = ucwords($list['vendor_list_mode']);
          //  $data_arry[$x]['clientname'] = $list['clientname'];
           // $data_arry[$x]['entity_name'] = $list['entity_name'];
            $data_arry[$x]['trasaction_id'] = $list['trasaction_id'];
            $data_arry[$x]['education_com_ref'] = $list['education_com_ref'];
            $data_arry[$x]['client_ref_no'] = $list['client_ref_no'];
            $data_arry[$x]['cmp_ref_no'] = $list['cmp_ref_no'];
                 
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

    public function education_view_datatable_assign_closed()
    {
     
        $params = $add_candidates = $data_arry = $columns = array();
         
        $params = $_REQUEST;
       
        $columns = array('eduver_id.id','candidates_info.ClientRefNumber','candidates_info.cmp_ref_no','company_database.coname','candidates_info.CandidateName','candidates_info.caserecddate','verfstatus','city','encry_id','school_college','university_board','state','check_closure_date','education_com_ref','iniated_date');
 
        $where_arry = array();

        $lists = $this->eduver_model->education_case_list_closed(array('education_vendor_log.vendor_id' => $this->vendor_info['vendors_id'],'view_vendor_master_log.status =' => 1,'component' => 'eduver'),$params,$columns);


        $totalRecords = count($this->eduver_model->education_case_list_closed(array('education_vendor_log.vendor_id' => $this->vendor_info['vendors_id'],'view_vendor_master_log.status =' => 1,'component' => 'eduver'),$params,$columns));
        
    
        $x = 0;
        $list_array = array();
        foreach ($lists as $list)
        {
           $data_arry[$x]['checkbox'] = $list['id'];
            //$list_arrays[]    =  $list['education_id'];
           // $data_arry[$x]['checkbox'] = "<input type='checkbox' id='select-checkbox' name='case_id' class='case_id' value='".$list['id']."'>";
            $data_arry[$x]['modified_on'] = convert_db_to_display_date($list['modified_on'],DB_DATE_FORMAT,DISPLAY_DATE_FORMAT12);
           
            $data_arry[$x]['CandidateName'] = ucwords($list['CandidateName']);
            $data_arry[$x]['school_college'] = ucwords($list['school_college']);
            $data_arry[$x]['city'] = ucwords($list['city']);
            $data_arry[$x]['university_board'] = ucwords($list['university_board']);
            $data_arry[$x]['state'] = ucwords($list['state']);
            $data_arry[$x]['city'] = ucwords($list['city']);
            $data_arry[$x]['status'] = ucwords($list['final_status']);
            $data_arry[$x]['vendor_executive_id'] = $list['vendor_executive_id'];
            $data_arry[$x]['due_date'] = convert_db_to_display_date($list['due_date']);
            $data_arry[$x]['document'] = '<a href ='.VENDOR_SITE_URL.'eduver/create_zip/'.encrypt($list['education_id']).' class="btn btn-info">Download</a>' ;
           
            $data_arry[$x]['encry_id'] =encrypt($list['id']);
            $data_arry[$x]['tat_status'] = $list['tat_status'];
            $data_arry[$x]['vendor_list_mode'] = ucwords($list['vendor_list_mode']);
           // $data_arry[$x]['clientname'] = $list['clientname'];
           // $data_arry[$x]['entity_name'] = $list['entity_name'];
            $data_arry[$x]['trasaction_id'] = $list['trasaction_id'];
            $data_arry[$x]['education_com_ref'] = $list['education_com_ref'];
            $data_arry[$x]['client_ref_no'] = $list['client_ref_no'];
            $data_arry[$x]['cmp_ref_no'] = $list['cmp_ref_no'];
                 
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
      //  $eduessver_details = $this->eduver_model->get_eduess_details(array('eduver.id' => decrypt($addsver_id)));
        $details['header_title'] = "education Edit";

        $details['details'] = $this->eduver_model->select_vendor_result_log(array('component_tbl_id' => "1","component" => "eduver"),$trans_id);
       // $details['states'] = $this->get_states();
  

        if($trans_id1 && !empty($details))
        {
            

            $this->load->view('vendor/header',$details);

            $this->load->view('vendor/eduver_edit');

            $this->load->view('vendor/footer');
        }
        else
        {
            show_404();
        }
    }

    public function update_education_wip()
    {
        if($this->input->is_ajax_request())

        {

            $frm_details = $this->input->post();

       
            $this->form_validation->set_rules('transaction_id', 'ID', 'required');

            $this->form_validation->set_rules('vendor_remark', 'Vendor Remark', 'required');

            /*if($frm_details['status'] == "closed")
            {
                
                if(empty($_FILES['attchments_file']['name'][0])  && empty($_POST['attchments']))
                {

                   
                   $this->form_validation->set_rules('attchments_file', 'Attachment file', 'required');
                 }
            }*/

            if ($this->form_validation->run() == FALSE)
            {


                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('','');


            }
            else
            {

                $frm_details = $this->input->post();

                $field_array = array('vendor_actual_status' => $frm_details['status'],'final_status' => $frm_details['status'],'vendor_remark' => $frm_details['vendor_remark'], 'modified_on' => date(DB_DATE_FORMAT));
                
                $result= $this->eduver_model->update('view_vendor_master_log',array_map('strtolower',$field_array),array('trasaction_id' => $frm_details['transaction_id']));
                
                $result_education = $this->eduver_model->update('education_result',array('verified_by' => $frm_details['verified_by'],'verifier_designation' => $frm_details['verifier_designation'],'verifier_contact_details' => $frm_details['verifier_contact_details']),array('education_id' => $frm_details['education_id']));
               
               $result_ver_education = $this->eduver_model->update('education_ver_result',array('verified_by' => $frm_details['verified_by'],'verifier_designation' => $frm_details['verifier_designation'],'verifier_contact_details' => $frm_details['verifier_contact_details']),array('id' => $frm_details['education_ver_id']));

                $folder_name = "vendor_file";
                $file_upload_path = SITE_BASE_PATH.EDUCATION.$folder_name;
                if(!folder_exist($file_upload_path)) {
                    mkdir($file_upload_path,0777);
                }else if(!is_writable($file_upload_path)) {
                    array_push($error_msgs,'Problem while uploading');
                }


               $config_array = array('file_upload_path' => $file_upload_path,
                                     'file_permission' => 'jpeg|jpg|png|pdf|tiff',
                                     'file_size' => BULK_UPLOAD_MAX_SIZE_MB*2000,
                                     'view_venor_master_log_id' => $frm_details['update_id'],
                                     'component_tbl_id' => 3,
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

                
                if($result)
                {
                    $update_activity_log = $this->save_activity_log(array('transaction_id' =>  $frm_details['update_id'],'component' => 'eduver','action' => 'Status change','remark' => 'Status Changed','status' => $frm_details['status'],'created_by'=> $this->vendor_info['id'] , 'created_on' => date(DB_DATE_FORMAT))); 


                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'Record Successfully Updated';

                    $json_array['redirect'] = VENDOR_SITE_URL.'eduver/eduver_wip';

                    //$json_array['active_tab'] = 'eduver';
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
    
    public function update_education_insufficiency()
    {

        if($this->input->is_ajax_request())

        {

          $frm_details = $this->input->post();


            $this->form_validation->set_rules('transaction_id', 'ID', 'required');

            $this->form_validation->set_rules('vendor_remark', 'Vendor Remark', 'required');

         /*  if($frm_details['status'] == "closed")
            {
                
                if(empty($_FILES['attchments_file']['name'][0])  && empty($_POST['attchments']))
                {

                $this->form_validation->set_rules('attchments_file', 'Attachment file', 'required');
                }

            }*/


            if ($this->form_validation->run() == FALSE)
            {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('','');
            }
            else
            {

                $frm_details = $this->input->post();

   
                $field_array = array('vendor_actual_status' => $frm_details['status'],'final_status' => $frm_details['status'],'vendor_remark' => $frm_details['vendor_remark'],'modified_on' => date(DB_DATE_FORMAT));
                
                $result= $this->eduver_model->update('view_vendor_master_log',array_map('strtolower',$field_array),array('trasaction_id' => $frm_details['transaction_id']));
                
                $folder_name = "vendor_file";
                $file_upload_path = SITE_BASE_PATH.EDUCATION.$folder_name;
                if(!folder_exist($file_upload_path)) {
                    mkdir($file_upload_path,0777);
                }else if(!is_writable($file_upload_path)) {
                    array_push($error_msgs,'Problem while uploading');
                }


                $config_array = array('file_upload_path' => $file_upload_path,
                                     'file_permission' => 'jpeg|jpg|png|pdf|tiff',
                                     'file_size' => BULK_UPLOAD_MAX_SIZE_MB*2000,
                                     'view_venor_master_log_id' => $frm_details['update_id'],
                                     'component_tbl_id' => 3,
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

                if($result)
                {  
                    $update_activity_log = $this->save_activity_log(array('transaction_id' =>  $frm_details['update_id'],'component' => 'eduver','action' => 'Status change','remark' => 'Status Changed','status' => $frm_details['status'],'created_by'=> $this->vendor_info['id'] , 'created_on' => date(DB_DATE_FORMAT))); 

                      
                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'Record Successfully Updated';

                    $json_array['redirect'] = VENDOR_SITE_URL.'eduver/eduver_insufficiency';

                    //$json_array['active_tab'] = 'eduver';
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

    public function university_list()
    {
        $this->load->model('university_model');

        $results = $this->university_model->select(FALSE,array('universityname','id'),array());

        $university_arry = array();

        $university_arry[0] = 'Select University';

        foreach ($results as $key => $value) {
            $university_arry[$value['universityname']] = ucwords($value['universityname']);
        }

        return $university_arry;
    }

    public function qualification_list()
    {
        $this->load->model('qualification_model');

        $results = $this->qualification_model->select(FALSE,array('qualification','id'),array());

        $university_arry = array();

        $university_arry[0] = 'Select Qualification';

        foreach ($results as $key => $value) {
            $university_arry[$value['qualification']] = ucwords($value['qualification']);
        }

        return $university_arry;
    }

   
    public function View_vendor_logs()
    {
        $encrypt_id = $this->input->post('id');
        $decrypt_id =  decrypt($encrypt_id);

     
        $details = $this->eduver_model->select_vendor_result_log(array('component_tbl_id' => "3","component" => "eduver"),$decrypt_id);   

        $details['attachments'] = $this->eduver_model->select_file(array('id','file_name','real_filename'),array('view_venor_master_log_id' => $decrypt_id,'status' => 1,'component_tbl_id' => 3));

        $details['attachments_file'] = $this->eduver_model->select_file_from_admin(array('id','file_name','real_filename'),array('education_id' =>  $details[0]['education_id'],'status' => 1));

        if($encrypt_id && !empty($details))
        {            
            

            $details['states'] = $this->get_states();
            $details['details'] = $details[0];
            $details['id'] = $decrypt_id;
            $details['assigned_user_id'] = $this->users_list();

            $details['university'] = $this->university_list();
            $details['qualification'] = $this->qualification_list();

            echo $this->load->view('vendor/education_ads_vendor_view',$details,TRUE);            
        }
        else
        {
            echo "<h4>Record Not Found</h4>";
        }

    }
    
    public function export_education()
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


                $all_records = $this->eduver_model->get_all_education_by_vendor_closed(array('view_vendor_master_log.component_tbl_id' => 3,'view_vendor_master_log.component' => 'eduver','education.vendor_id' => $this->vendor_info['vendors_id']),$where3,$where_condition);
              
            }
            else
            {
               
              $all_records = $this->eduver_model->get_all_education_by_vendor(array('view_vendor_master_log.final_status' => $component_status,'view_vendor_master_log.component_tbl_id' => 3,'view_vendor_master_log.component' => 'eduver','education.vendor_id' => $this->vendor_info['vendors_id'])); 
            }

           

            require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
            // Create new Spreadsheet object
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

            // Set document properties
            $spreadsheet->getProperties()->setCreator(CRMNAME)
                ->setLastModifiedBy(CRMNAME)
                ->setTitle(CRMNAME)
                ->setSubject('Education records')
                ->setDescription('Education records with their status');

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
                ->setCellValue("A1",REFNO)
                ->setCellValue("B1",'Component Ref No')
                ->setCellValue("C1",'Transaction No')
                ->setCellValue("D1",'Candidate Name')
                ->setCellValue("E1",'Fathers Name')
                ->setCellValue("F1",'University')
                ->setCellValue("G1",'Vendor')
                ->setCellValue("H1",'Vendor Status')
                ->setCellValue("I1",'Vendor Assigned on')
                ->setCellValue("J1",'Closure Date')
                ->setCellValue("K1",'Insuff Raised Date')
                ->setCellValue("L1",'Insuff Clear Date')
                ->setCellValue("M1",'Insuff Remark')
                ->setCellValue("N1",'Vendor Remark');
            // Add some data
            $x= 2;
            foreach($all_records as $all_record){
                
                $closuredate = ($all_record['filter_status'] == "Closed") ? convert_db_to_display_date($all_record['closuredate']) : "NA";
                $insuff_remarks =  $all_record['insuff_raise_remark'];

                $spreadsheet->setActiveSheetIndex(0)
                  
                  ->setCellValue("A$x",$all_record['cmp_ref_no'])
                  ->setCellValue("B$x",$all_record['education_com_ref'])
                  ->setCellValue("C$x",$all_record['transaction_id'])
                  ->setCellValue("D$x",ucwords($all_record['CandidateName']))
                  ->setCellValue("E$x",ucwords($all_record['NameofCandidateFather']))
                  ->setCellValue("F$x",$all_record['university_name'])
                  ->setCellValue("G$x",$all_record['vendor_name'])
                  ->setCellValue("H$x",ucwords($all_record['vendor_status']))
                  ->setCellValue("I$x",convert_db_to_display_date($all_record['vendor_assgined_on'],DB_DATE_FORMAT,DISPLAY_DATE_FORMAT12))
                  ->setCellValue("J$x",$closuredate)
                  ->setCellValue("K$x",$all_record['insuff_raised_date'])
                  ->setCellValue("L$x",$all_record['insuff_clear_date'])
                  ->setCellValue("M$x",$insuff_remarks)
                  ->setCellValue("N$x",$all_record['vendor_remark']);

              $x++;
            }
            // Rename worksheet
            $spreadsheet->getActiveSheet()->setTitle('Education Vendor Records');

            $spreadsheet->setActiveSheetIndex(0);

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment;filename=Education Vendor Records $component_status.xlsx");
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

            $json_array['file_name'] = "Education Vendor Records $component_status";

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

    public function export_education_doc()
    {
      
        
        $this->load->library('zip');

            set_time_limit(0);
            
            $component_status = $this->input->post('component_status_doc'); 

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


                $all_records = $this->eduver_model->get_all_education_by_vendor_closed(array('view_vendor_master_log.component_tbl_id' => 3,'view_vendor_master_log.component' => 'eduver','education.vendor_id' => $this->vendor_info['vendors_id']),$where3,$where_condition);
              
            }
            else
            {
             
               $all_records = $this->eduver_model->get_all_education_by_vendor(array('view_vendor_master_log.final_status' => $component_status,'view_vendor_master_log.component_tbl_id' => 3,'view_vendor_master_log.component' => 'eduver','education.vendor_id' => $this->vendor_info['vendors_id'])); 
            }
          
         foreach($all_records as $all_record){
           

             $attachment = $all_record['attachments'];
        
             $attachment_explode = explode('||',$attachment);
             $counter = 1;
              foreach ($attachment_explode as  $value) {
                 $file_info = pathinfo($value);
                 $extension = $file_info['extension'];

                 $file_upload_path = SITE_BASE_PATH . EDUCATION . $all_record['clientid'].'/'.$value;
                
                 $this->zip->read_file(  SITE_BASE_PATH . EDUCATION . $all_record['clientid'].'/'.$value,$all_record['education_com_ref']."_".$counter. '.' . $extension);

                 $counter++;

              }

            }
            
            require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
            // Create new Spreadsheet object
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

            // Set document properties
            $spreadsheet->getProperties()->setCreator(CRMNAME)
                ->setLastModifiedBy(CRMNAME)
                ->setTitle(CRMNAME)
                ->setSubject('Education records')
                ->setDescription('Education records with their status');

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
                ->setCellValue("A1",REFNO)
                ->setCellValue("B1",'Component Ref No')
                ->setCellValue("C1",'Transaction No')
                ->setCellValue("D1",'Candidate Name')
                ->setCellValue("E1",'Fathers Name')
                ->setCellValue("F1",'University')
                ->setCellValue("G1",'Vendor')
                ->setCellValue("H1",'Verify Status')
                ->setCellValue("I1",'Vendor Assigned on')
                ->setCellValue("J1",'Closure Date')
                ->setCellValue("K1",'Insuff Raised Date')
                ->setCellValue("L1",'Insuff Clear Date')
                ->setCellValue("M1",'Insuff Remark')
                ->setCellValue("N1",'Vendor Remark');
            // Add some data
            $x= 2;
            foreach($all_records as $all_record){
                
                $closuredate = ($all_record['filter_status'] == "Closed") ? convert_db_to_display_date($all_record['closuredate']) : "NA";
                $insuff_remarks =  $all_record['insuff_raise_remark'];

                $spreadsheet->setActiveSheetIndex(0)
                  
                  ->setCellValue("A$x",$all_record['cmp_ref_no'])
                  ->setCellValue("B$x",$all_record['education_com_ref'])
                  ->setCellValue("C$x",$all_record['transaction_id'])
                  ->setCellValue("D$x",ucwords($all_record['CandidateName']))
                  ->setCellValue("E$x",ucwords($all_record['NameofCandidateFather']))
                  ->setCellValue("F$x",$all_record['university_name'])
                  ->setCellValue("G$x",$all_record['vendor_name'])
                  ->setCellValue("H$x",ucwords($all_record['verifiers_stamp_status']))
                  ->setCellValue("I$x",convert_db_to_display_date($all_record['vendor_assgined_on'],DB_DATE_FORMAT,DISPLAY_DATE_FORMAT12))
                  ->setCellValue("J$x",$closuredate)
                  ->setCellValue("K$x",$all_record['insuff_raised_date'])
                  ->setCellValue("L$x",$all_record['insuff_clear_date'])
                  ->setCellValue("M$x",$insuff_remarks)
                  ->setCellValue("N$x",$all_record['vendor_remark']);

              $x++;
            }
            // Rename worksheet
            $spreadsheet->getActiveSheet()->setTitle('Education Vendor Records');

            $spreadsheet->setActiveSheetIndex(0);

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment;filename=Education Vendor Records $component_status.xlsx");
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

            $this->zip->add_data("Education Vendor Records ".$component_status.'.'.'xls',$xlsData);

            //$this->zip->close();
            
            $this->zip->download(''.time().'.zip');


       
    }
    
    public function create_zip($education_id)
    {
    
        $education_id  =  decrypt($education_id);

        $select_client_id = $this->eduver_model->select_client_id(array('clientid','education_com_ref'),array('id' =>  $education_id)); 
        $attachments_files = $this->eduver_model->select_file_from_admin(array('id','file_name','real_filename'),array('education_id' =>  $education_id,'status' => 1)); 
 
        if(!empty($attachments_files))
        {
         $this->load->library('zip');

          foreach($attachments_files as $attachments_file)
          {
      
             $name = $select_client_id[0]['education_com_ref']."_".$attachments_file['file_name'];
             $data = file_get_contents(SITE_BASE_PATH.EDUCATION.$select_client_id[0]['clientid']."/".$attachments_file['file_name']);

            $this->zip->add_data($name,$data);

          }
         $this->zip->download(''.time().'.zip');
        }
 
    }


    public function create_all_zip()
    {
    
        $education_id = $this->input->post('education_id');

        $filename = ''.time().'.zip';
   
        if($education_id != "")
        {
          
            $education_ids =  explode(",", $education_id);

            foreach ($education_ids as $education_id) 
            {
        

                $select_client_id = $this->eduver_model->select_client_id(array('clientid','education_com_ref'),array('id' =>  $education_id)); 
     
                $attachments_files = $this->eduver_model->select_file_from_admin(array('id','file_name','real_filename'),array('education_id' =>  $education_id,'status' => 1)); 
                 
                if(!empty($attachments_files))
                {
                    $this->load->library('zip');

                    foreach($attachments_files as $attachments_file)
                    {
                        $folder_dir = $select_client_id[0]['education_com_ref'];
                      
                        if(!folder_exist($folder_dir)) {
                          $this->zip->add_dir($folder_dir);
                        }
           
                        $name = $folder_dir.'/'.$select_client_id[0]['education_com_ref']."_".$attachments_file['file_name'];
                        $data = file_get_contents(SITE_BASE_PATH.EDUCATION.$select_client_id[0]['clientid']."/".$attachments_file['file_name']);

                        $this->zip->add_data($name,$data);
                     
                    }
                        
                }
                           
            }
            $this->zip->download( $filename); 
        }

    }

    /* public function reject_vendor_eduess()
    {
       
        if($this->input->is_ajax_request())
        {

          //if( $this->permission['access_eduess_aq_allow'] == 1 )
          // {
            $frm_details = $this->input->post();
         
           
            if($frm_details['cases_id'] != "")
            {
                $list = explode(',',$frm_details['cases_id']);



                 
                $update_counter = 0;
                foreach ($list as $key => $value) {
                   
                   $update =  $this->eduver_model->upload_vendor_reject('view_vendor_master_log',array('status' => 4,'remarks' => $frm_details['reject_reason'],'vendor_rejected_by' => $this->vendor_info['id'],'vendor_reject_on' => date(DB_DATE_FORMAT),'modified_by' => $this->vendor_info['id'],'modified_on' => date(DB_DATE_FORMAT)),array('id' => $value));

                    $eduess_vendor_log_id = $this->eduver_model->select_eduess_vendor_log_id('view_vendor_master_log',array('id' => $value));

                    $update_eduess =  $this->eduver_model->upload_vendor_reject('eduess_vendor_log',array('status' => 4,'remarks' => $frm_details['reject_reason'],'modified_by' => $this->vendor_info['id'],'modified_on' => date(DB_DATE_FORMAT)),array('id' => $eduess_vendor_log_id[0]['case_id']));



                   $insert_activity_eduess =  $this->eduver_model->insert_activity_vendor_reject('eduess_activity_data',array('status' => 4,'remarks' => $frm_details['reject_reason'],'modified_by' => $this->vendor_info['id'],'modified_on' => date(DB_DATE_FORMAT)),array('id' => $eduess_vendor_log_id[0]['case_id']));

                    if($update) {
                        $update_counter++;
                        //$return =  $this->eduessver_model->save(array('vendor_id' => 0,'vendor_assgined_on' => NULL),array('id' => $value));
                    }
                }



                $result_get_count_plus= $this->common_model->get_count(array('controllers' => 'assign_add'));
                                      
                $total_get_count_plus = $result_get_count_plus + count($list);
 
                $result_update_count_plus= $this->common_model->update_count(array('count' => $total_get_count_plus),array('controllers' => 'assign_add'));

                $result_get_count_minus= $this->common_model->get_count(array('controllers' => 'eduess/approval_queue'));
                                      
                $total_get_count_minus = $result_get_count_minus - count($list);
 
                $result_update_count_minus= $this->common_model->update_count(array('count' => $total_get_count_minus),array('controllers' => 'eduess/approval_queue'));

                if($update_counter) {

                    $json_array['status'] = SUCCESS_CODE;
                    $json_array['message'] = $update_counter." of ".count($list)." Rejected Successfully";

                } else {
                    $json_array['status'] = ERROR_CODE;
                    $json_array['message'] ="Something went wrong,please try again";
                }

            } 
        
            else 
            {
                $json_array['status'] = ERROR_CODE;
                $json_array['message'] ="Select atleast one case";
            }

          } 

       
    
        else {

            $json_array['status'] = ERROR_CODE;
            $json_array['message'] ="Access Denied, You don’t have permission to access this page";
        }

        echo_json($json_array);
    }*/
   
   }

?>