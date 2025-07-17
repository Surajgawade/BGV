<?php defined('BASEPATH') or exit('No direct script access allowed');

class Company_database extends MY_Controller
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

        $this->perm_array = array('page_id' => 23, 'direct_access' => true);
        $this->load->model('company_database_model');
    }

    public function index()
    {
        log_message('error', 'Campany Database');
        try {

            $data['header_title'] = "Suspicious Company Database";

            $this->load->view('admin/header', $data);

            $this->load->view('admin/company_db_view');

            $this->load->view('admin/footer');

        } catch (Exception $e) {
            log_message('error', 'Error on Company_database::index');
            log_message('error', $e->getMessage());
        }    
    
    }

    public function company_view_datatable()
    {
        if ($this->input->is_ajax_request()) {

            log_message('error', 'Get Campany Data Load'); 
            try {

                    $fetch_rows = $data_arry = $columns = array();

                    $params = $_REQUEST;

                    $columns = array('id', 'coname', 'dropdown_status', 'co_email_id');

                    $fetch_rows = $this->company_database_model->get_all_company_for_datatable($params, $columns);

                    $totalRecords = count($this->company_database_model->get_all_company_for_datatable_count($params, $columns));

                    $x = 0;
                    foreach ($fetch_rows as $fetch_row) {

                        if($fetch_row['dropdown_status'] == "1")
                        {
                           $dropdown_status  = "Active";
                        }
                        else
                        {
                           $dropdown_status  = "Inactive";
                        }
                        $requirement = array();
                        if($fetch_row['previous_emp_code'] == 1)
                        {
                           array_push($requirement,'Previous Employee Code');
                        }
                        if($fetch_row['branch_location'] == 1)
                        {
                            array_push($requirement,'Branch Location');
                        }
                        if($fetch_row['experience_letter'] == 1)
                        {
                           array_push($requirement,'Experience Letter');
                        }
                        if($fetch_row['loa'] == 1)
                        {
                            array_push($requirement,'LOA');
                        }
                        if($fetch_row['auto_initiate'] == 1)
                        {
                            array_push($requirement,'Auto Initiate');
                        }
                        if($fetch_row['follow_up'] == 1)
                        {
                            array_push($requirement,'Follow Up');
                        }
                        if($fetch_row['client_disclosure'] == 1)
                        {
                            array_push($requirement,'Client Disclosure');
                        }


                        $data_arry[$x]['id'] = $x + 1;
                        $data_arry[$x]['coname'] = ucwords($fetch_row['coname']);
                        $data_arry[$x]['requirement'] = implode(",", $requirement);
                        $data_arry[$x]['co_email_id'] = $fetch_row['co_email_id'];
                        $data_arry[$x]['dropdown_status'] = $dropdown_status;
                        $data_arry[$x]['encry_id'] = ADMIN_SITE_URL . "company_database/view_details/" . encrypt($fetch_row['id']);
                        $data_arry[$x]['edit_access'] = ADMIN_SITE_URL . "company_database/view_details/" . encrypt($fetch_row['id']);
                        $x++;
                    }

                    $json_data = array("draw" => intval($params['draw']),
                        "recordsTotal" => intval($totalRecords),
                        "recordsFiltered" => intval($totalRecords),
                        "data" => $data_arry,
                    );

                    echo_json($json_data);

            } catch (Exception $e) {
                log_message('error', 'Error on Company_database::company_view_datatable');
                log_message('error', $e->getMessage());
            }    
        }
    }

    public function add()
    {
        log_message('error', 'Campany Add Page'); 
        try {

            $data['header_title'] = "Add Company Database";

            $data['states'] = $this->get_states();

            $data['form_view'] = $this->load->view('admin/addAddCompanyModel', $data, true);

            $this->load->view('admin/header', $data);

            $this->load->view('admin/company_db_add');

            $this->load->view('admin/footer');

        } catch (Exception $e) {
            log_message('error', 'Error on Company_database::add');
            log_message('error', $e->getMessage());
        } 
    }

    public function save_company()
    {
        if ($this->input->is_ajax_request()) {

            log_message('error', 'Campany Save'); 
            try {
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
                        'created_by' => $this->user_info['id'],
                        'modified_on' => date(DB_DATE_FORMAT),
                        'modified_by' => $this->user_info['id'],
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

    public function view_details($company_id = '')
    {
        log_message('error', 'Campany Edit Page'); 
        try {
            $company_details = $this->company_database_model->get_company_details(array('company_database.id' => decrypt($company_id)));
      
            $verifiers_details = $this->company_database_model->get_hr_database_details(array('company_database_verifiers_details.company_database_id' => decrypt($company_id)));
           
            $verifiers_details_bk = $this->company_database_model->get_hr_database_details_bk($company_details[0]['coname']);

            if ($company_id && !empty($company_details)) {
                $data['header_title'] = 'Edit Company Details';

                $data['states'] = $this->get_states();

                $data['company_details'] = $company_details[0];

                $data['verifiers_details'] = $verifiers_details;

                $data['verifiers_details_bk'] = $verifiers_details_bk;

                $this->load->view('admin/header', $data);

                $this->load->view('admin/company_edit');

                $this->load->view('admin/footer');
            }
        } catch (Exception $e) {
            log_message('error', 'Error on Company_database::view_details');
            log_message('error', $e->getMessage());
        }    
    }

    public function update_company()
    {
        if ($this->input->is_ajax_request()) {

            log_message('error', 'Update Company'); 
            try {

                $this->form_validation->set_rules('coname', 'Company Name', 'required');

                if ($this->form_validation->run() == false) {
                    
                        $json_array['status'] = ERROR_CODE;

                        $json_array['message'] = validation_errors('', '');
                    }else{
                            $frm_details = $this->input->post();
                         
                            if(isset($frm_details['previous_emp_code'])){
                                $previous_emp_code = ($frm_details['previous_emp_code'] == "on") ? '1' : NULL;
                            }else{
                                $previous_emp_code = NULL;
                            }

                            if(isset($frm_details['branch_location'])){
                                $branch_location = ($frm_details['branch_location'] == "on") ? '1' : NULL;
                            }else{
                                $branch_location = NULL;
                            }

                            if(isset($frm_details['experience_letter'])){
                                $experience_letter = ($frm_details['experience_letter'] == "on") ? '1' : NULL;
                            }else{
                                $experience_letter = NULL;
                            }

                            if(isset($frm_details['loa'])){
                                $loa = ($frm_details['loa'] == "on") ? '1' : NULL;
                            }else{
                                $loa = NULL;
                            }
                            
                            if(isset($frm_details['auto_initiate'])){
                                $auto_initiate = ($frm_details['auto_initiate'] == "on") ? '1' : NULL;
                            }else{
                                $auto_initiate = NULL;
                            }
                            
                            if(isset($frm_details['follow_up'])){
                                $follow_up = ($frm_details['follow_up'] == "on") ? '1' : NULL;
                            }else{
                                $follow_up = NULL;
                            }

                            if(isset($frm_details['client_disclosure'])){
                                $client_disclosure = ($frm_details['client_disclosure'] == "on") ? '1' : NULL;
                            }else{
                                $client_disclosure = NULL;
                            }
                           
                            $field_array = array("coname" => $frm_details["coname"],
                                "address" => $frm_details["address"],
                                "city" => $frm_details["city"],
                                "pincode" => $frm_details["pincode"],
                                "state" => $frm_details["state"],
                                "cc_email_id" => $frm_details["cc_email_id"],
                                "co_email_id" => $frm_details["co_email_id"],
                                "previous_emp_code" => $previous_emp_code,
                                "branch_location" => $branch_location,
                                "experience_letter" => $experience_letter,
                                "loa" => $loa,
                                "auto_initiate" => $auto_initiate,
                                "follow_up" => $follow_up,
                                "client_disclosure" => $client_disclosure,
                            );
                            
                            $field_array = array_map('strtolower', $field_array);

                            $result = $this->company_database_model->save_company($field_array, array('id' => $frm_details['id']));
                            if ( $result ) {
                                $json_array['status'] = SUCCESS_CODE;

                                $json_array['message'] = 'Company Updated Successfully';

                                $json_array['redirect'] = ADMIN_SITE_URL . 'company_database';
                            } else {
                                $json_array['status'] = ERROR_CODE;

                                $json_array['message'] = 'Something went wrong,please try again';
                            }
                        }
                
                echo_json($json_array);

            } catch (Exception $e) {
                log_message('error', 'Error on Company_database::update_company');
                log_message('error', $e->getMessage());
            }
        } else {
            permission_denied();
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

    public function addAddCompanyModelEmployment()
    {
        log_message('error', 'Add Company Model'); 
        try {
            if ($this->input->is_ajax_request()) {
                $data['states'] = $this->get_states();
                echo $this->load->view('admin/addAddCompanyModelEmployment', $data, true);
            } else {
                echo "<p>We're sorry, but you do not have access to this page.</p>";
            }
        } catch (Exception $e) {
            log_message('error', 'Error on Company_database::addAddCompanyModelEmployment');
            log_message('error', $e->getMessage());
        }
    }


    public function save_company_employment()
    {
        if ($this->input->is_ajax_request()) {

            log_message('error', 'Campany Save'); 
            try {
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
                        "state" => $frm_details["state"],
                        'created_on' => date(DB_DATE_FORMAT),
                        'created_by' => $this->user_info['id'],
                        'modified_on' => date(DB_DATE_FORMAT),
                        'modified_by' => $this->user_info['id'],
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
