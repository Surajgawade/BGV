<?php defined('BASEPATH') or exit('No direct script access allowed');
class Pcc extends MY_Candidate_Cotroller
{

    public function __construct()
    {
        parent::__construct();

        if (!$this->is_candidate_logged_in()) {
            redirect('candidates_info/login');
            exit();
        }

        $this->perm_array = array('direct_access' => true);
        $this->load->model(array('candidate_info/pcc_model'));
    }

    public function list_view()
    {
        $data['header_title'] = "PCC Pending List";

        $this->load->view('candidate_info/header', $data);

        $this->load->view('candidate_info/pcc_list');

        $this->load->view('candidate_info/footer');
    }

    public function submitted_list_view()
    {
        $data['header_title'] = "PCC Submit List";

        $this->load->view('candidate_info/header', $data);

        $this->load->view('candidate_info/pcc_submit_list');

        $this->load->view('candidate_info/footer');
    }

    public function pcc_view()
    {

        $params = $add_candidates = $data_arry = $columns = array();

        $params = $_REQUEST;

        $columns = array('addrver_id.id', 'candidates_info.ClientRefNumber', 'candidates_info.cmp_ref_no', 'company_database.coname', 'candidates_info.CandidateName', 'candidates_info.caserecddate', 'verfstatus', 'city', 'encry_id', 'address', 'pincode', 'state', 'check_closure_date', 'add_com_ref', 'iniated_date');

        $where_arry = array();

        $add_address = $this->pcc_model->get_all_pcc_record_datatable($params, $columns);

        $totalRecords = count($this->pcc_model->get_all_pcc_record_datatable_count($params, $columns));

        $x = 0;

        foreach ($add_address as $pcc_candidate) {
            $data_arry[$x]['id'] = $x + 1;
            $data_arry[$x]['checkbox'] = $pcc_candidate['id'];
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
            // $data_arry[$x]['first_qc_approve'] = $pcc_candidate['first_qc_approve'];
            $data_arry[$x]['executive_name'] = $pcc_candidate['user_name'];
            $data_arry[$x]['caserecddate'] = convert_db_to_display_date($pcc_candidate['caserecddate']);
            $data_arry[$x]['last_activity_date'] = $pcc_candidate['last_activity_date'];
            $data_arry[$x]['closuredate'] = convert_db_to_display_date($pcc_candidate['closuredate']);
            $data_arry[$x]['remarks'] = $pcc_candidate['remarks'];
            $data_arry[$x]['tat_status'] = $pcc_candidate['tat_status'];
            $data_arry[$x]['due_date'] = convert_db_to_display_date($pcc_candidate['due_date']);
            $data_arry[$x]['mode_of_veri'] = $pcc_candidate['mode_of_veri'];
            $data_arry[$x]['encry_id'] = CANDIDATE_SITE_URL . "pcc/add/" . $pcc_candidate['candidates_info_id'] . "/" . $pcc_candidate['pcc_id'];
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

    public function pcc_submit_view()
    {

        $params = $add_candidates = $data_arry = $columns = array();

        $params = $_REQUEST;

        $columns = array('addrver_id.id', 'candidates_info.ClientRefNumber', 'candidates_info.cmp_ref_no', 'company_database.coname', 'candidates_info.CandidateName', 'candidates_info.caserecddate', 'verfstatus', 'city', 'encry_id', 'address', 'pincode', 'state', 'check_closure_date', 'add_com_ref', 'iniated_date');

        $where_arry = array();

        $add_address = $this->pcc_model->get_all_pcc_submit_by_candidate_datatable($params, $columns);

        $totalRecords = count($this->pcc_model->get_all_pcc_submit_by_candidate_datatable_count($params, $columns));

        $x = 0;

        foreach ($add_address as $pcc_candidate) {
            $data_arry[$x]['id'] = $x + 1;
            $data_arry[$x]['checkbox'] = $pcc_candidate['id'];
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
            // $data_arry[$x]['first_qc_approve'] = $pcc_candidate['first_qc_approve'];
            $data_arry[$x]['executive_name'] = $pcc_candidate['user_name'];
            $data_arry[$x]['caserecddate'] = convert_db_to_display_date($pcc_candidate['caserecddate']);
            $data_arry[$x]['last_activity_date'] = $pcc_candidate['last_activity_date'];
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

    public function add($candsid, $id)
    {

        $this->load->model('Pcc_verificatiion_model');

        if ($this->input->is_ajax_request()) {

            $data['get_cands_details'] = $this->candidate_entity_pack_details($candsid);
            $data['states'] = $this->get_states();
            $data['id'] = $id;

            $data['details'] = $this->Pcc_verificatiion_model->get_all_pcc_record(array('pcc.id' => $id));

            $data['mode_of_verification'] = $this->Pcc_verificatiion_model->get_mode_of_verification(array('entity' => $data['get_cands_details']['entity_id'], 'package' => $data['get_cands_details']['package_id'], 'tbl_clients_id' => $data['get_cands_details']['clientid']));

            echo $this->load->view('candidate_info/pcc_add', $data, true);
        } else {
            echo "<h3>Something went wrong, please try again</h3>";
        }
    }

    public function save_pcc()
    {
        if ($this->input->is_ajax_request()) {

            $frm_details = $this->input->post();

            $this->form_validation->set_rules('clientid', 'Client', 'required');

            $this->form_validation->set_rules('candsid', 'Candidate', 'required');

            if ($this->form_validation->run() == false) {

                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');
            } else {

                $error_msgs = array();
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
                    'iniated_date' => convert_display_to_db_date($frm_details['iniated_date']),
                    'references' => json_encode(array_filter($frm_details['references'])),
                    'references_no' => json_encode(array_filter($frm_details['references_no'])),
                    'street_address' => $frm_details['street_address'],
                    'city' => $frm_details['city'],
                    'pincode' => $frm_details['pincode'],
                    'state' => $frm_details['state'],
                    'mode_of_veri' => $frm_details['mod_of_veri'],
                    'created_on' => date(DB_DATE_FORMAT),
                    'modified_on' => date(DB_DATE_FORMAT),
                    'is_bulk_uploaded' => 0,
                    'has_case_id' => 13,
                    "due_date" => $closed_date,
                    "tat_status" => "IN TAT",
                );

                $result = $this->pcc_model->save(array_map('strtolower', $field_array), array('pcc.id' => $frm_details['pcc_id']));

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
                }

                if ($result) {

                    $check = $this->pcc_model->select_insuff(array('pcc_id' => $frm_details['pcc_id'], 'status' => 1));

                    if (!empty($check)) {
                        $result = $this->pcc_model->save_update_insuff(array('insuff_clear_date' => date('Y-m-d'), 'insuff_cleared_timestamp' => date(DB_DATE_FORMAT), 'insuff_remarks' => 'Auto - Candidate has updated the info', 'status' => 2, 'pcc_id' => $frm_details['pcc_id'], 'auto_stamp' => 2, 'insuff_cleared_by' => "Candidate"));

                    }

                    $result_update_record = $this->pcc_model->save_update(array('verfstatus' => 1, 'var_filter_status' => 'WIP', 'var_report_status' => 'WIP'), array('pcc_result.pcc_id' => $frm_details['pcc_id']));

                    auto_update_tat_status($frm_details['candsid']);

                    auto_update_overall_status($frm_details['candsid']);

                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'PCC Record Successfully Inserted';

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
