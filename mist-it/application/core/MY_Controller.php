<?php defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{

    public $cookie_secret_key = 'p8I6o6zC73hS2YQ9sRlb2Eew1yTB15Vx';

    public $cookie_admin_secret_key = 'p8I6o6zC73hS2YQ9sRlb2Eew1yTB15Vx';
    public $cookie_vendor_secret_key = 'p8I6o6zC73hS2YQ9sRlb2Eew1yTB15X4c';

    public $menus = array();

    public function __construct()
    {
        parent::__construct();

        $this->load->model('common_model');

        $this->load->library(array('encryption', 'form_validation', 'session','file_upload_library'));

        $this->form_validation->set_error_delimiters('<label class="error">', '</label>');

        $this->load->helper(array('cookie'));

        $this->mode_of_verification = array('' => 'Select', 'verbal' => 'Verbal', 'online' => 'Online', 'written' => 'Written', 'latterhead' => 'Latterhead');

        $this->emp_modeofverification = array('Select', 'Verbal' => 'Verbal', 'Personal visit' => 'Personal visit', 'Others' => 'Others');

        $this->account_status = array('0' => 'Inavtive', '1' => 'Active');

        if ($this->session->userdata('admin')) {
            $this->user_info = $this->session->userdata('admin');

            $this->permission = $this->user_info['permission_array'];

            $this->insuff_date_db = isWeekend(date(DATE_ONLY));
            $this->insuff_date = convert_db_to_display_date($this->insuff_date_db);
            if (!is_array($this->user_info['menus'])) {

                $this->load->model('admin_menus_model');
                $result_menu = $this->admin_menus_model->get_user_permission($this->user_info['groups_id']);
                foreach ($result_menu['menu'] as $key => $value) {
                    $this->menus[$value['id']] = $value;

                    foreach ($result_menu['submenu'] as $key => $ar) {

                        if ($ar['parent_id'] == $value['id']) {
                            $this->menus[$value['id']]['sub'][] = $ar;
                        }
                    }
                }
                $this->user_info['menu'] = $this->menus;
                $this->user_info['access_page_id'] = $result_menu['access_page_id'];
            }
        }
    }

    protected function filter_view($true = false)
    {
        if ($true) {
            $data['status'] = $this->get_status_candidate();
        } else {
            $data['status'] = $this->get_status();
        }
        $data['clients'] = $this->get_clients();
        $data['user_list_name'] = $this->users_list_filter();
        if($this->user_info['id'] == "24")
        {   
            unset($data['user_list_name']['All']);
            unset($data['user_list_name']['0']);
        }
        return $this->load->view('admin/filter_view', $data, true);
    }

    protected function filter_view_candidates($true = false)
    {
        if ($true) {
            $data['status'] = $this->get_status_candidate();
        } else {
            $data['status'] = $this->get_status();
        }
        $data['clients'] = $this->get_clients();
        unset($data['status']['NA']);

        return $this->load->view('admin/filter_view_candidates', $data, true);
    }

    protected function users_list()
    {
        $lists = $this->common_model->select('user_profile', false, array("id,concat(firstname,' ',lastname) as fullname
            ", ), array('status' => STATUS_ACTIVE));

        return convert_to_single_dimension_array($lists, 'id', 'fullname');
    }

    protected function users_list_filter()
    {
        $lists = $this->common_model->select('user_profile', false, array("id,concat(firstname,' ',lastname) as fullname
            ", ), array('status' => STATUS_ACTIVE, "id" => $this->user_info['id']));

        $status_arry[0] = 'Select User';

        foreach ($lists as $key => $value) {
            $status_arry[$value['id']] = $value['fullname'];
        }
        $status_arry['All'] = 'All';
        return $status_arry;
        //return convert_to_single_dimension_array($lists,'id','fullname');
    }

    protected function client_closure_list_filter($clientid)
    {
        $lists = $this->common_model->select_client_closure('clients', array("id","clientname"), array('status' => STATUS_ACTIVE),$clientid);

        $status_arry[0] = 'Select Client';

        foreach ($lists as $key => $value) {
            $status_arry[$value['id']] = $value['clientname'];
        }
        $status_arry['All'] = 'All';
        return $status_arry;
        //return convert_to_single_dimension_array($lists,'id','fullname');
    }

    protected function users_list_executive()
    {
        $lists = $this->common_model->users_list_executive1('user_profile', false, array("`user_profile`.`status`,`user_profile`.`id`,`user_profile`.`email`,`user_profile`.`department`,`user_profile`.`user_name`,concat(`user_profile`.`firstname`,' ',`user_profile`.`lastname`) as `fullname`,`user_profile`.`profile_pic`,`user_profile`.`firstname`,`user_profile`.`lastname`,`roles`.`role_name`,`roles`.`groups_id`,`roles_permissions`.*"), array('status' => STATUS_ACTIVE));
        return convert_to_single_dimension_array($lists, 'id', 'fullname');
    }

    protected function users_list_prifix_id()
    {
        $lists = $this->common_model->select('user_profile', false, array("id,concat(firstname,' ',lastname) as fullname
            ", ), array('status' => STATUS_ACTIVE));

        $single_dimension_array = array();

        foreach ($lists as $list) {
            $single_dimension_array[$list['id']] = $list['id'] . '-' . ucwords($list['fullname']);
        }
        return $single_dimension_array;
    }

    public function sub_status($status)
    {
        $lists = $this->common_model->select('status', false, array('status_value', 'id'), array('filter_status' => $status, 'components_id' => '6'));
        return convert_to_single_dimension_array($lists, 'id', 'status_value');
    }

    public function sub_status_candidates($status)
    {
        $lists = $this->common_model->select('status', false, array('status_value', 'id'), array('filter_status' => $status, 'components_id' => '0'));
        return convert_to_single_dimension_array($lists, 'id', 'status_value');
    }

    protected function vendor_list($component_name)
    {
        $lists = $this->common_model->select_vendor_list($component_name);
        return convert_to_single_dimension_array($lists, 'id', 'vendor_name');
    }

    protected function insuff_reason_list($component_id = 6)
    {
        $lists = $this->common_model->select('raising_insuff_dropdown', false, array("remarks", "reason", "reason as txt_reason"), array('component_id' => $component_id));
        return convert_to_single_dimension_array($lists, 'reason', 'reason');
    }

    protected function candidate_entity_pack_details($cands_id)
    {
        $this->load->model('candidates_model');
        $return = $this->candidates_model->candidate_entity_pack_details(array('candidates_info.id' => $cands_id));
        if (!empty($return)) {
            $return = $return[0];
        }
        return $return;
    }

    protected function address_entity_pack_details($cands_id)
    {
        $return = $this->common_model->address_entity_pack_details(array('addrver.candsid' => $cands_id));
        if (!empty($return)) {
            $return = $return[0];
        }
        return $return;
    }

    protected function file_upload_multiple($config_array)
    {
        $file_array = $error_msgs = array();

        if (!folder_exist($config_array['file_upload_path'])) {
            mkdir($config_array['file_upload_path'], 0777, true);
        } else if (!is_writable($config_array['file_upload_path'])) {
            array_push($error_msgs, 'Problem while uploading');
        }
        try {
            for ($i = 0; $i < $config_array['files_count']; $i++) {
                if ($config_array['file_data']['name'][$i]) {
                    $file_name = $config_array['file_data']['name'][$i];

                    $file_info = pathinfo($file_name);

                    $new_file_name = preg_replace('/[[:space:]]+/', '_', $file_info['filename']);

                    $new_file_name = $new_file_name . '_' . DATE(UPLOAD_FILE_DATE_FORMAT_FILE_NAME);

                    $new_file_name = str_replace('.', '_', $new_file_name);

                    $new_file_name = preg_replace('/[^A-Za-z0-9\-]/', '_', $new_file_name);

                    $file_extension = strtolower($file_info['extension']);

                    $new_file_name = $new_file_name . '.' . $file_extension;

                    $_FILES['attchment']['name'] = $new_file_name;

                    $_FILES['attchment']['tmp_name'] = $config_array['file_data']['tmp_name'][$i];

                    $_FILES['attchment']['error'] = $config_array['file_data']['error'][$i];

                    $_FILES['attchment']['size'] = $config_array['file_data']['size'][$i];

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
                            'file_name' => $new_file_name,
                            'real_filename' => $file_name,
                            'type' => $config_array['type'],
                            $config_array['component_name'] => $config_array['file_id'])
                        );
                    } else {
                        array_push($error_msgs, $this->upload->display_errors('', ''));
                    }
                }
            }
        } catch (Exception $e) {
            log_message('error', 'My_Controller::file_upload_multiple');
            log_message('error', $e->getMessage());
        }

        return array('success' => $file_array, 'fail' => $error_msgs);
    }

    protected function file_upload_multiple_vendor($config_array)
    {
        $file_array = $error_msgs = array();

        if (!folder_exist($config_array['file_upload_path'])) {
            mkdir($config_array['file_upload_path'], 0777, true);
        } else if (!is_writable($config_array['file_upload_path'])) {
            array_push($error_msgs, 'Problem while uploading');
        }
        try {
            for ($i = 0; $i < $config_array['files_count']; $i++) {
                if ($config_array['file_data']['name'][$i]) {
                    $file_name = $config_array['file_data']['name'][$i];

                    $file_info = pathinfo($file_name);

                    $new_file_name = preg_replace('/[[:space:]]+/', '_', $file_info['filename']);

                    $new_file_name = $new_file_name . '_' . DATE(UPLOAD_FILE_DATE_FORMAT_FILE_NAME);

                    $new_file_name = str_replace('.', '_', $new_file_name);

                    $new_file_name = preg_replace('/[^A-Za-z0-9\-]/', '_', $new_file_name);

                    $file_extension = strtolower($file_info['extension']);

                    $new_file_name = $new_file_name . '.' . $file_extension;

                    $_FILES['attchment']['name'] = $new_file_name;

                    $_FILES['attchment']['tmp_name'] = $config_array['file_data']['tmp_name'][$i];

                    $_FILES['attchment']['error'] = $config_array['file_data']['error'][$i];

                    $_FILES['attchment']['size'] = $config_array['file_data']['size'][$i];

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
                            'file_name' => $new_file_name,
                            'real_filename' => $file_name,
                            'vendor_cost_details_id' => $config_array['vendor_cost_details_id'],
                            'component_tbl_id' => $config_array['component_tbl_id'],
                            'status' => $config_array['status'])
                        );
                    } else {
                        array_push($error_msgs, $this->upload->display_errors('', ''));
                    }
                }
            }
        } catch (Exception $e) {
            log_message('error', 'My_Controller::file_upload_multiple_vendor');
            log_message('error', $e->getMessage());
        }

        return array('success' => $file_array, 'fail' => $error_msgs);
    }

    protected function file_upload_multiple_vendor_log($config_array)
    {
        $file_array = $error_msgs = array();

        if (!folder_exist($config_array['file_upload_path'])) {
            mkdir($config_array['file_upload_path'], 0777, true);
        } else if (!is_writable($config_array['file_upload_path'])) {
            array_push($error_msgs, 'Problem while uploading');
        }
        try {
            for ($i = 0; $i < $config_array['files_count']; $i++) {
                if ($config_array['file_data']['name'][$i]) {
                    $file_name = $config_array['file_data']['name'][$i];

                    $file_info = pathinfo($file_name);

                    $new_file_name = preg_replace('/[[:space:]]+/', '_', $file_info['filename']);

                    $new_file_name = $new_file_name . '_' . DATE(UPLOAD_FILE_DATE_FORMAT_FILE_NAME);

                    $new_file_name = str_replace('.', '_', $new_file_name);

                    $new_file_name = preg_replace('/[^A-Za-z0-9\-]/', '_', $new_file_name);

                    $file_extension = strtolower($file_info['extension']);

                    $new_file_name = $new_file_name . '.' . $file_extension;

                    $_FILES['attchment']['name'] = $new_file_name;

                    $_FILES['attchment']['tmp_name'] = $config_array['file_data']['tmp_name'][$i];

                    $_FILES['attchment']['error'] = $config_array['file_data']['error'][$i];

                    $_FILES['attchment']['size'] = $config_array['file_data']['size'][$i];

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
                            'file_name' => $new_file_name,
                            'real_filename' => $file_name,
                            'view_venor_master_log_id' => $config_array['view_venor_master_log_id'],
                            'component_tbl_id' => $config_array['component_tbl_id'],
                            'status' => $config_array['status'])
                        );
                    } else {
                        array_push($error_msgs, $this->upload->display_errors('', ''));
                    }
                }
            }
        } catch (Exception $e) {
            log_message('error', 'My_Controller::file_upload_multiple_vendor_log');
            log_message('error', $e->getMessage());
        }
        return array('success' => $file_array, 'fail' => $error_msgs);
    }

    protected function file_upload_multiple_task($config_array)
    {
        $file_array = $error_msgs = array();
        try {
            for ($i = 0; $i < $config_array['files_count']; $i++) {
                if ($config_array['file_data']['name'][$i]) {
                    $file_name = $config_array['file_data']['name'][$i];

                    $file_info = pathinfo($file_name);

                    $new_file_name = preg_replace('/[[:space:]]+/', '_', $file_info['filename']);

                    $new_file_name = $new_file_name . '_' . DATE(UPLOAD_FILE_DATE_FORMAT_FILE_NAME);

                    $new_file_name = str_replace('.', '_', $new_file_name);

                    $new_file_name = preg_replace('/[^A-Za-z0-9\-]/', '_', $new_file_name);

                    $file_extension = strtolower($file_info['extension']);

                    $new_file_name = $new_file_name . '.' . $file_extension;

                    $_FILES['attchment']['name'] = $new_file_name;

                    $_FILES['attchment']['tmp_name'] = $config_array['file_data']['tmp_name'][$i];

                    $_FILES['attchment']['error'] = $config_array['file_data']['error'][$i];

                    $_FILES['attchment']['size'] = $config_array['file_data']['size'][$i];

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
                            'file_name' => $new_file_name,
                            'real_filename' => $file_name,
                            'status' => $config_array['status'],
                            $config_array['component_name'] => $config_array['file_id'])
                        );
                    } else {
                        array_push($error_msgs, $this->upload->display_errors('', ''));
                    }
                }
            }
        } catch (Exception $e) {
            log_message('error', 'My_Controller::file_upload_multiple_task');
            log_message('error', $e->getMessage());
        }
        return array('success' => $file_array, 'fail' => $error_msgs);
    }

    protected function file_uplod($config_array)
    {
        $msgs = array();
        try {
            if (!folder_exist($config_array['file_upload_path'])) {
                mkdir($config_array['file_upload_path'], 0777);
            }

            if (!folder_exist($config_array['file_upload_path'])) {
                mkdir($config_array['file_upload_path'], 0777);
            } else if (!is_writable($config_array['file_upload_path'])) {
                $msgs['status'] = false;
                $msgs['message'] = 'Problem while uploading';
            }

            if ($config_array['file_data']['name']) {
                $file_name = $config_array['file_data']['name'];

                $file_info = pathinfo($file_name);

                $new_file_name = preg_replace('/[[:space:]]+/', '_', $file_info['filename']);

                $new_file_name = preg_replace('/[^A-Za-z0-9\-]/', '_', $new_file_name);

                $new_file_name = str_replace('.', '_', $new_file_name . '_' . DATE(UPLOAD_FILE_DATE_FORMAT_FILE_NAME));

                $new_file_name = $new_file_name . '.' . $file_info['extension'];

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

                    $msgs['status'] = true;
                    $msgs['message'] = 'Successfully Uploaded';
                    $msgs['file_name'] = $new_file_name;
                } else {

                    $msgs['status'] = false;
                    $msgs['message'] = $this->upload->display_errors('', '');
                }
            } else {
                $msgs['status'] = false;
                $msgs['message'] = 'Uplaod File';
            }
        } catch (Exception $e) {
            log_message('error', 'My_Controller::file_uplod');
            log_message('error', $e->getMessage());
        }
        return $msgs;
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

    protected function get_status()
    {
        $clients = $this->common_model->distinct_status(array('id', "filter_status"), array('components_id' => '6'));

        $status_arry[0] = 'Select Status';

        foreach ($clients as $key => $value) {
            $status_arry[$value['filter_status']] = ucwords($value['filter_status']);
        }
        $status_arry['All'] = 'All';
        return $status_arry;
    }

    protected function get_status_export()
    {
        $clients = $this->common_model->distinct_status(array('id', "filter_status"), array('components_id' => '6'));

        $status_arry[0] = 'Select Status';

        foreach ($clients as $key => $value) {
            $status_arry[$value['filter_status']] = ucwords($value['filter_status']);
        }
        unset($status_arry['NA']);
        return $status_arry;
    }

    protected function get_clients($where = array())
    {
        $clients = $this->common_model->select('clients', false, array('id', 'clientname'), $where);

        $clients_arry[0] = 'Select Client';

        foreach ($clients as $key => $value) {
            $clients_arry[$value['id']] = ucwords(strtolower($value['clientname']));
        }
        return $clients_arry;
    }

    protected function get_entiry_package_list($where = array())
    {
        $company = $this->common_model->select('entity_package', false, array('id', 'entity_package_name'), $where);

        $entity_arry[0] = 'Select';

        foreach ($company as $key => $value) {
            $entity_arry[$value['id']] = ucwords(strtolower($value['entity_package_name']));
        }
        return $entity_arry;
    }

    protected function get_package_list()
    {
        $company = $this->common_model->select('entity_package', false, array('id', 'entity_package_name'), array('is_entity_package' => 2));

        $entity_arry[0] = 'Select';

        foreach ($company as $key => $value) {
            $entity_arry[$value['id']] = ucwords(strtolower($value['entity_package_name']));
        }
        return $entity_arry;
    }

    protected function get_entiry_package_list1($is_entity = array('is_entity'))
    {
        $company = $this->common_model->select('entity_package', false, array('id', 'entity_package_name'), $is_entity);

        $entity_arry[0] = 'Select';

        foreach ($company as $key => $value) {
            $entity_arry[$value['id']] = ucwords(strtolower($value['entity_package_name']));
        }
        return $entity_arry;
    }

    protected function get_entiry_list1($tbl_clients_id = array('tbl_clients_id'))
    {
        $company = $this->common_model->select('entity_package', false, array('id', 'entity_package_name'), $tbl_clients_id);

        $entity_arry[0] = 'Select';

        foreach ($company as $key => $value) {
            $entity_arry[$value['id']] = ucwords(strtolower($value['entity_package_name']));
        }
        return $entity_arry;
    }

    protected function components($where = array())
    {
        return $this->common_model->component_serial_wise();
    }

    protected function components_key_val($compoents_val)
    {
        $return_arry = array();
        foreach ($this->components() as $key => $value) {

            $return_arry[$value['component_key']] = $compoents_val[$key];
        }
        return json_encode($return_arry);
    }

    protected function get_company_list()
    {
        $company = $this->common_model->select('company_database', false, array('id', 'coname'), array());
        $company_arry[0] = 'Select Company';
        foreach ($company as $key => $value) {
            $company_arry[$value['id']] = ucwords(strtolower($value['coname']));
        }
        return $company_arry;
    }

    protected function is_user_logged_in()
    {
        if (!$this->verify_login_cookie()) {
            $this->logout_user();
            return false;
        }

        $id = $this->session->userdata('id');
        $logged_in = (bool) $this->session->userdata('logged_in');
        return ($id and $logged_in);
    }

    protected function is_admin_logged_in($page_id = false)
    {
        $is_admin_logged_in = false;

        if (!$this->verify_admin_login_cookie()) {
            $this->logout_admin();

            return $is_admin_logged_in;
        }

        $admin_data = $this->session->userdata('admin');

        if ($admin_data) {
            $id = $admin_data['id'];

            $logged_in = (bool) $admin_data['admin_logged_in'];

            $is_admin_logged_in = ($id and $logged_in);
        }

        return $is_admin_logged_in;
    }

    protected function is_admin_id($page_id = false)
    {
        $admin_data = $this->session->userdata('admin');
        if ($admin_data) {
            $id = $admin_data['id'];
            $admin_data = $this->common_model->update_log_in(array('is_login_or_not' => 1), array('id' => $id));
        }
        return $id;
    }

    protected function logout_user()
    {
        if (!empty($this->session->userdata())) {
            $this->session->unset_userdata('id');
        }

        delete_cookie('member');
    }

    protected function logout_admin()
    {
        delete_cookie('smember');

        if (!empty($this->session->userdata())) {
            $this->session->unset_userdata(array('admin'));
        }
    }

    protected function encrypt($str)
    {
        return $this->encryption->encrypt($str);
    }

    protected function decrypt($encrypted_str)
    {
        return $this->encryption->decrypt($encrypted_str);
    }

    protected function get_login_cookie()
    {
        $cookie_name = 'tokenusercook';

        if (!$this->verify_login_cookie()) {
            return "";
        }

        list($encrypted_cookie, $hash) = explode(':', get_cookie($cookie_name));

        $salt = $this->session->userdata('ctkn');

        $salt = base64_decode($salt);

        $decrypted_cookie = $this->encrypt->decode($encrypted_cookie, $this->cookie_secret_key);

        return unserialize($decrypted_cookie);
    }

    protected function get_admin_login_cookie()
    {
        $cookie_name = 'adtokencook';

        if (!$this->verify_admin_login_cookie()) {
            return "";
        }

        list($encrypted_cookie, $hash) = explode(':', get_cookie($cookie_name));

        $admin_data = $this->session->userdata('admin');

        $salt = $admin_data['catkn'];

        $salt = base64_decode($salt);

        $decrypted_cookie = $this->encrypt->decode($encrypted_cookie, $this->cookie_admin_secret_key);

        return unserialize($decrypted_cookie);
    }

    protected function verify_login_cookie()
    {
        $verified = false;

        $hash = false;

        $cookie_name = 'tokenusercook';

        $session_salt = $this->session->userdata('ctkn');

        if (empty($session_salt)) {
            return $verified;
        }

        list($encrypted_cookie, $hash) = explode(':', get_cookie($cookie_name));

        if (!empty($hash)) {
            $session_salt = base64_decode($session_salt);

            if ($hash == hash_hmac('sha256', $encrypted_cookie, $session_salt)) {
                $verified = true;
            }
        }

        return $verified;
    }

    protected function verify_admin_login_cookie()
    {
        $verified = false;

        $cookie_name = 'adtokencook';

        $admin_data = $this->session->userdata('admin');

        if (empty($admin_data)) {
            return $verified;
        }

        $session_salt = $admin_data['catkn'];

        if (empty($session_salt)) {
            return $verified;
        }

        if (is_array(explode(':', get_cookie($cookie_name)))) {
            list($encrypted_cookie, $hash) = explode(':', get_cookie($cookie_name));

            if (!empty($hash)) {
                $session_salt = base64_decode($session_salt);

                if ($hash == hash_hmac('sha256', $encrypted_cookie, $session_salt)) {
                    $verified = true;
                }
            }
        }

        return $verified;
    }

    protected function set_login_cookie($cookie_values)
    {
        $cookie_name = 'tokenusercook';

        $cookie_value = serialize($cookie_values);

        $encrypted_cookie = $this->encrypt($cookie_value, $this->cookie_secret_key);

        $salt = uniqid(mt_rand(), true);

        $encrypted_cookie .= ':' . hash_hmac('sha256', $encrypted_cookie, $salt);

        $name = $cookie_name;
        $value = $encrypted_cookie;
        $expire = time() + 86500;
        $path = '/';

        setcookie($name, $value, $expire, $path);

        $this->session->set_userdata(array(
            'ctkn' => base64_encode($salt),
        ));
    }

    protected function set_admin_login_cookie($cookie_values)
    {
        delete_cookie('adtokencook');

        $cookie_name = 'adtokencook';

        $cookie_value = serialize($cookie_values);

        $encrypted_cookie = $this->encrypt($cookie_value, $this->cookie_secret_key);

        $salt = uniqid(mt_rand(), true);

        $encrypted_cookie .= ':' . hash_hmac('sha256', $encrypted_cookie, $salt);

        $expire = time() + 86500;

        $path = '/';

        $a = setcookie($cookie_name, $encrypted_cookie, $expire, $path);

        $admin_data = $this->session->userdata('admin');

        $admin_data['catkn'] = base64_encode($salt);

        $this->session->set_userdata(array('admin' => $admin_data));
    }

    protected function get_admin_session($key = "")
    {
        $return = "";

        $admin_data = $this->session->userdata('admin');

        if (!empty($key) and !empty($admin_data)) {
            $return = $admin_data[$key];
        } else {
            $return = $admin_data;
        }

        return $return;
    }

    protected function get_states($option = 'id')
    {
        $state_arry[0] = "Select State";

        $lists = $this->common_model->select('states', false, array('id', 'lower(state) as state'), array(), true);

        foreach ($lists as $key => $value) {
            $state_arry[$value['state']] = ucwords($value['state']);
        }

        return $state_arry;
    }

    public function get_reporting_manager_for_execituve($clientid)
    {

        $this->db->select("user_profile.id,concat(`user_profile`.`firstname`,' ',`user_profile`.`lastname`) as fullname");

        $this->db->from('user_profile');

        $this->db->join('clients','clients.clientmgr = user_profile.id');
    
        $this->db->where('clients.id',$clientid); 
        
        $result_array = $this->db->get()->result_array();    
           
        return convert_to_single_dimension_array1($result_array,'id','fullname');
    }

    public function get_reporting_manager_for_executive($clientid)
    {

        $this->db->select("user_profile.id");

        $this->db->from('user_profile');

        $this->db->join('clients','clients.clientmgr = user_profile.id');
        
        $this->db->where('clients.id',$clientid); 
        
        $result_array = $this->db->get()->result_array();    
           
        return $result_array;
    }


    protected function update_master_vendor($component_name, $component_id)
    {
        $file_array = $error_msgs = array();

        for ($i = 0; $i < $config_array['files_count']; $i++) {
            if ($config_array['file_data']['name'][$i]) {
                $file_name = $config_array['file_data']['name'][$i];

                $file_info = pathinfo($file_name);

                $new_file_name = preg_replace('/[[:space:]]+/', '_', $file_info['filename']);

                $new_file_name = $new_file_name . '_' . DATE(UPLOAD_FILE_DATE_FORMAT_FILE_NAME);

                $new_file_name = str_replace('.', '_', $new_file_name);

                $new_file_name = preg_replace('/[^A-Za-z0-9\-]/', '_', $new_file_name);

                $file_extension = strtolower($file_info['extension']);

                $new_file_name = $new_file_name . '.' . $file_extension;

                $_FILES['attchment']['name'] = $new_file_name;

                $_FILES['attchment']['tmp_name'] = $config_array['file_data']['tmp_name'][$i];

                $_FILES['attchment']['error'] = $config_array['file_data']['error'][$i];

                $_FILES['attchment']['size'] = $config_array['file_data']['size'][$i];

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
                        'file_name' => $new_file_name,
                        'real_filename' => $file_name,
                        'type' => $config_array['type'],
                        $config_array['component_name'] => $config_array['file_id'])
                    );
                } else {
                    array_push($error_msgs, $this->upload->display_errors('', ''));
                }
            }
        }

        return array('success' => $file_array, 'fail' => $error_msgs);
    }

    public function get_client_entity_package_tat_day($wherearray)
    {

        $return = $this->common_model->candidate_entity_package_tat_day($wherearray);

        return $return;
    }

    public function get_holiday()
    {

        $return = $this->common_model->get_holiday(array('status' => 1));

        return $return;
    }

    public function get_address_cases_wip($holiday_date)
    {

        $address_result = $this->common_model->get_addres_ver_filter_status(array('addrverres.var_filter_status' => "WIP"));

        if (!empty($address_result)) {

            foreach ($address_result as $key => $value) {

                $get_holiday1 = $this->get_holiday();

                $get_holiday = array_map('current', $get_holiday1);

                $closed_date = getWorkingDays_increament($address_result[$key]['due_date'], $get_holiday, 1);

                if (convert_display_to_db_date($holiday_date) <= $address_result[$key]['due_date']) {

                    $address_result_update = $this->common_model->set_address_verification_status(array('due_date' => $closed_date), array('id' => $address_result[$key]['id']));

                } else {

                    $address_result_update = '';

                }

            }

        } else {
            $address_result_update = '';
        }

        return $address_result_update;
    }

    public function auto_increament_date_holiday_add($holiday_date)
    {

        $address_cases = $this->get_address_cases_wip($holiday_date);

        return $address_cases;
    }

}

