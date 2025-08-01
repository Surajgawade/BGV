<?php defined('BASEPATH') or exit('No direct script access allowed');
class Pcc extends MY_Controller
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

        $this->perm_array = array('page_id' => 7);
        if ($this->input->is_ajax_request()) {
            $this->perm_array = array('direct_access' => true);

        }

        $this->assign_options = array('0' => 'Select Executive', '1' => 'Assign to Executive');
        $this->assign_options_vendor = array('0' => 'Select Vendor', '1' => 'Assign to Vendor');
        $this->load->model(array('pcc_verificatiion_model'));
    }

    public function index()
    {
        $data['header_title'] = "PCC Verificatiion Lists";

        $data['filter_view'] = $this->filter_view();

        $data['users_list'] = $this->pcc_verificatiion_model->get_assign_users('user_profile', false, array("`user_profile`.`status`,`user_profile`.`id`,`user_profile`.`email`,`user_profile`.`department`,`user_profile`.`user_name`,concat(`user_profile`.`firstname`,' ',`user_profile`.`lastname`) as fullname,`user_profile`.`profile_pic`,`user_profile`.`firstname`,`user_profile`.`lastname`,`roles`.`role_name`,`roles`.`groups_id`,`roles_permissions`.*"), array('user_profile.status' => STATUS_ACTIVE));

        $data['clients_id'] = $this->get_clients(array('status' => STATUS_ACTIVE));

        $data['status_value'] = $this->get_status();

        $data['vendor_list'] = $this->vendor_list('crimver');

        $this->load->view('admin/header', $data);

        $this->load->view('admin/pcc_verification_list');

        $this->load->view('admin/footer');
    }

    public function get_clients_for_pcc_list_view()
    {
        $params = $_REQUEST;

        $clients = $this->pcc_verificatiion_model->select_client_list_view_pcc('clients', false, array('clients.id', 'clients.clientname'), $params);

        $clients_arry[0] = 'Select Client';

        foreach ($clients as $key => $value) {
            $clients_arry[$value['id']] = ucwords(strtolower($value['clientname']));
        }

        echo_json(array('client_list' => $clients_arry));

    }

    public function pcc_view_datatable()
    {
        $params = $pcc_candidates = $data_arry = $columns = array();

        $params = $_REQUEST;

        $columns = array('id', 'ClientRefNumber', 'cmp_ref_no', 'CandidateName', 'caserecddate', 'last_modified', 'encry_id', 'verfstatus', 'verification_address', 'additional_comment', 'verifiername', 'verification_date', 'modeofverification', 'closuredate', 'remarks', 'reiniated_date');

        $pcc_candidates = $this->pcc_verificatiion_model->get_all_pcc_record_datatable(false, $params, $columns);

        $totalRecords = count($this->pcc_verificatiion_model->get_all_pcc_record_datatable_count(false, $params, $columns));

        $x = 0;
        foreach ($pcc_candidates as $pcc_candidate) {
            $data_arry[$x]['id'] = $x + 1;
         //   $data_arry[$x]['checkbox'] = $pcc_candidate['id'];
            $data_arry[$x]['ClientRefNumber'] = $pcc_candidate['ClientRefNumber'];
            $data_arry[$x]['cmp_ref_no'] = $pcc_candidate['cmp_ref_no'];
            $data_arry[$x]['CandidateName'] = $pcc_candidate['CandidateName'];
            $data_arry[$x]['pcc_com_ref'] = $pcc_candidate['pcc_com_ref'];
            $data_arry[$x]['iniated_date'] = convert_db_to_display_date($pcc_candidate['iniated_date']);
            $data_arry[$x]['clientname'] = $pcc_candidate['clientname'];
            $data_arry[$x]['vendor_name'] = $pcc_candidate['vendor_name'];
            $data_arry[$x]['verfstatus'] = $pcc_candidate['status_value'];
            $data_arry[$x]['street_address'] = $pcc_candidate['street_address'];
            $data_arry[$x]['city'] = $pcc_candidate['city'];
            $data_arry[$x]['pincode'] = $pcc_candidate['pincode'];
            $data_arry[$x]['state'] = $pcc_candidate['state'];
            $data_arry[$x]['executive_name'] = $pcc_candidate['user_name'];
            $data_arry[$x]['caserecddate'] = convert_db_to_display_date($pcc_candidate['caserecddate']);
            $data_arry[$x]['last_activity_date'] = convert_db_to_display_date($pcc_candidate['last_activity_date'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);
            $data_arry[$x]['closuredate'] = convert_db_to_display_date($pcc_candidate['closuredate']);
            $data_arry[$x]['remarks'] = $pcc_candidate['remarks'];
            $data_arry[$x]['tat_status'] = $pcc_candidate['tat_status'];
            $data_arry[$x]['due_date'] = convert_db_to_display_date($pcc_candidate['due_date']);
            $data_arry[$x]['mode_of_veri'] = $pcc_candidate['mode_of_veri'];
            $data_arry[$x]['encry_id'] = ADMIN_SITE_URL . "pcc/view_details/" . encrypt($pcc_candidate['pcc_id']);

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

            $data['mode_of_verification'] = $this->pcc_verificatiion_model->get_mode_of_verification(array('entity' => $data['get_cands_details']['entity_id'], 'package' => $data['get_cands_details']['package_id'], 'tbl_clients_id' => $data['get_cands_details']['clientid']));

            $data['states'] = $this->get_states();
          //  $data['assigned_user_id'] = $this->pcc_verificatiion_model->get_assign_users_id('user_profile', false, array("`user_profile`.`status`,`user_profile`.`id`,`user_profile`.`email`,`user_profile`.`department`,`user_profile`.`user_name`,concat(`user_profile`.`firstname`,' ',`user_profile`.`lastname`) as fullname,`user_profile`.`profile_pic`,`user_profile`.`firstname`,`user_profile`.`lastname`,`roles`.`role_name`,`roles`.`groups_id`,`roles_permissions`.*"), array('user_profile.status' => STATUS_ACTIVE));

            $data['assigned_user_id']  = $this->get_reporting_manager_for_execituve($data['get_cands_details']['clientid']);

            echo $this->load->view('admin/pcc_add', $data, true);
        }
    }

    protected function pcc_com_ref($insert_id)
    {

        $name = COMPONENT_REF_NO['PCC'];
        $pccnumber = $name . $insert_id;
        $field_array = array('pcc_com_ref' => $pccnumber);
        $update_auto_increament_id = $this->pcc_verificatiion_model->update_auto_increament_value($field_array, array('id' => $insert_id));
        return $pccnumber;
    }

    public function save_form()
    {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('clientid', 'Client', 'required');

            $this->form_validation->set_rules('candsid', 'Candidate', 'required');

            $this->form_validation->set_rules('date_of_birth', 'Date of Birth', 'required');

            $this->form_validation->set_rules('father_name', 'Father Name', 'required');

            $this->form_validation->set_rules('street_address', 'Address', 'required');

            $this->form_validation->set_rules('city', 'City', 'required');

            $this->form_validation->set_rules('pincode', 'PIN code', 'required');

            if ($this->form_validation->run() == false) {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');
            } else {

                
                $frm_details = $this->input->post();

                $file_upload_path = SITE_BASE_PATH . PCC . $frm_details['clientid'];
                if (!folder_exist($file_upload_path)) {
                    mkdir($file_upload_path, 0777);
                } else if (!is_writable($file_upload_path)) {
                    array_push($error_msgs, 'Problem while uploading');
                }

                $tat_day = $this->get_client_entity_package_tat_day(array('tbl_clients_id' => $frm_details['clientid'], 'entity' => $frm_details['entity_id'], 'package' => $frm_details['package_id']));

                $get_holiday1 = $this->get_holiday();

                $get_holiday = array_map('current', $get_holiday1);

                $closed_date = getWorkingDays(convert_display_to_db_date($frm_details['iniated_date']), $get_holiday, $tat_day[0]['tat_crimver']);

                $field_array = array('clientid' => $frm_details['clientid'],
                    'candsid' => $frm_details['candsid'],
                    'pcc_com_ref' => '',
                    'iniated_date' => convert_display_to_db_date($frm_details['iniated_date']),
                    'references' => json_encode(array_filter($frm_details['references'])),
                    'references_no' => json_encode(array_filter($frm_details['references_no'])),
                    'address_type' => $frm_details['address_type'],
                    'street_address' => $frm_details['street_address'],
                    'city' => $frm_details['city'],
                    'pincode' => $frm_details['pincode'],
                    'state' => $frm_details['state'],
                    'mode_of_veri' => $frm_details['mode_of_veri'],
                    'created_by' => $this->user_info['id'],
                    'created_on' => date(DB_DATE_FORMAT),
                    'modified_on' => date(DB_DATE_FORMAT),
                    'modified_by' => $this->user_info['id'],
                    'has_case_id' => $frm_details['has_case_id'],
                    'has_assigned_on' => date(DB_DATE_FORMAT),
                    'is_bulk_uploaded' => 0,
                    "due_date" => $closed_date,
                    "tat_status" => "IN TAT",
                );
                $result = $this->pcc_verificatiion_model->save(array_map('strtolower', $field_array));

                $pcc_com_ref = $this->pcc_com_ref($result);

                $config_array = array('file_upload_path' => $file_upload_path, 'file_permission' => 'jpeg|jpg|png|pdf|docx|xls|xlsx', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 1000, 'file_id' => $result, 'component_name' => 'pcc_id');
                if (empty($error_msgs)) {
                    if ($_FILES['attchments']['name'][0] != '') {
                        $config_array['files_count'] = count($_FILES['attchments']['name']);
                        $config_array['file_data'] = $_FILES['attchments'];
                        $config_array['type'] = 0;
                        $retunr_de = $this->file_upload_multiple($config_array);
                        if (!empty($retunr_de)) {
                            $this->common_model->common_insert_batch('pcc_files', $retunr_de['success']);
                        }
                    }

                    if ($_FILES['attchments_cs']['name'][0] != '') {
                        $config_array['files_count'] = count($_FILES['attchments_cs']['name']);
                        $config_array['file_data'] = $_FILES['attchments_cs'];
                        $config_array['type'] = 2;
                        $retunr_cd = $this->file_upload_multiple($config_array);
                        if (!empty($retunr_cd)) {
                            $this->common_model->common_insert_batch('pcc_files', $retunr_cd['success']);
                        }
                    }
                }

                if ($result) {

                    $user_activity_data = $this->common_model->user_actity_data(array('component' => "PCC",'ref_no' => $pcc_com_ref, 'candidate_name' => $frm_details['CandidateName'], 'created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id'], 'activity_type' => 'Manual', 'action' => 'Add'));

                    auto_update_overall_status($frm_details['candsid']);

                    auto_update_tat_status($frm_details['candsid']);

                    $mode_of_verification = $this->pcc_verificatiion_model->get_mode_of_verification(array('tbl_clients_id' => $frm_details['clientid'],'entity' =>$frm_details['entity_id'],'package' =>$frm_details['package_id'])); 

                    if(!empty($mode_of_verification))
                    {
                        $mode_of_verification_value = json_decode($mode_of_verification[0]['mode_of_verification']);
                    }

                    if(isset($mode_of_verification_value->crimver) && ($mode_of_verification_value->crimver  == "verbal"))
                    {
                        $vendor_id_mov = $this->pcc_verificatiion_model->get_vendor_id_find_mov(); 

                        if(!empty($vendor_id_mov))
                        {
                           
                            $vendor_id = $vendor_id_mov[0]['id'];

                        }
                        else{
                            $vendor_id = array();
                        }

                        if(!empty($vendor_id))
                        { 
                           
                            $update = $this->pcc_verificatiion_model->upload_vendor_assign('pcc', array('vendor_id' => $vendor_id, 'vendor_list_mode' => 'verbal', 'vendor_assgined_on' => date(DB_DATE_FORMAT)), array('id' => $result, 'vendor_id =' => 0));


                            $update1 = $this->pcc_verificatiion_model->update_status('pcc_result', array('verfstatus' => 1), array('pcc_id' => $result));

                                if ($update) {

                                    $fiels = array(
                                        'vendor_id' => $vendor_id,
                                        'case_id' => $result,
                                        "status" => 0,
                                        "remarks" => '',
                                        "created_by" => 1,
                                        "created_on" => date(DB_DATE_FORMAT),
                                        "approval_by" => 0,
                                        "modified_on" => null,
                                        "modified_by" => '',
                                    );

                                    $save_vendor_log = $this->pcc_verificatiion_model->save_vendor_log($fiels);
               
                                }
                        }
                                
                    }
                    
                    if($result)
                    {

                        $json_array['status'] = SUCCESS_CODE;

                        $json_array['message'] = 'Record Successfully Inserted';

                        $json_array['redirect'] = ADMIN_SITE_URL . 'pcc';

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
        }
    }

    public function get_assign_pcc_list_view()
    {
        log_message('error', 'PCC List Assign in Assign View');
        try {
            $data['header_title'] = "PCC Verification";

            $data['filter_view'] = $this->filter_view_assign_pcc_list();
            $data['vendor_list'] = $this->vendor_list('crimver');

        } catch (Exception $e) {
            log_message('error', 'Pcc::get_assign_pcc_list_view');
            log_message('error', $e->getMessage());
        }

        echo $this->load->view('admin/assign_pcc_verification_list', $data, true);
    }

    public function get_assign_pcc_list_view_verbal()
    {
        log_message('error', 'PCC List Assign in Assign View');
        try {
            $data['header_title'] = "PCC Verification";

            $data['filter_view'] = $this->filter_view_assign_pcc_list();
            $data['vendor_list'] = $this->vendor_list('crimver');

        } catch (Exception $e) {
            log_message('error', 'Pcc::get_assign_pcc_list_view_verbal');
            log_message('error', $e->getMessage());
        }

        echo $this->load->view('admin/assign_pcc_verification_list_verbal', $data, true);
    }

    protected function filter_view_assign_pcc_list($true = false)
    {
        if ($true) {
            $data['status'] = $this->get_status_candidate();
        } else {
            $data['status'] = $this->get_status();
        }
        $data['clients'] = $this->get_clients_for_list_view_pcc();
        $data['user_list_name'] = $this->users_list_filter();

        return $this->load->view('admin/filter_view_assign', $data, true);
    }

    public function get_clients_for_list_view_pcc()
    {
        $this->load->model('assign_pcc_verificatiion_model');
        $clients = $this->assign_pcc_verificatiion_model->select_client_list_assign_pcc_view('clients', false, array('clients.id', 'clients.clientname'));

        $clients_arry[0] = 'Select Client';

        foreach ($clients as $key => $value) {
            $clients_arry[$value['id']] = ucwords(strtolower($value['clientname']));
        }
        return $clients_arry;
    }

    public function view_from_inside($add_comp_ids = false)
    {
        if ($this->input->is_ajax_request()) {

            $cget_details = $this->pcc_verificatiion_model->get_all_pcc_record(array('pcc.pcc_com_ref' => $add_comp_ids));

            if (!empty($cget_details)) {

                $data['header_title'] = 'PCC Verification';

                $data['get_cands_details'] = $this->candidate_entity_pack_details($cget_details[0]['cands_id']);

                $data['mode_of_verification'] = $this->pcc_verificatiion_model->get_mode_of_verification(array('entity' => $data['get_cands_details']['entity_id'], 'package' => $data['get_cands_details']['package_id'], 'tbl_clients_id' => $data['get_cands_details']['clientid']));

                $data['selected_data'] = $cget_details[0];
                $data['states'] = $this->get_states();
                // $data['assigned_user_id'] = $this->users_list();
               // $data['assigned_user_id'] = $this->pcc_verificatiion_model->get_assign_users_id('user_profile', false, array("`user_profile`.`status`,`user_profile`.`id`,`user_profile`.`email`,`user_profile`.`department`,`user_profile`.`user_name`,concat(`user_profile`.`firstname`,' ',`user_profile`.`lastname`) as fullname,`user_profile`.`profile_pic`,`user_profile`.`firstname`,`user_profile`.`lastname`,`roles`.`role_name`,`roles`.`groups_id`,`roles_permissions`.*"), array('user_profile.status' => STATUS_ACTIVE));

                $data['assigned_user_id']  = $this->get_reporting_manager_for_execituve($cget_details[0]['clientid']);


                $data['reinitiated'] = ($cget_details[0]['var_filter_status'] == 'Closed' || $cget_details[0]['var_filter_status'] == 'closed') ? '1' : '2';

                $data['attachments'] = $this->pcc_verificatiion_model->select_file(array('id', 'file_name', 'real_filename', 'type'), array('pcc_id' => $cget_details[0]['pcc_id'], 'status' => 1));

                $check_insuff_raise = $this->pcc_verificatiion_model->select_insuff(array('pcc_id' => $cget_details[0]['pcc_id'], 'status' => STATUS_ACTIVE, 'insuff_clear_date is null' => null));

                $data['insuff_reason_list'] = $this->pcc_verificatiion_model->insuff_reason_list(false, array('component_id' => 7));

                $data['insuff_raise_message'] = (!empty($check_insuff_raise) ? 'Insufficiency raised on ' . convert_db_to_display_date($check_insuff_raise[0]['insuff_raised_date']) . ' for ' . $check_insuff_raise[0]['insff_reason'] : '');

                $data['check_insuff_raise'] = ((!empty($check_insuff_raise)) ? 'disabled' : '');
                $data['insuff_raise_id'] = (!empty($check_insuff_raise) ? $check_insuff_raise[0]['id'] : '');

                echo $this->load->view('admin/pcc_edit_view_inside', $data, true);
            } else {
                echo "<h3>Something went wrong, please try again</h3>";
            }
        }
    }

    public function update_form()
    {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('pcc_id', 'ID', 'required');

            $this->form_validation->set_rules('has_case_id', 'Assigned To Executive', 'required');

            $this->form_validation->set_rules('clientid', 'Client', 'required');

            $this->form_validation->set_rules('pcc_com_ref', 'Component Ref', 'required');

            $this->form_validation->set_rules('candsid', 'Candidate', 'required');

            $this->form_validation->set_rules('street_address', 'Address', 'required');

            $this->form_validation->set_rules('city', 'City', 'required');

            $this->form_validation->set_rules('pincode', 'PIN code', 'required');

            if ($this->form_validation->run() == false) {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');
            } else {
                $frm_details = $this->input->post();

                $file_upload_path = SITE_BASE_PATH . PCC . $frm_details['clientid'];
                if (!folder_exist($file_upload_path)) {
                    mkdir($file_upload_path, 0777);
                } else if (!is_writable($file_upload_path)) {
                    array_push($error_msgs, 'Problem while uploading');
                }

                $tat_day = $this->get_client_entity_package_tat_day(array('tbl_clients_id' => $frm_details['clientid'], 'entity' => $frm_details['entity_id'], 'package' => $frm_details['package_id']));

                $get_holiday1 = $this->get_holiday();

                $get_holiday = array_map('current', $get_holiday1);

                $closed_date = getWorkingDays(convert_display_to_db_date($frm_details['iniated_date']), $get_holiday, $tat_day[0]['tat_crimver']);

                $field_array = array('clientid' => $frm_details['clientid'],
                    'candsid' => $frm_details['candsid'],
                    'pcc_com_ref' => $frm_details['pcc_com_ref'],
                    'iniated_date' => convert_display_to_db_date($frm_details['iniated_date']),
                    'references' => json_encode(array_filter($frm_details['references'])),
                    'references_no' => json_encode(array_filter($frm_details['references_no'])),
                    'address_type' => $frm_details['address_type'],
                    'street_address' => $frm_details['street_address'],
                    'city' => $frm_details['city'],
                    'build_date' => $frm_details['build_date'],
                    'pincode' => $frm_details['pincode'],
                    'state' => $frm_details['state'],
                    'mode_of_veri' => $frm_details['mode_of_veri'],
                    'modified_on' => date(DB_DATE_FORMAT),
                    'modified_by' => $this->user_info['id'],
                    'has_case_id' => $frm_details['has_case_id'],
                    'has_assigned_on' => date(DB_DATE_FORMAT),
                    'is_bulk_uploaded' => 0,
                    "due_date" => $closed_date,
                    "tat_status" => "IN TAT",
                );

                $result = $this->pcc_verificatiion_model->save(array_map('strtolower', $field_array), array('id' => $frm_details['pcc_id']));

                $select_candidate_billed_date = $this->common_model->select_candidate_billed_date('candidates_info', true, array('build_date'), array('id' => $frm_details['candsid']));

                $component_name = json_decode($select_candidate_billed_date['build_date'], true);

                $result_candidate_billed = $this->common_model->update_candidate_billed_date(array('build_date' => $this->components_key_val(array('0' => $component_name['addrver'], '1' => $component_name['courtver'], '2' => $component_name['globdbver'], '3' => $component_name['narcver'], '4' => $component_name['refver'], '5' => $component_name['empver'], '6' => $component_name['eduver'], '7' => $component_name['identity'], '8' => $component_name['cbrver'], '9' => $frm_details['build_date']))), array('id' => $frm_details['candsid']));

                $config_array = array('file_upload_path' => $file_upload_path, 'file_permission' => 'jpeg|jpg|png|pdf|docx|xls|xlsx', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 1000, 'file_id' => $frm_details['pcc_id'], 'component_name' => 'pcc_id');
                if (empty($error_msgs)) {
                    if ($_FILES['attchments']['name'][0] != '') {
                        $config_array['files_count'] = count($_FILES['attchments']['name']);
                        $config_array['file_data'] = $_FILES['attchments'];
                        $config_array['type'] = 0;
                        $retunr_de = $this->file_upload_multiple($config_array);
                        if (!empty($retunr_de)) {
                            $this->common_model->common_insert_batch('pcc_files', $retunr_de['success']);
                        }
                    }

                    if ($_FILES['attchments_cs']['name'][0] != '') {
                        $config_array['files_count'] = count($_FILES['attchments_cs']['name']);
                        $config_array['file_data'] = $_FILES['attchments_cs'];
                        $config_array['type'] = 2;
                        $retunr_cd = $this->file_upload_multiple($config_array);
                        if (!empty($retunr_cd)) {
                            $this->common_model->common_insert_batch('pcc_files', $retunr_cd['success']);
                        }
                    }
                }

                if ($result) {

                    $user_activity_data = $this->common_model->user_actity_data(array('component' => "PCC",
                        'ref_no' => $frm_details['pcc_com_ref'], 'candidate_name' => $frm_details['CandidateName'], 'created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id'], 'activity_type' => 'Manual', 'action' => 'Edit'));

                    auto_update_tat_status($frm_details['candsid']);

                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'Record Successfully Updated';

                    $json_array['redirect'] = ADMIN_SITE_URL . 'pcc';
                } else {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Something went wrong,please try again';
                }
                echo_json($json_array);
            }
        }
    }

    public function view_details($pcc_id = '')
    {
        if (!empty($pcc_id)) {
            $details = $this->pcc_verificatiion_model->get_all_pcc_record(array('pcc.id' => decrypt($pcc_id)));

            if ($pcc_id && !empty($details)) {
                $data['header_title'] = 'PCC Verification';

                $data['get_cands_details'] = $this->candidate_entity_pack_details($details[0]['cands_id']);
                $data['states'] = $this->get_states();
                $data['selected_data'] = $details[0];
                
              //  $data['assigned_user_id'] = $this->pcc_verificatiion_model->get_assign_users_id('user_profile', false, array("`user_profile`.`status`,`user_profile`.`id`,`user_profile`.`email`,`user_profile`.`department`,`user_profile`.`user_name`,concat(`user_profile`.`firstname`,' ',`user_profile`.`lastname`) as fullname,`user_profile`.`profile_pic`,`user_profile`.`firstname`,`user_profile`.`lastname`,`roles`.`role_name`,`roles`.`groups_id`,`roles_permissions`.*"), array('user_profile.status' => STATUS_ACTIVE));
                $data['assigned_user_id']  = $this->get_reporting_manager_for_execituve($details[0]['clientid']);

                $data['mode_of_verification'] = $this->pcc_verificatiion_model->get_mode_of_verification(array('entity' => $data['get_cands_details']['entity_id'], 'package' => $data['get_cands_details']['package_id'], 'tbl_clients_id' => $data['get_cands_details']['clientid']));

                $check_insuff_raise = $this->pcc_verificatiion_model->select_insuff(array('pcc_id' => decrypt($pcc_id), 'status' => STATUS_ACTIVE, 'insuff_clear_date is null' => null));

                $data['reinitiated'] = (($details[0]['first_qc_approve'] == 'first qc approve' || $details[0]['first_qc_approve'] == 'First QC Approve' || $details[0]['first_qc_approve'] == '') && ($details[0]['var_filter_status'] == 'Closed' || $details[0]['var_filter_status'] == 'closed')) ? '1' : '2';

                $data['attachments'] = $this->pcc_verificatiion_model->select_file(array('id', 'file_name', 'real_filename', 'type'), array('pcc_id' => decrypt($pcc_id), 'status' => 1));

                $data['insuff_reason_list'] = $this->pcc_verificatiion_model->insuff_reason_list(false, array('component_id' => 7));

                $data['insuff_raise_message'] = (!empty($check_insuff_raise) ? 'Insufficiency raised on ' . convert_db_to_display_date($check_insuff_raise[0]['insuff_raised_date']) . ' for ' . $check_insuff_raise[0]['insff_reason'] : '');

                $data['check_insuff_raise'] = ((!empty($check_insuff_raise)) ? 'disabled' : '');
                $data['insuff_raise_id'] = (!empty($check_insuff_raise) ? $check_insuff_raise[0]['id'] : '');

                $this->load->view('admin/header', $data);

                $this->load->view('admin/pcc_edit_view');

                $this->load->view('admin/footer');
            } else {
                show_404();
            }
        } else {
            $this->index();
        }
    }

    public function add_result_model($where_id, $url)
    {
        $details = $this->pcc_verificatiion_model->get_all_pcc_record(array('pcc.id' => $where_id));

        if ($where_id && !empty($details)) {
            $data['details'] = $details[0];
            $data['url'] = $url;

            $data['attachments'] = $this->pcc_verificatiion_model->select_file(array('id', 'file_name', 'status'), array('pcc_id' => $where_id, 'type' => 1));

            $data['vendor_attachments'] = $this->pcc_verificatiion_model->select_file_vendor(array('view_vendor_master_log_file.id', 'view_vendor_master_log_file.file_name', 'view_vendor_master_log_file.real_filename', 'view_vendor_master_log_file.status'), array('view_vendor_master_log_file.component_tbl_id' => 8),$where_id);


            echo $this->load->view('admin/pcc_add_result_model_view', $data, true);
        } else {
            echo "<h4>Record Not Found</h4>";
        }
    }

    public function add_verificarion_result()
    {
        if ($this->input->is_ajax_request()) {

            $this->form_validation->set_rules('pcc_id', 'Id', 'required');

            $this->form_validation->set_rules('pcc_result_id', 'Id', 'required');

            if ($this->form_validation->run() == false) {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');
            } else {
                try {

                    $frm_details = $this->input->post();
                    if (($frm_details['action_val'] != "Select") || ($frm_details['activity_last_id'] != "")) {
                        
                        $verfstatus = status_id_frm_db(array('status_value' => $frm_details['action_val'], 'components_id' => 6));

                        $pcc_result_id = $frm_details['pcc_result_id'];

                        $field_array = array(
                            'verfstatus' => $verfstatus['id'],
                            'var_filter_status' => $verfstatus['filter_status'],
                            'var_report_status' => $verfstatus['report_status'],
                            'clientid' => $frm_details['clientid'],
                            'candsid' => $frm_details['candidates_info_id'],
                            'pcc_id' => $frm_details['pcc_id'],
                            'closuredate' => convert_display_to_db_date($frm_details['closuredate']),
                            'mode_of_verification' => $frm_details['mode_of_verification'],
                            'application_id_ref' => $frm_details['application_id_ref'],
                            'submission_date' => convert_display_to_db_date($frm_details['submission_date']),
                            'police_station' => $frm_details['police_station'],
                            'police_station_visit_date' => convert_display_to_db_date($frm_details['police_station_visit_date']),
                            'name_designation_police' => $frm_details['name_designation_police'],
                            'contact_number_police' => $frm_details['contact_number_police'],
                            'remarks' => $frm_details['remarks'],
                            'modified_on' => date(DB_DATE_FORMAT),
                            'modified_by' => $this->user_info['id'],
                            'is_bulk_uploaded' => 0,
                            'activity_log_id' => $frm_details['activity_last_id'],
                        );

                        if (isset($frm_details['remove_file'])) // delete uploaded file
                        {
                            $this->pcc_verificatiion_model->delete_uploaded_file($frm_details['remove_file']);
                        }
                        if (isset($frm_details['add_file'])) // delete uploaded file
                        {
                            $this->pcc_verificatiion_model->add_uploaded_file($frm_details['add_file']);
                        }

                        if (isset($frm_details['vendor_remove_file'])) // delete uploaded file
                        {
                            $this->pcc_verificatiion_model->vendor_delete_uploaded_file($frm_details['vendor_remove_file']);
                        }
                        if (isset($frm_details['vendor_add_file'])) // delete uploaded file
                        {
                            $this->pcc_verificatiion_model->vendor_add_uploaded_file($frm_details['vendor_add_file']);
                        }




                        $result = $this->pcc_verificatiion_model->save_update_result(array_map('strtolower', $field_array), array('id' => $pcc_result_id));

                        $result_pcc = $this->pcc_verificatiion_model->save_update_result_pcc(array_map('strtolower', $field_array));

                        if ($verfstatus['id'] == 9 || $verfstatus['id'] == 27 || $verfstatus['id'] == 28) {

                            $get_vendor_log_deatail = $this->pcc_verificatiion_model->check_vendor_status_closed_or_not(array('view_vendor_master_log.component' => "crimver", 'view_vendor_master_log.component_tbl_id' => 8, 'pcc.id' => $frm_details['pcc_id']));

                            if (!empty($get_vendor_log_deatail)) {

                                $update_vendor_log_deatail = $this->pcc_verificatiion_model->reject_cost_vendor(array('final_status' => 'cancelled', 'status' => 5, 'remarks' => 'Stop Check', 'rejected_by' => $this->user_info['id'], 'rejected_on' => date(DB_DATE_FORMAT), 'vendor_remark' => 'Stop Check', 'modified_on' => date(DB_DATE_FORMAT)), array('view_vendor_master_log.id' => $get_vendor_log_deatail[0]['id']));

                                $field_array_pcc = array(
                                    'remarks' => "Stop Check",
                                    'modified_by' => $this->user_info['id'],
                                    'modified_on' => date(DB_DATE_FORMAT),
                                    'status' => 2,
                                );

                                $pcc_vendor_result = $this->pcc_verificatiion_model->update_pcc_vendor_log('pcc_vendor_log', $field_array_pcc, array('id' => $get_vendor_log_deatail[0]['pcc_vendor_log_id']));

                            }
                        }
                        
                        $file_upload_path = SITE_BASE_PATH . PCC . $frm_details['clientid'];
                        
                        $config_array = array(
                            'file_upload_path'  => $file_upload_path, 
                            'file_permission'   => 'jpeg|jpg|png|pdf', 
                            'file_size'         => BULK_UPLOAD_MAX_SIZE_MB * 2000, 
                            'file_id'           => $frm_details['pcc_id'], 
                            'component_name'    => 'pcc_id'
                        );

                        if (isset($_FILES['attchments_ver']['name'][0]) &&  $_FILES['attchments_ver']['name'][0] != '') {
                            $config_array['files_count']    = count($_FILES['attchments_ver']['name']);
                            $config_array['file_data']      = $_FILES['attchments_ver'];
                            $config_array['type']           = 1;
                            $retunr_de = $this->file_upload_library->file_upload_multiple($config_array, true);
                            
                            if (!empty($retunr_de['success'])) {
                                $this->common_model->common_insert_batch('pcc_files', $retunr_de['success']);
                            }
                        }

                        if ($result) {

                            auto_update_overall_status($frm_details['candidates_info_id']);
                 //           all_component_closed_qc_status($frm_details['candidates_info_id']);
                            $json_array['status'] = SUCCESS_CODE;
                            $json_array['message'] = 'Updated Successfully';
                            $json_array['redirect'] = ADMIN_SITE_URL . 'pcc';
                        } else {
                            $json_array['status'] = ERROR_CODE;
                            $json_array['message'] = 'Something went wrong,please try again';
                        }

                    } else {
                        $json_array['status'] = ERROR_CODE;
                        $json_array['message'] = 'Something went wrong,please try again';
                    }
                } catch (Exception $e) {
                    log_message('error', 'Pcc::add_verificarion_result');
                    log_message('error', $e->getMessage());
                }
            }
            echo_json($json_array);
        }
    }

    public function add_verificarion_ver_result()
    {
        if ($this->input->is_ajax_request()) {

            $this->form_validation->set_rules('pcc_id', 'Id', 'required');

            $this->form_validation->set_rules('pcc_result_id', 'Id', 'required');

            if ($this->form_validation->run() == false) {
                $json_array['status'] = ERROR_CODE;
                $json_array['message'] = validation_errors('', '');
            } else {
                try {
                    $frm_details = $this->input->post();
                    $pcc_result_id = $frm_details['pcc_result_id'];

                    $field_array = array(
                        'clientid' => $frm_details['clientid'],
                        'candsid' => $frm_details['candidates_info_id'],
                        'pcc_id' => $frm_details['pcc_id'],
                        'closuredate' => convert_display_to_db_date($frm_details['closuredate']),
                        'mode_of_verification' => $frm_details['mode_of_verification'],
                        'application_id_ref' => $frm_details['application_id_ref'],
                        'submission_date' => convert_display_to_db_date($frm_details['submission_date']),
                        'police_station' => $frm_details['police_station'],
                        'police_station_visit_date' => convert_display_to_db_date($frm_details['police_station_visit_date']),
                        'name_designation_police' => $frm_details['name_designation_police'],
                        'contact_number_police' => $frm_details['contact_number_police'],
                        'remarks' => $frm_details['remarks'],
                        'modified_on' => date(DB_DATE_FORMAT),
                        'modified_by' => $this->user_info['id'],
                    );

                    if (isset($frm_details['remove_file'])) // delete uploaded file
                    {
                        $this->pcc_verificatiion_model->delete_uploaded_file($frm_details['remove_file']);
                    }
                    if (isset($frm_details['add_file'])) // delete uploaded file
                    {
                        $this->pcc_verificatiion_model->add_uploaded_file($frm_details['add_file']);
                    }


                    if (isset($frm_details['vendor_remove_file'])) // delete uploaded file
                    {
                        $this->pcc_verificatiion_model->vendor_delete_uploaded_file($frm_details['vendor_remove_file']);
                    }
                    if (isset($frm_details['vendor_add_file'])) // delete uploaded file
                    {
                        $this->pcc_verificatiion_model->vendor_add_uploaded_file($frm_details['vendor_add_file']);
                    }


                    $result = $this->pcc_verificatiion_model->save_update_result(array_map('strtolower', $field_array), array('id' => $pcc_result_id));

                    $result_pcc = $this->pcc_verificatiion_model->save_update_result_pcc(array_map('strtolower', $field_array), array('id' => $frm_details['result_update_id']));

                    $file_upload_path = SITE_BASE_PATH . PCC . $frm_details['clientid'];
                        
                    $config_array = array(
                        'file_upload_path'  => $file_upload_path, 
                        'file_permission'   => 'jpeg|jpg|png|pdf', 
                        'file_size'         => BULK_UPLOAD_MAX_SIZE_MB * 2000, 
                        'file_id'           => $frm_details['pcc_id'], 
                        'component_name'    => 'pcc_id'
                    );

                    if (isset($_FILES['attchments_ver']['name'][0]) &&  $_FILES['attchments_ver']['name'][0] != '') {
                        $config_array['files_count']    = count($_FILES['attchments_ver']['name']);
                        $config_array['file_data']      = $_FILES['attchments_ver'];
                        $config_array['type']           = 1;
                        $retunr_de = $this->file_upload_library->file_upload_multiple($config_array, true);
                        
                        if (!empty($retunr_de['success'])) {
                            $this->common_model->common_insert_batch('pcc_files', $retunr_de['success']);
                        }
                    } 

                    if ($result) {
                        auto_update_overall_status($frm_details['candidates_info_id']);
                   //     all_component_closed_qc_status($frm_details['candidates_info_id']);
                        $json_array['status'] = SUCCESS_CODE;
                        $json_array['message'] = 'Updated Successfully';
                        $json_array['redirect'] = ADMIN_SITE_URL . 'pcc';
                    } else {
                        $json_array['status'] = ERROR_CODE;
                        $json_array['message'] = 'Something went wrong,please try again';
                    }
                } catch (Exception $e) {
                    log_message('error', 'Pcc::add_verificarion_ver_result');
                    log_message('error', $e->getMessage());
                }
            }
            echo_json($json_array);
        }
    }

    public function assign_to_executive()
    {
        $json_array = array();

        if ($this->input->is_ajax_request() && ($this->permission['access_pcc_list_re_assign'] == '1')) {
            $frm_details = $this->input->post();

            if ($frm_details['users_list'] != 0 && $frm_details['cases_id'] != "") {
                $return = $this->common_model->update_in('pcc', array('has_case_id' => $frm_details['users_list'], 'has_assigned_on' => date(DB_DATE_FORMAT)), array('where_id' => $frm_details['cases_id']));
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

    public function approval_queue()
    {
        $this->load->model(array('pcc_vendor_log_model'));

        $assigned_option = array( 0 => 'select');
        ($this->permission['access_pcc_aq_allow'] == 1) ? $assigned_option[1] = 'Assign' : '';
        ($this->permission['access_pcc_aq_allow'] == 1) ? $assigned_option[2] = 'Reject' : '';
        $data['assigned_option'] = $assigned_option;
        $data['header_title'] = "Vendor Approve List";
        $data['user_list_name'] = $this->users_list_filter();
        $this->load->view('admin/header', $data);
        $this->load->view('admin/pcc_vendor_aq');
        $this->load->view('admin/footer');
    }

    public function view_approval_queue()
    {
        $this->load->model(array('pcc_vendor_log_model'));

        $params = $add_candidates = $data_arry = $columns = array();

        $params = $_REQUEST;

        $lists = $this->pcc_vendor_log_model->get_new_list(array('pcc_vendor_log.status' => 0), $params);

        $totalRecords = count($this->pcc_vendor_log_model->get_new_list(array('pcc_vendor_log.status' => 0), $params));

        if ($this->permission['access_pcc_aq_view'] == 1) {

            $x = 0;
            foreach ($lists as $list) {

                $data_arry[$x]['checkbox']      = $list['id'];
                $data_arry[$x]['id']            = $x + 1;
                $data_arry[$x]['pcc_com_ref']   = $list['pcc_com_ref'];
                $data_arry[$x]['clientname']    = $list['clientname'];
                $data_arry[$x]['entity_name']   = $list['entity_name'];
                $data_arry[$x]['package_name']  = $list['package_name'];
                $data_arry[$x]['vendor_name']   = $list['vendor_name'];
                $data_arry[$x]['city']          = $list['city'];
                $data_arry[$x]['encry_id']      = ADMIN_SITE_URL . "pcc/view_details/" . encrypt($list['case_id']);
                $data_arry[$x]['pincode']       = $list['pincode'];
                $data_arry[$x]['state']         = $list['state'];
                $data_arry[$x]['allocated_by']  = $list['allocated_by'];
                $data_arry[$x]['allocated_on']  = convert_db_to_display_date($list['allocated_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);
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
    }

    public function pcc_reinitiated_date()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {

            $pccid = $this->input->post('update_id');
            $reinitiated_date = $this->input->post('reinitiated_date');
            $reinitiated_remark = $this->input->post('reinitiated_remark');
            $clientid = $this->input->post('clientid');

            $check = $this->pcc_verificatiion_model->select_reinitiated_date(array('id' => $pccid));

            if ($check[0]['pcc_re_open_date'] == "0000-00-00" || $check[0]['pcc_re_open_date'] == "") {
                $reinitiated_dates = $reinitiated_date;
            } else {
                $reinitiated_dates = $check[0]['pcc_re_open_date'] . "||" . $reinitiated_date;
            }

            $result = $this->pcc_verificatiion_model->save_update_initiated_date(array('pcc_re_open_date' => $reinitiated_dates, 'pcc_reinitiated_remark' => $reinitiated_remark), array('id' => $pccid));

            $result_pcc = $this->pcc_verificatiion_model->save_update_initiated_date_pcc(array('verfstatus' => 26, 'var_filter_status' => "WIP", 'var_report_status' => "WIP", 'first_qc_approve' => "", 'first_qc_updated_on' => "", 'first_qu_reject_reason' => "", 'first_qu_updated_by' => ""), array('pcc_id' => $pccid));

            $file_upload_path = SITE_BASE_PATH . PCC . $clientid;
            if (!folder_exist($file_upload_path)) {
                mkdir($file_upload_path, 0777);
            } else if (!is_writable($file_upload_path)) {
                array_push($error_msgs, 'Problem while uploading');
            }

            $config_array = array('file_upload_path' => $file_upload_path, 'file_permission' => 'jpeg|jpg|png|pdf|docx|xls|xlsx', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 1000, 'file_id' => $pccid, 'component_name' => 'pcc_id');

            if ($_FILES['attachment_reinitiated']['name'][0] != '') {
                $config_array['files_count'] = count($_FILES['attachment_reinitiated']['name']);
                $config_array['file_data'] = $_FILES['attachment_reinitiated'];
                $config_array['type'] = 2;
                $retunr_cd = $this->file_upload_multiple($config_array);
                if (!empty($retunr_cd)) {
                    $this->common_model->common_insert_batch('pcc_files', $retunr_cd['success']);
                }
            }

            $result_pcc_activity_data = $this->pcc_verificatiion_model->initiated_date_pcc_activity_data(array('candsid' => $this->input->post('candidates_info_id'), 'comp_table_id' => $pccid, 'action' => "Re-Initiated", '  activity_status' => "Re-Initiated", 'remarks' => 'Client requested to re-verify the case [' . $reinitiated_remark . ']', 'created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id']));

            if ($result && $result_pcc) {

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

    public function assign_to_vendor()
    {
        $json_array = array();

        if ($this->input->is_ajax_request() && $this->permission['access_pcc_assign_pcc_assign'] == true) {
            $frm_details = $this->input->post();
            $list = explode(',', $frm_details['cases_id']);
            if ($frm_details['vendor_list'] != 0 && $frm_details['cases_id'] != "" && !empty($list)) {
                $files = $update = array();
                $insert_counter = 0;
                foreach ($list as $key => $value) {

                    $update = $this->common_model->update_batch_vendor_assign('pcc', array('vendor_id' => $frm_details['vendor_list'], 'vendor_list_mode' => $frm_details['vendor_list_mode'], 'vendor_assgined_on' => date(DB_DATE_FORMAT)), array('id' => $value, 'vendor_id =' => 0));

                    $update1 = $this->common_model->update_status('pcc_result', array('verfstatus' => 1), array('pcc_id' => $value));

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
                    $inserted = $this->common_model->common_insert_batch('pcc_vendor_log', $files);
                }

                if (!empty($inserted)) {

                    $result_get_count_plus = $this->common_model->get_count(array('controllers' => 'pcc/approval_queue'));

                    $total_get_count_plus = $result_get_count_plus + count($list);

                    $result_update_count_plus = $this->common_model->update_count(array('count' => $total_get_count_plus), array('controllers' => 'pcc/approval_queue'));

                    $result_get_count_minus = $this->common_model->get_count(array('controllers' => 'assign_pcc'));

                    $total_get_count_minus = $result_get_count_minus - count($list);

                    $result_update_count_minus = $this->common_model->update_count(array('count' => $total_get_count_minus), array('controllers' => 'assign_pcc'));

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

    public function pcc_final_assigning()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {

            if ($this->permission['access_pcc_aq_allow'] == 1) {
                $frm_details = $this->input->post();

                $action = $frm_details['action'];

                if ($frm_details['action'] == 2 && $frm_details['cases_id'] != "") {
                    $list = explode(',', $frm_details['cases_id']);
                    $update_counter = 0;
                    foreach ($list as $key => $value) {

                        $update = $this->pcc_verificatiion_model->upload_vendor_assign('pcc_vendor_log', array('status' => 2, 'remarks' => $frm_details['reject_reason'], 'modified_by' => $this->user_info['id'], 'modified_on' => date(DB_DATE_FORMAT)), array('id' => $value));

                        if ($update) {
                            $update_counter++;
                            //$return =  $this->pcc_verificatiion_model->save(array('vendor_id' => 0,'vendor_assgined_on' => NULL),array('id' => $value));
                        }
                    }

                    if ($update_counter) {

                        $result_get_count_plus = $this->common_model->get_count(array('controllers' => 'assign_pcc'));

                        $total_get_count_plus = $result_get_count_plus + count($list);

                        $result_update_count_plus = $this->common_model->update_count(array('count' => $total_get_count_plus), array('controllers' => 'assign_pcc'));

                        $result_get_count_minus = $this->common_model->get_count(array('controllers' => 'pcc/approval_queue'));

                        $total_get_count_minus = $result_get_count_minus - count($list);

                        $result_update_count_minus = $this->common_model->update_count(array('count' => $total_get_count_minus), array('controllers' => 'pcc/approval_queue'));

                        if ($update_counter) {

                            $json_array['status'] = SUCCESS_CODE;
                            $json_array['message'] = $update_counter . " of " . count($list) . " Rejected Successfully";

                        } else {
                            $json_array['status'] = ERROR_CODE;
                            $json_array['message'] = "Something went wrong,please try again";
                        }

                    } else {
                        $json_array['status'] = ERROR_CODE;
                        $json_array['message'] = "Something went wrong,please try again";
                    }
                } else if ($frm_details['action'] == 1 && $frm_details['cases_id'] != "") {

                    $this->load->library('email');

                    $list = explode(',', $frm_details['cases_id']);

                    $update_counter = 0;

                    foreach ($list as $key => $value) {


                        $pcc_details[] = $this->pcc_verificatiion_model->get_pcc_details_for_approval($value);

                        
                    }

                    if(isset($pcc_details) && !empty($pcc_details))
                    {

                        $pcc_detail =  (array_map('current', $pcc_details));
                                
                        foreach($pcc_detail as $k => $v) {
                            $new_arr[$v['vendor_id']][]=$v;
                        }

                        foreach ($new_arr as $key => $value) {

                            $vendor_name = $this->pcc_verificatiion_model->vendor_email_id(array('vendors.id' => $key));


                            $email_tmpl_data['subject'] = 'PCC Verification - '.ucwords($vendor_name[0]['vendor_name']).' - New case/s initiated by '.CRMNAME;
                                    $message = "<p>Team,</p><p> The below case/s have been initiated to you. Kindly have them closed within TAT.</p>";
                                        
                                    $message .= "<table border = '2'  style='border-spacing: 10; border-collapse: collapse;'>
                                        <tr>
                                        <th style='background-color: #EDEDED;text-align:center'>Sr No</th>
                                        <th style='background-color: #EDEDED;text-align:center'>Component Ref No</th>
                                        <th style='background-color: #EDEDED;text-align:center'>Candidate Name</th>
                                        <th style='background-color: #EDEDED;text-align:center'>Date of Birth</th>
                                        <th style='background-color: #EDEDED;text-align:center'>Father's Name</th>
                                        <th style='background-color: #EDEDED;text-align:center'>Address</th>
                                        <th style='background-color: #EDEDED;text-align:center'>City</th>
                                        <th style='background-color: #EDEDED;text-align:center'>Pincode</th>
                                        <th style='background-color: #EDEDED;text-align:center'>State</th>
                                        </tr>";
                                        $court_vendor_log_id = array();
                                        $m = 1;
                                        foreach ($value as $court_key => $court_value) {
                                    
                                            $message .= '<tr>
                                            <td style="text-align:center">'.$m.'</td>
                                            <td style="text-align:center">'.$court_value['pcc_com_ref'] . '</td>
                                            <td style="text-align:center">'.ucwords($court_value['CandidateName']). '</td>
                                            <td style="text-align:center">'.convert_db_to_display_date($court_value['DateofBirth']). '</td>
                                            <td style="text-align:center">'.ucwords($court_value['NameofCandidateFather']) . '</td>
                                            <td style="text-align:center">'.ucwords($court_value['street_address']) . '</td>
                                            <td style="text-align:center">'.ucwords($court_value['city']) . '</td>
                                            <td style="text-align:center">'.$court_value['pincode'] . '</td>
                                            <td style="text-align:center">'.ucwords($court_value['state']) . '</td>
                                            </tr>';

                                            $pcc_vendor_log_id[] = $court_value['pcc_vendor_log_id'];
                                        
                                            $m++; 
                                        
                                        } 
                                        $message .= "</table>";

                                        $message .= "<br><br><p><b>Note :</b> <I>This is an auto generated email. Request you to write back within 24 hrs with any discrepancy.</I>";
                                        
                                        $to_emails = $this->pcc_verificatiion_model->vendor_email_id(array('vendors.id' => $key));
            
                                        $email_tmpl_data['to_emails'] = ($to_emails[0]['pcc_mov_email'] == "") ? $to_emails[0]['email_id'] : $to_emails[0]['pcc_mov_email'];

                                        $email_tmpl_data['message'] = $message;
                
                                        $result = $this->email->address_approve_vendor_cases_mail_send($email_tmpl_data);
                              
                                        if(!empty($result) &&  $result == "Success")
                                        {
                                            if(!empty($pcc_vendor_log_id))
                                            {
                                                foreach($pcc_vendor_log_id as $pcc_vendor_log_key => $pcc_vendor_log_value)
                                                {
                                                    $update = $this->pcc_verificatiion_model->upload_vendor_assign('pcc_vendor_log', array('status' => 1, 'approval_by' => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT)), array('id' => $pcc_vendor_log_value));

                                                    if ($update) {

                                                        $update_counter++;
                                                        $field_array = array('component' => 'crimver',
                                                            'component_tbl_id' => '8',
                                                            'case_id' => $pcc_vendor_log_value,
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
                                        }

                                        $this->email->clear(true);
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

        } else {

            $json_array['status'] = ERROR_CODE;
            $json_array['message'] = "Access Denied, You don’t have permission to access this page";
        }
        echo_json($json_array);
    }

    public function insuff_raised()
    {
        $json_array = array();
        $json_array['status'] = ERROR_CODE;
        $json_array['message'] = 'Something went wrong,please try again';

        if ($this->input->is_ajax_request()) {
            $pcc_id = $this->input->post('update_id');

            $insff_reason = $this->input->post('insff_reason');

            $insff_date = $this->input->post('txt_insuff_raise');

            $ref_no = $this->input->post('component_ref_no');

            $CandidateName = $this->input->post('CandidateName');

            $check = $this->pcc_verificatiion_model->select_insuff(array('pcc_id' => $pcc_id, 'status !=' => 3, 'insuff_clear_date is null' => null));

            if (empty($check)) {
                $result = $this->pcc_verificatiion_model->save_update_insuff(array('insuff_raised_date' => convert_display_to_db_date($insff_date), 'insuff_raise_remark' => $this->input->post('insuff_raise_remark'), 'status' => STATUS_ACTIVE, 'insff_reason' => $insff_reason, 'created_by' => $this->user_info['id'], 'pcc_id' => $pcc_id, 'auto_stamp' => 1));

                if ($result) {
                    $user_activity_data = $this->common_model->user_actity_data(array('component' => "PCC", 'ref_no' => $ref_no, 'candidate_name' => $CandidateName, 'created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id'], 'activity_type' => 'Manual', 'action' => 'Insuff Raised'));
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
            $data['insuff_details'] = $this->pcc_verificatiion_model->select_insuff_join(array('pcc_id' => $emp_id));

            echo $this->load->view('admin/crimver_insuff_view', $data, true);
        } else {
            echo "<p>Something went wrong, please try again.</p>";
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

            $result = $this->pcc_verificatiion_model->save_first_qc_result(array("first_qc_approve" => "First QC Approve", "first_qc_updated_on" => $accepted_on, "first_qu_updated_by" => $this->user_info['id']), array("pcc_id" => $comp_id, "id" => $frist_qc_id, "candsid" => $cands_id));

            if ($result) {

                $user_activity_data = $this->common_model->user_actity_data(array('component' => "PCC",
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

            $result = $this->pcc_verificatiion_model->save_first_qc_result(array("first_qu_reject_reason" => $rejected_reason, "first_qc_approve" => '', "first_qu_updated_by" => $this->user_info['id'], "first_qc_updated_on" => $rejected_on, "verfstatus" => 13, "var_filter_status" => "WIP", "var_report_status" => "WIP"), array("pcc_id" => $comp_id, "id" => $frist_qc_id, "candsid" => $cands_id));

            if ($result) {

                $user_activity_data = $this->common_model->user_actity_data(array('component' => "PCC",
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

    public function insuff_edit_clear_raised_data()
    {
        if ($this->input->is_ajax_request()) {
            $insuff_data = $this->input->post('insuff_data');

            $result = $this->pcc_verificatiion_model->select_insuff(array('id' => $insuff_data));

            if (!empty($result)) {
                $data['insuff_reason_list'] = $this->insuff_reason_list(8);
                $data['insuff_details'] = $result[0];
                echo $this->load->view('admin/insuff_edit_and_clear_view', $data, true);
            }
        }
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

            $result = $this->pcc_verificatiion_model->save_update_insuff($fields, array('id' => $frm_details['id']));

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

    public function insuff_raised_data()
    {
        if ($this->input->is_ajax_request()) {
            $insuff_data = $this->input->post('insuff_data');

            $result = $this->pcc_verificatiion_model->select_insuff(array('id' => $insuff_data));
            if (!empty($result)) {
                $result = $result[0];
                $result['insuff_raised_date'] = convert_db_to_display_date($result['insuff_raised_date']);
                $result['insuff_clear_date'] = convert_db_to_display_date($result['insuff_clear_date']);
            }
            echo_json($result);
        }
    }

    public function insuff_clear()
    {
        $json_array = array();

        $frm_details = $this->input->post();

        if ($this->input->is_ajax_request() && $this->permission['access_pcc_list_insuff_clear'] == 1) {
            if (convert_display_to_db_date($frm_details['insuff_clear_date']) >= convert_display_to_db_date($frm_details['check_insuff_raise'])) {
                $insuff_date = $frm_details['insuff_clear_date'];

                $hold_days = getNetWorkDays($frm_details['check_insuff_raise'], $frm_details['insuff_clear_date']);

                $date_tat = $this->pcc_verificatiion_model->get_date_for_update(array('id' => $frm_details['clear_update_id']));

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

                $result_due_date = $this->pcc_verificatiion_model->update_due_date(array('due_date' => $closed_date, 'tat_status' => $new_tat), array('id' => $frm_details['clear_update_id']));

                $fields = array('insuff_clear_date' => convert_display_to_db_date($insuff_date), 'insuff_remarks' => $frm_details['insuff_remarks'], 'insuff_cleared_timestamp' => date(DB_DATE_FORMAT), 'status' => 2, 'insuff_cleared_by' => $this->user_info['id'], 'auto_stamp' => 2, 'hold_days' => $hold_days);

                $result = $this->pcc_verificatiion_model->save_update_insuff($fields, array('id' => $frm_details['insuff_clear_id']));

                $get_vendor_log_deatail = $this->pcc_verificatiion_model->check_vendor_status_insufficiency(array('view_vendor_master_log.component' => "crimver", 'view_vendor_master_log.component_tbl_id' => 8,
                    'view_vendor_master_log.final_status' => 'insufficiency', 'pcc.id' => $frm_details['clear_update_id']));

                if (!empty($get_vendor_log_deatail)) {

                    $update_vendor_log_deatail = $this->pcc_verificatiion_model->reject_cost_vendor(array('final_status' => 'wip', 'vendor_remark' => '', 'modified_on' => date(DB_DATE_FORMAT)), array('view_vendor_master_log.id' => $get_vendor_log_deatail[0]['id']));

                }

                if ($result) {
                    $user_activity_data = $this->common_model->user_actity_data(array('component' => "PCC", 'ref_no' => $frm_details['component_ref_no'], 'candidate_name' => $frm_details['CandidateName'], 'created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id'], 'activity_type' => 'Manual', 'action' => 'Insuff Cleared'));
                }

                $error_msgs = $file_array = array();

                $file_upload_path = SITE_BASE_PATH . PCC . $frm_details['clear_clientid'];

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
                                'pcc_id' => $frm_details['clear_update_id'],
                                'type' => 2)
                            );
                        } else {
                            array_push($error_msgs, $this->upload->display_errors('', ''));

                            $json_array['status'] = ERROR_CODE;

                            $json_array['e_message'] = implode('<br>', $error_msgs);

                        }
                    }

                    if (!empty($file_array)) {
                        $this->pcc_verificatiion_model->uploaded_files($file_array);
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
        if ($this->input->is_ajax_request() && $this->permission['access_pcc_list_insuff_delete'] == 1) {
            $fields = array('status' => 3, 'modified_on' => date(DB_DATE_FORMAT), 'modified_by' => $this->user_info['id']);

            $result = $this->pcc_verificatiion_model->save_update_insuff($fields, array('id' => $insuff_data));

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
            $details = $this->pcc_verificatiion_model->get_all_pcc_record(array('pcc.id' => decrypt($cmp_id)));

            if (!empty($details)) {
                $data['details'] = $details[0];
                echo $this->load->view('admin/pcc_add_result_model_view_first_qc', $data, true);
            } else {
                echo "<h4>Record Not Found</h4>";
            }

        } else {
            echo "<p>Something went wrong, please try again.</p>";
        }
    }

    public function bulk_upload_pcc()
    {

        if ($this->input->is_ajax_request()) {
            $file_upload_path = SITE_BASE_PATH . PCC;

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

                        if (count($value) < 11) {
                            continue;
                        }

                        $check_record_exits = $this->candidates_model->select(true, array('id', 'clientid', 'entity', 'package'), array('cmp_ref_no' => strtolower($value[0])));

                        if (!empty($check_record_exits) && $value[0] != "") {

                            $users_id  = $this->get_reporting_manager_for_executive($check_record_exits['clientid']); 

                            $user_id =  $users_id[0]['id'];

                            $tat_day = $this->get_client_entity_package_tat_day(array('tbl_clients_id' => $check_record_exits['clientid'], 'entity' => $check_record_exits['entity'], 'package' => $check_record_exits['package']));

                            $get_holiday1 = $this->get_holiday();

                            $get_holiday = array_map('current', $get_holiday1);

                            $closed_date = getWorkingDays(get_date_from_timestamp($value[1]), $get_holiday, $tat_day[0]['tat_crimver']);

                            $mode_of_verification = $this->pcc_verificatiion_model->get_mode_of_verification(array('entity' => $check_record_exits['entity'], 'package' => $check_record_exits['package'], 'tbl_clients_id' => $check_record_exits['clientid']));

                            if (!empty($mode_of_verification)) {
                                $mode_of_verification_value = json_decode($mode_of_verification[0]['mode_of_verification']);

                                $mode_of_veri = $mode_of_verification_value->crimver;
                            } else {

                                $mode_of_veri = "";
                            }

                            $references = array($value[7], $value[9]);

                            $references_no = array($value[8], $value[10]);

                            $field_array = array('clientid' => $check_record_exits['clientid'],
                                'candsid' => $check_record_exits['id'],
                                'pcc_com_ref' => '',
                                'address_type' => $value[2],
                                'street_address' => $value[3],
                                'city' => $value[4],
                                'pincode' => $value[5],
                                'state' => $value[6],
                                'references' => json_encode(array_filter($references)),
                                'references_no' => json_encode(array_filter($references_no)),
                                'iniated_date' => get_date_from_timestamp($value[1]),
                                "pcc_re_open_date" => '',
                                'created_by' => $this->user_info['id'],
                                'created_on' => date(DB_DATE_FORMAT),
                                'modified_on' => date(DB_DATE_FORMAT),
                                'modified_by' => $this->user_info['id'],
                                'has_case_id' => $user_id,
                                'has_assigned_on' => date(DB_DATE_FORMAT),
                                'is_bulk_uploaded' => 1,
                                "mode_of_veri" => $mode_of_veri,
                                "status" => 1,
                                "due_date" => $closed_date,
                                "tat_status" => "IN TAT",
                            );

                            $record = array_map('strtolower', array_map('trim', $field_array));

                            $insert_id = $this->pcc_verificatiion_model->save($record);

                            $pcc_com_ref = $this->pcc_com_ref($insert_id);

                            $result_get_count = $this->common_model->get_count(array('controllers' => 'assign_pcc'));

                            $total_get_count = $result_get_count + 1;

                            $result_update_count = $this->common_model->update_count(array('count' => $total_get_count), array('controllers' => 'assign_pcc'));

                            auto_update_overall_status($check_record_exits['id']);
                            auto_update_tat_status($check_record_exits['id']);

                            $data['success'] = $pcc_com_ref . " This Component Code Records Created Successfully";

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

    public function pcc_result_list($emp_id = '')
    {

        if ($emp_id && $this->input->is_ajax_request()) {
            $pcc_result = $this->pcc_verificatiion_model->select_result_log(array('pcc_ver_result.pcc_id' => $emp_id, 'activity_log_id !=' => null));

            $html_view = '<thead><tr><th>Created On</th><th>Created By</th><th>Action</th><th>Activit Mode</th><th>Attachment</th><th>Activity Type</th><th>Activity Status</th><th>View</th></tr></thead>';

            if (!empty($pcc_result[0]['id'])) {
                $l = 1;

                foreach ($pcc_result as $key => $value) {


                    $vendor_attachments = $this->pcc_verificatiion_model->select_file_vendor(array('view_vendor_master_log_file.id', 'view_vendor_master_log_file.file_name', 'view_vendor_master_log_file.real_filename', 'view_vendor_master_log_file.status'), array('view_vendor_master_log_file.component_tbl_id' => 8), $value['pcc_id']);

                    $file = "&nbsp;&nbsp;&nbsp;&nbsp;";
                    if ($value['file_names']) {
                        $files = explode(',', $value['file_names']);

                        for ($i = 0; $i < count($files); $i++) {
                            $url = "'" . SITE_URL . PCC . $value['clientid'] . '/';
                            $actual_file = $files[$i] . "'";
                            $myWin = "'" . "myWin" . "'";
                            $attribute = "'" . "height=250,width=480" . "'";

                            $file .= '<a href="javascript:;" onClick="myOpenWindow(' . $url . $actual_file . ',' . $myWin . ',' . $attribute . '); return false"><i class="fa fa-file-photo-o" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;';
                        }
                    }

                    if ($vendor_attachments) {

                        for ($j = 0; $j < count($vendor_attachments); $j++) {
                            $folder_name = "vendor_file";
                            $url = "'" . SITE_URL . PCC . $folder_name . '/';
                            $actual_file = $vendor_attachments[$j]['file_name'] . "'";
                            $myWin = "'" . "myWin" . "'";
                            $attribute = "'" . "height=250,width=480" . "'";

                            $file .= '<a href="javascript:;" ondblClick="myOpenWindow(' . $url . $actual_file . ',' . $myWin . ',' . $attribute . '); return false"> <i class="fa fa-file-photo-o" aria-hidden="true"></i> </a>&nbsp;&nbsp;&nbsp;&nbsp;';
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
                        $html_view .= '<td><button data-id="showAddResultModel" data-url ="' . ADMIN_SITE_URL . 'pcc/pcc_result_list_idwise/' . $value['id'] . '/' . str_replace(" ", "", $value['activity_action']) . '" data-toggle="modal" data-target="#showAddResultModel1"  class="btn-info  showAddResultModel"> View </button></td>';
                    } else {
                        $html_view .= '<td></td>';
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

    public function pcc_result_list_idwise($where_id, $url)
    {
        $details = $this->pcc_verificatiion_model->select_result_log1(array('pcc_ver_result.id' => $where_id));

        if ($where_id && !empty($details)) {

            $data['check_insuff_raise'] = '';
            $data['details'] = $details[0];
            $data['url'] = $url;
            $data['attachments'] = $this->pcc_verificatiion_model->select_file(array('id', 'file_name', 'status'), array('pcc_id' => $details[0]['pcc_id'], 'type' => 1));

            $data['vendor_attachments'] = $this->pcc_verificatiion_model->select_file_vendor(array('view_vendor_master_log_file.id', 'view_vendor_master_log_file.file_name', 'view_vendor_master_log_file.real_filename', 'view_vendor_master_log_file.status'), array('view_vendor_master_log_file.component_tbl_id' => 8), $details[0]['pcc_id']);


            echo $this->load->view('admin/pcc_add_result_model_view_log', $data, true);
        } else {
            echo "<h4>Record Not Found</h4>";
        }

    }

    public function vendor_logs($id)
    {
        if ($this->input->is_ajax_request() && $id) {

            $vendor_result = $this->pcc_verificatiion_model->vendor_logs(array('component_tbl_id' => "8", "component" => "crimver"), $id);

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
                    $html_view .= '<td><button data-id=' . $value['id'] . ' data-url ="' . ADMIN_SITE_URL . 'pcc/View_vendor_log/' . $value['id'] . '" data-toggle="modal" data-target="#showvendorModel"  class="btn-info  showvendorModel"> View </button>';
                    // if(($value['costing'] == "0" &&  $value['additional_costing'] == "0") ||  ($value['costing'] == NULL &&  $value['additional_costing'] == NULL) )
                    // {
                    /* if($value['final_status'] != "closed")
                    {
                    $html_view .= ' <button data-id="showvendorModel_cost" data-url ="'.ADMIN_SITE_URL.'address/View_vendor_log_cost/'.$value['id'].'" data-toggle="modal" data-target="#showvendorModel_cost"  class="btn-info  showvendorModel_cost">Charge</button></tb> ';

                    }*/
                    $html_view .= ' <button data-id="showvendorModel_cancel" data-url ="' . ADMIN_SITE_URL . 'pcc/View_vendor_log_cancel/' . $value['id'] . '" data-toggle="modal" data-target="#showvendorModel_cancel"   class="btn-info  showvendorModel_cancel">Cancel</button></tb> ';
                }
                $html_view .= '<td></td>';
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

        $details = $this->pcc_verificatiion_model->select_vendor_result_log(array('component_tbl_id' => "8", "component" => "crimver"), $where_id);

        if ($where_id && !empty($details)) {
            $details['attachments'] = $this->pcc_verificatiion_model->select_file_from_vendor(array('id', 'file_name', 'real_filename'), array('view_venor_master_log_id' => $where_id, 'status' => 1, 'component_tbl_id' => 8));

            $details['attachments_file'] = $this->pcc_verificatiion_model->select_file(array('id', 'file_name', 'real_filename'), array('pcc_id' => $details[0]['pcc_id'], 'status' => 1));

            $details['check_insuff_raise'] = '';

            $details['assigned_user_id']  = $this->get_reporting_manager_for_execituve($details[0]['clientid']);

            $details['states'] = $this->get_states();
            $details['details'] = $details[0];

            echo $this->load->view('admin/pcc_ads_vendor_view', $details, true);
        } else {
            echo "<h4>Record Not Found</h4>";
        }

    }

    public function View_vendor_log1($where_id)
    {
        $details = $this->pcc_verificatiion_model->select_vendor_result_log(array('component_tbl_id' => "8", "component" => "crimver"), $where_id);

        if ($where_id && !empty($details)) {

            $details['attachments'] = $this->pcc_verificatiion_model->select_file_from_vendor(array('id', 'file_name', 'real_filename'), array('view_venor_master_log_id' => $where_id, 'status' => 1, 'component_tbl_id' => 8));
     
            $details['attachments_file'] = $this->pcc_verificatiion_model->select_file(array('id', 'file_name', 'real_filename'), array('pcc_id' => $details[0]['pcc_id'], 'status' => 1,'type' => 0));

            $details['check_insuff_raise'] = '';

            $details['states'] = $this->get_states();
            $details['details'] = $details[0];

            echo $this->load->view('admin/pcc_ads_vendor_view_approve_tab', $details, true);

        } else {
            echo "<h4>Record Not Found</h4>";
        }

    }


    public function View_vendor_log_cancel($where_id)
    {

        $details = $this->pcc_verificatiion_model->select_vendor_result_log_cost(array('component_tbl_id' => "8", "component" => "crimver"), $where_id);

        if ($where_id && !empty($details)) {

            $details['details'] = $details[0];

            echo $this->load->view('admin/pcc_ads_vendor_view_cancel', $details, true);
        } else {
            echo "<h4>Record Not Found</h4>";
        }

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
                $file_upload_path = SITE_BASE_PATH . PCC . $folder_name;
                if (!folder_exist($file_upload_path)) {
                    mkdir($file_upload_path, 0777);
                } else if (!is_writable($file_upload_path)) {
                    array_push($error_msgs, 'Problem while uploading');
                }

                $frm_details = $this->input->post();

                $transaction_id = $frm_details['transaction_id'];

                $field_array = array('final_status' => $frm_details['status'], 'vendor_remark' => $frm_details['vendor_remark'], 'modified_on' => date(DB_DATE_FORMAT), 'vendor_date' => convert_display_to_db_date($frm_details['vendor_date']));

                $result = $this->pcc_verificatiion_model->save_vendor_details("view_vendor_master_log", $field_array, array('trasaction_id' => $transaction_id));

                
                $config_array = array('file_upload_path' => $file_upload_path, 'file_permission' => 'jpeg|jpg|png|tiff|pdf', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 2000, 'view_venor_master_log_id' => $frm_details['view_vendor_master_log_id'], 'component_tbl_id' => 8, 'status' => 1);

                if ($_FILES['attchments_file']['name'][0] != '') {
                    $config_array['files_count'] = count($_FILES['attchments_file']['name']);
                    $config_array['file_data'] = $_FILES['attchments_file'];

                    $retunr_de = $this->file_upload_library->file_upload_multiple_vendor($config_array, true);

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

                $result = $this->pcc_verificatiion_model->save_vendor_details_cancel($field_array, array('id' => $frm_details['update_id']));

                $field_array_court = array(
                    'remarks' => $frm_details['venodr_reject_reason'],
                    'modified_by' => $this->user_info['id'],
                    'modified_on' => date(DB_DATE_FORMAT),
                    'status' => 2,
                );

                $pcc_vendor_result = $this->pcc_verificatiion_model->update_pcc_vendor_log('pcc_vendor_log', $field_array_court, array('id' => $frm_details['case_id']));

                $result_get_count_plus = $this->common_model->get_count(array('controllers' => 'assign_pcc'));

                $total_get_count_plus = $result_get_count_plus + 1;

                $result_update_count_plus = $this->common_model->update_count(array('count' => $total_get_count_plus), array('controllers' => 'assign_pcc'));

                if ($result && $pcc_vendor_result) {

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

    public function pcc_closure_entries()
    {
        if ($this->input->is_ajax_request()) {

            log_message('error', 'PCC Closure Entries');
            try {

                $details['vendor_executive_list'] = $this->pcc_verificatiion_model->pcc_case_list(array('view_vendor_master_log.status =' => 1, 'view_vendor_master_log.final_status =' => 'closed', 'component' => 'crimver'));

             } catch (Exception $e) {
                log_message('error', 'Pcc::pcc_closure_entries');
                log_message('error', $e->getMessage());
            }

        } else {
            $details['vendor_executive_list'] = "Something went wrong,please try again";
        }

        echo $this->load->view('admin/pcc_vendor_closure_entries', $details, true);
    }

    public function pcc_closure_entries_vendor_insuff($userid)
    {
        if ($this->input->is_ajax_request() && $userid) {

            log_message('error', 'Pcc vendor insuff');
            try {

                $details['user_list_insuff'] = $this->users_list_filter();

                $details['userid'] = $userid;

                $details['vendor_executive_list'] = $this->pcc_verificatiion_model->pcc_case_list_insuff(array('view_vendor_master_log.status =' => 1, 'view_vendor_master_log.final_status =' => 'insufficiency', 'component' => 'crimver'), $userid);

            } catch (Exception $e) {
                log_message('error', 'Pcc::pcc_closure_entries_vendor_insuff');
                log_message('error', $e->getMessage());
            }

        } else {
            $details['vendor_executive_list'] = "Something went wrong,please try again";
        }

        echo $this->load->view('admin/pcc_vendor_insuff_entries', $details, true);
    }

    public function check_insuff_already_raised($where_id)
    {

        $pcc_details = $this->pcc_verificatiion_model->get_all_pcc_record(array('pcc.id' => $where_id));

        $check_insuff_raise = $this->pcc_verificatiion_model->select_insuff(array('pcc_id' => $where_id, 'status' => STATUS_ACTIVE, 'insuff_clear_date is null' => null));

        $data['insuff_reason_list'] = $this->insuff_reason_list(8);

        $data['insuff_raise_message'] = (!empty($check_insuff_raise) ? 'Insufficiency raised on ' . convert_db_to_display_date($check_insuff_raise[0]['insuff_raised_date']) . ' for ' . $check_insuff_raise[0]['insff_reason'] : '');

        $data['check_insuff_raise'] = ((!empty($check_insuff_raise)) ? 'disabled' : '');
        $data['check_insuff_value'] = ((!empty($check_insuff_raise)) ? '1' : '2');
        $data['insuff_raise_id'] = (!empty($check_insuff_raise) ? $check_insuff_raise[0]['id'] : '');

        if (empty($check_insuff_raise)) {
            $data['get_cands_details'] = $this->candidate_entity_pack_details($pcc_details[0]['cands_id']);
            $data['pcc_details'] = $pcc_details[0];

            echo $this->load->view('admin/pcc_insuff_view', $data, true);

        } else {
            echo "<h4>Insuff Already Created</h4>";
        }
    }

    public function pcc_closure()
    {

        if ($this->input->is_ajax_request()) {

            if ($this->permission['access_pcc_list_activity'] == 1) {
                $frm_details = $this->input->post();
                $action = $frm_details['action'];

                if ($frm_details['action'] == 2 && $frm_details['closure_id'] != "") {
                    $list = explode(',', $frm_details['closure_id']);

                    $update_counter = 0;
                    $files = array();

                    foreach ($list as $key => $value) {

                        $update_closure = $this->pcc_verificatiion_model->update_closure_approval('view_vendor_master_log_closure_status', array('view_vendor_master_log_id' => $value, 'approve_reject_status' => 2, 'approve_reject_by' => $this->user_info['id'], 'reject_reasons' => $frm_details['reject_reason'], "approve_rejected_on" => date(DB_DATE_FORMAT)));

                        $update_closure1 = $this->pcc_verificatiion_model->update_closure_approval_details('view_vendor_master_log', array('final_status' => 'wip'), array('id' => $value));

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

                        $update_closure = $this->pcc_verificatiion_model->update_closure_approval('view_vendor_master_log_closure_status', array('view_vendor_master_log_id' => $value, 'approve_reject_status' => 1, 'approve_reject_by' => $this->user_info['id'], "approve_rejected_on" => date(DB_DATE_FORMAT)));

                        $update_closure1 = $this->pcc_verificatiion_model->update_closure_approval_details('view_vendor_master_log', array('final_status' => 'approve'), array('id' => $value));

                        //$get_client_id = $this->pcc_verificatiion_model->get_client_id(array('view_vendor_master_log.final_status' => 'approve', 'view_vendor_master_log.id' => $value));

                        //$insert_task_manager = $this->pcc_verificatiion_model->update_closure_approval('client_new_cases', array('client_id' => $get_client_id[0]['client_id'], ' total_cases' => 1, 'status' => 'wip', 'created_by' => $this->user_info['id'], 'created_on' => date(DB_DATE_FORMAT), 'type' => 'closures', 'remarks' => 'closures', 'case_type' => 1, 'view_vendor_master_log_id' => $value));

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

           // $assigned_user_id = $this->pcc_verificatiion_model->get_assign_users_id('user_profile', false, array("`user_profile`.`status`,`user_profile`.`id`,`user_profile`.`email`,`user_profile`.`department`,`user_profile`.`user_name`,concat(`user_profile`.`firstname`,' ',`user_profile`.`lastname`) as fullname,`user_profile`.`profile_pic`,`user_profile`.`firstname`,`user_profile`.`lastname`,`roles`.`role_name`,`roles`.`groups_id`,`roles_permissions`.*"), array('user_profile.status' => STATUS_ACTIVE));

            $states = array('Select State', 'Andaman And Nicobar Islands', 'Andhra Pradesh', 'Arunachal Pradesh', 'Assam', 'Bihar', 'Chattisgarh', 'Chandigarh', 'Daman And Diu', 'Delhi', 'Dadra And Nagar Haveli', 'Goa', 'Gujarat', 'Himachal Pradesh', 'Haryana', 'Jammu And Kashmir', 'Jharkhand', 'Kerala', 'Karnataka', 'Lakshadweep', 'Meghalaya');

            $address_type = array('Select Address Type', 'permanent', 'current');

            require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
            // Create new Spreadsheet object
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            // Set document properties
            $spreadsheet->getProperties()->setCreator(CRMNAME)
                ->setLastModifiedBy(CRMNAME)
                ->setTitle(CRMNAME)
                ->setSubject('PCC Import Template')
                ->setDescription('PCC Import Template File for bulk upload');

            $styleArray = array(
                'fill' => array(
                    'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startcolor' => array(
                        'rgb' => 'FF0000',
                    ),
                ),
            );
            $spreadsheet->getActiveSheet()->getStyle('A1:B1')->applyFromArray($styleArray);
            $spreadsheet->getActiveSheet()->getStyle('D1:G1')->applyFromArray($styleArray);

            foreach (range('A', 'K') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                    ->setWidth(20);
            }

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A1", REFNO)
                ->setCellValue("B1", 'Comp Int Date')
                ->setCellValue("C1", 'Address Type')
                ->setCellValue("D1", 'Street Address')
                ->setCellValue("E1", 'City')
                ->setCellValue("F1", 'PIN Code')
                ->setCellValue("G1", 'State')
                ->setCellValue("H1", 'Reference Name 1')
                ->setCellValue("I1", 'Reference Contact 1')
                ->setCellValue("J1", 'Reference Name 2')
                ->setCellValue("K1", 'Reference Contact 2');

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
                $objValidation->setPrompt('Please ' . REFNO);

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
                $objValidation->setFormula1('"' . implode(',', $address_type) . '"');

                $objValidation = $spreadsheet->getActiveSheet()->getCell('D' . $i)->getDataValidation();
                $objValidation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                $objValidation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
                $objValidation->setAllowBlank(false);
                $objValidation->setShowInputMessage(true);
                $objValidation->setShowErrorMessage(true);
                $objValidation->setErrorTitle('Input error');
                $objValidation->setError('This Field is Compulsory.');
                $objValidation->setPromptTitle('Insert Street Address');
                $objValidation->setPrompt('Please insert Street Address.');

                $objValidation = $spreadsheet->getActiveSheet()->getCell('E' . $i)->getDataValidation();
                $objValidation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                $objValidation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
                $objValidation->setAllowBlank(false);
                $objValidation->setShowInputMessage(true);
                $objValidation->setShowErrorMessage(true);
                $objValidation->setErrorTitle('Input error');
                $objValidation->setError('This Field is Compulsory.');
                $objValidation->setPromptTitle('Insert City');
                $objValidation->setPrompt('Please insert City');

                $objValidation = $spreadsheet->getActiveSheet()->getCell('F' . $i)->getDataValidation();
                $objValidation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                $objValidation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
                $objValidation->setAllowBlank(false);
                $objValidation->setShowInputMessage(true);
                $objValidation->setShowErrorMessage(true);
                $objValidation->setErrorTitle('Input error');
                $objValidation->setError('This Field is Compulsory.');
                $objValidation->setPromptTitle('Insert Pin Code');
                $objValidation->setPrompt('Please insert Maximum 6 digit and Mimimum 6.');

                $objValidation = $spreadsheet->getActiveSheet()->getCell('G' . $i)->getDataValidation();
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

                $objValidation = $spreadsheet->getActiveSheet()->getCell('I' . $i)->getDataValidation();
                $objValidation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                $objValidation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
                $objValidation->setAllowBlank(false);
                $objValidation->setShowInputMessage(true);
                $objValidation->setShowErrorMessage(true);
                $objValidation->setErrorTitle('Input error');
                $objValidation->setError('This Field is Compulsory.');
                $objValidation->setPromptTitle('Insert Reference Contact 1');
                $objValidation->setPrompt('Please insert Maximum 11 digit and Mimimum 10.');

                $objValidation = $spreadsheet->getActiveSheet()->getCell('K' . $i)->getDataValidation();
                $objValidation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                $objValidation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
                $objValidation->setAllowBlank(false);
                $objValidation->setShowInputMessage(true);
                $objValidation->setShowErrorMessage(true);
                $objValidation->setErrorTitle('Input error');
                $objValidation->setError('This Field is Compulsory.');
                $objValidation->setPromptTitle('Insert Reference Contact 2');
                $objValidation->setPrompt('Please insert Maximum 11 digit and Mimimum 10.');

                /*$objValidation = $spreadsheet->getActiveSheet()->getCell('L' . $i)->getDataValidation();
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

            $spreadsheet->getActiveSheet()->setTitle('PCC Records');
            $spreadsheet->setActiveSheetIndex(0);
            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment;filename=PCC Bulk Uplaod Template.xlsx");
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

            $json_array['file_name'] = "PCC  Bulk Uplaod Template";

            $json_array['message'] = "File downloaded successfully,please check in download folder";

            $json_array['status'] = SUCCESS_CODE;
        } else {
            $json_array['file_name'] = "PCC Bulk Uplaod Template";

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

            $all_records = $this->pcc_verificatiion_model->get_all_pcc_by_client($client_id, $fil_by_status, $from_date, $to_date);

            require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
            // Create new Spreadsheet object
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

            // Set document properties
            $spreadsheet->getProperties()->setCreator(CRMNAME)
                ->setLastModifiedBy(CRMNAME)
                ->setTitle(CRMNAME)
                ->setSubject('PCC records')
                ->setDescription('PCC records with their status');

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
            $spreadsheet->getActiveSheet()->getStyle('A1:Z1')->applyFromArray($styleArray);
            // auto fit column to content
            foreach (range('A', 'Z') as $columnID) {
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
                ->setCellValue("L1", 'Address')
                ->setCellValue("M1", 'City')
                ->setCellValue("N1", 'Pincode')
                ->setCellValue("O1", 'State')
                ->setCellValue("P1", 'Status')
                ->setCellValue("Q1", 'Sub Status')
                ->setCellValue("R1", 'Executive Name')
                ->setCellValue("S1", 'Vendor')
                ->setCellValue("T1", 'Vendor Status')
                ->setCellValue("U1", 'Vendor Assigned on')
                ->setCellValue("V1", 'Closure Date')
                ->setCellValue("W1", 'Insuff Raised Date')
                ->setCellValue("X1", 'Insuff Clear Date')
                ->setCellValue("Y1", 'Insuff Remark')
                ->setCellValue("Z1", 'Gender');
            // Add some data
            $x = 2;
            foreach ($all_records as $all_record) {

                $PCC_status = ($all_record['verfstatus'] != "") ? $all_record['verfstatus'] : "WIP";
                $PCC_filter_status = ($all_record['filter_status'] != "") ? $all_record['filter_status'] : "WIP";
                $closuredate = ($all_record['filter_status'] == "Closed") ? convert_db_to_display_date($all_record['closuredate']) : "NA";
                $insuff_remarks = $all_record['insuff_raise_remark'];

                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A$x", ucwords($all_record['clientname']))
                    ->setCellValue("B$x", ucwords($all_record['entity_name']))
                    ->setCellValue("C$x", ucwords($all_record['package_name']))
                    ->setCellValue("D$x", $all_record['cmp_ref_no'])
                    ->setCellValue("E$x", $all_record['pcc_com_ref'])
                    ->setCellValue("F$x", $all_record['ClientRefNumber'])
                    ->setCellValue("G$x", $all_record['transaction_id'])
                    ->setCellValue("H$x", convert_db_to_display_date($all_record['iniated_date']))
                    ->setCellValue("I$x", ucwords($all_record['CandidateName']))
                    ->setCellValue("J$x", ucwords($all_record['NameofCandidateFather']))
                    ->setCellValue("K$x", convert_db_to_display_date($all_record['DateofBirth']))
                    ->setCellValue("L$x", $all_record['street_address'])
                    ->setCellValue("M$x", $all_record['city'])
                    ->setCellValue("N$x", $all_record['pincode'])
                    ->setCellValue("O$x", $all_record['state'])
                    ->setCellValue("P$x", $PCC_filter_status)
                    ->setCellValue("Q$x", $PCC_status)
                    ->setCellValue("R$x", $all_record['executive_name'])
                    ->setCellValue("S$x", $all_record['vendor_name'])
                    ->setCellValue("T$x", ucwords($all_record['vendor_status']))
                    ->setCellValue("U$x", convert_db_to_display_date($all_record['vendor_assgined_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12))
                    ->setCellValue("V$x", $closuredate)
                    ->setCellValue("W$x", $all_record['insuff_raised_date'])
                    ->setCellValue("X$x", $all_record['insuff_clear_date'])
                    ->setCellValue("Y$x", $insuff_remarks)
                    ->setCellValue("Z$x", $all_record['gender']);
                $x++;
            }
            // Rename worksheet
            $spreadsheet->getActiveSheet()->setTitle('PCC Records');

            $spreadsheet->setActiveSheetIndex(0);

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment;filename=PCC Records of $client_name.xlsx");
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

            $json_array['file_name'] = "PCC Records of $client_name";

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
            $result = $this->pcc_model->save_update_pcc_files(array('status' => 2), array('id' => $id));

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
}
