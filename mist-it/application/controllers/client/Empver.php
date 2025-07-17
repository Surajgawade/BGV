<?php defined('BASEPATH') or exit('No direct script access allowed');

class Empver extends MY_Client_Cotroller
{

    public function __construct()
    {
        parent::__construct();

        if (!$this->is_client_logged_in()) {
            redirect('client/login');
            exit();
        }

        $this->perm_array = array('direct_access' => true);
        $this->load->model(array('employment_model'));

        $this->employment_type = array('' => 'Select', 'full time' => 'Full time', 'contractual' => 'Contractual', 'part time' => 'Part time');
    }

    public function add($candsid = false)
    {
        if ($this->input->is_ajax_request()) {

            $data['get_cands_details'] = $this->candidate_entity_pack_details($candsid);
            $data['states'] = $this->get_states();

            $data['company_list'] = $this->get_company_list();

            $data['mode_of_verification'] = $this->employment_model->get_mode_of_verification(array('entity' => $data['get_cands_details']['entity_id'], 'package' => $data['get_cands_details']['package_id'], 'tbl_clients_id' => $data['get_cands_details']['clientid']));

            echo $this->load->view('client/employment_add', $data, true);
        } else {
            echo "<h3>Something went wrong, please try again</h3>";
        }
    }

    protected function emp_com_ref($insert_id)
    {
        $name = COMPONENT_REF_NO['EMPLOYMENT'];

        $employmentnumber = $name . $insert_id;

        $field_array = array('emp_com_ref' => $employmentnumber);

        $update_auto_increament_id = $this->employment_model->update_auto_increament_value($field_array, array('id' => $insert_id));

        return $employmentnumber;
    }