class MY_Vendor_Cotroller extends CI_Controller
{
    public $cookie_secret_key = 'BAh7CEkiD3Nlc3Npb25faWQGOgZFVEkiRTdhYTliNGY5ZjVmOTE4MjIxYTU5';

    public $vendor_info = array();

    public $vendor_nemus = array();

    public function __construct()
    {
        parent::__construct();

        $this->load->model('vendor/vendor_common_model');

        $this->load->model('common_model');

        $this->load->library(array('encryption', 'form_validation', 'session'));

        $this->load->library(array('encryption', 'form_validation', 'session','file_upload_library'));

        $this->vendor_info = $this->session->userdata('vendor');

        $this->vendor_nemus = $this->vendor_menus();

        $this->load->library(array('encryption', 'form_validation'));

        $this->form_validation->set_error_delimiters('<label class="error">', '</label>');

        $this->load->helper(array('cookie'));

    }

    protected function vendor_menus()
    {
        if(isset( $this->vendor_info['vendors_components']))
        {
        $temp = explode(',', $this->vendor_info['vendors_components']);
        }
        else{
           $temp = array(); 
        }

        $temp[] = "users";
        $temp[] = "settings";
        $temp[] = "reports";

        return $this->vendor_common_model->vendor_component($temp);
    }

    protected function is_vendor_logged_in()
    {
        if (!$this->verify_vendor_login_cookie()) {

            $this->logout_vendor_user();
            return false;
        }

        $vendor_id = $this->vendor_info['id'];
        $logged_in = (bool) $this->vendor_info['vendor_logged_in'];
        return ($vendor_id and $logged_in);
    }

