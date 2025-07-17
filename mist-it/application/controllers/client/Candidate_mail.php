<?php defined('BASEPATH') or exit('No direct script access allowed');
class Candidate_mail extends MY_Client_Cotroller
{
    public function __construct()
    {
        parent::__construct();
        $this->perm_array = array('direct_access' => true);
        $this->load->library('session');
        $this->load->model('client/candidates_mail_model');

        $this->employment_type = array('' => 'Select', 'full time' => 'Full time', 'contractual' => 'Contractual', 'part time' => 'Part time');
    }

    public function index($public_key = false,$private_key = false)
    {
        if($public_key != "" && $private_key != "")
        {
        
            $candidate_details = $this->candidates_mail_model->select(true, array(),array('public_key' => $public_key,'private_key' => $private_key));
           
            if (!empty($candidate_details)) {
                try {
                    

                    $data['scope_of_work'] = $this->common_model->get_scope_of_work(array('tbl_clients_id' => $candidate_details['clientid'], 'entity' => $candidate_details['entity'], 'package' => $candidate_details['package']));

                    $data['candidate_details'] = $candidate_details;
                    $data['client_components'] = $this->candidates_mail_model->get_entitypackages(array('entity' => $candidate_details['entity'], 'package' => $candidate_details['package']))[0];
                    if($this->input->ip_address()  == "115.112.52.186" && $this->input->ip_address()  == "103.48.59.75")
                    { 
                       $this->candidates_mail_model->save_user_visited_count(array('last_candidate_visit'=> date(DB_DATE_FORMAT)),array('public_key' => $public_key,'private_key' => $private_key));
                    }  
                    

                    $data['states'] = $this->get_states();

 
                   $company_logos = $this->common_model->select('clients',true,array('comp_logo'),array('id'=>$candidate_details['clientid']));

                   
                   $data['company_logo'] = $company_logos['comp_logo']; 

                   echo $this->load->view('client/candidate_add_component',$data ,true); 
                } catch (Exception $e) {
                log_message('error', 'Error on Candidate::view_details');
                log_message('error', $e->getMessage());
                }
            }
        }
        else
        { 
            $this->load->view('err404');
        } 
      
    }
   
    public function ajax_tab_data($candsid = '', $tab = "")
    {
        if ($this->input->is_ajax_request()) {
            $data['cand_id'] = $candsid;
            
            $client_deatils = $this->candidates_mail_model->get_client_deatils($candsid);

            $Candidate_component = $this->candidates_mail_model->get_candidate_component_check($candsid);
             
            switch ($tab) {
                case "addrver":

                    $data['address_lists'] = $this->candidates_mail_model->get_address_details_mail($candsid);
                    $data['component_check'] = $client_deatils[0]['candidate_component_count'];
                    $data['address_details_component_check'] = $Candidate_component[0]['address_component_check'];
 
                    echo $this->load->view('client/address_ajax_tab', $data, true);
                    break;
                case "courtver":
                    $this->load->model('court_verificatiion_model');

                    $data['court_list'] = $this->court_verificatiion_model->get_all_court_record(array('candidates_info.id' => $candsid));
                    $data['component_check'] = $client_deatils[0]['candidate_component_count'];
                    $data['court_details_component_check'] = $Candidate_component[0]['court_component_check'];
 

                    echo $this->load->view('client/court_ajax_tab', $data, true);
                    break;

                case "cbrver":
                    $this->load->model('credit_report_model');

                    $data['credit_report_lists'] = $this->credit_report_model->get_all_credit_report_record(array('candidates_info.id' => $candsid));
                    $data['component_check'] = $client_deatils[0]['candidate_component_count'];
                    $data['credit_report_details_component_check'] = $Candidate_component[0]['credit_report_component_check'];

                    echo $this->load->view('client/credit_report_ajax_tab', $data, true);
                    break;

                case "eduver":

                    $data['education_lists'] = $this->candidates_mail_model->get_education_details_mail($candsid);
                    $data['component_check'] = $client_deatils[0]['candidate_component_count'];
                    $data['education_details_component_check'] = $Candidate_component[0]['education_component_check'];

                    echo $this->load->view('client/education_ajax_tab', $data, true);
                    break;
                case "empver":
                
                    $data['employment_lists'] = $this->candidates_mail_model->get_employment_details_mail($candsid);
                    $data['component_check'] = $client_deatils[0]['candidate_component_count'];
                    $data['employment_details_component_check'] = $Candidate_component[0]['employment_component_check'];

                    echo $this->load->view('client/employment_ajax_tab', $data, true);
                    break;
                case "globdbver":
                    $this->load->model('global_database_model');

                    $data['global_cand_lists'] = $this->global_database_model->get_all_global_record(array('candidates_info.id' => $candsid));
                    $data['component_check'] = $client_deatils[0]['candidate_component_count'];
                    $data['global_details_component_check'] = $Candidate_component[0]['global_component_check'];
             
                    echo $this->load->view('client/global_ajax_tab', $data, true);
                    break;
                case "identity":
                    $this->load->model('identity_model');

                    $data['identity_lists'] = $this->identity_model->get_all_identity_record(array('candidates_info.id' => $candsid));
                    $data['component_check'] = $client_deatils[0]['candidate_component_count'];
                    $data['identity_details_component_check'] = $Candidate_component[0]['identity_component_check'];
             

                    echo $this->load->view('client/identity_ajax_tab', $data, true);
                    break;
                case "crimver":
                    $this->load->model('pcc_verificatiion_model');

                    $data['crimver_lists'] = $this->pcc_verificatiion_model->get_all_pcc_record(array('candidates_info.id' => $candsid));
                    $data['component_check'] = $client_deatils[0]['candidate_component_count'];
                    $data['pcc_details_component_check'] = $Candidate_component[0]['pcc_component_check'];

                    echo $this->load->view('client/pcc_ajax_tab', $data, true);
                    break;
                case "narcver":
                    $this->load->model('drug_verificatiion_model');

                    $data['drug_lists'] = $this->drug_verificatiion_model->get_all_drug_record(array('candidates_info.id' => $candsid));
                    $data['component_check'] = $client_deatils[0]['candidate_component_count'];
                    $data['drugs_details_component_check'] = $Candidate_component[0]['drugs_component_check'];


                    echo $this->load->view('client/drug_ajax_tab', $data, true);
                    break;
                case "refver":
                    $this->load->model('reference_verificatiion_model');

                    $data['reference_lists'] = $this->reference_verificatiion_model->get_all_reference_record(array('candidates_info.id' => $candsid));

                    $data['component_check'] = $client_deatils[0]['candidate_component_count'];
                    $data['reference_details_component_check'] = $Candidate_component[0]['reference_component_check'];
              
                    echo $this->load->view('client/references_ajax_tab', $data, true);
                    break;
                default:
                    echo "No components found";
            }
        }
    }
    public function address_add($candsid = false,$address_type = false)
    {
        $this->load->model('addressver_model');

        if ($this->input->is_ajax_request()) {

            $data['get_cands_details'] = $this->candidate_entity_pack_details($candsid);
            $data['states'] = $this->get_states();
            $data['address_type_status'] = $address_type;

            $data['mode_of_verification'] = $this->addressver_model->get_mode_of_verification(array('entity' => $data['get_cands_details']['entity_id'], 'package' => $data['get_cands_details']['package_id'], 'tbl_clients_id' => $data['get_cands_details']['clientid']));

            echo $this->load->view('client/address_add_candidate', $data, true);
        } else {
            echo "<h3>Something went wrong, please try again</h3>";
        }
    }

    public function address_edit($candsid = false,$address_type = false)
    {
        $this->load->model('addressver_model');

        if ($this->input->is_ajax_request()) {

            $addressver_details = $this->addressver_model->get_address_details(array('addrver.candsid' => $candsid));
            if(!empty($addressver_details))
            {
                $data['header_title'] = 'Edit Address Verification';

               $data['states'] = $this->get_states();

               $data['addressver_details'] = $addressver_details[0];

               $data['address_type_status'] = $address_type;


               $data['get_cands_details'] = $this->candidate_entity_pack_details($candsid);

               echo $this->load->view('client/address_edit_candidate', $data, true);
            }
        } else {
            echo "<h3>Something went wrong, please try again</h3>";
        }
    }

    public function address_view_form_inside($add_comp_id = false)
    {
        $this->load->model('addressver_model');
        $add_details = $this->addressver_model->get_address_details(array('addrver.add_com_ref' => $add_comp_id));

        $Candidate_component = $this->candidates_mail_model->get_candidate_component_check($add_details[0]['candsid']);
        
        if ($add_comp_id && !empty($add_details) ) {

            if ($Candidate_component[0]['address_component_check'] == "1")
            {
                log_message('error', 'Address Edit From Candidate');
                try {
                    $data['header_title'] = 'Edit Address Verification';

                    $data['states'] = $this->get_states();

                    $data['addressver_details'] = $add_details[0];

                    $data['get_cands_details'] = $this->candidate_entity_pack_details($add_details[0]['candsid']);

                    echo $this->load->view('client/address_edit_candidate', $data, true);

                } catch (Exception $e) {
                    log_message('error', 'Address::view_form_inside');
                    log_message('error', $e->getMessage());
                }
            }else{
                echo "<h3>Address Details already submitted</h3>";
            }
        } else {
            echo "<h3>Something went wrong, please try again</h3>";
        }
    }

    public function drugs_view_from_inside($drugs_comp_id = false)
    {
        $this->load->model('drug_verificatiion_model');
        $cget_details = $this->drug_verificatiion_model->get_all_drug_record(array('drug_narcotis.drug_com_ref' => $drugs_comp_id));

        $Candidate_component = $this->candidates_mail_model->get_candidate_component_check($cget_details[0]['cands_id']);

        if ($drugs_comp_id && !empty($cget_details)) {

            if ($Candidate_component[0]['drugs_component_check'] == "1")
            {

                log_message('error', 'Drugs Edit From Candidate');
                try {
                    $data['header_title'] = 'Edit Drugs Verification';

                    $data['states'] = $this->get_states();

                    $data['drugs_details'] = $cget_details[0];

                    $data['get_cands_details'] = $this->candidate_entity_pack_details($cget_details[0]['cands_id']);

                    echo $this->load->view('client/drugs_edit_candidate', $data, true);

                } catch (Exception $e) {
                    log_message('error', 'Drugs_narcotics::view_form_inside');
                    log_message('error', $e->getMessage());
                }

            }else{
                echo "<h3>Drugs Details already submitted</h3>";
            }
        } else {
            echo "<h3>Something went wrong, please try again</h3>";
        }
    }


    public function reference_view_from_inside($reference_comp_id = false)
    {
        $this->load->model('reference_verificatiion_model');
        $cget_details = $this->reference_verificatiion_model->get_all_reference_record(array('reference.reference_com_ref' => $reference_comp_id));
        
        $Candidate_component = $this->candidates_mail_model->get_candidate_component_check($cget_details[0]['cands_id']);


        if ($reference_comp_id && !empty($cget_details)) {

            if ($Candidate_component[0]['reference_component_check'] == "1")
            {
                log_message('error', 'Reference Edit From Candidate');
                try {
                    $data['header_title'] = 'Edit Reference Verification';

                    $data['states'] = $this->get_states();

                    $data['reference_details'] = $cget_details[0];
    
                    $data['get_cands_details'] = $this->candidate_entity_pack_details($cget_details[0]['cands_id']);

                    echo $this->load->view('client/reference_edit_candidate', $data, true);

                } catch (Exception $e) {
                    log_message('error', 'Reference_verificatiion::view_form_inside');
                    log_message('error', $e->getMessage());
                }

            }else{
                echo "<h3>Reference Details already submitted</h3>";
            }    

        } else {
            echo "<h3>Something went wrong, please try again</h3>";
        }
    }

    public function employment_view_from_cands($emp_comp_id = false)
    {
        $this->load->model('employment_model');
       
        $emp_details = $this->employment_model->get_employer_details(array('empver.emp_com_ref' => $emp_comp_id));

        $Candidate_component = $this->candidates_mail_model->get_candidate_component_check($emp_details[0]['candsid']);


        if ($emp_comp_id && !empty($emp_details)) {

            if ($Candidate_component[0]['employment_component_check'] == "1")
            {
                
                log_message('error', 'Employment Edit From Candidate');
                try {
                    $data['header_title'] = 'Edit Employment Verification';

                    $data['states'] = $this->get_states();

                    $data['empt_details'] = $emp_details[0];

                    $data['company_list'] = $this->get_company_list();

                    $data['get_cands_details'] = $this->candidate_entity_pack_details($emp_details[0]['candsid']);

                    echo $this->load->view('client/employment_edit_candidate', $data, true);

                } catch (Exception $e) {
                    log_message('error', 'employment::view_form_inside');
                    log_message('error', $e->getMessage());
                }
            }else{
                echo "<h3>Employment Details already submitted</h3>";
            }    
    
        } else {
            echo "<h3>Something went wrong, please try again</h3>";
        }
    }

    public function education_view_from_cands($education_com_ref)
    {
        $this->load->model('education_model');
       
        $cget_details = $this->education_model->get_all_education_record(array('education.education_com_ref' => $education_com_ref));

        $Candidate_component = $this->candidates_mail_model->get_candidate_component_check($cget_details[0]['cands_id']);


        if ($education_com_ref && !empty($cget_details)) {

            if ($Candidate_component[0]['education_component_check'] == "1")
            {
             
                log_message('error', 'Education Edit From Candidate');
                try {
                    $data['header_title'] = 'Edit Education Verification';

                    $data['states'] = $this->get_states();


                    $data['universityname'] = $this->university_list();

                    $data['qualification_name'] = $this->qualification_list();

                    $data['selected_data'] = $cget_details[0];

                    $data['company_list'] = $this->get_company_list();

                    $data['get_cands_details'] = $this->candidate_entity_pack_details($cget_details[0]['cands_id']);

                    echo $this->load->view('client/education_edit_candidate', $data, true);

                } catch (Exception $e) {
                    log_message('error', 'education::view_form_inside');
                    log_message('error', $e->getMessage());
                }

            }else{
                echo "<h3>Education Details already submitted</h3>";
            }     
        } else {
            echo "<h3>Something went wrong, please try again</h3>";
        }
    }

    public function identity_view_from_inside($identity_com_ref)
    {
        $this->load->model('identity_model');
       
        $cget_details = $this->identity_model->get_all_identity_record(array('identity.identity_com_ref' => $identity_com_ref));
        
        $Candidate_component = $this->candidates_mail_model->get_candidate_component_check($cget_details[0]['cands_id']);


        if ($identity_com_ref && !empty($cget_details)) {

            if ($Candidate_component[0]['identity_component_check'] == "1")
            {
                log_message('error', 'Identity Edit From Candidate');
                try {
                    $data['header_title'] = 'Edit Identity Verification';

                    $data['states'] = $this->get_states();

                    $data['selected_data'] = $cget_details[0];

                    $data['get_cands_details'] = $this->candidate_entity_pack_details($cget_details[0]['cands_id']);

                    echo $this->load->view('client/identity_edit_candidate', $data, true);

                } catch (Exception $e) {
                    log_message('error', 'Identity::view_form_inside');
                    log_message('error', $e->getMessage());
                }
            }else{
                echo "<h3>Identity Details already submitted</h3>";
            } 
        } else {
            echo "<h3>Something went wrong, please try again</h3>";
        }
    }
     
    public function credit_report_view_from_inside($credit_report_com_ref)
    {
        $this->load->model('credit_report_model');
    
        $cget_details = $this->credit_report_model->get_all_credit_report_record(array('credit_report.credit_report_com_ref' => $credit_report_com_ref));

        $Candidate_component = $this->candidates_mail_model->get_candidate_component_check($cget_details[0]['cands_id']);


        if ($credit_report_com_ref && !empty($cget_details)) {

            
            if ($Candidate_component[0]['credit_report_component_check'] == "1")
            {

                log_message('error', 'Credit Report Edit From Candidate');
                try {
                    $data['header_title'] = 'Edit Credit Report Verification';

                    $data['states'] = $this->get_states();

                    $data['selected_data'] = $cget_details[0];

                    $data['get_cands_details'] = $this->candidate_entity_pack_details($cget_details[0]['cands_id']);

                    echo $this->load->view('client/credit_report_edit_candidate', $data, true);

                } catch (Exception $e) {
                    log_message('error', 'Credit_report::view_form_inside');
                    log_message('error', $e->getMessage());
                }

            }else{
                echo "<h3>Credit Report Details already submitted</h3>";
            }     
        } else {
            echo "<h3>Something went wrong, please try again</h3>";
        }
    }
 

    public function employment_add($candsid = false)
    {
        $this->load->model('employment_model');

        if ($this->input->is_ajax_request()) {

            $data['get_cands_details'] = $this->candidate_entity_pack_details($candsid);
            $data['states'] = $this->get_states();
            $data['company_list'] = $this->get_company_list();


            $data['mode_of_verification'] = $this->employment_model->get_mode_of_verification(array('entity' => $data['get_cands_details']['entity_id'], 'package' => $data['get_cands_details']['package_id'], 'tbl_clients_id' => $data['get_cands_details']['clientid']));

            echo $this->load->view('client/employment_add_candidate', $data, true);
        } else {
            echo "<h3>Something went wrong, please try again</h3>";
        }
    }


    public function employment_edit($candsid = false)
    {
        $this->load->model('employment_model');
              

        if ($this->input->is_ajax_request()) {

           $emp_details = $this->employment_model->get_employer_details(array('empver.candsid' => $candsid ));

            if(!empty($emp_details))
            {

            $data['get_cands_details'] = $this->candidate_entity_pack_details($candsid);
            $data['states'] = $this->get_states();
            $data['company_list'] = $this->get_company_list();
            $data['empt_details'] = $emp_details[0];

            echo $this->load->view('client/employment_edit_candidate', $data, true);
            }
        } else {
            echo "<h3>Something went wrong, please try again</h3>";
        }
    }


    

    public function education_add($candsid = false)
    {
        $this->load->model('education_model');

        if ($this->input->is_ajax_request()) {

            $data['get_cands_details'] = $this->candidate_entity_pack_details($candsid);
            $data['states'] = $this->get_states();

            $data['universityname'] = $this->university_list();

            $data['qualification_name'] = $this->qualification_list();


            $data['mode_of_verification'] = $this->education_model->get_mode_of_verification(array('entity' => $data['get_cands_details']['entity_id'], 'package' => $data['get_cands_details']['package_id'], 'tbl_clients_id' => $data['get_cands_details']['clientid']));

            echo $this->load->view('client/education_add_candidate', $data, true);
        } else {
            echo "<h3>Something went wrong, please try again</h3>";
        }
    }

    public function education_edit($candsid = false)
    {
        $this->load->model('education_model');
              
        if ($this->input->is_ajax_request()) {

            $details = $this->education_model->get_all_education_record(array('education.candsid' => $candsid));
            if(!empty($details))
            {
                $data['get_cands_details'] = $this->candidate_entity_pack_details($candsid);

                $data['states'] = $this->get_states();
                
                $data['selected_data'] = $details[0];

                $data['universityname'] = $this->university_list();

                $data['qualification_name'] = $this->qualification_list();
                  
                echo $this->load->view('client/education_edit_candidate', $data, true);
            }
            else {
               echo "<h3>Something went wrong, please try again</h3>";
            }
        } else {
            echo "<h3>Something went wrong, please try again</h3>";
        }
    }


    public function identity_add($candsid = false)
    {
        $this->load->model('identity_model');

        if ($this->input->is_ajax_request()) {

            $data['get_cands_details'] = $this->candidate_entity_pack_details($candsid);
            $data['states'] = $this->get_states();

            $data['mode_of_verification'] = $this->identity_model->get_mode_of_verification(array('entity' => $data['get_cands_details']['entity_id'], 'package' => $data['get_cands_details']['package_id'], 'tbl_clients_id' => $data['get_cands_details']['clientid']));

            echo $this->load->view('client/identity_add_candidate', $data, true);
        } else {
            echo "<h3>Something went wrong, please try again</h3>";
        }
    }

