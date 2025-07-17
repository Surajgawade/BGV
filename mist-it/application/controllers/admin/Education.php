<?php defined('BASEPATH') or exit('No direct script access allowed');

class Education extends MY_Controller
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

        $this->perm_array = array('page_id' => 6);
        if ($this->input->is_ajax_request()) {
            $this->perm_array = array('direct_access' => true);

        }

        $this->assign_options = array('0' => 'Select Executive', '1' => 'Assign to Executive');
        $this->assign_options_vendor = array('0' => 'Select Vendor', '1' => 'Assign to Vendor');
        $this->load->model(array('education_model'));
    }

    public function index()
    {
        $data['header_title'] = "Education Verificatiion Lists";

        $data['filter_view'] = $this->filter_view();
        $data['users_list'] = $this->education_model->get_assign_users('user_profile', false, array("`user_profile`.`status`,`user_profile`.`id`,`user_profile`.`email`,`user_profile`.`department`,`user_profile`.`user_name`,concat(`user_profile`.`firstname`,' ',`user_profile`.`lastname`) as fullname,`user_profile`.`profile_pic`,`user_profile`.`firstname`,`user_profile`.`lastname`,`roles`.`role_name`,`roles`.`groups_id`,`roles_permissions`.*"), array('user_profile.status' => STATUS_ACTIVE));
        // $data['users_list'] = $this->users_list();
        $data['vendor_list'] = $this->vendor_list('eduver');

        $data['clients_id'] = $this->get_clients(array('status' => STATUS_ACTIVE));

        $data['status_value'] = $this->get_status();

        $this->load->view('admin/header', $data);

        $this->load->view('admin/education_list');

        $this->load->view('admin/footer');
    }

    public function get_clients_for_education_list_view()
    {
        $params = $_REQUEST;

        $clients = $this->education_model->select_client_list_view_education('clients', false, array('clients.id', 'clients.clientname'), $params);

        $clients_arry[0] = 'Select Client';

        foreach ($clients as $key => $value) {
            $clients_arry[$value['id']] = ucwords(strtolower($value['clientname']));
        }

        echo_json(array('client_list' => $clients_arry));

    }

    public function education_view_datatable()
    {
        $params = $details = $data_arry = $columns = array();

        $params = $_REQUEST;

        $columns = array('education.id', 'ClientRefNumber', 'cmp_ref_no', 'CandidateName', 'caserecddate', 'last_modified', 'encry_id', 'verfstatus', 'verification_address', 'additional_comment', 'verifiername', 'verification_date', 'modeofverification', 'closuredate', 'remarks', 'reiniated_date');

        $details = $this->education_model->get_all_education_record_datatable(false, $params, $columns);

        $totalRecords = count($this->education_model->get_all_education_record_datatable_count(false, $params, $columns));

        $x = 0;

        foreach ($details as $detail) {

            if($detail['vendor_stamp_name'] != "")
            {
              $vendor_name = $detail['vendor_stamp_name'];  
            }
            else
            {
              $vendor_name = $detail['vendor_name'];     
            }
      

            $data_arry[$x]['id'] = $x + 1;
            //$data_arry[$x]['checkbox'] = $detail['id'];
            $data_arry[$x]['ClientRefNumber'] = $detail['ClientRefNumber'];
            $data_arry[$x]['cmp_ref_no'] = $detail['cmp_ref_no'];
            $data_arry[$x]['CandidateName'] = $detail['CandidateName'];
            $data_arry[$x]['education_com_ref'] = $detail['education_com_ref'];
            $data_arry[$x]['iniated_date'] = convert_db_to_display_date($detail['iniated_date']);
            $data_arry[$x]['clientname'] = $detail['clientname'];
            $data_arry[$x]['vendor_name'] = $vendor_name;
            $data_arry[$x]['verfstatus'] = $detail['status_value'];
            $data_arry[$x]['school_college'] = $detail['school_college'];
            $data_arry[$x]['university_board'] = $detail['university_board'];
            $data_arry[$x]['qualification'] = $detail['qualification'];
            $data_arry[$x]['year_of_passing'] = $detail['year_of_passing'];
            $data_arry[$x]['executive_name'] = $detail['executive_name'];
            $data_arry[$x]['caserecddate'] = convert_db_to_display_date($detail['caserecddate']);
            $data_arry[$x]['last_activity_date'] = convert_db_to_display_date($detail['last_activity_date'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);
            $data_arry[$x]['closuredate'] = convert_db_to_display_date($detail['closuredate']);
            $data_arry[$x]['due_date'] = convert_db_to_display_date($detail['due_date']);
            $data_arry[$x]['tat_status'] = $detail['tat_status'];
            $data_arry[$x]['encry_id'] = ADMIN_SITE_URL . "education/view_details/" . encrypt($detail['id']);
            $data_arry[$x]['mode_of_veri'] = $detail['mode_of_veri'];
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

    public function add($candsid)
    {
        if ($this->input->is_ajax_request()) {
            $data['get_cands_details'] = $this->candidate_entity_pack_details($candsid);

            $data['mode_of_verification'] = $this->education_model->get_mode_of_verification(array('entity' => $data['get_cands_details']['entity_id'], 'package' => $data['get_cands_details']['package_id'], 'tbl_clients_id' => $data['get_cands_details']['clientid']));

            $data['states'] = $this->get_states();
            //$data['assigned_user_id'] = $this->users_list();

            $data['universityname'] = $this->university_list();

            $data['qualification_name'] = $this->qualification_list();

            //$data['assigned_user_id'] = $this->education_model->get_assign_users_id('user_profile', false, array("`user_profile`.`status`,`user_profile`.`id`,`user_profile`.`email`,`user_profile`.`department`,`user_profile`.`user_name`,concat(`user_profile`.`firstname`,' ',`user_profile`.`lastname`) as fullname,`user_profile`.`profile_pic`,`user_profile`.`firstname`,`user_profile`.`lastname`,`roles`.`role_name`,`roles`.`groups_id`,`roles_permissions`.*"), array('user_profile.status' => STATUS_ACTIVE));

            $data['assigned_user_id']  = $this->get_reporting_manager_for_execituve($data['get_cands_details']['clientid']);

            echo $this->load->view('admin/education_add', $data, true);
        }
    }

    /* protected function education_com_ref()
    {
    $lists = $this->education_model->education_com_ref();

    $number =  (!empty($lists)) ? $lists['A_I']+1 : 1000;

    return COMPONENT_REF_NO['EDUCATION'].$number;
    }

    protected function bulk_education_com_ref()
    {
    $lists = $this->education_model->education_com_ref();

    return  (!empty($lists)) ? $lists['A_I']+1 : 1000;
    }*/
    public function save_university()
    {
        if ($this->input->is_ajax_request()) {
            $university_name = $this->input->post('university_name');

            $this->form_validation->set_rules('university_name', 'University Name', 'required|is_unique[university_master.universityname]');

            if ($this->form_validation->run() == false) {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');
            } else {
                $frm_details = $this->input->post();

                $field_array = array("universityname" => $frm_details["university_name"],
                    'created_on' => date(DB_DATE_FORMAT),
                    'created_by' => $this->user_info['id'],
                    'status' => STATUS_ACTIVE,
                );

                $field_array = array_map('ucwords', $field_array);
                $insert_id = $this->education_model->save_education($field_array);
                if ($insert_id) {
                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'Added Successfully';

                    $json_array['insert_id'] = $insert_id;
                    $json_array['university_name'] = $university_name;

                    $json_array['redirect'] = ADMIN_SITE_URL . 'education';
                } else {
                    $json_array['insert_id'] = 0;

                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Something went wrong,please try again';
                }
            }
            echo_json($json_array);
        } else {
            permission_denied();
        }
    }

    public function save_qualification()
    {
        if ($this->input->is_ajax_request()) {
            $qualification_name = $this->input->post('qualification_name');

            $this->form_validation->set_rules('qualification_name', 'Qualification Name', 'required|is_unique[qualification_master.qualification]');

            if ($this->form_validation->run() == false) {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');
            } else {
                $frm_details = $this->input->post();

                $field_array = array("qualification" => $frm_details["qualification_name"],
                    'created_on' => date(DB_DATE_FORMAT),
                    'created_by' => $this->user_info['id'],
                );

                $field_array = array_map('ucwords', $field_array);
                $insert_id = $this->education_model->save_qualification($field_array);
                if ($insert_id) {
                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'Added Successfully';

                    $json_array['insert_id'] = $insert_id;
                    $json_array['qualification_name'] = $qualification_name;

                    $json_array['redirect'] = ADMIN_SITE_URL . 'education';
                } else {
                    $json_array['insert_id'] = 0;

                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Something went wrong,please try again';
                }
            }
            echo_json($json_array);
        } else {
            permission_denied();
        }
    }

    protected function education_com_ref($insert_id)
    {

        $name = COMPONENT_REF_NO['EDUCATION'];

        $educationnumber = $name . $insert_id;

        $field_array = array('education_com_ref' => $educationnumber);

        $update_auto_increament_id = $this->education_model->update_auto_increament_value($field_array, array('id' => $insert_id));

        return $educationnumber;
    }

    public function save_form()
    {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('clientid', 'Client', 'required');

            // $this->form_validation->set_rules('education_com_ref', 'Component Ref','required');

            $this->form_validation->set_rules('candsid', 'Candidate', 'required');

            $this->form_validation->set_rules('university_board', 'University', 'required');

            if ($this->form_validation->run() == false) {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');
            } else {
                $frm_details = $this->input->post();

          
                $file_upload_path = SITE_BASE_PATH . EDUCATION . $frm_details['clientid'];
                if (!folder_exist($file_upload_path)) {
                    mkdir($file_upload_path, 0777);
                } else if (!is_writable($file_upload_path)) {
                    array_push($error_msgs, 'Problem while uploading');
                }

               
                $tat_day = $this->get_client_entity_package_tat_day(array('tbl_clients_id' => $frm_details['clientid'], 'entity' => $frm_details['entity_id'], 'package' => $frm_details['package_id']));

                $get_holiday1 = $this->get_holiday();

                $get_holiday = array_map('current', $get_holiday1);

                $closed_date = getWorkingDays(convert_display_to_db_date($frm_details['iniated_date']), $get_holiday, $tat_day[0]['tat_eduver']);

                $field_array = array('clientid' => $frm_details['clientid'],
                    'candsid' => $frm_details['candsid'],
                    'education_com_ref' => '',
                    'iniated_date' => convert_display_to_db_date($frm_details['iniated_date']),
                    'school_college' => $frm_details['school_college'],
                    'university_board' => $frm_details['university_board'],
                    'grade_class_marks' => $frm_details['grade_class_marks'],
                    'qualification' => $frm_details['qualification'],
                    'major' => $frm_details['major'],
                    'course_start_date' => convert_display_to_db_date($frm_details['course_start_date']),
                    'course_end_date' => convert_display_to_db_date($frm_details['course_end_date']),
                    'month_of_passing' => $frm_details['month_of_passing'],
                    'year_of_passing' => $frm_details['year_of_passing'],
                    'roll_no' => $frm_details['roll_no'],
                    'enrollment_no' => $frm_details['enrollment_no'],
                    'PRN_no' => $frm_details['PRN_no'],
                    'documents_provided' => json_encode($frm_details['documents_provided']),
                    'city' => $frm_details['city'],
                    'state' => $frm_details['state'],
                    'mode_of_veri' => $frm_details['mode_of_veri'],
                    //'genuineness' => $frm_details['genuineness'],
                    //'online_URL' => $frm_details['online_URL'],
                    'city' => $frm_details['city'],
                    'state' => $frm_details['state'],
                    'created_by' => $this->user_info['id'],
                    'created_on' => date(DB_DATE_FORMAT),
                    'modified_on' => date(DB_DATE_FORMAT),
                    'modified_by' => $this->user_info['id'],
                    'has_case_id' => $frm_details['has_case_id'],
                    'has_assigned_on' => date(DB_DATE_FORMAT),
                    'is_bulk_uploaded' => 0,
                    "due_date" => $closed_date,
                    "tat_status" => "IN TAT",
                );

                $result = $this->education_model->save($field_array);

                $education_com_ref = $this->education_com_ref($result);


                $config_array = array(
                    'file_upload_path' => $file_upload_path, 'file_permission' => 'jpeg|jpg|png|pdf|docx|xls|xlsx', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 1000, 'file_id' => $result, 'component_name' => 'education_id');
                if (empty($error_msgs)) {
                    if ($_FILES['attchments']['name'][0] != '') {
                        $config_array['files_count'] = count($_FILES['attchments']['name']);
                        $config_array['file_data'] = $_FILES['attchments'];
                        $config_array['type'] = 0;
                        $config_array['education_id'] =  $education_com_ref ;
                        $retunr_de = $this->file_upload_library->file_upload_multiple_education($config_array, true);
                      //  $retunr_de = $this->file_upload_multiple($config_array);
                        if (!empty($retunr_de)) {
                            $this->common_model->common_insert_batch('education_files', $retunr_de['success']);
                        }
                    }

                   

                   /* if (isset($frm_details['upload_capture_image_education'])) {

                        if ($frm_details['upload_capture_image_education']) {

                            $upload_capture_image = explode("||", $frm_details['upload_capture_image_education']);
                                    
                            foreach ($upload_capture_image as $key => $value) {
                                $key = $key + 1;

                                $file_name = $education_com_ref . '-' .$key. '-' . date(UPLOAD_FILE_DATE_FORMAT) . '.png';
                                $uploadpath = $file_upload_path . '/' . $file_name;
                                $base64_to_jpeg = base64_to_jpeg($value, $uploadpath);
                                    if ($base64_to_jpeg) {
                                        log_message('error', 'Inside if condition success');
                                        $this->common_model->save('education_files', ['file_name' => $file_name, 'real_filename' => $file_name,'status' => 1,'type' => 0,'education_id' => $result]);
                                    }

                            }
                        }   
                    }*/

                }

                if ($result) {
                    $user_activity_data = $this->common_model->user_actity_data(array('component' => "Education",
                        'ref_no' => $education_com_ref, 'candidate_name' => $frm_details['CandidateName'], 'created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id'], 'activity_type' => 'Manual', 'action' => 'Add'));

                    auto_update_overall_status($frm_details['candsid']);
                    auto_update_tat_status($frm_details['candsid']);

                    if($frm_details['university_board'] != "2476")
                    {

                        $select_vendor_details = $this->education_model->select_education('university_master', array('*'),array('university_master.id'=>$frm_details['university_board']));

                        if(!empty($select_vendor_details[0]['vendor_id']) && !empty($select_vendor_details[0]['year_of_passing']))
                        {
                          
                            if(($frm_details['year_of_passing'] >= $select_vendor_details[0]['year_of_passing']) && ($select_vendor_details[0]['id'] == $frm_details['university_board']))
                            {    

                                $update = $this->education_model->upload_vendor_assign('education', array('vendor_id' => $select_vendor_details[0]['vendor_id'],'verifiers_spoc_status' => 2,  'vendor_assgined_on' => date(DB_DATE_FORMAT)), array('id' => $result, 'vendor_id =' => 0));

                                $update1 = $this->education_model->update_status('education_result', array('verfstatus' => 1), array('education_id' => $result));

                                $get_username = $this->education_model->get_reporting_manager_id();
                                $get_vendorname = $this->education_model->get_vendor_details(array('id'=>$select_vendor_details[0]['vendor_id']));
                                if ($update) {

                                    $files[] = array(
                                        'vendor_id' => $select_vendor_details[0]['vendor_id'],
                                        'case_id' => $result,
                                        "status" => 0,
                                        "remarks" => '',
                                        "created_by" => $this->user_info['id'],
                                        "created_on" => date(DB_DATE_FORMAT),
                                        "approval_by" => $this->user_info['id'],
                                        "modified_on" =>  date(DB_DATE_FORMAT),
                                        "modified_by" => $this->user_info['id'],
                                    );

                                    $activity[]  =  array(
                                        'comp_table_id' => $result,
                                        'activity_status' => 'Assign',
                                        "activity_type" => 'Mist it',
                                        "remarks" => $get_username[0]['user_name'].' has assigned the case to '.$get_vendorname[0]['vendor_name'],
                                        "created_by" => $this->user_info['id'],
                                        "created_on" => date(DB_DATE_FORMAT),
                                        "is_auto_filled" => 0
                                                
                                    );
                                }
                               
                            }
                            else{

                                $update = $this->education_model->upload_vendor_assign('education', array('vendor_id' => 20,'verifiers_spoc_status' => 1,  'vendor_assgined_on' => date(DB_DATE_FORMAT)), array('id' => $result, 'vendor_id =' => 0));

                                $update1 = $this->education_model->update_status('education_result', array('verfstatus' => 1), array('education_id' => $result));

                                $get_username = $this->education_model->get_reporting_manager_id();
                                $get_vendorname = $this->education_model->get_vendor_details(array('id'=>20));
                                if ($update) {

                                    $files[] = array(
                                        'vendor_id' => 20,
                                        'case_id' => $result,
                                        "status" => 0,
                                        "remarks" => '',
                                        "created_by" => $this->user_info['id'],
                                        "created_on" => date(DB_DATE_FORMAT),
                                        "approval_by" => 0,
                                        "modified_on" => null,
                                        "modified_by" => '',
                                    );

                                    $activity[]  =  array(
                                        'comp_table_id' => $result,
                                        'activity_status' => 'Assign',
                                        "activity_type" => 'Mist it',
                                        "remarks" => $get_username[0]['user_name'].' has assigned the case to '.$get_vendorname[0]['vendor_name'],
                                        "created_by" => $this->user_info['id'],
                                        "created_on" => date(DB_DATE_FORMAT),
                                        "is_auto_filled" => 0
                                                
                                    );
                                }
                      
                            }
                        }
                        else{

                                $update = $this->education_model->upload_vendor_assign('education', array('vendor_id' => 20,'verifiers_spoc_status' => 1,  'vendor_assgined_on' => date(DB_DATE_FORMAT)), array('id' => $result, 'vendor_id =' => 0));

                                $update1 = $this->education_model->update_status('education_result', array('verfstatus' => 1), array('education_id' => $result));

                                $get_username = $this->education_model->get_reporting_manager_id();
                                $get_vendorname = $this->education_model->get_vendor_details(array('id'=>20));
                                if ($update) {

                                    $files[] = array(
                                        'vendor_id' => 20,
                                        'case_id' => $result,
                                        "status" => 0,
                                        "remarks" => '',
                                        "created_by" => $this->user_info['id'],
                                        "created_on" => date(DB_DATE_FORMAT),
                                        "approval_by" => 0,
                                        "modified_on" => null,
                                        "modified_by" => '',
                                    );

                                    $activity[]  =  array(
                                        'comp_table_id' => $result,
                                        'activity_status' => 'Assign',
                                        "activity_type" => 'Mist it',
                                        "remarks" => $get_username[0]['user_name'].' has assigned the case to '.$get_vendorname[0]['vendor_name'],
                                        "created_by" => $this->user_info['id'],
                                        "created_on" => date(DB_DATE_FORMAT),
                                        "is_auto_filled" => 0
                                                
                                    );
                                }
                      
                        }

                        if (!empty($files)) {
                            $inserted = $this->common_model->common_insert_batch('education_vendor_log', $files);
                            $activity_insert = $this->common_model->common_insert_batch('education_activity_data', $activity);
                        }
                    }
                  
                    $json_array['status'] = SUCCESS_CODE;
                    $json_array['message'] = 'Education Record Successfully Inserted';
                    $json_array['redirect'] = ADMIN_SITE_URL . 'education';

                
                } else {
                    $json_array['status'] = ERROR_CODE;
                    $json_array['message'] = 'Something went wrong,please try again';
                }
                echo_json($json_array);
            }
        }
    }

    public function get_assign_education_list_view()
    {
        log_message('error', 'Education List Assign in Assign View');
        try {

            $data['header_title'] = "Education Verification";

            $data['filter_view'] = $this->filter_view_assign_education_list();
            $data['users_list'] = $this->users_list();

        } catch (Exception $e) {

            log_message('error', 'Education::get_assign_education_list_view');
            log_message('error', $e->getMessage());
        }

        echo $this->load->view('admin/assign_edu', $data, true);
    }

    protected function filter_view_assign_education_list($true = false)
    {
        if ($true) {
            $data['status'] = $this->get_status_candidate();
        } else {
            $data['status'] = $this->get_status();
        }
        $data['clients'] = $this->get_clients_for_list_view();
        $data['user_list_name'] = $this->users_list_filter();

        return $this->load->view('admin/filter_view_assign', $data, true);
    }

    public function get_clients_for_list_view()
    {
        $this->load->model('assign_edu_model');

        $clients = $this->assign_edu_model->select_client_list_assign_edu_view('clients', false, array('clients.id', 'clients.clientname'));

        $clients_arry[0] = 'Select Client';

        foreach ($clients as $key => $value) {
            $clients_arry[$value['id']] = ucwords(strtolower($value['clientname']));
        }
        return $clients_arry;
    }

    public function update_form()
    {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('education_id', 'ID', 'required');

            $this->form_validation->set_rules('has_case_id', 'Assigned To Executive', 'required');

            $this->form_validation->set_rules('clientid', 'Client', 'required');

            $this->form_validation->set_rules('education_com_ref', 'Component Ref', 'required');

            $this->form_validation->set_rules('candsid', 'Candidate', 'required');

            $this->form_validation->set_rules('university_board', 'University', 'required');

            if ($this->form_validation->run() == false) {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');
            } else {
                $frm_details = $this->input->post();

                $file_upload_path = SITE_BASE_PATH . EDUCATION . $frm_details['clientid'];
                if (!folder_exist($file_upload_path)) {
                    mkdir($file_upload_path, 0777);
                } else if (!is_writable($file_upload_path)) {
                    array_push($error_msgs, 'Problem while uploading');
                }

                $tat_day = $this->get_client_entity_package_tat_day(array('tbl_clients_id' => $frm_details['clientid'], 'entity' => $frm_details['entity_id'], 'package' => $frm_details['package_id']));

                $get_holiday1 = $this->get_holiday();

                $get_holiday = array_map('current', $get_holiday1);

                $closed_date = getWorkingDays(convert_display_to_db_date($frm_details['iniated_date']), $get_holiday, $tat_day[0]['tat_eduver']);

                $field_array = array('clientid' => $frm_details['clientid'],
                    'candsid' => $frm_details['candsid'],
                    'education_com_ref' => $frm_details['education_com_ref'],
                    'iniated_date' => convert_display_to_db_date($frm_details['iniated_date']),
                    'school_college' => $frm_details['school_college'],
                    'university_board' => $frm_details['university_board'],
                    'grade_class_marks' => $frm_details['grade_class_marks'],
                    'qualification' => $frm_details['qualification'],
                    'major' => $frm_details['major'],
                    'course_start_date' => convert_display_to_db_date($frm_details['course_start_date']),
                    'course_end_date' => convert_display_to_db_date($frm_details['course_end_date']),
                    'month_of_passing' => $frm_details['month_of_passing'],
                    'year_of_passing' => $frm_details['year_of_passing'],
                    'roll_no' => $frm_details['roll_no'],
                    'enrollment_no' => $frm_details['enrollment_no'],
                    'PRN_no' => $frm_details['PRN_no'],
                    'build_date' => $frm_details['build_date'],
                    'documents_provided' => json_encode($frm_details['documents_provided']),
                    'city' => $frm_details['city'],
                    'state' => $frm_details['state'],
                    'mode_of_veri' => $frm_details['mode_of_veri'],
                    'genuineness' => $frm_details['genuineness'],
                    'online_URL' => $frm_details['online_URL'],
                    'city' => $frm_details['city'],
                    'state' => $frm_details['state'],
                    'modified_on' => date(DB_DATE_FORMAT),
                    'modified_by' => $this->user_info['id'],
                    'has_case_id' => $frm_details['has_case_id'],
                    'has_assigned_on' => date(DB_DATE_FORMAT),
                    'is_bulk_uploaded' => 0,
                    "due_date" => $closed_date,
                    "tat_status" => "IN TAT",
                );

                $result = $this->education_model->save($field_array, array('id' => $frm_details['education_id']));

                $select_candidate_billed_date = $this->common_model->select_candidate_billed_date('candidates_info', true, array('build_date'), array('id' => $frm_details['candsid']));

                $component_name = json_decode($select_candidate_billed_date['build_date'], true);

                $result_candidate_billed = $this->common_model->update_candidate_billed_date(array('build_date' => $this->components_key_val(array('0' => $component_name['addrver'], '1' => $component_name['courtver'], '2' => $component_name['globdbver'], '3' => $component_name['narcver'], '4' => $component_name['refver'], '5' => $component_name['empver'], '6' => $frm_details['build_date'], '7' => $component_name['identity'], '8' => $component_name['cbrver'], '9' => $component_name['crimver']))), array('id' => $frm_details['candsid']));

                $config_array = array('file_upload_path' => $file_upload_path, 'file_permission' => 'jpeg|jpg|png|pdf|docx|xls|xlsx', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 1000, 'file_id' => $frm_details['education_id'], 'component_name' => 'education_id');
                if (empty($error_msgs)) {
                    if ($_FILES['attchments']['name'][0] != '') {
                        $config_array['files_count'] = count($_FILES['attchments']['name']);
                        $config_array['file_data'] = $_FILES['attchments'];
                        $config_array['type'] = 0;
                        $config_array['education_id'] =  $frm_details['education_com_ref'] ;
                        $retunr_de = $this->file_upload_library->file_upload_multiple_education($config_array, true);

                        if (!empty($retunr_de)) {
                            $this->common_model->common_insert_batch('education_files', $retunr_de['success']);
                        }
                    }

                

                   /* if (isset($frm_details['upload_capture_image_education'])) {

                        if ($frm_details['upload_capture_image_education']) {

                            $upload_capture_image = explode("||", $frm_details['upload_capture_image_education']);
                                    
                            foreach ($upload_capture_image as $key => $value) {
                                $key = $key + 1;

                                $file_name = $frm_details['education_com_ref'] . '-' .$key. '-' . date(UPLOAD_FILE_DATE_FORMAT) . '.png';
                                $uploadpath = $file_upload_path . '/' . $file_name;
                                $base64_to_jpeg = base64_to_jpeg($value, $uploadpath);
                                    if ($base64_to_jpeg) {
                                        log_message('error', 'Inside if condition success');
                                        $this->common_model->save('education_files', ['file_name' => $file_name, 'real_filename' => $file_name,'status' => 1,'type' => 0,'education_id' => $frm_details['education_id']]);
                                    }

                            }
                        }
                    }*/
                }

                if ($result) {

                    $user_activity_data = $this->common_model->user_actity_data(array('component' => "Education",
                        'ref_no' => $frm_details['education_com_ref'], 'candidate_name' => $frm_details['CandidateName'], 'created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id'], 'activity_type' => 'Manual', 'action' => 'Edit'));

                    auto_update_tat_status($frm_details['candsid']);

                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'Record Successfully Updated';

                    $json_array['redirect'] = ADMIN_SITE_URL . 'education';
                } else {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Something went wrong,please try again';
                }
                echo_json($json_array);
            }
        }
    }
    public function university_dropdown()
    {
        if ($this->input->is_ajax_request()) {
            $university_list = $this->university_list_dropdown();
            echo_json(array('university_list' => $university_list));
        }
    }

    public function qualification_dropdown()
    {
        if ($this->input->is_ajax_request()) {
            $university_list = $this->qualification_list_dropdown();
            echo_json(array('qualification_list' => $university_list));
        }
    }

    public function university_list_dropdown()
    {
        $this->load->model('university_model');

        $results = $this->university_model->select(false, array('universityname', 'id'), array());
        $university_arry = array();

        $university_arry[0] = 'Select University';

        foreach ($results as $key => $value) {
            $university_arry[$value['id']] = $value['universityname'];
        }

        return $university_arry;
    }

    public function qualification_list_dropdown()
    {
        $this->load->model('qualification_model');

        $results = $this->qualification_model->select(false, array('qualification', 'id'), array());

        $university_arry = array();

        $university_arry[0] = 'Select Qualification';

        foreach ($results as $key => $value) {
            $university_arry[$value['id']] = ucwords($value['qualification']);
        }

        return $university_arry;
    }

    public function university_list()
    {
        $this->load->model('university_model');

        $results = $this->university_model->select(false, array('universityname', 'id'), array('status'=> STATUS_ACTIVE));
        $university_arry = array();

        $university_arry[0] = 'Select University';

        foreach ($results as $key => $value) {
            $university_arry[$value['id']] = $value['universityname'];
        }

        return $university_arry;
    }

    public function qualification_list()
    {
        $this->load->model('qualification_model');

        $results = $this->qualification_model->select(false, array('qualification', 'id'), array());

        $university_arry = array();

        $university_arry[0] = 'Select Qualification';

        foreach ($results as $key => $value) {
            $university_arry[$value['id']] = ucwords($value['qualification']);
        }

        return $university_arry;
    }



    public function view_from_inside($education_com_ref = false)
    {
        if ($this->input->is_ajax_request()) {

            $cget_details = $this->education_model->get_all_education_record(array('education.education_com_ref' => $education_com_ref));

            if (!empty($cget_details)) {

                if($cget_details[0]['has_case_id'] == $this->user_info['id'])
                {
                    $data['bcc_email_id'] =  $this->user_info['email'].','.FROMEMAIL;
                }
                else
                {
                    $email = $this->education_model->get_user_email_id(array('user_profile.id' => $cget_details[0]['has_case_id']));

                    $data['bcc_email_id']  =  $this->user_info['email'].','.$email.','.FROMEMAIL;
                }

                
                $data['reinitiated'] = ($cget_details[0]['var_filter_status'] == 'Closed' || $cget_details[0]['var_filter_status'] == 'closed') ? '1' : '2';


                $data['get_cands_details'] = $this->candidate_entity_pack_details($cget_details[0]['cands_id']);

                $data['mode_of_verification'] = $this->education_model->get_mode_of_verification(array('entity' => $data['get_cands_details']['entity_id'], 'package' => $data['get_cands_details']['package_id'], 'tbl_clients_id' => $data['get_cands_details']['clientid']));

                $data['selected_data'] = $cget_details[0];
                
                $data['states'] = $this->get_states();
               
               // $data['assigned_user_id'] = $this->education_model->get_assign_users_id('user_profile', false, array("`user_profile`.`status`,`user_profile`.`id`,`user_profile`.`email`,`user_profile`.`department`,`user_profile`.`user_name`,concat(`user_profile`.`firstname`,' ',`user_profile`.`lastname`) as fullname,`user_profile`.`profile_pic`,`user_profile`.`firstname`,`user_profile`.`lastname`,`roles`.`role_name`,`roles`.`groups_id`,`roles_permissions`.*"), array('user_profile.status' => STATUS_ACTIVE));


                $data['assigned_user_id']  = $this->get_reporting_manager_for_execituve($cget_details[0]['clientid']);


                $data['attachments'] = $this->education_model->select_file(array('id', 'file_name', 'real_filename', 'type'), array('education_id' => $cget_details[0]['education_id'], 'status' => 1));

                $check_insuff_raise = $this->education_model->select_insuff(array('education_id' => $cget_details[0]['education_id'], 'status' => STATUS_ACTIVE, 'insuff_clear_date is null' => null));

                $data['insuff_reason_list'] = $this->education_model->insuff_reason_list(false, array('component_id' => 3));

                $data['insuff_raise_message'] = (!empty($check_insuff_raise) ? 'Insufficiency raised on ' . convert_db_to_display_date($check_insuff_raise[0]['insuff_raised_date']) . ' for ' . $check_insuff_raise[0]['insff_reason'] : '');

                $data['check_insuff_value'] = ((!empty($check_insuff_raise)) ? '1' : '2');

                $data['check_insuff_raise'] = ((!empty($check_insuff_raise)) ? 'disabled' : '');
                $data['insuff_raise_id'] = (!empty($check_insuff_raise) ? $check_insuff_raise[0]['id'] : '');

                echo $this->load->view('admin/education_edit_view_inside', $data, true);
            } else {
                echo "<h3>Something went wrong, please try again</h3>";
            }
        }
    }

    public function view_details($id = '')
    {
        if (!empty($id)) {
            $details = $this->education_model->get_all_education_record(array('education.id' => decrypt($id)));

            if ($id && !empty($details)) {
                $data['header_title'] = 'Education Verification';

                $data['get_cands_details'] = $this->candidate_entity_pack_details($details[0]['cands_id']);
                $data['states'] = $this->get_states();
                $data['selected_data'] = $details[0];
                // $data['assigned_user_id'] = $this->users_list();

               // $data['assigned_user_id'] = $this->education_model->get_assign_users_id('user_profile', false, array("`user_profile`.`status`,`user_profile`.`id`,`user_profile`.`email`,`user_profile`.`department`,`user_profile`.`user_name`,concat(`user_profile`.`firstname`,' ',`user_profile`.`lastname`) as fullname,`user_profile`.`profile_pic`,`user_profile`.`firstname`,`user_profile`.`lastname`,`roles`.`role_name`,`roles`.`groups_id`,`roles_permissions`.*"), array('user_profile.status' => STATUS_ACTIVE));

               $data['assigned_user_id']  = $this->get_reporting_manager_for_execituve($details[0]['clientid']);

                $data['mode_of_verification'] = $this->education_model->get_mode_of_verification(array('entity' => $data['get_cands_details']['entity_id'], 'package' => $data['get_cands_details']['package_id'], 'tbl_clients_id' => $data['get_cands_details']['clientid']));

                if($details[0]['has_case_id'] == $this->user_info['id'])
                {
                    $data['bcc_email_id'] =  $this->user_info['email'].','.FROMEMAIL;
                }
                else
                {
                    $email = $this->education_model->get_user_email_id(array('user_profile.id' => $details[0]['has_case_id']));

                    $data['bcc_email_id']  =  $this->user_info['email'].','.$email.','.FROMEMAIL;
                }

                
                $data['reinitiated'] = ($details[0]['var_filter_status'] == 'Closed' || $details[0]['var_filter_status'] == 'closed') ? '1' : '2';

                $check_insuff_raise = $this->education_model->select_insuff(array('education_id' => decrypt($id), 'status' => STATUS_ACTIVE, 'insuff_clear_date is null' => null));

                $data['attachments'] = $this->education_model->select_file(array('id', 'file_name', 'real_filename', 'type'), array('education_id' => decrypt($id), 'status' => 1));

                $data['insuff_reason_list'] = $this->education_model->insuff_reason_list(false, array('component_id' => 3));

                $data['insuff_raise_message'] = (!empty($check_insuff_raise) ? 'Insufficiency raised on ' . convert_db_to_display_date($check_insuff_raise[0]['insuff_raised_date']) . ' for ' . $check_insuff_raise[0]['insff_reason'] : '');

                $data['check_insuff_value'] = ((!empty($check_insuff_raise)) ? '1' : '2');

                $data['check_insuff_raise'] = ((!empty($check_insuff_raise)) ? 'disabled' : '');
                $data['insuff_raise_id'] = (!empty($check_insuff_raise) ? $check_insuff_raise[0]['id'] : '');

                $this->load->view('admin/header', $data);

                $this->load->view('admin/education_edit_view');

                $this->load->view('admin/footer');
            } else {
                show_404();
            }
        } else {
            $this->index();
        }
    }

    public function insuff_raised()
    {
        $this->load->library('email');
        $json_array = array();
        $json_array['status'] = ERROR_CODE;
        $json_array['message'] = 'Something went wrong,please try again';

        if ($this->input->is_ajax_request()) {
           
            $education_id = $this->input->post('update_id');

            $insff_reason = $this->input->post('insff_reason');

            $insff_date = $this->input->post('txt_insuff_raise');

            $ref_no = $this->input->post('component_ref_no');

            $CandidateName = $this->input->post('CandidateName');

            $check = $this->education_model->select_insuff(array('education_id' => $education_id, 'status !=' => 3, 'insuff_clear_date is null' => null));

            if (empty($check)) {
                $result = $this->education_model->save_update_insuff(array('insuff_raised_date' => convert_display_to_db_date($insff_date), 'insuff_raise_remark' => $this->input->post('insuff_raise_remark'), 'status' => STATUS_ACTIVE, 'insff_reason' => $insff_reason, 'created_by' => $this->user_info['id'], 'education_id' => $education_id, 'auto_stamp' => 1));

                if ($result) {


                        $case_activity = $this->common_model->get_case_activity_status(array('entity' => $this->input->post('entity_id'), 'package' => $this->input->post('package_id'), 'tbl_clients_id' => $this->input->post('clientid')));

                        if(!empty($case_activity)){

                            if($case_activity[0]['case_activity'] == "1"){

                                $education_details = $this->education_model->get_education_details_for_insuff_mail(array('education.id' => $education_id));

                                $users_id  = $this->get_reporting_manager_for_executive($this->input->post('clientid')); 
                                $email = $this->education_model->get_user_email_id(array('user_profile.id' => $users_id[0]['id']));

                                $spoc_email_id = $this->common_model->select_spoc_mail_id($this->input->post('clientid'));

                                $subject = 'Insufficiency raised in Education for '.ucwords($this->input->post('CandidateName'));
                                $message = "<p>Team,</p><p>Insufficiency has been raised for the following reason.</p>";


                                $message .= "<table border = '2'  style='border-spacing: 10; border-collapse: collapse;'>
                                <tr>
                                    <td style='text-align:center;background-color: #EDEDED;'>Component</td>
                                    <td style='text-align:center'>Education</td>
                                </tr>
                               <tr>
                                    <td style='text-align:center;background-color: #EDEDED;'>Candidate Name</td>
                                    <td style='text-align:center'>".ucwords($this->input->post('CandidateName'))."</td>
                                </tr>
                                <tr>
                                    <td style='text-align:center;background-color: #EDEDED;'>Entity</td>
                                    <td style='text-align:center'>".ucwords( $education_details[0]['entity_name'] )."</td>
                                </tr>
                                <tr>
                                    <td style='text-align:center;background-color: #EDEDED;'>Spoc/Package</td>
                                    <td style='text-align:center'>".ucwords( $education_details[0]['package_name'])."</td>
                                </tr>
                                <tr>
                                    <td style='text-align:center;background-color: #EDEDED;'>Client Ref No</td>
                                    <td style='text-align:center'>".$education_details[0]['ClientRefNumber']."</td>
                                </tr>
                                <tr>
                                    <td style='text-align:center;background-color: #EDEDED;'>Mist Ref No</td>
                                    <td style='text-align:center'>".$education_details[0]['cmp_ref_no']."</td>
                                </tr>
                                <tr>
                                    <td style='text-align:center;background-color: #EDEDED;'>Raised Date</td>
                                    <td style='text-align:center'>".$insff_date."</td>
                                </tr>
                                <tr>
                                    <td style='text-align:center;background-color: #EDEDED;'>Details</td>
                                    <td style='text-align:center'>".ucwords($education_details[0]['university_name'])."</td>
                                </tr>
                                <tr>
                                    <td style='text-align:center;background-color: #EDEDED;'>Remarks</td>
                                    <td style='text-align:center'>".$this->input->post('insuff_raise_remark')."</td>
                                </tr>
                                </table>";

                                $message .= "<p><b>Note : </b>This is an auto generated email. Request you to write back within 24 hrs to report any discrepancy.</p>";

                                $email_tmpl_data['to_emails'] = $spoc_email_id[0]['spoc_email'];
                                $email_tmpl_data['cc_emails'] = $email.",".$spoc_email_id[0]['spoc_manager_email'];
                                $email_tmpl_data['from_emails'] = $email;
                
                                $email_tmpl_data['message'] = $message;
                                $email_tmpl_data['subject'] = $subject;
               
                                $result_mail = $this->email->component_send_insuff_raised($email_tmpl_data);
                            }
                        }

                    $user_activity_data = $this->common_model->user_actity_data(array('component' => "Education", 'ref_no' => $ref_no, 'candidate_name' => $CandidateName, 'created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id'], 'activity_type' => 'Manual', 'action' => 'Insuff Raised'));
                }

                auto_update_overall_status($this->input->post('candidates_info_id'));

                $json_array['status'] = SUCCESS_CODE;

                $json_array['message'] = 'Record Successfully Inserted';

            } else {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = 'already insuff raised, please close this and raise again';
            }
        }
        echo_json($json_array);
    }

    public function approve_first_qc()
    {
        if ($this->input->is_ajax_request()) {

            $frist_qc_id = $this->input->post('frist_qc_id');
            $cands_id = $this->input->post('frist_cands_id');
            $comp_id = $this->input->post('frist_comp_id');
            $cands_name = $this->input->post('frist_cands_name');
            $ref_no = $this->input->post('frist_ref_no');

            $accepted_on = date(DB_DATE_FORMAT);

            $result = $this->education_model->save_first_qc_result(array("first_qc_approve" => "First QC Approve", "first_qc_updated_on" => $accepted_on, "first_qu_updated_by" => $this->user_info['id']), array("education_id" => $comp_id, "id" => $frist_qc_id, "candsid" => $cands_id));

            if ($result) {
                $user_activity_data = $this->common_model->user_actity_data(array('component' => "Education",
                    'ref_no' => $ref_no, 'candidate_name' => $cands_name, 'created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id'], 'activity_type' => 'Manual', 'action' => 'First QC Approved'));

                all_component_closed_qc_status($cands_id);

                $json_array['status'] = SUCCESS_CODE;

                $json_array['message'] = 'First QC Approve';

            } else {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = 'Something went wrong,please try again';
            }
        } else {
            $json_array['status'] = ERROR_CODE;

            $json_array['message'] = 'Something went wrong,please try again';
        }
        echo_json($json_array);
    }

    public function rejected_first_qc()
    {
        if ($this->input->is_ajax_request()) {
            $comp_id = $this->input->post('frist_comp_id');
            $frist_qc_id = $this->input->post('frist_qc_id');
            $rejected_reason = $this->input->post('reject_reason');
            $cands_id = $this->input->post('frist_cands_id');
            $rejected_on = date(DB_DATE_FORMAT);
            $cands_name = $this->input->post('frist_cands_name');
            $ref_no = $this->input->post('frist_ref_no');

            $result = $this->education_model->save_first_qc_result(array("first_qu_reject_reason" => $rejected_reason, "first_qc_approve" => '', "first_qu_updated_by" => $this->user_info['id'], "first_qc_updated_on" => $rejected_on, "verfstatus" => 13, "var_filter_status" => "WIP", "var_report_status" => "WIP"), array("education_id" => $comp_id, "id" => $frist_qc_id, "candsid" => $cands_id));

            if ($result) {
                $user_activity_data = $this->common_model->user_actity_data(array('component' => "Education",
                    'ref_no' => $ref_no, 'candidate_name' => $cands_name, 'created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id'], 'activity_type' => 'Manual', 'action' => 'First QC Rejected'));

                $json_array['status'] = SUCCESS_CODE;

                $json_array['message'] = 'First QC Rejected';
            } else {

                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = 'Something went wrong,please try again';

            }
        } else {
            $json_array['status'] = ERROR_CODE;

            $json_array['message'] = 'Something went wrong,please try again';
        }
        echo_json($json_array);
    }

    public function education_reinitiated_date()
    {

        $json_array = array();

        if ($this->input->is_ajax_request()) {

            $educationid = $this->input->post('update_id');
            $reinitiated_date = $this->input->post('reinitiated_date');
            $reinitiated_remark = $this->input->post('reinitiated_remark');
            $clientid = $this->input->post('clientid');

            $check = $this->education_model->select_reinitiated_date(array('id' => $educationid));

            if ($check[0]['edu_re_open_date'] == "0000-00-00" || $check[0]['edu_re_open_date'] == "") {
                $reinitiated_dates = $reinitiated_date;
            } else {
                $reinitiated_dates = $check[0]['edu_re_open_date'] . "||" . $reinitiated_date;
            }

            $result = $this->education_model->save_update_initiated_date(array('edu_re_open_date' => $reinitiated_dates, 'edu_reinitiated_remark' => $reinitiated_remark), array('id' => $educationid));

            $result_addrverres = $this->education_model->save_update_initiated_date_education(array('verfstatus' => 26, 'var_filter_status' => "WIP", 'var_report_status' => "WIP", 'first_qc_approve' => "", 'first_qc_updated_on' => "", 'first_qu_reject_reason' => "", 'first_qu_updated_by' => ""), array('education_id' => $educationid));

            $error_msgs = array();
            $file_upload_path = SITE_BASE_PATH . EDUCATION . $clientid;
            if (!folder_exist($file_upload_path)) {
                mkdir($file_upload_path, 0777);
            } else if (!is_writable($file_upload_path)) {
                array_push($error_msgs, 'Problem while uploading');
            }

            $config_array = array('file_upload_path' => $file_upload_path, 'file_permission' => 'jpeg|jpg|png|pdf|docx|xls|xlsx', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 1000, 'file_id' => $educationid, 'component_name' => 'education_id');

            if ($_FILES['attachment_reinitiated']['name'][0] != '') {
                $config_array['files_count'] = count($_FILES['attachment_reinitiated']['name']);
                $config_array['file_data'] = $_FILES['attachment_reinitiated'];
                $config_array['type'] = 2;
                $retunr_cd = $this->file_upload_multiple($config_array);
                if (!empty($retunr_cd)) {
                    $this->common_model->common_insert_batch('education_files', $retunr_cd['success']);
                }
            }

            $result_address_activity_data = $this->education_model->initiated_date_education_activity_data(array('candsid' => $this->input->post('candidates_info_id'), 'comp_table_id' => $educationid, 'action' => "Re-Initiated", '  activity_status' => "Re-Initiated", 'remarks' => 'Client requested to re-verify the case [' . $reinitiated_remark . ']', 'created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id']));

            if ($result && $result_addrverres) {

                auto_update_overall_status($this->input->post('candidates_info_id'));

                $json_array['status'] = SUCCESS_CODE;

                $json_array['message'] = 'Record Successfully Inserted';
            }

        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['message'] = 'Something went wrong,please try again';
        }
        echo_json($json_array);
    }

    public function insuff_tab_view($emp_id = '')
    {
        if ($this->input->is_ajax_request() && $emp_id) {
            $data['insuff_details'] = $this->education_model->select_insuff_join(array('education_id' => $emp_id));

            echo $this->load->view('admin/eduver_insuff_view', $data, true);
        } else {
            echo "<p>Something went wrong, please try again.</p>";
        }
    }

    public function insuff_raised_data()
    {
        if ($this->input->is_ajax_request()) {
            $insuff_data = $this->input->post('insuff_data');

            $result = $this->education_model->select_insuff(array('id' => $insuff_data));
            if (!empty($result)) {
                $result = $result[0];
                $result['insuff_raised_date'] = convert_db_to_display_date($result['insuff_raised_date']);
                $result['insuff_clear_date'] = convert_db_to_display_date($result['insuff_clear_date']);
            }
            echo_json($result);
        }
    }

    public function insuff_edit_clear_raised_data()
    {
        if ($this->input->is_ajax_request()) {
            $insuff_data = $this->input->post('insuff_data');

            $result = $this->education_model->select_insuff(array('id' => $insuff_data));

            if (!empty($result)) {
                $data['insuff_reason_list'] = $this->insuff_reason_list(3);
                $data['insuff_details'] = $result[0];
                echo $this->load->view('admin/insuff_edit_and_clear_view', $data, true);
            }
        }
    }

    public function update_insuff_details()
    {
        if ($this->input->is_ajax_request()) {
            $frm_details = $this->input->post();

            $fields = array('insuff_raised_date' => convert_display_to_db_date($frm_details['txt_insuff_raise']),
                'insff_reason' => $frm_details['insff_reason'],
                'insuff_raise_remark' => $frm_details['insuff_raise_remark'],
                'insuff_clear_date' => null,
                'insuff_remarks' => $frm_details['insuff_remarks'],
                'modified_on' => date(DB_DATE_FORMAT),
                'modified_by' => $this->user_info['id'],
                'status' => 4,
            );

            if ($frm_details['insuff_clear_date'] != '') {
                $clear_date = $frm_details['insuff_clear_date'];
                $fields['insuff_clear_date'] = convert_display_to_db_date($frm_details['insuff_clear_date']);
                $fields['insuff_cleared_timestamp'] = date(DB_DATE_FORMAT);
                $fields['insuff_cleared_by'] = $this->user_info['id'];
            } else {
                $clear_date = $this->insuff_date;
                $fields['insuff_clear_date'] = convert_display_to_db_date($frm_details['insuff_clear_date']);
            }

            $fields['hold_days'] = getNetWorkDays($frm_details['txt_insuff_raise'], $clear_date);

            $result = $this->education_model->save_update_insuff($fields, array('id' => $frm_details['id']));

            if ($result) {
                auto_update_overall_status($frm_details['candidates_info_id']);

                auto_update_tat_status($frm_details['candidates_info_id']);

                $json_array['status'] = SUCCESS_CODE;

                $json_array['message'] = 'Updated Successfully';

            } else {
                $json_array['status'] = SUCCESS_CODE;

                $json_array['message'] = "Something went wrong, please try again!";
            }
            echo_json($json_array);
        }
    }

    public function insuff_clear()
    {
        $json_array = array();

        $frm_details = $this->input->post();


        if ($this->input->is_ajax_request() && $this->permission['access_education_list_insuff_clear'] == 1) {

            if (convert_display_to_db_date($frm_details['insuff_clear_date']) >= convert_display_to_db_date($frm_details['check_insuff_raise'])) {

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

                $fields = array('insuff_clear_date' => convert_display_to_db_date($insuff_date), 'insuff_remarks' => $frm_details['insuff_remarks'], 'insuff_cleared_timestamp' => date(DB_DATE_FORMAT), 'status' => 2, 'insuff_cleared_by' => $this->user_info['id'], 'auto_stamp' => 2, 'hold_days' => $hold_days);

                $result = $this->education_model->save_update_insuff($fields, array('id' => $frm_details['insuff_clear_id']));

                $get_vendor_log_deatail = $this->education_model->check_vendor_status_insufficiency(array('view_vendor_master_log.component' => "eduver", 'view_vendor_master_log.component_tbl_id' => 3,
                    'view_vendor_master_log.final_status' => 'insufficiency', 'education.id' => $frm_details['clear_update_id']));

                if (!empty($get_vendor_log_deatail)) {

                    $update_vendor_log_deatail = $this->education_model->reject_cost_vendor(array('final_status' => 'wip', 'vendor_remark' => '', 'modified_on' => date(DB_DATE_FORMAT)), array('view_vendor_master_log.id' => $get_vendor_log_deatail[0]['id']));

                }

                if ($result) {
                    $user_activity_data = $this->common_model->user_actity_data(array('component' => "Education", 'ref_no' => $frm_details['component_ref_no'], 'candidate_name' => $frm_details['CandidateName'], 'created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id'], 'activity_type' => 'Manual', 'action' => 'Insuff Cleared'));
                }

                $error_msgs = $file_array = array();

                $file_upload_path = SITE_BASE_PATH . EDUCATION . $frm_details['clear_clientid'];

                if (!folder_exist($file_upload_path)) {
                    mkdir($file_upload_path, 0777);
                }

                $config_array = array('file_upload_path' => $file_upload_path, 'file_permission' => 'jpeg|jpg|png|pdf', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 1000, 'file_id' => $frm_details['clear_update_id'], 'component_name' => 'education_id');
                if (empty($error_msgs)) {
                    if ($_FILES['clear_attchments']['name'][0] != '') {
                        $config_array['files_count'] = count($_FILES['clear_attchments']['name']);
                        $config_array['file_data'] = $_FILES['clear_attchments'];
                        $config_array['type'] = 2;
                        $config_array['education_id'] =  $frm_details['component_ref_no'] ;
                        $retunr_de = $this->file_upload_library->file_upload_multiple_education($config_array, true);
                        if (!empty($retunr_de)) {
                            $this->common_model->common_insert_batch('education_files', $retunr_de['success']);
                        }
                    }
                }
                

               
                if ($result) {

                    auto_update_overall_status($frm_details['candidates_info_id']);

                    auto_update_tat_status($frm_details['candidates_info_id']);

                    $details = $this->education_model->get_all_education_record(array('education.id' =>  $frm_details['clear_update_id']));
     
                    if($details[0]['university_board'] != "2476")
                    {

                        $select_vendor_details = $this->education_model->select_education('university_master', array('*'),array('university_master.id'=>$details[0]['university_board']));

                        if(!empty($select_vendor_details[0]['vendor_id']) && !empty($select_vendor_details[0]['year_of_passing']))
                        {
                          
                            if(($details[0]['university_board'] >= $select_vendor_details[0]['year_of_passing']) && ($select_vendor_details[0]['id'] == $details[0]['university_board']))
                            {    

                                $update = $this->education_model->upload_vendor_assign('education', array('vendor_id' => $select_vendor_details[0]['vendor_id'],'verifiers_spoc_status' => 2,  'vendor_assgined_on' => date(DB_DATE_FORMAT)), array('id' => $frm_details['clear_update_id'], 'vendor_id =' => 0));

                                $update1 = $this->education_model->update_status('education_result', array('verfstatus' => 1), array('education_id' => $frm_details['clear_update_id']));

                                $get_username = $this->education_model->get_reporting_manager_id();
                                $get_vendorname = $this->education_model->get_vendor_details(array('id'=>$select_vendor_details[0]['vendor_id']));
                                if ($update) {

                                    $files[] = array(
                                        'vendor_id' => $select_vendor_details[0]['vendor_id'],
                                        'case_id' => $frm_details['clear_update_id'],
                                        "status" => 0,
                                        "remarks" => '',
                                        "created_by" => $this->user_info['id'],
                                        "created_on" => date(DB_DATE_FORMAT),
                                        "approval_by" => $this->user_info['id'],
                                        "modified_on" =>  date(DB_DATE_FORMAT),
                                        "modified_by" => $this->user_info['id'],
                                    );

                                    $activity[]  =  array(
                                        'comp_table_id' => $frm_details['clear_update_id'],
                                        'activity_status' => 'Assign',
                                        "activity_type" => 'Mist it',
                                        "remarks" => $get_username[0]['user_name'].' has assigned the case to '.$get_vendorname[0]['vendor_name'],
                                        "created_by" => $this->user_info['id'],
                                        "created_on" => date(DB_DATE_FORMAT),
                                        "is_auto_filled" => 0
                                                
                                    );
                                }
                               
                            }
                            else{

                                $update = $this->education_model->upload_vendor_assign('education', array('vendor_id' => 20,'verifiers_spoc_status' => 1,  'vendor_assgined_on' => date(DB_DATE_FORMAT)), array('id' => $frm_details['clear_update_id'], 'vendor_id =' => 0));

                                $update1 = $this->education_model->update_status('education_result', array('verfstatus' => 1), array('education_id' => $frm_details['clear_update_id']));

                                $get_username = $this->education_model->get_reporting_manager_id();
                                $get_vendorname = $this->education_model->get_vendor_details(array('id'=>20));
                                if ($update) {

                                    $files[] = array(
                                        'vendor_id' => 20,
                                        'case_id' => $frm_details['clear_update_id'],
                                        "status" => 0,
                                        "remarks" => '',
                                        "created_by" => $this->user_info['id'],
                                        "created_on" => date(DB_DATE_FORMAT),
                                        "approval_by" => 0,
                                        "modified_on" => null,
                                        "modified_by" => '',
                                    );

                                    $activity[]  =  array(
                                        'comp_table_id' => $frm_details['clear_update_id'],
                                        'activity_status' => 'Assign',
                                        "activity_type" => 'Mist it',
                                        "remarks" => $get_username[0]['user_name'].' has assigned the case to '.$get_vendorname[0]['vendor_name'],
                                        "created_by" => $this->user_info['id'],
                                        "created_on" => date(DB_DATE_FORMAT),
                                        "is_auto_filled" => 0
                                                
                                    );
                                }
                      
                            }
                        }
                        else{
        
                                $update = $this->education_model->upload_vendor_assign('education', array('vendor_id' => 20,'verifiers_spoc_status' => 1,  'vendor_assgined_on' => date(DB_DATE_FORMAT)), array('id' => $frm_details['clear_update_id'], 'vendor_id =' => 0));

                                $update1 = $this->education_model->update_status('education_result', array('verfstatus' => 1), array('education_id' => $frm_details['clear_update_id']));

                                $get_username = $this->education_model->get_reporting_manager_id();
                                $get_vendorname = $this->education_model->get_vendor_details(array('id'=>20));
                                if ($update) {

                                    $files[] = array(
                                        'vendor_id' => 20,
                                        'case_id' => $frm_details['clear_update_id'],
                                        "status" => 0,
                                        "remarks" => '',
                                        "created_by" => $this->user_info['id'],
                                        "created_on" => date(DB_DATE_FORMAT),
                                        "approval_by" => 0,
                                        "modified_on" => null,
                                        "modified_by" => '',
                                    );

                                    $activity[]  =  array(
                                        'comp_table_id' => $frm_details['clear_update_id'],
                                        'activity_status' => 'Assign',
                                        "activity_type" => 'Mist it',
                                        "remarks" => $get_username[0]['user_name'].' has assigned the case to '.$get_vendorname[0]['vendor_name'],
                                        "created_by" => $this->user_info['id'],
                                        "created_on" => date(DB_DATE_FORMAT),
                                        "is_auto_filled" => 0
                                                
                                    );
                                }
                      
                        }

                        if (!empty($files)) {
                            $inserted = $this->common_model->common_insert_batch('education_vendor_log', $files);
                            $activity_insert = $this->common_model->common_insert_batch('education_activity_data', $activity);
                        }
                    }

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

    public function insuff_delete()
    {
        $json_array = array();

        $insuff_data = $this->input->post('insuff_data');
        if ($this->input->is_ajax_request() && $this->permission['access_education_list_insuff_delete'] == 1) {
            $fields = array('status' => 3, 'modified_on' => date(DB_DATE_FORMAT), 'modified_by' => $this->user_info['id']);

            $result = $this->education_model->save_update_insuff($fields, array('id' => $insuff_data));

            if ($result) {

                auto_update_overall_status($this->input->post('candidates_info_id'));

                $json_array['status'] = SUCCESS_CODE;

                $json_array['message'] = 'Deleted Successfully';
            } else {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = 'Something went wrong,please try again';
            }
        } else {
            $json_array['message'] = "Don't have permission to access this function";

            $json_array['status'] = ERROR_CODE;
        }
        echo_json($json_array);
    }

    public function add_result_model($where_id, $url)
    {
        $details = $this->education_model->get_all_education_record(array('education.id' => $where_id));

        if ($where_id && !empty($details)) {
            $data['details'] = $details[0];
            $data['university'] = $this->university_list_dropdown();
            $data['qualification'] = $this->qualification_list();
       

            $data['university_selected_attachments'] = $this->education_model->select_file(array('id', 'file_name', 'status'), array('education_id' => $where_id, 'type' => 4));

            $data['attachments'] = $this->education_model->select_file(array('id', 'file_name', 'status'), array('education_id' => $where_id, 'type' => 1));

            $data['vendor_attachments'] = $this->education_model->select_file_vendor(array('view_vendor_master_log_file.id', 'view_vendor_master_log_file.file_name', 'view_vendor_master_log_file.real_filename', 'view_vendor_master_log_file.status'), array('view_vendor_master_log_file.component_tbl_id' => 3), $where_id);

            $data['url'] = $url;
            echo $this->load->view('admin/education_add_result_model_view', $data, true);
        } else {
            echo "<h4>Record Not Found</h4>";
        }
    }

    public function add_verificarion_result()
    {
        if ($this->input->is_ajax_request()) {
            try{ 
                $this->form_validation->set_rules('education_id', 'Id', 'required');

                $this->form_validation->set_rules('clientid', 'Client Id', 'required');

                $this->form_validation->set_rules('education_result_id', 'Id', 'required');

                $this->form_validation->set_rules('closuredate', 'Mode Of Verification', 'required');

                if ($this->form_validation->run() == false) {

                    $json_array['status'] = ERROR_CODE;
                    $json_array['message'] = validation_errors('', '');

                } else {
                    $frm_details = $this->input->post();

                    if (($frm_details['action_val'] != "Select") || ($frm_details['activity_last_id'] != "")) {

                        $verfstatus = status_id_frm_db(array('status_value' => $frm_details['action_val'], 'components_id' => 6));

                        $education_result_id = $frm_details['education_result_id'];

                        $field_array = array(
                            'verfstatus' => $verfstatus['id'],
                            'var_filter_status' => $verfstatus['filter_status'],
                            'var_report_status' => $verfstatus['report_status'],
                            'clientid' => $frm_details['clientid'],
                            'candsid' => $frm_details['candidates_info_id'],
                            'education_id' => $frm_details['education_id'],
                            'closuredate' => convert_display_to_db_date($frm_details['closuredate']),
                            'res_qualification' => $frm_details['res_qualification'],
                            'res_school_college' => $frm_details['res_school_college'],
                            'res_university_board' => $frm_details['res_university_board'],
                            'res_month_of_passing' => $frm_details['res_month_of_passing'],
                            'res_year_of_passing' => $frm_details['res_year_of_passing'],
                            'res_mode_of_verification' => $frm_details['res_mode_of_verification'],
                            'verified_by' => $frm_details['verified_by'],
                            'verifier_designation' => $frm_details['verifier_designation'],
                            'verifier_contact_details' => $frm_details['verifier_contact_details'],
                            'remarks' => $frm_details['res_remarks'],
                            'modified_on' => date(DB_DATE_FORMAT),
                            'modified_by' => $this->user_info['id'],
                            'is_bulk_uploaded' => 0,
                            'activity_log_id' => $frm_details['activity_last_id'],
                            'qualification_action' => $frm_details['qualification_action'],
                            'school_college_action' => $frm_details['school_college_action'],
                            'university_board_action' => $frm_details['university_board_action'],
                            'month_of_passing_action' => $frm_details['month_of_passing_action'],
                            'year_of_passing_action' => $frm_details['year_of_passing_action'],

                        );

                        $field_array = array_map('strtolower', $field_array);

                        if (isset($frm_details['remove_file'])) // delete uploaded file
                        {
                            $this->education_model->delete_uploaded_file($frm_details['remove_file']);
                        }
                        if (isset($frm_details['add_file'])) // delete uploaded file
                        {
                            $this->education_model->add_uploaded_file($frm_details['add_file']);
                        }

                        if (isset($frm_details['vendor_remove_file'])) // delete uploaded file
                        {
                            $this->education_model->vendor_delete_uploaded_file($frm_details['vendor_remove_file']);
                        }
                        if (isset($frm_details['vendor_add_file'])) // delete uploaded file
                        {
                            $this->education_model->vendor_add_uploaded_file($frm_details['vendor_add_file']);
                        }
                        if (isset($frm_details['university_images'])) // delete uploaded file
                        {
                            if($frm_details['university_images'] != '')
                            {
                                $university_image = explode(',',$frm_details['university_images']);
                                $file_array = array();
                                foreach ($university_image as $key => $value) {
                                    $filename = SITE_BASE_PATH . UNIVERSITY_PIC . $value;

                                    if (file_exists($filename)) {
                                       
                                       // $file_upload_path_university = SITE_BASE_PATH . EDUCATION . $frm_details['clientid'].'/';
                                       // if (!folder_exist($file_upload_path_university)) {
                                       //     mkdir($file_upload_path_university, 0777);
                                       // }
                                        
                                    
                                      //  if(copy($filename,$file_upload_path_university.$value)){
                                           
                                            array_push($file_array, array(
                                                'file_name' => $value,
                                                'real_filename' => $value,
                                                'type' => 4,
                                                'status' => 1,
                                                'education_id' => $frm_details['education_id'])
                                            );
                                       // }

                                    } 
                                } 
                                
                                if (!empty($file_array)) {
                                    $this->common_model->common_insert_batch('education_files',$file_array);
                                } 
                            }
                        } 

                        $result = $this->education_model->save_update_result($field_array, array('id' => $education_result_id));

                        $result_education_result = $this->education_model->save_update_result_education($field_array);

                        if ($verfstatus['id'] == 9 || $verfstatus['id'] == 27 || $verfstatus['id'] == 28) {

                            $get_vendor_log_deatail = $this->education_model->check_vendor_status_closed_or_not(array('view_vendor_master_log.component' => "eduver", 'view_vendor_master_log.component_tbl_id' => 3, 'education.id' => $frm_details['education_id']));

                            if (!empty($get_vendor_log_deatail)) {

                                $update_vendor_log_deatail = $this->education_model->reject_cost_vendor(array('final_status' => 'cancelled', 'status' => 5, 'remarks' => 'Stop Check', 'rejected_by' => $this->user_info['id'], 'rejected_on' => date(DB_DATE_FORMAT), 'vendor_remark' => 'Stop Check', 'modified_on' => date(DB_DATE_FORMAT)), array('view_vendor_master_log.id' => $get_vendor_log_deatail[0]['id']));

                                $field_array_education = array(
                                    'remarks' => "Stop Check",
                                    'modified_by' => $this->user_info['id'],
                                    'modified_on' => date(DB_DATE_FORMAT),
                                    'status' => 2,
                                );

                                $education_vendor_result = $this->education_model->update_education_vendor_log('education_vendor_log', $field_array_education, array('id' => $get_vendor_log_deatail[0]['education_vendor_log_id']));

                            }
                        }

                        $file_upload_path = SITE_BASE_PATH . EDUCATION . $frm_details['clientid'];
                    
                        $config_array = array(
                            'file_upload_path'  => $file_upload_path, 
                            'file_permission'   => 'jpeg|jpg|png|pdf', 
                            'file_size'         => BULK_UPLOAD_MAX_SIZE_MB * 2000, 
                            'file_id'           => $frm_details['education_id'], 
                            'component_name'    => 'education_id'
                        );
            
                        if (isset($_FILES['attchments_ver']['name'][0]) &&  $_FILES['attchments_ver']['name'][0] != '') {
                            $config_array['files_count']    = count($_FILES['attchments_ver']['name']);
                            $config_array['file_data']      = $_FILES['attchments_ver'];
                            $config_array['type']           = 1;
                            $retunr_de = $this->file_upload_library->file_upload_multiple($config_array, true);
                            
                            if (!empty($retunr_de['success'])) {
                                $this->common_model->common_insert_batch('education_files', $retunr_de['success']);
                            }
                        }

                        if(isset($frm_details['upload_capture_image_education_result']))
                        {
                            if ($frm_details['upload_capture_image_education_result']) {
      
                                $upload_capture_image = explode("||", $frm_details['upload_capture_image_education_result']);
                                        
                                    foreach ($upload_capture_image as $key => $value) {
                                        $key = $key + 1;

                                        $file_name = $frm_details['education_com_ref'] . '-' .$key. '-' . date(UPLOAD_FILE_DATE_FORMAT) . '.png';
                                        $uploadpath = $file_upload_path . '/' . $file_name;
                                        $base64_to_jpeg = base64_to_jpeg($value, $uploadpath);
                                            if ($base64_to_jpeg) {
                                                log_message('error', 'Inside if condition success');
                                                $this->common_model->save('education_files', ['file_name' => $file_name, 'real_filename' => $file_name,'status' => 1,'type' => 1,'education_id' => $frm_details['education_id']]);
                                            }

                                    }
                            }
                        }
                        
                        if ($result) {

                            auto_update_overall_status($frm_details['candidates_info_id']);

                        //  all_component_closed_qc_status($frm_details['candidates_info_id']);

                            $json_array['status'] = SUCCESS_CODE;
                            $json_array['message'] = 'Updated Successfully';
                            $json_array['redirect'] = ADMIN_SITE_URL . 'education';
                        } else {
                            $json_array['status'] = ERROR_CODE;

                            $json_array['message'] = 'Something went wrong,please try again';
                        }
                    } else {
                        $json_array['status'] = ERROR_CODE;

                        $json_array['message'] = 'Something went wrong,please try again';
                    }
                }
                echo_json($json_array);
            } catch (Exception $e) {
                log_message('error', 'Education::add_verificarion_result');
                log_message('error', $e->getMessage());
            }
        }
    }

    public function edit_university($id)
    {

        if ($this->input->is_ajax_request() && $id) {
            $data['universityname'] = $this->university_list();
            $data['university'] = $id;
            echo $this->load->view('admin/edit_university', $data, true);
        } else {
            echo "<p>We're sorry, but you do not have access to this page.</p>";
        }
    }

    public function edit_qualification($id)
    {

        if ($this->input->is_ajax_request() && $id) {
            $data['qualification_list'] = $this->qualification_list();
            $data['qualification'] = $id;
            echo $this->load->view('admin/edit_qualification', $data, true);
        } else {
            echo "<p>We're sorry, but you do not have access to this page.</p>";
        }
    }

    public function education_update_university()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {

            $educationid = $this->input->post('tbl_education_id');
            $university_board = $this->input->post('university_board');

            $result = $this->education_model->save(array('university_board' => $university_board), array('id' => $educationid));

            if ($result) {

                $json_array['message'] = 'University Updated Successfully';

                $json_array['status'] = SUCCESS_CODE;
            } else {
                $json_array['message'] = 'Something went wrong, please try again';

                $json_array['status'] = ERROR_CODE;
            }

        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['message'] = 'Something went wrong,please try again';
        }
        echo_json($json_array);
    }

    public function education_update_qualification()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {

            $educationid = $this->input->post('tbl_education_id');
            $qualification = $this->input->post('qualification');

            $result = $this->education_model->save(array('qualification' => $qualification), array('id' => $educationid));

            if ($result) {

                $json_array['message'] = 'Qualification Updated Successfully';

                $json_array['status'] = SUCCESS_CODE;
            } else {
                $json_array['message'] = 'Something went wrong, please try again';

                $json_array['status'] = ERROR_CODE;
            }

        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['message'] = 'Something went wrong,please try again';
        }
        echo_json($json_array);
    }

    public function add_verificarion_ver_result()
    {
        if ($this->input->is_ajax_request()) {
            try{
                $frm_details = $this->input->post();
                $this->form_validation->set_rules('education_id', 'Id', 'required');

                $this->form_validation->set_rules('clientid', 'Client Id', 'required');

                $this->form_validation->set_rules('education_result_id', 'Id', 'required');

                $this->form_validation->set_rules('closuredate', 'Mode Of Verification', 'required');

                if ($this->form_validation->run() == false) {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = validation_errors('', '');
                } else {

                    $frm_details = $this->input->post();
                    $education_result_id = $frm_details['education_result_id'];

                    $field_array = array(
                        'clientid' => $frm_details['clientid'],
                        'candsid' => $frm_details['candidates_info_id'],
                        'education_id' => $frm_details['education_id'],
                        'closuredate' => convert_display_to_db_date($frm_details['closuredate']),
                        'res_qualification' => $frm_details['res_qualification'],
                        'res_school_college' => $frm_details['res_school_college'],
                        'res_university_board' => $frm_details['res_university_board'],
                        'res_month_of_passing' => $frm_details['res_month_of_passing'],
                        'res_year_of_passing' => $frm_details['res_year_of_passing'],
                        'res_mode_of_verification' => $frm_details['res_mode_of_verification'],
                        'verified_by' => $frm_details['verified_by'],
                        'verifier_designation' => $frm_details['verifier_designation'],
                        'verifier_contact_details' => $frm_details['verifier_contact_details'],
                        'remarks' => $frm_details['res_remarks'],
                        'modified_on' => date(DB_DATE_FORMAT),
                        'modified_by' => $this->user_info['id'],
                        'qualification_action' => $frm_details['qualification_action'],
                        'school_college_action' => $frm_details['school_college_action'],
                        'university_board_action' => $frm_details['university_board_action'],
                        'month_of_passing_action' => $frm_details['month_of_passing_action'],
                        'year_of_passing_action' => $frm_details['year_of_passing_action'],

                    );

                    $field_array = array_map('strtolower', $field_array);

                    if (isset($frm_details['remove_file'])) // delete uploaded file
                    {
                        $this->education_model->delete_uploaded_file($frm_details['remove_file']);
                    }
                    if (isset($frm_details['add_file'])) // delete uploaded file
                    {
                        $this->education_model->add_uploaded_file($frm_details['add_file']);
                    }

                    if (isset($frm_details['vendor_remove_file'])) // delete uploaded file
                    {
                        $this->education_model->vendor_delete_uploaded_file($frm_details['vendor_remove_file']);
                    }
                    if (isset($frm_details['vendor_add_file'])) // delete uploaded file
                    {
                        $this->education_model->vendor_add_uploaded_file($frm_details['vendor_add_file']);
                    }

                    if (isset($frm_details['university_images_result'])) // delete uploaded file
                    {
                        if($frm_details['university_images_result'] != '')
                        {
                            $university_image = explode(',',$frm_details['university_images_result']);
                            $file_array = array();
                            foreach ($university_image as $key => $value) {
                                $filename = SITE_BASE_PATH . UNIVERSITY_PIC . $value;

                                if (file_exists($filename)) {
                                       
                                       // $file_upload_path_university = SITE_BASE_PATH . EDUCATION . $frm_details['clientid'].'/';
                                       // if (!folder_exist($file_upload_path_university)) {
                                       //     mkdir($file_upload_path_university, 0777);
                                       // }
                                        
                                    
                                      //  if(copy($filename,$file_upload_path_university.$value)){
                                           
                                            array_push($file_array, array(
                                                'file_name' => $value,
                                                'real_filename' => $value,
                                                'type' => 4,
                                                'status' => 1,
                                                'education_id' => $frm_details['education_id'])
                                            );
                                       // }

                                    } 
                            } 
                                
                            if (!empty($file_array)) {
                                $this->common_model->common_insert_batch('education_files',$file_array);
                            } 
                        }
                    } 
    

                    $result = $this->education_model->save_update_result($field_array, array('id' => $education_result_id));

                    $result_education_result = $this->education_model->save_update_result_education($field_array, array('id' => $frm_details['result_update_id']));

                    $file_upload_path = SITE_BASE_PATH . EDUCATION . $frm_details['clientid'];
                   
                    $config_array = array(
                        'file_upload_path'  => $file_upload_path, 
                        'file_permission'   => 'jpeg|jpg|png|pdf', 
                        'file_size'         => BULK_UPLOAD_MAX_SIZE_MB * 2000, 
                        'file_id'           => $frm_details['education_id'], 
                        'component_name'    => 'education_id'
                    );
        
                    if (isset($_FILES['attchments_ver']['name'][0]) &&  $_FILES['attchments_ver']['name'][0] != '') {
                        $config_array['files_count']    = count($_FILES['attchments_ver']['name']);
                        $config_array['file_data']      = $_FILES['attchments_ver'];
                        $config_array['type']           = 1;
                        $retunr_de = $this->file_upload_library->file_upload_multiple($config_array, true);
                        
                        if (!empty($retunr_de['success'])) {
                            $this->common_model->common_insert_batch('education_files', $retunr_de['success']);
                        }
                    }

                    if (isset($frm_details['upload_capture_image_education_ver_result'])) {

                        if ($frm_details['upload_capture_image_education_ver_result']) {

                            $upload_capture_image = explode("||", $frm_details['upload_capture_image_education_ver_result']);
                                        
                                foreach ($upload_capture_image as $key => $value) {
                                    $key = $key + 1;

                                    $file_name = $frm_details['education_com_ref'] . '-' .$key. '-' . date(UPLOAD_FILE_DATE_FORMAT) . '.png';
                                    $uploadpath = $file_upload_path . '/' . $file_name;
                                    $base64_to_jpeg = base64_to_jpeg($value, $uploadpath);
                                        if ($base64_to_jpeg) {
                                            log_message('error', 'Inside if condition success');
                                            $this->common_model->save('education_files', ['file_name' => $file_name, 'real_filename' => $file_name,'status' => 1,'type' => 1,'education_id' => $frm_details['education_id']]);
                                        }

                                }
                        }
                    }


                    if ($result) {

                        auto_update_overall_status($frm_details['candidates_info_id']);
                  //      all_component_closed_qc_status($frm_details['candidates_info_id']);
                        $json_array['status'] = SUCCESS_CODE;

                        $json_array['message'] = 'Updated Successfully';

                        $json_array['redirect'] = ADMIN_SITE_URL . 'education';
                    } else {
                        $json_array['status'] = ERROR_CODE;

                        $json_array['message'] = 'Something went wrong,please try again';
                    }
                }
                echo_json($json_array);

            } catch (Exception $e) {
                log_message('error', 'Education::add_verificarion_result');
                log_message('error', $e->getMessage());
            }
        }
    }

    public function componet_html_form($cmp_id)
    {
        if ($this->input->is_ajax_request() && $cmp_id) {
            $details = $this->education_model->get_all_education_record(array('education.id' => decrypt($cmp_id)));

            if (!empty($details)) {
                $data['details'] = $details[0];
                echo $this->load->view('admin/education_add_result_model_view_first_qc', $data, true);
            } else {
                echo "<h4>Record Not Found</h4>";
            }

        } else {
            echo "<p>Something went wrong, please try again.</p>";
        }
    }

    public function bulk_upload_education()
    {
        $data = array();

        if ($this->input->is_ajax_request()) {
            $record = array();
            $file_upload_path = SITE_BASE_PATH . EDUCATION;

            if (!folder_exist($file_upload_path)) {
                mkdir($file_upload_path, 0777,true);
            } else if (!is_writable($file_upload_path)) {
                array_push($error_msgs, 'Problem while uploading');
            }

            $uplaod_details = array('file_upload_path' => $file_upload_path, 'file_data' => $_FILES['cands_bulk_sheet'], 'file_permission' => 'csv|xls|xlsx', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 100);
            $upload_result = $this->file_uplod($uplaod_details);

            $record = array();

            if (!empty($upload_result) && $upload_result['status'] == true) {

                $this->load->model('candidates_model');

                $raw_filename = $_FILES['cands_bulk_sheet']['tmp_name'];

                $headerLine = 0;
                $file = fopen($raw_filename, "r");

                $this->load->library('excel_reader', array('file_name' => $file_upload_path . '/' . $upload_result['file_name']));

                $excel_handler = $this->excel_reader->file_handler;

                $excel_data = $excel_handler->rows();
                if (!empty($excel_data)) {
                    unset($excel_data[0]);

                    $excel_data = array_map("unserialize", array_unique(array_map("serialize", $excel_data)));
                    foreach ($excel_data as $value) {

                        if (count($value) < 17) {
                            continue;
                        }

                        $check_record_exits = $this->candidates_model->select(true, array('id', 'clientid', 'entity', 'package'), array('cmp_ref_no' => strtolower($value[0])));

                        $univerities_details = array('universityname' => strtolower($value[3]),
                            'status' => '1',
                            'created_by' => $this->user_info['id'],
                            'created_on' => date(DB_DATE_FORMAT),
                        );

                        $nameoftheuniversities_id = $this->education_model->  check_universities_exits($univerities_details);

                        $nameoftheuniversity = $this->education_model->check_universitiesname_exits(array('id' => $nameoftheuniversities_id));
                       
                        $qualification_details = array('qualification' => strtolower($value[5]),
                            'created_by' => $this->user_info['id'],
                            'created_on' => date(DB_DATE_FORMAT),
                        );

                        $nameofthequalification_id = $this->education_model->check_qualification_exits($qualification_details);

                        $nameofthequalification = $this->education_model->check_qualificationname_exits(array('id' => $nameofthequalification_id));

                        if (!empty($check_record_exits) && $value[0] != "") {

                            $users_id  = $this->get_reporting_manager_for_executive($check_record_exits['clientid']); 

                            $user_id =  $users_id[0]['id'];

                            $tat_day = $this->get_client_entity_package_tat_day(array('tbl_clients_id' => $check_record_exits['clientid'], 'entity' => $check_record_exits['entity'], 'package' => $check_record_exits['package']));

                            $get_holiday1 = $this->get_holiday();

                            $get_holiday = array_map('current', $get_holiday1);
                            $closed_date = getWorkingDays(get_date_from_timestamp($value[1]), $get_holiday, $tat_day[0]['tat_eduver']);

                            $mode_of_verification = $this->education_model->get_mode_of_verification(array('entity' => $check_record_exits['entity'], 'package' => $check_record_exits['package'], 'tbl_clients_id' => $check_record_exits['clientid']));

                            if (!empty($mode_of_verification)) {
                                $mode_of_verification_value = json_decode($mode_of_verification[0]['mode_of_verification']);

                                $mode_of_veri = $mode_of_verification_value->eduver;
                            } else {

                                $mode_of_veri = "";
                            }

                            $field_array = array('clientid' => $check_record_exits['clientid'],
                                'candsid' => $check_record_exits['id'],
                                'education_com_ref' => "",
                                'school_college' => $value[2],
                                'university_board' => $nameoftheuniversities_id,
                                'grade_class_marks' => $value[4],
                                'qualification' => $nameofthequalification_id,
                                'major' => $value[6],
                                'course_start_date' => $value[7],
                                'course_end_date' => $value[8],
                                'month_of_passing' => $value[9],
                                'year_of_passing' => $value[10],
                                'roll_no' => $value[11],
                                'enrollment_no' => $value[12],
                                'PRN_no' => $value[13],
                                'documents_provided' => $value[14],
                                'city' => $value[15],
                                'state' => $value[16],
                                'created_by' => $this->user_info['id'],
                                'created_on' => date(DB_DATE_FORMAT),
                                'modified_on' => date(DB_DATE_FORMAT),
                                'modified_by' => $this->user_info['id'],
                                'has_case_id' => $user_id,
                                'has_assigned_on' => date(DB_DATE_FORMAT),
                                'is_bulk_uploaded' => 1,
                                'iniated_date' => get_date_from_timestamp($value[1]),
                                "edu_re_open_date" => '',
                                "mode_of_veri" => $mode_of_veri,
                                "due_date" => $closed_date,
                                "status" => 1,
                                "tat_status" => "IN TAT",
                            );

                            $record = array_map('strtolower', array_map('trim', $field_array));

                            $insert_id = $this->education_model->save($record);

                            $education_com_ref = $this->education_com_ref($insert_id);

                            $result_get_count = $this->common_model->get_count(array('controllers' => 'assign_edu'));

                            $total_get_count = $result_get_count + 1;

                            auto_update_overall_status($check_record_exits['id']);
                            auto_update_tat_status($check_record_exits['id']);

                            $result_update_count = $this->common_model->update_count(array('count' => $total_get_count), array('controllers' => 'assign_edu'));

                            $data['success'] = $education_com_ref . " This Component Code Records Created Successfully";

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

    public function vendor_logs($id)
    {
        if ($this->input->is_ajax_request() && $id) {

            $vendor_result = $this->education_model->vendor_logs(array('component_tbl_id' => '3', 'component' => 'eduver'), $id);

            $counter = 1;

            $html_view = '<thead><tr><th>Sr No</th><th>Trasaction Id</th><th>Vendor Name</th><th>Approved By</th><th>Approved Date</th><th>Costing</th><th>TAT</th><th>Remark</th><th>Status</th><th>Action</th></tr></thead>';
            foreach ($vendor_result as $key => $value) {
                if($value['vendor_status'] == "1")
                {
                  $vendor_status = "Verifiers";
                }
                else
                {
                   $vendor_status = "Stamp";  
                }

                $html_view .= '<tr>';
                $html_view .= "<td>" . $counter . "</td>";
                $html_view .= "<td>" . $value['trasaction_id'] . "</td>";
                $html_view .= "<td>" . $value['vendor_name']." ( ".$vendor_status ." )</td>";
                //$html_view .= "<td>".$value['allocated_by']."</td>";
                //$html_view .= "<td>".$value['allocated_on']."</td>";
                $html_view .= "<td>" . $value['approval_by'] . "</td>";
                $html_view .= "<td>" . convert_db_to_display_date($value['approval_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12) . "</td>";
                $html_view .= "<td>" . $value['costing'] . "</td>";
                $html_view .= "<td>" . $value['tat_status'] . "</td>";
                $html_view .= "<td>" . $value['remarks'] . "</td>";
                $html_view .= "<td>" . $value['final_status'] . "</td>";

                if ($value['status'] != "5") {
                    $html_view .= '<td><button data-id=' . $value['id'] . ' data-url ="' . ADMIN_SITE_URL . 'education/View_vendor_log/' . $value['id'] . '" data-toggle="modal" data-target="#showvendorModel"  class="btn-info  showvendorModel"> View </button>';
                    // if(($value['costing'] == "0" &&  $value['additional_costing'] == "0") ||  ($value['costing'] == NULL &&  $value['additional_costing'] == NULL) )
                    // {
                    if ($value['final_status'] != "closed") {
                        $html_view .= ' <button data-id="showvendorModel_cost" data-url ="' . ADMIN_SITE_URL . 'education/View_vendor_log_cost/' . $value['id'] . '" data-toggle="modal" data-target="#showvendorModel_cost"  class="btn-info  showvendorModel_cost">Charge</button></tb> ';

                    }
                    $html_view .= ' <button data-id="showvendorModel_cancel" data-url ="' . ADMIN_SITE_URL . 'education/View_vendor_log_cancel/' . $value['id'] . '" data-toggle="modal" data-target="#showvendorModel_cancel"   class="btn-info  showvendorModel_cancel">Cancel</button></tb> ';
                }
                $html_view .= '</tr>';
                //}

                $counter++;
            }

            $json_array['message'] = $html_view;

            $json_array['status'] = SUCCESS_CODE;

        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['message'] = '<tr><td>Something went wrong, please try again!<td></tr>';

        }
        echo_json($json_array);
    }

    public function View_vendor_log($where_id)
    {

        $details = $this->education_model->select_vendor_result_log(array('component_tbl_id' => "3", "component" => "eduver"), $where_id);

        if ($where_id && !empty($details)) {
            $details['attachments'] = $this->education_model->select_file_from_vendor(array('id', 'file_name', 'real_filename'), array('view_venor_master_log_id' => $where_id, 'status' => 1, 'component_tbl_id' => 3));

            $details['attachments_file'] = $this->education_model->select_file(array('id', 'file_name', 'real_filename'), array('education_id' => $details[0]['education_id'], 'status' => 1));

            $details['check_insuff_raise'] = '';
           

            $details['university'] = $this->university_list();
            $details['qualification'] = $this->qualification_list();
            $details['states'] = $this->get_states();
            $details['details'] = $details[0];

            echo $this->load->view('admin/education_ads_vendor_view', $details, true);
        } else {
            echo "<h4>Record Not Found</h4>";
        }

    }

    public function View_vendor_log1($where_id)
    {

        $details = $this->education_model->select_vendor_result_log(array('component_tbl_id' => "3", "component" => "eduver"), $where_id);

        if ($where_id && !empty($details)) {

            $details['attachments'] = $this->education_model->select_file_from_vendor(array('id', 'file_name', 'real_filename'), array('view_venor_master_log_id' => $where_id, 'status' => 1, 'component_tbl_id' => 3));

            $details['attachments_file'] = $this->education_model->select_file(array('id', 'file_name', 'real_filename'), array('education_id' => $details[0]['education_id'], 'status' => 1));

            $details['check_insuff_raise'] = '';
             $data['assigned_user_id']  = $this->get_reporting_manager_for_execituve($details[0]['clientid']);

            $details['university'] = $this->university_list();
            $details['qualification'] = $this->qualification_list();
            $details['states'] = $this->get_states();
            $details['details'] = $details[0];

            echo $this->load->view('admin/education_ads_vendor_view_approve_tab', $details, true);
        } else {
            echo "<h4>Record Not Found</h4>";
        }

    }

    public function vendor_logs_cost()
    {

        if ($this->input->is_ajax_request()) {

            $frm_details = $this->input->post();

            $details = $this->education_model->select_vendor_result_log_cost_details(array('components_tbl_id' => "3"), $frm_details['id']);

            $counter = 1;

            $html_view = '<thead><tr><th>Sr No</th><th>Requested date</th><th>Requested By</th><th>Approved By</th><th>Cost</th><th>Additionl Cost</th><th>Total Cost</th><th>Reamrk</th></tr></thead>';
            foreach ($details as $key => $value) {
                $total_cost = $value['cost'] + $value['additional_cost'];

                $html_view .= '<tr>';
                $html_view .= "<td>" . $counter . "</td>";
                $html_view .= "<td>" . $value['created_on'] . "</td>";
                $html_view .= "<td>" . $value['requested_by'] . "</td>";

                if ($value['accept_reject_cost'] == 1) {
                    $html_view .= "<td>" . $value['approved_by'] . "</td>";
                } else {

                    $html_view .= "<td></td>";

                }

                $html_view .= "<td>" . $value['cost'] . "</td>";
                $html_view .= "<td>" . $value['additional_cost'] . "</td>";
                $html_view .= "<td>" . $total_cost . "</td>";
                $html_view .= "<td>" . $value['remark'] . "</td>";

                $html_view .= '</tr>';

                $counter++;
            }

            $json_array['message'] = $html_view;

            $json_array['status'] = SUCCESS_CODE;
        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['message'] = '<tr><td>Something went wrong, please try again!<td></tr>';
        }

        echo_json($json_array);

    }

    public function View_vendor_log_cost($where_id)
    {

        $details = $this->education_model->select_vendor_result_log_cost(array('component_tbl_id' => "3", "component" => "eduver"), $where_id);

        if ($where_id && !empty($details)) {

            $details['details'] = $details[0];

            echo $this->load->view('admin/education_ads_vendor_view_cost', $details, true);
        } else {
            echo "<h4>Record Not Found</h4>";
        }

    }

    public function View_vendor_log_cancel($where_id)
    {

        $details = $this->education_model->select_vendor_result_log_cost(array('component_tbl_id' => "3", "component" => "eduver"), $where_id);

        if ($where_id && !empty($details)) {

            $details['details'] = $details[0];

            echo $this->load->view('admin/address_ads_vendor_view_cancel', $details, true);
        } else {
            echo "<h4>Record Not Found</h4>";
        }

    }

    public function approval_queue()
    {
        $this->load->model(array('education_vendor_log_model'));
        $verifier_status =  2; 
        $assigned_option = array('' => 'select','clear'=>'Clear','major discrepancy'=>'Major Discrepancy','minor discrepancy'=>'Minor Discrepancy','no record found'=>'No Record Found','unable to verify'=>'Unable to Verify','insufficiency'=>'Insufficiency');
        $data['vendor_list'] = $this->vendor_list_education('eduver',$verifier_status);
        $data['assigned_option'] = $assigned_option;
        $data['header_title'] = "Vendor Approve List";

        $data['user_list_name'] = $this->users_list_filter();

        $data['vendor_spoc_list'] = $this->vendor_list_education('eduver',1);

        $data['vendor_stamp_list'] = $this->vendor_list_education('eduver',2);

        $this->load->view('admin/header', $data);

        $this->load->view('admin/education_vendor_aq');

        $this->load->view('admin/footer');
    }

    public function view_approval_queue()
    {

        $this->load->model(array('education_vendor_log_model'));

        $params = $add_candidates = $data_arry = $columns = array();

        $params = $_REQUEST;


        $lists = $this->education_vendor_log_model->get_new_list(array('education_vendor_log.status' => 0), $params);

      
        $totalRecords = count($this->education_vendor_log_model->get_new_list_count(array('education_vendor_log.status' => 0), $params));

        if ($this->permission['access_education_aq_view'] == 1) {
            $x = 0;

            foreach ($lists as $list) {

                $file = "&nbsp;&nbsp;&nbsp;&nbsp;";
                if ($list['education_attachments']) {
                    $files = explode('||', $list['education_attachments']);
                      
                    for ($i = 0; $i < count($files); $i++) {
                        $url = "'" . SITE_URL . EDUCATION . $list['clientid'] . '/';
                        $actual_file = $files[$i] . "'";
                        $myWin = "'" . "myWin" . "'";
                        $attribute = "'" . "height=250,width=480" . "'";

                        $file .= '<a href="javascript:;" onClick="myOpenWindow(' . $url . $actual_file . ',' . $myWin . ',' . $attribute . '); return false"><i class="fa fa-file-photo-o" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;';
                    }
                }

                $mode_of_verification_value = json_decode($list['mode_of_verification']);
                $data_arry[$x]['checkbox'] = $list['id'];
                $data_arry[$x]['id'] = $x + 1;

                $data_arry[$x]['education_com_ref'] = $list['education_com_ref'];
                $data_arry[$x]['clientname'] = $list['clientname'];
                $data_arry[$x]['entity_name'] = $list['entity_name'];
                $data_arry[$x]['package_name'] = $list['package_name'];
                $data_arry[$x]['vendor_name'] = $list['vendor_name'];
                $data_arry[$x]['school_college'] = $list['school_college'];
                $data_arry[$x]['encry_id'] = ADMIN_SITE_URL . "education/view_details/" . encrypt($list['case_id']);
                $data_arry[$x]['university_board'] = $list['university_board'];
                $data_arry[$x]['qualification'] = $list['qualification'];
                $data_arry[$x]['allocated_by'] = $list['allocated_by'];
                $data_arry[$x]['allocated_on'] = convert_db_to_display_date($list['allocated_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);
                $data_arry[$x]['CandidateName'] = $list['CandidateName'];
                $data_arry[$x]['mode_of_verification'] = $mode_of_verification_value->eduver;
                $data_arry[$x]['school_college'] =  $list['school_college'];
                $data_arry[$x]['DateofBirth'] = convert_db_to_display_date($list['DateofBirth']);
                $data_arry[$x]['NameofCandidateFather'] = $list['NameofCandidateFather'];
                $data_arry[$x]['MothersName'] = $list['MothersName'];
                $data_arry[$x]['ClientRefNumber'] = $list['ClientRefNumber'];
                $data_arry[$x]['roll_no'] = $list['roll_no'];
                $data_arry[$x]['enrollment_no'] = $list['enrollment_no'];
                $data_arry[$x]['major'] = $list['major'];
                $data_arry[$x]['grade_class_marks'] = $list['grade_class_marks'];
                $data_arry[$x]['course_start_date'] = convert_db_to_display_date($list['course_start_date']);
                $data_arry[$x]['course_end_date'] = convert_db_to_display_date($list['course_end_date']);
                $data_arry[$x]['month_of_passing'] = $list['month_of_passing'];
                $data_arry[$x]['year_of_passing'] = $list['year_of_passing'];
                if(!empty($list['url_link']))
                {
                    
                    $data_arry[$x]['url_link'] = "<a href='".$list['url_link']."' target='_blank' class='btn btn-sm btn-info'>Visit Link</a>";
                }
                else
                {
                    $data_arry[$x]['url_link'] = "";  
                }
                $data_arry[$x]['attachment_file'] = $file;

                $data_arry[$x]['action'] = '<button data-id='.$list['case_id'].' data-mode_of_verification='.$mode_of_verification_value->eduver.' data-educationcaseid ='.$list['id'].' data-toggle="modal" class="btn btn-sm btn-info  showverifierModel"> View </button> <button data-id='.$list['case_id'].' data-mode_of_verification='.$mode_of_verification_value->eduver.' data-educationcaseid ='.$list['id'].' data-toggle="modal" class="btn btn-sm btn-info  showURLModel"> URL </button>';

                $x++;
            }

            $json_data = array(
                "draw" => intval($params['draw']),
                "recordsTotal" => intval($totalRecords),
                "recordsFiltered" => intval($totalRecords),
                "data" => $data_arry,
            );

            echo_json($json_data);
        } else {
            $client_new_cases = $data_arry = array();

            $json_data = array("draw" => intval(1),
                "recordsTotal" => "Not Permission",
                "recordsFiltered" => null,
                "data" => $data_arry,

            );
            echo_json($json_data);

            // permission_denied();
        }
    }

    public function assign_to_executive()
    {
        $json_array = array();
        if ($this->input->is_ajax_request() && ($this->permission['access_education_list_re_assign'] == '1')) {
            $frm_details = $this->input->post();

            if ($frm_details['users_list'] != 0 && $frm_details['cases_id'] != "") {
                $return = $this->common_model->update_in('education', array('has_case_id' => $frm_details['users_list'], 'has_assigned_on' => date(DB_DATE_FORMAT)), array('where_id' => $frm_details['cases_id']));
                if ($return) {
                    $json_array['status'] = SUCCESS_CODE;
                    $json_array['message'] = "Assigned Successfully";

                } else {
                    $json_array['status'] = ERROR_CODE;
                    $json_array['message'] = "Something went wrong,please try again";
                }

            } else {
                $json_array['status'] = ERROR_CODE;
                $json_array['message'] = "Select atleast one case";
            }

        } else {

            $json_array['status'] = ERROR_CODE;
            $json_array['message'] = "Access Denied, You don’t have permission to access this page";
        }
        echo_json($json_array);
    }

    public function assign_to_vendor()
    {
        $json_array = array();

        if ($this->input->is_ajax_request() && $this->permission['access_education_assign_edu_assign'] == true) {
            $frm_details = $this->input->post();
  

            $list = explode(',', $frm_details['cases_id']);
            if ($frm_details['vendor_list'] != 0 && $frm_details['cases_id'] != "" && !empty($list)) {
                $files = $update = $activity = array();
                $insert_counter = 0;
                foreach ($list as $key => $value) {

                    $update = $this->common_model->update_batch_vendor_assign('education', array('vendor_id' => $frm_details['vendor_list'],'verifiers_spoc_status' => 1,  'vendor_assgined_on' => date(DB_DATE_FORMAT)), array('id' => $value, 'vendor_id =' => 0));

                    $update1 = $this->common_model->update_status('education_result', array('verfstatus' => 1), array('education_id' => $value));

                    $get_username = $this->education_model->get_reporting_manager_id();
                    $get_vendorname = $this->education_model->get_vendor_details(array('id'=>$frm_details['vendor_list']));
                    if ($update) {

                        $insert_counter++;
                        $files[] = array(
                            'vendor_id' => $frm_details['vendor_list'],
                            'case_id' => $value,
                            "status" => 0,
                            "remarks" => '',
                            "created_by" => $this->user_info['id'],
                            "created_on" => date(DB_DATE_FORMAT),
                            "approval_by" => 0,
                            "modified_on" => null,
                            "modified_by" => '',
                        );

                      $activity[]  =  array(
                            'comp_table_id' => $value,
                            'activity_status' => 'Assign',
                            "activity_type" => 'Mist it',
                            "remarks" => $get_username[0]['user_name'].' has assigned the case to '.$get_vendorname[0]['vendor_name'],
                            "created_by" => $this->user_info['id'],
                            "created_on" => date(DB_DATE_FORMAT),
                            "is_auto_filled" => 0
                            
                        );
                    }

                }
                if (!empty($files)) {
                    $inserted = $this->common_model->common_insert_batch('education_vendor_log', $files);
                    $activity_insert = $this->common_model->common_insert_batch('education_activity_data', $activity);
                }

                $json_array['status'] = SUCCESS_CODE;
                $json_array['message'] = $insert_counter . " of " . count($list) . " Assigned Successfully";

            } else {
                $json_array['status'] = ERROR_CODE;
                $json_array['message'] = "Select atleast one case";
            }

        } else {

            $json_array['status'] = ERROR_CODE;
            $json_array['message'] = "Access Denied, You don’t have permission to access this page";
        }
        echo_json($json_array);
    }

    public function assign_to_spoc()
    {
        $json_array = array();

            $frm_details = $this->input->post();
            $list = explode(',', $frm_details['cases_id']);

            if ($frm_details['vendor_list'] != 0 && $frm_details['cases_id'] != "" && !empty($list)) {
                $files = $update = $activity = array();
                $insert_counter = 0;
                foreach ($list as $key => $value) {

                    if($frm_details['vendor_list'] != 20)
                    {
                    
                        $education_id =  $this->education_model->select_education('education_vendor_log', array('case_id'), array('id' => $value));

                        $update_education = $this->education_model->update_education_vendor_log('education', array('vendor_id' => $frm_details['vendor_list'],'verifiers_spoc_status' => 2), array('id' => $education_id[0]['case_id']));

                        $update = $this->education_model->update_education_vendor_log('education_vendor_log', array('vendor_id' => $frm_details['vendor_list'],'modified_on'=> date(DB_DATE_FORMAT)), array('id' => $value));

                        $get_username = $this->education_model->get_reporting_manager_id();
                        $get_vendorname = $this->education_model->get_vendor_details(array('id'=>$frm_details['vendor_list']));
                          
                        $activity[]  =  array(
                                'comp_table_id' => $education_id[0]['case_id'],
                                'activity_status' => 'Assign',
                                "activity_type" => 'Mist it',
                                "remarks" => $get_username[0]['user_name'].' has assigned the case to spoc vendor '.$get_vendorname[0]['vendor_name'],
                                "created_by" => $this->user_info['id'],
                                "created_on" => date(DB_DATE_FORMAT),
                                "is_auto_filled" => 0
                                
                            );
                      
                        $activity_insert = $this->common_model->common_insert_batch('education_activity_data', $activity);
                    }

                    else{

                        $json_array['status'] = ERROR_CODE;
                        $json_array['message'] = "Select Please Select Spoc Vendor";
                    }
                }

                $json_array['status'] = SUCCESS_CODE;
                $json_array['message'] = "Case Assigned Successfully";

            } else {
                $json_array['status'] = ERROR_CODE;
                $json_array['message'] = "Select atleast one case";
            }

        
        echo_json($json_array);
    }

    public function assign_to_spoc_bulk()
    {
        $json_array = array();

            $frm_details = $this->input->post();

            $education_com_ref  =  trim($frm_details['education_id']);
 
            $input_value = explode('EDU', $education_com_ref);

            $list = array();
            foreach ($input_value as $key => $value) {
              $list[] = "EDU".$value;
            } 
           
            array_shift($list);
          
            if ($frm_details['vendor_list'] != 0) {

                if ($frm_details['vendor_list'] != 20) {

                    if(!empty($list))
                    {
                        $all_record_verifiers_queue = $this->education_model->select_all_verifiers_queue(array('education_vendor_log.status' => 0));
                        $all_record_verifiers_queue = array_map('current', $all_record_verifiers_queue);
                    
                        $files = $update = $activity =  $edu_com_ref = array();
                        $insert_counter = 0;
                        foreach ($list as $key => $value) {
                           $value = trim($value);      
                            if(in_array($value, $all_record_verifiers_queue))
                            {
                            
                                $education_id =  $this->education_model->select_education('education', array('id'), array('education_com_ref' => $value)); 
                                
                                $update_education = $this->education_model->update_education_vendor_log('education', array('vendor_id' => $frm_details['vendor_list'],'verifiers_spoc_status' => 2), array('id' => $education_id[0]['id']));

                                $update = $this->education_model->update_education_vendor_log('education_vendor_log', array('vendor_id' => $frm_details['vendor_list'],'modified_on'=> date(DB_DATE_FORMAT)), array('case_id' => $education_id[0]['id']));

                                $get_username = $this->education_model->get_reporting_manager_id();
                                $get_vendorname = $this->education_model->get_vendor_details(array('id'=>$frm_details['vendor_list']));
                                  
                                $activity  =  array(
                                        'comp_table_id' => $education_id[0]['id'],
                                        'activity_status' => 'Assign',
                                        "activity_type" => 'Mist it',
                                        "remarks" => $get_username[0]['user_name'].' has assigned the case to spoc vendor '.$get_vendorname[0]['vendor_name'],
                                        "created_by" => $this->user_info['id'],
                                        "created_on" => date(DB_DATE_FORMAT),
                                        "is_auto_filled" => 0
                                        
                                    );

                                $activity_insert = $this->education_model->initiated_date_education_activity_data($activity);
                              
                            }

                            if(!in_array($value, $all_record_verifiers_queue))
                            {
                               $edu_com_ref[] = $value; 
                            }
                        }
                       
                        $json_array['status'] = SUCCESS_CODE;
                        $json_array['message'] = "Case Assigned Successfully but ".implode(",", $edu_com_ref) . " not available";

                    } else {
                        $json_array['status'] = ERROR_CODE;
                        $json_array['message'] = "Please Insert Reference No";
                    }
                } else {
                    $json_array['status'] = ERROR_CODE;
                    $json_array['message'] = "Please Select Spoc Vendor";
                }
    
            } else {
                $json_array['status'] = ERROR_CODE;
                $json_array['message'] = "Please Select Spoc Vendor Name";
            }

        
        echo_json($json_array);
    }

    public function assign_to_stamp_bulk()
    {
        $json_array = array();

            $frm_details = $this->input->post();
            $education_com_ref  =  trim($frm_details['education_id']);
 
            $input_value = explode('EDU', $education_com_ref);

            $list = array();
            foreach ($input_value as $key => $value) {
              $list[] = "EDU".$value;
            } 
           
            array_shift($list);
       

                if(!empty($list))
                {
                    $all_record_verifiers_queue = $this->education_model->select_all_verifiers_queue(array('education_vendor_log.status' => 0));

                    $all_record_verifiers_queue = array_map('current', $all_record_verifiers_queue);
                
                    $files = $update = $activity =  $edu_com_ref = array();
                    $insert_counter = 0;
                    foreach ($list as $key => $value) {
                        $value = trim($value);

                        if(in_array($value, $all_record_verifiers_queue))
                        {
                           
                            $education_id =  $this->education_model->select_education('education', array('id'), array('education_com_ref' => $value));
                            
                            $education_vendor_log_id =  $this->education_model->select_education('education_vendor_log', array('id'), array('case_id' => $education_id[0]['id']));
     
                            $education_cands_id =  $this->education_model->select_education('education', array('candsid'), array('education_com_ref' => $value)); 
                           
                            $education_cands_id =  $this->education_model->select_education('candidates_info', array('clientid','entity','package'), array('id' => $education_cands_id[0]['candsid'])); 
                          
                            $mode_of_verification = $this->education_model->get_mode_of_verification(array('entity' =>$education_cands_id[0]['entity'], 'package' => $education_cands_id[0]['package'], 'tbl_clients_id' => $education_cands_id[0]['clientid']));
                             
                            if (!empty($mode_of_verification)) {
                                $mode_of_verification_value = json_decode($mode_of_verification[0]['mode_of_verification']);

                                $mode_of_veri = $mode_of_verification_value->eduver;
                            } else {

                                $mode_of_veri = "";
                            }
                            
                            if($mode_of_veri != "verbal")
                            {
                                if($frm_details['status_value'] == "insufficiency")
                                {
                                    $update = $this->education_model->upload_vendor_assign('education_vendor_log', array('status' => 1, 'approval_by' => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT)), array('case_id' =>  $education_id[0]['id']));
                                    if ($update) {


                                    $field_array = array('component' => 'eduver',
                                                'component_tbl_id' => '3',
                                                'case_id' => $education_vendor_log_id[0]['id'],
                                                'trasaction_id' => 'Txn',
                                                "status" => 1,
                                                "final_status" => $frm_details['status_value'],
                                                "vendor_remark" => $frm_details['vendor_remark'],
                                                "approval_by" => $this->user_info['id'],
                                                "approval_on" => date(DB_DATE_FORMAT),
                                                "created_by" => $this->user_info['id'],
                                                "created_on" => date(DB_DATE_FORMAT),
                                                "vendor_status" => 0,
                                                "modified_on" => date(DB_DATE_FORMAT),
                                                "modified_by" => $this->user_info['id'],
                                            );

                                    $insert_id = $this->common_model->common_insert_transaction_no('view_vendor_master_log', $field_array);
                                    $update_transaction_id = $this->common_model->update_transaction_id(array('trasaction_id' => "Txn" . $insert_id), array('id' => $insert_id)); 
                                }

                                    $get_username = $this->education_model->get_reporting_manager_id();


                                    $activity  =  array(
                                                'comp_table_id' => $education_id[0]['id'],
                                                'activity_status' => 'Approve',
                                                "activity_type" => 'Mist it',
                                                "remarks" => $get_username[0]['user_name'].' has provided status as '.$frm_details['status_value'].' with remarks as '.$frm_details['vendor_remark'],
                                                "created_by" => $this->user_info['id'],
                                                "created_on" => date(DB_DATE_FORMAT),
                                                "is_auto_filled" => 0
                                                
                                            );

                                    $activity_insert = $this->education_model->initiated_date_education_activity_data($activity); 

                      
                                }
                                if($frm_details['status_value'] != "insufficiency")
                                {

                                    $this->load->model('education_vendor_log_model');

                                    $update = $this->education_model->upload_vendor_assign('education_vendor_log', array('status' => 1, 'approval_by' => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT)), array('case_id' =>  $education_id[0]['id']));


                                    if ($update) {

                                        $field_array = array('component' => 'eduver',
                                            'component_tbl_id' => '3',
                                            'case_id' => $education_vendor_log_id[0]['id'],
                                            'trasaction_id' => 'Txn',
                                            "status" => 1,
                                            "final_status" => "approve",
                                            "vendor_remark" =>$frm_details['vendor_remark'],
                                            "remarks" => $frm_details['status_value'],
                                            "approval_by" => $this->user_info['id'],
                                            "approval_on" => date(DB_DATE_FORMAT),
                                            "created_by" => $this->user_info['id'],
                                            "created_on" => date(DB_DATE_FORMAT),
                                            "vendor_status" => 0,
                                            "modified_on" => date(DB_DATE_FORMAT),
                                            "modified_by" => $this->user_info['id'],
                                        );

                                        $checking_id = $this->education_model->check_transaction_exit_or_not('view_vendor_master_log',array('id'),array('case_id'=>$education_vendor_log_id[0]['id'],'component_tbl_id' => 3));
                                        if(!empty($checking_id))
                                        { 
                                            $update_transation = $this->education_model->update_closure_approval_details('view_vendor_master_log', $field_array,array('id'=>  $checking_id[0]['id'],'component_tbl_id' => 3)); 
                                            $insert_id =  $checking_id[0]['id'];

                                            $update_transaction_id = $this->common_model->update_transaction_id(array('trasaction_id' => "Txn" . $insert_id), array('id' => $insert_id));

                                        }
                                        else{

                                            $insert_id = $this->common_model->common_insert_transaction_no('view_vendor_master_log', $field_array);
                                            $update_transaction_id = $this->common_model->update_transaction_id(array('trasaction_id' => "Txn" . $insert_id), array('id' => $insert_id));
                                        }
                                    }
                                

                                    $fields1 = array('vendor_stamp_id'=>$frm_details['vendor_list']);

                                    $update_verifier_stamp = $this->education_model->save($fields1,array('id'=>$education_id[0]['id']));   

                                    $get_username = $this->education_model->get_reporting_manager_id();

                                    $get_vendorname = $this->education_model->get_vendor_details(array('id'=>$frm_details['vendor_list']));
              

                                    $stamp_vendor_details = array(
                                            'vendor_id' => $frm_details['vendor_list'],
                                            'case_id' =>  $education_id[0]['id'],
                                            "status" => 1,
                                            "remarks" => '',
                                            "created_by" => $this->user_info['id'],
                                            "created_on" => date(DB_DATE_FORMAT),
                                            "approval_by" => 0,
                                            "modified_on" => null,
                                            "modified_by" => '',
                                        );

                                    $activity  =  array(
                                        'comp_table_id' => $education_id[0]['id'],
                                        'activity_status' => 'Assign',
                                        "activity_type" => 'Mist it',
                                        "remarks" => $get_username[0]['user_name'].' has provided status as '.$frm_details['status_value'].' with remarks as '.$frm_details['vendor_remark'].' and assigned to '.$get_vendorname[0]['vendor_name'],
                                        "created_by" => $this->user_info['id'],
                                        "created_on" => date(DB_DATE_FORMAT),
                                        "is_auto_filled" => 0
                                        
                                    );
                                
                                    $inserted = $this->education_model->insert_education_vendor_log('education_vendor_log', $stamp_vendor_details);
                                    $activity_insert = $this->education_model->initiated_date_education_activity_data($activity);


                                    $field_array = array('component' => 'eduver',
                                        'component_tbl_id' => '3',
                                        'case_id' => $inserted,
                                        'trasaction_id' => 'Txn',
                                        "status" => 1,
                                        "final_status" => 'wip',
                                        "vendor_remark" => $frm_details['vendor_remark'],
                                        "remarks" => $frm_details['status_value'],
                                        "approval_by" => $this->user_info['id'],
                                        "approval_on" => date(DB_DATE_FORMAT),
                                        "created_by" => $this->user_info['id'],
                                        "created_on" => date(DB_DATE_FORMAT),
                                        "vendor_status" => 0,
                                        "modified_on" =>  date(DB_DATE_FORMAT),
                                        "modified_by" =>  $this->user_info['id'],
                                    );

                                    $insert_id = $this->common_model->common_insert_transaction_no('view_vendor_master_log', $field_array);
                                    $update_transaction_id = $this->common_model->update_transaction_id(array('trasaction_id' => "Txn" . $insert_id), array('id' => $insert_id));

                            
                                }
                            }
                        }

                        if(!in_array($value, $all_record_verifiers_queue))
                        {
                           $edu_com_ref[] = $value; 
                        }
                    }
                   
                    $json_array['status'] = SUCCESS_CODE;
                    $json_array['message'] = "Case Assigned Successfully but ".implode(",", $edu_com_ref) . " not available";

                } else {
                    $json_array['status'] = ERROR_CODE;
                    $json_array['message'] = "Please Insert Reference No";
                }

     
        echo_json($json_array);
    }

    public function assign_to_stamp_spoc_bulk()
    {
        $json_array = array();

            $frm_details = $this->input->post();
            $education_com_ref  =  trim($frm_details['education_id']);
 
            $input_value = explode('EDU', $education_com_ref);

            $list = array();
            foreach ($input_value as $key => $value) {
              $list[] = "EDU".$value;
            } 
           
            array_shift($list);
       

                if(!empty($list))
                {
                    $all_record_verifiers_queue = $this->education_model->select_all_spoc_verifiers_queue(array('education_vendor_log.status' => 0));

                    $all_record_verifiers_queue = array_map('current', $all_record_verifiers_queue);
                
                    $files = $update = $activity =  $edu_com_ref = array();
                    $insert_counter = 0;
                    foreach ($list as $key => $value) {
                        $value = trim($value);
                       
                        if(in_array($value, $all_record_verifiers_queue))
                        {
                           
                            $education_id =  $this->education_model->select_education('education', array('id'), array('education_com_ref' => $value));
                            
                            $education_vendor_log_id =  $this->education_model->select_education('education_vendor_log', array('id'), array('case_id' => $education_id[0]['id']));
     
                            $education_cands_id =  $this->education_model->select_education('education', array('candsid'), array('education_com_ref' => $value)); 
                           
                            $education_cands_id =  $this->education_model->select_education('candidates_info', array('clientid','entity','package'), array('id' => $education_cands_id[0]['candsid'])); 
                          
                            $mode_of_verification = $this->education_model->get_mode_of_verification(array('entity' =>$education_cands_id[0]['entity'], 'package' => $education_cands_id[0]['package'], 'tbl_clients_id' => $education_cands_id[0]['clientid']));
                             
                            if (!empty($mode_of_verification)) {
                                $mode_of_verification_value = json_decode($mode_of_verification[0]['mode_of_verification']);

                                $mode_of_veri = $mode_of_verification_value->eduver;
                            } else {

                                $mode_of_veri = "";
                            }
                            
                            if($mode_of_veri != "verbal")
                            {
                                if($frm_details['status_value'] == "insufficiency")
                                {
                                    $update = $this->education_model->upload_vendor_assign('education_vendor_log', array('status' => 1, 'approval_by' => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT)), array('case_id' =>  $education_id[0]['id']));
                                    if ($update) {


                                    $field_array = array('component' => 'eduver',
                                                'component_tbl_id' => '3',
                                                'case_id' => $education_vendor_log_id[0]['id'],
                                                'trasaction_id' => 'Txn',
                                                "status" => 1,
                                                "final_status" => $frm_details['status_value'],
                                                "vendor_remark" => $frm_details['vendor_remark'],
                                                "approval_by" => $this->user_info['id'],
                                                "approval_on" => date(DB_DATE_FORMAT),
                                                "created_by" => $this->user_info['id'],
                                                "created_on" => date(DB_DATE_FORMAT),
                                                "vendor_status" => 0,
                                                "modified_on" => date(DB_DATE_FORMAT),
                                                "modified_by" => $this->user_info['id'],
                                            );

                                    $insert_id = $this->common_model->common_insert_transaction_no('view_vendor_master_log', $field_array);
                                    $update_transaction_id = $this->common_model->update_transaction_id(array('trasaction_id' => "Txn" . $insert_id), array('id' => $insert_id)); 
                                }

                                    $get_username = $this->education_model->get_reporting_manager_id();


                                    $activity  =  array(
                                                'comp_table_id' => $education_id[0]['id'],
                                                'activity_status' => 'Approve',
                                                "activity_type" => 'Mist it',
                                                "remarks" => $get_username[0]['user_name'].' has provided status as '.$frm_details['status_value'].' with remarks as '.$frm_details['vendor_remark'],
                                                "created_by" => $this->user_info['id'],
                                                "created_on" => date(DB_DATE_FORMAT),
                                                "is_auto_filled" => 0
                                                
                                            );

                                    $activity_insert = $this->education_model->initiated_date_education_activity_data($activity); 

                      
                                }
                                if($frm_details['status_value'] != "insufficiency")
                                {

                                    $this->load->model('education_vendor_log_model');

                                    $update = $this->education_model->upload_vendor_assign('education_vendor_log', array('status' => 1, 'approval_by' => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT)), array('case_id' =>  $education_id[0]['id']));


                                    if ($update) {

                                        $field_array = array('component' => 'eduver',
                                            'component_tbl_id' => '3',
                                            'case_id' => $education_vendor_log_id[0]['id'],
                                            'trasaction_id' => 'Txn',
                                            "status" => 1,
                                            "final_status" => "approve",
                                            "vendor_remark" =>$frm_details['vendor_remark'],
                                            "remarks" => $frm_details['status_value'],
                                            "approval_by" => $this->user_info['id'],
                                            "approval_on" => date(DB_DATE_FORMAT),
                                            "created_by" => $this->user_info['id'],
                                            "created_on" => date(DB_DATE_FORMAT),
                                            "vendor_status" => 0,
                                            "modified_on" => date(DB_DATE_FORMAT),
                                            "modified_by" => $this->user_info['id'],
                                        );

                                        $checking_id = $this->education_model->check_transaction_exit_or_not('view_vendor_master_log',array('id'),array('case_id'=>$education_vendor_log_id[0]['id'],'component_tbl_id' => 3));
                                        if(!empty($checking_id))
                                        { 
                                            $update_transation = $this->education_model->update_closure_approval_details('view_vendor_master_log', $field_array,array('id'=>  $checking_id[0]['id'],'component_tbl_id' => 3)); 
                                            $insert_id =  $checking_id[0]['id'];

                                            $update_transaction_id = $this->common_model->update_transaction_id(array('trasaction_id' => "Txn" . $insert_id), array('id' => $insert_id));

                                        }
                                        else{

                                            $insert_id = $this->common_model->common_insert_transaction_no('view_vendor_master_log', $field_array);
                                            $update_transaction_id = $this->common_model->update_transaction_id(array('trasaction_id' => "Txn" . $insert_id), array('id' => $insert_id));
                                        }
                                    }
                                

                                    $fields1 = array('vendor_stamp_id'=>$frm_details['vendor_list']);

                                    $update_verifier_stamp = $this->education_model->save($fields1,array('id'=>$education_id[0]['id']));   

                                    $get_username = $this->education_model->get_reporting_manager_id();

                                    $get_vendorname = $this->education_model->get_vendor_details(array('id'=>$frm_details['vendor_list']));
              

                                    $stamp_vendor_details = array(
                                            'vendor_id' => $frm_details['vendor_list'],
                                            'case_id' =>  $education_id[0]['id'],
                                            "status" => 1,
                                            "remarks" => '',
                                            "created_by" => $this->user_info['id'],
                                            "created_on" => date(DB_DATE_FORMAT),
                                            "approval_by" => 0,
                                            "modified_on" => null,
                                            "modified_by" => '',
                                        );

                                    $activity  =  array(
                                        'comp_table_id' => $education_id[0]['id'],
                                        'activity_status' => 'Assign',
                                        "activity_type" => 'Mist it',
                                        "remarks" => $get_username[0]['user_name'].' has provided status as '.$frm_details['status_value'].' with remarks as '.$frm_details['vendor_remark'].' and assigned to '.$get_vendorname[0]['vendor_name'],
                                        "created_by" => $this->user_info['id'],
                                        "created_on" => date(DB_DATE_FORMAT),
                                        "is_auto_filled" => 0
                                        
                                    );
                                
                                    $inserted = $this->education_model->insert_education_vendor_log('education_vendor_log', $stamp_vendor_details);
                                    $activity_insert = $this->education_model->initiated_date_education_activity_data($activity);


                                    $field_array = array('component' => 'eduver',
                                        'component_tbl_id' => '3',
                                        'case_id' => $inserted,
                                        'trasaction_id' => 'Txn',
                                        "status" => 1,
                                        "final_status" => 'wip',
                                        "vendor_remark" => $frm_details['vendor_remark'],
                                        "remarks" => $frm_details['status_value'],
                                        "approval_by" => $this->user_info['id'],
                                        "approval_on" => date(DB_DATE_FORMAT),
                                        "created_by" => $this->user_info['id'],
                                        "created_on" => date(DB_DATE_FORMAT),
                                        "vendor_status" => 0,
                                        "modified_on" =>  date(DB_DATE_FORMAT),
                                        "modified_by" =>  $this->user_info['id'],
                                    );

                                    $insert_id = $this->common_model->common_insert_transaction_no('view_vendor_master_log', $field_array);
                                    $update_transaction_id = $this->common_model->update_transaction_id(array('trasaction_id' => "Txn" . $insert_id), array('id' => $insert_id));

                            
                                }
                            }
                        }

                        if(!in_array($value, $all_record_verifiers_queue))
                        {
                           $edu_com_ref[] = $value; 
                        }
                    }
                   
                    $json_array['status'] = SUCCESS_CODE;
                    $json_array['message'] = "Case Assigned Successfully but ".implode(",", $edu_com_ref) . " not available";

                } else {
                    $json_array['status'] = ERROR_CODE;
                    $json_array['message'] = "Please Insert Reference No";
                }

     
        echo_json($json_array);
    }
    
    

    public function assign_to_stamp()
    {
        $json_array = array();

            $frm_details = $this->input->post();
     
            $list = explode(',', $frm_details['cases_id']);

            if ($frm_details['vendor_list'] != 0 && $frm_details['cases_id'] != "" && !empty($list)) {
                $files = $update = $activity = array();
                foreach ($list as $key => $value) {

                    $update_education = $this->education_model->update_education_vendor_log('education', array('vendor_stamp_id' => $frm_details['vendor_list']), array('id' => $value));

                    $education_id =  $this->education_model->select_education('education_vendor_log', array('id'), array('case_id' => $value));
                    
                    if(!empty($education_id[1]['id']))
                    {
                        $update = $this->education_model->update_education_vendor_log('education_vendor_log', array('vendor_id' => $frm_details['vendor_list'],'modified_on'=> date(DB_DATE_FORMAT)), array('id' => $education_id[1]['id']));

                        $update_modified_date_transaction = $this->education_model->update_education_vendor_log('view_vendor_master_log', array('modified_on'=> date(DB_DATE_FORMAT)), array('case_id' => $education_id[1]['id'],'component' => 'eduver', 'component_tbl_id' => 3));
                    }

                    $get_username = $this->education_model->get_reporting_manager_id();
                    $get_vendorname = $this->education_model->get_vendor_details(array('id'=>$frm_details['vendor_list']));
                      
                    $activity[]  =  array(
                            'comp_table_id' => $value,
                            'activity_status' => 'Assign',
                            "activity_type" => 'Mist it',
                            "remarks" => $get_username[0]['user_name'].' has reassigned the case to Stamp vendor '.$get_vendorname[0]['vendor_name'],
                            "created_by" => $this->user_info['id'],
                            "created_on" => date(DB_DATE_FORMAT),
                            "is_auto_filled" => 0
                            
                        );
                  
                   
                }

                $activity_insert = $this->common_model->common_insert_batch('education_activity_data', $activity);

                $json_array['status'] = SUCCESS_CODE;
                $json_array['message'] = "Case Assigned Successfully";

            } else {
                $json_array['status'] = ERROR_CODE;
                $json_array['message'] = "Select atleast one case";
            }

        
        echo_json($json_array);
    }

    public function assign_to_spoc_reinitiated()
    {
        $json_array = array();

            $frm_details = $this->input->post();
    
            $list = explode(',', $frm_details['cases_id']);

            if ($frm_details['vendor_list'] != 0 && $frm_details['cases_id'] != "" && !empty($list)) {
                $files = $update = $activity = array();
                foreach ($list as $key => $value) {

                    $update_education = $this->education_model->update_education_vendor_log('education', array('vendor_id' => $frm_details['vendor_list']), array('id' => $value));

                    if($frm_details['vendor_list'] == 20)
                    {

                        $update_education = $this->education_model->update_education_vendor_log('education', array('verifiers_spoc_status' => 1), array('id' => $value));
                    }

                    $education_id =  $this->education_model->select_education('education_vendor_log', array('id'), array('case_id' => $value));
                    
                    if(!empty($education_id[0]['id']))
                    {
                        $update = $this->education_model->update_education_vendor_log('education_vendor_log', array('vendor_id' => $frm_details['vendor_list'],'modified_on'=> date(DB_DATE_FORMAT)), array('id' => $education_id[0]['id']));

                    }

                    $get_username = $this->education_model->get_reporting_manager_id();
                    $get_vendorname = $this->education_model->get_vendor_details(array('id'=>$frm_details['vendor_list']));
                      
                    $activity[]  =  array(
                            'comp_table_id' => $value,
                            'activity_status' => 'Assign',
                            "activity_type" => 'Mist it',
                            "remarks" => $get_username[0]['user_name'].' has reassigned the case to Spoc vendor '.$get_vendorname[0]['vendor_name'],
                            "created_by" => $this->user_info['id'],
                            "created_on" => date(DB_DATE_FORMAT),
                            "is_auto_filled" => 0
                            
                        );
                  
                    $activity_insert = $this->common_model->common_insert_batch('education_activity_data', $activity);
                }

                $json_array['status'] = SUCCESS_CODE;
                $json_array['message'] = "Case Assigned Successfully";

            } else {
                $json_array['status'] = ERROR_CODE;
                $json_array['message'] = "Select atleast one case";
            }

        
        echo_json($json_array);
    }

    public function remove_uploaded_file($id)
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {
            $result = $this->education_model->save_update_education_files(array('status' => 2), array('id' => $id));

            if ($result) {
                $json_array['status'] = SUCCESS_CODE;

                $json_array['message'] = 'Attachment removed successfully, please refresh the page';
            } else {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = 'Something went worong, please try again';
            }
            echo_json($json_array);
        }
    }

    public function education_final_assigning()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {
            $frm_details = $this->input->post();
            $action = $frm_details['action'];

            if ($frm_details['action'] == 2 && $frm_details['cases_id'] != "") {
                $list = explode(',', $frm_details['cases_id']);
                $update_counter = 0;
                foreach ($list as $key => $value) {

                    $update = $this->education_model->upload_vendor_assign('education_vendor_log', array('status' => 2, 'remarks' => $frm_details['reject_reason'], 'modified_by' => $this->user_info['id'], 'modified_on' => date(DB_DATE_FORMAT)), array('id' => $value));

                    if ($update) {
                        $update_counter++;
                        //$return =  $this->education_model->save(array('vendor_id' => 0,'vendor_assgined_on' => NULL),array('id' => $value));
                    }
                }

                $result_get_count_plus = $this->common_model->get_count(array('controllers' => 'assign_edu'));

                $total_get_count_plus = $result_get_count_plus + count($list);

                $result_update_count_plus = $this->common_model->update_count(array('count' => $total_get_count_plus), array('controllers' => 'assign_edu'));

                $result_get_count_minus = $this->common_model->get_count(array('controllers' => 'education/approval_queue'));

                $total_get_count_minus = $result_get_count_minus - count($list);

                $result_update_count_minus = $this->common_model->update_count(array('count' => $total_get_count_minus), array('controllers' => 'education/approval_queue'));

                if ($update_counter) {

                    $json_array['status'] = SUCCESS_CODE;
                    $json_array['message'] = $update_counter . " of " . count($list) . " Rejected Successfully";

                } else {
                    $json_array['status'] = ERROR_CODE;
                    $json_array['message'] = "Something went wrong,please try again";
                }

            } else if ($frm_details['action'] == 1 && $frm_details['cases_id'] != "") {

                $list = explode(',', $frm_details['cases_id']);
                $update_counter = 0;

                foreach ($list as $key => $value) {

                    $update = $this->education_model->upload_vendor_assign('education_vendor_log', array('status' => 1, 'approval_by' => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT)), array('id' => $value));

                    if ($update) {

                        $update_counter++;

                        $field_array = array('component' => 'eduver',
                            'component_tbl_id' => '3',
                            'case_id' => $value,
                            'trasaction_id' => 'Txn',
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

                        $insert_id = $this->common_model->common_insert_transaction_no('view_vendor_master_log', $field_array);
                        $update_transaction_id = $this->common_model->update_transaction_id(array('trasaction_id' => "Txn" . $insert_id), array('id' => $insert_id));
                    }

                }
                $result_get_count_plus = $this->common_model->get_count(array('controllers' => 'education'));

                $total_get_count_plus = $result_get_count_plus + count($list);

                $result_update_count_plus = $this->common_model->update_count(array('count' => $total_get_count_plus), array('controllers' => 'education'));

                $result_get_count_minus = $this->common_model->get_count(array('controllers' => 'education/approval_queue'));

                $total_get_count_minus = $result_get_count_minus - count($list);

                $result_update_count_minus = $this->common_model->update_count(array('count' => $total_get_count_minus), array('controllers' => 'education/approval_queue'));

                $json_array['status'] = SUCCESS_CODE;
                $json_array['message'] = $update_counter . " of " . count($list) . " Assigned Successfully";

            } else {
                $json_array['status'] = ERROR_CODE;
                $json_array['message'] = "Select atleast one case";
            }

        } else {

            $json_array['status'] = ERROR_CODE;
            $json_array['message'] = "Access Denied, You don’t have permission to access this page";
        }
        echo_json($json_array);
    }

    public function assign_stamp_verifiers_details()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {
            $frm_details = $this->input->post();
        

            if($frm_details['cases_assgin'] == "insufficiency")
            {
                $field_array = array('component' => 'eduver',
                            'component_tbl_id' => '3',
                            'case_id' => $frm_details['education_case_id_stamp'],
                            'trasaction_id' => 'Txn',
                            "status" => 1,
                            "final_status" => $frm_details['cases_assgin'],
                            "vendor_remark" => $frm_details['remark_verifiers_stamp'],
                            "approval_by" => $this->user_info['id'],
                            "approval_on" => date(DB_DATE_FORMAT),
                            "created_by" => $this->user_info['id'],
                            "created_on" => date(DB_DATE_FORMAT),
                            "vendor_status" => 0,
                            "modified_on" => date(DB_DATE_FORMAT),
                            "modified_by" => $this->user_info['id'],
                        );

                $insert_id = $this->common_model->common_insert_transaction_no('view_vendor_master_log', $field_array);
                $update_transaction_id = $this->common_model->update_transaction_id(array('trasaction_id' => "Txn" . $insert_id), array('id' => $insert_id)); 

                $get_username = $this->education_model->get_reporting_manager_id();


                $activity  =  array(
                            'comp_table_id' => $frm_details['verifiers_stamp_id'],
                            'activity_status' => 'Approve',
                            "activity_type" => 'Mist it',
                            "remarks" => $get_username[0]['user_name'].' has provided status as '.$frm_details['cases_assgin'].' with remarks as '.$frm_details['remark_verifiers_stamp'],
                            "created_by" => $this->user_info['id'],
                            "created_on" => date(DB_DATE_FORMAT),
                            "is_auto_filled" => 0
                            
                        );

                        $activity_insert = $this->education_model->initiated_date_education_activity_data($activity); 

                    if ($insert_id) {
                        $json_array['status'] = SUCCESS_CODE;
                        $json_array['message'] = "Successfully Submitted record";
                    } else {
                        $json_array['status'] = ERROR_CODE;
                        $json_array['message'] = "Something went wrong,please try again";
                    }   
            }
            else{

                if($frm_details['mode_veri'] == "verbal")
                {   
                    $update = $this->education_model->upload_vendor_assign('education_vendor_log', array('status' => 1, 'approval_by' => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT)), array('id' => $frm_details['education_case_id_stamp']));

                    $field_array = array('component' => 'eduver',
                        'component_tbl_id' => '3',
                        'case_id' => $frm_details['education_case_id_stamp'],
                        'trasaction_id' => 'Txn',
                        "status" => 1,
                        "final_status" => $frm_details['cases_assgin'],
                        "remarks" => $frm_details['cases_assgin'],
                        "vendor_remark" => $frm_details['remark_verifiers_stamp'],
                        "approval_by" => $this->user_info['id'],
                        "approval_on" => date(DB_DATE_FORMAT),
                        "created_by" => $this->user_info['id'],
                        "created_on" => date(DB_DATE_FORMAT),
                        "vendor_status" => 0,
                        "modified_on" => date(DB_DATE_FORMAT),
                        "modified_by" => $this->user_info['id'],
                    );
                    $checking_id = $this->education_model->check_transaction_exit_or_not('view_vendor_master_log',array('id'),array('case_id'=>$frm_details['education_case_id_stamp'],'component_tbl_id' => 3));
                    if(!empty($checking_id))
                    { 
                        $update_transation = $this->education_model->update_closure_approval_details('view_vendor_master_log', $field_array,array('id'=>  $checking_id[0]['id'],'component_tbl_id' => 3)); 
                        $insert_id =  $checking_id[0]['id'];

                        $update_transaction_id = $this->common_model->update_transaction_id(array('trasaction_id' => "Txn" . $insert_id), array('id' => $insert_id));

                    }
                    else{

                        $insert_id = $this->common_model->common_insert_transaction_no('view_vendor_master_log', $field_array);
                        $update_transaction_id = $this->common_model->update_transaction_id(array('trasaction_id' => "Txn" . $insert_id), array('id' => $insert_id));

                    }

                    $folder_name = "vendor_file";
                    $file_upload_path = SITE_BASE_PATH.EDUCATION.$folder_name;
                    if(!folder_exist($file_upload_path)) {
                        mkdir($file_upload_path,0777);
                    }else if(!is_writable($file_upload_path)) {
                        array_push($error_msgs,'Problem while uploading');
                    } 


                    $config_array = array('file_upload_path' => $file_upload_path,
                                        'file_permission' => 'jpeg|jpg|png|pdf|tiff',
                                        'file_size' => BULK_UPLOAD_MAX_SIZE_MB*2000,
                                        'view_venor_master_log_id' => $insert_id,
                                        'component_tbl_id' => 3,
                                        'status' => 1 );
                        
                        if($_FILES['attchments_verifiers']['name'][0] != '')
                        {
                            $config_array['files_count'] = count($_FILES['attchments_verifiers']['name']);
                            $config_array['file_data'] = $_FILES['attchments_verifiers'];
                                
                            $retunr_de = $this->file_upload_library->file_upload_multiple_vendor($config_array, true); 
                            if(!empty($retunr_de['success'])) {
                                $this->common_model->common_insert_batch('view_vendor_master_log_file',$retunr_de['success']);
                            }   
                        }
             
                    if ($insert_id) {
                        $json_array['status'] = SUCCESS_CODE;
                        $json_array['message'] = "Successfully Submitted record";
                    } else {
                        $json_array['status'] = ERROR_CODE;
                        $json_array['message'] = "Something went wrong,please try again";
                    }

                }
                else
                {

                    if($frm_details['vendor_list_stamp'] == "no")
                    {
                        $this->form_validation->set_rules('verifiers_stamp_id', 'Id', 'required');

                        if(empty($_FILES['attchments_verifiers']['name'][0])  && empty($_POST['attchments_verifiers']))
                        {

                           $this->form_validation->set_rules('attchments_verifiers', 'Attachment file', 'required');
                        }
                     
                        if ($this->form_validation->run() == FALSE)
                        {
                        
                           $json_array['status'] = ERROR_CODE;

                           $json_array['message'] = validation_errors('','');
                        }
                        else
                        {
                         

                            $update = $this->education_model->upload_vendor_assign('education_vendor_log', array('status' => 1, 'approval_by' => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT)), array('id' => $frm_details['education_case_id_stamp']));

                            if ($update) {


                                $field_array = array('component' => 'eduver',
                                    'component_tbl_id' => '3',
                                    'case_id' => $frm_details['education_case_id_stamp'],
                                    'trasaction_id' => 'Txn',
                                    "status" => 1,
                                    "final_status" => $frm_details['cases_assgin'],
                                    "vendor_remark" => $frm_details['remark_verifiers_stamp'],
                                    "approval_by" => $this->user_info['id'],
                                    "approval_on" => date(DB_DATE_FORMAT),
                                    "created_by" => $this->user_info['id'],
                                    "created_on" => date(DB_DATE_FORMAT),
                                    "vendor_status" => 0,
                                    "modified_on" => date(DB_DATE_FORMAT),
                                    "modified_by" => $this->user_info['id'],
                                );
                             
                                $checking_id = $this->education_model->check_transaction_exit_or_not('view_vendor_master_log',array('id'),array('case_id'=>$frm_details['education_case_id_stamp'],'component_tbl_id' => 3));
                                
                                if(!empty($checking_id))
                                { 
                                   
                                    $update_transation = $this->education_model->update_closure_approval_details('view_vendor_master_log', $field_array,array('id'=>  $checking_id[0]['id'],'component_tbl_id' => 3)); 
                                    $insert_id =  $checking_id[0]['id'];

                                    $update_transaction_id = $this->common_model->update_transaction_id(array('trasaction_id' => "Txn" . $insert_id), array('id' => $insert_id));

                                }
                                else{

                                    $insert_id = $this->common_model->common_insert_transaction_no('view_vendor_master_log', $field_array);
                                    $update_transaction_id = $this->common_model->update_transaction_id(array('trasaction_id' => "Txn" . $insert_id), array('id' => $insert_id));

                                    $get_username = $this->education_model->get_reporting_manager_id();

                                    $select_education = $this->education_model->select(true,array('id','vendor_id'),array('id'=>$frm_details['verifiers_stamp_id']));
                                        
                                    $get_vendorname = $this->education_model->get_vendor_details(array('id'=>$select_education['vendor_id']));

                                    $activity  =  array(
                                        'comp_table_id' => $frm_details['verifiers_stamp_id'],
                                        'activity_status' => 'Approve',
                                        "activity_type" => 'Mist it',
                                        "remarks" => $get_username[0]['user_name'].' has provided status as '.$frm_details['cases_assgin'].' with remarks as '.$frm_details['remark_verifiers_stamp'],
                                       
                                        "created_by" => $this->user_info['id'],
                                        "created_on" => date(DB_DATE_FORMAT),
                                        "is_auto_filled" => 0
                                        
                                    );

                                    $activity_insert = $this->education_model->initiated_date_education_activity_data($activity);
                                }
                            }

                            $folder_name = "vendor_file";
                            $file_upload_path = SITE_BASE_PATH.EDUCATION.$folder_name;
                            if(!folder_exist($file_upload_path)) {
                                mkdir($file_upload_path,0777);
                            }else if(!is_writable($file_upload_path)) {
                                array_push($error_msgs,'Problem while uploading');
                            }


                            $config_array = array('file_upload_path' => $file_upload_path,
                                                 'file_permission' => 'jpeg|jpg|png|pdf|tiff',
                                                 'file_size' => BULK_UPLOAD_MAX_SIZE_MB*2000,
                                                 'view_venor_master_log_id' => $insert_id,
                                                 'component_tbl_id' => 3,
                                                 'status' => 1 );
                        
                            if($_FILES['attchments_verifiers']['name'][0] != '')
                            {
                                $config_array['files_count'] = count($_FILES['attchments_verifiers']['name']);
                                $config_array['file_data'] = $_FILES['attchments_verifiers'];
                                
                                $retunr_de = $this->file_upload_library->file_upload_multiple_vendor($config_array, true); 
                                if(!empty($retunr_de['success'])) {
                                    $this->common_model->common_insert_batch('view_vendor_master_log_file',$retunr_de['success']);
                                }   
                            }
             
                            if ($insert_id) {
                                $json_array['status'] = SUCCESS_CODE;
                                $json_array['message'] = "Successfully Submitted record";
                            } else {
                                $json_array['status'] = ERROR_CODE;
                                $json_array['message'] = "Something went wrong,please try again";
                            }
                        }  
                    }
                    else
                    { 

                        $this->load->model('education_vendor_log_model');

                        $update = $this->education_model->upload_vendor_assign('education_vendor_log', array('status' => 1, 'approval_by' => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT)), array('id' => $frm_details['education_case_id_stamp']));
                        
                            if ($update) {

                                $field_array = array('component' => 'eduver',
                                    'component_tbl_id' => '3',
                                    'case_id' => $frm_details['education_case_id_stamp'],
                                    'trasaction_id' => 'Txn',
                                    "status" => 1,
                                    "final_status" => "approve",
                                    "vendor_remark" => $frm_details['remark_verifiers_stamp'],
                                    "remarks" => $frm_details['cases_assgin'],
                                    "approval_by" => $this->user_info['id'],
                                    "approval_on" => date(DB_DATE_FORMAT),
                                    "created_by" => $this->user_info['id'],
                                    "created_on" => date(DB_DATE_FORMAT),
                                    "vendor_status" => 0,
                                    "modified_on" => date(DB_DATE_FORMAT),
                                    "modified_by" => $this->user_info['id'],
                                );

                                $checking_id = $this->education_model->check_transaction_exit_or_not('view_vendor_master_log',array('id'),array('case_id'=>$frm_details['education_case_id_stamp'],'component_tbl_id' => 3));
                                if(!empty($checking_id))
                                { 
                                    $update_transation = $this->education_model->update_closure_approval_details('view_vendor_master_log', $field_array,array('id'=>  $checking_id[0]['id'],'component_tbl_id' => 3)); 
                                    $insert_id =  $checking_id[0]['id'];

                                    $update_transaction_id = $this->common_model->update_transaction_id(array('trasaction_id' => "Txn" . $insert_id), array('id' => $insert_id));

                                }
                                else{

                                    $insert_id = $this->common_model->common_insert_transaction_no('view_vendor_master_log', $field_array);
                                    $update_transaction_id = $this->common_model->update_transaction_id(array('trasaction_id' => "Txn" . $insert_id), array('id' => $insert_id));

                                }

                            
                            }

                        $fields1 = array('vendor_stamp_id'=>$frm_details['vendor_list_stamp']);

                        $update_verifier_stamp = $this->education_model->save($fields1,array('id'=>$frm_details['verifiers_stamp_id']));   

                        $get_username = $this->education_model->get_reporting_manager_id();

                        $get_vendorname = $this->education_model->get_vendor_details(array('id'=>$frm_details['vendor_list_stamp']));
          

                            $stamp_vendor_details = array(
                                    'vendor_id' => $frm_details['vendor_list_stamp'],
                                    'case_id' => $frm_details['verifiers_stamp_id'],
                                    "status" => 1,
                                    "remarks" => '',
                                    "created_by" => $this->user_info['id'],
                                    "created_on" => date(DB_DATE_FORMAT),
                                    "approval_by" => 0,
                                    "modified_on" => null,
                                    "modified_by" => '',
                                );

                            $activity[]  =  array(
                                    'comp_table_id' => $frm_details['verifiers_stamp_id'],
                                    'activity_status' => 'Assign',
                                    "activity_type" => 'Mist it',
                                    "remarks" => $get_username[0]['user_name'].' has provided status as '.$frm_details['cases_assgin'].' with remarks as '.$frm_details['remark_verifiers_stamp'].' and assigned to '.$get_vendorname[0]['vendor_name'],
                                    "created_by" => $this->user_info['id'],
                                    "created_on" => date(DB_DATE_FORMAT),
                                    "is_auto_filled" => 0
                                    
                                );
                            
                            $inserted = $this->education_model->insert_education_vendor_log('education_vendor_log', $stamp_vendor_details);
                            $activity_insert = $this->common_model->common_insert_batch('education_activity_data', $activity);


                                $field_array = array('component' => 'eduver',
                                    'component_tbl_id' => '3',
                                    'case_id' => $inserted,
                                    'trasaction_id' => 'Txn',
                                    "status" => 1,
                                    "final_status" => 'wip',
                                    "vendor_remark" => $frm_details['remark_verifiers_stamp'],
                                    "remarks" => $frm_details['cases_assgin'],
                                    "approval_by" => $this->user_info['id'],
                                    "approval_on" => date(DB_DATE_FORMAT),
                                    "created_by" => $this->user_info['id'],
                                    "created_on" => date(DB_DATE_FORMAT),
                                    "vendor_status" => 0,
                                    "modified_on" =>  date(DB_DATE_FORMAT),
                                    "modified_by" =>  $this->user_info['id'],
                                );

                                $insert_id = $this->common_model->common_insert_transaction_no('view_vendor_master_log', $field_array);
                                $update_transaction_id = $this->common_model->update_transaction_id(array('trasaction_id' => "Txn" . $insert_id), array('id' => $insert_id));

                        if ($update_verifier_stamp) {
                            $json_array['status'] = SUCCESS_CODE;
                            $json_array['message'] = "Successfully Submitted record";
                        } else {
                            $json_array['status'] = ERROR_CODE;
                            $json_array['message'] = "Something went wrong,please try again";
                        }

                    }

                }
            }
           
        } else {

            $json_array['status'] = ERROR_CODE;
            $json_array['message'] = "Access Denied, You don’t have permission to access this page";
        }
        echo_json($json_array);
    }

    public function assign_url_details()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {

            $frm_details = $this->input->post();

            if(!empty($frm_details['education_url']))
            {

                if(!empty($frm_details['educ_id']))
                {
                    $edu_details =   $this->education_model->select_education('education',array('university_board','qualification','year_of_passing'),array('education.id'=>$frm_details['educ_id']));
                    
                    $check_exists =   $this->education_model->select_education('education_url_details',array('id'),array('university_name' => $edu_details[0]['university_board'],'qualification_name' => $edu_details[0]['qualification'],'year_of_passing' => $edu_details[0]['year_of_passing']));

                    if(empty($check_exists[0]))
                    {
                        $field_url_array = array('education_id' => $frm_details['educ_id'],
                                        'university_name' => $edu_details[0]['university_board'],
                                        'qualification_name' => $edu_details[0]['qualification'],
                                        'year_of_passing' => $edu_details[0]['year_of_passing'],
                                        'url' => $frm_details['education_url'],
                                        "status" => 1,
                                        "created_by" => $this->user_info['id'],
                                        "created_on" => date(DB_DATE_FORMAT),
                                    
                                    );

                        $save_url  = $this->education_model->save_vendor_details_costing('education_url_details',$field_url_array);    
                    }
                    else{
                        $update_url  = $this->education_model->save_vendor_details('education_url_details',array('url'=>$frm_details['education_url'], "modified_by" => $this->user_info['id'],"modified_on" => date(DB_DATE_FORMAT)), array('education_url_details.id'=>$check_exists[0]['id']));    
                    }
                    
                    $json_array['status'] = SUCCESS_CODE;
                    $json_array['message'] = "URL Submited Successfully";

                }else {

                    $json_array['status'] = ERROR_CODE;
                    $json_array['message'] = "Something went wrong";
                }  


            } else {

                $json_array['status'] = ERROR_CODE;
                $json_array['message'] = "Please Enter Education URL";
            }  
        } else {

            $json_array['status'] = ERROR_CODE;
            $json_array['message'] = "Access Denied, You don’t have permission to access this page";
        }
        echo_json($json_array);
    }

    public function assign_stamp_spoc_details()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {
            $frm_details = $this->input->post();

            if($frm_details['cases_assgin_spoc'] == "insufficiency")
            {
                $field_array = array('component' => 'eduver',
                            'component_tbl_id' => '3',
                            'case_id' => $frm_details['education_case_id_spoc'],
                            'trasaction_id' => 'Txn',
                            "status" => 1,
                            "final_status" => $frm_details['cases_assgin_spoc'],
                            "vendor_remark" => $frm_details['remark_verifiers_spoc'],
                            "approval_by" => $this->user_info['id'],
                            "approval_on" => date(DB_DATE_FORMAT),
                            "created_by" => $this->user_info['id'],
                            "created_on" => date(DB_DATE_FORMAT),
                            "vendor_status" => 0,
                            "modified_on" => date(DB_DATE_FORMAT),
                            "modified_by" => $this->user_info['id'],
                        );

                $insert_id = $this->common_model->common_insert_transaction_no('view_vendor_master_log', $field_array);
                $update_transaction_id = $this->common_model->update_transaction_id(array('trasaction_id' => "Txn" . $insert_id), array('id' => $insert_id)); 

                $get_username = $this->education_model->get_reporting_manager_id();


                $activity  =  array(
                            'comp_table_id' => $frm_details['verifiers_spoc_id'],
                            'activity_status' => 'Approve',
                            "activity_type" => 'Mist it',
                            "remarks" => $get_username[0]['user_name'].' has provided status as '.$frm_details['cases_assgin_spoc'].' with remarks as '.$frm_details['remark_verifiers_spoc'],
                            "created_by" => $this->user_info['id'],
                            "created_on" => date(DB_DATE_FORMAT),
                            "is_auto_filled" => 0
                            
                        );

                        $activity_insert = $this->education_model->initiated_date_education_activity_data($activity); 

                    if ($insert_id) {
                        $json_array['status'] = SUCCESS_CODE;
                        $json_array['message'] = "Successfully Submitted record";
                    } else {
                        $json_array['status'] = ERROR_CODE;
                        $json_array['message'] = "Something went wrong,please try again";
                    }   
            } 
            else{ 

                if($frm_details['mode_veri_spoc'] == "verbal")
                {   
                    $update = $this->education_model->upload_vendor_assign('education_vendor_log', array('status' => 1, 'approval_by' => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT)), array('id' => $frm_details['education_case_id_spoc']));

                    
                    $field_array = array('component' => 'eduver',
                        'component_tbl_id' => '3',
                        'case_id' => $frm_details['education_case_id_spoc'],
                        'trasaction_id' => 'Txn',
                        "status" => 1,
                        "final_status" => $frm_details['cases_assgin_spoc'],
                        "remarks" => $frm_details['cases_assgin_spoc'],
                        "vendor_remark" => $frm_details['remark_verifiers_spoc'],
                        "approval_by" => $this->user_info['id'],
                        "approval_on" => date(DB_DATE_FORMAT),
                        "created_by" => $this->user_info['id'],
                        "created_on" => date(DB_DATE_FORMAT),
                        "vendor_status" => 0,
                        "modified_on" => date(DB_DATE_FORMAT),
                        "modified_by" => $this->user_info['id'],
                    );

                    $checking_id = $this->education_model->check_transaction_exit_or_not('view_vendor_master_log',array('id'),array('case_id'=>$frm_details['education_case_id_spoc'],'component_tbl_id' => 3));
                    if(!empty($checking_id))
                    { 
                        $update_transation = $this->education_model->update_closure_approval_details('view_vendor_master_log', $field_array,array('id'=>  $checking_id[0]['id'],'component_tbl_id' => 3)); 
                        $insert_id =  $checking_id[0]['id'];

                        $update_transaction_id = $this->common_model->update_transaction_id(array('trasaction_id' => "Txn" . $insert_id), array('id' => $insert_id));

                    }
                    else{

                        $insert_id = $this->common_model->common_insert_transaction_no('view_vendor_master_log', $field_array);
                        $update_transaction_id = $this->common_model->update_transaction_id(array('trasaction_id' => "Txn" . $insert_id), array('id' => $insert_id));

                    }

                    $folder_name = "vendor_file";
                    $file_upload_path = SITE_BASE_PATH.EDUCATION.$folder_name;
                    if(!folder_exist($file_upload_path)) {
                        mkdir($file_upload_path,0777);
                    }else if(!is_writable($file_upload_path)) {
                        array_push($error_msgs,'Problem while uploading');
                    } 


                    $config_array = array('file_upload_path' => $file_upload_path,
                                        'file_permission' => 'jpeg|jpg|png|pdf|tiff',
                                        'file_size' => BULK_UPLOAD_MAX_SIZE_MB*2000,
                                        'view_venor_master_log_id' => $insert_id,
                                        'component_tbl_id' => 3,
                                        'status' => 1 );
                        
                        if($_FILES['attchments_spoc']['name'][0] != '')
                        {
                            $config_array['files_count'] = count($_FILES['attchments_spoc']['name']);
                            $config_array['file_data'] = $_FILES['attchments_spoc'];
                                
                            $retunr_de = $this->file_upload_library->file_upload_multiple_vendor($config_array, true); 
                            if(!empty($retunr_de['success'])) {
                                $this->common_model->common_insert_batch('view_vendor_master_log_file',$retunr_de['success']);
                            }   
                        }
             
                    if ($insert_id) {
                        $json_array['status'] = SUCCESS_CODE;
                        $json_array['message'] = "Successfully Submitted record";
                    } else {
                        $json_array['status'] = ERROR_CODE;
                        $json_array['message'] = "Something went wrong,please try again";
                    }

                }
                else
                {  

                    if($frm_details['vendor_list_spoc'] == "no")
                    {
                        $this->form_validation->set_rules('verifiers_spoc_id', 'Id', 'required');

                        if(empty($_FILES['attchments_spoc']['name'][0])  && empty($_POST['attchments_spoc']))
                        {

                           $this->form_validation->set_rules('attchments_spoc', 'Attachment file', 'required');
                        }
                     
                        if ($this->form_validation->run() == FALSE)
                        {
                        
                           $json_array['status'] = ERROR_CODE;

                           $json_array['message'] = validation_errors('','');
                        }
                        else
                        {
                        

                            $update = $this->education_model->upload_vendor_assign('education_vendor_log', array('status' => 1, 'approval_by' => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT)), array('id' => $frm_details['education_case_id_spoc']));

                            if ($update) {


                                $field_array = array('component' => 'eduver',
                                    'component_tbl_id' => '3',
                                    'case_id' => $frm_details['education_case_id_spoc'],
                                    'trasaction_id' => 'Txn',
                                    "status" => 1,
                                    "final_status" => $frm_details['cases_assgin_spoc'],
                                    "vendor_remark" => $frm_details['remark_verifiers_spoc'],
                                    "approval_by" => $this->user_info['id'],
                                    "approval_on" => date(DB_DATE_FORMAT),
                                    "created_by" => $this->user_info['id'],
                                    "created_on" => date(DB_DATE_FORMAT),
                                    "vendor_status" => 0,
                                    "modified_on" => date(DB_DATE_FORMAT),
                                    "modified_by" => $this->user_info['id'],
                                );

                                $checking_id = $this->education_model->check_transaction_exit_or_not('view_vendor_master_log',array('id'),array('case_id'=>$frm_details['education_case_id_spoc'],'component_tbl_id' => 3));
                                if(!empty($checking_id))
                                { 
                                    $update_transation = $this->education_model->update_closure_approval_details('view_vendor_master_log', $field_array,array('id'=>  $checking_id[0]['id'],'component_tbl_id' => 3)); 
                                    $insert_id =  $checking_id[0]['id'];
                                }
                                else{

                                    $insert_id = $this->common_model->common_insert_transaction_no('view_vendor_master_log', $field_array);
                                   $update_transaction_id = $this->common_model->update_transaction_id(array('trasaction_id' => "Txn" . $insert_id), array('id' => $insert_id));


                                    $get_username = $this->education_model->get_reporting_manager_id();

                                    $select_education = $this->education_model->select(true,array('id','vendor_id'),array('id'=>$frm_details['verifiers_spoc_id']));
                                        
                                    $get_vendorname = $this->education_model->get_vendor_details(array('id'=>$select_education['vendor_id']));

                                    $activity  =  array(
                                        'comp_table_id' => $frm_details['verifiers_spoc_id'],
                                        'activity_status' => 'Approve',
                                        "activity_type" => 'Mist it',
                                        "remarks" => $get_username[0]['user_name'].' has provided status as '.$frm_details['cases_assgin_spoc'].' with remarks as '.$frm_details['remark_verifiers_spoc'],
                                        "created_by" => $this->user_info['id'],
                                        "created_on" => date(DB_DATE_FORMAT),
                                        "is_auto_filled" => 0
                                        
                                    );

                                    $activity_insert = $this->education_model->initiated_date_education_activity_data($activity);

                                }
                                  

                            }

                            $folder_name = "vendor_file";
                            $file_upload_path = SITE_BASE_PATH.EDUCATION.$folder_name;
                            if(!folder_exist($file_upload_path)) {
                                mkdir($file_upload_path,0777);
                            }else if(!is_writable($file_upload_path)) {
                                array_push($error_msgs,'Problem while uploading');
                            }


                            $config_array = array('file_upload_path' => $file_upload_path,
                                                 'file_permission' => 'jpeg|jpg|png|pdf|tiff',
                                                 'file_size' => BULK_UPLOAD_MAX_SIZE_MB*2000,
                                                 'view_venor_master_log_id' => $insert_id,
                                                 'component_tbl_id' => 3,
                                                 'status' => 1 );
                        
                            if($_FILES['attchments_spoc']['name'][0] != '')
                            {
                                $config_array['files_count'] = count($_FILES['attchments_spoc']['name']);
                                $config_array['file_data'] = $_FILES['attchments_spoc'];
                                
                                $retunr_de = $this->file_upload_library->file_upload_multiple_vendor($config_array, true); 
                                if(!empty($retunr_de['success'])) {
                                    $this->common_model->common_insert_batch('view_vendor_master_log_file',$retunr_de['success']);
                                }   
                            }
             
                            if ($insert_id) {
                                $json_array['status'] = SUCCESS_CODE;
                                $json_array['message'] = "Successfully Submitted record";
                            } else {
                                $json_array['status'] = ERROR_CODE;
                                $json_array['message'] = "Something went wrong,please try again";
                            }
                        }  
                    }
                    else
                    { 

                        $this->load->model('education_vendor_log_model');

                        $update = $this->education_model->upload_vendor_assign('education_vendor_log', array('status' => 1, 'approval_by' => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT)), array('id' => $frm_details['education_case_id_spoc']));
                        
                            if ($update) {

                                $field_array = array('component' => 'eduver',
                                    'component_tbl_id' => '3',
                                    'case_id' => $frm_details['education_case_id_spoc'],
                                    'trasaction_id' => 'Txn',
                                    "status" => 1,
                                    "final_status" => "approve",
                                    "vendor_remark" => $frm_details['remark_verifiers_spoc'],
                                    "remarks" => $frm_details['cases_assgin_spoc'],
                                    "approval_by" => $this->user_info['id'],
                                    "approval_on" => date(DB_DATE_FORMAT),
                                    "created_by" => $this->user_info['id'],
                                    "created_on" => date(DB_DATE_FORMAT),
                                    "vendor_status" => 0,
                                    "modified_on" => date(DB_DATE_FORMAT),
                                    "modified_by" => $this->user_info['id'],
                                );

                                $checking_id = $this->education_model->check_transaction_exit_or_not('view_vendor_master_log',array('id'),array('case_id'=>$frm_details['education_case_id_spoc'],'component_tbl_id' => 3));
                                if(!empty($checking_id))
                                { 
                                    $update_transation = $this->education_model->update_closure_approval_details('view_vendor_master_log', $field_array,array('id'=>  $checking_id[0]['id'],'component_tbl_id' => 3)); 
                                    $insert_id =  $checking_id[0]['id'];
                                }
                                else{

                                $insert_id = $this->common_model->common_insert_transaction_no('view_vendor_master_log', $field_array);
                                $update_transaction_id = $this->common_model->update_transaction_id(array('trasaction_id' => "Txn" . $insert_id), array('id' => $insert_id));

                                }
                            }

                        $fields1 = array('vendor_stamp_id'=>$frm_details['vendor_list_spoc']);

                        $update_verifier_stamp = $this->education_model->save($fields1,array('id'=>$frm_details['verifiers_spoc_id']));   

                        $get_username = $this->education_model->get_reporting_manager_id();

                        $get_vendorname = $this->education_model->get_vendor_details(array('id'=>$frm_details['vendor_list_spoc']));
          

                            $stamp_vendor_details = array(
                                    'vendor_id' => $frm_details['vendor_list_spoc'],
                                    'case_id' => $frm_details['verifiers_spoc_id'],
                                    "status" => 1,
                                    "remarks" => '',
                                    "created_by" => $this->user_info['id'],
                                    "created_on" => date(DB_DATE_FORMAT),
                                    "approval_by" => 0,
                                    "modified_on" => null,
                                    "modified_by" => '',
                                );

                            $activity[]  =  array(
                                    'comp_table_id' => $frm_details['verifiers_spoc_id'],
                                    'activity_status' => 'Assign',
                                    "activity_type" => 'Mist it',
                                    "remarks" => $get_username[0]['user_name'].' has provided status as '.$frm_details['cases_assgin_spoc'].' with remarks as '.$frm_details['remark_verifiers_spoc'].' and assigned to '.$get_vendorname[0]['vendor_name'],
                                    "created_by" => $this->user_info['id'],
                                    "created_on" => date(DB_DATE_FORMAT),
                                    "is_auto_filled" => 0
                                    
                                );
                            
                            $inserted = $this->education_model->insert_education_vendor_log('education_vendor_log', $stamp_vendor_details);
                            $activity_insert = $this->common_model->common_insert_batch('education_activity_data', $activity);


                                $field_array = array('component' => 'eduver',
                                    'component_tbl_id' => '3',
                                    'case_id' => $inserted,
                                    'trasaction_id' => 'Txn',
                                    "status" => 1,
                                    "final_status" => 'wip',
                                    "vendor_remark" => $frm_details['remark_verifiers_spoc'],
                                    "remarks" => $frm_details['cases_assgin_spoc'],
                                    "approval_by" => $this->user_info['id'],
                                    "approval_on" => date(DB_DATE_FORMAT),
                                    "created_by" => $this->user_info['id'],
                                    "created_on" => date(DB_DATE_FORMAT),
                                    "vendor_status" => 0,
                                    "modified_on" => date(DB_DATE_FORMAT),
                                    "modified_by" => $this->user_info['id'],
                                );

                                $insert_id = $this->common_model->common_insert_transaction_no('view_vendor_master_log', $field_array);
                                $update_transaction_id = $this->common_model->update_transaction_id(array('trasaction_id' => "Txn" . $insert_id), array('id' => $insert_id));

                        if ($update_verifier_stamp) {
                            $json_array['status'] = SUCCESS_CODE;
                            $json_array['message'] = "Successfully Submitted record";
                        } else {
                            $json_array['status'] = ERROR_CODE;
                            $json_array['message'] = "Something went wrong,please try again";
                        }

                    }
                }
            }

        } else {

            $json_array['status'] = ERROR_CODE;
            $json_array['message'] = "Access Denied, You don’t have permission to access this page";
        }
        echo_json($json_array);
    }

    public function assign_stamp_details()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {

            $frm_details = $this->input->post();
           
            $this->form_validation->set_rules('stamp_id', 'Id', 'required');

            if(empty($_FILES['attchments_stamp']['name'][0])  && empty($_POST['attchments_stamp']))
            {

                $this->form_validation->set_rules('attchments_stamp', 'Attachment file', 'required');
            }
             
            if ($this->form_validation->run() == FALSE)
            {
                
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('','');
            }
            else
            {      
                    $field_array = array("final_status" => $frm_details['cases_assgin_stamp'],'modified_on'=> date(DB_DATE_FORMAT));

                    $update_transaction_status = $this->education_model->update_closure_approval_details('view_vendor_master_log',$field_array,array('id' => $frm_details['stamp_id'])); 

                    $folder_name = "vendor_file";
                    $file_upload_path = SITE_BASE_PATH.EDUCATION.$folder_name;
                    if(!folder_exist($file_upload_path)) {
                        mkdir($file_upload_path,0777);
                    }else if(!is_writable($file_upload_path)) {
                        array_push($error_msgs,'Problem while uploading');
                    }


                    $config_array = array('file_upload_path' => $file_upload_path,
                                         'file_permission' => 'jpeg|jpg|png|pdf|tiff',
                                         'file_size' => BULK_UPLOAD_MAX_SIZE_MB*2000,
                                         'view_venor_master_log_id' => $frm_details['stamp_id'],
                                         'component_tbl_id' => 3,
                                         'status' => 1 );
                
                    if($_FILES['attchments_stamp']['name'][0] != '')
                    {
                        $config_array['files_count'] = count($_FILES['attchments_stamp']['name']);
                        $config_array['file_data'] = $_FILES['attchments_stamp'];
                        
                        $retunr_de = $this->file_upload_library->file_upload_multiple_vendor($config_array, true); 
                        if(!empty($retunr_de['success'])) {
                            $this->common_model->common_insert_batch('view_vendor_master_log_file',$retunr_de['success']);
                        }   
                    }
                   
                    $json_array['status'] = SUCCESS_CODE;
                    $json_array['message'] = "Successfully Submitted record";
                   
            } 
       
        } else {

            $json_array['status'] = ERROR_CODE;
            $json_array['message'] = "Access Denied, You don’t have permission to access this page";
        }
        echo_json($json_array);
    }

    public function education_result_list($emp_id = '')
    {

        if ($emp_id && $this->input->is_ajax_request()) {
            $education_result = $this->education_model->select_result_log(array('education_ver_result.education_id' => $emp_id, 'activity_log_id !=' => null));

            $html_view = '<thead><tr><th>Created On</th><th>Created By</th><th>Action</th><th>Activit Mode</th><th>Attachment</th><th>Activity Type</th><th>Activity Status</th><th>View</th></tr></thead>';

            if (!empty($education_result[0]['id'])) {
                $l = 1;
                foreach ($education_result as $key => $value) {

                    $vendor_attachments = $this->education_model->select_file_vendor(array('view_vendor_master_log_file.id', 'view_vendor_master_log_file.file_name', 'view_vendor_master_log_file.real_filename', 'view_vendor_master_log_file.status'), array('view_vendor_master_log_file.component_tbl_id' => 3), $value['education_id']);

                    $file = "&nbsp;&nbsp;&nbsp;&nbsp;";
                    if ($value['file_names']) {
                        $files = explode(',', $value['file_names']);

                        for ($i = 0; $i < count($files); $i++) {
                            $url = "'" . SITE_URL . EDUCATION . $value['clientid'] . '/';
                            $actual_file = $files[$i] . "'";
                            $myWin = "'" . "myWin" . "'";
                            $attribute = "'" . "height=250,width=480" . "'";

                            $file .= '<a href="javascript:;" onClick="myOpenWindow(' . $url . $actual_file . ',' . $myWin . ',' . $attribute . '); return false"><i class="fa fa-file-photo-o" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;';
                        }
                    }
                     if ($value['file_university_names']) {
                        $files = explode(',', $value['file_university_names']);

                        for ($i = 0; $i < count($files); $i++) {
                            $url = "'" . SITE_URL . UNIVERSITY_PIC;
                            $actual_file = $files[$i] . "'";
                            $myWin = "'" . "myWin" . "'";
                            $attribute = "'" . "height=250,width=480" . "'";

                            $file .= '<a href="javascript:;" onClick="myOpenWindow(' . $url . $actual_file . ',' . $myWin . ',' . $attribute . '); return false"><i class="fa fa-file-photo-o" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;';
                        }
                    }


                    if ($vendor_attachments) {

                        for ($j = 0; $j < count($vendor_attachments); $j++) {
                            $folder_name = "vendor_file";
                            $url = "'" . SITE_URL . EDUCATION . $folder_name . '/';
                            $actual_file = $vendor_attachments[$j]['file_name'] . "'";
                            $myWin = "'" . "myWin" . "'";
                            $attribute = "'" . "height=250,width=480" . "'";

                            $file .= '<a href="javascript:;" ondblClick="myOpenWindow(' . $url . $actual_file . ',' . $myWin . ',' . $attribute . '); return false"> <i class="fa fa-file-photo-o" aria-hidden="true"></i> </a>&nbsp;&nbsp;&nbsp;&nbsp;';

                        }
                    }

                    $html_view .= '<tr>';
                    $html_view .= "<td>" . convert_db_to_display_date($value['created_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12) . "</td>";
                    $html_view .= "<td>" . $value['created_by'] . "</td>";
                    $html_view .= "<td>" . $value['activity_action'] . "</td>";
                    $html_view .= "<td>" . $value['activity_mode'] . "</td>";
                    $html_view .= "<td>" . $file . "</td>";
                    $html_view .= "<td>" . $value['activity_type'] . "</td>";
                    $html_view .= "<td>" . $value['activity_status'] . "</td>";
                    if ($l == 1) {
                        $html_view .= '<td><button data-id="showAddResultModel" data-url ="' . ADMIN_SITE_URL . 'education/education_result_list_idwise/' . $value['id'] . '/' . str_replace(" ", "", $value['activity_action']) . '" data-toggle="modal" data-target="#showAddResultModel1"  class="btn-info  showAddResultModel"> View </button></td>';
                    } else {
                        $html_view .= '<td>  </td>';
                    }
                    $html_view .= '</tr>';

                    $l++;
                }
            } else {
                $html_view .= "<tr><td colspan = '8'>No Record Found</td></tr>";

            }
            $json_array['status'] = SUCCESS_CODE;

            $json_array['message'] = $html_view;
        } else {
            $json_array['status'] = SUCCESS_CODE;

            $json_array['message'] = "<tr><td>Something went wrong, please try again!<td></tr>";
        }
        echo_json($json_array);
    }

    public function education_result_list_idwise($where_id, $url)
    {

        $details = $this->education_model->select_result_log1(array('education_ver_result.id' => $where_id));

        if ($where_id && !empty($details)) {

            $data['check_insuff_raise'] = '';
            $data['university'] = $this->university_list_dropdown();
            $data['qualification'] = $this->qualification_list();
            $data['details'] = $details[0];
            $data['attachments'] = $this->education_model->select_file(array('id', 'file_name', 'status'), array('education_id' => $details[0]['education_id'], 'type' => 1));

            $data['university_selected_attachments'] = $this->education_model->select_file(array('id', 'file_name', 'status'), array('education_id' => $details[0]['education_id'], 'type' => 4));

            $data['vendor_attachments'] = $this->education_model->select_file_vendor(array('view_vendor_master_log_file.id', 'view_vendor_master_log_file.file_name', 'view_vendor_master_log_file.real_filename', 'view_vendor_master_log_file.status'), array('view_vendor_master_log_file.component_tbl_id' => 3), $details[0]['education_id']);

            $data['url'] = $url;

            echo $this->load->view('admin/education_add_result_model_view_log', $data, true);
        } else {
            echo "<h4>Record Not Found</h4>";
        }

    }

    public function Save_vendor_details()
    {
        if ($this->input->is_ajax_request()) {

            $this->form_validation->set_rules('transaction_id', 'transaction id', 'required');

            $this->form_validation->set_rules('vendor_remark', 'Vendor Remark', 'required');

            if ($this->form_validation->run() == false) {

                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');

            } else {
                $folder_name = "vendor_file";
                $file_upload_path = SITE_BASE_PATH . EDUCATION . $folder_name;
                if (!folder_exist($file_upload_path)) {
                    mkdir($file_upload_path, 0777);
                } else if (!is_writable($file_upload_path)) {
                    array_push($error_msgs, 'Problem while uploading');
                }

                $frm_details = $this->input->post();

                $transaction_id = $frm_details['transaction_id'];

                $field_array = array('final_status' => $frm_details['status'], 'vendor_remark' => $frm_details['vendor_remark'], 'modified_on' => date(DB_DATE_FORMAT), 'vendor_date' => convert_display_to_db_date($frm_details['vendor_date']));

                $result = $this->education_model->save_vendor_details("view_vendor_master_log", array_map('strtolower', $field_array), array('trasaction_id' => $transaction_id));

              
                $config_array = array('file_upload_path' => $file_upload_path, 'file_permission' => 'jpeg|jpg|png|tiff', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 2000, 'view_venor_master_log_id' => $frm_details['view_vendor_master_log_id'], 'component_tbl_id' => 3, 'status' => 1);

                if ($_FILES['attchments_file']['name'][0] != '') {
                    $config_array['files_count'] = count($_FILES['attchments_file']['name']);
                    $config_array['file_data'] = $_FILES['attchments_file'];

                    $retunr_de = $this->file_upload_multiple_vendor_log($config_array);
                    if (!empty($retunr_de)) {
                        $this->common_model->common_insert_batch('view_vendor_master_log_file', $retunr_de['success']);
                    }
                }

                if ($result) {
                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'Vendor Details Successfully Inserted';

                } else {

                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Something went wrong,please try again';

                }

                echo_json($json_array);
            }
        }
    }

    public function Save_vendor_details_cost()
    {

        if ($this->input->is_ajax_request()) {

            $frm_details = $this->input->post();

            if (!empty($frm_details['additional_charges'])) {

                $this->form_validation->set_rules('remark', 'Remark', 'required');

            }

            $this->form_validation->set_rules('update_id', 'ID', 'required');

            $this->form_validation->set_rules('charges', 'Charges', 'required');

            if ($this->form_validation->run() == false) {

                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');

            } else {
                $folder_name = "vendor_file";
                $file_upload_path = SITE_BASE_PATH . ADDRESS . $folder_name;
                if (!folder_exist($file_upload_path)) {
                    mkdir($file_upload_path, 0777);
                } else if (!is_writable($file_upload_path)) {
                    array_push($error_msgs, 'Problem while uploading');
                }

                $frm_details = $this->input->post();

                $field_array = array(
                    'vendor_master_log_id' => $frm_details['update_id'],
                    'cost' => $frm_details['charges'],
                    'additional_cost' => $frm_details['additional_charges'],
                    'remark' => $frm_details['remark'],
                    'components_tbl_id' => 3,
                    'status' => 1,
                    'created_by' => $this->user_info['id'],
                    'created_on' => date(DB_DATE_FORMAT),

                );

                $result = $this->education_model->save_vendor_details_costing("vendor_cost_details", $field_array);

                $config_array = array('file_upload_path' => $file_upload_path, 'file_permission' => 'jpeg|jpg|png', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 2000, 'vendor_cost_details_id' => $result, 'component_tbl_id' => 1, 'status' => 1);

                if ($_FILES['attchments_file']['name'][0] != '') {

                    $config_array['files_count'] = count($_FILES['attchments_file']['name']);
                    $config_array['file_data'] = $_FILES['attchments_file'];

                    $retunr_de = $this->file_upload_multiple_vendor($config_array);
                    if (!empty($retunr_de)) {
                        $this->common_model->common_insert_batch('vendor_cost_details_file', $retunr_de['success']);
                    }
                }

                if ($result) {

                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'Vendor Costing Details Successfully Inserted';

                } else {

                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Something went wrong,please try again';

                }

            }
            echo_json($json_array);
        }
    }

    public function Save_vendor_details_cancel()
    {

        if ($this->input->is_ajax_request()) {
            $frm_details = $this->input->post();

            $this->form_validation->set_rules('update_id', 'ID', 'required');

            $this->form_validation->set_rules('venodr_reject_reason', 'venodr reject reason', 'required');

            if ($this->form_validation->run() == false) {

                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');

            } else {

                $frm_details = $this->input->post();

                $field_array = array(
                    'remarks' => $frm_details['venodr_reject_reason'],
                    'rejected_by' => $this->user_info['id'],
                    'rejected_on' => date(DB_DATE_FORMAT),
                    'status' => 5,
                    'final_status' => "cancelled",
                );

                $result = $this->education_model->save_vendor_details_cancel($field_array, array('id' => $frm_details['update_id']));

                $field_array_address = array(
                    'remarks' => $frm_details['venodr_reject_reason'],
                    'modified_by' => $this->user_info['id'],
                    'modified_on' => date(DB_DATE_FORMAT),
                    'status' => 2,
                );

                $education_vendor_result = $this->education_model->update_education_vendor_log('education_vendor_log', $field_array_address, array('id' => $frm_details['case_id']));

               

                if ($result && $education_vendor_result) {

                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'Vendor Rejected Successfully';

                } else {

                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Something went wrong,please try again';

                }

            }
            echo_json($json_array);
        }
    }

    public function vendor_cost_approval()
    {
        if ($this->input->is_ajax_request()) {
            $details['detail'] = $this->education_model->get_vendor_cost_aprroval_details();

            //  $details['details'] = $details[0];

        } else {
            $details['detail'] = "Access Denied, You don’t have permission to access this page";
        }

        echo $this->load->view('admin/education_vendor_cost_details', $details, true);
        //echo $this->load->view('admin/address_ajax_tab',$data,TRUE);
    }

    public function apperovecost()
    {
        if ($this->input->is_ajax_request()) {

            $id = $this->input->post('id');

            $cost = $this->input->post('cost');
            $add_cost = $this->input->post('add_cost');
            $vendor_master_log_id = $this->input->post('vendor_master_log_id');
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
        } else {
            $json_array['status'] = ERROR_CODE;

            $json_array['message'] = 'Something went wrong,please try again';
        }
        echo_json($json_array);
    }

    public function rejectcost()
    {

        if ($this->input->is_ajax_request()) {

            $id = $this->input->post('id');
            $vendor_master_log_id = $this->input->post('vendor_master_log_id');
            $rejected_by = $this->user_info['id'];
            $rejected_on = date(DB_DATE_FORMAT);

            $result = $this->education_model->reject_cost(array("accept_reject_cost" => "2", "rejected_by" => $rejected_by, "rejected_on" => $rejected_on), array('id' => $id));

            $result1 = $this->education_model->reject_cost_vendor(array("costing" => "0", "additional_costing" => "0"), array('id' => $vendor_master_log_id));

            //  $details['details'] = $details[0];

            if ($result && $result1) {

                $json_array['status'] = SUCCESS_CODE;

                $json_array['message'] = 'Reject Vendor Cost ';

            } else {

                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = 'Something went wrong,please try again';

            }

        } else {
            $json_array['status'] = ERROR_CODE;

            $json_array['message'] = 'Something went wrong,please try again';
        }

        echo_json($json_array);

    }

    public function initiation_mail($id)
    {
        if ($this->input->is_ajax_request()) {
            $details = $this->education_model->education_ver_details_for_email(array('education.id' => $id));

            $reportingmanager_user = $this->education_model->get_reporting_manager_id();

            $reportingmanager_id = $reportingmanager_user[0]['reporting_manager'];
            $reportingmanager = $this->education_model->get_reporting_manager_email_id($reportingmanager_id);
            $view_data['reporting_manager_email'] = $reportingmanager[0];
            $view_data['user_profile_info'] = $reportingmanager_user[0];

            $view_data['email_info'] = $details[0];

            echo $this->load->view(EMAIL_VIEW_FOLDER_NAME . 'education_intitial_mail_template', $view_data, true);
        }
    }

    public function addAddCompanyModel()
    {
        if ($this->input->is_ajax_request()) {
            $data['states'] = $this->get_states();
            echo $this->load->view('admin/addAddCompanyModel', $data, true);
        } else {
            echo "<p>We're sorry, but you do not have access to this page.</p>";
        }
    }

    public function education_initial_send_mail()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {
            $education_id = $this->input->post('edu_user_id');

            $to_emails = $this->input->post('to_email');

            $from_email = $this->input->post('from');

            $cc_email = $this->input->post('cc_email');

            $bcc_email = $this->input->post('bcc_email');

            $subject = $this->input->post('subject');

            $attchment =  $this->input->post('attachment');

            $this->load->library('email');

            $details = $this->education_model->education_ver_details_for_email(array('education.id' => $education_id));
            $reportingmanager_user = $this->education_model->get_reporting_manager_id();
            $email_tmpl_data['user_profile_info'] = $reportingmanager_user[0];

            $email_tmpl_data['to_emails'] = $to_emails;
            $email_tmpl_data['cc_emails'] = $cc_email;
            $email_tmpl_data['bcc_emails'] = $bcc_email;
            $email_tmpl_data['subject'] = $subject;
            $email_tmpl_data['attchment'] = $attchment;

            $email_tmpl_data['from_email'] = ($from_email != "") ? $from_email : FROMEMAIL;

            $email_tmpl_data['detail_info'] = $details[0];

            $result = $this->email->admin_send_education_initial_mail($email_tmpl_data);
            if ($result) {
                if ($details[0]['verfstatus'] == "14") {
                    $details = $this->education_model->education_verfstatus_update(array('education_result.verfstatus' => "1"), array('education_result.education_id' => $education_id));
                }

                $field = array('candsid' => $details[0]['candsid'],
                    'ClientRefNumber' => $details[0]['ClientRefNumber'],
                    'comp_table_id' => $education_id,
                    'activity_status' => "WIP",
                    'activity_type' => "Email",
                    'action' => "Initiation Mail",
                    'remarks' => "Initiation Mail Send to " . $to_emails,
                    'created_on' => date(DB_DATE_FORMAT),
                    'created_by' => $this->user_info['id'],
                    'is_auto_filled' => 1,

                );

                $result = $this->education_model->save_mail_activity_data($field);

                $this->education_model->education_mail_details(array('created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id'], 'to_mail_id' => $to_emails, 'cc_mail_id' => $cc_email, 'education_id' => $education_id, 'type' => "Initiation Mail"));

                $user_activity_data = $this->common_model->user_actity_data(array('component' => "Education",
                    'ref_no' => $details[0]['cmp_ref_no'], 'candidate_name' => $details[0]['CandidateName'], 'created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id'], 'activity_type' => 'Manual', 'action' => 'Initiation Email'));

                $json_array['message'] = 'Email Send Successfully';

                $json_array['status'] = SUCCESS_CODE;
            } else {
                $json_array['message'] = 'Email not sent, please try again';

                $json_array['status'] = ERROR_CODE;
            }
        } else {
            $json_array['message'] = 'Something went wrong, please try again';

            $json_array['status'] = ERROR_CODE;
        }

        echo_json($json_array);
    }

    public function education_closure_entries($userid)
    {
        if ($this->input->is_ajax_request() && $userid) {

            log_message('error', 'Education Closure Entries');
            try {

                $details['user_list_closed'] = $this->users_list_filter();

                $details['userid'] = $userid;

                $details['vendor_executive_list'] = $this->education_model->education_case_list(array('view_vendor_master_log.status =' => 1, 'component' => 'eduver', 'view_vendor_master_log.final_status = ' => 'closed'), $userid);

            } catch (Exception $e) {
                log_message('error', 'Education::education_closure_entries');
                log_message('error', $e->getMessage());
            }

        } else {
            $details['vendor_executive_list'] = "Something went wrong,please try again";
        }

        echo $this->load->view('admin/education_vendor_closure_entries', $details, true);

    }

    public function education_closure_entries_vendor_insuff()
    {
        if ($this->input->is_ajax_request()) {

            log_message('error', 'Education vendor insuff');
            try {

                $details['user_list_insuff'] = $this->users_list_filter();

                $details['vendor_executive_list'] = $this->education_model->education_case_list_insuff(array('view_vendor_master_log.status =' => 1, 'view_vendor_master_log.final_status = ' => 'insufficiency', 'component' => 'eduver'));

            } catch (Exception $e) {
                log_message('error', 'Education::education_closure_entries_vendor_insuff');
                log_message('error', $e->getMessage());
            }

        } else {
            $details['vendor_executive_list'] = "Something went wrong,please try again";
        }

        echo $this->load->view('admin/education_vendor_insuff_entries', $details, true);

    }

    public function check_insuff_already_raised($where_id)
    {

        $details = $this->education_model->get_all_education_record(array('education.id' => $where_id));

        $check_insuff_raise = $this->education_model->select_insuff(array('education_id' => $where_id, 'status' => STATUS_ACTIVE, 'insuff_clear_date is null' => null));

        $data['insuff_reason_list'] = $this->education_model->insuff_reason_list(false, array('component_id' => 3));

        $data['insuff_raise_message'] = (!empty($check_insuff_raise) ? 'Insufficiency raised on ' . convert_db_to_display_date($check_insuff_raise[0]['insuff_raised_date']) . ' for ' . $check_insuff_raise[0]['insff_reason'] : '');

        $data['check_insuff_value'] = ((!empty($check_insuff_raise)) ? '1' : '2');

        $data['check_insuff_raise'] = ((!empty($check_insuff_raise)) ? 'disabled' : '');
        $data['insuff_raise_id'] = (!empty($check_insuff_raise) ? $check_insuff_raise[0]['id'] : '');

        if (empty($check_insuff_raise)) {
            $data['get_cands_details'] = $this->candidate_entity_pack_details($details[0]['cands_id']);
            $data['selected_data'] = $details[0];

            echo $this->load->view('admin/education_insuff_view', $data, true);

        } else {
            echo "<h4>Insuff Already Created</h4>";
        }
    }

    public function education_closure()
    {

        if ($this->input->is_ajax_request()) {

            if ($this->permission['access_education_list_activity'] == 1) {
                $frm_details = $this->input->post();
                $action = $frm_details['action'];

                if ($frm_details['action'] == 2 && $frm_details['closure_id'] != "") {
                    $list = explode(',', $frm_details['closure_id']);

                    $update_counter = 0;
                    $files = array();

                    foreach ($list as $key => $value) {

                        $update_closure = $this->education_model->update_closure_approval('view_vendor_master_log_closure_status', array('view_vendor_master_log_id' => $value, 'approve_reject_status' => 2, 'approve_reject_by' => $this->user_info['id'], 'reject_reasons' => $frm_details['reject_reason'], "approve_rejected_on" => date(DB_DATE_FORMAT)));

                        $update_closure1 = $this->education_model->update_closure_approval_details('view_vendor_master_log', array('final_status' => 'wip'), array('id' => $value));

                    }
                    if (!empty($update_closure) && !empty($update_closure1)) {

                        $json_array['status'] = SUCCESS_CODE;
                        $json_array['message'] = "Successfully Rejected Closure";
                    } else {
                        $json_array['status'] = ERROR_CODE;
                        $json_array['message'] = "Something went wrong,please try again";
                    }

                } else if ($frm_details['action'] == 1 && $frm_details['closure_id'] != "") {

                    $list = explode(',', $frm_details['closure_id']);

                    $update_counter = 0;
                    $files = array();

                    foreach ($list as $key => $value) {

                        $update_closure = $this->education_model->update_closure_approval('view_vendor_master_log_closure_status', array('view_vendor_master_log_id' => $value, 'approve_reject_status' => 1, 'approve_reject_by' => $this->user_info['id'], "approve_rejected_on" => date(DB_DATE_FORMAT)));

                        $update_closure1 = $this->education_model->update_closure_approval_details('view_vendor_master_log', array('final_status' => 'approve'), array('id' => $value));

                    }
                    if (!empty($update_closure) && !empty($update_closure1)) {

                        $json_array['status'] = SUCCESS_CODE;
                        $json_array['message'] = "Successfully Approve Closure";
                    } else {
                        $json_array['status'] = ERROR_CODE;
                        $json_array['message'] = "Something went wrong,please try again";
                    }
                } else {
                    $json_array['status'] = ERROR_CODE;
                    $json_array['message'] = "Select atleast one case";
                }

            } else {

                $json_array['status'] = ERROR_CODE;
                $json_array['message'] = "Access Denied, You don’t have permission to access this page";
            }
        } else {

            $json_array['status'] = ERROR_CODE;
            $json_array['message'] = "Access Denied, You don’t have permission to access this page";
        }

        echo_json($json_array);
    }

    /* protected function university_list1()
    {
    $lists = $this->common_model->select('university_master',FALSE,array("id","universityname"),array('status' => STATUS_ACTIVE));

    return convert_to_single_dimension_array($lists,'id','universityname');
    }*/
    /*   protected function get_university_list()
    {
    $university = $this->common_model->select('university_master',FALSE,array('id','universityname'),array());
    $university_arry[0] = 'Select University Name';
    foreach ($university as $key => $value) {
    $university_arry[$value['universityname']] = ucwords(strtolower($value['universityname']));
    }
    return $university_arry;
    }
     */
    /*  protected function get_university_list()
    {
    $company = $this->common_model->select('university_master',FALSE,array('id','universityname'),array());
    $company_arry[0] = 'Select Company';
    foreach ($company as $key => $value) {
    $company_arry[$value['id']] = ucwords(strtolower($value['universityname']));
    }
    return $company_arry;

    }
     */
    /*  protected function get_qualification_list()
    {
    $qualification = $this->common_model->select('qualification_master',FALSE,array('id','qualification'),array());
    $qualification_arry[0] = 'Select Qualification';
    foreach ($qualification as $key => $value) {
    $result1 = explode('(',$value['qualification']);
    $result2     = str_replace(".", "",$result1[0]);
    $result3     = str_replace("&", "And",$result2 );
    $result     = str_replace("-", " ",$result3 );
    $qualification_arry[$value['id']] = ucwords(strtolower($result));
    }
    return $qualification_arry;
    }*/

    public function template_download()
    {
        if ($this->input->is_ajax_request()) {

          //  $assigned_user_id = $this->education_model->get_assign_users_id('user_profile', false, array("`user_profile`.`status`,`user_profile`.`id`,`user_profile`.`email`,`user_profile`.`department`,`user_profile`.`user_name`,concat(`user_profile`.`firstname`,' ',`user_profile`.`lastname`) as fullname,`user_profile`.`profile_pic`,`user_profile`.`firstname`,`user_profile`.`lastname`,`roles`.`role_name`,`roles`.`groups_id`,`roles_permissions`.*"), array('user_profile.status' => STATUS_ACTIVE));

            $states = array('Select State', 'Andaman And Nicobar Islands', 'Andhra Pradesh', 'Arunachal Pradesh', 'Assam', 'Bihar', 'Chattisgarh', 'Chandigarh', 'Daman And Diu', 'Delhi', 'Dadra And Nagar Haveli', 'Goa', 'Gujarat', 'Himachal Pradesh', 'Haryana', 'Jammu And Kashmir', 'Jharkhand', 'Kerala', 'Karnataka', 'Lakshadweep', 'Meghalaya');

            $document_provided = array('Select Document Provided', 'provisional certificate', 'degree', 'marksheet', 'other');

            //    $university = $this->get_university_list();

            //  $qualification = $this->get_qualification_list();
            // print_r($qualification);exit();
            //$this->load->model('university_model');

            require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
            // Create new Spreadsheet object
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            // Set document properties
            $spreadsheet->getProperties()->setCreator(CRMNAME)
                ->setLastModifiedBy(CRMNAME)
                ->setTitle(CRMNAME)
                ->setSubject('Education Import Template')
                ->setDescription('Education Import Template File for bulk upload');

            $styleArray = array(
                'fill' => array(
                    'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startcolor' => array(
                        'rgb' => 'FF0000',
                    ),
                ),
            );
            $spreadsheet->getActiveSheet()->getStyle('A1:B1')->applyFromArray($styleArray);
            $spreadsheet->getActiveSheet()->getStyle('D1')->applyFromArray($styleArray);
            $spreadsheet->getActiveSheet()->getStyle('J1:K1')->applyFromArray($styleArray);
            $spreadsheet->getActiveSheet()->getStyle('M1')->applyFromArray($styleArray);

            foreach (range('A', 'Q') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                    ->setWidth(20);
            }

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A1", REFNO)
                ->setCellValue("B1", 'Comp Int Date')
                ->setCellValue("C1", 'School/College')
                ->setCellValue("D1", 'University/Board')
                ->setCellValue("E1", 'Grade/Class/Marks')
                ->setCellValue("F1", 'Qualification')
                ->setCellValue("G1", 'Major')
                ->setCellValue("H1", 'Course Start Date')
                ->setCellValue("I1", 'Course End Date')
                ->setCellValue("J1", 'Month of Passing')
                ->setCellValue("K1", 'Year of Passing')
                ->setCellValue("L1", 'Roll No')
                ->setCellValue("M1", 'Enrollment No')
                ->setCellValue("N1", 'PRN Number')
                ->setCellValue("O1", 'Documents Provided')
                ->setCellValue("P1", 'City')
                ->setCellValue("Q1", 'State');

            for ($i = 1; $i <= 1000; $i++) {

                $objValidation = $spreadsheet->getActiveSheet()->getCell('A' . $i)->getDataValidation();
                $objValidation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                $objValidation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
                $objValidation->setAllowBlank(false);
                $objValidation->setShowInputMessage(true);
                $objValidation->setShowErrorMessage(true);
                $objValidation->setErrorTitle('Input error');
                $objValidation->setError('This Field is Compulsory.');
                $objValidation->setPromptTitle('Insert Value');
                $objValidation->setPrompt('Please insert ' . REFNO);

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

                /*  $objValidation = $spreadsheet->getActiveSheet()->getCell('D'.$i)->getDataValidation();
                $objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST  );
                $objValidation->setErrorStyle(  \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION );
                $objValidation->setAllowBlank(false);
                $objValidation->setShowInputMessage(true);
                $objValidation->setShowErrorMessage(true);
                $objValidation->setShowDropDown(true);
                $objValidation->setErrorTitle('Input error');
                $objValidation->setError('Value is not in list.');
                $objValidation->setPromptTitle('Pick from list');
                $objValidation->setPrompt('Please pick a value from the drop-down list.');
                $objValidation->setFormula1('"'.implode(',', $university).'"');

                $objValidation = $spreadsheet->getActiveSheet()->getCell('F'.$i)->getDataValidation();
                $objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST  );
                $objValidation->setErrorStyle(  \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION );
                $objValidation->setAllowBlank(false);
                $objValidation->setShowInputMessage(true);
                $objValidation->setShowErrorMessage(true);
                $objValidation->setShowDropDown(true);
                $objValidation->setErrorTitle('Input error');
                $objValidation->setError('Value is not in list.');
                $objValidation->setPromptTitle('Pick from list');
                $objValidation->setPrompt('Please pick a value from the drop-down list.');
                $objValidation->setFormula1('"'.implode(',', $qualification).'"');

                 */

                $objValidation = $spreadsheet->getActiveSheet()->getCell('J' . $i)->getDataValidation();
                $objValidation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                $objValidation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
                $objValidation->setAllowBlank(false);
                $objValidation->setShowInputMessage(true);
                $objValidation->setShowErrorMessage(true);
                $objValidation->setErrorTitle('Input error');
                $objValidation->setError('This Field is Compulsory.');
                $objValidation->setPromptTitle('Insert Month of Passing');
                $objValidation->setPrompt('Please insert Month of Passing.');

                $objValidation = $spreadsheet->getActiveSheet()->getCell('K' . $i)->getDataValidation();
                $objValidation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                $objValidation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
                $objValidation->setAllowBlank(false);
                $objValidation->setShowInputMessage(true);
                $objValidation->setShowErrorMessage(true);
                $objValidation->setErrorTitle('Input error');
                $objValidation->setError('This Field is Compulsory.');
                $objValidation->setPromptTitle('Insert Year of Passing');
                $objValidation->setPrompt('Please Insert Year of Passing(YYYY)');

                $objValidation = $spreadsheet->getActiveSheet()->getCell('M' . $i)->getDataValidation();
                $objValidation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                $objValidation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
                $objValidation->setAllowBlank(false);
                $objValidation->setShowInputMessage(true);
                $objValidation->setShowErrorMessage(true);
                $objValidation->setErrorTitle('Input error');
                $objValidation->setError('This Field is Compulsory.');
                $objValidation->setPromptTitle('Insert Enrollment No');
                $objValidation->setPrompt('Please insert Enrollment No.');

                $objValidation = $spreadsheet->getActiveSheet()->getCell('O' . $i)->getDataValidation();
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
                $objValidation->setFormula1('"' . implode(',', $document_provided) . '"');

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
                $objValidation->setFormula1('"' . implode(',', $states) . '"');

              /*  $objValidation = $spreadsheet->getActiveSheet()->getCell('R' . $i)->getDataValidation();
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
                $objValidation->setFormula1('"' . implode(',', $assigned_user_id) . '"');*/

            }

            $spreadsheet->getActiveSheet()->setTitle('Education Records');
            $spreadsheet->setActiveSheetIndex(0);
            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment;filename=Education Bulk Uplaod Template.xlsx");
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

            $json_array['file_name'] = "Education Bulk Uplaod Template";

            $json_array['message'] = "File downloaded successfully,please check in download folder";

            $json_array['status'] = SUCCESS_CODE;
        } else {
            $json_array['file_name'] = "Education Bulk Uplaod Template";

            $json_array['message'] = "File downloaded failed,please check in download folder";

            $json_array['status'] = ERROR_CODE;
        }
        echo_json($json_array);
    }

    public function export_to_excel()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {
            set_time_limit(0);
            ini_set('memory_limit', '-1');
            $where_arry = array();

            $client_id = ($this->input->post('clientid') != "All") ? $this->input->post('clientid') : false;

            $fil_by_status = ($this->input->post('fil_by_status') != "All") ? $this->input->post('fil_by_status') : false;

            $client_name = $this->input->post('client_name');

            $from_date = ($this->input->post('from_date') != "") ? convert_display_to_db_date($this->input->post('from_date')) : false;

            $to_date = ($this->input->post('to_date') != "") ? convert_display_to_db_date($this->input->post('to_date')) : false;

            $all_records = $this->education_model->get_all_education_by_client($client_id, $fil_by_status, $from_date, $to_date);

            require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
            // Create new Spreadsheet object
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

            // Set document properties
            $spreadsheet->getProperties()->setCreator(CRMNAME)
                ->setLastModifiedBy(CRMNAME)
                ->setTitle(CRMNAME)
                ->setSubject('Education records')
                ->setDescription('Education records with their status');

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
            $spreadsheet->getActiveSheet()->getStyle('A1:AE1')->applyFromArray($styleArray);
            // auto fit column to content
            foreach (range('A', 'AE') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                    ->setWidth(20);
            }

            // set the names of header cells
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A1", 'Client Name')
                ->setCellValue("B1", 'Entity')
                ->setCellValue("C1", 'Package')
                ->setCellValue("D1", REFNO)
                ->setCellValue("E1", 'Component Ref No')
                ->setCellValue("F1", 'Client Ref No')
                ->setCellValue("G1", 'Transaction No')
                ->setCellValue("H1", 'Comp Received date')
                ->setCellValue("I1", 'Candidate Name')
                ->setCellValue("J1", 'Fathers Name')
                ->setCellValue("K1", 'Candidate DOB')
                ->setCellValue("L1", 'University')
                ->setCellValue("M1", 'Qualification')
                ->setCellValue("N1", 'Year of Passing')
                ->setCellValue("O1", 'Status')
                ->setCellValue("P1", 'Sub Status')
                ->setCellValue("Q1", 'Executive Name')
                ->setCellValue("R1", 'Verifier/SPOC Assigned On')
                ->setCellValue("S1", 'Verifier/SPOC')
                ->setCellValue("T1", 'Verifier/SPOC Status')
                ->setCellValue("U1", 'Verifier/SPOC Actual Status')
                ->setCellValue("V1", 'Verifier/SPOC closure Date')
                ->setCellValue("W1", 'Vendor Assigned on')
                ->setCellValue("X1", 'Vendor')
                ->setCellValue("Y1", 'Vendor Status')
                ->setCellValue("Z1", 'Vendor Actual Status')
                ->setCellValue("AA1", 'Vendor Closure Date')
                ->setCellValue("AB1", 'Closure Date')
                ->setCellValue("AC1", 'Insuff Raised Date')
                ->setCellValue("AD1", 'Insuff Clear Date')
                ->setCellValue("AE1", 'Insuff Remark');
            // Add some data
            $x = 2;
            foreach ($all_records as $all_record) {

                if($all_record['verifiers_spoc_status'] == 1)
                {
                    $verifiers_spoc_assigned_on = convert_db_to_display_date($all_record['verifiers_spoc_created'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);
                }
                else
                {
                    $verifiers_spoc_assigned_on = convert_db_to_display_date($all_record['verifiers_spoc_modified'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);
                }

                $edu_status = ($all_record['verfstatus'] != "") ? $all_record['verfstatus'] : "WIP";
                $edu_filter_status = ($all_record['filter_status'] != "") ? $all_record['filter_status'] : "WIP";
                $closuredate = ($all_record['filter_status'] == "Closed") ? convert_db_to_display_date($all_record['closuredate']) : "NA";
                $insuff_remarks = $all_record['insuff_raise_remark'];



                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A$x", ucwords($all_record['clientname']))
                    ->setCellValue("B$x", ucwords($all_record['entity_name']))
                    ->setCellValue("C$x", ucwords($all_record['package_name']))
                    ->setCellValue("D$x", $all_record['cmp_ref_no'])
                    ->setCellValue("E$x", $all_record['education_com_ref'])
                    ->setCellValue("F$x", $all_record['ClientRefNumber'])
                    ->setCellValue("G$x", $all_record['transaction_id'])
                    ->setCellValue("H$x", convert_db_to_display_date($all_record['iniated_date']))
                    ->setCellValue("I$x", ucwords($all_record['CandidateName']))
                    ->setCellValue("J$x", ucwords($all_record['NameofCandidateFather']))
                    ->setCellValue("K$x", convert_db_to_display_date($all_record['DateofBirth']))
                    ->setCellValue("L$x", $all_record['university_name'])
                    ->setCellValue("M$x", $all_record['qualification_name'])
                    ->setCellValue("N$x", $all_record['year_of_passing'])
                    ->setCellValue("O$x", $edu_filter_status)
                    ->setCellValue("P$x", $edu_status)
                    ->setCellValue("Q$x", $all_record['executive_name'])
                    ->setCellValue("R$x", $verifiers_spoc_assigned_on)
                    ->setCellValue("S$x", ucwords($all_record['vendor_name']))
                    ->setCellValue("T$x", ucwords($all_record['verifier_vendor_status']))
                    ->setCellValue("U$x", ucwords($all_record['verifier_vendor_actual_status']))
                    ->setCellValue("V$x", convert_db_to_display_date($all_record['verifier_closure_date'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12))

                    ->setCellValue("W$x", convert_db_to_display_date($all_record['verifiers_stamp_created'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12))
                    ->setCellValue("X$x", ucwords($all_record['vendor_stamp_name']))
                    ->setCellValue("Y$x", ucwords($all_record['stamp_vendor_status']))
                    ->setCellValue("Z$x", ucwords($all_record['stamp_vendor_actual_status']))
                    ->setCellValue("AA$x", convert_db_to_display_date($all_record['stamp_closure_date'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12))

                    ->setCellValue("AB$x", $closuredate)
                    ->setCellValue("AC$x", $all_record['insuff_raised_date'])
                    ->setCellValue("AD$x", $all_record['insuff_clear_date'])
                    ->setCellValue("AE$x", $insuff_remarks);

                $x++;
            }
            // Rename worksheet
            $spreadsheet->getActiveSheet()->setTitle('Education Records');

            $spreadsheet->setActiveSheetIndex(0);

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment;filename=Education Records of $client_name.xlsx");
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

            $json_array['file_name'] = "Education Records of $client_name";

            $json_array['message'] = "File downloaded successfully,please check in download folder";

            $json_array['status'] = SUCCESS_CODE;

        } else {
            $json_array['message'] = "Something went wrong,please try again";
            $json_array['status'] = ERROR_CODE;
        }

        echo_json($json_array);
    }

    public function assign_verifiers_details()
    {
        if(isset($_POST['cases_assgin_action']))
        {
            $verifier_status = $_POST['cases_assgin_action'];
            if($verifier_status == 1)
            {
                  $vendor_list = $this->vendor_list_education('eduver',$verifier_status);
                  $verification_dropdown = '<div class="col-md-12 col-sm-12 col-xs-12 form-group vendor_list">';
                  $verification_dropdown .= form_dropdown('vendor_list',$vendor_list, set_value('vendor_list'), 'class="form-control" id="vendor_list" required="required" ');
                  $verification_dropdown .= '</div>';

                
            }
            if($verifier_status == 2)
            {
                 $vendor_list = $this->vendor_list_education('eduver',$verifier_status);

                  $verification_dropdown = '<div class="col-md-12 col-sm-12 col-xs-12 form-group vendor_list">';
                  $verification_dropdown .= form_dropdown('vendor_list',$vendor_list, set_value('vendor_list'), 'class="form-control" id="vendor_list" required="required" ');
                  $verification_dropdown .= '</div>';

                
            }
           echo $verification_dropdown;
        }
        else
        {
            echo "Something went wrong,please try again";   
        }
    }

    protected function vendor_list_education($component_name,$verifier_status)
    {
        $lists = $this->education_model->select_vendor_list_education($component_name,$verifier_status);
        return convert_to_single_dimension_array($lists, 'id', 'vendor_name');
    }

    public function education_stamp_verifiers()
    {
      //  if ($this->input->is_ajax_request()) {

       //     log_message('error', 'Education Stamp Verifiers');
      //      try {
        $details['header'] = "Stamp Details";

        $details['stamp_vendor_list'] = $this->vendor_list_education('eduver',2);

        //$details['stamp_entries'] = $this->education_model->education_stamp_verifiers_queue(array('view_vendor_master_log.status =' => 1, 'component' => 'eduver'));

        //    } catch (Exception $e) {
        //        log_message('error', 'Education::education_stamp_verifiers');
        //        log_message('error', $e->getMessage());
        //    }

       // } else {
        //    $details['stamp_entries'] = "Something went wrong,please try again";
       // }

        echo $this->load->view('admin/education_stamp_queue_entries', $details, true);
    }
    
    public function education_spoc_verifiers()
    {
       // if ($this->input->is_ajax_request()) {

         //   log_message('error', 'Education Spoc Verifiers');
         //   try {
        $details['header'] = "Spoc Details";
        $details['assigned_option'] = array('' => 'select','clear'=>'Clear','major discrepancy'=>'Major Discrepancy','minor discrepancy'=>'Minor Discrepancy','no record found'=>'No Record Found','unable to verify'=>'Unable to Verify','insufficiency'=>'Insufficiency');
        $details['vendor_stamp_list'] = $this->vendor_list_education('eduver',2);

            //    $details['spoc_entries'] = $this->education_model->education_spoc_verifiers_queue(array('education_vendor_log.status' => 0));

          /*  } catch (Exception $e) {
                log_message('error', 'Education::education_spoc_verifiers');
                log_message('error', $e->getMessage());
            }

        } else {
            $details['stamp_entries'] = "Something went wrong,please try again";
        }*/

        echo $this->load->view('admin/education_spoc_queue_entries', $details, true);
    }

    public function education_spoc_verifiers_details()
    {
       // $this->load->model(array('education_vendor_log_model'));

        $params = $add_candidates = $data_arry = $columns = array();

        $params = $_REQUEST;

        $spoc_entries = $this->education_model->education_spoc_verifiers_queue(array('education_vendor_log.status' => 0),$params);

        $totalRecords = count($this->education_model->education_spoc_verifiers_queue_count(array('education_vendor_log.status' => 0),$params));

            $x = 0;

            foreach ($spoc_entries as $list) {
                $edu_details =   $this->education_model->select_education('university_master',array('url_link'),array('university_master.id'=> $list['university_board']));

                $file = "&nbsp;&nbsp;&nbsp;&nbsp;";
                if ($list['education_attachments']) {
                    $files = explode('||', $list['education_attachments']);
                      
                    for ($i = 0; $i < count($files); $i++) {
                        $url = "'" . SITE_URL . EDUCATION . $list['clientid'] . '/';
                        $actual_file = $files[$i] . "'";
                        $myWin = "'" . "myWin" . "'";
                        $attribute = "'" . "height=250,width=480" . "'";

                        $file .= '<a href="javascript:;" onClick="myOpenWindow(' . $url . $actual_file . ',' . $myWin . ',' . $attribute . '); return false"><i class="fa fa-file-photo-o" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;';
                    }
                }

                $mode_of_verification_value = json_decode($list['mode_of_verification']);
                $data_arry[$x]['checkbox'] = $list['case_id'];
                $data_arry[$x]['id'] = $x + 1;

                $data_arry[$x]['education_com_ref'] = $list['education_com_ref'];
                $data_arry[$x]['clientname'] = $list['clientname'];
                $data_arry[$x]['entity_name'] = $list['entity_name'];
                $data_arry[$x]['package_name'] = $list['package_name'];
                $data_arry[$x]['vendor_name'] = $list['vendor_name'];
                $data_arry[$x]['school_college'] = $list['school_college'];
                $data_arry[$x]['encry_id'] = ADMIN_SITE_URL . "education/view_details/" . encrypt($list['case_id']);
                $data_arry[$x]['university_board'] = $list['university_board_name'];
                $data_arry[$x]['qualification'] = $list['qualification'];
                $data_arry[$x]['allocated_by'] = $list['allocated_by'];
                $data_arry[$x]['allocated_on'] = convert_db_to_display_date($list['allocated_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);
                $data_arry[$x]['modified_on'] = convert_db_to_display_date($list['modified_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);
                $data_arry[$x]['CandidateName'] = $list['CandidateName'];
                $data_arry[$x]['mode_of_verification'] = $mode_of_verification_value->eduver;
                $data_arry[$x]['school_college'] =  $list['school_college'];
                $data_arry[$x]['DateofBirth'] = convert_db_to_display_date($list['DateofBirth']);
                $data_arry[$x]['NameofCandidateFather'] = $list['NameofCandidateFather'];
                $data_arry[$x]['MothersName'] = $list['MothersName'];
                $data_arry[$x]['ClientRefNumber'] = $list['ClientRefNumber'];
                $data_arry[$x]['roll_no'] = $list['roll_no'];
                $data_arry[$x]['enrollment_no'] = $list['enrollment_no'];
                $data_arry[$x]['major'] = $list['major'];
                $data_arry[$x]['grade_class_marks'] = $list['grade_class_marks'];
                $data_arry[$x]['course_start_date'] = convert_db_to_display_date($list['course_start_date']);
                $data_arry[$x]['course_end_date'] = convert_db_to_display_date($list['course_end_date']);
                $data_arry[$x]['month_of_passing'] = $list['month_of_passing'];
                $data_arry[$x]['year_of_passing'] = $list['year_of_passing'];
                $data_arry[$x]['attachment_file'] = $file;

                if($edu_details[0]['url_link'] != '')
                {
                   $spoc_url = '<button  data-university_id ='.$list['university_board'].' data-toggle="modal" class="btn btn-sm btn-info  showURLModelSPOC"> URL </button>';  
                }
                else
                {
                    $spoc_url = '';
                }

                $data_arry[$x]['action'] = '<button data-id='.$list['case_id'].' data-educationcaseid ='.$list['id'].' data-mode_of_verification='.$mode_of_verification_value->eduver.'  data-toggle="modal" class="btn btn-sm btn-info  showspocModel"> View </button>&nbsp;'.$spoc_url;

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
   
    public function education_stamp_verifiers_details()
    {
       // $this->load->model(array('education_vendor_log_model'));

        $params = $add_candidates = $data_arry = $columns = array();

        $params = $_REQUEST;


        $stamp_entries = $this->education_model->education_stamp_verifiers_queue(array('view_vendor_master_log.status =' => 1, 'component' => 'eduver'),$params);

        $totalRecords = count( $this->education_model->education_stamp_verifiers_queue_count(array('view_vendor_master_log.status =' => 1, 'component' => 'eduver'),$params));

            $x = 0;

            foreach ($stamp_entries as $list) {

                $file = "&nbsp;&nbsp;&nbsp;&nbsp;";
                if ($list['education_attachments']) {
                    $files = explode('||', $list['education_attachments']);
                      
                    for ($i = 0; $i < count($files); $i++) {
                        $url = "'" . SITE_URL . EDUCATION . $list['clientid'] . '/';
                        $actual_file = $files[$i] . "'";
                        $myWin = "'" . "myWin" . "'";
                        $attribute = "'" . "height=250,width=480" . "'";

                        $file .= '<a href="javascript:;" onClick="myOpenWindow(' . $url . $actual_file . ',' . $myWin . ',' . $attribute . '); return false"><i class="fa fa-file-photo-o" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;';
                    }
                }

                $mode_of_verification_value = json_decode($list['mode_of_verification']);
                $data_arry[$x]['checkbox'] = $list['education_id'];
                $data_arry[$x]['id'] = $x + 1;

                $data_arry[$x]['education_com_ref'] = $list['education_com_ref'];
                $data_arry[$x]['clientname'] = $list['clientname'];
                $data_arry[$x]['entity_name'] = $list['entity_name'];
                $data_arry[$x]['package_name'] = $list['package_name'];
                $data_arry[$x]['vendor_name'] = $list['vendor_name'];
                $data_arry[$x]['vendor_stamp_name'] = $list['vendor_stamp_name'];
                $data_arry[$x]['school_college'] = $list['school_college'];
                $data_arry[$x]['encry_id'] = ADMIN_SITE_URL . "education/view_details/" . encrypt($list['case_id']);
                $data_arry[$x]['university_board'] = $list['university_board'];
                $data_arry[$x]['qualification'] = $list['qualification'];
                $data_arry[$x]['modified_on'] = convert_db_to_display_date($list['modified_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);
                $data_arry[$x]['CandidateName'] = $list['CandidateName'];
                $data_arry[$x]['mode_of_verification'] = $mode_of_verification_value->eduver;
                $data_arry[$x]['school_college'] =  $list['school_college'];
                $data_arry[$x]['DateofBirth'] = convert_db_to_display_date($list['DateofBirth']);
                $data_arry[$x]['NameofCandidateFather'] = $list['NameofCandidateFather'];
                $data_arry[$x]['MothersName'] = $list['MothersName'];
                $data_arry[$x]['ClientRefNumber'] = $list['ClientRefNumber'];
                $data_arry[$x]['roll_no'] = $list['roll_no'];
                $data_arry[$x]['enrollment_no'] = $list['enrollment_no'];
                $data_arry[$x]['major'] = $list['major'];
                $data_arry[$x]['grade_class_marks'] = $list['grade_class_marks'];
                $data_arry[$x]['course_start_date'] = convert_db_to_display_date($list['course_start_date']);
                $data_arry[$x]['course_end_date'] = convert_db_to_display_date($list['course_end_date']);
                $data_arry[$x]['month_of_passing'] = $list['month_of_passing'];
                $data_arry[$x]['remarks'] = $list['remarks'];
                $data_arry[$x]['year_of_passing'] = $list['year_of_passing'];
                $data_arry[$x]['attachment_file'] = $file;

                $data_arry[$x]['action'] = '<button data-id='.$list['id'].'  data-status="'.$list['remarks'].'"  data-toggle="modal" class="btn btn-sm btn-info  showstampModel"> View </button>';

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

    public function education_closure_verifiers_stamp()
    {
        if ($this->input->is_ajax_request()) {

            log_message('error', 'Education Stamp Verifiers closures enties');
            try {

                $details['stamp_verifiers_closures_entries'] = $this->education_model->education_stamp_verifiers_closure_queue(array('view_vendor_master_log.status =' => 1, 'component' => 'eduver'));

            } catch (Exception $e) {
                log_message('error', 'Education::education_closure_verifiers_stamp');
                log_message('error', $e->getMessage());
            }

        } else {
            $details['stamp_verifiers_closures_entries'] = "Something went wrong,please try again";
        }

        echo $this->load->view('admin/education_stamp_verifiers_closure_entries', $details, true);
    }

    public function select_url_details()
    {
        if ($this->input->is_ajax_request()) {
           
            $education_id = $this->input->post('education_id');

            $edu_details =   $this->education_model->select_education('education',array('university_board','qualification','year_of_passing'),array('education.id'=> $education_id));
                
            $check_exists =   $this->education_model->select_education('education_url_details',array('url'),array('university_name' => $edu_details[0]['university_board'],'qualification_name' => $edu_details[0]['qualification'],'year_of_passing' => $edu_details[0]['year_of_passing']));
     
            if(!empty( $check_exists[0]))
            {
                echo $check_exists[0]['url'];
            }
            else{
                echo "";
            }  
        
        } else {

          echo  "Something went wrong,please try again";

        }


    }

    public function select_url_details_spoc()
    {
        if ($this->input->is_ajax_request()) {
           
            $university_id = $this->input->post('university_id');

            $edu_details =   $this->education_model->select_education('university_master',array('url_link'),array('university_master.id'=> $university_id));
                
          
     
            if(!empty( $edu_details[0]))
            {
                echo $edu_details[0]['url_link'];
            }
            else{
                echo "";
            }  
        
        } else {

          echo  "Something went wrong,please try again";

        }


    }


    public function export_education_aq_doc()
    {
     
        $this->load->library('zip');

        set_time_limit(0);

        ini_set('memory_limit', '-1');
            
        $component_check_id = $this->input->post('coomponent_check_id'); 
        
        $component_id = explode(',',$component_check_id);
        
        $all_records = $this->education_model->get_education_detail_for_export($component_id); 
   
          
         foreach($all_records as $all_record){
           

             $attachment = $all_record['attachments'];
        
             $attachment_explode = explode('||',$attachment);
             $counter = 1;
              foreach ($attachment_explode as  $value) {
                 $file_info = pathinfo($value);
                 $extension = $file_info['extension'];

                 $file_upload_path = SITE_BASE_PATH . EDUCATION . $all_record['clientid'].'/'.$value;
                
                 $this->zip->read_file(  SITE_BASE_PATH . EDUCATION . $all_record['clientid'].'/'.$value,$all_record['education_com_ref']."_".$counter. '.' . $extension);

                 $counter++;

              }

            }
            
            require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
            // Create new Spreadsheet object
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

            // Set document properties
            $spreadsheet->getProperties()->setCreator(CRMNAME)
                ->setLastModifiedBy(CRMNAME)
                ->setTitle(CRMNAME)
                ->setSubject('Education records')
                ->setDescription('Education records with their status');

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
            $spreadsheet->getActiveSheet()->getStyle('A1:Q1')->applyFromArray($styleArray);
            // auto fit column to content
            foreach(range('A','Q') as $columnID) {
              $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                  ->setWidth(20);
            }

            // set the names of header cells
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A1",'Component Ref No')
                ->setCellValue("B1",'Candidate Name')
                ->setCellValue("C1",'Fathers Name')
                ->setCellValue("D1",'Mothers Name')
                ->setCellValue("E1",'University')
                ->setCellValue("F1",'Qualification')
                ->setCellValue("G1",'YOP')
                ->setCellValue("H1",'Roll No')
                ->setCellValue("I1",'Enrollment No')
                ->setCellValue("J1",'DOB')
                ->setCellValue("K1",'Client Name')
                ->setCellValue("L1",'Mode of Verification')
                ->setCellValue("M1",'Vendor')
                ->setCellValue("N1",'Vendor Assigned on')
                ->setCellValue("O1",'Insuff Raised Date')
                ->setCellValue("P1",'Insuff Clear Date')
                ->setCellValue("Q1",'Insuff Remark');
            // Add some data
            $x= 2;
            foreach($all_records as $all_record){

                $mode_of_verification  = $all_record['mode_of_verification'];
                
                if(!empty($mode_of_verification))
                {
                    $mode_of_verification_value = json_decode($mode_of_verification);

                    $mode_of_verification_value = $mode_of_verification_value->eduver;
                }
                else
                {
                    $mode_of_verification_value =  "";
                }
   
              
                $insuff_remarks =  $all_record['insuff_raise_remark'];

                $spreadsheet->setActiveSheetIndex(0)
                  
                  ->setCellValue("A$x",$all_record['education_com_ref'])
                  ->setCellValue("B$x",ucwords($all_record['CandidateName']))
                  ->setCellValue("C$x",ucwords($all_record['NameofCandidateFather']))
                  ->setCellValue("D$x",ucwords($all_record['MothersName']))
                  ->setCellValue("E$x",$all_record['university_board'])
                  ->setCellValue("F$x",$all_record['qualification_name'])
                  ->setCellValue("G$x",$all_record['year_of_passing'])
                  ->setCellValue("H$x",$all_record['roll_no'])
                  ->setCellValue("I$x",$all_record['enrollment_no'])
                  ->setCellValue("J$x",convert_db_to_display_date($all_record['DateofBirth']))
                  ->setCellValue("K$x",ucwords($all_record['clientname']))
                  ->setCellValue("L$x",$mode_of_verification_value)
                  ->setCellValue("M$x",$all_record['vendor_name'])
                  ->setCellValue("N$x",convert_db_to_display_date($all_record['created_on'],DB_DATE_FORMAT,DISPLAY_DATE_FORMAT12))
                  ->setCellValue("O$x",$all_record['insuff_raised_date'])
                  ->setCellValue("P$x",$all_record['insuff_clear_date'])
                  ->setCellValue("Q$x",$insuff_remarks);

              $x++;
            }
            // Rename worksheet
            $spreadsheet->getActiveSheet()->setTitle('Education Records');

            $spreadsheet->setActiveSheetIndex(0);

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment;filename=Education  Records.xlsx");
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

            $this->zip->add_data("Education Records ".'.'.'xls',$xlsData);

            //$this->zip->close();
            
            $this->zip->download(''.time().'.zip');       
    }

    public function export_education_spoc_doc()
    {
     
        $this->load->library('zip');

        set_time_limit(0);

        ini_set('memory_limit', '-1');
            
        $component_check_id = $this->input->post('components_check_id'); 
        
        $component_id = explode(',',$component_check_id);
        
        $all_records = $this->education_model->get_education_detail_for_export_spoc($component_id); 
      
          
         foreach($all_records as $all_record){
           

             $attachment = $all_record['attachments'];
        
             $attachment_explode = explode('||',$attachment);
             $counter = 1;
              foreach ($attachment_explode as  $value) {
                 $file_info = pathinfo($value);
                 $extension = $file_info['extension'];

                 $file_upload_path = SITE_BASE_PATH . EDUCATION . $all_record['clientid'].'/'.$value;
                
                 $this->zip->read_file(  SITE_BASE_PATH . EDUCATION . $all_record['clientid'].'/'.$value,$all_record['education_com_ref']."_".$counter. '.' . $extension);

                 $counter++;

              }

            }
            
            require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
            // Create new Spreadsheet object
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

            // Set document properties
            $spreadsheet->getProperties()->setCreator(CRMNAME)
                ->setLastModifiedBy(CRMNAME)
                ->setTitle(CRMNAME)
                ->setSubject('Education records')
                ->setDescription('Education records with their status');

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
            foreach(range('A','K') as $columnID) {
              $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                  ->setWidth(20);
            }

            // set the names of header cells
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A1",'Component Ref No')
                ->setCellValue("B1",'Candidate Name')
                ->setCellValue("C1",'Fathers Name')
                ->setCellValue("D1",'Mothers Name')
                ->setCellValue("E1",'University')
                ->setCellValue("F1",'Qualification')
                ->setCellValue("G1",'YOP')
                ->setCellValue("H1",'Roll No')
                ->setCellValue("I1",'Enrollment No')
                ->setCellValue("J1",'Vendor')
                ->setCellValue("K1",'Vendor Assigned on');
               // ->setCellValue("J1",'Insuff Raised Date')
               // ->setCellValue("K1",'Insuff Clear Date')
               // ->setCellValue("L1",'Insuff Remark');
            // Add some data
            $x= 2;
            foreach($all_records as $all_record){
            
               // $insuff_remarks =  $all_record['insuff_raise_remark'];

                $spreadsheet->setActiveSheetIndex(0)
                  
                  ->setCellValue("A$x",$all_record['education_com_ref'])
                  ->setCellValue("B$x",ucwords($all_record['CandidateName']))
                  ->setCellValue("C$x",ucwords($all_record['NameofCandidateFather']))
                  ->setCellValue("D$x",ucwords($all_record['MothersName']))
                  ->setCellValue("E$x",$all_record['university_board'])
                  ->setCellValue("F$x",$all_record['qualification_name'])
                  ->setCellValue("G$x",$all_record['year_of_passing'])
                  ->setCellValue("H$x",$all_record['roll_no'])
                  ->setCellValue("I$x",$all_record['enrollment_no'])
                  ->setCellValue("J$x",$all_record['vendor_name'])
                  ->setCellValue("K$x",convert_db_to_display_date($all_record['modified_on'],DB_DATE_FORMAT,DISPLAY_DATE_FORMAT12));
                 // ->setCellValue("J$x",$all_record['insuff_raised_date'])
                 // ->setCellValue("K$x",$all_record['insuff_clear_date'])
                //  ->setCellValue("L$x",$insuff_remarks);

              $x++;
            }
            // Rename worksheet
            $spreadsheet->getActiveSheet()->setTitle('Education Records');

            $spreadsheet->setActiveSheetIndex(0);

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment;filename=Education  Records.xlsx");
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

            $this->zip->add_data("Education Records ".'.'.'xls',$xlsData);

            //$this->zip->close();
            
            $this->zip->download(''.time().'.zip');       
    }

    public function export_education_stamp_doc()
    {
     
        $this->load->library('zip');

        set_time_limit(0);

        ini_set('memory_limit', '-1');
            
        $component_check_id = $this->input->post('component_check_id'); 
        
        $component_id = explode(',',$component_check_id);
        
        $all_records = $this->education_model->get_education_detail_for_export_stamp($component_id); 
     
    
         foreach($all_records as $all_record){
           

             $attachment = $all_record['attachments'];
              
             if(!empty( $attachment))
             { 
             $attachment_explode = explode('||',$attachment);
             $counter = 1;
              foreach ($attachment_explode as  $value) {
                 $file_info = pathinfo($value);
                 $extension = $file_info['extension'];

                 $file_upload_path = SITE_BASE_PATH . EDUCATION . $all_record['clientid'].'/'.$value;
                
                 $this->zip->read_file(  SITE_BASE_PATH . EDUCATION . $all_record['clientid'].'/'.$value,$all_record['education_com_ref']."_".$counter. '.' . $extension);

                 $counter++;

              }
             }

            }
            
            require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
            // Create new Spreadsheet object
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

            // Set document properties
            $spreadsheet->getProperties()->setCreator(CRMNAME)
                ->setLastModifiedBy(CRMNAME)
                ->setTitle(CRMNAME)
                ->setSubject('Education records')
                ->setDescription('Education records with their status');

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
                ->setCellValue("B1",'Candidate Name')
                ->setCellValue("C1",'University')
                ->setCellValue("D1",'Qualification')
                ->setCellValue("E1",'Status')
                ->setCellValue("F1",'YOP')
                ->setCellValue("G1",'Vendor')
                ->setCellValue("H1",'Vendor Assigned on');
            // Add some data
            $x= 2;
            foreach($all_records as $all_record){
            
                

                $spreadsheet->setActiveSheetIndex(0)
                  
                  ->setCellValue("A$x",$all_record['education_com_ref'])
                  ->setCellValue("B$x",ucwords($all_record['CandidateName']))
                  ->setCellValue("C$x",$all_record['university_board'])
                  ->setCellValue("D$x",$all_record['qualification_name'])
                  ->setCellValue("E$x",$all_record['status'])
                  ->setCellValue("F$x",$all_record['year_of_passing'])
                  ->setCellValue("G$x",$all_record['vendor_name'])
                  ->setCellValue("H$x",convert_db_to_display_date($all_record['modified_on'],DB_DATE_FORMAT,DISPLAY_DATE_FORMAT12));

              $x++;
            }
            // Rename worksheet
            $spreadsheet->getActiveSheet()->setTitle('Education Records');

            $spreadsheet->setActiveSheetIndex(0);

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment;filename=Education  Records.xlsx");
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

            $this->zip->add_data("Education Records ".'.'.'xls',$xlsData);

            //$this->zip->close();
            
            $this->zip->download(''.time().'.zip');       
    }


    public static function deleteDir($dirPath) {
        if ( is_dir($dirPath)) {
          
            if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
                $dirPath .= '/';
            }
            $files = glob($dirPath . '*', GLOB_MARK);
            foreach ($files as $file) {
                if (is_dir($file)) {
                    self::deleteDir($file);
                } else {
                    unlink($file);
                }
            }
            rmdir($dirPath);
        }
    }


    public function assign_to_send_mail()
    {
        $this->load->library('email');
        $json_array = array();

            $frm_details = $this->input->post();

            $list = explode(',', $frm_details['cases_id']);
             

            if (!empty($list)) {

                $all_records = $this->education_model->get_education_detail_for_export_spoc($list); 
                         

                foreach( $all_records  as $k => $v) {
                    $new_arr[$v['vendor_id']][]=$v;
                }

                foreach ($new_arr as $key => $value) {


                    $count_datewise_record = $this->education_model->get_education_detail_for_export_spoc_count($list,$key);
                    

                    $this->deleteDir(SITE_BASE_PATH .  EDUCATION . 'vendor_mail_file/'. $key );


                    $file_upload_path = SITE_BASE_PATH . EDUCATION . 'vendor_mail_file/'. $key ;

                    if (!folder_exist($file_upload_path)) {
                        mkdir($file_upload_path, 0777,true);
                    } else if (!is_writable($file_upload_path)) {
                        $message = 'Problem while uploading, folder permission';
                    }

                    foreach ($value as  $value_details) {


                        $attachment = $value_details['attachments'];



                         $attachment_explode = explode('||',$attachment);
                         $counter = 1;
                          foreach ($attachment_explode as  $value_attachment) {

                            $file_info = pathinfo($value_attachment);
                            $extension = $file_info['extension'];


                            $file = SITE_BASE_PATH .  EDUCATION . $value_details['clientid'] .'/'. $value_attachment;
                              
                            $newfile = $file_upload_path.'/'.$value_details['education_com_ref']."_".$counter. '.' . $extension;
                            if(file_exists($file))
                            {
                               copy($file,$newfile);
                            }  
                          
                             $counter++;

                          }
                    } 


                    require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
                    // Create new Spreadsheet object
                    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

                    // Set document properties
                    $spreadsheet->getProperties()->setCreator(CRMNAME)
                        ->setLastModifiedBy(CRMNAME)
                        ->setTitle(CRMNAME)
                        ->setSubject('Education records')
                        ->setDescription('Education records with their status');

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
                    $spreadsheet->getActiveSheet()->getStyle('A1:I1')->applyFromArray($styleArray);
                    // auto fit column to content
                    foreach(range('A','J') as $columnID) {
                      $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                          ->setWidth(20);
                    }

                    // set the names of header cells
                    $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue("A1",'Component Ref No')
                        ->setCellValue("B1",'Candidate Name')
                        ->setCellValue("C1",'Fathers Name')
                        ->setCellValue("D1",'Mothers Name')
                        ->setCellValue("E1",'DOB')
                        ->setCellValue("F1",'University')
                        ->setCellValue("G1",'Qualification')
                        ->setCellValue("H1",'YOP')
                        ->setCellValue("I1",'Vendor')
                        ->setCellValue("J1",'Vendor Assigned on');
                       
                    // Add some data
                    $x= 2;
                    foreach($value as $all_record){
                    
                       // $insuff_remarks =  $all_record['insuff_raise_remark'];

                        $spreadsheet->setActiveSheetIndex(0)
                          
                          ->setCellValue("A$x",$all_record['education_com_ref'])
                          ->setCellValue("B$x",ucwords($all_record['CandidateName']))
                          ->setCellValue("C$x",ucwords($all_record['NameofCandidateFather']))
                          ->setCellValue("D$x",ucwords($all_record['MothersName']))
                          ->setCellValue("E$x",convert_db_to_display_date($all_record['DateofBirth']))
                          ->setCellValue("F$x",$all_record['university_board'])
                          ->setCellValue("G$x",$all_record['qualification_name'])
                          ->setCellValue("H$x",$all_record['year_of_passing'])
                          ->setCellValue("I$x",$all_record['vendor_name'])
                          ->setCellValue("J$x",convert_db_to_display_date($all_record['modified_on'],DB_DATE_FORMAT,DISPLAY_DATE_FORMAT12));
                       

                      $x++;
                    }
                    // Rename worksheet
                    $spreadsheet->getActiveSheet()->setTitle('Education Records');

                    $spreadsheet->setActiveSheetIndex(0);

                    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                    header("Content-Disposition: attachment;filename=Education  Records.xlsx");
                    header('Cache-Control: max-age=0');
                    // If you're serving to IE 9, then the following may be needed
                    header('Cache-Control: max-age=1');

                    // If you're serving to IE over SSL, then the following may be needed
                    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
                    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                    header('Pragma: public'); // HTTP/1.0

                

                    $file_name = "Vendor_" .str_replace(" ", "_",$value_details['vendor_name'])  . '_' . 'education' . ".xls";

                    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Excel2007');
                    ob_start();
                    $writer->save($file_upload_path . "/" . $file_name);
                    ob_end_clean();


                    $subject = 'Cases assigned to '.ucwords($value_details['vendor_name']) ." ". date('d-m-Y H:i');


                    $message = "<p>Team,</p><p>The attached cases have been assigned to you. Kindly close within TAT</p>

                  <p><b>Link for attachments :</b> <a href ='".SERVER_URL.SITE_FOLDER.'Download_education/folderdownload/'.base64_encode($key)."'>link </a></p>";

                 $message .= "<table border = '1'>
                  <tr>
                  <th style='text-align:center'>Allocated date </th>
                  <th style='text-align:center'>Cases</th>
                  <th style='text-align:center'>Days</th>
                  </tr>";
                    $total = 0;
                    foreach ($count_datewise_record as $count_datewise_records) {
                        $hold_day = getNetWorkDays($count_datewise_records['date'], date("d-m-Y"));
                        $message .= '<tr>
                  <td style="text-align:center">' . $count_datewise_records['date'] . '</td>
                  <td style="text-align:center">' . $count_datewise_records['count_record'] . '</td>
                  <td style="text-align:center">' . $hold_day . '</td>
                  </tr>';
                        $total += $count_datewise_records['count_record'];
                    }
    
                    $message .= '<tr><td style="text-align:center"><b>Total Cases</b></td><td style="text-align:center" colspan="2"><b>' . $total . '</b></tr>';
                    $message .= "</table>";

                    $message .= "<br><p><b>Regards :</b><p>"; 
                    $message .= "<p>Mist Team<p>"; 

                    $email_tmpl_data['vendor_id'] = $key;
                    $email_tmpl_data['to_emails'] = $value_details['vendor_email_id'];
                    $email_tmpl_data['attchment'] = $file_name;
                    $email_tmpl_data['cc_email_id'] = MAINEMAIL.','.VENDOREMAIL;
                    $email_tmpl_data['vendor_name'] = $value_details['vendor_name'];
                    $email_tmpl_data['message'] = $message;
                    $email_tmpl_data['subject'] = $subject;
   
                    $result = $this->email->education_vendor_case_send_mail($email_tmpl_data);
                    $this->email->clear(true);

                    if(file_exists($file_upload_path.'/'. $file_name))
                    {
                        unlink($file_upload_path.'/'. $file_name);
                    }

                }


                $json_array['status'] = SUCCESS_CODE;

                $json_array['message'] = 'Mail Send Successfully';

            } else {
                $json_array['status'] = ERROR_CODE;
                $json_array['message'] = "Select atleast one case";
            }

        
        echo_json($json_array);
    }



    public function assign_to_stamp_send_mail()
    {
        $this->load->library('email');
        $json_array = array();

            $frm_details = $this->input->post();
    
            $list = explode(',', $frm_details['cases_id']);
             

            if (!empty($list)) {

                $all_records = $this->education_model->get_education_detail_for_export_stamp($list); 

                foreach( $all_records  as $k => $v) {
                    $new_arr[$v['vendor_stamp_id']][]=$v;
                }

                foreach ($new_arr as $key => $value) {


                    $count_datewise_record = $this->education_model->get_education_detail_for_export_stamp_count($list,$key);
                    
                       

                    $this->deleteDir(SITE_BASE_PATH .  EDUCATION . 'vendor_mail_file/'. $key );


                    $file_upload_path = SITE_BASE_PATH . EDUCATION . 'vendor_mail_file/'. $key ;

                    if (!folder_exist($file_upload_path)) {
                        mkdir($file_upload_path, 0777,true);
                    } else if (!is_writable($file_upload_path)) {
                        $message = 'Problem while uploading, folder permission';
                    }

                    foreach ($value as  $value_details) {


                        $attachment = $value_details['attachments'];



                         $attachment_explode = explode('||',$attachment);
                         $counter = 1;
                          foreach ($attachment_explode as  $value_attachment) {

                            $file_info = pathinfo($value_attachment);
                            $extension = $file_info['extension'];


                            $file = SITE_BASE_PATH .  EDUCATION . $value_details['clientid'] .'/'. $value_attachment;
                              
                            $newfile = $file_upload_path.'/'.$value_details['education_com_ref']."_".$counter. '.' . $extension;
                            if(file_exists($file))
                            {
                               copy($file,$newfile);
                            }  
                          
                             $counter++;

                          }
                    } 


                    require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
                    // Create new Spreadsheet object
                    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

                    // Set document properties
                    $spreadsheet->getProperties()->setCreator(CRMNAME)
                        ->setLastModifiedBy(CRMNAME)
                        ->setTitle(CRMNAME)
                        ->setSubject('Education records')
                        ->setDescription('Education records with their status');

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
                        ->setCellValue("B1",'Candidate Name')
                        ->setCellValue("C1",'University')
                        ->setCellValue("D1",'Qualification')
                        ->setCellValue("E1",'Vendor Remark')
                        ->setCellValue("F1",'YOP')
                        ->setCellValue("G1",'Vendor')
                        ->setCellValue("H1",'Vendor Assigned on');

                       
                    // Add some data
                    $x= 2;
                    foreach($value as $all_record){
                    
                       // $insuff_remarks =  $all_record['insuff_raise_remark'];

                        $spreadsheet->setActiveSheetIndex(0)
                          
                          ->setCellValue("A$x",$all_record['education_com_ref'])
                          ->setCellValue("B$x",ucwords($all_record['CandidateName']))
                          ->setCellValue("C$x",$all_record['university_board'])
                          ->setCellValue("D$x",$all_record['qualification_name'])
                          ->setCellValue("E$x",$all_record['vendor_remark'])
                          ->setCellValue("F$x",$all_record['year_of_passing'])
                          ->setCellValue("G$x",$all_record['vendor_name'])
                          ->setCellValue("H$x",convert_db_to_display_date($all_record['modified_on'],DB_DATE_FORMAT,DISPLAY_DATE_FORMAT12));
                       

                      $x++;
                    }
                    // Rename worksheet
                    $spreadsheet->getActiveSheet()->setTitle('Education Records');

                    $spreadsheet->setActiveSheetIndex(0);

                    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                    header("Content-Disposition: attachment;filename=Education  Records.xlsx");
                    header('Cache-Control: max-age=0');
                    // If you're serving to IE 9, then the following may be needed
                    header('Cache-Control: max-age=1');

                    // If you're serving to IE over SSL, then the following may be needed
                    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
                    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                    header('Pragma: public'); // HTTP/1.0

                

                    $file_name = "Vendor_" .str_replace(" ", "_",$value_details['vendor_name'])  . '_' . 'education' . ".xls";

                    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Excel2007');
                    ob_start();
                    $writer->save($file_upload_path . "/" . $file_name);
                    ob_end_clean();

                    $subject = 'Cases for '.ucwords($value_details['vendor_name']) ." ". date('d-m-Y H:i');


                    $message = "<p>Team,</p><p>The attached cases have been assigned to you. Kindly provide reports within TAT.</p>

                  <p><b>Link for attachments :</b> <a href ='".SERVER_URL.SITE_FOLDER.'Download_education/folderdownload/'.base64_encode($key)."'>link </a></p>";

                 $message .= "<table border = '1'>
                  <tr>
                  <th style='text-align:center'>Allocated date </th>
                  <th style='text-align:center'>Cases</th>
                  <th style='text-align:center'>Days</th>
                  </tr>";
                    $total = 0;
                    foreach ($count_datewise_record as $count_datewise_records) {
                        $hold_day = getNetWorkDays($count_datewise_records['date'], date("d-m-Y"));
                        $message .= '<tr>
                  <td style="text-align:center">' . $count_datewise_records['date'] . '</td>
                  <td style="text-align:center">' . $count_datewise_records['count_record'] . '</td>
                  <td style="text-align:center">' . $hold_day . '</td>
                  </tr>';
                        $total += $count_datewise_records['count_record'];
                    }

                    $message .= '<tr><td style="text-align:center"><b>Total Cases</b></td><td style="text-align:center" colspan="2"><b>' . $total . '</b></tr>';
                    $message .= "</table>";

                    $message .= "<br><p><b>Regards :</b><p>"; 
                    $message .= "<p>Mist Team<p>"; 

                    $email_tmpl_data['vendor_id'] = $key;
                    $email_tmpl_data['to_emails'] = $value_details['vendor_email_id'];
                    $email_tmpl_data['attchment'] = $file_name;
                    $email_tmpl_data['cc_email_id'] = MAINEMAIL.','.VENDOREMAIL;
                    $email_tmpl_data['vendor_name'] = $value_details['vendor_name'];
                    $email_tmpl_data['message'] = $message;
                    $email_tmpl_data['subject'] = $subject;
        
                    $result = $this->email->education_vendor_case_send_mail($email_tmpl_data);
                    $this->email->clear(true);

                    if(file_exists($file_upload_path.'/'. $file_name))
                    {
                        unlink($file_upload_path.'/'. $file_name);
                    }

                }


                $json_array['status'] = SUCCESS_CODE;

                $json_array['message'] = 'Mail Send Successfully';

            } else {
                $json_array['status'] = ERROR_CODE;
                $json_array['message'] = "Select atleast one case";
            }

        
        echo_json($json_array);
    }

    public function university_image_selection($university_id)
    {

        if ($university_id) {
          
            $university_attachments = $this->education_model->select_file_universtity('university_master_image',array('id', 'file_name', 'status'), array('university_id' => $university_id, 'status' => 1));

            if(!empty($university_attachments))
            {

               $data['university_attachments'] = $university_attachments;

               echo $this->load->view('admin/show_university_images', $data, true);
            }
            else{
                 echo "<h4>Record Not Found</h4>";
            }

         
           
        } else {
            echo "<h4>Record Not Found</h4>";
        }
    }

    
    public function university_image_selection_idwise($education_id)
    {

        if ($education_id) {

            $education_details = $this->education_model->select_file_universtity('education',array('university_board'), array('id' => $education_id));
            if(!empty( $education_details ))
            {
          
                $university_attachments = $this->education_model->select_file_universtity('university_master_image',array('id', 'file_name', 'status'), array('university_id' => $education_details[0]['university_board'], 'status' => 1));

                if(!empty($university_attachments))
                {

                   $data['university_attachments'] = $university_attachments;

                   echo $this->load->view('admin/show_university_images', $data, true);
                }
                else{
                     echo "<h4>Record Not Found</h4>";
                }

            }
            else
            {
                echo "<h4>Education Not Found</h4>"; 
            }
           
        } else {
            echo "<h4>Record Not Found</h4>";
        }
    }
  
}

