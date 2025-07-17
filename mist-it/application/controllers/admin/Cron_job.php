<?php defined('BASEPATH') or exit('No direct script access allowed');

class Cron_job extends MY_Controller
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
        $this->perm_array = array('page_id' => 22);

        $this->load->model('Cron_job_model');

    }

    public function index()
    {
        $data['header_title'] = "Cron Job";

        $data['lists'] = $this->Cron_job_model->Cron_job_details();

        $data['vendor_list'] = $this->Cron_job_model->vendor_details();

        $data['components'] = $this->components();

        $data['components_aq_selection'] = $this->Cron_job_model->selected_aq_component();

        $data['final_qc_aq_selection'] = $this->Cron_job_model->selected_final_qc_aq_component();

        $this->load->view('admin/header', $data);

        $this->load->view('admin/cron_jobs_list');

        $this->load->view('admin/footer');
    }

    public function Set_cron_job()
    {

        if ($this->input->is_ajax_request()) {

            $this->CI = &get_instance();

            $today = date("Y-m-d");

            $today_date = strtotime($today);

            $get_holiday1 = $this->get_holiday();

            $get_holiday = array_map('current', $get_holiday1);

            $day_name = date("D", $today_date);

            $query_address = $this->CI->db->query("SELECT addrver.*,`addrverres`.`verfstatus`,`addrverres`.`var_filter_status`,`addrverres`.`var_report_status`  FROM addrver join `addrverres`  ON addrverres.`addrverid` = addrver.`id` WHERE (`addrverres`.`var_filter_status` = 'WIP' OR `addrverres`.`var_filter_status` = 'wip'  OR `addrverres`.`var_filter_status` = 'Insufficiency' OR `addrverres`.`var_filter_status` = 'insufficiency')");

            record_db_error($this->CI->db->last_query());

            $address_result = $query_address->result_array();

        } else {
            permission_denied();
        }

    }

    public function tat_calculation()
    {

        $json_array = array();
        $json_array['status'] = ERROR_CODE;
        $json_array['message'] = 'Something went wrong,please try again';

        if ($this->input->is_ajax_request()) {
            $group_id = $this->db->query("select group_concat(id) as wip_filter from status where filter_status = 'WIP'");
            $res_filter = $group_id->result_array()[0];

            //Employment
            $query = $this->db->query("SELECT empver.id, iniated_date,clients_details.tat_empver,(SELECT sum(hold_days) FROM empverres_insuff empinsf WHERE empinsf.empverres_id = empver.id) AS totat_days
            FROM empver
            INNER JOIN empverres ON ( empverres.empverid = empver.id and empverres.verfstatus in (" . $res_filter['wip_filter'] . ") )
            INNER JOIN candidates_info ON candidates_info.id = empver.candsid
            INNER JOIN clients_details ON (clients_details.tbl_clients_id =  candidates_info.clientid and clients_details.entity = candidates_info.entity and clients_details.package = candidates_info.package) ");

            $result_array = $query->result_array();

            $updateArray = array();

            foreach ($result_array as $key => $value) {

                $iniation_date = ($value['tat_empver'] + $value['totat_days']);

                $new_date = getWorkingDays($value['iniated_date'], array(), $iniation_date);
      
                if ($new_date) {
                    $final_date = $this->get_holidays_list($value['iniated_date'], $new_date);
                    $tat_status = $this->case_tat_status($final_date);
                    $updateArray[] = array('due_date' => $final_date, 'tat_status' => $tat_status, 'id' => $value['id']);

                }
            }
         

            if (!empty($updateArray)) {
                $this->db->update_batch('empver', $updateArray, 'id');
            }

            //Address
            $query = $this->db->query("SELECT addrver.id, iniated_date,clients_details.tat_addrver,(SELECT sum(hold_days) FROM addrver_insuff empinsf WHERE empinsf.addrverid = addrver.id) AS totat_days
            FROM addrver
            INNER JOIN addrverres ON ( addrverres.addrverid = addrver.id and addrverres.verfstatus in (" . $res_filter['wip_filter'] . ") )
            INNER JOIN candidates_info ON candidates_info.id = addrver.candsid
            INNER JOIN clients_details ON (clients_details.tbl_clients_id =  candidates_info.clientid and clients_details.entity = candidates_info.entity and clients_details.package = candidates_info.package)");

            $result_array = $query->result_array();

            $updateArray = array();
            foreach ($result_array as $key => $value) {

                $iniation_date = ($value['tat_addrver'] + $value['totat_days']);

                $new_date = getWorkingDays($value['iniated_date'], array(), $iniation_date);

                if ($new_date) {
                    $final_date = $this->get_holidays_list($value['iniated_date'], $new_date);
                    $tat_status = $this->case_tat_status($final_date);
                    $updateArray[] = array('due_date' => $final_date, 'tat_status' => $tat_status, 'id' => $value['id']);

                }
            }

            if (!empty($updateArray)) {
                $this->db->update_batch('addrver', $updateArray, 'id');
            }

            // Education
            $query = $this->db->query("SELECT education.id, iniated_date,clients_details.tat_eduver,(SELECT sum(hold_days) FROM education_insuff empinsf WHERE empinsf.education_id = education.id) AS totat_days
            FROM education
            INNER JOIN education_result ON ( education_result.education_id = education.id and education_result.verfstatus in (" . $res_filter['wip_filter'] . ") )
            INNER JOIN candidates_info ON candidates_info.id = education.candsid
            INNER JOIN clients_details ON (clients_details.tbl_clients_id =  candidates_info.clientid and clients_details.entity = candidates_info.entity and clients_details.package = candidates_info.package)");

            $result_array = $query->result_array();

            $updateArray = array();
            foreach ($result_array as $key => $value) {

                $iniation_date = ($value['tat_eduver'] + $value['totat_days']);

                $new_date = getWorkingDays($value['iniated_date'], array(), $iniation_date);

                if ($new_date) {
                    $final_date = $this->get_holidays_list($value['iniated_date'], $new_date);
                    $tat_status = $this->case_tat_status($final_date);
                    $updateArray[] = array('due_date' => $final_date, 'tat_status' => $tat_status, 'id' => $value['id']);

                }
            }

            if (!empty($updateArray)) {
                $this->db->update_batch('education', $updateArray, 'id');
            }

            //Reference
            $query = $this->db->query("SELECT reference.id, iniated_date,clients_details.tat_refver,(SELECT sum(hold_days) FROM reference_insuff empinsf WHERE empinsf.reference_id = reference.id) AS totat_days
            FROM reference
            INNER JOIN reference_result ON ( reference_result.reference_id = reference.id and reference_result.verfstatus in (" . $res_filter['wip_filter'] . ") )
            INNER JOIN candidates_info ON candidates_info.id = reference.candsid
            INNER JOIN clients_details ON (clients_details.tbl_clients_id =  candidates_info.clientid and clients_details.entity = candidates_info.entity and clients_details.package = candidates_info.package)");

            $result_array = $query->result_array();

            $updateArray = array();
            foreach ($result_array as $key => $value) {

                $iniation_date = ($value['tat_refver'] + $value['totat_days']);
 
                $new_date = getWorkingDays($value['iniated_date'], array(), $iniation_date);

                if ($new_date) {
                    $final_date = $this->get_holidays_list($value['iniated_date'], $new_date);
                    $tat_status = $this->case_tat_status($final_date);
                    $updateArray[] = array('due_date' => $final_date, 'tat_status' => $tat_status, 'id' => $value['id']);

                }
            }

            if (!empty($updateArray)) {
                $this->db->update_batch('reference', $updateArray, 'id');
            }

            //Court
            $query = $this->db->query("SELECT courtver.id, iniated_date,clients_details.tat_courtver,(SELECT sum(hold_days) FROM courtver_insuff empinsf WHERE empinsf.courtver_id = courtver.id) AS totat_days
            FROM courtver
            INNER JOIN courtver_result ON ( courtver_result.courtver_id = courtver.id and courtver_result.verfstatus in (" . $res_filter['wip_filter'] . ") )
            INNER JOIN candidates_info ON candidates_info.id = courtver.candsid
            INNER JOIN clients_details ON (clients_details.tbl_clients_id =  candidates_info.clientid and clients_details.entity = candidates_info.entity and clients_details.package = candidates_info.package)");

            $result_array = $query->result_array();

            $updateArray = array();
            foreach ($result_array as $key => $value) {

                $iniation_date = ($value['tat_courtver'] + $value['totat_days']);

                $new_date = getWorkingDays($value['iniated_date'], array(), $iniation_date);

                if ($new_date) {
                    $final_date = $this->get_holidays_list($value['iniated_date'], $new_date);
                    $tat_status = $this->case_tat_status($final_date);
                    $updateArray[] = array('due_date' => $final_date, 'tat_status' => $tat_status, 'id' => $value['id']);

                }
            }

            if (!empty($updateArray)) {
                $this->db->update_batch('courtver', $updateArray, 'id');
            }
            
            // GLobal DB

            $query = $this->db->query("SELECT glodbver.id, iniated_date,clients_details.tat_globdbver,(SELECT sum(hold_days) FROM glodbver_insuff empinsf WHERE empinsf.glodbver_id = glodbver.id) AS totat_days
            FROM glodbver
            INNER JOIN glodbver_result ON ( glodbver_result.glodbver_id = glodbver.id and glodbver_result.verfstatus in (" . $res_filter['wip_filter'] . ") )
            INNER JOIN candidates_info ON candidates_info.id = glodbver.candsid
            INNER JOIN clients_details ON (clients_details.tbl_clients_id =  candidates_info.clientid and clients_details.entity = candidates_info.entity and clients_details.package = candidates_info.package)");

            $result_array = $query->result_array();

            $updateArray = array();
            foreach ($result_array as $key => $value) {

                $iniation_date = ($value['tat_globdbver'] + $value['totat_days']);

                $new_date = getWorkingDays($value['iniated_date'], array(), $iniation_date);

                if ($new_date) {
                    $final_date = $this->get_holidays_list($value['iniated_date'], $new_date);
                    $tat_status = $this->case_tat_status($final_date);
                    $updateArray[] = array('due_date' => $final_date, 'tat_status' => $tat_status, 'id' => $value['id']);

                }
            }

            if (!empty($updateArray)) {
                $this->db->update_batch('glodbver', $updateArray, 'id');
            }

            // PCC
            $query = $this->db->query("SELECT pcc.id, iniated_date,clients_details.tat_crimver,(SELECT sum(hold_days) FROM pcc_insuff empinsf WHERE empinsf.pcc_id = pcc.id) AS totat_days
            FROM pcc
            INNER JOIN pcc_result ON ( pcc_result.pcc_id = pcc.id and pcc_result.verfstatus in (" . $res_filter['wip_filter'] . ") )
            INNER JOIN candidates_info ON candidates_info.id = pcc.candsid
            INNER JOIN clients_details ON (clients_details.tbl_clients_id =  candidates_info.clientid and clients_details.entity = candidates_info.entity and clients_details.package = candidates_info.package)");

            $result_array = $query->result_array();

            $updateArray = array();
            foreach ($result_array as $key => $value) {

                $iniation_date = ($value['tat_crimver'] + $value['totat_days']);

                $new_date = getWorkingDays($value['iniated_date'], array(), $iniation_date);

                if ($new_date) {
                    $final_date = $this->get_holidays_list($value['iniated_date'], $new_date);
                    $tat_status = $this->case_tat_status($final_date);
                    $updateArray[] = array('due_date' => $final_date, 'tat_status' => $tat_status, 'id' => $value['id']);

                }
            }

            if (!empty($updateArray)) {
                $this->db->update_batch('pcc', $updateArray, 'id');
            }

            // Identity
            $query = $this->db->query("SELECT identity.id, iniated_date,clients_details.tat_identity,(SELECT sum(hold_days) FROM identity_insuff empinsf WHERE empinsf.identity_id = identity.id) AS totat_days
            FROM identity
            INNER JOIN identity_result ON ( identity_result.identity_id = identity.id and identity_result.verfstatus in (" . $res_filter['wip_filter'] . ") )
            INNER JOIN candidates_info ON candidates_info.id = identity.candsid
            INNER JOIN clients_details ON (clients_details.tbl_clients_id =  candidates_info.clientid and clients_details.entity = candidates_info.entity and clients_details.package = candidates_info.package)");

            $result_array = $query->result_array();

            $updateArray = array();
            foreach ($result_array as $key => $value) {

                $iniation_date = ($value['tat_identity'] + $value['totat_days']);

                $new_date = getWorkingDays($value['iniated_date'], array(), $iniation_date);

                if ($new_date) {
                    $final_date = $this->get_holidays_list($value['iniated_date'], $new_date);
                    $tat_status = $this->case_tat_status($final_date);
                    $updateArray[] = array('due_date' => $final_date, 'tat_status' => $tat_status, 'id' => $value['id']);

                }
            }

            if (!empty($updateArray)) {
                $this->db->update_batch('identity', $updateArray, 'id');
            }

            // Credit Report
            $query = $this->db->query("SELECT credit_report.id, iniated_date,clients_details.tat_cbrver,(SELECT sum(hold_days) FROM credit_report_insuff empinsf WHERE empinsf.credit_report_id = credit_report.id) AS totat_days
            FROM credit_report
            INNER JOIN credit_report_result ON ( credit_report_result.credit_report_id = credit_report.id and credit_report_result.verfstatus in (" . $res_filter['wip_filter'] . ") )
            INNER JOIN candidates_info ON candidates_info.id = credit_report.candsid
            INNER JOIN clients_details ON (clients_details.tbl_clients_id =  candidates_info.clientid and clients_details.entity = candidates_info.entity and clients_details.package = candidates_info.package)");

            $result_array = $query->result_array();

            $updateArray = array();
            foreach ($result_array as $key => $value) {

                $iniation_date = ($value['tat_cbrver'] + $value['totat_days']);

                $new_date = getWorkingDays($value['iniated_date'], array(), $iniation_date);

                if ($new_date) {
                    $final_date = $this->get_holidays_list($value['iniated_date'], $new_date);
                    $tat_status = $this->case_tat_status($final_date);
                    $updateArray[] = array('due_date' => $final_date, 'tat_status' => $tat_status, 'id' => $value['id']);

                }
            }

            if (!empty($updateArray)) {
                $this->db->update_batch('credit_report', $updateArray, 'id');
            }

            //Drug Verificatin
            /* $query = $this->db->query("SELECT drug_narcotis.id, iniated_date,clients_details.tat_narcver,(SELECT sum(hold_days) FROM drug_narcotis_insuff empinsf WHERE empinsf.drug_narcotis_id = drug_narcotis.id) AS totat_days
            FROM drug_narcotis INNER JOIN drug_narcotis_result ON ( drug_narcotis_result.drug_narcotis_id = drug_narcotis.id and drug_narcotis_result.verfstatus in (".$res_filter['wip_filter'].") )
            INNER JOIN candidates_info ON candidates_info.id = drug_narcotis.candsid
            INNER JOIN clients_details ON (clients_details.tbl_clients_id =  candidates_info.clientid and clients_details.entity = candidates_info.entity and clients_details.package = candidates_info.package)");

            $result_array = $query->result_array();

            $updateArray = array();
            foreach ($result_array as $key => $value) {

            $iniation_date = ($value['tat_narcver']+$value['totat_days']);

            $new_date = getWorkingDays($value['iniated_date'], array(), $iniation_date);

            if($new_date) {
            $final_date = $this->get_holidays_list($value['iniated_date'],$new_date);
            $tat_status = $this->case_tat_status($final_date);
            $updateArray[] = array('due_date' => $final_date,'tat_status' => $tat_status,'id' => $value['id']);

            }
            }

            if(!empty($updateArray))
            {
            $this->db->update_batch('drug_narcotis',$updateArray, 'id');
            }*/

            $query = $this->db->query("SELECT id from candidates_info where overallstatus = 1");

            $result_array = $query->result_array();

            $updateArray = array();

            foreach ($result_array as $key => $value) {

                $query_overall = $this->db->query("(SELECT due_date  from addrver where candsid =" . $value['id'] . ") UNION ALL  (SELECT due_date  from empver where candsid =" . $value['id'] . ")  UNION ALL (SELECT due_date  from education where candsid =" . $value['id'] . ") UNION ALL (SELECT due_date  from reference where candsid =" . $value['id'] . ") UNION ALL (SELECT due_date  from courtver where candsid =" . $value['id'] . ")  UNION ALL (SELECT due_date  from glodbver where candsid =" . $value['id'] . ")  UNION ALL (SELECT due_date  from pcc where candsid =" . $value['id'] . ")  UNION ALL (SELECT due_date  from identity where candsid =" . $value['id'] . ")  UNION ALL (SELECT due_date  from credit_report where candsid =" . $value['id'] . ") ORDER BY due_date desc limit 1");

                $result_array = $query_overall->result_array();

                if (isset($result_array[0]['due_date'])) {

                    $tat_status = $this->case_tat_status($result_array[0]['due_date']);

                    $updateArray[] = array('due_date_candidate' => $result_array[0]['due_date'], 'tat_status_candidate' => $tat_status, 'id' => $value['id']);
                }
            }

            if (!empty($updateArray)) {
                $this->db->update_batch('candidates_info', $updateArray, 'id');
            }

            $update_cronjob_detail = $this->Cron_job_model->save(array('created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id'], 'executed_on' => date(DB_DATE_FORMAT), 'status' => 1), array('id' => 2));

            if ($update_cronjob_detail) {
                $json_array['status'] = SUCCESS_CODE;

                $json_array['message'] = 'Record Update Successfully';
            } else {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = 'Something went wrong,please try again';
            }
        } else {
            permission_denied();
        }

        echo_json($json_array);
    }

    private function get_holidays_list($srart_date, $end_date)
    {

        $this->CI = &get_instance();

        $this->CI->db->select('holiday_date')->from('holiday_dates');

        $this->CI->db->where('holiday_date >=', $srart_date);

        $this->CI->db->where('holiday_date <=', $end_date);

        $result = $this->CI->db->get()->result_array();

        $return_date = '';

        if (!empty($result) && count($result) > 0) {
            $days = count($result);
            $return_date = getWorkingDays_increament($end_date, array(), $days);
        } else {
            $return_date = $end_date;
        }

        return $return_date;
    }

    protected function case_tat_status($date)
    {
        $today_date = new DateTime(date(DATE_ONLY));

        $datetime2 = new DateTime($date);

        $difference = $today_date->diff($datetime2);
        $difference = $difference->d;

        if ($date < date(DATE_ONLY)) {
            $tat_stauts = 'OUT TAT';
        } else if ($difference == 0) {
            $tat_stauts = 'TDY TAT';
        } else if (3 >= $difference) {
            $tat_stauts = 'AP TAT';
        } else if ($difference >= 4) {
            $tat_stauts = 'IN TAT';
        }

        return $tat_stauts;
    }

    public function set_wip_vendor_details()
    {
        $vendor_id =  $this->input->post();

      
        $result = $this->Vendor_Pending_Cases_Mail($vendor_id);

        $update_cronjob_detail = $this->Cron_job_model->save(array('created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id'], 'executed_on' => date(DB_DATE_FORMAT), 'status' => 1), array('id' => 1));

        $json_array['message'] = "Send Mail Successfully";

        $json_array['status'] = SUCCESS_CODE;

        echo_json($json_array);

    }

    public function Vendor_Pending_Cases_Mail($vendor_id)
    {

        set_time_limit(0);

        // $this->load->library('email');

        $this->load->model('vendor/Vendor_common_model');

        $vendor_details = $this->Vendor_common_model->get_vendor_details($vendor_id);
        //  $all_records =  $this->Vendor_common_model->get_vedor_datails_for_mail();

        foreach ($vendor_details as $vendor_detail) {

            $vendors_components = explode(',', $vendor_detail['vendors_components']);
            
            if (in_array("addrver", $vendors_components)) {

                $addrver = $this->Vendor_common_model->get_vedor_datails_for_address($vendor_detail['id']);
             
                if (!empty($addrver)) {
                  
                    $count_datewise_record = $this->Vendor_common_model->get_address_date_wise_count($vendor_detail['id']);
                    
                    $address_array = array();
                    foreach ($count_datewise_record as $count_datewise_records) {
                        $hold_day = getNetWorkDays($count_datewise_records['date'], date("d-m-Y"));
                        
                        array_push( $address_array,$hold_day);
                    }
                  
                  //  $user_info = $this->Vendor_common_model->get_address_user_name_password(array('status' => 1, 'id' => 3));

                 //   $reporting_manager_info = $this->Vendor_common_model->get_repoting_manager_email_id(array('status' => 1, 'id' => $user_info[0]['reporting_manager']));

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

                 
                    $subject = 'Address_Pending cases_'.ucwords($vendor_detail['vendor_name']) .'_'. date('d-M-Y');
                 

                  $message = "<p>Team,</p><p>Please find attached list of pending cases.</p>";
              
                  if(min($address_array) < 7)
                  {
                
                  $message .= "<table border = '1'>
                  <tr>
                  <td style='text-align:center' colspan = '3'><b>IN TAT</b> </td>
                  </tr>
                  <tr>
                  <th style='text-align:center'>Allocated date </th>
                  <th style='text-align:center'>Cases</th>
                  <th style='text-align:center'>Days</th>
                  </tr>";
                    $total = 0;
                    foreach ($count_datewise_record as $count_datewise_records) {
                        $hold_day = getNetWorkDays($count_datewise_records['date'], date("d-m-Y"));

                        if($hold_day < 7)
                        {
                         

                        $message .= '<tr>
                  <td style="text-align:center">' . $count_datewise_records['date'] . '</td>
                  <td style="text-align:center">' . $count_datewise_records['count_record'] . '</td>
                  <td style="text-align:center">' . $hold_day . '</td>
                  </tr>';
                        $total += $count_datewise_records['count_record'];

                        }
                    }

                    $message .= '<tr><td style="text-align:center"><b>Total Cases</b></td><td style="text-align:center" colspan="2"><b>' . $total . '</b></tr>';
                    $message .= "</table><br>";
                    } 
                    
                    if(max($address_array) > 6)
                    {
                    $message .= "<table border = '1'>
                    <tr>
                    <td style='text-align:center;color:red;' colspan = '3'><b>OUT TAT</b> </td>
                    </tr>
                    <tr>
                    <th style='text-align:center'>Allocated date </th>
                    <th style='text-align:center'>Cases</th>
                    <th style='text-align:center'>Days</th>
                    </tr>";
                      $total = 0;
                      foreach ($count_datewise_record as $count_datewise_records) {
                          $hold_day = getNetWorkDays($count_datewise_records['date'], date("d-m-Y"));
  
                          if($hold_day > 6)
                          {
                             
                          $message .= '<tr>
                    <td style="text-align:center">' . $count_datewise_records['date'] . '</td>
                    <td style="text-align:center">' . $count_datewise_records['count_record'] . '</td>
                    <td style="text-align:center">' . $hold_day . '</td>
                    </tr>';
                          $total += $count_datewise_records['count_record'];

                          }
                      }
                
  
                    $message .= '<tr><td style="text-align:center"><b>Total Cases</b></td><td style="text-align:center;color:red;" colspan="2"><b>' . $total . '</b></tr>';
                    $message .= "</table><br>";
                    }

                    $message .= "<p><b>Note :</b> <I>This is an auto generated email. Request you to write back in case a check is closed as per your knowledge but is showing as pending.</I></p>";
              
                    $email_tmpl_data['to_emails'] = $vendor_detail['email_id'];
                    $email_tmpl_data['attchment'] = $file_name;
                   // $email_tmpl_data['user_email_id'] = $user_info[0]['email'];
                   // $email_tmpl_data['reporting_email_id'] = $reporting_manager_info[0]['email'];
                    $email_tmpl_data['vendor_name'] = $vendor_detail['vendor_name'];
                    $email_tmpl_data['message'] = $message;
                    $email_tmpl_data['subject'] = $subject;

                   // $email_tmpl_data['email_password'] = base64_decode($user_info[0]['email_password']);

                    //$result = $this->email->vendor_case_send_mail($email_tmpl_data);

                    $result = $this->vendor_case_send_mail($email_tmpl_data);

                    //   $this->email->Clear(TRUE);
                
                }
            }

            if (in_array("empver", $vendors_components)) {

                $empver = $this->Vendor_common_model->get_vedor_datails_for_employment($vendor_detail['id']);

                if (!empty($empver)) {

                    $count_datewise_record = $this->Vendor_common_model->get_employment_date_wise_count($vendor_detail['id']);
                     
                    $employment_array = array();
                    foreach ($count_datewise_record as $count_datewise_records) {
                        $hold_day = getNetWorkDays($count_datewise_records['date'], date("d-m-Y"));
                        
                        array_push( $employment_array,$hold_day);
                    }
                  
                   // $user_info = $this->Vendor_common_model->get_address_user_name_password(array('status' => 1, 'id' => 3));

                  //  $reporting_manager_info = $this->Vendor_common_model->get_repoting_manager_email_id(array('status' => 1, 'id' => $user_info[0]['reporting_manager']));

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

                    $subject = 'Employment_Pending cases_'.ucwords($vendor_detail['vendor_name']) .'_'. date('d-M-Y');

                   $message = "<p>Team,</p><p>Please find attached list of pending cases.</p>";
                  
                  if(min($employment_array) < 7)
                  {
    
                    $message .= "<table border = '1'>
                    <tr>
                      <td style='text-align:center' colspan = '3'><b>IN TAT</b> </td>
                    </tr>
                  <tr>
                  <th style='text-align:center'>Allocated date </th>
                  <th style='text-align:center'>Cases</th>
                  <th style='text-align:center'>Days</th>
                  </tr>";
                    $total = 0;
                    foreach ($count_datewise_record as $count_datewise_records) {
                        $hold_day = getNetWorkDays($count_datewise_records['date'], date("d-m-Y"));

                        if($hold_day < 7)
                        {
                        $message .= '<tr>
                  <td style="text-align:center">' . $count_datewise_records['date'] . '</td>
                  <td style="text-align:center">' . $count_datewise_records['count_record'] . '</td>
                  <td style="text-align:center">' . $hold_day . '</td>
                  </tr>';
                        $total += $count_datewise_records['count_record'];

                        }

                    }
                    $message .= '<tr><td style="text-align:center"><b>Total Cases</b></td><td style="text-align:center" colspan = "2"><b>' . $total . '</b></tr>';
                    $message .= "</table><br>";
                  }
                  if(max($employment_array) > 6)
                  {
                
                    $message .= "<table border = '1'>
                    <tr>
                       <td style='text-align:center;color:red;' colspan = '3'><b>OUT TAT</b> </td>
                    </tr>
                  <tr>
                  <th style='text-align:center'>Allocated date </th>
                  <th style='text-align:center'>Cases</th>
                  <th style='text-align:center'>Days</th>
                  </tr>";
                    $total = 0;
                    foreach ($count_datewise_record as $count_datewise_records) {
                        $hold_day = getNetWorkDays($count_datewise_records['date'], date("d-m-Y"));

                        if($hold_day > 6)
                        {
                        $message .= '<tr>
                  <td style="text-align:center">' . $count_datewise_records['date'] . '</td>
                  <td style="text-align:center">' . $count_datewise_records['count_record'] . '</td>
                  <td style="text-align:center">' . $hold_day . '</td>
                  </tr>';
                        $total += $count_datewise_records['count_record'];

                        }

                    }
                    $message .= '<tr><td style="text-align:center"><b>Total Cases</b></td><td style="text-align:center;color:red;" colspan = "2"><b>' . $total . '</b></tr>';
                    $message .= "</table><br>";
                }

                    $message .= "<p><b>Note :</b> <I>This is an auto generated email. Request you to write back in case a check is closed as per your knowledge but is showing as pending.</I></p>";


                    $email_tmpl_data['to_emails'] = $vendor_detail['email_id'];
                    $email_tmpl_data['attchment'] = $file_name;
                   // $email_tmpl_data['user_email_id'] = $user_info[0]['email'];
                   // $email_tmpl_data['reporting_email_id'] = $reporting_manager_info[0]['email'];
                    $email_tmpl_data['vendor_name'] = $vendor_detail['vendor_name'];
                    $email_tmpl_data['message'] = $message;
                    $email_tmpl_data['subject'] = $subject;

                  //  $email_tmpl_data['email_password'] = base64_decode($user_info[0]['email_password']);

                    //  $result = $this->email->vendor_case_send_mail($email_tmpl_data);

                    $result = $this->vendor_case_send_mail($email_tmpl_data);
                    //  $this->email->Clear(TRUE);

                }
            }
           /* if (in_array("eduver", $vendors_components)) {

                $eduver = $this->Vendor_common_model->get_vedor_datails_for_education($vendor_detail['id']);

                if (!empty($eduver)) {
                    $count_datewise_record = $this->Vendor_common_model->get_education_date_wise_count($vendor_detail['id']);
                   
                    $education_array = array();
                    foreach ($count_datewise_record as $count_datewise_records) {
                        $hold_day = getNetWorkDays($count_datewise_records['date'], date("d-m-Y"));
                        
                        array_push( $education_array,$hold_day);
                    }
                  //  $user_info = $this->Vendor_common_model->get_address_user_name_password(array('status' => 1, 'id' => 16));

                  //  $reporting_manager_info = $this->Vendor_common_model->get_repoting_manager_email_id(array('status' => 1, 'id' => $user_info[0]['reporting_manager']));

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

                    $spreadsheet->getActiveSheet()->getStyle('A1:P1')->applyFromArray($styleArray);

                    // auto fit column to content
                    foreach (range('A', 'P') as $columnID) {
                        $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                            ->setWidth(20);
                    }

                    $spreadsheet->setActiveSheetIndex(0)

                        ->setCellValue("A1", 'Vendor Assigned on')
                        ->setCellValue("B1", 'Component Ref No')
                        ->setCellValue("C1", 'Transaction No')
                        ->setCellValue("D1", 'Candidate Name')
                        ->setCellValue("E1", 'Mode of Verification')
                        ->setCellValue("F1", 'Qualification')
                        ->setCellValue("G1", 'University')
                        ->setCellValue("H1", 'Grade/Class/Marks')
                        ->setCellValue("I1", 'Month Of Passing')
                        ->setCellValue("J1", 'Year Of Passing')
                        ->setCellValue("K1", 'Roll No')
                        ->setCellValue("L1", 'PRN No')
                        ->setCellValue("M1", 'Course Start date')
                        ->setCellValue("N1", 'Course End date')
                        ->setCellValue("O1", 'Major')
                        ->setCellValue("P1", 'Vendor');

                    $x = 2;

                    foreach ($eduver as $all_record) {

                        $spreadsheet->setActiveSheetIndex(0);

                        $spreadsheet->getActiveSheet()->setCellValue("A$x", convert_db_to_display_date($all_record['vendor_assign_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12));
                        $spreadsheet->getActiveSheet()->setCellValue("B$x", $all_record['component_ref_no']);
                        $spreadsheet->getActiveSheet()->setCellValue("C$x", $all_record['trasaction_id']);
                        $spreadsheet->getActiveSheet()->setCellValue("D$x", ucwords($all_record['CandidateName']));

                        $spreadsheet->getActiveSheet()->setCellValue("E$x", $all_record['mode_of_veri']);
                        $spreadsheet->getActiveSheet()->setCellValue("F$x", ucwords($all_record['qualification_name']));
                        $spreadsheet->getActiveSheet()->setCellValue("G$x", ucwords($all_record['university_name']));
                        $spreadsheet->getActiveSheet()->setCellValue("H$x", ucwords($all_record['grade_class_marks']));
                        $spreadsheet->getActiveSheet()->setCellValue("I$x", ucwords($all_record['month_of_passing']));
                        $spreadsheet->getActiveSheet()->setCellValue("J$x", $all_record['year_of_passing']);
                        $spreadsheet->getActiveSheet()->setCellValue("K$x", $all_record['roll_no']);
                        $spreadsheet->getActiveSheet()->setCellValue("L$x", $all_record['PRN_no']);
                        $spreadsheet->getActiveSheet()->setCellValue("M$x", $all_record['course_start_date']);
                        $spreadsheet->getActiveSheet()->setCellValue("N$x", $all_record['course_end_date']);
                        $spreadsheet->getActiveSheet()->setCellValue("O$x", ucwords($all_record['major']));
                        $spreadsheet->getActiveSheet()->setCellValue("P$x", ucwords($all_record['vendor_name']));
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

                    
                    $subject = 'Education_Pending cases_'.ucwords($vendor_detail['vendor_name']) .'_'. date('d-M-Y');

                    $message = "<p>Team,</p><p>Please find attached list of pending cases.</p>";
                  
                   if(min($education_array) < 7)
                   {
                   
                    $message .= "<table border = '1'>
                    <tr>
                       <td style='text-align:center' colspan = '3'><b>IN TAT</b> </td>
                    </tr>
                  <tr>
                  <th style='text-align:center'>Allocated date </th>
                  <th style='text-align:center'>Cases</th>
                  <th style='text-align:center'>Days</th>
                  </tr>";
                    $total = 0;
                    foreach ($count_datewise_record as $count_datewise_records) {
                        $hold_day = getNetWorkDays($count_datewise_records['date'], date("d-m-Y"));

                        if($hold_day < 7)
                        {
                         
                        $message .= '<tr>
                  <td style="text-align:center">' . $count_datewise_records['date'] . '</td>
                  <td style="text-align:center">' . $count_datewise_records['count_record'] . '</td>
                  <td style="text-align:center">' . $hold_day . '</td>
                  </tr>';
                        $total += $count_datewise_records['count_record'];

                        }

                    }
                    $message .= '<tr><td style="text-align:center"><b>Total Cases</b></td><td style="text-align:center" colspan = "2"><b>' . $total . '</b></tr>';
                    $message .= "</table><br>";
                   }
                   if(max($education_array) > 6)
                   {
                    $message .= "<table border = '1'>
                    <tr>
                       <td style='text-align:center;color:red;' colspan = '3'><b>OUT TAT</b> </td>
                    </tr>
                  <tr>
                  <th style='text-align:center'>Allocated date </th>
                  <th style='text-align:center'>Cases</th>
                  <th style='text-align:center'>Days</th>
                  </tr>";
                    $total = 0;
                    foreach ($count_datewise_record as $count_datewise_records) {
                        $hold_day = getNetWorkDays($count_datewise_records['date'], date("d-m-Y"));

                        if($hold_day > 6)
                        {
                         
                        $message .= '<tr>
                  <td style="text-align:center">' . $count_datewise_records['date'] . '</td>
                  <td style="text-align:center">' . $count_datewise_records['count_record'] . '</td>
                  <td style="text-align:center">' . $hold_day . '</td>
                  </tr>';
                        $total += $count_datewise_records['count_record'];

                        }

                    }
                    $message .= '<tr><td style="text-align:center"><b>Total Cases</b></td><td style="text-align:center;color:red;" colspan = "2"><b>' . $total . '</b></tr>';
                    $message .= "</table><br>";
                }
                    $message .= "<p><b>Note :</b> <I>This is an auto generated email. Request you to write back in case a check is closed as per your knowledge but is showing as pending.</I></p>";


                    $email_tmpl_data['to_emails'] = $vendor_detail['email_id'];
                    $email_tmpl_data['attchment'] = $file_name;
                  //  $email_tmpl_data['user_email_id'] = $user_info[0]['email'];
                  //  $email_tmpl_data['reporting_email_id'] = $reporting_manager_info[0]['email'];
                    $email_tmpl_data['vendor_name'] = $vendor_detail['vendor_name'];
                    $email_tmpl_data['message'] = $message;
                    $email_tmpl_data['subject'] = $subject;
                    //  $result = $this->email->vendor_case_send_mail($email_tmpl_data);

                  //  $email_tmpl_data['email_password'] = base64_decode($user_info[0]['email_password']);

                    // $result = $this->email->vendor_case_send_mail($email_tmpl_data);

                    $result = $this->vendor_case_send_mail($email_tmpl_data);
                    //  $this->email->Clear(TRUE);

                }

            }*/
            if (in_array("courtver", $vendors_components)) {

                $courtver = $this->Vendor_common_model->get_vedor_datails_for_court($vendor_detail['id']);
                if (!empty($courtver)) {

                    $count_datewise_record = $this->Vendor_common_model->get_court_date_wise_count($vendor_detail['id']);
                   
                     
                    $court_array = array();
                    foreach ($count_datewise_record as $count_datewise_records) {
                        $hold_day = getNetWorkDays($count_datewise_records['date'], date("d-m-Y"));
                        
                        array_push( $court_array,$hold_day);
                    }
                 //   $user_info = $this->Vendor_common_model->get_address_user_name_password(array('status' => 1, 'id' => 13));

                 //   $reporting_manager_info = $this->Vendor_common_model->get_repoting_manager_email_id(array('status' => 1, 'id' => $user_info[0]['reporting_manager']));

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

                    $spreadsheet->getActiveSheet()->getStyle('A1:K1')->applyFromArray($styleArray);

                    // auto fit column to content
                    foreach (range('A', 'K') as $columnID) {
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
                        ->setCellValue("G1", 'Address')
                        ->setCellValue("H1", 'City')
                        ->setCellValue("I1", 'Pincode')
                        ->setCellValue("J1", 'State')
                        ->setCellValue("K1", 'Vendor');

                    $x = 2;

                    foreach ($courtver as $all_record) {

                        $spreadsheet->setActiveSheetIndex(0);

                        $spreadsheet->getActiveSheet()->setCellValue("A$x", convert_db_to_display_date($all_record['vendor_assign_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12));
                        $spreadsheet->getActiveSheet()->setCellValue("B$x", $all_record['component_ref_no']);
                        $spreadsheet->getActiveSheet()->setCellValue("C$x", $all_record['trasaction_id']);
                        $spreadsheet->getActiveSheet()->setCellValue("D$x", ucwords($all_record['CandidateName']));

                        $spreadsheet->getActiveSheet()->setCellValue("E$x", ucwords($all_record['NameofCandidateFather']));
                        $spreadsheet->getActiveSheet()->setCellValue("F$x",  date('d-M-Y', strtotime($all_record['DateofBirth'])));
                        $spreadsheet->getActiveSheet()->setCellValue("G$x", ucwords($all_record['street_address']));
                        $spreadsheet->getActiveSheet()->setCellValue("H$x", ucwords($all_record['city']));
                        $spreadsheet->getActiveSheet()->setCellValue("I$x", $all_record['pincode']);
                        $spreadsheet->getActiveSheet()->setCellValue("J$x", ucwords($all_record['state']));
                        $spreadsheet->getActiveSheet()->setCellValue("K$x", ucwords($all_record['vendor_name']));
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


                    $subject = 'Court_Pending cases_'.ucwords($vendor_detail['vendor_name']) .'_'. date('d-M-Y');

                    $message = "<p>Team,</p><p>Please find attached list of pending cases.</p>";
                  
                   if(min($court_array) < 7)
                   {
                   
                    $message .= "<table border = '1'>
                    <tr>
                       <td style='text-align:center' colspan = '3'><b>IN TAT</b> </td>
                    </tr>
                  <tr>
                  <th style='text-align:center'>Allocated date </th>
                  <th style='text-align:center'>Cases</th>
                  <th style='text-align:center'>Days</th>
                  </tr>";
                    $total = 0;
                    foreach ($count_datewise_record as $count_datewise_records) {
                        $hold_day = getNetWorkDays($count_datewise_records['date'], date("d-m-Y"));

                        if($hold_day < 7)
                        {
                        $message .= '<tr>
                  <td style="text-align:center">' . $count_datewise_records['date'] . '</td>
                  <td style="text-align:center">' . $count_datewise_records['count_record'] . '</td>
                  <td style="text-align:center">' . $hold_day . '</td>
                  </tr>';
                        $total += $count_datewise_records['count_record'];
                        }
                    }
                    $message .= '<tr><td style="text-align:center"><b>Total Cases</b></td><td style="text-align:center" colspan = "2"><b>' . $total . '</b></tr>';
                    $message .= "</table><br>";
                }   
                if(max($court_array) > 6)
                {
                    $message .= "<table border = '1'>
                    <tr>
                        <td style='text-align:center;color:red;' colspan = '3'><b>OUT TAT</b> </td>
                    </tr>
                    <tr>
                    <th style='text-align:center'>Allocated date </th>
                    <th style='text-align:center'>Cases</th>
                    <th style='text-align:center'>Days</th>
                    </tr>";
                      $total = 0;
                      foreach ($count_datewise_record as $count_datewise_records) {
                          $hold_day = getNetWorkDays($count_datewise_records['date'], date("d-m-Y"));
  
                          if($hold_day > 6)
                          {
                          $message .= '<tr>
                    <td style="text-align:center">' . $count_datewise_records['date'] . '</td>
                    <td style="text-align:center">' . $count_datewise_records['count_record'] . '</td>
                    <td style="text-align:center">' . $hold_day . '</td>
                    </tr>';
                          $total += $count_datewise_records['count_record'];
                          }
                      }
                    $message .= '<tr><td style="text-align:center"><b>Total Cases</b></td><td style="text-align:center;color:red;" colspan = "2"><b>' . $total . '</b></tr>';
                    $message .= "</table><br>";
                }
                    $message .= "<p><b>Note :</b> <I>This is an auto generated email. Request you to write back in case a check is closed as per your knowledge but is showing as pending.</I></p>";


                    $email_tmpl_data['to_emails'] = $vendor_detail['email_id'];
                    $email_tmpl_data['attchment'] = $file_name;
                  //  $email_tmpl_data['user_email_id'] = $user_info[0]['email'];
                 //   $email_tmpl_data['reporting_email_id'] = $reporting_manager_info[0]['email'];
                    $email_tmpl_data['vendor_name'] = $vendor_detail['vendor_name'];
                    $email_tmpl_data['message'] = $message;
                    $email_tmpl_data['subject'] = $subject;
                    // $result = $this->email->vendor_case_send_mail($email_tmpl_data);

                   // $email_tmpl_data['email_password'] = base64_decode($user_info[0]['email_password']);

                    // $result = $this->email->vendor_case_send_mail($email_tmpl_data);

                    $result = $this->vendor_case_send_mail($email_tmpl_data);
                    //  $this->email->Clear(TRUE);

                }

            }

            if (in_array("globdbver", $vendors_components)) {

                $globdbver = $this->Vendor_common_model->get_vedor_datails_for_global($vendor_detail['id']);

                if (!empty($globdbver)) {

                    $count_datewise_record = $this->Vendor_common_model->get_global_date_wise_count($vendor_detail['id']);
                    $global_array = array();
                    foreach ($count_datewise_record as $count_datewise_records) {
                        $hold_day = getNetWorkDays($count_datewise_records['date'], date("d-m-Y"));
                        
                        array_push( $global_array,$hold_day);
                    }
                    //$user_info = $this->Vendor_common_model->get_address_user_name_password(array('status' => 1, 'id' => 13));

                   // $reporting_manager_info = $this->Vendor_common_model->get_repoting_manager_email_id(array('status' => 1, 'id' => $user_info[0]['reporting_manager']));

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
                        ->setCellValue("G1", 'Gender')
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
                        $spreadsheet->getActiveSheet()->setCellValue("F$x", date('d-M-Y', strtotime($all_record['DateofBirth'])));
                        $spreadsheet->getActiveSheet()->setCellValue("G$x", $all_record['gender']);
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


                    $subject = 'Global_Pending cases_'.ucwords($vendor_detail['vendor_name']) .'_'. date('d-M-Y');

                    $message = "<p>Team,</p><p>Please find attached list of pending cases.</p>";
               

                if(min($global_array) < 7)
                {
                    
                    $message .= "<table border = '1'>
                    <tr>
                       <td style='text-align:center' colspan = '3'><b>IN TAT</b> </td>
                    </tr>
                  <tr>
                  <th style='text-align:center'>Allocated date </th>
                  <th style='text-align:center'>Cases</th>
                  <th style='text-align:center'>Days</th>
                  </tr>";
                    $total = 0;
                    foreach ($count_datewise_record as $count_datewise_records) {
                        $hold_day = getNetWorkDays($count_datewise_records['date'], date("d-m-Y"));

                        if($hold_day < 7)
                        {
                        $message .= '<tr>
                  <td style="text-align:center">' . $count_datewise_records['date'] . '</td>
                  <td style="text-align:center">' . $count_datewise_records['count_record'] . '</td>
                  <td style="text-align:center">' . $hold_day . '</td>
                  </tr>';
                        $total += $count_datewise_records['count_record'];

                        }
                    }
                    $message .= '<tr><td style="text-align:center"><b>Total Cases</b></td><td style="text-align:center" colspan = "2"><b>' . $total . '</b></tr>';
                    $message .= "</table><br>";
                }
                if(max($global_array) > 6)
                {
                   
                    $message .= "<table border = '1'>
                    <tr>
                       <td style='text-align:center;color:red;' colspan = '3'><b>OUT TAT</b> </td>
                    </tr>
                    <tr>
                    <th style='text-align:center'>Allocated date </th>
                    <th style='text-align:center'>Cases</th>
                    <th style='text-align:center'>Days</th>
                    </tr>";
                      $total = 0;
                      foreach ($count_datewise_record as $count_datewise_records) {
                          $hold_day = getNetWorkDays($count_datewise_records['date'], date("d-m-Y"));
  
                          if($hold_day > 6)
                          {
                          $message .= '<tr>
                    <td style="text-align:center">' . $count_datewise_records['date'] . '</td>
                    <td style="text-align:center">' . $count_datewise_records['count_record'] . '</td>
                    <td style="text-align:center">' . $hold_day . '</td>
                    </tr>';
                          $total += $count_datewise_records['count_record'];
  
                          }
                      }
                      $message .= '<tr><td style="text-align:center"><b>Total Cases</b></td><td style="text-align:center;color:red;" colspan = "2"><b>' . $total . '</b></tr>';
                      $message .= "</table><br>";
                }
                    $message .= "<p><b>Note :</b> <I>This is an auto generated email. Request you to write back in case a check is closed as per your knowledge but is showing as pending.</I></p>";


                    $email_tmpl_data['to_emails'] = $vendor_detail['email_id'];
                    $email_tmpl_data['attchment'] = $file_name;
                  //  $email_tmpl_data['user_email_id'] = $user_info[0]['email'];
                  //  $email_tmpl_data['reporting_email_id'] = $reporting_manager_info[0]['email'];
                    $email_tmpl_data['vendor_name'] = $vendor_detail['vendor_name'];
                    $email_tmpl_data['message'] = $message;
                    $email_tmpl_data['subject'] = $subject;
                    // $result = $this->email->vendor_case_send_mail($email_tmpl_data);

                  //  $email_tmpl_data['email_password'] = base64_decode($user_info[0]['email_password']);

                    //  $result = $this->email->vendor_case_send_mail($email_tmpl_data);

                    $result = $this->vendor_case_send_mail($email_tmpl_data);
                    //  $this->email->Clear(TRUE);

                }

            }
            if (in_array("crimver", $vendors_components)) {

                $crimver = $this->Vendor_common_model->get_vedor_datails_for_pcc($vendor_detail['id']);
                if (!empty($crimver)) {

                    $count_datewise_record = $this->Vendor_common_model->get_pcc_date_wise_count($vendor_detail['id']);
                     
                    $pcc_array = array();
                    foreach ($count_datewise_record as $count_datewise_records) {
                        $hold_day = getNetWorkDays($count_datewise_records['date'], date("d-m-Y"));
                        
                        array_push( $pcc_array,$hold_day);
                    }
                  //  $user_info = $this->Vendor_common_model->get_address_user_name_password(array('status' => 1, 'id' => 13));

                  //  $reporting_manager_info = $this->Vendor_common_model->get_repoting_manager_email_id(array('status' => 1, 'id' => $user_info[0]['reporting_manager']));

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

                    $spreadsheet->getActiveSheet()->getStyle('A1:K1')->applyFromArray($styleArray);

                    // auto fit column to content
                    foreach (range('A', 'K') as $columnID) {
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
                        ->setCellValue("G1", 'Address')
                        ->setCellValue("H1", 'City')
                        ->setCellValue("I1", 'Pincode')
                        ->setCellValue("J1", 'State')
                        ->setCellValue("K1", 'Vendor');

                    $x = 2;

                    foreach ($crimver as $all_record) {

                        $spreadsheet->setActiveSheetIndex(0);

                        $spreadsheet->getActiveSheet()->setCellValue("A$x", convert_db_to_display_date($all_record['vendor_assign_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12));
                        $spreadsheet->getActiveSheet()->setCellValue("B$x", $all_record['component_ref_no']);
                        $spreadsheet->getActiveSheet()->setCellValue("C$x", $all_record['trasaction_id']);
                        $spreadsheet->getActiveSheet()->setCellValue("D$x", ucwords($all_record['CandidateName']));

                        $spreadsheet->getActiveSheet()->setCellValue("E$x", ucwords($all_record['NameofCandidateFather']));
                        $spreadsheet->getActiveSheet()->setCellValue("F$x", date('d-M-Y', strtotime($all_record['DateofBirth'])));
                        $spreadsheet->getActiveSheet()->setCellValue("G$x", ucwords($all_record['street_address']));
                        $spreadsheet->getActiveSheet()->setCellValue("H$x", ucwords($all_record['city']));
                        $spreadsheet->getActiveSheet()->setCellValue("I$x", $all_record['pincode']);
                        $spreadsheet->getActiveSheet()->setCellValue("J$x", ucwords($all_record['state']));
                        $spreadsheet->getActiveSheet()->setCellValue("K$x", ucwords($all_record['vendor_name']));
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

                    
                    $subject = 'PCC_Pending cases_'.ucwords($vendor_detail['vendor_name']) .'_'. date('d-M-Y');

                    $message = "<p>Team,</p><p>Please find attached list of pending cases.</p>";
              
                if(min($pcc_array) < 7)
                {
                  
                    $message .= "<table border = '1'>
                    <tr>
                        <td style='text-align:center' colspan = '3'><b>IN TAT</b> </td>
                    </tr>
                  <tr>
                  <th style='text-align:center'>Allocated date </th>
                  <th style='text-align:center'>Cases</th>
                  <th style='text-align:center'>Days</th>
                  </tr>";
                    $total = 0;
                    foreach ($count_datewise_record as $count_datewise_records) {
                        $hold_day = getNetWorkDays($count_datewise_records['date'], date("d-m-Y"));

                        
                        if($hold_day < 7)
                        {
                         
                        $message .= '<tr>
                  <td style="text-align:center">' . $count_datewise_records['date'] . '</td>
                  <td style="text-align:center">' . $count_datewise_records['count_record'] . '</td>
                  <td style="text-align:center">' . $hold_day . '</td>
                  </tr>';
                        $total += $count_datewise_records['count_record'];
                        }
                    }
                    $message .= '<tr><td style="text-align:center"><b>Total Cases</b></td><td style="text-align:center" colspan = "2"><b>' . $total . '</b></tr>';
                    $message .= "</table><br>";
                }
                if(max($pcc_array) > 6)
                {
                   
                    $message .= "<table border = '1'>
                    <tr>
                       <td style='text-align:center;color:red;' colspan = '3'><b>OUT TAT</b> </td>
                    </tr>
                    <tr>
                    <th style='text-align:center'>Allocated date </th>
                    <th style='text-align:center'>Cases</th>
                    <th style='text-align:center'>Days</th>
                    </tr>";
                      $total = 0;
                      foreach ($count_datewise_record as $count_datewise_records) {
                          $hold_day = getNetWorkDays($count_datewise_records['date'], date("d-m-Y"));
  
                          
                          if($hold_day > 6)
                          {
                           
                          $message .= '<tr>
                    <td style="text-align:center">' . $count_datewise_records['date'] . '</td>
                    <td style="text-align:center">' . $count_datewise_records['count_record'] . '</td>
                    <td style="text-align:center">' . $hold_day . '</td>
                    </tr>';
                          $total += $count_datewise_records['count_record'];
                          }
                      }
                      $message .= '<tr><td style="text-align:center"><b>Total Cases</b></td><td style="text-align:center;color:red;" colspan = "2"><b>' . $total . '</b></tr>';
                      $message .= "</table><br>";
                }
                    $message .= "<p><b>Note :</b> <I>This is an auto generated email. Request you to write back in case a check is closed as per your knowledge but is showing as pending.</I></p>";


                    $email_tmpl_data['to_emails'] = ($vendor_detail['pcc_mov_email'] == "") ? $vendor_detail['email_id'] : $vendor_detail['pcc_mov_email'];
                    $email_tmpl_data['attchment'] = $file_name;
                  //  $email_tmpl_data['user_email_id'] = $user_info[0]['email'];
                  //  $email_tmpl_data['reporting_email_id'] = $reporting_manager_info[0]['email'];
                    $email_tmpl_data['vendor_name'] = $vendor_detail['vendor_name'];
                    $email_tmpl_data['message'] = $message;
                    $email_tmpl_data['subject'] = $subject;
                    //$result = $this->email->vendor_case_send_mail($email_tmpl_data);

                  //  $email_tmpl_data['email_password'] = base64_decode($user_info[0]['email_password']);

                    //$result = $this->email->vendor_case_send_mail($email_tmpl_data);

                    $result = $this->vendor_case_send_mail($email_tmpl_data);
                    //  $this->email->Clear(TRUE);

                }

            }

            if (in_array("cbrver", $vendors_components)) {

                $cbrver = $this->Vendor_common_model->get_vedor_datails_for_credit_report($vendor_detail['id']);
                if (!empty($cbrver)) {

                    $count_datewise_record = $this->Vendor_common_model->get_credit_date_wise_count($vendor_detail['id']);
                    
                    $credit_array = array();
                    foreach ($count_datewise_record as $count_datewise_records) {
                        $hold_day = getNetWorkDays($count_datewise_records['date'], date("d-m-Y"));
                        
                        array_push( $credit_array,$hold_day);
                    }
                  //  $user_info = $this->Vendor_common_model->get_address_user_name_password(array('status' => 1, 'id' => 13));

                   // $reporting_manager_info = $this->Vendor_common_model->get_repoting_manager_email_id(array('status' => 1, 'id' => $user_info[0]['reporting_manager']));

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

                    $spreadsheet->getActiveSheet()->getStyle('A1:J1')->applyFromArray($styleArray);

                    // auto fit column to content
                    foreach (range('A', 'J') as $columnID) {
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
                        ->setCellValue("G1", 'PAN No')
                        ->setCellValue("H1", 'Address')
                        ->setCellValue("I1", 'Document Submitted')
                        ->setCellValue("J1", 'Vendor');

                    $x = 2;

                    foreach ($cbrver as $all_record) {

                        $spreadsheet->setActiveSheetIndex(0);

                        $spreadsheet->getActiveSheet()->setCellValue("A$x", convert_db_to_display_date($all_record['vendor_assign_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12));
                        $spreadsheet->getActiveSheet()->setCellValue("B$x", $all_record['component_ref_no']);
                        $spreadsheet->getActiveSheet()->setCellValue("C$x", $all_record['trasaction_id']);
                        $spreadsheet->getActiveSheet()->setCellValue("D$x", ucwords($all_record['CandidateName']));

                        $spreadsheet->getActiveSheet()->setCellValue("E$x", ucwords($all_record['NameofCandidateFather']));
                        $spreadsheet->getActiveSheet()->setCellValue("F$x", convert_db_to_display_date($all_record['DateofBirth']));
                        $spreadsheet->getActiveSheet()->setCellValue("G$x", ucwords($all_record['id_number']));
                        $spreadsheet->getActiveSheet()->setCellValue("H$x",$all_record['street_address']);
                        $spreadsheet->getActiveSheet()->setCellValue("I$x", ucwords($all_record['doc_submited']));
                        $spreadsheet->getActiveSheet()->setCellValue("J$x", ucwords($all_record['vendor_name']));
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

                  
                    $subject = 'CREDIT_REPORT_Pending cases_'.ucwords($vendor_detail['vendor_name']) .'_'. date('d-M-Y');

                    $message = "<p>Team,</p><p>Please find attached list of pending cases.</p>";
              
                if(min($credit_array) < 7)
                {
                  
                    $message .= "<table border = '1'>
                    <tr>
                       <td style='text-align:center' colspan = '3'><b>IN TAT</b> </td>
                    </tr>
                  <tr>
                  <th style='text-align:center'>Allocated date </th>
                  <th style='text-align:center'>Cases</th>
                  <th style='text-align:center'>Days</th>
                  </tr>";
                    $total = 0;

                    foreach ($count_datewise_record as $count_datewise_records) {
                        $hold_day = getNetWorkDays($count_datewise_records['date'], date("d-m-Y"));

                        
                        if($hold_day < 7)
                        {
                        $message .= '<tr>
                  <td style="text-align:center">' . $count_datewise_records['date'] . '</td>
                  <td style="text-align:center">' . $count_datewise_records['count_record'] . '</td>
                  <td style="text-align:center">' . $hold_day . '</td>
                  </tr>';
                        $total += $count_datewise_records['count_record'];
                        }
                    }
                    $message .= '<tr><td style="text-align:center"><b>Total Cases</b></td><td style="text-align:center" colspan = "2"><b>' . $total . '</b></tr>';
                    $message .= "</table><br>";
                }
                if(max($credit_array) > 6)
                {
                    $message .= "<table border = '1'>
                    <tr>
                        <td style='text-align:center;color:red;' colspan = '3'><b>OUT TAT</b> </td>
                    </tr>
                    <tr>
                    <th style='text-align:center'>Allocated date </th>
                    <th style='text-align:center'>Cases</th>
                    <th style='text-align:center'>Days</th>
                    </tr>";
                      $total = 0;
  
                      foreach ($count_datewise_record as $count_datewise_records) {
                          $hold_day = getNetWorkDays($count_datewise_records['date'], date("d-m-Y"));
  
                          
                          if($hold_day > 6)
                          {
                          $message .= '<tr>
                    <td style="text-align:center">' . $count_datewise_records['date'] . '</td>
                    <td style="text-align:center">' . $count_datewise_records['count_record'] . '</td>
                    <td style="text-align:center">' . $hold_day . '</td>
                    </tr>';
                          $total += $count_datewise_records['count_record'];
                          }
                      }
                      $message .= '<tr><td style="text-align:center"><b>Total Cases</b></td><td style="text-align:center;color:red;" colspan = "2"><b>' . $total . '</b></tr>';
                      $message .= "</table><br>";
                }
                    $message .= "<p><b>Note :</b> <I>This is an auto generated email. Request you to write back in case a check is closed as per your knowledge but is showing as pending.</I></p>";


                    $email_tmpl_data['to_emails'] = $vendor_detail['email_id'];
                    $email_tmpl_data['attchment'] = $file_name;
                   // $email_tmpl_data['user_email_id'] = $user_info[0]['email'];
                   // $email_tmpl_data['reporting_email_id'] = $reporting_manager_info[0]['email'];
                    $email_tmpl_data['vendor_name'] = $vendor_detail['vendor_name'];
                    $email_tmpl_data['message'] = $message;
                    $email_tmpl_data['subject'] = $subject;

                   // $email_tmpl_data['email_password'] = base64_decode($user_info[0]['email_password']);

                    //$result = $this->email->vendor_case_send_mail($email_tmpl_data);

                    $result = $this->vendor_case_send_mail($email_tmpl_data);

                    // $this->email->Clear(TRUE);

                }
            }

            if (in_array("narcver", $vendors_components)) {

                $narcver = $this->Vendor_common_model->get_vedor_datails_for_drugs($vendor_detail['id']);
                if (!empty($narcver)) {

                    $count_datewise_record = $this->Vendor_common_model->get_drugs_date_wise_count($vendor_detail['id']);
                    $drugs_array = array();
                    foreach ($count_datewise_record as $count_datewise_records) {
                        $hold_day = getNetWorkDays($count_datewise_records['date'], date("d-m-Y"));
                        
                        array_push( $drugs_array,$hold_day);
                    }
                  //  $user_info = $this->Vendor_common_model->get_address_user_name_password(array('status' => 1, 'id' => 13));

                   // $reporting_manager_info = $this->Vendor_common_model->get_repoting_manager_email_id(array('status' => 1, 'id' => $user_info[0]['reporting_manager']));

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
                        ->setCellValue("G1", 'Drugs Test Panel')
                        ->setCellValue("H1", 'Vendor');

                    $x = 2;

                    foreach ($narcver as $all_record) {

                        $spreadsheet->setActiveSheetIndex(0);

                        $spreadsheet->getActiveSheet()->setCellValue("A$x", convert_db_to_display_date($all_record['vendor_assign_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12));
                        $spreadsheet->getActiveSheet()->setCellValue("B$x", $all_record['component_ref_no']);
                        $spreadsheet->getActiveSheet()->setCellValue("C$x", $all_record['trasaction_id']);
                        $spreadsheet->getActiveSheet()->setCellValue("D$x", ucwords($all_record['CandidateName']));

                        $spreadsheet->getActiveSheet()->setCellValue("E$x", ucwords($all_record['NameofCandidateFather']));
                        $spreadsheet->getActiveSheet()->setCellValue("F$x", convert_db_to_display_date($all_record['DateofBirth']));
                        $spreadsheet->getActiveSheet()->setCellValue("G$x", ucwords($all_record['drug_test_code']));
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

                    $file_name = "Vendor_" . $vendor_detail['vendor_name'] . '_' . 'DRUGS' . ".xls";

                    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Excel2007');
                    ob_start();
                    $writer->save($file_upload_path . "/" . $file_name);
                    ob_end_clean();

                  
                    $subject = 'DRUGS_Pending cases_'.ucwords($vendor_detail['vendor_name']) .'_'. date('d-M-Y');

                    $message = "<p>Team,</p><p>Please find attached list of pending cases.</p>";
                
                if(min($drugs_array) < 7)
                {
                  
                    $message .= "<table border = '1'>
                    <tr>
                       <td style='text-align:center' colspan = '3'><b>IN TAT</b> </td>
                    </tr>
                  <tr>
                  <th style='text-align:center'>Allocated date </th>
                  <th style='text-align:center'>Cases</th>
                  <th style='text-align:center'>Days</th>
                  </tr>";
                    $total = 0;

                    foreach ($count_datewise_record as $count_datewise_records) {
                        $hold_day = getNetWorkDays($count_datewise_records['date'], date("d-m-Y"));

                        if($hold_day < 7)
                        {
                        $message .= '<tr>
                  <td style="text-align:center">' . $count_datewise_records['date'] . '</td>
                  <td style="text-align:center">' . $count_datewise_records['count_record'] . '</td>
                  <td style="text-align:center">' . $hold_day . '</td>
                  </tr>';
                        $total += $count_datewise_records['count_record'];

                        }
                    }
                    $message .= '<tr><td style="text-align:center"><b>Total Cases</b></td><td style="text-align:center" colspan = "2"><b>' . $total . '</b></tr>';
                    $message .= "</table><br>";
                }
                if(max($drugs_array) > 6)
                {
                
                    $message .= "<table border = '1'>
                    <tr>
                       <td style='text-align:center;color:red;' colspan = '3'><b>OUT TAT</b> </td>
                    </tr>
                    <tr>
                    <th style='text-align:center'>Allocated date </th>
                    <th style='text-align:center'>Cases</th>
                    <th style='text-align:center'>Days</th>
                    </tr>";
                      $total = 0;
  
                      foreach ($count_datewise_record as $count_datewise_records) {
                          $hold_day = getNetWorkDays($count_datewise_records['date'], date("d-m-Y"));
  
                          if($hold_day > 6)
                          {
                          $message .= '<tr>
                    <td style="text-align:center">' . $count_datewise_records['date'] . '</td>
                    <td style="text-align:center">' . $count_datewise_records['count_record'] . '</td>
                    <td style="text-align:center">' . $hold_day . '</td>
                    </tr>';
                          $total += $count_datewise_records['count_record'];
  
                          }
                      }
                      $message .= '<tr><td style="text-align:center"><b>Total Cases</b></td><td style="text-align:center;color:red;" colspan = "2"><b>' . $total . '</b></tr>';
                      $message .= "</table><br>";
                }
                    $message .= "<p><b>Note :</b> <I>This is an auto generated email. Request you to write back in case a check is closed as per your knowledge but is showing as pending.</I></p>";


                    $email_tmpl_data['to_emails'] = $vendor_detail['email_id'];
                    $email_tmpl_data['attchment'] = $file_name;
                   // $email_tmpl_data['user_email_id'] = $user_info[0]['email'];
                   // $email_tmpl_data['reporting_email_id'] = $reporting_manager_info[0]['email'];
                    $email_tmpl_data['vendor_name'] = $vendor_detail['vendor_name'];
                    $email_tmpl_data['message'] = $message;
                    $email_tmpl_data['subject'] = $subject;

                   // $email_tmpl_data['email_password'] = base64_decode($user_info[0]['email_password']);

                    //$result = $this->email->vendor_case_send_mail($email_tmpl_data);

                    $result = $this->vendor_case_send_mail($email_tmpl_data);

                    // $this->email->Clear(TRUE);

                }
            }
        }
    }

    public function vendor_case_send_mail($email_tmpl_data)
    {
        set_time_limit(0);

        $this->load->library('email');

        $this->email->clear(true);

        $to_emails = $email_tmpl_data['to_emails'];

        $cc_emails = VENDOREMAIL.",".MAINEMAIL;
       
        $cc_emails = explode(',', $cc_emails); 

        $cc_emails = array_unique($cc_emails); 

        $cc_emails = implode(",", $cc_emails); 
         
     // $bcc_emails = $email_tmpl_data['reporting_email_id'];
        $message = $email_tmpl_data['message'];
        $subject = $email_tmpl_data['subject'];
     // $email_password = $email_tmpl_data['email_password'];


        $config = array(
            'protocol' => 'smtp',
            'smtp_host' => SMTPHOST,
            'smtp_port' => 587,
            'smtp_user' => SMTPUSER,
            'smtp_pass' => SMTPPASSWORD,
            'smtp_timeout' => 30,
            'wordwrap' => true,
            'wrapchars' => 76,
            'mailtype' => 'html',
            'charset' => 'utf-8',
            'validate' => true,
            'priority' => 3,
            'smtp_crypto' => 'tsl',
            'smtp_auto_tls' => false,
            'smtp_conn_options' => array(),
            'smtp_debug' => 0,
            'debug_output' => ''
        
        );

        $this->email->initialize($config);

        $this->email->to($to_emails);

        $this->email->cc($cc_emails);

        $this->email->from(VENDOREMAIL);

        $this->email->subject($subject);

        $this->email->message($message);

        $path = SITE_BASE_PATH . UPLOAD_FOLDER . 'vendor_cases';

        $this->email->attach($path . "/" . $email_tmpl_data['attchment']);

        return $this->email->send();
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

    public function fino_finance_send_mail()
    {

        $result = $this->fino_finance_send_mail_details();

        $update_cronjob_detail = $this->Cron_job_model->save(array('created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id'], 'executed_on' => date(DB_DATE_FORMAT), 'status' => 1), array('id' => 4));

        $json_array['message'] = "Send Mail Successfully";

        $json_array['status'] = SUCCESS_CODE;

        echo_json($json_array);

    }

 

  /*  public function fino_finance_send_mail_details()
    {
      
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $client_id = 5;
               

            $all_records = $this->get_all_client_fino_finance($client_id);
           // $spoc_records = $this->Cron_job_model->get_all_client_spoc_details_with_count($client_id);


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
            $spreadsheet->getActiveSheet()->getStyle('A1:J1')->applyFromArray($styleArray);
            // auto fit column to content
            foreach (range('A', 'J') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                    ->setWidth(20);
            }
            // set the names of header cells

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A1", 'Client Ref No.')
                ->setCellValue("B1", REFNO)
                ->setCellValue("C1", 'Client Name')
                ->setCellValue("D1", 'Entity')
                ->setCellValue("E1", 'SPOC/Package')
                ->setCellValue("F1", 'Case Initiated')
                ->setCellValue("G1", 'Overall Status')
                ->setCellValue("H1", 'Candidate Name')
                ->setCellValue("I1", 'Insufficiency/Discrepancy')
                ->setCellValue("J1", 'Details');

            // Add some data
            $x = 2;
            foreach ($all_records as $all_record) {
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A$x", $all_record['ClientRefNumber'])
                    ->setCellValue("B$x", $all_record['cmp_ref_no'])
                    ->setCellValue("C$x", $all_record['clientname'])
                    ->setCellValue("D$x", $all_record['entity_name'])
                    ->setCellValue("E$x", $all_record['package_name'])
                    ->setCellValue("F$x", $all_record['caserecddate'])
                    ->setCellValue("G$x", $all_record['overallstatus'])
                    ->setCellValue("H$x", $all_record['CandidateName'])
                    ->setCellValue("I$x", $all_record['insufficiency_closed'])
                    ->setCellValue("J$x", $all_record['insuff_closed_details']);

                $x++;
            }

            $file_name = "Fino_payments_bank" . ".xls";
            // Rename worksheet
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="'.$file_name.'"');
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
            } else if (!is_writable($file_upload_path)) {
                mkdir($file_upload_path, 0777);
            }

           

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Excel2007');

            ob_start();
            $writer->save($file_upload_path . "/" . $file_name);
            ob_end_clean();

            $subject = 'Insufficiency/Major Discrepancy Cases '.'-'. date('d-M-Y');

            $message = "<p>Team,</p><p>Attached herewith are cases of Insufficiency/Major Discrepancy which requires your immediate attention. Request you to kindly check and advice.</p>";*/

           /* $message .= "<table border = '1'>
            <tr>
            <th>Spoc</th>
            <th>Insufficiency</th>
            <th>Mojor Discrepency</th>
            </tr>";
            $previous = '';
            foreach ($spoc_records  as $key => $value) {

              $key_val = explode("_", $key);
       
              $message .= "<tr>";  
              $message .= "<td>".$key_val[0]."</td>";
              if($key_val[1] == "Insufficiency") {
                $message .= "<td>".$value."</td>";
              }
              else{
                $message .= "<td>0</td>";
              } 
              if($key_val[1] == "Mojor Discrepency") {
                 $message .= "<td>".$value."</td>";
              } else{
                 $message .= "<td>0</td>";
              } 

               if($previous != $key_val[0]) {
     
                $message .= "</tr>"; 
               }

                $previous = $key_val[0];
             
            }
            $message .= "<tr>";
            $message .= "<td>Total</td>";  
           
            $message .= "</tr>";     
            $message .= "</table>";*/
          /*  $message .= "<p><b>Note :</b> <I>This is an automated email. Please write back in case of any discrepancy.</I></p>";


            $client_details_id = $this->Cron_job_model->select_client_details(array('tbl_clients_id'=>$client_id));
      
            $client_actual_id = array();
            foreach ($client_details_id as $key => $value) {
              $client_actual_id[] = $value['id'];  
            }
            
            $spoc_email_id = $this->Cron_job_model->select_spoc_mail_id($client_actual_id);
            $spoc_email = array();
            foreach ($spoc_email_id as $key => $value) {
              $spoc_email[] = $value['spoc_email'];  
            }   

            $client_details_id = $this->Cron_job_model->select_client_manager_details(array('id'=>$client_id));

            $client_details_email_id = $this->Cron_job_model->select_client_manager_email_details(array('id'=>$client_details_id[0]['clientmgr']));
            
            $spoc_email = array_unique($spoc_email);   
            $email_tmpl_data['from'] =  $client_details_email_id[0]['email'];     
            $email_tmpl_data['to_emails'] = implode(',',$spoc_email);
            $email_tmpl_data['attchment'] = $file_name;
             
            $email_tmpl_data['message'] = $message;
            $email_tmpl_data['subject'] = $subject;

            $result = $this->fino_finance_send_mail_client($email_tmpl_data);           
        
    }*/


    public function fino_finance_send_mail_details()
    {
        $clientid = 5;
         
        $this->load->library('email');  
        
        $cands_results = $this->Cron_job_model->get_all_cand_for_fino_finance($clientid);
    
       // foreach($cands_results as $k => $v) {
       //     $new_arr[$v['package']][]=$v;
      //  }


      //  foreach ($new_arr as $key => $value) {
            $client_details_id = array();
           
            $message = "<p>Team,</p><p>Attached herewith are cases which requires your immediate attention. Request you to kindly check and advice.</p>";

            $message .= "<table border = '2'  style='border-spacing: 10; border-collapse: collapse;'>
                        <tr>
                            <th style='background-color: #EDEDED;text-align:center'>Client Ref No</th>
                            <th style='background-color: #EDEDED;text-align:center'>CRM Ref No</th>
                            <th style='background-color: #EDEDED;text-align:center'>SPOC/Package</th>
                            <th style='background-color: #EDEDED;text-align:center'>Comp Initiation date</th>
                            <th style='background-color: #EDEDED;text-align:center'>Candidate Name</th>
                            <th style='background-color: #EDEDED;text-align:center'>Component</th>
                            <th style='background-color: #EDEDED;text-align:center'>Status</th>
                            <th style='background-color: #EDEDED;text-align:center'>Details</th>
                            <th style='background-color: #EDEDED;text-align:center'>Days</th>
                            
                        </tr>";

            foreach ($cands_results as $address_employee_key => $address_employee_value) {
              
               if(($address_employee_value['verfstatus'] != "19") or ($address_employee_value['overallstatus'] != "3")){

                $hold_day = getNetWorkDays($address_employee_value['iniated_date'], date("Y-m-d"));

                $hold_day = $hold_day + 1;
                                
            $message .= '<tr>
                            <td style="text-align:center">'.$address_employee_value['ClientRefNumber']. '</td>
                            <td style="text-align:center">'.ucwords($address_employee_value['cmp_ref_no']) . '</td>
                            <td style="text-align:center">'.ucwords($address_employee_value['package_name']) . '</td>
                            <td style="text-align:center">'.convert_db_to_display_date($address_employee_value['iniated_date']) . '</td>
                            <td style="text-align:center">'.ucwords($address_employee_value['CandidateName']) . '</td>
                            <td style="text-align:center">'.$address_employee_value['component_name'] . '</td>';
                             
                            if($address_employee_value['status'] == "Insufficiency"){
                                $message .=  '<td style="color: #FF0000;text-align:center">'.$address_employee_value['status'] . '</td>';
                            }
                            elseif($address_employee_value['status'] == "Major Discrepancy"){
                                $message .=  '<td style="background-color: #FF0000;text-align:center">'.$address_employee_value['status'] . '</td>';
                            }
                            else{
                                $message .=  '<td style="text-align:center">'."WIP". '</td>';
                            }
                            if($address_employee_value['verfstatus'] == "18"){
                                $message .= '<td style="text-align:center">'.$address_employee_value['details']." || ".$address_employee_value['insuff_raise_remark']. '</td>';
                            }
                            elseif($address_employee_value['verfstatus'] == "19"){
                                $message .= '<td style="text-align:center">'.$address_employee_value['details']." || ".$address_employee_value['verification_remarks']. '</td>'; 
                            }else{
                                $message .= '<td style="text-align:center">'.$address_employee_value['details']. '</td>'; 
                            }
                            $message .= '<td style="text-align:center">'.$hold_day.'</td>';
                           
            $message .=  '</tr>';
                }
                  
                
                $client_details_id[] = $this->Cron_job_model->select_client_details(array('tbl_clients_id'=>$address_employee_value['clientid'],'entity'=>$address_employee_value['entity'],'package'=>$address_employee_value['package']));
                             
            } 
            $message .= "</table>";

            $message .= "<br><br><p><b>Note :</b> <I>This is an automated generated email. Please write back in case of any discrepancy.</I>";
            

            if(!empty($client_details_id))
            {
                $client_details_id = array_unique(array_map('current',array_map('current',$client_details_id)));    

                $spoc_email_id = $this->Cron_job_model->select_spoc_mail_id($client_details_id);

                if(!empty($spoc_email_id))
                {  
                     $spoc_email = array();
                   //  $spoc_manager_email = array();
                     foreach($spoc_email_id as $key => $spoc_email_ids){
                        $spoc_email[]  =  $spoc_email_ids['spoc_email'];
                      //  $spoc_manager_email[]  =  $spoc_email_ids['spoc_manager_email'];
                     }
                }
                else
                {   
                     $spoc_email = array();
                   //  $spoc_manager_email = array();
                }
                   
            }

            $to_remove = array('neha.agarwal@finobank.com','swapnil_bhosale@finobank.com');
            $spoc_email = array_diff($spoc_email, $to_remove);
            $client_details_id = $this->Cron_job_model->select_client_manager_details(array('id'=>$address_employee_value['clientid']));
         
            $client_details_email_id = $this->Cron_job_model->select_client_manager_email_details(array('id'=>$client_details_id[0]['clientmgr']));
          
            $email_tmpl_data['from'] =  $client_details_email_id[0]['email'];   
            $email_tmpl_data['to_emails'] = implode(',',$spoc_email);
            $email_tmpl_data['cc_emails'] = $client_details_email_id[0]['email'].",".MAINEMAIL.","."neha.agarwal@finobank.com,swapnil_bhosale@finobank.com";
             
            $email_tmpl_data['subject'] = ucwords($address_employee_value['clientname']).' Pending cases '.date("d-M-Y H:i"); 
            $email_tmpl_data['message'] = $message;
      
            $result = $this->fino_finance_send_mail_client($email_tmpl_data);

            $this->email->clear(true);
        //}  
                               
    }

    public function fino_finance_send_mail_client($email_tmpl_data)
    {
     
        set_time_limit(0);

        $this->load->library('email');

        //$this->email->clear(true);

        $to_emails = $email_tmpl_data['to_emails'];

        $from = $email_tmpl_data['from'];
        $message = $email_tmpl_data['message'];
        $subject = $email_tmpl_data['subject'];

        $to_emails = explode(",", $email_tmpl_data['to_emails']);

        $to_emails =  array_unique($to_emails);

        $to_emails = implode(",", $to_emails);

        $cc_email = explode(",", $email_tmpl_data['cc_emails']);

        $cc_email =  array_unique($cc_email);

        $cc_emails = implode(",", $cc_email);
       
        $config = array(
            'protocol' => 'smtp',
            'smtp_host' => SMTPHOST,
            'smtp_port' => 587,
            'smtp_user' => SMTPUSER,
            'smtp_pass' => SMTPPASSWORD,
            'smtp_timeout' => 30,
            'wordwrap' => true,
            'wrapchars' => 76,
            'mailtype' => 'html',
            'charset' => 'utf-8',
            'validate' => true,
            'priority' => 3,
            'smtp_crypto' => 'tsl',
            'smtp_auto_tls' => false,
            'smtp_conn_options' => array(),
            'smtp_debug' => 0,
            'debug_output' => ''
        
        );

        $this->email->initialize($config);

        $this->email->to($to_emails);

        $this->email->cc($cc_emails);

        $this->email->from($from);

        $this->email->subject($subject);

        $this->email->message($message);

        return $this->email->send();
    }

    public function export_to_excel_courtver()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {
            set_time_limit(0);
            ini_set('memory_limit', '-1');
          
            $all_records = $this->Cron_job_model->get_all_court_vendor_details();

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

            $spreadsheet->getActiveSheet()->getStyle('A1:J1')->applyFromArray($styleArray);

                    // auto fit column to content
            foreach (range('A', 'J') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                            ->setWidth(20);
            }

            $spreadsheet->setActiveSheetIndex(0)

                ->setCellValue("A1", 'Component Ref No')
                ->setCellValue("B1", 'Candidate Name')
                ->setCellValue("C1", 'Date of Birth')
                ->setCellValue("D1", 'Fathers Name')
                ->setCellValue("E1", 'Address')

                ->setCellValue("F1", 'City')
                ->setCellValue("G1", 'Pincode')
                ->setCellValue("H1", 'State')
                ->setCellValue("I1", 'Vendor Status')
                ->setCellValue("J1", 'Vendor Final Status');

                $x = 2;

                foreach ($all_records as $all_record) {

                    $spreadsheet->setActiveSheetIndex(0);

                    $spreadsheet->getActiveSheet()->setCellValue("A$x", $all_record['component_ref_no']);
                    $spreadsheet->getActiveSheet()->setCellValue("B$x", ucwords($all_record['CandidateName']));
                    $spreadsheet->getActiveSheet()->setCellValue("C$x", convert_db_to_display_date($all_record['DateofBirth']));
                    $spreadsheet->getActiveSheet()->setCellValue("D$x",ucwords($all_record['NameofCandidateFather']));
                    $spreadsheet->getActiveSheet()->setCellValue("E$x",$all_record['street_address']);

                    $spreadsheet->getActiveSheet()->setCellValue("F$x", $all_record['city']);
                    $spreadsheet->getActiveSheet()->setCellValue("G$x", $all_record['pincode']);
                    $spreadsheet->getActiveSheet()->setCellValue("H$x", $all_record['state']);
                    $spreadsheet->getActiveSheet()->setCellValue("I$x", ucwords($all_record['vendor_actual_status']));
                    $spreadsheet->getActiveSheet()->setCellValue("J$x", ucwords($all_record['final_status']));
                       
                    $x++;

                }

                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header("Content-Disposition: attachment;filename=Vendor Records of Court.xlsx");
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

                $file_name = "Vendor_" . 'Court_Verification' . ".xls";

                $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Excel2007');
                ob_start();
                $writer->save($file_upload_path . "/" . $file_name);
                ob_end_clean();
                
                $subject = 'Court Verification - Vednor Generate Advocate Form '.date("d-M-Y H:i");

                $message = "<p>Team,</p><p>Please find attached list of pending cases.</p";
                  
                $email_tmpl_data['to_emails'] = $vendor_detail['email_id'];
                $email_tmpl_data['attchment'] = $file_name;
                   // $email_tmpl_data['user_email_id'] = $user_info[0]['email'];
                   // $email_tmpl_data['reporting_email_id'] = $reporting_manager_info[0]['email'];
                $email_tmpl_data['vendor_name'] = $vendor_detail['vendor_name'];
                $email_tmpl_data['message'] = $message;
                $email_tmpl_data['subject'] = $subject;

                   // $email_tmpl_data['email_password'] = base64_decode($user_info[0]['email_password']);

                    //$result = $this->email->vendor_case_send_mail($email_tmpl_data);

                $result = $this->vendor_case_send_mail($email_tmpl_data);


        } else {
            $json_array['message'] = "Something went wrong,please try again";
            $json_array['status'] = ERROR_CODE;
        }

        echo_json($json_array);
    }

    public function component_selection_aq()
    {
     
        $frm_details = $this->input->post();

        $selected_component =  $this->Cron_job_model->selected_aq_component();

        if(empty($selected_component))
        { 
           $frm_array = array('cron_job_component_selection' => implode(',',$frm_details['components']) ,'status' => 1);

           $result = $this->Cron_job_model->save_cron('cron_job_component',$frm_array);
        }
        else{

            $frm_array = array('cron_job_component_selection' => implode(',',$frm_details['components']));
            
            $result = $this->Cron_job_model->save_cron('cron_job_component',$frm_array,array('cron_job_id' => 5));
        }
        
        if($result)
        {
            $json_array['message'] = "Successfully Updated Record";
            $json_array['status'] = SUCCESS_CODE;

        } else {
            $json_array['message'] = "Something went wrong,please try again";
            $json_array['status'] = ERROR_CODE;
        }

        echo_json($json_array);
     
    }


    public function final_qc_selection_aq()
    {
     
        $frm_details = $this->input->post();
        $selected_component =  $this->Cron_job_model->selected_final_qc_aq_component();

        if(empty($selected_component))
        { 
            if(isset($frm_details['final_qc_selection'])) 
            {

                if($frm_details['final_qc_selection'] == "on")
                {

                   $frm_array = array('cron_job_component_selection' => "Yes" ,'status' => 1);

                   $result = $this->Cron_job_model->save_cron('cron_job_component',$frm_array);
                }
 
            }
            else{

               $frm_array = array('cron_job_component_selection' => "No" ,'status' => 1);

               $result = $this->Cron_job_model->save_cron('cron_job_component',$frm_array);
            }
        }
        else{
            
            if(isset($frm_details['final_qc_selection'])) 
            { 
                if( $frm_details['final_qc_selection'] == "on") 
                {
                    $frm_array = array('cron_job_component_selection' => "Yes");
                    
                    $result = $this->Cron_job_model->save_cron('cron_job_component',$frm_array,array('cron_job_id' => 6));
                }
            } else{

                $frm_array = array('cron_job_component_selection' => "No");
                
                $result = $this->Cron_job_model->save_cron('cron_job_component',$frm_array,array('cron_job_id' => 6));
            }
        }
        
        if($result)
        {
            $json_array['message'] = "Successfully Updated Record";
            $json_array['status'] = SUCCESS_CODE;

        } else {
            $json_array['message'] = "Something went wrong,please try again";
            $json_array['status'] = ERROR_CODE;
        }

        echo_json($json_array);
     
    }

}
