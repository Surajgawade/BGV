<?php defined('BASEPATH') or exit('No direct script access allowed');

class Employment extends MY_Controller
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

        $this->perm_array = array('page_id' => 5);

        if ($this->input->is_ajax_request()) {
            $this->perm_array = array('direct_access' => true);

        }

        $this->employment_type = array('' => 'Select', 'full time' => 'Full time', 'contractual' => 'Contractual', 'part time' => 'Part time');
        $this->assign_options = array('0' => 'Select Executive', '1' => 'Assign to Executive');
        $this->assign_options_vendor = array('0' => 'Select Vendor', '1' => 'Assign to Vendor');

        $this->load->model(array('employment_model'));
    }

    public function index()
    {
        $data['header_title'] = "Employment Verification";

        $data['filter_view'] = $this->filter_view();
       // $data['users_list'] = $this->employment_model->get_assign_users('user_profile', false, array("`user_profile`.`status`,`user_profile`.`id`,`user_profile`.`email`,`user_profile`.`department`,`user_profile`.`user_name`,concat(`user_profile`.`firstname`,' ',`user_profile`.`lastname`) as fullname,`user_profile`.`profile_pic`,`user_profile`.`firstname`,`user_profile`.`lastname`,`roles`.`role_name`,`roles`.`groups_id`,`roles_permissions`.*"), array('user_profile.status' => STATUS_ACTIVE));

        $data['vendor_list'] = $this->vendor_list('empver');

        $data['clients_id'] = $this->get_clients(array('status' => STATUS_ACTIVE));

        $data['status_value'] = $this->get_status();

        $this->load->view('admin/header', $data);

        $this->load->view('admin/employment_list');

        $this->load->view('admin/footer');
    }

    public function get_clients_for_employment_list_view()
    {
        $params = $_REQUEST;

        $clients = $this->employment_model->select_client_list_view_employment('clients', false, array('clients.id', 'clients.clientname'), $params);

        $clients_arry[0] = 'Select Client';

        foreach ($clients as $key => $value) {
            $clients_arry[$value['id']] = ucwords(strtolower($value['clientname']));
        }

        echo_json(array('client_list' => $clients_arry));

    }

    public function employment_view_datatable_new_cases()
    {
        log_message('error', 'New Cases in Employment');
        try {

            $params = $add_candidates = $data_arry = $columns = array();

            $params = $_REQUEST;

            $columns = array('addrver_id.id', 'candidates_info.ClientRefNumber', 'candidates_info.cmp_ref_no', 'company_database.coname', 'candidates_info.CandidateName', 'candidates_info.caserecddate', 'verfstatus', 'city', 'encry_id', 'address', 'pincode', 'state', 'check_closure_date', 'emp_com_ref', 'iniated_date');

            $where_arry = array();

            $add_candidates = $this->employment_model->get_all_emp_by_client_datatable_new_cases($where_arry, $params, $columns);

            $totalRecords = count($this->employment_model->get_all_emp_by_client_datatable_count_new_cases($where_arry, $params, $columns));

            $x = 0;

            foreach ($add_candidates as $add_candidate) {
                $data_arry[$x]['id'] = $x + 1;
                $data_arry[$x]['checkbox'] = $add_candidate['id'];
                $data_arry[$x]['ClientRefNumber'] = $add_candidate['ClientRefNumber'];
                $data_arry[$x]['emp_com_ref'] = $add_candidate['emp_com_ref'];
                $data_arry[$x]['cmp_ref_no'] = $add_candidate['cmp_ref_no'];
                $data_arry[$x]['iniated_date'] = convert_db_to_display_date($add_candidate['iniated_date']);
                $data_arry[$x]['clientname'] = $add_candidate['clientname'];
                $data_arry[$x]['vendor_name'] = ($add_candidate['vendor_name'] != '0') ? $add_candidate['vendor_name'] : '';
                $data_arry[$x]['CandidateName'] = $add_candidate['CandidateName'];
                $data_arry[$x]['locationaddr'] = $add_candidate['locationaddr'];
                $data_arry[$x]['citylocality'] = $add_candidate['citylocality'];
                $data_arry[$x]['last_activity_date'] = convert_db_to_display_date($add_candidate['last_activity_date'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);
                $data_arry[$x]['status_value'] = $add_candidate['status_value'];
                $data_arry[$x]['encry_id'] = ADMIN_SITE_URL . "employment/view_details/" . encrypt($add_candidate['id']);
                $data_arry[$x]['state'] = $add_candidate['state'];
                $data_arry[$x]['pincode'] = $add_candidate['pincode'];
                $data_arry[$x]['first_qc_approve'] = $add_candidate['first_qc_approve'];
                $data_arry[$x]['executive_name'] = $add_candidate['user_name'];
                $data_arry[$x]['tat_status'] = $add_candidate['tat_status'];
                $data_arry[$x]['check_closure_date'] = convert_db_to_display_date($add_candidate['due_date']);
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
            log_message('error', 'Employment::employment_view_datatable_new_cases');
            log_message('error', $e->getMessage());
        }
    }

    public function approval_queue()
    {
        $this->load->model(array('employment_vendor_log_model'));

        $assigned_option = array(0 => 'select');

        ($this->permission['access_education_aq_allow'] == 1) ? $assigned_option[1] = 'Assign' : '';
        ($this->permission['access_education_aq_allow'] == 1) ? $assigned_option[2] = 'Reject' : '';
        $data['assigned_option'] = $assigned_option;
        $data['header_title'] = "Vendor Approve List";

        $data['user_list_name'] = $this->users_list_filter();

        $this->load->view('admin/header', $data);

        $this->load->view('admin/employment_vendor_aq');

        $this->load->view('admin/footer');
    }

    public function view_approval_queue()
    {

        $this->load->model(array('employment_vendor_log_model'));

        $params = $add_candidates = $data_arry = $columns = array();

        $params = $_REQUEST;

        $lists = $this->employment_vendor_log_model->get_new_list(array('employment_vendor_log.status' => 0), $params);

        $totalRecords = count($this->employment_vendor_log_model->get_new_list_count(array('employment_vendor_log.status' => 0), $params));

            $x = 0;

            foreach ($lists as $list) {
                $data_arry[$x]['checkbox'] = $list['id'];
                $data_arry[$x]['id'] = $x + 1;

                $data_arry[$x]['emp_com_ref'] = $list['emp_com_ref'];
                $data_arry[$x]['clientname'] = $list['clientname'];
                $data_arry[$x]['entity_name'] = $list['entity_name'];
                $data_arry[$x]['package_name'] = $list['package_name'];
                $data_arry[$x]['vendor_name'] = $list['vendor_name'];
                $data_arry[$x]['locationaddr'] = $list['locationaddr'];
                $data_arry[$x]['encry_id'] = ADMIN_SITE_URL . "employment/field_visit/" . encrypt($list['case_id']);
                $data_arry[$x]['citylocality'] = $list['citylocality'];
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
    }

    public function assign_to_vendor()
    {
        $json_array = array();

        if ($this->input->is_ajax_request() && $this->permission['access_education_assign_edu_assign'] == true) {
            $frm_details = $this->input->post();

            $list = explode(',', $frm_details['cases_id']);
            if ($frm_details['vendor_list'] != 0 && $frm_details['cases_id'] != "" && !empty($list)) {
                $files = $update = array();
                $insert_counter = 0;
                foreach ($list as $key => $value) {

                    $update = $this->common_model->update_batch_vendor_assign('empver', array('vendor_id' => $frm_details['vendor_list'], 'vendor_list_mode' => $frm_details['vendor_list_mode'], 'vendor_assgined_on' => date(DB_DATE_FORMAT)), array('id' => $value, 'vendor_id =' => 0));

                    $update1 = $this->common_model->update_status('empverres', array('verfstatus' => 1), array('empverid' => $value));

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
                    $inserted = $this->common_model->common_insert_batch('employment_vendor_log', $files);
                }

                if (!empty($inserted)) {

                    $result_get_count_plus = $this->common_model->get_count(array('controllers' => 'employment/approval_queue'));

                    $total_get_count_plus = $result_get_count_plus + count($list);

                    $result_update_count_plus = $this->common_model->update_count(array('count' => $total_get_count_plus), array('controllers' => 'employment/approval_queue'));

                    $result_get_count_minus = $this->common_model->get_count(array('controllers' => 'assign_employment'));

                    $total_get_count_minus = $result_get_count_minus - count($list);

                    $result_update_count_minus = $this->common_model->update_count(array('count' => $total_get_count_minus), array('controllers' => 'assign_employment'));

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

    public function add($candsid = false)
    {
        if ($this->input->is_ajax_request()) {

            $data['get_cands_details'] = $this->candidate_entity_pack_details($candsid);
            $data['mode_of_verification'] = $this->employment_model->get_mode_of_verification(array('entity' => $data['get_cands_details']['entity_id'], 'package' => $data['get_cands_details']['package_id'], 'tbl_clients_id' => $data['get_cands_details']['clientid']));

            $data['states'] = $this->get_states();
            // $data['assigned_user_id'] = $this->users_list();
         //   $data['assigned_user_id'] = $this->employment_model->get_assign_users_id('user_profile', false, array("`user_profile`.`status`,`user_profile`.`id`,`user_profile`.`email`,`user_profile`.`department`,`user_profile`.`user_name`,concat(`user_profile`.`firstname`,' ',`user_profile`.`lastname`) as fullname,`user_profile`.`profile_pic`,`user_profile`.`firstname`,`user_profile`.`lastname`,`roles`.`role_name`,`roles`.`groups_id`,`roles_permissions`.*"), array('user_profile.status' => STATUS_ACTIVE));
            $data['assigned_user_id']  = $this->get_reporting_manager_for_execituve($data['get_cands_details']['clientid']);

            $data['company_list'] = $this->get_company_list();


            echo $this->load->view('admin/employment_add', $data, true);
        } else {
            echo "<h3>Something went wrong, please try again</h3>";
        }
    }

    public function view_from_cands($emp_comp_id = false)
    {
        $emp_details = $this->employment_model->get_employer_details(array('empver.emp_com_ref' => $emp_comp_id));

        if ($emp_comp_id && !empty($emp_details)) {
            $data['header_title'] = 'Edit Employment';

            if($emp_details[0]['has_case_id'] == $this->user_info['id'])
            {
                $data['bcc_email_id'] =  $this->user_info['email'].','.FROMEMAIL;
            }
            else
            {
                $email = $this->employment_model->get_user_email_id(array('user_profile.id' => $emp_details[0]['has_case_id']));

                $data['bcc_email_id']  =  $this->user_info['email'].','.$email.','.FROMEMAIL;
            }



            $data['empt_details'] = $emp_details[0];

            $data['get_cands_details'] = $this->candidate_entity_pack_details($emp_details[0]['candsid']);

            $data['mode_of_verification'] = $this->employment_model->get_mode_of_verification(array('entity' => $data['get_cands_details']['entity_id'], 'package' => $data['get_cands_details']['package_id'], 'tbl_clients_id' => $data['get_cands_details']['clientid']));

            $data['states'] = $this->get_states();

            $data['email_info'] = $emp_details[0];

            $data['company'] = $this->get_company_list();

            $data['reinitiated'] = ($emp_details[0]['var_filter_status'] == 'Closed' || $emp_details[0]['var_filter_status'] == 'closed') ? '1' : '2';


          //  $data['assigned_user_id'] = $this->employment_model->get_assign_users_id('user_profile', false, array("`user_profile`.`status`,`user_profile`.`id`,`user_profile`.`email`,`user_profile`.`department`,`user_profile`.`user_name`,concat(`user_profile`.`firstname`,' ',`user_profile`.`lastname`) as fullname,`user_profile`.`profile_pic`,`user_profile`.`firstname`,`user_profile`.`lastname`,`roles`.`role_name`,`roles`.`groups_id`,`roles_permissions`.*"), array('user_profile.status' => STATUS_ACTIVE));

           
            $data['assigned_user_id']  = $this->get_reporting_manager_for_execituve($emp_details[0]['clientid']);

            $data['attachments'] = $this->employment_model->select_file(array('id', 'file_name', 'real_filename', 'type'), array('empver_id' => $emp_details[0]['id'], 'status' => 1));

            $check_insuff_raise = $this->employment_model->select_insuff(array('empverres_id' => $emp_details[0]['id'], 'status' => STATUS_ACTIVE, 'insuff_clear_date is null' => null));

            $data['insuff_reason_list'] = $this->employment_model->insuff_reason_list(false, array('component_id' => 2));

            $data['insuff_raise_message'] = (!empty($check_insuff_raise) ? 'Insufficiency raised on ' . convert_db_to_display_date($check_insuff_raise[0]['insuff_raised_date']) . ' for ' . $check_insuff_raise[0]['insff_reason'] : '');

            $data['check_insuff_raise'] = ((!empty($check_insuff_raise)) ? 'disabled' : '');
            $data['check_insuff_value'] = ((!empty($check_insuff_raise)) ? '1' : '2');
            $data['insuff_raise_id'] = (!empty($check_insuff_raise) ? $check_insuff_raise[0]['id'] : '');

            $data['clients'] = $this->get_clients(array('status' => STATUS_ACTIVE));

            echo $this->load->view('admin/employment_edit_inside', $data, true);
        } else {
            echo "<h3>Something went wrong, please try again</h3>";
        }
    }

    protected function emp_com_ref($insert_id)
    {
        $name = COMPONENT_REF_NO['EMPLOYMENT'];

        $employmentnumber = $name . $insert_id;

        $field_array = array('emp_com_ref' => $employmentnumber);

        $update_auto_increament_id = $this->employment_model->update_auto_increament_value($field_array, array('id' => $insert_id));

        return $employmentnumber;
    }

    public function save_employment()
    {

        if ($this->input->is_ajax_request()) {

            $this->form_validation->set_rules('clientid', 'Client', 'required');

            $this->form_validation->set_rules('candsid', 'Candidate', 'required');

            if ($this->form_validation->run() == false) {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');
            } else {
                $frm_details = $this->input->post();


                $is_exit = $this->employment_model->check_cin_exist($frm_details['nameofthecompany']);

                $is_company_exit = $this->employment_model->check_company_exist(str_replace("-", ' ', $frm_details['selected_company_name']));

                if (!empty($is_exit)) {
                    $nameofthecompany = $is_exit[0]['id'];
                } else if (!empty($is_company_exit)) {
                    $nameofthecompany = $is_company_exit[0]['id'];
                } else {
                    $nameofthecompany = $this->employment_model->save_company_details(array('cin_number' => $frm_details['nameofthecompany'], 'coname' => str_replace('-', ' ', $frm_details['selected_company_name'])));
                }

                $tat_day = $this->get_client_entity_package_tat_day(array('tbl_clients_id' => $frm_details['clientid'], 'entity' => $frm_details['entity_id'], 'package' => $frm_details['package_id']));

                $get_holiday1 = $this->get_holiday();

                $get_holiday = array_map('current', $get_holiday1);

                $closed_date = getWorkingDays(convert_display_to_db_date($frm_details['iniated_date']), $get_holiday, $tat_day[0]['tat_empver']);

                $field_array = array('has_case_id' => $frm_details['has_case_id'],
                    'clientid' => $frm_details['clientid'],
                    'emp_com_ref' => '',
                    'candsid' => $frm_details['candsid'],
                    'empid' => $frm_details['empid'],
                    'nameofthecompany' => $nameofthecompany,
                    'deputed_company' => $frm_details['deputed_company'],
                    'employment_type' => $frm_details['employment_type'],
                    'locationaddr' => $frm_details['locationaddr'],
                    'citylocality' => $frm_details['citylocality'],
                    'mode_of_veri' => $frm_details['mod_of_veri'],
                    'pincode' => $frm_details['pincode'],
                    'state' => $frm_details['state'],
                    'compant_contact' => $frm_details['compant_contact'],
                    'compant_contact_name' => $frm_details['compant_contact_name'],
                    'compant_contact_designation' => $frm_details['compant_contact_designation'],
                    'compant_contact_email' => $frm_details['compant_contact_email'],
                    'empfrom' => $frm_details['empfrom'],
                    'empto' => $frm_details['empto'],
                    'designation' => $frm_details['designation'],
                    'remuneration' => $frm_details['remuneration'],
                    'iniated_date' => convert_display_to_db_date($frm_details['iniated_date']),
                    "reasonforleaving" => $frm_details['reasonforleaving'],
                    "emp_re_open_date" => '',
                    'r_manager_name' => $frm_details['r_manager_name'],
                    'r_manager_no' => $frm_details['r_manager_no'],
                    'r_manager_designation' => $frm_details['r_manager_designation'],
                    'r_manager_email' => $frm_details['r_manager_email'],
                    'created_by' => $this->user_info['id'],
                    'created_on' => date(DB_DATE_FORMAT),
                    'modified_on' => date(DB_DATE_FORMAT),
                    'modified_by' => $this->user_info['id'],
                    'has_assigned_on' => date(DB_DATE_FORMAT),
                    'is_bulk_uploaded' => 0,
                    "due_date" => $closed_date,
                    "uan_no" => $frm_details['uan_no'],
                    "tat_status" => 'IN TAT',
                );

                $field_array = array_map('strtolower', $field_array);

                $lists = $this->employment_model->select_required_field(array('company_database.id' => $frm_details['nameofthecompany']));

                if (!empty($lists[0])) {
                    if ($lists[0]['previous_emp_code'] == 1) {
                        if ($frm_details['empid'] == "") {
                            $field_array['reject_status'] = 2;
                        }
                    }
                    if ($lists[0]['branch_location'] == 1) {
                        if ($frm_details['citylocality'] == "") {
                            $field_array['reject_status'] = 2;
                        }
                    }
                    if ($lists[0]['experience_letter'] == 1) {
                        if ($_FILES['attchments_reliving']['name'][0] == "") {
                            $field_array['reject_status'] = 2;
                        }
                    }
                    if ($lists[0]['loa'] == 1) {
                        if ($_FILES['attchments_loa']['name'][0] == "") {
                            $field_array['reject_status'] = 2;
                        }
                    }
                }

                $result = $this->employment_model->save($field_array);

                $emp_com_ref = $this->emp_com_ref($result);

                $total_rows = count(array_filter($frm_details['supervisor_name']));

                $x = 0;
                $supervisor_array = array();
                while ($x < $total_rows) {
                    if ($frm_details['supervisor_name']) {
                        array_push($supervisor_array,
                            array(
                                'supervisor_name' => $frm_details['supervisor_name'][$x],
                                'supervisor_designation' => $frm_details['supervisor_designation'][$x],
                                'supervisor_contact_details' => $frm_details['supervisor_contact_details'][$x],
                                'supervisor_email_id' => $frm_details['supervisor_email_id'][$x],
                                'status' => 1,
                                'created_by' => $this->user_info['id'],
                                'created_on' => date(DB_DATE_FORMAT),
                                'empver_id' => $result,
                            ));
                    }
                    $x++;
                }

                if (!empty($supervisor_array)) {
                    $this->employment_model->empver_supervisor_details($supervisor_array);
                }

                $error_msgs = array();
                $file_upload_path = SITE_BASE_PATH . EMPLOYMENT . $frm_details['clientid'];
                if (!folder_exist($file_upload_path)) {
                    mkdir($file_upload_path, 0777);
                } else if (!is_writable($file_upload_path)) {
                    array_push($error_msgs, 'Problem while uploading');
                }

                $config_array = array('file_upload_path' => $file_upload_path, 'file_permission' => 'jpeg|jpg|png|pdf|docx|xls|xlsx', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 1000, 'file_id' => $result,'component_name' => 'empver_id');

                if (empty($error_msgs)) {
                    if ($_FILES['attchments']['name'][0] != '') {
                        $config_array['files_count'] = count($_FILES['attchments']['name']);
                        $config_array['file_data'] = $_FILES['attchments'];
                        $config_array['type'] = 0;
                        $retunr_de = $this->file_upload_library->file_upload_multiple($config_array, true);
                        if (!empty($retunr_de['success'])) {

                            $this->common_model->common_insert_batch('empverres_files', $retunr_de['success']);
                        }
                    }

                    if ($_FILES['attchments_reliving']['name'][0] != '') {
                        $config_array['files_count'] = count($_FILES['attchments_reliving']['name']);
                        $config_array['file_data'] = $_FILES['attchments_reliving'];
                        $config_array['type'] = 3;
                        $retunr_de = $this->file_upload_library->file_upload_multiple($config_array, true);
                        if (!empty($retunr_de['success'])) {
                            $this->common_model->common_insert_batch('empverres_files', $retunr_de['success']);
                        }
                    }

                    if ($_FILES['attchments_loa']['name'][0] != '') {
                        $config_array['files_count'] = count($_FILES['attchments_loa']['name']);
                        $config_array['file_data'] = $_FILES['attchments_loa'];
                        $config_array['type'] = 4;
                        $retunr_de = $this->file_upload_library->file_upload_multiple($config_array, true);
                        if (!empty($retunr_de['success'])) {

                             $this->common_model->common_insert_batch('empverres_files', $retunr_de['success']);
                        }
                    }

                    /*if ($_FILES['attchments_cs']['name'][0] != '') {
                        $config_array['files_count'] = count($_FILES['attchments_cs']['name']);
                        $config_array['file_data'] = $_FILES['attchments_cs'];
                        $config_array['type'] = 2;
                        $retunr_cd = $this->file_upload_multiple($config_array);
                        if (!empty($retunr_cd)) {
                            $this->common_model->common_insert_batch('empverres_files', $retunr_cd['success']);
                        }
                    }*/

                    if ($frm_details['upload_capture_image_employment']) {

                        $copy_attachment_selection = $frm_details['copy_attachment_selection'];

                        $upload_capture_image = explode("||", $frm_details['upload_capture_image_employment']);
                            
                        foreach ($upload_capture_image as $key => $value) {
                            $key = $key + 1;

                            $file_name = $emp_com_ref . '-' .$key. '-' . date(UPLOAD_FILE_DATE_FORMAT) . '.png';
                            $uploadpath = $file_upload_path . '/' . $file_name;
                            $base64_to_jpeg = base64_to_jpeg($value, $uploadpath);
                                if ($base64_to_jpeg) {
                                    log_message('error', 'Inside if condition success');
                                    $this->common_model->save('empverres_files', ['file_name' => $file_name, 'real_filename' => $file_name,'type' => $copy_attachment_selection,  'empver_id' => $result]);
                                }

                        }
                    }
                }

                if ($result) {

                    $user_activity_data = $this->common_model->user_actity_data(array('component' => "Employment", 'ref_no' => $emp_com_ref, 'candidate_name' => $frm_details['CandidateName'], 'created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id'], 'activity_type' => 'Manual', 'action' => 'Add'));

                    auto_update_overall_status($frm_details['candsid']);
                    auto_update_tat_status($frm_details['candsid']);

                    $reject_status = $this->employment_model->select(true, array('reject_status', 'nameofthecompany'), array('id' => $result));

                    if ($reject_status['reject_status'] == '1') {
                        $lists_company_details = $this->employment_model->select_required_field(array('company_database.id' => $reject_status['nameofthecompany']));

                        if ($lists_company_details[0]['auto_initiate'] == '1')
                        { 
                            if ($lists_company_details[0]['client_disclosure'] == '1')
                            {
                               $candidate_entity_package = $this->employment_model->get_candidate_entity_package(array('candidates_info.id' =>$frm_details['candsid']));

                               $client_disc = $this->employment_model->get_client_disclosure_details(array('entity' => $candidate_entity_package[0]['entity'], 'package' => $candidate_entity_package[0]['package'], 'tbl_clients_id' => $candidate_entity_package[0]['clientid']));

                                if($client_disc[0]['client_disclosures'] == 1)
                                {
                                   
                                    
                                    if(!empty($lists_company_details[0]['co_email_id']))
                                    { 
                                       $to_email_id =  $lists_company_details[0]['co_email_id'];
                                       $cc_email_id =  $lists_company_details[0]['cc_email_id'];
                                       if($frm_details['has_case_id'] == $this->user_info['id'])
                                       {
                                          $bcc_email_id =  $this->user_info['email'];
                                       }
                                       else
                                       {
                                          $email = $this->employment_model->get_user_email_id(array('user_profile.id' => $frm_details['has_case_id']));

                                          $bcc_email_id =  $this->user_info['email'].','.$email;
                                       }
                                    }
                                    else
                                    {
                                       $lists_verifiers_details = $this->employment_model->select_email_id_verifiers(array('company_database_verifiers_details.company_database_id'=>  $reject_status['nameofthecompany'])); 

                                        foreach ($lists_verifiers_details as $key => $value) {

                                        if(!empty($value['verifiers_email_id']))
                                        {
                                            $to_email_id = $value['verifiers_email_id'];
                                            $cc_email_id = $this->user_info['email'];
                                            if($frm_details['has_case_id'] == $this->user_info['id'])
                                            {
                                                $bcc_email_id =  $this->user_info['email'];
                                            }
                                            else
                                            {
                                                $email = $this->employment_model->get_user_email_id(array('user_profile.id' => $frm_details['has_case_id']));

                                                $bcc_email_id =  $this->user_info['email'].','.$email;
                                            }
                                            break;
                                         }
                                         else
                                         {
                                            continue;
                                         }
                                         
                                       }
                                       
                                    }
                                    
                                    if(!empty($to_email_id))
                                    {
                                       $attachments = $this->employment_model->select_file(array('id', 'file_name', 'real_filename', 'type'), array('empver_id' => decrypt($result), 'status' => 1));
                                       $employment_selection_attachment = array();
                                       if($lists_company_details[0]['experience_letter'] == "1")
                                       {
                                           foreach ($attachments as $key => $value) {
                                                if($value['type'] == 3)
                                                {
                                                    array_push($employment_selection_attachment,$value['file_name']);
                                                }
                                           }
                                       }
                                       if($lists_company_details[0]['loa'] == "1")
                                       {
                                           foreach ($attachments as $key => $value) {
                                                if($value['type'] == 4)
                                                {
                                                    array_push($employment_selection_attachment,$value['file_name']);
                                                }
                                           }
                                       }


                                       $email = $this->employment_model->get_user_email_id(array('user_profile.id' => $frm_details['has_case_id']));
                                       $send_mail = $this->emp_send_mail_auto($result,$email,$to_email_id,$cc_email_id,$bcc_email_id,'yes',$employment_selection_attachment);  
                                    }
                                } 
                            }
                            else
                            {
                                    if(!empty($lists_company_details[0]['co_email_id']))
                                    { 
                                       $to_email_id =  $lists_company_details[0]['co_email_id'];
                                       $cc_email_id =  $lists_company_details[0]['cc_email_id'];
                                       if($frm_details['has_case_id'] == $this->user_info['id'])
                                       {
                                          $bcc_email_id =  $this->user_info['email'];
                                       }
                                       else
                                       {
                                          $email = $this->employment_model->get_user_email_id(array('user_profile.id' => $frm_details['has_case_id']));

                                          $bcc_email_id =  $this->user_info['email'].','.$email;
                                       }
                                    }
                                    else
                                    {
                                       $lists_verifiers_details = $this->employment_model->select_email_id_verifiers(array('company_database_verifiers_details.company_database_id'=>  $reject_status['nameofthecompany'])); 

                                        foreach ($lists_verifiers_details as $key => $value) {

                                        if(!empty($value['verifiers_email_id']))
                                        {
                                            $to_email_id = $value['verifiers_email_id'];
                                            $cc_email_id = $this->user_info['email'];
                                            if($frm_details['has_case_id'] == $this->user_info['id'])
                                            {
                                                $bcc_email_id =  $this->user_info['email'];
                                            }
                                            else
                                            {
                                                $email = $this->employment_model->get_user_email_id(array('user_profile.id' => $frm_details['has_case_id']));

                                                $bcc_email_id =  $this->user_info['email'].','.$email;
                                            }
                                            break;
                                         }
                                         else
                                         {
                                            continue;
                                         }
                                         
                                       }
                                       
                                    }
                                    
                                    if(!empty($to_email_id))
                                    {
                                       $attachments = $this->employment_model->select_file(array('id', 'file_name', 'real_filename', 'type'), array('empver_id' => decrypt($result), 'status' => 1));
                                       $employment_selection_attachment = array();
                                       if($lists_company_details[0]['experience_letter'] == "1")
                                       {
                                           foreach ($attachments as $key => $value) {
                                                if($value['type'] == 3)
                                                {
                                                    array_push($employment_selection_attachment,$value['file_name']);
                                                }
                                           }
                                       }
                                       if($lists_company_details[0]['loa'] == "1")
                                       {
                                           foreach ($attachments as $key => $value) {
                                                if($value['type'] == 4)
                                                {
                                                    array_push($employment_selection_attachment,$value['file_name']);
                                                }
                                           }
                                       }

                                       $email = $this->employment_model->get_user_email_id(array('user_profile.id' => $frm_details['has_case_id']));
                                       $send_mail = $this->emp_send_mail_auto($result,$email,$to_email_id,$cc_email_id,$bcc_email_id,'no',$employment_selection_attachment);  
                                    }
                                  
                            }
                        }
                    }

                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'Employment Record Successfully Inserted';

                    $json_array['redirect'] = ADMIN_SITE_URL . 'employment';
                } else {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Something went wrong,please try again';
                }
            }
            echo_json($json_array);
        }
    }

    public function employement_view_datatable()
    {

        if ($this->permission['access_employment_list_view'] == true) {
            $params = $details = $data_arry = array();

            $params = $_REQUEST;

            $columns = array('empver.id', 'candidates_info.ClientRefNumber', 'candidates_info.cmp_ref_no', 'company_database.coname', 'candidates_info.CandidateName', 'candidates_info.caserecddate', 'verfstatus', 'mail_sent_addrs', 'encry_id', 'employercontactno', 'locationaddr', 'citylocality', 'empfrom', 'empto', 'designation', 'remuneration', 'check_closure_date');

            $where_arry = array();

            $employment_lists = $this->employment_model->get_all_emp_by_client_datatable($where_arry, $params, $columns);

            $totalRecords = count($this->employment_model->get_all_emp_by_client_datatable_count($where_arry, $params, $columns));

            $x = 0;
            foreach ($employment_lists as $employment_list) {
                $data_arry[$x]['id'] = $x + 1;
               // $data_arry[$x]['checkbox'] = $employment_list['id'];
                $data_arry[$x]['ClientRefNumber'] = $employment_list['ClientRefNumber'];
                $data_arry[$x]['cmp_ref_no'] = $employment_list['cmp_ref_no'];
                $data_arry[$x]['emp_com_ref'] = $employment_list['emp_com_ref'];
                $data_arry[$x]['clientname'] = $employment_list['clientname'];
                $data_arry[$x]['field_status'] = $employment_list['field_visit_status'];

                $data_arry[$x]['coname'] = ucwords($employment_list['coname']);
                $data_arry[$x]['CandidateName'] = ucwords($employment_list['CandidateName']);
                $data_arry[$x]['caserecddate'] = convert_db_to_display_date($employment_list['caserecddate']);
                $data_arry[$x]['iniated_date'] = convert_db_to_display_date($employment_list['iniated_date']);
                // $data_arry[$x]['first_qc_approve'] = $employment_list['first_qc_approve'];
                $data_arry[$x]['empid'] = $employment_list['empid'];
                $data_arry[$x]['encry_id'] = ADMIN_SITE_URL . "employment/view_details/" . encrypt($employment_list['id']);
                // $data_arry[$x]['edit_access'] = $this->permission['access_employment_list_edit'];
                $data_arry[$x]['verfstatus'] = $employment_list['status_value'];
                $data_arry[$x]['executive_name'] = $employment_list['user_name'];
                $data_arry[$x]['has_assigned_on'] = convert_db_to_display_date($employment_list['has_assigned_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);
                $data_arry[$x]['last_activity_date'] = convert_db_to_display_date($employment_list['last_activity_date'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);
                $data_arry[$x]['due_date'] = convert_db_to_display_date($employment_list['due_date']);
                $data_arry[$x]['tat_status'] = $employment_list['tat_status'];
                $data_arry[$x]['mode_of_veri'] = $employment_list['mode_of_veri'];
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

    public function get_assign_employment_list_view()
    {
        log_message('error', 'Employment New Cases');
        try {
            $data['header_title'] = "Employment Verification";

            $data['user_list_name'] = $this->users_list_filter();

            echo $this->load->view('admin/new_cases_employment', $data, true);

        } catch (Exception $e) {
            log_message('error', 'Employment::get_assign_employment_list_view');
            log_message('error', $e->getMessage());
        }
    }

    public function view_details($emppoyment_id = '')
    {
        try {
            $emp_details = $this->employment_model->get_employer_details(array('empver.id' => decrypt($emppoyment_id)));

            if ($emppoyment_id && !empty($emp_details)) {
                log_message('error', 'inside if condition');
                try {

                    if($emp_details[0]['has_case_id'] == $this->user_info['id'])
                    {
                        $data['bcc_email_id'] =  $this->user_info['email'].','.FROMEMAIL;
                    }
                    else
                    {
                        $email = $this->employment_model->get_user_email_id(array('user_profile.id' => $emp_details[0]['has_case_id']));

                        $data['bcc_email_id']  =  $this->user_info['email'].','.$email.','.FROMEMAIL;
                    }


                    $data['email_info'] = $emp_details[0];

                    $data['header_title'] = 'Edit Employment';

                    $data['empt_details'] = $emp_details[0];

                    $data['get_cands_details'] = $this->candidate_entity_pack_details($emp_details[0]['candsid']);

                    $data['mode_of_verification'] = $this->employment_model->get_mode_of_verification(array('entity' => $data['get_cands_details']['entity_id'], 'package' => $data['get_cands_details']['package_id'], 'tbl_clients_id' => $data['get_cands_details']['clientid']));

                    $data['reinitiated'] = (($emp_details[0]['first_qc_approve'] == 'first qc approve' || $emp_details[0]['first_qc_approve'] == 'First QC Approve' || $emp_details[0]['first_qc_approve'] == '') && ($emp_details[0]['var_filter_status'] == 'Closed' || $emp_details[0]['var_filter_status'] == 'closed')) ? '1' : '2';

                    $data['states'] = $this->get_states();

                    $data['company'] = $this->get_company_list();

                   // $data['assigned_user_id'] = $this->employment_model->get_assign_users_id('user_profile', false, array("`user_profile`.`status`,`user_profile`.`id`,`user_profile`.`email`,`user_profile`.`department`,`user_profile`.`user_name`,concat(`user_profile`.`firstname`,' ',`user_profile`.`lastname`) as fullname,`user_profile`.`profile_pic`,`user_profile`.`firstname`,`user_profile`.`lastname`,`roles`.`role_name`,`roles`.`groups_id`,`roles_permissions`.*"), array('user_profile.status' => STATUS_ACTIVE));

                    $data['assigned_user_id']  = $this->get_reporting_manager_for_execituve($emp_details[0]['clientid']);


                    $check_insuff_raise = $this->employment_model->select_insuff(array('empverres_id' => decrypt($emppoyment_id), 'status' => STATUS_ACTIVE, 'insuff_clear_date is null' => null));

                    $data['attachments'] = $this->employment_model->select_file(array('id', 'file_name', 'real_filename', 'type'), array('empver_id' => decrypt($emppoyment_id), 'status' => 1));

                    $data['insuff_reason_list'] = $this->employment_model->insuff_reason_list(false, array('component_id' => 2));

                    $data['insuff_raise_message'] = (!empty($check_insuff_raise) ? 'Insufficiency raised on ' . convert_db_to_display_date($check_insuff_raise[0]['insuff_raised_date']) . ' for ' . $check_insuff_raise[0]['insff_reason'] : '');

                    $data['check_insuff_value'] = ((!empty($check_insuff_raise)) ? '1' : '2');

                    $data['check_insuff_raise'] = ((!empty($check_insuff_raise)) ? 'disabled' : '');
                    $data['insuff_raise_id'] = (!empty($check_insuff_raise) ? $check_insuff_raise[0]['id'] : '');


                    $initiation_mail_details = $this->employment_model->emp_initial_mail_details(array('empver_id' => $emppoyment_id));
                    if(!empty($initiation_mail_details))
                    {
                       
                    $data['initiation_mail_details'] = $initiation_mail_details[0]; 
                    }
                    else
                    {
                      $data['initiation_mail_details'] =  '';  
                    }

                    $data['clients'] = $this->get_clients(array('status' => STATUS_ACTIVE));

                    $this->load->view('admin/header', $data);

                    $this->load->view('admin/employment_edit');
                    

                } catch (Exception $e) {
                    log_message('error', 'Error on Employment::view_details');
                    log_message('error', $e->getMessage());
                }
            } else {
                show_404();
            }
        } catch (Exception $e) {
            log_message('error', 'Error on Employment::view_details');
            log_message('error', $e->getMessage());
        }
    }

    public function employment_edit_view()
    {
        try {
            $emp_details = $this->employment_model->get_employer_details(array('empver.id' => decrypt($emppoyment_id)));

            if ($emppoyment_id && !empty($emp_details)) {
                log_message('error', 'inside if');

                $data['header_title'] = 'Edit Employment';
                $data['empt_details'] = $emp_details[0];

                $data['states'] = $this->get_states();

                $data['company'] = $this->get_company_list();

                //$data['assigned_user_id'] = $this->employment_model->get_assign_users_id('user_profile', false, array("`user_profile`.`status`,`user_profile`.`id`,`user_profile`.`email`,`user_profile`.`department`,`user_profile`.`user_name`,concat(`user_profile`.`firstname`,' ',`user_profile`.`lastname`) as fullname,`user_profile`.`profile_pic`,`user_profile`.`firstname`,`user_profile`.`lastname`,`roles`.`role_name`,`roles`.`groups_id`,`roles_permissions`.*"), array('user_profile.status' => STATUS_ACTIVE));
                $data['assigned_user_id']  = $this->get_reporting_manager_for_execituve($emp_details[0]['clientid']);

                $check_insuff_raise = $this->employment_model->select_insuff(array('empverres_id' => decrypt($emppoyment_id), 'status' => STATUS_ACTIVE, 'insuff_clear_date is null' => null));

                $data['insuff_reason_list'] = $this->insuff_reason_list(6);

                $data['insuff_raise_message'] = (!empty($check_insuff_raise) ? 'Insufficiency raised on ' . convert_db_to_display_date($check_insuff_raise[0]['insuff_raised_date']) . ' for ' . $check_insuff_raise[0]['insff_reason'] : '');

                $data['check_insuff_raise'] = (!empty($check_insuff_raise) ? 'disabled' : '');

                $data['clients'] = $this->get_clients(array('status' => STATUS_ACTIVE));

                $this->load->view('admin/header', $data);

                $this->load->view('admin/employment_edit');

                $this->load->view('admin/footer');
            } else {
                show_404();
            }
        } catch (Exception $e) {
            log_message('error', 'Error on Employment::employment_edit_view');
            log_message('error', $e->getMessage());
        }
    }

    public function update_employment()
    {
        try {
            if ($this->input->is_ajax_request() && $this->permission['access_employment_list_edit'] == true) {

                $this->form_validation->set_rules('update_id', 'Update ID', 'required');

                $this->form_validation->set_rules('has_case_id', 'Assigned To Executive', 'required');

                $this->form_validation->set_rules('emp_com_ref', 'Employment Component', 'required');

                $this->form_validation->set_rules('clientid', 'Client', 'required');

                $this->form_validation->set_rules('candsid', 'Candidate', 'required');

                if ($this->form_validation->run() == false) {
                    $json_array['status'] = ERROR_CODE;
                    $json_array['message'] = validation_errors('', '');
                } else {

                    try {
                        $frm_details = $this->input->post();
                        log_message('error', print_r($frm_details, true));
                       
                        $is_exit = $this->employment_model->check_cin_exist($frm_details['nameofthecompany']);

                        $is_company_exit = $this->employment_model->check_company_exist(str_replace("-", ' ', $frm_details['selected_company_name']));

                        if (!empty($is_exit)) {
                            $nameofthecompany = $is_exit[0]['id'];
                        } else if (!empty($is_company_exit)) {
                            $nameofthecompany = $is_company_exit[0]['id'];
                        } else {
                            $nameofthecompany = $this->employment_model->save_company_details(array('cin_number' => $frm_details['nameofthecompany'], 'coname' => str_replace('-', ' ', $frm_details['selected_company_name'])));
                        }

                        $tat_day = $this->get_client_entity_package_tat_day(array('tbl_clients_id' => $frm_details['clientid'], 'entity' => $frm_details['entity_id'], 'package' => $frm_details['package_id']));

                        $get_holiday1 = $this->get_holiday();

                        $get_holiday = array_map('current', $get_holiday1);

                        $closed_date = getWorkingDays(convert_display_to_db_date($frm_details['iniated_date']), $get_holiday, $tat_day[0]['tat_empver']);

                        $fields = array('has_case_id' => $frm_details['has_case_id'],
                            'empid' => $frm_details['empid'],
                            'nameofthecompany' => $nameofthecompany,
                            'deputed_company' => $frm_details['deputed_company'],
                            'employment_type' => $frm_details['employment_type'],
                            'iniated_date' => convert_display_to_db_date($frm_details['iniated_date']),
                            'locationaddr' => $frm_details['locationaddr'],
                            'citylocality' => $frm_details['citylocality'],
                            'pincode' => $frm_details['pincode'],
                            'state' => $frm_details['state'],
                            'mode_of_veri' => $frm_details['mod_of_veri'],
                            'compant_contact' => $frm_details['compant_contact'],
                            'compant_contact_name' => $frm_details['compant_contact_name'],
                            'compant_contact_designation' => $frm_details['compant_contact_designation'],
                            'compant_contact_email' => $frm_details['compant_contact_email'],
                            'empfrom' => $frm_details['empfrom'],
                            'empto' => $frm_details['empto'],
                            'r_manager_name' => $frm_details['r_manager_name'],
                            'r_manager_no' => $frm_details['r_manager_no'],
                            'r_manager_designation' => $frm_details['r_manager_designation'],
                            'r_manager_email' => $frm_details['r_manager_email'],
                            "due_date" => $closed_date,
                            'build_date' => $frm_details['build_date'],
                            "tat_status" => "IN TAT",
                            'designation' => $frm_details['designation'],
                            'remuneration' => $frm_details['remuneration'],
                            "reasonforleaving" => $frm_details['reasonforleaving'],
                            "uan_no" => $frm_details['uan_no'],
                            "uan_remark" => $frm_details['uan_remark'],
                            'modified_on' => date(DB_DATE_FORMAT),
                            'modified_by' => $this->user_info['id'],
                        );

                        $fields = array_map('strtolower', $fields);

                        $lists = $this->employment_model->select_required_field(array('company_database.id' => $frm_details['nameofthecompany']));

                        $fields['reject_status'] = 1;
                        if (!empty($lists[0])) {
                            if ($lists[0]['previous_emp_code'] == 1) {
                                if ($frm_details['empid'] == "") {
                                    $fields['reject_status'] = 2;
                                }
                            }
                            if ($lists[0]['branch_location'] == 1) {
                                if ($frm_details['citylocality'] == "") {
                                    $fields['reject_status'] = 2;
                                }
                            }
                            if ($lists[0]['experience_letter'] == 1) {
                                if ($_FILES['attchments_reliving']['name'][0] == "") {
                                    $fields['reject_status'] = 2;
                                }
                            }
                            if ($lists[0]['loa'] == 1) {
                                if ($_FILES['attchments_loa']['name'][0] == "") {
                                    $fields['reject_status'] = 2;
                                }
                            }
                        }

                        $result = $this->employment_model->save($fields, array('id' => $frm_details['update_id']));

                        $select_candidate_billed_date = $this->common_model->select_candidate_billed_date('candidates_info', true, array('build_date'), array('id' => $frm_details['candidateid']));

                        $component_name = json_decode($select_candidate_billed_date['build_date'], true);

                        $result_candidate_billed = $this->common_model->update_candidate_billed_date(array('build_date' => $this->components_key_val(array('0' => $component_name['addrver'], '1' => $component_name['courtver'], '2' => $component_name['globdbver'], '3' => $component_name['narcver'], '4' => $component_name['refver'], '5' => $frm_details['build_date'], '6' => $component_name['eduver'], '7' => $component_name['identity'], '8' => $component_name['cbrver'], '9' => $component_name['crimver']))), array('id' => $frm_details['candidateid']));
                    } catch (Exception $e) {
                        log_message('error', 'Form validation Employment::employment_edit_view');
                        log_message('error', $e->getMessage());
                    }
                    $total_rows = count(array_filter($frm_details['supervisor_name']));

                    $x = 0;
                    $supervisor_array = array();

                    while ($x < $total_rows) {
                        if ($frm_details['supervisor_name']) {
                            array_push($supervisor_array,
                                array(
                                    'supervisor_name' => $frm_details['supervisor_name'][$x],
                                    'supervisor_designation' => $frm_details['supervisor_designation'][$x],
                                    'supervisor_contact_details' => $frm_details['supervisor_contact_details'][$x],
                                    'supervisor_email_id' => $frm_details['supervisor_email_id'][$x],
                                    'status' => 1,
                                    'created_by' => $this->user_info['id'],
                                    'created_on' => date(DB_DATE_FORMAT),
                                    'empver_id' => $frm_details['update_id'],
                                ));
                        }
                        $x++;
                    }

                    if (!empty($supervisor_array)) {
                        $this->employment_model->empver_supervisor_details($supervisor_array, array('empver_id' => $frm_details['update_id']));
                    }
                    try {
                        if (empty($error_msgs)) {
                            

                            $file_upload_path = SITE_BASE_PATH . EMPLOYMENT . $frm_details['clientid'];
                    
                            if (!folder_exist($file_upload_path)) {
                                mkdir($file_upload_path, 0777, true);
                            } else if (!is_writable($file_upload_path)) {
                               array_push($error_msgs, 'Problem while uploading');
                            }

                            $config_array = array('file_upload_path' => $file_upload_path, 'file_permission' => 'jpeg|jpg|png|pdf|docx|xls|xlsx', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 10000, 'file_id' => $frm_details['update_id'], 'component_name' => 'empver_id');

                            if ($_FILES['attchments']['name'][0] != '') {
                                $config_array['files_count'] = count($_FILES['attchments']['name']);
                                $config_array['file_data'] = $_FILES['attchments'];
                                $config_array['type'] = 0;
                                $retunr_de = $this->file_upload_library->file_upload_multiple($config_array, true);
                                if (!empty($retunr_de['success'])) {
                                   $this->common_model->common_insert_batch('empverres_files', $retunr_de['success']);
                                }
                            }


                            if ($_FILES['attchments_reliving']['name'][0] != '') {
                                $config_array['files_count'] = count($_FILES['attchments_reliving']['name']);
                                $config_array['file_data'] = $_FILES['attchments_reliving'];
                                $config_array['type'] = 3;
                                $retunr_de = $this->file_upload_library->file_upload_multiple($config_array, true);

                                if (!empty($retunr_de['success'])) {
                                   $this->common_model->common_insert_batch('empverres_files', $retunr_de['success']);
                                }
                            }

                            if ($_FILES['attchments_loa']['name'][0] != '') {
                                $config_array['files_count'] = count($_FILES['attchments_loa']['name']);
                                $config_array['file_data'] = $_FILES['attchments_loa'];
                                $config_array['type'] = 4;
                                $retunr_de = $this->file_upload_library->file_upload_multiple($config_array, true);
                                if (!empty($retunr_de['success'])) {
                                   $this->common_model->common_insert_batch('empverres_files', $retunr_de['success']);
                                }
                            }

                            if ($frm_details['upload_capture_image_employment']) {

                                $copy_attachment_selection = $frm_details['copy_attachment_selection'];

                                $upload_capture_image = explode("||", $frm_details['upload_capture_image_employment']);
                                    
                                foreach ($upload_capture_image as $key => $value) {
                                    $key = $key + 1;
        
                                    $file_name = $frm_details['emp_com_ref'] . '-' .$key. '-' . date(UPLOAD_FILE_DATE_FORMAT) . '.png';
                                    $uploadpath = $file_upload_path . '/' . $file_name;
                                    $base64_to_jpeg = base64_to_jpeg($value, $uploadpath);
                                        if ($base64_to_jpeg) {
                                            log_message('error', 'Inside if condition success');
                                            $this->common_model->save('empverres_files', ['file_name' => $file_name, 'real_filename' => $file_name,'type' => $copy_attachment_selection, 'empver_id' => $frm_details['update_id']]);
                                        }
        
                                }
                            }

                           /* if ($_FILES['attchments_CS']['name'][0] != '') {
                                $config_array['files_count'] = count($_FILES['attchments_CS']['name']);
                                $config_array['file_data'] = $_FILES['attchments_CS'];
                                $config_array['type'] = 2;
                                $retunr_cd = $this->file_upload_multiple($config_array);
                                if (!empty($retunr_cd)) {
                                    $this->common_model->common_insert_batch('empverres_files', $retunr_cd['success']);
                                }
                            }*/
                        }
                    } catch (Exception $e) {
                        log_message('error', 'File upload Employment::employment_edit_view');
                        log_message('error', $e->getMessage());
                    }
                    if ($result) {

                        $user_activity_data = $this->common_model->user_actity_data(array('component' => "Employment",
                            'ref_no' => $frm_details['emp_com_ref'], 'candidate_name' => $frm_details['candsid'], 'created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id'], 'activity_type' => 'Manual', 'action' => 'Edit'));

                        auto_update_tat_status($frm_details['candidateid']);
                        $json_array['status'] = SUCCESS_CODE;

                        $json_array['message'] = 'Employment Record Successfully Inserted';

                        $json_array['redirect'] = ADMIN_SITE_URL . 'employment';
                    } else {
                        $json_array['status'] = ERROR_CODE;

                        $json_array['message'] = 'Something went wrong,please try again';
                    }
                }
                echo_json($json_array);
            } else {
                permission_denied();
            }
        } catch (Exception $e) {
            log_message('error', 'Error on Employment::employment_edit_view');
            log_message('error', $e->getMessage());
        }
    }

    public function update_company_web_status()
    {
        if ($this->input->is_ajax_request()) {

            $this->form_validation->set_rules('comp_table_id', 'Update ID', 'required');

            $this->form_validation->set_rules('justdialwebcheck', 'Check Web Status', 'required');

            $this->form_validation->set_rules('mcaregn', 'Register with MCA', 'required');

            $this->form_validation->set_rules('domainname', 'Domain Name ', 'required');

            if ($this->form_validation->run() == false) {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');
            } else {
                $frm_details = $this->input->post();

                $fields = array(
                    'justdialwebcheck'  => $frm_details['justdialwebcheck'],
                    'mcaregn'           => $frm_details['mcaregn'],
                    'domainname'        => $frm_details['domainname'],
                    'domainpurch'       => $frm_details['domainpurch'],
                );

                $fields = array_map('strtolower', $fields);

                $result = $this->employment_model->save($fields, array('id' => $frm_details['comp_table_id']));

            }
            if ($result) {
                $json_array['status'] = SUCCESS_CODE;

                $json_array['message'] = 'Company Web Status Record Inserted Successfully';

            } else {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = 'Something went wrong,please try again';
            }
            echo_json($json_array);
        } else {
            permission_denied();
        }
    }

    public function employment_reinitiated_date()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {
            try {
                $empverid = $this->input->post('update_id');
                $reinitiated_date = $this->input->post('reinitiated_date');
                $reinitiated_remark = $this->input->post('reinitiated_remark');
                $clientid = $this->input->post('clientid');

                $check = $this->employment_model->select_reinitiated_date(array('id' => $empverid));

                if ($check[0]['emp_re_open_date'] == "0000-00-00" || $check[0]['emp_re_open_date'] == "") {
                    $reinitiated_dates = $reinitiated_date;
                } else {
                    $reinitiated_dates = $check[0]['emp_re_open_date'] . "||" . $reinitiated_date;
                }

                $result = $this->employment_model->save_update_initiated_date(array('emp_re_open_date' => $reinitiated_dates, 'emp_reinitiated_remark' => $reinitiated_remark), array('id' => $empverid));

                $result_empverres = $this->employment_model->save_update_initiated_date_empver(
                    array( 
                        'verfstatus' => 26, 
                        'var_filter_status' => "WIP", 
                        'var_report_status' => "WIP", 
                        'first_qc_approve' => "", 
                        'first_qc_updated_on' => "", 
                        'first_qu_reject_reason' => "", 
                        'first_qu_updated_by' => ""), 
                        array('empverid' => $empverid)
                );

                $error_msgs = array();
                $file_upload_path = SITE_BASE_PATH . EMPLOYMENT . $clientid;
                if (!folder_exist($file_upload_path)) {
                    mkdir($file_upload_path, 0777, true);
                } else if (!is_writable($file_upload_path)) {
                    array_push($error_msgs, 'Problem while uploading');
                }

                $config_array = array('file_upload_path' => $file_upload_path, 'file_permission' => 'jpeg|jpg|png|pdf|docx|xls|xlsx', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 1000, 'file_id' => $empverid,
                    'component_name' => 'empver_id');

                if ($_FILES['attachment_reinitiated']['name'][0] != '') {
                    $config_array['files_count'] = count($_FILES['attachment_reinitiated']['name']);
                    $config_array['file_data'] = $_FILES['attachment_reinitiated'];
                    $config_array['type'] = 2;
                    $retunr_cd = $this->file_upload_multiple($config_array);
                    if (!empty($retunr_cd)) {
                        $this->common_model->common_insert_batch('empverres_files', $retunr_cd['success']);
                    }
                }

                $result_employment_activity_data = $this->employment_model->initiated_date_empver_activity_data(array('candsid' => $this->input->post('candidates_info_id'), 'comp_table_id' => $empverid, 'action' => "Re-Initiated", '  activity_status' => "Re-Initiated", 'remarks' => 'Client requested to re-verify the case [' . $reinitiated_remark . ']', 'created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id']));

                if ($result && $result_empverres) {

                    auto_update_overall_status($this->input->post('candidates_info_id'));

                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'Record Successfully Inserted';
                }
            } catch (Exception $e) {
                log_message('error', 'Form validation Employment::employment_reinitiated_date');
                log_message('error', $e->getMessage());
            }
        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['message'] = 'Something went wrong,please try again';
        }
        echo_json($json_array);
    }

    public function load_supervisor_details($sts, $counter)
    {
        if ($this->input->is_ajax_request()) {

            $empver_id = $this->input->post('details_fields');

            $data['count'] = $counter;
            $data['disabled'] = ($sts == 'dis') ? 'disabled' : '';
            if ($empver_id) {

                $result = $this->employment_model->supervison_details(array('empver_id' => $empver_id));
                $data['total_count'] = count($result);
                $data['superv_details'] = $result;
            }

            echo $this->load->view('admin/supervisor_details_view', $data, true);
        } else {
            echo "<h2>Something went wrong, please try again!</h2>";
        }
    }

    public function edit_company($id, $deputed_company)
    {
        if ($this->input->is_ajax_request() && $id) {
            $data['company'] = $this->get_company_list();
            $data['nameofthecompany'] = $id;
            $data['deputed_company'] = $deputed_company;
            echo $this->load->view('admin/edit_employment', $data, true);
        } else {
            echo "<p>We're sorry, but you do not have access to this page.</p>";
        }
    }

    public function employment_update_company()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {
            $empverid = $this->input->post('tbl_empver_id');
            $nameofthecompany = $this->input->post('nameofthecompany');
            $deputed_company = $this->input->post('deputed_company');

            $result = $this->employment_model->save(array('nameofthecompany' => $nameofthecompany, 'deputed_company' => $deputed_company), array('id' => $empverid));

            if ($result) {

                $json_array['message'] = 'Company Updated Successfully';
                $json_array['status'] = SUCCESS_CODE;
            } else {
                $json_array['message'] = 'Something went wrong, please try again';
                $json_array['status'] = ERROR_CODE;
            }

        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['message'] = 'Something went wrong,please try again';
        }
        echo_json($json_array);
    }

    public function empt_verificarion_result()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {

            $frm_details = $this->input->post();
            try {
                log_message('error', print_r($frm_details, true));
                if (($frm_details['action_val'] != "Select") || ($frm_details['activity_last_id'] != "")) {

                    $verfstatus = status_id_frm_db(array('status_value' => $frm_details['action_val'], 'components_id' => 6));

                    $fields = array(
                        "verfstatus"        => $verfstatus['id'],
                        'var_filter_status' => $verfstatus['filter_status'],
                        'var_report_status' => $verfstatus['report_status'],
                        "closuredate"       => convert_display_to_db_date($frm_details['closuredate']),
                        "clientid"          => $frm_details['clientid'],
                        "candsid"           => $frm_details['candidates_info_id'],
                        "empverid"          => $frm_details['tbl_empver_id'],
                        "res_nameofthecompany" => $frm_details['res_nameofthecompany'],
                        "res_deputed_company" => $frm_details['res_deputed_company'],
                        'res_employment_type' => $frm_details['res_employment_type'],
                        "employed_from" => $frm_details['res_empfrom'],
                        "employed_to" => $frm_details['res_empto'],
                        "emp_designation" => $frm_details['emp_designation'],
                        "res_empid" => $frm_details['res_empid'],
                        "reportingmanager" => $frm_details['res_reportingmanager'],
                        "res_reasonforleaving" => $frm_details['res_reasonforleaving'],
                        "res_remuneration" => $frm_details['res_remuneration'],
                        "info_integrity_disciplinary_issue" => $frm_details['info_integrity_disciplinary_issue'],
                        "info_exitformalities" => $frm_details['info_exitformalities'],
                        "info_eligforrehire" => $frm_details['info_eligforrehire'],
                        "integrity_disciplinary_issue" => $frm_details['integrity_disciplinary_issue'],
                        "exitformalities" => $frm_details['exitformalities'],
                        "eligforrehire" => $frm_details['eligforrehire'],
                        "fmlyowned" => $frm_details['fmlyowned'],
                        'activity_log_id' => $frm_details['activity_last_id'],
                        'nameofthecompany_action' => $frm_details['company_action'],
                        'deputed_company_action' => $frm_details['deputed_company_action'],
                        'employed_from_action' => $frm_details['empfrom_action'],
                        'employed_to_action' => $frm_details['empto_action'],
                        'emp_designation_action' => $frm_details['designation_action'],
                        'empid_action' => $frm_details['empid_action'],
                        'reportingmanager_action' => $frm_details['reportingmanager_action'],
                        'reasonforleaving_action' => $frm_details['reasonforleaving_action'],
                        'remuneration_action' => $frm_details['remuneration_action'],
                        "modeofverification" => $frm_details['modeofverification'],
                        "remarks" => $frm_details['remarks'],
                        'verifiers_role' => $frm_details['verifiers_role'],
                        "verfname" => $frm_details['verfname'],
                        "verfdesgn" => $frm_details['verfdesgn'],
                        "verifiers_contact_no" => $frm_details['verifiers_contact_no'],
                        "verifiers_email_id" => $frm_details['verifiers_email_id'],
                        "justdialwebcheck" => $frm_details['justdialwebcheck'],
                        "mcaregn" => $frm_details['mcaregn'],
                        "domainname" => $frm_details['domainname'],
                        "created_on" => date(DB_DATE_FORMAT),
                        "created_by" => $this->user_info['id'],
                        "modified_on" => date(DB_DATE_FORMAT),
                        "modified_by" => $this->user_info['id'],
                    );

                    $fields = array_map('strtolower', $fields);

                    if (isset($frm_details['remove_file'])) // delete uploaded file
                    {
                        $this->employment_model->delete_uploaded_file($frm_details['remove_file']);
                    }
                    if (isset($frm_details['add_file'])) // delete uploaded file
                    {
                        $this->employment_model->add_uploaded_file($frm_details['add_file']);
                    }

                    if (isset($frm_details['vendor_remove_file'])) // delete uploaded file
                    {
                        $this->addressver_model->vendor_delete_uploaded_file($frm_details['vendor_remove_file']);
                    }
                    if (isset($frm_details['vendor_add_file'])) // delete uploaded file
                    {
                        $this->addressver_model->vendor_add_uploaded_file($frm_details['vendor_add_file']);
                    }

                    $result = $this->employment_model->save_update_empt_ver_result($fields, array('id' => $frm_details['empverres_id']));

                    $result_employment = $this->employment_model->save_update_empt_ver_result_employment($fields);

                    if (!empty($result) && ($frm_details['action_val'] == "Major Discrepancy" || $frm_details['action_val'] == "Minor Discrepancy" || $frm_details['action_val'] == "Clear") && ($frm_details['verifiers_role'] == "hr") && !empty($frm_details['verifiers_email_id'])) {

                        $is_exit = $this->employment_model->check_company_exists(array('verifiers_email_id' => $frm_details['verifiers_email_id'], 'verifiers_contact_no' => $frm_details['verifiers_contact_no']));

                        if (empty($is_exit)) {

                            $fields_company_verified = array("company_database_id" => $frm_details['res_nameofthecompany'],
                                "empver_id" => $frm_details['tbl_empver_id'],
                                "deputed_company" => $frm_details['res_deputed_company'],
                                "verifiers_name" => $frm_details['verfname'],
                                "verifiers_designation" => $frm_details['verfdesgn'],
                                "verifiers_contact_no" => $frm_details['verifiers_contact_no'],
                                'verifiers_email_id' => $frm_details['verifiers_email_id'],
                                "created_on" => date(DB_DATE_FORMAT),
                                "created_by" => $this->user_info['id'],
                                "status" => STATUS_ACTIVE,
                            );

                            $result_company_verified = $this->employment_model->save_employee_company_verified($fields_company_verified);

                        } else {

                            $fields_company_verified = array(
                                "modified_on" => date(DB_DATE_FORMAT),
                                "modified_by" => $this->user_info['id'],
                            );

                            $result_company_verified = $this->employment_model->save_employee_company_verified_update($fields_company_verified, array('verifiers_email_id' => $frm_details['verifiers_email_id'], 'verifiers_contact_no' => $frm_details['verifiers_contact_no']));
                        }
                    }

                    $file_upload_path = SITE_BASE_PATH . EMPLOYMENT . $frm_details['clientid'];

                    $config_array = array(
                        'file_upload_path'  => $file_upload_path, 
                        'file_permission'   => 'jpeg|jpg|png|pdf', 
                        'file_size'         => BULK_UPLOAD_MAX_SIZE_MB * 2000, 
                        'file_id'           => $frm_details['tbl_empver_id'], 
                        'component_name'    => 'empver_id'
                    );

                    if ($_FILES['attchments_ver']['name'][0] != '') {
                        $config_array['files_count'] = count($_FILES['attchments_ver']['name']);
                        $config_array['file_data'] = $_FILES['attchments_ver'];
                        $config_array['type'] = 1;
                        $retunr_de = $this->file_upload_library->file_upload_multiple($config_array, true);
                        
                        if (!empty($retunr_de['success'])) {
                            $this->common_model->common_insert_batch('empverres_files', $retunr_de['success']);
                        }
                    }

                    if (isset($frm_details['upload_capture_image_employment_result'])) {

                        if ($frm_details['upload_capture_image_employment_result']) {

                            $upload_capture_image = explode("||", $frm_details['upload_capture_image_employment_result']);
                                        
                            foreach ($upload_capture_image as $key => $value) {
                                        $key = $key + 1;

                                $file_name = $frm_details['emp_com_ref'] . '-' .$key. '-' . date(UPLOAD_FILE_DATE_FORMAT) . '.png';
                                $uploadpath = $file_upload_path . '/' . $file_name;
                                $base64_to_jpeg = base64_to_jpeg($value, $uploadpath);
                                    if ($base64_to_jpeg) {
                                        log_message('error', 'Inside if condition success');
                                        $this->common_model->save('empverres_files', ['file_name' => $file_name, 'real_filename' => $file_name,'status' => 1,'type' => 1,'empver_id' => $frm_details['tbl_empver_id']]);
                                    }

                            }
                        } 
                    }

                    if (isset($frm_details['sortable_data'])) {
                        $order = (explode('&', $frm_details['sortable_data']));
                        if (is_array($order) && !empty($order[0])) {
                            foreach ($order as $key => $value) {
                                preg_match('/=([a-z0-9]*)$/', $value, $treffer);
                                $update[] = array('serialno' => $key + 1, 'id' => $treffer[1]);
                            }
                            $this->employment_model->upload_file_update($update);
                        }
                    }

                    if ($verfstatus['id'] == 9 || $verfstatus['id'] == 27 || $verfstatus['id'] == 28) {
                        $this->employment_model->update_final_status_of_vendor($frm_details['tbl_empver_id']);
                    }

                    if ($result) {

                        auto_update_overall_status($frm_details['candidates_info_id']);

               //         all_component_closed_qc_status($frm_details['candidates_info_id']);

                        $json_array['message'] = 'Result Updated Successfully';

                        $json_array['redirect'] = ADMIN_SITE_URL . "employment/view_details/" . encrypt($frm_details['tbl_empver_id']);

                        $json_array['status'] = SUCCESS_CODE;
                    } else {
                        $json_array['message'] = 'Something went wrong, please try again';

                        $json_array['status'] = ERROR_CODE;
                    }
                } else {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Something went wrong,please try again';
                }
                
            } catch (Exception $e) {
                log_message('error', 'Form validation Employment::empt_verificarion_result');
                log_message('error', $e->getMessage());
            }
            echo_json($json_array);
        }
    }

    public function empt_verificarion_ver_result()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {
            try  {
                $frm_details = $this->input->post();
                log_message('error', ' add result empt_verificarion_ver_result');
                $fields = array(
                    "closuredate" => convert_display_to_db_date($frm_details['closuredate']),
                    "clientid" => $frm_details['clientid'],
                    "candsid" => $frm_details['candidates_info_id'],
                    "empverid" => $frm_details['tbl_empver_id'],
                    "res_nameofthecompany" => $frm_details['res_nameofthecompany'],
                    "res_deputed_company" => $frm_details['res_deputed_company'],
                    'res_employment_type' => $frm_details['res_employment_type'],
                    "employed_from" => $frm_details['res_empfrom'],
                    "employed_to" => $frm_details['res_empto'],
                    "emp_designation" => $frm_details['emp_designation'],
                    "res_empid" => $frm_details['res_empid'],
                    "reportingmanager" => $frm_details['res_reportingmanager'],
                    "res_reasonforleaving" => $frm_details['res_reasonforleaving'],
                    "res_remuneration" => $frm_details['res_remuneration'],
                    "info_integrity_disciplinary_issue" => $frm_details['info_integrity_disciplinary_issue'],
                    "info_exitformalities" => $frm_details['info_exitformalities'],
                    "info_eligforrehire" => $frm_details['info_eligforrehire'],
                    "integrity_disciplinary_issue" => $frm_details['integrity_disciplinary_issue'],
                    "exitformalities" => $frm_details['exitformalities'],
                    "eligforrehire" => $frm_details['eligforrehire'],
                    "fmlyowned" => $frm_details['fmlyowned'],
                    'nameofthecompany_action' => $frm_details['company_action'],
                    'deputed_company_action' => $frm_details['deputed_company_action'],
                    'employed_from_action' => $frm_details['empfrom_action'],
                    'employed_to_action' => $frm_details['empto_action'],
                    'emp_designation_action' => $frm_details['designation_action'],
                    'empid_action' => $frm_details['empid_action'],
                    'reportingmanager_action' => $frm_details['reportingmanager_action'],
                    'reasonforleaving_action' => $frm_details['reasonforleaving_action'],
                    'remuneration_action' => $frm_details['remuneration_action'],
                    "modeofverification" => $frm_details['modeofverification'],
                    "remarks" => $frm_details['remarks'],
                    'verifiers_role' => $frm_details['verifiers_role'],
                    "verfname" => $frm_details['verfname'],
                    "verfdesgn" => $frm_details['verfdesgn'],
                    "verifiers_contact_no" => $frm_details['verifiers_contact_no'],
                    "verifiers_email_id" => $frm_details['verifiers_email_id'],
                    "justdialwebcheck" => $frm_details['justdialwebcheck'],
                    "mcaregn" => $frm_details['mcaregn'],
                    "domainname" => $frm_details['domainname'],
                    "created_on" => date(DB_DATE_FORMAT),
                    "created_by" => $this->user_info['id'],
                    "modified_on" => date(DB_DATE_FORMAT),
                    "modified_by" => $this->user_info['id'],
                );

                $fields = array_map('strtolower', $fields);

                if (isset($frm_details['remove_file'])) // delete uploaded file
                {
                    $this->employment_model->delete_uploaded_file($frm_details['remove_file']);
                }
                if (isset($frm_details['add_file'])) // delete uploaded file
                {
                    $this->employment_model->add_uploaded_file($frm_details['add_file']);
                }

                if (isset($frm_details['vendor_remove_file'])) // delete uploaded file
                {
                    $this->addressver_model->vendor_delete_uploaded_file($frm_details['vendor_remove_file']);
                }
                if (isset($frm_details['vendor_add_file'])) // delete uploaded file
                {
                    $this->addressver_model->vendor_add_uploaded_file($frm_details['vendor_add_file']);
                }

                $result = $this->employment_model->save_update_empt_ver_result($fields, array('id' => $frm_details['empverres_id']));

                $result_employment = $this->employment_model->save_update_empt_ver_result_employment($fields, array('sr_id' => $frm_details['result_update_id']));

                if (!empty($result) && ($frm_details['action_val'] == "major discrepancy" || $frm_details['action_val'] == "minor discrepancy" || $frm_details['action_val'] == "clear") && ($frm_details['verifiers_role'] == "hr") && !empty($frm_details['verifiers_email_id'])) {

                    $is_exit = $this->employment_model->check_company_exists(array('verifiers_email_id' => $frm_details['verifiers_email_id'], 'verifiers_contact_no' => $frm_details['verifiers_contact_no']));

                    if (empty($is_exit)) {

                        $fields_company_verified = array(
                            "company_database_id"   => $frm_details['res_nameofthecompany'],
                            "empver_id"             => $frm_details['tbl_empver_id'],
                            "deputed_company"       => $frm_details['res_deputed_company'],
                            "verifiers_name"        => $frm_details['verfname'],
                            "verifiers_designation" => $frm_details['verfdesgn'],
                            "verifiers_contact_no"  => $frm_details['verifiers_contact_no'],
                            'verifiers_email_id'    => $frm_details['verifiers_email_id'],
                            "created_on"            => date(DB_DATE_FORMAT),
                            "created_by"            => $this->user_info['id'],
                            "status"                => STATUS_ACTIVE,
                        );

                        $result_company_verified = $this->employment_model->save_employee_company_verified($fields_company_verified);

                    } else {

                        $fields_company_verified = array(
                            "modified_on" => date(DB_DATE_FORMAT),
                            "modified_by" => $this->user_info['id'],
                        );

                        $result_company_verified = $this->employment_model->save_employee_company_verified_update($fields_company_verified, array('verifiers_email_id' => $frm_details['verifiers_email_id'], 'verifiers_contact_no' => $frm_details['verifiers_contact_no']));
                    }
                }

                $file_upload_path = SITE_BASE_PATH . EMPLOYMENT . $frm_details['clientid'];

                $config_array = array(
                    'file_upload_path'  => $file_upload_path, 
                    'file_permission'   => 'jpeg|jpg|png|pdf', 
                    'file_size'         => BULK_UPLOAD_MAX_SIZE_MB * 2000, 
                    'file_id'           => $frm_details['tbl_empver_id'], 
                    'component_name'    => 'empver_id'
                );

                if (isset($_FILES['attchments_ver']['name'][0]) &&  $_FILES['attchments_ver']['name'][0] != '') {
                    $config_array['files_count']    = count($_FILES['attchments_ver']['name']);
                    $config_array['file_data']      = $_FILES['attchments_ver'];
                    $config_array['type']           = 1;
                    $retunr_de = $this->file_upload_library->file_upload_multiple($config_array, true);
                    
                    if (!empty($retunr_de['success'])) {
                        $this->common_model->common_insert_batch('empverres_files', $retunr_de['success']);
                    }
                }

                if (isset($frm_details['upload_capture_image_employment_ver_result'])) {

                    if ($frm_details['upload_capture_image_employment_ver_result']) {

                    $upload_capture_image = explode("||", $frm_details['upload_capture_image_employment_ver_result']);
                                    
                        foreach ($upload_capture_image as $key => $value) {
                            $key = $key + 1;

                            $file_name = $frm_details['emp_com_ref'] . '-' .$key. '-' . date(UPLOAD_FILE_DATE_FORMAT) . '.png';
                            $uploadpath = $file_upload_path . '/' . $file_name;
                            $base64_to_jpeg = base64_to_jpeg($value, $uploadpath);
                                if ($base64_to_jpeg) {
                                        log_message('error', 'Inside if condition success');
                                        $this->common_model->save('empverres_files', ['file_name' => $file_name, 'real_filename' => $file_name,'status' => 1,'type' => 1,'empver_id' => $frm_details['tbl_empver_id']]);
                                }

                        }
                    }
                }

            
                if (isset($frm_details['sortable_data'])) {

                    $order = (explode('&', $frm_details['sortable_data']));
                    if (is_array($order) && !empty($order[0])) {
                        foreach ($order as $key => $value) {
                            preg_match('/=([a-z0-9]*)$/', $value, $treffer);
                            $update[] = array('serialno' => $key + 1, 'id' => $treffer[1]);
                        }
                        $this->employment_model->upload_file_update($update);
                    }
                }

                if ($result) {
                    auto_update_overall_status($frm_details['candidates_info_id']);
             //       all_component_closed_qc_status($frm_details['candidates_info_id']);
                    $json_array['message'] = 'Result Updated Successfully';
                    $json_array['redirect'] = ADMIN_SITE_URL . "employment/view_details/" . encrypt($frm_details['tbl_empver_id']);
                    $json_array['status'] = SUCCESS_CODE;
                } else {
                    $json_array['message'] = 'Something went wrong, please try again';
                    $json_array['status'] = ERROR_CODE;
                }
                echo_json($json_array);
            } catch (Exception $e) {
                log_message('error', 'Address::empt_verificarion_ver_result');
                log_message('error', $e->getMessage());
            }
        }
    }

    public function add_result_model($where_empt_id, $url)
    {
        $emp_details = $this->employment_model->get_employer_result_details(array('empver.id' => $where_empt_id));

        if ($where_empt_id && !empty($emp_details)) {
            $data['company'] = $this->get_company_list();

            $data['status_list_dropdown'] = status_frm_db(array('components_id' => 6));

            $data['empt_details'] = $emp_details[0];

            $data['attachments'] = $this->employment_model->select_file(array('id', 'file_name', 'status'), array('empver_id' => $where_empt_id, 'type' => 1));

            $data['vendor_attachments'] = $this->employment_model->select_file_vendor(array('view_vendor_master_log_file.id', 'view_vendor_master_log_file.file_name', 'view_vendor_master_log_file.real_filename', 'view_vendor_master_log_file.status'), array('view_vendor_master_log_file.component_tbl_id' => 2), $where_empt_id);

            $data['url'] = $url;

            echo $this->load->view('admin/employment_add_result_model_view', $data, true);
        } else {
            echo "<h4>Record Not Found</h4>";
        }
    }

    protected function insuff_trigger_mail($details)
    {
        $get_refno = $this->employment_model->get_cands_details(array('empver.id' => $details['empverid']));

        $mail_status = array("Insufficiency", "Discrepancy");

        if (in_array($details['verfstatus'], $mail_status)) {
            $details['ClientRefNumber'] = $get_refno['ClientRefNumber'];

            $details['caserecddate'] = convert_db_to_display_date($get_refno['caserecddate']);

            $this->load->library('email');

            if ($details['verfstatus'] == 'Discrepancy') {
                $this->email->discrepancy_raised_mail($details);
            } else {
                $this->email->insuff_raised_mail($details);
            }
        }
        return true;
    }

    protected function verifiers_details_save($frm_details)
    {
        $this->load->model('company_database_model');
        $this->company_database_model->verifiers_details_save($frm_details);
    }

    public function employment_logs($empver_id = false)
    {
        if ($empver_id && $this->input->is_ajax_request()) {
            $employment_logs = $this->employment_model->select_empver_logs(array('empver_logs.empver_id' => $empver_id));
            $html_view = '';
            foreach ($employment_logs as $key => $value) {
                $html_view .= '<tbody><tr>';
                $html_view .= "<td>" . convert_db_to_display_date($value['created_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12) . "</td>";
                $html_view .= "<td>" . $value['user_name'] . "</td>";
                $html_view .= "<td>" . $value['empid'] . "</td>";
                $html_view .= "<td>" . $value['locationaddr'] . "</td>";
                $html_view .= '</tr></tbody>';
            }
            $json_array['status'] = SUCCESS_CODE;

            $json_array['message'] = $html_view;
        } else {
            $json_array['status'] = SUCCESS_CODE;

            $json_array['message'] = "<h3>Something went wrong, please try again!</h3>";
        }
        echo_json($json_array);
    }

    public function employment_result_list_idwise($where_id, $url)
    {

        $details = $this->employment_model->select_result_log1(array('empverres_logs.sr_id' => $where_id));

        if ($where_id && !empty($details)) {

            $data['url'] = $url;

            $data['attachments'] = $this->employment_model->select_file(array('id', 'file_name', 'status'), array('empver_id' => $details[0]['empverid'], 'type' => 1));


            $data['vendor_attachments'] = $this->employment_model->select_file_vendor(array('view_vendor_master_log_file.id', 'view_vendor_master_log_file.file_name', 'view_vendor_master_log_file.real_filename', 'view_vendor_master_log_file.status'), array('view_vendor_master_log_file.component_tbl_id' => 2), $where_id);

            $data['vendor_attachments'] = $this->employment_model->select_file_vendor(array('view_vendor_master_log_file.id', 'view_vendor_master_log_file.file_name', 'view_vendor_master_log_file.real_filename', 'view_vendor_master_log_file.status'), array('view_vendor_master_log_file.component_tbl_id' => 2), $details[0]['empverid']);
          
            $data['check_insuff_raise'] = '';
            $data['states'] = $this->get_states();
            $data['empt_details'] = $details[0];
            $data['company'] = $this->get_company_list();
            echo $this->load->view('admin/employment_add_result_model_view_log', $data, true);
        } else {
            echo "<h4>Record Not Found</h4>";
        }

    }

    public function employment_result_list($empver_id = false)
    {
        if ($empver_id && $this->input->is_ajax_request()) {
            $empverres_result = $this->employment_model->select_result_log(array('empverid' => $empver_id, 'activity_log_id !=' => null));

            $html_view = '<thead><tr><th>Created On</th><th>Created By</th><th>Action</th><th>Activit Mode</th><th>Attachment</th><th>Activity Type</th><th>Activity Status</th><th>View</th></tr></thead>';
            if (!empty($empverres_result[0]['sr_id'])) {
                $l = 1;
                foreach ($empverres_result as $key => $value) {

                    $file = "&nbsp;&nbsp;&nbsp;&nbsp;";
                    if ($value['file_names']) {
                        $files = explode(',', $value['file_names']);

                        for ($i = 0; $i < count($files); $i++) {
                            $url = "'" . SITE_URL . EMPLOYMENT . $value['clientid'] . '/';
                            $actual_file = $files[$i] . "'";
                            $myWin = "'" . "myWin" . "'";
                            $attribute = "'" . "height=250,width=480" . "'";

                            $file .= '<a href="javascript:;" onClick="myOpenWindow(' . $url . $actual_file . ',' . $myWin . ',' . $attribute . '); return false"><i class="fa fa-file-photo-o" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;';
                        }
                    }

                    $html_view .= "<tr>";
                    $html_view .= "<td>" . convert_db_to_display_date($value['created_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12) . "</td>";
                    $html_view .= "<td>" . $value['created_by'] . "</td>";
                    $html_view .= "<td>" . $value['activity_action'] . "</td>";
                    $html_view .= "<td>" . $value['activity_mode'] . "</td>";
                    $html_view .= "<td>" . $file . "</td>";
                    $html_view .= "<td>" . $value['activity_type'] . "</td>";
                    $html_view .= "<td>" . $value['activity_status'] . "</td>";
                    if ($l == 1) {
                        $html_view .= '<td><button data-id="showAddResultModel" data-url ="' . ADMIN_SITE_URL . 'Employment/employment_result_list_idwise/' . $value['sr_id'] . '/' . str_replace(" ", "", $value['activity_action']) . '" data-toggle="modal" data-target="#showAddResultModel1"  class="btn-info  showAddResultModel"> View </button></td>';
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

            $json_array['message'] = "<h3>Something went wrong, please try again!</h3>";
        }
        echo_json($json_array);
    }

    public function export_logs($empver_id = null)
    {
        $details = $this->employment_model->select_empver_logs(array('.empver_logs.empver_id' => decrypt($empver_id)));

        if (!empty($details)) {
            $file_name = $details[0]['empver_id'] . '-' . now() . '.csv';

            export_to_csv($details, $file_name);
        } else {
            export_to_csv(array(), 'Empty-File.csv');
        }
    }

    public function insuff_tab_view($emp_id = '')
    {
        if ($this->input->is_ajax_request() && $emp_id) {
            $data['insuff_details'] = $this->employment_model->select_insuff_join(array('empverres_id' => $emp_id));

            echo $this->load->view('admin/employment_insuff_view', $data, true);
        } else {
            echo "<p>Something went wrong, please try again.</p>";
        }
    }

    public function update_insuff_details()
    {
        if ($this->input->is_ajax_request()) {
            $frm_details = $this->input->post();

            $fields = array('insuff_raised_date' => convert_display_to_db_date($frm_details['txt_insuff_raise']),
                'insff_reason' => $frm_details['insff_reason'],
                'insuff_raise_remark' => $frm_details['insuff_raise_remark'],
                'insuff_clear_date' => null,
                'insuff_remarks' => $frm_details['insuff_remarks'],
                'modified_on' => date(DB_DATE_FORMAT),
                'modified_by' => $this->user_info['id'],
                'status' => 4,
            );

            if ($frm_details['insuff_clear_date'] != '') {
                $clear_date = $frm_details['insuff_clear_date'];
                $fields['insuff_clear_date'] = convert_display_to_db_date($frm_details['insuff_clear_date']);
                $fields['insuff_cleared_timestamp'] = date(DB_DATE_FORMAT);
                $fields['insuff_cleared_by'] = $this->user_info['id'];
            } else {
                $clear_date = date(DATE_ONLY);
                $fields['insuff_clear_date'] = convert_display_to_db_date($frm_details['insuff_clear_date']);
            }

            $fields['hold_days'] = getNetWorkDays($frm_details['txt_insuff_raise'], $clear_date);

            $result = $this->employment_model->save_update_insuff($fields, array('id' => $frm_details['id']));

            $emp_details = $this->employment_model->get_employer_details(array('empver.id' =>$frm_details['update_id']));

            $lists = $this->employment_model->select_required_field(array('company_database.id' => $emp_details[0]['nameofthecompany']));

            $fields1['reject_status'] = 1;
                if (!empty($lists[0])) {
                   
                    if ($lists[0]['experience_letter'] == 1) {
                        if ($_FILES['attchments_reliving_clear_edit']['name'][0] == "") {
                            $fields1['reject_status'] = 2;
                        }
                    }
                    if ($lists[0]['loa'] == 1) {
                        if ($_FILES['attchments_loa_clear_edit']['name'][0] == "") {
                            $fields1['reject_status'] = 2;
                        }
                    }
                }


            $update_reject_status = $this->employment_model->save($fields1, array('id' => $frm_details['update_id']));

            $error_msgs = array();
                if (empty($error_msgs)) {
                            

                    $file_upload_path = SITE_BASE_PATH . EMPLOYMENT . $frm_details['clear_clientid'];
                   
                    if (!folder_exist($file_upload_path)) {
                        mkdir($file_upload_path, 0777, true);
                    } else if (!is_writable($file_upload_path)) {
                        array_push($error_msgs, 'Problem while uploading');
                    }

                    $config_array = array('file_upload_path' => $file_upload_path, 'file_permission' => 'jpeg|jpg|png|pdf|docx|xls|xlsx', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 10000, 'file_id' => $frm_details['update_id'], 'component_name' => 'empver_id');

                    if ($_FILES['attchments_clear_edit']['name'][0] != '') {
                        $config_array['files_count'] = count($_FILES['attchments_clear_edit']['name']);
                        $config_array['file_data'] = $_FILES['attchments_clear_edit'];
                        $config_array['type'] = 0;
                        $retunr_de = $this->file_upload_library->file_upload_multiple($config_array, true);
                        if (!empty($retunr_de['success'])) {
                            $this->common_model->common_insert_batch('empverres_files', $retunr_de['success']);
                        }
                    }


                    if ($_FILES['attchments_reliving_clear_edit']['name'][0] != '') {
                        $config_array['files_count'] = count($_FILES['attchments_reliving_clear_edit']['name']);
                        $config_array['file_data'] = $_FILES['attchments_reliving_clear_edit'];
                        $config_array['type'] = 3;
                        $retunr_de = $this->file_upload_library->file_upload_multiple($config_array, true);

                        if (!empty($retunr_de['success'])) {
                            $this->common_model->common_insert_batch('empverres_files', $retunr_de['success']);
                        }
                    }

                    if ($_FILES['attchments_loa_clear_edit']['name'][0] != '') {
                        $config_array['files_count'] = count($_FILES['attchments_loa_clear_edit']['name']);
                        $config_array['file_data'] = $_FILES['attchments_loa_clear_edit'];
                        $config_array['type'] = 4;
                        $retunr_de = $this->file_upload_library->file_upload_multiple($config_array, true);
                        if (!empty($retunr_de['success'])) {
                            $this->common_model->common_insert_batch('empverres_files', $retunr_de['success']);
                        }
                    }

                           /* if ($_FILES['attchments_CS']['name'][0] != '') {
                                $config_array['files_count'] = count($_FILES['attchments_CS']['name']);
                                $config_array['file_data'] = $_FILES['attchments_CS'];
                                $config_array['type'] = 2;
                                $retunr_cd = $this->file_upload_multiple($config_array);
                                if (!empty($retunr_cd)) {
                                    $this->common_model->common_insert_batch('empverres_files', $retunr_cd['success']);
                                }
                            }*/
                }
         

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

    public function insuff_raised()
    {
        $this->load->library('email');
        $json_array = array();
        $json_array['status'] = ERROR_CODE;
        $json_array['message'] = 'Something went wrong,please try again';

        if ($this->input->is_ajax_request()) {

            $empverres_id = $this->input->post('update_id');

            $insff_reason = $this->input->post('insff_reason');

            $insff_date = $this->input->post('txt_insuff_raise');

            $ref_no = $this->input->post('component_ref_no');

            $CandidateName = $this->input->post('CandidateName');

            $check = $this->employment_model->select_insuff(array('empverres_id' => $empverres_id, 'empverres_insuff.status !=' => 3, 'insuff_clear_date is null' => null));

            if (empty($check)) {
                $result = $this->employment_model->save_update_insuff(array('insuff_raised_date' => convert_display_to_db_date($insff_date), 'insuff_raise_remark' => $this->input->post('insuff_raise_remark'), 'status' => STATUS_ACTIVE, 'insff_reason' => $insff_reason, 'created_by' => $this->user_info['id'], 'empverres_id' => $empverres_id, 'auto_stamp' => 1));

                if ($result) {
                        
                        $case_activity = $this->common_model->get_case_activity_status(array('entity' => $this->input->post('entity_id'), 'package' => $this->input->post('package_id'), 'tbl_clients_id' => $this->input->post('clientid')));

                        if(!empty($case_activity)){

                            if($case_activity[0]['case_activity'] == "1"){

                                $employment_details = $this->employment_model->get_employment_details_for_insuff_mail(array('empver.id' => $empverres_id));

                                $users_id  = $this->get_reporting_manager_for_executive($this->input->post('clientid')); 
                                $email = $this->employment_model->get_user_email_id(array('user_profile.id' => $users_id[0]['id']));

                                $spoc_email_id = $this->common_model->select_spoc_mail_id($this->input->post('clientid'));

                                $subject = 'Insufficiency raised in Employment for '.ucwords($this->input->post('CandidateName'));
                                $message = "<p>Team,</p><p>Insufficiency has been raised for the following reason.</p>";


                                $message .= "<table border = '2'  style='border-spacing: 10; border-collapse: collapse;'>
                                <tr>
                                    <td style='text-align:center;background-color: #EDEDED;'>Component</td>
                                    <td style='text-align:center'>Employment</td>
                                </tr>
                               <tr>
                                    <td style='text-align:center;background-color: #EDEDED;'>Candidate Name</td>
                                    <td style='text-align:center'>".ucwords($this->input->post('CandidateName'))."</td>
                                </tr>
                                <tr>
                                    <td style='text-align:center;background-color: #EDEDED;'>Entity</td>
                                    <td style='text-align:center'>".ucwords( $employment_details[0]['entity_name'] )."</td>
                                </tr>
                                <tr>
                                    <td style='text-align:center;background-color: #EDEDED;'>Spoc/Package</td>
                                    <td style='text-align:center'>".ucwords( $employment_details[0]['package_name'])."</td>
                                </tr>
                                <tr>
                                    <td style='text-align:center;background-color: #EDEDED;'>Client Ref No</td>
                                    <td style='text-align:center'>".$employment_details[0]['ClientRefNumber']."</td>
                                </tr>
                                <tr>
                                    <td style='text-align:center;background-color: #EDEDED;'>Mist Ref No</td>
                                    <td style='text-align:center'>".$employment_details[0]['cmp_ref_no']."</td>
                                </tr>
                                <tr>
                                    <td style='text-align:center;background-color: #EDEDED;'>Raised Date</td>
                                    <td style='text-align:center'>".$insff_date."</td>
                                </tr>
                                <tr>
                                    <td style='text-align:center;background-color: #EDEDED;'>Details</td>
                                    <td style='text-align:center'>".ucwords($employment_details[0]['company_name'])."</td>
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

                    $user_activity_data = $this->common_model->user_actity_data(array('component' => "Employment", 'ref_no' => $ref_no, 'candidate_name' => $CandidateName, 'created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id'], 'activity_type' => 'Manual', 'action' => 'Insuff Raised'));
                }

                auto_update_overall_status($this->input->post('candidates_info_id'));

                $json_array['status'] = SUCCESS_CODE;

                $json_array['message'] = 'Record Successfully Inserted';

            } else {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = 'already insuff raised, please close this and raise again';
            }

            $this->session->set_flashdata('active_tab', 'clk_insuff_log');
        }
        echo_json($json_array);
    }

    public function insuff_raised_data()
    {
        if ($this->input->is_ajax_request()) {
            $insuff_data = $this->input->post('insuff_data');

            $result = $this->employment_model->select_insuff(array('id' => $insuff_data));
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

            $result = $this->employment_model->select_insuff(array('id' => $insuff_data));

            if (!empty($result)) {
                $data['insuff_reason_list'] = $this->insuff_reason_list(2);
                $data['insuff_details'] = $result[0];
                echo $this->load->view('admin/insuff_edit_and_clear_view', $data, true);
            }
        }
    }

    public function insuff_clear()
    {
        $json_array = array();

        $frm_details = $this->input->post();

        if ($this->input->is_ajax_request() && $this->permission['access_employment_list_insuff_clear'] == 1) {
            if (convert_display_to_db_date($frm_details['insuff_clear_date']) >= convert_display_to_db_date($frm_details['check_insuff_raise'])) {
                $insuff_date = $frm_details['insuff_clear_date'];
                $hold_days = getNetWorkDays($frm_details['check_insuff_raise'], $frm_details['insuff_clear_date']);

                $date_tat = $this->employment_model->get_date_for_update(array('id' => $frm_details['clear_update_id']));

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

                $result_due_date = $this->employment_model->update_due_date(array('due_date' => $closed_date, 'tat_status' => $new_tat), array('id' => $frm_details['clear_update_id']));

                $fields = array('insuff_clear_date' => convert_display_to_db_date($insuff_date), 'insuff_remarks' => $frm_details['insuff_remarks'], 'insuff_cleared_timestamp' => date(DB_DATE_FORMAT), 'status' => 2, 'insuff_cleared_by' => $this->user_info['id'], 'auto_stamp' => 2, 'hold_days' => $hold_days);

                $result = $this->employment_model->save_update_insuff($fields, array('id' => $frm_details['insuff_clear_id']));

                $get_vendor_log_deatail = $this->employment_model->check_vendor_status_insufficiency(array('view_vendor_master_log.component' => "empver", 'view_vendor_master_log.component_tbl_id' => 2,
                    'view_vendor_master_log.final_status' => 'insufficiency', 'empver.id' => $frm_details['clear_update_id']));

                if (!empty($get_vendor_log_deatail)) {

                    $update_vendor_log_deatail = $this->employment_model->reject_cost_vendor(array('final_status' => 'wip', 'vendor_remark' => '', 'modified_on' => date(DB_DATE_FORMAT)), array('view_vendor_master_log.id' => $get_vendor_log_deatail[0]['id']));

                }
                
                $emp_details = $this->employment_model->get_employer_details(array('empver.id' =>$frm_details['clear_update_id']));

                $lists = $this->employment_model->select_required_field(array('company_database.id' => $emp_details[0]['nameofthecompany']));

                $fields1['reject_status'] = 1;
                    if (!empty($lists[0])) {
                        
                        if ($lists[0]['experience_letter'] == 1) {
                            if ($_FILES['attchments_reliving_clear']['name'][0] == "") {
                                $fields1['reject_status'] = 2;
                            }
                        }
                        if ($lists[0]['loa'] == 1) {
                            if ($_FILES['attchments_loa_clear']['name'][0] == "") {
                                $fields1['reject_status'] = 2;
                            }
                        }
                    }


                $update_reject_status = $this->employment_model->save($fields1, array('id' => $frm_details['clear_update_id']));

                if ($result) {
                    $user_activity_data = $this->common_model->user_actity_data(array('component' => "Employment", 'ref_no' => $frm_details['component_ref_no'], 'candidate_name' => $frm_details['CandidateName'], 'created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id'], 'activity_type' => 'Manual', 'action' => 'Insuff Cleared'));
                }

                $error_msgs =  array();
                 
                      try {
                        if (empty($error_msgs)) {
                            

                            $file_upload_path = SITE_BASE_PATH . EMPLOYMENT . $frm_details['clear_clientid'];
                    
                            if (!folder_exist($file_upload_path)) {
                                mkdir($file_upload_path, 0777, true);
                            } else if (!is_writable($file_upload_path)) {
                               array_push($error_msgs, 'Problem while uploading');
                            }

                            $config_array = array('file_upload_path' => $file_upload_path, 'file_permission' => 'jpeg|jpg|png|pdf', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 10000, 'file_id' => $frm_details['clear_update_id'], 'component_name' => 'empver_id');

                            if ($_FILES['attchments_clear']['name'][0] != '') {
                                $config_array['files_count'] = count($_FILES['attchments_clear']['name']);
                                $config_array['file_data'] = $_FILES['attchments_clear'];
                                $config_array['type'] = 0;
                                $retunr_de = $this->file_upload_library->file_upload_multiple($config_array, true);
                                if (!empty($retunr_de['success'])) {
                                   $this->common_model->common_insert_batch('empverres_files', $retunr_de['success']);
                                }
                            }


                            if ($_FILES['attchments_reliving_clear']['name'][0] != '') {
                                $config_array['files_count'] = count($_FILES['attchments_reliving_clear']['name']);
                                $config_array['file_data'] = $_FILES['attchments_reliving_clear'];
                                $config_array['type'] = 3;
                                $retunr_de = $this->file_upload_library->file_upload_multiple($config_array, true);

                                if (!empty($retunr_de['success'])) {
                                   $this->common_model->common_insert_batch('empverres_files', $retunr_de['success']);
                                }
                            }

                            if ($_FILES['attchments_loa_clear']['name'][0] != '') {
                                $config_array['files_count'] = count($_FILES['attchments_loa_clear']['name']);
                                $config_array['file_data'] = $_FILES['attchments_loa_clear'];
                                $config_array['type'] = 4;
                                $retunr_de = $this->file_upload_library->file_upload_multiple($config_array, true);
                                if (!empty($retunr_de['success'])) {
                                   $this->common_model->common_insert_batch('empverres_files', $retunr_de['success']);
                                }
                            }

                           /* if ($_FILES['attchments_CS']['name'][0] != '') {
                                $config_array['files_count'] = count($_FILES['attchments_CS']['name']);
                                $config_array['file_data'] = $_FILES['attchments_CS'];
                                $config_array['type'] = 2;
                                $retunr_cd = $this->file_upload_multiple($config_array);
                                if (!empty($retunr_cd)) {
                                    $this->common_model->common_insert_batch('empverres_files', $retunr_cd['success']);
                                }
                            }*/
                        }
                    } catch (Exception $e) {
                        log_message('error', 'File upload Employment::Insuff Clear');
                        log_message('error', $e->getMessage());
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
        if ($this->input->is_ajax_request() && $this->permission['access_employment_list_insuff_delete'] == 1) {
            $fields = array('status' => 3, 'modified_on' => date(DB_DATE_FORMAT), 'modified_by' => $this->user_info['id']);

            $result = $this->employment_model->save_update_insuff($fields, array('id' => $insuff_data));

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

    public function insuff_raise_remark()
    {
        if ($this->input->is_ajax_request()) {
            $reslt = $this->common_model->select('raising_insuff_dropdown', true, array("remarks"), array('reason' => $this->input->post('insff_reason')));
            if (!empty($reslt)) {
                $json_array['status'] = SUCCESS_CODE;

                $json_array['message'] = $reslt['remarks'];
            } else {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = '';
            }
            echo_json($json_array);
        }
    }

    public function field_visit($emp_id)
    {
        if ($this->input->is_ajax_request()) {
            $emp_details = $this->employment_model->get_employer_details(array('empver.id' => decrypt($emp_id)));
            $data['empt_details'] = $emp_details[0];

            $data['attachments'] = $this->employment_model->select_file(array('id', 'file_name', 'real_filename', 'type'), array('empver_id' => $emp_details[0]['id'], 'status' => 1));

          
            $data['states'] = $this->get_states();

            $data['company'] = $this->get_company_list();

            echo $this->load->view('admin/employment_field_visit_frm', $data, true);
        } else {
            permission_denied();
        }
    }

    public function add_field_visit()
    {
        $json_array = array();

        $this->load->library('email');

        $this->load->library('employment_field_visit');

        if ($this->input->is_ajax_request()) {

            $frm_details =  $this->input->post(); 
           
            
            $file_name = $frm_details['emp_com_ref']."_Form".".pdf";
           
            $this->load->library('employment_field_visit');

            $this->employment_field_visit->generate_pdf($frm_details); 
            
            if(isset($frm_details['attachment']))
            {
                $attachment = $frm_details['attachment'];
                
                if(is_array($attachment))
                {
                    array_push($attachment,$file_name);
                } 
            }
            else{

                $attachment = array();
                if(is_array($attachment))
                {
                    array_push($attachment,$file_name);
                } 
            }

            $vendor_name = $this->employment_model->vendor_email_id(array('vendors.id' => $frm_details['vendor_id']));


            $email_tmpl_data['subject'] = 'Employment site visit - '.ucwords($vendor_name[0]['vendor_name']).' - New case/s initiated by '.CRMNAME;
            $message = "<p>Team,</p><p> The below case/s have been initiated to you. Kindly have them closed within TAT.</p>";
                                        
            $message .= "<table border = '2'  style='border-spacing: 10; border-collapse: collapse;'>
                <tr>
                    <th style='background-color: #EDEDED;text-align:center'>Sr No</th>
                    <th style='background-color: #EDEDED;text-align:center'>Company Name</th>
                    <th style='background-color: #EDEDED;text-align:center'>Component Ref No</th>
                    <th style='background-color: #EDEDED;text-align:center'>Candidate Name</th>
                    <th style='background-color: #EDEDED;text-align:center'>Address</th>
                    <th style='background-color: #EDEDED;text-align:center'>City</th>
                    <th style='background-color: #EDEDED;text-align:center'>Pincode</th>
                    <th style='background-color: #EDEDED;text-align:center'>State</th>
                </tr>";

                                   
            $message .= '<tr>
                    <td style="text-align:center"> 1 </td>
                    <td style="text-align:center">'.ucwords($frm_details['actual_company_name']). '</td>
                    <td style="text-align:center">'.$frm_details['emp_com_ref'] . '</td>
                    <td style="text-align:center">'.ucwords($frm_details['candsid']) . '</td>
                    <td style="text-align:center">'.$frm_details['locationaddr'] . '</td>
                    <td style="text-align:center">'.$frm_details['citylocality'] . '</td>
                    <td style="text-align:center">'.$frm_details['pincode'] . '</td>
                   <td style="text-align:center">'.ucwords($frm_details['state']) . '</td>
                </tr>';

                                     

                             
            $message .= "</table>";

            $message .= "<br><br><p><b>Note :</b> <I>This is an auto generated email. Request you to write back within 24 hrs with any discrepancy.</I>";

            $to_emails = $this->employment_model->vendor_email_id(array('vendors.id' => $frm_details['vendor_id']));
                             
            $email_tmpl_data['to_emails'] = $to_emails[0]['email_id'];
            $email_tmpl_data['clientid'] = $frm_details['clientid'];
            $email_tmpl_data['message'] = $message;


            $result_mail = $this->email->employment_field_visit_mail_send($attachment,$email_tmpl_data);
            if($result_mail == "Success")
            {

                $update = $this->employment_model->upload_vendor_assign('empver', array('vendor_id' => $frm_details['vendor_id'], 'vendor_assgined_on' => date(DB_DATE_FORMAT)), array('id' => $frm_details['update_id'], 'vendor_id =' => 0));

                 $update1 = $this->employment_model->update_status('empverres', array('verfstatus' => 1), array('empverid' => $frm_details['update_id']));

                if ($update) {
       
                    $field_array = array(
                        'vendor_id' => $frm_details['vendor_id'],
                        'case_id' => $frm_details['update_id'],
                        "status" => 0,
                        "remarks" => '',
                        "created_by" => $this->user_info['id'],
                        "created_on" => date(DB_DATE_FORMAT),
                        "approval_by" => 0,
                        "modified_on" => null,
                        "modified_by" => '',
                        );

                    $inserted = $this->employment_model->save_employment('employment_vendor_log', $field_array);

                    $update_vendor = $this->employment_model->upload_vendor_assign('employment_vendor_log', array('status' => 1, 'approval_by' => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT)), array('id' => $inserted));
        
                    if ($update_vendor) {

                        $field_array = array('component' => 'empver',
                            'component_tbl_id' => '2',
                            'case_id' => $inserted,
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

                $empver_id = $this->input->post('update_id');
                $field_visit_additional_remark = $this->input->post('field_visit_additional_remark');

                $result = $this->employment_model->save(array('field_visit_status' => 'WIP', 'field_visit_additional_remark' => $field_visit_additional_remark), array('id' => $empver_id));

                if ($result) {

            

                    $result_employment_activity_data = $this->employment_model->initiated_date_empver_activity_data(array('comp_table_id' => $empver_id, 'action' => "Field Visit", 'activity_status' => "Field Visit", 'remarks' => 'Field Visit requested' . $this->user_info['user_name'], 'created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id']));



                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'Record Updated Inserted';

                } else {
                    $json_array['status'] = ERROR_CODE;
                    $json_array['message'] = 'Something went wrong,please try again';

                }

            } 
            else {
                 $json_array['status'] = ERROR_CODE;
                 $json_array['message'] = 'Email Not Send !!';
            }

        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['message'] = 'Something went wrong,please try again';
        }
        echo_json($json_array);
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

            $result = $this->employment_model->save_first_qc_result(array("first_qc_approve" => "First QC Approve", "first_qc_updated_on" => $accepted_on, "first_qu_updated_by" => $this->user_info['id']), array("empverid" => $comp_id, "id" => $frist_qc_id, "candsid" => $cands_id));

            if ($result) {

                $user_activity_data = $this->common_model->user_actity_data(array('component' => "Employment",
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

            $result = $this->employment_model->save_first_qc_result(array("first_qu_reject_reason" => $rejected_reason, "first_qc_approve" => '', "first_qu_updated_by" => $this->user_info['id'], "first_qc_updated_on" => $rejected_on, "verfstatus" => 13, "var_filter_status" => "WIP", "var_report_status" => "WIP"), array("empverid" => $comp_id, "id" => $frist_qc_id, "candsid" => $cands_id));

            if ($result) {
                $user_activity_data = $this->common_model->user_actity_data(array('component' => "Employment",
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

    public function componet_html_form($emp_id)
    {
        if ($this->input->is_ajax_request() && $emp_id) {
            $emp_details = $this->employment_model->get_employer_result_details(array('empver.id' => decrypt($emp_id)));

            if (!empty($emp_details)) {
                $data['company'] = $this->get_company_list();

                $data['status_list_dropdown'] = status_frm_db(array('components_id' => 6));

                $data['empt_details'] = $emp_details[0];

                echo $this->load->view('admin/employment_add_result_model_view_first_qc', $data, true);

            } else {
                echo "<p>Record not found,please try again.</p>";
            }

        } else {
            echo "<p>Something went wrong, please try again.</p>";
        }
    }

    public function frm_first_qc()
    {
        if ($this->input->is_ajax_request() && $this->permission['access_first_QC_approve'] == true) {
            $this->form_validation->set_rules('frist_qc_id', 'ID', 'required');

            if ($this->form_validation->run() == false) {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');
            } else {
                $frm_detail = $this->input->post();

                $comp_id = decrypt($frm_detail['frist_qc_id']);

                if ($frm_detail['first_qc_status'] == 'reject') {
                    $fields = array('first_qc_updated_on' => date(DB_DATE_FORMAT), 'first_qc_approve' => 'First QC Reject', 'first_qu_reject_reason' => $frm_detail['reject_reason'], 'verfstatus' => 13, 'qc_status' => 'First QC Reject', 'first_qu_updated_by' => $this->user_info['id']);

                    $remarks = 'Check rejected by ' . $this->user_info['user_name'] . ' stating ' . $frm_detail['reject_reason'];

                    $this->db->insert('activity_log', array('candsid' => '', 'ClientRefNumber' => '', 'comp_table_id' => $comp_id, 'activity_mode' => '', 'activity_status' => 'Rejected', 'activity_type' => 'First QC', 'action' => 'Rejected', 'next_follow_up_date' => null, 'remarks' => $remarks, 'created_on ' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id'], 'component_type' => 6, 'is_auto_filled' => 1));

                } else {
                    $fields = array('first_qc_updated_on' => date(DB_DATE_FORMAT), 'first_qc_approve' => 'First QC Approved', 'qc_status' => 'First QC Approved', 'first_qu_updated_by' => $this->user_info['id']);

                    $remarks = 'Check approved by ' . $this->user_info['user_name'] . ' stating ';

                    $this->db->insert('activity_log', array('candsid' => '', 'ClientRefNumber' => '', 'comp_table_id' => $comp_id, 'activity_mode' => '', 'activity_status' => 'Approved', 'activity_type' => 'First QC', 'action' => 'Approved', 'next_follow_up_date' => null, 'remarks' => $remarks, 'created_on ' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id'], 'component_type' => 6, 'is_auto_filled' => 1));
                }

                $result = $this->employment_model->save_update_empt_ver_result($fields, array('id' => $comp_id));

                if ($result) {
                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = "Record Successfully Updated";
                } else {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Something went wrong,please try again';
                }
            }

        } else {
            $json_array['status'] = ERROR_CODE;

            $json_array['message'] = 'Access Denied, You don’t have permission to access this page';
        }
        echo_json($json_array);
    }

    public function bulk_upload_employment()
    {

        if ($this->input->is_ajax_request()) {
            $file_upload_path = SITE_BASE_PATH . EMPLOYMENT;

            if (!folder_exist($file_upload_path)) {
                mkdir($file_upload_path, 0777);
            } else if (!is_writable($file_upload_path)) {
                $message = 'Problem while uploading, folder permission';
            }

            $uplaod_details = array('file_upload_path' => $file_upload_path, 'file_data' => $_FILES['cands_bulk_sheet'], 'file_permission' => 'csv|xls|xlsx', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 100);
            $upload_result = $this->file_uplod($uplaod_details);
            // $record = $supervisor =  array();
            $record = array();

            if (!empty($upload_result) && $upload_result['status'] == true) {

                $this->load->model('candidates_model');

                $raw_filename = $_FILES['cands_bulk_sheet']['tmp_name'];

                $headerLine = 0;

                // $supervisot_last_id = $last_id['id'];

                $file = fopen($raw_filename, "r");

                $this->load->library('excel_reader', array('file_name' => $file_upload_path . '/' . $upload_result['file_name']));

                $excel_handler = $this->excel_reader->file_handler;

                $excel_data = $excel_handler->rows();
                if (!empty($excel_data)) {

                    unset($excel_data[0]);

                    $excel_data = array_map("unserialize", array_unique(array_map("serialize", $excel_data)));

                    foreach ($excel_data as $value) {

                        if (count($value) < 31) {
                            continue;
                        }

                        $check_record_exits = $this->candidates_model->select(true, array('id', 'clientid', 'entity', 'package'), array('cmp_ref_no' => strtolower($value[0])));

                        $company_details = array('coname' => strtolower($value[2]),
                            'address' => '',
                            'city' => '',
                            'state' => '',
                            'pincode' => '',
                            'created_by' => $this->user_info['id'],
                            'created_on' => date(DB_DATE_FORMAT),
                        );

                        $nameofthecompany_id = $this->employment_model->check_company_exits($company_details);

                        $nameofthecompany = $this->employment_model->check_companyname_exits(array('id' => $nameofthecompany_id));

                        if (!empty($check_record_exits) && $value[0] != "" && $nameofthecompany_id != "") {

                            $users_id  = $this->get_reporting_manager_for_executive($check_record_exits['clientid']); 

                            $user_id =  $users_id[0]['id'];

                            $tat_day = $this->get_client_entity_package_tat_day(array('tbl_clients_id' => $check_record_exits['clientid'], 'entity' => $check_record_exits['entity'], 'package' => $check_record_exits['package']));

                            $get_holiday1 = $this->get_holiday();

                            $get_holiday = array_map('current', $get_holiday1);

                            $closed_date = getWorkingDays(get_date_from_timestamp($value[1]), $get_holiday, $tat_day[0]['tat_empver']);

                            $mode_of_verification = $this->employment_model->get_mode_of_verification(array('entity' => $check_record_exits['entity'], 'package' => $check_record_exits['package'], 'tbl_clients_id' => $check_record_exits['clientid']));

                            if (!empty($mode_of_verification)) {
                                $mode_of_verification_value = json_decode($mode_of_verification[0]['mode_of_verification']);

                                $mode_of_veri = $mode_of_verification_value->empver;
                            } else {

                                $mode_of_veri = "";
                            }

                            $deputed_company = ($value[3] == "") ? $nameofthecompany : $value[3];

                            $field_array = array('clientid' => $check_record_exits['clientid'],
                                'candsid' => $check_record_exits['id'],
                                'emp_com_ref' => '',
                                'nameofthecompany' => $nameofthecompany_id,
                                'deputed_company' => $deputed_company,
                                'empid' => $value[4],
                                'employment_type' => $value[5],
                                'empfrom' => $value[6],
                                'empto' => $value[7],
                                'designation' => $value[8],
                                'remuneration' => $value[9],
                                "reasonforleaving" => $value[10],
                                'compant_contact' => $value[14],
                                'compant_contact_name' => $value[11],
                                'compant_contact_designation' => $value[12],
                                'compant_contact_email' => $value[13],
                                'locationaddr' => $value[15],
                                'citylocality' => $value[16],
                                'state' => $value[17],
                                'pincode' => $value[18],
                                'r_manager_name' => $value[27],
                                'r_manager_no' => $value[28],
                                'r_manager_designation' => $value[29],
                                'r_manager_email' => $value[30],
                                'iniated_date' => get_date_from_timestamp($value[1]),
                                "emp_re_open_date" => '',
                                'created_by' => $this->user_info['id'],
                                'created_on' => date(DB_DATE_FORMAT),
                                'modified_on' => date(DB_DATE_FORMAT),
                                'modified_by' => $this->user_info['id'],
                                'has_case_id' => $user_id,
                                'has_assigned_on' => date(DB_DATE_FORMAT),
                                'is_bulk_uploaded' => 1,
                                "mode_of_veri" => $mode_of_veri,
                                "due_date" => $closed_date,
                                "tat_status" => "IN TAT",
                            );

                            $record = array_map('strtolower', array_map('trim', $field_array));

                            $insert_id = $this->employment_model->save($record);

                            $emp_com_ref = $this->emp_com_ref($insert_id);

                            auto_update_overall_status($check_record_exits['id']);
                            auto_update_tat_status($check_record_exits['id']);

                            if (!empty($insert_id)) {
                                if ($value[19] != "" || $value[20] != "" || $value[21] != "") {
                                    $supervisor1 = array('empver_id' => $insert_id, 'supervisor_name' => strtolower($value[19]), 'supervisor_contact_details' => $value[20], 'supervisor_designation' => strtolower($value[21]), 'supervisor_email_id' => $value[22], 'created_by' => $this->user_info['id'], 'status' => 1);

                                    $this->employment_model->empver_supervisor_save($supervisor1);
                                }

                                if ($value[23] != "" || $value[24] != "" || $value[25] != "") {
                                    $supervisor2 = array('empver_id' => $insert_id, 'supervisor_name' => strtolower($value[23]), 'supervisor_contact_details' => $value[24], 'supervisor_designation' => strtolower($value[25]), 'supervisor_email_id' => $value[26], 'created_by' => $this->user_info['id'], 'status' => 1);

                                    $this->employment_model->empver_supervisor_save($supervisor2);
                                }
                            }

                            $data['success'] = $emp_com_ref . " This Component Code Records Created Successfully";
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

    protected function initiation_main_view($employment_id)
    {
        $details = $this->employment_model->emp_ver_details_for_email(array('empver.id' => $employment_id));
        $reportingmanager_user = $this->employment_model->get_reporting_manager_id();

        $reportingmanager_id = $reportingmanager_user[0]['reporting_manager'];
        $reportingmanager = $this->employment_model->get_reporting_manager_email_id($reportingmanager_id);
        $view_data['reporting_manager_email'] = $reportingmanager[0];
        $view_data['user_profile_info'] = $reportingmanager_user[0];
        $view_data['email_info'] = $details[0];

        return $this->load->view(EMAIL_VIEW_FOLDER_NAME . 'employment_template', $view_data, true);
    }

    public function emp_email_tem_view($id)
    {

        if ($this->input->is_ajax_request()) {
            $check = $this->employment_model->select(true, array('mail_sent_status', 'mail_sent_addrs'), array('id' => $id));

            if (!empty($check)) {
                if ($check['mail_sent_status'] == '1') {
                    echo '<div class="col-md-12 col-sm-12 col-xs-12 form-group"><p style="color: #ff0000">Email already sent to ' . $check['mail_sent_addrs'] . ', do you want to resend?</p></div>';
                }
            }

            echo $this->initiation_main_view($id);
        }
    }

    public function emp_send_mail()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {


            $employment_id = $this->input->post('emp_user_id');

            $to_emails = $this->input->post('to_email');

            $from_email = $this->input->post('from');

            $cc_email = $this->input->post('cc_email');

            $bcc_email = $this->input->post('bcc_email');

            $subject = $this->input->post('subject');

            $attchment = $this->input->post('attachment');

            $client_disclosure = $this->input->post('client_disclosure');


            $this->load->library('email');

            $details = $this->employment_model->emp_ver_details_for_email(array('empver.id' => $employment_id));
            $reportingmanager_user = $this->employment_model->get_reporting_manager_id();
            $email_tmpl_data['user_profile_info'] = $reportingmanager_user[0];

            $email_tmpl_data['to_emails'] = $to_emails;
            $email_tmpl_data['cc_emails'] = $cc_email;
            $email_tmpl_data['bcc_emails'] = $bcc_email;
            $email_tmpl_data['subject'] = $subject;
            $email_tmpl_data['attchment'] = $attchment;
            $email_tmpl_data['client_disclosure'] = $client_disclosure;
            $email_tmpl_data['from_email'] = ($from_email != "") ? $from_email : FROMEMAIL;

            $email_tmpl_data['detail_info'] = $details[0];
         
            $result = $this->email->admin_send_employment_mail1($email_tmpl_data);

            if ($result) {

                if (($details[0]['verfstatus'] == "14") || ($details[0]['verfstatus'] == "11") || ($details[0]['verfstatus'] == "26")) {
                    $details_update = $this->employment_model->emp_verfstatus_update(array('empverres.verfstatus' => "1", 'empverres.var_filter_status' => "WIP", 'empverres.var_report_status' => "WIP"), array('empverres.empverid' => $employment_id));
                }

                $field = array('candsid' => $details[0]['candsid'],
                    'ClientRefNumber' => $details[0]['ClientRefNumber'],
                    'comp_table_id' => $employment_id,
                    'activity_status' => "WIP",
                    'activity_type' => "Email",
                    'action' => "Initiation Mail",
                    'remarks' => "Initiation Mail Send to" . $to_emails,
                    'created_on' => date(DB_DATE_FORMAT),
                    'created_by' => $this->user_info['id'],
                    'is_auto_filled' => 1,

                );

                $result = $this->employment_model->save_mail_activity_data($field);

                $this->employment_model->emp_mail_details(array('created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id'], 'from_email_id' => $from_email,'to_mail_id' => $to_emails, 'cc_mail_id' => $cc_email, 'empver_id' => $employment_id, 'type' => "Initiation Mail"));

                $user_activity_data = $this->common_model->user_actity_data(array('component' => "Employment", 'ref_no' => $details[0]['cmp_ref_no'], 'candidate_name' => $details[0]['CandidateName'], 'created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id'], 'activity_type' => 'Manual', 'action' => 'Initiation Email'));

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

    public function emp_send_mail_auto($employment_id,$from_email,$to_emails,$cc_emails,$bcc_emails,$client_disclosure,$employment_selection_attachment)
    {
        $json_array = array();
 
        $bcc_email = $bcc_emails.",".$from_email.",".FROMEMAIL;
        $attchment = $employment_selection_attachment;
    
        $this->load->library('email');

        $details = $this->employment_model->emp_ver_details_for_email(array('empver.id' => $employment_id));

        $reportingmanager_user = $this->employment_model->get_reporting_manager_id();
        $email_tmpl_data['user_profile_info'] = $reportingmanager_user[0];

        $email_tmpl_data['to_emails'] = $to_emails;
        $email_tmpl_data['cc_emails'] = $cc_emails;
        $email_tmpl_data['bcc_emails'] = $bcc_email;
        $subject  = "Employment verification of ".ucwords($details[0]['CandidateName'])." - ".$details[0]['emp_com_ref'];
        $email_tmpl_data['subject'] = $subject;
        $email_tmpl_data['attchment'] = $attchment;
        $email_tmpl_data['client_disclosure'] = $client_disclosure;

        $email_tmpl_data['from_email'] = ($from_email != "") ? $from_email : FROMEMAIL;

        $email_tmpl_data['detail_info'] = $details[0];

        $result = $this->email->admin_send_employment_mail1($email_tmpl_data);

        if ($result) {

            if (($details[0]['verfstatus'] == "14") || ($details[0]['verfstatus'] == "11") || ($details[0]['verfstatus'] == "26")) {
                $details_update = $this->employment_model->emp_verfstatus_update(array('empverres.verfstatus' => "1", 'empverres.var_filter_status' => "WIP", 'empverres.var_report_status' => "WIP"), array('empverres.empverid' => $employment_id));
            }
            $field = array(
                'candsid'           => $details[0]['candsid'],
                'ClientRefNumber'   => $details[0]['ClientRefNumber'],
                'comp_table_id'     => $employment_id,
                'activity_status'   => "WIP",
                'activity_type'     => "Email",
                'action'            => "Initiation Mail",
                'remarks'           => "Initiation Mail Send to" . $to_emails,
                'created_on'        => date(DB_DATE_FORMAT),
                'created_by'        => 1,
                'is_auto_filled'    => 1
            );
            $result = $this->employment_model->save_mail_activity_data($field);


            $this->employment_model->emp_mail_details(array('created_on' => date(DB_DATE_FORMAT), 'created_by' => 1,'from_email_id' => $from_email, 'to_mail_id' => $to_emails, 'cc_mail_id' => $cc_emails, 'empver_id' => $employment_id, 'type' => "Initiation Mail"));

            $user_activity_data = $this->common_model->user_actity_data(array('component' => "Employment", 'ref_no' => $details[0]['cmp_ref_no'], 'candidate_name' => $details[0]['CandidateName'], 'created_on' => date(DB_DATE_FORMAT), 'created_by' => 1, 'activity_type' => 'Auto', 'action' => 'Initiation Email'));
            
               $json_array['message'] = 'Email Send Successfully';

                $json_array['status'] = SUCCESS_CODE;
            } else {
                $json_array['message'] = 'Email not sent, please try again';

                $json_array['status'] = ERROR_CODE;
            }

        echo_json($json_array);
    }

    public function follw_up($employment_id)
    {

        $details = $this->employment_model->emp_ver_details_for_email(array('empver.id' => $employment_id));

          
        $initiation_mail_details = $this->employment_model->emp_initial_mail_details(array('empver_id' => $employment_id)); 
       
        $reportingmanager_user = $this->employment_model->get_reporting_manager_id();

        $reportingmanager_id = $reportingmanager_user[0]['reporting_manager'];
        $reportingmanager = $this->employment_model->get_reporting_manager_email_id($reportingmanager_id);
        $view_data['reporting_manager_email'] = $reportingmanager[0];
        $view_data['user_profile_info'] = $reportingmanager_user[0];
        $view_data['email_info'] = $details[0];
        $view_data['initiation_mail_detail'] = $initiation_mail_details;

       // $initiation_mail_data = $this->employment_model->select_table_value('user_profile','user_profile.*',array('user_profile.id' => $initiation_mail_details[0]['created_by']));

       // $view_data['initiation_mail_data'] = $initiation_mail_data[0];

        echo $this->load->view(EMAIL_VIEW_FOLDER_NAME . 'employment_follow_up_template', $view_data, true);
    }


    public function emp_send_follow_mail()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {


            $employment_id = $this->input->post('emp_user_id');

            $to_emails = $this->input->post('to_email');

            $from_email = $this->input->post('from');

            $cc_email = $this->input->post('cc_email');

            $bcc_email = $this->input->post('bcc_email');

            $subject = $this->input->post('subject');

            //$attchment = $this->input->post('attachment');

           // $client_disclosure = $this->input->post('client_disclosure');
   

            $this->load->library('email');

            $details = $this->employment_model->emp_ver_details_for_email(array('empver.id' => $employment_id));
            $reportingmanager_user = $this->employment_model->get_reporting_manager_id();
            $email_tmpl_data['user_profile_info'] = $reportingmanager_user[0];
            $initiation_mail_details = $this->employment_model->emp_initial_mail_details(array('empver_id' => $employment_id)); 

           // $email_tmpl_data['initiation_mail_details'] = $initiation_mail_details[0];

           // $initiation_mail_data = $this->employment_model->select_table_value('user_profile','user_profile.*',array('user_profile.id' => $initiation_mail_details[0]['created_by']));

           $email_tmpl_data['initiation_mail_detail'] = $initiation_mail_details;

            $email_tmpl_data['to_emails'] = $to_emails;
            $email_tmpl_data['cc_emails'] = $cc_email;
            $email_tmpl_data['bcc_emails'] = $bcc_email;
            $email_tmpl_data['subject'] = $subject;
            //$email_tmpl_data['attchment'] = $attchment;
           // $email_tmpl_data['client_disclosure'] = $client_disclosure;
            $email_tmpl_data['from_email'] = ($from_email != "") ? $from_email : FROMEMAIL;

            $email_tmpl_data['detail_info'] = $details[0];
            $email_tmpl_data['user_profile_info'] = $reportingmanager_user[0];

            $result = $this->email->admin_send_follow_up_mail($email_tmpl_data);

            if ($result) {

                $field = array('candsid' => $details[0]['candsid'],
                    'ClientRefNumber' => $details[0]['ClientRefNumber'],
                    'comp_table_id' => $employment_id,
                    'activity_status' => "WIP",
                    'activity_type' => "Email",
                    'action' => "Follow Up Mail",
                    'remarks' => "Follow Up Mail Send to " . $to_emails,
                    'created_on' => date(DB_DATE_FORMAT),
                    'created_by' => $this->user_info['id'],
                    'is_auto_filled' => 1,

                );

                $result = $this->employment_model->save_mail_activity_data($field);

                $this->employment_model->emp_mail_details(array('created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id'], 'from_email_id' => $from_email,'to_mail_id' => $to_emails, 'cc_mail_id' => $cc_email, 'empver_id' => $employment_id, 'type' => "Follow Mail"));

                $user_activity_data = $this->common_model->user_actity_data(array('component' => "Employment", 'ref_no' => $details[0]['cmp_ref_no'], 'candidate_name' => $details[0]['CandidateName'], 'created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id'], 'activity_type' => 'Manual', 'action' => 'Follow Up Email'));

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


    public function assign_to_executive()
    {
        $json_array = array();
        if ($this->input->is_ajax_request() && ($this->permission['access_employment_list_re_assign'] == '1')) {
            $frm_details = $this->input->post();

            if ($frm_details['users_list'] != 0 && $frm_details['cases_id'] != "") {
                $return = $this->common_model->update_in('empver', array('has_case_id' => $frm_details['users_list'], 'has_assigned_on' => date(DB_DATE_FORMAT)), array('where_id' => $frm_details['cases_id']));
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

    public function employement_view_cases()
    {
        if ($this->input->is_ajax_request()) {
            $result = array();

            $cases = $this->employment_model->get_assigned_cases();

            $i = 1;

            foreach ($cases as $key => $value) {
                $href = "<a class='my_class' href='" . ADMIN_SITE_URL . "employment/view_details/" . $value['id'] . "' title='Edit'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a>";

                $new_array = array($i, $value['id'], $value['ClientRefNumber'], $value['cmp_ref_no'], $value['CandidateName'], $value['vender_employment_name'], $value['custom1'], convert_db_to_display_date($value['has_assigned_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12), $href);

                $result['data'][] = $new_array;

                $i++;
            }
            echo json_encode($result);
        }
    }

    public function employement_view_cases_member()
    {
        if ($this->input->is_ajax_request()) {
            $result = array();

            $cases = $this->employment_model->get_assigned_cases(array('empver.has_case_id' => $this->user_info['id']));

            $i = 1;

            foreach ($cases as $key => $value) {
                $href = "<a class='my_class' href='" . ADMIN_SITE_URL . "employment/view_details_for_member/" . $value['id'] . "' title='Edit'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a>";

                $new_array = array($i, $value['ClientRefNumber'], $value['cmp_ref_no'], $value['CandidateName'], $value['vender_employment_name'], convert_db_to_display_date($value['has_assigned_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12), $href);

                $result['data'][] = $new_array;

                $i++;
            }
            echo json_encode($result);
        }
    }

    public function assigned_cases()
    {
        if ($this->user_info['groupID'] !== "16") {
            $data['header_title'] = "New Cases | Employment ";

            $data['assigned_user_id'] = $this->get_members_by_group(array('groupID' => 16));

            $this->load->view('admin/header', $data);

            $this->load->view('admin/employment_list_new_cases');

            $this->load->view('admin/footer');
        } else {
            $data['header_title'] = "New Cases | Employment ";

            $this->load->view('admin/header', $data);

            $this->load->view('admin/employment_list_new_cases_for_member');

            $this->load->view('admin/footer');
        }
    }

    public function view_details_for_member($emppoyment_id = '')
    {
        if (!empty($emppoyment_id)) {
            $emp_details = $this->employment_model->get_all_emp_by_client(array('empver.id' => $emppoyment_id, 'empver.has_case_id' => $this->user_info['id']));

            if (!empty($emp_details)) {

                $data['header_title'] = 'Edit Employment Verification';

                $data['empt_details'] = $emp_details[0];

                $data['clients'] = $this->get_clients(array('clients.empver' => 1, 'status' => 1));

                $data['gruop_list'] = $this->gruop_list();

                $data['company'] = $this->get_company_list();

                $data['vendor_details'] = $this->get_vendor_detaild();

                $data['assigned_user_id'] = $this->get_members_by_group(array('groupID' => 16));

                $data['supervison_details'] = $this->employment_model->supervison_details(array('empver_id' => $emppoyment_id));

                $data['empverres_result_details'] = $this->employment_model->get_empverres_result(array('empverres.empverid' => $emppoyment_id));

                $this->load->view('admin/header', $data);

                $this->load->view('admin/employment_edit');

                $this->load->view('admin/footer');
            } else {
                show_404();
            }
        } else {
            $this->index();
        }
    }

    public function update_emp_ver()
    {
        $frm_details = $this->input->post();

        $file_array = array();
        $json_array = array();
        $error_msgs = array();

        $id = $frm_details['id'];

        if ($this->input->post()) {
            $this->form_validation->set_rules('assignedto', 'Assigned To', 'required');

            $this->form_validation->set_rules('has_case_id', 'Assigned To Executive', 'required');

            $this->form_validation->set_rules('clientid', 'Client', 'required');

            $this->form_validation->set_rules('candsid', 'Candidate', 'required');

            $this->form_validation->set_rules('empid', 'Employee ID/Code ', 'required');

            $this->form_validation->set_rules('nameofthecompany', 'Company', 'required');

            $this->form_validation->set_rules('citylocality', 'City', 'required');

            $this->form_validation->set_rules('empfrom', 'Employed From', 'required');

            $this->form_validation->set_rules('empto', 'Employed To', 'required');

            $this->form_validation->set_rules('designation', 'Designation', 'required');

            $this->form_validation->set_rules('remuneration', 'Remuneration', 'required');

            if ($this->form_validation->run() == false) {
                $this->view_details($id);
            } else {
                $vender_employment_name = ($frm_details['vender_employment_name'] != 0) ? $frm_details['vender_employment_name'] : '';

                $fields = array('assignedto' => $frm_details['assignedto'],
                    'has_case_id' => $frm_details['has_case_id'],
                    'clientid' => $frm_details['clientid'],
                    'candsid' => $frm_details['candsid'],
                    'empid' => $frm_details['empid'],
                    'nameofthecompany' => $frm_details['nameofthecompany'],
                    'locationaddr' => $frm_details['locationaddr'],
                    'employercontactno' => $frm_details['employercontactno'],
                    'citylocality' => $frm_details['citylocality'],
                    'empfrom' => convert_display_to_db_date($frm_details['empfrom']),
                    'empto' => convert_display_to_db_date($frm_details['empto']),
                    'designation' => $frm_details['designation'],
                    'remuneration' => $frm_details['remuneration'],
                    'reiniated_date' => convert_display_to_db_date($frm_details['reiniated_date']),
                    "reportingmanager" => $frm_details['reportingmanager'],
                    "reasonforleaving" => $frm_details['reasonforleaving'],
                    "vender_employment_name" => $vender_employment_name,
                    'lastupdatedby' => $this->user_info['id'],
                    'updated' => date(DB_DATE_FORMAT),
                );

                // if(isset($frm_details['remove_file'])) // delete uploaded file
                // {
                //     $this->employment_model->delete_uploaded_file($frm_details['remove_file']);
                // }

                $files_count = count($_FILES['attchments']['name']);

                if ($_FILES['attchments']['name'][0] != "") {
                    $file_upload_path = SITE_BASE_PATH . UPLOAD_FOLDER . EMPLOYMENT . $frm_details['clientid'];

                    if (!folder_exist($file_upload_path)) {
                        mkdir($file_upload_path, 0777);
                    } else if (!is_writable($file_upload_path)) {
                        array_push($error_msgs, 'Problem while uploading');
                    }

                    for ($i = 0; $i < $files_count; $i++) {
                        $file_name = $_FILES['attchments']['name'][$i];

                        $file_info = pathinfo($file_name);

                        $new_file_name = preg_replace('/[[:space:]]+/', '_', $file_info['filename']);

                        $new_file_name = $new_file_name . '_' . DATE(UPLOAD_FILE_DATE_FORMAT_FILE_NAME);

                        $new_file_name = str_replace('.', '_', $new_file_name);

                        $new_file_name = preg_replace('/[^A-Za-z0-9\-]/', '_', $new_file_name);

                        $file_extension = $file_info['extension'];

                        $new_file_name = $new_file_name . '.' . $file_extension;

                        $_FILES['attchment']['name'] = $new_file_name;

                        $_FILES['attchment']['tmp_name'] = $_FILES['attchments']['tmp_name'][$i];

                        $_FILES['attchment']['error'] = $_FILES['attchments']['error'][$i];

                        $_FILES['attchment']['size'] = $_FILES['attchments']['size'][$i];

                        $config['upload_path'] = $file_upload_path;

                        $config['file_name'] = $new_file_name;

                        $config['allowed_types'] = 'jpeg|jpg|png';

                        $config['file_ext_tolower'] = true;

                        $config['remove_spaces'] = true;

                        $config['max_size'] = BULK_UPLOAD_MAX_SIZE_MB * 1000;

                        $this->load->library('upload', $config);

                        $this->upload->initialize($config);

                        if ($this->upload->do_upload('attchment')) {
                            array_push($file_array, array(
                                'file_name' => $new_file_name,
                                'real_filename' => $file_name,
                                'empver_id' => $id)
                            );
                        } else {
                            array_push($error_msgs, $this->upload->display_errors('', ''));

                            $json_array['status'] = ERROR_CODE;

                            $json_array['e_message'] = implode('<br>', $error_msgs);
                        }
                    }
                }

                if (!empty($file_array)) {
                    $this->employment_model->uploaded_files($file_array);
                }

                $result = $this->employment_model->save($fields, array('id' => $id));

                if ($result) {
                    $this->session->set_flashdata('message', array('message' => 'Employment Verification Updated Successfully', 'class' => 'alert alert-success fade in', 'file_upload_error' => $json_array));

                    redirect('admin/employment');
                } else {
                    $this->session->set_flashdata('message', array('message' => 'Something went wrong, please try again', 'class' => 'alert alert-danger fade in', 'file_upload_error' => $json_array));

                    redirect('admin/employment');
                }
            }
        } else {
            $this->load->view('admin/header', $data);

            $this->load->view('admin/employment_add');

            $this->load->view('admin/footer');
        }
    }

    public function update_empt_verificarion_result()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {
            $frm_details = $this->input->post();

            $where = array('id' => $frm_details['id']);

            $fields = array("employed_from" => convert_display_to_db_date($frm_details['employed_from']),
                "employed_to" => convert_display_to_db_date($frm_details['employed_to']),
                "clientid" => $frm_details['clientid'],
                "empverid" => $frm_details['empverid'],
                "candsid" => $frm_details['candsid_ver'],
                "reasonforleaving" => $frm_details['reasonforleaving'],
                "issues" => $frm_details['issues'],
                "exitformalities" => $frm_details['exitformalities'],
                "natureofseparation" => $frm_details['natureofseparation'],
                "mcaregn" => $frm_details['mcaregn'],
                "eligforrehire" => $frm_details['eligforrehire'],
                "addlhrcomments" => $frm_details['addlhrcomments'],
                "integrity_disciplinary_issue" => $frm_details['integrity_disciplinary_issue'],
                "modeofverification" => $frm_details['modeofverification'],
                "emp_designation" => $frm_details['emp_designation'],
                'verifiers_role' => $frm_details['verifiers_role'],
                "verfname" => $frm_details['verfname'],
                "verfdesgn" => $frm_details['verfdesgn'],
                "verifiers_contact_no" => $frm_details['verifiers_contact_no'],
                "verifiers_email_id" => $frm_details['verifiers_email_id'],
                "domainname" => $frm_details['domainname'],
                "domainpurch" => $frm_details['domainpurch'],
                "justdialwebcheck" => $frm_details['justdialwebcheck'],
                "fmlyowned" => $frm_details['fmlyowned'],
                "insuffraiseddate" => $frm_details['insuffraiseddate'],
                "insuffcleardate" => $frm_details['insuffcleardate'],
                "insuffremark" => $frm_details['insuffremark'],
                "insuff_raised_date_2" => convert_display_to_db_date($frm_details['insuff_raised_date_2']),
                "insuff_clear_date_2" => $frm_details['insuff_clear_date_2'],
                "insuff_remarks_2" => $frm_details['insuff_remarks_2'],
                "closuredate" => convert_display_to_db_date($frm_details['closuredate']),
                "verfstatus" => $frm_details['verfstatus'],
                "sub_status" => $frm_details['sub_status'],
                "reportingmanager" => $frm_details['reportingmanager'],
                "remarks" => $frm_details['remarks'],
                "remuneration" => $frm_details['remuneration'],
                "discrepancy_remark_1" => $frm_details['discrepancy_remark_1'],
                "discrepancy_raise_date_1" => $frm_details['discrepancy_raise_date_1'],
                "discrepancy_remark_2" => $frm_details['discrepancy_remark_2'],
                "discrepancy_raise_date_2" => convert_display_to_db_date($frm_details['discrepancy_raise_date_2']),
                'insuff_additional_remark_1' => $frm_details['insuff_additional_remark_1'],
                'insuff_additional_remark_2' => $frm_details['insuff_additional_remark_2'],
                "empid" => $frm_details['empid'],
                "updated" => date(DB_DATE_FORMAT),
                "lastupdatedby" => $this->user_info['id'],
            );

            if ($frm_details['verifiers_role'] == 'hr') //insert only role is hr
            {
                $knowncos_verifiers_details = array('knowncos_id' => $frm_details['knowncos_id'], 'verifiers_name' => $frm_details['verfname'], 'verifiers_designation' => $frm_details['verfdesgn'], 'verifiers_contact_no' => $frm_details['verifiers_contact_no'], 'verifiers_email_id' => $frm_details['verifiers_email_id'], 'created_by' => $this->user_info['id']);

                $this->verifiers_details_save($knowncos_verifiers_details);
            }

            $this->insuff_trigger_mail($fields);

            $result = $this->employment_model->save_update_empt_ver_result($fields, $where);

            $file_array = array();

            $error_msgs = array();

            $file_upload_path = SITE_BASE_PATH . UPLOAD_FOLDER . EMPLOYMENT . $frm_details['clientid'];

            if (!folder_exist($file_upload_path)) {
                mkdir($file_upload_path, 0777);
            } else if (!is_writable($file_upload_path)) {
                array_push($error_msgs, 'Problem while uploading');
            }

            if (empty($error_msgs) && !empty($_FILES['attchments_ver']['name'][0])) {
                $files_count = count($_FILES['attchments_ver']['name']);

                for ($i = 0; $i < $files_count; $i++) {
                    $file_name = $_FILES['attchments_ver']['name'][$i];

                    $file_info = pathinfo($file_name);

                    $new_file_name = preg_replace('/[[:space:]]+/', '_', $file_info['filename']);

                    $new_file_name = $new_file_name . '_' . DATE(UPLOAD_FILE_DATE_FORMAT_FILE_NAME);

                    $new_file_name = str_replace('.', '_', $new_file_name);

                    $new_file_name = preg_replace('/[^A-Za-z0-9\-]/', '_', $new_file_name);

                    $file_extension = $file_info['extension'];

                    $new_file_name = $new_file_name . '.' . $file_extension;

                    $_FILES['attchment']['name'] = $new_file_name;

                    $_FILES['attchment']['tmp_name'] = $_FILES['attchments_ver']['tmp_name'][$i];

                    $_FILES['attchment']['error'] = $_FILES['attchments_ver']['error'][$i];

                    $_FILES['attchment']['size'] = $_FILES['attchments_ver']['size'][$i];

                    $config['upload_path'] = $file_upload_path;

                    $config['file_name'] = $new_file_name;

                    $config['allowed_types'] = 'jpeg|jpg|png';

                    $config['file_ext_tolower'] = true;

                    $config['remove_spaces'] = true;

                    $config['max_size'] = BULK_UPLOAD_MAX_SIZE_MB * 1000;

                    $this->load->library('upload', $config);

                    $this->upload->initialize($config);

                    if ($this->upload->do_upload('attchment')) {
                        array_push($file_array, array(
                            'file_name' => $new_file_name,
                            'real_filename' => $file_name,
                            'empver_id' => $frm_details['id'],
                            'status' => 1,
                            'type' => 1)
                        );
                    } else {
                        array_push($error_msgs, $this->upload->display_errors('', ''));

                        $json_array['status'] = ERROR_CODE;

                        $json_array['e_message'] = implode('<br>', $error_msgs);

                    }
                }
            }

            if (!empty($file_array)) {
                $this->employment_model->uploaded_files($file_array);
            }

            if ($result) {
                $this->session->set_flashdata('message', array('message' => 'Result Updated Successfully', 'class' => 'alert alert-success fade in'));

                $json_array['redirect'] = ADMIN_SITE_URL . "employment/view_details/" . $frm_details['empverid'];

                $json_array['status'] = SUCCESS_CODE;
            } else {
                $this->session->set_flashdata('message', array('message' => 'Something went wrong, please try again', 'class' => 'alert alert-danger fade in'));

                $json_array['message'] = 'Something went wrong, please try again';

                $json_array['status'] = ERROR_CODE;
            }

            auto_update_overall_status($frm_details['candsid_ver']);

            echo_json($json_array);
        }
    }

    public function edit_emptm_veri_result($id = '')
    {
        if ($this->input->is_ajax_request()) {
            $result = $this->employment_model->get_empverres_result(array('empverres.id' => $id));

            if (!empty($result)) {
                $data['empverres_result_details'] = $result[0];

                echo $this->load->view('admin/empt_veri_result_model', $data, true);
            } else {
                echo "<p>Something went wrong, please try again</p>";
            }
        } else {
            echo "<p>Something went wrong, please try again</p>";
        }
    }

    public function get_company()
    {
        $json_array['company_list'] = $this->get_company_list();

        echo_json($json_array);
    }

    public function add_new_company()
    {
        if ($this->input->is_ajax_request()) {
            $frm_details = $this->input->post();

            $json_array = array();

            $fields = array('coname' => ucwords(strtolower($frm_details['coname'])),
                'hrname' => $frm_details['hrname'],
                'hrcno' => $frm_details['hrcno'],
                'hrcno1' => $frm_details['hrcno1'],
                'email1' => $frm_details['email1'],
                'email2' => $frm_details['email2'],
                'created_by' => $this->user_info['id'],
                'created_on' => date(DB_DATE_FORMAT),
            );

            $result = $this->employment_model->add_new_company($fields);

            if ($result) {
                $json_array['message'] = 'Company Added Successfully';

                $json_array['status'] = SUCCESS_CODE;
            } else {
                $json_array['message'] = 'Something went wrong, please try again';

                $json_array['status'] = ERROR_CODE;
            }
            echo_json($json_array);
        }
    }

    public function template_download()
    {
        if ($this->input->is_ajax_request()) {

            // $assigned_user_id = $this->users_list();
          //  $assigned_user_id = $this->employment_model->get_assign_users_id('user_profile', false, array("`user_profile`.`status`,`user_profile`.`id`,`user_profile`.`email`,`user_profile`.`department`,`user_profile`.`user_name`,concat(`user_profile`.`firstname`,' ',`user_profile`.`lastname`) as fullname,`user_profile`.`profile_pic`,`user_profile`.`firstname`,`user_profile`.`lastname`,`roles`.`role_name`,`roles`.`groups_id`,`roles_permissions`.*"), array('user_profile.status' => STATUS_ACTIVE));

            $states = array('Select State', 'Andaman And Nicobar Islands', 'Andhra Pradesh', 'Arunachal Pradesh', 'Assam', 'Bihar', 'Chattisgarh', 'Chandigarh', 'Daman And Diu', 'Delhi', 'Dadra And Nagar Haveli', 'Goa', 'Gujarat', 'Himachal Pradesh', 'Haryana', 'Jammu And Kashmir', 'Jharkhand', 'Kerala', 'Karnataka', 'Lakshadweep', 'Meghalaya');

            // $company_list = $this->get_company_list();

            $employment_type = array('Select Employee Type', 'full time', 'contractual', 'part time');

            require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
            // Create new Spreadsheet object
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            // Set document properties
            $spreadsheet->getProperties()->setCreator(CRMNAME)
                ->setLastModifiedBy(CRMNAME)
                ->setTitle(CRMNAME)
                ->setSubject('Employment Import Template')
                ->setDescription('Employment Import Template File for bulk upload');

            $styleArray = array(
                'fill' => array(
                    'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startcolor' => array(
                        'rgb' => 'FF0000',
                    ),
                ),
            );
            $spreadsheet->getActiveSheet()->getStyle('A1:C1')->applyFromArray($styleArray);
            $spreadsheet->getActiveSheet()->getStyle('G1:I1')->applyFromArray($styleArray);
            $spreadsheet->getActiveSheet()->getStyle('K1')->applyFromArray($styleArray);

            foreach (range('A', 'AE') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                    ->setWidth(20);
            }

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A1", REFNO)
                ->setCellValue("B1", 'Comp Int Date')
                ->setCellValue("C1", 'Company Name')
                ->setCellValue("D1", 'Deputed Company')
                ->setCellValue("E1", 'Previous Employee Code')
                ->setCellValue("F1", 'Employment Type')
                ->setCellValue("G1", 'Employed From')
                ->setCellValue("H1", 'Employed To')
                ->setCellValue("I1", 'Designation')
                ->setCellValue("J1", 'Remuneration')
                ->setCellValue("K1", 'Reason for Leaving')
                ->setCellValue("L1", 'Company Contact Name')
                ->setCellValue("M1", 'Company Contact Designation')
                ->setCellValue("N1", 'Company Contact Email ID')
                ->setCellValue("O1", 'Company Contact No.')
                ->setCellValue("P1", 'Street Address')
                ->setCellValue("Q1", 'City')
                ->setCellValue("R1", 'State')
                ->setCellValue("S1", 'Pincode')
                ->setCellValue("T1", 'Supervisor Name 1')
                ->setCellValue("U1", 'Supervisors contact 1')
                ->setCellValue("V1", 'Designation 1')
                ->setCellValue("W1", 'Supervisors Email ID 1')
                ->setCellValue("X1", 'Supervisor Name 2')
                ->setCellValue("Y1", 'Supervisors contact 2')
                ->setCellValue("Z1", 'Designation 2')
                ->setCellValue("AA1", 'Supervisors Email ID 2')
                ->setCellValue("AB1", 'Manager Name')
                ->setCellValue("AC1", 'Managers contact')
                ->setCellValue("AD1", 'Designation')
                ->setCellValue("AE1", 'Managers Email ID');

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

                /*  $objValidation->setAllowBlank(false);
                $objValidation->setShowInputMessage(true);
                $objValidation->setShowErrorMessage(true);
                $objValidation->setShowDropDown(true);
                $objValidation->setErrorTitle('Input error');
                $objValidation->setError('Value is not in list.');
                $objValidation->setPromptTitle('Pick from list');
                $objValidation->setPrompt('Please pick a value from the drop-down list.');
                $objValidation->setFormula1('"'.implode(',', $company_list).'"');
                 */
                $objValidation = $spreadsheet->getActiveSheet()->getCell('F' . $i)->getDataValidation();
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
                $objValidation->setFormula1('"' . implode(',', $employment_type) . '"');

                /*
                $objValidation = $spreadsheet->getActiveSheet()->getCell('G'.$i)->getDataValidation();
                $objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST  );
                $objValidation->setErrorStyle(  \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION );
                $objValidation->setAllowBlank(false);
                $objValidation->setShowInputMessage(true);
                $objValidation->setShowErrorMessage(true);
                $objValidation->setErrorTitle('Input error');
                $objValidation->setError('This Field is Compulsory.');
                $objValidation->setPromptTitle('Insert Date Only');
                $objValidation->setPrompt('Please insert date only format(DD-MM-YYYY).');

                $objValidation = $spreadsheet->getActiveSheet()->getCell('H'.$i)->getDataValidation();
                $objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST  );
                $objValidation->setErrorStyle(  \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION );
                $objValidation->setAllowBlank(false);
                $objValidation->setShowInputMessage(true);
                $objValidation->setShowErrorMessage(true);
                $objValidation->setErrorTitle('Input error');
                $objValidation->setError('This Field is Compulsory.');
                $objValidation->setPromptTitle('Insert Date Only');
                $objValidation->setPrompt('Please insert date only format(DD-MM-YYYY).');

                $objValidation = $spreadsheet->getActiveSheet()->getCell('I'.$i)->getDataValidation();
                $objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST  );
                $objValidation->setErrorStyle(  \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION );
                $objValidation->setAllowBlank(false);
                $objValidation->setShowInputMessage(true);
                $objValidation->setShowErrorMessage(true);
                $objValidation->setErrorTitle('Input error');
                $objValidation->setError('This Field is Compulsory.');
                $objValidation->setPromptTitle('Insert Designation');
                $objValidation->setPrompt('Please insert Designation');

                 */

                $objValidation = $spreadsheet->getActiveSheet()->getCell('K' . $i)->getDataValidation();
                $objValidation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                $objValidation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
                $objValidation->setAllowBlank(false);
                $objValidation->setShowInputMessage(true);
                $objValidation->setShowErrorMessage(true);
                $objValidation->setErrorTitle('Input error');
                $objValidation->setError('This Field is Compulsory.');
                $objValidation->setPromptTitle('Insert Reason for Leaving');
                $objValidation->setPrompt('Please Insert Reason for Leaving');

                $objValidation = $spreadsheet->getActiveSheet()->getCell('S' . $i)->getDataValidation();
                $objValidation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                $objValidation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
                $objValidation->setAllowBlank(false);
                $objValidation->setShowInputMessage(true);
                $objValidation->setShowErrorMessage(true);
                $objValidation->setErrorTitle('Input error');
                $objValidation->setError('This Field is Compulsory.');
                $objValidation->setPromptTitle('Insert Pin Code');
                $objValidation->setPrompt('Please insert Maximum 6 digit and Mimimum 6.');

                $objValidation = $spreadsheet->getActiveSheet()->getCell('R' . $i)->getDataValidation();
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

              /*  $objValidation = $spreadsheet->getActiveSheet()->getCell('AF' . $i)->getDataValidation();
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

            $spreadsheet->getActiveSheet()->setTitle('Employment Records');
            $spreadsheet->setActiveSheetIndex(0);
            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment;filename=Employment Bulk Uplaod Template.xlsx");
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

            $json_array['file_name'] = "Employment Bulk Uplaod Template";

            $json_array['message'] = "File downloaded successfully,please check in download folder";

            $json_array['status'] = SUCCESS_CODE;
        } else {
            $json_array['file_name'] = "Employment Bulk Uplaod Template";

            $json_array['message'] = "File downloaded failed,please check in download folder";

            $json_array['status'] = ERROR_CODE;
        }
        echo_json($json_array);
    }

    public function template_download_status_change()
    {
        if ($this->input->is_ajax_request()) {
            $frm_details = $this->input->post();

            if ($frm_details['status'] == "stop_check") {

                require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
                // Create new Spreadsheet object
                $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
                // Set document properties
                $spreadsheet->getProperties()->setCreator(CRMNAME)
                    ->setLastModifiedBy(CRMNAME)
                    ->setTitle(CRMNAME)
                    ->setSubject('Address Import Template')
                    ->setDescription('Address Import Template File for bulk upload');

                $styleArray = array(
                    'fill' => array(
                        'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startcolor' => array(
                            'rgb' => 'FF0000',
                        ),
                    ),
                );

                $spreadsheet->getActiveSheet()->getStyle('A1:C1')->applyFromArray($styleArray);

                foreach (range('A', 'C') as $columnID) {
                    $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                        ->setWidth(20);
                }

                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A1", 'Component Ref No.')
                    ->setCellValue("B1", 'Closure Date')
                    ->setCellValue("C1", 'Remark');

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
                    $objValidation->setPrompt('Please insert value.');

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

                }

                $spreadsheet->getActiveSheet()->setTitle('Employment Status Change');
                $spreadsheet->setActiveSheetIndex(0);
                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header("Content-Disposition: attachment;filename=Stop Check Bulk  Upload  Template.xlsx");
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

                $json_array['file_name'] = "Stop Check Bulk  Uplaod Template";

                $json_array['message'] = "File downloaded successfully,please check in download folder";

                $json_array['status'] = SUCCESS_CODE;
            }

        } else {
            $json_array['file_name'] = "Employment Bulk Status Change Uplaod Template";

            $json_array['message'] = "File downloaded failed,please check in download folder";

            $json_array['status'] = ERROR_CODE;
        }
        echo_json($json_array);
    }

    public function bulk_upload()
    {
        $data['header_title'] = "Employment Bulk Upload";

        $data['clients'] = $this->get_clients(array('clients.empver' => 1, 'status' => 1));

        $data['gruop_list'] = $this->gruop_list();

        $data['assigned_user_id'] = $this->get_members_by_group(array('groupID' => 16));

        $this->load->view('admin/header', $data);

        $this->load->view('admin/employment_bulk_upload_view');

        $this->load->view('admin/footer');
    }

    public function bulk_upload_employment_status_change()
    {

        if ($this->input->is_ajax_request()) {

            $frm_details = $this->input->post();

            if ($frm_details['upload_status'] == "stop_check") {

                $file_upload_path = SITE_BASE_PATH . ADDRESS;

                if (!folder_exist($file_upload_path)) {
                    mkdir($file_upload_path, 0777);
                } else if (!is_writable($file_upload_path)) {
                    $message = 'Problem while uploading, folder permission';
                }

                $uplaod_details = array('file_upload_path' => $file_upload_path, 'file_data' => $_FILES['status_change_bulk_upload'], 'file_permission' => 'csv|xls|xlsx', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 100);
                $upload_result = $this->file_uplod($uplaod_details);
                $record = array();

                if (!empty($upload_result) && $upload_result['status'] == true) {

                    $raw_filename = $_FILES['status_change_bulk_upload']['tmp_name'];

                    $headerLine = 0;
                    $file = fopen($raw_filename, "r");

                    $this->load->library('excel_reader', array('file_name' => $file_upload_path . '/' . $upload_result['file_name']));

                    $excel_handler = $this->excel_reader->file_handler;

                    $excel_data = $excel_handler->rows();

                    if (!empty($excel_data)) {

                        unset($excel_data[0]);

                        $excel_data = array_map("unserialize", array_unique(array_map("serialize", $excel_data)));

                        foreach ($excel_data as $value) {

                            if (count($value) < 3) {
                                continue;
                            }

                            $check_record_exits = $this->employment_model->select(true, array('*'), array('emp_com_ref' => strtolower($value[0])));

                            $check_status_filter_value = $this->employment_model->select_verification_status(array('empverid' => $check_record_exits['id']));

                            if (!empty($check_record_exits) && $value[0] != "") {

                                if ($check_status_filter_value[0]['var_filter_status'] == "WIP" || $check_status_filter_value[0]['var_filter_status'] == "wip") {

                                    $field_array = array('verfstatus' => 9,
                                        'var_filter_status' => "Closed",
                                        'var_report_status' => "Stop Check",
                                        'clientid' => $check_record_exits['clientid'],
                                        'candsid' => $check_record_exits['candsid'],
                                        'empverid' => $check_record_exits['id'],
                                        'closuredate' => date('Y-m-d', strtotime(str_replace('/', '-', $value[1]))),
                                        'remarks' => $value[2],
                                        'modified_on' => date(DB_DATE_FORMAT),
                                        'modified_by' => $this->user_info['id'],
                                        'is_bulk_uploaded' => 1,

                                    );

                                    $result = $this->employment_model->save_update_empt_ver_result(array_map('strtolower', $field_array), array('empverid' => $check_record_exits['id']));

                                    $result_empverres_result1 = $this->employment_model->save_update_empt_ver_result_employment(array_map('strtolower', $field_array));

                                    $fields_activity_log = array(
                                        'candsid' => $check_record_exits['candsid'],
                                        'comp_table_id' => $check_record_exits['id'],
                                        'activity_status' => "Stop Check",
                                        'activity_mode' => "Stop Check",
                                        'activity_type' => "Stop Check",
                                        'action' => "Stop Check",
                                        'remarks' => $value[2],
                                        'created_by' => $this->user_info['id'],
                                        'created_on' => date(DB_DATE_FORMAT),
                                        'component_type' => 2,
                                    );

                                    $result_activity_log = $this->employment_model->save_activity_log_trigger($fields_activity_log);

                                    $field_activity = array('candsid' => $check_record_exits['candsid'],
                                        'comp_table_id' => $check_record_exits['id'],
                                        'activity_mode' => "Stop Check",
                                        'activity_status' => "Stop Check",
                                        'activity_type' => "Stop Check",
                                        'action' => "Stop Check",
                                        'remarks' => $value[2],
                                        'created_on' => date(DB_DATE_FORMAT),
                                        'created_by' => $this->user_info['id'],
                                        'is_auto_filled' => 0,

                                    );

                                    $result_activity = $this->employment_model->save_trigger($field_activity);

                                    $array_activity_log = array('activity_log_id' => $result_activity_log);

                                    $result_empverres = $this->employment_model->save_update_empt_ver_result($array_activity_log, array('empverid' => $check_record_exits['id']));

                                    $result_empverres_result = $this->employment_model->save_update_empt_ver_result_employment($array_activity_log, array('sr_id' => $result_empverres_result1));

                                    $get_vendor_log_deatail = $this->employment_model->get_client_id(array('view_vendor_master_log.component' => "empver", 'view_vendor_master_log.component_tbl_id' => 2, 'empver.id' => $check_record_exits['id']));

                                    if (!empty($get_vendor_log_deatail)) {

                                        $update_vendor_log_deatail = $this->employment_model->reject_cost_vendor(array('final_status' => 'closed', 'vendor_remark' => 'Stop Check', 'modified_on' => date(DB_DATE_FORMAT)), array('view_vendor_master_log.id' => $get_vendor_log_deatail[0]['id']));

                                    }

                                    auto_update_overall_status($check_record_exits['candsid']);
                                    auto_update_tat_status($check_record_exits['candsid']);

                                    $data['success'] = $value[0] . " This Component Code Records Created Successfully";

                                } else {

                                    $data['success'] = $value[0] . " This Component Code Records Either Insufficiency or Closed";
                                }
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

        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['message'] = 'Something went wrong, please try again';
        }
        echo_json($json_array);
    }

    public function emp_send_generic_mail()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {
            $employment_id = $this->input->post('emp_user_id');

            $to_emails = $this->input->post('to_email');

            $from_email = $this->input->post('from');

            $cc_email = $this->input->post('cc_email');

            $bcc_email = $this->input->post('bcc_email');

            $subject = $this->input->post('subject');

            $attchment = '';

            $this->load->library('email');

            $details = $this->employment_model->emp_ver_details_for_email(array('empver.id' => $employment_id));

            $reportingmanager_user = $this->employment_model->get_reporting_manager_id();
            $email_tmpl_data['user_profile_info'] = $reportingmanager_user[0];

            $email_tmpl_data['to_emails'] = $to_emails;
            $email_tmpl_data['cc_emails'] = $cc_email;
            $email_tmpl_data['bcc_emails'] = $bcc_email;
            $email_tmpl_data['subject'] = $subject;
            $email_tmpl_data['attchment'] = $attchment;

            $email_tmpl_data['from_email'] = ($from_email != "") ? $from_email : FROMEMAIL;

            $email_tmpl_data['detail_info'] = $details[0];

            $result = $this->email->admin_send_employment_generci_mail($email_tmpl_data);

            if ($result) {
                if (($details[0]['verfstatus'] == "14") || ($details[0]['verfstatus'] == "11") || ($details[0]['verfstatus'] == "26")) {
                    $details_update = $this->employment_model->emp_verfstatus_update(array('empverres.verfstatus' => "1", 'empverres.var_filter_status' => "WIP", 'empverres.var_report_status' => "WIP"), array('empverres.empverid' => $employment_id));
                }

                $field = array('candsid' => $details[0]['candsid'],
                    'ClientRefNumber' => $details[0]['ClientRefNumber'],
                    'comp_table_id' => $employment_id,
                    'activity_status' => "WIP",
                    'activity_type' => "Email",
                    'action' => "Generic Mail",
                    'remarks' => "Generic Mail Send  to ".$to_emails,
                    'created_on' => date(DB_DATE_FORMAT),
                    'created_by' => $this->user_info['id'],
                    'is_auto_filled' => 1,

                );

                $result = $this->employment_model->save_mail_activity_data($field);

                $this->employment_model->emp_mail_details(array('created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id'],'from_email_id' => $from_email, 'to_mail_id' => $to_emails, 'cc_mail_id' => $cc_email, 'empver_id' => $employment_id, 'type' => "Generic Mail"));

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

    public function emp_send_summary_mail()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {
            $employment_id = $this->input->post('emp_user_id');

            $to_emails = $this->input->post('to_email');

            $from_email = $this->input->post('from');

            $cc_email = $this->input->post('cc_email');

            $bcc_email = $this->input->post('bcc_email');

            $subject = $this->input->post('subject');

            $attchment = '';

            $client_disclosure_summary = $this->input->post('client_disclosure_summary');

            $this->load->library('email');

            $details = $this->employment_model->emp_ver_details_for_email(array('empver.id' => $employment_id));
            $reportingmanager_user = $this->employment_model->get_reporting_manager_id();
            $email_tmpl_data['user_profile_info'] = $reportingmanager_user[0];

            $email_tmpl_data['to_emails'] = $to_emails;
            $email_tmpl_data['cc_emails'] = $cc_email;
            $email_tmpl_data['bcc_emails'] = $bcc_email;
            $email_tmpl_data['subject'] = $subject;
            $email_tmpl_data['attchment'] = $attchment;
            $email_tmpl_data['client_disclosure_summary'] = $client_disclosure_summary;

            $email_tmpl_data['from_email'] = ($from_email != "") ? $from_email : FROMEMAIL;

            $email_tmpl_data['detail_info'] = $details[0];

            $result = $this->email->admin_send_employment_summary_mail($email_tmpl_data);

            if ($result) {

                $field = array('candsid' => $details[0]['candsid'],
                    'ClientRefNumber' => $details[0]['ClientRefNumber'],
                    'comp_table_id' => $employment_id,
                    'activity_status' => "",
                    'activity_type' => "Email",
                    'action' => "Summary Mail",
                    'remarks' => "Summary Mail Send to " . $to_emails,
                    'created_on' => date(DB_DATE_FORMAT),
                    'created_by' => $this->user_info['id'],
                    'is_auto_filled' => 1,

                );

                $result = $this->employment_model->save_mail_activity_data($field);

                $this->employment_model->emp_mail_details(array('created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id'], 'from_email_id' => $from_email,'to_mail_id' => $to_emails, 'cc_mail_id' => $cc_email, 'empver_id' => $employment_id, 'type' => "Summary Mail"));

                $user_activity_data = $this->common_model->user_actity_data(array('component' => "Employment",
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

    public function generic_mail($id)
    {
        if ($this->input->is_ajax_request()) {
            $details = $this->employment_model->emp_ver_details_for_email(array('empver.id' => $id));

            $reportingmanager_user = $this->employment_model->get_reporting_manager_id();

            $reportingmanager_id = $reportingmanager_user[0]['reporting_manager'];
            $reportingmanager = $this->employment_model->get_reporting_manager_email_id($reportingmanager_id);
            $view_data['reporting_manager_email'] = $reportingmanager[0];
            $view_data['user_profile_info'] = $reportingmanager_user[0];

            $view_data['email_info'] = $details[0];

            echo $this->load->view(EMAIL_VIEW_FOLDER_NAME . 'generic_mail_template', $view_data, true);
        }
    }

    public function summary_mail($id)
    {
        if ($this->input->is_ajax_request()) {
            $details = $this->employment_model->emp_ver_details_for_email(array('empver.id' => $id));

            $reportingmanager_user = $this->employment_model->get_reporting_manager_id();
            $reportingmanager_id = $reportingmanager_user[0]['reporting_manager'];
            $reportingmanager = $this->employment_model->get_reporting_manager_email_id($reportingmanager_id);
            $view_data['reporting_manager_email'] = $reportingmanager[0];
            $view_data['user_profile_info'] = $reportingmanager_user[0];


            $check_all_madetory = array();
            $view_data['column_empty'] = false;

            foreach ($details as $key => $value) {
                $check_all_madetory['CandidateName'] = $value['CandidateName'];
                $check_all_madetory['coname'] = $value['coname'];
                $check_all_madetory['rsl_empid'] = $value['rsl_empid'];
                $check_all_madetory['employed_from'] = $value['employed_from'];
                $check_all_madetory['employed_to'] = $value['employed_to'];
                $check_all_madetory['emp_designation'] = $value['emp_designation'];
                $check_all_madetory['rsL_remuneration'] = $value['rsL_remuneration'];
                $check_all_madetory['verfdesgn'] = $value['verfdesgn'];
                $check_all_madetory['verfname'] = $value['verfname'];
                $check_all_madetory['rsl_reasonforleaving'] = $value['res_reasonforleaving'];
                $check_all_madetory['integrity_disciplinary_issue'] = $value['info_integrity_disciplinary_issue'];
                $check_all_madetory['exitformalities'] = $value['info_exitformalities'];
                $check_all_madetory['eligforrehire'] = $value['info_eligforrehire'];
                $check_all_madetory['res_employment_type'] = $value['res_employment_type'];
                $check_all_madetory['rsl_reportingmanager'] = $value['rsl_reportingmanager'];
            }

            if (count(array_filter($check_all_madetory)) != 15) {
                $view_data['column_empty'] = true;
            }

            $view_data['email_info'] = $details[0];

            echo $this->load->view(EMAIL_VIEW_FOLDER_NAME . 'summary_mail_template', $view_data, true);
        }
    }

    public function export_to_excel()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {
            set_time_limit(0);
            ini_set('memory_limit', '-1');
            $where_arry = array();

            $client_id = ($this->input->post('clientid') != "All") ? $this->input->post('clientid') : false;

            $fil_by_status = ($this->input->post('fil_by_status') != "All") ? $this->input->post('fil_by_status') : false;

            $client_name = $this->input->post('client_name');

            $from_date = ($this->input->post('from_date') != "") ? convert_display_to_db_date($this->input->post('from_date')) : false;

            $to_date = ($this->input->post('to_date') != "") ? convert_display_to_db_date($this->input->post('to_date')) : false;

            $all_records = $this->employment_model->get_all_employment_by_client($client_id, $fil_by_status, $from_date, $to_date);

            require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
            // Create new Spreadsheet object
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            // Set document properties
            $spreadsheet->getProperties()->setCreator(CRMNAME)
                ->setLastModifiedBy(CRMNAME)
                ->setTitle(CRMNAME)
                ->setSubject('Employment records')
                ->setDescription('Employment records with their status');
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
            $spreadsheet->getActiveSheet()->getStyle('A1:AK1')->applyFromArray($styleArray);
            // auto fit column to content
            foreach (range('A', 'AK') as $columnID) {
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
                ->setCellValue("J1", 'Company Name')
                ->setCellValue("K1", 'Deputed Company')
                ->setCellValue("L1", 'Previous Employee Code')
                ->setCellValue("M1", 'Employed From')
                ->setCellValue("N1", 'Employed To')
                ->setCellValue("O1", 'Designation')
                ->setCellValue("P1", 'Status')
                ->setCellValue("Q1", 'Sub Status')
                ->setCellValue("R1", 'Executive Name')
                ->setCellValue("S1", 'Site Visit')
                ->setCellValue("T1", 'Vendor')
                ->setCellValue("U1", 'Vendor Status')
                ->setCellValue("V1", 'Vendor Assigned on')
                ->setCellValue("W1", 'Closure Date')
                ->setCellValue("X1", 'Insuff Raised Date')
                ->setCellValue("Y1", 'Insuff Clear Date')
                ->setCellValue("Z1", 'Insuff Remark')
                ->setCellValue("AA1", 'Supervisor Name')
                ->setCellValue("AB1", 'Supervisor Contact')
                ->setCellValue("AC1", 'Designation')
                ->setCellValue("AD1", 'Supervisor Email ID')
                ->setCellValue("AE1", 'UAN No')
                ->setCellValue("AF1", 'UAN Remark')
                ->setCellValue("AG1", 'Closure Remark')
                ->setCellValue("AH1", 'Manager Name')
                ->setCellValue("AI1", 'Manager Contact')
                ->setCellValue("AJ1", 'Manager Designation')
                ->setCellValue("AK1", 'Manager Email ID');
            // Add some data
            $x = 2;

            foreach ($all_records as $all_record) {

                $select_supervisor_details = $this->employment_model->supervison_details(array('empver_id' => $all_record['id'],'status'=> 1 ));

                if(!empty($select_supervisor_details))
                {
                    $supervisor_name = $select_supervisor_details[0]['supervisor_name'];
                    $supervisor_designation = $select_supervisor_details[0]['supervisor_designation'];
                    $supervisor_contact_details = $select_supervisor_details[0]['supervisor_contact_details'];
                    $supervisor_email_id = $select_supervisor_details[0]['supervisor_email_id'];

                }
                else
                { 

                   $supervisor_name = "";
                   $supervisor_designation = "";
                   $supervisor_contact_details = "";
                   $supervisor_email_id = "";


                }

                $emp_status = ($all_record['verfstatus'] != "") ? $all_record['verfstatus'] : "WIP";
                $emp_filter_status = ($all_record['filter_status'] != "") ? $all_record['filter_status'] : "WIP";

                $closuredate = ($all_record['filter_status'] == "Closed") ? convert_db_to_display_date($all_record['closuredate']) : "NA";
                $insuff_remarks = $all_record['insuff_raise_remark'];

                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A$x", ucwords($all_record['clientname']))
                    ->setCellValue("B$x", ucwords($all_record['entity_name']))
                    ->setCellValue("C$x", ucwords($all_record['package_name']))
                    ->setCellValue("D$x", $all_record['cmp_ref_no'])
                    ->setCellValue("E$x", $all_record['emp_com_ref'])
                    ->setCellValue("F$x", $all_record['ClientRefNumber'])
                    ->setCellValue("G$x", $all_record['transaction_id'])
                    ->setCellValue("H$x", convert_db_to_display_date($all_record['iniated_date']))
                    ->setCellValue("I$x", ucwords($all_record['CandidateName']))
                    ->setCellValue("J$x", ucwords($all_record['coname']))
                    ->setCellValue("K$x", ucwords($all_record['deputed_company']))
                    ->setCellValue("L$x", $all_record['empid'])
                    ->setCellValue("M$x", $all_record['empfrom'])
                    ->setCellValue("N$x", $all_record['empto'])
                    ->setCellValue("O$x", ucwords($all_record['designation']))
                    ->setCellValue("P$x", $emp_filter_status)
                    ->setCellValue("Q$x", $emp_status)
                    ->setCellValue("R$x", $all_record['executive_name'])
                    ->setCellValue("S$x", $all_record['field_visit_status'])
                    ->setCellValue("T$x", $all_record['vendor_name'])
                    ->setCellValue("U$x", ucwords($all_record['vendor_status']))
                    ->setCellValue("V$x", convert_db_to_display_date($all_record['vendor_assgined_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12))
                    ->setCellValue("W$x", $closuredate)
                    ->setCellValue("X$x", $all_record['insuff_raised_date'])
                    ->setCellValue("Y$x", $all_record['insuff_clear_date'])
                    ->setCellValue("Z$x", $insuff_remarks)
                    ->setCellValue("AA$x", $supervisor_name)
                    ->setCellValue("AB$x", $supervisor_contact_details)
                    ->setCellValue("AC$x", $supervisor_designation)
                    ->setCellValue("AD$x", $supervisor_email_id)
                    ->setCellValue("AE$x", $all_record['uan_no'])
                    ->setCellValue("AF$x", $all_record['uan_remark'])
                    ->setCellValue("AG$x", $all_record['remarks'])
                    ->setCellValue("AH$x", $all_record['r_manager_name'])
                    ->setCellValue("AI$x", $all_record['r_manager_no'])
                    ->setCellValue("AJ$x", $all_record['r_manager_designation'])
                    ->setCellValue("AK$x", $all_record['r_manager_email']);
                $x++;
            }
            // Rename worksheet
            $spreadsheet->getActiveSheet()->setTitle('Employment Records');

            $spreadsheet->setActiveSheetIndex(0);

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment;filename=Employment Records of $client_name.xlsx");
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

            $json_array['file_name'] = "Employment Records of $client_name";

            $json_array['message'] = "File downloaded successfully,please check in download folder";

            $json_array['status'] = SUCCESS_CODE;

        } else {
            $json_array['message'] = "Something went wrong,please try again";
            $json_array['status'] = ERROR_CODE;
        }

        echo_json($json_array);
    }

    public function export_activity_records()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('activity_from', 'Date From', 'required');

            $this->form_validation->set_rules('activity_to', 'Date To', 'required');

            if ($this->form_validation->run() == false) {
                $json_array['message'] = "All fields required";

                $json_array['status'] = ERROR_CODE;

                echo_json($json_array);
            }

            $frm_details = $this->input->post();

            $where_arry = array('activity_log.created_on >' => convert_display_to_db_date($frm_details['activity_from']), 'activity_log.created_on <' => convert_display_to_db_date($frm_details['activity_to']) . ' 23:59:59');

            if ($this->user_info['groupID'] == 4) {
                $where_arry['activity_log.created_by'] = $this->user_info['id'];
            }

            $all_records = $this->employment_model->get_activity_records($where_arry);

            require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
            // Create new Spreadsheet object
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            // Set document properties
            $spreadsheet->getProperties()->setCreator(CRMNAME)
                ->setLastModifiedBy(CRMNAME)
                ->setTitle(CRMNAME)
                ->setSubject('Employment records')
                ->setDescription('Employment records with their status');
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
            $spreadsheet->getActiveSheet()->getStyle('A1:AA1')->applyFromArray($styleArray);
            // auto fit column to content
            foreach (range('A', 'AA') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                    ->setWidth(20);
            }
            // set the names of header cells
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A1", 'Client Ref No.')
                ->setCellValue("B1", 'Created By')
                ->setCellValue("C1", 'Created ON')
                ->setCellValue("D1", 'Activity Status')
                ->setCellValue("E1", 'Activity Mode')
                ->setCellValue("F1", 'Activity Type')
                ->setCellValue("G1", 'Action')
                ->setCellValue("H1", 'Next Follow Up Date')
                ->setCellValue("I1", 'Remarks');
            // Add some data
            $x = 2;

            foreach ($all_records as $all_record) {
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A$x", $all_record['ClientRefNumber'])
                    ->setCellValue("B$x", $all_record['user_name'])
                    ->setCellValue("C$x", convert_db_to_display_date($all_record['created_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12))
                    ->setCellValue("D$x", ucwords($all_record['activity_status']))
                    ->setCellValue("E$x", ucwords($all_record['activity_mode']))
                    ->setCellValue("F$x", ucwords($all_record['activity_type']))
                    ->setCellValue("G$x", ucwords($all_record['action']))
                    ->setCellValue("H$x", convert_db_to_display_date($all_record['next_follow_up_date']))
                    ->setCellValue("I$x", $all_record['remarks']);
                $x++;
            }
            // Rename worksheet
            $spreadsheet->getActiveSheet()->setTitle('Employment Activity Records');

            $spreadsheet->setActiveSheetIndex(0);

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment;filename=Employment Activity Records.xlsx");
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

            $json_array['file_name'] = "Employment Activity Records";

            $json_array['message'] = "File downloaded successfully,please check in download folder";

            $json_array['status'] = SUCCESS_CODE;

        } else {
            $json_array['message'] = "Something went wrong,please try again";
            $json_array['status'] = ERROR_CODE;
        }

        echo_json($json_array);
    }

    public function assign_employment_cases()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {
            $frm_details = $this->input->post();

            $where = array();

            foreach ($frm_details['assign_case'] as $key => $value) {
                array_push($where, array('has_assigned_on' => date(DB_DATE_FORMAT), 'has_case_id' => $frm_details['has_case_id'], 'id' => $value));
            }

            $result = $this->common_model->assign_cases_to_team('empver', $where);

            if ($result) {
                $this->session->set_flashdata('message', array('message' => 'Cases Assigned Successfully', 'class' => 'alert alert-success fade in'));

                $json_array['redirect'] = ADMIN_SITE_URL . "employment/assigned_cases";

                $json_array['status'] = SUCCESS_CODE;

                $json_array['message'] = 'Cases Assigned Successfully';
            } else {
                $this->session->set_flashdata('message', array('message' => 'Something went wrong, please try again', 'class' => 'alert alert-danger fade in'));
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = 'Something went worong, please try again';
            }
            echo_json($json_array);
        }
    }

    public function remove_uploaded_file($id)
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {
            $result = $this->employment_model->save_update_empver_files(array('status' => 2), array('id' => $id));

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

    public function rearrage_uploaded_files($id, $clientid)
    {
        if ($id && $clientid) {
            $data['header_title'] = 'Arrage File';
            $data['clientid'] = $clientid;
            $data['file_path'] = EMPLOYMENT_COM;
            $data['ajax_url'] = ADMIN_SITE_URL . '/employment/';

            $data['files'] = $this->employment_model->get_emp_uploded_files(array('status' => 1, 'type' => 1, 'empver_id' => $id));
            $this->load->view('admin/header', $data);
            $this->load->view('admin/rearrage_file_view');
            $this->load->view('admin/footer');
        } else {
            show_404();
        }
    }

    public function employment_final_assigning()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {

            log_message('error', 'Employment Final Assigning');
            try {

                if ($this->permission['access_address_aq_allow'] == 1) {

                    $frm_details = $this->input->post();
                    $action = $frm_details['action'];

                    if ($frm_details['action'] == 2 && $frm_details['cases_id'] != "") {
                        $list = explode(',', $frm_details['cases_id']);
                        $update_counter = 0;
                        foreach ($list as $key => $value) {

                            $update = $this->employment_model->upload_vendor_assign('employment_vendor_log', array('status' => 2, 'remarks' => $frm_details['reject_reason'], 'modified_by' => $this->user_info['id'], 'modified_on' => date(DB_DATE_FORMAT)), array('id' => $value));

                            if ($update) {
                                $update_counter++;
                                //$return =  $this->education_model->save(array('vendor_id' => 0,'vendor_assgined_on' => NULL),array('id' => $value));
                            }
                        }

                        if ($update_counter) {

                            $json_array['status'] = SUCCESS_CODE;
                            $json_array['message'] = $update_counter . " of " . count($list) . " Rejected Successfully";

                        } else {
                            $json_array['status'] = ERROR_CODE;
                            $json_array['message'] = "Something went wrong,please try again";
                        }

                    } else if ($frm_details['action'] == 1 && $frm_details['cases_id'] != "") {

                        $this->load->library('email');

                        $list = explode(',', $frm_details['cases_id']);

                        $update_counter = 0;

                        foreach ($list as $key => $value) {


                            $select_employment_id = $this->employment_model->select_employment_dt('employment_vendor_log',array('case_id') ,array('id' => $value));

                            $cands_id = $this->employment_model->select_employment_dt('empver',array('candsid'),array('id' => $select_employment_id[0]['case_id']));
                           
                            $cands_details = $this->employment_model->select_employment_dt('candidates_info',array('clientid','entity','package'),array('id' => $cands_id[0]['candsid']));


                            $employment_details[] = $this->employment_model->get_employment_details_for_approval($value);
                        }

                        if(isset($employment_details) && !empty($employment_details))
                        {

                            $employment_detail =  (array_map('current', $employment_details));
                          
                                       
                            foreach( $employment_detail  as $k => $v) {
                                $new_arr[$v['vendor_id']][]=$v;
                            }

                            foreach ($new_arr as $key => $value) {

                                $vendor_name = $this->employment_model->vendor_email_id(array('vendors.id' => $key));

                                $email_tmpl_data['subject'] = 'Employment - '.ucwords($vendor_name[0]['vendor_name']).' - New case/s initiated by '.CRMNAME;
                                $message = "<p>Team,</p><p> The below case/s have been initiated to you. Kindly have them closed within TAT.</p>";
                                        
                                $message .= "<table border = '2'  style='border-spacing: 10; border-collapse: collapse;'>
                                    <tr>
                                    <th style='background-color: #EDEDED;text-align:center'>Sr No</th>
                                    <th style='background-color: #EDEDED;text-align:center'>Client Name</th>
                                    <th style='background-color: #EDEDED;text-align:center'>Component Ref No</th>
                                    <th style='background-color: #EDEDED;text-align:center'>Candidate Name</th>
                                    <th style='background-color: #EDEDED;text-align:center'>Primary Contact</th>
                                    <th style='background-color: #EDEDED;text-align:center'>Contact No (2)</th>
                                    <th style='background-color: #EDEDED;text-align:center'>Contact No (3)</th>
                                    <th style='background-color: #EDEDED;text-align:center'>Address</th>
                                    <th style='background-color: #EDEDED;text-align:center'>City</th>
                                    <th style='background-color: #EDEDED;text-align:center'>Pincode</th>
                                    <th style='background-color: #EDEDED;text-align:center'>State</th>
                                    </tr>";

                                $employment_vendor_log_id = array();
                                $m = 1;
                                foreach ($value as $employment_key => $employment_value) {
                                   
                                    $message .= '<tr>
                                        <td style="text-align:center">'.$m.'</td>
                                        <td style="text-align:center">'.ucwords($employment_value['clientname']). '</td>
                                        <td style="text-align:center">'.$employment_value['emp_com_ref'] . '</td>
                                        <td style="text-align:center">'.ucwords($employment_value['CandidateName']) . '</td>
                                        <td style="text-align:center">'.$employment_value['CandidatesContactNumber'] . '</td>
                                        <td style="text-align:center">'.$employment_value['ContactNo1'] . '</td>
                                        <td style="text-align:center">'.$employment_value['ContactNo2'] . '</td>
                                        <td style="text-align:center">'.ucwords($employment_value['locationaddr']) . '</td>
                                        <td style="text-align:center">'.ucwords($employment_value['citylocality']) . '</td>
                                        <td style="text-align:center">'.$employment_value['pincode'] . '</td>
                                        <td style="text-align:center">'.ucwords($employment_value['state']) . '</td>
                                        </tr>';

                                        $employment_vendor_log_id[] = $employment_value['employment_vendor_log_id'];
                                        
                                    $m++; 
                                           
                                } 


                                $message .= "</table>";

                                $message .= "<br><br><p><b>Note :</b> <I>This is an auto generated email. Request you to write back within 24 hrs with any discrepancy.</I>";
                                        
                                $to_emails = $this->employment_model->vendor_email_id(array('vendors.id' => $key));
                                

                                $email_tmpl_data['to_emails'] = $to_emails[0]['email_id'];

                                $email_tmpl_data['message'] = $message;


                               // $result = $this->email->address_approve_vendor_cases_mail_send($email_tmpl_data);
              
                               // if(!empty($result) && $result == "Success")
                              //  {
                                    if(!empty($employment_vendor_log_id))
                                    {

                                        foreach($employment_vendor_log_id as $employment_vendor_log_key => $employment_vendor_log_value)
                                        {

                                            $update = $this->employment_model->upload_vendor_assign('employment_vendor_log', array('status' => 1, 'approval_by' => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT)), array('id' => $employment_vendor_log_value));
                                            if ($update) {

                                                $update_counter++;
                                                $field_array = array('component' => 'empver',
                                                        'component_tbl_id' => '2',
                                                        'case_id' => $employment_vendor_log_value,
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
                                    } 

                               // }
                               // $this->email->clear(true);
                            }
                        }
                          
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

            } catch (Exception $e) {
                log_message('error', 'Employment::employment_final_assigning');
                log_message('error', $e->getMessage());
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

            $vendor_result = $this->employment_model->vendor_logs(array('component_tbl_id' => '2', 'component' => 'empver'), $id);

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
                    $html_view .= '<td><button data-id=' . $value['id'] . ' data-url ="' . ADMIN_SITE_URL . 'employment/View_vendor_log/' . $value['id'] . '" data-toggle="modal" data-target="#showvendorModel"  class="btn-info  showvendorModel"> View </button>';
                    // if(($value['costing'] == "0" &&  $value['additional_costing'] == "0") ||  ($value['costing'] == NULL &&  $value['additional_costing'] == NULL) )
                    // {
                    if ($value['final_status'] != "closed") {
                        $html_view .= ' <button data-id="showvendorModel_cost" data-url ="' . ADMIN_SITE_URL . 'employment/View_vendor_log_cost/' . $value['id'] . '" data-toggle="modal" data-target="#showvendorModel_cost"  class="btn-info  showvendorModel_cost">Charge</button></tb> ';

                    }
                    $html_view .= ' <button data-id="showvendorModel_cancel" data-url ="' . ADMIN_SITE_URL . 'employment/View_vendor_log_cancel/' . $value['id'] . '" data-toggle="modal" data-target="#showvendorModel_cancel"   class="btn-info  showvendorModel_cancel">Cancel</button></tb> ';
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

    public function View_vendor_log($where_id)
    {

        $details = $this->employment_model->select_vendor_result_log(array('component_tbl_id' => "2", "component" => "empver"), $where_id);

        if ($where_id && !empty($details)) {

            $details['check_insuff_raise'] = '';

            $details['attachments'] = $this->employment_model->select_file_from_vendor(array('id', 'file_name', 'real_filename'), array('view_venor_master_log_id' => $where_id, 'status' => 1, 'component_tbl_id' => 2));
            $details['attachments_file'] = $this->employment_model->select_file_employment(array('id', 'file_name', 'real_filename'), array('empver_id' => $details[0]['employment_id'], 'status' => 1));

    
            $details['assigned_user_id']  = $this->get_reporting_manager_for_execituve($details[0]['clientid']);

            $details['company'] = $this->get_company_list();

            $details['states'] = $this->get_states();
            $details['details'] = $details[0];

            echo $this->load->view('admin/employment_ads_vendor_view', $details, true);
        } else {
            echo "<h4>Record Not Found</h4>";
        }

    }

    public function vendor_logs_cost()
    {

        if ($this->input->is_ajax_request()) {

            $frm_details = $this->input->post();

            $details = $this->employment_model->select_vendor_result_log_cost_details(array('components_tbl_id' => "2"), $frm_details['id']);

            $counter = 1;

            $html_view = '<thead><tr><th>Sr No</th><th>Requested date</th><th>Requested By</th><th>Approved By</th><th>Cost</th><th>Additionl Cost</th><th>Total Cost</th><th>Reamrk</th></tr></thead>';
            foreach ($details as $key => $value) {
                $total_cost = $value['cost'] + $value['additional_cost'];

                $html_view .= '<tr>';
                $html_view .= "<td>" . $counter . "</td>";
                $html_view .= "<td>" . $value['created_on'] . "</td>";
                $html_view .= "<td>" . $value['requested_by'] . "</td>";

                if ($value['accept_reject_cost'] == 1) {
                    $html_view .= "<td>" . $value['approved_by'] . "</td>";
                } else {

                    $html_view .= "<td></td>";

                }

                $html_view .= "<td>" . $value['cost'] . "</td>";
                $html_view .= "<td>" . $value['additional_cost'] . "</td>";
                $html_view .= "<td>" . $total_cost . "</td>";
                $html_view .= "<td>" . $value['remark'] . "</td>";

                $html_view .= '</tr>';

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

    public function View_vendor_log_cost($where_id)
    {

        $details = $this->employment_model->select_vendor_result_log_cost(array('component_tbl_id' => "2", "component" => "empver"), $where_id);

        if ($where_id && !empty($details)) {

            $details['details'] = $details[0];

            echo $this->load->view('admin/employment_ads_vendor_view_cost', $details, true);
        } else {
            echo "<h4>Record Not Found</h4>";
        }

    }

    public function View_vendor_log_cancel($where_id)
    {

        $details = $this->employment_model->select_vendor_result_log_cost(array('component_tbl_id' => "2", "component" => "empver"), $where_id);

        if ($where_id && !empty($details)) {

            $details['details'] = $details[0];

            echo $this->load->view('admin/employment_ads_vendor_view_cancel', $details, true);
        } else {
            echo "<h4>Record Not Found</h4>";
        }

    }

    public function update_arrage_img()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {
            $frm_details = $this->input->post();

            if (is_array($frm_details) && !empty($frm_details)) {
                $order = $frm_details['item'];

                if (is_array($order) && !empty($order)) {
                    foreach ($order as $key => $value) {
                        $update[] = array('serialno' => $key + 1, 'id' => $value);
                    }

                    $this->employment_model->upload_file_update($update);

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

    public function Save_vendor_details_cost()
    {
        if ($this->input->is_ajax_request()) {

            $frm_details = $this->input->post();

            if (!empty($frm_details['additional_charges'])) {

                $this->form_validation->set_rules('remark', 'Remark', 'required');

            }

            $this->form_validation->set_rules('update_id', 'ID', 'required');

            $this->form_validation->set_rules('charges', 'Charges', 'required');

            if ($this->form_validation->run() == false) {

                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');

            } else {
                $folder_name = "vendor_file";
                $file_upload_path = SITE_BASE_PATH . ADDRESS . $folder_name;
                if (!folder_exist($file_upload_path)) {
                    mkdir($file_upload_path, 0777);
                } else if (!is_writable($file_upload_path)) {
                    array_push($error_msgs, 'Problem while uploading');
                }

                $frm_details = $this->input->post();

                $field_array = array(
                    'vendor_master_log_id' => $frm_details['update_id'],
                    'cost' => $frm_details['charges'],
                    'additional_cost' => $frm_details['additional_charges'],
                    'remark' => $frm_details['remark'],
                    'components_tbl_id' => 2,
                    'status' => 1,
                    'created_by' => $this->user_info['id'],
                    'created_on' => date(DB_DATE_FORMAT),

                );

                $result = $this->employment_model->save_vendor_details_costing("vendor_cost_details", $field_array);

                $config_array = array('file_upload_path' => $file_upload_path, 'file_permission' => 'jpeg|jpg|png', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 2000, 'vendor_cost_details_id' => $result, 'component_tbl_id' => 1, 'status' => 1);

                if ($_FILES['attchments_file']['name'][0] != '') {

                    $config_array['files_count'] = count($_FILES['attchments_file']['name']);
                    $config_array['file_data'] = $_FILES['attchments_file'];

                    $retunr_de = $this->file_upload_multiple_vendor($config_array);
                    if (!empty($retunr_de)) {
                        $this->common_model->common_insert_batch('vendor_cost_details_file', $retunr_de['success']);
                    }
                }

                if ($result) {

                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'Vendor Costing Details Successfully Inserted';

                } else {

                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Something went wrong,please try again';

                }

            }
            echo_json($json_array);
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

                $result = $this->employment_model->save_vendor_details_cancel($field_array, array('id' => $frm_details['update_id']));

                $field_array_address = array(
                    'remarks' => $frm_details['venodr_reject_reason'],
                    'modified_by' => $this->user_info['id'],
                    'modified_on' => date(DB_DATE_FORMAT),
                    'status' => 2,
                );

                $address_vendor_result = $this->employment_model->update_address_vendor_log('employment_vendor_log', $field_array_address, array('id' => $frm_details['case_id']));

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

    public function vendor_cost_approval()
    {
        if ($this->input->is_ajax_request()) {
            $details['detail'] = $this->employment_model->get_vendor_cost_aprroval_details();

            //  $details['details'] = $details[0];

        } else {
            $details['detail'] = "Access Denied, You don’t have permission to access this page";
        }

        echo $this->load->view('admin/employment_vendor_cost_details', $details, true);
        //echo $this->load->view('admin/address_ajax_tab',$data,TRUE);

    }

    public function apperovecost()
    {

        if ($this->input->is_ajax_request()) {

            $id = $this->input->post('id');

            $cost = $this->input->post('cost');
            $add_cost = $this->input->post('add_cost');
            $vendor_master_log_id = $this->input->post('vendor_master_log_id');
            $approved_by = $this->user_info['id'];
            $aprroved_on = date(DB_DATE_FORMAT);

            $result = $this->employment_model->approve_cost(array("accept_reject_cost" => "1", "approved_by" => $approved_by, "approved_on" => $aprroved_on), array('id' => $id));

            $result1 = $this->employment_model->approve_cost_vendor(array("costing" => $cost, "additional_costing" => $add_cost), array('id' => $vendor_master_log_id));
            //  $details['details'] = $details[0];

            if ($result && $result1) {

                $json_array['status'] = SUCCESS_CODE;

                $json_array['message'] = 'Approve Vendor Cost';

            } else {

                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = 'Something went wrong,please try again';

            }

        } else {
            $json_array['status'] = ERROR_CODE;

            $json_array['message'] = 'Something went wrong,please try again';
        }

        echo_json($json_array);

        //echo $this->load->view('admin/address_ajax_tab',$data,TRUE);

    }

    public function rejectcost()
    {

        if ($this->input->is_ajax_request()) {

            $id = $this->input->post('id');
            $vendor_master_log_id = $this->input->post('vendor_master_log_id');
            $rejected_by = $this->user_info['id'];
            $rejected_on = date(DB_DATE_FORMAT);

            $result = $this->employment_model->reject_cost(array("accept_reject_cost" => "2", "rejected_by" => $rejected_by, "rejected_on" => $rejected_on), array('id' => $id));

            $result1 = $this->employment_model->reject_cost_vendor(array("costing" => "0", "additional_costing" => "0"), array('id' => $vendor_master_log_id));

            //  $details['details'] = $details[0];

            if ($result && $result1) {

                $json_array['status'] = SUCCESS_CODE;

                $json_array['message'] = 'Reject Vendor Cost ';

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

    public function employment_closure_entries($userid)
    {
        if ($this->input->is_ajax_request() && $userid) {

            log_message('error', 'Employment Closure Entries');
            try {

                $details['user_list_closed'] = $this->users_list_filter();

                $details['userid'] = $userid;

                $details['vendor_executive_list'] = $this->employment_model->employment_case_list(array('view_vendor_master_log.status =' => 1, 'view_vendor_master_log.final_status =' => 'closed', 'component' => 'empver'), $userid);

            } catch (Exception $e) {
                log_message('error', 'Employment::employment_closure_entries');
                log_message('error', $e->getMessage());
            }

        } else {
            $details['vendor_executive_list'] = "Something went wrong,please try again";
        }

        echo $this->load->view('admin/employment_vendor_closure_entries', $details, true);

    }

    public function employment_closure_entries_vendor_insuff($userid)
    {

        if ($this->input->is_ajax_request() && $userid) {

            log_message('error', 'Employment vendor insuff');
            try {

                $details['user_list_insuff'] = $this->users_list_filter();

                $details['userid'] = $userid;

                $details['vendor_executive_list'] = $this->employment_model->employment_case_list_insuff(array('view_vendor_master_log.status =' => 1, 'view_vendor_master_log.final_status =' => 'insufficiency', 'component' => 'empver'), $userid);

            } catch (Exception $e) {
                log_message('error', 'Employment::employment_closure_entries_vendor_insuff');
                log_message('error', $e->getMessage());
            }

        } else {
            $details['vendor_executive_list'] = "Something went wrong,please try again";
        }

        echo $this->load->view('admin/employment_vendor_insuff_entries', $details, true);
    }

    public function check_insuff_already_raised($where_id)
    {

        $details = $this->employment_model->get_employer_details(array('empver.id' => $where_id));

        $check_insuff_raise = $this->employment_model->select_insuff(array('empverres_id' => $where_id, 'status' => STATUS_ACTIVE, 'insuff_clear_date is null' => null));

        $data['insuff_reason_list'] = $this->employment_model->insuff_reason_list(6);

        $data['insuff_raise_message'] = (!empty($check_insuff_raise) ? 'Insufficiency raised on ' . convert_db_to_display_date($check_insuff_raise[0]['insuff_raised_date']) . ' for ' . $check_insuff_raise[0]['insff_reason'] : '');

        $data['check_insuff_value'] = ((!empty($check_insuff_raise)) ? '1' : '2');

        $data['check_insuff_raise'] = ((!empty($check_insuff_raise)) ? 'disabled' : '');
        $data['insuff_raise_id'] = (!empty($check_insuff_raise) ? $check_insuff_raise[0]['id'] : '');

        if (empty($check_insuff_raise)) {
            $data['get_cands_details'] = $this->candidate_entity_pack_details($details[0]['cands_id']);
            $data['selected_data'] = $details[0];

            echo $this->load->view('admin/employments_insuff_view', $data, true);

        } else {
            echo "<h4>Insuff Already Created</h4>";
        }
    }

    public function employment_closure()
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

                        $update_closure = $this->employment_model->update_closure_approval('view_vendor_master_log_closure_status', array('view_vendor_master_log_id' => $value, 'approve_reject_status' => 2, 'approve_reject_by' => $this->user_info['id'], 'reject_reasons' => $frm_details['reject_reason'], "approve_rejected_on" => date(DB_DATE_FORMAT)));

                        $update_closure1 = $this->employment_model->update_closure_approval_details('view_vendor_master_log', array('final_status' => 'wip'), array('id' => $value));

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

                        $update_closure = $this->employment_model->update_closure_approval('view_vendor_master_log_closure_status', array('view_vendor_master_log_id' => $value, 'approve_reject_status' => 1, 'approve_reject_by' => $this->user_info['id'], "approve_rejected_on" => date(DB_DATE_FORMAT)));

                        $update_closure1 = $this->employment_model->update_closure_approval_details('view_vendor_master_log', array('final_status' => 'approve'), array('id' => $value));

                        //$get_client_id = $this->employment_model->get_client_id(array('view_vendor_master_log.final_status' => 'approve', 'view_vendor_master_log.id' => $value));

                       // $insert_task_manager = $this->employment_model->update_closure_approval('client_new_cases', array('client_id' => $get_client_id[0]['client_id'], ' total_cases' => 1, 'status' => 'wip', 'created_by' => $this->user_info['id'], 'created_on' => date(DB_DATE_FORMAT), 'type' => 'closures', 'remarks' => 'closures', 'case_type' => 1, 'view_vendor_master_log_id' => $value));

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

    public function get_hr_database_details($company_databse_id)
    {
        if ($this->input->is_ajax_request() && $company_databse_id) {
            
           $this->load->model('company_database_model'); 

           $company_details = $this->company_database_model->get_company_details(array('company_database.id' => $company_databse_id));

      
            $verifiers_details = $this->company_database_model->get_hr_database_details(array('company_database_verifiers_details.company_database_id' => $company_databse_id));
           
            $verifiers_details_bk = $this->company_database_model->get_hr_database_details_bk($company_details[0]['coname']);

           // $get_other_detail = $this->company_database_model->get_other_database_details($company_details[0]['coname']);
             
            /*$get_other = array();
            foreach ( $get_other_detail as $key => $value) {
               $get_other[] =  $value['id'];
            }       
            

            $get_other_details = $this->company_database_model->get_other_database($get_other); 
*/
        
            $data['company_details'] = $company_details[0];

            $data['verifiers_details'] = $verifiers_details;

            $data['verifiers_details_bk'] = $verifiers_details_bk;

            //$data['get_other_details'] =  $get_other_details;

            $data['states'] = $this->get_states();
 
            echo $this->load->view('admin/employee_hr_database', $data, true);
        } else {
            echo "<p>Something went wrong, please try again.</p>";
        }
    }

    /*public  function get_company_details($requested_term)
    {
    $json_array = array();
    $json_array =  array(array("id" => '100',"company_name" => 'Other'));
    if($this->input->is_ajax_request()) {
    $company_name = $this->employment_model->get_company_name($requested_term);
    if($company_name) {
    $json_array = $company_name;
    }
    }
    return $json_array;
    }

    public function fetch_company_details()
    {
    $get_compnay_name = $_REQUEST['q']['term'];
    $api_details = fetch_company_details($get_compnay_name);
    if(empty($api_details)){
    $api_details = $this->get_company_details($get_compnay_name);
    }

    echo_json($api_details);
    }*/

    public function get_company_details()
    {

        $get_company_name = $_REQUEST['q']['term'];

        if ($this->input->is_ajax_request()) {
            $api_details = $this->employment_model->get_company_name($get_company_name);

            if (empty($api_details[0])) {

                $api_details = $this->fetch_company_details($get_company_name);
            }
        }
        echo_json($api_details);
    }

    public function fetch_company_details($requested_term)
    {

        $company_name = fetch_company_details($requested_term);

        if (!empty($company_name)) {

            $json_array = $company_name;
        } else {
            $json_array = array(array("id" => '100', "company_name" => 'Other'));
        }

        return $json_array;

    }

    public function get_required_fields()
    {
        if ($this->input->is_ajax_request()) {

            $company_name = $this->input->post('company_name');

            $lists = $this->employment_model->select_required_field(array('company_database.id' => $company_name));

            if (!empty($lists)) {
                echo_json($lists[0]);
            } else {
                echo_json('false');
            }
        }
    }
    public function add_form_componet($component_id)
    {
        $emp_details = $this->employment_model->get_employer_details(array('empver.id' => $component_id));
        if ($component_id && !empty($emp_details)) {

            $data['get_cands_details'] = $this->candidate_entity_pack_details($emp_details[0]['candsid']);

            $data['empt_details'] = $emp_details[0];
            $data['states'] = $this->get_states();

            $data['company'] = $this->get_company_list();

           // $data['assigned_user_id'] = $this->employment_model->get_assign_users_id('user_profile', false, array("`user_profile`.`status`,`user_profile`.`id`,`user_profile`.`email`,`user_profile`.`department`,`user_profile`.`user_name`,concat(`user_profile`.`firstname`,' ',`user_profile`.`lastname`) as fullname,`user_profile`.`profile_pic`,`user_profile`.`firstname`,`user_profile`.`lastname`,`roles`.`role_name`,`roles`.`groups_id`,`roles_permissions`.*"), array('user_profile.status' => STATUS_ACTIVE));
            $data['assigned_user_id']  = $this->get_reporting_manager_for_execituve($emp_details[0]['clientid']);


            $data['attachments'] = $this->employment_model->select_file(array('id', 'file_name', 'real_filename', 'type'), array('empver_id' => $component_id, 'status' => 1));

            $data['insuff_reason_list'] = $this->employment_model->insuff_reason_list(false, array('component_id' => 2));

            echo $this->load->view('admin/employment_add_form_reject', $data, true);
        } else {
            show_404();
        }
    }

    public function reject_status()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {

            $employment_id = $this->input->post('employment_id');

            $result = $this->employment_model->save_update_initiated_date(array('reject_status' => 1), array('id' => $employment_id));

            if ($result) {

                $json_array['status'] = SUCCESS_CODE;

                $json_array['message'] = 'Approve Rejected Case';

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

    public function bulk_update_case_received_date()
    {

        if ($this->input->is_ajax_request()) {

            $frm_details = $this->input->post();

          
                $file_upload_path = SITE_BASE_PATH . EMPLOYMENT;

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

                            $check_record_exits = $this->employment_model->select(true, array('*'), array('emp_com_ref' => $value[0]));

                            if (!empty($check_record_exits) && $value[0] != "") {

                                $result = $this->employment_model->save(array('iniated_date' => date('Y-m-d', strtotime(str_replace('/', '-', $value[1])))),array('emp_com_ref'=>$value[0]));

                                $data['success'] = $value[0] . " This Employment Records Update Successfully";
                                   
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

    public function bulk_update_uin_number()
    {

        if ($this->input->is_ajax_request()) {

            $frm_details = $this->input->post();

          
                $file_upload_path = SITE_BASE_PATH . EMPLOYMENT;

                if (!folder_exist($file_upload_path)) {
                    mkdir($file_upload_path, 0777);
                } else if (!is_writable($file_upload_path)) {
                    $message = 'Problem while uploading, folder permission';
                }

                $uplaod_details = array('file_upload_path' => $file_upload_path, 'file_data' => $_FILES['uin_number_change_bulk_upload'], 'file_permission' => 'csv|xls|xlsx', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 100);
                $upload_result = $this->file_uplod($uplaod_details);
                $record = array();

                if (!empty($upload_result) && $upload_result['status'] == true) {

                    $raw_filename = $_FILES['uin_number_change_bulk_upload']['tmp_name'];

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

                            $check_record_exits = $this->employment_model->select_table_value('candidates_info',array('id'),array('cmp_ref_no'=>$value[0]));

                            if (!empty($check_record_exits) && $value[0] != "") {
                            
                                foreach ($check_record_exits as $key => $detail) {

                                   $result = $this->employment_model->save(array('uan_remark' => $value[1]),array('candsid'=>$detail['id']));
                                }
                                   $data['success'] = $value[0] . " This Employment Records Update Successfully";
                                   
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


    public function bulk_update_closure_date()
    {

        if ($this->input->is_ajax_request()) {

            $frm_details = $this->input->post();

          
                $file_upload_path = SITE_BASE_PATH . EMPLOYMENT;

                if (!folder_exist($file_upload_path)) {
                    mkdir($file_upload_path, 0777);
                } else if (!is_writable($file_upload_path)) {
                    $message = 'Problem while uploading, folder permission';
                }

                $uplaod_details = array('file_upload_path' => $file_upload_path, 'file_data' => $_FILES['closure_date_change_bulk_upload'], 'file_permission' => 'csv|xls|xlsx', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 100);
                $upload_result = $this->file_uplod($uplaod_details);
                $record = array();

                if (!empty($upload_result) && $upload_result['status'] == true) {

                    $raw_filename = $_FILES['closure_date_change_bulk_upload']['tmp_name'];

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

                            $check_record_exits = $this->employment_model->select(true, array('*'), array('emp_com_ref' => $value[0]));

                            if (!empty($check_record_exits) && $value[0] != "") {

                                $result = $this->employment_model->save_update_empt_ver_result(array('closuredate' => date('Y-m-d', strtotime(str_replace('/', '-', $value[1])))),array('empverid'=>$check_record_exits['id']));

                                $result_ver = $this->employment_model->save_update_empt_ver_result_employment(array('closuredate' => date('Y-m-d', strtotime(str_replace('/', '-', $value[1])))),array('empverid'=>$check_record_exits['id']));

                                $data['success'] = $value[0] . " This Employment Records Update Successfully";
                                   
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

    public function get_vendor_list_for_field_visit()
    {
        if ($this->input->is_ajax_request()) {
            $vendor_id = $this->employment_model->get_vendor_id_find_state($this->input->post('state')); 
                   
            if(!empty($vendor_id) &&  count($vendor_id) > 1)
            {

                if($this->input->post('state') == "maharashtra" || $this->input->post('state') == "Maharashtra")
                {
                    $vendor_id_city = $this->employment_model->get_vendor_id_find_city($this->input->post('city')); 
                                        
                    if(!empty($vendor_id_city))
                    {
                        $vendor_id = $vendor_id_city[0]['id'];
                    }
                    else{

                        $vendor_id = array(); 

                    }
                }
                else{

                    $vendor_id = $vendor_id;
                }

            }
            else{

                $vendor_id = $this->vendor_list('empver');
            }
   
            echo form_dropdown('vendor_id', $vendor_id, set_value('vendor_id'), 'class="form-control" id="vendor_id"');
        }
    }

    public function Save_vendor_details()
    {
        $this->load->library('email');


        if ($this->input->is_ajax_request()) {

            log_message('error', 'Employment Save Vendor  Details');
            try {

                $this->form_validation->set_rules('transaction_id', 'transaction id', 'required');

                $this->form_validation->set_rules('vendor_remark', 'Vendor Remark', 'required');

                if ($this->form_validation->run() == false) {

                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = validation_errors('', '');

                } else {

                  
                    $frm_details = $this->input->post();

                    $transaction_id = $frm_details['transaction_id'];

                    $get_status = $this->employment_model->select_employment_dt('view_vendor_master_log',array('id','final_status','case_id'),array('trasaction_id' => $transaction_id)); 
                    

                    $field_array = array('final_status' => $frm_details['status'], 'vendor_remark' => $frm_details['vendor_remark'], 'modified_on' => date(DB_DATE_FORMAT), 'vendor_date' => convert_display_to_db_date($frm_details['vendor_date']));

                    $result = $this->employment_model->save_vendor_details("view_vendor_master_log", array_map('strtolower', $field_array), array('trasaction_id' => $transaction_id));

                    $folder_name = "vendor_file";
                    $file_upload_path = SITE_BASE_PATH . EMPLOYMENT . $folder_name;
                    if (!folder_exist($file_upload_path)) {
                        mkdir($file_upload_path, 0777);
                    } else if (!is_writable($file_upload_path)) {
                        array_push($error_msgs, 'Problem while uploading');
                    }

                        
                    $config_array = array('file_upload_path' => $file_upload_path, 'file_permission' => 'jpeg|jpg|png|pdf|tiff', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 2000, 'view_venor_master_log_id' => $frm_details['view_vendor_master_log_id'], 'component_tbl_id' => 2, 'status' => 1);
                
                   
                    if(isset($_FILES['attchments_file']))
                    {
                        if ($_FILES['attchments_file']['name'][0] != '') {
                            $config_array['files_count'] = count($_FILES['attchments_file']['name']);
                            $config_array['file_data'] = $_FILES['attchments_file'];

                            $retunr_de = $this->file_upload_library->file_upload_multiple_vendor($config_array, true);

                            if (!empty($retunr_de)) {
                                $this->common_model->common_insert_batch('view_vendor_master_log_file', $retunr_de['success']);
                            }
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

            } catch (Exception $e) {
                log_message('error', 'Employment::Save_vendor_details');
                log_message('error', $e->getMessage());
            }
        }
    }

}