    protected function verify_vendor_login_cookie()
    {
        $verified = $hash = false;

        $cookie_name = 'vendlogincookie';

        $vendor_data = $this->session->userdata('vendor');

        if (empty($vendor_data)) {
            return $verified;
        }

        $session_salt = $vendor_data['vdctkn'];

        if (empty($session_salt)) {
            return $verified;
        }

        list($encrypted_cookie, $hash) = explode(':', get_cookie($cookie_name));

        if (!empty($hash)) {
            $session_salt = base64_decode($session_salt);

            if ($hash == hash_hmac('sha256', $encrypted_cookie, $session_salt)) {
                $verified = true;
            }
        }

        return $verified;
    }

    protected function logout_vendor_user()
    {
        if (!empty($this->session->userdata('vendor'))) {
            $this->session->unset_userdata(array('vendor'));

            $this->session->unset_userdata('vdctkn');
        }
    }

    protected function get_company_list()
    {
        $company = $this->common_model->select('company_database', false, array('id', 'coname'), array());
        $company_arry[0] = 'Select Company';
        foreach ($company as $key => $value) {
            $company_arry[$value['id']] = ucwords(strtolower($value['coname']));
        }
        return $company_arry;
    }

    protected function users_list()
    {
        $lists = $this->common_model->select('user_profile', false, array("id,concat(firstname,' ',lastname) as fullname
            ", ), array('status' => STATUS_ACTIVE));

        return convert_to_single_dimension_array($lists, 'id', 'fullname');
    }

    protected function encrypt($str)
    {
        return $this->encryption->encrypt($str);
    }

    protected function decrypt($encrypted_str)
    {
        return $this->encryption->decrypt($encrypted_str);
    }

    protected function get_vendor_login_cookie()
    {
        $cookie_name = 'vendlogincookie';

        if (!$this->verify_vendor_login_cookie()) {
            return "";
        }

        list($encrypted_cookie, $hash) = explode(':', get_cookie($cookie_name));

        $salt = $this->session->userdata('vdctkn');

        $salt = base64_decode($salt);

        $decrypted_cookie = $this->encrypt->decode($encrypted_cookie, $this->cookie_vendor_secret_key);

        return unserialize($decrypted_cookie);
    }

    protected function set_vendor_login_cookie($cookie_values)
    {
        delete_cookie('vendlogincookie');

        $cookie_name = 'vendlogincookie';

        $cookie_value = serialize($cookie_values);

        $encrypted_cookie = $this->encrypt($cookie_value, $this->cookie_secret_key);

        $salt = uniqid(mt_rand(), true);

        $encrypted_cookie .= ':' . hash_hmac('sha256', $encrypted_cookie, $salt);

        $expire = time() + 86500;

        setcookie($cookie_name, $encrypted_cookie, $expire, '/');

        $vendor_data = $this->session->userdata('vendor');

        $vendor_data['vdctkn'] = base64_encode($salt);

        $this->session->set_userdata(array('vendor' => $vendor_data));
    }

    protected function get_states($option = 'id')
    {

        $state_arry[0] = "Select State";

        $lists = $this->common_model->select('states', false, array('id', 'lower(state) as state'), array(), true);

        foreach ($lists as $key => $value) {
            $state_arry[$value['state']] = ucwords($value['state']);
        }

        return $state_arry;
    }

    protected function file_upload_multiple_vendor($config_array)
    {
        $file_array = $error_msgs = array();

        for ($i = 0; $i < $config_array['files_count']; $i++) {
            if ($config_array['file_data']['name'][$i]) {
                $file_name = $config_array['file_data']['name'][$i];
                // print_r($config_array['file_data']['name'][$i]);
                $file_info = pathinfo($file_name);

                $new_file_name = preg_replace('/[[:space:]]+/', '_', $file_info['filename']);

                $new_file_name = $new_file_name . '_' . DATE(UPLOAD_FILE_DATE_FORMAT_FILE_NAME);

                $new_file_name = str_replace('.', '_', $new_file_name);

                $new_file_name = preg_replace('/[^A-Za-z0-9\-]/', '_', $new_file_name);

                $file_extension = strtolower($file_info['extension']);

                $new_file_name = $new_file_name . '.' . $file_extension;

                $_FILES['attchment']['name'] = $new_file_name;

                $_FILES['attchment']['tmp_name'] = $config_array['file_data']['tmp_name'][$i];

                $_FILES['attchment']['error'] = $config_array['file_data']['error'][$i];

                $_FILES['attchment']['size'] = $config_array['file_data']['size'][$i];

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
                        'file_name' => $new_file_name,
                        'real_filename' => $file_name,
                        'view_venor_master_log_id' => $config_array['view_venor_master_log_id'],
                        'component_tbl_id' => $config_array['component_tbl_id'],
                        'status' => $config_array['status'])
                    );
                } else {
                    array_push($error_msgs, $this->upload->display_errors('', ''));
                }
            }
        }

