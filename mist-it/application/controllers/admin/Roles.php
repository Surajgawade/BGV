<?php defined('BASEPATH') or exit('No direct script access allowed');

class Roles extends MY_Controller
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

        $this->perm_array = array('page_id' => 11);
        $this->load->model(array('roles_model'));
    }

    public function index()
    {
        $data['header_title'] = "Roles List";

        $this->load->view('admin/header', $data);

        $this->load->view('admin/role_view');

        $this->load->view('admin/footer');
    }

    public function fetch_roles()
    {
        if ($this->input->is_ajax_request()) {

            $params = $add_candidates = $data_arry = $columns = array();

            $params = $_REQUEST;

            $columns = array('id', 'role_name', 'role_description', 'created_on', 'user_name');

            $where_arry = array();

            $data_arry = array();

            $results = $this->roles_model->select_jion(array('roles.status' => STATUS_ACTIVE), $params, $columns);

            $totalRecords = count($this->roles_model->select_jion(array('roles.status' => STATUS_ACTIVE), $params, $columns));

            $x = 0;

            foreach ($results as $result) {

                $data_arry[$x]['id'] = $x + 1;
                $data_arry[$x]['role_name'] = ucwords($result['role_name']);
                $data_arry[$x]['role_description'] = ucwords($result['role_description']);
                $data_arry[$x]['user_name'] = $result['user_name'];
                $data_arry[$x]['encry_id'] = ADMIN_SITE_URL . 'roles/edit/' . encrypt($result['id']);
                $data_arry[$x]['created_on'] = convert_db_to_display_date($result['created_on'], DB_DATE_FORMAT);

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
            permission_denied();
        }

    }
    public function add()
    {
        if ($this->permission['access_admin_role_add'] == true) {
            $data['header_title'] = "Create Role";

            $data['access'] = $this->db->list_fields('roles_permissions');

            $data['groups_list'] = $this->groups_list();

            $this->load->view('admin/header', $data);

            $this->load->view('admin/role_add');

            $this->load->view('admin/footer');
        }
    }

    protected function groups_list()
    {
        $where_array = array('status' => STATUS_ACTIVE);

        $lists = $this->common_model->select('groups', false, array('id', 'group_name'), $where_array);

        return convert_to_single_dimension_array($lists, 'id', 'group_name');
    }

    public function save_new_role()
    {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('role_name', 'Role Name', 'required');

            $this->form_validation->set_rules('groups_id[]', 'Groups', 'required');

            $this->form_validation->set_rules('permission[]', 'Permission', 'required');

            if ($this->form_validation->run() == false) {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors();
            } else {
                $frm_details = $this->input->post();
                $field_array = array('role_name' => $frm_details['role_name'],
                    'role_description' => $frm_details['role_description'],
                    'groups_id' => implode(',', $frm_details['groups_id']),
                    'status' => STATUS_ACTIVE,
                    'created_by' => $this->user_info['id'],
                    'created_on' => date(DB_DATE_FORMAT),
                    'modified_by' => $this->user_info['id'],
                    'modified_on' => date(DB_DATE_FORMAT),
                );
                $field_array = array_map('strtolower', $field_array);
                $insert_id = $this->roles_model->save($field_array);
                if ($insert_id) {
                    $permission = array();
                    foreach ($this->db->list_fields('roles_permissions') as $key => $value) {
                        if (in_array($value, $frm_details['permission'])) {
                            $permission[$value] = 1;
                        } else {
                            $permission[$value] = 0;
                        }
                    }
                    $permission['tbl_roles_id'] = $insert_id;
                    unset($permission['permissionID']);
                    $permission['tbl_roles_id'] = $this->roles_model->roles_permission_insert($permission, "");

                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'Role Created Successfully';

                    $json_array['redirect'] = ADMIN_SITE_URL . 'roles';
                } else {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Something went wrong,please try again';
                }
            }
            echo_json($json_array);
        } else {
            permission_denied();
        }
    }

    public function edit($role_id = null)
    {
        $is_exits = $this->roles_model->select_jion(array('roles.id' => decrypt($role_id), 'roles.status' => STATUS_ACTIVE), true, true);

        if ($role_id && !empty($is_exits)) {
            $data['header_title'] = "Role Details";

            $data['role_details'] = $is_exits[0];

            $data['access'] = $this->db->list_fields('roles_permissions');

            $data['groups_list'] = $this->groups_list();

            $this->load->view('admin/header', $data);

            $this->load->view('admin/role_edit');

            $this->load->view('admin/footer');
        } else {
            show_404();
        }
    }

    public function update_role()
    {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('hidden_uid', 'User ID', 'required');

            $this->form_validation->set_rules('role_name', 'Role Name', 'required');

            $this->form_validation->set_rules('groups_id[]', 'Groups', 'required');

            $this->form_validation->set_rules('permission[]', 'Permission', 'required');

            if ($this->form_validation->run() == false) {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');
            } else {
                $frm_details = $this->input->post();

                $field_array = array('role_name' => $frm_details['role_name'],
                    'role_description' => $frm_details['role_description'],
                    'groups_id' => implode(',', $frm_details['groups_id']),
                    'status' => STATUS_ACTIVE,
                    'modified_by' => $this->user_info['id'],
                    'modified_on' => date(DB_DATE_FORMAT),
                );
                $field_array = array_map('strtolower', $field_array);

                if ($this->roles_model->save($field_array, array('id' => $frm_details['hidden_uid']))) {
                    $permission = array();
                    foreach ($this->db->list_fields('roles_permissions') as $key => $value) {
                        if (in_array($value, $frm_details['permission'])) {
                            $permission[$value] = 1;
                        } else {
                            $permission[$value] = 0;
                        }
                    }
                    unset($permission['permissionID']);
                    unset($permission['tbl_roles_id']);
                    $this->roles_model->roles_permission_insert($permission, array('tbl_roles_id' => $frm_details['hidden_uid']));

                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'Role Updated Successfully';

                    $json_array['redirect'] = ADMIN_SITE_URL . 'roles';
                } else {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Something went wrong,please try again';
                }
            }
            echo_json($json_array);
        }
    }

    public function roles_list()
    {
        if ($this->input->is_ajax_request()) {
            $lists = $this->roles_model->select(false, array('id', 'role_name'), array('status' => STATUS_ACTIVE));

            $json_array['message'] = convert_to_single_dimension_array($lists, 'id', 'role_name');

            echo_json($json_array);
        }
    }

    public function delete($role_id = null)
    {
        if ($this->input->is_ajax_request()) {
            $is_exits = $this->roles_model->select(true, array('id'), array('id' => decrypt($role_id)));

            if ($role_id && !empty($is_exits)) {
                $field_array = array('status' => STATUS_DELETED,
                    'modified_on' => date(DB_DATE_FORMAT),
                    'modified_by' => $this->user_info['id'],
                );

                if ($this->roles_model->save($field_array, array('id' => $is_exits['id']))) {
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

    public function get_page_name()
    {

        if ($this->input->is_ajax_request()) {

            $group_id = $this->input->post('id');

            $lists = $this->roles_model->get_page_name_idwise($group_id, array('status' => STATUS_ACTIVE));

            $the_json = json_decode($lists, true);
            $new_array = array_column($the_json, 'group_name');

            $json_array['status'] = SUCCESS_CODE;

            $json_array['message'] = $new_array;

            echo_json($json_array);
        } else {
            permission_denied();
        }
    }

    public function get_page_name_details()
    {

        if ($this->input->is_ajax_request()) {

            $group_id1 = $this->input->post('id');

            $group_id = explode(',', $group_id1);

            $lists = $this->roles_model->get_page_name_idwise($group_id, array('status' => STATUS_ACTIVE));

            $the_json = json_decode($lists, true);
            $new_array = array_column($the_json, 'group_name');

            $json_array['status'] = SUCCESS_CODE;

            $json_array['message'] = $new_array;

            echo_json($json_array);
        } else {
            permission_denied();
        }
    }
/*
public function get_page_name()
{

if($this->input->is_ajax_request())
{

$group_id = $this->input->post('id');

$lists = $this->roles_model->get_page_name($group_id,array('status' => STATUS_ACTIVE));

$the_json = json_decode( $lists);

//print_r($the_json['tbl_admin_menu_id']);         //$json_array['message'] = convert_to_single_dimension_array($lists,'id','role_name');
array_count_values(array_map(function($item) {
return $item['state'];
}, $resellers));

foreach ($the_json as $key => $value){

$singleArray[] = $value;
}

print_r($singleArray);

$list_name = $this->roles_model->get_page_name_id($singleArray,array('status' => STATUS_ACTIVE));

echo_json($json_array);
}
}*/
}
