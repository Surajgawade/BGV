<?php defined('BASEPATH') or exit('No direct script access allowed');
class Identity extends MY_Candidate_Cotroller
{

    public function __construct()
    {
        parent::__construct();

        if (!$this->is_candidate_logged_in()) {
            redirect('candidates_info/login');
            exit();
        }

        $this->perm_array = array('direct_access' => true);
        $this->load->model(array('candidate_info/identity_model'));
    }

    public function list_view()
    {
        $data['header_title'] = "Identity Pending List";

        $this->load->view('candidate_info/header', $data);

        $this->load->view('candidate_info/Identity_list');

        $this->load->view('candidate_info/footer');
    }

    public function submitted_list_view()
    {
        $data['header_title'] = "Identity Submit List";

        $this->load->view('candidate_info/header', $data);

        $this->load->view('candidate_info/identity_submit_list');

        $this->load->view('candidate_info/footer');
    }

    public function identity_view()
    {

        $params = $add_candidates = $data_arry = $columns = array();

        $params = $_REQUEST;

        $columns = array('addrver_id.id', 'candidates_info.ClientRefNumber', 'candidates_info.cmp_ref_no', 'company_database.coname', 'candidates_info.CandidateName', 'candidates_info.caserecddate', 'verfstatus', 'city', 'encry_id', 'address', 'pincode', 'state', 'check_closure_date', 'add_com_ref', 'iniated_date');

        $where_arry = array();

        $add_address = $this->identity_model->get_all_identity_record_datatable($params, $columns);

        $totalRecords = count($this->identity_model->get_all_identity_record_datatable_count($params, $columns));

        $x = 0;

        foreach ($add_address as $court_candidate) {
            $data_arry[$x]['id'] = $x + 1;
            $data_arry[$x]['checkbox'] = $court_candidate['id'];
            $data_arry[$x]['ClientRefNumber'] = $court_candidate['ClientRefNumber'];
            $data_arry[$x]['cmp_ref_no'] = $court_candidate['cmp_ref_no'];
            $data_arry[$x]['CandidateName'] = $court_candidate['CandidateName'];
            $data_arry[$x]['identity_com_ref'] = $court_candidate['identity_com_ref'];
            $data_arry[$x]['iniated_date'] = convert_db_to_display_date($court_candidate['iniated_date']);
            $data_arry[$x]['clientname'] = $court_candidate['clientname'];
            // $data_arry[$x]['first_qc_approve'] = $court_candidate['first_qc_approve'];
            $data_arry[$x]['verfstatus'] = $court_candidate['status_value'];
            $data_arry[$x]['executive_name'] = $court_candidate['user_name'];

            $data_arry[$x]['id_number'] = $court_candidate['id_number'];

            $data_arry[$x]['caserecddate'] = convert_db_to_display_date($court_candidate['caserecddate']);
            $data_arry[$x]['last_activity_date'] = $court_candidate['last_activity_date'];
            $data_arry[$x]['closuredate'] = convert_db_to_display_date($court_candidate['closuredate']);
            $data_arry[$x]['remarks'] = $court_candidate['remarks'];
            $data_arry[$x]['tat_status'] = $court_candidate['tat_status'];
            $data_arry[$x]['due_date'] = convert_db_to_display_date($court_candidate['due_date']);
            $data_arry[$x]['mode_of_veri'] = $court_candidate['mode_of_veri'];
            $data_arry[$x]['encry_id'] = CANDIDATE_SITE_URL . "identity/add/" . $court_candidate['candidates_info_id'] . "/" . $court_candidate['identity_id'];
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

    public function identity_submit_view()
    {

        $params = $add_candidates = $data_arry = $columns = array();

        $params = $_REQUEST;

        $columns = array('addrver_id.id', 'candidates_info.ClientRefNumber', 'candidates_info.cmp_ref_no', 'company_database.coname', 'candidates_info.CandidateName', 'candidates_info.caserecddate', 'verfstatus', 'city', 'encry_id', 'address', 'pincode', 'state', 'check_closure_date', 'add_com_ref', 'iniated_date');

        $where_arry = array();

        $add_address = $this->identity_model->get_all_identity_submit_record_datatable($params, $columns);

        $totalRecords = count($this->identity_model->get_all_identity_submit_record_datatable_count($params, $columns));

        $x = 0;

        foreach ($add_address as $court_candidate) {
            $data_arry[$x]['id'] = $x + 1;
            $data_arry[$x]['checkbox'] = $court_candidate['id'];
            $data_arry[$x]['ClientRefNumber'] = $court_candidate['ClientRefNumber'];
            $data_arry[$x]['cmp_ref_no'] = $court_candidate['cmp_ref_no'];
            $data_arry[$x]['CandidateName'] = $court_candidate['CandidateName'];
            $data_arry[$x]['identity_com_ref'] = $court_candidate['identity_com_ref'];
            $data_arry[$x]['iniated_date'] = convert_db_to_display_date($court_candidate['iniated_date']);
            $data_arry[$x]['clientname'] = $court_candidate['clientname'];
            // $data_arry[$x]['first_qc_approve'] = $court_candidate['first_qc_approve'];
            $data_arry[$x]['verfstatus'] = $court_candidate['status_value'];
            $data_arry[$x]['executive_name'] = $court_candidate['user_name'];

            $data_arry[$x]['id_number'] = $court_candidate['id_number'];

            $data_arry[$x]['caserecddate'] = convert_db_to_display_date($court_candidate['caserecddate']);
            $data_arry[$x]['last_activity_date'] = $court_candidate['last_activity_date'];
            $data_arry[$x]['closuredate'] = convert_db_to_display_date($court_candidate['closuredate']);
            $data_arry[$x]['remarks'] = $court_candidate['remarks'];
            $data_arry[$x]['tat_status'] = $court_candidate['tat_status'];
            $data_arry[$x]['due_date'] = convert_db_to_display_date($court_candidate['due_date']);
            $data_arry[$x]['mode_of_veri'] = $court_candidate['mode_of_veri'];
            $data_arry[$x]['encry_id'] = CANDIDATE_SITE_URL . "identity/add/" . $court_candidate['candidates_info_id'];
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

    public function add($candsid, $id)
    {

        $this->load->model('Identity_model');

        if ($this->input->is_ajax_request()) {

            $data['get_cands_details'] = $this->candidate_entity_pack_details($candsid);
            $data['states'] = $this->get_states();
            $data['id'] = $id;

            $data['details'] = $this->Identity_model->get_all_identity_record(array('identity.id' => $id));

            $data['mode_of_verification'] = $this->Identity_model->get_mode_of_verification(array('entity' => $data['get_cands_details']['entity_id'], 'package' => $data['get_cands_details']['package_id'], 'tbl_clients_id' => $data['get_cands_details']['clientid']));

            echo $this->load->view('candidate_info/identity_add', $data, true);
        } else {
            echo "<h3>Something went wrong, please try again</h3>";
        }
    }

    public function save_identity()
    {
        if ($this->input->is_ajax_request()) {

            $frm_details = $this->input->post();

            $this->form_validation->set_rules('clientid', 'Client', 'required');

            //$this->form_validation->set_rules('add_com_ref', 'Address Component','required');

            $this->form_validation->set_rules('candsid', 'Candidate', 'required');

            if ($this->form_validation->run() == false) {

                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');
            } else {

                $error_msgs = array();
                $file_upload_path = SITE_BASE_PATH . IDENTITY . $frm_details['clientid'];
                if (!folder_exist($file_upload_path)) {
                    mkdir($file_upload_path, 0777);
                } else if (!is_writable($file_upload_path)) {
                    array_push($error_msgs, 'Problem while uploading');
                }

                $tat_day = $this->get_client_entity_package_tat_day(array('tbl_clients_id' => $frm_details['clientid'], 'entity' => $frm_details['entity_id'], 'package' => $frm_details['package_id']));

                $get_holiday1 = $this->get_holiday();

                $get_holiday = array_map('current', $get_holiday1);

                $closed_date = getWorkingDays(convert_display_to_db_date($frm_details['iniated_date']), $get_holiday, $tat_day[0]['tat_identity']);

                $field_array = array('clientid' => $frm_details['clientid'],
                    'candsid' => $frm_details['candsid'],
                    'iniated_date' => convert_display_to_db_date($frm_details['iniated_date']),
                    'doc_submited' => $frm_details['doc_submited'],
                    'id_number' => $frm_details['id_number'],
                    'mode_of_veri' => $frm_details['mod_of_veri'],
                    'created_on' => date(DB_DATE_FORMAT),
                    'modified_on' => date(DB_DATE_FORMAT),
                    'is_bulk_uploaded' => 0,
                    'has_case_id' => 13,
                    "due_date" => $closed_date,
                    "tat_status" => "IN TAT",
                );

                $result = $this->identity_model->save(array_map('strtolower', $field_array), array('identity.id' => $frm_details['identity_id']));

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
                }

                if ($result) {

                    $check = $this->identity_model->select_insuff(array('identity_id' => $frm_details['identity_id'], 'status' => 1));

                    if (!empty($check)) {
                        $result = $this->identity_model->save_update_insuff(array('insuff_clear_date' => date('Y-m-d'), 'insuff_cleared_timestamp' => date(DB_DATE_FORMAT), 'insuff_remarks' => 'Auto - Candidate has updated the info', 'status' => 2, 'identity_id' => $frm_details['identity_id'], 'auto_stamp' => 2, 'insuff_cleared_by' => "Candidate"), array('identity_id' => $frm_details['identity_id']));

                    }

                    $result_update_record = $this->identity_model->save_update(array('verfstatus' => 1, 'var_filter_status' => 'WIP', 'var_report_status' => 'WIP'), array('identity_id' => $frm_details['identity_id']));

                    auto_update_tat_status($frm_details['candsid']);

                    auto_update_overall_status($frm_details['candsid']);

                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'Identity Record Successfully Inserted';

                    $json_array['redirect'] = ADMIN_SITE_URL . 'address';
                } else {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Something went wrong,please try again';
                }
                echo_json($json_array);
            }
        }
    }

}
