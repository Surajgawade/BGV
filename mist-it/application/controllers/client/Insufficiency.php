
<?php defined('BASEPATH') or exit('No direct script access allowed');

class Insufficiency extends MY_Client_Cotroller
{

    public function __construct()
    {
        parent::__construct();

        if (!$this->is_client_logged_in()) {
            redirect('client/login');
            exit();
        }
        $this->perm_array = array('direct_access' => true);
        $this->load->model(array('client/candidates_model_insufficiency'));
    }
    public function index()
    {

        $data['header_title'] = "Insufficiency - List View";

        $data['insuff_details'] = $this->candidates_model_insufficiency->select_overall_insuff();

        $data['clients'] = $this->get_clients(array('status' => STATUS_ACTIVE));

        $this->load->view('client/header', $data);

        $this->load->view('client/candidates_insufficiency');

        $this->load->view('client/footer');

    }

    public function get_component_insufficiency()
    {
        $this->load->model('overall_insufficiency_model');

        $data['header_title'] = "All Component Insuff List";

        $data['clients'] = $this->get_clients(array('status' => STATUS_ACTIVE));

        $data['insuff_details'] = $this->candidate_insufficiency_model->select_overall_insuff();

        echo $this->load->view('client/component_insufficiency', $data, true);

    }

    public function get_entity_list()
    {
        if ($this->input->is_ajax_request()) {

            $entity_list = $this->get_entiry_list1(array('tbl_client_id' => $this->input->post('clientid'), 'is_entity_package' => 1));

            echo form_dropdown('entity', $entity_list, set_value('package', $this->input->post('selected_entityy')), 'class="form-control" id="entity"');
        }
    }

