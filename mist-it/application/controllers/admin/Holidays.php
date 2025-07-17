<?php defined('BASEPATH') or exit('No direct script access allowed');

class Holidays extends MY_Controller
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

        $this->perm_array = array('page_id' => 25);
        $this->load->model(array('holiday_model'));

    }

    public function index()
    {
        $data['header_title'] = "Holidays Dates"; // $data['holidays_lists']  = $this->get_holidays_list();

        $this->load->view('admin/header', $data);

        $this->load->view('admin/holidays_list');

        $this->load->view('admin/footer');
    }

    public function export_to_excel_holiday()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {
            set_time_limit(0);

            $start_from = $this->input->post('start_from');

            $end_to = $this->input->post('end_to');

            if ($start_from <= $end_to) {

                $all_records = $this->holiday_model->holiday_export($start_from, $end_to);

                require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
                // Create new Spreadsheet object
                $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

                // Set document properties
                $spreadsheet->getProperties()->setCreator(CRMNAME)
                    ->setLastModifiedBy(CRMNAME)
                    ->setTitle(CRMNAME)
                    ->setSubject('Holiday List')
                    ->setDescription('Holiday List');

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
                $spreadsheet->getActiveSheet()->getStyle('A1:E1')->applyFromArray($styleArray);
                // auto fit column to content
                foreach (range('A', 'E') as $columnID) {
                    $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                        ->setWidth(20);
                }
                // set the names of header cells

                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A1", 'ID')
                    ->setCellValue("B1", 'Holiday List')
                    ->setCellValue("C1", 'Remarks')
                    ->setCellValue("D1", 'Created By')
                    ->setCellValue("E1", 'Created On');

                // Add some data
                $x = 2;
                $y = 1;
                foreach ($all_records as $all_record) {
                    $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue("A$x", $y)
                        ->setCellValue("B$x", convert_db_to_display_date($all_record['holiday_date']))
                        ->setCellValue("C$x", $all_record['remark'])
                        ->setCellValue("D$x", $all_record['created_user_name'])
                        ->setCellValue("E$x", convert_db_to_display_date($all_record['created_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12));
                    $x++;
                    $y++;
                }
                // Rename worksheet
                $spreadsheet->getActiveSheet()->setTitle('Holiday Dates');

                // set right to left direction
                //    $spreadsheet->getActiveSheet()->setRightToLeft(true);

                // Set active sheet index to the first sheet, so Excel opens this as the first sheet
                $spreadsheet->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header("Content-Disposition: attachment;filename=Holiday Dates of .xlsx");
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

                $json_array['file_name'] = "Holiday Date";

                $json_array['message'] = "File downloaded successfully,please check in download folder";

                $json_array['status'] = SUCCESS_CODE;

            } else {
                $json_array['message'] = "Start Date Cannot Greather than End date";

                $json_array['status'] = ERROR_CODE;

            }

        } else {
            $json_array['message'] = "Something went wrong,please try again";

            $json_array['status'] = ERROR_CODE;
        }

        echo_json($json_array);

    }

    public function holiday_view_datatable()
    {
        if ($this->input->is_ajax_request()) {

            $params = $add_candidates = $data_arry = $columns = array();

            $params = $_REQUEST;

            $columns = array('holiday_date', 'remark', 'created_on', 'created_by', 'status');

            $where_arry = array();

            $add_holidays = $this->holiday_model->get_holidays_list($where_arry, $params, $columns);

            $totalRecords = count($this->holiday_model->get_holidays_list_count($where_arry, $params, $columns));

            $x = 0;

            foreach ($add_holidays as $add_holiday) {

                $delete_access = ($this->permission['access_admin_holiday_delete']) ? 'data-accessUrl' : 'data-url';

                $data_arry[$x]['id'] = $x + 1;
                $data_arry[$x]['holiday_date'] = convert_db_to_display_date($add_holiday['holiday_date']);
                $data_arry[$x]['created_user_name'] = $add_holiday['created_user_name'];
                $data_arry[$x]['remark'] = $add_holiday['remark'];
                $data_arry[$x]['created_on'] = convert_db_to_display_date($add_holiday['created_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);
                $data_arry[$x]['delete'] = "<a href='javascript:void(0)' class='deleteURL' " . $delete_access . "=" . ADMIN_SITE_URL . 'holidays/delete/' . $add_holiday['id'] . '/' . $add_holiday['holiday_date'] . "><i class='fa fa-trash'></i> Delete</a>";
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
            permission_denied();
        }

    }

    public function add()
    {
        $data['header_title'] = 'TAT Holiday';

        $this->load->view('admin/header', $data);

        $this->load->view('admin/holidays_add');

        $this->load->view('admin/footer');
    }

    private function save_holidays_list($arrdata)
    {
        $this->CI = &get_instance();

        $this->CI->db->insert('holiday_dates', $arrdata);

        record_db_error($this->CI->db->last_query());

        return $this->db->insert_id();
    }

    public function save()
    {
        $frm_details = $this->input->post();

        $json_array = array();

        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('holiday_date', 'Date', 'required');

            $this->form_validation->set_rules('remark', 'Remark', 'required');

            if ($this->form_validation->run() == false) {
                $json_array['message'] = validation_errors('', '');

                $json_array['status'] = ERROR_CODE;
            } else {
                $frm_details = $this->input->post();

                $holiday_dates = convert_display_to_db_date($frm_details['holiday_date']);

                $holiday_date = strtotime("now", strtotime($holiday_dates));

                $day_name = date("D", $holiday_date);

                if ($day_name == 'Sat' || $day_name == 'Sun') {

                    $json_array['message'] = 'Inserted date is either Saturday or Sunday';

                    $json_array['status'] = ERROR_CODE;

                } else {
                    $date_exist = $this->holiday_model->get_holidays_exists_or_not(array('holiday_date' => $holiday_dates, 'status' => 1));

                    if (empty($date_exist)) {
                        $fields = array('holiday_date' => $holiday_dates,
                            'remark' => $frm_details['remark'],
                            'created_by' => $this->user_info['id'],
                            'created_on' => date(DB_DATE_FORMAT),
                            'status' => 1,
                        );

                        $result = $this->save_holidays_list($fields);

                        if ($result) {

                            // $result_holiday  = $this->auto_increament_date_holiday_add($frm_details['holiday_date']);

                            $json_array['message'] = 'Holiday Stored Successfully';

                            $json_array['redirect'] = ADMIN_SITE_URL . 'holidays';

                            $json_array['status'] = SUCCESS_CODE;

                        } else {
                            $json_array['message'] = 'Something went wrong, please try again';

                            $json_array['status'] = ERROR_CODE;
                        }
                    } else {
                        $json_array['message'] = 'Selected Date Already Exists';

                        $json_array['status'] = ERROR_CODE;
                    }
                }
            }
        } else {
            $json_array['message'] = 'Something went wrong, please try again';

            $json_array['status'] = ERROR_CODE;
        }

        echo_json($json_array);
    }

    public function delete($id = null, $date = null)
    {

        if ($this->input->is_ajax_request() && $this->permission['access_admin_holidate_delete'] == true) {
            if ($id) {
                $this->CI = &get_instance();
                $check = date('w', strtotime($date));

                if ($check == 0 || $check == 6) {
                    $json_array['message'] = "Can't delete weekend.";

                    $json_array['status'] = ERROR_CODE;
                } else {
                    $result = $this->CI->db->delete('holiday_dates', array('id' => $id));

                    if ($result) {
                        $json_array['message'] = 'Deleted Successfully';

                        $json_array['redirect'] = ADMIN_SITE_URL . 'holidays';

                        $json_array['status'] = SUCCESS_CODE;
                    } else {
                        $json_array['message'] = 'Something went wrong, please try again';

                        $json_array['status'] = ERROR_CODE;
                    }
                }
            } else {
                $json_array['message'] = 'Something went wrong, please try again';

                $json_array['status'] = ERROR_CODE;
            }

            echo_json($json_array);
        } else {
            permission_denied();
        }
    }
}
