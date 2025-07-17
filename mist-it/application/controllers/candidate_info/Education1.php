<?php defined('BASEPATH') or exit('No direct script access allowed');
class Education extends MY_Candidate_Cotroller
{

    public function __construct()
    {
        parent::__construct();

        if (!$this->is_candidate_logged_in()) {
            redirect('candidates_info/login');
            exit();
        }

        $this->perm_array = array('direct_access' => true);
        $this->load->model(array('candidate_info/education_model'));
    }

    public function list_view()
    {
        $data['header_title'] = "Education Pending List";

        $this->load->view('candidate_info/header', $data);

        $this->load->view('candidate_info/education_list');

        $this->load->view('candidate_info/footer');
    }

    public function submitted_list_view()
    {
        $data['header_title'] = "Education Submit List";

        $this->load->view('candidate_info/header', $data);

        $this->load->view('candidate_info/education_submit_list');

        $this->load->view('candidate_info/footer');
    }

    public function education_view()
    {

        $params = $add_candidates = $data_arry = $columns = array();

        $params = $_REQUEST;

        $columns = array('addrver_id.id', 'candidates_info.ClientRefNumber', 'candidates_info.cmp_ref_no', 'company_database.coname', 'candidates_info.CandidateName', 'candidates_info.caserecddate', 'verfstatus', 'city', 'encry_id', 'address', 'pincode', 'state', 'check_closure_date', 'add_com_ref', 'iniated_date');

        $where_arry = array();

        $add_address = $this->education_model->get_all_education_record_datatable($params, $columns);

        $totalRecords = count($this->education_model->get_all_education_record_datatable_count($params, $columns));

        $x = 0;

        foreach ($add_address as $detail) {
            $data_arry[$x]['id'] = $x + 1;
            $data_arry[$x]['checkbox'] = $detail['education_id'];
            $data_arry[$x]['ClientRefNumber'] = $detail['ClientRefNumber'];
            $data_arry[$x]['cmp_ref_no'] = $detail['cmp_ref_no'];
            $data_arry[$x]['CandidateName'] = $detail['CandidateName'];
            $data_arry[$x]['education_com_ref'] = $detail['education_com_ref'];
            $data_arry[$x]['iniated_date'] = convert_db_to_display_date($detail['iniated_date']);
            $data_arry[$x]['clientname'] = $detail['clientname'];
            $data_arry[$x]['vendor_name'] = $detail['vendor_name'];
            $data_arry[$x]['verfstatus'] = $detail['status_value'];
            $data_arry[$x]['school_college'] = $detail['school_college'];
            $data_arry[$x]['university_board'] = $detail['university_board'];
            $data_arry[$x]['qualification'] = $detail['qualification'];
            $data_arry[$x]['year_of_passing'] = $detail['year_of_passing'];
            $data_arry[$x]['executive_name'] = $detail['executive_name'];
            // $data_arry[$x]['first_qc_approve'] = $detail['first_qc_approve'];
            $data_arry[$x]['caserecddate'] = convert_db_to_display_date($detail['caserecddate']);
            $data_arry[$x]['last_activity_date'] = $detail['last_activity_date'];
            $data_arry[$x]['closuredate'] = convert_db_to_display_date($detail['closuredate']);
            $data_arry[$x]['due_date'] = convert_db_to_display_date($detail['due_date']);
            $data_arry[$x]['tat_status'] = $detail['tat_status'];
            $data_arry[$x]['encry_id'] = CANDIDATE_SITE_URL . "Education/add/" . $detail['candidates_info_id'] . "/" . $detail['education_id'];
            $data_arry[$x]['mode_of_veri'] = $detail['mode_of_veri'];

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

    public function education_submit_view()
    {

        $params = $add_candidates = $data_arry = $columns = array();

        $params = $_REQUEST;

        $columns = array('addrver_id.id', 'candidates_info.ClientRefNumber', 'candidates_info.cmp_ref_no', 'company_database.coname', 'candidates_info.CandidateName', 'candidates_info.caserecddate', 'verfstatus', 'city', 'encry_id', 'address', 'pincode', 'state', 'check_closure_date', 'add_com_ref', 'iniated_date');

        $where_arry = array();

        $add_address = $this->education_model->get_all_education_submit_by_candidate_datatable($params, $columns);

        $totalRecords = count($this->education_model->get_all_education_submit_by_candidate_datatable_count($params, $columns));

        $x = 0;

        foreach ($add_address as $detail) {

            $data_arry[$x]['id'] = $x + 1;
            $data_arry[$x]['checkbox'] = $detail['id'];
            $data_arry[$x]['ClientRefNumber'] = $detail['ClientRefNumber'];
            $data_arry[$x]['cmp_ref_no'] = $detail['cmp_ref_no'];
            $data_arry[$x]['CandidateName'] = $detail['CandidateName'];
            $data_arry[$x]['education_com_ref'] = $detail['education_com_ref'];
            $data_arry[$x]['iniated_date'] = convert_db_to_display_date($detail['iniated_date']);
            $data_arry[$x]['clientname'] = $detail['clientname'];
            $data_arry[$x]['vendor_name'] = $detail['vendor_name'];
            $data_arry[$x]['verfstatus'] = $detail['status_value'];
            $data_arry[$x]['school_college'] = $detail['school_college'];
            $data_arry[$x]['university_board'] = $detail['university_board'];
            $data_arry[$x]['qualification'] = $detail['qualification'];
            $data_arry[$x]['year_of_passing'] = $detail['year_of_passing'];
            $data_arry[$x]['executive_name'] = $detail['executive_name'];
            // $data_arry[$x]['first_qc_approve'] = $detail['first_qc_approve'];
            $data_arry[$x]['caserecddate'] = convert_db_to_display_date($detail['caserecddate']);
            $data_arry[$x]['last_activity_date'] = $detail['last_activity_date'];
            $data_arry[$x]['closuredate'] = convert_db_to_display_date($detail['closuredate']);
            $data_arry[$x]['due_date'] = convert_db_to_display_date($detail['due_date']);
            $data_arry[$x]['tat_status'] = $detail['tat_status'];
            $data_arry[$x]['encry_id'] = ADMIN_SITE_URL . "education/view_details/" . encrypt($detail['id']);
            $data_arry[$x]['mode_of_veri'] = $detail['mode_of_veri'];

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

        $this->load->model('Education_model');

        if ($this->input->is_ajax_request()) {

            $data['get_cands_details'] = $this->candidate_entity_pack_details($candsid);
            $data['states'] = $this->get_states();

            $data['universityname'] = $this->university_list();
            $data['id'] = $id;

            $data['qualification_name'] = $this->qualification_list();

            $data['details'] = $this->Education_model->get_all_education_record(array('education.id' => $id));

            $data['mode_of_verification'] = $this->Education_model->get_mode_of_verification(array('entity' => $data['get_cands_details']['entity_id'], 'package' => $data['get_cands_details']['package_id'], 'tbl_clients_id' => $data['get_cands_details']['clientid']));

            echo $this->load->view('candidate_info/education_add', $data, true);
        } else {
            echo "<h3>Something went wrong, please try again</h3>";
        }
    }

    public function university_list()
    {
        $this->load->model('university_model');

        $results = $this->university_model->select(false, array('universityname', 'id'), array());
        $university_arry = array();

        $university_arry[0] = 'Select University';

        foreach ($results as $key => $value) {
            $university_arry[$value['id']] = $value['universityname'];
        }

        return $university_arry;
    }

    public function qualification_list()
    {
        $this->load->model('qualification_model');

        $results = $this->qualification_model->select(false, array('qualification', 'id'), array());

        $university_arry = array();

        $university_arry[0] = 'Select Qualification';

        foreach ($results as $key => $value) {
            $university_arry[$value['id']] = ucwords($value['qualification']);
        }

        return $university_arry;
    }
    public function save_education()
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
                $file_upload_path = SITE_BASE_PATH . EDUCATION . $frm_details['clientid'];
                if (!folder_exist($file_upload_path)) {
                    mkdir($file_upload_path, 0777);
                } else if (!is_writable($file_upload_path)) {
                    array_push($error_msgs, 'Problem while uploading');
                }

                $tat_day = $this->get_client_entity_package_tat_day(array('tbl_clients_id' => $frm_details['clientid'], 'entity' => $frm_details['entity_id'], 'package' => $frm_details['package_id']));

                $get_holiday1 = $this->get_holiday();

                $get_holiday = array_map('current', $get_holiday1);

                $closed_date = getWorkingDays(convert_display_to_db_date($frm_details['iniated_date']), $get_holiday, $tat_day[0]['tat_eduver']);

                $field_array = array('clientid' => $frm_details['clientid'],
                    'candsid' => $frm_details['candsid'],
                    'iniated_date' => convert_display_to_db_date($frm_details['iniated_date']),
                    'school_college' => $frm_details['school_college'],
                    'university_board' => $frm_details['university_board'],
                    'grade_class_marks' => $frm_details['grade_class_marks'],
                    'qualification' => $frm_details['qualification'],
                    'major' => $frm_details['major'],
                    'course_start_date' => convert_display_to_db_date($frm_details['course_start_date']),
                    'course_end_date' => convert_display_to_db_date($frm_details['course_end_date']),
                    'month_of_passing' => $frm_details['month_of_passing'],
                    'year_of_passing' => $frm_details['year_of_passing'],
                    'roll_no' => $frm_details['roll_no'],
                    'enrollment_no' => $frm_details['enrollment_no'],
                    'PRN_no' => $frm_details['PRN_no'],
                    'documents_provided' => json_encode($frm_details['documents_provided']),
                    'city' => $frm_details['city'],
                    'state' => $frm_details['state'],
                    'mode_of_veri' => $frm_details['mod_of_veri'],
                    //'genuineness' => $frm_details['genuineness'],
                    //'online_URL' => $frm_details['online_URL'],
                    'city' => $frm_details['city'],
                    'state' => $frm_details['state'],
                    'created_on' => date(DB_DATE_FORMAT),
                    'modified_on' => date(DB_DATE_FORMAT),
                    'is_bulk_uploaded' => 0,
                    'has_case_id' => 12,
                    "due_date" => $closed_date,
                    "tat_status" => "IN TAT",
                );

                $result = $this->education_model->save(array_map('strtolower', $field_array), array('education.id' => $frm_details['education_id']));

                $config_array = array('file_upload_path' => $file_upload_path, 'file_permission' => 'jpeg|jpg|png|pdf|docx|xls|xlsx', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 1000, 'file_id' => $frm_details['education_id'], 'component_name' => 'education_id');
                if (empty($error_msgs)) {
                    if ($_FILES['attchments']['name'][0] != '') {
                        $config_array['files_count'] = count($_FILES['attchments']['name']);
                        $config_array['file_data'] = $_FILES['attchments'];
                        $config_array['type'] = 0;
                        $config_array['education_id'] =  $frm_details['comp_ref_no'] ;
                        $retunr_de = $this->file_upload_library->file_upload_multiple_education($config_array, true);
                        //$retunr_de = $this->file_upload_multiple($config_array);
                        if (!empty($retunr_de)) {
                            $this->common_model->common_insert_batch('education_files', $retunr_de['success']);
                        }
                    }

                }

                if ($result) {

                    $check = $this->education_model->select_insuff(array('education_id' => $frm_details['education_id'], 'status' => 1));

                    if (!empty($check)) {

                        $result = $this->education_model->save_update_insuff(array('insuff_clear_date' => date('Y-m-d'), 'insuff_cleared_timestamp' => date(DB_DATE_FORMAT), 'insuff_remarks' => 'Auto - Candidate has updated the info', 'status' => 2, 'education_id' => $frm_details['education_id'], 'auto_stamp' => 2, 'insuff_cleared_by' => "Candidate"), array('education_insuff.education_id' => $frm_details['education_id']));

                    }

                    $result_update_record = $this->education_model->save_update(array('verfstatus' => 1, 'var_filter_status' => 'WIP', 'var_report_status' => 'WIP'), array('education_id' => $frm_details['education_id']));

                    auto_update_tat_status($frm_details['candsid']);

                    auto_update_overall_status($frm_details['candsid']);

                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'Education Record Successfully Inserted';

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
