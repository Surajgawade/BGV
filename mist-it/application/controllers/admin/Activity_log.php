<?php defined('BASEPATH') or exit('No direct script access allowed');

class Activity_log extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!$this->is_admin_logged_in()) {
            redirect('admin/login');
            exit();
        }
        $this->perm_array = array('page_id' => 27, 'direct_access' => true);

        $this->load->model(array('activity_data_model'));
    }

    public function index()
    {
        $data['header_title'] = "Activity Log";

        $data['all_activity_data'] = $this->activity_data_model->activity_all_data();

        $data['activity_mode'] = $this->get_mode_of_activity(array('parent_id' => 0));

        $data['components'] = convert_to_single_dimension_array($this->components(), 'id', 'component_name');

        $this->load->view('admin/header', $data);

        $this->load->view('admin/activity_data_add');

        $this->load->view('admin/footer');
    }

    public function activity_log_view($type, $component_id, $component_name)
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {

            log_message('error', 'Activity Add');
            try {

                $data['type'] = $type;
                $data['component_id'] = $component_id;
                $data['activity_mode'] = $this->get_mode_of_activity(array('components_id' => $type, 'parent_id' => 0, 'status' => 1));
                $data['add_result_url'] = ADMIN_SITE_URL . $component_name . '/add_result_model/' . $component_id;
                echo $this->load->view('admin/activity_frm_view', $data, true);

            } catch (Exception $e) {
                log_message('error', 'Activity_log::activity_log_view');
                log_message('error', $e->getMessage());
            }
        }
    }

    public function activity_log_view_vendor_form($type, $component_id, $component_name)
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {
            log_message('error', 'Activity From Component AQ');
            try {
                if ($type == 6) {
                    $check_exists = $this->activity_data_model->get_emp_details(array('id' => $component_id));

                    if (empty($check_exists[0]['justdialwebcheck']) && empty($check_exists[0]['mcaregn']) && empty($check_exists[0]['domainname']) && empty($check_exists[0]['domainpurch'])) {
                        //print_r($check_exists);
                        $data['type'] = $type;
                        $data['component_id'] = $component_id;
                        $data['activity_mode'] = $this->get_mode_of_activity(array('components_id' => $type, 'parent_id' => 0, 'status' => 1));
                    } else {
                        $data['type'] = '0';
                        $data['component_id'] = '0';
                        $data['activity_mode'] = $this->get_mode_of_activity(array('components_id' => $type, 'parent_id' => 0, 'status' => 1));
                    }
                } else {
                    $data['type'] = '0';
                    $data['component_id'] = '0';
                    $data['activity_mode'] = $this->get_mode_of_activity(array('components_id' => $type, 'parent_id' => 0));
                }

                $activity_mode = array_flip($data['activity_mode']);
                unset($activity_mode['Follow Up']);

                $data['activity_mode'] = array_flip($activity_mode);
                $data['add_result_url'] = ADMIN_SITE_URL . $component_name . '/add_result_model/' . $component_id;
                echo $this->load->view('admin/activity_frm_view', $data, true);

            } catch (Exception $e) {
                log_message('error', 'Activity_log::activity_log_view_vendor_form');
                log_message('error', $e->getMessage());
            }
        }
    }


    public function activity_log_view_address_verification($type, $component_id, $component_name)
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {

            log_message('error', 'Activity Add');
            try {

                $data['type'] = $type;
                $data['component_id'] = $component_id;
                $data['activity_mode'] = $this->get_mode_of_activity(array('components_id' => $type, 'parent_id' => 0, 'status' => 1,'id' => 29));
                $data['add_result_url'] = ADMIN_SITE_URL . $component_name . '/add_result_model/' . $component_id;
                echo $this->load->view('admin/activity_frm_view', $data, true);

            } catch (Exception $e) {
                log_message('error', 'Activity_log::activity_log_view');
                log_message('error', $e->getMessage());
            }
        }
    }


    public function activity_log_view_task($type, $component_id, $component_name, $client_new_cases_id)
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {
            log_message('error', 'Activity Log View Task');
            try {
                $data['activity_mode'] = $this->get_mode_of_activity(array('components_id' => $type, 'parent_id' => 0));

                $data['add_result_url'] = ADMIN_SITE_URL . $component_name . '/add_result_model_task/' . $component_id . '/' . $client_new_cases_id;
                echo $this->load->view('admin/activity_frm_view', $data, true);

            } catch (Exception $e) {
                log_message('error', 'Activity_log::activity_log_view_task');
                log_message('error', $e->getMessage());
            }
        }
    }

    protected function get_mode_of_activity($where)
    {
        log_message('error', 'Mode Of Activity');
        try {
            $get_activity = $this->activity_data_model->select(false, array('activity_name', 'id'), $where);

            return convert_to_single_dimension_array($get_activity, 'id', 'activity_name');
        } catch (Exception $e) {
            log_message('error', 'Activity_log::get_mode_of_activity');
            log_message('error', $e->getMessage());
        }
    }

    public function save_activity()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {

            $this->form_validation->set_rules('comp_table_id', 'Id', 'required');

            $this->form_validation->set_rules('component_type', 'Component Id', 'required');

            if ($this->form_validation->run() == false) {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');
            } else {

                log_message('error', 'All Component Activity Saved');
                try {

                    $frm_details = $this->input->post();

                    if (($frm_details['action_val'] != "Select") || ($frm_details['activity_mode_val'] != "Select Activity") || ($frm_details['activity_type_val'] != "Select Activity") || ($frm_details['activity_status_val'] != "Select Activity")) {

                        $fields = array('ClientRefNumber' => $frm_details['ac_ClientRefNumber'],
                            'candsid' => $frm_details['acti_candsid'],
                            'comp_table_id' => $frm_details['comp_table_id'],
                            'activity_status' => $frm_details['activity_status_val'],
                            'activity_mode' => $frm_details['activity_mode_val'],
                            'activity_type' => $frm_details['activity_type_val'],
                            'next_follow_up_date' => $frm_details['next_follow_up_date'],
                            'action' => $frm_details['action_val'],
                            'remarks' => $frm_details['remarks'],
                            'created_by' => $this->user_info['id'],
                            'created_on' => date(DB_DATE_FORMAT),
                            'component_type' => $frm_details['component_type'],
                        );
                        $result1 = $this->activity_data_model->activity_log_save($fields);

                        if ($result1) {

                            $field = array('candsid' => $frm_details['acti_candsid'],
                                'ClientRefNumber' => $frm_details['ac_ClientRefNumber'],
                                'comp_table_id' => $frm_details['comp_table_id'],
                                'activity_mode' => $frm_details['activity_mode_val'],
                                'activity_status' => $frm_details['activity_status_val'],
                                'activity_type' => $frm_details['activity_type_val'],
                                'action' => $frm_details['action_val'],
                                'next_follow_up_date' => $frm_details['next_follow_up_date'],
                                'remarks' => $frm_details['remarks'],
                                'created_on' => date(DB_DATE_FORMAT),
                                'created_by' => $this->user_info['id'],
                                'is_auto_filled' => 1,

                            );
                            $field_user_activity = array('CandidateName' => $frm_details['CandidateName'],
                                'component_ref_no' => $frm_details['component_ref_no']);

                            $result = $this->activity_data_model->save_trigger_save($field, array('component_type' => $frm_details['component_type']), $field_user_activity);

                            if ($result) {

                                $json_array['message'] = 'Activity Stored Successfully';
                                $json_array['last_id'] = $result1;
                                $json_array['status'] = SUCCESS_CODE;

                            } else {
                                $json_array['message'] = 'Something went wrong, please try again';

                                $json_array['status'] = ERROR_CODE;
                            }
                        } else {
                            $json_array['message'] = 'Something went wrong, please try again';

                            $json_array['status'] = ERROR_CODE;
                        }

                    } else {
                        $json_array['message'] = 'Something went wrong, please try again';
                        $json_array['last_id'] = '';
                        $json_array['status'] = ERROR_CODE;
                    }

                } catch (Exception $e) {
                    log_message('error', 'Activity_log::save_activity');
                    log_message('error', $e->getMessage());
                }
            }
        } else {
            $json_array['message'] = 'Something went wrong, please try again';

            $json_array['status'] = ERROR_CODE;
        }
        echo_json($json_array);
    }

    public function get_stauts_by_component()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {

            log_message('error', 'Get Status by Component');
            try {

                $components_id = $this->input->post('components_id');

                $append_id = $this->input->post('append_id');

                $get_activity = $this->activity_data_model->select(false, array('activity_name', 'id'), array('components_id' => $components_id, 'parent_id' => 0));

                $get_activity = convert_to_single_dimension_array($get_activity, 'id', 'activity_name');
                echo form_dropdown($append_id, $get_activity, 'class="form-control" id="' . $append_id . '" ');

            } catch (Exception $e) {
                log_message('error', 'Activity_log::get_stauts_by_component');
                log_message('error', $e->getMessage());
            }
        }
    }

    public function get_activity_dropdown_mode()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {

            log_message('error', 'Get activity dropdown for  Component');
            try {
                $type = $this->input->post('activity_status');

                $selected = $this->input->post('selected');

                $name = $this->input->post('name');

                if ($name != 'remarks') {
                    $get_activity = $this->activity_data_model->select(false, array('activity_name', 'id'), array('parent_id' => $type));
                    $get_activity = convert_to_single_dimension_array($get_activity, 'id', 'activity_name');
                    echo form_dropdown($name, $get_activity, $selected, 'class="form-control" id="' . $name . '" ');
                } else {
                    $get_activity = $this->activity_data_model->select(true, array('activity_name', 'id', 'activity_remark'), array('id' => $type));

                    echo $get_activity['activity_remark'];
                }

            } catch (Exception $e) {
                log_message('error', 'Activity_log::get_activity_dropdown_mode');
                log_message('error', $e->getMessage());
            }
        }
    }

    public function activity_dropdown_mode()
    {
        $json_array = array();
        if ($this->input->is_ajax_request()) {

            log_message('error', 'Acivity Drop Down');
            try {

                $type = $this->input->post('activity_status');
                $selected = $this->input->post('selected');
                $name = $this->input->post('name');

                if ($name != 'remarks') {

                    $get_activity = $this->activity_data_model->select(false, array('activity_name', 'id'), array('parent_id' => $type));

                    $retunractivity['continue_button'] = $this->activity_data_model->select(true, array('add_result', 'id'), array('id' => $type, 'parent_id' => 0));

                    $retunractivity['list'] = convert_to_single_dimension_array($get_activity, 'id', 'activity_name');

                    echo_json($retunractivity);
                } else {
                    $get_activity = $this->activity_data_model->select(true, array('activity_name', 'id', 'activity_remark'), array('id' => $type));

                    echo $get_activity['activity_remark'];
                }
            } catch (Exception $e) {
                log_message('error', 'Activity_log::activity_dropdown_mode');
                log_message('error', $e->getMessage());
            }
        }
    }

    public function activity_log_databale($comp_table_id = false, $component)
    {

        if ($comp_table_id && $this->input->is_ajax_request()) {

            log_message('error', 'Each Component Activity Tab');
            try {

                switch ($component) {
                    case "1":
                        $data['logs'] = $this->activity_data_model->activity_log_records(array('comp_table_id' => $comp_table_id, 'component_type' => 'address'));
                        break;
                    case "2":
                        $data['logs'] = $this->activity_data_model->activity_log_records(array('comp_table_id' => $comp_table_id, 'component_type' => 'empver'));
                        break;
                    case "3":
                        $data['logs'] = $this->activity_data_model->activity_log_records(array('comp_table_id' => $comp_table_id, 'component_type' => 'education'));

                        break;
                    case "4":
                        $data['logs'] = $this->activity_data_model->activity_log_records(array('comp_table_id' => $comp_table_id, 'component_type' => 'reference'));
                        break;
                    case "5":
                        $data['logs'] = $this->activity_data_model->activity_log_records(array('comp_table_id' => $comp_table_id, 'component_type' => 'court'));
                        break;
                    case "6":
                        $data['logs'] = $this->activity_data_model->activity_log_records(array('comp_table_id' => $comp_table_id, 'component_type' => 'glodbver'));
                        break;
                    case "7":
                        $data['logs'] = $this->activity_data_model->activity_log_records(array('comp_table_id' => $comp_table_id, 'component_type' => 'drug_narcotis'));
                        break;
                    case "8":
                        $data['logs'] = $this->activity_data_model->activity_log_records(array('comp_table_id' => $comp_table_id, 'component_type' => 'pcc'));
                        break;
                    case "9":
                        $data['logs'] = $this->activity_data_model->activity_log_records(array('comp_table_id' => $comp_table_id, 'component_type' => 'identity'));
                        break;
                    case "10":
                        $data['logs'] = $this->activity_data_model->activity_log_records(array('comp_table_id' => $comp_table_id, 'component_type' => 'credit_report'));

                        break;
                    case "11":
                        $data['logs'] = $this->activity_data_model->activity_log_records(array('comp_table_id' => $comp_table_id, 'component_type' => 'social_media'));

                        break;    
                    case "14":
                        break;
                    default:
                        $data['logs'] = "No components found";
                }

                echo $this->load->view('admin/component_activity_data', $data, true);

            } catch (Exception $e) {
                log_message('error', 'Activity_log::activity_log_databale');
                log_message('error', $e->getMessage());
            }

        } else {

            echo "<tr><td>Something went wrong, please try again!<td></tr>";
        }

    }

    public function delete($id = null)
    {
        if ($id) {

            log_message('error', 'delete Massage');
            try {
                $result = $this->activity_data_model->delete(array('id' => $id));

                if ($result) {
                    $this->session->set_flashdata('message', array('message' => 'Records Deleted Successfully', 'class' => 'alert alert-success fade in'));
                } else {
                    $this->session->set_flashdata('message', array('message' => 'Something went wrong, please try again', 'class' => 'alert alert-danger fade in'));
                }

                redirect('admin/activity_log');
            } catch (Exception $e) {
                log_message('error', 'Activity_log::delete');
                log_message('error', $e->getMessage());
            }
        } else {
            show_404();
        }
    }

    public function activity_status()
    {

        log_message('error', 'Get activity by action selection');
        try {
            $activity_name = $this->input->post('activity_status');

            $activity_name = explode(',', $activity_name);

            foreach ($activity_name as $key => $value) {
                if ($value == "") {
                    continue;
                }
                $fields[] = array('activity_name' => $value,
                    'add_result' => $this->input->post('add_result'),
                    'parent_id' => 0,
                    'status' => STATUS_ACTIVE,
                    'components_id' => $this->input->post('components_id'),
                    'created_by' => $this->user_info['id'],
                    'created_on' => date(DB_DATE_FORMAT),
                );
            }

            $result = $this->activity_data_model->inseer_multiple($fields);

            if ($result) {
                $this->session->set_flashdata('message', array('message' => 'Records Inserted Successfully', 'class' => 'alert alert-success fade in'));

                redirect('admin/activity_log');
            } else {
                $this->session->set_flashdata('message', array('message' => 'Something went wrong, please try again', 'class' => 'alert alert-danger fade in'));

                redirect('admin/activity_log');
            }

        } catch (Exception $e) {
            log_message('error', 'Activity_log::activity_status');
            log_message('error', $e->getMessage());
        }
    }

    public function activity_mode()
    {

        log_message('error', 'Get activity by Activity Mode selection');
        try {
            $activity_mode = $this->input->post('activity_mode');

            $activity_mode = explode(',', $activity_mode);

            foreach ($activity_mode as $key => $value) {
                if ($value == "") {
                    continue;
                }
                $fields[] = array('activity_name' => $value,
                    'parent_id' => $this->input->post('activity_mode_id'),
                    'status' => STATUS_ACTIVE,
                    'components_id' => $this->input->post('components_id'),
                    'created_by' => $this->user_info['id'],
                    'created_on' => date(DB_DATE_FORMAT),
                );
            }

            $result = $this->activity_data_model->inseer_multiple($fields);

            if ($result) {
                $this->session->set_flashdata('message', array('message' => 'Records Inserted Successfully', 'class' => 'alert alert-success fade in'));

                redirect('admin/activity_log');
            } else {
                $this->session->set_flashdata('message', array('message' => 'Something went wrong, please try again', 'class' => 'alert alert-danger fade in'));

                redirect('admin/activity_log');
            }

        } catch (Exception $e) {
            log_message('error', 'Activity_log::activity_mode');
            log_message('error', $e->getMessage());
        }
    }

    public function activity_type()
    {
        log_message('error', 'Get activity Type selection');
        try {
            $activity_type = $this->input->post('activity_type');

            $activity_type = explode(',', $activity_type);

            foreach ($activity_type as $key => $value) {
                if ($value == "") {
                    continue;
                }
                $fields[] = array('activity_name' => $value,
                    'parent_id' => $this->input->post('activity_mode_id'),
                    'status' => STATUS_ACTIVE,
                    'components_id' => $this->input->post('components_id'),
                    'created_by' => $this->user_info['id'],
                    'created_on' => date(DB_DATE_FORMAT),
                );
            }

            $result = $this->activity_data_model->inseer_multiple($fields);

            if ($result) {
                $this->session->set_flashdata('message', array('message' => 'Records Inserted Successfully', 'class' => 'alert alert-success fade in'));

                redirect('admin/activity_log');
            } else {
                $this->session->set_flashdata('message', array('message' => 'Something went wrong, please try again', 'class' => 'alert alert-danger fade in'));

                redirect('admin/activity_log');
            }
        } catch (Exception $e) {
            log_message('error', 'Activity_log::activity_type');
            log_message('error', $e->getMessage());
        }
    }

    public function activity_action()
    {

        try {
            $activity_action = $this->input->post('activity_action');

            $active_remarks = $this->input->post('active_remarks');

            $activity_action = explode(',', $activity_action);

            foreach ($activity_action as $key => $value) {
                if ($value == "") {
                    continue;
                }

                $fields[] = array('activity_name' => $value,
                    'parent_id' => $this->input->post('activity_mode_id_action'),
                    'status' => STATUS_ACTIVE,
                    'components_id' => $this->input->post('components_id'),
                    'created_by' => $this->user_info['id'],
                    'activity_remark' => $active_remarks,
                    'created_on' => date(DB_DATE_FORMAT),
                );
            }

            $result = $this->activity_data_model->inseer_multiple($fields);

            if ($result) {
                $this->session->set_flashdata('message', array('message' => 'Records Inserted Successfully', 'class' => 'alert alert-success fade in'));

                redirect('admin/activity_log');
            } else {
                $this->session->set_flashdata('message', array('message' => 'Something went wrong, please try again', 'class' => 'alert alert-danger fade in'));

                redirect('admin/activity_log');
            }

        } catch (Exception $e) {
            log_message('error', 'Activity_log::activity_action');
            log_message('error', $e->getMessage());
        }
    }

}
