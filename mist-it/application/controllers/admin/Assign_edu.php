<?php defined('BASEPATH') or exit('No direct script access allowed');

class Assign_edu extends MY_Controller
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

        $this->perm_array = array('page_id' => 6);
        $this->assign_options = array('0' => 'Select', '1' => 'Assign to Vendor');
        $this->load->model(array('assign_edu_model'));
    }

    public function index()
    {
        $data['header_title'] = "Education Verificatiion Lists";

        $data['filter_view'] = $this->filter_view_assign_education_list();
        $data['users_list'] = $this->users_list();
        $data['vendor_list'] = $this->vendor_list('eduver');

        $this->load->view('admin/header', $data);

        $this->load->view('admin/assign_edu');

        $this->load->view('admin/footer');
    }

    protected function filter_view_assign_education_list($true = false)
    {

        if ($true) {
            //$data['status'] = status_frm_db();
            $data['status'] = $this->get_status_candidate();
        } else {
            $data['status'] = $this->get_status();
        }
        $data['clients'] = $this->get_clients_for_list_view();
        $data['user_list_name'] = $this->users_list_filter();

        return $this->load->view('admin/filter_view', $data, true);
    }

    public function get_clients_for_list_view()
    {

        $clients = $this->assign_edu_model->select_client_list_assign_edu_view('clients', false, array('clients.id', 'clients.clientname'));

        $clients_arry[0] = 'Select Client';

        foreach ($clients as $key => $value) {
            $clients_arry[$value['id']] = ucwords(strtolower($value['clientname']));
        }
        return $clients_arry;
    }

    public function education_view_datatable_assign()
    {
        log_message('error', 'Assign List View in Education');
        try {

            $params = $details = $data_arry = $columns = array();

            $params = $_REQUEST;

            $columns = array('education.id', 'ClientRefNumber', 'cmp_ref_no', 'CandidateName', 'caserecddate', 'last_modified', 'encry_id', 'verfstatus', 'verification_address', 'additional_comment', 'verifiername', 'verification_date', 'modeofverification', 'closuredate', 'remarks', 'reiniated_date');

            $details = $this->assign_edu_model->get_all_education_record_datatable_assign(false, $params, $columns);

            $totalRecords = count($this->assign_edu_model->get_all_education_record_datatable_count_assign(false, $params, $columns));

            $x = 0;

            foreach ($details as $detail) {

              /*  $mode_of_verification = $this->assign_edu_model->get_mode_of_verification(array('tbl_clients_id' => $detail['clientid'],'entity' =>$detail['entity'],'package' =>$detail['package'])); 

                if(!empty($mode_of_verification))
                {
                    $mode_of_verification_value = json_decode($mode_of_verification[0]['mode_of_verification']);
                }

                   
               if(isset($mode_of_verification_value->eduver) && ($mode_of_verification_value->eduver != "verbal"))
                {
*/

                    $data_arry[$x]['id'] = $x + 1;
                    $data_arry[$x]['checkbox'] = $detail['id'];
                    $data_arry[$x]['ClientRefNumber'] = $detail['ClientRefNumber'];
                    $data_arry[$x]['cmp_ref_no'] = $detail['cmp_ref_no'];
                    $data_arry[$x]['CandidateName'] = $detail['CandidateName'];
                    $data_arry[$x]['education_com_ref'] = $detail['education_com_ref'];
                    $data_arry[$x]['iniated_date'] = convert_db_to_display_date($detail['iniated_date']);
                    $data_arry[$x]['clientname'] = $detail['clientname'];
                    $data_arry[$x]['vendor_name'] = $detail['vendor_name'];
                    $data_arry[$x]['verfstatus'] = $detail['status_value'];
                    $data_arry[$x]['school_college'] = $detail['school_college'];
                    $data_arry[$x]['university_board'] = $detail['university_board'];
                    $data_arry[$x]['qualification'] = $detail['qualification'];
                    $data_arry[$x]['year_of_passing'] = $detail['year_of_passing'];
                    $data_arry[$x]['executive_name'] = $detail['executive_name'];
                    $data_arry[$x]['first_qc_approve'] = $detail['first_qc_approve'];
                    $data_arry[$x]['caserecddate'] = convert_db_to_display_date($detail['caserecddate']);
                    $data_arry[$x]['last_activity_date'] = convert_db_to_display_date($detail['last_activity_date'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);
                    $data_arry[$x]['closuredate'] = convert_db_to_display_date($detail['closuredate']);
                    $data_arry[$x]['due_date'] = convert_db_to_display_date($detail['due_date']);
                    $data_arry[$x]['tat_status'] = $detail['tat_status'];
                    $data_arry[$x]['encry_id'] = ADMIN_SITE_URL . "education/view_details/" . encrypt($detail['id']);
                    $x++;
             //   }
            }

            $json_data = array(
                "draw" => intval($params['draw']),
                "recordsTotal" => intval($totalRecords),
                "recordsFiltered" => intval($totalRecords),
                "data" => $data_arry,
            );

            echo_json($json_data);

        } catch (Exception $e) {
            log_message('error', 'Assign_edu::education_view_datatable_assign');
            log_message('error', $e->getMessage());
        }    
    }

}