        return array('success' => $file_array, 'fail' => $error_msgs);
    }

    public function save_activity_log($arrdata, $arrwhere = array())
    {
        if (!empty($arrwhere)) {
            $this->db->where($arrwhere);

            $result = $this->db->update("vendor_activity_log", $arrdata);

            record_db_error($this->db->last_query());

            return $result;
        } else {
            $this->db->insert("vendor_activity_log", $arrdata);

            record_db_error($this->db->last_query());

            return $this->db->insert_id();
        }
    }

}
class MY_Client_Cotroller extends CI_Controller
{
    public $cookie_secret_key = 'FAFGG56BAh7CEkiD3Nlc3Npb25faWQGOgZFVEkiRTdhYTliNGY5ZjVmOTE4MjIxYTU5';

    public $client_info = array();
    public $client_nemus = array();

    public function __construct()
    {
        parent::__construct();

        $this->load->model('common_model');

        $this->load->model('client/client_common_model');

        $this->load->library(array('encryption', 'form_validation', 'session','file_upload_library'));

        $this->client_info = $this->session->userdata('client');

        $this->client_nemus = array();

        $this->load->library(array('encryption', 'form_validation'));

        $this->client_nemus = $this->clients_menus();

        $this->form_validation->set_error_delimiters('<label class="error">', '</label>');

        $this->load->helper(array('cookie'));
        
        
        $this->user_role = ($this->session->userdata('client')['role'] === ADMIN_ROLE) ? false : $this->session->userdata('client')['client_id'];

        $this->user_package = !empty($this->session->userdata('client')['client_entity_access']) ? $this->session->userdata('client')['client_entity_access'] : false;
        
        $this->candidate_upload = $this->client_common_model->select_candidate_upload(array('clients_details.tbl_clients_id' => $this->client_info['client_id'],'clients_details.package' => $this->client_info['client_entity_access']));
    }

