<?php defined('BASEPATH') or exit('No direct script access allowed');

class Reports extends MY_Controller
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
        if ($this->input->is_ajax_request()) {
            $this->perm_array = array('direct_access' => true);

        }

        $this->load->model('report_generated_user');
    }

    protected function requested_report_save($fields)
    {
        return $this->report_generated_user->save($fields);
    }

    public function index()
    {
        $data['header_title'] = "Reports";
        
        $data['user_list'] = $this->users_list();
    
        $this->load->view('admin/header', $data);

        $this->load->view('admin/reports_list');

        $this->load->view('admin/footer');
    }

    public function hourly_activity()
    {
        $data['header_title'] = "Reports Hourly Activity";
        
        $data['user_list'] = $this->users_list();
        
        echo $this->load->view('admin/hourly_list_activity', $data, true);

    }

    public function user_cases_activity()
    {
        $data['header_title'] = "User Cases Activity";
        
        $data['user_list'] = $this->users_list();
        
        echo $this->load->view('admin/users_cases_activity', $data, true);

    }

    public function client_allocation()
    {
        $data['header_title'] = "Client Allocation";
        
        $data['clients_list'] = $this->client_list();
        
        echo $this->load->view('admin/client_allocation', $data, true);

    }

    public function vendor_allocation()
    {
        $data['header_title'] = "Vendor Allocation";
        
        $data['vendors_list'] = $this->vendors_list();


        echo $this->load->view('admin/vendor_allocation', $data, true);

    }

    public function aq_component()
    {
        $data['header_title'] = "AQ Component";
        
        $data['components'] = array('1'=>'Address','2'=>"Employment",'3'=>"Education",'4'=>"Reference",'5'=>"Court",'6'=>"Global database",'7'=> "PCC",'8'=>"Identity",'9'=>"Credit Report",'10'=>"Drugs");


        echo $this->load->view('admin/component_count', $data, true);

    }

    protected function client_list()
    {
        $lists = $this->common_model->select('clients', false, array("id","clientname"), array('status' => STATUS_ACTIVE));

        return convert_to_single_dimension_array($lists, 'id', 'clientname');
    }

    protected function vendors_list()
    {
        $lists = $this->common_model->select('vendors', false, array("id","vendor_name","vendors_components"), array('status' => STATUS_ACTIVE));
        
        return $lists;
    }

   
    public function user_activity_details()
    {
        $json_array = array();
        
        $result = '';

        if($this->input->is_ajax_request())
        {

            $params = $_REQUEST;

            $result = $this->report_generated_user->get_user_activity_details($params);

            if($result != '')
            {
        
                $json_array['status'] = SUCCESS_CODE;
                        
                $json_array['message'] = $result;
            }
            else
            {
                $json_array['status'] = ERROR_CODE;

               $json_array['message'] = 'Something went wrong';
            }
        }
        else
        {
            $json_array['status'] = ERROR_CODE;

            $json_array['message'] = 'Something went wrong';
        }
        echo_json($json_array);
    }

    
    public function hourly_activity_details()
    {
        $json_array = array();
        
        $result = '';

        if($this->input->is_ajax_request())
        {

            $params = $_REQUEST;

            $result = $this->report_generated_user->get_hourly_activity_details($params);

            if($result != '')
            {
        
                $json_array['status'] = SUCCESS_CODE;
                        
                $json_array['message'] = $result;
            }
            else
            {
                $json_array['status'] = ERROR_CODE;

               $json_array['message'] = 'Something went wrong';
            }
        }
        else
        {
            $json_array['status'] = ERROR_CODE;

            $json_array['message'] = 'Something went wrong';
        }
        echo_json($json_array);
    }

    public function  user_cases_activity_details()
    {
        $json_array = array();
        
        $result = array();

        if($this->input->is_ajax_request())
        {

            $params = $_REQUEST;

            $result['address'] = $this->report_generated_user->status_count_address($params);
          
            $result['employment'] = $this->report_generated_user->status_count_employment($params);
           
            $result['education'] = $this->report_generated_user->status_count_education($params);
          
            $result['reference'] = $this->report_generated_user->status_count_reference($params);
           
            $result['court'] = $this->report_generated_user->status_count_court($params);
          
            $result['global'] = $this->report_generated_user->status_count_global_database($params);
            
            $result['pcc'] = $this->report_generated_user->status_count_pcc($params);
            
            $result['identity'] = $this->report_generated_user->status_count_identity($params);
            
            $result['credit'] = $this->report_generated_user->status_count_credit_report($params);

            $result['drugs'] = $this->report_generated_user->status_count_drugs($params);
           

            if($result != '')
            {
        
                $json_array['status'] = SUCCESS_CODE;
                        
                $json_array['message'] = $result;
            }
            else
            {
                $json_array['status'] = ERROR_CODE;

               $json_array['message'] = 'Something went wrong';
            }
        }
        else
        {
            $json_array['status'] = ERROR_CODE;

            $json_array['message'] = 'Something went wrong';
        }
        echo_json($json_array);
    }

    public function  client_allocation_details()
    {
        $json_array = array();
        
        $result = array();

        if($this->input->is_ajax_request())
        {

            $params = $_REQUEST;

            $result = $this->report_generated_user->status_count_component($params);
        

            if($result != '')
            {
        
                $json_array['status'] = SUCCESS_CODE;
                        
                $json_array['message'] = $result;
            }
            else
            {
                $json_array['status'] = ERROR_CODE;

               $json_array['message'] = 'Something went wrong';
            }
        }
        else
        {
            $json_array['status'] = ERROR_CODE;

            $json_array['message'] = 'Something went wrong';
        }
        echo_json($json_array);
    }

    public function  vendor_allocation_details()
    {
        $json_array = array();
        
        $result = array();

        if($this->input->is_ajax_request())
        {

            $params = $_REQUEST;

            $result = $this->report_generated_user->status_count_component_vendor( $params);
        

            if($result != '')
            {
        
                $json_array['status'] = SUCCESS_CODE;
                        
                $json_array['message'] = $result;
            }
            else
            {
                $json_array['status'] = ERROR_CODE;

               $json_array['message'] = 'Something went wrong';
            }
        }
        else
        {
            $json_array['status'] = ERROR_CODE;

            $json_array['message'] = 'Something went wrong';
        }
        echo_json($json_array);
    }



    public function candidates()
    {
        $data['header_title'] = "Export Candidates Records";

        $data['status'] = status_frm_db();

        $data['clients'] = $this->get_clients(array('status' => STATUS_ACTIVE));

        $this->load->view('admin/header', $data);

        $this->load->view('admin/candidates_export_filter');

        $this->load->view('admin/footer');
    }

    public function addrver()
    {
        $data['header_title'] = "Export Address Records";

        $data['states'] = $this->get_states();

        $data['assigned_user_id'] = $this->users_list();

        $data['status'] = $this->get_status();

        $data['clients'] = $this->get_clients(array('status' => STATUS_ACTIVE));

        $data['logs'] = $this->report_generated_user->select(false, array('created_on,(select user_name from user_profile where user_profile.id = report_generated_user.created_by) as executive_name'), array('type' => 'Address'));

        $this->load->view('admin/header', $data);

        $this->load->view('admin/export_address_filter');

        $this->load->view('admin/footer');
    }

    public function address_export()
    {
        $json_data = array();

        if ($this->input->is_ajax_request()) {
            $this->load->model('addressver_model');

            $filter = $this->input->post();

            $where_condition_arry = array('clientid' => 'candidates_info.clientid', 'entity' => 'candidates_info.entity', 'package' => 'candidates_info.package', 'overallclosuredate_from' => 'candidates_info.caserecddate', 'overallclosuredate_to' => 'candidates_info.caserecddate', 'address_type' => 'address_type', 'address' => 'addrver.address', 'city' => 'addrver.city', 'pincode' => 'addrver.pincode', 'state' => 'addrver.state', 'has_case_id' => 'addrver.has_case_id', 'verfstatus' => 'addrverres.verfstatus', 'filter_by_sub_status' => 'addrverres.verfstatus', 'mode_of_verification' => 'addrverres.mode_of_verification', 'closuredate_from' => 'addrverres.closuredate', 'closuredate_to' => 'addrverres.closuredate');

            $where = ' where 1 ';

            foreach ($filter as $key => $value) {

                switch ($key) {
                    case 'clientid':
                        if ($filter['clientid'] != 0) {
                            $where .= " and candidates_info.clientid = '" . $filter['clientid'] . "' ";
                        }
                        break;
                    case 'entity':
                        if ($filter['entity'] != 0) {
                            $where .= " and candidates_info.entity = '" . $filter['entity'] . "' ";
                        }
                        break;
                    case 'package':
                        if ($filter['package'] != 0) {
                            $where .= " and candidates_info.package = '" . $filter['package'] . "' ";
                        }
                        break;
                    case 'overallclosuredate_from':
                        if ($filter['overallclosuredate_from'] != '') {
                            $from = ($filter['overallclosuredate_from'] != '') ? convert_display_to_db_date($filter['overallclosuredate_from']) : date(DATE_ONLY);
                            $to = ($filter['overallclosuredate_to'] != '') ? convert_display_to_db_date($filter['overallclosuredate_to']) : '';
                            $to = ($to) ? " '" . $to . "' " : 'CURRENT_DATE()';
                            $where .= " and candidates_info.caserecddate between '" . $from . "' and " . $to . " ";
                        }
                        break;
                    case 'overallclosuredate_to':
                        break;
                    case 'iniated_date_from':
                        if ($filter['iniated_date_from'] != '') {
                            $from = ($filter['iniated_date_from'] != '') ? convert_display_to_db_date($filter['iniated_date_from']) : date(DATE_ONLY);
                            $to = ($filter['iniated_date_to'] != '') ? convert_display_to_db_date($filter['iniated_date_to']) : '';
                            $to = ($to) ? " '" . $to . "' " : 'CURRENT_DATE()';

                            $where .= " and addrver.iniated_date between '" . $from . "' and " . $to . " ";
                        }
                        break;
                    case 'iniated_date_to':
                        break;
                    case 'state':
                        if ($filter['state'] != 0) {
                            $where .= " and addrver.state = '" . $filter['state'] . "' ";
                        }
                        break;
                    case 'has_case_id':
                        if ($filter['has_case_id'] != 0) {
                            $where .= " and addrver.has_case_id = '" . $filter['has_case_id'] . "' ";
                        }
                        break;
                    case 'verfstatus':
                        if ($filter['verfstatus'] != 'Select Status') {
                            $status = $this->sub_status($filter['verfstatus']);
                            $where .= " and addrverres.verfstatus in (" . implode(',', array_keys($status)) . ") ";
                        }
                        break;
                    case 'filter_by_sub_status':
                        if ($filter['filter_by_sub_status'] != 0) {
                            $where .= " and addrverres.verfstatus = '" . $filter['filter_by_sub_status'] . "' ";
                        }
                        break;
                    case 'closuredate_from':
                        if ($filter['closuredate_from'] != '') {
                            $from = ($filter['closuredate_from'] != '') ? convert_display_to_db_date($filter['closuredate_from']) : date(DATE_ONLY);
                            $to = ($filter['closuredate_to'] != '') ? convert_display_to_db_date($filter['closuredate_to']) : '';
                            $to = ($to) ? " '" . $to . "' " : 'CURRENT_DATE()';

                            $where .= " and addrverres.closuredate '" . $from . "' between " . $to . " ";
                        }
                        break;
                    case 'closuredate_to':
                        break;
                    default:
                        if (array_key_exists($key, $where_condition_arry) && $value != '') {
                            $where .= " and  " . $where_condition_arry[$key] . " = '" . $value . "' ";
                        }
                        break;
                }
            }

            $data['lists'] = $this->addressver_model->export_sql($where);

            $report_id = $this->requested_report_save(array('query' => $this->db->last_query(), 'type' => 'Address', 'created_by' => $this->user_info['id'], 'filter' => json_encode($filter), 'created_on' => date(DB_DATE_FORMAT)));
            $data['report_id'] = $report_id;
            $data['assigned_user_id'] = $this->users_list();
            $json_array['message'] = $this->load->view('admin/window_popup', $data, true);

            $json_array['status'] = SUCCESS_CODE;
        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['status'] = "we are sorry but you don't have access to this service";

        }

        echo_json($json_array);
    }

    public function address_export_csv()
    {
        $json_data = array();

        if ($this->input->is_ajax_request()) {
            $this->load->model('addressver_model');

            $filter = $this->input->post();

            $where_condition_arry = array('clientid' => 'candidates_info.clientid', 'entity' => 'candidates_info.entity', 'package' => 'candidates_info.package', 'overallclosuredate_from' => 'candidates_info.caserecddate', 'overallclosuredate_to' => 'candidates_info.caserecddate', 'address_type' => 'address_type', 'address' => 'addrver.address', 'city' => 'addrver.city', 'pincode' => 'addrver.pincode', 'state' => 'addrver.state', 'has_case_id' => 'addrver.has_case_id', 'verfstatus' => 'addrverres.verfstatus', 'filter_by_sub_status' => 'addrverres.verfstatus', 'mode_of_verification' => 'addrverres.mode_of_verification', 'closuredate_from' => 'addrverres.closuredate', 'closuredate_to' => 'addrverres.closuredate');

            $where = ' where 1 ';

            foreach ($filter as $key => $value) {

                switch ($key) {
                    case 'clientid':
                        if ($filter['clientid'] != 0) {
                            $where .= " and candidates_info.clientid = '" . $filter['clientid'] . "' ";
                        }
                        break;
                    case 'entity':
                        if ($filter['entity'] != 0) {
                            $where .= " and candidates_info.entity = '" . $filter['entity'] . "' ";
                        }
                        break;
                    case 'package':
                        if ($filter['package'] != 0) {
                            $where .= " and candidates_info.package = '" . $filter['package'] . "' ";
                        }
                        break;
                    case 'overallclosuredate_from':
                        if ($filter['overallclosuredate_from'] != '') {
                            $from = ($filter['overallclosuredate_from'] != '') ? convert_display_to_db_date($filter['overallclosuredate_from']) : date(DATE_ONLY);
                            $to = ($filter['overallclosuredate_to'] != '') ? convert_display_to_db_date($filter['overallclosuredate_to']) : date(DATE_ONLY);

                            $where .= " and candidates_info.caserecddate >= '" . $from . "' and candidates_info.caserecddate <= '" . $to . "' ";
                        }
                        break;
                    case 'overallclosuredate_to':
                        break;
                    case 'state':
                        if ($filter['state'] != 0) {
                            $where .= " and addrver.state = '" . $filter['state'] . "' ";
                        }
                        break;
                    case 'has_case_id':
                        if ($filter['has_case_id'] != 0) {
                            $where .= " and addrver.has_case_id = '" . $filter['has_case_id'] . "' ";
                        }
                        break;
                    case 'verfstatus':
                        if ($filter['verfstatus'] != 'Select Status') {
                            $status = $this->sub_status($filter['verfstatus']);
                            $where .= " and addrverres.verfstatus in (" . implode(',', array_keys($status)) . ") ";
                        }
                        break;
                    case 'filter_by_sub_status':
                        if ($filter['filter_by_sub_status'] != 0) {
                            $where .= " and addrverres.verfstatus = '" . $filter['filter_by_sub_status'] . "' ";
                        }
                        break;
                    case 'closuredate_from':
                        if ($filter['closuredate_from'] != '') {
                            $from = ($filter['closuredate_from'] != '') ? convert_display_to_db_date($filter['closuredate_from']) : date(DATE_ONLY);
                            $to = ($filter['closuredate_to'] != '') ? convert_display_to_db_date($filter['closuredate_to']) : date(DATE_ONLY);

                            $where .= " and addrverres.closuredate >= '" . $from . "' and addrverres.closuredate <= '" . $to . "' ";
                        }
                        break;
                    case 'closuredate_to':
                        break;
                    default:
                        if (array_key_exists($key, $where_condition_arry) && $value != '') {
                            $where .= " and  " . $where_condition_arry[$key] . " = '" . $value . "' ";
                        }
                        break;
                }
            }

            $result = $this->addressver_model->export_sql($where);

            require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
            // Create new Spreadsheet object
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            // Set document properties
            $spreadsheet->getProperties()->setCreator('BGV')
                ->setLastModifiedBy('BGV')
                ->setTitle('BGV')
                ->setSubject('Address Export Report')
                ->setDescription('Address Export');

            foreach (range('A', 'AI') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                    ->setWidth(20);
            }

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A1", 'Sr No.')
                ->setCellValue("B1", 'Client Name')
                ->setCellValue("C1", 'Entity')
                ->setCellValue("D1", 'Package')
                ->setCellValue("E1", 'Client Ref No')
                ->setCellValue("F1", REFNO)
                ->setCellValue("G1", 'Candidate Name')
                ->setCellValue("H1", 'Received Date')
                ->setCellValue("I1", 'Component Ref No')
                ->setCellValue("J1", 'Component Initiation Date')
                ->setCellValue("K1", 'Vendor')
                ->setCellValue("L1", 'Stay From')
                ->setCellValue("M1", 'Stay To')
                ->setCellValue("N1", 'Address type')
                ->setCellValue("O1", 'Street Address')
                ->setCellValue("P1", 'City')
                ->setCellValue("Q1", 'Pincode')
                ->setCellValue("R1", 'State')
                ->setCellValue("S1", 'Executive Name')
                ->setCellValue("T1", 'Mode of verification')
                ->setCellValue("U1", 'Resident Status')
                ->setCellValue("V1", 'Landmark')
                ->setCellValue("W1", 'Neighbour 1')
                ->setCellValue("X1", 'Neighbour Details 1')
                ->setCellValue("Y1", 'Neighbour 2')
                ->setCellValue("Z1", 'Neighbour Details 2')
                ->setCellValue("AA1", 'Verified By')
                ->setCellValue("AB1", 'Addr. Proof Collected')
                ->setCellValue("AC1", 'verification Status')
                ->setCellValue("AD1", 'Remarks')
                ->setCellValue("AE1", 'QC Status')
                ->setCellValue("AF1", 'TAT Status')
                ->setCellValue("AG1", 'Check Closure Date')
                ->setCellValue("AH1", 'Last Activity');
            $x = 2;

            foreach ($result as $key => $value) {
                $value = array_map('ucwords', $value);
                $value = array_map(function ($value) {return $value == "" ? 'NA' : $value;}, $value);
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A$x", ($x - 1))
                    ->setCellValue("B$x", $value['client_name'])
                    ->setCellValue("C$x", $value['entity_name'])
                    ->setCellValue("D$x", $value['package_name'])
                    ->setCellValue("E$x", $value['ClientRefNumber'])
                    ->setCellValue("F$x", $value['cmp_ref_no'])
                    ->setCellValue("G$x", $value['CandidateName'])
                    ->setCellValue("H$x", $value['caserecddate'])
                    ->setCellValue("I$x", $value['add_com_ref'])
                    ->setCellValue("J$x", $value['iniated_date'])
                    ->setCellValue("K$x", $value['vendor_id'])
                    ->setCellValue("L$x", $value['stay_from'])
                    ->setCellValue("M$x", $value['stay_to'])
                    ->setCellValue("N$x", $value['address_type'])
                    ->setCellValue("O$x", $value['address'])
                    ->setCellValue("P$x", $value['city'])
                    ->setCellValue("Q$x", $value['pincode'])
                    ->setCellValue("R$x", $value['state'])
                    ->setCellValue("S$x", $value['executive_name'])
                    ->setCellValue("T$x", $value['mode_of_verification'])
                    ->setCellValue("U$x", $value['resident_status'])
                    ->setCellValue("V$x", $value['landmark'])
                    ->setCellValue("W$x", $value['neighbour_1'])
                    ->setCellValue("X$x", $value['neighbour_details_1'])
                    ->setCellValue("Y$x", $value['neighbour_2'])
                    ->setCellValue("Z$x", $value['neighbour_details_2'])
                    ->setCellValue("AA$x", $value['verified_by'])
                    ->setCellValue("AB$x", $value['addr_proof_collected'])
                    ->setCellValue("AC$x", $value['verfstatus'])
                    ->setCellValue("AD$x", $value['remarks'])
                    ->setCellValue("AE$x", $value['first_qc_approve'])
                    ->setCellValue("AF$x", $value['tat_status'])
                    ->setCellValue("AG$x", $value['closuredate'])
                    ->setCellValue("AH$x", $value['last_activity_date']);
                $x++;
            }
            $spreadsheet->getActiveSheet()->setTitle('Candidate Records');
            $spreadsheet->setActiveSheetIndex(0);

            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment;filename=Candidates Bulk Uplaod Template.xlsx");
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

            $json_array['file_name'] = "Export-Address-" . date(DISPLAY_DATE_FORMAT12) . ".xls";

            $json_array['message'] = "File downloaded successfully,please check in download folder";

            $json_array['status'] = SUCCESS_CODE;
        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['status'] = "we are sorry but you don't have access to this service";

        }

        echo_json($json_array);
    }

    public function empver()
    {
        $data['header_title'] = "Export Employment Records";

        $data['states'] = $this->get_states();

        $data['logs'] = $this->report_generated_user->select(false, array('created_on,(select user_name from user_profile where user_profile.id = report_generated_user.created_by) as executive_name'), array('type' => 'Employment'));

        $data['assigned_user_id'] = $this->users_list();

        $data['status'] = $this->get_status();

        $data['company_list'] = $this->get_company_list();

        $data['clients'] = $this->get_clients(array('status' => STATUS_ACTIVE));

        $this->load->view('admin/header', $data);

        $this->load->view('admin/export_employment_filter');

        $this->load->view('admin/footer');
    }

    public function employment_export()
    {
        $json_data = array();

        if ($this->input->is_ajax_request()) {
            $this->load->model('employment_model');

            $filter = $this->input->post();

            $where_condition_arry = array('clientid' => 'candidates_info.clientid', 'entity' => 'candidates_info.entity', 'package' => 'candidates_info.package', 'overallclosuredate_from' => 'candidates_info.caserecddate', 'overallclosuredate_to' => 'candidates_info.caserecddate', 'locationaddr' => 'empver.locationaddr', 'citylocality' => 'empver.citylocality', 'pincode' => 'empver.pincode', 'state' => 'empver.state', 'has_case_id' => 'empver.has_case_id', 'verfstatus' => 'empverres.verfstatus', 'filter_by_sub_status' => 'empverres.verfstatus', 'modeofverification' => 'empverres.modeofverification', 'closuredate_from' => 'empverres.closuredate', 'closuredate_to' => 'empverres.closuredate');

            $where = ' where 1 ';

            foreach ($filter as $key => $value) {

                switch ($key) {
                    case 'clientid':
                        if ($filter['clientid'] != 0) {
                            $where .= " and candidates_info.clientid = '" . $filter['clientid'] . "' ";
                        }
                        break;
                    case 'entity':
                        if ($filter['entity'] != 0) {
                            $where .= " and candidates_info.entity = '" . $filter['entity'] . "' ";
                        }
                        break;
                    case 'package':
                        if ($filter['package'] != 0) {
                            $where .= " and candidates_info.package = '" . $filter['package'] . "' ";
                        }
                        break;
                    case 'overallclosuredate_from':
                        if ($filter['overallclosuredate_from'] != '') {
                            $from = ($filter['overallclosuredate_from'] != '') ? convert_display_to_db_date($filter['overallclosuredate_from']) : date(DATE_ONLY);
                            $to = ($filter['overallclosuredate_to'] != '') ? convert_display_to_db_date($filter['overallclosuredate_to']) : '';
                            $to = ($to) ? " '" . $to . "' " : 'CURRENT_DATE()';
                            $where .= " and candidates_info.caserecddate between '" . $from . "' and " . $to . " ";
                        }
                        break;
                    case 'modeofverification':
                        if ($filter['modeofverification'] != 0) {
                            $where .= " and modeofverification = '" . $filter['modeofverification'] . "' ";
                        }
                        break;
                    case 'overallclosuredate_to':
                        break;
                    case 'iniated_date_from':
                        if ($filter['iniated_date_from'] != '') {
                            $from = ($filter['iniated_date_from'] != '') ? convert_display_to_db_date($filter['iniated_date_from']) : date(DATE_ONLY);
                            $to = ($filter['iniated_date_to'] != '') ? convert_display_to_db_date($filter['iniated_date_to']) : '';
                            $to = ($to) ? " '" . $to . "' " : 'CURRENT_DATE()';

                            $where .= " and empver.iniated_date between '" . $from . "' and " . $to . " ";
                        }
                        break;
                    case 'iniated_date_to':
                        break;
                    case 'state':
                        if ($filter['state'] != 0) {
                            $where .= " and empver.state = '" . $filter['state'] . "' ";
                        }
                        break;
                    case 'has_case_id':
                        if ($filter['has_case_id'] != 0) {
                            $where .= " and empver.has_case_id = '" . $filter['has_case_id'] . "' ";
                        }
                        break;
                    case 'verfstatus':
                        if ($filter['verfstatus'] != 'Select Status') {
                            $status = $this->sub_status($filter['verfstatus']);
                            $where .= " and empverres.verfstatus in (" . implode(',', array_keys($status)) . ") ";
                        }
                        break;
                    case 'filter_by_sub_status':
                        if ($filter['filter_by_sub_status'] != 0) {
                            $where .= " and empverres.verfstatus = '" . $filter['filter_by_sub_status'] . "' ";
                        }
                        break;
                    case 'closuredate_from':
                        if ($filter['closuredate_from'] != '') {
                            $from = ($filter['closuredate_from'] != '') ? convert_display_to_db_date($filter['closuredate_from']) : date(DATE_ONLY);
                            $to = ($filter['closuredate_to'] != '') ? convert_display_to_db_date($filter['closuredate_to']) : '';
                            $to = ($to) ? " '" . $to . "' " : 'CURRENT_DATE()';

                            $where .= " and empverres.closuredate '" . $from . "' between " . $to . " ";
                        }
                        break;
                    case 'closuredate_to':
                        break;
                    default:
                        if (array_key_exists($key, $where_condition_arry) && $value != '') {
                            $where .= " and  " . $where_condition_arry[$key] . " = '" . $value . "' ";
                        }
                        break;
                }
            }

            $data['lists'] = $this->employment_model->export_sql($where);

            $report_id = $this->requested_report_save(array('query' => $this->db->last_query(), 'type' => 'Employment', 'created_by' => $this->user_info['id'], 'filter' => json_encode($filter), 'created_on' => date(DB_DATE_FORMAT)));

            $data['report_id'] = $report_id;

            $data['assigned_user_id'] = $this->users_list();

            $json_array['message'] = $this->load->view('admin/window_popup_employment', $data, true);

            $json_array['status'] = SUCCESS_CODE;
        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['status'] = "we are sorry but you don't have access to this service";

        }

        echo_json($json_array);
    }

    public function employment_export_csv()
    {
        $json_data = array();

        if ($this->input->is_ajax_request()) {
            $this->load->model('employment_model');

            $filter = $this->input->post();

            $result = $this->employment_model->export_sql($filter);

            require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
            // Create new Spreadsheet object
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            // Set document properties
            $spreadsheet->getProperties()->setCreator('BGV')
                ->setLastModifiedBy('BGV')
                ->setTitle('BGV')
                ->setSubject('Address Export Report')
                ->setDescription('Address Export');

            foreach (range('A', 'BG') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                    ->setWidth(20);
            }

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A1", 'Sr No.')
                ->setCellValue("B1", 'Client Name')
                ->setCellValue("C1", 'Entity')
                ->setCellValue("D1", 'Package')
                ->setCellValue("E1", 'Client Ref No')
                ->setCellValue("F1", REFNO)
                ->setCellValue("G1", 'Candidate Name')
                ->setCellValue("H1", 'Received Date')
                ->setCellValue("I1", 'Component Ref No')
                ->setCellValue("J1", 'Component Initiation Date')
                ->setCellValue("K1", 'Company Name')
                ->setCellValue("L1", 'Deputed Company')
                ->setCellValue("M1", 'Previous Employee Code')
                ->setCellValue("N1", 'Employment Type')
                ->setCellValue("O1", 'Employed From')
                ->setCellValue("P1", 'Employed To')
                ->setCellValue("Q1", 'Designation')
                ->setCellValue("R1", 'Remuneration')
                ->setCellValue("S1", 'Reason for Leaving')
                ->setCellValue("T1", 'Company Contact No')
                ->setCellValue("U1", 'Company Contact Name')
                ->setCellValue("V1", 'Company Contact Designation')
                ->setCellValue("W1", 'Street Address')
                ->setCellValue("X1", 'City')
                ->setCellValue("Y1", 'State')
                ->setCellValue("Z1", 'Pincode')
                ->setCellValue("AA1", 'Manager Name')
                ->setCellValue("AB1", "Manager's contact")
                ->setCellValue("AC1", 'Designation')
                ->setCellValue("AD1", "Manager's Email ID")
                ->setCellValue("AE1", 'Supervisor Name')
                ->setCellValue("AF1", "Supervisor's contact")
                ->setCellValue("AG1", 'Designation')
                ->setCellValue("AH1", "Supervisor's Email ID")
                ->setCellValue("AI1", 'Supervisor Name 2')
                ->setCellValue("AJ1", "Supervisor's contact 2")
                ->setCellValue("AK1", 'Designation 2')
                ->setCellValue("AL1", "Supervisor's Email ID 2")
                ->setCellValue("AM1", 'Integrity/Disciplinary Issues')
                ->setCellValue("AN1", 'Exit Formalities Completed?')
                ->setCellValue("AO1", 'Eligible for Rehire?')
                ->setCellValue("AP1", 'Family Owned?')
                ->setCellValue("AQ1", 'Web check status')
                ->setCellValue("AR1", 'Registered with MCA?')
                ->setCellValue("AS1", 'Domain Name')
                ->setCellValue("AT1", 'Domain Purchased')
                ->setCellValue("AU1", 'Mode of verification')
                ->setCellValue("AV1", 'Remarks')
                ->setCellValue("AW1", 'Verifiers Role')
                ->setCellValue("AX1", "Verifiers Name")
                ->setCellValue("AY1", 'Verifiers Contact No')
                ->setCellValue("AZ1", 'Verifiers Email ID')
                ->setCellValue("BA1", 'Verifiers Designation')
                ->setCellValue("BB1", 'Executive Name')
                ->setCellValue("BC1", 'Verification Status')
                ->setCellValue("BD1", 'QC Status')
                ->setCellValue("BE1", 'TAT Status')
                ->setCellValue("BF1", 'Check Closure Date')
                ->setCellValue("BG1", 'Last Activity');
            $x = 2;

            foreach ($result as $key => $value) {
                $value = array_map('ucwords', $value);
                $value = array_map(function ($value) {return $value == "" ? 'NA' : $value;}, $value);
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A$x", ($x - 1))
                    ->setCellValue("B$x", $value['client_name'])
                    ->setCellValue("C$x", $value['entity_name'])
                    ->setCellValue("D$x", $value['package_name'])
                    ->setCellValue("E$x", $value['ClientRefNumber'])
                    ->setCellValue("F$x", $value['cmp_ref_no'])
                    ->setCellValue("G$x", $value['CandidateName'])
                    ->setCellValue("H$x", $value['caserecddate'])
                    ->setCellValue("I$x", $value['emp_com_ref'])
                    ->setCellValue("J$x", $value['iniated_date'])
                    ->setCellValue("K$x", $value['coname'])
                    ->setCellValue("L$x", $value['deputed_company'])
                    ->setCellValue("M$x", $value['empid'])
                    ->setCellValue("N$x", $value['employment_type'])
                    ->setCellValue("O$x", $value['empfrom'])
                    ->setCellValue("P$x", $value['empto'])
                    ->setCellValue("Q$x", $value['designation'])
                    ->setCellValue("R$x", $value['remuneration'])
                    ->setCellValue("S$x", $value['reasonforleaving'])
                    ->setCellValue("T$x", $value['compant_contact'])
                    ->setCellValue("U$x", $value['compant_contact_name'])
                    ->setCellValue("V$x", $value['compant_contact_designation'])
                    ->setCellValue("W$x", $value['locationaddr'])
                    ->setCellValue("X$x", $value['citylocality'])
                    ->setCellValue("Y$x", $value['state'])
                    ->setCellValue("Z$x", $value['pincode'])
                    ->setCellValue("AA$x", $value['r_manager_name'])
                    ->setCellValue("AB$x", $value['r_manager_no'])
                    ->setCellValue("AC$x", $value['r_manager_designation'])
                    ->setCellValue("AD$x", $value['r_manager_email'])
                    ->setCellValue("AE$x", '')
                    ->setCellValue("AF$x", '')
                    ->setCellValue("AG$x", '')
                    ->setCellValue("AH$x", '')
                    ->setCellValue("AI$x", '')
                    ->setCellValue("AJ$x", '')
                    ->setCellValue("AK$x", '')
                    ->setCellValue("AL$x", '')
                    ->setCellValue("AM$x", $value['integrity_disciplinary_issue'])
                    ->setCellValue("AM$x", $value['exitformalities'])
                    ->setCellValue("AO$x", $value['eligforrehire'])
                    ->setCellValue("AP$x", $value['fmlyowned'])
                    ->setCellValue("AQ$x", $value['justdialwebcheck'])
                    ->setCellValue("AR$x", $value['mcaregn'])
                    ->setCellValue("AS$x", $value['domainname'])
                    ->setCellValue("AT$x", $value['domainpurch'])
                    ->setCellValue("AU$x", $value['modeofverification'])
                    ->setCellValue("AV$x", $value['remarks'])
                    ->setCellValue("AW$x", $value['verifiers_role'])
                    ->setCellValue("AX$x", $value['verfname'])
                    ->setCellValue("AY$x", $value['verifiers_contact_no'])
                    ->setCellValue("AZ$x", $value['verifiers_email_id'])
                    ->setCellValue("BA$x", $value['verfdesgn'])
                    ->setCellValue("BB$x", $value['executive_name'])
                    ->setCellValue("BC$x", $value['verfstatus'])
                    ->setCellValue("BD$x", $value['first_qc_approve'])
                    ->setCellValue("BE$x", $value['tat_status'])
                    ->setCellValue("BF$x", $value['closuredate'])
                    ->setCellValue("BG$x", $value['last_activity_date']);
                $x++;
            }
            $spreadsheet->getActiveSheet()->setTitle('Address Records');
            $spreadsheet->setActiveSheetIndex(0);

            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment;filename=Candidates Bulk Uplaod Template.xlsx");
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

            $json_array['file_name'] = "Export-Employment-" . date(DISPLAY_DATE_FORMAT12) . ".xls";

            $json_array['message'] = "File downloaded successfully,please check in download folder";

            $json_array['status'] = SUCCESS_CODE;
        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['status'] = "we are sorry but you don't have access to this service";

        }

        echo_json($json_array);
    }

    public function eduver()
    {
        $data['header_title'] = "Export Education Records";

        $data['states'] = $this->get_states();

        $data['logs'] = $this->report_generated_user->select(false, array('created_on,(select user_name from user_profile where user_profile.id = report_generated_user.created_by) as executive_name'), array('type' => 'Education'));

        $data['assigned_user_id'] = $this->users_list();

        $data['status'] = $this->get_status();

        $data['company_list'] = $this->get_company_list();

        $data['clients'] = $this->get_clients(array('status' => STATUS_ACTIVE));

        $this->load->view('admin/header', $data);

        $this->load->view('admin/export_education_filter');

        $this->load->view('admin/footer');
    }

    public function education_export()
    {
        $json_data = array();

        if ($this->input->is_ajax_request()) {
            $this->load->model('education_model');

            $filter = $this->input->post();

            $where_condition_arry = array('clientid' => 'candidates_info.clientid', 'entity' => 'candidates_info.entity', 'package' => 'candidates_info.package', 'overallclosuredate_from' => 'candidates_info.caserecddate', 'overallclosuredate_to' => 'candidates_info.caserecddate', 'university_board' => 'education.university_board', 'qualification' => 'education.qualification', 'has_case_id' => 'education.has_case_id', 'verfstatus' => 'education_result.verfstatus', 'filter_by_sub_status' => 'education_result.verfstatus', 'res_mode_of_verification' => 'education_result.res_mode_of_verification', 'closuredate_from' => 'education_result.closuredate', 'closuredate_to' => 'education_result.closuredate');

            $where = ' where 1 ';

            foreach ($filter as $key => $value) {

                switch ($key) {
                    case 'clientid':
                        if ($filter['clientid'] != 0) {
                            $where .= " and candidates_info.clientid = '" . $filter['clientid'] . "' ";
                        }
                        break;
                    case 'entity':
                        if ($filter['entity'] != 0) {
                            $where .= " and candidates_info.entity = '" . $filter['entity'] . "' ";
                        }
                        break;
                    case 'package':
                        if ($filter['package'] != 0) {
                            $where .= " and candidates_info.package = '" . $filter['package'] . "' ";
                        }
                        break;
                    case 'overallclosuredate_from':
                        if ($filter['overallclosuredate_from'] != '') {
                            $from = ($filter['overallclosuredate_from'] != '') ? convert_display_to_db_date($filter['overallclosuredate_from']) : date(DATE_ONLY);
                            $to = ($filter['overallclosuredate_to'] != '') ? convert_display_to_db_date($filter['overallclosuredate_to']) : '';
                            $to = ($to) ? " '" . $to . "' " : 'CURRENT_DATE()';
                            $where .= " and candidates_info.caserecddate between '" . $from . "' and " . $to . " ";
                        }
                        break;
                    case 'overallclosuredate_to':
                        break;
                    case 'iniated_date_from':
                        if ($filter['iniated_date_from'] != '') {
                            $from = ($filter['iniated_date_from'] != '') ? convert_display_to_db_date($filter['iniated_date_from']) : date(DATE_ONLY);
                            $to = ($filter['iniated_date_to'] != '') ? convert_display_to_db_date($filter['iniated_date_to']) : '';
                            $to = ($to) ? " '" . $to . "' " : 'CURRENT_DATE()';

                            $where .= " and education.iniated_date between '" . $from . "' and " . $to . " ";
                        }
                        break;
                    case 'iniated_date_to':
                        break;
                    case 'university_board':
                        if ($filter['university_board'] != 0) {
                            $where .= " and education.university_board = '" . $filter['university_board'] . "' ";
                        }
                        break;
                    case 'qualification':
                        if ($filter['qualification'] != 0) {
                            $where .= " and education.qualification = '" . $filter['qualification'] . "' ";
                        }
                        break;
                    case 'has_case_id':
                        if ($filter['has_case_id'] != 0) {
                            $where .= " and education.has_case_id = '" . $filter['has_case_id'] . "' ";
                        }
                        break;
                    case 'verfstatus':
                        if ($filter['verfstatus'] != 'Select Status') {
                            $status = $this->sub_status($filter['verfstatus']);
                            $where .= " and education_result.verfstatus in (" . implode(',', array_keys($status)) . ") ";
                        }
                        break;
                    case 'filter_by_sub_status':
                        if ($filter['filter_by_sub_status'] != 0) {
                            $where .= " and education_result.verfstatus = '" . $filter['filter_by_sub_status'] . "' ";
                        }
                        break;
                    case 'closuredate_from':
                        if ($filter['closuredate_from'] != '') {
                            $from = ($filter['closuredate_from'] != '') ? convert_display_to_db_date($filter['closuredate_from']) : date(DATE_ONLY);
                            $to = ($filter['closuredate_to'] != '') ? convert_display_to_db_date($filter['closuredate_to']) : '';
                            $to = ($to) ? " '" . $to . "' " : 'CURRENT_DATE()';

                            $where .= " and education_result.closuredate '" . $from . "' between " . $to . " ";
                        }
                        break;
                    case 'closuredate_to':
                        break;
                    default:
                        if (array_key_exists($key, $where_condition_arry) && $value != '') {
                            $where .= " and  " . $where_condition_arry[$key] . " = '" . $value . "' ";
                        }
                        break;
                }
            }

            $data['lists'] = $this->education_model->export_sql($where);

            $report_id = $this->requested_report_save(array('query' => $this->db->last_query(), 'type' => 'Employment', 'created_by' => $this->user_info['id'], 'filter' => json_encode($filter), 'created_on' => date(DB_DATE_FORMAT)));

            $data['report_id'] = $report_id;

            $data['assigned_user_id'] = $this->users_list();

            $json_array['message'] = $this->load->view('admin/window_popup_education', $data, true);

            $json_array['status'] = SUCCESS_CODE;
        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['status'] = "we are sorry but you don't have access to this service";

        }

        echo_json($json_array);
    }

    public function education_export_csv()
    {
        $json_data = array();

        if ($this->input->is_ajax_request()) {
            $this->load->model('education_model');

            $filter = $this->input->post();

            $result = $this->education_model->export_sql($filter);

            require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
            // Create new Spreadsheet object
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            // Set document properties
            $spreadsheet->getProperties()->setCreator('BGV')
                ->setLastModifiedBy('BGV')
                ->setTitle('BGV')
                ->setSubject('Education Export Report')
                ->setDescription('Education Export');

            foreach (range('A', 'AL') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                    ->setWidth(20);
            }

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A1", 'Sr No.')
                ->setCellValue("B1", 'Client Name')
                ->setCellValue("C1", 'Entity')
                ->setCellValue("D1", 'Package')
                ->setCellValue("E1", 'Client Ref No')
                ->setCellValue("F1", REFNO)
                ->setCellValue("G1", 'Candidate Name')
                ->setCellValue("H1", 'Received Date')
                ->setCellValue("I1", 'Component Ref No')
                ->setCellValue("J1", 'Component Initiation Date')
                ->setCellValue("K1", 'School/College')
                ->setCellValue("L1", 'University/Board')
                ->setCellValue("M1", 'Grade/Class/Marks')
                ->setCellValue("N1", 'Qualification')
                ->setCellValue("O1", 'Major')
                ->setCellValue("P1", 'Course Start Date')
                ->setCellValue("Q1", 'Course End Date')
                ->setCellValue("R1", 'Month of Passing')
                ->setCellValue("S1", 'Year of Passing')
                ->setCellValue("T1", 'Roll No')
                ->setCellValue("U1", 'Enrollment No')
                ->setCellValue("V1", 'PRN Number')
                ->setCellValue("W1", 'Documents Provided')
                ->setCellValue("X1", 'Executive Name')
                ->setCellValue("Y1", 'City')
                ->setCellValue("Z1", 'State')
                ->setCellValue("AA1", 'Genuineness')
                ->setCellValue("AB1", 'Online URL')
                ->setCellValue("AC1", 'Mode of verification')
                ->setCellValue("AD1", 'Verified By')
                ->setCellValue("AE1", 'Verifier Designation')
                ->setCellValue("AF1", "Verifier's Contact Details")
                ->setCellValue("AG1", 'Verification Status')
                ->setCellValue("AH1", 'Remarks')
                ->setCellValue("AI1", 'QC Status')
                ->setCellValue("AJ1", 'TAT Status')
                ->setCellValue("AK1", 'Check Closure Date')
                ->setCellValue("AL1", 'Last Activity');
            $x = 2;

            foreach ($result as $key => $value) {
                $value = array_map('ucwords', $value);
                $value = array_map(function ($value) {return $value == "" ? 'NA' : $value;}, $value);
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A$x", ($x - 1))
                    ->setCellValue("B$x", $value['client_name'])
                    ->setCellValue("C$x", $value['entity_name'])
                    ->setCellValue("D$x", $value['package_name'])
                    ->setCellValue("E$x", $value['ClientRefNumber'])
                    ->setCellValue("F$x", $value['cmp_ref_no'])
                    ->setCellValue("G$x", $value['CandidateName'])
                    ->setCellValue("H$x", $value['caserecddate'])
                    ->setCellValue("I$x", $value['education_com_ref'])
                    ->setCellValue("J$x", $value['iniated_date'])
                    ->setCellValue("K$x", $value['school_college'])
                    ->setCellValue("L$x", $value['university_board'])
                    ->setCellValue("M$x", $value['grade_class_marks'])
                    ->setCellValue("N$x", $value['qualification'])
                    ->setCellValue("O$x", $value['major'])
                    ->setCellValue("P$x", $value['course_start_date'])
                    ->setCellValue("Q$x", $value['course_end_date'])
                    ->setCellValue("R$x", $value['month_of_passing'])
                    ->setCellValue("S$x", $value['year_of_passing'])
                    ->setCellValue("T$x", $value['roll_no'])
                    ->setCellValue("U$x", $value['enrollment_no'])
                    ->setCellValue("V$x", $value['PRN_no'])
                    ->setCellValue("W$x", $value['documents_provided'])
                    ->setCellValue("X$x", $value['executive_name'])
                    ->setCellValue("Y$x", $value['city'])
                    ->setCellValue("Z$x", $value['state'])
                    ->setCellValue("AA$x", $value['genuineness'])
                    ->setCellValue("AB$x", $value['online_URL'])
                    ->setCellValue("AC$x", $value['res_mode_of_verification'])
                    ->setCellValue("AD$x", $value['verified_by'])
                    ->setCellValue("AE$x", $value['verifier_designation'])
                    ->setCellValue("AF$x", $value['verifier_contact_details'])
                    ->setCellValue("AG$x", $value['verfstatus'])
                    ->setCellValue("AH$x", $value['remarks'])
                    ->setCellValue("AI$x", $value['first_qc_approve'])
                    ->setCellValue("AJ$x", $value['tat_status'])
                    ->setCellValue("AK$x", $value['closuredate'])
                    ->setCellValue("AL$x", $value['last_activity_date']);
                $x++;
            }
            $spreadsheet->getActiveSheet()->setTitle('Candidate Records');
            $spreadsheet->setActiveSheetIndex(0);

            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment;filename=Candidates Bulk Uplaod Template.xlsx");
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

            $json_array['file_name'] = "Export-Education-" . date(DISPLAY_DATE_FORMAT12) . ".xls";

            $json_array['message'] = "File downloaded successfully,please check in download folder";

            $json_array['status'] = SUCCESS_CODE;
        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['status'] = "we are sorry but you don't have access to this service";

        }
        echo_json($json_array);
    }

    public function refver()
    {
        $data['header_title'] = "Export Reference Records";

        $data['status'] = status_frm_db();

        $data['assigned_user_id'] = $this->users_list();

        $data['clients'] = $this->get_clients(array('status' => STATUS_ACTIVE));

        $data['logs'] = $this->report_generated_user->select(false, array('created_on,(select user_name from user_profile where user_profile.id = report_generated_user.created_by) as executive_name'), array('type' => 'Reference'));

        $this->load->view('admin/header', $data);

        $this->load->view('admin/export_reference_filter');

        $this->load->view('admin/footer');
    }

    public function reference_export()
    {
        $json_data = array();

        if ($this->input->is_ajax_request()) {
            $this->load->model('reference_verificatiion_model');

            $filter = $this->input->post();

            $where_condition_arry = array('clientid' => 'candidates_info.clientid', 'entity' => 'candidates_info.entity', 'package' => 'candidates_info.package', 'overallclosuredate_from' => 'candidates_info.caserecddate', 'overallclosuredate_to' => 'candidates_info.caserecddate', 'has_case_id' => 'reference.has_case_id', 'verfstatus' => 'reference_result.verfstatus', 'filter_by_sub_status' => 'reference_result.verfstatus', 'closuredate_from' => 'reference_result.closuredate', 'closuredate_to' => 'reference_result.closuredate');

            $where = ' where 1 ';

            foreach ($filter as $key => $value) {

                switch ($key) {
                    case 'clientid':
                        if ($filter['clientid'] != 0) {
                            $where .= " and candidates_info.clientid = '" . $filter['clientid'] . "' ";
                        }
                        break;
                    case 'entity':
                        if ($filter['entity'] != 0) {
                            $where .= " and candidates_info.entity = '" . $filter['entity'] . "' ";
                        }
                        break;
                    case 'package':
                        if ($filter['package'] != 0) {
                            $where .= " and candidates_info.package = '" . $filter['package'] . "' ";
                        }
                        break;
                    case 'overallclosuredate_from':
                        if ($filter['overallclosuredate_from'] != '') {
                            $from = ($filter['overallclosuredate_from'] != '') ? convert_display_to_db_date($filter['overallclosuredate_from']) : date(DATE_ONLY);
                            $to = ($filter['overallclosuredate_to'] != '') ? convert_display_to_db_date($filter['overallclosuredate_to']) : '';
                            $to = ($to) ? " '" . $to . "' " : 'CURRENT_DATE()';
                            $where .= " and candidates_info.caserecddate between '" . $from . "' and " . $to . " ";
                        }
                        break;
                    case 'overallclosuredate_to':
                        break;
                    case 'iniated_date_from':
                        if ($filter['iniated_date_from'] != '') {
                            $from = ($filter['iniated_date_from'] != '') ? convert_display_to_db_date($filter['iniated_date_from']) : date(DATE_ONLY);
                            $to = ($filter['iniated_date_to'] != '') ? convert_display_to_db_date($filter['iniated_date_to']) : '';
                            $to = ($to) ? " '" . $to . "' " : 'CURRENT_DATE()';

                            $where .= " and reference.iniated_date between '" . $from . "' and " . $to . " ";
                        }
                        break;
                    case 'iniated_date_to':
                        break;
                    case 'has_case_id':
                        if ($filter['has_case_id'] != 0) {
                            $where .= " and reference.has_case_id = '" . $filter['has_case_id'] . "' ";
                        }
                        break;
                    case 'verfstatus':
                        if ($filter['verfstatus'] != 'Select Status') {
                            $status = $this->sub_status($filter['verfstatus']);
                            $where .= " and reference_result.verfstatus in (" . implode(',', array_keys($status)) . ") ";
                        }
                        break;
                    case 'filter_by_sub_status':
                        if ($filter['filter_by_sub_status'] != 0) {
                            $where .= " and reference_result.verfstatus = '" . $filter['filter_by_sub_status'] . "' ";
                        }
                        break;
                    case 'closuredate_from':
                        if ($filter['closuredate_from'] != '') {
                            $from = ($filter['closuredate_from'] != '') ? convert_display_to_db_date($filter['closuredate_from']) : date(DATE_ONLY);
                            $to = ($filter['closuredate_to'] != '') ? convert_display_to_db_date($filter['closuredate_to']) : '';
                            $to = ($to) ? " '" . $to . "' " : 'CURRENT_DATE()';

                            $where .= " and reference_result.closuredate '" . $from . "' between " . $to . " ";
                        }
                        break;
                    case 'closuredate_to':
                        break;
                    default:
                        if (array_key_exists($key, $where_condition_arry) && $value != '') {
                            $where .= " and  " . $where_condition_arry[$key] . " = '" . $value . "' ";
                        }
                        break;
                }
            }

            $data['lists'] = $this->reference_verificatiion_model->export_sql($where);

            $report_id = $this->requested_report_save(array('query' => $this->db->last_query(), 'type' => 'Reference', 'created_by' => $this->user_info['id'], 'filter' => json_encode($filter), 'created_on' => date(DB_DATE_FORMAT)));

            $data['report_id'] = $report_id;

            $data['assigned_user_id'] = $this->users_list();

            $json_array['message'] = $this->load->view('admin/window_popup_reference', $data, true);

            $json_array['status'] = SUCCESS_CODE;
        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['status'] = "we are sorry but you don't have access to this service";

        }

        echo_json($json_array);
    }

    public function reference_export_csv()
    {
        $json_data = array();

        if ($this->input->is_ajax_request()) {
            $this->load->model('reference_verificatiion_model');

            $filter = $this->input->post();

            $result = $this->reference_verificatiion_model->export_sql($filter);

            require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
            // Create new Spreadsheet object
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            // Set document properties
            $spreadsheet->getProperties()->setCreator('BGV')
                ->setLastModifiedBy('BGV')
                ->setTitle('BGV')
                ->setSubject('Address Export Report')
                ->setDescription('Address Export');

            foreach (range('A', 'U') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                    ->setWidth(20);
            }

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A1", 'Sr No.')
                ->setCellValue("B1", 'Client Name')
                ->setCellValue("C1", 'Entity')
                ->setCellValue("D1", 'Package')
                ->setCellValue("E1", 'Client Ref No')
                ->setCellValue("F1", REFNO)
                ->setCellValue("G1", 'Candidate Name')
                ->setCellValue("H1", 'Received Date')
                ->setCellValue("I1", 'Component Ref No')
                ->setCellValue("J1", 'Component Initiation Date')
                ->setCellValue("K1", 'Name of Reference')
                ->setCellValue("L1", 'Designation')
                ->setCellValue("M1", 'Contact Number')
                ->setCellValue("N1", 'Email ID')
                ->setCellValue("O1", 'Mode of verification')
                ->setCellValue("P1", 'verification Status')
                ->setCellValue("Q1", 'Remarks')
                ->setCellValue("R1", 'QC Status')
                ->setCellValue("S1", 'TAT Status')
                ->setCellValue("T1", 'Check Closure Date')
                ->setCellValue("U1", 'Last Activity');
            $x = 2;

            foreach ($result as $key => $value) {
                $value = array_map('ucwords', $value);
                $value = array_map(function ($value) {return $value == "" ? 'NA' : $value;}, $value);
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A$x", ($x - 1))
                    ->setCellValue("B$x", $value['client_name'])
                    ->setCellValue("C$x", $value['entity_name'])
                    ->setCellValue("D$x", $value['package_name'])
                    ->setCellValue("E$x", $value['ClientRefNumber'])
                    ->setCellValue("F$x", $value['cmp_ref_no'])
                    ->setCellValue("G$x", $value['CandidateName'])
                    ->setCellValue("H$x", $value['caserecddate'])
                    ->setCellValue("I$x", $value['reference_com_ref'])
                    ->setCellValue("J$x", $value['iniated_date'])
                    ->setCellValue("K$x", $value['name_of_reference'])
                    ->setCellValue("L$x", $value['designation'])
                    ->setCellValue("M$x", $value['contact_no'])
                    ->setCellValue("N$x", $value['email_id'])
                    ->setCellValue("O$x", $value['mode_of_verification'])
                    ->setCellValue("P$x", $value['verfstatus'])
                    ->setCellValue("Q$x", $value['remarks'])
                    ->setCellValue("R$x", $value['first_qc_approve'])
                    ->setCellValue("S$x", $value['tat_status'])
                    ->setCellValue("T$x", $value['closuredate'])
                    ->setCellValue("U$x", $value['last_activity_date']);
                $x++;
            }
            $spreadsheet->getActiveSheet()->setTitle('Candidate Records');
            $spreadsheet->setActiveSheetIndex(0);

            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment;filename=Candidates Bulk Uplaod Template.xlsx");
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

            $json_array['file_name'] = "Export-Reference-" . date(DISPLAY_DATE_FORMAT12) . ".xls";

            $json_array['message'] = "File downloaded successfully,please check in download folder";

            $json_array['status'] = SUCCESS_CODE;
        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['status'] = "we are sorry but you don't have access to this service";

        }

        echo_json($json_array);
    }

    public function courtver()
    {
        $data['header_title'] = "Export Court Records";

        $data['clients'] = $this->get_clients(array('status' => STATUS_ACTIVE));

        $data['states'] = $this->get_states();

        $data['assigned_user_id'] = $this->users_list();

        $data['status'] = $this->get_status();

        $data['logs'] = $this->report_generated_user->select(false, array('created_on,(select user_name from user_profile where user_profile.id = report_generated_user.created_by) as executive_name'), array('type' => 'Court'));

        $this->load->view('admin/header', $data);

        $this->load->view('admin/export_court_filter');

        $this->load->view('admin/footer');
    }

    public function court_export()
    {
        $json_data = array();

        if ($this->input->is_ajax_request()) {
            $this->load->model('court_verificatiion_model');

            $filter = $this->input->post();

            $where_condition_arry = array('clientid' => 'candidates_info.clientid', 'entity' => 'candidates_info.entity', 'package' => 'candidates_info.package', 'overallclosuredate_from' => 'candidates_info.caserecddate', 'overallclosuredate_to' => 'candidates_info.caserecddate', 'address_type' => 'address_type', 'street_address' => 'street_address', 'city' => 'city', 'pincode' => 'pincode', 'state' => 'state', 'has_case_id' => 'has_case_id', 'verfstatus' => 'verfstatus', 'filter_by_sub_status' => 'verfstatus', 'mode_of_verification' => 'mode_of_verification', 'closuredate_from' => 'closuredate', 'closuredate_to' => 'closuredate');

            $where = ' where 1 ';

            foreach ($filter as $key => $value) {

                switch ($key) {
                    case 'clientid':
                        if ($filter['clientid'] != 0) {
                            $where .= " and candidates_info.clientid = '" . $filter['clientid'] . "' ";
                        }
                        break;
                    case 'entity':
                        if ($filter['entity'] != 0) {
                            $where .= " and candidates_info.entity = '" . $filter['entity'] . "' ";
                        }
                        break;
                    case 'package':
                        if ($filter['package'] != 0) {
                            $where .= " and candidates_info.package = '" . $filter['package'] . "' ";
                        }
                        break;
                    case 'overallclosuredate_from':
                        if ($filter['overallclosuredate_from'] != '') {
                            $from = ($filter['overallclosuredate_from'] != '') ? convert_display_to_db_date($filter['overallclosuredate_from']) : date(DATE_ONLY);
                            $to = ($filter['overallclosuredate_to'] != '') ? convert_display_to_db_date($filter['overallclosuredate_to']) : '';
                            $to = ($to) ? " '" . $to . "' " : 'CURRENT_DATE()';
                            $where .= " and candidates_info.caserecddate between '" . $from . "' and " . $to . " ";
                        }
                        break;
                    case 'overallclosuredate_to':
                        break;
                    case 'mode_of_verification':
                        if ($filter['mode_of_verification'] != 0 && $filter['mode_of_verification'] != '') {
                            $where .= " and mode_of_verification  = '" . $filter['mode_of_verification'] . "' ";
                        }
                        break;
                    case 'iniated_date_from':
                        if ($filter['iniated_date_from'] != '') {
                            $from = ($filter['iniated_date_from'] != '') ? convert_display_to_db_date($filter['iniated_date_from']) : date(DATE_ONLY);
                            $to = ($filter['iniated_date_to'] != '') ? convert_display_to_db_date($filter['iniated_date_to']) : '';
                            $to = ($to) ? " '" . $to . "' " : 'CURRENT_DATE()';

                            $where .= " and iniated_date between '" . $from . "' and " . $to . " ";
                        }
                        break;
                    case 'iniated_date_to':
                        break;
                    case 'state':
                        if ($filter['state'] != 0) {
                            $where .= " and state = '" . $filter['state'] . "' ";
                        }
                        break;
                    case 'has_case_id':
                        if ($filter['has_case_id'] != 0) {
                            $where .= " and has_case_id = '" . $filter['has_case_id'] . "' ";
                        }
                        break;
                    case 'verfstatus':
                        if ($filter['verfstatus'] != 'Select Status') {
                            $status = $this->sub_status($filter['verfstatus']);
                            $where .= " and verfstatus in (" . implode(',', array_keys($status)) . ") ";
                        }
                        break;
                    case 'filter_by_sub_status':
                        if ($filter['filter_by_sub_status'] != 0) {
                            $where .= " and verfstatus = '" . $filter['filter_by_sub_status'] . "' ";
                        }
                        break;
                    case 'closuredate_from':
                        if ($filter['closuredate_from'] != '') {
                            $from = ($filter['closuredate_from'] != '') ? convert_display_to_db_date($filter['closuredate_from']) : date(DATE_ONLY);
                            $to = ($filter['closuredate_to'] != '') ? convert_display_to_db_date($filter['closuredate_to']) : '';
                            $to = ($to) ? " '" . $to . "' " : 'CURRENT_DATE()';

                            $where .= " and closuredate '" . $from . "' between " . $to . " ";
                        }
                        break;
                    case 'closuredate_to':
                        break;
                    default:
                        if (array_key_exists($key, $where_condition_arry) && $value != '') {
                            $where .= " and  " . $where_condition_arry[$key] . " = '" . $value . "' ";
                        }
                        break;
                }
            }

            $data['lists'] = $this->court_verificatiion_model->export_sql($where);

            $report_id = $this->requested_report_save(array('query' => $this->db->last_query(), 'type' => 'Court', 'created_by' => $this->user_info['id'], 'filter' => json_encode($filter), 'created_on' => date(DB_DATE_FORMAT)));
            $data['report_id'] = $report_id;
            $data['assigned_user_id'] = $this->users_list();
            $json_array['message'] = $this->load->view('admin/window_popup_court', $data, true);

            $json_array['status'] = SUCCESS_CODE;
        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['status'] = "we are sorry but you don't have access to this service";

        }

        echo_json($json_array);
    }

    public function court_export_csv()
    {
        $json_data = array();

        if ($this->input->is_ajax_request()) {
            $this->load->model('court_verificatiion_model');

            $filter = $this->input->post();

            $result = $this->court_verificatiion_model->export_sql($filter);

            require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
            // Create new Spreadsheet object
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            // Set document properties
            $spreadsheet->getProperties()->setCreator('BGV')
                ->setLastModifiedBy('BGV')
                ->setTitle('BGV')
                ->setSubject('Court Export Report')
                ->setDescription('Court Export');

            foreach (range('A', 'Z') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                    ->setWidth(20);
            }

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A1", 'Sr No.')
                ->setCellValue("B1", 'Client Name')
                ->setCellValue("C1", 'Entity')
                ->setCellValue("D1", 'Package')
                ->setCellValue("E1", 'Client Ref No')
                ->setCellValue("F1", REFNO)
                ->setCellValue("G1", 'Candidate Name')
                ->setCellValue("H1", 'Received Date')
                ->setCellValue("I1", 'Component Ref No')
                ->setCellValue("J1", 'Component Initiation Date')
                ->setCellValue("K1", 'Address type')
                ->setCellValue("L1", 'Street Address')
                ->setCellValue("M1", 'City')
                ->setCellValue("N1", 'Pincode')
                ->setCellValue("O1", 'State')
                ->setCellValue("P1", 'Vendor')
                ->setCellValue("Q1", 'Executive Name')
                ->setCellValue("R1", 'Mode of verification')
                ->setCellValue("S1", 'Verified By')
                ->setCellValue("T1", "Advocate's Name")
                ->setCellValue("U1", 'Verification Status')
                ->setCellValue("V1", 'Remarks')
                ->setCellValue("W1", 'QC Status')
                ->setCellValue("X1", 'TAT Status')
                ->setCellValue("Y1", 'Check Closure Date')
                ->setCellValue("Z1", 'Last Activity');
            $x = 2;

            foreach ($result as $key => $value) {
                $value = array_map('ucwords', $value);
                $value = array_map(function ($value) {return $value == "" ? 'NA' : $value;}, $value);
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A$x", ($x - 1))
                    ->setCellValue("B$x", $value['client_name'])
                    ->setCellValue("C$x", $value['entity_name'])
                    ->setCellValue("D$x", $value['package_name'])
                    ->setCellValue("E$x", $value['ClientRefNumber'])
                    ->setCellValue("F$x", $value['cmp_ref_no'])
                    ->setCellValue("G$x", $value['CandidateName'])
                    ->setCellValue("H$x", $value['caserecddate'])
                    ->setCellValue("I$x", $value['court_com_ref'])
                    ->setCellValue("J$x", $value['iniated_date'])
                    ->setCellValue("K$x", $value['address_type'])
                    ->setCellValue("L$x", $value['street_address'])
                    ->setCellValue("M$x", $value['city'])
                    ->setCellValue("N$x", $value['pincode'])
                    ->setCellValue("O$x", $value['state'])
                    ->setCellValue("P$x", $value['vendor_id'])
                    ->setCellValue("Q$x", $value['executive_name'])
                    ->setCellValue("R$x", $value['mode_of_verification'])
                    ->setCellValue("S$x", $value['verified_by'])
                    ->setCellValue("T$x", $value['advocate_name'])
                    ->setCellValue("U$x", $value['verfstatus'])
                    ->setCellValue("V$x", $value['remarks'])
                    ->setCellValue("W$x", $value['first_qc_approve'])
                    ->setCellValue("X$x", $value['tat_status'])
                    ->setCellValue("Y$x", $value['closuredate'])
                    ->setCellValue("Z$x", $value['last_activity_date']);
                $x++;
            }
            $spreadsheet->getActiveSheet()->setTitle('Candidate Records');
            $spreadsheet->setActiveSheetIndex(0);

            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment;filename=Candidates Bulk Uplaod Template.xlsx");
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

            $json_array['file_name'] = "Export-Court-" . date(DISPLAY_DATE_FORMAT12) . ".xls";

            $json_array['message'] = "File downloaded successfully,please check in download folder";

            $json_array['status'] = SUCCESS_CODE;
        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['status'] = "we are sorry but you don't have access to this service";

        }

        echo_json($json_array);
    }

    public function globdbver()
    {
        $data['header_title'] = "Export Global Records";

        $data['clients'] = $this->get_clients(array('status' => STATUS_ACTIVE));

        $data['states'] = $this->get_states();

        $data['assigned_user_id'] = $this->users_list();

        $data['status'] = $this->get_status();

        $data['logs'] = $this->report_generated_user->select(false, array('created_on,(select user_name from user_profile where user_profile.id = report_generated_user.created_by) as executive_name'), array('type' => 'Global'));

        $this->load->view('admin/header', $data);

        $this->load->view('admin/export_global_filter');

        $this->load->view('admin/footer');
    }

    public function global_export()
    {
        $json_data = array();

        if ($this->input->is_ajax_request()) {
            $this->load->model('global_database_model');

            $filter = $this->input->post();

            $where_condition_arry = array('clientid' => 'candidates_info.clientid', 'entity' => 'candidates_info.entity', 'package' => 'candidates_info.package', 'overallclosuredate_from' => 'candidates_info.caserecddate', 'overallclosuredate_to' => 'candidates_info.caserecddate', 'address_type' => 'address_type', 'street_address' => 'street_address', 'city' => 'city', 'pincode' => 'pincode', 'state' => 'state', 'has_case_id' => 'has_case_id', 'verfstatus' => 'verfstatus', 'filter_by_sub_status' => 'verfstatus', 'mode_of_verification' => 'mode_of_verification', 'closuredate_from' => 'closuredate', 'closuredate_to' => 'closuredate');

            $where = ' where 1 ';

            foreach ($filter as $key => $value) {

                switch ($key) {
                    case 'clientid':
                        if ($filter['clientid'] != 0) {
                            $where .= " and candidates_info.clientid = '" . $filter['clientid'] . "' ";
                        }
                        break;
                    case 'entity':
                        if ($filter['entity'] != 0) {
                            $where .= " and candidates_info.entity = '" . $filter['entity'] . "' ";
                        }
                        break;
                    case 'package':
                        if ($filter['package'] != 0) {
                            $where .= " and candidates_info.package = '" . $filter['package'] . "' ";
                        }
                        break;
                    case 'overallclosuredate_from':
                        if ($filter['overallclosuredate_from'] != '') {
                            $from = ($filter['overallclosuredate_from'] != '') ? convert_display_to_db_date($filter['overallclosuredate_from']) : date(DATE_ONLY);
                            $to = ($filter['overallclosuredate_to'] != '') ? convert_display_to_db_date($filter['overallclosuredate_to']) : '';
                            $to = ($to) ? " '" . $to . "' " : 'CURRENT_DATE()';
                            $where .= " and candidates_info.caserecddate between '" . $from . "' and " . $to . " ";
                        }
                        break;
                    case 'overallclosuredate_to':
                        break;
                    case 'mode_of_verification':
                        if ($filter['mode_of_verification'] != 0 && $filter['mode_of_verification'] != '') {
                            $where .= " and mode_of_verification  = '" . $filter['mode_of_verification'] . "' ";
                        }
                        break;
                    case 'iniated_date_from':
                        if ($filter['iniated_date_from'] != '') {
                            $from = ($filter['iniated_date_from'] != '') ? convert_display_to_db_date($filter['iniated_date_from']) : date(DATE_ONLY);
                            $to = ($filter['iniated_date_to'] != '') ? convert_display_to_db_date($filter['iniated_date_to']) : '';
                            $to = ($to) ? " '" . $to . "' " : 'CURRENT_DATE()';

                            $where .= " and iniated_date between '" . $from . "' and " . $to . " ";
                        }
                        break;
                    case 'iniated_date_to':
                        break;
                    case 'state':
                        if ($filter['state'] != 0) {
                            $where .= " and state = '" . $filter['state'] . "' ";
                        }
                        break;
                    case 'has_case_id':
                        if ($filter['has_case_id'] != 0) {
                            $where .= " and has_case_id = '" . $filter['has_case_id'] . "' ";
                        }
                        break;
                    case 'verfstatus':
                        if ($filter['verfstatus'] != 'Select Status') {
                            $status = $this->sub_status($filter['verfstatus']);
                            $where .= " and verfstatus in (" . implode(',', array_keys($status)) . ") ";
                        }
                        break;
                    case 'filter_by_sub_status':
                        if ($filter['filter_by_sub_status'] != 0) {
                            $where .= " and verfstatus = '" . $filter['filter_by_sub_status'] . "' ";
                        }
                        break;
                    case 'closuredate_from':
                        if ($filter['closuredate_from'] != '') {
                            $from = ($filter['closuredate_from'] != '') ? convert_display_to_db_date($filter['closuredate_from']) : date(DATE_ONLY);
                            $to = ($filter['closuredate_to'] != '') ? convert_display_to_db_date($filter['closuredate_to']) : '';
                            $to = ($to) ? " '" . $to . "' " : 'CURRENT_DATE()';

                            $where .= " and closuredate '" . $from . "' between " . $to . " ";
                        }
                        break;
                    case 'closuredate_to':
                        break;
                    default:
                        if (array_key_exists($key, $where_condition_arry) && $value != '') {
                            $where .= " and  " . $where_condition_arry[$key] . " = '" . $value . "' ";
                        }
                        break;
                }
            }

            $data['lists'] = $this->global_database_model->export_sql($where);

            $report_id = $this->requested_report_save(array('query' => $this->db->last_query(), 'type' => 'Global', 'created_by' => $this->user_info['id'], 'filter' => json_encode($filter), 'created_on' => date(DB_DATE_FORMAT)));
            $data['report_id'] = $report_id;
            $data['assigned_user_id'] = $this->users_list();
            $json_array['message'] = $this->load->view('admin/window_popup_global', $data, true);

            $json_array['status'] = SUCCESS_CODE;
        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['status'] = "we are sorry but you don't have access to this service";

        }

        echo_json($json_array);
    }

    public function global_export_csv()
    {
        $json_data = array();

        if ($this->input->is_ajax_request()) {
            $this->load->model('global_database_model');

            $filter = $this->input->post();

            $result = $this->global_database_model->export_sql($filter);

            require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
            // Create new Spreadsheet object
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            // Set document properties
            $spreadsheet->getProperties()->setCreator('BGV')
                ->setLastModifiedBy('BGV')
                ->setTitle('BGV')
                ->setSubject('Court Export Report')
                ->setDescription('Court Export');

            foreach (range('A', 'Z') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                    ->setWidth(20);
            }

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A1", 'Sr No.')
                ->setCellValue("B1", 'Client Name')
                ->setCellValue("C1", 'Entity')
                ->setCellValue("D1", 'Package')
                ->setCellValue("E1", 'Client Ref No')
                ->setCellValue("F1", REFNO)
                ->setCellValue("G1", 'Candidate Name')
                ->setCellValue("H1", 'Received Date')
                ->setCellValue("I1", 'Component Ref No')
                ->setCellValue("J1", 'Component Initiation Date')
                ->setCellValue("K1", 'Address type')
                ->setCellValue("L1", 'Street Address')
                ->setCellValue("M1", 'City')
                ->setCellValue("N1", 'Pincode')
                ->setCellValue("O1", 'State')
                ->setCellValue("P1", 'Vendor')
                ->setCellValue("Q1", 'Executive Name')
                ->setCellValue("R1", 'Mode of verification')
                ->setCellValue("S1", 'Verified By')
                ->setCellValue("T1", "Attachment")
                ->setCellValue("U1", 'Verification Status')
                ->setCellValue("V1", 'Remarks')
                ->setCellValue("W1", 'QC Status')
                ->setCellValue("X1", 'TAT Status')
                ->setCellValue("Y1", 'Check Closure Date')
                ->setCellValue("Z1", 'Last Activity');
            $x = 2;

            foreach ($result as $key => $value) {
                $value = array_map('ucwords', $value);
                $value = array_map(function ($value) {return $value == "" ? 'NA' : $value;}, $value);
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A$x", ($x - 1))
                    ->setCellValue("B$x", $value['client_name'])
                    ->setCellValue("C$x", $value['entity_name'])
                    ->setCellValue("D$x", $value['package_name'])
                    ->setCellValue("E$x", $value['ClientRefNumber'])
                    ->setCellValue("F$x", $value['cmp_ref_no'])
                    ->setCellValue("G$x", $value['CandidateName'])
                    ->setCellValue("H$x", $value['caserecddate'])
                    ->setCellValue("I$x", $value['global_com_ref'])
                    ->setCellValue("J$x", $value['iniated_date'])
                    ->setCellValue("K$x", $value['address_type'])
                    ->setCellValue("L$x", $value['street_address'])
                    ->setCellValue("M$x", $value['city'])
                    ->setCellValue("N$x", $value['pincode'])
                    ->setCellValue("O$x", $value['state'])
                    ->setCellValue("P$x", $value['vendor_id'])
                    ->setCellValue("Q$x", $value['executive_name'])
                    ->setCellValue("R$x", $value['mode_of_verification'])
                    ->setCellValue("S$x", $value['verified_by'])
                    ->setCellValue("T$x", $value['file_name'])
                    ->setCellValue("U$x", $value['verfstatus'])
                    ->setCellValue("V$x", $value['remarks'])
                    ->setCellValue("W$x", $value['first_qc_approve'])
                    ->setCellValue("X$x", $value['tat_status'])
                    ->setCellValue("Y$x", $value['closuredate'])
                    ->setCellValue("Z$x", $value['last_activity_date']);
                $x++;
            }
            $spreadsheet->getActiveSheet()->setTitle('Candidate Records');
            $spreadsheet->setActiveSheetIndex(0);

            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment;filename=Candidates Bulk Uplaod Template.xlsx");
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

            $json_array['file_name'] = "Export-Global-" . date(DISPLAY_DATE_FORMAT12) . ".xls";

            $json_array['message'] = "File downloaded successfully,please check in download folder";

            $json_array['status'] = SUCCESS_CODE;
        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['status'] = "we are sorry but you don't have access to this service";

        }

        echo_json($json_array);
    }

    public function narcver()
    {
        $data['header_title'] = "Export Drug/Narcotics Records";

        $data['clients'] = $this->get_clients(array('status' => STATUS_ACTIVE));

        $data['states'] = $this->get_states();

        $data['assigned_user_id'] = $this->users_list();

        $data['status'] = $this->get_status();

        $data['logs'] = $this->report_generated_user->select(false, array('created_on,(select user_name from user_profile where user_profile.id = report_generated_user.created_by) as executive_name'), array('type' => 'Drug'));

        $this->load->view('admin/header', $data);

        $this->load->view('admin/export_drug_narcver_filter');

        $this->load->view('admin/footer');
    }

    public function narcver_export()
    {
        $json_data = array();

        if ($this->input->is_ajax_request()) {
            $this->load->model('drug_verificatiion_model');

            $filter = $this->input->post();

            $where_condition_arry = array('clientid' => 'candidates_info.clientid', 'entity' => 'candidates_info.entity', 'package' => 'candidates_info.package', 'overallclosuredate_from' => 'candidates_info.caserecddate', 'overallclosuredate_to' => 'candidates_info.caserecddate', 'address_type' => 'address_type', 'street_address' => 'street_address', 'city' => 'city', 'pincode' => 'pincode', 'state' => 'state', 'has_case_id' => 'has_case_id', 'verfstatus' => 'verfstatus', 'filter_by_sub_status' => 'verfstatus', 'mode_of_verification' => 'mode_of_verification', 'closuredate_from' => 'closuredate', 'closuredate_to' => 'closuredate');

            $where = ' where 1 ';

            foreach ($filter as $key => $value) {

                switch ($key) {
                    case 'clientid':
                        if ($filter['clientid'] != 0) {
                            $where .= " and candidates_info.clientid = '" . $filter['clientid'] . "' ";
                        }
                        break;
                    case 'entity':
                        if ($filter['entity'] != 0) {
                            $where .= " and candidates_info.entity = '" . $filter['entity'] . "' ";
                        }
                        break;
                    case 'package':
                        if ($filter['package'] != 0) {
                            $where .= " and candidates_info.package = '" . $filter['package'] . "' ";
                        }
                        break;
                    case 'overallclosuredate_from':
                        if ($filter['overallclosuredate_from'] != '') {
                            $from = ($filter['overallclosuredate_from'] != '') ? convert_display_to_db_date($filter['overallclosuredate_from']) : date(DATE_ONLY);
                            $to = ($filter['overallclosuredate_to'] != '') ? convert_display_to_db_date($filter['overallclosuredate_to']) : '';
                            $to = ($to) ? " '" . $to . "' " : 'CURRENT_DATE()';
                            $where .= " and candidates_info.caserecddate between '" . $from . "' and " . $to . " ";
                        }
                        break;
                    case 'overallclosuredate_to':
                        break;
                    case 'mode_of_verification':
                        if ($filter['mode_of_verification'] != 0 && $filter['mode_of_verification'] != '') {
                            $where .= " and mode_of_verification  = '" . $filter['mode_of_verification'] . "' ";
                        }
                        break;
                    case 'iniated_date_from':
                        if ($filter['iniated_date_from'] != '') {
                            $from = ($filter['iniated_date_from'] != '') ? convert_display_to_db_date($filter['iniated_date_from']) : date(DATE_ONLY);
                            $to = ($filter['iniated_date_to'] != '') ? convert_display_to_db_date($filter['iniated_date_to']) : '';
                            $to = ($to) ? " '" . $to . "' " : 'CURRENT_DATE()';

                            $where .= " and iniated_date between '" . $from . "' and " . $to . " ";
                        }
                        break;
                    case 'iniated_date_to':
                        break;
                    case 'state':
                        if ($filter['state'] != 0) {
                            $where .= " and state = '" . $filter['state'] . "' ";
                        }
                        break;
                    case 'has_case_id':
                        if ($filter['has_case_id'] != 0) {
                            $where .= " and has_case_id = '" . $filter['has_case_id'] . "' ";
                        }
                        break;
                    case 'verfstatus':
                        if ($filter['verfstatus'] != 'Select Status') {
                            $status = $this->sub_status($filter['verfstatus']);
                            $where .= " and verfstatus in (" . implode(',', array_keys($status)) . ") ";
                        }
                        break;
                    case 'filter_by_sub_status':
                        if ($filter['filter_by_sub_status'] != 0) {
                            $where .= " and verfstatus = '" . $filter['filter_by_sub_status'] . "' ";
                        }
                        break;
                    case 'closuredate_from':
                        if ($filter['closuredate_from'] != '') {
                            $from = ($filter['closuredate_from'] != '') ? convert_display_to_db_date($filter['closuredate_from']) : date(DATE_ONLY);
                            $to = ($filter['closuredate_to'] != '') ? convert_display_to_db_date($filter['closuredate_to']) : '';
                            $to = ($to) ? " '" . $to . "' " : 'CURRENT_DATE()';

                            $where .= " and closuredate '" . $from . "' between " . $to . " ";
                        }
                        break;
                    case 'closuredate_to':
                        break;
                    default:
                        if (array_key_exists($key, $where_condition_arry) && $value != '') {
                            $where .= " and  " . $where_condition_arry[$key] . " = '" . $value . "' ";
                        }
                        break;
                }
            }

            $data['lists'] = $this->drug_verificatiion_model->export_sql($where);

            $report_id = $this->requested_report_save(array('query' => $this->db->last_query(), 'type' => 'Drug', 'created_by' => $this->user_info['id'], 'filter' => json_encode($filter), 'created_on' => date(DB_DATE_FORMAT)));
            $data['report_id'] = $report_id;
            $data['assigned_user_id'] = $this->users_list();
            $json_array['message'] = $this->load->view('admin/window_popup_drug', $data, true);

            $json_array['status'] = SUCCESS_CODE;
        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['status'] = "we are sorry but you don't have access to this service";

        }

        echo_json($json_array);
    }

    public function drug_export_csv()
    {
        $json_data = array();

        if ($this->input->is_ajax_request()) {
            $this->load->model('drug_verificatiion_model');

            $filter = $this->input->post();

            $result = $this->drug_verificatiion_model->export_sql($filter);

            require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
            // Create new Spreadsheet object
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            // Set document properties
            $spreadsheet->getProperties()->setCreator('BGV')
                ->setLastModifiedBy('BGV')
                ->setTitle('BGV')
                ->setSubject('Court Export Report')
                ->setDescription('Court Export');

            foreach (range('A', 'AH') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                    ->setWidth(20);
            }

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A1", 'Sr No.')
                ->setCellValue("B1", 'Client Name')
                ->setCellValue("C1", 'Entity')
                ->setCellValue("D1", 'Package')
                ->setCellValue("E1", 'Client Ref No')
                ->setCellValue("F1", REFNO)
                ->setCellValue("G1", 'Candidate Name')
                ->setCellValue("H1", 'Received Date')
                ->setCellValue("I1", 'Component Ref No')
                ->setCellValue("J1", 'Component Initiation Date')
                ->setCellValue("K1", 'Appointment Date')
                ->setCellValue("L1", 'Appointment Time')
                ->setCellValue("M1", 'Spoc Phone Number')
                ->setCellValue("N1", 'Drug Test Panel/Code')
                ->setCellValue("O1", 'Facility Name/Code')
                ->setCellValue("P1", 'Street Address')
                ->setCellValue("Q1", 'City')
                ->setCellValue("R1", 'Pincode')
                ->setCellValue("S1", 'State')
                ->setCellValue("T1", 'Vendor')
                ->setCellValue("U1", 'Executive Name')
                ->setCellValue("V1", 'Mode of verification')
                ->setCellValue("W1", 'Amphetamine Screen, Urine')
                ->setCellValue("X1", 'Cannabinoids Screen, Urine')
                ->setCellValue("Y1", 'Cocaine Screen, Urine')
                ->setCellValue("Z1", 'Opiates Screen, Urine')
                ->setCellValue("AA1", 'Phencyclidine Screen, Urine')
                ->setCellValue("AB1", "Attachment")
                ->setCellValue("AC1", 'Verification Status')
                ->setCellValue("AD1", 'Remarks')
                ->setCellValue("AE1", 'QC Status')
                ->setCellValue("AF1", 'TAT Status')
                ->setCellValue("AG1", 'Check Closure Date')
                ->setCellValue("AH1", 'Last Activity');
            $x = 2;

            foreach ($result as $key => $value) {
                $value = array_map('ucwords', $value);
                $value = array_map(function ($value) {return $value == "" ? 'NA' : $value;}, $value);
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A$x", ($x - 1))
                    ->setCellValue("B$x", $value['client_name'])
                    ->setCellValue("C$x", $value['entity_name'])
                    ->setCellValue("D$x", $value['package_name'])
                    ->setCellValue("E$x", $value['ClientRefNumber'])
                    ->setCellValue("F$x", $value['cmp_ref_no'])
                    ->setCellValue("G$x", $value['CandidateName'])
                    ->setCellValue("H$x", $value['caserecddate'])
                    ->setCellValue("I$x", $value['drug_com_ref'])
                    ->setCellValue("J$x", $value['iniated_date'])
                    ->setCellValue("K$x", $value['appointment_date'])
                    ->setCellValue("L$x", $value['appointment_time'])
                    ->setCellValue("M$x", $value['spoc_no'])
                    ->setCellValue("N$x", $value['drug_test_code'])
                    ->setCellValue("O$x", $value['facility_name'])
                    ->setCellValue("P$x", $value['street_address'])
                    ->setCellValue("Q$x", $value['city'])
                    ->setCellValue("R$x", $value['pincode'])
                    ->setCellValue("S$x", $value['state'])
                    ->setCellValue("T$x", $value['vendor_id'])
                    ->setCellValue("U$x", $value['executive_name'])
                    ->setCellValue("V$x", $value['mode_of_verification'])
                    ->setCellValue("W$x", $value['amphetamine_screen'])
                    ->setCellValue("X$x", $value['cannabinoids_screen'])
                    ->setCellValue("Y$x", $value['cocaine_screen'])
                    ->setCellValue("Z$x", $value['opiates_screen'])
                    ->setCellValue("AA$x", $value['phencyclidine_screen'])
                    ->setCellValue("AB$x", $value['file_name'])
                    ->setCellValue("AC$x", $value['verfstatus'])
                    ->setCellValue("AD$x", $value['remarks'])
                    ->setCellValue("AE$x", $value['first_qc_approve'])
                    ->setCellValue("AF$x", $value['tat_status'])
                    ->setCellValue("AG$x", $value['closuredate'])
                    ->setCellValue("AH$x", $value['last_activity_date']);
                $x++;
            }
            $spreadsheet->getActiveSheet()->setTitle('Candidate Records');
            $spreadsheet->setActiveSheetIndex(0);

            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment;filename=Candidates Bulk Uplaod Template.xlsx");
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

            $json_array['file_name'] = "Export-Drug/Narcotics-" . date(DISPLAY_DATE_FORMAT12) . ".xls";

            $json_array['message'] = "File downloaded successfully,please check in download folder";

            $json_array['status'] = SUCCESS_CODE;
        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['status'] = "we are sorry but you don't have access to this service";

        }

        echo_json($json_array);
    }

    public function crimver() // pcc

    {
        $data['header_title'] = "Export PCC Records";

        $data['clients'] = $this->get_clients(array('status' => STATUS_ACTIVE));

        $data['states'] = $this->get_states();

        $data['assigned_user_id'] = $this->users_list();

        $data['logs'] = $this->report_generated_user->select(false, array('created_on,(select user_name from user_profile where user_profile.id = report_generated_user.created_by) as executive_name'), array('type' => 'PCC'));

        $data['status'] = $this->get_status();

        $this->load->view('admin/header', $data);

        $this->load->view('admin/export_pcc_filter');

        $this->load->view('admin/footer');
    }

    public function pcc_export()
    {
        $json_data = array();

        if ($this->input->is_ajax_request()) {
            $this->load->model('pcc_verificatiion_model');

            $filter = $this->input->post();
            $where_condition_arry = array('clientid' => 'candidates_info.clientid', 'entity' => 'candidates_info.entity', 'package' => 'candidates_info.package', 'overallclosuredate_from' => 'candidates_info.caserecddate', 'overallclosuredate_to' => 'candidates_info.caserecddate', 'address_type' => 'address_type', 'street_address' => 'street_address', 'city' => 'city', 'pincode' => 'pincode', 'state' => 'pcc.state', 'has_case_id' => 'has_case_id', 'verfstatus' => 'verfstatus', 'filter_by_sub_status' => 'verfstatus', 'mode_of_verification' => 'mode_of_verification', 'closuredate_from' => 'closuredate', 'closuredate_to' => 'closuredate');

            $where = ' where 1 ';

            foreach ($filter as $key => $value) {

                switch ($key) {
                    case 'clientid':
                        if ($filter['clientid'] != 0) {
                            $where .= " and candidates_info.clientid = '" . $filter['clientid'] . "' ";
                        }
                        break;
                    case 'entity':
                        if ($filter['entity'] != 0) {
                            $where .= " and candidates_info.entity = '" . $filter['entity'] . "' ";
                        }
                        break;
                    case 'package':
                        if ($filter['package'] != 0) {
                            $where .= " and candidates_info.package = '" . $filter['package'] . "' ";
                        }
                        break;
                    case 'overallclosuredate_from':
                        if ($filter['overallclosuredate_from'] != '') {
                            $from = ($filter['overallclosuredate_from'] != '') ? convert_display_to_db_date($filter['overallclosuredate_from']) : date(DATE_ONLY);
                            $to = ($filter['overallclosuredate_to'] != '') ? convert_display_to_db_date($filter['overallclosuredate_to']) : '';
                            $to = ($to) ? " '" . $to . "' " : 'CURRENT_DATE()';
                            $where .= " and candidates_info.caserecddate between '" . $from . "' and " . $to . " ";
                        }
                        break;
                    case 'overallclosuredate_to':
                        break;
                    case 'mode_of_verification':
                        if ($filter['mode_of_verification']) {
                            $where .= " and mode_of_verification  = '" . $filter['mode_of_verification'] . "' ";
                        }
                        break;
                    case 'iniated_date_from':
                        if ($filter['iniated_date_from'] != '') {
                            $from = ($filter['iniated_date_from'] != '') ? convert_display_to_db_date($filter['iniated_date_from']) : date(DATE_ONLY);
                            $to = ($filter['iniated_date_to'] != '') ? convert_display_to_db_date($filter['iniated_date_to']) : '';
                            $to = ($to) ? " '" . $to . "' " : 'CURRENT_DATE()';

                            $where .= " and iniated_date between '" . $from . "' and " . $to . " ";
                        }
                        break;
                    case 'iniated_date_to':
                        break;
                    case 'state':
                        if ($filter['state']) {
                            $where .= " and pcc.state = '" . $filter['state'] . "' ";
                        }

                        break;
                    case 'has_case_id':
                        if ($filter['has_case_id'] != 0) {
                            $where .= " and has_case_id = '" . $filter['has_case_id'] . "' ";
                        }
                        break;
                    case 'verfstatus':
                        if ($filter['verfstatus'] != 'Select Status') {
                            $status = $this->sub_status($filter['verfstatus']);
                            $where .= " and verfstatus in (" . implode(',', array_keys($status)) . ") ";
                        }
                        break;
                    case 'filter_by_sub_status':
                        if ($filter['filter_by_sub_status'] != 0) {
                            $where .= " and verfstatus = '" . $filter['filter_by_sub_status'] . "' ";
                        }
                        break;
                    case 'closuredate_from':
                        if ($filter['closuredate_from'] != '') {
                            $from = ($filter['closuredate_from'] != '') ? convert_display_to_db_date($filter['closuredate_from']) : date(DATE_ONLY);
                            $to = ($filter['closuredate_to'] != '') ? convert_display_to_db_date($filter['closuredate_to']) : '';
                            $to = ($to) ? " '" . $to . "' " : 'CURRENT_DATE()';

                            $where .= " and closuredate '" . $from . "' between " . $to . " ";
                        }
                        break;
                    case 'closuredate_to':
                        break;
                    default:
                        if (array_key_exists($key, $where_condition_arry) && $value != '') {
                            $where .= " and  " . $where_condition_arry[$key] . " = '" . $value . "' ";
                        }
                        break;
                }
            }

            $data['lists'] = $this->pcc_verificatiion_model->export_sql($where);

            $report_id = $this->requested_report_save(array('query' => $this->db->last_query(), 'type' => 'PCC', 'created_by' => $this->user_info['id'], 'filter' => json_encode($filter), 'created_on' => date(DB_DATE_FORMAT)));
            $data['report_id'] = $report_id;
            $data['assigned_user_id'] = $this->users_list();
            $json_array['message'] = $this->load->view('admin/window_popup_pcc', $data, true);

            $json_array['status'] = SUCCESS_CODE;
        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['status'] = "we are sorry but you don't have access to this service";

        }

        echo_json($json_array);
    }

    public function pcc_export_csv()
    {
        $json_data = array();

        if ($this->input->is_ajax_request()) {
            $this->load->model('pcc_verificatiion_model');

            $filter = $this->input->post();

            $result = $this->pcc_verificatiion_model->export_sql($filter);

            require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
            // Create new Spreadsheet object
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            // Set document properties
            $spreadsheet->getProperties()->setCreator('BGV')
                ->setLastModifiedBy('BGV')
                ->setTitle('BGV')
                ->setSubject('Court Export Report')
                ->setDescription('Court Export');

            foreach (range('A', 'AI') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                    ->setWidth(20);
            }

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A1", 'Sr No.')
                ->setCellValue("B1", 'Client Name')
                ->setCellValue("C1", 'Entity')
                ->setCellValue("D1", 'Package')
                ->setCellValue("E1", 'Client Ref No')
                ->setCellValue("F1", REFNO)
                ->setCellValue("G1", 'Candidate Name')
                ->setCellValue("H1", 'Received Date')
                ->setCellValue("I1", 'Component Ref No')
                ->setCellValue("J1", 'Component Initiation Date')
                ->setCellValue("K1", 'Address type')
                ->setCellValue("L1", 'Street Address')
                ->setCellValue("M1", 'City')
                ->setCellValue("N1", 'Pincode')
                ->setCellValue("O1", 'State')
                ->setCellValue("P1", 'Reference')
                ->setCellValue("Q1", 'Contact Number')
                ->setCellValue("R1", 'Reference 1')
                ->setCellValue("S1", 'Contact Number 2')
                ->setCellValue("T1", 'Vendor')
                ->setCellValue("U1", 'Executive Name')
                ->setCellValue("V1", 'Mode of verification')
                ->setCellValue("W1", 'Application ID/Ref')
                ->setCellValue("X1", 'Submission Date')
                ->setCellValue("Y1", 'Police Station')
                ->setCellValue("Z1", 'Police station visit date')
                ->setCellValue("AA1", 'Name/Designation of the police personnel')
                ->setCellValue("AB1", 'Contact number of the police personnel')
                ->setCellValue("AC1", "Attachment")
                ->setCellValue("AD1", 'Verification Status')
                ->setCellValue("AE1", 'Remarks')
                ->setCellValue("AF1", 'QC Status')
                ->setCellValue("AG1", 'TAT Status')
                ->setCellValue("AH1", 'Check Closure Date')
                ->setCellValue("AI1", 'Last Activity');
            $x = 2;

            foreach ($result as $key => $value) {
                $value = array_map('ucwords', $value);
                $value = array_map(function ($value) {return $value == "" ? 'NA' : $value;}, $value);
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A$x", ($x - 1))
                    ->setCellValue("B$x", $value['client_name'])
                    ->setCellValue("C$x", $value['entity_name'])
                    ->setCellValue("D$x", $value['package_name'])
                    ->setCellValue("E$x", $value['ClientRefNumber'])
                    ->setCellValue("F$x", $value['cmp_ref_no'])
                    ->setCellValue("G$x", $value['CandidateName'])
                    ->setCellValue("H$x", $value['caserecddate'])
                    ->setCellValue("I$x", $value['pcc_com_ref'])
                    ->setCellValue("J$x", $value['iniated_date'])
                    ->setCellValue("K$x", $value['address_type'])
                    ->setCellValue("L$x", $value['street_address'])
                    ->setCellValue("M$x", $value['city'])
                    ->setCellValue("N$x", $value['pincode'])
                    ->setCellValue("O$x", $value['state'])
                    ->setCellValue("P$x", '')
                    ->setCellValue("Q$x", '')
                    ->setCellValue("R$x", '')
                    ->setCellValue("S$x", '')
                    ->setCellValue("T$x", $value['vendor_id'])
                    ->setCellValue("U$x", $value['executive_name'])
                    ->setCellValue("V$x", $value['mode_of_verification'])
                    ->setCellValue("W$x", $value['application_id_ref'])
                    ->setCellValue("X$x", $value['submission_date'])
                    ->setCellValue("Y$x", $value['police_station'])
                    ->setCellValue("Z$x", $value['police_station_visit_date'])
                    ->setCellValue("AA$x", $value['name_designation_police'])
                    ->setCellValue("AB$x", $value['contact_number_police'])
                    ->setCellValue("AC$x", $value['file_name'])
                    ->setCellValue("AD$x", $value['verfstatus'])
                    ->setCellValue("AE$x", $value['remarks'])
                    ->setCellValue("AF$x", $value['first_qc_approve'])
                    ->setCellValue("AG$x", $value['tat_status'])
                    ->setCellValue("AH$x", $value['closuredate'])
                    ->setCellValue("AI$x", $value['last_activity_date']);
                $x++;
            }
            $spreadsheet->getActiveSheet()->setTitle('Candidate Records');
            $spreadsheet->setActiveSheetIndex(0);

            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment;filename=Candidates Bulk Uplaod Template.xlsx");
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

            $json_array['file_name'] = "Export-PCC-" . date(DISPLAY_DATE_FORMAT12) . ".xls";

            $json_array['message'] = "File downloaded successfully,please check in download folder";

            $json_array['status'] = SUCCESS_CODE;
        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['status'] = "we are sorry but you don't have access to this service";

        }

        echo_json($json_array);
    }

    public function identity()
    {
        $data['header_title'] = "Export Court Records";

        $data['status'] = status_frm_db();

        $data['clients'] = $this->get_clients(array('status' => STATUS_ACTIVE));

        $this->load->view('admin/header', $data);

        $this->load->view('admin/export_identity_filter');

        $this->load->view('admin/footer');
    }

    public function identity_export()
    {
        $json_data = array();

        if ($this->input->is_ajax_request()) {
            $this->load->model('identity_model');

            $filter = $this->input->post();

            $result = $this->identity_model->export_sql($filter);

            require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
            // Create new Spreadsheet object
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            // Set document properties
            $spreadsheet->getProperties()->setCreator('BGV')
                ->setLastModifiedBy('BGV')
                ->setTitle('BGV')
                ->setSubject('Court Export Report')
                ->setDescription('Court Export');

            foreach (range('A', 'Z') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                    ->setWidth(20);
            }

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A1", 'Sr No.')
                ->setCellValue("B1", 'Client Name')
                ->setCellValue("C1", 'Entity')
                ->setCellValue("D1", 'Package')
                ->setCellValue("E1", 'Client Ref No')
                ->setCellValue("F1", REFNO)
                ->setCellValue("G1", 'Candidate Name')
                ->setCellValue("H1", 'Received Date')
                ->setCellValue("I1", 'Component Ref No')
                ->setCellValue("J1", 'Component Initiation Date')
                ->setCellValue("K1", 'Doc Submitted')
                ->setCellValue("L1", 'Id Number')
                ->setCellValue("M1", 'Street Address')
                ->setCellValue("N1", 'City')
                ->setCellValue("O1", 'Pincode')
                ->setCellValue("P1", 'State')
                ->setCellValue("Q1", 'Attachment')
                ->setCellValue("R1", 'Executive Name')
                ->setCellValue("S1", 'Mode of verification')
                ->setCellValue("T1", 'Verification Status')
                ->setCellValue("U1", 'Remarks')
                ->setCellValue("V1", 'QC Status')
                ->setCellValue("W1", 'TAT Status')
                ->setCellValue("X1", 'Check Closure Date')
                ->setCellValue("Y1", 'Last Activity');
            $x = 2;

            foreach ($result as $key => $value) {
                $value = array_map('ucwords', $value);
                $value = array_map(function ($value) {return $value == "" ? 'NA' : $value;}, $value);
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A$x", ($x - 1))
                    ->setCellValue("B$x", $value['client_name'])
                    ->setCellValue("C$x", $value['entity_name'])
                    ->setCellValue("D$x", $value['package_name'])
                    ->setCellValue("E$x", $value['ClientRefNumber'])
                    ->setCellValue("F$x", $value['cmp_ref_no'])
                    ->setCellValue("G$x", $value['CandidateName'])
                    ->setCellValue("H$x", $value['caserecddate'])
                    ->setCellValue("I$x", $value['identity_com_ref'])
                    ->setCellValue("J$x", $value['iniated_date'])
                    ->setCellValue("K$x", $value['doc_submited'])
                    ->setCellValue("L$x", $value['id_number'])
                    ->setCellValue("M$x", $value['street_address'])
                    ->setCellValue("N$x", $value['city'])
                    ->setCellValue("O$x", $value['pincode'])
                    ->setCellValue("P$x", $value['state'])
                    ->setCellValue("Q$x", $value['file_name'])
                    ->setCellValue("R$x", $value['executive_name'])
                    ->setCellValue("S$x", $value['mode_of_verification'])
                    ->setCellValue("T$x", $value['verfstatus'])
                    ->setCellValue("U$x", $value['remarks'])
                    ->setCellValue("V$x", $value['first_qc_approve'])
                    ->setCellValue("W$x", $value['tat_status'])
                    ->setCellValue("X$x", $value['closuredate'])
                    ->setCellValue("Y$x", $value['last_activity_date']);
                $x++;
            }
            $spreadsheet->getActiveSheet()->setTitle('Candidate Records');
            $spreadsheet->setActiveSheetIndex(0);

            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment;filename=Candidates Bulk Uplaod Template.xlsx");
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

            $json_array['file_name'] = "Export-Identity-" . date(DISPLAY_DATE_FORMAT12) . ".xls";

            $json_array['message'] = "File downloaded successfully,please check in download folder";

            $json_array['status'] = SUCCESS_CODE;
        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['status'] = "we are sorry but you don't have access to this service";

        }

        echo_json($json_array);
    }

    public function activity_list()
    {
        $data['header_title'] = "Activity List";

        $data['clients'] = $this->get_clients(array('status' => STATUS_ACTIVE));

        $data['logs'] = $this->report_generated_user->select(false, array('created_on,(select user_name from user_profile where user_profile.id = report_generated_user.created_by) as executive_name'), array('type' => 'Activity'));

        $this->load->view('admin/header', $data);

        $this->load->view('admin/export_activity_filter');

        $this->load->view('admin/footer');
    }

    public function tat_status()
    {
        $data['header_title'] = "Export Address Records";

        $data['clients'] = $this->get_clients(array('status' => STATUS_ACTIVE));

        $data['logs'] = $this->report_generated_user->select(false, array('created_on,(select user_name from user_profile where user_profile.id = report_generated_user.created_by) as executive_name'), array('type' => 'TAT Status'));

        $this->load->view('admin/header', $data);

        $this->load->view('admin/export_tat_status_filter');

        $this->load->view('admin/footer');
    }

    public function insufficiency_list()
    {
        $data['header_title'] = "insufficiency Records";

        $data['clients'] = $this->get_clients(array('status' => STATUS_ACTIVE));

        $data['logs'] = $this->report_generated_user->select(false, array('created_on,(select user_name from user_profile where user_profile.id = report_generated_user.created_by) as executive_name'), array('type' => 'TAT Status'));

        $this->load->view('admin/header', $data);

        $this->load->view('admin/export_insufficiency_filter');

        $this->load->view('admin/footer');
    }

    public function address_dashboard_record()
    {
        $json_data = array();

        if ($this->input->is_ajax_request()) {
            $this->load->model('addressver_model');

            $filter = $this->input->post();

            $where_condition_arry = array('clientid' => 'candidates_info.clientid', 'entity' => 'candidates_info.entity', 'package' => 'candidates_info.package', 'overallclosuredate_from' => 'candidates_info.caserecddate', 'overallclosuredate_to' => 'candidates_info.caserecddate', 'address_type' => 'address_type', 'address' => 'addrver.address', 'city' => 'addrver.city', 'pincode' => 'addrver.pincode', 'state' => 'addrver.state', 'has_case_id' => 'addrver.has_case_id', 'verfstatus' => 'addrverres.verfstatus', 'filter_by_sub_status' => 'addrverres.verfstatus', 'mode_of_verification' => 'addrverres.mode_of_verification', 'closuredate_from' => 'addrverres.closuredate', 'closuredate_to' => 'addrverres.closuredate');

            $where = ' where 1 ';

            foreach ($filter as $key => $value) {

                switch ($key) {
                    case 'clientid':
                        if ($filter['clientid'] != 0) {
                            $where .= " and addrver.clientid = '" . $filter['clientid'] . "' ";
                        }
                        break;

                    case 'has_case_id':
                        if ($filter['has_case_id'] != 0) {
                            if ($this->user_info['tbl_roles_id'] != "1") {
                                $where .= " and addrver.has_case_id = '" . $this->user_info['id'] . "' ";
                            }
                        }
                        break;

                    case 'form_date':
                        if ($filter['form_date'] != '' || $filter['to_date'] != '') {
                            if ($filter['component_type'] == 'closed') {

                                $from = ($filter['form_date'] != '') ? convert_display_to_db_date($filter['form_date']) : '';
                                $to = ($filter['to_date'] != '') ? convert_display_to_db_date($filter['to_date']) : '';
                                $to = ($to) ? " '" . $to . "' " : 'CURRENT_DATE()';
                                $where .= " and addrverres.closuredate between '" . $from . "' and " . $to . " ";

                            }
                        }
                        break;

                    case 'component_type':
                        if ($filter['component_type'] != '') {

                            if ($filter['component_type'] == 'wip') {
                                $status_wip = array('11', '12', '13', '14', '16', '23', '26', '1');
                                $where .= " and addrverres.verfstatus in (" . implode(',', $status_wip) . ") ";
                            }
                            if ($filter['component_type'] == 'insufficiency') {
                                $status_insufficiency = array('18');
                                $where .= " and addrverres.verfstatus in (" . implode(',', $status_insufficiency) . ") ";
                            }
                            if ($filter['component_type'] == 'closed') {
                                $status_closed = array('9', '17', '19', '20', '21', '22', '24', '25', '27', '28');
                                $where .= " and addrverres.verfstatus in (" . implode(',', $status_closed) . ") ";
                            }
                        }
                        break;

                    default:
                        if (array_key_exists($key, $where_condition_arry) && $value != '') {
                            $where .= " and  " . $where_condition_arry[$key] . " = '" . $value . "' ";
                        }
                        break;
                }
            }

            $data['lists'] = $this->addressver_model->dashboard_sql($where);

            $json_array['message'] = $this->load->view('admin/address_dashboard_details', $data, true);

            $json_array['status'] = SUCCESS_CODE;
        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['status'] = "we are sorry but you don't have access to this service";

        }

        echo_json($json_array);
    }

    public function employment_dashboard_record()
    {
        $json_data = array();

        if ($this->input->is_ajax_request()) {
            $this->load->model('employment_model');

            $filter = $this->input->post();

            $where_condition_arry = array('clientid' => 'candidates_info.clientid', 'entity' => 'candidates_info.entity', 'package' => 'candidates_info.package', 'overallclosuredate_from' => 'candidates_info.caserecddate', 'overallclosuredate_to' => 'candidates_info.caserecddate', 'locationaddr' => 'empver.locationaddr', 'citylocality' => 'empver.citylocality', 'pincode' => 'empver.pincode', 'state' => 'empver.state', 'has_case_id' => 'empver.has_case_id', 'verfstatus' => 'empverres.verfstatus', 'filter_by_sub_status' => 'empverres.verfstatus', 'modeofverification' => 'empverres.modeofverification', 'closuredate_from' => 'empverres.closuredate', 'closuredate_to' => 'empverres.closuredate');

            $where = ' where 1 ';

            foreach ($filter as $key => $value) {

                switch ($key) {
                    case 'clientid':
                        if ($filter['clientid'] != 0) {
                            $where .= " and empver.clientid = '" . $filter['clientid'] . "' ";
                        }
                        break;

                    case 'has_case_id':
                        if ($filter['has_case_id'] != 0) {
                            if ($this->user_info['tbl_roles_id'] != "1") {
                                $where .= " and empver.has_case_id = '" . $this->user_info['id'] . "' ";
                            }
                        }
                        break;

                    case 'form_date':
                        if ($filter['form_date'] != '' || $filter['to_date'] != '') {
                            if ($filter['component_type'] == 'closed') {

                                $from = ($filter['form_date'] != '') ? convert_display_to_db_date($filter['form_date']) : '';
                                $to = ($filter['to_date'] != '') ? convert_display_to_db_date($filter['to_date']) : '';
                                $to = ($to) ? " '" . $to . "' " : 'CURRENT_DATE()';
                                $where .= " and empverres.closuredate between '" . $from . "' and " . $to . " ";

                            }
                        }
                        break;

                    case 'component_type':
                        if ($filter['component_type'] != '') {

                            if ($filter['component_type'] == 'wip') {
                                $status_wip = array('11', '12', '13', '14', '16', '23', '26', '1');
                                $where .= " and empverres.verfstatus in (" . implode(',', $status_wip) . ") ";
                            }
                            if ($filter['component_type'] == 'insufficiency') {
                                $status_insufficiency = array('18');
                                $where .= " and empverres.verfstatus in (" . implode(',', $status_insufficiency) . ") ";
                            }
                            if ($filter['component_type'] == 'closed') {
                                $status_closed = array('9', '17', '19', '20', '21', '22', '24', '25', '27', '28');
                                $where .= " and empverres.verfstatus in (" . implode(',', $status_closed) . ") ";
                            }
                        }
                        break;

                    default:
                        if (array_key_exists($key, $where_condition_arry) && $value != '') {
                            $where .= " and  " . $where_condition_arry[$key] . " = '" . $value . "' ";
                        }
                        break;
                }
            }

            $data['lists'] = $this->employment_model->dashboard_sql($where);

            $json_array['message'] = $this->load->view('admin/employment_dashboard_details', $data, true);

            $json_array['status'] = SUCCESS_CODE;
        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['status'] = "we are sorry but you don't have access to this service";

        }

        echo_json($json_array);
    }

    public function education_dashboard_record()
    {
        $json_data = array();

        if ($this->input->is_ajax_request()) {
            $this->load->model('education_model');

            $filter = $this->input->post();

            $where_condition_arry = array('clientid' => 'candidates_info.clientid', 'entity' => 'candidates_info.entity', 'package' => 'candidates_info.package', 'overallclosuredate_from' => 'candidates_info.caserecddate', 'overallclosuredate_to' => 'candidates_info.caserecddate', 'university_board' => 'education.university_board', 'qualification' => 'education.qualification', 'has_case_id' => 'education.has_case_id', 'verfstatus' => 'education_result.verfstatus', 'filter_by_sub_status' => 'education_result.verfstatus', 'res_mode_of_verification' => 'education_result.res_mode_of_verification', 'closuredate_from' => 'education_result.closuredate', 'closuredate_to' => 'education_result.closuredate');

            $where = ' where 1 ';

            foreach ($filter as $key => $value) {

                switch ($key) {
                    case 'clientid':
                        if ($filter['clientid'] != 0) {
                            $where .= " and education.clientid = '" . $filter['clientid'] . "' ";
                        }
                        break;

                    case 'has_case_id':
                        if ($filter['has_case_id'] != 0) {
                            if ($this->user_info['tbl_roles_id'] != "1") {
                                $where .= " and education.has_case_id = '" . $this->user_info['id'] . "' ";
                            }
                        }
                        break;

                    case 'form_date':
                        if ($filter['form_date'] != '' || $filter['to_date'] != '') {
                            if ($filter['component_type'] == 'closed') {

                                $from = ($filter['form_date'] != '') ? convert_display_to_db_date($filter['form_date']) : '';
                                $to = ($filter['to_date'] != '') ? convert_display_to_db_date($filter['to_date']) : '';
                                $to = ($to) ? " '" . $to . "' " : 'CURRENT_DATE()';
                                $where .= " and education_result.closuredate between '" . $from . "' and " . $to . " ";

                            }
                        }
                        break;

                    case 'component_type':
                        if ($filter['component_type'] != '') {

                            if ($filter['component_type'] == 'wip') {
                                $status_wip = array('11', '12', '13', '14', '16', '23', '26', '1');
                                $where .= " and education_result.verfstatus in (" . implode(',', $status_wip) . ") ";
                            }
                            if ($filter['component_type'] == 'insufficiency') {
                                $status_insufficiency = array('18');
                                $where .= " and education_result.verfstatus in (" . implode(',', $status_insufficiency) . ") ";
                            }
                            if ($filter['component_type'] == 'closed') {
                                $status_closed = array('9', '17', '19', '20', '21', '22', '24', '25', '27', '28');
                                $where .= " and education_result.verfstatus in (" . implode(',', $status_closed) . ") ";
                            }
                        }
                        break;

                    default:
                        if (array_key_exists($key, $where_condition_arry) && $value != '') {
                            $where .= " and  " . $where_condition_arry[$key] . " = '" . $value . "' ";
                        }
                        break;
                }
            }

            $data['lists'] = $this->education_model->dashboard_sql($where);

            $json_array['message'] = $this->load->view('admin/education_dashboard_details', $data, true);

            $json_array['status'] = SUCCESS_CODE;
        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['status'] = "we are sorry but you don't have access to this service";

        }

        echo_json($json_array);
    }

    public function reference_dashboard_record()
    {
        $json_data = array();

        if ($this->input->is_ajax_request()) {
            $this->load->model('reference_verificatiion_model');

            $filter = $this->input->post();

            $where_condition_arry = array('clientid' => 'candidates_info.clientid', 'entity' => 'candidates_info.entity', 'package' => 'candidates_info.package', 'overallclosuredate_from' => 'candidates_info.caserecddate', 'overallclosuredate_to' => 'candidates_info.caserecddate', 'has_case_id' => 'reference.has_case_id', 'verfstatus' => 'reference_result.verfstatus', 'filter_by_sub_status' => 'reference_result.verfstatus', 'closuredate_from' => 'reference_result.closuredate', 'closuredate_to' => 'reference_result.closuredate');

            $where = ' where 1 ';

            foreach ($filter as $key => $value) {

                switch ($key) {
                    case 'clientid':
                        if ($filter['clientid'] != 0) {
                            $where .= " and reference.clientid = '" . $filter['clientid'] . "' ";
                        }
                        break;

                    case 'has_case_id':
                        if ($filter['has_case_id'] != 0) {
                            if ($this->user_info['tbl_roles_id'] != "1") {
                                $where .= " and reference.has_case_id = '" . $this->user_info['id'] . "' ";
                            }
                        }
                        break;

                    case 'form_date':
                        if ($filter['form_date'] != '' || $filter['to_date'] != '') {
                            if ($filter['component_type'] == 'closed') {

                                $from = ($filter['form_date'] != '') ? convert_display_to_db_date($filter['form_date']) : '';
                                $to = ($filter['to_date'] != '') ? convert_display_to_db_date($filter['to_date']) : '';
                                $to = ($to) ? " '" . $to . "' " : 'CURRENT_DATE()';
                                $where .= " and reference_result.closuredate between '" . $from . "' and " . $to . " ";

                            }
                        }
                        break;

                    case 'component_type':
                        if ($filter['component_type'] != '') {

                            if ($filter['component_type'] == 'wip') {
                                $status_wip = array('11', '12', '13', '14', '16', '23', '26', '1');
                                $where .= " and reference_result.verfstatus in (" . implode(',', $status_wip) . ") ";
                            }
                            if ($filter['component_type'] == 'insufficiency') {
                                $status_insufficiency = array('18');
                                $where .= " and reference_result.verfstatus in (" . implode(',', $status_insufficiency) . ") ";
                            }
                            if ($filter['component_type'] == 'closed') {
                                $status_closed = array('9', '17', '19', '20', '21', '22', '24', '25', '27', '28');
                                $where .= " and reference_result.verfstatus in (" . implode(',', $status_closed) . ") ";
                            }
                        }
                        break;

                    default:
                        if (array_key_exists($key, $where_condition_arry) && $value != '') {
                            $where .= " and  " . $where_condition_arry[$key] . " = '" . $value . "' ";
                        }
                        break;
                }
            }

            $data['lists'] = $this->reference_verificatiion_model->dashboard_sql($where);

            $json_array['message'] = $this->load->view('admin/reference_dashboard_details', $data, true);

            $json_array['status'] = SUCCESS_CODE;
        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['status'] = "we are sorry but you don't have access to this service";

        }

        echo_json($json_array);
    }

    public function court_dashboard_record()
    {
        $json_data = array();

        if ($this->input->is_ajax_request()) {
            $this->load->model('court_verificatiion_model');

            $filter = $this->input->post();

            $where_condition_arry = array('clientid' => 'candidates_info.clientid', 'entity' => 'candidates_info.entity', 'package' => 'candidates_info.package', 'overallclosuredate_from' => 'candidates_info.caserecddate', 'overallclosuredate_to' => 'candidates_info.caserecddate', 'address_type' => 'address_type', 'street_address' => 'street_address', 'city' => 'city', 'pincode' => 'pincode', 'state' => 'state', 'has_case_id' => 'has_case_id', 'verfstatus' => 'verfstatus', 'filter_by_sub_status' => 'verfstatus', 'mode_of_verification' => 'mode_of_verification', 'closuredate_from' => 'closuredate', 'closuredate_to' => 'closuredate');

            $where = ' where 1 ';

            foreach ($filter as $key => $value) {

                switch ($key) {
                    case 'clientid':
                        if ($filter['clientid'] != 0) {
                            $where .= " and courtver.clientid = '" . $filter['clientid'] . "' ";
                        }
                        break;

                    case 'has_case_id':
                        if ($filter['has_case_id'] != 0) {
                            if ($this->user_info['tbl_roles_id'] != "1") {
                                $where .= " and courtver.has_case_id = '" . $this->user_info['id'] . "' ";
                            }
                        }
                        break;

                    case 'form_date':
                        if ($filter['form_date'] != '' || $filter['to_date'] != '') {
                            if ($filter['component_type'] == 'closed') {

                                $from = ($filter['form_date'] != '') ? convert_display_to_db_date($filter['form_date']) : '';
                                $to = ($filter['to_date'] != '') ? convert_display_to_db_date($filter['to_date']) : '';
                                $to = ($to) ? " '" . $to . "' " : 'CURRENT_DATE()';
                                $where .= " and courtver_result.closuredate between '" . $from . "' and " . $to . " ";

                            }
                        }
                        break;

                    case 'component_type':
                        if ($filter['component_type'] != '') {

                            if ($filter['component_type'] == 'wip') {
                                $status_wip = array('11', '12', '13', '14', '16', '23', '26', '1');
                                $where .= " and courtver_result.verfstatus in (" . implode(',', $status_wip) . ") ";
                            }
                            if ($filter['component_type'] == 'insufficiency') {
                                $status_insufficiency = array('18');
                                $where .= " and courtver_result.verfstatus in (" . implode(',', $status_insufficiency) . ") ";
                            }
                            if ($filter['component_type'] == 'closed') {
                                $status_closed = array('9', '17', '19', '20', '21', '22', '24', '25', '27', '28');
                                $where .= " and courtver_result.verfstatus in (" . implode(',', $status_closed) . ") ";
                            }
                        }
                        break;

                    default:
                        if (array_key_exists($key, $where_condition_arry) && $value != '') {
                            $where .= " and  " . $where_condition_arry[$key] . " = '" . $value . "' ";
                        }
                        break;
                }
            }

            $data['lists'] = $this->court_verificatiion_model->dashboard_sql($where);

            $json_array['message'] = $this->load->view('admin/court_dashboard_details', $data, true);

            $json_array['status'] = SUCCESS_CODE;
        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['status'] = "we are sorry but you don't have access to this service";

        }

        echo_json($json_array);
    }

    public function global_database_dashboard_record()
    {
        $json_data = array();

        if ($this->input->is_ajax_request()) {
            $this->load->model('global_database_model');

            $filter = $this->input->post();

            $where_condition_arry = array('clientid' => 'candidates_info.clientid', 'entity' => 'candidates_info.entity', 'package' => 'candidates_info.package', 'overallclosuredate_from' => 'candidates_info.caserecddate', 'overallclosuredate_to' => 'candidates_info.caserecddate', 'address_type' => 'address_type', 'street_address' => 'street_address', 'city' => 'city', 'pincode' => 'pincode', 'state' => 'state', 'has_case_id' => 'has_case_id', 'verfstatus' => 'verfstatus', 'filter_by_sub_status' => 'verfstatus', 'mode_of_verification' => 'mode_of_verification', 'closuredate_from' => 'closuredate', 'closuredate_to' => 'closuredate');

            $where = ' where 1 ';

            foreach ($filter as $key => $value) {

                switch ($key) {
                    case 'clientid':
                        if ($filter['clientid'] != 0) {
                            $where .= " and glodbver.clientid = '" . $filter['clientid'] . "' ";
                        }
                        break;

                    case 'has_case_id':
                        if ($filter['has_case_id'] != 0) {
                            if ($this->user_info['tbl_roles_id'] != "1") {
                                $where .= " and glodbver.has_case_id = '" . $this->user_info['id'] . "' ";
                            }
                        }
                        break;

                    case 'form_date':
                        if ($filter['form_date'] != '' || $filter['to_date'] != '') {
                            if ($filter['component_type'] == 'closed') {

                                $from = ($filter['form_date'] != '') ? convert_display_to_db_date($filter['form_date']) : '';
                                $to = ($filter['to_date'] != '') ? convert_display_to_db_date($filter['to_date']) : '';
                                $to = ($to) ? " '" . $to . "' " : 'CURRENT_DATE()';
                                $where .= " and glodbver_result.closuredate between '" . $from . "' and " . $to . " ";

                            }
                        }
                        break;

                    case 'component_type':
                        if ($filter['component_type'] != '') {

                            if ($filter['component_type'] == 'wip') {
                                $status_wip = array('11', '12', '13', '14', '16', '23', '26', '1');
                                $where .= " and glodbver_result.verfstatus in (" . implode(',', $status_wip) . ") ";
                            }
                            if ($filter['component_type'] == 'insufficiency') {
                                $status_insufficiency = array('18');
                                $where .= " and glodbver_result.verfstatus in (" . implode(',', $status_insufficiency) . ") ";
                            }
                            if ($filter['component_type'] == 'closed') {
                                $status_closed = array('9', '17', '19', '20', '21', '22', '24', '25', '27', '28');
                                $where .= " and glodbver_result.verfstatus in (" . implode(',', $status_closed) . ") ";
                            }
                        }
                        break;

                    default:
                        if (array_key_exists($key, $where_condition_arry) && $value != '') {
                            $where .= " and  " . $where_condition_arry[$key] . " = '" . $value . "' ";
                        }
                        break;
                }
            }

            $data['lists'] = $this->global_database_model->dashboard_sql($where);

            $json_array['message'] = $this->load->view('admin/global_dashboard_details', $data, true);

            $json_array['status'] = SUCCESS_CODE;
        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['status'] = "we are sorry but you don't have access to this service";

        }

        echo_json($json_array);
    }

    public function identity_dashboard_record()
    {
        $json_data = array();

        if ($this->input->is_ajax_request()) {
            $this->load->model('identity_model');

            $filter = $this->input->post();

            $where_condition_arry = array('clientid' => 'candidates_info.clientid', 'entity' => 'candidates_info.entity', 'package' => 'candidates_info.package', 'overallclosuredate_from' => 'candidates_info.caserecddate', 'overallclosuredate_to' => 'candidates_info.caserecddate', 'doc_submited' => 'doc_submited', 'id_number' => 'identity.id_number', 'has_case_id' => 'identity.has_case_id', 'verfstatus' => 'identity_result.verfstatus', 'filter_by_sub_status' => 'identity_result.verfstatus', 'mode_of_verification' => 'identity.mode_of_veri', 'closuredate_from' => 'identity_result.closuredate', 'closuredate_to' => 'identity_result.closuredate');

            $where = ' where 1 ';

            foreach ($filter as $key => $value) {

                switch ($key) {
                    case 'clientid':
                        if ($filter['clientid'] != 0) {
                            $where .= " and identity.clientid = '" . $filter['clientid'] . "' ";
                        }
                        break;

                    case 'has_case_id':
                        if ($filter['has_case_id'] != 0) {
                            if ($this->user_info['tbl_roles_id'] != "1") {
                                $where .= " and identity.has_case_id = '" . $this->user_info['id'] . "' ";
                            }
                        }
                        break;

                    case 'form_date':
                        if ($filter['form_date'] != '' || $filter['to_date'] != '') {
                            if ($filter['component_type'] == 'closed') {

                                $from = ($filter['form_date'] != '') ? convert_display_to_db_date($filter['form_date']) : '';
                                $to = ($filter['to_date'] != '') ? convert_display_to_db_date($filter['to_date']) : '';
                                $to = ($to) ? " '" . $to . "' " : 'CURRENT_DATE()';
                                $where .= " and identity_result.closuredate between '" . $from . "' and " . $to . " ";

                            }
                        }
                        break;

                    case 'component_type':
                        if ($filter['component_type'] != '') {

                            if ($filter['component_type'] == 'wip') {
                                $status_wip = array('11', '12', '13', '14', '16', '23', '26', '1');
                                $where .= " and identity_result.verfstatus in (" . implode(',', $status_wip) . ") ";
                            }
                            if ($filter['component_type'] == 'insufficiency') {
                                $status_insufficiency = array('18');
                                $where .= " and identity_result.verfstatus in (" . implode(',', $status_insufficiency) . ") ";
                            }
                            if ($filter['component_type'] == 'closed') {
                                $status_closed = array('9', '17', '19', '20', '21', '22', '24', '25', '27', '28');
                                $where .= " and identity_result.verfstatus in (" . implode(',', $status_closed) . ") ";
                            }
                        }
                        break;

                    default:
                        if (array_key_exists($key, $where_condition_arry) && $value != '') {
                            $where .= " and  " . $where_condition_arry[$key] . " = '" . $value . "' ";
                        }
                        break;
                }
            }

            $data['lists'] = $this->identity_model->dashboard_sql($where);

            $json_array['message'] = $this->load->view('admin/identity_dashboard_details', $data, true);

            $json_array['status'] = SUCCESS_CODE;
        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['status'] = "we are sorry but you don't have access to this service";

        }

        echo_json($json_array);
    }

    public function pcc_dashboard_record()
    {
        $json_data = array();

        if ($this->input->is_ajax_request()) {
            $this->load->model('pcc_verificatiion_model');

            $filter = $this->input->post();

            $where_condition_arry = array('clientid' => 'candidates_info.clientid', 'entity' => 'candidates_info.entity', 'package' => 'candidates_info.package', 'overallclosuredate_from' => 'candidates_info.caserecddate', 'overallclosuredate_to' => 'candidates_info.caserecddate', 'street_address' => 'pcc.street_address', 'city' => 'pcc.city', 'pincode' => 'pcc.pincode', 'state' => 'pcc.state', 'has_case_id' => 'pcc.has_case_id', 'verfstatus' => 'pcc_result.verfstatus', 'filter_by_sub_status' => 'pcc_result.verfstatus', 'mode_of_verification' => 'pcc.mode_of_veri', 'closuredate_from' => 'pcc_result.closuredate', 'closuredate_to' => 'pcc_result.closuredate');

            $where = ' where 1 ';

            foreach ($filter as $key => $value) {

                switch ($key) {
                    case 'clientid':
                        if ($filter['clientid'] != 0) {
                            $where .= " and pcc.clientid = '" . $filter['clientid'] . "' ";
                        }
                        break;

                    case 'has_case_id':
                        if ($filter['has_case_id'] != 0) {
                            if ($this->user_info['tbl_roles_id'] != "1") {
                                $where .= " and pcc.has_case_id = '" . $this->user_info['id'] . "' ";
                            }
                        }
                        break;

                    case 'form_date':
                        if ($filter['form_date'] != '' || $filter['to_date'] != '') {
                            if ($filter['component_type'] == 'closed') {

                                $from = ($filter['form_date'] != '') ? convert_display_to_db_date($filter['form_date']) : '';
                                $to = ($filter['to_date'] != '') ? convert_display_to_db_date($filter['to_date']) : '';
                                $to = ($to) ? " '" . $to . "' " : 'CURRENT_DATE()';
                                $where .= " and pcc_result.closuredate between '" . $from . "' and " . $to . " ";

                            }
                        }
                        break;

                    case 'component_type':
                        if ($filter['component_type'] != '') {

                            if ($filter['component_type'] == 'wip') {
                                $status_wip = array('11', '12', '13', '14', '16', '23', '26', '1');
                                $where .= " and pcc_result.verfstatus in (" . implode(',', $status_wip) . ") ";
                            }
                            if ($filter['component_type'] == 'insufficiency') {
                                $status_insufficiency = array('18');
                                $where .= " and pcc_result.verfstatus in (" . implode(',', $status_insufficiency) . ") ";
                            }
                            if ($filter['component_type'] == 'closed') {
                                $status_closed = array('9', '17', '19', '20', '21', '22', '24', '25', '27', '28');
                                $where .= " and pcc_result.verfstatus in (" . implode(',', $status_closed) . ") ";
                            }
                        }
                        break;

                    default:
                        if (array_key_exists($key, $where_condition_arry) && $value != '') {
                            $where .= " and  " . $where_condition_arry[$key] . " = '" . $value . "' ";
                        }
                        break;
                }
            }

            $data['lists'] = $this->pcc_verificatiion_model->dashboard_sql($where);

            $json_array['message'] = $this->load->view('admin/pcc_dashboard_details', $data, true);

            $json_array['status'] = SUCCESS_CODE;
        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['status'] = "we are sorry but you don't have access to this service";

        }

        echo_json($json_array);
    }

    public function credit_report_dashboard_record()
    {
        $json_data = array();

        if ($this->input->is_ajax_request()) {
            $this->load->model('credit_report_model');

            $filter = $this->input->post();

            $where_condition_arry = array('clientid' => 'candidates_info.clientid', 'entity' => 'candidates_info.entity', 'package' => 'candidates_info.package', 'overallclosuredate_from' => 'candidates_info.caserecddate', 'overallclosuredate_to' => 'candidates_info.caserecddate', 'doc_submited' => 'doc_submited', 'id_number' => 'credit_report.id_number', 'has_case_id' => 'credit_report.has_case_id', 'verfstatus' => 'credit_report_result.verfstatus', 'filter_by_sub_status' => 'credit_report_result.verfstatus', 'mode_of_verification' => 'credit_report.mode_of_veri', 'closuredate_from' => 'credit_report_result.closuredate', 'closuredate_to' => 'credit_report_result.closuredate');

            $where = ' where 1 ';

            foreach ($filter as $key => $value) {

                switch ($key) {
                    case 'clientid':
                        if ($filter['clientid'] != 0) {
                            $where .= " and credit_report.clientid = '" . $filter['clientid'] . "' ";
                        }
                        break;

                    case 'has_case_id':
                        if ($filter['has_case_id'] != 0) {
                            if ($this->user_info['tbl_roles_id'] != "1") {
                                $where .= " and credit_report.has_case_id = '" . $this->user_info['id'] . "' ";
                            }
                        }
                        break;

                    case 'form_date':
                        if ($filter['form_date'] != '' || $filter['to_date'] != '') {
                            if ($filter['component_type'] == 'closed') {

                                $from = ($filter['form_date'] != '') ? convert_display_to_db_date($filter['form_date']) : '';
                                $to = ($filter['to_date'] != '') ? convert_display_to_db_date($filter['to_date']) : '';
                                $to = ($to) ? " '" . $to . "' " : 'CURRENT_DATE()';
                                $where .= " and credit_report_result.closuredate between '" . $from . "' and " . $to . " ";

                            }
                        }
                        break;

                    case 'component_type':
                        if ($filter['component_type'] != '') {

                            if ($filter['component_type'] == 'wip') {
                                $status_wip = array('11', '12', '13', '14', '16', '23', '26', '1');
                                $where .= " and credit_report_result.verfstatus in (" . implode(',', $status_wip) . ") ";
                            }
                            if ($filter['component_type'] == 'insufficiency') {
                                $status_insufficiency = array('18');
                                $where .= " and credit_report_result.verfstatus in (" . implode(',', $status_insufficiency) . ") ";
                            }
                            if ($filter['component_type'] == 'closed') {
                                $status_closed = array('9', '17', '19', '20', '21', '22', '24', '25', '27', '28');
                                $where .= " and credit_report_result.verfstatus in (" . implode(',', $status_closed) . ") ";
                            }
                        }
                        break;

                    default:
                        if (array_key_exists($key, $where_condition_arry) && $value != '') {
                            $where .= " and  " . $where_condition_arry[$key] . " = '" . $value . "' ";
                        }
                        break;
                }
            }

            $data['lists'] = $this->credit_report_model->dashboard_sql($where);

            $json_array['message'] = $this->load->view('admin/credit_report_dashboard_details', $data, true);

            $json_array['status'] = SUCCESS_CODE;
        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['status'] = "we are sorry but you don't have access to this service";

        }

        echo_json($json_array);
    }

    public function drugs_dashboard_record()
    {
        $json_data = array();

        if ($this->input->is_ajax_request()) {
            $this->load->model('drug_verificatiion_model');

            $filter = $this->input->post();

            $where_condition_arry = array('clientid' => 'candidates_info.clientid', 'entity' => 'candidates_info.entity', 'package' => 'candidates_info.package', 'overallclosuredate_from' => 'candidates_info.caserecddate', 'overallclosuredate_to' => 'candidates_info.caserecddate', 'street_address' => 'drug_narcotis.street_address', 'city' => 'drug_narcotis.city', 'pincode' => 'drug_narcotis.pincode', 'state' => 'drug_narcotis.state', 'has_case_id' => 'drug_narcotis.has_case_id', 'verfstatus' => 'drug_narcotis_result.verfstatus', 'filter_by_sub_status' => 'drug_narcotis_result.verfstatus', 'mode_of_verification' => 'drug_narcotis.mode_of_veri', 'closuredate_from' => 'drug_narcotis_result.closuredate', 'closuredate_to' => 'drug_narcotis_result.closuredate');

            $where = ' where 1 ';

            foreach ($filter as $key => $value) {

                switch ($key) {
                    case 'clientid':
                        if ($filter['clientid'] != 0) {
                            $where .= " and drug_narcotis.clientid = '" . $filter['clientid'] . "' ";
                        }
                        break;

                    case 'has_case_id':
                        if ($filter['has_case_id'] != 0) {
                            if ($this->user_info['tbl_roles_id'] != "1") {
                                $where .= " and drug_narcotis.has_case_id = '" . $this->user_info['id'] . "' ";
                            }
                        }
                        break;

                    case 'form_date':
                        if ($filter['form_date'] != '' || $filter['to_date'] != '') {
                            if ($filter['component_type'] == 'closed') {

                                $from = ($filter['form_date'] != '') ? convert_display_to_db_date($filter['form_date']) : '';
                                $to = ($filter['to_date'] != '') ? convert_display_to_db_date($filter['to_date']) : '';
                                $to = ($to) ? " '" . $to . "' " : 'CURRENT_DATE()';
                                $where .= " and drug_narcotis_result.closuredate between '" . $from . "' and " . $to . " ";

                            }
                        }
                        break;

                    case 'component_type':
                        if ($filter['component_type'] != '') {

                            if ($filter['component_type'] == 'wip') {
                                $status_wip = array('11', '12', '13', '14', '16', '23', '26', '1');
                                $where .= " and drug_narcotis_result.verfstatus in (" . implode(',', $status_wip) . ") ";
                            }
                            if ($filter['component_type'] == 'insufficiency') {
                                $status_insufficiency = array('18');
                                $where .= " and drug_narcotis_result.verfstatus in (" . implode(',', $status_insufficiency) . ") ";
                            }
                            if ($filter['component_type'] == 'closed') {
                                $status_closed = array('9', '17', '19', '20', '21', '22', '24', '25', '27', '28');
                                $where .= " and drug_narcotis_result.verfstatus in (" . implode(',', $status_closed) . ") ";
                            }
                        }
                        break;

                    default:
                        if (array_key_exists($key, $where_condition_arry) && $value != '') {
                            $where .= " and  " . $where_condition_arry[$key] . " = '" . $value . "' ";
                        }
                        break;
                }
            }

            $data['lists'] = $this->drug_verificatiion_model->dashboard_sql($where);

            $json_array['message'] = $this->load->view('admin/drugs_dashboard_details', $data, true);

            $json_array['status'] = SUCCESS_CODE;
        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['status'] = "we are sorry but you don't have access to this service";

        }

        echo_json($json_array);
    }

    public function address_dashboard_record_tat()
    {
        $json_data = array();

        if ($this->input->is_ajax_request()) {
            $this->load->model('addressver_model');

            $filter = $this->input->post();

            $where_condition_arry = array('clientid' => 'candidates_info.clientid', 'entity' => 'candidates_info.entity', 'package' => 'candidates_info.package', 'overallclosuredate_from' => 'candidates_info.caserecddate', 'overallclosuredate_to' => 'candidates_info.caserecddate', 'address_type' => 'address_type', 'address' => 'addrver.address', 'city' => 'addrver.city', 'pincode' => 'addrver.pincode', 'state' => 'addrver.state', 'has_case_id' => 'addrver.has_case_id', 'verfstatus' => 'addrverres.verfstatus', 'filter_by_sub_status' => 'addrverres.verfstatus', 'mode_of_verification' => 'addrverres.mode_of_verification', 'closuredate_from' => 'addrverres.closuredate', 'closuredate_to' => 'addrverres.closuredate');

            $where = ' where 1 ';

            foreach ($filter as $key => $value) {

                switch ($key) {
                    case 'has_case_id':
                        if ($filter['has_case_id'] != 0) {
                            if ($this->user_info['tbl_roles_id'] != "1") {
                                $where .= " and addrver.has_case_id = '" . $this->user_info['id'] . "' ";
                            }
                        }

                        break;

                    case 'component_type':
                        if ($filter['component_type'] != '') {

                            if ($filter['component_type'] == 'today') {
                                $where .= " and ((addrver.tat_status = 'tdy tat') or (addrver.tat_status = 'TDY TAT'))";
                            }
                            if ($filter['component_type'] == 'app') {
                                $where .= " and ((addrver.tat_status = 'ap tat') or (addrver.tat_status = 'AP TAT'))";
                            }
                            if ($filter['component_type'] == 'in') {
                                $where .= " and ((addrver.tat_status = 'in tat') or (addrver.tat_status = 'IN TAT'))";
                            }
                            if ($filter['component_type'] == 'out') {
                                $where .= " and ((addrver.tat_status = 'out tat') or (addrver.tat_status = 'OUT TAT'))";
                            }

                            $group_id = $this->db->query("select group_concat(id) as wip_filter from status where filter_status = 'WIP'");

                            $res_filter = $group_id->result_array()[0];

                            $filter = explode(',', $res_filter['wip_filter']);

                            $where .= " and addrverres.verfstatus in (" . implode(',', $filter) . ") ";
                        }
                        break;

                    default:
                        if (array_key_exists($key, $where_condition_arry) && $value != '') {
                            $where .= " and  " . $where_condition_arry[$key] . " = '" . $value . "' ";
                        }
                        break;
                }
            }

            $data['lists'] = $this->addressver_model->dashboard_sql($where);

            $json_array['message'] = $this->load->view('admin/address_dashboard_details', $data, true);

            $json_array['status'] = SUCCESS_CODE;
        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['status'] = "we are sorry but you don't have access to this service";

        }

        echo_json($json_array);
    }

    public function employment_dashboard_record_tat()
    {
        $json_data = array();

        if ($this->input->is_ajax_request()) {
            $this->load->model('employment_model');

            $filter = $this->input->post();

            $where_condition_arry = array('clientid' => 'candidates_info.clientid', 'entity' => 'candidates_info.entity', 'package' => 'candidates_info.package', 'overallclosuredate_from' => 'candidates_info.caserecddate', 'overallclosuredate_to' => 'candidates_info.caserecddate', 'locationaddr' => 'empver.locationaddr', 'citylocality' => 'empver.citylocality', 'pincode' => 'empver.pincode', 'state' => 'empver.state', 'has_case_id' => 'empver.has_case_id', 'verfstatus' => 'empverres.verfstatus', 'filter_by_sub_status' => 'empverres.verfstatus', 'modeofverification' => 'empverres.modeofverification', 'closuredate_from' => 'empverres.closuredate', 'closuredate_to' => 'empverres.closuredate');

            $where = ' where 1 ';

            foreach ($filter as $key => $value) {

                switch ($key) {

                    case 'has_case_id':
                        if ($filter['has_case_id'] != 0) {
                            if ($this->user_info['tbl_roles_id'] != "1") {
                                $where .= " and empver.has_case_id = '" . $this->user_info['id'] . "' ";
                            }
                        }

                        break;

                    case 'component_type':
                        if ($filter['component_type'] != '') {

                            if ($filter['component_type'] == 'today') {
                                $where .= " and ((empver.tat_status = 'tdy tat') or (empver.tat_status = 'TDY TAT'))";
                            }
                            if ($filter['component_type'] == 'app') {
                                $where .= " and ((empver.tat_status = 'ap tat') or (empver.tat_status = 'AP TAT'))";
                            }
                            if ($filter['component_type'] == 'in') {
                                $where .= " and ((empver.tat_status = 'in tat') or (empver.tat_status = 'IN TAT'))";
                            }
                            if ($filter['component_type'] == 'out') {
                                $where .= " and ((empver.tat_status = 'out tat') or (empver.tat_status = 'OUT TAT'))";
                            }

                            $group_id = $this->db->query("select group_concat(id) as wip_filter from status where filter_status = 'WIP'");

                            $res_filter = $group_id->result_array()[0];

                            $filter = explode(',', $res_filter['wip_filter']);

                            $where .= " and empverres.verfstatus in (" . implode(',', $filter) . ") ";
                        }
                        break;

                    default:
                        if (array_key_exists($key, $where_condition_arry) && $value != '') {
                            $where .= " and  " . $where_condition_arry[$key] . " = '" . $value . "' ";
                        }
                        break;
                }
            }

            $data['lists'] = $this->employment_model->dashboard_sql($where);

            $json_array['message'] = $this->load->view('admin/employment_dashboard_details', $data, true);

            $json_array['status'] = SUCCESS_CODE;
        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['status'] = "we are sorry but you don't have access to this service";

        }

        echo_json($json_array);
    }

    public function education_dashboard_record_tat()
    {
        $json_data = array();

        if ($this->input->is_ajax_request()) {
            $this->load->model('education_model');

            $filter = $this->input->post();

            $where_condition_arry = array('clientid' => 'candidates_info.clientid', 'entity' => 'candidates_info.entity', 'package' => 'candidates_info.package', 'overallclosuredate_from' => 'candidates_info.caserecddate', 'overallclosuredate_to' => 'candidates_info.caserecddate', 'university_board' => 'education.university_board', 'qualification' => 'education.qualification', 'has_case_id' => 'education.has_case_id', 'verfstatus' => 'education_result.verfstatus', 'filter_by_sub_status' => 'education_result.verfstatus', 'res_mode_of_verification' => 'education_result.res_mode_of_verification', 'closuredate_from' => 'education_result.closuredate', 'closuredate_to' => 'education_result.closuredate');

            $where = ' where 1 ';

            foreach ($filter as $key => $value) {

                switch ($key) {

                    case 'has_case_id':
                        if ($filter['has_case_id'] != 0) {
                            if ($this->user_info['tbl_roles_id'] != "1") {
                                $where .= " and education.has_case_id = '" . $this->user_info['id'] . "' ";
                            }
                        }

                        break;

                    case 'component_type':

                        if ($filter['component_type'] != '') {

                            if ($filter['component_type'] == 'today') {
                                $where .= " and ((education.tat_status = 'tdy tat') or (education.tat_status = 'TDY TAT'))";
                            }
                            if ($filter['component_type'] == 'app') {
                                $where .= " and ((education.tat_status = 'ap tat') or (education.tat_status = 'AP TAT'))";
                            }
                            if ($filter['component_type'] == 'in') {
                                $where .= " and ((education.tat_status = 'in tat') or (education.tat_status = 'IN TAT'))";
                            }
                            if ($filter['component_type'] == 'out') {
                                $where .= " and ((education.tat_status = 'out tat') or (education.tat_status = 'OUT TAT'))";
                            }

                            $group_id = $this->db->query("select group_concat(id) as wip_filter from status where filter_status = 'WIP'");

                            $res_filter = $group_id->result_array()[0];

                            $filter = explode(',', $res_filter['wip_filter']);

                            $where .= " and education_result.verfstatus in (" . implode(',', $filter) . ") ";
                        }
                        break;

                    default:
                        if (array_key_exists($key, $where_condition_arry) && $value != '') {
                            $where .= " and  " . $where_condition_arry[$key] . " = '" . $value . "' ";
                        }
                        break;
                }
            }

            $data['lists'] = $this->education_model->dashboard_sql($where);

            $json_array['message'] = $this->load->view('admin/education_dashboard_details', $data, true);

            $json_array['status'] = SUCCESS_CODE;
        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['status'] = "we are sorry but you don't have access to this service";

        }

        echo_json($json_array);
    }

    public function reference_dashboard_record_tat()
    {
        $json_data = array();

        if ($this->input->is_ajax_request()) {
            $this->load->model('reference_verificatiion_model');

            $filter = $this->input->post();

            $where_condition_arry = array('clientid' => 'candidates_info.clientid', 'entity' => 'candidates_info.entity', 'package' => 'candidates_info.package', 'overallclosuredate_from' => 'candidates_info.caserecddate', 'overallclosuredate_to' => 'candidates_info.caserecddate', 'has_case_id' => 'reference.has_case_id', 'verfstatus' => 'reference_result.verfstatus', 'filter_by_sub_status' => 'reference_result.verfstatus', 'closuredate_from' => 'reference_result.closuredate', 'closuredate_to' => 'reference_result.closuredate');

            $where = ' where 1 ';

            foreach ($filter as $key => $value) {

                switch ($key) {

                    case 'has_case_id':
                        if ($filter['has_case_id'] != 0) {
                            if ($this->user_info['tbl_roles_id'] != "1") {
                                $where .= " and reference.has_case_id = '" . $this->user_info['id'] . "' ";
                            }
                        }

                        break;

                    case 'component_type':

                        if ($filter['component_type'] != '') {

                            if ($filter['component_type'] == 'today') {
                                $where .= " and ((reference.tat_status = 'tdy tat') or (reference.tat_status = 'TDY TAT'))";
                            }
                            if ($filter['component_type'] == 'app') {
                                $where .= " and ((reference.tat_status = 'ap tat') or (reference.tat_status = 'AP TAT'))";
                            }
                            if ($filter['component_type'] == 'in') {
                                $where .= " and ((reference.tat_status = 'in tat') or (reference.tat_status = 'IN TAT'))";
                            }
                            if ($filter['component_type'] == 'out') {
                                $where .= " and ((reference.tat_status = 'out tat') or (reference.tat_status = 'OUT TAT'))";
                            }

                            $group_id = $this->db->query("select group_concat(id) as wip_filter from status where filter_status = 'WIP'");

                            $res_filter = $group_id->result_array()[0];

                            $filter = explode(',', $res_filter['wip_filter']);

                            $where .= " and reference_result.verfstatus in (" . implode(',', $filter) . ") ";
                        }
                        break;

                    default:
                        if (array_key_exists($key, $where_condition_arry) && $value != '') {
                            $where .= " and  " . $where_condition_arry[$key] . " = '" . $value . "' ";
                        }
                        break;
                }
            }

            $data['lists'] = $this->reference_verificatiion_model->dashboard_sql($where);

            $json_array['message'] = $this->load->view('admin/reference_dashboard_details', $data, true);

            $json_array['status'] = SUCCESS_CODE;
        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['status'] = "we are sorry but you don't have access to this service";

        }

        echo_json($json_array);
    }

    public function court_dashboard_record_tat()
    {
        $json_data = array();

        if ($this->input->is_ajax_request()) {
            $this->load->model('court_verificatiion_model');

            $filter = $this->input->post();

            $where_condition_arry = array('clientid' => 'candidates_info.clientid', 'entity' => 'candidates_info.entity', 'package' => 'candidates_info.package', 'overallclosuredate_from' => 'candidates_info.caserecddate', 'overallclosuredate_to' => 'candidates_info.caserecddate', 'address_type' => 'address_type', 'street_address' => 'street_address', 'city' => 'city', 'pincode' => 'pincode', 'state' => 'state', 'has_case_id' => 'has_case_id', 'verfstatus' => 'verfstatus', 'filter_by_sub_status' => 'verfstatus', 'mode_of_verification' => 'mode_of_verification', 'closuredate_from' => 'closuredate', 'closuredate_to' => 'closuredate');

            $where = ' where 1 ';

            foreach ($filter as $key => $value) {

                switch ($key) {

                    case 'has_case_id':
                        if ($filter['has_case_id'] != 0) {
                            if ($this->user_info['tbl_roles_id'] != "1") {
                                $where .= " and courtver.has_case_id = '" . $this->user_info['id'] . "' ";
                            }
                        }

                        break;

                    case 'component_type':

                        if ($filter['component_type'] != '') {

                            if ($filter['component_type'] == 'today') {
                                $where .= " and ((courtver.tat_status = 'tdy tat') or (courtver.tat_status = 'TDY TAT'))";
                            }
                            if ($filter['component_type'] == 'app') {
                                $where .= " and ((courtver.tat_status = 'ap tat') or (courtver.tat_status = 'AP TAT'))";
                            }
                            if ($filter['component_type'] == 'in') {
                                $where .= " and ((courtver.tat_status = 'in tat') or (courtver.tat_status = 'IN TAT'))";
                            }
                            if ($filter['component_type'] == 'out') {
                                $where .= " and ((courtver.tat_status = 'out tat') or (courtver.tat_status = 'OUT TAT'))";
                            }

                            $group_id = $this->db->query("select group_concat(id) as wip_filter from status where filter_status = 'WIP'");

                            $res_filter = $group_id->result_array()[0];

                            $filter = explode(',', $res_filter['wip_filter']);

                            $where .= " and courtver_result.verfstatus in (" . implode(',', $filter) . ") ";
                        }
                        break;

                    default:
                        if (array_key_exists($key, $where_condition_arry) && $value != '') {
                            $where .= " and  " . $where_condition_arry[$key] . " = '" . $value . "' ";
                        }
                        break;
                }
            }

            $data['lists'] = $this->court_verificatiion_model->dashboard_sql($where);

            $json_array['message'] = $this->load->view('admin/court_dashboard_details', $data, true);

            $json_array['status'] = SUCCESS_CODE;
        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['status'] = "we are sorry but you don't have access to this service";

        }

        echo_json($json_array);
    }

    public function global_database_dashboard_record_tat()
    {
        $json_data = array();

        if ($this->input->is_ajax_request()) {
            $this->load->model('global_database_model');

            $filter = $this->input->post();

            $where_condition_arry = array('clientid' => 'candidates_info.clientid', 'entity' => 'candidates_info.entity', 'package' => 'candidates_info.package', 'overallclosuredate_from' => 'candidates_info.caserecddate', 'overallclosuredate_to' => 'candidates_info.caserecddate', 'address_type' => 'address_type', 'street_address' => 'street_address', 'city' => 'city', 'pincode' => 'pincode', 'state' => 'state', 'has_case_id' => 'has_case_id', 'verfstatus' => 'verfstatus', 'filter_by_sub_status' => 'verfstatus', 'mode_of_verification' => 'mode_of_verification', 'closuredate_from' => 'closuredate', 'closuredate_to' => 'closuredate');

            $where = ' where 1 ';

            foreach ($filter as $key => $value) {

                switch ($key) {
                    case 'has_case_id':
                        if ($filter['has_case_id'] != 0) {
                            if ($this->user_info['tbl_roles_id'] != "1") {
                                $where .= " and glodbver.has_case_id = '" . $this->user_info['id'] . "' ";
                            }
                        }

                        break;
                    case 'component_type':

                        if ($filter['component_type'] != '') {

                            if ($filter['component_type'] == 'today') {
                                $where .= " and ((glodbver.tat_status = 'tdy tat') or (glodbver.tat_status = 'TDY TAT'))";
                            }
                            if ($filter['component_type'] == 'app') {
                                $where .= " and ((glodbver.tat_status = 'ap tat') or (glodbver.tat_status = 'AP TAT'))";
                            }
                            if ($filter['component_type'] == 'in') {
                                $where .= " and ((glodbver.tat_status = 'in tat') or (glodbver.tat_status = 'IN TAT'))";
                            }
                            if ($filter['component_type'] == 'out') {
                                $where .= " and ((glodbver.tat_status = 'out tat') or (glodbver.tat_status = 'OUT TAT'))";
                            }

                            $group_id = $this->db->query("select group_concat(id) as wip_filter from status where filter_status = 'WIP'");

                            $res_filter = $group_id->result_array()[0];

                            $filter = explode(',', $res_filter['wip_filter']);

                            $where .= " and glodbver_result.verfstatus in (" . implode(',', $filter) . ") ";
                        }
                        break;

                    default:
                        if (array_key_exists($key, $where_condition_arry) && $value != '') {
                            $where .= " and  " . $where_condition_arry[$key] . " = '" . $value . "' ";
                        }
                        break;
                }
            }

            $data['lists'] = $this->global_database_model->dashboard_sql($where);

            $json_array['message'] = $this->load->view('admin/global_dashboard_details', $data, true);

            $json_array['status'] = SUCCESS_CODE;
        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['status'] = "we are sorry but you don't have access to this service";

        }

        echo_json($json_array);
    }

    public function identity_dashboard_record_tat()
    {
        $json_data = array();

        if ($this->input->is_ajax_request()) {
            $this->load->model('identity_model');

            $filter = $this->input->post();

            $where_condition_arry = array('clientid' => 'candidates_info.clientid', 'entity' => 'candidates_info.entity', 'package' => 'candidates_info.package', 'overallclosuredate_from' => 'candidates_info.caserecddate', 'overallclosuredate_to' => 'candidates_info.caserecddate', 'doc_submited' => 'doc_submited', 'id_number' => 'identity.id_number', 'has_case_id' => 'identity.has_case_id', 'verfstatus' => 'identity_result.verfstatus', 'filter_by_sub_status' => 'identity_result.verfstatus', 'mode_of_verification' => 'identity.mode_of_veri', 'closuredate_from' => 'identity_result.closuredate', 'closuredate_to' => 'identity_result.closuredate');

            $where = ' where 1 ';

            foreach ($filter as $key => $value) {

                switch ($key) {
                    case 'has_case_id':
                        if ($filter['has_case_id'] != 0) {
                            if ($this->user_info['tbl_roles_id'] != "1") {
                                $where .= " and identity.has_case_id = '" . $this->user_info['id'] . "' ";
                            }
                        }

                        break;

                    case 'component_type':

                        if ($filter['component_type'] != '') {

                            if ($filter['component_type'] == 'today') {
                                $where .= " and ((identity.tat_status = 'tdy tat') or (identity.tat_status = 'TDY TAT'))";
                            }
                            if ($filter['component_type'] == 'app') {
                                $where .= " and ((identity.tat_status = 'ap tat') or (identity.tat_status = 'AP TAT'))";
                            }
                            if ($filter['component_type'] == 'in') {
                                $where .= " and ((identity.tat_status = 'in tat') or (identity.tat_status = 'IN TAT'))";
                            }
                            if ($filter['component_type'] == 'out') {
                                $where .= " and ((identity.tat_status = 'out tat') or (identity.tat_status = 'OUT TAT'))";
                            }

                            $group_id = $this->db->query("select group_concat(id) as wip_filter from status where filter_status = 'WIP'");

                            $res_filter = $group_id->result_array()[0];

                            $filter = explode(',', $res_filter['wip_filter']);

                            $where .= " and identity_result.verfstatus in (" . implode(',', $filter) . ") ";
                        }
                        break;

                    default:
                        if (array_key_exists($key, $where_condition_arry) && $value != '') {
                            $where .= " and  " . $where_condition_arry[$key] . " = '" . $value . "' ";
                        }
                        break;
                }
            }

            $data['lists'] = $this->identity_model->dashboard_sql($where);

            $json_array['message'] = $this->load->view('admin/identity_dashboard_details', $data, true);

            $json_array['status'] = SUCCESS_CODE;
        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['status'] = "we are sorry but you don't have access to this service";

        }

        echo_json($json_array);
    }

    public function pcc_dashboard_record_tat()
    {
        $json_data = array();

        if ($this->input->is_ajax_request()) {
            $this->load->model('pcc_verificatiion_model');

            $filter = $this->input->post();

            $where_condition_arry = array('clientid' => 'candidates_info.clientid', 'entity' => 'candidates_info.entity', 'package' => 'candidates_info.package', 'overallclosuredate_from' => 'candidates_info.caserecddate', 'overallclosuredate_to' => 'candidates_info.caserecddate', 'street_address' => 'pcc.street_address', 'city' => 'pcc.city', 'pincode' => 'pcc.pincode', 'state' => 'pcc.state', 'has_case_id' => 'pcc.has_case_id', 'verfstatus' => 'pcc_result.verfstatus', 'filter_by_sub_status' => 'pcc_result.verfstatus', 'mode_of_verification' => 'pcc.mode_of_veri', 'closuredate_from' => 'pcc_result.closuredate', 'closuredate_to' => 'pcc_result.closuredate');

            $where = ' where 1 ';

            foreach ($filter as $key => $value) {

                switch ($key) {
                    case 'has_case_id':
                        if ($filter['has_case_id'] != 0) {
                            if ($this->user_info['tbl_roles_id'] != "1") {
                                $where .= " and pcc.has_case_id = '" . $this->user_info['id'] . "' ";
                            }
                        }

                        break;

                    case 'component_type':

                        if ($filter['component_type'] != '') {

                            if ($filter['component_type'] == 'today') {
                                $where .= " and ((pcc.tat_status = 'tdy tat') or (pcc.tat_status = 'TDY TAT'))";
                            }
                            if ($filter['component_type'] == 'app') {
                                $where .= " and ((pcc.tat_status = 'ap tat') or (pcc.tat_status = 'AP TAT'))";
                            }
                            if ($filter['component_type'] == 'in') {
                                $where .= " and ((pcc.tat_status = 'in tat') or (pcc.tat_status = 'IN TAT'))";
                            }
                            if ($filter['component_type'] == 'out') {
                                $where .= " and ((pcc.tat_status = 'out tat') or (pcc.tat_status = 'OUT TAT'))";
                            }

                            $group_id = $this->db->query("select group_concat(id) as wip_filter from status where filter_status = 'WIP'");

                            $res_filter = $group_id->result_array()[0];

                            $filter = explode(',', $res_filter['wip_filter']);

                            $where .= " and pcc_result.verfstatus in (" . implode(',', $filter) . ") ";
                        }
                        break;

                    default:
                        if (array_key_exists($key, $where_condition_arry) && $value != '') {
                            $where .= " and  " . $where_condition_arry[$key] . " = '" . $value . "' ";
                        }
                        break;
                }
            }

            $data['lists'] = $this->pcc_verificatiion_model->dashboard_sql($where);

            $json_array['message'] = $this->load->view('admin/pcc_dashboard_details', $data, true);

            $json_array['status'] = SUCCESS_CODE;
        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['status'] = "we are sorry but you don't have access to this service";

        }

        echo_json($json_array);
    }

    public function credit_report_dashboard_record_tat()
    {
        $json_data = array();

        if ($this->input->is_ajax_request()) {
            $this->load->model('credit_report_model');

            $filter = $this->input->post();

            $where_condition_arry = array('clientid' => 'candidates_info.clientid', 'entity' => 'candidates_info.entity', 'package' => 'candidates_info.package', 'overallclosuredate_from' => 'candidates_info.caserecddate', 'overallclosuredate_to' => 'candidates_info.caserecddate', 'doc_submited' => 'doc_submited', 'id_number' => 'credit_report.id_number', 'has_case_id' => 'credit_report.has_case_id', 'verfstatus' => 'credit_report_result.verfstatus', 'filter_by_sub_status' => 'credit_report_result.verfstatus', 'mode_of_verification' => 'credit_report.mode_of_veri', 'closuredate_from' => 'credit_report_result.closuredate', 'closuredate_to' => 'credit_report_result.closuredate');

            $where = ' where 1 ';

            foreach ($filter as $key => $value) {

                switch ($key) {

                    case 'has_case_id':
                        if ($filter['has_case_id'] != 0) {
                            if ($this->user_info['tbl_roles_id'] != "1") {
                                $where .= " and credit_report.has_case_id = '" . $this->user_info['id'] . "' ";
                            }
                        }

                        break;

                    case 'component_type':

                        if ($filter['component_type'] != '') {

                            if ($filter['component_type'] == 'today') {
                                $where .= " and ((credit_report.tat_status = 'tdy tat') or (credit_report.tat_status = 'TDY TAT'))";
                            }
                            if ($filter['component_type'] == 'app') {
                                $where .= " and ((credit_report.tat_status = 'ap tat') or (credit_report.tat_status = 'AP TAT'))";
                            }
                            if ($filter['component_type'] == 'in') {
                                $where .= " and ((credit_report.tat_status = 'in tat') or (credit_report.tat_status = 'IN TAT'))";
                            }
                            if ($filter['component_type'] == 'out') {
                                $where .= " and ((credit_report.tat_status = 'out tat') or (credit_report.tat_status = 'OUT TAT'))";
                            }

                            $group_id = $this->db->query("select group_concat(id) as wip_filter from status where filter_status = 'WIP'");

                            $res_filter = $group_id->result_array()[0];

                            $filter = explode(',', $res_filter['wip_filter']);

                            $where .= " and credit_report_result.verfstatus in (" . implode(',', $filter) . ") ";
                        }
                        break;

                    default:
                        if (array_key_exists($key, $where_condition_arry) && $value != '') {
                            $where .= " and  " . $where_condition_arry[$key] . " = '" . $value . "' ";
                        }
                        break;
                }
            }

            $data['lists'] = $this->credit_report_model->dashboard_sql($where);

            $json_array['message'] = $this->load->view('admin/credit_report_dashboard_details', $data, true);

            $json_array['status'] = SUCCESS_CODE;
        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['status'] = "we are sorry but you don't have access to this service";

        }

        echo_json($json_array);
    }

    public function drugs_dashboard_record_tat()
    {
        $json_data = array();

        if ($this->input->is_ajax_request()) {
            $this->load->model('drug_verificatiion_model');

            $filter = $this->input->post();

            $where_condition_arry = array('clientid' => 'candidates_info.clientid', 'entity' => 'candidates_info.entity', 'package' => 'candidates_info.package', 'overallclosuredate_from' => 'candidates_info.caserecddate', 'overallclosuredate_to' => 'candidates_info.caserecddate', 'street_address' => 'drug_narcotis.street_address', 'city' => 'drug_narcotis.city', 'pincode' => 'drug_narcotis.pincode', 'state' => 'drug_narcotis.state', 'has_case_id' => 'drug_narcotis.has_case_id', 'verfstatus' => 'drug_narcotis_result.verfstatus', 'filter_by_sub_status' => 'drug_narcotis_result.verfstatus', 'mode_of_verification' => 'drug_narcotis.mode_of_veri', 'closuredate_from' => 'drug_narcotis_result.closuredate', 'closuredate_to' => 'drug_narcotis_result.closuredate');

            $where = ' where 1 ';

            foreach ($filter as $key => $value) {

                switch ($key) {
                    case 'has_case_id':
                        if ($filter['has_case_id'] != 0) {
                            if ($this->user_info['tbl_roles_id'] != "1") {
                                $where .= " and drug_narcotis.has_case_id = '" . $this->user_info['id'] . "' ";
                            }
                        }

                        break;

                    case 'component_type':

                        if ($filter['component_type'] != '') {

                            if ($filter['component_type'] == 'today') {
                                $where .= " and ((drug_narcotis.tat_status = 'tdy tat') or (drug_narcotis.tat_status = 'TDY TAT'))";
                            }
                            if ($filter['component_type'] == 'app') {
                                $where .= " and ((drug_narcotis.tat_status = 'ap tat') or (drug_narcotis.tat_status = 'AP TAT'))";
                            }
                            if ($filter['component_type'] == 'in') {
                                $where .= " and ((drug_narcotis.tat_status = 'in tat') or (drug_narcotis.tat_status = 'IN TAT'))";
                            }
                            if ($filter['component_type'] == 'out') {
                                $where .= " and ((drug_narcotis.tat_status = 'out tat') or (drug_narcotis.tat_status = 'OUT TAT'))";
                            }

                            $group_id = $this->db->query("select group_concat(id) as wip_filter from status where filter_status = 'WIP'");

                            $res_filter = $group_id->result_array()[0];

                            $filter = explode(',', $res_filter['wip_filter']);

                            $where .= " and drug_narcotis_result.verfstatus in (" . implode(',', $filter) . ") ";
                        }
                        break;

                    default:
                        if (array_key_exists($key, $where_condition_arry) && $value != '') {
                            $where .= " and  " . $where_condition_arry[$key] . " = '" . $value . "' ";
                        }
                        break;
                }
            }

            $data['lists'] = $this->drug_verificatiion_model->dashboard_sql($where);

            $json_array['message'] = $this->load->view('admin/drugs_dashboard_details', $data, true);

            $json_array['status'] = SUCCESS_CODE;
        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['status'] = "we are sorry but you don't have access to this service";

        }

        echo_json($json_array);
    }

    public function candidate_record()
    {
        $json_data = array();

        if ($this->input->is_ajax_request()) {
            $this->load->model('candidates_model');

            $filter = $this->input->post();

            $where_condition_arry = array('clientid' => 'candidates_info.clientid', 'entity' => 'candidates_info.entity', 'package' => 'candidates_info.package', 'overallclosuredate_from' => 'candidates_info.caserecddate', 'overallclosuredate_to' => 'candidates_info.caserecddate', 'street_address' => 'pcc.street_address', 'city' => 'pcc.city', 'pincode' => 'pcc.pincode', 'state' => 'pcc.state', 'has_case_id' => 'pcc.has_case_id', 'verfstatus' => 'pcc_result.verfstatus', 'filter_by_sub_status' => 'pcc_result.verfstatus', 'mode_of_verification' => 'pcc.mode_of_veri', 'closuredate_from' => 'pcc_result.closuredate', 'closuredate_to' => 'pcc_result.closuredate');

            $where = ' where 1 ';

            foreach ($filter as $key => $value) {

                switch ($key) {
                    case 'clientid':
                        if ($filter['clientid'] != 0) {
                            $where .= " and candidates_info.clientid = '" . $filter['clientid'] . "' ";
                        }
                        break;

                    case 'form_date':
                        if ($filter['form_date'] != '' || $filter['to_date'] != '') {
                            if (($filter['component_type'] == '2') || ($filter['component_type'] == '3') || ($filter['component_type'] == '4') || ($filter['component_type'] == '6') || ($filter['component_type'] == '7') || ($filter['component_type'] == '8')) {

                                $from = ($filter['form_date'] != '') ? convert_display_to_db_date($filter['form_date']) : '';
                                $to = ($filter['to_date'] != '') ? convert_display_to_db_date($filter['to_date']) : '';
                                $to = ($to) ? " '" . $to . "' " : 'CURRENT_DATE()';
                                $where .= " and DATE_FORMAT(`candidates_info`.`overallclosuredate`,'%Y-%m-%d')  BETWEEN '" . $from . "' and " . $to . " ";

                            }
                        }
                        break;

                    case 'component_type':
                        if ($filter['component_type'] != '') {

                            $where .= " and candidates_info.overallstatus ='" . $filter['component_type'] . "' ";
                        }
                        break;

                    default:
                        if (array_key_exists($key, $where_condition_arry) && $value != '') {
                            $where .= " and  " . $where_condition_arry[$key] . " = '" . $value . "' ";
                        }
                        break;
                }
            }

            $data['lists'] = $this->candidates_model->dashboard_sql($where);

            $json_array['message'] = $this->load->view('admin/candidates_dashboard_details', $data, true);

            $json_array['status'] = SUCCESS_CODE;
        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['status'] = "we are sorry but you don't have access to this service";

        }

        echo_json($json_array);
    }
   
    public function aq_component_details()
    {
       
        $json_array = array();
        
        $result = '';

        if($this->input->is_ajax_request())
        {

            $params = $_REQUEST;
       
            $result = $this->report_generated_user->get_aq_component_details();

            if($result != '')
            {
        
                $json_array['status'] = SUCCESS_CODE;
                        
                $json_array['message'] = $result;
            }
            else
            {
                $json_array['status'] = ERROR_CODE;

               $json_array['message'] = 'Something went wrong';
            }
        }
        else
        {
            $json_array['status'] = ERROR_CODE;

            $json_array['message'] = 'Something went wrong';
        }
        echo_json($json_array);
    }
}

?>