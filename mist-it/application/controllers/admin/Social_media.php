<?php defined('BASEPATH') or exit('No direct script access allowed');

class Social_media extends MY_Controller
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

        $this->perm_array = array('page_id' => 17);
        if ($this->input->is_ajax_request()) {
            $this->perm_array = array('direct_access' => true);

        }
        $this->assign_options = array('0' => 'Select Executive', '1' => 'Assign to Executive');
        $this->assign_options_vendor = array('0' => 'Select Vendor', '1' => 'Assign to Vendor');
        $this->load->model(array('social_media_model'));
    }

    public function index()
    {
        $data['header_title'] = "Social Lists";
        $data['filter_view'] = $this->filter_view();
        $data['users_list'] = $this->social_media_model->get_assign_users('user_profile', false, array("`user_profile`.`status`,`user_profile`.`id`,`user_profile`.`email`,`user_profile`.`department`,`user_profile`.`user_name`,concat(`user_profile`.`firstname`,' ',`user_profile`.`lastname`) as fullname,`user_profile`.`profile_pic`,`user_profile`.`firstname`,`user_profile`.`lastname`,`roles`.`role_name`,`roles`.`groups_id`,`roles_permissions`.*"), array('user_profile.status' => STATUS_ACTIVE));
        $data['clients_id'] = $this->get_clients(array('status' => STATUS_ACTIVE));
        $data['status_value'] = $this->get_status();
        $this->load->view('admin/header', $data);
        $this->load->view('admin/social_media_list');
        $this->load->view('admin/footer');
    }

    public function get_clients_for_social_media_list_view()
    {
        $params = $_REQUEST;

        $clients = $this->social_media_model->select_client_list_view_social_media('clients', false, array('clients.id', 'clients.clientname'), $params);

        $clients_arry[0] = 'Select Client';

        foreach ($clients as $key => $value) {
            $clients_arry[$value['id']] = ucwords(strtolower($value['clientname']));
        }

        echo_json(array('client_list' => $clients_arry));

    }

    public function social_media_view_datatable()
    {
        $params = $court_candidates = $data_arry = $columns = array();

        $params = $_REQUEST;

        $columns = array('id', 'ClientRefNumber', 'cmp_ref_no', 'CandidateName', 'caserecddate', 'last_modified', 'encry_id', 'verfstatus', 'verification_address', 'additional_comment', 'verifiername', 'verification_date', 'modeofverification', 'closuredate', 'remarks', 'reiniated_date');

        $social_media_candidates = $this->social_media_model->get_all_social_media_record_datatable(false, $params, $columns);

        $totalRecords = count($this->social_media_model->get_all_social_media_record_datatable_count(false, $params, $columns));

        $x = 0;
        foreach ($social_media_candidates as $social_media_candidate) {

            $data_arry[$x]['id'] = $x + 1;
            $data_arry[$x]['ClientRefNumber'] = $social_media_candidate['ClientRefNumber'];
            $data_arry[$x]['cmp_ref_no'] = $social_media_candidate['cmp_ref_no'];
            $data_arry[$x]['CandidateName'] = $social_media_candidate['CandidateName'];
            $data_arry[$x]['social_media_com_ref'] = $social_media_candidate['social_media_com_ref'];
            $data_arry[$x]['iniated_date'] = convert_db_to_display_date($social_media_candidate['iniated_date']);
            $data_arry[$x]['clientname'] = $social_media_candidate['clientname'];
            $data_arry[$x]['verfstatus'] = $social_media_candidate['status_value'];
            $data_arry[$x]['executive_name'] = $social_media_candidate['user_name'];
            $data_arry[$x]['caserecddate'] = convert_db_to_display_date($social_media_candidate['caserecddate']);
            $data_arry[$x]['last_activity_date'] = convert_db_to_display_date($social_media_candidate['last_activity_date'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);
            $data_arry[$x]['closuredate'] = convert_db_to_display_date($social_media_candidate['closuredate']);
            $data_arry[$x]['remarks'] = $social_media_candidate['remarks'];
            $data_arry[$x]['tat_status'] = $social_media_candidate['tat_status'];
            $data_arry[$x]['due_date'] = convert_db_to_display_date($social_media_candidate['due_date']);
            $data_arry[$x]['mode_of_veri'] = $social_media_candidate['mode_of_veri'];
            $data_arry[$x]['encry_id'] = ADMIN_SITE_URL . "social_media/view_details/" . encrypt($social_media_candidate['social_media_id']);
            $x++;
        }

        $json_data = array(
            "draw" => intval($params['draw']),
            "recordsTotal" => intval($totalRecords),
            "recordsFiltered" => intval($totalRecords),
            "data" => $data_arry,
        );

        echo_json($json_data);
    }

    public function add($candsid)
    {
        if ($this->input->is_ajax_request()) {
            $data['get_cands_details'] = $this->candidate_entity_pack_details($candsid);

            $data['get_address_details'] = $this->address_entity_pack_details($candsid);

            $data['mode_of_verification'] = $this->social_media_model->get_mode_of_verification(array('entity' => $data['get_cands_details']['entity_id'], 'package' => $data['get_cands_details']['package_id'], 'tbl_clients_id' => $data['get_cands_details']['clientid']));

            $data['states'] = $this->get_states();


            $data['assigned_user_id']  = $this->get_reporting_manager_for_execituve($data['get_cands_details']['clientid']);


            echo $this->load->view('admin/social_media_add', $data, true);
        } else {
            echo "<h3>Something went wrong, please try again</h3>";
        }
    }

    protected function social_media_com_ref($insert_id)
    {

        $name = COMPONENT_REF_NO['SOCIAL'];

        $social_media_number = $name . $insert_id;

        $field_array = array('social_media_com_ref' => $social_media_number);

        $update_auto_increament_id = $this->social_media_model->update_auto_increament_value($field_array, array('id' => $insert_id));

        return $social_media_number;
    }

    public function save_form()
    {
        if ($this->input->is_ajax_request()) {

            $this->form_validation->set_rules('clientid', 'Client', 'required');
            $this->form_validation->set_rules('candsid', 'Candidate', 'required');

            if ($this->form_validation->run() == false) {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');
            } else {
                $frm_details = $this->input->post();

                $file_upload_path = SITE_BASE_PATH . SOCIAL_MEDIA . $frm_details['clientid'];
                if (!folder_exist($file_upload_path)) {
                    mkdir($file_upload_path, 0777);
                } else if (!is_writable($file_upload_path)) {
                    array_push($error_msgs, 'Problem while uploading');
                }

                $tat_day = $this->get_client_entity_package_tat_day(array('tbl_clients_id' => $frm_details['clientid'], 'entity' => $frm_details['entity_id'], 'package' => $frm_details['package_id']));

                $get_holiday1 = $this->get_holiday();

                $get_holiday = array_map('current', $get_holiday1);

                $closed_date = getWorkingDays(convert_display_to_db_date($frm_details['iniated_date']), $get_holiday, $tat_day[0]['tat_social_media']);

                $field_array = array(
                    'clientid'          => $frm_details['clientid'],
                    'candsid'           => $frm_details['candsid'],
                    'social_media_com_ref'  => '',
                    'iniated_date'      => convert_display_to_db_date($frm_details['iniated_date']),
                    'mode_of_veri'      => $frm_details['mode_of_veri'],
                    'created_by'        => $this->user_info['id'],
                    'created_on'        => date(DB_DATE_FORMAT),
                    'modified_on'       => date(DB_DATE_FORMAT),
                    'modified_by'       => $this->user_info['id'],
                    'has_case_id'       => $frm_details['has_case_id'],
                    'has_assigned_on'   => date(DB_DATE_FORMAT),
                    'is_bulk_uploaded'  => 0,
                    "due_date"          => $closed_date,
                    "tat_status"        => "IN TAT",
                );

                $result = $this->social_media_model->save(array_map('strtolower', $field_array));

                $social_media_com_ref = $this->social_media_com_ref($result);

                $config_array = array('file_upload_path' => $file_upload_path, 'file_permission' => 'jpeg|jpg|png|pdf|docx|xls|xlsx', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 1000, 'file_id' => $result, 'component_name' => 'social_media_id');
                if (empty($error_msgs)) {
                    if ($_FILES['attchments']['name'][0] != '') {
                        $config_array['files_count'] = count($_FILES['attchments']['name']);
                        $config_array['file_data'] = $_FILES['attchments'];
                        $config_array['type'] = 0;
                        $retunr_de = $this->file_upload_multiple($config_array);
                        if (!empty($retunr_de)) {
                            $this->common_model->common_insert_batch('social_media_files', $retunr_de['success']);
                        }
                    }

                }

                if ($result) {

                    $user_activity_data = $this->common_model->user_actity_data(array('component' => "Social Media",
                        'ref_no' => $social_media_com_ref, 'candidate_name' => $frm_details['CandidateName'], 'created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id'], 'activity_type' => 'Manual', 'action' => 'Add'));

                    auto_update_overall_status($frm_details['candsid']);
                    auto_update_tat_status($frm_details['candsid']);
                
                    if ($result) {
                        $json_array['status'] = SUCCESS_CODE;
                        $json_array['message'] = 'Record Successfully Inserted';
                        $json_array['redirect'] = ADMIN_SITE_URL . 'social_media';

                    } else {
                        $json_array['status'] = ERROR_CODE;
                        $json_array['message'] = 'Something went wrong,please try again';
                    }

                } else {
                    $json_array['status'] = ERROR_CODE;
                    $json_array['message'] = 'Something went wrong,please try again';
                }
                echo_json($json_array);
            }
        }
    }

    public function get_assign_social_media_list_view()
    {
        log_message('error', 'Social Media List Assign in Assign View');
        try {

            $data['header_title'] = "Social Media Verification";
            $data['filter_view'] = $this->filter_view_assign_social_media_list();
            $data['users_list'] = $this->users_list();
          

        } catch (Exception $e) {
            log_message('error', 'Identity::get_assign_social_media_list_view');
            log_message('error', $e->getMessage());
        }
        echo $this->load->view('admin/assign_social_media', $data, true);
    }

    protected function filter_view_assign_social_media_list($true = false)
    {
        if ($true) {
            $data['status'] = $this->get_status_candidate();
        } else {
            $data['status'] = $this->get_status();
        }
        $data['clients'] = $this->get_clients_for_list_view_social_media();
        $data['user_list_name'] = $this->users_list_filter();

        return $this->load->view('admin/filter_view_assign', $data, true);
    }

    public function get_clients_for_list_view_social_media()
    {
        $this->load->model('assign_social_media_model');
        $clients = $this->assign_social_media_model->select_client_list_assign_social_media_view('clients', false, array('clients.id', 'clients.clientname'));

        $clients_arry[0] = 'Select Client';

        foreach ($clients as $key => $value) {
            $clients_arry[$value['id']] = ucwords(strtolower($value['clientname']));
        }
        return $clients_arry;
    }

   
    public function view_from_inside($add_comp_id = false)
    {
        if ($this->input->is_ajax_request()) {

            $cget_details = $this->social_media_model->get_all_social_media_record(array('social_media.social_media_com_ref' => $add_comp_id));

            if (!empty($cget_details)) {

                $data['header_title'] = 'Social Media Verification';

                $data['get_cands_details'] = $this->candidate_entity_pack_details($cget_details[0]['cands_id']);

                $data['mode_of_verification'] = $this->social_media_model->get_mode_of_verification(array('entity' => $data['get_cands_details']['entity_id'], 'package' => $data['get_cands_details']['package_id'], 'tbl_clients_id' => $data['get_cands_details']['clientid']));

                $data['selected_data'] = $cget_details[0];

                $data['states'] = $this->get_states();

             
                $data['assigned_user_id']  = $this->get_reporting_manager_for_execituve($cget_details[0]['clientid']);

                $data['attachments'] = $this->social_media_model->select_file(array('id', 'file_name', 'real_filename', 'type'), array('social_media_id' => $cget_details[0]['social_media_id'], 'status' => 1));

                $check_insuff_raise = $this->social_media_model->select_insuff(array('social_media_id' => $cget_details[0]['social_media_id'], 'status' => STATUS_ACTIVE, 'insuff_clear_date is null' => null));

                $data['reinitiated'] = ($cget_details[0]['var_filter_status'] == 'Closed' || $cget_details[0]['var_filter_status'] == 'closed') ? '1' : '2';


                $data['insuff_reason_list'] = $this->insuff_reason_list(9);

                $data['insuff_raise_message'] = (!empty($check_insuff_raise) ? 'Insufficiency raised on ' . convert_db_to_display_date($check_insuff_raise[0]['insuff_raised_date']) . ' for ' . $check_insuff_raise[0]['insff_reason'] : '');

                $data['check_insuff_raise'] = ((!empty($check_insuff_raise)) ? 'disabled' : '');
                $data['insuff_raise_id'] = (!empty($check_insuff_raise) ? $check_insuff_raise[0]['id'] : '');

                
                echo $this->load->view('admin/social_media_edit_view_inside', $data, true);
            } else {
                echo "<h3>Something went wrong, please try again</h3>";
            }
        }
    }

    public function update_form()
    {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('social_media_id', 'ID', 'required');

            $this->form_validation->set_rules('has_case_id', 'Assigned To Executive', 'required');

            $this->form_validation->set_rules('clientid', 'Client', 'required');

            $this->form_validation->set_rules('social_media_com_ref', 'Component Ref', 'required');

            $this->form_validation->set_rules('candsid', 'Candidate', 'required');

           

            if ($this->form_validation->run() == false) {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');
            } else {
                $frm_details = $this->input->post();

                $file_upload_path = SITE_BASE_PATH . SOCIAL_MEDIA . $frm_details['clientid'];
                if (!folder_exist($file_upload_path)) {
                    mkdir($file_upload_path, 0777);
                } else if (!is_writable($file_upload_path)) {
                    array_push($error_msgs, 'Problem while uploading');
                }

                $tat_day = $this->get_client_entity_package_tat_day(array('tbl_clients_id' => $frm_details['clientid'], 'entity' => $frm_details['entity_id'], 'package' => $frm_details['package_id']));

                $get_holiday1 = $this->get_holiday();

                $get_holiday = array_map('current', $get_holiday1);

                $closed_date = getWorkingDays(convert_display_to_db_date($frm_details['iniated_date']), $get_holiday, $tat_day[0]['tat_social_media']);

                $field_array = array('clientid' => $frm_details['clientid'],
                    'candsid' => $frm_details['candsid'],
                    'social_media_com_ref' => $frm_details['social_media_com_ref'],
                    'iniated_date' => convert_display_to_db_date($frm_details['iniated_date']),
                    'build_date' => $frm_details['build_date'],
                    'mode_of_veri' => $frm_details['mode_of_veri'],
                    'modified_on' => date(DB_DATE_FORMAT),
                    'modified_by' => $this->user_info['id'],
                    'has_case_id' => $frm_details['has_case_id'],
                    'has_assigned_on' => date(DB_DATE_FORMAT),
                    'is_bulk_uploaded' => 0,
                    "due_date" => $closed_date,
                    "tat_status" => "IN TAT",
                );

                $result = $this->social_media_model->save(array_map('strtolower', $field_array), array('id' => $frm_details['social_media_id']));

                

                $config_array = array('file_upload_path' => $file_upload_path, 'file_permission' => 'jpeg|jpg|png|pdf|docx|xls|xlsx', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 1000, 'file_id' => $frm_details['social_media_id'], 'component_name' => 'social_media_id');
                if (empty($error_msgs)) {
                    if ($_FILES['attchments']['name'][0] != '') {
                        $config_array['files_count'] = count($_FILES['attchments']['name']);
                        $config_array['file_data'] = $_FILES['attchments'];
                        $config_array['type'] = 0;
                        $retunr_de = $this->file_upload_multiple($config_array);
                        if (!empty($retunr_de)) {
                            $this->common_model->common_insert_batch('social_media_files', $retunr_de['success']);
                        }
                    }

                }

                if ($result) {
                    $user_activity_data = $this->common_model->user_actity_data(array('component' => "Social Media",
                        'ref_no' => $frm_details['social_media_com_ref'], 'candidate_name' => $frm_details['CandidateName'], 'created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id'], 'activity_type' => 'Manual', 'action' => 'Edit'));

                    auto_update_tat_status($frm_details['candsid']);

                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'Record Successfully Updated';

                    $json_array['redirect'] = ADMIN_SITE_URL . 'social_media';
                } else {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Something went wrong,please try again';
                }
            }
            echo_json($json_array);
        }
    }

    public function view_details($social_media_id = '')
    {
        if (!empty($social_media_id)) {
            $details = $this->social_media_model->get_all_social_media_record(array('social_media.id' => decrypt($social_media_id)));

            if ($social_media_id && !empty($details)) {
                $data['header_title'] = 'Social Media Verification';

                $data['get_cands_details'] = $this->candidate_entity_pack_details($details[0]['cands_id']);
                $data['states'] = $this->get_states();
                $data['selected_data'] = $details[0];
        

                $data['assigned_user_id']  = $this->get_reporting_manager_for_execituve($details[0]['clientid']);

                $data['mode_of_verification'] = $this->social_media_model->get_mode_of_verification(array('entity' => $data['get_cands_details']['entity_id'], 'package' => $data['get_cands_details']['package_id'], 'tbl_clients_id' => $data['get_cands_details']['clientid']));

                $check_insuff_raise = $this->social_media_model->select_insuff(array('social_media_id' => decrypt($social_media_id), 'status' => STATUS_ACTIVE, 'insuff_clear_date is null' => null));

                $data['reinitiated'] = ($details[0]['var_filter_status'] == 'Closed' || $details[0]['var_filter_status'] == 'closed') ? '1' : '2';

                $data['attachments'] = $this->social_media_model->select_file(array('id', 'file_name', 'real_filename', 'type'), array('social_media_id' => decrypt($social_media_id), 'status' => 1));

                $data['insuff_reason_list'] = $this->insuff_reason_list(9);

                $data['insuff_raise_message'] = (!empty($check_insuff_raise) ? 'Insufficiency raised on ' . convert_db_to_display_date($check_insuff_raise[0]['insuff_raised_date']) . ' for ' . $check_insuff_raise[0]['insff_reason'] : '');

                $data['check_insuff_raise'] = ((!empty($check_insuff_raise)) ? 'disabled' : '');
                $data['insuff_raise_id'] = (!empty($check_insuff_raise) ? $check_insuff_raise[0]['id'] : '');

                $this->load->view('admin/header', $data);

                $this->load->view('admin/social_media_edit_view');

                $this->load->view('admin/footer');
            } else {
                show_404();
            }
        } else {
            $this->index();
        }
    }

    public function assign_to_executive()
    {
        $json_array = array();
        if ($this->input->is_ajax_request() && ($this->permission['access_identity_list_re_assign'] == '1')) {
            $frm_details = $this->input->post();

            if ($frm_details['users_list'] != 0 && $frm_details['cases_id'] != "") {
                $return = $this->common_model->update_in('identity', array('has_case_id' => $frm_details['users_list'], 'has_assigned_on' => date(DB_DATE_FORMAT)), array('where_id' => $frm_details['cases_id']));
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

    public function add_result_model($where_id, $url)
    {
        $details = $this->social_media_model->get_all_social_media_record(array('social_media.id' => $where_id));

        if ($where_id && !empty($details)) {

            $candidate_details = $this->social_media_model->select_social_media_details('candidates_info',array('CandidatesContactNumber','ContactNo1','ContactNo2','cands_state','gender','DateofBirth'),array('candidates_info.id' => $details[0]['cands_id']));

            $data['check_insuff_raise'] = '';

            $data['details'] = $details[0];

            $data['candidate_details'] = $candidate_details[0];

            $data['url'] = $url;

            $data['attachments'] = $this->social_media_model->select_file(array('id', 'file_name', 'status'), array('social_media_id' => $where_id, 'type' => 1));

            echo $this->load->view('admin/social_media_add_result_model_view', $data, true);
        } else {
            echo "<h4>Record Not Found</h4>";
        }
    }

    public function approve_first_qc()
    {
        if ($this->input->is_ajax_request()) {

            $frist_qc_id = $this->input->post('frist_qc_id');
            $cands_id = $this->input->post('frist_cands_id');
            $comp_id = $this->input->post('frist_comp_id');
            $cands_name = $this->input->post('frist_cands_name');
            $ref_no = $this->input->post('frist_ref_no');

            $accepted_on = date(DB_DATE_FORMAT);

            $result = $this->identity_model->save_first_qc_result(array("first_qc_approve" => "First QC Approve", "first_qc_updated_on" => $accepted_on, "first_qu_updated_by" => $this->user_info['id']), array("identity_id" => $comp_id, "id" => $frist_qc_id, "candsid" => $cands_id));

            if ($result) {
                $user_activity_data = $this->common_model->user_actity_data(array('component' => "Identity",
                    'ref_no' => $ref_no, 'candidate_name' => $cands_name, 'created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id'], 'activity_type' => 'Manual', 'action' => 'First QC Approved'));

                all_component_closed_qc_status($cands_id);

                $json_array['status'] = SUCCESS_CODE;

                $json_array['message'] = 'First QC Approve';

            } else {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = 'Something went wrong,please try again';
            }
        } else {
            $json_array['status'] = ERROR_CODE;

            $json_array['message'] = 'Something went wrong,please try again';
        }
        echo_json($json_array);
    }

    public function rejected_first_qc()
    {

        if ($this->input->is_ajax_request()) {

            $comp_id = $this->input->post('frist_comp_id');
            $frist_qc_id = $this->input->post('frist_qc_id');
            $rejected_reason = $this->input->post('reject_reason');
            $cands_id = $this->input->post('frist_cands_id');
            $rejected_on = date(DB_DATE_FORMAT);
            $cands_name = $this->input->post('frist_cands_name');
            $ref_no = $this->input->post('frist_ref_no');

            $result = $this->identity_model->save_first_qc_result(array("first_qu_reject_reason" => $rejected_reason, "first_qc_approve" => '', "first_qu_updated_by" => $this->user_info['id'], "first_qc_updated_on" => $rejected_on, "verfstatus" => 13, "var_filter_status" => "WIP", "var_report_status" => "WIP"), array("identity_id" => $comp_id, "id" => $frist_qc_id, "candsid" => $cands_id));

            if ($result) {
                $user_activity_data = $this->common_model->user_actity_data(array('component' => "Identity",
                    'ref_no' => $ref_no, 'candidate_name' => $cands_name, 'created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id'], 'activity_type' => 'Manual', 'action' => 'First QC Rejected'));

                $json_array['status'] = SUCCESS_CODE;

                $json_array['message'] = 'First QC Rejected';
            } else {

                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = 'Something went wrong,please try again';

            }
        } else {
            $json_array['status'] = ERROR_CODE;

            $json_array['message'] = 'Something went wrong,please try again';
        }
        echo_json($json_array);
    }

    public function add_verificarion_result()
    {
        if ($this->input->is_ajax_request()) {
            try {
                $this->form_validation->set_rules('social_media_id', 'ID', 'required');
                if ($this->form_validation->run() == false) {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = validation_errors('', '');
                } else {
                    $frm_details = $this->input->post();

                    if (($frm_details['action_val'] != "Select") || ($frm_details['activity_last_id'] != "")) {

                        $verfstatus = status_id_frm_db(array('status_value' => $frm_details['action_val'], 'components_id' => 6));

                        $field_array = array('verfstatus' => $verfstatus['id'],
                            'var_filter_status' => $verfstatus['filter_status'],
                            'var_report_status' => $verfstatus['report_status'],
                            'clientid' => $frm_details['clientid'],
                            'candsid' => $frm_details['candidates_info_id'],
                            'social_media_id' => $frm_details['social_media_id'],
                            'closuredate' => convert_display_to_db_date($frm_details['closuredate']),
                            'remarks' => $frm_details['remarks'],
                            'mode_of_verification' => $frm_details['mode_of_verification'],
                            "modified_on" => date(DB_DATE_FORMAT),
                            "modified_by" => $this->user_info['id'],
                            'activity_log_id' => $frm_details['activity_last_id'],
                        );

                        if (isset($frm_details['remove_file'])) // delete uploaded file
                        {
                            $this->social_media_model->delete_uploaded_file($frm_details['remove_file']);
                        }
                        if (isset($frm_details['add_file'])) // delete uploaded file
                        {
                            $this->social_media_model->add_uploaded_file($frm_details['add_file']);
                        }

                        $field_array = array_map('strtolower', $field_array);

                        $result = $this->social_media_model->save_update_ver_result($field_array, array('id' => $frm_details['social_media_result_id']));

                        $result_social_media = $this->social_media_model->save_update_result_social_media(array_map('strtolower', $field_array));

                        $file_upload_path = SITE_BASE_PATH . SOCIAL_MEDIA . $frm_details['clientid'];
                        $config_array = array(
                                'file_upload_path'  => $file_upload_path, 
                                'file_permission'   => 'jpeg|jpg|png|pdf', 
                                'file_size'         => BULK_UPLOAD_MAX_SIZE_MB * 1000, 
                                'file_id'           => $frm_details['social_media_id'], 
                                'component_name'    => 'social_media_id'
                        );

                        if (empty($error_msgs)) {
                            if ($_FILES['attchments_ver']['name'][0] != '') {
                                $config_array['files_count'] = count($_FILES['attchments_ver']['name']);
                                $config_array['file_data'] = $_FILES['attchments_ver'];
                                $config_array['type'] = 1;
                                $retunr_de = $this->file_upload_library->file_upload_multiple($config_array, true);
                                if (!empty($retunr_de['success'])) {
                                    $this->common_model->common_insert_batch('social_media_files', $retunr_de['success']);
                                }
                            }

                           
                        }

                        if ($result) {
                            auto_update_overall_status($frm_details['candidates_info_id']);

                    //        all_component_closed_qc_status($frm_details['candidates_info_id']);

                            $json_array['status'] = SUCCESS_CODE;

                            $json_array['message'] = 'Record Successfully Updated';

                            $json_array['redirect'] = ADMIN_SITE_URL . 'social_media';
                        } else {
                            $json_array['status'] = ERROR_CODE;

                            $json_array['message'] = 'Something went wrong,please try again';
                        }
                    } else {
                        $json_array['status'] = ERROR_CODE;

                        $json_array['message'] = 'Something went wrong,please try again';
                    }
                }
                echo_json($json_array);
            } catch (Exception $e) {
                log_message('error', 'Social Media::add_verificarion_result');
                log_message('error', $e->getMessage());
            }
        }
    }

    public function add_verificarion_ver_result()
    {
        if ($this->input->is_ajax_request()) {
            try {
                $this->form_validation->set_rules('social_media_id', 'ID', 'required');

                if ($this->form_validation->run() == false) {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = validation_errors('', '');
                } else {
                    $frm_details = $this->input->post();

                    $field_array = array(
                        'clientid' => $frm_details['clientid'],
                        'candsid' => $frm_details['candidates_info_id'],
                        'social_media_id' => $frm_details['social_media_id'],
                        'closuredate' => convert_display_to_db_date($frm_details['closuredate']),
                        'remarks' => $frm_details['remarks'],
                        'mode_of_verification' => $frm_details['mode_of_verification'],
                        "modified_on" => date(DB_DATE_FORMAT),
                        "modified_by" => $this->user_info['id'],
                    );

                    if (isset($frm_details['remove_file'])) // delete uploaded file
                    {
                        $this->social_media_model->delete_uploaded_file($frm_details['remove_file']);
                    }
                    if (isset($frm_details['add_file'])) // delete uploaded file
                    {
                        $this->social_media_model->add_uploaded_file($frm_details['add_file']);
                    }

                    $field_array = array_map('strtolower', $field_array);

                    $result = $this->social_media_model->save_update_ver_result($field_array, array('id' => $frm_details['social_media_result_id']));

                    $result_social_media = $this->social_media_model->save_update_result_social_media(array_map('strtolower', $field_array), array('id' => $frm_details['result_update_id']));

                    $file_upload_path = SITE_BASE_PATH . SOCIAL_MEDIA . $frm_details['clientid'];                    
                    $config_array = array(
                        'file_upload_path'  => $file_upload_path, 
                        'file_permission'   => 'jpeg|jpg|png|pdf', 
                        'file_size'         => BULK_UPLOAD_MAX_SIZE_MB * 2000, 
                        'file_id'           => $frm_details['social_media_id'], 
                        'component_name'    => 'social_media_id'
                    );

                    if (isset($_FILES['attchments_ver']['name'][0]) &&  $_FILES['attchments_ver']['name'][0] != '') {
                        $config_array['files_count']    = count($_FILES['attchments_ver']['name']);
                        $config_array['file_data']      = $_FILES['attchments_ver'];
                        $config_array['type']           = 1;
                        $retunr_de = $this->file_upload_library->file_upload_multiple($config_array, true);
                        
                        if (!empty($retunr_de['success'])) {
                            $this->common_model->common_insert_batch('social_media_files', $retunr_de['success']);
                        }
                    } 


                    if ($result) {
                        auto_update_overall_status($frm_details['candidates_info_id']);


                        $json_array['status'] = SUCCESS_CODE;

                        $json_array['message'] = 'Record Successfully Updated';

                        $json_array['redirect'] = ADMIN_SITE_URL . 'social_media';
                    } else {
                        $json_array['status'] = ERROR_CODE;

                        $json_array['message'] = 'Something went wrong,please try again';
                    }
                }
                echo_json($json_array);
            } catch (Exception $e) {
                log_message('error', 'Identity::add_verificarion_ver_result');
                log_message('error', $e->getMessage());
            } 
        }
    }

    public function social_media_result_list($emp_id = '')
    {
        if ($emp_id && $this->input->is_ajax_request()) {
            $social_media_result = $this->social_media_model->select_result_log(array('social_media_id' => $emp_id, 'activity_log_id !=' => null));

            $html_view = '<thead><tr><th>Created On</th><th>Created By</th><th>Action</th><th>Activit Mode</th><th>Attachment</th><th>Activity Type</th><th>Activity Status</th><th>View</th></tr></thead>';

            if (!empty($social_media_result[0]['id'])) {
                $l = 1;

                foreach ($social_media_result as $key => $value) {

                    $file = "&nbsp;&nbsp;&nbsp;&nbsp;";
                    if ($value['file_names']) {
                        $files = explode(',', $value['file_names']);

                        for ($i = 0; $i < count($files); $i++) {
                            $url = "'" . SITE_URL . SOCIAL_MEDIA . $value['clientid'] . '/';
                            $actual_file = $files[$i] . "'";
                            $myWin = "'" . "myWin" . "'";
                            $attribute = "'" . "height=250,width=480" . "'";

                            $file .= '<a href="javascript:;" onClick="myOpenWindow(' . $url . $actual_file . ',' . $myWin . ',' . $attribute . '); return false"><i class="fa fa-file-photo-o" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;';
                        }
                    }
                    $html_view .= '<tr>';
                    $html_view .= "<td>" . convert_db_to_display_date($value['created_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12) . "</td>";
                    $html_view .= "<td>" . $value['created_by'] . "</td>";
                    $html_view .= "<td>" . $value['activity_action'] . "</td>";
                    $html_view .= "<td>" . $value['activity_mode'] . "</td>";
                    $html_view .= "<td>" . $file . "</td>";
                    $html_view .= "<td>" . $value['activity_type'] . "</td>";
                    $html_view .= "<td>" . $value['activity_status'] . "</td>";
                    if ($l == 1) {
                        $html_view .= '<td><button data-id="showAddResultModel" data-url ="' . ADMIN_SITE_URL . 'social_media/social_media_result_list_idwise/' . $value['id'] . '/' . str_replace(" ", "", $value['activity_action']) . '" data-toggle="modal" data-target="#showAddResultModel1"  class="btn-info  showAddResultModel"> View </button></td>';
                    } else {
                        $html_view .= '<td> </td>';
                    }
                    $html_view .= '</tr>';
                    $l++;
                }
            } else {
                $html_view .= "<tr><td colspan = '8'>No Record Found</td></tr>";
            }

            $json_array['status'] = SUCCESS_CODE;
            $json_array['message'] = $html_view;
        } else {
            $json_array['status'] = SUCCESS_CODE;
            $json_array['message'] = "<tr><td>Something went wrong, please try again!<td></tr>";
        }
        echo_json($json_array);
    }

    public function social_media_result_list_idwise($where_id, $url)
    {
        $details = $this->social_media_model->select_result_log1(array('social_media_ver_result.id' => $where_id));

        if ($where_id && !empty($details)) {

            $candidate_details = $this->social_media_model->select_social_media_details('candidates_info',array('CandidatesContactNumber','ContactNo1','ContactNo2','cands_state','gender','DateofBirth'),array('candidates_info.id' => $details[0]['candsid']));

            $data['candidate_details'] = $candidate_details[0];
            
            $data['url'] = $url;
            $data['check_insuff_raise'] = '';
            $data['details'] = $details[0];

            $data['attachments'] = $this->social_media_model->select_file(array('id', 'file_name', 'status'), array('social_media_id' => $details[0]['social_media_id'], 'type' => 1));

            echo $this->load->view('admin/social_media_add_result_model_view_log', $data, true);
        } else {
            echo "<h4>Record Not Found</h4>";
        }
    }

    public function insuff_raised()
    {
        $json_array = array();
        $json_array['status'] = ERROR_CODE;
        $json_array['message'] = 'Something went wrong,please try again';

        if ($this->input->is_ajax_request()) {
            $social_media_id = $this->input->post('update_id');

            $insff_reason = $this->input->post('insff_reason');

            $insff_date = $this->input->post('txt_insuff_raise');

            $ref_no = $this->input->post('component_ref_no');

            $CandidateName = $this->input->post('CandidateName');

            $check = $this->social_media_model->select_insuff(array('social_media_id' => $social_media_id, 'status !=' => 3, 'insuff_clear_date is null' => null));

            if (empty($check)) {
                $result = $this->social_media_model->save_update_insuff(array('insuff_raised_date' => convert_display_to_db_date($insff_date), 'insuff_raise_remark' => $this->input->post('insuff_raise_remark'), 'status' => STATUS_ACTIVE, 'insff_reason' => $insff_reason, 'created_by' => $this->user_info['id'], 'social_media_id' => $social_media_id, 'auto_stamp' => 1));

                if ($result) {
                    $user_activity_data = $this->common_model->user_actity_data(array('component' => "Social Media", 'ref_no' => $ref_no, 'candidate_name' => $CandidateName, 'created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id'], 'activity_type' => 'Manual', 'action' => 'Insuff Raised'));
                }

                auto_update_overall_status($this->input->post('candidates_info_id'));

                $json_array['status'] = SUCCESS_CODE;

                $json_array['message'] = 'Record Successfully Inserted';

            } else {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = 'already insuff raised, please close this and raise again';
            }
        }
        echo_json($json_array);
    }

    public function insuff_tab_view($emp_id = '')
    {
        if ($this->input->is_ajax_request() && $emp_id) {
            $data['insuff_details'] = $this->social_media_model->select_insuff_join(array('social_media_id' => $emp_id));

            echo $this->load->view('admin/social_media_insuff_view', $data, true);
        } else {
            echo "<p>Something went wrong, please try again.</p>";
        }
    }

    public function insuff_raised_data()
    {
        if ($this->input->is_ajax_request()) {
            $insuff_data = $this->input->post('insuff_data');

            $result = $this->social_media_model->select_insuff(array('id' => $insuff_data));
            if (!empty($result)) {
                $result = $result[0];
                $result['insuff_raised_date'] = convert_db_to_display_date($result['insuff_raised_date']);
                $result['insuff_clear_date'] = convert_db_to_display_date($result['insuff_clear_date']);
            }
            echo_json($result);
        }
    }

    public function insuff_edit_clear_raised_data()
    {
        if ($this->input->is_ajax_request()) {
            $insuff_data = $this->input->post('insuff_data');

            $result = $this->social_media_model->select_insuff(array('id' => $insuff_data));

            if (!empty($result)) {
                $data['insuff_reason_list'] = $this->insuff_reason_list(9);
                $data['insuff_details'] = $result[0];
                echo $this->load->view('admin/insuff_edit_and_clear_view', $data, true);
            }
        }
    }

    public function identity_reinitiated_date()
    {

        $json_array = array();

        if ($this->input->is_ajax_request()) {

            $identityid = $this->input->post('update_id');
            $reinitiated_date = $this->input->post('reinitiated_date');
            $reinitiated_remark = $this->input->post('reinitiated_remark');
            $clientid = $this->input->post('clientid');

            $check = $this->identity_model->select_reinitiated_date(array('id' => $identityid));

            if ($check[0]['identity_re_open_date'] == "0000-00-00" || $check[0]['identity_re_open_date'] == "") {
                $reinitiated_dates = $reinitiated_date;
            } else {
                $reinitiated_dates = $check[0]['identity_re_open_date'] . "||" . $reinitiated_date;
            }

            $result = $this->identity_model->save_update_initiated_date(array('identity_re_open_date' => $reinitiated_dates, 'identity_reinitiated_remark' => $reinitiated_remark), array('id' => $identityid));

            $result_identity = $this->identity_model->save_update_initiated_date_identity(array('verfstatus' => 26, 'var_filter_status' => "WIP", 'var_report_status' => "WIP", 'first_qc_approve' => "", 'first_qc_updated_on' => "", 'first_qu_reject_reason' => "", 'first_qu_updated_by' => ""), array('identity_id' => $identityid));

            $file_upload_path = SITE_BASE_PATH . IDENTITY . $clientid;
            if (!folder_exist($file_upload_path)) {
                mkdir($file_upload_path, 0777);
            } else if (!is_writable($file_upload_path)) {
                array_push($error_msgs, 'Problem while uploading');
            }

            $config_array = array('file_upload_path' => $file_upload_path, 'file_permission' => 'jpeg|jpg|png|pdf|docx|xls|xlsx', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 1000, 'file_id' => $identityid, 'component_name' => 'identity_id');

            if ($_FILES['attachment_reinitiated']['name'][0] != '') {
                $config_array['files_count'] = count($_FILES['attachment_reinitiated']['name']);
                $config_array['file_data'] = $_FILES['attachment_reinitiated'];
                $config_array['type'] = 2;
                $retunr_cd = $this->file_upload_multiple($config_array);
                if (!empty($retunr_cd)) {
                    $this->common_model->common_insert_batch('identity_files', $retunr_cd['success']);
                }
            }

            $result_identity_activity_data = $this->identity_model->initiated_date_identity_activity_data(array('candsid' => $this->input->post('candidates_info_id'), 'comp_table_id' => $identityid, 'action' => "Re-Initiated", '  activity_status' => "Re-Initiated", 'remarks' => 'Client requested to re-verify the case [' . $reinitiated_remark . ']', 'created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id']));

            if ($result && $result_identity) {

                auto_update_overall_status($this->input->post('candidates_info_id'));

                $json_array['status'] = SUCCESS_CODE;

                $json_array['message'] = 'Record Successfully Inserted';
            }

        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['message'] = 'Something went wrong,please try again';
        }
        echo_json($json_array);
    }

    public function insuff_clear()
    {
        $json_array = array();

        $frm_details = $this->input->post();

        if ($this->input->is_ajax_request() && $this->permission['access_social_media_list_insuff_clear'] == 1) {
            if (convert_display_to_db_date($frm_details['insuff_clear_date']) >= convert_display_to_db_date($frm_details['check_insuff_raise'])) {
                $insuff_date = $frm_details['insuff_clear_date'];

                $hold_days = getNetWorkDays($frm_details['check_insuff_raise'], $frm_details['insuff_clear_date']);

                $date_tat = $this->social_media_model->get_date_for_update(array('id' => $frm_details['clear_update_id']));

                $due_date = $date_tat[0]['due_date'];

                $today = date("Y-m-d");

                //    $due_date_increament = date('Y-m-d', strtotime($due_date .' +'.$hold_days. 'day'));

                // $tat_count1  = $tat_count - $hold_days;

                $get_holiday1 = $this->get_holiday();

                $get_holiday = array_map('current', $get_holiday1);

                $closed_date = getWorkingDays_increament($due_date, $get_holiday, $hold_days);

                $x = 1;
                $counter_1 = 0;
                while ($x <= 10) {

                    $yesterday = date('Y-m-d', strtotime('-' . $x . ' day', strtotime($closed_date)));

                    $today_date1 = strtotime($yesterday);

                    $day_name1 = date("D", $today_date1);

                    if ($day_name1 == 'Sat' || $day_name1 == 'Sun' || in_array($yesterday, $get_holiday) == true) {

                        $counter_1 += 1;
                    } else {
                        break;
                    }

                    $x++;
                }

                $yesterday_main = $yesterday;

                $y = 1;
                $counter_2 = 0;
                while ($y <= 10) {

                    $yesterday_ago = date('Y-m-d', strtotime('-' . $y . ' day', strtotime($yesterday_main)));

                    $today_date2 = strtotime($yesterday_ago);

                    $day_name2 = date("D", $today_date2);

                    if ($day_name2 == 'Sat' || $day_name2 == 'Sun' || in_array($yesterday_ago, $get_holiday) == true) {

                        $counter_2 += 1;
                    } else {
                        break;
                    }
                    $y++;
                }

                $yesterday_ago_main = $yesterday_ago;

                $date_array = array($yesterday_main, $yesterday_ago_main);

                if ($closed_date < $today) {
                    $new_tat = "OUT TAT";
                } elseif ($closed_date == $today) {
                    $new_tat = "TDY TAT";
                } elseif (in_array(date("Y-m-d"), $date_array) == true) {
                    $new_tat = "AP TAT";
                } else {
                    $new_tat = "IN TAT";
                }

                $result_due_date = $this->social_media_model->update_due_date(array('due_date' => $closed_date, 'tat_status' => $new_tat), array('id' => $frm_details['clear_update_id']));

                $fields = array('insuff_clear_date' => convert_display_to_db_date($insuff_date), 'insuff_remarks' => $frm_details['insuff_remarks'], 'insuff_cleared_timestamp' => date(DB_DATE_FORMAT), 'status' => 2, 'insuff_cleared_by' => $this->user_info['id'], 'auto_stamp' => 2, 'hold_days' => $hold_days);

                $result = $this->social_media_model->save_update_insuff($fields, array('id' => $frm_details['insuff_clear_id']));

        

                if ($result) {
                    $user_activity_data = $this->common_model->user_actity_data(array('component' => "Social Media", 'ref_no' => $frm_details['component_ref_no'], 'candidate_name' => $frm_details['CandidateName'], 'created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id'], 'activity_type' => 'Manual', 'action' => 'Insuff Cleared'));
                }

                $error_msgs = $file_array = array();

                $file_upload_path = SITE_BASE_PATH . SOCIAL_MEDIA . $frm_details['clear_clientid'];

                if (!folder_exist($file_upload_path)) {
                    mkdir($file_upload_path, 0777);
                }

                if (!empty($_FILES['clear_attchments']['name'][0])) {
                    $files_count = count($_FILES['clear_attchments']['name']);

                    for ($i = 0; $i < $files_count; $i++) {
                        $file_name = $_FILES['clear_attchments']['name'][$i];

                        $file_info = pathinfo($file_name);

                        $new_file_name = preg_replace('/[[:space:]]+/', '_', $file_info['filename']);

                        $new_file_name = $new_file_name . '_' . DATE(UPLOAD_FILE_DATE_FORMAT_FILE_NAME);

                        $new_file_name = str_replace('.', '_', $new_file_name);

                        $new_file_name = preg_replace('/[^A-Za-z0-9\-]/', '_', $new_file_name);

                        $file_extension = $file_info['extension'];

                        $new_file_name = $new_file_name . '.' . $file_extension;

                        $_FILES['attchment']['name'] = $new_file_name;

                        $_FILES['attchment']['tmp_name'] = $_FILES['clear_attchments']['tmp_name'][$i];

                        $_FILES['attchment']['error'] = $_FILES['clear_attchments']['error'][$i];

                        $_FILES['attchment']['size'] = $_FILES['clear_attchments']['size'][$i];

                        $config['upload_path'] = $file_upload_path;

                        $config['file_name'] = $new_file_name;

                        $config['allowed_types'] = 'jpeg|jpg|png|pdf';

                        $config['file_ext_tolower'] = true;

                        $config['remove_spaces'] = true;

                        $config['max_size'] = BULK_UPLOAD_MAX_SIZE_MB * 1000;

                        $this->load->library('upload', $config);

                        $this->upload->initialize($config);

                        if ($this->upload->do_upload('attchment')) {
                            array_push($file_array, array(
                                'file_name' => $new_file_name,
                                'real_filename' => $file_name,
                                'social_media_id' => $frm_details['clear_update_id'],
                                'type' => 2)
                            );
                        } else {
                            array_push($error_msgs, $this->upload->display_errors('', ''));

                            $json_array['status'] = ERROR_CODE;

                            $json_array['e_message'] = implode('<br>', $error_msgs);

                        }
                    }

                    if (!empty($file_array)) {
                        $this->social_media_model->uploaded_files($file_array);
                    }
                }

                if ($result) {

                    auto_update_overall_status($frm_details['candidates_info_id']);

                    auto_update_tat_status($frm_details['candidates_info_id']);

                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'Record Successfully Inserted';
                } else {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Something went wrong,please try again';
                }
            } else {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = 'Insuff cleared date can not be less than the raised date.';
            }
        } else {
            $json_array['message'] = "Don't have permission to access this function";

            $json_array['status'] = ERROR_CODE;
        }
        echo_json($json_array);
    }

    public function insuff_delete()
    {
        $json_array = array();

        $insuff_data = $this->input->post('insuff_data');
        if ($this->input->is_ajax_request() && $this->permission['access_social_media_list_insuff_delete'] == 1) {
            $fields = array('status' => 3, 'modified_on' => date(DB_DATE_FORMAT), 'modified_by' => $this->user_info['id']);

            $result = $this->social_media_model->save_update_insuff($fields, array('id' => $insuff_data));

            if ($result) {

                auto_update_overall_status($this->input->post('candidates_info_id'));

                $json_array['status'] = SUCCESS_CODE;

                $json_array['message'] = 'Deleted Successfully';
            } else {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = 'Something went wrong,please try again';
            }
        } else {
            $json_array['message'] = "Don't have permission to access this function";

            $json_array['status'] = ERROR_CODE;
        }
        echo_json($json_array);
    }

    public function update_insuff_details()
    {
        if ($this->input->is_ajax_request()) {
            $frm_details = $this->input->post();

            $fields = array('insuff_raised_date' => convert_display_to_db_date($frm_details['txt_insuff_raise']),
                'insff_reason' => $frm_details['insff_reason'],
                'insuff_raise_remark' => $frm_details['insuff_raise_remark'],
                'insuff_clear_date' => convert_display_to_db_date($frm_details['insuff_clear_date']),
                'insuff_remarks' => $frm_details['insuff_remarks'],
                'modified_on' => date(DB_DATE_FORMAT),
                'modified_by' => $this->user_info['id'],
                'status' => 4,
            );

            if ($frm_details['insuff_clear_date'] != '') {
                $clear_date = $frm_details['insuff_clear_date'];

                $fields['insuff_cleared_timestamp'] = date(DB_DATE_FORMAT);
                $fields['insuff_cleared_by'] = $this->user_info['id'];
            } else {
                $clear_date = date(DATE_ONLY);
            }

            $fields['hold_days'] = getNetWorkDays($frm_details['txt_insuff_raise'], $clear_date);

            $result = $this->social_media_model->save_update_insuff($fields, array('id' => $frm_details['id']));

            if ($result) {
                auto_update_overall_status($frm_details['candidates_info_id']);

                auto_update_tat_status($frm_details['candidates_info_id']);

                $json_array['status'] = SUCCESS_CODE;

                $json_array['message'] = 'Updated Successfully';

            } else {
                $json_array['status'] = SUCCESS_CODE;

                $json_array['message'] = "Something went wrong, please try again!";
            }
            echo_json($json_array);
        }
    }

    public function componet_html_form($cmp_id)
    {
        if ($this->input->is_ajax_request() && $cmp_id) {
            $details = $this->identity_model->get_all_identity_record(array('identity.id' => decrypt($cmp_id)));

            if (!empty($details)) {
                $data['details'] = $details[0];
                echo $this->load->view('admin/identity_add_result_model_view_first_qc', $data, true);
            } else {
                echo "<h4>Record Not Found</h4>";
            }

        } else {
            echo "<p>Something went wrong, please try again.</p>";
        }
    }

    public function bulk_upload_identity()
    {

        if ($this->input->is_ajax_request()) {
            $file_upload_path = SITE_BASE_PATH . IDENTITY;

            if (!folder_exist($file_upload_path)) {
                mkdir($file_upload_path, 0777,true);
            } else if (!is_writable($file_upload_path)) {
                $message = 'Problem while uploading, folder permission';
            }

            $uplaod_details = array('file_upload_path' => $file_upload_path, 'file_data' => $_FILES['cands_bulk_sheet'], 'file_permission' => 'csv|xls|xlsx', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 100);
            $upload_result = $this->file_uplod($uplaod_details);
            $record = array();

            if (!empty($upload_result) && $upload_result['status'] == true) {

                $this->load->model('candidates_model');

                $raw_filename = $_FILES['cands_bulk_sheet']['tmp_name'];

                $headerLine = 0;
                $file = fopen($raw_filename, "r");

                $this->load->library('excel_reader', array('file_name' => $file_upload_path . '/' . $upload_result['file_name']));

                $excel_handler = $this->excel_reader->file_handler;

                $excel_data = $excel_handler->rows();

                if (!empty($excel_data)) {
                    unset($excel_data[0]);

                    $excel_data = array_map("unserialize", array_unique(array_map("serialize", $excel_data)));

                    foreach ($excel_data as $value) {

                        if (count($value) < 8) {
                            continue;
                        }

                        $check_record_exits = $this->candidates_model->select(true, array('id', 'clientid', 'entity', 'package'), array('cmp_ref_no' => strtolower($value[0])));

                        if (!empty($check_record_exits) && $value[0] != "") {

                            $users_id  = $this->get_reporting_manager_for_executive($check_record_exits['clientid']); 

                            $user_id =  $users_id[0]['id'];

                            $tat_day = $this->get_client_entity_package_tat_day(array('tbl_clients_id' => $check_record_exits['clientid'], 'entity' => $check_record_exits['entity'], 'package' => $check_record_exits['package']));

                            $get_holiday1 = $this->get_holiday();

                            $get_holiday = array_map('current', $get_holiday1);

                            $closed_date = getWorkingDays(get_date_from_timestamp($value[1]), $get_holiday, $tat_day[0]['tat_identity']);

                            $mode_of_verification = $this->identity_model->get_mode_of_verification(array('entity' => $check_record_exits['entity'], 'package' => $check_record_exits['package'], 'tbl_clients_id' => $check_record_exits['clientid']));

                            if (!empty($mode_of_verification)) {
                                $mode_of_verification_value = json_decode($mode_of_verification[0]['mode_of_verification']);

                                $mode_of_veri = $mode_of_verification_value->identity;
                            } else {

                                $mode_of_veri = "";
                            }

                            $field_array = array('clientid' => $check_record_exits['clientid'],
                                'candsid' => $check_record_exits['id'],
                                'identity_com_ref' => '',
                                'doc_submited' => $value[2],
                                'id_number' => $value[3],
                                'street_address' => $value[4],
                                'city' => $value[5],
                                'pincode' => $value[6],
                                'state' => $value[7],
                                'iniated_date' => get_date_from_timestamp($value[1]),
                                "identity_re_open_date" => '',
                                'created_by' => $this->user_info['id'],
                                'created_on' => date(DB_DATE_FORMAT),
                                'modified_on' => date(DB_DATE_FORMAT),
                                'modified_by' => $this->user_info['id'],
                                'has_case_id' => $user_id,
                                'has_assigned_on' => date(DB_DATE_FORMAT),
                                'is_bulk_uploaded' => 1,
                                "due_date" => $closed_date,
                                "mode_of_veri" => $mode_of_veri,
                                "status" => 1,
                                "tat_status" => "IN TAT",
                            );

                            $record = array_map('strtolower', array_map('trim', $field_array));

                            $insert_id = $this->identity_model->save($record);

                            $identity_com_ref = $this->identity_com_ref($insert_id);

                            $result_get_count = $this->common_model->get_count(array('controllers' => 'assign_identity'));

                            $total_get_count = $result_get_count + 1;

                            $result_update_count = $this->common_model->update_count(array('count' => $total_get_count), array('controllers' => 'assign_identity'));

                            auto_update_overall_status($check_record_exits['id']);
                            auto_update_tat_status($check_record_exits['id']);

                            $data['success'] = $identity_com_ref . " This Component Code Records Created Successfully";

                        }
                    }
                } else {
                    $data['message'] = 'CSV File is empty or data large then 1000 charecters';
                }

                $json_array['message'] = json_encode($data);

                $json_array['status'] = SUCCESS_CODE;

            } else {
                $json_array['message'] = $upload_result['message'];
            }
        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['message'] = 'Something went wrong, please try again';
        }
        echo_json($json_array);
    }

    public function approval_queue()
    {
        $this->load->model(array('identity_vendor_log_model'));

        $data['header_title'] = "Vendor Approve List";

        $assigned_option = array(0 => 'select');

        ($this->permission['access_identity_aq_allow'] == 1) ? $assigned_option[1] = 'Assign' : '';
        ($this->permission['access_identity_aq_allow'] == 1) ? $assigned_option[2] = 'Reject' : '';
        $data['assigned_option'] = $assigned_option;

        $data['user_list_name'] = $this->users_list_filter();

        $this->load->view('admin/header', $data);

        $this->load->view('admin/identity_vendor_aq');

        $this->load->view('admin/footer');

    }

    public function view_approval_queue()
    {

        $this->load->model(array('identity_vendor_log_model'));

        $params = $add_candidates = $data_arry = $columns = array();

        $params = $_REQUEST;

        $lists = $this->identity_vendor_log_model->get_new_list(array('identity_vendor_log.status' => 0), $params);

        $totalRecords = count($this->identity_vendor_log_model->get_new_list(array('identity_vendor_log.status' => 0), $params));

        if ($this->permission['access_identity_aq_view'] == 1) {
            $x = 0;

            foreach ($lists as $list) {
                $data_arry[$x]['checkbox'] = $list['id'];
                $data_arry[$x]['id'] = $x + 1;

                $data_arry[$x]['identity_com_ref'] = $list['identity_com_ref'];
                $data_arry[$x]['clientname'] = $list['clientname'];
                $data_arry[$x]['entity_name'] = $list['entity_name'];
                $data_arry[$x]['package_name'] = $list['package_name'];
                $data_arry[$x]['vendor_name'] = $list['vendor_name'];
                $data_arry[$x]['city'] = $list['city'];
                $data_arry[$x]['encry_id'] = ADMIN_SITE_URL . "identity/view_details/" . encrypt($list['case_id']);
                $data_arry[$x]['pincode'] = $list['pincode'];
                $data_arry[$x]['state'] = $list['state'];
                $data_arry[$x]['allocated_by'] = $list['allocated_by'];
                $data_arry[$x]['allocated_on'] = convert_db_to_display_date($list['allocated_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);

                $x++;
            }

            $json_data = array(
                "draw" => intval($params['draw']),
                "recordsTotal" => intval($totalRecords),
                "recordsFiltered" => intval($totalRecords),
                "data" => $data_arry,
            );

            echo_json($json_data);
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

    public function assign_to_vendor()
    {
        $json_array = array();

        if ($this->input->is_ajax_request() && $this->permission['access_identity_assign_identity_assign'] == true) {
            $frm_details = $this->input->post();

            $list = explode(',', $frm_details['cases_id']);

            if ($frm_details['vendor_list'] != 0 && $frm_details['cases_id'] != "" && !empty($list)) {

                $files = $update = array();
                $insert_counter = 0;
                foreach ($list as $key => $value) {

                    $update = $this->identity_model->upload_vendor_assign('identity', array('vendor_id' => $frm_details['vendor_list'], 'vendor_list_mode' => $frm_details['vendor_list_mode'], 'vendor_assgined_on' => date(DB_DATE_FORMAT)), array('id' => $value, 'vendor_id =' => 0));

                    $update1 = $this->identity_model->update_status('identity_result', array('verfstatus' => 1), array('identity_id' => $value));

                    if ($update) {

                        $insert_counter++;
                        $files[] = array(
                            'vendor_id' => $frm_details['vendor_list'],
                            'case_id' => $value,
                            "status" => 0,
                            "remarks" => '',
                            "created_by" => $this->user_info['id'],
                            "created_on" => date(DB_DATE_FORMAT),
                            "approval_by" => 0,
                            "modified_on" => null,
                            "modified_by" => '',
                        );
                    }
                }

                if (!empty($files)) {
                    $inserted = $this->common_model->common_insert_batch('identity_vendor_log', $files);
                }

                if (!empty($inserted)) {

                    $result_get_count_plus = $this->common_model->get_count(array('controllers' => 'identity/approval_queue'));

                    $total_get_count_plus = $result_get_count_plus + count($list);

                    $result_update_count_plus = $this->common_model->update_count(array('count' => $total_get_count_plus), array('controllers' => 'identity/approval_queue'));

                    $result_get_count_minus = $this->common_model->get_count(array('controllers' => 'assign_identity'));

                    $total_get_count_minus = $result_get_count_minus - count($list);

                    $result_update_count_minus = $this->common_model->update_count(array('count' => $total_get_count_minus), array('controllers' => 'assign_identity'));

                }

                $json_array['status'] = SUCCESS_CODE;
                $json_array['message'] = $insert_counter . " of " . count($list) . " Assigned Successfully";

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

    public function identity_final_assigning()
    {

        if ($this->input->is_ajax_request()) {

            if ($this->permission['access_social_media_aq_allow'] == 1) {
                $frm_details = $this->input->post();
                $action = $frm_details['action'];

                if ($frm_details['action'] == 2 && $frm_details['cases_id'] != "") {
                    $list = explode(',', $frm_details['cases_id']);

                    $update_counter = 0;
                    foreach ($list as $key => $value) {

                        $update = $this->identity_model->upload_vendor_assign('identity_vendor_log', array('status' => 2, 'remarks' => $frm_details['reject_reason'], 'modified_by' => $this->user_info['id'], 'modified_on' => date(DB_DATE_FORMAT)), array('id' => $value));

                        if ($update) {
                            $update_counter++;
                            //$return =  $this->addressver_model->save(array('vendor_id' => 0,'vendor_assgined_on' => NULL),array('id' => $value));
                        }
                    }

                    $result_get_count_plus = $this->common_model->get_count(array('controllers' => 'assign_identity'));

                    $total_get_count_plus = $result_get_count_plus + count($list);

                    $result_update_count_plus = $this->common_model->update_count(array('count' => $total_get_count_plus), array('controllers' => 'assign_identity'));

                    $result_get_count_minus = $this->common_model->get_count(array('controllers' => 'identity/approval_queue'));

                    $total_get_count_minus = $result_get_count_minus - count($list);

                    $result_update_count_minus = $this->common_model->update_count(array('count' => $total_get_count_minus), array('controllers' => 'identity/approval_queue'));

                    if ($update_counter) {

                        $json_array['status'] = SUCCESS_CODE;
                        $json_array['message'] = $update_counter . " of " . count($list) . " Rejected Successfully";

                    } else {
                        $json_array['status'] = ERROR_CODE;
                        $json_array['message'] = "Something went wrong,please try again";
                    }

                } else if ($frm_details['action'] == 1 && $frm_details['cases_id'] != "") {

                    $list = explode(',', $frm_details['cases_id']);

                    $update_counter = 0;

                    foreach ($list as $key => $value) {

                        $update = $this->identity_model->upload_vendor_assign('identity_vendor_log', array('status' => 1, 'approval_by' => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT)), array('id' => $value));

                        if ($update) {

                            $update_counter++;
                            $field_array = array('component' => 'identity',
                                'component_tbl_id' => '9',
                                'case_id' => $value,
                                'trasaction_id' => 'Txn',
                                "status" => 1,
                                "remarks" => '',
                                "approval_by" => $this->user_info['id'],
                                "approval_on" => date(DB_DATE_FORMAT),
                                "created_by" => $this->user_info['id'],
                                "created_on" => date(DB_DATE_FORMAT),
                                "vendor_status" => 0,
                                "modified_on" => null,
                                "modified_by" => '',
                            );
                            $insert_id = $this->common_model->common_insert_transaction_no('view_vendor_master_log', $field_array);
                            $update_transaction_id = $this->common_model->update_transaction_id(array('trasaction_id' => "Txn" . $insert_id), array('id' => $insert_id));
                        }
                    }

                    $result_get_count_plus = $this->common_model->get_count(array('controllers' => 'identity'));

                    $total_get_count_plus = $result_get_count_plus + count($list);

                    $result_update_count_plus = $this->common_model->update_count(array('count' => $total_get_count_plus), array('controllers' => 'identity'));

                    $result_get_count_minus = $this->common_model->get_count(array('controllers' => 'identity/approval_queue'));

                    $total_get_count_minus = $result_get_count_minus - count($list);

                    $result_update_count_minus = $this->common_model->update_count(array('count' => $total_get_count_minus), array('controllers' => 'identity/approval_queue'));

                    $json_array['status'] = SUCCESS_CODE;
                    $json_array['message'] = $update_counter . " of " . count($list) . " Assigned Successfully";

                } else {
                    $json_array['status'] = ERROR_CODE;
                    $json_array['message'] = "Select atleast one case";
                }

            } else {

                $json_array['status'] = ERROR_CODE;
                $json_array['message'] = "Access Denied, You don’t have permission to access this page";
            }
        } else {

            $json_array['status'] = ERROR_CODE;
            $json_array['message'] = "Access Denied, You don’t have permission to access this page";
        }

        echo_json($json_array);
    }

    public function vendor_logs($id)
    {
        if ($this->input->is_ajax_request() && $id) {

            $vendor_result = $this->identity_model->vendor_logs(array('component_tbl_id' => "9", "component" => "identity"), $id);

            $counter = 1;

            $html_view = '<thead><tr><th>Sr No</th><th>Trasaction Id</th><th>Vendor Name</th><th>Approved By</th><th>Approved Date</th><th>Costing</th><th>TAT</th><th>Remark</th><th>Status</th><th>Action</th></tr></thead>';
            foreach ($vendor_result as $key => $value) {
                $html_view .= '<tr>';
                $html_view .= "<td>" . $counter . "</td>";
                $html_view .= "<td>" . $value['trasaction_id'] . "</td>";
                $html_view .= "<td>" . $value['vendor_name'] . "</td>";
                //$html_view .= "<td>".$value['allocated_by']."</td>";
                //$html_view .= "<td>".$value['allocated_on']."</td>";
                $html_view .= "<td>" . $value['approval_by'] . "</td>";
                $html_view .= "<td>" . convert_db_to_display_date($value['approval_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12) . "</td>";
                $html_view .= "<td>" . $value['costing'] . "</td>";
                $html_view .= "<td>" . $value['tat_status'] . "</td>";
                $html_view .= "<td>" . $value['remarks'] . "</td>";
                $html_view .= "<td>" . $value['final_status'] . "</td>";

                if ($value['status'] != "5") {
                    $html_view .= '<td><button data-id=' . $value['id'] . ' data-url ="' . ADMIN_SITE_URL . 'identity/View_vendor_log/' . $value['id'] . '" data-toggle="modal" data-target="#showvendorModel"  class="btn-info  showvendorModel"> View </button>';
                    // if(($value['costing'] == "0" &&  $value['additional_costing'] == "0") ||  ($value['costing'] == NULL &&  $value['additional_costing'] == NULL) )
                    // {
                    if ($value['final_status'] != "closed") {
                        $html_view .= ' <button data-id="showvendorModel_cost" data-url ="' . ADMIN_SITE_URL . 'identity/View_vendor_log_cost/' . $value['id'] . '" data-toggle="modal" data-target="#showvendorModel_cost"  class="btn-info  showvendorModel_cost">Charge</button></tb> ';

                    }
                    $html_view .= ' <button data-id="showvendorModel_cancel" data-url ="' . ADMIN_SITE_URL . 'identity/View_vendor_log_cancel/' . $value['id'] . '" data-toggle="modal" data-target="#showvendorModel_cancel"   class="btn-info  showvendorModel_cancel">Cancel</button></tb> ';
                }
                $html_view .= '</tr>';
                //}

                $counter++;
            }

            $json_array['message'] = $html_view;

            $json_array['status'] = SUCCESS_CODE;

        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['message'] = '<tr><td>Something went wrong, please try again!<td></tr>';
        }
        echo_json($json_array);
    }

    

    public function Save_vendor_details()
    {
        if ($this->input->is_ajax_request()) {

            $this->form_validation->set_rules('transaction_id', 'transaction id', 'required');

            $this->form_validation->set_rules('vendor_remark', 'Vendor Remark', 'required');

            if ($this->form_validation->run() == false) {

                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');

            } else {
                $folder_name = "vendor_file";
                $file_upload_path = SITE_BASE_PATH . IDENTITY . $folder_name;
                if (!folder_exist($file_upload_path)) {
                    mkdir($file_upload_path, 0777);
                } else if (!is_writable($file_upload_path)) {
                    array_push($error_msgs, 'Problem while uploading');
                }

                $frm_details = $this->input->post();

                $transaction_id = $frm_details['transaction_id'];

                $field_array = array('final_status' => $frm_details['status'], 'vendor_remark' => $frm_details['vendor_remark'], 'modified_on' => date(DB_DATE_FORMAT), 'vendor_date' => convert_display_to_db_date($frm_details['vendor_date']));

                $result = $this->identity_model->save_vendor_details("view_vendor_master_log", $field_array, array('trasaction_id' => $transaction_id));

                if ($frm_details['status'] == "wip") {
                    $result = $this->identity_model->save_first_qc_result(array("verfstatus" => 1, "var_filter_status" => "WIP", "var_report_status" => "WIP"), array('identity_id' => $frm_details['identity_id']));
                }

                $config_array = array('file_upload_path' => $file_upload_path, 'file_permission' => 'jpeg|jpg|png|tiff', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 2000, 'view_venor_master_log_id' => $frm_details['view_vendor_master_log_id'], 'component_tbl_id' => 9, 'status' => 1);

                if ($_FILES['attchments_file']['name'][0] != '') {
                    $config_array['files_count'] = count($_FILES['attchments_file']['name']);
                    $config_array['file_data'] = $_FILES['attchments_file'];

                    $retunr_de = $this->file_upload_multiple_vendor_log($config_array);
                    if (!empty($retunr_de)) {
                        $this->common_model->common_insert_batch('view_vendor_master_log_file', $retunr_de['success']);
                    }
                }

                if ($result) {

                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'Vendor Details Successfully Inserted';

                } else {

                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Something went wrong,please try again';

                }

                echo_json($json_array);
            }
        }
    }

    public function Save_vendor_details_cancel()
    {

        if ($this->input->is_ajax_request()) {

            $frm_details = $this->input->post();

            $this->form_validation->set_rules('update_id', 'ID', 'required');

            $this->form_validation->set_rules('venodr_reject_reason', 'venodr reject reason', 'required');

            if ($this->form_validation->run() == false) {

                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');

            } else {

                $frm_details = $this->input->post();

                $field_array = array(
                    'remarks' => $frm_details['venodr_reject_reason'],
                    'rejected_by' => $this->user_info['id'],
                    'rejected_on' => date(DB_DATE_FORMAT),
                    'status' => 5,
                    'final_status' => "cancelled",
                );

                $result = $this->identity_model->save_vendor_details_cancel($field_array, array('id' => $frm_details['update_id']));

                $field_array_address = array(
                    'remarks' => $frm_details['venodr_reject_reason'],
                    'modified_by' => $this->user_info['id'],
                    'modified_on' => date(DB_DATE_FORMAT),
                    'status' => 2,
                );

                $address_vendor_result = $this->identity_model->update_address_vendor_log('identity_vendor_log', $field_array_address, array('id' => $frm_details['case_id']));

                $result_get_count_plus = $this->common_model->get_count(array('controllers' => 'assign_identity'));

                $total_get_count_plus = $result_get_count_plus + 1;

                $result_update_count_plus = $this->common_model->update_count(array('count' => $total_get_count_plus), array('controllers' => 'assign_identity'));

                /*   $field_array1 = array(
                'comp_table_id' =>  $frm_details['update_id'],
                'remarks' => $frm_details['venodr_reject_reason'],
                'created_by'   => $this->user_info['id'],
                'created_on'   => date(DB_DATE_FORMAT),
                'is_auto_filled'   => 0,
                );

                $activity_result = $this->addressver_model->address_vendor_details_cancel("address_activity_data",$field_array1);*/

                if ($result && $address_vendor_result) {

                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'Vendor Rejected Successfully';

                } else {

                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Something went wrong,please try again';

                }

            }
            echo_json($json_array);
        }
    }

    public function identity_closure_entries($userid)
    {

        if ($this->input->is_ajax_request() && $userid) {

            log_message('error', 'Identity Closure Entries');
            try {

                $details['user_list_closed'] = $this->users_list_filter();

                $details['userid'] = $userid;

                $details['vendor_executive_list'] = $this->identity_model->identity_case_list(array('view_vendor_master_log.status =' => 1, 'view_vendor_master_log.final_status =' => 'closed', 'component' => 'identity'), $userid);

            } catch (Exception $e) {
                log_message('error', 'Identity::identity_closure_entries');
                log_message('error', $e->getMessage());
            }

        } else {
            $details['vendor_executive_list'] = "Something went wrong,please try again";
        }

        echo $this->load->view('admin/identity_vendor_closure_entries', $details, true);

    }

    public function identity_closure_entries_vendor_insuff($userid)
    {
        if ($this->input->is_ajax_request() && $userid) {

            log_message('error', 'Identity vendor insuff');
            try {

                $details['user_list_insuff'] = $this->users_list_filter();

                $details['userid'] = $userid;

                $details['vendor_executive_list'] = $this->identity_model->identity_case_list_insuff(array('view_vendor_master_log.status =' => 1, 'view_vendor_master_log.final_status =' => 'insufficiency', 'component' => 'identity'), $userid);

            } catch (Exception $e) {
                log_message('error', 'Identity::identity_closure_entries_vendor_insuff');
                log_message('error', $e->getMessage());
            }

        } else {
            $details['vendor_executive_list'] = "Something went wrong,please try again";
        }

        echo $this->load->view('admin/identity_vendor_insuff_entries', $details, true);
    }

  
    public function check_insuff_already_raised($where_id)
    {

        $identity_details = $this->identity_model->get_all_identity_record(array('identity.id' => $where_id));

        $check_insuff_raise = $this->identity_model->select_insuff(array('identity_id' => $where_id, 'status' => STATUS_ACTIVE, 'insuff_clear_date is null' => null));

        $data['insuff_reason_list'] = $this->insuff_reason_list(9);

        $data['insuff_raise_message'] = (!empty($check_insuff_raise) ? 'Insufficiency raised on ' . convert_db_to_display_date($check_insuff_raise[0]['insuff_raised_date']) . ' for ' . $check_insuff_raise[0]['insff_reason'] : '');

        $data['check_insuff_raise'] = ((!empty($check_insuff_raise)) ? 'disabled' : '');
        $data['check_insuff_value'] = ((!empty($check_insuff_raise)) ? '1' : '2');
        $data['insuff_raise_id'] = (!empty($check_insuff_raise) ? $check_insuff_raise[0]['id'] : '');

        if (empty($check_insuff_raise)) {
            $data['get_cands_details'] = $this->candidate_entity_pack_details($identity_details[0]['cands_id']);
            $data['identity_details'] = $identity_details[0];

            echo $this->load->view('admin/identity_insuff_view', $data, true);

        } else {
            echo "<h4>Insuff Already Created</h4>";
        }
    }

    public function identity_closure()
    {

        if ($this->input->is_ajax_request()) {

            if ($this->permission['access_address_aq_allow'] == 1) {
                $frm_details = $this->input->post();
                $action = $frm_details['action'];

                if ($frm_details['action'] == 2 && $frm_details['closure_id'] != "") {
                    $list = explode(',', $frm_details['closure_id']);

                    $update_counter = 0;
                    $files = array();

                    foreach ($list as $key => $value) {

                        $update_closure = $this->identity_model->update_closure_approval('view_vendor_master_log_closure_status', array('view_vendor_master_log_id' => $value, 'approve_reject_status' => 2, 'approve_reject_by' => $this->user_info['id'], 'reject_reasons' => $frm_details['reject_reason'], "approve_rejected_on" => date(DB_DATE_FORMAT)));

                        $update_closure1 = $this->identity_model->update_closure_approval_details('view_vendor_master_log', array('final_status' => 'wip'), array('id' => $value));

                    }
                    if (!empty($update_closure) && !empty($update_closure1)) {

                        $json_array['status'] = SUCCESS_CODE;
                        $json_array['message'] = "Successfully Rejected Closure";
                    } else {
                        $json_array['status'] = ERROR_CODE;
                        $json_array['message'] = "Something went wrong,please try again";
                    }

                } else if ($frm_details['action'] == 1 && $frm_details['closure_id'] != "") {

                    $list = explode(',', $frm_details['closure_id']);

                    $update_counter = 0;
                    $files = array();

                    foreach ($list as $key => $value) {

                        $update_closure = $this->identity_model->update_closure_approval('view_vendor_master_log_closure_status', array('view_vendor_master_log_id' => $value, 'approve_reject_status' => 1, 'approve_reject_by' => $this->user_info['id'], "approve_rejected_on" => date(DB_DATE_FORMAT)));

                        $update_closure1 = $this->identity_model->update_closure_approval_details('view_vendor_master_log', array('final_status' => 'approve'), array('id' => $value));

                      //  $get_client_id = $this->identity_model->get_client_id(array('view_vendor_master_log.final_status' => 'approve', 'view_vendor_master_log.id' => $value));

                       // $insert_task_manager = $this->identity_model->update_closure_approval('client_new_cases', array('client_id' => $get_client_id[0]['client_id'], ' total_cases' => 1, 'status' => 'wip', 'created_by' => $this->user_info['id'], 'created_on' => date(DB_DATE_FORMAT), 'type' => 'closures', 'remarks' => 'closures', 'case_type' => 1, 'view_vendor_master_log_id' => $value));

                    }
                    if (!empty($update_closure) && !empty($update_closure1)) {

                        $json_array['status'] = SUCCESS_CODE;
                        $json_array['message'] = "Successfully Approve Closure";
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
        } else {

            $json_array['status'] = ERROR_CODE;
            $json_array['message'] = "Access Denied, You don’t have permission to access this page";
        }

        echo_json($json_array);
    }

    public function template_download()
    {
        if ($this->input->is_ajax_request()) {

            // $assigned_user_id = $this->users_list();
          //  $assigned_user_id = $this->identity_model->get_assign_users_id('user_profile', false, array("`user_profile`.`status`,`user_profile`.`id`,`user_profile`.`email`,`user_profile`.`department`,`user_profile`.`user_name`,concat(`user_profile`.`firstname`,' ',`user_profile`.`lastname`) as fullname,`user_profile`.`profile_pic`,`user_profile`.`firstname`,`user_profile`.`lastname`,`roles`.`role_name`,`roles`.`groups_id`,`roles_permissions`.*"), array('user_profile.status' => STATUS_ACTIVE));

            $states = array('Select State', 'Andaman And Nicobar Islands', 'Andhra Pradesh', 'Arunachal Pradesh', 'Assam', 'Bihar', 'Chattisgarh', 'Chandigarh', 'Daman And Diu', 'Delhi', 'Dadra And Nagar Haveli', 'Goa', 'Gujarat', 'Himachal Pradesh', 'Haryana', 'Jammu And Kashmir', 'Jharkhand', 'Kerala', 'Karnataka', 'Lakshadweep', 'Meghalaya');

            $document_submitted = array('Select Document Submitted', 'Aadhar Card', 'Pan Card');

            require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
            // Create new Spreadsheet object
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            // Set document properties
            $spreadsheet->getProperties()->setCreator(CRMNAME)
                ->setLastModifiedBy(CRMNAME)
                ->setTitle(CRMNAME)
                ->setSubject('Identity Import Template')
                ->setDescription('Identity Import Template File for bulk upload');

            $styleArray = array(
                'fill' => array(
                    'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startcolor' => array(
                        'rgb' => 'FF0000',
                    ),
                ),
            );
            $spreadsheet->getActiveSheet()->getStyle('A1:H1')->applyFromArray($styleArray);

            foreach (range('A', 'H') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                    ->setWidth(20);
            }

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A1", REFNO)
                ->setCellValue("B1", 'Comp Int Date')
                ->setCellValue("C1", 'Doc Submitted')
                ->setCellValue("D1", 'Id Number')
                ->setCellValue("E1", 'Street Address')
                ->setCellValue("F1", 'City')
                ->setCellValue("G1", 'PIN Code')
                ->setCellValue("H1", 'State');

            for ($i = 1; $i <= 1000; $i++) {

                $objValidation = $spreadsheet->getActiveSheet()->getCell('A' . $i)->getDataValidation();
                $objValidation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                $objValidation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
                $objValidation->setAllowBlank(false);
                $objValidation->setShowInputMessage(true);
                $objValidation->setShowErrorMessage(true);
                $objValidation->setErrorTitle('Input error');
                $objValidation->setError('This Field is Compulsory.');
                $objValidation->setPromptTitle(REFNO);
                $objValidation->setPrompt('Please Insert ' . REFNO);

                $objValidation = $spreadsheet->getActiveSheet()->getCell('B' . $i)->getDataValidation();
                $objValidation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                $objValidation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
                $objValidation->setAllowBlank(false);
                $objValidation->setShowInputMessage(true);
                $objValidation->setShowErrorMessage(true);
                $objValidation->setErrorTitle('Input error');
                $objValidation->setError('This Field is Compulsory.');
                $objValidation->setPromptTitle('Insert Date Only');
                $objValidation->setPrompt('Please insert date only format(DD-MM-YYYY).');

                $objValidation = $spreadsheet->getActiveSheet()->getCell('C' . $i)->getDataValidation();
                $objValidation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                $objValidation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
                $objValidation->setAllowBlank(false);
                $objValidation->setShowInputMessage(true);
                $objValidation->setShowErrorMessage(true);
                $objValidation->setShowDropDown(true);
                $objValidation->setErrorTitle('Input error');
                $objValidation->setError('Value is not in list.');
                $objValidation->setPromptTitle('Pick from list');
                $objValidation->setPrompt('Please pick a value from the drop-down list.');
                $objValidation->setFormula1('"' . implode(',', $document_submitted) . '"');

                $objValidation = $spreadsheet->getActiveSheet()->getCell('D' . $i)->getDataValidation();
                $objValidation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                $objValidation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
                $objValidation->setAllowBlank(false);
                $objValidation->setShowInputMessage(true);
                $objValidation->setShowErrorMessage(true);
                $objValidation->setErrorTitle('Input error');
                $objValidation->setError('This Field is Compulsory.');
                $objValidation->setPromptTitle('Insert ID Number');
                $objValidation->setPrompt('Please insert ID Number.');

                $objValidation = $spreadsheet->getActiveSheet()->getCell('G' . $i)->getDataValidation();
                $objValidation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                $objValidation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
                $objValidation->setShowInputMessage(true);
                $objValidation->setShowErrorMessage(true);
                $objValidation->setErrorTitle('Input error');
                $objValidation->setPromptTitle('Insert Pin Code');
                $objValidation->setPrompt('Please insert Maximum 6 digit and Mimimum 6.');

                $objValidation = $spreadsheet->getActiveSheet()->getCell('H' . $i)->getDataValidation();
                $objValidation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                $objValidation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
                $objValidation->setAllowBlank(false);
                $objValidation->setShowInputMessage(true);
                $objValidation->setShowErrorMessage(true);
                $objValidation->setShowDropDown(true);
                $objValidation->setErrorTitle('Input error');
                $objValidation->setError('Value is not in list.');
                $objValidation->setPromptTitle('Pick from list');
                $objValidation->setPrompt('Please pick a value from the drop-down list.');
                $objValidation->setFormula1('"' . implode(',', $states) . '"');

               /* $objValidation = $spreadsheet->getActiveSheet()->getCell('I' . $i)->getDataValidation();
                $objValidation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                $objValidation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
                $objValidation->setAllowBlank(false);
                $objValidation->setShowInputMessage(true);
                $objValidation->setShowErrorMessage(true);
                $objValidation->setShowDropDown(true);
                $objValidation->setErrorTitle('Input error');
                $objValidation->setError('Value is not in list.');
                $objValidation->setPromptTitle('Pick from list');
                $objValidation->setPrompt('Please pick a value from the drop-down list.');
                $objValidation->setFormula1('"' . implode(',', $assigned_user_id) . '"');*/

            }

            $spreadsheet->getActiveSheet()->setTitle('Identity Records');
            $spreadsheet->setActiveSheetIndex(0);
            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment;filename=Identity Bulk Uplaod Template.xlsx");
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

            $json_array['file'] = "data:application/vnd.ms-excel;base64," . base64_encode($xlsData);

            $json_array['file_name'] = "Identity Bulk Uplaod Template";

            $json_array['message'] = "File downloaded successfully,please check in download folder";

            $json_array['status'] = SUCCESS_CODE;
        } else {
            $json_array['file_name'] = "Identity Bulk Uplaod Template";

            $json_array['message'] = "File downloaded failed,please check in download folder";

            $json_array['status'] = ERROR_CODE;
        }
        echo_json($json_array);
    }

    public function export_to_excel()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {
            set_time_limit(0);

            $where_arry = array();

            $client_id = ($this->input->post('clientid') != "All") ? $this->input->post('clientid') : false;

            $fil_by_status = ($this->input->post('fil_by_status') != "All") ? $this->input->post('fil_by_status') : false;

            $client_name = $this->input->post('client_name');

            $from_date = ($this->input->post('from_date') != "") ? convert_display_to_db_date($this->input->post('from_date')) : false;

            $to_date = ($this->input->post('to_date') != "") ? convert_display_to_db_date($this->input->post('to_date')) : false;

            $all_records = $this->identity_model->get_all_identity_by_client($client_id, $fil_by_status, $from_date, $to_date);

            require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
            // Create new Spreadsheet object
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

            // Set document properties
            $spreadsheet->getProperties()->setCreator(CRMNAME)
                ->setLastModifiedBy(CRMNAME)
                ->setTitle(CRMNAME)
                ->setSubject('Identity records')
                ->setDescription('Identity records with their status');

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
            $spreadsheet->getActiveSheet()->getStyle('A1:W1')->applyFromArray($styleArray);
            // auto fit column to content
            foreach (range('A', 'W') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                    ->setWidth(20);
            }

            // set the names of header cells
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A1", 'Client Name')
                ->setCellValue("B1", 'Entity')
                ->setCellValue("C1", 'Package')
                ->setCellValue("D1", REFNO)
                ->setCellValue("E1", 'Component Ref No')
                ->setCellValue("F1", 'Client Ref No')
                ->setCellValue("G1", 'Transaction No')
                ->setCellValue("H1", 'Comp Received date')
                ->setCellValue("I1", 'Candidate Name')
                ->setCellValue("J1", 'Fathers Name')
                ->setCellValue("K1", 'Candidate DOB')
                ->setCellValue("L1", 'Document Submitted')
                ->setCellValue("M1", 'ID Number')
                ->setCellValue("N1", 'Status')
                ->setCellValue("O1", 'Sub Status')
                ->setCellValue("P1", 'Executive Name')
                ->setCellValue("Q1", 'Vendor')
                ->setCellValue("R1", 'Vendor Status')
                ->setCellValue("S1", 'Vendor Assigned on')
                ->setCellValue("T1", 'Closure Date')
                ->setCellValue("U1", 'Insuff Raised Date')
                ->setCellValue("V1", 'Insuff Clear Date')
                ->setCellValue("W1", 'Insuff Remark');
            // Add some data
            $x = 2;
            foreach ($all_records as $all_record) {

                $identity_status = ($all_record['verfstatus'] != "") ? $all_record['verfstatus'] : "WIP";
                $identity_filter_status = ($all_record['filter_status'] != "") ? $all_record['filter_status'] : "WIP";
                $closuredate = ($all_record['filter_status'] == "Closed") ? convert_db_to_display_date($all_record['closuredate']) : "NA";
                $insuff_remarks = $all_record['insuff_raise_remark'];

                $id_number = (strlen($all_record['id_number']) == 12) ?  wordwrap($all_record['id_number'] , 4 , '-' , true ) : strtoupper($all_record['id_number']);

                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A$x", ucwords($all_record['clientname']))
                    ->setCellValue("B$x", ucwords($all_record['entity_name']))
                    ->setCellValue("C$x", ucwords($all_record['package_name']))
                    ->setCellValue("D$x", $all_record['cmp_ref_no'])
                    ->setCellValue("E$x", $all_record['identity_com_ref'])
                    ->setCellValue("F$x", $all_record['ClientRefNumber'])
                    ->setCellValue("G$x", $all_record['transaction_id'])
                    ->setCellValue("H$x", convert_db_to_display_date($all_record['iniated_date']))
                    ->setCellValue("I$x", ucwords($all_record['CandidateName']))
                    ->setCellValue("J$x", ucwords($all_record['NameofCandidateFather']))
                    ->setCellValue("K$x", convert_db_to_display_date($all_record['DateofBirth']))
                    ->setCellValue("L$x", ucwords($all_record['doc_submited']))
                    ->setCellValue("M$x", $id_number)
                    ->setCellValue("N$x", $identity_filter_status)
                    ->setCellValue("O$x", $identity_status)
                    ->setCellValue("P$x", $all_record['executive_name'])
                    ->setCellValue("Q$x", $all_record['vendor_name'])
                    ->setCellValue("R$x", ucwords($all_record['vendor_status']))
                    ->setCellValue("S$x", convert_db_to_display_date($all_record['vendor_assgined_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12))
                    ->setCellValue("T$x", $closuredate)
                    ->setCellValue("U$x", $all_record['insuff_raised_date'])
                    ->setCellValue("V$x", $all_record['insuff_clear_date'])
                    ->setCellValue("W$x", $insuff_remarks);
                $x++;
            }
            // Rename worksheet
            $spreadsheet->getActiveSheet()->setTitle('Identity Records');

            $spreadsheet->setActiveSheetIndex(0);

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment;filename=Identity Records of $client_name.xlsx");
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

            $json_array['file'] = "data:application/vnd.ms-excel;base64," . base64_encode($xlsData);

            $json_array['file_name'] = "Identity Records of $client_name";

            $json_array['message'] = "File downloaded successfully,please check in download folder";

            $json_array['status'] = SUCCESS_CODE;

        } else {
            $json_array['message'] = "Something went wrong,please try again";
            $json_array['status'] = ERROR_CODE;
        }

        echo_json($json_array);
    }

    public function remove_uploaded_file($id)
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {
            $result = $this->social_media_model->save_update_identity_files(array('status' => 2), array('id' => $id));

            if ($result) {
                $json_array['status'] = SUCCESS_CODE;

                $json_array['message'] = 'Attachment removed successfully, please refresh the page';
            } else {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = 'Something went worong, please try again';
            }
            echo_json($json_array);
        }
    }

    public function bulk_update_case_received_date()
    {

        if ($this->input->is_ajax_request()) {

            $frm_details = $this->input->post();

          
                $file_upload_path = SITE_BASE_PATH . IDENTITY;

                if (!folder_exist($file_upload_path)) {
                    mkdir($file_upload_path, 0777);
                } else if (!is_writable($file_upload_path)) {
                    $message = 'Problem while uploading, folder permission';
                }

                $uplaod_details = array('file_upload_path' => $file_upload_path, 'file_data' => $_FILES['case_received_date_change_bulk_upload'], 'file_permission' => 'csv|xls|xlsx', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 100);
                $upload_result = $this->file_uplod($uplaod_details);
                $record = array();

                if (!empty($upload_result) && $upload_result['status'] == true) {

                    $raw_filename = $_FILES['case_received_date_change_bulk_upload']['tmp_name'];

                    $headerLine = 0;
                    $file = fopen($raw_filename, "r");

                    $this->load->library('excel_reader', array('file_name' => $file_upload_path . '/' . $upload_result['file_name']));

                    $excel_handler = $this->excel_reader->file_handler;

                    $excel_data = $excel_handler->rows();

                    if (!empty($excel_data)) {

                        unset($excel_data[0]);

                        $excel_data = array_map("unserialize", array_unique(array_map("serialize", $excel_data)));

                        foreach ($excel_data as $value) {

                            if (count($value) < 1) {
                                continue;
                            }

                            $check_record_exits = $this->identity_model->select(true, array('*'), array('identity_com_ref' => $value[0]));

                            if (!empty($check_record_exits) && $value[0] != "") {

                                $result = $this->identity_model->save(array('iniated_date' => date('Y-m-d', strtotime(str_replace('/', '-', $value[1])))),array('identity_com_ref'=>$value[0]));

                                $data['success'] = $value[0] . " This Identity Records Update Successfully";
                                   
                            }
                        }
                    } else {
                        $data['message'] = 'CSV File is empty or data large then 1000 charecters';
                    }

                    $json_array['message'] = json_encode($data);

                    $json_array['status'] = SUCCESS_CODE;

                } else {
                    $json_array['message'] = $upload_result['message'];
                }

        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['message'] = 'Something went wrong, please try again';
        }
        echo_json($json_array);
    }

}