    protected function clients_menus()
    {
        if (!empty($this->client_info['client_entity_access'])) {

            $client_entity_access = explode(",", $this->client_info['client_entity_access']);

            foreach ($client_entity_access as $client_entity_package) {
                $this->client_info1[] = $this->client_common_model->select1('clients_details', false, array('component_id'), array('tbl_clients_id' => $this->client_info['client_id'], 'package' => $client_entity_package));
            }

            $temp = array();
            foreach ($this->client_info1 as $i) {
                $temp[] = $i['component_id'];

            }

            $temp2 = implode(",", $temp);
            $temp = explode(",", $temp2);
            return $this->client_common_model->client_component($temp);
        }
    }

    protected function is_client_logged_in()
    {
        if (!$this->verify_client_login_cookie()) {

            $this->logout_client_user();
            return false;
        }

        $client_id = $this->client_info['id'];
        $logged_in = (bool) $this->client_info['client_logged_in'];
        return ($client_id and $logged_in);
    }

    protected function verify_client_login_cookie()
    {
        $verified = $hash = false;

        $cookie_name = 'clitlogincookie';

        $client_data = $this->session->userdata('client');

        if (empty($client_data)) {
            return $verified;
        }

        $session_salt = $client_data['ctlctkn'];

        if (empty($session_salt)) {
            return $verified;
        }

        list($encrypted_cookie, $hash) = explode(':', get_cookie($cookie_name));

        if (!empty($hash)) {
            $session_salt = base64_decode($session_salt);

            if ($hash == hash_hmac('sha256', $encrypted_cookie, $session_salt)) {
                $verified = true;
            }
        }
        return $verified;
    }

    protected function get_clients($where = array())
    {
        $clients = $this->common_model->select('clients', false, array('id', 'clientname'), $where);

        $clients_arry[0] = 'Select Client';

        foreach ($clients as $key => $value) {
            $clients_arry[$value['id']] = ucwords(strtolower($value['clientname']));
        }
        return $clients_arry;
    }

    protected function get_status_candidate()
    {
        $clients = $this->common_model->distinct_status(array('id', "filter_status"), array('components_id' => '0'));

        $status_arry[''] = 'Select Status';

        foreach ($clients as $key => $value) {
            $status_arry[$value['filter_status']] = ucwords($value['filter_status']);
        }

        unset($status_arry['NA']);
        $status_arry['All'] = 'All';
        return $status_arry;
    }

    protected function get_company_list()
    {
        $company = $this->common_model->select('company_database', false, array('id', 'coname'), array());
        $company_arry[0] = 'Select Company';
        foreach ($company as $key => $value) {
            $company_arry[$value['id']] = ucwords(strtolower($value['coname']));
        }
        return $company_arry;
    }

    public function get_holiday()
    {
        $return = $this->common_model->get_holiday(array('status' => 1));

        return $return;
    }

    public function sub_status_candidates($status)
    {
        $lists = $this->common_model->select('status', false, array('status_value', 'id'), array('filter_status' => $status, 'components_id' => '0'));

        return convert_to_single_dimension_array($lists, 'id', 'status_value');
    }

    protected function get_university_list()
    {
        $university = $this->common_model->select('university_master', false, array('id', 'universityname'), array());
        $university_arry[0] = 'Select University Name';
        foreach ($university as $key => $value) {
            $university_arry[$value['universityname']] = ucwords(strtolower($value['universityname']));
        }
        return $university_arry;
    }

    protected function get_qualification_list()
    {
        $qualification = $this->common_model->select('qualification_master', false, array('id', 'qualification'), array());
        $qualification_arry[0] = 'Select Qualification';
        foreach ($qualification as $key => $value) {
            $qualification_arry[$value['qualification']] = ucwords(strtolower($value['qualification']));
        }
        return $qualification_arry;
    }

    protected function logout_client_user()
    {
        if (!empty($this->session->userdata('client'))) {
            $this->session->unset_userdata(array('client'));

            $this->session->unset_userdata('ctlctkn');
        }
    }

    public function get_client_entity_package_tat_day($wherearray)
    {

        $return = $this->common_model->candidate_entity_package_tat_day($wherearray);

        return $return;
    }

    protected function get_states($option = 'id')
    {
        $state_arry[0] = "Select State";

        $lists = $this->common_model->select('states', false, array('id', 'lower(state) as state'), array(), true);

        foreach ($lists as $key => $value) {
            $state_arry[$value['state']] = ucwords($value['state']);
        }

        return $state_arry;
    }

    protected function candidate_entity_pack_details($cands_id)
    {
        $this->load->model('candidates_model');

        $return = $this->candidates_model->candidate_entity_pack_details(array('candidates_info.id' => $cands_id));
        if (!empty($return)) {
            $return = $return[0];
        }

        return $return;
    }

