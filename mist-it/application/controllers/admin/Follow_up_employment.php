<?php defined('BASEPATH') or exit('No direct script access allowed');

class Follow_up_employment extends MY_Controller
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

        $this->perm_array = array('page_id' => 70);
        $this->load->model(array('employment_wip_activity_cases_model'));
    }

    public function index()
    {

        $data['header_title'] = "Employment WIP Cases";

        $data['user_list_name'] = $this->users_list_filter();

        $this->load->view('admin/header', $data);

        $this->load->view('admin/employment_wip_activity_cases');

        $this->load->view('admin/footer');
    }

    public function employment_view_datatable_wip_activity()
    {
        log_message('error', 'Employment WIP activity cases');
        try {

                $params = $employment_lists = $data_arry = $columns = array();

                $params = $_REQUEST;

               $columns = array('empver.id', 'candidates_info.ClientRefNumber', 'candidates_info.cmp_ref_no', 'company_database.coname', 'candidates_info.CandidateName', 'candidates_info.caserecddate', 'verfstatus', 'mail_sent_addrs', 'encry_id', 'employercontactno', 'locationaddr', 'citylocality', 'empfrom', 'empto', 'designation', 'remuneration', 'check_closure_date');


                $where_arry = array();

                $employment_lists = $this->employment_wip_activity_cases_model->get_all_emp_by_client_datatable_wip_activity($where_arry, $params, $columns);

                $totalRecords = count($this->employment_wip_activity_cases_model->get_all_emp_by_client_datatable_count_wip_activity($where_arry, $params, $columns));

                $x = 0;

                foreach ($employment_lists as $employment_list) {

                    $data_arry[$x]['id'] = $x + 1;
                    $data_arry[$x]['checkbox'] = $employment_list['id'];
                    $data_arry[$x]['ClientRefNumber'] = $employment_list['ClientRefNumber'];
                    $data_arry[$x]['cmp_ref_no'] = $employment_list['cmp_ref_no'];
                    $data_arry[$x]['emp_com_ref'] = $employment_list['emp_com_ref'];
                    $data_arry[$x]['clientname'] = $employment_list['clientname'];
                    $data_arry[$x]['field_status'] = $employment_list['field_visit_status'];

                    $data_arry[$x]['coname'] = ucwords($employment_list['coname']);
                    $data_arry[$x]['CandidateName'] = ucwords($employment_list['CandidateName']);
                    $data_arry[$x]['caserecddate'] = convert_db_to_display_date($employment_list['caserecddate']);
                    $data_arry[$x]['iniated_date'] = convert_db_to_display_date($employment_list['iniated_date']);
                   
                    $data_arry[$x]['empid'] = $employment_list['empid'];
                    $data_arry[$x]['encry_id'] = ADMIN_SITE_URL . "employment/view_details/" . encrypt($employment_list['id']);
                    $data_arry[$x]['verfstatus'] = $employment_list['status_value'];
                    $data_arry[$x]['executive_name'] = $employment_list['user_name'];
                    $data_arry[$x]['has_assigned_on'] = convert_db_to_display_date($employment_list['has_assigned_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);
                    $data_arry[$x]['created_on'] = convert_db_to_display_date($employment_list['created_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);
                    $data_arry[$x]['due_date'] = convert_db_to_display_date($employment_list['due_date']);
                    $data_arry[$x]['tat_status'] = $employment_list['tat_status'];
                    $data_arry[$x]['mode_of_veri'] = $employment_list['mode_of_veri'];

                    $x++;
                }

                $json_data = array(
                    "draw" => intval($params['draw']),
                    "recordsTotal" => intval($totalRecords),
                    "recordsFiltered" => intval($totalRecords),
                    "data" => $data_arry,
                );

                echo_json($json_data);
            
            
        } catch (Exception $e) {
            log_message('error', 'Wip_activity_cases::employment_view_datatable_wip_activity');
            log_message('error', $e->getMessage());
        }    
    }



}
?>   