    public function identity_edit($candsid = false)
    {
        $this->load->model('identity_model');

        if ($this->input->is_ajax_request()) {

            $details = $this->identity_model->get_all_identity_record(array('identity.candsid' => $candsid));
            if(!empty($details)){

                $data['selected_data'] = $details[0];

                $data['get_cands_details'] = $this->candidate_entity_pack_details($candsid);
                $data['states'] = $this->get_states();

                echo $this->load->view('client/identity_edit_candidate', $data, true);
            }
            else {
              echo "<h3>Something went wrong, please try again</h3>";
            }
        } else {
            echo "<h3>Something went wrong, please try again</h3>";
        }
    }


    public function credit_report_add($candsid = false)
    {
        $this->load->model('credit_report_model');

        if ($this->input->is_ajax_request()) {

            $data['get_cands_details'] = $this->candidate_entity_pack_details($candsid);
            $data['states'] = $this->get_states();

            $data['mode_of_verification'] = $this->credit_report_model->get_mode_of_verification(array('entity' => $data['get_cands_details']['entity_id'], 'package' => $data['get_cands_details']['package_id'], 'tbl_clients_id' => $data['get_cands_details']['clientid']));

            echo $this->load->view('client/credit_report_add_candidate', $data, true);
        } else {
            echo "<h3>Something went wrong, please try again</h3>";
        }
    }

    public function credit_report_edit($candsid = false)
    {
        $this->load->model('credit_report_model');

        if ($this->input->is_ajax_request()) {

            $details = $this->credit_report_model->get_all_credit_report_record(array('credit_report.candsid' => $candsid));
            if(!empty($details))
            { 
                $data['selected_data'] = $details[0];

                $data['get_cands_details'] = $this->candidate_entity_pack_details($candsid);
                $data['states'] = $this->get_states();

                echo $this->load->view('client/credit_report_edit_candidate', $data, true);
            }
            else {
              echo "<h3>Something went wrong, please try again</h3>";
            }

        } else {
            echo "<h3>Something went wrong, please try again</h3>";
        }
    }


    public function reference_add($candsid = false)
    {
        $this->load->model('reference_verificatiion_model');

        if ($this->input->is_ajax_request()) {

            $data['get_cands_details'] = $this->candidate_entity_pack_details($candsid);
            $data['states'] = $this->get_states();

            $data['mode_of_verification'] = $this->reference_verificatiion_model->get_mode_of_verification(array('entity' => $data['get_cands_details']['entity_id'], 'package' => $data['get_cands_details']['package_id'], 'tbl_clients_id' => $data['get_cands_details']['clientid']));

            echo $this->load->view('client/reference_add_candidate', $data, true);
        } else {
            echo "<h3>Something went wrong, please try again</h3>";
        }
    }

    public function reference_edit($candsid = false)
    {
        $this->load->model('reference_verificatiion_model');

        if ($this->input->is_ajax_request()) {
                
            $details = $this->reference_verificatiion_model->get_all_reference_record(array('reference.candsid' => $candsid));
            
            if(!empty($details))
            {
                $data['header_title'] = 'Edit Reference Verification';

               $data['reference_details'] = $details[0];

               $data['get_cands_details'] = $this->candidate_entity_pack_details($candsid);

               echo $this->load->view('client/reference_edit_candidate', $data, true);
            }
        } else {
            echo "<h3>Something went wrong, please try again</h3>";
        }
    }

    public function drugs_narcotics_add($candsid = false)
    {
        
        if ($this->input->is_ajax_request()) {

            $this->load->model('drug_verificatiion_model');

            $data['get_cands_details'] = $this->candidate_entity_pack_details($candsid);
            $data['states'] = $this->get_states();

            $data['mode_of_verification'] = $this->drug_verificatiion_model->get_mode_of_verification(array('entity' => $data['get_cands_details']['entity_id'], 'package' => $data['get_cands_details']['package_id'], 'tbl_clients_id' => $data['get_cands_details']['clientid']));

            echo $this->load->view('client/drugs_add_candidate', $data, true);
        } else {
            echo "<h3>Something went wrong, please try again</h3>";
        }
    }
    
    public function drugs_narcotics_edit($candsid = false)
    {        
        if ($this->input->is_ajax_request()) {

            $this->load->model('drug_verificatiion_model');

            $details = $this->drug_verificatiion_model->get_all_drug_record(array('drug_narcotis.candsid' => $candsid));

            if(!empty($details))
            {
              
            $data['get_cands_details'] = $this->candidate_entity_pack_details($candsid);
            $data['states'] = $this->get_states();

            $data['drugs_details'] = $details[0];

            $data['mode_of_verification'] = $this->drug_verificatiion_model->get_mode_of_verification(array('entity' => $data['get_cands_details']['entity_id'], 'package' => $data['get_cands_details']['package_id'], 'tbl_clients_id' => $data['get_cands_details']['clientid']));
            }
 
            echo $this->load->view('client/drugs_edit_candidate', $data, true);
        } else {
            echo "<h3>Something went wrong, please try again</h3>";
        }
    } 
  
    public function university_list()
    {
        $this->load->model('university_model');

        $results = $this->university_model->select(false, array('universityname', 'id'), array());
        $university_arry = array();

        $university_arry[0] = 'Select University';

        foreach ($results as $key => $value) {
            $university_arry[$value['id']] = $value['universityname'];
        }

        return $university_arry;
    }

    public function qualification_list()
    {
        $this->load->model('qualification_model');

        $results = $this->qualification_model->select(false, array('qualification', 'id'), array());

        $university_arry = array();

        $university_arry[0] = 'Select Qualification';

        foreach ($results as $key => $value) {
            $university_arry[$value['id']] = ucwords($value['qualification']);
        }

        return $university_arry;
    }

    public function candidates_update()
    {

        $this->load->model('client/candidates_model');

        if ($this->input->is_ajax_request()) {

            $this->form_validation->set_rules('candidate_id', 'ID', 'required');

            $this->form_validation->set_rules('CandidateName', 'Candidate Name', 'required');

            $this->form_validation->set_rules('gender', 'Gender', 'required');

            $this->form_validation->set_rules('CandidatesContactNumber', 'Primary Cantact', 'required');

            if ($this->form_validation->run() == false) {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');
            } else {
                $frm_details = $this->input->post();

                $id = $frm_details['candidate_id'];

                if ($id == $frm_details['candidate_id']) {
                  

                    $field_array = array('CandidateName' => $frm_details['CandidateName'],
                        'DateofBirth' => convert_display_to_db_date($frm_details['DateofBirth']),
                        'NameofCandidateFather' => $frm_details['NameofCandidateFather'],
                        'CandidatesContactNumber' => $frm_details['CandidatesContactNumber'],
                        'ContactNo1' => $frm_details['ContactNo1'],
                        'gender' => $frm_details['gender'],
                         );
               
                    $result = $this->candidates_model->save($field_array, array('cands_info_id' => $id));
  
                    if ($result) {

                        $result_main_table = $this->candidates_model->save_actual_table($field_array,array('id' => $id));


                        $field_array = array('CandidateName' => $frm_details['CandidateName'],
                        'DateofBirth' => convert_display_to_db_date($frm_details['DateofBirth']),
                        'NameofCandidateFather' => $frm_details['NameofCandidateFather'],
                        'CandidatesContactNumber' => $frm_details['CandidatesContactNumber'],
                        'ContactNo1' => $frm_details['ContactNo1'],
                        'gender' => $frm_details['gender'],
                        'candidates_info_id' => $id
                        );

                        $result_candidate = $this->candidates_model->save_candidate($field_array);

                        $result_candidate = $this->candidates_model->save_candidate_log_main($field_array);


                        $json_array['status'] = SUCCESS_CODE;

                        $json_array['message'] = 'Info Updated Successfully';

                    } else {
                        $json_array['status'] = ERROR_CODE;

                        $json_array['message'] = 'Database error, please try again';
                    }
                } else {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Something went wrong, please try again';

                }
            }
            echo_json($json_array);
        } else {
            permission_denied();
        }
    }

    public function save_address()
    {
      
        if ($this->input->is_ajax_request()) {

            $this->load->model('addressver_model');

                $frm_details = $this->input->post();

                $this->form_validation->set_rules('clientid', 'Client', 'required');

                $this->form_validation->set_rules('candsid', 'Candidate', 'required');

                $this->form_validation->set_rules('address', 'Address', 'required');

                $this->form_validation->set_rules('city', 'City', 'required');

                $this->form_validation->set_rules('pincode', 'PIN code', 'required');

                $this->form_validation->set_rules('address_type', 'Address Type', 'required');
             
                $this->form_validation->set_rules('state', 'State', 'required');


            if ($this->form_validation->run() == false ) {

                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');
            } else {


                $address_id = $this->addressver_model->check_address_exists_in_candidate(array('address'  => $frm_details['address'],'candsid' => $frm_details['candsid']));

                if(empty($address_id))
                {
                  
                    $work = $this->common_model->get_scope_of_work(array('tbl_clients_id' => $frm_details['clientid'], 'entity' => $frm_details['entity_id'], 'package' => $frm_details['package_id']));

                    if(!empty($work))
                    {
                      $scope_of_work =  (json_decode($work[0]['scope_of_work'])->addrver);
                    }
               
                    if((isset($scope_of_work) && $scope_of_work == "all") || ($scope_of_work ==  $frm_details['address_type']) || ($frm_details['address_type'] == "current/permanent"))
                    {
                         
                        $tat_day = $this->get_client_entity_package_tat_day(array('tbl_clients_id' => $frm_details['clientid'], 'entity' => $frm_details['entity_id'], 'package' => $frm_details['package_id']));

                        $get_holiday1 = $this->get_holiday();
                           

                        $get_holiday = array_map('current', $get_holiday1);

                        $closed_date = getWorkingDays(convert_display_to_db_date($frm_details['iniated_date']), $get_holiday, $tat_day[0]['tat_addrver']);

                        $has_case_id = $this->addressver_model->get_reporting_manager_id($frm_details['clientid']);
                        

                            $field_array = array('clientid' => $frm_details['clientid'],
                                'candsid' => $frm_details['candsid'],
                                'add_com_ref' => '',
                                'stay_from' => $frm_details['stay_from'],
                                'stay_to' => $frm_details['stay_to'],
                                'address_type' => $frm_details['address_type'],
                                'address' => $frm_details['address'],
                                'city' => $frm_details['city'],
                                'pincode' => $frm_details['pincode'],
                                'state' => $frm_details['state'],
                                'mod_of_veri' => $frm_details['mod_of_veri'],
                                'created_on' => date(DB_DATE_FORMAT),
                                'modified_on' => date(DB_DATE_FORMAT),
                                'is_bulk_uploaded' => 0,
                                'iniated_date' => convert_display_to_db_date($frm_details['iniated_date']),
                                "add_re_open_date" => '',
                                'has_case_id' => $has_case_id[0]['clientmgr'],
                                "due_date" => $closed_date,
                                "tat_status" => 'IN TAT',
                                'fill_by' => 2,
                            );
                      

                        $result = $this->addressver_model->save(array_map('strtolower', $field_array));

                        $add_com_ref = $this->add_com_ref($result);

                        $error_msgs = array();
                        $file_upload_path = SITE_BASE_PATH . ADDRESS . $frm_details['clientid'];
                        if (!folder_exist($file_upload_path)) {
                            mkdir($file_upload_path, 0777,true);
                        } else if (!is_writable($file_upload_path)) {
                            array_push($error_msgs, 'Problem while uploading');
                        }

                        $config_array = array('file_upload_path' => $file_upload_path, 'file_permission' => 'jpeg|jpg|png|pdf|docx|xls|xlsx', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 1000, 'file_id' => $result, 'component_name' => 'addrver_id');

                        if (empty($error_msgs)) {
                            if ($_FILES['attchments']['name'][0] != '') {
                                $config_array['files_count'] = count($_FILES['attchments']['name']);
                                $config_array['file_data'] = $_FILES['attchments'];
                                $config_array['type'] = 0;
                                $retunr_de = $this->file_upload_multiple($config_array);
                                if (!empty($retunr_de)) {
                                    $this->common_model->common_insert_batch('addrver_files', $retunr_de['success']);
                                }
                            }

                        }

                        if ($result) {


                                $field = array('candsid' => $frm_details['candsid'],
                                    'ClientRefNumber' => '',
                                    'comp_table_id' => $result,
                                    'activity_mode' => '',
                                    'activity_status' => 'New check',
                                    'activity_type' => 'New check',
                                    'action' => 'New check',
                                    'next_follow_up_date' => null,
                                    'remarks' => 'New Check Added by  Client ' . $frm_details['clientname'],
                                    'created_on' => date(DB_DATE_FORMAT),
                                    'is_auto_filled' => 0,

                                );


                            $result = $this->addressver_model->save_trigger($field);

                            if ($result) {
                                $component_status_update = $this->common_model->component_stat_update(array('address_component_check'=>'0'),array('cands_info_id'=>$frm_details['candsid']));

                                auto_update_client_candidate_status($frm_details['candsid']);

                                $this->component_save_candidate_mail_update('Address',$frm_details); 

                                $json_array['status'] = SUCCESS_CODE;

                                $json_array['message'] = 'Address Record Successfully Inserted';

                               
                            } else {
                                $json_array['status'] = ERROR_CODE;

                                $json_array['message'] = 'Something went wrong,please try again';
                            }

                        
                        } else {
                            $json_array['status'] = ERROR_CODE;

                            $json_array['message'] = 'Something went wrong,please try again';
                        }
                    }else {

                        $json_array['status'] = SUCCESS_CODE;

                        $json_array['message'] = 'Address Condition not match but other component add if condition match';
                    }
                }
                else {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Address Already exits '.$frm_details['address'];
                }

                $this->load->model('clients_model');

                $client_components = $this->clients_model->get_entitypackages(array('tbl_clients_id' => $frm_details['clientid'], 'entity' => $frm_details['entity_id'], 'package' => $frm_details['package_id']));

                if(!empty($client_components));
                {
                    $selected_component = explode(',', $client_components[0]['component_id']);
                    if(in_array('courtver', $selected_component))
                    {
                    
                        $Candidate_component = $this->candidates_mail_model->get_candidate_component_check($frm_details['candsid']);
                    
                        if($Candidate_component[0]['court_component_check'] == "1"){
                            $this->update_court($frm_details); 
                        }
                        else{
                            $this->save_court($frm_details);
                        }
                    }
                
                    if(in_array('globdbver', $selected_component))
                    { 
                        $this->load->model('Global_database_model');
                        $global_id = $this->Global_database_model->check_global_exists_in_candidate(array('candsid' =>  $frm_details['candsid']));

                        $Candidate_component = $this->candidates_mail_model->get_candidate_component_check($frm_details['candsid']);

                        if($Candidate_component[0]['global_component_check'] == "1"){
                            $this->update_global($frm_details);
                        }else{
                            $this->save_global($frm_details);
                        }
                    }
                    if(in_array('crimver', $selected_component))
                    {   $this->load->model('Pcc_verificatiion_model');
                        

                        $Candidate_component = $this->candidates_mail_model->get_candidate_component_check($frm_details['candsid']);

                        if($Candidate_component[0]['pcc_component_check'] == "1"){
                            $this->update_pcc($frm_details);
                        }else{
                            $this->save_pcc($frm_details);
                        }
                    }
                }
            }
            echo_json($json_array);
        }
    }

    public function update_address()
    {
        if ($this->input->is_ajax_request()) {

            $this->load->model('addressver_model');

                $frm_details = $this->input->post();

                $this->form_validation->set_rules('address', 'Address', 'required');

                $this->form_validation->set_rules('city', 'City', 'required');

                $this->form_validation->set_rules('pincode', 'PIN code', 'required');

                $this->form_validation->set_rules('address_type', 'Address Type', 'required');
             
                $this->form_validation->set_rules('state', 'State', 'required');


            if ($this->form_validation->run() == false ) {

                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');
            } else { 
                   
                 
                    $work = $this->common_model->get_scope_of_work(array('tbl_clients_id' => $frm_details['clientid'], 'entity' => $frm_details['entity_id'], 'package' => $frm_details['package_id']));

                    if(!empty($work))
                    {
                      $scope_of_work =  (json_decode($work[0]['scope_of_work'])->addrver);
                    }

                     if((isset($scope_of_work) && $scope_of_work == "all") || ($scope_of_work ==  $frm_details['address_type']) || ($frm_details['address_type'] == "current/permanent"))
                    {
                    
                        $field_array = array(
                            'stay_from' => $frm_details['stay_from'],
                            'stay_to' => $frm_details['stay_to'],
                            'address_type' => $frm_details['address_type'],
                            'address' => $frm_details['address'],
                            'city' => $frm_details['city'],
                            'pincode' => $frm_details['pincode'],
                            'state' => $frm_details['state']
                        );
                      
                        $result = $this->addressver_model->save(array_map('strtolower', $field_array),array('id'=>$frm_details['update_id']));

                        $error_msgs = array();
                        $file_upload_path = SITE_BASE_PATH . ADDRESS . $frm_details['clientid'];
                        if (!folder_exist($file_upload_path)) {
                            mkdir($file_upload_path, 0777,true);
                        } else if (!is_writable($file_upload_path)) {
                            array_push($error_msgs, 'Problem while uploading');
                        }

                        $config_array = array('file_upload_path' => $file_upload_path, 'file_permission' => 'jpeg|jpg|png|pdf|docx|xls|xlsx', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 1000, 'file_id' => $frm_details['update_id'], 'component_name' => 'addrver_id');

                        if (empty($error_msgs)) {
                            if ($_FILES['attchments']['name'][0] != '') {
                                $config_array['files_count'] = count($_FILES['attchments']['name']);
                                $config_array['file_data'] = $_FILES['attchments'];
                                $config_array['type'] = 0;
                                $retunr_de = $this->file_upload_multiple($config_array);
                                if (!empty($retunr_de)) {
                                    $this->common_model->common_insert_batch('addrver_files', $retunr_de['success']);
                                }
                            }
                        }

                        if ($result) {
                            
                            $update_candidate_info = $this->addressver_model->update_status('client_candidates_info',array('address_component_check'=> '0'),array('cands_info_id'=> $frm_details['candsid']));
                        

                            $insuff_clear_date = date(DATE_ONLY);

                            $check = $this->addressver_model->select_insuff(array('addrverid' =>  $frm_details['update_id'], 'status !=' => 3, 'insuff_clear_date is null' => null));
                            if(!empty($check))
                            {
                           
                                $hold_days = getNetWorkDays($check[0]['insuff_raised_date'],$insuff_clear_date);

                                $date_tat = $this->addressver_model->get_date_for_update(array('id' => $frm_details['update_id']));

                                $due_date = $date_tat[0]['due_date'];

                                $today = date("Y-m-d");

                                $get_holiday1 = $this->get_holiday();

                                $get_holiday = array_map('current', $get_holiday1);

                                $closed_date = getWorkingDays_increament($due_date, $get_holiday, $hold_days);

                                $x = 1;
                                $counter_1 = 0;
                                while ($x <= 10) {

                                    $yesterday = date('Y-m-d', strtotime('-' . $x . ' day', strtotime($closed_date)));

                                    $today_date1 = strtotime($yesterday);

                                    $day_name1 = date("D", $today_date1);

                                    if ($day_name1 == 'Sat' || $day_name1 == 'Sun' || in_array($yesterday, $get_holiday) == true) {
                                        $counter_1 += 1;
                                    } else {
                                        break;
                                    }
                                    $x++;
                                }

                                $yesterday_main = $yesterday;

                                $y = 1;
                                $counter_2 = 0;
                                while ($y <= 10) {

                                    $yesterday_ago = date('Y-m-d', strtotime('-' . $y . ' day', strtotime($yesterday_main)));

                                    $today_date2 = strtotime($yesterday_ago);

                                    $day_name2 = date("D", $today_date2);

                                    if ($day_name2 == 'Sat' || $day_name2 == 'Sun' || in_array($yesterday_ago, $get_holiday) == true) {
                                        $counter_2 += 1;
                                    } else {
                                        break;
                                    }
                                    $y++;
                                }

                                $yesterday_ago_main = $yesterday_ago;

                                $date_array = array($yesterday_main, $yesterday_ago_main);

                                if ($closed_date < $today) {
                                    $new_tat = "OUT TAT";
                                } elseif ($closed_date == $today) {
                                    $new_tat = "TDY TAT";
                                } elseif (in_array(date("Y-m-d"), $date_array) == true) {
                                    $new_tat = "AP TAT";
                                } else {
                                    $new_tat = "IN TAT";
                                }

                                $result_due_date = $this->addressver_model->update_due_date(array('due_date' => $closed_date, 'tat_status' => $new_tat), array('id' => $frm_details['update_id']));

                                $fields = array('insuff_clear_date' => $insuff_clear_date, 'insuff_remarks' => "Candidate Fill Address", 'insuff_cleared_timestamp' => date(DB_DATE_FORMAT), 'status' => 2,  'auto_stamp' => 2, 'hold_days' => $hold_days);

                                $result = $this->addressver_model->save_update_insuff($fields, array('addrverid' => $frm_details['update_id']));
                            }
                        

                            if(isset($scope_of_work) && ($scope_of_work != $frm_details['address_type']))
                            {
                               $this->load->model("activity_data_model");

                                $fields = array('candsid' => $frm_details['candsid'],
                                        'comp_table_id' => $frm_details['update_id'],
                                        'activity_status' => "NA",
                                        'activity_mode' => "NA",
                                        'activity_type' => "NA",
                                        'action' => "NA",
                                        'remarks' => "Candidate update record",
                                        'created_on' => date(DB_DATE_FORMAT),
                                        'component_type' => 1,
                                );
                                $result_activity_log = $this->activity_data_model->activity_log_save($fields);

                                $this->load->model("addressver_model");

                                $field_array = array(
                                            'verfstatus' => 28,
                                            'var_filter_status' => "closed",
                                            'var_report_status' => "NA",
                                            'clientid' => $frm_details['clientid'],
                                            'candsid' => $frm_details['candsid'],
                                            'closuredate' => date(DATE_ONLY),
                                            'addrverid' => $frm_details['update_id'],
                                            'remarks' => "Candidate fill address but current and permanent address different",
                                            'modified_on' => date(DB_DATE_FORMAT),
                                            'is_bulk_uploaded' => 0,
                                            'activity_log_id' => $result_activity_log,
                                    );

                                $field_array = array_map('strtolower', $field_array);
                    
                                $result = $this->addressver_model->save_update_result(array_map('strtolower', $field_array), array('addrverid' => $frm_details['update_id']));

                                $result_addrverres_result = $this->addressver_model->save_update_result_addrverres(array_map('strtolower', $field_array));

                            }
                        
                                $component_status_update = $this->common_model->component_stat_update(array('address_component_check'=>'0'),array('cands_info_id'=>$frm_details['candsid']));

                                auto_update_client_candidate_status($frm_details['candsid']);

                                $this->component_save_candidate_mail_update('Address',$frm_details); 
 

                                $json_array['status'] = SUCCESS_CODE;

                                $json_array['message'] = 'Address Record Successfully Inserted';

                               
                            } else {
                                $json_array['status'] = ERROR_CODE;

                                $json_array['message'] = 'Something went wrong,please try again';
                            }
                    }else {

                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'Address Condition not match but other component add if condition match';
                    
                    }
        
                       

                $this->load->model('clients_model');

                $client_components = $this->clients_model->get_entitypackages(array('tbl_clients_id' => $frm_details['clientid'], 'entity' => $frm_details['entity_id'], 'package' => $frm_details['package_id']));

                if(!empty($client_components));
                {
                    $selected_component = explode(',', $client_components[0]['component_id']);
                    if(in_array('courtver', $selected_component))
                    {
                       $this->update_court($frm_details);
                    }
                
                    if(in_array('globdbver', $selected_component))
                    {
                       $this->update_global($frm_details);
                    }
                    if(in_array('crimver', $selected_component))
                    {
                       $this->update_pcc($frm_details);
                    }
                }
            }
            echo_json($json_array);
        }
    }

