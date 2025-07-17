<?php defined('BASEPATH') or exit('No direct script access allowed');

class Billing extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!$this->is_admin_logged_in()) {
            redirect('admin/login');
            exit();
        }
        $this->perm_array = array('page_id' => 21);
    }

    public function index()
    {
        $data['header_title'] = "Billing Details";

        $this->load->view('admin/header', $data);

        $this->load->view('admin/billing_list_view');

        $this->load->view('admin/footer');
    }

    public function client()
    {
        $data['header_title'] = "Billing Details";

        $data['clients'] = $this->get_clients(array('status' => STATUS_ACTIVE));

        $this->load->view('admin/header', $data);

        $this->load->view('admin/billing_list_view');

        $this->load->view('admin/footer');
    }

    public function template_download()
    {
        if ($this->input->is_ajax_request()) {

            require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
            // Create new Spreadsheet object
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            // Set document properties
            $spreadsheet->getProperties()->setCreator(CRMNAME)
                ->setLastModifiedBy(CRMNAME)
                ->setTitle(CRMNAME)
                ->setSubject('Billing Import Template')
                ->setDescription('Billing Import Template File for bulk upload');

            $styleArray = array(
                'fill' => array(
                    'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startcolor' => array(
                        'rgb' => 'FF0000',
                    ),
                ),
            );
            $spreadsheet->getActiveSheet()->getStyle('A1:B1')->applyFromArray($styleArray);
            // $spreadsheet->getActiveSheet()->getStyle('F1:J1')->applyFromArray($styleArray);

            foreach (range('A', 'B') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                    ->setWidth(20);
            }

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A1", 'Component Ref No.')
                ->setCellValue("B1", 'Details');

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
                $objValidation->setPromptTitle('Insert Value');
                $objValidation->setPrompt('Please insert value.');

            }

            $spreadsheet->getActiveSheet()->setTitle('Billing Records');
            $spreadsheet->setActiveSheetIndex(0);
            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment;filename=Billing Bulk Uplaod Template.xlsx");
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

            $json_array['file_name'] = "Billing Bulk Uplaod Template";

            $json_array['message'] = "File downloaded successfully,please check in download folder";

            $json_array['status'] = SUCCESS_CODE;
        } else {
            $json_array['file_name'] = "Billing Bulk Uplaod Template";

            $json_array['message'] = "File downloaded failed,please check in download folder";

            $json_array['status'] = ERROR_CODE;
        }
        echo_json($json_array);
    }

    public function bulk_upload_billing()
    {

        if ($this->input->is_ajax_request()) {
            $clientid = $this->input->post('clientid');
            $entity = $this->input->post('entity');
            $package = $this->input->post('package');
            $component_type = $this->input->post('component_type');

            $file_upload_path = SITE_BASE_PATH . BILLING_REPORT;

            if (!folder_exist($file_upload_path)) {
                mkdir($file_upload_path, 0777);
            } else if (!is_writable($file_upload_path)) {
                $message = 'Problem while uploading, folder permission';
            }

            $file_upload_path = $file_upload_path . $clientid;

            if (!folder_exist($file_upload_path)) {
                mkdir($file_upload_path, 0777);
            } else if (!is_writable($file_upload_path)) {
                $message = 'Problem while uploading, folder permission';
            }

            $uplaod_details = array('file_upload_path' => $file_upload_path, 'file_data' => $_FILES['billing_bulk_sheet'], 'file_permission' => 'csv|xls|xlsx', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 100);
            $upload_result = $this->file_uplod($uplaod_details);

            $record = array();

            if (!empty($upload_result) && $upload_result['status'] == true) {

                $raw_filename = $_FILES['billing_bulk_sheet']['tmp_name'];
                //$BatchRefNumber = $this->BatchRefNumber($clientid);

                $headerLine = 0;
                $file = fopen($raw_filename, "r");

                $this->load->library('excel_reader', array('file_name' => $file_upload_path . '/' . $upload_result['file_name']));

                $excel_handler = $this->excel_reader->file_handler;

                $excel_data = $excel_handler->rows();

                if (!empty($excel_data))
                // while (($value = fgetcsv($file, 1000, ",")) !== FALSE)
                {
                    unset($excel_data[0]);

                    $excel_data = array_map("unserialize", array_unique(array_map("serialize", $excel_data)));

                    foreach ($excel_data as $value) {

                        if (count($value) <= 1) {
                            continue;

                        }

                        if ($component_type == 'address') {
                            $this->load->model('addressver_model');
                            $check_record_exits = $this->addressver_model->select(true, array('id', 'clientid'), array('add_com_ref' => $value[0]));

                            if (!empty($check_record_exits) && $value[0] != "" && $value[1] != "") {

                                $update = $this->addressver_model->save(array('build_date' => $value[1]), array('add_com_ref' => $value[0]));
                            }

                        } elseif ($component_type == 'employment') {

                            $this->load->model('employment_model');
                            $check_record_exits = $this->employment_model->select(true, array('id', 'clientid'), array('emp_com_ref' => $value[0]));

                            if (!empty($check_record_exits) && $value[0] != "" && $value[1] != "") {

                                $update = $this->employment_model->save(array('build_date' => $value[1]), array('emp_com_ref' => $value[0]));
                            }

                        } elseif ($component_type == 'education') {
                            $this->load->model('education_model');
                            $check_record_exits = $this->education_model->select(true, array('id', 'clientid'), array('education_com_ref' => $value[0]));

                            if (!empty($check_record_exits) && $value[0] != "" && $value[1] != "") {

                                $update = $this->education_model->save(array('build_date' => $value[1]), array('education_com_ref' => $value[0]));
                            }

                        } elseif ($component_type == 'reference') {
                            $this->load->model('reference_verificatiion_model');
                            $check_record_exits = $this->reference_verificatiion_model->select(true, array('id', 'clientid'), array('reference_com_ref' => $value[0]));

                            if (!empty($check_record_exits) && $value[0] != "" && $value[1] != "") {

                                $update = $this->reference_verificatiion_model->save(array('build_date' => $value[1]), array('reference_com_ref' => $value[0]));
                            }

                        } elseif ($component_type == 'court') {
                            $this->load->model('court_verificatiion_model');
                            $check_record_exits = $this->court_verificatiion_model->select(true, array('id', 'clientid'), array('court_com_ref' => $value[0]));

                            if (!empty($check_record_exits) && $value[0] != "" && $value[1] != "") {

                                $update = $this->court_verificatiion_model->save(array('build_date' => $value[1]), array('court_com_ref' => $value[0]));
                            }

                        } elseif ($component_type == 'global') {
                            $this->load->model('global_database_model');
                            $check_record_exits = $this->global_database_model->select(true, array('id', 'clientid'), array('global_com_ref' => $value[0]));

                            if (!empty($check_record_exits) && $value[0] != "" && $value[1] != "") {

                                $update = $this->global_database_model->save(array('build_date' => $value[1]), array('global_com_ref' => $value[0]));
                            }
                        } elseif ($component_type == 'pcc') {
                            $this->load->model('pcc_verificatiion_model');
                            $check_record_exits = $this->pcc_verificatiion_model->select(true, array('id', 'clientid'), array('pcc_com_ref' => $value[0]));

                            if (!empty($check_record_exits) && $value[0] != "" && $value[1] != "") {

                                $update = $this->pcc_verificatiion_model->save(array('build_date' => $value[1]), array('pcc_com_ref' => $value[0]));
                            }

                        } elseif ($component_type == 'identity') {
                            $this->load->model('identity_model');
                            $check_record_exits = $this->identity_model->select(true, array('id', 'clientid'), array('identity_com_ref' => $value[0]));

                            if (!empty($check_record_exits) && $value[0] != "" && $value[1] != "") {

                                $update = $this->identity_model->save(array('build_date' => $value[1]), array('identity_com_ref' => $value[0]));
                            }
                        } elseif ($component_type == 'credit') {
                            $this->load->model('credit_report_model');
                            $check_record_exits = $this->credit_report_model->select(true, array('id', 'clientid'), array('credit_report_com_ref' => $value[0]));

                            if (!empty($check_record_exits) && $value[0] != "" && $value[1] != "") {

                                $update = $this->credit_report_model->save(array('build_date' => $value[1]), array('credit_report_com_ref' => $value[0]));
                            }
                        } elseif ($component_type == 'drugs') {
                            $this->load->model('drug_verificatiion_model');
                            $check_record_exits = $this->drug_verificatiion_model->select(true, array('id', 'clientid'), array('drug_com_ref ' => $value[0]));

                            if (!empty($check_record_exits) && $value[0] != "" && $value[1] != "") {

                                $update = $this->drug_verificatiion_model->save(array('build_date' => $value[1]), array('drug_com_ref ' => $value[0]));
                            }
                        }

                        $data['success'] = $value[0] . "This Reference Code Records Updated Successfully";
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
