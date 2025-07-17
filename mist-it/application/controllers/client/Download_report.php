<?php defined('BASEPATH') or exit('No direct script access allowed');

class Download_report extends MY_Client_Cotroller
{
    public function __construct()
    {
        parent::__construct();

        if (!$this->is_client_logged_in()) {
            redirect('client/login');
            exit();
        }
        $this->perm_array = array('direct_access' => true);
        $this->load->model(array('client/candidates_model'));
    }

    public function index()
    {
        $data['header_title'] = "Download Report";


        $this->load->view('client/header', $data);

        $this->load->view('client/report_list');

        $this->load->view('client/footer');

    }

    public function data_table_cands_view_report()
    {
        if ($this->input->is_ajax_request()) {
            $params = $data_arry = $columns = array();

            $params = $_REQUEST;

            $columns = array('id', 'CandidateName', "clientname", "caserecddate", 'ClientRefNumber', 'cmp_ref_no', 'overallstatus', 'remarks', 'view', 'encry_id');
            $cands_results = $this->candidates_model->get_all_cand_with_search_report($this->user_package, $this->user_role, $params, $columns);

            $totalRecords = count($this->candidates_model->get_all_cand_with_search_count_report($this->user_package, $this->user_role, $params, $columns));
            $x = 0;

            foreach ($cands_results as $key => $cands_result) {

                $report_details = $this->candidates_model->select_cadidate_report_join(array('activity_log.candsid' => $cands_result['id']));

                $data_arry[$x]['id'] = $cands_result['id'];
                $data_arry[$x]['CandidateName'] = ucwords(strtolower($cands_result['CandidateName']));
                $data_arry[$x]['entity'] = ucwords(strtolower($cands_result['entity_name']));
                $data_arry[$x]['package'] = ucwords(strtolower($cands_result['package_name']));
                $data_arry[$x]['ClientRefNumber'] = $cands_result['ClientRefNumber'];
                $data_arry[$x]['cmp_ref_no'] = $cands_result['cmp_ref_no'];
                $data_arry[$x]['overallstatus'] = $cands_result['status_value'];
                $data_arry[$x]['overallclosuredate'] = convert_db_to_display_date($cands_result['overallclosuredate']);
                $data_arry[$x]['remarks'] = $cands_result['remarks'];
                $data_arry[$x]['TAT'] = $cands_result['tat_status_candidate'];
                $data_arry[$x]['Action'] = "";

                $data_arry[$x]['clientname'] = ucwords(strtolower($cands_result['clientname']));
                $data_arry[$x]['caserecddate'] = convert_db_to_display_date($cands_result['caserecddate']);
                $data_arry[$x]['pending_check'] = 'NA';
                $data_arry[$x]['insufficiency_check'] = 'NA';
                $data_arry[$x]['closed_check'] = 'NA';

                // print_r($cands_result['id']);
                $array_wip = array();
                $array_insufficiency = array();
                $array_closed = array();

                $result_address_main = $this->candidates_model->get_addres_ver_status_list(array('candidates_info.id' => $cands_result['id']));
              
                if (!empty($result_address_main)) {
                    $address_count = 1;
                    foreach ($result_address_main as $result_address) {

                        $address_component_status = ($result_address['var_filter_status'] != "") ? $result_address['var_filter_status'] : 'WIP';

                        if (($address_component_status == "WIP") || ($address_component_status == "wip")) {
                            array_push($array_wip, 'Address ' . $address_count);
                        }
                        if (($address_component_status == "Insufficiency") || ($address_component_status == "insufficiency")) {
                            array_push($array_insufficiency, 'Address ' . $address_count);
                        }
                        if (($address_component_status == "Closed") || ($address_component_status == "closed")) {
                            array_push($array_closed, 'Address ' . $address_count);
                        }

                        $address_count++;

                    }
                }

                $result_education_main = $this->candidates_model->get_education_ver_status_list(array('candidates_info.id' => $cands_result['id']));

                if (!empty($result_education_main)) {
                    $education_count = 1;
                    foreach ($result_education_main as $result_education) {

                        $education_component_status = ($result_education['var_filter_status'] != "") ? $result_education['var_filter_status'] : 'WIP';

                        if (($education_component_status == "WIP") || ($education_component_status == "wip")) {
                            array_push($array_wip, 'Education ' . $education_count);
                        }
                        if (($education_component_status == "Insufficiency") || ($education_component_status == "insufficiency")) {
                            array_push($array_insufficiency, 'Education ' . $education_count);
                        }
                        if (($education_component_status == "Closed") || ($education_component_status == "closed")) {
                            array_push($array_closed, 'Education ' . $education_count);
                        }

                        $education_count++;
                    }
                }

                $result_employment_main = $this->candidates_model->get_employment_ver_status_list(array('candidates_info.id' => $cands_result['id']));

                if (!empty($result_employment_main)) {
                    $employment_count = 1;

                    foreach ($result_employment_main as $result_employment) {

                        $employment_component_status = ($result_employment['var_filter_status'] != "") ? $result_employment['var_filter_status'] : 'WIP';

                        if (($employment_component_status == "WIP") || ($employment_component_status == "wip")) {
                            array_push($array_wip, "Employment " . $employment_count);
                        }
                        if (($employment_component_status == "Insufficiency") || ($employment_component_status == "insufficiency")) {
                            array_push($array_insufficiency, 'Employment ' . $employment_count);
                        }
                        if (($employment_component_status == "Closed") || ($employment_component_status == "closed")) {
                            array_push($array_closed, 'Employment ' . $employment_count);
                        }
                        $employment_count++;
                    }
                }

                $result_court_main = $this->candidates_model->get_court_ver_status_list(array('candidates_info.id' => $cands_result['id']));

                if (!empty($result_court_main)) {
                    $court_count = 1;

                    foreach ($result_court_main as $result_court) {

                        $court_component_status = ($result_court['var_filter_status'] != "") ? $result_court['var_filter_status'] : 'WIP';
                        if (($court_component_status == "WIP") || ($court_component_status == "wip")) {
                            array_push($array_wip, "Court " . $court_count);
                        }
                        if (($court_component_status == "Insufficiency") || ($court_component_status == "insufficiency")) {
                            array_push($array_insufficiency, 'Court ' . $court_count);
                        }
                        if (($court_component_status == "Closed") || ($court_component_status == "closed")) {
                            array_push($array_closed, 'Court ' . $court_count);
                        }

                        $court_count++;
                    }
                }

                $result_pcc_main = $this->candidates_model->get_pcc_ver_status_list(array('candidates_info.id' => $cands_result['id']));

                if (!empty($result_pcc_main)) {

                    $pcc_count = 1;

                    foreach ($result_pcc_main as $result_pcc) {

                        $pcc_component_status = ($result_pcc['var_filter_status'] != "") ? $result_pcc['var_filter_status'] : 'WIP';

                        if (($pcc_component_status == "WIP") || ($pcc_component_status == "wip")) {
                            array_push($array_wip, "PCC " . $pcc_count);
                        }
                        if (($pcc_component_status == "Insufficiency") || ($pcc_component_status == "insufficiency")) {
                            array_push($array_insufficiency, 'PCC ' . $pcc_count);
                        }
                        if (($pcc_component_status == "Closed") || ($pcc_component_status == "closed")) {
                            array_push($array_closed, 'PCC ' . $pcc_count);
                        }

                        $pcc_count++;
                    }
                }

                $result_reference_main = $this->candidates_model->get_refver_ver_status_list(array('reference.candsid' => $cands_result['id']));

                if (!empty($result_reference_main)) {

                    $reference_count = 1;

                    foreach ($result_reference_main as $result_reference) {

                        $reference_component_status = ($result_reference['var_filter_status'] != "") ? $result_reference['var_filter_status'] : 'WIP';

                        if (($reference_component_status == "WIP") || ($reference_component_status == "wip")) {
                            array_push($array_wip, "Reference " . $reference_count);
                        }
                        if (($reference_component_status == "Insufficiency") || ($reference_component_status == "insufficiency")) {
                            array_push($array_insufficiency, 'Reference ' . $reference_count);
                        }
                        if (($reference_component_status == "Closed") || ($reference_component_status == "closed")) {
                            array_push($array_closed, 'Reference ' . $reference_count);
                        }

                        $reference_count++;
                    }

                }

                $result_global_main = $this->candidates_model->get_globdbver_ver_status_list(array('candidates_info.id' => $cands_result['id']));
                if (!empty($result_global_main)) {

                    $global_count = 1;

                    foreach ($result_global_main as $result_global) {

                        $globaldb_component_status = ($result_global['var_filter_status'] != "") ? $result_global['var_filter_status'] : 'WIP';

                        if (($globaldb_component_status == "WIP") || ($globaldb_component_status == "wip")) {
                            array_push($array_wip, "Global " . $global_count);
                        }
                        if (($globaldb_component_status == "Insufficiency") || ($globaldb_component_status == "insufficiency")) {
                            array_push($array_insufficiency, 'Global ' . $global_count);
                        }
                        if (($globaldb_component_status == "Closed") || ($globaldb_component_status == "closed")) {
                            array_push($array_closed, 'Global ' . $global_count);
                        }

                        $global_count++;
                    }
                }

                $result_identity_main = $this->candidates_model->get_identity_ver_status_list(array('candidates_info.id' => $cands_result['id']));

                if (!empty($result_identity_main)) {

                    $identity_count = 1;

                    foreach ($result_identity_main as $result_identity) {

                        $identity_component_status = ($result_identity['var_filter_status'] != "") ? $result_identity['var_filter_status'] : 'WIP';

                        if (($identity_component_status == "WIP") || ($identity_component_status == "wip")) {
                            array_push($array_wip, "Identity " . $identity_count);
                        }
                        if (($identity_component_status == "Insufficiency") || ($identity_component_status == "insufficiency")) {
                            array_push($array_insufficiency, 'Identity ' . $identity_count);
                        }
                        if (($identity_component_status == "Closed") || ($identity_component_status == "closed")) {
                            array_push($array_closed, 'Identity ' . $identity_count);
                        }

                        $identity_count++;
                    }
                }

                $result_credit_report_main = $this->candidates_model->get_credit_reports_ver_status_list(array('candidates_info.id' => $cands_result['id']));
                if (!empty($result_credit_report_main)) {

                    $credit_report_count = 1;

                    foreach ($result_credit_report_main as $result_credit_report) {

                        $credit_report_component_status = ($result_credit_report['var_filter_status'] != "") ? $result_credit_report['var_filter_status'] : 'WIP';

                        if (($credit_report_component_status == "WIP") || ($credit_report_component_status == "wip")) {
                            array_push($array_wip, "Credit Report " . $credit_report_count);
                        }
                        if (($credit_report_component_status == "Insufficiency") || ($credit_report_component_status == "insufficiency")) {
                            array_push($array_insufficiency, 'Credit Report ' . $credit_report_count);
                        }
                        if (($credit_report_component_status == "Closed") || ($credit_report_component_status == "closed")) {
                            array_push($array_closed, 'Credit Report ' . $credit_report_count);
                        }

                        $credit_report_count++;
                    }
                }

                $result_drugs_main = $this->candidates_model->get_narcver_ver_status_list(array('candidates_info.id' => $cands_result['id']));
                if (!empty($result_drugs)) {

                    $drugs_count = 1;

                    foreach ($result_drugs_main as $result_drugs) {

                        $drugs_component_status = ($result_drugs['var_filter_status'] != "") ? $result_drugs['var_filter_status'] : 'WIP';

                        if (($drugs_component_status == "WIP") || ($drugs_component_status == "wip")) {
                            array_push($array_wip, "Drugs " . $drugs_count);
                        }
                        if (($drugs_component_status == "Insufficiency") || ($drugs_component_status == "insufficiency")) {
                            array_push($array_insufficiency, 'Drugs ' . $drugs_count);
                        }
                        if (($drugs_component_status == "Closed") || ($drugs_component_status == "closed")) {
                            array_push($array_closed, 'Drugs ' . $drugs_count);
                        }
                        $drugs_count++;
                    }
                }

                $data_arry[$x]['pending_check'] = $array_wip;
                $data_arry[$x]['insufficiency_check'] = $array_insufficiency;
                $data_arry[$x]['closed_check'] = $array_closed;

                $x++;
            }

            $json_data = array(
                "draw" => intval($params['draw']),
                "recordsTotal" => intval($totalRecords),
                "recordsFiltered" => intval($totalRecords),
                "data" => $data_arry,
            );

             $this->echo_json($json_data);
        }
    }
   
    private function echo_json($json_array)
    {
        echo_json($json_array);

        exit;
    }

    public function report()
    {
        $candsid = $this->input->post('cases_id');

        if (!empty($candsid)) {
            
            $candid = explode(",", $candsid);

            foreach ($candid as $key => $id) {
                
               
                $this->load->model('first_qc_model');

                $this->load->library('example');

                $this->load->library('zip');

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
                $report['social_media_info'] = array();


                $report['report_type'] = $report_type;

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
                        $cleit_logo_path = IP_SITE_URL . CLIENT_LOGO . '/' . $cands_result['comp_logo'];
                        define('CLIENT_LOGOS', $cleit_logo_path);
                    } else {
                        define('CLIENT_LOGOS', '');
                    }

                    define('CUSTOM_CLINT_ID',$cands_result['clientid']);


                    $this->example->generate_pdf($report, 'client');
                }

            } 
        } else {
            redirect('client/Download_report');
        }
        
    }  
}
?>