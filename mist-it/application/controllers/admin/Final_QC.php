<?php defined('BASEPATH') or exit('No direct script access allowed');

class Final_QC extends MY_Controller
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

        $this->perm_array = array('page_id' => 20);
        $this->load->model('final_qc_model');
    }

    public function index()
    {
        $data['header_title'] = "Final QC Verification";

        $data['filter_view_final_qc'] = $this->filter_view_final_qc_list();

        $this->load->view('admin/header', $data);

        $this->load->view('admin/final_qu_view');

        $this->load->view('admin/footer');
    }

    

    public function annexture_list_view()
    {
        $data['header_title'] = "Final QC Verification";

     //   $data['filter_view_final_qc_annexure'] = $this->filter_view_final_qc_annexure();


        $this->load->view('admin/annexture_list_view' , $data);
    }


    protected function filter_view_final_qc()
    {

        $data['clients'] = $this->get_clients_for_approval_queue();

        $data['status'] = status_frm_db();
        return $this->load->view('admin/filter_view_final_qc', $data, true);
    }

    protected function filter_view_final_qc_annexure()
    {

        $data['clients_ids'] = $this->get_clients_for_approval_queue_annexure();

        return $this->load->view('admin/filter_view_final_qc_annexure', $data, true);
    }


    protected function filter_view_final_qc_list()
    {

        $data['clients'] = $this->get_clients_for_list_view();

        $data['status'] = status_frm_db();
        return $this->load->view('admin/filter_view_final_qc', $data, true);
    }

    public function get_clients_for_approval_queue()
    {

        $clients = $this->final_qc_model->select_client('clients', false, array('clients.id', 'clients.clientname'));

        $clients_arry[0] = 'Select Client';

        foreach ($clients as $key => $value) {
            $clients_arry[$value['id']] = ucwords(strtolower($value['clientname']));
        }
        return $clients_arry;
    }

    public function get_clients_for_approval_queue_annexure()
    {

        $clients = $this->final_qc_model->select_client_annexure('clients', false, array('clients.id', 'clients.clientname'));

        $clients_arry[0] = 'Select Client';

        foreach ($clients as $key => $value) {
            $clients_arry[$value['id']] = ucwords(strtolower($value['clientname']));
        }
        return $clients_arry;
    }


    public function get_clients_for_list_view()
    {

        $clients = $this->final_qc_model->select_client_list_view('clients', false, array('clients.id', 'clients.clientname'));

        $clients_arry[0] = 'Select Client';

        foreach ($clients as $key => $value) {
            $clients_arry[$value['id']] = ucwords(strtolower($value['clientname']));
        }
        return $clients_arry;
    }

    public function final_qc_view_datatable()
    {

        if ($this->input->is_ajax_request()) {
            $candidates = $data_arry = array();

            $params = $_REQUEST;

            $columns = array('id', 'CandidateName', 'created_by', 'ClientRefNumber', 'cmp_ref_no', 'overallstatus', 'encry_id');

            $final_qc_list = $this->final_qc_model->select_final_qc($params, $columns);
            $totalRecords = count($this->final_qc_model->select_final_count_qc($params, $columns));

            $x = 0;

            foreach ($final_qc_list as $final_qc_lists) {

                $last_activity_component = $this->final_qc_model->select_last_activity_component_name(array('candsid' => $final_qc_lists['id']));
                
                $data_arry[$x]['id'] = $x + 1;
                $data_arry[$x]['modified_on'] = convert_db_to_display_date($final_qc_lists['modified_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);
                //$data_arry[$x]['created_by'] = ucwords($final_qc_lists['username']);
                $data_arry[$x]['caserecddate'] = convert_db_to_display_date($final_qc_lists['caserecddate']);
                $data_arry[$x]['clientname'] = ucwords($final_qc_lists['clientname']);
                $data_arry[$x]['entity'] = $final_qc_lists['entity_name'];
                $data_arry[$x]['package'] = $final_qc_lists['package_name'];
                $data_arry[$x]['CandidateName'] = $final_qc_lists['CandidateName'];
                $data_arry[$x]['ClientRefNumber'] = $final_qc_lists['ClientRefNumber'];
                $data_arry[$x]['cmp_ref_no'] = $final_qc_lists['cmp_ref_no'];
                $data_arry[$x]['overallstatus'] = $final_qc_lists['status_value'];
                $data_arry[$x]['final_qc_send_mail_timestamp'] = convert_db_to_display_date($final_qc_lists['final_qc_send_mail_timestamp'],DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);
                $data_arry[$x]['last_activity_component'] = $last_activity_component[0]['component_name'];
                $data_arry[$x]['encry_id'] = ADMIN_SITE_URL . "Final_QC/report_html_form_final/" . encrypt($final_qc_lists['id']) . "/Final";
                $url = ($this->permission['access_final_list_download'] == 1) ? '<a target="__blank" href="'.ADMIN_SITE_URL.'candidates/report_final_view/'.encrypt($final_qc_lists['id']).'/final_report" > <button type="button" class="btn btn-danger"> Download </button></a>' : '';
                $url_approve = ($this->permission['access_final_list_approved'] == 1) ? '<button class="btn btn-info brn_first_qc_approve" id="'.$final_qc_lists['id'].'">Approve</button>' : '';
                $data_arry[$x]['action'] =  $url ." ". $url_approve;

                $x++;

            }

            $json_data = array("draw" => intval($params['draw']),
                "recordsTotal" => intval($totalRecords),
                "recordsFiltered" => intval($totalRecords),
                "data" => $data_arry,
            );
            

            echo_json($json_data);

        
     
        }

    }

    public function final_qc_view_approve_datatable()
    {

        if ($this->input->is_ajax_request()) {
            $candidates = $data_arry = array();

            $params = $_REQUEST;

            $columns = array('id', 'CandidateName', 'created_by', 'ClientRefNumber', 'cmp_ref_no', 'overallstatus', 'encry_id');

            $final_qc_list = $this->final_qc_model->select_final_approve_qc($params, $columns);
            $totalRecords = count($this->final_qc_model->select_final_approve_count_qc($params, $columns));

            $x = 0;

            foreach ($final_qc_list as $final_qc_lists) {

                if($final_qc_lists['final_qc_send_mail'] == "4"){
                    $final_qc_send_mail  =  '<button type="button" class="btn btn-primary">Sending...</button>';
                }
                elseif($final_qc_lists['final_qc_send_mail'] == "1"){
                    $final_qc_send_mail  = '<button type="button" class="btn btn-success">Send !s</button>';
                }
                elseif($final_qc_lists['final_qc_send_mail'] == "0"){
                    $final_qc_send_mail  = '<button type="button" class="btn btn-warning">Pending</button>';
                }
                elseif($final_qc_lists['final_qc_send_mail'] == "2"){
                    $final_qc_send_mail  = '<button type="button" class="btn btn-danger">Reject</button>';
                }else{
                    $final_qc_send_mail  = ""; 
                }

                $data_arry[$x]['id'] = $final_qc_lists['id'];
                $data_arry[$x]['final_qc_approve_reject_timestamp'] = convert_db_to_display_date($final_qc_lists['final_qc_approve_reject_timestamp'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);
                $data_arry[$x]['created_by'] = ucwords($final_qc_lists['username']);
                $data_arry[$x]['caserecddate'] = convert_db_to_display_date($final_qc_lists['caserecddate']);
                $data_arry[$x]['clientname'] = ucwords($final_qc_lists['clientname']);
                $data_arry[$x]['entity'] = $final_qc_lists['entity_name'];
                $data_arry[$x]['package'] = $final_qc_lists['package_name'];
                $data_arry[$x]['CandidateName'] = $final_qc_lists['CandidateName'];
                $data_arry[$x]['ClientRefNumber'] = $final_qc_lists['ClientRefNumber'];
                $data_arry[$x]['sending_status'] = $final_qc_send_mail;
                $data_arry[$x]['cmp_ref_no'] = $final_qc_lists['cmp_ref_no'];
                $data_arry[$x]['overallstatus'] = $final_qc_lists['status_value'];
                $data_arry[$x]['final_qc_send_mail_timestamp'] = convert_db_to_display_date($final_qc_lists['final_qc_send_mail_timestamp'],DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);
                $data_arry[$x]['encry_id'] = ADMIN_SITE_URL . "Final_qc/report_html_form_final/" . encrypt($final_qc_lists['id']) . "/Final";

                $x++;

            }

            $json_data = array("draw" => intval($params['draw']),
                "recordsTotal" => intval($totalRecords),
                "recordsFiltered" => intval($totalRecords),
                "data" => $data_arry,
            );

            echo_json($json_data);
        }

    }

    

  /* public function final_qc_view_approve_datatable_annexture()
    {

        if ($this->input->is_ajax_request()) {
            $candidates = $data_arry = array();

            $params = $_REQUEST;

            $columns = array('id', 'CandidateName', 'created_by', 'ClientRefNumber', 'cmp_ref_no', 'overallstatus', 'encry_id');

            $final_qc_list = $this->final_qc_model->select_final_approve_qc_annexture($params, $columns);
            $totalRecords = count($this->final_qc_model->select_final_approve_annexture_count_qc($params, $columns));

            $x = 0;

            foreach ($final_qc_list as $final_qc_lists) {


                $data_arry[$x]['id'] = $final_qc_lists['id'];
                $data_arry[$x]['final_qc_approve_reject_timestamp'] = convert_db_to_display_date($final_qc_lists['final_qc_approve_reject_timestamp'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);
                $data_arry[$x]['created_by'] = ucwords($final_qc_lists['username']);
                $data_arry[$x]['caserecddate'] = convert_db_to_display_date($final_qc_lists['caserecddate']);
                $data_arry[$x]['clientname'] = ucwords($final_qc_lists['clientname']);
                $data_arry[$x]['entity'] = $final_qc_lists['entity_name'];
                $data_arry[$x]['package'] = $final_qc_lists['package_name'];
                $data_arry[$x]['CandidateName'] = $final_qc_lists['CandidateName'];
                $data_arry[$x]['ClientRefNumber'] = $final_qc_lists['ClientRefNumber'];
                $data_arry[$x]['cmp_ref_no'] = $final_qc_lists['cmp_ref_no'];
                $data_arry[$x]['overallstatus'] = $final_qc_lists['status_value'];
                
                $data_arry[$x]['encry_id'] = ADMIN_SITE_URL . "Final_qc/report_html_form_final/" . encrypt($final_qc_lists['id']) . "/Final";

                $x++;

            }

            $json_data = array("draw" => intval($params['draw']),
                "recordsTotal" => intval($totalRecords),
                "recordsFiltered" => intval($totalRecords),
                "data" => $data_arry,
            );

            echo_json($json_data);
        }

    }*/

    public function final_qc_view_approve_datatable_annexture()
    {

        if ($this->input->is_ajax_request()) {
            $candidates = $data_arry = array();

            $params = $_REQUEST;

            $columns = array('id', 'CandidateName', 'created_by', 'ClientRefNumber', 'cmp_ref_no', 'overallstatus', 'encry_id');

            $final_qc_list = $this->final_qc_model->select_final_approve_qc_annexture($params, $columns);
            $totalRecords = count($this->final_qc_model->select_final_approve_annexture_count_qc($params, $columns));

            $x = 0;

            foreach ($final_qc_list as $final_qc_lists) {

             
                $data_arry[$x]['id'] = $final_qc_lists['id'];
                $data_arry[$x]['mod_time'] = convert_db_to_display_date($final_qc_lists['mod_time'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);
                $data_arry[$x]['vendor_name'] = ucwords($final_qc_lists['vendor_name']);
                $data_arry[$x]['iniated_date'] = convert_db_to_display_date($final_qc_lists['iniated_date']);
                $data_arry[$x]['clientname'] = ucwords($final_qc_lists['clientname']);
                $data_arry[$x]['entity'] = $final_qc_lists['entity_name'];
                $data_arry[$x]['package'] = $final_qc_lists['package_name'];
                $data_arry[$x]['CandidateName'] = $final_qc_lists['CandidateName'];
                $data_arry[$x]['ClientRefNumber'] = $final_qc_lists['ClientRefNumber'];
                $data_arry[$x]['cmp_ref_no'] = $final_qc_lists['cmp_ref_no'];
                $data_arry[$x]['overallstatus'] = $final_qc_lists['status_value'];
                
                $data_arry[$x]['encry_id'] = ADMIN_SITE_URL . "Final_qc/report_html_form_final/" . encrypt($final_qc_lists['id']) . "/Final";

                $x++;

            }

            $json_data = array("draw" => intval($params['draw']),
                "recordsTotal" => intval($totalRecords),
                "recordsFiltered" => intval($totalRecords),
                "data" => $data_arry,
            );

            echo_json($json_data);
        }

    }

    /*public function all_component_closed_qc_status($cands_id,$component_check,$component_first_qc_check)
    {

    $this->load->model('Final_qc_model');

    if(isset($component_check))
    {
    $component_check  =  explode(',',$component_check);
    }
    else
    {
    $component_check  = array();
    }

    if(isset($component_first_qc_check))
    {
    $component_first_check  =  explode(',',$component_first_qc_check);
    }
    else
    {
    $component_first_check  = array();
    }

    if(in_array('addrver', $component_check))
    {

    $result =  $this->Final_qc_model->get_address_closed_qc_result($cands_id);

    if(!empty($result))
    {
    foreach ($result as $key => $value)
    {

    $statuss =  $value['var_filter_status'] == "Closed" || $value['var_filter_status'] == "closed" ? "" : 'Address';
    $component_status[] =  $statuss;
    }
    }
    }

    if(in_array('addrver', $component_first_check))
    {

    $result =  $this->Final_qc_model->get_address_closed_qc_result($cands_id);

    if(!empty($result))
    {
    foreach ($result as $key => $value)
    {

    $statuss =   $value['first_qc_approve'] == "First QC Approve" ? "" : 'Address';
    $component_first_qc_status[] =  $statuss;
    }
    }
    }

    if(in_array('empver', $component_check))
    {

    $result =  $this->Final_qc_model->get_employment_closed_qc_result($cands_id);

    if(!empty($result))
    {
    foreach ($result as $key => $value)
    {
    $statuss =  $value['var_filter_status'] == "Closed" || $value['var_filter_status'] == "closed" ? "" : 'Employment';
    $component_status[] =  $statuss;
    }
    }
    }

    if(in_array('empver', $component_first_check))
    {

    $result =  $this->Final_qc_model->get_employment_closed_qc_result($cands_id);

    if(!empty($result))
    {
    foreach ($result as $key => $value)
    {

    $statuss =   $value['first_qc_approve'] == "First QC Approve" ? "" : 'Employment';
    $component_first_qc_status[] =  $statuss;
    }
    }
    }

    if(in_array('eduver', $component_check))
    {

    $result =  $this->Final_qc_model->get_education_closed_qc_result($cands_id);

    if(!empty($result))
    {
    foreach ($result as $key => $value)
    {
    $statuss =  $value['var_filter_status'] == "Closed" || $value['var_filter_status'] == "closed" ? "" : 'Education';
    $component_status[] =  $statuss;
    }
    }
    }

    if(in_array('eduver', $component_first_check))
    {

    $result =  $this->Final_qc_model->get_education_closed_qc_result($cands_id);

    if(!empty($result))
    {
    foreach ($result as $key => $value)
    {

    $statuss =   $value['first_qc_approve'] == "First QC Approve" ? "" : 'Education';
    $component_first_qc_status[] =  $statuss;
    }
    }
    }

    if(in_array('refver', $component_check))
    {

    $result =  $this->Final_qc_model->get_reference_closed_qc_result($cands_id);

    if(!empty($result))
    {
    foreach ($result as $key => $value)
    {
    $statuss =  $value['var_filter_status'] == "Closed" || $value['var_filter_status'] == "closed" ? "" : 'Reference';
    $component_status[] =  $statuss;
    }
    }
    }

    if(in_array('refver', $component_first_check))
    {

    $result =  $this->Final_qc_model->get_reference_closed_qc_result($cands_id);

    if(!empty($result))
    {
    foreach ($result as $key => $value)
    {

    $statuss =   $value['first_qc_approve'] == "First QC Approve" ? "" : 'Reference';
    $component_first_qc_status[] =  $statuss;
    }
    }
    }

    if(in_array('courtver', $component_check))
    {

    $result =  $this->Final_qc_model->get_court_closed_qc_result($cands_id);

    if(!empty($result))
    {
    foreach ($result as $key => $value)
    {
    $statuss =  $value['var_filter_status'] == "Closed" || $value['var_filter_status'] == "closed" ? "" : 'Court';
    $component_status[] =  $statuss;
    }
    }
    }

    if(in_array('courtver', $component_first_check))
    {

    $result =  $this->Final_qc_model->get_court_closed_qc_result($cands_id);

    if(!empty($result))
    {
    foreach ($result as $key => $value)
    {

    $statuss =   $value['first_qc_approve'] == "First QC Approve" ? "" : 'Court';
    $component_first_qc_status[] =  $statuss;
    }
    }
    }

    if(in_array('globdbver', $component_check))
    {

    $result =  $this->Final_qc_model->get_global_closed_qc_result($cands_id);

    if(!empty($result))
    {
    foreach ($result as $key => $value)
    {
    $statuss =  $value['var_filter_status'] == "Closed" || $value['var_filter_status'] == "closed" ? "" : 'Global';
    $component_status[] =  $statuss;
    }
    }
    }

    if(in_array('globdbver', $component_first_check))
    {

    $result =  $this->Final_qc_model->get_global_closed_qc_result($cands_id);

    if(!empty($result))
    {
    foreach ($result as $key => $value)
    {

    $statuss =   $value['first_qc_approve'] == "First QC Approve" ? "" : 'Global';
    $component_first_qc_status[] =  $statuss;
    }
    }
    }

    if(in_array('crimver', $component_check))
    {

    $result =  $this->Final_qc_model->get_pcc_closed_qc_result($cands_id);

    if(!empty($result))
    {
    foreach ($result as $key => $value)
    {
    $statuss =  $value['var_filter_status'] == "Closed" || $value['var_filter_status'] == "closed" ? "" : 'PCC';
    $component_status[] =  $statuss;
    }
    }
    }

    if(in_array('crimver', $component_first_check))
    {

    $result =  $this->Final_qc_model->get_pcc_closed_qc_result($cands_id);

    if(!empty($result))
    {
    foreach ($result as $key => $value)
    {

    $statuss =   $value['first_qc_approve'] == "First QC Approve" ? "" : 'PCC';
    $component_first_qc_status[] =  $statuss;
    }
    }
    }

    if(in_array('identity', $component_check))
    {

    $result =  $this->Final_qc_model->get_identity_closed_qc_result($cands_id);

    if(!empty($result))
    {
    foreach ($result as $key => $value)
    {
    $statuss =  $value['var_filter_status'] == "Closed" || $value['var_filter_status'] == "closed" ? "" : 'Identity';
    $component_status[] =  $statuss;
    }
    }
    }

    if(in_array('identity', $component_first_check))
    {

    $result =  $this->Final_qc_model->get_identity_closed_qc_result($cands_id);

    if(!empty($result))
    {
    foreach ($result as $key => $value)
    {

    $statuss =   $value['first_qc_approve'] == "First QC Approve" ? "" : 'Identity';
    $component_first_qc_status[] =  $statuss;
    }
    }
    }

    if(in_array('cbrver', $component_check))
    {

    $result =  $this->Final_qc_model->get_credit_report_closed_qc_result($cands_id);

    if(!empty($result))
    {
    foreach ($result as $key => $value)
    {
    $statuss =  $value['var_filter_status'] == "Closed" || $value['var_filter_status'] == "closed" ? "" : 'Credit Report';
    $component_status[] =  $statuss;
    }
    }
    }

    if(in_array('cbrver', $component_first_check))
    {

    $result =  $this->Final_qc_model->get_credit_report_closed_qc_result($cands_id);

    if(!empty($result))
    {
    foreach ($result as $key => $value)
    {

    $statuss =   $value['first_qc_approve'] == "First QC Approve" ? "" : 'Credit Report';
    $component_first_qc_status[] =  $statuss;
    }
    }
    }

    if(in_array('narcver', $component_check))
    {

    $result =  $this->Final_qc_model->get_drugs_closed_qc_result($cands_id);

    if(!empty($result))
    {
    foreach ($result as $key => $value)
    {
    $statuss =  $value['var_filter_status'] == "Closed" || $value['var_filter_status'] == "closed" ? "" : 'Drugs';
    $component_status[] =  $statuss;
    }
    }
    }

    if(in_array('narcver', $component_first_check))
    {

    $result =  $this->Final_qc_model->get_drugs_closed_qc_result($cands_id);

    if(!empty($result))
    {
    foreach ($result as $key => $value)
    {

    $statuss =   $value['first_qc_approve'] == "First QC Approve" ? "" : 'Drugs';
    $component_first_qc_status[] =  $statuss;
    }
    }
    }

    if(isset($component_status))
    {
    if(count(array_filter($component_status)) == 0)
    {
    $component_status1 = "1";
    }
    else
    {
    $component_status1 = "2";
    }
    }
    else
    {
    $component_status1 = "";
    }

    if(isset($component_first_qc_status))
    {
    if(count(array_filter($component_first_qc_status)) == 0)
    {
    $component_first_qc_status1 = "1";
    }
    else
    {
    $component_first_qc_status1 = "2";
    }
    }
    else
    {
    $component_first_qc_status1 = "";
    }

    $return_array = array("actual_status" => $component_status1,"first_qc_status" => $component_first_qc_status1);

    return  $return_array;
    }

     */
    public function report_html_form($com_id)
    {

        //if($this->permission['access_final_QC_reject']) {

        $this->load->model('candidates_model');

        $candidate_details = $this->candidates_model->get_component_details(array('candidates_info.id' => decrypt($com_id)));

        if (!empty($candidate_details)) {
            $data['candidate_details'] = array_map('ucwords', $candidate_details[0]);
            $data['component_details'] = $this->all_component($candidate_details[0], decrypt($com_id));

            $this->load->view('admin/header', $data);

            $this->load->view('admin/report_html_view');

            $this->load->view('admin/footer');
        } else {
            echo '<p>Record not found</p>';
        }

    }

    public function report_html_form_final($com_id, $report_type)
    {

        //if($this->permission['access_final_QC_reject']) {

        $this->load->model('candidates_model');

        $candidate_details = $this->candidates_model->get_component_details(array('candidates_info.id' => decrypt($com_id)));

        if (!empty($candidate_details)) {
            $data['candidate_details'] = array_map('ucwords', $candidate_details[0]);
            $data['component_details'] = $this->all_component($candidate_details[0], decrypt($com_id));
            $data['report_type'] = $report_type;
            $data['candidate_id'] = decrypt($com_id);
            $data['ClientRefNumber'] = $candidate_details[0]['ClientRefNumber'];

            echo $this->load->view('admin/report_html_view_final_report', $data, true);

        } else {
            echo '<p>Record not found</p>';
        }

    }

    public function report_html_form_final_view($com_id, $report_type)
    {

        //if($this->permission['access_final_QC_reject']) {

        $this->load->model('candidates_model');

        $candidate_details = $this->candidates_model->get_component_details(array('candidates_info.id' => decrypt($com_id)));

        if (!empty($candidate_details)) {
            $data['candidate_details'] = array_map('ucwords', $candidate_details[0]);
            $data['component_details'] = $this->all_component($candidate_details[0], decrypt($com_id));
            $data['report_type'] = $report_type;

            echo $this->load->view('admin/report_html_view_final_report_download', $data, true);

        } else {
            echo '<p>Record not found</p>';
        }

    }

    public function save_final_report()
    {
        $this->load->model('candidates_model');

        $frm_details = $this->input->post();

        $candidate_details = $this->candidates_model->get_component_details(array('candidates_info.id' => $frm_details['candidate_id']));
   
        if (!empty($candidate_details)) {
            $data['candidate_details'] = array_map('ucwords', $candidate_details[0]);
            $data['component_details'] = $this->all_component($candidate_details[0], $frm_details['candidate_id']);
            $data['report_type'] = 'Final';

            $massage = $this->load->view('admin/report_html_view_final_report_download', $data, true);

        } else {
            $massage = '<p>Record not found</p>';
        }

        /* $file_extension = 'html';
        $my_file = 'final_report_'.DATE(UPLOAD_FILE_DATE_FORMAT);
        $my_file_name  = $my_file.'.'.$file_extension;

        $handle = fopen("uploads/candidate_report_file/".$my_file_name, 'w') or die('Cannot open file:  '.$my_file);

        fwrite($handle,$massage);*/

        $field_array = array('candsid' => $frm_details['candidate_id'],
            'created_on' => date(DB_DATE_FORMAT),
            'created_by' => $this->user_info['id'],
            'action' => "Final Report Approved",
            'component_type' => "Final Report",
            'ClientRefNumber' => $candidate_details[0]['ClientRefNumber'],
            // 'remarks' => $my_file_name

        );

        $field_array_candidate = array('final_qc' => "Final QC Approve",
            'final_qc_updated_by' => $this->user_info['id'],
            'final_qc_approve_reject_timestamp' => date(DB_DATE_FORMAT),
        );

    
        $result_final_report = $this->candidates_model->save($field_array_candidate, array('candidates_info.id' => $frm_details['candidate_id']));
        $result_activity_log = $this->candidates_model->save_activity_log($field_array);

        if (!empty($result_final_report) && !empty($result_activity_log)) {

            $user_activity_data = $this->common_model->user_actity_data(array('component' => "Candidate",'ref_no' => $candidate_details[0]['cmp_ref_no'], 'candidate_name' => $candidate_details[0]['CandidateName'], 'created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id'], 'activity_type' => 'Manual', 'action' => 'Final QC Approved'));

            $json_array['status'] = SUCCESS_CODE;

            $json_array['message'] = 'Candidate Updated Successfully';

            $json_array['redirect'] = ADMIN_SITE_URL . 'candidates';
        } else {
            $json_array['status'] = ERROR_CODE;

            $json_array['message'] = 'Something went wrong, please try again';

            $json_array['redirect'] = ADMIN_SITE_URL . 'candidates';

        }

        echo_json($json_array);
    }

    public function save_interiam_report()
    {
        $this->load->model('candidates_model');

        $frm_details = $this->input->post();

        //  echo $massage   =   $this->report_html_form_final( encrypt($frm_details['candidate_id']));

        $candidate_details = $this->candidates_model->get_component_details(array('candidates_info.id' => $frm_details['candidate_id']));

        if (!empty($candidate_details)) {
            $data['candidate_details'] = array_map('ucwords', $candidate_details[0]);
            $data['component_details'] = $this->all_component($candidate_details[0], $frm_details['candidate_id']);
            $data['report_type'] = 'Interiam';

            $massage = $this->load->view('admin/report_html_view_final_report_download', $data, true);

        } else {
            $massage = '<p>Record not found</p>';
        }
        /*
        $file_extension = 'html';
        $my_file = 'interiam_report_'.DATE(UPLOAD_FILE_DATE_FORMAT);
        $my_file_name  = $my_file.'.'.$file_extension;

        $handle = fopen("uploads/candidate_report_file/".$my_file_name, 'w') or die('Cannot open file:  '.$my_file);

        fwrite($handle,$massage);*/

        $field_array = array('candsid' => $frm_details['candidate_id'],
            'created_on' => date(DB_DATE_FORMAT),
            'created_by' => $this->user_info['id'],
            'component_type' => "Interim Report",
            'ClientRefNumber' => $frm_details['ClientRefNumber'],
            //   'remarks' => $my_file_name

        );

        $field_array_candidate = array('final_qc' => "Interim QC Approve",
            'final_qc_updated_by' => $this->user_info['id'],
            'final_qc_approve_reject_timestamp' => date(DB_DATE_FORMAT),
        );

        $result_final_report = $this->candidates_model->save($field_array_candidate, array('candidates_info.id' => $frm_details['candidate_id']));
        $result_activity_log = $this->candidates_model->save_activity_log($field_array);

        if (!empty($result_final_report) && !empty($result_activity_log)) {

            $user_activity_data = $this->common_model->user_actity_data(array('component' => "Candidate",'ref_no' => $candidate_details[0]['cmp_ref_no'], 'candidate_name' => $candidate_details[0]['CandidateName'], 'created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id'], 'activity_type' => 'Manual', 'action' => 'Interim QC Approved'));

            $json_array['status'] = SUCCESS_CODE;

            $json_array['message'] = 'Candidate Updated Successfully';

            $json_array['redirect'] = ADMIN_SITE_URL . 'candidates';
        } else {
            $json_array['status'] = ERROR_CODE;

            $json_array['message'] = 'Something went wrong, please try again';

            $json_array['redirect'] = ADMIN_SITE_URL . 'candidates';

        }

        echo_json($json_array);
    }

    public function update_arrage_img()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {
            $frm_details = $this->input->post();

            if (!empty($frm_details['sortable_data'])) {
                $order = $frm_details['sortable_data'];

                $orderable = explode(",", $order);

                if (is_array($orderable) && !empty($orderable)) {
                    $counter = 1;
                    foreach ($orderable as $key => $orderablevalue) {

                        $orderable_item = explode("-", $orderablevalue);

                        $prevois_comp_value = '';
                        if (isset($prev_comp)) {
                            $prevois_comp_value = $prev_comp;
                        }
                        $prev_comp = $orderable_item[1];

                        if ($orderable_item[1] != $prevois_comp_value) {
                            $counter = 1;
                        }

                        if ($orderable_item[1] == 'employment') {

                            //$counter = ($key == 0) ? $key+1  : $key+1;
                            $prevois_value = '';
                            if (isset($prev)) {
                                $prevois_value = $prev;
                            }
                            $prev = $orderable_item[2];

                            if ($orderable_item[2] != $prevois_value) {
                                $counter = 1;
                            }
                            $update_employment[] = array('serialno' => $counter, 'id' => $orderable_item[3]);

                        }

                        if ($orderable_item[1] == 'address') {

                            //$counter = ($key == 0) ? $key+1  : $key+1;
                            $prevois_value = '';
                            if (isset($prev)) {
                                $prevois_value = $prev;
                            }
                            $prev = $orderable_item[2];

                            if ($orderable_item[2] != $prevois_value) {
                                $counter = 1;
                            }
                            $update_address[] = array('serialno' => $counter, 'id' => $orderable_item[3]);

                        }

                        if ($orderable_item[1] == 'education') {

                            //$counter = ($key == 0) ? $key+1  : $key+1;
                            $prevois_value = '';
                            if (isset($prev)) {
                                $prevois_value = $prev;
                            }
                            $prev = $orderable_item[2];

                            if ($orderable_item[2] != $prevois_value) {
                                $counter = 1;
                            }
                            $update_education[] = array('serialno' => $counter, 'id' => $orderable_item[3]);

                        }

                        if ($orderable_item[1] == 'reference') {
                            //$counter = ($key == 0) ? $key+1  : $key+1;
                            $prevois_value = '';
                            if (isset($prev)) {
                                $prevois_value = $prev;
                            }
                            $prev = $orderable_item[2];

                            if ($orderable_item[2] != $prevois_value) {
                                $counter = 1;
                            }
                            $update_reference[] = array('serialno' => $counter, 'id' => $orderable_item[3]);

                        }

                        if ($orderable_item[1] == 'court') {

                            //$counter = ($key == 0) ? $key+1  : $key+1;
                            $prevois_value = '';
                            if (isset($prev)) {
                                $prevois_value = $prev;
                            }
                            $prev = $orderable_item[2];

                            if ($orderable_item[2] != $prevois_value) {
                                $counter = 1;
                            }
                            $update_court[] = array('serialno' => $counter, 'id' => $orderable_item[3]);

                        }

                        if ($orderable_item[1] == 'global') {

                            //$counter = ($key == 0) ? $key+1  : $key+1;
                            $prevois_value = '';
                            if (isset($prev)) {
                                $prevois_value = $prev;
                            }
                            $prev = $orderable_item[2];

                            if ($orderable_item[2] != $prevois_value) {
                                $counter = 1;
                            }
                            $update_global[] = array('serialno' => $counter, 'id' => $orderable_item[3]);

                        }

                        if ($orderable_item[1] == 'drugs') {

                            //$counter = ($key == 0) ? $key+1  : $key+1;
                            $prevois_value = '';
                            if (isset($prev)) {
                                $prevois_value = $prev;
                            }
                            $prev = $orderable_item[2];

                            if ($orderable_item[2] != $prevois_value) {
                                $counter = 1;
                            }
                            $update_drugs[] = array('serialno' => $counter, 'id' => $orderable_item[3]);

                        }

                        if ($orderable_item[1] == 'pcc') {

                            //$counter = ($key == 0) ? $key+1  : $key+1;
                            $prevois_value = '';
                            if (isset($prev)) {
                                $prevois_value = $prev;
                            }
                            $prev = $orderable_item[2];

                            if ($orderable_item[2] != $prevois_value) {
                                $counter = 1;
                            }
                            $update_pcc[] = array('serialno' => $counter, 'id' => $orderable_item[3]);

                        }

                        if ($orderable_item[1] == 'identity') {

                            //$counter = ($key == 0) ? $key+1  : $key+1;
                            $prevois_value = '';
                            if (isset($prev)) {
                                $prevois_value = $prev;
                            }
                            $prev = $orderable_item[2];

                            if ($orderable_item[2] != $prevois_value) {
                                $counter = 1;
                            }
                            $update_identity[] = array('serialno' => $counter, 'id' => $orderable_item[3]);

                        }

                        if ($orderable_item[1] == 'credit_report') {

                            //$counter = ($key == 0) ? $key+1  : $key+1;
                            $prevois_value = '';
                            if (isset($prev)) {
                                $prevois_value = $prev;
                            }
                            $prev = $orderable_item[2];

                            if ($orderable_item[2] != $prevois_value) {
                                $counter = 1;
                            }
                            $update_credit_report[] = array('serialno' => $counter, 'id' => $orderable_item[3]);

                        }

                        $counter++;

                    }

                    if (!empty($update_employment)) {
                        $this->load->model('Employment_model');
                        $this->Employment_model->upload_file_update($update_employment);
                    }
                    if (!empty($update_address)) {
                        $this->load->model('Addressver_model');
                        $this->Addressver_model->upload_file_update($update_address);
                    }
                    if (!empty($update_education)) {
                        $this->load->model('education_model');
                        $this->education_model->upload_file_update($update_education);
                    }
                    if (!empty($update_reference)) {
                        $this->load->model('reference_verificatiion_model');
                        $this->reference_verificatiion_model->upload_file_update($update_reference);
                    }
                    if (!empty($update_court)) {
                        $this->load->model('court_verificatiion_model');
                        $this->court_verificatiion_model->upload_file_update($update_court);
                    }
                    if (!empty($update_global)) {
                        $this->load->model('global_database_model');
                        $this->global_database_model->upload_file_update($update_global);
                    }
                    if (!empty($update_pcc)) {
                        $this->load->model('pcc_verificatiion_model');
                        $this->pcc_verificatiion_model->upload_file_update($update_pcc);
                    }
                    if (!empty($update_drugs)) {
                        $this->load->model('drug_verificatiion_model');
                        $this->drug_verificatiion_model->upload_file_update($update_drugs);
                    }
                    if (!empty($update_identity)) {
                        $this->load->model('identity_model');
                        $this->identity_model->upload_file_update($update_identity);
                    }

                    if (!empty($update_credit_report)) {
                        $this->load->model('credit_report_model');
                        $this->credit_report_model->upload_file_update($update_credit_report);
                    }

                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'Ordered Successfully';

                } else {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Something went worong, please try again';
                }
            } else {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = 'Something went worong, please try again';
            }
            echo_json($json_array);
        }
    }

    protected function all_component($candidate_details, $com_id)
    {
        $this->load->model('first_qc_model');

        $return = array();

        /*$return['address'] = $this->first_qc_model->get_address_final_qc(array('addrverres.first_qc_approve'=>'First QC Approve','addrverres.var_filter_status'=>'closed','addrverres.candsid'=>$com_id));
        $return['employment'] = $this->first_qc_model->get_emp_final_qc(array('empverres.first_qc_approve'=>'First QC Approve','empverres.var_filter_status'=>'closed','empverres.candsid'=>$com_id));
        $return['education'] = $this->first_qc_model->get_education_final_qc(array('education_result.first_qc_approve'=>'First QC Approve','education_result.var_filter_status'=>'closed','education_result.candsid'=>$com_id));

        $return['reference'] = $this->first_qc_model->get_reference_final_qc(array('reference_result.first_qc_approve'=>'First QC Approve','reference_result.var_filter_status'=>'closed','reference_result.candsid'=>$com_id));

        $return['court'] = $this->first_qc_model->get_court_final_qc(array('courtver_result.first_qc_approve'=>'First QC Approve','courtver_result.var_filter_status'=>'closed','courtver_result.candsid'=>$com_id));

        $return['global_db'] = $this->first_qc_model->get_global_db_final_qc(array('glodbver_result.first_qc_approve'=>'First QC Approve','glodbver_result.var_filter_status'=>'closed','glodbver_result.candsid'=>$com_id));

        $return['drug'] = $this->first_qc_model->get_drug_db_final_qc(array('drug_narcotis_result.first_qc_approve'=>'First QC Approve','drug_narcotis_result.var_filter_status'=>'closed','drug_narcotis_result.candsid'=>$com_id));

        $return['pcc'] = $this->first_qc_model->get_pcc_final_qc(array('pcc_result.first_qc_approve'=>'First QC Approve','pcc_result.var_filter_status'=>'closed','pcc_result.candsid'=>$com_id));

        $return['identity'] = $this->first_qc_model->get_identity_final_qc(array('identity_result.first_qc_approve'=>'First QC Approve','identity_result.var_filter_status'=>'closed','identity_result.candsid'=>$com_id));

        $return['credit_report'] = $this->first_qc_model->get_credit_report_final_qc(array('credit_report_result.first_qc_approve'=>'First QC Approve','credit_report_result.var_filter_status'=>'closed','credit_report_result.candsid'=>$com_id));

         */
        $return['address'] = $this->first_qc_model->get_address_final_qc(array('addrverres.candsid' => $com_id));
        $return['employment'] = $this->first_qc_model->get_emp_final_qc(array('empverres.candsid' => $com_id));
        $return['education'] = $this->first_qc_model->get_education_final_qc(array('education_result.candsid' => $com_id));

        $return['reference'] = $this->first_qc_model->get_reference_final_qc(array('reference_result.candsid' => $com_id));

        $return['court'] = $this->first_qc_model->get_court_final_qc(array('courtver_result.candsid' => $com_id));

        $return['global_db'] = $this->first_qc_model->get_global_db_final_qc(array('glodbver_result.candsid' => $com_id));

        $return['drug'] = $this->first_qc_model->get_drug_db_final_qc(array('drug_narcotis_result.candsid' => $com_id));

        $return['pcc'] = $this->first_qc_model->get_pcc_final_qc(array('pcc_result.candsid' => $com_id));

        $return['identity'] = $this->first_qc_model->get_identity_final_qc(array('identity_result.candsid' => $com_id));

        $return['credit_report'] = $this->first_qc_model->get_credit_report_final_qc(array('credit_report_result.candsid' => $com_id));
       
        return $return;
    }

    public function approved_queue()
    {

        $data['header_title'] = "Final QC Verification";

        $data['filter_view_final_qc'] = $this->filter_view_final_qc();

        // $client_manager_id = $this->candidates_model->get_client_manager_id(array('id'=> $candidate_details['clientid']));

        //  $data['client_manager_email_id'] = $this->candidates_model->get_client_manager_email_id($client_manager_id[0]['clientmgr']);
        $this->load->model('candidates_model');
        $reportingmanager_user = $this->candidates_model->get_reporting_manager_id();

        $reportingmanager_id = $reportingmanager_user[0]['reporting_manager'];

        $reportingmanager = $this->candidates_model->get_reporting_manager_email_id($reportingmanager_id);

        $data['reporting_manager_email'] = $reportingmanager[0];

        $this->load->view('admin/header', $data);

        $this->load->view('admin/final_qu_approve_view');

        $this->load->view('admin/footer');

    }

    public function send_report_mail($report_type, $id)
    {

        if ($this->input->is_ajax_request()) {

            $this->load->model('candidates_model');
            $candidate_id = explode(',', $id);
            $details = '';
            foreach ($candidate_id as $key => $value) {
             
                $details[] = $this->candidates_model->select_candidate(array('candidates_info.id' => $value));

            }

            $details = array_map('current', $details);

            $view_data['email_info'] = $details;

            $this->load->model('employment_model');

            $reportingmanager_user = $this->employment_model->get_reporting_manager_id();

            $view_data['user_profile_info'] = $reportingmanager_user[0];

            $client_manager_id = $this->candidates_model->get_client_manager_id(array('id' => $details[0]['clientid']));

            $client_manager_email_id = $this->candidates_model->get_client_manager_email_id($client_manager_id[0]['clientmgr']);

            $reportingmanager_user = $this->candidates_model->get_reporting_manager_id();

            $reportingmanager_id = $reportingmanager_user[0]['reporting_manager'];

            $reportingmanager = $this->candidates_model->get_reporting_manager_email_id($reportingmanager_id);
            $view_data['client_name'] = $details[0]['clientname'];


            $view_data['email_info'] = $details;

            ?>


             <input type="hidden" name="candidate_user_id" value="<?php echo set_value('candidate_user_id', $id); ?>">
           <div class="form-row mb-3">
                <label for="to" class="col-2 col-sm-1 col-form-label">Subject:</label>
                <div class="col-10 col-sm-11">
                    <input type="text" class="form-control" id="subject" name="subject" placeholder="Type email" value="<?php echo $details[0]['clientname'] . ' - Report/s - ' . date('d-M-Y') ?>" readonly>
                </div>
            </div>
           <?php
               $client_manager_email = array_map('current', $client_manager_email_id);
            ?>
            <div class="form-row mb-3">
                <label for="to" class="col-2 col-sm-1 col-form-label">To:</label>
                <div class="col-10 col-sm-11">
                    <input type="text" class="form-control" id="to_email" name="to_email" placeholder="Insert Multiple Email Id using comma separated"  value="<?php echo implode(' , ', $client_manager_email); ?>">
                </div>
            </div>
            <div class="form-row mb-3">
                <label for="cc" class="col-2 col-sm-1 col-form-label">CC:</label>
                <div class="col-10 col-sm-11">
                    <input type="text" class="form-control" id="cc_email" name="cc_email" placeholder="Insert Multiple Email Id using comma separated" value="<?php echo $reportingmanager[0]['email'] . " , " . $this->user_info['email']; ?>" readonly>
                </div>
            </div>

            <div class="clearfix"></div>

          <?php if (!empty($details)) {

                echo $this->load->view(EMAIL_VIEW_FOLDER_NAME . 'candidates_report', $view_data, true);

            }
        }
    }

  /*  public function report_send_mail()
    {

        $json_array = array();

        log_message('error', 'Candidate Send mail Final Approve');
        try {    

        if ($this->input->is_ajax_request()) {
           
            $candidate_id = $this->input->post('candidate_user_id');

            $case_status = $this->input->post('case_status');

            $candidate_id = explode(',', $candidate_id);
                
            $this->load->library('email');

            $this->load->model('candidates_model');

            log_message('error', 'Candidate Send mail Final Approve');
            try {    

                foreach ($candidate_id as $key => $value) {
                   
                    $details = $this->candidates_model->select_candidate(array('candidates_info.id' => $value));

                    if($case_status  == '1')
                    {
                     
                        $create_file =  $this->report_generate($value,'final_report');
                      
                        $clients_details = $this->candidates_model->get_entitypackages(array('tbl_clients_id'=>$details[0]['clientid'],'entity'=>$details[0]['entity'],'package'=>$details[0]['package']));
                      

                        $component_id = explode(",", $clients_details[0]['component_id']);
                        
                        $data_arry = array();
                        if (in_array('addrver', $component_id)) {
                          
                            $result = $this->candidates_model->get_addres_ver_status_for_export(array('addrver.candsid' => $details[0]['id']));
                         

                            if (!empty($result)) {
                                $counter = 1;
                                foreach ($result as $key => $value) {  

                                    $data_arry["address"][] = $value['report_status'];
                                    $counter++;
                                }
                                   
                            }
                        }


                        if (in_array('eduver', $component_id)) {
                            $result = $this->candidates_model->get_education_ver_status_for_export(array('education.candsid' =>  $details[0]['id']));

                            if (!empty($result)) {

                                $counter = 1;
                                foreach ($result as $key => $value) {
                                
                                    $data_arry["education"][] = $value['report_status'];         
                           
                                    $counter++;
                                }
                              
                            }
                        }

                        if (in_array('empver', $component_id)) {
                            $result = $this->candidates_model->get_employment_ver_status_for_export(array('empver.candsid' => $details[0]['id']));

                          
                            if (!empty($result)) {
                                $counter = 1;
                                foreach ($result as $key => $value) {
                                    
                                   $data_arry["employment"][] = $value['report_status'];  
                                   
                                    $counter++;
                                }

                            }
                        }
             
                        if (in_array('refver', $component_id)) {
                            $result = $this->candidates_model->get_refver_ver_status_for_export(array('reference.candsid' => $details[0]['id']));

                            if (!empty($result)) {
                                $counter = 1;
                                foreach ($result as $key => $value) {
                                    
                                    $data_arry["reference"][] = $value['report_status']; 
                                    
                                    $counter++;
                                }
                              
                            }
                        }

                        if (in_array('courtver', $component_id)) {
                            $result = $this->candidates_model->get_court_ver_status_for_export(array('courtver.candsid' => $details[0]['id']));

                            if (!empty($result)) {
                                $counter = 1;
                                foreach ($result as $key => $value) {

                                    $data_arry["court"][] = $value['report_status']; 

                                    $counter++;
                                }
                               
                            }
                        }

                        if (in_array('crimver', $component_id)) {
                            $result = $this->candidates_model->get_pcc_ver_status_for_export(array('pcc.candsid' => $details[0]['id']));

                            
                            if (!empty($result)) {

                                $counter = 1;
                                foreach ($result as $key => $value) {
                                    
                                    $data_arry["pcc"][] = $value['report_status']; 
                                
                                    $counter++;
                                }
                                
                            }
                        }

                        if (in_array('globdbver', $component_id)) {
                            $result = $this->candidates_model->get_globdbver_ver_status_for_export(array('glodbver.candsid' => $details[0]['id']));
                            
                            if (!empty($result)) {

                                $counter = 1;
                                foreach ($result as $key => $value) {

                                    $data_arry["global"][] = $value['report_status'];

                                    $counter++;
                                }
                               
                            }
                        }

                        if (in_array('identity', $component_id)) {
                            $result = $this->candidates_model->get_identity_ver_status_for_export(array('identity.candsid' => $details[0]['id']));

                            if (!empty($result)) {

                                $counter = 1;
                                foreach ($result as $key => $value) {

                                    $data_arry["identity"][] = $value['report_status'];
                                   
                                    $counter++;
                                }
                               
                            }
                        }

                        if (in_array('cbrver', $component_id)) {
                            $result = $this->candidates_model->get_credit_report_ver_status_for_export(array('credit_report.candsid' => $details[0]['id']));


                            if (!empty($result)) {

                                $counter = 1;
                                foreach ($result as $key => $value) {

                                    $data_arry["credit_report"][] = $value['report_status'];
                                    $counter++;
                                }

                            }
                        }

                       
                        $this->load->model('employment_model');

                        $reportingmanager_user = $this->employment_model->get_reporting_manager_id();

                        $email_tmpl_data['user_profile_info'] = $reportingmanager_user[0];

                        $client_details_id = $this->candidates_model->select_client_details(array('tbl_clients_id'=>$details[0]['clientid'],'entity'=>$details[0]['entity'],'package'=>$details[0]['package']));
          
                        $client_actual_id = array();
                        foreach ($client_details_id as $key => $value) {
                          $client_actual_id[] = $value['id'];  
                        }
                        
                        $spoc_email_id = $this->candidates_model->select_spoc_mail_id($client_actual_id);
                       
                        $spoc_email = array();
                        $spoc_cc = array();
                        foreach ($spoc_email_id as $key => $value) {
                          $spoc_email[] = $value['spoc_email'];  
                          $spoc_cc[] = $value['spoc_manager_email'];  
                        }   

                        $spoc_email = array_unique($spoc_email);
                        $spoc_cc = array_unique($spoc_cc);

                        $client_details_id = $this->candidates_model->get_client_manager_id(array('id'=>$details[0]['clientid']));

                        $client_details_email_id = $this->candidates_model->get_reporting_manager_email_id($client_details_id[0]['clientmgr']);
                  
                        $email_tmpl_data['to_emails'] = implode(',',$spoc_email);
                                
                        $carc_mail =  implode(',',$spoc_cc).','.REPORTEMAIL.','.MAINEMAIL.','.$client_details_email_id[0]['email'];
                                
                        $carc_mails = explode(',', $carc_mail); 
                          
                        $carboncopy_mails = array_unique($carc_mails);

                        $carboncopy_mail = implode(',',$carboncopy_mails);

                        $email_tmpl_data['cc_emails'] = $carboncopy_mail;


                        if($details[0]['clientid'] == '3' || $details[0]['clientid'] == '4' || $details[0]['clientid'] == '5')
                        {

                            if(empty($details[0]['final_qc_send_mail_timestamp']))
                            {
                                if(isset($data_arry['address']))
                                {
                                    $email_tmpl_data['subject'] = 'Report of '.ucwords($details[0]['CandidateName']) . ' | Post BGV Report'; 
                                }
                                else
                                {
                                    $email_tmpl_data['subject'] = 'Report of '.ucwords($details[0]['CandidateName']) . ' | Pre BGV Report';    
                                }

                            }
                            else
                            {  
                                if(isset($data_arry['address']))
                                {

                                    $email_tmpl_data['subject'] = 'REVISED Report of '.ucwords($details[0]['CandidateName']) . ' | Post BGV Report';
                                } 
                                else
                                {

                                    $email_tmpl_data['subject'] = 'REVISED Report of '.ucwords($details[0]['CandidateName']) . ' | Pre BGV Report';
                                }
                            }

                        }
                        elseif ($details[0]['clientid'] == '33') {
                            
                            if(empty($details[0]['final_qc_send_mail_timestamp']))
                            {
                                if(isset($data_arry['employment']))
                                {
                                    $email_tmpl_data['subject'] = 'Report of '.ucwords($details[0]['CandidateName']) . ' | Post BGV Report'; 
                                }
                                else
                                {
                                    $email_tmpl_data['subject'] = 'Report of '.ucwords($details[0]['CandidateName']) . ' | Pre BGV Report';    
                                }

                            }
                            else
                            {  
                                if(isset($data_arry['employment']))
                                {

                                    $email_tmpl_data['subject'] = 'REVISED Report of '.ucwords($details[0]['CandidateName']) . ' | Post BGV Report';
                                } 
                                else
                                {

                                    $email_tmpl_data['subject'] = 'REVISED Report of '.ucwords($details[0]['CandidateName']) . ' | Pre BGV Report';
                                }
                            } 
                        }
                        else
                        {
                            if(empty($details[0]['final_qc_send_mail_timestamp']))
                            {
                       
                                $email_tmpl_data['subject'] = 'Report of '.ucwords($details[0]['CandidateName']) . ' | Case Received date - ' . date('d-M-Y', strtotime($details[0]['caserecddate']));
                            }
                            else
                            {
                                $email_tmpl_data['subject'] = 'REVISED Report of '.ucwords($details[0]['CandidateName']) . ' | Case Received date - ' . date('d-M-Y', strtotime($details[0]['caserecddate']));
                            }
                        }

                        $email_tmpl_data['from_email'] = REPORTEMAIL;

                        $email_tmpl_data['detail_info'] = $details;

                        $email_tmpl_data['component_details'] = $data_arry;

                        $attachemnt = ucwords($details[0]['ClientRefNumber']).'_'.ucwords($details[0]['CandidateName']).'_Report'.'.pdf';

                        $email_tmpl_data['attachments'] = $attachemnt;
                    
                        $result = $this->email->candidate_report_mail_send($email_tmpl_data);

                        $this->email->clear(true);

                        if ($result) {
                     
                            $update_activity_log_candidate = $this->candidates_model->save(array('final_qc_send_mail' => 1,'final_qc_send_mail_timestamp' => date(DB_DATE_FORMAT)), array('id' => $details[0]['id']));

                            if(file_exists(SITE_BASE_PATH . CANDIDATES . $attachemnt)){
                                    unlink(SITE_BASE_PATH . CANDIDATES . $attachemnt);
                            }
                        }
                    }
                    if($case_status == '2')
                    { 

                          $update_activity_log_candidate = $this->candidates_model->save(array('final_qc_send_mail' => 2), array('id' => $details[0]['id']));

                    }
                }

           } catch (Exception $e) {
            log_message('error', 'Final QC::report_send_mail');
            log_message('error', $e->getMessage());
           }    

                 
            $json_array['message'] = 'Email Send Successfully';

            $json_array['status'] = SUCCESS_CODE;
           
            
        } else {
            $json_array['message'] = 'Something went wrong, please try again';

            $json_array['status'] = ERROR_CODE;
        }

    } catch (Exception $e) {
        log_message('error', 'Final QC::report_send_mail');
        log_message('error', $e->getMessage());
    } 
        echo_json($json_array);
    }*/

    public function report_send_mail()
    {
     
        $json_array = array();

        log_message('error', 'Candidate Send mail Final Approve');
        try {    

            if ($this->input->is_ajax_request()) {

                 
                if ($this->permission['access_final_aq_status'] == 1) {

                    $this->load->model('candidates_model');
              
                    $candidate_id = $this->input->post('candidate_user_id');

                    $case_status = $this->input->post('case_status');

                    $user_id = $this->user_info['id'];

                    $candidate_ids = explode(',', $candidate_id);
                
                    foreach ($candidate_ids as $key => $value) {  
                    $candidate_pending = $this->candidates_model->save(array('final_qc_send_mail' => 4), array('id' => $value));
                    }

                    $url = "cli_request_only final_qc_send_report_mail $candidate_id $case_status $user_id";
               
        
                    $cmd = 'php /var/www/html/'.SITE_FOLDER.'index.php ' . $url;

                    if (substr(php_uname(), 0, 7) == "Windows") {
                        pclose(popen("start /MIN " . $cmd, "r"));
                    } else {
                        exec($cmd . " > /dev/null &");
                    }

                    $json_array['message'] = 'Email Send Successfully'; 

                    $json_array['status'] = SUCCESS_CODE; 

                }else{

                    $json_array['message'] = 'Sorry, You do not  have permission.';

                    $json_array['status'] = ERROR_CODE;
                }

            }
            else
            {
               $json_array['message'] = 'Something went wrong, please try again';

               $json_array['status'] = ERROR_CODE;
            }

        } catch (Exception $e) {
            log_message('error', 'Final QC::report_send_mail');
            log_message('error', $e->getMessage());
        } 
        echo_json($json_array);
    }
    
    

 /*   public function report_send_mail()
    {

        $json_array = array();

        if ($this->input->is_ajax_request()) {
            if ($this->input->post('to_email') != REFNO) {
                $candidate_id = $this->input->post('candidate_user_id');

                $candidate_id = explode(',', $candidate_id);

                $report_type = $this->input->post('report_type');

                $to_emails = $this->input->post('to_email');

                $from_email = $this->input->post('from');

                $cc_email = $this->input->post('cc_email');

                $subject = $this->input->post('subject');


                $this->load->library('email');

                $this->load->model('candidates_model');

                $details = '';

                foreach ($candidate_id as $key => $value) {
                     
                    $create_file =  $this->report_generate($value,'final_report');
                    
                    $details[] = $this->candidates_model->select_candidate(array('candidates_info.id' => $value));

                }

                $details = array_map('current', $details);

                $this->load->model('employment_model');
                $reportingmanager_user = $this->employment_model->get_reporting_manager_id();

                $email_tmpl_data['user_profile_info'] = $reportingmanager_user[0];

                $email_tmpl_data['to_emails'] = $to_emails;
                $email_tmpl_data['cc_emails'] = $cc_email;
               
                $email_tmpl_data['subject'] = $subject;
          
                $email_tmpl_data['from_email'] = ($from_email != "") ? $from_email : FROMEMAIL;

                $email_tmpl_data['detail_info'] = $details;

                $email_tmpl_data['attachments'] = $DelFilePath;

                $result = $this->email->candidate_report_mail_send($email_tmpl_data);

                if ($result) {

                    foreach ($candidate_id as $key => $value) {

                        $update_activity_log_candidate = $this->candidates_model->save(array('final_qc_send_mail' => 1), array('id' => $value));

                    }
                 
                    $json_array['message'] = 'Email Send Successfully';

                    $json_array['status'] = SUCCESS_CODE;
                } else {
                    $json_array['message'] = 'Email not sent, please try again';

                    $json_array['status'] = ERROR_CODE;
                }
            } else {
                $json_array['message'] = 'Please Use Different Email ID';

                $json_array['status'] = ERROR_CODE;
            }
        } else {
            $json_array['message'] = 'Something went wrong, please try again';

            $json_array['status'] = ERROR_CODE;
        }
        echo_json($json_array);
    }*/

    public function report_generate($candsid = null, $report_type)
    {
        if (!empty($candsid)) {

            $this->load->model('candidates_model');

            $id = decrypt($candsid);

            $this->load->model('first_qc_model');

            $this->load->library('example_zip');

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

                   /* $attachment = explode('||',$result[0]['add_attachments']);

                    foreach ($attachment as $key => $value) {
                        $value_attachment =  explode('/',$value);
                        $value_attachments =   $value_attachment[2];
                        $source_file_upload_path = SITE_BASE_PATH . ADDRESS . $value_attachment[1] .'/'.$value_attachment[2];

                        $destination_file_upload_path = SITE_BASE_PATH . ADDRESS_TEMP;
                        if (!folder_exist($destination_file_upload_path)) {
                            mkdir($destination_file_upload_path, 0777);
                        } else if (!is_writable($destination_file_upload_path)) {
                            array_push($error_msgs, 'Problem while uploading');
                        }

                        $this->convert_jpg_to_png($source_file_upload_path,$destination_file_upload_path);
                    }*/

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
                    if(!defined('CLIENT_LOGOS')) {
                        define('CLIENT_LOGOS', $cleit_logo_path);
                    }
                } else {
                    if(!defined('CLIENT_LOGOS')) {
                        define('CLIENT_LOGOS', '');
                    }
                }


                define('CUSTOM_CLINT_ID',$cands_result['clientid']);


                $this->example_zip->generate_pdf($report, 'admin');

               /* $result = $this->first_qc_model->get_address_final_qc(array('addrverres.candsid' => $id));

                if (!empty($result)) {

                    $attachment = explode('||',$result[0]['add_attachments']);
                    foreach ($attachment as $key => $value) {
                        $value_attachment =  explode('/',$value);

                        unlink($destination_file_upload_path.$value_attachment[2]);
                    }  
                } */

            } else {
                show_404();
            }
        } else {
            redirect('admin/candidates');
        }
       
    }

  /*  public function download_annexure_report()
    {
        $this->load->library('zip');

        set_time_limit(0);

        ini_set('memory_limit', '-1');

        $this->load->model('candidates_model');

        $candidate_id = $this->input->post('candidate_user_id');

        $candidate_id = explode(',', $candidate_id);

        foreach ($candidate_id as $key => $value) {

            $details = $this->candidates_model->select_candidate(array('candidates_info.id' => $value));

            $clients_details = $this->candidates_model->get_entitypackages(array('tbl_clients_id'=>$details[0]['clientid'],'entity'=>$details[0]['entity'],'package'=>$details[0]['package']));

            $component_id = explode(",", $clients_details[0]['component_id']);*/

           /* if (in_array('addrver', $component_id)) {
                          
                $result = $this->candidates_model->get_address_attachment_details(array('addrver.candsid' => $details[0]['id']));

                if (!empty($result)) {
                    
                    foreach ($result as $key => $attachment_result) {  

                       $attachment = $attachment_result['add_attachments'];
        
                        $attachment_explode = explode('||',$attachment);
                        $counter = 1;
                        foreach ($attachment_explode as  $value) {
                            $file_info = pathinfo($value);
                            $extension = $file_info['extension'];

                            $file_upload_path = SITE_BASE_PATH . ADDRESS . $attachment_result['clientid'].'/'.$value;
                            
                            $this->zip->read_file(  SITE_BASE_PATH . ADDRESS . $attachment_result['clientid'].'/'.$value,$attachment_result['add_com_ref']."_".$counter. '.' . $extension);

                            $counter++;

                        }

                        $vendor_attachments = $attachment_result['vendor_attachments'];
        
                        $attachment_explode_vendor = explode('||',$vendor_attachments);
                        $counter = 101;
                        foreach ($attachment_explode_vendor as  $value) {
                            $file_info = pathinfo($value);
                            $extension = $file_info['extension'];

                            $file_upload_path = SITE_BASE_PATH . ADDRESS . "vendor_file".'/'.$value;
                            
                            $this->zip->read_file(  SITE_BASE_PATH . ADDRESS . "vendor_file".'/'.$value,$attachment_result['add_com_ref']."_".$counter. '.' . $extension);

                            $counter++;

                        }
                    }
                                   
                }
            }

            if (in_array('empver', $component_id)) {
                          
                $result = $this->candidates_model->get_employment_attachment_details(array('empver.candsid' => $details[0]['id']));
                

                if (!empty($result)) {
                    
                    foreach ($result as $key => $attachment_result) {  

                       $attachment = $attachment_result['add_attachments'];
        
                        $attachment_explode = explode('||',$attachment);
                        $counter = 1;
                        foreach ($attachment_explode as  $value) {
                            $file_info = pathinfo($value);
                            $extension = $file_info['extension'];

                            $file_upload_path = SITE_BASE_PATH . EMPLOYMENT . $attachment_result['clientid'].'/'.$value;
                            
                            $this->zip->read_file(  SITE_BASE_PATH . EMPLOYMENT . $attachment_result['clientid'].'/'.$value,$attachment_result['emp_com_ref']."_".$counter. '.' . $extension);

                            $counter++;

                        }

                        $vendor_attachments = $attachment_result['vendor_attachments'];
        
                        $attachment_explode_vendor = explode('||',$vendor_attachments);
                        $counter = 101;
                        foreach ($attachment_explode_vendor as  $value) {
                            $file_info = pathinfo($value);
                            $extension = $file_info['extension'];

                            $file_upload_path = SITE_BASE_PATH . EMPLOYMENT . "vendor_file".'/'.$value;
                            
                            $this->zip->read_file(  SITE_BASE_PATH . EMPLOYMENT . "vendor_file".'/'.$value,$attachment_result['emp_com_ref']."_".$counter. '.' . $extension);

                            $counter++;

                        }
                    }                 
                }
            }

            if (in_array('eduver', $component_id)) {
                          
                $result = $this->candidates_model->get_education_attachment_details(array('education.candsid' => $details[0]['id']));
                

                if (!empty($result)) {
                    
                    foreach ($result as $key => $attachment_result) {  

                       $attachment = $attachment_result['add_attachments'];
        
                        $attachment_explode = explode('||',$attachment);
                        $counter = 1;
                        foreach ($attachment_explode as  $value) {
                            $file_info = pathinfo($value);
                            $extension = $file_info['extension'];

                            $file_upload_path = SITE_BASE_PATH . EDUCATION . $attachment_result['clientid'].'/'.$value;
                            
                            $this->zip->read_file(  SITE_BASE_PATH . EDUCATION . $attachment_result['clientid'].'/'.$value,$attachment_result['education_com_ref']."_".$counter. '.' . $extension);

                            $counter++;

                        }

                        $vendor_attachments = $attachment_result['vendor_attachments'];
        
                        $attachment_explode_vendor = explode('||',$vendor_attachments);
                        $counter = 101;
                        foreach ($attachment_explode_vendor as  $value) {
                            $file_info = pathinfo($value);
                            $extension = $file_info['extension'];

                            $file_upload_path = SITE_BASE_PATH . EDUCATION . "vendor_file".'/'.$value;
                            
                            $this->zip->read_file(  SITE_BASE_PATH . EDUCATION . "vendor_file".'/'.$value,$attachment_result['education_com_ref']."_".$counter. '.' . $extension);

                            $counter++;

                        }
                    }                 
                }
            }

            if (in_array('refver', $component_id)) {
                          
                $result = $this->candidates_model->get_reference_attachment_details(array('reference.candsid' => $details[0]['id']));
                

                if (!empty($result)) {
                    
                    foreach ($result as $key => $attachment_result) {  

                       $attachment = $attachment_result['add_attachments'];
        
                        $attachment_explode = explode('||',$attachment);
                        $counter = 1;
                        foreach ($attachment_explode as  $value) {
                            $file_info = pathinfo($value);
                            $extension = $file_info['extension'];

                            $file_upload_path = SITE_BASE_PATH . REFERENCES . $attachment_result['clientid'].'/'.$value;
                            
                            $this->zip->read_file(  SITE_BASE_PATH . REFERENCES . $attachment_result['clientid'].'/'.$value,$attachment_result['reference_com_ref']."_".$counter. '.' . $extension);

                            $counter++;

                        }

                    }                 
                }
            }
               */

         /*   if (in_array('courtver', $component_id)) {
                          
                $result = $this->candidates_model->get_court_attachment_details(array('courtver.candsid' => $details[0]['id']));
                

                if (!empty($result)) {
                    
                    foreach ($result as $key => $attachment_result) {  

                       $attachment = $attachment_result['add_attachments'];
        
                        $attachment_explode = explode('||',$attachment);
                        $counter = 1;
                        foreach ($attachment_explode as  $value) {
                            $file_info = pathinfo($value);
                            $extension = $file_info['extension'];

                            $file_upload_path = SITE_BASE_PATH . COURT_VERIFICATION . $attachment_result['clientid'].'/'.$value;
                            
                            $this->zip->read_file(  SITE_BASE_PATH . COURT_VERIFICATION . $attachment_result['clientid'].'/'.$value,$attachment_result['court_com_ref']."_".$counter. '.' . $extension);

                            $counter++;

                        }

                        $vendor_attachments = $attachment_result['vendor_attachments'];
        
                        $attachment_explode_vendor = explode('||',$vendor_attachments);
                        $counter = 101;
                        foreach ($attachment_explode_vendor as  $value) {
                            $file_info = pathinfo($value);
                            $extension = $file_info['extension'];

                            $file_upload_path = SITE_BASE_PATH . COURT_VERIFICATION . "vendor_file".'/'.$value;
                            
                            $this->zip->read_file(  SITE_BASE_PATH . COURT_VERIFICATION . "vendor_file".'/'.$value,$attachment_result['court_com_ref']."_".$counter. '.' . $extension);

                            $counter++;

                        }

                    }                 
                }
            }*/

           /* if (in_array('globdbver', $component_id)) {
                          
                $result = $this->candidates_model->get_global_attachment_details(array('glodbver.candsid' => $details[0]['id']));
                

                if (!empty($result)) {
                    
                    foreach ($result as $key => $attachment_result) {  

                       $attachment = $attachment_result['add_attachments'];
        
                        $attachment_explode = explode('||',$attachment);
                        $counter = 1;
                        foreach ($attachment_explode as  $value) {
                            $file_info = pathinfo($value);
                            $extension = $file_info['extension'];

                            $file_upload_path = SITE_BASE_PATH . GLOBAL_DB . $attachment_result['clientid'].'/'.$value;
                            
                            $this->zip->read_file(  SITE_BASE_PATH . GLOBAL_DB . $attachment_result['clientid'].'/'.$value,$attachment_result['global_com_ref']."_".$counter. '.' . $extension);

                            $counter++;

                        }

                        $vendor_attachments = $attachment_result['vendor_attachments'];
        
                        $attachment_explode_vendor = explode('||',$vendor_attachments);
                        $counter = 101;
                        foreach ($attachment_explode_vendor as  $value) {
                            $file_info = pathinfo($value);
                            $extension = $file_info['extension'];

                            $file_upload_path = SITE_BASE_PATH . GLOBAL_DB . "vendor_file".'/'.$value;
                            
                            $this->zip->read_file(  SITE_BASE_PATH . GLOBAL_DB . "vendor_file".'/'.$value,$attachment_result['global_com_ref']."_".$counter. '.' . $extension);

                            $counter++;

                        }

                    }                 
                }
            }

            if (in_array('identity', $component_id)) {
                          
                $result = $this->candidates_model->get_identity_attachment_details(array('identity.candsid' => $details[0]['id']));
                

                if (!empty($result)) {
                    
                    foreach ($result as $key => $attachment_result) {  

                       $attachment = $attachment_result['add_attachments'];
        
                        $attachment_explode = explode('||',$attachment);
                        $counter = 1;
                        foreach ($attachment_explode as  $value) {
                            $file_info = pathinfo($value);
                            $extension = $file_info['extension'];

                            $file_upload_path = SITE_BASE_PATH . IDENTITY . $attachment_result['clientid'].'/'.$value;
                            
                            $this->zip->read_file(  SITE_BASE_PATH . IDENTITY . $attachment_result['clientid'].'/'.$value,$attachment_result['identity_com_ref']."_".$counter. '.' . $extension);

                            $counter++;

                        }

                        $vendor_attachments = $attachment_result['vendor_attachments'];
        
                        $attachment_explode_vendor = explode('||',$vendor_attachments);
                        $counter = 101;
                        foreach ($attachment_explode_vendor as  $value) {
                            $file_info = pathinfo($value);
                            $extension = $file_info['extension'];

                            $file_upload_path = SITE_BASE_PATH . IDENTITY . "vendor_file".'/'.$value;
                            
                            $this->zip->read_file(  SITE_BASE_PATH . IDENTITY . "vendor_file".'/'.$value,$attachment_result['identity_com_ref']."_".$counter. '.' . $extension);

                            $counter++;

                        }

                    }                 
                }
            }


            if (in_array('crimver', $component_id)) {
                          
                $result = $this->candidates_model->get_pcc_attachment_details(array('pcc.candsid' => $details[0]['id']));
                

                if (!empty($result)) {
                    
                    foreach ($result as $key => $attachment_result) {  

                       $attachment = $attachment_result['add_attachments'];
        
                        $attachment_explode = explode('||',$attachment);
                        $counter = 1;
                        foreach ($attachment_explode as  $value) {
                            $file_info = pathinfo($value);
                            $extension = $file_info['extension'];

                            $file_upload_path = SITE_BASE_PATH . PCC . $attachment_result['clientid'].'/'.$value;
                            
                            $this->zip->read_file(  SITE_BASE_PATH . PCC . $attachment_result['clientid'].'/'.$value,$attachment_result['pcc_com_ref']."_".$counter. '.' . $extension);

                            $counter++;

                        }

                        $vendor_attachments = $attachment_result['vendor_attachments'];
        
                        $attachment_explode_vendor = explode('||',$vendor_attachments);
                        $counter = 101;
                        foreach ($attachment_explode_vendor as  $value) {
                            $file_info = pathinfo($value);
                            $extension = $file_info['extension'];

                            $file_upload_path = SITE_BASE_PATH . PCC . "vendor_file".'/'.$value;
                            
                            $this->zip->read_file(  SITE_BASE_PATH . PCC . "vendor_file".'/'.$value,$attachment_result['pcc_com_ref']."_".$counter. '.' . $extension);

                            $counter++;

                        }

                    }                 
                }
            }

            if (in_array('cbrver', $component_id)) {
                          
                $result = $this->candidates_model->get_credit_report_attachment_details(array('credit_report.candsid' => $details[0]['id']));
                

                if (!empty($result)) {
                    
                    foreach ($result as $key => $attachment_result) {  

                       $attachment = $attachment_result['add_attachments'];
        
                        $attachment_explode = explode('||',$attachment);
                        $counter = 1;
                        foreach ($attachment_explode as  $value) {
                            $file_info = pathinfo($value);
                            $extension = $file_info['extension'];

                            $file_upload_path = SITE_BASE_PATH . CREDIT_REPORT . $attachment_result['clientid'].'/'.$value;
                            
                            $this->zip->read_file(  SITE_BASE_PATH . CREDIT_REPORT . $attachment_result['clientid'].'/'.$value,$attachment_result['credit_report_com_ref']."_".$counter. '.' . $extension);

                            $counter++;

                        }

                        $vendor_attachments = $attachment_result['vendor_attachments'];
        
                        $attachment_explode_vendor = explode('||',$vendor_attachments);
                        $counter = 101;
                        foreach ($attachment_explode_vendor as  $value) {
                            $file_info = pathinfo($value);
                            $extension = $file_info['extension'];

                            $file_upload_path = SITE_BASE_PATH . CREDIT_REPORT . "vendor_file".'/'.$value;
                            
                            $this->zip->read_file(  SITE_BASE_PATH . CREDIT_REPORT . "vendor_file".'/'.$value,$attachment_result['credit_report_com_ref']."_".$counter. '.' . $extension);

                            $counter++;

                        }

                    }                 
                }
            }

            if (in_array('narcver', $component_id)) {
                          
                $result = $this->candidates_model->get_drugs_attachment_details(array('drug_narcotis.candsid' => $details[0]['id']));
                

                if (!empty($result)) {
                    
                    foreach ($result as $key => $attachment_result) {  

                       $attachment = $attachment_result['add_attachments'];
        
                        $attachment_explode = explode('||',$attachment);
                        $counter = 1;
                        foreach ($attachment_explode as  $value) {
                            $file_info = pathinfo($value);
                            $extension = $file_info['extension'];

                            $file_upload_path = SITE_BASE_PATH . DRUGS . $attachment_result['clientid'].'/'.$value;
                            
                            $this->zip->read_file(  SITE_BASE_PATH . DRUGS . $attachment_result['clientid'].'/'.$value,$attachment_result['drug_com_ref']."_".$counter. '.' . $extension);

                            $counter++;

                        }

                        $vendor_attachments = $attachment_result['vendor_attachments'];
        
                        $attachment_explode_vendor = explode('||',$vendor_attachments);
                        $counter = 101;
                        foreach ($attachment_explode_vendor as  $value) {
                            $file_info = pathinfo($value);
                            $extension = $file_info['extension'];

                            $file_upload_path = SITE_BASE_PATH . DRUGS . "vendor_file".'/'.$value;
                            
                            $this->zip->read_file(  SITE_BASE_PATH . DRUGS . "vendor_file".'/'.$value,$attachment_result['drug_com_ref']."_".$counter. '.' . $extension);

                            $counter++;

                        }

                    }                 
                }
            }*/
      /*  }

        $result_candidate = $this->candidates_model->select_candidate_for_export($candidate_id);

        require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
            // Create new Spreadsheet object
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

            // Set document properties
            $spreadsheet->getProperties()->setCreator(CRMNAME)
                ->setLastModifiedBy(CRMNAME)
                ->setTitle(CRMNAME)
                ->setSubject('Candidate Records')
                ->setDescription('Candidate Records');

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
            foreach(range('A','G') as $columnID) {
              $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                  ->setWidth(20);
            }

            // set the names of header cells
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A1",'Component Ref No')
                ->setCellValue("B1",'Client Ref No')
                ->setCellValue("C1",'Comp Received date')
                ->setCellValue("D1",'Candidate Name')
                ->setCellValue("E1",'Vendor Status')
                ->setCellValue("F1",'Vendor Assigned on')
                ->setCellValue("G1",'Closure Date');
            // Add some data
            $x= 2;
            foreach($result_candidate as $result_candidates){
            

                $spreadsheet->setActiveSheetIndex(0)
                  
                  ->setCellValue("A$x",$result_candidates['court_com_ref'])
                  ->setCellValue("B$x",ucwords($result_candidates['ClientRefNumber']))
                  ->setCellValue("C$x",convert_db_to_display_date($result_candidates['iniated_date']))
                  ->setCellValue("D$x",ucwords($result_candidates['CandidateName']))
                  ->setCellValue("E$x",ucwords($result_candidates['final_status']))
                  ->setCellValue("F$x",convert_db_to_display_date($result_candidates['created_on'],DB_DATE_FORMAT,DISPLAY_DATE_FORMAT12))
                  ->setCellValue("G$x",convert_db_to_display_date($result_candidates['closuredate']));

              $x++;
            }
            // Rename worksheet
            $spreadsheet->getActiveSheet()->setTitle('Candidate Records');

            $spreadsheet->setActiveSheetIndex(0);

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment;filename=Candidate Records.xlsx");
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

            $this->zip->add_data("Candidate Records ".'.'.'xls',$xlsData);

           $this->zip->download(''.time().'.zip'); 

    }*/


    
    public function download_annexure_report()
    {
        $this->load->library('zip');

        set_time_limit(0);

        ini_set('memory_limit', '-1');

        $this->load->model('candidates_model');

        $court_id = $this->input->post('court_id');

        $court_id = explode(',', $court_id);
       
        foreach ($court_id as $key => $value_id) {
        
                    
            $result = $this->candidates_model->get_court_attachment_details(array('courtver.id' => $value_id));
                
          
            if (!empty($result)) {
                    
                foreach ($result as $key => $attachment_result) {  

                    $attachment = $attachment_result['add_attachments'];
        
                    $attachment_explode = explode('||',$attachment);
                    $counter = 1;
                    foreach ($attachment_explode as  $value_attachment) {
                        $file_info = pathinfo($value_attachment);
                        $extension = $file_info['extension'];

                        $file_upload_path = SITE_BASE_PATH . COURT_VERIFICATION . $attachment_result['clientid'].'/'.$value_attachment;
                        
                            $this->zip->read_file(  SITE_BASE_PATH . COURT_VERIFICATION . $attachment_result['clientid'].'/'.$value_attachment,$attachment_result['ClientRefNumber']."_".$counter. '.' . $extension);

                            $counter++;

                        }
                   
                        $vendor_attachments = $attachment_result['vendor_attachments'];
        
                        $attachment_explode_vendor = explode('||',$vendor_attachments);
                        $counter = 101;
                        foreach ($attachment_explode_vendor as  $value_vendor_attachment) {
                            $file_info = pathinfo($value_vendor_attachment);
                            $extension = $file_info['extension'];
  
                            $file_upload_path = SITE_BASE_PATH . COURT_VERIFICATION . "vendor_file".'/'.$value_vendor_attachment;
                            
                            $this->zip->read_file(  SITE_BASE_PATH . COURT_VERIFICATION . "vendor_file".'/'.$value_vendor_attachment,$attachment_result['ClientRefNumber']."_".$counter. '.' . $extension);

                            $counter++;

                        }

                    }                 
                }

        }


        $result_candidate = $this->final_qc_model->select_candidate_for_export($court_id);

        require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
            // Create new Spreadsheet object
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

            // Set document properties
            $spreadsheet->getProperties()->setCreator(CRMNAME)
                ->setLastModifiedBy(CRMNAME)
                ->setTitle(CRMNAME)
                ->setSubject('Court Records')
                ->setDescription('Court Records');

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
            foreach(range('A','H') as $columnID) {
              $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                  ->setWidth(20);
            }

            // set the names of header cells
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A1",'Component Ref No')
                ->setCellValue("B1",'Client Ref No')
                ->setCellValue("C1",'Comp Received date')
                ->setCellValue("D1",'Candidate Name')
                ->setCellValue("E1",'Vendor Status')
                ->setCellValue("F1",'Vendor Assigned on')
                ->setCellValue("G1",'Remarks')
                ->setCellValue("H1",'Closure Date');
            // Add some data
            $x= 2;
            foreach($result_candidate as $result_candidates){
            

                $spreadsheet->setActiveSheetIndex(0)
                  
                  ->setCellValue("A$x",$result_candidates['court_com_ref'])
                  ->setCellValue("B$x",ucwords($result_candidates['ClientRefNumber']))
                  ->setCellValue("C$x",convert_db_to_display_date($result_candidates['iniated_date']))
                  ->setCellValue("D$x",ucwords($result_candidates['CandidateName']))
                  ->setCellValue("E$x",ucwords($result_candidates['vendor_actual_status']))
                  ->setCellValue("F$x",convert_db_to_display_date($result_candidates['created_on'],DB_DATE_FORMAT,DISPLAY_DATE_FORMAT12))
                  ->setCellValue("G$x",$result_candidates['vendor_remark'])
                  ->setCellValue("H$x",convert_db_to_display_date($result_candidates['closuredate']));

              $x++;
            }
            // Rename worksheet
            $spreadsheet->getActiveSheet()->setTitle('Court Records');

            $spreadsheet->setActiveSheetIndex(0);

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment;filename=Court Records.xlsx");
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

            $this->zip->add_data("Court Records ".'.'.'xls',$xlsData);

           $this->zip->download(''.time().'.zip'); 

    }

    
   /* protected function convert_jpg_to_png($source,$distination)
    {
        try {
            $jpg = $source;
            $info = pathinfo($jpg);
            $file_name =  $distination.basename($jpg,'.'.$info['extension']);
            exec("convert -density 300 $jpg -resize 50% -quality 100 $file_name.png > /dev/null &");
            return true;
        } catch (Exception $e) {
            log_message('error', 'File_upload_library::convert_jpg_to_png');
            log_message('error', $e->getMessage());
        }
       
    }*/
                        
}