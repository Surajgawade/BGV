<?php defined('BASEPATH') or exit('No direct script access allowed');

class Assign_identity extends MY_Controller
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


        $this->perm_array = array('page_id' => 13);
        $this->assign_options = array('0' => 'Select', '1' => 'Assign to Vendor');
        $this->load->model(array('assign_identity_model'));
    }

    public function index()
    {
        $data['header_title'] = "Identity Assign Lists";

        $data['users_list'] = $this->users_list();

        $data['vendor_list'] = $this->vendor_list('identity');

        $data['filter_view'] = $this->filter_view_assign_identity_list();

        $this->load->view('admin/header', $data);

        $this->load->view('admin/assign_identity');

        $this->load->view('admin/footer');
    }

    protected function filter_view_assign_identity_list($true = false)
    {

        if ($true) {
            //$data['status'] = status_frm_db();
            $data['status'] = $this->get_status_candidate();
        } else {
            $data['status'] = $this->get_status();
        }
        $data['clients'] = $this->get_clients_for_list_view_identity();
        $data['user_list_name'] = $this->users_list_filter();

        return $this->load->view('admin/filter_view', $data, true);
    }

    public function get_clients_for_list_view_identity()
    {

        $clients = $this->assign_identity_model->select_client_list_assign_identity_view('clients', false, array('clients.id', 'clients.clientname'));

        $clients_arry[0] = 'Select Client';

        foreach ($clients as $key => $value) {
            $clients_arry[$value['id']] = ucwords(strtolower($value['clientname']));
        }
        return $clients_arry;
    }

    public function identity_view_datatable_assign()
    {
        log_message('error', 'Assign List View in Identity');
        try {
            
            $params = $court_candidates = $data_arry = $columns = array();

            $params = $_REQUEST;

            $columns = array('id', 'ClientRefNumber', 'cmp_ref_no', 'CandidateName', 'caserecddate', 'last_modified', 'encry_id', 'verfstatus', 'verification_address', 'additional_comment', 'verifiername', 'verification_date', 'modeofverification', 'closuredate', 'remarks', 'reiniated_date');

            $identity_candidates = $this->assign_identity_model->get_all_identity_record_datatable_assign(false, $params, $columns);

            $totalRecords = count($this->assign_identity_model->get_all_identity_record_datatable_count_assign(false, $params, $columns));

            $x = 0;
            foreach ($identity_candidates as $identity_candidate) {

                $data_arry[$x]['id'] = $x + 1;
                $data_arry[$x]['checkbox'] = $identity_candidate['id'];
                $data_arry[$x]['ClientRefNumber'] = $identity_candidate['ClientRefNumber'];
                $data_arry[$x]['cmp_ref_no'] = $identity_candidate['cmp_ref_no'];
                $data_arry[$x]['CandidateName'] = $identity_candidate['CandidateName'];
                $data_arry[$x]['identity_com_ref'] = $identity_candidate['identity_com_ref'];
                $data_arry[$x]['iniated_date'] = convert_db_to_display_date($identity_candidate['iniated_date']);
                $data_arry[$x]['clientname'] = $identity_candidate['clientname'];
                $data_arry[$x]['first_qc_approve'] = $identity_candidate['first_qc_approve'];
                $data_arry[$x]['verfstatus'] = $identity_candidate['status_value'];
                $data_arry[$x]['executive_name'] = $identity_candidate['user_name'];

                $data_arry[$x]['id_number'] = (strlen($identity_candidate['id_number']) == 12) ?  wordwrap($identity_candidate['id_number'] , 4 , '-' , true ) : strtoupper($identity_candidate['id_number']);

                $data_arry[$x]['caserecddate'] = convert_db_to_display_date($identity_candidate['caserecddate']);
                $data_arry[$x]['last_activity_date'] = convert_db_to_display_date($identity_candidate['last_activity_date'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);
                $data_arry[$x]['closuredate'] = convert_db_to_display_date($identity_candidate['closuredate']);
                $data_arry[$x]['remarks'] = $identity_candidate['remarks'];
                $data_arry[$x]['tat_status'] = $identity_candidate['tat_status'];
                $data_arry[$x]['due_date'] = convert_db_to_display_date($identity_candidate['due_date']);
                $data_arry[$x]['encry_id'] = ADMIN_SITE_URL . "identity/view_details/" . encrypt($identity_candidate['identity_id']);

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
            log_message('error', 'Assign_identity::identity_view_datatable_assign');
            log_message('error', $e->getMessage());
        }  
            
    }
}
