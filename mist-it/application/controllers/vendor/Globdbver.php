<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Globdbver extends MY_Vendor_Cotroller
{    
    function __construct()
    {
        parent::__construct();
        $this->perm_array = array('page_name' => 'globdbver');
        $this->assign_options = array('0' => 'Select','1' => 'Assign to Executive');
        $this->load->model(array('vendor/globver_model'));
    }

   public function index()
    {   
        $data['header_title'] = "Global Database Cases View";

        $data['lists'] = $this->vendor_common_model->global_case_list(array('view_vendor_master_log.vendor_id' => $this->vendor_info['vendors_id'],'view_vendor_master_log.status =' => 1,'component' => 'globdbver'));

        $this->load->view('vendor/header',$data);
        
        $this->load->view('vendor/globver_list');

        $this->load->view('vendor/footer');


    }
    public function globdbver_wip()
    {   
        $data['header_title'] = "Global Database Cases View";

       // $data['lists'] = $this->globver_model->global_case_list(array('glodbver_vendor_log.vendor_id' => $this->vendor_info['vendors_id'],'view_vendor_master_log.status =' => 1,'component' => 'globdbver'));

       $data['vendor_executive_list'] = $this->globver_model->get_vendor_assign_users('vendor_executive_login',FALSE,array("`vendor_executive_login`.`status`,`vendor_executive_login`.`id`,`vendor_executive_login`.`email_id`,`vendor_executive_login`.`user_name`,concat(`vendor_executive_login`.`first_name`,' ',`vendor_executive_login`.`last_name`) as fullname,`vendor_executive_login`.`first_name`,`vendor_executive_login`.`last_name`"),array('status' => STATUS_ACTIVE));
       
       
        $this->load->view('vendor/header',$data);
        
        $this->load->view('vendor/global_list_wip');

        $this->load->view('vendor/footer');
    }

     public function globdbver_insufficiency()
    {   
        $data['header_title'] = "Global Database Cases View";

      //  $data['lists'] = $this->globver_model->global_case_list(array('glodbver_vendor_log.vendor_id' => $this->vendor_info['vendors_id'],'view_vendor_master_log.status =' => 1,'component' => 'globdbver'));

       $data['vendor_executive_list'] = $this->globver_model->get_vendor_assign_users('vendor_executive_login',FALSE,array("`vendor_executive_login`.`status`,`vendor_executive_login`.`id`,`vendor_executive_login`.`email_id`,`vendor_executive_login`.`user_name`,concat(`vendor_executive_login`.`first_name`,' ',`vendor_executive_login`.`last_name`) as fullname,`vendor_executive_login`.`first_name`,`vendor_executive_login`.`last_name`"),array('status' => STATUS_ACTIVE));
       
       
        $this->load->view('vendor/header',$data);
        
        $this->load->view('vendor/global_list_insufficiency');

        $this->load->view('vendor/footer');
    }
    
    public function globdbver_closed()
    {   
        $data['header_title'] = "Global Database Cases View";

   
       $data['vendor_executive_list'] = $this->globver_model->get_vendor_assign_users('vendor_executive_login',FALSE,array("`vendor_executive_login`.`status`,`vendor_executive_login`.`id`,`vendor_executive_login`.`email_id`,`vendor_executive_login`.`user_name`,concat(`vendor_executive_login`.`first_name`,' ',`vendor_executive_login`.`last_name`) as fullname,`vendor_executive_login`.`first_name`,`vendor_executive_login`.`last_name`"),array('status' => STATUS_ACTIVE));
       
       
        $this->load->view('vendor/header',$data);
        
        $this->load->view('vendor/global_list_closed');

        $this->load->view('vendor/footer');


    }

    public function global_view_datatable_assign_wip()
    {
     
        $params = $add_candidates = $data_arry = $columns = array();
         
        $params = $_REQUEST;
       
        $columns = array('global_id.id','candidates_info.ClientRefNumber','candidates_info.cmp_ref_no','company_database.coname','candidates_info.CandidateName','candidates_info.caserecddate','verfstatus','city','encry_id','address','pincode','state','check_closure_date','global_com_ref','iniated_date');
 
        $where_arry = array();

        $lists = $this->globver_model->global_case_list(array('glodbver_vendor_log.vendor_id' => $this->vendor_info['vendors_id'],'view_vendor_master_log.status =' => 1,'view_vendor_master_log.final_status =' => 'wip','component' => 'globdbver'),$params,$columns);

        $totalRecords = count($this->globver_model->global_case_list_count(array('glodbver_vendor_log.vendor_id' => $this->vendor_info['vendors_id'],'view_vendor_master_log.status =' => 1,'view_vendor_master_log.final_status =' => 'wip','component' => 'globdbver'),$params,$columns));
        
    
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
            $data_arry[$x]['global_com_ref'] = $list['global_com_ref'];
                 
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

    public function global_view_datatable_assign_insufficiency()
    {
     
        $params = $add_candidates = $data_arry = $columns = array();
         
        $params = $_REQUEST;
       
        $columns = array('global_id.id','candidates_info.ClientRefNumber','candidates_info.cmp_ref_no','company_database.coname','candidates_info.CandidateName','candidates_info.caserecddate','verfstatus','city','encry_id','address','pincode','state','check_closure_date','global_com_ref','iniated_date');
 
        $where_arry = array();

        $lists = $this->globver_model->global_case_list(array('glodbver_vendor_log.vendor_id' => $this->vendor_info['vendors_id'],'view_vendor_master_log.status =' => 1,'view_vendor_master_log.final_status =' => 'insufficiency','component' => 'globdbver'),$params,$columns);

        $totalRecords = count($this->globver_model->global_case_list_count(array('glodbver_vendor_log.vendor_id' => $this->vendor_info['vendors_id'],'view_vendor_master_log.status =' => 1,'view_vendor_master_log.final_status =' => 'insufficiency','component' => 'globdbver'),$params,$columns));
        
       
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
            $data_arry[$x]['global_com_ref'] = $list['global_com_ref'];
                 
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

    public function global_view_datatable_assign_closed()
    {
     
        $params = $add_candidates = $data_arry = $columns = array();
         
        $params = $_REQUEST;
       
        $columns = array('court_id.id','candidates_info.ClientRefNumber','candidates_info.cmp_ref_no','company_database.coname','candidates_info.CandidateName','candidates_info.caserecddate','verfstatus','city','encry_id','address','pincode','state','check_closure_date','global_com_ref','iniated_date');
 
        $where_arry = array();

        $lists = $this->globver_model->global_case_list_closed(array('glodbver_vendor_log.vendor_id' => $this->vendor_info['vendors_id'],'view_vendor_master_log.status =' => 1,'component' => 'globdbver'),$params,$columns);

        $totalRecords = count($this->globver_model->global_case_list_closed_count(array('glodbver_vendor_log.vendor_id' => $this->vendor_info['vendors_id'],'view_vendor_master_log.status =' => 1,'component' => 'globdbver'),$params,$columns));
        
       
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
            $data_arry[$x]['global_com_ref'] = $list['global_com_ref'];
                 
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
   
        $details['header_title'] = "Address Edit";

        $details['details'] = $this->globver_model->select_vendor_result_log(array('component_tbl_id' => "6","component" => "globdbver"),$trans_id);
       

        if($trans_id1 && !empty($details))
        {
            

            $this->load->view('vendor/header',$details);

            $this->load->view('vendor/globdbver_edit');

            $this->load->view('vendor/footer');
        }
        else
        {
            show_404();
        }
    }

    public function update_global_wip()
    {
        if($this->input->is_ajax_request())
        {

            $frm_details = $this->input->post();

       
            $this->form_validation->set_rules('transaction_id', 'ID', 'required');

            if($frm_details['status'] != "wip")
            {

            $this->form_validation->set_rules('vendor_remark', 'Vendor Remark', 'required');

            }

            if($frm_details['status'] == "clear" || $frm_details['status'] == "possible match")
            {
            

                if(empty($_FILES['attchments_file']['name'][0]) && empty($_POST['attchments']))
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

                $frm_details = $this->input->post();

   
                $field_array = array('vendor_actual_status' => $frm_details['status'],'final_status' => $frm_details['status'],'vendor_remark' => $frm_details['vendor_remark']);

                if($frm_details['status'] == "insufficiency" || $frm_details['status'] == "clear" || $frm_details['status'] == "possible match")
                {
                   $field_array['modified_on']  = date(DB_DATE_FORMAT);
                }
                
                $result= $this->globver_model->update('view_vendor_master_log',array_map('strtolower',$field_array),array('trasaction_id' => $frm_details['transaction_id']));
                
                $folder_name = "vendor_file";
                $file_upload_path = SITE_BASE_PATH.GLOBAL_DB.$folder_name;
                if(!folder_exist($file_upload_path)) {
                    mkdir($file_upload_path,0777,true);
                }else if(!is_writable($file_upload_path)) {
                    array_push($error_msgs,'Problem while uploading');
                }

               $config_array = array('file_upload_path' => $file_upload_path,
                                     'file_permission' => 'jpeg|jpg|png|tiff|pdf',
                                     'file_size' => BULK_UPLOAD_MAX_SIZE_MB*2000,
                                     'view_venor_master_log_id' =>  $frm_details['update_id'],
                                     'component_tbl_id' => 6,
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

                    $update_activity_log = $this->save_activity_log(array('transaction_id' =>  $frm_details['update_id'],'component' => 'globdbver','action' => 'Status change','remark' => 'Status Changed','status' => $frm_details['status'],'created_by'=> $this->vendor_info['id'] , 'created_on' => date(DB_DATE_FORMAT))); 

                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'Record Successfully Updated';

                    $json_array['redirect'] = VENDOR_SITE_URL.'globdbver/globdbver_wip';

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
    
    public function update_globver_insufficiency()
    {
        if($this->input->is_ajax_request())
        {

          $frm_details = $this->input->post();


            $this->form_validation->set_rules('transaction_id', 'ID', 'required');

            if($frm_details['status'] != "wip")
            {

            $this->form_validation->set_rules('vendor_remark', 'Vendor Remark', 'required');

            }

            if($frm_details['status'] == "clear" || $frm_details['status'] == "possible match")
            {
            
                if(empty($_FILES['attchments_file']['name'][0]) && empty($_POST['attchments']))
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

                $frm_details = $this->input->post();

                $field_array = array('vendor_actual_status' => $frm_details['status'],'final_status' => $frm_details['status'],'vendor_remark' => $frm_details['vendor_remark']);

                if($frm_details['status'] == "insufficiency" || $frm_details['status'] == "clear" || $frm_details['status'] == "possible match")
                {
                   $field_array['modified_on']  = date(DB_DATE_FORMAT);
                }
                
                $result= $this->globver_model->update('view_vendor_master_log',array_map('strtolower',$field_array),array('trasaction_id' => $frm_details['transaction_id']));


                $folder_name = "vendor_file";
                $file_upload_path = SITE_BASE_PATH.GLOBAL_DB.$folder_name;
                if(!folder_exist($file_upload_path)) {
                    mkdir($file_upload_path,0777,true);
                }else if(!is_writable($file_upload_path)) {
                    array_push($error_msgs,'Problem while uploading');
                }
                
                $config_array = array('file_upload_path' => $file_upload_path,
                                     'file_permission' => 'jpeg|jpg|png|tiff|pdf',
                                     'file_size' => BULK_UPLOAD_MAX_SIZE_MB*2000,
                                     'view_venor_master_log_id' =>  $frm_details['update_id'],
                                     'component_tbl_id' => 6,
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

                    $update_activity_log = $this->save_activity_log(array('transaction_id' =>  $frm_details['update_id'],'component' => 'globdbver','action' => 'Status change','remark' => 'Status Changed','status' => $frm_details['status'],'created_by'=> $this->vendor_info['id'] , 'created_on' => date(DB_DATE_FORMAT)));
                    
                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'Record Successfully Updated';

                    $json_array['redirect'] = VENDOR_SITE_URL.'globdbver/globdbver_insufficiency';

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


        $details = $this->globver_model->select_vendor_result_log(array('component_tbl_id' => "6","component" => "globdbver"),$decrypt_id);

        $details['attachments'] = $this->globver_model->select_file(array('id','file_name','real_filename','status'),array('view_venor_master_log_id' => $decrypt_id,'component_tbl_id' => 6));

        $details['attachments_file'] = $this->globver_model->select_file_from_admin(array('id','file_name','real_filename'),array('glodbver_id' =>  $details[0]['glodbver_id'],'status' => 1,'type' => 0));
     
        if($encrypt_id && !empty($details))
        {            
            
            $details['assigned_user_id'] = $this->users_list();
            $details['states'] = $this->get_states();
            $details['details'] = $details[0];
            $details['id'] = $decrypt_id;

            echo $this->load->view('vendor/global_ads_vendor_view',$details,TRUE);            
        }
        else
        {
            echo "<h4>Record Not Found</h4>";
        }

    }
   
}
?>