    public function get_package_list()
    {
        if ($this->input->is_ajax_request()) {
            $package_list = $this->get_entiry_package_list1(array('is_entity' => $this->input->post('entity'), 'is_entity_package' => 2));

            echo form_dropdown('package', $package_list, set_value('package', $this->input->post('selected_paclage')), 'class="form-control" id="package"');
        }
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

                $address_results = $this->candidates_model_insufficiency->get_address_result($cands_result['id']);

                $address_count = 1;

                foreach ($address_results as $key => $address_result) {
                    $data_arry[$x]['address']['Component Name' . $address_count] = ucwords($address_result['component_name']);

                    $data_arry[$x]['address']['Component Id' . $address_count] = ucwords($address_result['component_id']);

                    $data_arry[$x]['address']['Component Received Date' . $address_count] = convert_db_to_display_date($address_result['iniated_date']);
                    $data_arry[$x]['address']['Raised Date' . $address_count] = $address_result['insuff_raised_date'];
                    $data_arry[$x]['address']['Raised Remark' . $address_count] = $address_result['insuff_raise_remark'];
                    $data_arry[$x]['address']['Clear button' . $address_count] = '<button data-id="' . $address_result['id'] . '"  data-candsid="' . $address_result['candidate_id'] . '"  data-toggle="modal" data-target="#insuffClearModel"  data-clientid="' . $address_result['clientid'] . '" data-accessUrl="' . $address_result['insuff_id'] . '" data-controller="' . $address_result['controller'] . '" class="btn btn-sm btn-info  tbl_row_edit">Clear</button>';

                    $address_count++;
                }

                $employment_results = $this->candidates_model_insufficiency->get_employment_result($cands_result['id']);

                $employment_count = 1;

                foreach ($employment_results as $key => $employment_result) {

                    $data_arry[$x]['employment']['ComponentName' . $employment_count] = ucwords($employment_result['component_name']);

                    $data_arry[$x]['employment']['Component Id' . $employment_count] = ucwords($employment_result['component_id']);
                    $data_arry[$x]['employment']['Component Received Date' . $employment_count] = convert_db_to_display_date($employment_result['iniated_date']);
                    $data_arry[$x]['employment']['Raised Date' . $employment_count] = $employment_result['insuff_raised_date'];
                    $data_arry[$x]['employment']['Raised Remark' . $employment_count] = $employment_result['insuff_raise_remark'];

                    $data_arry[$x]['employment']['Clear button' . $employment_count] = '<button data-id="' . $employment_result['id'] . '"  data-candsid="' . $employment_result['candidate_id'] . '"  data-toggle="modal" data-target="#insuffClearModel"  data-clientid="' . $employment_result['clientid'] . '" data-accessUrl="' . $employment_result['insuff_id'] . '" data-controller="' . $employment_result['controller'] . '" class="btn btn-sm btn-info  tbl_row_edit" >Clear</button>';

                    $employment_count++;
                }

                $education_results = $this->candidates_model_insufficiency->get_education_result($cands_result['id']);

                $education_count = 1;

                foreach ($education_results as $key => $education_result) {
                    $data_arry[$x]['education']['Component Name' . $education_count] = ucwords($education_result['component_name']);

                    $data_arry[$x]['education']['Component Id' . $education_count] = ucwords($education_result['component_id']);
                    $data_arry[$x]['education']['Component Received Date' . $education_count] = convert_db_to_display_date($education_result['iniated_date']);
                    $data_arry[$x]['education']['Raised Date' . $education_count] = $education_result['insuff_raised_date'];
                    $data_arry[$x]['education']['Raised Remark' . $education_count] = $education_result['insuff_raise_remark'];

                    $data_arry[$x]['education']['Clear button' . $education_count] = '<button data-id="' . $education_result['id'] . '"  data-candsid="' . $education_result['candidate_id'] . '"  data-toggle="modal" data-target="#insuffClearModel"  data-clientid="' . $education_result['clientid'] . '" data-accessUrl="' . $education_result['insuff_id'] . '" data-controller="' . $education_result['controller'] . '" class="btn btn-sm btn-info  tbl_row_edit" >Clear</button>';

                    $education_count++;
                }

                $reference_results = $this->candidates_model_insufficiency->get_reference_result($cands_result['id']);

                $reference_count = 1;

                foreach ($reference_results as $key => $reference_result) {
                    $data_arry[$x]['reference']['Component Name' . $reference_count] = ucwords($reference_result['component_name']);

                    $data_arry[$x]['reference']['Component Id' . $reference_count] = ucwords($reference_result['component_id']);
                    $data_arry[$x]['reference']['Component Received Date' . $reference_count] = convert_db_to_display_date($reference_result['iniated_date']);
                    $data_arry[$x]['reference']['Raised Date' . $reference_count] = $reference_result['insuff_raised_date'];
                    $data_arry[$x]['reference']['Raised Remark' . $reference_count] = $reference_result['insuff_raise_remark'];

                    $data_arry[$x]['reference']['Clear button' . $reference_count] = '<button data-id="' . $reference_result['id'] . '"  data-candsid="' . $reference_result['candidate_id'] . '"  data-toggle="modal" data-target="#insuffClearModel"  data-clientid="' . $reference_result['clientid'] . '" data-accessUrl="' . $reference_result['insuff_id'] . '" data-controller="' . $reference_result['controller'] . '" class="btn btn-sm btn-info  tbl_row_edit" >Clear</button>';

                    $reference_count++;
                }

                $court_results = $this->candidates_model_insufficiency->get_court_result($cands_result['id']);

                $court_count = 1;

                foreach ($court_results as $key => $court_result) {
                    $data_arry[$x]['court']['Component Name' . $court_count] = ucwords($court_result['component_name']);

                    $data_arry[$x]['court']['Component Id' . $court_count] = ucwords($court_result['component_id']);
                    $data_arry[$x]['court']['Component Received Date' . $court_count] = convert_db_to_display_date($court_result['iniated_date']);
                    $data_arry[$x]['court']['Raised Date' . $court_count] = $court_result['insuff_raised_date'];

                    $data_arry[$x]['court']['Raised Remark' . $court_count] = $court_result['insuff_raise_remark'];

                    $data_arry[$x]['court']['Clear button' . $court_count] = '<button data-id="' . $court_result['id'] . '"  data-candsid="' . $court_result['candidate_id'] . '"  data-toggle="modal" data-target="#insuffClearModel"  data-clientid="' . $court_result['clientid'] . '" data-accessUrl="' . $court_result['insuff_id'] . '" data-controller="' . $court_result['controller'] . '" class="btn btn-sm btn-info  tbl_row_edit" >Clear</button>';

                    $court_count++;
                }

                $global_results = $this->candidates_model_insufficiency->get_global_result($cands_result['id']);

                $global_count = 1;

                foreach ($global_results as $key => $global_result) {
                    $data_arry[$x]['global']['Component Name' . $global_count] = ucwords($global_result['component_name']);

                    $data_arry[$x]['global']['Component Id' . $global_count] = ucwords($global_result['component_id']);
                    $data_arry[$x]['global']['Component Received Date' . $global_count] = convert_db_to_display_date($global_result['iniated_date']);
                    $data_arry[$x]['global']['Raised Date' . $global_count] = $global_result['insuff_raised_date'];

                    $data_arry[$x]['global']['Raised Remark' . $global_count] = $global_result['insuff_raise_remark'];

                    $data_arry[$x]['global']['Clear button' . $global_count] = '<button data-id="' . $global_result['id'] . '"  data-candsid="' . $global_result['candidate_id'] . '"  data-toggle="modal" data-target="#insuffClearModel"  data-clientid="' . $global_result['clientid'] . '" data-accessUrl="' . $global_result['insuff_id'] . '" data-controller="' . $global_result['controller'] . '" class="btn btn-sm btn-info  tbl_row_edit">Clear</button>';

                    $global_count++;
                }

                $pcc_results = $this->candidates_model_insufficiency->get_pcc_result($cands_result['id']);

                $pcc_count = 1;

                foreach ($pcc_results as $key => $pcc_result) {
                    $data_arry[$x]['pcc']['Component Name' . $pcc_count] = ucwords($pcc_result['component_name']);

                    $data_arry[$x]['pcc']['Component Id' . $pcc_count] = ucwords($pcc_result['component_id']);
                    $data_arry[$x]['pcc']['Component Received Date' . $pcc_count] = convert_db_to_display_date($pcc_result['iniated_date']);
                    $data_arry[$x]['pcc']['Raised Date' . $pcc_count] = $pcc_result['insuff_raised_date'];

                    $data_arry[$x]['pcc']['Raised Remark' . $pcc_count] = $pcc_result['insuff_raise_remark'];

                    $data_arry[$x]['pcc']['Clear button' . $pcc_count] = '<button data-id="' . $pcc_result['id'] . '"  data-candsid="' . $pcc_result['candidate_id'] . '"  data-toggle="modal" data-target="#insuffClearModel"  data-clientid="' . $pcc_result['clientid'] . '" data-accessUrl="' . $pcc_result['insuff_id'] . '" data-controller="' . $pcc_result['controller'] . '" class="btn btn-sm btn-info  tbl_row_edit" >Clear</button>';

                    $pcc_count++;
                }

                $identity_results = $this->candidates_model_insufficiency->get_identity_result($cands_result['id']);

                $identity_count = 1;

                foreach ($identity_results as $key => $identity_result) {
                    $data_arry[$x]['identity']['Component Name' . $identity_count] = ucwords($identity_result['component_name']);
                    $data_arry[$x]['identity']['Component Id' . $identity_count] = ucwords($identity_result['component_id']);
                    $data_arry[$x]['identity']['Component Received Date' . $identity_count] = convert_db_to_display_date($identity_result['iniated_date']);
                    $data_arry[$x]['identity']['Raised Date' . $identity_count] = $identity_result['insuff_raised_date'];

                    $data_arry[$x]['identity']['Raised Remark' . $identity_count] = $identity_result['insuff_raise_remark'];

                    $data_arry[$x]['identity']['Clear button' . $identity_count] = '<button data-id="' . $identity_result['id'] . '"  data-candsid="' . $identity_result['candidate_id'] . '"  data-toggle="modal" data-target="#insuffClearModel"  data-clientid="' . $identity_result['clientid'] . '" data-accessUrl="' . $identity_result['insuff_id'] . '" data-controller="' . $identity_result['controller'] . '" class="btn btn-sm btn-info  tbl_row_edit" >Clear</button>';

                    $identity_count++;
                }

                $credit_report_results = $this->candidates_model_insufficiency->get_credit_report_result($cands_result['id']);

                $credit_report_count = 1;

                foreach ($credit_report_results as $key => $credit_report_result) {
                    $data_arry[$x]['credit_report']['Component Name' . $credit_report_count] = ucwords($credit_report_result['component_name']);
                    $data_arry[$x]['credit_report']['Component Id' . $credit_report_count] = ucwords($credit_report_result['component_id']);
                    $data_arry[$x]['credit_report']['Component Received Date' . $credit_report_count] = convert_db_to_display_date($credit_report_result['iniated_date']);

                    $data_arry[$x]['credit_report']['Raised Date' . $credit_report_count] = $credit_report_result['insuff_raised_date'];

                    $data_arry[$x]['credit_report']['Raised Remark' . $credit_report_count] = $credit_report_result['insuff_raise_remark'];

                    $data_arry[$x]['credit_report']['Clear button' . $credit_report_count] = '<button data-id="' . $credit_report_result['id'] . '"  data-candsid="' . $credit_report_result['candidate_id'] . '"  data-toggle="modal" data-target="#insuffClearModel"  data-clientid="' . $credit_report_result['clientid'] . '" data-accessUrl="' . $credit_report_result['insuff_id'] . '" data-controller="' . $credit_report_result['controller'] . '" class="btn btn-sm btn-info  tbl_row_edit" >Clear</button>';

                    $credit_report_count++;
                }

                $drugs_results = $this->candidates_model_insufficiency->get_drugs_result($cands_result['id']);

                $drugs_count = 1;

                foreach ($drugs_results as $key => $drugs_result) {
                    $data_arry[$x]['drugs']['Component Name' . $drugs_count] = ucwords($drugs_result['component_name']);

                    $data_arry[$x]['drugs']['Component Id' . $drugs_count] = ucwords($drugs_result['component_id']);
                    $data_arry[$x]['drugs']['Component Received Date' . $drugs_count] = convert_db_to_display_date($drugs_result['iniated_date']);
                    $data_arry[$x]['drugs']['Raised Date' . $drugs_count] = $drugs_result['insuff_raised_date'];

                    $data_arry[$x]['drugs']['Raised Remark' . $drugs_count] = $drugs_result['insuff_raise_remark'];

                    $data_arry[$x]['drugs']['Clear button' . $drugs_count] = '<button data-id="' . $drugs_result['id'] . '"  data-candsid="' . $drugs_result['candidate_id'] . '"  data-toggle="modal" data-target="#insuffClearModel"  data-clientid="' . $drugs_result['clientid'] . '" data-accessUrl="' . $drugs_result['insuff_id'] . '" data-controller="' . $drugs_result['controller'] . '" class="btn btn-sm btn-info  tbl_row_edit" >Clear</button>';

                    $drugs_count++;
                }

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
                $this->load->model(array('client/candidates_model'));

                $result_address_main = $this->candidates_model->get_addres_ver_status(array('candidates_info.id' => $cands_result['id']));

                if (!empty($result_address_main)) {
                    $address_count = 1;
                    foreach ($result_address_main as $result_address) {

                        $address_component_status = ($result_address['var_filter_status'] != "") ? $result_address['var_filter_status'] : 'WIP';
                        //print_r($data_arry[$x]['address_component_status']);
                        if (($address_component_status == "WIP") || ($address_component_status == "wip")) {
                            array_push($array_wip, 'Address');
                        }
                        if (($address_component_status == "Insufficiency") || ($address_component_status == "insufficiency")) {
                            array_push($array_insufficiency, 'Address ' . $address_count);
                        }
                        if (($address_component_status == "Closed") || ($address_component_status == "closed")) {
                            array_push($array_closed, 'Address');
                        }

                        $address_count++;

                    }
                }

                $result_education_main = $this->candidates_model->get_education_ver_status(array('candidates_info.id' => $cands_result['id']));

                if (!empty($result_education_main)) {
                    $education_count = 1;
                    foreach ($result_education_main as $result_education) {

                        $education_component_status = ($result_education['filter_status'] != "") ? $result_education['filter_status'] : 'WIP';

                        if (($education_component_status == "WIP") || ($education_component_status == "wip")) {
                            array_push($array_wip, 'Education');
                        }
                        if (($education_component_status == "Insufficiency") || ($education_component_status == "insufficiency")) {
                            array_push($array_insufficiency, 'Education ' . $education_count);
                        }
                        if (($education_component_status == "Closed") || ($education_component_status == "closed")) {
                            array_push($array_closed, 'Education');
                        }

                        $education_count++;
                    }
                }

                $result_employment_main = $this->candidates_model->get_employment_ver_status1(array('candidates_info.id' => $cands_result['id']));

                if (!empty($result_employment_main)) {
                    $employment_count = 1;

                    foreach ($result_employment_main as $result_employment) {

                        $employment_component_status = ($result_employment['var_filter_status'] != "") ? $result_employment['var_filter_status'] : 'WIP';

                        if (($employment_component_status == "WIP") || ($employment_component_status == "wip")) {
                            array_push($array_wip, "Employment");
                        }
                        if (($employment_component_status == "Insufficiency") || ($employment_component_status == "insufficiency")) {
                            array_push($array_insufficiency, 'Employment ' . $employment_count);
                        }
                        if (($employment_component_status == "Closed") || ($employment_component_status == "closed")) {
                            array_push($array_closed, 'Employment');
                        }
                        $employment_count++;
                    }
                }

                $result_court_main = $this->candidates_model->get_court_ver_status(array('candidates_info.id' => $cands_result['id']));

                if (!empty($result_court_main)) {
                    $court_count = 1;

                    foreach ($result_court_main as $result_court) {

                        $court_component_status = ($result_court['filter_status'] != "") ? $result_court['filter_status'] : 'WIP';
                        if (($court_component_status == "WIP") || ($court_component_status == "wip")) {
                            array_push($array_wip, "Court");
                        }
                        if (($court_component_status == "Insufficiency") || ($court_component_status == "insufficiency")) {
                            array_push($array_insufficiency, 'Court ' . $court_count);
                        }
                        if (($court_component_status == "Closed") || ($court_component_status == "closed")) {
                            array_push($array_closed, 'Court');
                        }

                        $court_count++;
                    }
                }

                $result_pcc_main = $this->candidates_model->get_pcc_ver_status(array('candidates_info.id' => $cands_result['id']));

                if (!empty($result_pcc_main)) {

                    $pcc_count = 1;

                    foreach ($result_pcc_main as $result_pcc) {

                        $pcc_component_status = ($result_pcc['filter_status'] != "") ? $result_pcc['filter_status'] : 'WIP';

                        if (($pcc_component_status == "WIP") || ($pcc_component_status == "wip")) {
                            array_push($array_wip, "PCC");
                        }
                        if (($pcc_component_status == "Insufficiency") || ($pcc_component_status == "insufficiency")) {
                            array_push($array_insufficiency, 'PCC ' . $pcc_count);
                        }
                        if (($pcc_component_status == "Closed") || ($pcc_component_status == "closed")) {
                            array_push($array_closed, 'PCC');
                        }

                        $pcc_count++;
                    }
                }

                $result_reference_main = $this->candidates_model->get_reference_ver_status(array('reference.candsid' => $cands_result['id']));

                if (!empty($result_reference_main)) {

                    $reference_count = 1;

                    foreach ($result_reference_main as $result_reference) {

                        $reference_component_status = ($result_reference['filter_status'] != "") ? $result_reference['filter_status'] : 'WIP';

                        if (($reference_component_status == "WIP") || ($reference_component_status == "wip")) {
                            array_push($array_wip, "Reference");
                        }
                        if (($reference_component_status == "Insufficiency") || ($reference_component_status == "insufficiency")) {
                            array_push($array_insufficiency, 'Reference ' . $reference_count);
                        }
                        if (($reference_component_status == "Closed") || ($reference_component_status == "closed")) {
                            array_push($array_closed, 'Reference');
                        }

                        $reference_count++;
                    }

                }

                $result_global_main = $this->candidates_model->get_global_db_ver_status(array('candidates_info.id' => $cands_result['id']));
                if (!empty($result_global_main)) {

                    $global_count = 1;

                    foreach ($result_global_main as $result_global) {

                        $globaldb_component_status = ($result_global['filter_status'] != "") ? $result_global['filter_status'] : 'WIP';

                        if (($globaldb_component_status == "WIP") || ($globaldb_component_status == "wip")) {
                            array_push($array_wip, "Global");
                        }
                        if (($globaldb_component_status == "Insufficiency") || ($globaldb_component_status == "insufficiency")) {
                            array_push($array_insufficiency, 'Global ' . $reference_count);
                        }
                        if (($globaldb_component_status == "Closed") || ($globaldb_component_status == "closed")) {
                            array_push($array_closed, 'Global');
                        }

                        $global_count++;
                    }
                }

                $result_identity_main = $this->candidates_model->get_identity_ver_status(array('candidates_info.id' => $cands_result['id']));

                if (!empty($result_identity_main)) {

                    $identity_count = 1;

                    foreach ($result_identity_main as $result_identity) {

                        $identity_component_status = ($result_identity['var_filter_status'] != "") ? $result_identity['var_filter_status'] : 'WIP';

                        if (($identity_component_status == "WIP") || ($identity_component_status == "wip")) {
                            array_push($array_wip, "Identity");
                        }
                        if (($identity_component_status == "Insufficiency") || ($identity_component_status == "insufficiency")) {
                            array_push($array_insufficiency, 'Identity ' . $identity_count);
                        }
                        if (($identity_component_status == "Closed") || ($identity_component_status == "closed")) {
                            array_push($array_closed, 'Identity');
                        }

                        $identity_count++;
                    }
                }

                $result_credit_report_main = $this->candidates_model->get_credit_reports_ver_status(array('candidates_info.id' => $cands_result['id']));
                if (!empty($result_credit_report_main)) {

                    $credit_report_count = 1;

                    foreach ($result_credit_report_main as $result_credit_report) {

                        $credit_report_component_status = ($result_credit_report['var_filter_status'] != "") ? $result_credit_report['var_filter_status'] : 'WIP';

                        if (($credit_report_component_status == "WIP") || ($credit_report_component_status == "wip")) {
                            array_push($array_wip, "Credit Report");
                        }
                        if (($credit_report_component_status == "Insufficiency") || ($credit_report_component_status == "insufficiency")) {
                            array_push($array_insufficiency, 'Credit Report ' . $credit_report_count);
                        }
                        if (($credit_report_component_status == "Closed") || ($credit_report_component_status == "closed")) {
                            array_push($array_closed, 'Credit Report');
                        }

                        $credit_report_count++;
                    }
                }

                $result_drugs_main = $this->candidates_model->get_narcver_ver_status(array('candidates_info.id' => $cands_result['id']));
                if (!empty($result_drugs)) {

                    $drugs_count = 1;

                    foreach ($result_drugs_main as $result_drugs) {

                        $drugs_component_status = ($result_drugs['var_filter_status'] != "") ? $result_drugs['var_filter_status'] : 'WIP';

                        if (($drugs_component_status == "WIP") || ($drugs_component_status == "wip")) {
                            array_push($array_wip, "Drugs");
                        }
                        if (($drugs_component_status == "Insufficiency") || ($drugs_component_status == "insufficiency")) {
                            array_push($array_insufficiency, 'Drugs ' . $drugs_count);
                        }
                        if (($drugs_component_status == "Closed") || ($drugs_component_status == "closed")) {
                            array_push($array_closed, 'Drugs');
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

            echo_json($json_data);
        }
    }

    public function insuff_raised_data($controller)
    {
        if ($this->input->is_ajax_request()) {
            if ($controller == "address") {
                $this->load->model('addressver_model');

                $insuff_data = $this->input->post('insuff_data');

                $result = $this->addressver_model->select_insuff(array('id' => $insuff_data));
                if (!empty($result)) {
                    $result = $result[0];
                    $result['insuff_raised_date'] = convert_db_to_display_date($result['insuff_raised_date']);
                    $result['insuff_clear_date'] = convert_db_to_display_date($result['insuff_clear_date']);
                }
                echo_json($result);

            }

            if ($controller == "employment") {
                $this->load->model('employment_model');

                $insuff_data = $this->input->post('insuff_data');

                $result = $this->employment_model->select_insuff(array('id' => $insuff_data));
                if (!empty($result)) {
                    $result = $result[0];
                    $result['insuff_raised_date'] = convert_db_to_display_date($result['insuff_raised_date']);
                    $result['insuff_clear_date'] = convert_db_to_display_date($result['insuff_clear_date']);
                }
                echo_json($result);

            }

            if ($controller == "education") {

                $this->load->model('education_model');

                $insuff_data = $this->input->post('insuff_data');

                $result = $this->education_model->select_insuff(array('id' => $insuff_data));
                if (!empty($result)) {
                    $result = $result[0];
                    $result['insuff_raised_date'] = convert_db_to_display_date($result['insuff_raised_date']);
                    $result['insuff_clear_date'] = convert_db_to_display_date($result['insuff_clear_date']);
                }
                echo_json($result);
            }

            if ($controller == "reference_verificatiion") {
                $this->load->model('reference_verificatiion_model');

                $insuff_data = $this->input->post('insuff_data');

                $result = $this->reference_verificatiion_model->select_insuff(array('id' => $insuff_data));
                if (!empty($result)) {
                    $result = $result[0];
                    $result['insuff_raised_date'] = convert_db_to_display_date($result['insuff_raised_date']);
                    $result['insuff_clear_date'] = convert_db_to_display_date($result['insuff_clear_date']);
                }
                echo_json($result);
            }

            if ($controller == "court_verificatiion") {
                $this->load->model('court_verificatiion_model');

                $insuff_data = $this->input->post('insuff_data');

                $result = $this->court_verificatiion_model->select_insuff(array('id' => $insuff_data));
                if (!empty($result)) {
                    $result = $result[0];
                    $result['insuff_raised_date'] = convert_db_to_display_date($result['insuff_raised_date']);
                    $result['insuff_clear_date'] = convert_db_to_display_date($result['insuff_clear_date']);
                }
                echo_json($result);
            }

            if ($controller == "global_database") {
                $this->load->model('global_database_model');

                $insuff_data = $this->input->post('insuff_data');

                $result = $this->global_database_model->select_insuff(array('id' => $insuff_data));
                if (!empty($result)) {
                    $result = $result[0];
                    $result['insuff_raised_date'] = convert_db_to_display_date($result['insuff_raised_date']);
                    $result['insuff_clear_date'] = convert_db_to_display_date($result['insuff_clear_date']);
                }
                echo_json($result);
            }

            if ($controller == "drugs_narcotics") {
                $this->load->model('drug_verificatiion_model');

                $insuff_data = $this->input->post('insuff_data');

                $result = $this->drug_verificatiion_model->select_insuff(array('id' => $insuff_data));
                if (!empty($result)) {
                    $result = $result[0];
                    $result['insuff_raised_date'] = convert_db_to_display_date($result['insuff_raised_date']);
                    $result['insuff_clear_date'] = convert_db_to_display_date($result['insuff_clear_date']);
                }
                echo_json($result);
            }

            if ($controller == "pcc") {
                $this->load->model('pcc_verificatiion_model');

                $insuff_data = $this->input->post('insuff_data');

                $result = $this->pcc_verificatiion_model->select_insuff(array('id' => $insuff_data));
                if (!empty($result)) {
                    $result = $result[0];
                    $result['insuff_raised_date'] = convert_db_to_display_date($result['insuff_raised_date']);
                    $result['insuff_clear_date'] = convert_db_to_display_date($result['insuff_clear_date']);
                }
                echo_json($result);
            }

            if ($controller == "identity") {
                $this->load->model('identity_model');

                $insuff_data = $this->input->post('insuff_data');

                $result = $this->identity_model->select_insuff(array('id' => $insuff_data));
                if (!empty($result)) {
                    $result = $result[0];
                    $result['insuff_raised_date'] = convert_db_to_display_date($result['insuff_raised_date']);
                    $result['insuff_clear_date'] = convert_db_to_display_date($result['insuff_clear_date']);
                }
                echo_json($result);
            }

            if ($controller == "Credit_report") {
                $this->load->model('credit_report_model');

                $insuff_data = $this->input->post('insuff_data');

                $result = $this->credit_report_model->select_insuff(array('id' => $insuff_data));
                if (!empty($result)) {
                    $result = $result[0];
                    $result['insuff_raised_date'] = convert_db_to_display_date($result['insuff_raised_date']);
                    $result['insuff_clear_date'] = convert_db_to_display_date($result['insuff_clear_date']);
                }
                echo_json($result);
            }

        }

    }

    public function insuff_clear($controller)
    {
        $json_array = array();

        $frm_details = $this->input->post();

        if ($this->input->is_ajax_request()) {

            if ($frm_details['insuff_clear_date'] >= $frm_details['check_insuff_raise']) {

                if ($controller == "address") {

                    $this->load->model('addressver_model');

                    $insuff_date = $frm_details['insuff_clear_date'];

                    $hold_days = getNetWorkDays($frm_details['check_insuff_raise'], $frm_details['insuff_clear_date']);

                    $date_tat = $this->addressver_model->get_date_for_update(array('id' => $frm_details['clear_update_id']));

                    $due_date = $date_tat[0]['due_date'];

                    $today = date("Y-m-d");

                    $get_holiday1 = $this->get_holiday();

                    $get_holiday = array_map('current', $get_holiday1);

                    $closed_date = getWorkingDays_increament($due_date, $get_holiday, $hold_days);

                    $x = 1;
                    $counter_1 = 0;
                    while ($x <= 10) {

                        $yesterday = date('Y-m-d', strtotime('-' . $x . ' day', strtotime($closed_date)));

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

                    if ($closed_date < $today) {
                        $new_tat = "OUT TAT";
                    } elseif ($closed_date == $today) {
                        $new_tat = "TDY TAT";
                    } elseif (in_array(date("Y-m-d"), $date_array) == true) {
                        $new_tat = "AP TAT";
                    } else {
                        $new_tat = "IN TAT";
                    }

                    $result_due_date = $this->addressver_model->update_due_date(array('due_date' => $closed_date, 'tat_status' => $new_tat), array('id' => $frm_details['clear_update_id']));

                    $fields = array('insuff_clear_date' => convert_display_to_db_date($insuff_date), 'insuff_remarks' => $frm_details['insuff_remarks'] . " Closed By Client " . $this->client_info['email_id'], 'insuff_cleared_timestamp' => date(DB_DATE_FORMAT), 'status' => 2, 'auto_stamp' => 2, 'hold_days' => $hold_days);

                    $result = $this->addressver_model->save_update_insuff($fields, array('id' => $frm_details['insuff_clear_id']));

                    $error_msgs = $file_array = array();

                    $file_upload_path = SITE_BASE_PATH . ADDRESS . $frm_details['clear_clientid'];

                    if (!folder_exist($file_upload_path)) {
                        mkdir($file_upload_path, 0777);
                    }

                    if (!empty($_FILES['clear_attchments']['name'][0])) {
                        $files_count = count($_FILES['clear_attchments']['name']);

                        for ($i = 0; $i < $files_count; $i++) {
                            $file_name = $_FILES['clear_attchments']['name'][$i];

                            $file_info = pathinfo($file_name);

                            $new_file_name = preg_replace('/[[:space:]]+/', '_', $file_info['filename']);

                            $new_file_name = $new_file_name . '_' . DATE(UPLOAD_FILE_DATE_FORMAT_FILE_NAME);

                            $new_file_name = str_replace('.', '_', $new_file_name);

                            $new_file_name = preg_replace('/[^A-Za-z0-9\-]/', '_', $new_file_name);

                            $file_extension = $file_info['extension'];

                            $new_file_name = $new_file_name . '.' . $file_extension;

                            $_FILES['attchment']['name'] = $new_file_name;

                            $_FILES['attchment']['tmp_name'] = $_FILES['clear_attchments']['tmp_name'][$i];

                            $_FILES['attchment']['error'] = $_FILES['clear_attchments']['error'][$i];

                            $_FILES['attchment']['size'] = $_FILES['clear_attchments']['size'][$i];

                            $config['upload_path'] = $file_upload_path;

                            $config['file_name'] = $new_file_name;

                            $config['allowed_types'] = 'jpeg|jpg|png|pdf';

                            $config['file_ext_tolower'] = true;

                            $config['remove_spaces'] = true;

                            $config['max_size'] = BULK_UPLOAD_MAX_SIZE_MB * 1000;

                            $this->load->library('upload', $config);

                            $this->upload->initialize($config);

                            if ($this->upload->do_upload('attchment')) {
                                array_push($file_array, array(
                                    'file_name' => $new_file_name,
                                    'real_filename' => $file_name,
                                    'addrver_id' => $frm_details['clear_update_id'],
                                    'type' => 2)
                                );
                            } else {
                                array_push($error_msgs, $this->upload->display_errors('', ''));

                                $json_array['status'] = ERROR_CODE;

                                $json_array['e_message'] = implode('<br>', $error_msgs);

                            }
                        }

                        if (!empty($file_array)) {
                            $this->addressver_model->uploaded_files($file_array);
                        }
                    }

                }

                if ($controller == "employment") {
                    $this->load->model('employment_model');

                    $insuff_date = $frm_details['insuff_clear_date'];
                    $hold_days = getNetWorkDays($frm_details['check_insuff_raise'], $frm_details['insuff_clear_date']);

                    $date_tat = $this->employment_model->get_date_for_update(array('id' => $frm_details['clear_update_id']));

                    $due_date = $date_tat[0]['due_date'];

                    $today = date("Y-m-d");

                    //    $due_date_increament = date('Y-m-d', strtotime($due_date .' +'.$hold_days. 'day'));

                    // $tat_count1  = $tat_count - $hold_days;

                    $get_holiday1 = $this->get_holiday();

                    $get_holiday = array_map('current', $get_holiday1);

                    $closed_date = getWorkingDays_increament($due_date, $get_holiday, $hold_days);

                    $x = 1;
                    $counter_1 = 0;
                    while ($x <= 10) {

                        $yesterday = date('Y-m-d', strtotime('-' . $x . ' day', strtotime($closed_date)));

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

                    if ($closed_date < $today) {
                        $new_tat = "OUT TAT";
                    } elseif ($closed_date == $today) {
                        $new_tat = "TDY TAT";
                    } elseif (in_array(date("Y-m-d"), $date_array) == true) {
                        $new_tat = "AP TAT";
                    } else {
                        $new_tat = "IN TAT";
                    }

                    $result_due_date = $this->employment_model->update_due_date(array('due_date' => $closed_date, 'tat_status' => $new_tat), array('id' => $frm_details['clear_update_id']));

                    $fields = array('insuff_clear_date' => convert_display_to_db_date($insuff_date), 'insuff_remarks' => $frm_details['insuff_remarks'] . " Closed By Client " . $this->client_info['email_id'], 'insuff_cleared_timestamp' => date(DB_DATE_FORMAT), 'status' => 2, 'auto_stamp' => 2, 'hold_days' => $hold_days);

                    $result = $this->employment_model->save_update_insuff($fields, array('id' => $frm_details['insuff_clear_id']));

                    $error_msgs = $file_array = array();

                    $file_upload_path = SITE_BASE_PATH . EMPLOYMENT . $frm_details['clear_clientid'];

                    if (!folder_exist($file_upload_path)) {
                        mkdir($file_upload_path, 0777);
                    }

                    if (!empty($_FILES['clear_attchments']['name'][0])) {
                        $files_count = count($_FILES['clear_attchments']['name']);

                        for ($i = 0; $i < $files_count; $i++) {
                            $file_name = $_FILES['clear_attchments']['name'][$i];

                            $file_info = pathinfo($file_name);

                            $new_file_name = preg_replace('/[[:space:]]+/', '_', $file_info['filename']);

                            $new_file_name = $new_file_name . '_' . DATE(UPLOAD_FILE_DATE_FORMAT_FILE_NAME);

                            $new_file_name = str_replace('.', '_', $new_file_name);

                            $new_file_name = preg_replace('/[^A-Za-z0-9\-]/', '_', $new_file_name);

                            $file_extension = $file_info['extension'];

                            $new_file_name = $new_file_name . '.' . $file_extension;

                            $_FILES['attchment']['name'] = $new_file_name;

                            $_FILES['attchment']['tmp_name'] = $_FILES['clear_attchments']['tmp_name'][$i];

                            $_FILES['attchment']['error'] = $_FILES['clear_attchments']['error'][$i];

                            $_FILES['attchment']['size'] = $_FILES['clear_attchments']['size'][$i];

                            $config['upload_path'] = $file_upload_path;

                            $config['file_name'] = $new_file_name;

                            $config['allowed_types'] = 'jpeg|jpg|png|pdf';

                            $config['file_ext_tolower'] = true;

                            $config['remove_spaces'] = true;

                            $config['max_size'] = BULK_UPLOAD_MAX_SIZE_MB * 1000;

                            $this->load->library('upload', $config);

                            $this->upload->initialize($config);

                            if ($this->upload->do_upload('attchment')) {
                                array_push($file_array, array(
                                    'file_name' => $new_file_name,
                                    'real_filename' => $file_name,
                                    'empver_id' => $frm_details['clear_update_id'],
                                    'type' => 2)
                                );
                            } else {
                                array_push($error_msgs, $this->upload->display_errors('', ''));

                                $json_array['status'] = ERROR_CODE;

                                $json_array['e_message'] = implode('<br>', $error_msgs);

                            }
                        }

                        if (!empty($file_array)) {
                            $this->employment_model->uploaded_files($file_array);
                        }
                    }

                }

                if ($controller == "education") {

                    $this->load->model('education_model');

                    $insuff_date = $frm_details['insuff_clear_date'];

                    $hold_days = getNetWorkDays($frm_details['check_insuff_raise'], $frm_details['insuff_clear_date']);

                    $date_tat = $this->education_model->get_date_for_update(array('id' => $frm_details['clear_update_id']));

                    $due_date = $date_tat[0]['due_date'];

                    $today = date("Y-m-d");

                    //    $due_date_increament = date('Y-m-d', strtotime($due_date .' +'.$hold_days. 'day'));

                    // $tat_count1  = $tat_count - $hold_days;

                    $get_holiday1 = $this->get_holiday();

                    $get_holiday = array_map('current', $get_holiday1);

                    $closed_date = getWorkingDays_increament($due_date, $get_holiday, $hold_days);

                    $x = 1;
                    $counter_1 = 0;
                    while ($x <= 10) {

                        $yesterday = date('Y-m-d', strtotime('-' . $x . ' day', strtotime($closed_date)));

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

                    if ($closed_date < $today) {
                        $new_tat = "OUT TAT";
                    } elseif ($closed_date == $today) {
                        $new_tat = "TDY TAT";
                    } elseif (in_array(date("Y-m-d"), $date_array) == true) {
                        $new_tat = "AP TAT";
                    } else {
                        $new_tat = "IN TAT";
                    }

                    $result_due_date = $this->education_model->update_due_date(array('due_date' => $closed_date, 'tat_status' => $new_tat), array('id' => $frm_details['clear_update_id']));

                    $fields = array('insuff_clear_date' => convert_display_to_db_date($insuff_date), 'insuff_remarks' => $frm_details['insuff_remarks'] . " Closed By Client " . $this->client_info['email_id'], 'insuff_cleared_timestamp' => date(DB_DATE_FORMAT), 'status' => 2, 'auto_stamp' => 2, 'hold_days' => $hold_days);

                    $result = $this->education_model->save_update_insuff($fields, array('id' => $frm_details['insuff_clear_id']));

                    $error_msgs = $file_array = array();

                    $file_upload_path = SITE_BASE_PATH . EDUCATION . $frm_details['clear_clientid'];

                    if (!folder_exist($file_upload_path)) {
                        mkdir($file_upload_path, 0777);
                    }

                    if (!empty($_FILES['clear_attchments']['name'][0])) {
                        $files_count = count($_FILES['clear_attchments']['name']);

                        for ($i = 0; $i < $files_count; $i++) {
                            $file_name = $_FILES['clear_attchments']['name'][$i];

                            $file_info = pathinfo($file_name);

                            $new_file_name = preg_replace('/[[:space:]]+/', '_', $file_info['filename']);

                            $new_file_name = $new_file_name . '_' . DATE(UPLOAD_FILE_DATE_FORMAT_FILE_NAME);

                            $new_file_name = str_replace('.', '_', $new_file_name);

                            $new_file_name = preg_replace('/[^A-Za-z0-9\-]/', '_', $new_file_name);

                            $file_extension = $file_info['extension'];

                            $new_file_name = $new_file_name . '.' . $file_extension;

                            $_FILES['attchment']['name'] = $new_file_name;

                            $_FILES['attchment']['tmp_name'] = $_FILES['clear_attchments']['tmp_name'][$i];

                            $_FILES['attchment']['error'] = $_FILES['clear_attchments']['error'][$i];

                            $_FILES['attchment']['size'] = $_FILES['clear_attchments']['size'][$i];

                            $config['upload_path'] = $file_upload_path;

                            $config['file_name'] = $new_file_name;

                            $config['allowed_types'] = 'jpeg|jpg|png|pdf';

                            $config['file_ext_tolower'] = true;

                            $config['remove_spaces'] = true;

                            $config['max_size'] = BULK_UPLOAD_MAX_SIZE_MB * 1000;

                            $this->load->library('upload', $config);

                            $this->upload->initialize($config);

                            if ($this->upload->do_upload('attchment')) {
                                array_push($file_array, array(
                                    'file_name' => $new_file_name,
                                    'real_filename' => $file_name,
                                    'addrver_id' => $frm_details['clear_update_id'],
                                    'type' => 2)
                                );
                            } else {
                                array_push($error_msgs, $this->upload->display_errors('', ''));

                                $json_array['status'] = ERROR_CODE;

                                $json_array['e_message'] = implode('<br>', $error_msgs);

                            }
                        }

                        if (!empty($file_array)) {
                            $this->education_model->uploaded_files($file_array);
                        }
                    }

                }

                if ($controller == "reference_verificatiion") {
                    $this->load->model('reference_verificatiion_model');

                    $insuff_date = $frm_details['insuff_clear_date'];

                    $hold_days = getNetWorkDays($frm_details['check_insuff_raise'], $frm_details['insuff_clear_date']);

                    $date_tat = $this->reference_verificatiion_model->get_date_for_update(array('id' => $frm_details['clear_update_id']));

                    $due_date = $date_tat[0]['due_date'];

                    $today = date("Y-m-d");

                    //    $due_date_increament = date('Y-m-d', strtotime($due_date .' +'.$hold_days. 'day'));

                    // $tat_count1  = $tat_count - $hold_days;

                    $get_holiday1 = $this->get_holiday();

                    $get_holiday = array_map('current', $get_holiday1);

                    $closed_date = getWorkingDays_increament($due_date, $get_holiday, $hold_days);

                    $x = 1;
                    $counter_1 = 0;
                    while ($x <= 10) {

                        $yesterday = date('Y-m-d', strtotime('-' . $x . ' day', strtotime($closed_date)));

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

                    if ($closed_date < $today) {
                        $new_tat = "OUT TAT";
                    } elseif ($closed_date == $today) {
                        $new_tat = "TDY TAT";
                    } elseif (in_array(date("Y-m-d"), $date_array) == true) {
                        $new_tat = "AP TAT";
                    } else {
                        $new_tat = "IN TAT";
                    }

                    $result_due_date = $this->reference_verificatiion_model->update_due_date(array('due_date' => $closed_date, 'tat_status' => $new_tat), array('id' => $frm_details['clear_update_id']));

                    $fields = array('insuff_clear_date' => convert_display_to_db_date($insuff_date), 'insuff_remarks' => $frm_details['insuff_remarks'] . " Closed By Client " . $this->client_info['email_id'], 'insuff_cleared_timestamp' => date(DB_DATE_FORMAT), 'status' => 2, 'auto_stamp' => 2, 'hold_days' => $hold_days);

                    $result = $this->reference_verificatiion_model->save_update_insuff($fields, array('id' => $frm_details['insuff_clear_id']));

                    $error_msgs = $file_array = array();

                    $file_upload_path = SITE_BASE_PATH . REFERENCES . $frm_details['clear_clientid'];

                    if (!folder_exist($file_upload_path)) {
                        mkdir($file_upload_path, 0777);
                    }

                    if (!empty($_FILES['clear_attchments']['name'][0])) {
                        $files_count = count($_FILES['clear_attchments']['name']);

                        for ($i = 0; $i < $files_count; $i++) {
                            $file_name = $_FILES['clear_attchments']['name'][$i];

                            $file_info = pathinfo($file_name);

                            $new_file_name = preg_replace('/[[:space:]]+/', '_', $file_info['filename']);

                            $new_file_name = $new_file_name . '_' . DATE(UPLOAD_FILE_DATE_FORMAT_FILE_NAME);

                            $new_file_name = str_replace('.', '_', $new_file_name);

                            $new_file_name = preg_replace('/[^A-Za-z0-9\-]/', '_', $new_file_name);

                            $file_extension = $file_info['extension'];

                            $new_file_name = $new_file_name . '.' . $file_extension;

                            $_FILES['attchment']['name'] = $new_file_name;

                            $_FILES['attchment']['tmp_name'] = $_FILES['clear_attchments']['tmp_name'][$i];

                            $_FILES['attchment']['error'] = $_FILES['clear_attchments']['error'][$i];

                            $_FILES['attchment']['size'] = $_FILES['clear_attchments']['size'][$i];

                            $config['upload_path'] = $file_upload_path;

                            $config['file_name'] = $new_file_name;

                            $config['allowed_types'] = 'jpeg|jpg|png|pdf';

                            $config['file_ext_tolower'] = true;

                            $config['remove_spaces'] = true;

                            $config['max_size'] = BULK_UPLOAD_MAX_SIZE_MB * 1000;

                            $this->load->library('upload', $config);

                            $this->upload->initialize($config);

                            if ($this->upload->do_upload('attchment')) {
                                array_push($file_array, array(
                                    'file_name' => $new_file_name,
                                    'real_filename' => $file_name,
                                    'reference_id' => $frm_details['clear_update_id'],
                                    'type' => 2)
                                );
                            } else {
                                array_push($error_msgs, $this->upload->display_errors('', ''));

                                $json_array['status'] = ERROR_CODE;

                                $json_array['e_message'] = implode('<br>', $error_msgs);

                            }
                        }

                        if (!empty($file_array)) {
                            $this->reference_verificatiion_model->uploaded_files($file_array);
                        }
                    }
                }

                if ($controller == "court_verificatiion") {
                    $this->load->model('court_verificatiion_model');

                    $insuff_date = $frm_details['insuff_clear_date'];

                    $hold_days = getNetWorkDays($frm_details['check_insuff_raise'], $frm_details['insuff_clear_date']);

                    $date_tat = $this->court_verificatiion_model->get_date_for_update(array('id' => $frm_details['clear_update_id']));

                    $due_date = $date_tat[0]['due_date'];

                    $today = date("Y-m-d");

                    $get_holiday1 = $this->get_holiday();

                    $get_holiday = array_map('current', $get_holiday1);

                    $closed_date = getWorkingDays_increament($due_date, $get_holiday, $hold_days);

                    $x = 1;
                    $counter_1 = 0;
                    while ($x <= 10) {

                        $yesterday = date('Y-m-d', strtotime('-' . $x . ' day', strtotime($closed_date)));

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

                    if ($closed_date < $today) {
                        $new_tat = "OUT TAT";
                    } elseif ($closed_date == $today) {
                        $new_tat = "TDY TAT";
                    } elseif (in_array(date("Y-m-d"), $date_array) == true) {
                        $new_tat = "AP TAT";
                    } else {
                        $new_tat = "IN TAT";
                    }

                    $result_due_date = $this->court_verificatiion_model->update_due_date(array('due_date' => $closed_date, 'tat_status' => $new_tat), array('id' => $frm_details['clear_update_id']));

                    $fields = array('insuff_clear_date' => convert_display_to_db_date($insuff_date), 'insuff_remarks' => $frm_details['insuff_remarks'] . " Closed By Client " . $this->client_info['email_id'], 'insuff_cleared_timestamp' => date(DB_DATE_FORMAT), 'status' => 2, 'auto_stamp' => 2, 'hold_days' => $hold_days);

                    $result = $this->court_verificatiion_model->save_update_insuff($fields, array('id' => $frm_details['insuff_clear_id']));

                    $error_msgs = $file_array = array();

                    $file_upload_path = SITE_BASE_PATH . COURT_VERIFICATION . $frm_details['clear_clientid'];

                    if (!folder_exist($file_upload_path)) {
                        mkdir($file_upload_path, 0777);
                    }

                    if (!empty($_FILES['clear_attchments']['name'][0])) {
                        $files_count = count($_FILES['clear_attchments']['name']);

                        for ($i = 0; $i < $files_count; $i++) {
                            $file_name = $_FILES['clear_attchments']['name'][$i];

                            $file_info = pathinfo($file_name);

                            $new_file_name = preg_replace('/[[:space:]]+/', '_', $file_info['filename']);

                            $new_file_name = $new_file_name . '_' . DATE(UPLOAD_FILE_DATE_FORMAT_FILE_NAME);

                            $new_file_name = str_replace('.', '_', $new_file_name);

                            $new_file_name = preg_replace('/[^A-Za-z0-9\-]/', '_', $new_file_name);

                            $file_extension = $file_info['extension'];

                            $new_file_name = $new_file_name . '.' . $file_extension;

                            $_FILES['attchment']['name'] = $new_file_name;

                            $_FILES['attchment']['tmp_name'] = $_FILES['clear_attchments']['tmp_name'][$i];

                            $_FILES['attchment']['error'] = $_FILES['clear_attchments']['error'][$i];

                            $_FILES['attchment']['size'] = $_FILES['clear_attchments']['size'][$i];

                            $config['upload_path'] = $file_upload_path;

                            $config['file_name'] = $new_file_name;

                            $config['allowed_types'] = 'jpeg|jpg|png|pdf';

                            $config['file_ext_tolower'] = true;

                            $config['remove_spaces'] = true;

                            $config['max_size'] = BULK_UPLOAD_MAX_SIZE_MB * 1000;

                            $this->load->library('upload', $config);

                            $this->upload->initialize($config);

                            if ($this->upload->do_upload('attchment')) {
                                array_push($file_array, array(
                                    'file_name' => $new_file_name,
                                    'real_filename' => $file_name,
                                    'courtver_id' => $frm_details['clear_update_id'],
                                    'type' => 2)
                                );
                            } else {
                                array_push($error_msgs, $this->upload->display_errors('', ''));

                                $json_array['status'] = ERROR_CODE;

                                $json_array['e_message'] = implode('<br>', $error_msgs);

                            }
                        }

                        if (!empty($file_array)) {
                            $this->court_verificatiion_model->uploaded_files($file_array);
                        }
                    }

                }

                if ($controller == "global_database") {
                    $this->load->model('global_database_model');

                    $insuff_date = $frm_details['insuff_clear_date'];

                    $hold_days = getNetWorkDays($frm_details['check_insuff_raise'], $frm_details['insuff_clear_date']);

                    $fields = array('insuff_clear_date' => convert_display_to_db_date($insuff_date), 'insuff_remarks' => $frm_details['insuff_remarks'] . " Closed By Client " . $this->client_info['email_id'], 'insuff_cleared_timestamp' => date(DB_DATE_FORMAT), 'status' => 2, 'auto_stamp' => 2, 'hold_days' => $hold_days);

                    $date_tat = $this->global_database_model->get_date_for_update(array('id' => $frm_details['clear_update_id']));

                    $due_date = $date_tat[0]['due_date'];

                    $today = date("Y-m-d");

                    $get_holiday1 = $this->get_holiday();

                    $get_holiday = array_map('current', $get_holiday1);

                    $closed_date = getWorkingDays_increament($due_date, $get_holiday, $hold_days);

                    $x = 1;
                    $counter_1 = 0;
                    while ($x <= 10) {

                        $yesterday = date('Y-m-d', strtotime('-' . $x . ' day', strtotime($closed_date)));

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

                    if ($closed_date < $today) {
                        $new_tat = "OUT TAT";
                    } elseif ($closed_date == $today) {
                        $new_tat = "TDY TAT";
                    } elseif (in_array(date("Y-m-d"), $date_array) == true) {
                        $new_tat = "AP TAT";
                    } else {
                        $new_tat = "IN TAT";
                    }

                    $result_due_date = $this->global_database_model->update_due_date(array('due_date' => $closed_date, 'tat_status' => $new_tat), array('id' => $frm_details['clear_update_id']));

                    $fields = array('insuff_clear_date' => convert_display_to_db_date($insuff_date), 'insuff_remarks' => $frm_details['insuff_remarks'] . " Closed By Client " . $this->client_info['email_id'], 'insuff_cleared_timestamp' => date(DB_DATE_FORMAT), 'status' => 2, 'auto_stamp' => 2, 'hold_days' => $hold_days);

                    $result = $this->global_database_model->save_update_insuff($fields, array('id' => $frm_details['insuff_clear_id']));

                    $error_msgs = $file_array = array();

                    $file_upload_path = SITE_BASE_PATH . GLOBAL_DB . $frm_details['clear_clientid'];

                    if (!folder_exist($file_upload_path)) {
                        mkdir($file_upload_path, 0777);
                    }

                    if (!empty($_FILES['clear_attchments']['name'][0])) {
                        $files_count = count($_FILES['clear_attchments']['name']);

                        for ($i = 0; $i < $files_count; $i++) {
                            $file_name = $_FILES['clear_attchments']['name'][$i];

                            $file_info = pathinfo($file_name);

                            $new_file_name = preg_replace('/[[:space:]]+/', '_', $file_info['filename']);

                            $new_file_name = $new_file_name . '_' . DATE(UPLOAD_FILE_DATE_FORMAT_FILE_NAME);

                            $new_file_name = str_replace('.', '_', $new_file_name);

                            $new_file_name = preg_replace('/[^A-Za-z0-9\-]/', '_', $new_file_name);

                            $file_extension = $file_info['extension'];

                            $new_file_name = $new_file_name . '.' . $file_extension;

                            $_FILES['attchment']['name'] = $new_file_name;

                            $_FILES['attchment']['tmp_name'] = $_FILES['clear_attchments']['tmp_name'][$i];

                            $_FILES['attchment']['error'] = $_FILES['clear_attchments']['error'][$i];

                            $_FILES['attchment']['size'] = $_FILES['clear_attchments']['size'][$i];

                            $config['upload_path'] = $file_upload_path;

                            $config['file_name'] = $new_file_name;

                            $config['allowed_types'] = 'jpeg|jpg|png|pdf';

                            $config['file_ext_tolower'] = true;

                            $config['remove_spaces'] = true;

                            $config['max_size'] = BULK_UPLOAD_MAX_SIZE_MB * 1000;

                            $this->load->library('upload', $config);

                            $this->upload->initialize($config);

                            if ($this->upload->do_upload('attchment')) {
                                array_push($file_array, array(
                                    'file_name' => $new_file_name,
                                    'real_filename' => $file_name,
                                    'glodbver_id' => $frm_details['clear_update_id'],
                                    'type' => 2)
                                );
                            } else {
                                array_push($error_msgs, $this->upload->display_errors('', ''));

                                $json_array['status'] = ERROR_CODE;

                                $json_array['e_message'] = implode('<br>', $error_msgs);

                            }
                        }

                        if (!empty($file_array)) {
                            $this->global_database_model->uploaded_files($file_array);
                        }
                    }
                }

                if ($controller == "drugs_narcotics") {
                    $this->load->model('drug_verificatiion_model');

                    $insuff_date = $frm_details['insuff_clear_date'];

                    $hold_days = getNetWorkDays($frm_details['check_insuff_raise'], $frm_details['insuff_clear_date']);

                    $date_tat = $this->drug_verificatiion_model->get_date_for_update(array('id' => $frm_details['clear_update_id']));

                    $due_date = $date_tat[0]['due_date'];

                    $today = date("Y-m-d");

                    $get_holiday1 = $this->get_holiday();

                    $get_holiday = array_map('current', $get_holiday1);

                    $closed_date = getWorkingDays_increament($due_date, $get_holiday, $hold_days);

                    $x = 1;
                    $counter_1 = 0;
                    while ($x <= 10) {

                        $yesterday = date('Y-m-d', strtotime('-' . $x . ' day', strtotime($closed_date)));

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

                    if ($closed_date < $today) {
                        $new_tat = "OUT TAT";
                    } elseif ($closed_date == $today) {
                        $new_tat = "TDY TAT";
                    } elseif (in_array(date("Y-m-d"), $date_array) == true) {
                        $new_tat = "AP TAT";
                    } else {
                        $new_tat = "IN TAT";
                    }

                    $result_due_date = $this->drug_verificatiion_model->update_due_date(array('due_date' => $closed_date, 'tat_status' => $new_tat), array('id' => $frm_details['clear_update_id']));

                    $fields = array('insuff_clear_date' => convert_display_to_db_date($insuff_date), 'insuff_remarks' => $frm_details['insuff_remarks'] . " Closed By Client " . $this->client_info['email_id'], 'insuff_cleared_timestamp' => date(DB_DATE_FORMAT), 'status' => 2, 'auto_stamp' => 2, 'hold_days' => $hold_days);

                    $result = $this->drug_verificatiion_model->save_update_insuff($fields, array('id' => $frm_details['insuff_clear_id']));

                    $error_msgs = $file_array = array();

                    $file_upload_path = SITE_BASE_PATH . DRUGS . $frm_details['clear_clientid'];

                    if (!folder_exist($file_upload_path)) {
                        mkdir($file_upload_path, 0777);
                    }

                    if (!empty($_FILES['clear_attchments']['name'][0])) {
                        $files_count = count($_FILES['clear_attchments']['name']);

                        for ($i = 0; $i < $files_count; $i++) {
                            $file_name = $_FILES['clear_attchments']['name'][$i];

                            $file_info = pathinfo($file_name);

                            $new_file_name = preg_replace('/[[:space:]]+/', '_', $file_info['filename']);

                            $new_file_name = $new_file_name . '_' . DATE(UPLOAD_FILE_DATE_FORMAT_FILE_NAME);

                            $new_file_name = str_replace('.', '_', $new_file_name);

                            $new_file_name = preg_replace('/[^A-Za-z0-9\-]/', '_', $new_file_name);

                            $file_extension = $file_info['extension'];

                            $new_file_name = $new_file_name . '.' . $file_extension;

                            $_FILES['attchment']['name'] = $new_file_name;

                            $_FILES['attchment']['tmp_name'] = $_FILES['clear_attchments']['tmp_name'][$i];

                            $_FILES['attchment']['error'] = $_FILES['clear_attchments']['error'][$i];

                            $_FILES['attchment']['size'] = $_FILES['clear_attchments']['size'][$i];

                            $config['upload_path'] = $file_upload_path;

                            $config['file_name'] = $new_file_name;

                            $config['allowed_types'] = 'jpeg|jpg|png|pdf';

                            $config['file_ext_tolower'] = true;

                            $config['remove_spaces'] = true;

                            $config['max_size'] = BULK_UPLOAD_MAX_SIZE_MB * 1000;

                            $this->load->library('upload', $config);

                            $this->upload->initialize($config);

                            if ($this->upload->do_upload('attchment')) {
                                array_push($file_array, array(
                                    'file_name' => $new_file_name,
                                    'real_filename' => $file_name,
                                    'drug_narcotis_id' => $frm_details['clear_update_id'],
                                    'type' => 2)
                                );
                            } else {
                                array_push($error_msgs, $this->upload->display_errors('', ''));

                                $json_array['status'] = ERROR_CODE;

                                $json_array['e_message'] = implode('<br>', $error_msgs);

                            }
                        }

                        if (!empty($file_array)) {
                            $this->drug_verificatiion_model->uploaded_files($file_array);
                        }
                    }

                }

                if ($controller == "pcc") {
                    $this->load->model('pcc_verificatiion_model');

                    $insuff_date = $frm_details['insuff_clear_date'];

                    $hold_days = getNetWorkDays($frm_details['check_insuff_raise'], $frm_details['insuff_clear_date']);

                    $date_tat = $this->pcc_verificatiion_model->get_date_for_update(array('id' => $frm_details['clear_update_id']));

                    $due_date = $date_tat[0]['due_date'];

                    $today = date("Y-m-d");

                    //    $due_date_increament = date('Y-m-d', strtotime($due_date .' +'.$hold_days. 'day'));

                    // $tat_count1  = $tat_count - $hold_days;

                    $get_holiday1 = $this->get_holiday();

                    $get_holiday = array_map('current', $get_holiday1);

                    $closed_date = getWorkingDays_increament($due_date, $get_holiday, $hold_days);

                    $x = 1;
                    $counter_1 = 0;
                    while ($x <= 10) {

                        $yesterday = date('Y-m-d', strtotime('-' . $x . ' day', strtotime($closed_date)));

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

                    if ($closed_date < $today) {
                        $new_tat = "OUT TAT";
                    } elseif ($closed_date == $today) {
                        $new_tat = "TDY TAT";
                    } elseif (in_array(date("Y-m-d"), $date_array) == true) {
                        $new_tat = "AP TAT";
                    } else {
                        $new_tat = "IN TAT";
                    }

                    $result_due_date = $this->pcc_verificatiion_model->update_due_date(array('due_date' => $closed_date, 'tat_status' => $new_tat), array('id' => $frm_details['clear_update_id']));

                    $fields = array('insuff_clear_date' => convert_display_to_db_date($insuff_date), 'insuff_remarks' => $frm_details['insuff_remarks'] . " Closed By Client " . $this->client_info['email_id'], 'insuff_cleared_timestamp' => date(DB_DATE_FORMAT), 'status' => 2, 'auto_stamp' => 2, 'hold_days' => $hold_days);

                    $result = $this->pcc_verificatiion_model->save_update_insuff($fields, array('id' => $frm_details['insuff_clear_id']));

                    $error_msgs = $file_array = array();

                    $file_upload_path = SITE_BASE_PATH . PCC . $frm_details['clear_clientid'];

                    if (!folder_exist($file_upload_path)) {
                        mkdir($file_upload_path, 0777);
                    }

                    if (!empty($_FILES['clear_attchments']['name'][0])) {
                        $files_count = count($_FILES['clear_attchments']['name']);

                        for ($i = 0; $i < $files_count; $i++) {
                            $file_name = $_FILES['clear_attchments']['name'][$i];

                            $file_info = pathinfo($file_name);

                            $new_file_name = preg_replace('/[[:space:]]+/', '_', $file_info['filename']);

                            $new_file_name = $new_file_name . '_' . DATE(UPLOAD_FILE_DATE_FORMAT_FILE_NAME);

                            $new_file_name = str_replace('.', '_', $new_file_name);

                            $new_file_name = preg_replace('/[^A-Za-z0-9\-]/', '_', $new_file_name);

                            $file_extension = $file_info['extension'];

                            $new_file_name = $new_file_name . '.' . $file_extension;

                            $_FILES['attchment']['name'] = $new_file_name;

                            $_FILES['attchment']['tmp_name'] = $_FILES['clear_attchments']['tmp_name'][$i];

                            $_FILES['attchment']['error'] = $_FILES['clear_attchments']['error'][$i];

                            $_FILES['attchment']['size'] = $_FILES['clear_attchments']['size'][$i];

                            $config['upload_path'] = $file_upload_path;

                            $config['file_name'] = $new_file_name;

                            $config['allowed_types'] = 'jpeg|jpg|png|pdf';

                            $config['file_ext_tolower'] = true;

                            $config['remove_spaces'] = true;

                            $config['max_size'] = BULK_UPLOAD_MAX_SIZE_MB * 1000;

                            $this->load->library('upload', $config);

                            $this->upload->initialize($config);

                            if ($this->upload->do_upload('attchment')) {
                                array_push($file_array, array(
                                    'file_name' => $new_file_name,
                                    'real_filename' => $file_name,
                                    'addrver_id' => $frm_details['pcc_id'],
                                    'type' => 2)
                                );
                            } else {
                                array_push($error_msgs, $this->upload->display_errors('', ''));

                                $json_array['status'] = ERROR_CODE;

                                $json_array['e_message'] = implode('<br>', $error_msgs);

                            }
                        }

                        if (!empty($file_array)) {
                            $this->pcc_verificatiion_model->uploaded_files($file_array);
                        }
                    }
                }

                if ($controller == "identity") {
                    $this->load->model('identity_model');

                    $insuff_date = $frm_details['insuff_clear_date'];

                    $hold_days = getNetWorkDays($frm_details['check_insuff_raise'], $frm_details['insuff_clear_date']);

                    $date_tat = $this->identity_model->get_date_for_update(array('id' => $frm_details['clear_update_id']));

                    $due_date = $date_tat[0]['due_date'];

                    $today = date("Y-m-d");

                    $get_holiday1 = $this->get_holiday();

                    $get_holiday = array_map('current', $get_holiday1);

                    $closed_date = getWorkingDays_increament($due_date, $get_holiday, $hold_days);

                    $x = 1;
                    $counter_1 = 0;
                    while ($x <= 10) {

                        $yesterday = date('Y-m-d', strtotime('-' . $x . ' day', strtotime($closed_date)));

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

                    if ($closed_date < $today) {
                        $new_tat = "OUT TAT";
                    } elseif ($closed_date == $today) {
                        $new_tat = "TDY TAT";
                    } elseif (in_array(date("Y-m-d"), $date_array) == true) {
                        $new_tat = "AP TAT";
                    } else {
                        $new_tat = "IN TAT";
                    }

                    $result_due_date = $this->identity_model->update_due_date(array('due_date' => $closed_date, 'tat_status' => $new_tat), array('id' => $frm_details['clear_update_id']));

                    $fields = array('insuff_clear_date' => convert_display_to_db_date($insuff_date), 'insuff_remarks' => $frm_details['insuff_remarks'] . " Closed By Client " . $this->client_info['email_id'], 'insuff_cleared_timestamp' => date(DB_DATE_FORMAT), 'status' => 2, 'auto_stamp' => 2, 'hold_days' => $hold_days);

                    $result = $this->identity_model->save_update_insuff($fields, array('id' => $frm_details['insuff_clear_id']));

                    $error_msgs = $file_array = array();

                    $file_upload_path = SITE_BASE_PATH . IDENTITY . $frm_details['clear_clientid'];

                    if (!folder_exist($file_upload_path)) {
                        mkdir($file_upload_path, 0777);
                    }

                    if (!empty($_FILES['clear_attchments']['name'][0])) {
                        $files_count = count($_FILES['clear_attchments']['name']);

                        for ($i = 0; $i < $files_count; $i++) {
                            $file_name = $_FILES['clear_attchments']['name'][$i];

                            $file_info = pathinfo($file_name);

                            $new_file_name = preg_replace('/[[:space:]]+/', '_', $file_info['filename']);

                            $new_file_name = $new_file_name . '_' . DATE(UPLOAD_FILE_DATE_FORMAT_FILE_NAME);

                            $new_file_name = str_replace('.', '_', $new_file_name);

                            $new_file_name = preg_replace('/[^A-Za-z0-9\-]/', '_', $new_file_name);

                            $file_extension = $file_info['extension'];

                            $new_file_name = $new_file_name . '.' . $file_extension;

                            $_FILES['attchment']['name'] = $new_file_name;

                            $_FILES['attchment']['tmp_name'] = $_FILES['clear_attchments']['tmp_name'][$i];

                            $_FILES['attchment']['error'] = $_FILES['clear_attchments']['error'][$i];

                            $_FILES['attchment']['size'] = $_FILES['clear_attchments']['size'][$i];

                            $config['upload_path'] = $file_upload_path;

                            $config['file_name'] = $new_file_name;

                            $config['allowed_types'] = 'jpeg|jpg|png|pdf';

                            $config['file_ext_tolower'] = true;

                            $config['remove_spaces'] = true;

                            $config['max_size'] = BULK_UPLOAD_MAX_SIZE_MB * 1000;

                            $this->load->library('upload', $config);

                            $this->upload->initialize($config);

                            if ($this->upload->do_upload('attchment')) {
                                array_push($file_array, array(
                                    'file_name' => $new_file_name,
                                    'real_filename' => $file_name,
                                    'addrver_id' => $frm_details['clear_update_id'],
                                    'type' => 2)
                                );
                            } else {
                                array_push($error_msgs, $this->upload->display_errors('', ''));

                                $json_array['status'] = ERROR_CODE;

                                $json_array['e_message'] = implode('<br>', $error_msgs);

                            }
                        }

                        if (!empty($file_array)) {
                            $this->identity_model->uploaded_files($file_array);
                        }
                    }
                }

                if ($controller == "Credit_report") {
                    $this->load->model('credit_report_model');

                    $insuff_date = $frm_details['insuff_clear_date'];

                    $hold_days = getNetWorkDays($frm_details['check_insuff_raise'], $frm_details['insuff_clear_date']);

                    $date_tat = $this->credit_report_model->get_date_for_update(array('id' => $frm_details['clear_update_id']));

                    $due_date = $date_tat[0]['due_date'];

                    $today = date("Y-m-d");

                    $get_holiday1 = $this->get_holiday();

                    $get_holiday = array_map('current', $get_holiday1);

                    $closed_date = getWorkingDays_increament($due_date, $get_holiday, $hold_days);

                    $x = 1;
                    $counter_1 = 0;
                    while ($x <= 10) {

                        $yesterday = date('Y-m-d', strtotime('-' . $x . ' day', strtotime($closed_date)));

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

                    if ($closed_date < $today) {
                        $new_tat = "OUT TAT";
                    } elseif ($closed_date == $today) {
                        $new_tat = "TDY TAT";
                    } elseif (in_array(date("Y-m-d"), $date_array) == true) {
                        $new_tat = "AP TAT";
                    } else {
                        $new_tat = "IN TAT";
                    }

                    $result_due_date = $this->credit_report_model->update_due_date(array('due_date' => $closed_date, 'tat_status' => $new_tat), array('id' => $frm_details['clear_update_id']));

                    $fields = array('insuff_clear_date' => convert_display_to_db_date($insuff_date), 'insuff_remarks' => $frm_details['insuff_remarks'] . " Closed By Client " . $this->client_info['email_id'], 'insuff_cleared_timestamp' => date(DB_DATE_FORMAT), 'status' => 2, 'auto_stamp' => 2, 'hold_days' => $hold_days);

                    $result = $this->credit_report_model->save_update_insuff($fields, array('id' => $frm_details['insuff_clear_id']));

                    $error_msgs = $file_array = array();

                    $file_upload_path = SITE_BASE_PATH . CREDIT_REPORT . $frm_details['clear_clientid'];

                    if (!folder_exist($file_upload_path)) {
                        mkdir($file_upload_path, 0777);
                    }

                    if (!empty($_FILES['clear_attchments']['name'][0])) {
                        $files_count = count($_FILES['clear_attchments']['name']);

                        for ($i = 0; $i < $files_count; $i++) {
                            $file_name = $_FILES['clear_attchments']['name'][$i];

                            $file_info = pathinfo($file_name);

                            $new_file_name = preg_replace('/[[:space:]]+/', '_', $file_info['filename']);

                            $new_file_name = $new_file_name . '_' . DATE(UPLOAD_FILE_DATE_FORMAT_FILE_NAME);

                            $new_file_name = str_replace('.', '_', $new_file_name);

                            $new_file_name = preg_replace('/[^A-Za-z0-9\-]/', '_', $new_file_name);

                            $file_extension = $file_info['extension'];

                            $new_file_name = $new_file_name . '.' . $file_extension;

                            $_FILES['attchment']['name'] = $new_file_name;

                            $_FILES['attchment']['tmp_name'] = $_FILES['clear_attchments']['tmp_name'][$i];

                            $_FILES['attchment']['error'] = $_FILES['clear_attchments']['error'][$i];

                            $_FILES['attchment']['size'] = $_FILES['clear_attchments']['size'][$i];

                            $config['upload_path'] = $file_upload_path;

                            $config['file_name'] = $new_file_name;

                            $config['allowed_types'] = 'jpeg|jpg|png|pdf';

                            $config['file_ext_tolower'] = true;

                            $config['remove_spaces'] = true;

                            $config['max_size'] = BULK_UPLOAD_MAX_SIZE_MB * 1000;

                            $this->load->library('upload', $config);

                            $this->upload->initialize($config);

                            if ($this->upload->do_upload('attchment')) {
                                array_push($file_array, array(
                                    'file_name' => $new_file_name,
                                    'real_filename' => $file_name,
                                    'addrver_id' => $frm_details['clear_update_id'],
                                    'type' => 2)
                                );
                            } else {
                                array_push($error_msgs, $this->upload->display_errors('', ''));

                                $json_array['status'] = ERROR_CODE;

                                $json_array['e_message'] = implode('<br>', $error_msgs);

                            }
                        }

                        if (!empty($file_array)) {
                            $this->credit_report->uploaded_files($file_array);
                        }
                    }
                }

                if ($result) {
                    auto_update_overall_status($frm_details['candidates_info_id']);

                    auto_update_tat_status($frm_details['candidates_info_id']);

                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'Record Successfully Inserted';
                } else {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Something went wrong,please try again';
                }
            } else {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = 'Insuff cleared date can not be less than the raised date.';
            }
        } else {
            $json_array['message'] = "Don't have permission to access this function";

            $json_array['status'] = ERROR_CODE;
        }
        echo_json($json_array);
    }

}
?>
