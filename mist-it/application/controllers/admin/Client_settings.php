<?php defined('BASEPATH') or exit('No direct script access allowed');

class Client_settings extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!$this->is_admin_logged_in()) {
            redirect('admin/login');
            exit();
        }
        $this->perm_array = array('page_id' => 29);
        $this->load->model('SLA_default_setting_model');
    }

    public function index()
    {
        $data['header_title'] = "Component";

        $data['components'] = $this->common_model->select('components', false, array(), array());

        $this->load->view('admin/header', $data);

        $this->load->view('admin/SLA_list');

        $this->load->view('admin/footer');
    }

    public function default_setting($component_id)
    {
        $data['header_title'] = 'SLA Setting';

        $data['SLA_settings'] = $this->SLA_default_setting_model->sla_setting(array('components.id' => $component_id));

        $data['component_details'] = $this->common_model->select('components', true, array('id', 'component_name'), array('id' => $component_id));

        $data['scope_of_work'] = $this->SLA_default_setting_model->select_scope_of_word(true, array('id', 'scop_of_word'), array('component_id' => $component_id));

        $data['mode_of_verification'] = $this->SLA_default_setting_model->select_mode_of_verification(true, array('id', 'mode_of_verification'), array('component_id' => $component_id));

        $this->load->view('admin/header', $data);

        $this->load->view('admin/sla_add');

        $this->load->view('admin/footer');
    }

    public function save_sla_setting()
    {
        $frm_details = $this->input->post();

        $json_array = array();

        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('component_id', 'Component ID', 'required');
            $this->form_validation->set_rules('particulars', 'Particulars', 'required');
            $this->form_validation->set_rules('section', 'section', 'required');
            $this->form_validation->set_rules('selected_selection', 'Default Section', 'required');

            if ($this->form_validation->run() == false) {
                $json_array['message'] = validation_errors('', '');

                $json_array['status'] = ERROR_CODE;
            } else {
                $frm_details = $this->input->post();

                $field_array = array('component_id' => $frm_details['component_id'],
                    'particulars' => $frm_details['particulars'],
                    'section' => $frm_details['section'],
                    'selected_selection' => $frm_details['selected_selection'],
                    'remarks' => $frm_details['remarks'],
                    'status' => STATUS_ACTIVE,
                    'modified_by' => $this->user_info['id'],
                    'modified_on' => date(DB_DATE_FORMAT),
                );
                $field_array = array_map('strtolower', $field_array);

                if (empty($frm_details['update_id'])) {
                    $result = $this->SLA_default_setting_model->save($field_array);
                } else {
                    $result = $this->SLA_default_setting_model->save($field_array, array('id' => $frm_details['update_id']));
                }

                if ($result) {
                    $json_array['message'] = 'Setting Updated Successfully';

                    $json_array['redirect'] = ADMIN_SITE_URL . 'client_settings';

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

        echo_json($json_array);
    }

    public function save_scope_setting()
    {
        $frm_details = $this->input->post();

        $json_array = array();

        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('component_id', 'Component ID', 'required');
            $this->form_validation->set_rules('scop_of_word', 'Scop of Word', 'required');

            if ($this->form_validation->run() == false) {
                $json_array['message'] = validation_errors('', '');

                $json_array['status'] = ERROR_CODE;
            } else {
                $frm_details = $this->input->post();

                $field_array = array('component_id' => $frm_details['component_id'],
                    'client_id' => 0,
                    'scop_of_word' => $frm_details['scop_of_word'],
                    'status' => STATUS_ACTIVE,
                    'created_by' => $this->user_info['id'],
                    'created_on' => date(DB_DATE_FORMAT),
                );
                $field_array = array_map('strtolower', $field_array);

                if (empty($frm_details['update_id'])) {
                    $result = $this->SLA_default_setting_model->save_scope_of_work($field_array);
                } else {
                    $result = $this->SLA_default_setting_model->save_scope_of_work($field_array, array('id' => $frm_details['update_id']));
                }

                if ($result) {
                    $json_array['message'] = 'Setting Updated Successfully';

                    $json_array['redirect'] = ADMIN_SITE_URL . 'client_settings';

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

        echo_json($json_array);
    }

    public function save_mode_of_verification()
    {
        $frm_details = $this->input->post();

        $json_array = array();

        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('component_id', 'Component ID', 'required');
            $this->form_validation->set_rules('mode_of_verification', 'Mode of Verification', 'required');

            if ($this->form_validation->run() == false) {
                $json_array['message'] = validation_errors('', '');

                $json_array['status'] = ERROR_CODE;
            } else {
                $frm_details = $this->input->post();

                $field_array = array('component_id' => $frm_details['component_id'],
                    'client_id' => 0,
                    'mode_of_verification' => $frm_details['mode_of_verification'],
                    'status' => STATUS_ACTIVE,
                    'created_by' => $this->user_info['id'],
                    'created_on' => date(DB_DATE_FORMAT),
                );
                $field_array = array_map('strtolower', $field_array);

                if (empty($frm_details['update_id'])) {
                    $result = $this->SLA_default_setting_model->save_mode_of_verification($field_array);
                } else {
                    $result = $this->SLA_default_setting_model->save_mode_of_verification($field_array, array('id' => $frm_details['update_id']));
                }

                if ($result) {
                    $json_array['message'] = 'Setting Updated Successfully';

                    $json_array['redirect'] = ADMIN_SITE_URL . 'client_settings';

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

        echo_json($json_array);
    }
}
