<?php defined('BASEPATH') or exit('No direct script access allowed');

class Candidates extends MY_Client_Cotroller
{
    public function __construct()
    {
        parent::__construct();

        if (!$this->is_client_logged_in()) {
            redirect('client/login');
            exit();
        }
        $this->perm_array = array('direct_access' => true);
        $this->load->model(array('client/candidates_model'));
        $this->load->library('email');
    }

    public function index()
    { 
       

        $data['header_title'] = "Candidates - List View";

        $data['my_components'] = $this->my_components();

        $data['clients'] = $this->get_clients(array('status' => STATUS_ACTIVE));

        $data['status'] = $this->get_status_candidate();

        $data['selected_status'] = 'WIP';

        $this->load->view('client/header', $data);

        $this->load->view('client/candidates_list_all');

        $this->load->view('client/footer');

    }

    public function insufficiency()
    {
        $data['header_title'] = "Candidates - List View";

        $data['my_components'] = $this->my_components();

        $data['clients'] = $this->get_clients(array('status' => STATUS_ACTIVE));

        $data['status'] = $this->get_status_candidate();

        $data['selected_status'] = 'Insufficiency';

        $this->load->view('client/header', $data);

        $this->load->view('client/candidates_list_all');

        $this->load->view('client/footer');

    }

    public function closed()
    {
        $data['header_title'] = "Candidates - List View";

        $data['my_components'] = $this->my_components();

        $data['clients'] = $this->get_clients(array('status' => STATUS_ACTIVE));

        $data['status'] = $this->get_status_candidate();

        $data['selected_status'] = 'Closed';

        $this->load->view('client/header', $data);

        $this->load->view('client/candidates_list_all');

        $this->load->view('client/footer');

    }

    public function all()
    {
        $data['header_title'] = "Candidates - List View";

        $data['my_components'] = $this->my_components();

        $data['clients'] = $this->get_clients(array('status' => STATUS_ACTIVE));

        $data['status'] = $this->get_status_candidate();

        $data['selected_status'] = 'All';

        $this->load->view('client/header', $data);

        $this->load->view('client/candidates_list_all');

        $this->load->view('client/footer');
    }

    public function add_candiddate_view()
    {
        $data['header_title'] = "Candidates List";

        $data['my_components'] = $this->my_components();

        $data['clients'] = $this->get_clients(array('status' => STATUS_ACTIVE));

        $data['status'] = $this->get_status_candidate();

        $data['selected_status'] = 'WIP';


        $this->load->view('client/header', $data);

        $this->load->view('client/candidates_list');

        $this->load->view('client/footer');
    }

    public function initiation_list()
    {
        $data['header_title'] = "Initiation Candidates List";

    
        $this->load->view('client/header', $data);

        $this->load->view('client/initiation_list');

        $this->load->view('client/footer');
    }


    public function pre_post_list()
    {
        $data['header_title'] = "Pre Post Candidates List";

    
        $this->load->view('client/header', $data);

        $this->load->view('client/pre_post_list');

        $this->load->view('client/footer');
    }

    
    public function get_candidate_mail_list()
    {
        $data['header_title'] = "Pending From Candidate";

        echo $this->load->view('client/candidate_mail_list_view',$data ,true);

    }

    public function check_candidate_add_component()
    { 
        try {
            $json_array = array();
            if ($this->input->is_ajax_request()) {

                $frm_details = $this->input->post();

                $check_component = $this->candidates_model->check_candidate_comp($frm_details['clientid'],$frm_details['entity'],$frm_details['package']);  
           
                if(!empty($check_component)){
            
                    $json_array['status'] = SUCCESS_CODE;
          
                    $json_array['message'] = $check_component[0];
                } else {
                    $json_array['status'] = ERROR_CODE;
        
                    $json_array['message'] = 'Something went wrong';
                }
            }

            $this->echo_json($json_array);
        } catch (Exception $e) {
            log_message('error', 'Client Side Controller');
            log_message('error', 'Candidate::check_candidate_add_component');
            log_message('error', $e->getMessage());
        }

          
    }

    protected function cmp_ref_no($insert_id)
    {
        $name = COMPONENT_REF_NO['CANDIDATES'];;
        $cmp_ref_no = $name . $insert_id;
        $field_array = array('cmp_ref_no' => $cmp_ref_no);
        $update_auto_increament_id = $this->candidates_model->update_auto_increament_value($field_array, array('id' => $insert_id));
        return $cmp_ref_no;
    }

