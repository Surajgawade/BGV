<?php defined('BASEPATH') or exit('No direct script access allowed');
class Address extends MY_Controller
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

        $this->perm_array = array('page_id' => 4);
        if ($this->input->is_ajax_request()) {
            $this->perm_array = array('direct_access' => true);

        }
    
        $this->assign_options = array('0' => 'Select Executive', '1' => 'Assign to Executive');
        $this->assign_options_vendor = array('0' => 'Select Vendor', '1' => 'Assign to Vendor');
        $this->load->model(array('addressver_model'));
    }

    public function index()
    {
        $data['header_title'] = "Address Verification";

       // $data['filter_view'] = $this->filter_view();
       // $data['users_list'] = $this->addressver_model->get_assign_users('user_profile', false, array("`user_profile`.`status`,`user_profile`.`id`,`user_profile`.`email`,`user_profile`.`department`,`user_profile`.`user_name`,concat(`user_profile`.`firstname`,' ',`user_profile`.`lastname`) as fullname,`user_profile`.`profile_pic`,`user_profile`.`firstname`,`user_profile`.`lastname`,`roles`.`role_name`,`roles`.`groups_id`,`roles_permissions`.*"), array('user_profile.status' => STATUS_ACTIVE));

        $data['vendor_list'] = $this->vendor_list('addrver');

        $data['clients_id'] = $this->get_clients(array('status' => STATUS_ACTIVE));

        $data['status'] = $this->get_status();

        $data['clients'] = $this->get_clients();

        $data['user_list_name'] = $this->users_list_filter();

        $data['status_value'] = $this->get_status();

        $this->load->view('admin/header', $data);

        $this->load->view('admin/address_list');

        $this->load->view('admin/footer');
    }

    public function get_clients_for_address_list_view()
    {
        log_message('error', 'For address list view get client');
        try {
            $params = $_REQUEST;

            $clients = $this->addressver_model->select_client_list_view_address('clients', false, array('clients.id', 'clients.clientname'), $params);

            $clients_arry[0] = 'Select Client';

            foreach ($clients as $key => $value) {
                $clients_arry[$value['id']] = ucwords(strtolower($value['clientname']));
            }

            echo_json(array('client_list' => $clients_arry));

        } catch (Exception $e) {
            log_message('error', 'Address::get_clients_for_address_list_view');
            log_message('error', $e->getMessage());
        }
    }

    public function add($candsid = false)
    {
        if ($this->input->is_ajax_request()) {

            log_message('error', 'Address Add');
            try {

                $data['get_cands_details'] = $this->candidate_entity_pack_details($candsid);
                $data['states'] = $this->get_states();

                $data['mode_of_verification'] = $this->addressver_model->get_mode_of_verification(array('entity' => $data['get_cands_details']['entity_id'], 'package' => $data['get_cands_details']['package_id'], 'tbl_clients_id' => $data['get_cands_details']['clientid']));

             // $data['assigned_user_id'] = $this->addressver_model->get_assign_users_id('user_profile', false, array("`user_profile`.`status`,`user_profile`.`id`,`user_profile`.`email`,`user_profile`.`department`,`user_profile`.`user_name`,concat(`user_profile`.`firstname`,' ',`user_profile`.`lastname`) as fullname,`user_profile`.`profile_pic`,`user_profile`.`firstname`,`user_profile`.`lastname`,`roles`.`role_name`,`roles`.`groups_id`,`roles_permissions`.*"), array('user_profile.status' => STATUS_ACTIVE));
                $data['assigned_user_id']  = $this->get_reporting_manager_for_execituve($data['get_cands_details']['clientid']);
             
                echo $this->load->view('admin/address_add', $data, true);

            } catch (Exception $e) {
                log_message('error', 'Address::add');
                log_message('error', $e->getMessage());
            }

        } else {
            echo "<h3>Something went wrong, please try again</h3>";
        }
    }

    protected function add_com_ref($insert_id)
    {
        log_message('error', 'Address Ref No Creation');
        try {
            $name = COMPONENT_REF_NO['ADDRESS'];

            $addressnumber = $name . $insert_id;

            $field_array = array('add_com_ref' => $addressnumber);

            $update_auto_increament_id = $this->addressver_model->update_auto_increament_value($field_array, array('id' => $insert_id));

            return $addressnumber;

        } catch (Exception $e) {
            log_message('error', 'Address::add_com_ref');
            log_message('error', $e->getMessage());
        }

    }

    protected function bulk_add_com_ref()
    {
        $lists = $this->addressver_model->add_com_ref();

        return (!empty($lists)) ? $lists['A_I'] + 1 : 1000;
    }

    public function save_address()
    {
        if ($this->input->is_ajax_request()) {

            $this->form_validation->set_rules('clientid', 'Client', 'required');
            $this->form_validation->set_rules('candsid', 'Candidate', 'required');
            $this->form_validation->set_rules('address', 'Address', 'required');
            $this->form_validation->set_rules('pincode', 'PIN code', 'required');

            $this->form_validation->set_rules('state', 'State', 'required');

            if ($this->form_validation->run() == false) {

                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');

            } else {
                log_message('error', 'New Address Creation');
                try {
           
                    $frm_details = $this->input->post();
                    
                    $error_msgs = array();
                    $file_upload_path = SITE_BASE_PATH . ADDRESS . $frm_details['clientid'];
                    if (!folder_exist($file_upload_path)) {
                        mkdir($file_upload_path, 0777);
                    } else if (!is_writable($file_upload_path)) {
                        array_push($error_msgs, 'Problem while uploading');
                    }

                    $tat_day = $this->get_client_entity_package_tat_day(array('tbl_clients_id' => $frm_details['clientid'], 'entity' => $frm_details['entity_id'], 'package' => $frm_details['package_id']));

                    $get_holiday1 = $this->get_holiday();

                    $get_holiday = array_map('current', $get_holiday1);

                    $closed_date = getWorkingDays(convert_display_to_db_date($frm_details['iniated_date']), $get_holiday, $tat_day[0]['tat_addrver']);

                    $field_array = array('clientid' => $frm_details['clientid'],
                        'candsid' => $frm_details['candsid'],
                        'add_com_ref' => '',
                        'stay_from' => $frm_details['stay_from'],
                        'stay_to' => $frm_details['stay_to'],
                        'address_type' => $frm_details['address_type'],
                        'address' => $frm_details['address'],
                        'city' => $frm_details['city'],
                        'pincode' => $frm_details['pincode'],
                        'state' => $frm_details['state'],
                        'mod_of_veri' => $frm_details['mod_of_veri'],
                        'created_by' => $this->user_info['id'],
                        'created_on' => date(DB_DATE_FORMAT),
                        'modified_on' => date(DB_DATE_FORMAT),
                        'modified_by' => $this->user_info['id'],
                        'has_case_id' => $frm_details['has_case_id'],
                        'has_assigned_on' => date(DB_DATE_FORMAT),
                        'is_bulk_uploaded' => 0,
                        'iniated_date' => convert_display_to_db_date($frm_details['iniated_date']),
                        "add_re_open_date" => '',
                        "due_date" => $closed_date,
                        "tat_status" => 'IN TAT',
                    );

                    $result = $this->addressver_model->save(array_map('strtolower', $field_array));

                    $add_com_ref = $this->add_com_ref($result);

                    $config_array = array('file_upload_path' => $file_upload_path, 'file_permission' => 'jpeg|jpg|png|pdf|docx|xls|xlsx', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 1000, 'file_id' => $result, 'component_name' => 'addrver_id');

                    if (empty($error_msgs)) {
                        if ($_FILES['attchments']['name'][0] != '') {
                            $config_array['files_count'] = count($_FILES['attchments']['name']);
                            $config_array['file_data'] = $_FILES['attchments'];
                            $config_array['type'] = 0;
                            $retunr_de = $this->file_upload_multiple($config_array);
                            if (!empty($retunr_de)) {
                                $this->common_model->common_insert_batch('addrver_files', $retunr_de['success']);
                            }
                        }

                        if ($_FILES['attchments_cs']['name'][0] != '') {
                            $config_array['files_count'] = count($_FILES['attchments_cs']['name']);
                            $config_array['file_data'] = $_FILES['attchments_cs'];
                            $config_array['type'] = 2;
                            $retunr_cd = $this->file_upload_multiple($config_array);
                            if (!empty($retunr_cd)) {
                                $this->common_model->common_insert_batch('addrver_files', $retunr_cd['success']);
                            }
                        }

                        if (isset($frm_details['upload_capture_image_address'])) {

                            if ($frm_details['upload_capture_image_address']) {

                                $upload_capture_image = explode("||", $frm_details['upload_capture_image_address']);
                                    
                                foreach ($upload_capture_image as $key => $value) {
                                    $key = $key + 1;

                                    $file_name = $add_com_ref . '-' .$key. '-' . date(UPLOAD_FILE_DATE_FORMAT) . '.png';
                                    $uploadpath = $file_upload_path . '/' . $file_name;
                                    $base64_to_jpeg = base64_to_jpeg($value, $uploadpath);
                                        if ($base64_to_jpeg) {
                                            log_message('error', 'Inside if condition success');
                                            $this->common_model->save('addrver_files', ['file_name' => $file_name, 'real_filename' => $file_name,'status' => 1,'type' => 0,'addrver_id' => $result]);
                                        }

                                }
                            }
                        }


                    }

                    if ($result) {

                        $field = array('candsid' => $frm_details['candsid'],
                            'ClientRefNumber' => '',
                            'comp_table_id' => $result,
                            'activity_mode' => '',
                            'activity_status' => 'New check',
                            'activity_type' => 'New check',
                            'action' => 'New check',
                            'next_follow_up_date' => null,
                            'remarks' => 'New Check Added',
                            'created_on' => date(DB_DATE_FORMAT),
                            'created_by' => $this->user_info['id'],
                            'is_auto_filled' => 0,

                        );

                        $result_trigger = $this->addressver_model->save_trigger($field);

                        $user_activity_data = $this->common_model->user_actity_data(array('component' => "Address",
                            'ref_no' => $add_com_ref, 'candidate_name' => $frm_details['CandidateName'], 'created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id'], 'activity_type' => 'Manual', 'action' => 'Add'));

                        if ($result) {
                            auto_update_tat_status($frm_details['candsid']);

                            auto_update_overall_status($frm_details['candsid']);

                            $mode_of_verification = $this->addressver_model->get_mode_of_verification(array('tbl_clients_id' => $frm_details['clientid'],'entity' =>$frm_details['entity_id'],'package' =>$frm_details['package_id'])); 

                            if(!empty($mode_of_verification))
                            {
                               $mode_of_verification_value = json_decode($mode_of_verification[0]['mode_of_verification']);
                            }

                            if(isset($mode_of_verification_value->addrver) && ($mode_of_verification_value->addrver  != "digital"))
                            {

                                $vendor_id_state = $this->addressver_model->get_vendor_id_find_state($frm_details['state']); 
                                
                                if(!empty($vendor_id_state))
                                {
                                    if($frm_details['state'] == "maharashtra" || $frm_details['state'] == "Maharashtra")
                                    {
                                        $vendor_id_city = $this->addressver_model->get_vendor_id_find_city($frm_details['city']); 
                                        
                                        if(!empty($vendor_id_city))
                                        {
                                            $vendor_id = $vendor_id_city[0]['id'];
                                        }
                                        else{
                                        
                                            $vendor_id = array(); 

                                        }
                                    }
                                    else{

                                        $vendor_id = $vendor_id_state[0]['id'];
                                    }

                                }
                                else{
                                    $vendor_id = array();
                                }
                                
                           
                                if(!empty($vendor_id))
                                { 
                           
                                
                                    $update = $this->addressver_model->upload_vendor_assign('addrver', array('vendor_id' => $vendor_id, 'vendor_list_mode' => 'physical visit', 'vendor_assgined_on' => date(DB_DATE_FORMAT)), array('id' => $result, 'vendor_id =' => 0));

                                    $update1 = $this->addressver_model->update_status('addrverres', array('verfstatus' => 1), array('addrverid' => $result));

                                    if ($update) {

                                        $fiels = array(
                                            'vendor_id' => $vendor_id,
                                            'case_id' => $result,
                                            "status" => 0,
                                            "remarks" => '',
                                            "created_by" => 1,
                                            "created_on" => date(DB_DATE_FORMAT),
                                            "approval_by" => 0,
                                            "modified_on" => null,
                                            "modified_by" => '',
                                        );

                                      $save_vendor_log = $this->addressver_model->save_vendor_log($fiels);
               
                                    }
                                }
                            }

                            if(isset($mode_of_verification_value->addrver) && ($mode_of_verification_value->addrver  == "digital"))
                            { 
                                $update = $this->addressver_model->upload_vendor_assign('addrver', array('vendor_digital_id' => 25, 'vendor_digital_list_mode' => 'digital', 'vendor_digital_assgined_on' => date(DB_DATE_FORMAT)), array('id' => $result, 'vendor_id =' => 0));

                                $update1 = $this->addressver_model->update_status('addrverres', array('verfstatus' => 1), array('addrverid' => $result));

                                $this->cli_address_invite_mail($result);

                                if($update && $update1)
                                {
                                    $user_name =  $this->addressver_model->select_address_dt("user_profile",array("user_name"), array("id" => $this->user_info['id']));
                                    $vendor_name =  $this->addressver_model->select_address_dt("vendors",array("vendor_name"), array("id" => 25));
                        
                                    $result_address_activity_data = $this->addressver_model->initiated_date_addrver_activity_data(array('created_on' =>date(DB_DATE_FORMAT),'created_by' => $this->user_info['id'], 'comp_table_id' => $result, 'activity_type' => "Mist it", 'activity_status' => "Assign", 'remarks' => $user_name[0]['user_name'].' has assigned the case to '.$vendor_name[0]['vendor_name']));
                                }
                            } 
                            if ($result) {

                                $json_array['status'] = SUCCESS_CODE;

                                $json_array['message'] = 'Address Record Successfully Inserted';

                                $json_array['redirect'] = ADMIN_SITE_URL . 'address';

                            } else {

                                $json_array['status'] = ERROR_CODE;

                                $json_array['message'] = 'Something went wrong,please try again';
                            }
                        } else {
                            $json_array['status'] = ERROR_CODE;

                            $json_array['message'] = 'Something went wrong,please try again';
                        }

                    } else {
                        $json_array['status'] = ERROR_CODE;

                        $json_array['message'] = 'Something went wrong,please try again';
                    }
                    echo_json($json_array);

                } catch (Exception $e) {
                    log_message('error', 'Address::save_address');
                    log_message('error', $e->getMessage());
                }
            }
        }
    }

    public function address_view_datatable()
    {
        if ($this->permission['access_address_list_view'] == true) {

            log_message('error', 'Address View');
            try {

                $params = $add_candidates = $data_arry = $columns = array();

                $params = $_REQUEST;

                $columns = array('addrver_id.id', 'candidates_info.ClientRefNumber', 'candidates_info.cmp_ref_no', 'company_database.coname', 'candidates_info.CandidateName', 'candidates_info.caserecddate', 'verfstatus', 'city', 'encry_id', 'address', 'pincode', 'state', 'check_closure_date', 'add_com_ref', 'iniated_date');

                $where_arry = array();

                $add_candidates = $this->addressver_model->get_all_addrs_by_client_datatable($params, $columns);

                $totalRecords = count($this->addressver_model->get_all_addrs_by_client_datatable_count($params, $columns));

                $x = 0;

                foreach ($add_candidates as $add_candidate) {
                    $data_arry[$x]['id'] = $x + 1;
                  //  $data_arry[$x]['checkbox'] = $add_candidate['id'];
                    $data_arry[$x]['ClientRefNumber'] = $add_candidate['ClientRefNumber'];
                    $data_arry[$x]['add_com_ref'] = $add_candidate['add_com_ref'];
                    $data_arry[$x]['cmp_ref_no'] = $add_candidate['cmp_ref_no'];
                    $data_arry[$x]['iniated_date'] = convert_db_to_display_date($add_candidate['iniated_date']);
                    $data_arry[$x]['clientname'] = $add_candidate['clientname'];
                    $data_arry[$x]['vendor_name'] = ($add_candidate['vendor_name'] != '0') ? $add_candidate['vendor_name'] : '';
                    $data_arry[$x]['CandidateName'] = $add_candidate['CandidateName'];
                    $data_arry[$x]['address'] = $add_candidate['address'];
                    $data_arry[$x]['city'] = $add_candidate['city'];
                    $data_arry[$x]['last_activity_date'] = convert_db_to_display_date($add_candidate['last_activity_date'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);
                    $data_arry[$x]['status_value'] = $add_candidate['status_value'];
                    $data_arry[$x]['encry_id'] = ADMIN_SITE_URL . "address/view_details/" . encrypt($add_candidate['id']);
                    $data_arry[$x]['state'] = $add_candidate['state'];
                    $data_arry[$x]['pincode'] = $add_candidate['pincode'];
                    // $data_arry[$x]['first_qc_approve'] = $add_candidate['first_qc_approve'];
                    $data_arry[$x]['executive_name'] = $add_candidate['user_name'];
                    $data_arry[$x]['tat_status'] = $add_candidate['tat_status'];
                    $data_arry[$x]['check_closure_date'] = convert_db_to_display_date($add_candidate['due_date']);
                    $data_arry[$x]['mod_of_veri'] = $add_candidate['mod_of_veri'];
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
                log_message('error', 'Address::address_view_datatable');
                log_message('error', $e->getMessage());
            }
        } else {
            $client_new_cases = $data_arry = array();

            $json_data = array("draw" => intval(1),
                "recordsTotal" => "Not Permission",
                "recordsFiltered" => null,
                "data" => $data_arry,

            );
            echo_json($json_data);
        }
    }

    public function get_assign_address_list_view()
    {
        log_message('error', 'Address List Assign in Assign View');
        try {
            $data['header_title'] = "Address Verification";

            $data['filter_view'] = $this->filter_view_assign_address_list();
            $data['users_list'] = $this->users_list();
            $data['vendor_list'] = $this->vendor_list('addrver');

        } catch (Exception $e) {
            log_message('error', 'Address::get_assign_address_list_view');
            log_message('error', $e->getMessage());
        }

        echo $this->load->view('admin/assign_add', $data, true);
    }

    protected function filter_view_assign_address_list($true = false)
    {

        if ($true) {
            //$data['status'] = status_frm_db();
            $data['status'] = $this->get_status_candidate();
        } else {
            $data['status'] = $this->get_status();
        }
        $data['clients'] = $this->get_clients_for_list_view_address();
        $data['user_list_name'] = $this->users_list_filter();

        return $this->load->view('admin/filter_view_assign', $data, true);
    }
    public function get_clients_for_list_view_address()
    {
        $this->load->model(array('assign_add_model'));
        $clients = $this->assign_add_model->select_client_list_assign_add_view('clients', false, array('clients.id', 'clients.clientname'));

        $clients_arry[0] = 'Select Client';

        foreach ($clients as $key => $value) {
            $clients_arry[$value['id']] = ucwords(strtolower($value['clientname']));
        }
        return $clients_arry;
    }

    public function view_details($addsver_id = '')
    {
        $addressver_details = $this->addressver_model->get_address_details(array('addrver.id' => decrypt($addsver_id)));

        if ($addsver_id && !empty($addressver_details)) {

            log_message('error', 'Address Edit View');
            try {
                $data['header_title'] = 'Edit Address Verification';

                $data['states'] = $this->get_states();

                //$data['assigned_user_id'] = $this->addressver_model->get_assign_users_id('user_profile', false, array("`user_profile`.`status`,`user_profile`.`id`,`user_profile`.`email`,`user_profile`.`department`,`user_profile`.`user_name`,concat(`user_profile`.`firstname`,' ',`user_profile`.`lastname`) as fullname,`user_profile`.`profile_pic`,`user_profile`.`firstname`,`user_profile`.`lastname`,`roles`.`role_name`,`roles`.`groups_id`,`roles_permissions`.*"), array('user_profile.status' => STATUS_ACTIVE));

                $data['assigned_user_id']  = $this->get_reporting_manager_for_execituve($addressver_details[0]['clientid']);


                $data['get_cands_details'] = $this->candidate_entity_pack_details($addressver_details[0]['candsid']);


                $data['mode_of_verification'] = $this->addressver_model->get_mode_of_verification(array('entity' => $data['get_cands_details']['entity_id'], 'package' => $data['get_cands_details']['package_id'], 'tbl_clients_id' => $data['get_cands_details']['clientid']));

                $data['addressver_details'] = $addressver_details[0];

                $data['reinitiated'] = ($addressver_details[0]['var_filter_status'] == 'Closed' || $addressver_details[0]['var_filter_status'] == 'closed') ? '1' : '2';

                $check_insuff_raise = $this->addressver_model->select_insuff(array('addrverid' => decrypt($addsver_id), 'status' => STATUS_ACTIVE, 'insuff_clear_date is null' => null));

                //$check_vendor = $this->addressver_model->select_vendor();

                $vendor_log = $this->addressver_model->get_approve_massage(array('address_vendor_log.status' => 0,'address_vendor_log.case_id' =>  decrypt($addsver_id)));

                

                $data['insuff_reason_list'] = $this->addressver_model->insuff_reason_list(false, array('component_id' => 1));

                $data['insuff_raise_message'] = (!empty($check_insuff_raise) ? 'Insufficiency raised on ' . convert_db_to_display_date($check_insuff_raise[0]['insuff_raised_date']) . ' for ' . $check_insuff_raise[0]['insff_reason'] : '');

               $data['assign_list_pending'] = (!empty( $vendor_log) ? 'Case pending in approve Queue' : '');

                $data['check_insuff_raise'] = ((!empty($check_insuff_raise)) ? 'disabled' : '');
                $data['check_insuff_value'] = ((!empty($check_insuff_raise)) ? '1' : '2');
                $data['insuff_raise_id'] = (!empty($check_insuff_raise) ? $check_insuff_raise[0]['id'] : '');

               // $data['check_vendor'] = ((!empty($check_vendor)) ? 'disabled' : '');

                $transaction_id = $this->addressver_model->check_transaction_id_exists($addressver_details[0]['id']); 

                if(!empty($data['mode_of_verification'][0]))
                {
                    $mode_of_verification_value = json_decode( $data['mode_of_verification'][0]['mode_of_verification']);
                }


                if(isset($mode_of_verification_value->addrver) && ($mode_of_verification_value->addrver  == "digital") && empty($transaction_id))
                {
                    $data['physical_button']  = 1;  
                }
                else{

                    $data['physical_button']  = 2;  
                }

                $data['attachments'] = $this->addressver_model->select_file_address(array('id', 'file_name', 'real_filename', 'type'), array('addrver_id' => decrypt($addsver_id), 'status' => 1));

                $vendor_id = $this->addressver_model->get_vendor_id_drop_down($addressver_details[0]['state']); 
                
                if(!empty($vendor_id) && count($vendor_id) > 1)
                {   
                    if($addressver_details[0]['state'] == "maharashtra" || $addressver_details[0]['state'] == "Maharashtra")
                    {
                        $vendor_id = $this->addressver_model->get_vendor_id_drop_down(); 
                        
                        $data['vendor_id'] = $vendor_id;
                    }
                    {
                      $data['vendor_id'] = $vendor_id;
                    }
                }
                else{
                   
                     $vendor_id = $this->addressver_model->get_vendor_id_drop_down(); 
                    
                     $data['vendor_id'] = $vendor_id;
                }
             
                $this->load->view('admin/header', $data);

                $this->load->view('admin/address_edit');

                $this->load->view('admin/footer');

            } catch (Exception $e) {
                log_message('error', 'Address::view_details');
                log_message('error', $e->getMessage());
            }

        } else {
            show_404();
        }
    }

    public function view_form_inside($add_comp_id = false)
    {
        $add_details = $this->addressver_model->get_address_details(array('addrver.add_com_ref' => $add_comp_id));

        if ($add_comp_id && !empty($add_details)) {

            log_message('error', 'Address Add From Candidate');
            try {
                $data['header_title'] = 'Edit Address';

                $data['states'] = $this->get_states();

                //  $data['assigned_user_id'] = $this->users_list();
                //$data['assigned_user_id'] = $this->addressver_model->get_assign_users_id('user_profile', false, array("`user_profile`.`status`,`user_profile`.`id`,`user_profile`.`email`,`user_profile`.`department`,`user_profile`.`user_name`,concat(`user_profile`.`firstname`,' ',`user_profile`.`lastname`) as fullname,`user_profile`.`profile_pic`,`user_profile`.`firstname`,`user_profile`.`lastname`,`roles`.`role_name`,`roles`.`groups_id`,`roles_permissions`.*"), array('user_profile.status' => STATUS_ACTIVE));

                $data['assigned_user_id']  = $this->get_reporting_manager_for_execituve($add_details[0]['clientid']);

                $data['get_cands_details'] = $this->candidate_entity_pack_details($add_details[0]['candsid']);

                $data['mode_of_verification'] = $this->addressver_model->get_mode_of_verification(array('entity' => $data['get_cands_details']['entity_id'], 'package' => $data['get_cands_details']['package_id'], 'tbl_clients_id' => $data['get_cands_details']['clientid']));

                $data['addressver_details'] = $add_details[0];

                $data['reinitiated'] = ($add_details[0]['var_filter_status'] == 'Closed' || $add_details[0]['var_filter_status'] == 'closed') ? '1' : '2';

                $check_insuff_raise = $this->addressver_model->select_insuff(array('addrverid' => $add_details[0]['id'], 'status' => STATUS_ACTIVE, 'insuff_clear_date is null' => null));

                $data['insuff_reason_list'] = $this->insuff_reason_list(1);

                $data['insuff_raise_message'] = (!empty($check_insuff_raise) ? 'Insufficiency raised on ' . convert_db_to_display_date($check_insuff_raise[0]['insuff_raised_date']) . ' for ' . $check_insuff_raise[0]['insff_reason'] : '');

                $data['attachments'] = $this->addressver_model->select_file_address(array('id', 'file_name', 'real_filename', 'type'), array('addrver_id' => $add_details[0]['id'], 'status' => 1));

                $data['check_insuff_raise'] = ((!empty($check_insuff_raise)) ? 'disabled' : '');
                $data['check_insuff_value'] = ((!empty($check_insuff_raise)) ? '1' : '2');
                $data['insuff_raise_id'] = (!empty($check_insuff_raise) ? $check_insuff_raise[0]['id'] : '');

                echo $this->load->view('admin/address_edit_form_inside', $data, true);

            } catch (Exception $e) {
                log_message('error', 'Address::view_form_inside');
                log_message('error', $e->getMessage());
            }
        } else {
            echo "<h3>Something went wrong, please try again</h3>";
        }
    }

    public function insuff_raised()
    { 
        $this->load->library('email');
        $json_array = array();
        $json_array['status'] = ERROR_CODE;
        $json_array['message'] = 'Something went wrong,please try again';

        if ($this->input->is_ajax_request()) {

            log_message('error', 'Address Add Raised Insuff');
            try {
             
                $addrverid = $this->input->post('update_id');

                $insff_reason = $this->input->post('insff_reason');

                $insff_date = $this->input->post('txt_insuff_raise');

                $ref_no = $this->input->post('component_ref_no');

                $CandidateName = $this->input->post('CandidateName');

                $check = $this->addressver_model->select_insuff(array('addrverid' => $addrverid, 'status !=' => 3, 'insuff_clear_date is null' => null));

                if (empty($check)) {
                    $result = $this->addressver_model->save_update_insuff(array('insuff_raised_date' => convert_display_to_db_date($insff_date), 'insuff_raise_remark' => $this->input->post('insuff_raise_remark'), 'status' => STATUS_ACTIVE, 'insff_reason' => $insff_reason, 'created_by' => $this->user_info['id'], 'addrverid' => $addrverid, 'auto_stamp' => 1));

                    if ($result) {
                        
                        $case_activity = $this->common_model->get_case_activity_status(array('entity' => $this->input->post('entity_id'), 'package' => $this->input->post('package_id'), 'tbl_clients_id' => $this->input->post('clientid')));

                        if(!empty($case_activity)){
                            if($case_activity[0]['case_activity'] == "1"){

                                $address_details = $this->addressver_model->get_address_details_for_insuff_mail(array('addrver.id' => $addrverid));

                                $users_id  = $this->get_reporting_manager_for_executive($this->input->post('clientid')); 
                                $email = $this->addressver_model->get_user_email_id(array('user_profile.id' => $users_id[0]['id']));

                                $spoc_email_id = $this->common_model->select_spoc_mail_id($this->input->post('clientid'));

                                $subject = 'Insufficiency raised in Address for '.ucwords($this->input->post('CandidateName'));
                                $message = "<p>Team,</p><p>Insufficiency has been raised for the following reason.</p>";


                                $message .= "<table border = '2'  style='border-spacing: 10; border-collapse: collapse;'>
                                <tr>
                                    <td style='text-align:center;background-color: #EDEDED;'>Component</td>
                                    <td style='text-align:center'>Address</td>
                                </tr>
                               <tr>
                                    <td style='text-align:center;background-color: #EDEDED;'>Candidate Name</td>
                                    <td style='text-align:center'>".ucwords($this->input->post('CandidateName'))."</td>
                                </tr>
                                <tr>
                                    <td style='text-align:center;background-color: #EDEDED;'>Entity</td>
                                    <td style='text-align:center'>".ucwords( $address_details[0]['entity_name'] )."</td>
                                </tr>
                                <tr>
                                    <td style='text-align:center;background-color: #EDEDED;'>Spoc/Package</td>
                                    <td style='text-align:center'>".ucwords( $address_details[0]['package_name'])."</td>
                                </tr>
                                <tr>
                                    <td style='text-align:center;background-color: #EDEDED;'>Client Ref No</td>
                                    <td style='text-align:center'>".$address_details[0]['ClientRefNumber']."</td>
                                </tr>
                                <tr>
                                    <td style='text-align:center;background-color: #EDEDED;'>Mist Ref No</td>
                                    <td style='text-align:center'>".$address_details[0]['cmp_ref_no']."</td>
                                </tr>
                                <tr>
                                    <td style='text-align:center;background-color: #EDEDED;'>Raised Date</td>
                                    <td style='text-align:center'>".$insff_date."</td>
                                </tr>
                                <tr>
                                    <td style='text-align:center;background-color: #EDEDED;'>Details</td>
                                    <td style='text-align:center'>".ucwords($address_details[0]['address']).",".ucwords($address_details[0]['city']).",".ucwords($address_details[0]['state'])." - ".$address_details[0]['pincode']."</td>
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

                       $user_activity_data = $this->common_model->user_actity_data(array('component' => "Address", 'ref_no' => $ref_no, 'candidate_name' => $CandidateName, 'created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id'], 'activity_type' => 'Manual', 'action' => 'Insuff Raised'));
                        

                    auto_update_overall_status($this->input->post('candidates_info_id'));

                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'Record Successfully Inserted';
                    }

                } else {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'already insuff raised, please close this and raise again';
                }

            } catch (Exception $e) {
                log_message('error', 'Address::insuff_raised');
                log_message('error', $e->getMessage());
            }
        }
        echo_json($json_array);
    }

    public function add_reinitiated_date()
    {

        $json_array = array();

        if ($this->input->is_ajax_request()) {

            log_message('error', 'Address Reninitiated Date');
            try {

                $addrverid = $this->input->post('update_id');
                $reinitiated_date = $this->input->post('reinitiated_date');
                $reinitiated_remark = $this->input->post('reinitiated_remark');
                $clientid = $this->input->post('clientid');

                $check = $this->addressver_model->select_reinitiated_date(array('id' => $addrverid));

                if ($check[0]['add_re_open_date'] == "0000-00-00" || $check[0]['add_re_open_date'] == "") {
                    $reinitiated_dates = $reinitiated_date;
                } else {
                    $reinitiated_dates = $check[0]['add_re_open_date'] . "||" . $reinitiated_date;
                }

                $result = $this->addressver_model->save_update_initiated_date(array('add_re_open_date' => $reinitiated_dates, 'add_reinitiated_remark' => $reinitiated_remark), array('id' => $addrverid));

                $result_addrverres = $this->addressver_model->save_update_initiated_date_addrver(array('verfstatus' => 26, 'var_filter_status' => "WIP", 'var_report_status' => "WIP"), array('addrverid' => $addrverid));

                $error_msgs = array();
                $file_upload_path = SITE_BASE_PATH . ADDRESS . $clientid;
                if (!folder_exist($file_upload_path)) {
                    mkdir($file_upload_path, 0777);
                } else if (!is_writable($file_upload_path)) {
                    array_push($error_msgs, 'Problem while uploading');
                }

                $config_array = array('file_upload_path' => $file_upload_path, 'file_permission' => 'jpeg|jpg|png|pdf|docx|xls|xlsx', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 1000, 'file_id' => $addrverid, 'component_name' => 'addrver_id');

                if ($_FILES['attachment_reinitiated']['name'][0] != '') {
                    $config_array['files_count'] = count($_FILES['attachment_reinitiated']['name']);
                    $config_array['file_data'] = $_FILES['attachment_reinitiated'];
                    $config_array['type'] = 2;
                    $retunr_cd = $this->file_upload_multiple($config_array);
                    if (!empty($retunr_cd)) {
                        $this->common_model->common_insert_batch('addrver_files', $retunr_cd['success']);
                    }
                }

                $result_address_activity_data = $this->addressver_model->initiated_date_addrver_activity_data(array('candsid' => $this->input->post('candidates_info_id'), 'comp_table_id' => $addrverid, 'action' => "Re-Initiated", '  activity_status' => "Re-Initiated", 'remarks' => 'Client requested to re-verify the case [' . $reinitiated_remark . ']', 'created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id']));

                if ($result && $result_addrverres) {

                    auto_update_overall_status($this->input->post('candidates_info_id'));

                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'Record Successfully Inserted';
                }

            } catch (Exception $e) {
                log_message('error', 'Address::add_reinitiated_date');
                log_message('error', $e->getMessage());
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
            $data['insuff_details'] = $this->addressver_model->select_insuff_join(array('addrverid' => $emp_id));
        
            echo $this->load->view('admin/addrver_insuff_view', $data, true);
        } else {
            echo "<p>Something went wrong, please try again.</p>";
        }
    }

    public function address_result_list($emp_id = '')
    {
        if ($emp_id && $this->input->is_ajax_request()) {

            log_message('error', 'Address Result List View');
            try {

                $address_result = $this->addressver_model->select_result_log(array('addrverid' => $emp_id, 'activity_log_id !=' => null));

                $html_view = '<thead><tr><th>Created On</th><th>Created By</th><th>Action</th><th>Activit Mode</th><th>Attachment</th><th>Activity Type</th><th>Activity Status</th><th>View</th></tr></thead>';
                //$html_view = '';
                if (!empty($address_result[0]['id'])) {
                    $l = 1;
                    foreach ($address_result as $key => $value) {

                        $vendor_attachments = $this->addressver_model->select_file_vendor(array('view_vendor_master_log_file.id', 'view_vendor_master_log_file.file_name', 'view_vendor_master_log_file.real_filename', 'view_vendor_master_log_file.status'), array('view_vendor_master_log_file.component_tbl_id' => 1), $value['addrverid']);

                        $file = "&nbsp;&nbsp;&nbsp;&nbsp;";
                        if ($value['file_names']) {
                            $files = explode(',', $value['file_names']);

                            for ($i = 0; $i < count($files); $i++) {
                                $url = "'" . SITE_URL . ADDRESS . $value['clientid'] . '/';
                                $actual_file = $files[$i] . "'";
                                $myWin = "'" . "myWin" . "'";
                                $attribute = "'" . "height=250,width=480" . "'";

                                $file .= '<a href="javascript:;" onClick="myOpenWindow(' . $url . $actual_file . ',' . $myWin . ',' . $attribute . '); return false"><i class="fa fa-file-photo-o" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;';
                            }
                        }

                        if ($vendor_attachments) {

                            for ($j = 0; $j < count($vendor_attachments); $j++) {
                                $folder_name = "vendor_file";
                                $url = "'" . SITE_URL . ADDRESS . $folder_name . '/';
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

                            $html_view .= '<td><button data-id="showAddResultModel" data-url ="' . ADMIN_SITE_URL . 'address/address_result_list_idwise/' . $value['id'] . '/' . str_replace(" ", "", $value['activity_action']) . '" data-toggle="modal" data-target="#showAddResultModel1"  class="btn-info  showAddResultModel"> View </button></td>';
                        } else {
                            $html_view .= '<td></td>';
                        }

                        $html_view .= '</tr>';

                        $l++;
                    }
                } else {
                    $html_view .= "<tr><td colspan = '8'>No Record Found</td></tr>";

                }
                $json_array['status'] = SUCCESS_CODE;
                $json_array['message'] = $html_view;

            } catch (Exception $e) {
                log_message('error', 'Address::address_result_list');
                log_message('error', $e->getMessage());
            }

        } else {
            $json_array['status'] = SUCCESS_CODE;
            $json_array['message'] = "<tr><td>Something went wrong, please try again!<td></tr>";
        }
        echo_json($json_array);
    }

    public function address_result_list_idwise($where_id, $url)
    {
        $details = $this->addressver_model->select_result_log1(array('addrverres_result.id' => $where_id));

        if ($where_id && !empty($details)) {

            log_message('error', 'Address Result Open Idwise');
            try {

                $data['check_insuff_raise'] = '';
                $data['states'] = $this->get_states();
                $data['details'] = $details[0];

                $data['url'] = $url;

                $data['attachments'] = $this->addressver_model->select_file_address(array('id', 'file_name', 'status'), array('addrver_id' => $details[0]['addrverid'], 'type' => 1));

                $data['vendor_attachments'] = $this->addressver_model->select_file_vendor(array('view_vendor_master_log_file.id', 'view_vendor_master_log_file.file_name', 'view_vendor_master_log_file.real_filename', 'view_vendor_master_log_file.status'), array('view_vendor_master_log_file.component_tbl_id' => 1), $details[0]['addrverid']);

                echo $this->load->view('admin/address_add_result_model_view_log', $data, true);
            } catch (Exception $e) {
                log_message('error', 'Address::address_result_list_idwise');
                log_message('error', $e->getMessage());
            }
        } else {
            echo "<h4>Record Not Found</h4>";
        }

    }

    public function insuff_raised_data()
    {
        if ($this->input->is_ajax_request()) {
            log_message('error', 'Address Insuff Raised Data');
            try {
                $insuff_data = $this->input->post('insuff_data');

                $result = $this->addressver_model->select_insuff(array('id' => $insuff_data));
                if (!empty($result)) {
                    $result = $result[0];
                    $result['insuff_raised_date'] = convert_db_to_display_date($result['insuff_raised_date']);
                    $result['insuff_clear_date'] = convert_db_to_display_date($result['insuff_clear_date']);
                }
                echo_json($result);

            } catch (Exception $e) {
                log_message('error', 'Address::insuff_raised_data');
                log_message('error', $e->getMessage());
            }
        }
    }

    public function insuff_edit_clear_raised_data()
    {
        if ($this->input->is_ajax_request()) {
            log_message('error', 'Address Insuff Edit Clear Data');
            try {
                $insuff_data = $this->input->post('insuff_data');

                $result = $this->addressver_model->select_insuff(array('id' => $insuff_data));

                if (!empty($result)) {
                    $data['insuff_reason_list'] = $this->addressver_model->insuff_reason_list(false, array('component_id' => 1));
                    $data['insuff_details'] = $result[0];
                    echo $this->load->view('admin/insuff_edit_and_clear_view', $data, true);
                }

            } catch (Exception $e) {
                log_message('error', 'Address::insuff_edit_clear_raised_data');
                log_message('error', $e->getMessage());
            }
        }
    }

    public function insuff_clear()
    {
        $json_array = array();

        $frm_details = $this->input->post();

        if ($this->input->is_ajax_request() && $this->permission['access_address_list_insuff_clear'] == 1) {

            log_message('error', 'Address Insuff Clear');
            try {
                if (convert_display_to_db_date($frm_details['insuff_clear_date']) >= convert_display_to_db_date($frm_details['check_insuff_raise'])) {
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

                    $fields = array('insuff_clear_date' => convert_display_to_db_date($insuff_date), 'insuff_remarks' => $frm_details['insuff_remarks'], 'insuff_cleared_timestamp' => date(DB_DATE_FORMAT), 'status' => 2, 'insuff_cleared_by' => $this->user_info['id'], 'auto_stamp' => 2, 'hold_days' => $hold_days);

                    $result = $this->addressver_model->save_update_insuff($fields, array('id' => $frm_details['insuff_clear_id']));

                    $get_vendor_log_deatail = $this->addressver_model->check_vendor_status_insufficiency(array('view_vendor_master_log.component' => "addrver", 'view_vendor_master_log.component_tbl_id' => 1,
                        'view_vendor_master_log.final_status' => 'insufficiency', 'addrver.id' => $frm_details['clear_update_id']));

                    if (!empty($get_vendor_log_deatail)) {
                        $update_vendor_log_deatail = $this->addressver_model->reject_cost_vendor(array('final_status' => 'wip', 'vendor_remark' => '', 'modified_on' => date(DB_DATE_FORMAT)), array('view_vendor_master_log.id' => $get_vendor_log_deatail[0]['id']));
                    }

                    if ($result) {
                        $user_activity_data = $this->common_model->user_actity_data(array('component' => "Address", 'ref_no' => $frm_details['component_ref_no'], 'candidate_name' => $frm_details['CandidateName'], 'created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id'], 'activity_type' => 'Manual', 'action' => 'Insuff Cleared'));
                    }

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

            } catch (Exception $e) {
                log_message('error', 'Address::insuff_clear');
                log_message('error', $e->getMessage());
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
        if ($this->input->is_ajax_request() && $this->permission['access_address_list_insuff_delete'] == 1) {

            log_message('error', 'Address Insuff Delete');
            try {
                $fields = array('status' => 3, 'modified_on' => date(DB_DATE_FORMAT), 'modified_by' => $this->user_info['id']);

                $result = $this->addressver_model->save_update_insuff($fields, array('id' => $insuff_data));

                if ($result) {

                    auto_update_overall_status($this->input->post('candidates_info_id'));

                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'Deleted Successfully';
                } else {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Something went wrong,please try again';
                }

            } catch (Exception $e) {
                log_message('error', 'Address::insuff_delete');
                log_message('error', $e->getMessage());
            }
        } else {
            $json_array['message'] = "Don't have permission to access this function";

            $json_array['status'] = ERROR_CODE;
        }
        echo_json($json_array);
    }

    public function update_insuff_details()
    {
        if ($this->input->is_ajax_request()) {

            log_message('error', 'Address Update Insuff Details');
            try {
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
                    $clear_date = date(DATE_ONLY);
                    $fields['insuff_clear_date'] = convert_display_to_db_date($frm_details['insuff_clear_date']);
                }

                $fields['hold_days'] = getNetWorkDays($frm_details['txt_insuff_raise'], $clear_date);

                $result = $this->addressver_model->save_update_insuff($fields, array('id' => $frm_details['id']));

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

            } catch (Exception $e) {
                log_message('error', 'Address::update_insuff_details');
                log_message('error', $e->getMessage());
            }
        }
    }

    public function frm_update_address()
    {

        if ($this->permission['access_candidates_list_edit'] == true) {

            if ($this->input->is_ajax_request()) {
                $this->form_validation->set_rules('address_id', 'ID', 'required');

                // $this->form_validation->set_rules('has_case_id', 'Assigned To Executive', 'required');

                $this->form_validation->set_rules('clientid', 'Client', 'required');

                $this->form_validation->set_rules('add_com_ref', 'Address Component', 'required');

                $this->form_validation->set_rules('candsid', 'Candidate', 'required');

                $this->form_validation->set_rules('address', 'Address', 'required');

                $this->form_validation->set_rules('city', 'City', 'required');

                $this->form_validation->set_rules('pincode', 'PIN code', 'required');

                //  $this->form_validation->set_rules('stay_from', 'Stay From', 'required');

                //  $this->form_validation->set_rules('stay_to', 'Stay To', 'required');

                $this->form_validation->set_rules('state', 'State', 'required');

                if ($this->form_validation->run() == false) {

                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = validation_errors('', '');
                } else {

                    log_message('error', 'Address Update Details');
                    try {

                        $frm_details = $this->input->post();
                        $error_msgs = array();

                        $file_upload_path = SITE_BASE_PATH . ADDRESS . $frm_details['clientid'];
                        if (!folder_exist($file_upload_path)) {
                            mkdir($file_upload_path, 0777);
                        } else if (!is_writable($file_upload_path)) {
                            array_push($error_msgs, 'Problem while uploading');
                        }

                        $tat_day = $this->get_client_entity_package_tat_day(array('tbl_clients_id' => $frm_details['clientid'], 'entity' => $frm_details['entity_id'], 'package' => $frm_details['package_id']));

                        $get_holiday1 = $this->get_holiday();

                        $get_holiday = array_map('current', $get_holiday1);

                        $closed_date = getWorkingDays(convert_display_to_db_date($frm_details['iniated_date']), $get_holiday, $tat_day[0]['tat_addrver']);

                        $field_array = array('clientid' => $frm_details['clientid'],
                            'candsid' => $frm_details['candsid'],
                            'add_com_ref' => $frm_details['add_com_ref'],
                            'stay_from' => $frm_details['stay_from'],
                            'stay_to' => $frm_details['stay_to'],
                            'address_type' => $frm_details['address_type'],
                            'mod_of_veri' => $frm_details['mod_of_veri'],
                            'address' => $frm_details['address'],
                            'city' => $frm_details['city'],
                            'pincode' => $frm_details['pincode'],
                            'state' => $frm_details['state'],
                            'build_date' => $frm_details['build_date'],
                            'modified_on' => date(DB_DATE_FORMAT),
                            'modified_by' => $this->user_info['id'],
                            'has_case_id' => $frm_details['has_case_id'],
                            'has_assigned_on' => date(DB_DATE_FORMAT),
                            'is_bulk_uploaded' => 0,
                            'iniated_date' => convert_display_to_db_date($frm_details['iniated_date']),
                            "add_re_open_date" => '',
                            "due_date" => $closed_date,
                            "tat_status" => "IN TAT",
                        );

                        $result = $this->addressver_model->save(array_map('strtolower', $field_array), array('id' => $frm_details['address_id']));

                        $select_candidate_billed_date = $this->common_model->select_candidate_billed_date('candidates_info', true, array('build_date'), array('id' => $frm_details['candsid']));

                        $component_name = json_decode($select_candidate_billed_date['build_date'], true);

                        $result_candidate_billed = $this->common_model->update_candidate_billed_date(array('build_date' => $this->components_key_val(array('0' => $frm_details['build_date'], '1' => $component_name['courtver'], '2' => $component_name['globdbver'], '3' => $component_name['narcver'], '4' => $component_name['refver'], '5' => $component_name['empver'], '6' => $component_name['eduver'], '7' => $component_name['identity'], '8' => $component_name['cbrver'], '9' => $component_name['crimver']))), array('id' => $frm_details['candsid']));

                        $config_array = array('file_upload_path' => $file_upload_path, 'file_permission' => 'jpeg|jpg|png|pdf|docx|xls|xlsx', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 1000, 'file_id' => $frm_details['address_id'], 'component_name' => 'addrver_id');
                     
                        if (empty($error_msgs)) {
                            if ($_FILES['attchments']['name'][0] != '') {
                                $config_array['files_count'] = count($_FILES['attchments']['name']);
                                $config_array['file_data'] = $_FILES['attchments'];
                                $config_array['type'] = 0;
                                // $retunr_de= $this->file_upload_library->file_upload_multiple($config_array,true);

                                $retunr_de = $this->file_upload_multiple($config_array, true);
                                if (!empty($retunr_de)) {
                                    $this->common_model->common_insert_batch('addrver_files', $retunr_de['success']);
                                }
                            }

                            if ($_FILES['attchments_cs']['name'][0] != '') {
                                $config_array['files_count'] = count($_FILES['attchments_cs']['name']);
                                $config_array['file_data'] = $_FILES['attchments_cs'];
                                $config_array['type'] = 2;
                                $retunr_cd = $this->file_upload_multiple($config_array);
                                if (!empty($retunr_cd)) {
                                    $this->common_model->common_insert_batch('addrver_files', $retunr_cd['success']);
                                }
                            }
                         
                            if (isset($frm_details['upload_capture_image_address'])) {
                                
                                if ($frm_details['upload_capture_image_address']) {

                                    $upload_capture_image = explode("||", $frm_details['upload_capture_image_address']);
                                        
                                    foreach ($upload_capture_image as $key => $value) {
                                        $key = $key + 1;

                                        $file_name = $frm_details['add_com_ref'] . '-' .$key. '-' . date(UPLOAD_FILE_DATE_FORMAT) . '.png';
                                        $uploadpath = $file_upload_path . '/' . $file_name;
                                        $base64_to_jpeg = base64_to_jpeg($value, $uploadpath);
                                            if ($base64_to_jpeg) {
                                                log_message('error', 'Inside if condition success');
                                                $this->common_model->save('addrver_files', ['file_name' => $file_name, 'real_filename' => $file_name,'status' => 1,'type' => 0,'addrver_id' => $frm_details['address_id']]);
                                            }

                                    }
                                }
                            }
                        }

                        if ($result) {

                            $user_activity_data = $this->common_model->user_actity_data(array('component' => "Address",
                                'ref_no' => $frm_details['add_com_ref'], 'candidate_name' => $frm_details['CandidateName'], 'created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id'], 'activity_type' => 'Manual', 'action' => 'Edit'));

                            auto_update_tat_status($frm_details['candsid']);

                            $json_array['status'] = SUCCESS_CODE;

                            $json_array['message'] = 'Record Successfully Updated';

                            $json_array['redirect'] = ADMIN_SITE_URL . 'address';

                            //$json_array['active_tab'] = 'addrver';
                        } else {
                            $json_array['status'] = ERROR_CODE;

                            $json_array['message'] = 'Something went wrong,please try again';
                        }
                        echo_json($json_array);

                    } catch (Exception $e) {
                        log_message('error', 'Address::frm_update_address');
                        log_message('error', $e->getMessage());
                    }
                }

            }
        } else {
            permission_denied();
        }
    }

    public function assign_to_executive()
    {
        $json_array = array();
        if ($this->input->is_ajax_request() && ($this->permission['access_address_list_re_assign_executive'] == '1')) {

            log_message('error', 'Address Assign Executive');
            try {
                $frm_details = $this->input->post();

                if ($frm_details['users_list'] != 0 && $frm_details['cases_id'] != "") {
                    $return = $this->common_model->update_in('addrver', array('has_case_id' => $frm_details['users_list'], 'has_assigned_on' => date(DB_DATE_FORMAT)), array('where_id' => $frm_details['cases_id']));
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
            } catch (Exception $e) {
                log_message('error', 'Address::assign_to_executive');
                log_message('error', $e->getMessage());
            }

        } else {

            $json_array['status'] = ERROR_CODE;
            $json_array['message'] = "Access Denied, You don’t have permission to access this page";
        }
        echo_json($json_array);
    }

    public function add_result_model($where_id, $url)
    {
        $details = $this->addressver_model->get_address_details(array('addrver.id' => $where_id));

        if ($where_id && !empty($details)) {

            log_message('error', 'Address Add Reult Model');
            try {
                $data['check_insuff_raise'] = '';
                $data['states'] = $this->get_states();
                $data['details'] = $details[0];
                $data['attachments'] = $this->addressver_model->select_file_address(array('id', 'file_name', 'status'), array('addrver_id' => $where_id, 'type' => 1));

                //  $data['vendor_attachments'] = $this->addressver_model->select_file_address(array('id','file_name','status'),array('addrver_id' => $where_id,'type' => 1,'status' => 1));

                $data['vendor_attachments'] = $this->addressver_model->select_file_vendor(array('view_vendor_master_log_file.id', 'view_vendor_master_log_file.file_name', 'view_vendor_master_log_file.real_filename', 'view_vendor_master_log_file.status'), array('view_vendor_master_log_file.component_tbl_id' => 1), $where_id);

                $data['url'] = $url;

                echo $this->load->view('admin/address_add_result_model_view', $data, true);
            } catch (Exception $e) {
                log_message('error', 'Address::add_result_model');
                log_message('error', $e->getMessage());
            }

        } else {
            echo "<h4>Record Not Found</h4>";
        }
    }

    public function add_result_model_task($where_id, $client_new_cases_id)
    {
        $details = $this->addressver_model->get_address_details(array('addrver.id' => $where_id));

        $data['attachments'] = $this->addressver_model->select_file(array('id', 'file_name', 'real_filename'), array('client_new_case_id' => $client_new_cases_id, 'status' => 1));

        $data['attachments1'] = $this->addressver_model->select_file1(array('view_vendor_master_log_file.status' => 1, 'client_new_cases.id' => $client_new_cases_id));

        $data['attachments2'] = $this->addressver_model->select_file2(array('id', 'file_name', 'real_filename'), array('addrver_id' => $where_id, 'status' => 1));

        if ($where_id && !empty($details)) {
            $data['check_insuff_raise'] = '';
            $data['states'] = $this->get_states();
            $data['details'] = $details[0];
            echo $this->load->view('admin/address_add_result_model_view_task', $data, true);
        } else {
            echo "<h4>Record Not Found</h4>";
        }
    }

    public function add_verificarion_result()
    {
        $this->load->library('email');

        if ($this->input->is_ajax_request()) {

            $this->form_validation->set_rules('tbl_addrver', 'Id', 'required');

            $this->form_validation->set_rules('addrverres_id', 'Id', 'required');

            $this->form_validation->set_rules('closuredate', 'Mode Of Verification', 'required');

            if ($this->form_validation->run() == false) {
                $json_array['status'] = ERROR_CODE;
                $json_array['message'] = validation_errors('', '');
            } else {

                log_message('error', 'Add verification result');
                try {

                    $frm_details = $this->input->post();

                    if (($frm_details['action_val'] != "Select") || ($frm_details['activity_last_id'] != "")) {
                       
                        $verfstatus = status_id_frm_db(array('status_value' => $frm_details['action_val'], 'components_id' => 6));

                        $addrverres_id = $frm_details['addrverres_id'];

                        $field_array = array(
                            'verfstatus' => $verfstatus['id'],
                            'var_filter_status' => $verfstatus['filter_status'],
                            'var_report_status' => $verfstatus['report_status'],
                            'closuredate' => convert_display_to_db_date($frm_details['closuredate']),
                            'res_address' => $frm_details['res_address'],
                            'clientid' => $frm_details['clientid'],
                            'candsid' => $frm_details['candidates_info_id'],
                            'addrverid' => $frm_details['tbl_addrver'],
                            'res_city' => $frm_details['res_city'],
                            'res_pincode' => $frm_details['res_pincode'],
                            'res_state' => $frm_details['res_state'],
                            'res_stay_from' => $frm_details['res_stay_from'],
                            'res_stay_to' => $frm_details['res_stay_to'],
                            'address_action' => $frm_details['address_action'],
                            'city_action' => $frm_details['city_action'],
                            'pincode_action' => $frm_details['pincode_action'],
                            'state_action' => $frm_details['state_action'],
                            'stay_from_action' => $frm_details['stay_from_action'],
                            'stay_to_action' => $frm_details['stay_to_action'],
                            'mode_of_verification' => $frm_details['mode_of_verification'],
                            'resident_status' => $frm_details['resident_status'],
                            'landmark' => $frm_details['landmark'],
                            'neighbour_1' => $frm_details['neighbour_1'],
                            'neighbour_details_1' => $frm_details['neighbour_details_1'],
                            'neighbour_2' => $frm_details['neighbour_2'],
                            'neighbour_details_2' => $frm_details['neighbour_details_2'],
                            'verified_by' => $frm_details['verified_by'],
                            'addr_proof_collected' => $frm_details['addr_proof_collected'],
                            'remarks' => $frm_details['remarks'],
                            'modified_on' => date(DB_DATE_FORMAT),
                            'modified_by' => $this->user_info['id'],
                            'is_bulk_uploaded' => 0,
                            'activity_log_id' => $frm_details['activity_last_id'],
                        );

                        $field_array = array_map('strtolower', $field_array);

                        if (isset($frm_details['remove_file'])) // delete uploaded file
                        {
                            $this->addressver_model->delete_uploaded_file($frm_details['remove_file']);
                        }
                        if (isset($frm_details['add_file'])) // delete uploaded file
                        {
                            $this->addressver_model->add_uploaded_file($frm_details['add_file']);
                        }

                        if (isset($frm_details['vendor_remove_file'])) // delete uploaded file
                        {
                            $this->addressver_model->vendor_delete_uploaded_file($frm_details['vendor_remove_file']);
                        }
                        if (isset($frm_details['vendor_add_file'])) // delete uploaded file
                        {
                            $this->addressver_model->vendor_add_uploaded_file($frm_details['vendor_add_file']);
                        }

                        $result = $this->addressver_model->save_update_result(array_map('strtolower', $field_array), array('id' => $addrverres_id));

                        $result_addrverres_result = $this->addressver_model->save_update_result_addrverres(array_map('strtolower', $field_array));

                        if ($verfstatus['id'] == 9 || $verfstatus['id'] == 27 || $verfstatus['id'] == 28) {

                            $get_vendor_log_deatail = $this->addressver_model->check_vendor_status_closed_or_not(array('view_vendor_master_log.component' => "addrver", 'view_vendor_master_log.component_tbl_id' => 1, 'addrver.id' => $frm_details['tbl_addrver']));


                            $address_details_vendor = $this->addressver_model->get_address_details_for_approval($get_vendor_log_deatail[0]['address_vendor_log_id']);

                            if (!empty($get_vendor_log_deatail)) {

                                $update_vendor_log_deatail = $this->addressver_model->reject_cost_vendor(array('final_status' => 'cancelled', 'status' => 5, 'remarks' => 'Stop Check', 'rejected_by' => $this->user_info['id'], 'rejected_on' => date(DB_DATE_FORMAT), 'vendor_remark' => 'Stop Check', 'modified_on' => date(DB_DATE_FORMAT)), array('view_vendor_master_log.id' => $get_vendor_log_deatail[0]['id']));

                                $field_array_address = array(
                                    'remarks' => 'Stop Check',
                                    'modified_by' => $this->user_info['id'],
                                    'modified_on' => date(DB_DATE_FORMAT),
                                    'status' => 2,
                                );
                                  
                                $address_vendor_result = $this->addressver_model->update_address_vendor_log('address_vendor_log', $field_array_address, array('id' => $get_vendor_log_deatail[0]['address_vendor_log_id']));
                       
                          
                                $email_tmpl_data['subject'] = 'Address - Stop check cases_'.date("d-M-Y H:i");
                                $message = "<p>Team,</p><p> The below case/s have been cancelled by the client.</p>";
                                        
                                $message .= "<table border = '2'  style='border-spacing: 10; border-collapse: collapse;'>
                                    <tr>
                                    <th style='background-color: #EDEDED;text-align:center'>Sr No</th>
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
                                $i = 1;
                                foreach ($address_details_vendor as $address_key => $address_value) {
                                    
                                    $message .= '<tr>
                                        <td style="text-align:center">'.$i. '</td>
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

                                    $i++;
                                           
                                    } 
                                
                                $message .= "</table>";

                                $message .= "<br><br><p><b>Note :</b> <I>This is an auto generated email. Request you to write back within 24 hrs with any discrepancy.</I>";
                      
                                $to_emails = $this->addressver_model->vendor_email_id(array('vendors.id' => $address_details_vendor[0]['vendor_id']));
                             
                                $email_tmpl_data['to_emails'] = $to_emails[0]['email_id'];
                               
                                $email_tmpl_data['message'] = $message;
                             
                                $results = $this->email->address_approve_vendor_cases_mail_send($email_tmpl_data);

                                $this->email->clear(true);
                            }

                        }
                        
                        $file_upload_path = SITE_BASE_PATH . ADDRESS . $frm_details['clientid'];

                        if(!empty($frm_details['attchments_ver']))
                        {
                            $attchments_ver = explode(',',$frm_details['attchments_ver']);

                            foreach ($attchments_ver as $key => $value) {
                                   
                                $this->compress_image(SITE_BASE_PATH . ADDRESS . $frm_details['clientid'].'/'.$value, SITE_BASE_PATH . ADDRESS . $frm_details['clientid'].'/'.$value, 80);

                            }
                        }
                        
                        if (isset($frm_details['upload_capture_image_address_result'])) {

                            if ($frm_details['upload_capture_image_address_result']) {

                               $upload_capture_image = explode("||", $frm_details['upload_capture_image_address_result']);
                                    
                                foreach ($upload_capture_image as $key => $value) {
                                    $key = $key + 1;

                                    $file_name = $frm_details['add_com_ref'] . '-' .$key. '-' . date(UPLOAD_FILE_DATE_FORMAT) . '.png';
                                    $uploadpath = $file_upload_path . '/' . $file_name;
                                    $base64_to_jpeg = base64_to_jpeg($value, $uploadpath);
                                        if ($base64_to_jpeg) {
                                            log_message('error', 'Inside if condition success');
                                            $this->common_model->save('addrver_files', ['file_name' => $file_name, 'real_filename' => $file_name,'status' => 1,'type' => 1,'addrver_id' => $frm_details['tbl_addrver']]);
                                        }

                                }
                            }
                        }


                        if (isset($frm_details['sortable_data'])) {
                            $order = (explode('&', $frm_details['sortable_data']));
                            if (is_array($order) && !empty($order[0])) {
                                foreach ($order as $key => $value) {
                                    preg_match('/=([a-z0-9]*)$/', $value, $treffer);
                                    $update[] = array('serialno' => $key + 1, 'id' => $treffer[1]);
                                }
                                $this->addressver_model->upload_file_update($update);
                            }
                        }

                        if (isset($frm_details['sortable_data_vendor'])) {
                            $order = (explode('&', $frm_details['sortable_data_vendor']));
                            if (is_array($order) && !empty($order[0])) {
                                foreach ($order as $key => $value) {
                                    preg_match('/=([a-z0-9]*)$/', $value, $treffer);
                                    $update[] = array('serialno' => $key + 1, 'id' => $treffer[1]);
                                }
                                $this->addressver_model->upload_file_update_vendor($update);
                            }
                        }

                        if ($result) {
                            auto_update_overall_status($frm_details['candidates_info_id']);
                    //     all_component_closed_qc_status($frm_details['candidates_info_id']);

                            $json_array['status'] = SUCCESS_CODE;
                            $json_array['message'] = 'Updated Successfully';
                            $json_array['redirect'] = ADMIN_SITE_URL . 'address';
                        } else {
                            $json_array['status'] = ERROR_CODE;
                            $json_array['message'] = 'Something went wrong,please try again';
                        }
                    } else {
                        $json_array['status'] = ERROR_CODE;

                        $json_array['message'] = 'Something went wrong,please try again';
                    }

                } catch (Exception $e) {
                    log_message('error', 'Address::add_verificarion_result');
                    log_message('error', $e->getMessage());
                }
            }
            echo_json($json_array);
        }
    }

    public function add_verificarion_ver_result()
    {
        if ($this->input->is_ajax_request()) {

            $this->form_validation->set_rules('tbl_addrver', 'Id', 'required');

            $this->form_validation->set_rules('addrverres_id', 'Id', 'required');

            $this->form_validation->set_rules('closuredate', 'Mode Of Verification', 'required');

            if ($this->form_validation->run() == false) {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');
            } else {

                log_message('error', 'Add verification reverification result');
                try {

                    $frm_details = $this->input->post();

                    $addrverres_id = $frm_details['addrverres_id'];

                    $field_array = array(
                        'closuredate' => convert_display_to_db_date($frm_details['closuredate']),
                        'res_address' => $frm_details['res_address'],
                        'clientid' => $frm_details['clientid'],
                        'candsid' => $frm_details['candidates_info_id'],
                        'addrverid' => $frm_details['tbl_addrver'],
                        'res_city' => $frm_details['res_city'],
                        'res_pincode' => $frm_details['res_pincode'],
                        'res_state' => $frm_details['res_state'],
                        'res_stay_from' => $frm_details['res_stay_from'],
                        'res_stay_to' => $frm_details['res_stay_to'],
                        'address_action' => $frm_details['address_action'],
                        'city_action' => $frm_details['city_action'],
                        'pincode_action' => $frm_details['pincode_action'],
                        'state_action' => $frm_details['state_action'],
                        'stay_from_action' => $frm_details['stay_from_action'],
                        'stay_to_action' => $frm_details['stay_to_action'],
                        'mode_of_verification' => $frm_details['mode_of_verification'],
                        'resident_status' => $frm_details['resident_status'],
                        'landmark' => $frm_details['landmark'],
                        'neighbour_1' => $frm_details['neighbour_1'],
                        'neighbour_details_1' => $frm_details['neighbour_details_1'],
                        'neighbour_2' => $frm_details['neighbour_2'],
                        'neighbour_details_2' => $frm_details['neighbour_details_2'],
                        'verified_by' => $frm_details['verified_by'],
                        'addr_proof_collected' => $frm_details['addr_proof_collected'],
                        'remarks' => $frm_details['remarks'],
                        'modified_on' => date(DB_DATE_FORMAT),
                        'modified_by' => $this->user_info['id'],
                    );

                    $field_array = array_map('strtolower', $field_array);

                    if (isset($frm_details['remove_file'])) // delete uploaded file
                    {
                        $this->addressver_model->delete_uploaded_file($frm_details['remove_file']);
                    }
                    if (isset($frm_details['add_file'])) // delete uploaded file
                    {
                        $this->addressver_model->add_uploaded_file($frm_details['add_file']);
                    }

                    if (isset($frm_details['vendor_remove_file'])) // delete uploaded file
                    {
                        $this->addressver_model->vendor_delete_uploaded_file($frm_details['vendor_remove_file']);
                    }
                    if (isset($frm_details['vendor_add_file'])) // delete uploaded file
                    {
                        $this->addressver_model->vendor_add_uploaded_file($frm_details['vendor_add_file']);
                    }

                    $result = $this->addressver_model->save_update_result(array_map('strtolower', $field_array), array('id' => $addrverres_id));

                    $result_addrverres_result = $this->addressver_model->save_update_result_addrverres(array_map('strtolower', $field_array), array('id' => $frm_details['result_update_id']));

                    $file_upload_path = SITE_BASE_PATH . ADDRESS . $frm_details['clientid'];
                    
                    if(!empty($frm_details['attchments_ver']))
                    {
                        $attchments_ver = explode(',',$frm_details['attchments_ver']);

                        foreach ($attchments_ver as $key => $value) {
                               
                            $this->compress_image(SITE_BASE_PATH . ADDRESS . $frm_details['clientid'].'/'.$value, SITE_BASE_PATH . ADDRESS . $frm_details['clientid'].'/'.$value, 80);

                        }
                    }

                    if (isset($frm_details['upload_capture_image_address_ver_result'])) {

                        if ($frm_details['upload_capture_image_address_ver_result']) {

                           $upload_capture_image = explode("||", $frm_details['upload_capture_image_address_ver_result']);
                                    
                            foreach ($upload_capture_image as $key => $value) {
                                $key = $key + 1;

                                $file_name = $frm_details['add_com_ref'] . '-' .$key. '-' . date(UPLOAD_FILE_DATE_FORMAT) . '.png';
                                $uploadpath = $file_upload_path . '/' . $file_name;
                                $base64_to_jpeg = base64_to_jpeg($value, $uploadpath);
                                    if ($base64_to_jpeg) {
                                        log_message('error', 'Inside if condition success');
                                        $this->common_model->save('addrver_files', ['file_name' => $file_name, 'real_filename' => $file_name,'status' => 1,'type' => 1,'addrver_id' => $frm_details['tbl_addrver']]);
                                    }

                            }
                        }
                    }
                  
                    if (isset($frm_details['sortable_data'])) {
                        $order = (explode('&', $frm_details['sortable_data']));
                        if (is_array($order) && !empty($order[0])) {
                            foreach ($order as $key => $value) {
                                preg_match('/=([a-z0-9]*)$/', $value, $treffer);
                                $update[] = array('serialno' => $key + 1, 'id' => $treffer[1]);
                            }
                            $this->addressver_model->upload_file_update($update);
                        }
                    }

                    if (isset($frm_details['sortable_data_vendor'])) {
                        $order = (explode('&', $frm_details['sortable_data_vendor']));
                        if (is_array($order) && !empty($order[0])) {
                            foreach ($order as $key => $value) {
                                preg_match('/=([a-z0-9]*)$/', $value, $treffer);
                                $update[] = array('serialno' => $key + 1, 'id' => $treffer[1]);
                            }
                            $this->addressver_model->upload_file_update_vendor($update);
                        }
                    }


                    if ($result) {
                        auto_update_overall_status($frm_details['candidates_info_id']);

                   //     all_component_closed_qc_status($frm_details['candidates_info_id']);

                        $json_array['status'] = SUCCESS_CODE;

                        $json_array['message'] = 'Updated Successfully';

                        $json_array['redirect'] = ADMIN_SITE_URL . 'address';
                    } else {
                        $json_array['status'] = ERROR_CODE;

                        $json_array['message'] = 'Something went wrong,please try again';
                    }

                } catch (Exception $e) {
                    log_message('error', 'Address::add_verificarion_result');
                    log_message('error', $e->getMessage());
                }
            }
            echo_json($json_array);
        }
    }

    public function assign_to_vendor()
    {
        $json_array = array();

        if ($this->input->is_ajax_request() && $this->permission['access_address_assign_add_assign'] == true) {

            log_message('error', 'Address assign Vendor');
            try {
                $frm_details = $this->input->post();

                $list = explode(',', $frm_details['cases_id']);

                if ($frm_details['vendor_list'] != 0 && $frm_details['cases_id'] != "" && !empty($list)) {

                    $files = $update = array();
                    $insert_counter = 0;
                    foreach ($list as $key => $value) {

                        $update = $this->addressver_model->upload_vendor_assign('addrver', array('vendor_id' => $frm_details['vendor_list'], 'vendor_list_mode' => $frm_details['vendor_list_mode'], 'vendor_assgined_on' => date(DB_DATE_FORMAT)), array('id' => $value, 'vendor_id =' => 0));

                        $update1 = $this->addressver_model->update_status('addrverres', array('verfstatus' => 1), array('addrverid' => $value));

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
                        }
                    }

                    if (!empty($files)) {
                        $inserted = $this->common_model->common_insert_batch('address_vendor_log', $files);
                    }

                    

                    $json_array['status'] = SUCCESS_CODE;
                    $json_array['message'] = $insert_counter . " of " . count($list) . " Assigned Successfully";

                } else {
                    $json_array['status'] = ERROR_CODE;
                    $json_array['message'] = "Select atleast one case";
                }

            } catch (Exception $e) {
                log_message('error', 'Address::assign_to_vendor');
                log_message('error', $e->getMessage());
            }

        } else {

            $json_array['status'] = ERROR_CODE;
            $json_array['message'] = "Access Denied, You don’t have permission to access this page";
        }
        echo_json($json_array);
    }

    public function approval_queue()
    {
        $this->load->model(array('address_vendor_log_model'));

        $data['header_title'] = "Vendor Approve List";

        $assigned_option = array(0 => 'select');

        ($this->permission['access_address_aq_allow'] == 1) ? $assigned_option[1] = 'Assign' : '';
        ($this->permission['access_address_aq_allow'] == 1) ? $assigned_option[2] = 'Reject' : '';
        $data['assigned_option'] = $assigned_option;

        $data['user_list_name'] = $this->users_list_filter();

        $this->load->view('admin/header', $data);

        $this->load->view('admin/address_vendor_aq');

        $this->load->view('admin/footer');

    }

    public function view_approval_queue()
    {

        $this->load->model(array('address_vendor_log_model'));

        $params = $add_candidates = $data_arry = $columns = array();

        $params = $_REQUEST;

        $lists = $this->address_vendor_log_model->get_new_list(array('address_vendor_log.status' => 0), $params);

        $totalRecords = count($this->address_vendor_log_model->get_new_list(array('address_vendor_log.status' => 0), $params));

        if ($this->permission['access_address_aq_view'] == 1) {
            $x = 0;

            foreach ($lists as $list) {
                $mode_of_verification_value =  json_decode($list['mode_of_verification']);
                   
                $data_arry[$x]['checkbox'] = $list['id'];
                $data_arry[$x]['id'] = $x + 1;
                $data_arry[$x]['mode_of_verification'] = $mode_of_verification_value->addrver;

                $data_arry[$x]['add_com_ref'] = $list['add_com_ref'];
                $data_arry[$x]['vendor_name'] = $list['vendor_name'];
                $data_arry[$x]['clientname'] = $list['clientname'];
                $data_arry[$x]['entity'] = $list['entity_name'];
                $data_arry[$x]['package'] = $list['package_name'];
                $data_arry[$x]['city'] = $list['city'];
                $data_arry[$x]['encry_id'] = ADMIN_SITE_URL . "address/view_details/" . encrypt($list['case_id']);
                $data_arry[$x]['pincode'] = $list['pincode'];
                $data_arry[$x]['state'] = $list['state'];
                $data_arry[$x]['allocated_by'] = $list['allocated_by'];
                $data_arry[$x]['allocated_on'] = convert_db_to_display_date($list['allocated_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);

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

    public function address_final_assigning()
    {

       
        if ($this->input->is_ajax_request()) {

            log_message('error', 'Address Final Assigning');
            try {
                if ($this->permission['access_address_aq_allow'] == 1) {
                    $frm_details = $this->input->post();
                    $action = $frm_details['action'];

                    if ($frm_details['action'] == 2 && $frm_details['cases_id'] != "") {
                        $list = explode(',', $frm_details['cases_id']);

                        $update_counter = 0;
                        foreach ($list as $key => $value) {

                            $update = $this->addressver_model->upload_vendor_assign('address_vendor_log', array('status' => 2, 'remarks' => $frm_details['reject_reason'], 'modified_by' => $this->user_info['id'], 'modified_on' => date(DB_DATE_FORMAT)), array('id' => $value));

                            if ($update) {
                                $update_counter++;
                                //$return =  $this->addressver_model->save(array('vendor_id' => 0,'vendor_assgined_on' => NULL),array('id' => $value));
                            }
                        }

                        if ($update) {

                            $json_array['status'] = SUCCESS_CODE;
                            $json_array['message'] = $update_counter . " of " . count($list) . " Rejected Successfully";

                        } else {
                            $json_array['status'] = ERROR_CODE;
                            $json_array['message'] = "Something went wrong,please try again";
                        }

                    } else if ($frm_details['action'] == 1 && $frm_details['cases_id'] != "") {
                      
                        $this->load->library('email');
                        $list = explode(',', $frm_details['cases_id']);
                       
                        $update_counter = 0;

                        foreach ($list as $key => $value) {

                            $select_address_id = $this->addressver_model->select_address_dt('address_vendor_log',array('case_id') ,array('id' => $value));

                            $cands_id = $this->addressver_model->select_address_dt('addrver',array('candsid'),array('id' => $select_address_id[0]['case_id']));
                           
                            $cands_details = $this->addressver_model->select_address_dt('candidates_info',array('clientid','entity','package'),array('id' => $cands_id[0]['candsid']));

                            //$mode_of_verification = $this->addressver_model->get_mode_of_verification(array('tbl_clients_id' =>  $cands_details[0]['clientid'],'entity' =>$cands_details[0]['entity'],'package' =>$cands_details[0]['package'])); 

                          //  if(!empty($mode_of_verification))
                           // {
                           //    $mode_of_verification_value = json_decode($mode_of_verification[0]['mode_of_verification']);
                          //  }

                         //   if(isset($mode_of_verification_value->addrver) && ($mode_of_verification_value->addrver  != "digital"))
                          //  {
                             /*   $update = $this->addressver_model->upload_vendor_assign('address_vendor_log', array('status' => 1, 'approval_by' => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT)), array('id' => $value));

                                if ($update) {
                                    $update_counter++;
                                    $field_array = array('component' => 'addrver',
                                        'component_tbl_id' => '1',
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
                                }*/
                                   
                               $address_details[] = $this->addressver_model->get_address_details_for_approval($value);
 
                       //     }

                       //     if(isset($mode_of_verification_value->addrver) && ($mode_of_verification_value->addrver  == "digital"))
                        //    { 
                                
                                //$this->cli_address_invite_mail($select_address_id[0]['case_id']);

                               // $update = $this->addressver_model->upload_vendor_assign('address_vendor_log', array('status' => 1, 'approval_by' => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT)), array('id' => $value));
                              //  $update_counter++;
                        //    } 

                        }

                        if(isset($address_details) && !empty($address_details))
                        {
                            

                            $address_detail =  (array_map('current', $address_details));
                          
                                       
                            foreach( $address_detail  as $k => $v) {
                                $new_arr[$v['vendor_id']][]=$v;
                            }
                           
                            foreach ($new_arr as $key => $value) {

                                $vendor_name = $this->addressver_model->vendor_email_id(array('vendors.id' => $key));

                                $email_tmpl_data['subject'] = 'Address - '.ucwords($vendor_name[0]['vendor_name']).' - New case/s initiated by '.CRMNAME;
                                $message = "<p>Team,</p><p> The below case/s have been initiated to you. Kindly have them closed within TAT.</p>";
                                        
                                $message .= "<table border = '2'  style='border-spacing: 10; border-collapse: collapse;'>
                                    <tr>
                                    <th style='background-color: #EDEDED;text-align:center'>Sr No</th>
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

                                $address_vendor_log_id = array();
                                $m = 1;
                                foreach ($value as $address_key => $address_value) {
                                   
                                    $message .= '<tr>
                                        <td style="text-align:center">'.$m.'</td>
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

                                        $address_vendor_log_id[] = $address_value['address_vendor_log_id'];
                                        
                                    $m++; 
                                           
                                } 

                             
                                    $message .= "</table>";

                                    $message .= "<br><br><p><b>Note :</b> <I>This is an auto generated email. Request you to write back within 24 hrs with any discrepancy.</I>";
                                        
                                    $to_emails = $this->addressver_model->vendor_email_id(array('vendors.id' => $key));
             
                                    $email_tmpl_data['to_emails'] = $to_emails[0]['email_id'];

                                    $email_tmpl_data['message'] = $message;
                  
                                    $result = $this->email->address_approve_vendor_cases_mail_send($email_tmpl_data);
              
                                    if(!empty($result) && $result == "Success")
                                    {
                                        if(!empty($address_vendor_log_id))
                                        {
                                            foreach($address_vendor_log_id as $address_vendor_log_key => $address_vendor_log_value)
                                            {

                                              
                                                $select_address_id = $this->addressver_model->select_address_dt('address_vendor_log',array('case_id') ,array('id' => $address_vendor_log_value));

                                                $cands_id = $this->addressver_model->select_address_dt('addrver',array('candsid'),array('id' => $select_address_id[0]['case_id']));
                                            
                                                $cands_details = $this->addressver_model->select_address_dt('candidates_info',array('clientid','entity','package'),array('id' => $cands_id[0]['candsid']));
        
                                                $mode_of_verification = $this->addressver_model->get_mode_of_verification(array('tbl_clients_id' =>  $cands_details[0]['clientid'],'entity' =>$cands_details[0]['entity'],'package' =>$cands_details[0]['package'])); 
        
                                                if(!empty($mode_of_verification))
                                                {
                                                   $mode_of_verification_value = json_decode($mode_of_verification[0]['mode_of_verification']);
                                                }
        
                                                $update = $this->addressver_model->upload_vendor_assign('address_vendor_log', array('status' => 1, 'approval_by' => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT)), array('id' => $address_vendor_log_value));
        
                                                if ($update) {
                                                    $update_counter++;
                                                    $field_array = array('component' => 'addrver',
                                                        'component_tbl_id' => '1',
                                                        'case_id' => $address_vendor_log_value,
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

                                        }
                                    }

                                
                                    $this->email->clear(true);
                                                                  
                            }
                        }
                              
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
            } catch (Exception $e) {
                log_message('error', 'Address::address_final_assigning');
                log_message('error', $e->getMessage());
            }
        } else {

            $json_array['status'] = ERROR_CODE;
            $json_array['message'] = "Access Denied, You don’t have permission to access this page";
        }

        echo_json($json_array);
    }

    public function bulk_upload_address()
    {

        if ($this->input->is_ajax_request()) {
            $file_upload_path = SITE_BASE_PATH . ADDRESS;

            if (!folder_exist($file_upload_path)) {
                mkdir($file_upload_path, 0777,true);
            } else if (!is_writable($file_upload_path)) {
                $message = 'Problem while uploading, folder permission';
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

                        if (count($value) < 9) {
                            continue;
                        }

                        $check_record_exits = $this->candidates_model->select(true, array('id', 'clientid', 'entity', 'package'), array('cmp_ref_no' => strtolower($value[0])));

                        if (!empty($check_record_exits) && $value[0] != "") {
                              
                            $users_id  = $this->get_reporting_manager_for_executive($check_record_exits['clientid']); 

                            $user_id =  $users_id[0]['id'];
                           

                            $tat_day = $this->get_client_entity_package_tat_day(array('tbl_clients_id' => $check_record_exits['clientid'], 'entity' => $check_record_exits['entity'], 'package' => $check_record_exits['package']));

                            $get_holiday1 = $this->get_holiday();

                            $get_holiday = array_map('current', $get_holiday1);
                            $closed_date = getWorkingDays(get_date_from_timestamp($value[1]), $get_holiday, $tat_day[0]['tat_addrver']);

                            $mode_of_verification = $this->addressver_model->get_mode_of_verification(array('entity' => $check_record_exits['entity'], 'package' => $check_record_exits['package'], 'tbl_clients_id' => $check_record_exits['clientid']));

                            if (!empty($mode_of_verification)) {
                                $mode_of_verification_value = json_decode($mode_of_verification[0]['mode_of_verification']);

                                $mode_of_veri = $mode_of_verification_value->addrver;
                            } else {

                                $mode_of_veri = "";
                            }

                            $field_array = array('clientid' => $check_record_exits['clientid'],
                                'candsid' => $check_record_exits['id'],
                                'add_com_ref' => '',
                                'stay_from' => $value[2],
                                'stay_to' => $value[3],
                                'address_type' => $value[4],
                                'address' => $value[5],
                                'city' => $value[6],
                                'pincode' => $value[7],
                                'state' => $value[8],
                                'created_by' => $this->user_info['id'],
                                'created_on' => date(DB_DATE_FORMAT),
                                'modified_on' => date(DB_DATE_FORMAT),
                                'modified_by' => $this->user_info['id'],
                                'has_case_id' => $user_id,
                                'has_assigned_on' => date(DB_DATE_FORMAT),
                                'is_bulk_uploaded' => 1,
                                'iniated_date' => get_date_from_timestamp($value[1]),
                                "add_re_open_date" => '',
                                "mod_of_veri" => $mode_of_veri,
                                "due_date" => $closed_date,
                                "tat_status" => "IN TAT",
                            );

                            $record = array_map('strtolower', array_map('trim', $field_array));

                            $insert_id = $this->addressver_model->save($record);

                            $add_com_ref = $this->add_com_ref($insert_id);

                            $field = array('candsid' => $check_record_exits['id'],
                                'ClientRefNumber' => '',
                                'comp_table_id' => $insert_id,
                                'activity_mode' => '',
                                'activity_status' => 'New check',
                                'activity_type' => 'New check',
                                'action' => 'New check',
                                'next_follow_up_date' => null,
                                'remarks' => 'New Check Added',
                                'created_on' => date(DB_DATE_FORMAT),
                                'created_by' => $this->user_info['id'],
                                'is_auto_filled' => 0,

                            );

                            $result = $this->addressver_model->save_trigger($field);

                          
                            $mode_of_verification = $this->addressver_model->get_mode_of_verification(array('entity' => $check_record_exits['entity'], 'package' => $check_record_exits['package'], 'tbl_clients_id' => $check_record_exits['clientid']));

                            if(!empty($mode_of_verification))
                            {
                               $mode_of_verification_value = json_decode($mode_of_verification[0]['mode_of_verification']);
                            }

                            if(isset($mode_of_verification_value->addrver) && ($mode_of_verification_value->addrver  != "digital"))
                            {

                                $vendor_id_state = $this->addressver_model->get_vendor_id_find_state($value[8]); 
                              
                                if(!empty($vendor_id_state))
                                {
                                    if($value[8] == "maharashtra" || $value[8] == "Maharashtra")
                                    {
                                        $vendor_id_city = $this->addressver_model->get_vendor_id_find_city($value[6]); 
                                         
                                        if(!empty($vendor_id_city))
                                        {
                                            $vendor_id = $vendor_id_city[0]['id'];
                                        }
                                        else{
                                        
                                            $vendor_id = array(); 

                                        }
                                    }
                                    else{

                                        $vendor_id = $vendor_id_state[0]['id'];
                                    }

                                }
                                else{
                                    $vendor_id = array();
                                }
                           
                                if(!empty($vendor_id))
                                { 
                                
                                    $update = $this->addressver_model->upload_vendor_assign('addrver', array('vendor_id' => $vendor_id, 'vendor_list_mode' => 'physical visit', 'vendor_assgined_on' => date(DB_DATE_FORMAT)), array('id' => $insert_id, 'vendor_id =' => 0));

                                    $update1 = $this->addressver_model->update_status('addrverres', array('verfstatus' => 1), array('addrverid' =>  $insert_id));

                                    if ($update) {

                                        $fiels = array(
                                            'vendor_id' => $vendor_id,
                                            'case_id' =>  $insert_id,
                                            "status" => 0,
                                            "remarks" => '',
                                            "created_by" => 1,
                                            "created_on" => date(DB_DATE_FORMAT),
                                            "approval_by" => 0,
                                            "modified_on" => null,
                                            "modified_by" => '',
                                        );

                                      $save_vendor_log = $this->addressver_model->save_vendor_log($fiels);
               
                                    }
                                }
                            }

                            if(isset($mode_of_verification_value->addrver) && ($mode_of_verification_value->addrver  == "digital"))
                            { 
                                $update = $this->addressver_model->upload_vendor_assign('addrver', array('vendor_id' => 25, 'vendor_list_mode' => 'digital', 'vendor_assgined_on' => date(DB_DATE_FORMAT)), array('id' =>  $insert_id, 'vendor_id =' => 0));

                                $update1 = $this->addressver_model->update_status('addrverres', array('verfstatus' => 1), array('addrverid' =>  $insert_id));

                                if ($update) {

                                    $fiels = array(
                                        'vendor_id' => 25,
                                        'case_id' => $insert_id,
                                        "status" => 0,
                                        "remarks" => '',
                                        "created_by" => 1,
                                        "created_on" => date(DB_DATE_FORMAT),
                                        "approval_by" => 0,
                                        "modified_on" => null,
                                        "modified_by" => '',
                                    );

                                $save_vendor_log = $this->addressver_model->save_vendor_log($fiels);
               
                                }  
                            }

                            auto_update_overall_status($check_record_exits['id']);
                            auto_update_tat_status($check_record_exits['id']);

                            $data['success'] = $add_com_ref . " This Component Code Records Created Successfully";

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

    public function bulk_upload_address_status_change()
    {

        if ($this->input->is_ajax_request()) {

            $frm_details = $this->input->post();

            if ($frm_details['upload_status'] == "stop_check") {

                $file_upload_path = SITE_BASE_PATH . ADDRESS;

                if (!folder_exist($file_upload_path)) {
                    mkdir($file_upload_path, 0777);
                } else if (!is_writable($file_upload_path)) {
                    $message = 'Problem while uploading, folder permission';
                }

                $uplaod_details = array('file_upload_path' => $file_upload_path, 'file_data' => $_FILES['status_change_bulk_upload'], 'file_permission' => 'csv|xls|xlsx', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 100);
                $upload_result = $this->file_uplod($uplaod_details);
                $record = array();

                if (!empty($upload_result) && $upload_result['status'] == true) {

                    $raw_filename = $_FILES['status_change_bulk_upload']['tmp_name'];

                    $headerLine = 0;
                    $file = fopen($raw_filename, "r");

                    $this->load->library('excel_reader', array('file_name' => $file_upload_path . '/' . $upload_result['file_name']));

                    $excel_handler = $this->excel_reader->file_handler;

                    $excel_data = $excel_handler->rows();

                    if (!empty($excel_data)) {

                        unset($excel_data[0]);

                        $excel_data = array_map("unserialize", array_unique(array_map("serialize", $excel_data)));

                        foreach ($excel_data as $value) {

                            if (count($value) < 3) {
                                continue;
                            }

                            $check_record_exits = $this->addressver_model->select(true, array('*'), array('add_com_ref' => strtolower($value[0])));

                            $check_status_filter_value = $this->addressver_model->select_addrverres_details(array('verfstatus', 'var_filter_status', 'var_report_status'), array('addrverid' => $check_record_exits['id']));

                            if (!empty($check_record_exits) && $value[0] != "") {

                                if ($check_status_filter_value[0]['var_filter_status'] == "WIP" || $check_status_filter_value[0]['var_filter_status'] == "wip") {

                                    $field_array = array('verfstatus' => 9,
                                        'var_filter_status' => "Closed",
                                        'var_report_status' => "Stop Check",
                                        'clientid' => $check_record_exits['clientid'],
                                        'candsid' => $check_record_exits['candsid'],
                                        'addrverid' => $check_record_exits['id'],
                                        'closuredate' => date('Y-m-d', strtotime(str_replace('/', '-', $value[1]))),
                                        'remarks' => $value[2],
                                        'modified_on' => date(DB_DATE_FORMAT),
                                        'modified_by' => $this->user_info['id'],
                                        'is_bulk_uploaded' => 1,

                                    );

                                    $result = $this->addressver_model->save_update_result(array_map('strtolower', $field_array), array('addrverid' => $check_record_exits['id']));

                                    $result_addrverres_result = $this->addressver_model->save_update_result_addrverres(array_map('strtolower', $field_array));

                                    $fields_activity_log = array(
                                        'candsid' => $check_record_exits['candsid'],
                                        'comp_table_id' => $check_record_exits['id'],
                                        'activity_status' => "Stop Check",
                                        'activity_mode' => "Stop Check",
                                        'activity_type' => "Stop Check",
                                        'action' => "Stop Check",
                                        'remarks' => $value[2],
                                        'created_by' => $this->user_info['id'],
                                        'created_on' => date(DB_DATE_FORMAT),
                                        'component_type' => 1,
                                    );

                                    $result_activity_log = $this->addressver_model->save_activity_log_trigger($fields_activity_log);

                                    $field_activity = array('candsid' => $check_record_exits['candsid'],
                                        'comp_table_id' => $check_record_exits['id'],
                                        'activity_mode' => "Stop Check",
                                        'activity_status' => "Stop Check",
                                        'activity_type' => "Stop Check",
                                        'action' => "Stop Check",
                                        'remarks' => $value[2],
                                        'created_on' => date(DB_DATE_FORMAT),
                                        'created_by' => $this->user_info['id'],
                                        'is_auto_filled' => 0,

                                    );

                                    $result_activity = $this->addressver_model->save_trigger($field_activity);

                                    $array_activity_log = array('activity_log_id' => $result_activity_log);

                                    $result_addrverres = $this->addressver_model->save_update_result($array_activity_log, array('addrverid' => $check_record_exits['id']));

                                    $result_addrverres_result = $this->addressver_model->save_update_result_addrverres($array_activity_log, array('id' => $result_addrverres_result));

                                    $get_vendor_log_deatail = $this->addressver_model->check_vendor_status_closed_or_not(array('view_vendor_master_log.component' => "addrver", 'view_vendor_master_log.component_tbl_id' => 1, 'addrver.id' => $check_record_exits['id']));

                                    if (!empty($get_vendor_log_deatail)) {

                                        $update_vendor_log_deatail = $this->addressver_model->reject_cost_vendor(array('final_status' => 'closed', 'vendor_remark' => 'Stop Check', 'modified_on' => date(DB_DATE_FORMAT)), array('view_vendor_master_log.id' => $get_vendor_log_deatail[0]['id']));

                                    }

                                    auto_update_overall_status($check_record_exits['candsid']);
                                    auto_update_tat_status($check_record_exits['candsid']);

                                    $data['success'] = $value[0] . " This Component Code Records Created Successfully";

                                } else {

                                    $data['success'] = $value[0] . " This Component Code Records Either Insufficiency or Closed";
                                }
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

            } elseif ($frm_details['upload_status'] == "clear") {
                $file_upload_path = SITE_BASE_PATH . ADDRESS;

                if (!folder_exist($file_upload_path)) {
                    mkdir($file_upload_path, 0777);
                } else if (!is_writable($file_upload_path)) {
                    $message = 'Problem while uploading, folder permission';
                }

                $uplaod_details = array('file_upload_path' => $file_upload_path, 'file_data' => $_FILES['status_change_bulk_upload'], 'file_permission' => 'csv|xls|xlsx', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 100);
                $upload_result = $this->file_uplod($uplaod_details);
                $record = array();

                if (!empty($upload_result) && $upload_result['status'] == true) {

                    $raw_filename = $_FILES['status_change_bulk_upload']['tmp_name'];

                    $headerLine = 0;
                    $file = fopen($raw_filename, "r");

                    $this->load->library('excel_reader', array('file_name' => $file_upload_path . '/' . $upload_result['file_name']));

                    $excel_handler = $this->excel_reader->file_handler;

                    $excel_data = $excel_handler->rows();

                    if (!empty($excel_data)) {

                        unset($excel_data[0]);

                        $excel_data = array_map("unserialize", array_unique(array_map("serialize", $excel_data)));

                        foreach ($excel_data as $value) {

                            if (count($value) < 14) {
                                continue;
                            }

                            $check_record_exits = $this->addressver_model->select(true, array('*'), array('add_com_ref' => strtolower($value[0])));
                           
                            $check_status_filter_value = $this->addressver_model->select_addrverres_details(array('verfstatus', 'var_filter_status', 'var_report_status'), array('addrverid' => $check_record_exits['id']));

                            if (!empty($check_record_exits) && $value[0] != "") {

                                if ($check_status_filter_value[0]['var_filter_status'] == "WIP" || $check_status_filter_value[0]['var_filter_status'] == "wip") {

                                    $field_array = array('verfstatus' => 17,
                                        'var_filter_status' => "Closed",
                                        'var_report_status' => "Clear",
                                        'clientid' => $check_record_exits['clientid'],
                                        'candsid' => $check_record_exits['candsid'],
                                        'addrverid' => $check_record_exits['id'],
                                        'res_address' => $check_record_exits['address'],
                                        'res_city' => $check_record_exits['city'],
                                        'res_pincode' => $check_record_exits['pincode'],
                                        'res_state' => $check_record_exits['state'],
                                        'res_stay_from' => $value[1],
                                        'res_stay_to' => $value[2],
                                        'address_action' => 'yes',
                                        'city_action' => 'yes',
                                        'pincode_action' => 'yes',
                                        'state_action' => 'yes',
                                        'stay_from_action' => 'no',
                                        'stay_to_action' => 'no',
                                        'mode_of_verification' => $value[3],
                                        'resident_status' => $value[4],
                                        'landmark' => $value[5],
                                        'verified_by' => $value[6],
                                        'neighbour_1' => $value[7],
                                        'neighbour_details_1' => $value[8],
                                        'neighbour_2' => $value[9],
                                        'neighbour_details_2' => $value[10],
                                        'addr_proof_collected' => $value[11],
                                        'closuredate' => date('Y-m-d', strtotime(str_replace('/', '-', $value[12]))),
                                        'remarks' => $value[13],
                                        'modified_on' => date(DB_DATE_FORMAT),
                                        'modified_by' => $this->user_info['id'],
                                        'is_bulk_uploaded' => 1,

                                    );

                                    $result = $this->addressver_model->save_update_result(array_map('strtolower', $field_array), array('addrverid' => $check_record_exits['id']));

                                    $result_addrverres_result = $this->addressver_model->save_update_result_addrverres(array_map('strtolower', $field_array));

                                    $fields_activity_log = array(
                                        'candsid' => $check_record_exits['candsid'],
                                        'comp_table_id' => $check_record_exits['id'],
                                        'activity_status' => "Verification Received",
                                        'activity_mode' => "Personal Visit",
                                        'activity_type' => "Other",
                                        'action' => "Clear",
                                        'remarks' => $value[13],
                                        'created_by' => $this->user_info['id'],
                                        'created_on' => date(DB_DATE_FORMAT),
                                        'component_type' => 1,
                                    );

                                    $result_activity_log = $this->addressver_model->save_activity_log_trigger($fields_activity_log);

                                    $field_activity = array('candsid' => $check_record_exits['candsid'],
                                        'comp_table_id' => $check_record_exits['id'],
                                        'activity_mode' => "Personal Visit",
                                        'activity_status' => "Verification Received",
                                        'activity_type' => "Other",
                                        'action' => "Clear",
                                        'remarks' => $value[13],
                                        'created_on' => date(DB_DATE_FORMAT),
                                        'created_by' => $this->user_info['id'],
                                        'is_auto_filled' => 0,

                                    );

                                    $result_activity = $this->addressver_model->save_trigger($field_activity);

                                    $array_activity_log = array('activity_log_id' => $result_activity_log);

                                    $result_addrverres = $this->addressver_model->save_update_result($array_activity_log, array('addrverid' => $check_record_exits['id']));

                                    $result_addrverres_result = $this->addressver_model->save_update_result_addrverres($array_activity_log, array('id' => $result_addrverres_result));

                                    auto_update_overall_status($check_record_exits['candsid']);
                                    auto_update_tat_status($check_record_exits['candsid']);

                                    $data['success'] = $value[0] . " This Component Code Records Created Successfully";
                                } else {

                                    $data['success'] = $value[0] . " This Component Code Records Either Insufficiency or Closed";
                                }
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
            } elseif ($frm_details['upload_status'] == "insufficiency") {
                $file_upload_path = SITE_BASE_PATH . ADDRESS;

                if (!folder_exist($file_upload_path)) {
                    mkdir($file_upload_path, 0777);
                } else if (!is_writable($file_upload_path)) {
                    $message = 'Problem while uploading, folder permission';
                }

                $uplaod_details = array('file_upload_path' => $file_upload_path, 'file_data' => $_FILES['status_change_bulk_upload'], 'file_permission' => 'csv|xls|xlsx', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 100);
                $upload_result = $this->file_uplod($uplaod_details);
                $record = array();

                if (!empty($upload_result) && $upload_result['status'] == true) {

                    $raw_filename = $_FILES['status_change_bulk_upload']['tmp_name'];

                    $headerLine = 0;
                    $file = fopen($raw_filename, "r");

                    $this->load->library('excel_reader', array('file_name' => $file_upload_path . '/' . $upload_result['file_name']));

                    $excel_handler = $this->excel_reader->file_handler;

                    $excel_data = $excel_handler->rows();

                    if (!empty($excel_data)) {

                        unset($excel_data[0]);

                        $excel_data = array_map("unserialize", array_unique(array_map("serialize", $excel_data)));

                        foreach ($excel_data as $value) {

                            if (count($value) < 4) {
                                continue;
                            }

                            $check_record_exits = $this->addressver_model->select(true, array('*'), array('add_com_ref' => strtolower($value[0])));

                            $check_status_filter_value = $this->addressver_model->select_addrverres_details(array('verfstatus', 'var_filter_status', 'var_report_status'), array('addrverid' => $check_record_exits['id']));

                            if ($check_status_filter_value[0]['var_filter_status'] == "WIP" || $check_status_filter_value[0]['var_filter_status'] == "wip") {

                                $check = $this->addressver_model->select_insuff(array('addrverid' => $check_record_exits['id'], 'status !=' => 3, 'insuff_clear_date is null' => null));

                                if (!empty($check_record_exits) && $value[0] != "") {

                                    if (empty($check)) {

                                        $result = $this->addressver_model->save_update_insuff(array('insuff_raised_date' => date('Y-m-d', strtotime(str_replace('/', '-', $value[1]))), 'insuff_raise_remark' => $value[3], 'status' => STATUS_ACTIVE, 'insff_reason' => $value[2], 'created_by' => $this->user_info['id'], 'addrverid' => $check_record_exits['id'], 'auto_stamp' => 1));

                                        auto_update_overall_status($check_record_exits['candsid']);

                                        $data['success'] = $value[0] . " This Component Code Records Created Successfully";
                                    } else {

                                        $data['success'] = $value[0] . " already insuff raised but other update insuff";
                                    }
                                }
                            } else {
                                $data['success'] = $value[0] . " This Component Code Records Either Insufficiency or Closed";
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

        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['message'] = 'Something went wrong, please try again';
        }
        echo_json($json_array);
    }

    public function assigned_cases()
    {
        if ($this->user_info['groupID'] !== "17") {
            $data['header_title'] = "New Cases | Address ";

            $data['assigned_user_id'] = $this->get_members_by_group(array('groupID' => 17));

            $this->load->view('admin/header', $data);

            $this->load->view('admin/address_list_new_cases');

            $this->load->view('admin/footer');
        } else {
            $data['header_title'] = "New Cases | Address ";

            $this->load->view('admin/header', $data);

            $this->load->view('admin/address_list_new_cases_for_member');

            $this->load->view('admin/footer');
        }
    }

    public function address_view_cases()
    {
        if ($this->input->is_ajax_request()) {
            $result = array();

            $cases = $this->addressver_model->get_assigned_cases();

            $i = 1;

            foreach ($cases as $key => $value) {
                $href = "<a class='my_class' href='" . ADMIN_SITE_URL . "address/view_details/" . $value['id'] . "' title='Edit'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a>";

                $new_array = array($i, $value['id'], $value['ClientRefNumber'], $value['cmp_ref_no'], $value['CandidateName'], $value['verder_address_name'], $value['custom1'], convert_db_to_display_date($value['has_assigned_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12), $href);

                $result['data'][] = $new_array;

                $i++;
            }
            echo json_encode($result);
        }
    }

    public function address_view_cases_member()
    {
        if ($this->input->is_ajax_request()) {
            $result = array();

            $cases = $this->addressver_model->get_assigned_cases(array('addrver.has_case_id' => $this->user_info['id']));

            $i = 1;

            foreach ($cases as $key => $value) {
                $href = "<a class='my_class' href='" . ADMIN_SITE_URL . "address/view_details_for_member/" . $value['id'] . "' title='Edit'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a>";

                $new_array = array($i, $value['ClientRefNumber'], $value['cmp_ref_no'], $value['CandidateName'], $value['verder_address_name'], convert_db_to_display_date($value['has_assigned_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12), $href);

                $result['data'][] = $new_array;

                $i++;
            }
            echo json_encode($result);
        }
    }

    public function get_clients_cads()
    {
        if ($this->input->is_ajax_request()) {

            log_message('error', 'Get Client');
            try {
                $clientid = $this->input->post('clientid');

                $selected_clientid = $this->input->post('selected_clientid');

                $disabled_b = $this->input->post('disabled_b');

                $json_array = array();

                if ($clientid != "") {
                    $cands = $this->common_model->select('cands', false, array('CandidateName', 'id'), array('clientid' => $clientid));

                    if (count($cands) > 0) {
                        $create_arr['0'] = 'Select Candidate';

                        foreach ($cands as $cand) {
                            $create_arr[$cand['id']] = ucwords($cand['CandidateName']);
                        }
                    } else {
                        $create_arr['0'] = 'No record found, in selected client';
                    }

                    echo form_dropdown('candsid', $create_arr, $selected_clientid, 'class="form-control" id="candsid"  ');
                }
            } catch (Exception $e) {
                log_message('error', 'Address::get_clients_cads');
                log_message('error', $e->getMessage());
            }
        }
    }

    public function view_details_for_member($address_com_id = '')
    {

        if (!empty($address_com_id)) {
            $emp_details = $this->addressver_model->get_all_addrs_by_client(array('addrver.id' => $address_com_id, 'addrver.has_case_id' => $this->user_info['id']));
            if (!empty($emp_details)) {
                $data['header_title'] = 'Edit Address Verification';

                $data['addressver_details'] = $emp_details[0];

                $data['clients'] = $this->get_clients(array('clients.addrver' => 1, 'status' => 1));

                $data['states'] = $this->get_states();

                $data['gruop_list'] = $this->gruop_list();

                $data['vendor_details'] = $this->get_vendor_detaild();

                $data['company'] = $this->get_company_list();

                $data['assigned_user_id'] = $this->get_members_by_group(array('groupID' => 17));

                $data['addressver_result_details'] = $this->addressver_model->get_address_ver_result(array('addrverres.addrverid' => $address_com_id));

                $this->load->view('admin/header', $data);

                $this->load->view('admin/address_edit');

                $this->load->view('admin/footer');
            } else {
                show_404();
            }
        } else {
            $this->index();
        }
    }

    public function addrver_update()
    {

        if ($this->input->post()) {

            $id = $this->input->post('id');

            $this->form_validation->set_rules('assignedto', 'Assigned To', 'required');

            $this->form_validation->set_rules('clientid', 'Client', 'required');

            $this->form_validation->set_rules('candsid', 'Candidate', 'required');

            $this->form_validation->set_rules('address', 'Address', 'required');

            $this->form_validation->set_rules('city', 'City', 'required');

            $this->form_validation->set_rules('pincode', 'PIN code', 'required');

            $this->form_validation->set_rules('state', 'State', 'required');

            if ($this->form_validation->run() == false) {

                $this->view_details($id);

            } else {
                try {
                    $frm_details = $this->input->post();
                    log_message('error', 'Form Data');
                    log_message('error', print_r($frm_details, true));
                    $doc = '';

                    if (isset($frm_details['documentsprovided'])) {
                        $doc = implode(",", $frm_details['documentsprovided']);
                    }

                    $verder_address_name = ($frm_details['verder_address_name'] != '0') ? $frm_details['verder_address_name'] : '';

                    $fields = array('assignedto' => $frm_details['assignedto'],
                        'clientid' => $frm_details['clientid'],
                        'candsid' => $frm_details['candsid'],
                        'address' => $frm_details['address'],
                        'city' => $frm_details['city'],
                        'pincode' => $frm_details['pincode'],
                        'state' => $frm_details['state'],
                        'documentsprovided' => $doc,
                        'vender_manager_id' => $verder_address_name,
                        'lastupdatedby' => $this->user_info['id'],
                        'reiniated_date' => convert_display_to_db_date($frm_details['reiniated_date']),
                        'updated' => date(DB_DATE_FORMAT),
                    );

                    $result = $this->addressver_model->save($fields, array('id' => $id));

                    if ($result) {
                        $this->session->set_flashdata('message', array('message' => 'Updated Successfully', 'class' => 'alert alert-success fade in'));
                        redirect('admin/address');
                    } else {
                        $this->session->set_flashdata('message', array('message' => 'Something went wrong, please try again', 'class' => 'alert alert-danger fade in'));

                        redirect('admin/address');
                    }

                } catch (Exception $e) {
                    log_message('error', 'Error on Address::addrver_update');
                    log_message('error', $e->getMessage());
                }
            }
        } else {
            redirect('admin/address');
        }
    }

    public function edit_address_veri_result($id)
    {
        if ($this->input->is_ajax_request()) {
            $result = $this->addressver_model->get_address_ver_result(array('addrverres.id' => $id));

            if (!empty($result)) {
                $data['addressver_result_details'] = $result[0];

                echo $this->load->view('admin/adds_veri_result_model', $data, true);
            } else {
                echo "<p>Something went wrong, please try again</p>";
            }
        } else {
            echo "<p>Something went wrong, please try again</p>";
        }
    }

    public function update_verificarion_result()
    {
        $json_array = array();

        $error_msgs = array();

        if ($this->input->is_ajax_request()) {

            log_message('error', 'Address Update Verification Result');
            try {
                $frm_details = $this->input->post();

                $id = array('id' => $frm_details['addverresult_id']);

                $fields = array('stayfrom' => $frm_details['stayfrom'],
                    'stayto' => $frm_details['stayto'],
                    'residentstatus' => $frm_details['residentstatus'],
                    'landmark' => $frm_details['landmark'],
                    'neighbour1' => $frm_details['neighbour1'],
                    'neighbour1details' => $frm_details['neighbour1details'],
                    'neighbour2' => $frm_details['neighbour2'],
                    'neighbour2details' => $frm_details['neighbour2details'],
                    'verifiername' => $frm_details['verifiername'],
                    'modeofverification' => $frm_details['modeofverification'],
                    'addrproofcollected' => $frm_details['addrproofcollected'],
                    'insuffraiseddate' => $frm_details['insuffraiseddate'],
                    'insuffcleardate' => $frm_details['insuffcleardate'],
                    'insuffremarks' => $frm_details['insuffremarks'],
                    "insuff_raised_date_2" => convert_display_to_db_date($frm_details['insuff_raised_date_2']),
                    "insuff_clear_date_2" => convert_display_to_db_date($frm_details['insuff_clear_date_2']),
                    "insuff_remarks_2" => $frm_details['insuff_remarks_2'],
                    'closuredate' => convert_display_to_db_date($frm_details['closuredate']),
                    'verfstatus' => $frm_details['verfstatus'],
                    'insuff_additional_remark_1' => $frm_details['insuff_additional_remark_1'],
                    'insuff_additional_remark_2' => $frm_details['insuff_additional_remark_2'],
                    "remark" => $frm_details['remark'],
                    "modefied_on" => date(DB_DATE_FORMAT),
                    "modefied_by" => $this->user_info['id'],
                );

                if (!empty($_FILES['photosavailable']['name'][0])) {
                    $file_upload_path = SITE_BASE_PATH . UPLOAD_FOLDER . ADDRESS . $frm_details['clientid'];

                    if (!folder_exist($file_upload_path)) {
                        mkdir($file_upload_path, 0777);
                    } else if (!is_writable($file_upload_path)) {
                        array_push($error_msgs, 'Problem while uploading');
                    }

                    $file_array = array();

                    $files_count = count($_FILES['photosavailable']['name']);

                    for ($i = 0; $i < $files_count; $i++) {
                        $file_name = $_FILES['photosavailable']['name'][$i];

                        $file_info = pathinfo($file_name);

                        $new_file_name = preg_replace('/[[:space:]]+/', '_', $file_info['filename']);

                        $new_file_name = $new_file_name . '_' . DATE(UPLOAD_FILE_DATE_FORMAT_FILE_NAME);

                        $new_file_name = str_replace('.', '_', $new_file_name);

                        $new_file_name = preg_replace('/[^A-Za-z0-9\-]/', '_', $new_file_name);

                        $file_extension = $file_info['extension'];

                        $new_file_name = $new_file_name . '.' . $file_extension;

                        $_FILES['photosavailable1']['name'] = $new_file_name;

                        $_FILES['photosavailable1']['tmp_name'] = $_FILES['photosavailable']['tmp_name'][$i];

                        $_FILES['photosavailable1']['error'] = $_FILES['photosavailable']['error'][$i];

                        $_FILES['photosavailable1']['size'] = $_FILES['photosavailable']['size'][$i];

                        $config['upload_path'] = $file_upload_path;

                        $config['file_name'] = $new_file_name;

                        $config['allowed_types'] = 'jpeg|jpg|png';

                        $config['file_ext_tolower'] = true;

                        $config['remove_spaces'] = true;

                        $config['max_size'] = BULK_UPLOAD_MAX_SIZE_MB * 1000;

                        $this->load->library('upload', $config);

                        $this->upload->initialize($config);

                        if ($this->upload->do_upload('photosavailable1')) {
                            array_push($file_array, array(
                                'file_name' => $new_file_name,
                                'real_filename' => $file_name,
                                'addrverres_id' => $frm_details['addverresult_id'],
                            ));
                        } else {
                            array_push($error_msgs, $this->upload->display_errors('', ''));

                            $json_array['status'] = ERROR_CODE;

                            $json_array['e_message'] = implode('<br>', $error_msgs);
                        }
                    }
                    auto_update_overall_status($frm_details['candsid_ver']);

                    if (!empty($file_array)) {
                        $this->addressver_model->uploaded_files($file_array);
                    }
                }

                $result = $this->addressver_model->save_update_adds_ver_result($fields, $id);

                if ($result) {
                    $this->session->set_flashdata('message', array('message' => 'Result Updated Successfully', 'class' => 'alert alert-success fade in', 'file_upload_error' => $json_array));

                    $json_array['redirect'] = ADMIN_SITE_URL . "address/view_details/" . $frm_details['addrverid'];

                    $json_array['status'] = SUCCESS_CODE;
                } else {
                    $this->session->set_flashdata('message', array('message' => 'Result Stored Successfully', 'class' => 'alert alert-success fade in'));

                    $json_array['message'] = 'Something went wrong, please try again';

                    $json_array['status'] = ERROR_CODE;
                }

                echo_json($json_array);

            } catch (Exception $e) {
                log_message('error', 'Address::update_verificarion_result');
                log_message('error', $e->getMessage());
            }
        }
    }

    public function template_download()
    {
        if ($this->input->is_ajax_request()) {

            log_message('error', 'Address Template Download');
            try {

               // $assigned_user_id = $this->addressver_model->get_assign_users_id('user_profile', false, array("`user_profile`.`status`,`user_profile`.`id`,`user_profile`.`email`,`user_profile`.`department`,`user_profile`.`user_name`,concat(`user_profile`.`firstname`,' ',`user_profile`.`lastname`) as fullname,`user_profile`.`profile_pic`,`user_profile`.`firstname`,`user_profile`.`lastname`,`roles`.`role_name`,`roles`.`groups_id`,`roles_permissions`.*"), array('user_profile.status' => STATUS_ACTIVE));

                $states = array('Select State', 'Andaman And Nicobar Islands', 'Andhra Pradesh', 'Arunachal Pradesh', 'Assam', 'Bihar', 'Chattisgarh', 'Chandigarh', 'Daman And Diu', 'Delhi', 'Dadra And Nagar Haveli', 'Goa', 'Gujarat', 'Himachal Pradesh', 'Haryana', 'Jammu And Kashmir', 'Jharkhand', 'Kerala', 'Karnataka', 'Lakshadweep', 'Meghalaya');

                $address_type = array('Select Address Type', 'permanent', 'current');

                require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
                // Create new Spreadsheet object
                $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
                // Set document properties
                $spreadsheet->getProperties()->setCreator(CRMNAME)
                    ->setLastModifiedBy(CRMNAME)
                    ->setTitle(CRMNAME)
                    ->setSubject('Address Import Template')
                    ->setDescription('Address Import Template File for bulk upload');

                $styleArray = array(
                    'fill' => array(
                        'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startcolor' => array(
                            'rgb' => 'FF0000',
                        ),
                    ),
                );
                $spreadsheet->getActiveSheet()->getStyle('A1:B1')->applyFromArray($styleArray);
                $spreadsheet->getActiveSheet()->getStyle('F1:I1')->applyFromArray($styleArray);

                foreach (range('A', 'I') as $columnID) {
                    $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                        ->setWidth(20);
                }

                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A1", REFNO)
                    ->setCellValue("B1", 'Comp Int Date')
                    ->setCellValue("C1", 'Stay From')
                    ->setCellValue("D1", 'Stay To')
                    ->setCellValue("E1", 'Address Type')
                    ->setCellValue("F1", 'Street Address')
                    ->setCellValue("G1", 'City')
                    ->setCellValue("H1", 'PIN Code')
                    ->setCellValue("I1", 'State');

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
                    $objValidation->setPrompt('Please insert value.');

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

                    $objValidation = $spreadsheet->getActiveSheet()->getCell('F' . $i)->getDataValidation();
                    $objValidation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                    $objValidation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
                    $objValidation->setAllowBlank(false);
                    $objValidation->setShowInputMessage(true);
                    $objValidation->setShowErrorMessage(true);
                    $objValidation->setErrorTitle('Input error');
                    $objValidation->setError('This Field is Compulsory.');
                    $objValidation->setPromptTitle('Insert Street Address');
                    $objValidation->setPrompt('Please insert Street Address.');

                    $objValidation = $spreadsheet->getActiveSheet()->getCell('G' . $i)->getDataValidation();
                    $objValidation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                    $objValidation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
                    $objValidation->setAllowBlank(false);
                    $objValidation->setShowInputMessage(true);
                    $objValidation->setShowErrorMessage(true);
                    $objValidation->setErrorTitle('Input error');
                    $objValidation->setError('This Field is Compulsory.');
                    $objValidation->setPromptTitle('Insert City');
                    $objValidation->setPrompt('Please insert City');

                    $objValidation = $spreadsheet->getActiveSheet()->getCell('H' . $i)->getDataValidation();
                    $objValidation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                    $objValidation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
                    $objValidation->setAllowBlank(false);
                    $objValidation->setShowInputMessage(true);
                    $objValidation->setShowErrorMessage(true);
                    $objValidation->setErrorTitle('Input error');
                    $objValidation->setError('This Field is Compulsory.');
                    $objValidation->setPromptTitle('Insert Pin Code');
                    $objValidation->setPrompt('Please insert Maximum 6 digit and Mimimum 6.');

                    $objValidation = $spreadsheet->getActiveSheet()->getCell('I' . $i)->getDataValidation();
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

                    $objValidation = $spreadsheet->getActiveSheet()->getCell('E' . $i)->getDataValidation();
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
                    $objValidation->setFormula1('"' . implode(',', $address_type) . '"');

                   /* $objValidation = $spreadsheet->getActiveSheet()->getCell('J' . $i)->getDataValidation();
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

                $spreadsheet->getActiveSheet()->setTitle('Address Records');
                $spreadsheet->setActiveSheetIndex(0);
                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header("Content-Disposition: attachment;filename=Address Bulk Uplaod Template.xlsx");
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

                $json_array['file_name'] = "Address Bulk Uplaod Template";

                $json_array['message'] = "File downloaded successfully,please check in download folder";

                $json_array['status'] = SUCCESS_CODE;

            } catch (Exception $e) {
                log_message('error', 'Address::template_download');
                log_message('error', $e->getMessage());
            }
        } else {
            $json_array['file_name'] = "Address Bulk Uplaod Template";

            $json_array['message'] = "File downloaded failed,please check in download folder";

            $json_array['status'] = ERROR_CODE;
        }
        echo_json($json_array);
    }

    public function template_download_status_change()
    {
        if ($this->input->is_ajax_request()) {

            log_message('error', 'Template Download Status Change');
            try {

                $frm_details = $this->input->post();

                if ($frm_details['status'] == "stop_check") {

                    require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
                    // Create new Spreadsheet object
                    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
                    // Set document properties
                    $spreadsheet->getProperties()->setCreator(CRMNAME)
                        ->setLastModifiedBy(CRMNAME)
                        ->setTitle(CRMNAME)
                        ->setSubject('Address Import Template')
                        ->setDescription('Address Import Template File for bulk upload');

                    $styleArray = array(
                        'fill' => array(
                            'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startcolor' => array(
                                'rgb' => 'FF0000',
                            ),
                        ),
                    );

                    $spreadsheet->getActiveSheet()->getStyle('A1:C1')->applyFromArray($styleArray);

                    foreach (range('A', 'C') as $columnID) {
                        $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                            ->setWidth(20);
                    }

                    $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue("A1", 'Component Ref No.')
                        ->setCellValue("B1", 'Closure Date')
                        ->setCellValue("C1", 'Remark');

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
                        $objValidation->setPrompt('Please insert value.');

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

                    }

                    $spreadsheet->getActiveSheet()->setTitle('Address Status Change');
                    $spreadsheet->setActiveSheetIndex(0);
                    // Redirect output to a client’s web browser (Excel2007)
                    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                    header("Content-Disposition: attachment;filename=Stop Check Bulk  Upload  Template.xlsx");
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

                    $json_array['file_name'] = "Stop Check Bulk  Uplaod Template";

                    $json_array['message'] = "File downloaded successfully,please check in download folder";

                    $json_array['status'] = SUCCESS_CODE;
                }

                if ($frm_details['status'] == "clear") {

                    // $states = array('Select State','Andaman And Nicobar Islands','Andhra Pradesh','Arunachal Pradesh','Assam','Bihar','Chattisgarh','Chandigarh','Daman And Diu','Delhi','Dadra And Nagar Haveli','Goa','Gujarat','Himachal Pradesh','Haryana','Jammu And Kashmir','Jharkhand','Kerala','Karnataka','Lakshadweep','Meghalaya');

                    $address_type = array('Select Address Type', 'permanent', 'current');

                    //$actions = array('yes','no','not-verified');

                    $mode_of_verification = array('personal visit', 'verbal', 'others');

                    $resident_status = array('Select Resident Status', 'rented', 'rwned', 'pg', 'relatives', 'government quarter', 'private quarter', 'hostel', 'others');

                    $address_proof = array('Select Address Proof', 'aadhar card', 'ration card', 'electricity bill', 'voter id', 'driving license', 'others', 'none');

                    require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
                    // Create new Spreadsheet object
                    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
                    // Set document properties
                    $spreadsheet->getProperties()->setCreator(CRMNAME)
                        ->setLastModifiedBy(CRMNAME)
                        ->setTitle(CRMNAME)
                        ->setSubject('Address Import Template')
                        ->setDescription('Address Import Template File for bulk upload');

                    $styleArray = array(
                        'fill' => array(
                            'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startcolor' => array(
                                'rgb' => 'FF0000',
                            ),
                        ),
                    );

                    $spreadsheet->getActiveSheet()->getStyle('A1:E1')->applyFromArray($styleArray);
                    $spreadsheet->getActiveSheet()->getStyle('L1:N1')->applyFromArray($styleArray);

                    foreach (range('A', 'N') as $columnID) {
                        $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                            ->setWidth(20);
                    }

                    $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue("A1", 'Component Ref No.')
                    /*  ->setCellValue("B1",'Street Address')
                    ->setCellValue("C1",'City')
                    ->setCellValue("D1",'Pincode')
                    ->setCellValue("E1",'State')*/
                        ->setCellValue("B1", 'Stay From')
                        ->setCellValue("C1", 'Stay To')
                    /* ->setCellValue("H1",'Street Address Action')
                    ->setCellValue("I1",'City Action')
                    ->setCellValue("J1",'Pincode Action')
                    ->setCellValue("K1",'State Action')
                    ->setCellValue("L1",'Stay From Action')
                    ->setCellValue("M1",'Stay To Action')*/
                        ->setCellValue("D1", 'Mode of Verification')
                        ->setCellValue("E1", 'Resident Status')
                        ->setCellValue("F1", 'Landmark')
                        ->setCellValue("G1", 'Verified By')
                        ->setCellValue("H1", 'Neighbour 1')
                        ->setCellValue("I1", 'Neighbour Details 1')
                        ->setCellValue("J1", 'Neighbour 2')
                        ->setCellValue("K1", 'Neighbour Details 2')
                        ->setCellValue("L1", 'Addr. Proof Collected')
                        ->setCellValue("M1", 'Closure Date')
                        ->setCellValue("N1", 'Remarks');

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
                        $objValidation->setPrompt('Please insert value.');

                        $objValidation = $spreadsheet->getActiveSheet()->getCell('B' . $i)->getDataValidation();
                        $objValidation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                        $objValidation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
                        $objValidation->setAllowBlank(false);
                        $objValidation->setShowInputMessage(true);
                        $objValidation->setShowErrorMessage(true);
                        $objValidation->setErrorTitle('Input error');
                        $objValidation->setError('This Field is Compulsory.');
                        $objValidation->setPromptTitle('Insert Value');
                        $objValidation->setPrompt('Please insert value.');

                        $objValidation = $spreadsheet->getActiveSheet()->getCell('C' . $i)->getDataValidation();
                        $objValidation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                        $objValidation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
                        $objValidation->setAllowBlank(false);
                        $objValidation->setShowInputMessage(true);
                        $objValidation->setShowErrorMessage(true);
                        $objValidation->setErrorTitle('Input error');
                        $objValidation->setError('This Field is Compulsory.');
                        $objValidation->setPromptTitle('Insert Value');
                        $objValidation->setPrompt('Please insert value.');

                        /* $objValidation = $spreadsheet->getActiveSheet()->getCell('D'.$i)->getDataValidation();
                        $objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST  );
                        $objValidation->setErrorStyle(  \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION );
                        $objValidation->setAllowBlank(false);
                        $objValidation->setShowInputMessage(true);
                        $objValidation->setShowErrorMessage(true);
                        $objValidation->setErrorTitle('Input error');
                        $objValidation->setError('This Field is Compulsory.');
                        $objValidation->setPromptTitle('Insert Pin Code');
                        $objValidation->setPrompt('Please insert Maximum 6 digit and Mimimum 6.');

                        $objValidation = $spreadsheet->getActiveSheet()->getCell('E'.$i)->getDataValidation();
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
                        $objValidation->setFormula1('"'.implode(',', $states).'"');*/

                        /* $objValidation = $spreadsheet->getActiveSheet()->getCell('H'.$i)->getDataValidation();
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
                        $objValidation->setFormula1('"'.implode(',', $actions).'"');

                        $objValidation = $spreadsheet->getActiveSheet()->getCell('I'.$i)->getDataValidation();
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
                        $objValidation->setFormula1('"'.implode(',', $actions).'"');

                        $objValidation = $spreadsheet->getActiveSheet()->getCell('J'.$i)->getDataValidation();
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
                        $objValidation->setFormula1('"'.implode(',', $actions).'"');

                        $objValidation = $spreadsheet->getActiveSheet()->getCell('K'.$i)->getDataValidation();
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
                        $objValidation->setFormula1('"'.implode(',', $actions).'"');

                        $objValidation = $spreadsheet->getActiveSheet()->getCell('L'.$i)->getDataValidation();
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
                        $objValidation->setFormula1('"'.implode(',', $actions).'"');

                        $objValidation = $spreadsheet->getActiveSheet()->getCell('M'.$i)->getDataValidation();
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
                        $objValidation->setFormula1('"'.implode(',', $actions).'"');*/

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
                        $objValidation->setFormula1('"' . implode(',', $mode_of_verification) . '"');

                        $objValidation = $spreadsheet->getActiveSheet()->getCell('E' . $i)->getDataValidation();
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
                        $objValidation->setFormula1('"' . implode(',', $resident_status) . '"');

                        $objValidation = $spreadsheet->getActiveSheet()->getCell('L' . $i)->getDataValidation();
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
                        $objValidation->setFormula1('"' . implode(',', $address_proof) . '"');

                        $objValidation = $spreadsheet->getActiveSheet()->getCell('M' . $i)->getDataValidation();
                        $objValidation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                        $objValidation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
                        $objValidation->setAllowBlank(false);
                        $objValidation->setShowInputMessage(true);
                        $objValidation->setShowErrorMessage(true);
                        $objValidation->setErrorTitle('Input error');
                        $objValidation->setError('This Field is Compulsory.');
                        $objValidation->setPromptTitle('Insert Date Only');
                        $objValidation->setPrompt('Please insert date only format(DD-MM-YYYY).');

                    }

                    $spreadsheet->getActiveSheet()->setTitle('Address Status Change');
                    $spreadsheet->setActiveSheetIndex(0);
                    // Redirect output to a client’s web browser (Excel2007)
                    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                    header("Content-Disposition: attachment;filename=Clear Bulk  Uplaod Template.xlsx");
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

                    $json_array['file_name'] = "Clear Bulk  Upload Template";

                    $json_array['message'] = "File downloaded successfully,please check in download folder";

                    $json_array['status'] = SUCCESS_CODE;

                }
                if ($frm_details['status'] == "insufficiency") {

                    // $insuff_reason_list = $this->addressver_model->insuff_reason_list(FALSE,array('component_id' => 1));
                    //$insuff_reason_list = array('Other');

                    require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
                    // Create new Spreadsheet object
                    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
                    // Set document properties
                    $spreadsheet->getProperties()->setCreator(CRMNAME)
                        ->setLastModifiedBy(CRMNAME)
                        ->setTitle(CRMNAME)
                        ->setSubject('Address Import Template')
                        ->setDescription('Address Import Template File for bulk upload');

                    $styleArray = array(
                        'fill' => array(
                            'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startcolor' => array(
                                'rgb' => 'FF0000',
                            ),
                        ),
                    );

                    $spreadsheet->getActiveSheet()->getStyle('A1:C1')->applyFromArray($styleArray);

                    foreach (range('A', 'D') as $columnID) {
                        $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                            ->setWidth(20);
                    }

                    $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue("A1", 'Component Ref No.')
                        ->setCellValue("B1", 'Raised Date(DD-MM-YYYY)')
                        ->setCellValue("C1", 'Reason')
                        ->setCellValue("D1", 'Remark');

                    $x = 2;
                    for ($i = 1; $i <= 4; $i++) {

                        $spreadsheet->setActiveSheetIndex(0)
                            ->setCellValue("C$x", "Other");

                        /*  $objValidation = $spreadsheet->getActiveSheet()->getCell('A'.$i)->getDataValidation();
                        $objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST  );
                        $objValidation->setErrorStyle(  \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION );
                        $objValidation->setAllowBlank(false);
                        $objValidation->setShowInputMessage(true);
                        $objValidation->setShowErrorMessage(true);
                        $objValidation->setErrorTitle('Input error');
                        $objValidation->setError('This Field is Compulsory.');
                        $objValidation->setPromptTitle('Insert Value');
                        $objValidation->setPrompt('Please insert value.');

                        $objValidation = $spreadsheet->getActiveSheet()->getCell('W'.$i)->getDataValidation();
                        $objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST  );
                        $objValidation->setErrorStyle(  \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION );
                        $objValidation->setAllowBlank(false);
                        $objValidation->setShowInputMessage(true);
                        $objValidation->setShowErrorMessage(true);
                        $objValidation->setErrorTitle('Input error');
                        $objValidation->setError('This Field is Compulsory.');
                        $objValidation->setPromptTitle('Insert Date Only');
                        $objValidation->setPrompt('Please insert date only format(DD-MM-YYYY).');

                        $objValidation = $spreadsheet->getActiveSheet()->getCell('C'.$i)->getDataValidation();
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
                        $objValidation->setFormula1('"'.implode(',', $insuff_reason_list).'"');*/

                        $x++;
                    }
                    $spreadsheet->getActiveSheet()->setTitle('Address Status Change');
                    $spreadsheet->setActiveSheetIndex(0);
                    // Redirect output to a client’s web browser (Excel2007)
                    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                    header("Content-Disposition: attachment;filename=Insufficiency Bulk Upload Template.xlsx");
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

                    $json_array['file_name'] = "Insufficiency Bulk  Uplaod Template";

                    $json_array['message'] = "File downloaded successfully,please check in download folder";

                    $json_array['status'] = SUCCESS_CODE;

                }

            } catch (Exception $e) {
                log_message('error', 'Address::template_download_status_change');
                log_message('error', $e->getMessage());
            }

        } else {
            $json_array['file_name'] = "Address Bulk Status Change Uplaod Template";

            $json_array['message'] = "File downloaded failed,please check in download folder";

            $json_array['status'] = ERROR_CODE;
        }
        echo_json($json_array);
    }

    public function bulk_upload()
    {
        $data['header_title'] = "Address Bulk Upload";

        $data['assigned_user_id'] = $this->get_members_by_group(array('groupID' => 17));

        $data['clients'] = $this->get_clients(array('clients.addrver' => 1, 'status' => 1));

        $data['gruop_list'] = $this->gruop_list();

        $this->load->view('admin/header', $data);

        $this->load->view('admin/address_bulk_upload_view');

        $this->load->view('admin/footer');
    }

    public function export_to_excel()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {

            log_message('error', 'Address Export To Excel');
            try {

                ini_set('memory_limit', '-1');
                set_time_limit(0);

                $where_arry = array();

                $client_id = ($this->input->post('clientid') != "All") ? $this->input->post('clientid') : false;

                $vendor_id = ($this->input->post('vendor_id') != "" ) ? $this->input->post('vendor_id') : false;

                $fil_by_status = ($this->input->post('fil_by_status') != "All") ? $this->input->post('fil_by_status') : false;

                $client_name = $this->input->post('client_name');

                $from_date = ($this->input->post('from_date') != "") ? convert_display_to_db_date($this->input->post('from_date')) : false;

                $to_date = ($this->input->post('to_date') != "") ? convert_display_to_db_date($this->input->post('to_date')) : false;

                $all_records = $this->addressver_model->get_all_address_by_client($client_id, $fil_by_status, $from_date, $to_date,$vendor_id);

                require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
                // Create new Spreadsheet object
                $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

                // Set document properties
                $spreadsheet->getProperties()->setCreator(CRMNAME)
                    ->setLastModifiedBy(CRMNAME)
                    ->setTitle(CRMNAME)
                    ->setSubject('Address records')
                    ->setDescription('Address records with their status');

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
                    ->setCellValue("K1", 'Primary Contact')
                    ->setCellValue("L1", 'Contact No (2)')
                    ->setCellValue("M1", 'Contact No (3)')
                    ->setCellValue("N1", 'Address')
                    ->setCellValue("O1", 'City')
                    ->setCellValue("P1", 'Pincode')
                    ->setCellValue("Q1", 'State')
                    ->setCellValue("R1", 'Status')
                    ->setCellValue("S1", 'Sub Status')
                    ->setCellValue("T1", 'Executive Name')
                    ->setCellValue("U1", 'Vendor')
                    ->setCellValue("V1", 'Vendor Status')
                    ->setCellValue("W1", 'Vendor Assigned on')
                    ->setCellValue("X1", 'Closure Date')
                    ->setCellValue("Y1", 'Insuff Raised Date')
                    ->setCellValue("Z1", 'Insuff Clear Date')
                    ->setCellValue("AA1", 'Insuff Remark');
                // Add some data
                $x = 2;
                foreach ($all_records as $all_record) {

                    $ad_status = ($all_record['verfstatus'] != "") ? $all_record['verfstatus'] : "WIP";
                    $ad_filter_status = ($all_record['filter_status'] != "") ? $all_record['filter_status'] : "WIP";
                    $closuredate = ($all_record['filter_status'] == "Closed") ? convert_db_to_display_date($all_record['closuredate']) : "NA";
                    $insuff_remarks = $all_record['insuff_raise_remark'];

                    $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue("A$x", ucwords($all_record['clientname']))
                        ->setCellValue("B$x", ucwords($all_record['entity_name']))
                        ->setCellValue("C$x", ucwords($all_record['package_name']))
                        ->setCellValue("D$x", $all_record['cmp_ref_no'])
                        ->setCellValue("E$x", $all_record['add_com_ref'])
                        ->setCellValue("F$x", $all_record['ClientRefNumber'])
                        ->setCellValue("G$x", $all_record['transaction_id'])
                        ->setCellValue("H$x", convert_db_to_display_date($all_record['iniated_date']))
                        ->setCellValue("I$x", ucwords($all_record['CandidateName']))
                        ->setCellValue("J$x", ucwords($all_record['NameofCandidateFather']))
                        ->setCellValue("K$x", $all_record['CandidatesContactNumber'])
                        ->setCellValue("L$x", $all_record['ContactNo1'])
                        ->setCellValue("M$x", $all_record['ContactNo2'])
                        ->setCellValue("N$x", $all_record['address'])
                        ->setCellValue("O$x", $all_record['city'])
                        ->setCellValue("P$x", $all_record['pincode'])
                        ->setCellValue("Q$x", $all_record['state'])
                        ->setCellValue("R$x", $ad_filter_status)
                        ->setCellValue("S$x", $ad_status)
                        ->setCellValue("T$x", $all_record['executive_name'])
                        ->setCellValue("U$x", $all_record['vendor_name'])
                        ->setCellValue("V$x", ucwords($all_record['vendor_status']))
                        ->setCellValue("W$x", convert_db_to_display_date($all_record['vendor_assgined_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12))
                        ->setCellValue("X$x", $closuredate)
                        ->setCellValue("Y$x", $all_record['insuff_raised_date'])
                        ->setCellValue("Z$x", $all_record['insuff_clear_date'])
                        ->setCellValue("AA$x", $insuff_remarks);

                    $x++;
                }
                // Rename worksheet
                $spreadsheet->getActiveSheet()->setTitle('Address Records');

                $spreadsheet->setActiveSheetIndex(0);

                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header("Content-Disposition: attachment;filename=Address Records of $client_name.xlsx");
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

                $json_array['file_name'] = "Address Records of $client_name";

                $json_array['message'] = "File downloaded successfully,please check in download folder";

                $json_array['status'] = SUCCESS_CODE;

            } catch (Exception $e) {
                log_message('error', 'Address::export_to_excel');
                log_message('error', $e->getMessage());
            }

        } else {
            $json_array['message'] = "Something went wrong,please try again";
            $json_array['status'] = ERROR_CODE;
        }

        echo_json($json_array);
    }

    public function assign_address_cases()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {
            $frm_details = $this->input->post();

            $where = array();

            foreach ($frm_details['assign_case'] as $key => $value) {
                array_push($where, array('has_assigned_on' => date(DB_DATE_FORMAT), 'has_case_id' => $frm_details['has_case_id'], 'id' => $value));
            }

            $result = $this->common_model->assign_cases_to_team('addrver', $where);

            if ($result) {
                $this->session->set_flashdata('message', array('message' => 'Cases Assigned Successfully', 'class' => 'alert alert-success fade in'));

                $json_array['redirect'] = ADMIN_SITE_URL . "address/assigned_cases";

                $json_array['status'] = SUCCESS_CODE;

                $json_array['message'] = 'Cases Assigned Successfully';
            } else {
                $this->session->set_flashdata('message', array('message' => 'Something went wrong, please try again', 'class' => 'alert alert-danger fade in'));
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = 'Something went worong, please try again';
            }

            echo_json($json_array);
        }
    }

    public function remove_uploaded_file($id)
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {

            log_message('error', 'Address Remove File');
            try {

                $result = $this->addressver_model->save_update_addver_files(array('status' => 2), array('id' => $id));

                if ($result) {
                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'Attachment removed successfully, please refresh the page';
                } else {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Something went worong, please try again';
                }
                echo_json($json_array);

            } catch (Exception $e) {
                log_message('error', 'Address::remove_uploaded_file');
                log_message('error', $e->getMessage());
            }
        }
    }

    public function undo_deleted_uploaded_file($id)
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {

            log_message('error', 'Address Undo Remove File');
            try {
                $result = $this->addressver_model->save_update_addver_files(array('status' => 1), array('id' => $id));

                if ($result) {
                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'Deleted File UNDO successfully, please refresh the page';
                } else {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Something went worong, please try again';
                }
                echo_json($json_array);

            } catch (Exception $e) {
                log_message('error', 'Address::undo_deleted_uploaded_file');
                log_message('error', $e->getMessage());
            }

        }
    }

    protected function get_vendor_id_by_pincode($pincode)
    {
        $CI = &get_instance();

        $query = $CI->db->query("CALL get_vendor_id_by_pincode($pincode)");

        $vendor_id = $query->result_array();

        $query->next_result();

        $query->free_result();

        if (!empty($vendor_id)) {
            return $vendor_id[0];
        } else {
            return false;
        }
    }

    public function vendor_cases()
    {
        $data['header_title'] = "Address Verification Vendor Cases";

        $data['vendor_details'] = $this->addressver_model->vendor_address_details();

        $data['assigned_cases'] = $this->addressver_model->vendor_assigned_cases(array('addrver.is_assigned' => 0));

        $this->load->view('admin/header', $data);

        $this->load->view('admin/address_vendor_cases');

        $this->load->view('admin/footer');
    }

    private function rejected_cases()
    {
        return $this->addressver_model->vendor_assigned_cases(array('addrver.vendor_rejected_on !=' => ""));
    }

    public function vendor_rejected()
    {
        $data['header_title'] = "Vendor Rejected Cases";

        $data['vendor_details'] = $this->addressver_model->vendor_address_details();

        $data['assigned_cases'] = $this->rejected_cases();

        $this->load->view('admin/header', $data);

        $this->load->view('admin/address_rejected_vendor_cases');

        $this->load->view('admin/footer');
    }
    public function rearrage_uploaded_files($id, $clientid)
    {
        if ($id && $clientid) {
            $data['header_title'] = 'Arrage File';
            $data['clientid'] = $clientid;
            $data['ajax_url'] = ADMIN_SITE_URL . '/address/';
            $data['file_path'] = ADDRESS_COM;
            $data['files'] = $this->addressver_model->get_add_uploded_files(array('status' => 1, 'addrverres_id' => $id));
            $this->load->view('admin/header', $data);
            $this->load->view('admin/rearrage_file_view');
            $this->load->view('admin/footer');
        } else {
            show_404();
        }
    }

    public function update_arrage_img()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {
            $frm_details = $this->input->post();

            if (is_array($frm_details) && !empty($frm_details)) {
                $order = $frm_details['item'];

                if (is_array($order) && !empty($order)) {
                    foreach ($order as $key => $value) {
                        $update[] = array('serialno' => $key + 1, 'id' => $value);
                    }

                    $this->addressver_model->upload_file_update($update);

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

    public function delete()
    {

        $address_id = $this->input->post('address_id');

        if ($this->input->is_ajax_request() && $this->permission['access_address_list_delete'] == true) {

            if ($address_id) {

                $field_array = array('status' => STATUS_DELETED,
                    'modified_on' => date(DB_DATE_FORMAT),
                    'modified_by' => $this->user_info['id'],
                );

                $where_array = array('id' => $address_id);

                if ($this->addressver_model->save($field_array, $where_array)) {
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

    public function insuff_raise_remark()
    {
        if ($this->input->is_ajax_request()) {

            log_message('error', 'Address Insuff Raised Remark');
            try {

                $reslt = $this->common_model->select('raising_insuff_dropdown', true, array("remarks"), array('reason' => $this->input->post('insff_reason')));
                if (!empty($reslt)) {
                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = $reslt['remarks'];
                } else {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = '';
                }
                echo_json($json_array);

            } catch (Exception $e) {
                log_message('error', 'Address::insuff_raise_remark');
                log_message('error', $e->getMessage());
            }

        }
    }

    public function vendor_logs($id)
    {
        if ($this->input->is_ajax_request() && $id) {

            log_message('error', 'Address Vendor log');
            try {

                $vendor_result = $this->addressver_model->vendor_logs(array('component_tbl_id' => "1", "component" => "addrver"), $id);

                $vendor_result_digital = $this->addressver_model->vendor_logs_digital($id);

                $html_view = '<thead><tr><th>Sr No</th><th>Trasaction Id</th><th>Vendor Name</th><th>Approved Date</th><th>File Name</th><th>Closure Date</th><th>Status</th><th>Action</th><th>Remark</th></tr></thead>';
                $counter = 1;
                foreach ($vendor_result as $key => $value) {
                    
                    $vendor_attachments = $this->addressver_model->select_file_vendor(array('view_vendor_master_log_file.id', 'view_vendor_master_log_file.file_name', 'view_vendor_master_log_file.real_filename', 'view_vendor_master_log_file.status'), array('view_vendor_master_log_file.component_tbl_id' => 1), $value['case_id']); 
                    $file = "&nbsp;&nbsp;&nbsp;&nbsp;";
                    if ($vendor_attachments) {

                        for ($j = 0; $j < count($vendor_attachments); $j++) {
                            $folder_name = "vendor_file";
                            $url = "'" . SITE_URL . ADDRESS . $folder_name . '/';
                            $actual_file = $vendor_attachments[$j]['file_name'] . "'";
                            $myWin = "'" . "myWin" . "'";
                                $attribute = "'" . "height=250,width=480" . "'";

                            $file .= '<a href="javascript:;" ondblClick="myOpenWindow(' . $url . $actual_file . ',' . $myWin . ',' . $attribute . '); return false"> <i class="fa fa-file-photo-o" aria-hidden="true"></i> </a>&nbsp;&nbsp;&nbsp;&nbsp;';

                        }
                    }
                    
                    $html_view .= '<tr>';
                    $html_view .= "<td>" . $counter . "</td>";
                    $html_view .= "<td>" . $value['trasaction_id'] . "</td>";
                    $html_view .= "<td>" . $value['vendor_name'] . "</td>";
                  
                    $html_view .= "<td>" . convert_db_to_display_date($value['approval_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12) . "</td>";
            
                    $html_view .= "<td>" . $file . "</td>";
                    $html_view .= "<td>" . convert_db_to_display_date($value['vendor_date']) . "</td>";
                
                   
                    $html_view .= "<td>" . $value['final_status'] . "</td>";

                    if ($value['status'] != "5") {
                      
                        $access_cost = ($this->permission['access_address_aq_allow'] == 1) ? '#showvendorModel_cost'  : '';
                        $access_cancle = ($this->permission['access_address_aq_allow'] == 1) ? '#showvendorModel_cancel'  : '';

                      

                        $html_view .= '<td><button data-id=' . $value['id'] . ' data-url ="' . ADMIN_SITE_URL . 'address/View_vendor_log/' . $value['id'] . '" data-toggle="modal" data-target="#showvendorModel" class="btn-info  showvendorModel"> View </button>&nbsp;&nbsp;';
                        
                        if ($value['final_status'] != "closed") {
                            
                            $html_view .= '<button data-id="showvendorModel_cost" data-url ="' . ADMIN_SITE_URL . 'address/View_vendor_log_cost/' . $value['id'] . '" data-toggle="modal" data-target="'.$access_cost.'"  class="btn-info  showvendorModel_cost">Charge</button>&nbsp;&nbsp;';

                        }


                       
                        if ($value['final_status'] == "wip" || $value['final_status'] == "insufficiency") {

                            $html_view .= '<button data-id="showvendorModel_cancel" data-case-id = "'.$value['case_id'].'" data-url ="' . ADMIN_SITE_URL . 'address/View_vendor_log_cancel/' . $value['id'] . '" data-toggle="modal" data-target="'.$access_cancle.'"  class="btn-info  showvendorModel_cancel">Cancel</button>';

                           
                        }


                        if ($value['final_status'] == "candidate shifted" || $value['final_status'] == "unable to verify" || $value['final_status'] == "denied verifiaction" || $value['final_status'] == "resigned" || $value['final_status'] == "candidate not responding") {
                 
                            if($value['digital_insuff'] == 2)
                            {
                                 
                                $html_view .= '<button id="show_vendor_insuff" data-id = "'.$value['id'].'" data-case-id = "'.$value['case_id'].'"  data-stat = "1" data-url ="' . ADMIN_SITE_URL . 'address/vendor_insuff_hide_show/"   class="btn-info">Start</button>'; 
                            }
                            else{

                               
                                $html_view .= '<button id="hide_vendor_insuff" data-id = "'.$value['id'].'" data-case-id = "'.$value['case_id'].'" data-stat = "2" data-url ="' . ADMIN_SITE_URL . 'address/vendor_insuff_hide_show/"   class="btn-info">Stop</button>'; 
                            } 
                        }

                       
                        $html_view .= '</td>'; 
                    } else {
                        $html_view .= '<td></td>';
                    }

                    $html_view .= "<td>" . $value['vendor_remark'] . "</td>";

                    $html_view .= '</tr>';
                  

                    $counter++;
                }

                $vendor_count =  count($vendor_result);

                $counter_digital = $vendor_count + 1;

                foreach ($vendor_result_digital as $key => $value) {


                    $access_digital_cancle = ($this->permission['access_address_aq_allow'] == 1) ? '#showvendorModeldigital_cancel'  : '';
                    

                    $html_view .= '<tr>';
                    $html_view .= "<td>" . $counter_digital . "</td>";
                    $html_view .= "<td>" . "" . "</td>";
                    $html_view .= "<td>" . ucwords($value['vendor_name']) . "</td>";
                  
                    $html_view .= "<td>" . convert_db_to_display_date($value['vendor_digital_assgined_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12) . "</td>";
            
                    $html_view .= "<td>" . "" . "</td>";
                    $html_view .= "<td>" . "" . "</td>";
                
                   
                    $html_view .= "<td>" . "" . "</td>";
                   
                   if($vendor_result_digital[0]['verification_status'] == 3)
                   {
                       $html_view .= "<td>" . "Cancelled" . "</td>";
                   }
                   else
                   {
                     $login_url = SITE_URL.'av/'.base64_encode($value['id']);
                     $html_view .= '<td><button data-id="showvendorModeldigital_cancel" data-case-id = "'.$value['id'].'" data-url ="' . ADMIN_SITE_URL . 'address/View_vendor_log_digital_cancel/' . $value['id'] . '" data-toggle="modal" data-target="'.$access_digital_cancle.'"  class="btn-info  showvendorModeldigital_cancel">Cancel</button>&nbsp;&nbsp;<button class="btn btn-info btn-sm copyLink" data-link="'.$login_url .'" >Copy Link</button></td>';
                    }                     

                    $html_view .= "<td>" . "" . "</td>";

                    $html_view .= '</tr>';
                  

                    $counter_digital++;
                }


                $json_array['message'] = $html_view;

                $json_array['status'] = SUCCESS_CODE;

            } catch (Exception $e) {
                log_message('error', 'Address::vendor_logs');
                log_message('error', $e->getMessage());
            }

        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['message'] = '<tr><td>Something went wrong, please try again!<td></tr>';
        }
        echo_json($json_array);
    }

    public function View_vendor_log($where_id)
    {
        $details = $this->addressver_model->select_vendor_result_log(array('component_tbl_id' => "1", "component" => "addrver"), $where_id);

        if ($where_id && !empty($details)) {

            log_message('error', 'Address Vendor View log');
            try {

                $details['attachments'] = $this->addressver_model->select_file_from_vendor(array('id', 'file_name', 'real_filename'), array('view_venor_master_log_id' => $where_id, 'status' => 1, 'component_tbl_id' => 1));
                $details['attachments_file'] = $this->addressver_model->select_file_address(array('id', 'file_name', 'real_filename'), array('addrver_id' => $details[0]['address_id'], 'status' => 1));

                $details['check_insuff_raise'] = '';

                $details['states'] = $this->get_states();
                $details['details'] = $details[0];

                echo $this->load->view('admin/address_ads_vendor_view', $details, true);

            } catch (Exception $e) {
                log_message('error', 'Address::View_vendor_log');
                log_message('error', $e->getMessage());
            }
        } else {
            echo "<h4>Record Not Found</h4>";
        }

    }

    public function View_vendor_log1($where_id)
    {
        $details = $this->addressver_model->select_vendor_result_log(array('component_tbl_id' => "1", "component" => "addrver"), $where_id);

        if ($where_id && !empty($details)) {

            log_message('error', 'Vendor Approve tab');
            try {

                $details['attachments'] = $this->addressver_model->select_file_from_vendor(array('id', 'file_name', 'real_filename','status'), array('view_venor_master_log_id' => $where_id,  'component_tbl_id' => 1));
                $details['attachments_file'] = $this->addressver_model->select_file_address(array('id', 'file_name', 'real_filename','status'), array('addrver_id' => $details[0]['address_id'], 'status' => 1,'type' => 0));

                $details['check_insuff_raise'] = '';

                $details['states'] = $this->get_states();
                $details['details'] = $details[0];

                echo $this->load->view('admin/address_ads_vendor_view_approve_tab', $details, true);

            } catch (Exception $e) {
                log_message('error', 'Address::View_vendor_log1');
                log_message('error', $e->getMessage());
            }

        } else {
            echo "<h4>Record Not Found</h4>";
        }

    }

    public function vendor_logs_cost()
    {

        if ($this->input->is_ajax_request()) {

            log_message('error', 'Address  Vendor Log Cost Details');
            try {

                $frm_details = $this->input->post();

                $details = $this->addressver_model->select_vendor_result_log_cost_details(array('components_tbl_id' => "1"), $frm_details['id']);

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

            } catch (Exception $e) {
                log_message('error', 'Address::vendor_logs_cost');
                log_message('error', $e->getMessage());
            }
        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['message'] = '<tr><td>Something went wrong, please try again!<td></tr>';
        }

        echo_json($json_array);

    }

    public function View_vendor_log_cost($where_id)
    {

        $details = $this->addressver_model->select_vendor_result_log_cost(array('component_tbl_id' => "1", "component" => "addrver"), $where_id);

        if ($where_id && !empty($details)) {

            $details['details'] = $details[0];

            echo $this->load->view('admin/address_ads_vendor_view_cost', $details, true);
        } else {
            echo "<h4>Record Not Found</h4>";
        }

    }

    public function View_vendor_log_cancel($where_id)
    {

        $details = $this->addressver_model->select_vendor_result_log_cost(array('component_tbl_id' => "1", "component" => "addrver"), $where_id);

        if ($where_id && !empty($details)) {

            $details['details'] = $details[0];

            echo $this->load->view('admin/address_ads_vendor_view_cancel', $details, true);
        } else {
            echo "<h4>Record Not Found</h4>";
        }

    }

    public function View_vendor_log_digital_cancel($where_id)
    {

        $details = $this->addressver_model->select_address_dt('addrver',array('*'),array('addrver.id' => $where_id));

        if ($where_id && !empty($details)) {

            $details['details'] = $details[0];

            echo $this->load->view('admin/address_ads_vendor_view_digital_cancel', $details, true);
        } else {
            echo "<h4>Record Not Found</h4>";
        }

    }

    public function View_vendor_log_digital_entries_view($where_id)
    {
       
        $details = $this->addressver_model->address_case_global_list(array('addrver.vendor_digital_id' => 25,'addrver.verification_status' => 1,'addrver.id' => $where_id));

        $this->load->model('activity_data_model');

        $logs = $this->addressver_model->follow_up_activity_log_records(array('comp_table_id' => $where_id, 'component_type' => 'address','action'=>"Follow Up"));    

        if ($where_id && !empty($details)) {

            log_message('error', 'Digital Vendor Approve tab');
            try {

                $details['header_title'] = ucwords($details[0]['CandidateName']).' Profile';  
                $details['details'] = $details[0];
                $details['logs'] = $logs;

                echo $this->load->view('admin/address_digital_vednor_details', $details, true);

            } catch (Exception $e) {
                log_message('error', 'Address::View_vendor_log_digital');
                log_message('error', $e->getMessage());
            }

        } else {
            echo "<h4>Record Not Found</h4>";
        }

    }

    public function add_vendor_result_attachment()
    {
        if ($this->input->is_ajax_request()) {

            $this->form_validation->set_rules('vendor_log_id', 'Id', 'required');

            if ($this->form_validation->run() == false) {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');
            } else {

                log_message('error', 'Add vendor Attachment');
                try {

                    $frm_details = $this->input->post();

                    $folder_name = "vendor_file";
                    $file_upload_path = SITE_BASE_PATH . ADDRESS . $folder_name;
                    if (!folder_exist($file_upload_path)) {
                        mkdir($file_upload_path, 0777);
                    } else if (!is_writable($file_upload_path)) {
                        array_push($error_msgs, 'Problem while uploading');
                    }

                    $config_array = array('file_upload_path' => $file_upload_path, 'file_permission' => 'jpeg|jpg|png|pdf|tiff', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 2000, 'view_venor_master_log_id' => $frm_details['vendor_log_id'], 'component_tbl_id' => 1, 'status' => 1);
                
                   
                    if(isset($_FILES['attchments_file']))
                    {
                        if ($_FILES['attchments_file']['name'][0] != '') {
                            $config_array['files_count'] = count($_FILES['attchments_file']['name']);
                            $config_array['file_data'] = $_FILES['attchments_file'];

                            $retunr_de = $this->file_upload_library->file_upload_multiple_vendor($config_array, true);

                            if (!empty($retunr_de)) {
                                $this->common_model->common_insert_batch('view_vendor_master_log_file', $retunr_de['success']);
                            }
                        }
                    }
                    
                    $attachment = array(); 
                    if (!empty($retunr_de['success'])) {
                      
                        foreach ($retunr_de['success'] as $key => $value) {
                            
                            array_push($attachment,$value['file_name']);

                            if(file_exists($file_upload_path.'/'.$value['real_filename']))
                            {
                                $info = pathinfo($file_upload_path.'/'.$value['real_filename']);
     
                            } 
                        } 
                    }
                   
                    if ($retunr_de['success']) {
                       
                         
                        $json_array['status'] = SUCCESS_CODE;

                        $json_array['message'] = 'Uploaded Successfully';

                        $json_array['attachments'] = implode(',', $attachment);

                      
                    } else {
                        $json_array['status'] = ERROR_CODE;

                        $json_array['message'] = 'Something went wrong,please try again';
                    }

                } catch (Exception $e) {
                    log_message('error', 'Address::add_verificarion_ver_result_attachment');
                    log_message('error', $e->getMessage());
                }
            }
            echo_json($json_array);
        } 
    }

    public function Save_vendor_details()
    {
        $this->load->library('email');


        if ($this->input->is_ajax_request()) {

            log_message('error', 'Address Save Vendor  Details');
            try {

                $this->form_validation->set_rules('transaction_id', 'transaction id', 'required');

                $this->form_validation->set_rules('vendor_remark', 'Vendor Remark', 'required');

                if ($this->form_validation->run() == false) {

                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = validation_errors('', '');

                } else {

                  
                    $frm_details = $this->input->post();

                    $transaction_id = $frm_details['transaction_id'];

                    $get_status = $this->addressver_model->select_address_dt('view_vendor_master_log',array('id','final_status','case_id'),array('trasaction_id' => $transaction_id)); 
                    
                    if($get_status[0]['final_status'] != $frm_details['status'])
                    {
                        if($frm_details['status'] == 'wip')
                        {
                           
                            $address_details_vendor = $this->addressver_model->get_address_details_for_approval($get_status[0]['case_id']);
                     
                            $email_tmpl_data['subject'] = 'Address - Insufficiency cleared for_'.$address_details_vendor[0]['add_com_ref'];

                            $message = "<p>Team,</p><p> Please make a note of the comments.</p>";
                                    
                            $message .= "<table border = '2'  style='border-spacing: 10; border-collapse: collapse;'>
                                <tr><td style='background-color: #EDEDED;text-align:center'>Date</td> <td style='text-align:center'>".date('d-m-Y H:i:s')."</td></tr>
                                <tr><td style='background-color: #EDEDED;text-align:center'>Client Name</td><td style='text-align:center'>". $address_details_vendor[0]['clientname']."</td></tr>
                                <tr><td style='background-color: #EDEDED;text-align:center'>Component Ref No</td><td style='text-align:center'>". $address_details_vendor[0]['add_com_ref']."</td></tr>
                                <tr><td style='background-color: #EDEDED;text-align:center'>Candidate Name</td><td style='text-align:center'>". $address_details_vendor[0]['CandidateName']."</td></tr>
                                <tr><td style='background-color: #EDEDED;text-align:center'>Contact Nos</td><td style='text-align:center'>". $address_details_vendor[0]['CandidatesContactNumber']." | ".$address_details_vendor[0]['ContactNo1']." | ".$address_details_vendor[0]['ContactNo2']."</td></tr>
                                <tr><td style='background-color: #EDEDED;text-align:center'>Address</td><td style='text-align:center'>". $address_details_vendor[0]['address'].",".$address_details_vendor[0]['city'].",".$address_details_vendor[0]['pincode'].",".$address_details_vendor[0]['state']."</td></tr>
                                <tr><td style='background-color: #EDEDED;text-align:center'>Remarks</td><td style='text-align:center'>".$frm_details['vendor_remark']."</td>
                                </tr>";
                      
                            
                            $message .= "</table>";
        
                            $message .= "<br><br><p><b>Note :</b> <I>This is an auto generated email. Request you to write back within 24 hrs with any discrepancy.</I>";
                       
                            $to_emails = $this->addressver_model->vendor_email_id(array('vendors.id' => $address_details_vendor[0]['vendor_id']));
                        
                            $email_tmpl_data['to_emails'] = $to_emails[0]['email_id'];
                        
                            $email_tmpl_data['message'] = $message;

                            $results = $this->email->address_approve_vendor_cases_mail_send($email_tmpl_data);
        
                            $this->email->clear(true);
                        }
                        
                    }

                    $field_array = array('final_status' => $frm_details['status'], 'vendor_remark' => $frm_details['vendor_remark'], 'modified_on' => date(DB_DATE_FORMAT), 'vendor_date' => convert_display_to_db_date($frm_details['vendor_date']));

                    $result = $this->addressver_model->save_vendor_details("view_vendor_master_log", array_map('strtolower', $field_array), array('trasaction_id' => $transaction_id));

                    $folder_name = "vendor_file";
                    $file_upload_path = SITE_BASE_PATH . ADDRESS . $folder_name;
                    if (!folder_exist($file_upload_path)) {
                        mkdir($file_upload_path, 0777);
                    } else if (!is_writable($file_upload_path)) {
                        array_push($error_msgs, 'Problem while uploading');
                    }


                    if(!empty($frm_details['attachment_list_vendor']))
                    {
                        $attachment_list_vendor = explode(',',$frm_details['attachment_list_vendor']);

                        foreach ($attachment_list_vendor as $key => $value) {
                                   
                            $this->compress_image_vendor(SITE_BASE_PATH . ADDRESS . $folder_name.'/'.$value, SITE_BASE_PATH . ADDRESS . $folder_name.'/'.$value, 80);

                        }
                    }
                        
                    $config_array = array('file_upload_path' => $file_upload_path, 'file_permission' => 'jpeg|jpg|png|pdf|tiff', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 2000, 'view_venor_master_log_id' => $frm_details['view_vendor_master_log_id'], 'component_tbl_id' => 1, 'status' => 1);
                
                   
                    if(isset($_FILES['attchments_file']))
                    {
                        if ($_FILES['attchments_file']['name'][0] != '') {
                            $config_array['files_count'] = count($_FILES['attchments_file']['name']);
                            $config_array['file_data'] = $_FILES['attchments_file'];

                            $retunr_de = $this->file_upload_library->file_upload_multiple_vendor($config_array, true);

                            if (!empty($retunr_de)) {
                                $this->common_model->common_insert_batch('view_vendor_master_log_file', $retunr_de['success']);
                            }
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

            } catch (Exception $e) {
                log_message('error', 'Address::Save_vendor_details');
                log_message('error', $e->getMessage());
            }
        }
    }

    public function Save_vendor_details_cost()
    {
        if ($this->input->is_ajax_request()) {

            log_message('error', 'Address Vendor Cost Details');
            try {

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
                        'components_tbl_id' => 1,
                        'status' => 1,
                        'created_by' => $this->user_info['id'],
                        'created_on' => date(DB_DATE_FORMAT),

                    );

                    $result = $this->addressver_model->save_vendor_details_costing("vendor_cost_details", $field_array);

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

            } catch (Exception $e) {
                log_message('error', 'Address::Save_vendor_details_cost');
                log_message('error', $e->getMessage());
            }
        }
    }

    public function Save_vendor_details_cancel()
    {
        $this->load->library('email');

        if ($this->input->is_ajax_request()) {

            log_message('error', 'Address Vendor Details Cancel');
            try {

                $frm_details = $this->input->post();

                $this->form_validation->set_rules('update_id', 'ID', 'required');

                $this->form_validation->set_rules('venodr_reject_reason', 'venodr reject reason', 'required');

                if ($this->form_validation->run() == false) {

                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = validation_errors('', '');

                } else {

                    $frm_details = $this->input->post();
  
                    $address_details_vendor = $this->addressver_model->get_address_details_for_approval($frm_details['case_id']);
                  
                    $email_tmpl_data['subject'] = 'Address - Stop check cases_'.date("d-M-Y H:i");
                    $message = "<p>Team,</p><p> The below case/s have been cancelled by the client.</p>";
                            
                    $message .= "<table border = '2'  style='border-spacing: 10; border-collapse: collapse;'>
                        <tr>
                        <th style='background-color: #EDEDED;text-align:center'>Sr No</th>
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
                    $i = 1;
                    foreach ($address_details_vendor as $address_key => $address_value) {
                        
                        $message .= '<tr>
                            <td style="text-align:center">'.$i. '</td>
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

                        $i++;
                               
                        } 
                    
                    $message .= "</table>";

                    $message .= "<br><br><p><b>Note :</b> <I>This is an auto generated email. Request you to write back within 24 hrs with any discrepancy.</I>";
                 
                    $to_emails = $this->addressver_model->vendor_email_id(array('vendors.id' => $address_details_vendor[0]['vendor_id']));
                   
                    $email_tmpl_data['to_emails'] = $to_emails[0]['email_id'];
                   
                    $email_tmpl_data['message'] = $message;
                 
                    $results = $this->email->address_approve_vendor_cases_mail_send($email_tmpl_data);

                    $this->email->clear(true);

                    if(!empty($results) && $results == "Success")
                    {

                        $field_array = array(
                            'remarks' => $frm_details['venodr_reject_reason'],
                            'rejected_by' => $this->user_info['id'],
                            'rejected_on' => date(DB_DATE_FORMAT),
                            'status' => 5,
                            'final_status' => "cancelled",
                        );

                        $result = $this->addressver_model->save_vendor_details_cancel($field_array, array('id' => $frm_details['update_id']));

                        $field_array_address = array(
                            'remarks' => $frm_details['venodr_reject_reason'],
                            'modified_by' => $this->user_info['id'],
                            'modified_on' => date(DB_DATE_FORMAT),
                            'status' => 2,
                        );

                       $address_vendor_result = $this->addressver_model->update_address_vendor_log('address_vendor_log', $field_array_address, array('id' => $frm_details['case_id']));

                    }
                    else{
                        $result = '';
                        $address_vendor_result = '';
                    }
                
                    

                    if ($result && $address_vendor_result) {

                        $json_array['status'] = SUCCESS_CODE;

                        $json_array['message'] = 'Vendor Rejected Successfully';

                    } else {

                        $json_array['status'] = ERROR_CODE;

                        $json_array['message'] = 'Something went wrong,please try again';

                    }

                }
                echo_json($json_array);

            } catch (Exception $e) {
                log_message('error', 'Drugs_narcotics::Save_vendor_details_cancel');
                log_message('error', $e->getMessage());
            }
        }
    }



    public function Save_vendor_details_digital_cancel()
    {
        
        if ($this->input->is_ajax_request()) {

            log_message('error', 'Address Vendor digital Details Cancel');
            try {

                $frm_details = $this->input->post();

                $this->form_validation->set_rules('update_id', 'ID', 'required');

                $this->form_validation->set_rules('venodr_reject_digital_reason', 'venodr reject reason', 'required');

                if ($this->form_validation->run() == false) {

                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = validation_errors('', '');

                } else {

                    $frm_details = $this->input->post();

                    $address_digital_vendor = $this->addressver_model->update_closure_approval_details('addrver',array('verification_status'=> 3,'cancel_reason'=> $frm_details['venodr_reject_digital_reason']),array('addrver.id' => $frm_details['update_id']));
                   

                  
                    if(!empty($address_digital_vendor))
                    {
                        $json_array['status'] = SUCCESS_CODE;

                        $json_array['message'] = 'Digital Vendor Rejected Successfully';

                    } else {

                        $json_array['status'] = ERROR_CODE;

                        $json_array['message'] = 'Something went wrong,please try again';

                    }

                }
                echo_json($json_array);

            } catch (Exception $e) {
                log_message('error', 'Address::Save_vendor_details_digital_cancel');
                log_message('error', $e->getMessage());
            }
        }
    }

    public function vendor_cost_approval()
    {
        if ($this->input->is_ajax_request()) {
            $details['detail'] = $this->addressver_model->get_vendor_cost_aprroval_details();

            //  $details['details'] = $details[0];

        } else {
            $details['detail'] = "Access Denied, You don’t have permission to access this page";
        }

        echo $this->load->view('admin/address_vendor_cost_details', $details, true);
        //echo $this->load->view('admin/address_ajax_tab',$data,TRUE);

    }

    
    public function address_digital_entries()
    {
        if ($this->input->is_ajax_request()) {

            log_message('error', 'Address Digital Entries');
            try {

                $details['user_list_closed'] = $this->users_list_filter();


                $details['vendor_executive_list'] = $this->addressver_model->address_case_global_list(array('addrver.vendor_digital_id' => 25,'addrver.verification_status' => 1,'addrverres.var_filter_status'=> "WIP"));

            } catch (Exception $e) {
                log_message('error', 'Address::address_digital_entries');
                log_message('error', $e->getMessage());
            }

        } else {
            $details['vendor_executive_list'] = "Something went wrong,please try again";
        }

        echo $this->load->view('admin/address_vendor_digital_entries', $details, true);

    }

    public function address_closure_entries($userid)
    {
        if ($this->input->is_ajax_request() && $userid) {

            log_message('error', 'Address Closure Entries');
            try {

                $details['user_list_closed'] = $this->users_list_filter();

                $details['userid'] = $userid;

                $details['vendor_executive_list'] = $this->addressver_model->address_case_list(array('view_vendor_master_log.status =' => 1, 'component' => 'addrver'), $userid);
               

            } catch (Exception $e) {
                log_message('error', 'Address::address_closure_entries');
                log_message('error', $e->getMessage());
            }

        } else {
            $details['vendor_executive_list'] = "Something went wrong,please try again";
        }

        echo $this->load->view('admin/address_vendor_closure_entries', $details, true);

    }

    public function address_digital_closure_entries()
    {
        if ($this->input->is_ajax_request()) {

            log_message('error', 'Address digital Closure Entries');
            try {

                
                $details['vendor_digital_vendor_list'] = $this->addressver_model->address_case_global_list_closed(array('addrver.vendor_digital_id' => 25,'addrver.verification_status' => 2,'address_details.status' => 1));


            } catch (Exception $e) {
                log_message('error', 'Address::address_digital_closure_entries');
                log_message('error', $e->getMessage());
            }

        } else {
            $details['vendor_executive_list'] = "Something went wrong,please try again";
        }

        echo $this->load->view('admin/address_digital_vendor_closure_entries', $details, true);

    }


    public function address_closure_entries_vendor_insuff($userid)
    {
        if ($this->input->is_ajax_request() && $userid) {

            log_message('error', 'Address vendor insuff');
            try {

                $details['user_list_insuff'] = $this->users_list_filter();

                $details['userid'] = $userid;

                $details['vendor_executive_list'] = $this->addressver_model->address_case_list_insuff(array('view_vendor_master_log.status =' => 1,'component' => 'addrver','digital_insuff' => '1'), $userid);

            } catch (Exception $e) {
                log_message('error', 'Activity_log::address_closure_entries_vendor_insuff');
                log_message('error', $e->getMessage());
            }

        } else {
            $details['vendor_executive_list'] = "Something went wrong,please try again";
        }

        echo $this->load->view('admin/address_vendor_insuff_entries', $details, true);

    }

    public function check_insuff_already_raised($where_id)
    {

        log_message('error', 'Address Check Insuff Already Raised');
        try {

            $addressver_details = $this->addressver_model->get_address_details(array('addrver.id' => $where_id));

            $check_insuff_raise = $this->addressver_model->select_insuff(array('addrverid' => $where_id, 'status' => STATUS_ACTIVE, 'insuff_clear_date is null' => null));

            $data['insuff_reason_list'] = $this->addressver_model->insuff_reason_list(false, array('component_id' => 1));

            $data['insuff_raise_message'] = (!empty($check_insuff_raise) ? 'Insufficiency raised on ' . convert_db_to_display_date($check_insuff_raise[0]['insuff_raised_date']) . ' for ' . $check_insuff_raise[0]['insff_reason'] : '');

            $data['check_insuff_raise'] = ((!empty($check_insuff_raise)) ? 'disabled' : '');
            $data['check_insuff_value'] = ((!empty($check_insuff_raise)) ? '1' : '2');
            $data['insuff_raise_id'] = (!empty($check_insuff_raise) ? $check_insuff_raise[0]['id'] : '');

            if (empty($check_insuff_raise)) {
                $data['get_cands_details'] = $this->candidate_entity_pack_details($addressver_details[0]['candsid']);
                $data['addressver_details'] = $addressver_details[0];

                echo $this->load->view('admin/address_insuff_view', $data, true);

            } else {
                echo "<h4>Insuff Already Created</h4>";
            }

        } catch (Exception $e) {
            log_message('error', 'Address::check_insuff_already_raised');
            log_message('error', $e->getMessage());
        }
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

            $result = $this->addressver_model->approve_cost(array("accept_reject_cost" => "1", "approved_by" => $approved_by, "approved_on" => $aprroved_on), array('id' => $id));

            $result1 = $this->addressver_model->approve_cost_vendor(array("costing" => $cost, "additional_costing" => $add_cost), array('id' => $vendor_master_log_id));
            //  $details['details'] = $details[0];

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

        //echo $this->load->view('admin/address_ajax_tab',$data,TRUE);

    }

    public function rejectcost()
    {

        if ($this->input->is_ajax_request()) {

            log_message('error', 'Address Reject Cost');
            try {

                $id = $this->input->post('id');
                $vendor_master_log_id = $this->input->post('vendor_master_log_id');
                $rejected_by = $this->user_info['id'];
                $rejected_on = date(DB_DATE_FORMAT);

                $result = $this->addressver_model->reject_cost(array("accept_reject_cost" => "2", "rejected_by" => $rejected_by, "rejected_on" => $rejected_on), array('id' => $id));

                $result1 = $this->addressver_model->reject_cost_vendor(array("costing" => "0", "additional_costing" => "0"), array('id' => $vendor_master_log_id));

                //  $details['details'] = $details[0];

                if ($result && $result1) {

                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'Reject Vendor Cost ';

                } else {

                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Something went wrong,please try again';

                }

            } catch (Exception $e) {
                log_message('error', 'Address::rejectcost');
                log_message('error', $e->getMessage());
            }

        } else {
            $json_array['status'] = ERROR_CODE;

            $json_array['message'] = 'Something went wrong,please try again';
        }

        echo_json($json_array);

    }

    public function get_holiday_list()
    {

        $get_holiday1 = $this->get_holiday();

        $get_holiday = array_map('current', $get_holiday1);

        if ($get_holiday) {

            $json_array['status'] = SUCCESS_CODE;

            $json_array['message'] = $get_holiday;

        } else {

            $json_array['status'] = ERROR_CODE;

            $json_array['message'] = 'Something went wrong,please try again';

        }
        echo_json($json_array);

    }

    public function approve_first_qc()
    {
        if ($this->input->is_ajax_request()) {

            log_message('error', 'Adress Aprrove First QC');
            try {

                $frist_qc_id = $this->input->post('frist_qc_id');
                $cands_id = $this->input->post('frist_cands_id');
                $comp_id = $this->input->post('frist_comp_id');
                $cands_name = $this->input->post('frist_cands_name');
                $ref_no = $this->input->post('frist_ref_no');

                $accepted_on = date(DB_DATE_FORMAT);

                $result = $this->addressver_model->save_first_qc_result(array("first_qc_approve" => "First QC Approve", "first_qc_updated_on" => $accepted_on), array("addrverid" => $comp_id, "id" => $frist_qc_id, "candsid" => $cands_id));

                if ($result) {

                    $user_activity_data = $this->common_model->user_actity_data(array('component' => "Address",
                        'ref_no' => $ref_no, 'candidate_name' => $cands_name, 'created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id'], 'activity_type' => 'Manual', 'action' => 'First QC Approved'));

                    all_component_closed_qc_status($cands_id);

                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'First QC Approve';

                } else {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Something went wrong,please try again';
                }

            } catch (Exception $e) {
                log_message('error', 'Address::approve_first_qc');
                log_message('error', $e->getMessage());
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

            log_message('error', 'Activity Rejected First Qc');
            try {

                $comp_id = $this->input->post('frist_comp_id');
                $frist_qc_id = $this->input->post('frist_qc_id');
                $rejected_reason = $this->input->post('reject_reason');
                $cands_id = $this->input->post('frist_cands_id');
                $rejected_on = date(DB_DATE_FORMAT);
                $cands_name = $this->input->post('frist_cands_name');
                $ref_no = $this->input->post('frist_ref_no');

                $result = $this->addressver_model->save_first_qc_result(array("first_qu_reject_reason" => $rejected_reason, "first_qc_approve" => '', "first_qc_updated_on" => $rejected_on, "verfstatus" => 13, "var_filter_status" => "WIP", "var_report_status" => "WIP"), array("addrverid" => $comp_id, "id" => $frist_qc_id, "candsid" => $cands_id));

                if ($result) {
                    $user_activity_data = $this->common_model->user_actity_data(array('component' => "Address",
                        'ref_no' => $ref_no, 'candidate_name' => $cands_name, 'created_on' => date(DB_DATE_FORMAT), 'created_by' => $this->user_info['id'], 'activity_type' => 'Manual', 'action' => 'First QC Rejected'));

                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'First QC Rejected';
                } else {

                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Something went wrong,please try again';

                }

            } catch (Exception $e) {
                log_message('error', 'Address::rejected_first_qc');
                log_message('error', $e->getMessage());
            }
        } else {
            $json_array['status'] = ERROR_CODE;

            $json_array['message'] = 'Something went wrong,please try again';
        }
        echo_json($json_array);
    }

    public function address_closure()
    {
        $json_array['status'] = ERROR_CODE;
        $json_array['message'] = "Access Denied, You don’t have permission to access this page";

        if ($this->input->is_ajax_request()) {

            log_message('error', 'Address AQ Closure Tab');
            try {

                if ($this->permission['access_address_list_activity'] == 1) {
                    $frm_details = $this->input->post();

                    $action = $frm_details['action'];

                    if ($frm_details['action'] == 2 && $frm_details['closure_id'] != "") {
                        $list = explode(',', $frm_details['closure_id']);

                        $update_counter = 0;
                        $files = array();

                        foreach ($list as $key => $value) {

                            $update_closure = $this->addressver_model->update_closure_approval('view_vendor_master_log_closure_status', array('view_vendor_master_log_id' => $value, 'approve_reject_status' => 2, 'approve_reject_by' => $this->user_info['id'], 'reject_reasons' => $frm_details['reject_reason'], "approve_rejected_on" => date(DB_DATE_FORMAT)));

                            $update_closure1 = $this->addressver_model->update_closure_approval_details('view_vendor_master_log', array('final_status' => 'wip'), array('id' => $value));

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

                            $update_closure = $this->addressver_model->update_closure_approval('view_vendor_master_log_closure_status', array('view_vendor_master_log_id' => $value, 'approve_reject_status' => 1, 'approve_reject_by' => $this->user_info['id'], "approve_rejected_on" => date(DB_DATE_FORMAT)));

                            $update_closure1 = $this->addressver_model->update_closure_approval_details('view_vendor_master_log', array('final_status' => 'approve'), array('id' => $value));

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
                }
            } catch (Exception $e) {
                log_message('error', 'Address::address_closure');
                log_message('error', $e->getMessage());
            }
        }
        echo_json($json_array);
    }

    public function address_closure_digital()
    {
        $json_array['status'] = ERROR_CODE;
        $json_array['message'] = "Access Denied, You don’t have permission to access this page";

        if ($this->input->is_ajax_request()) {

            log_message('error', 'Address AQ Closure Tab');
            try {

                $frm_details = $this->input->post();

                if ($frm_details['action'] == 2 && $frm_details['closure_id'] != "") {


                    $update_closure = $this->addressver_model->update_closure_approval_details('address_details', array('status' => $frm_details['action'],'reject_reason' => $frm_details['reject_reason']), array('id' => $frm_details['closure_id']));

                    $update_address = $this->addressver_model->update_closure_approval_details('addrver', array('verification_status' => 1), array('id' => $frm_details['address_ids']));

                    if (!empty($update_closure) && !empty($update_address)) {

                        $json_array['status'] = SUCCESS_CODE;
                        $json_array['message'] = "Successfully Rejected Closure";

                        $json_array['redirect'] = ADMIN_SITE_URL . 'digital_closure';
                    } else {
                        $json_array['status'] = ERROR_CODE;
                        $json_array['message'] = "Something went wrong,please try again";
                    }

                } 
            } catch (Exception $e) {
                log_message('error', 'Address::address_closure');
                log_message('error', $e->getMessage());
            }
        }
        echo_json($json_array);
    }


    public function address_activity_log()
    {
        if ($this->input->is_ajax_request()) {

            log_message('error', 'Activity Activity Log');
            try {

                $encrypt_id = $this->input->post('id');
                $decrypt_id = decrypt($encrypt_id);

                $details = $this->addrver_model->select_vendor_activity_log(array('component' => "addrver", "transaction_id" => $decrypt_id));
                $counter = 1;

                $html_view = '<thead><tr><th>Sr No</th><th>Created On</th><th>Created By</th><th>Action</th><th>Status</th><th>Remark</th></tr></thead>';

                foreach ($details as $key => $value) {

                    $html_view .= '<tr>';
                    $html_view .= "<td>" . $counter . "</td>";
                    $html_view .= "<td>" . $value['created_on'] . "</td>";
                    $html_view .= "<td>" . $value['created_by'] . "</td>";
                    $html_view .= "<td>" . $value['action'] . "</td>";
                    $html_view .= "<td>" . $value['status'] . "</td>";
                    $html_view .= "<td>" . $value['remark'] . "</td>";

                    $html_view .= '</tr>';

                    $counter++;
                }
                $json_array['message'] = $html_view;
                $json_array['status'] = SUCCESS_CODE;

            } catch (Exception $e) {
                log_message('error', 'Address::address_activity_log');
                log_message('error', $e->getMessage());
            }
        } else {
            $json_array['status'] = ERROR_CODE;
            $json_array['message'] = '<tr><td>Something went wrong, please try again!<td></tr>';
        }
        echo_json($json_array);
    }

    public function bulk_update_case_received_date()
    {

        if ($this->input->is_ajax_request()) {

            $frm_details = $this->input->post();

          
                $file_upload_path = SITE_BASE_PATH . ADDRESS;

                if (!folder_exist($file_upload_path)) {
                    mkdir($file_upload_path, 0777);
                } else if (!is_writable($file_upload_path)) {
                    $message = 'Problem while uploading, folder permission';
                }

                $uplaod_details = array('file_upload_path' => $file_upload_path, 'file_data' => $_FILES['case_received_date_change_bulk_upload'], 'file_permission' => 'csv|xls|xlsx', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 100);
                $upload_result = $this->file_uplod($uplaod_details);
                $record = array();

                if (!empty($upload_result) && $upload_result['status'] == true) {

                    $raw_filename = $_FILES['case_received_date_change_bulk_upload']['tmp_name'];

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

                            $check_record_exits = $this->addressver_model->select(true, array('*'), array('add_com_ref' => $value[0]));

                            if (!empty($check_record_exits) && $value[0] != "") {

                                $result = $this->addressver_model->save(array('iniated_date' => date('Y-m-d', strtotime(str_replace('/', '-', $value[1])))),array('add_com_ref'=>$value[0]));

                                $data['success'] = $value[0] . " This Address Records Update Successfully";
                                   
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

    public function bulk_update_closure_date()
    {

        if ($this->input->is_ajax_request()) {

            $frm_details = $this->input->post();

          
                $file_upload_path = SITE_BASE_PATH . ADDRESS;

                if (!folder_exist($file_upload_path)) {
                    mkdir($file_upload_path, 0777);
                } else if (!is_writable($file_upload_path)) {
                    $message = 'Problem while uploading, folder permission';
                }

                $uplaod_details = array('file_upload_path' => $file_upload_path, 'file_data' => $_FILES['closure_date_change_bulk_upload'], 'file_permission' => 'csv|xls|xlsx', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 100);
                $upload_result = $this->file_uplod($uplaod_details);
                $record = array();

                if (!empty($upload_result) && $upload_result['status'] == true) {

                    $raw_filename = $_FILES['closure_date_change_bulk_upload']['tmp_name'];

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

                            $check_record_exits = $this->addressver_model->select(true, array('*'), array('add_com_ref' => $value[0]));

                            if (!empty($check_record_exits) && $value[0] != "") {

                                $result = $this->addressver_model->save_update_adds_ver_result(array('closuredate' => date('Y-m-d', strtotime(str_replace('/', '-', $value[1])))),array('addrverid'=>$check_record_exits['id']));

                                $result_ver = $this->addressver_model->save_update_result_addrverres(array('closuredate' => date('Y-m-d', strtotime(str_replace('/', '-', $value[1])))),array('addrverid'=>$check_record_exits['id']));

                                $data['success'] = $value[0] . " This Address Records Update Successfully";
                                   
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

    protected function cli_address_invite_mail($result)
    {
        $this->load->library('email');
        $this->email->address_invite_mail_sms($result);
    }
    public function change_physical_address()
    {
        if ($this->input->is_ajax_request()) {

            log_message('error', 'Change Physical Address');
            try {

                if(!empty($this->input->post('change_vendor_name')))
                {           
                    $comp_id = $this->input->post('update_id');
                    $change_vendor_name = $this->input->post('change_vendor_name');
                
                    $result = $this->addressver_model->save(array("vendor_id" => $change_vendor_name,"vendor_list_mode" => "physical visit",'vendor_assgined_on' => date(DB_DATE_FORMAT)), array("id" => $comp_id));

                    $array_data = array(
                                'vendor_id' => $change_vendor_name,
                                'case_id' => $comp_id,
                                "status" => 0,
                                "remarks" => '',
                                "created_by" => $this->user_info['id'],
                                "created_on" => date(DB_DATE_FORMAT),
                                "approval_by" => 0,
                                "modified_on" => null,
                                "modified_by" => '',
                        );

                    $result_vendor_log = $this->addressver_model->save_vendor_log($array_data);
        
                    $user_name =  $this->addressver_model->select_address_dt("user_profile",array("user_name"), array("id" => $this->user_info['id']));
                    $vendor_name =  $this->addressver_model->select_address_dt("vendors",array("vendor_name"), array("id" => $change_vendor_name));
                   // $result_address_activity_data = $this->addressver_model->initiated_date_addrver_activity_data(array('created_on' =>date(DB_DATE_FORMAT),'created_by' => $this->user_info['id'], 'comp_table_id' => $comp_id, 'activity_type' => "Mist it", 'activity_status' => "Assign", 'remarks' => $user_name[0]['user_name'].' has assigned the case to '.$vendor_name[0]['vendor_name']));
                        
    
                    if ($result && $result_vendor_log) {
                    
                        $json_array['status'] = SUCCESS_CODE;

                        $json_array['message'] = 'Successful Change physical Address';

                    } else {

                        $json_array['status'] = ERROR_CODE;

                        $json_array['message'] = 'Something went wrong,please try again';

                    }
                }
                else {

                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Please Select Vendor';

                }

            } catch (Exception $e) {
                log_message('error', 'Address::change_physical_address');
                log_message('error', $e->getMessage());
            }
        } else {
            $json_array['status'] = ERROR_CODE;

            $json_array['message'] = 'Something went wrong,please try again';
        }
        echo_json($json_array);

    }


    public function change_digital_address()
    {
        if ($this->input->is_ajax_request()) {

            log_message('error', 'Change Digital Address');
            try {

                if(!empty($this->input->post('digital_verification_case_id')))
                {           
                    $digital_verification_case_id = $this->input->post('digital_verification_case_id');
                
                    $update = $this->addressver_model->upload_vendor_assign('addrver', array('vendor_digital_id' => 25, 'vendor_digital_list_mode' => 'digital', 'vendor_digital_assgined_on' => date(DB_DATE_FORMAT)), array('id' => $digital_verification_case_id));


                    $this->cli_address_invite_mail($digital_verification_case_id);

                    $user_name =  $this->addressver_model->select_address_dt("user_profile",array("user_name"), array("id" => $this->user_info['id']));
                   
                    $result_address_activity_data = $this->addressver_model->initiated_date_addrver_activity_data(array('created_on' =>date(DB_DATE_FORMAT),'created_by' => $this->user_info['id'], 'comp_table_id' => $digital_verification_case_id, 'activity_type' => "Mist it", 'activity_status' => "Assign", 'remarks' => $user_name[0]['user_name'].' has assigned the case to Digital Verification'));
                        
    
                    if ($update) {
                    
                        $json_array['status'] = SUCCESS_CODE;

                        $json_array['message'] = 'Successful Change Digital Address';

                    } else {

                        $json_array['status'] = ERROR_CODE;

                        $json_array['message'] = 'Something went wrong,please try again';

                    }
                }
                else {

                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Please Select Vendor';

                }

            } catch (Exception $e) {
                log_message('error', 'Address::change_digital_address');
                log_message('error', $e->getMessage());
            }
        } else {
            $json_array['status'] = ERROR_CODE;

            $json_array['message'] = 'Something went wrong,please try again';
        }
        echo_json($json_array);

    }


    public function save_address_generate_verification()
    {

        if ($this->input->is_ajax_request()) { 

            $frm_details = $this->input->post();
            
       
            $file_upload_path = SITE_BASE_PATH.ADDRESS.$frm_details['clientid'];
            if(!folder_exist($file_upload_path)) {
                mkdir($file_upload_path,0777,true);
            }else if(!is_writable($file_upload_path)) {
            array_push($error_msgs,'Problem while uploading');
            }
                    
            $file_name =   $frm_details['add_com_ref']."_".date(UPLOAD_FILE_DATE_FORMAT) . '.png';

            $savefile = file_put_contents($file_upload_path."/".$file_name, base64_decode(explode(",", $_POST['img'])[1]));

            $this->common_model->save('addrver_files', ['file_name' => $file_name, 'real_filename' => $file_name,'status' => 1,'type' => 1,'addrver_id' => $frm_details['update_id']]);
            
            
            $json_array['status'] = SUCCESS_CODE;

            $json_array['message'] = 'Successful Submitted Record';

        } else {
            $json_array['status'] = ERROR_CODE;

            $json_array['message'] = 'Something went wrong,please try again';
        }
        echo_json($json_array);

       
    }


    public function update_distance() {
        $json_array['status'] = ERROR_CODE;
        $json_array['message'] = 'Database error, please try again';

        if($this->input->is_ajax_request())
        {
            $this->form_validation->set_rules('distance', 'distance', 'required');
            $this->form_validation->set_rules('add_update_id', 'ID', 'required');

            if ($this->form_validation->run() == FALSE)
            {
                $json_array['status'] = ERROR_CODE;
                $json_array['message'] = validation_errors('','');
            }
            else
            {
                $frm_details = $this->input->post();

                $update = $this->common_model->save('address_details',array('place_distance' => $frm_details['distance']),array('address_id' => $frm_details['add_update_id'],'status' => 1));

                $file_upload_path = SITE_BASE_PATH . ADDRESS .'address_verification';

                    if (!folder_exist($file_upload_path)) {
                        mkdir($file_upload_path, 0777);
                    } else if (!is_writable($file_upload_path)) {
                        array_push($error_msgs, 'Problem while uploading');
                    }

                
                $file_upload_path = SITE_BASE_PATH . ADDRESS .'address_verification/capture_'.$frm_details['add_update_id'].'_map.png';
                $photo_upload_path = SITE_BASE_PATH . ADDRESS .'address_verification/pic_'.$frm_details['add_update_id'].'_thumbnail.png';

                $map_url = SITE_URL.'home/capture/'.$frm_details['add_update_id'];

                $photo_map_url = SITE_URL.'home/merge_image/'.$frm_details['add_update_id'];

               //$this->capture_screenshot($map_url,$file_upload_path);
                $this->ScreenShotUrl($map_url,$file_upload_path);
                $this->capture_screenshot_photo($photo_map_url,$photo_upload_path);


                if($update) {
                    $json_array['status'] = SUCCESS_CODE;
                    $json_array['message'] = 'Record Inserted Successfully';
                } else {
                    $json_array['status'] = ERROR_CODE;
                    $json_array['message'] = 'Unable to store in Database,Please try again';
                }
            }
        } 
        echo_json($json_array);
    }
    

    public function update_address() {
        $json_array['status'] = ERROR_CODE;
        $json_array['message'] = 'Database error, please try again';

        if($this->input->is_ajax_request())
        {
            $this->form_validation->set_rules('update_address_id', 'ID', 'required');
            $this->form_validation->set_rules('update_address', 'ID', 'required');

            if ($this->form_validation->run() == FALSE)
            {
                $json_array['status'] = ERROR_CODE;
                $json_array['message'] = validation_errors('','');
            }
            else
            {
                $frm_details = $this->input->post();
                $update = $this->common_model->save('address_details',array('address_edit' => $frm_details['update_address']),array('id' => $frm_details['update_address_id']));


                if($update) {
                    $json_array['status'] = SUCCESS_CODE;
                    $json_array['message'] = 'Record Update Successfully';
                } else {
                    $json_array['status'] = ERROR_CODE;
                    $json_array['message'] = 'Unable to store in Database,Please try again';
                }
            }
        } 
        echo_json($json_array);
    }
  

    public function update_candidate_verification_result() {
        $json_array['status'] = ERROR_CODE;
        $json_array['message'] = 'Database error, please try again';

        if($this->input->is_ajax_request())
        {
            $this->form_validation->set_rules('address_details_verification_id', 'ID', 'required');

            if ($this->form_validation->run() == FALSE)
            {
                $json_array['status'] = ERROR_CODE;
                $json_array['message'] = validation_errors('','');
            }
            else
            {
                $frm_details = $this->input->post();
                $update = $this->common_model->save('address_details',array('nature_of_residence' => $frm_details['stay_type'],'period_stay' => $frm_details['stay_from'],'period_to' => $frm_details['stay_to'],'verifier_name' => $frm_details['verified_by_digital'],'relation_verifier_name' => $frm_details['relation_by_verified'],'candidate_remarks'  => $frm_details['candidate_remarks']),array('id' => $frm_details['address_details_verification_id']));


                if($update) {
                    $json_array['status'] = SUCCESS_CODE;
                    $json_array['message'] = 'Record Update Successfully';
                } else {
                    $json_array['status'] = ERROR_CODE;
                    $json_array['message'] = 'Unable to store in Database,Please try again';
                }
            }
        } 
        echo_json($json_array);
    }


    public function capture_screenshot_photo($map_url,$file_upload_path)
    {
        $url = 'https://PhantomJsCloud.com/api/browser/v2/ak-j3m89-hrn04-9kamt-1n76n-q60zv/';
        $payload = file_get_contents ( SITE_BASE_PATH.'request.json' );
        $url_update = json_decode($payload,true);
        $url_update['pages'][0]['url'] = $map_url;
        $url_update = json_encode($url_update);
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/json",
                'method'  => 'POST',
                'content' => $url_update
            )
        );
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        if ($result === FALSE) { /* Handle error */ }
        $obj = json_decode( $result );
        $base64Enc = $obj->{"content"}->{"data"};
        $this->Phan_base64_to_jpeg( $base64Enc, $file_upload_path);
    }

    public function capture_screenshot($map_url,$file_upload_path)
    {

        $url = 'http://PhantomJScloud.com/api/browser/v2/a-demo-key-with-low-quota-per-ip-address/';
        $payload = file_get_contents ( SITE_BASE_PATH.'request.json' );
        $url_update = json_decode($payload,true);
        $url_update['pages'][0]['url'] = $map_url;
        $url_update = json_encode($url_update);
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/json",
                'method'  => 'POST',
                'content' => $url_update
            )
        );

        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        if ($result === FALSE) { /* Handle error */ }
        $obj = json_decode( $result );
        $base64Enc = $obj->{"content"}->{"data"};
        $this->Phan_base64_to_jpeg( $base64Enc, $file_upload_path);
    }

    public function ScreenShotUrl($url,$file_upload_path){

        $link = $url;

        // Google API key 
        $googleApiKey = 'AIzaSyA6Z3FJPkKdFJYSMsfT7OGuwHIn_BylwLw'; 
        $adress="https://pagespeedonline.googleapis.com/pagespeedonline/v5/runPagespeed?url=$link&category=CATEGORY_UNSPECIFIED&strategy=DESKTOP&key=$googleApiKey";

        $curl_init = curl_init($adress);
        curl_setopt($curl_init,CURLOPT_RETURNTRANSFER,true);
        
        $response = curl_exec($curl_init);
        curl_close($curl_init);
        
        $googledata = json_decode($response,true);
       
        $screenshot_data = $googledata["lighthouseResult"]["audits"]["full-page-screenshot"]["details"]["screenshot"]['data'];

        list($type, $screenshot_data) = explode(';', $screenshot_data); 
        list(, $screenshot_data)      = explode(',', $screenshot_data);

       /* $googlePagespeedData = file_get_contents("https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url=$link&screenshot=true&key=$googleApiKey");

        $googlePagespeedData = json_decode($googlePagespeedData, true);

        $screenshot = $googlePagespeedData['lighthouseResult']['audits']['final-screenshot']['details']['data']; 

        // Save screenshot as an image 
        $screenshot_data = $screenshot; 
        list($type, $screenshot_data) = explode(';', $screenshot_data); 
        list(, $screenshot_data)      = explode(',', $screenshot_data); */
               
        //file_put_contents($file_upload_path,$screenshot_data);
        $this->Phan_base64_to_jpeg($screenshot_data,$file_upload_path);

    }
   

    protected function Phan_base64_to_jpeg( $base64_string, $output_file ) {
        $ifp = fopen( $output_file, "wb" ); 
        fwrite( $ifp, base64_decode( $base64_string) ); 
        fclose( $ifp ); 
        return( $output_file ); 
    }

    protected function pdf_page_count($file_path)
    {   
        try {
            $count = 0;

            if($file_path != "") {
                $cmd = sprintf("identify %s", $file_path);
      
                exec($cmd,$output);  
                $count = count($output);
            }
        } catch (Exception $e) {
            log_message('error', 'Address::pdf_page_count');
            log_message('error', $e->getMessage());
        }
        return $count;
        // for windows
        // $pagecount = 0;
        // if($file_path != "") {
        //     $cmd = "C:\\xpdf-tools-win-4.02\\bin64\\pdfinfo.exe";  // Windows
        //     exec("$cmd \"$file_path\"", $output);
        //     foreach($output as $op)
        //     {
        //         if(preg_match("/Pages:\s*(\d+)/i", $op, $matches) === 1)
        //         {
        //             $pagecount = intval($matches[1]);
        //             break;
        //         }
        //     }
        // }
        // return $pagecount;
    }

    protected function conver_pdf_to_jpg($source,$distination)
    {
        try {
            $pdf = $source;
            $info = pathinfo($pdf);
            $file_name =  $distination.basename($pdf,'.'.$info['extension']);
            exec("convert -density 300 $pdf -quality 100 $file_name.png > /dev/null &");
            return true;
        } catch (Exception $e) {
            log_message('error', 'Address::conver_pdf_to_jpg');
            log_message('error', $e->getMessage());
        }
        // for windows
        // $cmd = "C:\\xpdf-tools-win-4.02\\bin64\\pdftopng.exe";  // Windows
        // exec("$cmd $source $distination", $output);
        // return true;
    }


   

    public function report_address_verification($rid = false) {

        if($rid != "") {
            
            $id = decrypt($rid);
            
            $address = $this->addressver_model->get_report_details(array('addrver.id' => $id,'address_details.status' =>1));
            if(!empty($address))
            {
                $data['address_info'] = $address[0];

                $this->load->library('final_report');

                define('DIGITAL_CLIENT_ID',$address[0]['clientid']);

                $this->final_report->generate_pdf($data);

                $client_id =  $this->addressver_model->select_address_dt('addrver',array('clientid'),array('addrver.id'=> $id));

                $update_files = $this->addressver_model->update_digital_upload_file($id);


                $jpg = '.png';
                $path = SITE_BASE_PATH . ADDRESS . 'address_verification/';
                $file_name = $id.'_Report'.'.pdf';
                $source_file = $path.$file_name;
                $distination_path = SITE_BASE_PATH . ADDRESS . $client_id[0]['clientid'].'/';

                $pagecount = $this->pdf_page_count($source_file,$distination_path);
                $this->conver_pdf_to_jpg($source_file,$distination_path);

                if($pagecount == 1) {
                    $file = pathinfo($file_name);
                    $file2 = pathinfo($file_name);
                                $file_array[] =  array(
                                                    'real_filename'  => $file['filename'].$jpg,
                                                    'file_name'      => $file2['filename'].$jpg,
                                                    'type'           => 1,
                                                    'addrver_id' => $id
                                                );
                    } else  {
                                for ($i=0 ; $i < $pagecount; $i++) { 
                                    $file = pathinfo($file_name);
                                    $file2 = pathinfo($file_name);
                                    $file_array[] = array(
                                        'real_filename'=> $file['filename']."-".$i.$jpg,
                                        'file_name'   => $file2['filename']."-".$i.$jpg,
                                        'type' => 1,
                                        'addrver_id' => $id
                                    );
                                }
                            }
                if (!empty($file_array)) {
                    $this->common_model->common_insert_batch('addrver_files', $file_array);
                }
              
              /*  if(file_exists(SITE_BASE_PATH . ADDRESS . "address_verification/".$id."_Report.pdf")){
                      unlink(SITE_BASE_PATH . ADDRESS . "address_verification/".$id."_Report.pdf");
                } */
               

                echo "<script>window.close();</script>";
            }
            else
            {
                show_404();
            }
        } else {
            redirect('admin/address');
        }

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
  
                $details = $this->addressver_model->select(TRUE,array('*'),array('id' => $id));

                if(!empty($details)) {

                    $update = $this->addressver_model->user_sms_count(array('id' => $id));

                    if(!empty($details) && $update) {
                        $this->cli_sms_send_again_digital($details);

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

                $details = $this->addressver_model->select(true, array(),array('id' => $id));
                
                if(!empty($details)) {
                

                        $update = $this->addressver_model->user_email_count(array('id' => $id));
                           
                        if(!empty($details) && $update) {

                            $this->cli_email_send_again_digital($details);

                            $json_array['status'] = SUCCESS_CODE;
                            $json_array['message'] = 'Email Sent';
                        }
                        else{
                           
                            $json_array['status'] = ERROR_CODE;
                            $json_array['message'] = 'Email Not Send';
                        }
                            
                        
                }
                
            }
        }
        echo_json($json_array);
    }

    public function cli_email_send_again_digital($details)
    {

        if (!empty($details)) {
            try {

                $this->load->model('common_model');
                $this->load->library('email');
         

                $details_arry = $this->common_model->select_address_data($details['id']);

               foreach ($details_arry as $key => $detail) {

                    $login_url = SITE_URL.'av/'.base64_encode($detail['id']);

            // email
                    $detail['login_url'] = $login_url;
                    if($detail['cands_email_id'])
                    {
                        $subject =  'Digital address verification for '.ucwords($detail['clientname']).' – '.ucwords($detail['CandidateName']);

              
                        $view_data['email_info'] = $detail;

                        $message = $this->load->view('email_tem/invite_mail', $view_data, TRUE);

                        $this->email->from(VERIFICATIONEMAIL);
                        $this->email->to($detail['cands_email_id']);
                        $this->email->cc(VENDOREMAIL.','.$detail['client_manager_email_id']);
                        $this->email->subject($subject);
                        $this->email->message($message);
                
                        if($this->email->send()){

                            $this->common_model->save('address_digital_mail_sms',array('address_id' => $detail['id'],' sms_mail_send_date_time' => date(DB_DATE_FORMAT),'type' => '1'));
                        }
                    }
           
               }
              
            } catch (Exception $e) {
                log_message('error', 'Address email trigger');
                log_message('error', $e->getMessage());
            }
        }
    }

    public function cli_sms_send_again_digital($details)
    {

        if (!empty($details)) {
            try {
                
                 $this->load->model('common_model');

                $details_arry = $this->common_model->select_address_data($details['id']);
   
                foreach ($details_arry as $key => $detail) {
 
                    $login_url = SITE_URL.'av/'.base64_encode($detail['id']);


                    $detail['login_url'] = $login_url;

           
                    if($detail['CandidatesContactNumber'])
                    {
                        $sms_content = "Greetings from MIST IT Services. Please click on the link below and fill for address verification ".$login_url;

                        $file_responce = $this->send_sms($detail['CandidatesContactNumber'], $sms_content);
                    }
                    
                    if(!empty($file_responce)) {
                        
                        $this->CI->common_model->save('address_digital_mail_sms',array('address_id' => $detail['id'],' sms_mail_send_date_time' => date(DB_DATE_FORMAT),'type' => '2'));
                        
                    }
                }
            } catch (Exception $e) {
                log_message('error', 'cli_sms_send_again_digital');
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


    public function check_closure_status_digital($address_id)
    {
       
       
        $check_address_status = $this->addressver_model->check_address_details_status( $address_id);

        if(!empty($check_address_status))
        {
            if($check_address_status[0]['verification_status'] == "3" )
            {
                 $status = "success";
            }
            else
            {
               $status = "error";  
            }
           
        }
        else
        {
            $status = "success";
        }
        
        echo $status ;
    }
    
    public function check_closure_status_physical($address_id)
    {
       
        $details = $this->addressver_model->check_transaction_final_status($address_id);

        if(!empty($details))
        {
            if($details[0]['status'] == "5")
            {
                
                $status = "success";

            }
            else
            {
                $status = "error"; 
            }

        }
        else
        {
            $status = "success";
        }

        echo $status ;
    }



    public function vendor_insuff_hide_show()
    {
        if ($this->input->is_ajax_request()) {

            if($this->permission['access_address_aq_allow'] == 1)
            {
 
                log_message('error', 'Show Hide Vendor Insuff');
                try {
               
                    $frm_details =   $this->input->post();
        
                              
                        $digital_verification_case_id = $this->input->post('digital_verification_case_id');
                    
                        $update = $this->addressver_model->upload_vendor_assign('view_vendor_master_log', array('digital_insuff' =>  $frm_details['stat']), array('id' =>  $frm_details['id']));
        
                        if ($update) {
                        
                            $json_array['status'] = SUCCESS_CODE;

                            $json_array['message'] = 'Successful Update Record';

                        } else {

                            $json_array['status'] = ERROR_CODE;

                            $json_array['message'] = 'Something went wrong,please try again';

                        }
                   
                } catch (Exception $e) {
                    log_message('error', 'Address::Show Hide Vendor Insuff');
                    log_message('error', $e->getMessage());
                }
            }else {

                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = 'You don’t have a permission to access this action';
            }


        } else {
            $json_array['status'] = ERROR_CODE;

            $json_array['message'] = 'Something went wrong,please try again';
        }
        echo_json($json_array);

    }
    public function address_result_attachment($address_id)
    {
         $data['address_id'] = $address_id;
         $select_client_id  = $this->addressver_model->select(true,array('clientid'),array('id'=>$address_id));
         $data['clients_id'] = $select_client_id['clientid'];
         echo $this->load->view('admin/address_attachment', $data, true);
    }
    public function address_vendor_attachment($vendor_log_id)
    {
         $data['vendor_log_id'] = $vendor_log_id;
        
         echo $this->load->view('admin/address_vendor_attachment', $data, true);
    }
    public function add_verificarion_ver_result_attachment()
    {
        if ($this->input->is_ajax_request()) {

            $this->form_validation->set_rules('address_id', 'Id', 'required');
            $this->form_validation->set_rules('clientid', 'Client Name', 'required');

            if ($this->form_validation->run() == false) {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');
            } else {

                log_message('error', 'Add verification reverification result Attachment');
                try {

                    $frm_details = $this->input->post();

                    $file_upload_path = SITE_BASE_PATH . ADDRESS . $frm_details['clientid'];
                    $config_array = array(
                        'file_upload_path' => $file_upload_path, 
                        'file_permission' => 'jpeg|jpg|png|pdf', 
                        'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 2000, 
                        'file_id' => $frm_details['address_id'],
                        'component_name' => 'addrver_id'
                    );

                    if ($_FILES['attchments_ver']['name'][0] != '') {
                        $config_array['files_count'] = count($_FILES['attchments_ver']['name']);
                        $config_array['file_data']  = $_FILES['attchments_ver'];
                        $config_array['type']       = 1;
                        $retunr_de = $this->file_upload_library->file_upload_multiple_address($config_array, true);
                        
                        if (!empty($retunr_de['success'])) {
                            $this->common_model->common_insert_batch('addrver_files', $retunr_de['success']);
                        }
                    }
                    
                    $attachment = array(); 
                    if (!empty($retunr_de['success'])) {
                      
                        foreach ($retunr_de['success'] as $key => $value) {
                            
                           array_push($attachment,$value['file_name']);

                            if(file_exists($file_upload_path.'/'.$value['real_filename']))
                            {
                                $info = pathinfo($file_upload_path.'/'.$value['real_filename']);
     
                            } 
                        }
                       
                    }
                   
                    if ($retunr_de['success']) {
                       
                         
                        $json_array['status'] = SUCCESS_CODE;

                        $json_array['message'] = 'Uploaded Successfully';

                        $json_array['attachments'] = implode(',', $attachment);

                      
                    } else {
                        $json_array['status'] = ERROR_CODE;

                        $json_array['message'] = 'Something went wrong,please try again';
                    }

                } catch (Exception $e) {
                    log_message('error', 'Address::add_verificarion_ver_result_attachment');
                    log_message('error', $e->getMessage());
                }
            }
            echo_json($json_array);
        } 
    }

    function compress_image($source, $destination, $quality) {
      
       
        $info = pathinfo($source);
     
        if ($info['extension'] == 'jpeg') 
        {
            $image = imagecreatefromjpeg($source);
            imagejpeg($image, $destination, $quality);
        }
        elseif ($info['extension'] == 'jpg') 
        {
            $image = imagecreatefromjpeg($source);
            imagejpeg($image, $destination, $quality);

        }
        elseif ($info['extension'] == 'gif') 
        {
            $image = imagecreatefromgif($source);
            imagejpeg($image, $destination, $quality);

        }

        elseif ($info['extension'] == 'png') 
        {
            $image = imagecreatefrompng($source);
            $bck   = imagecolorallocate( $image , 0, 0, 0 );
            imagecolortransparent( $image, $bck );
            imagealphablending( $image, false );
            imagesavealpha( $image, true );
            imagepng( $image, $destination);
        
        }

        return $destination;
    }

    function compress_image_vendor($source, $destination, $quality) {
      
        $info = pathinfo($source);
     
        if ($info['extension'] == 'jpeg') 
        {
            $image = imagecreatefromjpeg($source);
            imagejpeg($image, $destination, $quality);
        }
        elseif ($info['extension'] == 'jpg') 
        {
            $image = imagecreatefromjpeg($source);
            imagejpeg($image, $destination, $quality);

        }
        elseif ($info['extension'] == 'gif') 
        {
            $image = imagecreatefromgif($source);
            imagejpeg($image, $destination, $quality);

        }

        elseif ($info['extension'] == 'png') 
        {
            $image = imagecreatefrompng($source);
            $bck   = imagecolorallocate( $image , 0, 0, 0 );
            imagecolortransparent( $image, $bck );
            imagealphablending( $image, false );
            imagesavealpha( $image, true );
            imagepng( $image, $destination);
        
        }

        return $destination;
    }


    
}
