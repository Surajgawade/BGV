<?php defined('BASEPATH') or exit('No direct script access allowed');
class Candidates extends MY_Controller {

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

        $this->perm_array = array('page_id' => 3);
        if ($this->input->is_ajax_request()) {
            $this->perm_array = array('direct_access' => true);
        }

        $this->assign_options = array();
        $this->load->helper('download');
        $this->load->model(array('candidates_model'));
    }

    public function index()
    {
        try {
            $data['header_title'] = "Candidates List";
            $data['filter_view'] = $this->filter_view_candidates(true);
            //$data['clients']        =   $this->get_clients(array('status' => STATUS_ACTIVE));
            $data['clients_id'] = $this->get_clients(array('status' => STATUS_ACTIVE));
            $data['status_value'] = $this->get_status_export();
            $this->load->view('admin/header', $data);
            $this->load->view('admin/candidates_list');
            $this->load->view('admin/footer');
        } catch (Exception $e) {
            log_message('error', 'Error on Candidates::index');
            log_message('error', $e->getMessage());
        }
    }

    public function add()
    {
        try {
            $data['header_title'] = "Create Candidates";
            $data['status'] = status_frm_db1();
            $data['states'] = $this->get_states();
            $data['clients'] = $this->get_clients(array('status' => STATUS_ACTIVE));
            $this->load->view('admin/header', $data);
            $this->load->view('admin/candidates_add');
            $this->load->view('admin/footer');
        } catch (Exception $e) {
            log_message('error', 'Error on Candidate::add');
            log_message('error', $e->getMessage());
        }
    }

    public function add_candidate()
    {
        if ($this->input->is_ajax_request()) {

            $frm_details = $this->input->post();

            $this->form_validation->set_rules('clientid', 'Client', 'required');

            $this->form_validation->set_rules('entity', 'Entity', 'required');

            $this->form_validation->set_rules('package', 'Package', 'required');

            $this->form_validation->set_rules('caserecddate', 'Case received', 'required');

            $this->form_validation->set_rules('ClientRefNumber', 'Client Ref Number', 'required');

            $this->form_validation->set_rules('CandidateName', 'Candidate Name', 'required');

            $this->form_validation->set_rules('CandidatesContactNumber', 'Primary Cantact', 'required');

            if ($this->form_validation->run() == false) {
                $json_array['status'] = ERROR_CODE;
                $json_array['message'] = validation_errors('', '');

            } else {
               
                try {
                    log_message('error', print_r($frm_details, true));
                    $client_ref_number = str_replace(" ", "", $frm_details['ClientRefNumber']);
                    
                    if(isset($frm_details['create_client_id']))
                    {
                        for ($i=1; $i < 1000; $i++) { 
                            $check_client_ref_exists =   $this->candidates_model->select(true, array('ClientRefNumber'), array('ClientRefNumber' =>$client_ref_number.'-'.$i, 'status' => '1'));

                            if (empty($check_client_ref_exists)) {
                                $client_ref_number = $client_ref_number.'-'.$i;
                                break;
                            } else {
                               continue;
                            }
                        }
                    }
                    
                    $field_array = array('clientid' => $frm_details['clientid'],
                        'entity' => $frm_details['entity'],
                        'package' => $frm_details['package'],
                        'caserecddate' => convert_display_to_db_date($frm_details['caserecddate']),
                        'cmp_ref_no' => '',
                        'ClientRefNumber' => $client_ref_number,
                        'CandidateName' => $frm_details['CandidateName'],
                        'DateofBirth' => convert_display_to_db_date($frm_details['DateofBirth']),
                        "gender" => $frm_details['gender'],
                        "grade" => $frm_details['grade'],
                        "prasent_address" => $frm_details['prasent_address'],
                        "cands_city" => $frm_details['cands_city'],
                        "cands_state" => $frm_details['cands_state'],
                        "cands_pincode" => $frm_details['cands_pincode'],
                        "cands_country" => 'India',
                        'NameofCandidateFather' => $frm_details['NameofCandidateFather'],
                        'MothersName' => $frm_details['MothersName'],
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
                        'cands_email_id' => $frm_details['cands_email_id'],
                        'branch_name' => $frm_details['branch_name'],
                        'remarks' => $frm_details['remarks'],
                        'overallstatus' => $frm_details['overallstatus'],
                        'created_by' => $this->user_info['id'],
                        'created_on' => date(DB_DATE_FORMAT),
                        'status' => STATUS_ACTIVE,
                    );
                  //  $field_array = array_map('strtolower', $field_array);

                    $result = $this->candidates_model->save($field_array);

                    $cmp_ref_no = $this->cmp_ref_no($result);

                    if ($result) {
                        $id = $result;

                        $field_array2 = array('candidates_info_id' => $result, 'cmp_ref_no' => $cmp_ref_no);

                        $field_array1 = array_merge($field_array, $field_array2);

                        $result_candidate = $this->candidates_model->save_candidate($field_array1);

                        if(isset($frm_details['add_pan_card_in_identity'])  && ($frm_details['add_pan_card_in_identity'] == 'on'))
                        {
                            if(!empty($frm_details['PANNumber']))
                            {

                                $result_identity_pan = $this->candidates_model->select_record_present('identity',array('id'),array('id_number'=>$frm_details['PANNumber']));
                             
                                if(empty($result_identity_pan[0]['id']))
                                {
                                    $this->load->model('identity_model');

                                    $mode_of_verification = $this->identity_model->get_mode_of_verification(array('entity' => $frm_details['entity'], 'package' => $frm_details['package'], 'tbl_clients_id' => $frm_details['clientid']));

                                    if(!empty($mode_of_verification))
                                    {
                                        $mode_of_verification_value = json_decode($mode_of_verification[0]['mode_of_verification']);
                                    } 

                                    if(isset($mode_of_verification_value->identity)) 
                                    { 
                                        $mode_of_verification_identity = $mode_of_verification_value->identity; 
                                    }
                                    else{

                                        $mode_of_verification_identity = "";
                                    }

                                    $assigned_user_id  = $this->get_reporting_manager_for_execituve($frm_details['clientid']);
                                     
                                    if(!empty($assigned_user_id))
                                    {            
                                        $assigned_user_ids = array_keys($assigned_user_id);
                                        $assigned_user_id = $assigned_user_ids[0];
                                    }
                                    else{
                                        $assigned_user_id = '';
                                    }

                                    $tat_day = $this->get_client_entity_package_tat_day(array('tbl_clients_id' => $frm_details['clientid'], 'entity' => $frm_details['entity'], 'package' => $frm_details['package']));
                               
                                    $get_holiday1 = $this->get_holiday();
                    
                                    $get_holiday = array_map('current', $get_holiday1);
                    
                                    $closed_date = getWorkingDays(convert_display_to_db_date($frm_details['caserecddate']), $get_holiday, $tat_day[0]['tat_identity']);
                    
                                    $field_array = array(
                                        'clientid'          => $frm_details['clientid'],
                                        'candsid'           => $id,
                                        'identity_com_ref'  => '',
                                        'iniated_date'      => convert_display_to_db_date($frm_details['caserecddate']),
                                        'doc_submited'      => "pan card",
                                        'id_number'         => $frm_details['PANNumber'],
                                        'mode_of_veri'      => $mode_of_verification_identity,
                                        'created_by'        => $this->user_info['id'],
                                        'created_on'        => date(DB_DATE_FORMAT),
                                        'modified_on'       => date(DB_DATE_FORMAT),
                                        'modified_by'       => $this->user_info['id'],
                                        'has_case_id'       => $assigned_user_id,
                                        'has_assigned_on'   => date(DB_DATE_FORMAT),
                                        'is_bulk_uploaded'  => 0,
                                        "due_date"          => $closed_date,
                                        "tat_status"        => "IN TAT",
                                    );
                    
                                  
                                    $result_identity = $this->identity_model->save(array_map('strtolower', $field_array));
                    
                                    $identity_com_ref = $this->identity_com_ref($result_identity);

                                    $user_activity_data = $this->common_model->user_actity_data(array('component' => "Identity",
                                    'ref_no' => $identity_com_ref, 'candidate_name' => $frm_details['CandidateName'], 'created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id'], 'activity_type' => 'Manual', 'action' => 'Add'));
            
                    
                                }
                            }
                        }

                        if(isset($frm_details['add_aadhar_card_in_identity'])  && ($frm_details['add_aadhar_card_in_identity'] == 'on'))
                        {
                            if(!empty($frm_details['AadharNumber']))
                            {
                                $result_identity_aadhar = $this->candidates_model->select_record_present('identity',array('id'),array('id_number'=>$frm_details['AadharNumber']));
                                
                                if(empty($result_identity_aadhar[0]['id']))
                                {
                                    $this->load->model('identity_model');

                                    $mode_of_verification = $this->identity_model->get_mode_of_verification(array('entity' => $frm_details['entity'], 'package' => $frm_details['package'], 'tbl_clients_id' => $frm_details['clientid']));

                                    if(!empty($mode_of_verification))
                                    {
                                        $mode_of_verification_value = json_decode($mode_of_verification[0]['mode_of_verification']);
                                    } 

                                    if(isset($mode_of_verification_value->identity)) 
                                    { 
                                        $mode_of_verification_identity = $mode_of_verification_value->identity; 
                                    }
                                    else{

                                        $mode_of_verification_identity = "";
                                    }

                                    $assigned_user_id  = $this->get_reporting_manager_for_execituve($frm_details['clientid']);
                                     
                                     
                                    if(!empty($assigned_user_id))
                                    {            
                                        $assigned_user_ids = array_keys($assigned_user_id);
                                        $assigned_user_id = $assigned_user_ids[0];
                                    }
                                    else{
                                        $assigned_user_id = '';
                                    }

                                    $tat_day = $this->get_client_entity_package_tat_day(array('tbl_clients_id' => $frm_details['clientid'], 'entity' => $frm_details['entity'], 'package' => $frm_details['package']));
                               
                                    $get_holiday1 = $this->get_holiday();
                    
                                    $get_holiday = array_map('current', $get_holiday1);
                    
                                    $closed_date = getWorkingDays(convert_display_to_db_date($frm_details['caserecddate']), $get_holiday, $tat_day[0]['tat_identity']);
                    
                                    $field_array = array(
                                        'clientid'          => $frm_details['clientid'],
                                        'candsid'           => $id,
                                        'identity_com_ref'  => '',
                                        'iniated_date'      => convert_display_to_db_date($frm_details['caserecddate']),
                                        'doc_submited'      => "aadhar card",
                                        'id_number'         => $frm_details['AadharNumber'],
                                        'mode_of_veri'      => $mode_of_verification_identity,
                                        'created_by'        => $this->user_info['id'],
                                        'created_on'        => date(DB_DATE_FORMAT),
                                        'modified_on'       => date(DB_DATE_FORMAT),
                                        'modified_by'       => $this->user_info['id'],
                                        'has_case_id'       => $assigned_user_id,
                                        'has_assigned_on'   => date(DB_DATE_FORMAT),
                                        'is_bulk_uploaded'  => 0,
                                        "due_date"          => $closed_date,
                                        "tat_status"        => "IN TAT",
                                    );

                    
                                    $result_identity = $this->identity_model->save(array_map('strtolower', $field_array));
                    
                                    $identity_com_ref = $this->identity_com_ref($result_identity);

                                    $user_activity_data = $this->common_model->user_actity_data(array('component' => "Identity",
                                    'ref_no' => $identity_com_ref, 'candidate_name' => $frm_details['CandidateName'], 'created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id'], 'activity_type' => 'Manual', 'action' => 'Add'));
            
                    
                                }   
                            }
                        }
                    
                        $user_activity_data = $this->common_model->user_actity_data(array('component' => "Candidate", 'ref_no' => $cmp_ref_no, 'candidate_name' => $frm_details['CandidateName'], 'created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id'], 'activity_type' => 'Manual', 'action' => 'Add'));

                        $file_upload_path = SITE_BASE_PATH . CANDIDATES . $frm_details['clientid'];

                        if (!folder_exist($file_upload_path)) {
                            mkdir($file_upload_path, 0777,true);
                        }

                        $config_array = array('file_upload_path' => $file_upload_path, 'file_permission' => 'jpeg|jpg|png|pdf|docx|xls|xlsx||tif|tiff', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 20000, 'file_id' => $id, 'component_name' => 'candidate_id');

                        if ($_FILES['attchments']['name'][0] != '') {
                            $config_array['files_count'] = count($_FILES['attchments']['name']);
                            $config_array['file_data'] = $_FILES['attchments'];
                            $config_array['type'] = 0;
                            $retunr_de = $this->file_upload_multiple($config_array);
                            if (!empty($retunr_de)) {
                                $this->common_model->common_insert_batch('candidate_files', $retunr_de['success']);
                            }
                        }

                        if ($frm_details['upload_capture_image']) {

                            $upload_capture_image = explode("||", $frm_details['upload_capture_image']);
                                
                            foreach ($upload_capture_image as $key => $value) {
                                $key = $key + 1;

                                $file_name = $cmp_ref_no . '-' .$key. '-' . date(UPLOAD_FILE_DATE_FORMAT) . '.png';
                                $uploadpath = $file_upload_path . '/' . $file_name;
                                $base64_to_jpeg = base64_to_jpeg($value, $uploadpath);
                                    if ($base64_to_jpeg) {
                                        log_message('error', 'Inside if condition success');
                                        $this->common_model->save('candidate_files', ['file_name' => $file_name, 'real_filename' => $file_name, 'candidate_id' => $id]);
                                    }

                            }
                        }

                        $json_array['status'] = SUCCESS_CODE;

                        $json_array['message'] = 'Candidate Created Successfully';

                        $json_array['redirect'] = ADMIN_SITE_URL . 'candidates/view_details/' . encrypt($id);

                    } else {

                        $json_array['status'] = ERROR_CODE;
                        $json_array['message'] = 'Something went wrong, please try again';
                        $json_array['redirect'] = ADMIN_SITE_URL . 'candidates';
                    }
                } catch (Exception $e) {
                    log_message('error', 'Error on Candidate::add_candidate');
                    log_message('error', $e->getMessage());
                }
            }
            echo_json($json_array);
        }
    }

    protected function BatchRefNumber($clientid)
    {
        try {
            $lists = $this->candidates_model->cmp_ref_no(array('clientid' => $clientid));
            if (!empty($lists)) {
                $name = $lists['A_I'] + 1;
            } else {
                $name = '1000';
            }
            return $name;
        } catch (Exception $e) {
            log_message('error', 'Candidates::BatchRefNumber');
            log_message('error', $e->getMessage());
        }
    }

    protected function identity_com_ref($insert_id)
    {
        $this->load->model('identity_model');

        $name = COMPONENT_REF_NO['IDENTITY'];

        $identitynumber = $name . $insert_id;

        $field_array = array('identity_com_ref' => $identitynumber);

        $update_auto_increament_id = $this->identity_model->update_auto_increament_value($field_array, array('id' => $insert_id));

        return $identitynumber;
    }


    protected function cmp_ref_no($insert_id)
    {
        try {
            log_message('error', 'cmp_ref_no');
            log_message('error', $insert_id);
            $name = COMPONENT_REF_NO['CANDIDATES'];
            $cmp_ref_no = $name . $insert_id;
            $field_array = array('cmp_ref_no' => $cmp_ref_no);
            $update_auto_increament_id = $this->candidates_model->update_auto_increament_value($field_array, array('id' => $insert_id));
            return $cmp_ref_no;
        } catch (Exception $e) {
            log_message('error', 'Error on Candidate::cmp_ref_no');
            log_message('error', $e->getMessage());
        }
    }

    public function get_package_list()
    {
        if ($this->input->is_ajax_request()) {
            $package_list = $this->get_entiry_package_list1(array('is_entity' => $this->input->post('entity'), 'is_entity_package' => 2));
            echo form_dropdown('package', $package_list, set_value('package', $this->input->post('selected_paclage')), 'class="form-control" id="package"');
        }
    }

    public function get_entity_list()
    {
        if ($this->input->is_ajax_request()) {
            $entity_list = $this->get_entiry_list1(array('tbl_client_id' => $this->input->post('clientid'), 'is_entity_package' => 1));
            echo form_dropdown('entity', $entity_list, set_value('package', $this->input->post('selected_entity')), 'class="form-control" id="entity"');
        }
    }

    public function generate_refno()
    {
        if ($this->input->is_ajax_request()) {
            $clientid = $this->input->post('clientid');
            $json_array['entity_list'] = $this->get_entiry_package_list(array('tbl_client_id' => $clientid, 'is_entity' => STATUS_DEACTIVE));
            $json_array['status'] = SUCCESS_CODE;
            $json_array['message'] = 'Successfully Generating';
            $json_array['cmp_ref_no'] = $this->cmp_ref_no($clientid);
            echo_json($json_array);
        }
    }

    public function is_client_ref_exists()
    {
        if ($this->input->is_ajax_request()) {
            $ClientRefNumber1 = $this->input->post('ClientRefNumber');
            $clientid = $this->input->post('clientid');
            $entity_name = $this->input->post('entity_name');
            $package_name = $this->input->post('package_name');
            $ClientRefNumber = str_replace(" ", "", $ClientRefNumber1);

            $lists = $this->candidates_model->select(true, array('id'), array('ClientRefNumber' => $ClientRefNumber, 'clientid' => $clientid, 'entity' => $entity_name, 'package' => $package_name, 'status' => '1'));

            if (empty($lists)) {
                echo 'true';
            } else {
                echo 'false';
            }
        }
    }

    public function primary_contact_no_check()
    {
        if ($this->input->is_ajax_request()) {
            $mobileno = $this->input->post('mobileno');
            $clientid = $this->input->post('clientid');
           
            $lists = $this->candidates_model->check_primary_no($mobileno,array('clientid'=>$clientid));
             
            if (!empty($lists)) {
                echo $lists[0]['cmp_ref_no'];
            }else{
                echo 'false';
            } 
        }

    }

    public function cands_view_datatable()
    {
        if ($this->permission['access_candidates_list_view'] == true) {

            if ($this->input->is_ajax_request()) {

                $candidates = $data_arry = array();
                $params = $_REQUEST;
                $columns = array('id', 'clientname', 'CandidateName', 'ClientRefNumber', 'cmp_ref_no', 'overallstatus', 'encry_id');

                $candidates = $this->candidates_model->get_all_cand_by_datatable($params, $columns);

                $totalRecords = count($this->candidates_model->get_all_cand_by_datatable_count($params, $columns));

                $x = 0;
                try {
                    foreach ($candidates as $candidate) {

                        $data_arry[$x]['id'] = $candidate['id'];
                        $data_arry[$x]['caserecddate'] = convert_db_to_display_date($candidate['caserecddate']);
                        $data_arry[$x]['clientname'] = ucwords($candidate['clientname']);
                        $data_arry[$x]['entity'] = $candidate['entity_name'];
                        $data_arry[$x]['package'] = $candidate['package_name'];
                        $data_arry[$x]['CandidateName'] = $candidate['CandidateName'];
                        $data_arry[$x]['ClientRefNumber'] = $candidate['ClientRefNumber'];
                        $data_arry[$x]['cmp_ref_no'] = $candidate['cmp_ref_no'];
                        $data_arry[$x]['overallstatus'] = $candidate['status_value'];

                        $data_arry[$x]['WIP'] = "NA";
                        $data_arry[$x]['Insufficiency'] = "NA";
                        $data_arry[$x]['Closed'] = "NA";
                        $data_arry[$x]['Closure_date'] = convert_db_to_display_date($candidate['overallclosuredate']);
                        $data_arry[$x]['Due_date'] = convert_db_to_display_date($candidate['due_date_candidate']);
                        $data_arry[$x]['TAT'] = $candidate['tat_status_candidate'];
                        $data_arry[$x]['Action'] = "";

                        $data_arry[$x]['encry_id'] = ADMIN_SITE_URL . "candidates/view_details/" . encrypt($candidate['id']);
                        $data_arry[$x]['edit_access'] = $this->permission['access_candidates_list_edit'];

                        $array_wip = array();
                        $array_insufficiency = array();
                        $array_closed = array();
                        $result_address_main = $this->candidates_model->get_addres_ver_status(array('candidates_info.id' => $candidate['id']));

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

                        $result_education_main = $this->candidates_model->get_education_ver_status(array('candidates_info.id' => $candidate['id']));

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

                        $result_employment_main = $this->candidates_model->get_employment_ver_status(array('candidates_info.id' => $candidate['id']));

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

                        $result_court_main = $this->candidates_model->get_court_ver_status(array('candidates_info.id' => $candidate['id']));

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

                        $result_pcc_main = $this->candidates_model->get_pcc_ver_status(array('candidates_info.id' => $candidate['id']));

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

                        $result_reference_main = $this->candidates_model->get_refver_ver_status(array('reference.candsid' => $candidate['id']));
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

                        $result_global_main = $this->candidates_model->get_globdbver_ver_status(array('candidates_info.id' => $candidate['id']));
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

                        $result_identity_main = $this->candidates_model->get_identity_ver_status(array('candidates_info.id' => $candidate['id']));

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

                        $result_credit_report_main = $this->candidates_model->get_credit_reports_ver_status(array('candidates_info.id' => $candidate['id']));
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

                        $result_drugs_main = $this->candidates_model->get_narcver_ver_status(array('candidates_info.id' => $candidate['id']));
                        if (!empty($result_drugs_main)) {

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

                        $data_arry[$x]['WIP'] = $array_wip;
                        $data_arry[$x]['Insufficiency'] = $array_insufficiency;
                        $data_arry[$x]['Closed'] = $array_closed;

                        $x++;
                    }
                } catch (Exception $e) {
                    log_message('error', 'Candidates::cands_view_datatable');
                    log_message('error', $e->getMessage());
                }
                $json_data = array("draw" => intval($params['draw']),
                    "recordsTotal" => intval($totalRecords),
                    "recordsFiltered" => intval($totalRecords),
                    "data" => $data_arry,
                );

                echo_json($json_data);
            }
        } else {
            $candidates = $data_arry = array();
            $json_data = array("draw" => intval(1),
                "recordsTotal" => "Not Permission",
                "recordsFiltered" => 0,
                "data" => $data_arry,
            );
            echo_json($json_data);
        }
    }

    public function data_table_cands_view_insufficiency()
    {
        if ($this->input->is_ajax_request()) {
            $params = $data_arry = $columns = array();
            $params = $_REQUEST;
            $columns = array('id', 'CandidateName', "clientname", "caserecddate", 'ClientRefNumber', 'cmp_ref_no', 'overallstatus', 'remarks', 'view', 'encry_id');
            try {
                $cands_results = $this->candidates_model->get_all_cand_with_search_insufficiency($params, $columns);
                $totalRecords = count($this->candidates_model->get_all_cand_with_search_insufficiency($params, $columns));
                $x = 0;

                foreach ($cands_results as $key => $cands_result) {

                    $data_arry[$x]['id'] = $x + 1;
                    $data_arry[$x]['CandidateName'] = ucwords(strtolower($cands_result['CandidateName']));
                    $data_arry[$x]['ClientName'] = ucwords(strtolower($cands_result['clientname']));
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

                    $data_arry[$x]['WIP'] = 'NA';
                    $data_arry[$x]['Insufficiency'] = 'NA';
                    $data_arry[$x]['Closed'] = 'NA';

                    $array_wip = array();
                    $array_insufficiency = array();
                    $array_closed = array();

                    $result_address_main = $this->candidates_model->get_addres_ver_status(array('candidates_info.id' => $cands_result['id']));

                    if (!empty($result_address_main)) {
                        $address_count = 1;
                        foreach ($result_address_main as $result_address) {
                            $address_component_status = ($result_address['var_filter_status'] != "") ? $result_address['var_filter_status'] : 'WIP';
                            if (($address_component_status == "WIP") || ($address_component_status == "wip")) {
                                array_push($array_wip, 'Address');
                            }
                            if (($address_component_status == "Insufficiency") || ($address_component_status == "insufficiency")) {
                                array_push($array_insufficiency, 'Address');
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

                            $education_component_status = ($result_education['var_filter_status'] != "") ? $result_education['var_filter_status'] : 'WIP';

                            if (($education_component_status == "WIP") || ($education_component_status == "wip")) {
                                array_push($array_wip, 'Education');
                            }
                            if (($education_component_status == "Insufficiency") || ($education_component_status == "insufficiency")) {
                                array_push($array_insufficiency, 'Education');
                            }
                            if (($education_component_status == "Closed") || ($education_component_status == "closed")) {
                                array_push($array_closed, 'Education');
                            }
                            $education_count++;
                        }
                    }

                    $result_employment_main = $this->candidates_model->get_employment_ver_status(array('candidates_info.id' => $cands_result['id']));

                    if (!empty($result_employment_main)) {
                        $employment_count = 1;
                        foreach ($result_employment_main as $result_employment) {

                            $employment_component_status = ($result_employment['var_filter_status'] != "") ? $result_employment['var_filter_status'] : 'WIP';

                            if (($employment_component_status == "WIP") || ($employment_component_status == "wip")) {
                                array_push($array_wip, "Employment");
                            }
                            if (($employment_component_status == "Insufficiency") || ($employment_component_status == "insufficiency")) {
                                array_push($array_insufficiency, 'Employment');
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
                            $court_component_status = ($result_court['var_filter_status'] != "") ? $result_court['var_filter_status'] : 'WIP';
                            if (($court_component_status == "WIP") || ($court_component_status == "wip")) {
                                array_push($array_wip, "Court");
                            }
                            if (($court_component_status == "Insufficiency") || ($court_component_status == "insufficiency")) {
                                array_push($array_insufficiency, 'Court');
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

                            $pcc_component_status = ($result_pcc['var_filter_status'] != "") ? $result_pcc['var_filter_status'] : 'WIP';

                            if (($pcc_component_status == "WIP") || ($pcc_component_status == "wip")) {
                                array_push($array_wip, "PCC");
                            }
                            if (($pcc_component_status == "Insufficiency") || ($pcc_component_status == "insufficiency")) {
                                array_push($array_insufficiency, 'PCC');
                            }
                            if (($pcc_component_status == "Closed") || ($pcc_component_status == "closed")) {
                                array_push($array_closed, 'PCC');
                            }
                            $pcc_count++;
                        }
                    }

                    $result_reference_main = $this->candidates_model->get_refver_ver_status(array('reference.candsid' => $cands_result['id']));

                    if (!empty($result_reference_main)) {
                        $reference_count = 1;
                        foreach ($result_reference_main as $result_reference) {

                            $reference_component_status = ($result_reference['var_filter_status'] != "") ? $result_reference['var_filter_status'] : 'WIP';

                            if (($reference_component_status == "WIP") || ($reference_component_status == "wip")) {
                                array_push($array_wip, "Reference");
                            }
                            if (($reference_component_status == "Insufficiency") || ($reference_component_status == "insufficiency")) {
                                array_push($array_insufficiency, 'Reference');
                            }
                            if (($reference_component_status == "Closed") || ($reference_component_status == "closed")) {
                                array_push($array_closed, 'Reference');
                            }
                            $reference_count++;
                        }
                    }

                    $result_global_main = $this->candidates_model->get_globdbver_ver_status(array('candidates_info.id' => $cands_result['id']));
                    if (!empty($result_global_main)) {

                        $global_count = 1;

                        foreach ($result_global_main as $result_global) {

                            $globaldb_component_status = ($result_global['var_filter_status'] != "") ? $result_global['var_filter_status'] : 'WIP';

                            if (($globaldb_component_status == "WIP") || ($globaldb_component_status == "wip")) {
                                array_push($array_wip, "Global");
                            }
                            if (($globaldb_component_status == "Insufficiency") || ($globaldb_component_status == "insufficiency")) {
                                array_push($array_insufficiency, 'Global');
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
                                array_push($array_insufficiency, 'Identity');
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
                                array_push($array_insufficiency, 'Credit Report');
                            }
                            if (($credit_report_component_status == "Closed") || ($credit_report_component_status == "closed")) {
                                array_push($array_closed, 'Credit Report');
                            }

                            $credit_report_count++;
                        }
                    }

                    $result_drugs_main = $this->candidates_model->get_narcver_ver_status(array('candidates_info.id' => $cands_result['id']));

                    if (!empty($result_drugs_main)) {

                        $drugs_count = 1;
                        foreach ($result_drugs_main as $result_drugs) {

                            $drugs_component_status = ($result_drugs['var_filter_status'] != "") ? $result_drugs['var_filter_status'] : 'WIP';

                            if (($drugs_component_status == "WIP") || ($drugs_component_status == "wip")) {
                                array_push($array_wip, "Drugs");
                            }
                            if (($drugs_component_status == "Insufficiency") || ($drugs_component_status == "insufficiency")) {
                                array_push($array_insufficiency, 'Drugs');
                            }
                            if (($drugs_component_status == "Closed") || ($drugs_component_status == "closed")) {
                                array_push($array_closed, 'Drugs');
                            }
                            $drugs_count++;
                        }
                    }

                    $data_arry[$x]['WIP'] = $array_wip;
                    $data_arry[$x]['Insufficiency'] = $array_insufficiency;
                    $data_arry[$x]['Closed'] = $array_closed;
                    $x++;
                }

                $json_data = array(
                    "draw" => intval($params['draw']),
                    "recordsTotal" => intval($totalRecords),
                    "recordsFiltered" => intval($totalRecords),
                    "data" => $data_arry,
                );
                echo_json($json_data);
            } catch (Exception $e) {
                log_message('error', 'Error on Candidate::data_table_cands_view_insufficiency');
                log_message('error', $e->getMessage());
            }
        }
    }

    public function view_details($cand_id = '')
    {
        $candidate_details = $this->candidates_model->select(true, array(), array('id' => decrypt($cand_id)));
        if ($cand_id && !empty($candidate_details)) {
            try {
                $this->load->model('clients_model');

                $data['status'] = status_frm_db();

                $data['states'] = $this->get_states();

                $data['clients'] = $this->get_clients();

                $client_manager_id = $this->candidates_model->get_client_manager_id(array('id' => $candidate_details['clientid']));

                $data['client_name'] = $client_manager_id[0]['clientname'];

                $data['client_manager_email_id'] = $this->candidates_model->get_client_manager_email_id($client_manager_id[0]['clientmgr']);

                $reportingmanager_user = $this->candidates_model->get_reporting_manager_id();

                $reportingmanager_id = $reportingmanager_user[0]['reporting_manager'];

                $reportingmanager = $this->candidates_model->get_reporting_manager_email_id($reportingmanager_id);
                $data['reporting_manager_email'] = $reportingmanager[0];

                $data['client_components'] = $this->clients_model->get_entitypackages(array('entity' => $candidate_details['entity'], 'package' => $candidate_details['package']))[0];

                $data['header_title'] = 'Candidate Details Edit';

                $data['interiam_final_report'] = (($candidate_details['overallstatus'] == '3' || $candidate_details['overallstatus'] == '4' || $candidate_details['overallstatus'] == '6' || $candidate_details['overallstatus'] == '7' || $candidate_details['overallstatus'] == '8') && ($candidate_details['final_qc'] == "final qc pending" || $candidate_details['final_qc'] == "Final QC Approve") ? '1' : '2');

                $data['attachments'] = $this->candidates_model->select_file(array('id', 'file_name', 'real_filename', 'type'), array('candidate_id' => decrypt($cand_id), 'status' => 1));

                $data['candidate_details'] = $candidate_details;

                $this->load->view('admin/header', $data);

                $this->load->view('admin/candidates_edit');

                $this->load->view('admin/footer');
            } catch (Exception $e) {
                log_message('error', 'Error on Candidate::view_details');
                log_message('error', $e->getMessage());
            }

        } else {
            show_404();
        }
    }

    public function remove_uploaded_file($id)
    {
        $json_array = array();
        if ($this->input->is_ajax_request()) {
            if ($this->permission['access_candidates_list_file_delete'] == true) {

                $result = $this->candidates_model->save_update_candidate_files(array('status' => 2), array('id' => $id));
                if ($result) {
                    $json_array['status'] = SUCCESS_CODE;
                    $json_array['message'] = 'Attachment removed successfully, please refresh the page';
                } else {
                    $json_array['status'] = ERROR_CODE;
                    $json_array['message'] = 'Something went worong, please try again';
                }

            } else {
                permission_denied();
            }
            echo_json($json_array);
        }
    }

    public function undo_deleted_uploaded_file($id)
    {
        $json_array['status'] = ERROR_CODE;
        $json_array['message'] = 'Something went worong, please try again';
        if ($this->input->is_ajax_request()) {
            $result = $this->candidates_model->save_update_candidate_files(array('status' => 1), array('id' => $id));
            if ($result) {
                $json_array['status'] = SUCCESS_CODE;
                $json_array['message'] = 'Deleted File UNDO successfully, please refresh the page';
            }
        }
        echo_json($json_array);
    }

    public function view_details_log($cand_id, $lesscand, $cand_log_id)
    {
        $candidate_details = $this->candidates_model->select_log(true, array(), array('id' => $cand_id, 'candidates_info_id' => $cand_log_id));

        $candidate_details1 = $this->candidates_model->select_log(true, array(), array('id' => $lesscand, 'candidates_info_id' => $cand_log_id));

        if ($cand_id && !empty($candidate_details)) {

            $this->load->model('clients_model');
            $data['status'] = status_frm_db();
            $data['states'] = $this->get_states();
            $data['entity_list'] = $this->get_entiry_package_list();
            $data['package_list'] = $this->get_package_list();
            $data['clients'] = $this->get_clients();
            $data['client_components'] = $this->clients_model->get_entitypackages(array('entity' => $candidate_details['entity'], 'package' => $candidate_details['package']))[0];
            $data['header_title'] = 'Candidate Details Edit';
            $data['candidate_details'] = $candidate_details;
            $data['candidate_details1'] = $candidate_details1;
            $this->load->view('admin/candidate_edit_view_log', $data);
        } else {
            show_404();
        }
    }

    public function candidates_update()
    {
        if ($this->input->is_ajax_request() && $this->permission['access_candidates_list_edit']) {
            $this->form_validation->set_rules('update_id', 'ID', 'required');

            $this->form_validation->set_rules('entity', 'Entity', 'required');

            $this->form_validation->set_rules('package', 'Package', 'required');

            $this->form_validation->set_rules('clientid', 'Client', 'required');

            $this->form_validation->set_rules('caserecddate', 'Case received', 'required');

            $this->form_validation->set_rules('ClientRefNumber', 'Client Ref Number', 'required');

            $this->form_validation->set_rules('CandidateName', 'Candidate Name', 'required');

            $this->form_validation->set_rules('CandidatesContactNumber', 'Primary Cantact', 'required');

            if ($this->form_validation->run() == false) {
                $json_array['status'] = ERROR_CODE;
                $json_array['message'] = validation_errors('', '');
            } else {
                try {
                    $frm_details = $this->input->post();

                    $client_ref_number = str_replace(" ", "", $frm_details['ClientRefNumber']);

                    $id = decrypt($frm_details['update_id']);

                    if ($id == $frm_details['cp_update_id']) {
                        $candidate_client_id = $this->candidates_model->select(true, array('clientid'), array('id' => $id));

                        if ($candidate_client_id['clientid'] != $frm_details['clientid']) {
                            $candidate_file_name = $this->candidates_model->select_file(array('file_name'), array('candidate_id' => $id, 'status' => 1));
                            foreach ($candidate_file_name as $key => $value_file) {
                                $file_upload_path_client = SITE_BASE_PATH . CANDIDATES . $candidate_client_id['clientid'];
                                $file_upload_path = SITE_BASE_PATH . CANDIDATES . $frm_details['clientid'];

                                if (!folder_exist($file_upload_path)) {
                                    mkdir($file_upload_path, 0777, true);
                                }
                                rename($file_upload_path_client . '/' . $value_file['file_name'], $file_upload_path . '/' . $value_file['file_name']);
                            }

                            $this->load->model('Addressver_model');
                            $result_address = $this->Addressver_model->save(array('clientid' => $frm_details['clientid']), array('candsid' => $id));
                            $result_address_result = $this->Addressver_model->save_update(array('clientid' => $frm_details['clientid']), array('candsid' => $id));
                            $result_address_ver_result = $this->Addressver_model->save_update_ver(array('clientid' => $frm_details['clientid']), array('candsid' => $id));

                            $address_file_name = $this->Addressver_model->select_candidate_from_file($id);

                            foreach ($address_file_name as $key => $value_file) {
                                $file_upload_path_address_old_client = SITE_BASE_PATH . ADDRESS . $candidate_client_id['clientid'];
                                $file_upload_path_address_new_client = SITE_BASE_PATH . ADDRESS . $frm_details['clientid'];

                                if (!folder_exist($file_upload_path_address_new_client)) {
                                    mkdir($file_upload_path_address_new_client, 0777);
                                }
                                rename($file_upload_path_address_old_client . '/' . $value_file['file_name'], $file_upload_path_address_new_client . '/' . $value_file['file_name']);
                            }

                            $this->load->model('Employment_model');
                            $result_employment = $this->Employment_model->save(array('clientid' => $frm_details['clientid']), array('candsid' => $id));
                            $result_employment_result = $this->Employment_model->save_update_empt_ver_result(array('clientid' => $frm_details['clientid']), array('candsid' => $id));
                            $result_employment_ver_result = $this->Employment_model->save_update_empt_ver_result_employment(array('clientid' => $frm_details['clientid']), array('candsid' => $id));

                            $employment_file_name = $this->Employment_model->select_candidate_from_file($id);

                            foreach ($employment_file_name as $key => $value_file) {
                                $file_upload_path_employment_old_client = SITE_BASE_PATH . EMPLOYMENT . $candidate_client_id['clientid'];
                                $file_upload_path_employment_new_client = SITE_BASE_PATH . EMPLOYMENT . $frm_details['clientid'];

                                if (!folder_exist($file_upload_path_employment_new_client)) {
                                    mkdir($file_upload_path_employment_new_client, 0777);
                                }
                                rename($file_upload_path_employment_old_client . '/' . $value_file['file_name'], $file_upload_path_employment_new_client . '/' . $value_file['file_name']);
                            }

                            $this->load->model('Education_model');
                            $result_education = $this->Education_model->save(array('clientid' => $frm_details['clientid']), array('candsid' => $id));
                            $result_education_result = $this->Education_model->education_verfstatus_update(array('clientid' => $frm_details['clientid']), array('candsid' => $id));
                            $result_education_ver_result = $this->Education_model->save_update_result_education(array('clientid' => $frm_details['clientid']), array('candsid' => $id));

                            $education_file_name = $this->Education_model->select_candidate_from_file($id);

                            foreach ($education_file_name as $key => $value_file) {
                                $file_upload_path_education_old_client = SITE_BASE_PATH . EDUCATION . $candidate_client_id['clientid'];
                                $file_upload_path_education_new_client = SITE_BASE_PATH . EDUCATION . $frm_details['clientid'];

                                if (!folder_exist($file_upload_path_education_new_client)) {
                                    mkdir($file_upload_path_education_new_client, 0777);
                                }
                                rename($file_upload_path_education_old_client . '/' . $value_file['file_name'], $file_upload_path_education_new_client . '/' . $value_file['file_name']);
                            }

                            $this->load->model('Reference_verificatiion_model');
                            $result_reference = $this->Reference_verificatiion_model->save(array('clientid' => $frm_details['clientid']), array('candsid' => $id));
                            $result_reference_result = $this->Reference_verificatiion_model->reference_verfstatus_update(array('clientid' => $frm_details['clientid']), array('candsid' => $id));
                            $result_reference_ver_result = $this->Reference_verificatiion_model->save_update_ver_result_refrence(array('clientid' => $frm_details['clientid']), array('candsid' => $id));

                            $reference_file_name = $this->Reference_verificatiion_model->select_candidate_from_file($id);

                            foreach ($reference_file_name as $key => $value_file) {
                                $file_upload_path_reference_old_client = SITE_BASE_PATH . REFERENCES . $candidate_client_id['clientid'];
                                $file_upload_path_reference_new_client = SITE_BASE_PATH . REFERENCES . $frm_details['clientid'];

                                if (!folder_exist($file_upload_path_reference_new_client)) {
                                    mkdir($file_upload_path_reference_new_client, 0777);
                                }
                                rename($file_upload_path_reference_old_client . '/' . $value_file['file_name'], $file_upload_path_reference_new_client . '/' . $value_file['file_name']);
                            }

                            $this->load->model('Court_verificatiion_model');
                            $result_court = $this->Court_verificatiion_model->save(array('clientid' => $frm_details['clientid']), array('candsid' => $id));
                            $result_court_result = $this->Court_verificatiion_model->save_update_result(array('clientid' => $frm_details['clientid']), array('candsid' => $id));
                            $result_court_ver_result = $this->Court_verificatiion_model->save_update_result_court(array('clientid' => $frm_details['clientid']), array('candsid' => $id));

                            $court_file_name = $this->Court_verificatiion_model->select_candidate_from_file($id);

                            foreach ($court_file_name as $key => $value_file) {
                                $file_upload_path_court_old_client = SITE_BASE_PATH . COURT_VERIFICATION . $candidate_client_id['clientid'];
                                $file_upload_path_court_new_client = SITE_BASE_PATH . COURT_VERIFICATION . $frm_details['clientid'];

                                if (!folder_exist($file_upload_path_court_new_client)) {
                                    mkdir($file_upload_path_court_new_client, 0777);
                                }
                                rename($file_upload_path_court_old_client . '/' . $value_file['file_name'], $file_upload_path_court_new_client . '/' . $value_file['file_name']);
                            }

                            $this->load->model('Global_database_model');
                            $result_global = $this->Global_database_model->save(array('clientid' => $frm_details['clientid']), array('candsid' => $id));
                            $result_global_result = $this->Global_database_model->save_update_initiated_date_global_database(array('clientid' => $frm_details['clientid']), array('candsid' => $id));
                            $result_global_ver_result = $this->Global_database_model->save_update_result_globerver(array('clientid' => $frm_details['clientid']), array('candsid' => $id));

                            $global_file_name = $this->Global_database_model->select_candidate_from_file($id);

                            foreach ($global_file_name as $key => $value_file) {
                                $file_upload_path_global_old_client = SITE_BASE_PATH . GLOBAL_DB . $candidate_client_id['clientid'];
                                $file_upload_path_global_new_client = SITE_BASE_PATH . GLOBAL_DB . $frm_details['clientid'];

                                if (!folder_exist($file_upload_path_global_new_client)) {
                                    mkdir($file_upload_path_global_new_client, 0777);
                                }
                                rename($file_upload_path_global_old_client . '/' . $value_file['file_name'], $file_upload_path_global_new_client . '/' . $value_file['file_name']);
                            }

                            $this->load->model('Pcc_verificatiion_model');
                            $result_pcc = $this->Pcc_verificatiion_model->save(array('clientid' => $frm_details['clientid']), array('candsid' => $id));
                            $result_pcc_result = $this->Pcc_verificatiion_model->save_update_initiated_date_pcc(array('clientid' => $frm_details['clientid']), array('candsid' => $id));
                            $result_pcc_ver_result = $this->Pcc_verificatiion_model->save_update_result_pcc(array('clientid' => $frm_details['clientid']), array('candsid' => $id));

                            $pcc_file_name = $this->Pcc_verificatiion_model->select_candidate_from_file($id);

                            foreach ($pcc_file_name as $key => $value_file) {
                                $file_upload_path_pcc_old_client = SITE_BASE_PATH . PCC . $candidate_client_id['clientid'];
                                $file_upload_path_pcc_new_client = SITE_BASE_PATH . PCC . $frm_details['clientid'];

                                if (!folder_exist($file_upload_path_pcc_new_client)) {
                                    mkdir($file_upload_path_pcc_new_client, 0777);
                                }
                                rename($file_upload_path_pcc_old_client . '/' . $value_file['file_name'], $file_upload_path_pcc_new_client . '/' . $value_file['file_name']);
                            }

                            $this->load->model('Identity_model');
                            $result_identity = $this->Identity_model->save(array('clientid' => $frm_details['clientid']), array('candsid' => $id));
                            $result_identity_result = $this->Identity_model->save_update_ver_result(array('clientid' => $frm_details['clientid']), array('candsid' => $id));
                            $result_identity_ver_result = $this->Identity_model->save_update_result_identity(array('clientid' => $frm_details['clientid']), array('candsid' => $id));

                            $identity_file_name = $this->Identity_model->select_candidate_from_file($id);

                            foreach ($identity_file_name as $key => $value_file) {
                                $file_upload_path_identity_old_client = SITE_BASE_PATH . IDENTITY . $candidate_client_id['clientid'];
                                $file_upload_path_identity_new_client = SITE_BASE_PATH . IDENTITY . $frm_details['clientid'];

                                if (!folder_exist($file_upload_path_identity_new_client)) {
                                    mkdir($file_upload_path_identity_new_client, 0777);
                                }
                                rename($file_upload_path_identity_old_client . '/' . $value_file['file_name'], $file_upload_path_identity_new_client . '/' . $value_file['file_name']);
                            }

                            $this->load->model('Credit_report_model');
                            $result_credit = $this->Credit_report_model->save(array('clientid' => $frm_details['clientid']), array('candsid' => $id));
                            $result_credit_result = $this->Credit_report_model->save_update_ver_result(array('clientid' => $frm_details['clientid']), array('candsid' => $id));
                            $result_credit_ver_result = $this->Credit_report_model->save_update_result_credit(array('clientid' => $frm_details['clientid']), array('candsid' => $id));

                            $credit_report_file_name = $this->Credit_report_model->select_candidate_from_file($id);

                            foreach ($credit_report_file_name as $key => $value_file) {
                                $file_upload_path_credit_report_old_client = SITE_BASE_PATH . CREDIT_REPORT . $candidate_client_id['clientid'];
                                $file_upload_path_credit_report_new_client = SITE_BASE_PATH . CREDIT_REPORT . $frm_details['clientid'];

                                if (!folder_exist($file_upload_path_credit_report_new_client)) {
                                    mkdir($file_upload_path_credit_report_new_client, 0777);
                                }
                                rename($file_upload_path_credit_report_old_client . '/' . $value_file['file_name'], $file_upload_path_credit_report_new_client . '/' . $value_file['file_name']);
                            }

                            $this->load->model('Drug_verificatiion_model');
                            $result_drugs = $this->Drug_verificatiion_model->save(array('clientid' => $frm_details['clientid']), array('candsid' => $id));
                            $result_drugs_result = $this->Drug_verificatiion_model->save_update_result(array('clientid' => $frm_details['clientid']), array('candsid' => $id));
                            $result_drugs_ver_result = $this->Drug_verificatiion_model->save_update_result_drugs(array('clientid' => $frm_details['clientid']), array('candsid' => $id));

                            $drugs_file_name = $this->Drug_verificatiion_model->select_candidate_from_file($id);

                            foreach ($drugs_file_name as $key => $value_file) {
                                $file_upload_path_drugs_old_client = SITE_BASE_PATH . DRUGS . $candidate_client_id['clientid'];
                                $file_upload_path_drugs_new_client = SITE_BASE_PATH . DRUGS . $frm_details['clientid'];

                                if (!folder_exist($file_upload_path_drugs_new_client)) {
                                    mkdir($file_upload_path_drugs_new_client, 0777);
                                }
                                rename($file_upload_path_drugs_old_client . '/' . $value_file['file_name'], $file_upload_path_drugs_new_client . '/' . $value_file['file_name']);
                            }
                        }
                        try {
                            $file_upload_path = SITE_BASE_PATH . CANDIDATES . $frm_details['clientid'];

                            $config_array = array('file_upload_path' => $file_upload_path, 'file_permission' => 'jpeg|jpg|png|pdf|docx|xls|xlsx|tif|tiff', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 20000, 'file_id' => $id, 'component_name' => 'candidate_id');

                            if (!folder_exist($file_upload_path)) {
                                mkdir($file_upload_path, 0777, true);
                            } else if (!is_writable($file_upload_path)) {
                                array_push($error_msgs, 'Problem while uploading');
                            }

                            if ($_FILES['attchments']['name'][0] != '') {
                                $config_array['files_count'] = count($_FILES['attchments']['name']);
                                $config_array['file_data'] = $_FILES['attchments'];
                                $config_array['type'] = 0;
                                $retunr_de = $this->file_upload_multiple($config_array);
                                if (!empty($retunr_de)) {
                                    $this->common_model->common_insert_batch('candidate_files', $retunr_de['success']);
                                }
                            }

                            if ($frm_details['upload_capture_image']) {

                                $upload_capture_image = explode("||", $frm_details['upload_capture_image']);
                                
                                foreach ($upload_capture_image as $key => $value) {
                                    $key = $key + 1;

                                    $file_name = $frm_details['cmp_ref_no'] . '-' .$key. '-' . date(UPLOAD_FILE_DATE_FORMAT) . '.png';
                                    $uploadpath = $file_upload_path . '/' . $file_name;
                                    $base64_to_jpeg = base64_to_jpeg($value, $uploadpath);
                                    if ($base64_to_jpeg) {
                                        log_message('error', 'Inside if condition success');
                                        $this->common_model->save('candidate_files', ['file_name' => $file_name, 'real_filename' => $file_name, 'candidate_id' => $id]);
                                    }

                                }
                            }
                        } catch (Exception $e) {
                            log_message('error', 'Candidate::copy to clipboard');
                            log_message('error', $e->getMessage());
                        }

                        $field_array = array('clientid' => $frm_details['clientid'],
                            'entity' => $frm_details['entity'],
                            'package' => $frm_details['package'],
                            'caserecddate' => convert_display_to_db_date($frm_details['caserecddate']),
                            'ClientRefNumber' => $client_ref_number,
                            'CandidateName' => $frm_details['CandidateName'],
                            'DateofBirth' => convert_display_to_db_date($frm_details['DateofBirth']),
                            "gender" => $frm_details['gender'],
                            "grade" => $frm_details['grade'],
                            "prasent_address" => $frm_details['prasent_address'],
                            "cands_city" => $frm_details['cands_city'],
                            "cands_state" => $frm_details['cands_state'],
                            "cands_pincode" => $frm_details['cands_pincode'],
                            "cands_country" => 'India',
                            'NameofCandidateFather' => $frm_details['NameofCandidateFather'],
                            'MothersName' => $frm_details['MothersName'],
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
                            'cands_email_id' => $frm_details['cands_email_id'],
                            //'BatchNumber' => $frm_details['BatchNumber'],
                            'branch_name' => $frm_details['branch_name'],
                            'remarks' => $frm_details['remarks'],
                            'overallclosuredate' => convert_display_to_db_date($frm_details['overallclosuredate']),
                            'overallstatus' => $frm_details['overallstatus'],
                            //'region'       => $frm_details['region'],
                            'modified_on' => date(DB_DATE_FORMAT),
                            'modified_by' => $this->user_info['id'],
                            'status' => STATUS_ACTIVE,
                        );
                        //$field_array = array_map('strtolower', $field_array);

                        ($this->permission['access_candidates_list_edit']) ? $field_array['build_date'] = convert_display_to_db_date($frm_details['build_date']) : '';

                        $result = $this->candidates_model->save($field_array, array('id' => $id));

                        if ($result) {
                            $field_array = array('clientid' => $frm_details['clientid'],
                                'entity' => $frm_details['entity'],
                                'package' => $frm_details['package'],
                                'caserecddate' => convert_display_to_db_date($frm_details['caserecddate']),
                                'ClientRefNumber' => $frm_details['ClientRefNumber'],
                                'cmp_ref_no' => $frm_details['cmp_ref_no'],
                                'CandidateName' => $frm_details['CandidateName'],
                                'DateofBirth' => convert_display_to_db_date($frm_details['DateofBirth']),
                                "gender" => $frm_details['gender'],
                                "prasent_address" => $frm_details['prasent_address'],
                                "cands_city" => $frm_details['cands_city'],
                                "cands_state" => $frm_details['cands_state'],
                                "cands_pincode" => $frm_details['cands_pincode'],
                                "cands_country" => 'India',
                                'NameofCandidateFather' => $frm_details['NameofCandidateFather'],
                                'MothersName' => $frm_details['MothersName'],
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
                                'cands_email_id' => $frm_details['cands_email_id'],
                                // 'BatchNumber' => $frm_details['BatchNumber'],
                                'branch_name' => $frm_details['branch_name'],
                                'remarks' => $frm_details['remarks'],
                                'overallclosuredate' => convert_display_to_db_date($frm_details['overallclosuredate']),
                                'overallstatus' => $frm_details['overallstatus'],
                                //'region'       => $frm_details['region'],
                                'created_on' => date(DB_DATE_FORMAT),
                                'created_by' => $this->user_info['id'],
                                'status' => STATUS_ACTIVE,
                                'candidates_info_id' => $id,
                            );

                            $result_candidate = $this->candidates_model->save_candidate($field_array);

                                
                            if(isset($frm_details['add_pan_card_in_identity'])  && ($frm_details['add_pan_card_in_identity'] == 'on'))
                            {
                                if(!empty($frm_details['PANNumber']))
                                {
                                       
                                    $result_identity_pan = $this->candidates_model->select_record_present('identity',array('id'),array('id_number'=>$frm_details['PANNumber']));
                                
                                    if(empty($result_identity_pan[0]['id']))
                                    {
                                        $this->load->model('identity_model');

                                        $mode_of_verification = $this->identity_model->get_mode_of_verification(array('entity' => $frm_details['entity'], 'package' => $frm_details['package'], 'tbl_clients_id' => $frm_details['clientid']));

                                        if(!empty($mode_of_verification))
                                        {
                                            $mode_of_verification_value = json_decode($mode_of_verification[0]['mode_of_verification']);
                                        } 
    
                                        if(isset($mode_of_verification_value->identity)) 
                                        { 
                                            $mode_of_verification_identity = $mode_of_verification_value->identity; 
                                        }
                                        else{
    
                                            $mode_of_verification_identity = "";
                                        }
    
                                        $assigned_user_id  = $this->get_reporting_manager_for_execituve($frm_details['clientid']);
                                         
                                     
                                        if(!empty($assigned_user_id))
                                        {            
                                            $assigned_user_ids = array_keys($assigned_user_id);
                                            $assigned_user_id = $assigned_user_ids[0];
                                        }
                                        else{
                                            $assigned_user_id = '';
                                        }
    

                                        $tat_day = $this->get_client_entity_package_tat_day(array('tbl_clients_id' => $frm_details['clientid'], 'entity' => $frm_details['entity'], 'package' => $frm_details['package']));
                                
                                        $get_holiday1 = $this->get_holiday();
                        
                                        $get_holiday = array_map('current', $get_holiday1);
                        
                                        $closed_date = getWorkingDays(convert_display_to_db_date($frm_details['caserecddate']), $get_holiday, $tat_day[0]['tat_identity']);
                        
                                        $field_array = array(
                                            'clientid'          => $frm_details['clientid'],
                                            'candsid'           => $id,
                                            'identity_com_ref'  => '',
                                            'iniated_date'      => convert_display_to_db_date($frm_details['caserecddate']),
                                            'doc_submited'      => "pan card",
                                            'id_number'         => $frm_details['PANNumber'],
                                            'mode_of_veri'      => $mode_of_verification_identity,
                                            'created_by'        => $this->user_info['id'],
                                            'created_on'        => date(DB_DATE_FORMAT),
                                            'modified_on'       => date(DB_DATE_FORMAT),
                                            'modified_by'       => $this->user_info['id'],
                                            'has_case_id'       => $assigned_user_id,
                                            'has_assigned_on'   => date(DB_DATE_FORMAT),
                                            'is_bulk_uploaded'  => 0,
                                            "due_date"          => $closed_date,
                                            "tat_status"        => "IN TAT",
                                        );
                        
                                    
                                        $result_identity = $this->identity_model->save($field_array);
                        
                                        $identity_com_ref = $this->identity_com_ref($result_identity);

                                        $user_activity_data = $this->common_model->user_actity_data(array('component' => "Identity",
                                        'ref_no' => $identity_com_ref, 'candidate_name' => $frm_details['CandidateName'], 'created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id'], 'activity_type' => 'Manual', 'action' => 'Add'));
                
                        
                                    }
                                }
                            }

                            if(isset($frm_details['add_aadhar_card_in_identity'])  && ($frm_details['add_aadhar_card_in_identity'] == 'on'))
                            {
                                if(!empty($frm_details['AadharNumber']))
                                {
                                    $result_identity_aadhar = $this->candidates_model->select_record_present('identity',array('id'),array('id_number'=>$frm_details['AadharNumber']));
                                    
                                    if(empty($result_identity_aadhar[0]['id']))
                                    {
                                        $this->load->model('identity_model');

                                        $mode_of_verification = $this->identity_model->get_mode_of_verification(array('entity' => $frm_details['entity'], 'package' => $frm_details['package'], 'tbl_clients_id' => $frm_details['clientid']));

                                        if(!empty($mode_of_verification))
                                        {
                                            $mode_of_verification_value = json_decode($mode_of_verification[0]['mode_of_verification']);
                                        } 
    
                                        if(isset($mode_of_verification_value->identity)) 
                                        { 
                                            $mode_of_verification_identity = $mode_of_verification_value->identity; 
                                        }
                                        else{
    
                                            $mode_of_verification_identity = "";
                                        }
    
                                        $assigned_user_id  = $this->get_reporting_manager_for_execituve($frm_details['clientid']);

                                        if(!empty($assigned_user_id))
                                        {            
                                            $assigned_user_ids = array_keys($assigned_user_id);
                                            $assigned_user_id = $assigned_user_ids[0];
                                        }
                                        else{
                                            $assigned_user_id = '';
                                        }

                                        $tat_day = $this->get_client_entity_package_tat_day(array('tbl_clients_id' => $frm_details['clientid'], 'entity' => $frm_details['entity'], 'package' => $frm_details['package']));
                                
                                        $get_holiday1 = $this->get_holiday();
                        
                                        $get_holiday = array_map('current', $get_holiday1);
                        
                                        $closed_date = getWorkingDays(convert_display_to_db_date($frm_details['caserecddate']), $get_holiday, $tat_day[0]['tat_identity']);
                        
                                        $field_array = array(
                                            'clientid'          => $frm_details['clientid'],
                                            'candsid'           => $id,
                                            'identity_com_ref'  => '',
                                            'iniated_date'      => convert_display_to_db_date($frm_details['caserecddate']),
                                            'doc_submited'      => "aadhar card",
                                            'id_number'         => $frm_details['AadharNumber'],
                                            'mode_of_veri'      => $mode_of_verification_identity,
                                            'created_by'        => $this->user_info['id'],
                                            'created_on'        => date(DB_DATE_FORMAT),
                                            'modified_on'       => date(DB_DATE_FORMAT),
                                            'modified_by'       => $this->user_info['id'],
                                            'has_case_id'       => $assigned_user_id,
                                            'has_assigned_on'   => date(DB_DATE_FORMAT),
                                            'is_bulk_uploaded'  => 0,
                                            "due_date"          => $closed_date,
                                            "tat_status"        => "IN TAT",
                                        );

                        
                                        $result_identity = $this->identity_model->save( $field_array);
                        
                                        $identity_com_ref = $this->identity_com_ref($result_identity);

                                        $user_activity_data = $this->common_model->user_actity_data(array('component' => "Identity",
                                        'ref_no' => $identity_com_ref, 'candidate_name' => $frm_details['CandidateName'], 'created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id'], 'activity_type' => 'Manual', 'action' => 'Add'));
                
                        
                                    }   
                                }
                            }
                    
                            $user_activity_data = $this->common_model->user_actity_data(array('component' => "Candidate",
                                'ref_no' => $frm_details['cmp_ref_no'], 'candidate_name' => $frm_details['CandidateName'], 'created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id'], 'activity_type' => 'Manual', 'action' => 'Edit'));

                            $json_array['status'] = SUCCESS_CODE;

                            $json_array['message'] = 'Info Updated Successfully';

                            $json_array['redirect'] = ADMIN_SITE_URL . 'candidates';
                        } else {
                            $json_array['status'] = ERROR_CODE;

                            $json_array['message'] = 'Database error, please try again';
                        }
                    } else {
                        $json_array['status'] = ERROR_CODE;

                        $json_array['message'] = 'Something went wrong, please try again';

                        $json_array['redirect'] = ADMIN_SITE_URL . 'candidates';
                    }
                } catch (Exception $e) {
                    log_message('error', 'Error on Candidate::candidates_update');
                    log_message('error', $e->getMessage());
                }
            }
            echo_json($json_array);
        } else {
            permission_denied();
        }
    }

    public function export_logs($cand_id = null)
    {
        $candidate_details = $this->candidates_model->select_logs(array('candidates_info_logs.candidates_info_id' => decrypt($cand_id)));

        if (!empty($candidate_details)) {
            $file_name = $candidate_details[0]['ClientRefNumber'] . '-' . $candidate_details[0]['CandidateName'] . '-' . now() . '.csv';

            export_to_csv($candidate_details, $file_name);
        } else {
            export_to_csv(array(), 'Empty-File.csv');
        }
    }

    public function get_clients_cadidate()
    {
        if ($this->input->is_ajax_request()) {
            $clientid = $this->input->post('clientid');

            $selected_clientid = $this->input->post('selected_clientid');

            $json_array = array();

            if ($clientid != "") {
                $cands = $this->common_model->select('candidates_info', false, array('CandidateName', 'id', 'ClientRefNumber', 'cmp_ref_no'), array('clientid' => $clientid));

                $create_arr['0'] = 'Select Candidate';

                foreach ($cands as $cand) {
                    $create_arr[$cand['id']] = ucwords($cand['CandidateName']);
                }

                echo form_dropdown('candsid', $create_arr, $selected_clientid, "class='form-control' id='candsid' ");
            }
        }
    }

    public function get_candidate_details()
    {
        if ($this->input->is_ajax_request()) {
            $candsid = $this->input->post('candsid');

            $selected_candsid = $this->input->post('selected_candsid');

            $json_array = array();

            if ($candsid != "") {
                $cands = $this->candidates_model->select(true, array('CandidateName', 'id', 'ClientRefNumber', 'cmp_ref_no'), array('id' => $candsid));
                if (!empty($cands)) {
                    $json_data['status'] = SUCCESS_CODE;
                    $json_data['message'] = $cands;
                } else {
                    $json_data['status'] = ERROR_CODE;
                    $json_data['message'] = 'Client Ref No. and ' . REFNO . ' not found for selected candidate';
                }
                echo_json($json_data);
            }
        }
    }

    public function ajax_view_component($candsid = '', $tab = "", $clientid = "")
    {
        if ($this->input->is_ajax_request()) {
            $data['clientid'] = $clientid;

            $data['cand_id'] = $candsid;

            switch ($tab) {
                case "candidate_tab":
                    $this->view_details_candidate(array('candidates_info.id' => decrypt($candsid)));
                    break;
                case "addrver":
                    echo "<br><h3>Working of Address Component</h3>";
                    // $this->load->model('addressver_model');
                    // $data['address_lists'] = $this->addressver_model->get_all_addrs_by_client(array('addrver.candsid'=>$candsid));
                    // echo $this->load->view('admin/address_ajax_tab',$data,TRUE);
                    break;
                case "courtver":
                    echo $this->load->view('admin/court_ajax_tab', $data, true);
                    break;
                    break;
                case "cbrver":
                    $this->load->model('credit_report_model');
                    $data['credit_report_lists'] = $this->credit_report_model->get_all_credit_report_record(array('candidates_info.id' => $candsid));
                    echo $this->load->view('admin/credit_report_ajax_tab', $data, true);
                    break;
                case "claim_investigation":
                    echo "Cashless investigation cases are not mapped with candidate records,please check manualy!";
                    break;
                case "eduver":
                    $this->load->model('education_model');
                    $data['education_lists'] = $this->education_model->get_all_education_record(array('candidates_info.id' => $candsid));
                    echo $this->load->view('admin/education_ajax_tab', $data, true);
                    break;
                case "empver":
                    $this->view_details_employment(array('candidates_info.id' => $candsid));
                    break;
                case "globdbver":
                    $this->load->model('global_database_model');
                    $data['global_cand_lists'] = $this->global_database_model->get_all_global_record(array('cands.id' => $candsid));
                    echo $this->load->view('admin/global_ajax_tab', $data, true);
                    break;
                case "crimver":
                    $this->load->model('pcc_model');
                    $data['crimver_lists'] = $this->pcc_model->get_all_pcc_records_by_client(array('cands.id' => $candsid));
                    echo $this->load->view('admin/pcc_ajax_tab', $data, true);
                    break;
                case "narcver":
                    $this->load->model('drug_verificatiion_model');
                    $data['crimver_lists'] = $this->drug_verificatiion_model->get_all_drug_records_by_client(array('candidates_info.id' => $candsid));
                    echo $this->load->view('admin/drug_ajax_tab', $data, true);
                    break;
                case "refver":
                    $this->load->model('reference_verificatiion_model');
                    $data['references_lists'] = $this->reference_verificatiion_model->get_all_references_records_by_client(array('candidates_info.id' => $candsid));
                    echo $this->load->view('admin/references_ajax_tab', $data, true);
                    break;
                default:
                    echo "No components found";
            }
        }
    }

    public function view_details_employment($where_array)
    {
        $this->load->model('employment_model');

        $emp_details = $this->employment_model->get_all_emp_by_client($where_array);

        if (!empty($where_array) && !empty($emp_details)) {
            $data['empt_details'] = $emp_details[0];
            $data['clients'] = $this->get_clients(array('clients.empver' => 1, 'status' => STATUS_ACTIVE));
            $data['company'] = $this->get_company_list();
            $data['assigned_user_id'] = $this->get_user_profile_list(array('status' => STATUS_ACTIVE));
            echo $this->load->view('admin/employment_edit', $data, true);
        } else {
            echo "<h1>Details Not Found</h1>";
        }
    }

    public function view_details_candidate($where_array = '')
    {
        $candidate_details = $this->candidates_model->select(true, array(), $where_array);

        if (!empty($where_array) && !empty($candidate_details)) {
            $this->load->model('clients_model');

            $data['status'] = status_frm_db();

            $data['states'] = $this->get_states();

            $data['clients'] = $this->get_clients();

            $data['components'] = $this->common_model->select('components', false, array(), array());

            $data['client_components'] = $this->clients_model->select(true, array(), array('id' => $candidate_details['clientid']));

            $data['candidate_details'] = $candidate_details;

            $data['attachments'] = $this->candidates_model->select_file(array('id', 'file_name', 'real_filename', 'type'), array('candidate_id' => $candidate_details['id'], 'status' => 1));

            echo $this->load->view('admin/candidates_edit_view', $data, true);
        } else {
            echo "<h1>Details Not Found</h1>";
        }
    }

    public function sub_status_list()
    {
        if ($this->input->is_ajax_request()) {
            $return = $this->sub_status($this->input->post('status'));
        }
        echo_json(array('sub_status_list' => $return));
    }

    public function sub_status_list_candidates()
    {
        if ($this->input->is_ajax_request()) {
            $return = $this->sub_status_candidates($this->input->post('status'));
        }
        echo_json(array('sub_status_list' => $return));
    }

    protected function cellColor($cells, $color)
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        $spreadsheet->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
            'startcolor' => array(
                'rgb' => $color,
            ),
        ));
    }

    protected function cellColorStatus($cells,$status_value,$spreadsheet)
    {
       
        if($status_value == "Clear")
        {
           

            $styleArrayClear = array(
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ),
                
                'fill' => array(
                    'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'rotation' => 90,
                    'color' => array(
                        'argb' => '008000',
                    ),
                   
                ),
            );
     

             $spreadsheet->getActiveSheet()->getStyle($cells)->applyFromArray($styleArrayClear);
          
        }
        if($status_value == "Insufficiency")
        {

            $styleArrayInsuff = array(
                'font' => array(
                    'bold' => true,
                    'color' =>  array(
                        'argb' => 'FF0000',
                    ),
                ),
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ),
                
            );
     
                 
          $spreadsheet->getActiveSheet()->getStyle($cells)->applyFromArray($styleArrayInsuff);
        }

        if($status_value == "Major Discrepancy")
        {

            $styleArrayMajor = array(
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ),

                'fill' => array(
                    'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'rotation' => 90,
                    'color' => array(
                        'argb' => 'FF0000',
                    ),
                   
                ),
                
            );
     
                 
          $spreadsheet->getActiveSheet()->getStyle($cells)->applyFromArray($styleArrayMajor);
        }

        if($status_value == "Stop Check" || $status_value == "NA" || $status_value == "N/A")
        {

            $styleArrayMajor = array(
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ),

                'fill' => array(
                    'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'rotation' => 90,
                    'color' => array(
                        'argb' => 'D3D3D3',
                    ),
                   
                ),
                
            );
          
          $spreadsheet->getActiveSheet()->getStyle($cells)->applyFromArray($styleArrayMajor);
        }

        if($status_value == "Unable to verify")
        {

            $styleArrayMajor = array(
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ),

                'fill' => array(
                    'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'rotation' => 90,
                    'color' => array(
                        'argb' => 'FFFF00',
                    ),
                   
                ),
                
            );
          
          $spreadsheet->getActiveSheet()->getStyle($cells)->applyFromArray($styleArrayMajor);
        }

        if($status_value == "Minor Discrepancy")
        {

            $styleArrayMajor = array(
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ),

                'fill' => array(
                    'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'rotation' => 90,
                    'color' => array(
                        'argb' => 'FFA500',
                    ),
                   
                ),
                
            );
          
          $spreadsheet->getActiveSheet()->getStyle($cells)->applyFromArray($styleArrayMajor);
        }


    }

    public function template_download()
    {
        if ($this->input->post('client_id')) {
            $client_id = $this->input->post('client_id');
            $states = array('Select State', 'Andaman And Nicobar Islands', 'Andhra Pradesh', 'Arunachal Pradesh', 'Assam', 'Bihar', 'Chattisgarh', 'Chandigarh', 'Daman And Diu', 'Delhi', 'Dadra And Nagar Haveli', 'Goa', 'Gujarat', 'Himachal Pradesh', 'Haryana', 'Jammu And Kashmir', 'Jharkhand', 'Kerala', 'Karnataka', 'Lakshadweep', 'Meghalaya');
            //  $states = array('Select State','Andaman And Nicobar Islands','Andhra Pradesh','Arunachal Pradesh','Assam','Bihar','Chattisgarh','Chandigarh','Daman And Diu','Delhi','Dadra And Nagar Haveli','Goa','Gujarat','Himachal Pradesh','Haryana','Jammu And Kashmir','Jharkhand','Kerala','Karnataka','Lakshadweep','Meghalaya','Maharashtra','Manipur','Madhya Pradesh','Mizoram','Nagaland','Orissa','Punjab','Pondicherry','Rajasthan','Sikkim','Tamil Nadu','Tripura','Uttarakhand','Uttar Pradesh','West Bengal','Telangana');
            try {
                $gender = array('Select Gender', 'Male', 'Female', 'Other');

                $work_experiance = array('Select', 'Fresher', 'Experienced');

                require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
                // Create new Spreadsheet object
                $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
                // Set document properties

                //$this->cellColor('A1:D1', '#FF0000');
                //$this->cellColor('H1', '#FF0000');
                $spreadsheet->getProperties()->setCreator(CRMNAME)
                    ->setLastModifiedBy(CRMNAME)
                    ->setTitle(CRMNAME)
                    ->setSubject('Candidates Import Template')
                    ->setDescription('Candidates Import Template File for bulk upload');

                $styleArray = array(
                    'fill' => array(
                        'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startcolor' => array(
                            'rgb' => 'FF0000',
                        ),
                    ),
                );
                $spreadsheet->getActiveSheet()->getStyle('A1:F1')->applyFromArray($styleArray);
                $spreadsheet->getActiveSheet()->getStyle('H1')->applyFromArray($styleArray);

                foreach (range('A', 'Y') as $columnID) {
                    $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                        ->setWidth(20);
                }

                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A1", 'Client Reference No.')
                    ->setCellValue("B1", 'Case Received Date')
                    ->setCellValue("C1", 'Candidate Name')
                    ->setCellValue("D1", 'Gender')
                    ->setCellValue("E1", 'Date of Birth (DD-MM-YYYY)')
                    ->setCellValue("F1", 'Father Name')
                    ->setCellValue("G1", 'Mother Name')
                    ->setCellValue("H1", 'Primary Contact No')
                    ->setCellValue("I1", 'Contact No(2)')
                    ->setCellValue("J1", 'Contact No(3)')
                    ->setCellValue("K1", 'Email ID')
                    ->setCellValue("L1", 'Date of Joining')
                    ->setCellValue("M1", 'Designation')
                    ->setCellValue("N1", 'Branch Location')
                    ->setCellValue("O1", 'Department')
                    ->setCellValue("P1", 'Employee Code')
                    ->setCellValue("Q1", 'Work Experience')
                    ->setCellValue("R1", 'PAN Card No')
                    ->setCellValue("S1", 'Aadhar Card No')
                    ->setCellValue("T1", 'Passport No')
                    ->setCellValue("U1", 'Street Address')
                    ->setCellValue("V1", 'City')
                    ->setCellValue("W1", 'Pincode')
                    ->setCellValue("X1", 'State')
                    ->setCellValue("Y1", 'Remarks');

                for ($i = 1; $i <= 1000; $i++) {

                    $objValidation = $spreadsheet->getActiveSheet()->getCell('D' . $i)->getDataValidation();
                    $objValidation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                    $objValidation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
                    $objValidation->setAllowBlank(false);
                    $objValidation->setShowInputMessage(true);
                    $objValidation->setShowErrorMessage(true);
                    $objValidation->setShowDropDown(true);
                    $objValidation->setErrorTitle('Input error');
                    $objValidation->setError('Value is not in list.');
                    $objValidation->setPromptTitle('Pick from list');
                    $objValidation->setPrompt('Please pick a value from the drop-down list.');
                    $objValidation->setFormula1('"' . implode(',', $gender) . '"');

                    $objValidation = $spreadsheet->getActiveSheet()->getCell('E' . $i)->getDataValidation();
                    $objValidation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                    $objValidation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
                    $objValidation->setAllowBlank(false);
                    $objValidation->setShowInputMessage(true);
                    $objValidation->setShowErrorMessage(true);
                    $objValidation->setErrorTitle('Input error');
                    $objValidation->setError('This Field is Compulsory.');
                    $objValidation->setPromptTitle('Insert Date of Birth');
                    $objValidation->setPrompt('Please insert Date of Birth.');

                    $objValidation = $spreadsheet->getActiveSheet()->getCell('F' . $i)->getDataValidation();
                    $objValidation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                    $objValidation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
                    $objValidation->setAllowBlank(false);
                    $objValidation->setShowInputMessage(true);
                    $objValidation->setShowErrorMessage(true);
                    $objValidation->setErrorTitle('Input error');
                    $objValidation->setError('This Field is Compulsory.');
                    $objValidation->setPromptTitle('Insert Father Name');
                    $objValidation->setPrompt('Please insert Father Name.');

                    $objValidation = $spreadsheet->getActiveSheet()->getCell('Q' . $i)->getDataValidation();
                    $objValidation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                    $objValidation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
                    $objValidation->setAllowBlank(false);
                    $objValidation->setShowInputMessage(true);
                    $objValidation->setShowErrorMessage(true);
                    $objValidation->setShowDropDown(true);
                    $objValidation->setErrorTitle('Input error');
                    $objValidation->setError('Value is not in list.');
                    $objValidation->setPromptTitle('Pick from list');
                    $objValidation->setPrompt('Please pick a value from the drop-down list.');
                    $objValidation->setFormula1('"' . implode(',', $work_experiance) . '"');

                    $objValidation = $spreadsheet->getActiveSheet()->getCell('X' . $i)->getDataValidation();
                    $objValidation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                    $objValidation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
                    $objValidation->setAllowBlank(false);
                    $objValidation->setShowInputMessage(true);
                    $objValidation->setShowErrorMessage(true);
                    $objValidation->setShowDropDown(true);
                    $objValidation->setErrorTitle('Input error');
                    $objValidation->setError('Value is not in list.');
                    $objValidation->setPromptTitle('Pick from list');
                    $objValidation->setPrompt('Please pick a value from the drop-down list.');
                    $objValidation->setFormula1('"' . implode(',', $states) . '"');

                    $objValidation = $spreadsheet->getActiveSheet()->getCell('A' . $i)->getDataValidation();
                    $objValidation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                    $objValidation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
                    $objValidation->setAllowBlank(false);
                    $objValidation->setShowInputMessage(true);
                    $objValidation->setShowErrorMessage(true);
                    $objValidation->setErrorTitle('Input error');
                    $objValidation->setError('This Field is Compulsory.');
                    $objValidation->setPromptTitle('Insert unique id');
                    $objValidation->setPrompt('Please do not insert duplicate value.');

                    $objValidation = $spreadsheet->getActiveSheet()->getCell('B' . $i)->getDataValidation();
                    $objValidation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                    $objValidation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
                    $objValidation->setAllowBlank(false);
                    $objValidation->setShowInputMessage(true);
                    $objValidation->setShowErrorMessage(true);
                    $objValidation->setErrorTitle('Input error');
                    $objValidation->setError('This Field is Compulsory.');
                    $objValidation->setPromptTitle('Insert Date Only');
                    $objValidation->setPrompt('Please insert date only format(DD-MM-YYYY).');

                    $objValidation = $spreadsheet->getActiveSheet()->getCell('C' . $i)->getDataValidation();
                    $objValidation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                    $objValidation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
                    $objValidation->setAllowBlank(false);
                    $objValidation->setShowInputMessage(true);
                    $objValidation->setShowErrorMessage(true);
                    $objValidation->setErrorTitle('Input error');
                    $objValidation->setError('This Field is Compulsory.');
                    $objValidation->setPromptTitle('Insert Cadidate Name');
                    $objValidation->setPrompt('Please insert Candidate Name.');

                    $objValidation = $spreadsheet->getActiveSheet()->getCell('D' . $i)->getDataValidation();
                    $objValidation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                    $objValidation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
                    $objValidation->setAllowBlank(false);
                    $objValidation->setShowInputMessage(true);
                    $objValidation->setShowErrorMessage(true);
                    $objValidation->setShowDropDown(true);
                    $objValidation->setErrorTitle('Input error');
                    $objValidation->setError('Value is not in list.');
                    $objValidation->setPromptTitle('Pick from list');
                    $objValidation->setPrompt('Please pick a value from the drop-down list.');
                    $objValidation->setFormula1('"' . implode(',', $gender) . '"');

                    $objValidation = $spreadsheet->getActiveSheet()->getCell('H' . $i)->getDataValidation();
                    $objValidation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                    $objValidation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
                    $objValidation->setAllowBlank(false);
                    $objValidation->setShowInputMessage(true);
                    $objValidation->setShowErrorMessage(true);
                    $objValidation->setErrorTitle('Input error');
                    $objValidation->setError('This Field is Compulsory.');
                    $objValidation->setPromptTitle('Insert Contact Number');
                    $objValidation->setPrompt('Please insert Maximum 11 digit and Mimimum 10.');

                    $objValidation = $spreadsheet->getActiveSheet()->getCell('K' . $i)->getDataValidation();
                    $objValidation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                    $objValidation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
                    $objValidation->setAllowBlank(true);
                    $objValidation->setShowInputMessage(true);
                    $objValidation->setShowErrorMessage(true);
                    $objValidation->setErrorTitle('Input error');
                    $objValidation->setPromptTitle('Insert Email ID');
                    $objValidation->setPrompt('Please insert Email id in proper format');

                    $objValidation = $spreadsheet->getActiveSheet()->getCell('L' . $i)->getDataValidation();
                    $objValidation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                    $objValidation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
                    $objValidation->setAllowBlank(false);
                    $objValidation->setShowInputMessage(true);
                    $objValidation->setShowErrorMessage(true);
                    $objValidation->setErrorTitle('Input error');
                    $objValidation->setPromptTitle('Insert Date Only');
                    $objValidation->setPrompt('Please insert date only format(DD-MM-YYYY).');

                    $objValidation = $spreadsheet->getActiveSheet()->getCell('W' . $i)->getDataValidation();
                    $objValidation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                    $objValidation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
                    $objValidation->setAllowBlank(false);
                    $objValidation->setShowInputMessage(true);
                    $objValidation->setShowErrorMessage(true);
                    $objValidation->setErrorTitle('Input error');
                    $objValidation->setPromptTitle('Insert Pin Code');
                    $objValidation->setPrompt('Please insert Maximum 6 digit and Mimimum 6.');
                }

                $spreadsheet->getActiveSheet()->setTitle('Candidate Records');
                $spreadsheet->setActiveSheetIndex(0);

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

                $json_array['file_name'] = "Candidates Bulk Uplaod Template";

                $json_array['message'] = "File downloaded successfully,please check in download folder";

                $json_array['status'] = SUCCESS_CODE;
            } catch (Exception $e) {
                log_message('error', 'Error on Candidate::template_download');
                log_message('error', $e->getMessage());
            }
        } else {
            $json_array['file_name'] = "Candidates Bulk Uplaod Template";
            $json_array['message'] = "File downloaded failed,please check in download folder";
            $json_array['status'] = ERROR_CODE;
        }
        echo_json($json_array);
    }

    public function template_download_attachment()
    {
        if ($this->input->is_ajax_request()) {
            try {
                require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
                // Create new Spreadsheet object
                $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
                // Set document properties
                $spreadsheet->getProperties()->setCreator(CRMNAME)
                    ->setLastModifiedBy(CRMNAME)
                    ->setTitle(CRMNAME)
                    ->setSubject('Candidates Attachment Import Template')
                    ->setDescription('Candidates Attachment Import Template File for bulk upload');

                foreach (range('A', 'B') as $columnID) {
                    $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                        ->setWidth(20);
                }

                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A1", REFNO)
                    ->setCellValue("B1", 'Attachment');

                for ($i = 1; $i <= 1000; $i++) {

                    $objValidation = $spreadsheet->getActiveSheet()->getCell('A' . $i)->getDataValidation();
                    $objValidation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                    $objValidation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
                    $objValidation->setAllowBlank(true);
                    $objValidation->setShowInputMessage(true);
                    $objValidation->setShowErrorMessage(true);
                    $objValidation->setErrorTitle('Input error');
                    $objValidation->setError('This Field is Compulsory.');
                    $objValidation->setPromptTitle('Insert ' . REFNO);
                    $objValidation->setPrompt('Please insert ' . REFNO);

                }

                $spreadsheet->getActiveSheet()->setTitle('Candidate Records Attachment');
                $spreadsheet->setActiveSheetIndex(0);

                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header("Content-Disposition: attachment;filename=Candidates Attachment Bulk Uplaod Template .xlsx");
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

                $json_array['file_name'] = "Candidates Attachment Bulk Uplaod Template";

                $json_array['message'] = "File downloaded successfully,please check in download folder";

                $json_array['status'] = SUCCESS_CODE;
            } catch (Exception $e) {
                log_message('error', 'Error on Candidate::template_download_attachment');
                log_message('error', $e->getMessage());
            }
        } else {
            $json_array['file_name'] = "Candidates Attachment Bulk Uplaod Template";
            $json_array['message'] = "File downloaded failed,please check in download folder";
            $json_array['status'] = ERROR_CODE;
        }
        echo_json($json_array);
    }

    public function bulk_upload_candidates()
    {
        if ($this->input->is_ajax_request()) {
            $clientid = $this->input->post('clientid');
            $entity = $this->input->post('entity');
            $package = $this->input->post('package');

            $file_upload_path = SITE_BASE_PATH . CANDIDATES_BULK_FILES;

            if (!folder_exist($file_upload_path)) {
                mkdir($file_upload_path, 0777,true);
            } else if (!is_writable($file_upload_path)) {
                $message = 'Problem while uploading, folder permission';
            }

            $file_upload_path = $file_upload_path . $clientid;

            if (!folder_exist($file_upload_path)) {
                mkdir($file_upload_path, 0777,true);
            } else if (!is_writable($file_upload_path)) {
                $message = 'Problem while uploading, folder permission';
            }

            $uplaod_details = array('file_upload_path' => $file_upload_path, 'file_data' => $_FILES['cands_bulk_sheet'], 'file_permission' => 'csv|xls|xlsx', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 100);
            $upload_result = $this->file_uplod($uplaod_details);

            $record = array();

            $raw_filename = $_FILES['cands_bulk_sheet']['tmp_name'];
            //$BatchRefNumber = $this->BatchRefNumber($clientid);

            $headerLine = 0;
            $file = fopen($raw_filename, "r");

            $this->load->library('excel_reader', array('file_name' => $file_upload_path . '/' . $upload_result['file_name']));

            $excel_handler = $this->excel_reader->file_handler;

            $excel_data = $excel_handler->rows();

            if (!empty($excel_data))
            // while (($value = fgetcsv($file, 1000, ",")) !== FALSE)
            {
                unset($excel_data[0]);

                $excel_data = array_map("unserialize", array_unique(array_map("serialize", $excel_data)));

                foreach ($excel_data as $value) {

                    if (count($value) <= 24) {
                        continue;

                    }
                    $check_record_exits = $this->candidates_model->select(true, array('id'), array('ClientRefNumber' => $value[0], 'clientid' => $clientid));

                    if (empty($check_record_exits) && $value[0] != "" && $entity != "" && $package != "") {

                        $field_array = array('clientid' => $clientid,
                            'entity' => $entity,
                            'package' => $package,
                            'created_on' => date(DB_DATE_FORMAT),
                            'created_by' => $this->user_info['id'],
                            'is_bulk_upload' => 1,
                            'status' => STATUS_ACTIVE,
                            'overallstatus' => 1,
                            'ClientRefNumber' => $value[0],
                            'caserecddate' => $value[1],
                            'CandidateName' => $value[2],
                            "gender" => $value[3],
                            'DateofBirth' => $value[4],
                            'NameofCandidateFather' => $value[5],
                            'MothersName' => $value[6],
                            'CandidatesContactNumber' => $value[7],
                            'ContactNo1' => $value[8],
                            'ContactNo2' => $value[9],
                            'cands_email_id' => $value[10],
                            'DateofJoining' => $value[11],
                            'DesignationJoinedas' => $value[12],
                            'Location' => $value[13],
                            'Department' => $value[14],
                            'EmployeeCode' => $value[15],
                            'branch_name' => $value[16],
                            'PANNumber' => $value[17],
                            'AadharNumber' => $value[18],
                            'PassportNumber' => $value[19],
                            //'BatchNumber' => $value[18],
                            'prasent_address' => $value[20],
                            'cands_city' => $value[21],
                            'cands_pincode' => $value[22],
                            'cands_state' => $value[23],
                            'cmp_ref_no' => '',
                            // 'cands_state' => $value[23],
                            'remarks' => $value[24],

                        );
                        $DateofBirth = get_date_from_timestamp($value[4]);
                        if ($DateofBirth) {
                            $field_array['DateofBirth'] = $DateofBirth;
                        }

                        $DateofJoining = get_date_from_timestamp($value[11]);
                        if ($DateofJoining) {
                            $field_array['DateofJoining'] = $DateofJoining;
                        }

                        $caserecddate = get_date_from_timestamp($value[1]);
                        if ($caserecddate) {
                            $field_array['caserecddate'] = $caserecddate;
                        }

                        $record = array_map('strtolower', array_map('trim', $field_array));

                        $insert_id = $this->candidates_model->save($record);

                        $cmp_ref_no = $this->cmp_ref_no($insert_id);

                        $all_id[] = $insert_id;

                        $field_array2 = array('candidates_info_id' => $insert_id, 'cmp_ref_no' => $cmp_ref_no);

                        $field_array1 = array_merge($field_array, $field_array2);

                        $result_candidate = $this->candidates_model->save_candidate($field_array1);

                        $data['success'] = $value[0] . "This Reference Code Records Created Successfully";
                    } else {
                        //$data['fail'] = $value[0]." This Reference Code Records Exits";

                    }
                }
            } else {
                $data['message'] = 'CSV File is empty or data large then 1000 charecters';
                $all_id = '';
            }

            $record_count = count($all_id);

            $json_array['message'] = json_encode($data);

            $json_array['count'] = $record_count;

            $json_array['status'] = SUCCESS_CODE;
        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['message'] = 'Something went wrong, please try again';
        }
        echo_json($json_array);
    }

    public function bulk_upload_candidates_attachment()
    {
        if ($this->input->is_ajax_request()) {
            try {
                $clientid = $this->input->post('clientid_attachment');

                $file_upload_path = SITE_BASE_PATH . CANDIDATES_BULK_FILES . "Attachment";

                if (!folder_exist($file_upload_path)) {
                    mkdir($file_upload_path, 0777,true);
                } else if (!is_writable($file_upload_path)) {
                    $message = 'Problem while uploading, folder permission';
                }

                $file_upload_path_client = SITE_BASE_PATH . CANDIDATES . $clientid;

                if (!folder_exist($file_upload_path)) {
                    mkdir($file_upload_path, 0777,true);
                } else if (!is_writable($file_upload_path)) {
                    $message = 'Problem while uploading, folder permission';
                }
                $uplaod_details = array('file_upload_path' => $file_upload_path, 'file_data' => $_FILES['cands_bulk_sheet_attachment'], 'file_permission' => 'csv', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 100);
                $upload_result = $this->file_uplod($uplaod_details);

                $record = array();

                $raw_filename = $_FILES['cands_bulk_sheet_attachment']['tmp_name'];
                //$BatchRefNumber = $this->BatchRefNumber($clientid);
                $headerLine = 0;
                $file = fopen($raw_filename, "r");

                $this->load->library('excel_reader', array('file_name' => $file_upload_path . '/' . $upload_result['file_name']));

                $excel_handler = $this->excel_reader->file_handler;

                $excel_data = $excel_handler->rows();
                $data = '';
                if (!empty($excel_data)) {
                    unset($excel_data[0]);
                    $excel_data = array_map("unserialize", array_unique(array_map("serialize", $excel_data)));
                    foreach ($excel_data as $value) {
                        if (count($value) <= 1) {
                            continue;
                        }
                        $check_record_exits = $this->candidates_model->select(true, array('id'), array('cmp_ref_no' => $value[0], 'clientid' => $clientid));

                        if (!empty($check_record_exits)) {

                            if (in_array($value[1], $_FILES['attchments']['name'])) {

                                $position = array_search($value[1], $_FILES['attchments']['name'], true);

                                $file_name = $_FILES['attchments']['name'][$position];

                                $file_info = pathinfo($file_name);

                                $new_file_name = preg_replace('/[[:space:]]+/', '_', $file_info['filename']);

                                $new_file_name = $new_file_name . '_' . DATE(UPLOAD_FILE_DATE_FORMAT_FILE_NAME);

                                $new_file_name = str_replace('.', '_', $new_file_name);

                                $new_file_name = preg_replace('/[^A-Za-z0-9\-]/', '_', $new_file_name);

                                $file_extension = $file_info['extension'];

                                $new_file_name = $new_file_name . '.' . $file_extension;

                                $_FILES['attchment']['name'] = $new_file_name;

                                $_FILES['attchment']['tmp_name'] = $_FILES['attchments']['tmp_name'][$position];

                                $_FILES['attchment']['error'] = $_FILES['attchments']['error'][$position];

                                $_FILES['attchment']['size'] = $_FILES['attchments']['size'][$position];

                                $config['upload_path'] = $file_upload_path_client;

                                $config['file_name'] = $new_file_name;

                                $config['allowed_types'] = 'jpeg|jpg|png|pdf';

                                $config['file_ext_tolower'] = true;

                                $config['remove_spaces'] = true;

                                $config['max_size'] = BULK_UPLOAD_MAX_SIZE_MB * 20000;

                                $this->load->library('upload', $config);

                                $this->upload->initialize($config);

                                if ($this->upload->do_upload('attchment')) {
                                    $field_array = array('file_name' => $new_file_name,
                                        'real_filename' => $file_name,
                                        'candidate_id' => $check_record_exits['id'],
                                        'type' => '0',
                                        'status' => STATUS_ACTIVE);
                                    $result = $this->candidates_model->save_candidate_file($field_array);

                                    $data['success'] = "Records Created Successfully";
                                } else {
                                    $result = array_push($error_msgs, $this->upload->display_errors('', ''));

                                    $data['Fail'] = "File Uploading Problem";
                                }
                            }
                        } else {
                            $data['Fail'] = "Candidates ID Not Found";
                        }
                    }
                } else {
                    $data['message'] = 'CSV File is empty or data large then 1000 charecters';
                }
                $json_array['message'] = json_encode($data);
                $json_array['status'] = SUCCESS_CODE;
            } catch (Exception $e) {
                log_message('error', 'Error on Candidate::template_download');
                log_message('error', $e->getMessage());
            }

        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['message'] = 'Something went wrong, please try again';
        }
        echo_json($json_array);
    }

    
    public function batch_attchment()
    {
        $json_array['status'] = ERROR_CODE;
        $json_array['message'] = 'Permission Denied';

        if ($this->input->is_ajax_request()) {
            $file_upload_path = SITE_BASE_PATH . CANDIDATES_BULK_FILES . '/attchments';

            if (!folder_exist($file_upload_path)) {
                mkdir($file_upload_path, 0777);
            }

            $file_name = str_replace(' ', '_', $_FILES['attachment_zip']['name']);

            $new_file_name = time() . "_" . $file_name;

            $_FILES['attachment_zip_']['name'] = $new_file_name;

            $_FILES['attachment_zip_']['tmp_name'] = $_FILES['attachment_zip']['tmp_name'];

            $_FILES['attachment_zip_']['error'] = $_FILES['attachment_zip']['error'];

            $_FILES['attachment_zip_']['size'] = $_FILES['attachment_zip']['size'];

            $config['upload_path'] = $file_upload_path;

            $config['file_ext_tolower'] = true;

            $config['max_size'] = BULK_UPLOAD_MAX_SIZE_MB * 10000;

            $config['allowed_types'] = 'zip';

            $config['remove_spaces'] = true;

            $config['overwrite'] = false;

            $config['file_name'] = $new_file_name;
            try {
                $this->load->library('upload', $config);

                $this->upload->initialize($config);

                if ($this->upload->do_upload('attachment_zip_')) {

                    $data = array('upload_data' => $this->upload->data());
                    $full_path = $data['upload_data']['full_path'];

                    $zip = new ZipArchive;

                    if ($zip->open($full_path) === true) {
                        $zip->extractTo($file_upload_path);
                        $zip->close();
                    }
                    $this->db->insert('batch_update_log', array('file_name' => $file_name, 'original_file_name' => $new_file_name, 'status' => 1, 'created_by' => $this->user_info['id'], 'created_on' => date(DB_DATE_FORMAT)));

                    $json_array['status'] = SUCCESS_CODE;
                    $json_array['message'] = 'Successfully Uploaded';
                } else {
                    $json_array['status'] = ERROR_CODE;
                    $json_array['message'] = $this->upload->display_errors();
                }
            } catch (Exception $e) {
                log_message('error', 'Error on Candidate::template_download');
                log_message('error', $e->getMessage());
            }
        }
        echo_json($json_array);
    }

    public function batch_update_template()
    {
        $json_array['file_name'] = "";

        $json_array['message'] = "File downloaded failed,please check in download folder";

        $json_array['status'] = ERROR_CODE;

        if ($this->input->is_ajax_request()) {
            $columns = 'cmp_ref_no,' . $this->input->post('columns');
            $columns = explode(',', $columns);

            if (is_array($columns) && !empty($columns)) {
                try {
                    require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
                    // Create new Spreadsheet object
                    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
                    // Set document properties
                    $spreadsheet->getProperties()->setCreator(CRMNAME)
                        ->setLastModifiedBy(CRMNAME)
                        ->setTitle(CRMNAME)
                        ->setSubject('Candidates Import Template')
                        ->setDescription('Candidates Import Template File for bulk upload');

                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("A1", 'Note- Date format should be yyyy-mm-dd');

                    // foreach(range('A','Y') as $columnID) {
                    //   $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                    //       ->setWidth(20);
                    // }

                    foreach ($columns as $key => $value) {
                        $spreadsheet->setActiveSheetIndex(0)->setCellValueByColumnAndRow($key, 2, $value);
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

                    $json_array['file_name'] = "Candidates-Batch-Uplaod-Template";

                    $json_array['message'] = "File downloaded successfully,please check in download folder";

                    $json_array['status'] = SUCCESS_CODE;
                } catch (Exception $e) {
                    log_message('error', 'Error on Candidate::template_download');
                    log_message('error', $e->getMessage());
                }
            }
        }

        echo_json($json_array);
    }

    public function ajax_add_address()
    {
        $data['clients'] = $this->get_clients();

        $data['states'] = $this->get_states();

        echo $this->load->view('admin/address_add', $data, true);
    }

    public function create_cand_ref()
    {
        if ($this->input->is_ajax_request()) {
            $client_id = $this->input->post('clientid');

            if ($client_id != 0 && $client_id != "") {
                $json_array['message'] = $this->cmp_ref_no_generate($client_id);

                $json_array['status'] = SUCCESS_CODE;

            } else {
                $json_array['message'] = 'Client Ref Number Not Generating, Please add manualy';

                $json_array['status'] = ERROR_CODE;
            }
        } else {
            $json_array['message'] = 'Something went wrong, please try again';

            $json_array['status'] = ERROR_CODE;
        }

        echo_json($json_array);
    }

    public function ajax_tab_data($candsid = '', $tab = "")
    {
        if ($this->input->is_ajax_request()) {
            $data['cand_id'] = $candsid;
            switch ($tab) {
                case "addrver":
                    $this->load->model('addressver_model');

                    if ($this->permission['access_address_list_view'] == true) {
                        $data['address_lists'] = $this->addressver_model->get_address_details1($candsid);
                    } else if (($this->user_info['department'] == "client services")) {
                        $data['address_lists'] = $this->addressver_model->get_address_details1($candsid);
                    } else {
                        $data['address_lists'] = "Not Permission";
                    }
                    echo $this->load->view('admin/address_ajax_tab', $data, true);
                    break;
                case "courtver":
                    $this->load->model('court_verificatiion_model');

                    if ($this->permission['access_court_list_view'] == true) {
                        $data['court_list'] = $this->court_verificatiion_model->get_all_court_record(array('candidates_info.id' => $candsid));
                    } else if (($this->user_info['department'] == "client services")) {
                        $data['court_list'] = $this->court_verificatiion_model->get_all_court_record(array('candidates_info.id' => $candsid));
                    } else {
                        $data['court_list'] = "Not Permission";
                    }
                    echo $this->load->view('admin/court_ajax_tab', $data, true);
                    break;

                case "cbrver":
                    $this->load->model('credit_report_model');
                    if ($this->permission['access_credit_list_view'] == true) {
                        $data['credit_report_lists'] = $this->credit_report_model->get_all_credit_report_record(array('candidates_info.id' => $candsid));
                    } else if (($this->user_info['department'] == "client services")) {
                        $data['credit_report_lists'] = $this->credit_report_model->get_all_credit_report_record(array('candidates_info.id' => $candsid));
                    } else {
                        $data['credit_report_lists'] = "Not Permission";
                    }
                    echo $this->load->view('admin/credit_report_ajax_tab', $data, true);
                    break;

                case "claim_investigation":
                    echo "Cashless investigation cases are not mapped with candidate records,please check manualy!";
                    break;
                case "eduver":
                    $this->load->model('education_model');

                    if ($this->permission['access_education_list_view'] == true) {
                        $data['education_lists'] = $this->education_model->get_all_education_record(array('candidates_info.id' => $candsid));
                    } else if (($this->user_info['department'] == "client services")) {
                        $data['education_lists'] = $this->education_model->get_all_education_record(array('candidates_info.id' => $candsid));
                    } else {
                        $data['education_lists'] = "Not Permission";
                    }
                    echo $this->load->view('admin/education_ajax_tab', $data, true);
                    break;
                case "empver":
                    $this->load->model('employment_model');

                    if ($this->permission['access_employment_list_view'] == true) {
                        $data['employment_lists'] = $this->employment_model->get_emp_list_view(array('candidates_info.id' => $candsid));

                    } else if (($this->user_info['department'] == "client services")) {

                        $data['employment_lists'] = $this->employment_model->get_emp_list_view(array('candidates_info.id' => $candsid));
                    } else {
                        $data['employment_lists'] = "Not Permission";
                    }
                    echo $this->load->view('admin/employment_ajax_tab', $data, true);
                    break;
                case "globdbver":
                    $this->load->model('global_database_model');
                    if ($this->permission['access_global_list_view'] == true) {
                        $data['global_cand_lists'] = $this->global_database_model->get_all_global_record(array('candidates_info.id' => $candsid));
                    } else if (($this->user_info['department'] == "client services")) {

                        $data['global_cand_lists'] = $this->global_database_model->get_all_global_record(array('candidates_info.id' => $candsid));
                    } else {
                        $data['global_cand_lists'] = "Not Permission";
                    }
                    echo $this->load->view('admin/global_ajax_tab', $data, true);
                    break;
                case "identity":
                    $this->load->model('identity_model');
                    if ($this->permission['access_identity_list_view'] == true) {
                        $data['identity_lists'] = $this->identity_model->get_all_identity_record(array('candidates_info.id' => $candsid));
                    } else if (($this->user_info['department'] == "client services")) {

                        $data['identity_lists'] = $this->identity_model->get_all_identity_record(array('candidates_info.id' => $candsid));
                    } else {
                        $data['identity_lists'] = "Not Permission";
                    }
                    echo $this->load->view('admin/identity_ajax_tab', $data, true);
                    break;
                case "crimver":
                    $this->load->model('pcc_verificatiion_model');
                    if ($this->permission['access_pcc_list_view'] == true) {
                        $data['crimver_lists'] = $this->pcc_verificatiion_model->get_all_pcc_record(array('candidates_info.id' => $candsid));
                    } else if (($this->user_info['department'] == "client services")) {
                        $data['crimver_lists'] = $this->pcc_verificatiion_model->get_all_pcc_record(array('candidates_info.id' => $candsid));
                    } else {
                        $data['crimver_lists'] = "Not Permission";
                    }
                    echo $this->load->view('admin/pcc_ajax_tab', $data, true);
                    break;
                case "narcver":
                    $this->load->model('drug_verificatiion_model');
                    if ($this->permission['access_drugs_list_view'] == true) {

                        $data['drug_lists'] = $this->drug_verificatiion_model->get_all_drug_record(array('candidates_info.id' => $candsid));
                    } else if (($this->user_info['department'] == "client services")) {
                        $data['drug_lists'] = $this->drug_verificatiion_model->get_all_drug_record(array('candidates_info.id' => $candsid));
                    } else {
                        $data['drug_lists'] = "Not Permission";
                    }
                    echo $this->load->view('admin/drug_ajax_tab', $data, true);
                    break;
                case "refver":
                    $this->load->model('reference_verificatiion_model');

                    if ($this->permission['access_reference_list_view'] == true) {
                        $data['reference_lists'] = $this->reference_verificatiion_model->get_all_reference_record(array('candidates_info.id' => $candsid));
                    } else if (($this->user_info['department'] == "client services")) {
                        $data['reference_lists'] = $this->reference_verificatiion_model->get_all_reference_record(array('candidates_info.id' => $candsid));
                    } else {
                        $data['reference_lists'] = "Not Permission";
                    }

                    echo $this->load->view('admin/references_ajax_tab', $data, true);
                    break;
                case "social_media":
                    $this->load->model('social_media_model');

                    if ($this->permission['access_reference_list_view'] == true) {
                        $data['social_media_lists'] = $this->social_media_model->get_all_social_media_record(array('candidates_info.id' => $candsid));
                    } else if (($this->user_info['department'] == "client services")) {
                        $data['social_media_lists'] = $this->social_media_model->get_all_social_media_record(array('candidates_info.id' => $candsid));
                    } else {
                        $data['social_media_lists'] = "Not Permission";
                    }

                    echo $this->load->view('admin/social_media_ajax_tab', $data, true);
                    break;    
                default:
                    echo "No components found";
            }
        }
    }

    public function bulk_upload()
    {
        $data['header_title'] = "Candidate Bulk Upload";

        $data['clients'] = $this->get_clients();

        $this->load->view('admin/header', $data);

        $this->load->view('admin/candidates_bulk_upload_view');

        $this->load->view('admin/footer');
    }

    public function report($candsid = null, $report_type)
    {
        if (!empty($candsid)) {

            $id = decrypt($candsid);
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
        // }
        // else
        // {

        //    redirect('/client/login');
        //     exit();
        // }

    }

    public function report_final_view($candsid = null, $report_type)
    {
        if (!empty($candsid)) {

            $id = decrypt($candsid);
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
                    $cleit_logo_path = SITE_URL . CLIENT_LOGO . '/' . $cands_result['comp_logo'];
                    define('CLIENT_LOGOS', $cleit_logo_path);
                } else {
                    define('CLIENT_LOGOS', '');
                }

                define('CUSTOM_CLINT_ID',$cands_result['clientid']);


                $this->example->generate_pdf($report, 'client');
            } else {
                show_404();
            }
        } else {
            redirect('admin/candidates');
        }
        // }
        // else
        // {

        //    redirect('/client/login');
        //     exit();
        // }

    }

    public function get_cands_details()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {
            $candsid = $this->input->post('candsid');

            $details = $this->candidates_model->select(true, array('DateofBirth', 'NameofCandidateFather', 'MothersName', 'CandidatesContactNumber', 'prasent_address'), array('id' => $candsid));

            if (!empty($details)) {
                $json_array['message'] = $details;

                $json_array['status'] = SUCCESS_CODE;
            } else {
                $json_array['message'] = "Something went wrong,please try again";
                $json_array['status'] = ERROR_CODE;
            }
        } else {
            $json_array['message'] = "Something went wrong,please try again";
            $json_array['status'] = ERROR_CODE;
        }

        echo_json($json_array);
    }

    public function get_all_client_data_for_export($clientid, $entity_id, $package_id, $fil_by_status, $fil_by_sub_status)
    {
        $data_arry = array();

        $cands_results = $this->candidates_model->get_all_cand_with_search($clientid, $entity_id, $package_id, $fil_by_status, $fil_by_sub_status);
   
        $x = 0;
        foreach ($cands_results as $key => $cands_result) {

            $data_arry[$x]['id'] = $x + 1;
            $data_arry[$x]['candsid'] = $cands_result['id'];
            $data_arry[$x]['CandidateName'] = ucwords(strtolower($cands_result['CandidateName']));
            $data_arry[$x]['ClientRefNumber'] = $cands_result['ClientRefNumber'];
            $data_arry[$x]['cmp_ref_no'] = $cands_result['cmp_ref_no'];
            $data_arry[$x]['overallstatus'] = $cands_result['status_value'];
            $data_arry[$x]['entity_name'] = $cands_result['entity_name'];
            $data_arry[$x]['package_name'] = $cands_result['package_name'];
            $data_arry[$x]['cands_email_id'] = $cands_result['cands_email_id'];
            $data_arry[$x]['CandidatesContactNumber'] = $cands_result['CandidatesContactNumber'];
            $data_arry[$x]['overallstatus'] = $cands_result['status_value'];
            $data_arry[$x]['due_date_candidate'] = convert_db_to_display_date($cands_result['due_date_candidate']);
            $data_arry[$x]['Location'] = $cands_result['Location'];
            $data_arry[$x]['clientname'] = ucwords(strtolower($cands_result['clientname']));
            $data_arry[$x]['caserecddate'] = convert_db_to_display_date($cands_result['caserecddate']);
            $data_arry[$x]['overallclosuredate'] = ($cands_result['overallclosuredate'] != "") ? convert_db_to_display_date($cands_result['overallclosuredate']) : 'NA';

            for ($i = 0; $i < 5; $i++) {

                $data_arry[$x]["addrver$i"] = 'NA';
                $data_arry[$x]["addrver_address$i"] = 'NA';
                $data_arry[$x]["empver$i"] = 'NA';
                $data_arry[$x]["empver_cpm$i"] = 'NA';
                $data_arry[$x]["eduver$i"] = 'NA';
                $data_arry[$x]["eduver_univer$i"] = 'NA';
                $data_arry[$x]["refver$i"] = 'NA';
                $data_arry[$x]["refvername$i"] = 'NA';
                $data_arry[$x]["courtver$i"] = 'NA';
                $data_arry[$x]["courtver_address$i"] = 'NA';
                $data_arry[$x]["crimver$i"] = 'NA';
                $data_arry[$x]["crimver_address$i"] = 'NA';
                $data_arry[$x]["glodbver$i"] = 'NA';
                $data_arry[$x]["glodbver_address$i"] = 'NA';
                $data_arry[$x]["identity$i"] = 'NA';
                $data_arry[$x]["identity_doc$i"] = 'NA';
                $data_arry[$x]["cbrver$i"] = 'NA';
                $data_arry[$x]["cbrver_cibil$i"] = 'NA';
                $data_arry[$x]["drugs$i"] = 'NA';
                $data_arry[$x]["drugs_test_code$i"] = 'NA';

            }

            $component_id = explode(",", $cands_result['component_id']);

            $rename_status = array('NA' => 'N/A');

            $insufficiency_details = array();

            if (in_array('addrver', $component_id)) {
                $result = $this->candidates_model->get_addres_ver_status_for_export(array('addrver.candsid' => $cands_result['id']));

                if (!empty($result)) {
                    $counter = 1;
                    foreach ($result as $key => $value) {
                        if ($counter > 5) {
                            continue;
                        }

                        if (array_key_exists($value['report_status'], $rename_status)) {
                            $data_arry[$x]["addrver$key"] = $rename_status[$value['report_status']];
                        } else {
                            $data_arry[$x]["addrver$key"] = ($value['report_status'] != "") ? $value['report_status'] : 'WIP';
                        }

                        if (($value['verfstatus'] == "Major Discrepancy") || ($value['verfstatus'] == "major discrepancy")) {

                            array_push($discrepancy_details, "Add " . $counter . " || " . $value['address'] . ',' . $value['city'] . ',' . $value['pincode'] . ',' . $value['state'] . " || " . $value['remarks']);

                        }

                        if (($value['verfstatus'] == "Insufficiency") || ($value['verfstatus'] == "insufficiency")) {

                            array_push($insufficiency_details, "Add " . $counter . " || " . $value['address'] . ',' . $value['city'] . ',' . $value['pincode'] . ',' . $value['state'] . " || " . $value['insuff_raise_remark']);

                        }

                        $data_arry[$x]["addrver_address$key"] = ($value['address'] != "") ? $value['address'] : 'NA';
                        $counter++;
                    }
                }
            }

            if (in_array('eduver', $component_id)) {
                $result = $this->candidates_model->get_education_ver_status_for_export(array('education.candsid' => $cands_result['id']));
                if (!empty($result)) {

                    $counter = 1;
                    foreach ($result as $key => $value) {
                        if ($counter > 5) {
                            continue;
                        }

                        if (array_key_exists($value['report_status'], $rename_status)) {
                            $data_arry[$x]["eduver$key"] = $rename_status[$value['report_status']];
                        } else {
                            $data_arry[$x]["eduver$key"] = ($value['report_status'] != "") ? $value['report_status'] : 'WIP';
                        }

                        if (($value['verfstatus'] == "Insufficiency") || ($value['verfstatus'] == "insufficiency")) {
                            array_push($insufficiency_details, "Edu " . $counter . " || " . $value['universityname'] . ',' . $value['qualification_name'] . " || " . $value['insuff_raise_remark']);
                        }

                        $data_arry[$x]["eduver_univer$key"] = ($value['universityname'] != "") ? $value['universityname'] : 'NA';
                        $counter++;
                    }
                }
            }

            if (in_array('empver', $component_id)) {
                $result = $this->candidates_model->get_employment_ver_status_for_export(array('empver.candsid' => $cands_result['id']));
                if (!empty($result)) {
                    $counter = 1;
                    foreach ($result as $key => $value) {
                        if ($counter > 5) {
                            continue;
                        }

                        if (array_key_exists($value['report_status'], $rename_status)) {
                            $data_arry[$x]["empver$key"] = $rename_status[$value['report_status']];
                        } else {
                            $data_arry[$x]["empver$key"] = ($value['report_status'] != "") ? $value['report_status'] : 'WIP';
                        }

                        if (($value['verfstatus'] == "Insufficiency") || ($value['verfstatus'] == "insufficiency")) {

                            array_push($insufficiency_details, "Emp " . $counter . " || " . $value['coname'] . " || " . $value['insuff_raise_remark']);
                        }

                        $data_arry[$x]["empver_cpm$key"] = ($value['coname'] != "") ? $value['coname'] : 'NA';

                        $counter++;
                    }
                }
            }

            if (in_array('refver', $component_id)) {
                $result = $this->candidates_model->get_refver_ver_status_for_export(array('reference.candsid' => $cands_result['id']));
                if (!empty($result)) {
                    $counter = 1;
                    foreach ($result as $key => $value) {
                        if ($counter > 5) {
                            continue;
                        }

                        if (array_key_exists($value['report_status'], $rename_status)) {
                            $data_arry[$x]["refver$key"] = $rename_status[$value['report_status']];
                        } else {
                            $data_arry[$x]["refver$key"] = ($value['report_status'] != "") ? $value['report_status'] : 'WIP';
                        }

                        if (($value['verfstatus'] == "Insufficiency") || ($value['verfstatus'] == "insufficiency")) {

                            array_push($insufficiency_details, "Ref " . $counter . " || " . $value['name_of_reference'] . " || " . $value['insuff_raise_remark']);
                        }

                        $data_arry[$x]["refvername$key"] = ($value['name_of_reference'] != "") ? $value['name_of_reference'] : 'NA';
                        $counter++;
                    }
                }
            }

            if (in_array('courtver', $component_id)) {
                $result = $this->candidates_model->get_court_ver_status_for_export(array('courtver.candsid' => $cands_result['id']));
                if (!empty($result)) {
                    $counter = 1;
                    foreach ($result as $key => $value) {
                        if ($counter > 5) {
                            continue;
                        }

                        if (array_key_exists($value['report_status'], $rename_status)) {
                            $data_arry[$x]["courtver$key"] = $rename_status[$value['report_status']];
                        } else {
                            $data_arry[$x]["courtver$key"] = ($value['report_status'] != "") ? $value['report_status'] : 'WIP';
                        }

                        if (($value['verfstatus'] == "Insufficiency") || ($value['verfstatus'] == "insufficiency")) {

                            array_push($insufficiency_details, "Court " . $counter . " || " . $value['street_address'] . ',' . $value['city'] . ',' . $value['pincode'] . ',' . $value['state'] . " || " . $value['insuff_raise_remark']);
                        }

                        $data_arry[$x]["courtver_address$key"] = ($value['street_address'] != "") ? $value['street_address'] : 'NA';
                        $counter++;
                    }
                }
            }
            if (in_array('crimver', $component_id)) {
                $result = $this->candidates_model->get_pcc_ver_status_for_export(array('pcc.candsid' => $cands_result['id']));
                if (!empty($result)) {

                    $counter = 1;
                    foreach ($result as $key => $value) {
                        if ($counter > 5) {
                            continue;
                        }

                        if (array_key_exists($value['report_status'], $rename_status)) {
                            $data_arry[$x]["crimver$key"] = $rename_status[$value['report_status']];
                        } else {
                            $data_arry[$x]["crimver$key"] = ($value['report_status'] != "") ? $value['report_status'] : 'WIP';
                        }

                        if (($value['verfstatus'] == "Insufficiency") || ($value['verfstatus'] == "insufficiency")) {

                            array_push($insufficiency_details, "PCC " . $counter . " || " . $value['street_address'] . ',' . $value['city'] . ',' . $value['pincode'] . ',' . $value['state'] . " || " . $value['insuff_raise_remark']);
                        }

                        $data_arry[$x]["crimver_address$key"] = ($value['street_address'] != "") ? $value['street_address'] : 'NA';
                        $counter++;
                    }
                }
            }

            if (in_array('globdbver', $component_id)) {
                $result = $this->candidates_model->get_globdbver_ver_status_for_export(array('glodbver.candsid' => $cands_result['id']));
                if (!empty($result)) {

                    $counter = 1;
                    foreach ($result as $key => $value) {
                        if ($counter > 5) {
                            continue;
                        }

                        if (array_key_exists($value['report_status'], $rename_status)) {
                            $data_arry[$x]["glodbver$key"] = $rename_status[$value['report_status']];
                        } else {
                            $data_arry[$x]["glodbver$key"] = ($value['report_status'] != "") ? $value['report_status'] : 'WIP';
                        }

                        if (($value['verfstatus'] == "Insufficiency") || ($value['verfstatus'] == "insufficiency")) {

                            array_push($insufficiency_details, "Global Database " . $counter . " || " . $value['street_address'] . ',' . $value['city'] . ',' . $value['pincode'] . ',' . $value['state'] . " || " . $value['insuff_raise_remark']);
                        }

                        $data_arry[$x]["glodbver_address$key"] = ($value['street_address'] != "") ? $value['street_address'] : 'NA';
                        $counter++;
                    }
                }
            }
            if (in_array('identity', $component_id)) {
                $result = $this->candidates_model->get_identity_ver_status_for_export(array('identity.candsid' => $cands_result['id']));

                if (!empty($result)) {

                    $counter = 1;
                    foreach ($result as $key => $value) {
                        if ($counter > 5) {
                            continue;
                        }

                        if (array_key_exists($value['report_status'], $rename_status)) {
                            $data_arry[$x]["identity$key"] = $rename_status[$value['report_status']];
                        } else {
                            $data_arry[$x]["identity$key"] = ($value['report_status'] != "") ? $value['report_status'] : 'WIP';
                        }

                        if (($value['verfstatus'] == "Insufficiency") || ($value['verfstatus'] == "insufficiency")) {

                            array_push($insufficiency_details, "Identity " . $counter . " || " . $value['doc_submited'] . " || " . $value['insuff_raise_remark']);
                        }

                        $data_arry[$x]["identity_doc$key"] = ($value['doc_submited'] != "") ? $value['doc_submited'] : 'NA';

                        $counter++;
                    }
                }
            }

            if (in_array('cbrver', $component_id)) {
                $result = $this->candidates_model->get_credit_report_ver_status_for_export(array('credit_report.candsid' => $cands_result['id']));

                if (!empty($result)) {

                    $counter = 1;
                    foreach ($result as $key => $value) {

                        if ($counter > 5) {

                            continue;
                        }

                        if (array_key_exists($value['report_status'], $rename_status)) {
                            $data_arry[$x]["cbrver$key"] = $rename_status[$value['report_status']];
                        } else {
                            $data_arry[$x]["cbrver$key"] = ($value['report_status'] != "") ? $value['report_status'] : 'WIP';
                        }

                        if (($value['verfstatus'] == "Insufficiency") || ($value['verfstatus'] == "insufficiency")) {

                            array_push($insufficiency_details, "Credit Report " . $counter . " || " . " Cibil " . " || " . $value['insuff_raise_remark']);
                        }

                        $data_arry[$x]["cbrver_cibil$key"] = 'Cibil';

                        $counter++;
                    }

                }
            }

            if (in_array('narcver', $component_id)) {
                $result = $this->candidates_model->get_drugs_report_ver_status_for_export(array('drug_narcotis.candsid' => $cands_result['id']));

                if (!empty($result)) {

                    $counter = 1;
                    foreach ($result as $key => $value) {

                        if ($counter > 5) {

                            continue;
                        }

                        if (array_key_exists($value['report_status'], $rename_status)) {
                            $data_arry[$x]["drugs$key"] = $rename_status[$value['report_status']];
                        } else {
                            $data_arry[$x]["drugs$key"] = ($value['report_status'] != "") ? $value['report_status'] : 'WIP';
                        }

                        if (($value['verfstatus'] == "Insufficiency") || ($value['verfstatus'] == "insufficiency")) {

                            array_push($insufficiency_details, "Drugs " . $counter . " || " . $value['drug_test_code'] . " || " . $value['insuff_raise_remark']);
                        }

                        $data_arry[$x]["drugs_test_code$key"] = ($value['drug_test_code'] != "") ? $value['drug_test_code'] : 'NA';

                        $counter++;
                    }

                }
            }

            $insufficiencys_details = array();
            foreach ($insufficiency_details as $key => $value) {
                $insufficiencys_details[] = $value . ",";
            }

            $data_arry[$x]['Details'] = implode('', $insufficiencys_details);
            unset($insufficiencys_details);

            $x++;
        }

        return $data_arry;
    }

    public function get_all_client_data_for_export_tracker($clientid, $entity_id, $package_id, $fil_by_status, $fil_by_sub_status)
    {
        $data_arry = array();

        $cands_results = $this->candidates_model->get_all_cand_with_search($clientid, $entity_id, $package_id, $fil_by_status, $fil_by_sub_status);
  
        $x = 0;
        foreach ($cands_results as $key => $cands_result) {

            $data_arry[$x]['id'] = $x + 1;
            $data_arry[$x]['candsid'] = $cands_result['id'];
            $data_arry[$x]['CandidateName'] = ucwords(strtolower($cands_result['CandidateName']));
            $data_arry[$x]['ClientRefNumber'] = $cands_result['ClientRefNumber'];
            $data_arry[$x]['cmp_ref_no'] = $cands_result['cmp_ref_no'];
            $data_arry[$x]['overallstatus'] = $cands_result['status_value'];
            $data_arry[$x]['entity_name'] = $cands_result['entity_name'];
            $data_arry[$x]['package_name'] = $cands_result['package_name'];
            $data_arry[$x]['overallstatus'] = $cands_result['status_value'];
            $data_arry[$x]['clientname'] = ucwords(strtolower($cands_result['clientname']));
            $data_arry[$x]['caserecddate'] = convert_db_to_display_date($cands_result['caserecddate']);
            $data_arry[$x]['overallclosuredate'] = ($cands_result['overallclosuredate'] != "") ? convert_db_to_display_date($cands_result['overallclosuredate']) : 'NA';

            for ($i = 0; $i < 3; $i++) {

                $data_arry[$x]["addrver$i"] = 'NA';
                $data_arry[$x]["empver$i"] = 'NA';
                $data_arry[$x]["eduver$i"] = 'NA';
                $data_arry[$x]["refver$i"] = 'NA';
                $data_arry[$x]["courtver$i"] = 'NA';
                $data_arry[$x]["crimver$i"] = 'NA';
                $data_arry[$x]["glodbver$i"] = 'NA';
                $data_arry[$x]["identity$i"] = 'NA';
                $data_arry[$x]["cbrver$i"] = 'NA';
                $data_arry[$x]["drugs$i"] = 'NA';

            }

            $component_id = explode(",", $cands_result['component_id']);

            $rename_status = array('NA' => 'N/A');

            $discrepancy_details = array();

            $insufficiency_details = array();

            $insuff_raise = array();

            $insuff_clear = array();

            $latest_date = array();


            if (in_array('addrver', $component_id)) {
                $result = $this->candidates_model->get_addres_ver_status_for_export(array('addrver.candsid' => $cands_result['id']));
                
                $result_insuff = $this->candidates_model->get_address_insuff_details(array('addrver.candsid' => $cands_result['id']));

                if (!empty($result)) {
                    $counter = 1;
                    foreach ($result as $key => $value) {
                        if ($counter > 3) {
                            continue;
                        }

                        if (array_key_exists($value['report_status'], $rename_status)) {
                            $data_arry[$x]["addrver$key"] = $rename_status[$value['report_status']];
                        } else {
                            $data_arry[$x]["addrver$key"] = ($value['report_status'] != "") ? $value['report_status'] : 'WIP';
                        }

                        if (($value['verfstatus'] == "Major Discrepancy") || ($value['verfstatus'] == "major discrepancy")) {

                            array_push($discrepancy_details, "Add " . $counter . " - " . $value['address'] . ',' . $value['city'] . ',' . $value['pincode'] . ',' . $value['state'] . " - " . $value['remarks']);

                        }

                        if (($value['verfstatus'] == "Insufficiency") || ($value['verfstatus'] == "insufficiency")) {

                            array_push($insufficiency_details, "Add " . $counter . " - " . $value['address'] . ',' . $value['city'] . ',' . $value['pincode'] . ',' . $value['state'] . " - " . $value['insuff_raise_remark']);

                        }

                        $counter++;
                    }
                    if(!empty($result_insuff))
                    { 
                        foreach ($result_insuff as  $value_insuff) {
                            if(!empty($value_insuff['insuff_raised_date']))
                            {
                            array_push($insuff_raise, convert_db_to_display_date($value_insuff['insuff_raised_date']));
                            }
                            if(!empty($value_insuff['insuff_clear_date']))
                            {
                            array_push($insuff_clear, convert_db_to_display_date($value_insuff['insuff_clear_date']));
                            }
                        }
                    }

                    foreach ($result as $key => $value_initiation) {

                        array_push($latest_date, $value_initiation['iniated_date']); 
                    }

                }
            }


            if (in_array('eduver', $component_id)) {
                $result = $this->candidates_model->get_education_ver_status_for_export(array('education.candsid' => $cands_result['id']));

                $result_insuff = $this->candidates_model->get_education_insuff_details(array('education.candsid' => $cands_result['id']));

                if (!empty($result)) {

                    $counter = 1;
                    foreach ($result as $key => $value) {
                        if ($counter > 3) {
                            continue;
                        }

                        if (array_key_exists($value['report_status'], $rename_status)) {
                            $data_arry[$x]["eduver$key"] = $rename_status[$value['report_status']];
                        } else {
                            $data_arry[$x]["eduver$key"] = ($value['report_status'] != "") ? $value['report_status'] : 'WIP';
                        }

                        if (($value['verfstatus'] == "Major Discrepancy") || ($value['verfstatus'] == "major discrepancy")) {

                            array_push($discrepancy_details, "Edu " . $counter . " - " . $value['universityname'] . ',' . $value['qualification_name'] . " - " . $value['remarks']);

                        }

                        if (($value['verfstatus'] == "Insufficiency") || ($value['verfstatus'] == "insufficiency")) {
                            array_push($insufficiency_details, "Edu " . $counter . " - " . $value['universityname'] . ',' . $value['qualification_name'] . " - " . $value['insuff_raise_remark']);
                        }

                        $counter++;
                    }
                    if(!empty($result_insuff))
                    { 
                        foreach ($result_insuff as  $value_insuff) {
                            if(!empty($value_insuff['insuff_raised_date']))
                            {
                            array_push($insuff_raise, convert_db_to_display_date($value_insuff['insuff_raised_date']));
                            }
                            if(!empty($value_insuff['insuff_clear_date']))
                            {
                            array_push($insuff_clear, convert_db_to_display_date($value_insuff['insuff_clear_date']));
                            }
                        }
                    }

                    foreach ($result as $key => $value_initiation) {

                        array_push($latest_date, $value_initiation['iniated_date']); 
                    }
                }
            }



            if (in_array('empver', $component_id)) {
                $result = $this->candidates_model->get_employment_ver_status_for_export(array('empver.candsid' => $cands_result['id']));

                $result_insuff = $this->candidates_model->get_employment_insuff_details(array('empver.candsid' => $cands_result['id']));

                if (!empty($result)) {
                    $counter = 1;
                    foreach ($result as $key => $value) {
                        if ($counter > 3) {
                            continue;
                        }

                        if (array_key_exists($value['report_status'], $rename_status)) {
                            $data_arry[$x]["empver$key"] = $rename_status[$value['report_status']];
                        } else {
                            $data_arry[$x]["empver$key"] = ($value['report_status'] != "") ? $value['report_status'] : 'WIP';
                        }

                        if (($value['verfstatus'] == "Major Discrepancy") || ($value['verfstatus'] == "major discrepancy")) {

                            array_push($discrepancy_details, "Emp " . $counter . " - " . $value['coname'] . " - " . $value['remarks']);

                        }

                        if (($value['verfstatus'] == "Insufficiency") || ($value['verfstatus'] == "insufficiency")) {

                            array_push($insufficiency_details, "Emp " . $counter . " - " . $value['coname'] . " - " . $value['insuff_raise_remark']);
                        }


                        $counter++;
                    }
                    if(!empty($result_insuff))
                    { 
                        foreach ($result_insuff as  $value_insuff) {
                            if(!empty($value_insuff['insuff_raised_date']))
                            {
                            array_push($insuff_raise, convert_db_to_display_date($value_insuff['insuff_raised_date']));
                            }
                            if(!empty($value_insuff['insuff_clear_date']))
                            {
                            array_push($insuff_clear, convert_db_to_display_date($value_insuff['insuff_clear_date']));
                            }
                        }
                    }

                    foreach ($result as $key => $value_initiation) {

                        array_push($latest_date, $value_initiation['iniated_date']); 
                    }
                }
            }
 
            if (in_array('refver', $component_id)) {
                $result = $this->candidates_model->get_refver_ver_status_for_export(array('reference.candsid' => $cands_result['id']));

                $result_insuff = $this->candidates_model->get_reference_insuff_details(array('reference.candsid' => $cands_result['id']));

                if (!empty($result)) {
                    $counter = 1;
                    foreach ($result as $key => $value) {
                        if ($counter > 3) {
                            continue;
                        }

                        if (array_key_exists($value['report_status'], $rename_status)) {
                            $data_arry[$x]["refver$key"] = $rename_status[$value['report_status']];
                        } else {
                            $data_arry[$x]["refver$key"] = ($value['report_status'] != "") ? $value['report_status'] : 'WIP';
                        }

                        if (($value['verfstatus'] == "Major Discrepancy") || ($value['verfstatus'] == "major discrepancy")) {

                            array_push($discrepancy_details, "Ref " . $counter . " - " . $value['name_of_reference'] . " - " . $value['remarks']);

                        }

                        if (($value['verfstatus'] == "Insufficiency") || ($value['verfstatus'] == "insufficiency")) {

                            array_push($insufficiency_details, "Ref " . $counter . " - " . $value['name_of_reference'] . " - " . $value['insuff_raise_remark']);
                        }

                        $counter++;
                    }
                    if(!empty($result_insuff))
                    { 
                        foreach ($result_insuff as  $value_insuff) {
                            if(!empty($value_insuff['insuff_raised_date']))
                            {
                            array_push($insuff_raise, convert_db_to_display_date($value_insuff['insuff_raised_date']));
                            }
                            if(!empty($value_insuff['insuff_clear_date']))
                            {
                            array_push($insuff_clear, convert_db_to_display_date($value_insuff['insuff_clear_date']));
                            }
                        }
                    }

                    foreach ($result as $key => $value_initiation) {

                        array_push($latest_date, $value_initiation['iniated_date']); 
                    }
                }
            }

            if (in_array('courtver', $component_id)) {
                $result = $this->candidates_model->get_court_ver_status_for_export(array('courtver.candsid' => $cands_result['id']));

                $result_insuff = $this->candidates_model->get_court_insuff_details(array('courtver.candsid' => $cands_result['id']));

                if (!empty($result)) {
                    $counter = 1;
                    foreach ($result as $key => $value) {
                        if ($counter > 3) {
                            continue;
                        }

                        if (array_key_exists($value['report_status'], $rename_status)) {
                            $data_arry[$x]["courtver$key"] = $rename_status[$value['report_status']];
                        } else {
                            $data_arry[$x]["courtver$key"] = ($value['report_status'] != "") ? $value['report_status'] : 'WIP';
                        }

                        if (($value['verfstatus'] == "Major Discrepancy") || ($value['verfstatus'] == "major discrepancy")) {

                            array_push($discrepancy_details, "Court " . $counter . " - " . $value['street_address'] . ',' . $value['city'] . ',' . $value['pincode'] . ',' . $value['state'] . " - " . $value['remarks']);
                        }

                        if (($value['verfstatus'] == "Insufficiency") || ($value['verfstatus'] == "insufficiency")) {

                            array_push($insufficiency_details, "Court " . $counter . " - " . $value['street_address'] . ',' . $value['city'] . ',' . $value['pincode'] . ',' . $value['state'] . " - " . $value['insuff_raise_remark']);
                        }

                        $counter++;
                    }
                    if(!empty($result_insuff))
                    { 
                        foreach ($result_insuff as  $value_insuff) {
                            if(!empty($value_insuff['insuff_raised_date']))
                            {
                            array_push($insuff_raise, convert_db_to_display_date($value_insuff['insuff_raised_date']));
                            }
                            if(!empty($value_insuff['insuff_clear_date']))
                            {
                            array_push($insuff_clear, convert_db_to_display_date($value_insuff['insuff_clear_date']));
                            }
                        }
                    }

                    foreach ($result as $key => $value_initiation) {

                        array_push($latest_date, $value_initiation['iniated_date']); 
                    }
                }
            }
            if (in_array('crimver', $component_id)) {
                $result = $this->candidates_model->get_pcc_ver_status_for_export(array('pcc.candsid' => $cands_result['id']));

                $result_insuff = $this->candidates_model->get_pcc_insuff_details(array('pcc.candsid' => $cands_result['id']));
                
                if (!empty($result)) {

                    $counter = 1;
                    foreach ($result as $key => $value) {
                        if ($counter > 3) {
                            continue;
                        }

                        if (array_key_exists($value['report_status'], $rename_status)) {
                            $data_arry[$x]["crimver$key"] = $rename_status[$value['report_status']];
                        } else {
                            $data_arry[$x]["crimver$key"] = ($value['report_status'] != "") ? $value['report_status'] : 'WIP';
                        }

                        if (($value['verfstatus'] == "Major Discrepancy") || ($value['verfstatus'] == "major discrepancy")) {

                            array_push($discrepancy_details, "PCC " . $counter . " - " . $value['street_address'] . ',' . $value['city'] . ',' . $value['pincode'] . ',' . $value['state'] . " - " . $value['remarks']);
                        }


                        if (($value['verfstatus'] == "Insufficiency") || ($value['verfstatus'] == "insufficiency")) {

                            array_push($insufficiency_details, "PCC " . $counter . " - " . $value['street_address'] . ',' . $value['city'] . ',' . $value['pincode'] . ',' . $value['state'] . " - " . $value['insuff_raise_remark']);
                        }

                        $counter++;
                    }
                    if(!empty($result_insuff))
                    { 
                        foreach ($result_insuff as  $value_insuff) {
                            if(!empty($value_insuff['insuff_raised_date']))
                            {
                            array_push($insuff_raise, convert_db_to_display_date($value_insuff['insuff_raised_date']));
                            }
                            if(!empty($value_insuff['insuff_clear_date']))
                            {
                            array_push($insuff_clear, convert_db_to_display_date($value_insuff['insuff_clear_date']));
                            }
                        }
                    }

                    foreach ($result as $key => $value_initiation) {

                        array_push($latest_date, $value_initiation['iniated_date']); 
                    }
                }
            }

            if (in_array('globdbver', $component_id)) {
                $result = $this->candidates_model->get_globdbver_ver_status_for_export(array('glodbver.candsid' => $cands_result['id']));

                $result_insuff = $this->candidates_model->get_global_insuff_details(array('glodbver.candsid' => $cands_result['id']));
                
                if (!empty($result)) {

                    $counter = 1;
                    foreach ($result as $key => $value) {
                        if ($counter > 3) {
                            continue;
                        }

                        if (array_key_exists($value['report_status'], $rename_status)) {
                            $data_arry[$x]["glodbver$key"] = $rename_status[$value['report_status']];
                        } else {
                            $data_arry[$x]["glodbver$key"] = ($value['report_status'] != "") ? $value['report_status'] : 'WIP';
                        }

                        if (($value['verfstatus'] == "Major Discrepancy") || ($value['verfstatus'] == "major discrepancy")) {

                            array_push($discrepancy_details, "Global Database " . $counter . " - " . $value['street_address'] . ',' . $value['city'] . ',' . $value['pincode'] . ',' . $value['state'] . " - " . $value['remarks']);
                        }

                        if (($value['verfstatus'] == "Insufficiency") || ($value['verfstatus'] == "insufficiency")) {

                            array_push($insufficiency_details, "Global Database " . $counter . " - " . $value['street_address'] . ',' . $value['city'] . ',' . $value['pincode'] . ',' . $value['state'] . " - " . $value['insuff_raise_remark']);
                        }

                        $counter++;
                    }
                    if(!empty($result_insuff))
                    { 
                        foreach ($result_insuff as  $value_insuff) {
                            if(!empty($value_insuff['insuff_raised_date']))
                            {
                            array_push($insuff_raise, convert_db_to_display_date($value_insuff['insuff_raised_date']));
                            }
                            if(!empty($value_insuff['insuff_clear_date']))
                            {
                            array_push($insuff_clear, convert_db_to_display_date($value_insuff['insuff_clear_date']));
                            }
                        }
                    }

                    foreach ($result as $key => $value_initiation) {

                        array_push($latest_date, $value_initiation['iniated_date']); 
                    }
                }
            }
            if (in_array('identity', $component_id)) {
                $result = $this->candidates_model->get_identity_ver_status_for_export(array('identity.candsid' => $cands_result['id']));

                $result_insuff = $this->candidates_model->get_identity_insuff_details(array('identity.candsid' => $cands_result['id']));
                

                if (!empty($result)) {

                    $counter = 1;
                    foreach ($result as $key => $value) {
                        if ($counter > 3) {
                            continue;
                        }

                        if (array_key_exists($value['report_status'], $rename_status)) {
                            $data_arry[$x]["identity$key"] = $rename_status[$value['report_status']];
                        } else {
                            $data_arry[$x]["identity$key"] = ($value['report_status'] != "") ? $value['report_status'] : 'WIP';
                        }

                        if (($value['verfstatus'] == "Major Discrepancy") || ($value['verfstatus'] == "major discrepancy")) {

                            array_push($discrepancy_details, "Identity " . $counter . " - " . $value['doc_submited'] . " - " . $value['remarks']);
                        }

                        if (($value['verfstatus'] == "Insufficiency") || ($value['verfstatus'] == "insufficiency")) {

                            array_push($insufficiency_details, "Identity " . $counter . " - " . $value['doc_submited'] . " - " . $value['insuff_raise_remark']);
                        }

                       
                        $counter++;
                    }
                    if(!empty($result_insuff))
                    { 
                        foreach ($result_insuff as  $value_insuff) {
                            if(!empty($value_insuff['insuff_raised_date']))
                            {
                            array_push($insuff_raise, convert_db_to_display_date($value_insuff['insuff_raised_date']));
                            }
                            if(!empty($value_insuff['insuff_clear_date']))
                            {
                            array_push($insuff_clear, convert_db_to_display_date($value_insuff['insuff_clear_date']));
                            }
                        }
                    }

                    foreach ($result as $key => $value_initiation) {

                        array_push($latest_date, $value_initiation['iniated_date']); 
                    }
                }
            }

            if (in_array('cbrver', $component_id)) {
                $result = $this->candidates_model->get_credit_report_ver_status_for_export(array('credit_report.candsid' => $cands_result['id']));

                $result_insuff = $this->candidates_model->get_credit_report_insuff_details(array('credit_report.candsid' => $cands_result['id']));

                if (!empty($result)) {

                    $counter = 1;
                    foreach ($result as $key => $value) {

                        if ($counter > 3) {

                            continue;
                        }

                        if (array_key_exists($value['report_status'], $rename_status)) {
                            $data_arry[$x]["cbrver$key"] = $rename_status[$value['report_status']];
                        } else {
                            $data_arry[$x]["cbrver$key"] = ($value['report_status'] != "") ? $value['report_status'] : 'WIP';
                        }

                        if (($value['verfstatus'] == "Major Discrepancy") || ($value['verfstatus'] == "major discrepancy")) {

                            array_push($discrepancy_details, "Credit Report " . $counter . " - " . " Cibil " . " - " . $value['remarks']);
                        }

                        if (($value['verfstatus'] == "Insufficiency") || ($value['verfstatus'] == "insufficiency")) {

                            array_push($insufficiency_details, "Credit Report " . $counter . " - " . " Cibil " . " - " . $value['insuff_raise_remark']);
                        }

                        $counter++;
                    }
                    if(!empty($result_insuff))
                    { 
                        foreach ($result_insuff as  $value_insuff) {
                            if(!empty($value_insuff['insuff_raised_date']))
                            {
                            array_push($insuff_raise, convert_db_to_display_date($value_insuff['insuff_raised_date']));
                            }
                            if(!empty($value_insuff['insuff_clear_date']))
                            {
                            array_push($insuff_clear, convert_db_to_display_date($value_insuff['insuff_clear_date']));
                            }
                        }
                    }

                    foreach ($result as $key => $value_initiation) {

                        array_push($latest_date, $value_initiation['iniated_date']); 
                    }

                }
            }

            if (in_array('narcver', $component_id)) {
                $result = $this->candidates_model->get_drugs_report_ver_status_for_export(array('drug_narcotis.candsid' => $cands_result['id']));

                $result_insuff = $this->candidates_model->get_drugs_report_insuff_details(array('drug_narcotis.candsid' => $cands_result['id']));

                if (!empty($result)) {

                    $counter = 1;
                    foreach ($result as $key => $value) {

                        if ($counter > 3) {

                            continue;
                        }

                        if (array_key_exists($value['report_status'], $rename_status)) {
                            $data_arry[$x]["drugs$key"] = $rename_status[$value['report_status']];
                        } else {
                            $data_arry[$x]["drugs$key"] = ($value['report_status'] != "") ? $value['report_status'] : 'WIP';
                        }

                        if (($value['verfstatus'] == "Major Discrepancy") || ($value['verfstatus'] == "major discrepancy")) {

                            array_push($discrepancy_details, "Drugs " . $counter . " - " . $value['drug_test_code'] . " - " . $value['remarks']);
                        }

                        if (($value['verfstatus'] == "Insufficiency") || ($value['verfstatus'] == "insufficiency")) {

                            array_push($insufficiency_details, "Drugs " . $counter . " - " . $value['drug_test_code'] . " - " . $value['insuff_raise_remark']);
                        }

                        $counter++;
                    }
                    if(!empty($result_insuff))
                    { 
                        foreach ($result_insuff as  $value_insuff) {
                            if(!empty($value_insuff['insuff_raised_date']))
                            {
                            array_push($insuff_raise, convert_db_to_display_date($value_insuff['insuff_raised_date']));
                            }
                            if(!empty($value_insuff['insuff_clear_date']))
                            {
                            array_push($insuff_clear, convert_db_to_display_date($value_insuff['insuff_clear_date']));
                            }
                        }
                    }

                    foreach ($result as $key => $value_initiation) {

                        array_push($latest_date, $value_initiation['iniated_date']); 
                    }

                }
            }
         
            $insufficiencys_details = array();
            foreach ($insufficiency_details as $key => $value) {
                $insufficiencys_details[] = $value . " || ";
            }

            $discrepancys_details = array();
            foreach ($discrepancy_details as $key => $value) {
                $discrepancys_details[] = $value . " || ";
            }

            asort($insuff_raise);
          
            $insuff_raise_date = array();
            foreach ($insuff_raise as $key => $value) {
                $insuff_raise_date[] = $value . " & ";
            }
            
            arsort($insuff_clear);

            $insuff_clear_date = array();
            foreach ($insuff_clear as $key => $value) {
                $insuff_clear_date[] = $value . " & ";
            }
      

            $data_arry[$x]['Details'] = implode('', $insufficiencys_details);
            unset($insufficiencys_details);

            $data_arry[$x]['DiscrepancyDetails'] = implode('', $discrepancys_details);
            unset($discrepancys_details);



            $data_arry[$x]['insuff_raise_date'] = implode('', $insuff_raise_date);
            unset($insuff_raise_date);


            $data_arry[$x]['insuff_clear_date'] = implode('', $insuff_clear_date);
            unset($insuff_clear_date);
            if(!empty($latest_date))
            {
                $data_arry[$x]['latest_date'] = max($latest_date);
            }
            else{
                $data_arry[$x]['latest_date'] = "";
            }

            $x++;
        }

        return $data_arry;
    }



    public function get_all_client_data_for_export_insufficiency($clientid, $entity_id, $package_id)
    {
        $data_arry = array();

        $cands_results = $this->candidates_model->get_all_cand_with_search_for_insufficiency($clientid, $entity_id, $package_id, false, false);
        $x = 0;
        foreach ($cands_results as $key => $cands_result) {

            $data_arry[$x]['id'] = $x + 1;
            $data_arry[$x]['candsid'] = $cands_result['id'];
            $data_arry[$x]['CandidateName'] = ucwords(strtolower($cands_result['CandidateName']));
            $data_arry[$x]['ClientRefNumber'] = $cands_result['ClientRefNumber'];
            $data_arry[$x]['cmp_ref_no'] = $cands_result['cmp_ref_no'];
            $data_arry[$x]['entity_name'] = $cands_result['entity_name'];
            $data_arry[$x]['overallstatus'] = $cands_result['status_value'];
            $data_arry[$x]['package_name'] = $cands_result['package_name'];
            $data_arry[$x]['clientname'] = ucwords(strtolower($cands_result['clientname']));
            $data_arry[$x]['caserecddate'] = convert_db_to_display_date($cands_result['caserecddate']);

            $component_id = explode(",", $cands_result['component_id']);

            $rename_status = array('NA' => 'N/A');

            $array_wip = array();
            $array_insufficiency = array();
            $array_closed = array();
            $insufficiency_details = array();

            if (in_array('addrver', $component_id)) {
                $result_address_main = $this->candidates_model->get_addres_ver_status_with_insuff(array('candidates_info.id' => $cands_result['id']));

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
                            array_push($insufficiency_details, $result_address['address'] . ',' . $result_address['city'] . ',' . $result_address['pincode'] . ',' . $result_address['state'] . " || " . $result_address['insuff_raise_remark']);

                        }
                        if (($address_component_status == "Closed") || ($address_component_status == "closed")) {
                            array_push($array_closed, 'Address ' . $address_count);
                        }

                        $address_count++;

                    }
                }
            }

            if (in_array('eduver', $component_id)) {
                $result_education_main = $this->candidates_model->get_education_ver_status_with_insuff(array('candidates_info.id' => $cands_result['id']));

                if (!empty($result_education_main)) {
                    $education_count = 1;
                    foreach ($result_education_main as $result_education) {

                        $education_component_status = ($result_education['var_filter_status'] != "") ? $result_education['var_filter_status'] : 'WIP';

                        if (($education_component_status == "WIP") || ($education_component_status == "wip")) {
                            array_push($array_wip, 'Education ' . $education_count);
                        }
                        if (($education_component_status == "Insufficiency") || ($education_component_status == "insufficiency")) {
                            array_push($array_insufficiency, 'Education ' . $education_count);
                            array_push($insufficiency_details, $result_education['university_name'] . ',' . $result_education['qualification_name'] . " || " . $result_education['insuff_raise_remark']);
                        }
                        if (($education_component_status == "Closed") || ($education_component_status == "closed")) {
                            array_push($array_closed, 'Education ' . $education_count);
                        }

                        $education_count++;
                    }
                }
            }

            if (in_array('empver', $component_id)) {
                $result_employment_main = $this->candidates_model->get_employment_ver_status_with_insuff(array('candidates_info.id' => $cands_result['id']));

                if (!empty($result_employment_main)) {
                    $employment_count = 1;

                    foreach ($result_employment_main as $result_employment) {

                        $employment_component_status = ($result_employment['var_filter_status'] != "") ? $result_employment['var_filter_status'] : 'WIP';

                        if (($employment_component_status == "WIP") || ($employment_component_status == "wip")) {
                            array_push($array_wip, "Employment " . $employment_count);
                        }
                        if (($employment_component_status == "Insufficiency") || ($employment_component_status == "insufficiency")) {
                            array_push($array_insufficiency, 'Employment ' . $employment_count);
                            array_push($insufficiency_details, $result_employment['company_name'] . " || " . $result_employment['insuff_raise_remark']);
                        }
                        if (($employment_component_status == "Closed") || ($employment_component_status == "closed")) {
                            array_push($array_closed, 'Employment ' . $employment_count);
                        }
                        $employment_count++;
                    }
                }
            }

            if (in_array('refver', $component_id)) {
                $result_reference_main = $this->candidates_model->get_refver_ver_status_with_insuff(array('reference.candsid' => $cands_result['id']));
                if (!empty($result_reference_main)) {

                    $reference_count = 1;

                    foreach ($result_reference_main as $result_reference) {

                        $reference_component_status = ($result_reference['var_filter_status'] != "") ? $result_reference['var_filter_status'] : 'WIP';

                        if (($reference_component_status == "WIP") || ($reference_component_status == "wip")) {
                            array_push($array_wip, "Reference " . $reference_count);
                        }
                        if (($reference_component_status == "Insufficiency") || ($reference_component_status == "insufficiency")) {
                            array_push($array_insufficiency, 'Reference ' . $reference_count);
                            array_push($insufficiency_details, $result_reference['name_of_reference'] . " || " . $result_reference['insuff_raise_remark']);
                        }
                        if (($reference_component_status == "Closed") || ($reference_component_status == "closed")) {
                            array_push($array_closed, 'Reference ' . $reference_count);
                        }

                        $reference_count++;
                    }

                }

            }

            if (in_array('courtver', $component_id)) {
                $result_court_main = $this->candidates_model->get_court_ver_status_with_insuff(array('candidates_info.id' => $cands_result['id']));

                if (!empty($result_court_main)) {
                    $court_count = 1;

                    foreach ($result_court_main as $result_court) {

                        $court_component_status = ($result_court['var_filter_status'] != "") ? $result_court['var_filter_status'] : 'WIP';
                        if (($court_component_status == "WIP") || ($court_component_status == "wip")) {
                            array_push($array_wip, "Court " . $court_count);
                        }
                        if (($court_component_status == "Insufficiency") || ($court_component_status == "insufficiency")) {
                            array_push($array_insufficiency, 'Court ' . $court_count);
                            array_push($insufficiency_details, $result_court['street_address'] . ',' . $result_court['city'] . ',' . $result_court['pincode'] . ',' . $result_court['state'] . " || " . $result_court['insuff_raise_remark']);
                        }
                        if (($court_component_status == "Closed") || ($court_component_status == "closed")) {
                            array_push($array_closed, 'Court ' . $court_count);
                        }

                        $court_count++;
                    }
                }

            }
            if (in_array('crimver', $component_id)) {

                $result_pcc_main = $this->candidates_model->get_pcc_ver_status_with_insuff(array('candidates_info.id' => $cands_result['id']));

                if (!empty($result_pcc_main)) {

                    $pcc_count = 1;

                    foreach ($result_pcc_main as $result_pcc) {

                        $pcc_component_status = ($result_pcc['var_filter_status'] != "") ? $result_pcc['var_filter_status'] : 'WIP';

                        if (($pcc_component_status == "WIP") || ($pcc_component_status == "wip")) {
                            array_push($array_wip, "PCC " . $pcc_count);
                        }
                        if (($pcc_component_status == "Insufficiency") || ($pcc_component_status == "insufficiency")) {
                            array_push($array_insufficiency, 'PCC ' . $pcc_count);
                            array_push($insufficiency_details, $result_pcc['street_address'] . ',' . $result_pcc['city'] . ',' . $result_pcc['pincode'] . ',' . $result_pcc['state'] . " || " . $result_pcc['insuff_raise_remark']);
                        }
                        if (($pcc_component_status == "Closed") || ($pcc_component_status == "closed")) {
                            array_push($array_closed, 'PCC ' . $pcc_count);
                        }

                        $pcc_count++;
                    }
                }

            }

            if (in_array('globdbver', $component_id)) {
                $result_global_main = $this->candidates_model->get_globdbver_ver_status_with_insuff(array('candidates_info.id' => $cands_result['id']));
                if (!empty($result_global_main)) {

                    $global_count = 1;

                    foreach ($result_global_main as $result_global) {

                        $globaldb_component_status = ($result_global['var_filter_status'] != "") ? $result_global['var_filter_status'] : 'WIP';

                        if (($globaldb_component_status == "WIP") || ($globaldb_component_status == "wip")) {
                            array_push($array_wip, "Global " . $global_count);
                        }
                        if (($globaldb_component_status == "Insufficiency") || ($globaldb_component_status == "insufficiency")) {
                            array_push($array_insufficiency, 'Global ' . $global_count);
                            array_push($insufficiency_details, $result_global['street_address'] . ',' . $result_global['city'] . ',' . $result_global['pincode'] . ',' . $result_global['state'] . " || " . $result_global['insuff_raise_remark']);
                        }
                        if (($globaldb_component_status == "Closed") || ($globaldb_component_status == "closed")) {
                            array_push($array_closed, 'Global ' . $global_count);
                        }

                        $global_count++;
                    }
                }
            }
            if (in_array('identity', $component_id)) {
                $result_identity_main = $this->candidates_model->get_identity_ver_status_with_insuff(array('candidates_info.id' => $cands_result['id']));

                if (!empty($result_identity_main)) {

                    $identity_count = 1;

                    foreach ($result_identity_main as $result_identity) {

                        $identity_component_status = ($result_identity['var_filter_status'] != "") ? $result_identity['var_filter_status'] : 'WIP';

                        if (($identity_component_status == "WIP") || ($identity_component_status == "wip")) {
                            array_push($array_wip, "Identity " . $identity_count);
                        }
                        if (($identity_component_status == "Insufficiency") || ($identity_component_status == "insufficiency")) {
                            array_push($array_insufficiency, 'Identity ' . $identity_count);
                            array_push($insufficiency_details, $result_identity['doc_submited'] . " || " . $result_identity['insuff_raise_remark']);
                        }
                        if (($identity_component_status == "Closed") || ($identity_component_status == "closed")) {
                            array_push($array_closed, 'Identity ' . $identity_count);
                        }

                        $identity_count++;
                    }
                }
            }

            if (in_array('cbrver', $component_id)) {

                $result_credit_report_main = $this->candidates_model->get_credit_reports_ver_status_with_insuff(array('candidates_info.id' => $cands_result['id']));
                if (!empty($result_credit_report_main)) {

                    $credit_report_count = 1;

                    foreach ($result_credit_report_main as $result_credit_report) {

                        $credit_report_component_status = ($result_credit_report['var_filter_status'] != "") ? $result_credit_report['var_filter_status'] : 'WIP';

                        if (($credit_report_component_status == "WIP") || ($credit_report_component_status == "wip")) {
                            array_push($array_wip, "Credit Report " . $credit_report_count);
                        }
                        if (($credit_report_component_status == "Insufficiency") || ($credit_report_component_status == "insufficiency")) {
                            array_push($array_insufficiency, 'Credit Report ' . $credit_report_count);
                            array_push($insufficiency_details, $result_credit_report['doc_submited'] . " || " . $result_credit_report['insuff_raise_remark']);
                        }
                        if (($credit_report_component_status == "Closed") || ($credit_report_component_status == "closed")) {
                            array_push($array_closed, 'Credit Report ' . $credit_report_count);
                        }

                        $credit_report_count++;
                    }
                }
            }

            if (in_array('narcver', $component_id)) {

                $result_drugs_main = $this->candidates_model->get_drugs_ver_status_with_insuff(array('candidates_info.id' => $cands_result['id']));
                if (!empty($result_drugs_main)) {

                    $drugs_count = 1;

                    foreach ($result_drugs_main as $result_drugs) {

                        $drugs_component_status = ($result_drugs['var_filter_status'] != "") ? $result_drugs['var_filter_status'] : 'WIP';

                        if (($drugs_component_status == "WIP") || ($drugs_component_status == "wip")) {
                            array_push($array_wip, "Drugs " . $drugs_count);
                        }
                        if (($drugs_component_status == "Insufficiency") || ($drugs_component_status == "insufficiency")) {
                            array_push($array_insufficiency, 'Drugs ' . $drugs_count);
                            array_push($insufficiency_details, $result_drugs['drug_test_code'] . " || " . $result_drugs['insuff_raise_remark']);
                        }
                        if (($drugs_component_status == "Closed") || ($drugs_component_status == "closed")) {
                            array_push($array_closed, 'Drugs ' . $drugs_count);
                        }

                        $drugs_count++;
                    }
                }
            }
            $array_wips = array();
            foreach ($array_wip as $key => $value) {
                $keys = $key + 1;
                $array_wips[] = $keys . ')' . $value . "\r\n";
            }
            $array_insufficiencys = array();
            foreach ($array_insufficiency as $key => $value) {
                $keys = $key + 1;
                $array_insufficiencys[] = $keys . ')' . $value . "\r\n";
            }
            $array_closeds = array();
            foreach ($array_closed as $key => $value) {
                $keys = $key + 1;
                $array_closeds[] = $keys . ')' . $value . "\r\n";
            }
            $insufficiencys_details = array();
            foreach ($insufficiency_details as $key => $value) {
                $keys = $key + 1;
                $insufficiencys_details[] = $keys . ')' . $value . "\r\n";
            }

            $data_arry[$x]['WIP'] = implode('', $array_wips);
            $data_arry[$x]['Insufficiency'] = implode('', $array_insufficiencys);
            $data_arry[$x]['Closed'] = implode('', $array_closeds);
            $data_arry[$x]['Details'] = implode('', $insufficiencys_details);

            unset($array_wips);
            unset($array_insufficiencys);
            unset($array_closeds);
            unset($insufficiencys_details);
            $x++;
        }
        // print_r($data_arry);exit();
        return $data_arry;
    }

    public function export_to_excel()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {
            set_time_limit(0);
            ini_set('memory_limit', '-1');

            $client_id = ($this->input->post('clientid') != "All") ? $this->input->post('clientid') : false;

            $entity_id = ($this->input->post('entity') != 0) ? $this->input->post('entity') : false;

            $package_id = ($this->input->post('package') != 0) ? $this->input->post('package') : false;

            $client_name = $this->input->post('client_name');

            $fil_by_status = ($this->input->post('fil_by_status') != "All") ? $this->input->post('fil_by_status') : false;

            $fil_by_sub_status = ($this->input->post('fil_by_sub_status') != 0) ? $this->input->post('fil_by_sub_status') : false;


            $all_records = $this->get_all_client_data_for_export($client_id, $entity_id, $package_id, $fil_by_status, $fil_by_sub_status);

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
            $spreadsheet->getActiveSheet()->getStyle('A1:DJ1')->applyFromArray($styleArray);
            // auto fit column to content
            foreach (range('A', 'DJ') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                    ->setWidth(20);
            }
            // set the names of header cells

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A1", 'Client Ref No.')
                ->setCellValue("B1", REFNO)
                ->setCellValue("C1", 'Client Name')
                ->setCellValue("D1", 'Entity')
                ->setCellValue("E1", 'Package')
                ->setCellValue("F1", 'Case Initiated')
                ->setCellValue("G1", 'Candidate Name')
                ->setCellValue("H1", 'Overall Status')
                ->setCellValue("I1", 'Overall Closure Date')

                ->setCellValue("J1", 'Address Status 1')
                ->setCellValue("K1", 'Address')

                ->setCellValue("L1", 'Address Status 2')
                ->setCellValue("M1", 'Address')

                ->setCellValue("N1", 'Address Status 3')
                ->setCellValue("O1", 'Address')

                ->setCellValue("P1", 'Address Status 4')
                ->setCellValue("Q1", 'Address')

                ->setCellValue("R1", 'Address Status 5')
                ->setCellValue("S1", 'Address')

                ->setCellValue("T1", 'Employment Status 1')
                ->setCellValue("U1", 'Employer Company Name')

                ->setCellValue("V1", 'Employment Status 2')
                ->setCellValue("W1", 'Employer Company Name')

                ->setCellValue("X1", 'Employment Status 3')
                ->setCellValue("Y1", 'Employer Company Name')

                ->setCellValue("Z1", 'Employment Status 4')
                ->setCellValue("AA1", 'Employer Company Name')

                ->setCellValue("AB1", 'Employment Status 5')
                ->setCellValue("AC1", 'Employer Company Name')

                ->setCellValue("AD1", 'Education Status 1')
                ->setCellValue("AE1", 'Education University')

                ->setCellValue("AF1", 'Education Status 2')
                ->setCellValue("AG1", 'Education University')

                ->setCellValue("AH1", 'Education Status 3')
                ->setCellValue("AI1", 'Education University')

                ->setCellValue("AJ1", 'Education Status 4')
                ->setCellValue("AK1", 'Education University')

                ->setCellValue("AL1", 'Education Status 5')
                ->setCellValue("AM1", 'Education University')

                ->setCellValue("AN1", 'Reference Status 1')
                ->setCellValue("AO1", 'Reference Name')

                ->setCellValue("AP1", 'Reference Status 2')
                ->setCellValue("AQ1", 'Reference Name')

                ->setCellValue("AR1", 'Reference Status 3')
                ->setCellValue("AS1", 'Reference Name')

                ->setCellValue("AT1", 'Reference Status 4')
                ->setCellValue("AU1", 'Reference Name')

                ->setCellValue("AV1", 'Reference Status 5')
                ->setCellValue("AW1", 'Reference Name')

                ->setCellValue("AX1", 'Court Status 1')
                ->setCellValue("AY1", 'Court Address')

                ->setCellValue("AZ1", 'Court Status 2')
                ->setCellValue("BA1", 'Court Address')

                ->setCellValue("BB1", 'Court Status 3')
                ->setCellValue("BC1", 'Court Address')

                ->setCellValue("BD1", 'Court Status 4')
                ->setCellValue("BE1", 'Court Address')

                ->setCellValue("BF1", 'Court Status 5')
                ->setCellValue("BG1", 'Court Address')

                ->setCellValue("BH1", 'Global Status 1')
                ->setCellValue("BI1", 'Global Address')

                ->setCellValue("BJ1", 'Global Status 2')
                ->setCellValue("BK1", 'Global Address')

                ->setCellValue("BL1", 'Global Status 3')
                ->setCellValue("BM1", 'Global Address')

                ->setCellValue("BN1", 'Global Status 4')
                ->setCellValue("BO1", 'Global Address')

                ->setCellValue("BP1", 'Global Status 5')
                ->setCellValue("BQ1", 'Global Address')

                ->setCellValue("BR1", 'PCC Status 1')
                ->setCellValue("BS1", 'PCC Address')

                ->setCellValue("BT1", 'PCC Status 2')
                ->setCellValue("BU1", 'PCC Address')

                ->setCellValue("BV1", 'PCC Status 3')
                ->setCellValue("BW1", 'PCC Address')

                ->setCellValue("BX1", 'PCC Status 4')
                ->setCellValue("BY1", 'PCC Address')

                ->setCellValue("BZ1", 'PCC Status 5')
                ->setCellValue("CA1", 'PCC Address')

                ->setCellValue("CB1", 'Identity Status 1')
                ->setCellValue("CC1", 'Identity Document')

                ->setCellValue("CD1", 'Identity Status 2')
                ->setCellValue("CE1", 'Identity Document')

                ->setCellValue("CF1", 'Identity Status 3')
                ->setCellValue("CG1", 'Identity Document')

                ->setCellValue("CH1", 'Identity Status 4')
                ->setCellValue("CI1", 'Identity Document')

                ->setCellValue("CJ1", 'Identity Status 5')
                ->setCellValue("CK1", 'Identity Document')

                ->setCellValue("CL1", 'Credit Report Status 1')
                ->setCellValue("CM1", 'Credit Report Details')

                ->setCellValue("CN1", 'Credit Report Status 2')
                ->setCellValue("CO1", 'Credit Report Details')

                ->setCellValue("CP1", 'Credit Report Status 3')
                ->setCellValue("CQ1", 'Credit Report Details')

                ->setCellValue("CR1", 'Credit Report Status 4')
                ->setCellValue("CS1", 'Credit Report Details')

                ->setCellValue("CT1", 'Credit Report Status 5')
                ->setCellValue("CU1", 'Credit Report Details')

                ->setCellValue("CV1", 'Drugs Status 1')
                ->setCellValue("CW1", 'Drugs Details')

                ->setCellValue("CX1", 'Drugs Status 2')
                ->setCellValue("CY1", 'Drugs Details')

                ->setCellValue("CZ1", 'Drugs Status 3')
                ->setCellValue("DA1", 'Credit Report Details')

                ->setCellValue("DB1", 'Drugs Status 4')
                ->setCellValue("DC1", 'Drugs Details')

                ->setCellValue("DD1", 'Drugs Status 5')
                ->setCellValue("DE1", 'Drugs Details')

                ->setCellValue("DF1", 'Insuff Details')
                ->setCellValue("DG1", 'Due Date')
                ->setCellValue("DH1", 'Branch Location')
                ->setCellValue("DI1", 'Candidate Email')
                ->setCellValue("DJ1", 'Contact Number');

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
                    ->setCellValue("G$x", $all_record['CandidateName'])
                    ->setCellValue("H$x", $all_record['overallstatus'])
                    ->setCellValue("I$x", $all_record['overallclosuredate'])
                    ->setCellValue("J$x", $all_record['addrver0'])
                    ->setCellValue("K$x", $all_record['addrver_address0'])
                    ->setCellValue("L$x", $all_record['addrver1'])
                    ->setCellValue("M$x", $all_record['addrver_address1'])
                    ->setCellValue("N$x", $all_record['addrver2'])
                    ->setCellValue("O$x", $all_record['addrver_address2'])
                    ->setCellValue("P$x", $all_record['addrver3'])
                    ->setCellValue("Q$x", $all_record['addrver_address3'])
                    ->setCellValue("R$x", $all_record['addrver4'])
                    ->setCellValue("S$x", $all_record['addrver_address4'])
                    ->setCellValue("T$x", $all_record['empver0'])
                    ->setCellValue("U$x", $all_record['empver_cpm0'])
                    ->setCellValue("V$x", $all_record['empver1'])
                    ->setCellValue("W$x", $all_record['empver_cpm1'])
                    ->setCellValue("X$x", $all_record['empver2'])
                    ->setCellValue("Y$x", $all_record['empver_cpm2'])
                    ->setCellValue("Z$x", $all_record['empver3'])
                    ->setCellValue("AA$x", $all_record['empver_cpm3'])
                    ->setCellValue("AB$x", $all_record['empver4'])
                    ->setCellValue("AC$x", $all_record['empver_cpm4'])
                    ->setCellValue("AD$x", $all_record['eduver0'])
                    ->setCellValue("AE$x", $all_record['eduver_univer0'])
                    ->setCellValue("AF$x", $all_record['eduver1'])
                    ->setCellValue("AG$x", $all_record['eduver_univer1'])
                    ->setCellValue("AH$x", $all_record['eduver2'])
                    ->setCellValue("AI$x", $all_record['eduver_univer2'])
                    ->setCellValue("AJ$x", $all_record['eduver3'])
                    ->setCellValue("AK$x", $all_record['eduver_univer3'])
                    ->setCellValue("AL$x", $all_record['eduver4'])
                    ->setCellValue("AM$x", $all_record['eduver_univer4'])
                    ->setCellValue("AN$x", $all_record['refver0'])
                    ->setCellValue("AO$x", $all_record['refvername0'])
                    ->setCellValue("AP$x", $all_record['refver1'])
                    ->setCellValue("AQ$x", $all_record['refvername1'])
                    ->setCellValue("AR$x", $all_record['refver2'])
                    ->setCellValue("AS$x", $all_record['refvername2'])
                    ->setCellValue("AT$x", $all_record['refver3'])
                    ->setCellValue("AU$x", $all_record['refvername3'])
                    ->setCellValue("AV$x", $all_record['refver4'])
                    ->setCellValue("AW$x", $all_record['refvername4'])
                    ->setCellValue("AX$x", $all_record['courtver0'])
                    ->setCellValue("AY$x", $all_record['courtver_address0'])
                    ->setCellValue("AZ$x", $all_record['courtver1'])
                    ->setCellValue("BA$x", $all_record['courtver_address1'])
                    ->setCellValue("BB$x", $all_record['courtver2'])
                    ->setCellValue("BC$x", $all_record['courtver_address2'])
                    ->setCellValue("BD$x", $all_record['courtver3'])
                    ->setCellValue("BE$x", $all_record['courtver_address3'])
                    ->setCellValue("BF$x", $all_record['courtver4'])
                    ->setCellValue("BG$x", $all_record['courtver_address4'])
                    ->setCellValue("BH$x", $all_record['glodbver0'])
                    ->setCellValue("BI$x", $all_record['glodbver_address0'])
                    ->setCellValue("BJ$x", $all_record['glodbver1'])
                    ->setCellValue("BK$x", $all_record['glodbver_address1'])
                    ->setCellValue("BL$x", $all_record['glodbver2'])
                    ->setCellValue("BM$x", $all_record['glodbver_address2'])
                    ->setCellValue("BN$x", $all_record['glodbver3'])
                    ->setCellValue("BO$x", $all_record['glodbver_address3'])
                    ->setCellValue("BP$x", $all_record['glodbver4'])
                    ->setCellValue("BQ$x", $all_record['glodbver_address4'])
                    ->setCellValue("BR$x", $all_record['crimver0'])
                    ->setCellValue("BS$x", $all_record['crimver_address0'])
                    ->setCellValue("BT$x", $all_record['crimver1'])
                    ->setCellValue("BU$x", $all_record['crimver_address1'])
                    ->setCellValue("BV$x", $all_record['crimver2'])
                    ->setCellValue("BW$x", $all_record['crimver_address2'])
                    ->setCellValue("BX$x", $all_record['crimver3'])
                    ->setCellValue("BY$x", $all_record['crimver_address3'])
                    ->setCellValue("BZ$x", $all_record['crimver4'])
                    ->setCellValue("CA$x", $all_record['crimver_address4'])
                    ->setCellValue("CB$x", $all_record['identity0'])
                    ->setCellValue("CC$x", $all_record['identity_doc0'])
                    ->setCellValue("CD$x", $all_record['identity1'])
                    ->setCellValue("CE$x", $all_record['identity_doc1'])
                    ->setCellValue("CF$x", $all_record['identity2'])
                    ->setCellValue("CG$x", $all_record['identity_doc2'])
                    ->setCellValue("CH$x", $all_record['identity3'])
                    ->setCellValue("CI$x", $all_record['identity_doc3'])
                    ->setCellValue("CJ$x", $all_record['identity4'])
                    ->setCellValue("CK$x", $all_record['identity_doc4'])
                    ->setCellValue("CL$x", $all_record['cbrver0'])
                    ->setCellValue("CM$x", $all_record['cbrver_cibil0'])
                    ->setCellValue("CN$x", $all_record['cbrver1'])
                    ->setCellValue("CO$x", $all_record['cbrver_cibil1'])
                    ->setCellValue("CP$x", $all_record['cbrver2'])
                    ->setCellValue("CQ$x", $all_record['cbrver_cibil2'])
                    ->setCellValue("CR$x", $all_record['cbrver3'])
                    ->setCellValue("CS$x", $all_record['cbrver_cibil3'])
                    ->setCellValue("CT$x", $all_record['cbrver4'])
                    ->setCellValue("CU$x", $all_record['cbrver_cibil4'])
                    ->setCellValue("CV$x", $all_record['drugs0'])
                    ->setCellValue("CW$x", $all_record['drugs_test_code0'])
                    ->setCellValue("CX$x", $all_record['drugs1'])
                    ->setCellValue("CY$x", $all_record['drugs_test_code1'])
                    ->setCellValue("CZ$x", $all_record['drugs2'])
                    ->setCellValue("DA$x", $all_record['drugs_test_code2'])
                    ->setCellValue("DB$x", $all_record['drugs3'])
                    ->setCellValue("DC$x", $all_record['drugs_test_code3'])
                    ->setCellValue("DD$x", $all_record['drugs4'])
                    ->setCellValue("DE$x", $all_record['drugs_test_code4'])
                    ->setCellValue("DF$x", $all_record['Details'])
                    ->setCellValue("DG$x", $all_record['due_date_candidate'])
                    ->setCellValue("DH$x", $all_record['Location'])
                    ->setCellValue("DI$x", $all_record['cands_email_id'])
                    ->setCellValue("DJ$x", $all_record['CandidatesContactNumber']);

                $x++;
            }
            // Rename worksheet
            $spreadsheet->getActiveSheet()->setTitle('Candidate Records');

            $spreadsheet->setActiveSheetIndex(0);

            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment;filename=Candidates Records of .xlsx");
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

            $json_array['file_name'] = "Candidates Records of $client_name";

            $json_array['message'] = "File downloaded successfully,please check in download folder";

            $json_array['status'] = SUCCESS_CODE;
        } else {
            $json_array['message'] = "Something went wrong,please try again";

            $json_array['status'] = ERROR_CODE;
        }

        echo_json($json_array);
    }

    protected  function rename_status_value($value ='')
    {
        if($value == "Clear")
        {
            $status_value = "Green";
        }
        else
        {
            $status_value = $value;   
        }

        return $status_value;
    }

   /* public function export_to_excel_tracker()
    {
        $json_array = array();
      
        if ($this->input->is_ajax_request()) {
            set_time_limit(0);
            ini_set('memory_limit', '-1');

            $client_id = ($this->input->post('clientid') != "All") ? $this->input->post('clientid') : false;



            $entity_id = ($this->input->post('entity') != 0) ? $this->input->post('entity') : false;

            $package_id = ($this->input->post('package') != 0) ? $this->input->post('package') : false;

            $client_name = $this->input->post('client_name');

            $fil_by_status = ($this->input->post('fil_by_status') != "All") ? $this->input->post('fil_by_status') : false;

            $fil_by_sub_status = ($this->input->post('fil_by_sub_status') != 0) ? $this->input->post('fil_by_sub_status') : false;

            $all_records = $this->get_all_client_data_for_export_tracker($client_id, $entity_id, $package_id, $fil_by_status, $fil_by_sub_status);

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
                    'allborders' => array(
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

            $styleborder = array(
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ),
                'borders' => array(
                    'allborders' => array(
                        'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ),
                ),
            );
            $spreadsheet->getActiveSheet()->getStyle('A1:AN1')->applyFromArray($styleArray);
            // auto fit column to content
            foreach (range('A', 'AN') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                    ->setWidth(20);
            }
            // set the names of header cells

            $array = array('address'=> 'address','employment'=> 'employment','education'=> 'education');
         
             $i = 65;
      while ($i <= 90) {
         
         if(in_array('address',$array))
         {
            echo chr($i) . "Address1";
            $i++;
            echo chr($i) . "Address2";
            $i++;
            echo chr($i) . "Address3";
            $i++;
         }
         if(in_array('employment',$array))
         {
            echo chr($i) . "Employment1";
            $i++;
            echo chr($i) . "Employment2";
            $i++;
            echo chr($i) . "Employment3";
            $i++; 
         }
          if(in_array('education',$array))
         {
            echo chr($i) . "Education1";
            $i++;
            echo chr($i) . "Education2";
            $i++;
            echo chr($i) . "Education3";
            $i++;
         }
         break;
      }

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A1", 'Client Ref No.')
                ->setCellValue("B1", REFNO)
                ->setCellValue("C1", 'Client Name')
                ->setCellValue("D1", 'Entity')
                ->setCellValue("E1", 'Spoc/Pack')
                ->setCellValue("F1", 'Case Initiated')
                ->setCellValue("G1", 'Latest Initiation Date')
                ->setCellValue("H1", 'Candidate Name')
                ->setCellValue("I1", 'Overall Status')
                ->setCellValue("J1", 'Overall Closure Date')

                ->setCellValue("K1", 'Address Status 1')

                ->setCellValue("L1", 'Address Status 2')

                ->setCellValue("M1", 'Address Status 3')

                ->setCellValue("N1", 'Employment Status 1')
        
                ->setCellValue("O1", 'Employment Status 2')

                ->setCellValue("P1", 'Employment Status 3')

                ->setCellValue("Q1", 'Education Status 1')

                ->setCellValue("R1", 'Education Status 2')
    
                ->setCellValue("S1", 'Education Status 3')

                ->setCellValue("T1", 'Reference Status 1')
             
                ->setCellValue("U1", 'Reference Status 2')

                ->setCellValue("V1", 'Reference Status 3')
         
                ->setCellValue("W1", 'Court Status 1')
        
                ->setCellValue("X1", 'Court Status 2')
    
                ->setCellValue("Y1", 'Court Status 3')

                ->setCellValue("Z1", 'Global Status 1')
               
                ->setCellValue("AA1", 'Global Status 2')

                ->setCellValue("AB1", 'Global Status 3')

                ->setCellValue("AC1", 'PCC Status 1')

                ->setCellValue("AD1", 'PCC Status 2')
    
                ->setCellValue("AE1", 'PCC Status 3')
        
                ->setCellValue("AF1", 'Identity Status 1')

                ->setCellValue("AG1", 'Identity Status 2')
            
                ->setCellValue("AH1", 'Identity Status 3')
            
                ->setCellValue("AI1", 'Credit Report Status 1')
        
                ->setCellValue("AJ1", 'Credit Report Status 2')

                ->setCellValue("AK1", 'Credit Report Status 3')

                ->setCellValue("AL1", 'Insuff Details')

                ->setCellValue("AM1", 'Insuff Raised Date')

                ->setCellValue("AN1", 'Insuff Clear Date');

            // Add some data
            $x = 2;
            foreach ($all_records as $all_record) {

                $spreadsheet->getActiveSheet()->getStyle("A$x:AN$x")->applyFromArray($styleborder);
                $this->cellColorStatus("I$x",$all_record['overallstatus'],$spreadsheet);
                $this->cellColorStatus("K$x",$all_record['addrver0'],$spreadsheet);
                $this->cellColorStatus("L$x",$all_record['addrver1'],$spreadsheet);
                $this->cellColorStatus("M$x",$all_record['addrver2'],$spreadsheet);
                $this->cellColorStatus("N$x",$all_record['empver0'],$spreadsheet);
                $this->cellColorStatus("O$x",$all_record['empver1'],$spreadsheet);
                $this->cellColorStatus("P$x",$all_record['empver2'],$spreadsheet);
                $this->cellColorStatus("Q$x",$all_record['eduver0'],$spreadsheet);
                $this->cellColorStatus("R$x",$all_record['eduver1'],$spreadsheet);
                $this->cellColorStatus("S$x",$all_record['eduver2'],$spreadsheet);
                $this->cellColorStatus("T$x",$all_record['refver0'],$spreadsheet);
                $this->cellColorStatus("U$x",$all_record['refver1'],$spreadsheet);
                $this->cellColorStatus("V$x",$all_record['refver2'],$spreadsheet);
                $this->cellColorStatus("W$x",$all_record['courtver0'],$spreadsheet);
                $this->cellColorStatus("X$x",$all_record['courtver1'],$spreadsheet);
                $this->cellColorStatus("Y$x",$all_record['courtver2'],$spreadsheet);
                $this->cellColorStatus("Z$x",$all_record['glodbver0'],$spreadsheet);
                $this->cellColorStatus("AA$x",$all_record['glodbver1'],$spreadsheet);
                $this->cellColorStatus("AB$x",$all_record['glodbver2'],$spreadsheet);
                $this->cellColorStatus("AC$x",$all_record['crimver0'],$spreadsheet);
                $this->cellColorStatus("AD$x",$all_record['crimver1'],$spreadsheet);
                $this->cellColorStatus("AE$x",$all_record['crimver2'],$spreadsheet);
                $this->cellColorStatus("AF$x",$all_record['identity0'],$spreadsheet);
                $this->cellColorStatus("AG$x",$all_record['identity1'],$spreadsheet);
                $this->cellColorStatus("AH$x",$all_record['identity2'],$spreadsheet);
                $this->cellColorStatus("AI$x",$all_record['cbrver0'],$spreadsheet);
                $this->cellColorStatus("AJ$x",$all_record['cbrver1'],$spreadsheet);
                $this->cellColorStatus("AK$x",$all_record['cbrver2'],$spreadsheet);
              
                
            
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A$x", $all_record['ClientRefNumber'])
                    ->setCellValue("B$x", $all_record['cmp_ref_no'])
                    ->setCellValue("C$x", $all_record['clientname'])
                    ->setCellValue("D$x", $all_record['entity_name'])
                    ->setCellValue("E$x", $all_record['package_name'])
                    ->setCellValue("F$x", $all_record['caserecddate'])
                    ->setCellValue("G$x", convert_db_to_display_date($all_record['latest_date']))
                    ->setCellValue("H$x", $all_record['CandidateName'])
                    ->setCellValue("I$x", $this->rename_status_value($all_record['overallstatus']))
                    ->setCellValue("J$x", $all_record['overallclosuredate'])
                    ->setCellValue("K$x", $this->rename_status_value($all_record['addrver0']))
                    ->setCellValue("L$x", $this->rename_status_value($all_record['addrver1']))
                    ->setCellValue("M$x", $this->rename_status_value($all_record['addrver2']))
                    ->setCellValue("N$x", $this->rename_status_value($all_record['empver0']))
                    ->setCellValue("O$x", $this->rename_status_value($all_record['empver1']))
                    ->setCellValue("P$x", $this->rename_status_value($all_record['empver2']))
                    ->setCellValue("Q$x", $this->rename_status_value($all_record['eduver0']))
                    ->setCellValue("R$x", $this->rename_status_value($all_record['eduver1']))
                    ->setCellValue("S$x", $this->rename_status_value($all_record['eduver2']))
                    ->setCellValue("T$x", $this->rename_status_value($all_record['refver0']))
                    ->setCellValue("U$x", $this->rename_status_value($all_record['refver1']))
                    ->setCellValue("V$x", $this->rename_status_value($all_record['refver2']))
                    ->setCellValue("W$x", $this->rename_status_value($all_record['courtver0']))
                    ->setCellValue("X$x", $this->rename_status_value($all_record['courtver1']))
                    ->setCellValue("Y$x", $this->rename_status_value($all_record['courtver2']))
                    ->setCellValue("Z$x", $this->rename_status_value($all_record['glodbver0']))
                    ->setCellValue("AA$x", $this->rename_status_value($all_record['glodbver1']))
                    ->setCellValue("AB$x", $this->rename_status_value($all_record['glodbver2']))
                    ->setCellValue("AC$x", $this->rename_status_value($all_record['crimver0']))
                    ->setCellValue("AD$x", $this->rename_status_value($all_record['crimver1']))
                    ->setCellValue("AE$x", $this->rename_status_value($all_record['crimver2']))
                    ->setCellValue("AF$x", $this->rename_status_value($all_record['identity0']))
                    ->setCellValue("AG$x", $this->rename_status_value($all_record['identity1']))
                    ->setCellValue("AH$x", $this->rename_status_value($all_record['identity2']))
                    ->setCellValue("AI$x", $this->rename_status_value($all_record['cbrver0']))
                    ->setCellValue("AJ$x", $this->rename_status_value($all_record['cbrver1']))
                    ->setCellValue("AK$x", $this->rename_status_value($all_record['cbrver2']))
                    ->setCellValue("AL$x", $all_record['Details'])
                    ->setCellValue("AM$x", $all_record['insuff_raise_date'])
                    ->setCellValue("AN$x", $all_record['insuff_clear_date']);

                $x++;
            }
            // Rename worksheet
            $spreadsheet->getActiveSheet()->setTitle('Candidate Records Tracker');

            $spreadsheet->setActiveSheetIndex(0);

            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment;filename=Candidates Records of .xlsx");
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

            $json_array['file_name'] = "Candidates Records of $client_name";

            $json_array['message'] = "File downloaded successfully,please check in download folder";

            $json_array['status'] = SUCCESS_CODE;
        } else {
            $json_array['message'] = "Something went wrong,please try again";

            $json_array['status'] = ERROR_CODE;
        }

        echo_json($json_array);
    }*/

    public function export_to_excel_tracker()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {
            set_time_limit(0);
            ini_set('memory_limit', '-1');

            $client_id = ($this->input->post('clientid') != "All") ? $this->input->post('clientid') : false;



            $entity_id = ($this->input->post('entity') != 0) ? $this->input->post('entity') : false;

            $package_id = ($this->input->post('package') != 0) ? $this->input->post('package') : false;

            $client_name = $this->input->post('client_name');

            $fil_by_status = ($this->input->post('fil_by_status') != "All") ? $this->input->post('fil_by_status') : false;

            $fil_by_sub_status = ($this->input->post('fil_by_sub_status') != 0) ? $this->input->post('fil_by_sub_status') : false;

    
            $all_records = $this->get_all_client_data_for_export_tracker($client_id, $entity_id, $package_id, $fil_by_status, $fil_by_sub_status);

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
                    'allborders' => array(
                        'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ),
                ),
                'fill' => array(
                    'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => array(
                        'argb' => 'FFA500',
                    ),
                    'endcolor' => array(
                        'argb' => 'FFFFFFFF',
                    ),
                ),
            );

            $styleborder = array(
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ),
                'borders' => array(
                    'allborders' => array(
                        'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ),
                ),
            );
            $spreadsheet->getActiveSheet()->getStyle('A1:AR1')->applyFromArray($styleArray);
            // auto fit column to content
            foreach (range('A', 'AR') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                    ->setWidth(20);
            }
            // set the names of header cells


            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A1", 'Client Ref No.')
                ->setCellValue("B1", REFNO)
                ->setCellValue("C1", 'Client Name')
                ->setCellValue("D1", 'Entity')
                ->setCellValue("E1", 'Spoc/Pack')
                ->setCellValue("F1", 'Case Initiated')
                ->setCellValue("G1", 'Latest Initiation Date')
                ->setCellValue("H1", 'Candidate Name')
                ->setCellValue("I1", 'Overall Status')
                ->setCellValue("J1", 'Overall Closure Date')

                ->setCellValue("K1", 'Address Status 1')

                ->setCellValue("L1", 'Address Status 2')

                ->setCellValue("M1", 'Address Status 3')

                ->setCellValue("N1", 'Employment Status 1')
        
                ->setCellValue("O1", 'Employment Status 2')

                ->setCellValue("P1", 'Employment Status 3')

                ->setCellValue("Q1", 'Education Status 1')

                ->setCellValue("R1", 'Education Status 2')
    
                ->setCellValue("S1", 'Education Status 3')

                ->setCellValue("T1", 'Reference Status 1')
             
                ->setCellValue("U1", 'Reference Status 2')

                ->setCellValue("V1", 'Reference Status 3')
         
                ->setCellValue("W1", 'Court Status 1')
        
                ->setCellValue("X1", 'Court Status 2')
    
                ->setCellValue("Y1", 'Court Status 3')

                ->setCellValue("Z1", 'Global Status 1')
               
                ->setCellValue("AA1", 'Global Status 2')

                ->setCellValue("AB1", 'Global Status 3')

                ->setCellValue("AC1", 'PCC Status 1')

                ->setCellValue("AD1", 'PCC Status 2')
    
                ->setCellValue("AE1", 'PCC Status 3')
        
                ->setCellValue("AF1", 'Identity Status 1')

                ->setCellValue("AG1", 'Identity Status 2')
            
                ->setCellValue("AH1", 'Identity Status 3')
            
                ->setCellValue("AI1", 'Credit Report Status 1')
        
                ->setCellValue("AJ1", 'Credit Report Status 2')

                ->setCellValue("AK1", 'Credit Report Status 3')


                ->setCellValue("AL1", 'Drugs Report Status 1')
        
                ->setCellValue("AM1", 'Drugs Report Status 2')

                ->setCellValue("AN1", 'Drugs Report Status 3')


                ->setCellValue("AO1", 'Discrepancy Details')

                ->setCellValue("AP1", 'Insuff Details')

                ->setCellValue("AQ1", 'Insuff Raised Date')

                ->setCellValue("AR1", 'Insuff Clear Date');

            // Add some data
            $x = 2;
            foreach ($all_records as $all_record) {

                $spreadsheet->getActiveSheet()->getStyle("A$x:AO$x")->applyFromArray($styleborder);
                $this->cellColorStatus("I$x",$all_record['overallstatus'],$spreadsheet);
                $this->cellColorStatus("K$x",$all_record['addrver0'],$spreadsheet);
                $this->cellColorStatus("L$x",$all_record['addrver1'],$spreadsheet);
                $this->cellColorStatus("M$x",$all_record['addrver2'],$spreadsheet);
                $this->cellColorStatus("N$x",$all_record['empver0'],$spreadsheet);
                $this->cellColorStatus("O$x",$all_record['empver1'],$spreadsheet);
                $this->cellColorStatus("P$x",$all_record['empver2'],$spreadsheet);
                $this->cellColorStatus("Q$x",$all_record['eduver0'],$spreadsheet);
                $this->cellColorStatus("R$x",$all_record['eduver1'],$spreadsheet);
                $this->cellColorStatus("S$x",$all_record['eduver2'],$spreadsheet);
                $this->cellColorStatus("T$x",$all_record['refver0'],$spreadsheet);
                $this->cellColorStatus("U$x",$all_record['refver1'],$spreadsheet);
                $this->cellColorStatus("V$x",$all_record['refver2'],$spreadsheet);
                $this->cellColorStatus("W$x",$all_record['courtver0'],$spreadsheet);
                $this->cellColorStatus("X$x",$all_record['courtver1'],$spreadsheet);
                $this->cellColorStatus("Y$x",$all_record['courtver2'],$spreadsheet);
                $this->cellColorStatus("Z$x",$all_record['glodbver0'],$spreadsheet);
                $this->cellColorStatus("AA$x",$all_record['glodbver1'],$spreadsheet);
                $this->cellColorStatus("AB$x",$all_record['glodbver2'],$spreadsheet);
                $this->cellColorStatus("AC$x",$all_record['crimver0'],$spreadsheet);
                $this->cellColorStatus("AD$x",$all_record['crimver1'],$spreadsheet);
                $this->cellColorStatus("AE$x",$all_record['crimver2'],$spreadsheet);
                $this->cellColorStatus("AF$x",$all_record['identity0'],$spreadsheet);
                $this->cellColorStatus("AG$x",$all_record['identity1'],$spreadsheet);
                $this->cellColorStatus("AH$x",$all_record['identity2'],$spreadsheet);
                $this->cellColorStatus("AI$x",$all_record['cbrver0'],$spreadsheet);
                $this->cellColorStatus("AJ$x",$all_record['cbrver1'],$spreadsheet);
                $this->cellColorStatus("AK$x",$all_record['cbrver2'],$spreadsheet);
                $this->cellColorStatus("AL$x",$all_record['drugs0'],$spreadsheet);
                $this->cellColorStatus("AM$x",$all_record['drugs1'],$spreadsheet);
                $this->cellColorStatus("AN$x",$all_record['drugs2'],$spreadsheet);
              
                
            
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A$x", $all_record['ClientRefNumber'])
                    ->setCellValue("B$x", $all_record['cmp_ref_no'])
                    ->setCellValue("C$x", $all_record['clientname'])
                    ->setCellValue("D$x", $all_record['entity_name'])
                    ->setCellValue("E$x", $all_record['package_name'])
                    ->setCellValue("F$x", $all_record['caserecddate'])
                    ->setCellValue("G$x", convert_db_to_display_date($all_record['latest_date']))
                    ->setCellValue("H$x", $all_record['CandidateName'])
                    ->setCellValue("I$x", $this->rename_status_value($all_record['overallstatus']))
                    ->setCellValue("J$x", $all_record['overallclosuredate'])
                    ->setCellValue("K$x", $this->rename_status_value($all_record['addrver0']))
                    ->setCellValue("L$x", $this->rename_status_value($all_record['addrver1']))
                    ->setCellValue("M$x", $this->rename_status_value($all_record['addrver2']))
                    ->setCellValue("N$x", $this->rename_status_value($all_record['empver0']))
                    ->setCellValue("O$x", $this->rename_status_value($all_record['empver1']))
                    ->setCellValue("P$x", $this->rename_status_value($all_record['empver2']))
                    ->setCellValue("Q$x", $this->rename_status_value($all_record['eduver0']))
                    ->setCellValue("R$x", $this->rename_status_value($all_record['eduver1']))
                    ->setCellValue("S$x", $this->rename_status_value($all_record['eduver2']))
                    ->setCellValue("T$x", $this->rename_status_value($all_record['refver0']))
                    ->setCellValue("U$x", $this->rename_status_value($all_record['refver1']))
                    ->setCellValue("V$x", $this->rename_status_value($all_record['refver2']))
                    ->setCellValue("W$x", $this->rename_status_value($all_record['courtver0']))
                    ->setCellValue("X$x", $this->rename_status_value($all_record['courtver1']))
                    ->setCellValue("Y$x", $this->rename_status_value($all_record['courtver2']))
                    ->setCellValue("Z$x", $this->rename_status_value($all_record['glodbver0']))
                    ->setCellValue("AA$x", $this->rename_status_value($all_record['glodbver1']))
                    ->setCellValue("AB$x", $this->rename_status_value($all_record['glodbver2']))
                    ->setCellValue("AC$x", $this->rename_status_value($all_record['crimver0']))
                    ->setCellValue("AD$x", $this->rename_status_value($all_record['crimver1']))
                    ->setCellValue("AE$x", $this->rename_status_value($all_record['crimver2']))
                    ->setCellValue("AF$x", $this->rename_status_value($all_record['identity0']))
                    ->setCellValue("AG$x", $this->rename_status_value($all_record['identity1']))
                    ->setCellValue("AH$x", $this->rename_status_value($all_record['identity2']))
                    ->setCellValue("AI$x", $this->rename_status_value($all_record['cbrver0']))
                    ->setCellValue("AJ$x", $this->rename_status_value($all_record['cbrver1']))
                    ->setCellValue("AK$x", $this->rename_status_value($all_record['cbrver2']))
                    ->setCellValue("AL$x", $this->rename_status_value($all_record['drugs0']))
                    ->setCellValue("AM$x", $this->rename_status_value($all_record['drugs1']))
                    ->setCellValue("AN$x", $this->rename_status_value($all_record['drugs2']))
                    ->setCellValue("AO$x", $all_record['DiscrepancyDetails'])
                    ->setCellValue("AP$x", $all_record['Details'])
                    ->setCellValue("AQ$x", $all_record['insuff_raise_date'])
                    ->setCellValue("AR$x", $all_record['insuff_clear_date']);

                $x++;
            }
            // Rename worksheet
            $spreadsheet->getActiveSheet()->setTitle('Candidate Records Tracker');

            $spreadsheet->setActiveSheetIndex(0);

            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment;filename=Candidates Records of .xlsx");
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

            $json_array['file_name'] = "Candidates Records of $client_name";

            $json_array['message'] = "File downloaded successfully,please check in download folder";

            $json_array['status'] = SUCCESS_CODE;
        } else {
            $json_array['message'] = "Something went wrong,please try again";

            $json_array['status'] = ERROR_CODE;
        }

        echo_json($json_array);
    }

    public function export_to_excel_prepost()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {
            set_time_limit(0);
            ini_set('memory_limit', '-1');

            $client_id = ($this->input->post('clientid') != "All") ? $this->input->post('clientid') : false;

            $entity_id = ($this->input->post('entity') != 0) ? $this->input->post('entity') : false;

            $package_id = ($this->input->post('package') != 0) ? $this->input->post('package') : false;

            $client_name = $this->input->post('client_name');

            $all_records = $this->get_all_client_data_for_export_prepost($client_id, $entity_id, $package_id);

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

            $spreadsheet->createSheet();
            $work_sheet_count=1;
            $work_sheet = 0;

            while($work_sheet<=$work_sheet_count)
            {
                if($work_sheet==0)
                {
                    if($client_id == 3 || $client_id == 4 || $client_id == 5)
                    {
                        $spreadsheet->getActiveSheet($work_sheet)->getStyle('A1:T1')->applyFromArray($styleArray);

                        // auto fit column to content
                        foreach(range('A','T') as $columnID) {
                            $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setWidth(20);
                        }
                        // set the names of header cells

                        $spreadsheet->setActiveSheetIndex($work_sheet)

                        ->setCellValue("A1",'Client Ref No.')
                        ->setCellValue("B1",'CRM Ref No.')
                        ->setCellValue("C1",'Client Name')
                        ->setCellValue("D1",'Entity')
                        ->setCellValue("E1",'Spoc/Pack')
                        ->setCellValue("F1",'Case Initiated')
                        ->setCellValue("G1",'Latest Initiation Date')
                        ->setCellValue("H1",'Candidate Name')
                        ->setCellValue("I1",'Overall Status')
                        ->setCellValue("J1",'Overall Closure Date')
                        ->setCellValue("K1",'Identity 1')
                        ->setCellValue("L1",'Identity 2')
                        ->setCellValue("M1",'Identity 3')
                        ->setCellValue("N1",'Court 1')
                        ->setCellValue("O1",'Court 2')
                        ->setCellValue("P1",'Court 3')
                        ->setCellValue("Q1",'Discrepancy Details')
                        ->setCellValue("R1",'Insuff Details')
                        ->setCellValue("S1",'Insuff Raised Date')
                        ->setCellValue("T1",'Insuff Clear Date');
 
                        // Add some data
                        $x = 2;
                        foreach ($all_records as $all_record) {

                            $spreadsheet->setActiveSheetIndex($work_sheet);

                            $spreadsheet->getActiveSheet()->setCellValue("A$x",$all_record['ClientRefNumber']);
                            $spreadsheet->getActiveSheet()->setCellValue("B$x",$all_record['cmp_ref_no']);
                            $spreadsheet->getActiveSheet()->setCellValue("C$x",$all_record['clientname']);
                            $spreadsheet->getActiveSheet()->setCellValue("D$x",$all_record['entity_name']); 
                            $spreadsheet->getActiveSheet()->setCellValue("E$x",$all_record['package_name']); 
                            $spreadsheet->getActiveSheet()->setCellValue("F$x",$all_record['case_initiation_pre_min']); 
                            $spreadsheet->getActiveSheet()->setCellValue("G$x",$all_record['case_initiation_pre_max']);
                            $spreadsheet->getActiveSheet()->setCellValue("H$x",$all_record['CandidateName']);
                            $spreadsheet->getActiveSheet()->setCellValue("I$x",$all_record['final_status_pre']);
                            $spreadsheet->getActiveSheet()->setCellValue("J$x",$all_record['overallclosuredate_pre']);
                            $spreadsheet->getActiveSheet()->setCellValue("K$x",$all_record['identity0']);
                            $spreadsheet->getActiveSheet()->setCellValue("L$x",$all_record['identity1']);
                            $spreadsheet->getActiveSheet()->setCellValue("M$x",$all_record['identity2']);
                            $spreadsheet->getActiveSheet()->setCellValue("N$x",$all_record['courtver0']);
                            $spreadsheet->getActiveSheet()->setCellValue("O$x",$all_record['courtver1']); 
                            $spreadsheet->getActiveSheet()->setCellValue("P$x",$all_record['courtver2']);
                            $spreadsheet->getActiveSheet()->setCellValue("Q$x",$all_record['DiscrepancyDetails_pre']);
                            $spreadsheet->getActiveSheet()->setCellValue("R$x",$all_record['Details_pre']);
                            $spreadsheet->getActiveSheet()->setCellValue("S$x",$all_record['insuff_raise_date_pre']);
                            $spreadsheet->getActiveSheet()->setCellValue("T$x",$all_record['insuff_clear_date_pre']);
                         

                            $x++;
  
                        }

                        $sheetData1 = $spreadsheet->getActiveSheet()->toArray(null,true,true,true); 

                        $sheetData2  =  count($sheetData1); 

                        $sheetData_pre   =   $sheetData2 - 1; 

                        $spreadsheet->getActiveSheet()->setTitle('Pre -'.$sheetData_pre);
 
                        $spreadsheet->setActiveSheetIndex($work_sheet);
                    }
                    if($client_id == 33)
                    {
                        $spreadsheet->getActiveSheet($work_sheet)->getStyle('A1:T1')->applyFromArray($styleArray);

                        // auto fit column to content
                        foreach(range('A','T') as $columnID) {
                            $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setWidth(20);
                        }
                        // set the names of header cells

                        $spreadsheet->setActiveSheetIndex($work_sheet)

                        ->setCellValue("A1",'Client Ref No.')
                        ->setCellValue("B1",'CRM Ref No.')
                        ->setCellValue("C1",'Client Name')
                        ->setCellValue("D1",'Entity')
                        ->setCellValue("E1",'Spoc/Pack')
                        ->setCellValue("F1",'Case Initiated')
                        ->setCellValue("G1",'Latest Initiation Date')
                        ->setCellValue("H1",'Candidate Name')
                        ->setCellValue("I1",'Overall Status')
                        ->setCellValue("J1",'Overall Closure Date')
                        ->setCellValue("K1",'Address 1')
                        ->setCellValue("L1",'Address 2')
                        ->setCellValue("M1",'Address 3')
                        ->setCellValue("N1",'Court 1')
                        ->setCellValue("O1",'Court 2')
                        ->setCellValue("P1",'Court 3')
                        ->setCellValue("Q1",'Discrepancy Details')
                        ->setCellValue("R1",'Insuff Details')
                        ->setCellValue("S1",'Insuff Raised Date')
                        ->setCellValue("T1",'Insuff Clear Date');
 
                        // Add some data
                        $x = 2;
                        foreach ($all_records as $all_record) {

                            $spreadsheet->setActiveSheetIndex($work_sheet);

                            
                            $spreadsheet->getActiveSheet()->setCellValue("A$x",$all_record['ClientRefNumber']);
                            $spreadsheet->getActiveSheet()->setCellValue("B$x",$all_record['cmp_ref_no']);
                            $spreadsheet->getActiveSheet()->setCellValue("C$x",$all_record['clientname']);
                            $spreadsheet->getActiveSheet()->setCellValue("D$x",$all_record['entity_name']); 
                            $spreadsheet->getActiveSheet()->setCellValue("E$x",$all_record['package_name']); 
                            $spreadsheet->getActiveSheet()->setCellValue("F$x",$all_record['case_initiation_pre_min']); 
                            $spreadsheet->getActiveSheet()->setCellValue("G$x",$all_record['case_initiation_pre_max']);
                            $spreadsheet->getActiveSheet()->setCellValue("H$x",$all_record['CandidateName']);
                            $spreadsheet->getActiveSheet()->setCellValue("I$x",$all_record['final_status_pre']);
                            $spreadsheet->getActiveSheet()->setCellValue("J$x",$all_record['overallclosuredate_pre']);
                            $spreadsheet->getActiveSheet()->setCellValue("K$x",$all_record['addrver0']);
                            $spreadsheet->getActiveSheet()->setCellValue("L$x",$all_record['addrver1']);
                            $spreadsheet->getActiveSheet()->setCellValue("M$x",$all_record['addrver2']);
                            $spreadsheet->getActiveSheet()->setCellValue("N$x",$all_record['courtver0']);
                            $spreadsheet->getActiveSheet()->setCellValue("O$x",$all_record['courtver1']); 
                            $spreadsheet->getActiveSheet()->setCellValue("P$x",$all_record['courtver2']);
                            $spreadsheet->getActiveSheet()->setCellValue("Q$x",$all_record['DiscrepancyDetails_pre']);
                            $spreadsheet->getActiveSheet()->setCellValue("R$x",$all_record['Details_pre']);
                            $spreadsheet->getActiveSheet()->setCellValue("S$x",$all_record['insuff_raise_date_pre']);
                            $spreadsheet->getActiveSheet()->setCellValue("T$x",$all_record['insuff_clear_date_pre']);

                            $x++;
  
                        }

                        $sheetData1 = $spreadsheet->getActiveSheet()->toArray(null,true,true,true); 

                        $sheetData2  =  count($sheetData1); 

                        $sheetData_pre   =   $sheetData2 - 1; 

                        $spreadsheet->getActiveSheet()->setTitle('Pre -'.$sheetData_pre);
 
                        $spreadsheet->setActiveSheetIndex($work_sheet);
                    }
                }
                if($work_sheet==1)
                { 
                    if($client_id == 3 || $client_id == 4 || $client_id == 5)
                    {  
                        $spreadsheet->getActiveSheet($work_sheet)->getStyle('A1:T1')->applyFromArray($styleArray);

                        // auto fit column to content
                        foreach(range('A','T') as $columnID) {
                            $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setWidth(20);
                        }
                        // set the names of header cells

                        $spreadsheet->setActiveSheetIndex($work_sheet)

                            ->setCellValue("A1",'Client Ref No.')
                            ->setCellValue("B1",'CRM Ref No.')
                            ->setCellValue("C1",'Client Name')
                            ->setCellValue("D1",'Entity')
                            ->setCellValue("E1",'Spoc/Pack')
                            ->setCellValue("F1",'Case Initiated')
                            ->setCellValue("G1",'Latest Initiation Date')
                            ->setCellValue("H1",'Candidate Name')
                            ->setCellValue("I1",'Overall Status')
                            ->setCellValue("J1",'Overall Closure Date')
                            ->setCellValue("K1",'Address 1')
                            ->setCellValue("L1",'Address 2')
                            ->setCellValue("M1",'Address 3')
                            ->setCellValue("N1",'Employment 1')
                            ->setCellValue("O1",'Employment 2')
                            ->setCellValue("P1",'Employment 3')
                            ->setCellValue("Q1",'Discrepancy Details')
                            ->setCellValue("R1",'Insuff Details')
                            ->setCellValue("S1",'Insuff Raised Date')
                            ->setCellValue("T1",'Insuff Clear Date');
     
                        // Add some data
                        $x = 2;
                        foreach ($all_records as $all_record) {

                            $spreadsheet->setActiveSheetIndex($work_sheet);
                            
                            $spreadsheet->getActiveSheet()->setCellValue("A$x",$all_record['ClientRefNumber']);
                            $spreadsheet->getActiveSheet()->setCellValue("B$x",$all_record['cmp_ref_no']);
                            $spreadsheet->getActiveSheet()->setCellValue("C$x",$all_record['clientname']);
                            $spreadsheet->getActiveSheet()->setCellValue("D$x",$all_record['entity_name']); 
                            $spreadsheet->getActiveSheet()->setCellValue("E$x",$all_record['package_name']); 
                            $spreadsheet->getActiveSheet()->setCellValue("F$x",$all_record['case_initiation_post_min']); 
                            $spreadsheet->getActiveSheet()->setCellValue("G$x",$all_record['case_initiation_post_max']);
                            $spreadsheet->getActiveSheet()->setCellValue("H$x",$all_record['CandidateName']);
                            $spreadsheet->getActiveSheet()->setCellValue("I$x",$all_record['final_status_post']);
                            $spreadsheet->getActiveSheet()->setCellValue("J$x",$all_record['overallclosuredate_post']);
                            $spreadsheet->getActiveSheet()->setCellValue("K$x",$all_record['addrver0']);
                            $spreadsheet->getActiveSheet()->setCellValue("L$x",$all_record['addrver1']);
                            $spreadsheet->getActiveSheet()->setCellValue("M$x",$all_record['addrver2']);
                            $spreadsheet->getActiveSheet()->setCellValue("N$x",$all_record['empver0']);
                            $spreadsheet->getActiveSheet()->setCellValue("O$x",$all_record['empver1']); 
                            $spreadsheet->getActiveSheet()->setCellValue("P$x",$all_record['empver2']);
                            $spreadsheet->getActiveSheet()->setCellValue("Q$x",$all_record['DiscrepancyDetails_post']);
                            $spreadsheet->getActiveSheet()->setCellValue("R$x",$all_record['Details_post']);
                            $spreadsheet->getActiveSheet()->setCellValue("S$x",$all_record['insuff_raise_date_post']);
                            $spreadsheet->getActiveSheet()->setCellValue("T$x",$all_record['insuff_clear_date_post']);

                            $x++;
  
                        }

                        $sheetData1 = $spreadsheet->getActiveSheet()->toArray(null,true,true,true); 

                        $sheetData2  =  count($sheetData1); 

                        $sheetData_post   =   $sheetData2 - 1; 

                        $spreadsheet->getActiveSheet()->setTitle('Post -'.$sheetData_post);
 
                        $spreadsheet->setActiveSheetIndex($work_sheet);
                    }

                    if($client_id == 33)
                    {  
                        $spreadsheet->getActiveSheet($work_sheet)->getStyle('A1:Q1')->applyFromArray($styleArray);

                        // auto fit column to content
                        foreach(range('A','Q') as $columnID) {
                            $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setWidth(20);
                        }
                        // set the names of header cells

                        $spreadsheet->setActiveSheetIndex($work_sheet)

                            ->setCellValue("A1",'Client Ref No.')
                            ->setCellValue("B1",'CRM Ref No.')
                            ->setCellValue("C1",'Client Name')
                            ->setCellValue("D1",'Entity')
                            ->setCellValue("E1",'Spoc/Pack')
                            ->setCellValue("F1",'Case Initiated')
                            ->setCellValue("G1",'Latest Initiation Date')
                            ->setCellValue("H1",'Candidate Name')
                            ->setCellValue("I1",'Overall Status')
                            ->setCellValue("J1",'Overall Closure Date')
                            ->setCellValue("K1",'Employment 1')
                            ->setCellValue("L1",'Employment 2')
                            ->setCellValue("M1",'Employment 3')
                            ->setCellValue("N1",'Discrepancy Details')
                            ->setCellValue("O1",'Insuff Details')
                            ->setCellValue("O1",'Insuff Raised Date')
                            ->setCellValue("R1",'Insuff Clear Date');
     
                        // Add some data
                        $x = 2;
                        foreach ($all_records as $all_record) {

                            $spreadsheet->setActiveSheetIndex($work_sheet);
                            
                           
                            $spreadsheet->getActiveSheet()->setCellValue("A$x",$all_record['ClientRefNumber']);
                            $spreadsheet->getActiveSheet()->setCellValue("B$x",$all_record['cmp_ref_no']);
                            $spreadsheet->getActiveSheet()->setCellValue("C$x",$all_record['clientname']);
                            $spreadsheet->getActiveSheet()->setCellValue("D$x",$all_record['entity_name']); 
                            $spreadsheet->getActiveSheet()->setCellValue("E$x",$all_record['package_name']); 
                            $spreadsheet->getActiveSheet()->setCellValue("F$x",$all_record['case_initiation_pre_min']); 
                            $spreadsheet->getActiveSheet()->setCellValue("G$x",$all_record['case_initiation_pre_max']);
                            $spreadsheet->getActiveSheet()->setCellValue("H$x",$all_record['CandidateName']);
                            $spreadsheet->getActiveSheet()->setCellValue("I$x",$all_record['final_status_pre']);
                            $spreadsheet->getActiveSheet()->setCellValue("J$x",$all_record['overallclosuredate_pre']);
                            $spreadsheet->getActiveSheet()->setCellValue("K$x",$all_record['empver0']);
                            $spreadsheet->getActiveSheet()->setCellValue("L$x",$all_record['empver1']);
                            $spreadsheet->getActiveSheet()->setCellValue("M$x",$all_record['empver2']);
                            $spreadsheet->getActiveSheet()->setCellValue("O$x",$all_record['DiscrepancyDetails_pre']);
                            $spreadsheet->getActiveSheet()->setCellValue("P$x",$all_record['Details_pre']);
                            $spreadsheet->getActiveSheet()->setCellValue("Q$x",$all_record['insuff_raise_date_pre']);
                            $spreadsheet->getActiveSheet()->setCellValue("R$x",$all_record['insuff_clear_date_pre']);

                            $x++;
  
                        }

                        $sheetData1 = $spreadsheet->getActiveSheet()->toArray(null,true,true,true); 

                        $sheetData2  =  count($sheetData1); 

                        $sheetData_post   =   $sheetData2 - 1; 

                        $spreadsheet->getActiveSheet()->setTitle('Post -'.$sheetData_post);
 
                        $spreadsheet->setActiveSheetIndex($work_sheet);
                    }
                }
            }
            // Rename worksheet
            //$spreadsheet->getActiveSheet()->setTitle('Candidate Records Pre Post');

           // $spreadsheet->setActiveSheetIndex(0);

            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment;filename=Candidates Records of .xlsx");
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

            $json_array['file_name'] = "Candidates Records of $client_name";

            $json_array['message'] = "File downloaded successfully,please check in download folder";

            $json_array['status'] = SUCCESS_CODE;
        } else {
            $json_array['message'] = "Something went wrong,please try again";

            $json_array['status'] = ERROR_CODE;
        }

        echo_json($json_array);
    }



    public function export_to_excel_insufficiency()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {
            set_time_limit(0);
            ini_set('memory_limit', '-1');

            $client_id = ($this->input->post('clientid') != "All") ? $this->input->post('clientid') : false;

            $entity_id = ($this->input->post('entity') != 0) ? $this->input->post('entity') : false;

            $package_id = ($this->input->post('package') != 0) ? $this->input->post('package') : false;

            $client_name = $this->input->post('client_name');

            $all_records = $this->get_all_client_data_for_export_insufficiency($client_id, $entity_id, $package_id);

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
            $spreadsheet->getActiveSheet()->getStyle('A1:L1')->applyFromArray($styleArray);
            // auto fit column to content
            foreach (range('A', 'L') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                    ->setWidth(20);
            }
            // set the names of header cells

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A1", 'Client Ref No.')
                ->setCellValue("B1", REFNO)
                ->setCellValue("C1", 'Client Name')
                ->setCellValue("D1", 'Entity')
                ->setCellValue("E1", 'Package')
                ->setCellValue("F1", 'Case Initiated')
                ->setCellValue("G1", 'Overall Status')
                ->setCellValue("H1", 'Candidate Name')

                ->setCellValue("I1", 'WIP')
                ->setCellValue("J1", 'Closed')
                ->setCellValue("K1", 'Insufficiency')
                ->setCellValue("L1", 'Details');

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
                    ->setCellValue("I$x", $all_record['WIP'])
                    ->setCellValue("J$x", $all_record['Closed'])
                    ->setCellValue("K$x", $all_record['Insufficiency'])
                    ->setCellValue("L$x", $all_record['Details']);

                $x++;
            }
            // Rename worksheet
            $spreadsheet->getActiveSheet()->setTitle('Candidate Records');

            $spreadsheet->setActiveSheetIndex(0);

            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment;filename=Candidates Records of .xlsx");
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

            $json_array['file_name'] = "Candidates Records of $client_name";

            $json_array['message'] = "File downloaded successfully,please check in download folder";

            $json_array['status'] = SUCCESS_CODE;
        } else {
            $json_array['message'] = "Something went wrong,please try again";

            $json_array['status'] = ERROR_CODE;
        }

        echo_json($json_array);
    }

    public function export_to_excel_direct()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {
            set_time_limit(0);

            $count = $this->input->post('count_record');

            $all_records = $this->candidates_model->get_all_export(array('candidates_info.is_bulk_upload' => '1', 'candidates_info.status' => '1'), $count);

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
            $spreadsheet->getActiveSheet()->getStyle('A1:AA1')->applyFromArray($styleArray);
            // auto fit column to content
            foreach (range('A', 'AA') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                    ->setWidth(20);
            }
            // set the names of header cells

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A1", 'Candidate ID')
                ->setCellValue("B1", 'Client Ref No.')
                ->setCellValue("C1", REFNO)
                ->setCellValue("D1", 'Client Name')
                ->setCellValue("E1", 'Case initiated')
                ->setCellValue("F1", 'Candidate Name')
                ->setCellValue("G1", 'Overall Status')
                ->setCellValue("H1", 'Gender')
                ->setCellValue("I1", 'Date of Birth')
                ->setCellValue("J1", 'Father Name')
                ->setCellValue("K1", 'Mother Name')
                ->setCellValue("L1", 'Primary Contact Number')
                ->setCellValue("M1", 'Email ID')
                ->setCellValue("N1", 'Date of Joining')
                ->setCellValue("O1", 'Location')
                ->setCellValue("P1", 'Designation')
                ->setCellValue("Q1", 'Department')
                ->setCellValue("R1", 'Employee Code')
                ->setCellValue("S1", 'PAN Number')
                ->setCellValue("T1", 'Aadhar Number')
                ->setCellValue("U1", 'Passport Number')
                ->setCellValue("V1", 'Address')
                ->setCellValue("W1", 'Country')
                ->setCellValue("X1", 'State')
                ->setCellValue("Y1", 'City')
                ->setCellValue("Z1", 'Pincode');

            // Add some data
            $x = 2;
            foreach ($all_records as $all_record) {
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A$x", $all_record['id'])
                    ->setCellValue("B$x", $all_record['ClientRefNumber'])
                    ->setCellValue("C$x", $all_record['cmp_ref_no'])
                    ->setCellValue("D$x", $all_record['clientname'])
                    ->setCellValue("E$x", $all_record['caserecddate'])
                    ->setCellValue("F$x", $all_record['CandidateName'])
                    ->setCellValue("G$x", $all_record['status_value'])
                    ->setCellValue("H$x", $all_record['gender'])
                    ->setCellValue("I$x", $all_record['DateofBirth'])
                    ->setCellValue("J$x", $all_record['NameofCandidateFather'])
                    ->setCellValue("K$x", $all_record['MothersName'])
                    ->setCellValue("L$x", $all_record['CandidatesContactNumber'])
                    ->setCellValue("M$x", $all_record['cands_email_id'])
                    ->setCellValue("N$x", $all_record['DateofJoining'])
                    ->setCellValue("O$x", $all_record['Location'])
                    ->setCellValue("P$x", $all_record['DesignationJoinedas'])
                    ->setCellValue("Q$x", $all_record['Department'])
                    ->setCellValue("R$x", $all_record['EmployeeCode'])
                    ->setCellValue("S$x", $all_record['PANNumber'])
                    ->setCellValue("T$x", $all_record['AadharNumber'])
                    ->setCellValue("U$x", $all_record['PassportNumber'])
                    ->setCellValue("V$x", $all_record['prasent_address'])
                    ->setCellValue("W$x", $all_record['cands_country'])
                    ->setCellValue("X$x", $all_record['cands_state'])
                    ->setCellValue("Y$x", $all_record['cands_city'])
                    ->setCellValue("Z$x", $all_record['cands_pincode']);
                $x++;
            }
            // Rename worksheet
            $spreadsheet->getActiveSheet()->setTitle('Candidate Records');

            // set right to left direction
            //    $spreadsheet->getActiveSheet()->setRightToLeft(true);

            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $spreadsheet->setActiveSheetIndex(0);
            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment;filename=Candidates Records of.xlsx");
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

            $json_array['file_name'] = "Candidates Records of";

            $json_array['message'] = "File downloaded successfully,please check in download folder";

            $json_array['status'] = SUCCESS_CODE;
        } else {
            $json_array['message'] = "Something went wrong,please try again";

            $json_array['status'] = ERROR_CODE;
        }

        echo_json($json_array);
    }

    private function echo_json($json_array)
    {
        echo_json($json_array);

        exit;
    }

    public function status_count()
    {
        $json_array = array();

        $result = '';

        if ($this->input->is_ajax_request()) {
            switch ($this->user_info['groupID']) {
                case "2": //all cands status super admin
                    $result = $this->candidates_model->status_count(false);
                    break;
                case "8": //all cands status super admin
                    $result = $this->candidates_model->status_count(false);
                    break;
                case "6": //all cands status admin
                    $result = $this->candidates_model->status_count(false);
                    break;
                case "3": //address
                    $this->load->model('addressver_model');
                    $result = $this->addressver_model->address_ver_status_count();
                    break;
                case "17": //address verification user
                    $this->load->model('addressver_model');
                    $result = $this->addressver_model->address_ver_status_count(array('addrver.has_case_id' => $this->user_info['id']));
                    break;
                case "4": // employment
                    $this->load->model('employment_model');
                    $result = $this->employment_model->employment_ver_status_count();
                    break;
                case "16": // employment verification user
                    $this->load->model('employment_model');
                    $result = $this->employment_model->employment_ver_status_count(array('empver.has_case_id' => $this->user_info['id']));
                    break;
                case "5": // education
                    $this->load->model('education_model');
                    $result = $this->education_model->education_ver_status_count();
                    break;
                case "9": // pcc
                    $this->load->model('pcc_model');
                    $result = $this->pcc_model->pcc_ver_status_count();
                    break;
                case "10":
                    $this->load->model('court_verificatiion_model');
                    $result = $this->court_verificatiion_model->court_ver_status_count();
                    break;
                case "11":
                    $this->load->model('global_database_model');
                    $result = $this->global_database_model->global_db_ver_status_count();
                    break;

                default:
                    echo "No components found";
            }

            $json_array['status'] = SUCCESS_CODE;

            $json_array['message'] = $result;
        } else {
            $json_array['status'] = ERROR_CODE;

            $json_array['message'] = 'Something went wrong';
        }
        echo_json($json_array);
    }

    public function get_last_week_cases()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {
            $lastWeek = array();

            switch ($this->user_info['groupID']) {
                case "2": //all cands status super admin
                    for ($i = 0; $i < 7; $i++) {
                        $date = date("Y-m-d", strtotime(date("Y-m-d") . " - $i day"));
                        array_push($lastWeek, array('day' => date('D', strtotime($date)), 'value' => $this->candidates_model->get_all_cases_by_date($date)));
                    }
                    break;
                case "6": //all cands status admin
                    for ($i = 0; $i < 7; $i++) {
                        $date = date("Y-m-d", strtotime(date("Y-m-d") . " - $i day"));
                        array_push($lastWeek, array('day' => date('D', strtotime($date)), 'value' => $this->candidates_model->get_all_cases_by_date($date)));
                    }
                    break;
                case "8": //all cands status super admin
                    for ($i = 0; $i < 7; $i++) {
                        $date = date("Y-m-d", strtotime(date("Y-m-d") . " - $i day"));
                        array_push($lastWeek, array('day' => date('D', strtotime($date)), 'value' => $this->candidates_model->get_all_cases_by_date($date)));
                    }
                    break;
                case "3": //address
                    $this->load->model('addressver_model');
                    for ($i = 0; $i < 7; $i++) {
                        $date = date("Y-m-d", strtotime(date("Y-m-d") . " - $i day"));
                        array_push($lastWeek, array('day' => date('D', strtotime($date)), 'value' => $this->addressver_model->get_aaddress_cases_by_date($date)));
                    }
                    break;
                case "17": //address
                    $this->load->model('addressver_model');
                    for ($i = 0; $i < 7; $i++) {
                        $date = date("Y-m-d", strtotime(date("Y-m-d") . " - $i day"));
                        array_push($lastWeek, array('day' => date('D', strtotime($date)), 'value' => $this->addressver_model->get_aaddress_cases_by_date($date, $this->user_info['id'])));
                    }
                    break;
                case "4": // employment
                    $this->load->model('employment_model');
                    for ($i = 0; $i < 7; $i++) {
                        $date = date("Y-m-d", strtotime(date("Y-m-d") . " - $i day"));
                        array_push($lastWeek, array('day' => date('D', strtotime($date)), 'value' => $this->employment_model->get_employment_cases_by_date($date)));
                    }
                    break;
                case "16": // employment
                    $this->load->model('employment_model');
                    for ($i = 0; $i < 7; $i++) {
                        $date = date("Y-m-d", strtotime(date("Y-m-d") . " - $i day"));
                        array_push($lastWeek, array('day' => date('D', strtotime($date)), 'value' => $this->employment_model->get_employment_cases_by_date($date, $this->user_info['id'])));
                    }
                    break;
                case "5": // education
                    $this->load->model('education_model');
                    for ($i = 0; $i < 7; $i++) {
                        $date = date("Y-m-d", strtotime(date("Y-m-d") . " - $i day"));
                        array_push($lastWeek, array('day' => date('D', strtotime($date)), 'value' => $this->education_model->get_education_cases_by_date($date)));
                    }
                    break;
                case "9": // pcc
                    $this->load->model('pcc_model');
                    for ($i = 0; $i < 7; $i++) {
                        $date = date("Y-m-d", strtotime(date("Y-m-d") . " - $i day"));
                        array_push($lastWeek, array('day' => date('D', strtotime($date)), 'value' => $this->pcc_model->get_pcc_cases_by_date($date)));
                    }
                    break;
                case "10":
                    $this->load->model('court_verificatiion_model');
                    for ($i = 0; $i < 7; $i++) {
                        $date = date("Y-m-d", strtotime(date("Y-m-d") . " - $i day"));
                        array_push($lastWeek, array('day' => date('D', strtotime($date)), 'value' => $this->court_verificatiion_model->get_court_cases_by_date($date)));
                    }
                    break;
                case "11":
                    $this->load->model('global_database_model');
                    for ($i = 0; $i < 7; $i++) {
                        $date = date("Y-m-d", strtotime(date("Y-m-d") . " - $i day"));
                        array_push($lastWeek, array('day' => date('D', strtotime($date)), 'value' => $this->global_database_model->get_global_db_cases_by_date($date)));
                    }
                    break;
                default:
                    echo "No components found";
            }

            $json_array['status'] = SUCCESS_CODE;

            $json_array['message'] = array_reverse($lastWeek);
        } else {
            $json_array['status'] = ERROR_CODE;

            $json_array['message'] = '0,0,0,0,0,0,0';
        }

        echo_json($json_array);
    }

    public function get_tat_cases()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {
            $tat_info = array();

            switch ($this->user_info['groupID']) {
                case "2": //all cands status super admin
                    $this->load->model('addressver_model');
                    $result = $this->addressver_model->get_address_tat();
                    $tat_info = $this->tat_calculate($result);
                    break;
                case "6": //all cands status admin
                    $this->load->model('addressver_model');
                    $result = $this->addressver_model->get_address_tat();
                    $tat_info = $this->tat_calculate($result);
                    break;
                case "8": //data entry
                    $this->load->model('addressver_model');
                    $result = $this->addressver_model->get_address_tat();
                    $tat_info = $this->tat_calculate($result);
                    break;
                case "3": //address
                    $this->load->model('addressver_model');
                    $result = $this->addressver_model->get_address_tat();
                    $tat_info = $this->tat_calculate($result);
                    break;
                case "17": //address
                    $this->load->model('addressver_model');
                    $result = $this->addressver_model->get_address_tat(array('addrver.has_case_id' => $this->user_info['id']));
                    $tat_info = $this->tat_calculate($result);
                    break;
                case "4": // employment
                    $this->load->model('employment_model');
                    $result = $this->employment_model->get_employment_tat();
                    $tat_info = $this->tat_calculate($result);
                    break;
                case "16": // employment
                    $this->load->model('employment_model');
                    $result = $this->employment_model->get_employment_tat(array('empver.has_case_id' => $this->user_info['id']));
                    $tat_info = $this->tat_calculate($result);
                    break;
                case "5": // education
                    $this->load->model('education_model');
                    $result = $this->education_model->get_education_tat();
                    $tat_info = $this->tat_calculate($result);
                    break;
                case "9": // pcc
                    $this->load->model('pcc_model');
                    $result = $this->pcc_model->get_pcc_tat();
                    $tat_info = $this->tat_calculate($result);
                    break;
                case "10":
                    $this->load->model('court_verificatiion_model');
                    $result = $this->court_verificatiion_model->get_court_tat();
                    $tat_info = $this->tat_calculate($result);
                    break;
                case "11":
                    $this->load->model('global_database_model');
                    $result = $this->global_database_model->get_global_tat();
                    $tat_info = $this->tat_calculate($result);
                    break;
                default:
                    echo "No components found";
            }

            $json_array['status'] = SUCCESS_CODE;

            $json_array['message'] = $tat_info;
        } else {
            $json_array['status'] = ERROR_CODE;

            $json_array['message'] = '0,0,0,0';
        }

        echo_json($json_array);
    }

    protected function tat_calculate($tat_data)
    {
        $within_tat_arry = $totay_tat_arry = $above_tat_arry = $approaching_tat_arry = array();

        foreach ($tat_data as $key => $value) {
            if (strtolower($value['verfstatus']) == "wip" || strtolower($value['verfstatus']) == "") {
                $tat_days = $this->calculate_weekend($value['caserecddate'], $value['tat_days']);
                //$tat_days = $value['tat_days'];

                if ($value['reiniated_date'] == null) {
                    $date_diff = date_diff(date_create(date("Y-m-d")), date_create($value['caserecddate']));
                } else {
                    $date_diff = date_diff(date_create($value['caserecddate']), date_create($value['reiniated_date']));
                }

                $rem = abs($date_diff->days - $tat_days);

                if ($rem == 1) {
                    array_push($totay_tat_arry, $tat_data[$key]);
                } else if ($rem == 2) {
                    array_push($approaching_tat_arry, $tat_data[$key]);
                } else if ($date_diff->days > $tat_days) {
                    array_push($above_tat_arry, $tat_data[$key]);
                } else {
                    array_push($within_tat_arry, $tat_data[$key]);
                }
            }
        }

        return array(array('key' => 'Within TAT', 'value' => count($within_tat_arry)), array('key' => 'Today TAT', 'value' => count($totay_tat_arry)), array('key' => 'Approaching TAT', 'value' => count($approaching_tat_arry)), array('key' => 'Above TAT', 'value' => count($above_tat_arry)));
    }

    private function get_holidays_date($where)
    {
        $this->CI = &get_instance();

        $query = $this->CI->db->query("SELECT count(id) as days  FROM holiday_dates_log WHERE DATE(holiday_date) BETWEEN '" . $where['start'] . "' AND '" . $where['end'] . "' ");

        record_db_error($this->CI->db->last_query());

        $result = $query->result_array();

        if (!empty($result)) {
            return $result[0]['days'];
        }

        return false;
    }

    private function calculate_weekend($initiated_date, $tat_days)
    {
        $tat_date = $end = date('Y-m-d', strtotime($initiated_date . "+ " . $tat_days . " day"));

        $start = new DateTime($initiated_date);

        $end = new DateTime($tat_date);

        $is_weekend = $end->format('D');

        if ($is_weekend == "Sat" || $is_weekend == "Sun") {
            $end = date('Y-m-d', strtotime($tat_date . "+ 1 day"));

            $end = new DateTime($end);
        }

        $interval = new DateInterval('P1D');

        $daterange = new DatePeriod($start, $interval, $end);

        $sat_sun_days = 0;

        $holidays = $this->get_holidays_date(array('start' => $initiated_date, 'end' => $tat_date));

        foreach ($daterange as $date) {
            $days = $date->format('D');

            if ($days == 'Sat' || $days == 'Sun') {
                $sat_sun_days++;
            }
        }

        return $tat_days + $sat_sun_days + $holidays;
    }

    public function delete()
    {

        $candidates_id = $this->input->post('candidates_id');

        if ($this->input->is_ajax_request() && $this->permission['access_candidates_list_delete'] == true) {

            if ($candidates_id) {

                $field_array = array('status' => STATUS_DELETED,
                    'modified_on' => date(DB_DATE_FORMAT),
                    'modified_by' => $this->user_info['id'],
                );

                $where_array = array('id' => $is_exits['id']);

                if ($this->client_new_cases_model->save($field_array, $where_array)) {
                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'Deleted Successfully';
                } else {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Something went wrong,please try again';
                }
            } else {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = 'Something went wrong,please try again';
            }
            echo_json($json_array);
        } else {
            permission_denied();
        }
    }

    public function view_candidate_log($candidates_id)
    {
        $add_candidates = $data_arry = $columns = array();

        $params = $_REQUEST;

        $lists = $this->candidates_model->get_candidate_list(array('candidates_info_logs.candidates_info_id' => $candidates_id));

        $totalRecords = count($this->candidates_model->get_candidate_list(array('candidates_info_logs.candidates_info_id' => $candidates_id)));

        $test = end($lists);

        $x = 0;

        foreach ($lists as $list) {
            $test_id = ($test['id'] != '') ? $test['id'] : '0';
            $is_bulk_upload = ($list['is_bulk_upload'] == '1') ? 'Bulk' : 'Manual';
            $data_arry[$x]['id'] = $x + 1;
            $data_arry[$x]['created_on'] = convert_db_to_display_date($list['created_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);
            $data_arry[$x]['create_by'] = $list['created_name'];
            $data_arry[$x]['is_bulk_upload'] = $is_bulk_upload;

            $data_arry[$x]['encry_id'] = "<button data-id=" . $list['id'] . " data-url = " . ADMIN_SITE_URL . 'candidates/view_details_log/' . $list['id'] . '/' . $test_id . '/' . $list['candidates_info_id'] . " data-toggle='modal' data-target='#showcandidatelog'  class='btn-info  showcandidatelog'> View </button>";

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

    public function entity_package_view($clientid)
    {

        if ($this->input->is_ajax_request()) {
            if (!empty($clientid)) {

                $data['candidate_details'] = $this->candidates_model->get_entity_package_component(array('clients_details.tbl_clients_id' => $clientid));
                echo $this->load->view('admin/view_entity_package_component', $data, true);
            } else {
                echo 'Data Not Found';
            }
        } else {
            echo 'Something went wrong,please try again';
        }

    }

    public function template_downloads($file_name)
    {
        if ($file_name) {
            $file = SITE_BASE_PATH . UPLOAD_FOLDER . "candidate_report_file/" . $file_name;
            if (file_exists($file)) {
                // get file content
                $data = file_get_contents($file);
                //force download
                force_download($file_name, $data);
            } else {
                redirect(base_url());
            }
        }
    }

    public function report_logs($cands_id)
    {
        if ($this->input->is_ajax_request() && $cands_id) {
            $report_details = $this->candidates_model->select_cadidate_report_join(array('activity_log.candsid' => $cands_id));
            
            $counter = 1;
            try {
                $html_view = '<thead><tr><th>Sr No</th><th>Created On</th><th>Component</th><th>Closed On</th><th>Created By</th><th>Status</th></tr></thead>';
                $first_qc_status_check[0] = array();
                foreach ($report_details as $report_detail) {

                    $create_on = '';
                    $details = '';
                    $filter_status = '';
                    $first_qc_status = '';
                    $result_log_id = '';
                    $result_download_id = '';
                    $result_email_id = '';
                    $closed_on = '';
                    $component_type = '';
                    $first_qc_status_check[0] = array();
                    if ($report_detail['component_type'] == 1) {
                        $lists = $this->candidates_model->select_address_join(array('addrver.id' => $report_detail['comp_table_id'], 'addrverres_result.activity_log_id' => $report_detail['id']));

                        $create_on = convert_db_to_display_date($lists[0]['created_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);

                        $component_type = "Address " . $report_detail['comp_table_id'];
                        $filter_status = $report_detail['action'];

                        $closed_on = convert_db_to_display_date($report_detail['created_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);
                     
                

                    } elseif ($report_detail['component_type'] == 2) {
                        $lists = $this->candidates_model->select_employment_join(array('empver.id' => $report_detail['comp_table_id'], 'empverres_logs.activity_log_id' => $report_detail['id']));
                        $create_on = convert_db_to_display_date($lists[0]['created_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);
                        $closed_on = convert_db_to_display_date($report_detail['created_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);

                        $component_type = "Employment " . $report_detail['comp_table_id'];
                        $filter_status = $report_detail['action'];
                   
                    } elseif ($report_detail['component_type'] == 3) {
                        $lists = $this->candidates_model->select_education_join(array('education.id' => $report_detail['comp_table_id'], 'education_ver_result.activity_log_id' => $report_detail['id']));

                        $create_on = convert_db_to_display_date($lists[0]['created_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);
                        $closed_on = convert_db_to_display_date($report_detail['created_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);

                        $component_type = "Education " . $report_detail['comp_table_id'];
                        $filter_status = $report_detail['action'];
                       
                    } elseif ($report_detail['component_type'] == 4) {
                        $lists = $this->candidates_model->select_reference_join(array('reference.id' => $report_detail['comp_table_id'], 'reference_ver_result.activity_log_id' => $report_detail['id']));
                        $create_on = convert_db_to_display_date($lists[0]['created_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);
                        $closed_on = convert_db_to_display_date($report_detail['created_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);

                        $component_type = "Reference " . $report_detail['comp_table_id'];
                        $filter_status = $report_detail['action'];
                       
                    } elseif ($report_detail['component_type'] == 5) {
                        
                        $lists = $this->candidates_model->select_court_join(array('courtver.id' => $report_detail['comp_table_id'], 'courtver_ver_result.activity_log_id' => $report_detail['id']));
                        $create_on = convert_db_to_display_date($lists[0]['created_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);
                        $closed_on = convert_db_to_display_date($report_detail['created_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);

                        $component_type = "Court " . $report_detail['comp_table_id'];
                        $filter_status = $report_detail['action'];
                      
                    } elseif ($report_detail['component_type'] == 6) {

                        $lists = $this->candidates_model->select_global_database_join(array('glodbver.id' => $report_detail['comp_table_id'], 'glodbver_ver_result.activity_log_id' => $report_detail['id']));

                        $create_on = convert_db_to_display_date($lists[0]['created_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);
                        $closed_on = convert_db_to_display_date($report_detail['created_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);

                        $component_type = "Global Database " . $report_detail['comp_table_id'];
                        $filter_status = $report_detail['action'];
                        
                    } elseif ($report_detail['component_type'] == 7) {

                        $lists = $this->candidates_model->select_drugs_join(array('drug_narcotis.id' => $report_detail['comp_table_id'], 'drug_narcotis_ver_result.activity_log_id' => $report_detail['id']));
                        $create_on = convert_db_to_display_date($lists[0]['created_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);
                        $closed_on = convert_db_to_display_date($report_detail['created_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);

                        $component_type = "Drugs " . $report_detail['comp_table_id'];
                        $filter_status = $report_detail['action'];
                       
                    } elseif ($report_detail['component_type'] == 8) {

                        $lists = $this->candidates_model->select_pcc_join(array('pcc.id' => $report_detail['comp_table_id'], 'pcc_ver_result.activity_log_id' => $report_detail['id']));

                        $create_on = convert_db_to_display_date($lists[0]['created_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);
                        $closed_on = convert_db_to_display_date($report_detail['created_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);

                        $component_type = "PCC " . $report_detail['comp_table_id'];
                        $filter_status = $report_detail['action'];
                        
                    } elseif ($report_detail['component_type'] == 9) {
                        $lists = $this->candidates_model->select_identity_join(array('identity.id' => $report_detail['comp_table_id'], 'identity_ver_result.activity_log_id' => $report_detail['id']));

                        $create_on = convert_db_to_display_date($lists[0]['created_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);
                        $closed_on = convert_db_to_display_date($report_detail['created_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);

                        $component_type = "Identity " . $report_detail['comp_table_id'];
                        $filter_status = $report_detail['action'];
                     
                    } elseif ($report_detail['component_type'] == 10) {
                        $lists = $this->candidates_model->select_credit_report_join(array('credit_report.id' => $report_detail['comp_table_id'], 'credit_report_ver_result.activity_log_id' => $report_detail['id']));

                        $create_on = convert_db_to_display_date($lists[0]['created_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);
                        $closed_on = convert_db_to_display_date($report_detail['created_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);

                        $component_type = "Credit Report " . $report_detail['comp_table_id'];
                        $filter_status = $report_detail['action'];
                       
                    } elseif ($report_detail['component_type'] == "Final Report") {
                        $create_on = convert_db_to_display_date($report_detail['created_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);
                        $closed_on = $report_detail['remarks'];
                        $component_type = $report_detail['component_type'];
                        $filter_status = $report_detail['action'];
                       
                    } elseif ($report_detail['component_type'] == "Candidate Import Report") {

                        $create_on = convert_db_to_display_date($report_detail['created_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);
                        $closed_on = $report_detail['remarks'];
                        $component_type = $report_detail['component_type'];
                        $filter_status = $report_detail['action'];
                        

                    } elseif ($report_detail['component_type'] == "Interim Report") {

                        $create_on = convert_db_to_display_date($report_detail['created_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);
                        $closed_on = $report_detail['remarks'];
                        $component_type = $report_detail['component_type'];
                        $filter_status = $report_detail['action'];

                    }

                    $html_view .= '<tr>';
                    $html_view .= "<td>" . $counter . "</td>";
                    $html_view .= "<td>" . $create_on . "</td>";
                    $html_view .= "<td>" . $component_type . "</td>";
                    $html_view .= "<td>" . $closed_on . "</td>";
                    $html_view .= "<td>" . $report_detail['username'] . "</td>";
                    $html_view .= "<td>" . $filter_status . "</td>";
                   
                    $html_view .= '</tr>';

                    $counter++;
                }
            } catch (Exception $e) {
                log_message('error', 'Error on Candidate::report_logs');
                log_message('error', $e->getMessage());
            }
            $lists1[] = $this->candidates_model->select_address_wip_join(array('addrver.candsid' => $cands_id));
            $lists1[] = $this->candidates_model->select_employment_wip_join(array('empver.candsid' => $cands_id));
            $lists1[] = $this->candidates_model->select_education_wip_join(array('education.candsid' => $cands_id));
            $lists1[] = $this->candidates_model->select_reference_wip_join(array('reference.candsid' => $cands_id));
            $lists1[] = $this->candidates_model->select_court_wip_join(array('courtver.candsid' => $cands_id));
            $lists1[] = $this->candidates_model->select_global_database_wip_join(array('glodbver.candsid' => $cands_id));
            $lists1[] = $this->candidates_model->select_identity_wip_join(array('identity.candsid' => $cands_id));
            $lists1[] = $this->candidates_model->select_drugs_wip_join(array('drug_narcotis.candsid' => $cands_id));
            $lists1[] = $this->candidates_model->select_credit_report_wip_join(array('credit_report.candsid' => $cands_id));
            $lists1[] = $this->candidates_model->select_pcc_wip_join(array('pcc.candsid' => $cands_id));
            try {
                foreach ($lists1 as $lists2) {
                    foreach ($lists2 as $lists3) {
                        if (isset($lists3['add_com_ref'])) {
                            $component_type1 = "Address";
                        }
                        if (isset($lists3['emp_com_ref'])) {
                            $component_type1 = "Employment";
                        }
                        if (isset($lists3['education_com_ref'])) {
                            $component_type1 = "Education";
                        }
                        if (isset($lists3['court_com_ref'])) {
                            $component_type1 = "Court";
                        }
                        if (isset($lists3['reference_com_ref'])) {
                            $component_type1 = "Reference";
                        }

                        if (isset($lists3['global_com_ref'])) {
                            $component_type1 = "Global Database";
                        }
                        if (isset($lists3['drug_com_ref'])) {
                            $component_type1 = "Drugs";
                        }
                        if (isset($lists3['pcc_com_ref'])) {
                            $component_type1 = "PCC";
                        }
                        if (isset($lists3['identity_com_ref'])) {
                            $component_type1 = "Identity";
                        }
                        if (isset($lists3['credit_report_com_ref'])) {
                            $component_type1 = "Credit Report";
                        }

                        $html_view .= '<tr>';
                        $html_view .= "<td>" . $counter . "</td>";
                        $html_view .= "<td>" . convert_db_to_display_date($lists3['created_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12) . "</td>";
                        $html_view .= "<td>" . $component_type1 . "</td>";
                        $html_view .= "<td></td>";
                        $html_view .= "<td></td>";
                        $html_view .= "<td>" . $lists3['status_value'] . "</td>";
                    
                        $html_view .= '</tr>';
                        $counter++;
                    }
                }
            } catch (Exception $e) {
                log_message('error', 'inside foreach Error on Candidate::report_logs');
                log_message('error', $e->getMessage());
            }

            if ($first_qc_status_check[0] == "first qc pending" || $first_qc_status_check[0] == "First QC Pending") {
                $component_type_test = 1;
            } else {
                $component_type_test = 2;
            }

            $json_array['message'] = $html_view;

            $json_array['check_status'] = $component_type_test;

            $json_array['status'] = SUCCESS_CODE;

        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['check_status'] = $component_type_test;
            $json_array['message'] = '<tr><td>Something went wrong, please try again!<td></tr>';
        }
        echo_json($json_array);
    }

    public function send_report_mail($id, $report_type)
    {
        if ($this->input->is_ajax_request()) {
            $details = $this->candidates_model->select_candidate(array('candidates_info.id' => $id));

            $this->load->model('employment_model');

            $reportingmanager_user = $this->employment_model->get_reporting_manager_id();

            $view_data['user_profile_info'] = $reportingmanager_user[0];

            if ($report_type == 'final_report') {
                $view_data['url'] = '<a target="__blank" href="' . ADMIN_SITE_URL . 'candidates/report/' . encrypt($id) . '/final_report' . '" style="float: right;">Report Link</a>';
            }

            if ($report_type == 'Interiam_report') {
                $view_data['url'] = '<a target="__blank" href="' . ADMIN_SITE_URL . 'candidates/report/' . encrypt($id) . '/final_report' . '" style="float: right;">Report Link</a>';
            }

            $view_data['email_info'] = $details;

            if (!empty($details)) {
                echo $this->load->view(EMAIL_VIEW_FOLDER_NAME . 'candidates_report', $view_data, true);
            }
        }
    }

    public function report_send_mail()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {

            if ($this->input->post('to_email') != 'client_services@demo.com') {

                $candidate_id = $this->input->post('candidate_user_id');

                $report_type = $this->input->post('report_type');

                $to_emails = $this->input->post('to_email');

                $from_email = $this->input->post('from');

                $cc_email = $this->input->post('cc_email');

                $subject = $this->input->post('subject');

                $activity_log_id = $this->input->post('activity_log_id');

                $this->load->library('email');

                $details = $this->candidates_model->select_candidate(array('candidates_info.id' => $candidate_id));

                $this->load->model('employment_model');
                $reportingmanager_user = $this->employment_model->get_reporting_manager_id();

                $email_tmpl_data['user_profile_info'] = $reportingmanager_user[0];

                $email_tmpl_data['to_emails'] = $to_emails;
                $email_tmpl_data['cc_emails'] = $cc_email;
                // $email_tmpl_data['bcc_emails']  =  $bcc_email;
                $email_tmpl_data['subject'] = $subject;
                //$email_tmpl_data['attchment']  =  $attchment;
                if ($report_type == "final_report") {
                    $email_tmpl_data['url'] = '<a target="__blank" href="' . ADMIN_SITE_URL . 'candidates/report/' . encrypt($candidate_id) . '/final_report' . '" style="float: right;">Report Link</a>';
                }

                if ($report_type == "Interiam_report") {
                    $email_tmpl_data['url'] = '<a target="__blank" href="' . ADMIN_SITE_URL . 'candidates/report/' . encrypt($candidate_id) . '/final_report' . '" style="float: right;">Report Link</a>';
                }

                // $email_tmpl_data['from_email']  = ($from_email != "") ? $from_email : 'employment.bgv@demo.com';
                $email_tmpl_data['from_email'] = ($from_email != "") ? $from_email : 'bgv@demo.com';

                $email_tmpl_data['detail_info'] = $details;

                $result = $this->email->candidate_report_mail_send($email_tmpl_data);

                if ($result) {

                    $get_activity_log = $this->candidates_model->select_activity_log(array('id' => $activity_log_id));

                    $get_activity_log_remark = ($get_activity_log[0]['remarks'] == '') ? '' : $get_activity_log[0]['remarks'] . '||';
                    $actity_log_date = $get_activity_log_remark . date(DB_DATE_FORMAT);

                    $update_activity_log = $this->candidates_model->update_activity_log(array('remarks' => $actity_log_date), array('id' => $activity_log_id));

                    $update_activity_log_candidate = $this->candidates_model->save(array('final_qc_send_mail' => 1), array('id' => $candidate_id));

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
    }

    public function save_report()
    {
        $frm_details = $this->input->post();

        $error_msgs = array();
        $file_upload_path = SITE_BASE_PATH . UPLOAD_FOLDER . "candidate_report_file";

        if (!folder_exist($file_upload_path)) {
            mkdir($file_upload_path, 0777);
        } else if (!is_writable($file_upload_path)) {
            array_push($error_msgs, 'Problem while uploading');
        }

        $config_array = array('file_upload_path' => $file_upload_path, 'file_permission' => 'pdf', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 1000, 'candsid' => $frm_details['candidate_id'], 'created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id'], 'component_type' => "Candidate Import Report", 'ClientRefNumber' => $frm_details['ClientRefNumber']);
        if (empty($error_msgs)) {
            if ($_FILES['cands_report_file']['name'][0] != '') {
                $config_array['file_data'] = $_FILES['cands_report_file'];
                $config_array['type'] = 0;

                $retunr_de = $this->file_upload_report($config_array);
                if (!empty($retunr_de)) {
                    $result_activity_log = $this->common_model->common_insert_batch('activity_log', $retunr_de['success']);
                }
            }
        }

        if (!empty($result_activity_log)) {
            $update_activity_log_candidate = $this->candidates_model->save(array('final_qc_send_mail' => 0), array('id' => $frm_details['candidate_id']));
            $json_array['status'] = SUCCESS_CODE;
            $json_array['message'] = 'Candidate Updated Successfully';
        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['message'] = 'Something went wrong, please try again';
        }

        echo_json($json_array);
    }

    protected function file_upload_report($config_array)
    {
        $file_array = $error_msgs = array();
        try {
            if ($config_array['file_data']['name']) {
                $file_name = $config_array['file_data']['name'];

                $file_info = pathinfo($file_name);

                $new_file_name = preg_replace('/[[:space:]]+/', '_', $file_info['filename']);

                $new_file_name = $new_file_name . '_' . DATE(UPLOAD_FILE_DATE_FORMAT_FILE_NAME);

                $new_file_name = str_replace('.', '_', $new_file_name);

                $new_file_name = preg_replace('/[^A-Za-z0-9\-]/', '_', $new_file_name);

                $file_extension = $file_info['extension'];

                $new_file_name = $new_file_name . '.' . $file_extension;

                $_FILES['attchment']['name'] = $new_file_name;

                $_FILES['attchment']['tmp_name'] = $config_array['file_data']['tmp_name'];

                $_FILES['attchment']['error'] = $config_array['file_data']['error'];

                $_FILES['attchment']['size'] = $config_array['file_data']['size'];

                $config['upload_path'] = $config_array['file_upload_path'];

                $config['file_name'] = $new_file_name;

                $config['allowed_types'] = $config_array['file_permission'];

                $config['file_ext_tolower'] = true;

                $config['remove_spaces'] = true;

                $config['max_size'] = $config_array['file_size'];

                $this->load->library('upload', $config);

                $this->upload->initialize($config);
                if ($this->upload->do_upload('attchment')) {

                    array_push($file_array, array(

                        'candsid' => $config_array['candsid'],
                        'created_on' => date(DB_DATE_FORMAT),
                        'created_by' => $this->user_info['id'],
                        'component_type' => $config_array['component_type'],
                        'ClientRefNumber' => $config_array['ClientRefNumber'],
                        'remarks' => $new_file_name,
                    ));

                } else {
                    array_push($error_msgs, $this->upload->display_errors('', ''));
                }
            }
        } catch (Exception $e) {
            log_message('error', 'Error on Candidate::file_upload_report');
            log_message('error', $e->getMessage());
        }
        return array('success' => $file_array, 'fail' => $error_msgs);
    }

    public function bulk_update_candidate()
    {

        if ($this->input->is_ajax_request()) {

            $frm_details = $this->input->post();

          
                $file_upload_path = SITE_BASE_PATH . CANDIDATES;

                if (!folder_exist($file_upload_path)) {
                    mkdir($file_upload_path, 0777);
                } else if (!is_writable($file_upload_path)) {
                    $message = 'Problem while uploading, folder permission';
                }

                $uplaod_details = array('file_upload_path' => $file_upload_path, 'file_data' => $_FILES['update_candidate_bulk_sheet_attachment'], 'file_permission' => 'csv|xls|xlsx', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 100);
                $upload_result = $this->file_uplod($uplaod_details);
                $record = array();

                if (!empty($upload_result) && $upload_result['status'] == true) {

                    $raw_filename = $_FILES['update_candidate_bulk_sheet_attachment']['tmp_name'];

                    $headerLine = 0;
                    $file = fopen($raw_filename, "r");

                    $this->load->library('excel_reader', array('file_name' => $file_upload_path . '/' . $upload_result['file_name']));

                    $excel_handler = $this->excel_reader->file_handler;

                    $excel_data = $excel_handler->rows();

                    if (!empty($excel_data)) {

                        unset($excel_data[0]);

                        $excel_data = array_map("unserialize", array_unique(array_map("serialize", $excel_data)));

                        foreach ($excel_data as $value) {

                            if (count($value) < 1) {
                                continue;
                            }

                            $check_record_exits = $this->candidates_model->select(true, array('*'), array('id' => strtolower($value[0])));

                            if (!empty($check_record_exits) && $value[0] != "") {

                                //$result = $this->candidates_model->save(array('caserecddate' => date('Y-m-d', strtotime(str_replace('/', '-', $value[1])))),array('id'=>$value[0]));

                                $result = $this->candidates_model->save(array('ClientRefNumber' => $value[1]),array('id'=>$value[0]));

                                $data['success'] = $value[0] . " This Candidate Records Update Successfully";
                                   
                            }
                        }
                    } else {
                        $data['message'] = 'CSV File is empty or data large then 1000 charecters';
                    }

                    $json_array['message'] = json_encode($data);

                    $json_array['status'] = SUCCESS_CODE;

                } else {
                    $json_array['message'] = $upload_result['message'];
                }

        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['message'] = 'Something went wrong, please try again';
        }
        echo_json($json_array);
    }

    public function assign_to_cases()
    { 
        $this->load->library('email');
        $json_array = array();

            $frm_details = $this->input->post();
          
            $mist_id  =  trim($frm_details['mist_id']);
           
            $input_value = explode('MIST', $mist_id);
         
            $list = array();
            foreach ($input_value as $key => $value) {
              $list[] = "MIST".$value;
            } 
           
            array_shift($list);
           
            if(!empty($list))
            {
               
                $files = $update = $activity =  $candidate_ref_no = array();
                $insert_counter = 0;
                foreach ($list as $key => $value) {
                    $value = trim($value);  
                
                    $candidate_details =  $this->candidates_model->get_component_details(array('cmp_ref_no' => $value)); 
                    
                    $component_id = array(); 
                    if(!empty($candidate_details[0]))
                    {
                      $component_id =  explode(',',$candidate_details[0]['component_id']);  
                    }
                   
                    if(in_array('addrver', $component_id))
                    {
                        
                        $address_details =  $this->candidates_model->select_address_wip_join(array('addrver.candsid' =>  $candidate_details[0]['id']));
                    
                        if(!empty( $address_details))
                        {
                            foreach ($address_details as $key => $address_detail) {
            
                             
                                $check_insuff =  $this->candidates_model->get_address_insuff_details(array('status'=> 1,'addrverid' =>$address_detail['id']));
                            
                                if(!empty($check_insuff[0]))
                                { 
                                    $hold_days = getNetWorkDays($check_insuff[0]['insuff_raised_date'],date("Y-m-d"));
                                    $this->load->model('addressver_model');
                                    $date_tat = $this->addressver_model->get_date_for_update(array('id' => $address_detail['id']));
                                    
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
                                 
                                    $result_due_date = $this->addressver_model->update_due_date(array('due_date' => $closed_date, 'tat_status' => $new_tat), array('id' => $address_detail['id']));
                
                                    $fields = array('insuff_clear_date' => date('Y-m-d'), 'insuff_remarks' => "Stop check", 'insuff_cleared_timestamp' => date(DB_DATE_FORMAT), 'status' => 2, 'insuff_cleared_by' => $this->user_info['id'], 'auto_stamp' => 2, 'hold_days' => $hold_days);
                
                                    $result_insuff = $this->addressver_model->save_update_insuff($fields, array('id' => $check_insuff[0]['id']));
                   
                                    
                                }

                                    $this->load->model('activity_data_model');
                                    $fields = array('candsid' =>  $candidate_details[0]['id'],
                                        'ClientRefNumber' =>  $candidate_details[0]['ClientRefNumber'],
                                        'comp_table_id' => $address_detail['id'],
                                        'activity_status' => 'Stop Check',
                                        'activity_mode' => 'Stop Check',
                                        'activity_type' => 'Stop Check',
                                        'action' => 'Stop Check',
                                        'remarks' => 'Communication received from client requesting to Stop Check.',
                                        'created_by' => $this->user_info['id'],
                                        'created_on' => date(DB_DATE_FORMAT),
                                        'component_type' => 1
                                    );
                                    $result1 = $this->activity_data_model->activity_log_save($fields);
    
                        
                                    $this->load->model('activity_data_model');
                                    $field = array('candsid' => $candidate_details[0]['id'],
                                        'comp_table_id' => $address_detail['id'],
                                        'ClientRefNumber' =>  $candidate_details[0]['ClientRefNumber'],
                                        'activity_status' => 'Stop Check',
                                        'activity_mode' => 'Stop Check',
                                        'activity_type' => 'Stop Check',
                                        'action' => 'Stop Check',
                                        'remarks' =>  'Communication received from client requesting to Stop Check.',
                                        'created_on' => date(DB_DATE_FORMAT),
                                        'created_by' => $this->user_info['id'],
                                        'is_auto_filled' => 1,
        
                                    );

                                    $field_user_activity = array('CandidateName' => $candidate_details[0]['CandidateName'],
                                        'component_ref_no' => $candidate_details[0]['cmp_ref_no']);
        
                                    $result = $this->activity_data_model->save_trigger_save($field, array('component_type' => 1), $field_user_activity);
        

                                    $field_array = array(
                                        'verfstatus' => 9,
                                        'var_filter_status' => "Closed",
                                        'var_report_status' => "Stop Check",
                                        'closuredate' => date('Y-m-d'),
                                        'remarks' => 'Communication received from client requesting to Stop Check.',
                                        'modified_on' => date(DB_DATE_FORMAT),
                                        'modified_by' => $this->user_info['id'],
                                        'activity_log_id' => $result1
                                    );
                                    $this->load->model('addressver_model'); 
                                    $result = $this->addressver_model->save_update_result(array_map('strtolower', $field_array), array('addrverid' =>  $address_detail['id']));
                                    
                                    $field_result = array(
                                        'verfstatus' => 9,
                                        'candsid' => $candidate_details[0]['id'],
                                        'clientid' => $candidate_details[0]['clientid'],  
                                        'var_filter_status' => "Closed",
                                        'var_report_status' => "Stop Check",
                                        'closuredate' => date('Y-m-d'),
                                        'addrverid' =>  $address_detail['id'],
                                        'remarks' => 'Communication received from client requesting to Stop Check.',
                                        'created_on' => date(DB_DATE_FORMAT),
                                        'created_by' => $this->user_info['id'],
                                        'activity_log_id' => $result1
                                    );
                       
                                    $result = $this->addressver_model->save_update_result_addrverres(array_map('strtolower', $field_result));
                                
                                    
                                    $get_view_vendor_master_details = $this->addressver_model->get_client_id(array('view_vendor_master_log.final_status' => 'wip','view_vendor_master_log.component'=>'addrver','component_tbl_id' => 1,'addrver.id' => $address_detail['id']));
                                      
                                    if(!empty($get_view_vendor_master_details[0]))
                                    {  
                                        $address_details_vendor[] = $this->addressver_model->get_address_details_for_approval($get_view_vendor_master_details[0]['address_vendor_log_id']);

                                           
                                        $field_array = array(
                                            'remarks' => "Stop Check",
                                            'rejected_by' => $this->user_info['id'],
                                            'rejected_on' => date(DB_DATE_FORMAT),
                                            'status' => 5,
                                            'final_status' => "cancelled",
                                        );
                        
                                        $result_view_vendor_log = $this->addressver_model->save_vendor_details_cancel($field_array, array('id' => $get_view_vendor_master_details[0]['id']));
                        
                                        $field_array_address = array(
                                            'remarks' => "Stop Check",
                                            'modified_by' => $this->user_info['id'],
                                            'modified_on' => date(DB_DATE_FORMAT),
                                            'status' => 2,
                                        );
                        
                                        $address_vendor_result = $this->addressver_model->update_address_vendor_log('address_vendor_log', $field_array_address, array('id' =>  $get_view_vendor_master_details[0]['vendor_master_log_case']));
                           
                                    }
                                    
                            }

                            if(isset($address_details_vendor) && !empty($address_details_vendor))
                            {
                             
                                $address_detail_vendor =  (array_map('current', $address_details_vendor));
                                   
                                foreach($address_detail_vendor as $k_address => $v_address) {     
                                    $new_arr_address[$v_address['vendor_id']][]=$v_address;
                                }
                               
                                foreach ($new_arr_address as $key_address => $value_address) {
                                       
                                    $email_tmpl_data['subject'] = 'Address - Stop check cases_'.date("d-M-Y H:i");
                                    $message = "<p>Team,</p><p> The below case/s have been cancelled by the client.</p>";
                                            
                                    $message .= "<table border = '2'  style='border-spacing: 10; border-collapse: collapse;'>
                                        <tr>
                                        <th style='background-color: #EDEDED;text-align:center'>Client Name</th>
                                        <th style='background-color: #EDEDED;text-align:center'>Component Ref No</th>
                                        <th style='background-color: #EDEDED;text-align:center'>Candidate Name</th>
                                        <th style='background-color: #EDEDED;text-align:center'>Primary Contact</th>
                                        <th style='background-color: #EDEDED;text-align:center'>Contact No (2)</th>
                                        <th style='background-color: #EDEDED;text-align:center'>Contact No (3)</th>
                                        <th style='background-color: #EDEDED;text-align:center'>Address</th>
                                        <th style='background-color: #EDEDED;text-align:center'>City</th>
                                        <th style='background-color: #EDEDED;text-align:center'>Pincode</th>
                                        <th style='background-color: #EDEDED;text-align:center'>State</th>
                                        </tr>";
    
                                    foreach ($value_address as $address_key => $address_value) {
                                        
                                        $message .= '<tr>
                                            <td style="text-align:center">'.ucwords($address_value['clientname']). '</td>
                                            <td style="text-align:center">'.$address_value['add_com_ref'] . '</td>
                                            <td style="text-align:center">'.ucwords($address_value['CandidateName']) . '</td>
                                            <td style="text-align:center">'.$address_value['CandidatesContactNumber'] . '</td>
                                            <td style="text-align:center">'.$address_value['ContactNo1'] . '</td>
                                            <td style="text-align:center">'.$address_value['ContactNo2'] . '</td>
                                            <td style="text-align:center">'.ucwords($address_value['address']) . '</td>
                                            <td style="text-align:center">'.ucwords($address_value['city']) . '</td>
                                            <td style="text-align:center">'.$address_value['pincode'] . '</td>
                                            <td style="text-align:center">'.ucwords($address_value['state']) . '</td>
                                            </tr>';
                                               
                                        } 
                                        $message .= "</table>";
    
                                        $message .= "<br><br><p><b>Note :</b> <I>This is an auto generated email. Request you to write back within 24 hrs with any discrepancy.</I>";
                                            
                                        $to_emails = $this->addressver_model->vendor_email_id(array('vendors.id' => $key_address));
                 
                                        $email_tmpl_data['to_emails'] = $to_emails[0]['email_id'];
    
                                        $email_tmpl_data['message'] = $message;
                      
                                        $result = $this->email->address_approve_vendor_cases_mail_send($email_tmpl_data);
    
                                        $this->email->clear(true);

                                        unset($address_details_vendor);
                                        unset($new_arr_address);
                                                                      
                                }
                            }
                            
                        }     
                      
                    }

                     
                    if(in_array('empver', $component_id))
                    {
                        
                        $employment_details = $this->candidates_model->select_employment_wip_join(array('empver.candsid' =>  $candidate_details[0]['id']));
                    
                        if(!empty( $employment_details))
                        {
                            foreach ($employment_details as $key => $employment_detail) {
            
                             
                                $check_insuff =  $this->candidates_model->get_employment_insuff_details(array('status'=> 1,'empverres_id' =>$employment_detail['id']));
                            
                                if(!empty($check_insuff[0]))
                                { 
                                    $hold_days = getNetWorkDays($check_insuff[0]['insuff_raised_date'],date("Y-m-d"));
                                    $this->load->model('employment_model');
                                    $date_tat = $this->employment_model->get_date_for_update(array('id' => $employment_detail['id']));
                                    
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
                                 
                                    $result_due_date = $this->employment_model->update_due_date(array('due_date' => $closed_date, 'tat_status' => $new_tat), array('id' => $employment_detail['id']));
                
                                    $fields = array('insuff_clear_date' => date('Y-m-d'), 'insuff_remarks' => "Stop check", 'insuff_cleared_timestamp' => date(DB_DATE_FORMAT), 'status' => 2, 'insuff_cleared_by' => $this->user_info['id'], 'auto_stamp' => 2, 'hold_days' => $hold_days);
                
                                    $result_insuff = $this->employment_model->save_update_insuff($fields, array('id' => $check_insuff[0]['id']));
                   
                                    
                                }

                                    $this->load->model('activity_data_model');
                                    $fields = array('candsid' =>  $candidate_details[0]['id'],
                                        'ClientRefNumber' =>  $candidate_details[0]['ClientRefNumber'],
                                        'comp_table_id' => $employment_detail['id'],
                                        'activity_status' => 'Stop Check',
                                        'activity_mode' => 'Stop Check',
                                        'activity_type' => 'Stop Check',
                                        'action' => 'Stop Check',
                                        'remarks' => 'Communication received from client requesting to Stop Check.',
                                        'created_by' => $this->user_info['id'],
                                        'created_on' => date(DB_DATE_FORMAT),
                                        'component_type' => 2
                                    );
                                    $result1 = $this->activity_data_model->activity_log_save($fields);
    
                        
                                    $this->load->model('activity_data_model');
                                    $field = array('candsid' => $candidate_details[0]['id'],
                                        'comp_table_id' => $employment_detail['id'],
                                        'ClientRefNumber' =>  $candidate_details[0]['ClientRefNumber'],
                                        'activity_status' => 'Stop Check',
                                        'activity_mode' => 'Stop Check',
                                        'activity_type' => 'Stop Check',
                                        'action' => 'Stop Check',
                                        'remarks' =>  'Communication received from client requesting to Stop Check.',
                                        'created_on' => date(DB_DATE_FORMAT),
                                        'created_by' => $this->user_info['id'],
                                        'is_auto_filled' => 1,
        
                                    );

                                    $field_user_activity = array('CandidateName' => $candidate_details[0]['CandidateName'],
                                        'component_ref_no' => $candidate_details[0]['cmp_ref_no']);
        
                                    $result = $this->activity_data_model->save_trigger_save($field, array('component_type' => 2), $field_user_activity);
        

                                    $field_array = array(
                                        'verfstatus' => 9,
                                        'var_filter_status' => "Closed",
                                        'var_report_status' => "Stop Check",
                                        'closuredate' => date('Y-m-d'),
                                        'remarks' => 'Communication received from client requesting to Stop Check.',
                                        'modified_on' => date(DB_DATE_FORMAT),
                                        'modified_by' => $this->user_info['id'],
                                        'activity_log_id' => $result1
                                    );
                                    $this->load->model('employment_model'); 
                                    $result = $this->employment_model->save_update_empt_ver_result(array_map('strtolower', $field_array), array('empverid' =>  $employment_detail['id']));
                                    
                                    $field_result = array(
                                        'verfstatus' => 9,
                                        'candsid' => $candidate_details[0]['id'],
                                        'clientid' => $candidate_details[0]['clientid'],  
                                        'var_filter_status' => "Closed",
                                        'var_report_status' => "Stop Check",
                                        'closuredate' => date('Y-m-d'),
                                        'empverid' =>  $employment_detail['id'],
                                        'remarks' => 'Communication received from client requesting to Stop Check.',
                                        'created_on' => date(DB_DATE_FORMAT),
                                        'created_by' => $this->user_info['id'],
                                        'activity_log_id' => $result1
                                    );
                       
                                    $result = $this->employment_model->save_update_empt_ver_result_employment(array_map('strtolower', $field_result));
                                
                                    
                                    $get_view_vendor_master_details = $this->employment_model->get_client_id(array('view_vendor_master_log.final_status' => 'wip','view_vendor_master_log.component'=>'empver','component_tbl_id' => 2,'empver.id' => $employment_detail['id']));
                                      
                                    if(!empty($get_view_vendor_master_details[0]))
                                    {
                                        $field_array = array(
                                            'remarks' => "Stop Check",
                                            'rejected_by' => $this->user_info['id'],
                                            'rejected_on' => date(DB_DATE_FORMAT),
                                            'status' => 5,
                                            'final_status' => "cancelled",
                                        );
                        
                                        $result_view_vendor_log = $this->employment_model->save_vendor_details_cancel($field_array, array('id' => $get_view_vendor_master_details[0]['id']));
                        
                                        $field_array_employment = array(
                                            'remarks' => "Stop Check",
                                            'modified_by' => $this->user_info['id'],
                                            'modified_on' => date(DB_DATE_FORMAT),
                                            'status' => 2,
                                        );
                        
                                        $employment_vendor_result = $this->employment_model->update_address_vendor_log('employment_vendor_log', $field_array_employment, array('id' =>  $get_view_vendor_master_details[0]['vendor_master_log_case']));
                           
                                    }
                                    
                            }
                            
                        }     
                      
                    }


                    if(in_array('refver', $component_id))
                    {
                        
                        $reference_details = $this->candidates_model->select_reference_wip_join(array('reference.candsid' =>  $candidate_details[0]['id']));
                    
                        if(!empty( $reference_details))
                        {
                            foreach ($reference_details as $key => $reference_detail) {
            
                             
                                $check_insuff =  $this->candidates_model->get_reference_insuff_details(array('reference_insuff.status'=> 1,'reference_id' =>$reference_detail['id']));
                            
                                if(!empty($check_insuff[0]))
                                { 
                                    $hold_days = getNetWorkDays($check_insuff[0]['insuff_raised_date'],date("Y-m-d"));
                                    $this->load->model('reference_verificatiion_model');
                                    $date_tat = $this->reference_verificatiion_model->get_date_for_update(array('id' => $reference_detail['id']));
                                    
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
                                 
                                    $result_due_date = $this->reference_verificatiion_model->update_due_date(array('due_date' => $closed_date, 'tat_status' => $new_tat), array('id' => $reference_detail['id']));
                
                                    $fields = array('insuff_clear_date' => date('Y-m-d'), 'insuff_remarks' => "Stop check", 'insuff_cleared_timestamp' => date(DB_DATE_FORMAT), 'status' => 2, 'insuff_cleared_by' => $this->user_info['id'], 'auto_stamp' => 2, 'hold_days' => $hold_days);
                
                                    $result_insuff = $this->reference_verificatiion_model->save_update_insuff($fields, array('id' => $check_insuff[0]['id']));
                   
                                    
                                }

                                    $this->load->model('activity_data_model');
                                    $fields = array('candsid' =>  $candidate_details[0]['id'],
                                        'ClientRefNumber' =>  $candidate_details[0]['ClientRefNumber'],
                                        'comp_table_id' => $reference_detail['id'],
                                        'activity_status' => 'Stop Check',
                                        'activity_mode' => 'Stop Check',
                                        'activity_type' => 'Stop Check',
                                        'action' => 'Stop Check',
                                        'remarks' => 'Communication received from client requesting to Stop Check.',
                                        'created_by' => $this->user_info['id'],
                                        'created_on' => date(DB_DATE_FORMAT),
                                        'component_type' => 4
                                    );
                                    $result1 = $this->activity_data_model->activity_log_save($fields);
    
                        
                                    $this->load->model('activity_data_model');
                                    $field = array('candsid' => $candidate_details[0]['id'],
                                        'comp_table_id' => $reference_detail['id'],
                                        'ClientRefNumber' =>  $candidate_details[0]['ClientRefNumber'],
                                        'activity_status' => 'Stop Check',
                                        'activity_mode' => 'Stop Check',
                                        'activity_type' => 'Stop Check',
                                        'action' => 'Stop Check',
                                        'remarks' =>  'Communication received from client requesting to Stop Check.',
                                        'created_on' => date(DB_DATE_FORMAT),
                                        'created_by' => $this->user_info['id'],
                                        'is_auto_filled' => 1,
        
                                    );

                                    $field_user_activity = array('CandidateName' => $candidate_details[0]['CandidateName'],
                                        'component_ref_no' => $candidate_details[0]['cmp_ref_no']);
        
                                    $result = $this->activity_data_model->save_trigger_save($field, array('component_type' => 4), $field_user_activity);
        

                                    $field_array = array(
                                        'verfstatus' => 9,
                                        'var_filter_status' => "Closed",
                                        'var_report_status' => "Stop Check",
                                        'closuredate' => date('Y-m-d'),
                                        'remarks' => 'Communication received from client requesting to Stop Check.',
                                        'modified_on' => date(DB_DATE_FORMAT),
                                        'modified_by' => $this->user_info['id'],
                                        'activity_log_id' => $result1
                                    );
                                    $this->load->model('reference_verificatiion_model'); 
                                    $result = $this->reference_verificatiion_model->reference_verfstatus_update(array_map('strtolower', $field_array), array('reference_id' =>  $reference_detail['id']));
                                    
                                    $field_result = array(
                                        'verfstatus' => 9,
                                        'candsid' => $candidate_details[0]['id'],
                                        'clientid' => $candidate_details[0]['clientid'],  
                                        'var_filter_status' => "Closed",
                                        'var_report_status' => "Stop Check",
                                        'closuredate' => date('Y-m-d'),
                                        'reference_id' =>  $reference_detail['id'],
                                        'remarks' => 'Communication received from client requesting to Stop Check.',
                                        'created_on' => date(DB_DATE_FORMAT),
                                        'created_by' => $this->user_info['id'],
                                        'activity_log_id' => $result1
                                    );
                       
                                    $result = $this->reference_verificatiion_model->save_update_ver_result_refrence(array_map('strtolower', $field_result));
        
                                    
                            }
                            
                        }     
                      
                    }

                    
                    if(in_array('courtver', $component_id))
                    {
                        
                        $court_details = $this->candidates_model->select_court_wip_join(array('courtver.candsid' =>  $candidate_details[0]['id']));
                    
                        if(!empty( $court_details))
                        {
                            foreach ($court_details as $key => $court_detail) {
            
                             
                                $check_insuff =  $this->candidates_model->get_court_insuff_details(array('courtver_insuff.status'=> 1,'courtver_id' =>$court_detail['id']));
                            
                                if(!empty($check_insuff[0]))
                                { 
                                    $hold_days = getNetWorkDays($check_insuff[0]['insuff_raised_date'],date("Y-m-d"));
                                    $this->load->model('court_verificatiion_model');
                                    $date_tat = $this->court_verificatiion_model->get_date_for_update(array('id' => $court_detail['id']));
                                    
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
                                 
                                    $result_due_date = $this->court_verificatiion_model->update_due_date(array('due_date' => $closed_date, 'tat_status' => $new_tat), array('id' => $court_detail['id']));
                
                                    $fields = array('insuff_clear_date' => date('Y-m-d'), 'insuff_remarks' => "Stop check", 'insuff_cleared_timestamp' => date(DB_DATE_FORMAT), 'status' => 2, 'insuff_cleared_by' => $this->user_info['id'], 'auto_stamp' => 2, 'hold_days' => $hold_days);
                
                                    $result_insuff = $this->court_verificatiion_model->save_update_insuff($fields, array('id' => $check_insuff[0]['id']));
                   
                                    
                                }

                                    $this->load->model('activity_data_model');
                                    $fields = array('candsid' =>  $candidate_details[0]['id'],
                                        'ClientRefNumber' =>  $candidate_details[0]['ClientRefNumber'],
                                        'comp_table_id' => $court_detail['id'],
                                        'activity_status' => 'Stop Check',
                                        'activity_mode' => 'Stop Check',
                                        'activity_type' => 'Stop Check',
                                        'action' => 'Stop Check',
                                        'remarks' => 'Communication received from client requesting to Stop Check.',
                                        'created_by' => $this->user_info['id'],
                                        'created_on' => date(DB_DATE_FORMAT),
                                        'component_type' => 5
                                    );
                                    $result1 = $this->activity_data_model->activity_log_save($fields);
    
                        
                                    $this->load->model('activity_data_model');
                                    $field = array('candsid' => $candidate_details[0]['id'],
                                        'comp_table_id' => $court_detail['id'],
                                        'ClientRefNumber' =>  $candidate_details[0]['ClientRefNumber'],
                                        'activity_status' => 'Stop Check',
                                        'activity_mode' => 'Stop Check',
                                        'activity_type' => 'Stop Check',
                                        'action' => 'Stop Check',
                                        'remarks' =>  'Communication received from client requesting to Stop Check.',
                                        'created_on' => date(DB_DATE_FORMAT),
                                        'created_by' => $this->user_info['id'],
                                        'is_auto_filled' => 1,
        
                                    );

                                    $field_user_activity = array('CandidateName' => $candidate_details[0]['CandidateName'],
                                        'component_ref_no' => $candidate_details[0]['cmp_ref_no']);
        
                                    $result = $this->activity_data_model->save_trigger_save($field, array('component_type' => 5), $field_user_activity);
        

                                    $field_array = array(
                                        'verfstatus' => 9,
                                        'var_filter_status' => "Closed",
                                        'var_report_status' => "Stop Check",
                                        'closuredate' => date('Y-m-d'),
                                        'remarks' => 'Communication received from client requesting to Stop Check.',
                                        'modified_on' => date(DB_DATE_FORMAT),
                                        'modified_by' => $this->user_info['id'],
                                        'activity_log_id' => $result1
                                    );
                                    $this->load->model('court_verificatiion_model'); 
                                    $result = $this->court_verificatiion_model->save_update_result(array_map('strtolower', $field_array), array('courtver_id' =>  $court_detail['id']));
                                    
                                    $field_result = array(
                                        'verfstatus' => 9,
                                        'candsid' => $candidate_details[0]['id'],
                                        'clientid' => $candidate_details[0]['clientid'],  
                                        'var_filter_status' => "Closed",
                                        'var_report_status' => "Stop Check",
                                        'closuredate' => date('Y-m-d'),
                                        'courtver_id' =>  $court_detail['id'],
                                        'remarks' => 'Communication received from client requesting to Stop Check.',
                                        'created_on' => date(DB_DATE_FORMAT),
                                        'created_by' => $this->user_info['id'],
                                        'activity_log_id' => $result1
                                    );
                       
                                    $result = $this->court_verificatiion_model->save_update_result_court(array_map('strtolower', $field_result));
                             
                                    $get_view_vendor_master_details = $this->court_verificatiion_model->get_client_id(array('view_vendor_master_log.final_status' => 'wip','view_vendor_master_log.component'=>'courtver','component_tbl_id' => 5,'courtver.id' => $court_detail['id']));
                                      
                                    if(!empty($get_view_vendor_master_details[0]))
                                    {
                                        $court_details_vendor[] = $this->court_verificatiion_model->get_court_details_for_approval($get_view_vendor_master_details[0]['courtver_vendor_log_id']);
                                      
                                        $field_array = array(
                                            'remarks' => "Stop Check",
                                            'rejected_by' => $this->user_info['id'],
                                            'rejected_on' => date(DB_DATE_FORMAT),
                                            'status' => 5,
                                            'final_status' => "cancelled",
                                        );
                        
                                        $result_view_vendor_log = $this->court_verificatiion_model->save_vendor_details_cancel($field_array, array('id' => $get_view_vendor_master_details[0]['id']));
                        
                                        $field_array_court = array(
                                            'remarks' => "Stop Check",
                                            'modified_by' => $this->user_info['id'],
                                            'modified_on' => date(DB_DATE_FORMAT),
                                            'status' => 2,
                                        );
                        
                                        $court_vendor_result = $this->court_verificatiion_model->update_court_vendor_log('courtver_vendor_log', $field_array_court, array('id' =>  $get_view_vendor_master_details[0]['vendor_master_log_case']));
                           
                                    }
                                    
                            }

                            if(isset($court_details_vendor) && !empty($court_details_vendor))
                            {
                                $court_detail_vendor =  (array_map('current', $court_details_vendor));
                               
                                foreach($court_detail_vendor as $k_court => $v_court) {
                                    $new_arr_court[$v_court['vendor_id']][]=$v_court;
                                }
    
                                foreach ($new_arr_court as $key_court => $value_court) {
    
                                    $email_tmpl_data['subject'] = 'Court Verification - Stop check cases_'.date("d-M-Y H:i");
                                    $message = "<p>Team,</p><p> The below case/s have been cancelled by the client.</p>";
                                        
                                    $message .= "<table border = '2'  style='border-spacing: 10; border-collapse: collapse;'>
                                        <tr>
                                           <th style='background-color: #EDEDED;text-align:center'>Component Ref No</th>
                                           <th style='background-color: #EDEDED;text-align:center'>Candidate Name</th>
                                           <th style='background-color: #EDEDED;text-align:center'>Date of Birth</th>
                                           <th style='background-color: #EDEDED;text-align:center'>Father's Name</th>
                                           <th style='background-color: #EDEDED;text-align:center'>Address</th>
                                           <th style='background-color: #EDEDED;text-align:center'>City</th>
                                           <th style='background-color: #EDEDED;text-align:center'>Pincode</th>
                                           <th style='background-color: #EDEDED;text-align:center'>State</th>
                                        </tr>";
    
                                         foreach ($value_court as $court_key => $court_value) {
                                    
                                            $message .= '<tr>
                                            <td style="text-align:center">'.$court_value['court_com_ref'] . '</td>
                                            <td style="text-align:center">'.ucwords($court_value['CandidateName']). '</td>
                                            <td style="text-align:center">'.convert_db_to_display_date($court_value['DateofBirth']). '</td>
                                            <td style="text-align:center">'.ucwords($court_value['NameofCandidateFather']) . '</td>
                                            <td style="text-align:center">'.ucwords($court_value['street_address']) . '</td>
                                            <td style="text-align:center">'.ucwords($court_value['city']) . '</td>
                                            <td style="text-align:center">'.$court_value['pincode'] . '</td>
                                            <td style="text-align:center">'.ucwords($court_value['state']) . '</td>
                                            </tr>';
                                           
                                        } 
                                        $message .= "</table>";
    
                                        $message .= "<br><br><p><b>Note :</b> <I>This is an auto generated email. Request you to write back within 24 hrs with any discrepancy.</I>";
                                        
                                        $to_emails = $this->court_verificatiion_model->vendor_email_id(array('vendors.id' => $key_court));
             
                                        $email_tmpl_data['to_emails'] = $to_emails[0]['email_id'];
    
                                        $email_tmpl_data['message'] = $message;
                  
                                        $result = $this->email->address_approve_vendor_cases_mail_send($email_tmpl_data);
    
                                        $this->email->clear(true);
                                        unset($court_details_vendor);
                                        unset($new_arr_court);
                                }
    

                            }
                            
                        }     
                      
                    }

                    if(in_array('globdbver', $component_id))
                    {
                        
                        $global_details = $this->candidates_model->select_global_database_wip_join(array('glodbver.candsid' =>  $candidate_details[0]['id']));
                    
                        if(!empty( $global_details))
                        {
                            foreach ($global_details as $key => $global_detail) {
            
                             
                                $check_insuff =  $this->candidates_model->get_global_insuff_details(array('glodbver_insuff.status'=> 1,'glodbver_id' =>$global_detail['id']));
                            
                                if(!empty($check_insuff[0]))
                                { 
                                    $hold_days = getNetWorkDays($check_insuff[0]['insuff_raised_date'],date("Y-m-d"));
                                    $this->load->model('global_database_model');
                                    $date_tat = $this->global_database_model->get_date_for_update(array('id' => $global_detail['id']));
                                    
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
                                 
                                    $result_due_date = $this->global_database_model->update_due_date(array('due_date' => $closed_date, 'tat_status' => $new_tat), array('id' => $global_detail['id']));
                
                                    $fields = array('insuff_clear_date' => date('Y-m-d'), 'insuff_remarks' => "Stop check", 'insuff_cleared_timestamp' => date(DB_DATE_FORMAT), 'status' => 2, 'insuff_cleared_by' => $this->user_info['id'], 'auto_stamp' => 2, 'hold_days' => $hold_days);
                
                                    $result_insuff = $this->global_database_model->save_update_insuff($fields, array('id' => $check_insuff[0]['id']));
                   
                                    
                                }

                                    $this->load->model('activity_data_model');
                                    $fields = array('candsid' =>  $candidate_details[0]['id'],
                                        'ClientRefNumber' =>  $candidate_details[0]['ClientRefNumber'],
                                        'comp_table_id' => $global_detail['id'],
                                        'activity_status' => 'Stop Check',
                                        'activity_mode' => 'Stop Check',
                                        'activity_type' => 'Stop Check',
                                        'action' => 'Stop Check',
                                        'remarks' => 'Communication received from client requesting to Stop Check.',
                                        'created_by' => $this->user_info['id'],
                                        'created_on' => date(DB_DATE_FORMAT),
                                        'component_type' => 6
                                    );
                                    $result1 = $this->activity_data_model->activity_log_save($fields);
    
                        
                                    $this->load->model('activity_data_model');
                                    $field = array('candsid' => $candidate_details[0]['id'],
                                        'comp_table_id' => $global_detail['id'],
                                        'ClientRefNumber' =>  $candidate_details[0]['ClientRefNumber'],
                                        'activity_status' => 'Stop Check',
                                        'activity_mode' => 'Stop Check',
                                        'activity_type' => 'Stop Check',
                                        'action' => 'Stop Check',
                                        'remarks' =>  'Communication received from client requesting to Stop Check.',
                                        'created_on' => date(DB_DATE_FORMAT),
                                        'created_by' => $this->user_info['id'],
                                        'is_auto_filled' => 1,
        
                                    );

                                    $field_user_activity = array('CandidateName' => $candidate_details[0]['CandidateName'],
                                        'component_ref_no' => $candidate_details[0]['cmp_ref_no']);
        
                                    $result = $this->activity_data_model->save_trigger_save($field, array('component_type' => 6), $field_user_activity);
        

                                    $field_array = array(
                                        'verfstatus' => 9,
                                        'var_filter_status' => "Closed",
                                        'var_report_status' => "Stop Check",
                                        'closuredate' => date('Y-m-d'),
                                        'remarks' => 'Communication received from client requesting to Stop Check.',
                                        'modified_on' => date(DB_DATE_FORMAT),
                                        'modified_by' => $this->user_info['id'],
                                        'activity_log_id' => $result1
                                    );
                                    $this->load->model('global_database_model'); 
                                    $result = $this->global_database_model->save_update_initiated_date_global_database(array_map('strtolower', $field_array), array('glodbver_id' =>  $global_detail['id']));
                                    
                                    $field_result = array(
                                        'verfstatus' => 9,
                                        'candsid' => $candidate_details[0]['id'],
                                        'clientid' => $candidate_details[0]['clientid'],  
                                        'var_filter_status' => "Closed",
                                        'var_report_status' => "Stop Check",
                                        'closuredate' => date('Y-m-d'),
                                        'glodbver_id' =>  $global_detail['id'],
                                        'remarks' => 'Communication received from client requesting to Stop Check.',
                                        'created_on' => date(DB_DATE_FORMAT),
                                        'created_by' => $this->user_info['id'],
                                        'activity_log_id' => $result1
                                    );
                       
                                    $result = $this->global_database_model->save_update_result_globerver(array_map('strtolower', $field_result));
                             
                                    $get_view_vendor_master_details = $this->global_database_model->get_client_id(array('view_vendor_master_log.final_status' => 'wip','view_vendor_master_log.component'=>'globdbver','component_tbl_id' => 6,'glodbver.id' => $global_detail['id']));
                                      
                                    if(!empty($get_view_vendor_master_details[0]))
                                    {
                                        $global_details_vendor[] = $this->global_database_model->get_global_database_details_for_approval($get_view_vendor_master_details[0]['glodbver_vendor_log_id']);

                                        $field_array = array(
                                            'remarks' => "Stop Check",
                                            'rejected_by' => $this->user_info['id'],
                                            'rejected_on' => date(DB_DATE_FORMAT),
                                            'status' => 5,
                                            'final_status' => "cancelled",
                                        );
                        
                                        $result_view_vendor_log = $this->global_database_model->save_vendor_details_cancel($field_array, array('id' => $get_view_vendor_master_details[0]['id']));
                        
                                        $field_array_global = array(
                                            'remarks' => "Stop Check",
                                            'modified_by' => $this->user_info['id'],
                                            'modified_on' => date(DB_DATE_FORMAT),
                                            'status' => 2,
                                        );
                        
                                        $global_vendor_result = $this->global_database_model->update_court_vendor_log('glodbver_vendor_log', $field_array_global, array('id' =>  $get_view_vendor_master_details[0]['vendor_master_log_case']));
                           
                                    }
                                    
                            }
                            
                            if(isset($global_details_vendor) && !empty($global_details_vendor))
                            {
                                $global_detail_vendor =  (array_map('current', $global_details_vendor));
                               
                                foreach($global_detail_vendor as $k_global => $v_global) {
                                    $new_arr_global[$v_global['vendor_id']][]=$v_global;
                                } 
    
    
                                foreach ($new_arr_global as $key_global => $value_global) {
    
                                    $email_tmpl_data['subject'] = 'Global Database - Stop check cases_'.date("d-M-Y H:i");
                                    $message = "<p>Team,</p><p> The below case/s have been cancelled by the client.</p>";
                                        
                                    $message .= "<table border = '2'  style='border-spacing: 10; border-collapse: collapse;'>
                                        <tr>
                                           <th style='background-color: #EDEDED;text-align:center'>Component Ref No</th>
                                           <th style='background-color: #EDEDED;text-align:center'>Candidate Name</th>
                                           <th style='background-color: #EDEDED;text-align:center'>Date of Birth</th>
                                           <th style='background-color: #EDEDED;text-align:center'>Gender</th>
                                           <th style='background-color: #EDEDED;text-align:center'>Father's Name</th>
                                           <th style='background-color: #EDEDED;text-align:center'>Address</th>
                                           <th style='background-color: #EDEDED;text-align:center'>City</th>
                                           <th style='background-color: #EDEDED;text-align:center'>Pincode</th>
                                           <th style='background-color: #EDEDED;text-align:center'>State</th>
                                        </tr>";
    
                                         foreach ($value_global as $global_key => $global_value) {
                                    
                                            $message .= '<tr>
                                            <td style="text-align:center">'.$global_value['global_com_ref'] . '</td>
                                            <td style="text-align:center">'.ucwords($global_value['CandidateName']). '</td>
                                            <td style="text-align:center">'.convert_db_to_display_date($global_value['DateofBirth']). '</td>
                                            <td style="text-align:center">'.$global_value['gender']. '</td>
                                            <td style="text-align:center">'.ucwords($global_value['NameofCandidateFather']) . '</td>
                                            <td style="text-align:center">'.ucwords($global_value['street_address']) . '</td>
                                            <td style="text-align:center">'.ucwords($global_value['city']) . '</td>
                                            <td style="text-align:center">'.$global_value['pincode'] . '</td>
                                            <td style="text-align:center">'.ucwords($global_value['state']) . '</td>
                                            </tr>';
                                           
                                        } 
                                        $message .= "</table>";
    
                                        $message .= "<br><br><p><b>Note :</b> <I>This is an auto generated email. Request you to write back within 24 hrs with any discrepancy.</I>";
                                        
                                        $to_emails = $this->global_database_model->vendor_email_id(array('vendors.id' => $key_global));
             
                                        $email_tmpl_data['to_emails'] = $to_emails[0]['email_id'];
    
                                        $email_tmpl_data['message'] = $message;
                  
                                        $result = $this->email->address_approve_vendor_cases_mail_send($email_tmpl_data);
    
                                        $this->email->clear(true);
                                        unset($global_details_vendor);
                                        unset($new_arr_global);
                                }   

                            }

                            
                        }     
                      
                    }

                    if(in_array('crimver', $component_id))
                    {
                        
                        $pcc_details = $this->candidates_model->select_pcc_wip_join(array('pcc.candsid' =>  $candidate_details[0]['id']));
                    
                        if(!empty( $pcc_details))
                        {
                            foreach ($pcc_details as $key => $pcc_detail) {
            
                             
                                $check_insuff =  $this->candidates_model->get_pcc_insuff_details(array('pcc_insuff.status'=> 1,'pcc_id' =>$pcc_detail['id']));
                            
                                if(!empty($check_insuff[0]))
                                { 
                                    $hold_days = getNetWorkDays($check_insuff[0]['insuff_raised_date'],date("Y-m-d"));
                                    $this->load->model('pcc_verificatiion_model');
                                    $date_tat = $this->pcc_verificatiion_model->get_date_for_update(array('id' => $pcc_detail['id']));
                                    
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
                                 
                                    $result_due_date = $this->pcc_verificatiion_model->update_due_date(array('due_date' => $closed_date, 'tat_status' => $new_tat), array('id' => $pcc_detail['id']));
                
                                    $fields = array('insuff_clear_date' => date('Y-m-d'), 'insuff_remarks' => "Stop check", 'insuff_cleared_timestamp' => date(DB_DATE_FORMAT), 'status' => 2, 'insuff_cleared_by' => $this->user_info['id'], 'auto_stamp' => 2, 'hold_days' => $hold_days);
                
                                    $result_insuff = $this->pcc_verificatiion_model->save_update_insuff($fields, array('id' => $check_insuff[0]['id']));
                   
                                    
                                }

                                    $this->load->model('activity_data_model');
                                    $fields = array('candsid' =>  $candidate_details[0]['id'],
                                        'ClientRefNumber' =>  $candidate_details[0]['ClientRefNumber'],
                                        'comp_table_id' => $pcc_detail['id'],
                                        'activity_status' => 'Stop Check',
                                        'activity_mode' => 'Stop Check',
                                        'activity_type' => 'Stop Check',
                                        'action' => 'Stop Check',
                                        'remarks' => 'Communication received from client requesting to Stop Check.',
                                        'created_by' => $this->user_info['id'],
                                        'created_on' => date(DB_DATE_FORMAT),
                                        'component_type' => 8
                                    );
                                    $result1 = $this->activity_data_model->activity_log_save($fields);
    
                        
                                    $this->load->model('activity_data_model');
                                    $field = array('candsid' => $candidate_details[0]['id'],
                                        'comp_table_id' => $pcc_detail['id'],
                                        'ClientRefNumber' =>  $candidate_details[0]['ClientRefNumber'],
                                        'activity_status' => 'Stop Check',
                                        'activity_mode' => 'Stop Check',
                                        'activity_type' => 'Stop Check',
                                        'action' => 'Stop Check',
                                        'remarks' =>  'Communication received from client requesting to Stop Check.',
                                        'created_on' => date(DB_DATE_FORMAT),
                                        'created_by' => $this->user_info['id'],
                                        'is_auto_filled' => 1,
        
                                    );

                                    $field_user_activity = array('CandidateName' => $candidate_details[0]['CandidateName'],
                                        'component_ref_no' => $candidate_details[0]['cmp_ref_no']);
        
                                    $result = $this->activity_data_model->save_trigger_save($field, array('component_type' => 8), $field_user_activity);
        

                                    $field_array = array(
                                        'verfstatus' => 9,
                                        'var_filter_status' => "Closed",
                                        'var_report_status' => "Stop Check",
                                        'closuredate' => date('Y-m-d'),
                                        'remarks' => 'Communication received from client requesting to Stop Check.',
                                        'modified_on' => date(DB_DATE_FORMAT),
                                        'modified_by' => $this->user_info['id'],
                                        'activity_log_id' => $result1
                                    );
                                    $this->load->model('pcc_verificatiion_model'); 
                                    $result = $this->pcc_verificatiion_model->save_update_result(array_map('strtolower', $field_array), array('pcc_id' =>  $pcc_detail['id']));
                                    
                                    $field_result = array(
                                        'verfstatus' => 9,
                                        'candsid' => $candidate_details[0]['id'],
                                        'clientid' => $candidate_details[0]['clientid'],  
                                        'var_filter_status' => "Closed",
                                        'var_report_status' => "Stop Check",
                                        'closuredate' => date('Y-m-d'),
                                        'pcc_id' =>  $pcc_detail['id'],
                                        'remarks' => 'Communication received from client requesting to Stop Check.',
                                        'created_on' => date(DB_DATE_FORMAT),
                                        'created_by' => $this->user_info['id'],
                                        'activity_log_id' => $result1
                                    );
                       
                                    $result = $this->pcc_verificatiion_model->save_update_result_pcc(array_map('strtolower', $field_result));
                             
                                    $get_view_vendor_master_details = $this->pcc_verificatiion_model->get_client_id(array('view_vendor_master_log.final_status' => 'wip','view_vendor_master_log.component'=>'crimver','component_tbl_id' => 8,'pcc.id' => $pcc_detail['id']));
                                      
                                    if(!empty($get_view_vendor_master_details[0]))
                                    {
                                        $field_array = array(
                                            'remarks' => "Stop Check",
                                            'rejected_by' => $this->user_info['id'],
                                            'rejected_on' => date(DB_DATE_FORMAT),
                                            'status' => 5,
                                            'final_status' => "cancelled",
                                        );
                        
                                        $result_view_vendor_log = $this->pcc_verificatiion_model->save_vendor_details_cancel($field_array, array('id' => $get_view_vendor_master_details[0]['id']));
                        
                                        $field_array_pcc = array(
                                            'remarks' => "Stop Check",
                                            'modified_by' => $this->user_info['id'],
                                            'modified_on' => date(DB_DATE_FORMAT),
                                            'status' => 2,
                                        );
                        
                                        $pcc_vendor_result = $this->pcc_verificatiion_model->update_pcc_vendor_log('pcc_vendor_log', $field_array_pcc, array('id' =>  $get_view_vendor_master_details[0]['vendor_master_log_case']));
                           
                                    }
                                    
                            }
                            
                        }     
                      
                    }

                    if(in_array('identity', $component_id))
                    {
                        
                        $identity_details = $this->candidates_model->select_identity_wip_join(array('identity.candsid' =>  $candidate_details[0]['id']));
                    
                        if(!empty( $identity_details))
                        {
                            foreach ($identity_details as $key => $identity_detail) {
            
                             
                                $check_insuff =  $this->candidates_model->get_identity_insuff_details(array('identity_insuff.status'=> 1,'identity_id' =>$identity_detail['id']));
                            
                                if(!empty($check_insuff[0]))
                                { 
                                    $hold_days = getNetWorkDays($check_insuff[0]['insuff_raised_date'],date("Y-m-d"));
                                    $this->load->model('identity_model');
                                    $date_tat = $this->identity_model->get_date_for_update(array('id' => $identity_detail['id']));
                                    
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
                                 
                                    $result_due_date = $this->identity_model->update_due_date(array('due_date' => $closed_date, 'tat_status' => $new_tat), array('id' => $identity_detail['id']));
                
                                    $fields = array('insuff_clear_date' => date('Y-m-d'), 'insuff_remarks' => "Stop check", 'insuff_cleared_timestamp' => date(DB_DATE_FORMAT), 'status' => 2, 'insuff_cleared_by' => $this->user_info['id'], 'auto_stamp' => 2, 'hold_days' => $hold_days);
                
                                    $result_insuff = $this->identity_model->save_update_insuff($fields, array('id' => $check_insuff[0]['id']));
                   
                                    
                                }

                                    $this->load->model('activity_data_model');
                                    $fields = array('candsid' =>  $candidate_details[0]['id'],
                                        'ClientRefNumber' =>  $candidate_details[0]['ClientRefNumber'],
                                        'comp_table_id' => $identity_detail['id'],
                                        'activity_status' => 'Stop Check',
                                        'activity_mode' => 'Stop Check',
                                        'activity_type' => 'Stop Check',
                                        'action' => 'Stop Check',
                                        'remarks' => 'Communication received from client requesting to Stop Check.',
                                        'created_by' => $this->user_info['id'],
                                        'created_on' => date(DB_DATE_FORMAT),
                                        'component_type' => 9
                                    );
                                    $result1 = $this->activity_data_model->activity_log_save($fields);
    
                        
                                    $this->load->model('activity_data_model');
                                    $field = array('candsid' => $candidate_details[0]['id'],
                                        'comp_table_id' => $identity_detail['id'],
                                        'ClientRefNumber' =>  $candidate_details[0]['ClientRefNumber'],
                                        'activity_status' => 'Stop Check',
                                        'activity_mode' => 'Stop Check',
                                        'activity_type' => 'Stop Check',
                                        'action' => 'Stop Check',
                                        'remarks' =>  'Communication received from client requesting to Stop Check.',
                                        'created_on' => date(DB_DATE_FORMAT),
                                        'created_by' => $this->user_info['id'],
                                        'is_auto_filled' => 1,
        
                                    );

                                    $field_user_activity = array('CandidateName' => $candidate_details[0]['CandidateName'],
                                        'component_ref_no' => $candidate_details[0]['cmp_ref_no']);
        
                                    $result = $this->activity_data_model->save_trigger_save($field, array('component_type' => 9), $field_user_activity);
        

                                    $field_array = array(
                                        'verfstatus' => 9,
                                        'var_filter_status' => "Closed",
                                        'var_report_status' => "Stop Check",
                                        'closuredate' => date('Y-m-d'),
                                        'remarks' => 'Communication received from client requesting to Stop Check.',
                                        'modified_on' => date(DB_DATE_FORMAT),
                                        'modified_by' => $this->user_info['id'],
                                        'activity_log_id' => $result1
                                    );
                                    $this->load->model('identity_model'); 
                                    $result = $this->identity_model->save_update_ver_result(array_map('strtolower', $field_array), array('identity_id' =>  $identity_detail['id']));
                                    
                                    $field_result = array(
                                        'verfstatus' => 9,
                                        'candsid' => $candidate_details[0]['id'],
                                        'clientid' => $candidate_details[0]['clientid'],  
                                        'var_filter_status' => "Closed",
                                        'var_report_status' => "Stop Check",
                                        'closuredate' => date('Y-m-d'),
                                        'identity_id' =>  $identity_detail['id'],
                                        'remarks' => 'Communication received from client requesting to Stop Check.',
                                        'created_on' => date(DB_DATE_FORMAT),
                                        'created_by' => $this->user_info['id'],
                                        'activity_log_id' => $result1
                                    );
                       
                                    $result = $this->identity_model->save_update_result_identity(array_map('strtolower', $field_result));
                             
                                    $get_view_vendor_master_details = $this->identity_model->get_client_id(array('view_vendor_master_log.final_status' => 'wip','view_vendor_master_log.component'=>'identity','component_tbl_id' => 9,'identity.id' => $identity_detail['id']));
                                      
                                    if(!empty($get_view_vendor_master_details[0]))
                                    {
                                        $field_array = array(
                                            'remarks' => "Stop Check",
                                            'rejected_by' => $this->user_info['id'],
                                            'rejected_on' => date(DB_DATE_FORMAT),
                                            'status' => 5,
                                            'final_status' => "cancelled",
                                        );
                        
                                        $result_view_vendor_log = $this->identity_model->save_vendor_details_cancel($field_array, array('id' => $get_view_vendor_master_details[0]['id']));
                        
                                        $field_array_identity = array(
                                            'remarks' => "Stop Check",
                                            'modified_by' => $this->user_info['id'],
                                            'modified_on' => date(DB_DATE_FORMAT),
                                            'status' => 2,
                                        );
                        
                                        $identity_vendor_result = $this->identity_model->update_address_vendor_log('identity_vendor_log', $field_array_identity, array('id' =>  $get_view_vendor_master_details[0]['vendor_master_log_case']));
                           
                                    }
                                    
                            }
                            
                        }     
                      
                    }

                    if(in_array('cbrver', $component_id))
                    {
                        
                        $credit_details = $this->candidates_model->select_credit_report_wip_join(array('credit_report.candsid' =>  $candidate_details[0]['id']));
                    
                        if(!empty( $credit_details))
                        {
                            foreach ($credit_details as $key => $credit_detail) {
            
                             
                                $check_insuff =  $this->candidates_model->get_credit_report_insuff_details(array('credit_report_insuff.status'=> 1,'credit_report_id' =>$credit_detail['id']));
                            
                                if(!empty($check_insuff[0]))
                                { 
                                    $hold_days = getNetWorkDays($check_insuff[0]['insuff_raised_date'],date("Y-m-d"));
                                    $this->load->model('credit_report_model');
                                    $date_tat = $this->credit_report_model->get_date_for_update(array('id' => $credit_detail['id']));
                                    
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
                                 
                                    $result_due_date = $this->credit_report_model->update_due_date(array('due_date' => $closed_date, 'tat_status' => $new_tat), array('id' => $credit_detail['id']));
                
                                    $fields = array('insuff_clear_date' => date('Y-m-d'), 'insuff_remarks' => "Stop check", 'insuff_cleared_timestamp' => date(DB_DATE_FORMAT), 'status' => 2, 'insuff_cleared_by' => $this->user_info['id'], 'auto_stamp' => 2, 'hold_days' => $hold_days);
                
                                    $result_insuff = $this->credit_report_model->save_update_insuff($fields, array('id' => $check_insuff[0]['id']));
                   
                                    
                                }

                                    $this->load->model('activity_data_model');
                                    $fields = array('candsid' =>  $candidate_details[0]['id'],
                                        'ClientRefNumber' =>  $candidate_details[0]['ClientRefNumber'],
                                        'comp_table_id' => $credit_detail['id'],
                                        'activity_status' => 'Stop Check',
                                        'activity_mode' => 'Stop Check',
                                        'activity_type' => 'Stop Check',
                                        'action' => 'Stop Check',
                                        'remarks' => 'Communication received from client requesting to Stop Check.',
                                        'created_by' => $this->user_info['id'],
                                        'created_on' => date(DB_DATE_FORMAT),
                                        'component_type' => 10
                                    );
                                    $result1 = $this->activity_data_model->activity_log_save($fields);
    
                        
                                    $this->load->model('activity_data_model');
                                    $field = array('candsid' => $candidate_details[0]['id'],
                                        'comp_table_id' => $credit_detail['id'],
                                        'ClientRefNumber' =>  $candidate_details[0]['ClientRefNumber'],
                                        'activity_status' => 'Stop Check',
                                        'activity_mode' => 'Stop Check',
                                        'activity_type' => 'Stop Check',
                                        'action' => 'Stop Check',
                                        'remarks' =>  'Communication received from client requesting to Stop Check.',
                                        'created_on' => date(DB_DATE_FORMAT),
                                        'created_by' => $this->user_info['id'],
                                        'is_auto_filled' => 1,
        
                                    );

                                    $field_user_activity = array('CandidateName' => $candidate_details[0]['CandidateName'],
                                        'component_ref_no' => $candidate_details[0]['cmp_ref_no']);
        
                                    $result = $this->activity_data_model->save_trigger_save($field, array('component_type' => 10), $field_user_activity);
        

                                    $field_array = array(
                                        'verfstatus' => 9,
                                        'var_filter_status' => "Closed",
                                        'var_report_status' => "Stop Check",
                                        'closuredate' => date('Y-m-d'),
                                        'remarks' => 'Communication received from client requesting to Stop Check.',
                                        'modified_on' => date(DB_DATE_FORMAT),
                                        'modified_by' => $this->user_info['id'],
                                        'activity_log_id' => $result1
                                    );
                                    $this->load->model('credit_report_model'); 
                                    $result = $this->credit_report_model->save_update_ver_result(array_map('strtolower', $field_array), array('credit_report_id' =>  $credit_detail['id']));
                                    
                                    $field_result = array(
                                        'verfstatus' => 9,
                                        'candsid' => $candidate_details[0]['id'],
                                        'clientid' => $candidate_details[0]['clientid'],  
                                        'var_filter_status' => "Closed",
                                        'var_report_status' => "Stop Check",
                                        'closuredate' => date('Y-m-d'),
                                        'credit_report_id' =>  $credit_detail['id'],
                                        'remarks' => 'Communication received from client requesting to Stop Check.',
                                        'created_on' => date(DB_DATE_FORMAT),
                                        'created_by' => $this->user_info['id'],
                                        'activity_log_id' => $result1
                                    );
                       
                                    $result = $this->credit_report_model->save_update_result_credit(array_map('strtolower', $field_result));
                             
                                    $get_view_vendor_master_details = $this->credit_report_model->get_client_id(array('view_vendor_master_log.final_status' => 'wip','view_vendor_master_log.component'=>'cbrver','component_tbl_id' => 10,'credit_report.id' => $credit_detail['id']));
                                      
                                    if(!empty($get_view_vendor_master_details[0]))
                                    {
                                        $credit_report_details_vendor[] = $this->credit_report_model->get_credit_report_details_for_approval($get_view_vendor_master_details[0]['credit_report_vendor_log_id']);

                                        $field_array = array(
                                            'remarks' => "Stop Check",
                                            'rejected_by' => $this->user_info['id'],
                                            'rejected_on' => date(DB_DATE_FORMAT),
                                            'status' => 5,
                                            'final_status' => "cancelled",
                                        );
                        
                                        $result_view_vendor_log = $this->credit_report_model->save_vendor_details_cancel($field_array, array('id' => $get_view_vendor_master_details[0]['id']));
                        
                                        $field_array_credit = array(
                                            'remarks' => "Stop Check",
                                            'modified_by' => $this->user_info['id'],
                                            'modified_on' => date(DB_DATE_FORMAT),
                                            'status' => 2,
                                        );
                        
                                        $credit_vendor_result = $this->credit_report_model->update_credit_report_vendor_log('credit_report_vendor_log', $field_array_credit, array('id' =>  $get_view_vendor_master_details[0]['vendor_master_log_case']));
                           
                                    }
                                    
                            }

                            if(isset($credit_report_details_vendor) && !empty($credit_report_details_vendor))
                            {


                                $credit_report_detail_vendor =  (array_map('current', $credit_report_details_vendor));
                                
                                foreach($credit_report_detail_vendor as $k_credit => $v_credit) {
                                    $new_arr_credit[$v_credit['vendor_id']][]=$v_credit;
                                }
                                
                                foreach ($new_arr_credit as $key_credit => $value_credit) {

                                    $email_tmpl_data['subject'] = 'Credit Report - Stop check cases_'.date("d-M-Y H:i");
                                    $message = "<p>Team,</p><p> The below case/s have been cancelled by the client.</p>";
                                        
                                    $message .= "<table border = '2'  style='border-spacing: 10; border-collapse: collapse;'>
                                        <tr>
                                        <th style='background-color: #EDEDED;text-align:center'>Component Ref No</th>
                                        <th style='background-color: #EDEDED;text-align:center'>Candidate Name</th>
                                        <th style='background-color: #EDEDED;text-align:center'>Date of Birth</th>
                                        <th style='background-color: #EDEDED;text-align:center'>Father's Name</th>
                                        <th style='background-color: #EDEDED;text-align:center'>Pan No</th>
                                        </tr>";

                                        foreach ($value_credit as $credit_report_key => $credit_report_value) {
                                    
                                            $message .= '<tr>
                                            <td style="text-align:center">'.$credit_report_value['credit_report_com_ref'] . '</td>
                                            <td style="text-align:center">'.ucwords($credit_report_value['CandidateName']). '</td>
                                            <td style="text-align:center">'.convert_db_to_display_date($credit_report_value['DateofBirth']). '</td>
                                            <td style="text-align:center">'.ucwords($credit_report_value['NameofCandidateFather']) . '</td>
                                            <td style="text-align:center">'.ucwords($credit_report_value['id_number']) . '</td>
                                            </tr>';
                                        
                                        } 
                                        $message .= "</table>";

                                        $message .= "<br><br><p><b>Note :</b> <I>This is an auto generated email. Request you to write back within 24 hrs with any discrepancy.</I>";
                                        
                                        $to_emails = $this->credit_report_model->vendor_email_id(array('vendors.id' => $key_credit));
            
                                        $email_tmpl_data['to_emails'] = $to_emails[0]['email_id'];

                                        $email_tmpl_data['message'] = $message;
                
                                        $result = $this->email->address_approve_vendor_cases_mail_send($email_tmpl_data);

                                        $this->email->clear(true);  

                                        unset($credit_report_details_vendor);
                                        unset($new_arr_credit);
                                }

                            }
                            
                        }     
                      
                    }

                    
                    if(in_array('narcver', $component_id))
                    {
                        
                        $drugs_details = $this->candidates_model->select_drugs_wip_join(array('drug_narcotis.candsid' =>  $candidate_details[0]['id']));
                    
                        if(!empty( $drugs_details))
                        {
                            foreach ($drugs_details as $key => $drugs_detail) {
            
                             
                                $check_insuff =  $this->candidates_model->get_drugs_report_insuff_details(array('drug_narcotis_insuff.status'=> 1,'drug_narcotis_id' =>$drugs_detail['id']));
                            
                                if(!empty($check_insuff[0]))
                                { 
                                    $hold_days = getNetWorkDays($check_insuff[0]['insuff_raised_date'],date("Y-m-d"));
                                    $this->load->model('drug_verificatiion_model');
                                    $date_tat = $this->drug_verificatiion_model->get_date_for_update(array('id' => $drugs_detail['id']));
                                    
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
                                 
                                    $result_due_date = $this->drug_verificatiion_model->update_due_date(array('due_date' => $closed_date, 'tat_status' => $new_tat), array('id' => $drugs_detail['id']));
                
                                    $fields = array('insuff_clear_date' => date('Y-m-d'), 'insuff_remarks' => "Stop check", 'insuff_cleared_timestamp' => date(DB_DATE_FORMAT), 'status' => 2, 'insuff_cleared_by' => $this->user_info['id'], 'auto_stamp' => 2, 'hold_days' => $hold_days);
                
                                    $result_insuff = $this->drug_verificatiion_model->save_update_insuff($fields, array('id' => $check_insuff[0]['id']));
                   
                                    
                                }

                                    $this->load->model('activity_data_model');
                                    $fields = array('candsid' =>  $candidate_details[0]['id'],
                                        'ClientRefNumber' =>  $candidate_details[0]['ClientRefNumber'],
                                        'comp_table_id' => $drugs_detail['id'],
                                        'activity_status' => 'Stop Check',
                                        'activity_mode' => 'Stop Check',
                                        'activity_type' => 'Stop Check',
                                        'action' => 'Stop Check',
                                        'remarks' => 'Communication received from client requesting to Stop Check.',
                                        'created_by' => $this->user_info['id'],
                                        'created_on' => date(DB_DATE_FORMAT),
                                        'component_type' => 7
                                    );
                                    $result1 = $this->activity_data_model->activity_log_save($fields);
    
                        
                                    $this->load->model('activity_data_model');
                                    $field = array('candsid' => $candidate_details[0]['id'],
                                        'comp_table_id' => $drugs_detail['id'],
                                        'ClientRefNumber' =>  $candidate_details[0]['ClientRefNumber'],
                                        'activity_status' => 'Stop Check',
                                        'activity_mode' => 'Stop Check',
                                        'activity_type' => 'Stop Check',
                                        'action' => 'Stop Check',
                                        'remarks' =>  'Communication received from client requesting to Stop Check.',
                                        'created_on' => date(DB_DATE_FORMAT),
                                        'created_by' => $this->user_info['id'],
                                        'is_auto_filled' => 1,
        
                                    );

                                    $field_user_activity = array('CandidateName' => $candidate_details[0]['CandidateName'],
                                        'component_ref_no' => $candidate_details[0]['cmp_ref_no']);
        
                                    $result = $this->activity_data_model->save_trigger_save($field, array('component_type' => 7), $field_user_activity);
        

                                    $field_array = array(
                                        'verfstatus' => 9,
                                        'var_filter_status' => "Closed",
                                        'var_report_status' => "Stop Check",
                                        'closuredate' => date('Y-m-d'),
                                        'remarks' => 'Communication received from client requesting to Stop Check.',
                                        'modified_on' => date(DB_DATE_FORMAT),
                                        'modified_by' => $this->user_info['id'],
                                        'activity_log_id' => $result1
                                    );
                                    $this->load->model('drug_verificatiion_model'); 
                                    $result = $this->drug_verificatiion_model->save_update_result(array_map('strtolower', $field_array), array('drug_narcotis_id' =>  $drugs_detail['id']));
                                    
                                    $field_result = array(
                                        'verfstatus' => 9,
                                        'candsid' => $candidate_details[0]['id'],
                                        'clientid' => $candidate_details[0]['clientid'],  
                                        'var_filter_status' => "Closed",
                                        'var_report_status' => "Stop Check",
                                        'closuredate' => date('Y-m-d'),
                                        'drug_narcotis_id' =>  $drugs_detail['id'],
                                        'remarks' => 'Communication received from client requesting to Stop Check.',
                                        'created_on' => date(DB_DATE_FORMAT),
                                        'created_by' => $this->user_info['id'],
                                        'activity_log_id' => $result1
                                    );
                       
                                    $result = $this->drug_verificatiion_model->save_update_result_drugs(array_map('strtolower', $field_result));
                             
                                    $get_view_vendor_master_details = $this->drug_verificatiion_model->get_client_id(array('view_vendor_master_log.final_status' => 'wip','view_vendor_master_log.component'=>'narcver','component_tbl_id' => 7,'drug_narcotis.id' => $drugs_detail['id']));
                                      
                                    if(!empty($get_view_vendor_master_details[0]))
                                    {

                                        $drugs_details_vendor[] = $this->drug_verificatiion_model->get_drugs_details_for_approval($get_view_vendor_master_details[0]['drug_narcotis_vendor_log_id']);
                                        
                                        $field_array = array(
                                            'remarks' => "Stop Check",
                                            'rejected_by' => $this->user_info['id'],
                                            'rejected_on' => date(DB_DATE_FORMAT),
                                            'status' => 5,
                                            'final_status' => "cancelled",
                                        );
                        
                                        $result_view_vendor_log = $this->drug_verificatiion_model->save_vendor_details_cancel($field_array, array('id' => $get_view_vendor_master_details[0]['id']));
                        
                                        $field_array_drugs = array(
                                            'remarks' => "Stop Check",
                                            'modified_by' => $this->user_info['id'],
                                            'modified_on' => date(DB_DATE_FORMAT),
                                            'status' => 2,
                                        );
                        
                                        $drugs_vendor_result = $this->drug_verificatiion_model->update_drugs_vendor_log('drug_narcotis_vendor_log', $field_array_drugs, array('id' =>  $get_view_vendor_master_details[0]['vendor_master_log_case']));
                           
                                    }
                                    
                            }

                            if(isset($drugs_details_vendor) && !empty($drugs_details_vendor))
                            {

                                $drugs_detail_vendor =  (array_map('current', $drugs_details_vendor));
                                
                                foreach($drugs_detail_vendor as $k_drugs => $v_drugs) {
                                $new_arr_drugs[$v_drugs['vendor_id']][]=$v_drugs;
                                }
                        
                                foreach ($new_arr_drugs as $key_drugs => $value_drugs) {
                            
                                    $email_tmpl_data['subject'] = 'Drugs - Stop check cases from_'.date("Y-m-d H:i:s");
                                    $message = "<p>Team,</p><p> The below case/s have been cancelled by the client.</p>";
                                    
                                    $message .= "<table border = '2'  style='border-spacing: 10; border-collapse: collapse;'>
                                    <tr>
                                    <th style='background-color: #EDEDED;text-align:center'>Client Name</th>
                                    <th style='background-color: #EDEDED;text-align:center'>Component Ref No</th>
                                    <th style='background-color: #EDEDED;text-align:center'>Panel</th>
                                    <th style='background-color: #EDEDED;text-align:center'>Candidate Name</th>
                                    <th style='background-color: #EDEDED;text-align:center'>Primary Contact</th>
                                    <th style='background-color: #EDEDED;text-align:center'>Contact No (2)</th>
                                    <th style='background-color: #EDEDED;text-align:center'>Contact No (3)</th>
                                    <th style='background-color: #EDEDED;text-align:center'>Date of Birth</th>
                                    <th style='background-color: #EDEDED;text-align:center'>Father's Name</th>
                                    <th style='background-color: #EDEDED;text-align:center'>Address</th>
                                    <th style='background-color: #EDEDED;text-align:center'>City</th>
                                    <th style='background-color: #EDEDED;text-align:center'>Pincode</th>
                                    <th style='background-color: #EDEDED;text-align:center'>State</th>
                                    </tr>";

                                    foreach ($value_drugs as $drugs_key => $drugs_value) {
                                
                                        $message .= '<tr>
                                        <td style="text-align:center">'.ucwords($drugs_value['clientname']). '</td>
                                        <td style="text-align:center">'.$drugs_value['drug_com_ref'] . '</td>
                                        <td style="text-align:center">'.$drugs_value['drug_test_code'] . '</td>
                                        <td style="text-align:center">'.ucwords($drugs_value['CandidateName']) . '</td>
                                        <td style="text-align:center">'.$drugs_value['CandidatesContactNumber'] . '</td>
                                        <td style="text-align:center">'.$drugs_value['ContactNo1'] . '</td>
                                        <td style="text-align:center">'.$drugs_value['ContactNo2'] . '</td>
                                        <td style="text-align:center">'.convert_db_to_display_date($drugs_value['DateofBirth']) . '</td>
                                        <td style="text-align:center">'.ucwords($drugs_value['NameofCandidateFather']) . '</td>
                                        <td style="text-align:center">'.ucwords($drugs_value['street_address']) . '</td>
                                        <td style="text-align:center">'.ucwords($drugs_value['city']) . '</td>
                                        <td style="text-align:center">'.$drugs_value['pincode'] . '</td>
                                        <td style="text-align:center">'.ucwords($drugs_value['state']) . '</td>
                                        </tr>';
                                    
                                    } 
                                    $message .= "</table>";

                                    $message .= "<br><br><p><b>Note :</b> <I>This is an auto generated email. Request you to write back within 24 hrs with any discrepancy.</I>";
                                    
                                    $to_emails = $this->drug_verificatiion_model->vendor_email_id(array('vendors.id' => $key_drugs));
        
                                    $email_tmpl_data['to_emails'] = $to_emails[0]['email_id'];

                                    $email_tmpl_data['message'] = $message;
            
                                    $result = $this->email->address_approve_vendor_cases_mail_send($email_tmpl_data);

                                    $this->email->clear(true);
                                    unset($drugs_details_vendor);
                                    unset($new_arr_drugs);
                                                          
                        
                                }    
                            }
                            
                        }     
                      
                    }

                    if(in_array('eduver', $component_id))
                    {
                        
                        $education_details = $this->candidates_model->select_education_wip_join(array('education.candsid' =>  $candidate_details[0]['id']));
                    
                        if(!empty( $education_details))
                        {
                            foreach ($education_details as $key => $education_detail) {
            
                             
                                $check_insuff =  $this->candidates_model->get_education_insuff_details(array('education_insuff.status'=> 1,'education_id' =>$education_detail['id']));
                            
                                if(!empty($check_insuff[0]))
                                { 
                                    $hold_days = getNetWorkDays($check_insuff[0]['insuff_raised_date'],date("Y-m-d"));
                                    $this->load->model('education_model');
                                    $date_tat = $this->education_model->get_date_for_update(array('id' => $education_detail['id']));
                                    
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
                                 
                                    $result_due_date = $this->education_model->update_due_date(array('due_date' => $closed_date, 'tat_status' => $new_tat), array('id' => $education_detail['id']));
                
                                    $fields = array('insuff_clear_date' => date('Y-m-d'), 'insuff_remarks' => "Stop check", 'insuff_cleared_timestamp' => date(DB_DATE_FORMAT), 'status' => 2, 'insuff_cleared_by' => $this->user_info['id'], 'auto_stamp' => 2, 'hold_days' => $hold_days);
                
                                    $result_insuff = $this->education_model->save_update_insuff($fields, array('id' => $check_insuff[0]['id']));
                   
                                    
                                }

                                    $this->load->model('activity_data_model');
                                    $fields = array('candsid' =>  $candidate_details[0]['id'],
                                        'ClientRefNumber' =>  $candidate_details[0]['ClientRefNumber'],
                                        'comp_table_id' => $education_detail['id'],
                                        'activity_status' => 'Stop Check',
                                        'activity_mode' => 'Stop Check',
                                        'activity_type' => 'Stop Check',
                                        'action' => 'Stop Check',
                                        'remarks' => 'Communication received from client requesting to Stop Check.',
                                        'created_by' => $this->user_info['id'],
                                        'created_on' => date(DB_DATE_FORMAT),
                                        'component_type' => 3
                                    );
                                    $result1 = $this->activity_data_model->activity_log_save($fields);
    
                        
                                    $this->load->model('activity_data_model');
                                    $field = array('candsid' => $candidate_details[0]['id'],
                                        'comp_table_id' => $education_detail['id'],
                                        'ClientRefNumber' =>  $candidate_details[0]['ClientRefNumber'],
                                        'activity_status' => 'Stop Check',
                                        'activity_mode' => 'Stop Check',
                                        'activity_type' => 'Stop Check',
                                        'action' => 'Stop Check',
                                        'remarks' =>  'Communication received from client requesting to Stop Check.',
                                        'created_on' => date(DB_DATE_FORMAT),
                                        'created_by' => $this->user_info['id'],
                                        'is_auto_filled' => 1,
        
                                    );

                                    $field_user_activity = array('CandidateName' => $candidate_details[0]['CandidateName'],
                                        'component_ref_no' => $candidate_details[0]['cmp_ref_no']);
        
                                    $result = $this->activity_data_model->save_trigger_save($field, array('component_type' => 3), $field_user_activity);
        

                                    $field_array = array(
                                        'verfstatus' => 9,
                                        'var_filter_status' => "Closed",
                                        'var_report_status' => "Stop Check",
                                        'closuredate' => date('Y-m-d'),
                                        'remarks' => 'Communication received from client requesting to Stop Check.',
                                        'modified_on' => date(DB_DATE_FORMAT),
                                        'modified_by' => $this->user_info['id'],
                                        'activity_log_id' => $result1
                                    );
                                    $this->load->model('education_model'); 
                                    $result = $this->education_model->save_update_result(array_map('strtolower', $field_array), array('education_id' =>  $education_detail['id']));
                                    
                                    $field_result = array(
                                        'verfstatus' => 9,
                                        'candsid' => $candidate_details[0]['id'],
                                        'clientid' => $candidate_details[0]['clientid'],  
                                        'var_filter_status' => "Closed",
                                        'var_report_status' => "Stop Check",
                                        'closuredate' => date('Y-m-d'),
                                        'education_id' =>  $education_detail['id'],
                                        'remarks' => 'Communication received from client requesting to Stop Check.',
                                        'created_on' => date(DB_DATE_FORMAT),
                                        'created_by' => $this->user_info['id'],
                                        'activity_log_id' => $result1
                                    );
                       
                                    $result = $this->education_model->save_update_result_education(array_map('strtolower', $field_result));
                             
                                   /* $get_view_vendor_master_details = $this->education_model->get_client_id(array('view_vendor_master_log.final_status' => 'wip','view_vendor_master_log.component'=>'eduver','component_tbl_id' => 3,'education.id' => $education_detail['id']));
                                      
                                    if(!empty($get_view_vendor_master_details[0]))
                                    {
                                        $field_array = array(
                                            'remarks' => "Stop Check",
                                            'rejected_by' => $this->user_info['id'],
                                            'rejected_on' => date(DB_DATE_FORMAT),
                                            'status' => 5,
                                            'final_status' => "cancelled",
                                        );
                        
                                        $result_view_vendor_log = $this->education_model->save_vendor_details_cancel($field_array, array('id' => $get_view_vendor_master_details[0]['id']));
                        
                                        $field_array_education = array(
                                            'remarks' => "Stop Check",
                                            'modified_by' => $this->user_info['id'],
                                            'modified_on' => date(DB_DATE_FORMAT),
                                            'status' => 2,
                                        );
                        
                                        $education_vendor_result = $this->education_model->update_drugs_vendor_log('drug_narcotis_vendor_log', $field_array_education, array('id' =>  $get_view_vendor_master_details[0]['vendor_master_log_case']));
                           
                                    }*/
                                    
                            }
                            
                        }     
                      
                    }


                    auto_update_tat_status($candidate_details[0]['id']);

                    auto_update_overall_status($candidate_details[0]['id']);

                    
                }
                   
                $json_array['status'] = SUCCESS_CODE;
                $json_array['message'] = "Record Update Successful";

            } else {
                    $json_array['status'] = ERROR_CODE;
                    $json_array['message'] = "Please Insert Reference No";
            }
        
        echo_json($json_array);
    }

    public function check_identity_component()
    { 
        
            $json_array = array();
            if ($this->input->is_ajax_request()) {

                $frm_details = $this->input->post();

                $check_component = $this->candidates_model->check_identity_comp($frm_details['clientid'],$frm_details['entity'],$frm_details['package']);  
             
                if(!empty($check_component[0]['component_id'])){

                    if(in_array('identity',explode(',',$check_component[0]['component_id'])))
                    {
                        $candidate_component['candidate_component'] = 'present';

                        $json_array['status'] = SUCCESS_CODE;
            
                        $json_array['message'] = $candidate_component;
                    }
                    else{
                         
                        $candidate_component['candidate_component'] =  "not present";

                        $json_array['status'] = ERROR_CODE;
            
                        $json_array['message'] = $candidate_component;
                    }
                } else {
                    $json_array['status'] = ERROR_CODE;
        
                    $json_array['message'] = 'Something went wrong';
                }
            }
            
            $this->echo_json($json_array);
      
          
    }
    public function search_aadhar_card_number()
    {
        $json_array = array();
        if ($this->input->is_ajax_request()) {

            $frm_details = $this->input->post();

            if(!empty($frm_details['aadhar_pan_id']))
            {
                $candidate_id = $this->candidates_model->select_record_present_like('identity','id_number',array('candsid'),$frm_details['aadhar_pan_id']);
               
                if(!empty($candidate_id[0]['candsid']))
                {
                    $mist_details = array();
                    foreach($candidate_id as $cands_id)
                    {

                       $mist_id = $this->candidates_model->select(true,array('cmp_ref_no','CandidateName','clientid'),array('candidates_info.id'=> $cands_id['candsid']));
                       
                       $client_name =  $this->candidates_model->select_record_present('clients',array('clientname'),array('clients.id'=>$mist_id['clientid']));
                       
                       array_push($mist_details,$mist_id['cmp_ref_no']." - ".$client_name[0]['clientname']." - ".$mist_id['CandidateName']);

                    }
                   
                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = implode('<br>',$mist_details);
    
                }
                else{

                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Aadhar Card/ Pan Card Not Present';
                }
            }
            else {
                $json_array['status'] = ERROR_CODE;
    
                $json_array['message'] = 'Enter Aadhar / Pan Card Number';
            }
        }
        else {
            $json_array['status'] = ERROR_CODE;

            $json_array['message'] = 'Something went wrong';
        }

        $this->echo_json($json_array);
    }


    public function report_client($candsid = null, $report_type)
    {
        if (!empty($candsid)) {

            $id = decrypt($candsid);
            $this->load->model('first_qc_model');

            $this->load->library('example_client');

            $report = array();

            $cands_result = $this->candidates_model->get_candidates_info_info_report(array('candidates_info.id' => $id));

           
            $report['employment_info'] = array();
            $report['education_info'] = array();
            
            $report['pcc_info'] = array();
         
          

            $report['report_type'] = $report_type;

            if ($cands_result) {
                $report['cand_info'] = $cands_result;

                $NA_array = array();

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
                
                $result = $this->first_qc_model->get_pcc_final_qc(array('pcc_result.candsid' => $id));

                if (!empty($result)) {
                    $report['pcc_info'] = $result;
                } else {
                    $report['pcc_info'] = $result;
                    $NA_array[] = array('POLICE VERIFICATION');
                }

               
                $report['NA_COMPONENTS'] = $NA_array;

                $report['status'] = OVERALL_STATUS;

                $this->example_client->generate_pdf($report, 'admin');
            } else {
                show_404();
            }
        } else {
            redirect('admin/candidates');
        }
       

    }


}
