<?php defined('BASEPATH') or exit('No direct script access allowed');

class Follow_up_reference extends MY_Controller
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

        $this->perm_array = array('page_id' => 71);
        $this->load->model(array('reference_wip_activity_cases_model'));
    }

    public function index()
    {

        $data['header_title'] = "Reference WIP Cases";

        $data['user_list_name'] = $this->users_list_filter();

        $this->load->view('admin/header', $data);

        $this->load->view('admin/reference_wip_activity_cases');

        $this->load->view('admin/footer');
    }

    public function reference_view_datatable_wip_activity()
    {
        log_message('error', 'Reference WIP activity cases');
        try {

                $params = $reference_candidates = $data_arry = $columns = array();

                $params = $_REQUEST;

                 $columns = array('id', 'ClientRefNumber', 'cmp_ref_no', 'CandidateName', 'caserecddate', 'last_modified', 'encry_id', 'verfstatus', 'verification_address', 'additional_comment', 'verifiername', 'verification_date', 'modeofverification', 'closuredate', 'remarks', 'reiniated_date');

                $where_arry = array();

                $reference_candidates = $this->reference_wip_activity_cases_model->get_all_reference_record_datatable_wip_cases(false, $params, $columns);

                $totalRecords = count($this->reference_wip_activity_cases_model->get_all_reference_record_datatable_wip_cases_count(false, $params, $columns));

                $x = 0;

                foreach ($reference_candidates as $reference_candidate) {

                    $data_arry[$x]['id'] = $x + 1;
                    $data_arry[$x]['checkbox'] = $reference_candidate['id'];
                    $data_arry[$x]['ClientRefNumber'] = $reference_candidate['ClientRefNumber'];
                    $data_arry[$x]['cmp_ref_no'] = $reference_candidate['cmp_ref_no'];
                    $data_arry[$x]['CandidateName'] = $reference_candidate['CandidateName'];
                    $data_arry[$x]['reference_com_ref'] = $reference_candidate['reference_com_ref'];
                    $data_arry[$x]['iniated_date'] = convert_db_to_display_date($reference_candidate['iniated_date']);
                    $data_arry[$x]['clientname'] = $reference_candidate['clientname'];
                    $data_arry[$x]['vendor_name'] = $reference_candidate['vendor_name'];
                    $data_arry[$x]['verfstatus'] = $reference_candidate['status_value'];
                    $data_arry[$x]['executive_name'] = $reference_candidate['executive_name'];
                    $data_arry[$x]['name_of_reference'] = $reference_candidate['name_of_reference'];
                    //  $data_arry[$x]['first_qc_approve'] = $reference_candidate['first_qc_approve'];
                    $data_arry[$x]['caserecddate'] = convert_db_to_display_date($reference_candidate['caserecddate']);
                    $data_arry[$x]['last_activity_date'] = convert_db_to_display_date($reference_candidate['last_activity_date'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);
                    $data_arry[$x]['closuredate'] = convert_db_to_display_date($reference_candidate['closuredate']);
                    $data_arry[$x]['due_date'] = convert_db_to_display_date($reference_candidate['due_date']);
                    $data_arry[$x]['tat_status'] = $reference_candidate['tat_status'];
                    $data_arry[$x]['mode_of_veri'] = $reference_candidate['mode_of_veri'];
                    $data_arry[$x]['encry_id'] = ADMIN_SITE_URL . "reference_verificatiion/view_details/" . encrypt($reference_candidate['id']);

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
            log_message('error', 'Wip_activity_cases::reference_view_datatable_wip_activity');
            log_message('error', $e->getMessage());
        }    
    }



}
?>   