    public function save_employment()
    {
        if ($this->input->is_ajax_request()) {

            $frm_details = $this->input->post();

            $this->form_validation->set_rules('clientid', 'Client', 'required');

            $this->form_validation->set_rules('candsid', 'Candidate', 'required');


            if ($this->form_validation->run() == false ) {

                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');

            } else {
                  
                
                $is_exit = $this->employment_model->check_cin_exist($frm_details['nameofthecompany']);

                $is_company_exit = $this->employment_model->check_company_exist(str_replace("-", ' ', $frm_details['selected_company_name']));

                if (!empty($is_exit)) {
                    $nameofthecompany = $is_exit[0]['id'];
                } else if (!empty($is_company_exit)) {
                    $nameofthecompany = $is_company_exit[0]['id'];
                } else {
                    $nameofthecompany = $this->employment_model->save_company_details(array('cin_number' => $frm_details['nameofthecompany'], 'coname' => str_replace('-', ' ', $frm_details['selected_company_name'])));
                }  

                $employment_id = $this->employment_model->check_employment_exists_in_candidate(array('nameofthecompany'  => $nameofthecompany,'empfrom' => $frm_details['empfrom'],'empto' => $frm_details['empto'],'candsid' => $frm_details['candsid']));

                if(empty($employment_id))
                {  
            
                    $tat_day = $this->get_client_entity_package_tat_day(array('tbl_clients_id' => $frm_details['clientid'], 'entity' => $frm_details['entity_id'], 'package' => $frm_details['package_id']));

                    $get_holiday1 = $this->get_holiday();

                    $get_holiday = array_map('current', $get_holiday1);

                    $closed_date = getWorkingDays(convert_display_to_db_date($frm_details['iniated_date']), $get_holiday, $tat_day[0]['tat_empver']);

                    $has_case_id = $this->employment_model->get_reporting_manager_id_client($frm_details['clientid']);
                    

                        $field_array = array('clientid' => $frm_details['clientid'],
                            'candsid' => $frm_details['candsid'],
                            'emp_com_ref' => '',
                            'empid' => $frm_details['empid'],
                            'nameofthecompany' => $nameofthecompany,
                            'deputed_company' => $frm_details['deputed_company'],
                            'employment_type' => $frm_details['employment_type'],
                            'locationaddr' => $frm_details['locationaddr'],
                            'citylocality' => $frm_details['citylocality'],
                            'mode_of_veri' => $frm_details['mod_of_veri'],
                            'pincode' => $frm_details['pincode'],
                            'state' => $frm_details['state'],
                            'compant_contact' => $frm_details['compant_contact'],
                            'compant_contact_name' => $frm_details['compant_contact_name'],
                            'compant_contact_designation' => $frm_details['compant_contact_designation'],
                            'compant_contact_email' => $frm_details['compant_contact_email'],
                            'empfrom' => $frm_details['empfrom'],
                            'empto' => $frm_details['empto'],
                            'designation' => $frm_details['designation'],
                            'remuneration' => $frm_details['remuneration'],
                            'iniated_date' => convert_display_to_db_date($frm_details['iniated_date']),
                            "reasonforleaving" => $frm_details['reasonforleaving'],
                            "emp_re_open_date" => '',
                            'r_manager_name' => $frm_details['r_manager_name'],
                            'r_manager_no' => $frm_details['r_manager_no'],
                            'r_manager_designation' => $frm_details['r_manager_designation'],
                            'r_manager_email' => $frm_details['r_manager_email'],
                            'created_on' => date(DB_DATE_FORMAT),
                            'modified_on' => date(DB_DATE_FORMAT),
                            'has_case_id' => $has_case_id[0]['clientmgr'],
                            "due_date" => $closed_date,
                            'is_bulk_uploaded' => 0,
                            'created_by' => 0,
                            "tat_status" => 'IN TAT',
                            'fill_by' => 1,
                        );

                     $lists = $this->employment_model->select_required_field(array('company_database.id' => $frm_details['nameofthecompany']));

                            $field_array['reject_status'] = 1;
                            if (!empty($lists[0])) {
                                if ($lists[0]['previous_emp_code'] == 1) {
                                    if ($frm_details['empid'] == "") {
                                        $field_array['reject_status'] = 2;
                                    }
                                }
                                if ($lists[0]['branch_location'] == 1) {
                                    if ($frm_details['citylocality'] == "") {
                                        $field_array['reject_status'] = 2;
                                    }
                                }
                                if ($lists[0]['experience_letter'] == 1) {
                                    if ($_FILES['attchments_reliving']['name'][0] == "") {
                                        $field_array['reject_status'] = 2;
                                    }
                                }
                                if ($lists[0]['loa'] == 1) {
                                    if ($_FILES['attchments_loa']['name'][0] == "") {
                                        $field_array['reject_status'] = 2;
                                    }
                                }
                            }


                    $result = $this->employment_model->save(array_map('strtolower', $field_array));

                    $emp_com_ref = $this->emp_com_ref($result);

                    

                    $total_rows = count(array_filter($frm_details['supervisor_name']));

                        $x = 0;
                        $supervisor_array = array();
                        while ($x < $total_rows) {
                            if ($frm_details['supervisor_name']) {
                                array_push($supervisor_array,
                                    array(
                                        'supervisor_name' => $frm_details['supervisor_name'][$x],
                                        'supervisor_designation' => $frm_details['supervisor_designation'][$x],
                                        'supervisor_contact_details' => $frm_details['supervisor_contact_details'][$x],
                                        'supervisor_email_id' => $frm_details['supervisor_email_id'][$x],
                                        'status' => 1,
                                        'created_on' => date(DB_DATE_FORMAT),
                                        'empver_id' => $result,
                                    ));
                            }
                            $x++;
                        }

                        if (!empty($supervisor_array)) {
                            $this->employment_model->empver_supervisor_details($supervisor_array);
                        }

                        $error_msgs = array();
                        $file_upload_path = SITE_BASE_PATH . EMPLOYMENT . $frm_details['clientid'];
                        if (!folder_exist($file_upload_path)) {
                            mkdir($file_upload_path, 0777,true);
                        } else if (!is_writable($file_upload_path)) {
                            array_push($error_msgs, 'Problem while uploading');
                        }

                        $config_array = array('file_upload_path' => $file_upload_path, 'file_permission' => 'jpeg|jpg|png|pdf|docx|xls|xlsx', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 1000, 'file_id' => $result, 'component_name' => 'empver_id');

                        if (empty($error_msgs)) {
                            if ($_FILES['attchments']['name'][0] != '') {
                                $config_array['files_count'] = count($_FILES['attchments']['name']);
                                $config_array['file_data'] = $_FILES['attchments'];
                                $config_array['type'] = 0;
                                $retunr_de = $this->file_upload_multiple($config_array);
                                if (!empty($retunr_de)) {
                                    $this->common_model->common_insert_batch('empverres_files', $retunr_de['success']);
                                }
                            }

                            if ($_FILES['attchments_reliving']['name'][0] != '') {
                                $config_array['files_count'] = count($_FILES['attchments_reliving']['name']);
                                $config_array['file_data'] = $_FILES['attchments_reliving'];
                                $config_array['type'] = 3;
                                $retunr_de = $this->file_upload_multiple($config_array);
                                if (!empty($retunr_de)) {
                                    $this->common_model->common_insert_batch('empverres_files', $retunr_de['success']);
                                }
                            }

                            if ($_FILES['attchments_loa']['name'][0] != '') {
                                $config_array['files_count'] = count($_FILES['attchments_loa']['name']);
                                $config_array['file_data'] = $_FILES['attchments_loa'];
                                $config_array['type'] = 4;
                                $retunr_de = $this->file_upload_multiple($config_array);
                                if (!empty($retunr_de)) {
                                    $this->common_model->common_insert_batch('empverres_files', $retunr_de['success']);
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
                                'remarks' => 'New Check Added by  Client ' . $frm_details['clientname'],
                                'created_on' => date(DB_DATE_FORMAT),
                                'is_auto_filled' => 0,

                            );
                       

                        if ($result) {
                            auto_update_tat_status($frm_details['candsid']);

                            auto_update_overall_status($frm_details['candsid']);

                            if ($result) {

                                $json_array['status'] = SUCCESS_CODE;

                                $json_array['message'] = 'Employment Record Successfully Inserted';


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

                
                } else {
                        $json_array['status'] = ERROR_CODE;

                        $json_array['message'] = 'Already Exists Company, From Date and To Date';
                }    
               
            }
             echo_json($json_array);
        }
    }

    public function load_supervisor_details($sts, $counter)
    {
        if ($this->input->is_ajax_request()) {

            $empver_id = $this->input->post('details_fields');

            $data['count'] = $counter;
            $data['disabled'] = ($sts == 'dis') ? 'disabled' : '';
            if ($empver_id) {

                $result = $this->employment_model->supervison_details(array('empver_id' => $empver_id));

                $data['total_count'] = count($result);

                $data['superv_details'] = $result;
            }

            echo $this->load->view('admin/supervisor_details_view', $data, true);
        } else {
            echo "<h2>Something went wrong, please try again!</h2>";
        }
    }

    public function get_company_details()
    {

        $get_company_name = $_REQUEST['q']['term'];

        if ($this->input->is_ajax_request()) {
            $api_details = $this->employment_model->get_company_name($get_company_name);

            if (empty($api_details[0])) {

                $api_details = $this->fetch_company_details($get_company_name);
            }
        }
        echo_json($api_details);
    }

    public function fetch_company_details($requested_term)
    {

        $company_name = fetch_company_details($requested_term);

        if (!empty($company_name)) {

            $json_array = $company_name;
        } else {
            $json_array = array(array("id" => '100', "company_name" => 'Other'));
        }

        return $json_array;

    }

    public function get_required_fields()
    {
        if ($this->input->is_ajax_request()) {

            $company_name = $this->input->post('company_name');

            $lists = $this->employment_model->select_required_field(array('company_database.id' => $company_name));

            if (!empty($lists)) {
                echo_json($lists[0]);
            } else {
                echo_json('false');
            }
        }
    }

    public function addAddCompanyModel()
    {
        log_message('error', 'Add Company Model'); 
        try {
            if ($this->input->is_ajax_request()) {
                $data['states'] = $this->get_states();
                echo $this->load->view('admin/addAddCompanyModel', $data, true);
            } else {
                echo "<p>We're sorry, but you do not have access to this page.</p>";
            }
        } catch (Exception $e) {
            log_message('error', 'Error on Company_database::addAddCompanyModel');
            log_message('error', $e->getMessage());
        }
    }
    public function save_company()
    {
        if ($this->input->is_ajax_request()) {

            log_message('error', 'Campany Save'); 
            try {
                $this->load->model('company_database_model');

                $coname = $this->input->post('coname');

                $this->form_validation->set_rules('coname', 'Company Name', 'required|is_unique[company_database.coname]');

                $this->form_validation->set_rules('address', 'Address', 'required');

                $this->form_validation->set_rules('city', 'City', 'required|alpha_numeric_spaces');

                $this->form_validation->set_rules('pincode', 'Pincode', 'required|numeric|min_length[6]|max_length[6]');

                $this->form_validation->set_rules('state', 'State', 'required');

                if ($this->form_validation->run() == false) {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = validation_errors('', '');
            
                    if($json_array['message'] == "The Company Name field must contain a unique value.\n")
                    {
                      
                      $insert_id = $this->company_database_model->save_company(array('dropdown_status'=> 1),array('coname'=> $coname)); 
                       $json_array['status'] = SUCCESS_CODE;
                       $json_array['message'] = 'Added Successfully';
                       $json_array['insert_id'] = $insert_id;
                       $json_array['coname'] = $coname;  
                    }
                } else {
                    $frm_details = $this->input->post();

                    $field_array = array("coname" => $frm_details["coname"],
                        "address" => $frm_details["address"],
                        "city" => $frm_details["city"],
                        "pincode" => $frm_details["pincode"],
                        "co_email_id" => $frm_details["co_email_id"],
                        "cc_email_id" => $frm_details["cc_email_id"],
                        "state" => $frm_details["state"],
                        'created_on' => date(DB_DATE_FORMAT),
                        'modified_on' => date(DB_DATE_FORMAT),
                        'status' => STATUS_ACTIVE,
                    );

                    $field_array = array_map('strtolower', $field_array);
                    $insert_id = $this->company_database_model->save_company($field_array);
                    if ($insert_id) {
                        $json_array['status'] = SUCCESS_CODE;

                        $json_array['message'] = 'Added Successfully';

                        $json_array['insert_id'] = $insert_id;
                        $json_array['coname'] = $coname;

                        $json_array['redirect'] = ADMIN_SITE_URL . 'company_database';
                    } else {
                        $json_array['insert_id'] = 0;

                        $json_array['status'] = ERROR_CODE;

                        $json_array['message'] = 'Something went wrong,please try again';
                    }
                }
                echo_json($json_array);

            } catch (Exception $e) {
            log_message('error', 'Error on Company_database::save_company');
            log_message('error', $e->getMessage());
            }     
        }
    }

}
?>
