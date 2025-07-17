<?php defined('BASEPATH') or exit('No direct script access allowed');
class Employment extends MY_Candidate_Cotroller
{

    public function __construct()
    {
        parent::__construct();

        if (!$this->is_candidate_logged_in()) {
            redirect('candidates_info/login');
            exit();
        }

        $this->perm_array = array('direct_access' => true);
        $this->load->model(array('candidate_info/employment_model'));

        $this->employment_type = array('' => 'Select', 'full time' => 'Full time', 'contractual' => 'Contractual', 'part time' => 'Part time');
    }

    public function list_view()
    {
        $data['header_title'] = "Employment Pending List";

        $this->load->view('candidate_info/header', $data);

        $this->load->view('candidate_info/Employment_list');

        $this->load->view('candidate_info/footer');
    }

    public function submitted_list_view()
    {
        $data['header_title'] = "Employment Submit List";

        $this->load->view('candidate_info/header', $data);

        $this->load->view('candidate_info/employment_submit_list');

        $this->load->view('candidate_info/footer');
    }

    public function Employment_view()
    {

        $params = $add_candidates = $data_arry = $columns = array();

        $params = $_REQUEST;

        $columns = array('employment_id.id', 'candidates_info.ClientRefNumber', 'candidates_info.cmp_ref_no', 'company_database.coname', 'candidates_info.CandidateName', 'candidates_info.caserecddate', 'verfstatus', 'city', 'encry_id', 'address', 'pincode', 'state', 'check_closure_date', 'emp_com_ref', 'iniated_date');

        $where_arry = array();

        $add_address = $this->employment_model->get_all_employment_by_candidate_datatable($params, $columns);

        $totalRecords = count($this->employment_model->get_all_employment_by_candidate_datatable_count($params, $columns));

        $x = 0;

        foreach ($add_address as $employment_list) {
            $data_arry[$x]['id'] = $x + 1;
            $data_arry[$x]['checkbox'] = $employment_list['id'];
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
            $data_arry[$x]['encry_id'] = CANDIDATE_SITE_URL . "employment/add/" . $employment_list['candidates_info_id'] . "/" . $employment_list['id'];
            // $data_arry[$x]['edit_access'] = $this->permission['access_employment_list_edit'];
            $data_arry[$x]['verfstatus'] = $employment_list['status_value'];
            //  $data_arry[$x]['executive_name'] = $employment_list['user_name'];
            $data_arry[$x]['has_assigned_on'] = convert_db_to_display_date($employment_list['has_assigned_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);
            $data_arry[$x]['last_activity_date'] = $employment_list['last_activity_date'];
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

    }

    public function Employment_submit_view()
    {

        $params = $add_candidates = $data_arry = $columns = array();

        $params = $_REQUEST;

        $columns = array('employment_id.id', 'candidates_info.ClientRefNumber', 'candidates_info.cmp_ref_no', 'company_database.coname', 'candidates_info.CandidateName', 'candidates_info.caserecddate', 'verfstatus', 'city', 'encry_id', 'address', 'pincode', 'state', 'check_closure_date', 'emp_com_ref', 'iniated_date');

        $where_arry = array();

        $add_address = $this->employment_model->get_all_employment_submit_by_candidate_datatable($params, $columns);

        $totalRecords = count($this->employment_model->get_all_employment_submit_by_candidate_datatable_count($params, $columns));

        $x = 0;

        foreach ($add_address as $employment_list) {
            $data_arry[$x]['id'] = $x + 1;
            $data_arry[$x]['checkbox'] = $employment_list['id'];
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
            $data_arry[$x]['last_activity_date'] = $employment_list['last_activity_date'];
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

    }

    public function add($candsid, $id)
    {

        $this->load->model('Employment_model');

        if ($this->input->is_ajax_request()) {

            $data['get_cands_details'] = $this->candidate_entity_pack_details($candsid);
            $data['states'] = $this->get_states();
            $data['company_list'] = $this->get_company_list();
            $data['id'] = $id;

            $data['details'] = $this->Employment_model->get_employer_details(array('empver.id' => $id));

            $data['mode_of_verification'] = $this->Employment_model->get_mode_of_verification(array('entity' => $data['get_cands_details']['entity_id'], 'package' => $data['get_cands_details']['package_id'], 'tbl_clients_id' => $data['get_cands_details']['clientid']));

            echo $this->load->view('candidate_info/employment_add', $data, true);
        } else {
            echo "<h3>Something went wrong, please try again</h3>";
        }
    }

    public function save_employment()
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

                $check_company_exists = $this->employment_model->check_company_exist($frm_details['nameofthecompany']);

                if (empty($check_company_exists)) {
                    $company_id = $this->employment_model->save_company_details(array("coname" => $frm_details["nameofthecompany"], 'created_on' => date(DB_DATE_FORMAT), 'modified_on' => date(DB_DATE_FORMAT), 'status' => STATUS_ACTIVE));

                } else {
                    $company_id = $check_company_exists[0]['id'];

                }

                $error_msgs = array();
                $file_upload_path = SITE_BASE_PATH . EMPLOYMENT . $frm_details['clientid'];
                if (!folder_exist($file_upload_path)) {
                    mkdir($file_upload_path, 0777);
                } else if (!is_writable($file_upload_path)) {
                    array_push($error_msgs, 'Problem while uploading');
                }

                $tat_day = $this->get_client_entity_package_tat_day(array('tbl_clients_id' => $frm_details['clientid'], 'entity' => $frm_details['entity_id'], 'package' => $frm_details['package_id']));

                $get_holiday1 = $this->get_holiday();

                $get_holiday = array_map('current', $get_holiday1);

                $closed_date = getWorkingDays(convert_display_to_db_date($frm_details['iniated_date']), $get_holiday, $tat_day[0]['tat_empver']);

                $get_executive_id = $this->employment_model->select_executive_id();

                if ($get_executive_id[0]['rr_id'] == 1) {
                    $executive_id = $get_executive_id[0]['id'];
                } elseif ($get_executive_id[1]['rr_id'] == 1) {
                    $executive_id = $get_executive_id[1]['id'];
                }

                if ($get_executive_id[0]['rr_id'] == 0) {
                    $not_executive_id = $get_executive_id[0]['id'];
                } elseif ($get_executive_id[1]['rr_id'] == 0) {
                    $not_executive_id = $get_executive_id[1]['id'];
                }

                $is_executives_id = $this->employment_model->update_executive_id(array('rr_id' => 0), array('id' => $executive_id));
                $is_not_executives_id = $this->employment_model->update_executive_id(array('rr_id' => 1), array('id' => $not_executive_id));

                $field_array = array(
                    'clientid' => $frm_details['clientid'],
                    'candsid' => $frm_details['candsid'],
                    'empid' => $frm_details['empid'],
                    'nameofthecompany' => $company_id,
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
                    'r_manager_name' => $frm_details['r_manager_name'],
                    'r_manager_no' => $frm_details['r_manager_no'],
                    'r_manager_designation' => $frm_details['r_manager_designation'],
                    'r_manager_email' => $frm_details['r_manager_email'],
                    'created_on' => date(DB_DATE_FORMAT),
                    'modified_on' => date(DB_DATE_FORMAT),
                    'is_bulk_uploaded' => 0,
                    "due_date" => $closed_date,
                    'has_case_id' => $executive_id,
                    "tat_status" => 'IN TAT',
                );

                $result = $this->employment_model->save(array_map('strtolower', $field_array), array('empver.id' => $frm_details['employment_id']));

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
                                'created_by' => "candidate info",
                                'created_on' => date(DB_DATE_FORMAT),
                                'empver_id' => $result,
                            ));
                    }
                    $x++;
                }

