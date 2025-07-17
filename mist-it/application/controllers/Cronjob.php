<?php defined('BASEPATH') or exit('No direct script access allowed');

class Cronjob extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function Update_tat_status()
    {

        $this->CI = &get_instance();

        $today = date("Y-m-d");

        $today_date = strtotime($today);

        $get_holiday1 = $this->get_holiday();

        $get_holiday = array_map('current', $get_holiday1);

        $day_name = date("D", $today_date);

        if (($day_name == 'Mon' || $day_name == 'Tue' || $day_name == 'Wen' || $day_name == 'Thu' || $day_name == 'Fri') && (in_array(date("Y-m-d"), $get_holiday) == false)) {

            $query_address = $this->CI->db->query("SELECT addrver.*,`addrverres`.`verfstatus`,`addrverres`.`var_filter_status`,`addrverres`.`var_report_status`  FROM addrver join `addrverres`  ON addrverres.`addrverid` = addrver.`id` WHERE `addrverres`.`var_filter_status` = 'WIP' and `addrver`.`tat_status` != 'OUT' ");

            record_db_error($this->CI->db->last_query());

            $address_result = $query_address->result_array();

            if (!empty($address_result)) {

                foreach ($address_result as $key => $value) {

                    $x = 1;
                    $counter_1 = 0;
                    while ($x <= 10) {

                        $yesterday = date('Y-m-d', strtotime('-' . $x . ' day', strtotime($address_result[$key]['due_date'])));

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

                    if ($address_result[$key]['due_date'] < $today) {
                        $new_tat = "OUT TAT";
                    } elseif ($address_result[$key]['due_date'] == $today) {
                        $new_tat = "TDY TAT";
                    } elseif (in_array(date("Y-m-d"), $date_array) == true) {
                        $new_tat = "AP TAT";
                    } else {
                        $new_tat = "IN TAT";
                    }

                    $address_result_update = $this->CI->db->update_string('addrver', array('tat_status' => $new_tat), array('id' => $address_result[$key]['id']));

                    $result = $this->CI->db->query($address_result_update);

                    record_db_error($this->CI->db->last_query());

                }

                return $result;
            } else {
                return false;
            }

        } else {

        }

    }
    public function get_holiday()
    {
        $this->CI = &get_instance();

        $query = $this->CI->db->query("SELECT holiday_date  FROM holiday_dates WHERE status = 1");

        record_db_error($this->CI->db->last_query());

        $result = $query->result_array();

        if (!empty($result)) {
            return $result;
        }
        return false;

    }

    public function Update_tat_status_candidate()
    {

        $this->load->model('candidates_model');

        $candidate_details = $this->candidates_model->select(true, array('*'), array('status' => 1, 'overallstatus' => 1));

        foreach ($candidate_details as $candidate_detail) {

            unset($component_max_value);

            $client_components = $this->candidates_model->get_entitypackages(array('tbl_clients_id' => $candidate_detail['clientid'], 'entity' => $candidate_detail['entity'], 'package' => $candidate_detail['package']));

            if (isset($client_components[0]['component_id'])) {
                $component_check = explode(',', $client_components[0]['component_id']);
            } else {
                $component_check = array();
            }

            if (in_array('addrver', $component_check)) {
                $result = $this->candidates_model->get_addres_due_tat_status(array('addrver.candsid' => $candidate_detail['id'], 'ev1.var_filter_status' => 'WIP'));

                if (!empty($result)) {

                    foreach ($result as $key => $value) {
                        $component_max_value[] = array($value['due_date']);
                    }
                }
            }

            if (in_array('empver', $component_check));
            {
                $result = $this->candidates_model->get_employment_due_tat_status(array('empver.candsid' => $candidate_detail['id'], 'ev1.var_filter_status' => 'WIP'));

                if (!empty($result)) {
                    foreach ($result as $key => $value) {
                        $component_max_value[] = array($value['due_date']);
                    }
                }
            }

            if (in_array('eduver', $component_check));
            {
                $result = $this->candidates_model->get_education_due_tat_status(array('education.candsid' => $candidate_detail['id'], 'ev1.var_filter_status' => 'WIP'));

                if (!empty($result)) {
                    foreach ($result as $key => $value) {
                        $component_max_value[] = array($value['due_date']);
                    }
                }
            }

            if (in_array('refver', $component_check));
            {
                $result = $this->candidates_model->get_reference_due_tat_status(array('reference.candsid' => $candidate_detail['id'], 'ev1.var_filter_status' => 'WIP'));

                if (!empty($result)) {
                    foreach ($result as $key => $value) {
                        $component_max_value[] = array($value['due_date']);
                    }
                }
            }

            if (in_array('courtver', $component_check));
            {
                $result = $this->candidates_model->get_court_due_tat_status(array('courtver.candsid' => $candidate_detail['id'], 'ev1.var_filter_status' => 'WIP'));

                if (!empty($result)) {
                    foreach ($result as $key => $value) {
                        $component_max_value[] = array($value['due_date']);
                    }
                }
            }

            if (in_array('globdbver', $component_check));
            {
                $result = $this->candidates_model->get_global_due_tat_status(array('glodbver.candsid' => $candidate_detail['id'], 'ev1.var_filter_status' => 'WIP'));

                if (!empty($result)) {
                    foreach ($result as $key => $value) {
                        $component_max_value[] = array($value['due_date']);
                    }
                }
            }

            if (in_array('crimver', $component_check));
            {
                $result = $this->candidates_model->get_pcc_due_tat_status(array('pcc.candsid' => $candidate_detail['id'], 'ev1.var_filter_status' => 'WIP'));

                if (!empty($result)) {
                    foreach ($result as $key => $value) {
                        $component_max_value[] = array($value['due_date']);
                    }
                }
            }

            if (in_array('identity', $component_check));
            {
                $result = $this->candidates_model->get_identity_due_tat_status(array('identity.candsid' => $candidate_detail['id'], 'ev1.var_filter_status' => 'WIP'));

                if (!empty($result)) {
                    foreach ($result as $key => $value) {
                        $component_max_value[] = array($value['due_date']);
                    }
                }
            }

            if (in_array('cbrver', $component_check));
            {
                $result = $this->candidates_model->get_credit_report_due_tat_status(array('credit_report.candsid' => $candidate_detail['id'], 'ev1.var_filter_status' => 'WIP'));

                if (!empty($result)) {
                    foreach ($result as $key => $value) {
                        $component_max_value[] = array($value['due_date']);
                    }
                }
            }

            if (in_array('narcver', $component_check));
            {
                $result = $this->candidates_model->get_drugs_due_tat_status(array('drug_narcotis.candsid' => $candidate_detail['id'], 'ev1.var_filter_status' => 'WIP'));

                if (!empty($result)) {
                    foreach ($result as $key => $value) {
                        $component_max_value[] = array($value['due_date']);
                    }
                }
            }
            $component_dates = $this->array_flatten($component_max_value);

            $dates = $component_dates['dates'];

            $max_date = max($dates);

            $get_holiday1 = $this->get_holiday();

            $get_holiday = array_map('current', $get_holiday1);

            $today = date("Y-m-d");

            $x = 1;
            $counter_1 = 0;
            while ($x <= 10) {

                $yesterday = date('Y-m-d', strtotime('-' . $x . ' day', strtotime($max_date)));

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

            $date_array = array($yesterday_main, $yesterday_ago_main);

            if (!empty($max_date)) {

                if ($max_date < $today) {
                    $new_tat = "OUT TAT";
                } elseif ($max_date == $today) {
                    $new_tat = "TDY TAT";
                } elseif (in_array(date("Y-m-d"), $date_array) == true) {
                    $new_tat = "AP TAT";
                } else {
                    $new_tat = "IN TAT";
                }

                $result_due_date = $this->candidates_model->update_candidate_due_date(array('due_date' => $max_date, 'tat_status' => $new_tat), array('id' => $candidate_detail['id']));

            }
        }

    }

    public function array_flatten($array)
    {

        if (!is_array($array)) {
            return false;
        }

        $result = array();

        foreach ($array as $key => $value) {

            $result[] = $value[0];

        }
        $new_arry = array('dates' => $result);
        return $new_arry;
    }

    public function get_vedor_datails_for_mail_all_component()
    {

    }

    /*  public function Vendor_Pending_Cases_Mail()
    {

    set_time_limit(0);

    $this->load->library('email');

    $this->load->model('vendor/Vendor_common_model');

    $vendor_details =  $this->Vendor_common_model->get_vendor_details();

    $all_records =  $this->Vendor_common_model->get_vedor_datails_for_mail();

    foreach ($vendor_details as $vendor_detail) {

    require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
    // Create new Spreadsheet object
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

    // Set document properties
    $spreadsheet->getProperties()->setCreator(CRMNAME)
    ->setLastModifiedBy(CRMNAME)
    ->setTitle(CRMNAME)
    ->setSubject('Vendor records')
    ->setDescription('Vendor records with their status');

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

    $spreadsheet->createSheet();
    $work_sheet_count=7;
    $work_sheet = 0;

    while($work_sheet<=$work_sheet_count)
    {

    if($work_sheet==0)
    {

    $spreadsheet->getActiveSheet($work_sheet)->getStyle('A1:N1')->applyFromArray($styleArray);

    // auto fit column to content
    foreach(range('A','N') as $columnID) {
    $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
    ->setWidth(20);
    }

    $spreadsheet->setActiveSheetIndex($work_sheet)

    ->setCellValue("A1",'Vendor Assigned on')
    ->setCellValue("B1",'Client Name')
    ->setCellValue("C1",'Component Ref No')
    ->setCellValue("D1",'Transaction No')
    ->setCellValue("E1",'Candidate Name')

    ->setCellValue("F1",'Fathers Name')
    ->setCellValue("G1",'Primary Contact')
    ->setCellValue("H1",'Contact No (2)')
    ->setCellValue("I1",'Contact No (3)')
    ->setCellValue("J1",'Address')
    ->setCellValue("K1",'City')
    ->setCellValue("L1",'Pincode')
    ->setCellValue("M1",'State')
    ->setCellValue("N1",'Vendor');

    $x = 2;

    foreach($all_records as $all_record){

    if($all_record['component_name'] == "Address")
    {

    $spreadsheet->setActiveSheetIndex($work_sheet);

    if($all_record['vendor_id'] == $vendor_detail['id'])
    {

    $spreadsheet->getActiveSheet()->setCellValue("A$x",$all_record['vendor_assign_on']);
    $spreadsheet->getActiveSheet()->setCellValue("B$x",$all_record['clientname']);
    $spreadsheet->getActiveSheet()->setCellValue("C$x",$all_record['component_ref_no']);
    $spreadsheet->getActiveSheet()->setCellValue("D$x",$all_record['trasaction_id']);
    $spreadsheet->getActiveSheet()->setCellValue("E$x",$all_record['CandidateName']);

    $spreadsheet->getActiveSheet()->setCellValue("F$x",$all_record['NameofCandidateFather']);
    $spreadsheet->getActiveSheet()->setCellValue("G$x",$all_record['CandidatesContactNumber']);
    $spreadsheet->getActiveSheet()->setCellValue("H$x",$all_record['ContactNo1']);
    $spreadsheet->getActiveSheet()->setCellValue("I$x",$all_record['ContactNo2']);
    $spreadsheet->getActiveSheet()->setCellValue("J$x",$all_record['address']);
    $spreadsheet->getActiveSheet()->setCellValue("K$x",$all_record['city']);
    $spreadsheet->getActiveSheet()->setCellValue("L$x",$all_record['pincode']);
    $spreadsheet->getActiveSheet()->setCellValue("M$x",$all_record['state']);
    $spreadsheet->getActiveSheet()->setCellValue("N$x",$all_record['vendor_name']);

    $x++;
    }

    }

    }

    $sheetData1 = $spreadsheet->getActiveSheet()->toArray(null,true,true,true);

    $sheetData2  =  count($sheetData1);

    $sheetData_address = $sheetData2 - 1;

    $spreadsheet->getActiveSheet()->setTitle('Address -'.$sheetData_address);

    $spreadsheet->setActiveSheetIndex($work_sheet);
    }

    if($work_sheet==1)
    {

    $spreadsheet->getActiveSheet($work_sheet)->getStyle('A1:G1')->applyFromArray($styleArray);

    // auto fit column to content
    foreach(range('A','G') as $columnID) {
    $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
    ->setWidth(20);
    }

    $spreadsheet->setActiveSheetIndex($work_sheet)

    ->setCellValue("A1",'Vendor Assigned on')
    ->setCellValue("B1",'Client Name')
    ->setCellValue("C1",'Component Ref No')
    ->setCellValue("D1",'Transaction No')
    ->setCellValue("E1",'Candidate Name')
    ->setCellValue("F1",'Company Name')
    ->setCellValue("G1",'Vendor');

    $x= 2;

    foreach($all_records as $all_record ){

    if($all_record['component_name'] == "Employment")
    {

    $spreadsheet->setActiveSheetIndex($work_sheet);

    if($all_record['vendor_id'] == $vendor_detail['id'])
    {

    $spreadsheet->getActiveSheet()->setCellValue("A$x",$all_record['vendor_assign_on']);
    $spreadsheet->getActiveSheet()->setCellValue("B$x",$all_record['clientname']);
    $spreadsheet->getActiveSheet()->setCellValue("C$x",$all_record['component_ref_no']);
    $spreadsheet->getActiveSheet()->setCellValue("D$x",$all_record['trasaction_id']);
    $spreadsheet->getActiveSheet()->setCellValue("E$x",$all_record['CandidateName']);

    $spreadsheet->getActiveSheet()->setCellValue("F$x",$all_record['company_name']);
    $spreadsheet->getActiveSheet()->setCellValue("G$x",$all_record['vendor_name']);

    $x++;
    }

    }

    }

    $sheetData1 = $spreadsheet->getActiveSheet()->toArray(null,true,true,true);

    $sheetData2  =  count($sheetData1);

    $sheetData_employment   =   $sheetData2 - 1;

    $spreadsheet->getActiveSheet()->setTitle('Employment -'.$sheetData_employment);

    $spreadsheet->setActiveSheetIndex($work_sheet);

    }
    if($work_sheet==2)
    {

    $spreadsheet->createSheet($work_sheet);
    $spreadsheet->getActiveSheet($work_sheet)->getStyle('A1:G1')->applyFromArray($styleArray);

    // auto fit column to content
    foreach(range('A','G') as $columnID) {
    $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
    ->setWidth(20);
    }

    $spreadsheet->setActiveSheetIndex($work_sheet)

    ->setCellValue("A1",'Vendor Assigned on')
    ->setCellValue("B1",'Client Name')
    ->setCellValue("C1",'Component Ref No')
    ->setCellValue("D1",'Transaction No')
    ->setCellValue("E1",'Candidate Name')
    ->setCellValue("F1",'Mode of Verification')
    ->setCellValue("G1",'Qualification')
    ->setCellValue("H1",'University')
    ->setCellValue("I1",'Grade/Class/Marks')
    ->setCellValue("J1",'Month Of Passing')
    ->setCellValue("K1",'Year Of Passing')
    ->setCellValue("L1",'Roll No')
    ->setCellValue("M1",'PRN No')
    ->setCellValue("N1",'Course Start date')
    ->setCellValue("O1",'Course End date')
    ->setCellValue("P1",'Major')
    ->setCellValue("Q1",'Vendor');

    $x= 2;

    foreach($all_records as $all_record ){

    if($all_record['component_name'] == "Education")
    {

    $spreadsheet->setActiveSheetIndex($work_sheet);

    if($all_record['vendor_id'] == $vendor_detail['id'])
    {

    $spreadsheet->getActiveSheet()->setCellValue("A$x",$all_record['vendor_assign_on']);
    $spreadsheet->getActiveSheet()->setCellValue("B$x",$all_record['clientname']);
    $spreadsheet->getActiveSheet()->setCellValue("C$x",$all_record['component_ref_no']);
    $spreadsheet->getActiveSheet()->setCellValue("D$x",$all_record['trasaction_id']);
    $spreadsheet->getActiveSheet()->setCellValue("E$x",$all_record['CandidateName']);

    $spreadsheet->getActiveSheet()->setCellValue("F$x",$all_record['mode_of_veri']);
    $spreadsheet->getActiveSheet()->setCellValue("G$x",$all_record['qualification_name']);
    $spreadsheet->getActiveSheet()->setCellValue("H$x",$all_record['university_name']);
    $spreadsheet->getActiveSheet()->setCellValue("I$x",$all_record['grade_class_marks']);
    $spreadsheet->getActiveSheet()->setCellValue("J$x",$all_record['month_of_passing']);
    $spreadsheet->getActiveSheet()->setCellValue("K$x",$all_record['year_of_passing']);
    $spreadsheet->getActiveSheet()->setCellValue("L$x",$all_record['roll_no']);
    $spreadsheet->getActiveSheet()->setCellValue("M$x",$all_record['PRN_no']);
    $spreadsheet->getActiveSheet()->setCellValue("N$x",$all_record['course_start_date']);
    $spreadsheet->getActiveSheet()->setCellValue("O$x",$all_record['course_end_date']);
    $spreadsheet->getActiveSheet()->setCellValue("P$x",$all_record['major']);
    $spreadsheet->getActiveSheet()->setCellValue("Q$x",$all_record['vendor_name']);

    $x++;
    }

    }

    }

    $sheetData1 = $spreadsheet->getActiveSheet()->toArray(null,true,true,true);

    $sheetData2  =  count($sheetData1);

    $sheetData_education   =   $sheetData2 - 1;

    $spreadsheet->getActiveSheet()->setTitle('Education -'.$sheetData_education);

    $spreadsheet->setActiveSheetIndex($work_sheet);

    }

    if($work_sheet==3)
    {

    $spreadsheet->createSheet($work_sheet);
    $spreadsheet->getActiveSheet($work_sheet)->getStyle('A1:Q1')->applyFromArray($styleArray);

    foreach(range('A','Q') as $columnID) {
    $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
    ->setWidth(20);
    }

    $spreadsheet->setActiveSheetIndex($work_sheet)

    ->setCellValue("A1",'Vendor Assigned on')
    ->setCellValue("B1",'Component Ref No')
    ->setCellValue("C1",'Transaction No')
    ->setCellValue("D1",'Candidate Name')
    ->setCellValue("E1",'Father Name')
    ->setCellValue("F1",'Date Of Birth')
    ->setCellValue("H1",'Address')
    ->setCellValue("I1",'City')
    ->setCellValue("J1",'Pincode')
    ->setCellValue("K1",'State')
    ->setCellValue("L1",'Vendor');

    $x= 2;

    foreach($all_records as $all_record ){

    if($all_record['component_name'] == "Court")
    {
    $spreadsheet->setActiveSheetIndex($work_sheet);

    if($all_record['vendor_id'] == $vendor_detail['id'])
    {
    $spreadsheet->getActiveSheet()->setCellValue("A$x",$all_record['vendor_assign_on']);
    $spreadsheet->getActiveSheet()->setCellValue("B$x",$all_record['component_ref_no']);
    $spreadsheet->getActiveSheet()->setCellValue("C$x",$all_record['trasaction_id']);
    $spreadsheet->getActiveSheet()->setCellValue("D$x",$all_record['CandidateName']);

    $spreadsheet->getActiveSheet()->setCellValue("E$x",$all_record['NameofCandidateFather']);
    $spreadsheet->getActiveSheet()->setCellValue("F$x",$all_record['DateofBirth']);
    $spreadsheet->getActiveSheet()->setCellValue("H$x",$all_record['street_address']);
    $spreadsheet->getActiveSheet()->setCellValue("I$x",$all_record['city']);
    $spreadsheet->getActiveSheet()->setCellValue("J$x",$all_record['pincode']);
    $spreadsheet->getActiveSheet()->setCellValue("K$x",$all_record['state']);
    $spreadsheet->getActiveSheet()->setCellValue("L$x",$all_record['vendor_name']);

    $x++;
    }

    }

    }

    $sheetData1 = $spreadsheet->getActiveSheet()->toArray(null,true,true,true);

    $sheetData2  =  count($sheetData1);

    $sheetData_court   =   $sheetData2 - 1;

    $spreadsheet->getActiveSheet()->setTitle('Court -'.$sheetData_court);

    $spreadsheet->setActiveSheetIndex($work_sheet);

    }

    if($work_sheet==4)
    {

    $spreadsheet->createSheet($work_sheet);
    $spreadsheet->getActiveSheet($work_sheet)->getStyle('A1:L1')->applyFromArray($styleArray);

    // auto fit column to content
    foreach(range('A','L') as $columnID) {
    $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
    ->setWidth(20);
    }

    $spreadsheet->setActiveSheetIndex($work_sheet)

    ->setCellValue("A1",'Vendor Assigned on')
    ->setCellValue("B1",'Component Ref No')
    ->setCellValue("C1",'Transaction No')
    ->setCellValue("D1",'Candidate Name')
    ->setCellValue("E1",'Father Name')
    ->setCellValue("F1",'Date Of Birth')
    ->setCellValue("H1",'Address')
    ->setCellValue("I1",'City')
    ->setCellValue("J1",'Pincode')
    ->setCellValue("K1",'State')
    ->setCellValue("L1",'Vendor');

    $x= 2;

    foreach($all_records as $all_record ){

    $spreadsheet->setActiveSheetIndex($work_sheet);

    if($all_record['component_name'] =="Global Database")
    {

    if($all_record['vendor_id'] == $vendor_detail['id'])
    {

    $spreadsheet->getActiveSheet()->setCellValue("A$x",$all_record['vendor_assign_on']);
    $spreadsheet->getActiveSheet()->setCellValue("B$x",$all_record['component_ref_no']);
    $spreadsheet->getActiveSheet()->setCellValue("C$x",$all_record['trasaction_id']);
    $spreadsheet->getActiveSheet()->setCellValue("D$x",$all_record['CandidateName']);

    $spreadsheet->getActiveSheet()->setCellValue("E$x",$all_record['NameofCandidateFather']);
    $spreadsheet->getActiveSheet()->setCellValue("F$x",$all_record['DateofBirth']);
    $spreadsheet->getActiveSheet()->setCellValue("H$x",$all_record['street_address']);
    $spreadsheet->getActiveSheet()->setCellValue("I$x",$all_record['city']);
    $spreadsheet->getActiveSheet()->setCellValue("J$x",$all_record['pincode']);
    $spreadsheet->getActiveSheet()->setCellValue("K$x",$all_record['state']);
    $spreadsheet->getActiveSheet()->setCellValue("L$x",$all_record['vendor_name']);

    $x++;
    }

    }

    }

    $sheetData1 = $spreadsheet->getActiveSheet()->toArray(null,true,true,true);

    $sheetData2  =  count($sheetData1);

    $sheetData_global   =   $sheetData2 - 1;

    $spreadsheet->getActiveSheet()->setTitle('Global -'.$sheetData_global);

    $spreadsheet->setActiveSheetIndex($work_sheet);

    }

    if($work_sheet==5)
    {

    $spreadsheet->createSheet($work_sheet);
    $spreadsheet->getActiveSheet($work_sheet)->getStyle('A1:L1')->applyFromArray($styleArray);

    // auto fit column to content
    foreach(range('A','L') as $columnID) {
    $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
    ->setWidth(20);
    }

    $spreadsheet->setActiveSheetIndex($work_sheet)

    ->setCellValue("A1",'Vendor Assigned on')
    ->setCellValue("B1",'Component Ref No')
    ->setCellValue("C1",'Transaction No')
    ->setCellValue("D1",'Candidate Name')
    ->setCellValue("E1",'Father Name')
    ->setCellValue("F1",'Date Of Birth')
    ->setCellValue("H1",'Address')
    ->setCellValue("I1",'City')
    ->setCellValue("J1",'Pincode')
    ->setCellValue("K1",'State')
    ->setCellValue("L1",'Vendor');

    $x= 2;

    foreach($all_records as $all_record ){

    $spreadsheet->setActiveSheetIndex($work_sheet);

    if($all_record['component_name'] =="PCC")
    {

    if($all_record['vendor_id'] == $vendor_detail['id'])
    {

    $spreadsheet->getActiveSheet()->setCellValue("A$x",$all_record['vendor_assign_on']);
    $spreadsheet->getActiveSheet()->setCellValue("B$x",$all_record['component_ref_no']);
    $spreadsheet->getActiveSheet()->setCellValue("C$x",$all_record['trasaction_id']);
    $spreadsheet->getActiveSheet()->setCellValue("D$x",$all_record['CandidateName']);

    $spreadsheet->getActiveSheet()->setCellValue("E$x",$all_record['NameofCandidateFather']);
    $spreadsheet->getActiveSheet()->setCellValue("F$x",$all_record['DateofBirth']);
    $spreadsheet->getActiveSheet()->setCellValue("H$x",$all_record['street_address']);
    $spreadsheet->getActiveSheet()->setCellValue("I$x",$all_record['city']);
    $spreadsheet->getActiveSheet()->setCellValue("J$x",$all_record['pincode']);
    $spreadsheet->getActiveSheet()->setCellValue("K$x",$all_record['state']);
    $spreadsheet->getActiveSheet()->setCellValue("L$x",$all_record['vendor_name']);

    $x++;
    }

    }

    }

    $sheetData1 = $spreadsheet->getActiveSheet()->toArray(null,true,true,true);

    $sheetData2  =  count($sheetData1);

    $sheetData_pcc   =   $sheetData2 - 1;

    $spreadsheet->getActiveSheet()->setTitle('PCC -'.$sheetData_pcc);

    $spreadsheet->setActiveSheetIndex($work_sheet);

    }

    if($work_sheet==6)
    {

    $spreadsheet->createSheet($work_sheet);
    $spreadsheet->getActiveSheet($work_sheet)->getStyle('A1:L1')->applyFromArray($styleArray);

    // auto fit column to content
    foreach(range('A','L') as $columnID) {
    $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
    ->setWidth(20);
    }

    $spreadsheet->setActiveSheetIndex($work_sheet)

    ->setCellValue("A1",'Vendor Assigned on')
    ->setCellValue("B1",'Component Ref No')
    ->setCellValue("C1",'Transaction No')
    ->setCellValue("D1",'Candidate Name')
    ->setCellValue("E1",'Father Name')
    ->setCellValue("F1",'Date Of Birth')
    ->setCellValue("G1",'Document Submitted')
    ->setCellValue("H1",'Vendor');

    $x= 2;

    foreach($all_records as $all_record ){

    $spreadsheet->setActiveSheetIndex($work_sheet);

    if($all_record['component_name'] =="Credit Report")
    {

    if($all_record['vendor_id'] == $vendor_detail['id'])
    {

    $spreadsheet->getActiveSheet()->setCellValue("A$x",$all_record['vendor_assign_on']);
    $spreadsheet->getActiveSheet()->setCellValue("B$x",$all_record['component_ref_no']);
    $spreadsheet->getActiveSheet()->setCellValue("C$x",$all_record['trasaction_id']);
    $spreadsheet->getActiveSheet()->setCellValue("D$x",$all_record['CandidateName']);

    $spreadsheet->getActiveSheet()->setCellValue("E$x",$all_record['NameofCandidateFather']);
    $spreadsheet->getActiveSheet()->setCellValue("F$x",$all_record['DateofBirth']);
    $spreadsheet->getActiveSheet()->setCellValue("G$x",$all_record['doc_submited']);
    $spreadsheet->getActiveSheet()->setCellValue("H$x",$all_record['vendor_name']);
    $x++;

    }
    }

    }

    $sheetData1 = $spreadsheet->getActiveSheet()->toArray(null,true,true,true);

    $sheetData2  =  count($sheetData1);

    $sheetData_credit   =   $sheetData2 - 1;

    $spreadsheet->getActiveSheet()->setTitle('Credit Report -'.$sheetData_credit);

    $spreadsheet->setActiveSheetIndex($work_sheet);

    }

    if($work_sheet==7)
    {

    $spreadsheet->getActiveSheet($work_sheet)->getStyle('A1:H1')->applyFromArray($styleArray);

    // auto fit column to content
    foreach(range('A','H') as $columnID) {
    $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
    ->setWidth(20);
    }
    }

    $work_sheet++;

    }

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment;filename=Vendor Records of .xlsx");
    header('Cache-Control: max-age=0');
    // If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');

    // If you're serving to IE over SSL, then the following may be needed
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header('Pragma: public'); // HTTP/1.0

    $file_upload_path = SITE_BASE_PATH.UPLOAD_FOLDER.'vendor_cases';

    if (!folder_exist($file_upload_path)) {
    mkdir($file_upload_path, 0777);
    }
    if (!folder_exist($file_upload_path)) {
    mkdir($file_upload_path, 0777);
    } else if (!is_writable($file_upload_path)) {
    mkdir($file_upload_path, 0777);
    }

    $file_name = "Vendor_".$vendor_detail['vendor_name'].".xls";

    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Excel2007');
    ob_start();
    $writer->save($file_upload_path . "/". $file_name);
    ob_end_clean();

    $email_tmpl_data['to_emails']  =  $vendor_detail['email_id'];
    $email_tmpl_data['attchment']  =   $file_name;
    $result = $this->email->vendor_case_send_mail($email_tmpl_data);
    $this->email->clear(TRUE);
    //  $json_array['file'] = "data:application/vnd.ms-excel;base64,".base64_encode($xlsData);

    //  $json_array['file_name'] = "Vendor Record";

    //  $json_array['message'] = "File downloaded successfully,please check in download folder";

    //  $json_array['status'] = SUCCESS_CODE;

    //  echo_json($json_array);
    }

    }
     */

    public function Vendor_Pending_Cases_Mail()
    {

        set_time_limit(0);

        $this->load->library('email');

        $this->load->model('vendor/Vendor_common_model');

        $vendor_details = $this->Vendor_common_model->get_vendor_details();

        //  $all_records =  $this->Vendor_common_model->get_vedor_datails_for_mail();

        foreach ($vendor_details as $vendor_detail) {

            $vendors_components = explode(',', $vendor_detail['vendors_components']);

            if (in_array("addrver", $vendors_components)) {

                $addrver = $this->Vendor_common_model->get_vedor_datails_for_address($vendor_detail['id']);

                if (!empty($addrver)) {

                    $count_datewise_record = $this->Vendor_common_model->get_address_date_wise_count($vendor_detail['id']);

                    $user_info = $this->Vendor_common_model->get_address_user_name_password(array('status' => 1, 'id' => 3));

                    $reporting_manager_info = $this->Vendor_common_model->get_repoting_manager_email_id(array('status' => 1, 'id' => $user_info[0]['reporting_manager']));

                    require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
                    // Create new Spreadsheet object
                    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

                    // Set document properties
                    $spreadsheet->getProperties()->setCreator(CRMNAME)
                        ->setLastModifiedBy(CRMNAME)
                        ->setTitle(CRMNAME)
                        ->setSubject('Vendor records')
                        ->setDescription('Vendor records with their status');

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

                    $spreadsheet->getActiveSheet()->getStyle('A1:N1')->applyFromArray($styleArray);

                    // auto fit column to content
                    foreach (range('A', 'N') as $columnID) {
                        $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                            ->setWidth(20);
                    }

                    $spreadsheet->setActiveSheetIndex(0)

                        ->setCellValue("A1", 'Vendor Assigned on')
                        ->setCellValue("B1", 'Client Name')
                        ->setCellValue("C1", 'Component Ref No')
                        ->setCellValue("D1", 'Transaction No')
                        ->setCellValue("E1", 'Candidate Name')

                        ->setCellValue("F1", 'Fathers Name')
                        ->setCellValue("G1", 'Primary Contact')
                        ->setCellValue("H1", 'Contact No (2)')
                        ->setCellValue("I1", 'Contact No (3)')
                        ->setCellValue("J1", 'Address')
                        ->setCellValue("K1", 'City')
                        ->setCellValue("L1", 'Pincode')
                        ->setCellValue("M1", 'State')
                        ->setCellValue("N1", 'Vendor');

                    $x = 2;

                    foreach ($addrver as $all_record) {

                        $spreadsheet->setActiveSheetIndex(0);

                        $spreadsheet->getActiveSheet()->setCellValue("A$x", convert_db_to_display_date($all_record['vendor_assign_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12));
                        $spreadsheet->getActiveSheet()->setCellValue("B$x", ucwords($all_record['clientname']));
                        $spreadsheet->getActiveSheet()->setCellValue("C$x", $all_record['component_ref_no']);
                        $spreadsheet->getActiveSheet()->setCellValue("D$x", $all_record['trasaction_id']);
                        $spreadsheet->getActiveSheet()->setCellValue("E$x", ucwords($all_record['CandidateName']));

                        $spreadsheet->getActiveSheet()->setCellValue("F$x", ucwords($all_record['NameofCandidateFather']));
                        $spreadsheet->getActiveSheet()->setCellValue("G$x", $all_record['CandidatesContactNumber']);
                        $spreadsheet->getActiveSheet()->setCellValue("H$x", $all_record['ContactNo1']);
                        $spreadsheet->getActiveSheet()->setCellValue("I$x", $all_record['ContactNo2']);
                        $spreadsheet->getActiveSheet()->setCellValue("J$x", ucwords($all_record['address']));
                        $spreadsheet->getActiveSheet()->setCellValue("K$x", ucwords($all_record['city']));
                        $spreadsheet->getActiveSheet()->setCellValue("L$x", $all_record['pincode']);
                        $spreadsheet->getActiveSheet()->setCellValue("M$x", ucwords($all_record['state']));
                        $spreadsheet->getActiveSheet()->setCellValue("N$x", ucwords($all_record['vendor_name']));

                        $x++;

                    }

                    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                    header("Content-Disposition: attachment;filename=Vendor Records .xlsx");
                    header('Cache-Control: max-age=0');
                    // If you're serving to IE 9, then the following may be needed
                    header('Cache-Control: max-age=1');

                    // If you're serving to IE over SSL, then the following may be needed
                    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
                    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                    header('Pragma: public'); // HTTP/1.0

                    $file_upload_path = SITE_BASE_PATH . UPLOAD_FOLDER . 'vendor_cases';

                    if (!folder_exist($file_upload_path)) {
                        mkdir($file_upload_path, 0777);
                    }
                    if (!folder_exist($file_upload_path)) {
                        mkdir($file_upload_path, 0777);
                    } else if (!is_writable($file_upload_path)) {
                        mkdir($file_upload_path, 0777);
                    }

                    $file_name = "Vendor_" . $vendor_detail['vendor_name'] . '_' . 'Address' . ".xls";

                    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Excel2007');
                    ob_start();
                    $writer->save($file_upload_path . "/" . $file_name);
                    ob_end_clean();

                    $subject = ucwords($vendor_detail['vendor_name']) . '_WIP cases_Address_' . date('d-m-Y');

                    $message = "<p>Dear Sir/Madam,</p><p>Please find attached pending cases.</p>
                  <p><b>Address :</b></p>";

                    $message .= "<table border = '1'>
                  <tr>
                  <th style='text-align:center'>Allocated date </th>
                  <th style='text-align:center'>Cases</th>
                  <th style='text-align:center'>Days</th>
                  </tr>";
                    $total = 0;
                    foreach ($count_datewise_record as $count_datewise_records) {
                        $hold_day = getNetWorkDays($count_datewise_records['date'], date("d-m-Y"));
                        $message .= '<tr>
                  <td style="text-align:center">' . $count_datewise_records['date'] . '</td>
                  <td style="text-align:center">' . $count_datewise_records['count_record'] . '</td>
                  <td style="text-align:center">' . $hold_day . '</td>
                  </tr>';
                        $total += $count_datewise_records['count_record'];
                    }

                    $message .= '<tr><td style="text-align:center"><b>Total Cases</b></td><td style="text-align:center" colspan="2"><b>' . $total . '</b></tr>';
                    $message .= "</table>";

                    $message .= "<p><b>Note :</b> <I>This is an automatically generated email. Please do not reply to it. If you have any queries with reference to the contents of this email contact your assigned SPOC with " . CRMNAME . " </I>";

                    $email_tmpl_data['to_emails'] = $vendor_detail['email_id'];
                    $email_tmpl_data['attchment'] = $file_name;
                    $email_tmpl_data['user_email_id'] = $user_info[0]['email'];
                    $email_tmpl_data['reporting_email_id'] = $reporting_manager_info[0]['email'];
                    $email_tmpl_data['vendor_name'] = $vendor_detail['vendor_name'];
                    $email_tmpl_data['message'] = $message;
                    $email_tmpl_data['subject'] = $subject;

                    $result = $this->email->vendor_case_send_mail($email_tmpl_data);
                    $this->email->clear(true);

                }
            }
            if (in_array("empver", $vendors_components)) {

                $empver = $this->Vendor_common_model->get_vedor_datails_for_employment($vendor_detail['id']);

                if (!empty($empver)) {

                    $count_datewise_record = $this->Vendor_common_model->get_employment_date_wise_count($vendor_detail['id']);

                    $user_info = $this->Vendor_common_model->get_address_user_name_password(array('status' => 1, 'id' => 3));

                    $reporting_manager_info = $this->Vendor_common_model->get_repoting_manager_email_id(array('status' => 1, 'id' => $user_info[0]['reporting_manager']));

                    require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
                    // Create new Spreadsheet object
                    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

                    // Set document properties
                    $spreadsheet->getProperties()->setCreator(CRMNAME)
                        ->setLastModifiedBy(CRMNAME)
                        ->setTitle(CRMNAME)
                        ->setSubject('Vendor records')
                        ->setDescription('Vendor records with their status');

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

                    $spreadsheet->getActiveSheet()->getStyle('A1:G1')->applyFromArray($styleArray);

                    // auto fit column to content
                    foreach (range('A', 'G') as $columnID) {
                        $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                            ->setWidth(20);
                    }

                    $spreadsheet->setActiveSheetIndex(0)

                        ->setCellValue("A1", 'Vendor Assigned on')
                        ->setCellValue("B1", 'Client Name')
                        ->setCellValue("C1", 'Component Ref No')
                        ->setCellValue("D1", 'Transaction No')
                        ->setCellValue("E1", 'Candidate Name')
                        ->setCellValue("F1", 'Company Name')
                        ->setCellValue("G1", 'Vendor');

                    $x = 2;

                    foreach ($empver as $all_record) {

                        $spreadsheet->setActiveSheetIndex(0);

                        $spreadsheet->getActiveSheet()->setCellValue("A$x", convert_db_to_display_date($all_record['vendor_assign_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12));
                        $spreadsheet->getActiveSheet()->setCellValue("B$x", ucwords($all_record['clientname']));
                        $spreadsheet->getActiveSheet()->setCellValue("C$x", $all_record['component_ref_no']);
                        $spreadsheet->getActiveSheet()->setCellValue("D$x", $all_record['trasaction_id']);
                        $spreadsheet->getActiveSheet()->setCellValue("E$x", ucwords($all_record['CandidateName']));

                        $spreadsheet->getActiveSheet()->setCellValue("F$x", ucwords($all_record['company_name']));
                        $spreadsheet->getActiveSheet()->setCellValue("G$x", ucwords($all_record['vendor_name']));

                        $x++;

                    }

                    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                    header("Content-Disposition: attachment;filename=Vendor Records .xlsx");
                    header('Cache-Control: max-age=0');
                    // If you're serving to IE 9, then the following may be needed
                    header('Cache-Control: max-age=1');

                    // If you're serving to IE over SSL, then the following may be needed
                    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
                    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                    header('Pragma: public'); // HTTP/1.0

                    $file_upload_path = SITE_BASE_PATH . UPLOAD_FOLDER . 'vendor_cases';

                    if (!folder_exist($file_upload_path)) {
                        mkdir($file_upload_path, 0777);
                    }
                    if (!folder_exist($file_upload_path)) {
                        mkdir($file_upload_path, 0777);
                    } else if (!is_writable($file_upload_path)) {
                        mkdir($file_upload_path, 0777);
                    }

                    $file_name = "Vendor_" . $vendor_detail['vendor_name'] . '_' . 'Employment' . ".xls";

                    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Excel2007');
                    ob_start();
                    $writer->save($file_upload_path . "/" . $file_name);
                    ob_end_clean();

                    $subject = ucwords($vendor_detail['vendor_name']) . '_WIP cases_Employment_' . date('d-m-Y');

                    $message = "<p>Dear Sir/Madam,</p><p>Please find attached pending cases.</p>
                  <p><b>Employment :</b></p>";

                    $message .= "<table border = '1'>
                  <tr>
                  <th style='text-align:center'>Allocated date </th>
                  <th style='text-align:center'>Cases</th>
                  <th style='text-align:center'>Days</th>
                  </tr>";
                    $total = 0;
                    foreach ($count_datewise_record as $count_datewise_records) {
                        $hold_day = getNetWorkDays($count_datewise_records['date'], date("d-m-Y"));
                        $message .= '<tr>
                  <td style="text-align:center">' . $count_datewise_records['date'] . '</td>
                  <td style="text-align:center">' . $count_datewise_records['count_record'] . '</td>
                  <td style="text-align:center">' . $hold_day . '</td>
                  </tr>';
                        $total += $count_datewise_records['count_record'];
                    }
                    $message .= '<tr><td style="text-align:center"><b>Total</b></td><td style="text-align:center" colspan = "2"><b>' . $total . '</b></tr>';
                    $message .= "</table>";

                    $message .= "<p><b>Note :</b> <I>This is an automatically generated email. Please do not reply to it. If you have any queries with reference to the contents of this email contact your assigned SPOC with " . CRMNAME . "</I>";

                    $email_tmpl_data['to_emails'] = $vendor_detail['email_id'];
                    $email_tmpl_data['attchment'] = $file_name;
                    $email_tmpl_data['user_email_id'] = $user_info[0]['email'];
                    $email_tmpl_data['reporting_email_id'] = $reporting_manager_info[0]['email'];
                    $email_tmpl_data['vendor_name'] = $vendor_detail['vendor_name'];
                    $email_tmpl_data['message'] = $message;
                    $email_tmpl_data['subject'] = $subject;

                    $result = $this->email->vendor_case_send_mail($email_tmpl_data);
                    $this->email->clear(true);

                }
            }
          /*  if (in_array("eduver", $vendors_components)) {

                $eduver = $this->Vendor_common_model->get_vedor_datails_for_education($vendor_detail['id']);

                if (!empty($eduver)) {
                    $count_datewise_record = $this->Vendor_common_model->get_education_date_wise_count($vendor_detail['id']);

                    $user_info = $this->Vendor_common_model->get_address_user_name_password(array('status' => 1, 'id' => 12));

                    $reporting_manager_info = $this->Vendor_common_model->get_repoting_manager_email_id(array('status' => 1, 'id' => $user_info[0]['reporting_manager']));

                    require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
                    // Create new Spreadsheet object
                    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

                    // Set document properties
                    $spreadsheet->getProperties()->setCreator(CRMNAME)
                        ->setLastModifiedBy(CRMNAME)
                        ->setTitle(CRMNAME)
                        ->setSubject('Vendor records')
                        ->setDescription('Vendor records with their status');

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

                    $spreadsheet->getActiveSheet()->getStyle('A1:Q1')->applyFromArray($styleArray);

                    // auto fit column to content
                    foreach (range('A', 'Q') as $columnID) {
                        $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                            ->setWidth(20);
                    }

                    $spreadsheet->setActiveSheetIndex(0)

                        ->setCellValue("A1", 'Vendor Assigned on')
                        ->setCellValue("B1", 'Client Name')
                        ->setCellValue("C1", 'Component Ref No')
                        ->setCellValue("D1", 'Transaction No')
                        ->setCellValue("E1", 'Candidate Name')
                        ->setCellValue("F1", 'Mode of Verification')
                        ->setCellValue("G1", 'Qualification')
                        ->setCellValue("H1", 'University')
                        ->setCellValue("I1", 'Grade/Class/Marks')
                        ->setCellValue("J1", 'Month Of Passing')
                        ->setCellValue("K1", 'Year Of Passing')
                        ->setCellValue("L1", 'Roll No')
                        ->setCellValue("M1", 'PRN No')
                        ->setCellValue("N1", 'Course Start date')
                        ->setCellValue("O1", 'Course End date')
                        ->setCellValue("P1", 'Major')
                        ->setCellValue("Q1", 'Vendor');

                    $x = 2;

                    foreach ($eduver as $all_record) {

                        $spreadsheet->setActiveSheetIndex(0);

                        $spreadsheet->getActiveSheet()->setCellValue("A$x", convert_db_to_display_date($all_record['vendor_assign_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12));
                        $spreadsheet->getActiveSheet()->setCellValue("B$x", ucwords($all_record['clientname']));
                        $spreadsheet->getActiveSheet()->setCellValue("C$x", $all_record['component_ref_no']);
                        $spreadsheet->getActiveSheet()->setCellValue("D$x", $all_record['trasaction_id']);
                        $spreadsheet->getActiveSheet()->setCellValue("E$x", ucwords($all_record['CandidateName']));

                        $spreadsheet->getActiveSheet()->setCellValue("F$x", $all_record['mode_of_veri']);
                        $spreadsheet->getActiveSheet()->setCellValue("G$x", ucwords($all_record['qualification_name']));
                        $spreadsheet->getActiveSheet()->setCellValue("H$x", ucwords($all_record['university_name']));
                        $spreadsheet->getActiveSheet()->setCellValue("I$x", ucwords($all_record['grade_class_marks']));
                        $spreadsheet->getActiveSheet()->setCellValue("J$x", ucwords($all_record['month_of_passing']));
                        $spreadsheet->getActiveSheet()->setCellValue("K$x", $all_record['year_of_passing']);
                        $spreadsheet->getActiveSheet()->setCellValue("L$x", $all_record['roll_no']);
                        $spreadsheet->getActiveSheet()->setCellValue("M$x", $all_record['PRN_no']);
                        $spreadsheet->getActiveSheet()->setCellValue("N$x", $all_record['course_start_date']);
                        $spreadsheet->getActiveSheet()->setCellValue("O$x", $all_record['course_end_date']);
                        $spreadsheet->getActiveSheet()->setCellValue("P$x", ucwords($all_record['major']));
                        $spreadsheet->getActiveSheet()->setCellValue("Q$x", ucwords($all_record['vendor_name']));
                        $x++;

                    }

                    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                    header("Content-Disposition: attachment;filename=Vendor Records .xlsx");
                    header('Cache-Control: max-age=0');
                    // If you're serving to IE 9, then the following may be needed
                    header('Cache-Control: max-age=1');

                    // If you're serving to IE over SSL, then the following may be needed
                    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
                    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                    header('Pragma: public'); // HTTP/1.0

                    $file_upload_path = SITE_BASE_PATH . UPLOAD_FOLDER . 'vendor_cases';

                    if (!folder_exist($file_upload_path)) {
                        mkdir($file_upload_path, 0777);
                    }
                    if (!folder_exist($file_upload_path)) {
                        mkdir($file_upload_path, 0777);
                    } else if (!is_writable($file_upload_path)) {
                        mkdir($file_upload_path, 0777);
                    }

                    $file_name = "Vendor_" . $vendor_detail['vendor_name'] . '_' . 'Education' . ".xls";

                    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Excel2007');
                    ob_start();
                    $writer->save($file_upload_path . "/" . $file_name);
                    ob_end_clean();

                    $subject = ucwords($vendor_detail['vendor_name']) . '_WIP cases_Education_' . date('d-m-Y');

                    $message = "<p>Dear Sir/Madam,</p><p>Please find attached pending cases.</p>
                  <p><b>Education :</b></p>";

                    $message .= "<table border = '1'>
                  <tr>
                  <th style='text-align:center'>Allocated date </th>
                  <th style='text-align:center'>Cases</th>
                  <th style='text-align:center'>Days</th>
                  </tr>";
                    $total = 0;
                    foreach ($count_datewise_record as $count_datewise_records) {
                        $hold_day = getNetWorkDays($count_datewise_records['date'], date("d-m-Y"));
                        $message .= '<tr>
                  <td style="text-align:center">' . $count_datewise_records['date'] . '</td>
                  <td style="text-align:center">' . $count_datewise_records['count_record'] . '</td>
                  <td style="text-align:center">' . $hold_day . '</td>
                  </tr>';
                        $total += $count_datewise_records['count_record'];

                    }
                    $message .= '<tr><td style="text-align:center"><b>Total</b></td><td style="text-align:center" colspan = "2"><b>' . $total . '</b></tr>';
                    $message .= "</table>";

                    $message .= "<p><b>Note :</b> <I>This is an automatically generated email. Please do not reply to it. If you have any queries with reference to the contents of this email contact your assigned SPOC with " . CRMNAME . " </I>";

                    $email_tmpl_data['to_emails'] = $vendor_detail['email_id'];
                    $email_tmpl_data['attchment'] = $file_name;
                    $email_tmpl_data['user_email_id'] = $user_info[0]['email'];
                    $email_tmpl_data['reporting_email_id'] = $reporting_manager_info[0]['email'];
                    $email_tmpl_data['vendor_name'] = $vendor_detail['vendor_name'];
                    $email_tmpl_data['message'] = $message;
                    $email_tmpl_data['subject'] = $subject;
                    $result = $this->email->vendor_case_send_mail($email_tmpl_data);
                    $this->email->clear(true);
                }

            }*/
            if (in_array("courtver", $vendors_components)) {

                $courtver = $this->Vendor_common_model->get_vedor_datails_for_court($vendor_detail['id']);
                if (!empty($courtver)) {

                    $count_datewise_record = $this->Vendor_common_model->get_court_date_wise_count($vendor_detail['id']);

                    $user_info = $this->Vendor_common_model->get_address_user_name_password(array('status' => 1, 'id' => 13));

                    $reporting_manager_info = $this->Vendor_common_model->get_repoting_manager_email_id(array('status' => 1, 'id' => $user_info[0]['reporting_manager']));

                    require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
                    // Create new Spreadsheet object
                    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

                    // Set document properties
                    $spreadsheet->getProperties()->setCreator(CRMNAME)
                        ->setLastModifiedBy(CRMNAME)
                        ->setTitle(CRMNAME)
                        ->setSubject('Vendor records')
                        ->setDescription('Vendor records with their status');

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

                    $spreadsheet->getActiveSheet()->getStyle('A1:L1')->applyFromArray($styleArray);

                    // auto fit column to content
                    foreach (range('A', 'L') as $columnID) {
                        $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                            ->setWidth(20);
                    }

                    $spreadsheet->setActiveSheetIndex(0)

                        ->setCellValue("A1", 'Vendor Assigned on')
                        ->setCellValue("B1", 'Component Ref No')
                        ->setCellValue("C1", 'Transaction No')
                        ->setCellValue("D1", 'Candidate Name')
                        ->setCellValue("E1", 'Father Name')
                        ->setCellValue("F1", 'Date Of Birth')
                        ->setCellValue("H1", 'Address')
                        ->setCellValue("I1", 'City')
                        ->setCellValue("J1", 'Pincode')
                        ->setCellValue("K1", 'State')
                        ->setCellValue("L1", 'Vendor');

                    $x = 2;

                    foreach ($courtver as $all_record) {

                        $spreadsheet->setActiveSheetIndex(0);

                        $spreadsheet->getActiveSheet()->setCellValue("A$x", convert_db_to_display_date($all_record['vendor_assign_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12));
                        $spreadsheet->getActiveSheet()->setCellValue("B$x", $all_record['component_ref_no']);
                        $spreadsheet->getActiveSheet()->setCellValue("C$x", $all_record['trasaction_id']);
                        $spreadsheet->getActiveSheet()->setCellValue("D$x", ucwords($all_record['CandidateName']));

                        $spreadsheet->getActiveSheet()->setCellValue("E$x", ucwords($all_record['NameofCandidateFather']));
                        $spreadsheet->getActiveSheet()->setCellValue("F$x", convert_db_to_display_date($all_record['DateofBirth']));
                        $spreadsheet->getActiveSheet()->setCellValue("H$x", ucwords($all_record['street_address']));
                        $spreadsheet->getActiveSheet()->setCellValue("I$x", ucwords($all_record['city']));
                        $spreadsheet->getActiveSheet()->setCellValue("J$x", $all_record['pincode']);
                        $spreadsheet->getActiveSheet()->setCellValue("K$x", ucwords($all_record['state']));
                        $spreadsheet->getActiveSheet()->setCellValue("L$x", ucwords($all_record['vendor_name']));
                        $x++;

                    }

                    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                    header("Content-Disposition: attachment;filename=Vendor Records .xlsx");
                    header('Cache-Control: max-age=0');
                    // If you're serving to IE 9, then the following may be needed
                    header('Cache-Control: max-age=1');

                    // If you're serving to IE over SSL, then the following may be needed
                    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
                    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                    header('Pragma: public'); // HTTP/1.0

                    $file_upload_path = SITE_BASE_PATH . UPLOAD_FOLDER . 'vendor_cases';

                    if (!folder_exist($file_upload_path)) {
                        mkdir($file_upload_path, 0777);
                    }
                    if (!folder_exist($file_upload_path)) {
                        mkdir($file_upload_path, 0777);
                    } else if (!is_writable($file_upload_path)) {
                        mkdir($file_upload_path, 0777);
                    }

                    $file_name = "Vendor_" . $vendor_detail['vendor_name'] . '_' . 'Court' . ".xls";

                    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Excel2007');
                    ob_start();
                    $writer->save($file_upload_path . "/" . $file_name);
                    ob_end_clean();

                    $subject = ucwords($vendor_detail['vendor_name']) . '_WIP cases_Court_' . date('d-m-Y');

                    $message = "<p>Dear Sir/Madam,</p><p>Please find attached pending cases.</p>
                  <p><b>Court :</b></p>";

                    $message .= "<table border = '1'>
                  <tr>
                  <th style='text-align:center'>Allocated date </th>
                  <th style='text-align:center'>Cases</th>
                  <th style='text-align:center'>Days</th>
                  </tr>";
                    $total = 0;
                    foreach ($count_datewise_record as $count_datewise_records) {
                        $hold_day = getNetWorkDays($count_datewise_records['date'], date("d-m-Y"));
                        $message .= '<tr>
                  <td style="text-align:center">' . $count_datewise_records['date'] . '</td>
                  <td style="text-align:center">' . $count_datewise_records['count_record'] . '</td>
                  <td style="text-align:center">' . $hold_day . '</td>
                  </tr>';
                        $total += $count_datewise_records['count_record'];
                    }
                    $message .= '<tr><td style="text-align:center"><b>Total</b></td><td style="text-align:center" colspan = "2"><b>' . $total . '</b></tr>';
                    $message .= "</table>";

                    $message .= "<p><b>Note :</b> <I>This is an automatically generated email. Please do not reply to it. If you have any queries with reference to the contents of this email contact your assigned SPOC with " . CRMNAME . " </I>";

                    $email_tmpl_data['to_emails'] = $vendor_detail['email_id'];
                    $email_tmpl_data['attchment'] = $file_name;
                    $email_tmpl_data['user_email_id'] = $user_info[0]['email'];
                    $email_tmpl_data['reporting_email_id'] = $reporting_manager_info[0]['email'];
                    $email_tmpl_data['vendor_name'] = $vendor_detail['vendor_name'];
                    $email_tmpl_data['message'] = $message;
                    $email_tmpl_data['subject'] = $subject;
                    $result = $this->email->vendor_case_send_mail($email_tmpl_data);
                    $this->email->clear(true);
                }

            }

            if (in_array("globdbver", $vendors_components)) {

                $globdbver = $this->Vendor_common_model->get_vedor_datails_for_global($vendor_detail['id']);

                if (!empty($globdbver)) {

                    $count_datewise_record = $this->Vendor_common_model->get_global_date_wise_count($vendor_detail['id']);

                    $user_info = $this->Vendor_common_model->get_address_user_name_password(array('status' => 1, 'id' => 13));

                    $reporting_manager_info = $this->Vendor_common_model->get_repoting_manager_email_id(array('status' => 1, 'id' => $user_info[0]['reporting_manager']));

                    require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
                    // Create new Spreadsheet object
                    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

                    // Set document properties
                    $spreadsheet->getProperties()->setCreator(CRMNAME)
                        ->setLastModifiedBy(CRMNAME)
                        ->setTitle(CRMNAME)
                        ->setSubject('Vendor records')
                        ->setDescription('Vendor records with their status');

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

                    $spreadsheet->getActiveSheet()->getStyle('A1:L1')->applyFromArray($styleArray);

                    // auto fit column to content
                    foreach (range('A', 'L') as $columnID) {
                        $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                            ->setWidth(20);
                    }

                    $spreadsheet->setActiveSheetIndex(0)

                        ->setCellValue("A1", 'Vendor Assigned on')
                        ->setCellValue("B1", 'Component Ref No')
                        ->setCellValue("C1", 'Transaction No')
                        ->setCellValue("D1", 'Candidate Name')
                        ->setCellValue("E1", 'Father Name')
                        ->setCellValue("F1", 'Date Of Birth')
                        ->setCellValue("H1", 'Address')
                        ->setCellValue("I1", 'City')
                        ->setCellValue("J1", 'Pincode')
                        ->setCellValue("K1", 'State')
                        ->setCellValue("L1", 'Vendor');

                    $x = 2;

                    foreach ($globdbver as $all_record) {

                        $spreadsheet->setActiveSheetIndex(0);

                        $spreadsheet->getActiveSheet()->setCellValue("A$x", convert_db_to_display_date($all_record['vendor_assign_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12));
                        $spreadsheet->getActiveSheet()->setCellValue("B$x", $all_record['component_ref_no']);
                        $spreadsheet->getActiveSheet()->setCellValue("C$x", $all_record['trasaction_id']);
                        $spreadsheet->getActiveSheet()->setCellValue("D$x", ucwords($all_record['CandidateName']));

                        $spreadsheet->getActiveSheet()->setCellValue("E$x", ucwords($all_record['NameofCandidateFather']));
                        $spreadsheet->getActiveSheet()->setCellValue("F$x", convert_db_to_display_date($all_record['DateofBirth']));
                        $spreadsheet->getActiveSheet()->setCellValue("H$x", ucwords($all_record['street_address']));
                        $spreadsheet->getActiveSheet()->setCellValue("I$x", ucwords($all_record['city']));
                        $spreadsheet->getActiveSheet()->setCellValue("J$x", $all_record['pincode']);
                        $spreadsheet->getActiveSheet()->setCellValue("K$x", ucwords($all_record['state']));
                        $spreadsheet->getActiveSheet()->setCellValue("L$x", ucwords($all_record['vendor_name']));
                        $x++;

                    }

                    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                    header("Content-Disposition: attachment;filename=Vendor Records .xlsx");
                    header('Cache-Control: max-age=0');
                    // If you're serving to IE 9, then the following may be needed
                    header('Cache-Control: max-age=1');

                    // If you're serving to IE over SSL, then the following may be needed
                    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
                    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                    header('Pragma: public'); // HTTP/1.0

                    $file_upload_path = SITE_BASE_PATH . UPLOAD_FOLDER . 'vendor_cases';

                    if (!folder_exist($file_upload_path)) {
                        mkdir($file_upload_path, 0777);
                    }
                    if (!folder_exist($file_upload_path)) {
                        mkdir($file_upload_path, 0777);
                    } else if (!is_writable($file_upload_path)) {
                        mkdir($file_upload_path, 0777);
                    }

                    $file_name = "Vendor_" . $vendor_detail['vendor_name'] . '_' . 'Global' . ".xls";

                    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Excel2007');
                    ob_start();
                    $writer->save($file_upload_path . "/" . $file_name);
                    ob_end_clean();

                    $subject = ucwords($vendor_detail['vendor_name']) . '_WIP cases_Global_' . date('d-m-Y');

                    $message = "<p>Dear Sir/Madam,</p><p>Please find attached pending cases.</p>
                  <p><b>Global Database :</b></p>";

                    $message .= "<table border = '1'>
                  <tr>
                  <th style='text-align:center'>Allocated date </th>
                  <th style='text-align:center'>Cases</th>
                  <th style='text-align:center'>Days</th>
                  </tr>";
                    $total = 0;
                    foreach ($count_datewise_record as $count_datewise_records) {
                        $hold_day = getNetWorkDays($count_datewise_records['date'], date("d-m-Y"));
                        $message .= '<tr>
                  <td style="text-align:center">' . $count_datewise_records['date'] . '</td>
                  <td style="text-align:center">' . $count_datewise_records['count_record'] . '</td>
                  <td style="text-align:center">' . $hold_day . '</td>
                  </tr>';
                        $total += $count_datewise_records['count_record'];
                    }
                    $message .= '<tr><td style="text-align:center"><b>Total</b></td><td style="text-align:center" colspan = "2"><b>' . $total . '</b></tr>';
                    $message .= "</table>";

                    $message .= "<p><b>Note :</b> <I>This is an automatically generated email. Please do not reply to it. If you have any queries with reference to the contents of this email contact your assigned SPOC with " . CRMNAME . "</I>";

                    $email_tmpl_data['to_emails'] = $vendor_detail['email_id'];
                    $email_tmpl_data['attchment'] = $file_name;
                    $email_tmpl_data['user_email_id'] = $user_info[0]['email'];
                    $email_tmpl_data['reporting_email_id'] = $reporting_manager_info[0]['email'];
                    $email_tmpl_data['vendor_name'] = $vendor_detail['vendor_name'];
                    $email_tmpl_data['message'] = $message;
                    $email_tmpl_data['subject'] = $subject;
                    $result = $this->email->vendor_case_send_mail($email_tmpl_data);
                    $this->email->clear(true);
                }

            }
            if (in_array("crimver", $vendors_components)) {

                $crimver = $this->Vendor_common_model->get_vedor_datails_for_pcc($vendor_detail['id']);
                if (!empty($crimver)) {

                    $count_datewise_record = $this->Vendor_common_model->get_pcc_date_wise_count($vendor_detail['id']);

                    $user_info = $this->Vendor_common_model->get_address_user_name_password(array('status' => 1, 'id' => 13));

                    $reporting_manager_info = $this->Vendor_common_model->get_repoting_manager_email_id(array('status' => 1, 'id' => $user_info[0]['reporting_manager']));

                    require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
                    // Create new Spreadsheet object
                    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

                    // Set document properties
                    $spreadsheet->getProperties()->setCreator(CRMNAME)
                        ->setLastModifiedBy(CRMNAME)
                        ->setTitle(CRMNAME)
                        ->setSubject('Vendor records')
                        ->setDescription('Vendor records with their status');

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

                    $spreadsheet->getActiveSheet()->getStyle('A1:L1')->applyFromArray($styleArray);

                    // auto fit column to content
                    foreach (range('A', 'L') as $columnID) {
                        $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                            ->setWidth(20);
                    }

                    $spreadsheet->setActiveSheetIndex(0)

                        ->setCellValue("A1", 'Vendor Assigned on')
                        ->setCellValue("B1", 'Component Ref No')
                        ->setCellValue("C1", 'Transaction No')
                        ->setCellValue("D1", 'Candidate Name')
                        ->setCellValue("E1", 'Father Name')
                        ->setCellValue("F1", 'Date Of Birth')
                        ->setCellValue("H1", 'Address')
                        ->setCellValue("I1", 'City')
                        ->setCellValue("J1", 'Pincode')
                        ->setCellValue("K1", 'State')
                        ->setCellValue("L1", 'Vendor');

                    $x = 2;

                    foreach ($crimver as $all_record) {

                        $spreadsheet->setActiveSheetIndex(0);

                        $spreadsheet->getActiveSheet()->setCellValue("A$x", convert_db_to_display_date($all_record['vendor_assign_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12));
                        $spreadsheet->getActiveSheet()->setCellValue("B$x", $all_record['component_ref_no']);
                        $spreadsheet->getActiveSheet()->setCellValue("C$x", $all_record['trasaction_id']);
                        $spreadsheet->getActiveSheet()->setCellValue("D$x", ucwords($all_record['CandidateName']));

                        $spreadsheet->getActiveSheet()->setCellValue("E$x", ucwords($all_record['NameofCandidateFather']));
                        $spreadsheet->getActiveSheet()->setCellValue("F$x", convert_db_to_display_date($all_record['DateofBirth']));
                        $spreadsheet->getActiveSheet()->setCellValue("H$x", ucwords($all_record['street_address']));
                        $spreadsheet->getActiveSheet()->setCellValue("I$x", ucwords($all_record['city']));
                        $spreadsheet->getActiveSheet()->setCellValue("J$x", $all_record['pincode']);
                        $spreadsheet->getActiveSheet()->setCellValue("K$x", ucwords($all_record['state']));
                        $spreadsheet->getActiveSheet()->setCellValue("L$x", ucwords($all_record['vendor_name']));
                        $x++;

                    }

                    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                    header("Content-Disposition: attachment;filename=Vendor Records .xlsx");
                    header('Cache-Control: max-age=0');
                    // If you're serving to IE 9, then the following may be needed
                    header('Cache-Control: max-age=1');

                    // If you're serving to IE over SSL, then the following may be needed
                    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
                    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                    header('Pragma: public'); // HTTP/1.0

                    $file_upload_path = SITE_BASE_PATH . UPLOAD_FOLDER . 'vendor_cases';

                    if (!folder_exist($file_upload_path)) {
                        mkdir($file_upload_path, 0777);
                    }
                    if (!folder_exist($file_upload_path)) {
                        mkdir($file_upload_path, 0777);
                    } else if (!is_writable($file_upload_path)) {
                        mkdir($file_upload_path, 0777);
                    }

                    $file_name = "Vendor_" . $vendor_detail['vendor_name'] . '_' . 'PCC' . ".xls";

                    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Excel2007');
                    ob_start();
                    $writer->save($file_upload_path . "/" . $file_name);
                    ob_end_clean();

                    $subject = ucwords($vendor_detail['vendor_name']) . '_WIP cases_PCC_' . date('d-m-Y');

                    $message = "<p>Dear Sir/Madam,</p><p>Please find attached pending cases.</p>
                  <p><b>PCC :</b></p>";

                    $message .= "<table border = '1'>
                  <tr>
                  <th style='text-align:center'>Allocated date </th>
                  <th style='text-align:center'>Cases</th>
                  <th style='text-align:center'>Days</th>
                  </tr>";
                    $total = 0;
                    foreach ($count_datewise_record as $count_datewise_records) {
                        $hold_day = getNetWorkDays($count_datewise_records['date'], date("d-m-Y"));
                        $message .= '<tr>
                  <td style="text-align:center">' . $count_datewise_records['date'] . '</td>
                  <td style="text-align:center">' . $count_datewise_records['count_record'] . '</td>
                  <td style="text-align:center">' . $hold_day . '</td>
                  </tr>';
                        $total += $count_datewise_records['count_record'];
                    }
                    $message .= '<tr><td style="text-align:center"><b>Total Cases</b></td><td style="text-align:center" colspan = "2"><b>' . $total . '</b></tr>';
                    $message .= "</table>";

                    $message .= "<p><b>Note :</b> <I>This is an automatically generated email. Please do not reply to it. If you have any queries with reference to the contents of this email contact your assigned SPOC with " . CRMNAME . "</I>";

                    $email_tmpl_data['to_emails'] = $vendor_detail['email_id'];
                    $email_tmpl_data['attchment'] = $file_name;
                    $email_tmpl_data['user_email_id'] = $user_info[0]['email'];
                    $email_tmpl_data['reporting_email_id'] = $reporting_manager_info[0]['email'];
                    $email_tmpl_data['vendor_name'] = $vendor_detail['vendor_name'];
                    $email_tmpl_data['message'] = $message;
                    $email_tmpl_data['subject'] = $subject;
                    $result = $this->email->vendor_case_send_mail($email_tmpl_data);
                    $this->email->clear(true);
                }

            }

            if (in_array("cbrver", $vendors_components)) {

                $cbrver = $this->Vendor_common_model->get_vedor_datails_for_credit_report($vendor_detail['id']);
                if (!empty($cbrver)) {

                    $count_datewise_record = $this->Vendor_common_model->get_credit_date_wise_count($vendor_detail['id']);

                    $user_info = $this->Vendor_common_model->get_address_user_name_password(array('status' => 1, 'id' => 13));

                    $reporting_manager_info = $this->Vendor_common_model->get_repoting_manager_email_id(array('status' => 1, 'id' => $user_info[0]['reporting_manager']));

                    require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
                    // Create new Spreadsheet object
                    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

                    // Set document properties
                    $spreadsheet->getProperties()->setCreator(CRMNAME)
                        ->setLastModifiedBy(CRMNAME)
                        ->setTitle(CRMNAME)
                        ->setSubject('Vendor records')
                        ->setDescription('Vendor records with their status');

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

                    $spreadsheet->getActiveSheet()->getStyle('A1:H1')->applyFromArray($styleArray);

                    // auto fit column to content
                    foreach (range('A', 'H') as $columnID) {
                        $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                            ->setWidth(20);
                    }

                    $spreadsheet->setActiveSheetIndex(0)

                        ->setCellValue("A1", 'Vendor Assigned on')
                        ->setCellValue("B1", 'Component Ref No')
                        ->setCellValue("C1", 'Transaction No')
                        ->setCellValue("D1", 'Candidate Name')
                        ->setCellValue("E1", 'Father Name')
                        ->setCellValue("F1", 'Date Of Birth')
                        ->setCellValue("G1", 'Document Submitted')
                        ->setCellValue("H1", 'Vendor');

                    $x = 2;

                    foreach ($cbrver as $all_record) {

                        $spreadsheet->setActiveSheetIndex(0);

                        $spreadsheet->getActiveSheet()->setCellValue("A$x", convert_db_to_display_date($all_record['vendor_assign_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12));
                        $spreadsheet->getActiveSheet()->setCellValue("B$x", $all_record['component_ref_no']);
                        $spreadsheet->getActiveSheet()->setCellValue("C$x", $all_record['trasaction_id']);
                        $spreadsheet->getActiveSheet()->setCellValue("D$x", ucwords($all_record['CandidateName']));

                        $spreadsheet->getActiveSheet()->setCellValue("E$x", ucwords($all_record['NameofCandidateFather']));
                        $spreadsheet->getActiveSheet()->setCellValue("F$x", convert_db_to_display_date($all_record['DateofBirth']));
                        $spreadsheet->getActiveSheet()->setCellValue("G$x", ucwords($all_record['doc_submited']));
                        $spreadsheet->getActiveSheet()->setCellValue("H$x", ucwords($all_record['vendor_name']));
                        $x++;

                    }

                    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                    header("Content-Disposition: attachment;filename=Vendor Records .xlsx");
                    header('Cache-Control: max-age=0');
                    // If you're serving to IE 9, then the following may be needed
                    header('Cache-Control: max-age=1');

                    // If you're serving to IE over SSL, then the following may be needed
                    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
                    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                    header('Pragma: public'); // HTTP/1.0

                    $file_upload_path = SITE_BASE_PATH . UPLOAD_FOLDER . 'vendor_cases';

                    if (!folder_exist($file_upload_path)) {
                        mkdir($file_upload_path, 0777);
                    }
                    if (!folder_exist($file_upload_path)) {
                        mkdir($file_upload_path, 0777);
                    } else if (!is_writable($file_upload_path)) {
                        mkdir($file_upload_path, 0777);
                    }

                    $file_name = "Vendor_" . $vendor_detail['vendor_name'] . '_' . 'CREDIT_REPORT' . ".xls";

                    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Excel2007');
                    ob_start();
                    $writer->save($file_upload_path . "/" . $file_name);
                    ob_end_clean();

                    $subject = ucwords($vendor_detail['vendor_name']) . '_WIP cases_CREDIT_REPORT_' . date('d-m-Y');

                    $message = "<p>Dear Sir/Madam,</p><p>Please find attached pending cases.</p>
                  <p><b>Credit Report :</b></p>";

                    $message .= "<table border = '1'>
                  <tr>
                  <th style='text-align:center'>Allocated date </th>
                  <th style='text-align:center'>Cases</th>
                  <th style='text-align:center'>Days</th>
                  </tr>";
                    $total = 0;

                    foreach ($count_datewise_record as $count_datewise_records) {
                        $hold_day = getNetWorkDays($count_datewise_records['date'], date("d-m-Y"));
                        $message .= '<tr>
                  <td style="text-align:center">' . $count_datewise_records['date'] . '</td>
                  <td style="text-align:center">' . $count_datewise_records['count_record'] . '</td>
                  <td style="text-align:center">' . $hold_day . '</td>
                  </tr>';
                        $total += $count_datewise_records['count_record'];
                    }
                    $message .= '<tr><td style="text-align:center"><b>Total Cases</b></td><td style="text-align:center" colspan = "2"><b>' . $total . '</b></tr>';
                    $message .= "</table>";

                    $message .= "<p><b>Note :</b> <I>This is an automatically generated email. Please do not reply to it. If you have any queries with reference to the contents of this email contact your assigned SPOC with " . CRMNAME . "</I>";

                    $email_tmpl_data['to_emails'] = $vendor_detail['email_id'];
                    $email_tmpl_data['attchment'] = $file_name;
                    $email_tmpl_data['user_email_id'] = $user_info[0]['email'];
                    $email_tmpl_data['reporting_email_id'] = $reporting_manager_info[0]['email'];
                    $email_tmpl_data['vendor_name'] = $vendor_detail['vendor_name'];
                    $email_tmpl_data['message'] = $message;
                    $email_tmpl_data['subject'] = $subject;

                    $result = $this->email->vendor_case_send_mail($email_tmpl_data);
                    $this->email->clear(true);
                }
            }
        }
    }

    public function report($candsid = null, $report_type)
    {

        //  if($this->is_admin_logged_in())
        //  {

        if (!empty($candsid)) {

            $id = decrypt($candsid);
            $this->load->model('candidates_model');
            $this->load->model('first_qc_model');

            $this->load->library('example');

            $report = array();

            $cands_result = $this->candidates_model->get_candidates_info_info_report(array('candidates_info.id' => $id));

            $report['address_info'] = array();
            $report['employment_info'] = array();
            $report['education_info'] = array();
            $report['references_info'] = array();
            $report['court_info'] = array();
            $report['global_db_info'] = array();
            $report['pcc_info'] = array();
            $report['identity_info'] = array();
            $report['credit_report_info'] = array();
            $report['drugs_info'] = array();

            $report['report_type'] = $report_type;
            $report['social_media_info'] = array();


            if ($cands_result) {
                $report['cand_info'] = $cands_result;

                $NA_array = array();

                $result = $this->first_qc_model->get_address_final_qc(array('addrverres.candsid' => $id));
                if (!empty($result)) {
                    $report['address_info'] = $result;
                } else {
                    $report['address_info'] = $result;
                    $NA_array[] = array('ADDRESS');
                }
                $result = $this->first_qc_model->get_emp_final_qc(array('empverres.candsid' => $id));
                if (!empty($result)) {
                    $report['employment_info'] = $result;
                } else {
                    $report['employment_info'] = $result;
                    $NA_array[] = array('EMPLOYMENT');
                }
                $result = $this->first_qc_model->get_education_final_qc(array('education_result.candsid' => $id));

                if (!empty($result)) {
                    $report['education_info'] = $result;
                } else {
                    $report['education_info'] = $result;
                    $NA_array[] = array('EDUCATION');
                }
                $result = $this->first_qc_model->get_reference_final_qc(array('reference_result.candsid' => $id));
                if (!empty($result)) {
                    $report['references_info'] = $result;
                } else {
                    $report['references_info'] = $result;
                    $NA_array[] = array('REFERENCES VERIFICATION');
                }

                $result = $this->first_qc_model->get_court_final_qc(array('courtver_result.candsid' => $id));

                if (!empty($result)) {
                    $report['court_info'] = $result;
                } else {
                    $report['court_info'] = $result;
                    $NA_array[] = array('COURT VERIFICATION');
                }
                $result = $this->first_qc_model->get_global_db_final_qc(array('glodbver_result.candsid' => $id));
                if (!empty($result)) {
                    $report['global_db_info'] = $result;
                } else {
                    $report['global_db_info'] = $result;
                    $NA_array[] = array('GLOBAL DATABASE VERIFICATION');
                }

                $result = $this->first_qc_model->get_drug_db_final_qc(array('drug_narcotis_result.candsid' => $id));

                if (!empty($result)) {
                    $report['drugs_info'] = $result;
                } else {
                    $report['drugs_info'] = $result;
                    $NA_array[] = array('DRUGS VERIFICATION');
                }
                $result = $this->first_qc_model->get_pcc_final_qc(array('pcc_result.candsid' => $id));

                if (!empty($result)) {
                    $report['pcc_info'] = $result;
                } else {
                    $report['pcc_info'] = $result;
                    $NA_array[] = array('POLICE VERIFICATION');
                }

                $result = $this->first_qc_model->get_identity_final_qc(array('identity_result.candsid' => $id));

                if (!empty($result)) {
                    $report['identity_info'] = $result;
                } else {
                    $report['identity_info'] = $result;
                    $NA_array[] = array('IDENTITY VERIFICATION');
                }

                $result = $this->first_qc_model->get_credit_report_final_qc(array('credit_report_result.candsid' => $id));

                if (!empty($result)) {
                    $report['credit_report_info'] = $result;
                } else {
                    $report['credit_report_info'] = $result;
                    $NA_array[] = array('CREDIT REPORT VERIFICATION');
                }

                $result = $this->first_qc_model->get_social_media_final_qc(array('social_media_result.candsid' => $id));

                if (!empty($result)) {
                    $report['social_media_info'] = $result;
                } else {
                    $report['social_media_info'] = $result;
                    $NA_array[] = array('SOCIAL MEDIA VERIFICATION');
                }


                $report['NA_COMPONENTS'] = $NA_array;

                $report['status'] = OVERALL_STATUS;

                if ($cands_result['comp_logo'] != "") {
                    $cleit_logo_path = SITE_URL . CLIENT_LOGO . '/' . $cands_result['comp_logo'];
                    define('CLIENT_LOGOS', $cleit_logo_path);
                } else {
                    define('CLIENT_LOGOS', '');
                }

                define('CUSTOM_CLINT_ID',$cands_result['clientid']);


                $this->example->generate_pdf($report, 'admin');
            } else {
                show_404();
            }
        } else {
            redirect('admin/candidates');
        }

    }

}
