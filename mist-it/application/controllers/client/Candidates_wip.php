
<?php defined('BASEPATH') or exit('No direct script access allowed');

class Candidates_wip extends MY_Client_Cotroller
{

    public function __construct()
    {
        parent::__construct();
        $this->perm_array = array('direct_access' => true);
        $this->load->model(array('client/candidates_model_wip'));
    }
    public function index()
    {

        $data['header_title'] = "Candidates List";
        $data['my_components'] = $this->my_components();

        $this->load->view('client/header', $data);

        $this->load->view('client/candidates_list_wip');

        $this->load->view('client/footer');

    }

    public function data_table_cands_view()
    {
        if ($this->input->is_ajax_request()) {
            $params = $data_arry = $columns = array();

            $params = $_REQUEST;

            $columns = array('id', 'CandidateName', "clientname", "caserecddate", 'ClientRefNumber', 'cmp_ref_no', 'overallstatus', 'remarks', 'view', 'encry_id');

            $cands_results = $this->candidates_model_wip->get_all_cand_with_search($this->user_package, $this->user_role, $params, $columns);

            $totalRecords = count($this->candidates_model_wip->get_all_cand_with_search_count($this->user_package, $this->user_role, $params, $columns));
            $x = 0;

            foreach ($cands_results as $key => $cands_result) {
                $data_arry[$x]['id'] = $x + 1;
                $data_arry[$x]['CandidateName'] = ucwords(strtolower($cands_result['CandidateName']));
                $data_arry[$x]['ClientRefNumber'] = $cands_result['ClientRefNumber'];
                $data_arry[$x]['cmp_ref_no'] = $cands_result['cmp_ref_no'];
                $data_arry[$x]['overallstatus'] = $cands_result['status_value'];
                $data_arry[$x]['overallclosuredate'] = convert_db_to_display_date($cands_result['overallclosuredate']);
                $data_arry[$x]['remarks'] = $cands_result['remarks'];
                $data_arry[$x]['clientname'] = ucwords(strtolower($cands_result['clientname']));
                $data_arry[$x]['caserecddate'] = convert_db_to_display_date($cands_result['caserecddate']);
                $data_arry[$x]['view'] = "<a href='#' title='View' data-toggle='modal' data-target='#myModal' class='view_details' data-raw_id='" . $cands_result['id'] . "'><i class='fa fa-eye'></i></a>";

                // $data_arry[$x]['view'] = CLIENT_SITE_URL."candidates_wip/view_details/".encrypt($cands_result['id'])
                $data_arry[$x]['encry_id'] = "<a target='__blank' class='status_alert' data-overallstatus='" . $cands_result['status_value'] . "' href='javascript:void(0)' data-href='" . CLIENT_SITE_URL . "candidates/report/" . encrypt($cands_result['id']) . "' title='Report'><i class='fa fa-file-pdf-o'></i></a>";
                $data_arry[$x]['pending_check'] = 'NA';
                $data_arry[$x]['insufficiency_check'] = 'NA';
                $data_arry[$x]['closed_check'] = 'NA';

                // print_r($cands_result['id']);
                $array_wip = array();
                $array_insufficiency = array();
                $array_closed = array();

                $result_address = $this->candidates_model_wip->get_addres_ver_status(array('candidates_info.id' => $cands_result['id']));

                if (!empty($result_address)) {

                    $address_component_status = ($result_address[0]['filter_status'] != "") ? $result_address[0]['filter_status'] : 'WIP';
                    //print_r($data_arry[$x]['address_component_status']);

                    if ($address_component_status == "WIP") {
                        array_push($array_wip, 'Address');
                    }
                    if ($address_component_status == "Insufficiency") {
                        array_push($array_insufficiency, 'Address');
                    }
                    if ($address_component_status == "Closed") {
                        array_push($array_closed, 'Address');
                    }
                }

                $result_education = $this->candidates_model_wip->get_education_ver_status(array('candidates_info.id' => $cands_result['id']));

                if (!empty($result_education)) {

                    $education_component_status = ($result_education[0]['filter_status'] != "") ? $result_education[0]['filter_status'] : 'WIP';

                    if ($education_component_status == "WIP") {
                        array_push($array_wip, 'Education');
                    }
                    if ($education_component_status == "Insufficiency") {
                        array_push($array_insufficiency, 'Education');
                    }
                    if ($education_component_status == "Closed") {
                        array_push($array_closed, 'Education');
                    }
                }

                $result_employment = $this->candidates_model_wip->get_employment_ver_status(array('candidates_info.id' => $cands_result['id']));

                if (!empty($result_employment)) {

                    $employment_component_status = ($result_employment[0]['filter_status'] != "") ? $result_employment[0]['filter_status'] : 'WIP';

                    if ($employment_component_status == "WIP") {
                        array_push($array_wip, "Employment");
                    }
                    if ($employment_component_status == "Insufficiency") {
                        array_push($array_insufficiency, 'Employment');
                    }
                    if ($employment_component_status == "Closed") {
                        array_push($array_closed, 'Employment');
                    }
                }

                $result_court = $this->candidates_model_wip->get_court_ver_status(array('candidates_info.id' => $cands_result['id']));

                if (!empty($result_court)) {

                    $court_component_status = ($result_court[0]['filter_status'] != "") ? $result_court[0]['filter_status'] : 'WIP';
                    if ($court_component_status == "WIP") {
                        array_push($array_wip, "Court");
                    }
                    if ($court_component_status == "Insufficiency") {
                        array_push($array_insufficiency, 'Court');
                    }
                    if ($court_component_status == "Closed") {
                        array_push($array_closed, 'Court');
                    }
                }

                $result_pcc = $this->candidates_model_wip->get_pcc_ver_status(array('candidates_info.id' => $cands_result['id']));

                if (!empty($result_pcc)) {
                    $pcc_component_status = ($result_pcc[0]['filter_status'] != "") ? $result_pcc[0]['filter_status'] : 'WIP';

                    if ($pcc_component_status == "WIP") {
                        array_push($array_wip, "PCC");
                    }
                    if ($pcc_component_status == "Insufficiency") {
                        array_push($array_insufficiency, 'PCC');
                    }
                    if ($pcc_component_status == "Closed") {
                        array_push($array_closed, 'PCC');
                    }
                }

                $result_reference = $this->candidates_model_wip->get_reference_ver_status(array('reference.candsid' => $cands_result['id']));
                if (!empty($result_reference)) {
                    $reference_component_status = ($result_reference[0]['filter_status'] != "") ? $result_reference[0]['filter_status'] : 'WIP';

                    if ($reference_component_status == "WIP") {
                        array_push($array_wip, "Reference");
                    }
                    if ($reference_component_status == "Insufficiency") {
                        array_push($array_insufficiency, 'Reference');
                    }
                    if ($reference_component_status == "Closed") {
                        array_push($array_closed, 'Reference');
                    }

                }

                $result_global = $this->candidates_model_wip->get_global_db_ver_status(array('candidates_info.id' => $cands_result['id']));
                if (!empty($result_global)) {
                    $globaldb_component_status = ($result_global[0]['filter_status'] != "") ? $result_global[0]['filter_status'] : 'WIP';

                    if ($globaldb_component_status == "WIP") {
                        array_push($array_wip, "Global");
                    }
                    if ($globaldb_component_status == "Insufficiency") {
                        array_push($array_insufficiency, 'Global');
                    }
                    if ($globaldb_component_status == "Closed") {
                        array_push($array_closed, 'Global');
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

    public function data_table_cands_view_for_client()
    {
        if ($this->input->is_ajax_request()) {
            $params = $data_arry = $columns = array();

            $params = $_REQUEST;

            $columns = array('id', 'CandidateName', "clientname", "caserecddate", 'ClientRefNumber', 'cmp_ref_no', 'overallstatus', 'remarks', 'view', 'encry_id');

            $cands_results = $this->candidates_model_wip->get_all_cand_with_search($this->user_role, $params, $columns);

            $totalRecords = count($this->candidates_model_wip->get_all_cand_with_search_count($this->user_role, $params, $columns));
            $x = 0;

            foreach ($cands_results as $key => $cands_result) {
                $data_arry[$x]['id'] = $x + 1;
                $data_arry[$x]['CandidateName'] = ucwords(strtolower($cands_result['CandidateName']));
                $data_arry[$x]['ClientRefNumber'] = $cands_result['ClientRefNumber'];
                $data_arry[$x]['cmp_ref_no'] = $cands_result['cmp_ref_no'];
                $data_arry[$x]['overallstatus'] = $cands_result['overallstatus'];
                $data_arry[$x]['overallclosuredate'] = convert_db_to_display_date($cands_result['overallclosuredate']);
                $data_arry[$x]['remarks'] = $cands_result['remarks'];
                $data_arry[$x]['clientname'] = ucwords(strtolower($cands_result['clientname']));
                $data_arry[$x]['caserecddate'] = convert_db_to_display_date($cands_result['caserecddate']);
                $data_arry[$x]['view'] = "<a href='#' title='View' class='view_details' data-raw_id='" . $cands_result['id'] . "'><i class='fa fa-eye'></i></a>";
                $data_arry[$x]['encry_id'] = "<a target='__blank' class='status_alert' data-overallstatus='" . $cands_result['overallstatus'] . "' href='javascript:void(0)' data-href='" . CLIENT_SITE_URL . "candidates/report/" . encrypt($cands_result['id']) . "' title='Report'><i class='fa fa-file-pdf-o'></i></a>";
                $data_arry[$x]['addrver'] = 'NA';
                $data_arry[$x]['eduver'] = 'NA';
                $data_arry[$x]['empver'] = 'NA';
                $data_arry[$x]['crimver'] = 'NA';
                $data_arry[$x]['courtver'] = 'NA';
                $data_arry[$x]['globdbver'] = 'NA';

                if ($cands_result['addrver'] == 1) {
                    $result = $this->candidates_model_wip->get_addres_ver_status(array('cands.id' => $cands_result['id']));
                    if (!empty($result)) {
                        $data_arry[$x]['addrver'] = ($result[0]['verfstatus'] != "") ? $result[0]['verfstatus'] : 'WIP';
                    }
                }

                if ($cands_result['eduver'] == 1) {
                    $result = $this->candidates_model_wip->get_education_ver_status(array('cands.id' => $cands_result['id']));
                    if (!empty($result)) {
                        $data_arry[$x]['eduver'] = ($result[0]['verfstatus'] != "") ? $result[0]['verfstatus'] : 'WIP';
                    }
                }

                if ($cands_result['empver'] == 1) {
                    $result = $this->candidates_model_wip->get_employment_ver_status(array('cands.id' => $cands_result['id']));
                    if (!empty($result)) {
                        $data_arry[$x]['empver'] = ($result[0]['verfstatus'] != "") ? $result[0]['verfstatus'] : 'WIP';
                    }
                }

                if ($cands_result['courtver'] == 1) {
                    $result = $this->candidates_model_wip->get_court_ver_status(array('cands.id' => $cands_result['id']));
                    if (!empty($result)) {
                        $data_arry[$x]['courtver'] = ($result[0]['verfstatus'] != "") ? $result[0]['verfstatus'] : 'WIP';
                    }
                }

                if ($cands_result['crimver'] == 1) {
                    $result = $this->candidates_model_wip->get_pcc_ver_status(array('cands.id' => $cands_result['id']));
                    if (!empty($result)) {
                        $data_arry[$x]['crimver'] = ($result[0]['verfstatus'] != "") ? $result[0]['verfstatus'] : 'WIP';
                    }
                }

                if ($cands_result['globdbver'] == 1) {
                    $result = $this->candidates_model_wip->get_global_db_ver_status(array('cands.id' => $cands_result['id']));

                    if (!empty($result)) {
                        $data_arry[$x]['globdbver'] = ($result[0]['verfstatus'] != "") ? $result[0]['verfstatus'] : 'WIP';
                    }
                }
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

    public function view_details($cand_id)
    {

        if (!empty($cand_id)) {
            $candidate_details = $this->candidates_model_wip->select(true, array(), array('id' => $cand_id));

            if (!empty($candidate_details)) {
                $data['candidate_details'] = $candidate_details;

                $data['my_components'] = $this->my_components();

                echo $this->load->view('client/candidate_details_view_wip', $data, true);
            } else {
                show_404();
            }
        } else {
            $this->index();
        }

    }

    public function ajax_tab_data($tab, $client_id) // tat cases showing

    {
        if ($this->input->is_ajax_request()) {
            $data['tab_change'] = $tab;
            $this->load->library('tat_calculation');
            $where_array = ($client_id == 0) ? array() : array('cands.clientid' => $client_id);
            switch ($tab) {
                case "addrver":
                    $this->load->model('addressver_model');
                    $tat_calculate = $this->addressver_model->get_all_addrs_by_client($where_array);
                    $data['component'] = 'Address Component Cases';
                    $data['all_tat_view_data'] = $this->tat_calculate($tat_calculate);
                    echo $this->load->view('address_ajax_tab', $data, true);
                    break;
                case "courtver":
                    $this->load->model('court_verificatiion_model');
                    $tat_calculate = $this->court_verificatiion_model->get_all_court_client($where_array);
                    $data['component'] = 'Court Component Cases';
                    $data['all_tat_view_data'] = $this->tat_calculate($tat_calculate);
                    echo $this->load->view('address_ajax_tab', $data, true);
                    break;
                case "cbrver":
                    echo "Your selected cbrver!";
                    break;
                case "narcver":
                    echo "Your selected narcver!";
                    break;
                case "eduver":
                    $this->load->model('education_model');
                    $tat_calculate = $this->education_model->get_all_eduver_client($where_array);
                    $data['all_tat_view_data'] = $this->tat_calculate($tat_calculate);
                    $data['component'] = 'Education Component Cases';
                    echo $this->load->view('address_ajax_tab', $data, true);
                    break;
                case "empver":
                    $this->load->model('employment_model');
                    $tat_calculate = $this->employment_model->get_all_emp_client($where_array);
                    $data['all_tat_view_data'] = $this->tat_calculate($tat_calculate);
                    $data['component'] = 'Employment Component Cases';
                    echo $this->load->view('address_ajax_tab', $data, true);
                    break;
                case "globdbver":
                    $this->load->model('global_database_model');
                    $tat_calculate = $this->global_database_model->get_all_global_db_client($where_array);
                    $data['all_tat_view_data'] = $this->tat_calculate($tat_calculate);
                    $data['component'] = 'Global DB Component Cases';
                    echo $this->load->view('address_ajax_tab', $data, true);
                    break;
                case "crimver":
                    $this->load->model('pcc_model');
                    $tat_calculate = $this->pcc_model->get_all_pcc_client($where_array);
                    $data['all_tat_view_data'] = $this->tat_calculate($tat_calculate);
                    $data['component'] = 'PCC Component Cases';
                    echo $this->load->view('address_ajax_tab', $data, true);
                    break;
                case "refver":
                    $this->load->model('references_model');
                    $tat_calculate = $this->references_model->cand_reference_details($where_array);
                    $data['all_tat_view_data'] = $this->tat_calculate($tat_calculate);
                    $data['component'] = 'References Component Cases';
                    echo $this->load->view('address_ajax_tab', $data, true);
                    break;

                default:
                    echo " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Working";
            }
        }
    }

    public function ajax_tab_data_view($tab = "", $client_id = '') // tat cases showing

    {
        if ($this->input->is_ajax_request()) {
            $data['tab_change'] = $tab;
            $data['client_id'] = $client_id;

            switch ($tab) {
                case "addrver":
                    $this->load->model('client/addressver_model');
                    $data['address_lists'] = $this->addressver_model->get_all_addrs_by_client_view($client_id);
                    $data['component'] = 'Address Component Cases';
                    echo $this->load->view('client/address_ajax_tab_view', $data, true);
                    break;
                case "courtver":
                    $this->load->model('client/court_verificatiion_model');
                    $data['court_lists'] = $this->court_verificatiion_model->get_all_court_client_view(array('candidates_info.id' => $client_id));
                    $data['component'] = 'Court Component Cases';
                    echo $this->load->view('client/court_ajax_tab_view', $data, true);
                    break;
                case "cbrver":
                    $this->load->model('client/credit_report_model');
                    $data['credit_report_lists'] = $this->credit_report_model->get_credit_report_data_view(array('candidates_info.id' => $client_id));
                    $data['component'] = 'Credit Report Component Cases';
                    echo $this->load->view('client/credit_report_ajax_tab_view', $data, true);
                    break;
                case "narcver":
                    $this->load->model('client/drugs_model');
                    $data['drugs_lists'] = $this->drugs_model->get_drugs_data_view(array('candidates_info.id' => $client_id));
                    $data['component'] = 'Drugs Component Cases';
                    echo $this->load->view('client/drugs_ajax_tab_view', $data, true);
                    break;
                case "eduver":
                    $this->load->model('client/education_model');
                    $data['education_lists'] = $this->education_model->cand_education_details_view($client_id);
                    $data['component'] = 'Education Component Cases';
                    echo $this->load->view('client/education_ajax_tab_view', $data, true);
                    break;
                case "empver":
                    $this->load->model('client/employment_model');
                    $data['employment_lists'] = $this->employment_model->cand_employment_details_view($client_id);
                    $data['component'] = 'Employment Component Cases';
                    echo $this->load->view('client/employment_ajax_tab_view', $data, true);
                    break;
                case "globdbver":
                    $this->load->model('client/global_database_model');
                    $data['global_database_lists'] = $this->global_database_model->cand_global_data_view(array('candidates_info.id' => $client_id));
                    $data['component'] = 'Global Component Cases';
                    echo $this->load->view('client/global_ajax_tab_view', $data, true);

                    break;
                case "crimver":
                    $this->load->model('client/pcc_model');
                    $data['pcc_lists'] = $this->pcc_model->cand_pcc_details_view($client_id);
                    $data['component'] = 'PCC Component Cases';
                    echo $this->load->view('client/pcc_ajax_tab_view', $data, true);
                    break;
                case "refver":
                    $this->load->model('client/references_model');
                    $data['reference_lists'] = $this->references_model->cand_reference_details_view(array('candidates_info.id' => $client_id));
                    $data['component'] = 'Reference Cases';
                    echo $this->load->view('client/reference_ajax_tab_view', $data, true);
                    break;

                case "identity":
                    $this->load->model('client/identity_model');
                    $data['identity_details'] = $this->identity_model->cand_identity_details_view(array('identity.candsid' => $client_id));
                    $data['component'] = 'Identity Cases';
                    echo $this->load->view('client/identity_ajax_tab_view', $data, true);
                    break;

                default:
                    echo " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Working";
            }
        }
    }

    public function all_component_details($tab = "", $id, $comp = '') // candidate info and there component cases

    {
        if ($this->input->is_ajax_request()) {
            switch ($tab) {
                case "addrver":
                    $this->load->model('client/addressver_model');
                    $data['address_details'] = $this->addressver_model->cand_address_details($id, $comp);
                    //  print_r($data['address_details']);
                    echo $this->load->view('client/address_details_ajax', $data, true);
                    break;
                case "courtver":
                    $this->load->model('client/court_verificatiion_model');
                    $data['court_details'] = $this->court_verificatiion_model->get_data(array('candidates_info.id' => $id), $comp);
                    echo $this->load->view('client/court_details_ajax', $data, true);
                    break;
                case "cbrver":
                    $this->load->model('client/credit_report_model');
                    $data['credit_report_details'] = $this->credit_report_model->get_credit_report_data(array('candidates_info.id' => $id), $comp);

                    echo $this->load->view('client/credit_report_details_ajax', $data, true);
                    break;
                case "narcver":
                    $this->load->model('client/drugs_model');
                    $data['drugs_details'] = $this->drugs_model->get_drugs_data(array('candidates_info.id' => $id), $comp);
                    echo $this->load->view('client/drugs_details_ajax', $data, true);
                    break;
                case "eduver":
                    $this->load->model('client/education_model');
                    $data['education_details'] = $this->education_model->cand_education_details($id, $comp);
                    echo $this->load->view('client/education_details_ajax', $data, true);
                    break;
                case "empver":
                    $this->load->model('client/employment_model');
                    $data['employment_details'] = $this->employment_model->cand_employment_details($id, $comp);
                    echo $this->load->view('client/employment_details_ajax', $data, true);
                    break;
                case "globdbver":
                    $this->load->model('client/global_database_model');
                    $data['global_db_details'] = $this->global_database_model->cand_global_data(array('candidates_info.id' => $id), $comp);
                    echo $this->load->view('client/global_db_details_ajax', $data, true);
                    break;
                case "crimver":
                    $this->load->model('client/pcc_model');
                    $data['pcc_details'] = $this->pcc_model->cand_pcc_details($id, $comp);
                    echo $this->load->view('client/pcc_details_ajax', $data, true);
                    break;
                case "refver":
                    $this->load->model('client/references_model');
                    $data['reference_details'] = $this->references_model->cand_reference_details(array('reference.candsid' => $id), $comp);
                    echo $this->load->view('client/reference_details_ajax', $data, true);
                    break;
                case "identity":
                    $this->load->model('client/identity_model');
                    $data['identity_details'] = $this->identity_model->cand_identity_details(array('identity.candsid' => $id), $comp);
                    echo $this->load->view('client/identity_details_ajax', $data, true);
                    break;

                default:
                    echo "No components found";
            }
        }
    }

    protected function tat_calculate($tat_data)
    {
        $this->load->library('tat_calculation');

        return $this->tat_calculation->tat_calculate($tat_data);
    }

    public function cands_component_details($tab = "", $client_id = "")
    {
        if ($this->input->is_ajax_request()) {
            $data['component_type'] = $tab;
            $client_id = ($client_id == 0) ? '' : array('clients.id' => $client_id);
            switch ($tab) {
                case "addrver":
                    $this->load->model('addressver_model');
                    $addres_total_count = $this->addressver_model->address_ver_status_count($client_id);
                    $data['component_counts'] = $this->convert_empployment_status($addres_total_count, 'verfstatus', 'count');
                    echo $this->load->view('component_vise_count', $data, true);
                    break;
                case "courtver":
                    $this->load->model('court_verificatiion_model');
                    $court_details = $this->court_verificatiion_model->court_ver_status_count($client_id);
                    $data['component_counts'] = convert_to_single_dimension_array($court_details, 'verfstatus', 'count');
                    echo $this->load->view('component_vise_count', $data, true);
                    break;
                case "cbrver":
                    echo "Your selected cbrver!";
                    break;
                case "narcver":
                    echo "Your selected narcver!";
                    break;
                case "eduver":
                    $this->load->model('education_model');
                    $education_details = $this->education_model->education_ver_status_count($client_id);
                    $data['component_counts'] = convert_to_single_dimension_array($education_details, 'verfstatus', 'count');
                    echo $this->load->view('component_vise_count', $data, true);
                    break;
                case "empver":
                    $this->load->model('employment_model');
                    $employment_details = $this->employment_model->employment_ver_status_count($client_id);
                    $data['component_counts'] = $this->convert_empployment_status($employment_details, 'verfstatus', 'count');
                    echo $this->load->view('component_vise_count', $data, true);
                    break;
                case "globdbver":
                    $this->load->model('global_database_model');
                    $global_db_details = $this->global_database_model->global_db_ver_status_count($client_id);
                    $data['component_counts'] = convert_to_single_dimension_array($global_db_details, 'verfstatus', 'count');
                    echo $this->load->view('component_vise_count', $data, true);
                    break;
                case "crimver":
                    $this->load->model('pcc_model');
                    $pcc_details = $this->pcc_model->pcc_ver_status_count($client_id);
                    $data['component_counts'] = convert_to_single_dimension_array($pcc_details, 'verfstatus', 'count');
                    echo $this->load->view('component_vise_count', $data, true);
                    break;
                case "claim_investigation":
                    $this->load->model('claim_investigation_model');
                    $pcc_details = $this->claim_investigation_model->claim_result_ver_count($client_id);
                    $data['component_counts'] = convert_to_single_dimension_array($pcc_details, 'verfstatus', 'count');
                    echo $this->load->view('component_vise_count_cashless', $data, true);
                    break;
                case "refver":
                    echo "Your selected refver!";
                    break;

                default:
                    echo "No components found";
            }
        }
    }

    public function client_components_data($tab = "", $component_status = 'all', $client_id = "")
    {
        if ($this->input->is_ajax_request()) {
            $client_id = ($client_id == 0) ? '' : array('cands.clientid' => $client_id);
            switch ($tab) {
                case "addrver":
                    $this->load->model('addressver_model');
                    $data['address_lists'] = $this->addressver_model->address_ver_by_status($component_status, $client_id);
                    echo $this->load->view('address_ajax_tab_by_status_client', $data, true);
                    break;
                case "courtver":
                    $this->load->model('court_verificatiion_model');
                    $data['court_list'] = $this->court_verificatiion_model->court_ver_by_status($component_status, $client_id);
                    echo $this->load->view('court_ajax_tab_by_status_client', $data, true);
                    break;
                case "cbrver":
                    echo "Your selected cbrver!";
                    break;
                case "narcver":
                    echo "Your selected narcver!";
                    break;
                case "eduver":
                    $this->load->model('education_model');
                    $data['education_lists'] = $this->education_model->education_ver_by_status($component_status, $client_id);
                    echo $this->load->view('education_ajax_tab_by_status_client', $data, true);
                    break;
                case "empver":
                    $this->load->model('employment_model');
                    $data['employment_lists'] = $this->employment_model->employment_ver_by_status($component_status, $client_id);
                    echo $this->load->view('employment_ajax_tab_by_status_client', $data, true);
                    break;
                case "globdbver":
                    $this->load->model('global_database_model');
                    $data['global_db_lists'] = $this->global_database_model->global_db_ver_by_status($component_status, $client_id);
                    echo $this->load->view('global_db_ajax_tab_by_status_client', $data, true);
                    break;
                case "crimver":
                    $this->load->model('pcc_model');
                    $data['crimver_lists'] = $this->pcc_model->pcc_ver_by_status($component_status, $client_id);
                    echo $this->load->view('pcc_ajax_tab_by_status_client', $data, true);
                    break;
                case "claim_investigation":
                    $this->load->model('claim_investigation_model');
                    $data['claim_lists'] = $this->claim_investigation_model->claim_investigation_ver_by_status($component_status, $client_id);
                    echo $this->load->view('claim_ajax_tab_by_status_client', $data, true);
                    break;
                case "refver":
                    echo "Your selected refver!";
                    break;

                default:
                    echo "No components found";
            }
        }
    }

    public function new_candidates()
    {
        $data['header_title'] = "New Candidates";

        $data['clients'] = $this->get_clients();

        if ($this->input->post()) {
            $this->form_validation->set_rules('clientid', 'Client', 'required');

            $this->form_validation->set_rules('caserecddate', 'Case received', 'required');

            $this->form_validation->set_rules('ClientRefNumber', 'Client Ref Number', 'required|is_unique[cands.ClientRefNumber]');

            $this->form_validation->set_rules('CandidateName', 'Candidate Name', 'required');

            if ($this->form_validation->run() == false) {
                $this->load->view('admin/header', $data);

                $this->load->view('admin/candidates_add');

                $this->load->view('admin/footer');
            } else {
                $frm_details = $this->input->post();

                $fields = array('clientid' => $frm_details['clientid'],
                    'caserecddate' => convert_display_to_db_date($frm_details['caserecddate']),
                    'cmp_ref_no' => $frm_details['cmp_ref_no'],
                    'ClientRefNumber' => $frm_details['ClientRefNumber'],
                    'CandidateName' => ucwords($frm_details['CandidateName']),
                    "gender" => $frm_details['gender'],
                    'DateofBirth' => convert_display_to_db_date($frm_details['DateofBirth']),
                    'NameofCandidateFather' => ucwords($frm_details['NameofCandidateFather']),
                    'MothersName' => ucwords($frm_details['MothersName']),
                    'CandidatesContactNumber' => $frm_details['CandidatesContactNumber'],
                    'ContactNo1' => $frm_details['ContactNo1'],
                    'ContactNo2' => $frm_details['ContactNo2'],
                    'DateofJoining' => convert_display_to_db_date($frm_details['DateofJoining']),
                    'Location' => $frm_details['Location'],
                    'DesignationJoinedas' => $frm_details['DesignationJoinedas'],
                    'Department' => $frm_details['Department'],
                    'EmployeeCode' => $frm_details['EmployeeCode'],
                    'PANNumber' => $frm_details['PANNumber'],
                    'AadharNumber' => $frm_details['AadharNumber'],
                    'PassportNumber' => $frm_details['PassportNumber'],
                    'BatchNumber' => $frm_details['BatchNumber'],
                    'insuffraisedate' => convert_display_to_db_date($frm_details['insuffraisedate']),
                    'insuffcleardate' => convert_display_to_db_date($frm_details['insuffcleardate']),
                    'overallstatus' => $frm_details['overallstatus'],
                    'remarks' => $frm_details['remarks'],
                    'created_by' => 1,
                    'created' => date(DB_DATE_FORMAT),
                );

                $result = $this->candidates_model_wip->save($fields);

                if ($result) {
                    $this->session->set_flashdata('message', array('message' => 'Client Create Successfully', 'class' => 'alert alert-success fade in'));

                    redirect('admin/candidates');
                } else {
                    $this->session->set_flashdata('message', array('message' => 'Something went wrong, please try again', 'class' => 'alert alert-danger fade in'));

                    redirect('admin/candidates');
                }
            }
        } else {
            $this->load->view('admin/header', $data);

            $this->load->view('admin/candidates_add');

            $this->load->view('admin/footer');
        }
    }

    public function report($id = null)
    {

        if (!empty($id)) {

            $this->load->library('example');

            $id = decrypt($id);

            $report = array();

            $cands_result = $this->candidates_model_wip->get_candidates_info_info_report(array('candidates_info.id' => $id));

            if (!empty($cands_result)) {
                $report['cand_info'] = $cands_result;

                $report['education_info'] = $this->candidates_model_wip->education_info_report(array('education.candsid' => $id, 'education_files.type' => 1));

                $report['employment_info'] = $this->candidates_model_wip->employment_info_report(array('empver.candsid' => $id));

                $report['address_info'] = $this->candidates_model_wip->address_info_report(array('addrver.candsid' => $id));

                $report['pcc_info'] = $this->candidates_model_wip->pcc_info_report(array('p1.candsid' => $id));

                $report['court_info'] = $this->candidates_model_wip->courtinfo_report(array('courtver.candsid' => $id));

                $report['references_info'] = $this->candidates_model_wip->references_report(array('reference.candsid' => $id));

                $report['status'] = OVERALL_STATUS;

                /* if($cands_result['comp_logo'] != "")
                {
                $cleit_logo_path  = SERVER_SITE_URL.CLIENT_LOGO_PATH.'/'.$cands_result['comp_logo'];
                define('CLIENT_LOGO',$cleit_logo_path);
                }
                else
                {
                define('CLIENT_LOGO', '');
                }
                 */

                $this->example->generate_pdf($report);
            } else {
                show_404();
            }
        } else {
            redirect('admin/candidates');
        }

    }

    private function echo_json($json_array)
    {
        echo_json($json_array);

        exit;
    }

    public function get_clients_by_components($tab)
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {
            $json_array['message'] = $this->get_clients_by_component($tab);
        }

        $this->echo_json($json_array);
    }

    public function report_download()
    {
        $data['header_title'] = "Download Reports";

        $data['clients'] = $this->get_clients();

        $this->load->view('header', $data);

        $this->load->view('download_reports');

        $this->load->view('footer');
    }

    public function report_download_new()
    {
        $data['header_title'] = "Download Reports";

        $data['clients'] = $this->get_clients();

        $file_upload_path = SITE_BASE_PATH . UPLOAD_FOLDER . 'bulk_reports/';
        $file_path = UPLOAD_FOLDER . 'bulk_reports/';

//        $data['list_downloads']['folders'] = $this->listdir_by_date($file_upload_path);

        $this->db->select("folder_name, created_on");
        $this->db->from('report_requested');
        $this->db->where('folder_generated_status !=', 0);
        $this->db->where('folder_name !=', '');
        $this->db->limit(20, 0);
        $this->db->order_by('id', 'desc');

        $result = $this->db->get();
        $final_array = $result->result_array();

        foreach ($final_array as $key => $value) {
            $files_array = $this->listdir_by_date($file_upload_path . $value['folder_name']);
            if (!empty($files_array)) {
                foreach ($files_array as $key_inner => $value_inner) {
                    $data['list_downloads']['files'][$value['folder_name']] = $value['created_on'] . "~" . $value_inner;
                }
            }

        }

        $data['list_downloads']['path'] = $file_path;

        $this->load->view('header', $data);

        $this->load->view('download_reports', $data['list_downloads']);

        $this->load->view('footer');
    }

    public function listdir_by_date($path)
    {
        $dir = opendir($path);
        $list = array();
        while ($file = readdir($dir)) {
            if ($file != '.' and $file != '..') {
//                $ctime = filemtime($path . $file) .":".  $file;
                $ctime = date("d-m-Y H:i:s", @filemtime($path . $file)) . "~" . $file;
                $list[$ctime] = $file;
            }
        }
        closedir($dir);
        krsort($list);
        return $list;
    }

    public function genetare_excel_reprots()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {
            $frm_details = $this->input->post();

            $client_id = $frm_details['clientid'];

            $client_name = $this->session->userdata('clientname');

            if (!empty($frm_details)) {
                $requested_data = array('type' => 'Excel', 'requested_id' => $this->session->userdata('id'), 'query' => '', 'created_on' => date(DB_DATE_FORMAT), 'mail_sent_status' => 0, 'folder_generated_status' => 0, 'downloaded_status' => 0, 'downloaded_on_date' => '', 'folder_name' => '');

                $report_requested_save_id = $this->candidates_model_wip->report_requested_save($requested_data);

                $cands_info = array();

                if ($report_requested_save_id) {
                    $cands_info['clientid'] = $client_id;

                    $cands_info['clinet_name'] = $client_name;

                    $cands_info['first_name'] = $this->session->userdata('first_name');

                    $cands_info['email_id'] = $frm_details['entered_email'];

                    $cands_info['report_requested_save_id'] = $report_requested_save_id;

                    $cands_info['frm_data'] = $frm_details;

                    $parameters_array = serialize($cands_info);

                    $parameters_array = base64_encode($parameters_array);

                    $url = "cli_request_only generate_excel_report $parameters_array";

                    $cmd = 'php C:\xampp\htdocs\client_bgv\index.php ' . $url;

                    $this->candidates_model_wip->report_requested_save(array('query' => $cmd), array('id' => $report_requested_save_id));

                    if (substr(php_uname(), 0, 7) == "Windows") {
                        pclose(popen("start /MIN " . $cmd, "r"));
                    } else {
                        exec($cmd . " > /dev/null &");
                    }

                    $json_array['message'] = "Reports are generating we'll send you mail on " . $cands_info['email_id'] . " email id";

                    $json_array['status'] = SUCCESS_CODE;
                } else {
                    $json_array['message'] = 'Select date range no cases completed';

                    $json_array['status'] = ERROR_CODE;
                }
            } else {
                $json_array['message'] = 'Something went wrong, please try again';

                $json_array['status'] = ERROR_CODE;
            }
        } else {
            $json_array['message'] = "Something went wrong,please try again";

            $json_array['status'] = ERROR_CODE;
        }

        $this->echo_json($json_array);
    }

    /*public function genetare_excel_reprots1()
    {
    $json_array = array();

    if($this->input->is_ajax_request())
    {

    $clientid = $this->input->post('clientid');

    $from_date = $this->input->post('from_date');

    $to_date = $this->input->post('to_date');

    $prod  =  $this->candidates_model_wip->get_excel($clientid,array('from_date' => $from_date,'to_date' => $to_date));

    require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $spreadsheet->getProperties()->setCreator(CRMNAME)
    ->setLastModifiedBy(CRMNAME)
    ->setTitle(CRMNAME)
    ->setSubject('Employment records')
    ->setDescription('Employment records with their status');
    // add style to the header
    $styleArray = array(
    'font' => array(
    'bold' => true,
    ),
    'alignment' => array(
    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    ),
    );
    $spreadsheet->getActiveSheet()->getStyle('A1:T1')->applyFromArray($styleArray);
    // auto fit column to content
    foreach(range('A','T') as $columnID) {
    $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
    ->setWidth(20);
    }
    //$spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(5);
    // set the names of header cells
    $spreadsheet->setActiveSheetIndex(0)
    ->setCellValue("A1",'Agency Name')
    ->setCellValue('B1','Handover Date')
    ->setCellValue("C1",'Employee Code')
    ->setCellValue("D1",'Employee Name')
    ->setCellValue("E1",'Address Status')
    ->setCellValue("F1",'Insuf raise 1 remark')
    ->setCellValue('G1','Insuff raise 1 date')
    ->setCellValue("H1",'Insuf raise 2 remark')
    ->setCellValue('I1','Insuff raise 2 date')
    ->setCellValue('J1','Date of Address check close')
    ->setCellValue('K1','Employment_Status')
    ->setCellValue('L1','Closure remark')
    ->setCellValue('M1','Insuf raise 1 remark')
    ->setCellValue('N1','Insuff raise 1 date')
    ->setCellValue('O1','Insuf raise 2 remark')
    ->setCellValue('P1','Insuff raise 2 date')
    ->setCellValue('Q1','Discrepancy Remark 1(if any)')
    ->setCellValue('R1','Discrepancy Remark2 (if any)')
    ->setCellValue('S1','Date of Employment check close')
    ->setCellValue('T1','Verifiers Email ID');

    $x= 2;

    foreach($prod as $list)
    {
    $spreadsheet->setActiveSheetIndex(0)
    ->setCellValue("A$x","vistar INERNATIONAL")
    ->setCellValue("B$x",$list['clientname'])
    ->setCellValue("C$x",$list['Candidate Name'])
    ->setCellValue("D$x",$list['Initiated Date'])
    ->setCellValue("E$x",$list['tat_expiry_date'])
    ->setCellValue("F$x",$list['Status'])
    ->setCellValue("G$x",$list['TAT'])
    ->setCellValue("H$x",$list['Remark'])
    ->setCellValue("I$x",$list['user_name'])
    ->setCellValue("J$x",$tat_cases['TAT-Component']);
    $x++;
    }
    // Rename worksheet
    $spreadsheet->getActiveSheet()->setTitle('TAT Records');

    $spreadsheet->setActiveSheetIndex(0);

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment;filename= ".$tat_cases['TAT-Component'].".xls");
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

    $json_array['file'] = "data:application/vnd.ms-excel;base64,".base64_encode($xlsData);

    $json_array['file_name'] = $tat_cases['TAT-Component'];

    $json_array['message'] = "File downloaded successfully,please check in download folder";

    $json_array['status'] = SUCCESS_CODE;

    }

    $this->echo_json($json_array);
    }
     */
    public function genetare_pdf_reprots()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('month_of_reprots', 'From Date', 'required');

            if ($this->form_validation->run() == false) {
                $json_array['message'] = 'All fields required';

                $json_array['status'] = ERROR_CODE;
            } else {
                $frm_details = $this->input->post();

                if (!empty($frm_details)) {
                    $requested_data = array('type' => 'sales', 'requested_id' => $this->session->userdata('id'), 'query' => '', 'created_on' => date(DB_DATE_FORMAT), 'mail_sent_status' => 0, 'folder_generated_status' => 0, 'downloaded_status' => 0, 'downloaded_on_date' => '', 'folder_name' => '');

                    if ($this->user_role) {
                        $requested_data['type'] = 'client';
                    }

                    $report_requested_save_id = $this->candidates_model_wip->report_requested_save($requested_data);

                    $cands_info = array();

                    if ($report_requested_save_id) {
                        $cands_info['clientid'] = $frm_details['clientid'];

                        $cands_info['first_name'] = $this->session->userdata('first_name');

                        $cands_info['email_id'] = $frm_details['entered_email'];

                        $cands_info['report_requested_save_id'] = $report_requested_save_id;

                        $cands_info['frm_data'] = $frm_details;

                        $parameters_array = serialize($cands_info);

                        $parameters_array = base64_encode($parameters_array);

                        $url = "cli_request_only bg_generate_reports $parameters_array";

                        $cmd = 'php C:\xampp\htdocs\client_bgv\index.php ' . $url;

                        $this->candidates_model_wip->report_requested_save(array('query' => $cmd), array('id' => $report_requested_save_id));

                        if (substr(php_uname(), 0, 7) == "Windows") {
                            pclose(popen("start /MIN " . $cmd, "r"));
                        } else {
                            exec($cmd . " > /dev/null &");
                        }

                        $json_array['message'] = "Reports are generating we'll send you mail on " . $cands_info['email_id'] . " email id";

                        $json_array['status'] = SUCCESS_CODE;
                    } else {
                        $json_array['message'] = 'Select date range no cases completed';

                        $json_array['status'] = ERROR_CODE;
                    }
                } else {
                    $json_array['message'] = 'Something went wrong, please try again';

                    $json_array['status'] = ERROR_CODE;
                }
            }
        } else {
            $json_array['message'] = 'Something went wrong, please try again';

            $json_array['status'] = ERROR_CODE;
        }

        $this->echo_json($json_array);
    }

    public function downloading($id, $folder_name)
    {
        $folder_name = decrypt($folder_name);

        $status = $this->candidates_model_wip->report_requested_select(array('id' => $id, 'folder_name' => $folder_name, 'requested_id' => $this->session->userdata('id')));

        if (!empty($status)) {
            $this->db->set('downloaded_status', 'downloaded_status+1', false);

            $this->candidates_model_wip->report_requested_save(array('downloaded_on_date' => date(DB_DATE_FORMAT)), array('id' => $id));

            $path = SITE_BASE_PATH . UPLOAD_FOLDER . 'bulk_reports/' . $folder_name;

            $this->load->library('zip');

            $this->zip->compression_level = 0;

            $this->zip->read_dir($path, false);

            $this->zip->download($folder_name . '.zip');
        } else {
            $ci = get_instance();
            $ci->output->set_status_header(404);
            $data['heading'] = 'Info';
            $data['message'] = 'Report request and download request user are different.';
            $ci->load->view('errors/custom_error', $data);
        }
    }

    protected function convert_empployment_status($multi_dimension_array, $key_from_multi, $value_from_multi)
    {
        $single_dimension_array = array();

        $overall_status_cnt = array('WIP' => 0, 'Insufficiency' => 0, 'Discrepancy' => 0, 'Unable to Verify' => 0, 'Stop/Check' => 0, 'Clear' => 0);

        foreach ($multi_dimension_array as $multi_dimension) {
            $key = $multi_dimension[$key_from_multi];

            $value = $multi_dimension[$value_from_multi];

            if ($key == 'Clear' || $key == "No-Response" || $key == "Inaccessible") {
                $overall_status_cnt['Clear'] = $overall_status_cnt['Clear'] + $value;
            } else if ($key == 'Insufficiency' || $key == 'Insufficiency-Relieving Letter Required' || $key == "Insufficiency I" || $key == "Insufficiency II") {
                $overall_status_cnt['Insufficiency'] = $overall_status_cnt['Insufficiency'] + $value;
            } else if ($key == 'Discrepancy' || $key == 'No Record Found') {
                $overall_status_cnt['Discrepancy'] = $overall_status_cnt['Discrepancy'] + $value;
            } else if ($key == 'Unable to Verify' || $key == 'No Record Found') {
                $overall_status_cnt['Unable to Verify'] = $overall_status_cnt['Unable to Verify'] + $value;
            } else if ($key == 'Stop/Check' || $key == 'Work With the Same Organization') {
                $overall_status_cnt['Stop/Check'] = $overall_status_cnt['Stop/Check'] + $value;
            } else if ($key == 'WIP' || $key == "WIP-Initiated" || $key == "WIP – pending for clarification" || $key == "case initiated") {
                $overall_status_cnt['WIP'] = $overall_status_cnt['WIP'] + $value;
            }

        }
        return $overall_status_cnt;
    }

    public function tat_cases_export()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {
            $tat_cases = $this->input->post('TatLocalStoreage');

            $tat_cases = base64_decode($tat_cases);

            $tat_cases = unserialize($tat_cases);

            $this->load->library('tat_calculation');

            $prod = $this->tat_calculation->tat_cases_separete($tat_cases);

            require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
            // Create new Spreadsheet object
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            // Set document properties
            $spreadsheet->getProperties()->setCreator(CRMNAME)
                ->setLastModifiedBy(CRMNAME)
                ->setTitle(CRMNAME)
                ->setSubject('Employment records')
                ->setDescription('Employment records with their status');
            // add style to the header
            $styleArray = array(
                'font' => array(
                    'bold' => true,
                ),
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ),
            );
            $spreadsheet->getActiveSheet()->getStyle('A1:I1')->applyFromArray($styleArray);
            // auto fit column to content
            foreach (range('A', 'I') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                    ->setWidth(20);
            }
            //$spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(5);
            // set the names of header cells
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A1", 'Client Ref No.')
                ->setCellValue('B1', 'Client Name')
                ->setCellValue("C1", 'Candidate Name')
                ->setCellValue("D1", 'Initiated Date')
                ->setCellValue("E1", 'TAT Due Date')
                ->setCellValue("F1", 'Status')
                ->setCellValue('G1', 'Type')
                ->setCellValue("H1", 'Remark')
                ->setCellValue('I1', 'User Name')
                ->setCellValue('J1', 'Component');
            $x = 2;

            foreach ($prod as $list) {
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A$x", $list['Ref. Number'])
                    ->setCellValue("B$x", $list['clientname'])
                    ->setCellValue("C$x", $list['Candidate Name'])
                    ->setCellValue("D$x", $list['Initiated Date'])
                    ->setCellValue("E$x", $list['tat_expiry_date'])
                    ->setCellValue("F$x", $list['Status'])
                    ->setCellValue("G$x", $list['TAT'])
                    ->setCellValue("H$x", $list['Remark'])
                    ->setCellValue("I$x", $list['user_name'])
                    ->setCellValue("J$x", $tat_cases['TAT-Component']);
                $x++;
            }
            // Rename worksheet
            $spreadsheet->getActiveSheet()->setTitle('TAT Records');

            $spreadsheet->setActiveSheetIndex(0);

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment;filename= " . $tat_cases['TAT-Component'] . ".xls");
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

            $json_array['file_name'] = $tat_cases['TAT-Component'];

            $json_array['message'] = "File downloaded successfully,please check in download folder";

            $json_array['status'] = SUCCESS_CODE;
        }

        $this->echo_json($json_array);
    }

    public function tat_cases_export_all_comp()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {
            $this->load->model('addressver_model');
            $tat_calculate = $this->addressver_model->get_all_addrs_by_client(false);
            $address_tat = $this->tat_calculate($tat_calculate);
            $address_tat = $this->tat_calculation->tat_cases_separete($address_tat);

            $this->load->model('court_verificatiion_model');
            $tat_calculate = $this->court_verificatiion_model->get_all_court_client(false);
            $court_tat = $this->tat_calculate($tat_calculate);
            $court_tat = $this->tat_calculation->tat_cases_separete($court_tat);

            $this->load->model('education_model');
            $tat_calculate = $this->education_model->get_all_eduver_client(false);
            $education_lists = $this->tat_calculate($tat_calculate);
            $education_lists = $this->tat_calculation->tat_cases_separete($education_lists);

            $this->load->model('employment_model');
            $tat_calculate = $this->employment_model->get_all_emp_client(false);
            $employment_lists = $this->tat_calculate($tat_calculate);
            $employment_lists = $this->tat_calculation->tat_cases_separete($employment_lists);

            $this->load->model('global_database_model');
            $tat_calculate = $this->global_database_model->get_all_global_db_client(false);
            $global_db_lists = $this->tat_calculate($tat_calculate);
            $global_db_lists = $this->tat_calculation->tat_cases_separete($global_db_lists);

            $this->load->model('pcc_model');
            $tat_calculate = $this->pcc_model->get_all_pcc_client(false);
            $pcc_tat = $this->tat_calculate($tat_calculate);
            $pcc_tat = $this->tat_calculation->tat_cases_separete($pcc_tat);

            $this->load->model('references_model');
            $tat_calculate = $this->references_model->cand_reference_details(false);
            $references = $this->tat_calculate($tat_calculate);
            $references = $this->tat_calculation->tat_cases_separete($references);

            require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
            // Create new Spreadsheet object
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            // Set document properties
            $spreadsheet->getProperties()->setCreator(CRMNAME)
                ->setLastModifiedBy(CRMNAME)
                ->setTitle(CRMNAME)
                ->setSubject('Employment records')
                ->setDescription('Employment records with their status');
            // add style to the header
            $styleArray = array(
                'font' => array(
                    'bold' => true,
                ),
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
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
                ->setCellValue('B1', 'Client Name')
                ->setCellValue("C1", 'Candidate Name')
                ->setCellValue("D1", 'Initiated Date')
                ->setCellValue("E1", 'TAT Due Date')
                ->setCellValue("F1", 'Status')
                ->setCellValue('G1', 'Type')
                ->setCellValue("H1", 'Remark')
                ->setCellValue('I1', 'User Name')
                ->setCellValue('J1', 'Component');
            $x = 2;

            foreach ($address_tat as $list) {
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A$x", $list['Ref. Number'])
                    ->setCellValue("B$x", $list['clientname'])
                    ->setCellValue("C$x", $list['Candidate Name'])
                    ->setCellValue("D$x", $list['Initiated Date'])
                    ->setCellValue("E$x", $list['tat_expiry_date'])
                    ->setCellValue("F$x", $list['Status'])
                    ->setCellValue("G$x", $list['TAT'])
                    ->setCellValue("H$x", $list['Remark'])
                    ->setCellValue("I$x", $list['user_name'])
                    ->setCellValue("J$x", 'Address');
                $x++;
            }

            foreach ($court_tat as $list) {
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A$x", $list['Ref. Number'])
                    ->setCellValue("B$x", $list['clientname'])
                    ->setCellValue("C$x", $list['Candidate Name'])
                    ->setCellValue("D$x", $list['Initiated Date'])
                    ->setCellValue("E$x", $list['tat_expiry_date'])
                    ->setCellValue("F$x", $list['Status'])
                    ->setCellValue("G$x", $list['TAT'])
                    ->setCellValue("H$x", $list['Remark'])
                    ->setCellValue("I$x", $list['user_name'])
                    ->setCellValue("J$x", 'Court Records');
                $x++;
            }

            foreach ($education_lists as $list) {
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A$x", $list['Ref. Number'])
                    ->setCellValue("B$x", $list['clientname'])
                    ->setCellValue("C$x", $list['Candidate Name'])
                    ->setCellValue("D$x", $list['Initiated Date'])
                    ->setCellValue("E$x", $list['tat_expiry_date'])
                    ->setCellValue("F$x", $list['Status'])
                    ->setCellValue("G$x", $list['TAT'])
                    ->setCellValue("H$x", $list['Remark'])
                    ->setCellValue("I$x", $list['user_name'])
                    ->setCellValue("J$x", 'Education');
                $x++;
            }

            foreach ($employment_lists as $list) {
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A$x", $list['Ref. Number'])
                    ->setCellValue("B$x", $list['clientname'])
                    ->setCellValue("C$x", $list['Candidate Name'])
                    ->setCellValue("D$x", $list['Initiated Date'])
                    ->setCellValue("E$x", $list['tat_expiry_date'])
                    ->setCellValue("F$x", $list['Status'])
                    ->setCellValue("G$x", $list['TAT'])
                    ->setCellValue("H$x", $list['Remark'])
                    ->setCellValue("I$x", $list['user_name'])
                    ->setCellValue("J$x", 'Employment');
                $x++;
            }

            foreach ($global_db_lists as $list) {
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A$x", $list['Ref. Number'])
                    ->setCellValue("B$x", $list['clientname'])
                    ->setCellValue("C$x", $list['Candidate Name'])
                    ->setCellValue("D$x", $list['Initiated Date'])
                    ->setCellValue("E$x", $list['tat_expiry_date'])
                    ->setCellValue("F$x", $list['Status'])
                    ->setCellValue("G$x", $list['TAT'])
                    ->setCellValue("H$x", $list['Remark'])
                    ->setCellValue("I$x", $list['user_name'])
                    ->setCellValue("J$x", 'GLobal DB');
                $x++;
            }

            foreach ($pcc_tat as $list) {
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A$x", $list['Ref. Number'])
                    ->setCellValue("B$x", $list['clientname'])
                    ->setCellValue("C$x", $list['Candidate Name'])
                    ->setCellValue("D$x", $list['Initiated Date'])
                    ->setCellValue("E$x", $list['tat_expiry_date'])
                    ->setCellValue("F$x", $list['Status'])
                    ->setCellValue("G$x", $list['TAT'])
                    ->setCellValue("H$x", $list['Remark'])
                    ->setCellValue("I$x", $list['user_name'])
                    ->setCellValue("J$x", 'PCC Records');
                $x++;
            }

            foreach ($references as $list) {
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A$x", $list['Ref. Number'])
                    ->setCellValue("B$x", $list['clientname'])
                    ->setCellValue("C$x", $list['Candidate Name'])
                    ->setCellValue("D$x", $list['Initiated Date'])
                    ->setCellValue("E$x", $list['tat_expiry_date'])
                    ->setCellValue("F$x", $list['Status'])
                    ->setCellValue("G$x", $list['TAT'])
                    ->setCellValue("H$x", $list['Remark'])
                    ->setCellValue("I$x", $list['user_name'])
                    ->setCellValue("J$x", 'References');
                $x++;
            }
            // Rename worksheet
            $spreadsheet->getActiveSheet()->setTitle('TAT Records');

            $spreadsheet->setActiveSheetIndex(0);

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment;filename= All-Component-TAT-Cases-" . date(DB_DATE_FORMAT) . ".xls");
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

            $json_array['file_name'] = "All-Component-TAT-Cases" . date(DB_DATE_FORMAT);

            $json_array['message'] = "File downloaded successfully,please check in download folder";

            $json_array['status'] = SUCCESS_CODE;
        } else {
            $json_array['message'] = "Something went wrong, please try again";

            $json_array['status'] = ERROR_CODE;
        }
        $this->echo_json($json_array);
    }
}