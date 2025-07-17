<?php defined('BASEPATH') or exit('No direct script access allowed');

class Cg extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!$this->is_admin_logged_in()) {
            redirect('admin/login');
            exit();
        }

        $this->perm_array = array('page_id' => 47);
        $this->assign_options = array('0' => 'Select', '1' => 'Assign to Executive');
        $this->load->model(array('Cg_model'));
    }

    public function index()
    {
        $data['header_title'] = "CG Lists";

        $data['filter_view'] = $this->filter_view();

        $data['vendor_list'] = $this->vendor_list('cg');

        $this->load->view('admin/header', $data);

        $this->load->view('admin/cg_collection_list');

        $this->load->view('admin/footer');
    }

    public function cg_view_datatable()
    {
        $params = $records = $data_arry = $columns = array();

        $params = $_REQUEST;

        $columns = array('cg.id', 'ClientRefNumber', 'CGRefNumber', 'company_customer_name', 'iniated_date', 'last_modified', 'encry_id', 'verfstatus', 'street_address', 'closuredate', 'remarks');

        $records = $this->Cg_model->get_all_cg_record_datatable(false, $params, $columns);

        $totalRecords = count($this->Cg_model->get_all_cg_record_datatable_count(false, $params, $columns));

        $x = 0;
        foreach ($records as $record) {
            $data_arry[$x]['id'] = $x + 1;
            $data_arry[$x]['checkbox'] = $record['cg_id'];
            $data_arry[$x]['ClientRefNumber'] = $record['ClientRefNumber'];
            $data_arry[$x]['cg_ref_no'] = $record['cg_ref_no'];
            $data_arry[$x]['company_customer_name'] = $record['company_customer_name'];
            $data_arry[$x]['iniated_date'] = convert_db_to_display_date($record['iniated_date']);
            $data_arry[$x]['vendor'] = '';
            $data_arry[$x]['clientname'] = $record['clientname'];
            $data_arry[$x]['verfstatus'] = $record['status_value'];
            $data_arry[$x]['executive_name'] = $record['user_name'];
            $data_arry[$x]['last_activity_date'] = $record['last_activity_date'];
            $data_arry[$x]['closuredate'] = convert_db_to_display_date($record['closuredate']);
            $data_arry[$x]['remarks'] = $record['remarks'];
            $data_arry[$x]['tat_status'] = $record['tat_status'];
            $data_arry[$x]['due_date'] = convert_db_to_display_date($record['due_date']);
            $data_arry[$x]['encry_id'] = ADMIN_SITE_URL . "cg/view_details/" . encrypt($record['cg_id']);
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

    public function add()
    {
        $data['clients'] = $this->get_clients(array('status' => STATUS_ACTIVE));

        $data['states'] = $this->get_states('state');

        $data['CGRefNumber'] = $this->CGRefNumber();

        $data['assigned_user_id'] = $this->users_list();

        $this->load->view('admin/header', $data);

        $this->load->view('admin/cg_add', $data);

        $this->load->view('admin/footer');
    }

    protected function CGRefNumber()
    {
        $lists = $this->Cg_model->cg_ref_no();

        $number = (!empty($lists)) ? $lists['A_I'] + 1 : 1000;

        return COMPONENT_REF_NO['CG'] . $number;
    }

    public function save_form()
    {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('clientid', 'Client', 'required');

            $this->form_validation->set_rules('entity', 'Entity', 'required');

            $this->form_validation->set_rules('package', 'Package', 'required');

            $this->form_validation->set_rules('has_case_id', 'Package', 'required');

            $this->form_validation->set_rules('ClientRefNumber', 'CLient Ref Number', 'required');

            $this->form_validation->set_rules('CGRefNumber', 'KYC Ref Number', 'required');

            if ($this->form_validation->run() == false) {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');
            } else {
                $frm_details = $this->input->post();

                $file_upload_path = SITE_BASE_PATH . UPLOAD_FOLDER . KYC . $frm_details['clientid'];
                if (!folder_exist($file_upload_path)) {
                    mkdir($file_upload_path, 0777);
                } else if (!is_writable($file_upload_path)) {
                    array_push($error_msgs, 'Problem while uploading');
                }

                $field_array = array('clientid' => $frm_details['clientid'],
                    'entity' => $frm_details['entity'],
                    'package' => $frm_details['package'],
                    'ClientRefNumber' => $frm_details['ClientRefNumber'],
                    'cg_ref_no' => $frm_details['CGRefNumber'],
                    'iniated_date' => convert_display_to_db_date($frm_details['iniated_date']),
                    'bill_date' => convert_display_to_db_date($frm_details['bill_date']),
                    'company_customer_name' => $frm_details['company_customer_name'],
                    'street_address' => $frm_details['street_address'],
                    'city' => $frm_details['city'],
                    'pincode' => $frm_details['pincode'],
                    'state' => $frm_details['state'],
                    'remarks' => $frm_details['remarks'],
                    'created_by' => $this->user_info['id'],
                    'created_on' => date(DB_DATE_FORMAT),
                    'has_case_id' => $frm_details['has_case_id'],
                    'is_bulk_uploaded' => 0,
                );

                $result = $this->Cg_model->save(array_map('strtolower', $field_array));

                $config_array = array('file_upload_path' => $file_upload_path, 'file_permission' => 'jpeg|jpg|png|pdf|docx|xls|xlsx', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 1000, 'file_id' => $result, 'component_name' => 'cg_id');
                if (empty($error_msgs)) {
                    if ($_FILES['attchments']['name'][0] != '') {
                        $config_array['files_count'] = count($_FILES['attchments']['name']);
                        $config_array['file_data'] = $_FILES['attchments'];
                        $config_array['type'] = 0;
                        $retunr_de = $this->file_upload_multiple($config_array);
                        if (!empty($retunr_de)) {
                            $this->common_model->common_insert_batch('cg_files', $retunr_de['success']);
                        }
                    }
                }

                if ($result) {
                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'Record Successfully Inserted';

                    $json_array['redirect'] = ADMIN_SITE_URL . 'cg';
                } else {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Something went wrong,please try again';
                }
                echo_json($json_array);
            }
        }
    }

    public function is_client_ref_exists()
    {
        if ($this->input->is_ajax_request()) {
            $ClientRefNumber = $this->input->post('ClientRefNumber');

            $lists = $this->KYC_collection_model->select(true, array('id'), array('ClientRefNumber' => $ClientRefNumber));
            if (empty($lists)) {
                echo 'true';
            } else {
                echo 'false';
            }
        }
    }

    public function view_details($kyc_id = '')
    {
        if (!empty($kyc_id)) {
            $details = $this->Cg_model->select(true, array('*'), array('id' => decrypt($kyc_id)));

            if ($kyc_id && !empty($details)) {
                $data['header_title'] = 'CG';

                $data['states'] = $this->get_states('state');

                $data['clients'] = $this->get_clients(array('status' => STATUS_ACTIVE));

                $data['selected_data'] = $details;

                $data['assigned_user_id'] = $this->users_list();

                $check_insuff_raise = $this->Cg_model->select_insuff(array('cg_id' => decrypt($kyc_id), 'status' => STATUS_ACTIVE, 'insuff_clear_date is null' => null));

                $data['insuff_reason_list'] = $this->insuff_reason_list(6);

                $data['insuff_raise_message'] = (!empty($check_insuff_raise) ? 'Insufficiency raised on ' . convert_db_to_display_date($check_insuff_raise[0]['insuff_raised_date']) . ' for ' . $check_insuff_raise[0]['insff_reason'] : '');

                $data['check_insuff_raise'] = ((!empty($check_insuff_raise)) ? 'disabled' : '');
                $data['insuff_raise_id'] = (!empty($check_insuff_raise) ? $check_insuff_raise[0]['id'] : '');

                $this->load->view('admin/header', $data);

                $this->load->view('admin/cg_edit_view');

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
        if ($this->input->is_ajax_request() && $this->permission['access_re_assign']) {
            $frm_details = $this->input->post();

            if ($frm_details['users_list'] != 0 && $frm_details['cases_id'] != "") {
                $return = $this->common_model->update_in('addrver', array('has_case_id' => $frm_details['users_list'], 'has_assigned_on' => date(DB_DATE_FORMAT)), array('where_id' => $frm_details['cases_id']));
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
                foreach ($list as $key => $value) {
                    $files[] = array('kyc_id' => $value,
                        'vendor_id' => $frm_details['vendor_list'],
                        "created_on" => date(DB_DATE_FORMAT),
                        "created_by" => $this->user_info['id'],
                        "status" => 0,
                        "reject_reason" => '',
                        "modified_on" => null,
                        "modified_by" => '',
                    );
                    $update[] = array('id' => $value, 'vendor_id' => $frm_details['vendor_list'], 'vendor_assgined_on' => date(DB_DATE_FORMAT));
                }

                $return = $this->KYC_collection_model->upload_file_update('kyc_collection', $update);
                $return = $this->common_model->common_insert_batch('kyc_vendor_log', $files);
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
        $this->load->model(array('Cg_vendor_log_model'));

        $data['header_title'] = "Vendor Approve List";

        $data['lists'] = $this->Cg_vendor_log_model->get_new_list(array('cg_vendor_log.status' => 0));

        $this->load->view('admin/header', $data);

        $this->load->view('admin/cg_vendor_approve_queue');

        $this->load->view('admin/footer');
    }

    public function view_from_inside($add_comp_id = false)
    {
        if ($this->input->is_ajax_request()) {

            $cget_details = $this->identity_model->get_all_identity_record(array('identity.identity_com_ref' => $add_comp_id));
            if (!empty($cget_details)) {
                $data['get_cands_details'] = $this->candidate_entity_pack_details($cget_details[0]['cands_id']);
                $data['selected_data'] = $cget_details[0];
                $data['states'] = $this->get_states();
                $data['assigned_user_id'] = $this->users_list();
                echo $this->load->view('admin/identity_edit', $data, true);
            } else {
                echo "<h3>Something went wrong, please try again</h3>";
            }
        }
    }

    public function update_form()
    {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('identity_id', 'ID', 'required');

            $this->form_validation->set_rules('has_case_id', 'Assigned To Executive', 'required');

            $this->form_validation->set_rules('clientid', 'Client', 'required');

            $this->form_validation->set_rules('identity_com_ref', 'Component Ref', 'required');

            $this->form_validation->set_rules('candsid', 'Candidate', 'required');

            $this->form_validation->set_rules('street_address', 'Address', 'required');

            $this->form_validation->set_rules('city', 'City', 'required');

            $this->form_validation->set_rules('pincode', 'PIN code', 'required');

            if ($this->form_validation->run() == false) {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');
            } else {
                $frm_details = $this->input->post();

                $file_upload_path = SITE_BASE_PATH . UPLOAD_FOLDER . IDENTITY . $frm_details['clientid'];
                if (!folder_exist($file_upload_path)) {
                    mkdir($file_upload_path, 0777);
                } else if (!is_writable($file_upload_path)) {
                    array_push($error_msgs, 'Problem while uploading');
                }

                $field_array = array('clientid' => $frm_details['clientid'],
                    'candsid' => $frm_details['candsid'],
                    'identity_com_ref' => $frm_details['identity_com_ref'],
                    'iniated_date' => convert_display_to_db_date($frm_details['iniated_date']),
                    'doc_submited' => $frm_details['doc_submited'],
                    'id_number' => $frm_details['id_number'],
                    'street_address' => $frm_details['street_address'],
                    'city' => $frm_details['city'],
                    'pincode' => $frm_details['pincode'],
                    'state' => $frm_details['state'],
                    'modified_on' => date(DB_DATE_FORMAT),
                    'modified_by' => $this->user_info['id'],
                    'has_case_id' => $frm_details['has_case_id'],
                    'has_assigned_on' => date(DB_DATE_FORMAT),
                    'is_bulk_uploaded' => 0,
                );

                $result = $this->identity_model->save(array_map('strtolower', $field_array), array('id' => $frm_details['identity_id']));

                $config_array = array('file_upload_path' => $file_upload_path, 'file_permission' => 'jpeg|jpg|png|pdf|docx|xls|xlsx', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 1000, 'file_id' => $frm_details['identity_id'], 'component_name' => 'identity_id');
                if (empty($error_msgs)) {
                    if ($_FILES['attchments']['name'][0] != '') {
                        $config_array['files_count'] = count($_FILES['attchments']['name']);
                        $config_array['file_data'] = $_FILES['attchments'];
                        $config_array['type'] = 0;
                        $retunr_de = $this->file_upload_multiple($config_array);
                        if (!empty($retunr_de)) {
                            $this->common_model->common_insert_batch('identity_files', $retunr_de['success']);
                        }
                    }

                    if ($_FILES['attchments_cs']['name'][0] != '') {
                        $config_array['files_count'] = count($_FILES['attchments_cs']['name']);
                        $config_array['file_data'] = $_FILES['attchments_cs'];
                        $config_array['type'] = 2;
                        $retunr_cd = $this->file_upload_multiple($config_array);
                        if (!empty($retunr_cd)) {
                            $this->common_model->common_insert_batch('identity_files', $retunr_cd['success']);
                        }
                    }
                }

                if ($result) {
                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'Record Successfully Updated';

                    $json_array['redirect'] = ADMIN_SITE_URL . 'identity';
                } else {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Something went wrong,please try again';
                }
            }
            echo_json($json_array);
        }
    }

    public function add_result_model($where_id)
    {
        $details = $this->identity_model->get_all_identity_record(array('identity.id' => $where_id));

        if ($where_id && !empty($details)) {
            $data['check_insuff_raise'] = '';

            $data['details'] = $details[0];

            echo $this->load->view('admin/identity_add_result_model_view', $data, true);
        } else {
            echo "<h4>Record Not Found</h4>";
        }
    }

    public function add_verificarion_result()
    {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('identity_id', 'ID', 'required');

            if ($this->form_validation->run() == false) {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');
            } else {
                $frm_details = $this->input->post();

                $file_upload_path = SITE_BASE_PATH . UPLOAD_FOLDER . IDENTITY . $frm_details['clientid'];
                if (!folder_exist($file_upload_path)) {
                    mkdir($file_upload_path, 0777);
                } else if (!is_writable($file_upload_path)) {
                    array_push($error_msgs, 'Problem while uploading');
                }

                $check_first_qc = get_group_wise_status(array('components_id' => 6, 'filter_status' => 'Closed'));

                $verfstatus = status_id_frm_db(array('status_value' => $frm_details['action_val'], 'components_id' => 6));

                $field_array = array('verfstatus' => $verfstatus['id'],
                    'closuredate' => convert_display_to_db_date($frm_details['closuredate']),
                    'remarks' => $frm_details['remarks'],
                    'mode_of_verification' => $frm_details['mode_of_verification'],
                    "modified_on" => date(DB_DATE_FORMAT),
                    "modified_by" => $this->user_info['id'],
                    "first_qc_approve" => 'first qc pending',
                    'first_qc_updated_on' => null,
                    'first_qu_reject_reason' => '',
                );
                if (in_array($verfstatus['id'], explode(',', $check_first_qc['status_ids']))) {
                    $field_array['first_qc_approve'] = 'first qc pending';
                    $field_array['first_qc_updated_on'] = date(DB_DATE_FORMAT);
                }

                $field_array = array_map('strtolower', $field_array);

                $result = $this->identity_model->save_update_ver_result($field_array, array('id' => $frm_details['identity_result_id']));

                $config_array = array('file_upload_path' => $file_upload_path, 'file_permission' => 'jpeg|jpg|png|pdf|docx|xls|xlsx', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 1000, 'file_id' => $frm_details['identity_result_id'], 'component_name' => 'identity_id');
                if (empty($error_msgs)) {
                    if ($_FILES['attchments_ver']['name'][0] != '') {
                        $config_array['files_count'] = count($_FILES['attchments_ver']['name']);
                        $config_array['file_data'] = $_FILES['attchments_ver'];
                        $config_array['type'] = 0;
                        $retunr_de = $this->file_upload_multiple($config_array);
                        if (!empty($retunr_de)) {
                            $this->common_model->common_insert_batch('identity_files', $retunr_de['success']);
                        }
                    }
                }

                if ($result) {
                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'Record Successfully Updated';

                    $json_array['redirect'] = ADMIN_SITE_URL . 'identity';
                } else {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Something went wrong,please try again';
                }
            }
            echo_json($json_array);
        }
    }

    public function insuff_raised()
    {
        $json_array = array();
        $json_array['status'] = ERROR_CODE;
        $json_array['message'] = 'Something went wrong,please try again';

        if ($this->input->is_ajax_request()) {
            $identity_id = $this->input->post('update_id');

            $insff_reason = $this->input->post('insff_reason');

            $check = $this->identity_model->select_insuff(array('identity_id' => $identity_id, 'status !=' => 3, 'insuff_clear_date is null' => null));

            if (empty($check)) {
                $result = $this->identity_model->save_update_insuff(array('insuff_raised_date' => $this->insuff_date_db, 'insuff_raise_remark' => $this->input->post('insuff_raise_remark'), 'status' => STATUS_ACTIVE, 'insff_reason' => $insff_reason, 'created_by' => $this->user_info['id'], 'identity_id' => $identity_id, 'auto_stamp' => 1));
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
            $data['insuff_details'] = $this->identity_model->select_insuff_join(array('identity_id' => $emp_id));

            echo $this->load->view('admin/employment_insuff_view', $data, true);
        } else {
            echo "<p>Something went wrong, please try again.</p>";
        }
    }

    public function insuff_raised_data()
    {
        if ($this->input->is_ajax_request()) {
            $insuff_data = $this->input->post('insuff_data');

            $result = $this->identity_model->select_insuff(array('id' => $insuff_data));
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

            $result = $this->identity_model->select_insuff(array('id' => $insuff_data));

            if (!empty($result)) {
                $data['insuff_reason_list'] = $this->insuff_reason_list(6);
                $data['insuff_details'] = $result[0];
                echo $this->load->view('admin/insuff_edit_and_clear_view', $data, true);
            }
        }
    }

    public function insuff_clear()
    {
        $json_array = array();

        $frm_details = $this->input->post();

        if ($this->input->is_ajax_request() && $this->permission['access_insuff_clear'] == 1) {
            if ($frm_details['insuff_clear_date'] >= $frm_details['check_insuff_raise']) {
                $insuff_date = $frm_details['insuff_clear_date'];

                $hold_days = getNetWorkDays($frm_details['check_insuff_raise'], $frm_details['insuff_clear_date']);

                $fields = array('insuff_clear_date' => convert_display_to_db_date($insuff_date), 'insuff_remarks' => $frm_details['insuff_remarks'], 'insuff_cleared_timestamp' => date(DB_DATE_FORMAT), 'status' => 2, 'insuff_cleared_by' => $this->user_info['id'], 'auto_stamp' => 2, 'hold_days' => $hold_days);

                $result = $this->identity_model->save_update_insuff($fields, array('id' => $frm_details['insuff_clear_id']));

                $error_msgs = $file_array = array();

                $file_upload_path = SITE_BASE_PATH . UPLOAD_FOLDER . ADDRESS . $frm_details['clear_clientid'];

                if (!folder_exist($file_upload_path)) {
                    mkdir($file_upload_path, 0777);
                }

                if (!empty($_FILES['clear_attchments']['name'][0])) {
                    $files_count = count($_FILES['clear_attchments']['name']);

                    for ($i = 0; $i < $files_count; $i++) {
                        $file_name = $_FILES['clear_attchments']['name'][$i];

                        $file_info = pathinfo($file_name);

                        $new_file_name = preg_replace('/[[:space:]]+/', '_', $file_info['filename']);

                        $new_file_name = $new_file_name . '_' . DATE(UPLOAD_FILE_DATE_FORMAT);

                        $new_file_name = str_replace('.', '_', $new_file_name);

                        $file_extension = $file_info['extension'];

                        $new_file_name = $new_file_name . '.' . $file_extension;

                        $_FILES['attchment']['name'] = $new_file_name;

                        $_FILES['attchment']['tmp_name'] = $_FILES['clear_attchments']['tmp_name'][$i];

                        $_FILES['attchment']['error'] = $_FILES['clear_attchments']['error'][$i];

                        $_FILES['attchment']['size'] = $_FILES['clear_attchments']['size'][$i];

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
                                'addrver_id' => $frm_details['clear_update_id'],
                                'type' => 1)
                            );
                        } else {
                            array_push($error_msgs, $this->upload->display_errors('', ''));

                            $json_array['status'] = ERROR_CODE;

                            $json_array['e_message'] = implode('<br>', $error_msgs);

                        }
                    }

                    if (!empty($file_array)) {
                        $this->identity_model->uploaded_files($file_array);
                    }
                }

                if ($result) {
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
        if ($this->input->is_ajax_request() && $this->permission['access_insuff_delete'] == 1) {
            $fields = array('status' => 3, 'modified_on' => date(DB_DATE_FORMAT), 'modified_by' => $this->user_info['id']);

            $result = $this->identity_model->save_update_insuff($fields, array('id' => $insuff_data));

            if ($result) {
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

            $result = $this->identity_model->save_update_insuff($fields, array('id' => $frm_details['id']));

            if ($result) {
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
}
