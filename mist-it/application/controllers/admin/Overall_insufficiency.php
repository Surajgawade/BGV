<?php defined('BASEPATH') or exit('No direct script access allowed');

class Overall_insufficiency extends MY_Controller
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

        $this->perm_array = array('page_id' => 43);

    }

    public function index()
    {
        $data['header_title'] = "All Component Insuff List";

        $data['clients'] = $this->get_clients(array('status' => STATUS_ACTIVE));

        $data['components_key_val'] = convert_to_single_dimension_array($this->components(), 'component_key', 'component_name');

        $data['clients_id'] = $this->get_clients(array('status' => STATUS_ACTIVE));

        $this->load->view('admin/header', $data);

        $this->load->view('admin/overall_insuff');

        $this->load->view('admin/footer');
    }

    public function get_component_insufficiency()
    {
        $this->load->model('overall_insufficiency_model');

        $data['header_title'] = "All Component Insuff List";

        $data['clients'] = $this->get_clients(array('status' => STATUS_ACTIVE));

        $data['components_key_val'] = convert_to_single_dimension_array($this->components(), 'component_key', 'component_name');

        $data['insuff_details'] = $this->overall_insufficiency_model->select_overall_insuff();

        echo $this->load->view('admin/component_insufficiency', $data, true);

    }

    public function data_table_cands_view_insufficiency()
    {
        if ($this->input->is_ajax_request()) {

            $params = $data_arry = $columns = array();

            $params = $_REQUEST;

            $columns = array('id', 'CandidateName', "clientname", "caserecddate", 'ClientRefNumber', 'cmp_ref_no', 'overallstatus', 'remarks', 'view', 'encry_id');

            $cands_results = $this->candidates_model_insufficiency->get_all_cand_with_search($this->user_package, $this->user_role, $params, $columns);

            $totalRecords = count($this->candidates_model_insufficiency->get_all_cand_with_search_count($this->user_package, $this->user_role, $params, $columns));
            $x = 0;

            foreach ($cands_results as $key => $cands_result) {
                $data_arry[$x]['id'] = $x + 1;
                $data_arry[$x]['CandidateName'] = ucwords(strtolower($cands_result['CandidateName']));
                $data_arry[$x]['Entity'] = ucwords(strtolower($cands_result['entity_name']));
                $data_arry[$x]['Package'] = ucwords(strtolower($cands_result['package_name']));
                $data_arry[$x]['ClientRefNumber'] = $cands_result['ClientRefNumber'];
                $data_arry[$x]['cmp_ref_no'] = $cands_result['cmp_ref_no'];
                $data_arry[$x]['overallstatus'] = $cands_result['status_value'];
                $data_arry[$x]['edit_id'] = CLIENT_SITE_URL . "candidates/view_details/" . encrypt($cands_result['id']);
                $data_arry[$x]['overallclosuredate'] = convert_db_to_display_date($cands_result['overallclosuredate']);
                $data_arry[$x]['remarks'] = $cands_result['remarks'];
                $data_arry[$x]['Action'] = "";

                $data_arry[$x]['clientname'] = ucwords(strtolower($cands_result['clientname']));
                $data_arry[$x]['caserecddate'] = convert_db_to_display_date($cands_result['caserecddate']);

                $data_arry[$x]['pending_check'] = 'NA';
                $data_arry[$x]['insufficiency_check'] = 'NA';
                $data_arry[$x]['closed_check'] = 'NA';

                $array_wip = array();
                $array_insufficiency = array();
                $array_closed = array();

                $result_address = $this->candidates_model_insufficiency->get_addres_ver_status(array('candidates_info.id' => $cands_result['id']));

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

                $result_education = $this->candidates_model_insufficiency->get_education_ver_status(array('candidates_info.id' => $cands_result['id']));

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

                $result_employment = $this->candidates_model_insufficiency->get_employment_ver_status(array('candidates_info.id' => $cands_result['id']));

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

                $result_court = $this->candidates_model_insufficiency->get_court_ver_status(array('candidates_info.id' => $cands_result['id']));

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

                $result_pcc = $this->candidates_model_insufficiency->get_pcc_ver_status(array('candidates_info.id' => $cands_result['id']));

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

                $result_reference = $this->candidates_model_insufficiency->get_reference_ver_status(array('reference.candsid' => $cands_result['id']));
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

                $result_global = $this->candidates_model_insufficiency->get_global_db_ver_status(array('candidates_info.id' => $cands_result['id']));
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

            echo_json($json_data);
        }
    }

}