    protected function rand_string($length)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@%+\/!^?()#{}[]~-_";
        return substr(str_shuffle($chars), 0, $length);
    }

    protected function rand_string_value($length)
    {
        $chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        return substr(str_shuffle($chars), 0, $length);
    }

    

    public function add_candidate()
    {
        if ($this->input->is_ajax_request()) {
            $frm_details = $this->input->post();

            $this->form_validation->set_rules('clientid', 'Client', 'required');

            $this->form_validation->set_rules('entity', 'Entity', 'required');

            $this->form_validation->set_rules('package', 'Package', 'required');


            $this->form_validation->set_rules('caserecddate', 'Case received', 'required');


            $this->form_validation->set_rules('CandidateName', 'Candidate Name', 'required');

            if ($this->form_validation->run() == false) {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');
            } else {


                $client_ref_number = str_replace(" ", "", $frm_details['ClientRefNumber']);

                if(isset($frm_details['create_client_id']))
                {
                    for ($i=1; $i < 1000; $i++) { 
                        $check_client_ref_exists =  $this->candidates_model->select_client_admin(true, array('ClientRefNumber'), array('ClientRefNumber' =>$client_ref_number.'-'.$i, 'status' => '1'));

                        if (empty($check_client_ref_exists)) {
                            $client_ref_number = $client_ref_number.'-'.$i;
                             break;
                        } else {
                            continue;
                        }
                    }
                }

                $field_array = array('clientid' => $frm_details['clientid'],
                    'entity' => $frm_details['entity'],
                    'package' => $frm_details['package'],
                    'caserecddate' => convert_display_to_db_date($frm_details['caserecddate']),
                    'ClientRefNumber' => $client_ref_number,
                    'CandidateName' => $frm_details['CandidateName'],
                    'DateofBirth' => convert_display_to_db_date($frm_details['DateofBirth']),
                    "gender" => $frm_details['gender'],
                    "prasent_address" => $frm_details['prasent_address'],
                    "cands_city" => $frm_details['cands_city'],
                    "cands_state" => $frm_details['cands_state'],
                    "cands_pincode" => $frm_details['cands_pincode'],
                    "cands_country" => 'India',
                    "grade" => $frm_details['grade'],
                    'NameofCandidateFather' => $frm_details['NameofCandidateFather'],
                    'MothersName' => $frm_details['MothersName'],
                    'CandidatesContactNumber' => $frm_details['CandidatesContactNumber'],
                    'ContactNo1' => $frm_details['ContactNo1'],
                    'ContactNo2' => $frm_details['ContactNo2'],
                    'DateofJoining' => convert_display_to_db_date($frm_details['DateofJoining']),
                    'Location' => $frm_details['Location'],
                    'DesignationJoinedas' => $frm_details['DesignationJoinedas'],
                    'Department' => $frm_details['Department'],
                    'EmployeeCode' => $frm_details['EmployeeCode'],
                    'PANNumber' => $frm_details['PANNumber'],
                    'AadharNumber' => $frm_details['AadharNumber'],
                    'PassportNumber' => $frm_details['PassportNumber'],
                    'cands_email_id' => $frm_details['cands_email_id'],
                    'branch_name' => $frm_details['branch_name'],
                    'remarks' => $frm_details['remarks'],
                    'overallstatus' => $frm_details['overallstatus'],
                    'created_by' => 1,
                    'created_on' => date(DB_DATE_FORMAT),

                    'status' => 1,
                );

                $field_array = array_map('strtolower', $field_array);

                $result = $this->candidates_model->save($field_array);
                try {
                    if ($result) {
                        $result_main_table = $this->candidates_model->save_actual_table($field_array);
                        $cmp_ref_no = $this->cmp_ref_no($result_main_table);

                        $id = $result;

                        $random_password = $this->rand_string(8);

                        $email_password = create_password($random_password);

                        $password_en_de = base64_encode($random_password);

                        $result_cmp_update = $this->candidates_model->save(array('cmp_ref_no' => $cmp_ref_no, 'cands_info_id' => $result_main_table), array('id' => $id));

                        $field_array2 = array('candidates_info_id' => $result, 'cmp_ref_no' => $cmp_ref_no);

                        $field_array1 = array_merge($field_array, $field_array2);

                        $field_array3 = array('candidates_info_id' => $result_main_table, 'cmp_ref_no' => $cmp_ref_no);

                        $field_array4 = array_merge($field_array, $field_array3);

                        $result_candidate_log = $this->candidates_model->save_candidate_log_main($field_array4);

                        $result_candidate = $this->candidates_model->save_candidate($field_array1);
                   
                        if(isset($frm_details['add_candidate_mail'])  && ($frm_details['add_candidate_mail'] == 'on'))
                        {
                            $unique_value = $this->generating_unique_number($cmp_ref_no);
                            $candidate_details['public_key'] = $unique_value['public_key'];
                            $candidate_details['private_key'] = $unique_value['private_key'];

                            $component_details = $this->candidates_model->get_entity_package_component(array('clients_details.tbl_clients_id' => $frm_details['clientid'],'entity'=>$frm_details['entity'],'package' => $frm_details['package']));
  
                            if(!empty($component_details))
                            {
                                $component_ids = $component_details[0]['component_id'];

                                $component_id =  explode(',',$component_ids);

                                if(in_array('addrver', $component_id))
                                {
                                    $candidate_details['address_component_check'] = 1;

                                    $this->load->model('addressver_model');

                                    $tat_day = $this->get_client_entity_package_tat_day(array('tbl_clients_id' => $frm_details['clientid'], 'entity' => $frm_details['entity'], 'package' => $frm_details['package']));

                                   $get_holiday1 = $this->get_holiday();
                           

                                   $get_holiday = array_map('current', $get_holiday1);

                                   $closed_date = getWorkingDays(date(DATE_ONLY), $get_holiday, $tat_day[0]['tat_addrver']);

                                   $mode_of_verification = $this->addressver_model->get_mode_of_verification(array('entity' => $frm_details['entity'], 'package' => $frm_details['package'], 'tbl_clients_id' => $frm_details['clientid']));

                                    if(!empty($mode_of_verification)){
                                        $mode_of_verification_veri = json_decode($mode_of_verification[0]['mode_of_verification']);
                                        $mode_of_verification_value =  $mode_of_verification_veri->addrver;
                                    }
                                    else{
                                        $mode_of_verification_value =  '';
                                    }


                                    $has_case_id = $this->addressver_model->get_reporting_manager_id($frm_details['clientid']);
                    

                                    $field_array = array('clientid' => $frm_details['clientid'],
                                                        'candsid' => $result_main_table,
                                                        'mod_of_veri' => $mode_of_verification_value,
                                                        'created_on' => date(DB_DATE_FORMAT),
                                                        'modified_on' => date(DB_DATE_FORMAT),
                                                        'has_case_id' => $has_case_id[0]['clientmgr'],
                                                        'has_assigned_on' => date(DB_DATE_FORMAT),
                                                        'is_bulk_uploaded' => 0,
                                                        'iniated_date' => date(DATE_ONLY),
                                                        "add_re_open_date" => '',
                                                        "due_date" => $closed_date,
                                                        "tat_status" => 'IN TAT',
                                                        "created_by" => 1,
                                                        'fill_by' => 2,
                                    );
    
                                    $result_address_insert = $this->addressver_model->save(array_map('strtolower', $field_array));

                                    $add_com_ref = $this->add_com_ref($result_address_insert);

                                    $result_address_update = $this->addressver_model->save(array('add_com_ref'=>$add_com_ref),array('id'=>$result_address_insert));

                                    $mode_of_verification = $this->addressver_model->get_mode_of_verification(array('tbl_clients_id' => $frm_details['clientid'],'entity' =>$frm_details['entity'],'package' =>$frm_details['package'])); 

                                    if(!empty($mode_of_verification))
                                    {
                                       $mode_of_verification_value = json_decode($mode_of_verification[0]['mode_of_verification']);
                                    }


                                    if(isset($mode_of_verification_value->addrver) && ($mode_of_verification_value->addrver  == "digital"))
                                    { 
                                        $update = $this->addressver_model->upload_vendor_assign('addrver', array('vendor_digital_id' => 25, 'vendor_digital_list_mode' => 'digital', 'vendor_digital_assgined_on' => date(DB_DATE_FORMAT)), array('id' => $result_address_insert, 'vendor_id =' => 0));

                                        $update1 = $this->addressver_model->update_status('addrverres', array('verfstatus' => 1), array('addrverid' => $result_address_insert));

                                        $this->cli_address_invite_mail($result_address_insert);

                                        if($update && $update1)
                                        {
                                        
                                            $vendor_name =  $this->addressver_model->select_address_dt("vendors",array("vendor_name"), array("id" => 25));
                                
                                            $result_address_activity_data = $this->addressver_model->initiated_date_addrver_activity_data(array('created_on' =>date(DB_DATE_FORMAT),'created_by' => 1, 'comp_table_id' => $result_address_insert, 'activity_type' => "Mist it", 'activity_status' => "Assign", 'remarks' => 'Candidate has assigned the case to '.$vendor_name[0]['vendor_name']));
                                        }
                                    } 



                                    $addrverid = $result_address_insert;

                                    $insff_reason = 'Others';

                                    $insuff_remarks = "Component Pending From Candidate";

                                    $insff_date = date(DATE_ONLY);

                                
                                    $check = $this->addressver_model->select_insuff(array('addrverid' =>  $result_address_insert, 'status !=' => 3, 'insuff_clear_date is null' => null));

                                    if (empty($check)) {
                                        $result = $this->addressver_model->save_update_insuff(array('insuff_raised_date' => $insff_date, 'insuff_raise_remark' => $insuff_remarks, 'status' => STATUS_ACTIVE, 'insff_reason' => $insff_reason,'addrverid' => $addrverid,"created_by" => 1, 'auto_stamp' => 1));
                                    }
         
                                }
                                if(in_array('empver', $component_id))
                                {
                                    
                                    $candidate_details['employment_component_check'] = 1;

                                    $this->load->model('employment_model');

                                    $tat_day = $this->get_client_entity_package_tat_day(array('tbl_clients_id' => $frm_details['clientid'], 'entity' => $frm_details['entity'], 'package' => $frm_details['package']));

                                    $get_holiday1 = $this->get_holiday();

                                    $get_holiday = array_map('current', $get_holiday1);

                                    $closed_date = getWorkingDays(date(DATE_ONLY), $get_holiday, $tat_day[0]['tat_empver']);

                                   $mode_of_verification = $this->employment_model->get_mode_of_verification(array('entity' => $frm_details['entity'], 'package' => $frm_details['package'], 'tbl_clients_id' => $frm_details['clientid']));

                                    if(!empty($mode_of_verification)){
                                        $mode_of_verification_veri = json_decode($mode_of_verification[0]['mode_of_verification']);
                                        $mode_of_verification_value = $mode_of_verification_veri->empver;
                                    }
                                    else{
                                        $mode_of_verification_value =  '';
                                    }


                                    $has_case_id = $this->employment_model->get_reporting_manager_id_client($frm_details['clientid']);


                                    $field_array = array('clientid' => $frm_details['clientid'],
                                        'candsid' => $result_main_table,
                                        'emp_com_ref' => '',
                                        'nameofthecompany' => 7744,
                                        'mode_of_veri' => $mode_of_verification_value,
                                        'iniated_date' => date(DATE_ONLY),
                                        'created_on' => date(DB_DATE_FORMAT),
                                        'modified_on' => date(DB_DATE_FORMAT),
                                        'has_case_id' => $has_case_id[0]['clientmgr'],
                                        "due_date" => $closed_date,
                                        "created_by" => 1,
                                        'is_bulk_uploaded' => 0,
                                        "tat_status" => 'IN TAT',
                                        'fill_by' => 2,
                                    );

                                    $result_employment_insert = $this->employment_model->save(array_map('strtolower', $field_array));

                                    $emp_com_ref = $this->emp_com_ref($result_employment_insert);

                                    $result_employment_update = $this->employment_model->save(array('emp_com_ref'=>$emp_com_ref),array('id'=>$result_employment_insert));

                                    $empverres_id = $result_employment_insert;

                                    $insff_reason = "Others";

                                    $insuff_remarks = "Component Pending From Candidate";

                                    $insff_date = date(DATE_ONLY);
    
                                    $check = $this->employment_model->select_insuff(array('empverres_id' => $empverres_id, 'empverres_insuff.status !=' => 3, 'insuff_clear_date is null' => null));

                                    if (empty($check)) {
                                      $result = $this->employment_model->save_update_insuff(array('insuff_raised_date' =>$insff_date, 'insuff_raise_remark' => $insuff_remarks, 'status' => STATUS_ACTIVE, 'insff_reason' => $insff_reason,'empverres_id' => $empverres_id,"created_by" => 1, 'auto_stamp' => 1));
                                    }

             
                                }
                                if(in_array('eduver', $component_id))
                                {
                                    $candidate_details['education_component_check'] = 1;

                                    $this->load->model('education_model'); 
                                    
                                    $tat_day = $this->get_client_entity_package_tat_day(array('tbl_clients_id' => $frm_details['clientid'], 'entity' => $frm_details['entity'], 'package' => $frm_details['package']));

                                    $get_holiday1 = $this->get_holiday();

                                    $get_holiday = array_map('current', $get_holiday1);

                                    $closed_date = getWorkingDays(date(DATE_ONLY), $get_holiday, $tat_day[0]['tat_eduver']);

                                    $has_case_id = $this->education_model->get_reporting_manager_id_client($frm_details['clientid']);

                                    $mode_of_verification = $this->education_model->get_mode_of_verification(array('entity' => $frm_details['entity'], 'package' => $frm_details['package'], 'tbl_clients_id' => $frm_details['clientid']));

                                    if(!empty($mode_of_verification)){
                                        $mode_of_verification_veri = json_decode($mode_of_verification[0]['mode_of_verification']);
                                        $mode_of_verification_value = $mode_of_verification_veri->eduver;
                                    }
                                    else{
                                        $mode_of_verification_value =  '';
                                    }

                                    $field_array = array('clientid' => $frm_details['clientid'],
                                                         'candsid' => $result_main_table,
                                                         'education_com_ref' => '',
                                                         'university_board' => 2476,
                                                         'qualification' => 401,
                                                         'iniated_date' => date(DATE_ONLY),
                                                         'mode_of_veri' => $mode_of_verification_value,
                                                         'created_on' => date(DB_DATE_FORMAT),
                                                         'modified_on' => date(DB_DATE_FORMAT),
                                                         "due_date" => $closed_date,
                                                         'has_case_id' => $has_case_id[0]['clientmgr'],
                                                         "tat_status" => 'IN TAT',
                                                         "created_by" => 1,
                                                         'fill_by' => 2,
                                    );

                                    $result_education_insert = $this->education_model->save(array_map('strtolower', $field_array));

                                    $education_com_ref = $this->education_com_ref($result_education_insert);

                                    $result_education_update = $this->education_model->save(array('education_com_ref'=>$education_com_ref),array('id'=>$result_education_insert));

                                    $education_id = $result_education_insert;

                                    $insff_reason = "Others";

                                    $insff_date = date(DATE_ONLY);

                                    $insuff_remarks = "Component Pending From Candidate";

                                    $check = $this->education_model->select_insuff(array('education_id' => $education_id, 'status !=' => 3, 'insuff_clear_date is null' => null));

                                    if (empty($check)) {
                                        $result = $this->education_model->save_update_insuff(array('insuff_raised_date' => $insff_date, 'insuff_raise_remark' =>  $insuff_remarks, 'status' => STATUS_ACTIVE, 'insff_reason' => $insff_reason, 'education_id' => $education_id,"created_by" => 1, 'auto_stamp' => 1));
                                    }

                                }
                                if(in_array('refver', $component_id))
                                {
                                    $candidate_details['reference_component_check'] = 1; 
                                    
                                    $this->load->model('reference_verificatiion_model'); 

                                    $tat_day = $this->get_client_entity_package_tat_day(array('tbl_clients_id' => $frm_details['clientid'], 'entity' => $frm_details['entity'], 'package' => $frm_details['package']));

                                    $get_holiday1 = $this->get_holiday();

                                    $get_holiday = array_map('current', $get_holiday1);

                                    $closed_date = getWorkingDays(date(DATE_ONLY), $get_holiday, $tat_day[0]['tat_refver']);

                                    $has_case_id = $this->reference_verificatiion_model->get_reporting_manager_id_client($frm_details['clientid']);

                                    $mode_of_verification = $this->reference_verificatiion_model->get_mode_of_verification(array('entity' => $frm_details['entity'], 'package' => $frm_details['package'], 'tbl_clients_id' => $frm_details['clientid']));

                                    if(!empty($mode_of_verification)){
                                        $mode_of_verification_veri = json_decode($mode_of_verification[0]['mode_of_verification']);
                                        $mode_of_verification_value = $mode_of_verification_veri->refver;
                                    }
                                    else{
                                        $mode_of_verification_value =  '';
                                    }

                                    $field_array = array('clientid' => $frm_details['clientid'],
                                        'candsid' => $result_main_table,
                                        'reference_com_ref' => "",
                                        'iniated_date' => date(DATE_ONLY),
                                        'mode_of_veri' => $mode_of_verification_value,
                                        'created_on' => date(DB_DATE_FORMAT),
                                        'modified_on' => date(DB_DATE_FORMAT),
                                        'is_bulk_uploaded' => 0,
                                        'has_case_id' => $has_case_id[0]['clientmgr'],
                                        "due_date" => $closed_date,
                                        "tat_status" => 'IN TAT',
                                        "created_by" => 1,
                                        'fill_by' => 2,
                                    );
                   
                                    $result_reference_insert = $this->reference_verificatiion_model->save(array_map('strtolower', $field_array));
                                    $reference_com_ref = $this->reference_com_ref($result_reference_insert);
                                 
                                    $result_reference_update = $this->reference_verificatiion_model->save(array('reference_com_ref'=>$reference_com_ref),array('id'=>$result_education_insert));

                                    $reference_id = $result_reference_insert;

                                    $insff_reason = "Others";

                                    $insff_date = date(DATE_ONLY);

                                    $insuff_remarks = "Component Pending From Candidate";

                                    $check = $this->reference_verificatiion_model->select_insuff(array('reference_id' => $reference_id, 'status !=' => 3, 'insuff_clear_date is null' => null));

                                    if (empty($check)) {
                                        $result = $this->reference_verificatiion_model->save_update_insuff(array('insuff_raised_date' => $insff_date, 'insuff_raise_remark' =>  $insuff_remarks, 'status' => STATUS_ACTIVE, 'insff_reason' => $insff_reason, 'reference_id' => $reference_id,"created_by" => 1,'auto_stamp' => 1));
                                    }


                                }
                                if(in_array('courtver', $component_id))
                                {
                                    $candidate_details['court_component_check'] = 1;

                                    $this->load->model('Court_verificatiion_model');

                                    $tat_day = $this->get_client_entity_package_tat_day(array('tbl_clients_id' => $frm_details['clientid'], 'entity' => $frm_details['entity'], 'package' => $frm_details['package']));

                                    $get_holiday1 = $this->get_holiday();

                                    $get_holiday = array_map('current', $get_holiday1);

                                    $closed_date = getWorkingDays(date(DATE_ONLY), $get_holiday, $tat_day[0]['tat_courtver']);

                                    $has_case_id = $this->Court_verificatiion_model->get_reporting_manager_id($frm_details['clientid']);

                                    $mode_of_verification = $this->Court_verificatiion_model->get_mode_of_verification(array('entity' => $frm_details['entity'], 'package' => $frm_details['package'], 'tbl_clients_id' => $frm_details['clientid']));

                                    if(!empty($mode_of_verification)){
                                        $mode_of_verification_veri = json_decode($mode_of_verification[0]['mode_of_verification']);
                                        $mode_of_verification_value = $mode_of_verification_veri->courtver;
                                    }
                                    else{
                                        $mode_of_verification_value =  '';
                                    }

                                    $field_array = array('clientid' => $frm_details['clientid'],
                                            'candsid' => $result_main_table,
                                            'court_com_ref' => '',
                                            'mode_of_veri' => $mode_of_verification_value,
                                            'created_on' => date(DB_DATE_FORMAT),
                                            'modified_on' => date(DB_DATE_FORMAT),
                                            'is_bulk_uploaded' => 0,
                                            'iniated_date' => date(DATE_ONLY),
                                            "due_date" => $closed_date,
                                            'has_case_id' => $has_case_id[0]['clientmgr'],
                                            "tat_status" => 'IN TAT',
                                            "created_by" => 1,
                                            'fill_by' => 2,
                                        );

                                    $result_court_insert  = $this->Court_verificatiion_model->save(array_map('strtolower', $field_array));

                                    $court_com_ref = $this->court_com_ref($result_court_insert);
                                 
                                    $result_court_update = $this->Court_verificatiion_model->save(array('court_com_ref'=>$court_com_ref),array('id'=>$result_court_insert));

                                    $court_id = $result_court_insert;

                                    $insff_reason = "Others";

                                    $insff_date = date(DATE_ONLY);

                                    $insuff_remarks = "Component Pending From Candidate";

                                    $check = $this->Court_verificatiion_model->select_insuff(array('courtver_id' => $court_id, 'status !=' => 3, 'insuff_clear_date is null' => null));

                                    if (empty($check)) {
                                        $result = $this->Court_verificatiion_model->save_update_insuff(array('insuff_raised_date' => $insff_date, 'insuff_raise_remark' => $insuff_remarks, 'status' => STATUS_ACTIVE, 'insff_reason' => $insff_reason, 'courtver_id' => $court_id, "created_by" => 1, 'auto_stamp' => 1));
                                    }
                                      
                                }
                                if(in_array('globdbver', $component_id))
                                {
                                    $candidate_details['global_component_check'] = 1; 

                                    $this->load->model('Global_database_model');

                                    $tat_day = $this->get_client_entity_package_tat_day(array('tbl_clients_id' => $frm_details['clientid'], 'entity' => $frm_details['entity'], 'package' => $frm_details['package']));

                                    $get_holiday1 = $this->get_holiday();

                                    $get_holiday = array_map('current', $get_holiday1);

                                    $closed_date = getWorkingDays(date(DATE_ONLY), $get_holiday, $tat_day[0]['tat_globdbver']);

                                    $has_case_id = $this->Global_database_model->get_reporting_manager_id($frm_details['clientid']);

                                    $mode_of_verification = $this->Global_database_model->get_mode_of_verification(array('entity' => $frm_details['entity'], 'package' => $frm_details['package'], 'tbl_clients_id' => $frm_details['clientid']));

                                    if(!empty($mode_of_verification)){
                                        $mode_of_verification_veri = json_decode($mode_of_verification[0]['mode_of_verification']);
                                        $mode_of_verification_value = $mode_of_verification_veri->globdbver;
                                    }
                                    else{
                                        $mode_of_verification_value =  '';
                                    }

                                    $field_array = array('clientid' => $frm_details['clientid'],
                                            'candsid' => $result_main_table,
                                            'global_com_ref' => '',
                                            'mode_of_veri' => $mode_of_verification_value,
                                            'created_on' => date(DB_DATE_FORMAT),
                                            'modified_on' => date(DB_DATE_FORMAT),
                                            'is_bulk_uploaded' => 0,
                                            'iniated_date' => date(DATE_ONLY),
                                            "due_date" => $closed_date,
                                            'has_case_id' => $has_case_id[0]['clientmgr'],
                                            "tat_status" => 'IN TAT',
                                            "created_by" => 1,
                                            'fill_by' => 2,
                                        );

                                    $result_global_insert  = $this->Global_database_model->save(array_map('strtolower', $field_array));

                                    $global_com_ref = $this->global_com_ref($result_global_insert);

                                 
                                    $result_global_update = $this->Global_database_model->save(array('global_com_ref'=>$global_com_ref),array('id'=>$result_global_insert));

                                    $global_id = $result_global_insert;

                                    $insff_reason = "Others";

                                    $insff_date = date(DATE_ONLY);

                                    $insuff_remarks = "Component Pending From Candidate";

                                    $check = $this->Global_database_model->select_insuff(array('glodbver_id' => $global_id, 'status !=' => 3, 'insuff_clear_date is null' => null));

                                    if (empty($check)) {
                                        $result = $this->Global_database_model->save_update_insuff(array('insuff_raised_date' => $insff_date, 'insuff_raise_remark' => $insuff_remarks, 'status' => STATUS_ACTIVE, 'insff_reason' => $insff_reason, 'glodbver_id' => $global_id, "created_by" => 1, 'auto_stamp' => 1));
                                    }
                                }
                                if(in_array('identity', $component_id))
                                {
                                    $candidate_details['identity_component_check'] = 1; 

                                    $this->load->model('identity_model');

                                    $tat_day = $this->get_client_entity_package_tat_day(array('tbl_clients_id' => $frm_details['clientid'], 'entity' => $frm_details['entity'], 'package' => $frm_details['package']));

                                    $get_holiday1 = $this->get_holiday();

                                    $get_holiday = array_map('current', $get_holiday1);

                                    $closed_date = getWorkingDays(date(DATE_ONLY), $get_holiday, $tat_day[0]['tat_refver']);

                                    $has_case_id = $this->identity_model->get_reporting_manager_id($frm_details['clientid']);

                                    $mode_of_verification = $this->identity_model->get_mode_of_verification(array('entity' => $frm_details['entity'], 'package' => $frm_details['package'], 'tbl_clients_id' => $frm_details['clientid']));

                                    if(!empty($mode_of_verification)){
                                        $mode_of_verification_veri = json_decode($mode_of_verification[0]['mode_of_verification']);
                                        $mode_of_verification_value = $mode_of_verification_veri->identity;
                                    }
                                    else{
                                        $mode_of_verification_value =  '';
                                    }

                                   $field_array = array('clientid' => $frm_details['clientid'],
                                            'candsid' => $result_main_table,
                                            'identity_com_ref' => '',
                                            'mode_of_veri' => $mode_of_verification_value,
                                            'created_on' => date(DB_DATE_FORMAT),
                                            'modified_on' => date(DB_DATE_FORMAT),
                                            'is_bulk_uploaded' => 0,
                                            'iniated_date' => date(DATE_ONLY),
                                            "due_date" => $closed_date,
                                            'has_case_id' => $has_case_id[0]['clientmgr'],
                                            "tat_status" => 'IN TAT',
                                            "created_by" => 1,
                                            'fill_by' => 2,
                                        );

                                    $result_identity_insert  = $this->identity_model->save(array_map('strtolower', $field_array));

                                    $identity_com_ref = $this->identity_com_ref($result_identity_insert);
                                 
                                   
                                    $result_identity_update = $this->identity_model->save(array('identity_com_ref'=>$identity_com_ref),array('id'=>$result_identity_insert));

                                    $identity_id = $result_identity_insert;

                                    $insff_reason = "Others";

                                    $insff_date = date(DATE_ONLY);

                                    $insuff_remarks = "Component Pending From Candidate";

                                    $check = $this->identity_model->select_insuff(array('identity_id' => $identity_id, 'status !=' => 3, 'insuff_clear_date is null' => null));

                                    if (empty($check)) {
                                        $result = $this->identity_model->save_update_insuff(array('insuff_raised_date' => $insff_date, 'insuff_raise_remark' => $insuff_remarks, 'status' => STATUS_ACTIVE, 'insff_reason' => $insff_reason, 'identity_id' => $identity_id,"created_by" => 1, 'auto_stamp' => 1));
                                    }
                                     

                                }
                                if(in_array('crimver', $component_id))
                                {
                                    $candidate_details['pcc_component_check'] = 1; 

                                    $this->load->model('pcc_verificatiion_model');

                                    $tat_day = $this->get_client_entity_package_tat_day(array('tbl_clients_id' => $frm_details['clientid'], 'entity' => $frm_details['entity'], 'package' => $frm_details['package']));

                                    $get_holiday1 = $this->get_holiday();

                                    $get_holiday = array_map('current', $get_holiday1);

                                    $closed_date = getWorkingDays(date(DATE_ONLY), $get_holiday, $tat_day[0]['tat_crimver']);

                                    $has_case_id = $this->pcc_verificatiion_model->get_reporting_manager_id($frm_details['clientid']);

                                    $mode_of_verification = $this->pcc_verificatiion_model->get_mode_of_verification(array('entity' => $frm_details['entity'], 'package' => $frm_details['package'], 'tbl_clients_id' => $frm_details['clientid']));

                                    if(!empty($mode_of_verification)){
                                        $mode_of_verification_veri = json_decode($mode_of_verification[0]['mode_of_verification']);
                                        $mode_of_verification_value = $mode_of_verification_veri->crimver;
                                    }
                                    else{
                                        $mode_of_verification_value =  '';
                                    }

                                    $field_array = array('clientid' => $frm_details['clientid'],
                                            'candsid' => $result_main_table,
                                            'pcc_com_ref' => '',
                                            'mode_of_veri' => $mode_of_verification_value,
                                            'created_on' => date(DB_DATE_FORMAT),
                                            'modified_on' => date(DB_DATE_FORMAT),
                                            'is_bulk_uploaded' => 0,
                                            'iniated_date' => date(DATE_ONLY),
                                            "due_date" => $closed_date,
                                            'has_case_id' => $has_case_id[0]['clientmgr'],
                                            "tat_status" => 'IN TAT',
                                            "created_by" => 1,
                                            'fill_by' => 2,
                                        );

                                    $result_pcc_insert  = $this->pcc_verificatiion_model->save(array_map('strtolower', $field_array));

                                    
                                    $pcc_com_ref = $this->pcc_com_ref($result_pcc_insert);

                                    $result_pcc_update = $this->pcc_verificatiion_model->save(array('pcc_com_ref'=>$pcc_com_ref),array('id'=>$result_pcc_insert));

                                    $pcc_id = $result_pcc_insert;

                                    $insff_reason = "Others";

                                    $insff_date = date(DATE_ONLY);

                                    $insuff_remarks = "Component Pending From Candidate";

                                    $check = $this->pcc_verificatiion_model->select_insuff(array('pcc_id' => $pcc_id, 'status !=' => 3, 'insuff_clear_date is null' => null));

                                    if (empty($check)) {
                                        $result = $this->pcc_verificatiion_model->save_update_insuff(array('insuff_raised_date' => $insff_date, 'insuff_raise_remark' => $insuff_remarks, 'status' => STATUS_ACTIVE, 'insff_reason' => $insff_reason, 'pcc_id' => $pcc_id, "created_by" => 1, 'auto_stamp' => 1));
                                    }
                                }
                                if(in_array('cbrver', $component_id))
                                {
                                    $candidate_details['credit_report_component_check'] = 1; 

                                    $this->load->model('Credit_report_model');

                                    $tat_day = $this->get_client_entity_package_tat_day(array('tbl_clients_id' => $frm_details['clientid'], 'entity' => $frm_details['entity'], 'package' => $frm_details['package']));

                                    $get_holiday1 = $this->get_holiday();

                                    $get_holiday = array_map('current', $get_holiday1);

                                    $closed_date = getWorkingDays(date(DATE_ONLY), $get_holiday, $tat_day[0]['tat_cbrver']);

                                    $has_case_id = $this->Credit_report_model->get_reporting_manager_id($frm_details['clientid']);

                                    $mode_of_verification = $this->Credit_report_model->get_mode_of_verification(array('entity' => $frm_details['entity'], 'package' => $frm_details['package'], 'tbl_clients_id' => $frm_details['clientid']));

                                    if(!empty($mode_of_verification)){
                                        $mode_of_verification_veri = json_decode($mode_of_verification[0]['mode_of_verification']);
                                        $mode_of_verification_value = $mode_of_verification_veri->cbrver;
                                    }
                                    else{
                                        $mode_of_verification_value =  '';
                                    }

                                    $field_array = array('clientid' => $frm_details['clientid'],
                                        'candsid' => $result_main_table,
                                        'credit_report_com_ref' => '',
                                        'mode_of_veri' => $mode_of_verification_value,
                                        'created_on' => date(DB_DATE_FORMAT),
                                        'modified_on' => date(DB_DATE_FORMAT),
                                        'is_bulk_uploaded' => 0,
                                        'iniated_date' => date(DATE_ONLY),
                                        "credit_report_re_open_date" => '',
                                        "due_date" => $closed_date,
                                        'has_case_id' => $has_case_id[0]['clientmgr'],
                                        "tat_status" => 'IN TAT',
                                        "created_by" => 1,
                                        'fill_by' => 2,
                                    );
                  
                                    $result_credit_insert = $this->Credit_report_model->save(array_map('strtolower', $field_array));

                                    $credit_report_com_ref = $this->credit_report_com_ref($result_credit_insert);

                                    $result_credit_update = $this->Credit_report_model->save(array('credit_report_com_ref'=>$credit_report_com_ref),array('id'=>$result_credit_insert));

                                    $credit_report_id = $result_credit_insert;

                                    $insff_reason = "Others";

                                    $insff_date = date(DATE_ONLY);

                                    $insuff_remarks = "Component Pending From Candidate";

                                    $check = $this->Credit_report_model->select_insuff(array('credit_report_id' => $credit_report_id, 'status !=' => 3, 'insuff_clear_date is null' => null));

                                    if (empty($check)) {

                                        $result = $this->Credit_report_model->save_update_insuff(array('insuff_raised_date' =>$insff_date,'insuff_raise_remark' => $insuff_remarks, 'status' => STATUS_ACTIVE, 'insff_reason' => $insff_reason,'credit_report_id' => $credit_report_id,"created_by" => 1, 'auto_stamp' => 1));
                                    }


                                }
                               
                                if(in_array('narcver', $component_id))
                                {

                                    $candidate_details['drugs_component_check'] = 1; 

                                    $this->load->model('Drug_verificatiion_model');

                                    $tat_day = $this->get_client_entity_package_tat_day(array('tbl_clients_id' => $frm_details['clientid'], 'entity' => $frm_details['entity'], 'package' => $frm_details['package']));

                                    $get_holiday1 = $this->get_holiday();

                                    $get_holiday = array_map('current', $get_holiday1);

                                    $closed_date = getWorkingDays(date(DATE_ONLY), $get_holiday, $tat_day[0]['tat_narcver']);

                                    $has_case_id = $this->Drug_verificatiion_model->get_reporting_manager_id($frm_details['clientid']);

                                    $mode_of_verification = $this->Drug_verificatiion_model->get_mode_of_verification(array('entity' => $frm_details['entity'], 'package' => $frm_details['package'], 'tbl_clients_id' => $frm_details['clientid']));

                                    if(!empty($mode_of_verification)){
                                        $mode_of_verification_veri = json_decode($mode_of_verification[0]['mode_of_verification']);
                                        $mode_of_verification_value = $mode_of_verification_veri->narcver;
                                    }
                                    else{
                                        $mode_of_verification_value =  '';
                                    }

                                    $field_array = array('clientid' => $frm_details['clientid'],
                                            'candsid' => $result_main_table,
                                            'drug_com_ref' => '',
                                            'mode_of_veri' => $mode_of_verification_value,
                                            'created_on' => date(DB_DATE_FORMAT),
                                            'modified_on' => date(DB_DATE_FORMAT),
                                            'is_bulk_uploaded' => 0,
                                            'iniated_date' => date(DATE_ONLY),
                                            "due_date" => $closed_date,
                                            'has_case_id' => $has_case_id[0]['clientmgr'],
                                            "tat_status" => 'IN TAT',
                                            "created_by" => 1,
                                            'fill_by' => 2,
                                        );

                                    $result_drugs_insert  = $this->Drug_verificatiion_model->save(array_map('strtolower', $field_array));

                                    
                                    $drugs_com_ref = $this->drug_com_ref($result_drugs_insert);

                                    $result_drugs_update = $this->Drug_verificatiion_model->save(array('drug_com_ref'=>$drugs_com_ref),array('id'=>$result_drugs_insert));

                                    $drugs_id = $result_drugs_insert;

                                    $insff_reason = "Others";

                                    $insff_date = date(DATE_ONLY);

                                    $insuff_remarks = "Component Pending From Candidate";

                                    $check = $this->Drug_verificatiion_model->select_insuff(array('drug_narcotis_id' => $drugs_id, 'status !=' => 3, 'insuff_clear_date is null' => null));

                                    if (empty($check)) {
                                        $result = $this->Drug_verificatiion_model->save_update_insuff(array('insuff_raised_date' => $insff_date, 'insuff_raise_remark' => $insuff_remarks, 'status' => STATUS_ACTIVE, 'insff_reason' => $insff_reason, 'drug_narcotis_id' => $drugs_id, "created_by" => 1, 'auto_stamp' => 1));
                                    }
                                }
                                
                                $candidate_details['check_mail_send'] = 1;

                                auto_update_client_candidate_status($result_main_table); 

                                $result_cmp_update = $this->candidates_model->save($candidate_details, array('cmp_ref_no' => $cmp_ref_no)); 

                                $this->client_send_mail_to_candidate_update($cmp_ref_no);

                            }
                        }    

                        $file_upload_path = SITE_BASE_PATH . CANDIDATES . $frm_details['clientid'];

                        if (!folder_exist($file_upload_path)) {
                            mkdir($file_upload_path, 0777);
                        }
                       
                        $config_array = array('file_upload_path' => $file_upload_path, 'file_permission' => 'jpeg|jpg|png|pdf', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 20000, 'file_id' => $result_main_table, 'component_name' => 'candidate_id');

                        if ($_FILES['attchments']['name'][0] != '') {
                            $config_array['files_count'] = count($_FILES['attchments']['name']);
                            $config_array['file_data'] = $_FILES['attchments'];
                            $config_array['type'] = 0;
                            $retunr_de = $this->file_upload_multiple($config_array);
                            if (!empty($retunr_de)) {
                                $this->common_model->common_insert_batch('candidate_files', $retunr_de['success']);
                            }
                        }

                        $json_array['status'] = SUCCESS_CODE;

                        $json_array['message'] = 'Candidate Created Successfully';

                        $json_array['redirect'] = CLIENT_SITE_URL . 'candidates/view_details/' . encrypt($id);
                   
                    }
                    else {
                        $json_array['status'] = ERROR_CODE;

                        $json_array['message'] = 'Something went wrong, please try again';
                    }
                } catch (Exception $e) {
                    log_message('error', 'Client Side Controller');
                    log_message('error', 'Candidate::add_candidate');
                    log_message('error', $e->getMessage());
                }
            }
            echo_json($json_array);
        }
    }

    protected function add_com_ref($insert_id)
    {
        $this->load->model('addressver_model');

        $name = COMPONENT_REF_NO['ADDRESS'];

        $addressnumber = $name . $insert_id;

        $field_array = array('add_com_ref' => $addressnumber);

        $update_auto_increament_id = $this->addressver_model->update_auto_increament_value($field_array, array('id' => $insert_id));

        return $addressnumber;
    }

    protected function emp_com_ref($insert_id)
    {
        $this->load->model('employment_model');

        $name = COMPONENT_REF_NO['EMPLOYMENT'];

        $employmentnumber = $name . $insert_id;

        $field_array = array('emp_com_ref' => $employmentnumber);

        $update_auto_increament_id = $this->employment_model->update_auto_increament_value($field_array, array('id' => $insert_id));

        return $employmentnumber;
    }

    protected function education_com_ref($insert_id)
    {
        $this->load->model('education_model');

        $name = COMPONENT_REF_NO['EDUCATION'];

        $educationnumber = $name . $insert_id;

        $field_array = array('education_com_ref' => $educationnumber);

        $update_auto_increament_id = $this->education_model->update_auto_increament_value($field_array, array('id' => $insert_id));

        return $educationnumber;
    }


    protected function reference_com_ref($insert_id)
    {
        $this->load->model('reference_verificatiion_model');

        $name = COMPONENT_REF_NO['REFERENCES'];

        $referencenumber = $name . $insert_id;

        $field_array = array('reference_com_ref' => $referencenumber);

        $update_auto_increament_id = $this->reference_verificatiion_model->update_auto_increament_value($field_array, array('id' => $insert_id));

        return $referencenumber;
    }

    protected function identity_com_ref($insert_id)
    {
        $this->load->model('identity_model');

        $name = COMPONENT_REF_NO['IDENTITY'];

        $identitynumber = $name . $insert_id;

        $field_array = array('identity_com_ref' => $identitynumber);

        $update_auto_increament_id = $this->identity_model->update_auto_increament_value($field_array, array('id' => $insert_id));

        return $identitynumber;
    }

    protected function credit_report_com_ref($insert_id)
    {
        $this->load->model('Credit_report_model');


        $name = COMPONENT_REF_NO['CREDIT_REPORT'];

        $credit_report_number = $name . $insert_id;

        $field_array = array('credit_report_com_ref' => $credit_report_number);

        $update_auto_increament_id = $this->Credit_report_model->update_auto_increament_value($field_array, array('id' => $insert_id));

        return $credit_report_number;
    }

    protected function court_com_ref($insert_id)
    {
        $this->load->model('court_verificatiion_model');

        log_message('error', 'Court Verification Ref No');
        try {

            $name = COMPONENT_REF_NO['COURT'];

            $courtnumber = $name . $insert_id;

            $field_array = array('court_com_ref' => $courtnumber);

            $update_auto_increament_id = $this->court_verificatiion_model->update_auto_increament_value($field_array, array('id' => $insert_id));

            return $courtnumber;

        } catch (Exception $e) {
            log_message('error', 'Error on Court_verificatiion::court_com_ref');
            log_message('error', $e->getMessage());
        }
    }

    protected function global_com_ref($insert_id)
    {
        $this->load->model('global_database_model');

        log_message('error', 'Global Verification Ref No');
        try {
            $name = COMPONENT_REF_NO['GLOBAL'];

            $globalnumber = $name . $insert_id;

            $field_array = array('global_com_ref' => $globalnumber);

            $update_auto_increament_id = $this->global_database_model->update_auto_increament_value($field_array, array('id' => $insert_id));

            return $globalnumber;
        } catch (Exception $e) {
            log_message('error', 'Error on Global Database::global_com_ref');
            log_message('error', $e->getMessage());
        }
    }

    protected function pcc_com_ref($insert_id)
    {
        $this->load->model('pcc_verificatiion_model');

        log_message('error', 'PCC Ref No');
        try {

            $name = COMPONENT_REF_NO['PCC'];

            $pccnumber = $name . $insert_id;

            $field_array = array('pcc_com_ref' => $pccnumber);

            $update_auto_increament_id = $this->pcc_verificatiion_model->update_auto_increament_value($field_array, array('id' => $insert_id));

            return $pccnumber;

        } catch (Exception $e) {
            log_message('error', 'Error on PCC::pcc_com_ref');
            log_message('error', $e->getMessage());
        }
    }

     

    protected function drug_com_ref($insert_id)
    {
        $this->load->model('drug_verificatiion_model');

        log_message('error', 'Drugs Verification Ref No');
        try {

            $name = COMPONENT_REF_NO['DRUGS'];

            $drugsnumber = $name . $insert_id;

            $field_array = array('drug_com_ref' => $drugsnumber);

            $update_auto_increament_id = $this->drug_verificatiion_model->update_auto_increament_value($field_array, array('id' => $insert_id));

            return $drugsnumber;

        } catch (Exception $e) {
            log_message('error', 'Error on Drugs::drug_com_ref');
            log_message('error', $e->getMessage());
        }
    }
 

    protected function generating_unique_number($cmp_ref_no)
    {
        $private_key = $this->rand_string_value(8);
        $public_key = $this->rand_string_value(8); 
        $where_array = array('public_key' => $public_key,'private_key' => $private_key,'cmp_ref_no' => $cmp_ref_no);
        $check_exists_or_not = $this->candidates_model->select(TRUE,array('id'),$where_array);

        if(!empty($check_exists_or_not)) {
            $this->generating_unique_number($cmp_ref_no);
        }
        return $where_array;
    }
 

    public function add()
    {
        $data['header_title'] = "Create Candidates";

        $data['status'] = status_frm_db1();

        $data['states'] = $this->get_states();

        $data['clients'] = $this->get_clients(array('status' => STATUS_ACTIVE));

        echo $this->load->view('client/candidates_add', $data, true);

    }

    public function add_initiation()
    {
        $data['header_title'] = "Create Candidates";

      
        $data['clients'] = $this->get_clients(array('status' => STATUS_ACTIVE));

        echo $this->load->view('client/initiation_add', $data, true);

    }

    public function add_pre_post()
    {
        $data['header_title'] = "Create Candidates";

      
        $data['clients'] = $this->get_clients(array('status' => STATUS_ACTIVE));

        echo $this->load->view('client/pre_post_add', $data, true);

    }

    public function add_post($id = '')
    {

        $data['header_title'] = "Create Candidates";

        $data['pre_post_details'] = $this->candidates_model->select_pre_post_details(array('id'=>$id));

        if(isset($data['pre_post_details'][0]['task_manager_id']))
        {
            $data['attachment'] = $this->candidates_model->select_attachment_details(array('client_new_case_id'=>$data['pre_post_details'][0]['task_manager_id']));
        }
        else
        {
            $data['attachment'] = '';
        }

        $data['clients'] = $this->get_clients(array('status' => STATUS_ACTIVE));

        echo $this->load->view('client/pre_post_edit', $data, true);

    }

    public function data_table_cands_view()
    {
        if ($this->input->is_ajax_request()) {
            $params = $data_arry = $columns = array();

            $params = $_REQUEST;

            $columns = array('id', 'CandidateName', "clientname", "caserecddate", 'ClientRefNumber', 'cmp_ref_no', 'overallstatus', 'remarks', 'view', 'encry_id');
            $cands_results = $this->candidates_model->get_all_cand_with_search($this->user_package, $this->user_role, $params, $columns);

            $totalRecords = count($this->candidates_model->get_all_cand_with_search_count_value($this->user_package, $this->user_role, $params, $columns));
            $x = 0;

            foreach ($cands_results as $key => $cands_result) {

                $report_details = $this->candidates_model->select_cadidate_report_join(array('activity_log.candsid' => $cands_result['id']));

                $data_arry[$x]['id'] = $x + 1;
                $data_arry[$x]['CandidateName'] = ucwords(strtolower($cands_result['CandidateName']));
                $data_arry[$x]['entity'] = ucwords(strtolower($cands_result['entity_name']));
                $data_arry[$x]['package'] = ucwords(strtolower($cands_result['package_name']));
                $data_arry[$x]['ClientRefNumber'] = $cands_result['ClientRefNumber'];
                $data_arry[$x]['cmp_ref_no'] = $cands_result['cmp_ref_no'];
                $data_arry[$x]['overallstatus'] = $cands_result['status_value'];
                $data_arry[$x]['overallclosuredate'] = convert_db_to_display_date($cands_result['overallclosuredate']);

                if ($report_details['component_type'] == "Final Report") {

                    $data_arry[$x]['report'] = "<a target='_blank' data-overallstatus='" . $cands_result['status_value'] . "' href='" . CLIENT_SITE_URL . "candidates/report/" . encrypt($cands_result['id']) . "/Final' title='Report' style='margin-left: 45px;'>View</a>";
                } elseif ($report_details['component_type'] == "Interim Report") {
                    $data_arry[$x]['report'] = "<a target='_blank' data-overallstatus='" . $cands_result['status_value'] . "' href='" . CLIENT_SITE_URL . "candidates/report/" . encrypt($cands_result['id']) . "/Interiam' title='Report' style='margin-left: 45px;'></a>";
                } elseif ($report_details['component_type'] == "Candidate Import Report") {
                    $data_arry[$x]['report'] = '<a target="_blank" href="' . SITE_URL . UPLOAD_FOLDER . 'candidate_report_file/' . $report_details['remarks'] . '"  style="margin-left: 45px;">View</a>';
                } else {
                    $data_arry[$x]['report'] = "<a target='_blank' data-overallstatus='" . $cands_result['status_value'] . "' href='" . CLIENT_SITE_URL . "candidates/report/" . encrypt($cands_result['id']) . "/Final' title='Report' style='margin-left: 45px;'>View</a>";
                }


                $data_arry[$x]['clientname'] = ucwords(strtolower($cands_result['clientname']));
                $data_arry[$x]['caserecddate'] = convert_db_to_display_date($cands_result['caserecddate']);
                $data_arry[$x]['pending_check'] = 'NA';
                $data_arry[$x]['insufficiency_check'] = 'NA';
                $data_arry[$x]['closed_check'] = 'NA';

                // print_r($cands_result['id']);
                $array_wip = array();
                $array_insufficiency = array();
                $array_closed = array();

                $result_address_main = $this->candidates_model->get_addres_ver_status_list(array('candidates_info.id' => $cands_result['id']));
              
                if (!empty($result_address_main)) {
                    $address_count = 1;
                    foreach ($result_address_main as $result_address) {

                        $address_component_status = ($result_address['var_filter_status'] != "") ? $result_address['var_filter_status'] : 'WIP';

                        if (($address_component_status == "WIP") || ($address_component_status == "wip")) {
                            array_push($array_wip, ' <i class="fas fa-home" style="color:#0000FF" title="Address '.$address_count.'"></i> ');
                        }
                        if (($address_component_status == "Insufficiency") || ($address_component_status == "insufficiency")) {
                            array_push($array_insufficiency, ' <i class="fas fa-home" style="color:#0000FF" title="Address '.$address_count.'"></i> ');
                        }
                        if (($address_component_status == "Closed") || ($address_component_status == "closed")) {
                            array_push($array_closed, ' <i class="fas fa-home" style="color:#0000FF" title="Address '.$address_count.'"></i> ');
                        }

                        $address_count++;

                    }
                }

                $result_education_main = $this->candidates_model->get_education_ver_status_list(array('candidates_info.id' => $cands_result['id']));

                if (!empty($result_education_main)) {
                    $education_count = 1;
                    foreach ($result_education_main as $result_education) {

                        $education_component_status = ($result_education['var_filter_status'] != "") ? $result_education['var_filter_status'] : 'WIP';

                        if (($education_component_status == "WIP") || ($education_component_status == "wip")) {
                            array_push($array_wip, ' <i class="fas fa-graduation-cap" style="color:#0000FF" title="Education '.$education_count.'"></i> ');
                        }
                        if (($education_component_status == "Insufficiency") || ($education_component_status == "insufficiency")) {
                            array_push($array_insufficiency, ' <i class="fas fa-graduation-cap" style="color:#0000FF" title="Education '.$education_count.'"></i> ');
                        }
                        if (($education_component_status == "Closed") || ($education_component_status == "closed")) {
                            array_push($array_closed, ' <i class="fas fa-graduation-cap" style="color:#0000FF" title="Education '.$education_count.'"></i> ');
                        }

                        $education_count++;
                    }
                }

                $result_employment_main = $this->candidates_model->get_employment_ver_status_list(array('candidates_info.id' => $cands_result['id']));

                if (!empty($result_employment_main)) {
                    $employment_count = 1;

                    foreach ($result_employment_main as $result_employment) {

                        $employment_component_status = ($result_employment['var_filter_status'] != "") ? $result_employment['var_filter_status'] : 'WIP';

                        if (($employment_component_status == "WIP") || ($employment_component_status == "wip")) {
                            array_push($array_wip,' <i class="fas fa-laptop" style="color:#0000FF" title="Employment '.$employment_count.'"></i>  ');
                        }
                        if (($employment_component_status == "Insufficiency") || ($employment_component_status == "insufficiency")) {
                            array_push($array_insufficiency,' <i class="fas fa-laptop" style="color:#0000FF" title="Employment '.$employment_count.'"></i> ');
                        }
                        if (($employment_component_status == "Closed") || ($employment_component_status == "closed")) {
                            array_push($array_closed, ' <i class="fas fa-laptop" style="color:#0000FF" title="Employment '.$employment_count.'"></i> ');
                        }
                        $employment_count++;
                    }
                }

                $result_court_main = $this->candidates_model->get_court_ver_status_list(array('candidates_info.id' => $cands_result['id']));

                if (!empty($result_court_main)) {
                    $court_count = 1;

                    foreach ($result_court_main as $result_court) {

                        $court_component_status = ($result_court['var_filter_status'] != "") ? $result_court['var_filter_status'] : 'WIP';
                        if (($court_component_status == "WIP") || ($court_component_status == "wip")) {
                            array_push($array_wip, ' <i class="fas fa-university" style="color:#0000FF" title="Court '.$court_count.'"></i> ');
                        }
                        if (($court_component_status == "Insufficiency") || ($court_component_status == "insufficiency")) {
                            array_push($array_insufficiency, ' <i class="fas fa-university" style="color:#0000FF" title="Court '.$court_count.'"></i> ');
                        }
                        if (($court_component_status == "Closed") || ($court_component_status == "closed")) {
                            array_push($array_closed, ' <i class="fas fa-university" style="color:#0000FF" title="Court '.$court_count.'"></i> ');
                        }

                        $court_count++;
                    }
                }

                $result_pcc_main = $this->candidates_model->get_pcc_ver_status_list(array('candidates_info.id' => $cands_result['id']));

                if (!empty($result_pcc_main)) {

                    $pcc_count = 1;

                    foreach ($result_pcc_main as $result_pcc) {

                        $pcc_component_status = ($result_pcc['var_filter_status'] != "") ? $result_pcc['var_filter_status'] : 'WIP';

                        if (($pcc_component_status == "WIP") || ($pcc_component_status == "wip")) {
                            array_push($array_wip, ' <i class="fas fa-shield-alt" style="color:#0000FF"  title="PCC '.$pcc_count.'"></i> ');
                        }
                        if (($pcc_component_status == "Insufficiency") || ($pcc_component_status == "insufficiency")) {
                            array_push($array_insufficiency, ' <i class="fas fa-shield-alt" style="color:#0000FF"  title="PCC '.$pcc_count.'"></i> ');
                        }
                        if (($pcc_component_status == "Closed") || ($pcc_component_status == "closed")) {
                            array_push($array_closed, ' <i class="fas fa-shield-alt" style="color:#0000FF"  title="PCC '.$pcc_count.'"></i> ');
                        }

                        $pcc_count++;
                    }
                }

                $result_reference_main = $this->candidates_model->get_refver_ver_status_list(array('reference.candsid' => $cands_result['id']));

                if (!empty($result_reference_main)) {

                    $reference_count = 1;

                    foreach ($result_reference_main as $result_reference) {

                        $reference_component_status = ($result_reference['var_filter_status'] != "") ? $result_reference['var_filter_status'] : 'WIP';

                        if (($reference_component_status == "WIP") || ($reference_component_status == "wip")) {
                            array_push($array_wip,' <i class="fas fa-link" style="color:#0000FF" title="Reference '.$reference_count.'"></i> ');
                        }
                        if (($reference_component_status == "Insufficiency") || ($reference_component_status == "insufficiency")) {
                            array_push($array_insufficiency, ' <i class="fas fa-link" style="color:#0000FF" title="Reference '.$reference_count.'"></i> ');
                        }
                        if (($reference_component_status == "Closed") || ($reference_component_status == "closed")) {
                            array_push($array_closed, ' <i class="fas fa-link" style="color:#0000FF" title="Reference '.$reference_count.'"></i> ');
                        }

                        $reference_count++;
                    }

                }

                $result_global_main = $this->candidates_model->get_globdbver_ver_status_list(array('candidates_info.id' => $cands_result['id']));
                if (!empty($result_global_main)) {

                    $global_count = 1;

                    foreach ($result_global_main as $result_global) {

                        $globaldb_component_status = ($result_global['var_filter_status'] != "") ? $result_global['var_filter_status'] : 'WIP';

                        if (($globaldb_component_status == "WIP") || ($globaldb_component_status == "wip")) {
                            array_push($array_wip,' <i class="fas fa-globe" style="color:#0000FF" title="Global '.$global_count.'"></i> ');
                        }
                        if (($globaldb_component_status == "Insufficiency") || ($globaldb_component_status == "insufficiency")) {
                            array_push($array_insufficiency, ' <i class="fas fa-globe" style="color:#0000FF" title="Global '.$global_count.'"></i> ');
                        }
                        if (($globaldb_component_status == "Closed") || ($globaldb_component_status == "closed")) {
                            array_push($array_closed, ' <i class="fas fa-globe" style="color:#0000FF" title="Global '.$global_count.'"></i> ');
                        }

                        $global_count++;
                    }
                }

                $result_identity_main = $this->candidates_model->get_identity_ver_status_list(array('candidates_info.id' => $cands_result['id']));

                if (!empty($result_identity_main)) {

                    $identity_count = 1;

                    foreach ($result_identity_main as $result_identity) {

                        $identity_component_status = ($result_identity['var_filter_status'] != "") ? $result_identity['var_filter_status'] : 'WIP';

                        if (($identity_component_status == "WIP") || ($identity_component_status == "wip")) {
                            array_push($array_wip, ' <i class="far fa-id-card" style="color:#0000FF" title="Identity '.$identity_count.'"></i> ');
                        }
                        if (($identity_component_status == "Insufficiency") || ($identity_component_status == "insufficiency")) {
                            array_push($array_insufficiency, ' <i class="far fa-id-card" style="color:#0000FF" title="Identity '.$identity_count.'"></i> ');
                        }
                        if (($identity_component_status == "Closed") || ($identity_component_status == "closed")) {
                            array_push($array_closed, ' <i class="far fa-id-card" style="color:#0000FF" title="Identity '.$identity_count.'"></i> ');
                        }

                        $identity_count++;
                    }
                }

                $result_credit_report_main = $this->candidates_model->get_credit_reports_ver_status_list(array('candidates_info.id' => $cands_result['id']));
                if (!empty($result_credit_report_main)) {

                    $credit_report_count = 1;

                    foreach ($result_credit_report_main as $result_credit_report) {

                        $credit_report_component_status = ($result_credit_report['var_filter_status'] != "") ? $result_credit_report['var_filter_status'] : 'WIP';

                        if (($credit_report_component_status == "WIP") || ($credit_report_component_status == "wip")) {
                            array_push($array_wip,' <i class="fas fa-credit-card" style="color:#0000FF" title="Credit Report '. $credit_report_count.'"></i> ');
                        }
                        if (($credit_report_component_status == "Insufficiency") || ($credit_report_component_status == "insufficiency")) {
                            array_push($array_insufficiency, ' <i class="fas fa-credit-card" style="color:#0000FF" title="Credit Report '. $credit_report_count.'"></i> ');
                        }
                        if (($credit_report_component_status == "Closed") || ($credit_report_component_status == "closed")) {
                            array_push($array_closed, ' <i class="fas fa-credit-card" style="color:#0000FF" title="Credit Report '. $credit_report_count.'"></i> ');
                        }

                        $credit_report_count++;
                    }
                }

                $result_drugs_main = $this->candidates_model->get_narcver_ver_status_list(array('candidates_info.id' => $cands_result['id']));
                if (!empty($result_drugs_main)) {

                    $drugs_count = 1;

                    foreach ($result_drugs_main as $result_drugs) {

                        $drugs_component_status = ($result_drugs['var_filter_status'] != "") ? $result_drugs['var_filter_status'] : 'WIP';

                        if (($drugs_component_status == "WIP") || ($drugs_component_status == "wip")) {

                            array_push($array_wip,' <i class="fas fa-prescription-bottle-alt" style="color:#0000FF" title="Drugs '. $drugs_count.'"></i> ');
                        }
                        if (($drugs_component_status == "Insufficiency") || ($drugs_component_status == "insufficiency")) {
                            array_push($array_insufficiency,' <i class="fas fa-prescription-bottle-alt" style="color:#0000FF" title="Drugs '. $drugs_count.'"></i> ');
                        }
                        if (($drugs_component_status == "Closed") || ($drugs_component_status == "closed")) {
                            array_push($array_closed,' <i class="fas fa-prescription-bottle-alt" style="color:#0000FF" title="Drugs '. $drugs_count.'"></i> ');
                        }
                        $drugs_count++;
                    }
                }

                $data_arry[$x]['pending_check'] = $array_wip;
                $data_arry[$x]['insufficiency_check'] = $array_insufficiency;
                $data_arry[$x]['closed_check'] = $array_closed;

                $x++;
            }

            $json_data = array(
                "draw" => intval($params['draw']),
                "recordsTotal" => intval($totalRecords),
                "recordsFiltered" => intval($totalRecords),
                "data" => $data_arry,
            );

            $this->echo_json($json_data);
        }
    }

    public function data_table_cands_view_add_candidate()
    {

        if ($this->input->is_ajax_request()) {
            $params = $data_arry = $columns = array();

            $params = $_REQUEST;

            $columns = array('id', 'CandidateName', "clientname", "caserecddate", 'ClientRefNumber', 'cmp_ref_no', 'overallstatus', 'remarks', 'view', 'encry_id');

            $cands_results = $this->candidates_model->get_all_cand_with_search_add_candidate($this->user_package, $this->user_role, $params, $columns);

            $totalRecords = count($this->candidates_model->get_all_cand_with_search_count_add_candidate($this->user_package, $this->user_role, $params, $columns));
            $x = 0;

            foreach ($cands_results as $key => $cands_result) {
               $data_arry[$x]['id'] = $x + 1;
                $data_arry[$x]['CandidateName'] = ucwords(strtolower($cands_result['CandidateName']));
                $data_arry[$x]['entity'] = ucwords(strtolower($cands_result['entity_name']));
                $data_arry[$x]['package'] = ucwords(strtolower($cands_result['package_name']));
                $data_arry[$x]['ClientRefNumber'] = $cands_result['ClientRefNumber'];
                $data_arry[$x]['cmp_ref_no'] = $cands_result['cmp_ref_no'];
                $data_arry[$x]['overallstatus'] = $cands_result['status_value'];
                $data_arry[$x]['overallclosuredate'] = convert_db_to_display_date($cands_result['overallclosuredate']);
                $data_arry[$x]['edit_id'] = CLIENT_SITE_URL . "candidates/view_details/" . encrypt($cands_result['cands_info_id']);
                $data_arry[$x]['report'] = "<a href='#' title='View' data-toggle='modal' data-target='#myModal' class='view_details' data-raw_id='" . $cands_result['id'] . "'><i class='fa fa-eye'></i></a>
                <a  class='status_alert' data-overallstatus='" . $cands_result['status_value'] . "' href='javascript:void(0)' data-href='" . CLIENT_SITE_URL . "candidates/report/" . encrypt($cands_result['cands_info_id']) . "/Final' title='Report'><i class='fa fa-file-pdf-o'></i></a>";

                $data_arry[$x]['clientname'] = ucwords(strtolower($cands_result['clientname']));
                $data_arry[$x]['caserecddate'] = convert_db_to_display_date($cands_result['created_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);

                $data_arry[$x]['pending_check'] = 'NA';
                $data_arry[$x]['insufficiency_check'] = 'NA';
                $data_arry[$x]['closed_check'] = 'NA';

                $array_wip = array();
                $array_insufficiency = array();
                $array_closed = array();

                $result_address_main = $this->candidates_model->get_addres_ver_status(array('candidates_info.id' => $cands_result['cands_info_id']));

                if (!empty($result_address_main)) {
                    $address_count = 1;
                    foreach ($result_address_main as $result_address) {

                        $address_component_status = ($result_address['var_filter_status'] != "") ? $result_address['var_filter_status'] : 'WIP';
                        //print_r($data_arry[$x]['address_component_status']);

                        if (($address_component_status == "WIP") || ($address_component_status == "wip")) {
                            array_push($array_wip, ' <i class="fas fa-home" style="color:#0000FF" title="Address '.$address_count.'"></i> ');
                        }
                        if (($address_component_status == "Insufficiency") || ($address_component_status == "insufficiency")) {
                            array_push($array_insufficiency, ' <i class="fas fa-home" style="color:#0000FF" title="Address '.$address_count.'"></i> ');
                        }
                        if (($address_component_status == "Closed") || ($address_component_status == "closed")) {
                            array_push($array_closed, ' <i class="fas fa-home" style="color:#0000FF" title="Address '.$address_count.'"></i> ');
                        }

                        $address_count++;

                    }
                }

                $result_education_main = $this->candidates_model->get_education_ver_status(array('candidates_info.id' => $cands_result['cands_info_id']));

                if (!empty($result_education_main)) {
                    $education_count = 1;
                    foreach ($result_education_main as $result_education) {

                        $education_component_status = ($result_education['filter_status'] != "") ? $result_education['filter_status'] : 'WIP';

                        if (($education_component_status == "WIP") || ($education_component_status == "wip")) {
                            array_push($array_wip, ' <i class="fas fa-graduation-cap" style="color:#0000FF" title="Education '.$education_count.'"></i> ');
                        }
                        if (($education_component_status == "Insufficiency") || ($education_component_status == "insufficiency")) {
                            array_push($array_insufficiency, ' <i class="fas fa-graduation-cap" style="color:#0000FF" title="Education '.$education_count.'"></i> ');
                        }
                        if (($education_component_status == "Closed") || ($education_component_status == "closed")) {
                            array_push($array_closed, ' <i class="fas fa-graduation-cap" style="color:#0000FF" title="Education '.$education_count.'"></i> ');
                        }

                        $education_count++;
                    }
                }

                $result_employment_main = $this->candidates_model->get_employment_ver_status1(array('candidates_info.id' => $cands_result['cands_info_id']));

                if (!empty($result_employment_main)) {
                    $employment_count = 1;

                    foreach ($result_employment_main as $result_employment) {

                        $employment_component_status = ($result_employment['var_filter_status'] != "") ? $result_employment['var_filter_status'] : 'WIP';

                        if (($employment_component_status == "WIP") || ($employment_component_status == "wip")) {
                            array_push($array_wip,' <i class="fas fa-laptop" style="color:#0000FF" title="Employment '.$employment_count.'"></i>  ');
                        }
                        if (($employment_component_status == "Insufficiency") || ($employment_component_status == "insufficiency")) {
                            array_push($array_insufficiency,' <i class="fas fa-laptop" style="color:#0000FF" title="Employment '.$employment_count.'"></i> ');
                        }
                        if (($employment_component_status == "Closed") || ($employment_component_status == "closed")) {
                            array_push($array_closed, ' <i class="fas fa-laptop" style="color:#0000FF" title="Employment '.$employment_count.'"></i> ');
                        }
                        $employment_count++;
                    }
                }

                $result_court_main = $this->candidates_model->get_court_ver_status(array('candidates_info.id' => $cands_result['cands_info_id']));

                if (!empty($result_court_main)) {
                    $court_count = 1;

                    foreach ($result_court_main as $result_court) {

                        $court_component_status = ($result_court['filter_status'] != "") ? $result_court['filter_status'] : 'WIP';

                       if (($court_component_status == "WIP") || ($court_component_status == "wip")) {
                            array_push($array_wip, ' <i class="fas fa-university" style="color:#0000FF" title="Court '.$court_count.'"></i> ');
                        }
                        if (($court_component_status == "Insufficiency") || ($court_component_status == "insufficiency")) {
                            array_push($array_insufficiency, ' <i class="fas fa-university" style="color:#0000FF" title="Court '.$court_count.'"></i> ');
                        }
                        if (($court_component_status == "Closed") || ($court_component_status == "closed")) {
                            array_push($array_closed, ' <i class="fas fa-university" style="color:#0000FF" title="Court '.$court_count.'"></i> ');
                        }

                        $court_count++;

                    }
                }

                $result_pcc_main = $this->candidates_model->get_pcc_ver_status(array('candidates_info.id' => $cands_result['cands_info_id']));

                if (!empty($result_pcc_main)) {

                    $pcc_count = 1;

                    foreach ($result_pcc_main as $result_pcc) {

                        $pcc_component_status = ($result_pcc['filter_status'] != "") ? $result_pcc['filter_status'] : 'WIP';

                        if (($pcc_component_status == "WIP") || ($pcc_component_status == "wip")) {
                            array_push($array_wip, ' <i class="fas fa-shield-alt" style="color:#0000FF"  title="PCC '.$pcc_count.'"></i> ');
                        }
                        if (($pcc_component_status == "Insufficiency") || ($pcc_component_status == "insufficiency")) {
                            array_push($array_insufficiency, ' <i class="fas fa-shield-alt" style="color:#0000FF"  title="PCC '.$pcc_count.'"></i> ');
                        }
                        if (($pcc_component_status == "Closed") || ($pcc_component_status == "closed")) {
                            array_push($array_closed, ' <i class="fas fa-shield-alt" style="color:#0000FF"  title="PCC '.$pcc_count.'"></i> ');
                        }

                        $pcc_count++;
                    }
                }

                $result_reference_main = $this->candidates_model->get_reference_ver_status(array('reference.candsid' => $cands_result['cands_info_id']));

                if (!empty($result_reference_main)) {

                    $reference_count = 1;

                    foreach ($result_reference_main as $result_reference) {

                        $reference_component_status = ($result_reference['filter_status'] != "") ? $result_reference['filter_status'] : 'WIP';

                       
                        if (($reference_component_status == "WIP") || ($reference_component_status == "wip")) {
                            array_push($array_wip,' <i class="fas fa-link" style="color:#0000FF" title="Reference '.$reference_count.'"></i> ');
                        }
                        if (($reference_component_status == "Insufficiency") || ($reference_component_status == "insufficiency")) {
                            array_push($array_insufficiency, ' <i class="fas fa-link" style="color:#0000FF" title="Reference '.$reference_count.'"></i> ');
                        }
                        if (($reference_component_status == "Closed") || ($reference_component_status == "closed")) {
                            array_push($array_closed, ' <i class="fas fa-link" style="color:#0000FF" title="Reference '.$reference_count.'"></i> ');
                        }

                        $reference_count++;
                    }

                }

                $result_global_main = $this->candidates_model->get_global_db_ver_status(array('candidates_info.id' => $cands_result['cands_info_id']));
                if (!empty($result_global_main)) {

                    $global_count = 1;

                    foreach ($result_global_main as $result_global) {

                        $globaldb_component_status = ($result_global['filter_status'] != "") ? $result_global['filter_status'] : 'WIP';

                         if (($globaldb_component_status == "WIP") || ($globaldb_component_status == "wip")) {
                            array_push($array_wip,' <i class="fas fa-globe" style="color:#0000FF" title="Global '.$global_count.'"></i> ');
                        }
                        if (($globaldb_component_status == "Insufficiency") || ($globaldb_component_status == "insufficiency")) {
                            array_push($array_insufficiency, ' <i class="fas fa-globe" style="color:#0000FF" title="Global '.$global_count.'"></i> ');
                        }
                        if (($globaldb_component_status == "Closed") || ($globaldb_component_status == "closed")) {
                            array_push($array_closed, ' <i class="fas fa-globe" style="color:#0000FF" title="Global '.$global_count.'"></i> ');
                        }

                        $global_count++;

                    }
                }

                $result_identity_main = $this->candidates_model->get_identity_ver_status(array('candidates_info.id' => $cands_result['cands_info_id']));

                if (!empty($result_identity_main)) {

                    $identity_count = 1;

                    foreach ($result_identity_main as $result_identity) {

                        $identity_component_status = ($result_identity['var_filter_status'] != "") ? $result_identity['var_filter_status'] : 'WIP';

                      
                        if (($identity_component_status == "WIP") || ($identity_component_status == "wip")) {
                            array_push($array_wip, ' <i class="far fa-id-card" style="color:#0000FF" title="Identity '.$identity_count.'"></i> ');
                        }
                        if (($identity_component_status == "Insufficiency") || ($identity_component_status == "insufficiency")) {
                            array_push($array_insufficiency, ' <i class="far fa-id-card" style="color:#0000FF" title="Identity '.$identity_count.'"></i> ');
                        }
                        if (($identity_component_status == "Closed") || ($identity_component_status == "closed")) {
                            array_push($array_closed, ' <i class="far fa-id-card" style="color:#0000FF" title="Identity '.$identity_count.'"></i> ');
                        }


                        $identity_count++;
                    }
                }

                $result_credit_report_main = $this->candidates_model->get_credit_reports_ver_status(array('candidates_info.id' => $cands_result['cands_info_id']));
                if (!empty($result_credit_report_main)) {

                    $credit_report_count = 1;

                    foreach ($result_credit_report_main as $result_credit_report) {

                        $credit_report_component_status = ($result_credit_report['var_filter_status'] != "") ? $result_credit_report['var_filter_status'] : 'WIP';

                        if (($credit_report_component_status == "WIP") || ($credit_report_component_status == "wip")) {
                            array_push($array_wip,' <i class="fas fa-credit-card" style="color:#0000FF" title="Credit Report '. $credit_report_count.'"></i> ');
                        }
                        if (($credit_report_component_status == "Insufficiency") || ($credit_report_component_status == "insufficiency")) {
                            array_push($array_insufficiency, ' <i class="fas fa-credit-card" style="color:#0000FF" title="Credit Report '. $credit_report_count.'"></i> ');
                        }
                        if (($credit_report_component_status == "Closed") || ($credit_report_component_status == "closed")) {
                            array_push($array_closed, ' <i class="fas fa-credit-card" style="color:#0000FF" title="Credit Report '. $credit_report_count.'"></i> ');
                        }

                        $credit_report_count++;
                    }
                }

                $result_drugs_main = $this->candidates_model->get_narcver_ver_status(array('candidates_info.id' => $cands_result['cands_info_id']));

                if (!empty($result_drugs_main)) {

                    $drugs_count = 1;

                    foreach ($result_drugs_main as $result_drugs) {
                      
                        $drugs_component_status = ($result_drugs['var_filter_status'] != "") ? $result_drugs['var_filter_status'] : 'WIP';

                        if (($drugs_component_status == "WIP") || ($drugs_component_status == "wip")) {
                            array_push($array_wip,' <i class="fas fa-prescription-bottle-alt" style="color:#0000FF" title="Drugs '. $drugs_count.'"></i> ');
                        }
                        if (($drugs_component_status == "Insufficiency") || ($drugs_component_status == "insufficiency")) {

                            array_push($array_insufficiency,' <i class="fas fa-prescription-bottle-alt" style="color:#0000FF" title="Drugs '. $drugs_count.'"></i> ');
                        }
                        if (($drugs_component_status == "Closed") || ($drugs_component_status == "closed")) {
                            array_push($array_closed,' <i class="fas fa-prescription-bottle-alt" style="color:#0000FF" title="Drugs '. $drugs_count.'"></i> ');
                        }
                        $drugs_count++;
                    }
                }

                $data_arry[$x]['pending_check'] = $array_wip;
                $data_arry[$x]['insufficiency_check'] = $array_insufficiency;
                $data_arry[$x]['closed_check'] = $array_closed;

                $x++;
            }

            $json_data = array(
                "draw" => intval($params['draw']),
                "recordsTotal" => intval($totalRecords),
                "recordsFiltered" => intval($totalRecords),
                "data" => $data_arry,
            );

            $this->echo_json($json_data);
        }
    }

    public function data_table_cands_view_initiation()
    {

        if ($this->input->is_ajax_request()) {
            $params = $data_arry = $columns = array();

            $params = $_REQUEST;

            $columns = array('id', 'CandidateName', "clientname", "caserecddate", 'ClientRefNumber', 'cmp_ref_no', 'status');

            $cands_results = $this->candidates_model->get_all_cand_with_search_initiation($this->user_package, $this->user_role, $params, $columns);

            $totalRecords = count($this->candidates_model->get_all_cand_with_search_count_initiation($this->user_package, $this->user_role, $params, $columns));
            $x = 0;

            foreach ($cands_results as $key => $cands_result) {
                if(empty($cands_result['component_ref_no']))
                {
                    $status = "Initiated";
                    
                }
                elseif($cands_result['overallstatus'] == '1'){
                    $status = "WIP";

                }
                elseif($cands_result['overallstatus'] == '2'){
                    $status = "NA";
                }
                elseif($cands_result['overallstatus'] == '3'){
                    $status = "Stop Check";

                }
                elseif($cands_result['overallstatus'] == '4'){
                    $status = "Clear";

                }
                elseif($cands_result['overallstatus'] == '5'){
                    $status = "Insufficiency";

                }
                elseif($cands_result['overallstatus'] == '6'){
                    $status = "Major Discrepancy";

                }
                elseif($cands_result['overallstatus'] == '7'){
                    $status = "Minor Discrepancy";

                }
                elseif($cands_result['overallstatus'] == '8'){
                    $status = "Unable to verify";

                }
                else{
                    $status = "";  
                  
                }
              
                if($cands_result['final_qc'] == 'Final QC Approve')
                {
                    $data_arry[$x]['report'] = "<a target='_blank' data-overallstatus='" . $status . "' href='" . CLIENT_SITE_URL . "candidates/report/" . encrypt($cands_result['candidate_id']) . "/Final' title='Report' style='margin-left: 25px;'><i class='fa fa-file' style = 'font-size:24px' aria-hidden='true'></i></a>";

                }
                else
                {
                    $data_arry[$x]['report'] = "";
                }

                
                $data_arry[$x]['id'] = $cands_result['id'];
                $data_arry[$x]['candidate_name'] = ucwords($cands_result['candidate_name']);
                $data_arry[$x]['client_ref_no'] = $cands_result['client_ref_no'];
                $data_arry[$x]['primary_contact'] = $cands_result['primary_contact'];
                $data_arry[$x]['contact_two'] = $cands_result['contact_two'];
                $data_arry[$x]['contact_three'] = $cands_result['contact_three'];
                $data_arry[$x]['component_ref_no'] = $cands_result['component_ref_no'];
                $data_arry[$x]['clientname'] = $cands_result['clientname'];
                $data_arry[$x]['entity'] = $cands_result['entity'];
                $data_arry[$x]['package'] = $cands_result['package'];
                $data_arry[$x]['status'] =  '<button class="btn btn-sm btn btn-info">'.ucwords($status).'</button>';
                
                $data_arry[$x]['initiation_date'] = convert_db_to_display_date($cands_result['initiation_date']);
                
               
                $x++;
            }

            $json_data = array(
                "draw" => intval($params['draw']),
                "recordsTotal" => intval($totalRecords),
                "recordsFiltered" => intval($totalRecords),
                "data" => $data_arry,
            );

            $this->echo_json($json_data);
        }
    }

    public function data_table_cands_view_pre_post()
    {

        if ($this->input->is_ajax_request()) {

            $params = $data_arry = $columns = array();

            $params = $_REQUEST;

            $columns = array('id', 'CandidateName', "clientname", "caserecddate", 'ClientRefNumber', 'cmp_ref_no', 'status');

            $cands_results = $this->candidates_model->get_all_cand_with_search_pre_post($this->user_package, $this->user_role, $params, $columns);

            $totalRecords = count($this->candidates_model->get_all_cand_with_search_count_pre_post($this->user_package, $this->user_role, $params, $columns));
            $x = 0;

            foreach ($cands_results as $key => $cands_result) {

                $data_arry[$x]['id'] = $cands_result['id'];
                $data_arry[$x]['candidate_name'] = ucwords($cands_result['candidate_name']);
                $data_arry[$x]['client_ref_no'] = $cands_result['client_ref_no'];
                $data_arry[$x]['primary_contact'] = $cands_result['primary_contact'];
                $data_arry[$x]['contact_two'] = $cands_result['contact_two'];
                $data_arry[$x]['contact_three'] = $cands_result['contact_three'];
                $data_arry[$x]['component_ref_no'] = $cands_result['component_ref_no'];
                $data_arry[$x]['clientname'] = $cands_result['clientname'];
                $data_arry[$x]['entity'] = $cands_result['entity'];
                $data_arry[$x]['package'] = $cands_result['package'];

                if(empty($cands_result['component_ref_no']))
                {
                    $status = "Initiated";
                    
                }
                elseif($cands_result['overallstatus'] == '1'){
                    $status = "WIP";

                }
                elseif($cands_result['overallstatus'] == '2'){
                    $status = "NA";
                }
                elseif($cands_result['overallstatus'] == '3'){
                    $status = "Stop Check";

                }
                elseif($cands_result['overallstatus'] == '4'){
                    $status = "Clear";

                }
                elseif($cands_result['overallstatus'] == '5'){
                    $status = "Insufficiency";

                }
                elseif($cands_result['overallstatus'] == '6'){
                    $status = "Major Discrepancy";

                }
                elseif($cands_result['overallstatus'] == '7'){
                    $status = "Minor Discrepancy";

                }
                elseif($cands_result['overallstatus'] == '8'){
                    $status = "Unable to verify";

                }
                else{
                    $status = "";  
                  
                }

                if($cands_result['final_qc'] == 'Final QC Approve')
                {
                    $data_arry[$x]['report'] = "<a target='_blank' data-overallstatus='" . $status . "' href='" . CLIENT_SITE_URL . "candidates/report/" . encrypt($cands_result['candidate_id']) . "/Final' title='Report' style='margin-left: 25px;'><i class='fa fa-file' style = 'font-size:24px' aria-hidden='true'></i></a>";

                }
                else
                {
                    $data_arry[$x]['report'] = "";
                }
                
                $pre_array = array();
                
                if(!empty($cands_result['component_ref_no']))
                {
                    $comp_ref_no =  explode('-',$cands_result['component_ref_no']);
                    
                    $candidate_details = $this->candidates_model->select_detials('candidates_info',array('id','clientid','entity','package'),array('candidates_info.id'=>$comp_ref_no[1]));
                   
                    $client_details = $this->candidates_model->select_detials('clients_details',array('pre_component','post_component'),array('clients_details.tbl_clients_id'=>$candidate_details[0]['clientid'],'clients_details.entity'=>$candidate_details[0]['entity'],'clients_details.package'=> $candidate_details[0]['package']));
                    $pre_component =  explode(',',$client_details[0]['pre_component']);
                    if(in_array('addrver', $pre_component))
                    {
                        $address_details = $this->candidates_model->get_addres_ver_status_list(array('candidates_info.id' => $comp_ref_no[1]));
                    
                    
                        foreach ($address_details as $result_address) {

                            $address_component_status = ($result_address['verfstatus'] != "") ? $result_address['verfstatus'] : 'WIP';
                            array_push($pre_array,$address_component_status);
                        }
                    }
                   
                    if(in_array('eduver', $pre_component))
                    {
                        $education_details = $this->candidates_model->get_education_ver_status_list(array('candidates_info.id' => $comp_ref_no[1]));
                        
                        foreach ($education_details as $result_education) {

                            $education_component_status = ($result_education['verfstatus'] != "") ? $result_education['verfstatus'] : 'WIP';
                            array_push($pre_array,$education_component_status);
                        }
                   
                    }
                    if(in_array('empver', $pre_component))
                    { 

                        $employment_details = $this->candidates_model->get_employment_ver_status_list(array('candidates_info.id' => $comp_ref_no[1]));
                        foreach ($employment_details as $result_employment) {

                            $employment_component_status = ($result_employment['verfstatus'] != "") ? $result_employment['verfstatus'] : 'WIP';
                            array_push($pre_array,$employment_component_status);
                        }
                    }

                    if(in_array('courtver', $pre_component))
                    { 
                        $court_details = $this->candidates_model->get_court_ver_status_list(array('candidates_info.id' => $comp_ref_no[1]));
                  
                        foreach ($court_details as $result_court) {

                            $court_component_status = ($result_court['verfstatus'] != "") ? $result_court['verfstatus'] : 'WIP';
                            array_push($pre_array,$court_component_status);
                        }
                   
                    }  
                    if(in_array('crimver', $pre_component))
                    {
                        $pcc_details = $this->candidates_model->get_pcc_ver_status_list(array('candidates_info.id' => $comp_ref_no[1]));
                        
                        foreach ($pcc_details as $result_pcc) {

                            $pcc_component_status = ($result_pcc['verfstatus'] != "") ? $result_pcc['verfstatus'] : 'WIP';
                            array_push($pre_array,$pcc_component_status);
                        }
                   
                    }
                    if(in_array('refver', $pre_component))
                    {
                        $reference_details = $this->candidates_model->get_refver_ver_status_list(array('reference.candsid' => $comp_ref_no[1]));
                        
                        foreach ($reference_details as $result_reference) {

                            $reference_component_status = ($result_reference['verfstatus'] != "") ? $result_reference['verfstatus'] : 'WIP';
                            array_push($pre_array,$reference_component_status);
                        }
                    }

                    if(in_array('globdbver', $pre_component))
                    {
                        $global_details = $this->candidates_model->get_globdbver_ver_status_list(array('candidates_info.id' => $comp_ref_no[1]));

                        foreach ($global_details as $result_global) {

                            $global_component_status = ($result_global['verfstatus'] != "") ? $result_global['verfstatus'] : 'WIP';
                            array_push($pre_array,$global_component_status);
                        }
                    }
                    if(in_array('identity', $pre_component))
                    {
                        $identity_details = $this->candidates_model->get_identity_ver_status_list(array('candidates_info.id' => $comp_ref_no[1]));

                        
                        foreach ($identity_details as $result_identity) {

                            $identity_component_status = ($result_identity['verfstatus'] != "") ? $result_identity['verfstatus'] : 'WIP';
                            array_push($pre_array,$identity_component_status);
                        }
                    }
                    if(in_array('cbrver', $pre_component))
                    {
                        $credit_report_details = $this->candidates_model->get_credit_reports_ver_status_list(array('candidates_info.id' => $comp_ref_no[1]));
                        
                        foreach ($credit_report_details as $result_credit_report) {

                            $credit_report_component_status = ($result_credit_report['verfstatus'] != "") ? $result_credit_report['verfstatus'] : 'WIP';
                            array_push($pre_array,$credit_report_component_status);
                        }
                   
                    }
                    if(in_array('narcver', $pre_component))
                    {
                        $drugs_details = $this->candidates_model->get_narcver_ver_status_list(array('candidates_info.id' => $comp_ref_no[1]));
            
                        foreach ($drugs_details as $result_drugs) {

                            $drugs_component_status = ($result_drugs['verfstatus'] != "") ? $result_drugs['verfstatus'] : 'WIP';
                            array_push($pre_array,$drugs_component_status);
                        }
               
                    }
                    
                    if(in_array('Insufficiency',$pre_array)){
                        $actual_status = 'Insufficiency';
                    } 
                    elseif(in_array('WIP',$pre_array) || in_array("Insufficiency Cleared", $pre_array) || in_array("Final QC Reject", $pre_array) || in_array("First QC Reject", $pre_array) || in_array("New Check", $pre_array) || in_array("YTR", $pre_array) || in_array("Follow Up", $pre_array) || in_array("Re-Initiated", $pre_array)){
                        $actual_status = 'WIP';
                    }
                    elseif(in_array('Major Discrepancy',$pre_array))
                    {
                        $actual_status = 'Major Discrepancy';
                    }
                    elseif(in_array('Major Discrepancy',$pre_array)){
                        $actual_status = 'Minor Discrepancy';
                    } 
                    elseif(in_array('Unable to verify',$pre_array)){
                        $actual_status = 'Unable to verify';
                    } 
                    elseif(in_array('Stop Check',$pre_array)){
                        $actual_status = 'Stop Check';
                    } 
                    elseif(in_array('Clear',$pre_array)){
                        $actual_status = 'Clear';
                    } 
                    else{
                        $actual_status = '';
                    }
                    
                }
                else
                {
                    $actual_status = 'Initiated';  
                } 
               
                if($actual_status == "Major Discrepancy" || $actual_status == "Minor Discrepancy" || $actual_status == "Unable to verify" || $actual_status == "Stop Check" || $actual_status == "Clear")
                {
                    $data_arry[$x]['action'] = '<td><button data-id="showAddResultModel" data-url ="' . CLIENT_SITE_URL . 'candidates/add_post/' . $cands_result['id'] . '" data-toggle="modal" data-target="#showAddResultModel1"  class="btn-info  showAddResultModel"> Add Post </button></td>';
                }
                else{
                    $data_arry[$x]['action'] = "";
                }

                $data_arry[$x]['initiation_date'] = convert_db_to_display_date($cands_result['initiation_date']);
                $data_arry[$x]['post_initiation_date'] = convert_db_to_display_date($cands_result['post_initiation_date']);
                $data_arry[$x]['pre_status'] =  '<button class="btn btn-sm btn btn-info">'.ucwords( $actual_status).'</button>';

                $data_arry[$x]['status'] =  '<button class="btn btn-sm btn btn-info">'.ucwords( $status).'</button>';
            

                $x++;
            }

            $json_data = array(
                "draw" => intval($params['draw']),
                "recordsTotal" => intval($totalRecords),
                "recordsFiltered" => intval($totalRecords),
                "data" => $data_arry,
            );

            $this->echo_json($json_data);
        }
    }



    public function data_table_cands_view_mail_view()
    {
        if ($this->input->is_ajax_request()) {
            $params = $data_arry = $columns = array();

            $params = $_REQUEST;

            $columns = array('id', 'CandidateName', "clientname", "caserecddate", 'ClientRefNumber', 'cmp_ref_no', 'overallstatus', 'remarks', 'view', 'encry_id');

            $cands_results = $this->candidates_model->get_all_cand_with_search_mail_view($this->user_package, $this->user_role, $params, $columns);

            $totalRecords = count($this->candidates_model->get_all_cand_with_search_count_add_candidate($this->user_package, $this->user_role, $params, $columns));
            $x = 0;

            foreach ($cands_results as $key => $cands_result) {
                $data_arry[$x]['id'] = $x + 1;
                $data_arry[$x]['candiate_id'] = $cands_result['cands_info_id'];
                $data_arry[$x]['CandidateName'] = ucwords(strtolower($cands_result['CandidateName']));
                $data_arry[$x]['Entity'] = ucwords(strtolower($cands_result['entity_name']));
                $data_arry[$x]['Package'] = ucwords(strtolower($cands_result['package_name']));
                $data_arry[$x]['ClientRefNumber'] = $cands_result['ClientRefNumber'];
                $data_arry[$x]['cmp_ref_no'] = $cands_result['cmp_ref_no'];
                $data_arry[$x]['overallstatus'] = $cands_result['status_value'];
                $data_arry[$x]['edit_id'] = CLIENT_SITE_URL . "candidates/view_details/" . encrypt($cands_result['id']);
                $data_arry[$x]['overallclosuredate'] = convert_db_to_display_date($cands_result['overallclosuredate']);
          

                $data_arry[$x]['clientname'] = ucwords(strtolower($cands_result['clientname']));
                $data_arry[$x]['caserecddate'] = convert_db_to_display_date($cands_result['created_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);

                 $login_url = '';
                
                $data_arry[$x]['link'] = $login_url;    

                $data_arry[$x]['mail_sent'] = "<button class='btn btn-info btn-sm trigger_email_again' id='".$cands_result['id']."'>Send (0)</button>";
                $data_arry[$x]['sms_sent'] = "<button class='btn btn-info btn-sm trigger_sms_again' id='".$cands_result['id']."'>Send (0)</button>";
                $data_arry[$x]['copy_link'] = '';
                if($cands_result['public_key'] != "" && $cands_result['private_key'] != "") {   
                    $login_url = CANDIDATE_VERIFY_LINK.$cands_result['public_key'].'/'.$cands_result['private_key'];
                    $data_arry[$x]['link'] = $login_url;
                    $data_arry[$x]['mail_sent'] = "<button class='btn btn-info btn-sm trigger_email_again' id='".$cands_result['id']."'>Send (".$cands_result['is_mail_sent'].")</button>";
                    $data_arry[$x]['sms_sent'] = "<button class='btn btn-info btn-sm trigger_sms_again' id='".$cands_result['id']."'>Send (".$cands_result['is_sms_sent'].")</button>";

                    $data_arry[$x]['copy_link'] = "<button class='btn btn-info btn-sm copyLink' data-link='".$login_url ."' id=".$x.">Copy Link</button>";
                }

                $data_arry[$x]['pending_check'] = 'NA';
                $data_arry[$x]['insufficiency_check'] = 'NA';
                $data_arry[$x]['closed_check'] = 'NA';

                $array_wip = array();
                $array_insufficiency = array();
                $array_closed = array();

                $this->load->model('addressver_model');

                  $result_address_main = $this->candidates_model->get_addres_ver_status(array('candidates_info.id' => $cands_result['cands_info_id']));

                if (!empty($result_address_main)) {
                    $address_count = 1;
                    foreach ($result_address_main as $result_address) {

                        $address_component_status = ($result_address['var_filter_status'] != "") ? $result_address['var_filter_status'] : 'WIP';
                        //print_r($data_arry[$x]['address_component_status']);

                        if (($address_component_status == "WIP") || ($address_component_status == "wip")) {
                            array_push($array_wip, ' <i class="fas fa-home" style="color:#0000FF" title="Address '.$address_count.'"></i> ');
                        }
                        if (($address_component_status == "Insufficiency") || ($address_component_status == "insufficiency")) {
                            array_push($array_insufficiency, ' <i class="fas fa-home" style="color:#0000FF" title="Address '.$address_count.'"></i> ');
                        }
                        if (($address_component_status == "Closed") || ($address_component_status == "closed")) {
                            array_push($array_closed, ' <i class="fas fa-home" style="color:#0000FF" title="Address '.$address_count.'"></i> ');
                        }

                        $address_count++;

                    }
                }

                $result_education_main = $this->candidates_model->get_education_ver_status(array('candidates_info.id' => $cands_result['cands_info_id']));

                if (!empty($result_education_main)) {
                    $education_count = 1;
                    foreach ($result_education_main as $result_education) {

                        $education_component_status = ($result_education['filter_status'] != "") ? $result_education['filter_status'] : 'WIP';

                        if (($education_component_status == "WIP") || ($education_component_status == "wip")) {
                            array_push($array_wip, ' <i class="fas fa-graduation-cap" style="color:#0000FF" title="Education '.$education_count.'"></i> ');
                        }
                        if (($education_component_status == "Insufficiency") || ($education_component_status == "insufficiency")) {
                            array_push($array_insufficiency, ' <i class="fas fa-graduation-cap" style="color:#0000FF" title="Education '.$education_count.'"></i> ');
                        }
                        if (($education_component_status == "Closed") || ($education_component_status == "closed")) {
                            array_push($array_closed, ' <i class="fas fa-graduation-cap" style="color:#0000FF" title="Education '.$education_count.'"></i> ');
                        }

                        $education_count++;
                    }
                }

                $result_employment_main = $this->candidates_model->get_employment_ver_status1(array('candidates_info.id' => $cands_result['cands_info_id']));

                if (!empty($result_employment_main)) {
                    $employment_count = 1;

                    foreach ($result_employment_main as $result_employment) {

                        $employment_component_status = ($result_employment['var_filter_status'] != "") ? $result_employment['var_filter_status'] : 'WIP';

                        if (($employment_component_status == "WIP") || ($employment_component_status == "wip")) {
                            array_push($array_wip,' <i class="fas fa-laptop" style="color:#0000FF" title="Employment '.$employment_count.'"></i>  ');
                        }
                        if (($employment_component_status == "Insufficiency") || ($employment_component_status == "insufficiency")) {
                            array_push($array_insufficiency,' <i class="fas fa-laptop" style="color:#0000FF" title="Employment '.$employment_count.'"></i> ');
                        }
                        if (($employment_component_status == "Closed") || ($employment_component_status == "closed")) {
                            array_push($array_closed, ' <i class="fas fa-laptop" style="color:#0000FF" title="Employment '.$employment_count.'"></i> ');
                        }
                        $employment_count++;
                    }
                }

                $result_court_main = $this->candidates_model->get_court_ver_status(array('candidates_info.id' => $cands_result['cands_info_id']));

                if (!empty($result_court_main)) {
                    $court_count = 1;

                    foreach ($result_court_main as $result_court) {

                        $court_component_status = ($result_court['filter_status'] != "") ? $result_court['filter_status'] : 'WIP';

                       if (($court_component_status == "WIP") || ($court_component_status == "wip")) {
                            array_push($array_wip, ' <i class="fas fa-university" style="color:#0000FF" title="Court '.$court_count.'"></i> ');
                        }
                        if (($court_component_status == "Insufficiency") || ($court_component_status == "insufficiency")) {
                            array_push($array_insufficiency, ' <i class="fas fa-university" style="color:#0000FF" title="Court '.$court_count.'"></i> ');
                        }
                        if (($court_component_status == "Closed") || ($court_component_status == "closed")) {
                            array_push($array_closed, ' <i class="fas fa-university" style="color:#0000FF" title="Court '.$court_count.'"></i> ');
                        }

                        $court_count++;

                    }
                }

                $result_pcc_main = $this->candidates_model->get_pcc_ver_status(array('candidates_info.id' => $cands_result['cands_info_id']));

                if (!empty($result_pcc_main)) {

                    $pcc_count = 1;

                    foreach ($result_pcc_main as $result_pcc) {

                        $pcc_component_status = ($result_pcc['filter_status'] != "") ? $result_pcc['filter_status'] : 'WIP';

                        if (($pcc_component_status == "WIP") || ($pcc_component_status == "wip")) {
                            array_push($array_wip, ' <i class="fas fa-shield-alt" style="color:#0000FF"  title="PCC '.$pcc_count.'"></i> ');
                        }
                        if (($pcc_component_status == "Insufficiency") || ($pcc_component_status == "insufficiency")) {
                            array_push($array_insufficiency, ' <i class="fas fa-shield-alt" style="color:#0000FF"  title="PCC '.$pcc_count.'"></i> ');
                        }
                        if (($pcc_component_status == "Closed") || ($pcc_component_status == "closed")) {
                            array_push($array_closed, ' <i class="fas fa-shield-alt" style="color:#0000FF"  title="PCC '.$pcc_count.'"></i> ');
                        }

                        $pcc_count++;
                    }
                }

                $result_reference_main = $this->candidates_model->get_reference_ver_status(array('reference.candsid' => $cands_result['cands_info_id']));

                if (!empty($result_reference_main)) {

                    $reference_count = 1;

                    foreach ($result_reference_main as $result_reference) {

                        $reference_component_status = ($result_reference['filter_status'] != "") ? $result_reference['filter_status'] : 'WIP';

                       
                        if (($reference_component_status == "WIP") || ($reference_component_status == "wip")) {
                            array_push($array_wip,' <i class="fas fa-link" style="color:#0000FF" title="Reference '.$reference_count.'"></i> ');
                        }
                        if (($reference_component_status == "Insufficiency") || ($reference_component_status == "insufficiency")) {
                            array_push($array_insufficiency, ' <i class="fas fa-link" style="color:#0000FF" title="Reference '.$reference_count.'"></i> ');
                        }
                        if (($reference_component_status == "Closed") || ($reference_component_status == "closed")) {
                            array_push($array_closed, ' <i class="fas fa-link" style="color:#0000FF" title="Reference '.$reference_count.'"></i> ');
                        }

                        $reference_count++;
                    }

                }

                $result_global_main = $this->candidates_model->get_global_db_ver_status(array('candidates_info.id' => $cands_result['cands_info_id']));
                if (!empty($result_global_main)) {

                    $global_count = 1;

                    foreach ($result_global_main as $result_global) {

                        $globaldb_component_status = ($result_global['filter_status'] != "") ? $result_global['filter_status'] : 'WIP';

                         if (($globaldb_component_status == "WIP") || ($globaldb_component_status == "wip")) {
                            array_push($array_wip,' <i class="fas fa-globe" style="color:#0000FF" title="Global '.$global_count.'"></i> ');
                        }
                        if (($globaldb_component_status == "Insufficiency") || ($globaldb_component_status == "insufficiency")) {
                            array_push($array_insufficiency, ' <i class="fas fa-globe" style="color:#0000FF" title="Global '.$global_count.'"></i> ');
                        }
                        if (($globaldb_component_status == "Closed") || ($globaldb_component_status == "closed")) {
                            array_push($array_closed, ' <i class="fas fa-globe" style="color:#0000FF" title="Global '.$global_count.'"></i> ');
                        }

                        $global_count++;

                    }
                }

                $result_identity_main = $this->candidates_model->get_identity_ver_status(array('candidates_info.id' => $cands_result['cands_info_id']));

                if (!empty($result_identity_main)) {

                    $identity_count = 1;

                    foreach ($result_identity_main as $result_identity) {

                        $identity_component_status = ($result_identity['var_filter_status'] != "") ? $result_identity['var_filter_status'] : 'WIP';

                      
                        if (($identity_component_status == "WIP") || ($identity_component_status == "wip")) {
                            array_push($array_wip, ' <i class="far fa-id-card" style="color:#0000FF" title="Identity '.$identity_count.'"></i> ');
                        }
                        if (($identity_component_status == "Insufficiency") || ($identity_component_status == "insufficiency")) {
                            array_push($array_insufficiency, ' <i class="far fa-id-card" style="color:#0000FF" title="Identity '.$identity_count.'"></i> ');
                        }
                        if (($identity_component_status == "Closed") || ($identity_component_status == "closed")) {
                            array_push($array_closed, ' <i class="far fa-id-card" style="color:#0000FF" title="Identity '.$identity_count.'"></i> ');
                        }


                        $identity_count++;
                    }
                }

                $result_credit_report_main = $this->candidates_model->get_credit_reports_ver_status(array('candidates_info.id' => $cands_result['cands_info_id']));
                if (!empty($result_credit_report_main)) {

                    $credit_report_count = 1;

                    foreach ($result_credit_report_main as $result_credit_report) {

                        $credit_report_component_status = ($result_credit_report['var_filter_status'] != "") ? $result_credit_report['var_filter_status'] : 'WIP';

                        if (($credit_report_component_status == "WIP") || ($credit_report_component_status == "wip")) {
                            array_push($array_wip,' <i class="fas fa-credit-card" style="color:#0000FF" title="Credit Report '. $credit_report_count.'"></i> ');
                        }
                        if (($credit_report_component_status == "Insufficiency") || ($credit_report_component_status == "insufficiency")) {
                            array_push($array_insufficiency, ' <i class="fas fa-credit-card" style="color:#0000FF" title="Credit Report '. $credit_report_count.'"></i> ');
                        }
                        if (($credit_report_component_status == "Closed") || ($credit_report_component_status == "closed")) {
                            array_push($array_closed, ' <i class="fas fa-credit-card" style="color:#0000FF" title="Credit Report '. $credit_report_count.'"></i> ');
                        }

                        $credit_report_count++;
                    }
                }

                $result_drugs_main = $this->candidates_model->get_narcver_ver_status(array('candidates_info.id' => $cands_result['cands_info_id']));
                if (!empty($result_drugs_main)) {

                    $drugs_count = 1;

                    foreach ($result_drugs_main as $result_drugs) {

                        $drugs_component_status = ($result_drugs['var_filter_status'] != "") ? $result_drugs['var_filter_status'] : 'WIP';

                        if (($drugs_component_status == "WIP") || ($drugs_component_status == "wip")) {
                            array_push($array_wip,' <i class="fas fa-prescription-bottle-alt" style="color:#0000FF" title="Drugs '. $drugs_count.'"></i> ');
                        }
                        if (($drugs_component_status == "Insufficiency") || ($drugs_component_status == "insufficiency")) {
                            array_push($array_insufficiency, ' <i class="fas fa-prescription-bottle-alt" style="color:#0000FF" title="Drugs '. $drugs_count.'"></i> ');
                        }
                        if (($drugs_component_status == "Closed") || ($drugs_component_status == "closed")) {
                            array_push($array_closed, ' <i class="fas fa-prescription-bottle-alt" style="color:#0000FF" title="Drugs '. $drugs_count.'"></i> ');
                        }
                        $drugs_count++;
                    }
                }


                $data_arry[$x]['pending_check'] = $array_wip;
                $data_arry[$x]['insufficiency_check'] = $array_insufficiency;
                $data_arry[$x]['closed_check'] = $array_closed;

                $x++;
            }

            $json_data = array(
                "draw" => intval($params['draw']),
                "recordsTotal" => intval($totalRecords),
                "recordsFiltered" => intval($totalRecords),
                "data" => $data_arry,
            );

            $this->echo_json($json_data);
        }
    }

    public function sub_status_list_candidates()
    {
        if ($this->input->is_ajax_request()) {
            $return = $this->sub_status_candidates($this->input->post('status'));
        }
        echo_json(array('sub_status_list' => $return));
    }

    public function frm_add_initiation()
    {
        if ($this->input->is_ajax_request()) {
            
            $this->form_validation->set_rules('clientid', 'Client ID', 'required');

            $this->form_validation->set_rules('entity', 'Entity', 'required');

            $this->form_validation->set_rules('package', 'Package', 'required');

            $this->form_validation->set_rules('caserecddate', 'Case Received Date', 'required');

            $this->form_validation->set_rules('ClientRefNumber', 'Client Ref Number', 'required');

            $this->form_validation->set_rules('CandidateName', 'Candidate Name', 'required');

            $this->form_validation->set_rules('CandidatesContactNumber', 'Primary Cantact', 'required');

            if ($this->form_validation->run() == false) {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');
            } else {

                $frm_details = $this->input->post();


                $client_ref_number = str_replace(" ", "", $frm_details['ClientRefNumber']);

                $fields = array('client_id' => $frm_details['clientid'],
                    'total_cases' => 1,
                    'status' => 'wip',
                    'created_by' => 1,
                    'created_on' => date(DB_DATE_FORMAT),
                    'type' => "new case",
                    'remarks' => $frm_details['remarks'],
                    'task_person_id' => $this->candidate_upload[0]['user_upload']
                );
      
                $result_task = $this->candidates_model->save_task_manager('client_new_cases',$fields);

                if($result_task)
                {
                    $pre_post_details = array('task_manager_id' => $result_task,
                        'client_id' => $frm_details['clientid'],
                        'entity' => $frm_details['entity'],
                        'package' => $frm_details['package'],
                        'type' => 2,
                        'initiation_date' => convert_display_to_db_date($frm_details['caserecddate']),
                        'client_ref_no' => $frm_details['ClientRefNumber'],
                        'candidate_name' =>  $frm_details['CandidateName'],
                        'primary_contact' => $frm_details['CandidatesContactNumber'],
                        'contact_two' => $frm_details['ContactNo1'],
                        'contact_three' => $frm_details['ContactNo2'],
                        'status' => 'Initiation',
                        'remarks' =>  $frm_details['remarks'],
                        'created_on' => date(DB_DATE_FORMAT)
                     );
                     
                     $result_pre_post = $this->candidates_model->save_task_manager('pre_post_details',$pre_post_details);
                    
                     $file_upload_path = SITE_BASE_PATH . "uploads/task_file/";
                     
                     $attached_file = '';

                     if (!folder_exist($file_upload_path)) {
                         mkdir($file_upload_path, 0777);
                     } else if (!is_writable($file_upload_path)) {
                         array_push($error_msgs, 'Problem while uploading');
                     }
 
                     $config_array = array('file_upload_path' => $file_upload_path, 
                                           'file_permission' => '*',
                                           'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 20000, 
                                           'file_id' => $result_task,
                                           'component_name' => 'client_new_case_id');
 
                    if ($_FILES['attchments']['name'][0] != '') {
                         $config_array['files_count'] = count($_FILES['attchments']['name']);
                         $config_array['file_data'] = $_FILES['attchments'];
                         $config_array['status'] = 1;
                         $retunr_de = $this->file_upload_multiple_task($config_array);
                         if (!empty($retunr_de)) {
                          
                             $this->common_model->common_insert_batch('client_new_case_file', $retunr_de['success']);
                         }

                        $attached_file = $retunr_de['success'][0]['file_name'];
                    }

                    $client_name = $this->candidates_model->select_detials('clients',array('clientname'),array('clients.id'=> $frm_details['clientid']));
                    $entity_name = $this->candidates_model->select_detials('entity_package',array('entity_package_name'),array('entity_package.id'=> $frm_details['entity']));
                    $package_name = $this->candidates_model->select_detials('entity_package',array('entity_package_name'),array('entity_package.id'=> $frm_details['package']));
                    $client_manager_id = $this->candidates_model->select_detials('clients',array('clientmgr'),array('clients.id'=> $frm_details['clientid']));
                    $client_manager_email = $this->candidates_model->select_detials('user_profile',array('email'),array('user_profile.id'=> $client_manager_id[0]['clientmgr']));

         
                    $email_tmpl_data['subject'] = 'BGV request for '.ucwords($frm_details['CandidateName']). ' received_'.date("d-M-Y H:i");
                    $message = "<p>Greetings from Mist IT Services Pvt Ltd!</p>";
                    $message .= "<p>We have received the following request!</p>";
                        
                    $message .= "<table border = '2'  style='border-spacing: 10; border-collapse: collapse;'>
                        <tr><td style='background-color: #EDEDED;text-align:center'>Client Details</td><td text-align:center'>".$client_name[0]['clientname']." | " .$entity_name[0]['entity_package_name']." | ".$package_name[0]['entity_package_name']."</td></tr>
                        <tr><td style='background-color: #EDEDED;text-align:center'>Candidate Name</td><td text-align:center'>".ucwords($frm_details['CandidateName'])."</td></tr>
                        <tr><td style='background-color: #EDEDED;text-align:center'>Initiation Type</td><td text-align:center'> BGV </td></tr>
                        <tr><td style='background-color: #EDEDED;text-align:center'>Received date</td><td text-align:center'>".$frm_details['caserecddate']."</td></tr>
                        <tr><td style='background-color: #EDEDED;text-align:center'>Client ID</td><td text-align:center'>".$frm_details['ClientRefNumber']."</td></tr>
                        <tr><td style='background-color: #EDEDED;text-align:center'>Mist ID</td><td text-align:center'>Pending</td></tr>
                        <tr><td style='background-color: #EDEDED;text-align:center'>Candidate contact details</td><td text-align:center'>".$frm_details['CandidatesContactNumber']."</td></tr>
                        <tr><td style='background-color: #EDEDED;text-align:center'>Status</td><td text-align:center'>Initiated</td></tr>
                        <tr><td style='background-color: #EDEDED;text-align:center'>Attachment</td><td text-align:center'><a href='http://3.109.13.110/mist-it/uploads/task_file/".$attached_file."' target='_blank'>".$attached_file."</a></td></tr>";

                        $message .= "</table>";

                        $message .= "<br><br><p><b>Note :</b> <I> This is an auto generated email. Request you to write back to report any discrepancy.</I></p>";
                        $message .= "<br><br><p><b>Regards,</b></p>";
                        $message .= "<p>BGV Team</p>";

                        

                        $email_tmpl_data['from_emails'] = $client_manager_email[0]['email'];

                        $email_tmpl_data['to_emails'] = $this->client_info['email_id'];

                        $email_tmpl_data['message'] = $message;
                        
  
                        $result_email = $this->email->client_add_cases_and_pre_post($email_tmpl_data);

                }

                $json_array['status'] = SUCCESS_CODE;

                $json_array['message'] = 'Info Updated Successfully';
            }
            echo_json($json_array);
        } else {
            permission_denied();
        }

    }

    public function frm_add_pre_post()
    {
        if ($this->input->is_ajax_request()) {
            
            $this->form_validation->set_rules('clientid', 'Client ID', 'required');

            $this->form_validation->set_rules('entity', 'Entity', 'required');

            $this->form_validation->set_rules('package', 'Package', 'required');

            $this->form_validation->set_rules('caserecddate', 'Case Received Date', 'required');

            $this->form_validation->set_rules('ClientRefNumber', 'Client Ref Number', 'required');

            $this->form_validation->set_rules('CandidateName', 'Candidate Name', 'required');

            $this->form_validation->set_rules('CandidatesContactNumber', 'Primary Cantact', 'required');

            if ($this->form_validation->run() == false) {

                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');

            } else {

                $frm_details = $this->input->post();

                $client_ref_number = str_replace(" ", "", $frm_details['ClientRefNumber']);

                $fields = array('client_id' => $frm_details['clientid'],
                    'total_cases' => 1,
                    'status' => 'wip',
                    'created_by' => 1,
                    'created_on' => date(DB_DATE_FORMAT),
                    'type' => "new case",
                    'remarks' => $frm_details['remarks'],
                    'task_person_id' => $this->candidate_upload[0]['user_upload']
                );
      
                $result_task = $this->candidates_model->save_task_manager('client_new_cases',$fields);

                if($result_task)
                {
                    $pre_post_details = array('task_manager_id' => $result_task,
                        'client_id' => $frm_details['clientid'],
                        'entity' => $frm_details['entity'],
                        'package' => $frm_details['package'],
                        'type' => 3,
                        'initiation_date' => convert_display_to_db_date($frm_details['caserecddate']),
                        'client_ref_no' => $frm_details['ClientRefNumber'],
                        'candidate_name' =>  $frm_details['CandidateName'],
                        'primary_contact' => $frm_details['CandidatesContactNumber'],
                        'contact_two' => $frm_details['ContactNo1'],
                        'contact_three' => $frm_details['ContactNo2'],
                        'status' => 'Initiation',
                        'remarks' => $frm_details['remarks'],
                        'created_on' => date(DB_DATE_FORMAT)
                     );
                     
                     $result_pre_post = $this->candidates_model->save_task_manager('pre_post_details',$pre_post_details);
                    
                     $file_upload_path = SITE_BASE_PATH . "uploads/task_file/";

                     $attached_file = '';


                     if (!folder_exist($file_upload_path)) {
                         mkdir($file_upload_path, 0777);
                     } else if (!is_writable($file_upload_path)) {
                         array_push($error_msgs, 'Problem while uploading');
                     }
 
                     $config_array = array('file_upload_path' => $file_upload_path, 
                                           'file_permission' => '*',
                                           'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 20000, 
                                           'file_id' => $result_task,
                                           'component_name' => 'client_new_case_id');
 
                    if ($_FILES['attchments']['name'][0] != '') {
                         $config_array['files_count'] = count($_FILES['attchments']['name']);
                         $config_array['file_data'] = $_FILES['attchments'];
                         $config_array['status'] = 1;
                         $retunr_de = $this->file_upload_multiple_task($config_array);
                         if (!empty($retunr_de)) {    

                             $this->common_model->common_insert_batch('client_new_case_file', $retunr_de['success']);
                        }

                        $attached_file = $retunr_de['success'][0]['file_name'];
                    }

                    
                    $client_name = $this->candidates_model->select_detials('clients',array('clientname'),array('clients.id'=> $frm_details['clientid']));
                    $entity_name = $this->candidates_model->select_detials('entity_package',array('entity_package_name'),array('entity_package.id'=> $frm_details['entity']));
                    $package_name = $this->candidates_model->select_detials('entity_package',array('entity_package_name'),array('entity_package.id'=> $frm_details['package']));
                    $client_manager_id = $this->candidates_model->select_detials('clients',array('clientmgr'),array('clients.id'=> $frm_details['clientid']));
                    $client_manager_email = $this->candidates_model->select_detials('user_profile',array('email'),array('user_profile.id'=> $client_manager_id[0]['clientmgr']));

         
                    $email_tmpl_data['subject'] = 'Pre BGV request for '.ucwords($frm_details['CandidateName']). ' received_'.date("d-M-Y H:i");
                    $message = "<p>Greetings from Mist IT Services Pvt Ltd!</p>";
                    $message .= "<p>We have received the following request!</p>";
                        
                    $message .= "<table border = '2'  style='border-spacing: 10; border-collapse: collapse;'>
                        <tr><td style='background-color: #EDEDED;text-align:center'>Client Details</td><td text-align:center'>".$client_name[0]['clientname']." | " .$entity_name[0]['entity_package_name']." | ".$package_name[0]['entity_package_name']."</td></tr>
                        <tr><td style='background-color: #EDEDED;text-align:center'>Candidate Name</td><td text-align:center'>".ucwords($frm_details['CandidateName'])."</td></tr>
                        <tr><td style='background-color: #EDEDED;text-align:center'>Initiation Type</td><td text-align:center'> Pre BGV </td></tr>
                        <tr><td style='background-color: #EDEDED;text-align:center'>Received date</td><td text-align:center'>".$frm_details['caserecddate']."</td></tr>
                        <tr><td style='background-color: #EDEDED;text-align:center'>Client ID</td><td text-align:center'>".$frm_details['ClientRefNumber']."</td></tr>
                        <tr><td style='background-color: #EDEDED;text-align:center'>Mist ID</td><td text-align:center'>Pending</td></tr>
                        <tr><td style='background-color: #EDEDED;text-align:center'>Candidate contact details</td><td text-align:center'>".$frm_details['CandidatesContactNumber']."</td></tr>
                        <tr><td style='background-color: #EDEDED;text-align:center'>Status</td><td text-align:center'>Initiated</td></tr>
                        <tr><td style='background-color: #EDEDED;text-align:center'>Attachment</td><td text-align:center'><a href='http://3.109.13.110/mist-it/uploads/task_file/".$attached_file."' target='_blank'>".$attached_file."</a></td></tr>";

                        $message .= "</table>";

                        $message .= "<br><br><p><b>Note :</b> <I> This is an auto generated email. Request you to write back to report any discrepancy.</I></p>";
                        $message .= "<br><br><p><b>Regards,</b></p>";
                        $message .= "<p>BGV Team</p>";

                        $email_tmpl_data['from_emails'] = $client_manager_email[0]['email'];

                        $email_tmpl_data['to_emails'] = $this->client_info['email_id'];

                        $email_tmpl_data['message'] = $message;
  
                        $result_email = $this->email->client_add_cases_and_pre_post($email_tmpl_data);
                }

                $json_array['status'] = SUCCESS_CODE;

                $json_array['message'] = 'Info Updated Successfully';
            }
            echo_json($json_array);
        } else {
            permission_denied();
        }

    }

    public function frm_edit_pre_post()
    {
        if ($this->input->is_ajax_request()) {
        
            $this->form_validation->set_rules('caserecddate_post', 'Case Received Date', 'required');

            if ($this->form_validation->run() == false) {

                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');

            } else {

                $frm_details = $this->input->post();

                $client_ref_number = str_replace(" ", "", $frm_details['ClientRefNumber']);

                $fields = array('client_id' => $frm_details['selected_client'],
                    'total_cases' => 1,
                    'status' => 'wip',
                    'created_by' => 1,
                    'created_on' => date(DB_DATE_FORMAT),
                    'type' => "new case",
                    'remarks' =>  $frm_details['remarks_post'],
                    'task_person_id' => $this->candidate_upload[0]['user_upload']
                );
      
                $result_task = $this->candidates_model->save_task_manager('client_new_cases',$fields);

                if($result_task)
                {
                    $pre_post_details = array('task_manager_id_post' => $result_task,
                        'post_initiation_date' => convert_display_to_db_date($frm_details['caserecddate_post']),
                        'remarks_post' =>  $frm_details['remarks_post'],
                        'status_post' => 'Initiation',
                        'created_on' => date(DB_DATE_FORMAT)
                     );
                     
                     $result_pre_post = $this->candidates_model->save_task_manager('pre_post_details',$pre_post_details,array('id'=> $frm_details['update_id']));
                    
                     $file_upload_path = SITE_BASE_PATH . "uploads/task_file/";

                     $attached_file = '';

                     if (!folder_exist($file_upload_path)) {
                         mkdir($file_upload_path, 0777);
                     } else if (!is_writable($file_upload_path)) {
                         array_push($error_msgs, 'Problem while uploading');
                     }
 
                     $config_array = array('file_upload_path' => $file_upload_path, 
                                           'file_permission' => '*',
                                           'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 20000, 
                                           'file_id' => $result_task,
                                           'component_name' => 'client_new_case_id');
 
                    if ($_FILES['attchments_post']['name'][0] != '') {
                         $config_array['files_count'] = count($_FILES['attchments_post']['name']);
                         $config_array['file_data'] = $_FILES['attchments_post'];
                         $config_array['status'] = 1;
                         $retunr_de = $this->file_upload_multiple_task($config_array);
                         if (!empty($retunr_de)) {    

                             $this->common_model->common_insert_batch('client_new_case_file', $retunr_de['success']);
                        }

                        $attached_file = $retunr_de['success'][0]['file_name'];

                    }

                    
                    $client_name = $this->candidates_model->select_detials('clients',array('clientname'),array('clients.id'=> $frm_details['selected_client']));
                    $entity_name = $this->candidates_model->select_detials('entity_package',array('entity_package_name'),array('entity_package.id'=> $frm_details['selected_entity']));
                    $package_name = $this->candidates_model->select_detials('entity_package',array('entity_package_name'),array('entity_package.id'=> $frm_details['selected_package']));
                    $client_manager_id = $this->candidates_model->select_detials('clients',array('clientmgr'),array('clients.id'=> $frm_details['selected_client']));
                    $client_manager_email = $this->candidates_model->select_detials('user_profile',array('email'),array('user_profile.id'=> $client_manager_id[0]['clientmgr']));

         
                    $email_tmpl_data['subject'] = 'Post BGV request for '.ucwords($frm_details['CandidateName']). ' received_'.date("d-M-Y H:i");
                    $message = "<p>Greetings from Mist IT Services Pvt Ltd!</p>";
                    $message .= "<p>We have received the following request!</p>";
                        
                    $message .= "<table border = '2'  style='border-spacing: 10; border-collapse: collapse;'>
                        <tr><td style='background-color: #EDEDED;text-align:center'>Client Details</td><td text-align:center'>".$client_name[0]['clientname']." | " .$entity_name[0]['entity_package_name']." | ".$package_name[0]['entity_package_name']."</td></tr>
                        <tr><td style='background-color: #EDEDED;text-align:center'>Candidate Name</td><td text-align:center'>".ucwords($frm_details['CandidateName'])."</td></tr>
                        <tr><td style='background-color: #EDEDED;text-align:center'>Initiation Type</td><td text-align:center'> Post BGV </td></tr>
                        <tr><td style='background-color: #EDEDED;text-align:center'>Received date</td><td text-align:center'>".$frm_details['caserecddate_post']."</td></tr>
                        <tr><td style='background-color: #EDEDED;text-align:center'>Client ID</td><td text-align:center'>".$frm_details['ClientRefNumber']."</td></tr>
                        <tr><td style='background-color: #EDEDED;text-align:center'>Mist ID</td><td text-align:center'>".$frm_details['mist_id']."</td></tr>
                        <tr><td style='background-color: #EDEDED;text-align:center'>Candidate contact details</td><td text-align:center'>".$frm_details['CandidatesContactNumber']."</td></tr>
                        <tr><td style='background-color: #EDEDED;text-align:center'>Status</td><td text-align:center'>Initiated</td></tr>
                        <tr><td style='background-color: #EDEDED;text-align:center'>Attachment</td><td text-align:center'><a href='http://3.109.13.110/mist-it/uploads/task_file/".$attached_file."' target='_blank'>".$attached_file."</a></td></tr>";

                        $message .= "</table>";

                        $message .= "<br><br><p><b>Note :</b> <I> This is an auto generated email. Request you to write back to report any discrepancy.</I></p>";
                        $message .= "<br><br><p><b>Regards,</b></p>";
                        $message .= "<p>BGV Team</p>";

                        $email_tmpl_data['from_emails'] = $client_manager_email[0]['email'];

                        $email_tmpl_data['to_emails'] = $this->client_info['email_id'];

                        $email_tmpl_data['message'] = $message;
  
                        $result_email = $this->email->client_add_cases_and_pre_post($email_tmpl_data);
                }

                $json_array['status'] = SUCCESS_CODE;

                $json_array['message'] = 'Info Updated Successfully';
            }
            echo_json($json_array);
        } else {
            permission_denied();
        }

    }


    public function candidates_update()
    {
        if ($this->input->is_ajax_request()) {

            $this->form_validation->set_rules('update_id', 'ID', 'required');

            $this->form_validation->set_rules('entity', 'Entity', 'required');

            $this->form_validation->set_rules('package', 'Package', 'required');

            $this->form_validation->set_rules('clientid', 'Client', 'required');

            $this->form_validation->set_rules('caserecddate', 'Case received', 'required');

            $this->form_validation->set_rules('ClientRefNumber', 'Client Ref Number', 'required');

            $this->form_validation->set_rules('CandidateName', 'Candidate Name', 'required');

            $this->form_validation->set_rules('CandidatesContactNumber', 'Primary Cantact', 'required');

            if ($this->form_validation->run() == false) {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');
            } else {
                $frm_details = $this->input->post();

                $client_ref_number = str_replace(" ", "", $frm_details['ClientRefNumber']);

                $id = decrypt($frm_details['update_id']);

                if ($id == $frm_details['cp_update_id']) {
                    //$file_upload_path = SITE_BASE_PATH.UPLOAD_FOLDER.CANDIDATES.$frm_details['clientid'];
                    $file_upload_path = SITE_BASE_PATH . CANDIDATES . $frm_details['selected_client'];

                    if (!folder_exist($file_upload_path)) {
                        mkdir($file_upload_path, 0777);
                    }

                    $config_array = array('file_upload_path' => $file_upload_path, 'file_permission' => 'jpeg|jpg|png|pdf', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 20000, 'file_id' => $id, 'component_name' => 'candidate_id');

                    if ($_FILES['attchments']['name'][0] != '') {
                        $config_array['files_count'] = count($_FILES['attchments']['name']);
                        $config_array['file_data'] = $_FILES['attchments'];
                        $config_array['type'] = 0;
                        $retunr_de = $this->file_upload_multiple($config_array);
                        if (!empty($retunr_de)) {
                            $this->common_model->common_insert_batch('candidate_files', $retunr_de['success']);
                        }
                    }

                    $candidate_details = $this->candidates_model->select(true, array(), array('id' => $id));

                    $field_array = array('clientid' => $frm_details['selected_client'],
                        'entity' => $frm_details['selected_entity'],
                        'package' => $frm_details['selected_package'],
                        'caserecddate' => convert_display_to_db_date($frm_details['caserecddate']),
                        'ClientRefNumber' => $client_ref_number,
                        'CandidateName' => $frm_details['CandidateName'],
                        'DateofBirth' => convert_display_to_db_date($frm_details['DateofBirth']),
                        "gender" => $frm_details['gender'],
                        "grade" => $frm_details['grade'],
                        //'email_candidate' => $frm_details['email_candidate'],
                        "prasent_address" => $frm_details['prasent_address'],
                        "cands_city" => $frm_details['cands_city'],
                        "cands_state" => $frm_details['cands_state'],
                        "cands_pincode" => $frm_details['cands_pincode'],
                        "cands_country" => 'India',
                        'NameofCandidateFather' => $frm_details['NameofCandidateFather'],
                        'MothersName' => $frm_details['MothersName'],
                        'CandidatesContactNumber' => $frm_details['CandidatesContactNumber'],
                        'ContactNo1' => $frm_details['ContactNo1'],
                        'ContactNo2' => $frm_details['ContactNo2'],
                        'DateofJoining' => convert_display_to_db_date($frm_details['DateofJoining']),
                        'Location' => $frm_details['Location'],
                        'DesignationJoinedas' => $frm_details['DesignationJoinedas'],
                        'Department' => $frm_details['Department'],
                        'EmployeeCode' => $frm_details['EmployeeCode'],
                        'PANNumber' => $frm_details['PANNumber'],
                        'AadharNumber' => $frm_details['AadharNumber'],
                        'PassportNumber' => $frm_details['PassportNumber'],
                        'cands_email_id' => $frm_details['cands_email_id'],
                        //'BatchNumber' => $frm_details['BatchNumber'],
                        'branch_name' => $frm_details['branch_name'],
                        'remarks' => $frm_details['remarks'],
                        //'region'       => $frm_details['region'],
                        'modified_on' => date(DB_DATE_FORMAT),
                        'modified_by' => $this->client_info['id'],
                        'status' => 1,
                    );
                    $field_array = array_map('strtolower', $field_array);
                 
                   
                    $result = $this->candidates_model->save($field_array, array('cands_info_id' => $id));

                    if ($result) {

                        $result_main_table = $this->candidates_model->save_actual_table($field_array,array('id' => $id));


                        $field_array_log = array('clientid' => $frm_details['selected_client'],
                            'entity' => $frm_details['selected_entity'],
                            'package' => $frm_details['selected_package'],
                            'caserecddate' => convert_display_to_db_date($frm_details['caserecddate']),
                            'ClientRefNumber' => $frm_details['ClientRefNumber'],

                            'CandidateName' => $frm_details['CandidateName'],
                            'DateofBirth' => convert_display_to_db_date($frm_details['DateofBirth']),
                            "gender" => $frm_details['gender'],
                            "prasent_address" => $frm_details['prasent_address'],
                            "cands_city" => $frm_details['cands_city'],
                            "cands_state" => $frm_details['cands_state'],
                            // 'email_candidate' => $frm_details['email_candidate'],
                            "cands_pincode" => $frm_details['cands_pincode'],
                            "cands_country" => 'India',
                            'NameofCandidateFather' => $frm_details['NameofCandidateFather'],
                            'MothersName' => $frm_details['MothersName'],
                            'CandidatesContactNumber' => $frm_details['CandidatesContactNumber'],
                            'ContactNo1' => $frm_details['ContactNo1'],
                            'ContactNo2' => $frm_details['ContactNo2'],
                            'DateofJoining' => convert_display_to_db_date($frm_details['DateofJoining']),
                            'Location' => $frm_details['Location'],
                            'DesignationJoinedas' => $frm_details['DesignationJoinedas'],
                            'Department' => $frm_details['Department'],
                            'EmployeeCode' => $frm_details['EmployeeCode'],
                            'PANNumber' => $frm_details['PANNumber'],
                            'AadharNumber' => $frm_details['AadharNumber'],
                            'PassportNumber' => $frm_details['PassportNumber'],
                            'cands_email_id' => $frm_details['cands_email_id'],
                            // 'BatchNumber' => $frm_details['BatchNumber'],
                            'branch_name' => $frm_details['branch_name'],
                            'remarks' => $frm_details['remarks'],

                            //'region'       => $frm_details['region'],
                            'created_on' => date(DB_DATE_FORMAT),
                            //'created_by'  => $this->user_info['id'],
                            'status' => 1,
                            'candidates_info_id' => $id,
                        );

                        $result_candidate = $this->candidates_model->save_candidate($field_array_log);

                        $result_candidate = $this->candidates_model->save_candidate_log_main($field_array_log);

                    

                        $json_array['status'] = SUCCESS_CODE;

                        $json_array['message'] = 'Info Updated Successfully';

                        $json_array['redirect'] = ADMIN_SITE_URL . 'candidates';
                    } else {
                        $json_array['status'] = ERROR_CODE;

                        $json_array['message'] = 'Database error, please try again';
                    }
                } else {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Something went wrong, please try again';

                    $json_array['redirect'] = ADMIN_SITE_URL . 'candidates';
                }
            }
            echo_json($json_array);
        } else {
            permission_denied();
        }
    }

    public function data_table_cands_view_for_client()
    {
        if ($this->input->is_ajax_request()) {
            $params = $data_arry = $columns = array();

            $params = $_REQUEST;

            $columns = array('id', 'CandidateName', "clientname", "caserecddate", 'ClientRefNumber', 'cmp_ref_no', 'overallstatus', 'remarks', 'view', 'encry_id');

            $cands_results = $this->candidates_model->get_all_cand_with_search($this->user_role, $params, $columns);

            $totalRecords = count($this->candidates_model->get_all_cand_with_search_count($this->user_role, $params, $columns));
            $x = 0;

            foreach ($cands_results as $key => $cands_result) {
                $data_arry[$x]['id'] = $x + 1;
                $data_arry[$x]['CandidateName'] = ucwords(strtolower($cands_result['CandidateName']));
                $data_arry[$x]['ClientRefNumber'] = $cands_result['ClientRefNumber'];
                $data_arry[$x]['cmp_ref_no'] = $cands_result['cmp_ref_no'];
                $data_arry[$x]['overallstatus'] = $cands_result['overallstatus'];
                $data_arry[$x]['overallclosuredate'] = convert_db_to_display_date($cands_result['overallclosuredate']);
                $data_arry[$x]['remarks'] = $cands_result['remarks'];
                $data_arry[$x]['clientname'] = ucwords(strtolower($cands_result['clientname']));
                $data_arry[$x]['caserecddate'] = convert_db_to_display_date($cands_result['caserecddate']);
                $data_arry[$x]['view'] = "<a href='#' title='View' class='view_details' data-raw_id='" . $cands_result['id'] . "'><i class='fa fa-eye'></i></a>";
                $data_arry[$x]['encry_id'] = "<a target='__blank' class='status_alert' data-overallstatus='" . $cands_result['overallstatus'] . "' href='javascript:void(0)' data-href='" . CLIENT_SITE_URL . "candidates/report/" . encrypt($cands_result['id']) . "' title='Report'><i class='fa fa-file-pdf-o'></i></a>";
                $data_arry[$x]['addrver'] = 'NA';
                $data_arry[$x]['eduver'] = 'NA';
                $data_arry[$x]['empver'] = 'NA';
                $data_arry[$x]['crimver'] = 'NA';
                $data_arry[$x]['courtver'] = 'NA';
                $data_arry[$x]['globdbver'] = 'NA';

                if ($cands_result['addrver'] == 1) {
                    $result = $this->candidates_model->get_addres_ver_status(array('cands.id' => $cands_result['id']));
                    if (!empty($result)) {
                        $data_arry[$x]['addrver'] = ($result[0]['verfstatus'] != "") ? $result[0]['verfstatus'] : 'WIP';
                    }
                }

                if ($cands_result['eduver'] == 1) {
                    $result = $this->candidates_model->get_education_ver_status(array('cands.id' => $cands_result['id']));
                    if (!empty($result)) {
                        $data_arry[$x]['eduver'] = ($result[0]['verfstatus'] != "") ? $result[0]['verfstatus'] : 'WIP';
                    }
                }

                if ($cands_result['empver'] == 1) {
                    $result = $this->candidates_model->get_employment_ver_status(array('cands.id' => $cands_result['id']));
                    if (!empty($result)) {
                        $data_arry[$x]['empver'] = ($result[0]['verfstatus'] != "") ? $result[0]['verfstatus'] : 'WIP';
                    }
                }

                if ($cands_result['courtver'] == 1) {
                    $result = $this->candidates_model->get_court_ver_status(array('cands.id' => $cands_result['id']));
                    if (!empty($result)) {
                        $data_arry[$x]['courtver'] = ($result[0]['verfstatus'] != "") ? $result[0]['verfstatus'] : 'WIP';
                    }
                }

                if ($cands_result['crimver'] == 1) {
                    $result = $this->candidates_model->get_pcc_ver_status(array('cands.id' => $cands_result['id']));
                    if (!empty($result)) {
                        $data_arry[$x]['crimver'] = ($result[0]['verfstatus'] != "") ? $result[0]['verfstatus'] : 'WIP';
                    }
                }

                if ($cands_result['globdbver'] == 1) {
                    $result = $this->candidates_model->get_global_db_ver_status(array('cands.id' => $cands_result['id']));

                    if (!empty($result)) {
                        $data_arry[$x]['globdbver'] = ($result[0]['verfstatus'] != "") ? $result[0]['verfstatus'] : 'WIP';
                    }
                }
                $x++;
            }

            $json_data = array(
                "draw" => intval($params['draw']),
                "recordsTotal" => intval($totalRecords),
                "recordsFiltered" => intval($totalRecords),
                "data" => $data_arry,
            );

            $this->echo_json($json_data);
        }
    }


    public function view_details($cand_id = '')
    {

        if (!empty($cand_id)) {
            $candidate_details = $this->candidates_model->select(true, array(), array('cands_info_id' => decrypt($cand_id)));

            if (!empty($candidate_details)) {

                $data['check_component'] = $this->candidates_model->check_candidate_comp($candidate_details['clientid'],$candidate_details['entity'],$candidate_details['package']);

                $data['attachments'] = $this->candidates_model->select_file(array('id', 'file_name', 'real_filename', 'type'), array('candidate_id' => $candidate_details['cands_info_id'], 'status' => 1)); 
                
                $data['comp'] = $this->candidates_model->select_component_details($cand_id); 
                 
                $data['comp'] = $data['comp'][0];

                $this->load->model('clients_model');

                $data['candidate_details'] = $candidate_details;

                $data['header_title'] = 'Candidate Details Edit';

                $data['status'] = status_frm_db();

                $data['states'] = $this->get_states();

                $data['company_list'] = $this->get_company_list();
                $data['university_name'] = $this->get_university_list();
                $data['qualification_name'] = $this->get_qualification_list();

                $data['clients'] = $this->get_clients();

                $data['client_components'] = $this->clients_model->get_entitypackages(array('entity' => $candidate_details['entity'], 'tbl_clients_id' => $candidate_details['clientid'], 'package' => $candidate_details['package']))[0];

               
                $this->load->view('client/header',$data);

                $this->load->view('client/candidates_edit');

                $this->load->view('client/footer');

                // echo $this->load->view('client/candidates_details_edit',$data,TRUE);
            } else {
                show_404();
            }
        } else {
            $this->index();
        }

    }

   
    public function ajax_tab_data($candsid = '', $tab = "")
    {
        if ($this->input->is_ajax_request()) {
            $data['cand_id'] = $candsid;
            $client_deatils = $this->candidates_model->get_client_deatils($candsid);
            switch ($tab) {
                case "addrver":
                    $this->load->model('addressver_model');

                    $data['address_lists'] = $this->addressver_model->get_address_details1($candsid);
                    $data['component_check'] = $client_deatils[0]['candidate_component_count'];

                    echo $this->load->view('client/address_ajax_tab_list', $data, true);
                    break;
                case "courtver":
                    $this->load->model('court_verificatiion_model');

                    $data['court_list'] = $this->court_verificatiion_model->get_all_court_record(array('candidates_info.id' => $candsid));
                    $data['component_check'] = $client_deatils[0]['candidate_component_count'];

                    echo $this->load->view('client/court_ajax_tab_list', $data, true);
                    break;

                case "cbrver":
                    $this->load->model('credit_report_model');

                    $data['credit_report_lists'] = $this->credit_report_model->get_all_credit_report_record(array('candidates_info.id' => $candsid));
                    $data['component_check'] = $client_deatils[0]['candidate_component_count'];

                    echo $this->load->view('client/credit_report_ajax_tab_list', $data, true);
                    break;

                case "eduver":
                    $this->load->model('education_model');

                    $data['education_lists'] = $this->education_model->get_all_education_record(array('candidates_info.id' => $candsid));
                    $data['component_check'] = $client_deatils[0]['candidate_component_count'];

                    echo $this->load->view('client/education_ajax_tab_list', $data, true);
                    break;
                case "empver":
                    $this->load->model('employment_model');

                    $data['employment_lists'] = $this->employment_model->get_emp_list_view(array('candidates_info.id' => $candsid));
                    $data['component_check'] = $client_deatils[0]['candidate_component_count'];

                    echo $this->load->view('client/employment_ajax_tab_list', $data, true);
                    break;
                case "globdbver":
                    $this->load->model('global_database_model');

                    $data['global_cand_lists'] = $this->global_database_model->get_all_global_record(array('candidates_info.id' => $candsid));
                    $data['component_check'] = $client_deatils[0]['candidate_component_count'];

                    echo $this->load->view('client/global_ajax_tab_list', $data, true);
                    break;
                case "identity":
                    $this->load->model('identity_model');

                    $data['identity_lists'] = $this->identity_model->get_all_identity_record(array('candidates_info.id' => $candsid));
                    $data['component_check'] = $client_deatils[0]['candidate_component_count'];

                    echo $this->load->view('client/identity_ajax_tab_list', $data, true);
                    break;
                case "crimver":
                    $this->load->model('pcc_verificatiion_model');

                    $data['crimver_lists'] = $this->pcc_verificatiion_model->get_all_pcc_record(array('candidates_info.id' => $candsid));
                    $data['component_check'] = $client_deatils[0]['candidate_component_count'];

                    echo $this->load->view('client/pcc_ajax_tab_list', $data, true);
                    break;
                case "narcver":
                    $this->load->model('drug_verificatiion_model');

                    $data['drug_lists'] = $this->drug_verificatiion_model->get_all_drug_record(array('candidates_info.id' => $candsid));
                    $data['component_check'] = $client_deatils[0]['candidate_component_count'];

                    echo $this->load->view('client/drug_ajax_tab', $data, true);
                    break;
                case "refver":
                    $this->load->model('reference_verificatiion_model');

                    $data['reference_lists'] = $this->reference_verificatiion_model->get_all_reference_record(array('candidates_info.id' => $candsid));
                    $data['component_check'] = $client_deatils[0]['candidate_component_count'];

                    echo $this->load->view('client/references_ajax_tab_list', $data, true);
                    break;
                default:
                    echo "No components found";
            }
        }
    }

    public function ajax_tab_data_view($candsid = '', $tab = "") // tat cases showing

    {
        if ($this->input->is_ajax_request()) {
            $data['tab_change'] = $tab;
            $data['client_id'] = $client_id;

            switch ($tab) {
                case "addrver":
                    $this->load->model('client/addressver_model');
                    $data['address_lists'] = $this->addressver_model->get_all_addrs_by_client_view($client_id);
                    $data['component'] = 'Address Component Cases';
                    echo $this->load->view('client/address_ajax_tab_view', $data, true);
                    break;
                case "courtver":
                    $this->load->model('client/court_verificatiion_model');
                    $data['court_lists'] = $this->court_verificatiion_model->get_all_court_client_view(array('candidates_info.id' => $client_id));
                    $data['component'] = 'Court Component Cases';
                    echo $this->load->view('client/court_ajax_tab_view', $data, true);
                    break;
                case "cbrver":
                    $this->load->model('client/credit_report_model');
                    $data['credit_report_lists'] = $this->credit_report_model->get_credit_report_data_view(array('candidates_info.id' => $client_id));
                    $data['component'] = 'Credit Report Component Cases';
                    echo $this->load->view('client/credit_report_ajax_tab_view', $data, true);
                    break;
                case "narcver":
                    $this->load->model('client/drugs_model');
                    $data['drugs_lists'] = $this->drugs_model->get_drugs_data_view(array('candidates_info.id' => $client_id));
                    $data['component'] = 'Drugs Component Cases';
                    echo $this->load->view('client/drugs_ajax_tab_view', $data, true);
                    break;
                case "eduver":
                    $this->load->model('client/education_model');
                    $data['education_lists'] = $this->education_model->cand_education_details_view($client_id);
                    $data['component'] = 'Education Component Cases';
                    echo $this->load->view('client/education_ajax_tab_view', $data, true);
                    break;
                case "empver":
                    $this->load->model('client/employment_model');
                    $data['employment_lists'] = $this->employment_model->cand_employment_details_view($client_id);
                    $data['component'] = 'Employment Component Cases';
                    echo $this->load->view('client/employment_ajax_tab_view', $data, true);
                    break;
                case "globdbver":
                    $this->load->model('client/global_database_model');
                    $data['global_database_lists'] = $this->global_database_model->cand_global_data_view(array('candidates_info.id' => $client_id));
                    $data['component'] = 'Global Component Cases';
                    echo $this->load->view('client/global_ajax_tab_view', $data, true);

                    break;
                case "crimver":
                    $this->load->model('client/pcc_model');
                    $data['pcc_lists'] = $this->pcc_model->cand_pcc_details_view($client_id);
                    $data['component'] = 'PCC Component Cases';
                    echo $this->load->view('client/pcc_ajax_tab_view', $data, true);
                    break;
                case "refver":
                    $this->load->model('client/references_model');
                    $data['reference_lists'] = $this->references_model->cand_reference_details_view(array('candidates_info.id' => $client_id));
                    $data['component'] = 'Reference Cases';
                    echo $this->load->view('client/reference_ajax_tab_view', $data, true);
                    break;

                case "identity":
                    $this->load->model('client/identity_model');
                    $data['identity_details'] = $this->identity_model->cand_identity_details_view(array('identity.candsid' => $client_id));
                    $data['component'] = 'Identity Cases';
                    echo $this->load->view('client/identity_ajax_tab_view', $data, true);
                    break;

                default:
                    echo " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Working";
            }
        }
    }

    public function all_component_details($tab = "", $id, $comp = '') // candidate info and there component cases

    {
        if ($this->input->is_ajax_request()) {
            switch ($tab) {
                case "addrver":
                    $this->load->model('client/addressver_model');
                    $data['address_details'] = $this->addressver_model->cand_address_details($id, $comp);
                    //  print_r($data['address_details']);
                    echo $this->load->view('client/address_details_ajax', $data, true);
                    break;
                case "courtver":
                    $this->load->model('client/court_verificatiion_model');
                    $data['court_details'] = $this->court_verificatiion_model->get_data(array('candidates_info.id' => $id), $comp);
                    echo $this->load->view('client/court_details_ajax', $data, true);
                    break;
                case "cbrver":
                    $this->load->model('client/credit_report_model');
                    $data['credit_report_details'] = $this->credit_report_model->get_credit_report_data(array('candidates_info.id' => $id), $comp);

                    echo $this->load->view('client/credit_report_details_ajax', $data, true);
                    break;
                case "narcver":
                    $this->load->model('client/drugs_model');
                    $data['drugs_details'] = $this->drugs_model->get_drugs_data(array('candidates_info.id' => $id), $comp);
                    echo $this->load->view('client/drugs_details_ajax', $data, true);
                    break;
                case "eduver":
                    $this->load->model('client/education_model');
                    $data['education_details'] = $this->education_model->cand_education_details($id, $comp);
                    echo $this->load->view('client/education_details_ajax', $data, true);
                    break;
                case "empver":
                    $this->load->model('client/employment_model');
                    $data['employment_details'] = $this->employment_model->cand_employment_details($id, $comp);
                    echo $this->load->view('client/employment_details_ajax', $data, true);
                    break;
                case "globdbver":
                    $this->load->model('client/global_database_model');
                    $data['global_db_details'] = $this->global_database_model->cand_global_data(array('candidates_info.id' => $id), $comp);
                    echo $this->load->view('client/global_db_details_ajax', $data, true);
                    break;
                case "crimver":
                    $this->load->model('client/pcc_model');
                    $data['pcc_details'] = $this->pcc_model->cand_pcc_details($id, $comp);
                    echo $this->load->view('client/pcc_details_ajax', $data, true);
                    break;
                case "refver":
                    $this->load->model('client/references_model');
                    $data['reference_details'] = $this->references_model->cand_reference_details(array('reference.candsid' => $id), $comp);
                    echo $this->load->view('client/reference_details_ajax', $data, true);
                    break;
                case "identity":
                    $this->load->model('client/identity_model');
                    $data['identity_details'] = $this->identity_model->cand_identity_details(array('identity.candsid' => $id), $comp);
                    echo $this->load->view('client/identity_details_ajax', $data, true);
                    break;

                default:
                    echo "No components found";
            }
        }
    }

    protected function tat_calculate($tat_data)
    {
        $this->load->library('tat_calculation');

        return $this->tat_calculation->tat_calculate($tat_data);
    }

    public function status_count()
    {
        $json_array = array();

        $result = '';

        if ($this->input->is_ajax_request()) {

            $result = $this->candidates_model->status_count($this->client_info['client_id'], explode(',', $this->client_info['client_entity_access']));

            $json_array['status'] = SUCCESS_CODE;

            $json_array['message'] = $result;
        } else {
            $json_array['status'] = ERROR_CODE;

            $json_array['message'] = 'Something went wrong';
        }
        $this->echo_json($json_array);
    }

    public function cands_component_details($tab = "", $client_id = "")
    {
        if ($this->input->is_ajax_request()) {
            $data['component_type'] = $tab;
            $client_id = ($client_id == 0) ? '' : array('clients.id' => $client_id);
            switch ($tab) {
                case "addrver":
                    $this->load->model('addressver_model');
                    $addres_total_count = $this->addressver_model->address_ver_status_count($client_id);
                    $data['component_counts'] = $this->convert_empployment_status($addres_total_count, 'verfstatus', 'count');
                    echo $this->load->view('component_vise_count', $data, true);
                    break;
                case "courtver":
                    $this->load->model('court_verificatiion_model');
                    $court_details = $this->court_verificatiion_model->court_ver_status_count($client_id);
                    $data['component_counts'] = convert_to_single_dimension_array($court_details, 'verfstatus', 'count');
                    echo $this->load->view('component_vise_count', $data, true);
                    break;
                case "cbrver":
                    echo "Your selected cbrver!";
                    break;
                case "narcver":
                    echo "Your selected narcver!";
                    break;
                case "eduver":
                    $this->load->model('education_model');
                    $education_details = $this->education_model->education_ver_status_count($client_id);
                    $data['component_counts'] = convert_to_single_dimension_array($education_details, 'verfstatus', 'count');
                    echo $this->load->view('component_vise_count', $data, true);
                    break;
                case "empver":
                    $this->load->model('employment_model');
                    $employment_details = $this->employment_model->employment_ver_status_count($client_id);
                    $data['component_counts'] = $this->convert_empployment_status($employment_details, 'verfstatus', 'count');
                    echo $this->load->view('component_vise_count', $data, true);
                    break;
                case "globdbver":
                    $this->load->model('global_database_model');
                    $global_db_details = $this->global_database_model->global_db_ver_status_count($client_id);
                    $data['component_counts'] = convert_to_single_dimension_array($global_db_details, 'verfstatus', 'count');
                    echo $this->load->view('component_vise_count', $data, true);
                    break;
                case "crimver":
                    $this->load->model('pcc_model');
                    $pcc_details = $this->pcc_model->pcc_ver_status_count($client_id);
                    $data['component_counts'] = convert_to_single_dimension_array($pcc_details, 'verfstatus', 'count');
                    echo $this->load->view('component_vise_count', $data, true);
                    break;
                case "claim_investigation":
                    $this->load->model('claim_investigation_model');
                    $pcc_details = $this->claim_investigation_model->claim_result_ver_count($client_id);
                    $data['component_counts'] = convert_to_single_dimension_array($pcc_details, 'verfstatus', 'count');
                    echo $this->load->view('component_vise_count_cashless', $data, true);
                    break;
                case "refver":
                    echo "Your selected refver!";
                    break;

                default:
                    echo "No components found";
            }
        }
    }

    public function client_components_data($tab = "", $component_status = 'all', $client_id = "")
    {
        if ($this->input->is_ajax_request()) {
            $client_id = ($client_id == 0) ? '' : array('cands.clientid' => $client_id);
            switch ($tab) {
                case "addrver":
                    $this->load->model('addressver_model');
                    $data['address_lists'] = $this->addressver_model->address_ver_by_status($component_status, $client_id);
                    echo $this->load->view('address_ajax_tab_by_status_client', $data, true);
                    break;
                case "courtver":
                    $this->load->model('court_verificatiion_model');
                    $data['court_list'] = $this->court_verificatiion_model->court_ver_by_status($component_status, $client_id);
                    echo $this->load->view('court_ajax_tab_by_status_client', $data, true);
                    break;
                case "cbrver":
                    echo "Your selected cbrver!";
                    break;
                case "narcver":
                    echo "Your selected narcver!";
                    break;
                case "eduver":
                    $this->load->model('education_model');
                    $data['education_lists'] = $this->education_model->education_ver_by_status($component_status, $client_id);
                    echo $this->load->view('education_ajax_tab_by_status_client', $data, true);
                    break;
                case "empver":
                    $this->load->model('employment_model');
                    $data['employment_lists'] = $this->employment_model->employment_ver_by_status($component_status, $client_id);
                    echo $this->load->view('employment_ajax_tab_by_status_client', $data, true);
                    break;
                case "globdbver":
                    $this->load->model('global_database_model');
                    $data['global_db_lists'] = $this->global_database_model->global_db_ver_by_status($component_status, $client_id);
                    echo $this->load->view('global_db_ajax_tab_by_status_client', $data, true);
                    break;
                case "crimver":
                    $this->load->model('pcc_model');
                    $data['crimver_lists'] = $this->pcc_model->pcc_ver_by_status($component_status, $client_id);
                    echo $this->load->view('pcc_ajax_tab_by_status_client', $data, true);
                    break;
                case "claim_investigation":
                    $this->load->model('claim_investigation_model');
                    $data['claim_lists'] = $this->claim_investigation_model->claim_investigation_ver_by_status($component_status, $client_id);
                    echo $this->load->view('claim_ajax_tab_by_status_client', $data, true);
                    break;
                case "refver":
                    echo "Your selected refver!";
                    break;

                default:
                    echo "No components found";
            }
        }
    }

    public function new_candidates()
    {
        $data['header_title'] = "New Candidates";

        $data['clients'] = $this->get_clients();

        if ($this->input->post()) {
            $this->form_validation->set_rules('clientid', 'Client', 'required');

            $this->form_validation->set_rules('caserecddate', 'Case received', 'required');

            $this->form_validation->set_rules('ClientRefNumber', 'Client Ref Number', 'required|is_unique[cands.ClientRefNumber]');

            $this->form_validation->set_rules('CandidateName', 'Candidate Name', 'required');

            if ($this->form_validation->run() == false) {
                $this->load->view('admin/header', $data);

                $this->load->view('admin/candidates_add');

                $this->load->view('admin/footer');
            } else {
                $frm_details = $this->input->post();

                $fields = array('clientid' => $frm_details['clientid'],
                    'caserecddate' => convert_display_to_db_date($frm_details['caserecddate']),
                    'cmp_ref_no' => $frm_details['cmp_ref_no'],
                    'ClientRefNumber' => $frm_details['ClientRefNumber'],
                    'CandidateName' => ucwords($frm_details['CandidateName']),
                    "gender" => $frm_details['gender'],
                    'DateofBirth' => convert_display_to_db_date($frm_details['DateofBirth']),
                    'NameofCandidateFather' => ucwords($frm_details['NameofCandidateFather']),
                    'MothersName' => ucwords($frm_details['MothersName']),
                    'CandidatesContactNumber' => $frm_details['CandidatesContactNumber'],
                    'ContactNo1' => $frm_details['ContactNo1'],
                    'ContactNo2' => $frm_details['ContactNo2'],
                    'DateofJoining' => convert_display_to_db_date($frm_details['DateofJoining']),
                    'Location' => $frm_details['Location'],
                    'DesignationJoinedas' => $frm_details['DesignationJoinedas'],
                    'Department' => $frm_details['Department'],
                    'EmployeeCode' => $frm_details['EmployeeCode'],
                    'PANNumber' => $frm_details['PANNumber'],
                    'AadharNumber' => $frm_details['AadharNumber'],
                    'PassportNumber' => $frm_details['PassportNumber'],
                    'BatchNumber' => $frm_details['BatchNumber'],
                    'insuffraisedate' => convert_display_to_db_date($frm_details['insuffraisedate']),
                    'insuffcleardate' => convert_display_to_db_date($frm_details['insuffcleardate']),
                    'overallstatus' => $frm_details['overallstatus'],
                    'remarks' => $frm_details['remarks'],
                    'created_by' => 1,
                    'created' => date(DB_DATE_FORMAT),
                );

                $result = $this->candidates_model->save($fields);

                if ($result) {
                    $this->session->set_flashdata('message', array('message' => 'Client Create Successfully', 'class' => 'alert alert-success fade in'));

                    redirect('admin/candidates');
                } else {
                    $this->session->set_flashdata('message', array('message' => 'Something went wrong, please try again', 'class' => 'alert alert-danger fade in'));

                    redirect('admin/candidates');
                }
            }
        } else {
            $this->load->view('admin/header', $data);

            $this->load->view('admin/candidates_add');

            $this->load->view('admin/footer');
        }
    }

    public function report($candsid = null, $report_type)
    {

        if (!empty($candsid)) {

            $id = decrypt($candsid);

            $this->load->model('first_qc_model');

            $this->load->library('example');

            $report = array();

            $cands_result = $this->candidates_model->get_candidates_info_info_report(array('candidates_info.id' => $id));

            $report['address_info'] = array();
            $report['employment_info'] = array();
            $report['education_info'] = array();
            $report['references_info'] = array();
            $report['court_info'] = array();
            $report['global_db_info'] = array();
            $report['pcc_info'] = array();
            $report['identity_info'] = array();
            $report['credit_report_info'] = array();
            $report['drugs_info'] = array();
            $report['social_media_info'] = array();


            $report['report_type'] = $report_type;

            if ($cands_result) {
                $report['cand_info'] = $cands_result;

                $NA_array = array();

                $result = $this->first_qc_model->get_address_final_qc(array('addrverres.candsid' => $id));
                if (!empty($result)) {
                    $report['address_info'] = $result;
                } else {
                    $report['address_info'] = $result;
                    $NA_array[] = array('ADDRESS');
                }
                $result = $this->first_qc_model->get_emp_final_qc(array('empverres.candsid' => $id));
                if (!empty($result)) {
                    $report['employment_info'] = $result;
                } else {
                    $report['employment_info'] = $result;
                    $NA_array[] = array('EMPLOYMENT');
                }
                $result = $this->first_qc_model->get_education_final_qc(array('education_result.candsid' => $id));

                if (!empty($result)) {
                    $report['education_info'] = $result;
                } else {
                    $report['education_info'] = $result;
                    $NA_array[] = array('EDUCATION');
                }
                $result = $this->first_qc_model->get_reference_final_qc(array('reference_result.candsid' => $id));
                if (!empty($result)) {
                    $report['references_info'] = $result;
                } else {
                    $report['references_info'] = $result;
                    $NA_array[] = array('REFERENCES VERIFICATION');
                }

                $result = $this->first_qc_model->get_court_final_qc(array('courtver_result.candsid' => $id));

                if (!empty($result)) {
                    $report['court_info'] = $result;
                } else {
                    $report['court_info'] = $result;
                    $NA_array[] = array('COURT VERIFICATION');
                }
                $result = $this->first_qc_model->get_global_db_final_qc(array('glodbver_result.candsid' => $id));
                if (!empty($result)) {
                    $report['global_db_info'] = $result;
                } else {
                    $report['global_db_info'] = $result;
                    $NA_array[] = array('GLOBAL DATABASE VERIFICATION');
                }

                $result = $this->first_qc_model->get_drug_db_final_qc(array('drug_narcotis_result.candsid' => $id));

                if (!empty($result)) {
                    $report['drugs_info'] = $result;
                } else {
                    $report['drugs_info'] = $result;
                    $NA_array[] = array('DRUGS VERIFICATION');
                }
                $result = $this->first_qc_model->get_pcc_final_qc(array('pcc_result.candsid' => $id));

                if (!empty($result)) {
                    $report['pcc_info'] = $result;
                } else {
                    $report['pcc_info'] = $result;
                    $NA_array[] = array('POLICE VERIFICATION');
                }

                $result = $this->first_qc_model->get_identity_final_qc(array('identity_result.candsid' => $id));

                if (!empty($result)) {
                    $report['identity_info'] = $result;
                } else {
                    $report['identity_info'] = $result;
                    $NA_array[] = array('IDENTITY VERIFICATION');
                }

                $result = $this->first_qc_model->get_credit_report_final_qc(array('credit_report_result.candsid' => $id));

                if (!empty($result)) {
                    $report['credit_report_info'] = $result;
                } else {
                    $report['credit_report_info'] = $result;
                    $NA_array[] = array('CREDIT REPORT VERIFICATION');
                }

                $result = $this->first_qc_model->get_social_media_final_qc(array('social_media_result.candsid' => $id));

                if (!empty($result)) {
                    $report['social_media_info'] = $result;
                } else {
                    $report['social_media_info'] = $result;
                    $NA_array[] = array('SOCIAL MEDIA VERIFICATION');
                }

                $report['NA_COMPONENTS'] = $NA_array;

                $report['status'] = OVERALL_STATUS;

                if ($cands_result['comp_logo'] != "") {
                    $cleit_logo_path = SITE_URL . CLIENT_LOGO . '/' . $cands_result['comp_logo'];
                    define('CLIENT_LOGOS', $cleit_logo_path);
                } else {
                    define('CLIENT_LOGOS', '');
                }

                define('CUSTOM_CLINT_ID',$cands_result['clientid']);


                $this->example->generate_pdf($report, 'client');
            } else {
                show_404();
            }
        } else {
            redirect('admin/candidates');
        }

    }

    private function echo_json($json_array)
    {
        echo_json($json_array);

        exit;
    }

    public function get_clients_by_components($tab)
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {
            $json_array['message'] = $this->get_clients_by_component($tab);
        }

        $this->echo_json($json_array);
    }

    public function report_download()
    {
        $data['header_title'] = "Download Reports";

        $data['clients'] = $this->get_clients();

        $this->load->view('header', $data);

        $this->load->view('download_reports');

        $this->load->view('footer');
    }

    public function report_download_new()
    {
        $data['header_title'] = "Download Reports";

        $data['clients'] = $this->get_clients();

        $file_upload_path = SITE_BASE_PATH . UPLOAD_FOLDER . 'bulk_reports/';
        $file_path = UPLOAD_FOLDER . 'bulk_reports/';

//        $data['list_downloads']['folders'] = $this->listdir_by_date($file_upload_path);

        $this->db->select("folder_name, created_on");
        $this->db->from('report_requested');
        $this->db->where('folder_generated_status !=', 0);
        $this->db->where('folder_name !=', '');
        $this->db->limit(20, 0);
        $this->db->order_by('id', 'desc');

        $result = $this->db->get();
        $final_array = $result->result_array();

        foreach ($final_array as $key => $value) {
            $files_array = $this->listdir_by_date($file_upload_path . $value['folder_name']);
            if (!empty($files_array)) {
                foreach ($files_array as $key_inner => $value_inner) {
                    $data['list_downloads']['files'][$value['folder_name']] = $value['created_on'] . "~" . $value_inner;
                }
            }

        }

        $data['list_downloads']['path'] = $file_path;

        $this->load->view('header', $data);

        $this->load->view('download_reports', $data['list_downloads']);

        $this->load->view('footer');
    }

    public function listdir_by_date($path)
    {
        $dir = opendir($path);
        $list = array();
        while ($file = readdir($dir)) {
            if ($file != '.' and $file != '..') {
//                $ctime = filemtime($path . $file) .":".  $file;
                $ctime = date("d-m-Y H:i:s", @filemtime($path . $file)) . "~" . $file;
                $list[$ctime] = $file;
            }
        }
        closedir($dir);
        krsort($list);
        return $list;
    }

    public function genetare_excel_reprots()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {
            $frm_details = $this->input->post();

            $client_id = $frm_details['clientid'];

            $client_name = $this->session->userdata('clientname');

            if (!empty($frm_details)) {
                $requested_data = array('type' => 'Excel', 'requested_id' => $this->session->userdata('id'), 'query' => '', 'created_on' => date(DB_DATE_FORMAT), 'mail_sent_status' => 0, 'folder_generated_status' => 0, 'downloaded_status' => 0, 'downloaded_on_date' => '', 'folder_name' => '');

                $report_requested_save_id = $this->candidates_model->report_requested_save($requested_data);

                $cands_info = array();

                if ($report_requested_save_id) {
                    $cands_info['clientid'] = $client_id;

                    $cands_info['clinet_name'] = $client_name;

                    $cands_info['first_name'] = $this->session->userdata('first_name');

                    $cands_info['email_id'] = $frm_details['entered_email'];

                    $cands_info['report_requested_save_id'] = $report_requested_save_id;

                    $cands_info['frm_data'] = $frm_details;

                    $parameters_array = serialize($cands_info);

                    $parameters_array = base64_encode($parameters_array);

                    $url = "cli_request_only generate_excel_report $parameters_array";

                    $cmd = 'php C:\xampp\htdocs\client_bgv\index.php ' . $url;

                    $this->candidates_model->report_requested_save(array('query' => $cmd), array('id' => $report_requested_save_id));

                    if (substr(php_uname(), 0, 7) == "Windows") {
                        pclose(popen("start /MIN " . $cmd, "r"));
                    } else {
                        exec($cmd . " > /dev/null &");
                    }

                    $json_array['message'] = "Reports are generating we'll send you mail on " . $cands_info['email_id'] . " email id";

                    $json_array['status'] = SUCCESS_CODE;
                } else {
                    $json_array['message'] = 'Select date range no cases completed';

                    $json_array['status'] = ERROR_CODE;
                }
            } else {
                $json_array['message'] = 'Something went wrong, please try again';

                $json_array['status'] = ERROR_CODE;
            }
        } else {
            $json_array['message'] = "Something went wrong,please try again";

            $json_array['status'] = ERROR_CODE;
        }

        $this->echo_json($json_array);
    }

    /*public function genetare_excel_reprots1()
    {
    $json_array = array();

    if($this->input->is_ajax_request())
    {

    $clientid = $this->input->post('clientid');

    $from_date = $this->input->post('from_date');

    $to_date = $this->input->post('to_date');

    $prod  =  $this->candidates_model->get_excel($clientid,array('from_date' => $from_date,'to_date' => $to_date));

    require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $spreadsheet->getProperties()->setCreator(CRMNAME)
    ->setLastModifiedBy(CRMNAME)
    ->setTitle(CRMNAME)
    ->setSubject('Employment records')
    ->setDescription('Employment records with their status');
    // add style to the header
    $styleArray = array(
    'font' => array(
    'bold' => true,
    ),
    'alignment' => array(
    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    ),
    );
    $spreadsheet->getActiveSheet()->getStyle('A1:T1')->applyFromArray($styleArray);
    // auto fit column to content
    foreach(range('A','T') as $columnID) {
    $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
    ->setWidth(20);
    }
    //$spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(5);
    // set the names of header cells
    $spreadsheet->setActiveSheetIndex(0)
    ->setCellValue("A1",'Agency Name')
    ->setCellValue('B1','Handover Date')
    ->setCellValue("C1",'Employee Code')
    ->setCellValue("D1",'Employee Name')
    ->setCellValue("E1",'Address Status')
    ->setCellValue("F1",'Insuf raise 1 remark')
    ->setCellValue('G1','Insuff raise 1 date')
    ->setCellValue("H1",'Insuf raise 2 remark')
    ->setCellValue('I1','Insuff raise 2 date')
    ->setCellValue('J1','Date of Address check close')
    ->setCellValue('K1','Employment_Status')
    ->setCellValue('L1','Closure remark')
    ->setCellValue('M1','Insuf raise 1 remark')
    ->setCellValue('N1','Insuff raise 1 date')
    ->setCellValue('O1','Insuf raise 2 remark')
    ->setCellValue('P1','Insuff raise 2 date')
    ->setCellValue('Q1','Discrepancy Remark 1(if any)')
    ->setCellValue('R1','Discrepancy Remark2 (if any)')
    ->setCellValue('S1','Date of Employment check close')
    ->setCellValue('T1','Verifiers Email ID');

    $x= 2;

    foreach($prod as $list)
    {
    $spreadsheet->setActiveSheetIndex(0)
    ->setCellValue("A$x","vistar INERNATIONAL")
    ->setCellValue("B$x",$list['clientname'])
    ->setCellValue("C$x",$list['Candidate Name'])
    ->setCellValue("D$x",$list['Initiated Date'])
    ->setCellValue("E$x",$list['tat_expiry_date'])
    ->setCellValue("F$x",$list['Status'])
    ->setCellValue("G$x",$list['TAT'])
    ->setCellValue("H$x",$list['Remark'])
    ->setCellValue("I$x",$list['user_name'])
    ->setCellValue("J$x",$tat_cases['TAT-Component']);
    $x++;
    }
    // Rename worksheet
    $spreadsheet->getActiveSheet()->setTitle('TAT Records');

    $spreadsheet->setActiveSheetIndex(0);

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment;filename= ".$tat_cases['TAT-Component'].".xls");
    header('Cache-Control: max-age=0');
    // If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');

    // If you're serving to IE over SSL, then the following may be needed
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header('Pragma: public'); // HTTP/1.0

    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Excel2007');
    ob_start();
    $writer->save('php://output');
    $xlsData = ob_get_contents();
    ob_end_clean();

    $json_array['file'] = "data:application/vnd.ms-excel;base64,".base64_encode($xlsData);

    $json_array['file_name'] = $tat_cases['TAT-Component'];

    $json_array['message'] = "File downloaded successfully,please check in download folder";

    $json_array['status'] = SUCCESS_CODE;

    }

    $this->echo_json($json_array);
    }
     */
    public function genetare_pdf_reprots()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('month_of_reprots', 'From Date', 'required');

            if ($this->form_validation->run() == false) {
                $json_array['message'] = 'All fields required';

                $json_array['status'] = ERROR_CODE;
            } else {
                $frm_details = $this->input->post();

                if (!empty($frm_details)) {
                    $requested_data = array('type' => 'sales', 'requested_id' => $this->session->userdata('id'), 'query' => '', 'created_on' => date(DB_DATE_FORMAT), 'mail_sent_status' => 0, 'folder_generated_status' => 0, 'downloaded_status' => 0, 'downloaded_on_date' => '', 'folder_name' => '');

                    if ($this->user_role) {
                        $requested_data['type'] = 'client';
                    }

                    $report_requested_save_id = $this->candidates_model->report_requested_save($requested_data);

                    $cands_info = array();

                    if ($report_requested_save_id) {
                        $cands_info['clientid'] = $frm_details['clientid'];

                        $cands_info['first_name'] = $this->session->userdata('first_name');

                        $cands_info['email_id'] = $frm_details['entered_email'];

                        $cands_info['report_requested_save_id'] = $report_requested_save_id;

                        $cands_info['frm_data'] = $frm_details;

                        $parameters_array = serialize($cands_info);

                        $parameters_array = base64_encode($parameters_array);

                        $url = "cli_request_only bg_generate_reports $parameters_array";

                        $cmd = 'php C:\xampp\htdocs\client_bgv\index.php ' . $url;

                        $this->candidates_model->report_requested_save(array('query' => $cmd), array('id' => $report_requested_save_id));

                        if (substr(php_uname(), 0, 7) == "Windows") {
                            pclose(popen("start /MIN " . $cmd, "r"));
                        } else {
                            exec($cmd . " > /dev/null &");
                        }

                        $json_array['message'] = "Reports are generating we'll send you mail on " . $cands_info['email_id'] . " email id";

                        $json_array['status'] = SUCCESS_CODE;
                    } else {
                        $json_array['message'] = 'Select date range no cases completed';

                        $json_array['status'] = ERROR_CODE;
                    }
                } else {
                    $json_array['message'] = 'Something went wrong, please try again';

                    $json_array['status'] = ERROR_CODE;
                }
            }
        } else {
            $json_array['message'] = 'Something went wrong, please try again';

            $json_array['status'] = ERROR_CODE;
        }

        $this->echo_json($json_array);
    }

    public function downloading($id, $folder_name)
    {
        $folder_name = decrypt($folder_name);

        $status = $this->candidates_model->report_requested_select(array('id' => $id, 'folder_name' => $folder_name, 'requested_id' => $this->session->userdata('id')));

        if (!empty($status)) {
            $this->db->set('downloaded_status', 'downloaded_status+1', false);

            $this->candidates_model->report_requested_save(array('downloaded_on_date' => date(DB_DATE_FORMAT)), array('id' => $id));

            $path = SITE_BASE_PATH . UPLOAD_FOLDER . 'bulk_reports/' . $folder_name;

            $this->load->library('zip');

            $this->zip->compression_level = 0;

            $this->zip->read_dir($path, false);

            $this->zip->download($folder_name . '.zip');
        } else {
            $ci = get_instance();
            $ci->output->set_status_header(404);
            $data['heading'] = 'Info';
            $data['message'] = 'Report request and download request user are different.';
            $ci->load->view('errors/custom_error', $data);
        }
    }

    protected function convert_empployment_status($multi_dimension_array, $key_from_multi, $value_from_multi)
    {
        $single_dimension_array = array();

        $overall_status_cnt = array('WIP' => 0, 'Insufficiency' => 0, 'Discrepancy' => 0, 'Unable to Verify' => 0, 'Stop/Check' => 0, 'Clear' => 0);

        foreach ($multi_dimension_array as $multi_dimension) {
            $key = $multi_dimension[$key_from_multi];

            $value = $multi_dimension[$value_from_multi];

            if ($key == 'Clear' || $key == "No-Response" || $key == "Inaccessible") {
                $overall_status_cnt['Clear'] = $overall_status_cnt['Clear'] + $value;
            } else if ($key == 'Insufficiency' || $key == 'Insufficiency-Relieving Letter Required' || $key == "Insufficiency I" || $key == "Insufficiency II") {
                $overall_status_cnt['Insufficiency'] = $overall_status_cnt['Insufficiency'] + $value;
            } else if ($key == 'Discrepancy' || $key == 'No Record Found') {
                $overall_status_cnt['Discrepancy'] = $overall_status_cnt['Discrepancy'] + $value;
            } else if ($key == 'Unable to Verify' || $key == 'No Record Found') {
                $overall_status_cnt['Unable to Verify'] = $overall_status_cnt['Unable to Verify'] + $value;
            } else if ($key == 'Stop/Check' || $key == 'Work With the Same Organization') {
                $overall_status_cnt['Stop/Check'] = $overall_status_cnt['Stop/Check'] + $value;
            } else if ($key == 'WIP' || $key == "WIP-Initiated" || $key == "WIP – pending for clarification" || $key == "case initiated") {
                $overall_status_cnt['WIP'] = $overall_status_cnt['WIP'] + $value;
            }

        }
        return $overall_status_cnt;
    }

    public function tat_cases_export()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {
            $tat_cases = $this->input->post('TatLocalStoreage');

            $tat_cases = base64_decode($tat_cases);

            $tat_cases = unserialize($tat_cases);

            $this->load->library('tat_calculation');

            $prod = $this->tat_calculation->tat_cases_separete($tat_cases);

            require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
            // Create new Spreadsheet object
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            // Set document properties
            $spreadsheet->getProperties()->setCreator(CRMNAME)
                ->setLastModifiedBy(CRMNAME)
                ->setTitle(CRMNAME)
                ->setSubject('Employment records')
                ->setDescription('Employment records with their status');
            // add style to the header
            $styleArray = array(
                'font' => array(
                    'bold' => true,
                ),
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ),
            );
            $spreadsheet->getActiveSheet()->getStyle('A1:I1')->applyFromArray($styleArray);
            // auto fit column to content
            foreach (range('A', 'I') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                    ->setWidth(20);
            }
            //$spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(5);
            // set the names of header cells
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A1", 'Client Ref No.')
                ->setCellValue('B1', 'Client Name')
                ->setCellValue("C1", 'Candidate Name')
                ->setCellValue("D1", 'Initiated Date')
                ->setCellValue("E1", 'TAT Due Date')
                ->setCellValue("F1", 'Status')
                ->setCellValue('G1', 'Type')
                ->setCellValue("H1", 'Remark')
                ->setCellValue('I1', 'User Name')
                ->setCellValue('J1', 'Component');
            $x = 2;

            foreach ($prod as $list) {
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A$x", $list['Ref. Number'])
                    ->setCellValue("B$x", $list['clientname'])
                    ->setCellValue("C$x", $list['Candidate Name'])
                    ->setCellValue("D$x", $list['Initiated Date'])
                    ->setCellValue("E$x", $list['tat_expiry_date'])
                    ->setCellValue("F$x", $list['Status'])
                    ->setCellValue("G$x", $list['TAT'])
                    ->setCellValue("H$x", $list['Remark'])
                    ->setCellValue("I$x", $list['user_name'])
                    ->setCellValue("J$x", $tat_cases['TAT-Component']);
                $x++;
            }
            // Rename worksheet
            $spreadsheet->getActiveSheet()->setTitle('TAT Records');

            $spreadsheet->setActiveSheetIndex(0);

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment;filename= " . $tat_cases['TAT-Component'] . ".xls");
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Excel2007');
            ob_start();
            $writer->save('php://output');
            $xlsData = ob_get_contents();
            ob_end_clean();

            $json_array['file'] = "data:application/vnd.ms-excel;base64," . base64_encode($xlsData);

            $json_array['file_name'] = $tat_cases['TAT-Component'];

            $json_array['message'] = "File downloaded successfully,please check in download folder";

            $json_array['status'] = SUCCESS_CODE;
        }

        $this->echo_json($json_array);
    }

    public function tat_cases_export_all_comp()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {
            $this->load->model('addressver_model');
            $tat_calculate = $this->addressver_model->get_all_addrs_by_client(false);
            $address_tat = $this->tat_calculate($tat_calculate);
            $address_tat = $this->tat_calculation->tat_cases_separete($address_tat);

            $this->load->model('court_verificatiion_model');
            $tat_calculate = $this->court_verificatiion_model->get_all_court_client(false);
            $court_tat = $this->tat_calculate($tat_calculate);
            $court_tat = $this->tat_calculation->tat_cases_separete($court_tat);

            $this->load->model('education_model');
            $tat_calculate = $this->education_model->get_all_eduver_client(false);
            $education_lists = $this->tat_calculate($tat_calculate);
            $education_lists = $this->tat_calculation->tat_cases_separete($education_lists);

            $this->load->model('employment_model');
            $tat_calculate = $this->employment_model->get_all_emp_client(false);
            $employment_lists = $this->tat_calculate($tat_calculate);
            $employment_lists = $this->tat_calculation->tat_cases_separete($employment_lists);

            $this->load->model('global_database_model');
            $tat_calculate = $this->global_database_model->get_all_global_db_client(false);
            $global_db_lists = $this->tat_calculate($tat_calculate);
            $global_db_lists = $this->tat_calculation->tat_cases_separete($global_db_lists);

            $this->load->model('pcc_model');
            $tat_calculate = $this->pcc_model->get_all_pcc_client(false);
            $pcc_tat = $this->tat_calculate($tat_calculate);
            $pcc_tat = $this->tat_calculation->tat_cases_separete($pcc_tat);

            $this->load->model('references_model');
            $tat_calculate = $this->references_model->cand_reference_details(false);
            $references = $this->tat_calculate($tat_calculate);
            $references = $this->tat_calculation->tat_cases_separete($references);

            require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
            // Create new Spreadsheet object
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            // Set document properties
            $spreadsheet->getProperties()->setCreator(CRMNAME)
                ->setLastModifiedBy(CRMNAME)
                ->setTitle(CRMNAME)
                ->setSubject('Employment records')
                ->setDescription('Employment records with their status');
            // add style to the header
            $styleArray = array(
                'font' => array(
                    'bold' => true,
                ),
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ),
            );
            $spreadsheet->getActiveSheet()->getStyle('A1:J1')->applyFromArray($styleArray);
            // auto fit column to content
            foreach (range('A', 'J') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                    ->setWidth(20);
            }
            // set the names of header cells
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A1", 'Client Ref No.')
                ->setCellValue('B1', 'Client Name')
                ->setCellValue("C1", 'Candidate Name')
                ->setCellValue("D1", 'Initiated Date')
                ->setCellValue("E1", 'TAT Due Date')
                ->setCellValue("F1", 'Status')
                ->setCellValue('G1', 'Type')
                ->setCellValue("H1", 'Remark')
                ->setCellValue('I1', 'User Name')
                ->setCellValue('J1', 'Component');
            $x = 2;

            foreach ($address_tat as $list) {
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A$x", $list['Ref. Number'])
                    ->setCellValue("B$x", $list['clientname'])
                    ->setCellValue("C$x", $list['Candidate Name'])
                    ->setCellValue("D$x", $list['Initiated Date'])
                    ->setCellValue("E$x", $list['tat_expiry_date'])
                    ->setCellValue("F$x", $list['Status'])
                    ->setCellValue("G$x", $list['TAT'])
                    ->setCellValue("H$x", $list['Remark'])
                    ->setCellValue("I$x", $list['user_name'])
                    ->setCellValue("J$x", 'Address');
                $x++;
            }

            foreach ($court_tat as $list) {
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A$x", $list['Ref. Number'])
                    ->setCellValue("B$x", $list['clientname'])
                    ->setCellValue("C$x", $list['Candidate Name'])
                    ->setCellValue("D$x", $list['Initiated Date'])
                    ->setCellValue("E$x", $list['tat_expiry_date'])
                    ->setCellValue("F$x", $list['Status'])
                    ->setCellValue("G$x", $list['TAT'])
                    ->setCellValue("H$x", $list['Remark'])
                    ->setCellValue("I$x", $list['user_name'])
                    ->setCellValue("J$x", 'Court Records');
                $x++;
            }

            foreach ($education_lists as $list) {
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A$x", $list['Ref. Number'])
                    ->setCellValue("B$x", $list['clientname'])
                    ->setCellValue("C$x", $list['Candidate Name'])
                    ->setCellValue("D$x", $list['Initiated Date'])
                    ->setCellValue("E$x", $list['tat_expiry_date'])
                    ->setCellValue("F$x", $list['Status'])
                    ->setCellValue("G$x", $list['TAT'])
                    ->setCellValue("H$x", $list['Remark'])
                    ->setCellValue("I$x", $list['user_name'])
                    ->setCellValue("J$x", 'Education');
                $x++;
            }

            foreach ($employment_lists as $list) {
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A$x", $list['Ref. Number'])
                    ->setCellValue("B$x", $list['clientname'])
                    ->setCellValue("C$x", $list['Candidate Name'])
                    ->setCellValue("D$x", $list['Initiated Date'])
                    ->setCellValue("E$x", $list['tat_expiry_date'])
                    ->setCellValue("F$x", $list['Status'])
                    ->setCellValue("G$x", $list['TAT'])
                    ->setCellValue("H$x", $list['Remark'])
                    ->setCellValue("I$x", $list['user_name'])
                    ->setCellValue("J$x", 'Employment');
                $x++;
            }

            foreach ($global_db_lists as $list) {
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A$x", $list['Ref. Number'])
                    ->setCellValue("B$x", $list['clientname'])
                    ->setCellValue("C$x", $list['Candidate Name'])
                    ->setCellValue("D$x", $list['Initiated Date'])
                    ->setCellValue("E$x", $list['tat_expiry_date'])
                    ->setCellValue("F$x", $list['Status'])
                    ->setCellValue("G$x", $list['TAT'])
                    ->setCellValue("H$x", $list['Remark'])
                    ->setCellValue("I$x", $list['user_name'])
                    ->setCellValue("J$x", 'GLobal DB');
                $x++;
            }

            foreach ($pcc_tat as $list) {
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A$x", $list['Ref. Number'])
                    ->setCellValue("B$x", $list['clientname'])
                    ->setCellValue("C$x", $list['Candidate Name'])
                    ->setCellValue("D$x", $list['Initiated Date'])
                    ->setCellValue("E$x", $list['tat_expiry_date'])
                    ->setCellValue("F$x", $list['Status'])
                    ->setCellValue("G$x", $list['TAT'])
                    ->setCellValue("H$x", $list['Remark'])
                    ->setCellValue("I$x", $list['user_name'])
                    ->setCellValue("J$x", 'PCC Records');
                $x++;
            }

            foreach ($references as $list) {
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A$x", $list['Ref. Number'])
                    ->setCellValue("B$x", $list['clientname'])
                    ->setCellValue("C$x", $list['Candidate Name'])
                    ->setCellValue("D$x", $list['Initiated Date'])
                    ->setCellValue("E$x", $list['tat_expiry_date'])
                    ->setCellValue("F$x", $list['Status'])
                    ->setCellValue("G$x", $list['TAT'])
                    ->setCellValue("H$x", $list['Remark'])
                    ->setCellValue("I$x", $list['user_name'])
                    ->setCellValue("J$x", 'References');
                $x++;
            }
            // Rename worksheet
            $spreadsheet->getActiveSheet()->setTitle('TAT Records');

            $spreadsheet->setActiveSheetIndex(0);

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment;filename= All-Component-TAT-Cases-" . date(DB_DATE_FORMAT) . ".xls");
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Excel2007');
            ob_start();
            $writer->save('php://output');
            $xlsData = ob_get_contents();
            ob_end_clean();

            $json_array['file'] = "data:application/vnd.ms-excel;base64," . base64_encode($xlsData);

            $json_array['file_name'] = "All-Component-TAT-Cases" . date(DB_DATE_FORMAT);

            $json_array['message'] = "File downloaded successfully,please check in download folder";

            $json_array['status'] = SUCCESS_CODE;
        } else {
            $json_array['message'] = "Something went wrong, please try again";

            $json_array['status'] = ERROR_CODE;
        }
        $this->echo_json($json_array);
    }

    public function get_entity_list()
    {
        if ($this->input->is_ajax_request()) {
            $entity_pakage_list = $this->get_entity_package_details(explode(',', $this->client_info['client_entity_access']));
     
            $entity_list1 = array_map('current', $entity_pakage_list);
           
            $entity_list = $this->get_entiry_list_add_candidate(array('tbl_client_id' => $this->input->post('clientid'), 'is_entity_package' => 1), $entity_list1);

            echo form_dropdown('entity', $entity_list, set_value('package', $this->input->post('selected_entityy')), 'class="form-control" id="entity"');
        }
    }

    public function get_package_list()
    {
        if ($this->input->is_ajax_request()) {
            $package_list = $this->get_entiry_package_list1(array('is_entity' => $this->input->post('entity'), 'is_entity_package' => 2));

            echo form_dropdown('package', $package_list, set_value('package', $this->input->post('selected_paclage')), 'class="form-control" id="package"');
        }
    }

    public function get_entity_package_details($entity_package_id)
    {
        $entity_package_id = $this->candidates_model->get_entity_package_details($entity_package_id);

        return $entity_package_id;
    }

    protected function get_entiry_list_add_candidate($tbl_clients_id = array('tbl_clients_id'), $entity_list1)
    {

        $company = $this->candidates_model->select_candidate_entity_package_list('entity_package', false, array('id', 'entity_package_name'), $tbl_clients_id, $entity_list1);

        $entity_arry[0] = 'Select';

        foreach ($company as $key => $value) {
            $entity_arry[$value['id']] = ucwords(strtolower($value['entity_package_name']));
        }
        return $entity_arry;
    }

    public function is_client_ref_exists()
    {
        if ($this->input->is_ajax_request()) {
            $ClientRefNumber1 = $this->input->post('ClientRefNumber');
            $clientid = $this->input->post('clientid');
            $entity_name = $this->input->post('entity_name');
            $package_name = $this->input->post('package_name');
            $ClientRefNumber = str_replace(" ", "", $ClientRefNumber1);

            $lists = $this->candidates_model->select_client_admin(true, array('id'), array('ClientRefNumber' => $ClientRefNumber, 'clientid' => $clientid, 'entity' => $entity_name, 'package' => $package_name, 'status' => '1'));
            if (empty($lists)) {
                echo 'true';
            } else {
                echo 'false';
            }
        }
    }

    public function is_client_ref_exists_pre_post()
    {
        if ($this->input->is_ajax_request()) {
            $ClientRefNumber1 = $this->input->post('ClientRefNumber');
            $clientid = $this->input->post('clientid');
            $ClientRefNumber = str_replace(" ", "", $ClientRefNumber1);

            $lists = $this->candidates_model->select_client_admin(true, array('id'), array('ClientRefNumber' => $ClientRefNumber, 'clientid' => $clientid, 'status' => '1'));
            if (empty($lists)) {
                echo 'true';
            } else {
                echo 'false';
            }
        }
    }

    public function entity_package_view($clientid)
    {

        if ($this->input->is_ajax_request()) {
            if (!empty($clientid)) {
                $data['candidate_details'] = $this->candidates_model->get_entity_package_component(array('clients_details.tbl_clients_id' => $clientid));

                echo $this->load->view('admin/view_entity_package_component', $data, true);
            } else {
                echo 'Data Not Found';
            }
        } else {
            echo 'Something went wrong,please try again';
        }

    }

    public function client_send_mail_to_candidate()
    {

        $json_array = array();

        if ($this->input->is_ajax_request()) {

            $candidate_id = $this->input->post('candidate_id');

            if (!empty($candidate_id)) {

                $check_mail_send = $this->candidates_model->select_check_mail_send_or_not($candidate_id);

                if ($check_mail_send[0]['check_mail_send'] == '0') {

                    $details = $this->candidates_model->select_mail_details($candidate_id);

                    $client_manager = $this->candidates_model->select_client_manager_details();

                    $user_profile_info = $this->candidates_model->select_user_info($client_manager[0]['clientmgr']);

                    $email_tmpl_data['to_emails'] = $details[0]['cands_email_id'];

                    $email_tmpl_data['cc_emails'] = $this->client_info['email_id'];

                    $email_tmpl_data['bcc_emails'] = $user_profile_info[0]['email'];

                    $email_tmpl_data['password'] = base64_decode($details[0]['password_en_de']);

                    $email_tmpl_data['cands_name'] = $details[0]['CandidateName'];

                    $email_tmpl_data['subject'] = $details[0]['CandidateName'] . " " . CRMNAME;

                    $this->load->library('email');

                    $result = $this->email->client_send_mail($email_tmpl_data);

                    if ($result) {

                        $this->candidates_model->update_mail_details(array('check_mail_send' => 1), array('client_candidates_info.cands_info_id' => $candidate_id));

                        $json_array['message'] = 'Email Send Successfully';

                        $json_array['status'] = SUCCESS_CODE;

                    } else {
                        $json_array['message'] = 'E-Mail Not Send Successfully';

                        $json_array['status'] = ERROR_CODE;
                    }
                } else {
                    $json_array['message'] = 'Data Save But Mail Already Send';

                    $json_array['status'] = SUCCESS_CODE;
                }

            } else {
                $json_array['message'] = 'Something went wrong, please try again';

                $json_array['status'] = ERROR_CODE;
            }

        } else {
            $json_array['message'] = 'Something went wrong, please try again';

            $json_array['status'] = ERROR_CODE;
        }

        echo_json($json_array);
    }

    public function client_send_mail_to_candidate_update($cmp_ref_no)
    {

        if (!empty($cmp_ref_no)) {
            try {
                $details = $this->candidates_model->select_mail_details(array('client_candidates_info.cmp_ref_no'=>$cmp_ref_no));
             
                $client_manager_id = $this->candidates_model->select_client_manager_details(array('clients.id'=> $this->client_info['client_id']));
         
                $client_manager_email = $this->candidates_model->select_user_info($client_manager_id[0]['clientmgr']);
                       
                $email_tmpl_data['url'] = CANDIDATE_VERIFY_LINK . $details[0]['public_key'].'/'.$details[0]['private_key'];
               
                $email_tmpl_data['from_email'] = VERIFICATIONEMAIL;

                $email_tmpl_data['to_emails'] = $details[0]['cands_email_id'];

                $email_tmpl_data['cc_emails'] = $client_manager_email[0]['email'].','.VERIFICATIONEMAIL;
       
                $email_tmpl_data['cands_name'] = $details[0]['CandidateName'];

                $email_tmpl_data['clientname'] = $details[0]['clientname'];

                $email_tmpl_data['candidate_id'] = $details[0]['id'];

                $email_tmpl_data['cands_info_id'] = $details[0]['cands_info_id'];


                $email_tmpl_data['CandidatesContactNumber'] = $details[0]['CandidatesContactNumber'];

                $email_tmpl_data['pending_component']['address_component_check'] = $details[0]['address_component_check'];
                $email_tmpl_data['pending_component']['employment_component_check'] = $details[0]['employment_component_check'];
                $email_tmpl_data['pending_component']['education_component_check'] = $details[0]['education_component_check'];
                $email_tmpl_data['pending_component']['reference_component_check'] = $details[0]['reference_component_check'];
                $email_tmpl_data['pending_component']['court_component_check'] = $details[0]['court_component_check'];
                $email_tmpl_data['pending_component']['global_component_check'] = $details[0]['global_component_check'];
                $email_tmpl_data['pending_component']['identity_component_check'] = $details[0]['identity_component_check'];
                $email_tmpl_data['pending_component']['credit_report_component_check'] = $details[0]['credit_report_component_check'];
                $email_tmpl_data['pending_component']['drugs_component_check'] = $details[0]['drugs_component_check'];
                $email_tmpl_data['pending_component']['pcc_component_check'] = $details[0]['pcc_component_check'];


                $this->load->library('email');

                $result = $this->email->client_send_mail($email_tmpl_data);

                return $result;
            } catch (Exception $e) {
                log_message('error', 'Candidate email trigger');
                log_message('error', $e->getMessage());
            }
        }
    }

    public function candidate_submit_component_client_update($candidate_id, $client_id, $candidate_name)
    {

        $this->load->model('candidate_info/Candidate_login_model');

        $client_email_id = $this->Candidate_login_model->select_client_email_id(array('client_id' => $client_id));

        $client_manager = $this->Candidate_login_model->select_client_manager_details($client_id);

        $user_profile_info = $this->Candidate_login_model->select_user_info($client_manager[0]['clientmgr']);

        $email_tmpl_data['to_emails'] = $client_email_id[0]['email_id'];

        $email_tmpl_data['cc_emails'] = $user_profile_info[0]['email'];

        $email_tmpl_data['component_details'] = $this->Candidate_login_model->select_all_component_mail($candidate_id);

        $email_tmpl_data['subject'] = ucwords($candidate_name) . " - Component Details";

        $this->load->library('email');

        $result = $this->email->candidate_submit_component($email_tmpl_data);

        return $result;

    }

    public function primary_contact_no_check()
    {
        if ($this->input->is_ajax_request()) {
            $mobileno = $this->input->post('mobileno');
            $clientid = $this->input->post('clientid');
         
            $lists = $this->candidates_model->check_primary_no($mobileno,array('clientid'=>$clientid));
            
            if (!empty($lists)) {
                echo_json(array('entity'=> $lists[0]['entity_name'],'package'=> $lists[0]['package_name'],'ref_no'=> $lists[0]['cmp_ref_no']));
            }else{
                echo_json(array('ref_no'=>"na"));
            } 
        }

    }

    public function remove_uploaded_file($id)
    {
        $json_array = array();
        if ($this->input->is_ajax_request()) {

                $result = $this->candidates_model->save_update_candidate_files(array('status' => 2), array('id' => $id));
                if ($result) {
                    $json_array['status'] = SUCCESS_CODE;
                    $json_array['message'] = 'Attachment removed successfully, please refresh the page';
                } else {
                    $json_array['status'] = ERROR_CODE;
                    $json_array['message'] = 'Something went worong, please try again';
                }

           
            echo_json($json_array);
        }
    }

    public function trigger_sms_again()
    {
        $json_array['status'] = ERROR_CODE;
        $json_array['message'] = 'Database error, please try again';
        if($this->input->is_ajax_request()) 
        {
            $this->form_validation->set_rules('send_id', 'ID', 'required');

            if ($this->form_validation->run() == FALSE)
            {
                $json_array['status'] = ERROR_CODE;
                $json_array['message'] = validation_errors('','');
            }
            else
            {
                $id = $this->input->post('send_id');
  
                $details = $this->candidates_model->select(TRUE,array('*'),array('id' => $id));

                if(!empty($details)) {

                    $update = $this->candidates_model->user_sms_count(array('status' => STATUS_ACTIVE),array('id' => $id));

                    if(!empty($details) && $update) {
                        $this->cli_sms_send_again($details);

                        $json_array['status'] = SUCCESS_CODE;
                        $json_array['message'] = 'SMS Sent';
                    }
                    else{
                         
                        $json_array['status'] = ERROR_CODE;
                        $json_array['message'] = 'SMS NOT Sent'; 
                    }
                   
                }
                
            }
        }
        echo_json($json_array);
    }

    public function trigger_email_again()
    {
        $json_array['status'] = ERROR_CODE;
        $json_array['message'] = 'Database error, please try again';
        if($this->input->is_ajax_request()) 
        {
            $this->form_validation->set_rules('send_id', 'ID', 'required');

            if ($this->form_validation->run() == FALSE)
            {
                $json_array['status'] = ERROR_CODE;
                $json_array['message'] = validation_errors('','');
            }
            else
            {
                $id = $this->input->post('send_id');

                $details = $this->candidates_model->select(true, array(),array('client_candidates_info.id' => $id));

                
                if(!empty($details)) {
                   
                    
                    if(!empty($details['address_component_check']) || !empty($details['education_component_check']) || !empty($details['employment_component_check']) || !empty($details['reference_component_check']) || !empty($details['court_component_check']) || !empty($details['global_component_check']) || !empty($details['identity_component_check']) || !empty($details['credit_report_component_check'])  || !empty($details['drugs_component_check']) || !empty($details['pcc_component_check'])) {

                        $update = $this->candidates_model->user_email_count(array('status' => STATUS_ACTIVE),array('id' => $id));
                           
                        if(!empty($details) && $update) {
                            $this->cli_email_send_again($details);

                            $json_array['status'] = SUCCESS_CODE;
                            $json_array['message'] = 'Email Sent';
                        }
                        else{
                           
                            $json_array['status'] = ERROR_CODE;
                            $json_array['message'] = 'Email Not Send';
                        }
                            
                    }else {
                        $json_array['status'] = ERROR_CODE;
                        $json_array['message'] = 'All check are closed'; 
                    }
                        
                }
                
            }
        }
        echo_json($json_array);
    }

    public function cli_email_send_again($detail)
    {

        if (!empty($detail)) {
            try {
                $details = $this->candidates_model->select_mail_details(array('client_candidates_info.cmp_ref_no'=>$detail['cmp_ref_no']));
             
                $client_manager_id = $this->candidates_model->select_client_manager_details(array('clients.id'=> $this->client_info['client_id']));
         
                $client_manager_email = $this->candidates_model->select_user_info($client_manager_id[0]['clientmgr']);
                       
                $email_tmpl_data['url'] = CANDIDATE_VERIFY_LINK . $details[0]['public_key'].'/'.$details[0]['private_key'];
               
                $email_tmpl_data['from_email'] = VERIFICATIONEMAIL;

                $email_tmpl_data['to_emails'] = $details[0]['cands_email_id'];

                $email_tmpl_data['cc_emails'] = $client_manager_email[0]['email'].','.$this->client_info['email_id'].','.FROMEMAIL;

       
                $email_tmpl_data['cands_name'] = $details[0]['CandidateName'];

                $email_tmpl_data['clientname'] = $details[0]['clientname'];

                $email_tmpl_data['candidate_id'] = $details[0]['id'];

                $email_tmpl_data['caserecddate'] = $details[0]['caserecddate'];

                $email_tmpl_data['cands_info_id'] = $details[0]['cands_info_id'];



                $email_tmpl_data['CandidatesContactNumber'] = $details[0]['CandidatesContactNumber'];

                $email_tmpl_data['pending_component']['address_component_check'] = $details[0]['address_component_check'];
                $email_tmpl_data['pending_component']['employment_component_check'] = $details[0]['employment_component_check'];
                $email_tmpl_data['pending_component']['education_component_check'] = $details[0]['education_component_check'];
                $email_tmpl_data['pending_component']['reference_component_check'] = $details[0]['reference_component_check'];
                $email_tmpl_data['pending_component']['court_component_check'] = $details[0]['court_component_check'];
                $email_tmpl_data['pending_component']['global_component_check'] = $details[0]['global_component_check'];
                $email_tmpl_data['pending_component']['identity_component_check'] = $details[0]['identity_component_check'];
                $email_tmpl_data['pending_component']['credit_report_component_check'] = $details[0]['credit_report_component_check'];
                $email_tmpl_data['pending_component']['drugs_component_check'] = $details[0]['drugs_component_check'];
                $email_tmpl_data['pending_component']['pcc_component_check'] = $details[0]['pcc_component_check'];

                $this->load->library('email');

                $result = $this->email->client_send_mail_trigger($email_tmpl_data);

                return $result;
            } catch (Exception $e) {
                log_message('error', 'Candidate email trigger');
                log_message('error', $e->getMessage());
            }
        }
    }

    public function cli_sms_send_again($detail)
    {

        if (!empty($detail)) {
            try {
                $details = $this->candidates_model->select_mail_details(array('client_candidates_info.cmp_ref_no'=>$detail['cmp_ref_no']));
             
                    if(!empty($details[0]['CandidatesContactNumber']))
                    {
                        $sms_url = CANDIDATE_VERIFY_LINK . $details[0]['public_key'].'/'.$details[0]['private_key']; 

                        $sms_content = "Greetings from ".SMS_NAME.". We have partnered with your current employer ".ucwords($details[0]['clientname'])." to conduct your background verification. Request you to visit ".$sms_url." and update the details at the earliest.";
                   
                        $result_json = $this->send_sms($details[0]['CandidatesContactNumber'],$sms_content);
               
                        $result  = json_decode($result_json);

                        if($result->status == "success")
                        {  

                            $this->candidates_model->save(array('last_sms_on' => date(DB_DATE_FORMAT),'cron_status' => STATUS_ACTIVE),array('id'=>$details[0]['id']));

                            $this->candidates_model->save_task_manager('mail_sms_details',array('type' => 2,'candidate_id'=> $details[0]['cands_info_id'],'mail_sms_send' =>  date(DB_DATE_FORMAT)));
                    
                        }
                    }
               
            } catch (Exception $e) {
                log_message('error', 'cli_sms_send_again');
                log_message('error', $e->getMessage());
            }
        }
    }

    protected function send_sms($numbers = '', $message) {
        log_message('error', '$message and mobile number');
        log_message('error', $message);
        $response = '';
        try {
            if(!empty($numbers) && $message) {
             
                $apiKey = urlencode('Nzc0MTZjMzI3MDMwMzk1NTY0NDc2ZTc2NTA2MzdhNmU=');
                $message = rawurlencode($message);
                $sender = urlencode(MASSAGESENDER);

                if(is_array($numbers))
                    $numbers = implode(',', $numbers);
            
                // Prepare data for POST request
                $data = array('apikey' => $apiKey, 'numbers' => $numbers, "sender" => $sender, "message" => $message);

                log_message('error', print_r($data, true));
                // Send the POST request with cURL
                $ch = curl_init('https://api.textlocal.in/send/');
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $errors = curl_error($ch);
                $response_http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $response = curl_exec($ch);
                curl_close($ch);

                log_message('error', 'send_sms response');
                log_message('error', print_r( [ $response_http ] , TRUE) );
               
            }
        } catch (Exception $e) {

            log_message('error', 'Email Library ::send_sms');
            log_message('error', $e->getMessage());
        }
  
        return $response;
    }

    public function export_candidate(){
        
        $json_array = array();

        if ($this->input->is_ajax_request()) {
            set_time_limit(0);
            ini_set('memory_limit', '-1');

            $all_records = $this->candidates_model->get_data_for_export_from_client($this->user_package,$this->user_role);

           require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
            // Create new Spreadsheet object
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

            // Set document properties
            $spreadsheet->getProperties()->setCreator(CRMNAME)
                ->setLastModifiedBy(CRMNAME)
                ->setTitle(CRMNAME)
                ->setSubject('Candidates records')
                ->setDescription('candidates records with their status');

            // add style to the header
            $styleArray = array(
                'font' => array(
                    'bold' => true,
                ),
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ),
                'borders' => array(
                    'top' => array(
                        'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ),
                ),
                'fill' => array(
                    'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => array(
                        'argb' => 'FFA0A0A0',
                    ),
                    'endcolor' => array(
                        'argb' => 'FFFFFFFF',
                    ),
                ),
            );
            $spreadsheet->getActiveSheet()->getStyle('A1:DH1')->applyFromArray($styleArray);
            // auto fit column to content
            foreach (range('A', 'DH') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                    ->setWidth(20);
            }
            // set the names of header cells

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A1", 'Client Ref No.')
                ->setCellValue("B1", REFNO)
                ->setCellValue("C1", 'Client Name')
                ->setCellValue("D1", 'Entity')
                ->setCellValue("E1", 'Package')
                ->setCellValue("F1", 'Case Initiated')
                ->setCellValue("G1", 'Candidate Name')
                ->setCellValue("F1", 'Candidates Email ID')
                ->setCellValue("G1", 'ContactNo1')
                ->setCellValue("G1", 'ContactNo2')
                ->setCellValue("G1", 'ContactNo3')
                ->setCellValue("G1", 'Gender')
                ->setCellValue("G1", 'Date of Birth')
                ->setCellValue("G1", 'Father Name')
                ->setCellValue("G1", 'Mother Name')
                ->setCellValue("G1", 'Address')
                ->setCellValue("G1", 'City')
                ->setCellValue("G1", 'Pinode')
                ->setCellValue("G1", 'State')
                ->setCellValue("G1", 'Pan No')
                ->setCellValue("G1", 'Aadhar No')
                ->setCellValue("G1", 'Passport No')
                ->setCellValue("H1", 'Overall Status')
                ->setCellValue("I1", 'Overall Closure Date');

                

            // Add some data
            $x = 2;
            foreach ($all_records as $all_record) {
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A$x", $all_record['ClientRefNumber'])
                    ->setCellValue("B$x", $all_record['cmp_ref_no'])
                    ->setCellValue("C$x", $all_record['clientname'])
                    ->setCellValue("D$x", $all_record['entity_name'])
                    ->setCellValue("E$x", $all_record['package_name'])
                    ->setCellValue("F$x", $all_record['caserecddate'])
                    ->setCellValue("G$x", $all_record['CandidateName'])
                    ->setCellValue("H$x", $all_record['overallstatus'])
                    ->setCellValue("I$x", $all_record['overallclosuredate'])
                    ->setCellValue("J$x", $all_record['addrver0'])
                    ->setCellValue("K$x", $all_record['addrver_address0'])
                    ->setCellValue("L$x", $all_record['addrver1'])
                    ->setCellValue("M$x", $all_record['addrver_address1'])
                    ->setCellValue("N$x", $all_record['addrver2'])
                    ->setCellValue("O$x", $all_record['addrver_address2'])
                    ->setCellValue("P$x", $all_record['addrver3'])
                    ->setCellValue("Q$x", $all_record['addrver_address3'])
                    ->setCellValue("R$x", $all_record['addrver4'])
                    ->setCellValue("S$x", $all_record['addrver_address4'])
                    ->setCellValue("T$x", $all_record['empver0'])
                    ->setCellValue("U$x", $all_record['empver_cpm0'])
                    ->setCellValue("V$x", $all_record['empver1'])
                    ->setCellValue("W$x", $all_record['empver_cpm1'])
                    ->setCellValue("X$x", $all_record['empver2'])
                    ->setCellValue("Y$x", $all_record['empver_cpm2'])
                    ->setCellValue("Z$x", $all_record['empver3'])
                    ->setCellValue("AA$x", $all_record['empver_cpm3'])
                    ->setCellValue("AB$x", $all_record['empver4'])
                    ->setCellValue("AC$x", $all_record['empver_cpm4'])
                    ->setCellValue("AD$x", $all_record['eduver0'])
                    ->setCellValue("AE$x", $all_record['eduver_univer0'])
                    ->setCellValue("AF$x", $all_record['eduver1'])
                    ->setCellValue("AG$x", $all_record['eduver_univer1'])
                    ->setCellValue("AH$x", $all_record['eduver2'])
                    ->setCellValue("AI$x", $all_record['eduver_univer2'])
                    ->setCellValue("AJ$x", $all_record['eduver3'])
                    ->setCellValue("AK$x", $all_record['eduver_univer3'])
                    ->setCellValue("AL$x", $all_record['eduver4'])
                    ->setCellValue("AM$x", $all_record['eduver_univer4'])
                    ->setCellValue("AN$x", $all_record['refver0'])
                    ->setCellValue("AO$x", $all_record['refvername0'])
                    ->setCellValue("AP$x", $all_record['refver1'])
                    ->setCellValue("AQ$x", $all_record['refvername1'])
                    ->setCellValue("AR$x", $all_record['refver2'])
                    ->setCellValue("AS$x", $all_record['refvername2'])
                    ->setCellValue("AT$x", $all_record['refver3'])
                    ->setCellValue("AU$x", $all_record['refvername3'])
                    ->setCellValue("AV$x", $all_record['refver4'])
                    ->setCellValue("AW$x", $all_record['refvername4'])
                    ->setCellValue("AX$x", $all_record['courtver0'])
                    ->setCellValue("AY$x", $all_record['courtver_address0'])
                    ->setCellValue("AZ$x", $all_record['courtver1'])
                    ->setCellValue("BA$x", $all_record['courtver_address1'])
                    ->setCellValue("BB$x", $all_record['courtver2'])
                    ->setCellValue("BC$x", $all_record['courtver_address2'])
                    ->setCellValue("BD$x", $all_record['courtver3'])
                    ->setCellValue("BE$x", $all_record['courtver_address3'])
                    ->setCellValue("BF$x", $all_record['courtver4'])
                    ->setCellValue("BG$x", $all_record['courtver_address4'])
                    ->setCellValue("BH$x", $all_record['glodbver0'])
                    ->setCellValue("BI$x", $all_record['glodbver_address0'])
                    ->setCellValue("BJ$x", $all_record['glodbver1'])
                    ->setCellValue("BK$x", $all_record['glodbver_address1'])
                    ->setCellValue("BL$x", $all_record['glodbver2'])
                    ->setCellValue("BM$x", $all_record['glodbver_address2'])
                    ->setCellValue("BN$x", $all_record['glodbver3'])
                    ->setCellValue("BO$x", $all_record['glodbver_address3'])
                    ->setCellValue("BP$x", $all_record['glodbver4'])
                    ->setCellValue("BQ$x", $all_record['glodbver_address4'])
                    ->setCellValue("BR$x", $all_record['crimver0'])
                    ->setCellValue("BS$x", $all_record['crimver_address0'])
                    ->setCellValue("BT$x", $all_record['crimver1'])
                    ->setCellValue("BU$x", $all_record['crimver_address1'])
                    ->setCellValue("BV$x", $all_record['crimver2'])
                    ->setCellValue("BW$x", $all_record['crimver_address2'])
                    ->setCellValue("BX$x", $all_record['crimver3'])
                    ->setCellValue("BY$x", $all_record['crimver_address3'])
                    ->setCellValue("BZ$x", $all_record['crimver4'])
                    ->setCellValue("CA$x", $all_record['crimver_address4'])
                    ->setCellValue("CB$x", $all_record['identity0'])
                    ->setCellValue("CC$x", $all_record['identity_doc0'])
                    ->setCellValue("CD$x", $all_record['identity1'])
                    ->setCellValue("CE$x", $all_record['identity_doc1'])
                    ->setCellValue("CF$x", $all_record['identity2'])
                    ->setCellValue("CG$x", $all_record['identity_doc2'])
                    ->setCellValue("CH$x", $all_record['identity3'])
                    ->setCellValue("CI$x", $all_record['identity_doc3'])
                    ->setCellValue("CJ$x", $all_record['identity4'])
                    ->setCellValue("CK$x", $all_record['identity_doc4'])
                    ->setCellValue("CL$x", $all_record['cbrver0'])
                    ->setCellValue("CM$x", $all_record['cbrver_cibil0'])
                    ->setCellValue("CN$x", $all_record['cbrver1'])
                    ->setCellValue("CO$x", $all_record['cbrver_cibil1'])
                    ->setCellValue("CP$x", $all_record['cbrver2'])
                    ->setCellValue("CQ$x", $all_record['cbrver_cibil2'])
                    ->setCellValue("CR$x", $all_record['cbrver3'])
                    ->setCellValue("CS$x", $all_record['cbrver_cibil3'])
                    ->setCellValue("CT$x", $all_record['cbrver4'])
                    ->setCellValue("CU$x", $all_record['cbrver_cibil4'])
                    ->setCellValue("CV$x", $all_record['drugs0'])
                    ->setCellValue("CW$x", $all_record['drugs_test_code0'])
                    ->setCellValue("CX$x", $all_record['drugs1'])
                    ->setCellValue("CY$x", $all_record['drugs_test_code1'])
                    ->setCellValue("CZ$x", $all_record['drugs2'])
                    ->setCellValue("DA$x", $all_record['drugs_test_code2'])
                    ->setCellValue("DB$x", $all_record['drugs3'])
                    ->setCellValue("DC$x", $all_record['drugs_test_code3'])
                    ->setCellValue("DD$x", $all_record['drugs4'])
                    ->setCellValue("DE$x", $all_record['drugs_test_code4'])
                    ->setCellValue("DF$x", $all_record['Details'])
                    ->setCellValue("DG$x", $all_record['due_date_candidate'])
                    ->setCellValue("DH$x", $all_record['Location']);

                $x++;
            }
            // Rename worksheet
            $spreadsheet->getActiveSheet()->setTitle('Candidate Records');

            $spreadsheet->setActiveSheetIndex(0);

            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment;filename=Candidates Records of .xlsx");
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Excel2007');
            ob_start();
            $writer->save('php://output');
            $xlsData = ob_get_contents();
            ob_end_clean();

            $json_array['file'] = "data:application/vnd.ms-excel;base64," . base64_encode($xlsData);

            $json_array['file_name'] = "Candidates Records of $client_name";

            $json_array['message'] = "File downloaded successfully,please check in download folder";

            $json_array['status'] = SUCCESS_CODE;
        } else {
            $json_array['message'] = "Something went wrong,please try again";

            $json_array['status'] = ERROR_CODE;
        }

        echo_json($json_array);
    }

    public function export_to_excel()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {
            set_time_limit(0);
            ini_set('memory_limit', '-1');

            $client_id = ($this->user_role != "0") ? $this->user_role : false;

            $entity_ids = $this->candidates_model->get_entity_package_details($this->user_package);


            $entity_id = isset($entity_ids[0]['is_entity']) ? $entity_ids[0]['is_entity'] : false;

            $package_id = ($this->user_package != 0) ? $this->user_package : false;
             
            $client_names = $this->candidates_model->get_tat_days($this->user_role); 
           

            $client_name = $client_names['clientname'];


            $all_records = $this->get_all_client_data_for_export($client_id, $entity_id, $package_id, false,false);

            require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
            // Create new Spreadsheet object
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

            // Set document properties
            $spreadsheet->getProperties()->setCreator(CRMNAME)
                ->setLastModifiedBy(CRMNAME)
                ->setTitle(CRMNAME)
                ->setSubject('Candidates records')
                ->setDescription('candidates records with their status');

            // add style to the header
            $styleArray = array(
                'font' => array(
                    'bold' => true,
                ),
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ),
                'borders' => array(
                    'top' => array(
                        'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ),
                ),
                'fill' => array(
                    'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => array(
                        'argb' => 'FFA0A0A0',
                    ),
                    'endcolor' => array(
                        'argb' => 'FFFFFFFF',
                    ),
                ),
            );
            $spreadsheet->getActiveSheet()->getStyle('A1:DJ1')->applyFromArray($styleArray);
            // auto fit column to content
            foreach (range('A', 'DJ') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                    ->setWidth(20);
            }
            // set the names of header cells

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A1", 'Client Ref No.')
                ->setCellValue("B1", REFNO)
                ->setCellValue("C1", 'Client Name')
                ->setCellValue("D1", 'Entity')
                ->setCellValue("E1", 'Package')
                ->setCellValue("F1", 'Case Initiated')
                ->setCellValue("G1", 'Candidate Name')
                ->setCellValue("H1", 'Candidate Contact No')
                ->setCellValue("I1", 'Candidate Email ID')
                ->setCellValue("J1", 'Overall Status')
                ->setCellValue("K1", 'Overall Closure Date')

                ->setCellValue("L1", 'Address Status 1')
                ->setCellValue("M1", 'Address')

                ->setCellValue("N1", 'Address Status 2')
                ->setCellValue("O1", 'Address')

                ->setCellValue("P1", 'Address Status 3')
                ->setCellValue("Q1", 'Address')

                ->setCellValue("R1", 'Address Status 4')
                ->setCellValue("S1", 'Address')

                ->setCellValue("T1", 'Address Status 5')
                ->setCellValue("U1", 'Address')

                ->setCellValue("V1", 'Employment Status 1')
                ->setCellValue("W1", 'Employer Company Name')

                ->setCellValue("X1", 'Employment Status 2')
                ->setCellValue("Y1", 'Employer Company Name')

                ->setCellValue("Z1", 'Employment Status 3')
                ->setCellValue("AA1", 'Employer Company Name')

                ->setCellValue("AB1", 'Employment Status 4')
                ->setCellValue("AC1", 'Employer Company Name')

                ->setCellValue("AD1", 'Employment Status 5')
                ->setCellValue("AE1", 'Employer Company Name')

                ->setCellValue("AF1", 'Education Status 1')
                ->setCellValue("AG1", 'Education University')

                ->setCellValue("AH1", 'Education Status 2')
                ->setCellValue("AI1", 'Education University')

                ->setCellValue("AJ1", 'Education Status 3')
                ->setCellValue("AK1", 'Education University')

                ->setCellValue("AL1", 'Education Status 4')
                ->setCellValue("AM1", 'Education University')

                ->setCellValue("AN1", 'Education Status 5')
                ->setCellValue("AO1", 'Education University')

                ->setCellValue("AP1", 'Reference Status 1')
                ->setCellValue("AQ1", 'Reference Name')

                ->setCellValue("AR1", 'Reference Status 2')
                ->setCellValue("AS1", 'Reference Name')

                ->setCellValue("AT1", 'Reference Status 3')
                ->setCellValue("AU1", 'Reference Name')

                ->setCellValue("AV1", 'Reference Status 4')
                ->setCellValue("AW1", 'Reference Name')

                ->setCellValue("AX1", 'Reference Status 5')
                ->setCellValue("AY1", 'Reference Name')

                ->setCellValue("AZ1", 'Court Status 1')
                ->setCellValue("BA1", 'Court Address')

                ->setCellValue("BB1", 'Court Status 2')
                ->setCellValue("BC1", 'Court Address')

                ->setCellValue("BD1", 'Court Status 3')
                ->setCellValue("BE1", 'Court Address')

                ->setCellValue("BF1", 'Court Status 4')
                ->setCellValue("BG1", 'Court Address')

                ->setCellValue("BH1", 'Court Status 5')
                ->setCellValue("BI1", 'Court Address')

                ->setCellValue("BJ1", 'Global Status 1')
                ->setCellValue("BK1", 'Global Address')

                ->setCellValue("BL1", 'Global Status 2')
                ->setCellValue("BM1", 'Global Address')

                ->setCellValue("BN1", 'Global Status 3')
                ->setCellValue("BO1", 'Global Address')

                ->setCellValue("BP1", 'Global Status 4')
                ->setCellValue("BQ1", 'Global Address')

                ->setCellValue("BR1", 'Global Status 5')
                ->setCellValue("BS1", 'Global Address')

                ->setCellValue("BT1", 'PCC Status 1')
                ->setCellValue("BU1", 'PCC Address')

                ->setCellValue("BV1", 'PCC Status 2')
                ->setCellValue("BW1", 'PCC Address')

                ->setCellValue("BX1", 'PCC Status 3')
                ->setCellValue("BY1", 'PCC Address')

                ->setCellValue("BZ1", 'PCC Status 4')
                ->setCellValue("CA1", 'PCC Address')

                ->setCellValue("CB1", 'PCC Status 5')
                ->setCellValue("CC1", 'PCC Address')

                ->setCellValue("CD1", 'Identity Status 1')
                ->setCellValue("CE1", 'Identity Document')

                ->setCellValue("CF1", 'Identity Status 2')
                ->setCellValue("CG1", 'Identity Document')

                ->setCellValue("CH1", 'Identity Status 3')
                ->setCellValue("CI1", 'Identity Document')

                ->setCellValue("CJ1", 'Identity Status 4')
                ->setCellValue("CK1", 'Identity Document')

                ->setCellValue("CL1", 'Identity Status 5')
                ->setCellValue("CM1", 'Identity Document')

                ->setCellValue("CN1", 'Credit Report Status 1')
                ->setCellValue("CO1", 'Credit Report Details')

                ->setCellValue("CP1", 'Credit Report Status 2')
                ->setCellValue("CQ1", 'Credit Report Details')

                ->setCellValue("CR1", 'Credit Report Status 3')
                ->setCellValue("CS1", 'Credit Report Details')

                ->setCellValue("CT1", 'Credit Report Status 4')
                ->setCellValue("CU1", 'Credit Report Details')

                ->setCellValue("CV1", 'Credit Report Status 5')
                ->setCellValue("CW1", 'Credit Report Details')

                ->setCellValue("CX1", 'Drugs Status 1')
                ->setCellValue("CY1", 'Drugs Details')

                ->setCellValue("CZ1", 'Drugs Status 2')
                ->setCellValue("DA1", 'Drugs Details')

                ->setCellValue("DB1", 'Drugs Status 3')
                ->setCellValue("DC1", 'Credit Report Details')

                ->setCellValue("DD1", 'Drugs Status 4')
                ->setCellValue("DE1", 'Drugs Details')

                ->setCellValue("DF1", 'Drugs Status 5')
                ->setCellValue("DG1", 'Drugs Details')

                ->setCellValue("DH1", 'Insuff Details')
                ->setCellValue("DI1", 'Due Date')
                ->setCellValue("DJ1", 'Branch Location');

            // Add some data
            $x = 2;
            foreach ($all_records as $all_record) {
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A$x", $all_record['ClientRefNumber'])
                    ->setCellValue("B$x", $all_record['cmp_ref_no'])
                    ->setCellValue("C$x", $all_record['clientname'])
                    ->setCellValue("D$x", $all_record['entity_name'])
                    ->setCellValue("E$x", $all_record['package_name'])
                    ->setCellValue("F$x", $all_record['caserecddate'])
                    ->setCellValue("G$x", $all_record['CandidateName'])
                    ->setCellValue("H$x", $all_record['CandidatesContactNumber'])
                    ->setCellValue("I$x", $all_record['cands_email_id'])
                    ->setCellValue("J$x", $all_record['overallstatus'])
                    ->setCellValue("K$x", $all_record['overallclosuredate'])
                    ->setCellValue("L$x", $all_record['addrver0'])
                    ->setCellValue("M$x", $all_record['addrver_address0'])
                    ->setCellValue("N$x", $all_record['addrver1'])
                    ->setCellValue("O$x", $all_record['addrver_address1'])
                    ->setCellValue("P$x", $all_record['addrver2'])
                    ->setCellValue("Q$x", $all_record['addrver_address2'])
                    ->setCellValue("R$x", $all_record['addrver3'])
                    ->setCellValue("S$x", $all_record['addrver_address3'])
                    ->setCellValue("T$x", $all_record['addrver4'])
                    ->setCellValue("U$x", $all_record['addrver_address4'])
                    ->setCellValue("V$x", $all_record['empver0'])
                    ->setCellValue("W$x", $all_record['empver_cpm0'])
                    ->setCellValue("X$x", $all_record['empver1'])
                    ->setCellValue("Y$x", $all_record['empver_cpm1'])
                    ->setCellValue("Z$x", $all_record['empver2'])
                    ->setCellValue("AA$x", $all_record['empver_cpm2'])
                    ->setCellValue("AB$x", $all_record['empver3'])
                    ->setCellValue("AC$x", $all_record['empver_cpm3'])
                    ->setCellValue("AD$x", $all_record['empver4'])
                    ->setCellValue("AE$x", $all_record['empver_cpm4'])
                    ->setCellValue("AF$x", $all_record['eduver0'])
                    ->setCellValue("AG$x", $all_record['eduver_univer0'])
                    ->setCellValue("AH$x", $all_record['eduver1'])
                    ->setCellValue("AI$x", $all_record['eduver_univer1'])
                    ->setCellValue("AJ$x", $all_record['eduver2'])
                    ->setCellValue("AK$x", $all_record['eduver_univer2'])
                    ->setCellValue("AL$x", $all_record['eduver3'])
                    ->setCellValue("AM$x", $all_record['eduver_univer3'])
                    ->setCellValue("AN$x", $all_record['eduver4'])
                    ->setCellValue("AO$x", $all_record['eduver_univer4'])
                    ->setCellValue("AP$x", $all_record['refver0'])
                    ->setCellValue("AQ$x", $all_record['refvername0'])
                    ->setCellValue("AR$x", $all_record['refver1'])
                    ->setCellValue("AS$x", $all_record['refvername1'])
                    ->setCellValue("AT$x", $all_record['refver2'])
                    ->setCellValue("AU$x", $all_record['refvername2'])
                    ->setCellValue("AV$x", $all_record['refver3'])
                    ->setCellValue("AW$x", $all_record['refvername3'])
                    ->setCellValue("AX$x", $all_record['refver4'])
                    ->setCellValue("AY$x", $all_record['refvername4'])
                    ->setCellValue("AZ$x", $all_record['courtver0'])
                    ->setCellValue("BA$x", $all_record['courtver_address0'])
                    ->setCellValue("BB$x", $all_record['courtver1'])
                    ->setCellValue("BC$x", $all_record['courtver_address1'])
                    ->setCellValue("BD$x", $all_record['courtver2'])
                    ->setCellValue("BE$x", $all_record['courtver_address2'])
                    ->setCellValue("BF$x", $all_record['courtver3'])
                    ->setCellValue("BG$x", $all_record['courtver_address3'])
                    ->setCellValue("BH$x", $all_record['courtver4'])
                    ->setCellValue("BI$x", $all_record['courtver_address4'])
                    ->setCellValue("BJ$x", $all_record['glodbver0'])
                    ->setCellValue("BK$x", $all_record['glodbver_address0'])
                    ->setCellValue("BL$x", $all_record['glodbver1'])
                    ->setCellValue("BM$x", $all_record['glodbver_address1'])
                    ->setCellValue("BN$x", $all_record['glodbver2'])
                    ->setCellValue("BO$x", $all_record['glodbver_address2'])
                    ->setCellValue("BP$x", $all_record['glodbver3'])
                    ->setCellValue("BQ$x", $all_record['glodbver_address3'])
                    ->setCellValue("BR$x", $all_record['glodbver4'])
                    ->setCellValue("BS$x", $all_record['glodbver_address4'])
                    ->setCellValue("BT$x", $all_record['crimver0'])
                    ->setCellValue("BU$x", $all_record['crimver_address0'])
                    ->setCellValue("BV$x", $all_record['crimver1'])
                    ->setCellValue("BW$x", $all_record['crimver_address1'])
                    ->setCellValue("BX$x", $all_record['crimver2'])
                    ->setCellValue("BY$x", $all_record['crimver_address2'])
                    ->setCellValue("BZ$x", $all_record['crimver3'])
                    ->setCellValue("CA$x", $all_record['crimver_address3'])
                    ->setCellValue("CB$x", $all_record['crimver4'])
                    ->setCellValue("CC$x", $all_record['crimver_address4'])
                    ->setCellValue("CD$x", $all_record['identity0'])
                    ->setCellValue("CE$x", $all_record['identity_doc0'])
                    ->setCellValue("CF$x", $all_record['identity1'])
                    ->setCellValue("CG$x", $all_record['identity_doc1'])
                    ->setCellValue("CH$x", $all_record['identity2'])
                    ->setCellValue("CI$x", $all_record['identity_doc2'])
                    ->setCellValue("CJ$x", $all_record['identity3'])
                    ->setCellValue("CK$x", $all_record['identity_doc3'])
                    ->setCellValue("CL$x", $all_record['identity4'])
                    ->setCellValue("CM$x", $all_record['identity_doc4'])
                    ->setCellValue("CN$x", $all_record['cbrver0'])
                    ->setCellValue("CO$x", $all_record['cbrver_cibil0'])
                    ->setCellValue("CP$x", $all_record['cbrver1'])
                    ->setCellValue("CQ$x", $all_record['cbrver_cibil1'])
                    ->setCellValue("CR$x", $all_record['cbrver2'])
                    ->setCellValue("CS$x", $all_record['cbrver_cibil2'])
                    ->setCellValue("CT$x", $all_record['cbrver3'])
                    ->setCellValue("CU$x", $all_record['cbrver_cibil3'])
                    ->setCellValue("CV$x", $all_record['cbrver4'])
                    ->setCellValue("CW$x", $all_record['cbrver_cibil4'])
                    ->setCellValue("CX$x", $all_record['drugs0'])
                    ->setCellValue("CY$x", $all_record['drugs_test_code0'])
                    ->setCellValue("CZ$x", $all_record['drugs1'])
                    ->setCellValue("DA$x", $all_record['drugs_test_code1'])
                    ->setCellValue("DB$x", $all_record['drugs2'])
                    ->setCellValue("DC$x", $all_record['drugs_test_code2'])
                    ->setCellValue("DD$x", $all_record['drugs3'])
                    ->setCellValue("DE$x", $all_record['drugs_test_code3'])
                    ->setCellValue("DF$x", $all_record['drugs4'])
                    ->setCellValue("DG$x", $all_record['drugs_test_code4'])
                    ->setCellValue("DH$x", $all_record['Details'])
                    ->setCellValue("DI$x", $all_record['due_date_candidate'])
                    ->setCellValue("DJ$x", $all_record['Location']);

                $x++;
            }
            // Rename worksheet
            $spreadsheet->getActiveSheet()->setTitle('Candidate Records');

            $spreadsheet->setActiveSheetIndex(0);

            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment;filename=Candidates Records of .xlsx");
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Excel2007');
            ob_start();
            $writer->save('php://output');
            $xlsData = ob_get_contents();
            ob_end_clean();

            $json_array['file'] = "data:application/vnd.ms-excel;base64," . base64_encode($xlsData);

            $json_array['file_name'] = "Candidates Records of $client_name";

            $json_array['message'] = "File downloaded successfully,please check in download folder";

            $json_array['status'] = SUCCESS_CODE;
        } else {
            $json_array['message'] = "Something went wrong,please try again";

            $json_array['status'] = ERROR_CODE;
        }

        echo_json($json_array);
    }


     public function get_all_client_data_for_export($clientid, $entity_id, $package_id, $fil_by_status, $fil_by_sub_status)
    {

        $data_arry = array();

        $cands_results = $this->candidates_model->get_all_cand_with_all_status($clientid, $entity_id, $package_id, $fil_by_status, $fil_by_sub_status);

        $x = 0;
        foreach ($cands_results as $key => $cands_result) {

            $data_arry[$x]['id'] = $x + 1;
            $data_arry[$x]['candsid'] = $cands_result['id'];
            $data_arry[$x]['CandidateName'] = ucwords(strtolower($cands_result['CandidateName']));
            $data_arry[$x]['CandidatesContactNumber'] = $cands_result['CandidatesContactNumber'];
            $data_arry[$x]['cands_email_id'] = $cands_result['cands_email_id'];
            $data_arry[$x]['ClientRefNumber'] = $cands_result['ClientRefNumber'];
            $data_arry[$x]['cmp_ref_no'] = $cands_result['cmp_ref_no'];
            $data_arry[$x]['overallstatus'] = $cands_result['status_value'];
            $data_arry[$x]['entity_name'] = $cands_result['entity_name'];
            $data_arry[$x]['package_name'] = $cands_result['package_name'];
            $data_arry[$x]['overallstatus'] = $cands_result['status_value'];
            $data_arry[$x]['due_date_candidate'] = convert_db_to_display_date($cands_result['due_date_candidate']);
            $data_arry[$x]['Location'] = $cands_result['Location'];
            $data_arry[$x]['clientname'] = ucwords(strtolower($cands_result['clientname']));
            $data_arry[$x]['caserecddate'] = convert_db_to_display_date($cands_result['caserecddate']);
            $data_arry[$x]['overallclosuredate'] = ($cands_result['overallclosuredate'] != "") ? convert_db_to_display_date($cands_result['overallclosuredate']) : 'NA';

            for ($i = 0; $i < 5; $i++) {

                $data_arry[$x]["addrver$i"] = 'NA';
                $data_arry[$x]["addrver_address$i"] = 'NA';
                $data_arry[$x]["empver$i"] = 'NA';
                $data_arry[$x]["empver_cpm$i"] = 'NA';
                $data_arry[$x]["eduver$i"] = 'NA';
                $data_arry[$x]["eduver_univer$i"] = 'NA';
                $data_arry[$x]["refver$i"] = 'NA';
                $data_arry[$x]["refvername$i"] = 'NA';
                $data_arry[$x]["courtver$i"] = 'NA';
                $data_arry[$x]["courtver_address$i"] = 'NA';
                $data_arry[$x]["crimver$i"] = 'NA';
                $data_arry[$x]["crimver_address$i"] = 'NA';
                $data_arry[$x]["glodbver$i"] = 'NA';
                $data_arry[$x]["glodbver_address$i"] = 'NA';
                $data_arry[$x]["identity$i"] = 'NA';
                $data_arry[$x]["identity_doc$i"] = 'NA';
                $data_arry[$x]["cbrver$i"] = 'NA';
                $data_arry[$x]["cbrver_cibil$i"] = 'NA';
                $data_arry[$x]["drugs$i"] = 'NA';
                $data_arry[$x]["drugs_test_code$i"] = 'NA';

            }

            $component_id = explode(",", $cands_result['component_id']);

            $rename_status = array('NA' => 'N/A');

            $insufficiency_details = array();

            if (in_array('addrver', $component_id)) {
                $result = $this->candidates_model->get_addres_ver_status_for_export(array('addrver.candsid' => $cands_result['id']));

                if (!empty($result)) {
                    $counter = 1;
                    foreach ($result as $key => $value) {
                        if ($counter > 5) {
                            continue;
                        }

                        if (array_key_exists($value['report_status'], $rename_status)) {
                            $data_arry[$x]["addrver$key"] = $rename_status[$value['report_status']];
                        } else {
                            $data_arry[$x]["addrver$key"] = ($value['report_status'] != "") ? $value['report_status'] : 'WIP';
                        }

                        if (($value['verfstatus'] == "Major Discrepancy") || ($value['verfstatus'] == "major discrepancy")) {

                            array_push($discrepancy_details, "Add " . $counter . " || " . $value['address'] . ',' . $value['city'] . ',' . $value['pincode'] . ',' . $value['state'] . " || " . $value['remarks']);

                        }

                        if (($value['verfstatus'] == "Insufficiency") || ($value['verfstatus'] == "insufficiency")) {

                            array_push($insufficiency_details, "Add " . $counter . " || " . $value['address'] . ',' . $value['city'] . ',' . $value['pincode'] . ',' . $value['state'] . " || " . $value['insuff_raise_remark']);

                        }

                        $data_arry[$x]["addrver_address$key"] = ($value['address'] != "") ? $value['address'] : 'NA';
                        $counter++;
                    }
                }
            }

            if (in_array('eduver', $component_id)) {
                $result = $this->candidates_model->get_education_ver_status_for_export(array('education.candsid' => $cands_result['id']));
                if (!empty($result)) {

                    $counter = 1;
                    foreach ($result as $key => $value) {
                        if ($counter > 5) {
                            continue;
                        }

                        if (array_key_exists($value['report_status'], $rename_status)) {
                            $data_arry[$x]["eduver$key"] = $rename_status[$value['report_status']];
                        } else {
                            $data_arry[$x]["eduver$key"] = ($value['report_status'] != "") ? $value['report_status'] : 'WIP';
                        }

                        if (($value['verfstatus'] == "Insufficiency") || ($value['verfstatus'] == "insufficiency")) {
                            array_push($insufficiency_details, "Edu " . $counter . " || " . $value['universityname'] . ',' . $value['qualification_name'] . " || " . $value['insuff_raise_remark']);
                        }

                        $data_arry[$x]["eduver_univer$key"] = ($value['universityname'] != "") ? $value['universityname'] : 'NA';
                        $counter++;
                    }
                }
            }

            if (in_array('empver', $component_id)) {
                $result = $this->candidates_model->get_employment_ver_status_for_export(array('empver.candsid' => $cands_result['id']));
                if (!empty($result)) {
                    $counter = 1;
                    foreach ($result as $key => $value) {
                        if ($counter > 5) {
                            continue;
                        }

                        if (array_key_exists($value['report_status'], $rename_status)) {
                            $data_arry[$x]["empver$key"] = $rename_status[$value['report_status']];
                        } else {
                            $data_arry[$x]["empver$key"] = ($value['report_status'] != "") ? $value['report_status'] : 'WIP';
                        }

                        if (($value['verfstatus'] == "Insufficiency") || ($value['verfstatus'] == "insufficiency")) {

                            array_push($insufficiency_details, "Emp " . $counter . " || " . $value['coname'] . " || " . $value['insuff_raise_remark']);
                        }

                        $data_arry[$x]["empver_cpm$key"] = ($value['coname'] != "") ? $value['coname'] : 'NA';

                        $counter++;
                    }
                }
            }

            if (in_array('refver', $component_id)) {
                $result = $this->candidates_model->get_refver_ver_status_for_export(array('reference.candsid' => $cands_result['id']));
                if (!empty($result)) {
                    $counter = 1;
                    foreach ($result as $key => $value) {
                        if ($counter > 5) {
                            continue;
                        }

                        if (array_key_exists($value['report_status'], $rename_status)) {
                            $data_arry[$x]["refver$key"] = $rename_status[$value['report_status']];
                        } else {
                            $data_arry[$x]["refver$key"] = ($value['report_status'] != "") ? $value['report_status'] : 'WIP';
                        }

                        if (($value['verfstatus'] == "Insufficiency") || ($value['verfstatus'] == "insufficiency")) {

                            array_push($insufficiency_details, "Ref " . $counter . " || " . $value['name_of_reference'] . " || " . $value['insuff_raise_remark']);
                        }

                        $data_arry[$x]["refvername$key"] = ($value['name_of_reference'] != "") ? $value['name_of_reference'] : 'NA';
                        $counter++;
                    }
                }
            }

            if (in_array('courtver', $component_id)) {
                $result = $this->candidates_model->get_court_ver_status_for_export(array('courtver.candsid' => $cands_result['id']));
                if (!empty($result)) {
                    $counter = 1;
                    foreach ($result as $key => $value) {
                        if ($counter > 5) {
                            continue;
                        }

                        if (array_key_exists($value['report_status'], $rename_status)) {
                            $data_arry[$x]["courtver$key"] = $rename_status[$value['report_status']];
                        } else {
                            $data_arry[$x]["courtver$key"] = ($value['report_status'] != "") ? $value['report_status'] : 'WIP';
                        }

                        if (($value['verfstatus'] == "Insufficiency") || ($value['verfstatus'] == "insufficiency")) {

                            array_push($insufficiency_details, "Court " . $counter . " || " . $value['street_address'] . ',' . $value['city'] . ',' . $value['pincode'] . ',' . $value['state'] . " || " . $value['insuff_raise_remark']);
                        }

                        $data_arry[$x]["courtver_address$key"] = ($value['street_address'] != "") ? $value['street_address'] : 'NA';
                        $counter++;
                    }
                }
            }
            if (in_array('crimver', $component_id)) {
                $result = $this->candidates_model->get_pcc_ver_status_for_export(array('pcc.candsid' => $cands_result['id']));
                if (!empty($result)) {

                    $counter = 1;
                    foreach ($result as $key => $value) {
                        if ($counter > 5) {
                            continue;
                        }

                        if (array_key_exists($value['report_status'], $rename_status)) {
                            $data_arry[$x]["crimver$key"] = $rename_status[$value['report_status']];
                        } else {
                            $data_arry[$x]["crimver$key"] = ($value['report_status'] != "") ? $value['report_status'] : 'WIP';
                        }

                        if (($value['verfstatus'] == "Insufficiency") || ($value['verfstatus'] == "insufficiency")) {

                            array_push($insufficiency_details, "PCC " . $counter . " || " . $value['street_address'] . ',' . $value['city'] . ',' . $value['pincode'] . ',' . $value['state'] . " || " . $value['insuff_raise_remark']);
                        }

                        $data_arry[$x]["crimver_address$key"] = ($value['street_address'] != "") ? $value['street_address'] : 'NA';
                        $counter++;
                    }
                }
            }

            if (in_array('globdbver', $component_id)) {
                $result = $this->candidates_model->get_globdbver_ver_status_for_export(array('glodbver.candsid' => $cands_result['id']));
                if (!empty($result)) {

                    $counter = 1;
                    foreach ($result as $key => $value) {
                        if ($counter > 5) {
                            continue;
                        }

                        if (array_key_exists($value['report_status'], $rename_status)) {
                            $data_arry[$x]["glodbver$key"] = $rename_status[$value['report_status']];
                        } else {
                            $data_arry[$x]["glodbver$key"] = ($value['report_status'] != "") ? $value['report_status'] : 'WIP';
                        }

                        if (($value['verfstatus'] == "Insufficiency") || ($value['verfstatus'] == "insufficiency")) {

                            array_push($insufficiency_details, "Global Database " . $counter . " || " . $value['street_address'] . ',' . $value['city'] . ',' . $value['pincode'] . ',' . $value['state'] . " || " . $value['insuff_raise_remark']);
                        }

                        $data_arry[$x]["glodbver_address$key"] = ($value['street_address'] != "") ? $value['street_address'] : 'NA';
                        $counter++;
                    }
                }
            }
            if (in_array('identity', $component_id)) {
                $result = $this->candidates_model->get_identity_ver_status_for_export(array('identity.candsid' => $cands_result['id']));

                if (!empty($result)) {

                    $counter = 1;
                    foreach ($result as $key => $value) {
                        if ($counter > 5) {
                            continue;
                        }

                        if (array_key_exists($value['report_status'], $rename_status)) {
                            $data_arry[$x]["identity$key"] = $rename_status[$value['report_status']];
                        } else {
                            $data_arry[$x]["identity$key"] = ($value['report_status'] != "") ? $value['report_status'] : 'WIP';
                        }

                        if (($value['verfstatus'] == "Insufficiency") || ($value['verfstatus'] == "insufficiency")) {

                            array_push($insufficiency_details, "Identity " . $counter . " || " . $value['doc_submited'] . " || " . $value['insuff_raise_remark']);
                        }

                        $data_arry[$x]["identity_doc$key"] = ($value['doc_submited'] != "") ? $value['doc_submited'] : 'NA';

                        $counter++;
                    }
                }
            }

            if (in_array('cbrver', $component_id)) {
                $result = $this->candidates_model->get_credit_report_ver_status_for_export(array('credit_report.candsid' => $cands_result['id']));

                if (!empty($result)) {

                    $counter = 1;
                    foreach ($result as $key => $value) {

                        if ($counter > 5) {

                            continue;
                        }

                        if (array_key_exists($value['report_status'], $rename_status)) {
                            $data_arry[$x]["cbrver$key"] = $rename_status[$value['report_status']];
                        } else {
                            $data_arry[$x]["cbrver$key"] = ($value['report_status'] != "") ? $value['report_status'] : 'WIP';
                        }

                        if (($value['verfstatus'] == "Insufficiency") || ($value['verfstatus'] == "insufficiency")) {

                            array_push($insufficiency_details, "Credit Report " . $counter . " || " . " Cibil " . " || " . $value['insuff_raise_remark']);
                        }

                        $data_arry[$x]["cbrver_cibil$key"] = 'Cibil';

                        $counter++;
                    }

                }
            }

            if (in_array('narcver', $component_id)) {
                $result = $this->candidates_model->get_drugs_report_ver_status_for_export(array('drug_narcotis.candsid' => $cands_result['id']));

                if (!empty($result)) {

                    $counter = 1;
                    foreach ($result as $key => $value) {

                        if ($counter > 5) {

                            continue;
                        }

                        if (array_key_exists($value['report_status'], $rename_status)) {
                            $data_arry[$x]["drugs$key"] = $rename_status[$value['report_status']];
                        } else {
                            $data_arry[$x]["drugs$key"] = ($value['report_status'] != "") ? $value['report_status'] : 'WIP';
                        }

                        if (($value['verfstatus'] == "Insufficiency") || ($value['verfstatus'] == "insufficiency")) {

                            array_push($insufficiency_details, "Drugs " . $counter . " || " . $value['drug_test_code'] . " || " . $value['insuff_raise_remark']);
                        }

                        $data_arry[$x]["drugs_test_code$key"] = ($value['drug_test_code'] != "") ? $value['drug_test_code'] : 'NA';

                        $counter++;
                    }

                }
            }

            $insufficiencys_details = array();
            foreach ($insufficiency_details as $key => $value) {
                $insufficiencys_details[] = $value . ",";
            }

            $data_arry[$x]['Details'] = implode('', $insufficiencys_details);
            unset($insufficiencys_details);

            $x++;
        }

        return $data_arry;
    }

    protected function cli_address_invite_mail($result)
    {
        $this->load->library('email');
        $this->email->address_invite_mail_sms($result);
    }


}
?>