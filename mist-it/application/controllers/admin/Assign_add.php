<?php defined('BASEPATH') or exit('No direct script access allowed');

class Assign_add extends MY_Controller
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

        $this->perm_array = array('page_id' => 4);
        $this->assign_options = array('0' => 'Select', '1' => 'Assign to Vendor');
        $this->load->model(array('assign_add_model'));
    }

    public function index()
    {

        $data['header_title'] = "Address Verification";

        $data['filter_view'] = $this->filter_view_assign_address_list();
        $data['users_list'] = $this->users_list();
        $data['vendor_list'] = $this->vendor_list('addrver');

        $this->load->view('admin/header', $data);

        $this->load->view('admin/assign_add');

        $this->load->view('admin/footer');
    }

    protected function filter_view_assign_address_list($true = false)
    {

        if ($true) {
            //$data['status'] = status_frm_db();
            $data['status'] = $this->get_status_candidate();
        } else {
            $data['status'] = $this->get_status();
        }
        $data['clients'] = $this->get_clients_for_list_view_address();
        $data['user_list_name'] = $this->users_list_filter();

        return $this->load->view('admin/filter_view', $data, true);
    }

    public function get_clients_for_list_view_address()
    {

        $clients = $this->assign_add_model->select_client_list_assign_add_view('clients', false, array('clients.id', 'clients.clientname'));

        $clients_arry[0] = 'Select Client';

        foreach ($clients as $key => $value) {
            $clients_arry[$value['id']] = ucwords(strtolower($value['clientname']));
        }
        return $clients_arry;
    }

    public function address_view_datatable_assign()
    {
        log_message('error', 'Assign List View in Address');
        try {

            if ($this->permission['access_address_assign_add_view'] == true) {
                $params = $add_candidates = $data_arry = $columns = array();

                $params = $_REQUEST;

                $columns = array('addrver_id.id', 'candidates_info.ClientRefNumber', 'candidates_info.cmp_ref_no', 'company_database.coname', 'candidates_info.CandidateName', 'candidates_info.caserecddate', 'verfstatus', 'city', 'encry_id', 'address', 'pincode', 'state', 'check_closure_date', 'add_com_ref', 'iniated_date');

                $where_arry = array();

                $add_candidates = $this->assign_add_model->get_all_addrs_by_client_datatable_assign($where_arry, $params, $columns);

                $totalRecords = count($this->assign_add_model->get_all_addrs_by_client_datatable_count_assign($where_arry, $params, $columns));

                $x = 0;

                foreach ($add_candidates as $add_candidate) {
                    
                    $mode_of_verification = $this->assign_add_model->get_mode_of_verification(array('tbl_clients_id' => $add_candidate['clientid'],'entity' =>$add_candidate['entity'],'package' =>$add_candidate['package'])); 
              
                    if(!empty($mode_of_verification))
                    {
                       $mode_of_verification_value = json_decode($mode_of_verification[0]['mode_of_verification']);
                    }

                    if(isset($mode_of_verification_value->addrver) && ($mode_of_verification_value->addrver  != "verbal" ))
                    {


                        $data_arry[$x]['checkbox'] = $add_candidate['id'];
                        $data_arry[$x]['ClientRefNumber'] = $add_candidate['ClientRefNumber'];
                        $data_arry[$x]['mode_of_verification'] = $mode_of_verification_value->addrver;
                        $data_arry[$x]['add_com_ref'] = $add_candidate['add_com_ref'];
                        $data_arry[$x]['cmp_ref_no'] = $add_candidate['cmp_ref_no'];
                        $data_arry[$x]['iniated_date'] = convert_db_to_display_date($add_candidate['iniated_date']);
                        $data_arry[$x]['clientname'] = $add_candidate['clientname'];
                        $data_arry[$x]['vendor_name'] = ($add_candidate['vendor_name'] != '0') ? $add_candidate['vendor_name'] : '';
                        $data_arry[$x]['CandidateName'] = $add_candidate['CandidateName'];
                        $data_arry[$x]['address'] = $add_candidate['address'];
                        $data_arry[$x]['city'] = $add_candidate['city'];
                        $data_arry[$x]['last_activity_date'] = convert_db_to_display_date($add_candidate['last_activity_date'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);
                        $data_arry[$x]['status_value'] = $add_candidate['status_value'];
                        $data_arry[$x]['encry_id'] = ADMIN_SITE_URL . "address/view_details/" . encrypt($add_candidate['id']);
                        $data_arry[$x]['state'] = $add_candidate['state'];
                        $data_arry[$x]['pincode'] = $add_candidate['pincode'];
                        $data_arry[$x]['first_qc_approve'] = $add_candidate['first_qc_approve'];
                        $data_arry[$x]['executive_name'] = $add_candidate['user_name'];
                        $data_arry[$x]['tat_status'] = $add_candidate['tat_status'];
                        $data_arry[$x]['check_closure_date'] = convert_db_to_display_date($add_candidate['due_date']);

                        $x++;

                    } 
                 
                }

                $json_data = array(
                    "draw" => intval($params['draw']),
                    "recordsTotal" => intval($totalRecords),
                    "recordsFiltered" => intval($totalRecords),
                    "data" => $data_arry,
                );

                echo_json($json_data);
            } else {
                $add_candidates = $data_arry = array();

                $json_data = array("draw" => intval(1),
                    "recordsTotal" => "Not Permission",
                    "recordsFiltered" => null,
                    "data" => $data_arry,

                );
                echo_json($json_data);
            }
        } catch (Exception $e) {
            log_message('error', 'Assign_add::address_view_datatable_assign');
            log_message('error', $e->getMessage());
        }    
    }
}