    protected function encrypt($str)
    {
        return $this->encryption->encrypt($str);
    }

    protected function decrypt($encrypted_str)
    {
        return $this->encryption->decrypt($encrypted_str);
    }

    protected function get_status()
    {
        $clients = $this->common_model->distinct_status(array('id', "filter_status"));

        $status_arry[0] = 'Select Status';

        foreach ($clients as $key => $value) {
            $status_arry[$value['filter_status']] = ucwords($value['filter_status']);
        }
        return $status_arry;
    }

    protected function get_vendor_login_cookie()
    {
        $cookie_name = 'clitlogincookie';

        if (!$this->verify_client_login_cookie()) {
            return "";
        }

        list($encrypted_cookie, $hash) = explode(':', get_cookie($cookie_name));

        $salt = $this->session->userdata('ctlctkn');

        $salt = base64_decode($salt);

        $decrypted_cookie = $this->encrypt->decode($encrypted_cookie, $this->cookie_vendor_secret_key);

        return unserialize($decrypted_cookie);
    }

    protected function set_client_login_cookie($cookie_values)
    {
        delete_cookie('clitlogincookie');

        $cookie_name = 'clitlogincookie';

        $cookie_value = serialize($cookie_values);

        $encrypted_cookie = $this->encrypt($cookie_value, $this->cookie_secret_key);

        $salt = uniqid(mt_rand(), true);

        $encrypted_cookie .= ':' . hash_hmac('sha256', $encrypted_cookie, $salt);

        $expire = time() + 86500;

        setcookie($cookie_name, $encrypted_cookie, $expire, '/');

        $client_data = $this->session->userdata('client');

        $client_data['ctlctkn'] = base64_encode($salt);

        $this->session->set_userdata(array('client' => $client_data));
    }

    protected function get_entiry_list1($tbl_clients_id = array('tbl_clients_id'))
    {

        $company = $this->common_model->select('entity_package', false, array('id', 'entity_package_name'), $tbl_clients_id);

        $entity_arry[0] = 'Select';

        foreach ($company as $key => $value) {
            $entity_arry[$value['id']] = ucwords(strtolower($value['entity_package_name']));
        }
        return $entity_arry;
    }

    protected function get_entiry_package_list1($is_entity = array('is_entity'))
    {

        $company = $this->common_model->select('entity_package', false, array('id', 'entity_package_name'), $is_entity);

        $entity_arry[0] = 'Select';

        foreach ($company as $key => $value) {
            $entity_arry[$value['id']] = ucwords(strtolower($value['entity_package_name']));
        }
        return $entity_arry;
    }

    public function my_components()
    {
        $this->load->model('clients_model');

        $components = $this->common_model->select('components', false, array(), array());
        $client_components1 = $this->clients_model->select_client(true, array('component_id'), array('id' => $this->session->userdata('client')['client_id']));

        $client_components = explode(",", $client_components1['component_id']);

        $my_component = array(0);

        foreach ($components as $key => $component) {

            if (in_array($component['component_key'], $client_components)) {
                $my_component[$component['component_key']] = $component['component_name'];
            }

            // $tat = $client_components[$component['component_key']];

            // if($tat == 1)
            // {
            //$my_component[$component['component_key']] = $component['component_name'];
            // }
        }

        return $my_component;
    }

    protected function file_upload_multiple($config_array)
    {
        $file_array = $error_msgs = array();

        for ($i = 0; $i < $config_array['files_count']; $i++) {
            if ($config_array['file_data']['name'][$i]) {
                $file_name = $config_array['file_data']['name'][$i];

                $file_info = pathinfo($file_name);

                $new_file_name = preg_replace('/[[:space:]]+/', '_', $file_info['filename']);

                $new_file_name = $new_file_name . '_' . DATE(UPLOAD_FILE_DATE_FORMAT_FILE_NAME);

                $new_file_name = str_replace('.', '_', $new_file_name);

                $new_file_name = preg_replace('/[^A-Za-z0-9\-]/', '_', $new_file_name);

                $file_extension = strtolower($file_info['extension']);

                $new_file_name = $new_file_name . '.' . $file_extension;

                $_FILES['attchment']['name'] = $new_file_name;

                $_FILES['attchment']['tmp_name'] = $config_array['file_data']['tmp_name'][$i];

                $_FILES['attchment']['error'] = $config_array['file_data']['error'][$i];

                $_FILES['attchment']['size'] = $config_array['file_data']['size'][$i];

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
                        'file_name' => $new_file_name,
                        'real_filename' => $file_name,
                        'type' => $config_array['type'],
                        $config_array['component_name'] => $config_array['file_id'])
                    );
                } else {
                    array_push($error_msgs, $this->upload->display_errors('', ''));
                }
            }
        }

        return array('success' => $file_array, 'fail' => $error_msgs);
    }

    protected function file_upload_multiple_task($config_array)
    {
        $file_array = $error_msgs = array();
        try {
            for ($i = 0; $i < $config_array['files_count']; $i++) {
                if ($config_array['file_data']['name'][$i]) {
                    $file_name = $config_array['file_data']['name'][$i];

                    $file_info = pathinfo($file_name);

                    $new_file_name = preg_replace('/[[:space:]]+/', '_', $file_info['filename']);

                    $new_file_name = $new_file_name . '_' . DATE(UPLOAD_FILE_DATE_FORMAT_FILE_NAME);

                    $new_file_name = str_replace('.', '_', $new_file_name);

                    $new_file_name = preg_replace('/[^A-Za-z0-9\-]/', '_', $new_file_name);

                    $file_extension = strtolower($file_info['extension']);

                    $new_file_name = $new_file_name . '.' . $file_extension;

                    $_FILES['attchment']['name'] = $new_file_name;

                    $_FILES['attchment']['tmp_name'] = $config_array['file_data']['tmp_name'][$i];

                    $_FILES['attchment']['error'] = $config_array['file_data']['error'][$i];

                    $_FILES['attchment']['size'] = $config_array['file_data']['size'][$i];

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
                            'file_name' => $new_file_name,
                            'real_filename' => $file_name,
                            'status' => $config_array['status'],
                            $config_array['component_name'] => $config_array['file_id'])
                        );
                    } else {
                        array_push($error_msgs, $this->upload->display_errors('', ''));
                    }
                }
            }
        } catch (Exception $e) {
            log_message('error', 'My_Controller::file_upload_multiple_task');
            log_message('error', $e->getMessage());
        }
        return array('success' => $file_array, 'fail' => $error_msgs);
    }

}

class MY_Candidate_Cotroller extends CI_Controller
{

    public $cookie_secret_key = 'FAFGG56BAh7CEkiD3Nlc3Npb25faWQGOgZFVEkiRTdhYTliNGY5ZjVmOTE4MjIxYTU5';

    public $candidate_info = array();
    public $candidate_nemus = array();

    public function __construct()
    {
        parent::__construct();

        $this->load->model('common_model');

        $this->load->model('candidate_info/candidate_common_model');

        $this->load->library(array('encryption', 'form_validation', 'session'));

        $this->candidate_info = $this->session->userdata('candidate');

        $this->candidate_nemus = array();
        $this->load->library(array('encryption', 'form_validation'));

        $this->candidate_nemus = $this->clients_menus();

        $this->form_validation->set_error_delimiters('<label class="error">', '</label>');

        $this->load->helper(array('cookie'));

        //  $this->user_role = ($this->session->userdata('client')['role'] === ADMIN_ROLE) ? FALSE : $this->session->userdata('client')['client_id'];

        //  $this->user_package = !empty($this->session->userdata('client')['client_entity_access']) ? $this->session->userdata('client')['client_entity_access'] : FALSE;

    }

    protected function clients_menus()
    {

        if (!empty($this->client_info['client_entity_access'])) {

            $client_entity_access = explode(",", $this->client_info['client_entity_access']);

            foreach ($client_entity_access as $client_entity_package) {

                $this->client_info1[] = $this->candidate_common_model->select1('clients_details', false, array('component_id'), array('tbl_clients_id' => $this->client_info['client_id'], 'package' => $client_entity_package));

            }

            $temp = array();
            foreach ($this->client_info1 as $i) {
                $temp[] = $i['component_id'];

            }

            $temp2 = implode(",", $temp);
            $temp = explode(",", $temp2);
            return $this->candidate_common_model->client_component($temp);
        }
    }

