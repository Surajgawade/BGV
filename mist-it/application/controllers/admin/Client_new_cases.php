<?php defined('BASEPATH') or exit('No direct script access allowed');

class Client_new_cases extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        if(!$this->input->is_ajax_request())
        {   
            $the_session = array("contoller_name" => $this->router->fetch_class(), "method_name" => $this->router->fetch_method());
            $this->session->set_userdata('controller_mothod', $the_session);
        }

        if (!$this->is_admin_logged_in()) {
            redirect('admin/login');
            exit();
        }
      
        $this->perm_array = array('page_id' => 60);
        $this->assign_options = array('0' => 'Select', '1' => 'Assign to Executive');
        $this->load->model('client_new_cases_model');

        $user_id = $this->user_info['id'];

    }

    public function index()
    {
        $data['header_title'] = "New Cases";

        $data['clients'] = $this->get_clients(array('status' => STATUS_ACTIVE));

        $data['users_list'] = $this->users_list_executive();

        $this->load->view('admin/header', $data);

        $this->load->view('admin/client_new_cases_list');

        $this->load->view('admin/footer');
    }

    public function add()
    {
        $data['header_title'] = "Add New";

        $data['clients'] = $this->get_clients(array('status' => STATUS_ACTIVE));

        $data['assign_ids'] = $this->client_new_cases_model->get_assign_users_id('user_profile', false, array("`user_profile`.`status`,`user_profile`.`id`,`user_profile`.`email`,`user_profile`.`department`,`user_profile`.`user_name`,concat(`user_profile`.`firstname`,' ',`user_profile`.`lastname`) as fullname,`user_profile`.`profile_pic`,`user_profile`.`firstname`,`user_profile`.`lastname`"), array('status' => STATUS_ACTIVE));

        $this->load->view('admin/header', $data);

        $this->load->view('admin/client_new_cases_add');

        $this->load->view('admin/footer');
    }

    public function list_data1()
    {
        if ($this->input->is_ajax_request()) {

            $get_employee_id = $this->client_new_cases_model->get_employee_under();

            $logs = $this->client_new_cases_model->select_jion($get_employee_id);

            $html_view = '';
            foreach ($logs as $key => $value) {
                $counter = $key + 1;
                $html_view .= '<tr class="tbl_row_clicked" data-accessUrl=' . ADMIN_SITE_URL . 'client_new_cases/view_details/' . encrypt($value['id']) . ' >';
                $html_view .= "<td><input type='checkbox' name='cases_id[]' id='cases_id' value='" . $value['id'] . "'/></td>";
                $html_view .= "<td>" . $counter . "</td>";
                $html_view .= "<td>" . $value['client_name'] . "</td>";
                $html_view .= "<td>" . $value['total_cases'] . "</td>";
                $html_view .= "<td>" . $value['executive_name'] . "</td>";
                $html_view .= "<td>" . $value['created_by'] . "</td>";
                $html_view .= "<td>" . convert_db_to_display_date($value['created_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12) . "</td>";
                $html_view .= "<td>" . $value['type'] . "</td>";
                $html_view .= "<td>" . $value['status'] . "</td>";

                $html_view .= '</tr>';
            }
            $json_array['status'] = SUCCESS_CODE;

            $json_array['message'] = $html_view;
        } else {
            $json_array['status'] = SUCCESS_CODE;

            $json_array['e_message'] = "<tr><td>Something went wrong, please try again!<td></tr>";
        }
        echo_json($json_array);
    }

    public function list_data()
    {
        if ($this->permission['access_task_list_view'] == true) {

            if ($this->input->is_ajax_request()) {
                $client_new_cases = $data_arry = array();

                $params = $_REQUEST;

                $columns = array('id', 'client_name', 'TotalCases',  'CreatedBy', 'CreatedOn', 'status', 'type', 'encry_id');

              //  $get_employee_id = $this->client_new_cases_model->get_employee_under();

                $client_new_cases = $this->client_new_cases_model->select_jion($params, $columns);

                $totalRecords = count($this->client_new_cases_model->select_jion_count( $params, $columns));

                $x = 0;
                foreach ($client_new_cases as $client_new_case) {

                    $task_person_pending = $this->client_new_cases_model->select_task_person_id($client_new_case['task_person_id']);
                  
                    $task_person_complete = $this->client_new_cases_model->select_task_person_id($client_new_case['task_completed_id']); 
                    
                    $task_person_pending =  array_column($task_person_pending, 'user_name');
                    $task_person_complete =  array_column($task_person_complete, 'user_name');
                    $data_arry[$x]['id'] = $client_new_case['id'];
                    $data_arry[$x]['checkbox'] = $client_new_case['id'];
                    $data_arry[$x]['clientname'] = ucwords($client_new_case['clientname']);
                    $data_arry[$x]['total_cases'] = $client_new_case['total_cases'];
                    $data_arry[$x]['created_by'] = $client_new_case['created_by'];
                    $data_arry[$x]['created_on'] = convert_db_to_display_date($client_new_case['created_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);
                    $data_arry[$x]['status'] = $client_new_case['status'];
                    $data_arry[$x]['type'] = $client_new_case['type'];
                    $data_arry[$x]['task_pending'] = implode(',',$task_person_pending);
                    $data_arry[$x]['task_complete'] = implode(',',$task_person_complete);
                    $data_arry[$x]['encry_id'] = ADMIN_SITE_URL . "client_new_cases/view_details/" . encrypt($client_new_case['id']);
                    $data_arry[$x]['edit_access'] = $this->permission['access_task_list_edit'];
                    $x++;
                }

                $json_data = array("draw" => intval($params['draw']),
                    "recordsTotal" => intval($totalRecords),
                    "recordsFiltered" => intval($totalRecords),
                    "data" => $data_arry,
                );

                echo_json($json_data);
            }
        } else {
            $client_new_cases = $data_arry = array();

            $json_data = array("draw" => intval(1),
                "recordsTotal" => "Not Permission",
                "recordsFiltered" => null,
                "data" => $data_arry,

            );
            echo_json($json_data);

            // permission_denied();
        }
    }

    public function frm_save()
    {
        if ($this->input->is_ajax_request()) {

            $frm_details = $this->input->post();

            $this->form_validation->set_rules('client_id', 'ID', 'required');

            $this->form_validation->set_rules('total_cases', 'Total Cases', 'required');

            if ($this->form_validation->run() == false) {

                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');

            } else {
                $assign_id =   implode(',',$frm_details['assign_id']);
                $fields = array('client_id' => $frm_details['client_id'],
                    'total_cases' => $frm_details['total_cases'],
                    'status' => 'wip',
                    'created_by' => $this->user_info['id'],
                    'created_on' => date(DB_DATE_FORMAT),
                    'type' => $frm_details['type'],
                    'remarks' => $frm_details['remarks'],
                    'task_person_id' => $assign_id
                );
      
                $result = $this->client_new_cases_model->save($fields);

                if ($result) {

                    $id = $result;
                    $file_upload_path = SITE_BASE_PATH . "uploads/task_file/";


                    if (!folder_exist($file_upload_path)) {
                        mkdir($file_upload_path, 0777);
                    } else if (!is_writable($file_upload_path)) {
                        array_push($error_msgs, 'Problem while uploading');
                    }

                    $config_array = array('file_upload_path' => $file_upload_path, 
                                          'file_permission' => '*',
                                          'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 20000, 
                                          'file_id' => $id,
                                          'component_name' => 'client_new_case_id');

                    if ($_FILES['ekm_file']['name'][0] != '') {
                        $config_array['files_count'] = count($_FILES['ekm_file']['name']);
                        $config_array['file_data'] = $_FILES['ekm_file'];
                        $config_array['status'] = 1;
                        $retunr_de = $this->file_upload_multiple_task($config_array);
                        if (!empty($retunr_de)) {
                          
                            $this->common_model->common_insert_batch('client_new_case_file', $retunr_de['success']);
                        }
                    }

                    $json_array['message'] = 'Records Updated Successfully';

                    $json_array['status'] = SUCCESS_CODE;

                } else {
                    $json_array['message'] = 'Something went wrong, please try again';

                    $json_array['status'] = ERROR_CODE;
                }
            }
            echo_json($json_array);
        }
    }
  

    public function view_details($id = false)
    {

        if ($id) {
            $details = $this->client_new_cases_model->select_client_new_cases(array('client_new_cases.id' => decrypt($id)));

            if (!empty($details)) {
                $data['header_title'] = "Edit Details";

                $data['details'] = $details[0];

                $data['pre_post_details'] = $this->client_new_cases_model->select_pre_post_details(decrypt($id));
              
                $data['assign_ids'] = $this->client_new_cases_model->get_assign_users_id('user_profile', false, array("`user_profile`.`status`,`user_profile`.`id`,`user_profile`.`email`,`user_profile`.`department`,`user_profile`.`user_name`,concat(`user_profile`.`firstname`,' ',`user_profile`.`lastname`) as fullname,`user_profile`.`profile_pic`,`user_profile`.`firstname`,`user_profile`.`lastname`"), array('status' => STATUS_ACTIVE));

                $data['attachments'] = $this->client_new_cases_model->select_file(array('id', 'file_name', 'real_filename'), array('client_new_case_id' => decrypt($id), 'status' => 1));

                $data['clients'] = $this->get_clients(array('status' => STATUS_ACTIVE));

                $this->load->view('admin/header', $data);

                $this->load->view('admin/client_new_cases_edit');

                $this->load->view('admin/footer');
            }

        }
    }

    public function frm_update()
    {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('update_id', 'ID', 'required');

            $this->form_validation->set_rules('remarks', 'Remarks', 'required');

            $this->form_validation->set_rules('status', 'Status', 'required');

            if ($this->form_validation->run() == false) {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');
            } else {
                $frm_details = $this->input->post();

                $id = $frm_details['update_id'];
                if ($id) {

                    $file_upload_path = SITE_BASE_PATH . "task_file/";

                    if (!folder_exist($file_upload_path)) {
                        mkdir($file_upload_path, 0777);
                    } else if (!is_writable($file_upload_path)) {
                        array_push($error_msgs, 'Problem while uploading');
                    }


                    $config_array = array('file_upload_path' => $file_upload_path, 
                                          'file_permission' => '*',
                                          'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 20000, 
                                          'file_id' => $id,
                                          'component_name' => 'client_new_case_id');

                    if ($_FILES['ekm_file']['name'][0] != '') {
                        $config_array['files_count'] = count($_FILES['ekm_file']['name']);
                        $config_array['file_data'] = $_FILES['ekm_file'];
                        $config_array['status'] = 1;
                        $retunr_de = $this->file_upload_multiple_task($config_array);
                        if (!empty($retunr_de)) {
                            $this->common_model->common_insert_batch('client_new_case_file', $retunr_de['success']);
                        }
                    }
                     
                    $com_id = $this->client_new_cases_model->select_client_new_cases(array('id' => $frm_details['update_id']));
                    $cor_id = array();
                    $complete_id = '';
                    if(empty($com_id[0]['task_completed_id']))
                    {
                        if(in_array($this->user_info['id'],explode(',',$com_id[0]['task_person_id']))) 
                        {   
                            if($frm_details['status'] != "hold" && $frm_details['status'] != "wip")
                            {
                            $complete_id = $this->user_info['id'];
                            array_push($cor_id,$this->user_info['id']);
                            }
                        }
                    }
                    else{
                        if(in_array($this->user_info['id'],explode(',',$com_id[0]['task_person_id']))) 
                        {
                            
                            if($frm_details['status'] != "hold" && $frm_details['status'] != "wip") 
                            {
                                if(in_array($this->user_info['id'],explode(',',$com_id[0]['task_completed_id'] )))
                                {
                                $complete_id = $com_id[0]['task_completed_id'];
                                }else{
                                  $complete_id = $com_id[0]['task_completed_id'].','.$this->user_info['id'];  
                                }
                                $cor_id = explode(',',$com_id[0]['task_completed_id']);
                                array_push($cor_id,$this->user_info['id']);
                            }
                        }
                     
                    }
                    
                    $assign_id =   implode(',',$frm_details['assign_id']);

                    $diff_result = array_diff($frm_details['assign_id'],$cor_id);
                     
                    if($frm_details['status'] != "hold") 
                    {
                         if(count($diff_result) > 0){
                            $actual_status = "wip";
                         }else{
                            $actual_status = "closed";
                         }
                    }
                    else
                    {
                        $actual_status = "hold";
                    }
                    
                    $fields = array('remarks' => $frm_details['remarks'],
                        'status' => $actual_status,
                        'type' => $frm_details['type'],
                        'client_id' => $frm_details['client_id'],
                        'total_cases' => $frm_details['total_cases'],
                        'task_person_id' => $assign_id,
                        'task_completed_id' => $complete_id,
                        'modified_by' => $this->user_info['id'],
                        'modified_on' => date(DB_DATE_FORMAT),
                    );

                    $result = $this->client_new_cases_model->save($fields, array('id' => $frm_details['update_id']));
                      
                    if(!empty($frm_details['component_ref_no']) && $actual_status == "closed") 
                    {
                        $fields_status = array('component_ref_no' => $frm_details['component_ref_no'],'status' => "Initiated");
                        
                        $result = $this->client_new_cases_model->save_task_manager('pre_post_details',$fields_status, array('task_manager_id' => $frm_details['update_id']));

                    } 
                    if ($result) {
                        $json_array['message'] = 'Records Updated Successfully';
                        $json_array['redirect'] = ADMIN_SITE_URL . 'client_new_cases';
                        $json_array['status'] = SUCCESS_CODE;
                    } else {
                        $json_array['message'] = 'Something went wrong, please try again';
                        $json_array['status'] = ERROR_CODE;
                    }
                } else {
                    $json_array['message'] = 'Something went wrong, please try again';
                    $json_array['status'] = ERROR_CODE;
                }
            }
            echo_json($json_array);
        }
    }

    public function check_ref_no_exists()
    {
        if ($this->input->is_ajax_request()) {
            $ref_no = $this->input->post('ref_no');
         
            $lists = $this->client_new_cases_model->check_reference_no(array('cmp_ref_no'=>$ref_no));
            
            if (!empty($lists[0]['cmp_ref_no'])) {
                echo_json(array('ref_no'=> $lists[0]['cmp_ref_no']));
            }else{
                echo_json(array('ref_no'=>"na"));
            } 
        }

    }

    public function assign_to_executive()
    {
        $json_array = array();
        if ($this->input->is_ajax_request() && $this->permission['access_task_list_re_assign']) {
            $frm_details = $this->input->post();

            if ($frm_details['users_list'] != 0 && $frm_details['cases_id'] != "") {
                $return = $this->common_model->update_in('client_new_cases', array('has_case_id' => $frm_details['users_list'], 'has_assigned_on' => date(DB_DATE_FORMAT)), array('where_id' => $frm_details['cases_id']));
                if ($return) {
                    $json_array['status'] = SUCCESS_CODE;
                    $json_array['message'] = "Assigned Successfully";

                } else {
                    $json_array['status'] = ERROR_CODE;
                    $json_array['message'] = "Something went wrong,please try again";
                }

            } else {
                $json_array['status'] = ERROR_CODE;
                $json_array['message'] = "Select atleast one case";
            }

        } else {

            $json_array['status'] = ERROR_CODE;
            $json_array['message'] = "Access Denied, You don’t have permission to access this page";
        }
        echo_json($json_array);
    }

    public function delete()
    {

        $task_id = $this->input->post('task_id');

        if ($this->input->is_ajax_request() && $this->permission['access_task_list_delete'] == true) {

            if ($task_id) {

                $field_array = array('status' => STATUS_DELETED,
                    'modified_on' => date(DB_DATE_FORMAT),
                    'modified_by' => $this->user_info['id'],
                );

                $where_array = array('id' => $is_exits['id']);

                if ($this->client_new_cases_model->save($field_array, $where_array)) {
                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'Deleted Successfully';
                } else {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Something went wrong,please try again';
                }
            } else {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = 'Something went wrong,please try again';
            }
            echo_json($json_array);
        } else {
            permission_denied();
        }
    }

    public function add_verificarion_result()
    {
        if ($this->input->is_ajax_request()) {

            $this->form_validation->set_rules('tbl_addrver', 'Id', 'required');

            $this->form_validation->set_rules('addrverres_id', 'Id', 'required');

            $this->form_validation->set_rules('closuredate', 'Mode Of Verification', 'required');

            if ($this->form_validation->run() == false) {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');
            } else {
                $frm_details = $this->input->post();

                $file_upload_path = SITE_BASE_PATH . ADDRESS . $frm_details['clientid'];
                if (!folder_exist($file_upload_path)) {
                    mkdir($file_upload_path, 0777);
                }
                $check_first_qc = get_group_wise_status(array('components_id' => 6, 'filter_status' => 'Closed'));

                $verfstatus = status_id_frm_db(array('status_value' => $frm_details['action_val'], 'components_id' => 6));

                $addrverres_id = $frm_details['addrverres_id'];

                if ($_FILES['attchments_ver']['name'][0] != '') {
                    $mod_of_veri = $frm_details['mode_of_verification'];
                } else {
                    $mod_of_veri = "Verbal";
                }

                $field_array = array(
                    'verfstatus' => $verfstatus['id'],
                    'var_filter_status' => $verfstatus['filter_status'],
                    'var_report_status' => $verfstatus['report_status'],
                    'closuredate' => convert_display_to_db_date($frm_details['closuredate']),
                    // 'res_address_type' => $frm_details['res_address_type'],
                    'res_address' => $frm_details['res_address'],
                    'clientid' => $frm_details['clientid'],
                    'candsid' => $frm_details['candidates_info_id'],
                    'addrverid' => $frm_details['tbl_addrver'],
                    'res_city' => $frm_details['res_city'],
                    'res_pincode' => $frm_details['res_pincode'],
                    'res_state' => $frm_details['res_state'],
                    'res_stay_from' => $frm_details['res_stay_from'],
                    'res_stay_to' => $frm_details['res_stay_to'],
                    'address_action' => $frm_details['address_action'],
                    'city_action' => $frm_details['city_action'],
                    'pincode_action' => $frm_details['pincode_action'],
                    'state_action' => $frm_details['state_action'],
                    'stay_from_action' => $frm_details['stay_from_action'],
                    'stay_to_action' => $frm_details['stay_to_action'],
                    'mode_of_verification' => $mod_of_veri,
                    'resident_status' => $frm_details['resident_status'],
                    'landmark' => $frm_details['landmark'],
                    'neighbour_1' => $frm_details['neighbour_1'],
                    'neighbour_details_1' => $frm_details['neighbour_details_1'],
                    'neighbour_2' => $frm_details['neighbour_2'],
                    'neighbour_details_2' => $frm_details['neighbour_details_2'],
                    'verified_by' => $frm_details['verified_by'],
                    'addr_proof_collected' => $frm_details['addr_proof_collected'],
                    'remarks' => $frm_details['remarks'],
                    'modified_on' => date(DB_DATE_FORMAT),
                    'modified_by' => $this->user_info['id'],
                    "first_qc_approve" => '',
                    'first_qc_updated_on' => null,
                    'first_qu_reject_reason' => '',
                    'is_bulk_uploaded' => 0,
                    'activity_log_id' => $frm_details['activity_last_id'],
                );

                if (in_array($verfstatus['id'], explode(',', $check_first_qc['status_ids']))) {
                    $field_array['first_qc_approve'] = 'first qc pending';
                }

                $result = $this->addressver_model->save_update_result(array_map('strtolower', $field_array), array('id' => $addrverres_id));

                $result_addrverres_result = $this->addressver_model->save_update_result_addrverres(array_map('strtolower', $field_array));

                $config_array = array('file_upload_path' => $file_upload_path, 'file_permission' => 'jpeg|jpg|png|pdf|docx|xls|xlsx', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 2000, 'file_id' => $addrverres_id, 'component_name' => 'addrver_id');

                if ($_FILES['attchments_ver']['name'][0] != '') {
                    $config_array['files_count'] = count($_FILES['attchments_ver']['name']);
                    $config_array['file_data'] = $_FILES['attchments_ver'];
                    $config_array['type'] = 1;
                    $retunr_de = $this->file_upload_multiple($config_array);
                    if (!empty($retunr_de)) {
                        $this->common_model->common_insert_batch('addrver_files', $retunr_de['success']);
                    }
                }

                if ($result) {
                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'Updated Successfully';

                    $json_array['redirect'] = ADMIN_SITE_URL . 'address';
                } else {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Something went wrong,please try again';
                }
            }
            echo_json($json_array);
        }
    }

    public function rearragged_file_name($clientid)
    {

        $json_array = array();

        if ($this->input->is_ajax_request()) {
            $frm_details = $this->input->post();

            if (is_array($frm_details) && !empty($frm_details)) {
                $order = $frm_details['position'];

                if (is_array($order) && !empty($order)) {

                    foreach ($order as $key => $value) {

                        if (!empty($value)) {
                            $update[] = array('new_case_id' => $clientid, 'rearranged_file_id' => $key + 1, 'file_name' => $value, 'status' => 1);
                        }

                    }

                    $this->client_new_cases_model->upload_file_update_status(array('status' => 0), array('new_case_id' => $clientid));

                    $this->client_new_cases_model->upload_file_update($update);

                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'Ordered Successfully';

                } else {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Something went worong, please try again';
                }
            } else {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = 'Something went worong, please try again';
            }
            echo_json($json_array);
        }

    }

}
