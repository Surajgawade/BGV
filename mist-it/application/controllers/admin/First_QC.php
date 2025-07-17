<?php defined('BASEPATH') or exit('No direct script access allowed');

class First_QC extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        if (!$this->is_admin_logged_in()) {
            redirect('admin/login');
            exit();
        }
        $this->perm_array = array('page_id' => 19);
    }

    public function index()
    {
        $data['header_title'] = "First QC Verification";

        $all_component_first_qc = $this->all_component_list_view();

        $data['address'] = $all_component_first_qc['address'];
        $data['Employment'] = $all_component_first_qc['Employment'];

        $data['education'] = $all_component_first_qc['education'];

        $data['reference'] = $all_component_first_qc['reference'];

        $data['court'] = $all_component_first_qc['court'];
        $data['global_db'] = $all_component_first_qc['global_db'];
        $data['drug'] = $all_component_first_qc['drug'];
        $data['pcc'] = $all_component_first_qc['pcc'];
        $data['identity'] = $all_component_first_qc['identity'];
        $data['credit_report'] = $all_component_first_qc['credit_report'];

        $this->load->view('admin/header', $data);

        $this->load->view('admin/firt_qu_componet_view');

        $this->load->view('admin/footer');
    }

    protected function all_component_list_view()
    {
        $this->load->model('first_qc_model');

        $return = array();

        $return['address'] = $this->first_qc_model->get_address_display_first_qc(array('addrverres.first_qc_approve' => 'first qc pending', 'addrverres.var_filter_status' => 'closed'));

        $return['Employment'] = $this->first_qc_model->get_emp_display_first_qc(array('empverres.first_qc_approve' => 'first qc pending', 'empverres.var_filter_status' => 'closed'));

        $return['education'] = $this->first_qc_model->get_education_display_first_qc(array('education_result.first_qc_approve' => 'first qc pending', 'education_result.var_filter_status' => 'closed'));

        $return['reference'] = $this->first_qc_model->get_reference_display_first_qc(array('reference_result.first_qc_approve' => 'first qc pending', 'reference_result.var_filter_status' => 'closed'));

        $return['court'] = $this->first_qc_model->get_court_display_first_qc(array('courtver_result.first_qc_approve' => 'first qc pending', 'courtver_result.var_filter_status' => 'closed'));

        $return['global_db'] = $this->first_qc_model->get_global_display_db_first_qc(array('glodbver_result.first_qc_approve' => 'first qc pending', 'glodbver_result.var_filter_status' => 'closed'));

        $return['drug'] = $this->first_qc_model->get_drug_display_db_first_qc(array('drug_narcotis_result.first_qc_approve' => 'first qc pending', 'drug_narcotis_result.var_filter_status' => 'closed'));

        $return['pcc'] = $this->first_qc_model->get_pcc_display_first_qc(array('pcc_result.first_qc_approve' => 'first qc pending', 'pcc_result.var_filter_status' => 'closed'));
        $return['identity'] = $this->first_qc_model->get_identity_display_first_qc(array('identity_result.first_qc_approve' => 'first qc pending', 'identity_result.var_filter_status' => 'closed'));

        $return['credit_report'] = $this->first_qc_model->get_credit_display_report_first_qc(array('credit_report_result.first_qc_approve' => 'first qc pending', 'credit_report_result.var_filter_status' => 'closed'));

        return $return;
    }

    protected function all_component($candidate_detail, $comp_id)
    {
        $this->load->model('first_qc_model');

        $return = array();

        $return['address'] = $this->first_qc_model->get_address_first_qc(array('addrverres.first_qc_approve' => 'first qc pending', 'addrverres.var_filter_status' => 'closed', 'addrver.id' => $comp_id));

        $return['Employment'] = $this->first_qc_model->get_emp_first_qc(array('empverres.first_qc_approve' => 'first qc pending', 'empverres.var_filter_status' => 'closed', 'empver.id' => $comp_id));

        $return['education'] = $this->first_qc_model->get_education_first_qc(array('education_result.first_qc_approve' => 'first qc pending', 'education_result.var_filter_status' => 'closed', 'education.id' => $comp_id));

        $return['reference'] = $this->first_qc_model->get_reference_first_qc(array('reference_result.first_qc_approve' => 'first qc pending', 'reference_result.var_filter_status' => 'closed', 'reference.id' => $comp_id));

        $return['court'] = $this->first_qc_model->get_court_first_qc(array('courtver_result.first_qc_approve' => 'first qc pending', 'courtver_result.var_filter_status' => 'closed', 'courtver.id' => $comp_id));

        $return['global_db'] = $this->first_qc_model->get_global_db_first_qc(array('glodbver_result.first_qc_approve' => 'first qc pending', 'glodbver_result.var_filter_status' => 'closed', 'glodbver.id' => $comp_id));

        $return['drug'] = $this->first_qc_model->get_drug_db_first_qc(array('drug_narcotis_result.first_qc_approve' => 'first qc pending', 'drug_narcotis_result.var_filter_status' => 'closed', 'drug_narcotis.id' => $comp_id));

        $return['pcc'] = $this->first_qc_model->get_pcc_first_qc(array('pcc_result.first_qc_approve' => 'first qc pending', 'pcc_result.var_filter_status' => 'closed', 'pcc.id' => $comp_id));
        $return['identity'] = $this->first_qc_model->get_identity_first_qc(array('identity_result.first_qc_approve' => 'first qc pending', 'identity_result.var_filter_status' => 'closed', 'identity.id' => $comp_id));

        $return['credit_report'] = $this->first_qc_model->get_credit_report_first_qc(array('credit_report_result.first_qc_approve' => 'first qc pending', 'credit_report_result.var_filter_status' => 'closed', 'credit_report.id' => $comp_id));
        return $return;
    }

    protected function all_component_approve()
    {
        $this->load->model('first_qc_model');

        $return = array();

        $return['address'] = $this->first_qc_model->get_address_first_qc(array('addrverres.first_qc_approve' => 'first qc pending', 'addrverres.var_filter_status' => 'closed'));

        $return['Employment'] = $this->first_qc_model->get_emp_first_qc(array('empverres.first_qc_approve' => 'first qc pending', 'empverres.var_filter_status' => 'closed'));

        $return['education'] = $this->first_qc_model->get_education_first_qc(array('education_result.first_qc_approve' => 'first qc pending', 'education_result.var_filter_status' => 'closed'));

        $return['reference'] = $this->first_qc_model->get_reference_first_qc(array('reference_result.first_qc_approve' => 'first qc pending', 'reference_result.var_filter_status' => 'closed'));

        $return['court'] = $this->first_qc_model->get_court_first_qc(array('courtver_result.first_qc_approve' => 'first qc pending', 'courtver_result.var_filter_status' => 'closed'));

        $return['global_db'] = $this->first_qc_model->get_global_db_first_qc(array('glodbver_result.first_qc_approve' => 'first qc pending', 'glodbver_result.var_filter_status' => 'closed'));

        $return['drug'] = $this->first_qc_model->get_drug_db_first_qc(array('drug_narcotis_result.first_qc_approve' => 'first qc pending', 'drug_narcotis_result.var_filter_status' => 'closed'));

        $return['pcc'] = $this->first_qc_model->get_pcc_first_qc(array('pcc_result.first_qc_approve' => 'first qc pending', 'pcc_result.var_filter_status' => 'closed'));
        $return['identity'] = $this->first_qc_model->get_identity_first_qc(array('identity_result.first_qc_approve' => 'first qc pending', 'identity_result.var_filter_status' => 'closed'));

        $return['credit_report'] = $this->first_qc_model->get_credit_report_first_qc(array('credit_report_result.first_qc_approve' => 'first qc pending', 'credit_report_result.var_filter_status' => 'closed'));
        return $return;
    }

    /*  protected function all_component()
    {
    $this->load->model('first_qc_model');

    $return = array();

    //  $get_client = $this->get_client_entity_package();
    $client_details =  $this->first_qc_model->get_client_entity_package_details_component(array('first_qc' => 1));

    foreach ($client_details as  $client_detail)
    {

    if(!empty($client_detail['first_qc_component_name']))
    {

    $selected_component = explode(',',$client_detail['first_qc_component_name']);

    if(in_array('addrver',$selected_component))
    {

    $return1['address'] = $this->first_qc_model->get_address_first_qc(array('addrverres.first_qc_approve'=>'first qc pending','addrverres.var_filter_status'=>'closed'),$client_detail['tbl_clients_id'],$client_detail['entity'],$client_detail['package']);

    if($return1['address'])
    {

    $return['address'][] =  $return1['address'];
    }
    }

    if(in_array('empver',$selected_component))
    {

    $return1['Employment'] = $this->first_qc_model->get_emp_first_qc(array('empverres.first_qc_approve'=>'first qc pending','empverres.var_filter_status'=>'closed'),$client_detail['tbl_clients_id'],$client_detail['entity'],$client_detail['package']);

    if($return1['Employment'])
    {

    $return['Employment'][] =  $return1['Employment'];
    }
    }

    if(in_array('eduver',$selected_component))
    {

    $return1['education'] = $this->first_qc_model->get_education_first_qc(array('education_result.first_qc_approve'=>'first qc pending','education_result.var_filter_status'=>'closed'),$client_detail['tbl_clients_id'],$client_detail['entity'],$client_detail['package']);

    if($return1['education'])
    {

    $return['education'][] =  $return1['education'];
    }
    }

    if(in_array('refver',$selected_component))
    {

    $return1['reference'] = $this->first_qc_model->get_reference_first_qc(array('reference_result.first_qc_approve'=>'first qc pending','reference_result.var_filter_status'=>'closed'),$client_detail['tbl_clients_id'],$client_detail['entity'],$client_detail['package']);

    if($return1['reference'])
    {
    $return['reference'][] =  $return1['reference'];
    }
    }

    if(in_array('courtver',$selected_component))
    {

    $return1['court'] = $this->first_qc_model->get_court_first_qc(array('courtver_result.first_qc_approve'=>'first qc pending','courtver_result.var_filter_status'=>'closed'),$client_detail['tbl_clients_id'],$client_detail['entity'],$client_detail['package']);

    if($return1['court'])
    {
    $return['court'][] =  $return1['court'];
    }
    }

    if(in_array('globdbver',$selected_component))
    {

    $return1['global_db'] = $this->first_qc_model->get_global_db_first_qc(array('glodbver_result.first_qc_approve'=>'first qc pending','glodbver_result.var_filter_status'=>'closed'),$client_detail['tbl_clients_id'],$client_detail['entity'],$client_detail['package']);

    if($return1['global_db'])
    {
    $return['global_db'][] =  $return1['global_db'];
    }
    }

    if(in_array('narcver',$selected_component))
    {

    $return1['drug'] = $this->first_qc_model->get_drug_db_first_qc(array('drug_narcotis_result.first_qc_approve'=>'first qc pending','drug_narcotis_result.var_filter_status'=>'closed'),$client_detail['tbl_clients_id'],$client_detail['entity'],$client_detail['package']);

    if($return1['drug'])
    {
    $return['drug'][] =  $return1['drug'];
    }
    }

    if(in_array('crimver',$selected_component))
    {

    $return1['pcc'] = $this->first_qc_model->get_pcc_first_qc(array('pcc_result.first_qc_approve'=>'first qc pending','pcc_result.var_filter_status'=>'closed'),$client_detail['tbl_clients_id'],$client_detail['entity'],$client_detail['package']);

    if($return1['pcc'])
    {
    $return['pcc'][] =  $return1['pcc'];
    }
    }

    if(in_array('identity',$selected_component))
    {

    $return1['identity'] = $this->first_qc_model->get_identity_first_qc(array('identity_result.first_qc_approve'=>'first qc pending','identity_result.var_filter_status'=>'closed'),$client_detail['tbl_clients_id'],$client_detail['entity'],$client_detail['package']);

    if($return1['identity'])
    {
    $return['identity'][] =  $return1['identity'];
    }
    }

    if(in_array('cbrver',$selected_component))
    {

    $return1['credit_report'] = $this->first_qc_model->get_credit_report_first_qc(array('credit_report_result.first_qc_approve'=>'first qc pending','credit_report_result.var_filter_status'=>'closed'),$client_detail['tbl_clients_id'],$client_detail['entity'],$client_detail['package']);

    if($return1['credit_report'])
    {
    $return['credit_report'][] =  $return1['credit_report'];
    }
    }

    }
    }

    if(!empty($return['address']))
    {
    $return['address'] =  array_map('current', $return['address']);
    }
    else
    {
    $return['address'] ='';
    }

    if(!empty($return['Employment']))
    {
    $return['Employment'] =  array_map('current', $return['Employment']);
    }
    else
    {
    $return['Employment'] ='';
    }

    if(!empty($return['education']))
    {
    $return['education'] =  array_map('current', $return['education']);
    }
    else
    {
    $return['education'] ='';
    }

    if(!empty($return['reference']))
    {
    $return['reference'] =  array_map('current', $return['reference']);
    }
    else
    {
    $return['reference'] ='';
    }

    if(!empty($return['court']))
    {
    $return['court'] =  array_map('current', $return['court']);
    }
    else
    {
    $return['court'] ='';
    }

    if(!empty($return['global_db']))
    {
    $return['global_db'] =  array_map('current', $return['global_db']);
    }
    else
    {
    $return['global_db'] ='';
    }

    if(!empty($return['drug']))
    {
    $return['drug'] =  array_map('current', $return['drug']);
    }
    else
    {
    $return['drug'] ='';
    }

    if(!empty($return['pcc']))
    {
    $return['pcc'] =  array_map('current', $return['pcc']);
    }
    else
    {
    $return['pcc'] ='';
    }

    if(!empty($return['identity']))
    {
    $return['identity'] =  array_map('current', $return['identity']);
    }
    else
    {
    $return['identity'] ='';
    }

    if(!empty($return['credit_report']))
    {
    $return['credit_report'] =  array_map('current', $return['credit_report']);
    }
    else
    {
    $return['credit_report'] ='';
    }

    // $return['Employment']= $this->first_qc_model->get_emp_first_qc(array('empverres.first_qc_approve'=>'first qc pending'),$get_client['tbl_clients_id'],$get_client['entity'],$get_client['package']);

    //  $return['address'] = $this->first_qc_model->get_address_first_qc(array('addrverres.first_qc_approve'=>'first qc pending','candidates_info.entity' => $get_client['entity'],'candidates_info.package' => $get_client['package'],'candidates_info.clientid' => $get_client['tbl_clients_id']));

    // $return['address'] = $this->first_qc_model->get_address_first_qc(array('addrverres.first_qc_approve'=>'first qc pending','addrverres.var_filter_status'=>'closed'),$get_client['tbl_clients_id'],$get_client['entity'],$get_client['package']);

    /*foreach ($return1['address'] as  $value) {
    $clientid = $value['clientid'];
    $entity_id  = $value['entity'];
    $package_id = $value['package'];

    $check_component_exists = $this->first_qc_model->check_component_exists(array('tbl_clients_id'=>$clientid,'entity'=>$entity_id,'package'=>$package_id));

    $selected_component = explode(',',$check_component_exists[0]['first_qc_component_name']);

    if(in_array('addrver',$selected_component))
    {
    $return['address'] = $this->first_qc_model->get_address_first_qc(array('addrverres.first_qc_approve'=>'first qc pending','addrverres.var_filter_status'=>'closed'),$check_component_exists[0]['tbl_clients_id'],$check_component_exists[0]['entity'],$check_component_exists[0]['package']);

    }

    //  exit();

    }*/

    // print_r( $return['address']);

    // $return['education'] = $this->first_qc_model->get_education_first_qc(array('education_result.first_qc_approve'=>'first qc pending'),$get_client['tbl_clients_id'],$get_client['entity'],$get_client['package']);

    //$return['reference'] = $this->first_qc_model->get_referencefirst_qc(array('reference_result.first_qc_approve'=>'first qc pending'),$get_client['tbl_clients_id'],$get_client['entity'],$get_client['package']);

    // $return['court'] = $this->first_qc_model->get_court_first_qc(array('courtver_result.first_qc_approve'=>'first qc pending'),$get_client['tbl_clients_id'],$get_client['entity'],$get_client['package']);

    // $return['global_db'] = $this->first_qc_model->get_global_db_first_qc(array('glodbver_result.first_qc_approve'=>'first qc pending'),$get_client['tbl_clients_id'],$get_client['entity'],$get_client['package']);

    // $return['drug'] = $this->first_qc_model->get_drug_db_first_qc(array('drug_narcotis_result.first_qc_approve'=>'first qc pending'),$get_client['tbl_clients_id'],$get_client['entity'],$get_client['package']);

    // $return['pcc'] = $this->first_qc_model->get_pcc_first_qc(array('pcc_result.first_qc_approve'=>'first qc pending'),$get_client['tbl_clients_id'],$get_client['entity'],$get_client['package']);

    // $return['identity'] = $this->first_qc_model->get_identity_first_qc(array('identity_result.first_qc_approve'=>'first qc pending'),$get_client['tbl_clients_id'],$get_client['entity'],$get_client['package']);
    /*   return $return;
    }*/

    public function get_client_entity_package()
    {

        $client_details = $this->first_qc_model->get_client_entity_package_details(array('first_qc' => 1));
        $result_array = array();
        foreach ($client_details as $val) {

            foreach ($val as $key => $inner_val) {
                $result_array[$key][] = $inner_val;
            }
        }
        return $result_array;

    }

    public function report_html_form_interim($com_id)
    {
        if ($this->input->is_ajax_request() && $com_id) {
            $this->load->model('candidates_model');

            $candidate_details = $this->candidates_model->get_component_details(array('candidates_info.id' => decrypt($com_id)));

            if (!empty($candidate_details)) {
                $data['candidate_details'] = array_map('ucwords', $candidate_details[0]);

                $data['component_details'] = $this->all_component_approve($candidate_details[0]);

                $this->load->view('admin/report_html_view_first_interim', $data);

            } else {
                echo '<p>Record not found</p>';
            }

        } else {
            echo "<p>Something went wrong, please try again.</p>";
        }
    }

    public function report_html_form_address($com_id)
    {
        if ($this->input->is_ajax_request() && $com_id) {
            $this->load->model('candidates_model');
            $this->load->model('addressver_model');

            $details = $this->addressver_model->get_address_details_first_qc(array('addrver.id' => decrypt($com_id)));

            $candidate_details = $this->candidates_model->get_component_details(array('candidates_info.id' => $details[0]['candsid']));

            if (!empty($candidate_details) && !empty($details)) {
                $data['candidate_details'] = array_map('ucwords', $candidate_details[0]);

                $data['component_details'] = $this->all_component($candidate_details[0], decrypt($com_id));

                $data['components_name'] = "Address";

                $data['details'] = $details;

                $this->load->view('admin/report_html_view_first', $data);

            } else {
                echo '<p>Record not found</p>';
            }

        } else {
            echo "<p>Something went wrong, please try again.</p>";
        }
    }

    public function report_html_form_employment($com_id)
    {
        if ($this->input->is_ajax_request() && $com_id) {
            $this->load->model('candidates_model');
            $this->load->model('employment_model');

            $details = $this->employment_model->get_employment_details_first_qc(array('empver.id' => decrypt($com_id)));

            $candidate_details = $this->candidates_model->get_component_details(array('candidates_info.id' => $details[0]['candsid']));

            if (!empty($candidate_details) && !empty($details)) {
                $data['candidate_details'] = array_map('ucwords', $candidate_details[0]);

                $data['component_details'] = $this->all_component($candidate_details[0], decrypt($com_id));

                $data['components_name'] = "Employment";

                $data['details'] = $details;

                $this->load->view('admin/report_html_view_first', $data);

            } else {
                echo '<p>Record not found</p>';
            }

        } else {
            echo "<p>Something went wrong, please try again.</p>";
        }
    }

    public function report_html_form_education($com_id)
    {
        if ($this->input->is_ajax_request() && $com_id) {
            $this->load->model('candidates_model');
            $this->load->model('education_model');

            $details = $this->education_model->get_education_details_first_qc(array('education.id' => decrypt($com_id)));

            $candidate_details = $this->candidates_model->get_component_details(array('candidates_info.id' => $details[0]['candsid']));

            if (!empty($candidate_details) && !empty($details)) {
                $data['candidate_details'] = array_map('ucwords', $candidate_details[0]);

                $data['component_details'] = $this->all_component($candidate_details[0], decrypt($com_id));

                $data['components_name'] = "Education";

                $data['details'] = $details;

                $this->load->view('admin/report_html_view_first', $data);

            } else {
                echo '<p>Record not found</p>';
            }

        } else {
            echo "<p>Something went wrong, please try again.</p>";
        }
    }

    public function report_html_form_reference($com_id)
    {
        if ($this->input->is_ajax_request() && $com_id) {
            $this->load->model('candidates_model');
            $this->load->model('reference_verificatiion_model');

            $details = $this->reference_verificatiion_model->get_reference_details_first_qc(array('reference.id' => decrypt($com_id)));

            $candidate_details = $this->candidates_model->get_component_details(array('candidates_info.id' => $details[0]['candsid']));

            if (!empty($candidate_details) && !empty($details)) {
                $data['candidate_details'] = array_map('ucwords', $candidate_details[0]);

                $data['component_details'] = $this->all_component($candidate_details[0], decrypt($com_id));

                $data['components_name'] = "Reference";

                $data['details'] = $details;

                $this->load->view('admin/report_html_view_first', $data);

            } else {
                echo '<p>Record not found</p>';
            }

        } else {
            echo "<p>Something went wrong, please try again.</p>";
        }
    }

    public function report_html_form_court($com_id)
    {
        if ($this->input->is_ajax_request() && $com_id) {
            $this->load->model('candidates_model');
            $this->load->model('Court_verificatiion_model');

            $details = $this->Court_verificatiion_model->get_court_details_first_qc(array('courtver.id' => decrypt($com_id)));

            $candidate_details = $this->candidates_model->get_component_details(array('candidates_info.id' => $details[0]['candsid']));

            if (!empty($candidate_details) && !empty($details)) {
                $data['candidate_details'] = array_map('ucwords', $candidate_details[0]);

                $data['component_details'] = $this->all_component($candidate_details[0], decrypt($com_id));

                $data['components_name'] = "Court";

                $data['details'] = $details;

                $this->load->view('admin/report_html_view_first', $data);

            } else {
                echo '<p>Record not found</p>';
            }

        } else {
            echo "<p>Something went wrong, please try again.</p>";
        }
    }

    public function report_html_form_global($com_id)
    {
        if ($this->input->is_ajax_request() && $com_id) {
            $this->load->model('candidates_model');
            $this->load->model('Global_database_model');

            $details = $this->Global_database_model->get_global_db_details_first_qc(array('glodbver.id' => decrypt($com_id)));

            $candidate_details = $this->candidates_model->get_component_details(array('candidates_info.id' => $details[0]['candsid']));

            if (!empty($candidate_details) && !empty($details)) {
                $data['candidate_details'] = array_map('ucwords', $candidate_details[0]);

                $data['component_details'] = $this->all_component($candidate_details[0], decrypt($com_id));

                $data['components_name'] = "Global";

                $data['details'] = $details;

                $this->load->view('admin/report_html_view_first', $data);

            } else {
                echo '<p>Record not found</p>';
            }

        } else {
            echo "<p>Something went wrong, please try again.</p>";
        }
    }

    public function report_html_form_pcc($com_id)
    {
        if ($this->input->is_ajax_request() && $com_id) {
            $this->load->model('candidates_model');
            $this->load->model('Pcc_verificatiion_model');

            $details = $this->Pcc_verificatiion_model->get_pcc_details_first_qc(array('pcc.id' => decrypt($com_id)));

            $candidate_details = $this->candidates_model->get_component_details(array('candidates_info.id' => $details[0]['candsid']));

            if (!empty($candidate_details) && !empty($details)) {
                $data['candidate_details'] = array_map('ucwords', $candidate_details[0]);

                $data['component_details'] = $this->all_component($candidate_details[0], decrypt($com_id));

                $data['components_name'] = "PCC";

                $data['details'] = $details;

                $this->load->view('admin/report_html_view_first', $data);

            } else {
                echo '<p>Record not found</p>';
            }

        } else {
            echo "<p>Something went wrong, please try again.</p>";
        }
    }

    public function report_html_form_identity($com_id)
    {
        if ($this->input->is_ajax_request() && $com_id) {
            $this->load->model('candidates_model');
            $this->load->model('Identity_model');

            $details = $this->Identity_model->get_identity_details_first_qc(array('identity.id' => decrypt($com_id)));

            $candidate_details = $this->candidates_model->get_component_details(array('candidates_info.id' => $details[0]['candsid']));

            if (!empty($candidate_details) && !empty($details)) {
                $data['candidate_details'] = array_map('ucwords', $candidate_details[0]);

                $data['component_details'] = $this->all_component($candidate_details[0], decrypt($com_id));

                $data['components_name'] = "Identity";

                $data['details'] = $details;

                $this->load->view('admin/report_html_view_first', $data);

            } else {
                echo '<p>Record not found</p>';
            }

        } else {
            echo "<p>Something went wrong, please try again.</p>";
        }
    }

    public function report_html_form_credit_report($com_id)
    {
        if ($this->input->is_ajax_request() && $com_id) {
            $this->load->model('candidates_model');
            $this->load->model('Credit_report_model');

            $details = $this->Credit_report_model->get_credit_report_details_first_qc(array('credit_report.id' => decrypt($com_id)));

            $candidate_details = $this->candidates_model->get_component_details(array('candidates_info.id' => $details[0]['candsid']));

            if (!empty($candidate_details) && !empty($details)) {
                $data['candidate_details'] = array_map('ucwords', $candidate_details[0]);

                $data['component_details'] = $this->all_component($candidate_details[0], decrypt($com_id));

                $data['components_name'] = "Credit Report";

                $data['details'] = $details;

                $this->load->view('admin/report_html_view_first', $data);

            } else {
                echo '<p>Record not found</p>';
            }

        } else {
            echo "<p>Something went wrong, please try again.</p>";
        }
    }

    public function report_html_form_drugs($com_id)
    {
        if ($this->input->is_ajax_request() && $com_id) {
            $this->load->model('candidates_model');
            $this->load->model('Drug_verificatiion_model');

            $details = $this->Drug_verificatiion_model->get_drugs_details_first_qc(array('drug_narcotis.id' => decrypt($com_id)));

            $candidate_details = $this->candidates_model->get_component_details(array('candidates_info.id' => $details[0]['candsid']));

            if (!empty($candidate_details) && !empty($details)) {
                $data['candidate_details'] = array_map('ucwords', $candidate_details[0]);

                $data['component_details'] = $this->all_component($candidate_details[0], decrypt($com_id));

                $data['components_name'] = "Drugs";

                $data['details'] = $details;

                $this->load->view('admin/report_html_view_first', $data);

            } else {
                echo '<p>Record not found</p>';
            }

        } else {
            echo "<p>Something went wrong, please try again.</p>";
        }
    }

}