    protected function is_candidate_logged_in()
    {
        if (!$this->verify_candidate_login_cookie()) {

            $this->logout_candidate_user();
            return false;
        }

        $candidate_id = $this->candidate_info['id'];
        $logged_in = (bool) $this->candidate_info['id'];
        return ($candidate_id and $logged_in);
    }

    protected function candidate_entity_pack_details($cands_id)
    {
        $this->load->model('candidates_model');

        $return = $this->candidates_model->candidate_entity_pack_details(array('candidates_info.id' => $cands_id));
        if (!empty($return)) {
            $return = $return[0];
        }

        return $return;
    }

    public function get_client_entity_package_tat_day($wherearray)
    {

        $return = $this->common_model->candidate_entity_package_tat_day($wherearray);

        return $return;
    }

    public function get_holiday()
    {
        $return = $this->common_model->get_holiday(array('status' => 1));

        return $return;
    }

    protected function verify_candidate_login_cookie()
    {

        $verified = $hash = false;

        $cookie_name = 'clitlogincookie';

        $candidate_data = $this->session->userdata('candidate');

        if (empty($candidate_data)) {
            return $verified;
        }

        $session_salt = $candidate_data['ctlctkn'];

        if (empty($session_salt)) {
            return $verified;
        }

        list($encrypted_cookie, $hash) = explode(':', get_cookie($cookie_name));

        if (!empty($hash)) {
            $session_salt = base64_decode($session_salt);

            if ($hash == hash_hmac('sha256', $encrypted_cookie, $session_salt)) {
                $verified = true;
            }
        }

        return $verified;
    }

    protected function get_clients($where = array())
    {
        $clients = $this->common_model->select('clients', false, array('id', 'clientname'), $where);

        $clients_arry[0] = 'Select Client';

        foreach ($clients as $key => $value) {
            $clients_arry[$value['id']] = ucwords(strtolower($value['clientname']));
        }
        return $clients_arry;
    }

    protected function logout_candidate_user()
    {
        if (!empty($this->session->userdata('candidate'))) {
            $this->session->unset_userdata(array('candidate'));

            $this->session->unset_userdata('ctlctkn');
        }
    }

    protected function get_states($option = 'id')
    {
        $state_arry[0] = "Select State";

        $lists = $this->common_model->select('states', false, array('id', 'lower(state) as state'), array(), true);

        foreach ($lists as $key => $value) {
            $state_arry[$value['state']] = ucwords($value['state']);
        }

        return $state_arry;
    }

    protected function get_company_list()
    {
        $company = $this->common_model->select('company_database', false, array('id', 'coname'), array());
        $company_arry[0] = 'Select Company';
        foreach ($company as $key => $value) {
            $company_arry[$value['id']] = ucwords(strtolower($value['coname']));
        }
        return $company_arry;
    }

    protected function get_university_list()
    {
        $university = $this->common_model->select('university_master', false, array('id', 'universityname'), array());
        $university_arry[0] = 'Select University Name';
        foreach ($university as $key => $value) {
            $university_arry[$value['universityname']] = ucwords(strtolower($value['universityname']));
        }
        return $university_arry;
    }

    protected function get_qualification_list()
    {
        $qualification = $this->common_model->select('qualification_master', false, array('id', 'qualification'), array());
        $qualification_arry[0] = 'Select Qualification';
        foreach ($qualification as $key => $value) {
            $qualification_arry[$value['qualification']] = ucwords(strtolower($value['qualification']));
        }
        return $qualification_arry;
    }

    protected function encrypt($str)
    {
        return $this->encryption->encrypt($str);
    }

    protected function decrypt($encrypted_str)
    {
        return $this->encryption->decrypt($encrypted_str);
    }

    protected function get_status()
    {
        $clients = $this->common_model->distinct_status(array('id', "filter_status"));

        $status_arry[0] = 'Select Status';

        foreach ($clients as $key => $value) {
            $status_arry[$value['filter_status']] = ucwords($value['filter_status']);
        }
        return $status_arry;
    }

    protected function get_vendor_login_cookie()
    {
        $cookie_name = 'clitlogincookie';

        if (!$this->verify_client_login_cookie()) {
            return "";
        }

        list($encrypted_cookie, $hash) = explode(':', get_cookie($cookie_name));

        $salt = $this->session->userdata('ctlctkn');

        $salt = base64_decode($salt);

        $decrypted_cookie = $this->encrypt->decode($encrypted_cookie, $this->cookie_vendor_secret_key);

        return unserialize($decrypted_cookie);
    }

    protected function set_candidate_login_cookie($cookie_values)
    {
        delete_cookie('clitlogincookie');

        $cookie_name = 'clitlogincookie';

        $cookie_value = serialize($cookie_values);

        $encrypted_cookie = $this->encrypt($cookie_value, $this->cookie_secret_key);

        $salt = uniqid(mt_rand(), true);

        $encrypted_cookie .= ':' . hash_hmac('sha256', $encrypted_cookie, $salt);

        $expire = time() + 86500;

        setcookie($cookie_name, $encrypted_cookie, $expire, '/');

        $candidate_data = $this->session->userdata('candidate');

        $candidate_data['ctlctkn'] = base64_encode($salt);

        $this->session->set_userdata(array('candidate' => $candidate_data));
    }

    protected function get_entiry_list1($tbl_clients_id = array('tbl_clients_id'))
    {

        $company = $this->common_model->select('entity_package', false, array('id', 'entity_package_name'), $tbl_clients_id);

        $entity_arry[0] = 'Select';

        foreach ($company as $key => $value) {
            $entity_arry[$value['id']] = ucwords(strtolower($value['entity_package_name']));
        }
        return $entity_arry;
    }

    protected function get_entiry_package_list1($is_entity = array('is_entity'))
    {

        $company = $this->common_model->select('entity_package', false, array('id', 'entity_package_name'), $is_entity);

        $entity_arry[0] = 'Select';

        foreach ($company as $key => $value) {
            $entity_arry[$value['id']] = ucwords(strtolower($value['entity_package_name']));
        }
        return $entity_arry;
    }

    public function my_components()
    {
        $this->load->model('clients_model');

        $components = $this->common_model->select('components', false, array(), array());
        $client_components1 = $this->clients_model->select_client(true, array('component_id'), array('id' => $this->session->userdata('client')['client_id']));

        $client_components = explode(",", $client_components1['component_id']);

        $my_component = array(0);

        foreach ($components as $key => $component) {

            if (in_array($component['component_key'], $client_components)) {
                $my_component[$component['component_key']] = $component['component_name'];
            }
        }

        return $my_component;
    }

    protected function file_upload_multiple($config_array)
    {
        $file_array = $error_msgs = array();
        try {
            for ($i = 0; $i < $config_array['files_count']; $i++) {
                if ($config_array['file_data']['name'][$i]) {
                    $file_name = $config_array['file_data']['name'][$i];

                    $file_info = pathinfo($file_name);

                    $new_file_name = preg_replace('/[[:space:]]+/', '_', $file_info['filename']);

                    $new_file_name = $new_file_name . '_' . DATE(UPLOAD_FILE_DATE_FORMAT_FILE_NAME);

                    $new_file_name = str_replace('.', '_', $new_file_name);

                    $new_file_name = preg_replace('/[^A-Za-z0-9\-]/', '_', $new_file_name);

                    $file_extension = strtolower($file_info['extension']);

                    $new_file_name = $new_file_name . '.' . $file_extension;

                    $_FILES['attchment']['name'] = $new_file_name;

                    $_FILES['attchment']['tmp_name'] = $config_array['file_data']['tmp_name'][$i];

                    $_FILES['attchment']['error'] = $config_array['file_data']['error'][$i];

                    $_FILES['attchment']['size'] = $config_array['file_data']['size'][$i];

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
                            'file_name' => $new_file_name,
                            'real_filename' => $file_name,
                            'type' => $config_array['type'],
                            $config_array['component_name'] => $config_array['file_id'])
                        );
                    } else {
                        array_push($error_msgs, $this->upload->display_errors('', ''));
                    }
                }
            }
        } catch (Exception $e) {
            log_message('error', 'Srinivas');
            log_message('error', 'My_Controller::file_upload_multiple');
            log_message('error', $e->getMessage());
        }
        return array('success' => $file_array, 'fail' => $error_msgs);
    }

}

