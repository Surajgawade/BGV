<?php defined('BASEPATH') or exit('No direct script access allowed');

class Hr_database extends MY_Controller
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

        $this->perm_array = array('page_id' => 24);
        $this->load->model('company_database_model');
    }

    public function index()
    {
        $data['header_title'] = "HR Database";

        $this->load->view('admin/header', $data);

        $this->load->view('admin/company_hr_view');

        $this->load->view('admin/footer');
    }

    public function add()
    {

        $data['header_title'] = 'Add HR Database';

        $data['company_list'] = $this->get_company_list();

        $this->load->view('admin/header', $data);

        $this->load->view('admin/company_hr_add');

        $this->load->view('admin/footer');

        //echo $this->load->view('admin/company_hr_add',$data,true);

    }

    public function hr_database_view_datatable()
    {

        if ($this->input->is_ajax_request()) {

            $params = $add_candidates = $data_arry = $columns = array();

            $params = $_REQUEST;

            $columns = array('company_database_verifiers_details.id', 'company_database_verifiers_details.company_database_id', 'company_database_verifiers_details.deputed_company', 'company_database_verifiers_details.verifiers_name', 'company_database_verifiers_details.verifiers_designation', 'company_database_verifiers_details.verifiers_contact_no', 'company_database_verifiers_details.verifiers_email_id');

            $where_arry = array();

            $add_companys = $this->company_database_model->get_all_company_for_datatable($params, $columns);
           
            $totalRecords = count($this->company_database_model->get_all_company_for_datatable_count($params, $columns));

            $x = 0;

            foreach ($add_companys as $add_company) {

                $data_arry[$x]['id'] = $x + 1;
                $data_arry[$x]['created_on'] = convert_db_to_display_date($add_company['created_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);
                $data_arry[$x]['created_by'] = $add_company['created_by'];
                $data_arry[$x]['coname'] = $add_company['coname'];
                $data_arry[$x]['deputed_company'] = $add_company['deputed_company'];
                $data_arry[$x]['verifiers_name'] = $add_company['verifiers_name'];
                $data_arry[$x]['encry_id'] = ADMIN_SITE_URL . "hr_database/view_details/" . $add_company['company_database_verifiers_details_id'];
                $data_arry[$x]['verifiers_designation'] = $add_company['verifiers_designation'];
                $data_arry[$x]['verifiers_contact_no'] = $add_company['verifiers_contact_no'];
                $data_arry[$x]['verifiers_email_id'] = $add_company['verifiers_email_id'];
                $data_arry[$x]['remark'] = $add_company['remark'];
                $data_arry[$x]['modified_on'] = convert_db_to_display_date($add_company['modified_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);
                $data_arry[$x]['modified_by'] = $add_company['modified_by'];
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

    public function view_details($hr_database_id = '')
    {
        $hr_database_details = $this->company_database_model->get_hr_database_details(array('company_database_verifiers_details.id' => $hr_database_id));
   

        if ($hr_database_id && !empty($hr_database_details)) {
            $data['header_title'] = 'Edit HR Database';

            $data['hr_database_details'] = $hr_database_details[0];

            $this->load->view('admin/header', $data);

            $this->load->view('admin/company_hr_edit');

            $this->load->view('admin/footer');
        } else {
            show_404();
        }
    }

    public function save_hr_database()
    {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('nameofthecompany', 'ID', 'required');

            if ($this->form_validation->run() == false) {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');
            } else {

                $frm_details = $this->input->post();

                $field_array = array('company_database_id' => $frm_details['nameofthecompany'],
                    'deputed_company' => $frm_details['deputed_company'],
                    'verifiers_name' => $frm_details['verifiers_name'],
                    'verifiers_designation' => $frm_details['verifiers_designation'],
                    'verifiers_contact_no' => $frm_details['verifiers_contact_no'],
                    'verifiers_email_id' => $frm_details['verifiers_email_id'],
                    'remark' => $frm_details['remark'],
                    'created_by' => $this->user_info['id'],
                    'created_on' => date(DB_DATE_FORMAT),
                );

                $result = $this->company_database_model->save($field_array);

                if ($result) {
                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'Record Successfully Updated';

                    $json_array['redirect'] = ADMIN_SITE_URL . 'hr_database';

                    //$json_array['active_tab'] = 'addrver';
                } else {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Something went wrong,please try again';
                }
                echo_json($json_array);
            }
        } else {
            permission_denied();
        }
    }

    public function update_hr_database()
    {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('hr_database_id', 'ID', 'required');

            if ($this->form_validation->run() == false) {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');
            } else {

                $frm_details = $this->input->post();

                $field_array = array('deputed_company' => $frm_details['deputed_company'],
                    'verifiers_name' => $frm_details['verifiers_name'],
                    'verifiers_designation' => $frm_details['verifiers_designation'],
                    'verifiers_contact_no' => $frm_details['verifiers_contact_no'],
                    'verifiers_email_id' => $frm_details['verifiers_email_id'],
                    'remark' => $frm_details['remark'],
                    'modified_by' => $this->user_info['id'],
                    'modified_on' => date(DB_DATE_FORMAT),
                );

                $result = $this->company_database_model->save($field_array, array('id' => $frm_details['hr_database_id']));

                if ($result) {
                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'Record Successfully Updated';

                    $json_array['redirect'] = ADMIN_SITE_URL . 'hr_database';

                    //$json_array['active_tab'] = 'addrver';
                } else {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Something went wrong,please try again';
                }
                echo_json($json_array);
            }
        } else {
            permission_denied();
        }
    }
}