    public function update_court($values)
    {
        
        $this->load->model('court_verificatiion_model');
            
        $frm_details = $values;

        $work = $this->common_model->get_scope_of_work(array('tbl_clients_id' => $frm_details['clientid'], 'entity' => $frm_details['entity_id'], 'package' => $frm_details['package_id']));

        if(!empty($work))
        {
            $scope_of_work =  (json_decode($work[0]['scope_of_work'])->courtver);
        }

        if((isset($scope_of_work) && $scope_of_work == "all") || ($scope_of_work ==  $frm_details['address_type']) || ($frm_details['address_type'] == "current/permanent"))
        {
    
            $field_array = array(
                                
                'address_type' => $frm_details['address_type'],
                'street_address' => $frm_details['address'],
                'city' => $frm_details['city'],
                'pincode' => $frm_details['pincode'],
                'state' => $frm_details['state']
            );
                    
            $result = $this->court_verificatiion_model->save(array_map('strtolower', $field_array),array('candsid'=>$frm_details['candsid']));
        
            if ($result) {
                                
                $update_candidate_info = $this->court_verificatiion_model->update_status('client_candidates_info',array('court_component_check'=> '0'),array('cands_info_id'=> $frm_details['candsid']));
                            

                $insuff_clear_date = date(DATE_ONLY);

                $court_id = $this->court_verificatiion_model->check_court_exists_in_candidate(array('candsid' =>  $frm_details['candsid']));
            
                $check = $this->court_verificatiion_model->select_insuff(array('courtver_id' =>  $court_id[0]['id'], 'status !=' => 3, 'insuff_clear_date is null' => null));

                if(!empty($check))
                {
                            
                    $hold_days = getNetWorkDays($check[0]['insuff_raised_date'],$insuff_clear_date);

                    $date_tat = $this->court_verificatiion_model->get_date_for_update(array('id' =>   $court_id[0]['id']));

                    $due_date = $date_tat[0]['due_date'];

                    $today = date("Y-m-d");

                    $get_holiday1 = $this->get_holiday();

                    $get_holiday = array_map('current', $get_holiday1);

                    $closed_date = getWorkingDays_increament($due_date, $get_holiday, $hold_days);

                    $x = 1;
                    $counter_1 = 0;
                        
                        while ($x <= 10) {

                            $yesterday = date('Y-m-d', strtotime('-' . $x . ' day', strtotime($closed_date)));

                            $today_date1 = strtotime($yesterday);

                            $day_name1 = date("D", $today_date1);

                            if ($day_name1 == 'Sat' || $day_name1 == 'Sun' || in_array($yesterday, $get_holiday) == true) {
                                $counter_1 += 1;
                            } else {
                                break;
                            }
                                $x++;
                        }

                        $yesterday_main = $yesterday;

                        $y = 1;
                        $counter_2 = 0;
                        while ($y <= 10) {

                            $yesterday_ago = date('Y-m-d', strtotime('-' . $y . ' day', strtotime($yesterday_main)));

                            $today_date2 = strtotime($yesterday_ago);

                            $day_name2 = date("D", $today_date2);

                            if ($day_name2 == 'Sat' || $day_name2 == 'Sun' || in_array($yesterday_ago, $get_holiday) == true) {
                                $counter_2 += 1;
                            } else {
                                break;
                            }
                            $y++;
                        }

                        $yesterday_ago_main = $yesterday_ago;

                        $date_array = array($yesterday_main, $yesterday_ago_main);

                            if ($closed_date < $today) {
                                $new_tat = "OUT TAT";
                            } elseif ($closed_date == $today) {
                                $new_tat = "TDY TAT";
                            } elseif (in_array(date("Y-m-d"), $date_array) == true) {
                                $new_tat = "AP TAT";
                            } else {
                                $new_tat = "IN TAT";
                            }

                        $result_due_date = $this->court_verificatiion_model->update_due_date(array('due_date' => $closed_date, 'tat_status' => $new_tat), array('id' =>  $court_id[0]['id']));

                        $fields = array('insuff_clear_date' => $insuff_clear_date, 'insuff_remarks' => "Candidate Fill Court", 'insuff_cleared_timestamp' => date(DB_DATE_FORMAT), 'status' => 2,  'auto_stamp' => 2, 'hold_days' => $hold_days);

                        $result = $this->court_verificatiion_model->save_update_insuff($fields, array('courtver_id' =>   $court_id[0]['id']));
                }
                            

                auto_update_client_candidate_status($frm_details['candsid']);

                $this->component_save_candidate_mail_update('Court',$frm_details); 
            }
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

    public function save_reference()
    {
        if ($this->input->is_ajax_request()) {

            $json_array = array();

            $this->load->model('reference_verificatiion_model');

            $frm_details = $this->input->post();

            $this->form_validation->set_rules('clientid', 'Client', 'required');

            $this->form_validation->set_rules('candsid', 'Candidate', 'required');

            $this->form_validation->set_rules('name_of_reference', 'Name of reference', 'required');
       
            if ($this->form_validation->run() == false) {

                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');

            } else {

                $reference_id = $this->reference_verificatiion_model->check_referenceno_exists_in_candidate(array('contact_no'  => $frm_details['contact_no'],'candsid' => $frm_details['candsid']));
                if(empty($reference_id))
                {

                    $tat_day = $this->get_client_entity_package_tat_day(array('tbl_clients_id' => $frm_details['clientid'], 'entity' => $frm_details['entity_id'], 'package' => $frm_details['package_id']));

                    $get_holiday1 = $this->get_holiday();

                    $get_holiday = array_map('current', $get_holiday1);

                    $closed_date = getWorkingDays(convert_display_to_db_date($frm_details['iniated_date']), $get_holiday, $tat_day[0]['tat_refver']);

                    $has_case_id = $this->reference_verificatiion_model->get_reporting_manager_id_client($frm_details['clientid']);

                        $field_array = array('clientid' => $frm_details['clientid'],
                            'candsid' => $frm_details['candsid'],
                            'reference_com_ref' => "",
                            'iniated_date' => convert_display_to_db_date($frm_details['iniated_date']),
                            'name_of_reference' => $frm_details['name_of_reference'],
                            'designation' => $frm_details['designation'],
                            'contact_no' => $frm_details['contact_no'],
                            'email_id' => $frm_details['email_id'],
                            'mode_of_veri' => $frm_details['mod_of_veri'],
                            'created_on' => date(DB_DATE_FORMAT),
                            'modified_on' => date(DB_DATE_FORMAT),
                            'is_bulk_uploaded' => 0,
                            'has_case_id' => $has_case_id[0]['clientmgr'],
                            "due_date" => $closed_date,
                            "tat_status" => 'IN TAT',
                            'fill_by' => 2,
                        );

                   
                    $result = $this->reference_verificatiion_model->save(array_map('strtolower', $field_array));
                    $reference_com_ref = $this->reference_com_ref($result);

                    $error_msgs = array();
                    $file_upload_path = SITE_BASE_PATH . REFERENCES . $frm_details['clientid'];
                    if (!folder_exist($file_upload_path)) {
                        mkdir($file_upload_path, 0777,true);
                    } else if (!is_writable($file_upload_path)) {
                        array_push($error_msgs, 'Problem while uploading');
                    }

                    $config_array = array('file_upload_path' => $file_upload_path, 'file_permission' => 'jpeg|jpg|png|pdf|docx|xls|xlsx', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 1000, 'file_id' => $result, 'component_name' => 'reference_id');
                    if (empty($error_msgs)) {
                        if ($_FILES['attchments']['name'][0] != '') {
                            $config_array['files_count'] = count($_FILES['attchments']['name']);
                            $config_array['file_data'] = $_FILES['attchments'];
                            $config_array['type'] = 0;
                            $retunr_de = $this->file_upload_multiple($config_array);
                            if (!empty($retunr_de)) {
                                    $this->common_model->common_insert_batch('reference_files', $retunr_de['success']);
                            }
                        }
                    }
            

                    if ($result) {

                            $field = array('candsid' => $frm_details['candsid'],
                                'ClientRefNumber' => '',
                                'comp_table_id' => $result,
                                'activity_mode' => '',
                                'activity_status' => 'New check',
                                'activity_type' => 'New check',
                                'action' => 'New check',
                                'next_follow_up_date' => null,
                                'remarks' => 'New Check Added by  Client ' . $frm_details['clientname'],
                                'created_on' => date(DB_DATE_FORMAT),
                                'is_auto_filled' => 0,

                            );
                       

                        if ($result) {
                              $component_status_update = $this->common_model->component_stat_update(array('reference_component_check'=>'0'),array('cands_info_id'=>$frm_details['candsid']));

                            auto_update_client_candidate_status($frm_details['candsid']);

                            $this->component_save_candidate_mail_update('Reference',$frm_details); 
 

                            auto_update_tat_status($frm_details['candsid']);

                            auto_update_overall_status($frm_details['candsid']);

                            $json_array['status'] = SUCCESS_CODE;

                            $json_array['message'] = 'Reference Record Successfully Inserted';


                        } else {
                            $json_array['status'] = ERROR_CODE;

                            $json_array['message'] = 'Something went wrong,please try again';
                        }

                        //$json_array['active_tab'] = 'addrver';
                    } else {
                        $json_array['status'] = ERROR_CODE;

                        $json_array['message'] = 'Something went wrong,please try again';
                    }

                } else {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Already Exits '.$frm_details['contact_no'];
                } 
                
            }
            echo_json($json_array);
        }
    }

    public function update_reference()
    {
        if ($this->input->is_ajax_request()) {

            $json_array = array();

            $this->load->model('reference_verificatiion_model');

            $frm_details = $this->input->post();

            $this->form_validation->set_rules('name_of_reference', 'Name of reference', 'required');
       
            if ($this->form_validation->run() == false) {

                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');

            } else {

                    $field_array = array('clientid' => $frm_details['clientid'],
                            'candsid' => $frm_details['candsid'],
                            'name_of_reference' => $frm_details['name_of_reference'],
                            'designation' => $frm_details['designation'],
                            'contact_no' => $frm_details['contact_no'],
                            'email_id' => $frm_details['email_id']);

                   
                    $result = $this->reference_verificatiion_model->save(array_map('strtolower', $field_array),array('id'=>$frm_details['update_id']));

                    $error_msgs = array();
                    $file_upload_path = SITE_BASE_PATH . REFERENCES . $frm_details['clientid'];
                    if (!folder_exist($file_upload_path)) {
                        mkdir($file_upload_path, 0777,true);
                    } else if (!is_writable($file_upload_path)) {
                        array_push($error_msgs, 'Problem while uploading');
                    }

                    $config_array = array('file_upload_path' => $file_upload_path, 'file_permission' => 'jpeg|jpg|png|pdf|docx|xls|xlsx', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 1000, 'file_id' => $frm_details['update_id'], 'component_name' => 'reference_id');
                    if (empty($error_msgs)) {
                        if ($_FILES['attchments']['name'][0] != '') {
                            $config_array['files_count'] = count($_FILES['attchments']['name']);
                            $config_array['file_data'] = $_FILES['attchments'];
                            $config_array['type'] = 0;
                            $retunr_de = $this->file_upload_multiple($config_array);
                            if (!empty($retunr_de)) {
                                    $this->common_model->common_insert_batch('reference_files', $retunr_de['success']);
                            }
                        }
                    }
            

                    if ($result) {
                        
                           $update_candidate_info = $this->reference_verificatiion_model->update_status('client_candidates_info',array('reference_component_check'=> '0'),array('cands_info_id'=> $frm_details['candsid']));

                            $insuff_clear_date = date(DATE_ONLY);

                            $check = $this->reference_verificatiion_model->select_insuff(array('reference_id' =>  $frm_details['update_id'], 'status !=' => 3, 'insuff_clear_date is null' => null));
                           
                            if(!empty($check))
                            {

                                $hold_days = getNetWorkDays($check[0]['insuff_raised_date'], $insuff_clear_date);

                                $date_tat = $this->reference_verificatiion_model->get_date_for_update(array('id' => $frm_details['update_id']));

                                $due_date = $date_tat[0]['due_date'];

                                $today = date("Y-m-d");

                                $get_holiday1 = $this->get_holiday();

                                $get_holiday = array_map('current', $get_holiday1);

                                $closed_date = getWorkingDays_increament($due_date, $get_holiday, $hold_days);

                                $x = 1;
                                $counter_1 = 0;
                                while ($x <= 10) {

                                    $yesterday = date('Y-m-d', strtotime('-' . $x . ' day', strtotime($closed_date)));

                                    $today_date1 = strtotime($yesterday);

                                    $day_name1 = date("D", $today_date1);

                                    if ($day_name1 == 'Sat' || $day_name1 == 'Sun' || in_array($yesterday, $get_holiday) == true) {

                                        $counter_1 += 1;
                                    } else {
                                        break;
                                    }

                                    $x++;
                                }

                                $yesterday_main = $yesterday;

                                $y = 1;
                                $counter_2 = 0;
                                while ($y <= 10) {

                                    $yesterday_ago = date('Y-m-d', strtotime('-' . $y . ' day', strtotime($yesterday_main)));

                                    $today_date2 = strtotime($yesterday_ago);

                                    $day_name2 = date("D", $today_date2);

                                    if ($day_name2 == 'Sat' || $day_name2 == 'Sun' || in_array($yesterday_ago, $get_holiday) == true) {

                                        $counter_2 += 1;
                                    } else {
                                        break;
                                    }
                                    $y++;
                                }

                                $yesterday_ago_main = $yesterday_ago;

                                $date_array = array($yesterday_main, $yesterday_ago_main);

                                if ($closed_date < $today) {
                                    $new_tat = "OUT TAT";
                                } elseif ($closed_date == $today) {
                                    $new_tat = "TDY TAT";
                                } elseif (in_array(date("Y-m-d"), $date_array) == true) {
                                    $new_tat = "AP TAT";
                                } else {
                                    $new_tat = "IN TAT";
                                }

                                $result_due_date = $this->reference_verificatiion_model->update_due_date(array('due_date' => $closed_date, 'tat_status' => $new_tat), array('id' => $frm_details['update_id']));

                                $fields = array('insuff_clear_date' => $insuff_clear_date, 'insuff_remarks' => "Candidate Fill Form", 'insuff_cleared_timestamp' => date(DB_DATE_FORMAT), 'status' => 2, 'auto_stamp' => 2, 'hold_days' => $hold_days);

                                $result = $this->reference_verificatiion_model->save_update_insuff($fields, array('reference_id' => $frm_details['update_id']));
                            }

                        $component_status_update = $this->common_model->component_stat_update(array('reference_component_check'=>'0'),array('cands_info_id'=>$frm_details['candsid']));

                        auto_update_client_candidate_status($frm_details['candsid']); 
                        $this->component_save_candidate_mail_update('Reference',$frm_details); 


                        $json_array['status'] = SUCCESS_CODE;

                        $json_array['message'] = 'Reference Record Successfully Inserted';


                    } else {
                        $json_array['status'] = ERROR_CODE;

                        $json_array['message'] = 'Something went wrong,please try again';
                    }

            }
            echo_json($json_array);
        }
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

    public function save_credit_report()
    {
        if ($this->input->is_ajax_request()) {

            $this->load->model('Credit_report_model');

            $frm_details = $this->input->post();

            $this->form_validation->set_rules('clientid', 'Client', 'required');

            $this->form_validation->set_rules('candsid', 'Candidate', 'required');

            $this->form_validation->set_rules('doc_submited', 'Document', 'required');

            $this->form_validation->set_rules('id_number', 'ID Number', 'required');

            if ($this->form_validation->run() == false ) {

                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');
            } else {
                
                $credit_report_id = $this->Credit_report_model->check_credit_exists_in_candidate(array('doc_submited'  => $frm_details['doc_submited'],'candsid' => $frm_details['candsid']));

                if(empty($credit_report_id))
                {

                    $tat_day = $this->get_client_entity_package_tat_day(array('tbl_clients_id' => $frm_details['clientid'], 'entity' => $frm_details['entity_id'], 'package' => $frm_details['package_id']));

                    $get_holiday1 = $this->get_holiday();

                    $get_holiday = array_map('current', $get_holiday1);

                    $closed_date = getWorkingDays(convert_display_to_db_date($frm_details['iniated_date']), $get_holiday, $tat_day[0]['tat_cbrver']);

                    $has_case_id = $this->Credit_report_model->get_reporting_manager_id($frm_details['clientid']);

                        $field_array = array('clientid' => $frm_details['clientid'],
                            'candsid' => $frm_details['candsid'],
                            'credit_report_com_ref' => '',
                            'doc_submited' => $frm_details['doc_submited'],
                            'id_number' => $frm_details['id_number'],
                            'mode_of_veri' => $frm_details['mod_of_veri'],
                            'created_on' => date(DB_DATE_FORMAT),
                            'modified_on' => date(DB_DATE_FORMAT),
                            'is_bulk_uploaded' => 0,
                            'iniated_date' => convert_display_to_db_date($frm_details['iniated_date']),
                            "credit_report_re_open_date" => '',
                            "due_date" => $closed_date,
                            'has_case_id' => $has_case_id[0]['clientmgr'],
                            "tat_status" => 'IN TAT',
                            'fill_by' => 2,
                        );
                  
                    $result = $this->Credit_report_model->save(array_map('strtolower', $field_array));

                    $credit_report_com_ref = $this->credit_report_com_ref($result);
                      
                    $error_msgs = array();
                    $file_upload_path = SITE_BASE_PATH . EMPLOYMENT . $frm_details['clientid'];
                    if (!folder_exist($file_upload_path)) {
                        mkdir($file_upload_path, 0777,true);
                    } else if (!is_writable($file_upload_path)) {
                        array_push($error_msgs, 'Problem while uploading');
                    }  
              

                    $config_array = array('file_upload_path' => $file_upload_path, 'file_permission' => 'jpeg|jpg|png|pdf|docx|xls|xlsx', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 1000, 'file_id' => $result, 'component_name' => 'credit_report_id');

                    if (empty($error_msgs)) {
                        if ($_FILES['attchments']['name'][0] != '') {
                            $config_array['files_count'] = count($_FILES['attchments']['name']);
                            $config_array['file_data'] = $_FILES['attchments'];
                            $config_array['type'] = 0;
                            $retunr_de = $this->file_upload_multiple($config_array);
                            if (!empty($retunr_de)) {
                                $this->common_model->common_insert_batch('credit_report_files', $retunr_de['success']);
                            }
                        }

                    }

                    
                    if ($result) {
                        $component_status_update = $this->common_model->component_stat_update(array('credit_report_component_check'=>'0'),array('cands_info_id'=>$frm_details['candsid']));

                        auto_update_client_candidate_status($frm_details['candsid']);
                        $this->component_save_candidate_mail_update('Credit Report',$frm_details); 
 

                        $json_array['status'] = SUCCESS_CODE;

                        $json_array['message'] = 'Credit Report Record Successfully Inserted';
                           
                    } else {
                        $json_array['status'] = ERROR_CODE;

                        $json_array['message'] = 'Something went wrong,please try again';
                    }
                } else {

                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Already Exists '.$frm_details['doc_submited'];
                }    
      
            }
             echo_json($json_array);
        }
    }

    public function update_credit_report()
    {
        if ($this->input->is_ajax_request()) {

            $this->load->model('Credit_report_model');

            $frm_details = $this->input->post();

            $this->form_validation->set_rules('clientid', 'Client', 'required');

            $this->form_validation->set_rules('candsid', 'Candidate', 'required');

            $this->form_validation->set_rules('doc_submited', 'Document', 'required');

            $this->form_validation->set_rules('id_number', 'ID Number', 'required');

            if ($this->form_validation->run() == false ) {

                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');
            } else {
                
                        $field_array = array('clientid' => $frm_details['clientid'],
                            'candsid' => $frm_details['candsid'],
                            'doc_submited' => $frm_details['doc_submited'],
                            'id_number' => $frm_details['id_number'],
                            'created_on' => date(DB_DATE_FORMAT),
                            'modified_on' => date(DB_DATE_FORMAT),
                     
                        );
                  
                    $result = $this->Credit_report_model->save(array_map('strtolower', $field_array),array('id'=>$frm_details['update_id']));
                  
                    $error_msgs = array();
                    $file_upload_path = SITE_BASE_PATH . EMPLOYMENT . $frm_details['clientid'];
                    if (!folder_exist($file_upload_path)) {
                        mkdir($file_upload_path, 0777,true);
                    } else if (!is_writable($file_upload_path)) {
                        array_push($error_msgs, 'Problem while uploading');
                    }  
              
                    $config_array = array('file_upload_path' => $file_upload_path, 'file_permission' => 'jpeg|jpg|png|pdf|docx|xls|xlsx', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 1000, 'file_id' => $frm_details['update_id'], 'component_name' => 'credit_report_id');

                    if (empty($error_msgs)) {
                        if ($_FILES['attchments']['name'][0] != '') {
                            $config_array['files_count'] = count($_FILES['attchments']['name']);
                            $config_array['file_data'] = $_FILES['attchments'];
                            $config_array['type'] = 0;
                            $retunr_de = $this->file_upload_multiple($config_array);
                            if (!empty($retunr_de)) {
                                $this->common_model->common_insert_batch('credit_report_files', $retunr_de['success']);
                            }
                        }

                    }

                    
                    if ($result) {
                        
                        $update_candidate_info = $this->Credit_report_model->update_status('client_candidates_info',array('credit_report_component_check'=> '0'),array('cands_info_id'=> $frm_details['candsid']));
                       

                        $check = $this->Credit_report_model->select_insuff(array('credit_report_id' =>  $frm_details['update_id'], 'status !=' => 3, 'insuff_clear_date is null' => null));
                        
                        if(!empty($check))
                        { 

                            $insuff_clear_date = date(DATE_ONLY);

                            $hold_days = getNetWorkDays($check[0]['insuff_raised_date'],$insuff_clear_date);

                            $date_tat = $this->Credit_report_model->get_date_for_update(array('id' => $frm_details['update_id']));

                            $due_date = $date_tat[0]['due_date'];

                            $today = date("Y-m-d");

                            $get_holiday1 = $this->get_holiday();

                            $get_holiday = array_map('current', $get_holiday1);

                            $closed_date = getWorkingDays_increament($due_date, $get_holiday, $hold_days);

                            $x = 1;
                            $counter_1 = 0;
                            while ($x <= 10) {

                                $yesterday = date('Y-m-d', strtotime('-' . $x . ' day', strtotime($closed_date)));

                                $today_date1 = strtotime($yesterday);

                                $day_name1 = date("D", $today_date1);

                                if ($day_name1 == 'Sat' || $day_name1 == 'Sun' || in_array($yesterday, $get_holiday) == true) {

                                    $counter_1 += 1;
                                } else {
                                    break;
                                }

                                $x++;
                            }

                            $yesterday_main = $yesterday;

                            $y = 1;
                            $counter_2 = 0;
                            while ($y <= 10) {

                                $yesterday_ago = date('Y-m-d', strtotime('-' . $y . ' day', strtotime($yesterday_main)));

                                $today_date2 = strtotime($yesterday_ago);

                                $day_name2 = date("D", $today_date2);

                                if ($day_name2 == 'Sat' || $day_name2 == 'Sun' || in_array($yesterday_ago, $get_holiday) == true) {

                                    $counter_2 += 1;
                                } else {
                                    break;
                                }
                                $y++;
                            }

                            $yesterday_ago_main = $yesterday_ago;

                            $date_array = array($yesterday_main, $yesterday_ago_main);

                            if ($closed_date < $today) {
                                $new_tat = "OUT TAT";
                            } elseif ($closed_date == $today) {
                                $new_tat = "TDY TAT";
                            } elseif (in_array(date("Y-m-d"), $date_array) == true) {
                                $new_tat = "AP TAT";
                            } else {
                                $new_tat = "IN TAT";
                            }

                            $result_due_date = $this->Credit_report_model->update_due_date(array('due_date' => $closed_date, 'tat_status' => $new_tat), array('id' => $frm_details['update_id']));

                            $fields = array('insuff_clear_date' => $insuff_clear_date, 'insuff_remarks' => "Candidate Information Added", 'insuff_cleared_timestamp' => date(DB_DATE_FORMAT), 'status' => 2, 'auto_stamp' => 2, 'hold_days' => $hold_days);

                            $result = $this->Credit_report_model->save_update_insuff($fields, array('credit_report_id' => $frm_details['update_id']));
                        }

                        $component_status_update = $this->common_model->component_stat_update(array('credit_report_component_check'=>'0'),array('cands_info_id'=>$frm_details['candsid']));

                        auto_update_client_candidate_status($frm_details['candsid']); 
                        $this->component_save_candidate_mail_update('Credit Report',$frm_details); 


                        $json_array['status'] = SUCCESS_CODE;

                        $json_array['message'] = 'Credit Report Record Successfully Inserted';
                           
                    } else {
                        $json_array['status'] = ERROR_CODE;

                        $json_array['message'] = 'Something went wrong,please try again';
                    }
                   
      
            }
             echo_json($json_array);
        }
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

    public function save_identity()
    {
        if ($this->input->is_ajax_request()) {

            $frm_details = $this->input->post();
            
            $this->load->model('identity_model');

            
            $this->form_validation->set_rules('clientid', 'Client', 'required');

            $this->form_validation->set_rules('candsid', 'Candidate', 'required');

            $this->form_validation->set_rules('doc_submited', 'Document', 'required');

            $this->form_validation->set_rules('id_number', 'Id Number', 'required');

            if ($this->form_validation->run() == false ) {

                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');

            } else {

                $identity_id = $this->identity_model->check_identity_exists_in_candidate(array('doc_submited'  => $frm_details['doc_submited'],'candsid' => $frm_details['candsid']));

                if(empty($identity_id))
                { 

                    $tat_day = $this->get_client_entity_package_tat_day(array('tbl_clients_id' => $frm_details['clientid'], 'entity' => $frm_details['entity_id'], 'package' => $frm_details['package_id']));

                    $get_holiday1 = $this->get_holiday();

                    $get_holiday = array_map('current', $get_holiday1);

                    $closed_date = getWorkingDays(convert_display_to_db_date($frm_details['iniated_date']), $get_holiday, $tat_day[0]['tat_identity']);

                    $has_case_id = $this->identity_model->get_reporting_manager_id($frm_details['clientid']);
                    
                        $field_array = array('clientid' => $frm_details['clientid'],
                            'candsid' => $frm_details['candsid'],
                            'identity_com_ref' => '',
                            'doc_submited' => $frm_details['doc_submited'],
                            'id_number' => $frm_details['id_number'],
                            'mode_of_veri' => $frm_details['mod_of_veri'],
                            'created_on' => date(DB_DATE_FORMAT),
                            'modified_on' => date(DB_DATE_FORMAT),
                            'is_bulk_uploaded' => 0,
                            'iniated_date' => convert_display_to_db_date($frm_details['iniated_date']),
                            "due_date" => $closed_date,
                            'has_case_id' => $has_case_id[0]['clientmgr'],
                            "tat_status" => 'IN TAT',
                            'fill_by' => 2,
                        );

                   

                    $result = $this->identity_model->save(array_map('strtolower', $field_array));

                    $identity_com_ref = $this->identity_com_ref($result);

                    $error_msgs = array();
                    $file_upload_path = SITE_BASE_PATH . IDENTITY . $frm_details['clientid'];
                    if (!folder_exist($file_upload_path)) {
                        mkdir($file_upload_path, 0777,true);
                    } else if (!is_writable($file_upload_path)) {
                        array_push($error_msgs, 'Problem while uploading');
                    }

            
                    $config_array = array('file_upload_path' => $file_upload_path, 'file_permission' => 'jpeg|jpg|png|pdf|docx|xls|xlsx', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 1000, 'file_id' => $result, 'component_name' => 'identity_id');
                    if (empty($error_msgs)) {
                        if ($_FILES['attchments']['name'][0] != '') {
                            $config_array['files_count'] = count($_FILES['attchments']['name']);
                            $config_array['file_data'] = $_FILES['attchments'];
                            $config_array['type'] = 0;
                            $retunr_de = $this->file_upload_multiple($config_array);
                            if (!empty($retunr_de)) {
                                $this->common_model->common_insert_batch('identity_files', $retunr_de['success']);
                            }
                        }
                    }
                

                    if ($result) {

                            $field = array('candsid' => $frm_details['candsid'],
                                'ClientRefNumber' => '',
                                'comp_table_id' => $result,
                                'activity_mode' => '',
                                'activity_status' => 'New check',
                                'activity_type' => 'New check',
                                'action' => 'New check',
                                'next_follow_up_date' => null,
                                'remarks' => 'New Check Added by  Client ' . $frm_details['clientname'],
                                'created_on' => date(DB_DATE_FORMAT),
                                'is_auto_filled' => 0,

                            );
                      
                        if ($result) {
                            $component_status_update = $this->common_model->component_stat_update(array('identity_component_check'=>'0'),array('cands_info_id'=>$frm_details['candsid']));

                            auto_update_client_candidate_status($frm_details['candsid']);
                            $this->component_save_candidate_mail_update('Identity',$frm_details);  

                            $json_array['status'] = SUCCESS_CODE;

                            $json_array['message'] = 'Identity Record Successfully Inserted';

                               
                        } else {
                            $json_array['status'] = ERROR_CODE;

                            $json_array['message'] = 'Something went wrong,please try again';
                        }

                        
                    } else {
                        $json_array['status'] = ERROR_CODE;

                        $json_array['message'] = 'Something went wrong,please try again';
                    }
                
                } else {
                        $json_array['status'] = ERROR_CODE;

                        $json_array['message'] = 'Already Exists '.$frm_details['doc_submited'];
                }
            }
             echo_json($json_array);
        }
    }

    public function update_identity()
    {
        if ($this->input->is_ajax_request()) {

            $frm_details = $this->input->post();
            
            $this->load->model('identity_model');

            $this->form_validation->set_rules('clientid', 'Client', 'required');

            $this->form_validation->set_rules('candsid', 'Candidate', 'required');

            $this->form_validation->set_rules('doc_submited', 'Document', 'required');

            $this->form_validation->set_rules('id_number', 'Id Number', 'required');

            if ($this->form_validation->run() == false ) {

                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');

            } else {

                    
                    $field_array = array('clientid' => $frm_details['clientid'],
                            'candsid' => $frm_details['candsid'],
                            'doc_submited' => $frm_details['doc_submited'],
                            'id_number' => $frm_details['id_number'],
                            'created_on' => date(DB_DATE_FORMAT),
                            'modified_on' => date(DB_DATE_FORMAT)
                        );

                    $result = $this->identity_model->save(array_map('strtolower', $field_array),array('id' => $frm_details['update_id']));

                    $error_msgs = array();
                    $file_upload_path = SITE_BASE_PATH . IDENTITY . $frm_details['clientid'];
                    if (!folder_exist($file_upload_path)) {
                        mkdir($file_upload_path, 0777,true);
                    } else if (!is_writable($file_upload_path)) {
                        array_push($error_msgs, 'Problem while uploading');
                    }

                    $config_array = array('file_upload_path' => $file_upload_path, 'file_permission' => 'jpeg|jpg|png|pdf|docx|xls|xlsx', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 1000, 'file_id' => $frm_details['update_id'], 'component_name' => 'identity_id');

                    if (empty($error_msgs)) {
                        if ($_FILES['attchments']['name'][0] != '') {
                            $config_array['files_count'] = count($_FILES['attchments']['name']);
                            $config_array['file_data'] = $_FILES['attchments'];
                            $config_array['type'] = 0;
                            $retunr_de = $this->file_upload_multiple($config_array);
                            if (!empty($retunr_de)) {
                                $this->common_model->common_insert_batch('identity_files', $retunr_de['success']);
                            }
                        }
                    }
                

                    if ($result) {

                        $update_candidate_info = $this->identity_model->update_status('client_candidates_info',array('identity_component_check'=> '0'),array('cands_info_id'=> $frm_details['candsid']));

                        $check = $this->identity_model->select_insuff(array('identity_id' =>  $frm_details['update_id'], 'status !=' => 3, 'insuff_clear_date is null' => null));
             
                        if(!empty($check))
                        {
                           
                            $insuff_clear_date = date(DATE_ONLY);

                            $hold_days = getNetWorkDays($check[0]['insuff_raised_date'], $insuff_clear_date);

                            $date_tat = $this->identity_model->get_date_for_update(array('id' => $frm_details['update_id']));

                            $due_date = $date_tat[0]['due_date'];

                            $today = date("Y-m-d");

                            $get_holiday1 = $this->get_holiday();

                            $get_holiday = array_map('current', $get_holiday1);

                            $closed_date = getWorkingDays_increament($due_date, $get_holiday, $hold_days);

                            $x = 1;
                            $counter_1 = 0;
                            while ($x <= 10) {

                                $yesterday = date('Y-m-d', strtotime('-' . $x . ' day', strtotime($closed_date)));

                                $today_date1 = strtotime($yesterday);

                                $day_name1 = date("D", $today_date1);

                                if ($day_name1 == 'Sat' || $day_name1 == 'Sun' || in_array($yesterday, $get_holiday) == true) {

                                    $counter_1 += 1;
                                } else {
                                    break;
                                }

                                $x++;
                            }

                            $yesterday_main = $yesterday;

                            $y = 1;
                            $counter_2 = 0;
                            while ($y <= 10) {

                                $yesterday_ago = date('Y-m-d', strtotime('-' . $y . ' day', strtotime($yesterday_main)));

                                $today_date2 = strtotime($yesterday_ago);

                                $day_name2 = date("D", $today_date2);

                                if ($day_name2 == 'Sat' || $day_name2 == 'Sun' || in_array($yesterday_ago, $get_holiday) == true) {

                                    $counter_2 += 1;
                                } else {
                                    break;
                                }
                                $y++;
                            }

                            $yesterday_ago_main = $yesterday_ago;

                            $date_array = array($yesterday_main, $yesterday_ago_main);

                            if ($closed_date < $today) {
                                $new_tat = "OUT TAT";
                            } elseif ($closed_date == $today) {
                                $new_tat = "TDY TAT";
                            } elseif (in_array(date("Y-m-d"), $date_array) == true) {
                                $new_tat = "AP TAT";
                            } else {
                                $new_tat = "IN TAT";
                            }

                            $result_due_date = $this->identity_model->update_due_date(array('due_date' => $closed_date, 'tat_status' => $new_tat), array('id' => $frm_details['update_id']));

                            $fields = array('insuff_clear_date' => $insuff_clear_date, 'insuff_remarks' =>"Candidate Fill Details", 'insuff_cleared_timestamp' => date(DB_DATE_FORMAT), 'status' => 2,'auto_stamp' => 2, 'hold_days' => $hold_days);

                            $result = $this->identity_model->save_update_insuff($fields, array('identity_id' => $frm_details['update_id']));
                        }
    
                        $component_status_update = $this->common_model->component_stat_update(array('identity_component_check'=>'0'),array('cands_info_id'=>$frm_details['candsid']));

                        auto_update_client_candidate_status($frm_details['candsid']); 
                        $this->component_save_candidate_mail_update('Identity',$frm_details); 

                        $json_array['status'] = SUCCESS_CODE;

                        $json_array['message'] = 'Identity Record Successfully Inserted';
   
                    } else {
                        $json_array['status'] = ERROR_CODE;

                        $json_array['message'] = 'Something went wrong,please try again';
                    }
                
               
            }
             echo_json($json_array);
        }
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

    public function save_education()
    {
        if ($this->input->is_ajax_request()) {
  
            $this->load->model('education_model');
          
            $frm_details = $this->input->post();
         
            $this->form_validation->set_rules('clientid', 'Client', 'required');

            $this->form_validation->set_rules('candsid', 'Candidate', 'required');

            
            if ($this->form_validation->run() == false) {

                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');

            } else {
               
                $education_id = $this->education_model->check_education_exists_in_candidate(array('qualification'  => $frm_details['qualification'],'candsid' => $frm_details['candsid']));

                if(empty($education_id))
                { 

                    $tat_day = $this->get_client_entity_package_tat_day(array('tbl_clients_id' => $frm_details['clientid'], 'entity' => $frm_details['entity_id'], 'package' => $frm_details['package_id']));

                    $get_holiday1 = $this->get_holiday();

                    $get_holiday = array_map('current', $get_holiday1);

                    $closed_date = getWorkingDays(convert_display_to_db_date($frm_details['iniated_date']), $get_holiday, $tat_day[0]['tat_eduver']);

                    $has_case_id = $this->education_model->get_reporting_manager_id_client($frm_details['clientid']);

                        $field_array = array('clientid' => $frm_details['clientid'],
                            'candsid' => $frm_details['candsid'],
                            'education_com_ref' => '',
                            'iniated_date' => convert_display_to_db_date($frm_details['iniated_date']),
                            'school_college' => $frm_details['school_college'],
                            'university_board' => $frm_details['university_board'],
                            'grade_class_marks' => $frm_details['grade_class_marks'],
                            'qualification' => $frm_details['qualification'],
                            'major' => $frm_details['major'],
                            'course_start_date' => convert_display_to_db_date($frm_details['course_start_date']),
                            'course_end_date' => convert_display_to_db_date($frm_details['course_end_date']),
                            'month_of_passing' => $frm_details['month_of_passing'],
                            'year_of_passing' => $frm_details['year_of_passing'],
                            'roll_no' => $frm_details['roll_no'],
                            'enrollment_no' => $frm_details['enrollment_no'],
                            'PRN_no' => $frm_details['PRN_no'],
                            'documents_provided' => json_encode($frm_details['documents_provided']),
                            'city' => $frm_details['city'],
                            'state' => $frm_details['state'],
                            'mode_of_veri' => $frm_details['mod_of_veri'],
                            'city' => $frm_details['city'],
                            'state' => $frm_details['state'],
                            'created_on' => date(DB_DATE_FORMAT),
                            'modified_on' => date(DB_DATE_FORMAT),
                            "due_date" => $closed_date,
                            'has_case_id' => $has_case_id[0]['clientmgr'],
                            "tat_status" => 'IN TAT',
                            'fill_by' => 2,
                        );

        

                    $result = $this->education_model->save(array_map('strtolower', $field_array));

                    $education_com_ref = $this->education_com_ref($result);

                    $error_msgs = array();
                    $file_upload_path = SITE_BASE_PATH . EDUCATION . $frm_details['clientid'];
                    if (!folder_exist($file_upload_path)) {
                        mkdir($file_upload_path, 0777,true);
                    } else if (!is_writable($file_upload_path)) {
                        array_push($error_msgs, 'Problem while uploading');
                    }

                    $config_array = array('file_upload_path' => $file_upload_path, 'file_permission' => 'jpeg|jpg|png|pdf|docx|xls|xlsx', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 1000, 'file_id' => $result, 'component_name' => 'education_id');
                    if (empty($error_msgs)) {
                        if ($_FILES['attchments']['name'][0] != '') {
                            $config_array['files_count'] = count($_FILES['attchments']['name']);
                            $config_array['file_data'] = $_FILES['attchments'];
                            $config_array['type'] = 0;
                            $config_array['education_id'] =  $education_com_ref;
                            $retunr_de = $this->file_upload_library->file_upload_multiple_education($config_array, true);
                           // $retunr_de = $this->file_upload_multiple($config_array);
                            if (!empty($retunr_de)) {
                                $this->common_model->common_insert_batch('education_files', $retunr_de['success']);
                            }
                        }
                    }
                  

                    if ($result) {

                            $field = array('candsid' => $frm_details['candsid'],
                                'ClientRefNumber' => '',
                                'comp_table_id' => $result,
                                'activity_mode' => '',
                                'activity_status' => 'New check',
                                'activity_type' => 'New check',
                                'action' => 'New check',
                                'next_follow_up_date' => null,
                                'remarks' => 'New Check Added by  Client ' . $frm_details['clientname'],
                                'created_on' => date(DB_DATE_FORMAT),
                                'is_auto_filled' => 0,

                            );
                      

                            if ($result) {
                               $component_status_update = $this->common_model->component_stat_update(array('education_component_check'=>'0'),array('cands_info_id'=>$frm_details['candsid']));


                                if($frm_details['university_board'] != "2476")
                                {

                                    $select_vendor_details = $this->education_model->select_education('university_master', array('*'),array('university_master.id'=>$frm_details['university_board']));

                                    if(!empty($select_vendor_details[0]['vendor_id']) && !empty($select_vendor_details[0]['year_of_passing']))
                                    {
                                      
                                        if(( $frm_details['year_of_passing']  >= $select_vendor_details[0]['year_of_passing']) && ($select_vendor_details[0]['id'] ==  $frm_details['university_board']))
                                        {    

                                            $update = $this->education_model->upload_vendor_assign('education', array('vendor_id' => $select_vendor_details[0]['vendor_id'],'verifiers_spoc_status' => 2,  'vendor_assgined_on' => date(DB_DATE_FORMAT)), array('id' => $result, 'vendor_id =' => 0));

                                            $update1 = $this->education_model->update_status('education_result', array('verfstatus' => 1), array('education_id' => $result));

                                            $get_vendorname = $this->education_model->get_vendor_details(array('id'=>$select_vendor_details[0]['vendor_id']));
                                            if ($update) {

                                                $files[] = array(
                                                    'vendor_id' => $select_vendor_details[0]['vendor_id'],
                                                    'case_id' => $result,
                                                    "status" => 0,
                                                    "remarks" => '',
                                                    "created_by" => 1,
                                                    "created_on" => date(DB_DATE_FORMAT),
                                                    "approval_by" => 1,
                                                    "modified_on" =>  date(DB_DATE_FORMAT),
                                                    "modified_by" => 1,
                                                );

                                                $activity[]  =  array(
                                                    'comp_table_id' => $result,
                                                    'activity_status' => 'Assign',
                                                    "activity_type" => 'Mist it',
                                                    "remarks" => 'system has assigned the case to '.$get_vendorname[0]['vendor_name'],
                                                    "created_by" => 1,
                                                    "created_on" => date(DB_DATE_FORMAT),
                                                    "is_auto_filled" => 0
                                                            
                                                );
                                            }
                                           
                                        }
                                        else{

                                            $update = $this->education_model->upload_vendor_assign('education', array('vendor_id' => 20,'verifiers_spoc_status' => 1,  'vendor_assgined_on' => date(DB_DATE_FORMAT)), array('id' => $result, 'vendor_id =' => 0));

                                            $update1 = $this->education_model->update_status('education_result', array('verfstatus' => 1), array('education_id' => $result));

                                            
                                            $get_vendorname = $this->education_model->get_vendor_details(array('id'=>20));
                                            if ($update) {

                                                $files[] = array(
                                                    'vendor_id' => 20,
                                                    'case_id' => $result,
                                                    "status" => 0,
                                                    "remarks" => '',
                                                    "created_by" => 1,
                                                    "created_on" => date(DB_DATE_FORMAT),
                                                    "approval_by" => 0,
                                                    "modified_on" => null,
                                                    "modified_by" => '',
                                                );

                                                $activity[]  =  array(
                                                    'comp_table_id' => $result,
                                                    'activity_status' => 'Assign',
                                                    "activity_type" => 'Mist it',
                                                    "remarks" => 'system has assigned the case to '.$get_vendorname[0]['vendor_name'],
                                                    "created_by" => 1,
                                                    "created_on" => date(DB_DATE_FORMAT),
                                                    "is_auto_filled" => 0
                                                            
                                                );
                                            }
                                  
                                        }
                                    }
                                    else{

                                            $update = $this->education_model->upload_vendor_assign('education', array('vendor_id' => 20,'verifiers_spoc_status' => 1,  'vendor_assgined_on' => date(DB_DATE_FORMAT)), array('id' => $result, 'vendor_id =' => 0));

                                            $update1 = $this->education_model->update_status('education_result', array('verfstatus' => 1), array('education_id' => $result));

                                           
                                            $get_vendorname = $this->education_model->get_vendor_details(array('id'=>20));
                                            if ($update) {

                                                $files[] = array(
                                                    'vendor_id' => 20,
                                                    'case_id' => $result,
                                                    "status" => 0,
                                                    "remarks" => '',
                                                    "created_by" => 1,
                                                    "created_on" => date(DB_DATE_FORMAT),
                                                    "approval_by" => 0,
                                                    "modified_on" => null,
                                                    "modified_by" => '',
                                                );

                                                $activity[]  =  array(
                                                    'comp_table_id' => $result,
                                                    'activity_status' => 'Assign',
                                                    "activity_type" => 'Mist it',
                                                    "remarks" => 'system has assigned the case to '.$get_vendorname[0]['vendor_name'],
                                                    "created_by" => 1,
                                                    "created_on" => date(DB_DATE_FORMAT),
                                                    "is_auto_filled" => 0
                                                            
                                                );
                                            }
                                  
                                    }

                                    if (!empty($files)) {
                                        $inserted = $this->common_model->common_insert_batch('education_vendor_log', $files);
                                        $activity_insert = $this->common_model->common_insert_batch('education_activity_data', $activity);
                                    }
                                }


                                auto_update_client_candidate_status($frm_details['candsid']);
                                $this->component_save_candidate_mail_update('Education',$frm_details);  


                                $json_array['status'] = SUCCESS_CODE;

                                $json_array['message'] = 'Education Record Successfully Inserted';

                            } else {

                                $json_array['status'] = ERROR_CODE;

                                $json_array['message'] = 'Something went wrong,please try again';
                            }

                    } else {
                        $json_array['status'] = ERROR_CODE;

                        $json_array['message'] = 'Something went wrong,please try again';
                    }
                
                } else {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Already Exists Qualification';
                }
            }
             echo_json($json_array);
        }
    }

    public function update_education()
    {
        if ($this->input->is_ajax_request()) {
  
            $this->load->model('education_model');
          
            $frm_details = $this->input->post();
         
            $this->form_validation->set_rules('clientid', 'Client', 'required');

            $this->form_validation->set_rules('candsid', 'Candidate', 'required');

            
            if ($this->form_validation->run() == false) {

                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');

            } else {
            

                        $field_array = array('clientid' => $frm_details['clientid'],
                            'candsid' => $frm_details['candsid'],
                            'school_college' => $frm_details['school_college'],
                            'university_board' => $frm_details['university_board'],
                            'grade_class_marks' => $frm_details['grade_class_marks'],
                            'qualification' => $frm_details['qualification'],
                            'major' => $frm_details['major'],
                            'course_start_date' => convert_display_to_db_date($frm_details['course_start_date']),
                            'course_end_date' => convert_display_to_db_date($frm_details['course_end_date']),
                            'month_of_passing' => $frm_details['month_of_passing'],
                            'year_of_passing' => $frm_details['year_of_passing'],
                            'roll_no' => $frm_details['roll_no'],
                            'enrollment_no' => $frm_details['enrollment_no'],
                            'PRN_no' => $frm_details['PRN_no'],
                            'documents_provided' => json_encode($frm_details['documents_provided']),
                            'city' => $frm_details['city'],
                            'state' => $frm_details['state'],
                            'city' => $frm_details['city'],
                            'state' => $frm_details['state'],
                            'created_on' => date(DB_DATE_FORMAT),
                            'modified_on' => date(DB_DATE_FORMAT)
                        );

                    $result = $this->education_model->save(array_map('strtolower', $field_array),array('id'=>$frm_details['update_id']));

                    $error_msgs = array();
                    $file_upload_path = SITE_BASE_PATH . EDUCATION . $frm_details['clientid'];
                    if (!folder_exist($file_upload_path)) {
                        mkdir($file_upload_path, 0777,true);
                    } else if (!is_writable($file_upload_path)) {
                        array_push($error_msgs, 'Problem while uploading');
                    }

                    $config_array = array('file_upload_path' => $file_upload_path, 'file_permission' => 'jpeg|jpg|png|pdf|docx|xls|xlsx', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 1000, 'file_id' => $frm_details['update_id'], 'component_name' => 'education_id');

                    if (empty($error_msgs)) {
                        if ($_FILES['attchments']['name'][0] != '') {
                            $config_array['files_count'] = count($_FILES['attchments']['name']);
                            $config_array['file_data'] = $_FILES['attchments'];
                            $config_array['type'] = 0;
                            $config_array['education_id'] =  $frm_details['education_com_ref'];
                             
                            $retunr_de = $this->file_upload_library->file_upload_multiple_education($config_array, true);
                             
                           // $retunr_de = $this->file_upload_multiple($config_array);
                            if (!empty($retunr_de)) {
                                $this->common_model->common_insert_batch('education_files', $retunr_de['success']);
                            }
                        }
                    }
                  

                    if ($result) {

                        $update_candidate_info = $this->education_model->update_status('client_candidates_info',array('education_component_check'=> '0'),array('cands_info_id'=> $frm_details['candsid']));

                        $insuff_clear_date = date(DATE_ONLY);

                        $check = $this->education_model->select_insuff(array('education_id' =>  $frm_details['update_id'], 'status !=' => 3, 'insuff_clear_date is null' => null));
                        
                        if(!empty($check))
                        {
                               
                            $hold_days = getNetWorkDays($check[0]['insuff_raised_date'], $insuff_clear_date);

                            $date_tat = $this->education_model->get_date_for_update(array('id' => $frm_details['update_id']));

                            $due_date = $date_tat[0]['due_date'];

                            $today = date("Y-m-d");

                            $get_holiday1 = $this->get_holiday();

                            $get_holiday = array_map('current', $get_holiday1);

                            $closed_date = getWorkingDays_increament($due_date, $get_holiday, $hold_days);

                            $x = 1;
                            $counter_1 = 0;
                            while ($x <= 10) {

                                $yesterday = date('Y-m-d', strtotime('-' . $x . ' day', strtotime($closed_date)));

                                $today_date1 = strtotime($yesterday);

                                $day_name1 = date("D", $today_date1);

                                if ($day_name1 == 'Sat' || $day_name1 == 'Sun' || in_array($yesterday, $get_holiday) == true) {

                                    $counter_1 += 1;
                                } else {
                                    break;
                                }

                                $x++;
                            }

                            $yesterday_main = $yesterday;

                            $y = 1;
                            $counter_2 = 0;
                            while ($y <= 10) {

                                $yesterday_ago = date('Y-m-d', strtotime('-' . $y . ' day', strtotime($yesterday_main)));

                                $today_date2 = strtotime($yesterday_ago);

                                $day_name2 = date("D", $today_date2);

                                if ($day_name2 == 'Sat' || $day_name2 == 'Sun' || in_array($yesterday_ago, $get_holiday) == true) {

                                    $counter_2 += 1;
                                } else {
                                    break;
                                }
                                $y++;
                            }

                            $yesterday_ago_main = $yesterday_ago;

                            $date_array = array($yesterday_main, $yesterday_ago_main);

                            if ($closed_date < $today) {
                                $new_tat = "OUT TAT";
                            } elseif ($closed_date == $today) {
                                $new_tat = "TDY TAT";
                            } elseif (in_array(date("Y-m-d"), $date_array) == true) {
                                $new_tat = "AP TAT";
                            } else {
                                $new_tat = "IN TAT";
                            }

                            $result_due_date = $this->education_model->update_due_date(array('due_date' => $closed_date, 'tat_status' => $new_tat), array('id' => $frm_details['update_id']));

                            $fields = array('insuff_clear_date' => $insuff_clear_date, 'insuff_remarks' =>"Candidate Fill Details", 'insuff_cleared_timestamp' => date(DB_DATE_FORMAT), 'status' => 2, 'auto_stamp' => 2, 'hold_days' => $hold_days);

                            $result = $this->education_model->save_update_insuff($fields, array('education_id' => $frm_details['update_id']));
                        }

                            $component_status_update = $this->common_model->component_stat_update(array('education_component_check'=>'0'),array('cands_info_id'=>$frm_details['candsid']));


                            if($frm_details['university_board'] != "2476")
                            {

                                $select_vendor_details = $this->education_model->select_education('university_master', array('*'),array('university_master.id'=>$frm_details['university_board']));

                                if(!empty($select_vendor_details[0]['vendor_id']) && !empty($select_vendor_details[0]['year_of_passing']))
                                {
                                  
                                    if(( $frm_details['year_of_passing']  >= $select_vendor_details[0]['year_of_passing']) && ($select_vendor_details[0]['id'] ==  $frm_details['university_board']))
                                    {    

                                        $update = $this->education_model->upload_vendor_assign('education', array('vendor_id' => $select_vendor_details[0]['vendor_id'],'verifiers_spoc_status' => 2,  'vendor_assgined_on' => date(DB_DATE_FORMAT)), array('id' => $frm_details['update_id'], 'vendor_id =' => 0));

                                        $update1 = $this->education_model->update_status('education_result', array('verfstatus' => 1), array('education_id' => $frm_details['update_id']));

                                        $get_vendorname = $this->education_model->get_vendor_details(array('id'=>$select_vendor_details[0]['vendor_id']));
                                        if ($update) {

                                            $files[] = array(
                                                'vendor_id' => $select_vendor_details[0]['vendor_id'],
                                                'case_id' => $frm_details['update_id'],
                                                "status" => 0,
                                                "remarks" => '',
                                                "created_by" => 1,
                                                "created_on" => date(DB_DATE_FORMAT),
                                                "approval_by" => 1,
                                                "modified_on" =>  date(DB_DATE_FORMAT),
                                                "modified_by" => 1,
                                            );

                                            $activity[]  =  array(
                                                'comp_table_id' => $frm_details['update_id'],
                                                'activity_status' => 'Assign',
                                                "activity_type" => 'Mist it',
                                                "remarks" => 'system has assigned the case to '.$get_vendorname[0]['vendor_name'],
                                                "created_by" => 1,
                                                "created_on" => date(DB_DATE_FORMAT),
                                                "is_auto_filled" => 0
                                                        
                                            );
                                        }
                                       
                                    }
                                    else{

                                        $update = $this->education_model->upload_vendor_assign('education', array('vendor_id' => 20,'verifiers_spoc_status' => 1,  'vendor_assgined_on' => date(DB_DATE_FORMAT)), array('id' => $frm_details['update_id'], 'vendor_id =' => 0));

                                        $update1 = $this->education_model->update_status('education_result', array('verfstatus' => 1), array('education_id' => $frm_details['update_id']));

                                        
                                        $get_vendorname = $this->education_model->get_vendor_details(array('id'=>20));
                                        if ($update) {

                                            $files[] = array(
                                                'vendor_id' => 20,
                                                'case_id' => $frm_details['update_id'],
                                                "status" => 0,
                                                "remarks" => '',
                                                "created_by" => 1,
                                                "created_on" => date(DB_DATE_FORMAT),
                                                "approval_by" => 0,
                                                "modified_on" => null,
                                                "modified_by" => '',
                                            );

                                            $activity[]  =  array(
                                                'comp_table_id' => $frm_details['update_id'],
                                                'activity_status' => 'Assign',
                                                "activity_type" => 'Mist it',
                                                "remarks" => 'system has assigned the case to '.$get_vendorname[0]['vendor_name'],
                                                "created_by" => 1,
                                                "created_on" => date(DB_DATE_FORMAT),
                                                "is_auto_filled" => 0
                                                        
                                            );
                                        }
                              
                                    }
                                }
                                else{

                                        $update = $this->education_model->upload_vendor_assign('education', array('vendor_id' => 20,'verifiers_spoc_status' => 1,  'vendor_assgined_on' => date(DB_DATE_FORMAT)), array('id' => $frm_details['update_id'], 'vendor_id =' => 0));

                                        $update1 = $this->education_model->update_status('education_result', array('verfstatus' => 1), array('education_id' => $frm_details['update_id']));

                                       
                                        $get_vendorname = $this->education_model->get_vendor_details(array('id'=>20));
                                        if ($update) {

                                            $files[] = array(
                                                'vendor_id' => 20,
                                                'case_id' => $frm_details['update_id'],
                                                "status" => 0,
                                                "remarks" => '',
                                                "created_by" => 1,
                                                "created_on" => date(DB_DATE_FORMAT),
                                                "approval_by" => 0,
                                                "modified_on" => null,
                                                "modified_by" => '',
                                            );

                                            $activity[]  =  array(
                                                'comp_table_id' => $frm_details['update_id'],
                                                'activity_status' => 'Assign',
                                                "activity_type" => 'Mist it',
                                                "remarks" => 'system has assigned the case to '.$get_vendorname[0]['vendor_name'],
                                                "created_by" => 1,
                                                "created_on" => date(DB_DATE_FORMAT),
                                                "is_auto_filled" => 0
                                                        
                                            );
                                        }
                              
                                }

                                if (!empty($files)) {
                                    $inserted = $this->common_model->common_insert_batch('education_vendor_log', $files);
                                    $activity_insert = $this->common_model->common_insert_batch('education_activity_data', $activity);
                                }
                            }

                            auto_update_client_candidate_status($frm_details['candsid']);
                            $this->component_save_candidate_mail_update('Education',$frm_details);
                             


                            $json_array['status'] = SUCCESS_CODE;

                            $json_array['message'] = 'Education Record Successfully Inserted';

                        } else {
                            $json_array['status'] = ERROR_CODE;

                            $json_array['message'] = 'Something went wrong,please try again';
                        }
                   }
               
             echo_json($json_array);
        }
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

    protected function emp_com_ref($insert_id)
    {
        $this->load->model('employment_model');

        $name = COMPONENT_REF_NO['EMPLOYMENT'];

        $employmentnumber = $name . $insert_id;

        $field_array = array('emp_com_ref' => $employmentnumber);

        $update_auto_increament_id = $this->employment_model->update_auto_increament_value($field_array, array('id' => $insert_id));

        return $employmentnumber;
    }

    public function save_employment()
    {
        if ($this->input->is_ajax_request()) {

            $this->load->model('employment_model');

            $frm_details = $this->input->post();

            $this->form_validation->set_rules('clientid', 'Client', 'required');

            $this->form_validation->set_rules('candsid', 'Candidate', 'required');


            if ($this->form_validation->run() == false ) {

                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');

            } else {
              
                
                $is_exit = $this->employment_model->check_cin_exist($frm_details['nameofthecompany']);

                $is_company_exit = $this->employment_model->check_company_exist(str_replace("-", ' ', $frm_details['selected_company_name']));

                if (!empty($is_exit)) {
                    $nameofthecompany = $is_exit[0]['id'];
                } else if (!empty($is_company_exit)) {
                    $nameofthecompany = $is_company_exit[0]['id'];
                } else {
                    $nameofthecompany = $this->employment_model->save_company_details(array('cin_number' => $frm_details['nameofthecompany'], 'coname' => str_replace('-', ' ', $frm_details['selected_company_name'])));
                } 

                $employment_id = $this->employment_model->check_employment_exists_in_candidate(array('nameofthecompany'  => $nameofthecompany,'empfrom' => $frm_details['empfrom'],'empto' => $frm_details['empto'],'candsid' => $frm_details['candsid']));

                if(empty($employment_id))
                { 
                
                    $tat_day = $this->get_client_entity_package_tat_day(array('tbl_clients_id' => $frm_details['clientid'], 'entity' => $frm_details['entity_id'], 'package' => $frm_details['package_id']));

                    $get_holiday1 = $this->get_holiday();

                    $get_holiday = array_map('current', $get_holiday1);

                    $closed_date = getWorkingDays(convert_display_to_db_date($frm_details['iniated_date']), $get_holiday, $tat_day[0]['tat_empver']);

                    $has_case_id = $this->employment_model->get_reporting_manager_id_client($frm_details['clientid']);
                    

                        $field_array = array('clientid' => $frm_details['clientid'],
                            'candsid' => $frm_details['candsid'],
                            'emp_com_ref' => '',
                            'empid' => $frm_details['empid'],
                            'nameofthecompany' => $nameofthecompany,
                            'deputed_company' => $frm_details['deputed_company'],
                            'employment_type' => $frm_details['employment_type'],
                            'locationaddr' => $frm_details['locationaddr'],
                            'citylocality' => $frm_details['citylocality'],
                            'mode_of_veri' => $frm_details['mod_of_veri'],
                            'pincode' => $frm_details['pincode'],
                            'state' => $frm_details['state'],
                            'compant_contact' => $frm_details['compant_contact'],
                            'compant_contact_name' => $frm_details['compant_contact_name'],
                            'compant_contact_designation' => $frm_details['compant_contact_designation'],
                            'compant_contact_email' => $frm_details['compant_contact_email'],
                            'empfrom' => $frm_details['empfrom'],
                            'empto' => $frm_details['empto'],
                            'designation' => $frm_details['designation'],
                            'remuneration' => $frm_details['remuneration'],
                            'iniated_date' => convert_display_to_db_date($frm_details['iniated_date']),
                            "reasonforleaving" => $frm_details['reasonforleaving'],
                            "emp_re_open_date" => '',
                            'r_manager_name' => $frm_details['r_manager_name'],
                            'r_manager_no' => $frm_details['r_manager_no'],
                            'r_manager_designation' => $frm_details['r_manager_designation'],
                            'r_manager_email' => $frm_details['r_manager_email'],
                            'created_on' => date(DB_DATE_FORMAT),
                            'modified_on' => date(DB_DATE_FORMAT),
                            'has_case_id' => $has_case_id[0]['clientmgr'],
                            "due_date" => $closed_date,
                            'is_bulk_uploaded' => 0,
                            'created_by' => 0,
                            "tat_status" => 'IN TAT',
                            'fill_by' => 2,
                        );

                     $lists = $this->employment_model->select_required_field(array('company_database.id' => $frm_details['nameofthecompany']));

                            $field_array['reject_status'] = 1;
                            if (!empty($lists[0])) {
                                if ($lists[0]['previous_emp_code'] == 1) {
                                    if ($frm_details['empid'] == "") {
                                        $field_array['reject_status'] = 2;
                                    }
                                }
                                if ($lists[0]['branch_location'] == 1) {
                                    if ($frm_details['citylocality'] == "") {
                                        $field_array['reject_status'] = 2;
                                    }
                                }
                               
                            }


                    $result = $this->employment_model->save(array_map('strtolower', $field_array));

                    $emp_com_ref = $this->emp_com_ref($result);

                    

                    $total_rows = count(array_filter($frm_details['supervisor_name']));

                        $x = 0;
                        $supervisor_array = array();
                        while ($x < $total_rows) {
                            if ($frm_details['supervisor_name']) {
                                array_push($supervisor_array,
                                    array(
                                        'supervisor_name' => $frm_details['supervisor_name'][$x],
                                        'supervisor_designation' => $frm_details['supervisor_designation'][$x],
                                        'supervisor_contact_details' => $frm_details['supervisor_contact_details'][$x],
                                        'supervisor_email_id' => $frm_details['supervisor_email_id'][$x],
                                        'status' => 1,
                                        'created_on' => date(DB_DATE_FORMAT),
                                        'empver_id' => $result,
                                    ));
                            }
                            $x++;
                        }

                        if (!empty($supervisor_array)) {
                            $this->employment_model->empver_supervisor_details($supervisor_array);
                        }

                        $error_msgs = array();
                        $file_upload_path = SITE_BASE_PATH . EMPLOYMENT . $frm_details['clientid'];
                        if (!folder_exist($file_upload_path)) {
                            mkdir($file_upload_path, 0777,true);
                        } else if (!is_writable($file_upload_path)) {
                            array_push($error_msgs, 'Problem while uploading');
                        }

                        $config_array = array('file_upload_path' => $file_upload_path, 'file_permission' => 'jpeg|jpg|png|pdf|docx|xls|xlsx', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 1000, 'file_id' => $result, 'component_name' => 'empver_id');

                        if (empty($error_msgs)) {
                            if ($_FILES['attchments']['name'][0] != '') {
                                $config_array['files_count'] = count($_FILES['attchments']['name']);
                                $config_array['file_data'] = $_FILES['attchments'];
                                $config_array['type'] = 0;
                                $retunr_de = $this->file_upload_multiple($config_array);
                                if (!empty($retunr_de)) {
                                    $this->common_model->common_insert_batch('empverres_files', $retunr_de['success']);
                                }
                            }

                        }
                
                        if ($result) {

                       
                            $field = array('candsid' => $frm_details['candsid'],
                                'ClientRefNumber' => '',
                                'comp_table_id' => $result,
                                'activity_mode' => '',
                                'activity_status' => 'New check',
                                'activity_type' => 'New check',
                                'action' => 'New check',
                                'next_follow_up_date' => null,
                                'remarks' => 'New Check Added by  Client ' . $frm_details['clientname'],
                                'created_on' => date(DB_DATE_FORMAT),
                                'is_auto_filled' => 0,

                            );
                       

                        if ($result) {
                            $component_status_update = $this->common_model->component_stat_update(array('employment_component_check'=>'0'),array('cands_info_id'=>$frm_details['candsid']));

                            auto_update_client_candidate_status($frm_details['candsid']);
                            $this->component_save_candidate_mail_update('Employment',$frm_details);  

                            if ($result) {

                                $json_array['status'] = SUCCESS_CODE;

                                $json_array['message'] = 'Employment Record Successfully Inserted';


                            } else {

                                $json_array['status'] = ERROR_CODE;

                                $json_array['message'] = 'Something went wrong,please try again';
                            }
                        } else {
                            $json_array['status'] = ERROR_CODE;

                            $json_array['message'] = 'Something went wrong,please try again';
                        }

                    } else {
                        $json_array['status'] = ERROR_CODE;

                        $json_array['message'] = 'Something went wrong,please try again';
                    }

                } else {
                        $json_array['status'] = ERROR_CODE;

                        $json_array['message'] = 'Already Exists Company, From Date and To Date';
                }
            }
             echo_json($json_array);
        }
    }

    public function update_employment()
    {
        if ($this->input->is_ajax_request()) {

            $this->load->model('employment_model');

            $frm_details = $this->input->post();

            $this->form_validation->set_rules('clientid', 'Client', 'required');

            $this->form_validation->set_rules('candsid', 'Candidate', 'required');


            if ($this->form_validation->run() == false ) {

                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');

            } else {
              
                if(isset($frm_details['check_fresher']) && $frm_details['check_fresher'] == "on"){
                    $insuff_clear_date = date(DATE_ONLY);

                    $check = $this->employment_model->select_insuff(array('empverres_id' =>  $frm_details['update_id'], 'status !=' => 3, 'insuff_clear_date is null' => null));

                    if(!empty($check))
                    {
                        
                        $hold_days = getNetWorkDays($check[0]['insuff_raised_date'], $insuff_clear_date);

                        $date_tat = $this->employment_model->get_date_for_update(array('id' => $frm_details['update_id']));

                        $due_date = $date_tat[0]['due_date'];

                        $today = date("Y-m-d");

                        $get_holiday1 = $this->get_holiday();

                        $get_holiday = array_map('current', $get_holiday1);

                        $closed_date = getWorkingDays_increament($due_date, $get_holiday, $hold_days);

                        $x = 1;
                        $counter_1 = 0;
                        while ($x <= 10) {

                            $yesterday = date('Y-m-d', strtotime('-' . $x . ' day', strtotime($closed_date)));

                            $today_date1 = strtotime($yesterday);

                            $day_name1 = date("D", $today_date1);

                            if ($day_name1 == 'Sat' || $day_name1 == 'Sun' || in_array($yesterday, $get_holiday) == true) {

                                    $counter_1 += 1;
                                } else {
                                    break;
                                }

                                $x++;
                            }

                            $yesterday_main = $yesterday;

                            $y = 1;
                            $counter_2 = 0;
                            while ($y <= 10) {

                                $yesterday_ago = date('Y-m-d', strtotime('-' . $y . ' day', strtotime($yesterday_main)));

                                $today_date2 = strtotime($yesterday_ago);

                                $day_name2 = date("D", $today_date2);

                                if ($day_name2 == 'Sat' || $day_name2 == 'Sun' || in_array($yesterday_ago, $get_holiday) == true) {

                                    $counter_2 += 1;
                                } else {
                                    break;
                                }
                                $y++;
                            }

                            $yesterday_ago_main = $yesterday_ago;

                            $date_array = array($yesterday_main, $yesterday_ago_main);

                            if ($closed_date < $today) {
                                $new_tat = "OUT TAT";
                            } elseif ($closed_date == $today) {
                                $new_tat = "TDY TAT";
                            } elseif (in_array(date("Y-m-d"), $date_array) == true) {
                                $new_tat = "AP TAT";
                            } else {
                                $new_tat = "IN TAT";
                            }

                            $result_due_date = $this->employment_model->update_due_date(array('due_date' => $closed_date, 'tat_status' => $new_tat), array('id' => $frm_details['update_id']));

                            $fields = array('insuff_clear_date' => $insuff_clear_date, 'insuff_remarks' => "Candidate has tagged himself as a fresher", 'insuff_cleared_timestamp' => date(DB_DATE_FORMAT), 'status' => 2,  'auto_stamp' => 2, 'hold_days' => $hold_days);

                            $result = $this->employment_model->save_update_insuff($fields, array('empverres_id' => $frm_details['update_id']));

                            $result_empvrres = $this->employment_model->save_first_qc_result(array('verfstatus'=>28,'var_filter_status'=> "closed",'var_report_status'=> "NA",'remarks'=>"Candidate has tagged himself as a fresher"), array('empverid' => $frm_details['update_id']));

                            if ($result_empvrres) {

                                $json_array['status'] = SUCCESS_CODE;

                                $json_array['message'] = 'Employment Record Successfully Inserted';


                            } else {

                                $json_array['status'] = ERROR_CODE;

                                $json_array['message'] = 'Something went wrong,please try again';
                            }
                    
                    }
                }
                else{


                    $is_exit = $this->employment_model->check_cin_exist($frm_details['nameofthecompany']);

                    $is_company_exit = $this->employment_model->check_company_exist(str_replace("-", ' ', $frm_details['selected_company_name']));

                    if (!empty($is_exit)) {
                        $nameofthecompany = $is_exit[0]['id'];
                    } else if (!empty($is_company_exit)) {
                        $nameofthecompany = $is_company_exit[0]['id'];
                    } else {
                        $nameofthecompany = $this->employment_model->save_company_details(array('cin_number' => $frm_details['nameofthecompany'], 'coname' => str_replace('-', ' ', $frm_details['selected_company_name'])));
                    } 


                    $field_array = array('clientid' => $frm_details['clientid'],
                            'candsid' => $frm_details['candsid'],
                            'empid' => $frm_details['empid'],
                            'nameofthecompany' => $nameofthecompany,
                            'deputed_company' => $frm_details['deputed_company'],
                            'employment_type' => $frm_details['employment_type'],
                            'locationaddr' => $frm_details['locationaddr'],
                            'citylocality' => $frm_details['citylocality'],
                            'pincode' => $frm_details['pincode'],
                            'state' => $frm_details['state'],
                            'compant_contact' => $frm_details['compant_contact'],
                            'compant_contact_name' => $frm_details['compant_contact_name'],
                            'compant_contact_designation' => $frm_details['compant_contact_designation'],
                            'compant_contact_email' => $frm_details['compant_contact_email'],
                            'empfrom' => $frm_details['empfrom'],
                            'empto' => $frm_details['empto'],
                            'designation' => $frm_details['designation'],
                            'remuneration' => $frm_details['remuneration'],
                            "reasonforleaving" => $frm_details['reasonforleaving'],
                            "emp_re_open_date" => '',
                            'r_manager_name' => $frm_details['r_manager_name'],
                            'r_manager_no' => $frm_details['r_manager_no'],
                            'r_manager_designation' => $frm_details['r_manager_designation'],
                            'r_manager_email' => $frm_details['r_manager_email'],
                            
                    );

                    $lists = $this->employment_model->select_required_field(array('company_database.id' => $nameofthecompany));

                        $field_array['reject_status'] = 1;
                        if (!empty($lists[0])) {
                            if ($lists[0]['previous_emp_code'] == 1) {
                                if ($frm_details['empid'] == "") {
                                    $field_array['reject_status'] = 2;
                                }
                            }
                            if ($lists[0]['branch_location'] == 1) {
                                if ($frm_details['citylocality'] == "") {
                                    $field_array['reject_status'] = 2;
                                }
                            }
                               
                        }

                    $result = $this->employment_model->save(array_map('strtolower', $field_array),array('id'=> $frm_details['update_id']));                    

                    $total_rows = count(array_filter($frm_details['supervisor_name']));

                        $x = 0;
                        $supervisor_array = array();
                        while ($x < $total_rows) {
                            if ($frm_details['supervisor_name']) {
                                array_push($supervisor_array,
                                    array(
                                        'supervisor_name' => $frm_details['supervisor_name'][$x],
                                        'supervisor_designation' => $frm_details['supervisor_designation'][$x],
                                        'supervisor_contact_details' => $frm_details['supervisor_contact_details'][$x],
                                        'supervisor_email_id' => $frm_details['supervisor_email_id'][$x],
                                        'status' => 1,
                                        'created_on' => date(DB_DATE_FORMAT),
                                        'empver_id' =>  $frm_details['update_id'],
                                    ));
                            }
                            $x++;
                        } 


                        if (!empty($supervisor_array)) {
                            $this->employment_model->empver_supervisor_details($supervisor_array);
                        }

                        $error_msgs = array();
                        $file_upload_path = SITE_BASE_PATH . EMPLOYMENT . $frm_details['clientid'];
                        if (!folder_exist($file_upload_path)) {
                            mkdir($file_upload_path, 0777,true);
                        } else if (!is_writable($file_upload_path)) {
                            array_push($error_msgs, 'Problem while uploading');
                        }

                        $config_array = array('file_upload_path' => $file_upload_path, 'file_permission' => 'jpeg|jpg|png|pdf|docx|xls|xlsx', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 1000, 'file_id' => $frm_details['update_id'], 'component_name' => 'empver_id');

                        if (empty($error_msgs)) {
                            if ($_FILES['attchments']['name'][0] != '') {
                                $config_array['files_count'] = count($_FILES['attchments']['name']);
                                $config_array['file_data'] = $_FILES['attchments'];
                                $config_array['type'] = 0;
                                $retunr_de = $this->file_upload_multiple($config_array);
                                if (!empty($retunr_de)) {
                                    $this->common_model->common_insert_batch('empverres_files', $retunr_de['success']);
                                }
                            }

                        }


                        if ($result) {

                            $update_candidate_info = $this->employment_model->update_status('client_candidates_info',array('employment_component_check'=> '0'),array('cands_info_id'=> $frm_details['candsid']));

                            $insuff_clear_date = date(DATE_ONLY);

                            $check = $this->employment_model->select_insuff(array('empverres_id' =>  $frm_details['update_id'], 'status !=' => 3, 'insuff_clear_date is null' => null));

                            if(!empty($check))
                            {
                           
                               
                                $hold_days = getNetWorkDays($check[0]['insuff_raised_date'], $insuff_clear_date);

                                $date_tat = $this->employment_model->get_date_for_update(array('id' => $frm_details['update_id']));

                                $due_date = $date_tat[0]['due_date'];

                                $today = date("Y-m-d");

                                $get_holiday1 = $this->get_holiday();

                                $get_holiday = array_map('current', $get_holiday1);

                                $closed_date = getWorkingDays_increament($due_date, $get_holiday, $hold_days);

                                $x = 1;
                                $counter_1 = 0;
                                while ($x <= 10) {

                                    $yesterday = date('Y-m-d', strtotime('-' . $x . ' day', strtotime($closed_date)));

                                    $today_date1 = strtotime($yesterday);

                                    $day_name1 = date("D", $today_date1);

                                    if ($day_name1 == 'Sat' || $day_name1 == 'Sun' || in_array($yesterday, $get_holiday) == true) {

                                        $counter_1 += 1;
                                    } else {
                                        break;
                                    }

                                    $x++;
                                }

                                $yesterday_main = $yesterday;

                                $y = 1;
                                $counter_2 = 0;
                                while ($y <= 10) {

                                    $yesterday_ago = date('Y-m-d', strtotime('-' . $y . ' day', strtotime($yesterday_main)));

                                    $today_date2 = strtotime($yesterday_ago);

                                    $day_name2 = date("D", $today_date2);

                                    if ($day_name2 == 'Sat' || $day_name2 == 'Sun' || in_array($yesterday_ago, $get_holiday) == true) {

                                        $counter_2 += 1;
                                    } else {
                                        break;
                                    }
                                    $y++;
                                }

                                $yesterday_ago_main = $yesterday_ago;

                                $date_array = array($yesterday_main, $yesterday_ago_main);

                                if ($closed_date < $today) {
                                    $new_tat = "OUT TAT";
                                } elseif ($closed_date == $today) {
                                    $new_tat = "TDY TAT";
                                } elseif (in_array(date("Y-m-d"), $date_array) == true) {
                                    $new_tat = "AP TAT";
                                } else {
                                    $new_tat = "IN TAT";
                                }

                                $result_due_date = $this->employment_model->update_due_date(array('due_date' => $closed_date, 'tat_status' => $new_tat), array('id' => $frm_details['update_id']));

                                $fields = array('insuff_clear_date' => $insuff_clear_date, 'insuff_remarks' => "Candidate Fill Details", 'insuff_cleared_timestamp' => date(DB_DATE_FORMAT), 'status' => 2,  'auto_stamp' => 2, 'hold_days' => $hold_days);

                                $result = $this->employment_model->save_update_insuff($fields, array('empverres_id' => $frm_details['update_id']));
                            }

                            $component_status_update = $this->common_model->component_stat_update(array('employment_component_check'=>'0'),array('cands_info_id'=>$frm_details['candsid']));

                            auto_update_client_candidate_status($frm_details['candsid']); 
                            $this->component_save_candidate_mail_update('Employment',$frm_details); 


                            if ($result) {

                                $json_array['status'] = SUCCESS_CODE;

                                $json_array['message'] = 'Employment Record Successfully Inserted';


                            } else {

                                $json_array['status'] = ERROR_CODE;

                                $json_array['message'] = 'Something went wrong,please try again';
                            }
                       

                    } else {
                        $json_array['status'] = ERROR_CODE;

                        $json_array['message'] = 'Something went wrong,please try again';
                    }       

                }
            }
             echo_json($json_array);
        }
    }

    public function save_drugs()
    {
        if ($this->input->is_ajax_request()) {

            $frm_details = $this->input->post();
            
            $this->load->model('drug_verificatiion_model');

            
            $this->form_validation->set_rules('clientid', 'Client', 'required');

            $this->form_validation->set_rules('candsid', 'Candidate', 'required');

            if ($this->form_validation->run() == false ) {

                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');

            } else {

                    $tat_day = $this->get_client_entity_package_tat_day(array('tbl_clients_id' => $frm_details['clientid'], 'entity' => $frm_details['entity_id'], 'package' => $frm_details['package_id']));

                    $get_holiday1 = $this->get_holiday();

                    $get_holiday = array_map('current', $get_holiday1);

                    $closed_date = getWorkingDays(convert_display_to_db_date($frm_details['iniated_date']), $get_holiday, $tat_day[0]['tat_narcver']);

                    $has_case_id = $this->drug_verificatiion_model->get_reporting_manager_id($frm_details['clientid']);
                    
                        $field_array = array('clientid' => $frm_details['clientid'],
                            'candsid' => $frm_details['candsid'],
                            'drug_com_ref' => '',
                            'appointment_date' => convert_display_to_db_date($frm_details['appointment_date']),
                            'appointment_time' => $frm_details['appointment_time'],
                            'mode_of_veri' => $frm_details['mod_of_veri'],
                            'drug_test_code' => $frm_details['drug_test_code'],
                            'street_address' => $frm_details['street_address'],
                            'city' => $frm_details['city'],
                            'pincode' => $frm_details['pincode'],
                            'state' => $frm_details['state'],
                            'mode_of_veri' => $frm_details['mod_of_veri'],
                            'created_on' => date(DB_DATE_FORMAT),
                            'modified_on' => date(DB_DATE_FORMAT),
                            'is_bulk_uploaded' => 0,
                            'iniated_date' => convert_display_to_db_date($frm_details['iniated_date']),
                            "due_date" => $closed_date,
                            'has_case_id' => $has_case_id[0]['clientmgr'],
                            "tat_status" => 'IN TAT',
                            'fill_by' => 2,
                        );

                    $result = $this->drug_verificatiion_model->save(array_map('strtolower', $field_array));

                    $drugs_com_ref = $this->drug_com_ref($result);

                    $error_msgs = array();
                    $file_upload_path = SITE_BASE_PATH . DRUGS . $frm_details['clientid'];
                    if (!folder_exist($file_upload_path)) {
                        mkdir($file_upload_path, 0777,true);
                    } else if (!is_writable($file_upload_path)) {
                        array_push($error_msgs, 'Problem while uploading');
                    }

            
                    $config_array = array('file_upload_path' => $file_upload_path, 'file_permission' => 'jpeg|jpg|png|pdf|docx|xls|xlsx', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 1000, 'file_id' => $result, 'component_name' => 'drug_narcotis_id');
                    if (empty($error_msgs)) {
                        if ($_FILES['attchments']['name'][0] != '') {
                            $config_array['files_count'] = count($_FILES['attchments']['name']);
                            $config_array['file_data'] = $_FILES['attchments'];
                            $config_array['type'] = 0;
                            $retunr_de = $this->file_upload_multiple($config_array);
                            if (!empty($retunr_de)) {
                                $this->common_model->common_insert_batch('drug_narcotis_files', $retunr_de['success']);
                            }
                        }
                    }
                

                    if ($result) {

                    
                        $component_status_update = $this->common_model->component_stat_update(array('drugs_component_check'=>'0'),array('cands_info_id'=>$frm_details['candsid']));

                        auto_update_client_candidate_status($frm_details['candsid']);
                        $this->component_save_candidate_mail_update('Drugs',$frm_details);  

                        $json_array['status'] = SUCCESS_CODE;

                        $json_array['message'] = 'Drugs Record Successfully Inserted';

                               
                    } else {
                        $json_array['status'] = ERROR_CODE;

                        $json_array['message'] = 'Something went wrong,please try again';
                    }   
            }
             echo_json($json_array);
        }
    }

    public function update_drugs()
    {
        if ($this->input->is_ajax_request()) {

            $frm_details = $this->input->post();
            
            $this->load->model('drug_verificatiion_model');

            
            $this->form_validation->set_rules('clientid', 'Client', 'required');

            $this->form_validation->set_rules('candsid', 'Candidate', 'required');

            if ($this->form_validation->run() == false ) {

                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');

            } else {

                    $tat_day = $this->get_client_entity_package_tat_day(array('tbl_clients_id' => $frm_details['clientid'], 'entity' => $frm_details['entity_id'], 'package' => $frm_details['package_id']));

                    $get_holiday1 = $this->get_holiday();

                    $get_holiday = array_map('current', $get_holiday1);

                    $closed_date = getWorkingDays(convert_display_to_db_date($frm_details['iniated_date']), $get_holiday, $tat_day[0]['tat_narcver']);

                    $has_case_id = $this->drug_verificatiion_model->get_reporting_manager_id($frm_details['clientid']);
                    
                        $field_array = array('clientid' => $frm_details['clientid'],
                            'candsid' => $frm_details['candsid'],
                            'appointment_date' => convert_display_to_db_date($frm_details['appointment_date']),
                            'appointment_time' => $frm_details['appointment_time'],
                            'mode_of_veri' => $frm_details['mod_of_veri'],
                            'drug_test_code' => $frm_details['drug_test_code'],
                            'street_address' => $frm_details['street_address'],
                            'city' => $frm_details['city'],
                            'pincode' => $frm_details['pincode'],
                            'state' => $frm_details['state'],
                            'mode_of_veri' => $frm_details['mod_of_veri'],
                            'created_on' => date(DB_DATE_FORMAT),
                            'modified_on' => date(DB_DATE_FORMAT),
                            'is_bulk_uploaded' => 0,
                            'iniated_date' => convert_display_to_db_date($frm_details['iniated_date']),
                            "due_date" => $closed_date,
                            'has_case_id' => $has_case_id[0]['clientmgr'],
                            "tat_status" => 'IN TAT',
                            'fill_by' => 2,
                        );

                    $result = $this->drug_verificatiion_model->save(array_map('strtolower', $field_array),array('id'=> $frm_details['update_id']));

                    $error_msgs = array();
                    $file_upload_path = SITE_BASE_PATH . DRUGS . $frm_details['clientid'];
                    if (!folder_exist($file_upload_path)) {
                        mkdir($file_upload_path, 0777,true);
                    } else if (!is_writable($file_upload_path)) {
                        array_push($error_msgs, 'Problem while uploading');
                    }

            
                    $config_array = array('file_upload_path' => $file_upload_path, 'file_permission' => 'jpeg|jpg|png|pdf|docx|xls|xlsx', 'file_size' => BULK_UPLOAD_MAX_SIZE_MB * 1000, 'file_id' => $frm_details['update_id'], 'component_name' => 'drug_narcotis_id');
                    if (empty($error_msgs)) {
                        if ($_FILES['attchments']['name'][0] != '') {
                            $config_array['files_count'] = count($_FILES['attchments']['name']);
                            $config_array['file_data'] = $_FILES['attchments'];
                            $config_array['type'] = 0;
                            $retunr_de = $this->file_upload_multiple($config_array);
                            if (!empty($retunr_de)) {
                                $this->common_model->common_insert_batch('drug_narcotis_files', $retunr_de['success']);
                            }
                        }
                    }
                

                    if ($result) {

                           $component_status_update = $this->common_model->component_stat_update(array('drugs_component_check'=>'0'),array('cands_info_id'=>$frm_details['candsid']));

                            $insuff_clear_date = date(DATE_ONLY);

                            $check = $this->drug_verificatiion_model->select_insuff(array('drug_narcotis_id' =>  $frm_details['update_id'], 'status !=' => 3, 'insuff_clear_date is null' => null));

                            if(!empty($check))
                            {
                           
                               
                                $hold_days = getNetWorkDays($check[0]['insuff_raised_date'], $insuff_clear_date);

                                $date_tat = $this->drug_verificatiion_model->get_date_for_update(array('id' => $frm_details['update_id']));

                                $due_date = $date_tat[0]['due_date'];

                                $today = date("Y-m-d");

                                $get_holiday1 = $this->get_holiday();

                                $get_holiday = array_map('current', $get_holiday1);

                                $closed_date = getWorkingDays_increament($due_date, $get_holiday, $hold_days);

                                $x = 1;
                                $counter_1 = 0;
                                while ($x <= 10) {

                                    $yesterday = date('Y-m-d', strtotime('-' . $x . ' day', strtotime($closed_date)));

                                    $today_date1 = strtotime($yesterday);

                                    $day_name1 = date("D", $today_date1);

                                    if ($day_name1 == 'Sat' || $day_name1 == 'Sun' || in_array($yesterday, $get_holiday) == true) {

                                        $counter_1 += 1;
                                    } else {
                                        break;
                                    }

                                    $x++;
                                }

                                $yesterday_main = $yesterday;

                                $y = 1;
                                $counter_2 = 0;
                                while ($y <= 10) {

                                    $yesterday_ago = date('Y-m-d', strtotime('-' . $y . ' day', strtotime($yesterday_main)));

                                    $today_date2 = strtotime($yesterday_ago);

                                    $day_name2 = date("D", $today_date2);

                                    if ($day_name2 == 'Sat' || $day_name2 == 'Sun' || in_array($yesterday_ago, $get_holiday) == true) {

                                        $counter_2 += 1;
                                    } else {
                                        break;
                                    }
                                    $y++;
                                }

                                $yesterday_ago_main = $yesterday_ago;

                                $date_array = array($yesterday_main, $yesterday_ago_main);

                                if ($closed_date < $today) {
                                    $new_tat = "OUT TAT";
                                } elseif ($closed_date == $today) {
                                    $new_tat = "TDY TAT";
                                } elseif (in_array(date("Y-m-d"), $date_array) == true) {
                                    $new_tat = "AP TAT";
                                } else {
                                    $new_tat = "IN TAT";
                                }

                                $result_due_date = $this->drug_verificatiion_model->update_due_date(array('due_date' => $closed_date, 'tat_status' => $new_tat), array('id' => $frm_details['update_id']));

                                $fields = array('insuff_clear_date' => $insuff_clear_date, 'insuff_remarks' => "Candidate Fill Details", 'insuff_cleared_timestamp' => date(DB_DATE_FORMAT), 'status' => 2,  'auto_stamp' => 2, 'hold_days' => $hold_days);

                                $result = $this->drug_verificatiion_model->save_update_insuff($fields, array('drug_narcotis_id' => $frm_details['update_id']));
                            }

                              $component_status_update = $this->common_model->component_stat_update(array('drugs_component_check'=>'0'),array('cands_info_id'=>$frm_details['candsid']));

                            auto_update_client_candidate_status($frm_details['candsid']); 
                            
                            $this->component_save_candidate_mail_update('Drugs',$frm_details);  

                        $json_array['status'] = SUCCESS_CODE;

                        $json_array['message'] = 'Drugs Record Successfully Inserted';

                               
                    } else {
                        $json_array['status'] = ERROR_CODE;

                        $json_array['message'] = 'Something went wrong,please try again';
                    }   
            }
             echo_json($json_array);
        }
    }


    protected function drug_com_ref($insert_id)
    {
        $this->load->model('drug_verificatiion_model');

  
        $name = COMPONENT_REF_NO['DRUGS'];

        $drugsnumber = $name . $insert_id;

        $field_array = array('drug_com_ref' => $drugsnumber);

        $update_auto_increament_id = $this->drug_verificatiion_model->update_auto_increament_value($field_array, array('id' => $insert_id));

        return $drugsnumber;
    }



    public function load_supervisor_details($sts, $counter)
    {
        if ($this->input->is_ajax_request()) {

            $this->load->model('employment_model');

            $empver_id = $this->input->post('details_fields');

            $data['count'] = $counter;
            $data['disabled'] = ($sts == 'dis') ? 'disabled' : '';
            if ($empver_id) {

                $result = $this->employment_model->supervison_details(array('empver_id' => $empver_id));

                $data['total_count'] = count($result);

                $data['superv_details'] = $result;
            }

            echo $this->load->view('admin/supervisor_details_view', $data, true);
        } else {
            echo "<h2>Something went wrong, please try again!</h2>";
        }
    }



    public function get_company_details()
    {
        $this->load->model('employment_model');

        $get_company_name = $_REQUEST['q']['term'];

        if ($this->input->is_ajax_request()) {
            $api_details = $this->employment_model->get_company_name($get_company_name);

            if (empty($api_details[0])) {

                $api_details = $this->fetch_company_details($get_company_name);
            }
        }
        echo_json($api_details);
    }

    public function fetch_company_details($requested_term)
    {

        $company_name = fetch_company_details($requested_term);

        if (!empty($company_name)) {

            $json_array = $company_name;
        } else {
            $json_array = array(array("id" => '100', "company_name" => 'Other'));
        }

        return $json_array;

    }

    public function get_required_fields()
    {
        if ($this->input->is_ajax_request()) {

            $this->load->model('employment_model');

            $company_name = $this->input->post('company_name');

            $lists = $this->employment_model->select_required_field(array('company_database.id' => $company_name));

            if (!empty($lists)) {
                echo_json($lists[0]);
            } else {
                echo_json('false');
            }
        }
    }

    public function addAddCompanyModel()
    {
        log_message('error', 'Add Company Model'); 
        try {
            if ($this->input->is_ajax_request()) {
                $data['states'] = $this->get_states();
                echo $this->load->view('admin/addAddCompanyModel', $data, true);
            } else {
                echo "<p>We're sorry, but you do not have access to this page.</p>";
            }
        } catch (Exception $e) {
            log_message('error', 'Error on Company_database::addAddCompanyModel');
            log_message('error', $e->getMessage());
        }
    }
    public function save_company()
    {
        if ($this->input->is_ajax_request()) {

            log_message('error', 'Campany Save'); 
            try {
                $this->load->model('company_database_model');

                $coname = $this->input->post('coname');

                $this->form_validation->set_rules('coname', 'Company Name', 'required|is_unique[company_database.coname]');

                $this->form_validation->set_rules('address', 'Address', 'required');

                $this->form_validation->set_rules('city', 'City', 'required|alpha_numeric_spaces');

                $this->form_validation->set_rules('pincode', 'Pincode', 'required|numeric|min_length[6]|max_length[6]');

                $this->form_validation->set_rules('state', 'State', 'required');

                if ($this->form_validation->run() == false) {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = validation_errors('', '');
            
                    if($json_array['message'] == "The Company Name field must contain a unique value.\n")
                    {
                      
                      $insert_id = $this->company_database_model->save_company(array('dropdown_status'=> 1),array('coname'=> $coname)); 
                       $json_array['status'] = SUCCESS_CODE;
                       $json_array['message'] = 'Added Successfully';
                       $json_array['insert_id'] = $insert_id;
                       $json_array['coname'] = $coname;  
                    }
                } else {
                    $frm_details = $this->input->post();

                    $field_array = array("coname" => $frm_details["coname"],
                        "address" => $frm_details["address"],
                        "city" => $frm_details["city"],
                        "pincode" => $frm_details["pincode"],
                        "co_email_id" => $frm_details["co_email_id"],
                        "cc_email_id" => $frm_details["cc_email_id"],
                        "state" => $frm_details["state"],
                        'created_on' => date(DB_DATE_FORMAT),
                     
                        'modified_on' => date(DB_DATE_FORMAT),
                        'status' => STATUS_ACTIVE,
                    );

                    $field_array = array_map('strtolower', $field_array);
                    $insert_id = $this->company_database_model->save_company($field_array);
                    if ($insert_id) {
                        $json_array['status'] = SUCCESS_CODE;

                        $json_array['message'] = 'Added Successfully';

                        $json_array['insert_id'] = $insert_id;
                        $json_array['coname'] = $coname;

                        $json_array['redirect'] = ADMIN_SITE_URL . 'company_database';
                    } else {
                        $json_array['insert_id'] = 0;

                        $json_array['status'] = ERROR_CODE;

                        $json_array['message'] = 'Something went wrong,please try again';
                    }
                }
                echo_json($json_array);

            } catch (Exception $e) {
            log_message('error', 'Error on Company_database::save_company');
            log_message('error', $e->getMessage());
            }     
        }
    }

    public function save_court($values)
    {
        
        $this->load->model('court_verificatiion_model');
            
        $frm_details = $values;
       
        $court_id = $this->court_verificatiion_model->check_court_exists_in_candidate(array('street_address'  => $frm_details['address'],'candsid' => $frm_details['candsid']));

        if(empty($court_id))
        {
                  
            $work = $this->common_model->get_scope_of_work(array('tbl_clients_id' => $frm_details['clientid'], 'entity' => $frm_details['entity_id'], 'package' => $frm_details['package_id']));

            if(!empty($work))
            {
                $scope_of_work =  (json_decode($work[0]['scope_of_work'])->courtver);
            }
             
            if((isset($scope_of_work) && $scope_of_work == "all") || ($scope_of_work ==  $frm_details['address_type']) || ($frm_details['address_type'] == "current/permanent"))
            {
                 
                
                $tat_day = $this->get_client_entity_package_tat_day(array('tbl_clients_id' => $frm_details['clientid'], 'entity' => $frm_details['entity_id'], 'package' => $frm_details['package_id']));

                $get_holiday1 = $this->get_holiday();

                $get_holiday = array_map('current', $get_holiday1);

                $closed_date = getWorkingDays(convert_display_to_db_date($frm_details['iniated_date']), $get_holiday, $tat_day[0]['tat_courtver']);

                $has_case_id = $this->court_verificatiion_model->get_reporting_manager_id($frm_details['clientid']);
                 
                $mode_of_verification = $this->court_verificatiion_model->get_mode_of_verification(array('entity' => $frm_details['entity_id'], 'package' => $frm_details['package_id'], 'tbl_clients_id' => $frm_details['clientid']));
         
                if(!empty($mode_of_verification))
                {
                  $mode_of_verification_value = json_decode($mode_of_verification[0]['mode_of_verification']);
                } 

                if(isset($mode_of_verification_value->courtver)) 
                {
                    $mode_of_verification_value =  $mode_of_verification_value->courtver; 
                }
                else
                {
                    $mode_of_verification_value = "";
                }
            

                $field_array = array('clientid' => $frm_details['clientid'],
                    'candsid' => $frm_details['candsid'],
                    'court_com_ref' => '',
                    'address_type' => $frm_details['address_type'],
                    'street_address' => $frm_details['address'],
                    'city' => $frm_details['city'],
                    'pincode' => $frm_details['pincode'],
                    'state' => $frm_details['state'],
                    'mode_of_veri' => $mode_of_verification_value,
                    'created_on' => date(DB_DATE_FORMAT),
                    'modified_on' => date(DB_DATE_FORMAT),
                    'is_bulk_uploaded' => 0,
                    'iniated_date' => convert_display_to_db_date($frm_details['iniated_date']),
                    'has_case_id' => $has_case_id[0]['clientmgr'],
                    "due_date" => $closed_date,
                    "tat_status" => 'IN TAT',
                    'fill_by' => 2,
                );
           
                $result = $this->court_verificatiion_model->save(array_map('strtolower', $field_array));

                $court_com_ref = $this->court_com_ref($result);

                if($result)
                {

                    $component_status_update = $this->common_model->component_stat_update(array('court_component_check'=>'0'),array('cands_info_id'=>$frm_details['candsid']));

                    auto_update_client_candidate_status($frm_details['candsid']); 
                    $this->component_save_candidate_mail_update('Court',$frm_details); 

                }
            }
        }
       
    }
    
    protected function court_com_ref($insert_id)
    {

        $this->load->model('court_verificatiion_model');

        $name = COMPONENT_REF_NO['COURT'];

        $courtnumber = $name . $insert_id;

        $field_array = array('court_com_ref' => $courtnumber);

        $update_auto_increament_id = $this->court_verificatiion_model->update_auto_increament_value($field_array, array('id' => $insert_id));

        return $courtnumber;
    }

    public function save_global($values)
    {
        $this->load->model('Global_database_model');

        $frm_details = $values;
       
        $global_id = $this->Global_database_model->check_global_exists_in_candidate(array('street_address'  => $frm_details['address'],'candsid' => $frm_details['candsid']));

        if(empty($global_id))
        {
                  
            $work = $this->common_model->get_scope_of_work(array('tbl_clients_id' => $frm_details['clientid'], 'entity' => $frm_details['entity_id'], 'package' => $frm_details['package_id']));

            if(!empty($work))
            {
                $scope_of_work =  (json_decode($work[0]['scope_of_work'])->globdbver);
            }
               
            if((isset($scope_of_work) && $scope_of_work == "all") || ($scope_of_work ==  $frm_details['address_type']) || ($frm_details['address_type'] == "current/permanent"))
            {
                 
                $tat_day = $this->get_client_entity_package_tat_day(array('tbl_clients_id' => $frm_details['clientid'], 'entity' => $frm_details['entity_id'], 'package' => $frm_details['package_id']));

                $get_holiday1 = $this->get_holiday();

                $get_holiday = array_map('current', $get_holiday1);

                $closed_date = getWorkingDays(convert_display_to_db_date($frm_details['iniated_date']), $get_holiday, $tat_day[0]['tat_globdbver']);

                $has_case_id = $this->Global_database_model->get_reporting_manager_id($frm_details['clientid']);

                $mode_of_verification = $this->Global_database_model->get_mode_of_verification(array('entity' => $frm_details['entity_id'], 'package' => $frm_details['package_id'], 'tbl_clients_id' => $frm_details['clientid']));
         
                if(!empty($mode_of_verification))
                {
                  $mode_of_verification_value = json_decode($mode_of_verification[0]['mode_of_verification']);
                } 

                if(isset($mode_of_verification_value->courtver)) 
                {
                    $mode_of_verification_value =  $mode_of_verification_value->globdbver; 
                }
                else
                {
                    $mode_of_verification_value = "";
                }

                $field_array = array('clientid' => $frm_details['clientid'],
                    'candsid' => $frm_details['candsid'],
                    'global_com_ref' => '',
                    "address_type" => $frm_details['address_type'],
                    'street_address' => $frm_details['address'],
                    'city' => $frm_details['city'],
                    'pincode' => $frm_details['pincode'],
                    'state' => $frm_details['state'],
                    'mode_of_veri' => $mode_of_verification_value,
                    'created_on' => date(DB_DATE_FORMAT),
                    'modified_on' => date(DB_DATE_FORMAT),
                    'is_bulk_uploaded' => 0,
                    'has_case_id' =>  $has_case_id[0]['clientmgr'],
                    'iniated_date' => convert_display_to_db_date($frm_details['iniated_date']),
                    "due_date" => $closed_date,
                    "tat_status" => 'IN TAT',
                    'fill_by' => 2,
                );

                $result = $this->Global_database_model->save(array_map('strtolower', $field_array));

                $global_com_ref = $this->global_com_ref($result);
                
                if ($result) {

                    $component_status_update = $this->common_model->component_stat_update(array('global_component_check'=>'0'),array('cands_info_id'=>$frm_details['candsid']));

                    auto_update_client_candidate_status($frm_details['candsid']);
                    $this->component_save_candidate_mail_update('Global Database',$frm_details); 

                }
            }
        }               
    }

    public function update_global($values)
    {
        
        $this->load->model('Global_database_model');
            
        $frm_details = $values;

        $work = $this->common_model->get_scope_of_work(array('tbl_clients_id' => $frm_details['clientid'], 'entity' => $frm_details['entity_id'], 'package' => $frm_details['package_id']));

        if(!empty($work))
        {
            $scope_of_work =  (json_decode($work[0]['scope_of_work'])->globdbver);
        }

        
        if((isset($scope_of_work) && $scope_of_work == "all") || ($scope_of_work ==  $frm_details['address_type']) || ($frm_details['address_type'] == "current/permanent"))
        {

            $field_array = array(
                                
                'address_type' => $frm_details['address_type'],
                'street_address' => $frm_details['address'],
                'city' => $frm_details['city'],
                'pincode' => $frm_details['pincode'],
                'state' => $frm_details['state']
            );
                        
            $result = $this->Global_database_model->save(array_map('strtolower', $field_array),array('candsid'=>$frm_details['candsid']));
        
            if ($result) {
                                
                $update_candidate_info = $this->Global_database_model->update_status('client_candidates_info',array('global_component_check'=> '0'),array('cands_info_id'=> $frm_details['candsid']));
                            

                $insuff_clear_date = date(DATE_ONLY);

                $global_id = $this->Global_database_model->check_global_exists_in_candidate(array('candsid' =>  $frm_details['candsid']));
                


                $check = $this->Global_database_model->select_insuff(array('glodbver_id' =>  $global_id[0]['id'], 'status !=' => 3, 'insuff_clear_date is null' => null));

                if(!empty($check))
                {
                            
                    $hold_days = getNetWorkDays($check[0]['insuff_raised_date'],$insuff_clear_date);

                    $date_tat = $this->Global_database_model->get_date_for_update(array('id' => $global_id[0]['id']));

                    $due_date = $date_tat[0]['due_date'];

                    $today = date("Y-m-d");

                    $get_holiday1 = $this->get_holiday();

                    $get_holiday = array_map('current', $get_holiday1);

                    $closed_date = getWorkingDays_increament($due_date, $get_holiday, $hold_days);

                    $x = 1;
                    $counter_1 = 0;
                        
                        while ($x <= 10) {

                            $yesterday = date('Y-m-d', strtotime('-' . $x . ' day', strtotime($closed_date)));

                            $today_date1 = strtotime($yesterday);

                            $day_name1 = date("D", $today_date1);

                            if ($day_name1 == 'Sat' || $day_name1 == 'Sun' || in_array($yesterday, $get_holiday) == true) {
                                $counter_1 += 1;
                            } else {
                                break;
                            }
                                $x++;
                        }

                        $yesterday_main = $yesterday;

                        $y = 1;
                        $counter_2 = 0;
                        while ($y <= 10) {

                            $yesterday_ago = date('Y-m-d', strtotime('-' . $y . ' day', strtotime($yesterday_main)));

                            $today_date2 = strtotime($yesterday_ago);

                            $day_name2 = date("D", $today_date2);

                            if ($day_name2 == 'Sat' || $day_name2 == 'Sun' || in_array($yesterday_ago, $get_holiday) == true) {
                                $counter_2 += 1;
                            } else {
                                break;
                            }
                            $y++;
                        }

                        $yesterday_ago_main = $yesterday_ago;

                        $date_array = array($yesterday_main, $yesterday_ago_main);

                            if ($closed_date < $today) {
                                $new_tat = "OUT TAT";
                            } elseif ($closed_date == $today) {
                                $new_tat = "TDY TAT";
                            } elseif (in_array(date("Y-m-d"), $date_array) == true) {
                                $new_tat = "AP TAT";
                            } else {
                                $new_tat = "IN TAT";
                            }

                        $result_due_date = $this->Global_database_model->update_due_date(array('due_date' => $closed_date, 'tat_status' => $new_tat), array('id' => $global_id[0]['id']));

                        $fields = array('insuff_clear_date' => $insuff_clear_date, 'insuff_remarks' => "Candidate Fill Global", 'insuff_cleared_timestamp' => date(DB_DATE_FORMAT), 'status' => 2,  'auto_stamp' => 2, 'hold_days' => $hold_days);

                        $result = $this->Global_database_model->save_update_insuff($fields, array('glodbver_id' => $global_id[0]['id']));
                }
                            
                $component_status_update = $this->common_model->component_stat_update(array('global_component_check'=>'0'),array('cands_info_id'=>$frm_details['candsid']));

                auto_update_client_candidate_status($frm_details['candsid']);

                $this->component_save_candidate_mail_update('Global Database',$frm_details); 
            }
        }
       
    }


    protected function global_com_ref($insert_id)
    {
        $this->load->model('Global_database_model');

        $name = COMPONENT_REF_NO['GLOBAL'];

        $globalnumber = $name . $insert_id;

        $field_array = array('global_com_ref' => $globalnumber);

        $update_auto_increament_id = $this->Global_database_model->update_auto_increament_value($field_array, array('id' => $insert_id));

        return $globalnumber;
    }

    public function save_pcc($values)
    {
       
        $this->load->model('Pcc_verificatiion_model');

        $frm_details = $values;

        $pcc_id = $this->Pcc_verificatiion_model->check_pcc_exists_in_candidate(array('street_address'  => $frm_details['address'],'candsid' => $frm_details['candsid']));

        if(empty($pcc_id))
        {
                  
            $work = $this->common_model->get_scope_of_work(array('tbl_clients_id' => $frm_details['clientid'], 'entity' => $frm_details['entity_id'], 'package' => $frm_details['package_id']));

            if(!empty($work))
            {
                $scope_of_work =  (json_decode($work[0]['scope_of_work'])->crimver);
            }
               
            if((isset($scope_of_work) && $scope_of_work == "all") || ($scope_of_work ==  $frm_details['address_type']) || ($frm_details['address_type'] == "current/permanent"))
            {
             
                $tat_day = $this->get_client_entity_package_tat_day(array('tbl_clients_id' => $frm_details['clientid'], 'entity' => $frm_details['entity_id'], 'package' => $frm_details['package_id']));

                $get_holiday1 = $this->get_holiday();

                $get_holiday = array_map('current', $get_holiday1);

                $closed_date = getWorkingDays(convert_display_to_db_date($frm_details['iniated_date']), $get_holiday, $tat_day[0]['tat_crimver']);

                $has_case_id = $this->Pcc_verificatiion_model->get_reporting_manager_id($frm_details['clientid']);

                $mode_of_verification = $this->Pcc_verificatiion_model->get_mode_of_verification(array('entity' => $frm_details['entity_id'], 'package' => $frm_details['package_id'], 'tbl_clients_id' => $frm_details['clientid']));
         
                if(!empty($mode_of_verification))
                {
                  $mode_of_verification_value = json_decode($mode_of_verification[0]['mode_of_verification']);
                } 

                if(isset($mode_of_verification_value->courtver)) 
                {
                    $mode_of_verification_value =  $mode_of_verification_value->crimver; 
                }
                else
                {
                    $mode_of_verification_value = "";
                }

                    $field_array = array('clientid' => $frm_details['clientid'],
                        'candsid' => $frm_details['candsid'],
                        'pcc_com_ref' => '',
                        'street_address' => $frm_details['address'],
                        'mode_of_veri' =>  $mode_of_verification_value,
                        'address_type' => $frm_details['address_type'],
                        'city' => $frm_details['city'],
                        'pincode' => $frm_details['pincode'],
                        'state' => $frm_details['state'],

                        'created_on' => date(DB_DATE_FORMAT),
                        'modified_on' => date(DB_DATE_FORMAT),
                        'is_bulk_uploaded' => 0,
                        'iniated_date' => convert_display_to_db_date($frm_details['iniated_date']),
                        'has_case_id' => $has_case_id[0]['clientmgr'],
                        "due_date" => $closed_date,
                        "tat_status" => 'IN TAT',
                        'fill_by' => 2,
                    );

                $result = $this->Pcc_verificatiion_model->save(array_map('strtolower', $field_array));

                $pcc_com_ref = $this->pcc_com_ref($result);
 
                if ($result) {
    
                    $component_status_update = $this->common_model->component_stat_update(array('pcc_component_check'=>'0'),array('cands_info_id'=>$frm_details['candsid']));

                    auto_update_client_candidate_status($frm_details['candsid']);
                    $this->component_save_candidate_mail_update('PCC',$frm_details); 
                }
            }
        
        }
    }

    public function update_pcc($values)
    {
        
        $this->load->model('Pcc_verificatiion_model');
            
        $frm_details = $values;

        $work = $this->common_model->get_scope_of_work(array('tbl_clients_id' => $frm_details['clientid'], 'entity' => $frm_details['entity_id'], 'package' => $frm_details['package_id']));

        if(!empty($work))
        {
            $scope_of_work =  (json_decode($work[0]['scope_of_work'])->crimver);
        }

        if((isset($scope_of_work) && $scope_of_work == "all") || ($scope_of_work ==  $frm_details['address_type']) || ($frm_details['address_type'] == "current/permanent"))
        {

            $field_array = array(
                                
                'address_type' => $frm_details['address_type'],
                'street_address' => $frm_details['address'],
                'city' => $frm_details['city'],
                'pincode' => $frm_details['pincode'],
                'state' => $frm_details['state']
            );
                        
            $result = $this->Pcc_verificatiion_model->save(array_map('strtolower', $field_array),array('candsid'=>$frm_details['candsid']));
        
            if ($result) {
                                
                $update_candidate_info = $this->Pcc_verificatiion_model->update_status('client_candidates_info',array('pcc_component_check'=> '0'),array('cands_info_id'=> $frm_details['candsid']));
                            

                $insuff_clear_date = date(DATE_ONLY);

                $pcc_id = $this->Pcc_verificatiion_model->check_pcc_exists_in_candidate( array('candsid' =>  $frm_details['candsid']));


                $check = $this->Pcc_verificatiion_model->select_insuff(array('pcc_id' =>  $pcc_id[0]['id'], 'status !=' => 3, 'insuff_clear_date is null' => null));

                if(!empty($check))
                {
                            
                    $hold_days = getNetWorkDays($check[0]['insuff_raised_date'],$insuff_clear_date);

                    $date_tat = $this->Pcc_verificatiion_model->get_date_for_update(array('id' => $pcc_id[0]['id']));

                    $due_date = $date_tat[0]['due_date'];

                    $today = date("Y-m-d");

                    $get_holiday1 = $this->get_holiday();

                    $get_holiday = array_map('current', $get_holiday1);

                    $closed_date = getWorkingDays_increament($due_date, $get_holiday, $hold_days);

                    $x = 1;
                    $counter_1 = 0;
                        
                        while ($x <= 10) {

                            $yesterday = date('Y-m-d', strtotime('-' . $x . ' day', strtotime($closed_date)));

                            $today_date1 = strtotime($yesterday);

                            $day_name1 = date("D", $today_date1);

                            if ($day_name1 == 'Sat' || $day_name1 == 'Sun' || in_array($yesterday, $get_holiday) == true) {
                                $counter_1 += 1;
                            } else {
                                break;
                            }
                                $x++;
                        }

                        $yesterday_main = $yesterday;

                        $y = 1;
                        $counter_2 = 0;
                        while ($y <= 10) {

                            $yesterday_ago = date('Y-m-d', strtotime('-' . $y . ' day', strtotime($yesterday_main)));

                            $today_date2 = strtotime($yesterday_ago);

                            $day_name2 = date("D", $today_date2);

                            if ($day_name2 == 'Sat' || $day_name2 == 'Sun' || in_array($yesterday_ago, $get_holiday) == true) {
                                $counter_2 += 1;
                            } else {
                                break;
                            }
                            $y++;
                        }

                        $yesterday_ago_main = $yesterday_ago;

                        $date_array = array($yesterday_main, $yesterday_ago_main);

                            if ($closed_date < $today) {
                                $new_tat = "OUT TAT";
                            } elseif ($closed_date == $today) {
                                $new_tat = "TDY TAT";
                            } elseif (in_array(date("Y-m-d"), $date_array) == true) {
                                $new_tat = "AP TAT";
                            } else {
                                $new_tat = "IN TAT";
                            }

                        $result_due_date = $this->Pcc_verificatiion_model->update_due_date(array('due_date' => $closed_date, 'tat_status' => $new_tat), array('id' => $pcc_id[0]['id']));

                        $fields = array('insuff_clear_date' => $insuff_clear_date, 'insuff_remarks' => "Candidate Fill PCC", 'insuff_cleared_timestamp' => date(DB_DATE_FORMAT), 'status' => 2,  'auto_stamp' => 2, 'hold_days' => $hold_days);

                        $result = $this->Pcc_verificatiion_model->save_update_insuff($fields, array('pcc_id' => $pcc_id[0]['id']));
                }
                            
                $component_status_update = $this->common_model->component_stat_update(array('pcc_component_check'=>'0'),array('cands_info_id'=>$frm_details['candsid']));

                auto_update_client_candidate_status($frm_details['candsid']);

                $this->component_save_candidate_mail_update('PCC',$frm_details); 
            }
        }
       
    }


    protected function pcc_com_ref($insert_id)
    {
        $this->load->model('Pcc_verificatiion_model');

        $name = COMPONENT_REF_NO['PCC'];

        $pccnumber = $name . $insert_id;

        $field_array = array('pcc_com_ref' => $pccnumber);

        $update_auto_increament_id = $this->Pcc_verificatiion_model->update_auto_increament_value($field_array, array('id' => $insert_id));

        return $pccnumber;
    }



    public function component_save_candidate_mail_update($component_name,$component_details)
    {
        $this->load->model('client/candidates_model');

        if (!empty($component_name) && !empty($component_details)) {

            $details = $this->candidates_model->select_mail_details(array('client_candidates_info.cands_info_id'=>$component_details['candsid']));
       
            $client_manager_id = $this->candidates_model->select_client_manager_details(array('clients.id'=> $component_details['clientid']));
     
            $client_manager_email = $this->candidates_model->select_user_info($client_manager_id[0]['clientmgr']);
                   

            $email_tmpl_data['from_email'] = VERIFICATIONEMAIL;

            $email_tmpl_data['to_emails'] = $client_manager_email[0]['email'].','.VERIFICATIONEMAIL;

            $email_tmpl_data['cc_emails'] = $details[0]['cands_email_id'].','.$this->client_info['email_id'].','.FROMEMAIL;

            $email_tmpl_data['component_name'] = $component_name;
   
            $email_tmpl_data['cands_name'] = $details[0]['CandidateName'];

            $email_tmpl_data['clientname'] = $details[0]['clientname'];

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

            $result = $this->email->candidate_submit_comp($email_tmpl_data);

            return $result;
        }
    }

}

?>