                if (!empty($supervisor_array)) {
                    $this->employment_model->empver_supervisor_details($supervisor_array);
                }

                $config_array = array('file_upload_path' => $file_upload_path, 'file_permission' => 'jpeg|jpg|png|pdf|docx|xls|xlsx', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 1000, 'file_id' => $frm_details['employment_id'], 'component_name' => 'empver_id');

                if (empty($error_msgs)) {
                    if ($_FILES['attchments']['name'][0] != '') {
                        $config_array['files_count'] = count($_FILES['attchments']['name']);
                        $config_array['file_data'] = $_FILES['attchments'];
                        $config_array['type'] = 0;
                        $retunr_de = $this->file_upload_multiple($config_array);
                        if (!empty($retunr_de)) {
                            $this->common_model->common_insert_batch('empverres_files', $retunr_de['success']);
                        }
                    }

                }

                if ($result) {

                    $check = $this->employment_model->select_insuff(array('empverres_id' => $frm_details['employment_id'], 'status' => 1));

                    if (!empty($check)) {
                        $result = $this->employment_model->save_update_insuff(array('insuff_clear_date' => date('Y-m-d'), 'insuff_cleared_timestamp' => date(DB_DATE_FORMAT), 'insuff_remarks' => 'Auto - Candidate has updated the info', 'status' => 2, 'empverres_id' => $frm_details['employment_id'], 'auto_stamp' => 2, 'insuff_cleared_by' => "Candidate"), array('empverres_id' => $frm_details['employment_id']));

                    }

                    $result_update_record = $this->employment_model->save_update(array('verfstatus' => 1, 'var_filter_status' => 'WIP', 'var_report_status' => 'WIP'), array('empverid' => $frm_details['employment_id']));

                    auto_update_tat_status($frm_details['candsid']);

                    auto_update_overall_status($frm_details['candsid']);

                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'Employment Record Successfully Inserted';

                    $json_array['redirect'] = ADMIN_SITE_URL . 'address';
                } else {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Something went wrong,please try again';
                }
                echo_json($json_array);
            }
        }
    }

    public function load_supervisor_details($sts, $counter)
    {
        $this->load->model('candidate_info/employment_model');
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

}
