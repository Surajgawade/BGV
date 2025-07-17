<?php defined('BASEPATH') or exit('No direct script access allowed');
class Scheduler extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!$this->is_admin_logged_in()) {
            redirect('admin/login');
            exit();
        }
        $this->perm_array = array('page_id' => 22);

        $this->load->model('report_generated_user');
        $this->load->helper('download');
    }

    public function index()
    {
        $data['header_title'] = "Schedule Report";

        $data['lists'] = $this->report_generated_user->scheduler_details(array('scheduler_list.status' => STATUS_ACTIVE));

        $data['status_value'] = $this->get_status();
        
        $data['components'] = convert_to_single_dimension_array($this->components(), 'id', 'component_name');


        $this->load->view('admin/header', $data);

        $this->load->view('admin/schedule_list');

        $this->load->view('admin/footer');
    }

    public function set_scheduler()
    {
        if ($this->input->is_ajax_request()) {
            $report_name = $this->input->post('report_name');

            $this->form_validation->set_rules('time_stamp', 'Timestamp', 'required');

            $this->form_validation->set_rules('report_id', 'Report ID', 'required');

            $this->form_validation->set_rules('portal_users[]', 'Portal User', 'required');

            $this->form_validation->set_rules('report_name', 'Report Name', 'required|min_length[3]|is_unique[scheduler_task.report_name]');

            $this->form_validation->set_rules('days[]', 'Days', 'required');

            $this->form_validation->set_message('is_unique', "$report_name report name already used");

            if ($this->form_validation->run() == false) {
                $json_data['status'] = ERROR_CODE;

                $json_data['message'] = validation_errors('', '');
            } else {
                $frm_details = $this->input->post();

                $fields = array('schedule_time' => date("H:i", strtotime($frm_details['time_stamp'])),
                    'activity_days' => implode(',', $frm_details['days']),
                    'report_name' => $frm_details['report_name'],
                    'report_id' => $frm_details['report_id'],
                    'portal_users' => implode(',', $frm_details['portal_users']),
                    'created_by' => $this->user_info['id'],
                    'created_on' => date(DB_DATE_FORMAT),
                    'status' => 1,
                    'is_executive' => 0,
                    'executive_on' => null,
                    'mail_sent' => 0,
                );

                if ($this->report_generated_user->scheduler_task_save($fields)) {

                    $json_data['status'] = 200;

                    $json_data['message'] = 'Task schedule successfully';

                } else {
                    $json_data['status'] = ERROR_CODE;

                    $json_data['message'] = 'Error to schedule task,please try again';
                }
            }
        }
        echo_json($json_data);
    }

    public function edit($id = null)
    {
        if ($id) {

            $id = decrypt($id);

            $details = $this->report_generated_user->scheduler_details(array('scheduler_task.id' => $id));
            if (!empty($details)) {
                $data['details'] = $details[0];
                $data['states'] = $this->get_states();

                $data['assigned_user_id'] = $this->users_list();

                $data['status'] = $this->get_status();

                $data['clients'] = $this->get_clients(array('status' => STATUS_ACTIVE));

                $data['logs'] = $this->report_generated_user->select(false, array('created_on,(select user_name from user_profile where user_profile.id = report_generated_user.created_by) as executive_name'), array('type' => 'Address'));

                $data['header_title'] = "Schedule Report";

                $this->load->view('admin/header', $data);

                $this->load->view('admin/export_address_filter_edit', $data);

                $this->load->view('admin/footer');
            } else {
                show_404();
            }

        } else {
            show_404();
        }
    }

    public function export_activity_data()
    {

        $json_array = array();

        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('activity_from', 'Date From', 'required');

            $this->form_validation->set_rules('activity_to', 'Date To', 'required');

            if ($this->form_validation->run() == false) {
                $json_array['message'] = "All fields required";

                $json_array['status'] = ERROR_CODE;

                $this->echo_json($json_array);
            }

            $frm_details = $this->input->post();
            if ($frm_details['activity_from'] <= $frm_details['activity_to']) {

                $requested_data = array('type' => 'Activity Log', 'requested_id' => $this->user_info['id'], 'file_name' => '', 'created_on' => date(DB_DATE_FORMAT), 'mail_send_status' => 0, 'folder_generated_status' => 0, 'downloaded_status' => 0, 'downloaded_on_date' => '', 'folder_name' => '');

                $report_requested_save_id = $this->report_generated_user->report_requested_save($requested_data);

                if ($report_requested_save_id) {

                    $file_upload_path = SITE_BASE_PATH . UPLOAD_FOLDER . 'bulk_reports';

                    if (!folder_exist($file_upload_path)) {
                        mkdir($file_upload_path, 0777);
                    }
                    if (!folder_exist($file_upload_path)) {
                        mkdir($file_upload_path, 0777);
                    } else if (!is_writable($file_upload_path)) {
                        mkdir($file_upload_path, 0777);
                    }

                    $where_arry = array('users_activitity_data.created_on >' => convert_display_to_db_date($frm_details['activity_from']), 'users_activitity_data.created_on <' => convert_display_to_db_date($frm_details['activity_to']) . ' 23:59:59');

                    $all_records = $this->report_generated_user->get_activity_records($where_arry);

                    require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
                    // Create new Spreadsheet object
                    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
                    // Set document properties
                    $spreadsheet->getProperties()->setCreator(CRMNAME)
                        ->setLastModifiedBy(CRMNAME)
                        ->setTitle(CRMNAME)
                        ->setSubject('Activity Records')
                        ->setDescription('Activity Records');
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
                    $spreadsheet->getActiveSheet()->getStyle('A1:O1')->applyFromArray($styleArray);
                    // auto fit column to content
                    foreach (range('A', 'O') as $columnID) {
                        $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                            ->setWidth(20);
                    }

                    // set the names of header cells
                    $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue("A1", 'Components')
                        ->setCellValue("B1", 'Ref No.')
                        ->setCellValue("C1", 'Candidate Name')
                        ->setCellValue("D1", 'Created On')
                        ->setCellValue("E1", 'Created By')
                        ->setCellValue("F1", 'Activity Type')
                        ->setCellValue("G1", 'Action')
                        ->setCellValue("H1", 'Current Status')
                        ->setCellValue("I1", 'Client Name')
                        ->setCellValue("J1", 'Entity')
                        ->setCellValue("K1", 'Package')
                        ->setCellValue("L1", 'Received Date')
                        ->setCellValue("M1", 'Due date')
                        ->setCellValue("N1", 'TAT Status')
                        ->setCellValue("O1", 'Assigned To');
                    // Add some data
                    $x = 2;

                    foreach ($all_records as $all_record) {

                        $spreadsheet->setActiveSheetIndex(0);
                        $spreadsheet->getActiveSheet()->setCellValue("A$x", $all_record['component']);
                        $spreadsheet->getActiveSheet()->setCellValue("B$x", $all_record['ref_no']);
                        $spreadsheet->getActiveSheet()->setCellValue("C$x", $all_record['candidate_name']);
                        $spreadsheet->getActiveSheet()->setCellValue("D$x", convert_db_to_display_date($all_record['created_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12));
                        $spreadsheet->getActiveSheet()->setCellValue("E$x", $all_record['username']);
                        $spreadsheet->getActiveSheet()->setCellValue("F$x", $all_record['activity_type']);
                        $spreadsheet->getActiveSheet()->setCellValue("G$x", $all_record['action']);

                        if ($all_record['component'] == "Candidate") {
                            $candidate_records = $this->report_generated_user->get_candidate_records(array('candidates_info.status' => STATUS_ACTIVE));

                            foreach ($candidate_records as $candidate_record) {

                                if (strtoupper($all_record['ref_no']) == strtoupper($candidate_record['cmp_ref_no'])) {
                                    $spreadsheet->getActiveSheet()->setCellValue("H$x", $candidate_record['status_value']);
                                    $spreadsheet->getActiveSheet()->setCellValue("I$x", $candidate_record['clientname']);
                                    $spreadsheet->getActiveSheet()->setCellValue("J$x", $candidate_record['entity_name']);
                                    $spreadsheet->getActiveSheet()->setCellValue("K$x", $candidate_record['package_name']);
                                    $spreadsheet->getActiveSheet()->setCellValue("L$x", convert_db_to_display_date($candidate_record['caserecddate']));
                                    $spreadsheet->getActiveSheet()->setCellValue("M$x", convert_db_to_display_date($candidate_record['due_date_candidate']));
                                    $spreadsheet->getActiveSheet()->setCellValue("N$x", $candidate_record['tat_status_candidate']);
                                    $spreadsheet->getActiveSheet()->setCellValue("O$x", "-");

                                }
                            }

                        }
                        if ($all_record['component'] == "Address") {
                            $address_records = $this->report_generated_user->get_address_records();

                            foreach ($address_records as $address_record) {

                                if (strtoupper($all_record['ref_no']) == strtoupper($address_record['add_com_ref'])) {
                                    $spreadsheet->getActiveSheet()->setCellValue("H$x", $address_record['status_value']);
                                    $spreadsheet->getActiveSheet()->setCellValue("I$x", $address_record['clientname']);
                                    $spreadsheet->getActiveSheet()->setCellValue("J$x", $address_record['entity_name']);
                                    $spreadsheet->getActiveSheet()->setCellValue("K$x", $address_record['package_name']);
                                    $spreadsheet->getActiveSheet()->setCellValue("L$x", convert_db_to_display_date($address_record['iniated_date']));
                                    $spreadsheet->getActiveSheet()->setCellValue("M$x", convert_db_to_display_date($address_record['due_date']));
                                    $spreadsheet->getActiveSheet()->setCellValue("N$x", $address_record['tat_status']);
                                    $spreadsheet->getActiveSheet()->setCellValue("O$x", $address_record['executive_name']);

                                }
                            }

                        }
                        if ($all_record['component'] == "Employment") {
                            $employment_records = $this->report_generated_user->get_employment_records();

                            foreach ($employment_records as $employment_record) {

                                if (strtoupper($all_record['ref_no']) == strtoupper($employment_record['emp_com_ref'])) {
                                    $spreadsheet->getActiveSheet()->setCellValue("H$x", $employment_record['status_value']);
                                    $spreadsheet->getActiveSheet()->setCellValue("I$x", $employment_record['clientname']);
                                    $spreadsheet->getActiveSheet()->setCellValue("J$x", $employment_record['entity_name']);
                                    $spreadsheet->getActiveSheet()->setCellValue("K$x", $employment_record['package_name']);
                                    $spreadsheet->getActiveSheet()->setCellValue("L$x", convert_db_to_display_date($employment_record['iniated_date']));
                                    $spreadsheet->getActiveSheet()->setCellValue("M$x", convert_db_to_display_date($employment_record['due_date']));
                                    $spreadsheet->getActiveSheet()->setCellValue("N$x", $employment_record['tat_status']);
                                    $spreadsheet->getActiveSheet()->setCellValue("O$x", $employment_record['executive_name']);

                                }
                            }

                        }
                        if ($all_record['component'] == "Education") {
                            $education_records = $this->report_generated_user->get_education_records();

                            foreach ($education_records as $education_record) {

                                if (strtoupper($all_record['ref_no']) == strtoupper($education_record['education_com_ref'])) {
                                    $spreadsheet->getActiveSheet()->setCellValue("H$x", $education_record['status_value']);
                                    $spreadsheet->getActiveSheet()->setCellValue("I$x", $education_record['clientname']);
                                    $spreadsheet->getActiveSheet()->setCellValue("J$x", $education_record['entity_name']);
                                    $spreadsheet->getActiveSheet()->setCellValue("K$x", $education_record['package_name']);
                                    $spreadsheet->getActiveSheet()->setCellValue("L$x", convert_db_to_display_date($education_record['iniated_date']));
                                    $spreadsheet->getActiveSheet()->setCellValue("M$x", convert_db_to_display_date($education_record['due_date']));
                                    $spreadsheet->getActiveSheet()->setCellValue("N$x", $education_record['tat_status']);
                                    $spreadsheet->getActiveSheet()->setCellValue("O$x", $education_record['executive_name']);

                                }
                            }

                        }
                        if ($all_record['component'] == "Reference") {
                            $reference_records = $this->report_generated_user->get_reference_records();

                            foreach ($reference_records as $reference_record) {

                                if (strtoupper($all_record['ref_no']) == strtoupper($reference_record['reference_com_ref'])) {
                                    $spreadsheet->getActiveSheet()->setCellValue("H$x", $reference_record['status_value']);
                                    $spreadsheet->getActiveSheet()->setCellValue("I$x", $reference_record['clientname']);
                                    $spreadsheet->getActiveSheet()->setCellValue("J$x", $reference_record['entity_name']);
                                    $spreadsheet->getActiveSheet()->setCellValue("K$x", $reference_record['package_name']);
                                    $spreadsheet->getActiveSheet()->setCellValue("L$x", convert_db_to_display_date($reference_record['iniated_date']));
                                    $spreadsheet->getActiveSheet()->setCellValue("M$x", convert_db_to_display_date($reference_record['due_date']));
                                    $spreadsheet->getActiveSheet()->setCellValue("N$x", $reference_record['tat_status']);
                                    $spreadsheet->getActiveSheet()->setCellValue("O$x", $reference_record['executive_name']);

                                }
                            }

                        }
                        if ($all_record['component'] == "Court Verification") {
                            $court_records = $this->report_generated_user->get_court_records();

                            foreach ($court_records as $court_record) {

                                if (strtoupper($all_record['ref_no']) == strtoupper($court_record['court_com_ref'])) {
                                    $spreadsheet->getActiveSheet()->setCellValue("H$x", $court_record['status_value']);
                                    $spreadsheet->getActiveSheet()->setCellValue("I$x", $court_record['clientname']);
                                    $spreadsheet->getActiveSheet()->setCellValue("J$x", $court_record['entity_name']);
                                    $spreadsheet->getActiveSheet()->setCellValue("K$x", $court_record['package_name']);
                                    $spreadsheet->getActiveSheet()->setCellValue("L$x", convert_db_to_display_date($court_record['iniated_date']));
                                    $spreadsheet->getActiveSheet()->setCellValue("M$x", convert_db_to_display_date($court_record['due_date']));
                                    $spreadsheet->getActiveSheet()->setCellValue("N$x", $court_record['tat_status']);
                                    $spreadsheet->getActiveSheet()->setCellValue("O$x", $court_record['executive_name']);

                                }
                            }
                        }
                        if ($all_record['component'] == "Global Database") {
                            $global_records = $this->report_generated_user->get_global_database_records();

                            foreach ($global_records as $global_record) {

                                if (strtoupper($all_record['ref_no']) == strtoupper($global_record['global_com_ref'])) {
                                    $spreadsheet->getActiveSheet()->setCellValue("H$x", $global_record['status_value']);
                                    $spreadsheet->getActiveSheet()->setCellValue("I$x", $global_record['clientname']);
                                    $spreadsheet->getActiveSheet()->setCellValue("J$x", $global_record['entity_name']);
                                    $spreadsheet->getActiveSheet()->setCellValue("K$x", $global_record['package_name']);
                                    $spreadsheet->getActiveSheet()->setCellValue("L$x", convert_db_to_display_date($global_record['iniated_date']));
                                    $spreadsheet->getActiveSheet()->setCellValue("M$x", convert_db_to_display_date($global_record['due_date']));
                                    $spreadsheet->getActiveSheet()->setCellValue("N$x", $global_record['tat_status']);
                                    $spreadsheet->getActiveSheet()->setCellValue("O$x", $global_record['executive_name']);

                                }
                            }

                        }
                        if ($all_record['component'] == "PCC") {
                            $pcc_records = $this->report_generated_user->get_pcc_records();

                            foreach ($pcc_records as $pcc_record) {

                                if (strtoupper($all_record['ref_no']) == strtoupper($pcc_record['pcc_com_ref'])) {
                                    $spreadsheet->getActiveSheet()->setCellValue("H$x", $pcc_record['status_value']);
                                    $spreadsheet->getActiveSheet()->setCellValue("I$x", $pcc_record['clientname']);
                                    $spreadsheet->getActiveSheet()->setCellValue("J$x", $pcc_record['entity_name']);
                                    $spreadsheet->getActiveSheet()->setCellValue("K$x", $pcc_record['package_name']);
                                    $spreadsheet->getActiveSheet()->setCellValue("L$x", convert_db_to_display_date($pcc_record['iniated_date']));
                                    $spreadsheet->getActiveSheet()->setCellValue("M$x", convert_db_to_display_date($pcc_record['due_date']));
                                    $spreadsheet->getActiveSheet()->setCellValue("N$x", $pcc_record['tat_status']);
                                    $spreadsheet->getActiveSheet()->setCellValue("O$x", $pcc_record['executive_name']);

                                }
                            }

                        }
                        if ($all_record['component'] == "Identity") {
                            $identity_records = $this->report_generated_user->get_identity_records();

                            foreach ($identity_records as $identity_record) {

                                if (strtoupper($all_record['ref_no']) == strtoupper($identity_record['identity_com_ref'])) {
                                    $spreadsheet->getActiveSheet()->setCellValue("H$x", $identity_record['status_value']);
                                    $spreadsheet->getActiveSheet()->setCellValue("I$x", $identity_record['clientname']);
                                    $spreadsheet->getActiveSheet()->setCellValue("J$x", $identity_record['entity_name']);
                                    $spreadsheet->getActiveSheet()->setCellValue("K$x", $identity_record['package_name']);
                                    $spreadsheet->getActiveSheet()->setCellValue("L$x", convert_db_to_display_date($identity_record['iniated_date']));
                                    $spreadsheet->getActiveSheet()->setCellValue("M$x", convert_db_to_display_date($identity_record['due_date']));
                                    $spreadsheet->getActiveSheet()->setCellValue("N$x", $identity_record['tat_status']);
                                    $spreadsheet->getActiveSheet()->setCellValue("O$x", $identity_record['executive_name']);

                                }
                            }

                        }
                        if ($all_record['component'] == "Credit Report") {
                            $credit_report_records = $this->report_generated_user->get_credit_report_records();

                            foreach ($credit_report_records as $credit_report_record) {

                                if (strtoupper($all_record['ref_no']) == strtoupper($credit_report_record['credit_report_com_ref'])) {
                                    $spreadsheet->getActiveSheet()->setCellValue("H$x", $credit_report_record['status_value']);
                                    $spreadsheet->getActiveSheet()->setCellValue("I$x", $credit_report_record['clientname']);
                                    $spreadsheet->getActiveSheet()->setCellValue("J$x", $credit_report_record['entity_name']);
                                    $spreadsheet->getActiveSheet()->setCellValue("K$x", $credit_report_record['package_name']);
                                    $spreadsheet->getActiveSheet()->setCellValue("L$x", convert_db_to_display_date($credit_report_record['iniated_date']));
                                    $spreadsheet->getActiveSheet()->setCellValue("M$x", convert_db_to_display_date($credit_report_record['due_date']));
                                    $spreadsheet->getActiveSheet()->setCellValue("N$x", $credit_report_record['tat_status']);
                                    $spreadsheet->getActiveSheet()->setCellValue("O$x", $credit_report_record['executive_name']);

                                }
                            }

                        }
                        if ($all_record['component'] == "Drugs Verification") {
                            $drugs_records = $this->report_generated_user->get_drugs_records();

                            foreach ($drugs_records as $drugs_record) {

                                if (strtoupper($all_record['ref_no']) == strtoupper($drugs_record['drugs_com_ref'])) {
                                    $spreadsheet->getActiveSheet()->setCellValue("H$x", $drugs_record['status_value']);
                                    $spreadsheet->getActiveSheet()->setCellValue("I$x", $drugs_record['clientname']);
                                    $spreadsheet->getActiveSheet()->setCellValue("J$x", $drugs_record['entity_name']);
                                    $spreadsheet->getActiveSheet()->setCellValue("K$x", $drugs_record['package_name']);
                                    $spreadsheet->getActiveSheet()->setCellValue("L$x", convert_db_to_display_date($drugs_record['iniated_date']));
                                    $spreadsheet->getActiveSheet()->setCellValue("M$x", convert_db_to_display_date($drugs_record['due_date']));
                                    $spreadsheet->getActiveSheet()->setCellValue("N$x", $drugs_record['tat_status']);
                                    $spreadsheet->getActiveSheet()->setCellValue("O$x", $drugs_record['executive_name']);

                                }
                            }

                        }

                        $x++;
                    }
                    // Rename worksheet
                    $spreadsheet->getActiveSheet()->setTitle('Activity Record');

                    $spreadsheet->setActiveSheetIndex(0);

                    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                    header("Content-Disposition: attachment;filename=Activity Records.xlsx");
                    header('Cache-Control: max-age=0');
                    // If you're serving to IE 9, then the following may be needed
                    header('Cache-Control: max-age=1');

                    // If you're serving to IE over SSL, then the following may be needed
                    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
                    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                    header('Pragma: public'); // HTTP/1.0

                    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Excel2007');

                    $file_name = "Candidates_Records_" . DATE(UPLOAD_FILE_DATE_FORMAT) . ".xls";

                    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Excel2007');
                    ob_start();
                    $writer->save($file_upload_path . "/" . $file_name);
                    ob_end_clean();

                    $this->update_request_data(array('file_name' => $file_name, 'folder_generated_status' => 1, 'folder_name' => $file_upload_path), array('id' => $report_requested_save_id));
                    $date_range = $frm_details['activity_from'] . ' to ' . $frm_details['activity_to'];

                    $this->update_request_schedular_list(array('report_id' => $report_requested_save_id, 'date_range' => $date_range, 'file_name' => $file_name, 'run_status' => 1, 'last_run_by' => $this->user_info['id'], 'last_run_on' => date(DB_DATE_FORMAT)), array('id' => '1'));

                    //  $json_array['file'] = "data:application/vnd.ms-excel;base64,".base64_encode($xlsData);

                    // $json_array['file_name'] = "Activity Records";

                    $json_array['message'] = "File Created Successfully";

                    $json_array['status'] = SUCCESS_CODE;
                } else {
                    $json_array['message'] = "Something went wrong,please try again";
                    $json_array['status'] = ERROR_CODE;
                }
            } else {
                $json_array['message'] = "From date not greather than to date";
                $json_array['status'] = ERROR_CODE;
            }
        } else {
            $json_array['message'] = "Something went wrong,please try again";
            $json_array['status'] = ERROR_CODE;
        }

        echo_json($json_array);

    }
    public function update_request_data($details, $where)
    {
        $this->report_generated_user->report_requested_save($details, $where);
    }
    public function update_request_schedular_list($details, $where)
    {
        $this->report_generated_user->report_requested_schedular($details, $where);
    }

    public function file_download_activity($file_name)
    {

        if ($file_name) {
            $file = SITE_BASE_PATH . UPLOAD_FOLDER . "bulk_reports/" . $file_name;
            if (file_exists($file)) {
                $this->report_generated_user->report_update_file_download_status(array('downloaded_status' => '1', 'downloaded_on_date' => date(DB_DATE_FORMAT)), array('file_name' => $file_name));
                $data = file_get_contents($file);
                force_download($file_name, $data);
            } else {
                redirect(base_url());
            }
        }
    }

    public function file_download_axis($file_name)
    {

        if ($file_name) {
            $file = SITE_BASE_PATH . UPLOAD_FOLDER . "bulk_reports/" . $file_name;
            if (file_exists($file)) {
                $this->report_generated_user->report_update_file_download_status(array('downloaded_status' => '1', 'downloaded_on_date' => date(DB_DATE_FORMAT)), array('file_name' => $file_name));
                $data = file_get_contents($file);
                force_download($file_name, $data);
            } else {
                redirect(base_url());
            }
        }
    }

    public function activity_recent_file()
    {

        $select_activity_file = $this->report_generated_user->get_activity_file();
        $file_path = UPLOAD_FOLDER . 'bulk_reports/';

        echo '<table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Sr. No</th>
                        <th>File Name</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>';

        $srno = 1;
        foreach ($select_activity_file as $key => $value) {

            $linkpath = ADMIN_SITE_URL . 'scheduler/file_download_activity/' . $value['file_name'];

            echo "<tr>";
            echo "<td>" . $srno . "</td>";
            echo "<td>" . $value['file_name'] . "</td>";
            echo "<td>" . convert_db_to_display_date($value['created_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12) . "</td>";
            echo "<td><a href='$linkpath' class='btn btn-link'>Download</a> </td>";
            echo "</tr>";

            $srno++;
        }

        echo '</tbody>
                </table>';
    }

    public function axis_ikya_recent_file()
    {

        $select_activity_file = $this->report_generated_user->get_axis_ikya_file();
        $file_path = UPLOAD_FOLDER . 'bulk_reports/';

        echo '<table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Sr. No</th>
                        <th>File Name</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>';

        $srno = 1;
        foreach ($select_activity_file as $key => $value) {

            $linkpath = ADMIN_SITE_URL . 'scheduler/file_download_axis/' . $value['file_name'];

            echo "<tr>";
            echo "<td>" . $srno . "</td>";
            echo "<td>" . $value['file_name'] . "</td>";
            echo "<td>" . convert_db_to_display_date($value['created_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12) . "</td>";
            echo "<td><a href='$linkpath' class='btn btn-link'>Download</a> </td>";
            echo "</tr>";

            $srno++;
        }

        echo '</tbody>
                </table>';
    }

    public function axis_teamlease_recent_file()
    {

        $select_activity_file = $this->report_generated_user->get_axis_teamlease_file();
        $file_path = UPLOAD_FOLDER . 'bulk_reports/';

        echo '<table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Sr. No</th>
                        <th>File Name</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>';

        $srno = 1;
        foreach ($select_activity_file as $key => $value) {

            $linkpath = ADMIN_SITE_URL . 'scheduler/file_download_axis/' . $value['file_name'];

            echo "<tr>";
            echo "<td>" . $srno . "</td>";
            echo "<td>" . $value['file_name'] . "</td>";
            echo "<td>" . convert_db_to_display_date($value['created_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12) . "</td>";
            echo "<td><a href='$linkpath' class='btn btn-link'>Download</a> </td>";
            echo "</tr>";

            $srno++;
        }

        echo '</tbody>
                </table>';
    }

    public function axis_recent_file()
    {

        $select_activity_file = $this->report_generated_user->get_axis_file();
        $file_path = UPLOAD_FOLDER . 'bulk_reports/';

        echo '<table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Sr. No</th>
                        <th>File Name</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>';

        $srno = 1;
        foreach ($select_activity_file as $key => $value) {

            $linkpath = ADMIN_SITE_URL . 'scheduler/file_download_axis/' . $value['file_name'];

            echo "<tr>";
            echo "<td>" . $srno . "</td>";
            echo "<td>" . $value['file_name'] . "</td>";
            echo "<td>" . convert_db_to_display_date($value['created_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12) . "</td>";
            echo "<td><a href='$linkpath' class='btn btn-link'>Download</a> </td>";
            echo "</tr>";

            $srno++;
        }

        echo '</tbody>
                </table>';
    }

    /*  public function export_axis_report()
    {

    $json_array = array();

    if($this->input->is_ajax_request())
    {
    /* $this->form_validation->set_rules('activity_from_axis', 'Date From', 'required');

    $this->form_validation->set_rules('activity_to_axis', 'Date To', 'required');

    if ($this->form_validation->run() == FALSE)
    {
    $json_array['message'] = "All fields required";

    $json_array['status'] = ERROR_CODE;

    $this->echo_json($json_array);
    }

    $frm_details = $this->input->post();
     */
    /*       $frm_details['activity_from_axis'] = '01-01-2020';
    $frm_details['activity_to_axis'] = date("d-m-Y");
    if($frm_details['activity_from_axis'] <= $frm_details['activity_to_axis'])
    {

    $requested_data = array('type' => 'Axis Tracker','requested_id' => $this->user_info['id'],'file_name'=>'','created_on'=>date(DB_DATE_FORMAT),'mail_send_status'=>0,'folder_generated_status'=>0,'downloaded_status'=>0,'downloaded_on_date' => '','folder_name' => '');

    $report_requested_save_id = $this->report_generated_user->report_requested_save($requested_data);

    if($report_requested_save_id)
    {

    $file_upload_path = SITE_BASE_PATH.UPLOAD_FOLDER.'bulk_reports';

    if (!folder_exist($file_upload_path)) {
    mkdir($file_upload_path, 0777);
    }
    if (!folder_exist($file_upload_path)) {
    mkdir($file_upload_path, 0777);
    } else if (!is_writable($file_upload_path)) {
    mkdir($file_upload_path, 0777);
    }

    $where_arry = array('candidates_info.caserecddate >' => convert_display_to_db_date($frm_details['activity_from_axis']),'candidates_info.caserecddate <' => convert_display_to_db_date($frm_details['activity_to_axis']));

    $all_records = $this->report_generated_user->get_candidate_record_for_axis($where_arry);

    require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
    // Create new Spreadsheet object
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    // Set document properties
    $spreadsheet->getProperties()->setCreator(CRMNAME)
    ->setLastModifiedBy(CRMNAME)
    ->setTitle(CRMNAME)
    ->setSubject('Candidates records')
    ->setDescription('candidates records with their status');
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
    foreach(range('A','Z') as $columnID) {
    $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
    ->setWidth(20);
    }

    // set the names of header cells
    $spreadsheet->setActiveSheetIndex(0)
    ->setCellValue("A1", 'Agency Name')
    ->setCellValue("B1", 'Handover Date')
    ->setCellValue("C1", 'Employee Code')
    ->setCellValue("D1", 'Employee Name')
    ->setCellValue("E1", 'Address Status')
    ->setCellValue("F1", 'Remark (including Insufficiency Details / Discrepancy Details)')
    ->setCellValue("G1", 'Date of insufficiency / Major Discrepancies raised by Vendor')
    ->setCellValue("H1", 'Insufficiency 2')
    ->setCellValue("I1", 'Date of Insufficeincy 2')
    ->setCellValue("J1", 'Date of Address check close')
    ->setCellValue("K1", 'Employment Status')
    ->setCellValue("L1", 'Closure Remark')
    ->setCellValue("M1", 'Insufficiency Remark 1')
    ->setCellValue("N1", 'Insuff Raised Date 1')
    ->setCellValue("O1", 'Insufficiency Remark 2')
    ->setCellValue("P1", 'Insuff Raised Date 2')
    ->setCellValue("Q1", 'Discrepancy Remark 1(if any)')
    ->setCellValue("R1", 'Discrepancy Remark 2(if any)')
    ->setCellValue("S1", 'Date of Employment check close')
    ->setCellValue("T1", 'Verifiers Email ID')
    ->setCellValue("U1", 'Add Clear Date')
    ->setCellValue("V1", 'Emp Clear Date')
    ->setCellValue("W1", 'Branch')
    ->setCellValue("X1", 'Department')
    ->setCellValue("Y1", 'Address')
    ->setCellValue("Z1", 'Name of Company');
    // Add some data
    $x= 2;

    foreach($all_records as $all_record)
    {

    $components = explode(',',$all_record['component_id']);

    $rename_status = array('New Check'=>'WIP','Clear' => 'Positive','Insufficiency Cleared' => 'WIP','First QC Reject' => 'WIP','Follow Up'=>'WIP','Final QC Reject'=>'WIP');

    if(in_array('addrver', $components))
    {
    $result1 =  $this->report_generated_user->get_addres_ver_status(array('candidates_info.id'=>$all_record['id']));

    if(!empty($result1))
    {

    $result =  $this->report_generated_user->get_address_insuff_details(array('addrver.id'=>$result1[0]['id']));

    $result_discrepancy =  $this->report_generated_user->get_address_discrepancy_details(array('addrver.id'=>$result1[0]['id']));

    if(array_key_exists($result1[0]['status_value'], $rename_status))
    {
    $all_record['addrver'] = $rename_status[$result1[0]['status_value']];
    }
    else
    {
    $all_record['addrver'] = ($result1[0]['status_value'] != "") ? $result1[0]['status_value'] : 'WIP';
    }

    if($result1[0]['closuredate'] === NULL)
    {
    $all_record['addrver_closure_date'] = "WIP";
    }
    else if ($result1[0]['closuredate'] != "")
    {
    $all_record['addrver_closure_date'] = date('d-M-Y',strtotime($result1[0]['closuredate']));
    }
    else
    {
    $all_record['addrver_closure_date'] =  "NA";
    }

    $all_record['address'] = $result1[0]['address'] ?  $result1[0]['address'] : 'NA';

    $all_record['addrver_insuff_remarks_1'] = ($result[0]['insuff_raise_remark'] != "") ? $result[0]['insuff_raise_remark'] : 'NA';

    $all_record['addrver_insuff_raised_date_1'] = (date('d-M-Y',strtotime($result[0]['insuff_raised_date'])) != '01-Jan-1970') ? date('d-M-Y',strtotime($result[0]['insuff_raised_date'])) : 'NA';

    $addrver_insuff_raised_date_array = array_slice($result,1);

    if(!empty($addrver_insuff_raised_date_array))
    {
    foreach ($addrver_insuff_raised_date_array as  $addrver_insuff_raised_date) {
    $addrver_insuff_raised_2[] = (date('d-M-Y',strtotime($addrver_insuff_raised_date['insuff_raised_date'])) != '01-Jan-1970') ? date('d-M-Y',strtotime($addrver_insuff_raised_date['insuff_raised_date'])) : 'NA';
    }

    if($all_record['addrver'] == "Insufficiency")
    {
    $all_record['addrver'] = "Insufficiency II";
    }
    else
    {
    $all_record['addrver'] = $all_record['addrver'];
    }

    $all_record['addrver_insuff_remarks_2'] =  (end($result)['insuff_raise_remark'] != "") ? end($result)['insuff_raise_remark'] : 'NA';
    }
    else
    {
    $addrver_insuff_raised_2[] = "NA";
    $all_record['addrver_insuff_remarks_2'] = "NA";
    }

    $all_record['addrver_insuff_raised_date_2'] = implode(" & ",$addrver_insuff_raised_2);
    unset($addrver_insuff_raised_2);

    foreach ($result as  $results) {
    $addrver_insuffcleardate_2[] = (date('d-M-Y',strtotime($results['insuff_clear_date'])) != '01-Jan-1970') ? date('d-M-Y',strtotime($results['insuff_clear_date'])) : 'NA';
    }

    $all_record['addrver_insuffcleardate_2'] = implode(" & ",$addrver_insuffcleardate_2);
    unset($addrver_insuffcleardate_2);

    $addrver_discrepancy_array = array_slice($result_discrepancy,1);

    if(!empty($addrver_discrepancy_array))
    {

    if($all_record['addrver'] == "Major Discrepancy")
    {
    $all_record['addrver'] = "Major Discrepancy II";
    }
    else
    {
    $all_record['addrver'] = $all_record['addrver'];
    }
    }

    }
    else
    {
    $all_record['addrver'] = "NA";
    $all_record['addrver_insuff_remarks_1'] = "NA";
    $all_record['addrver_insuff_raised_date_1'] = "NA";
    $all_record['addrver_insuff_remarks_2'] = "NA";
    $all_record['addrver_insuff_raised_date_2'] = "NA";
    $all_record['addrver_closure_date']= "NA";
    }
    }
    else
    {
    $all_record['addrver'] = "NA";
    $all_record['addrver_insuff_remarks_1'] = "NA";
    $all_record['addrver_insuff_raised_date_1'] = "NA";
    $all_record['addrver_insuff_remarks_2'] = "NA";
    $all_record['addrver_insuff_raised_date_2'] = "NA";
    $all_record['addrver_closure_date']= "NA";
    }

    if(in_array('empver', $components))
    {

    $result1 =  $this->report_generated_user->get_empver_ver_status(array('candidates_info.id'=>$all_record['id']));

    if(!empty($result1))
    {

    $result =  $this->report_generated_user->get_employment_insuff_details(array('empver.id'=>$result1[0]['id']));

    $result_discrepancy =  $this->report_generated_user->get_employment_discrepancy_details(array('empver.id'=>$result1[0]['id']));

    if(array_key_exists($result1[0]['status_value'], $rename_status))
    {
    $all_record['empver'] = $rename_status[$result1[0]['status_value']];
    }
    else
    {
    $all_record['empver'] = ($result1[0]['status_value'] != "") ? $result1[0]['status_value'] : 'WIP';

    }

    if($result1[0]['closuredate'] === NULL)
    {
    $all_record['empver_closure_date'] = "WIP";
    }
    else if ($result1[0]['closuredate'] != "")
    {
    $all_record['empver_closure_date'] = date('d-M-Y',strtotime($result1[0]['closuredate']));
    }
    else
    {
    $all_record['empver_closure_date'] =  "NA";
    }

    $all_record['coname'] = $result1[0]['coname'] ?  $result1[0]['coname'] : 'NA';

    $all_record['empres_remarks_']  = $result1[0]['remarks'] ?  $result1[0]['remarks'] : 'NA';

    $all_record['empver_insuff_remarks_1'] = ($result[0]['insuff_raise_remark'] != "") ? $result[0]['insuff_raise_remark'] : 'NA';

    $all_record['empver_insuff_raised_date_1'] = (date('d-M-Y',strtotime($result[0]['insuff_raised_date'])) != '01-Jan-1970') ? date('d-M-Y',strtotime($result[0]['insuff_raised_date'])) : 'NA';

    $all_record['verifiers_email_id'] = ($result1[0]['verifiers_email_id'] != "") ? $result1[0]['verifiers_email_id'] : 'NA';

    $empver_insuff_raised_date_array = array_slice($result,1);

    if(!empty($empver_insuff_raised_date_array))
    {
    foreach ($empver_insuff_raised_date_array as  $empver_insuff_raised_date) {
    $empver_insuff_raised_2[] = (date('d-M-Y',strtotime($empver_insuff_raised_date['insuff_raised_date'])) != '01-Jan-1970') ? date('d-M-Y',strtotime($empver_insuff_raised_date['insuff_raised_date'])) : 'NA';
    }

    if($all_record['empver'] == "Insufficiency")
    {
    $all_record['empver'] = "Insufficiency II";
    }
    else
    {
    $all_record['empver'] = $all_record['empver'];
    }

    $all_record['empver_insuff_remarks_2'] =  (end($result)['insuff_raise_remark'] != "") ? end($result)['insuff_raise_remark'] : 'NA';

    }
    else
    {
    $empver_insuff_raised_2[] = "NA";

    $all_record['empver_insuff_remarks_2'] = 'NA';

    }

    $all_record['empver_insuff_raised_date_2'] = implode(" & ",$empver_insuff_raised_2);
    unset($empver_insuff_raised_2);

    foreach ($result as  $results) {
    $empver_insuffcleardate_2[] = (date('d-M-Y',strtotime($results['insuff_clear_date'])) != '01-Jan-1970') ? date('d-M-Y',strtotime($results['insuff_clear_date'])) : 'NA';
    }

    $all_record['empver_insuffcleardate_2'] = implode(" & ",$empver_insuffcleardate_2);
    unset($empver_insuffcleardate_2);

    $all_record['verifiers_email_id'] = ($result1[0]['verifiers_email_id'] != "") ? $result1[0]['verifiers_email_id'] : 'NA';

    if(!empty($result_discrepancy))
    {

    $all_record['empver_discrepancy_remark_1'] = ($result_discrepancy[0]['remarks'] != "") ?  $result_discrepancy[0]['remarks'] : "NA";
    }
    else
    {
    $all_record['empver_discrepancy_remark_1'] = "NA";

    }

    $empver_discrepancy_array = array_slice($result_discrepancy,1);

    if(!empty($empver_discrepancy_array))
    {
    $all_record['empver_discrepancy_remark_2'] =  (end($result_discrepancy)['remarks'] != "") ? end($result_discrepancy)['remarks'] : 'NA';

    if($all_record['empver'] == "Major Discrepancy")
    {
    $all_record['empver'] = "Major Discrepancy II";
    }
    else
    {
    $all_record['empver'] = $all_record['empver'];
    }
    }
    else
    {
    $all_record['empver_discrepancy_remark_2'] = 'NA';
    }
    }
    else
    {
    $all_record['empver'] = "NA";
    $all_record['empres_remarks_'] = "NA";
    $all_record['empver_insuff_remarks_1'] = "NA";
    $all_record['empver_insuff_raised_date_1'] = "NA";
    $all_record['empver_insuff_remarks_2'] = "NA";
    $all_record['empver_insuff_raised_date_2'] = "NA";
    $all_record['empver_discrepancy_remark_1'] = "NA";
    $all_record['empver_discrepancy_remark_2'] = "NA";
    $all_record['empver_closure_date'] = "NA";
    $all_record['verifiers_email_id'] = "NA";
    $all_record['addrver_insuffcleardate_2'] = "NA";
    $all_record['empver_insuffcleardate_2'] = "NA";
    $all_record['coname'] = "NA";
    }

    }
    else
    {
    $all_record['empver'] = "NA";
    $all_record['empres_remarks_'] = "NA";
    $all_record['empver_insuff_remarks_1'] = "NA";
    $all_record['empver_insuff_raised_date_1'] = "NA";
    $all_record['empver_insuff_remarks_2'] = "NA";
    $all_record['empver_insuff_raised_date_2'] = "NA";
    $all_record['empver_discrepancy_remark_1'] = "NA";
    $all_record['empver_discrepancy_remark_2'] = "NA";
    $all_record['empver_closure_date'] = "NA";
    $all_record['verifiers_email_id'] = "NA";
    $all_record['addrver_insuffcleardate_2'] = "NA";
    $all_record['empver_insuffcleardate_2'] = "NA";
    $all_record['coname'] = "NA";
    }

    $clientName = $all_record['clientname'];

    $caserecddate = (date('d-M-Y',strtotime($all_record['caserecddate'])) != '01-Jan-1970') ? date('d-M-Y',strtotime($all_record['caserecddate'])) : 'NA';

    $spreadsheet->setActiveSheetIndex(0)
    ->setCellValue("A$x", CRMNAME)
    ->setCellValue("B$x", $caserecddate)
    ->setCellValue("C$x", $all_record['ClientRefNumber'])
    ->setCellValue("D$x", $all_record['CandidateName'])
    ->setCellValue("E$x", $all_record['addrver'])
    ->setCellValue("F$x", $all_record['addrver_insuff_remarks_1'])
    ->setCellValue("G$x", $all_record['addrver_insuff_raised_date_1'])
    ->setCellValue("H$x", $all_record['addrver_insuff_remarks_2'])
    ->setCellValue("I$x", $all_record['addrver_insuff_raised_date_2'])
    ->setCellValue("J$x", $all_record['addrver_closure_date'])
    ->setCellValue("K$x", $all_record['empver'])
    ->setCellValue("L$x", $all_record['empres_remarks_'])
    ->setCellValue("M$x", $all_record['empver_insuff_remarks_1'])
    ->setCellValue("N$x", $all_record['empver_insuff_raised_date_1'])
    ->setCellValue("O$x", $all_record['empver_insuff_remarks_2'])
    ->setCellValue("P$x", $all_record['empver_insuff_raised_date_2'])
    ->setCellValue("Q$x", $all_record['empver_discrepancy_remark_1'])
    ->setCellValue("R$x", $all_record['empver_discrepancy_remark_2'])
    ->setCellValue("S$x", $all_record['empver_closure_date'])
    ->setCellValue("T$x", $all_record['verifiers_email_id'])
    ->setCellValue("U$x", $all_record['addrver_insuffcleardate_2'])
    ->setCellValue("V$x", $all_record['empver_insuffcleardate_2'])
    ->setCellValue("W$x", $all_record['Location'])
    ->setCellValue("X$x", $all_record['Department'])
    ->setCellValue("Y$x", $all_record['address'])
    ->setCellValue("Z$x", $all_record['coname']);

    $x++;
    }
    // Rename worksheet
    $spreadsheet->getActiveSheet()->setTitle('Candidate Records');

    $spreadsheet->setActiveSheetIndex(0);

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment;filename=Candidates Records of .xlsx");
    header('Cache-Control: max-age=0');
    // If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');

    // If you're serving to IE over SSL, then the following may be needed
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header('Pragma: public'); // HTTP/1.0

    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Excel2007');

    $file_name = "Candidates_Records_Axis_Securities_".DATE(UPLOAD_FILE_DATE_FORMAT).".xls";

    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Excel2007');
    ob_start();
    $writer->save($file_upload_path . "/". $file_name);
    ob_end_clean();

    $this->update_request_data(array('file_name' =>  $file_name, 'folder_generated_status' => 1, 'folder_name' => $file_upload_path), array('id' => $report_requested_save_id));
    $date_range = $frm_details['activity_from_axis'].' to '.$frm_details['activity_to_axis'];

    $this->update_request_schedular_list(array('report_id' =>  $report_requested_save_id, 'date_range' =>$date_range,'file_name' => $file_name,'run_status' => 1,'last_run_by' => $this->user_info['id'],'last_run_on' =>date(DB_DATE_FORMAT)), array('id' => '2'));

    //  $json_array['file'] = "data:application/vnd.ms-excel;base64,".base64_encode($xlsData);

    // $json_array['file_name'] = "Activity Records";

    $json_array['message'] = "File Created Successfully";

    $json_array['status'] = SUCCESS_CODE;
    }
    else
    {
    $json_array['message'] = "Something went wrong,please try again";
    $json_array['status'] = ERROR_CODE;
    }
    }
    else
    {
    $json_array['message'] = "From date not greather than to date";
    $json_array['status'] = ERROR_CODE;
    }
    }
    else
    {
    $json_array['message'] = "Something went wrong,please try again";
    $json_array['status'] = ERROR_CODE;
    }

    echo_json($json_array);

    }

     */
    public function export_axis_report()
    {

        $json_array = array();

        if ($this->input->is_ajax_request()) {

            $frm_details['activity_from_axis'] = '01-01-2009';
            $frm_details['activity_to_axis'] = date("d-m-Y");
            if ($frm_details['activity_from_axis'] <= $frm_details['activity_to_axis']) {

                $requested_data = array('type' => 'Axis Tracker', 'requested_id' => $this->user_info['id'], 'file_name' => '', 'created_on' => date(DB_DATE_FORMAT), 'mail_send_status' => 0, 'folder_generated_status' => 0, 'downloaded_status' => 0, 'downloaded_on_date' => '', 'folder_name' => '');

                $report_requested_save_id = $this->report_generated_user->report_requested_save($requested_data);

                if ($report_requested_save_id) {

                    $url = "cli_request_only generate_excel_axis_report $report_requested_save_id";

                    $cmd = 'php /var/www/html/'.SITE_FOLDER.'index.php ' . $url;

                    //   $this->candidates_model->report_requested_save(array('query'=>$cmd),array('id'=>$report_requested_save_id));

                    if (substr(php_uname(), 0, 7) == "Windows") {
                        pclose(popen("start /MIN " . $cmd, "r"));
                    } else {
                        exec($cmd . " > /dev/null &");
                    }

                    $json_array['message'] = "File Created Successfully";

                    $json_array['status'] = SUCCESS_CODE;
                } else {
                    $json_array['message'] = "Something went wrong,please try again";
                    $json_array['status'] = ERROR_CODE;
                }
            } else {
                $json_array['message'] = "From date not greather than to date";
                $json_array['status'] = ERROR_CODE;
            }
        } else {
            $json_array['message'] = "Something went wrong,please try again";
            $json_array['status'] = ERROR_CODE;
        }

        echo_json($json_array);

    }

    public function export_axis_ikya_report()
    {

        $json_array = array();

        if ($this->input->is_ajax_request()) {

            $frm_details['activity_from_axis'] = '01-01-2009';
            $frm_details['activity_to_axis'] = date("d-m-Y");
            if ($frm_details['activity_from_axis'] <= $frm_details['activity_to_axis']) {

                $requested_data = array('type' => 'Axis Securities Limited (Ikya)', 'requested_id' => $this->user_info['id'], 'file_name' => '', 'created_on' => date(DB_DATE_FORMAT), 'mail_send_status' => 0, 'folder_generated_status' => 0, 'downloaded_status' => 0, 'downloaded_on_date' => '', 'folder_name' => '');

                $report_requested_save_id = $this->report_generated_user->report_requested_save($requested_data);

                if ($report_requested_save_id) {
                    $url = "cli_request_only generate_excel_axis_ikya_report $report_requested_save_id";

                    $cmd = 'php /var/www/html/'.SITE_FOLDER.'index.php ' . $url;

                    //   $this->candidates_model->report_requested_save(array('query'=>$cmd),array('id'=>$report_requested_save_id));

                    if (substr(php_uname(), 0, 7) == "Windows") {
                        pclose(popen("start /MIN " . $cmd, "r"));
                    } else {
                        exec($cmd . " > /dev/null &");
                    }

                    $json_array['message'] = "File Created Successfully";

                    $json_array['status'] = SUCCESS_CODE;
                } else {
                    $json_array['message'] = "Something went wrong,please try again";
                    $json_array['status'] = ERROR_CODE;
                }
            } else {
                $json_array['message'] = "From date not greather than to date";
                $json_array['status'] = ERROR_CODE;
            }
        } else {
            $json_array['message'] = "Something went wrong,please try again";
            $json_array['status'] = ERROR_CODE;
        }

        echo_json($json_array);

    }

    public function export_axis_teamlease_report()
    {

        $json_array = array();

        if ($this->input->is_ajax_request()) {

            $frm_details['activity_from_axis'] = '01-01-2009';
            $frm_details['activity_to_axis'] = date("d-m-Y");
            if ($frm_details['activity_from_axis'] <= $frm_details['activity_to_axis']) {

                $requested_data = array('type' => 'Axis Securities Limited (Teamlease)', 'requested_id' => $this->user_info['id'], 'file_name' => '', 'created_on' => date(DB_DATE_FORMAT), 'mail_send_status' => 0, 'folder_generated_status' => 0, 'downloaded_status' => 0, 'downloaded_on_date' => '', 'folder_name' => '');

                $report_requested_save_id = $this->report_generated_user->report_requested_save($requested_data);

                if ($report_requested_save_id) {
                    $url = "cli_request_only generate_excel_axis_teamlease_report $report_requested_save_id";

                    $cmd = 'php /var/www/html/'.SITE_FOLDER.'index.php ' . $url;

                    //   $this->candidates_model->report_requested_save(array('query'=>$cmd),array('id'=>$report_requested_save_id));

                    if (substr(php_uname(), 0, 7) == "Windows") {
                        pclose(popen("start /MIN " . $cmd, "r"));
                    } else {
                        exec($cmd . " > /dev/null &");
                    }

                    $json_array['message'] = "File Created Successfully";

                    $json_array['status'] = SUCCESS_CODE;
                } else {
                    $json_array['message'] = "Something went wrong,please try again";
                    $json_array['status'] = ERROR_CODE;
                }
            } else {
                $json_array['message'] = "From date not greather than to date";
                $json_array['status'] = ERROR_CODE;
            }
        } else {
            $json_array['message'] = "Something went wrong,please try again";
            $json_array['status'] = ERROR_CODE;
        }

        echo_json($json_array);

    }

}