class MY_User_Controller extends CI_Controller {
    var $cands_session;
    public function __construct()
    {
       
        $useragent=$_SERVER['HTTP_USER_AGENT'];
        
        parent::__construct();
        $this->load->helper('cookie');
        $this->load->library(array('encryption', 'form_validation', 'session','file_upload_library'));
         
        if(!preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
        { 

            $data['message'] = 'Link can be accessed through a smartphone only !!';
            $this->load->view('smartphone_access',$data);
           // echo "<p>Link can be accessed through a smartphone only !!</p>";
           
        } 
        //$this->cands_session = $this->session->userdata('cands_session');
    }
    
    protected  function verify_cands_cookie()
    {
        $cookie_name = 'cands_session';
        return get_cookie($cookie_name);
    }

    protected function logout_cands()
    {
        if(!empty($this->session->userdata())) {
            $this->session->unset_userdata('cands_session');
        }
        delete_cookie('cands_session');
    }

    protected function is_cands_logged_in()
    {
        $is_cands_logged_in = FALSE;
        
        $cands_session_data = $this->session->userdata('cands_session');
        if($cands_session_data)
        {
           $id =  $cands_session_data['id'];
           $logged_in =  (bool) $cands_session_data['logged_in'];
           $is_cands_logged_in  = ($id AND $logged_in);
        }
        return $is_cands_logged_in;
    }

}

class MY_Executive_Cotroller extends CI_Controller
{
    public $cookie_secret_key = 'BAh7CEkiD3Nlc3Npb25faWQGOgZFVEkiRTdhYTliNGY5ZjVmOTE4MjIxYTU5';

    public $vendor_executive_info = array();

    public $vendor_executive_nemus = array();

    public function __construct()
    {
        parent::__construct();

        $this->load->model('vendor/vendor_common_model');

        $this->load->model('common_model');

        $this->load->library(array('encryption', 'form_validation', 'session'));

        $this->load->library(array('encryption', 'form_validation', 'session','file_upload_library'));

        $this->vendor_executive_info = $this->session->userdata('vendor_executive');

      

        $this->load->library(array('encryption', 'form_validation'));

        $this->form_validation->set_error_delimiters('<label class="error">', '</label>');

        $this->load->helper(array('cookie'));

    }

    protected function vendor_menus()
    {
        if(isset( $this->vendor_info['vendors_components']))
        {
        $temp = explode(',', $this->vendor_info['vendors_components']);
        }
        else{
           $temp = array(); 
        }

        $temp[] = "users";
        $temp[] = "settings";
        $temp[] = "reports";

        return $this->vendor_common_model->vendor_component($temp);
    }

    protected function is_vendor_executive_logged_in()
    {
        if (!$this->verify_vendor_executive_login_cookie()) {

            $this->logout_vendor_executive_user();
            return false;
        }

        $vendor_executive_id = $this->vendor_executive_info['id'];
        $logged_in = (bool) $this->vendor_executive_info['vendor_executive_logged_in'];
        return ($vendor_executive_id and $logged_in);
    }

    protected function verify_vendor_executive_login_cookie()
    {
        $verified = $hash = false;

        $cookie_name = 'vendlogincookie';

        $vendor_executive_data = $this->session->userdata('vendor_executive');
        if (empty($vendor_executive_data)) {
            return $verified;
        }

        $session_salt = $vendor_executive_data['vdctkn'];

        if (empty($session_salt)) {
            return $verified;
        }

        list($encrypted_cookie, $hash) = explode(':', get_cookie($cookie_name));

        if (!empty($hash)) {
            $session_salt = base64_decode($session_salt);

            if ($hash == hash_hmac('sha256', $encrypted_cookie, $session_salt)) {
                $verified = true;
            }
        }


        return $verified;
    }

    protected function logout_vendor_executive_user()
    {
        if (!empty($this->session->userdata('vendor_executive'))) {
            $this->session->unset_userdata(array('vendor_executive'));

            $this->session->unset_userdata('vdctkn');
        }
    }

    protected function get_company_list()
    {
        $company = $this->common_model->select('company_database', false, array('id', 'coname'), array());
        $company_arry[0] = 'Select Company';
        foreach ($company as $key => $value) {
            $company_arry[$value['id']] = ucwords(strtolower($value['coname']));
        }
        return $company_arry;
    }

    protected function users_list()
    {
        $lists = $this->common_model->select('user_profile', false, array("id,concat(firstname,' ',lastname) as fullname
            ", ), array('status' => STATUS_ACTIVE));

        return convert_to_single_dimension_array($lists, 'id', 'fullname');
    }

    protected function encrypt($str)
    {
        return $this->encryption->encrypt($str);
    }

    protected function decrypt($encrypted_str)
    {
        return $this->encryption->decrypt($encrypted_str);
    }

    protected function get_vendor_login_cookie()
    {
        $cookie_name = 'vendlogincookie';

        if (!$this->verify_vendor_login_cookie()) {
            return "";
        }

        list($encrypted_cookie, $hash) = explode(':', get_cookie($cookie_name));

        $salt = $this->session->userdata('vdctkn');

        $salt = base64_decode($salt);

        $decrypted_cookie = $this->encrypt->decode($encrypted_cookie, $this->cookie_vendor_secret_key);

        return unserialize($decrypted_cookie);
    }

    protected function set_vendor_executive_login_cookie($cookie_values)
    {
        delete_cookie('vendlogincookie');

        $cookie_name = 'vendlogincookie';

        $cookie_value = serialize($cookie_values);

        $encrypted_cookie = $this->encrypt($cookie_value, $this->cookie_secret_key);

        $salt = uniqid(mt_rand(), true);

        $encrypted_cookie .= ':' . hash_hmac('sha256', $encrypted_cookie, $salt);

        $expire = time() + 86500;

        setcookie($cookie_name, $encrypted_cookie, $expire, '/');

        $vendor_executive_data = $this->session->userdata('vendor_executive');

        $vendor_executive_data['vdctkn'] = base64_encode($salt);

        $this->session->set_userdata(array('vendor_executive' => $vendor_executive_data));
    }

    protected function get_states($option = 'id')
    {

        $state_arry[0] = "Select State";

        $lists = $this->common_model->select('states', false, array('id', 'lower(state) as state'), array(), true);

        foreach ($lists as $key => $value) {
            $state_arry[$value['state']] = ucwords($value['state']);
        }

        return $state_arry;
    }

    protected function file_upload_multiple_vendor($config_array)
    {
        $file_array = $error_msgs = array();

        for ($i = 0; $i < $config_array['files_count']; $i++) {
            if ($config_array['file_data']['name'][$i]) {
                $file_name = $config_array['file_data']['name'][$i];
                // print_r($config_array['file_data']['name'][$i]);
                $file_info = pathinfo($file_name);

                $new_file_name = preg_replace('/[[:space:]]+/', '_', $file_info['filename']);

                $new_file_name = $new_file_name . '_' . DATE(UPLOAD_FILE_DATE_FORMAT_FILE_NAME);

                $new_file_name = str_replace('.', '_', $new_file_name);

                $new_file_name = preg_replace('/[^A-Za-z0-9\-]/', '_', $new_file_name);

                $file_extension = strtolower($file_info['extension']);

                $new_file_name = $new_file_name . '.' . $file_extension;

                $_FILES['attchment']['name'] = $new_file_name;

                $_FILES['attchment']['tmp_name'] = $config_array['file_data']['tmp_name'][$i];

                $_FILES['attchment']['error'] = $config_array['file_data']['error'][$i];

                $_FILES['attchment']['size'] = $config_array['file_data']['size'][$i];

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
                        'file_name' => $new_file_name,
                        'real_filename' => $file_name,
                        'view_venor_master_log_id' => $config_array['view_venor_master_log_id'],
                        'component_tbl_id' => $config_array['component_tbl_id'],
                        'status' => $config_array['status'])
                    );
                } else {
                    array_push($error_msgs, $this->upload->display_errors('', ''));
                }
            }
        }

        return array('success' => $file_array, 'fail' => $error_msgs);
    }

    public function save_activity_log($arrdata, $arrwhere = array())
    {
        if (!empty($arrwhere)) {
            $this->db->where($arrwhere);

            $result = $this->db->update("vendor_activity_log", $arrdata);

            record_db_error($this->db->last_query());

            return $result;
        } else {
            $this->db->insert("vendor_activity_log", $arrdata);

            record_db_error($this->db->last_query());

            return $this->db->insert_id();
        }
    }

}
