<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Identity extends MY_Vendor_Cotroller
{    
    function __construct()
    {
        parent::__construct();
        if(!$this->is_vendor_logged_in())
        {
            redirect('vendor/login');
            exit();
        }
        $this->perm_array = array('page_name' => 'identity');
          $this->assign_options = array('0' => 'Select','1' => 'Assign to Executive');
        $this->load->model(array('vendor/identity_model'));
    }

    public function identity_wip()
    {   
        $data['header_title'] = "Identity Cases View";

      //  $data['lists'] = $this->identity_model->identity_case_list(array('identity_vendor_log.vendor_id' => $this->vendor_info['vendors_id'],'view_vendor_master_log.status =' => 1,'component' => 'identity'));

       $data['vendor_executive_list'] = $this->identity_model->get_vendor_assign_users('vendor_executive_login',FALSE,array("`vendor_executive_login`.`status`,`vendor_executive_login`.`id`,`vendor_executive_login`.`email_id`,`vendor_executive_login`.`user_name`,concat(`vendor_executive_login`.`first_name`,' ',`vendor_executive_login`.`last_name`) as fullname,`vendor_executive_login`.`first_name`,`vendor_executive_login`.`last_name`"),array('status' => STATUS_ACTIVE));
       
       
        $this->load->view('vendor/header',$data);
        
        $this->load->view('vendor/identity_list_wip');

        $this->load->view('vendor/footer');
    }

     public function identity_insufficiency()
    {   
        $data['header_title'] = "Identity Cases View";

       // $data['lists'] = $this->identity_model->identity_case_list(array('identity_vendor_log.vendor_id' => $this->vendor_info['vendors_id'],'view_vendor_master_log.status =' => 1,'component' => 'identity'));

       $data['vendor_executive_list'] = $this->identity_model->get_vendor_assign_users('vendor_executive_login',FALSE,array("`vendor_executive_login`.`status`,`vendor_executive_login`.`id`,`vendor_executive_login`.`email_id`,`vendor_executive_login`.`user_name`,concat(`vendor_executive_login`.`first_name`,' ',`vendor_executive_login`.`last_name`) as fullname,`vendor_executive_login`.`first_name`,`vendor_executive_login`.`last_name`"),array('status' => STATUS_ACTIVE));
       
       
        $this->load->view('vendor/header',$data);
        
        $this->load->view('vendor/identity_list_insufficiency');

        $this->load->view('vendor/footer');
    }
    
     public function identity_closed()
    {   
        $data['header_title'] = "Identity Cases View";

      //  $data['lists'] = $this->identity_model->identity_case_list(array('identity_vendor_log.vendor_id' => $this->vendor_info['vendors_id'],'view_vendor_master_log.status =' => 1,'component' => 'identity'));

       $data['vendor_executive_list'] = $this->identity_model->get_vendor_assign_users('vendor_executive_login',FALSE,array("`vendor_executive_login`.`status`,`vendor_executive_login`.`id`,`vendor_executive_login`.`email_id`,`vendor_executive_login`.`user_name`,concat(`vendor_executive_login`.`first_name`,' ',`vendor_executive_login`.`last_name`) as fullname,`vendor_executive_login`.`first_name`,`vendor_executive_login`.`last_name`"),array('status' => STATUS_ACTIVE));
       
       
        $this->load->view('vendor/header',$data);
        
        $this->load->view('vendor/identity_list_closed');

        $this->load->view('vendor/footer');


    }

    public function identity_view_datatable_assign_wip()
    {
     
        $params = $add_candidates = $data_arry = $columns = array();
         
        $params = $_REQUEST;
       
        $columns = array('identity_id.id','candidates_info.ClientRefNumber','candidates_info.cmp_ref_no','company_database.coname','candidates_info.CandidateName','candidates_info.caserecddate','verfstatus','city','encry_id','address','pincode','state','check_closure_date','identity_com_ref','iniated_date');
 
        $where_arry = array();

        $lists = $this->identity_model->identity_case_list(array('identity_vendor_log.vendor_id' => $this->vendor_info['vendors_id'],'view_vendor_master_log.status =' => 1,'view_vendor_master_log.final_status =' => 'wip','component' => 'identity'),$params,$columns);

        $totalRecords = count($this->identity_model->identity_case_list(array('identity_vendor_log.vendor_id' => $this->vendor_info['vendors_id'],'view_vendor_master_log.status =' => 1,'view_vendor_master_log.final_status =' => 'wip','component' => 'identity'),$params,$columns));
        
      
        $x = 0;
        
        foreach ($lists as $list)
        {
         //  $data_arry[$x]['checkbox'] = $list['id'];
           $data_arry[$x]['checkbox'] = "<input type='checkbox' id='select-checkbox' name='case_id' class='case_id' value='".$list['id']."'>";
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
           // $data_arry[$x]['encry_id'] =VENDOR_SITE_URL."addrver/view_details/".encrypt($list['id']);
            $data_arry[$x]['encry_id'] =encrypt($list['id']);
            $data_arry[$x]['tat_status'] = $list['tat_status'];
            $data_arry[$x]['vendor_list_mode'] = ucwords($list['vendor_list_mode']);
          //  $data_arry[$x]['clientname'] = $list['clientname'];
          //  $data_arry[$x]['entity_name'] = $list['entity_name'];
            $data_arry[$x]['trasaction_id'] = $list['trasaction_id'];
            $data_arry[$x]['identity_com_ref'] = $list['identity_com_ref'];
            $data_arry[$x]['client_ref_no'] = $list['client_ref_no'];
                 
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

    public function identity_view_datatable_assign_insufficiency()
    {
     
        $params = $add_candidates = $data_arry = $columns = array();
         
        $params = $_REQUEST;
       
        $columns = array('identity_id.id','candidates_info.ClientRefNumber','candidates_info.cmp_ref_no','company_database.coname','candidates_info.CandidateName','candidates_info.caserecddate','verfstatus','city','encry_id','address','pincode','state','check_closure_date','identity_com_ref','iniated_date');
 
        $where_arry = array();

        $lists = $this->identity_model->identity_case_list(array('identity_vendor_log.vendor_id' => $this->vendor_info['vendors_id'],'view_vendor_master_log.status =' => 1,'view_vendor_master_log.final_status =' => 'insufficiency','component' => 'identity'),$params,$columns);

        $totalRecords = count($this->identity_model->identity_case_list(array('identity_vendor_log.vendor_id' => $this->vendor_info['vendors_id'],'view_vendor_master_log.status =' => 1,'view_vendor_master_log.final_status =' => 'insufficiency','component' => 'identity'),$params,$columns));
        
         
        $x = 0;
        
        foreach ($lists as $list)
        {
         //  $data_arry[$x]['checkbox'] = $list['id'];
           $data_arry[$x]['checkbox'] = "<input type='checkbox' id='select-checkbox' name='case_id' class='case_id' value='".$list['id']."'>";
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
           // $data_arry[$x]['encry_id'] =VENDOR_SITE_URL."addrver/view_details/".encrypt($list['id']);
            $data_arry[$x]['encry_id'] =encrypt($list['id']);
            $data_arry[$x]['tat_status'] = $list['tat_status'];
            $data_arry[$x]['vendor_list_mode'] = ucwords($list['vendor_list_mode']);
          //  $data_arry[$x]['clientname'] = $list['clientname'];
          //  $data_arry[$x]['entity_name'] = $list['entity_name'];
            $data_arry[$x]['trasaction_id'] = $list['trasaction_id'];
            $data_arry[$x]['identity_com_ref'] = $list['identity_com_ref'];
            $data_arry[$x]['client_ref_no'] = $list['client_ref_no'];
                 
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

    public function identity_view_datatable_assign_closed()
    {
     
        $params = $add_candidates = $data_arry = $columns = array();
         
        $params = $_REQUEST;
       
        $columns = array('identity_id.id','candidates_info.ClientRefNumber','candidates_info.cmp_ref_no','company_database.coname','candidates_info.CandidateName','candidates_info.caserecddate','verfstatus','city','encry_id','address','pincode','state','check_closure_date','identity_com_ref','iniated_date');
 
        $where_arry = array();

        $lists = $this->identity_model->identity_case_list_closed(array('identity_vendor_log.vendor_id' => $this->vendor_info['vendors_id'],'view_vendor_master_log.status =' => 1,'view_vendor_master_log.final_status =' => 'closed','component' => 'identity'),$params,$columns);

        $totalRecords = count($this->identity_model->identity_case_list_closed(array('identity_vendor_log.vendor_id' => $this->vendor_info['vendors_id'],'view_vendor_master_log.status =' => 1,'view_vendor_master_log.final_status =' => 'closed','component' => 'identity'),$params,$columns));
        
    
        $x = 0;
        
        foreach ($lists as $list)
        {
         //  $data_arry[$x]['checkbox'] = $list['id'];
            $data_arry[$x]['checkbox'] = "<input type='checkbox' id='select-checkbox' name='case_id' class='case_id' value='".$list['id']."'>";
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
           // $data_arry[$x]['encry_id'] =VENDOR_SITE_URL."addrver/view_details/".encrypt($list['id']);
            $data_arry[$x]['encry_id'] =encrypt($list['id']);
            $data_arry[$x]['tat_status'] = $list['tat_status'];
            $data_arry[$x]['vendor_list_mode'] = ucwords($list['vendor_list_mode']);
          //  $data_arry[$x]['clientname'] = $list['clientname'];
          //  $data_arry[$x]['entity_name'] = $list['entity_name'];
            $data_arry[$x]['trasaction_id'] = $list['trasaction_id'];
            $data_arry[$x]['identity_com_ref'] = $list['identity_com_ref'];
            $data_arry[$x]['client_ref_no'] = $list['client_ref_no'];
                 
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
        $details['header_title'] = "Identity Edit";

        $details['details'] = $this->identity_model->select_vendor_result_log(array('component_tbl_id' => "9","component" => "identity"),$trans_id);
       // $details['states'] = $this->get_states();
  

        if($trans_id1 && !empty($details))
        {
            

            $this->load->view('vendor/header',$details);

            $this->load->view('vendor/identity_edit');

            $this->load->view('vendor/footer');
        }
        else
        {
            show_404();
        }
    }

    public function update_identity_wip()
    {

        if($this->input->is_ajax_request())

        {

            $frm_details = $this->input->post();

       
            $this->form_validation->set_rules('transaction_id', 'ID', 'required');

            if($frm_details['status'] == "closed")
            {

                if(empty($_FILES['attchments_file']['name'][0]))
                {

                   
                   $this->form_validation->set_rules('attchments_file', 'Attachment file', 'required');
                 }
            }

            if ($this->form_validation->run() == FALSE)
            {


                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('','');


            }
            else
            {

                 $folder_name = "vendor_file";
                $file_upload_path = SITE_BASE_PATH.IDENTITY.$folder_name;
                if(!folder_exist($file_upload_path)) {
                    mkdir($file_upload_path,0777);
                }else if(!is_writable($file_upload_path)) {
                    array_push($error_msgs,'Problem while uploading');
                }


                $frm_details = $this->input->post();

   
                $field_array = array('vendor_actual_status' => $frm_details['status'],'final_status' => $frm_details['status'] );
                
                $result= $this->identity_model->update('view_vendor_master_log',array_map('strtolower',$field_array),array('trasaction_id' => $frm_details['transaction_id']));
                
               $config_array = array('file_upload_path' => $file_upload_path,'file_permission' => 'jpeg|jpg|png|tiff','file_size' => BULK_UPLOAD_MAX_SIZE_MB*2000,'view_venor_master_log_id' =>  $frm_details['update_id'],'component_tbl_id' => 9,'status' => 1 );
                
                if($_FILES['attchments_file']['name'][0] != '')
                {


                    $config_array['files_count'] = count($_FILES['attchments_file']['name']);
                    $config_array['file_data'] = $_FILES['attchments_file'];
                    
                    $retunr_de = $this->file_upload_multiple_vendor($config_array); 
                    if(!empty($retunr_de)) {
                        $this->common_model->common_insert_batch('view_vendor_master_log_file',$retunr_de['success']);
                    }   
                }

                    
                if($result)
                {
                    $update_activity_log = $this->save_activity_log(array('transaction_id' =>  $frm_details['update_id'],'component' => 'identity','action' => 'Status change','remark' => 'Status Changed','status' => $frm_details['status'],'created_by'=> $this->vendor_info['id'] , 'created_on' => date(DB_DATE_FORMAT)));

                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'Record Successfully Updated';

                    $json_array['redirect'] = VENDOR_SITE_URL.'identity/identity_wip';

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
    
    public function update_identity_insufficiency()
    {

        if($this->input->is_ajax_request())

        {

          $frm_details = $this->input->post();


            $this->form_validation->set_rules('transaction_id', 'ID', 'required');

           if($frm_details['status'] == "closed")
            {
                
                if(empty($_FILES['attchments_file']['name'][0]))
                {

                $this->form_validation->set_rules('attchments_file', 'Attachment file', 'required');
                }

            }


            if ($this->form_validation->run() == FALSE)
            {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('','');
            }
            else
            {

                 $folder_name = "vendor_file";
                $file_upload_path = SITE_BASE_PATH.IDENTIY.$folder_name;
                if(!folder_exist($file_upload_path)) {
                    mkdir($file_upload_path,0777);
                }else if(!is_writable($file_upload_path)) {
                    array_push($error_msgs,'Problem while uploading');
                }


                $frm_details = $this->input->post();

   
                $field_array = array('vendor_actual_status' => $frm_details['status'],'final_status' => $frm_details['status'] );
                
                $result= $this->identity_model->update('view_vendor_master_log',array_map('strtolower',$field_array),array('trasaction_id' => $frm_details['transaction_id']));
                
               $config_array = array('file_upload_path' => $file_upload_path,'file_permission' => 'jpeg|jpg|png|tiff','file_size' => BULK_UPLOAD_MAX_SIZE_MB*2000,'view_venor_master_log_id' =>  $frm_details['update_id'],'component_tbl_id' => 9,'status' => 1 );
                
                if($_FILES['attchments_file']['name'][0] != '')
                {


                    $config_array['files_count'] = count($_FILES['attchments_file']['name']);
                    $config_array['file_data'] = $_FILES['attchments_file'];
                    
                    $retunr_de = $this->file_upload_multiple_vendor($config_array); 
                    if(!empty($retunr_de)) {
                        $this->common_model->common_insert_batch('view_vendor_master_log_file',$retunr_de['success']);
                    }   
                }
                
                if($result)
                {
                    $update_activity_log = $this->save_activity_log(array('transaction_id' =>  $frm_details['update_id'],'component' => 'identity','action' => 'Status change','remark' => 'Status Changed','status' => $frm_details['status'],'created_by'=> $this->vendor_info['id'] , 'created_on' => date(DB_DATE_FORMAT)));

                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'Record Successfully Updated';

                    $json_array['redirect'] = VENDOR_SITE_URL.'identity/identity_insufficiency';

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


        $details = $this->identity_model->select_vendor_result_log(array('component_tbl_id' => "9","component" => "identity"),$decrypt_id);

        $details['attachments'] = $this->identity_model->select_file(array('id','file_name','real_filename'),array('view_venor_master_log_id' => $decrypt_id,'status' => 1,'component_tbl_id' => 9));
     
        if($encrypt_id && !empty($details))
        {            
            
            $details['assigned_user_id'] = $this->users_list();
            $details['states'] = $this->get_states();
            $details['details'] = $details[0];
            $details['id'] = $decrypt_id;

            echo $this->load->view('vendor/identity_ads_vendor_view',$details,TRUE);            
        }
        else
        {
            echo "<h4>Record Not Found</h4>";
        }

    }



    /* public function reject_vendor_address()
    {
       
        if($this->input->is_ajax_request())
        {

          //if( $this->permission['access_address_aq_allow'] == 1 )
          // {
            $frm_details = $this->input->post();
         
           
            if($frm_details['cases_id'] != "")
            {
                $list = explode(',',$frm_details['cases_id']);



                 
                $update_counter = 0;
                foreach ($list as $key => $value) {
                   
                   $update =  $this->addrver_model->upload_vendor_reject('view_vendor_master_log',array('status' => 4,'remarks' => $frm_details['reject_reason'],'vendor_rejected_by' => $this->vendor_info['id'],'vendor_reject_on' => date(DB_DATE_FORMAT),'modified_by' => $this->vendor_info['id'],'modified_on' => date(DB_DATE_FORMAT)),array('id' => $value));

                    $address_vendor_log_id = $this->addrver_model->select_address_vendor_log_id('view_vendor_master_log',array('id' => $value));

                    $update_address =  $this->addrver_model->upload_vendor_reject('address_vendor_log',array('status' => 4,'remarks' => $frm_details['reject_reason'],'modified_by' => $this->vendor_info['id'],'modified_on' => date(DB_DATE_FORMAT)),array('id' => $address_vendor_log_id[0]['case_id']));



                   $insert_activity_address =  $this->addrver_model->insert_activity_vendor_reject('address_activity_data',array('status' => 4,'remarks' => $frm_details['reject_reason'],'modified_by' => $this->vendor_info['id'],'modified_on' => date(DB_DATE_FORMAT)),array('id' => $address_vendor_log_id[0]['case_id']));

                    if($update) {
                        $update_counter++;
                        //$return =  $this->addressver_model->save(array('vendor_id' => 0,'vendor_assgined_on' => NULL),array('id' => $value));
                    }
                }



                $result_get_count_plus= $this->common_model->get_count(array('controllers' => 'assign_add'));
                                      
                $total_get_count_plus = $result_get_count_plus + count($list);
 
                $result_update_count_plus= $this->common_model->update_count(array('count' => $total_get_count_plus),array('controllers' => 'assign_add'));

                $result_get_count_minus= $this->common_model->get_count(array('controllers' => 'address/approval_queue'));
                                      
                $total_get_count_minus = $result_get_count_minus - count($list);
 
                $result_update_count_minus= $this->common_model->update_count(array('count' => $total_get_count_minus),array('controllers' => 'address/approval_queue'));

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