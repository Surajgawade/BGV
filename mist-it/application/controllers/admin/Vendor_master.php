<?php defined('BASEPATH') or exit('No direct script access allowed');
class Vendor_master extends MY_Controller
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

        $this->perm_array = array('page_id' => 33);
        $this->load->model(array('vendor_master_log_model'));
    }

    public function index()
    {
        $data['header_title'] = "Vendor Master";

        $data['lists'] = $this->vendor_master_log_model->get_list(array('view_vendor_master_log.status' => 1));

        $this->load->view('admin/header', $data);

        $this->load->view('admin/vendor_master_list');

        $this->load->view('admin/footer');
    }

    public function reject_case()
    {
        $json_array = array();
        $json_array['status'] = ERROR_CODE;
        $json_array['message'] = "Something went wrong,please try again";

        if ($this->input->is_ajax_request()) {
            $frm_details = $this->input->post();

            $component = explode(',', $frm_details['cases_id']);

            $update_counter = 0;
            $address = $not_found = $court = $pcc = $glodbver = $drug_narcotis = $education = array();

            foreach ($component as $key => $value) {

                $list = explode('||', $value);

                $reject_case[] = array('id' => $list[2], 'status' => 6, 'remarks' => $frm_details['reject_reason'], 'modified_by' => $this->user_info['id']);

                switch ($list[0]) {
                    case 'eduver':
                        $education[] = array('status' => 2, 'modified_by' => $this->user_info['id'], 'modified_on' => date(DB_DATE_FORMAT), 'id' => $list[1]);
                        break;
                    case 'addrver':
                        $address[] = array('status' => 2, 'modified_by' => $this->user_info['id'], 'modified_on' => date(DB_DATE_FORMAT), 'id' => $list[1]);
                        break;
                    case 'courtver':
                        $court[] = array('status' => 2, 'modified_by' => $this->user_info['id'], 'modified_on' => date(DB_DATE_FORMAT), 'id' => $list[1]);
                        break;
                    case 'crimver':
                        $pcc[] = array('status' => 2, 'modified_by' => $this->user_info['id'], 'modified_on' => date(DB_DATE_FORMAT), 'id' => $list[1]);
                        break;
                    case 'globdbver':
                        $glodbver[] = array('status' => 2, 'modified_by' => $this->user_info['id'], 'modified_on' => date(DB_DATE_FORMAT), 'id' => $list[1]);
                        break;
                    case 'narcver':
                        $drug_narcotis[] = array('status' => 2, 'modified_by' => $this->user_info['id'], 'modified_on' => date(DB_DATE_FORMAT), 'id' => $list[1]);
                        break;
                    default:
                        $not_found[] = array($list[3]);
                        break;
                }

            }

            if (empty($not_found)) {
                if (!empty($reject_case)) {
                    $this->common_model->update_batch_all('view_vendor_master_log', $reject_case, 'id');
                }

                if (!empty($education)) {
                    $this->common_model->update_batch_all('education_vendor_log', $education, 'id');
                }
                if (!empty($address)) {
                    $this->common_model->update_batch_all('address_vendor_log', $address, 'id');

                    $result_get_count_plus = $this->common_model->get_count(array('controllers' => 'assign_add'));

                    $total_get_count_plus = $result_get_count_plus + count($component);

                    $result_update_count_plus = $this->common_model->update_count(array('count' => $total_get_count_plus), array('controllers' => 'assign_add'));
                }

                if (!empty($court)) {
                    $this->common_model->update_batch_all('courtver_vendor_log', $court, 'id');
                }

                if (!empty($pcc)) {
                    $this->common_model->update_batch_all('pcc_vendor_log', $pcc, 'id');
                }

                if (!empty($glodbver)) {
                    $this->common_model->update_batch_all('glodbver_vendor_log', $glodbver, 'id');
                }
                if (!empty($drug_narcotis)) {
                    $this->common_model->update_batch_all('drug_narcotis_vendor_log', $drug_narcotis, 'id');
                }
                $json_array['status'] = SUCCESS_CODE;
                $json_array['message'] = "Cases Rejected";
            } else {
                $json_array['message'] = "Trasaction is not found" . implode(',', $not_found);
            }
        }
        echo_json($json_array);
    }

    public function vendor_aq_all_component()
    {

        if ($this->input->is_ajax_request()) {

            $lists = $this->vendor_master_log_model->get_new_list_vendor_aq();

            $x = 0;

            $html_view = '<thead><tr><th></th><th>Sr No</th><th>Comp Ref No</th><th>City</th><th>Pincode</th><th>State</th><th>Vendor Name</th><th>Allocated By</th><th>Allocated Date</th></tr></thead>';
            foreach ($lists as $list) {
                $html_view .= '<tr>';

                if (isset($list['add_com_ref'])) {
                    $component_name = "address";
                } elseif (isset($list['emp_com_ref'])) {
                    $component_name = "employment";

                } elseif (isset($list['education_com_ref'])) {
                    $component_name = "education";

                } elseif (isset($list['court_com_ref'])) {

                    $component_name = "court";
                } elseif (isset($list['global_com_ref'])) {
                    $component_name = "global";
                } elseif (isset($list['pcc_com_ref'])) {
                    $component_name = "pcc";
                } elseif (isset($list['credit_report_com_ref'])) {
                    $component_name = "credit";
                } elseif (isset($list['identity_com_ref'])) {
                    $component_name = "identity";
                } elseif (isset($list['drug_com_ref'])) {
                    $component_name = "drugs";
                } else {
                    $component_name = "";
                }

                $html_view .= "<td><input type='checkbox' id='select-checkbox' name='cases_id[]' class='cases_id' id='cases_id' value='" . $component_name . "||" . $list['id'] . "'></td>";

                $html_view .= "<td>" . ($x + 1) . "</td>";
                if (isset($list['add_com_ref'])) {
                    $html_view .= "<td>" . $list['add_com_ref'] . "</td>";
                    $html_view .= "<td>" . $list['city'] . "</td>";
                    $html_view .= "<td>" . $list['pincode'] . "</td>";
                    $html_view .= "<td>" . $list['state'] . "</td>";

                }
                if (isset($list['emp_com_ref'])) {

                    $html_view .= "<td>" . $list['emp_com_ref'] . "</td>";
                    $html_view .= "<td>" . $list['citylocality'] . "</td>";
                    $html_view .= "<td>" . $list['pincode'] . "</td>";
                    $html_view .= "<td>" . $list['state'] . "</td>";
                }

                if (isset($list['education_com_ref'])) {
                    $html_view .= "<td>" . $list['education_com_ref'] . "</td>";
                    $html_view .= "<td>" . $list['city'] . "</td>";
                    $html_view .= "<td>" . '-' . "</td>";
                    $html_view .= "<td>" . $list['state'] . "</td>";

                }

                if (isset($list['court_com_ref'])) {
                    $html_view .= "<td>" . $list['court_com_ref'] . "</td>";
                    $html_view .= "<td>" . $list['city'] . "</td>";
                    $html_view .= "<td>" . $list['pincode'] . "</td>";
                    $html_view .= "<td>" . $list['state'] . "</td>";

                }

                if (isset($list['global_com_ref'])) {
                    $html_view .= "<td>" . $list['global_com_ref'] . "</td>";
                    $html_view .= "<td>" . $list['city'] . "</td>";
                    $html_view .= "<td>" . $list['pincode'] . "</td>";
                    $html_view .= "<td>" . $list['state'] . "</td>";

                }

                if (isset($list['pcc_com_ref'])) {
                    $html_view .= "<td>" . $list['pcc_com_ref'] . "</td>";
                    $html_view .= "<td>" . $list['city'] . "</td>";
                    $html_view .= "<td>" . $list['pincode'] . "</td>";
                    $html_view .= "<td>" . $list['state'] . "</td>";

                }

                if (isset($list['credit_report_com_ref'])) {
                    $html_view .= "<td>" . $list['credit_report_com_ref'] . "</td>";
                    $html_view .= "<td>" . $list['city'] . "</td>";
                    $html_view .= "<td>" . $list['pincode'] . "</td>";
                    $html_view .= "<td>" . $list['state'] . "</td>";

                }
                if (isset($list['identity_com_ref'])) {
                    $html_view .= "<td>" . $list['identity_com_ref'] . "</td>";
                    $html_view .= "<td>" . $list['city'] . "</td>";
                    $html_view .= "<td>" . $list['pincode'] . "</td>";
                    $html_view .= "<td>" . $list['state'] . "</td>";

                }
                if (isset($list['drug_com_ref'])) {
                    $html_view .= "<td>" . $list['drug_com_ref'] . "</td>";
                    $html_view .= "<td>" . $list['city'] . "</td>";
                    $html_view .= "<td>" . $list['pincode'] . "</td>";
                    $html_view .= "<td>" . $list['state'] . "</td>";

                }

                $html_view .= "<td>" . $list['vendor_name'] . "</td>";
                $html_view .= "<td>" . $list['allocated_by'] . "</td>";

                $html_view .= "<td>" . convert_db_to_display_date($list['allocated_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12) . "</td>";
                $html_view .= '</tr>';

                $x++;
            }

            $json_array['status'] = SUCCESS_CODE;

            $json_array['message'] = $html_view;

        } else {
            $json_array['status'] = ERROR_CODE;

            $json_array['message'] = 'Something went wrong,please try again';
        }

        echo_json($json_array);

    }

    public function vendor_charges_all_component()
    {

        if ($this->input->is_ajax_request()) {

            $lists = $this->vendor_master_log_model->get_new_list_vendor_charges();

            $x = 1;

            $html_view = '<thead><tr><th></th><th>Sr No</th><th>Date</th><th>By</th><th>Comp Ref No</th><th>Vendor Name</th><th>City</th><th>State</th><th>Addtn Cost</th><th>Total Cost</th><th>Total Cost</th></tr></thead>';
            foreach ($lists as $list) {

                if (($list['accept_reject_cost'] == "0" || $list['accept_reject_cost'] == null)) {

                    $total_cost = $list['additional_cost'] + $list['cost'];

                    if (isset($list['add_com_ref'])) {
                        $comp_ref = $list['add_com_ref'];
                        $component_name = "address";
                        $city = $list['city'];
                    } elseif (isset($list['emp_com_ref'])) {
                        $comp_ref = $list['emp_com_ref'];
                        $component_name = "employment";
                        $city = $list['citylocality'];
                    } elseif (isset($list['education_com_ref'])) {
                        $comp_ref = $list['education_com_ref'];
                        $component_name = "education";
                        $city = $list['city'];
                    } elseif (isset($list['identity_com_ref'])) {
                        $comp_ref = $list['identity_com_ref'];
                        $component_name = "Identity";
                        $city = $list['city'];
                    } else {
                        $component_name = "";
                    }

                    $html_view .= '<tr>';
                    $html_view .= "<td><input type='checkbox' id='select-checkbox' name='cases_id[]' class='cases_id' id='cases_id' value='" . $component_name . "||" . $list['vendor_master_log_id'] . "||" . $list['vendor_cost_details_id'] . "||" . $list['cost'] . "||" . $list['additional_cost'] . "'></td>";

                    $html_view .= "<td>" . $x . "</td>";

                    $html_view .= "<td>" . convert_db_to_display_date($list['created_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12) . "</td>";
                    $html_view .= "<td>" . $list['created_by'] . "</td>";
                    $html_view .= "<td>" . $comp_ref . "</td>";
                    $html_view .= "<td>" . $list['vendor_name'] . "</td>";
                    $html_view .= "<td>" . $city . "</td>";
                    $html_view .= "<td>" . $list['state'] . "</td>";
                    $html_view .= "<td>" . $list['additional_cost'] . "</td>";
                    $html_view .= "<td>" . $list['cost'] . "</td>";
                    $html_view .= "<td>" . $total_cost . "</td>";

                    $html_view .= '</tr>';

                    $x++;
                }

            }

            $json_array['status'] = SUCCESS_CODE;

            $json_array['message'] = $html_view;

        } else {
            $json_array['status'] = ERROR_CODE;

            $json_array['message'] = 'Something went wrong,please try again';
        }

        echo_json($json_array);

    }

    public function approve_reject_case()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {

            $frm_details = $this->input->post();
            $action = $frm_details['action'];
            $component = explode(',', $frm_details['cases_id']);
            $update_counter = 0;

            foreach ($component as $key => $value) {

                $list = explode('||', $value);

                switch ($list[0]) {
                    case 'address':

                        $this->load->model(array('addressver_model'));

                        if ($frm_details['action'] == 2 && $list[1] != "") {
                            $update = $this->addressver_model->upload_vendor_assign('address_vendor_log', array('status' => 2, 'remarks' => $frm_details['reject_reason'], 'modified_by' => $this->user_info['id'], 'modified_on' => date(DB_DATE_FORMAT)), array('id' => $list[1]));

                            $result_get_count_plus = $this->common_model->get_count(array('controllers' => 'assign_add'));

                            $total_get_count_plus = $result_get_count_plus + 1;

                            $result_update_count_plus = $this->common_model->update_count(array('count' => $total_get_count_plus), array('controllers' => 'assign_add'));

                            $result_get_count_minus = $this->common_model->get_count(array('controllers' => 'address/approval_queue'));

                            $total_get_count_minus = $result_get_count_minus - 1;

                            $result_update_count_minus = $this->common_model->update_count(array('count' => $total_get_count_minus), array('controllers' => 'address/approval_queue'));

                            if ($update) {

                                $json_array['status'] = SUCCESS_CODE;
                                $json_array['message'] = "Successfully Update Record";

                            } else {
                                $json_array['status'] = ERROR_CODE;
                                $json_array['message'] = "Something went wrong,please try again";
                            }

                        } else if ($frm_details['action'] == 1 && $list[1] != "") {
                            $files = array();
                            $last_insert_id = $this->common_model->last_insert_id();
                            if ($last_insert_id > 0) {

                                $update = $this->addressver_model->upload_vendor_assign('address_vendor_log', array('status' => 1, 'approval_by' => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT)), array('id' => $list[1]));

                                if ($update) {
                                    $trasaction_id = ++$last_insert_id;
                                    $files[] = array('component' => 'addrver',
                                        'component_tbl_id' => '1',
                                        'case_id' => $list[1],
                                        'trasaction_id' => 'Txn' . $trasaction_id,
                                        "status" => 1,
                                        "remarks" => '',
                                        // "allocated_by"        => $this->user_info['id'],
                                        //  "allocated_on"        => date(DB_DATE_FORMAT),
                                        "approval_by" => $this->user_info['id'],
                                        "approval_on" => date(DB_DATE_FORMAT),
                                        "created_by" => $this->user_info['id'],
                                        "created_on" => date(DB_DATE_FORMAT),
                                        "vendor_status" => 0,
                                        "modified_on" => null,
                                        "modified_by" => '',
                                    );
                                }

                                if (!empty($files)) {
                                    $insert = $this->common_model->common_insert_batch('view_vendor_master_log', $files);
                                }

                                $result_get_count_plus = $this->common_model->get_count(array('controllers' => 'address'));

                                $total_get_count_plus = $result_get_count_plus + 1;

                                $result_update_count_plus = $this->common_model->update_count(array('count' => $total_get_count_plus), array('controllers' => 'address'));

                                $result_get_count_minus = $this->common_model->get_count(array('controllers' => 'address/approval_queue'));

                                $total_get_count_minus = $result_get_count_minus - 1;

                                $result_update_count_minus = $this->common_model->update_count(array('count' => $total_get_count_minus), array('controllers' => 'address/approval_queue'));

                                if ($update && $insert) {

                                    $json_array['status'] = SUCCESS_CODE;
                                    $json_array['message'] = "Successfully Update Record";

                                } else {
                                    $json_array['status'] = ERROR_CODE;
                                    $json_array['message'] = "Something went wrong,please try again";
                                }
                            }

                        }
                        break;
                    case 'employment':

                        $this->load->model(array('employment_model'));

                        if ($frm_details['action'] == 2 && $list[1] != "") {

                            $update = $this->employment_model->upload_vendor_assign('employment_vendor_log', array('status' => 2, 'remarks' => $frm_details['reject_reason'], 'modified_by' => $this->user_info['id'], 'modified_on' => date(DB_DATE_FORMAT)), array('id' => $list[1]));

                            $result_get_count_plus = $this->common_model->get_count(array('controllers' => 'assign_employment'));

                            $total_get_count_plus = $result_get_count_plus + 1;

                            $result_update_count_plus = $this->common_model->update_count(array('count' => $total_get_count_plus), array('controllers' => 'assign_employment'));

                            $result_get_count_minus = $this->common_model->get_count(array('controllers' => 'employment/approval_queue'));

                            $total_get_count_minus = $result_get_count_minus - 1;

                            $result_update_count_minus = $this->common_model->update_count(array('count' => $total_get_count_minus), array('controllers' => 'employment/approval_queue'));

                            if ($update) {

                                $json_array['status'] = SUCCESS_CODE;
                                $json_array['message'] = "Successfully Update Record";

                            } else {
                                $json_array['status'] = ERROR_CODE;
                                $json_array['message'] = "Something went wrong,please try again";
                            }

                        } else if ($frm_details['action'] == 1 && $list[1] != "") {

                            $files = array();
                            $last_insert_id = $this->common_model->last_insert_id();
                            if ($last_insert_id > 0) {

                                $update = $this->employment_model->upload_vendor_assign('employment_vendor_log', array('status' => 1, 'approval_by' => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT)), array('id' => $list[1]));

                                if ($update) {
                                    $trasaction_id = ++$last_insert_id;
                                    $files[] = array('component' => 'empver',
                                        'component_tbl_id' => '2',
                                        'case_id' => $list[1],
                                        'trasaction_id' => 'Txn' . $trasaction_id,
                                        "status" => 1,
                                        "remarks" => '',
                                        "approval_by" => $this->user_info['id'],
                                        "approval_on" => date(DB_DATE_FORMAT),
                                        "created_by" => $this->user_info['id'],
                                        "created_on" => date(DB_DATE_FORMAT),
                                        "vendor_status" => 0,
                                        "modified_on" => null,
                                        "modified_by" => '',
                                    );
                                }

                                if (!empty($files)) {
                                    $insert = $this->common_model->common_insert_batch('view_vendor_master_log', $files);
                                }

                                $result_get_count_plus = $this->common_model->get_count(array('controllers' => 'employment'));

                                $total_get_count_plus = $result_get_count_plus + 1;

                                $result_update_count_plus = $this->common_model->update_count(array('count' => $total_get_count_plus), array('controllers' => 'employment'));

                                $result_get_count_minus = $this->common_model->get_count(array('controllers' => 'employment/approval_queue'));

                                $total_get_count_minus = $result_get_count_minus - 1;

                                $result_update_count_minus = $this->common_model->update_count(array('count' => $total_get_count_minus), array('controllers' => 'employment/approval_queue'));

                                if ($update && $insert) {

                                    $json_array['status'] = SUCCESS_CODE;
                                    $json_array['message'] = "Successfully Update Record";

                                } else {
                                    $json_array['status'] = ERROR_CODE;
                                    $json_array['message'] = "Something went wrong,please try again";
                                }

                            }
                        }
                        break;
                    case 'education':

                        $this->load->model(array('education_model'));

                        if ($frm_details['action'] == 2 && $list[1] != "") {

                            $update = $this->education_model->upload_vendor_assign('education_vendor_log', array('status' => 2, 'remarks' => $frm_details['reject_reason'], 'modified_by' => $this->user_info['id'], 'modified_on' => date(DB_DATE_FORMAT)), array('id' => $list[1]));

                            $result_get_count_plus = $this->common_model->get_count(array('controllers' => 'assign_edu'));

                            $total_get_count_plus = $result_get_count_plus + 1;

                            $result_update_count_plus = $this->common_model->update_count(array('count' => $total_get_count_plus), array('controllers' => 'assign_edu'));

                            $result_get_count_minus = $this->common_model->get_count(array('controllers' => 'education/approval_queue'));

                            $total_get_count_minus = $result_get_count_minus - 1;

                            $result_update_count_minus = $this->common_model->update_count(array('count' => $total_get_count_minus), array('controllers' => 'education/approval_queue'));

                            if ($update) {

                                $json_array['status'] = SUCCESS_CODE;
                                $json_array['message'] = "Successfully Update Record";

                            } else {
                                $json_array['status'] = ERROR_CODE;
                                $json_array['message'] = "Something went wrong,please try again";
                            }

                        } else if ($frm_details['action'] == 1 && $list[1] != "") {

                            $files = array();
                            $last_insert_id = $this->common_model->last_insert_id();
                            if ($last_insert_id > 0) {

                                $update = $this->education_model->upload_vendor_assign('education_vendor_log', array('status' => 1, 'approval_by' => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT)), array('id' => $list[1]));

                                if ($update) {
                                    $trasaction_id = ++$last_insert_id;
                                    $files[] = array('component' => 'eduver',
                                        'component_tbl_id' => '3',
                                        'case_id' => $list[1],
                                        'trasaction_id' => 'Txn' . $trasaction_id,
                                        "status" => 1,
                                        "remarks" => '',
                                        "approval_by" => $this->user_info['id'],
                                        "approval_on" => date(DB_DATE_FORMAT),
                                        "created_by" => $this->user_info['id'],
                                        "created_on" => date(DB_DATE_FORMAT),
                                        "vendor_status" => 0,
                                        "modified_on" => null,
                                        "modified_by" => '',
                                    );
                                }
                                if (!empty($files)) {
                                    $insert = $this->common_model->common_insert_batch('view_vendor_master_log', $files);
                                }

                                $result_get_count_plus = $this->common_model->get_count(array('controllers' => 'education'));

                                $total_get_count_plus = $result_get_count_plus + 1;

                                $result_update_count_plus = $this->common_model->update_count(array('count' => $total_get_count_plus), array('controllers' => 'education'));

                                $result_get_count_minus = $this->common_model->get_count(array('controllers' => 'education/approval_queue'));

                                $total_get_count_minus = $result_get_count_minus - 1;

                                $result_update_count_minus = $this->common_model->update_count(array('count' => $total_get_count_minus), array('controllers' => 'education/approval_queue'));

                                if ($update && $insert) {

                                    $json_array['status'] = SUCCESS_CODE;
                                    $json_array['message'] = "Successfully Update Record";

                                } else {
                                    $json_array['status'] = ERROR_CODE;
                                    $json_array['message'] = "Something went wrong,please try again";
                                }
                            }

                        }
                        break;
                    case 'court':

                        $this->load->model(array('court_verificatiion_model'));

                        if ($frm_details['action'] == 2 && $list[1] != "") {

                            $update = $this->court_verificatiion_model->upload_vendor_assign('courtver_vendor_log', array('status' => 2, 'remarks' => $frm_details['reject_reason'], 'modified_by' => $this->user_info['id'], 'modified_on' => date(DB_DATE_FORMAT)), array('id' => $list[1]));

                            $result_get_count_plus = $this->common_model->get_count(array('controllers' => 'assign_court'));

                            $total_get_count_plus = $result_get_count_plus + 1;

                            $result_update_count_plus = $this->common_model->update_count(array('count' => $total_get_count_plus), array('controllers' => 'assign_court'));

                            $result_get_count_minus = $this->common_model->get_count(array('controllers' => 'court_verificatiion/approval_queue'));

                            $total_get_count_minus = $result_get_count_minus - 1;

                            $result_update_count_minus = $this->common_model->update_count(array('count' => $total_get_count_minus), array('controllers' => 'court_verificatiion/approval_queue'));

                            if ($update) {

                                $json_array['status'] = SUCCESS_CODE;
                                $json_array['message'] = "Successfully Update Record";

                            } else {
                                $json_array['status'] = ERROR_CODE;
                                $json_array['message'] = "Something went wrong,please try again";
                            }

                        } else if ($frm_details['action'] == 1 && $list[1] != "") {

                            $files = array();
                            $last_insert_id = $this->common_model->last_insert_id();
                            if ($last_insert_id > 0) {

                                $update = $this->court_verificatiion_model->upload_vendor_assign('courtver_vendor_log', array('status' => 1, 'approval_by' => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT)), array('id' => $list[1]));

                                if ($update) {
                                    $trasaction_id = ++$last_insert_id;
                                    $files[] = array('component' => 'courtver',
                                        'component_tbl_id' => '5',
                                        'case_id' => $list[1],
                                        'trasaction_id' => 'Txn' . $trasaction_id,
                                        "status" => 1,
                                        "remarks" => '',
                                        "approval_by" => $this->user_info['id'],
                                        "approval_on" => date(DB_DATE_FORMAT),
                                        "created_by" => $this->user_info['id'],
                                        "created_on" => date(DB_DATE_FORMAT),
                                        "vendor_status" => 0,
                                        "modified_on" => null,
                                        "modified_by" => '',
                                    );

                                }
                                if (!empty($files)) {
                                    $insert = $this->common_model->common_insert_batch('view_vendor_master_log', $files);
                                }

                                $result_get_count_plus = $this->common_model->get_count(array('controllers' => 'court_verificatiion'));

                                $total_get_count_plus = $result_get_count_plus + 1;

                                $result_update_count_plus = $this->common_model->update_count(array('count' => $total_get_count_plus), array('controllers' => 'court_verificatiion'));

                                $result_get_count_minus = $this->common_model->get_count(array('controllers' => 'court_verificatiion/approval_queue'));

                                $total_get_count_minus = $result_get_count_minus - 1;

                                $result_update_count_minus = $this->common_model->update_count(array('count' => $total_get_count_minus), array('controllers' => 'court_verificatiion/approval_queue'));

                                if ($update && $insert) {

                                    $json_array['status'] = SUCCESS_CODE;
                                    $json_array['message'] = "Successfully Update Record";

                                } else {
                                    $json_array['status'] = ERROR_CODE;
                                    $json_array['message'] = "Something went wrong,please try again";
                                }
                            }

                        }

                        break;
                    case 'global':

                        $this->load->model(array('global_database_model'));

                        if ($frm_details['action'] == 2 && $list[1] != "") {

                            $update = $this->global_database_model->upload_vendor_assign('glodbver_vendor_log', array('status' => 2, 'remarks' => $frm_details['reject_reason'], 'modified_by' => $this->user_info['id'], 'modified_on' => date(DB_DATE_FORMAT)), array('id' => $list[1]));

                            $result_get_count_plus = $this->common_model->get_count(array('controllers' => 'assign_global'));

                            $total_get_count_plus = ($result_get_count_plus + 1);

                            $result_update_count_plus = $this->common_model->update_count(array('count' => $total_get_count_plus), array('controllers' => 'assign_global'));

                            $result_get_count_minus = $this->common_model->get_count(array('controllers' => 'global_database/approval_queue'));

                            $total_get_count_minus = ($result_get_count_minus - 1);

                            $result_update_count_minus = $this->common_model->update_count(array('count' => $total_get_count_minus), array('controllers' => 'global_database/approval_queue'));

                            if ($update) {

                                $json_array['status'] = SUCCESS_CODE;
                                $json_array['message'] = "Successfully Update Record";

                            } else {
                                $json_array['status'] = ERROR_CODE;
                                $json_array['message'] = "Something went wrong,please try again";
                            }

                        } else if ($frm_details['action'] == 1 && $list[1] != "") {

                            $files = array();
                            $last_insert_id = $this->common_model->last_insert_id();
                            if ($last_insert_id > 0) {

                                $update = $this->global_database_model->upload_vendor_assign('glodbver_vendor_log', array('status' => 1, 'approval_by' => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT)), array('id' => $list[1]));

                                if ($update) {
                                    $trasaction_id = ++$last_insert_id;
                                    $files[] = array('component' => 'globdbver',
                                        'component_tbl_id' => '6',
                                        'case_id' => $list[1],
                                        'trasaction_id' => 'Txn' . $trasaction_id,
                                        "status" => 1,
                                        "remarks" => '',
                                        "approval_by" => $this->user_info['id'],
                                        "approval_on" => date(DB_DATE_FORMAT),
                                        "created_by" => $this->user_info['id'],
                                        "created_on" => date(DB_DATE_FORMAT),
                                        "vendor_status" => 0,
                                        "modified_on" => null,
                                        "modified_by" => '',
                                    );
                                }
                                if (!empty($files)) {
                                    $insert = $this->common_model->common_insert_batch('view_vendor_master_log', $files);
                                }

                                $result_get_count_plus = $this->common_model->get_count(array('controllers' => 'global_database'));

                                $total_get_count_plus = $result_get_count_plus + 1;

                                $result_update_count_plus = $this->common_model->update_count(array('count' => $total_get_count_plus), array('controllers' => 'global_database'));

                                $result_get_count_minus = $this->common_model->get_count(array('controllers' => 'global_database/approval_queue'));

                                $total_get_count_minus = $result_get_count_minus - 1;

                                $result_update_count_minus = $this->common_model->update_count(array('count' => $total_get_count_minus), array('controllers' => 'global_database/approval_queue'));

                                if ($update && $insert) {

                                    $json_array['status'] = SUCCESS_CODE;
                                    $json_array['message'] = "Successfully Update Record";

                                } else {
                                    $json_array['status'] = ERROR_CODE;
                                    $json_array['message'] = "Something went wrong,please try again";
                                }

                            }

                        }

                        break;
                    case 'pcc':

                        $this->load->model(array('pcc_verificatiion_model'));

                        if ($frm_details['action'] == 2 && $list[1] != "") {

                            $update = $this->pcc_verificatiion_model->upload_vendor_assign('pcc_vendor_log', array('status' => 2, 'remarks' => $frm_details['reject_reason'], 'modified_by' => $this->user_info['id'], 'modified_on' => date(DB_DATE_FORMAT)), array('id' => $list[1]));

                            $result_get_count_plus = $this->common_model->get_count(array('controllers' => 'assign_pcc'));

                            $total_get_count_plus = $result_get_count_plus + 1;

                            $result_update_count_plus = $this->common_model->update_count(array('count' => $total_get_count_plus), array('controllers' => 'assign_pcc'));

                            $result_get_count_minus = $this->common_model->get_count(array('controllers' => 'pcc/approval_queue'));

                            $total_get_count_minus = $result_get_count_minus - 1;

                            $result_update_count_minus = $this->common_model->update_count(array('count' => $total_get_count_minus), array('controllers' => 'pcc/approval_queue'));

                            if ($update) {

                                $json_array['status'] = SUCCESS_CODE;
                                $json_array['message'] = "Successfully Update Record";

                            } else {
                                $json_array['status'] = ERROR_CODE;
                                $json_array['message'] = "Something went wrong,please try again";
                            }

                        } else if ($frm_details['action'] == 1 && $list[1] != "") {

                            $files = array();
                            $last_insert_id = $this->common_model->last_insert_id();
                            if ($last_insert_id > 0) {

                                $update = $this->pcc_verificatiion_model->upload_vendor_assign('pcc_vendor_log', array('status' => 1, 'approval_by' => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT)), array('id' => $list[1]));

                                if ($update) {
                                    $trasaction_id = ++$last_insert_id;
                                    $files[] = array('component' => 'crimver',
                                        'component_tbl_id' => '8',
                                        'case_id' => $list[1],
                                        'trasaction_id' => 'Txn' . $trasaction_id,
                                        "status" => 1,
                                        "remarks" => '',
                                        "approval_by" => $this->user_info['id'],
                                        "approval_on" => date(DB_DATE_FORMAT),
                                        "created_by" => $this->user_info['id'],
                                        "created_on" => date(DB_DATE_FORMAT),
                                        "vendor_status" => 0,
                                        "modified_on" => null,
                                        "modified_by" => '',
                                    );

                                }
                                if (!empty($files)) {
                                    $insert = $this->common_model->common_insert_batch('view_vendor_master_log', $files);
                                }

                                $result_get_count_plus = $this->common_model->get_count(array('controllers' => 'pcc'));

                                $total_get_count_plus = $result_get_count_plus + 1;

                                $result_update_count_plus = $this->common_model->update_count(array('count' => $total_get_count_plus), array('controllers' => 'pcc'));

                                $result_get_count_minus = $this->common_model->get_count(array('controllers' => 'pcc/approval_queue'));

                                $total_get_count_minus = $result_get_count_minus - 1;

                                $result_update_count_minus = $this->common_model->update_count(array('count' => $total_get_count_minus), array('controllers' => 'pcc/approval_queue'));

                                if ($update && $insert) {

                                    $json_array['status'] = SUCCESS_CODE;
                                    $json_array['message'] = "Successfully Update Record";

                                } else {
                                    $json_array['status'] = ERROR_CODE;
                                    $json_array['message'] = "Something went wrong,please try again";
                                }
                            }
                        }
                        break;
                    case 'credit':
                        $this->load->model(array('credit_report_model'));

                        if ($frm_details['action'] == 2 && $list[1] != "") {

                            $update = $this->credit_report_model->upload_vendor_assign('credit_report_vendor_log', array('status' => 2, 'remarks' => $frm_details['reject_reason'], 'modified_by' => $this->user_info['id'], 'modified_on' => date(DB_DATE_FORMAT)), array('id' => $list[1]));

                            $result_get_count_plus = $this->common_model->get_count(array('controllers' => 'assign_credit_report'));

                            $total_get_count_plus = $result_get_count_plus + 1;

                            $result_update_count_plus = $this->common_model->update_count(array('count' => $total_get_count_plus), array('controllers' => 'assign_credit_report'));

                            $result_get_count_minus = $this->common_model->get_count(array('controllers' => 'credit_report/approval_queue'));

                            $total_get_count_minus = $result_get_count_minus - 1;

                            $result_update_count_minus = $this->common_model->update_count(array('count' => $total_get_count_minus), array('controllers' => 'credit_report/approval_queue'));

                            if ($update) {

                                $json_array['status'] = SUCCESS_CODE;
                                $json_array['message'] = "Successfully Update Record";

                            } else {
                                $json_array['status'] = ERROR_CODE;
                                $json_array['message'] = "Something went wrong,please try again";
                            }

                        } else if ($frm_details['action'] == 1 && $list[1] != "") {
                            $files = array();
                            $last_insert_id = $this->common_model->last_insert_id();
                            if ($last_insert_id > 0) {

                                $update = $this->credit_report_model->upload_vendor_assign('credit_report_vendor_log', array('status' => 1, 'approval_by' => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT)), array('id' => $list[1]));

                                if ($update) {
                                    $trasaction_id = ++$last_insert_id;
                                    $files[] = array('component' => 'cbrver',
                                        'component_tbl_id' => '10',
                                        'case_id' => $list[1],
                                        'trasaction_id' => 'Txn' . $trasaction_id,
                                        "status" => 1,
                                        "remarks" => '',
                                        // "allocated_by"        => $this->user_info['id'],
                                        //  "allocated_on"        => date(DB_DATE_FORMAT),
                                        "approval_by" => $this->user_info['id'],
                                        "approval_on" => date(DB_DATE_FORMAT),
                                        "created_by" => $this->user_info['id'],
                                        "created_on" => date(DB_DATE_FORMAT),
                                        "vendor_status" => 0,
                                        "modified_on" => null,
                                        "modified_by" => '',
                                    );

                                }
                                if (!empty($files)) {
                                    $insert = $this->common_model->common_insert_batch('view_vendor_master_log', $files);
                                }

                                $result_get_count_plus = $this->common_model->get_count(array('controllers' => 'credit_report'));

                                $total_get_count_plus = $result_get_count_plus + 1;

                                $result_update_count_plus = $this->common_model->update_count(array('count' => $total_get_count_plus), array('controllers' => 'credit_report'));

                                $result_get_count_minus = $this->common_model->get_count(array('controllers' => 'credit_report/approval_queue'));

                                $total_get_count_minus = $result_get_count_minus - 1;

                                $result_update_count_minus = $this->common_model->update_count(array('count' => $total_get_count_minus), array('controllers' => 'credit_report/approval_queue'));

                                if ($update && $insert) {

                                    $json_array['status'] = SUCCESS_CODE;
                                    $json_array['message'] = "Successfully Update Record";

                                } else {
                                    $json_array['status'] = ERROR_CODE;
                                    $json_array['message'] = "Something went wrong,please try again";
                                }

                            }
                        }
                        break;

                    case 'identity':

                        $this->load->model(array('identity_model'));

                        if ($frm_details['action'] == 2 && $list[1] != "") {

                            $update = $this->identity_model->upload_vendor_assign('identity_vendor_log', array('status' => 2, 'remarks' => $frm_details['reject_reason'], 'modified_by' => $this->user_info['id'], 'modified_on' => date(DB_DATE_FORMAT)), array('id' => $list[1]));

                            $result_get_count_plus = $this->common_model->get_count(array('controllers' => 'assign_identity'));

                            $total_get_count_plus = $result_get_count_plus + 1;

                            $result_update_count_plus = $this->common_model->update_count(array('count' => $total_get_count_plus), array('controllers' => 'assign_identity'));

                            $result_get_count_minus = $this->common_model->get_count(array('controllers' => 'identity/approval_queue'));

                            $total_get_count_minus = $result_get_count_minus - 1;

                            $result_update_count_minus = $this->common_model->update_count(array('count' => $total_get_count_minus), array('controllers' => 'identity/approval_queue'));

                            if ($update) {

                                $json_array['status'] = SUCCESS_CODE;
                                $json_array['message'] = "Successfully Update Record";

                            } else {
                                $json_array['status'] = ERROR_CODE;
                                $json_array['message'] = "Something went wrong,please try again";
                            }

                        } else if ($frm_details['action'] == 1 && $list[1] != "") {
                            $files = array();
                            $last_insert_id = $this->common_model->last_insert_id();
                            if ($last_insert_id > 0) {

                                $update = $this->identity_model->upload_vendor_assign('identity_vendor_log', array('status' => 1, 'approval_by' => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT)), array('id' => $list[1]));

                                if ($update) {
                                    $trasaction_id = ++$last_insert_id;
                                    $files[] = array('component' => 'identity',
                                        'component_tbl_id' => '9',
                                        'case_id' => $list[1],
                                        'trasaction_id' => 'Txn' . $trasaction_id,
                                        "status" => 1,
                                        "remarks" => '',
                                        "approval_by" => $this->user_info['id'],
                                        "approval_on" => date(DB_DATE_FORMAT),
                                        "created_by" => $this->user_info['id'],
                                        "created_on" => date(DB_DATE_FORMAT),
                                        "vendor_status" => 0,
                                        "modified_on" => null,
                                        "modified_by" => '',
                                    );

                                }
                                if (!empty($files)) {
                                    $insert = $this->common_model->common_insert_batch('view_vendor_master_log', $files);
                                }

                                $result_get_count_plus = $this->common_model->get_count(array('controllers' => 'identity'));

                                $total_get_count_plus = $result_get_count_plus + 1;

                                $result_update_count_plus = $this->common_model->update_count(array('count' => $total_get_count_plus), array('controllers' => 'identity'));

                                $result_get_count_minus = $this->common_model->get_count(array('controllers' => 'identity/approval_queue'));

                                $total_get_count_minus = $result_get_count_minus - 1;

                                $result_update_count_minus = $this->common_model->update_count(array('count' => $total_get_count_minus), array('controllers' => 'identity/approval_queue'));

                                if ($update && $insert) {

                                    $json_array['status'] = SUCCESS_CODE;
                                    $json_array['message'] = "Successfully Update Record";

                                } else {
                                    $json_array['status'] = ERROR_CODE;
                                    $json_array['message'] = "Something went wrong,please try again";
                                }

                            }
                        }

                        break;
                    case 'drugs':

                        $this->load->model(array('drug_verificatiion_model'));

                        if ($frm_details['action'] == 2 && $list[1] != "") {

                            $update = $this->drug_verificatiion_model->upload_vendor_assign('drug_narcotis_vendor_log', array('status' => 2, 'remarks' => $frm_details['reject_reason'], 'modified_by' => $this->user_info['id'], 'modified_on' => date(DB_DATE_FORMAT)), array('id' => $list[1]));

                            $result_get_count_plus = $this->common_model->get_count(array('controllers' => 'assign_drugs'));

                            $total_get_count_plus = $result_get_count_plus + 1;

                            $result_update_count_plus = $this->common_model->update_count(array('count' => $total_get_count_plus), array('controllers' => 'assign_drugs'));

                            $result_get_count_minus = $this->common_model->get_count(array('controllers' => 'drugs_narcotics/approval_queue'));

                            $total_get_count_minus = $result_get_count_minus - 1;

                            $result_update_count_minus = $this->common_model->update_count(array('count' => $total_get_count_minus), array('controllers' => 'drugs_narcotics/approval_queue'));

                            if ($update) {

                                $json_array['status'] = SUCCESS_CODE;
                                $json_array['message'] = "Successfully Update Record";

                            } else {
                                $json_array['status'] = ERROR_CODE;
                                $json_array['message'] = "Something went wrong,please try again";
                            }

                        } else if ($frm_details['action'] == 1 && $list[1] != "") {
                            $files = array();
                            $last_insert_id = $this->common_model->last_insert_id();
                            if ($last_insert_id > 0) {

                                $update = $this->drug_verificatiion_model->upload_vendor_assign('drug_narcotis_vendor_log', array('status' => 1, 'approval_by' => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT)), array('id' => $list[1]));

                                if ($update) {
                                    $trasaction_id = ++$last_insert_id;

                                    $files[] = array('component' => 'narcver',
                                        'component_tbl_id' => '7',
                                        'case_id' => $list[1],
                                        'trasaction_id' => 'Txn' . $trasaction_id,
                                        "status" => 1,
                                        "remarks" => '',
                                        "approval_by" => $this->user_info['id'],
                                        "approval_on" => date(DB_DATE_FORMAT),
                                        "created_by" => $this->user_info['id'],
                                        "created_on" => date(DB_DATE_FORMAT),
                                        "vendor_status" => 0,
                                        "modified_on" => null,
                                        "modified_by" => '',
                                    );

                                }
                                if (!empty($files)) {
                                    $insert = $this->common_model->common_insert_batch('view_vendor_master_log', $files);
                                }

                                $result_get_count_plus = $this->common_model->get_count(array('controllers' => 'drugs_narcotics'));

                                $total_get_count_plus = $result_get_count_plus + 1;

                                $result_update_count_plus = $this->common_model->update_count(array('count' => $total_get_count_plus), array('controllers' => 'drugs_narcotics'));

                                $result_get_count_minus = $this->common_model->get_count(array('controllers' => 'drugs_narcotics/approval_queue'));

                                $total_get_count_minus = $result_get_count_minus - 1;

                                $result_update_count_minus = $this->common_model->update_count(array('count' => $total_get_count_minus), array('controllers' => 'drugs_narcotics/approval_queue'));

                                if ($update && $insert) {

                                    $json_array['status'] = SUCCESS_CODE;
                                    $json_array['message'] = "Successfully Update Record";

                                } else {
                                    $json_array['status'] = ERROR_CODE;
                                    $json_array['message'] = "Something went wrong,please try again";
                                }
                            }
                        }
                        break;
                    default:
                        $json_array['status'] = SUCCESS_CODE;
                        $json_array['message'] = "Not Found Component";
                        break;
                }

            }

        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['message'] = "Something went wrong,please try again";
        }
        echo_json($json_array);
    }

    public function approve_reject_case_charges()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {

            $frm_details = $this->input->post();

            $action = $frm_details['action'];
            $component = explode(',', $frm_details['cases_id']);
            $update_counter = 0;

            foreach ($component as $key => $value) {

                $list = explode('||', $value);

                switch ($list[0]) {
                    case 'address':

                        $this->load->model(array('addressver_model'));

                        if ($frm_details['action'] == 2 && $list[1] != "" && $list[2] != "") {
                            $id = $list[2];
                            $vendor_master_log_id = $list[1];
                            $rejected_by = $this->user_info['id'];
                            $rejected_on = date(DB_DATE_FORMAT);

                            $result = $this->addressver_model->reject_cost(array("accept_reject_cost" => "2", "rejected_by" => $rejected_by, "rejected_on" => $rejected_on), array('id' => $id));

                            $result1 = $this->addressver_model->reject_cost_vendor(array("costing" => "0", "additional_costing" => "0"), array('id' => $vendor_master_log_id));
                            if ($result && $result1) {
                                $json_array['status'] = SUCCESS_CODE;

                                $json_array['message'] = 'Reject Vendor Cost ';
                            } else {
                                $json_array['status'] = ERROR_CODE;

                                $json_array['message'] = 'Something went wrong,please try again';
                            }
                        } else if ($frm_details['action'] == 1 && $list[1] != "" && $list[2] != "") {
                            $id = $list[2];

                            $cost = $list[3];
                            $add_cost = $list[4];
                            $vendor_master_log_id = $list[1];
                            $approved_by = $this->user_info['id'];
                            $aprroved_on = date(DB_DATE_FORMAT);

                            $result = $this->addressver_model->approve_cost(array("accept_reject_cost" => "1", "approved_by" => $approved_by, "approved_on" => $aprroved_on), array('id' => $id));

                            $result1 = $this->addressver_model->approve_cost_vendor(array("costing" => $cost, "additional_costing" => $add_cost), array('id' => $vendor_master_log_id));

                            if ($result && $result1) {
                                $json_array['status'] = SUCCESS_CODE;

                                $json_array['message'] = 'Approve Vendor Cost';
                            } else {
                                $json_array['status'] = ERROR_CODE;

                                $json_array['message'] = 'Something went wrong,please try again';
                            }
                        }
                        break;
                    case 'employment':

                        $this->load->model(array('employment_model'));

                        if ($frm_details['action'] == 2 && $list[1] != "" && $list[2] != "") {
                            $id = $list[2];
                            $vendor_master_log_id = $list[1];
                            $rejected_by = $this->user_info['id'];
                            $rejected_on = date(DB_DATE_FORMAT);

                            $result = $this->employment_model->reject_cost(array("accept_reject_cost" => "2", "rejected_by" => $rejected_by, "rejected_on" => $rejected_on), array('id' => $id));

                            $result1 = $this->employment_model->reject_cost_vendor(array("costing" => "0", "additional_costing" => "0"), array('id' => $vendor_master_log_id));

                            if ($result && $result1) {
                                $json_array['status'] = SUCCESS_CODE;

                                $json_array['message'] = 'Reject Vendor Cost ';

                            } else {

                                $json_array['status'] = ERROR_CODE;

                                $json_array['message'] = 'Something went wrong,please try again';
                            }

                        } else if ($frm_details['action'] == 1 && $list[1] != "" && $list[2] != "") {

                            $id = $list[2];

                            $cost = $list[3];
                            $add_cost = $list[4];
                            $vendor_master_log_id = $list[1];
                            $approved_by = $this->user_info['id'];
                            $aprroved_on = date(DB_DATE_FORMAT);

                            $result = $this->employment_model->approve_cost(array("accept_reject_cost" => "1", "approved_by" => $approved_by, "approved_on" => $aprroved_on), array('id' => $id));

                            $result1 = $this->employment_model->approve_cost_vendor(array("costing" => $cost, "additional_costing" => $add_cost), array('id' => $vendor_master_log_id));

                            if ($result && $result1) {

                                $json_array['status'] = SUCCESS_CODE;

                                $json_array['message'] = 'Approve Vendor Cost';

                            } else {
                                $json_array['status'] = ERROR_CODE;

                                $json_array['message'] = 'Something went wrong,please try again';
                            }
                        }
                        break;
                    case 'education':

                        $this->load->model(array('education_model'));

                        if ($frm_details['action'] == 2 && $list[1] != "" && $list[2] != "") {

                            $id = $list[2];
                            $vendor_master_log_id = $list[1];
                            $rejected_by = $this->user_info['id'];
                            $rejected_on = date(DB_DATE_FORMAT);

                            $result = $this->education_model->reject_cost(array("accept_reject_cost" => "2", "rejected_by" => $rejected_by, "rejected_on" => $rejected_on), array('id' => $id));

                            $result1 = $this->education_model->reject_cost_vendor(array("costing" => "0", "additional_costing" => "0"), array('id' => $vendor_master_log_id));

                            if ($result && $result1) {
                                $json_array['status'] = SUCCESS_CODE;

                                $json_array['message'] = 'Reject Vendor Cost ';

                            } else {

                                $json_array['status'] = ERROR_CODE;

                                $json_array['message'] = 'Something went wrong,please try again';
                            }

                        } else if ($frm_details['action'] == 1 && $list[1] != "" && $list[2] != "") {

                            $id = $list[2];
                            $cost = $list[3];
                            $add_cost = $list[4];
                            $vendor_master_log_id = $list[1];
                            $approved_by = $this->user_info['id'];
                            $aprroved_on = date(DB_DATE_FORMAT);

                            $result = $this->education_model->approve_cost(array("accept_reject_cost" => "1", "approved_by" => $approved_by, "approved_on" => $aprroved_on), array('id' => $id));

                            $result1 = $this->education_model->approve_cost_vendor(array("costing" => $cost, "additional_costing" => $add_cost), array('id' => $vendor_master_log_id));

                            if ($result && $result1) {

                                $json_array['status'] = SUCCESS_CODE;

                                $json_array['message'] = 'Approve Vendor Cost';

                            } else {
                                $json_array['status'] = ERROR_CODE;

                                $json_array['message'] = 'Something went wrong,please try again';

                            }
                        }
                        break;

                    case 'Identity':

                        $this->load->model(array('identity_model'));

                        if ($frm_details['action'] == 2 && $list[1] != "" && $list[2] != "") {
                            $id = $list[2];
                            $vendor_master_log_id = $list[1];
                            $rejected_by = $this->user_info['id'];
                            $rejected_on = date(DB_DATE_FORMAT);

                            $result = $this->identity_model->reject_cost(array("accept_reject_cost" => "2", "rejected_by" => $rejected_by, "rejected_on" => $rejected_on), array('id' => $id));

                            $result1 = $this->identity_model->reject_cost_vendor(array("costing" => "0", "additional_costing" => "0"), array('id' => $vendor_master_log_id));

                            if ($result && $result1) {
                                $json_array['status'] = SUCCESS_CODE;

                                $json_array['message'] = 'Reject Vendor Cost ';
                            } else {

                                $json_array['status'] = ERROR_CODE;

                                $json_array['message'] = 'Something went wrong,please try again';

                            }
                        } else if ($frm_details['action'] == 1 && $list[1] != "" && $list[2] != "") {

                            $id = $list[2];
                            $cost = $list[3];
                            $add_cost = $list[4];
                            $vendor_master_log_id = $list[1];
                            $approved_by = $this->user_info['id'];
                            $aprroved_on = date(DB_DATE_FORMAT);

                            $result = $this->identity_model->approve_cost(array("accept_reject_cost" => "1", "approved_by" => $approved_by, "approved_on" => $aprroved_on), array('id' => $id));

                            $result1 = $this->identity_model->approve_cost_vendor(array("costing" => $cost, "additional_costing" => $add_cost), array('id' => $vendor_master_log_id));

                            if ($result && $result1) {
                                $json_array['status'] = SUCCESS_CODE;

                                $json_array['message'] = 'Approve Vendor Cost';
                            } else {

                                $json_array['status'] = ERROR_CODE;

                                $json_array['message'] = 'Something went wrong,please try again';
                            }

                        }

                        break;
                    case 'drugs':

                        $this->load->model(array('drug_verificatiion_model'));

                        if ($frm_details['action'] == 2 && $list[1] != "") {

                            $update = $this->drug_verificatiion_model->upload_vendor_assign('drug_narcotis_vendor_log', array('status' => 2, 'remarks' => $frm_details['reject_reason'], 'modified_by' => $this->user_info['id'], 'modified_on' => date(DB_DATE_FORMAT)), array('id' => $list[1]));

                            $result_get_count_plus = $this->common_model->get_count(array('controllers' => 'assign_drugs'));

                            $total_get_count_plus = $result_get_count_plus + 1;

                            $result_update_count_plus = $this->common_model->update_count(array('count' => $total_get_count_plus), array('controllers' => 'assign_drugs'));

                            $result_get_count_minus = $this->common_model->get_count(array('controllers' => 'drugs_narcotics/approval_queue'));

                            $total_get_count_minus = $result_get_count_minus - 1;

                            $result_update_count_minus = $this->common_model->update_count(array('count' => $total_get_count_minus), array('controllers' => 'drugs_narcotics/approval_queue'));

                            if ($update) {

                                $json_array['status'] = SUCCESS_CODE;
                                $json_array['message'] = "Successfully Update Record";

                            } else {
                                $json_array['status'] = ERROR_CODE;
                                $json_array['message'] = "Something went wrong,please try again";
                            }

                        } else if ($frm_details['action'] == 1 && $list[1] != "") {
                            $files = array();
                            $last_insert_id = $this->common_model->last_insert_id();
                            if ($last_insert_id > 0) {

                                $update = $this->drug_verificatiion_model->upload_vendor_assign('drug_narcotis_vendor_log', array('status' => 1, 'approval_by' => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT)), array('id' => $list[1]));

                                if ($update) {
                                    $trasaction_id = ++$last_insert_id;

                                    $files[] = array('component' => 'narcver',
                                        'component_tbl_id' => '7',
                                        'case_id' => $list[1],
                                        'trasaction_id' => 'Txn' . $trasaction_id,
                                        "status" => 1,
                                        "remarks" => '',
                                        "approval_by" => $this->user_info['id'],
                                        "approval_on" => date(DB_DATE_FORMAT),
                                        "created_by" => $this->user_info['id'],
                                        "created_on" => date(DB_DATE_FORMAT),
                                        "vendor_status" => 0,
                                        "modified_on" => null,
                                        "modified_by" => '',
                                    );

                                }
                                if (!empty($files)) {
                                    $insert = $this->common_model->common_insert_batch('view_vendor_master_log', $files);
                                }

                                $result_get_count_plus = $this->common_model->get_count(array('controllers' => 'drugs_narcotics'));

                                $total_get_count_plus = $result_get_count_plus + 1;

                                $result_update_count_plus = $this->common_model->update_count(array('count' => $total_get_count_plus), array('controllers' => 'drugs_narcotics'));

                                $result_get_count_minus = $this->common_model->get_count(array('controllers' => 'drugs_narcotics/approval_queue'));

                                $total_get_count_minus = $result_get_count_minus - 1;

                                $result_update_count_minus = $this->common_model->update_count(array('count' => $total_get_count_minus), array('controllers' => 'drugs_narcotics/approval_queue'));

                                if ($update && $insert) {

                                    $json_array['status'] = SUCCESS_CODE;
                                    $json_array['message'] = "Successfully Update Record";

                                } else {
                                    $json_array['status'] = ERROR_CODE;
                                    $json_array['message'] = "Something went wrong,please try again";
                                }
                            }
                        }
                        break;
                    default:
                        $json_array['status'] = SUCCESS_CODE;
                        $json_array['message'] = "Not Found Component";
                        break;
                }

            }

        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['message'] = "Something went wrong,please try again";
        }
        echo_json($json_array);
    }

}
