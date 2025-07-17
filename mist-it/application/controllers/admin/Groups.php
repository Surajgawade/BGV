<?php defined('BASEPATH') or exit('No direct script access allowed');

class Groups extends MY_Controller
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

        $this->perm_array = array('page_id' => 12);
        $this->load->model(array('groups_model'));
    }

    public function index()
    {
        $data['header_title'] = "Groups List";

        $this->load->view('admin/header', $data);

        $this->load->view('admin/group_view');

        $this->load->view('admin/footer');
    }

    public function fetch_groups()
    {
        if ($this->input->is_ajax_request()) {

            if ($this->permission['access_admin_group_view'] == true) {

                $params = $add_candidates = $data_arry = $columns = array();

                $params = $_REQUEST;

                $columns = array('id', 'role_name', 'role_description', 'created_on', 'user_name');

                $where_arry = array();

                $data_arry = array();

                $results = $this->groups_model->select_join(array('groups.status' => STATUS_ACTIVE), $params, $columns);

                $totalRecords = count($this->groups_model->select_join(array('groups.status' => STATUS_ACTIVE), $params, $columns));

                $x = 0;

                foreach ($results as $key => $value) {

                    $tbl_admin_menu_id = explode(',', $value['tbl_admin_menu_id']);

                    foreach ($tbl_admin_menu_id as $value_menu) {

                        $results = $this->groups_model->select_join_menu(array('admin_menus.id' => $value_menu, 'admin_menus.status' => STATUS_ACTIVE));

                        if (!empty($results[0]['name'])) {

                            $results_menu[] = $results[0]['name'];
                        } else {
                            $results_menu[] = '';
                        }

                    }

                    $data_arry[$x]['id'] = $x + 1;
                    $data_arry[$x]['pages'] = implode(",", $results_menu);
                    $data_arry[$x]['group_name'] = ucwords($value['group_name']);
                    $data_arry[$x]['description'] = ucwords($value['description']);
                    $data_arry[$x]['user_name'] = ucwords($value['user_name']);
                    $data_arry[$x]['encry_id'] = ADMIN_SITE_URL . 'groups/edit/' . encrypt($value['id']);
                    $data_arry[$x]['created_on'] = convert_db_to_display_date($value['created_on'], DB_DATE_FORMAT);

                    $x++;

                    unset($results_menu);

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

        } else {
            permission_denied();
        }
    }

    public function add()
    {
        $data['header_title'] = "Add Group";

        $data['menus'] = $this->get_menu_list();

        $data['clientmgr'] = $this->users_list();

        $this->load->view('admin/header', $data);

        $this->load->view('admin/group_add');

        $this->load->view('admin/footer');
    }

    protected function get_menu_list()
    {
        $result = $this->common_model->select('admin_menus', false, array('id', 'name', 'controllers'), array('status' => STATUS_ACTIVE));

        $return = array();

        foreach ($result as $key => $value) {
            $return[$value['id']] = $value['name'];
        }
        return $return;
    }

    public function ajax_save()
    {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('group_name', 'Group Name', 'required|is_unique[groups.group_name]');

            $this->form_validation->set_rules('admin_menu_id[]', 'Page Permission', 'required');

            $this->form_validation->set_rules('reporting_manager[]', 'Manager', 'required');

            $this->form_validation->set_message('is_unique', "Group name exists, please try another.");

            if ($this->form_validation->run() == false) {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors();
            } else {
                $frm_details = $this->input->post();

                $field_array = array('group_name' => $frm_details['group_name'],
                    'description' => $frm_details['description'],
                    'tbl_admin_menu_id' => implode(',', $frm_details['admin_menu_id']),
                    'reporting_manager' => implode(',', $frm_details['reporting_manager']),
                    'status' => STATUS_ACTIVE,
                    'created_by' => $this->user_info['id'],
                    'created_on' => date(DB_DATE_FORMAT),
                    'modified_by' => $this->user_info['id'],
                    'modified_on' => date(DB_DATE_FORMAT),
                );
                $field_array = array_map('strtolower', $field_array);

                if ($this->groups_model->save($field_array)) {
                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'Group Created Successfully';

                    $json_array['redirect'] = ADMIN_SITE_URL . 'groups';
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

    public function edit($group_id = "")
    {
        $is_exits = $this->groups_model->select(true, array('*'), array('id' => decrypt($group_id), 'status' => STATUS_ACTIVE));

        if ($group_id && !empty($is_exits)) {
            $data['menus'] = $this->get_menu_list();

            $data['clientmgr'] = $this->users_list();

            $data['group_details'] = $is_exits;

            $data['header_title'] = "Edit Group";

            $this->load->view('admin/header', $data);

            $this->load->view('admin/group_edit');

            $this->load->view('admin/footer');

        } else {
            show_404();
        }
    }

    public function update_group()
    {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('group_name', 'Name', 'required');

            $this->form_validation->set_rules('admin_menu_id[]', 'Page Permission', 'required');

            $this->form_validation->set_rules('reporting_manager[]', 'Manager', 'required');

            $this->form_validation->set_rules('update_id', 'Update ID', 'required');

            if ($this->form_validation->run() == false) {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');
            } else {
                $frm_details = $this->input->post();

                $field_array = array('group_name' => $frm_details['group_name'],
                    'description' => $frm_details['description'],
                    'tbl_admin_menu_id' => implode(',', $frm_details['admin_menu_id']),
                    'reporting_manager' => implode(',', $frm_details['reporting_manager']),
                    'status' => STATUS_ACTIVE,
                    'modified_by' => $this->user_info['id'],
                    'modified_on' => date(DB_DATE_FORMAT),
                );
                $field_array = array_map('strtolower', $field_array);

                $update_id = $this->groups_model->save($field_array, array('id' => $frm_details['update_id']));

                if ($update_id) {
                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'Group Updated Successfully';

                    $json_array['redirect'] = ADMIN_SITE_URL . 'groups';
                } else {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Something went wrong, please try again';
                }
            }
            echo_json($json_array);
        }
    }

    public function add_member()
    {
        $data['header_title'] = "Add New Member";

        $this->load->view('admin/header', $data);

        $this->load->view('admin/group_member');

        $this->load->view('admin/footer');
    }

    public function delete()
    {

        $user_id = $this->input->post('user_id');

        if ($this->input->is_ajax_request() && $this->permission['access_admin_list_delete'] == true) {

            $is_exits = $this->groups_model->user_role_gorup_details(array('user_profile.id' => decrypt($user_id)));

            if ($user_id && !empty($is_exits)) {

                $field_array = array('status' => STATUS_DELETED,
                    'modified_on' => date(DB_DATE_FORMAT),
                    'modified_by' => $this->user_info['id'],
                );

                $where_array = array('id' => $is_exits['id']);

                if ($this->groups_model->save($field_array, $where_array)) {
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

    public function gruop_list()
    {
        if ($this->input->is_ajax_request()) {
            $lists = $this->groups_model->select(false, array('id', 'group_name'), array('status' => STATUS_ACTIVE));

            $json_array['message'] = convert_to_single_dimension_array($lists, 'id', 'group_name');

            echo_json($json_array);
        }
    }

}
