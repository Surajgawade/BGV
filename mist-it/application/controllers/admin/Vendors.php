<?php defined('BASEPATH') or exit('No direct script access allowed');

class Vendors extends MY_Controller
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

        $this->perm_array = array('page_id' => 66, 'direct_access' => 'true');
        $this->panel = array('0'=>'Select Panel','5 panel' => '5 Panel','7 panel'=>'7 Panel');
        $this->load->model('vendors_model');
    }

    public function index()
    {
        $data['header_title'] = "Vendor List";

        $this->load->view('admin/header', $data);

        $this->load->view('admin/vendor_list');

        $this->load->view('admin/footer');
    }

    public function fetch_vendor_list()
    {
        if ($this->input->is_ajax_request()) {

            $params = $add_candidates = $data_arry = $columns = array();

            $params = $_REQUEST;

            $columns = array('vendor_name', 'created_on', 'user_name', 'vendors_components', 'aggr_end_date', 'status');

            $where_arry = array();

            $add_vendors = $this->vendors_model->get_vendor_list($where_arry, $params, $columns);

            $totalRecords = count($this->vendors_model->get_vendor_list_count($where_arry, $params, $columns));

            $x = 0;

            foreach ($add_vendors as $add_vendor) {

                $status = ($add_vendor['status'] == 1) ? "Active" : "Inactive";
                $delete_access = ($this->permission['access_admin_holiday_delete']) ? 'data-accessUrl' : 'data-url';

                $data_arry[$x]['id'] = $x + 1;
                $data_arry[$x]['vendor_name'] = ucwords($add_vendor['vendor_name']);
                $data_arry[$x]['created_by'] = $add_vendor['user_name'];
                $data_arry[$x]['encry_id'] = ADMIN_SITE_URL . 'vendors/view_details/' . encrypt($add_vendor['id']);
                $data_arry[$x]['created_on'] = convert_db_to_display_date($add_vendor['created_on'], DB_DATE_FORMAT);
                $data_arry[$x]['components'] = $add_vendor['vendors_components'];
                $data_arry[$x]['aggr_end_date'] = convert_db_to_display_date($add_vendor['aggr_end_date']);
                $data_arry[$x]['status'] = $status;

                $x++;

                unset($results_client_manager);
                unset($results_sales_manager);

            }

            $json_data = array(
                "draw" => intval($params['draw']),
                "recordsTotal" => intval($totalRecords),
                "recordsFiltered" => intval($totalRecords),
                "data" => $data_arry,
            );

            echo_json($json_data);

        } else {
            permission_denied();
        }

    }

    public function add()
    {
        $data['header_title'] = "Vendor Database";

        $data['states'] = $this->get_states();

        $data['employment_states'] = $this->get_states();



        $previous_selected_state = $this->vendors_model->select_vendor_employment_state1();
                
        if(!empty($previous_selected_state))
        {
            $selected_state1  = array_map('current', $previous_selected_state);
            $selected_state1 =  implode(',',$selected_state1);
            $selected_state  = explode(',', $selected_state1);
            foreach ($selected_state as $key => $value) {
               if($value != "maharashtra")
               {
                 unset($data['states'][$value]); 
               }
            }

        }

        $previous_selected_employment_state = $this->vendors_model->select_vendor_employment_state1();
      
        if(!empty($previous_selected_employment_state))
        {
            $selected_state_employment1  = array_map('current', $previous_selected_employment_state);

            $selected_state_employment1 =  implode(',',$selected_state_employment1);

            $selected_state_employment  = explode(',', $selected_state_employment1);

            foreach ($selected_state_employment as $key => $value) {
               if($value != "maharashtra")
               {
                 unset($data['employment_states'][$value]); 
               }
            }
        }

   
        $data['court_clients'] = $this->get_clients(array('status' => STATUS_ACTIVE));

        $data['global_clients'] = $this->get_clients(array('status' => STATUS_ACTIVE));

        $data['credit_clients'] = $this->get_clients(array('status' => STATUS_ACTIVE));

        $data['panel'] = $this->panel; 

        $previous_selected_court_client = $this->vendors_model->select_vendor_court_client1();

        if(!empty($previous_selected_court_client))
        {

            $selected_court1  = array_map('current', $previous_selected_court_client);
            $selected_court1 =  implode(',',$selected_court1);
            $selected_court  = explode(',', $selected_court1);
             
            foreach ($selected_court as $key => $value) {

                
                unset($data['court_clients'][$value]); 

            }

        }

        $previous_selected_global_client = $this->vendors_model->select_vendor_global_client1();

        if(!empty($previous_selected_global_client))
        {
            $selected_global1  = array_map('current', $previous_selected_global_client);
            $selected_global1 =  implode(',',$selected_global1);
            $selected_global  = explode(',', $selected_global1);
            foreach ($selected_global as $key => $value) {

               
                unset($data['global_clients'][$value]); 

            } 

        }

        $previous_selected_credit_client = $this->vendors_model->select_vendor_credit_client1();

        if(!empty($previous_selected_credit_client))
        {
            $selected_credit1  = array_map('current', $previous_selected_credit_client);
            $selected_credit1 =  implode(',',$selected_credit1);
            $selected_credit  = explode(',', $selected_credit1);
            foreach ($selected_credit as $key => $value) {

               
                unset($data['credit_clients'][$value]); 

            } 

        }
           
        $previous_selected_panel = $this->vendors_model->select_vendor_drugs_panel1();
        if(!empty($previous_selected_panel))
        {
            $selected_panel1  = array_map('current', $previous_selected_panel);
            $selected_panel1 =  implode(',',$selected_panel1);
            $selected_panel  = explode(',', $selected_panel1);
            foreach ($selected_panel as $key => $value) {

               
                unset($data['panel'][$value]); 

            } 

        }
                 
    
        
        $data['clients_id'] = $this->get_clients(array('status' => STATUS_ACTIVE));
        
        $data['components'] = $this->components();

        $data['vendor_managers'] = $this->users_list();

        $this->load->view('admin/header', $data);

        $this->load->view('admin/vendor_add');

        $this->load->view('admin/footer');
    }

    public function check_vendor_name()
    {
        $result = $this->vendors_model->select(true, array('id'), array('vendor_name' => $this->input->post('vendor_name')));

        echo (!empty($result) ? 'false' : 'true');
    }

    public function save_vendor()
    {
        if ($this->input->is_ajax_request()) {

            $frm_details = $this->input->post();

            if (in_array("addrver", $frm_details['components'])) {

                if ($frm_details['vendors_components_tat'][0] == '' || $frm_details['vendors_components_tat'][0] == '0') {

                    $this->form_validation->set_rules('vendors_components_tat', 'Address Tat', 'required|is_natural_no_zero');

                }
            }

            if (in_array("courtver", $frm_details['components'])) {

                if ($frm_details['vendors_components_tat'][1] == '' || $frm_details['vendors_components_tat'][1] == '0') {
                    $this->form_validation->set_rules('tat_courtver', 'Court Tat', 'required|is_natural_no_zero');
                }
            }

            if (in_array("globdbver", $frm_details['components'])) {

                if ($frm_details['vendors_components_tat'][2] == '' || $frm_details['vendors_components_tat'][2] == '0') {
                    $this->form_validation->set_rules('tat_globdbver', 'Global Tat', 'required|is_natural_no_zero');
                }
            }
            if (in_array("narcver", $frm_details['components'])) {

                if ($frm_details['vendors_components_tat'][3] == '' || $frm_details['vendors_components_tat'][3] == '0') {
                    $this->form_validation->set_rules('tat_narcver', 'Drugs Tat', 'required|is_natural_no_zero');
                }
            }
            if (in_array("refver", $frm_details['components'])) {

                if ($frm_details['vendors_components_tat'][4] == '' || $frm_details['vendors_components_tat'][4] == '0') {
                    $this->form_validation->set_rules('tat_refver', 'Reference Tat', 'required|is_natural_no_zero');
                }
            }

            if (in_array("empver", $frm_details['components'])) {

                if ($frm_details['vendors_components_tat'][5] == '' || $frm_details['vendors_components_tat'][5] == '0') {
                    $this->form_validation->set_rules('tat_empver', 'Employee Tat', 'required|is_natural_no_zero');
                }
            }

            if (in_array("eduver", $frm_details['components'])) {

                if ($frm_details['vendors_components_tat'][6] == '' || $frm_details['vendors_components_tat'][6] == '0') {
                    $this->form_validation->set_rules('tat_eduver', 'Education Tat', 'required|is_natural_no_zero');
                }
            }

            if (in_array("identity", $frm_details['components'])) {

                if ($frm_details['vendors_components_tat'][7] == '' || $frm_details['vendors_components_tat'][7] == '0') {
                    $this->form_validation->set_rules('tat_identity', 'Identity Tat', 'required|is_natural_no_zero');
                }
            }

            if (in_array("cbrver", $frm_details['components'])) {

                if ($frm_details['vendors_components_tat'][8] == '' || $frm_details['vendors_components_tat'][8] == '0') {
                    $this->form_validation->set_rules('tat_cbrver', 'Credit  Tat', 'required|is_natural_no_zero');
                }
            }

            if (in_array("crimver", $frm_details['components'])) {
                if ($frm_details['vendors_components_tat'][9] == '' || $frm_details['vendors_components_tat'][9] == '0') {
                    $this->form_validation->set_rules('tat_crimver', 'Crime Tat', 'required|is_natural_no_zero');
                }
            }

            $this->form_validation->set_rules('vendor_name', 'User Name', 'required|is_unique[vendors.vendor_name]');

            $this->form_validation->set_rules('pincode', 'Pincode', 'required|numeric|min_length[6]|max_length[6]');

            if ($this->form_validation->run() == false) {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');
            } else {
                $frm_details = $this->input->post();

                if(!empty($frm_details['state_address']))
                {
                  $state_address  =  implode(',', $frm_details['state_address']);
                }
                else
                {
                   $state_address  = ''; 
                }
                if(!empty($frm_details['address_city']))
                {
                   $address_city  =  $frm_details['address_city'];
                }
                else
                {
                   $address_city  = ''; 
                }

                if(!empty($frm_details['state_employment']))
                {
                  $state_employment  =  implode(',', $frm_details['state_employment']);
                }
                else
                {
                   $state_employment  = ''; 
                }
                if(!empty($frm_details['employment_city']))
                {
                   $employment_city  =  $frm_details['employment_city'];
                }
                else
                {
                   $employment_city  = ''; 
                }


                if(!empty($frm_details['court_clients']))
                {
                  $court_clients  =  implode(',', $frm_details['court_clients']);
                }
                else
                {
                   $court_clients  = ''; 
                }

                if(!empty($frm_details['global_clients']))
                {
                  $global_clients  =  implode(',', $frm_details['global_clients']);
                }
                else
                {
                   $global_clients  = ''; 
                }

                if(!empty($frm_details['credit_clients']))
                {
                  $credit_clients  =  implode(',', $frm_details['credit_clients']);
                }
                else
                {
                   $credit_clients  = ''; 
                }

                if(!empty($frm_details['narcotis_panel']))
                {
                  $narcotis_panel  =  $frm_details['narcotis_panel'];
                }
                else
                {
                   $narcotis_panel  = ''; 
                }

                if(!empty($frm_details['education_verification_status']))
                {
                  $education_verification_status  =  $frm_details['education_verification_status'];
                }
                else
                {
                   $education_verification_status  = ''; 
                }


                $field_array = array('vendor_name' => $frm_details['vendor_name'],
                    'street_address' => $frm_details['street_address'],
                    //'vendor_managers' => implode(',', $frm_details['vendor_managers']),
                    'city' => $frm_details['city'],
                    'state' => $frm_details['state'],
                    'pincode' => $frm_details['pincode'],
                    'sopc_name' => $frm_details['sopc_name'],
                    'primary_contact' => $frm_details['primary_contact'],
                    'email_id' => $frm_details['email_id'],
                    'aggr_start_date' => convert_display_to_db_date($frm_details['aggr_start_date']),
                    'aggr_end_date' => convert_display_to_db_date($frm_details['aggr_end_date']),
                    'vendor_remarks' => $frm_details['vendor_remarks'],
                    'vendors_components' => implode(',', $frm_details['components']),
                    'vendors_components_tat' => $this->components_key_val($frm_details['vendors_components_tat']),
                    'adv_name' => $frm_details['adv_name'],
                    'address_state' => $state_address,
                    'address_city' => $address_city,
                    'employment_state' => $state_employment,
                    'employment_city' => $employment_city,
                    'court_client' => $court_clients,
                    'global_client' => $global_clients,
                    'credit_client' => $credit_clients,
                    'panel_code' => $narcotis_panel,
                    'education_verification_status' => $education_verification_status,
                    'pcc_mov' => $frm_details['crimver_mov'],
                    'pcc_mov_email' => $frm_details['pcc_email_id'],
                    'created_on' => date(DB_DATE_FORMAT),
                    'created_by' => $this->user_info['id'],
                    'modified_on' => date(DB_DATE_FORMAT),
                    'modified_by' => $this->user_info['id'],
                    'status' => STATUS_ACTIVE,
                    
                );
                $file_upload_path = SITE_BASE_PATH . UPLOAD_FOLDER . 'vendor_aggr';
                if (!folder_exist($file_upload_path)) {
                    mkdir($file_upload_path, 0777);
                }

                if(isset($frm_details['generate']))
                {
                    if($frm_details['generate'] == "on")
                    {
                       $field_array['generate'] = 1;
                    }
  
                }

                $field_array = array_map('strtolower', $field_array);
                if (!empty($_FILES['attchament']['name'])) {
                    $file_name = str_replace(' ', '_', $_FILES['attchament']['name']);

                    $new_file_name = time() . "_" . $file_name;

                    $_FILES['attchament_']['name'] = $new_file_name;

                    $_FILES['attchament_']['tmp_name'] = $_FILES['attchament']['tmp_name'];

                    $_FILES['attchament_']['error'] = $_FILES['attchament']['error'];

                    $_FILES['attchament_']['size'] = $_FILES['attchament']['size'];

                    $config['upload_path'] = $file_upload_path;

                    $config['file_ext_tolower'] = true;

                    $config['max_size'] = BULK_UPLOAD_MAX_SIZE_MB * 2000;

                    $config['allowed_types'] = 'pdf|png|jpg|jpeg';

                    $config['remove_spaces'] = true;

                    $config['overwrite'] = false;

                    $config['file_name'] = $new_file_name;

                    $this->load->library('upload', $config);

                    $this->upload->initialize($config);

                    if ($this->upload->do_upload('attchament_')) {
                        $field_array['aggr_file'] = $new_file_name;
                    }
                }

                $insert_id = $this->vendors_model->save($field_array);

                if (count($frm_details['client_email_id']) > 0 && count($frm_details['client_password']) > 0) {
                    $login_details = array();

                    for ($i = 0; $i < count($frm_details['client_email_id']); $i++) {

                        if ($frm_details['client_email_id'][$i] != '' && $frm_details['client_password'][$i] != "") {

                            $login_details[] = array('vendors_id' => $insert_id,
                                'first_name' => $frm_details['client_first_name'][$i],
                                'email_id' => $frm_details['client_email_id'][$i],
                                'password' => create_password($frm_details['client_password'][$i]),
                                'mobile_no' => $frm_details['client_mobileno'][$i],
                                'status' => STATUS_ACTIVE,
                                'creted_on' => date(DB_DATE_FORMAT),
                                'created_by' => $this->user_info['id'],
                            );
                        }
                    }
                }

            

                if ($insert_id) {
                    if (!empty($login_details)) {
                        $this->vendors_model->insert_batch_vendors_login($login_details);
                    }

                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'Added Successfully';

                    $json_array['redirect'] = ADMIN_SITE_URL . 'vendors';
                } else {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Something went wrong,please try again';
                }
            }
            echo_json($json_array);
        }
    }

    public function update_vendor_details()
    {
        if ($this->input->is_ajax_request()) {
            $frm_details = $this->input->post();
  
            if (in_array("addrver", $frm_details['components'])) {

                if ($frm_details['vendors_components_tat'][0] == '' || $frm_details['vendors_components_tat'][0] == '0') {

                    $this->form_validation->set_rules('vendors_components_tat', 'Address Tat', 'required|is_natural_no_zero');

                }
            }

            if (in_array("courtver", $frm_details['components'])) {

                if ($frm_details['vendors_components_tat'][1] == '' || $frm_details['vendors_components_tat'][1] == '0') {
                    $this->form_validation->set_rules('tat_courtver', 'Court Tat', 'required|is_natural_no_zero');
                }
            }

            if (in_array("globdbver", $frm_details['components'])) {

                if ($frm_details['vendors_components_tat'][2] == '' || $frm_details['vendors_components_tat'][2] == '0') {
                    $this->form_validation->set_rules('tat_globdbver', 'Global Tat', 'required|is_natural_no_zero');
                }
            }
            if (in_array("narcver", $frm_details['components'])) {

                if ($frm_details['vendors_components_tat'][3] == '' || $frm_details['vendors_components_tat'][3] == '0') {
                    $this->form_validation->set_rules('tat_narcver', 'Drugs Tat', 'required|is_natural_no_zero');
                }
            }
            if (in_array("refver", $frm_details['components'])) {

                if ($frm_details['vendors_components_tat'][4] == '' || $frm_details['vendors_components_tat'][4] == '0') {
                    $this->form_validation->set_rules('tat_refver', 'Reference Tat', 'required|is_natural_no_zero');
                }
            }

            if (in_array("empver", $frm_details['components'])) {

                if ($frm_details['vendors_components_tat'][5] == '' || $frm_details['vendors_components_tat'][5] == '0') {
                    $this->form_validation->set_rules('tat_empver', 'Employee Tat', 'required|is_natural_no_zero');
                }
            }

            if (in_array("eduver", $frm_details['components'])) {

                if ($frm_details['vendors_components_tat'][6] == '' || $frm_details['vendors_components_tat'][6] == '0') {
                    $this->form_validation->set_rules('tat_eduver', 'Education Tat', 'required|is_natural_no_zero');
                }
            }

            if (in_array("identity", $frm_details['components'])) {

                if ($frm_details['vendors_components_tat'][7] == '' || $frm_details['vendors_components_tat'][7] == '0') {
                    $this->form_validation->set_rules('tat_identity', 'Identity Tat', 'required|is_natural_no_zero');
                }
            }

            if (in_array("cbrver", $frm_details['components'])) {

                if ($frm_details['vendors_components_tat'][8] == '' || $frm_details['vendors_components_tat'][8] == '0') {
                    $this->form_validation->set_rules('tat_cbrver', 'Credit  Tat', 'required|is_natural_no_zero');
                }
            }

            if (in_array("crimver", $frm_details['components'])) {
                if ($frm_details['vendors_components_tat'][9] == '' || $frm_details['vendors_components_tat'][9] == '0') {
                    $this->form_validation->set_rules('tat_crimver', 'Crime Tat', 'required|is_natural_no_zero');
                }
            }

            $this->form_validation->set_rules('vendor_name', 'Vendor Name', 'required');
            $this->form_validation->set_rules('update_id', 'ID', 'required');

            $this->form_validation->set_rules('pincode', 'Pincode', 'required|numeric|min_length[6]|max_length[6]');

            if ($this->form_validation->run() == false) {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');
            } else {
                $frm_details = $this->input->post();

                if(!empty($frm_details['state_address']))
                {
                  $state_address  =  implode(',', $frm_details['state_address']);
                }
                else
                {
                   $state_address  = ''; 
                }

                if(!empty($frm_details['address_city']))
                {
                   $address_city  =  $frm_details['address_city'];
                }
                else
                {
                   $address_city  = ''; 
                }

                if(!empty($frm_details['state_employment']))
                {
                  $state_employment  =  implode(',', $frm_details['state_employment']);
                }
                else
                {
                   $state_employment  = ''; 
                }
                if(!empty($frm_details['employment_city']))
                {
                   $employment_city  =  $frm_details['employment_city'];
                }
                else
                {
                   $employment_city  = ''; 
                }



                if(!empty($frm_details['court_clients']))
                {
                  $court_clients  =  implode(',', $frm_details['court_clients']);
                }
                else
                {
                   $court_clients  = ''; 
                }

                if(!empty($frm_details['global_clients']))
                {
                  $global_clients  =  implode(',', $frm_details['global_clients']);
                }
                else
                {
                   $global_clients  = ''; 
                }

                if(!empty($frm_details['credit_clients']))
                {
                  $credit_clients  =  implode(',', $frm_details['credit_clients']);
                }
                else
                {
                   $credit_clients  = ''; 
                } 

                if(!empty($frm_details['narcotis_panel']))
                {
                    $narcotis_panel  =  implode(',',$frm_details['narcotis_panel']);
                }
                else
                {
                    $narcotis_panel  = ''; 
                }


                if(!empty($frm_details['education_verification_status']))
                {
                  $education_verification_status  =  $frm_details['education_verification_status'];
                }
                else
                {
                   $education_verification_status  = ''; 
                }
   

                $field_array = array('vendor_name' => $frm_details['vendor_name'],
                    'street_address' => $frm_details['street_address'],
                    // 'vendor_managers' => implode(',', $frm_details['vendor_managers']),
                    'city' => $frm_details['city'],
                    'state' => $frm_details['state'],
                    'pincode' => $frm_details['pincode'],
                    'sopc_name' => $frm_details['sopc_name'],
                    'primary_contact' => $frm_details['primary_contact'],
                    'email_id' => $frm_details['email_id'],
                    'aggr_start_date' => convert_display_to_db_date($frm_details['aggr_start_date']),
                    'aggr_end_date' => convert_display_to_db_date($frm_details['aggr_end_date']),
                    'vendor_remarks' => $frm_details['vendor_remarks'],
                    'vendors_components' => implode(',', $frm_details['components']),
                    'vendors_components_tat' => $this->components_key_val($frm_details['vendors_components_tat']),
                    'address_state' => $state_address,
                    'address_city' => $address_city,
                    'employment_states' => $state_employment,
                    'employment_city' => $employment_city,
                    'court_client' => $court_clients,
                    'global_client' => $global_clients,
                    'panel_code' => $narcotis_panel,
                    'education_verification_status' => $education_verification_status,
                    'pcc_mov' => $frm_details['crimver_mov'],
                    'pcc_mov_email' => $frm_details['pcc_email_id'],
                    'created_on' => date(DB_DATE_FORMAT),
                    'created_by' => $this->user_info['id'],
                    'modified_on' => date(DB_DATE_FORMAT),
                    'modified_by' => $this->user_info['id'],
                    'status' => $frm_details['status'],
                    'adv_name' => $frm_details['adv_name']
                );

                $file_upload_path = SITE_BASE_PATH . UPLOAD_FOLDER . 'vendor_aggr';
                if (!folder_exist($file_upload_path)) {
                    mkdir($file_upload_path, 0777);
                }
            
                $field_array = array_map('strtolower', $field_array);
                if (!empty($_FILES['attchament']['name'])) {
                    $file_name = str_replace(' ', '_', $_FILES['attchament']['name']);

                    $new_file_name = time() . "_" . $file_name;

                    $_FILES['attchament_']['name'] = $new_file_name;

                    $_FILES['attchament_']['tmp_name'] = $_FILES['attchament']['tmp_name'];

                    $_FILES['attchament_']['error'] = $_FILES['attchament']['error'];

                    $_FILES['attchament_']['size'] = $_FILES['attchament']['size'];

                    $config['upload_path'] = $file_upload_path;

                    $config['file_ext_tolower'] = true;

                    $config['max_size'] = BULK_UPLOAD_MAX_SIZE_MB * 2000;

                    $config['allowed_types'] = 'pdf|png|jpg|jpeg';

                    $config['remove_spaces'] = true;

                    $config['overwrite'] = false;

                    $config['file_name'] = $new_file_name;

                    $this->load->library('upload', $config);

                    $this->upload->initialize($config);

                    if ($this->upload->do_upload('attchament_')) {
                        $field_array['aggr_file'] = $new_file_name;
                    }
                }

                if(isset($frm_details['generate']))
                {
                    if($frm_details['generate'] == "on")
                    {
                       $field_array['generate'] = 1;
                    }
                    else
                    {
                       $field_array['generate'] = 0; 
                    }
  
                }
                else
                {
                   $field_array['generate'] = 0; 
                }


                $insert_id = $this->vendors_model->save($field_array, array('id' => $frm_details['update_id']));

                if (count($frm_details['client_email_id']) > 0 && count($frm_details['client_password']) > 0) {
                    $login_details = array();

                    for ($i = 0; $i < count($frm_details['client_email_id']); $i++) {

                        if ($frm_details['client_email_id'][$i] != '' && $frm_details['client_password'][$i] != "") {

                            $login_details[] = array('vendors_id' => $frm_details['update_id'],
                                'first_name' => $frm_details['client_first_name'][$i],
                                'email_id' => $frm_details['client_email_id'][$i],
                                'password' => create_password($frm_details['client_password'][$i]),
                                'mobile_no' => $frm_details['client_mobileno'][$i],
                                'status' => STATUS_ACTIVE,
                                'creted_on' => date(DB_DATE_FORMAT),
                                'created_by' => $this->user_info['id'],
                            );
                        }
                    }
                }

           

                if ($insert_id) {
                    if (!empty($login_details)) {
                        $this->vendors_model->insert_batch_vendors_login($login_details);
                    }

                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'Record Updated Successfully';

                    $json_array['redirect'] = ADMIN_SITE_URL . 'vendors';
                } else {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Something went wrong,please try again';
                }
            }
            echo_json($json_array);
        }
    }
    public function vendor_multiple_spoc()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->load->view('admin/vendor_multiple_spoc_view', '', true);
        }
    }

    public function view_details($ids = false)
    {
        if (!empty($ids)) {
            $results = $this->vendors_model->select_join(array('vendors.id' => decrypt($ids)));

            if (!empty($results)) {
                $data['header_title'] = 'Edit Details';

                $data['detailds'] = $results[0];

                $data['states'] = $this->get_states();

                $data['employment_states'] = $this->get_states();


                $previous_selected_state = $this->vendors_model->select_vendor_state($results[0]['id']);
     
                if(!empty($previous_selected_state))
                {
                    $selected_state1  = array_map('current', $previous_selected_state);
                    $selected_state1 =  implode(',',$selected_state1);
                    $selected_state  = explode(',', $selected_state1);
                    foreach ($selected_state as $key => $value) {

                        if($value != "maharashtra")
                        {
                          unset($data['states'][$value]); 
                        }
                    }

                }

                $previous_selected_employment_state = $this->vendors_model->select_vendor_employment_state($results[0]['id']);
                
                if(!empty($previous_selected_employment_state))
                {
                    $selected_state_employment1  = array_map('current', $previous_selected_employment_state);
                    $selected_state_employment1 =  implode(',',$selected_state_employment1);
                    $selected_state_employment  = explode(',', $selected_state_employment1);
                    foreach ($selected_state_employment as $key => $value) {
                       if($value != "maharashtra")
                       {
                         unset($data['employment_states'][$value]); 
                       }
                    }
                }
              
      
                $data['court_clients'] = $this->get_clients(array('status' => STATUS_ACTIVE));

                $data['global_clients'] = $this->get_clients(array('status' => STATUS_ACTIVE));
               
                $data['credit_clients'] = $this->get_clients(array('status' => STATUS_ACTIVE));

                $data['panel'] = $this->panel; 


                $previous_selected_court_client = $this->vendors_model->select_vendor_court_client($results[0]['id']);

                if(!empty($previous_selected_court_client))
                {
                    $selected_court1  = array_map('current', $previous_selected_court_client);
                    $selected_court1 =  implode(',',$selected_court1);
                    $selected_court  = explode(',', $selected_court1);
                    foreach ($selected_court as $key => $value) {

                        
                        unset($data['court_clients'][$value]); 

                    }

                }

                $previous_selected_global_client = $this->vendors_model->select_vendor_global_client($results[0]['id']);

                if(!empty($previous_selected_global_client))
                {
                    $selected_global1  = array_map('current', $previous_selected_global_client);
                    $selected_global1 =  implode(',',$selected_global1);
                    $selected_global  = explode(',', $selected_global1);
                    foreach ($selected_global as $key => $value) {

                      
                        unset($data['global_clients'][$value]); 

                   }

                }

                $previous_selected_credit_client = $this->vendors_model->select_vendor_credit_client($results[0]['id']);

                if(!empty($previous_selected_credit_client))
                {
                    $selected_credit1  = array_map('current', $previous_selected_credit_client);
                    $selected_credit1 =  implode(',',$selected_credit1);
                    $selected_credit  = explode(',', $selected_credit1);
                    foreach ($selected_credit as $key => $value) {

                      
                        unset($data['credit_clients'][$value]); 

                   }

                }

                $previous_selected_drugs_panel = $this->vendors_model->select_vendor_drugs_panel($results[0]['id']);

                if(!empty($previous_selected_drugs_panel))
                {
                    $selected_panel1  = array_map('current', $previous_selected_drugs_panel);
                    $selected_panel1 =  implode(',',$selected_panel1);
                    $selected_panel  = explode(',', $selected_panel1);
                    foreach ($selected_panel as $key => $value) {

                      
                        unset($data['panel'][$value]); 

                   }

                }




                $data['components'] = $this->components();


                $data['vendor_managers'] = $this->users_list();

                $data['vendor_account'] = $this->vendors_model->vendor_account(array('vendors_id' => decrypt($ids)));

                $this->load->view('admin/header', $data);

                $this->load->view('admin/vendor_edit');

                $this->load->view('admin/footer');
            }
        } else {
            show_404();
        }
    }

    public function delete()
    {

        $vendor_id = $this->input->post('vendor_id');

        if ($this->input->is_ajax_request() && $this->permission['access_address_vendor_database_delete'] == true) {

            if ($vendor_id) {

                $field_array = array('status' => STATUS_DELETED,
                    'modified_on' => date(DB_DATE_FORMAT),
                    'modified_by' => $this->user_info['id'],
                );

                $where_array = array('id' => $is_exits['id']);

                if ($this->vendor_model->save($field_array, $where_array)) {
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

    public function get_vendor_state_info()
    {
        if ($this->input->is_ajax_request()) { 
            print_r($this->input->post('states'));
        }
        else{

           permission_denied();
        }
    }

    public function vendor_edit_idwise($vendor_id)
    {
        $details = $this->vendors_model->vendor_account(array('vendors_login.id' => $vendor_id));

        if ($vendor_id && !empty($details)) {

            log_message('error', 'Vendor Edit Open Idwise');
            try {
                 
                $data['details'] =  $details[0];
                echo $this->load->view('admin/vendor_edit_idwise', $data, true);
            } catch (Exception $e) {
                log_message('error', 'Vednors::vendor_edit_idwise');
                log_message('error', $e->getMessage());
            }
        } else {
            echo "<h4>Record Not Found</h4>";
        }
    }

    public function update_vendor_details_idwise()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {

            $frm_details = $this->input->post();
               
                $fields = array('first_name' =>  $frm_details['first_name'],'email_id' =>  $frm_details['email_id'] ,'mobile_no' =>  $frm_details['mobile_no'] ,'modified_on' => date(DB_DATE_FORMAT), 'modified_by' => $this->user_info['id']);


                ($frm_details["password"] != "") ? $fields['password'] = create_password($frm_details['password']) : '';


                $result = $this->vendors_model->save_update('vendors_login',$fields, array('id' => $frm_details['update_id']));

                if ($result) {

                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'Deleted Successfully';

                    $json_array['redirect'] = ADMIN_SITE_URL . 'vendors';

                } else {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Something went wrong,please try again';
                }

        
        } else {
            $json_array['message'] = "Something went wrong,please try again";

            $json_array['status'] = ERROR_CODE;
        }
        echo_json($json_array);
    }

   
}
