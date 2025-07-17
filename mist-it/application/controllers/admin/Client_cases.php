<?php defined('BASEPATH') or exit('No direct script access allowed');

class Client_cases extends MY_Controller
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

        $this->perm_array = array('page_id' => 67);
        $this->load->model('client/candidates_model');

    }

    public function index()
    {
        $data['header_title'] = "Client Candidates List";

        $data['clients_id'] = $this->get_clients_for_client_cases();
       

        $data['status'] = $this->get_status_candidate();

        $this->load->view('admin/header', $data);

        $this->load->view('admin/client_candidates_list');

        $this->load->view('admin/footer');

    }

    
    public function client_cands_view_datatable()
    {
    
        if ($this->input->is_ajax_request()) {
            
            $params = $data_arry = $columns = array();

            $params = $_REQUEST;

            $columns = array('id', 'CandidateName', "clientname", "caserecddate", 'ClientRefNumber', 'cmp_ref_no', 'overallstatus', 'remarks', 'view', 'encry_id');

            $cands_results = $this->candidates_model->get_all_cand_with_search_client_case($params, $columns);

            $totalRecords = count($this->candidates_model->get_all_cand_with_search_client_case_count($params, $columns));
            $x = 0;

            foreach ($cands_results as $key => $cands_result) {
                $data_arry[$x]['id'] = $cands_result['cands_info_id'];
                $data_arry[$x]['candiate_id'] = $cands_result['cands_info_id'];
                $data_arry[$x]['CandidateName'] = ucwords(strtolower($cands_result['CandidateName']));
                $data_arry[$x]['Entity'] = ucwords(strtolower($cands_result['entity_name']));
                $data_arry[$x]['Package'] = ucwords(strtolower($cands_result['package_name']));
                $data_arry[$x]['ClientRefNumber'] = $cands_result['ClientRefNumber'];
                $data_arry[$x]['cmp_ref_no'] = $cands_result['cmp_ref_no'];
                $data_arry[$x]['overallstatus'] = $cands_result['status_value'];
                $data_arry[$x]['clientname'] = $cands_result['clientname'];
                $data_arry[$x]['edit_id'] = CLIENT_SITE_URL . "candidates/view_details/" . encrypt($cands_result['id']);
                $data_arry[$x]['overallclosuredate'] = convert_db_to_display_date($cands_result['overallclosuredate']);
                $data_arry[$x]['encry_id'] = ADMIN_SITE_URL . "candidates/view_details/" . encrypt($cands_result['cands_info_id']);

                $data_arry[$x]['clientname'] = ucwords(strtolower($cands_result['clientname']));
                $data_arry[$x]['caserecddate'] = convert_db_to_display_date($cands_result['created_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);

                $data_arry[$x]['last_sms_on'] = convert_db_to_display_date($cands_result['last_sms_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);
                $data_arry[$x]['last_activity'] = convert_db_to_display_date($cands_result['last_activity'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);
                $data_arry[$x]['last_email_on'] = convert_db_to_display_date($cands_result['last_email_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);
                $data_arry[$x]['candidate_visit_on'] = convert_db_to_display_date($cands_result['last_candidate_visit'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);

                 $login_url = '';
                
                $data_arry[$x]['link'] = $login_url;
                $data_arry[$x]['activity_buttom'] =  '<button data-id='.$cands_result['cands_info_id'].'  data-url="'.ADMIN_SITE_URL.'client_cases/select_candidate_activity_record/'.$cands_result['cands_info_id'].'"  data-toggle="modal" class="btn btn-sm btn-info  showactivityModel"> Activity </button>';

                $data_arry[$x]['mail_sent'] = "<button class='btn btn-info btn-sm trigger_email_again' id='".$cands_result['id']."'>Send (0)</button>";
                $data_arry[$x]['sms_sent'] = "<button class='btn btn-info btn-sm trigger_sms_again' id='".$cands_result['id']."'>Send (0)</button>";
                $data_arry[$x]['candidate_visit'] = "<button class='btn btn-info btn-sm' id='".$cands_result['id']."'>Visit (0)</button>";

                $data_arry[$x]['copy_link'] = '';
                if($cands_result['public_key'] != "" && $cands_result['private_key'] != "") {   
                    $login_url = CANDIDATE_VERIFY_LINK.$cands_result['public_key'].'/'.$cands_result['private_key'];
                    $data_arry[$x]['link'] = $login_url;
                    $data_arry[$x]['mail_sent'] = "<button class='btn btn-info btn-sm trigger_email_again' id='".$cands_result['id']."'>Send (".$cands_result['is_mail_sent'].")</button>";
                    $data_arry[$x]['sms_sent'] = "<button class='btn btn-info btn-sm trigger_sms_again' id='".$cands_result['id']."'>Send (".$cands_result['is_sms_sent'].")</button>";
                    $data_arry[$x]['candidate_visit'] = "<button class='btn btn-info btn-sm' id='".$cands_result['id']."'>Visit (".$cands_result['candidate_visit'].")</button>";
                    $data_arry[$x]['copy_link'] = "<button class='btn btn-info btn-sm copyLink' data-link='".$login_url ."' id=".$x.">Copy Link</button>";
                }

                $data_arry[$x]['pending_check'] = 'NA';
                $data_arry[$x]['insufficiency_check'] = 'NA';
                $data_arry[$x]['closed_check'] = 'NA';

                $array_wip = array();
                $array_insufficiency = array();
                $array_closed = array();

                $this->load->model('candidates_model');

                $result_address_main = $this->candidates_model->get_addres_ver_status(array('candidates_info.id' => $cands_result['cands_info_id']));

                if (!empty($result_address_main)) {
                    $address_count = 1;
                    foreach ($result_address_main as $result_address) {

                        $address_component_status = ($result_address['var_filter_status'] != "") ? $result_address['var_filter_status'] : 'WIP';
                        //print_r($data_arry[$x]['address_component_status']);

                        if (($address_component_status == "WIP") || ($address_component_status == "wip")) {
                            array_push($array_wip, 'Address ' . $address_count);
                        }
                        if (($address_component_status == "Insufficiency") || ($address_component_status == "insufficiency")) {
                            array_push($array_insufficiency, 'Address ' . $address_count);
                        }
                        if (($address_component_status == "Closed") || ($address_component_status == "closed")) {
                            array_push($array_closed,'Address ' . $address_count);
                        }

                        $address_count++;

                    }
                }

                $result_education_main = $this->candidates_model->get_education_ver_status(array('candidates_info.id' => $cands_result['cands_info_id']));

                if (!empty($result_education_main)) {
                    $education_count = 1;
                    foreach ($result_education_main as $result_education) {

                        $education_component_status = ($result_education['filter_status'] != "") ? $result_education['filter_status'] : 'WIP';

                        if (($education_component_status == "WIP") || ($education_component_status == "wip")) {
                            array_push($array_wip,  'Education ' . $education_count);
                        }
                        if (($education_component_status == "Insufficiency") || ($education_component_status == "insufficiency")) {
                            array_push($array_insufficiency,  'Education ' . $education_count);
                        }
                        if (($education_component_status == "Closed") || ($education_component_status == "closed")) {
                            array_push($array_closed,  'Education ' . $education_count);
                        }

                        $education_count++;
                    }
                }

                $result_employment_main = $this->candidates_model->get_employment_ver_status1(array('candidates_info.id' => $cands_result['cands_info_id']));

                if (!empty($result_employment_main)) {
                    $employment_count = 1;

                    foreach ($result_employment_main as $result_employment) {

                        $employment_component_status = ($result_employment['var_filter_status'] != "") ? $result_employment['var_filter_status'] : 'WIP';

                        if (($employment_component_status == "WIP") || ($employment_component_status == "wip")) {
                            array_push($array_wip,"Employment " . $employment_count);
                        }
                        if (($employment_component_status == "Insufficiency") || ($employment_component_status == "insufficiency")) {
                            array_push($array_insufficiency,"Employment " . $employment_count);
                        }
                        if (($employment_component_status == "Closed") || ($employment_component_status == "closed")) {
                            array_push($array_closed, "Employment " . $employment_count);
                        }
                        $employment_count++;
                    }
                }

                $result_court_main = $this->candidates_model->get_court_ver_status(array('candidates_info.id' => $cands_result['cands_info_id']));

                if (!empty($result_court_main)) {
                    $court_count = 1;

                    foreach ($result_court_main as $result_court) {

                        $court_component_status = ($result_court['filter_status'] != "") ? $result_court['filter_status'] : 'WIP';

                       if (($court_component_status == "WIP") || ($court_component_status == "wip")) {
                            array_push($array_wip,  "Court " . $court_count);
                        }
                        if (($court_component_status == "Insufficiency") || ($court_component_status == "insufficiency")) {
                            array_push($array_insufficiency, "Court " . $court_count);
                        }
                        if (($court_component_status == "Closed") || ($court_component_status == "closed")) {
                            array_push($array_closed,  "Court " . $court_count);
                        }

                        $court_count++;

                    }
                }

                $result_pcc_main = $this->candidates_model->get_pcc_ver_status(array('candidates_info.id' => $cands_result['cands_info_id']));

                if (!empty($result_pcc_main)) {

                    $pcc_count = 1;

                    foreach ($result_pcc_main as $result_pcc) {

                        $pcc_component_status = ($result_pcc['filter_status'] != "") ? $result_pcc['filter_status'] : 'WIP';

                        if (($pcc_component_status == "WIP") || ($pcc_component_status == "wip")) {
                            array_push($array_wip, "PCC " . $pcc_count);
                        }
                        if (($pcc_component_status == "Insufficiency") || ($pcc_component_status == "insufficiency")) {
                            array_push($array_insufficiency, "PCC " . $pcc_count);
                        }
                        if (($pcc_component_status == "Closed") || ($pcc_component_status == "closed")) {
                            array_push($array_closed,"PCC " . $pcc_count);
                        }

                        $pcc_count++;
                    }
                }

                $result_reference_main = $this->candidates_model->get_reference_ver_status(array('reference.candsid' => $cands_result['cands_info_id']));

                if (!empty($result_reference_main)) {

                    $reference_count = 1;

                    foreach ($result_reference_main as $result_reference) {

                        $reference_component_status = ($result_reference['filter_status'] != "") ? $result_reference['filter_status'] : 'WIP';

                       
                        if (($reference_component_status == "WIP") || ($reference_component_status == "wip")) {
                            array_push($array_wip,"Reference " . $reference_count);
                        }
                        if (($reference_component_status == "Insufficiency") || ($reference_component_status == "insufficiency")) {
                            array_push($array_insufficiency,"Reference " . $reference_count);
                        }
                        if (($reference_component_status == "Closed") || ($reference_component_status == "closed")) {
                            array_push($array_closed,"Reference " . $reference_count);
                        }

                        $reference_count++;
                    }

                }

                $result_global_main = $this->candidates_model->get_global_db_ver_status(array('candidates_info.id' => $cands_result['cands_info_id']));
                if (!empty($result_global_main)) {

                    $global_count = 1;

                    foreach ($result_global_main as $result_global) {

                        $globaldb_component_status = ($result_global['filter_status'] != "") ? $result_global['filter_status'] : 'WIP';

                         if (($globaldb_component_status == "WIP") || ($globaldb_component_status == "wip")) {
                            array_push($array_wip,"Global " . $global_count);
                        }
                        if (($globaldb_component_status == "Insufficiency") || ($globaldb_component_status == "insufficiency")) {
                            array_push($array_insufficiency,"Global " . $global_count);
                        }
                        if (($globaldb_component_status == "Closed") || ($globaldb_component_status == "closed")) {
                            array_push($array_closed, "Global " . $global_count);
                        }

                        $global_count++;

                    }
                }

                $result_identity_main = $this->candidates_model->get_identity_ver_status(array('candidates_info.id' => $cands_result['cands_info_id']));

                if (!empty($result_identity_main)) {

                    $identity_count = 1;

                    foreach ($result_identity_main as $result_identity) {

                        $identity_component_status = ($result_identity['var_filter_status'] != "") ? $result_identity['var_filter_status'] : 'WIP';

                      
                        if (($identity_component_status == "WIP") || ($identity_component_status == "wip")) {
                            array_push($array_wip, "Identity " . $identity_count);
                        }
                        if (($identity_component_status == "Insufficiency") || ($identity_component_status == "insufficiency")) {
                            array_push($array_insufficiency, "Identity " . $identity_count);
                        }
                        if (($identity_component_status == "Closed") || ($identity_component_status == "closed")) {
                            array_push($array_closed,"Identity " . $identity_count);
                        }


                        $identity_count++;
                    }
                }

                $result_credit_report_main = $this->candidates_model->get_credit_reports_ver_status(array('candidates_info.id' => $cands_result['cands_info_id']));
                if (!empty($result_credit_report_main)) {

                    $credit_report_count = 1;

                    foreach ($result_credit_report_main as $result_credit_report) {

                        $credit_report_component_status = ($result_credit_report['var_filter_status'] != "") ? $result_credit_report['var_filter_status'] : 'WIP';

                        if (($credit_report_component_status == "WIP") || ($credit_report_component_status == "wip")) {
                            array_push($array_wip,"Credit Report " . $credit_report_count);
                        }
                        if (($credit_report_component_status == "Insufficiency") || ($credit_report_component_status == "insufficiency")) {
                            array_push($array_insufficiency, "Credit Report " . $credit_report_count);
                        }
                        if (($credit_report_component_status == "Closed") || ($credit_report_component_status == "closed")) {
                            array_push($array_closed,"Credit Report " . $credit_report_count);
                        }

                        $credit_report_count++;
                    }
                }

                $result_drugs_main = $this->candidates_model->get_narcver_ver_status(array('candidates_info.id' => $cands_result['cands_info_id']));
                if (!empty($result_drugs_main)) {

                    $drugs_count = 1;

                    foreach ($result_drugs_main as $result_drugs) {

                        $drugs_component_status = ($result_drugs['var_filter_status'] != "") ? $result_drugs['var_filter_status'] : 'WIP';

                        if (($drugs_component_status == "WIP") || ($drugs_component_status == "wip")) {
                            array_push($array_wip,"Drugs " . $drugs_count);
                        }
                        if (($drugs_component_status == "Insufficiency") || ($drugs_component_status == "insufficiency")) {
                            array_push($array_insufficiency, "Drugs " . $drugs_count);
                        }
                        if (($drugs_component_status == "Closed") || ($drugs_component_status == "closed")) {
                            array_push($array_closed, "Drugs " . $drugs_count);
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


    public function get_clients_for_client_cases()
    {

        $clients = $this->candidates_model->select_client_for_client_cases('clients', false, array('clients.id', 'clients.clientname'));

        $clients_arry[0] = 'Select Client';

        foreach ($clients as $key => $value) {
            $clients_arry[$value['id']] = ucwords(strtolower($value['clientname']));
        }
        return $clients_arry;
    }

    public function trigger_sms_again()
    {
        $json_array['status'] = ERROR_CODE;
        $json_array['message'] = 'Database error, please try again';
        if($this->input->is_ajax_request()) 
        {
            $this->form_validation->set_rules('send_id', 'ID', 'required');

            if ($this->form_validation->run() == FALSE)
            {
                $json_array['status'] = ERROR_CODE;
                $json_array['message'] = validation_errors('','');
            }
            else
            {
                $id = $this->input->post('send_id');
  
                $details = $this->candidates_model->select(TRUE,array('*'),array('id' => $id));

                if(!empty($details)) {

                    $update = $this->candidates_model->user_sms_count(array('status' => STATUS_ACTIVE),array('id' => $id));

                    if(!empty($details) && $update) {
                        $this->cli_sms_send_again($details);

                        $json_array['status'] = SUCCESS_CODE;
                        $json_array['message'] = 'SMS Sent';
                    }
                    else{
                         
                        $json_array['status'] = ERROR_CODE;
                        $json_array['message'] = 'SMS NOT Sent'; 
                    }
                   
                }
                
            }
        }
        echo_json($json_array);
    }

    public function trigger_email_again()
    {
        $json_array['status'] = ERROR_CODE;
        $json_array['message'] = 'Database error, please try again';
        if($this->input->is_ajax_request()) 
        {
            $this->form_validation->set_rules('send_id', 'ID', 'required');

            if ($this->form_validation->run() == FALSE)
            {
                $json_array['status'] = ERROR_CODE;
                $json_array['message'] = validation_errors('','');
            }
            else
            {
                $id = $this->input->post('send_id');

                $details = $this->candidates_model->select(true, array(),array('client_candidates_info.id' => $id));

                
                if(!empty($details)) {
                   
                    
                    if(!empty($details['address_component_check']) || !empty($details['education_component_check']) || !empty($details['employment_component_check']) || !empty($details['reference_component_check']) || !empty($details['court_component_check']) || !empty($details['global_component_check']) || !empty($details['identity_component_check']) || !empty($details['credit_report_component_check'])  || !empty($details['drugs_component_check']) || !empty($details['pcc_component_check'])) {

                        $update = $this->candidates_model->user_email_count(array('status' => STATUS_ACTIVE),array('id' => $id));
                           
                        if(!empty($details) && $update) {
                            $this->cli_email_send_again($details);

                            $json_array['status'] = SUCCESS_CODE;
                            $json_array['message'] = 'Email Sent';
                        }
                        else{
                           
                            $json_array['status'] = ERROR_CODE;
                            $json_array['message'] = 'Email Not Send';
                        }
                            
                    }else {
                        $json_array['status'] = ERROR_CODE;
                        $json_array['message'] = 'All check are closed'; 
                    }
                        
                }
                
            }
        }
        echo_json($json_array);
    }


    public function cli_email_send_again($detail)
    {

        if (!empty($detail)) {
            try {
                $details = $this->candidates_model->select_mail_details(array('client_candidates_info.cmp_ref_no'=>$detail['cmp_ref_no']));
             
                $client_manager_id = $this->candidates_model->select_client_manager_details(array('clients.id'=>  $details[0]['clientid']));
         
                $client_manager_email = $this->candidates_model->select_user_info($client_manager_id[0]['clientmgr']);

                $client_login_email_id = $this->candidates_model->select_client_login_email_id(array('client_login.client_id' => $details[0]['clientid']));
                       
                $email_tmpl_data['url'] = CANDIDATE_VERIFY_LINK . $details[0]['public_key'].'/'.$details[0]['private_key'];
               
                $email_tmpl_data['from_email'] = VERIFICATIONEMAIL;

                $email_tmpl_data['to_emails'] = $details[0]['cands_email_id'];

                $email_tmpl_data['cc_emails'] = $client_manager_email[0]['email'].','.$client_login_email_id[0]['email_id'].','.FROMEMAIL;

       
                $email_tmpl_data['cands_name'] = $details[0]['CandidateName'];

                $email_tmpl_data['clientname'] = $details[0]['clientname'];

                $email_tmpl_data['candidate_id'] = $details[0]['id'];

                $email_tmpl_data['caserecddate'] = $details[0]['caserecddate'];

                
                $email_tmpl_data['cands_info_id'] = $details[0]['cands_info_id'];



                $email_tmpl_data['CandidatesContactNumber'] = $details[0]['CandidatesContactNumber'];

                $email_tmpl_data['pending_component']['address_component_check'] = $details[0]['address_component_check'];
                $email_tmpl_data['pending_component']['employment_component_check'] = $details[0]['employment_component_check'];
                $email_tmpl_data['pending_component']['education_component_check'] = $details[0]['education_component_check'];
                $email_tmpl_data['pending_component']['reference_component_check'] = $details[0]['reference_component_check'];
                $email_tmpl_data['pending_component']['court_component_check'] = $details[0]['court_component_check'];
                $email_tmpl_data['pending_component']['global_component_check'] = $details[0]['global_component_check'];
                $email_tmpl_data['pending_component']['identity_component_check'] = $details[0]['identity_component_check'];
                $email_tmpl_data['pending_component']['credit_report_component_check'] = $details[0]['credit_report_component_check'];
                $email_tmpl_data['pending_component']['drugs_component_check'] = $details[0]['drugs_component_check'];
                $email_tmpl_data['pending_component']['pcc_component_check'] = $details[0]['pcc_component_check'];

                $this->load->library('email');

                $result = $this->email->client_send_mail_trigger($email_tmpl_data);

                return $result;
            } catch (Exception $e) {
                log_message('error', 'Candidate email trigger');
                log_message('error', $e->getMessage());
            }
        }
    }

    public function cli_sms_send_again($detail)
    {

        if (!empty($detail)) {
            try {
                $details = $this->candidates_model->select_mail_details(array('client_candidates_info.cmp_ref_no'=>$detail['cmp_ref_no']));
             
                    if(!empty($details[0]['CandidatesContactNumber']))
                    {
                        $sms_url = CANDIDATE_VERIFY_LINK . $details[0]['public_key'].'/'.$details[0]['private_key']; 

                        $sms_content = "Greetings from ".SMS_NAME.". We have partnered with your current employer ".ucwords($details[0]['clientname'])." to conduct your background verification. Request you to visit ".$sms_url." and update the details at the earliest.";
                   
                        $result_json = $this->send_sms($details[0]['CandidatesContactNumber'],$sms_content);
               
                        $result  = json_decode($result_json);

                        if($result->status == "success")
                        {  

                            $this->candidates_model->save(array('last_sms_on' => date(DB_DATE_FORMAT),'cron_status' => STATUS_ACTIVE),array('id'=>$details[0]['id']));

                            $this->candidates_model->save_task_manager('mail_sms_details',array('type' => 2,'candidate_id'=> $details[0]['cands_info_id'],'mail_sms_send' =>  date(DB_DATE_FORMAT)));

                    
                        }
                    }
               
            } catch (Exception $e) {
                log_message('error', 'cli_sms_send_again');
                log_message('error', $e->getMessage());
            }
        }
    }

    protected function send_sms($numbers = '', $message) {
        log_message('error', '$message and mobile number');
        log_message('error', $message);
        $response = '';
        try {
            if(!empty($numbers) && $message) {
             
                $apiKey = urlencode('Nzc0MTZjMzI3MDMwMzk1NTY0NDc2ZTc2NTA2MzdhNmU=');
                $message = rawurlencode($message);
                $sender = urlencode(MASSAGESENDER);

                if(is_array($numbers))
                    $numbers = implode(',', $numbers);
            
                // Prepare data for POST request
                $data = array('apikey' => $apiKey, 'numbers' => $numbers, "sender" => $sender, "message" => $message);

                log_message('error', print_r($data, true));
                // Send the POST request with cURL
                $ch = curl_init('https://api.textlocal.in/send/');
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $errors = curl_error($ch);
                $response_http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $response = curl_exec($ch);
                curl_close($ch);

                log_message('error', 'send_sms response');
                log_message('error', print_r( [ $response_http ] , TRUE) );
               
            }
        } catch (Exception $e) {

            log_message('error', 'Email Library ::send_sms');
            log_message('error', $e->getMessage());
        }
  
        return $response;
    }
    
    protected function get_status_candidate()
    {
        $clients = $this->common_model->distinct_status(array('id', "filter_status"), array('components_id' => '0'));
   
        $status_arry[''] = 'Select Status';

        foreach ($clients as $key => $value) {
            $status_arry[$value['filter_status']] = ucwords($value['filter_status']);
        }
        $status_arry['All'] = 'All';
        return $status_arry;
    }

    public function save_candidate_activity_details()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {

            $frm_details = $this->input->post();
          
            $field_array = array('candidate_id' => $frm_details['candidate_id'],
                'activity_status' => $frm_details['activity_status'],
                'remark' => $frm_details['activity_remark'],
                "status" => 1,
                "created_by" => $this->user_info['id'],
                "created_on" => date(DB_DATE_FORMAT)
            );

            
            $insert_id = $this->candidates_model->save_task_manager('candidate_activity_record', $field_array);

            if( $insert_id)
            { 
                $json_array['status'] = SUCCESS_CODE;
                $json_array['message'] = 'Record inserted successfully';

            }
            else{

                $json_array['status'] = ERROR_CODE;
                $json_array['message'] = "Access Denied, You don’t have permission to access this page";
            }

        } else {

            $json_array['status'] = ERROR_CODE;
            $json_array['message'] = "Access Denied, You don’t have permission to access this page";
        }
        echo_json($json_array);
        
    }

    public function select_candidate_activity_record($candidate_id)
    {
        if ($this->input->is_ajax_request() && $candidate_id) {

            $data['candiate_activity'] = $this->candidates_model->select_candidate_activity_record(array('candidate_activity_record.candidate_id' => $candidate_id));
          
            echo $this->load->view('admin/candidate_activity_record', $data, true);
        } else {
            echo "<p>Something went wrong, please try again.</p>";
        }

    }

    public function get_candidate_detail_for_export_detail($component_id)
    {
        $data_arry = array();

        $cands_results = $this->candidates_model->get_candidate_detail_for_export($component_id);
       
        $x = 0;
        foreach ($cands_results as $key => $cands_result) {


            $mail_details = $this->candidates_model->select_detials('mail_sms_details',array('type','mail_sms_send'),array('candidate_id' => $cands_result['cands_info_id']));
             
            $mail_array = array();
            $sms_array = array();

            if(!empty($mail_details[0]))
            {
                foreach ($mail_details as $key => $mail_detail) {
                    if($mail_detail['type'] == '1')
                    {
                        array_push($mail_array, convert_db_to_display_date($mail_detail['mail_sms_send'],DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12)); 
                    }
                    if($mail_detail['type'] == '2')
                    {
                        array_push($sms_array, convert_db_to_display_date($mail_detail['mail_sms_send'],DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12)); 
                    }
                }

            }
            else{

                $mail_array = array();
                $sms_array = array();

            }

          
            $data_arry[$x]['id'] = $x + 1;
            $data_arry[$x]['CandidateName'] = ucwords(strtolower($cands_result['CandidateName']));
     
            $data_arry[$x]['cmp_ref_no'] = $cands_result['cmp_ref_no'];
            $data_arry[$x]['status_value'] = $cands_result['status_value'];
            $data_arry[$x]['entity_name'] = $cands_result['entity_name'];
            $data_arry[$x]['package_name'] = $cands_result['package_name'];
            $data_arry[$x]['activity_status'] = $cands_result['activity_status'];
            $data_arry[$x]['clientname'] = ucwords(strtolower($cands_result['clientname']));
            $data_arry[$x]['caserecddate'] = convert_db_to_display_date($cands_result['caserecddate']);
            $data_arry[$x]['modified_by'] = $cands_result['modified_by'];
            $data_arry[$x]['modified_on'] = convert_db_to_display_date($cands_result['modified_on'],DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);

            $data_arry[$x]['email_count'] = $cands_result['is_mail_sent'];
            $data_arry[$x]['sms_count'] = $cands_result['is_sms_sent'];

            $data_arry[$x]['last_email_on'] = implode(' || ', $mail_array);
            $data_arry[$x]['last_sms_on'] = implode('|| ', $sms_array);

            $data_arry[$x]['activity_remark'] =  convert_db_to_display_date($cands_result['latest_activity_created_on'],DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12)." || ".$cands_result['activity_remark'];

            $login_url = CANDIDATE_VERIFY_LINK.$cands_result['public_key'].'/'.$cands_result['private_key'];
            $data_arry[$x]['link'] = $login_url;

        

                $data_arry[$x]['pending_check'] = 'NA';
                $data_arry[$x]['insufficiency_check'] = 'NA';
                $data_arry[$x]['closed_check'] = 'NA';

                $array_wip = array();
                $array_insufficiency = array();
                $array_closed = array();

                $this->load->model('addressver_model');

                $result_address_main = $this->candidates_model->get_addres_ver_status(array('candidates_info.id' => $cands_result['cands_info_id']));

                if (!empty($result_address_main)) {
                    $address_count = 1;
                    foreach ($result_address_main as $result_address) {

                        $address_component_status = ($result_address['var_filter_status'] != "") ? $result_address['var_filter_status'] : 'WIP';
                        //print_r($data_arry[$x]['address_component_status']);

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

                $result_education_main = $this->candidates_model->get_education_ver_status(array('candidates_info.id' => $cands_result['cands_info_id']));

                if (!empty($result_education_main)) {
                    $education_count = 1;
                    foreach ($result_education_main as $result_education) {

                        $education_component_status = ($result_education['filter_status'] != "") ? $result_education['filter_status'] : 'WIP';

                        if (($education_component_status == "WIP") || ($education_component_status == "wip")) {
                            array_push($array_wip, 'Education '.$education_count);
                        }
                        if (($education_component_status == "Insufficiency") || ($education_component_status == "insufficiency")) {
                            array_push($array_insufficiency, 'Education '.$education_count);
                        }
                        if (($education_component_status == "Closed") || ($education_component_status == "closed")) {
                            array_push($array_closed, 'Education '.$education_count);
                        }

                        $education_count++;
                    }
                }

                $result_employment_main = $this->candidates_model->get_employment_ver_status1(array('candidates_info.id' => $cands_result['cands_info_id']));

                if (!empty($result_employment_main)) {
                    $employment_count = 1;

                    foreach ($result_employment_main as $result_employment) {

                        $employment_component_status = ($result_employment['var_filter_status'] != "") ? $result_employment['var_filter_status'] : 'WIP';

                        if (($employment_component_status == "WIP") || ($employment_component_status == "wip")) {
                            array_push($array_wip,'Employment '.$employment_count);
                        }
                        if (($employment_component_status == "Insufficiency") || ($employment_component_status == "insufficiency")) {
                            array_push($array_insufficiency,'Employment '.$employment_count);
                        }
                        if (($employment_component_status == "Closed") || ($employment_component_status == "closed")) {
                            array_push($array_closed, 'Employment '.$employment_count);
                        }
                        $employment_count++;
                    }
                }

                $result_court_main = $this->candidates_model->get_court_ver_status(array('candidates_info.id' => $cands_result['cands_info_id']));

                if (!empty($result_court_main)) {
                    $court_count = 1;

                    foreach ($result_court_main as $result_court) {

                        $court_component_status = ($result_court['filter_status'] != "") ? $result_court['filter_status'] : 'WIP';

                       if (($court_component_status == "WIP") || ($court_component_status == "wip")) {
                            array_push($array_wip, 'Court '.$court_count);
                        }
                        if (($court_component_status == "Insufficiency") || ($court_component_status == "insufficiency")) {
                            array_push($array_insufficiency, 'Court '.$court_count);
                        }
                        if (($court_component_status == "Closed") || ($court_component_status == "closed")) {
                            array_push($array_closed, 'Court '.$court_count);
                        }

                        $court_count++;

                    }
                }

                $result_pcc_main = $this->candidates_model->get_pcc_ver_status(array('candidates_info.id' => $cands_result['cands_info_id']));

                if (!empty($result_pcc_main)) {

                    $pcc_count = 1;

                    foreach ($result_pcc_main as $result_pcc) {

                        $pcc_component_status = ($result_pcc['filter_status'] != "") ? $result_pcc['filter_status'] : 'WIP';

                        if (($pcc_component_status == "WIP") || ($pcc_component_status == "wip")) {
                            array_push($array_wip, 'PCC '.$pcc_count);
                        }
                        if (($pcc_component_status == "Insufficiency") || ($pcc_component_status == "insufficiency")) {
                            array_push($array_insufficiency, 'PCC '.$pcc_count);
                        }
                        if (($pcc_component_status == "Closed") || ($pcc_component_status == "closed")) {
                            array_push($array_closed, 'PCC '.$pcc_count);
                        }

                        $pcc_count++;
                    }
                }

                $result_reference_main = $this->candidates_model->get_reference_ver_status(array('reference.candsid' => $cands_result['cands_info_id']));

                if (!empty($result_reference_main)) {

                    $reference_count = 1;

                    foreach ($result_reference_main as $result_reference) {

                        $reference_component_status = ($result_reference['filter_status'] != "") ? $result_reference['filter_status'] : 'WIP';

                       
                        if (($reference_component_status == "WIP") || ($reference_component_status == "wip")) {
                            array_push($array_wip,'Reference '.$reference_count);
                        }
                        if (($reference_component_status == "Insufficiency") || ($reference_component_status == "insufficiency")) {
                            array_push($array_insufficiency, 'Reference '.$reference_count);
                        }
                        if (($reference_component_status == "Closed") || ($reference_component_status == "closed")) {
                            array_push($array_closed, 'Reference '.$reference_count);
                        }

                        $reference_count++;
                    }

                }

                $result_global_main = $this->candidates_model->get_global_db_ver_status(array('candidates_info.id' => $cands_result['cands_info_id']));
                if (!empty($result_global_main)) {

                    $global_count = 1;

                    foreach ($result_global_main as $result_global) {

                        $globaldb_component_status = ($result_global['filter_status'] != "") ? $result_global['filter_status'] : 'WIP';

                         if (($globaldb_component_status == "WIP") || ($globaldb_component_status == "wip")) {
                            array_push($array_wip,'Global '.$global_count);
                        }
                        if (($globaldb_component_status == "Insufficiency") || ($globaldb_component_status == "insufficiency")) {
                            array_push($array_insufficiency, 'Global '.$global_count);
                        }
                        if (($globaldb_component_status == "Closed") || ($globaldb_component_status == "closed")) {
                            array_push($array_closed, 'Global '.$global_count);
                        }

                        $global_count++;

                    }
                }

                $result_identity_main = $this->candidates_model->get_identity_ver_status(array('candidates_info.id' => $cands_result['cands_info_id']));

                if (!empty($result_identity_main)) {

                    $identity_count = 1;

                    foreach ($result_identity_main as $result_identity) {

                        $identity_component_status = ($result_identity['var_filter_status'] != "") ? $result_identity['var_filter_status'] : 'WIP';

                      
                        if (($identity_component_status == "WIP") || ($identity_component_status == "wip")) {
                            array_push($array_wip, 'Identity '.$identity_count);
                        }
                        if (($identity_component_status == "Insufficiency") || ($identity_component_status == "insufficiency")) {
                            array_push($array_insufficiency, 'Identity '.$identity_count);
                        }
                        if (($identity_component_status == "Closed") || ($identity_component_status == "closed")) {
                            array_push($array_closed, 'Identity '.$identity_count);
                        }


                        $identity_count++;
                    }
                }

                $result_credit_report_main = $this->candidates_model->get_credit_reports_ver_status(array('candidates_info.id' => $cands_result['cands_info_id']));
                if (!empty($result_credit_report_main)) {

                    $credit_report_count = 1;

                    foreach ($result_credit_report_main as $result_credit_report) {

                        $credit_report_component_status = ($result_credit_report['var_filter_status'] != "") ? $result_credit_report['var_filter_status'] : 'WIP';

                        if (($credit_report_component_status == "WIP") || ($credit_report_component_status == "wip")) {
                            array_push($array_wip,'Credit Report '. $credit_report_count);
                        }
                        if (($credit_report_component_status == "Insufficiency") || ($credit_report_component_status == "insufficiency")) {
                            array_push($array_insufficiency, 'Credit Report '. $credit_report_count);
                        }
                        if (($credit_report_component_status == "Closed") || ($credit_report_component_status == "closed")) {
                            array_push($array_closed, 'Credit Report '. $credit_report_count);
                        }

                        $credit_report_count++;
                    }
                }

                $result_drugs_main = $this->candidates_model->get_narcver_ver_status(array('candidates_info.id' => $cands_result['cands_info_id']));
                if (!empty($result_drugs_main)) {

                    $drugs_count = 1;

                    foreach ($result_drugs_main as $result_drugs) {

                        $drugs_component_status = ($result_drugs['var_filter_status'] != "") ? $result_drugs['var_filter_status'] : 'WIP';

                        if (($drugs_component_status == "WIP") || ($drugs_component_status == "wip")) {
                            array_push($array_wip,'Drugs '. $drugs_count);
                        }
                        if (($drugs_component_status == "Insufficiency") || ($drugs_component_status == "insufficiency")) {
                            array_push($array_insufficiency, 'Drugs '. $drugs_count);
                        }
                        if (($drugs_component_status == "Closed") || ($drugs_component_status == "closed")) {
                            array_push($array_closed, 'Drugs '. $drugs_count);
                        }
                        $drugs_count++;
                    }
                }


                $data_arry[$x]['pending_check'] = implode(',',$array_wip);
                $data_arry[$x]['insufficiency_check'] = implode(',',$array_insufficiency);
                $data_arry[$x]['closed_check'] = implode(',',$array_closed);

            $x++;
        }
      
        return $data_arry;
    }


    public function export_client_candidate_details()
    {
  

        $json_array = array();
       
        if ($this->input->is_ajax_request()) {

        set_time_limit(0);

        ini_set('memory_limit', '-1');
            
        $component_check_id = $this->input->post('coomponent_check_id'); 
        

        if(!empty($component_check_id ))
        {
           $component_id = explode(',',$component_check_id);
        }
        else{
            $component_id = array(); 
        }
        
        $all_records = $this->get_candidate_detail_for_export_detail($component_id); 
   
         
            
            require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
            // Create new Spreadsheet object
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

            // Set document properties
            $spreadsheet->getProperties()->setCreator(CRMNAME)
                ->setLastModifiedBy(CRMNAME)
                ->setTitle(CRMNAME)
                ->setSubject('Client Cases records')
                ->setDescription('Client Cases records with their status');

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
            $spreadsheet->getActiveSheet()->getStyle('A1:S1')->applyFromArray($styleArray);
            // auto fit column to content
            foreach(range('A','S') as $columnID) {
              $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                  ->setWidth(20);
            }

            // set the names of header cells
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A1",'Case Inititiated')
                ->setCellValue("B1",'CRM Ref No')
                ->setCellValue("C1",'Client Name')
                ->setCellValue("D1",'Entity')
                ->setCellValue("E1",'Package')
                ->setCellValue("F1",'Candidate Name')
                ->setCellValue("G1",'Candidate Status')
                ->setCellValue("H1",'Status')
                ->setCellValue("I1",'WIP')
                ->setCellValue("J1",'Insuff')
                ->setCellValue("K1",'Closed')
                ->setCellValue("L1",'Email Count')
                ->setCellValue("M1",'Dates')
                ->setCellValue("N1",'SMS Count')
                ->setCellValue("O1",'Dates')
                ->setCellValue("P1",'Link')
                ->setCellValue("Q1",'Last worked by')
                ->setCellValue("R1",'Last worked on')
                ->setCellValue("S1",'Last remarks');
            // Add some data
            $x= 2;
            foreach($all_records as $all_record){

             
                $spreadsheet->setActiveSheetIndex(0)
                  
                  ->setCellValue("A$x",$all_record['caserecddate'])
                  ->setCellValue("B$x",ucwords($all_record['cmp_ref_no']))
                  ->setCellValue("C$x",ucwords($all_record['clientname']))
                  ->setCellValue("D$x",ucwords($all_record['entity_name']))
                  ->setCellValue("E$x",ucwords($all_record['package_name']))
                  ->setCellValue("F$x",ucwords($all_record['CandidateName']))
                  ->setCellValue("G$x",ucwords($all_record['status_value']))
                  ->setCellValue("H$x",ucwords($all_record['activity_status']))
                  ->setCellValue("I$x",$all_record['pending_check'])
                  ->setCellValue("J$x",$all_record['insufficiency_check'])
                  ->setCellValue("K$x",$all_record['closed_check'])
                  ->setCellValue("L$x",$all_record['email_count'])
                  ->setCellValue("M$x",$all_record['last_email_on'])
                  ->setCellValue("N$x",$all_record['sms_count'])
                  ->setCellValue("O$x",$all_record['last_sms_on'])
                  ->setCellValue("P$x",$all_record['link'])
                  ->setCellValue("Q$x",$all_record['modified_by'])
                  ->setCellValue("R$x",$all_record['modified_on'])
                  ->setCellValue("S$x",$all_record['activity_remark']);

              $x++;
            }
            // Rename worksheet
            $spreadsheet->getActiveSheet()->setTitle('Client Cases');

            $spreadsheet->setActiveSheetIndex(0);

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment;filename=Client Cases Records.xlsx");
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

            $json_array['file_name'] = "Client Cases";

            $json_array['message'] = "File downloaded successfully,please check in download folder";

            $json_array['status'] = SUCCESS_CODE;

        } else {
            $json_array['message'] = "Something went wrong,please try again";

            $json_array['status'] = ERROR_CODE;
        }

        echo_json($json_array);
    }

    public function get_credit_list()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {

       
            // Authorisation details.   
            $username = "sales@mistitservices.com";
            $hash = "801510e21fc5a5522b7dc6673a69fa9fe2a86f36d26987b74223daea5108ef7e";
            
            // You shouldn't need to change anything here.  
            $data = "username=".$username."&hash=".$hash;
            $ch = curl_init('http://api.textlocal.in/balance/?');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $credits = curl_exec($ch);
            // This is the number of credits you have left  
            curl_close($ch);

            $credit_details = json_decode($credits);
            
            $json_array['message'] = $credit_details->balance->sms;

            $json_array['status'] = SUCCESS_CODE;

            
        } else {
            $json_array['message'] = "Something went wrong,please try again";

            $json_array['status'] = ERROR_CODE;
        }

        echo_json($json_array);
      

    }
    
}
?>