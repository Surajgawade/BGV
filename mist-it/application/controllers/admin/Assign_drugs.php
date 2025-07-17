<?php defined('BASEPATH') or exit('No direct script access allowed');

class Assign_drugs extends MY_Controller
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
        
        $this->perm_array = array('page_id' => 57);
        $this->assign_options = array('0' => 'Select', '1' => 'Assign to Vendor');
        $this->load->model(array('assign_drug_verificatiion_model'));
    }

    public function index()
    {
        $data['header_title'] = "Drug Verificatiion Lists";

        $data['filter_view'] = $this->filter_view();

        $data['users_list'] = $this->users_list();
        $data['vendor_list'] = $this->vendor_list('narcver');

        $this->load->view('admin/header', $data);

        $this->load->view('admin/assign_drugs');

        $this->load->view('admin/footer');
    }

    public function drug_view_datatable_assign()
    {
        log_message('error', 'Assign List View in Drugs');
        try {

            $params = $details = $data_arry = $columns = array();

            $params = $_REQUEST;

            $columns = array('drug_narcotis.id', 'ClientRefNumber', 'cmp_ref_no', 'CandidateName', 'caserecddate', 'last_modified', 'encry_id', 'verfstatus', 'verification_address', 'additional_comment', 'verifiername', 'verification_date', 'modeofverification', 'closuredate', 'remarks', 'reiniated_date');

            $details = $this->assign_drug_verificatiion_model->get_all_drug_record_datatable_assign(false, $params, $columns);

            $totalRecords = count($this->assign_drug_verificatiion_model->get_all_drug_record_datatable_count_assign(false, $params, $columns));

            $x = 0;
            foreach ($details as $detail) {
                $data_arry[$x]['id'] = $x + 1;
                $data_arry[$x]['checkbox'] = $detail['id'];
                $data_arry[$x]['ClientRefNumber'] = $detail['ClientRefNumber'];
                $data_arry[$x]['cmp_ref_no'] = $detail['cmp_ref_no'];
                $data_arry[$x]['CandidateName'] = $detail['CandidateName'];
                $data_arry[$x]['drug_com_ref'] = $detail['drug_com_ref'];
                $data_arry[$x]['iniated_date'] = convert_db_to_display_date($detail['iniated_date']);
                $data_arry[$x]['clientname'] = $detail['clientname'];
                $data_arry[$x]['vendor_name'] = ($detail['vendor_name'] != '0') ? $detail['vendor_name'] : '';
                $data_arry[$x]['verfstatus'] = $detail['status_value'];
                $data_arry[$x]['executive_name'] = $detail['user_name'];
                $data_arry[$x]['drug_test_code'] = $detail['drug_test_code'];
                $data_arry[$x]['street_address'] = $detail['street_address'];
                $data_arry[$x]['city'] = $detail['city'];
                $data_arry[$x]['pincode'] = $detail['pincode'];
                $data_arry[$x]['state'] = $detail['state'];
                $data_arry[$x]['caserecddate'] = convert_db_to_display_date($detail['caserecddate']);
                $data_arry[$x]['closuredate'] = convert_db_to_display_date($detail['closuredate']);
                $data_arry[$x]['first_qc_approve'] = $detail['first_qc_approve'];
                $data_arry[$x]['remarks'] = $detail['remarks'];
                $data_arry[$x]['due_date'] = convert_db_to_display_date($detail['due_date']);
                $data_arry[$x]['tat_status'] = $detail['tat_status'];
                $data_arry[$x]['last_activity_date'] = convert_db_to_display_date($detail['last_activity_date'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);
                $data_arry[$x]['encry_id'] = ADMIN_SITE_URL . "drugs_narcotics/view_details/" . encrypt($detail['drug_narcotis_id']);
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
            log_message('error', 'Assign_drugs::drug_view_datatable_assign');
            log_message('error', $e->getMessage());
        }    
    }
}
