<?php defined('BASEPATH') or exit('No direct script access allowed');
class Reference_verificatiion extends MY_Controller
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

        $this->perm_array = array('page_id' => 9);
        if ($this->input->is_ajax_request()) {
            $this->perm_array = array('direct_access' => true);

        }

        $this->assign_options = array('0' => 'Select', '1' => 'Assign to Executive');
        $this->assign_options_vendor = array('0' => 'Select Vendor', '1' => 'Assign to Vendor');
        $this->load->model(array('reference_verificatiion_model'));
    }

    public function index()
    {
        $data['header_title'] = "Reference Verificatiion Lists";

        $data['filter_view'] = $this->filter_view();
        $data['vendor_list'] = $this->vendor_list('refver');

        $data['users_list'] = $this->reference_verificatiion_model->get_assign_users('user_profile', false, array("`user_profile`.`status`,`user_profile`.`id`,`user_profile`.`email`,`user_profile`.`department`,`user_profile`.`user_name`,concat(`user_profile`.`firstname`,' ',`user_profile`.`lastname`) as fullname,`user_profile`.`profile_pic`,`user_profile`.`firstname`,`user_profile`.`lastname`,`roles`.`role_name`,`roles`.`groups_id`,`roles_permissions`.*"), array('user_profile.status' => STATUS_ACTIVE));

        $data['clients_id'] = $this->get_clients(array('status' => STATUS_ACTIVE));

        $data['status_value'] = $this->get_status();

        $this->load->view('admin/header', $data);

        $this->load->view('admin/reference_verification_list');

        $this->load->view('admin/footer');
    }

    public function get_clients_for_reference_list_view()
    {
        $params = $_REQUEST;

        $clients = $this->reference_verificatiion_model->select_client_list_view_reference('clients', false, array('clients.id', 'clients.clientname'), $params);

        $clients_arry[0] = 'Select Client';

        foreach ($clients as $key => $value) {
            $clients_arry[$value['id']] = ucwords(strtolower($value['clientname']));
        }

        echo_json(array('client_list' => $clients_arry));

    }

    public function reference_view_datatable()
    {
        if ($this->permission['access_reference_list_view'] == true) {
            $params = $reference_candidates = $data_arry = $columns = array();

            $params = $_REQUEST;

            $columns = array('id', 'ClientRefNumber', 'cmp_ref_no', 'CandidateName', 'caserecddate', 'last_modified', 'encry_id', 'verfstatus', 'verification_address', 'additional_comment', 'verifiername', 'verification_date', 'modeofverification', 'closuredate', 'remarks', 'reiniated_date');

            $reference_candidates = $this->reference_verificatiion_model->get_all_reference_record_datatable(false, $params, $columns);

            $totalRecords = count($this->reference_verificatiion_model->get_all_reference_record_datatable_count(false, $params, $columns));

            $x = 0;

            foreach ($reference_candidates as $reference_candidate) {
                $data_arry[$x]['id'] = $x + 1;
               // $data_arry[$x]['checkbox'] = $reference_candidate['id'];
                $data_arry[$x]['ClientRefNumber'] = $reference_candidate['ClientRefNumber'];
                $data_arry[$x]['cmp_ref_no'] = $reference_candidate['cmp_ref_no'];
                $data_arry[$x]['CandidateName'] = $reference_candidate['CandidateName'];
                $data_arry[$x]['reference_com_ref'] = $reference_candidate['reference_com_ref'];
                $data_arry[$x]['iniated_date'] = convert_db_to_display_date($reference_candidate['iniated_date']);
                $data_arry[$x]['clientname'] = $reference_candidate['clientname'];
                $data_arry[$x]['vendor_name'] = $reference_candidate['vendor_name'];
                $data_arry[$x]['verfstatus'] = $reference_candidate['status_value'];
                $data_arry[$x]['executive_name'] = $reference_candidate['executive_name'];
                $data_arry[$x]['name_of_reference'] = $reference_candidate['name_of_reference'];
                //  $data_arry[$x]['first_qc_approve'] = $reference_candidate['first_qc_approve'];
                $data_arry[$x]['caserecddate'] = convert_db_to_display_date($reference_candidate['caserecddate']);
                $data_arry[$x]['last_activity_date'] = convert_db_to_display_date($reference_candidate['last_activity_date'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);
                $data_arry[$x]['closuredate'] = convert_db_to_display_date($reference_candidate['closuredate']);
                $data_arry[$x]['due_date'] = convert_db_to_display_date($reference_candidate['due_date']);
                $data_arry[$x]['tat_status'] = $reference_candidate['tat_status'];
                $data_arry[$x]['mode_of_veri'] = $reference_candidate['mode_of_veri'];
                $data_arry[$x]['encry_id'] = ADMIN_SITE_URL . "reference_verificatiion/view_details/" . encrypt($reference_candidate['id']);

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

    public function add($candsid)
    {
        if ($this->input->is_ajax_request()) {

            //$data['assigned_user_id'] = $this->users_list();
            //$data['assigned_user_id'] = $this->reference_verificatiion_model->get_assign_users_id('user_profile', false, array("`user_profile`.`status`,`user_profile`.`id`,`user_profile`.`email`,`user_profile`.`department`,`user_profile`.`user_name`,concat(`user_profile`.`firstname`,' ',`user_profile`.`lastname`) as fullname,`user_profile`.`profile_pic`,`user_profile`.`firstname`,`user_profile`.`lastname`,`roles`.`role_name`,`roles`.`groups_id`,`roles_permissions`.*"), array('user_profile.status' => STATUS_ACTIVE));


            $data['get_cands_details'] = $this->candidate_entity_pack_details($candsid);

            $data['mode_of_verification'] = $this->reference_verificatiion_model->get_mode_of_verification(array('entity' => $data['get_cands_details']['entity_id'], 'package' => $data['get_cands_details']['package_id'], 'tbl_clients_id' => $data['get_cands_details']['clientid']));

            $data['assigned_user_id']  = $this->get_reporting_manager_for_execituve($data['get_cands_details']['clientid']);


            $data['states'] = $this->get_states();
            echo $this->load->view('admin/reference_add', $data, true);
        }
    }

    protected function reference_com_ref($insert_id)
    {

        $name = COMPONENT_REF_NO['REFERENCES'];

        $referencenumber = $name . $insert_id;

        $field_array = array('reference_com_ref' => $referencenumber);

        $update_auto_increament_id = $this->reference_verificatiion_model->update_auto_increament_value($field_array, array('id' => $insert_id));

        return $referencenumber;
    }

    public function save_form()
    {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('clientid', 'Client', 'required');

            $this->form_validation->set_rules('has_case_id', 'Assigned To Executive', 'required');

            //$this->form_validation->set_rules('reference_com_ref', 'Component Ref','required');

            $this->form_validation->set_rules('candsid', 'Candidate', 'required');

            $this->form_validation->set_rules('name_of_reference', 'Reference Name', 'required');

            $this->form_validation->set_rules('designation', 'Designation', 'required');

            $this->form_validation->set_rules('contact_no', 'Contact Number', 'required');

            if ($this->form_validation->run() == false) {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');
            } else {
                $frm_details = $this->input->post();

                $file_upload_path = SITE_BASE_PATH . REFERENCES . $frm_details['clientid'];

                if (!folder_exist($file_upload_path)) {
                    mkdir($file_upload_path, 0777);
                } else if (!is_writable($file_upload_path)) {
                    array_push($error_msgs, 'Problem while uploading');
                }

                $tat_day = $this->get_client_entity_package_tat_day(array('tbl_clients_id' => $frm_details['clientid'], 'entity' => $frm_details['entity_id'], 'package' => $frm_details['package_id']));

                $get_holiday1 = $this->get_holiday();

                $get_holiday = array_map('current', $get_holiday1);

                $closed_date = getWorkingDays(convert_display_to_db_date($frm_details['iniated_date']), $get_holiday, $tat_day[0]['tat_refver']);

                $field_array = array('clientid' => $frm_details['clientid'],
                    'candsid' => $frm_details['candsid'],
                    'reference_com_ref' => "",
                    'iniated_date' => convert_display_to_db_date($frm_details['iniated_date']),
                    'name_of_reference' => $frm_details['name_of_reference'],
                    'designation' => $frm_details['designation'],
                    'contact_no' => $frm_details['contact_no'],
                    'contact_no_first' => $frm_details['contact_no_first'],
                    'contact_no_second' => $frm_details['contact_no_second'],
                    'email_id' => $frm_details['email_id'],
                    'created_by' => $this->user_info['id'],
                    'created_on' => date(DB_DATE_FORMAT),
                    'modified_on' => date(DB_DATE_FORMAT),
                    'mode_of_veri' => $frm_details['mode_of_veri'],
                    'modified_by' => $this->user_info['id'],
                    'has_case_id' => $frm_details['has_case_id'],
                    'has_assigned_on' => date(DB_DATE_FORMAT),

                    'is_bulk_uploaded' => 0,
                    "due_date" => $closed_date,
                    "tat_status" => "IN TAT",
                );

                $result = $this->reference_verificatiion_model->save(array_map('strtolower', $field_array));

                $reference_com_ref = $this->reference_com_ref($result);

                $config_array = array('file_upload_path' => $file_upload_path, 'file_permission' => 'jpeg|jpg|png|pdf|docx|xls|xlsx', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 1000, 'file_id' => $result, 'component_name' => 'reference_id');
                if (empty($error_msgs)) {
                    if ($_FILES['attchments']['name'][0] != '') {
                        $config_array['files_count'] = count($_FILES['attchments']['name']);
                        $config_array['file_data'] = $_FILES['attchments'];
                        $config_array['type'] = 0;
                        $retunr_de = $this->file_upload_multiple($config_array);
                        if (!empty($retunr_de)) {
                            $this->common_model->common_insert_batch('reference_files', $retunr_de['success']);
                        }
                    }

                    if ($_FILES['attchments_cs']['name'][0] != '') {
                        $config_array['files_count'] = count($_FILES['attchments_cs']['name']);
                        $config_array['file_data'] = $_FILES['attchments_cs'];
                        $config_array['type'] = 2;
                        $retunr_cd = $this->file_upload_multiple($config_array);
                        if (!empty($retunr_cd)) {
                            $this->common_model->common_insert_batch('reference_files', $retunr_cd['success']);
                        }
                    }
                }

                if ($result) {

                    $user_activity_data = $this->common_model->user_actity_data(array('component' => "Reference",
                        'ref_no' => $reference_com_ref, 'candidate_name' => $frm_details['CandidateName'], 'created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id'], 'activity_type' => 'Manual', 'action' => 'Add'));

                    auto_update_overall_status($frm_details['candsid']);
                    auto_update_tat_status($frm_details['candsid']);

                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'Record Successfully Inserted';

                    $json_array['redirect'] = ADMIN_SITE_URL . 'reference_verificatiion';
                } else {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Something went wrong,please try again';
                }
                echo_json($json_array);
            }
        }
    }

    public function get_assign_reference_list_view()
    {
        log_message('error', 'Reference List View New Case');
        try {
            $data['header_title'] = "Reference New Cases";
            $data['user_list_name'] = $this->users_list_filter();

            echo $this->load->view('admin/reference_new_cases', $data, true);

        } catch (Exception $e) {
            log_message('error', 'Reference_verificatiion::get_assign_reference_list_view');
            log_message('error', $e->getMessage());
        }
    }

    public function reference_view_datatable_new_cases()
    {
        log_message('error', 'New Cases in Referece');
        try {

            $params = $reference_candidates = $data_arry = $columns = array();

            $params = $_REQUEST;

            $columns = array('id', 'ClientRefNumber', 'cmp_ref_no', 'CandidateName', 'caserecddate', 'last_modified', 'encry_id', 'verfstatus', 'verification_address', 'additional_comment', 'verifiername', 'verification_date', 'modeofverification', 'closuredate', 'remarks', 'reiniated_date');

            $reference_candidates = $this->reference_verificatiion_model->get_all_reference_record_datatable_new_case(false, $params, $columns);

            $totalRecords = count($this->reference_verificatiion_model->get_all_reference_record_datatable_count_new_case(false, $params, $columns));

            $x = 0;

            foreach ($reference_candidates as $reference_candidate) {
                $data_arry[$x]['id'] = $x + 1;
                $data_arry[$x]['checkbox'] = $reference_candidate['id'];
                $data_arry[$x]['ClientRefNumber'] = $reference_candidate['ClientRefNumber'];
                $data_arry[$x]['cmp_ref_no'] = $reference_candidate['cmp_ref_no'];
                $data_arry[$x]['CandidateName'] = $reference_candidate['CandidateName'];
                $data_arry[$x]['reference_com_ref'] = $reference_candidate['reference_com_ref'];
                $data_arry[$x]['iniated_date'] = convert_db_to_display_date($reference_candidate['iniated_date']);
                $data_arry[$x]['clientname'] = $reference_candidate['clientname'];
                $data_arry[$x]['vendor_name'] = $reference_candidate['vendor_name'];
                $data_arry[$x]['verfstatus'] = $reference_candidate['status_value'];
                $data_arry[$x]['executive_name'] = $reference_candidate['executive_name'];
                $data_arry[$x]['name_of_reference'] = $reference_candidate['name_of_reference'];
                $data_arry[$x]['caserecddate'] = convert_db_to_display_date($reference_candidate['caserecddate']);
                $data_arry[$x]['last_activity_date'] = convert_db_to_display_date($reference_candidate['last_activity_date'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);
                $data_arry[$x]['closuredate'] = convert_db_to_display_date($reference_candidate['closuredate']);
                $data_arry[$x]['due_date'] = convert_db_to_display_date($reference_candidate['due_date']);
                $data_arry[$x]['tat_status'] = $reference_candidate['tat_status'];
                $data_arry[$x]['mode_of_veri'] = $reference_candidate['mode_of_veri'];
                $data_arry[$x]['encry_id'] = ADMIN_SITE_URL . "reference_verificatiion/view_details/" . encrypt($reference_candidate['id']);
                $x++;
            }

            $json_data = array(
                "draw" => intval($params['draw']),
                "recordsTotal" => intval($totalRecords),
                "recordsFiltered" => intval($totalRecords),
                "data" => $data_arry,
            );

            echo_json($json_data);

        } catch (Exception $e) {
            log_message('error', 'reference_verificatiion::reference_view_datatable_new_cases');
            log_message('error', $e->getMessage());
        }
    }

    public function view_from_inside($add_comp_ids = '')
    {
        if ($this->input->is_ajax_request()) {

            $cget_details = $this->reference_verificatiion_model->get_all_reference_record(array('reference.reference_com_ref' => $add_comp_ids));
            if (!empty($cget_details)) {

                $data['header_title'] = 'Reference Verification';

                if($cget_details[0]['has_case_id'] == $this->user_info['id'])
                {
                    $data['bcc_email_id'] =  $this->user_info['email'].','.FROMEMAIL;
                }
                else
                {
                    $email = $this->reference_verificatiion_model->get_user_email_id(array('user_profile.id' => $cget_details[0]['has_case_id']));

                    $data['bcc_email_id']  =  $this->user_info['email'].','.$email.','.FROMEMAIL;
                }

                $data['get_cands_details'] = $this->candidate_entity_pack_details($cget_details[0]['cands_id']);

                $data['mode_of_verification'] = $this->reference_verificatiion_model->get_mode_of_verification(array('entity' => $data['get_cands_details']['entity_id'], 'package' => $data['get_cands_details']['package_id'], 'tbl_clients_id' => $data['get_cands_details']['clientid']));

                $data['selected_data'] = $cget_details[0];

                $data['states'] = $this->get_states();
            
                //$data['assigned_user_id'] = $this->reference_verificatiion_model->get_assign_users_id('user_profile', false, array("`user_profile`.`status`,`user_profile`.`id`,`user_profile`.`email`,`user_profile`.`department`,`user_profile`.`user_name`,concat(`user_profile`.`firstname`,' ',`user_profile`.`lastname`) as fullname,`user_profile`.`profile_pic`,`user_profile`.`firstname`,`user_profile`.`lastname`,`roles`.`role_name`,`roles`.`groups_id`,`roles_permissions`.*"), array('user_profile.status' => STATUS_ACTIVE));
                $data['assigned_user_id']  = $this->get_reporting_manager_for_execituve($cget_details[0]['clientid']);

                $data['attachments'] = $this->reference_verificatiion_model->select_file(array('id', 'file_name', 'real_filename', 'type'), array('reference_id' => $cget_details[0]['reference_id'], 'status' => 1));

                $data['reinitiated'] = ($cget_details[0]['var_filter_status'] == 'Closed' || $cget_details[0]['var_filter_status'] == 'closed') ? '1' : '2';

                
                $check_insuff_raise = $this->reference_verificatiion_model->select_insuff(array('reference_id' => $cget_details[0]['reference_id'], 'status' => STATUS_ACTIVE, 'insuff_clear_date is null' => null));

                $data['insuff_reason_list'] = $this->reference_verificatiion_model->insuff_reason_list(false, array('component_id' => 4));

                $data['insuff_raise_message'] = (!empty($check_insuff_raise) ? 'Insufficiency raised on ' . convert_db_to_display_date($check_insuff_raise[0]['insuff_raised_date']) . ' for ' . $check_insuff_raise[0]['insff_reason'] : '');

                $data['check_insuff_raise'] = ((!empty($check_insuff_raise)) ? 'disabled' : '');

                $data['insuff_raise_id'] = (!empty($check_insuff_raise) ? $check_insuff_raise[0]['id'] : '');


               
                echo $this->load->view('admin/reference_edit_view_inside', $data, true);
            } else {
                echo "<h3>Something went wrong, please try again</h3>";
            }
        }
    }

    public function view_details($reference_id = '')
    {
        if (!empty($reference_id)) {
            $details = $this->reference_verificatiion_model->get_all_reference_record(array('reference.id' => decrypt($reference_id)));

            if ($reference_id && !empty($details)) {
                $data['header_title'] = 'Reference Verification';

                if($details[0]['has_case_id'] == $this->user_info['id'])
                {
                    $data['bcc_email_id'] =  $this->user_info['email'].','.FROMEMAIL;
                }
                else
                {
                    $email = $this->reference_verificatiion_model->get_user_email_id(array('user_profile.id' => $details[0]['has_case_id']));

                    $data['bcc_email_id']  =  $this->user_info['email'].','.$email.','.FROMEMAIL;
                }

                $data['get_cands_details'] = $this->candidate_entity_pack_details($details[0]['cands_id']);

                $data['states'] = $this->get_states();

                $data['selected_data'] = $details[0];
               
               // $data['assigned_user_id'] = $this->reference_verificatiion_model->get_assign_users_id('user_profile', false, array("`user_profile`.`status`,`user_profile`.`id`,`user_profile`.`email`,`user_profile`.`department`,`user_profile`.`user_name`,concat(`user_profile`.`firstname`,' ',`user_profile`.`lastname`) as fullname,`user_profile`.`profile_pic`,`user_profile`.`firstname`,`user_profile`.`lastname`,`roles`.`role_name`,`roles`.`groups_id`,`roles_permissions`.*"), array('user_profile.status' => STATUS_ACTIVE));

                $data['assigned_user_id']  = $this->get_reporting_manager_for_execituve($details[0]['clientid']);

                $data['mode_of_verification'] = $this->reference_verificatiion_model->get_mode_of_verification(array('entity' => $data['get_cands_details']['entity_id'], 'package' => $data['get_cands_details']['package_id'], 'tbl_clients_id' => $data['get_cands_details']['clientid']));

                $reportingmanager_user = $this->reference_verificatiion_model->get_reporting_manager_id();

                $data['reinitiated'] = ($details[0]['var_filter_status'] == 'Closed' || $details[0]['var_filter_status'] == 'closed') ? '1' : '2';

                
                $check_insuff_raise = $this->reference_verificatiion_model->select_insuff(array('reference_id' => decrypt($reference_id), 'status' => STATUS_ACTIVE, 'insuff_clear_date is null' => null));

                $data['insuff_reason_list'] = $this->reference_verificatiion_model->insuff_reason_list(false, array('component_id' => 4));

                $data['attachments'] = $this->reference_verificatiion_model->select_file(array('id', 'file_name', 'real_filename', 'type'), array('reference_id' => decrypt($reference_id), 'status' => 1));

                $data['insuff_raise_message'] = (!empty($check_insuff_raise) ? 'Insufficiency raised on ' . convert_db_to_display_date($check_insuff_raise[0]['insuff_raised_date']) . ' for ' . $check_insuff_raise[0]['insff_reason'] : '');

                $data['check_insuff_raise'] = ((!empty($check_insuff_raise)) ? 'disabled' : '');

                $data['insuff_raise_id'] = (!empty($check_insuff_raise) ? $check_insuff_raise[0]['id'] : '');

                $this->load->view('admin/header', $data);

                $this->load->view('admin/reference_edit_view');

                $this->load->view('admin/footer');
            } else {
                show_404();
            }
        } else {
            $this->index();
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

            $result = $this->reference_verificatiion_model->save_first_qc_result(array("first_qc_approve" => "First QC Approve", "first_qc_updated_on" => $accepted_on, "first_qu_updated_by" => $this->user_info['id']), array("reference_id" => $comp_id, "id" => $frist_qc_id, "candsid" => $cands_id));

            if ($result) {
                $user_activity_data = $this->common_model->user_actity_data(array('component' => "Reference",
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

            $result = $this->reference_verificatiion_model->save_first_qc_result(array("first_qu_reject_reason" => $rejected_reason, "first_qc_approve" => '', "first_qu_updated_by" => $this->user_info['id'], "first_qc_updated_on" => $rejected_on, "verfstatus" => 13, "var_filter_status" => "WIP", "var_report_status" => "WIP"), array("reference_id" => $comp_id, "id" => $frist_qc_id, "candsid" => $cands_id));

            if ($result) {
                $user_activity_data = $this->common_model->user_actity_data(array('component' => "Reference",
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

    public function update_form()
    {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('reference_id', 'ID', 'required');

            $this->form_validation->set_rules('has_case_id', 'Assigned To Executive', 'required');

            $this->form_validation->set_rules('clientid', 'Client', 'required');

            $this->form_validation->set_rules('reference_com_ref', 'Component Ref', 'required');

            $this->form_validation->set_rules('candsid', 'Candidate', 'required');

            $this->form_validation->set_rules('name_of_reference', 'Reference Name', 'required');

            $this->form_validation->set_rules('designation', 'Designation', 'required');

            $this->form_validation->set_rules('contact_no', 'Contact Number', 'required');

            if ($this->form_validation->run() == false) {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');
            } else {
                $frm_details = $this->input->post();

                $file_upload_path = SITE_BASE_PATH . REFERENCES . $frm_details['clientid'];

                if (!folder_exist($file_upload_path)) {
                    mkdir($file_upload_path, 0777);
                } else if (!is_writable($file_upload_path)) {
                    array_push($error_msgs, 'Problem while uploading');
                }

                $tat_day = $this->get_client_entity_package_tat_day(array('tbl_clients_id' => $frm_details['clientid'], 'entity' => $frm_details['entity_id'], 'package' => $frm_details['package_id']));

                $get_holiday1 = $this->get_holiday();

                $get_holiday = array_map('current', $get_holiday1);

                $closed_date = getWorkingDays(convert_display_to_db_date($frm_details['iniated_date']), $get_holiday, $tat_day[0]['tat_refver']);

                $field_array = array('clientid' => $frm_details['clientid'],
                    'candsid' => $frm_details['candsid'],
                    'reference_com_ref' => $frm_details['reference_com_ref'],
                    'iniated_date' => convert_display_to_db_date($frm_details['iniated_date']),
                    'name_of_reference' => $frm_details['name_of_reference'],
                    'designation' => $frm_details['designation'],
                    'contact_no' => $frm_details['contact_no'],
                    'contact_no_first' => $frm_details['contact_no_first'],
                    'contact_no_first' => $frm_details['contact_no_first'],
                    'email_id' => $frm_details['email_id'],
                    'mode_of_veri' => $frm_details['mode_of_veri'],
                    'modified_on' => date(DB_DATE_FORMAT),
                    'build_date' => $frm_details['build_date'],
                    'modified_by' => $this->user_info['id'],
                    'has_case_id' => $frm_details['has_case_id'],
                    'has_assigned_on' => date(DB_DATE_FORMAT),
                    'is_bulk_uploaded' => 0,
                    "due_date" => $closed_date,
                    "tat_status" => "IN TAT",
                );

                $result = $this->reference_verificatiion_model->save(array_map('strtolower', $field_array), array('id' => $frm_details['reference_id']));

                $select_candidate_billed_date = $this->common_model->select_candidate_billed_date('candidates_info', true, array('build_date'), array('id' => $frm_details['candsid']));

                $component_name = json_decode($select_candidate_billed_date['build_date'], true);

                $result_candidate_billed = $this->common_model->update_candidate_billed_date(array('build_date' => $this->components_key_val(array('0' => $component_name['addrver'], '1' => $component_name['courtver'], '2' => $component_name['globdbver'], '3' => $component_name['narcver'], '4' => $frm_details['build_date'], '5' => $component_name['empver'], '6' => $component_name['eduver'], '7' => $component_name['identity'], '8' => $component_name['cbrver'], '9' => $component_name['crimver']))), array('id' => $frm_details['candsid']));

                $config_array = array('file_upload_path' => $file_upload_path, 'file_permission' => 'jpeg|jpg|png|pdf|docx|xls|xlsx', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 1000, 'file_id' => $frm_details['reference_id'], 'component_name' => 'reference_id');
                if (empty($error_msgs)) {
                    if ($_FILES['attchments']['name'][0] != '') {
                        $config_array['files_count'] = count($_FILES['attchments']['name']);
                        $config_array['file_data'] = $_FILES['attchments'];
                        $config_array['type'] = 0;
                        $retunr_de = $this->file_upload_multiple($config_array);
                        if (!empty($retunr_de)) {
                            $this->common_model->common_insert_batch('reference_files', $retunr_de['success']);
                        }
                    }

                    if ($_FILES['attchments_cs']['name'][0] != '') {
                        $config_array['files_count'] = count($_FILES['attchments_cs']['name']);
                        $config_array['file_data'] = $_FILES['attchments_cs'];
                        $config_array['type'] = 2;
                        $retunr_cd = $this->file_upload_multiple($config_array);
                        if (!empty($retunr_cd)) {
                            $this->common_model->common_insert_batch('reference_files', $retunr_cd['success']);
                        }
                    }
                }

                if ($result) {
                    $user_activity_data = $this->common_model->user_actity_data(array('component' => "Reference",
                        'ref_no' => $frm_details['reference_com_ref'], 'candidate_name' => $frm_details['CandidateName'], 'created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id'], 'activity_type' => 'Manual', 'action' => 'Edit'));

                    auto_update_tat_status($frm_details['candsid']);

                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'Record Successfully Updated';

                    $json_array['redirect'] = ADMIN_SITE_URL . 'reference_verificatiion';
                } else {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Something went wrong,please try again';
                }
                echo_json($json_array);
            }
        }
    }

    public function add_result_model($where_id, $url)
    {
        $details = $this->reference_verificatiion_model->get_all_reference_record(array('reference.id' => $where_id));
        if ($where_id && !empty($details)) {
            $data['check_insuff_raise'] = '';

            $data['details'] = $details[0];

            $data['attachments'] = $this->reference_verificatiion_model->select_file(array('id', 'file_name', 'status'), array('reference_id' => $where_id, 'type' => 1));

            $data['url'] = $url;

            echo $this->load->view('admin/reference_add_result_model_view', $data, true);
        } else {
            echo "<h4>Record Not Found</h4>";
        }
    }

    public function reference_reinitiated_date()
    {

        $json_array = array();

        if ($this->input->is_ajax_request()) {

            $referenceid = $this->input->post('reference_id');
            $clientid = $this->input->post('clientid');
            $reinitiated_date = $this->input->post('reinitiated_date');
            $reinitiated_remark = $this->input->post('reinitiated_remark');

            $check = $this->reference_verificatiion_model->select_reinitiated_date(array('id' => $referenceid));

            if ($check[0]['reference_re_open_date'] == "0000-00-00" || $check[0]['reference_re_open_date'] == "") {
                $reinitiated_dates = $reinitiated_date;
            } else {
                $reinitiated_dates = $check[0]['reference_re_open_date'] . "||" . $reinitiated_date;
            }

            $result = $this->reference_verificatiion_model->save_update_initiated_date(array('reference_re_open_date' => $reinitiated_dates, 'reference_reinitiated_remark' => $reinitiated_remark), array('id' => $referenceid));

            $result_reference = $this->reference_verificatiion_model->save_update_initiated_date_reference(array('verfstatus' => 26, 'var_filter_status' => "WIP", 'var_report_status' => "WIP", 'first_qc_approve' => "", 'first_qc_updated_on' => "", 'first_qu_reject_reason' => "", 'first_qu_updated_by' => ""), array('reference_id' => $referenceid));

            $error_msgs = array();
            $file_upload_path = SITE_BASE_PATH . REFERENCES . $clientid;
            if (!folder_exist($file_upload_path)) {
                mkdir($file_upload_path, 0777);
            } else if (!is_writable($file_upload_path)) {
                array_push($error_msgs, 'Problem while uploading');
            }

            $config_array = array('file_upload_path' => $file_upload_path, 'file_permission' => 'jpeg|jpg|png|pdf|docx|xls|xlsx', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 1000, 'file_id' => $referenceid, 'component_name' => 'reference_id');

            if ($_FILES['attachment_reinitiated']['name'][0] != '') {
                $config_array['files_count'] = count($_FILES['attachment_reinitiated']['name']);
                $config_array['file_data'] = $_FILES['attachment_reinitiated'];
                $config_array['type'] = 2;
                $retunr_cd = $this->file_upload_multiple($config_array);
                if (!empty($retunr_cd)) {
                    $this->common_model->common_insert_batch('reference_files', $retunr_cd['success']);
                }
            }

            $result_reference_activity_data = $this->reference_verificatiion_model->initiated_date_reference_activity_data(array('candsid' => $this->input->post('candidates_info_id'), 'comp_table_id' => $referenceid, 'action' => "Re-Initiated", '  activity_status' => "Re-Initiated", 'remarks' => 'Client requested to re-verify the case [' . $reinitiated_remark . ']', 'created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id']));

            if ($result && $result_reference) {

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

    public function add_verificarion_result()
    {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('reference_id', 'ID', 'required');

            $this->form_validation->set_rules('reference_result_id', 'ID', 'required');

            if ($this->form_validation->run() == false) {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');
            } else {

                try {
                    $frm_details = $this->input->post();

                    if (($frm_details['action_val'] != "Select") || ($frm_details['activity_last_id'] != "")) {

                        $verfstatus = status_id_frm_db(array('status_value' => $frm_details['action_val'], 'components_id' => 6));

                        $field_array = array('verfstatus' => $verfstatus['id'],
                            'var_filter_status' => $verfstatus['filter_status'],
                            'var_report_status' => $verfstatus['report_status'],
                            'clientid' => $frm_details['clientid'],
                            'candsid' => $frm_details['candidates_info_id'],
                            'reference_id' => $frm_details['reference_id'],
                            'handle_pressure_value' => $frm_details['handle_pressure_value'],
                            'attendance_value' => $frm_details['attendance_value'],
                            'integrity_value' => $frm_details['integrity_value'],
                            'leadership_skills_value' => $frm_details['leadership_skills_value'],
                            'responsibilities_value' => $frm_details['responsibilities_value'],
                            'achievements_value' => $frm_details['achievements_value'],
                            'strengths_value' => $frm_details['strengths_value'],
                            'overall_performance' => $frm_details['overall_performance'],
                            'additional_comments' => $frm_details['additional_comments'],
                            'team_player_value' => $frm_details['team_player_value'],
                            'weakness_value' => $frm_details['weakness_value'],
                            'mode_of_verification' => $frm_details['mode_of_verification'],
                            'closuredate' => convert_display_to_db_date($frm_details['closuredate']),
                            'remarks' => $frm_details['remarks'],
                            "modified_on" => date(DB_DATE_FORMAT),
                            "modified_by" => $this->user_info['id'],
                            "first_qc_approve" => '',
                            'first_qc_updated_on' => null,
                            'first_qu_reject_reason' => '',
                            'activity_log_id' => $frm_details['activity_last_id'],
                        );

                        $field_array = array_map('strtolower', $field_array);

                        if (isset($frm_details['remove_file'])) // delete uploaded file
                        {
                            $this->reference_verificatiion_model->delete_uploaded_file($frm_details['remove_file']);
                        }
                        if (isset($frm_details['add_file'])) // delete uploaded file
                        {
                            $this->reference_verificatiion_model->add_uploaded_file($frm_details['add_file']);
                        }

                        $result = $this->reference_verificatiion_model->save_update_ver_result($field_array, array('id' => $frm_details['reference_result_id']));

                        $reference_verification_result = $this->reference_verificatiion_model->save_update_ver_result_refrence($field_array);

                        $file_upload_path = SITE_BASE_PATH . REFERENCES . $frm_details['clientid'];
                        
                        $config_array = array(
                            'file_upload_path'  => $file_upload_path, 
                            'file_permission'   => 'jpeg|jpg|png|pdf', 
                            'file_size'         => BULK_UPLOAD_MAX_SIZE_MB * 2000, 
                            'file_id'           => $frm_details['reference_id'], 
                            'component_name'    => 'reference_id'
                        );
            
                        if (isset($_FILES['attchments_ver']['name'][0]) &&  $_FILES['attchments_ver']['name'][0] != '') {
                            $config_array['files_count']    = count($_FILES['attchments_ver']['name']);
                            $config_array['file_data']      = $_FILES['attchments_ver'];
                            $config_array['type']           = 1;
                            $retunr_de = $this->file_upload_library->file_upload_multiple($config_array, true);
                            
                            if (!empty($retunr_de['success'])) {
                                $this->common_model->common_insert_batch('reference_files', $retunr_de['success']);
                            }
                        }

                        if (isset($frm_details['upload_capture_image_reference_result'])) {

                            if ($frm_details['upload_capture_image_reference_result']) {

                               $upload_capture_image = explode("||", $frm_details['upload_capture_image_reference_result']);
                                    
                                foreach ($upload_capture_image as $key => $value) {
                                    $key = $key + 1;

                                    $file_name = $frm_details['reference_com_ref'] . '-' .$key. '-' . date(UPLOAD_FILE_DATE_FORMAT) . '.png';
                                    $uploadpath = $file_upload_path . '/' . $file_name;
                                    $base64_to_jpeg = base64_to_jpeg($value, $uploadpath);
                                        if ($base64_to_jpeg) {
                                            log_message('error', 'Inside if condition success');
                                            $this->common_model->save('reference_files', ['file_name' => $file_name, 'real_filename' => $file_name,'status' => 1,'type' => 1,'reference_id' => $frm_details['reference_id']]);
                                        }

                                }
                            }
                        }

                        if ($result) {

                            auto_update_overall_status($frm_details['candidates_info_id']);

                         //   all_component_closed_qc_status($frm_details['candidates_info_id']);

                            $json_array['status'] = SUCCESS_CODE;

                            $json_array['message'] = 'Record Successfully Updated';

                            $json_array['redirect'] = ADMIN_SITE_URL . 'reference_verificatiion';
                        } else {
                            $json_array['status'] = ERROR_CODE;

                            $json_array['message'] = 'Something went wrong,please try again';
                        }
                    } else {
                        $json_array['status'] = ERROR_CODE;

                        $json_array['message'] = 'Something went wrong,please try again';
                    }
                } catch (Exception $e) {
                    log_message('error', 'Reference_verification::add_verificarion_result');
                    log_message('error', $e->getMessage());
                }
            }
            echo_json($json_array);
        }
    }

    public function add_verificarion_ver_result()
    {
        if ($this->input->is_ajax_request()) {

            $this->form_validation->set_rules('reference_id', 'ID', 'required');

            $this->form_validation->set_rules('reference_result_id', 'ID', 'required');

            if ($this->form_validation->run() == false) {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');
            } else {
                try{
                    $frm_details = $this->input->post();

                    $field_array = array(
                        'clientid' => $frm_details['clientid'],
                        'candsid' => $frm_details['candidates_info_id'],
                        'reference_id' => $frm_details['reference_id'],
                        'handle_pressure_value' => $frm_details['handle_pressure_value'],
                        'attendance_value' => $frm_details['attendance_value'],
                        'integrity_value' => $frm_details['integrity_value'],
                        'leadership_skills_value' => $frm_details['leadership_skills_value'],
                        'responsibilities_value' => $frm_details['responsibilities_value'],
                        'achievements_value' => $frm_details['achievements_value'],
                        'strengths_value' => $frm_details['strengths_value'],
                        'overall_performance' => $frm_details['overall_performance'],
                        'additional_comments' => $frm_details['additional_comments'],
                        'team_player_value' => $frm_details['team_player_value'],
                        'weakness_value' => $frm_details['weakness_value'],
                        'mode_of_verification' => $frm_details['mode_of_verification'],
                        'closuredate' => convert_display_to_db_date($frm_details['closuredate']),
                        'remarks' => $frm_details['remarks'],
                        "modified_on" => date(DB_DATE_FORMAT),
                        "modified_by" => $this->user_info['id'],
                    );

                    $field_array = array_map('strtolower', $field_array);

                    if (isset($frm_details['remove_file'])) // delete uploaded file
                    {
                        $this->reference_verificatiion_model->delete_uploaded_file($frm_details['remove_file']);
                    }
                    if (isset($frm_details['add_file'])) // delete uploaded file
                    {
                        $this->reference_verificatiion_model->add_uploaded_file($frm_details['add_file']);
                    }

                    $result = $this->reference_verificatiion_model->save_update_ver_result($field_array, array('id' => $frm_details['reference_result_id']));

                    $reference_verification_result = $this->reference_verificatiion_model->save_update_ver_result_refrence($field_array, array('id' => $frm_details['result_update_id']));

                    $file_upload_path = SITE_BASE_PATH . REFERENCES . $frm_details['clientid'];
                
                    $config_array = array(
                        'file_upload_path'  => $file_upload_path, 
                        'file_permission'   => 'jpeg|jpg|png|pdf', 
                        'file_size'         => BULK_UPLOAD_MAX_SIZE_MB * 2000, 
                        'file_id'           => $frm_details['reference_id'], 
                        'component_name'    => 'reference_id'
                    );
        
                    if (isset($_FILES['attchments_ver']['name'][0]) &&  $_FILES['attchments_ver']['name'][0] != '') {
                        $config_array['files_count']    = count($_FILES['attchments_ver']['name']);
                        $config_array['file_data']      = $_FILES['attchments_ver'];
                        $config_array['type']           = 1;
                        $retunr_de = $this->file_upload_library->file_upload_multiple($config_array, true);
                        
                        if (!empty($retunr_de['success'])) {
                            $this->common_model->common_insert_batch('reference_files', $retunr_de['success']);
                        }
                    }

                    if (isset($frm_details['upload_capture_image_reference_ver_result'])) {

                        if ($frm_details['upload_capture_image_reference_ver_result']) {

                           $upload_capture_image = explode("||", $frm_details['upload_capture_image_reference_ver_result']);
                                
                            foreach ($upload_capture_image as $key => $value) {
                                $key = $key + 1;

                                $file_name = $frm_details['reference_com_ref'] . '-' .$key. '-' . date(UPLOAD_FILE_DATE_FORMAT) . '.png';
                                $uploadpath = $file_upload_path . '/' . $file_name;
                                $base64_to_jpeg = base64_to_jpeg($value, $uploadpath);
                                    if ($base64_to_jpeg) {
                                        log_message('error', 'Inside if condition success');
                                        $this->common_model->save('reference_files', ['file_name' => $file_name, 'real_filename' => $file_name,'status' => 1,'type' => 1,'reference_id' => $frm_details['reference_id']]);
                                    }

                            }
                        }
                    }


                    if ($result) {

                        auto_update_overall_status($frm_details['candidates_info_id']);

                       // all_component_closed_qc_status($frm_details['candidates_info_id']);

                        $json_array['status'] = SUCCESS_CODE;

                        $json_array['message'] = 'Record Successfully Updated';

                        $json_array['redirect'] = ADMIN_SITE_URL . 'reference_verificatiion';
                    } else {
                        $json_array['status'] = ERROR_CODE;

                        $json_array['message'] = 'Something went wrong,please try again';
                    }
                } catch (Exception $e) {
                    log_message('error', 'Reference_verification::add_verificarion_ver_result');
                    log_message('error', $e->getMessage());
                }
            }
            echo_json($json_array);
        }
    }

    public function insuff_raised()
    {
        $this->load->library('email');
        $json_array = array();
        $json_array['status'] = ERROR_CODE;
        $json_array['message'] = 'Something went wrong,please try again';

        if ($this->input->is_ajax_request()) {
            $reference_id = $this->input->post('update_id');

            $insff_reason = $this->input->post('insff_reason');

            $insff_date = $this->input->post('txt_insuff_raise');

            $ref_no = $this->input->post('component_ref_no');

            $CandidateName = $this->input->post('CandidateName');

            $check = $this->reference_verificatiion_model->select_insuff(array('reference_id' => $reference_id, 'status !=' => 3, 'insuff_clear_date is null' => null));

            if (empty($check)) {
                $result = $this->reference_verificatiion_model->save_update_insuff(array('insuff_raised_date' => convert_display_to_db_date($insff_date), 'insuff_raise_remark' => $this->input->post('insuff_raise_remark'), 'status' => STATUS_ACTIVE, 'insff_reason' => $insff_reason, 'created_by' => $this->user_info['id'], 'reference_id' => $reference_id, 'auto_stamp' => 1));

                if ($result) {

                    $case_activity = $this->common_model->get_case_activity_status(array('entity' => $this->input->post('entity_id'), 'package' => $this->input->post('package_id'), 'tbl_clients_id' => $this->input->post('clientid')));

                    if(!empty($case_activity)){

                        if($case_activity[0]['case_activity'] == "1"){

                            $reference_details = $this->reference_verificatiion_model->get_reference_details_for_insuff_mail(array('reference.id' => $reference_id));

                                $users_id  = $this->get_reporting_manager_for_executive($this->input->post('clientid'));

                                $email = $this->reference_verificatiion_model->get_user_email_id(array('user_profile.id' => $users_id[0]['id']));

                                $spoc_email_id = $this->common_model->select_spoc_mail_id($this->input->post('clientid'));

                                $subject = 'Insufficiency raised in Reference for '.ucwords($this->input->post('CandidateName'));
                                $message = "<p>Team,</p><p>Insufficiency has been raised for the following reason.</p>";


                                $message .= "<table border = '2'  style='border-spacing: 10; border-collapse: collapse;'>
                                <tr>
                                    <td style='text-align:center;background-color: #EDEDED;'>Component</td>
                                    <td style='text-align:center'>Education</td>
                                </tr>
                               <tr>
                                    <td style='text-align:center;background-color: #EDEDED;'>Candidate Name</td>
                                    <td style='text-align:center'>".ucwords($this->input->post('CandidateName'))."</td>
                                </tr>
                                <tr>
                                    <td style='text-align:center;background-color: #EDEDED;'>Entity</td>
                                    <td style='text-align:center'>".ucwords( $reference_details[0]['entity_name'] )."</td>
                                </tr>
                                <tr>
                                    <td style='text-align:center;background-color: #EDEDED;'>Spoc/Package</td>
                                    <td style='text-align:center'>".ucwords( $reference_details[0]['package_name'])."</td>
                                </tr>
                                <tr>
                                    <td style='text-align:center;background-color: #EDEDED;'>Client Ref No</td>
                                    <td style='text-align:center'>".$reference_details[0]['ClientRefNumber']."</td>
                                </tr>
                                <tr>
                                    <td style='text-align:center;background-color: #EDEDED;'>Mist Ref No</td>
                                    <td style='text-align:center'>".$reference_details[0]['cmp_ref_no']."</td>
                                </tr>
                                <tr>
                                    <td style='text-align:center;background-color: #EDEDED;'>Raised Date</td>
                                    <td style='text-align:center'>".$insff_date."</td>
                                </tr>
                                <tr>
                                    <td style='text-align:center;background-color: #EDEDED;'>Details</td>
                                    <td style='text-align:center'>".ucwords($reference_details[0]['name_of_reference'])."</td>
                                </tr>
                                <tr>
                                    <td style='text-align:center;background-color: #EDEDED;'>Remarks</td>
                                    <td style='text-align:center'>".$this->input->post('insuff_raise_remark')."</td>
                                </tr>
                                </table>";

                                $message .= "<p><b>Note : </b>This is an auto generated email. Request you to write back within 24 hrs to report any discrepancy.</p>";

                                $email_tmpl_data['to_emails'] = $spoc_email_id[0]['spoc_email'];
                                $email_tmpl_data['cc_emails'] = $email.",".$spoc_email_id[0]['spoc_manager_email'];
                                $email_tmpl_data['from_emails'] = $email;
                
                                $email_tmpl_data['message'] = $message;
                                $email_tmpl_data['subject'] = $subject;
               
                                $result_mail = $this->email->component_send_insuff_raised($email_tmpl_data);
                            }
                        }

                    $user_activity_data = $this->common_model->user_actity_data(array('component' => "Reference", 'ref_no' => $ref_no, 'candidate_name' => $CandidateName, 'created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id'], 'activity_type' => 'Manual', 'action' => 'Insuff Raised'));
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
            $data['insuff_details'] = $this->reference_verificatiion_model->select_insuff_join(array('reference_id' => $emp_id));

            echo $this->load->view('admin/reference_insuff_view', $data, true);
        } else {
            echo "<p>Something went wrong, please try again.</p>";
        }
    }

    public function insuff_raised_data()
    {
        if ($this->input->is_ajax_request()) {
            $insuff_data = $this->input->post('insuff_data');

            $result = $this->reference_verificatiion_model->select_insuff(array('id' => $insuff_data));
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

            $result = $this->reference_verificatiion_model->select_insuff(array('id' => $insuff_data));

            if (!empty($result)) {
                $data['insuff_reason_list'] = $this->insuff_reason_list(4);
                $data['insuff_details'] = $result[0];
                echo $this->load->view('admin/insuff_edit_and_clear_view', $data, true);
            }
        }
    }

    public function initiation_mail($id)
    {
        if ($this->input->is_ajax_request()) {
            $details = $this->reference_verificatiion_model->reference_ver_details_for_email(array('reference.id' => $id));

            $reportingmanager_user = $this->reference_verificatiion_model->get_reporting_manager_id();

            $reportingmanager_id = $reportingmanager_user[0]['reporting_manager'];
            $reportingmanager = $this->reference_verificatiion_model->get_reporting_manager_email_id($reportingmanager_id);
            $view_data['reporting_manager_email'] = $reportingmanager[0];
            $view_data['user_profile_info'] = $reportingmanager_user[0];

            $view_data['email_info'] = $details[0];

            echo $this->load->view(EMAIL_VIEW_FOLDER_NAME . 'reference_initial_template', $view_data, true);
        }
    }

    public function ref_initial_send_mail()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {
            $reference_id = $this->input->post('ref_user_id');

            $to_emails = $this->input->post('to_email');

            $from_email = $this->input->post('from');

            $cc_email = $this->input->post('cc_email');

            $bcc_email = $this->input->post('bcc_email');

            $subject = $this->input->post('subject');

        
            $attchment =  $this->input->post('attachment');

            $this->load->library('email');

            $details = $this->reference_verificatiion_model->reference_ver_details_for_email(array('reference.id' => $reference_id));
            $reportingmanager_user = $this->reference_verificatiion_model->get_reporting_manager_id();
            $email_tmpl_data['user_profile_info'] = $reportingmanager_user[0];

            $email_tmpl_data['to_emails'] = $to_emails;
            $email_tmpl_data['cc_emails'] = $cc_email;
            $email_tmpl_data['bcc_emails'] = $bcc_email;
            $email_tmpl_data['subject'] = $subject;
            $email_tmpl_data['attchment'] = $attchment;
            $email_tmpl_data['from_email'] = ($from_email != "") ? $from_email : FROMEMAIL;

            $email_tmpl_data['detail_info'] = $details[0];

            $result = $this->email->admin_send_reference_initial_mail($email_tmpl_data);

            if ($result) {

                if ($details[0]['verfstatus'] == "14") {
                    $details = $this->reference_verificatiion_model->reference_verfstatus_update(array('reference_result.verfstatus' => "1"), array('reference_result.reference_id' => $reference_id));
                }

                $field = array('candsid' => $details[0]['candsid'],
                    'ClientRefNumber' => $details[0]['ClientRefNumber'],
                    'comp_table_id' => $reference_id,
                    'activity_status' => "WIP",
                    'activity_type' => "Email",
                    'action' => "Initiation Mail",
                    'remarks' => "Initiation Mail Send to " . $to_emails,
                    'created_on' => date(DB_DATE_FORMAT),
                    'created_by' => $this->user_info['id'],
                    'is_auto_filled' => 1,

                );

                $result = $this->reference_verificatiion_model->save_mail_activity_data($field);

                $this->reference_verificatiion_model->referece_mail_details(array('created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id'], 'to_mail_id' => $to_emails, 'cc_mail_id' => $cc_email, '   reference_id' => $reference_id, 'type' => "Initiation Mail"));

                $user_activity_data = $this->common_model->user_actity_data(array('component' => "Reference",
                    'ref_no' => $details[0]['cmp_ref_no'], 'candidate_name' => $details[0]['CandidateName'], 'created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id'], 'activity_type' => 'Manual', 'action' => 'Initiation Email'));

                $json_array['message'] = 'Email Send Successfully';

                $json_array['status'] = SUCCESS_CODE;
            } else {
                $json_array['message'] = 'Email not sent, please try again';

                $json_array['status'] = ERROR_CODE;
            }
        } else {
            $json_array['message'] = 'Something went wrong, please try again';

            $json_array['status'] = ERROR_CODE;
        }

        echo_json($json_array);
    }

    public function summary_mail($id)
    {
        if ($this->input->is_ajax_request()) {

            $details = $this->reference_verificatiion_model->reference_ver_details_for_email(array('reference.id' => $id));
            $reportingmanager_user = $this->reference_verificatiion_model->get_reporting_manager_id();
            $reportingmanager_id = $reportingmanager_user[0]['reporting_manager'];
            $reportingmanager = $this->reference_verificatiion_model->get_reporting_manager_email_id($reportingmanager_id);
            $view_data['reporting_manager_email'] = $reportingmanager[0];
            $view_data['user_profile_info'] = $reportingmanager_user[0];

            $check_all_madetory = array();
            $view_data['column_empty'] = false;

            foreach ($details as $key => $value) {
                $check_all_madetory['name_of_reference'] = $value['name_of_reference'];
                $check_all_madetory['handle_pressure_value'] = $value['handle_pressure_value'];
                $check_all_madetory['attendance_value'] = $value['attendance_value'];
                $check_all_madetory['integrity_value'] = $value['integrity_value'];
                $check_all_madetory['leadership_skills_value'] = $value['leadership_skills_value'];
                $check_all_madetory['responsibilities_value'] = $value['responsibilities_value'];
                $check_all_madetory['achievements_value'] = $value['achievements_value'];
                $check_all_madetory['strengths_value'] = $value['strengths_value'];
                $check_all_madetory['team_player_value'] = $value['team_player_value'];
                $check_all_madetory['weakness_value'] = $value['weakness_value'];
                $check_all_madetory['overall_performance'] = $value['overall_performance'];

            }
            if (count(array_filter($check_all_madetory)) != 11) {
                $view_data['column_empty'] = true;
            }

            $view_data['email_info'] = $details[0];
            echo $this->load->view(EMAIL_VIEW_FOLDER_NAME . 'summary_mail_ref_template', $view_data, true);
        }
    }

    public function reference_send_summary_mail()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {
            $reference_id = $this->input->post('ref_user_id');

            $to_emails = $this->input->post('to_email');

            $from_email = $this->input->post('from');

            $cc_email = $this->input->post('cc_email');

            $bcc_email = $this->input->post('bcc_email');

            $subject = $this->input->post('subject');

            $attchment = $this->input->post('attachment');

            $this->load->library('email');

            $details = $this->reference_verificatiion_model->reference_ver_details_for_email(array('reference.id' => $reference_id));
            $reportingmanager_user = $this->reference_verificatiion_model->get_reporting_manager_id();
            $email_tmpl_data['user_profile_info'] = $reportingmanager_user[0];

            $email_tmpl_data['to_emails'] = $to_emails;
            $email_tmpl_data['cc_emails'] = $cc_email;
            $email_tmpl_data['bcc_emails'] = $bcc_email;
            $email_tmpl_data['subject'] = $subject;
            $email_tmpl_data['attchment'] = $attchment;

            $email_tmpl_data['from_email'] = ($from_email != "") ? $from_email : FROMEMAIL;

            $email_tmpl_data['detail_info'] = $details[0];

            $result = $this->email->admin_send_reference_summary_mail($email_tmpl_data);

            if ($result) {

                $field = array('candsid' => $details[0]['candsid'],
                    'ClientRefNumber' => $details[0]['ClientRefNumber'],
                    'comp_table_id' => $reference_id,
                    'activity_status' => "",
                    'activity_type' => "Email",
                    'action' => "Summary Mail",
                    'remarks' => "Summary Mail Send to " . $to_emails,
                    'created_on' => date(DB_DATE_FORMAT),
                    'created_by' => $this->user_info['id'],
                    'is_auto_filled' => 1,

                );

                $result = $this->reference_verificatiion_model->save_mail_activity_data($field);

                $this->reference_verificatiion_model->referece_mail_details(array('created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id'], 'to_mail_id' => $to_emails, 'cc_mail_id' => $cc_email, '   reference_id' => $reference_id, 'type' => "Summary Mail"));

                $user_activity_data = $this->common_model->user_actity_data(array('component' => "Reference",
                    'ref_no' => $details[0]['cmp_ref_no'], 'candidate_name' => $details[0]['CandidateName'], 'created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id'], 'activity_type' => 'Manual', 'action' => 'Summary Email'));

                $json_array['message'] = 'Email Send Successfully';

                $json_array['status'] = SUCCESS_CODE;
            } else {
                $json_array['message'] = 'Email not sent, please try again';

                $json_array['status'] = ERROR_CODE;
            }
        } else {
            $json_array['message'] = 'Something went wrong, please try again';

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

            $result = $this->reference_verificatiion_model->save_update_insuff($fields, array('id' => $frm_details['id']));

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

    public function insuff_clear()
    {
        $json_array = array();

        $frm_details = $this->input->post();

        if ($this->input->is_ajax_request() && $this->permission['access_reference_list_insuff_clear'] == 1) {
            if (convert_display_to_db_date($frm_details['insuff_clear_date']) >= convert_display_to_db_date($frm_details['check_insuff_raise'])) {
                $insuff_date = $frm_details['insuff_clear_date'];

                $hold_days = getNetWorkDays($frm_details['check_insuff_raise'], $frm_details['insuff_clear_date']);

                $date_tat = $this->reference_verificatiion_model->get_date_for_update(array('id' => $frm_details['clear_update_id']));

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

                $result_due_date = $this->reference_verificatiion_model->update_due_date(array('due_date' => $closed_date, 'tat_status' => $new_tat), array('id' => $frm_details['clear_update_id']));

                $fields = array('insuff_clear_date' => convert_display_to_db_date($insuff_date), 'insuff_remarks' => $frm_details['insuff_remarks'], 'insuff_cleared_timestamp' => date(DB_DATE_FORMAT), 'status' => 2, 'insuff_cleared_by' => $this->user_info['id'], 'auto_stamp' => 2, 'hold_days' => $hold_days);

                $result = $this->reference_verificatiion_model->save_update_insuff($fields, array('id' => $frm_details['insuff_clear_id']));

                if ($result) {
                    $user_activity_data = $this->common_model->user_actity_data(array('component' => "Reference", 'ref_no' => $frm_details['component_ref_no'], 'candidate_name' => $frm_details['CandidateName'], 'created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id'], 'activity_type' => 'Manual', 'action' => 'Insuff Cleared'));
                }

                $error_msgs = $file_array = array();

                $file_upload_path = SITE_BASE_PATH . REFERENCES . $frm_details['clear_clientid'];

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
                                'reference_id' => $frm_details['clear_update_id'],
                                'type' => 2)
                            );
                        } else {
                            array_push($error_msgs, $this->upload->display_errors('', ''));

                            $json_array['status'] = ERROR_CODE;

                            $json_array['e_message'] = implode('<br>', $error_msgs);

                        }
                    }

                    if (!empty($file_array)) {
                        $this->reference_verificatiion_model->uploaded_files($file_array);
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
        if ($this->input->is_ajax_request() && $this->permission['access_reference_list_insuff_delete'] == 1) {
            $fields = array('status' => 3, 'modified_on' => date(DB_DATE_FORMAT), 'modified_by' => $this->user_info['id']);

            $result = $this->reference_verificatiion_model->save_update_insuff($fields, array('id' => $insuff_data));

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

    public function componet_html_form($cmp_id)
    {
        if ($this->input->is_ajax_request() && $cmp_id) {
            $details = $this->reference_verificatiion_model->get_all_reference_record(array('reference.id' => decrypt($cmp_id)));

            if (!empty($details)) {
                $data['details'] = $details[0];

                echo $this->load->view('admin/reference_add_result_model_view_first_qc', $data, true);

            } else {
                echo "<h4>Record Not Found</h4>";
            }

        } else {
            echo "<p>Something went wrong, please try again.</p>";
        }
    }

    public function bulk_upload_reference()
    {

        if ($this->input->is_ajax_request()) {
            $file_upload_path = SITE_BASE_PATH . REFERENCES;

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

                        if (count($value) < 6) {
                            continue;
                        }

                        $check_record_exits = $this->candidates_model->select(true, array('id', 'clientid', 'entity', 'package'), array('cmp_ref_no' => strtolower($value[0])));

                        if (!empty($check_record_exits) && $value[0] != "") {

                            $users_id  = $this->get_reporting_manager_for_executive($check_record_exits['clientid']); 

                            $user_id =  $users_id[0]['id'];

                            $tat_day = $this->get_client_entity_package_tat_day(array('tbl_clients_id' => $check_record_exits['clientid'], 'entity' => $check_record_exits['entity'], 'package' => $check_record_exits['package']));

                            $get_holiday1 = $this->get_holiday();

                            $get_holiday = array_map('current', $get_holiday1);

                            $closed_date = getWorkingDays(get_date_from_timestamp($value[1]), $get_holiday, $tat_day[0]['tat_refver']);

                            $mode_of_verification = $this->reference_verificatiion_model->get_mode_of_verification(array('entity' => $check_record_exits['entity'], 'package' => $check_record_exits['package'], 'tbl_clients_id' => $check_record_exits['clientid']));

                            if (!empty($mode_of_verification)) {
                                $mode_of_verification_value = json_decode($mode_of_verification[0]['mode_of_verification']);

                                $mode_of_veri = $mode_of_verification_value->refver;
                            } else {

                                $mode_of_veri = "";
                            }

                            $field_array = array('clientid' => $check_record_exits['clientid'],
                                'candsid' => $check_record_exits['id'],
                                'reference_com_ref' => '',
                                'name_of_reference' => $value[2],
                                'designation' => $value[3],
                                'contact_no' => $value[4],
                                'email_id' => $value[5],
                                'iniated_date' => get_date_from_timestamp($value[1]),
                                "reference_re_open_date" => '',
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

                            $insert_id = $this->reference_verificatiion_model->save($record);

                            $reference_com_ref = $this->reference_com_ref($insert_id);

                            auto_update_overall_status($check_record_exits['id']);
                            auto_update_tat_status($check_record_exits['id']);

                            $data['success'] = $reference_com_ref . " This Component Code Records Created Successfully";

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
        $this->load->model(array('reference_vendor_log_model'));

        $assigned_option = array(0 => 'select');

        ($this->permission['access_vendor_mgr_approve'] == 1) ? $assigned_option[1] = 'Assign' : '';
        ($this->permission['access_vendor_mgr_reject'] == 1) ? $assigned_option[2] = 'Reject' : '';
        $data['assigned_option'] = $assigned_option;
        $data['header_title'] = "Vendor Approve List";

        $data['lists'] = $this->reference_vendor_log_model->get_new_list(array('reference_vendor_log.status' => 0));

        $this->load->view('admin/header', $data);

        $this->load->view('admin/reference_vendor_aq');

        $this->load->view('admin/footer');
    }

    public function assign_to_executive()
    {
        $json_array = array();
        if ($this->input->is_ajax_request() && ($this->permission['access_reference_list_re_assign'] == '1')) {
            $frm_details = $this->input->post();

            if ($frm_details['users_list'] != 0 && $frm_details['cases_id'] != "") {
                $return = $this->common_model->update_in('reference', array('has_case_id' => $frm_details['users_list'], 'has_assigned_on' => date(DB_DATE_FORMAT)), array('where_id' => $frm_details['cases_id']));
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

    public function assign_to_vendor()
    {
        $json_array = array();

        if ($this->input->is_ajax_request() && $this->permission['access_vendor_allocation']) {
            $frm_details = $this->input->post();
            $list = explode(',', $frm_details['cases_id']);
            if ($frm_details['vendor_list'] != 0 && $frm_details['cases_id'] != "" && !empty($list)) {
                $files = $update = array();
                $insert_counter = 0;
                foreach ($list as $key => $value) {

                    $update = $this->common_model->update_batch_vendor_assign('reference', array('vendor_id' => $frm_details['vendor_list'], 'vendor_assgined_on' => date(DB_DATE_FORMAT)), array('id' => $value, 'vendor_id =' => 0));

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
                    $this->common_model->common_insert_batch('reference_vendor_log', $files);
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

    public function remove_uploaded_file($id)
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {
            $result = $this->reference_verificatiion_model->save_update_reference_files(array('status' => 2), array('id' => $id));

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
    public function reference_final_assigning()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {
            $frm_details = $this->input->post();
            $action = $frm_details['action'];

            if ($frm_details['action'] == 2 && $frm_details['cases_id'] != "" && $this->permission['access_vendor_mgr_reject'] == 1) {
                $list = explode(',', $frm_details['cases_id']);
                $update_counter = 0;
                foreach ($list as $key => $value) {

                    $update = $this->reference_verificatiion_model->upload_vendor_assign('reference_vendor_log', array('status' => 2, 'remarks' => $frm_details['reject_reason'], 'modified_by' => $this->user_info['id'], 'modified_on' => date(DB_DATE_FORMAT)), array('id' => $value));

                    if ($update) {
                        $update_counter++;
                        $return = $this->reference_verificatiion_model->save(array('vendor_id' => 0, 'vendor_assgined_on' => null), array('id' => $value));
                    }
                }

                if ($update_counter) {

                    $json_array['status'] = SUCCESS_CODE;
                    $json_array['message'] = $update_counter . " of " . count($list) . " Rejected Successfully";

                } else {
                    $json_array['status'] = ERROR_CODE;
                    $json_array['message'] = "Something went wrong,please try again";
                }

            } else if ($frm_details['action'] == 1 && $frm_details['cases_id'] != "" && $this->permission['access_vendor_mgr_approve']) {

                $list = explode(',', $frm_details['cases_id']);
                $update_counter = 0;
                $files = array();
                $last_insert_id = $this->common_model->last_insert_id();
                if ($last_insert_id > 0) {
                    foreach ($list as $key => $value) {

                        $update = $this->reference_verificatiion_model->upload_vendor_assign('reference_vendor_log', array('status' => 1, 'approval_by' => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT)), array('id' => $value));

                        if ($update) {
                            $trasaction_id = ++$last_insert_id;
                            $update_counter++;
                            $files[] = array('component' => 'Address',
                                'case_id' => $value,
                                'trasaction_id' => 'V' . $trasaction_id,
                                "status" => 1,
                                "remarks" => '',
                                "created_by" => $this->user_info['id'],
                                "created_on" => date(DB_DATE_FORMAT),
                                "vendor_status" => 0,
                                "modified_on" => null,
                                "modified_by" => '',
                            );
                        }
                    }
                    if (!empty($files)) {
                        $insert = $this->common_model->common_insert_batch('vendor_master_log', $files);
                    }

                    $json_array['status'] = SUCCESS_CODE;
                    $json_array['message'] = $update_counter . " of " . count($files) . " Assigned Successfully";

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

    public function reference_result_list($emp_id = '')
    {

        if ($emp_id && $this->input->is_ajax_request()) {
            $reference_result = $this->reference_verificatiion_model->select_result_log(array('reference_ver_result.reference_id' => $emp_id, 'activity_log_id !=' => null));

            $html_view = '<thead><tr><th>Created On</th><th>Created By</th><th>Action</th><th>Activit Mode</th><th>Attachment</th><th>Activity Type</th><th>Activity Status</th><th>View</th></tr></thead>';

            if (!empty($reference_result[0]['id'])) {
                $l = 1;
                foreach ($reference_result as $key => $value) {

                    $file = "&nbsp;&nbsp;&nbsp;&nbsp;";
                    if ($value['file_names']) {
                        $files = explode(',', $value['file_names']);

                        for ($i = 0; $i < count($files); $i++) {
                            $url = "'" . SITE_URL . REFERENCES . $value['clientid'] . '/';
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
                        $html_view .= '<td><button data-id="showAddResultModel" data-url ="' . ADMIN_SITE_URL . 'reference_verificatiion/reference_result_list_idwise/' . $value['id'] . '/' . str_replace(" ", "", $value['activity_action']) . '" data-toggle="modal" data-target="#showAddResultModel1"  class="btn-info  showAddResultModel"> View </button></td>';

                    } else {
                        $html_view .= '<td>  </td>';
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

    public function reference_result_list_idwise($where_id, $url)
    {
      
        $details = $this->reference_verificatiion_model->select_result_log1(array('reference_ver_result.id' => $where_id));

        if ($where_id && !empty($details)) {
            $data['check_insuff_raise'] = '';
            $data['details'] = $details[0];
            $data['url'] = $url;

            $data['attachments'] = $this->reference_verificatiion_model->select_file(array('id', 'file_name', 'status'), array('reference_id' => $details[0]['reference_id'], 'type' => 1));

            echo $this->load->view('admin/reference_add_result_model_view_log', $data, true);
        } else {
            echo "<h4>Record Not Found</h4>";
        }

    }

    public function template_download()
    {
        if ($this->input->is_ajax_request()) {

            //$assigned_user_id = $this->users_list();
           // $assigned_user_id = $this->reference_verificatiion_model->get_assign_users_id('user_profile', false, array("`user_profile`.`status`,`user_profile`.`id`,`user_profile`.`email`,`user_profile`.`department`,`user_profile`.`user_name`,concat(`user_profile`.`firstname`,' ',`user_profile`.`lastname`) as fullname,`user_profile`.`profile_pic`,`user_profile`.`firstname`,`user_profile`.`lastname`,`roles`.`role_name`,`roles`.`groups_id`,`roles_permissions`.*"), array('user_profile.status' => STATUS_ACTIVE));

            require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
            // Create new Spreadsheet object
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            // Set document properties
            $spreadsheet->getProperties()->setCreator(CRMNAME)
                ->setLastModifiedBy(CRMNAME)
                ->setTitle(CRMNAME)
                ->setSubject('Reference Import Template')
                ->setDescription('Reference Import Template File for bulk upload');

            $styleArray = array(
                'fill' => array(
                    'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startcolor' => array(
                        'rgb' => 'FF0000',
                    ),
                ),
            );
            $spreadsheet->getActiveSheet()->getStyle('A1:E1')->applyFromArray($styleArray);

            foreach (range('A', 'F') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                    ->setWidth(20);
            }

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A1", REFNO)
                ->setCellValue("B1", 'Comp Int Date')
                ->setCellValue("C1", 'Name of Reference')
                ->setCellValue("D1", 'Designation')
                ->setCellValue("E1", 'Contact Number')
                ->setCellValue("F1", 'Email ID');

            for ($i = 1; $i <= 1000; $i++) {

                $objValidation = $spreadsheet->getActiveSheet()->getCell('A' . $i)->getDataValidation();
                $objValidation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                $objValidation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
                $objValidation->setAllowBlank(false);
                $objValidation->setShowInputMessage(true);
                $objValidation->setShowErrorMessage(true);
                $objValidation->setErrorTitle('Input error');
                $objValidation->setError('This Field is Compulsory.');
                $objValidation->setPromptTitle('Insert Value');
                $objValidation->setPrompt('Please insert ' . REFNO);

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
                $objValidation->setErrorTitle('Input error');
                $objValidation->setError('This Field is Compulsory.');
                $objValidation->setPromptTitle('Insert Name of Reference');
                $objValidation->setPrompt('Please Insert Name of Reference.');

                $objValidation = $spreadsheet->getActiveSheet()->getCell('D' . $i)->getDataValidation();
                $objValidation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                $objValidation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
                $objValidation->setAllowBlank(false);
                $objValidation->setShowInputMessage(true);
                $objValidation->setShowErrorMessage(true);
                $objValidation->setErrorTitle('Input error');
                $objValidation->setError('This Field is Compulsory.');
                $objValidation->setPromptTitle('Insert Designation');
                $objValidation->setPrompt('Please Insert Designation');

                $objValidation = $spreadsheet->getActiveSheet()->getCell('E' . $i)->getDataValidation();
                $objValidation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                $objValidation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
                $objValidation->setAllowBlank(false);
                $objValidation->setShowInputMessage(true);
                $objValidation->setShowErrorMessage(true);
                $objValidation->setErrorTitle('Input error');
                $objValidation->setError('This Field is Compulsory.');
                $objValidation->setPromptTitle('Insert Contact Number');
                $objValidation->setPrompt('Please insert Maximum 10 digit and Mimimum 11.');

              /*  $objValidation = $spreadsheet->getActiveSheet()->getCell('G' . $i)->getDataValidation();
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

            $spreadsheet->getActiveSheet()->setTitle('Reference Records');
            $spreadsheet->setActiveSheetIndex(0);
            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment;filename=Reference Bulk Uplaod Template.xlsx");
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

            $json_array['file_name'] = "Reference Bulk Uplaod Template";

            $json_array['message'] = "File downloaded successfully,please check in download folder";

            $json_array['status'] = SUCCESS_CODE;
        } else {
            $json_array['file_name'] = "Reference Bulk Uplaod Template";

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

            $all_records = $this->reference_verificatiion_model->get_all_reference_verification_by_client($client_id, $fil_by_status, $from_date, $to_date);

            require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
            // Create new Spreadsheet object
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

            // Set document properties
            $spreadsheet->getProperties()->setCreator(CRMNAME)
                ->setLastModifiedBy(CRMNAME)
                ->setTitle(CRMNAME)
                ->setSubject('Reference Verification records')
                ->setDescription('Reference Verification records with their status');

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
            $spreadsheet->getActiveSheet()->getStyle('A1:Q1')->applyFromArray($styleArray);
            // auto fit column to content
            foreach (range('A', 'Q') as $columnID) {
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
                ->setCellValue("G1", 'Comp Received date')
                ->setCellValue("H1", 'Candidate Name')
                ->setCellValue("I1", 'Fathers Name')
                ->setCellValue("J1", 'Reference Name')
                ->setCellValue("K1", 'Status')
                ->setCellValue("L1", 'Sub Status')
                ->setCellValue("M1", 'Executive Name')
                ->setCellValue("N1", 'Closure Date')
                ->setCellValue("O1", 'Insuff Raised Date')
                ->setCellValue("P1", 'Insuff Clear Date')
                ->setCellValue("Q1", 'Insuff Remark');
            // Add some data
            $x = 2;
            foreach ($all_records as $all_record) {

                $reference_status = ($all_record['verfstatus'] != "") ? $all_record['verfstatus'] : "WIP";
                $reference_filter_status = ($all_record['filter_status'] != "") ? $all_record['filter_status'] : "WIP";
                $closuredate = ($all_record['filter_status'] == "Closed") ? convert_db_to_display_date($all_record['closuredate']) : "NA";
                $insuff_remarks = $all_record['insuff_raise_remark'];

                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A$x", ucwords($all_record['clientname']))
                    ->setCellValue("B$x", ucwords($all_record['entity_name']))
                    ->setCellValue("C$x", ucwords($all_record['package_name']))
                    ->setCellValue("D$x", $all_record['cmp_ref_no'])
                    ->setCellValue("E$x", $all_record['reference_com_ref'])
                    ->setCellValue("F$x", $all_record['ClientRefNumber'])
                    ->setCellValue("G$x", convert_db_to_display_date($all_record['iniated_date']))
                    ->setCellValue("H$x", ucwords($all_record['CandidateName']))
                    ->setCellValue("I$x", ucwords($all_record['NameofCandidateFather']))
                    ->setCellValue("J$x", $all_record['name_of_reference'])
                    ->setCellValue("K$x", $reference_filter_status)
                    ->setCellValue("L$x", $reference_status)
                    ->setCellValue("M$x", $all_record['executive_name'])
                    ->setCellValue("L$x", $reference_status)
                    ->setCellValue("M$x", $all_record['executive_name'])
                    ->setCellValue("N$x", $closuredate)
                    ->setCellValue("O$x", $all_record['insuff_raised_date'])
                    ->setCellValue("P$x", $all_record['insuff_clear_date'])
                    ->setCellValue("Q$x", $insuff_remarks);
                $x++;
            }
            // Rename worksheet
            $spreadsheet->getActiveSheet()->setTitle('Reference Verification Records');

            $spreadsheet->setActiveSheetIndex(0);

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment;filename=Reference Verification Records of $client_name.xlsx");
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

            $json_array['file_name'] = "Reference Verification Records of $client_name";

            $json_array['message'] = "File downloaded successfully,please check in download folder";

            $json_array['status'] = SUCCESS_CODE;

        } else {
            $json_array['message'] = "Something went wrong,please try again";
            $json_array['status'] = ERROR_CODE;
        }

        echo_json($json_array);
    }

}
