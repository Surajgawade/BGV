<?php defined('BASEPATH') or exit('No direct script access allowed');

class Clients extends MY_Controller
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
       
        $this->perm_array = array('page_id' => 2);
        if ($this->input->is_ajax_request()) {

            $this->perm_array = array('page_id' => 4);
        }
        $this->load->model(array('clients_model'));
    }

    public function index()
    {
        try {
            $data['header_title'] = "Clients";

            $this->load->view('admin/header', $data);

            $this->load->view('admin/clients_list');

            $this->load->view('admin/footer');

        } catch (Exception $e) {
            log_message('error', 'Error on Clients::index');
            log_message('error', $e->getMessage());
        }    


    }

    public function fetch_clients()
    {
        if ($this->input->is_ajax_request()) {

            log_message('error', 'Fetch the Cleint');
            try {

                $params = $add_candidates = $data_arry = $columns = array();

                $params = $_REQUEST;

                $columns = array('clientname', 'user_profile.user_name', 'user_profile.user_name', 'status');

                $where_arry = array();

                $add_client = $this->clients_model->get_client_list($where_arry, $params, $columns);

                $totalRecords = count($this->clients_model->get_client_list_count($where_arry, $params, $columns));

                $x = 0;
                $results_client_manager = [];
                foreach ($add_client as $add_clients) {

                    $client_manager = explode('||', $add_clients['clientmgr']);

                    foreach ($client_manager as $value_client_manager) {

                        $result_client_manager = $this->clients_model->select_join_client(array('user_profile.id' => $value_client_manager));
                        if (!empty($result_client_manager)) {
                            $results_client_manager[] = $result_client_manager[0]['fullname'];
                        }
                    }

                    $sales_manager = explode('||', $add_clients['sales_manager']);

                    foreach ($sales_manager as $value_sales_manager) {

                        $result_sales_manager = $this->clients_model->select_join_client(array('user_profile.id' => $value_sales_manager));
                        if (!empty($result_sales_manager)) {
                            $results_sales_manager[] = $result_sales_manager[0]['fullname'];
                        }
                    }

                    $status = ($add_clients['status'] == 1) ? "Active" : "Inactive";
                    $delete_access = ($this->permission['access_admin_holiday_delete']) ? 'data-accessUrl' : 'data-url';

                    $data_arry[$x]['id'] = $x + 1;
                    $data_arry[$x]['clientname'] = ucwords($add_clients['clientname']);
                    $data_arry[$x]['created_by'] = $add_clients['create_by'];
                    $data_arry[$x]['encry_id'] = ADMIN_SITE_URL . 'clients/view_details/' . encrypt($add_clients['id']);
                    $data_arry[$x]['clientmgr'] = (isset($results_client_manager) ? implode(",", $results_client_manager) : '');
                    // $data_arry[$x]['sales_manager'] = implode(",",$results_sales_manager);

                    $data_arry[$x]['sales_manager'] = '';
                    $data_arry[$x]['agreement_end_date'] = convert_db_to_display_date($add_clients['agreement_end_date']);
                    $data_arry[$x]['created_on'] = convert_db_to_display_date($add_clients['created_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);
                    $data_arry[$x]['status'] = $status;
                    $data_arry[$x]['delete'] = "<a href='javascript:void(0)' class='deleteURL' " . $delete_access . "=" . ADMIN_SITE_URL . 'clients/delete_client/' . $add_clients['id'] . "><i class='fa fa-trash'></i> Delete</a>";
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

            } catch (Exception $e) {
                log_message('error', 'Error on Clients::fetch_clients');
                log_message('error', $e->getMessage());
            }    
    

        } else {
            permission_denied();
        }

    }

    public function add()
    {

        log_message('error', 'Client Add');
        try {
            $data['header_title'] = "Create New Client";

            $data['entity_list'] = $this->get_entiry_package_list();

            $data['clientmgr'] = $this->users_list_limited();

            $data['sales_manager'] = $this->sales_manager_list_limited();

            $this->load->view('admin/header', $data);

            $this->load->view('admin/clients_add');

            $this->load->view('admin/footer');

        } catch (Exception $e) {
                log_message('error', 'Error on Clients::add');
                log_message('error', $e->getMessage());
        }
    }

    public function save_clients()
    {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('clientname', 'Client Name', 'required|is_unique[clients.clientname]');

            if ($this->form_validation->run() == false) {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');
            } else {
                try {
                    $frm_details = $this->input->post();
                    $fields_client = array('clientname' => $frm_details['clientname'],
                        'clientmgr' => $frm_details['clientmgr'],
                        'sales_manager' => $frm_details['sales_manager'],
                        'created_by' => $this->user_info['id'],
                        'created_on' => date(DB_DATE_FORMAT),
                        'modified_on' => date(DB_DATE_FORMAT),
                        'modified_by' => $this->user_info['id'],
                        'status' => STATUS_ACTIVE,
                    );
                    $fields_client = array_map('strtolower', $fields_client);

                    $error_msgs = $aggr_array = array();

                    if (!empty($_FILES['comp_logo']['name'])) {
                        $file_upload_path = CLIENT_CONST['CLIENT_LOGO_PATH'];
                        if (!folder_exist($file_upload_path)) {
                            mkdir($file_upload_path, 0777);
                        }

                        $file_name = str_replace(' ', '_', $_FILES['comp_logo']['name']);
                        $file_info = pathinfo($file_name);

                        $new_file_name = preg_replace('/[[:space:]]+/', '_', $file_info['filename']);

                        $new_file_name = $new_file_name . '_' . DATE(UPLOAD_FILE_DATE_FORMAT);

                        $new_file_name = str_replace('.', '_', $new_file_name);

                        $file_extension = $file_info['extension'];

                        $new_file_name = $new_file_name . '.' . $file_extension;

                        $_FILES['logo']['name'] = $new_file_name;

                        $_FILES['logo']['tmp_name'] = $_FILES['comp_logo']['tmp_name'];

                        $_FILES['logo']['error'] = $_FILES['comp_logo']['error'];

                        $_FILES['logo']['size'] = $_FILES['comp_logo']['size'];

                        $config['upload_path'] = $file_upload_path;

                        $config['file_ext_tolower'] = true;

                        $config['max_size'] = BULK_UPLOAD_MAX_SIZE_MB * 2000;

                        $config['allowed_types'] = 'jpeg|jpg|png';

                        $config['remove_spaces'] = true;

                        $config['overwrite'] = false;

                        $config['file_name'] = $new_file_name;

                        $this->load->library('upload', $config);

                        $this->upload->initialize($config);

                        if ($this->upload->do_upload('logo')) {
                            $fields_client['comp_logo'] = $new_file_name;

                            $uploaded_file_name = $this->upload->data('file_name');

                            $config = array();

                            $config['image_library'] = 'gd2';

                            $config['source_image'] = $file_upload_path . '/' . $uploaded_file_name;

                            $config['maintain_ratio'] = false;

                            $config['width'] = 497;

                            $config['height'] = 280;

                            $this->load->library('image_lib', $config);

                            $this->image_lib->resize();
                        } else {
                            $json_array['upload_error'] = $this->upload->display_errors('', '');
                        }
                    }

                    $insert_id = $this->clients_model->save($fields_client);

                    $aggr_start = $frm_details['aggr_start'];
                    $aggr_end = $frm_details['aggr_end'];
                    if (count($aggr_start) > 0) {
                        try {
                            for ($i = 0; $i < count($aggr_start); $i++) {
                                if ($aggr_start[$i] != "") {
                                    $file_upload_path = CLIENT_CONST['CLIENT_AGGREMENT_PATH'];

                                    if (!folder_exist($file_upload_path)) {
                                        mkdir($file_upload_path, 0777);
                                    }
                                    $aggr = '';

                                    if (!empty($_FILES['aggrement_file']['name'])) {
                                        $file_name = str_replace(' ', '_', $_FILES['aggrement_file']['name'][$i]);

                                        $file_info = pathinfo($file_name);

                                        $new_file_name = preg_replace('/[[:space:]]+/', '_', $file_info['filename']);

                                        $new_file_name = $new_file_name . '_' . DATE(UPLOAD_FILE_DATE_FORMAT);

                                        $new_file_name = str_replace('.', '_', $new_file_name);

                                        if (isset($file_info['extension'])) {
                                            $file_extension = $file_info['extension'];
                                        } else {
                                            $file_extension = "";
                                        }
                                        $new_file_name = $new_file_name . '.' . $file_extension;

                                        $_FILES['aggrement_file_']['name'] = $new_file_name;

                                        $_FILES['aggrement_file_']['tmp_name'] = $_FILES['aggrement_file']['tmp_name'][$i];

                                        $_FILES['aggrement_file_']['error'] = $_FILES['aggrement_file']['error'][$i];

                                        $_FILES['aggrement_file_']['size'] = $_FILES['aggrement_file']['size'][$i];

                                        $config['upload_path'] = $file_upload_path;

                                        $config['file_ext_tolower'] = true;

                                        $config['max_size'] = BULK_UPLOAD_MAX_SIZE_MB * 10000;

                                        $config['allowed_types'] = 'pdf';

                                        $config['remove_spaces'] = true;

                                        $config['overwrite'] = false;

                                        $config['file_name'] = $new_file_name;

                                        $this->load->library('upload', $config);

                                        $this->upload->initialize($config);

                                        if ($this->upload->do_upload('aggrement_file_')) {
                                            $aggr = $new_file_name;
                                        }
                                    }

                                    $aggr_array[] = array('aggr_start' => convert_display_to_db_date($aggr_start[$i]),
                                        'aggr_end' =>
                                        convert_display_to_db_date($aggr_end[$i]),
                                        'aggrement_file' => $aggr,
                                        'client_id' => $insert_id);
                                }
                            }
                        } catch (Exception $e) {
                            log_message('error', 'Error on client::save_clients');
                            log_message('error', $e->getMessage());
                        }
                    }

                    if ($insert_id) {
                        if (!empty($aggr_array)) {
                            $this->db->insert_batch('client_aggr_details', $aggr_array);
                        }
                        $json_array['status'] = SUCCESS_CODE;

                        $json_array['message'] = "Client Created Successfully";

                        $json_array['redirect'] = ADMIN_SITE_URL . 'clients/view_details/' . encrypt($insert_id);

                        $json_array['return_client_id'] = $insert_id;

                    } else {
                        $json_array['client_id'] = 0;
                        $json_array['status'] = ERROR_CODE;
                        $json_array['message'] = 'Something went wrong,please try again';
                    }
                } catch (Exception $e) {
                    log_message('error', 'Error on client::save_clients');
                    log_message('error', $e->getMessage());
                }
            }
            echo_json($json_array);
        }
    }

    public function client_aggrement_view($client_id = false)
    {
        if ($this->input->is_ajax_request()) {
            $data['client_aggr_details'] = array();
            if ($client_id) {
                $data['client_aggr_details'] = $this->clients_model->select_aggrement(true, array('*'), array('client_id' => $client_id));
            }
            echo $this->load->view('admin/client_aggrement_view', $data, true);
        }
    }

    public function save_entity_package()
    {
        if ($this->input->is_ajax_request()) {

            $this->form_validation->set_rules('entity_package', 'Select any one', 'required');
            $this->form_validation->set_rules('tbl_client_id', 'Client ID', 'required');

            if ($this->form_validation->run() == false) {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');
            } else {

                $frm_details = $this->input->post();

                if ($frm_details['entity_package'] == 'isEntity') {

                    $entity_package1 = $this->input->post('Entity');

                    $this->form_validation->set_rules('Entity', 'entity package', 'is_unique[entity_package.entity_package_name]');
                    // $this->form_validation->set_rules('tbl_client_id', 'entity package', 'is_unique[entit_package.tbl_client_id]');
                    $this->form_validation->set_message('is_unique', "$entity_package1 Entity name taken, please try another.");

                    if ($this->form_validation->run() == false) {
                        $json_array['status'] = ERROR_CODE;

                        $json_array['message'] = validation_errors('', '');
                        echo_json($json_array);
                    } else {

                        $count_enty = explode(',', $frm_details['Entity']);
                        for ($i = 0; $i < count($count_enty); $i++) {
                            $fields[] = array('entity_package_name' => strtolower(trim($count_enty[$i])),
                                'tbl_client_id' => $frm_details['tbl_client_id'],
                                'is_entity' => 1,
                                'is_entity_package' => 1,
                                'status' => STATUS_ACTIVE,
                                'created_by' => $this->user_info['id'],
                            );
                        }
                        $fields = array_map("unserialize", array_unique(array_map("serialize", $fields)));
                        $insert_status = $this->common_model->common_insert_batch('entity_package', $fields);
                        $json_array['message'] = "Entity Insert Successfully, Create packages";
                        $json_array['entity_list'] = $this->get_entiry_package_list(array('is_entity' => 1, 'is_entity_package' => 1, 'tbl_client_id' => $frm_details['tbl_client_id']));
                        $json_array['list_show'] = 0;

                    }
                } else {

                    $entity_package2 = $this->input->post('package');
                    $this->form_validation->set_rules('package', 'entity package3', 'is_unique[entity_package.entity_package_name]');
                    $this->form_validation->set_message('is_unique', "$entity_package2 Package name taken, please try another.");
                    if ($this->form_validation->run() == false) {
                        $json_array['status'] = ERROR_CODE;

                        $json_array['message'] = validation_errors('', '');

                        echo_json($json_array);
                    } else {

                        $fields = array('entity_package_name' => $frm_details['package'],
                            'tbl_client_id' => $frm_details['tbl_client_id'],
                            'is_entity' => $frm_details['entity_list'],
                            'is_entity_package' => 2,
                            'status' => STATUS_ACTIVE,
                            'created_by' => $this->user_info['id'],
                            'created_on' => date(DB_DATE_FORMAT),
                        );
                        $fields = array_map('strtolower', $fields);

                        $json_array['message'] = "Package Insert Successfully";

                        $insert_status = $this->common_model->save('entity_package', $fields);

                        if ($frm_details['copy'] == 1) {
                            $result = $this->clients_model->copy_package($frm_details['tbl_client_id'], $frm_details['entity_list'], $insert_status);
                        }

                        $json_array['list_show'] = 1;
                        $json_array['entity_package_list'] = $this->clients_model->entity_packageList(array('entity_package.tbl_client_id' => $frm_details['tbl_client_id']));
                    }
                }

                if ($insert_status) {
                    $json_array['status'] = SUCCESS_CODE;

                } else {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = "Something went wrong, please try again";
                }
            }
            echo_json($json_array);
        }
    }

    public function update_client()
    {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('update_id', 'ID', 'required');

            $this->form_validation->set_rules('clientname', 'Client Name', 'required');

            if ($this->form_validation->run() == false) {
                $json_array['status'] = ERROR_CODE;
                $json_array['message'] = validation_errors('', '');
            } else {
                try {

                    $frm_details = $this->input->post();
                    log_message('error', 'frm_details');
                    log_message('error', print_r($frm_details, true));
                     
                    $client_manager_id = $this->clients_model->select_client_manager_details(array('id'=> $frm_details['update_id']));
                    if($client_manager_id[0]['clientmgr'] != $frm_details['clientmgr'])
                    {
                       
                        $this->load->model('Addressver_model');
                        $result_address = $this->Addressver_model->save(array('has_case_id' => $frm_details['clientmgr']), array('clientid' => $frm_details['update_id']));

                        $this->load->model('Employment_model');
                        $result_employment = $this->Employment_model->save(array('has_case_id' => $frm_details['clientmgr']), array('clientid' => $frm_details['update_id']));

                        $this->load->model('Education_model');
                        $result_education = $this->Education_model->save(array('has_case_id' => $frm_details['clientmgr']), array('clientid' => $frm_details['update_id']));

                        $this->load->model('Reference_verificatiion_model');
                        $result_reference = $this->Reference_verificatiion_model->save(array('has_case_id' => $frm_details['clientmgr']), array('clientid' => $frm_details['update_id']));

                        $this->load->model('Court_verificatiion_model');
                        $result_court = $this->Court_verificatiion_model->save(array('has_case_id' => $frm_details['clientmgr']), array('clientid' => $frm_details['update_id']));

                        $this->load->model('Global_database_model');
                        $result_global = $this->Global_database_model->save(array('has_case_id' => $frm_details['clientmgr']), array('clientid' => $frm_details['update_id']));

                        $this->load->model('Pcc_verificatiion_model');
                        $result_pcc = $this->Pcc_verificatiion_model->save(array('has_case_id' => $frm_details['clientmgr']), array('clientid' => $frm_details['update_id']));

                        $this->load->model('Identity_model');
                        $result_identity = $this->Identity_model->save(array('has_case_id' => $frm_details['clientmgr']), array('clientid' => $frm_details['update_id']));

                        $this->load->model('Credit_report_model');
                        $result_credit_report = $this->Credit_report_model->save(array('has_case_id' => $frm_details['clientmgr']), array('clientid' => $frm_details['update_id']));

                        $this->load->model('drug_verificatiion_model');
                        $result_drugs = $this->drug_verificatiion_model->save(array('has_case_id' => $frm_details['clientmgr']), array('clientid' => $frm_details['update_id']));

                    };
 
                    $fields_client = array('clientname' => $frm_details['clientname'],
                        'clientmgr' => $frm_details['clientmgr'],
                        'sales_manager' =>$frm_details['sales_manager'],
                        'modified_on' => date(DB_DATE_FORMAT),
                        'modified_by' => $this->user_info['id'],
                    );
                    $fields_client = array_map('strtolower', $fields_client);

                    $error_msgs = $aggr_array = array();

                    if (!empty($_FILES['comp_logo']['name'])) {
                        $file_upload_path = CLIENT_CONST['CLIENT_LOGO_PATH'];
                        if (!folder_exist($file_upload_path)) {
                            mkdir($file_upload_path, 0777);
                        }

                        $file_name = str_replace(' ', '_', $_FILES['comp_logo']['name']);

                        $file_info = pathinfo($file_name);

                        $new_file_name = preg_replace('/[[:space:]]+/', '_', $file_info['filename']);

                        $new_file_name = $new_file_name . '_' . DATE(UPLOAD_FILE_DATE_FORMAT);

                        $new_file_name = str_replace('.', '_', $new_file_name);

                        $file_extension = $file_info['extension'];

                        $new_file_name = $new_file_name . '.' . $file_extension;

                        $_FILES['logo']['name'] = $new_file_name;

                        $_FILES['logo']['tmp_name'] = $_FILES['comp_logo']['tmp_name'];

                        $_FILES['logo']['error'] = $_FILES['comp_logo']['error'];

                        $_FILES['logo']['size'] = $_FILES['comp_logo']['size'];

                        $config['upload_path'] = $file_upload_path;

                        $config['file_ext_tolower'] = true;

                        $config['max_size'] = BULK_UPLOAD_MAX_SIZE_MB * 2000;

                        $config['allowed_types'] = 'jpeg|jpg|png';

                        $config['remove_spaces'] = true;

                        $config['overwrite'] = false;

                        $config['file_name'] = $new_file_name;

                        $this->load->library('upload', $config);

                        $this->upload->initialize($config);

                        if ($this->upload->do_upload('logo')) {
                            $fields_client['comp_logo'] = $new_file_name;

                            $uploaded_file_name = $this->upload->data('file_name');

                            $config = array();

                            $config['image_library'] = 'gd2';

                            $config['source_image'] = $file_upload_path . '/' . $uploaded_file_name;

                            $config['maintain_ratio'] = false;

                            $config['width'] = 497;

                            $config['height'] = 280;

                            $this->load->library('image_lib', $config);

                            $this->image_lib->resize();
                        } else {
                            $json_array['upload_error'] = $this->upload->display_errors('', '');
                        }
                    }

                    $insert_id = $this->clients_model->save($fields_client, array('id' => $frm_details['update_id']));

                    $aggr_start = $frm_details['aggr_start'];
                    $aggr_end = $frm_details['aggr_end'];
                    if (count($aggr_start) > 0) {

                        for ($i = 0; $i < count($aggr_start); $i++) {
                            if ($aggr_start[$i] != "") {
                                $file_upload_path = CLIENT_CONST['CLIENT_AGGREMENT_PATH'];

                                if (!folder_exist($file_upload_path)) {
                                    mkdir($file_upload_path, 0777);
                                }
                                $aggr = '';

                                if (!empty($_FILES['aggrement_file']['name'][0])) {

                                    $file_name = str_replace(' ', '_', $_FILES['aggrement_file']['name'][$i]);

                                    $file_info = pathinfo($file_name);

                                    $new_file_name = preg_replace('/[[:space:]]+/', '_', $file_info['filename']);

                                    $new_file_name = $new_file_name . '_' . DATE(UPLOAD_FILE_DATE_FORMAT);

                                    $new_file_name = str_replace('.', '_', $new_file_name);

                                    if (isset($file_info['extension'])) {
                                        $file_extension = $file_info['extension'];
                                    } else {
                                        $file_extension = "";
                                    }

                                    $new_file_name = $new_file_name . '.' . $file_extension;

                                    $_FILES['aggrement_file_']['name'] = $new_file_name;

                                    $_FILES['aggrement_file_']['tmp_name'] = $_FILES['aggrement_file']['tmp_name'][$i];

                                    $_FILES['aggrement_file_']['error'] = $_FILES['aggrement_file']['error'][$i];

                                    $_FILES['aggrement_file_']['size'] = $_FILES['aggrement_file']['size'][$i];

                                    $config['upload_path'] = $file_upload_path;

                                    $config['file_ext_tolower'] = true;

                                    $config['max_size'] = BULK_UPLOAD_MAX_SIZE_MB * 2000;

                                    $config['allowed_types'] = 'pdf';

                                    $config['remove_spaces'] = true;

                                    $config['overwrite'] = false;

                                    $config['file_name'] = $new_file_name;

                                    $this->load->library('upload', $config);

                                    $this->upload->initialize($config);

                                    if ($this->upload->do_upload('aggrement_file_')) {
                                        $aggr = $new_file_name;
                                    }

                                }
                                $aggr_array[] = array('aggr_start' => convert_display_to_db_date($aggr_start[$i]),
                                    'aggr_end' =>
                                    convert_display_to_db_date($aggr_end[$i]),
                                    'aggrement_file' => $aggr,
                                    'client_id' => $frm_details['update_id']);

                                if (!empty($aggr_array)) {
                                    $this->db->insert_batch('client_aggr_details', $aggr_array);
                                }

                            }
                        }
                    }

                    if (count($frm_details['client_email_id']) > 0 && count($frm_details['client_password']) > 0) {

                        $login_details = array();

                        for ($i = 0; $i < count($frm_details['client_email_id']); $i++) {

                            if ($frm_details['client_email_id'][$i] != '' && $frm_details['client_password'][$i] != "") {

                                if ($frm_details['client_login_id'][$i] == '') {

                                    $client_entity_access = implode(",", $frm_details['client_entity_access'][$i]);
                                    $login_details[] = array(
                                        'client_id' => $frm_details['update_id'],
                                        'first_name' => $frm_details['client_first_name'][$i],
                                        'email_id' => $frm_details['client_email_id'][$i],
                                        'password' => create_password($frm_details['client_password'][$i]),
                                        'mobile_no' => $frm_details['client_mobileno'][$i],
                                        'client_entity_access' => $client_entity_access,
                                        'role' => 1,
                                        'status' => STATUS_ACTIVE,
                                        'creted_on' => date(DB_DATE_FORMAT),
                                        'created_by' => $this->user_info['id'],
                                    );

                                } else {

                                    for ($x = 0; $x < count($frm_details['client_entity_access'][$i]); $x++) {

                                        $client_entity_access1 = implode(",", $frm_details['client_entity_access'][$i]);

                                        $login_details_update = array(
                                            'first_name' => $frm_details['client_first_name'][$i],
                                            'email_id' => $frm_details['client_email_id'][$i],
                                            'password' => create_password($frm_details['client_password'][$i]),
                                            'mobile_no' => $frm_details['client_mobileno'][$i],
                                            'client_entity_access' => $client_entity_access1,
                                            'role' => 1,
                                            'status' => STATUS_ACTIVE,
                                        );

                                        $this->clients_model->update_batch_update_client($login_details_update, array('id' => $frm_details['client_login_id'][$i]));
                                    }
                                }
                            }
                            elseif($frm_details['client_email_id'][$i] != '')
                            {
                                if ($frm_details['client_login_id'][$i] == '') {

                                    $client_entity_access = implode(",", $frm_details['client_entity_access'][$i]);
                                    $login_details[] = array(
                                        'client_id' => $frm_details['update_id'],
                                        'first_name' => $frm_details['client_first_name'][$i],
                                        'email_id' => $frm_details['client_email_id'][$i],
                                        'mobile_no' => $frm_details['client_mobileno'][$i],
                                        'client_entity_access' => $client_entity_access,
                                        'role' => 1,
                                        'status' => STATUS_ACTIVE,
                                        'creted_on' => date(DB_DATE_FORMAT),
                                        'created_by' => $this->user_info['id'],
                                    );

                                } else {

                                    for ($x = 0; $x < count($frm_details['client_entity_access'][$i]); $x++) {

                                        $client_entity_access1 = implode(",", $frm_details['client_entity_access'][$i]);

                                        $login_details_update = array(
                                            'first_name' => $frm_details['client_first_name'][$i],
                                            'email_id' => $frm_details['client_email_id'][$i],
                                            'mobile_no' => $frm_details['client_mobileno'][$i],
                                            'client_entity_access' => $client_entity_access1,
                                            'role' => 1,
                                            'status' => STATUS_ACTIVE,
                                        );

                                        $this->clients_model->update_batch_update_client($login_details_update, array('id' => $frm_details['client_login_id'][$i]));
                                    }
                                }

                            }
                        }

                        if (!empty($login_details)) {
                            $this->clients_model->insert_batch_client_login($login_details);
                        }
                    }

                    if ($insert_id) {
                        $json_array['status'] = SUCCESS_CODE;
                        $json_array['message'] = 'Record Updated Successfully';
                        $json_array['return_client_id'] = $frm_details['update_id'];
                    } else {
                        $json_array['client_id'] = 0;
                        $json_array['status'] = ERROR_CODE;
                        $json_array['message'] = 'Something went wrong,please try again';
                    }
                } catch (Exception $e) {
                    log_message('error', 'Error on Client::update_client');
                    log_message('error', $e->getMessage());
                }
            }
            echo_json($json_array);
        }
    }

    public function entity_package_view()
    {
        if ($this->input->is_ajax_request()) {
            $data['entity_list'] = $this->get_entiry_package_list();
            $data['client_list'] = $this->get_clients();
            echo $this->load->view('admin/entity_package_view', $data, true);
        }

    }

    public function frm_entity_package_view()
    {
        if ($this->input->is_ajax_request()) {
            try {
                $data['frm_details'] = $this->input->post();
                $this->load->model('SLA_default_setting_model');
                $data['scope_of_word'] = $this->scope_of_work_by_component();
                $data['mode_of_veri'] = $this->mode_of_veri_component();
                $data['components'] = $this->components();
                $data['states'] = $this->get_states();
                echo $this->load->view('admin/frm_entity_package_view', $data, true);
            } catch (Exception $e) {
                log_message('error', 'Error on client::frm_entity_package_view');
                log_message('error', $e->getMessage());
            }
        }
    }

    public function frm_edit_entity_package_view()
    {
        if ($this->input->is_ajax_request()) {
            try {
                $frm_details = $this->input->post();

                $this->load->model('SLA_default_setting_model');

                $data['scope_of_word'] = $this->scope_of_work_by_component();

                $data['mode_of_veri'] = $this->mode_of_veri_component();

                $data['components'] = $this->components();

                $data['states'] = $this->get_states();

                $data['user_name'] = $this->users_list_limited();

                $data['client_details'] = $this->clients_model->select_clients_details(true, array('entity' => $frm_details['entity'], 'package' => $frm_details['package'], 'tbl_clients_id' => $frm_details['client_id']));
      
                $data['client_entity_package_details'] = array('tbl_clients_id' => $frm_details['client_id'], 'entity' => $frm_details['entity'], 'package' => $frm_details['package']);
                
                if(!empty($data['client_details']))
                {

                    $data['client_spoc_details'] = $this->clients_model->select_clients_spoc_details(array('clients_details_id' => $data['client_details']['id']));
                }

                echo $this->load->view('admin/frm_edit_entity_package_view', $data, true);
            } catch (Exception $e) {
                log_message('error', 'Error on client::frm_edit_entity_package_view');
                log_message('error', $e->getMessage());
            }
        }
    }

    public function sales_manager_list()
    {
        $lists = $this->common_model->select('sales_login', false, array("id,concat(first_name,' ',last_name) as fullname
            "), array('status' => STATUS_ACTIVE));

        return convert_to_single_dimension_array($lists, 'id', 'fullname');
    }

    public function sales_manager_list_limited()
    {
        $lists = $this->common_model->select('user_profile', false, array("id,concat(firstname,' ',lastname) as fullname
            ", ), array('status' => 1));
        return convert_to_single_dimension_array($lists, 'id', 'fullname');
    }

    public function entity_list()
    {
        $lists = $this->common_model->select('entity_package', false, 'entity_package_name', array('status' => 1, 'is_entity' => 1));

        return convert_to_single_dimension_array($lists, 'id', 'fullname');
    }

    public function users_list_limited()
    {
        $lists = $this->common_model->select('user_profile', false, array("id,concat(firstname,' ',lastname) as fullname
            ", ), array('status' => 1));
        return convert_to_single_dimension_array($lists, 'id', 'fullname');
    }

    public function client_portal_view()
    {
        if ($this->input->is_ajax_request()) {
            $client_id = $this->input->post('tbl_client_id');
            $data['entityList'] = $this->clients_model->entityList(array('entity_package.tbl_client_id' => $client_id, 'entity_package.is_entity_package' => 2));
            $data['client_logins'] = $this->clients_model->client_details(array('client_login.client_id' => $client_id));
            echo $this->load->view('admin/client_portal_modal_view', $data, true);
        }
    }

    protected function scope_of_work_by_component()
    {
        $result = $this->SLA_default_setting_model->scope_of_work_group_by();
        $return_arry = array();
        foreach ($result as $key => $value) {

            $ids = array_map('strval', explode(',', $value['id']));

            $scop_of_word = array_map('ucwords', explode(',', $value['scop_of_word']));

            $combine = array_combine($ids, $scop_of_word);

            $return_arry[$value['component_key']] = $combine;
        }
        return $return_arry;
    }

    protected function mode_of_veri_component()
    {
        $result = $this->SLA_default_setting_model->mode_of_veri_group_by();

        $return_arry = array();

        foreach ($result as $key => $value) {

            $ids = array_map('strval', explode(',', $value['id']));

            $mode_of_verification = array_map('ucwords', explode(',', $value['mode_of_verification']));

            $combine = array_combine($ids, $mode_of_verification);

            $return_arry[$value['component_key']] = $combine;
        }
        return $return_arry;
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

    public function get_entity_list1()
    {
        if ($this->input->is_ajax_request()) {
            $entity_list = $this->get_entiry_list1(array('tbl_client_id' => $this->input->post('clientid'), 'is_entity_package' => 1));
            unset($entity_list[0]);
            echo form_dropdown('entity', $entity_list, set_value('package', $this->input->post('selected_entityy')), 'class="form-control" id="entity"');
        }
    }

    public function save_clients_component()
    {
        if ($this->input->is_ajax_request()) {
            try {
                $frm_details = $this->input->post();
                print_r( $frm_details);exit();
                log_message('error', print_r($frm_details, true));
                if (in_array("addrver", $frm_details['components'])) {
                    $this->form_validation->set_rules('tat_addrver', 'Address Tat', 'required|is_natural_no_zero');
                }

                if (in_array("courtver", $frm_details['components'])) {
                    $this->form_validation->set_rules('tat_courtver', 'Court Tat', 'required|is_natural_no_zero');
                }

                if (in_array("globdbver", $frm_details['components'])) {
                    $this->form_validation->set_rules('tat_globdbver', 'Global Tat', 'required|is_natural_no_zero');
                }
                if (in_array("narcver", $frm_details['components'])) {
                    $this->form_validation->set_rules('tat_narcver', 'Drugs Tat', 'required|is_natural_no_zero');
                }

                if (in_array("refver", $frm_details['components'])) {
                    $this->form_validation->set_rules('tat_refver', 'Reference Tat', 'required|is_natural_no_zero');
                }

                if (in_array("empver", $frm_details['components'])) {
                    $this->form_validation->set_rules('tat_empver', 'Employee Tat', 'required|is_natural_no_zero');
                }

                if (in_array("eduver", $frm_details['components'])) {
                    $this->form_validation->set_rules('tat_eduver', 'Education Tat', 'required|is_natural_no_zero');
                }

                if (in_array("identity", $frm_details['components'])) {
                    $this->form_validation->set_rules('tat_identity', 'Identity Tat', 'required|is_natural_no_zero');
                }

                if (in_array("cbrver", $frm_details['components'])) {
                    $this->form_validation->set_rules('tat_cbrver', 'Credit  Tat', 'required|is_natural_no_zero');
                }

                if (in_array("crimver", $frm_details['components'])) {
                    $this->form_validation->set_rules('tat_crimver', 'Crime Tat', 'required|is_natural_no_zero');
                }

                if (in_array("social_media", $frm_details['components'])) {
                    $this->form_validation->set_rules('social_media', 'Social Media Tat', 'required|is_natural_no_zero');
                }
            } catch (Exception $e) {
                log_message('error', 'Error on client::save_clients_component');
                log_message('error', $e->getMessage());
            }
            log_message('error', 'Validated Successfully');

            $this->form_validation->set_rules('client_id', 'Client Name', 'required');

            $this->form_validation->set_rules('selected_entity', 'Entity', 'required');

            $this->form_validation->set_rules('selected_package', 'Package', 'required');

            if ($this->form_validation->run() == false) {
                $json_array['status'] = ERROR_CODE;
                $json_array['message'] = validation_errors('', '');
            } else {
                try {
                    $fields_entity[] = array(
                        'created_by' => $this->user_info['id'],
                        'created_on' => date(DB_DATE_FORMAT),
                        'modified_on' => date(DB_DATE_FORMAT),
                        'modified_by' => $this->user_info['id'],
                        'tbl_clients_id' => $frm_details['client_id'],
                        'clientaddress' => $frm_details['clientaddress'],
                        'clientcity' => $frm_details['clientcity'],
                        'clientpincode' => $frm_details['clientpincode'],
                        'clientstate' => $frm_details['clientstate'],
                        'report_type' => $frm_details['report_type'],
                        'component_id' => implode(',', $frm_details['components']),
                        'component_name' => $this->components_key_val($frm_details['component_name']),
                        'scope_of_work' => $this->components_key_val($frm_details['scope_of_work']),
                        'mode_of_verification' => $this->components_key_val($frm_details['mode_of_verification']),
                        'tat_addrver' => $frm_details['tat_addrver'],
                        'tat_empver' => $frm_details['tat_empver'],
                        'tat_eduver' => $frm_details['tat_eduver'],
                        'tat_refver' => $frm_details['tat_refver'],
                        'tat_courtver' => $frm_details['tat_courtver'],
                        'tat_globdbver' => $frm_details['tat_globdbver'],
                        'tat_narcver' => $frm_details['tat_narcver'],
                        'tat_crimver' => $frm_details['tat_crimver'],
                        'tat_identity' => $frm_details['tat_identity'],
                        'tat_cbrver' => $frm_details['tat_cbrver'],
                        'tat_social_media' => $frm_details['tat_social_media'],
                        'price' => $this->components_key_val($frm_details['price']),
                        'entity' => $frm_details['selected_entity'],
                        'package' => $frm_details['selected_package'],
                        'package_amount' => $frm_details['package_amount'],
                    );

                    $result = $this->clients_model->insert_batch_client_details($fields_entity);
                    if ($result) {
                        if ($frm_details['spoc_name'] != "" && $frm_details['spoc_email'] != "") {
                            $spoc = array();
                            $loop = count($frm_details['spoc_name']);
                            for ($i = 0; $i < $loop; $i++) {
                                if ($frm_details['spoc_name'][$i] != "" && $frm_details['spoc_email'][$i] != "") {
                                    $spoc[] = array('spoc_name' => $frm_details['spoc_name'][$i], 'spoc_email' => $frm_details['spoc_email'][$i], 'spoc_mobile' => $frm_details['spoc_mobile_no'][$i], 'spoc_manager_email' => $frm_details['spoc_manager_email'][$i], 'clients_details_id' => $result);
                                }
                            }
                            if (!empty($spoc)) {
                                $spoc = array_filter(array_map('array_filter', $spoc));
                                $this->db->insert_batch('client_spoc_details', $spoc);
                            }
                        }

                        $json_array['status'] = SUCCESS_CODE;
                        $json_array['message'] = 'Client Created Successfully';
                        $json_array['redirect'] = ADMIN_SITE_URL . 'clients';
                    } else {
                        $json_array['status'] = ERROR_CODE;
                        $json_array['message'] = 'Something went wrong, details not inserted';
                    }
                } catch (Exception $e) {
                    log_message('error', 'Error on client::save_clients_component');
                    log_message('error', $e->getMessage());
                }
            }
            echo_json($json_array);
        }
    }

    public function update_clients_component()
    {

        if ($this->input->is_ajax_request()) {

            $frm_details = $this->input->post();

            if (in_array("addrver", $frm_details['components'])) {
                $this->form_validation->set_rules('tat_addrver', 'Address Tat', 'required|is_natural_no_zero');
            }

            if (in_array("courtver", $frm_details['components'])) {
                $this->form_validation->set_rules('tat_courtver', 'Court Tat', 'required|is_natural_no_zero');
            }

            if (in_array("globdbver", $frm_details['components'])) {
                $this->form_validation->set_rules('tat_globdbver', 'Global Tat', 'required|is_natural_no_zero');
            }

            if (in_array("narcver", $frm_details['components'])) {
                $this->form_validation->set_rules('tat_narcver', 'Drugs Tat', 'required|is_natural_no_zero');
            }

            if (in_array("refver", $frm_details['components'])) {
                $this->form_validation->set_rules('tat_refver', 'Reference Tat', 'required|is_natural_no_zero');
            }

            if (in_array("empver", $frm_details['components'])) {
                $this->form_validation->set_rules('tat_empver', 'Employee Tat', 'required|is_natural_no_zero');
            }

            if (in_array("eduver", $frm_details['components'])) {
                $this->form_validation->set_rules('tat_eduver', 'Education Tat', 'required|is_natural_no_zero');
            }

            if (in_array("identity", $frm_details['components'])) {
                $this->form_validation->set_rules('tat_identity', 'Identity Tat', 'required|is_natural_no_zero');
            }

            if (in_array("cbrver", $frm_details['components'])) {
                $this->form_validation->set_rules('tat_cbrver', 'Credit  Tat', 'required|is_natural_no_zero');
            }

            if (in_array("crimver", $frm_details['components'])) {
                $this->form_validation->set_rules('tat_crimver', 'Crime Tat', 'required|is_natural_no_zero');
            }

            if (in_array("social_media", $frm_details['components'])) {
                $this->form_validation->set_rules('tat_social_media', 'Social Media Tat', 'required|is_natural_no_zero');
            }

            $this->form_validation->set_rules('client_id', 'Client Name', 'required');

            $this->form_validation->set_rules('selected_entity', 'Entity', 'required');

            $this->form_validation->set_rules('selected_package', 'Package', 'required');

            if ($this->form_validation->run() == false) {
                $json_array['status'] = ERROR_CODE;
                $json_array['message'] = validation_errors('', '');
            } else {
                try {
                    $fields_entity = array(
                        'modified_on' => date(DB_DATE_FORMAT),
                        'modified_by' => $this->user_info['id'],
                        'tbl_clients_id' => $frm_details['client_id'],
                        'clientaddress' => $frm_details['clientaddress'],
                        'clientcity' => $frm_details['clientcity'],
                        'clientpincode' => $frm_details['clientpincode'],
                        'clientstate' => $frm_details['clientstate'],
                        'report_type' => $frm_details['report_type'],
                        'component_id' => implode(',', $frm_details['components']),
                        'component_name' => $this->components_key_val($frm_details['component_name']),
                        'scope_of_work' => $this->components_key_val($frm_details['scope_of_work']),
                        'mode_of_verification' => $this->components_key_val($frm_details['mode_of_verification']),
                        'tat_addrver' => $frm_details['tat_addrver'],
                        'tat_empver' => $frm_details['tat_empver'],
                        'tat_eduver' => $frm_details['tat_eduver'],
                        'tat_refver' => $frm_details['tat_refver'],
                        'tat_courtver' => $frm_details['tat_courtver'],
                        'tat_globdbver' => $frm_details['tat_globdbver'],
                        'tat_narcver' => $frm_details['tat_narcver'],
                        'tat_crimver' => $frm_details['tat_crimver'],
                        'tat_identity' => $frm_details['tat_identity'],
                        'tat_cbrver' => $frm_details['tat_cbrver'],
                        'tat_social_media' => $frm_details['tat_social_media'],
                        'price' => $this->components_key_val($frm_details['price']),
                        'entity' => $frm_details['selected_entity'],
                        'package' => $frm_details['selected_package'],
                        'package_amount' => $frm_details['package_amount'],
                        'candidate_report_type' => $frm_details['report_type'],
                        'candidate_add_component' => $frm_details['candidate_add'],
                        'final_qc' => $frm_details['final_qc'],
                        'auto_report' => $frm_details['auto_report'],
                        'insuff_report' => $frm_details['insuff_report'],
                        'case_type' => $frm_details['case_type'],
                        'billing_type' => $frm_details['billing_type'],
                        'client_disclosures' => $frm_details['client_disclosures'],
                        'candidate_upload' => $frm_details['candidate_upload'],
                        'user_upload' => $frm_details['user_upload'],
                        'case_activity' => $frm_details['case_activity']
                    );
                    if(isset($frm_details['candidate_component_count'])) {
                        $fields_entity['candidate_component_count'] = implode(',', $frm_details['candidate_component_count']); 
                     }
                     if(isset($frm_details['pre_component'])) {
                        $fields_entity['pre_component'] = implode(',', $frm_details['pre_component']); 
                     }
                     if(isset($frm_details['post_component'])) {
                        $fields_entity['post_component'] = implode(',', $frm_details['post_component']); 
                     }

                    if (!empty($frm_details['update_id'])) {
                        $result = $this->clients_model->clients_details_save($fields_entity, array('id' => $frm_details['update_id']));
                    } else {
                        $result = $this->clients_model->clients_details_save($fields_entity);
                    }

                    if ($result) {

                        if ($frm_details['spoc_name'] != "" && $frm_details['spoc_email'] != "") {
                            $spoc = array();
                            $loop = count($frm_details['spoc_name']);

                            for ($i = 0; $i < $loop; $i++) {
                                if ($frm_details['spoc_name'][$i] != "" && $frm_details['spoc_email'][$i] != "") {
                                   
                                  if (!empty($frm_details['update_id'])) {

                                    $check = $this->clients_model->select_client_spoc_email(array('id' => isset($frm_details['spoc_id'][$i])));
                                    if(empty($check))
                                    {
                                      $spoc[] = array('spoc_name' => $frm_details['spoc_name'][$i], 'spoc_email' => $frm_details['spoc_email'][$i], 'spoc_mobile' => $frm_details['spoc_mobile_no'][$i], 'spoc_manager_email' => $frm_details['spoc_manager_email'][$i], 'clients_details_id' => $frm_details['update_id']);
                                    }
                                    else{

                                        $spoc_upate = array('spoc_name' => $frm_details['spoc_name'][$i], 'spoc_email' => $frm_details['spoc_email'][$i], 'spoc_mobile' => $frm_details['spoc_mobile_no'][$i], 'spoc_manager_email' => $frm_details['spoc_manager_email'][$i]);
                                        
                                          
                                        $result_spoc_upate = $this->clients_model->update_spoc_detail($spoc_upate, array('id' => $frm_details['spoc_id'][$i]));

                                    }

                                   }
                                   else
                                   {
                                       $check = $this->clients_model->select_client_spoc_email(array('spoc_email' => $frm_details['spoc_email'][$i],'clients_details_id' => $result));
                                        if(empty($check))
                                        {
                                        $spoc[] = array('spoc_name' => $frm_details['spoc_name'][$i], 'spoc_email' => $frm_details['spoc_email'][$i], 'spoc_mobile' => $frm_details['spoc_mobile_no'][$i], 'spoc_manager_email' => $frm_details['spoc_manager_email'][$i], 'clients_details_id' => $result);
                                        }
                                   }
                                }
                            }
                            if (!empty($spoc)) {

                                $this->db->insert_batch('client_spoc_details', $spoc);
                            }
                        }

                        $json_array['status'] = SUCCESS_CODE;
                        $json_array['message'] = 'Client Details Updated Successfully';
                        $json_array['redirect'] = ADMIN_SITE_URL . 'clients';
                    } else {
                        $json_array['status'] = ERROR_CODE;
                        $json_array['message'] = 'Something went wrong, details not inserted';
                    }
                } catch (Exception $e) {
                    log_message('error', 'Error on client::update_clients_component');
                    log_message('error', $e->getMessage());
                }
            }
            echo_json($json_array);
        }
    }

    public function view_details($client_id = '')
    {
        $decrypt_id = decrypt($client_id);

        $client_details = $this->clients_model->select(true, array('*'), array('id' => $decrypt_id));

        if ($client_id != '' && !empty($client_details)) {
            $data['entity_list'] = $this->get_entiry_package_list(array('is_entity' => 1, 'tbl_client_id' => $decrypt_id));

            $data['clientmgr'] = $this->users_list_limited();

            $data['sales_manager'] = $this->sales_manager_list_limited();

            $data['client_details'] = $client_details;

            $data['entitypackages'] = $this->clients_model->entity_packageList(array('entity_package.tbl_client_id' => $decrypt_id, 'sub.is_entity_package' => "2"));

            $data['entityList'] = $this->clients_model->entityList(array('entity_package.tbl_client_id' => $decrypt_id, 'entity_package.is_entity' => 0));

            $data['attachments'] = $this->clients_model->select_file(array('id', 'aggrement_file', 'client_id'), array('client_id' => $client_details['id']));

            $data['logo'] = $this->clients_model->select_file_logo(array('id', 'clientname', 'status', 'comp_logo'), array('id' => $client_details['id'], 'status' => 1));

            $this->load->view('admin/header', $data);

            $this->load->view('admin/clients_edit');

            $this->load->view('admin/footer');
        } else {
            show_404();
        }
    }

    public function export_logs($id = null)
    {

        $client_details = $this->clients_model->select_logs(array('clients_logs.clients_id' => decrypt($id)));

        if (!empty($client_details)) {
            $file_name = $client_details[0]['clientname'] . '-' . now() . '.csv';

            export_to_csv($client_details, $file_name);
        } else {
            export_to_csv(array(), 'Empty-File.csv');
        }
    }

    public function edit_login_details($id)
    {
        $json_array = array();

        $result = $this->clients_model->client_details(array('client_login.id' => $id));

        if ($this->input->is_ajax_request() && $id != "" && !empty($result)) {
            $data['details'] = $result[0];
            echo "<h2>Working</h2>";
        } else {
            echo "<h2>Something went wrong, please try after something!</h2>";
        }
    }
    public function update_login_details()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {
            $frm_details = $this->input->post();

            if (!empty($frm_details)) {
                show($frm_details);
            }
        } else {
            $json_array['message'] = 'Something went wrong, please try again';

            $json_array['status'] = ERROR_CODE;
        }

        $this->echo_json($json_array);
    }

    public function delete_login_details()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('user_login_id');
            $client_id = $this->input->post('client_id');
            if (!empty($id)) {
                $return = $this->clients_model->client_login_save(array('status' => 0), array('id' => $id));

                if ($return) {
                    $json_array['message'] = 'Record Deleted Successfully';

                    $json_array['redirect'] = ADMIN_SITE_URL . 'clients/view_details/' . $client_id;

                    $json_array['status'] = SUCCESS_CODE;
                } else {
                    $json_array['message'] = 'Something went wrong, please try again';

                    $json_array['status'] = ERROR_CODE;
                }
            }
        } else {
            $json_array['message'] = 'Something went wrong, please try again';

            $json_array['status'] = ERROR_CODE;
        }

        $this->echo_json($json_array);
    }

    public function template_download()
    {
        try {
            $this->load->helper('download');
            force_download(CLIENTS_BULK_FILES . '/template.xlsx', null);
        } catch (Exception $e) {
            log_message('error', 'Error on client::template_download');
            log_message('error', $e->getMessage());
        }
        exit();
    }

    public function bulk_upload()
    {
        $data['header_title'] = "Bulk Upload Clients";

        $data['client_manager'] = $this->get_members_by_group();

        $this->load->view('admin/header', $data);

        $this->load->view('admin/clients_bulk_upload_view');

        $this->load->view('admin/footer');
    }

    public function bulk_upload_clients()
    {
        $data['header_title'] = 'Bulk Create Clients';

        if ($this->input->post('upload')) {
            try {
                $file_name = str_replace(' ', '_', $_FILES['user_bulk_sheet']['name']);

                $new_file_name = time() . "_" . $file_name;

                $_FILES['user_bulk_sheet']['name'] = $new_file_name;

                $upload_path = BULK_USER_UPLOAD_PATH;

                if (!folder_exist($upload_path)) {
                    mkdir($upload_path, 0777);
                }

                $config['upload_path'] = $upload_path;

                $config['file_ext_tolower'] = true;

                $config['max_size'] = BULK_UPLOAD_MAX_SIZE_MB * 2000;

                $config['allowed_types'] = '*';

                $config['remove_spaces'] = true;

                $config['overwrite'] = false;

                $config['file_name'] = $new_file_name;

                $this->load->library('upload', $config);

                $this->upload->initialize($config);

                if (!$this->upload->do_upload('user_bulk_sheet')) {
                    $data = array('error' => $this->upload->display_errors());
                } else {
                    $this->load->library('Excel_reader', array('file_name' => $upload_path . $new_file_name));

                    $excel_handler = $this->excel_reader->file_handler;

                    $excel_data = $excel_handler->rows();

                    if (!empty($excel_data)) {
                        unset($excel_data[0]);

                        unset($excel_data[1]);

                        $excel_data = array_map("unserialize", array_unique(array_map("serialize", $excel_data)));

                        $cc_company_details_id = $this->input->post('cc_company_details_id');

                        //$excel_data = array_filter(array_map('array_filter', $excel_data));
                        $this->load->model('ci_promo_code_model');

                        foreach ($excel_data as $value) {
                            if (count($value) < SELF::BULK_UPLOAD_SHEET_COLS) {
                                continue;
                            }

                            $activation_key = random_string('numeric', 10);

                            $user = array('cc_company_details_id' => $cc_company_details_id,
                                'first_name' => htmlentities(trim($value[0])),
                                'last_name' => htmlentities(trim($value[1])),
                                'user_email' => htmlentities(trim($value[2])),
                                'mobile' => htmlentities(trim($value[3])),
                                'promo_code' => htmlentities(trim($value[4])),
                                "redeem_date" => date(DB_ONLY_DATE_FORMAT),
                                'user_status' => STATUS_DEACTIVE,
                                'registration_source' => REGISTRATION_SOURCE_BULK,
                                'user_activation_key' => $activation_key,
                            );

                            $is_mobile_exists = $this->ci_users_model->is_mobile_exists($value[3]);

                            $is_email_exists = $this->ci_users_model->is_email_exists($value[2]);

                            $check_code_exits = $this->ci_promo_code_model->is_code_exits($value[4]);

                            if ($is_mobile_exists == 0 && $is_email_exists == 0) {
                                if ($check_code_exits === true) {
                                    //$bg_arry_push = array_push($bg_sms_arry,$value[3]);

                                    $this->ci_promo_code_model->validate_r_code($value[4]);

                                    $results = $this->ci_users_model->save($user);

                                    // $this->load->library('email');

                                    // $encrypt_insert_id = $this->default_m->encrypt($results);

                                    // $email_tmpl_data['subject']  = "McXtra Account Activation";

                                    // $email_tmpl_data['email_id']  =  $user['user_email'];

                                    // $email_tmpl_data['first_name'] = $user['first_name'];

                                    // $email_tmpl_data['last_name']  = $user['last_name'];

                                    // $email_tmpl_data['mobile_no']  = $user['mobile'];

                                    // $email_tmpl_data['verification_url']  = SITE_URL."myacc/activate_registration/$encrypt_insert_id/$activation_key";

                                    // $message = "Your McXtra Account is created. Details will send to email id";

                                    // $send_mail = $this->email->sendSms($user['mobile'], $message);

                                    // $this->email->send_registration_mail($email_tmpl_data);

                                    //exec(CLI_EXEC_PATH."myacc send_bgsms $mobile $message > /dev/null &");

                                    $data['success'][] = "The " . $user['user_email'] . " and " . $user['mobile'] . " Created Successfully";
                                } else {
                                    $data['fail'][] = "The " . $user['user_email'] . " and " . $user['mobile'] . " user code not exists";
                                }

                            } else {
                                $data['fail'][] = "The " . $user['user_email'] . " and " . $user['mobile'] . " exists";
                            }

                            // $mobile_nos = implode(',',$bg_arry_push);

                            // exec( CLI_EXEC_PATH."admin policy_holders send_bgsms $mobile_nos ' Your McXtra Account is created. Details will send to email id'  > /dev/null &" );
                        }
                    } else {
                        $data['fail'][] = "Excel sheet is blank";
                    }
                }
                $this->session->set_flashdata('data', $data);

                $this->load->view('admin/create_user', $data);
            } catch (Exception $e) {
                log_message('error', 'Error on client::bulk_upload_clients');
                log_message('error', $e->getMessage());
            }
        } else {
            $this->bulk_upload();

        }
    }

    protected function get_sales_manager()
    {
        $results = $this->common_model->select('sales_login', false, array('id', 'first_name', 'email_id'), array('status' => 1));

        $array[0] = 'Select Sales Manager';

        foreach ($results as $key => $value) {
            $array[$value['id']] = $value['first_name'] . "-" . ucwords(strtolower($value['email_id']));
        }
        return $array;
    }

    public function remove_logo_or_aggriment()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {

            $frm_details = $this->input->post();
            if (!empty($frm_details)) {

                $result = $this->clients_model->save(array($frm_details['name'] => ''), array('id' => $frm_details['clientid']));
                if ($result) {
                    $json_array['message'] = 'Deleted Successfully';
                    $json_array['deleted_msg'] = '#dlt_' . $frm_details['name'];
                    $json_array['status'] = SUCCESS_CODE;
                } else {
                    $json_array['message'] = 'Something went wrong, please try again';
                    $json_array['status'] = ERROR_CODE;
                }
            } else {
                $json_array['message'] = 'Something went wrong, please try again';
                $json_array['status'] = ERROR_CODE;
            }

            $this->echo_json($json_array);
        }
    }

    public function delete()
    {
        $client_id = $this->input->post('client_id');

        if ($this->input->is_ajax_request() && $this->permission['access_clients_list_delete'] == true) {
            try {
                $this->load->model('users_model');
                $is_exits = $this->users_model->user_role_gorup_details(array('user_profile.id' => decrypt($client_id)));
                log_message('error', 'Client ID '. $client_id);
                if ($client_id && !empty($is_exits)) {

                    $field_array = array('status' => STATUS_DELETED,
                        'modified_on' => date(DB_DATE_FORMAT),
                        'modified_by' => $this->user_info['id'],
                    );

                    $where_array = array('id' => $is_exits['id']);

                    if ($this->users_model->save($field_array, $where_array)) {
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
            } catch (Exception $e) {
                log_message('error', 'Error on client::delete');
                log_message('error', $e->getMessage());
            }
            echo_json($json_array);
        } else {
            permission_denied();
        }
    }

    public function save_sla()
    {
        $frm_details = $this->input->post();

        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('client_name_id', 'Client ID', 'required');

            if ($this->form_validation->run() == false) {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');
            } else {
                $frm_details = $this->input->post();

                $client_component = $frm_details['client_component'];

                if ($client_component == "Address") {

                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['address_client_name'], "selected_selection" => $frm_details['address_client_name_selection'], "remarks" => $frm_details['address_client_name_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));
                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['address_candidate_calling'], "selected_selection" => $frm_details['address_candidate_calling_selection'], "remarks" => $frm_details['address_candidate_calling_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));
                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['address_add_charges'], "selected_selection" => $frm_details['address_add_charges_selection'], "remarks" => $frm_details['address_add_charges_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));
                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['address_mode_of_verification'], "selected_selection" => $frm_details['address_mode_of_verification_selection'], "remarks" => $frm_details['address_mode_of_verification_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));
                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['address_display_matrix'], "selected_selection" => $frm_details['address_display_matrix_selection'], "remarks" => $frm_details['address_display_matrix_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));
                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['address_insufficiency'], "selected_selection" => $frm_details['address_insufficiency_selection'], "remarks" => $frm_details['address_insufficiency_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));
                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['address_unable_verify'], "selected_selection" => $frm_details['address_unable_verify_selection'], "remarks" => $frm_details['address_unable_verify_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));
                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['address_insufficiency_not_cleared'], "selected_selection" => $frm_details['address_insufficiency_not_cleared_selection'], "remarks" => $frm_details['address_insufficiency_not_cleared_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));
                }

                if ($client_component == "Employment") {

                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['employment_client_name'], "selected_selection" => $frm_details['employment_client_name_selection'], "remarks" => $frm_details['employment_client_name_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));
                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['employment_candidate_calling'], "selected_selection" => $frm_details['employment_candidate_calling_selection'], "remarks" => $frm_details['employment_candidate_calling_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));
                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['employment_add_charges'], "selected_selection" => $frm_details['employment_add_charges_selection'], "remarks" => $frm_details['employment_add_charges_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));
                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['employment_overseas_check'], "selected_selection" => $frm_details['employment_overseas_check_selection'], "remarks" => $frm_details['employment_overseas_check_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));
                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['employment_mode_of_verification'], "selected_selection" => $frm_details['employment_mode_of_verification_selection'], "remarks" => $frm_details['employment_mode_of_verification_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));
                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['employment_candidate_previously_employed'], "selected_selection" => $frm_details['employment_candidate_previously_employed_selection'], "remarks" => $frm_details['employment_candidate_previously_employed_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));
                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['employment_current'], "selected_selection" => $frm_details['employment_current_selection'], "remarks" => $frm_details['employment_current_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));
                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['employment_supervisor_calling'], "selected_selection" => $frm_details['emploment_supervisor_calling_selection'], "remarks" => $frm_details['emploment_supervisor_calling_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));
                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['employment_left_organization'], "selected_selection" => $frm_details['employment_left_organization_selection'], "remarks" => $frm_details['employment_left_organization_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));
                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['employment_site_visit'], "selected_selection" => $frm_details['employment_site_visit_selection'], "remarks" => $frm_details['employment_site_visit_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));
                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['emploment_display_matrix'], "selected_selection" => $frm_details['employment_display_matrix_selection'], "remarks" => $frm_details['employment_display_matrix_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));
                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['employment_insufficiency'], "selected_selection" => $frm_details['employment_insufficiency_selection'], "remarks" => $frm_details['employment_insufficiency_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));
                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['employment_unable_verify'], "selected_selection" => $frm_details['employment_unable_verify_selection'], "remarks" => $frm_details['employment_unable_verify_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));
                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['employment_insufficiency_not_cleared'], "selected_selection" => $frm_details['employment_insufficiency_not_cleared_selection'], "remarks" => $frm_details['employment_insufficiency_not_cleared_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));

                }

                if ($client_component == "Education") {

                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['education_overseas_degree'], "selected_selection" => $frm_details['education_overseas_degree_selection'], "remarks" => $frm_details['employment_overseas_degree_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));
                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['education_candidate_calling'], "selected_selection" => $frm_details['education_candidate_calling_selection'], "remarks" => $frm_details['education_candidate_calling_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));
                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['education_initiate_pursuing_degree'], "selected_selection" => $frm_details['education_initiate_pursuing_degree_selection'], "remarks" => $frm_details['education_initiate_pursuing_degree_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));
                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['education_add_charges'], "selected_selection" => $frm_details['education_add_charges_selection'], "remarks" => $frm_details['education_add_charges_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));
                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['education_mode_of_verification'], "selected_selection" => $frm_details['education_mode_of_verification_selection'], "remarks" => $frm_details['education_mode_of_verification_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));

                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['education_display_matrix'], "selected_selection" => $frm_details['education_display_matrix_selection'], "remarks" => $frm_details['education_display_matrix_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));
                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['education_insufficiency'], "selected_selection" => $frm_details['education_insufficiency_selection'], "remarks" => $frm_details['education_insufficiency_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));
                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['education_unable_verify'], "selected_selection" => $frm_details['education_unable_verify_selection'], "remarks" => $frm_details['education_unable_verify_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));
                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['education_insufficiency_not_cleared'], "selected_selection" => $frm_details['education_insufficiency_not_cleared_selection'], "remarks" => $frm_details['education_insufficiency_not_cleared_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));

                }
                if ($client_component == "References") {

                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['references_client_name'], "selected_selection" => $frm_details['references_client_name_selection'], "remarks" => $frm_details['references_client_name_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));
                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['references_candidate_calling'], "selected_selection" => $frm_details['references_candidate_calling_selection'], "remarks" => $frm_details['references_candidate_calling_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));
                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['references_overseas_check'], "selected_selection" => $frm_details['references_overseas_check_selection'], "remarks" => $frm_details['references_overseas_check_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));

                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['references_mode_of_verification'], "selected_selection" => $frm_details['references_mode_of_verification_selection'], "remarks" => $frm_details['references_mode_of_verification_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));

                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['references_display_matrix'], "selected_selection" => $frm_details['references_display_matrix_selection'], "remarks" => $frm_details['references_display_matrix_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));
                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['references_insufficiency'], "selected_selection" => $frm_details['references_insufficiency_selection'], "remarks" => $frm_details['references_insufficiency_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));
                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['references_unable_verify'], "selected_selection" => $frm_details['references_unable_verify_selection'], "remarks" => $frm_details['references_unable_verify_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));
                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['references_insufficiency_not_cleared'], "selected_selection" => $frm_details['references_insufficiency_not_cleared_selection'], "remarks" => $frm_details['references_insufficiency_not_cleared_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));

                }
                if ($client_component == "Court") {

                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['court_mode_of_verification'], "selected_selection" => $frm_details['court_mode_of_verification_selection'], "remarks" => $frm_details['court_mode_of_verification_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));

                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['court_display_matrix'], "selected_selection" => $frm_details['court_display_matrix_selection'], "remarks" => $frm_details['court_display_matrix_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));
                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['court_insufficiency'], "selected_selection" => $frm_details['court_insufficiency_selection'], "remarks" => $frm_details['court_insufficiency_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));
                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['court_unable_verify'], "selected_selection" => $frm_details['court_unable_verify_selection'], "remarks" => $frm_details['court_unable_verify_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));
                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['court_insufficiency_not_cleared'], "selected_selection" => $frm_details['court_insufficiency_not_cleared_selection'], "remarks" => $frm_details['court_insufficiency_not_cleared_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));

                }
                if ($client_component == "Global") {

                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['global_mode_of_verification'], "selected_selection" => $frm_details['global_mode_of_verification_selection'], "remarks" => $frm_details['global_mode_of_verification_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));

                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['global_display_matrix'], "selected_selection" => $frm_details['global_display_matrix_selection'], "remarks" => $frm_details['global_display_matrix_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));
                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['global_insufficiency'], "selected_selection" => $frm_details['global_insufficiency_selection'], "remarks" => $frm_details['global_insufficiency_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));
                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['global_unable_verify'], "selected_selection" => $frm_details['global_unable_verify_selection'], "remarks" => $frm_details['global_unable_verify_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));
                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['global_insufficiency_not_cleared'], "selected_selection" => $frm_details['global_insufficiency_not_cleared_selection'], "remarks" => $frm_details['global_insufficiency_not_cleared_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));

                }
                if ($client_component == "Drugs/Narcotics") {

                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['drugs_mode_of_verification'], "selected_selection" => $frm_details['drugs_mode_of_verification_selection'], "remarks" => $frm_details['drugs_mode_of_verification_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));

                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['drugs_display_matrix'], "selected_selection" => $frm_details['drugs_display_matrix_selection'], "remarks" => $frm_details['drugs_display_matrix_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));
                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['drugs_insufficiency'], "selected_selection" => $frm_details['drugs_insufficiency_selection'], "remarks" => $frm_details['drugs_insufficiency_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));
                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['drugs_unable_verify'], "selected_selection" => $frm_details['drugs_unable_verify_selection'], "remarks" => $frm_details['drugs_unable_verify_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));
                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['drugs_insufficiency_not_cleared'], "selected_selection" => $frm_details['drugs_insufficiency_not_cleared_selection'], "remarks" => $frm_details['drugs_insufficiency_not_cleared_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));

                }
                if ($client_component == "PCC") {

                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['pcc_mode_of_verification'], "selected_selection" => $frm_details['pcc_mode_of_verification_selection'], "remarks" => $frm_details['pcc_mode_of_verification_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));

                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['pcc_display_matrix'], "selected_selection" => $frm_details['pcc_display_matrix_selection'], "remarks" => $frm_details['pcc_display_matrix_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));
                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['pcc_insufficiency'], "selected_selection" => $frm_details['pcc_insufficiency_selection'], "remarks" => $frm_details['pcc_insufficiency_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));
                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['pcc_unable_verify'], "selected_selection" => $frm_details['pcc_unable_verify_selection'], "remarks" => $frm_details['pcc_unable_verify_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));
                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['pcc_insufficiency_not_cleared'], "selected_selection" => $frm_details['pcc_insufficiency_not_cleared_selection'], "remarks" => $frm_details['pcc_insufficiency_not_cleared_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));

                }
                if ($client_component == "Credit") {

                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['pcc_mode_of_verification'], "selected_selection" => $frm_details['pcc_mode_of_verification_selection'], "remarks" => $frm_details['pcc_mode_of_verification_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));

                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['pcc_display_matrix'], "selected_selection" => $frm_details['pcc_display_matrix_selection'], "remarks" => $frm_details['pcc_display_matrix_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));
                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['pcc_insufficiency'], "selected_selection" => $frm_details['pcc_insufficiency_selection'], "remarks" => $frm_details['pcc_insufficiency_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));
                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['pcc_unable_verify'], "selected_selection" => $frm_details['pcc_unable_verify_selection'], "remarks" => $frm_details['pcc_unable_verify_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));
                    $fields_entity[] = array("client_id" => $frm_details['client_name_id'], "client_component" => $frm_details['client_component'], "question" => $frm_details['pcc_insufficiency_not_cleared'], "selected_selection" => $frm_details['pcc_insufficiency_not_cleared_selection'], "remarks" => $frm_details['pcc_insufficiency_not_cleared_remark'], "status" => 1, "created_by" => $this->user_info['id'], "created_date" => date(DB_DATE_FORMAT), "modified_by" => $this->user_info['id'], "modified_on" => date(DB_DATE_FORMAT));

                }

                $result = $this->clients_model->insert_sla_details($fields_entity, array('client_id' => $frm_details['client_name_id'], 'client_component' => $frm_details['client_component'], 'status' => 1));
                if ($result) {

                    $json_array['status'] = SUCCESS_CODE;
                    $json_array['message'] = 'Client Created Successfully';
                    $json_array['redirect'] = ADMIN_SITE_URL . 'clients';
                } else {
                    $json_array['status'] = ERROR_CODE;
                    $json_array['message'] = 'Something went wrong, details not inserted';
                }
            }
            echo_json($json_array);
        }
    }

    public function view_sla_details()
    {
        if ($this->input->is_ajax_request()) {
            $frm_details = $this->input->post();
            $client_name_id = $frm_details['client_id'];
            $client_component = $frm_details['txt_val'];
            $sla_details = $this->clients_model->select_sla_details(true, array('client_id' => $client_name_id, 'client_component' => $client_component));
            if (!empty($sla_details)) {
                echo_json($sla_details);
            } else {
                echo "Error";
            }
            exit();
        }
    }

    // generate PDF File
    public function sla_pdf()
    {
        $data = array();
        $frm_details = $this->input->post();
        $client_name_id = $frm_details['client_id'];

        $htmlContent = '';
        $data['client_info'] = $this->clients_model->select_sla_client_info(true, array('clients_details.id' => $client_name_id));

        $data['getInfo'] = $this->clients_model->select_sla_pdf(true, array('client_id' => $client_name_id));
        //$data['getInfo'] = $this->clients_model->select_sla_pdf(TRUE,array('client_id' => $client_name_id));
        //print_r($data);
        $htmlContent = $this->load->view('admin/sla_view', $data);
        //$htmlContent = $this->load->view('sla_view', $data, TRUE);

        //$createPDFFile = time().'.pdf';
        //$this->createPDF(SITE_BASE_PATH.UPLOAD_FOLDER."sla_report/".$createPDFFile, $htmlContent);
        //redirect(HTTP_FILE_PATH.$createPDFFile);
    }

    public function createPDF($fileName, $html)
    {
        ob_start();
        // Include the main TCPDF library (search for installation path).
        $this->load->library('Pdf');
        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('TechArise');
        $pdf->SetTitle('TechArise');
        $pdf->SetSubject('TechArise');
        $pdf->SetKeywords('TechArise');

        // set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

        // set header and footer fonts
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, 0, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(0);
        $pdf->SetFooterMargin(0);

        // set auto page breaks
        //$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->SetAutoPageBreak(true, 0);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once dirname(__FILE__) . '/lang/eng.php';
            $pdf->setLanguageArray($l);
        }

        // set font
        $pdf->SetFont('dejavusans', '', 10);

        // add a page
        $pdf->AddPage();

        // output the HTML content
        $pdf->writeHTML($html, true, false, true, false, '');

        // reset pointer to the last page
        $pdf->lastPage();
        ob_end_clean();
        //Close and output PDF document
        $pdf->Output($fileName, 'FD');
    }

    private function echo_json($json_array)
    {
        echo_json($json_array);
        exit;
    }

    public function delete_client($id = null)
    {
        if ($this->input->is_ajax_request()) {
            try {
                $json_array['message'] = 'Something went wrong, please try again';
                $json_array['status'] = ERROR_CODE;

                if ($id) {
                    $result = $this->clients_model->delete_client(array('status' => 0), array('id' => $id));
                    if ($result) {
                        $json_array['message'] = 'Deleted Successfully';
                        $json_array['redirect'] = ADMIN_SITE_URL . 'clients';
                        $json_array['status'] = SUCCESS_CODE;
                    }
                } 
            } catch (Exception $e) {
                log_message('error', 'Candidates::delete_client');
                log_message('error', $e->getMessage());
            }
            echo_json($json_array);
        } else {
            permission_denied();
        }
    }
}
